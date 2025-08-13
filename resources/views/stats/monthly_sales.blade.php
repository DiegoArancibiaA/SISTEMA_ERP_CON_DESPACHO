@extends('include.frame_graph')

@section('title', 'AlphaERP')
@section('page-title','Estadísticas - Ventas Mensuales')

@section('content')
    {{-- GRÁFICO 1: VENTAS MENSUALES --}}
<div class="container-graf">
    <div class="chart-responsive-container">
        <div class="bg-white rounded shadow-sm chart-card">

            <div class="chart-header">
                <div class="chart-header-bg"></div>
                <div class="chart-header-content">
                    <i class="fas fa-chart-line chart-icon"></i>
                    <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; 
                    background: linear-gradient(to left, #e85041, #a02b22; line-height: 40px;">Ventas Mensuales</span>
                </div>
            </div>

            <div class="p-3 chart-body">
                <div id="ventasMensuales" class="echarts-responsive"></div>
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
        border-radius: .25rem .25rem 0 0;
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
        color: white; /* Color blanco para contraste con el fondo rojo */
        line-height: 50px;
    }
    .echarts-responsive {
        width: 100%;
        min-height: 400px;
    }
    .chart-card {
        border: 1px solid #e0e0e0; /* Borde sutil para la tarjeta */
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
            const chartContainer = document.getElementById('ventasMensuales');
            if (chartContainer) {
                resizeObserver.observe(chartContainer);
            }
            
            window.addEventListener('orientationchange', handleResize);
            handleResize();
        }

        // Etiquetas de meses
        const etiquetasMeses = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];

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
            const chartDom = document.getElementById('ventasMensuales');
            
            if (!chartDom) {
                console.error('No se encontró el elemento con ID "ventasMensuales"');
                return;
            }
            
            const myChart = echarts.init(chartDom);
            
            // Configuración mejorada con animaciones
            const option = {
                animation: true,
                animationDuration: 1500,
                animationEasing: 'elasticOut',
                animationDelay: function(idx) {
                    return idx * 100;
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        const month = etiquetasMeses[params[0].dataIndex];
                        const value = params[0].value.toLocaleString('es-ES');
                        return `
                            <div style="font-weight:bold;margin-bottom:5px">${month}</div>
                            <div>Ventas: <span style="font-weight:bold;color:#e85041">$${value}</span></div>
                        `;
                    },
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    borderColor: '#e85041',
                    borderWidth: 1,
                    textStyle: {
                        color: '#333'
                    }
                },
                grid: {
                    left: '5%',
                    right: '5%',
                    bottom: '15%',
                    top: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: etiquetasMeses,
                    axisLabel: {
                        rotate: 30,
                        interval: 0,
                        fontSize: function() {
                            return Math.max(10, Math.min(14, window.innerWidth / 100));
                        },
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#ccc'
                        }
                    },
                    axisTick: {
                        alignWithLabel: true
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Ventas ($)',
                    nameTextStyle: {
                        color: '#666',
                        fontSize: 12
                    },
                    axisLabel: {
                        formatter: function(value) {
                            return '$' + value.toLocaleString('es-ES');
                        },
                        color: '#333'
                    },
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: '#ccc'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: '#f0f0f0'
                        }
                    }
                },
                series: [{
                    name: 'Ventas',
                    type: 'bar',
                    data: [],
                    itemStyle: {
                        color: function(params) {
                            return createGradient('#e85041', '#a02b22');
                        },
                        borderRadius: [4, 4, 0, 0],
                        shadowColor: 'rgba(0,0,0,0.2)',
                        shadowBlur: 5,
                        shadowOffsetY: 2
                    },
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    },
                    label: {
                        show: true,
                        position: 'top',
                        formatter: function(params) {
                            return '$' + params.value.toLocaleString('es-ES');
                        },
                        fontSize: function() {
                            return Math.max(10, Math.min(12, window.innerWidth / 100));
                        },
                        color: '#333',
                        fontWeight: 'bold'
                    },
                    barWidth: '60%',
                    animationType: 'scale',
                    animationEasing: 'elasticOut'
                }]
            };
            
            myChart.setOption(option);
            
            // Cargar datos con animación de carga
            fetch("{{ route('dashboard.infobox') }}")
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data?.monthly_sales?.length) {
                        // Animación de carga progresiva
                        const animatedData = data.monthly_sales.map((val, idx) => ({
                            value: val,
                            itemStyle: {
                                color: createGradient(
                                    `hsl(${idx * 30}, 70%, 60%)`,
                                    `hsl(${idx * 30}, 70%, 40%)`
                                )
                            }
                        }));
                        
                        myChart.setOption({
                            series: [{
                                data: animatedData,
                                animationDelay: function(idx) {
                                    return idx * 100;
                                }
                            }]
                        });
                        
                        // Efecto de resaltado inicial
                        setTimeout(() => {
                            myChart.dispatchAction({
                                type: 'highlight',
                                seriesIndex: 0,
                                dataIndex: new Date().getMonth()
                            });
                        }, 2000);
                    } else {
                        throw new Error('Formato de datos inválido');
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
                // Resaltar el mes actual
                myChart.dispatchAction({
                    type: 'highlight',
                    seriesIndex: 0,
                    dataIndex: new Date().getMonth()
                });
            });
        });
    </script>
@endpush