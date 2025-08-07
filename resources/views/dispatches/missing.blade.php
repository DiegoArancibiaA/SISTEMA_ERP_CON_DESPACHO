@extends('include.master')

@section('content')
<div class="container modern-container">
    <div class="modern-header">
        <h2 class="modern-title">Productos Faltantes</h2>
        <div class="modern-actions">
            <a href="{{ route('dispatches.missing.report') }}" class="modern-btn modern-btn-export" target="_blank">
                <i class="fas fa-file-export"></i> Exportar
            </a>

            <button class="modern-btn modern-btn-refresh">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
    </div>

    <div class="modern-table-responsive">
        <table class="modern-table">
            <thead class="modern-thead">
                <tr>
                    <th class="modern-th">SKU</th>
                    <th class="modern-th">Producto</th>
                    <th class="modern-th">Despachado</th>
                    <th class="modern-th">Retornado</th>
                    <th class="modern-th">Faltante</th>
                    <th class="modern-th">Fecha de Despacho</th>
                    <th class="modern-th">Fecha de Retorno</th>
                </tr>
            </thead>
            <tbody class="modern-tbody">
                @forelse($missing as $item)
                    <tr class="modern-tr">
                        <td class="modern-td modern-td-sku">{{ $item->product->sku }}</td>
                        <td class="modern-td">{{ $item->product->product_name }}</td>
                        <td class="modern-td modern-td-number">{{ $item->quantity_out }}</td>
                        <td class="modern-td modern-td-number">{{ $item->quantity_returned }}</td>
                        <td class="modern-td modern-td-missing">{{ $item->quantity_out - $item->quantity_returned }}</td>
                        <td class="modern-td modern-td-date">{{ \Carbon\Carbon::parse($item->dispatch->dispatch_date)->format('d-m-Y H:i') }}</td>
                        <td class="modern-td modern-td-date">
                            @if($item->dispatch->return_date)
                                {{ \Carbon\Carbon::parse($item->dispatch->return_date)->format('d-m-Y H:i') }}
                            @else
                                <span class="modern-badge modern-badge-pending">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="modern-tr">
                        <td colspan="7" class="modern-td-empty">
                            <div class="modern-empty-state">
                                <i class="fas fa-box-open"></i>
                                <p>No hay productos faltantes</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Estilos modernos */
    .modern-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .modern-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .modern-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.75rem;
        margin: 0;
    }

    .modern-actions {
        display: flex;
        gap: 1rem;
    }

    .modern-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modern-btn-export {
        background-color: #4299e1;
        color: white;
    }

    .modern-btn-export:hover {
        background-color: #3182ce;
    }

    .modern-btn-refresh {
        background-color: #edf2f7;
        color: #4a5568;
    }

    .modern-btn-refresh:hover {
        background-color: #e2e8f0;
    }

    .modern-table-responsive {
        overflow-x: auto;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 800px;
    }

    .modern-thead {
        background-color: #4a5568;
    }

    .modern-th {
        padding: 1rem;
        color: white;
        font-weight: 500;
        text-align: left;
        position: sticky;
        top: 0;
    }

    .modern-tbody {
        background-color: #ffffff;
    }

    .modern-tr {
        transition: background-color 0.2s;
    }

    .modern-tr:not(:last-child) {
        border-bottom: 1px solid #edf2f7;
    }

    .modern-tr:hover {
        background-color: #f8fafc;
    }

    .modern-td {
        padding: 1rem;
        vertical-align: middle;
    }

    .modern-td-sku {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #4a5568;
    }

    .modern-td-number {
        font-family: 'Courier New', monospace;
        text-align: right;
    }

    .modern-td-missing {
        font-weight: 600;
        color: #e53e3e;
        text-align: right;
    }

    .modern-td-date {
        font-family: 'Courier New', monospace;
        color: #4a5568;
    }

    .modern-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 1.6rem;
        font-weight: 500;
    }

    .modern-badge-pending {
        background-color: #fffaf0;
        color: #dd6b20;
    }

    .modern-td-empty {
        padding: 3rem;
        text-align: center;
    }

    .modern-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        color: #a0aec0;
    }

    .modern-empty-state i {
        font-size: 2.5rem;
        color: #cbd5e0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .modern-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<!-- Incluir Font Awesome para los íconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection