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
use App\fluidoxpozos;
use App\formacion;
use App\cuenca;
use App\permeabilidad_relativaxf_gl;
use App\permeabilidad_relativaxf_wo;
use App\presion_yacimiento;
use App\PvtFormacionXPozo;

class add_producing_interval_controller extends Controller
{
    /**
     * Despliega la vista de add_producing_interval con la información de formación y cuencas para la craeción de intervalos productores.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if (\Auth::check()) {
            if(\Auth::User()->profile!=5){
                $data['formacion'] = formacion::select('id', 'nombre');
                $data2['basin'] = cuenca::select('id', 'nombre');
                return view('intervalos.add_producing_interval', $data, $data2);
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
    public function store(IntervalProducerRequest $request)
    {
        //dd($request->all());
        if (\Auth::check()) {
            if(\Auth::User()->profile!=5){
                //Validaciones para formulario
                
                //VALIDAR QUE si ya está asignado, no se debería dejar asignar de nuevo (el intervalo productor)
                $formacionxwellvalidate = DB::table('formacionxpozos')->
                where('pozo_id', $request->input('wellName'))->
                where('formacion_id', $request->input('formacionName'))->
                where('nombre', $request->input('nameInterval'))->count();

                if ($formacionxwellvalidate > 0) {
                    return redirect('AddFormationWellC')
                        ->withErrors('Producing interval has already been created.')
                        ->withInput();
                }

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
                //$formationxwell= formacionxpozo::find(90);

                if($request->input("RelP") != '[[null]]')
                {
                    $this->storeWo($request, $formationxwell);
                }

                if($request->input("RelP2") != '[[null,null,null,null]]')
                {
                    $this->storeGas($request, $formationxwell);
                }

                if($request->input("reservoirPressure") != '[[null,null,null]]')
                {
                    $this->storeYacimiento($request, $formationxwell);
                }

                 //Guardar tabla de pvt de campos
                if(!empty(json_decode($request->pvt_table)))
                {

                    $request->formacionxpozos_id = $formationxwell->id;
                    PvtFormacionXPozo::store($request);
                
                }

                return view('database');
            
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }


    public function storeGas($request, $formationxwell){

        $relative_permeability_gl=new permeabilidad_relativaxf_gl;
                    
                    $tableGl = str_replace(",[null,null,null,null]","",$request->input("RelP2"));
                    $Sg=array();
                    $Krg=array();
                    $Krl=array();
                    $Pcgl=array();
                    $tableGl = json_decode($tableGl);


                    foreach ($tableGl as $valueGl) {
                            $relative_permeability_gl = new permeabilidad_relativaxf_gl;
                            $relative_permeability_gl->sg = str_replace(",", ".", $valueGl[0]);
                            $Sg[] = (float)str_replace(",", ".", $valueGl[0]);

                            $relative_permeability_gl->krg = str_replace(",",".",$valueGl[1]);
                            $Krg[] = (float)str_replace(",", ".", $valueGl[1]);

                            $relative_permeability_gl->krl = str_replace(",",".",$valueGl[2]);
                            $Krl[] = (float)str_replace(",", ".", $valueGl[2]);

                            $relative_permeability_gl->pcgl = str_replace(",",".",$valueGl[3]);
                            $Pcgl[] = (float)str_replace(",", ".", $valueGl[3]);

                            $relative_permeability_gl->formacionxpozo_id=$formationxwell->id;
                            $relative_permeability_gl->save();
                        
                    }
    }

    public function storeWo($request, $formationxwell) {

        if ($request->input("RelP") !== "[{}]" && $request->input("RelP") !== "[[null],[null]]") { 
            //Guardar tabla permeabilidad relativa w-o
            $relative_permeability_wo=new permeabilidad_relativaxf_wo;
                    
            $tableWo = str_replace(",[null,null,null,null]","",$request->input("RelP"));
            $Sw=array();
            $Krw=array();
            $Kro=array();
            $Pcwo=array();
            $tableWo = json_decode($tableWo);

            foreach ($tableWo as $valueWo) {
                    $relative_permeability_wo = new permeabilidad_relativaxf_wo;
                    $relative_permeability_wo->sw = str_replace(",", ".", $valueWo[0]);
                    $Sw[] = (float)str_replace(",", ".", $valueWo[0]);

                    $relative_permeability_wo->krw = str_replace(",",".",$valueWo[1]);
                    $Krw[] = (float)str_replace(",", ".", $valueWo[1]);

                    $relative_permeability_wo->kro = str_replace(",",".",$valueWo[2]);
                    $Kro[] = (float)str_replace(",", ".", $valueWo[2]);

                    $relative_permeability_wo->pcwo = str_replace(",",".",$valueWo[3]);
                    $Pcwo[] = (float)str_replace(",", ".", $valueWo[3]);

                    $relative_permeability_wo->formacionxpozo_id=$formationxwell->id;
                    $relative_permeability_wo->save();
            }
        }

    }

    public function storeYacimiento($request, $formationxwell){

        //Guardar tabla presion de yacimiento
                    $reservoir_pressure=new presion_yacimiento;
                    
                    $tablePy = str_replace(",[null,null,null]","",$request->input("reservoirPressure"));
                    $date=array();
                    $value=array();
                    $comment=array();
                    $tablePy = json_decode($tablePy);

                    foreach ($tablePy as $valuePy) {
                        
                            $reservoir_pressure = new presion_yacimiento;
                            $reservoir_pressure->fecha = str_replace(",", ".", $valuePy[0]);
                            $date[] = (float)str_replace(",", ".", $valuePy[0]);

                            $reservoir_pressure->valor = str_replace(",",".",$valuePy[1]);
                            $value[] = (float)str_replace(",", ".", $valuePy[1]);

                            $reservoir_pressure->comentario = str_replace(",",".",$valuePy[2]);
                            $comment[] = (float)str_replace(",", ".", $valuePy[2]);

                            $reservoir_pressure->id_intervalo=$formationxwell->id;
                            $reservoir_pressure->save();
                        
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
