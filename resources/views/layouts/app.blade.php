<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Hall - DEV</title>

    <!-- Bootstrap 3.3.6 -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="{{ asset('css/skins/skin-blue.min.css') }}">

    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/loading-cover.css') }}">


@yield('styles')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini @if(Request::path() == "mercaderia/ingreso" || Request::path() == "ventas/nueva-venta") sidebar-collapse @endif">
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Hall</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Hall</b> Admin</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <li>
                        @if (Auth::user()->abrioCaja())
                            <a href="{{ url('caja/cerrar') }}">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                                Cerrar Caja
                            </a>
                        @endif
                    </li>
                    <li class="dropdown">
                        @if (Auth::user()->tieneModuloHabilitado('nueva-venta') || Auth::user()->esSuperAdmin() || Auth::user()->tieneModuloHabilitado('nuevo-cambio'))
                            <a href="{{ url('ventas/nueva-venta') }}">
                                <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                Vender
                            </a>
                        @endif
                    </li>
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            @if(Auth::user()->archivo == "")
                                <img src="{{ asset('img/avatar.png') }}" class="user-image">
                            @else
                                <img src="{{ asset( Auth::user()->archivo) }}" class="user-image">
                        @endif
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                @if(Auth::user()->archivo == "")
                                    <img src="{{ asset('img/avatar.png') }}" class="img-circle">
                                @else
                                    <img src="{{ asset( Auth::user()->archivo) }}" class="img-circle">
                                @endif

                                <p>
                                    {{ ucfirst(Auth::user()->nombre) }} {{ ucfirst(Auth::user()->apellido) }}
                                    <small>Miembro desde
                                        el {{ date('d-m-Y', strtotime(Auth::user()->created_at)) }} </small>
                                </p>
                            </li>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ url('/perfil') }}" class="btn btn-default btn-flat">
                                        <i class="fa fa-user" aria-hidden="true"></i>&nbsp;Mi Perfil
                                    </a>
                                </div>
                                <div class="pull-right">
                                    <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Salir
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    @if(session('LOCAL_ACTUAL') != null && (Auth::user()->esAdmin() || Auth::user()->esSuperAdmin()))
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            @if(Auth::user()->esSuperAdmin())
                @include('menus.super-admin')
            @elseif (Auth::user()->esAdmin())
                @include('menus.admin')
            @else
                @include('menus.usuario')
            @endif
        </section>
        <!-- /.sidebar -->
    </aside>

    <div style="width:100%;height: 50px;" class="hidden-xs">
        &nbsp;
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 800px">
    @yield('alerta-caja-sin-abrir')

    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ session('LOCAL_NOMBRE') }}
                <small>@yield('page-description')</small>
            </h1>
            <ol class="breadcrumb">
                @yield('niveles')
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="spinner-container">
                        <div class="spinner">
                            <div class="double-bounce1"></div>
                            <div class="double-bounce2"></div>
                        </div>
                    </div>

                    <div id="prev-load" class="hidden">
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- Your Page Content Here -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        @include('commons.footer')
    </footer>

    <!-- Control Sidebar -->
    @if(session('LOCAL_ACTUAL') != null && (Auth::user()->esAdmin() || Auth::user()->esSuperAdmin()))
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active">
                    <a href="#control-sidebar-home-tab" data-toggle="tab">
                        <i class="fa fa-home"></i>&nbsp;Ud está administrando<br>
                        <strong>{{ session('LOCAL_NOMBRE') }}</strong>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Cambiar de Local</h3>
                    <ul class="control-sidebar-menu">
                        @foreach(session('locales') as $local)
                            <li>
                                <a href="javascript:;" class="cambiar-de-local" data-local-id="{{ $local->id }}">
                                    <img style="height: 35px;" class="menu-icon img-circle"
                                         src="/{{ $local->archivo }}">

                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">{{ $local->nombre }}</h4>

                                        <p>{{ $local->domicilio }}</p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
@endif
<!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<!-- Bootstrap 3.3.6 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/app.min.js') }}"></script>


@yield('javascript')

<script type="text/javascript">
    $(window).load(function () {
        $('#prev-load').hide().removeClass('hidden').fadeIn(500);

        $(".spinner-container").fadeOut(200);

        $('.cambiar-de-local').on('click', function () {
            var local_id = $(this).data('local-id');

            $.ajax({
                url: '/cambiar-de-local/' + local_id,
                dataType: 'json',
                success: function (data) {
                    if (data.valid == true)
                        window.location.href = '/';
                }
            });
        });

        // Click en la imagen de perfil redirije al perfil
        $('.img-circle').on('click', function () {
            window.location.href = 'perfil';
        });
    });
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>