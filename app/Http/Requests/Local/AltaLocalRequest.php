<?php

namespace App\Http\Requests\Local;

use Illuminate\Foundation\Http\FormRequest;

class AltaLocalRequest extends FormRequest
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
            'archivo'     => 'mimes:jpg,jpeg,png,gif',
            'email'       => 'email|max:100',
            'telefono'    => 'required'
        ];
    }
}
