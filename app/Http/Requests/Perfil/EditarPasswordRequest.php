<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class EditarPasswordRequest extends FormRequest
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
            'old_password'  => 'required',
            'password'      => 'required|min:6|confirmed'
        ];
    }

    public function after()
    {
        // Valido que la contraseña vieja sea la actual
        if (!Hash::check($this->old_password, $this->password)){
            $this->errors()->add('old_password', 'Contraseña actual incorrecta');
        }
    }
}
