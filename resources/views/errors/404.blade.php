@extends('layouts.app', [
    'LOCAL_NOMBRE' => session('LOCAL_NOMBRE'),
    'locales' => Auth::user()->Locales
])

@section('page-header', 'Página no encontrada')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Página no encontrada</a></li>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content" style="padding: 10px;">
            <h3><i class="fa fa-warning text-yellow"></i> Error! Página no encontrada.</h3>

            <p>
                La página que está tratando de acceder no se encuentra en nuestra nube.
                Si lo desea, trate accediendo desde el menú principal.
            </p>

        </div>
        <!-- /.error-content -->
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('categorias') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop
