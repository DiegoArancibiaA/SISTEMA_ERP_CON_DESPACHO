<!DOCTYPE html>
<html>
<head>
    <title>@yield('title','AlphaERP')</title>

    @include('include.header')
    <style>
        .select2 {
            width: 100% !important;
        }

        .centered-wrapper {
            max-width: 2900px;
            margin: 0 auto;
            padding: 2rem;
        }

        body {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body class="theme-teal" onload="loaded()">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Por favor espera...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->

    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ url('/') }}" title="">
                    <img class="img-fluid" src="{{ url('images/logo.png') }}" alt=" logo" style="height: 40px;">
                </a>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->

    <!-- Contenido centrado -->
    <!--<section class="content_frame">
        <div style="max-width: 2500px; height: 1000px; margin: 10px auto 2rem; width: 100%;">
            <div id="AlphaERP" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                <div class="block-header" style="text-align: center;">
                    <h2>@yield('page-title')</h2>
                </div>


                <div style="text-align: center; max-width: 2500px; height: 1000px; margin: 50px auto 2rem; width: 100%;">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>-->
    <section class="content_frame">
            <div id="AlphaERP" class="centered-wrapper" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                <div class="block-header">
                    <h2>@yield('page-title')</h2>
                </div>

                <!-- Aquí va el contenido específico  max-width: 2500px; -->
                 <div style="text-align: center; height: 90%; margin: 30px auto 2rem; width: 90%;">
                @yield('content')
                </div>
            </div>
        </div>
    </section>

    @include('include.footer')

    <script type="text/javascript">
        var base_url = "{{ url('/').'/' }}";

        function loaded() {
            var segment3 = '{{ Request::segment(1) }}';
            var current_url = base_url + segment3;
            $('a[href="' + current_url + '"]').parents('.ml-menu').siblings('a').addClass('toggled');
            $('a[href="' + current_url + '"]').parents('.ml-menu').css('display', 'block');
        }
    </script>

    @stack('script')
</body>
</html>
