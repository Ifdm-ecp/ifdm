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
use App\medicion;
use App\pozo;
use App\variable_dano_medicion;
use App\datos_produccion;
use App\permeabilidad_relativaxf_wo_peticion;
use App\permeabilidad_relativaxf_gl_peticion;
use App\permeabilidad_relativaxf_wo;
use App\permeabilidad_relativaxf_gl;
use App\configuracion_dano_medicion;
use App\datos_produccion_peticion;
use App\formacionxpozo;
use App\Http\Requests\DamageVariablesRequest;
use App\Http\Requests\WellCreateRequest;
use App\Http\Requests\IntervalCreateRequest;
use Carbon\Carbon;


class list_request_controller extends Controller
{
    /**
     * Despliega la vista list_request y envía la información de pozos, intervalos y variables de daño desde la base de datos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        if (\Auth::check()) {
            if(\Auth::User()->office==0){
                $damage = peticiones::where('tipo_peticion','=',"Damage variables")->get();
                $well = peticiones::where('tipo_peticion','=',"Well")->get();
                $interval = peticiones::where('tipo_peticion','=',"Interval")->get();
                return view('list_request', ['damage' => $damage,'well' => $well, 'interval' => $interval]);
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
     * Despliega la vista edit_request con base en el tipo de petición con la información desde la base de datos con un id específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office==0){
                $request = peticiones::find($id);
                if($request){

                    //Guardar peticiones de variables de daño, informacion general: medicion, variable de daño y configuracion daño
                    if($request->tipo_peticion == "Damage variables" ){
                        if($request->subparametro_id){
                            if(is_numeric($request->subparametro_id)){
                                $measurement=new medicion;
                                $measurement->valor = $request->valor;
                                $measurement->fecha = $request->fecha_monitoreo;
                                $measurement->comentario = $request->comentario;
                                $measurement->pozo_id = $request->pozo_id;
                                $measurement->subparametro_id = $request->subparametro_id;
                                $measurement->save();
                            }else{
                                $damage_variable_measurement=new variable_dano_medicion;
                                $damage_variable_measurement->pozo_id=$request->pozo_id;
                                $damage_variable_measurement->valor=$request->valor;
                                $damage_variable_measurement->fecha=$request->fecha_monitoreo;
                                $damage_variable_measurement->comentario=$request->comentario;
                                $damage_variable_measurement->vd_id=$request->damag_2->id;
                                $damage_variable_measurement->save();
                            }
                        }elseif($request->configuracion_daño){
                            $configuration_damage_measurement=new configuracion_dano_medicion;
                            $configuration_damage_measurement->pozo_id=$request->pozo_id;
                            $configuration_damage_measurement->valor=$request->valor;
                            $configuration_damage_measurement->fecha=$request->fecha_monitoreo;
                            $configuration_damage_measurement->comentario=$request->comentario;
                            $configuration_damage_measurement->cd_id=$request->damag_conf->id;
                            $configuration_damage_measurement->save();
                        }
                        
                        peticiones::destroy($id);
                        $damage = peticiones::where('tipo_peticion','=',"Damage variables")->get();
                        $well = peticiones::where('tipo_peticion','=',"Well")->get();
                        $interval = peticiones::where('tipo_peticion','=',"Interval")->get();
                        return view('list_request', ['damage' => $damage,'well' => $well, 'interval' => $interval]);
                    }


                    //Guardar peticiones de informacion general de pozo
                    if($request->tipo_peticion == "Well" ){
                        if($request->variable != "Production data" ){
                            $well=pozo::find($request->pozo_id);

                            if($request->variable == "BHP" ){
                                $well->bhp = $request->valor;
                            }

                            if($request->variable == "Well radius" ){
                                $well->radius = $request->valor;
                            }

                            if($request->variable == "Drainage radius" ){
                                $well->drainage_radius = $request->valor;
                            }

                            if($request->variable == "Latitude" ){
                                $well->lat = $request->valor;
                            }

                            if($request->variable == "Longitude" ){
                                $well->lon = $request->valor;
                            }

                            if($request->variable == "TVD" ){
                                $well->tdv = $request->valor;
                            }
                            $well->save(); 
                        }else{
                            $production_data_request=datos_produccion_peticion::where('peticion_id','=',$request->id)->get();
                            

                            if(count($production_data_request)!=0){
                                datos_produccion::where('pozo_id', $request->pozo_id)->delete();
                            
                                $array = json_decode( json_encode( $production_data_request ), true );

                                for ($i = 0; $i < count($array); $i++) {
                                    $production_data=new datos_produccion;
                                    $production_data->qg=$array[$i]['qg'];
                                    $production_data->qo=$array[$i]['qo'];
                                    $production_data->qw=$array[$i]['qw'];
                                    $production_data->cummulative_qg=$array[$i]['cummulative_qg'];
                                    $production_data->cummulative_qo=$array[$i]['cummulative_qo'];
                                    $production_data->cummulative_qw=$array[$i]['cummulative_qw'];
                                    $production_data->date=$array[$i]['date'];
                                    $production_data->pozo_id=$request->pozo_id;
                                    $production_data->save(); 
                                }
                                datos_produccion_peticion::where('peticion_id','=',$request->id)->delete();
                            }
                        }
                        

                        peticiones::destroy($id);
                        $damage = peticiones::where('tipo_peticion','=',"Damage variables")->get();
                        $well = peticiones::where('tipo_peticion','=',"Well")->get();
                        $interval = peticiones::where('tipo_peticion','=',"Interval")->get();
                        return view('list_request', ['damage' => $damage,'well' => $well, 'interval' => $interval]);
                    }


                    //Guardar peticiones de informacion general de intervalo productor
                    if($request->tipo_peticion == "Interval" ){
                        if($request->variable != "Relative permeability and capilar pressure" ){
                            $interval=formacionxpozo::find($request->intervalo_id);

                            if($request->variable == "Top" ){
                                $interval->top = $request->valor;
                            }

                            if($request->variable == "Net pay" ){
                                $interval->netpay = $request->valor;
                            }

                            if($request->variable == "Porosity" ){
                                $interval->porosidad = $request->valor;
                            }

                            if($request->variable == "Permeability" ){
                                $interval->permeabilidad = $request->valor;
                            }

                            if($request->variable == "Reservoir pressure" ){
                                $interval->presion_reservorio = $request->valor;
                            }

                            $interval->save(); 
                        }else{
                            $relative_permeability_wo_request=permeabilidad_relativaxf_wo_peticion::where('peticion_id','=',$request->id)->get();
                            
                            if(count($relative_permeability_wo_request)!=0){
                                permeabilidad_relativaxf_wo::where('formacionxpozo_id', $request->intervalo_id)->delete();

                                $array = json_decode( json_encode( $relative_permeability_wo_request ), true );

                                for ($i = 0; $i < count($array); $i++) {
                                    $relative_permeability_wo=new permeabilidad_relativaxf_wo;
                                    $relative_permeability_wo->sw=$array[$i]['sw'];
                                    $relative_permeability_wo->krw=$array[$i]['krw'];
                                    $relative_permeability_wo->kro=$array[$i]['kro'];
                                    $relative_permeability_wo->pcwo=$array[$i]['pcwo'];
                                    $relative_permeability_wo->formacionxpozo_id=$request->intervalo_id;
                                    $relative_permeability_wo->save(); 
                                }
                                permeabilidad_relativaxf_wo_peticion::where('peticion_id','=',$request->id)->delete();
                            }

                            $relative_permeability_gl_request=permeabilidad_relativaxf_gl_peticion::where('peticion_id','=',$request->id)->get();
                            
                            if(count($relative_permeability_gl_request)!=0){
                                permeabilidad_relativaxf_gl::where('formacionxpozo_id', $request->intervalo_id)->delete();

                                $array = json_decode( json_encode( $relative_permeability_gl_request ), true );

                                for ($i = 0; $i < count($array); $i++) {
                                    $relative_permeability_gl=new permeabilidad_relativaxf_gl;
                                    $relative_permeability_gl->sg=$array[$i]['sg'];
                                    $relative_permeability_gl->krg=$array[$i]['krg'];
                                    $relative_permeability_gl->krl=$array[$i]['krl'];
                                    $relative_permeability_gl->pcgl=$array[$i]['pcgl'];
                                    $relative_permeability_gl->formacionxpozo_id=$request->intervalo_id;
                                    $relative_permeability_gl->save(); 
                                }
                                permeabilidad_relativaxf_gl_peticion::where('peticion_id','=',$request->id)->delete();
                            }
                        }
                        

                        peticiones::destroy($id);
                        $damage = peticiones::where('tipo_peticion','=',"Damage variables")->get();
                        $well = peticiones::where('tipo_peticion','=',"Well")->get();
                        $interval = peticiones::where('tipo_peticion','=',"Interval")->get();
                        return view('list_request', ['damage' => $damage,'well' => $well, 'interval' => $interval]);
                    }
                }
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
        if (\Auth::check()) {
            if(\Auth::User()->office==0){
                peticiones::destroy($id);
                $damage = peticiones::where('tipo_peticion','=',"Damage variables")->get();
                $well = peticiones::where('tipo_peticion','=',"Well")->get();
                $interval = peticiones::where('tipo_peticion','=',"Interval")->get();
                return view('list_request', ['damage' => $damage,'well' => $well, 'interval' => $interval]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

}
