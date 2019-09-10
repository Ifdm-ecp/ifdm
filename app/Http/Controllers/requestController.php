<?php

namespace App\Http\Controllers;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\cuenca;
use App\mecanismos_dano;
use App\peticiones;
use App\permeabilidad_relativaxf_gl_peticion;
use App\permeabilidad_relativaxf_wo_peticion;
use App\datos_produccion_peticion;
use App\Http\Requests\DamageVariablesRequest;
use App\Http\Requests\WellCreateRequest;
use App\Http\Requests\IntervalCreateRequest;
use Carbon\Carbon;


class requestController extends Controller
{
    /**
     * Despliega la vista inicial request y carga datos en select box con cuencas y variables de daño.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        if (\Auth::check()) {
            if(\Auth::User()->office==2){
                $cuenca = cuenca::select('id', 'nombre');
                $mecan_dano = mecanismos_dano::select('id', 'nombre');
                return view('request', ['cuenca' => $cuenca, 'mecan_dano' => $mecan_dano]);
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
    public function storeDamageVariables(DamageVariablesRequest $request)
    {
        if (\Auth::check()) {

            if(\Auth::User()->office==2){

                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'basin' => 'required',
                    'field' => 'required',
                    'well' => 'required',
                    'mecan_dano' => 'required',
                    'damage_variables' => 'required_without:damage_configuration',
                    'damage_configuration' => 'required_without:damage_variables',
                    'value' => 'required|numeric',
                    'date' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect('requestC')
                                ->withErrors($validator)
                                ->withInput();
                }else{
                    //Guardar informacion general de peticiones para variables de daño
                    $request=new peticiones;
                    $request->pozo_id = $request->input('well');
                    $request->mecdan_id = $request->input('mecan_dano');
                    $request->subparametro_id = $request->input('damage_variables');
                    $request->configuracion_daño = $request->input('damage_configuration');
                    $request->valor = $request->input('value');
                    $request->fecha_monitoreo = $request->input('date');
                    $request->fecha_creacion = Carbon::now();
                    $request->comentario = $request->input('comment');
                    $request->usuario_id = \Auth::User()->id;
                    $request->tipo_peticion = "Damage variables";
                    $request->save();
                }
                    return redirect('requestC');
                }else{
                    return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }
        

    public function storeWell(WellCreateRequest $request)
    {
        if (\Auth::check()) {

            if(\Auth::User()->office==2){
                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'basin_well' => 'required',
                    'field_well' => 'required',
                    'well_well' => 'required',
                    'variable_name' => 'required',
                    'value_well' => 'required_if:variable_name,BHP,Well radius,Drainage radius,Latitude,Longitude,TVD|numeric',
                    'ProdD2' => 'required',
                    'date_well' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect('requestC')
                                ->withErrors($validator)
                                ->withInput();
                }else{
                    //Guardar informacion general de peticiones para pozo
                    $request=new peticiones;
                    $request->pozo_id = $request->input('well_well');
                    $request->variable = $request->input('variable_name');
                    $request->fecha_monitoreo = $request->input('date_well');
                    $request->fecha_creacion = Carbon::now();
                    $request->comentario = $request->input('comment_well');
                    $request->usuario_id = \Auth::User()->id;
                    $request->tipo_peticion = "Well";

                    //Guardar tabla datos de produccion
                    if($request->input('variable_name') == "Production data"){
                        $request->save();
                        $ProdD1 = $request->input('ProdD2');
                        $ProdD = explode(",", $ProdD1);

                        $Qg=array();
                        $Qo=array();
                        $Qw=array();
                        $cummulativeQg=array();
                        $cummulativeQo=array();
                        $cummulativeQw=array();
                        $date=array();

                        for ($i = 0; $i < count(array_filter($ProdD))/7; $i++) {  
                            $date[$i] = $ProdD[(7*$i)];
                            $Qo[$i] = $ProdD[(7*$i)+1];    
                            $cummulativeQo[$i] = $ProdD[(7*$i)+2];   
                            $Qg[$i] = $ProdD[(7*$i)+3];
                            $cummulativeQg[$i] = $ProdD[(7*$i)+4];
                            $Qw[$i] = $ProdD[(7*$i)+5];
                            $cummulativeQw[$i] = $ProdD[(7*$i)+6];
                        }

                        for ($i = 0; $i < count($date); $i++) {
                            $request_production_data=new datos_produccion_peticion;
                            $request_production_data->date=$date[$i];
                            $request_production_data->qo=$Qo[$i];
                            $request_production_data->cummulative_qo=$cummulativeQo[$i];
                            $request_production_data->qg=$Qg[$i];
                            $request_production_data->cummulative_qg=$cummulativeQg[$i];
                            $request_production_data->qw=$Qw[$i];
                            $request_production_data->cummulative_qw=$cummulativeQw[$i];
                            $request_production_data->peticion_id=$request->id;
                            $request_production_data->save(); 
                        }
                    }else{
                        $request->valor = $request->input('value_well');  
                        $request->save();
                    }
                }
                    return redirect('requestC');
                }else{
                    return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    public function storeIterval(IntervalCreateRequest $request)
    {
        if (\Auth::check()) {

            if(\Auth::User()->office==2){
                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'basin_interval' => 'required',
                    'field_interval' => 'required',
                    'well_interval' => 'required',
                    'formation_interval' => 'required',
                    'interval_interval' => 'required',
                    'variable_name' => 'required',
                    'value_interval' => 'required_if:Top,Net pay,Porosity,Permeability,Reservoir pressure|numeric',
                    'RelP' => 'required_without:value_interval',
                    'RelP2' => 'required_without:value_interval',
                    'date_interval' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect('requestC')
                                ->withErrors($validator)
                                ->withInput();
                }else{
                    //Guardar informacion general de peticiones para intervalos productores
                    $request=new peticiones;
                    $request->pozo_id = $request->input('well_interval');
                    $request->intervalo_id = $request->input('interval_interval');
                    $request->fecha_monitoreo = $request->input('date_interval');
                    $request->fecha_creacion = Carbon::now();
                    $request->comentario = $request->input('comment_interval');
                    $request->usuario_id = \Auth::User()->id;
                    $request->tipo_peticion = "Interval";
                    $request->variable = $request->input('variable_name');

                    //Guardar tablas permeabilidades relativas W-O y G-L
                    if($request->input('variable_name') == "Relative permeability and capilar pressure" ){

                        //Tabla W-O
                        $request->save();
                        $RelP1 = $request->input('RelP');
                        $RelP = explode(",", $RelP1);

                        $Sw=array();
                        $Krw=array();
                        $Kro=array();
                        $Pcwo=array();

                        for ($i = 0; $i < count(array_filter($RelP))/4; $i++) {      
                            $Sw[$i] = $RelP[(4*$i)];   
                            $Krw[$i] = $RelP[(4*$i)+1];
                            $Kro[$i] = $RelP[(4*$i)+2];
                            $Pcwo[$i] = $RelP[(4*$i)+3];
                        }

                        for ($i = 0; $i < count($Sw); $i++) {
                            $relative_permeability_wo_request=new permeabilidad_relativaxf_wo_peticion;
                            $relative_permeability_wo_request->sw=$Sw[$i];
                            $relative_permeability_wo_request->krw=$Krw[$i];
                            $relative_permeability_wo_request->kro=$Kro[$i];
                            $relative_permeability_wo_request->pcwo=$Pcwo[$i];
                            $relative_permeability_wo_request->peticion_id=$request->id;
                            $relative_permeability_wo_request->save(); 
                        }


                        //Tabla G-L
                        $RelP2 = $request->input('RelP2');
                        $RelP3 = explode(",", $RelP2);

                        $Sg=array();
                        $Krg=array();
                        $Krl=array();
                        $Pcgl=array();

                        for ($i = 0; $i < count(array_filter($RelP3))/4; $i++) {      
                            $Sg[$i] = $RelP3[(4*$i)];   
                            $Krg[$i] = $RelP3[(4*$i)+1];
                            $Krl[$i] = $RelP3[(4*$i)+2];
                            $Pcgl[$i] = $RelP3[(4*$i)+3];
                            
                        }

                        for ($i = 0; $i < count($Sg); $i++) {
                            $relative_permeability_gl_request=new permeabilidad_relativaxf_gl_peticion;
                            $relative_permeability_gl_request->sg=$Sg[$i];
                            $relative_permeability_gl_request->krg=$Krg[$i];
                            $relative_permeability_gl_request->krl=$Krl[$i];
                            $relative_permeability_gl_request->pcgl=$Pcgl[$i];
                            $relative_permeability_gl_request->peticion_id=$request->id;
                            $relative_permeability_gl_request->save(); 
                        }
                    }else{
                        $request->valor = $request->input('value_interval');
                        $request->save();
                    }
                }
                    return redirect('requestC');
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
