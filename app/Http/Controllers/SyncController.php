<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Picking;
use App\Device;
use App\Company;
use App\Reap;
use App\DetailsReap;

class SyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SyncUp(Request $request)
    {
        
        $requests = $request->only('pers_id', 'cpny_id', 'updated_at_company', 'updated_at_picking', 'updated_at_reap', 'updated_at_detailsreap'); 

        $Pers_id = $request->pers_id;
        $Cpny_id = $request->cpny_id;
        $Updated_at_company = $request->updated_at_company;
        $Updated_at_picking = $request->updated_at_picking;
        $Updated_at_reap = $request->updated_at_reap;
        $Updated_at_detailsreap = $request->updated_at_detailsreap;

        $data = [
            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id,
        ]; 

        $rules = [
            'Pers_id' => 'required|max:15|exists:picking,pers_id',
            'Cpny_id' => 'required|max:20',
        ];

        $messages = [
            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 15',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 20',
        ];       

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

            $Company = Company::where('cpny_id', $Cpny_id)
                              ->where('cpny_active', 1)
                              ->where('cpny_record', 0)
                              ->where('updated_at', '>', $Updated_at_company)
                              ->orderBy('updated_at', 'desc')
                              ->get();

            $Picking = Picking::where('pers_id', $Pers_id)
                              ->where('cpny_id', $Cpny_id)
                              ->where('pick_active', 1)
                              ->where('pick_record', 0)
                              ->where('updated_at', '>', $Updated_at_picking)
                              ->orderBy('updated_at', 'desc')
                              ->get();

            $Reap = Reap::where('cpny_id', $Cpny_id)
                          ->where('pers_id', $Pers_id)
                          ->where('reap_record', 0)
                          ->where('updated_at', '>', $Updated_at_reap)
                          ->orderBy('updated_at', 'desc')
                          ->get();

            $DetailsReap = DetailsReap::where('cpny_id', $Cpny_id)
                                ->where('dere_status_card', 1)
                                ->where('dere_record', 0)
                                ->where('updated_at', '>', $Updated_at_detailsreap)
                                ->orderBy('updated_at', 'desc')
                                ->get();

            $Data = [
                'Picking' => $Picking,
                'Company' => $Company,
                'Reap' => $Reap,
                'DetailsReap' => $DetailsReap
            ];

            return response()->json([
              'Success' => "Success"
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SynchronizedPickings(Request $request)
    {
        $requests = $request->only('pers_id', 'cpny_id', 'updated_at_picking');

        $Pers_id = $request->pers_id;
        $Cpny_id = $request->cpny_id;
        $Updated_at_picking = $request->updated_at_picking;

        $data = [

            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id,

            'created_userpicking'        => $Updated_at_picking
        ]; 

        $rules = [

            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
            'Cpny_id' => 'required|max:20',

            'created_userpicking'        => 'required|date',
        ];

        $messages = [

            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',

            'created_userpicking.required'          => 'created_userpicking - Periodo tabla UserPicking es requerido',
            'created_userpicking.date'              => 'created_userpicking - Periodo tabla UserPicking debe ser formato Date valido',
        ]; 

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

          $UserPicking = Picking::where('pers_id', $Pers_id)
                                  ->where('cpny_id', $Cpny_id)
                                  ->where('pick_active', 1)
                                  ->where('pick_record', 0)
                                  ->whereDate('updated_at', '<=',  $Updated_at_picking)
                                  ->update(['pick_record' => 1]);

          return response()->json([
                        'Success' => "Success"
                    ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SynchronizedCompanies(Request $request)
    {
        $requests = $request->only('cpny_id', 'updated_at_company');

        $Cpny_id = $request->cpny_id;
        $Updated_at_company = $request->updated_at_company;

        $data = [

            'Cpny_id' => $Cpny_id,

        ]; 

        $rules = [

            'Cpny_id' => 'required|max:20',

        ];

        $messages = [

            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',
        ];  

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

          $UserPickingCompanys = Company::where('cpny_id', $Cpny_id)
                                          ->where('cpny_active', 1)
                                          ->where('cpny_record', 0)
                                          ->whereDate('updated_at', '<=',  $Updated_at_company)
                                          ->update(['cpny_record' => 1]);  

          return response()->json([
                        'Success' => "Success"
                    ]);
        }  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SynchronizedReaps(Request $request)
    {
        $requests = $request->only('pers_id', 'cpny_id', 'updated_at_reap'); 

        $Pers_id = $request->pers_id;
        $Cpny_id = $request->cpny_id;
        $Updated_at_reap = $request->updated_at_reap;

        $data = [
            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id,

            'created_reap' => $Updated_at_reap,
        ]; 

        $rules = [

            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
            'Cpny_id' => 'required|max:20',

            'created_reap'               => 'required|date',
        ];

        $messages = [

            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',

            'created_reap.required'                 => 'created_reap - Periodo tabla Reap es requerido',
            'created_reap.date'                     => 'created_reap - Periodo tabla Reap debe ser formato Date valido',

          
        ]; 

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

          $Reaps = Reap::where('cpny_id', $Cpny_id)
                         ->where('pers_id', $Pers_id)
                         ->where('reap_record', 0)
                         ->whereDate('updated_at', '<=',  $Updated_at_reap)
                         ->update(['reap_record' => 1]);

           return response()->json([
                        'Success' => "Success"
                    ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SynchronizedDetailsreap(Request $request)
    {
        $requests = $request->only('pers_id', 'cpny_id', 'updated_at_detailsreap'); 

        $Pers_id = $request->pers_id;
        $Cpny_id = $request->cpny_id;
        $Updated_at_detailsreap = $request->updated_at_detailsreap;

        $data = [

            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id,

            'created_detailsreap'        => $Updated_at_detailsreap
        ]; 

        $rules = [

            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
            'Cpny_id' => 'required|max:20',

            'created_detailsreap'        => 'required|date',
        ];

        $messages = [

            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',

            'created_detailsreap.required'          => 'created_detailsreap - Periodo tabla DetailsReap es requerido',
            'created_detailsreap.date'              => 'created_detailsreap - Periodo tabla DetailsReap debe ser formato Date valido',
        ]; 

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

          $DetailsReap = DetailsReap::whereIn('reap_id', $reap->reap_id)
                                      ->where('dere_status_card', 1)
                                      ->where('dere_record', 0)
                                      ->whereDate('updated_at', '<=',  $Updated_at_detailsreap)
                                      ->update(['dere_record' => 1]);

          return response()->json([
                        'Success' => "Success"
                    ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Synchronized(Request $request)
    {

      $requests = $request->only('pers_id', 'cpny_id', 'updated_at_company', 'updated_at_picking', 'updated_at_reap', 'updated_at_detailsreap'); 

        $Pers_id = $request->pers_id;
        $Cpny_id = $request->cpny_id;
        $Updated_at_company = $request->updated_at_company;
        $Updated_at_picking = $request->updated_at_picking;
        $Updated_at_reap = $request->updated_at_reap;
        $Updated_at_detailsreap = $request->updated_at_detailsreap;

        $data = [

            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id,

            'created_userpicking'        => $Updated_at_picking, 
            'created_userpickingcompany' => $Updated_at_company,
            'created_reap'               => $Updated_at_reap,
            'created_detailsreap'        => $Updated_at_detailsreap
        ]; 

        $rules = [

            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
            'Cpny_id' => 'required|max:20',

            'created_userpicking'        => 'required|date',
            'created_userpickingcompany' => 'required|date',
            'created_reap'               => 'required|date',
            'created_detailsreap'        => 'required|date',
        ];

        $messages = [

            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',

            'created_userpicking.required'          => 'created_userpicking - Periodo tabla UserPicking es requerido',
            'created_userpicking.date'              => 'created_userpicking - Periodo tabla UserPicking debe ser formato Date valido',

            'created_device.required'               => 'created_device - Periodo tabla Device es requerido',
            'created_device.date'                   => 'created_device - Periodo tabla Device debe ser formato Date valido',

            'created_userpickingcompany.required'   => 'created_userpickingcompany - Periodo tabla UserPickingCompany es requerido',
            'created_userpickingcompany.date'       => 'created_userpickingcompany - Periodo tabla UserPickingCompany debe ser formato Date valido',

            'created_reap.required'                 => 'created_reap - Periodo tabla Reap es requerido',
            'created_reap.date'                     => 'created_reap - Periodo tabla Reap debe ser formato Date valido',

            'created_detailsreap.required'          => 'created_detailsreap - Periodo tabla DetailsReap es requerido',
            'created_detailsreap.date'              => 'created_detailsreap - Periodo tabla DetailsReap debe ser formato Date valido',
        ];       

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

            $UserPicking = Picking::where('pers_id', $Pers_id)
                                        ->where('pick_active', 1)
                                        ->where('pick_record', 0)
                                        ->whereDate('updated_at', '<=',  $Updated_at_picking)
                                        ->update(['pick_record' => 1]);
            

            /*$Device = Device::where('devi_id', $Devi_id)
                             ->where('devi_active', 1)
                             ->where('devi_record', 0)
                             ->whereDate('created_at', '<=',  $created_device)
                             ->update(['devi_record' => 1]);*/


            $UserPickingCompanys = Company::where('pers_id', $Pers_id)
                                                        ->where('cpny_id', $Cpny_id)
                                                        ->where('cpny_active', 1)
                                                        ->where('cpny_record', 0)
                                                        ->whereDate('updated_at', '<=',  $Updated_at_company)
                                                        ->update(['cpny_record' => 1]);

            $Reaps = Reap::where('cpny_id', $Cpny_id)
                         ->where('pers_id', $Pers_id)
                         ->where('reap_record', 0)
                         ->whereDate('updated_at', '<=',  $Updated_at_reap)
                         ->update(['reap_record' => 1]);
            
            $DetailsReap = DetailsReap::whereIn('reap_id', $reap->reap_id)
                                      ->whereIn('dere_status_card', 1)
                                      ->whereIn('dere_record', 0)
                                      ->whereDate('updated_at', '<=',  $Updated_at_detailsreap)
                                      ->update(['dere_record' => 1]);

            return response()->json([
                        'Success' => "Success"
                    ]);
        }
    }

}
