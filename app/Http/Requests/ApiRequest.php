<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

abstract class ApiRequest extends Request
{
    public function wantsJson()
    {
         return true;
    }
}