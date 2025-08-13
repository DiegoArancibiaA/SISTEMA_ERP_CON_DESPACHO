@extends('include.frame_graph')
@section('title', 'AlphaERP')
@section('page-title','Estadísticas - Top Productos')

@section('content')
<div class="container-graf">
    <div class="chart-responsive-container">
        <div class="bg-white rounded shadow-sm chart-card">
            <div class="chart-header">
                <div class="chart-header-bg"></div>
                <div class="chart-header-content">
                    <i class="fas fa-chart-bar chart-icon"
                       style="display: block; width: 100%; text-align: center; font-size: 1.8rem; 
                       font-weight: 500; color: white; line-height: 40px;">
                    </i>
                    <span style="display: block; width: 100%; text-align: center; font-size: 1.8rem; font-weight: 500; 
                       background: linear-gradient(to left, #e85041, #a02b22); line-height: 40px;">
                       Top 5 Productos Más Vendidos
                    </span>
                </div>
            </div>
            <div class="p-3 chart-body">
                <div id="graficoTopProductos" class="echarts-responsive"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    // Función responsive mejorada
    function makeChartResponsive(chart) {
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
            
        window.addEventListener('resize', handleResize);
        window.addEventListener('orientationchange', handleResize);
        handleResize();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const chartDom = document.getElementById('graficoTopProductos');
        const myChart = echarts.init(chartDom);
        const datos = @json($datos ?? []);
        
        if (!datos || datos.length === 0) {
            chartDom.innerHTML = '<div class="text-center p-4 text-muted">No hay datos disponibles</div>';
            return;
        }

        // Preparar datos
        const nombres = datos.map(item => item.product_name);
        const cantidades = datos.map(item => item.total);

        // Paleta de gradientes oscuros para cada barra
        const gradientColors = [
            { start: '#2E7D32', end: '#1B5E20' },  // Verde oscuro
            { start: '#1565C0', end: '#0D47A1' },  // Azul oscuro
            { start: '#6A1B9A', end: '#4A148C' },   // Morado oscuro
            { start: '#C62828', end: '#B71C1C' },   // Rojo oscuro
            { start: '#FF8F00', end: '#E65100' }    // Naranja oscuro
        ];

        // Configuración del gráfico con animaciones y gradientes
        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: { type: 'shadow' },
                formatter: function(params) {
                    const data = params[0];
                    return `<strong>${data.name}</strong><br/>Vendidos: <b>${data.value}</b> unidades`;
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '15%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: nombres,
                axisLabel: {
                    rotate: 30,
                    interval: 0,
                    color: '#000', // Texto negro
                    formatter: function(value) {
                        return value.length > 15 ? value.substring(0, 15) + '...' : value;
                    }
                },
                axisLine: { lineStyle: { color: '#ddd' } },
                axisTick: { alignWithLabel: true }
            },
            yAxis: {
                type: 'value',
                name: 'Unidades Vendidas',
                nameLocation: 'middle',
                nameGap: 30,
                nameTextStyle: {
                    color: '#000', // Texto negro
                    fontWeight: 'bold'
                },
                axisLine: { lineStyle: { color: '#ddd' } },
                axisLabel: { color: '#000' }, // Texto negro
                splitLine: { lineStyle: { color: '#f5f5f5' } }
            },
            series: [{
                name: 'Ventas',
                type: 'bar',
                barWidth: '60%',
                data: cantidades.map((value, index) => ({
                    value: value,
                    name: nombres[index],
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: gradientColors[index % gradientColors.length].start },
                            { offset: 1, color: gradientColors[index % gradientColors.length].end }
                        ]),
                        borderRadius: [4, 4, 0, 0],
                        borderWidth: 1,
                        borderColor: '#fff'
                    }
                })),
                emphasis: {
                    itemStyle: {
                        shadowBlur: 15,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.3)'
                    }
                },
                label: {
                    show: true,
                    position: 'top',
                    formatter: '{c}',
                    color: '#000', // Texto negro
                    fontWeight: 'bold',
                    fontSize: 12
                },
                animationType: 'elastic',
                animationDuration: 1500,
                animationEasing: 'cubicOut',
                animationDelay: function(idx) {
                    return idx * 200;
                }
            }]
        };

        myChart.setOption(option);
        makeChartResponsive(myChart);
        
        // Efecto hover interactivo
        chartDom.addEventListener('mousemove', function() {
            myChart.dispatchAction({
                type: 'highlight',
                seriesIndex: 0
            });
        });

        chartDom.addEventListener('mouseout', function() {
            myChart.dispatchAction({
                type: 'downplay',
                seriesIndex: 0
            });
        });
    });
</script>
@endpush