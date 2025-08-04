{{-- 
    |--------------------------------------------------------------------------
    | PLANTILLA BLADE PARA DASHBOARD DE INVENTARIO
    |--------------------------------------------------------------------------
    | 
    | Este archivo define la estructura del dashboard de inventario que incluye:
    | - Tres gráficos interactivos usando ECharts
    | - Mapa de clientes con clustering
    | - Diseño responsive y moderno
    | - Integración con FontAwesome para íconos
    |
--}}

{{-- Extiende la plantilla maestra ubicada en include.master --}}
@extends('include.master')

{{-- Define el título de la página que aparece en la pestaña del navegador --}}
@section('title','Inventory | Dashboard')

{{-- Define el título principal de la sección --}}
@section('page-title','Dashboard')

{{-- Inicia la sección de contenido principal --}}
@section('content')
    <info-box></info-box> <!-- NO MODIFICAR, JAMAS, NO TOCAR -->
    
    <!--  Mapa de Clientes -->
    <div style="max-width: 2600px; margin: 0px auto 2rem; width: 100%;">
        <div class="bg-white rounded shadow-sm" style="border-radius: .25rem;">
            <div style="position: relative; height: 50px; margin: 10px 0px;">
                <div class="d-flex justify-content-center align-items-center text-white"
                    style="position: absolute; top: 0; left: 0; right: 0; height: 50px;
                            background: linear-gradient(to left, #e85041, #a02b22);
                            border-top-left-radius: .25rem; border-top-right-radius: .25rem;
                            z-index: 1;">
                </div>
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 50px; z-index: 2;">
                    <i class="fas fa-users text-white" 
                    style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.5rem;">
                    </i>
                    <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; color: white; line-height: 50px;">
                    Mapa de Clientes
                    </span>
                </div>
            </div>
            <div class="p-3">
                <div id="miniMapaClientes" style="height: 400px; width: 100%;"></div>
                <div class="text-center mt-2">
                    <a href="{{ route('mapa.clientes') }}" class="btn btn-sm btn-danger">
                        Ver mapa completo <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!--  Ventas Mensuales y Ventas por Categoría -->
    <div style="display: flex; flex-wrap: wrap; gap: 2rem; max-width: 2600px; margin: 0 auto 2rem; width: 100%;">
        
        <!-- Gráfico 1: Ventas Mensuales -->
        <div style="flex: 1; min-width: 500px;">
            <div class="bg-white rounded shadow-sm" style="border-radius: .25rem; height: 100%;">
                <div style="position: relative; height: 50px; margin: 10px 0px;">
                    <div class="d-flex justify-content-center align-items-center text-white"
                        style="position: absolute; top: 0; left: 0; right: 0; height: 50px;
                                background: linear-gradient(to left, #e85041, #a02b22);
                                border-top-left-radius: .25rem; border-top-right-radius: .25rem;
                                z-index: 1;">
                    </div>
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 50px; z-index: 2;">
                        <i class="fas fa-chart-line text-white" 
                        style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.5rem;">
                        </i>
                        <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; color: white; line-height: 50px;">
                        Ventas Mensuales
                        </span>
                    </div>
                </div>
                <div class="p-3">
                    <div id="ventasMensuales" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfico 2: Ventas por Categoría -->
        <div style="flex: 1; min-width: 500px;">
            <div class="bg-white rounded shadow-sm" style="border-radius: .25rem; height: 100%;">
                <div style="position: relative; height: 50px; margin: 10px 0px;">
                    <div class="d-flex justify-content-center align-items-center text-white"
                        style="position: absolute; top: 0; left: 0; right: 0; height: 50px;
                                background: linear-gradient(to left, #e85041, #a02b22);
                                border-top-left-radius: .25rem; border-top-right-radius: .25rem;
                                z-index: 1;">
                    </div>
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 50px; z-index: 2;">
                        <i class="fas fa-chart-pie text-white" 
                        style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.5rem;">
                        </i>
                        <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; color: white; line-height: 50px;">
                        Ventas por Categoría
                        </span>
                    </div>
                </div>
                <div class="p-3">
                    <div id="ventasPorCategoria" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!--  Distribución de Productos -->
    <div style="max-width: 2600px; margin: 0px auto 2rem; width: 100%;">
        <div class="bg-white rounded shadow-sm" style="border-radius: .25rem;">
            <div style="position: relative; height: 50px; margin: 10px 0px;">
                <div class="d-flex justify-content-center align-items-center text-white"
                    style="position: absolute; top: 0; left: 0; right: 0; height: 50px;
                            background: linear-gradient(to left, #e85041, #a02b22);
                            border-top-left-radius: .25rem; border-top-right-radius: .25rem;
                            z-index: 1;">
                </div>
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 50px; z-index: 2;">
                    <i class="fas fa-chart-bar text-white" 
                    style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.5rem;">
                    </i>
                    <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; color: white; line-height: 50px;">
                    Distribución de Productos
                    </span>
                </div>
            </div>
            <div class="p-3">
                <div id="productosPorCategoria" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>
@endsection

{{-- ################################################################# --}}
{{-- SECCIÓN DE SCRIPTS --}}
{{-- ################################################################# --}}
@push('script')
    <script type="text/javascript" src="{{ url('public/js/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <!-- Leaflet JS para el mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
    <!-- Leaflet MarkerCluster -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <script>
        function makeChartResponsive(chart) {
            window.addEventListener('resize', function() {
                chart.resize();
            });
        }

        function createGradient(color1, color2) {
            return {
                type: 'linear',
                x: 0,
                y: 0,
                x2: 0,
                y2: 1,
                colorStops: [
                    { offset: 0, color: color1 },
                    { offset: 1, color: color2 }
                ]
            };
        }

        const etiquetasMeses = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];

        // Gráfico 1: Ventas Mensuales con Animación
        fetch("{{ route('dashboard.infobox') }}")
            .then(res => res.json())
            .then(data => {
                const chart = echarts.init(document.getElementById('ventasMensuales'));
                const option = {
                    animation: true,
                    animationDuration: 1500,
                    animationEasing: 'elasticOut',
                    animationDelay: function(idx) {
                        return idx * 100;
                    },
                    textStyle: {
                        color: '#000'
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: { type: 'shadow' }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: etiquetasMeses,
                        axisLabel: {
                            rotate: 45,
                            color: '#000'
                        }
                    },
                    yAxis: {
                        type: 'value',
                        name: 'Ventas',
                        axisLabel: {
                            color: '#000'
                        }
                    },
                    series: [{
                        name: 'Ventas',
                        type: 'bar',
                        data: data.monthly_sales.map((value, idx) => ({
                            value: value,
                            itemStyle: {
                                color: createGradient('#e85041', '#a02b22')
                            }
                        })),
                        label: {
                            show: true,
                            position: 'top',
                            color: '#000',
                            fontWeight: 'bold'
                        },
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        animationDelay: function(idx) {
                            return idx * 100;
                        }
                    }]
                };
                chart.setOption(option);
                makeChartResponsive(chart);
            });

        // Gráfico 2: Ventas por Categoría con Animación
        fetch("{{ url('sales-by-category') }}")
            .then(res => res.json())
            .then(data => {
                const categoriaTotal = {};
                data.forEach(item => {
                    const nombre = item.category;
                    categoriaTotal[nombre] = (categoriaTotal[nombre] || 0) + parseFloat(item.total);
                });

                const chart = echarts.init(document.getElementById('ventasPorCategoria'));
                const option = {
                    animation: true,
                    animationDuration: 2000,
                    animationEasing: 'cubicInOut',
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a}<br/>{b}: {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                        textStyle: {
                            fontSize: 10
                        }
                    },
                    series: [{
                        name: 'Ventas por Categoría',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: true,
                        label: {
                            formatter: function(params) {
                                const name = params.name.length > 12 ? 
                                    params.name.substring(0, 12) + '...' : 
                                    params.name;
                                return `${name}\n${params.value} (${params.percent}%)`;
                            },
                            fontSize: 10,
                            lineHeight: 16
                        },
                        labelLine: {
                            show: true
                        },
                        data: Object.entries(categoriaTotal).map(([name, value], index) => ({
                            value: value,
                            name: name,
                            itemStyle: {
                                color: createGradient(
                                    `hsl(${index * 360 / Object.keys(categoriaTotal).length}, 70%, 60%)`,
                                    `hsl(${index * 360 / Object.keys(categoriaTotal).length}, 70%, 40%)`
                                )
                            }
                        })),
                        emphasis: {
                            scale: true,
                            scaleSize: 10,
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        animationType: 'scale',
                        animationEasing: 'elasticOut',
                        animationDelay: function(idx) {
                            return Math.random() * 200;
                        }
                    }]
                };
                chart.setOption(option);
                makeChartResponsive(chart);
            });

        // Gráfico 3: Distribución de Productos con Animación
        fetch("{{ route('dashboard.product-distribution') }}")
            .then(res => res.json())
            .then(data => {
                const chart = echarts.init(document.getElementById('productosPorCategoria'));
                const option = {
                    animation: true,
                    animationDuration: 1800,
                    animationEasing: 'backOut',
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a}<br/>{b}: {c} ({d}%)'
                    },
                    legend: {
                        type: 'scroll',
                        orient: 'horizontal',
                        bottom: 0,
                        textStyle: {
                            fontSize: 10
                        }
                    },
                    series: [{
                        name: 'Distribución',
                        type: 'pie',
                        radius: ['30%', '60%'],
                        avoidLabelOverlap: true,
                        label: {
                            show: true,
                            formatter: '{b}: {c} ({d}%)',
                            fontSize: 10
                        },
                        labelLine: {
                            show: true
                        },
                        data: data.map((item, index) => ({
                            value: item.count,
                            name: item.category,
                            itemStyle: {
                                color: createGradient(
                                    `hsl(${index * 360 / data.length}, 70%, 60%)`,
                                    `hsl(${index * 360 / data.length}, 70%, 40%)`
                                )
                            }
                        })),
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        animationType: 'expansion',
                        animationEasing: 'elasticOut',
                        animationDelay: function(idx) {
                            return idx * 150;
                        }
                    }]
                };
                chart.setOption(option);
                makeChartResponsive(chart);
            });

        // Mapa de Clientes con Clustering
        document.addEventListener('DOMContentLoaded', function() {
            const miniMap = L.map('miniMapaClientes').setView([19.4326, -99.1332], 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(miniMap);
            
            // Icono personalizado
            const customIcon = L.icon({
                iconUrl: 'https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678111-map-marker-512.png',
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });
            
            // Crear grupo de clusters
            const markers = L.markerClusterGroup({
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                maxClusterRadius: 40,
                iconCreateFunction: function(cluster) {
                    const childCount = cluster.getChildCount();
                    let size = 'medium';
                    if (childCount < 10) {
                        size = 'small';
                    } else if (childCount > 50) {
                        size = 'large';
                    }
                    
                    return L.divIcon({
                        html: '<div><span>' + childCount + '</span></div>',
                        className: 'marker-cluster marker-cluster-' + size,
                        iconSize: new L.Point(40, 40)
                    });
                }
            });
            
            // Cargar clientes via AJAX
            fetch('/api/clientes')
                .then(response => response.json())
                .then(clientes => {
                    clientes.forEach(cliente => {
                        if(cliente.lat && cliente.lon) {
                            const marker = L.marker([cliente.lat, cliente.lon], {icon: customIcon})
                                .bindPopup(`
                                    <div style="min-width: 200px;">
                                        <h5 style="color: #e85041; margin-bottom: 5px;">${cliente.customer_name}</h5>
                                        <p><strong>Dirección:</strong> ${cliente.address}</p>
                                        <p><strong>Teléfono:</strong> ${cliente.phone}</p>
                                        <p><strong>Email:</strong> ${cliente.email}</p>
                                    </div>
                                `);
                            markers.addLayer(marker);
                        }
                    });
                    
                    miniMap.addLayer(markers);
                    
                    // Ajustar vista si hay clientes
                    if(clientes.filter(c => c.lat && c.lon).length > 0) {
                        miniMap.fitBounds(markers.getBounds());
                    }
                    
                    // Mostrar contador de clientes
                    const clientCount = clientes.filter(c => c.lat && c.lon).length;
                    const countElement = document.createElement('div');
                    countElement.style.position = 'absolute';
                    countElement.style.bottom = '10px';
                    countElement.style.right = '10px';
                    countElement.style.zIndex = '1000';
                    countElement.style.background = 'rgba(255,255,255,0.9)';
                    countElement.style.padding = '5px 10px';
                    countElement.style.borderRadius = '3px';
                    countElement.style.border = '1px solid #e85041';
                    countElement.style.fontWeight = 'bold';
                    countElement.textContent = `Clientes: ${clientCount}`;
                    
                    document.getElementById('miniMapaClientes').appendChild(countElement);
                });
            
            // Ajustar el mapa al cambiar tamaño de ventana
            window.addEventListener('resize', function() {
                setTimeout(() => miniMap.invalidateSize(), 100);
            });
        });
    </script>
    
    <style>
        .marker-cluster {
            background-clip: padding-box;
            border-radius: 20px;
            background-color: rgba(232, 80, 65, 0.6);
        }
        .marker-cluster div {
            width: 30px;
            height: 30px;
            margin-left: 5px;
            margin-top: 5px;
            text-align: center;
            border-radius: 15px;
            font: 12px "Helvetica Neue", Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: white;
            background-color: rgba(160, 43, 34, 0.8);
        }
        .marker-cluster-small {
            background-color: rgba(232, 80, 65, 0.6);
        }
        .marker-cluster-medium {
            background-color: rgba(232, 80, 65, 0.7);
        }
        .marker-cluster-large {
            background-color: rgba(232, 80, 65, 0.8);
        }
    </style>
@endpush