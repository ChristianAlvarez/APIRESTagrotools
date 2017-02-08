<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePickingRequest extends ApiRequest
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
            'pick_password' => 'required|max:50',
            'pick_active'   => 'required|boolean',
            'pick_record'   => 'required|boolean',      
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

            'pick_password.required'    => 'pick_password - Password del pickeador es requerido',
            'pick_password.max'         => 'pick_password - password del pickeador maximo de caracteres permitidos 50',

            'pick_active.required'      => 'pick_active - Estado usuario es requerido',
            'pick_active.boolean'       => 'pick_active - Estado usuario debe ser formato booleano 0, 1',

            'pick_record.required'      => 'pick_record - Estado sincronización es requerido',
            'pick_record.boolean'       => 'pick_record - Estado sincronización debe ser formato booleano 0, 1',
        ];
    }
}
