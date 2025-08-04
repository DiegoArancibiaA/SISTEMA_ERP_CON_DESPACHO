
@extends('include.frame_graph')

@section('title','Stock por Categoría')
@section('page-title','Estadísticas - Stock por Categoría')

@section('content')
<div class="container-graf">
    <div class="chart-responsive-container">
        <div class="bg-white rounded shadow-sm chart-card">

            <div class="chart-header">
                <div class="chart-header-bg"></div>
                <div class="chart-header-content">
                    <i class="fas fa-chart-line chart-icon"
                    style="display: block; width: 100%; text-align: center; font-size: 1.8rem; 
                    font-weight: 500; color: white; line-height: 40px;">
                    </i>
                    <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; 
                    background: linear-gradient(to left, #e85041, #a02b22); line-height: 40px;">Stock por Categoría</span>
                </div>
            </div>

            <div class="p-3 chart-body">
                <div id="graficoStockCategoria" class="echarts-responsive" style="min-height: 500px;" ></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    {{-- Script base del dashboard --}}
    <script type="text/javascript" src="{{ url('public/js/dashboard.js') }}"></script>

    {{-- CDN de ECharts --}}
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

    {{-- CDN de FontAwesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
// ========================================================
// BLOQUE QUE CONTROLA EL TAMAÑO DEL GRÁFICO (FUNCIÓN RESPONSIVE)
// ========================================================
        function makeChartResponsive(chart) {
            // Redimensionar inmediatamente y al cambiar tamaño
            function handleResize() {
                const container = document.querySelector('.chart-responsive-container');
                if (container) {
                    // Calcular altura en base al ancho (relación de aspecto)
                    const width = container.clientWidth;
                    let height;
                    
                    if (width > 1200) {
                        // Pantallas grandes: altura mayor
                        height = Math.min(width * 0.54, 1300); // Máximo 800px
                    } else if (width > 768) {
                        // Pantallas medianas
                        height = width * 0.6;
                    } else {
                        // Móviles: usar más espacio vertical
                        height = width * 0.4;
                    }
                    
                    // Aplicar la altura al contenedor del gráfico
                    chart.getDom().style.height = `${height}px`;
                    chart.resize();
                }
            }
            
            // Eventos que activan el redimensionamiento
            window.addEventListener('resize', handleResize);
            window.addEventListener('orientationchange', handleResize);
            
            handleResize(); // Ejecuta inicialmente
        }

    document.addEventListener('DOMContentLoaded', function () {
        // Verificar si hay datos
        const stockData = @json($stockPorCategoria ?? []);
        if (!stockData || stockData.length === 0) {
            document.getElementById('graficoStockCategoria').innerHTML = 
                '<div class="text-center p-4 text-muted">No hay datos disponibles</div>';
            return;
        }

        // Inicializar el gráfico
        const chartDom = document.getElementById('graficoStockCategoria');
        const myChart = echarts.init(chartDom);
        
        // Configuración de umbrales
        const UMBRAL_STOCK_BAJO = 20;
        const UMBRAL_STOCK_MEDIO = 50;

        // Opciones del gráfico
        const option = {
            title: { 
                text: '', 
                left: 'center',
                textStyle: { 
                    fontWeight: 'bold',
                    fontSize: 16,
                    color: '#333'
                }
            },
            tooltip: { 
                trigger: 'axis', 
                axisPointer: { type: 'shadow' },
                formatter: function(params) {
                    const data = params[0];
                    return `
                        <strong>${data.name}</strong><br/>
                        Stock: <b>${data.value}</b> unidades
                        ${data.value < UMBRAL_STOCK_BAJO ? '⚠️ <span style="color:red">(CRÍTICO)</span>' : ''}
                    `;
                }
            },
            grid: {
                top: '15%',
                left: '3%',
                right: '4%',
                bottom: '15%',
                containLabel: true
            },
            xAxis: { 
                type: 'category', 
                data: stockData.map(e => e.categoria), 
                axisLabel: { 
                    rotate: 30,
                    fontWeight: 'bold',
                    interval: 0,
                    color: '#000' // Texto en negro
                },
                axisLine: {
                    lineStyle: {
                        color: '#ddd'
                    }
                },
                axisTick: {
                    alignWithLabel: true
                }
            },
            yAxis: { 
                type: 'value', 
                name: 'Unidades en Stock',
                nameLocation: 'middle',
                nameGap: 30,
                nameTextStyle: {
                    fontWeight: 'bold',
                    color: '#000' // Texto en negro
                },
                axisLine: { 
                    show: true,
                    lineStyle: {
                        color: '#ddd'
                    }
                },
                axisLabel: {
                    color: '#000' // Texto en negro
                },
                splitLine: {
                    lineStyle: {
                        color: '#f0f0f0'
                    }
                }
            },
            visualMap: {
                type: 'piecewise',
                pieces: [
                    { gt: UMBRAL_STOCK_MEDIO, label: 'Stock Alto', color: '#2E7D32' },
                    { gte: UMBRAL_STOCK_BAJO, lte: UMBRAL_STOCK_MEDIO, label: 'Stock Medio', color: '#FF8F00' },
                    { lt: UMBRAL_STOCK_BAJO, label: 'Stock Bajo', color: '#C62828' }
                ],
                orient: 'horizontal',
                left: 'center',
                bottom: '0%',
                textStyle: { 
                    fontWeight: 'bold',
                    color: '#000' // Texto en negro
                },
                itemGap: 10,
                itemWidth: 20,
                itemHeight: 12
            },
            series: [{
                name: 'Stock',
                type: 'bar',
                data: stockData.map(e => ({
                    value: e.total,
                    name: e.categoria,
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: e.total < UMBRAL_STOCK_BAJO ? '#EF5350' : 
                                     e.total < UMBRAL_STOCK_MEDIO ? '#FFA000' : '#388E3C' },
                            { offset: 1, color: e.total < UMBRAL_STOCK_BAJO ? '#C62828' : 
                                     e.total < UMBRAL_STOCK_MEDIO ? '#E65100' : '#1B5E20' }
                        ])
                    },
                    label: {
                        show: e.total < UMBRAL_STOCK_BAJO,
                        position: 'top',
                        formatter: '{c} ⚠️',
                        color: '#000', // Texto en negro
                        fontWeight: 'bold',
                        fontSize: 12
                    }
                })),
                barWidth: '60%',
                itemStyle: {
                    borderRadius: [5, 5, 0, 0],
                    borderColor: '#fff',
                    borderWidth: 1
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
                animation: true,
                animationDuration: 1500,
                animationEasing: 'elasticOut',
                animationDelay: function (idx) {
                    return idx * 100;
                }
            }]
        };

        // Aplicar las opciones
        myChart.setOption(option);

        // Función de redimensionamiento mejorada
        function resizeChart() {
            myChart.resize();
        }

        // Usar ResizeObserver para mejores resultados
        const observer = new ResizeObserver(resizeChart);
        observer.observe(chartDom);

        // Redimensionar inicialmente
        setTimeout(resizeChart, 100);

        // Hacer el gráfico responsive
        makeChartResponsive(myChart);

        // Efecto de movimiento al pasar el mouse
        chartDom.addEventListener('mousemove', function() {
            myChart.dispatchAction({
                type: 'highlight',
                seriesIndex: 0
            });
        });

        // Restaurar cuando el mouse sale
        chartDom.addEventListener('mouseout', function() {
            myChart.dispatchAction({
                type: 'downplay',
                seriesIndex: 0
            });
        });
    });
</script>
@endpush
