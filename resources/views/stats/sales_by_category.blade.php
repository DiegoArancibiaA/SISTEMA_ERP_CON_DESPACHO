@extends('include.frame_graph')
@section('title','Ventas por Categoría')
@section('page-title','Estadísticas - Ventas por Categoría')

@section('content')
    {{-- GRÁFICO 3: VENTAS POR CATEGORÍA --}}
<div class="container-graf">
    <div class="chart-responsive-container">
        <div class="bg-white rounded shadow-sm chart-card">

            <div class="chart-header">
                <div class="chart-header-bg"></div>
                <div class="chart-header-content">
                    <i class="fas fa-chart-bar chart-icon"></i>
                    <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; 
                    background: linear-gradient(to left, #e85041, #a02b22; line-height: 40px;">Ventas por Categoría</span>
                </div>
            </div>

            <div class="p-3 chart-body">
                <div id="ventasPorCategoria" class="echarts-responsive"></div>
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
            const chartContainer = document.getElementById('ventasPorCategoria');
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
            const chartDom = document.getElementById('ventasPorCategoria');
            
            if (!chartDom) {
                console.error('No se encontró el elemento con ID "ventasPorCategoria"');
                return;
            }
            
            const myChart = echarts.init(chartDom);
            
            // Configuración mejorada con animaciones
            const option = {
                animation: true,
                animationDuration: 1500,
                animationEasing: 'elasticOut',
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: function(params) {
                        return `
                            <div style="font-weight:bold;margin-bottom:5px">${params[0].name}</div>
                            <div>Ventas: <span style="font-weight:bold;color:#e85041">$${params[0].value.toLocaleString('es-ES')}</span></div>
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
                    axisLabel: {
                        rotate: 45,
                        interval: 0,
                        fontSize: function() {
                            return Math.max(10, Math.min(12, window.innerWidth / 100));
                        },
                        color: '#333',
                        formatter: function(value) {
                            return value.length > 12 ? value.substring(0, 12) + '...' : value;
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#ccc'
                        }
                    },
                    axisTick: {
                        alignWithLabel: true
                    },
                    data: []
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
                    barWidth: '60%',
                    itemStyle: {
                        color: function(params) {
                            return createGradient(
                                `hsl(${params.dataIndex * 360 / 8}, 70%, 60%)`,
                                `hsl(${params.dataIndex * 360 / 8}, 70%, 40%)`
                            );
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
                    animationType: 'scale',
                    animationEasing: 'elasticOut',
                    animationDelay: function(idx) {
                        return idx * 100;
                    }
                }]
            };
            
            myChart.setOption(option);
            
            // Cargar datos con animación de carga
            fetch("{{ url('sales-by-category') }}")
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data?.length) {
                        const categoriaTotal = {};
                        data.forEach(item => {
                            const nombre = item.category;
                            categoriaTotal[nombre] = (categoriaTotal[nombre] || 0) + parseFloat(item.total);
                        });

                        const categorias = Object.keys(categoriaTotal);
                        const totales = Object.values(categoriaTotal);

                        myChart.setOption({
                            xAxis: {
                                data: categorias
                            },
                            series: [{
                                data: totales.map((value, index) => ({
                                    value: value,
                                    itemStyle: {
                                        color: createGradient(
                                            `hsl(${index * 360 / categorias.length}, 70%, 60%)`,
                                            `hsl(${index * 360 / categorias.length}, 70%, 40%)`
                                        )
                                    }
                                }))
                            }]
                        });

                        // Efecto de resaltado inicial
                        setTimeout(() => {
                            if (categorias.length > 0) {
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