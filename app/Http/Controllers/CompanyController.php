<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveCompany(Request $request)
    {
        $request = $request->all();
        
        try
            {
                $Company = new \App\Company();
                
                $Company = Company::updateOrCreate(
                                       ['cpny_id' => $request['cpny_id']],
                                       ['cpny_name' => $request['cpny_name'],
                                        'cpny_active' => $request['cpny_active'],
                                        'cpny_record' => $request['cpny_record']]
                                    );

                /*$Company = Company::updateOrCreate(['cpny_name' => $request['cpny_name'],
                                                    'cpny_active' => $request['cpny_active'],
                                                    'cpny_record' => $request['cpny_record']],
                                                    ['cpny_id' => $request['cpny_id']]);*/


                /*$Company = Company::updateOrCreate(
                                        ['cpny_id' => $request['cpny_id']],
                                        ['cpny_name' => $request['cpny_name'],
                                         'cpny_active' => $request['cpny_active'],
                                         'cpny_record' => $request['cpny_record']]
                                    );*/

                if ($Company) {
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
        //
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
