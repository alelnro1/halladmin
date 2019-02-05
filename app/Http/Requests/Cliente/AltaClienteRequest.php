<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class AltaClienteRequest extends FormRequest
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
            'email'       => 'required|email|max:100',
            'telefono'    => 'required|max:30',
            'domicilio'   => 'required',
            'cuit'        => 'required'
        ];
    }
}
