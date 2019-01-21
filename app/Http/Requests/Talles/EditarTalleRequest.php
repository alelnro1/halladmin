<?php

namespace App\Http\Requests\Talles;

use Illuminate\Foundation\Http\FormRequest;

class EditarTalleRequest extends FormRequest
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
            'nombre'        => 'required|max:100',
            'categoria_id'  => 'required|not_in:0'
        ];
    }
}
