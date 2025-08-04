<!-- Left Sidebar -->
<!-- Inicio del aside (barra lateral izquierda) con clase "sidebar" y ID "leftsidebar" -->
<aside id="leftsidebar" class="sidebar">
    
    <!-- User Info -->
    <!-- Sección de información del usuario -->
    <div class="user-info">
        <!-- Contenedor de la imagen del usuario -->
        <div class="image">
            <!-- Imagen del usuario con fuente dinámica usando Laravel's url() -->
            <img src="{{ url('images/user.png') }}" width="60" height="60" alt="User" />
        </div>
        
        <!-- Contenedor de la información del usuario -->
        <div class="info-container">
            <!-- Nombre del usuario obtenido del objeto Auth de Laravel -->
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div>
            <!-- Email del usuario obtenido del objeto Auth de Laravel -->
            <div class="email">{{ Auth::user()->email  }}</div>
            
            <!-- Dropdown menu para acciones del usuario -->
            <div class="btn-group user-helper-dropdown">
                <!-- Ícono que activa el dropdown -->
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <!-- Menú desplegable con opciones -->
                <ul class="dropdown-menu pull-right">
                    <!-- Opción para cambiar contraseña -->
                    <li><a href="{{ url('password-change') }}"><i class="material-icons">person</i>Perfil</a></li>
                    <!-- Opción para cerrar sesión -->
                    <li><a href="{{ url('logout') }}"><i class="material-icons">input</i>Desconectar</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->

    <!--=======================================================================================================-->

    <!-- Menu -->
    <!-- Sección del menú de navegación principal -->
    <div class="menu">
        <ul class="list">
            <!-- Encabezado del menú -->
            <li class="header">NAVEGACIÓN PRINCIPAL</li>
            
            <!-- Elemento del menú Dashboard -->
            <li @if(Route::currentRouteName()=='' ) class="active" @endif>
                <a href="{{ url('/') }}">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>
    <!--=======================================================================================================-->
            <!-- Bloque PHP para obtener el menú lateral de la sesión -->
            @php
            $side_menu = Session::get('side_menu');
            @endphp

            <!-- Bucle para generar los elementos del menú -->
            @foreach($side_menu[0] as $value)

                <!-- Verifica si el elemento del menú tiene submenús -->
                @if(count($value['sub_menu'])>0)
                <li class="parent">
                    <!-- Elemento del menú principal con toggle para submenú -->
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">{{ $value['icon'] }}</i>
                        <span>{{ $value['name'] }}</span>
                    </a>
                    <!-- Submenú -->
                    <ul class="ml-menu">
                        <!-- Bucle para generar los elementos del submenú -->
                        @foreach($value['sub_menu'] as $sub)
                        <li @if(Route::currentRouteName()==$sub->menu_url) class="active" @endif>
                            <a href="{{ $sub->menu_url ? route($sub->menu_url) : '' }}">
                                <span>{{ $sub->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                @else
                <!-- Elemento del menú sin submenús -->
                <li @if(Route::currentRouteName()==$value['url']) class="active" @endif>
                    <a href="{{ $value['url'] ? route($value['url']) : '' }}">
                        <i class="material-icons">{{ $value['icon'] }}</i>
                        <span>{{ $value['name'] }}</span>
                    </a>
                </li>

                @endif

            @endforeach()
    <!--=======================================================================================================-->
            <!-- Menú manual de Estadísticas con submenús -->
            <li class="parent">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">bar_chart</i> <!-- Icono para estadísticas -->
                    <span>Estadísticas</span>
                </a>
                <ul class="ml-menu">
                    <li @if(Route::currentRouteName() == 'stats.monthly-sales') class="active" @endif>
                        <a href="{{ route('stats.monthly-sales') }}">
                            <i class="material-icons">show_chart</i>
                            <span>Ventas Mensuales</span>
                        </a>
                    </li>
                    <li @if(Route::currentRouteName() == 'stats.product-distribution') class="active" @endif>
                        <a href="{{ route('stats.product-distribution') }}">
                            <i class="material-icons">pie_chart</i>
                            <span>Distribución de Productos</span>
                        </a>
                    </li>
                    <li @if(Route::currentRouteName() == 'stats.sales-by-category') class="active" @endif>
                        <a href="{{ route('stats.sales-by-category') }}">
                            <i class="material-icons">stacked_bar_chart</i>
                            <span>Ventas por Categoría</span>
                        </a>
                    </li>
                    <li @if(Route::currentRouteName() == 'stats.mapa-antofagasta') class="active" @endif>
                        <a href="{{ route('mapa.clientes') }}" >
                            <i class="material-icons">map</i>
                            <span>Mapa de Clientes</span>
                        </a>
                    </li>
                    
                    <li @if(Request::is('estadisticas/top-productos-mas-vendidos')) class="active" @endif>
                        <a href="{{ url('estadisticas/top-productos-mas-vendidos') }}">
                            <i class="material-icons">star</i>
                            <span>Top Productos Más Vendidos</span>
                        </a>
                    </li>
                    <li @if(Request::is('estadisticas/ventas-por-metodo-pago')) class="active" @endif>
                        <a href="{{ url('estadisticas/ventas-por-metodo-pago') }}">
                            <i class="material-icons">credit_card</i>
                            <span>Ventas por Método de Pago</span>
                        </a>
                    </li>
                    <li @if(Request::is('estadisticas/stock-por-categoria')) class="active" @endif>
                        <a href="{{ url('estadisticas/stock-por-categoria') }}">
                            <i class="material-icons">category</i>
                            <span>Stock por Categoría</span>
                        </a>
                    </li>
                    
                    <li @if(Request::is('stats/low-stock')) class="active" @endif>
                        <a href="{{ route('stats.low-stock') }}">
                            <i class="material-icons">warning</i>
                            <span>Reporte Stock Bajo</span>
                            @php
                                $lowStockCount = \DB::table('stocks')
                                    ->where('current_quantity', '<=', 20)
                                    ->count();
                            @endphp
                            @if($lowStockCount > 0)
                                <span class="badge badge-pill badge-danger float-right">{{ $lowStockCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>

        </ul>


    </div>
    <!-- #Menu -->

</aside>
<!-- #END# Left Sidebar -->