<div class="form-group{{ $errors->has('tipo_contribuyente') ? ' has-error' : '' }}">
    <label for="tipo_contribuyente">Tipo Contribuyente</label>

    <select name="tipo_contribuyente" id="tipo_contribuyente" class="form-control">
        <option value="">Seleccione...</option>
        <option value="responsable_inscripto">Responsable Inscripto en IVA</option>
        <option value="monotributista">Monotributista</option>
        <option value="consumidor_final">Consumidor Final</option>
    </select>

    @if ($errors->has('tipo_contribuyente'))
        <span class="help-block">
                    <strong>{{ $errors->first('tipo_contribuyente') }}</strong>
                </span>
    @endif
</div>