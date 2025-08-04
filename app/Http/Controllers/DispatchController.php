<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dispatch;
use App\DispatchProduct;
use App\Product;
use App\User;
use Validator;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DispatchExport;

class DispatchController extends Controller
{
    public function __construct()
    {
                
        
        $this->middleware('auth');

    }
    
    public function index()
    {
        $dispatches = Dispatch::with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('dispatches.index', compact('dispatches'));
    }
    
    public function create()
    {
        $sellers = User::where('role_id', 2)
                    ->orderBy('name')
                    ->pluck('name', 'id');
        
        return view('dispatches.create', compact('sellers'))
               ->withHeaders([
                   'X-Content-Type-Options' => 'nosniff',
                   'Referrer-Policy' => 'strict-origin-when-cross-origin'
               ]);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();
            
            $dispatch = Dispatch::create([
                'user_id' => $validatedData['user_id'],
                'dispatch_date' => now(),
                'status' => 'pending'
            ]);
            
            DB::commit();
            
            // AÑADE ESTO PARA DEPURACIÓN (temporal)
            \Log::info('Despacho creado', ['id' => $dispatch->id]);
            
            // MODIFICA LA REDIRECCIÓN PARA ASEGURAR LA RUTA
            return redirect()->route('dispatches.scan', ['dispatch' => $dispatch->id])
                ->with('success', 'Despacho creado correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear despacho', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al crear despacho: '.$e->getMessage());
        }
    }
    
    public function scan($id)
    {
        $dispatch = Dispatch::with(['user', 'products.product'])
                    ->findOrFail($id);
        
        return view('dispatches.scan', compact('dispatch'))
               ->withHeaders([
                   'Content-Security-Policy' => "default-src 'self'",
                   'X-Frame-Options' => 'DENY'
               ]);
    }
    
    public function scanOut(Request $request, $dispatchId)
    {
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Content-Type-Options', 'nosniff');
        
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($request->product_id);
            
            $dispatchProduct = DispatchProduct::firstOrNew([
                'dispatch_id' => $dispatchId,
                'product_id' => $request->product_id
            ]);
            
            $dispatchProduct->quantity_out += $request->quantity;
            $dispatchProduct->save();
            
            DB::commit();
            
            return $this->jsonResponse([
                'success' => true,
                'product' => $product,
                'quantity_out' => $dispatchProduct->quantity_out,
                'products' => $this->getDispatchProducts($dispatchId)
            ])->cookie('XSRF-TOKEN', csrf_token(), 0, null, null, true, true);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Error al registrar producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function scanReturn(Request $request, $dispatchId)
    {
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-XSS-Protection', '1; mode=block');
        
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $dispatchProduct = DispatchProduct::where([
                'dispatch_id' => $dispatchId,
                'product_id' => $request->product_id
            ])->firstOrFail();
            
            if (($dispatchProduct->quantity_returned + $request->quantity) > $dispatchProduct->quantity_out) {
                throw new \Exception('No puede devolver más productos de los que salieron');
            }
            
            $dispatchProduct->quantity_returned += $request->quantity;
            $dispatchProduct->save();
            
            DB::commit();
            
            return $this->jsonResponse([
                'success' => true,
                'product' => $dispatchProduct->product,
                'quantity_returned' => $dispatchProduct->quantity_returned,
                'missing' => $dispatchProduct->missing,
                'products' => $this->getDispatchProducts($dispatchId)
            ])->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Error al registrar producto devuelto: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function getDispatchProducts($dispatchId)
    {
        return DispatchProduct::with('product')
            ->where('dispatch_id', $dispatchId)
            ->get()
            ->map(function($item) {
                return [
                    'product' => [
                        'product_name' => $item->product->product_name,
                    ],
                    'quantity_out' => $item->quantity_out,
                    'quantity_returned' => $item->quantity_returned,
                    'missing' => $item->missing
                ];
            });
    }

    public function complete(Request $request, $dispatchId)
    {
        $request->headers->set('Accept', 'application/json');
        
        try {
            DB::beginTransaction();
            
            $dispatch = Dispatch::findOrFail($dispatchId);
            $dispatch->status = 'completed';
            $dispatch->completed_at = now();
            $dispatch->save();
            
            DB::commit();
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Despacho marcado como completado'
            ])->header('Cache-Control', 'no-store');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Error al completar despacho: ' . $e->getMessage()
            ], 500);
        }
    }

    public function report($id)
    {
        $dispatch = Dispatch::with(['user', 'products.product'])
                    ->findOrFail($id);
        
        return view('dispatches.report', compact('dispatch'))
               ->withHeaders([
                   'X-Content-Type-Options' => 'nosniff',
                   'Referrer-Policy' => 'strict-origin-when-cross-origin'
               ]);
    }

    public function export($id)
    {
        $dispatch = Dispatch::with(['user', 'products.product'])
                    ->findOrFail($id);
        
        $products = $dispatch->products->map(function($item) {
            return [
                'Producto' => $item->product->product_name,
                'Salida' => $item->quantity_out,
                'Retorno' => $item->quantity_returned,
                'Faltante' => $item->missing > 0 ? $item->missing : 0
            ];
        });
        
        return Excel::download(new DispatchExport($products), "despacho_{$dispatch->id}.xlsx")
               ->withHeaders([
                   'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                   'Content-Disposition' => 'attachment; filename="despacho_'.$dispatch->id.'.xlsx"'
               ]);
    }

    protected function jsonResponse($data, $status = 200, array $headers = [])
    {
        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block'
        ];
        
        return response()->json($data, $status, array_merge($defaultHeaders, $headers));
    }
}