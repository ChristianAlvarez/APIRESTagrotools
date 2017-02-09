<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReapRequest extends ApiRequest
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
            //REAP
            'reap_id'                   => 'required|max:20',
            'cpny_id'                   => 'required|max:12',
            'stus_id'                   => 'required|max:8',
            'pers_id'                   => 'required|exists:userspicking,pers_id',
            'pers_name'                 => 'required|max:160',
            'land_name'                 => 'required|max:50',
            'prun_name'                 => 'required|max:50',
            'ticu_name'                 => 'required|max:80',
            'vare_name'                 => 'required|max:80',
            'mere_name'                 => 'required|max:80', 
            'reap_record'               => 'required|boolean',

            //DETAILSREAP
            'card_identification'       => 'required|max:50',
            'quad_name'                 => 'max:80',
            'dere_status_card'          => 'required|boolean',
            'dere_record'               => 'required|boolean',
            
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
            //REAP
            'reap_id.required'      => 'reap_id - Id es requerido',
            'reap_id.max'           => 'reap_id - Id maximo de caracteres permitidos 20',

            'cpny_id.required'      => 'cpny_id - Compañia es requerido',
            'cpny_id.max'           => 'cpny_id - Compañia maximo de caracteres permitidos 12',

            'stus_id.required'      => 'stus_id - Estado de la cosecha es requerido',
            'stus_id.max'           => 'stus_id - Estado de la cosecha maximo de caracteres permitidos 50',

            'pers_id.required'      => 'pers_id - Identificador del usuario es requerido',
            'pers_id.exists'        => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',

            'pers_name.required'    => 'pers_name - Nombre del pickeador es requerido',
            'pers_name.max'         => 'pers_name - Nombre del pickeador maximo de caracteres permitidos 160',

            'land_name.required'    => 'land_name - Nombre del predio es requerido',
            'land_name.max'         => 'land_name - Nombre del predio maximo de caracteres permitidos 50',

            'prun_name.required'    => 'prun_name - Nombre Unidad es requerido',
            'prun_name.max'         => 'prun_name - Nombre Unidad maximo de caracteres permitidos 50',

            'ticu_name.required'    => 'ticu_name - Nombre Tipo cultivo es requerido',
            'ticu_name.max'         => 'ticu_name - Nombre Tipo cultivo maximo de caracteres permitidos 80',

            'vare_name.required'    => 'vare_name - Nombre Variedad recolección es requerido',
            'vare_name.max'         => 'vare_name - Nombre Variedad recolección maximo de caracteres permitidos 80',

            'mere_name.required'    => 'mere_name - Nombre Método recolección es requerido',
            'mere_name.max'         => 'mere_name - Nombre Método recolección maximo de caracteres permitidos 80',

            'reap_record.required'  => 'reap_record - Estado sincronización es requerido',
            'reap_record.boolean'   => 'reap_record - Estado sincronización debe ser formato booleano 0, 1',

            //DETAILSREAP
            'card_identification.required'      => 'card_identification - Id del cosechador',
            'card_identification.max'           => 'card_identification - Id del cosechador maximo de caracteres permitidos 50',

            'quad_name.max'                     => 'quad_name - Nombre de la cuadrilla maximo de caracteres permitidos 80',

            'dere_status_card.required'         => 'dere_status_card - Estado detailsreap es requerido',
            'dere_status_card.boolean'          => 'dere_status_card - Estado detailsreap debe ser formato booleano 0, 1',

            'dere_record.required'              => 'dere_record - Estado sincronización es requerido',
            'dere_record.boolean'               => 'dere_record - Estado sincronización debe ser formato booleano 0, 1',
        ];
    }
}
