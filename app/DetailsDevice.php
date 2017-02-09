<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailsDevice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'detailsdevice';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'devi_id',
        'pers_id',
        'dtde_active',
        'dtde_record',
    ];
}
