<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reap extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'reap';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reap_id',
        'cpny_id',
        'stus_id',
        'pers_id',
        'pers_name',
        'land_name',
        'prun_name',
        'ticu_name',
        'vare_name',
        'mere_name',
        'reap_record',
    ];

    public function DetailsReap(){
        return $this->hasMany('App\DetailsReap', 'reap_id', 'reap_id');
    }

}
