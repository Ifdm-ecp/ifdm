<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\formacion;
use App\campo;
use App\proyecto;
use DB;
use Validator;
use App\Http\Requests\updateRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\permeabilidad_relativa_wo;
use App\permeabilidad_relativa_gl;
use App\Http\Requests\AddFormationRequest;
use App\coordenada_formacion;
use App\catalogoIntervaloProductor;
use App\cuenca;
use App\pvt;

class list_formation_controller extends Controller
{
    /**
     * Despliega la vista list_formation con la información de formación y cuenca para los select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $formation = DB::table('formaciones')->select('id', 'nombre')->paginate(15);
                $cuenca = cuenca::select('id', 'nombre')->get();
                return view('formaciones.list_formation', ['formation' => $formation, 'cuenca' => $cuenca]);
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
    public function store(Request $request)
    {
        //
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
     * Despliega la visat edit_formation con la información de una formación específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { //dd($id);
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $campo = campo::All();
                $data = formacion::find($id);
                $intervalos = $data->intervalosProductores();
                
                $tabla = Pvt::edit($data->pvt);
                if($data->pvt != null)
                {
                    $data->pvt_table = $tabla = json_encode(Pvt::edit($data->pvt));
                    $data->saturation_pressure = $data->pvt->saturation_pressure;
                }
                
                //dd($data->pvt_table);
                
                return view('formaciones.edit_formation', ['data' => $data, 'campo' => $campo, 'intervalos' => $intervalos]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddFormationRequest $request, $id)
    {
        //dd($request->all());
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){

                    //Editar datos generales de formacion
                    $formation=formacion::find($id);

                    $formation->nombre = $request->input('nameFormation');
                    $formation->campo_id = $request->input('fieldFormation');
                    $formation->top = $request->input('topFormation');
                    $formation->porosidad = $request->input('porosityFormation');
                    $formation->permeabilidad = $request->input('permeabilityFormation');
                    $formation->presion_reservorio = $request->input('reservoirPressureFormation');
                    $formation->save();
                    
                    //Guardar datos de tabla producing intervals                  
                    $table = str_replace(",[null]","",$request->input("producing_intervals"));
                    $table = json_decode($table, true);
                    
                    catalogoIntervaloProductor::where('formacion_id', $formation->id)->delete();
                    
                    foreach ($table as $value) {
                        if(isset($value[0])){
                            
                            $producing_interval = new catalogoIntervaloProductor;
                            $producing_interval->nombre = $value[0];
                            $producing_interval->formacion_id = $formation->id;
                            $producing_interval->save();
                        }
                    }

                    //Editar table permeabilidad relativa w-o
                    $relative_permeability_wo=new permeabilidad_relativa_wo;
                    $table = str_replace(",[null,null,null,null]","",$request->input("RelP"));
                    $Sw=array();
                    $Krw=array();
                    $Kro=array();
                    $Pcwo=array();
                    $table = json_decode($table, true);
                    permeabilidad_relativa_wo::where('formacion_id', $formation->id)->delete();

                    foreach ($table as $value) {
                        if (isset($value[0])){
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
                        
                    }
                    


                    //Editar table permeabilidad relativa g-l
                    $relative_permeability_gl=new permeabilidad_relativa_gl;
                    
                    $table = str_replace(",[null,null,null,null]","",$request->input("RelP2"));
                    $Sg=array();
                    $Krg=array();
                    $Krl=array();
                    $Pcgl=array();
                    $table = json_decode($table, true);
                    permeabilidad_relativa_gl::where('formacion_id', $formation->id)->delete();

                    foreach ($table as $value) {
                        if (isset($value[0])){
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
                    }



                    //Editar tabla coordenadas apra formacion
                    $formation_coordinates=new coordenada_formacion;

                    $table = str_replace(",[null,null]","",$request->input("CoordF"));
                    $Lat=array();
                    $Lon=array();
                    $Ord=array();
                    $table = json_decode($table, true);
                    coordenada_formacion::where('campo_id','=', $formation->campo_id)->where('formacion_id','=', $formation->id)->delete();

                    foreach ($table as $value) {
                        if (isset($value[0])){
                            if($value[0]){
                                $formation_coordinates = new coordenada_formacion;
                                $formation_coordinates->lat = str_replace(",", ".", $value[0]);
                                $Lat[] = (float)str_replace(",", ".", $value[0]);
                                $formation_coordinates->lon = str_replace(",",".",$value[1]);
                                $Lon[] = (float)str_replace(",", ".", $value[1]);
                                $formation_coordinates->orden = null;
                                $Ord[] = null;
                                $formation_coordinates->formacion_id=$formation->id;
                                $formation_coordinates->campo_id=$formation->campo_id;
                                $formation_coordinates->depth=$request->input('prof');
                                $formation_coordinates->save();
                            }
                        }
                    }

                    if($request->pvt_table  != '[{}]')
                    {
                        Pvt::actualizarPvtGlobal($request, $formation);
                    }
                        
                
                    return redirect('listFormationC');
                
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
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
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                formacion::destroy($id);

                $formation = DB::table('formaciones')->paginate(15);

                $cuenca = cuenca::select('id', 'nombre')->get();
                return redirect('listFormationC');
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }

    }
}
