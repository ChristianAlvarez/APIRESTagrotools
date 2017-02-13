<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $incrementing = false; 

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'company';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cpny_id',
        'cpny_name',
        'cpny_active',
        'cpny_record',
    ];

    protected $primaryKey = "cpny_id";

}
