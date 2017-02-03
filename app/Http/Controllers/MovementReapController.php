<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $MovementReap = MovementReap::all();
        return Response()->json(array('MovementReap' => $MovementReap));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovementReapRequest $request)
    {

        $MovementReap                           = new \App\MovementReap();
        $MovementReap->reap_id                  = $request->reap_id;
        $MovementReap->cpny_id                  = $request->cpny_id;
        $MovementReap->dmrp_card_identification = $request->dmrp_card_identification;
        $MovementReap->dtrp_received_pay_units  = $request->dtrp_received_pay_units;
        $MovementReap->dmrp_received_amount     = $request->dmrp_received_amount;
        $MovementReap->dmrp_date_transaction    = $request->dmrp_date_transaction;
        $MovementReap->modc_input               = $request->modc_input;
        $MovementReap->user_id                  = $request->user_id;
        $MovementReap->more_record              = $request->more_record;
        $MovementReap->dmrp_device_id           = $request->dmrp_device_id;
        $MovementReap->save();

        return response()->json([
                'msg' => 'Success',
            ], STATUS_CODE
        );
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
