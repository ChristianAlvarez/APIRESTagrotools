<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserPickingCompanyRequest extends FormRequest
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
            'cpny_id'       => 'required|max:12',
            'pers_id'       => 'required|exists:userspicking,pers_id',
            'cpny_name'     => 'required|max:200',
            'cpny_active'   => 'required|boolean',
            'cpny_record'   => 'required|boolean',      
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
            'cpny_id.required'      => 'cpny_id - Identificador de la compañia es requerido',
            'cpny_id.max'           => 'cpny_id - Identificador de la compañia maximo de caracteres permitidos 12',

            'pers_id.required'      => 'pers_id - Identificador del usuario es requerido',
            'pers_id.exists'        => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',

            'cpny_name.required'    => 'cpny_name - Nombre de la compañia es requerido',
            'cpny_name.max'         => 'cpny_name - Nombre de la compañia maximo de caracteres permitidos 200',

            'cpny_active.required'  => 'cpny_active - Estado compañia es requerido',
            'cpny_active.boolean'   => 'cpny_active - Estado compañia debe ser formato booleano 0, 1',

            'uspi_record.required'      => 'uspi_record - Estado sincronización es requerido',
            'uspi_record.boolean'       => 'uspi_record - Estado sincronización debe ser formato booleano 0, 1',
        ];
    }
}
