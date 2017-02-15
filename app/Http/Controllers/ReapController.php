<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reap;
use App\DetailsReap;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreReapRequest;

class ReapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetReap($Pers_id, $Cpny_id)
    {
        $data = [
            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id
        ]; 

        $rules = [
            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
            'Cpny_id' => 'required|max:20',
        ];

        $messages = [
            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',
        ];       

        $validator = Validator::make($data, $rules, $messages);
        //dd($validator);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

            $Reaps = Reap::with('DetailsReap')
                                ->where('cpny_id', $Cpny_id)
                                ->where('pers_id', $Pers_id)
                                ->where('reap_record', 0)
                                ->orderBy('created_at', 'desc')
                                ->get();
           
            return response()->json([
                'msg' => "Success",
                'Reaps' => $Reaps
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2($Pers_id)
    {

        $data = [
            'Pers_id' => $Pers_id
        ]; 

        $rules = [
            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
        ];

        $messages = [
            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
        ];       

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {
            $Reaps = Reap::with('DetailsReap')
                                ->where('pers_id', $Pers_id)
                                ->where('reap_record', 0)
                                ->get();

            return response()->json([
                'msg' => "Success",
                'Reaps' => $Reaps                
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SaveMasterDetailReap(StoreReapRequest $request)
    {

        $masterCollection = collect($request['Reaps']);
        $detailCollection = collect($request['DetailsReap']);

        foreach ($masterCollection as $item) {
            $Reap               = new \App\Reap();
            $Reap->reap_id      = $item['reap_id'];
            $Reap->cpny_id      = $item['cpny_id'];
            $Reap->stus_id      = $item['stus_id'];
            $Reap->pers_id      = $item['pers_id'];
            $Reap->pers_name    = $item['pers_name'];
            $Reap->land_name    = $item['land_name'];
            $Reap->prun_name    = $item['prun_name'];
            $Reap->ticu_name    = $item['ticu_name'];
            $Reap->vare_name    = $item['vare_name'];
            $Reap->mere_name    = $item['mere_name'];
            $Reap->reap_record  = $item['reap_record'];

            if ($Reap->save()) {
                foreach ($detailCollection as $value) {
                    if ($item['reap_id'] == $value['reap_id']) {
                        $DetailsReap                        = new \App\DetailsReap();
                        $DetailsReap->reap_id               = $value['reap_id'];
                        $DetailsReap->card_identification   = $value['card_identification'];
                        $DetailsReap->pers_name             = $value['pers_name'];
                        $DetailsReap->quad_name             = $value['quad_name'];
                        $DetailsReap->dere_status_card      = $value['dere_status_card'];
                        $DetailsReap->dere_record           = $value['dere_record'];
                        $DetailsReap->save();
                    }
                }
            }      
        }
   
        return response()->json([
                'msg' => 'Success',
        ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveReap(Request $request)
    {
        $reaps = collect($request->all());    
        $insert = $reaps->where('row_mode', 1);
        $update = $reaps->where('row_mode', 0);

        /*foreach  ($insert as $reap) {
            dd(collect($reap));
        }*/
        
        //INSERT       
        if (count($insert) > 0) {
        
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->Insert($arr->toArray());
            });
                
        }

        //UPDATE
        if (count($update) > 0) {
            $this->Update($update->toArray());
        }

        return response()->json([
                'Codigo' => "2"
        ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
    }

    private function Insert($reaps)
    {
       
        try {
                $Reap = new \App\Reap();
                $Reap = Reap::insert($reaps);

                if (!$Reap) {
                    return response()->json([
                        'Codigo' => "1"
                    ]);
                }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
    }

    private function Update($reaps)
    {
        try {
            foreach  ($reaps as $id_key => $reap) {
                $Reap =  Reap::where(['reap_id' => $reap['reap_id']])
                             ->where(['cpny_id' => $reap['cpny_id']])
                             ->update(['stus_id' => $reap['stus_id'],
                                       'pers_id' => $reap['pers_id'],
                                       'pers_name' => $reap['pers_name'],
                                       'land_name' => $reap['land_name'],
                                       'prun_name' => $reap['prun_name'],
                                       'ticu_name' => $reap['ticu_name'],
                                       'vare_name' => $reap['vare_name'],
                                       'mere_name' => $reap['mere_name'],
                                       'reap_record' => $reap['reap_record']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
        
    }

}
