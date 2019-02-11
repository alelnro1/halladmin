<?php

namespace App\Http\Requests\PriceList;

use Illuminate\Foundation\Http\FormRequest;

class AltaPriceListRequest extends FormRequest
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
            'nombre' => 'required|string|max:100',
            'descripcion' => 'max:150|string',
            'vigencia_desde' => 'required_with:vigencia_hasta',
            'vigencia_hasta' => 'required_with:vigencia_desde|after:vigencia_hasta',
        ];
    }

    public function after()
    {
        die('aca');
    }
}
