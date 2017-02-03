<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPicking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'userspicking';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pers_id',
        'pers_name',
        'uspi_password',
        'uspi_active',
        'uspi_record',
    ];

    public function Device(){
        return $this->HasMany('App\Device', 'pers_id', 'pers_id');
    }

    public function UserPickingCompany(){
        return $this->HasMany('App\UserPickingCompany', 'pers_id', 'pers_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'uspi_password', 
    ];
}
