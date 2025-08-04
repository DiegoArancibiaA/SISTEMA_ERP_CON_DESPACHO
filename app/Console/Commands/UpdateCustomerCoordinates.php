<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
class UpdateCustomerCoordinates extends Command
{
    protected $signature = 'customers:update-coordinates';

    protected $description = 'Actualizar latitud y longitud de clientes sin coordenadas';

    public function handle()
    {
        $clientes = Customer::whereNull('lat')->orWhereNull('lon')->get();

        foreach ($clientes as $cliente) {
            $this->info("Actualizando: {$cliente->customer_name} - {$cliente->address}");
            $cliente->updateCoordinates();
            sleep(1); // Respetar límites Nominatim (1 segundo entre consultas)
        }

        $this->info('Coordenadas actualizadas.');
    }
}