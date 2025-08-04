<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GeocodeController extends Controller
{
    public function execute(Request $request)
    {
        try {
            // Ejecutar el comando y capturar la salida
            Artisan::call('customers:geocode');
            $output = Artisan::output();
            
            // Buscar en la salida cuántos registros se actualizaron
            preg_match('/(\d+) coordenadas actualizadas/', $output, $matches);
            $processed = $matches[1] ?? 0;

            return response()->json([
                'success' => true,
                'message' => 'Geocodificación completada',
                'processed' => $processed,
                'output' => $output
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en GeocodeController: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al ejecutar geocodificación: ' . $e->getMessage(),
                'output' => $output ?? ''
            ], 500);
        }
    }
}