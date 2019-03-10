<?php

namespace App\Http\Requests;

use App\Http\Controllers\AfipController;
use Illuminate\Foundation\Http\FormRequest;

class NegocioRequest extends FormRequest
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
            'condicion_iva' => 'required',
            'cuit' => 'required'
        ];
    }

    /**
     * Después de que pasa la validación, validamos que el CUIT sea de alguien registrado en el IVA
     * y que la condicion frente al iva seleccionada coincida con la registrada en AFIP
     *
     * @param $validator
     */
    public function withValidator($validator)
    {
        // Obtenemos el CUIT del request
        $cuit = $this->cuit;

        // Tipo de registro frente a IVA
        $condicion_iva = $this->condicion_iva;

        // El CUIT viene con una mascara => La limpiamos
        $cuit = str_replace('-', '', $cuit);

        $validator->after(function ($validator) use ($cuit, $condicion_iva) {
            $afipController = new AfipController();

            $persona_afip = $afipController->getContribuyenteAfip($cuit, $condicion_iva);

            dd($persona_afip);

            // Revisamos si dada una CUIT la AFIP nos responde que el contribuyente existe y está registrado
            if (!isset($persona_afip->datosGenerales)) {
                $validator->errors()->add('cuit', 'El CUIT es inválido o no corresponde a un registro frente al IVA');
            }

            // Revisamos la condicion contra AFIP seleccionada contra la que tiene AFIP realmente
            if ($condicion_iva == "responsable_inscripto") {
                $condicion_iva_coincide = isset($persona_afip->datosRegimenGeneral);
            } else {
                $condicion_iva_coincide = isset($persona_afip->datosMonotributo);
            }

            if (!$condicion_iva_coincide) {
                $validator->errors()->add('cuit', 'El CUIT no coincide con el tipo de condición frente al IVA seleccionada');
            }
        });

    }
}
