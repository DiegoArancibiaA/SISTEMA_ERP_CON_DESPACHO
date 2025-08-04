@extends('include.frame_graph')

@section('content')
<div class="container-fluid p-0" style="height: calc(100vh - 150px);">
    <div class="d-flex justify-content-end mb-2">
        <button id="geocodeBtn" class="btn btn-primary">
            <i class="fas fa-map-marker-alt"></i> Generar Coordenadas
        </button>
        <div id="geocodeStatus" class="alert alert-info ml-2 mb-0 p-2 d-none"></div>
    </div>
    <div id="map" style="height: calc(100% - 10px); width: 100%;"></div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el mapa
    var map = L.map('map').setView([19.4326, -99.1332], 13);
    
    // Añadir capa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Crear grupo de clusters
    var markers = L.markerClusterGroup();

    // Datos de clientes desde PHP
    var customers = @json($customers);
    
    console.log('Clientes recibidos:', customers);

    // Añadir marcadores para cada cliente
    customers.forEach(function(customer) {
        if(customer.latitude && customer.longitude) {
            var marker = L.marker([customer.latitude, customer.longitude])
                .bindPopup(`
                    <div style="min-width: 200px;">
                        <h6><b>${customer.customer_name || 'Sin nombre'}</b></h6>
                        <p class="mb-1"><i class="fas fa-map-marker-alt"></i> ${customer.address || 'Sin dirección'}</p>
                        <p class="mb-1"><i class="fas fa-phone"></i> ${customer.phone || 'Sin teléfono'}</p>
                        ${customer.email ? `<p class="mb-0"><i class="fas fa-envelope"></i> ${customer.email}</p>` : ''}
                    </div>
                `);
            markers.addLayer(marker);
        }
    });

    // Añadir todos los marcadores al mapa
    map.addLayer(markers);

    // Ajustar vista para mostrar todos los marcadores
    if(customers.length > 0) {
        map.fitBounds(markers.getBounds());
    }

    // Forzar redimensionamiento
    setTimeout(function() {
        map.invalidateSize();
    }, 100);

    // Manejar clic en el botón de generación de coordenadas
    document.getElementById('geocodeBtn').addEventListener('click', function() {
        const btn = this;
        const statusDiv = document.getElementById('geocodeStatus');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        statusDiv.classList.remove('d-none', 'alert-danger', 'alert-success');
        statusDiv.classList.add('alert-info');
        statusDiv.innerHTML = 'Ejecutando comando de generación de coordenadas...';
        
        // Llamada AJAX para ejecutar el comando
        fetch('{{ route("geocode.execute") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || 'Error en el servidor'); });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                statusDiv.classList.remove('alert-info');
                statusDiv.classList.add('alert-success');
                statusDiv.innerHTML = `Geocodificación completada. ${data.processed} registros actualizados.`;
                
                // Mostrar salida del comando en consola para depuración
                console.log('Salida del comando:', data.output);
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            statusDiv.classList.remove('alert-info');
            statusDiv.classList.add('alert-danger');
            statusDiv.innerHTML = error.message || 'Error al ejecutar el comando';
        })
    });
});
</script>

<style>
    #geocodeBtn {
        transition: all 0.3s ease;
    }
    #geocodeBtn:disabled {
        opacity: 0.7;
    }
    #geocodeStatus {
        transition: all 0.3s ease;
        max-width: 400px;
    }
    .leaflet-popup-content {
        margin: 10px;
    }
    .fa-spinner {
        margin-right: 5px;
    }
</style>
@endsection