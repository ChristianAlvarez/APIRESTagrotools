<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPickingCompany extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'userspickingcompany';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'cpny_id',
        'pers_id',
        'cpny_name',
        'cpny_active',
        'cpny_record',
    ];

}
