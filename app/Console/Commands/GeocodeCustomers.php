<?php

namespace App\Console\Commands;

use App\Customer;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GeocodeCustomers extends Command
{
    protected $signature = 'customers:geocode';
    protected $description = 'Geocode customer addresses worldwide using Nominatim API';

    public function handle()
    {
        $client = new Client([
            'base_uri' => 'https://nominatim.openstreetmap.org',
            'timeout' => 15.0, // Timeout aumentado para respuestas internacionales
            'headers' => [
                'User-Agent' => 'YourAppName/1.0 (your@email.com)', // REQUERIDO: Cambia esto
                'Accept-Language' => 'en', // Idioma básico para respuestas
                'Referer' => config('app.url')
            ]
        ]);
        
        // Solo clientes sin coordenadas
        $customers = Customer::whereNull('latitude')
                            ->orWhereNull('longitude')
                            ->get();

        foreach ($customers as $customer) {
            // Usamos la dirección exacta como está en la base de datos
            $address = trim($customer->address);
            
            $this->info("\nProcesando cliente #{$customer->id}: {$customer->customer_name}");
            $this->line("Dirección completa: {$address}");
            $this->line("País: " . $this->guessCountryFromAddress($address));

            try {
                $response = $client->get('/search', [
                    'query' => [
                        'q' => $address,
                        'format' => 'json',
                        'limit' => 1,
                        'addressdetails' => 1,
                        'email' => 'your@email.com' // REQUERIDO: Usa tu email real
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if (!empty($data)) {
                    $this->displayLocationInfo($data[0]);
                    
                    // Actualización segura con validación
                    $customer->latitude = $this->validateCoordinate($data[0]['lat'] ?? null);
                    $customer->longitude = $this->validateCoordinate($data[0]['lon'] ?? null);
                    
                    if ($customer->save()) {
                        $this->info("✅ Coordenadas guardadas: {$customer->latitude}, {$customer->longitude}");
                    } else {
                        $this->error("❌ Error al guardar en base de datos");
                        $this->logFailedGeocode($customer->id, $address, 'Database save error');
                    }
                } else {
                    $this->warn("❌ No se encontraron coordenadas para esta dirección");
                    $this->logFailedGeocode($customer->id, $address, 'No results from API');
                }
            } catch (RequestException $e) {
                $this->handleRequestException($e, $customer->id, $address);
            }

            // Respetar la política de uso (mínimo 1 segundo entre peticiones)
            sleep(1.2); // Pequeño margen adicional
        }

        $this->info("\nProceso de geocodificación completado.");
    }

    /**
     * Intenta determinar el país desde la dirección
     */
    protected function guessCountryFromAddress($address)
    {
        // Busca la última coma que normalmente precede al país
        $parts = explode(',', $address);
        $lastPart = trim(end($parts));
        
        return $lastPart ?: 'No identificado';
    }

    /**
     * Valida que la coordenada sea correcta
     */
    protected function validateCoordinate($coord)
    {
        if (is_null($coord)) return null;
        
        $coord = (float)$coord;
        return ($coord >= -180 && $coord <= 180) ? $coord : null;
    }

    /**
     * Muestra información detallada de la ubicación encontrada
     */
    protected function displayLocationInfo($location)
    {
        $this->line("📍 Ubicación encontrada:");
        $this->line("Nombre: ".($location['display_name'] ?? 'N/A'));
        $this->line("Latitud: {$location['lat']}, Longitud: {$location['lon']}");
        
        if (isset($location['address'])) {
            $country = $location['address']['country'] ?? 'N/A';
            $this->line("País confirmado: {$country}");
        }
    }

    /**
     * Maneja errores de la API
     */
    protected function handleRequestException($e, $customerId = null, $address = null)
    {
        $this->error("⚠️ Error en la petición:");
        $this->line("Mensaje: ".$e->getMessage());
        
        if ($customerId && $address) {
            $this->logFailedGeocode($customerId, $address, $e->getMessage());
        }

        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $this->line("Código HTTP: {$statusCode}");
            
            if ($statusCode == 429 || $statusCode == 403) {
                $waitTime = 60; // 1 minuto de espera si excedemos el límite
                $this->error("API bloqueó la petición. Esperando {$waitTime} segundos...");
                sleep($waitTime);
            }
        }
    }

    /**
     * Registra fallos de geocodificación (deberías implementar esto)
     */
    protected function logFailedGeocode($customerId, $address, $reason)
    {
        // Implementa según tus necesidades:
        // - Log a archivo
        // - Entrada en base de datos
        // - Notificación
        
        // Ejemplo básico:
        $logMessage = date('Y-m-d H:i:s')." | Cliente #{$customerId} | {$address} | Error: {$reason}";
        file_put_contents(storage_path('logs/geocode_failures.log'), $logMessage.PHP_EOL, FILE_APPEND);
    }
}