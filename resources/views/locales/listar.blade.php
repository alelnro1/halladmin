@extends('layouts.app')

@section('page-header', 'Locales')
@section('page-description', 'Listado')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Locales</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools">
                <a href="{{ route('locales.create') }}" class="btn btn-block btn-success btn-sm">
                    <i class="fa fa-fw fa-plus" aria-hidden="true"></i>
                    Nuevo Local
                </a>
            </div>
        </div>

        <div class="box-body">
            @if (Session::has('local_eliminado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('local_eliminado') }}
                </div>
            @endif

            @if (Session::has('local_creado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('local_creado') }}
                </div>
            @endif

            @if (Session::has('local_actualizado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('local_actualizado') }}
                </div>
            @endif

            @if (Session::has('user_no_tiene_locales'))
                <div class="callout callout-danger">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    Para poder ingresar mercadería, realizar ventas o cambios, administrar usuarios y ofertas, debe tener al menos un local
                    sobre el cual operar.
                    Por favor cree uno haciendo <a href="{{ url('locales/create') }}">click aquí</a>
                </div>
            @endif

            @if (count($locales) > 0)
                <table class="table table-bordered table-hover table-striped responsive" id="locales" width="100%">
                    <!-- Table Headings -->
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($locales as $local)
                        <tr>
                            <td>{{ $local->nombre }}</td>
                            <td>{{ $local->email }}</td>
                            <td>{{ $local->telefono }}</td>

                            <td>
                                <a href="{{ route('locales.view', ['id' => $local['id']])   }}" class="btn btn-default btn-xs">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    Ver
                                </a>

                                <a href="{{ route('locales.edit', ['id' => $local['id']]) }}" class="btn btn-default btn-xs">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                    Editar
                                </a>

                                <a href="{{ url('locales/' . $local['id']) }}"
                                   class="btn btn-danger btn-xs"
                                   data-method="delete"
                                   data-token="{{ csrf_token() }}"
                                   data-confirm="Esta seguro que desea eliminar a local con nombre {{ $local->nombre }}?">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay locales
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/locales/listar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop