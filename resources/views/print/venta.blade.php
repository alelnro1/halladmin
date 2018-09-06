<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ session('LOCAL_NOMBRE') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body onload="window.print();">
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> {{ strtoupper(session('LOCAL_ACTUAL')->nombre) }}
                    <small class="pull-right">{{ date("d/m/Y H:i", time()) }}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <div class="row invoice-info">
            <div class="col-xs-4 invoice-col">
                @if($cliente)
                    Cliente
                    <address>
                        <strong>{{ $cliente->nombre }} {{ $cliente->apellido }}</strong><br>
                        Teléfono: {{ $cliente->telefono }}<br>
                        Email: {{ $cliente->email }}
                    </address>
                @endif
            </div>
            <!-- /.col -->
            <div class="col-xs-4 invoice-col">
                @if ($articulo_a_cambiar)
                    <strong>Artículo a Cambiar</strong><br>
                    Descripción: {{ $articulo_a_cambiar->DatosArticulo->descripcion }}<br>
                    Precio: ${{ number_format($articulo_a_cambiar->DatosArticulo->precio, 2) }}<br>
                @endif
            </div>

            @include('ventas.col-totales')
        </div>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Cód</th>
                        <th>Cant</th>
                        <th>Artículo</th>
                        <th>Talle</th>
                        <th>Color</th>
                        <th>Género</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Dto</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($articulos) > 0)
                        @foreach($articulos as $articulo)
                            <tr>
                                <td>{{ $articulo->DatosArticulo->codigo }}</td>
                                <td>{{ $articulo->cantidad_a_vender }}</td>
                                <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                                <td>{{ $articulo->Talle->nombre }}</td>
                                <td>{{ $articulo->color }}</td>
                                <td>{{ $articulo->DatosArticulo->Genero->nombre }}</td>
                                <td class="text-right">
                                    ${{ number_format($articulo->DatosArticulo->precio, 2) }}
                                </td>
                                <td class="text-right">
                                    ${{ number_format($articulo->subtototal, 2) }}
                                </td>
                                <td class="text-right">
                                    @if ($articulo->descuento != 0)
                                        {{ number_format($articulo->descuento, 2) }}%
                                    @else
                                        No aplica
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>