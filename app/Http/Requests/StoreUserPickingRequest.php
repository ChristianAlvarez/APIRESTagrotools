<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserPickingRequest extends FormRequest
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
            //USERPICKING
            'pers_id'       => 'required|max:12',
            'pers_name'     => 'required|max:160',
            'uspi_password' => 'required|max:50',
            'uspi_active'   => 'required|boolean',
            'uspi_record'   => 'required|boolean',      
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //USERPICKING
            'pers_id.required'          => 'pers_id - Identificador del usuario es requerido',
            'pers_id.max'               => 'pers_id - Identificador del usuario maximo de caracteres permitidos 12',

            'pers_name.required'        => 'pers_name - Nombre del pickeador es requerido',
            'pers_name.max'             => 'pers_name - Nombre del pickeador maximo de caracteres permitidos 160',

            'uspi_password.required'    => 'uspi_password - Password del pickeador es requerido',
            'uspi_password.max'         => 'uspi_password - password del pickeador maximo de caracteres permitidos 50',

            'uspi_active.required'      => 'uspi_active - Estado usuario es requerido',
            'uspi_active.boolean'       => 'uspi_active - Estado usuario debe ser formato booleano 0, 1',

            'uspi_record.required'      => 'uspi_record - Estado sincronización es requerido',
            'uspi_record.boolean'       => 'uspi_record - Estado sincronización debe ser formato booleano 0, 1',
        ];
    }
}
