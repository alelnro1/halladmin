<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticuloRequest extends FormRequest
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
            'codigo' => 'required|max:100',
            'descripcion' => 'required|max:500',
            'categoria_id.*' => 'required|max:100',
            'precio' => 'required|numeric',
            'talle_id' => 'required',
            'color' => 'required|string',
            'genero_id' => 'required|max:100',
            'cantidad' => 'required'
        ];
    }
}
