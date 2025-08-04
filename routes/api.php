<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para autocompletado de productos (pública o protegida según necesites)
Route::get('/products/search', function(Request $request) {
    $term = $request->input('q');
    
    $products = \App\Product::where('product_name', 'like', "%$term%")
                ->orWhere('code', 'like', "%$term%")
                ->select('id', 'product_name', 'code')
                ->limit(10)
                ->get();
    
    return $products;
});

// Alternativa con controlador (recomendado para lógica más compleja)
// Route::get('/products/search', 'Api\ProductController@search');