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

    public function company(Request $request)
    {
        $QueryInsert = $request['0'];
        $QueryUpdate = $request['1'];

        if (!empty($QueryInsert)) {
            saveCompany($QueryInsert);
        }

        if (!empty($QueryUpdate)) {
            updateCompany($QueryUpdate);
        }

        return response()->json([
            'Codigo' => "2"
        ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
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
                $Company = Company::insert($request);

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
            } catch (Exception $e) {
           
        }
        /*$request = $request->all();
        
        try
            {
                $Company = new \App\Company();
                $Company = Company::insert($request);

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

            }*/
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCompany(Request $request)
    {
        $companys = $request->all();    

        try
            {
                if (count($companys) > 4) {
                    foreach  ($companys as $id_key => $company) {

                      $Company =  Company::where(['cpny_id' => $id_key])->update($company);
                    }
                }
                else{
                    $Company = Company::find($companys['cpny_id']);
                    $Company->cpny_name = $companys['cpny_name'];
                    $Company->cpny_active = $companys['cpny_active'];
                    $Company->cpny_record = $companys['cpny_record'];
                    $Company->save();
                }
                /*foreach ($companys as $key => $value) {
                            $Company = Company::find($key);
                            $Company->cpny_name = $value;
                            $Company->cpny_active = $value;
                            $Company->cpny_record = $value;
                            $Company->save();
                }

                $Company = Company::whereIn('company_id', $itemTypes)
                    ->update([$request]);*/

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
