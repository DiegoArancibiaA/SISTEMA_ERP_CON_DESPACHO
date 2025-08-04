@extends('include.frame_graph')
 
@section('title', 'Ventas por Método de Pago')
@section('page-title', 'Ventas por Método de Pago')

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
                    background: linear-gradient(to left, #e85041, #a02b22); line-height: 40px;">Ventas por Método de Pago</span>
                </div>
            </div>
            <div class="p-3 chart-body">
                <div id="graficoMetodoPago" style="width: 100%; min-height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    // Función responsive CORREGIDA que afecta al gráfico, no solo al contenedor
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
        
        
        // Usamos ResizeObserver para máxima precisión
        const observer = new ResizeObserver(handleResize);
        observer.observe(chart.getDom().parentElement);
        
        // También escuchamos eventos de resize por si acaso
        window.addEventListener('resize', handleResize);
        
        // Ejecutamos inmediatamente
        handleResize();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const datos = @json($datos ?? []);
        const chartDom = document.getElementById('graficoMetodoPago');
        const myChart = echarts.init(chartDom);
        
        if (!datos || datos.length === 0) {
            chartDom.innerHTML = '<div class="text-center p-4 text-muted">No hay datos disponibles</div>';
            return;
        }

        // Mapeo de métodos de pago
        const metodos = datos.map(item => {
            switch(item.payment_method.toString()) {
                case '1': return 'Pago Efectivo';
                case '2': return 'Transferencia Bancaria';
                default: return 'Otro Método';
            }
        });
        
        const totales = datos.map(item => item.total);

        // Gradientes oscuros mejorados
        const gradientColors = [
            { start: '#C62828', end: '#8E0000' },  // Rojo oscuro
            { start: '#1565C0', end: '#0D47A1' },  // Azul oscuro
            { start: '#2E7D32', end: '#1B5E20' },  // Verde oscuro
            { start: '#FF6D00', end: '#E65100' },  // Naranja oscuro
            { start: '#6A1B9A', end: '#4A148C' }   // Morado oscuro
        ];

        // Configuración del gráfico CORREGIDA
        const option = {
            backgroundColor: 'transparent',
            title: { show: false },
            tooltip: {
                trigger: 'item',
                formatter: '{b}: <b>{c}</b> ({d}%)',
                textStyle: { color: '#000' }
            },
            legend: {
                type: 'scroll',
                orient: 'horizontal',
                bottom: 0,
                textStyle: { color: '#000' },
                data: metodos
            },
            series: [{
                name: 'Ventas',
                type: 'pie',
                radius: ['35%', '65%'],
                center: ['50%', '45%'],
                avoidLabelOverlap: true,
                itemStyle: {
                    borderRadius: 5,
                    borderColor: '#fff',
                    borderWidth: 2,
                    color: function(params) {
                        return new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: gradientColors[params.dataIndex % gradientColors.length].start },
                            { offset: 1, color: gradientColors[params.dataIndex % gradientColors.length].end }
                        ]);
                    }
                },
                label: {
                    show: true,
                    color: '#000',
                    formatter: '{b}: {d}%',
                    fontWeight: 'bold'
                },
                labelLine: { show: true },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    },
                    label: { show: true, fontSize: '16' }
                },
                data: metodos.map((name, index) => ({
                    name: name,
                    value: totales[index]
                })),
                animationType: 'scale',
                animationEasing: 'elasticOut',
                animationDelay: function(idx) { return idx * 100; }
            }]
        };

        myChart.setOption(option);
        
        // FINALMENTE: Aplicamos el responsive CORRECTO
        makeChartResponsive(myChart);

        // Interacciones
        chartDom.addEventListener('mouseover', () => myChart.dispatchAction({ type: 'highlight', seriesIndex: 0 }));
        chartDom.addEventListener('mouseout', () => myChart.dispatchAction({ type: 'downplay', seriesIndex: 0 }));
    });
</script>
@endpush