<?php

namespace App\Http\Requests;

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
        return [
            'nombre'      => 'required|max:100',
            'apellido'    => 'required|max:500',
            'archivo'     => 'max:2000|mimes:jpg,jpeg,png,gif',
            'password'    => 'required|confirmed|min:6',
            'email'       => 'required|email|max:100|unique:users',
            'menus'       => 'required'
        ];
    }
}
