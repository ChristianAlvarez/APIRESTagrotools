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
    public function getMovementReap($created_at)
    {
        $MovementReap = MovementReap::where('created_at', '>', $created_at)->get();
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
            } catch (Exception $e) {
           
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
