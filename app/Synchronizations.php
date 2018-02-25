<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Synchronizations extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'synchronizations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'cpny_id',
        'dmrp_date_transaction',
        'dmrp_device_id',
        'pers_id',
        'latitud',
        'longitud',
        'esdo_id',
        'json',
        'created_at',
        'updated_at',

        /*
        'id',
        'cpny_id',
        'sync_date_transaction',
        'sync_device_id',
        'pers_id',
        'sync_latitud',
        'sync_longitude',
        'esdo_id',
        'created_at',
        'updated_at',
        */
    ];

    public function Movementreap(){
        return $this->hasMany('App\Movementreap', 'synchronizations_id', 'id');
    }
}
