<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generación de Factura</h4>
            </div>
            <div class="modal-body">
                <!-- Nombre -->
                <div class="form-group{{ $errors->has('tipo_comprobante') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Tipo de Comprobante</label>

                    <div class="col-md-6">
                        <select name="tipo_comprobante" id="" class="form-control">
                            @foreach($afip_tipos_comprobantes as $tipo_comprobante)
                                <option value="{{ $tipo_comprobante->Id }}">{{ $tipo_comprobante->Desc }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('tipo_comprobante'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tipo_comprobante') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <br>

                <div class="form-group{{ $errors->has('tipo_concepto') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Tipo de Conceptos</label>

                    <div class="col-md-6">
                        <select name="tipo_concepto" id="" class="form-control">
                            @foreach($afip_tipos_conceptos as $tipo_concepto)
                                <option value="{{ $tipo_concepto->Id }}">{{ $tipo_concepto->Desc }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('tipo_concepto'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tipo_concepto') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <br>

                <div class="form-group{{ $errors->has('tipo_comprobante') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Tipo de Documento</label>

                    <div class="col-md-6">
                        <select name="tipo_comprobante" id="" class="form-control">
                            @foreach($afip_tipos_documentos as $tipo_documento)
                                <option value="{{ $tipo_documento->Id }}">{{ $tipo_documento->Desc }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('tipo_comprobante'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tipo_comprobante') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <br>

                <div class="form-group{{ $errors->has('nro_documento') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Nro Documento</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nro_documento" value="{{ old('nro_documento') }}"
                               autocomplete="off"
                               id="nro_documento"
                               placeholder="Escriba el número de documento">

                        @if ($errors->has('nro_documento'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nro_documento') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-default" id="buscar-contribuyente"
                            data-buscar-contribuyente-url="{{ route('afip.get-info-contribuyente') }}">
                            Buscar
                        </button>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Nombre/Razón Social</label>

                    <div class="col-md-6">
                        <span id="nombre-razon-social"></span>
                    </div>
                </div>

                <div style="clear:both;"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

