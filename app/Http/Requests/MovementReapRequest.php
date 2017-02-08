<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class MovementReapRequest extends ApiRequest
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
            'reap_id'                   => 'required|max:20',
            'cpny_id'                   => 'required|max:12',
            'dmrp_card_identification'  => 'required|max:50',
            'dtrp_received_pay_units'   => 'required|numeric',
            'dmrp_received_amount'      => 'required|numeric',
            'dmrp_date_transaction'     => 'required|date|date_format:H:i',
            'modc_input'                => 'required|boolean',
            'pers_id'                   => 'required|exists:userspicking,pers_id',
            'more_record'               => 'required|boolean',
            'dmrp_device_id'            => 'required|max:50',
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
            'reap_id.required'                  => 'reap_id - Id es requerido',
            'reap_id.max'                       => 'reap_id - Id maximo de caracteres permitidos 20',

            'cpny_id.required'                  => 'cpny_id - Compañia es requerido',
            'cpny_id.max'                       => 'cpny_id - Compañia maximo de caracteres permitidos 12',

            'dmrp_card_identification.required' => 'dmrp_card_identification - Identificador de la cosecha es requerido',
            'dmrp_card_identification.max'      => 'dmrp_card_identification - Identificador de la cosecha maximo de caracteres permitidos 50',

            'dtrp_received_pay_units.required'  => 'dtrp_received_pay_units - Cantidad recolectada es requerido',
            'dtrp_received_pay_units.numeric'   => 'dtrp_received_pay_units - Cantidad recolectada debe ser formato numerico',

            'dmrp_received_amount.required'     => 'dmrp_received_amount - Unidades de recolección es requerido',
            'dmrp_received_amount.numeric'      => 'dmrp_received_amount - Unidades de recolección debe ser formato numerico',

            'dmrp_date_transaction.required'    => 'dmrp_date_transaction - Fecha de recolección es requerido',
            'dmrp_date_transaction.date'        => 'dmrp_date_transaction - Fecha de recolección debe ser formato tipo date',
            'dmrp_date_transaction.date_format' => 'dmrp_date_transaction - Fecha de recolección debe ser formato tipo date',

            'modc_input.required'               => 'modc_input - Entrada salida de kilos es requerido',
            'modc_input.boolean'                => 'modc_input - Entrada salida de kilos debe ser formato booleano 0, 1',

            'pers_id.required'                  => 'pers_id - Identificador del usuario es requerido',
            'pers_id.exists'                    => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',

            'more_record.required'              => 'more_record - Estado sincronización es requerido',
            'more_record.boolean'               => 'more_record - Estado sincronización debe ser formato booleano 0, 1',

            'dmrp_device_id.required'           => 'dmrp_device_id - Id del dispositivo es requerido',
            'dmrp_device_id.max'                => 'dmrp_device_id - Id del dispositivo maximo de caracteres permitidos 50',

        ];
    }
}
