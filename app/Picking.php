<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picking extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'picking';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pers_id',
        'pers_name',
        'pick_password',
        'pick_active',
        'pick_record',
    ];

    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pick_password', 
    ];
}
