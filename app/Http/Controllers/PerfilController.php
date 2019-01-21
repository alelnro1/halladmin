<?php

namespace App\Http\Controllers;

use App\Http\Requests\Perfil\EditarPerfilRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mostrar el perfil del usuario actual
     */
    public function verPerfil()
    {
        $usuario = Auth::user();
        return view('auth.perfil', array('usuario' => $usuario));
    }

    /**
     * Editar el perfil actual
     */
    public function editarPerfil()
    {
        $usuario = Auth::user();
        return view('auth.editar-perfil', array('usuario' => $usuario));
    }

    /**
     * Actualizar el perfil con los campos ingresados (y foto si la hay)
     */
    public function actualizarPerfil(EditarPerfilRequest $request)
    {
        /*$validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'apellido' => 'required|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'max:60',
            'archivo'  => 'max:5000|mimes:jpg,jpeg,png',
        ]);*/

        /*$validator->after(function($validator) use ($request) {
            // Obtengo todos los usuarios con mail igual al ingresado
            $usuarios_con_mail_igual  = User::where('email', $request->email)->select(['id'])->get();

            // Si algun id es distinto al del usuario actual => el email ya existe
            foreach ($usuarios_con_mail_igual as $usuario_con_mail_igual) {
                if ($usuario_con_mail_igual->id != Auth::user()->id) {
                    $validator->errors()->add('email', 'El email elegido pertenece a otro usuario');
                }
            }
        });

        if ($validator->fails()) {
            return redirect('perfil/edit')
                ->withErrors($validator)
                ->withInput();
        }*/

        $usuario = Auth::user();

        // Actualizar el usuario
        $usuario->nombre   = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email    = $request->email;
        $usuario->telefono = $request->telefono;

        $usuario->save();

        // Si se trató de guardar una foto para el local, validarla y subirla
        //$validator = $this->subirYGuardarArchivoSiHay($request, $validator, $usuario);
        $this->subirYGuardarArchivoSiHay($request, $usuario);

        /*if ($validator) {
            if ($validator->fails())
                return redirect('perfil/edit')->withErrors($validator)->withInput();
        }*/

        return redirect('perfil')->with('perfil_actualizado', 'Su perfil se actualizó');
    }

    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     * @param $request
     * @param $validator
     * @param $local
     * @return mixed
     */
    /*private function subirYGuardarArchivoSiHay($request, $validator, $local)
    {
        if (isset($request->archivo) && count($request->archivo) > 0) {
            $archivo = $this->subirArchivo($request);

            if ($archivo['url']) {
                $local->archivo = $archivo['url'];
                $local->save();
            } else {
                $validator->errors()->add('archivo', $archivo['err']);

                return $validator;
            }
        }
    }*/

    /**
     * Mostrar el formulario para cambiar la contraseña
     */
    public function cambiarClaveForm()
    {
        return view('auth.cambiar-clave');
    }

    /**
     * Actualizar la contraseña
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function actualizarClave(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'old_password'  => 'required',
            'password'      => 'required|min:6|confirmed',
        ]);*/

        // Busco el email y contraseña del usuario actual
        $usuario = User::where('id', '=', Auth::user()->id)->first(['id', 'email', 'password']);

        /*// Valido que la contraseña vieja sea la actual
        $validator->after(function($validator) use ($request, $usuario) {
            if (!Hash::check($request->old_password, $usuario->password)){
                $validator->errors()->add('old_password', 'Contraseña actual incorrecta');
            }
        });

        if ($validator->fails()) {
            return redirect('perfil/edit')
                ->withErrors($validator)
                ->withInput();
        }*/

        // Todo funcionó => cambio la contraseña
        $nueva_pass = Hash::make($request->password);

        $usuario->password = $nueva_pass;

        $usuario->save();

        // Ciero la sesion
        Auth::logout();
        Session::flush();

        return redirect('/login')->with('password_changed', true);
    }
}