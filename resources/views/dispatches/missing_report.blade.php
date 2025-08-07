<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos Faltantes</title>
    <style>
        /* Estilos base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            color: #333;
            line-height: 1.6;
        }

        /* Encabezado */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e1e1e1;
        }

        .logo img {
            height: 70px;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
        }

        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 5px;
        }

        h2 {
            color: #7f8c8d;
            font-size: 16px;
            font-weight: normal;
            margin-top: 0;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th {
            background-color: #3498db;
            color: white;
            text-align: center;
            padding: 12px 8px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 13px;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e1e1e1;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f1f8fe;
        }

        /* Estilos específicos para columnas */
        .missing-quantity {
            font-weight: bold;
            color: #e74c3c;
        }

        /* Pie de página */
        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: right;
            color: #7f8c8d;
            padding-top: 15px;
            border-top: 1px solid #e1e1e1;
        }

        /* Botones (solo en pantalla) */
        .no-print {
            text-align: center;
            margin-top: 30px;
        }

        .no-print button, .no-print a {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .no-print button:hover, .no-print a:hover {
            background-color: #2980b9;
        }

        /* Estilos para impresión */
        @media print {
            body {
                margin: 0;
                padding: 20px;
                font-size: 12px;
            }

            .no-print {
                display: none;
            }

            table {
                box-shadow: none;
            }

            th {
                background-color: #3498db !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }

            .header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }

            .logo img {
                height: 60px;
            }

            h1 {
                font-size: 20px;
            }

            h2 {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ url('images/logo_report.png') }}" alt="Logo de la empresa">
        </div>
        <div class="company-info">
            <p style="margin: 0; font-weight: bold;">{{ config('app.name', 'Sistema de Gestión') }}</p>
            <p style="margin: 0; font-size: 13px;">{{ config('app.address', 'Dirección de la empresa') }}</p>
        </div>
    </div>

    <div class="report-title">
        <h1>REPORTE DE PRODUCTOS FALTANTES</h1>
        <h2>Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Despacho</th>
                <th>Fecha Despacho</th>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Despachado</th>
                <th>Devuelto</th>
                <th>Faltante</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($missingProducts as $product)
                <tr>
                    <td>{{ $product->dispatch_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($product->dispatch_date)->format('d/m/Y H:i') }}</td>
                    <td>{{ $product->user_name }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->quantity_out }}</td>
                    <td>{{ $product->quantity_returned }}</td>
                    <td class="missing-quantity">{{ $product->missing_quantity }}</td>
                </tr>
            @endforeach
            @if($missingProducts->isEmpty())
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No se encontraron productos faltantes</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Despachos - {{ config('app.name', 'Nombre de la empresa') }}</p>
        <p>Página 1 de 1</p>
    </div>

    <div class="no-print">
        <button onclick="window.print()">Imprimir Reporte</button>
        <a href="{{ route('dispatches.missing') }}">Volver al listado</a>
    </div>
</body>
</html>