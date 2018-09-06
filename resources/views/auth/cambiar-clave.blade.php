<form class="form-horizontal" role="form" method="POST" action="{{ url('/cambiar-clave-personal') }}">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
        <label for="email" class="col-md-4 control-label">Contraseña actual</label>

        <div class="col-md-6">
            <input id="old_password" type="password" class="form-control" name="old_password">

            @if ($errors->has('old_password'))
                <span class="help-block">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="col-md-4 control-label">Nueva contraseña</label>

        <div class="col-md-6">
            <input id="password" type="password" class="form-control" name="password">

            @if ($errors->has('password'))
                <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label for="password-confirm" class="col-md-4 control-label">Repetir constraseña</label>

        <div class="col-md-6">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-btn fa-refresh"></i> Cambiar
            </button>
        </div>
    </div>
</form>
