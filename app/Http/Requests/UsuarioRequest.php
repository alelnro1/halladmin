<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
        $usuario_id = $this->route('usuario')->id;

        return [
            'nombre'      => 'required|max:100',
            'apellido'    => 'required|max:500',
            'domicilio'   => 'required',
            'telefono'    => 'required',
            'archivo'     => 'max:2000|mimes:jpg,jpeg,png,gif',
            'password'    => 'confirmed',
            'email'       => 'required|email|max:100|unique:users,email,' . $usuario_id,
            'menus'       => 'required'
        ];
    }
}
