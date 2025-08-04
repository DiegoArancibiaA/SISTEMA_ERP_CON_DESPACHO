@extends('include.master')

@section('content')
<div class="container">
    <h2>Reporte de Despacho #{{ $dispatch->id }}</h2>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Información del Despacho</h5>
            <p><strong>Vendedor:</strong> {{ $dispatch->user->name }}</p>
            <p><strong>Fecha de Salida:</strong> {{ $dispatch->dispatch_date->format('d/m/Y H:i') }}</p>
            <p><strong>Fecha de Retorno:</strong> {{ $dispatch->return_date->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Salida</h5>
                    <p class="card-text h4">{{ $products->sum('out') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Retorno</h5>
                    <p class="card-text h4">{{ $products->sum('returned') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Total Faltante</h5>
                    <p class="card-text h4">{{ $products->sum('missing') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-3">
        <div class="card-body">
            <canvas id="dispatchChart" height="100"></canvas>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Productos</h4>
            <div>
                <a href="{{ route('dispatches.export', $dispatch->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-file-excel"></i> Exportar a Excel
                </a>
                <button onclick="window.print()" class="btn btn-info btn-sm">
                    <i class="fa fa-print"></i> Imprimir
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Salida</th>
                            <th>Cantidad Retorno</th>
                            <th>Faltante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['out'] }}</td>
                            <td>{{ $product['returned'] }}</td>
                            <td class="{{ $product['missing'] > 0 ? 'table-danger' : '' }}">
                                {{ $product['missing'] > 0 ? $product['missing'] : '0' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('dispatches.index') }}" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> Volver a Despachos
        </a>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dispatchChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Salida', 'Retorno', 'Faltante'],
                datasets: [{
                    label: 'Resumen de Despacho',
                    data: [
                        {{ $products->sum('out') }},
                        {{ $products->sum('returned') }},
                        {{ $products->sum('missing') }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection