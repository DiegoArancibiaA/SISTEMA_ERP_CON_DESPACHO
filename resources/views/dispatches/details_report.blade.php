@extends('layouts.app')

@section('content')
<div class="container report-container">
    <div class="report-header">
        <div class="company-logo">
            <img src="{{ asset('images/logo_report.png') }}" alt="Company Logo">
        </div>
        <div class="report-title">
            <h2>Reporte de Detalle de Despacho</h2>
            <p class="report-date">Generado: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="dispatch-info">
        <div class="info-section">
            <h3>Información del Despacho</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ID de Despacho:</span>
                    <span class="info-value">{{ $dispatch->id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Despacho:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($dispatch->dispatch_date)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Retorno:</span>
                    <span class="info-value {{ $dispatch->return_date ? '' : 'pending' }}">
                        {{ $dispatch->return_date ? \Carbon\Carbon::parse($dispatch->return_date)->format('d/m/Y H:i') : 'Pendiente' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h3>Información del Responsable</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">{{ $dispatch->user_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $dispatch->user_email }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="products-table">
        <h3>Productos del Despacho</h3>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Producto</th>
                    <th>Cantidad Despachada</th>
                    <th>Cantidad Devuelta</th>
                    <th>Faltante</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                <tr>
                    <td class="sku">{{ $p->sku }}</td>
                    <td>{{ $p->product_name }}</td>
                    <td class="quantity">{{ $p->quantity_out }}</td>
                    <td class="quantity">{{ $p->quantity_returned }}</td>
                    <td class="quantity missing">{{ $p->missing_quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="report-footer">
        <div class="signature">
            <p>_________________________</p>
            <p>Firma del Responsable</p>
        </div>
        <div class="actions no-print">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Imprimir Reporte
            </button>
            <a href="{{ url()->previous() }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<style>
    /* Estilos generales */
    .report-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Encabezado */
    .report-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e1e1e1;
    }

    .company-logo img {
        height: 80px;
        margin-right: 30px;
    }

    .report-title h2 {
        color: #2c3e50;
        margin: 0;
        font-size: 24px;
    }

    .report-date {
        color: #7f8c8d;
        margin: 5px 0 0;
        font-size: 14px;
    }

    /* Información del despacho */
    .dispatch-info {
        margin-bottom: 30px;
    }

    .info-section {
        margin-bottom: 20px;
    }

    .info-section h3 {
        color: #3498db;
        font-size: 18px;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }

    .info-label {
        font-weight: 600;
        color: #555;
    }

    .info-value {
        color: #333;
    }

    .info-value.pending {
        color: #e74c3c;
        font-weight: 500;
    }

    /* Tabla de productos */
    .products-table {
        margin-bottom: 40px;
    }

    .products-table h3 {
        color: #3498db;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .modern-table thead {
        background-color: #3498db;
        color: white;
    }

    .modern-table th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 500;
    }

    .modern-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }

    .modern-table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .modern-table tr:hover {
        background-color: #f1f8fe;
    }

    .sku {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #2c3e50;
    }

    .quantity {
        text-align: right;
        font-family: 'Courier New', monospace;
    }

    .quantity.missing {
        color: #e74c3c;
        font-weight: 600;
    }

    /* Pie de página */
    .report-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e1e1e1;
    }

    .signature {
        text-align: center;
        color: #7f8c8d;
        font-size: 14px;
    }

    /* Botones */
    .actions {
        display: flex;
        gap: 15px;
    }

    .print-btn, .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-size: 14px;
    }

    .print-btn {
        background-color: #3498db;
        color: white;
        border: none;
    }

    .print-btn:hover {
        background-color: #2980b9;
    }

    .back-btn {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
    }

    .back-btn:hover {
        background-color: #e9ecef;
    }

    /* Estilos para impresión */
    @media print {
        .no-print {
            display: none;
        }

        body {
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .report-container {
            padding: 0;
        }

        .modern-table th {
            background-color: #3498db !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
        }

        .quantity.missing {
            color: #e74c3c !important;
        }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection