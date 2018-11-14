@extends('layouts.app')

@if (Auth::user()->tieneAlgunLocal())
    {{-- Esta sección deberá mostrar una alerta si no se abrió la caja si hay al menos 1 local --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection
@endif


@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Contabilidad</h3>

            <div class="box-tools pull-right">

            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-4"></div>
                    <div class="col-xs-4"></div>
                    <div class="col-xs-4"></div>
                </div>
            </div>
        </div>
    </div>
@endsection