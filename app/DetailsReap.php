<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailsReap extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'detailsreap';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reap_id',
        'cpny_id',
        'card_identification',
        'pers_name',
        'quad_name',
        'dere_status_card',
        'dere_record',
    ];
}
