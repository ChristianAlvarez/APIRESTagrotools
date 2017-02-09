<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends ApiRequest
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
            'cpny_id'       => 'required|max:12',
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

            'cpny_name.required'    => 'cpny_name - Nombre de la compañia es requerido',
            'cpny_name.max'         => 'cpny_name - Nombre de la compañia maximo de caracteres permitidos 200',

            'cpny_active.required'  => 'cpny_active - Estado compañia es requerido',
            'cpny_active.boolean'   => 'cpny_active - Estado compañia debe ser formato booleano 0, 1',

            'cpny_record.required'  => 'cpny_record - Estado sincronización es requerido',
            'cpny_record.boolean'   => 'cpny_record - Estado sincronización debe ser formato booleano 0, 1',
        ];
    }
}
