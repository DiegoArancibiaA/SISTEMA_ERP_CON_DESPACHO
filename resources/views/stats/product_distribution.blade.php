@extends('include.frame_graph')
@section('title', 'AlphaERP')
@section('page-title','Estadísticas - Distribución de Productos')

@section('content')
    {{-- GRÁFICO 2: DISTRIBUCIÓN DE PRODUCTOS --}}
<div class="container-graf">
    <div class="chart-responsive-container">
        <div class="bg-white rounded shadow-sm chart-card">

            <div class="chart-header">
                <div class="chart-header-bg"></div>
                <div class="chart-header-content">
                    <i class="fas fa-chart-pie chart-icon"></i>
                    <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; 
                    background: linear-gradient(to left, #e85041, #a02b22; line-height: 40px;">
                    Distribución de Productos
                    </span>
                </div>
            </div>

            <div class="p-3 chart-body">
                <div id="productosPorCategoria" class="echarts-responsive"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-header {
        position: relative;
        height: 50px;
        margin: 10px 0;
    }
    .chart-header-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(to left, #e85041, #a02b22);
        border-radius: .25rem;
        z-index: 1;
    }
    .chart-header-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50px;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .chart-icon {
        font-size: 1.5rem;
        color: white;
    }
    .chart-title {
        font-size: 1.8rem;
        font-weight: 500;
        color: white;
        line-height: 50px;
    }
    .echarts-responsive {
        width: 100%;
        min-height: 400px;
    }
    .chart-body {
        padding: 15px;
    }
</style>

@endsection

@push('script')
    {{-- Script base del dashboard --}}
    <script type="text/javascript" src="{{ url('public/js/dashboard.js') }}"></script>

    {{-- CDN de ECharts --}}
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

    {{-- CDN de FontAwesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        // Función para hacer el gráfico responsive
        function makeChartResponsive(chart) {
            function handleResize() {
                const container = document.querySelector('.chart-responsive-container');
                if (container) {
                    const width = container.clientWidth;
                    let height;
                    
                    if (width > 1200) {
                        height = Math.min(width * 0.5, 800);
                    } else if (width > 768) {
                        height = width * 0.6;
                    } else {
                        height = width * 0.8;
                    }
                    
                    chart.getDom().style.height = `${height}px`;
                    chart.resize();
                }
            }
            
            // Usar ResizeObserver para mejor detección de cambios
            const resizeObserver = new ResizeObserver(handleResize);
            const chartContainer = document.getElementById('productosPorCategoria');
            if (chartContainer) {
                resizeObserver.observe(chartContainer);
            }
            
            window.addEventListener('orientationchange', handleResize);
            handleResize();
        }

        // Función para crear gradientes
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

        document.addEventListener('DOMContentLoaded', function() {
            const chartDom = document.getElementById('productosPorCategoria');
            
            if (!chartDom) {
                console.error('No se encontró el elemento con ID "productosPorCategoria"');
                return;
            }
            
            const myChart = echarts.init(chartDom);
            
            // Configuración mejorada con animaciones
            const option = {
                animation: true,
                animationDuration: 2000,
                animationEasing: 'cubicInOut',
                tooltip: {
                    trigger: 'item',
                    formatter: function(params) {
                        return `
                            <div style="font-weight:bold;margin-bottom:5px">${params.name}</div>
                            <div>Cantidad: <span style="font-weight:bold;color:#e85041">${params.value}</span></div>
                            <div>Porcentaje: <span style="font-weight:bold;color:#e85041">${params.percent}%</span></div>
                        `;
                    },
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    borderColor: '#e85041',
                    borderWidth: 1,
                    textStyle: {
                        color: '#333'
                    }
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    textStyle: {
                        fontSize: 12,
                        fontWeight: 'bold'
                    },
                    formatter: function(name) {
                        return name.length > 15 ? name.substring(0, 15) + '...' : name;
                    },
                    tooltip: {
                        show: true
                    }
                },
                series: [{
                    name: 'Distribución de Productos',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: true,
                    itemStyle: {
                        borderRadius: 8,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: true,
                        formatter: function(params) {
                            const name = params.name.length > 12 ? 
                                params.name.substring(0, 12) + '...' : 
                                params.name;
                            return `${name}\n${params.value} (${params.percent}%)`;
                        },
                        fontSize: 12,
                        lineHeight: 16,
                        color: '#333',
                        fontWeight: 'bold'
                    },
                    labelLine: {
                        show: true,
                        length: 10,
                        length2: 5
                    },
                    emphasis: {
                        scale: true,
                        scaleSize: 10,
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        },
                        label: {
                            show: true,
                            fontSize: 14,
                            fontWeight: 'bold'
                        }
                    },
                    data: [],
                    animationType: 'scale',
                    animationEasing: 'elasticOut',
                    animationDelay: function(idx) {
                        return Math.random() * 200;
                    }
                }]
            };
            
            myChart.setOption(option);
            
            // Cargar datos con animación de carga
            fetch("{{ route('dashboard.product-distribution') }}")
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data?.length) {
                        const chartData = data.map((item, index) => ({
                            value: item.count,
                            name: item.category,
                            itemStyle: {
                                color: createGradient(
                                    `hsl(${index * 360 / data.length}, 70%, 60%)`,
                                    `hsl(${index * 360 / data.length}, 70%, 40%)`
                                )
                            }
                        }));
                        
                        myChart.setOption({
                            legend: {
                                data: data.map(d => d.category)
                            },
                            series: [{
                                data: chartData
                            }]
                        });
                        
                        // Efecto de resaltado inicial
                        setTimeout(() => {
                            if (data.length > 0) {
                                myChart.dispatchAction({
                                    type: 'highlight',
                                    seriesIndex: 0,
                                    dataIndex: 0
                                });
                            }
                        }, 2000);
                    } else {
                        throw new Error('No hay datos disponibles');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    myChart.setOption({
                        graphic: {
                            type: 'text',
                            left: 'center',
                            top: 'middle',
                            style: {
                                text: 'Error al cargar datos',
                                fill: '#e85041',
                                fontSize: 18
                            }
                        }
                    });
                });
            
            makeChartResponsive(myChart);
            
            // Efecto hover personalizado
            myChart.on('mouseover', function(params) {
                myChart.dispatchAction({
                    type: 'downplay',
                    seriesIndex: 0
                });
                myChart.dispatchAction({
                    type: 'highlight',
                    seriesIndex: 0,
                    dataIndex: params.dataIndex
                });
            });
            
            myChart.on('globalout', function() {
                myChart.dispatchAction({
                    type: 'downplay',
                    seriesIndex: 0
                });
                // Resaltar el primer elemento por defecto
                myChart.dispatchAction({
                    type: 'highlight',
                    seriesIndex: 0,
                    dataIndex: 0
                });
            });
        });
    </script>
@endpush