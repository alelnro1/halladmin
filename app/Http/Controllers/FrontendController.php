<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    public function index()
    {
        return view('front-end.index');

    }
    public function register()
    {
        return view('auth.register');
    }

    /*public function registerConEmail($email, $codigo)
    {
        return view('auth.register')->with(['email' => $email, 'codigo' => $codigo]);
    }*/

    public function contacto()
    {
        return view('front-end.contacto');
    }

    /*public function enviarContacto(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'email' => 'required|email',
            'titulo' => 'required',
            'mensaje' => 'required'
        ]);

        $nombre = $request->nombre;
        $email = $request->email;
        $titulo = $request->titulo;
        $mensaje = $request->mensaje;

        Mail::send('emails.contacto', ['nombre' => $nombre, 'email' => $email, 'titulo' => $titulo, 'mensaje' => $mensaje],
            function ($mail) use ($request) {
                $mail->from('contacto@aexpensas.com', 'Contacto AExpensas');
                $mail->to('info@doublepoint.com.ar', 'Info AExpensas');
                $mail->subject($request->titulo);

            });

        return redirect('contacto')->with('contacto_enviado', true);
    }

    public function contactarAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator) {
            if ($validator->fails()) {
                echo json_encode(['mail_invalido' => true]);
                return false;
            }
        }

        $email = $request->email;

        Mail::send('emails.footer-contacto', ['email' => $email],
            function ($mail) use ($request) {
                $mail->from('contacto@aexpensas.com', 'Contacto AExpensas');
                $mail->to('info@doublepoint.com.ar', 'Info AExpensas');
                $mail->subject('Contacto AExpensas');
            });

        echo json_encode(['mail_enviado' => true]);
    }*/
}
