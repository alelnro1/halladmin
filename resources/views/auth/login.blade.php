@extends('layouts.app-login')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>Hall</b >Admin</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">

            <p class="login-box-msg">Inicie sesión</p>

            @if (Session::has('password_changed'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    Ha cambiado su contraseña. Ingrese con ella nuevamente
                    para continuar operando
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"  required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <input type="checkbox"> Recordarme
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-5">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            <i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;
                            Entrar
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a class="btn btn-link" href="{{ url('password/reset') }}">Recuperar contraseña</a>
        </div>
    </div>
@stop