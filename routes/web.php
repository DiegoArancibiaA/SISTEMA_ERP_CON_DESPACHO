<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DispatchController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|PUT AND DELETE method not work in some windows server that's why laiter we use 
 get and post mehod for update and delete
|
*/
//use App\Http\Controllers\DispatchController;

Route::group(['middleware'=> ['auth','check.permission']],function(){

// Rutas de gráficos estadísticos
Route::get('/estadisticas/ventas-mensuales', function () { return view('stats.monthly_sales'); })->name('stats.monthly-sales');
Route::get('/estadisticas/distribucion-productos', function () { return view('stats.product_distribution'); })->name('stats.product-distribution');
Route::get('/estadisticas/ventas-categoria', function () { return view('stats.sales_by_category'); })->name('stats.sales-by-category');
Route::get('/estadisticas/mapa-antofagasta', 'DashboardController@mapaAntofagasta')->name('stats.mapa-antofagasta');
Route::get('/estadisticas/top-productos-mas-vendidos', 'DashboardController@topProductosMasVendidos')->name('stats.top-productos');
Route::get('/estadisticas/ventas-por-metodo-pago', 'DashboardController@ventasPorMetodoPago')->name('stats.ventas-metodo');
Route::get('/estadisticas/stock-por-categoria', 'DashboardController@stockPorCategoria')->name('stats.stock-categoria');

// Reporte de Stock Bajo
Route::get('/stats/low-stock', 'DashboardController@showLowStockReport')->name('stats.low-stock');
Route::get('/api/low-stock-products', 'DashboardController@getLowStockProducts');

// Rutas API para gráficos
Route::get('sales-by-category', 'DashboardController@getSalesByCategory')->name('dashboard.sales-by-category');
Route::get('inventory-trend', 'DashboardController@getInventoryTrend')->name('dashboard.inventory-trend');
Route::get('product-distribution', 'DashboardController@getProductDistribution')->name('dashboard.product-distribution');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Rutas de despachos
Route::get('/dispatches/create', 'DispatchController@create')->name('dispatches.create');
Route::post('/dispatches/out', 'DispatchController@storeOut')->name('dispatches.storeOut');
Route::post('/dispatches/return', 'DispatchController@storeReturn')->name('dispatches.storeReturn');
Route::get('/dispatches/missing', 'DispatchController@missingProducts')->name('dispatches.missing');

Route::get('/dispatches/history', 'DispatchController@history')->name('dispatches.history');
Route::get('/dispatches/{id}/details', 'DispatchController@details')->name('dispatches.details');


Route::get('/dispatches/missing/report', 'DispatchController@missingReport')->name('dispatches.missing.report');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// dashboard 
Route::get('/', 'DashboardController@index');
Route::get('info-box', 'DashboardController@InfoBox')->name('dashboard.infobox');
Route::get('product-distribution', 'DashboardController@getProductDistribution')->name('dashboard.product-distribution');

//MAPA
Route::get('/mapa-clientes', 'MapController@showCustomerMap')->name('mapa.clientes');
Route::get('/api/clientes', 'MapController@getCustomerData')->name('api.clientes');
Route::post('/geocode/execute', [
    'as' => 'geocode.execute',
    'uses' => 'GeocodeController@execute',
    'middleware' => 'auth' // Opcional: protege la ruta si es necesario
]);

// vendor 
Route::resource('supplier','VendorController');
Route::get('supplier/delete/{id}','VendorController@destroy');
Route::post('supplier/update/{id}','VendorController@update');
Route::get('vendor-list','VendorController@Vendor');

// product category 
Route::resource('category','CategoryController');
// category delete 
Route::get('category/delete/{id}','CategoryController@destroy');
//category update
Route::post('category/update/{id}','CategoryController@update');

Route::get('category-list','CategoryController@CategoryList');

Route::get('all-category','CategoryController@AllCategory');

// product 
Route::resource('product','ProductController');
Route::get('product/delete/{id}','ProductController@destroy');
Route::post('product/update/{id}','ProductController@update');

Route::get('product-list','ProductController@ProductList');
Route::get('category/product/{id}','ProductController@productByCategory');



// customer 
Route::resource('customer','CustomerController');
Route::get('customer/delete/{id}','CustomerController@destroy');
Route::post('customer/update/{id}','CustomerController@update');
Route::get('customer-list','CustomerController@CustomerList');

//Stock
Route::resource('stock','StockController');
Route::get('stock/delete/{id}','StockController@destroy');
Route::post('stock/update/{id}','StockController@update');
Route::get('stock-list','StockController@StockList');
Route::get('chalan-list/chalan/{id}','StockController@ChalanList');
Route::get('stock-asset','StockController@StockAsset');
Route::post('stock-update','StockController@StockUpdate');

// invoice 
Route::resource('invoice','InvoiceController');
Route::get('invoice/delete/{id}','InvoiceController@destroy');
Route::post('invoice/update/{id}','InvoiceController@update');
Route::get('invoice-list','InvoiceController@InvoiceList');
Route::get('get/invoice/number','InvoiceController@getLastInvoice');

// payment 
Route::resource('payment','PaymentController');
Route::get('payment/delete/{id}','PaymentController@destroy');



// Report 
Route::resource('role','RoleController');
Route::get('role/delete/{id}','RoleController@destroy');
Route::post('role/update/{id}','RoleController@update');
Route::get('role-list','RoleController@RoleList');
Route::post('permission','RoleController@Permission');
Route::get('report',['as'=>'report.index','uses'=>'ReportingController@index']);
Route::get('get-report',['as'=>'report.store','uses'=>'ReportingController@store']);
Route::get('print-report',['as'=>'report.print','uses'=>'ReportingController@Print']);

// user management 
Route::resource('user','UserManageController');
Route::get('user/delete/{id}','UserManageController@destroy');
Route::post('user/update/{id}','UserManageController@update');
Route::get('user-list','UserManageController@UserList');
Route::get('comapany-setting',['as'=>'company.index','uses'=>'CompanyController@index']);
Route::post('comapany-setting',['as'=>'company.store','uses'=>'CompanyController@store']);
Route::get('password-change',['as'=>'password.index','uses'=>'SettingController@index']);
Route::post('password-change',['as'=>'password.store','uses'=>'SettingController@store']);
Route::get('user-role','RoleController@userRole');
Route::get('logout','UserController@logout');

});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
