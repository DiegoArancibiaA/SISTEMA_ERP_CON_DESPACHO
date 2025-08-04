@extends('include.master')

@section('title','Gestión de Despachos | Escaneo')

@section('page-title','Escaneo de Productos - Despacho #'.$dispatch->id)

@section('content')

<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="header">
                <h2>Registrar Salida</h2>
            </div>
            <div class="body">
                <form id="scanOutForm">
                    <div class="form-group">
                        <label for="product_id_out">Código de Producto:</label>
                        <input type="text" id="product_id_out" class="form-control" autofocus>
                        <div id="product_name_out" class="mt-2 font-weight-bold"></div>
                    </div>
                    <div class="form-group">
                        <label for="quantity_out">Cantidad:</label>
                        <input type="number" id="quantity_out" class="form-control" value="1" min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Registrar Salida
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="header">
                <h2>Registrar Retorno</h2>
            </div>
            <div class="body">
                <form id="scanReturnForm">
                    <div class="form-group">
                        <label for="product_id_return">Código de Producto:</label>
                        <input type="text" id="product_id_return" class="form-control">
                        <div id="product_name_return" class="mt-2 font-weight-bold"></div>
                    </div>
                    <div class="form-group">
                        <label for="quantity_return">Cantidad:</label>
                        <input type="number" id="quantity_return" class="form-control" value="1" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Registrar Retorno
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>Productos Registrados</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="scannedProductsTable">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Salida</th>
                                <th>Retorno</th>
                                <th>Faltante</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dispatch->products as $product)
                            <tr>
                                <td>{{ $product->product->product_name }}</td>
                                <td>{{ $product->quantity_out }}</td>
                                <td>{{ $product->quantity_returned }}</td>
                                <td>{{ $product->missing > 0 ? $product->missing : 0 }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-md-12 text-center">
        <a href="{{ route('dispatches.complete', $dispatch->id) }}" class="btn btn-success btn-lg">
            <i class="material-icons">check</i> Finalizar Despacho
        </a>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Configuración de Select2 para autocompletar
    function initSelect2(element, url, displayField) {
        return $(element).select2({
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(item => ({
                            id: item.id,
                            text: item[displayField] + ' (' + item.code + ')'
                        }))
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    }

    // Inicializar autocompletado
    const selectOut = initSelect2('#product_id_out', '/api/products/search', 'product_name');
    const selectReturn = initSelect2('#product_id_return', '/api/products/search', 'product_name');

    // Mostrar nombre del producto seleccionado
    selectOut.on('select2:select', function(e) {
        $('#product_name_out').text('Producto: ' + e.params.data.text);
    });

    selectReturn.on('select2:select', function(e) {
        $('#product_name_return').text('Producto: ' + e.params.data.text);
    });

    // Función genérica para manejar AJAX
    async function handleScan(formData, url, successMessage) {
        try {
            showLoading();
            const response = await $.ajax({
                url: url,
                method: 'POST',
                data: formData,
            });

            if (response.success) {
                showSuccessToast(successMessage);
                updateProductsTable(response.products);
                $(formData.product_id).val('').trigger('change');
                $(formData.quantity).val(1);
                $(formData.product_id).focus();
            } else {
                showErrorAlert(response.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorAlert(error.responseJSON?.message || 'Error en la solicitud');
        } finally {
            hideLoading();
        }
    }

    // Configuración para el escaneo de salida
    $('#scanOutForm').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            product_id: $('#product_id_out').val(),
            quantity: $('#quantity_out').val(),
            _token: "{{ csrf_token() }}"
        };

        handleScan(
            formData, 
            "{{ route('dispatches.scan.out', $dispatch->id) }}",
            'Producto registrado para salida'
        );
    });
    
    // Configuración para el escaneo de retorno
    $('#scanReturnForm').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            product_id: $('#product_id_return').val(),
            quantity: $('#quantity_return').val(),
            _token: "{{ csrf_token() }}"
        };

        handleScan(
            formData, 
            "{{ route('dispatches.scan.return', $dispatch->id) }}",
            'Producto registrado para retorno'
        );
    });

    // Actualizar tabla de productos
    function updateProductsTable(products) {
        const tbody = $('#scannedProductsTable tbody');
        tbody.empty();
        
        products.forEach(product => {
            tbody.append(`
                <tr>
                    <td>${product.product.product_name}</td>
                    <td>${product.quantity_out}</td>
                    <td>${product.quantity_returned}</td>
                    <td>${product.missing > 0 ? product.missing : 0}</td>
                </tr>
            `);
        });
    }

    // Funciones de utilidad para UI
    function showLoading() {
        $('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
    }

    function hideLoading() {
        $('button[type="submit"]').prop('disabled', false).html(function() {
            return $(this).data('original-text') || $(this).text();
        });
    }

    function showSuccessToast(message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: message
        });
    }

    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonText: 'Entendido'
        });
    }

    // Configuración para escaneo con lector de códigos de barras
    let barcode = '';
    let reading = false;

    $(document).keypress(function(e) {
        if (e.which >= 48 && e.which <= 57) {
            barcode += String.fromCharCode(e.which);
            if (!reading) {
                reading = true;
                setTimeout(() => {
                    if (barcode.length >= 6) { // Asumiendo códigos de mínimo 6 caracteres
                        handleBarcodeScan(barcode);
                    }
                    barcode = '';
                    reading = false;
                }, 500);
            }
        }
    });

    function handleBarcodeScan(code) {
        // Verificar qué campo tiene el foco
        if ($('#product_id_out').is(':focus')) {
            $('#product_id_out').val(code).trigger('change');
        } else if ($('#product_id_return').is(':focus')) {
            $('#product_id_return').val(code).trigger('change');
        }
    }
});
</script>
@endpush

@endsection