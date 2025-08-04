@extends('include.frame_graph')

@section('title', 'Reporte de Stock Bajo')
@section('content')
    {{-- REPORTE DE STOCK BAJO --}}
    <div class="container-graf">
        <div class="chart-responsive-container">
            <div class="bg-white rounded shadow-sm chart-card">

                <div class="chart-header">
                    <div class="chart-header-bg"></div>
                    <div class="chart-header-content">
                        <i class="fas fa-boxes chart-icon"></i>
                        <span class="chart-title">Reporte de Stock Bajo</span>
                    </div>
                </div>

                <div class="chart-body">
                    <p class="chart-description">Productos con stock menor a 20 unidades</p>
                    <div id="lowStockChart" class="echarts-responsive"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETALLE DE PRODUCTOS --}}
    <div class="container-graf mt-4">
        <div class="chart-responsive-container">
            <div class="bg-white rounded shadow-sm chart-card">

                <div class="chart-header">
                    <div class="chart-header-bg"></div>
                    <div class="chart-header-content">
                        <i class="fas fa-list-ul chart-icon"></i>
                        <span class="chart-title">Detalle de Productos</span>
                    </div>
                </div>

                <div class="chart-body">
                    <div class="table-responsive">
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th class="text-center">Stock Actual</th>
                                    <th class="text-center">Nivel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->category_name }}</td>
                                    <td class="text-center">{{ $product->current_quantity }}</td>
                                    <td class="text-center">
                                        @if($product->current_quantity <= 4)
                                            <span class="badge critical-badge">Crítico</span>
                                        @elseif($product->current_quantity <= 10)
                                            <span class="badge warning-badge">Advertencia</span>
                                        @else
                                            <span class="badge low-badge">Bajo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

    <style>
        /* --- Estilos base del contenedor --- */
        .container-graf {
            width: 100%;
            margin-bottom: 2rem;
        }
        
        .chart-responsive-container {
            position: relative;
            width: 100%;
        }
        
        .chart-card {
            border: 1px solid #e0e0e0;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        /* --- Estilos del encabezado --- */
        .chart-header {
            position: relative;
            height: 50px;
            margin: 0;
        }
        
        .chart-header-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(to left, #e85041, #a02b22);
            z-index: 1;
        }
        
        .chart-header-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
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
            margin: 0;
            line-height: 1;
            width: 100%;
            text-align: center;
        }
        
        /* --- Estilos del cuerpo --- */
        .chart-body {
            padding: 1.5rem;
            color: #333;
        }
        
        .chart-description {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        
        .echarts-responsive {
            width: 100%;
            min-height: 500px;
        }
        
        /* --- Estilos de la tabla --- */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }
        
        .product-table thead {
            background: linear-gradient(to right, #e85041, #a02b22);
            color: white;
        }
        
        .product-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .product-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        
        .product-table tr:hover {
            background-color: #f9f9f9;
        }
        
        /* --- Estilos de los badges --- */
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.85em;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: white;
        }
        
        .critical-badge {
            background: linear-gradient(135deg, #ff4d4d, #cc0000);
        }
        
        .warning-badge {
            background: linear-gradient(135deg, #ffcc00, #ff9900);
        }
        
        .low-badge {
            background: linear-gradient(135deg, #4da6ff, #0066cc);
        }
        
        /* --- Estilos responsivos --- */
        @media (max-width: 768px) {
            .chart-title {
                font-size: 1.5rem;
            }
            
            .product-table th, 
            .product-table td {
                padding: 8px 10px;
                font-size: 0.9rem;
            }
            
            .echarts-responsive {
                min-height: 400px;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chart = echarts.init(document.getElementById('lowStockChart'));
        
        fetch('/api/low-stock-products')
            .then(response => response.json())
            .then(data => {
                // Procesamos los datos para el gráfico
                const products = data.products;
                
                // Ordenamos por cantidad (ascendente)
                products.sort((a, b) => a.current_quantity - b.current_quantity);
                
                // Preparamos datos para el gráfico
                const productNames = products.map(p => p.product_name);
                const stockValues = products.map(p => p.current_quantity);
                const categories = products.map(p => p.category_name);
                
                // Configuración del gráfico con gradientes
                const option = {
                    title: {
                        text: 'Productos con Stock Bajo',
                        subtext: `Total de ${data.count} productos con stock ≤ 20 unidades`,
                        left: 'center',
                        textStyle: {
                            fontSize: 18,
                            fontWeight: 'bold',
                            color: '#333'
                        },
                        subtextStyle: {
                            color: '#666'
                        }
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        },
                        formatter: function(params) {
                            const data = params[0];
                            return `
                                <strong>${data.name}</strong><br/>
                                Categoría: ${categories[data.dataIndex]}<br/>
                                Stock: <b>${data.value} unidades</b><br/>
                                Nivel: ${data.value <= 4 ? 'Crítico' : data.value <= 10 ? 'Advertencia' : 'Bajo'}
                            `;
                        }
                    },
                    legend: {
                        data: ['Crítico (0-4)', 'Advertencia (5-10)', 'Bajo (11-20)'],
                        bottom: 10,
                        textStyle: {
                            color: '#333'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '15%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'value',
                        axisLabel: {
                            formatter: '{value} unidades',
                            color: '#666'
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#e0e0e0'
                            }
                        },
                        splitLine: {
                            lineStyle: {
                                type: 'dashed',
                                color: '#f0f0f0'
                            }
                        }
                    },
                    yAxis: {
                        type: 'category',
                        data: productNames,
                        axisLabel: {
                            interval: 0,
                            rotate: 30,
                            color: '#333',
                            formatter: function(value) {
                                return value.length > 20 ? value.substring(0, 20) + '...' : value;
                            }
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#e0e0e0'
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Stock Actual',
                            type: 'bar',
                            data: stockValues.map((value, index) => ({
                                value: value,
                                itemStyle: {
                                    color: {
                                        type: 'linear',
                                        x: 0,
                                        y: 0,
                                        x2: 1,
                                        y2: 0,
                                        colorStops: [{
                                            offset: 0,
                                            color: value <= 4 ? '#ff7d7d' : value <= 10 ? '#ffb866' : '#66a3ff'
                                        }, {
                                            offset: 1,
                                            color: value <= 4 ? '#cc0000' : value <= 10 ? '#ff9900' : '#0066cc'
                                        }]
                                    }
                                }
                            })),
                            label: {
                                show: true,
                                position: 'right',
                                formatter: '{c} unidades',
                                color: '#333'
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.3)'
                                }
                            },
                            barWidth: '60%',
                            barCategoryGap: '20%'
                        }
                    ]
                };
                
                chart.setOption(option);
                
                // Hacer responsive
                window.addEventListener('resize', function() {
                    chart.resize();
                });
            })
            .catch(error => {
                console.error('Error al cargar los datos:', error);
                document.getElementById('lowStockChart').innerHTML = 
                    '<div class="text-center p-4 text-danger">Error al cargar los datos del gráfico</div>';
            });
    });
    </script>
@endsection