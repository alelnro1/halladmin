<p>
    Usted ha solicitado un cambio de contraseña. Para realizarlo, haga
    <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">
        click aquí
    </a>

    o copie la siguiente dirección en su navegador:
    {{ $link }}
</p>
