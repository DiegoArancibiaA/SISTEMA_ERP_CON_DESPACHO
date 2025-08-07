@extends('include.master')

@section('content')
<div class="container-fluid">
    <!-- Encabezado mejorado -->
    <div class="block-header">
        <div class="d-flex justify-content-between align-items-center">
            <h2>
                <i class="fas fa-truck-loading"></i> Detalle de Despacho #{{ $dispatch->id }}
            </h2>
            <div class="header-actions">
                <a href="{{ route('dispatches.details.report', $dispatch->id) }}" 
                   class="btn btn-report" 
                   target="_blank"
                   title="Generar reporte detallado">
                    <i class="fas fa-file-pdf"></i> Exportar a PDF
                </a>
                <a href="{{ route('dispatches.history') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de información del despacho mejorada -->
    <div class="card modern-card">
        <div class="header bg-blue">
            <h2>
                <i class="fas fa-info-circle"></i> Información del Despacho
            </h2>
        </div>
        <div class="body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label"><i class="far fa-calendar-alt"></i> Fecha de Despacho:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($dispatch->dispatch_date)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="far fa-calendar-check"></i> Fecha de Retorno:</span>
                    <span class="info-value {{ $dispatch->return_date ? '' : 'text-warning' }}">
                        {{ $dispatch->return_date ? \Carbon\Carbon::parse($dispatch->return_date)->format('d/m/Y H:i') : 'Pendiente' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-tasks"></i> Estado:</span>
                    <span class="info-value status-{{ strtolower($dispatch->status) }}">
                        {{ ucfirst($dispatch->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta de productos mejorada -->
    <div class="card modern-card">
        <div class="header bg-blue">
            <h2>
                <i class="fas fa-boxes"></i> Productos Despachados
            </h2>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th class="text-center">Despachado</th>
                            <th class="text-center">Retornado</th>
                            <th class="text-center">Diferencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dispatch->products as $item)
                        <tr>
                            <td>{{ $item->product->product_name }}</td>
                            <td class="sku">{{ $item->product->sku }}</td>
                            <td class="text-center">{{ $item->quantity_out }}</td>
                            <td class="text-center">{{ $item->quantity_returned }}</td>
                            <td class="text-center {{ ($item->quantity_out - $item->quantity_returned) > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $item->quantity_out - $item->quantity_returned }}
                            </td>
                        </tr>
                        @endforeach

                        @if($dispatch->products->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-4 empty-state">
                                <i class="fas fa-box-open fa-2x"></i>
                                <p>No se encontraron productos</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos generales */
    .block-header h2 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.5rem;
        margin: 0px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    /* Tarjetas modernas */
    .modern-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: none;
    }

    .modern-card .header {
        border-radius: 8px 8px 0 0 !important;
        color: white;
    }

    .bg-blue {
        background-color: #3498db !important;
    }

    /* Grid de información */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px dashed #eee;
    }

    .info-label {
        font-weight: 600;
        color: #555;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        color: #333;
    }

    /* Tabla moderna */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead th {
        background-color: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
        padding: 12px 15px;
        border-bottom: 2px solid #eee;
    }

    .modern-table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .modern-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .sku {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Estados */
    .status-completado {
        color: #38a169;
        font-weight: 500;
    }

    .status-pendiente {
        color: #d69e2e;
        font-weight: 500;
    }

    .status-proceso {
        color: #3182ce;
        font-weight: 500;
    }

    /* Botones */
    .btn-report {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 6px;
        background-color: #e53e3e;
        color: white;
        border: none;
        transition: all 0.3s;
        text-decoration: none;
        margin: 10px;
    }

    .btn-report:hover {
        background-color: #c53030;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 6px;
        background-color: #edf2f7;
        color: #4a5568;
        border: none;
        transition: all 0.3s;
        text-decoration: none;
        margin: 10px;
    }

    .btn-back:hover {
        background-color: #e2e8f0;
    }

    /* Estado vacío */
    .empty-state {
        padding: 20px;
        text-align: center;
        color: #a0aec0;
    }

    .empty-state i {
        margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .header-actions {
            flex-direction: column;
            width: 100%;
        }
    }
</style>

<!-- Incluir Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection