<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sell;
use App\SellDetails;
use App\Product;
use App\Stock;
use App\Category;
use App\Vendor;
use App\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    /*==================================== InfoBox + Ventas Mensuales ===========================================================================================*/
    public function InfoBox()
    {
        // Resumen general
        $total_invoice = Sell::count();
        $total_customer = Customer::count();
        $total_vendor = Vendor::count();
        $total_product = Product::count();

        $total_quantity = Stock::sum('stock_quantity');
        $total_sold_quantity = SellDetails::sum('sold_quantity');
        $total_current_quantity = $total_quantity - $total_sold_quantity;

        $total_sold_amount = Sell::sum('total_amount');
        $total_paid_amount = Sell::sum('paid_amount');
        $total_outstanding = $total_sold_amount - $total_paid_amount;

        $total_buy_price = SellDetails::sum('total_buy_price');
        $total_gross_profit = $total_sold_amount - $total_buy_price;
        $total_net_profit = $total_paid_amount - $total_buy_price;

        // Ventas mensuales para el gráfico
        $monthlySales = Sell::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $salesData = array_fill(0, 12, 0);
        foreach ($monthlySales as $sale) {
            $salesData[$sale->month - 1] = $sale->total;
        }

        return response()->json([
            'total_invoice' => $total_invoice,
            'total_customer' => $total_customer,
            'total_vendor' => $total_vendor,
            'total_product' => $total_product,
            'total_quantity' => $total_quantity,
            'total_sold_quantity' => $total_sold_quantity,
            'total_current_quantity' => $total_current_quantity,
            'total_sold_amount' => round($total_sold_amount),
            'total_paid_amount' => round($total_paid_amount),
            'total_outstanding' => round($total_outstanding),
            'total_gross_profit' => round($total_gross_profit),
            'total_net_profit' => round($total_net_profit),
            'monthly_sales' => $salesData
        ]);
    }

    /*==================================== Producto por Categoría (Gráfico de Pastel) ==========================*/
    public function getProductDistribution()
    {
        $distribution = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('COUNT(products.id) as count'))
            ->groupBy('categories.name')
            ->get();

        return response()->json($distribution);
    }

    /*==================================== Tendencia de Inventario (Gráfico de Líneas) ==========================*/
    public function getAlphaERPTrend()
    {
        $trend = Stock::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(stock_quantity) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        return response()->json($trend);
    }

    /*==================================== Ventas por Categoría (Gráfico de Barras Apiladas) ==========================*/
    public function getSalesByCategory()
    {
        $sales = SellDetails::select(
                'categories.name as category',
                DB::raw('MONTH(sells.created_at) as month'),
                DB::raw('SUM(sell_details.total_sold_price) as total')
            )
            ->join('products', 'sell_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sells', 'sell_details.sell_id', '=', 'sells.id')
            ->whereYear('sells.created_at', date('Y'))
            ->groupBy('category', 'month')
            ->orderBy('month')
            ->get();

        return response()->json($sales);
    }
    /*=============================================TOP PRODUCTOS MAS VENDIDOS =============================================================================*/
    public function topProductosMasVendidos()
    {
        $datos = DB::table('sell_details')
            ->join('products', 'sell_details.product_id', '=', 'products.id')
            ->select('products.product_name', DB::raw('SUM(sold_quantity) as total'))
            ->groupBy('products.product_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('stats.top_productos_mas_vendidos', compact('datos'));
    }

    /*============================================== VENTAS POR METODOS DE PAGO ============================================================================*/
    public function ventasPorMetodoPago()
    {
        $datos = DB::table('sells')
            ->select('payment_method', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('stats.ventas_por_metodo_pago', compact('datos'));
    }

    /*============================================== STOCK POR CATEGORIA ============================================================================*/
    public function stockPorCategoria()
    {
        $stockPorCategoria = DB::table('stocks')
            ->join('categories', 'stocks.category_id', '=', 'categories.id')
            ->select('categories.name as categoria', DB::raw('SUM(stocks.current_quantity) as total'))
            ->groupBy('categories.name')
            ->get();

        return view('stats.stock_por_categoria', compact('stockPorCategoria'));
    }
    /*==========================================================================================================================*/


    /*==========================================================================================================================*/
/*==================================== Alerta de Bajo Stock ============================================*/
    public function getLowStockProducts(Request $request)
    {
        $products = Product::with(['category', 'stock'])
            ->join('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(
                'products.id',
                'products.product_name',
                'products.category_id',
                'stocks.current_quantity',
                DB::raw('categories.name as category_name')
            )
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('stocks.current_quantity', '<=', 20)
            ->orderBy('stocks.current_quantity', 'asc')
            ->get();

        return response()->json([
            'products' => $products,
            'count' => $products->count()
        ]);
    }

    public function showLowStockReport(Request $request)
    {
        $lowStockData = $this->getLowStockProducts($request)->getData();
        
        return view('stats.low_stock', [
            'products' => $lowStockData->products,
            'count' => $lowStockData->count
        ]);
    }

    /*==========================================================================================================================*/

    /*==========================================================================================================================*/

    /*==========================================================================================================================*/

    /*==========================================================================================================================*/

    /*==========================================================================================================================*/


    // Métodos restantes no implementados aún
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
