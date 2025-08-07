<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dispatch;
use App\DispatchProduct;
use App\Product;
use Carbon\Carbon;
use DB;


class DispatchController extends Controller
{
    // Mostrar la página con ambos formularios y tabla de faltantes
    public function create()
    {
        // Opcional: podrías cargar productos para autocompletar en los formularios
        return view('dispatches.create');
    }

    // Registrar salida de producto
    public function storeOut(Request $request)
    {
        $request->validate([
            'sku_out' => 'required|string|exists:products,sku',
            'quantity_out' => 'required|integer|min:1',
        ]);

        $product = Product::where('sku', $request->sku_out)->firstOrFail();

        DB::transaction(function () use ($product, $request) {
            $dispatch = Dispatch::create([
                'user_id' => auth()->id() ?? 1,
                'dispatch_date' => now(),
                'status' => 'pending',
            ]);

            DispatchProduct::create([
                'dispatch_id' => $dispatch->id,
                'product_id' => $product->id,
                'quantity_out' => $request->quantity_out,
                'quantity_returned' => 0,
            ]);
        });

        return back()->with('success_out', 'Salida registrada correctamente.');
    }


    // Registrar retorno de producto
    public function storeReturn(Request $request)
    {
        $request->validate([
            'sku_return' => 'required|string|exists:products,sku',
            'quantity_return' => 'required|integer|min:1',
        ]);

        $product = Product::where('sku', $request->sku_return)->firstOrFail();

        // Buscar dispatch_products pendiente para ese producto para actualizar retorno
        $dispatchProduct = DispatchProduct::whereHas('dispatch', function ($q) {
            $q->where('status', 'pending');
        })->where('product_id', $product->id)
          ->whereRaw('quantity_returned < quantity_out')
          ->first();

        if (!$dispatchProduct) {
            return back()->withErrors(['sku_return' => 'No existe salida pendiente para este producto o ya fue retornado completamente.']);
        }

        // Actualizar cantidad retornada, sin exceder la cantidad salida
        $new_returned = $dispatchProduct->quantity_returned + $request->quantity_return;
        if ($new_returned > $dispatchProduct->quantity_out) {
            return back()->withErrors(['quantity_return' => 'La cantidad retornada no puede superar la cantidad salida.']);
        }

        $dispatchProduct->quantity_returned = $new_returned;
        $dispatchProduct->save();

        // Opcional: si se retorna toda la cantidad, se puede actualizar estado dispatch a completed
        $allReturned = DispatchProduct::where('dispatch_id', $dispatchProduct->dispatch_id)
            ->whereRaw('quantity_returned < quantity_out')
            ->count();

        if ($allReturned == 0) {
            $dispatch = Dispatch::find($dispatchProduct->dispatch_id);
            $dispatch->status = 'completed';
            $dispatch->return_date = Carbon::now();
            $dispatch->save();
        }

        return back()->with('success_return', 'Retorno de producto registrado correctamente.');
    }

    // Mostrar productos faltantes: cantidad_out - cantidad_returned > 0
    public function missingProducts()
    {
        // Solo mostrar productos cuyo retorno es menor a la salida
        $missing = DispatchProduct::whereColumn('quantity_returned', '<', 'quantity_out')
            ->with(['product', 'dispatch'])
            ->get();

        return view('dispatches.missing', compact('missing'));
    }

    public function history()
    {
        $dispatches = Dispatch::with('products')
            ->whereNotNull('return_date')
            ->orderBy('dispatch_date', 'desc')
            ->get();

        return view('dispatches.history', compact('dispatches'));
    }



    public function details($id)
    {
        $dispatch = Dispatch::with(['products.product'])->findOrFail($id);

        return view('dispatches.details', compact('dispatch'));
    }


    public function missingReport()
    {
        $missingProducts = DB::table('dispatch_products')
            ->join('dispatches', 'dispatch_products.dispatch_id', '=', 'dispatches.id')
            ->join('products', 'dispatch_products.product_id', '=', 'products.id')
            ->join('users', 'dispatches.user_id', '=', 'users.id') // unión con tabla users
            ->select(
                'dispatches.id as dispatch_id',
                'dispatches.dispatch_date',
                'users.name as user_name', // nombre del usuario
                'products.id as product_id',
                'products.product_name',
                'products.sku',
                'dispatch_products.quantity_out',
                'dispatch_products.quantity_returned',
                DB::raw('(dispatch_products.quantity_out - dispatch_products.quantity_returned) as missing_quantity')
            )
            ->whereColumn('dispatch_products.quantity_returned', '<', 'dispatch_products.quantity_out')
            ->orderBy('dispatches.dispatch_date', 'desc')
            ->get();

        return view('dispatches.missing_report', compact('missingProducts'));
    }






}
