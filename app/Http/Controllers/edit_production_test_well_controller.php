<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\IntervalProducerRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\plt;
use App\formacionxpozo;

use App\Http\Requests\ProductionTestRequest;



class edit_production_test_well_controller extends Controller
{
    /**
     * Despliega la vista edit_production_test_well con la información de pozos para popular los select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $pozo = \Request::get('pozoId');
                $pozoN = DB::table('pozos')->where('id',$pozo)->value('nombre');
                $intervals = formacionxpozo::select('nombre')->where('pozo_id',$pozo)->get();
                return view('edit_production_test_well', ['pozo' => $pozo, 'pozoN' => $pozoN, 'intervals' => $intervals]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
        
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

    public function store(ProductionTestRequest $request)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){

                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'pltqo'=> 'boolean',
                    'pltqg'=> 'boolean',
                    'pltqw'=> 'boolean',
                 ]);


                if ($validator->fails()) {
                    return \Redirect::action('listProductionTestICE', compact('pozoId'))
                        ->withErrors($validator)
                        ->withInput();
                }else{

                    //Editar tabla PLT
                    $plt=new plt;
                    $table = str_replace(",[null,null,null,null,null]","",$request->input("ProdT"));
                    $interval=array();
                    $date=array();
                    $pqo=array();
                    $pqg=array();
                    $pqw=array();
                    $table = json_decode($table);
                    plt::where('intervalo', $table[0][0])->delete();
                    plt::where('pozo_id', \Request::get('pozoId'))->delete();

                    foreach ($table as $value) {
                        if(count($table)>=1){
                            $plt = new plt;
                            $plt->intervalo = str_replace(",", ".", $value[0]);
                            $interval[] = (float)str_replace(",", ".", $value[0]);

                            $plt->fecha = str_replace(",",".",$value[1]);
                            $date[] = (float)str_replace(",", ".", $value[1]);

                            $plt->pqo = str_replace(",", ".", $value[2]);
                            $pqo[] = (float)str_replace(",", ".", $value[2]);

                            $plt->pqg = str_replace(",",".",$value[3]);
                            $pqg[] = (float)str_replace(",", ".", $value[3]);

                            $plt->pqw = str_replace(",",".",$value[4]);
                            $pqw[] = (float)str_replace(",", ".", $value[4]);

                            $plt->pozo_id=\Request::get('pozoId');
                            $plt->save();
                        }
                    }

                    $request->session()->flash('mensaje', 'Record successfully entered.');
                    return redirect('listWellC');
                }

            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
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
