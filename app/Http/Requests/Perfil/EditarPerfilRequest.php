<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class EditarPerfilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre' => 'required|max:255',
            'apellido' => 'required|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'max:60',
            'archivo'  => 'max:5000|mimes:jpg,jpeg,png'
        ];
    }

    public function after()
    {
        // Obtengo todos los usuarios con mail igual al ingresado
        $usuarios_con_mail_igual  = User::where('email', $this->email)->select(['id'])->get();

        // Si algun id es distinto al del usuario actual => el email ya existe
        foreach ($usuarios_con_mail_igual as $usuario_con_mail_igual) {
            if ($usuario_con_mail_igual->id != Auth::user()->id) {
                $this->errors()->add('email', 'El email elegido pertenece a otro usuario');
            }
        }
    }
}
