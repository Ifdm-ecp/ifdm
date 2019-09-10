<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\IntervalProducerRequest;
use App\formacionxpozo;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;

use App\pozo;
use App\campo;
use App\cuenca;
use App\formacion;
use App\fluidoxpozos;
use App\presion_yacimiento;
use App\permeabilidad_relativaxf_gl;
use App\permeabilidad_relativaxf_wo;


class add_producing_interval_well_controller extends Controller
{
    /**
     * Despliega la vista add_producing_interval_well con la información de las formaciones y un pozo específico.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $pozo_id = \Request::get('pozoId');

                $pozo = pozo::find($pozo_id);
                #$field = campo::where('cuenca_id',$pozo->cuenca_id)->first();
                $field = $pozo->campo;

                $formacion = formacion::select('id', 'nombre')->where('campo_id',$field->id)->get();
                $well = pozo::select('id', 'nombre')->get();
                $intervalo = formacionxpozo::where('pozo_id',$pozo->id)->get();

                
                return view('add_producing_interval_well', ['formacion' => $formacion, 'intervalo' => $intervalo, 'well' => $well, 'pozo' => $pozo]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Despliega la vista add_production_test_well con la información de pozo para la creación de un intervalo productor.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $pozo = \Request::get('pozoId');
                $pozoN = DB::table('pozos')->where('id',$pozo)->value('nombre');
                return view('add_production_test_well', ['pozo' => $pozo, 'pozoN' => $pozoN]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IntervalProducerRequest $request)
    {
        //dd($request->all());
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                    //Guardar datos generales del intervalo productor
                    $formationxwell=new formacionxpozo;
                    $formationxwell->nombre = $request->input('nameInterval');
                    $formationxwell->top = $request->input('top');
                    $formationxwell->netpay = $request->input('netpay');
                    $formationxwell->porosidad = $request->input('porosity');
                    $formationxwell->permeabilidad = $request->input('permeability');
                    $formationxwell->presion_reservorio = $request->input('reservoir');
                    $formationxwell->pozo_id = $request->input('wellName');
                    $formationxwell->formacion_id = $request->input('formacionName');
                    $formationxwell->save();
                    $pozoId=$request->input('wellName');


                    //Guardar tabla de permeabilidad relativa w-o
                    $relative_permeability_wo=new permeabilidad_relativaxf_wo;
                    
                    $table = str_replace(",[null,null,null,null]","",$request->input("RelP"));
                    $Sw=array();
                    $Krw=array();
                    $Kro=array();
                    $Pcwo=array();
                    $table = json_decode($table);

                    foreach ($table as $value) {
                        if($value[0]!=null or $value[1]!=null or $value[2]!=null or $value[3]!=null){
                            $relative_permeability_wo = new permeabilidad_relativaxf_wo;
                            $relative_permeability_wo->sw = str_replace(",", ".", $value[0]);
                            $Sw[] = (float)str_replace(",", ".", $value[0]);

                            $relative_permeability_wo->krw = str_replace(",",".",$value[1]);
                            $Krw[] = (float)str_replace(",", ".", $value[1]);

                            $relative_permeability_wo->kro = str_replace(",",".",$value[2]);
                            $Kro[] = (float)str_replace(",", ".", $value[2]);

                            $relative_permeability_wo->pcwo = str_replace(",",".",$value[3]);
                            $Pcwo[] = (float)str_replace(",", ".", $value[3]);

                            $relative_permeability_wo->formacionxpozo_id=$formationxwell->id;
                            $relative_permeability_wo->save();
                        }
                    }
                    


                    //Guardar tabla de permeabilidad relativa g-l
                    $relative_permeability_gl=new permeabilidad_relativaxf_gl;
                    
                    $table = str_replace(",[null,null,null,null]","",$request->input("RelP2"));
                    $Sg=array();
                    $Krg=array();
                    $Krl=array();
                    $Pcgl=array();
                    $table = json_decode($table);

                    foreach ($table as $value) {
                        $relative_permeability_gl = new permeabilidad_relativaxf_gl;
                        $relative_permeability_gl->sg = str_replace(",", ".", $value[0]);
                        $Sg[] = (float)str_replace(",", ".", $value[0]);

                        $relative_permeability_gl->krg = str_replace(",",".",$value[1]);
                        $Krg[] = (float)str_replace(",", ".", $value[1]);

                        $relative_permeability_gl->krl = str_replace(",",".",$value[2]);
                        $Krl[] = (float)str_replace(",", ".", $value[2]);

                        $relative_permeability_gl->pcgl = str_replace(",",".",$value[3]);
                        $Pcgl[] = (float)str_replace(",", ".", $value[3]);

                        $relative_permeability_gl->formacionxpozo_id=$formationxwell->id;
                        $relative_permeability_gl->save();
                    }


                    //Guardar tabla de presion de yacimiento
                    $reservoir_pressure=new presion_yacimiento;
                    
                    $table = str_replace(",[null,null,null]","",$request->input("reservoirPressure"));
                    $date=array();
                    $value=array();
                    $comment=array();
                    $table = json_decode($table);

                    foreach ($table as $value) {
                        if($value[0]){
                            $reservoir_pressure = new presion_yacimiento;
                            $reservoir_pressure->fecha = str_replace(",", ".", $value[0]);
                            $date[] = (float)str_replace(",", ".", $value[0]);

                            $reservoir_pressure->valor = str_replace(",",".",$value[1]);
                            $value[] = (float)str_replace(",", ".", $value[1]);

                            $reservoir_pressure->comentario = str_replace(",",".",$value[2]);
                            $comment[] = (float)str_replace(",", ".", $value[2]);

                            $reservoir_pressure->id_intervalo=$formationxwell->id;
                            $reservoir_pressure->save();
                        }

                        if($request->pvt_table != '')
                        {
                            $request->formacionxpozos_id = $formationxwell->id;
                            PvtFormacionXPozo::store($request);
                        }

                    return view('database');
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
