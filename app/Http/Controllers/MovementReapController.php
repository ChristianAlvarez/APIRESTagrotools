<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\MovementReap;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\MovementReapRequest;

class MovementreapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMovementReap($updated_at, $cpny_id)
    {
        $MovementReap = MovementReap::where('updated_at', '>', $updated_at)
                                    ->where('cpny_id', $cpny_id)
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
    public function get()
    {
        $MovementReap = MovementReap::all();
        
        return Response()->json(array('MovementReap' => $MovementReap));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postMovementReap(Request $request)
    {
       //$updated_at, $cpny_id
       $Request = $request->all();

       $id = MovementReap::where('id' ,'>' ,0)
                         ->where('updated_at', '>', $Request['updated_at'])
                         ->where('cpny_id', $Request['cpny_id'])
                         ->where('more_record', 0)
                         ->pluck('id')->toArray();  
        
        if (!empty($id)) 
        {

            try 
                {
                    
                  $MovementReap = MovementReap::whereIn('id',$id)->update(['more_record' => 1]);

                  return response()->json([
                      'Codigo' => "2"
                  ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
            }
        }
        else
        {
          return response()->json([
              'Codigo' => "1",
              'Descripcion' => "No existen registros para la condición de su consulta"
          ]);
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
