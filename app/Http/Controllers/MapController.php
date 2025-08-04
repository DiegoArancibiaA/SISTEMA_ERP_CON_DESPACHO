<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function showCustomerMap()
    {
        $customers = Customer::whereNotNull('latitude')
                           ->whereNotNull('longitude')
                           ->get();
        
        if($customers->isEmpty()) {
            return redirect()->back()->with('error', 'No hay clientes con coordenadas geográficas registradas.');
        }

        // Configuración básica del mapa
        $mapConfig = [
            'center' => [$customers->avg('latitude'), $customers->avg('longitude')], // Centro promedio
            'zoom' => 12
        ];

        return view('stats.mapa', compact('customers', 'mapConfig'));
    }

    public function getCustomerData()
    {
        $customers = Customer::whereNotNull('latitude')
                           ->whereNotNull('longitude')
                           ->select('id', 'customer_name', 'address', 'phone', 'email', 'latitude as lat', 'longitude as lon')
                           ->get();

        return response()->json($customers);
    }
}