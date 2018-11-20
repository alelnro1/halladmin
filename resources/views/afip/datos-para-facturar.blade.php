<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generación de Factura</h4>
            </div>
            <div class="modal-body">
                <div class="callout callout-warning">
                    Una vez realizada la factura, la venta se dará por finalizada y no podrá modificarse.
                </div>
                <!-- Nombre -->
                <div class="form-group{{ $errors->has('tipo_comprobante') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Tipo de Comprobante</label>

                    <div class="col-md-6">
                        <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
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

                {{--<div class="form-group{{ $errors->has('tipo_concepto') ? ' has-error' : '' }}">
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

                <br>--}}

                {{--<div class="form-group{{ $errors->has('tipo_tributo') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Tipo de Tributos</label>

                    <div class="col-md-6">
                        <select name="tipo_tributo" id="" class="form-control">
                            @foreach($afip_tipos_tributos as $tipo_tributo)
                                <option value="{{ $tipo_tributo->Id }}">{{ $tipo_tributo->Desc }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('tipo_tributo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tipo_tributo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <br>--}}

                {{--<div class="form-group{{ $errors->has('tipo_tributo') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Opciones</label>

                    <div class="col-md-6">
                        <select name="tipo_tributo" id="" class="form-control">
                            @foreach($afip_opciones_disponibles as $afip_opcion)
                                <option value="{{ $afip_opcion->Id }}">{{ $afip_opcion->Desc }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('tipo_tributo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tipo_tributo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <br>--}}

                {{--<div class="form-group{{ $errors->has('tipo_tributo') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Tipos Alicuotas</label>

                    <div class="col-md-6">
                        <select name="tipo_tributo" id="" class="form-control">
                            @foreach($afip_tipos_alicuotas as $tipo_alicuota)
                                <option value="{{ $tipo_alicuota->Id }}">{{ $tipo_alicuota->Desc }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('tipo_tributo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tipo_tributo') }}</strong>
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
                </div>--}}

                <br>

                <div class="form-group{{ $errors->has('cuit') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">CUIT</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="cuit" value="{{ old('cuit') }}"
                               autocomplete="off"
                               id="cuit"
                               data-mask="99-99999999-9"
                               placeholder="Escriba el CUIT">

                        @if ($errors->has('cuit'))
                            <span class="help-block">
                                <strong>{{ $errors->first('cuit') }}</strong>
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

                <br>

                <div id="cargando-datos-contribuyente" style="display: none;">
                    <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
                </div>

                <br>

                <div id="datos-contribuyente" style="border: 1px dashed gray; display: none;">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Tipo Contribuyente</label>

                            <div class="col-md-6">
                                <span id="tipo-contribuyente"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nombre/Razón Social</label>

                            <div class="col-md-6">
                                <span id="nombre-razon-social"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Domicilio Fiscal</label>

                            <div class="col-md-6">
                                <span id="domicilio-fiscal"></span>
                            </div>
                        </div>
                    </div>

                    <div style="clear: both;"></div>
                </div>

                <div style="clear:both;"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-facturar-url="{{ route('afip.generar-factura') }}"
                        id="facturar">
                    Facturar
                </button>
            </div>
        </div>

    </div>
</div>

