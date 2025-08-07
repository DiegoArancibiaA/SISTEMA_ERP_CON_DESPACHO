@extends('include.master')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>Detalle de Despacho #{{ $dispatch->id }}</h2>
    </div>

    <div class="card">
        <div class="header">
            <h2>Información del Despacho</h2>
        </div>
        <div class="body">
            <p><strong>Fecha de Despacho:</strong> {{ $dispatch->dispatch_date }}</p>
            <p><strong>Fecha de Retorno:</strong> {{ $dispatch->return_date }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($dispatch->status) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="header">
            <h2>Productos Despachados</h2>
        </div>
        <div class="body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Cantidad Despachada</th>
                        <th>Cantidad Retornada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispatch->products as $item)
                    <tr>
                        <td>{{ $item->product->product_name }}</td>
                        <td>{{ $item->product->sku }}</td>
                        <td>{{ $item->quantity_out }}</td>
                        <td>{{ $item->quantity_returned }}</td>
                    </tr>
                    @endforeach

                    @if($dispatch->products->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No se encontraron productos.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('dispatches.history') }}" class="btn btn-default">Volver al Historial</a>
</div>
@endsection
