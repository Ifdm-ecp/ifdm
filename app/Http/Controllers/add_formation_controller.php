<?php

namespace App\Http\Controllers;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\AddFormationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\campo;
use App\formacion;
use App\permeabilidad_relativa_gl;
use App\permeabilidad_relativa_wo;
use App\coordenada_formacion;
use App\pvt;
use App\catalogoIntervaloProductor;

class add_formation_controller extends Controller
{
    /**
     * Despliega la vista add_formation con la información de campos para popular el select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $data['campo'] = campo::orderBy('nombre')->get();
                return view('formaciones.add_formation', $data);
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
    public function store(AddFormationRequest $request)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'fieldFormation'=> 'required',
                    'nameFormation'=> 'required:formaciones,nombre',
                    'topFormation'=> 'numeric|min:0',
                    'porosityFormation'=> 'numeric|between:0,100',
                    'permeabilityFormation'=> 'numeric|min:0',
                    'reservoirPressureFormation'=> 'numeric|min:0',
                 ]);

                if ($validator->fails()) {
                    return redirect('AddFormationC')
                        ->withErrors($validator)
                        ->withInput();
                }else{
                    //dd($request->all());
                    //Guardar datos generales de la formacion
                    $formation=new formacion;
                    $formation->nombre = $request->input('nameFormation');
                    $formation->campo_id = $request->input('fieldFormation');
                    $formation->top = $request->input('topFormation');
                    $formation->porosidad = $request->input('porosityFormation');
                    $formation->permeabilidad = $request->input('permeabilityFormation');
                    $formation->presion_reservorio = $request->input('reservoirPressureFormation');
                    $formation->save();
                    
                    //Guardar datos de tabla producing intervals                  
                    $table = $request->input("producing_intervals");
                    
                    $table = json_decode($table);
                    
                    foreach ($table as $value) {
                        if($value[0]){
                            $producing_interval = new catalogoIntervaloProductor;
                            $producing_interval->nombre = $value[0];
                            $producing_interval->formacion_id = $formation->id;
                            $producing_interval->save();
                        }
                    }

                    //Guardar datos de tabla permeabilidad relativa para w-o
                    $relative_permeability_wo=new permeabilidad_relativa_wo;
                    
                    $table = str_replace(",[null,null,null,null]","",$request->input("RelP"));
                    
                    $Sw=array();
                    $Krw=array();
                    $Kro=array();
                    $Pcwo=array();
                    $table = json_decode($table);
                    
                    foreach ($table as $value) {
                        if($value[0]){
                            $relative_permeability_wo = new permeabilidad_relativa_wo;
                            $relative_permeability_wo->sw = str_replace(",", ".", $value[0]);
                            $Sw[] = (float)str_replace(",", ".", $value[0]);

                            $relative_permeability_wo->krw = str_replace(",",".",$value[1]);
                            $Krw[] = (float)str_replace(",", ".", $value[1]);

                            $relative_permeability_wo->kro = str_replace(",",".",$value[2]);
                            $Kro[] = (float)str_replace(",", ".", $value[2]);

                            $relative_permeability_wo->pcwo = str_replace(",",".",$value[3]);
                            $Pcwo[] = (float)str_replace(",", ".", $value[3]);

                            $relative_permeability_wo->formacion_id=$formation->id;
                            $relative_permeability_wo->save();
                        }
                    }
                    


                    //Guardar datos de tabla permeabilidad relativa para g-l
                    $relative_permeability_gl=new permeabilidad_relativa_gl;
                    
                    $table = str_replace(",[null,null,null,null]","",$request->input("RelP2"));
                    $Sg=array();
                    $Krg=array();
                    $Krl=array();
                    $Pcgl=array();
                    $table = json_decode($table);

                    foreach ($table as $value) {
                        if($value[0]){
                            $relative_permeability_gl = new permeabilidad_relativa_gl;
                            $relative_permeability_gl->sg = str_replace(",", ".", $value[0]);
                            $Sg[] = (float)str_replace(",", ".", $value[0]);

                            $relative_permeability_gl->krg = str_replace(",",".",$value[1]);
                            $Krg[] = (float)str_replace(",", ".", $value[1]);

                            $relative_permeability_gl->krl = str_replace(",",".",$value[2]);
                            $Krl[] = (float)str_replace(",", ".", $value[2]);

                            $relative_permeability_gl->pcgl = str_replace(",",".",$value[3]);
                            $Pcgl[] = (float)str_replace(",", ".", $value[3]);

                            $relative_permeability_gl->formacion_id=$formation->id;
                            $relative_permeability_gl->save();
                        }
                    }


                    //Guardar datos de tabla coordenadas para formacion
                    $formation_coordinates=new coordenada_formacion;

                    $table = str_replace(",[null,null]","",$request->input("CoordF"));
                    $latitude=array();
                    $longitude=array();
                    $order=array();
                    $table = json_decode($table);

                    foreach ($table as $value) {
                        if($value[0]){
                            $formation_coordinates = new coordenada_formacion;
                            $formation_coordinates->lat = str_replace(",", ".", $value[0]);
                            $latitude[] = (float)str_replace(",", ".", $value[0]);
                            $formation_coordinates->lon = str_replace(",",".",$value[1]);
                            $longitude[] = (float)str_replace(",", ".", $value[1]);
                            $formation_coordinates->orden = null;
                            $order[] = null;
                            $formation_coordinates->formacion_id=$formation->id;
                            $formation_coordinates->campo_id=$formation->campo_id;
                            $formation_coordinates->depth=$request->input('prof');
                            $formation_coordinates->save();
                        }
                    }


                    //Guardar tabla de pvt de campos
                    $request->formacion_id = $formation->id;
                    Pvt::store($request);

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
