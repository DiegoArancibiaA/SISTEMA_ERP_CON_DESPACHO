<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src="{{ url('images/user.png') }}" width="60" height="60" alt="User" />
        </div>
        
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div>
            <div class="email">{{ Auth::user()->email }}</div>
            
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="{{ url('password-change') }}"><i class="material-icons">person</i>Perfil</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons">input</i>Desconectar
                    </a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->

    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <!-- Dashboard -->
            <li @if(request()->is('/')) class="active" @endif>
                <a href="{{ url('/') }}">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Gestión de Ventas -->
            <li class="parent @if(request()->is('invoice*') || request()->is('customer*') || request()->is('payment*')) active @endif">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">point_of_sale</i>
                    <span>Ventas</span>
                </a>
                <ul class="ml-menu">
                    <li @if(request()->is('invoice*')) class="active" @endif>
                        <a href="{{ url('invoice') }}">
                            <i class="material-icons">receipt</i>
                            <span>Facturas</span>
                        </a>
                    </li>
                    <li @if(request()->is('customer*')) class="active" @endif>
                        <a href="{{ url('customer') }}">
                            <i class="material-icons">people</i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    <li @if(request()->is('payment*')) class="active" @endif>
                        <a href="{{ url('payment') }}">
                            <i class="material-icons">payment</i>
                            <span>Pagos</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Gestión de Inventario -->
            <li class="parent @if(request()->is('product*') || request()->is('category*') || request()->is('stock*') || request()->is('dispatches*')) active @endif">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">inventory</i>
                    <span>Inventario</span>
                </a>
                <ul class="ml-menu">
                    <li @if(request()->is('product*')) class="active" @endif>
                        <a href="{{ url('product') }}">
                            <i class="material-icons">shopping_cart</i>
                            <span>Productos</span>
                        </a>
                    </li>
                    <li @if(request()->is('category*')) class="active" @endif>
                        <a href="{{ url('category') }}">
                            <i class="material-icons">category</i>
                            <span>Categorías</span>
                        </a>
                    </li>
                    <li @if(request()->is('stock*')) class="active" @endif>
                        <a href="{{ url('stock') }}">
                            <i class="material-icons">compare_arrows</i>
                            <span>Movimientos</span>
                        </a>
                    </li>
                    <!-- Nuevo módulo de Despachos -->
                    <!-- Módulo de Despachos -->
                    <li class="parent @if(request()->is('dispatches*')) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <i class="material-icons">local_shipping</i>
                            <span>Despachos</span>
                        </a>
                        <ul class="ml-menu" style="display: @if(request()->is('dispatches*')) block @else none @endif;">
                            
                            {{-- Página para registrar salida y retorno --}}
                            <li class="@if(request()->is('dispatches/create')) active @endif">
                                <a href="{{ route('dispatches.create') }}" class="waves-effect waves-block">
                                    <i class="material-icons">add_circle</i>
                                    <span>Registrar Salida / Retorno</span>
                                </a>
                            </li>

                            {{-- Página para ver productos faltantes --}}
                            <li class="@if(request()->is('dispatches/missing')) active @endif">
                                <a href="{{ route('dispatches.missing') }}" class="waves-effect waves-block">
                                    <i class="material-icons">error_outline</i>
                                    <span>Productos Faltantes</span>
                                </a>
                            </li>

                            <li class="@if(request()->is('dispatches/history*')) active @endif">
                                <a href="{{ route('dispatches.history') }}" class="waves-effect waves-block">
                                    <i class="material-icons">history</i>
                                    <span>Historial de Despachos</span>
                                </a>
                            </li>


                        </ul>
                    </li>
                    <!-- Fin módulo de Despachos -->


                    <li @if(request()->is('stats/low-stock')) class="active" @endif>
                        <a href="{{ url('stats/low-stock') }}">
                            <i class="material-icons">warning</i>
                            <span>Stock Bajo</span>
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

           
            <!-- Gestión de Compras -->
            <li @if(request()->is('supplier*')) class="active" @endif>
                <a href="{{ url('supplier') }}">
                    <i class="material-icons">local_shipping</i>
                    <span>Proveedores</span>
                </a>
            </li>

            <!-- Reportes y Gráficos -->
            <li class="parent @if(request()->is('estadisticas*') || request()->is('stats*') || request()->is('report*') || request()->is('mapa*')) active @endif">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">assessment</i>
                    <span>Reportes</span>
                </a>
                <ul class="ml-menu">
                    <!-- Gráficos Estadísticos -->
                    <li class="parent @if(request()->is('estadisticas*')) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">bar_chart</i>
                            <span>Gráficos</span>
                        </a>
                        <ul class="ml-menu">
                            <li @if(request()->is('estadisticas/ventas-mensuales')) class="active" @endif>
                                <a href="{{ url('estadisticas/ventas-mensuales') }}">
                                    <i class="material-icons">show_chart</i>
                                    <span>Ventas Mensuales</span>
                                </a>
                            </li>
                            <li @if(request()->is('estadisticas/distribucion-productos')) class="active" @endif>
                                <a href="{{ url('estadisticas/distribucion-productos') }}">
                                    <i class="material-icons">pie_chart</i>
                                    <span>Distribución Productos</span>
                                </a>
                            </li>
                            <li @if(request()->is('estadisticas/ventas-categoria')) class="active" @endif>
                                <a href="{{ url('estadisticas/ventas-categoria') }}">
                                    <i class="material-icons">stacked_bar_chart</i>
                                    <span>Ventas por Categoría</span>
                                </a>
                            </li>
                            <li @if(request()->is('estadisticas/top-productos-mas-vendidos')) class="active" @endif>
                                <a href="{{ url('estadisticas/top-productos-mas-vendidos') }}">
                                    <i class="material-icons">star</i>
                                    <span>Top Productos</span>
                                </a>
                            </li>
                            <li @if(request()->is('estadisticas/ventas-por-metodo-pago')) class="active" @endif>
                                <a href="{{ url('estadisticas/ventas-por-metodo-pago') }}">
                                    <i class="material-icons">credit_card</i>
                                    <span>Métodos de Pago</span>
                                </a>
                            </li>
                            <li @if(request()->is('estadisticas/stock-por-categoria')) class="active" @endif>
                                <a href="{{ url('estadisticas/stock-por-categoria') }}">
                                    <i class="material-icons">category</i>
                                    <span>Stock por Categoría</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Mapa -->
                    <li @if(request()->is('mapa-clientes')) class="active" @endif>
                        <a href="{{ url('mapa-clientes') }}">
                            <i class="material-icons">map</i>
                            <span>Mapa de Clientes</span>
                        </a>
                    </li>
                    
                    <!-- Reportes PDF -->
                    <li @if(request()->is('report')) class="active" @endif>
                        <a href="{{ url('report') }}">
                            <i class="material-icons">picture_as_pdf</i>
                            <span>Reportes PDF</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Administración -->
            <li class="parent @if(request()->is('user*') || request()->is('role*') || request()->is('company*') || request()->is('password-change')) active @endif">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">settings</i>
                    <span>Administración</span>
                </a>
                <ul class="ml-menu">
                    <li @if(request()->is('user*')) class="active" @endif>
                        <a href="{{ url('user') }}">
                            <i class="material-icons">people</i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <li @if(request()->is('role*')) class="active" @endif>
                        <a href="{{ url('role') }}">
                            <i class="material-icons">lock</i>
                            <span>Roles</span>
                        </a>
                    </li>
                    <li @if(request()->is('comapany-setting')) class="active" @endif>
                        <a href="{{ url('comapany-setting') }}">
                            <i class="material-icons">business</i>
                            <span>Configuración</span>
                        </a>
                    </li>
                    <li @if(request()->is('password-change')) class="active" @endif>
                        <a href="{{ url('password-change') }}">
                            <i class="material-icons">security</i>
                            <span>Cambiar Contraseña</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- #Menu -->
</aside>
<!-- #END# Left Sidebar -->