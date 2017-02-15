<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\DetailsReap;

class DetailsReapController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDetailsReap(Request $request)
    {

        $detailsreaps = collect($request->all());    
        $insert = $detailsreaps->where('row_mode', 1);
        $update = $detailsreaps->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->Insert($arr->toArray());
                //$this->Insert($comp);
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

     private function Insert($detailsreaps)
    {
       
        try {
                $DetailsReap = new \App\DetailsReap();
                $DetailsReap = DetailsReap::insert($detailsreaps);
                if (!$DetailsReap) {
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

    private function Update($detailsreaps)
    {
        try {
            foreach  ($detailsreaps as $id_key => $detailsreap) {
                $DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                           ->where(['cpny_id' => $detailsreap['cpny_id']])
                                           ->where(['card_identification' => $detailsreap['card_identification']])
                                           ->update(['pers_name' => $detailsreap['pers_name'],
                                                     'quad_name' => $detailsreap['quad_name'],
                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                     'dere_record' => $detailsreap['dere_record']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
        
    }

}
