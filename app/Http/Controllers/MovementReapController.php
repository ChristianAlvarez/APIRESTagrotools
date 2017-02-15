<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\MovementReap;
use Illuminate\Http\Request;
use App\Http\Requests\MovementReapRequest;

class MovementreapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMovementReap($created_at, $cpny_id)
    {
        $MovementReap = MovementReap::where('created_at', '>', $created_at)
                                    ->where('cpny_id', $cpny_id)
                                    ->where('more_record', 0)
                                    ->get();
        
        return Response()->json(array('MovementReap' => $MovementReap));

        /*if (!empty($created_at)) {
            $MovementReap = MovementReap::where('created_at', '>', $created_at)->get();
            return Response()->json(array('MovementReap' => $MovementReap));
        }
        else{
            $MovementReap = MovementReap::all();
            return Response()->json(array('MovementReap' => $MovementReap));
        }*/
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postMovementReap($created_at, $cpny_id)
    {
       
       $id = MovementReap::where('id' ,'>' ,0)
                                      ->where('created_at', '>', $created_at)
                                      ->where('cpny_id', $cpny_id)
                                      ->where('more_record', 0)
                                      ->pluck('id')->toArray();  

        if (!empty($id)) 
        {

            try 
                {
                    
                    $MovementReap = MovementReap::whereIn('id',$id)->update(['more_record' => 1]);

                    if ($MovementReap) 
                    {
                        return response()->json([
                            'Codigo' => "2"
                        ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
                    }
                    else{
                        return response()->json([
                            'Codigo' => "1"
                        ]);
                    }
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
            }
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request = $request->all();
        try 
            {
                $MovementReap = new \App\MovementReap();
                $MovementReap = MovementReap::insert($request);

                if ($MovementReap) {
                    return response()->json([
                        'Codigo' => "2"
                    ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
                }
                else{
                    return response()->json([
                            'Codigo' => "1"
                    ]);
                }
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);

            }
    }

}
