@extends('layouts.app')

@section('site-name', 'Editar articulo')

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Articulo</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('articulos.edit', ['articulo' => $articulo->id]) }}"
                  enctype="multipart/form-data">
                {!! csrf_field() !!}

                <fieldset>
                    <legend>Datos Comunes</legend>

                    <div class="help-block">
                        Estos datos afectan a todos los artículos con el mismo código. Si quiere editar
                        un dato <i>exclusivo</i> de este artículo, como color o talle, edite en la sección de abajo.
                    </div>

                    <!-- Código -->
                    <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Código</label>

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="codigo"
                                   value="{{ $articulo->getCodigo() }}">

                            @if ($errors->has('codigo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('codigo') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Descripcion -->
                    <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                        <label for="descripcion" class="control-label col-md-4">Descripción</label>

                        <div class="col-md-6">
                            <textarea name="descripcion"
                                      rows="3"
                                      class="form-control col-md-6">{{ $articulo->DatosArticulo->descripcion }}</textarea>

                            @if ($errors->has('descripcion'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class="form-group{{ $errors->has('categoria') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Categoría</label>

                        <div class="col-md-6">
                            @include('commons.select-categoria', array('page' => 'hay datos', 'categoria_seleccionada' => $articulo->DatosArticulo->categoria_id))

                            @if ($errors->has('categoria'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('categoria') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Precio</label>

                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text"
                                       id="{{ 'precio_' . str_random(8) }}"
                                       data-id="precio"
                                       name="precio"
                                       value="{{ $articulo->getPrecio() }}"
                                       class="form-control precio"
                                       autocomplete="off">
                            </div>

                            @if ($errors->has('color'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('color') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Género -->
                    <div class="form-group{{ $errors->has('genero') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Género</label>

                        <div class="col-md-6">
                            <select name="genero_id"
                                    class="form-control genero selectpicker required"
                                    id="{{ 'genero_' . str_random(8) }}"
                                    data-id="genero">
                                <option value="">Elija...</option>

                                @foreach ($generos as $genero)
                                    <option value="{{ $genero->id }}"
                                            @if($articulo->DatosArticulo->genero_id == $genero->id) selected @endif>
                                        {{ $genero->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('genero_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('genero_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                </fieldset>

                <fieldset>
                    <legend>Datos Exclusivos</legend>

                    <div class="help-block">
                        Estos datos afectarán <i>únicamente</i> al artículo en cuestión.
                    </div>

                    <!-- Color -->
                    <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Color</label>

                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   name="color"
                                   value="{{ $articulo->color }}">

                            @if ($errors->has('color'))
                                <span class="help-block">
                        <strong>{{ $errors->first('color') }}</strong>
                    </span>
                            @endif
                        </div>
                    </div>

                    <!-- Talle -->
                    <div class="form-group{{ $errors->has('talle_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Talle</label>

                        <div class="col-md-6">
                            <select name="talle_id"
                                    id="{{ 'talle_id_' . str_random(8) }}"
                                    data-id="talle_id"
                                    class="form-control talle_id selectpicker required"
                                    autocomplete="off"
                                    data-msg="Campo obligatorio"
                                    data-live-search="true"
                                    data-live-search-placeholder="Buscar">
                                <option value="" selected>Elija...</option>

                                @foreach ($talles as $talle)
                                    <option value="{{ $talle->id }}"
                                            @if($talle->id == $articulo['talle_id']) selected @endif>
                                        {{ $talle->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('talle_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('talle_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Cantidad</label>

                        <div class="col-md-6">
                            <input type="text"
                                   id="{{ 'cantidad_' . str_random(8) }}"
                                   data-id="cantidad"
                                   name="cantidad"
                                   class="form-control cantidad"
                                   value="{{ $articulo->cantidad }}"
                                   autocomplete="off">

                            @if ($errors->has('color'))
                                <span class="help-block">
                            <strong>{{ $errors->first('color') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                </fieldset>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-save"></i>&nbsp;Actualizar
                        </button>
                    </div>
                </div>
            </form>

            <div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>
        </div>
    </div>
@stop