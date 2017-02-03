<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovementReap extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'movementreap';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reap_id',
        'cpny_id',
        'dmrp_card_identification',
        'dtrp_received_pay_units',
        'dmrp_received_amount',
        'dmrp_date_transaction',
        'modc_input',
        'pers_id',
        'more_record',
        'dmrp_device_id',
    ];
}
