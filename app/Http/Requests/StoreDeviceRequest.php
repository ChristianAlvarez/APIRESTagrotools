<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends ApiRequest
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
            'devi_id'       => 'required|max:50',
            'pers_id'       => 'required|exists:userspicking,pers_id',
            'devi_name'     => 'required|max:50',
            'devi_active'   => 'required|boolean',
            'devi_record'   => 'required|boolean',      
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $array = [
                'devi_id.required'      => 'devi_id - Identificador del dispositivo es requerido',
                'devi_id.max'           => 'devi_id - Identificador del dispositivo maximo de caracteres permitidos 50',

                'pers_id.required'      => 'pers_id - Identificador del usuario es requerido',
                'pers_id.exists'        => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',

                'devi_name.required'    => 'devi_name - Nombre del dispositivo es requerido',
                'devi_name.max'         => 'devi_name - Nombre del dispositivo maximo de caracteres permitidos 50',

                'devi_active.required'  => 'devi_active - Estado dispositivo es requerido',
                'devi_active.boolean'   => 'devi_active - Estado dispositivo debe ser formato booleano 0, 1',

                'devi_record.required'  => 'devi_record - Estado sincronización es requerido',
                'devi_record.boolean'   => 'devi_record - Estado sincronización debe ser formato booleano 0, 1',

        ];

        return $array;
    }
}
