<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdministradoresRequest extends FormRequest
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
            'apellido'    => 'required|max:100',
            'password'    => 'sometimes|nullable|confirmed|min:6',
            'domicilio'   => 'required',
            'email'       => 'required|email|max:100|unique:users',
            'telefono'    => 'required',
            'negocio'     => 'required|max:100',
            'archivo'     => 'sometimes|nullable|max:1000|mimes:jpg,jpeg,png,gif',
        ];
    }
}
