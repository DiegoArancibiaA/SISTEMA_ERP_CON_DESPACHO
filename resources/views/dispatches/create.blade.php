@extends('include.master')

@section('content')
<div class="container dispatch-container">
    <h2 class="dispatch-title">Registrar Salida y Retorno de Productos</h2>

    @if(session('success_out'))
        <div class="alert alert-success alert-modern">{{ session('success_out') }}</div>
    @endif
    @if(session('success_return'))
        <div class="alert alert-success alert-modern">{{ session('success_return') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-modern">
            <ul class="modern-error-list">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row form-row">
        <!-- Formulario Salida -->
        <div class="col-md-6 form-column">
            <div class="form-card form-card-out">
                <h4 class="form-card-title">Registrar Salida</h4>
                <form method="POST" action="{{ route('dispatches.storeOut') }}" class="modern-form">
                    {{ csrf_field() }}
                    <div class="form-group modern-form-group">
                        <label for="sku_out" class="modern-label">SKU Producto</label>
                        <input type="text" class="form-control modern-input" name="sku_out" id="sku_out" required>
                    </div>
                    <div class="form-group modern-form-group">
                        <label for="quantity_out" class="modern-label">Cantidad Salida</label>
                        <input type="number" min="1" class="form-control modern-input" name="quantity_out" id="quantity_out" required>
                    </div>
                    <button type="submit" class="btn btn-primary modern-btn">Registrar Salida</button>
                </form>
            </div>
        </div>

        <!-- Formulario Retorno -->
        <div class="col-md-6 form-column">
            <div class="form-card form-card-return">
                <h4 class="form-card-title">Registrar Retorno</h4>
                <form method="POST" action="{{ route('dispatches.storeReturn') }}" class="modern-form">
                    {{ csrf_field() }}
                    <div class="form-group modern-form-group">
                        <label for="sku_return" class="modern-label">SKU Producto</label>
                        <input type="text" class="form-control modern-input" name="sku_return" id="sku_return" required>
                    </div>
                    <div class="form-group modern-form-group">
                        <label for="quantity_return" class="modern-label">Cantidad Retorno</label>
                        <input type="number" min="1" class="form-control modern-input" name="quantity_return" id="quantity_return" required>
                    </div>
                    <button type="submit" class="btn btn-success modern-btn">Registrar Retorno</button>
                </form>
            </div>
        </div>
    </div>

    <hr class="modern-divider">

    <h3 class="section-title">Productos Faltantes</h3>
    <div class="table-responsive modern-table-container">
        <table class="table table-modern">
            <thead class="modern-thead">
                <tr>
                    <th>SKU</th>
                    <th>Nombre Producto</th>
                    <th>Cantidad Faltante</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $missing = \App\DispatchProduct::select('product_id', \DB::raw('SUM(quantity_out) as total_out'), \DB::raw('SUM(quantity_returned) as total_returned'))
                        ->groupBy('product_id')
                        ->havingRaw('SUM(quantity_out) > SUM(quantity_returned)')
                        ->with('product')
                        ->get();
                @endphp

                @forelse ($missing as $item)
                    <tr class="modern-tr">
                        <td>{{ $item->product->sku }}</td>
                        <td>{{ $item->product->product_name }}</td>
                        <td class="missing-quantity">{{ $item->total_out - $item->total_returned }}</td>
                    </tr>
                @empty
                    <tr class="modern-tr">
                        <td colspan="3" class="text-center no-data">No hay productos faltantes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Estilos modernos adicionales */
    .dispatch-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .dispatch-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 2rem;
        text-align: center;
        font-size: 2rem;
    }

    .form-row {
        margin-bottom: 3rem;
    }

    .form-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        height: 100%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-top: 4px solid;
    }

    .form-card-out {
        border-top-color: #3490dc;
    }

    .form-card-return {
        border-top-color: #38c172;
    }

    .form-card-title {
        color: #2c3e50;
        font-weight: 500;
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
    }

    .modern-form-group {
        margin-bottom: 1.5rem;
    }

    .modern-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4a5568;
        font-weight: 500;
    }

    .modern-input {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }

    .modern-input:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
    }

    .modern-btn {
        border-radius: 6px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .modern-divider {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 2.5rem 0;
    }

    .section-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .modern-thead th {
        background-color: #4a5568;
        color: white;
        font-weight: 500;
        padding: 1rem;
    }

    .modern-tr td {
        padding: 1rem;
        border-bottom: 1px solid #edf2f7;
    }

    .modern-tr:hover {
        background-color: #f8fafc;
    }

    .missing-quantity {
        font-weight: 600;
        color: #e53e3e;
    }

    .no-data {
        padding: 2rem;
        color: #718096;
    }

    .alert-modern {
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
    }

    .modern-error-list {
        padding-left: 0;
        list-style-type: none;
        margin-bottom: 0;
    }

    .modern-error-list li {
        padding: 0.5rem 0;
    }

    .modern-error-list li:not(:last-child) {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endsection