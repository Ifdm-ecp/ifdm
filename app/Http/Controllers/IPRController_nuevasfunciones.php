<?php

namespace App\Http\Controllers;
if(!isset($_SESSION)) {
     session_start();
}
use DB;
use Validator;
use Illuminate\Http\Request;
use App\ipr;

use App\Http\Requests;
use App\Http\Requests\IPRRequest;
use App\ipr_tabla;
use App\ipr_tabla_gasliquid;
use App\ipr_tabla_wateroil;
use App\ipr_resultado;
use App\ipr_resultado_skin_ideal;
use App\ipr_pvt_gas;
use App\ipr_gas_oil_kr_c_g;
use App\ipr_dropout_c_g;
use App\ipr_pvt_c_g;
use App\ipr_shrinkage_curve;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use App\formacionxpozo;
use App\escenario;
use View;

class IPRController extends Controller
{
    /**
     * Despliega la vista iprs y precarga información del pozo, intervalo productor y formación.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) 
        {
            if(! isset($_SESSION["IPR_DATA"]) || is_null($_SESSION["IPR_DATA"]))
            {
              $IPR = new ipr();
              $IPR->tasa_flujo = 0.000001;
              $IPR->presion_fondo = 0.000001;
              $IPR->fluido = 1;
              $IPR->modulo_permeabilidad = 0.1;
            }
            else
            {
              $IPR = $_SESSION["IPR_DATA"];
            }
            $pozo = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
            $formacion = DB::table('formacionxpozos')->where('nombre', $_SESSION['formation'])->first();
            $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
            $escenario = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
            $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
            $scenaryId = \Request::get('scenaryId');
            $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
            $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();
            $pvt = DB::table('pvt')->where('formacion_id',$formacion->formacion_id)->get();
            $advisor = $scenary_s->enable_advisor;
            //dd($pvt);
            return View::make('IPRS', compact(['user','pozo', 'formacion', 'fluido', 'campo', 'IPR','scenary_s', 'intervalo','pvt', 'advisor']));
        }
        else
        {
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
    public function store(IPRRequest $request)
    { 
        if (\Auth::check()) 
        {
            set_time_limit(360); 
            $pozo = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
            $formacion = DB::table('formacionxpozos')->where('nombre', $_SESSION['formation'])->first();
            $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();

            $presion_yacimiento = $request->input("presion_yacimiento");
            $radio_pozo = $request->input("radio_pozo");
            $saturacion_gas_crit = $request->input("saturacion_gas_crit");
            $limite_max= 1-($request->input("saturacion_aceite_irred_gas"))-($request->input("saturacion_agua_irred"));

            #Definición validator con base en el fluido
            #1: oil
            #2: gas
            #3: condensate gas
            #dd($request->input());
            if($request->input("fluido") == "1")
            {
                $validator = Validator::make($request->all(),
                    [
                        'fluido'=>'numeric|min:0',
                        'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$radio_pozo,
                        'presion_yacimiento' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'bsw' => 'required|numeric|min:0|max:1',
                        'tasa_flujo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'presion_fondo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0|max:'.$presion_yacimiento.'|not_in:'.$presion_yacimiento,
                        'presion_inicial' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$presion_yacimiento,
                        'permeabilidad_abs_ini' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'espesor_reservorio' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'modulo_permeabilidad' => 'required_if:permeabilidad,|numeric|min:0',
                        'permeabilidad' => 'required_if:modulo_permeabilidad,|numeric|min:0',
                        'porosidad' => 'required_if:modulo_permeabilidad,|numeric|between:0,0.5|not_in:0', #No puede ser igual a cero
                        'tipo_roca' => 'required_if:modulo_permeabilidad,|numeric|min:0',
                        'end_point_kr_aceite_gas'=>'required_if:flag_perm_oil,corey|numeric|between:0,1|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
                        'saturacion_gas_crit' => 'required_if:flag_perm_oil,corey|numeric|min:0|max:1|not_in:1', #mayor e igual a cero y menor que 1
                        'end_point_kr_gas' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                        'saturacion_aceite_irred_gas' => 'required_if:flag_perm_oil,corey|numeric|min:0|max:1|not_in:1',#mayor e igual a cero y menor que 1
                        'exponente_corey_aceite_gas'=>'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0',#Mayor o igual a 1
                        'exponente_corey_gas' => 'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0', 
                        'end_point_kr_petroleo' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                        'saturacion_agua_irred' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:1',#mayor e igual a cero y menor que 1
                        'end_point_kr_agua' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                        'saturacion_aceite_irred' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:1', #mayor e igual a cero y menor que 1
                        'exponente_corey_petroleo' => 'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0', 
                        'exponente_corey_agua' => 'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0', 
                        'presion_saturacion' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'viscosidad_agua' => 'required_if:flag_pvt_oil,cub_eq|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'campo_a1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                        'campo_b1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                        'campo_c1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                        'campo_d1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                        'campo_a2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                        'campo_b2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                        'campo_c2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                        'campo_d2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                    ]);

                $validator->setAttributeNames(array(
                    'fluido'=>'Fluid Type',
                    'radio_pozo' => 'Well Radius',
                    'radio_drenaje_yac' => 'Reservoir Drainage Radius',
                    'presion_yacimiento' => 'Current Reservoir Pressure',
                    'bsw' => 'BSW',
                    'tasa_flujo' => 'Oil Rate',
                    'presion_fondo' => 'BHP',
                    'presion_inicial' => 'Initial Reservoir Pressure',
                    'permeabilidad_abs_ini' => 'Absolute Permeability At Initial Reservoir Pressure',
                    'espesor_reservorio' => 'Net Pay',
                    'modulo_permeabilidad' => 'Permeability Module',
                    'permeabilidad' => 'Absolute Permeability',
                    'porosidad' => 'Porosity', 
                    'tipo_roca' => 'Rock Type',
                    'end_point_kr_aceite_gas'=>'Kro (Sgc)',
                    'saturacion_gas_crit' => 'Sgc', 
                    'end_point_kr_gas' => 'Krg (Sorg)', 
                    'saturacion_aceite_irred_gas' => 'Sorg',
                    'exponente_corey_aceite_gas'=>'Corey Exponent Oil/Gas',
                    'exponente_corey_gas' => 'Corey Exponent Gas', 
                    'end_point_kr_petroleo' => 'Kro (Swi)', 
                    'saturacion_agua_irred' => 'Swi',
                    'end_point_kr_agua' => 'Krw (Sor)', 
                    'saturacion_aceite_irred' => 'Sor', 
                    'exponente_corey_petroleo' => 'Corey Exponent Oil', 
                    'exponente_corey_agua' => 'Corey Exponent Water', 
                    'presion_saturacion' => 'Saturation Pressure',
                    'viscosidad_agua' => 'Water Viscosity',
                    'campo_a1'=> 'A Coeficient in Uo',
                    'campo_b1'=> 'B Coeficient in Uo',
                    'campo_c1'=> 'C Coeficient in Uo',
                    'campo_d1'=> 'D Coeficient in Uo',
                    'campo_a2'=> 'A Coeficient in Bo',
                    'campo_b2'=> 'B Coeficient in Bo',
                    'campo_c2'=> 'C Coeficient in Bo',
                    'campo_d2'=> 'D Coeficient in Bo',
                    'flag_pvt_oil'=>'Check pvt data selection',
                    ));
            }
            else if($request->input("fluido") == "2")
            {
                //dd($request);
                $presion_yacimiento = $request->input("presion_yacimiento");
                $validator = Validator::make($request->all(),
                    [
                        'fluido'=>'numeric|min:0',
                        'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$radio_pozo,
                        'presion_yacimiento' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'gas_rate_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'bhp_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0|max:'.$presion_yacimiento.'|not_in:'.$presion_yacimiento,
                        'init_res_press_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$presion_yacimiento,
                        'abs_perm_init_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'net_pay_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'permeability_module_text_g' => 'required_if:abs_perm_text_g,|numeric|min:0',
                        'abs_perm_text_g' => 'required_if:permeability_module_text_g,|numeric|min:0',
                        'porosity_text_g' => 'required_if:permeability_module_text_g,|numeric|min:0|max:0.5|not_in:0', #No puede ser igual a cero
                        'rock_type' => 'required_if:permeability_module_text_g,|numeric|min:0',
                        'temperature_text_g'=>'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                        'c1_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                        'c2_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                        'c3_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                        'c4_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                        'c1_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                        'c2_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                        'c3_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                        'c4_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric',
                    ]);

                $validator->setAttributeNames(array(
                    'fluido'=>'Fluid Type',
                    'radio_pozo' => 'Well Radius',
                    'radio_drenaje_yac' => 'Reservoir Drainage Radius',
                    'presion_yacimiento' => 'Current Reservoir Pressure',
                    'gas_rate_g' => 'Gas Rate',
                    'bhp_g' => 'BHP',
                    'init_res_press_text_g' => 'Initial Reservoir Pressure',
                    'abs_perm_init_text_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                    'net_pay_text_g' => 'Net pay',
                    'permeability_module_text_g' => 'Permeability Module',
                    'abs_perm_text_g' => 'Absolute Permeability',
                    'porosity_text_g' => 'Porosity', #No puede ser igual a cero
                    'rock_type' => 'Rock Type',
                    'temperature_text_g'=>'Temperature',
                    'c1_viscosity_fluid_g'=>'A Coeficient in Ug',
                    'c2_viscosity_fluid_g'=>'BCoeficient in Ug',
                    'c3_viscosity_fluid_g'=>'C Coeficient in Ug',
                    'c4_viscosity_fluid_g'=>'D Coeficient in Ug',
                    'c1_compressibility_fluid_g'=>'A Coeficient in Bo',
                    'c2_compressibility_fluid_g'=>'B Coeficient in Bo',
                    'c3_compressibility_fluid_g'=>'C Coeficient in Bo',
                    'c4_compressibility_fluid_g'=>'D Coeficient in Bo',
                    'flag_pvt_gas'=>'Check pvt data selection',
                    ));
            }
            else if($request->input("fluido") == "3") 
            {
                $validator = Validator::make($request->all(),
                    [
                        'fluido' => 'numeric|min:0',
                        'radio_pozo' => 'required|numeric|min:0',
                        'radio_drenaje_yac' => 'required|numeric|min:'.$radio_pozo,
                        'presion_yacimiento' => 'required|numeric|min:0',
                        'gas_rate_c_g' => 'required|numeric',
                        'bhp_c_g' => 'required|numeric',
                        'presion_inicial_c_g' => 'required|numeric',
                        'permeabilidad_abs_ini_c_g' => 'required|numeric',
                        'espesor_reservorio_c_g' => 'required|numeric',
                        'modulo_permeabilidad_c_g' => 'required_without:permeabilidad_c_g|numeric|min:0',
                        'permeabilidad_c_g' => 'required_if:modulo_permeabilidad_c_g,|numeric',
                        'porosidad_c_g' => 'required_if:modulo_permeabilidad_c_g,|numeric',
                        'tipo_roca_c_g' => 'required_if:modulo_permeabilidad_c_g,|numeric|min:0',
                        'presion_saturacion_c_g' => 'required|numeric',
                        'gor_c_g' => 'required|numeric',
                    ]);

                $validator->setAttributeNames(array(
                    'fluido' => 'Fluid Type',
                    'radio_pozo' => 'Well Radius',
                    'radio_drenaje_yac' => 'Reservoir Drainage Radius',
                    'presion_yacimiento' => 'Current Reservoir Pressure',
                    'gas_rate_c_g' => 'Gas Rate',
                    'bhp_c_g' => 'BHP',
                    'presion_inicial_c_g' => 'Initial Reservoir Pressure',
                    'permeabilidad_abs_ini_c_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                    'espesor_reservorio_c_g' => 'Net Pay',
                    'modulo_permeabilidad_c_g' => 'Permeability Module',
                    'permeabilidad_c_g' => 'Absolute Permeability',
                    'porosidad_c_g' => 'Porosity',
                    'tipo_roca_c_g' => 'Rock Type',
                    'presion_saturacion_c_g' => 'Saturation Pressure',
                    'gor_c_g' => 'Gor',
                    ));
            }

            /* Tratamiento de datos para posterior conexión con módulo de python; captura de datos; inserción de registros; generación de input y captura de resultados. */
            if($request->input("accion") == "Run IPR")
            {
                #dd($request);
                if ($validator->fails()) 
                {
                    return redirect('IPR')
                        ->withErrors($validator)
                        ->withInput();
                }
                else
                {
                    $_SESSION["IPR_DATA"] = null;
                    $IPR = new ipr();
                    #Inserción de datos
                    if($request->input("fluido") == "1")
                    {
                        $IPR->fluido = $request->input("fluido");
                        $IPR->radio_pozo = $request->input("radio_pozo");
                        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                        $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                        $IPR->factor_dano = null;
                        $IPR->bsw = $request->input("bsw");
                        $IPR->gor = null;
                        $IPR->tasa_flujo = $request->input("tasa_flujo");
                        $IPR->presion_fondo = $request->input("presion_fondo");
                        $IPR->presion_inicial = $request->input("presion_inicial");
                        $IPR->permeabilidad_abs_ini = $request->input("permeabilidad_abs_ini");           
                        $IPR->espesor_reservorio = $request->input("espesor_reservorio"); 
                        $IPR->modulo_permeabilidad = $request->input("modulo_permeabilidad");
                        $IPR->permeabilidad = $request->input("permeabilidad");
                        $IPR->porosidad = $request->input("porosidad");
                        $IPR->tipo_roca = $request->input("tipo_roca");

                        $IPR->end_point_kr_aceite_gas = $request->input("end_point_kr_aceite_gas");
                        $IPR->saturacion_gas_crit = $request->input("saturacion_gas_crit");
                        $IPR->end_point_kr_gas = $request->input("end_point_kr_gas");
                        $IPR->saturacion_aceite_irred_gas = $request->input("saturacion_aceite_irred_gas");
                        $IPR->saturacion_aceite_irred_gas = $request->input("saturacion_aceite_irred_gas");
                        $IPR->exponente_corey_aceite_gas = $request->input("exponente_corey_aceite_gas");
                        $IPR->exponente_corey_gas = $request->input("exponente_corey_gas");
                        $IPR->end_point_kr_petroleo = $request->input("end_point_kr_petroleo");
                        $IPR->saturacion_agua_irred = $request->input("saturacion_agua_irred");
                        $IPR->end_point_kr_agua = $request->input("end_point_kr_agua");
                        $IPR->saturacion_aceite_irred = $request->input("saturacion_aceite_irred");
                        $IPR->exponente_corey_petroleo = $request->input("exponente_corey_petroleo");
                        $IPR->exponente_corey_agua = $request->input("exponente_corey_agua");
                        $IPR->viscosidad_agua = $request->input("viscosidad_agua");
                        $IPR->saturation_pressure = $request->input("presion_saturacion");
                        $IPR->campo_a1 = $request->input("campo_a1");
                        $IPR->campo_b1 = $request->input("campo_b1");
                        $IPR->campo_c1 = $request->input("campo_c1");
                        $IPR->campo_d1 = $request->input("campo_d1");
                        $IPR->campo_a2 = $request->input("campo_a2");
                        $IPR->campo_b2 = $request->input("campo_b2");
                        $IPR->campo_c2 = $request->input("campo_c2");
                        $IPR->campo_d2 = $request->input("campo_d2");

                        $IPR->skin_g= null;
                        $IPR->gas_rate_g= null;
                        $IPR->bhp_g= null;
                        $IPR->init_res_press_text_g= null;
                        $IPR->abs_perm_init_text_g= null;
                        $IPR->net_pay_text_g= null;
                        $IPR->permeability_module_text_g= null;
                        $IPR->abs_perm_text_g= null;
                        $IPR->porosity_text_g= null;
                        $IPR->rock_type= null;
                        $IPR->temperature_text_g= null;
                        $IPR->c1_viscosity_fluid_g= null;
                        $IPR->c2_viscosity_fluid_g= null;
                        $IPR->c3_viscosity_fluid_g= null;
                        $IPR->c4_viscosity_fluid_g= null;
                        $IPR->c1_compressibility_fluid_g= null;
                        $IPR->c2_compressibility_fluid_g= null;
                        $IPR->c3_compressibility_fluid_g= null;
                        $IPR->c4_compressibility_fluid_g= null;

                        $IPR->id_escenario=$_SESSION['scenary_id'];
                        $attributtes = array();
                        
                        $tmp_attr = $IPR->toArray();
                        foreach($tmp_attr as $k => $v)
                        {
                            if($v == '')
                            {
                                $v = null;
                            }
                            $attributtes[$k] = $v;
                        }
                        foreach($attributtes as $k => $v)
                        {
                            $IPR->$k = $v;
                        }

                        $IPR->save();
                    }
                    else if($request->input("fluido") == "2")
                    {
                        $IPR->fluido = $request->input("fluido");
                        $IPR->radio_pozo = $request->input("radio_pozo");
                        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                        $IPR->presion_yacimiento = $request->input("presion_yacimiento");

                        $IPR->factor_dano = null;
                        $IPR->bsw = null;
                        $IPR->gor = null;
                        $IPR->tasa_flujo = null;
                        $IPR->presion_fondo = null;
                        $IPR->presion_inicial = null;
                        $IPR->permeabilidad_abs_ini = null;           
                        $IPR->espesor_reservorio = null; 
                        $IPR->modulo_permeabilidad = null;
                        $IPR->permeabilidad = null;
                        $IPR->porosidad = null;
                        $IPR->tipo_roca = null;
                        $IPR->end_point_kr_aceite_gas = null;
                        $IPR->saturacion_gas_crit = null;
                        $IPR->end_point_kr_gas = null;
                        $IPR->saturacion_aceite_irred_gas = null;
                        $IPR->saturacion_aceite_irred_gas = null;
                        $IPR->exponente_corey_aceite_gas = null;
                        $IPR->exponente_corey_gas = null;
                        $IPR->end_point_kr_petroleo = null;
                        $IPR->saturacion_agua_irred = null;
                        $IPR->end_point_kr_agua = null;
                        $IPR->saturacion_aceite_irred = null;
                        $IPR->exponente_corey_petroleo = null;
                        $IPR->exponente_corey_agua = null;
                        $IPR->viscosidad_agua = null;
                        $IPR->campo_a1 = null;
                        $IPR->campo_b1 = null;
                        $IPR->campo_c1 = null;
                        $IPR->campo_d1 = null;
                        $IPR->campo_a2 = null;
                        $IPR->campo_b2 = null;
                        $IPR->campo_c2 = null;
                        $IPR->campo_d2 = null;

                        $IPR->skin_g= null;
                        $IPR->gas_rate_g= $request->input("gas_rate_g");
                        $IPR->bhp_g= $request->input("bhp_g");
                        $IPR->init_res_press_text_g= $request->input("init_res_press_text_g");
                        $IPR->abs_perm_init_text_g= $request->input("abs_perm_init_text_g");
                        $IPR->net_pay_text_g= $request->input("net_pay_text_g");
                        $IPR->permeability_module_text_g= $request->input("permeability_module_text_g");
                        $IPR->abs_perm_text_g= $request->input("abs_perm_text_g");
                        $IPR->porosity_text_g= $request->input("porosity_text_g");
                        $IPR->rock_type= $request->input("rock_type");
                        $IPR->temperature_text_g= $request->input("temperature_text_g");
                        $IPR->c1_viscosity_fluid_g= $request->input("c1_viscosity_fluid_g");
                        $IPR->c2_viscosity_fluid_g= $request->input("c2_viscosity_fluid_g");
                        $IPR->c3_viscosity_fluid_g= $request->input("c3_viscosity_fluid_g");
                        $IPR->c4_viscosity_fluid_g= $request->input("c4_viscosity_fluid_g");
                        $IPR->c1_compressibility_fluid_g= $request->input("c1_compressibility_fluid_g");
                        $IPR->c2_compressibility_fluid_g= $request->input("c2_compressibility_fluid_g");
                        $IPR->c3_compressibility_fluid_g= $request->input("c3_compressibility_fluid_g");
                        $IPR->c4_compressibility_fluid_g= $request->input("c4_compressibility_fluid_g");

                        $IPR->id_escenario=$_SESSION['scenary_id'];
                        $attributtes = array();
                                
                        $tmp_attr = $IPR->toArray();
                        foreach($tmp_attr as $k => $v)
                        {
                            if($v == '')
                            {
                                $v = null;
                            }
                            $attributtes[$k] = $v;
                        }
                        foreach($attributtes as $k => $v)
                        {
                            $IPR->$k = $v;
                        }

                        $IPR->save();
                    }
                    else if($request->input("fluido") == "3")
                    {
                        $IPR->fluido = $request->input("fluido");
                        $IPR->radio_pozo = $request->input("radio_pozo");
                        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                        $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                        $IPR->gas_rate_c_g = $request->input("gas_rate_c_g");
                        $IPR->bhp_c_g = $request->input("bhp_c_g");
                        $IPR->initial_pressure_c_g = $request->input("presion_inicial_c_g");
                        $IPR->ini_abs_permeability_c_g = $request->input("permeabilidad_abs_ini_c_g");
                        $IPR->netpay_c_g = $request->input("espesor_reservorio_c_g");
                        $IPR->permeability_module_c_g = $request->input("modulo_permeabilidad_c_g");
                        $IPR->permeability_c_g = $request->input("permeabilidad_c_g");
                        $IPR->porosity_c_g = $request->input("porosidad_c_g");
                        $IPR->rock_type_c_g = $request->input("tipo_roca_c_g");
                        $IPR->saturation_pressure_c_g = $request->input("presion_saturacion_c_g");
                        $IPR->gor_c_g = $request->input("gor_c_g");
                        $IPR->id_escenario=$_SESSION['scenary_id'];

                        $attributtes = array();
                                
                        $tmp_attr = $IPR->toArray();
                        foreach($tmp_attr as $k => $v)
                        {
                            if($v == '')
                            {
                                $v = null;
                            }
                            $attributtes[$k] = $v;
                        }
                        foreach($attributtes as $k => $v)
                        {
                            $IPR->$k = $v;
                        }

                        $IPR->save();
                    }

                        $_SESSION['ipr'] = $IPR->id;
                        $_SESSION['for_id'] = $IPR->formation_id;
                        $_SESSION['fie_id'] = $IPR->field_id;
                        $_SESSION['we_id'] = $IPR->well_id;

                        $scenaryId = DB::table('ipr')->where('id_escenario', $_SESSION['scenary_id'])->pluck('id_escenario');
                        $scenary = escenario::find($scenaryId);
                        $scenary->estado=1;
                        $scenary->completo=1;
                        $scenary->save();


                        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
                        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
                        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
                        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
                        $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');

                        #Generación de set de datos
                        if($request->input("fluido")=="1")
                        {
                            //Pvt - ipr_tabla
                            $IPR_TABLA = new ipr_tabla;
                            $tabla = str_replace(",[null,null,null,null]","",$request->input("presiones_table"));
                            $presionesv = array();
                            $viscosidades_aceite= array();
                            $factores_vol_aceite = array();
                            $viscosidades_agua= array();
                            $tabla = json_decode($tabla);
                            foreach ($tabla as $value) 
                            {
                                $IPR_TABLA = new ipr_tabla;
                                $IPR_TABLA->presion = str_replace(",", ".", $value[0]);
                                $presionesv[] = (int)str_replace(",", ".", $value[0]);
                                $IPR_TABLA->viscosidad_aceite = str_replace(",",".",$value[1]);
                                $viscosidades_aceite[] = (float)str_replace(",", ".", $value[1]);
                                $IPR_TABLA->factor_volumetrico_aceite = str_replace(",",".",$value[2]);
                                $factores_vol_aceite[] = (float)str_replace(",", ".", $value[2]);
                                $IPR_TABLA->viscosidad_agua = str_replace(",",".",$value[3]);
                                $viscosidades_agua[] = (float)str_replace(",", ".", $value[3]);
                                $IPR_TABLA->id_ipr=$IPR->id;
                                $IPR_TABLA->save();
                            }

                            $presiones = json_encode($tabla);
                                
                            if ($this->allZeroes($presionesv)) {
                                $presionesv = [0];
                            }

                            if ($this->allZeroes($viscosidades_aceite)) {
                                $viscosidades_aceite = [0];
                            }

                            if ($this->allZeroes($factores_vol_aceite)) {
                                $factores_vol_aceite = [0];
                            }

                            if ($this->allZeroes($viscosidades_agua)) {
                                $viscosidades_agua = [0];
                            }

                            //Water-oil - ipr_tabla_water
                            $i_tables = 0;
                            
                            $tabla_wateroil = json_decode(str_replace(",[null,null,null]","",$request->input("wateroil_hidden")));
                            
                            $lista_sw = array();
                            $lista_krw = array();
                            $lista_kro = array();
                            foreach ($tabla_wateroil as $value) {
                                $i_tables++;
                                $IPR_TABLA_WATEROIL = new ipr_tabla_wateroil;
                                $IPR_TABLA_WATEROIL->lista_sw = str_replace(",", ".", $value[0]);
                                $lista_sw[] = (float)$IPR_TABLA_WATEROIL->lista_sw;
                                $IPR_TABLA_WATEROIL->lista_krw = str_replace(",", ".", $value[1]);
                                $lista_krw[] = (float)$IPR_TABLA_WATEROIL->lista_krw;
                                $IPR_TABLA_WATEROIL->lista_kro = str_replace(",", ".", $value[2]);
                                $lista_kro[] = (float)$IPR_TABLA_WATEROIL->lista_kro;
                                $IPR_TABLA_WATEROIL->id_ipr=$IPR->id;
                                $IPR_TABLA_WATEROIL->save();
                            }
                                
                            if ($this->allZeroes($lista_sw)) {
                                $i_tables = 0;
                                $lista_sw = [0];
                            }

                            if ($this->allZeroes($lista_krw)) {
                                $i_tables = 0;
                                $lista_krw = [0];
                            }

                            if ($this->allZeroes($lista_kro)) {
                                $i_tables = 0;
                                $lista_kro = [0];
                            }
                                
                            $lista_kro = json_encode($lista_kro);
                            $lista_krw = json_encode($lista_krw);
                            $lista_sw = json_encode($lista_sw);

                            //Gas-Oil - ipr_tabla_gas

                            $tabla_gasliquid = json_decode(str_replace(",[null,null,null]","",$request->input("gasliquid_hidden")));
                            $lista_sg = array();
                            $lista_krg = array();
                            $lista_krosg = array();
                            foreach ($tabla_gasliquid as $value) {
                                $i_tables++;
                                $IPR_TABLA_GASLIQUID = new ipr_tabla_gasliquid;
                                $IPR_TABLA_GASLIQUID->lista_sg = str_replace(",", ".", $value[0]);
                                $lista_sg[] = (float)$IPR_TABLA_GASLIQUID->lista_sg;
                                $IPR_TABLA_GASLIQUID->lista_krg = str_replace(",", ".", $value[1]);
                                $lista_krg[] = (float)$IPR_TABLA_GASLIQUID->lista_krg;
                                $IPR_TABLA_GASLIQUID->lista_krosg = str_replace(",", ".", $value[2]);
                                $lista_krosg[] = (float)$IPR_TABLA_GASLIQUID->lista_krosg;
                                $IPR_TABLA_GASLIQUID->id_ipr=$IPR->id;
                                $IPR_TABLA_GASLIQUID->save();
                            }
                                
                            if ($this->allZeroes($lista_sg)) {
                                $i_tables = 0;
                                $lista_sg = [0];
                            }

                            if ($this->allZeroes($lista_krg)) {
                                $i_tables = 0;
                                $lista_krg = [0];
                            }

                            if ($this->allZeroes($lista_krosg)) {
                                $i_tables = 0;
                                $lista_krosg = [0];
                            }
                            $lista_krosg = json_encode($lista_krosg);
                            $lista_krg = json_encode($lista_krg);
                            $lista_sg = json_encode($lista_sg);


                            //Input Data
                            $input_data = 
                            [
                                "presiones" => json_encode($presionesv),
                                "viscosidades_aceite" => json_encode($viscosidades_aceite),
                                "factores_vol_aceite" => json_encode($factores_vol_aceite),
                                "viscosidades_agua" => json_encode($viscosidades_agua),
                                "fluido" => $IPR->fluido,
                                "radio_pozo" => $IPR->radio_pozo,
                                "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                                "presion_yacimiento" => $IPR->presion_yacimiento,
                                "factor_dano" => "-1",
                                "bsw" => $IPR->bsw,
                                #"gor" => $IPR->gor,
                                "tasa_flujo" => $IPR->tasa_flujo,
                                "presion_fondo" => $IPR->presion_fondo,
                                "presion_inicial" => $IPR->presion_inicial,
                                "permeabilidad_abs_ini" => $IPR->permeabilidad_abs_ini,
                                "espesor_reservorio" => $IPR->espesor_reservorio,
                                "modulo_permeabilidad" => $IPR->modulo_permeabilidad,
                                "permeabilidad" => $IPR->permeabilidad,
                                "porosidad" => $IPR->porosidad,
                                "tipo_roca" => $IPR->tipo_roca,
                                "end_point_kr_aceite_gas" => $IPR->end_point_kr_aceite_gas,
                                "saturacion_gas_crit" => $IPR->saturacion_gas_crit,
                                "end_point_kr_gas" => $IPR->end_point_kr_gas,
                                "saturacion_aceite_irred_gas" => $IPR->saturacion_aceite_irred_gas,
                                "exponente_corey_aceite_gas" => $IPR->exponente_corey_aceite_gas, 
                                "exponente_corey_gas" => $IPR->exponente_corey_gas,
                                "end_point_kr_petroleo" => $IPR->end_point_kr_petroleo,
                                "saturacion_agua_irred" => $IPR->saturacion_agua_irred,                
                                "end_point_kr_agua" => $IPR->end_point_kr_agua,
                                "saturacion_aceite_irred" => $IPR->saturacion_aceite_irred,
                                "exponente_corey_petroleo" => $IPR->exponente_corey_petroleo,
                                "exponente_corey_agua" => $IPR->exponente_corey_agua,
                                "viscosidad_agua" => $IPR->viscosidad_agua,
                                "saturation_pressure" => $IPR->saturation_pressure,
                                "campo_a1" => $IPR->campo_a1,
                                "campo_b1" => $IPR->campo_b1,
                                "campo_c1" => $IPR->campo_c1,
                                "campo_d1" => $IPR->campo_d1,
                                "campo_a2" => $IPR->campo_a2,
                                "campo_b2" => $IPR->campo_b2,
                                "campo_c2" => $IPR->campo_c2,
                                "campo_d2" => $IPR->campo_d2,
                                "lista_sg" => $lista_sg, #Gas-oil
                                "lista_krg" => $lista_krg,
                                "lista_krosg" => $lista_krosg,
                                "lista_sw" => $lista_sw, #Water-oil
                                "lista_krw" => $lista_krw,
                                "lista_kro" => $lista_kro,
                            ];

                        }
                        else if($request->input("fluido")=="2")
                        {

                            //PVT ipr Gas
                            $pvt_gas_ipr = json_decode(str_replace(",[null,null,null]","",$request->input("pvt_gas_ipr")));
                            $pvt_pressure_gas = array();
                            $pvt_gasViscosity_gas = array();
                            $pvt_gasCompressibility_gas = array();
                            foreach ($pvt_gas_ipr as $value) {
                                $ipr_pvt_gas = new ipr_pvt_gas;
                                $ipr_pvt_gas->pressure = str_replace(",", ".", $value[0]);
                                $pvt_pressure_gas[] = (float)$ipr_pvt_gas->pressure;

                                $ipr_pvt_gas->gas_viscosity = str_replace(",", ".", $value[1]);
                                $pvt_gasViscosity_gas[] = (float)$ipr_pvt_gas->gas_viscosity;

                                $ipr_pvt_gas->gas_compressibility_factor = str_replace(",", ".", $value[2]);
                                $pvt_gasCompressibility_gas[] = (float)$ipr_pvt_gas->gas_compressibility_factor;

                                $ipr_pvt_gas->id_ipr=$IPR->id;
                                $ipr_pvt_gas->save();
                            }
                            
                            if ($this->allZeroes($pvt_pressure_gas)) {
                                $pvt_pressure_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasViscosity_gas)) {
                                $pvt_gasViscosity_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasCompressibility_gas)) {
                                $pvt_gasCompressibility_gas = [0];
                            }
                            $pvt_pressure_gas = json_encode($pvt_pressure_gas);
                            $pvt_gasViscosity_gas = json_encode($pvt_gasViscosity_gas);
                            $pvt_gasCompressibility_gas = json_encode($pvt_gasCompressibility_gas);

                            $input_data = 
                            [
                                "fluido" => $IPR->fluido,
                                "radio_pozo" => $IPR->radio_pozo,
                                "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                                "presion_yacimiento" => $IPR->presion_yacimiento,
                                "factor_dano" => "-1",
                                "gas_rate_g" => $IPR->gas_rate_g,
                                "bhp_g" => $IPR->bhp_g,
                                "init_res_press_text_g" => $IPR->init_res_press_text_g,
                                "abs_perm_init_text_g" => $IPR->abs_perm_init_text_g,
                                "net_pay_text_g" => $IPR->net_pay_text_g,
                                "permeability_module_text_g" => $IPR->permeability_module_text_g,
                                "abs_perm_text_g" => $IPR->abs_perm_text_g,
                                "porosity_text_g" => $IPR->porosity_text_g,
                                "rock_type" => $IPR->rock_type,
                                "temperature_text_g" => $IPR->temperature_text_g,
                                "c1_viscosity_fluid_g" => $IPR->c1_viscosity_fluid_g,
                                "c2_viscosity_fluid_g" => $IPR->c2_viscosity_fluid_g,
                                "c3_viscosity_fluid_g" => $IPR->c3_viscosity_fluid_g,
                                "c4_viscosity_fluid_g" => $IPR->c4_viscosity_fluid_g,
                                "c1_compressibility_fluid_g" => $IPR->c1_compressibility_fluid_g,
                                "c2_compressibility_fluid_g" => $IPR->c2_compressibility_fluid_g, 
                                "c3_compressibility_fluid_g" => $IPR->c3_compressibility_fluid_g,
                                "c4_compressibility_fluid_g" => $IPR->c4_compressibility_fluid_g,
                                "pvt_pressure_gas" => $pvt_pressure_gas, #Pvt-Gas
                                "pvt_gasviscosity_gas" => $pvt_gasViscosity_gas,
                                "pvt_gascompressibility_gas" => $pvt_gasCompressibility_gas,
                            ];
                        }
                        else if($request->input("fluido") == "3")
                        {

                            //Kr gas oil - Condensate gas
                            $kr_c_g_table_data = str_replace(",[null,null,null]","",$request->input("gas_oil_kr_cg"));
                            $sg_data = array();
                            $krg_data = array();
                            $krog_data = array();
                            $kr_c_g_table_data = json_decode($kr_c_g_table_data);
                            foreach ($kr_c_g_table_data as $value) 
                            {
                                $gas_oil_kr_c_g = new ipr_gas_oil_kr_c_g; 
                                $gas_oil_kr_c_g->ipr_id = $IPR->id;
                                $gas_oil_kr_c_g->sg = str_replace(",", ".", $value[0]); 
                                $sg_data[] = (float)$gas_oil_kr_c_g->sg;

                                $gas_oil_kr_c_g->krg = str_replace(",", ".", $value[1]);
                                $krg_data[] = (float)$gas_oil_kr_c_g->krg; 

                                $gas_oil_kr_c_g->krog = str_replace(",", ".", $value[2]); 
                                $krog_data[] = (float)$gas_oil_kr_c_g->krog;

                                $gas_oil_kr_c_g->save();
                            }

                            $sg_data = json_encode($sg_data);
                            $krg_data = json_encode($krg_data);
                            $krog_data = json_encode($krog_data);

                            //PVT Condensate Gas
                            $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));
                            
                            $pressure_data = array();
                            $bo_data = array();
                            $vo_data = array();
                            $rs_data = array();
                            $bg_data = array();
                            $vg_data = array();
                            $og_ratio_data = array();
                            $pvt_c_g_table_data = json_decode($pvt_c_g_table_data);
                            foreach ($pvt_c_g_table_data as $value) 
                            {
                                $pvt_cg = new ipr_pvt_c_g;
                                $pvt_cg->ipr_id = $IPR->id;
                                $pvt_cg->pressure = str_replace(",", ".", $value[0]);
                                $pvt_cg->bo = str_replace(",", ".", $value[1]);
                                $pvt_cg->vo = str_replace(",", ".", $value[2]);
                                $pvt_cg->rs = str_replace(",", ".", $value[3]);
                                $pvt_cg->bg = str_replace(",", ".", $value[4]);
                                $pvt_cg->vg = str_replace(",", ".", $value[5]);
                                $pvt_cg->og_ratio = str_replace(",", ".", $value[6]);

                                $pressure_data[] = (float)$pvt_cg->pressure; 
                                $bo_data[] = (float)$pvt_cg->bo; 
                                $vo_data[] = (float)$pvt_cg->vo; 
                                $rs_data[] = (float)$pvt_cg->rs; 
                                $bg_data[] = (float)$pvt_cg->bg; 
                                $vg_data[] = (float)$pvt_cg->vg; 
                                $og_ratio_data[] = (float)$pvt_cg->og_ratio; 

                                $pvt_cg->save();
                            }

                            $pressure_data = json_encode($pressure_data);
                            $bo_data = json_encode($bo_data);
                            $vo_data = json_encode($vo_data);
                            $rs_data = json_encode($rs_data);
                            $bg_data = json_encode($bg_data);
                            $vg_data = json_encode($vg_data);
                            $og_ratio_data = json_encode($og_ratio_data);


                            //Drop out Condensate Gas
                            $dropout_c_g_table_data = str_replace(",[null,null]","",$request->input("dropout_cg"));

                            $dropout_pressure_data = array();
                            $dropout_liquid_percentage = array();
                            $dropout_c_g_table_data = json_decode($dropout_c_g_table_data);

                            foreach ($dropout_c_g_table_data as $value) 
                            {
                                $dropout_c_g = new ipr_dropout_c_g;
                                
                                $dropout_c_g->ipr_id = $IPR->id;

                                $dropout_c_g->pressure = str_replace(",",".", $value[0]);
                                $dropout_c_g->liquid_percentage = str_replace(",",".", $value[1]);

                                $dropout_pressure_data[] = $dropout_c_g->pressure;
                                $dropout_liquid_percentage[] = $dropout_c_g->liquid_percentage;

                                $dropout_c_g->save();
                            }

                            $dropout_pressure_data = json_encode($dropout_pressure_data);
                            $dropout_liquid_percentage = json_encode($dropout_liquid_percentage);

                            $input_data =
                            [
                                "fluido" => $IPR->fluido,
                                "well_radius" => $IPR->radio_pozo,
                                "drainage_radius" => $IPR->radio_drenaje_yac,
                                "reservoir_pressure" => $IPR->presion_yacimiento,
                                "gas_rate_c_g" => $IPR->gas_rate_c_g,
                                "bhp_c_g" => $IPR->bhp_c_g,
                                "initial_pressure_c_g" => $IPR->initial_pressure_c_g,
                                "ini_abs_permeability_c_g" => $IPR->ini_abs_permeability_c_g,
                                "netpay_c_g" => $IPR->netpay_c_g,
                                "permeability_module_c_g" => $IPR->permeability_module_c_g,
                                "permeability_c_g" => $IPR->permeability_c_g,
                                "porosity_c_g" => $IPR->porosity_c_g,
                                "rock_type_c_g" => $IPR->rock_type_c_g,
                                "saturation_pressure_c_g" => $IPR->saturation_pressure_c_g,
                                "gor_c_g" => $IPR->gor_c_g,
                                "sg_data" => $sg_data,
                                "krg_data" => $krg_data,
                                "krog_data" => $krog_data,
                                "pressure_data" => $pressure_data,
                                "bo_data" => $bo_data,
                                "vo_data" => $vo_data,
                                "rs_data" => $rs_data,
                                "bg_data" => $bg_data,
                                "vg_data" => $vg_data,
                                "og_ratio_data" => $og_ratio_data,
                                "dropout_pressure_data" => $dropout_pressure_data,
                                "dropout_liquid_percentage" => $dropout_liquid_percentage,
                                "factor_dano" => -1,
                            ];
                        }

                        #Cálculo módulo IPR
                        $ipr_function_results = json_decode($this->run_ipr($input_data));

                        #Captura de resultados y tratamiento de datos
                        
                        $ipr_resultados = array();
                        $categorias = array();
                        $eje_y = array();

                        $data = array();
                        #dd($ipr_function_results);
                        $tasa_flujo_resultados = $ipr_function_results[0];
                        $presion_fondo_resultados = $ipr_function_results[1];
                        $skin_resultados = $ipr_function_results[2];

                        for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) 
                        { 
                            $ipr_resultado = new ipr_resultado;
                            $ipr_resultado->skin = $skin_resultados;
                            $ipr_resultado->tasa_flujo = floatval($tasa_flujo_resultados[$i]);
                            $ipr_resultado->presion_fondo = floatval($presion_fondo_resultados[$i]);
                            $ipr_resultado->id_ipr = $IPR->id;
                            
                            $data[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3)];

                            $categorias[] = round($ipr_resultado->tasa_flujo,3);
                            $eje_y[] = round($ipr_resultado->presion_fondo,3);
                            
                            $ipr_resultados[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3), round($ipr_resultado->skin,3)];
                            
                            $ipr_resultado->save();   

                            $skin = floatval($skin_resultados);
                            $skin_tmp = floatval($skin_resultados);
                        }

                        $categorias = json_encode($categorias);
                        $eje_y = json_encode($eje_y);
                        $data = json_encode($data);

                        $request->session()->flash('mensaje', 'Record successfully entered.');
                        
                        if($request->input("fluido")=="1")
                        {
                            $tasa_flujo = $IPR->tasa_flujo;
                            $presion_fondo = $IPR->presion_fondo;
                            $tipo_roca = $IPR->tipo_roca;
                        }
                        else if($request->input("fluido")=="2")
                        {
                            $tasa_flujo = $IPR->gas_rate_g;
                            $presion_fondo = $IPR->bhp_g;
                            $tipo_roca = $IPR->rock_type;
                        }
                        else if($request->input("fluido")=="3")
                        {
                            $tasa_flujo = $IPR->gas_rate_c_g;
                            $presion_fondo = $IPR->bhp_c_g;
                            $tipo_roca = $IPR->rock_type_c_g;
                        }
                            
                        #Cálculo IPR Base, skin = 0

                        $input_data["factor_dano"] = 0;
                        $ipr_function_results = json_decode($this->run_ipr($input_data));

                        #Captura de resultados y tratamiento de datos
                        
                        $categorias_i = array();
                        $eje_yi = array();

                        $data_i = array();

                        $tasa_flujo_resultados = $ipr_function_results[0];
                        $presion_fondo_resultados = $ipr_function_results[1];
                        $skin_resultados = $ipr_function_results[2];

                        for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) 
                        { 
                            $ipr_resultado = new ipr_resultado_skin_ideal;
                            $ipr_resultado->skin = $skin_resultados;
                            $ipr_resultado->tasa_flujo = floatval($tasa_flujo_resultados[$i]);
                            $ipr_resultado->presion_fondo = floatval($presion_fondo_resultados[$i]);
                            $ipr_resultado->id_ipr = $IPR->id;
                            
                            $data_i[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3)];

                            $categorias_i[] = round($ipr_resultado->tasa_flujo,3);
                            $eje_yi[] = round($ipr_resultado->presion_fondo,3);
                            
                            $ipr_resultados[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3), $ipr_resultado->skin];
                            
                            $ipr_resultado->save();   
                        }

                        $categorias_i = json_encode($categorias_i);
                        $eje_yi = json_encode($eje_yi);
                        $data_i = json_encode($data_i);                  

                        $skin = $skin_tmp;

                        $escenario = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                        $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
                        $scenaryId = \Request::get('scenaryId');
                        $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                        $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
                        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();

                        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
                        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
                        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
                        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
                        $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');

                        return view('IPRResults', compact('user','campo', 'IPR', 'ipr_resultados' , 'pozo', 'formacion','fluido', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca', 'data_i', 'i_tables','scenary_s', 'intervalo'));
                }
            }
            else if($request->input("accion") == "Save")
            {
                        /* Tratamiento de datos para el botón save; almacenamiento temporal de datos */
                        $IPR = new ipr();
                        if($request->input("fluido") == "1")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                            $IPR->factor_dano = null;
                            $IPR->bsw = $request->input("bsw");
                            $IPR->gor = null;
                            $IPR->tasa_flujo = $request->input("tasa_flujo");
                            $IPR->presion_fondo = $request->input("presion_fondo");
                            $IPR->presion_inicial = $request->input("presion_inicial");
                            $IPR->permeabilidad_abs_ini = $request->input("permeabilidad_abs_ini");           
                            $IPR->espesor_reservorio = $request->input("espesor_reservorio"); 
                            $IPR->modulo_permeabilidad = $request->input("modulo_permeabilidad");
                            $IPR->permeabilidad = $request->input("permeabilidad");
                            $IPR->porosidad = $request->input("porosidad");
                            $IPR->tipo_roca = $request->input("tipo_roca");
                            $IPR->end_point_kr_aceite_gas = $request->input("end_point_kr_aceite_gas");
                            $IPR->saturacion_gas_crit = $request->input("saturacion_gas_crit");
                            $IPR->end_point_kr_gas = $request->input("end_point_kr_gas");
                            $IPR->saturacion_aceite_irred_gas = $request->input("saturacion_aceite_irred_gas");
                            $IPR->exponente_corey_aceite_gas = $request->input("exponente_corey_aceite_gas");
                            $IPR->exponente_corey_gas = $request->input("exponente_corey_gas");
                            $IPR->end_point_kr_petroleo = $request->input("end_point_kr_petroleo");
                            $IPR->saturacion_agua_irred = $request->input("saturacion_agua_irred");
                            $IPR->end_point_kr_agua = $request->input("end_point_kr_agua");
                            $IPR->saturacion_aceite_irred = $request->input("saturacion_aceite_irred");
                            $IPR->exponente_corey_petroleo = $request->input("exponente_corey_petroleo");
                            $IPR->exponente_corey_agua = $request->input("exponente_corey_agua");
                            $IPR->viscosidad_agua = $request->input("viscosidad_agua");
                            $IPR->saturation_pressure = $request->input("presion_saturacion");
                            $IPR->campo_a1 = $request->input("campo_a1");
                            $IPR->campo_b1 = $request->input("campo_b1");
                            $IPR->campo_c1 = $request->input("campo_c1");
                            $IPR->campo_d1 = $request->input("campo_d1");
                            $IPR->campo_a2 = $request->input("campo_a2");
                            $IPR->campo_b2 = $request->input("campo_b2");
                            $IPR->campo_c2 = $request->input("campo_c2");
                            $IPR->campo_d2 = $request->input("campo_d2");
                            $IPR->skin_g= null;
                            $IPR->gas_rate_g= null;
                            $IPR->bhp_g= null;
                            $IPR->init_res_press_text_g= null;
                            $IPR->abs_perm_init_text_g= null;
                            $IPR->net_pay_text_g= null;
                            $IPR->permeability_module_text_g= null;
                            $IPR->abs_perm_text_g= null;
                            $IPR->porosity_text_g= null;
                            $IPR->rock_type= null;
                            $IPR->temperature_text_g= null;
                            $IPR->c1_viscosity_fluid_g= null;
                            $IPR->c2_viscosity_fluid_g= null;
                            $IPR->c3_viscosity_fluid_g= null;
                            $IPR->c4_viscosity_fluid_g= null;
                            $IPR->c1_compressibility_fluid_g= null;
                            $IPR->c2_compressibility_fluid_g= null;
                            $IPR->c3_compressibility_fluid_g= null;
                            $IPR->c4_compressibility_fluid_g= null;
                            $IPR->id_escenario=$_SESSION['scenary_id'];
                            $attributtes = array();
                            
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }


                            $IPR->save();

                            //Tablas
                            //Pvt - ipr_tabla
                            $IPR_TABLA = new ipr_tabla;
                            $tabla = str_replace(",[null,null,null,null]","",$request->input("presiones_table"));
                            $presionesv = array();
                            $viscosidades_aceite= array();
                            $factores_vol_aceite = array();
                            $viscosidades_agua= array();
                            $tabla = json_decode($tabla);

                            foreach ($tabla as $value) {
                                $IPR_TABLA = new ipr_tabla;
                                $IPR_TABLA->presion = str_replace(",", ".", $value[0]);
                                $presionesv[] = (int)str_replace(",", ".", $value[0]);
                                $IPR_TABLA->viscosidad_aceite = str_replace(",",".",$value[1]);
                                $viscosidades_aceite[] = (float)str_replace(",", ".", $value[1]);
                                $IPR_TABLA->factor_volumetrico_aceite = str_replace(",",".",$value[2]);
                                $factores_vol_aceite[] = (float)str_replace(",", ".", $value[2]);
                                $IPR_TABLA->viscosidad_agua = str_replace(",",".",$value[3]);
                                $viscosidades_agua[] = (float)str_replace(",", ".", $value[3]);
                                $IPR_TABLA->id_ipr=$IPR->id;
                                $IPR_TABLA->save();
                            }

                            $presiones = json_encode($tabla);
                            
                            if ($this->allZeroes($presionesv)) {
                                $presionesv = [0];
                            }

                            if ($this->allZeroes($viscosidades_aceite)) {
                                $viscosidades_aceite = [0];
                            }

                            if ($this->allZeroes($factores_vol_aceite)) {
                                $factores_vol_aceite = [0];
                            }

                            if ($this->allZeroes($viscosidades_agua)) {
                                $viscosidades_agua = [0];
                            }

                            //Water-oil - ipr_tabla_water

                            $i_tables = 0;
                            
                            $tabla_wateroil = json_decode(str_replace(",[null,null,null]","",$request->input("wateroil_hidden")));
                            
                            $lista_sw = array();
                            $lista_krw = array();
                            $lista_kro = array();
                            foreach ($tabla_wateroil as $value) {
                                $i_tables++;
                                $IPR_TABLA_WATEROIL = new ipr_tabla_wateroil;
                                $IPR_TABLA_WATEROIL->lista_sw = str_replace(",", ".", $value[0]);
                                $lista_sw[] = (float)$IPR_TABLA_WATEROIL->lista_sw;
                                $IPR_TABLA_WATEROIL->lista_krw = str_replace(",", ".", $value[1]);
                                $lista_krw[] = (float)$IPR_TABLA_WATEROIL->lista_krw;
                                $IPR_TABLA_WATEROIL->lista_kro = str_replace(",", ".", $value[2]);
                                $lista_kro[] = (float)$IPR_TABLA_WATEROIL->lista_kro;
                                $IPR_TABLA_WATEROIL->id_ipr=$IPR->id;
                                $IPR_TABLA_WATEROIL->save();
                            }
                            
                            if ($this->allZeroes($lista_sw)) {
                                $i_tables = 0;
                                $lista_sw = [0];
                            }

                            if ($this->allZeroes($lista_krw)) {
                                $i_tables = 0;
                                $lista_krw = [0];
                            }

                            if ($this->allZeroes($lista_kro)) {
                                $i_tables = 0;
                                $lista_kro = [0];
                            }
                            
                            $lista_kro = json_encode($lista_kro);
                            $lista_krw = json_encode($lista_krw);
                            $lista_sw = json_encode($lista_sw);

                            //Gas-Oil - ipr_tabla_gas

                            $tabla_gasliquid = json_decode(str_replace(",[null,null,null]","",$request->input("gasliquid_hidden")));
                            $lista_sg = array();
                            $lista_krg = array();
                            $lista_krosg = array();
                            foreach ($tabla_gasliquid as $value) {
                                $i_tables++;
                                $IPR_TABLA_GASLIQUID = new ipr_tabla_gasliquid;
                                $IPR_TABLA_GASLIQUID->lista_sg = str_replace(",", ".", $value[0]);
                                $lista_sg[] = (float)$IPR_TABLA_GASLIQUID->lista_sg;
                                $IPR_TABLA_GASLIQUID->lista_krg = str_replace(",", ".", $value[1]);
                                $lista_krg[] = (float)$IPR_TABLA_GASLIQUID->lista_krg;
                                $IPR_TABLA_GASLIQUID->lista_krosg = str_replace(",", ".", $value[2]);
                                $lista_krosg[] = (float)$IPR_TABLA_GASLIQUID->lista_krosg;
                                $IPR_TABLA_GASLIQUID->id_ipr=$IPR->id;
                                $IPR_TABLA_GASLIQUID->save();
                            }
                            
                            if ($this->allZeroes($lista_sg)) {
                                $i_tables = 0;
                                $lista_sg = [0];
                            }

                            if ($this->allZeroes($lista_krg)) {
                                $i_tables = 0;
                                $lista_krg = [0];
                            }

                            if ($this->allZeroes($lista_krosg)) {
                                $i_tables = 0;
                                $lista_krosg = [0];
                            }
                            $lista_krosg = json_encode($lista_krosg);
                            $lista_krg = json_encode($lista_krg);
                            $lista_sg = json_encode($lista_sg);

                        }
                        else if($request->input("fluido") == "2")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");

                            $IPR->factor_dano = null;
                            $IPR->bsw = null;
                            $IPR->gor = null;
                            $IPR->tasa_flujo = null;
                            $IPR->presion_fondo = null;
                            $IPR->presion_inicial = null;
                            $IPR->permeabilidad_abs_ini = null;           
                            $IPR->espesor_reservorio = null; 
                            $IPR->modulo_permeabilidad = null;
                            $IPR->permeabilidad = null;
                            $IPR->porosidad = null;
                            $IPR->tipo_roca = null;
                            $IPR->end_point_kr_aceite_gas = null;
                            $IPR->saturacion_gas_crit = null;
                            $IPR->end_point_kr_gas = null;
                            $IPR->saturacion_aceite_irred_gas = null;
                            $IPR->saturacion_aceite_irred_gas = null;
                            $IPR->exponente_corey_aceite_gas = null;
                            $IPR->exponente_corey_gas = null;
                            $IPR->end_point_kr_petroleo = null;
                            $IPR->saturacion_agua_irred = null;
                            $IPR->end_point_kr_agua = null;
                            $IPR->saturacion_aceite_irred = null;
                            $IPR->exponente_corey_petroleo = null;
                            $IPR->exponente_corey_agua = null;
                            $IPR->viscosidad_agua = null;
                            $IPR->campo_a1 = null;
                            $IPR->campo_b1 = null;
                            $IPR->campo_c1 = null;
                            $IPR->campo_d1 = null;
                            $IPR->campo_a2 = null;
                            $IPR->campo_b2 = null;
                            $IPR->campo_c2 = null;
                            $IPR->campo_d2 = null;

                            $IPR->skin_g= null;
                            $IPR->gas_rate_g= $request->input("gas_rate_g");
                            $IPR->bhp_g= $request->input("bhp_g");
                            $IPR->init_res_press_text_g= $request->input("init_res_press_text_g");
                            $IPR->abs_perm_init_text_g= $request->input("abs_perm_init_text_g");
                            $IPR->net_pay_text_g= $request->input("net_pay_text_g");
                            $IPR->permeability_module_text_g= $request->input("permeability_module_text_g");
                            $IPR->abs_perm_text_g= $request->input("abs_perm_text_g");
                            $IPR->porosity_text_g= $request->input("porosity_text_g");
                            $IPR->rock_type= $request->input("rock_type");
                            $IPR->temperature_text_g= $request->input("temperature_text_g");
                            $IPR->c1_viscosity_fluid_g= $request->input("c1_viscosity_fluid_g");
                            $IPR->c2_viscosity_fluid_g= $request->input("c2_viscosity_fluid_g");
                            $IPR->c3_viscosity_fluid_g= $request->input("c3_viscosity_fluid_g");
                            $IPR->c4_viscosity_fluid_g= $request->input("c4_viscosity_fluid_g");
                            $IPR->c1_compressibility_fluid_g= $request->input("c1_compressibility_fluid_g");
                            $IPR->c2_compressibility_fluid_g= $request->input("c2_compressibility_fluid_g");
                            $IPR->c3_compressibility_fluid_g= $request->input("c3_compressibility_fluid_g");
                            $IPR->c4_compressibility_fluid_g= $request->input("c4_compressibility_fluid_g");

                            $IPR->id_escenario=$_SESSION['scenary_id'];
                            $attributtes = array();
                            
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }


                            $IPR->save();

                            //Tablas
                            //PVT ipr Gas
                            $pvt_gas_ipr = json_decode(str_replace(",[null,null,null]","",$request->input("pvt_gas_ipr")));
                            $pvt_pressure_gas = array();
                            $pvt_gasViscosity_gas = array();
                            $pvt_gasCompressibility_gas = array();
                            foreach ($pvt_gas_ipr as $value) {
                                $ipr_pvt_gas = new ipr_pvt_gas;
                                $ipr_pvt_gas->pressure = str_replace(",", ".", $value[0]);
                                $pvt_pressure_gas[] = (float)$ipr_pvt_gas->pressure;

                                $ipr_pvt_gas->gas_viscosity = str_replace(",", ".", $value[1]);
                                $pvt_gasViscosity_gas[] = (float)$ipr_pvt_gas->gas_viscosity;

                                $ipr_pvt_gas->gas_compressibility_factor = str_replace(",", ".", $value[2]);
                                $pvt_gasCompressibility_gas[] = (float)$ipr_pvt_gas->gas_compressibility_factor;

                                $ipr_pvt_gas->id_ipr=$IPR->id;
                                $ipr_pvt_gas->save();
                            }
                            
                            if ($this->allZeroes($pvt_pressure_gas)) {
                                $pvt_pressure_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasViscosity_gas)) {
                                $pvt_gasViscosity_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasCompressibility_gas)) {
                                $pvt_gasCompressibility_gas = [0];
                            }
                            $pvt_pressure_gas = json_encode($pvt_pressure_gas);
                            $pvt_gasViscosity_gas = json_encode($pvt_gasViscosity_gas);
                            $pvt_gasCompressibility_gas = json_encode($pvt_gasCompressibility_gas);
                        }
                        else if($request->input("fluido")=="3")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                            $IPR->gas_rate_c_g = $request->input("gas_rate_c_g");
                            $IPR->bhp_c_g = $request->input("bhp_c_g");
                            $IPR->initial_pressure_c_g = $request->input("presion_inicial_c_g");
                            $IPR->ini_abs_permeability_c_g = $request->input("permeabilidad_abs_ini_c_g");
                            $IPR->netpay_c_g = $request->input("espesor_reservorio_c_g");
                            $IPR->permeability_module_c_g = $request->input("modulo_permeabilidad_c_g");
                            $IPR->permeability_c_g = $request->input("permeabilidad_c_g");
                            $IPR->porosity_c_g = $request->input("porosidad_c_g");
                            $IPR->rock_type_c_g = $request->input("tipo_roca_c_g");
                            $IPR->saturation_pressure_c_g = $request->input("presion_saturacion_c_g");
                            $IPR->gor_c_g = $request->input("gor_c_g");
                            $IPR->id_escenario=$_SESSION['scenary_id'];

                            $attributtes = array();
                            
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }

                            $IPR->save();

                            #dd($request->input());
                            #Tablas
                            //Kr gas oil - Condensate gas
                            $kr_c_g_table_data = str_replace(",[null,null,null]","",$request->input("gas_oil_kr_cg"));
                            $sg_data = array();
                            $krg_data = array();
                            $krog_data = array();
                            $kr_c_g_table_data = json_decode($kr_c_g_table_data);
                            foreach ($kr_c_g_table_data as $value) 
                            {
                                $gas_oil_kr_c_g = new ipr_gas_oil_kr_c_g; 
                                $gas_oil_kr_c_g->ipr_id = $IPR->id;
                                $gas_oil_kr_c_g->sg = str_replace(",", ".", $value[0]); 
                                $sg_data[] = (float)$gas_oil_kr_c_g->sg;

                                $gas_oil_kr_c_g->krg = str_replace(",", ".", $value[1]);
                                $krg_data[] = (float)$gas_oil_kr_c_g->krg; 

                                $gas_oil_kr_c_g->krog = str_replace(",", ".", $value[2]); 
                                $krog_data[] = (float)$gas_oil_kr_c_g->krog;

                                $gas_oil_kr_c_g->save();
                            }

                            $sg_data = json_encode($sg_data);
                            $krg_data = json_encode($krg_data);
                            $krog_data = json_encode($krog_data);

                            //PVT Condensate Gas
                            $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));
                            
                            $pressure_data = array();
                            $bo_data = array();
                            $vo_data = array();
                            $rs_data = array();
                            $bg_data = array();
                            $vg_data = array();
                            $og_ratio_data = array();
                            $pvt_c_g_table_data = json_decode($pvt_c_g_table_data);
                            foreach ($pvt_c_g_table_data as $value) 
                            {
                                $pvt_cg = new ipr_pvt_c_g;
                                $pvt_cg->ipr_id = $IPR->id;
                                $pvt_cg->pressure = str_replace(",", ".", $value[0]);
                                $pvt_cg->bo = str_replace(",", ".", $value[1]);
                                $pvt_cg->vo = str_replace(",", ".", $value[2]);
                                $pvt_cg->rs = str_replace(",", ".", $value[3]);
                                $pvt_cg->bg = str_replace(",", ".", $value[4]);
                                $pvt_cg->vg = str_replace(",", ".", $value[5]);
                                $pvt_cg->og_ratio = str_replace(",", ".", $value[6]);

                                $pressure_data[] = (float)$pvt_cg->pressure; 
                                $bo_data[] = (float)$pvt_cg->bo; 
                                $vo_data[] = (float)$pvt_cg->vo; 
                                $rs_data[] = (float)$pvt_cg->rs; 
                                $bg_data[] = (float)$pvt_cg->bg; 
                                $vg_data[] = (float)$pvt_cg->vg; 
                                $og_ratio_data[] = (float)$pvt_cg->og_ratio; 

                                $pvt_cg->save();
                            }

                            $pressure_data = json_encode($pressure_data);
                            $bo_data = json_encode($bo_data);
                            $vo_data = json_encode($vo_data);
                            $rs_data = json_encode($rs_data);
                            $bg_data = json_encode($bg_data);
                            $vg_data = json_encode($vg_data);
                            $og_ratio_data = json_encode($og_ratio_data);


                            //Drop out Condensate Gas
                            $dropout_c_g_table_data = str_replace(",[null,null]","",$request->input("dropout_cg"));

                            $dropout_pressure_data = array();
                            $dropout_liquid_percentage = array();
                            $dropout_c_g_table_data = json_decode($dropout_c_g_table_data);

                            foreach ($dropout_c_g_table_data as $value) 
                            {
                                $dropout_c_g = new ipr_dropout_c_g;
                                
                                $dropout_c_g->ipr_id = $IPR->id;

                                $dropout_c_g->pressure = str_replace(",",".", $value[0]);
                                $dropout_c_g->liquid_percentage = str_replace(",",".", $value[1]);

                                $dropout_pressure_data[] = $dropout_c_g->pressure;
                                $dropout_liquid_percentage[] = $dropout_c_g->liquid_percentage;

                                $dropout_c_g->save();
                            }

                            $dropout_pressure_data = json_encode($dropout_pressure_data);
                            $dropout_liquid_percentage = json_encode($dropout_liquid_percentage);
                        }
                        

                        $attributtes = array();
                        $tmp_attr = $IPR->toArray();

                        foreach($tmp_attr as $k => $v){
                            if($v == '')
                            {
                                $v = null;
                            }
                            $attributtes[$k] = $v;
                        }
                        foreach($attributtes as $k => $v)
                        {
                            $IPR->$k = $v;
                        }

                        $IPR->save();
                        $_SESSION['ipr'] = $IPR->id;
                        $_SESSION['for_id'] = $IPR->formation_id;
                        $_SESSION['fie_id'] = $IPR->field_id;
                        $_SESSION['we_id'] = $IPR->well_id;

                        $scenaryId= DB::table('ipr')->where('id_escenario', $_SESSION['scenary_id'])->pluck('id_escenario');
                        $scenary = escenario::find($scenaryId);
                        $scenary->estado=1;

                        if($validator->fails())
                        {
                            $scenary->completo=0;
                        }
                        else
                        {
                            $scenary->completo=0;
                        }
                        $scenary->save();



                        return \Redirect::route('IPR.edit',$IPR->id_escenario);
            }
        }
        else
        {
            return view('loginfirst');
        }
    }

    /* Esta función reconstruye el input enviado al módulo de python con base en las sensibilidades que el usuario escoge, ejecuta de nuevo el módulo y presenta los nuevos resultados junto al caso base. */
    public function sensibility(IPRRequest $request)
    {   
        if (\Auth::check()) 
        {
            set_time_limit(360); 
            $pozo = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
            $formacion = DB::table('formacionxpozos')->where('nombre', $_SESSION['formation'])->first();
            $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
            $IPR = DB::table('IPR')->where('id', $request->get("id_ipr"))->first();
            $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
            $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
            $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
            $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
            $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');
           //Resultado ipr calculado e ipr skin ideal
            $ipr_result_calculated_skin = DB::table('ipr_resultados')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();        
            $ipr_result_ideal_skin = DB::table('ipr_resultados_skin_ideal')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();
            $skin_resultante = DB::table('ipr_resultados')->select('skin')->where('id_ipr',$IPR->id)->get();
            foreach ($skin_resultante as $value) 
            {
                $skin_resultante_final = $value->skin;
            }
            $i_tables = 0;
            $ipr_resultados = array();
            $categorias = array();
            $eje_y = array();
            $data = array(); 
            $skin=0; 
            $skin_tmp = 0; 
            foreach ($ipr_result_calculated_skin as $value)
            {   
                $skin = $value->skin;
                $data[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias[] = (float)$value->tasa_flujo;
                $eje_y[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }
            $categorias = json_encode($categorias);
            $eje_y = json_encode($eje_y);
            #$data = json_encode($data);
            $categorias_skin_ideal = array();
            $eje_y_skin_ideal = array();
            $data_skin_ideal = array(); 
            foreach ($ipr_result_ideal_skin as $value)
            {   
                $data_skin_ideal[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias_skin_ideal[] = (float)$value->tasa_flujo;
                $eje_y_skin_ideal[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }
            $categorias_skin_ideal = json_encode($categorias_skin_ideal);
            $eje_y_skin_ideal = json_encode($eje_y_skin_ideal);
            #$data_skin_ideal = json_encode($data_skin_ideal);
                          
                       
            if($IPR->fluido =="1")
            {
                $tasa_flujo = $IPR->tasa_flujo;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = $IPR->tipo_roca;
            }
            else if($IPR->fluido =="2")
            {
                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = $IPR->rock_type;
            }
            else if($IPR->fluido == "3")
            {
                $tasa_flujo = $IPR->gas_rate_c_g;
                $presion_fondo = $IPR->bhp_c_g;
                $tipo_roca = $IPR->rock_type_c_g;
            }
            $data_i = $data_skin_ideal;
            $ejes_dobles[] = $data;
            $ejes_dobles[] = $data_i;
            $etiquetas_ejes[] = 'Current IPR';
            $etiquetas_ejes[] = 'Base Case';
            #Generación del set de datos y normalización de las tablas para el envío al módulo de python.
            if($IPR->fluido=="1")
            {
                $table = DB::table('IPR_TABLA')->select('presion', 'factor_volumetrico_aceite', 'viscosidad_aceite', 'viscosidad_agua')->where('id_ipr', $IPR->id)->get();
                $valid_table = false;
                $a = 0;
                $presionesv = array();
                $viscosidades_aceite= array();
                $factores_vol_aceite = array();
                $viscosidades_agua= array();
                foreach ($table as $value) 
                {
                    $presionesv[] = (float)$value->presion;
                    $viscosidades_aceite[] = (float)$value->viscosidad_aceite;
                    $factores_vol_aceite[] = (float)$value->factor_volumetrico_aceite;
                    $viscosidades_agua[] = (float)$value->viscosidad_agua;
                }
                //Water oil table - ipr oil
                $tabla_wateroil = DB::table('ipr_tabla_water')->select('lista_sw', 'lista_krw', 'lista_kro')->where('id_ipr', $IPR->id)->get();
                $lista_sw = array();
                $lista_krw = array();
                $lista_kro = array();
                foreach ($tabla_wateroil as $value) 
                {
                    $i_tables++;
                    
                    $lista_sw[] = (float)$value->lista_sw;
                    $lista_krw[] = (float)$value->lista_krw;
                    $lista_kro[] = (float)$value->lista_kro;
                }
                //Gas oil table - ipr oil
                $tabla_gasliquid = DB::table('IPR_TABLA_GAS')->select('lista_sg', 'lista_krg', 'lista_krosg')->where('id_ipr', $IPR->id)->get();
                $lista_sg = array();
                $lista_krg = array();
                $lista_krosg = array();
                foreach ($tabla_gasliquid as $value) 
                {
                    $i_tables++;
                    
                    $lista_sg[] = (float)$value->lista_sg;
                    $lista_krg[] = (float)$value->lista_krg;
                    $lista_krosg[] = (float)$value->lista_krosg;
                    
                    $gas_table[] = [
                        (float)$value->lista_sg,
                        (float)$value->lista_krg,
                        (float)$value->lista_krosg
                    ];    
                }
                
                if ($this->allZeroes($lista_sg)) {
                    $i_tables = 0;
                }
                if ($this->allZeroes($lista_krg)) {
                    $i_tables = 0;
                }
                if ($this->allZeroes($lista_krosg)) {
                    $i_tables = 0;
                }
                
               
                $gas_table = json_encode($gas_table); //Oil
                $tabla = json_encode($table); //Oil
                $lista_sg = json_encode($lista_sg);
                $lista_krg = json_encode($lista_krg);
                $lista_krosg = json_encode($lista_krosg);
                $lista_sw = json_encode($lista_sw);
                $lista_krw = json_encode($lista_krw);
                $lista_kro = json_encode($lista_kro);
                $input_data = 
                [
                    "presiones" => json_encode($presionesv),
                    "viscosidades_aceite" => json_encode($viscosidades_aceite),
                    "factores_vol_aceite" => json_encode($factores_vol_aceite),
                    "viscosidades_agua" => json_encode($viscosidades_agua),
                    "fluido" => $IPR->fluido,
                    "radio_pozo" => $IPR->radio_pozo,
                    "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                    "presion_yacimiento" => $IPR->presion_yacimiento,
                    "factor_dano" => "-1",
                    "bsw" => $IPR->bsw,
                    #"gor" => $IPR->gor,
                    "tasa_flujo" => $IPR->tasa_flujo,
                    "presion_fondo" => $IPR->presion_fondo,
                    "presion_inicial" => $IPR->presion_inicial,
                    "permeabilidad_abs_ini" => $IPR->permeabilidad_abs_ini,
                    "espesor_reservorio" => $IPR->espesor_reservorio,
                    "modulo_permeabilidad" => $IPR->modulo_permeabilidad,
                    "permeabilidad" => $IPR->permeabilidad,
                    "porosidad" => $IPR->porosidad,
                    "tipo_roca" => $IPR->tipo_roca,
                    "end_point_kr_aceite_gas" => $IPR->end_point_kr_aceite_gas,
                    "saturacion_gas_crit" => $IPR->saturacion_gas_crit,
                    "end_point_kr_gas" => $IPR->end_point_kr_gas,
                    "saturacion_aceite_irred_gas" => $IPR->saturacion_aceite_irred_gas,
                    "exponente_corey_aceite_gas" => $IPR->exponente_corey_aceite_gas, 
                    "exponente_corey_gas" => $IPR->exponente_corey_gas,
                    "end_point_kr_petroleo" => $IPR->end_point_kr_petroleo,
                    "saturacion_agua_irred" => $IPR->saturacion_agua_irred,                
                    "end_point_kr_agua" => $IPR->end_point_kr_agua,
                    "saturacion_aceite_irred" => $IPR->saturacion_aceite_irred,
                    "exponente_corey_petroleo" => $IPR->exponente_corey_petroleo,
                    "exponente_corey_agua" => $IPR->exponente_corey_agua,
                    "viscosidad_agua" => $IPR->viscosidad_agua,
                    "saturation_pressure" => $IPR->saturation_pressure,
                    "campo_a1" => $IPR->campo_a1,
                    "campo_b1" => $IPR->campo_b1,
                    "campo_c1" => $IPR->campo_c1,
                    "campo_d1" => $IPR->campo_d1,
                    "campo_a2" => $IPR->campo_a2,
                    "campo_b2" => $IPR->campo_b2,
                    "campo_c2" => $IPR->campo_c2,
                    "campo_d2" => $IPR->campo_d2,
                    "lista_sg" => $lista_sg, #Gas-oil
                    "lista_krg" => $lista_krg,
                    "lista_krosg" => $lista_krosg,
                    "lista_sw" => $lista_sw, #Water-oil
                    "lista_krw" => $lista_krw,
                    "lista_kro" => $lista_kro,
                 ];
            }
            else if($IPR->fluido=="2")
            {
                //Gas oil table - ipr oil
                $tabla_ipr_pvt_gas = DB::table('ipr_pvt_gas')->select('pressure', 'gas_viscosity', 'gas_compressibility_factor')->where('id_ipr', $IPR->id)->get();
                $lista_pressure = array();
                $lista_gas_viscosity = array();
                $lista_gas_compressibility = array();
                foreach ($tabla_ipr_pvt_gas as $value) {
                    $i_tables++;
                    
                    $lista_pressure[] = (float)$value->pressure;
                    $lista_gas_viscosity[] = (float)$value->gas_viscosity;
                    $lista_gas_compressibility[] = (float)$value->gas_compressibility_factor;
                    
                    $pvt_gas_table[] = [
                        (float)$value->pressure,
                        (float)$value->gas_viscosity,
                        (float)$value->gas_compressibility_factor
                    ];
                    
                }
                $water_table = "[[0,0,0]]"; //Oil
                $gas_table = "[[0,0,0]]"; //Oil
                $tabla = "[[0,0,0]]"; //Oil
                $lista_pressure = json_encode($lista_pressure); //Gas
                $lista_gas_viscosity = json_encode($lista_gas_viscosity); //Gas
                $lista_gas_compressibility = json_encode($lista_gas_compressibility); //Gas
                $input_data = 
                [
                    "fluido" => $IPR->fluido,
                    "radio_pozo" => $IPR->radio_pozo,
                    "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                    "presion_yacimiento" => $IPR->presion_yacimiento,
                    "factor_dano" => "-1",
                    "gas_rate_g" => $IPR->gas_rate_g,
                    "bhp_g" => $IPR->bhp_g,
                    "init_res_press_text_g" => $IPR->init_res_press_text_g,
                    "abs_perm_init_text_g" => $IPR->abs_perm_init_text_g,
                    "net_pay_text_g" => $IPR->net_pay_text_g,
                    "permeability_module_text_g" => $IPR->permeability_module_text_g,
                    "abs_perm_text_g" => $IPR->abs_perm_text_g,
                    "porosity_text_g" => $IPR->porosity_text_g,
                    "rock_type" => $IPR->rock_type,
                    "temperature_text_g" => $IPR->temperature_text_g,
                    "c1_viscosity_fluid_g" => $IPR->c1_viscosity_fluid_g,
                    "c2_viscosity_fluid_g" => $IPR->c2_viscosity_fluid_g,
                    "c3_viscosity_fluid_g" => $IPR->c3_viscosity_fluid_g,
                    "c4_viscosity_fluid_g" => $IPR->c4_viscosity_fluid_g,
                    "c1_compressibility_fluid_g" => $IPR->c1_compressibility_fluid_g,
                    "c2_compressibility_fluid_g" => $IPR->c2_compressibility_fluid_g, 
                    "c3_compressibility_fluid_g" => $IPR->c3_compressibility_fluid_g,
                    "c4_compressibility_fluid_g" => $IPR->c4_compressibility_fluid_g,
                    "pvt_pressure_gas" => $lista_pressure, #Pvt-Gas
                    "pvt_gasviscosity_gas" => $lista_gas_viscosity,
                    "pvt_gascompressibility_gas" => $lista_gas_compressibility,
                ];
            }
            else if($IPR->fluido == "3")
            {
                //Condesate Gas Tables
                $water_table = "[[0,0,0]]"; //Oil
                $gas_table = "[[0,0,0]]"; //Oil
                $tabla = "[[0,0,0]]"; //Oil
                $pvt_gas_table = "[[0,0,0]]"; //Gas
                $sg_data = array();
                $krg_data = array();
                $krog_data = array();
                $kr_c_g_table_data = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->get();
                foreach ($kr_c_g_table_data as $value) 
                {
                    $sg_data[] = (float)$value->sg;
                    $krg_data[] = (float)$value->krg; 
                    $krog_data[] = (float)$value->krog;
                }
                $sg_data = json_encode($sg_data);
                $krg_data = json_encode($krg_data);
                $krog_data = json_encode($krog_data);
                //PVT Condensate Gas
                $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));
                
                $pressure_data = array();
                $bo_data = array();
                $vo_data = array();
                $rs_data = array();
                $bg_data = array();
                $vg_data = array();
                $og_ratio_data = array();
                $pvt_c_g_table_data = DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->get();
                foreach ($pvt_c_g_table_data as $value) 
                {
                    $pressure_data[] = (float)$value->pressure; 
                    $bo_data[] = (float)$value->bo; 
                    $vo_data[] = (float)$value->vo; 
                    $rs_data[] = (float)$value->rs; 
                    $bg_data[] = (float)$value->bg; 
                    $vg_data[] = (float)$value->vg; 
                    $og_ratio_data[] = (float)$value->og_ratio; 
                }
                $pressure_data = json_encode($pressure_data);
                $bo_data = json_encode($bo_data);
                $vo_data = json_encode($vo_data);
                $rs_data = json_encode($rs_data);
                $bg_data = json_encode($bg_data);
                $vg_data = json_encode($vg_data);
                $og_ratio_data = json_encode($og_ratio_data);
                //Drop out Condensate Gas
                $dropout_c_g_table_data = DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->get();
                foreach ($dropout_c_g_table_data as $value) 
                {
                    $dropout_pressure_data[] = $value->pressure;
                    $dropout_liquid_percentage[] = $value->liquid_percentage;
                }
                $dropout_pressure_data = json_encode($dropout_pressure_data);
                $dropout_liquid_percentage = json_encode($dropout_liquid_percentage);
                $input_data =
                [
                    "fluido" => $IPR->fluido,
                    "well_radius" => $IPR->radio_pozo,
                    "drainage_radius" => $IPR->radio_drenaje_yac,
                    "reservoir_pressure" => $IPR->presion_yacimiento,
                    "gas_rate_c_g" => $IPR->gas_rate_c_g,
                    "bhp_c_g" => $IPR->bhp_c_g,
                    "initial_pressure_c_g" => $IPR->initial_pressure_c_g,
                    "ini_abs_permeability_c_g" => $IPR->ini_abs_permeability_c_g,
                    "netpay_c_g" => $IPR->netpay_c_g,
                    "permeability_module_c_g" => $IPR->permeability_module_c_g,
                    "permeability_c_g" => $IPR->permeability_c_g,
                    "porosity_c_g" => $IPR->porosity_c_g,
                    "rock_type_c_g" => $IPR->rock_type_c_g,
                    "saturation_pressure_c_g" => $IPR->saturation_pressure_c_g,
                    "gor_c_g" => $IPR->gor_c_g,
                    "sg_data" => $sg_data,
                    "krg_data" => $krg_data,
                    "krog_data" => $krog_data,
                    "pressure_data" => $pressure_data,
                    "bo_data" => $bo_data,
                    "vo_data" => $vo_data,
                    "rs_data" => $rs_data,
                    "bg_data" => $bg_data,
                    "vg_data" => $vg_data,
                    "og_ratio_data" => $og_ratio_data,
                    "dropout_pressure_data" => $dropout_pressure_data,
                    "dropout_liquid_percentage" => $dropout_liquid_percentage,
                    "factor_dano" => -1,
                ];
            }
            #Definición de valores y tipos de las sensibilidades. 
            foreach($request->get("sensibilidad") as $clave => $valor)
            {
                $label = "";
                if($request->get("FType") == "factor_dano" or $request->get("FType") == "desagregacion")
                {
                    $label = "Skin";
                    $input_data["factor_dano"] = (float)$valor;
                }
                else if($request->get("FType") == "modulo_permeabilidad")
                {
                    $label = "Permeability Module";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    if($IPR->fluido == "1")
                    {
                        $input_data["modulo_permeabilidad"] = (float)$valor;
                    }
                    else if($IPR->fluido =="2")
                    {
                        $input_data["permeability_module_text_g"] = (float)$valor;
                    }
                    else if($IPR->fluido =="3")
                    {
                        $input_data["permeability_module_c_g"] = (float)$valor;
                    }
                }
                else if($request->get("FType") == "netpay")
                {
                    $label = "Net Pay";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    if($IPR->fluido=="1")
                    {
                        $input_data["espesor_reservorio"] = (float)$valor;
                    }
                    else if($IPR->fluido=="2")
                    {
                        $input_data["net_pay_text_g"] = (float)$valor;
                    }
                    else if($IPR->fluido=="3")
                    {
                        $input_data["netpay_c_g"] = (float)$valor;
                    }
                }
                else if($request->get("FType") == "absolute_permeability")
                {
                    $label = "Abosulte Permeability";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    if($IPR->fluido=="1")
                    {
                        $input_data["permeabilidad_abs_ini"] = (float)$valor;
                    }
                    else if($IPR->fluido=="2")
                    {
                        $input_data["abs_perm_init_text_g"] = (float)$valor;
                    }
                    else if($IPR->fluido=="3")
                    {
                        $input_data["ini_abs_permeability_c_g"] = (float)$valor;
                    }
                }
                else if($request->get("FType")=="bhp")
                {
                    $label = "BHP";
                    if($IPR->fluido=="1")
                    {
                        $input_data["presion_fondo"] = (float)$valor;
                    }
                    else if($IPR->fluido=="2")
                    {
                        $input_data["bhp_g"] = (float)$valor;
                    }
                    else if($IPR->fluido=="3")
                    {
                        $input_data["bhp_c_g"] = (float)$valor;
                    }
                }
                else if($request->get("FType")=="bsw")
                {
                    $label = "BSW";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data["bsw"] = (float)$valor;
                }
                else if($request->get("FType")=="corey")
                {
                    $label = "Corey Exponent Oil";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data["exponente_corey_petroleo"] = (float)$valor;
                }
                else{
                    break;
                }


                #Cálculo módulo IPR
                $ipr_function_results = json_decode($this->run_ipr($input_data));

                #Captura de resultados y tratamiento de datos
                
                $ipr_resultados = array();
                $categorias = array();
                $eje_y = array();

                $data = array();

                $tasa_flujo_resultados = $ipr_function_results[0];
                $presion_fondo_resultados = $ipr_function_results[1];
                $skin_resultados = $ipr_function_results[2];

                for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) 
                { 
                    $ipr_resultado = new ipr_resultado;
                    $ipr_resultado->skin = $skin_resultados;
                    $ipr_resultado->tasa_flujo = floatval($tasa_flujo_resultados[$i]);
                    $ipr_resultado->presion_fondo = floatval($presion_fondo_resultados[$i]);
                    $ipr_resultado->id_ipr = $IPR->id;
                    
                    $data[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3)];

                    $categorias[] = round($ipr_resultado->tasa_flujo,3);
                    $eje_y[] = round($ipr_resultado->presion_fondo,3);
                    
                    $ipr_resultados[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3), $ipr_resultado->skin];
                    

                    $skin = floatval($skin_resultados);
                    $skin_tmp = floatval($skin_resultados);
                }

                $categorias = json_encode($categorias);
                $eje_y = json_encode($eje_y);

                $categorias = json_encode($categorias);
                $eje_y = json_encode($eje_y);
                $ejes_dobles[] = $data;
                $etiquetas_ejes[] = $label . ": " . $valor;
                $valores[] = $valor;
                $ejes[] = $eje_y;
            }
            if($IPR->fluido == "2")
            {
                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
            }
            else if($IPR->fluido =="1")
            {
                $tasa_flujo = $IPR->tasa_flujo;
                $presion_fondo = $IPR->presion_fondo;
            }
            else if($IPR->fluido =="3")
            {
                $tasa_flujo = $IPR->gas_rate_c_g;
                $presion_fondo = $IPR->bhp_c_g;
            }
           if((float)$IPR->tasa_flujo != 0.000001 && (float)$IPR->presion_fondo != 0.000001)
            {
                  $ejes_dobles[] = [[round($tasa_flujo, 3), round($presion_fondo, 3)]];
                  $etiquetas_ejes[] = "Production Data";
            }
            if($request->get("FType") == "desagregacion"){
                $ejes_dobles = array_slice($ejes_dobles, 1,5);
                $etiquetas_ejes = array('Skin = 0 (Ideal)','Skin = ' . $request->get("sensibilidad")[0],'Skin by components');
                if((float)$IPR->tasa_flujo != 0.000001 && (float)$IPR->presion_fondo != 0.000001){
                    $ejes_dobles[] = [[round($IPR->tasa_flujo, 3), round($IPR->presion_fondo, 3)]];
                    $etiquetas_ejes[] = "Production Data";
                }
            }   

            return response()->json(['etiquetas_ejes' => $etiquetas_ejes, 'ejes_dobles' => $ejes_dobles]);   
        }
        else
        {
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
     * Prepara y construye el set de datos que se envía a la vista de resultados.
     */
    public function result($id)
    {
        if (\Auth::check()) 
        {
            $_SESSION["scenary_id"] = $id;
            $scenary = escenario::find($id);
            $IPR = DB::table('IPR')->where('id_escenario', $scenary->id)->first();
            $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
            $campo = DB::table('campos')->where('id', $scenary->campo_id)->first();
            $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->first();
            $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
            $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
            $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
            $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
            $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');
            $ipr_result_calculated_skin = DB::table('ipr_resultados')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();        
            $ipr_result_ideal_skin = DB::table('ipr_resultados_skin_ideal')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();
            $i_tables = 0;
            $ipr_resultados = array();
            $categorias = array();
            $eje_y = array();
            $data = array(); 
            $skin=0; 
            $skin_tmp = 0; 
            foreach ($ipr_result_calculated_skin as $value)
            {   
                $skin = $value->skin;
                $data[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias[] = (float)$value->tasa_flujo;
                $eje_y[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }
            $categorias = json_encode($categorias);
            $eje_y = json_encode($eje_y);
            $data = json_encode($data);
            $desagregacion = DB::table('desagregacion')->where('id_escenario', $id)->orderBy('created_at', 'desc')->first();
            $escenario = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
            $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
            $categorias_skin_ideal = array();
            $eje_y_skin_ideal = array();
            $data_skin_ideal = array(); 
            foreach ($ipr_result_ideal_skin as $value)
            {   
                $data_skin_ideal[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias_skin_ideal[] = (float)$value->tasa_flujo;
                $eje_y_skin_ideal[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }
            $categorias_skin_ideal = json_encode($categorias_skin_ideal);
            $eje_y_skin_ideal = json_encode($eje_y_skin_ideal);
            $data_skin_ideal = json_encode($data_skin_ideal);
                          
            $scenaryId = \Request::get('scenaryId');
            $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
            if($IPR->fluido =="1")
            {
                $tasa_flujo = $IPR->tasa_flujo;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = $IPR->tipo_roca;
            }
            else if($IPR->fluido =="2")
            {
                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = $IPR->rock_type;
            }
            else if($IPR->fluido =="3")
            {
                $tasa_flujo = $IPR->gas_rate_c_g;
                $presion_fondo = $IPR->bhp_c_g;
                $tipo_roca = $IPR->rock_type_c_g;
            }
            $data_i = $data_skin_ideal;
            $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();
            #dd($data);
            $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
            $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
            $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
            $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
            $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');

            return view('IPRResults', compact('user','campo', 'IPR', 'ipr_resultados' , 'pozo', 'formacion','fluido', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca', 'desagregacion', 'data_i', 'i_tables','scenary_s','intervalo'));
        }
        else
        {
            return view('loginfirst');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        if (\Auth::check()) 
        {
            #dd($id);
            $_SESSION["scenary_id"] = $id;
            $scenary = escenario::find($id);
            $advisor = $scenary->enable_advisor;
            $IPR = DB::table('IPR')->where('id_escenario', $scenary->id)->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();
            $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
            $campo = DB::table('campos')->where('id', $scenary->campo_id)->first();
            $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->first();
            $intervalo = DB::table('formacionxpozos')->where('id',$scenary->formacion_id)->first();
            $tabla = [[null,null,null,null]];
            
            $tabla_water = [];
            $tabla_gas = [];
            $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
            $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
            $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
            $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
            $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');
            $scenaryId = \Request::get('scenaryId');
            $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
            if(is_null($IPR))
            {
                $valid_table = false;
                return view('IPRS', ['user'=>$user,'IPR' => $IPR, 'cuenca' => $cuenca, 'campo' => $campo, 'pozo' => $pozo, 'formacion' => $formacion, 'tabla' => [], "valid_table" => $valid_table, 'scenary_s'=>$scenary_s,'intervalo'=>$intervalo, 'advisor'=>$advisor]); 
            }
            else
            {

                $valid_table=false;
                $i_tables=0;
                $water_table = array();
                $gas_table = array();
                $tabla = array();
                $ipr_cg_dropout_table = array();
                $ipr_cg_gasoil_table = array();
                $ipr_cg_pvt_table = array();
                $pvt_gas_table = array();
                if($IPR->fluido=="1")
                {
                    $table = DB::table('IPR_TABLA')->select('presion', 'factor_volumetrico_aceite', 'viscosidad_aceite', 'viscosidad_agua')->where('id_ipr', $IPR->id)->get();
                    $valid_table = false;
                    $a = 0;
                    foreach($table as $v){
                        if($a == 0){
                            $tabla = [];
                            $a = 1;
                        }
                        $row = [(float)$v->presion, (float)$v->viscosidad_aceite, (float)$v->factor_volumetrico_aceite, (float)$v->viscosidad_agua];
                        $tabla[] = $row;
                        if($this->allZeroes($row) == false){
                            $valid_table = true;
                        }
                    }
                    $_SESSION['formation']=$formacion->nombre;
                    //Water oil table - ipr oil
                    $tabla_wateroil = DB::table('IPR_TABLA_WATER')->select('lista_sw', 'lista_krw', 'lista_kro')->where('id_ipr', $IPR->id)->get();
                    $lista_sw = array();
                    $lista_krw = array();
                    $lista_kro = array();
                    $i_tables = 0;
                         
                    foreach ($tabla_wateroil as $IPR_TABLA_WATEROIL) 
                    {
                        $i_tables++;
                             
                        $lista_sw[] = (float)$IPR_TABLA_WATEROIL->lista_sw;
                        $lista_krw[] = (float)$IPR_TABLA_WATEROIL->lista_krw;
                        $lista_kro[] = (float)$IPR_TABLA_WATEROIL->lista_kro;
                             
                        $water_table[] = [
                            (float)$IPR_TABLA_WATEROIL->lista_sw,
                            (float)$IPR_TABLA_WATEROIL->lista_krw,
                            (float)$IPR_TABLA_WATEROIL->lista_kro
                        ];
                    }
                         
                    if ($this->allZeroes($lista_sw)) {
                        $i_tables = 0;
                    }
                    if ($this->allZeroes($lista_krw)) {
                        $i_tables = 0;
                    }
                    if ($this->allZeroes($lista_kro)) {
                        $i_tables = 0;
                    }
                    //Gas oil table - ipr oil
                    $tabla_gasliquid = DB::table('IPR_TABLA_GAS')->select('lista_sg', 'lista_krg', 'lista_krosg')->where('id_ipr', $IPR->id)->get();
                    $lista_sg = array();
                    $lista_krg = array();
                    $lista_krosg = array();
                    foreach ($tabla_gasliquid as $IPR_TABLA_GASLIQUID) 
                    {
                        $i_tables++;
                        
                        $lista_sg[] = (float)$IPR_TABLA_GASLIQUID->lista_sg;
                        $lista_krg[] = (float)$IPR_TABLA_GASLIQUID->lista_krg;
                        
                        $lista_krosg[] = (float)$IPR_TABLA_GASLIQUID->lista_krosg;
                        
                        $gas_table[] = [
                            (float)$IPR_TABLA_GASLIQUID->lista_sg,
                            (float)$IPR_TABLA_GASLIQUID->lista_krg,
                            (float)$IPR_TABLA_GASLIQUID->lista_krosg
                        ];
                        
                    }
                         
                    if ($this->allZeroes($lista_sg)) {
                        $i_tables = 0;
                    }
                    if ($this->allZeroes($lista_krg)) {
                        $i_tables = 0;
                    }
                    if ($this->allZeroes($lista_krosg)) {
                        $i_tables = 0;
                    }
                         
                         
                    $water_table = json_encode($water_table); //Oil
                    $gas_table = json_encode($gas_table); //Oil
                    $tabla = json_encode($tabla); //Oil
                    $pvt_gas_table = "[[0,0,0]]"; //Gas
                    $ipr_cg_gasoil_table = "[[0,0,0]]";   //Condensate Gas
                    $ipr_cg_dropout_table = "[[0,0]]"; //Condensate Gas
                    $ipr_cg_pvt_table = "[[0,0,0,0,0,0,0]]"; //Condensate Gas
                    }
                    else if($IPR->fluido=="2")
                    {
                        //Gas oil table - ipr oil
                        $tabla_ipr_pvt_gas = DB::table('ipr_pvt_gas')->select('pressure', 'gas_viscosity', 'gas_compressibility_factor')->where('id_ipr', $IPR->id)->get();
                        $lista_pressure = array();
                        $lista_gas_viscosity = array();
                        $lista_gas_compressibility = array();
                        foreach ($tabla_ipr_pvt_gas as $value) 
                        {
                            $i_tables++;
                             
                            $lista_pressure[] = (float)$value->pressure;
                            $lista_gas_viscosity[] = (float)$value->gas_viscosity;
                            $lista_gas_compressibility[] = (float)$value->gas_compressibility_factor;
                             
                            $pvt_gas_table[] = [
                                (float)$value->pressure,
                                (float)$value->gas_viscosity,
                                (float)$value->gas_compressibility_factor
                            ];
                             
                        }
                        $water_table = "[[0,0,0]]"; //Oil
                        $gas_table = "[[0,0,0]]"; //Oil
                        $tabla = "[[0,0,0]]"; //Oil
          
                        $pvt_gas_table = json_encode($pvt_gas_table); //Gas
                        $ipr_cg_gasoil_table = "[[0,0,0]]";   //Condensate Gas
                        $ipr_cg_dropout_table = "[[0,0]]"; //Condensate Gas
                        $ipr_cg_pvt_table = "[[0,0,0,0,0,0,0]]"; //Condensate Gas
                    }
                    else if($IPR->fluido=="3")
                    {
                         //Condesate Gas Tables
                         //Gas oil 
                        $ipr_cg_gasoil = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->get();
                        #dd($IPR->id);
                        $ipr_cg_gasoil_table = array();
                        foreach ($ipr_cg_gasoil as $value) 
                        {
                            $ipr_cg_gasoil_table[] = [floatval($value->sg),floatval($value->krg),floatval($value->krog)];
                        }
                        //Drop out
                        $ipr_cg_dropout = DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->get();
                        $ipr_cg_dropout_table = array();
                        foreach ($ipr_cg_dropout as $value) 
                        {
                            $ipr_cg_dropout_table[] = [floatval($value->pressure),floatval($value->liquid_percentage)];
                        }
                                //PVT
                        $ipr_cg_pvt = DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->get();
                        $ipr_cg_pvt_table = array();
                        foreach ($ipr_cg_pvt as $value) 
                        {
                            $ipr_cg_pvt_table[] = [floatval($value->pressure),floatval($value->bo),floatval($value->vo),floatval($value->rs),floatval($value->bg),floatval($value->vg),floatval($value->og_ratio)];
                        }

                        $water_table = "[[0,0,0]]"; //Oil
                        $gas_table = "[[0,0,0]]"; //Oil
                        $tabla = "[[0,0,0]]"; //Oil

                        $pvt_gas_table = "[[0,0,0]]"; //Gas
                             
                        //Condensate Gas
                        $ipr_cg_gasoil_table = json_encode($ipr_cg_gasoil_table);
                        $ipr_cg_dropout_table = json_encode($ipr_cg_dropout_table);
                        $ipr_cg_pvt_table = json_encode($ipr_cg_pvt_table);
                    }

                    #dd([$ipr_cg_dropout_table, $ipr_cg_gasoil_table, $ipr_cg_pvt_table]);
                    $scenaryId = \Request::get('scenaryId');
                    $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                    $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
                    #dd($pvt_gas_table);
                    return view('IPRSEdit', ['user'=>$user,'IPR' => $IPR, 'cuenca' => $cuenca, 'campo' => $campo, 'pozo' => $pozo, 'formacion' => $formacion, 'tabla' => $tabla, "valid_table" => $valid_table, "i_tables" => $i_tables, "water_table" => $water_table, "gas_table" => $gas_table, 'scenary_s'=>$scenary_s,'pvt_gas_table' =>$pvt_gas_table,'intervalo'=>$intervalo, 'ipr_cg_dropout_table'=>$ipr_cg_dropout_table,'ipr_cg_gasoil_table'=>$ipr_cg_gasoil_table,'ipr_cg_pvt_table'=>$ipr_cg_pvt_table, 'advisor'=>$advisor]); 
                }
        }
        else
        {
            return view('loginfirst');
        }
    }
    /**
     * Prepara y construye el set de datos que se envía a la vista de edición.
     */
    public function back()
    {   
        if (\Auth::check()) 
        {
                    //dd($_SESSION['scenary_id']);
                    $scenaryId= DB::table('ipr')->where('id_escenario', $_SESSION['scenary_id'])->pluck('id_escenario');
                    $scenary = escenario::find($scenaryId);
                    $advisor = $scenary->enable_advisor;
                    $IPR = DB::table('IPR')->where('id_escenario', $scenary->id)->first();
                    $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
                    $campo = DB::table('campos')->where('id', $scenary->campo_id)->first();
                    $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
                    $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();
                    $pozo = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
                    $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->first();
                    $tabla = [[null,null,null,null]];
                    $table = DB::table('IPR_TABLA')->select('presion', 'factor_volumetrico_aceite', 'viscosidad_aceite', 'viscosidad_agua')->where('id_ipr', $IPR->id)->get();
                    $valid_table = false;
                    $intervalo = DB::table('formacionxpozos')->where('id',$scenary->formacion_id)->first();


                    //Cambiar
                    $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
                    $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
                    $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
                    $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
                    $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');




                    $a = 0;
                    foreach($table as $v){
                        if($a == 0){
                            $tabla = [];
                            $a = 1;
                        }
                        $row = [$v->presion, $v->viscosidad_aceite, $v->factor_volumetrico_aceite, $v->viscosidad_agua];
                        $tabla[] = $row;
                        if($this->allZeroes($row) == false){
                          $valid_table = true;
                        }
                    }
                    $tabla = json_encode($tabla);
                    $_SESSION['formation']=$formacion->nombre;
                    
                    $water_table = array();
                    $gas_table = array();

                    
                    //Water oil table - ipr oil
                    $tabla_wateroil = DB::table('IPR_TABLA_WATER')->select('lista_sw', 'lista_krw', 'lista_kro')->where('id_ipr', $IPR->id)->get();
                
                    $lista_sw = array();
                    $lista_krw = array();
                    $lista_kro = array();
                    $i_tables = 0;
                    $water_table = array();
                    foreach ($tabla_wateroil as $IPR_TABLA_WATEROIL) {
                        $i_tables++;
                        
                        $lista_sw[] = (float)$IPR_TABLA_WATEROIL->lista_sw;
                        $lista_krw[] = (float)$IPR_TABLA_WATEROIL->lista_krw;
                        $lista_kro[] = (float)$IPR_TABLA_WATEROIL->lista_kro;
                        $water_table[] = [
                            (float)$IPR_TABLA_WATEROIL->lista_sw,
                            (float)$IPR_TABLA_WATEROIL->lista_krw,
                            (float)$IPR_TABLA_WATEROIL->lista_kro
                        ];
                    }
                    
                    if ($this->allZeroes($lista_sw)) {
                        $i_tables = 0;
                    }

                    if ($this->allZeroes($lista_krw)) {
                        $i_tables = 0;
                    }

                    if ($this->allZeroes($lista_kro)) {
                        $i_tables = 0;
                    }


                    //Gas oil table - ipr oil
                    $tabla_gasliquid = DB::table('IPR_TABLA_GAS')->select('lista_sg', 'lista_krg', 'lista_krosg')->where('id_ipr', $IPR->id)->get();
                    $lista_sg = array();
                    $lista_krg = array();
                    $lista_krosg = array();
                    $gas_table = array();
                    foreach ($tabla_gasliquid as $IPR_TABLA_GASLIQUID) {
                        $i_tables++;
                        
                        $lista_sg[] = (float)$IPR_TABLA_GASLIQUID->lista_sg;

                        $lista_krg[] = (float)$IPR_TABLA_GASLIQUID->lista_krg;
                        
                        $lista_krosg[] = (float)$IPR_TABLA_GASLIQUID->lista_krosg;
                        
                        $gas_table[] = [
                            (float)$IPR_TABLA_GASLIQUID->lista_sg,
                            (float)$IPR_TABLA_GASLIQUID->lista_krg,
                            (float)$IPR_TABLA_GASLIQUID->lista_krosg
                        ];
                        
                    }
                    
                    if ($this->allZeroes($lista_sg)) {
                        $i_tables = 0;
                    }

                    if ($this->allZeroes($lista_krg)) {
                        $i_tables = 0;
                    }

                    if ($this->allZeroes($lista_krosg)) {
                        $i_tables = 0;
                    }


                    //Pvt table - ipr gas
                    $tabla_pvt_gas = DB::table('ipr_pvt_gas')->select('pressure', 'gas_viscosity', 'gas_compressibility_factor')->where('id_ipr', $IPR->id)->get();
                    $lista_pressure_gas = array();
                    $lista_gas_viscosity = array();
                    $lista_gas_compressibility = array();
                    $pvt_gas_table = array();
                    foreach ($tabla_pvt_gas as $pvt_gas) 
                    {
                        $i_tables++;
                        $lista_pressure_gas = (float)$pvt_gas->pressure;
                        $lista_gas_viscosity = (float)$pvt_gas->gas_viscosity;
                        $lista_gas_compressibility = (float)$pvt_gas->gas_compressibility_factor;
                        $pvt_gas_table[] = 
                        [
                            (float)$pvt_gas->pressure,
                            (float)$pvt_gas->gas_viscosity,
                            (float)$pvt_gas->gas_compressibility_factor
                        ];
                    }


                    //Condesate Gas Tables
                    //Gas oil 
                    $ipr_cg_gasoil = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->get();
                    $ipr_cg_gasoil_table = array();
                    foreach ($ipr_cg_gasoil as $value) 
                    {
                        $ipr_cg_gasoil_table[] = [floatval($value->sg),floatval($value->krg),floatval($value->krog)];
                    }

                    //Drop out
                    $ipr_cg_dropout = DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->get();
                    $ipr_cg_dropout_table = array();
                    foreach ($ipr_cg_dropout as $value) 
                    {
                        $ipr_cg_dropout_table[] = [floatval($value->pressure),floatval($value->liquid_percentage)];
                    }

                    //PVT
                    $ipr_cg_pvt = DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->get();
                    $ipr_cg_pvt_table = array();
                    foreach ($ipr_cg_pvt as $value) 
                    {
                        $ipr_cg_pvt_table[] = [floatval($value->pressure),floatval($value->bo),floatval($value->vo),floatval($value->rs),floatval($value->bg),floatval($value->vg),floatval($value->og_ratio)];
                    }

                    $water_table = json_encode($water_table); //Oil
                    $gas_table = json_encode($gas_table); //Oil


                    $pvt_gas_table = json_encode($pvt_gas_table); //Gas
                    
                    //Condensate Gas
                    $ipr_cg_gasoil_table = json_encode($ipr_cg_gasoil_table);
                    $ipr_cg_dropout_table = json_encode($ipr_cg_dropout_table);
                    $ipr_cg_pvt_table = json_encode($ipr_cg_pvt_table);


                    $scenaryId = \Request::get('scenaryId');
                    $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                    $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
                    return view('IPRSEdit', ['user'=>$user,'IPR' => $IPR, 'cuenca' => $cuenca, 'campo' => $campo, 'pozo' => $pozo, 'formacion' => $formacion, 'tabla' => $tabla,'valid_table'=>$valid_table, "i_tables" => $i_tables, "water_table" => $water_table, "gas_table" => $gas_table, 'scenary_s'=>$scenary_s, 'pvt_gas_table' =>$pvt_gas_table, 'intervalo'=>$intervalo, 'ipr_cg_dropout_table'=>$ipr_cg_dropout_table,'ipr_cg_gasoil_table'=>$ipr_cg_gasoil_table,'ipr_cg_pvt_table'=>$ipr_cg_pvt_table, 'advisor'=>$advisor ]); 
        }
        else
        {
            return view('loginfirst');
        }
    }


    function allZeroes($arr) 
    {
        foreach($arr as $v) { if($v != 0) return false; }
        return true;
    }   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IPRRequest $request, $id)
    {   
        if (\Auth::check()) 
        {
                    set_time_limit(360); 
                    $pozo = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
                    $formacion = DB::table('formacionxpozos')->where('nombre', $_SESSION['formation'])->first();
                    $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
                    $scenaryId = \Request::get('scenaryId');
                    $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                    $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
                    $presion_yacimiento = $request->input("presion_yacimiento");
                    $radio_pozo = $request->input("radio_pozo");
                    $saturacion_gas_crit = $request->input("saturacion_gas_crit");
                    
                    $limite_max= 1-($request->input("saturacion_aceite_irred_gas"))-($request->input("saturacion_agua_irred"));
                    
                    #Diferneciar qué tipo de fluido es y definir validator.
                    if($request->input("fluido") == "1")
                    {
                        $validator = Validator::make($request->all(),
                            [
                                'fluido'=>'numeric|min:0',
                                'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$radio_pozo,
                                'presion_yacimiento' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'bsw' => 'required|numeric|min:0|max:1',
                                #'gor'=>'required|numeric|min:0',
                                'tasa_flujo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'presion_fondo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0|max:'.$presion_yacimiento.'|not_in:'.$presion_yacimiento,
                                'presion_inicial' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$presion_yacimiento,
                                'permeabilidad_abs_ini' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'espesor_reservorio' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'modulo_permeabilidad' => 'required_if:permeabilidad,|numeric|min:0',
                                'permeabilidad' => 'required_if:modulo_permeabilidad,|numeric|min:0',
                                'porosidad' => 'required_if:modulo_permeabilidad,|numeric|between:0,0.5|not_in:0', #No puede ser igual a cero
                                'tipo_roca' => 'required_if:modulo_permeabilidad,|numeric|min:0',
                                'end_point_kr_aceite_gas'=>'required_if:flag_perm_oil,corey|numeric|between:0,1|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
                                'saturacion_gas_crit' => 'required_if:flag_perm_oil,corey|numeric|min:0|max:1|not_in:1', #mayor e igual a cero y menor que 1
                                'end_point_kr_gas' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                                'saturacion_aceite_irred_gas' => 'required_if:flag_perm_oil,corey|numeric|min:0|max:1|not_in:1',#mayor e igual a cero y menor que 1
                                'exponente_corey_aceite_gas'=>'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0',#Mayor o igual a 1
                                'exponente_corey_gas' => 'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0', 
                                'end_point_kr_petroleo' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                                'saturacion_agua_irred' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:1',#mayor e igual a cero y menor que 1
                                'end_point_kr_agua' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                                'saturacion_aceite_irred' => 'required_if:flag_perm_oil,corey|numeric|between:0,1|not_in:1', #mayor e igual a cero y menor que 1
                                'exponente_corey_petroleo' => 'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0', 
                                'exponente_corey_agua' => 'required_if:flag_perm_oil,corey|numeric|min:0|not_in:0', 
                                'presion_saturacion' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'viscosidad_agua' => 'required_if:flag_pvt_oil,cub_eq|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'campo_a1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                                'campo_b1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                                'campo_c1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                                'campo_d1'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                                'campo_a2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                                'campo_b2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                                'campo_c2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                                'campo_d2'=>'required_if:flag_pvt_oil,cub_eq|numeric',
                            ]);

                        $validator->setAttributeNames(array(
                            'fluido'=>'Fluid Type',
                            'radio_pozo' => 'Well Radius',
                            'radio_drenaje_yac' => 'Reservoir Drainage Radius',
                            'presion_yacimiento' => 'Current Reservoir Pressure',
                            'bsw' => 'BSW',
                            #'gor'=>'Gor',
                            'tasa_flujo' => 'Oil Rate',
                            'presion_fondo' => 'BHP',
                            'presion_inicial' => 'Initial Reservoir Pressure',
                            'permeabilidad_abs_ini' => 'Absolute Permeability At Initial Reservoir Pressure',
                            'espesor_reservorio' => 'Net Pay',
                            'modulo_permeabilidad' => 'Permeability Module',
                            'permeabilidad' => 'Absolute Permeability',
                            'porosidad' => 'Porosity', 
                            'tipo_roca' => 'Rock Type',
                            'end_point_kr_aceite_gas'=>'Kro (Sgc)',
                            'saturacion_gas_crit' => 'Sgc', 
                            'end_point_kr_gas' => 'Krg (Sorg)', 
                            'saturacion_aceite_irred_gas' => 'Sorg',
                            'exponente_corey_aceite_gas'=>'Corey Exponent Oil/Gas',
                            'exponente_corey_gas' => 'Corey Exponent Gas', 
                            'end_point_kr_petroleo' => 'Kro (Swi)', 
                            'saturacion_agua_irred' => 'Swi',
                            'end_point_kr_agua' => 'Krw (Sor)', 
                            'saturacion_aceite_irred' => 'Sor', 
                            'exponente_corey_petroleo' => 'Corey Exponent Oil', 
                            'exponente_corey_agua' => 'Corey Exponent Water', 
                            'presion_saturacion' => 'Saturation Pressure',
                            'viscosidad_agua' => 'Water Viscosity',
                            'campo_a1'=> 'A Coeficient in Uo',
                            'campo_b1'=> 'B Coeficient in Uo',
                            'campo_c1'=> 'C Coeficient in Uo',
                            'campo_d1'=> 'D Coeficient in Uo',
                            'campo_a2'=> 'A Coeficient in Bo',
                            'campo_b2'=> 'B Coeficient in Bo',
                            'campo_c2'=> 'C Coeficient in Bo',
                            'campo_d2'=> 'D Coeficient in Bo',
                            'flag_pvt_oil'=>'Check pvt data selection',

                            ));
                    }
                    else if($request->input("fluido") == "2")
                    {
                        //dd($request);
                        $presion_yacimiento = $request->input("presion_yacimiento");
                        $validator = Validator::make($request->all(),
                            [
                                'fluido'=>'numeric|min:0',
                                'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$radio_pozo,
                                'presion_yacimiento' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'gas_rate_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'bhp_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0|max:'.$presion_yacimiento.'|not_in:'.$presion_yacimiento,
                                'init_res_press_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$presion_yacimiento,
                                'abs_perm_init_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'net_pay_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'permeability_module_text_g' => 'required_if:abs_perm_text_g,|numeric|min:0',
                                'abs_perm_text_g' => 'required_if:permeability_module_text_g,|numeric|min:0',
                                'porosity_text_g' => 'required_if:permeability_module_text_g,|numeric|min:0|max:0.5|not_in:0', #No puede ser igual a cero
                                'rock_type' => 'required_if:permeability_module_text_g,|numeric|min:0',
                                'temperature_text_g'=>'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                                'c1_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                                'c2_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                                'c3_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                                'c4_viscosity_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                                'c1_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                                'c2_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                                'c3_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                                'c4_compressibility_fluid_g'=>'required_if:flag_pvt_gas,cub_eq|numeric|min:0',
                            ]);

                        $validator->setAttributeNames(array(
                            'fluido'=>'Fluid Type',
                            'radio_pozo' => 'Well Radius',
                            'radio_drenaje_yac' => 'Reservoir Drainage Radius',
                            'presion_yacimiento' => 'Current Reservoir Pressure',
                            'gas_rate_g' => 'Gas Rate',
                            'bhp_g' => 'BHP',
                            'init_res_press_text_g' => 'Initial Reservoir Pressure',
                            'abs_perm_init_text_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                            'net_pay_text_g' => 'Net pay',
                            'permeability_module_text_g' => 'Permeability Module',
                            'abs_perm_text_g' => 'Absolute Permeability',
                            'porosity_text_g' => 'Porosity', #No puede ser igual a cero
                            'rock_type' => 'Rock Type',
                            'temperature_text_g'=>'Temperature',
                            'c1_viscosity_fluid_g'=>'A Coeficient in Ug',
                            'c2_viscosity_fluid_g'=>'BCoeficient in Ug',
                            'c3_viscosity_fluid_g'=>'C Coeficient in Ug',
                            'c4_viscosity_fluid_g'=>'D Coeficient in Ug',
                            'c1_compressibility_fluid_g'=>'A Coeficient in Bo',
                            'c2_compressibility_fluid_g'=>'B Coeficient in Bo',
                            'c3_compressibility_fluid_g'=>'C Coeficient in Bo',
                            'c4_compressibility_fluid_g'=>'D Coeficient in Bo',
                            'flag_pvt_gas'=>'Check pvt data selection',
                            ));
                    }
                    else if($request->input("fluido") == "3") 
                    {
                        $validator = Validator::make($request->all(),
                            [
                                'fluido' => 'numeric|min:0',
                                'radio_pozo' => 'required|numeric|min:0',
                                'radio_drenaje_yac' => 'required|numeric|min:'.$radio_pozo,
                                'presion_yacimiento' => 'required|numeric|min:0',
                                'gas_rate_c_g' => 'required|numeric',
                                'bhp_c_g' => 'required|numeric',
                                'presion_inicial_c_g' => 'required|numeric',
                                'permeabilidad_abs_ini_c_g' => 'required|numeric',
                                'espesor_reservorio_c_g' => 'required|numeric',
                                'modulo_permeabilidad_c_g' => 'required_if:permeabilidad_c_g,|numeric|min:0',
                                'permeabilidad_c_g' => 'required_if:modulo_permeabilidad_c_g,|numeric',
                                'porosidad_c_g' => 'required_if:modulo_permeabilidad_c_g,|numeric',
                                'tipo_roca_c_g' => 'required_if:modulo_permeabilidad_c_g,|numeric|min:0',
                                'presion_saturacion_c_g' => 'required|numeric',
                                'gor_c_g' => 'required|numeric',
                            ]);

                        $validator->setAttributeNames(array(
                            'fluido' => 'Fluid Type',
                            'radio_pozo' => 'Well Radius',
                            'radio_drenaje_yac' => 'Reservoir Drainage Radius',
                            'presion_yacimiento' => 'Current Reservoir Pressure',
                            'gas_rate_c_g' => 'Gas Rate',
                            'bhp_c_g' => 'BHP',
                            'presion_inicial_c_g' => 'Initial Reservoir Pressure',
                            'permeabilidad_abs_ini_c_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                            'espesor_reservorio_c_g' => 'Net Pay',
                            'modulo_permeabilidad_c_g' => 'Permeability Module',
                            'permeabilidad_c_g' => 'Absolute Permeability',
                            'porosidad_c_g' => 'Porosity',
                            'tipo_roca_c_g' => 'Rock Type',
                            'presion_saturacion_c_g' => 'Saturation Pressure',
                            'gor_c_g' => 'Gor',
                            ));
                    }

                    if($request->input("accion") == "Run IPR")
                    {
                        if ($validator->fails()) 
                        {
                        $scenary = escenario::find($_SESSION['scenary_id']);
                        $scenary->completo=0;
                        $scenary->save();
                        return redirect("IPR/$id/edit") # IPR
                            ->withErrors($validator)
                            ->withInput();
                        }
                        else
                        {
                        $IPR= ipr::where("id_escenario", $id)->first();

                        if($request->input("fluido") == "1")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                            $IPR->factor_dano = null;
                            $IPR->bsw = $request->input("bsw");
                            $IPR->gor = null;
                            $IPR->tasa_flujo = $request->input("tasa_flujo");
                            $IPR->presion_fondo = $request->input("presion_fondo");
                            $IPR->presion_inicial = $request->input("presion_inicial");
                            $IPR->permeabilidad_abs_ini = $request->input("permeabilidad_abs_ini");           
                            $IPR->espesor_reservorio = $request->input("espesor_reservorio"); 
                            $IPR->modulo_permeabilidad = $request->input("modulo_permeabilidad");
                            $IPR->permeabilidad = $request->input("permeabilidad");
                            $IPR->porosidad = $request->input("porosidad");
                            $IPR->tipo_roca = $request->input("tipo_roca");

                            $IPR->end_point_kr_aceite_gas = $request->input("end_point_kr_aceite_gas");
                            $IPR->saturacion_gas_crit = $request->input("saturacion_gas_crit");
                            $IPR->end_point_kr_gas = $request->input("end_point_kr_gas");
                            $IPR->saturacion_aceite_irred_gas = $request->input("saturacion_aceite_irred_gas");
                            $IPR->saturacion_aceite_irred_gas = $request->input("saturacion_aceite_irred_gas");
                            $IPR->exponente_corey_aceite_gas = $request->input("exponente_corey_aceite_gas");
                            $IPR->exponente_corey_gas = $request->input("exponente_corey_gas");
                            $IPR->end_point_kr_petroleo = $request->input("end_point_kr_petroleo");
                            $IPR->saturacion_agua_irred = $request->input("saturacion_agua_irred");
                            $IPR->end_point_kr_agua = $request->input("end_point_kr_agua");
                            $IPR->saturacion_aceite_irred = $request->input("saturacion_aceite_irred");
                            $IPR->exponente_corey_petroleo = $request->input("exponente_corey_petroleo");
                            $IPR->exponente_corey_agua = $request->input("exponente_corey_agua");
                            $IPR->viscosidad_agua = $request->input("viscosidad_agua");
                            $IPR->saturation_pressure = $request->input("presion_saturacion");
                            $IPR->campo_a1 = $request->input("campo_a1");
                            $IPR->campo_b1 = $request->input("campo_b1");
                            $IPR->campo_c1 = $request->input("campo_c1");
                            $IPR->campo_d1 = $request->input("campo_d1");
                            $IPR->campo_a2 = $request->input("campo_a2");
                            $IPR->campo_b2 = $request->input("campo_b2");
                            $IPR->campo_c2 = $request->input("campo_c2");
                            $IPR->campo_d2 = $request->input("campo_d2");

                            $IPR->skin_g= null;
                            $IPR->gas_rate_g= null;
                            $IPR->bhp_g= null;
                            $IPR->init_res_press_text_g= null;
                            $IPR->abs_perm_init_text_g= null;
                            $IPR->net_pay_text_g= null;
                            $IPR->permeability_module_text_g= null;
                            $IPR->abs_perm_text_g= null;
                            $IPR->porosity_text_g= null;
                            $IPR->rock_type= null;
                            $IPR->temperature_text_g= null;
                            $IPR->c1_viscosity_fluid_g= null;
                            $IPR->c2_viscosity_fluid_g= null;
                            $IPR->c3_viscosity_fluid_g= null;
                            $IPR->c4_viscosity_fluid_g= null;
                            $IPR->c1_compressibility_fluid_g= null;
                            $IPR->c2_compressibility_fluid_g= null;
                            $IPR->c3_compressibility_fluid_g= null;
                            $IPR->c4_compressibility_fluid_g= null;

                            $IPR->id_escenario=$_SESSION['scenary_id'];
                            $attributtes = array();
                            
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }


                            $IPR->save();
                        }
                        else if($request->input("fluido") == "2")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");

                            $IPR->factor_dano = null;
                            $IPR->bsw = null;
                            $IPR->gor = null;
                            $IPR->tasa_flujo = null;
                            $IPR->presion_fondo = null;
                            $IPR->presion_inicial = null;
                            $IPR->permeabilidad_abs_ini = null;           
                            $IPR->espesor_reservorio = null; 
                            $IPR->modulo_permeabilidad = null;
                            $IPR->permeabilidad = null;
                            $IPR->porosidad = null;
                            $IPR->tipo_roca = null;
                            $IPR->end_point_kr_aceite_gas = null;
                            $IPR->saturacion_gas_crit = null;
                            $IPR->end_point_kr_gas = null;
                            $IPR->saturacion_aceite_irred_gas = null;
                            $IPR->saturacion_aceite_irred_gas = null;
                            $IPR->exponente_corey_aceite_gas = null;
                            $IPR->exponente_corey_gas = null;
                            $IPR->end_point_kr_petroleo = null;
                            $IPR->saturacion_agua_irred = null;
                            $IPR->end_point_kr_agua = null;
                            $IPR->saturacion_aceite_irred = null;
                            $IPR->exponente_corey_petroleo = null;
                            $IPR->exponente_corey_agua = null;
                            $IPR->viscosidad_agua = null;
                            $IPR->campo_a1 = null;
                            $IPR->campo_b1 = null;
                            $IPR->campo_c1 = null;
                            $IPR->campo_d1 = null;
                            $IPR->campo_a2 = null;
                            $IPR->campo_b2 = null;
                            $IPR->campo_c2 = null;
                            $IPR->campo_d2 = null;

                            $IPR->skin_g= null;
                            $IPR->gas_rate_g= $request->input("gas_rate_g");
                            $IPR->bhp_g= $request->input("bhp_g");
                            $IPR->init_res_press_text_g= $request->input("init_res_press_text_g");
                            $IPR->abs_perm_init_text_g= $request->input("abs_perm_init_text_g");
                            $IPR->net_pay_text_g= $request->input("net_pay_text_g");
                            $IPR->permeability_module_text_g= $request->input("permeability_module_text_g");
                            $IPR->abs_perm_text_g= $request->input("abs_perm_text_g");
                            $IPR->porosity_text_g= $request->input("porosity_text_g");
                            $IPR->rock_type= $request->input("rock_type");
                            $IPR->temperature_text_g= $request->input("temperature_text_g");
                            $IPR->c1_viscosity_fluid_g= $request->input("c1_viscosity_fluid_g");
                            $IPR->c2_viscosity_fluid_g= $request->input("c2_viscosity_fluid_g");
                            $IPR->c3_viscosity_fluid_g= $request->input("c3_viscosity_fluid_g");
                            $IPR->c4_viscosity_fluid_g= $request->input("c4_viscosity_fluid_g");
                            $IPR->c1_compressibility_fluid_g= $request->input("c1_compressibility_fluid_g");
                            $IPR->c2_compressibility_fluid_g= $request->input("c2_compressibility_fluid_g");
                            $IPR->c3_compressibility_fluid_g= $request->input("c3_compressibility_fluid_g");
                            $IPR->c4_compressibility_fluid_g= $request->input("c4_compressibility_fluid_g");

                            $IPR->id_escenario=$_SESSION['scenary_id'];
                            $attributtes = array();
                            
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }

                            $IPR->save();
                        }
                        else if($request->input("fluido") == "3")
                        {
                            #dd($request->input());
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                            $IPR->gas_rate_c_g = $request->input("gas_rate_c_g");
                            $IPR->bhp_c_g = $request->input("bhp_c_g");
                            $IPR->initial_pressure_c_g = $request->input("presion_inicial_c_g");
                            $IPR->ini_abs_permeability_c_g = $request->input("permeabilidad_abs_ini_c_g");
                            $IPR->netpay_c_g = $request->input("espesor_reservorio_c_g");
                            $IPR->permeability_module_c_g = $request->input("modulo_permeabilidad_c_g");
                            $IPR->permeability_c_g = $request->input("permeabilidad_c_g");
                            $IPR->porosity_c_g = $request->input("porosidad_c_g");
                            $IPR->rock_type_c_g = $request->input("tipo_roca_c_g");
                            $IPR->saturation_pressure_c_g = $request->input("presion_saturacion_c_g");
                            $IPR->gor_c_g = $request->input("gor_c_g");
                            $IPR->id_escenario=$_SESSION['scenary_id'];

                            $attributtes = array();
                            
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }

                            $IPR->save();
                        }

                        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
                        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
                        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
                        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
                        $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');

                        
                        // Delete and recreate
                        DB::table('ipr_tabla')->where('id_ipr', $IPR->id)->delete();
                        DB::table('ipr_tabla_gas')->where('id_ipr', $IPR->id)->delete();
                        DB::table('ipr_tabla_water')->where('id_ipr', $IPR->id)->delete();

                        DB::table('ipr_pvt_gas')->where('id_ipr', $IPR->id)->delete();
                        DB::table('ipr_resultados')->where('id_ipr', $IPR->id)->delete();
                        DB::table('ipr_resultados_skin_ideal')->where('id_ipr', $IPR->id)->delete();

                        DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->delete();
                        DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->delete();
                        DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->delete();

                        $_SESSION['ipr'] = $IPR->id;
                        $_SESSION['for_id'] = $IPR->formation_id;
                        $_SESSION['fie_id'] = $IPR->field_id;
                        $_SESSION['we_id'] = $IPR->well_id;

                        $scenaryId= DB::table('ipr')->where('id_escenario', $IPR->id_escenario)->pluck('id_escenario');
                        $scenary = escenario::find($scenaryId);
                        $scenary->completo=1;
                        $scenary->save();   
               

                        //Input Data

                        if($request->input("fluido")=="1")
                        {

                            //Pvt - ipr_tabla
                            $IPR_TABLA = new ipr_tabla;
                            $tabla = str_replace(",[null,null,null,null]","",$request->input("presiones_table"));
                            $presionesv = array();
                            $viscosidades_aceite= array();
                            $factores_vol_aceite = array();
                            $viscosidades_agua= array();
                            $tabla = json_decode($tabla);
                            foreach ($tabla as $value) {
                                $IPR_TABLA = new ipr_tabla;
                                $IPR_TABLA->presion = str_replace(",", ".", $value[0]);
                                $presionesv[] = (int)str_replace(",", ".", $value[0]);
                                $IPR_TABLA->viscosidad_aceite = str_replace(",",".",$value[1]);
                                $viscosidades_aceite[] = (float)str_replace(",", ".", $value[1]);
                                $IPR_TABLA->factor_volumetrico_aceite = str_replace(",",".",$value[2]);
                                $factores_vol_aceite[] = (float)str_replace(",", ".", $value[2]);
                                $IPR_TABLA->viscosidad_agua = str_replace(",",".",$value[3]);
                                $viscosidades_agua[] = (float)str_replace(",", ".", $value[3]);
                                $IPR_TABLA->id_ipr=$IPR->id;
                                $IPR_TABLA->save();
                            }

                            $presiones = json_encode($tabla);
                            
                            if ($this->allZeroes($presionesv)) {
                                $presionesv = [0];
                            }

                            if ($this->allZeroes($viscosidades_aceite)) {
                                $viscosidades_aceite = [0];
                            }

                            if ($this->allZeroes($factores_vol_aceite)) {
                                $factores_vol_aceite = [0];
                            }

                            if ($this->allZeroes($viscosidades_agua)) {
                                $viscosidades_agua = [0];
                            }

                            //Water-oil - ipr_tabla_water

                            $i_tables = 0;
                        
                            $tabla_wateroil = json_decode(str_replace(",[null,null,null]","",$request->input("wateroil_hidden")));
                        
                            $lista_sw = array();
                            $lista_krw = array();
                            $lista_kro = array();
                            foreach ($tabla_wateroil as $value) {
                                $i_tables++;
                                $IPR_TABLA_WATEROIL = new ipr_tabla_wateroil;
                                $IPR_TABLA_WATEROIL->lista_sw = str_replace(",", ".", $value[0]);
                                $lista_sw[] = (float)$IPR_TABLA_WATEROIL->lista_sw;
                                $IPR_TABLA_WATEROIL->lista_krw = str_replace(",", ".", $value[1]);
                                $lista_krw[] = (float)$IPR_TABLA_WATEROIL->lista_krw;
                                $IPR_TABLA_WATEROIL->lista_kro = str_replace(",", ".", $value[2]);
                                $lista_kro[] = (float)$IPR_TABLA_WATEROIL->lista_kro;
                                $IPR_TABLA_WATEROIL->id_ipr=$IPR->id;
                                $IPR_TABLA_WATEROIL->save();
                            }
                            
                            if ($this->allZeroes($lista_sw)) {
                                $i_tables = 0;
                                $lista_sw = [0];
                            }

                            if ($this->allZeroes($lista_krw)) {
                                $i_tables = 0;
                                $lista_krw = [0];
                            }

                            if ($this->allZeroes($lista_kro)) {
                                $i_tables = 0;
                                $lista_kro = [0];
                            }
                            
                            $lista_kro = json_encode($lista_kro);
                            $lista_krw = json_encode($lista_krw);
                            $lista_sw = json_encode($lista_sw);

                            //Gas-Oil - ipr_tabla_gas

                            $tabla_gasliquid = json_decode(str_replace(",[null,null,null]","",$request->input("gasliquid_hidden")));
                            $lista_sg = array();
                            $lista_krg = array();
                            $lista_krosg = array();
                            foreach ($tabla_gasliquid as $value) {
                                $i_tables++;
                                $IPR_TABLA_GASLIQUID = new ipr_tabla_gasliquid;
                                $IPR_TABLA_GASLIQUID->lista_sg = str_replace(",", ".", $value[0]);
                                $lista_sg[] = (float)$IPR_TABLA_GASLIQUID->lista_sg;
                                $IPR_TABLA_GASLIQUID->lista_krg = str_replace(",", ".", $value[1]);
                                $lista_krg[] = (float)$IPR_TABLA_GASLIQUID->lista_krg;
                                $IPR_TABLA_GASLIQUID->lista_krosg = str_replace(",", ".", $value[2]);
                                $lista_krosg[] = (float)$IPR_TABLA_GASLIQUID->lista_krosg;
                                $IPR_TABLA_GASLIQUID->id_ipr=$IPR->id;
                                $IPR_TABLA_GASLIQUID->save();
                            }
                            
                            if ($this->allZeroes($lista_sg)) {
                                $i_tables = 0;
                                $lista_sg = [0];
                            }

                            if ($this->allZeroes($lista_krg)) {
                                $i_tables = 0;
                                $lista_krg = [0];
                            }

                            if ($this->allZeroes($lista_krosg)) {
                                $i_tables = 0;
                                $lista_krosg = [0];
                            }
                            $lista_krosg = json_encode($lista_krosg);
                            $lista_krg = json_encode($lista_krg);
                            $lista_sg = json_encode($lista_sg);



                            //Input Data
                            $input_data = 
                            [
                                "presiones" => json_encode($presionesv),
                                "viscosidades_aceite" => json_encode($viscosidades_aceite),
                                "factores_vol_aceite" => json_encode($factores_vol_aceite),
                                "viscosidades_agua" => json_encode($viscosidades_agua),
                                "fluido" => $IPR->fluido,
                                "radio_pozo" => $IPR->radio_pozo,
                                "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                                "presion_yacimiento" => $IPR->presion_yacimiento,
                                "factor_dano" => "-1",
                                "bsw" => $IPR->bsw,
                                #"gor" => $IPR->gor,
                                "tasa_flujo" => $IPR->tasa_flujo,
                                "presion_fondo" => $IPR->presion_fondo,
                                "presion_inicial" => $IPR->presion_inicial,
                                "permeabilidad_abs_ini" => $IPR->permeabilidad_abs_ini,
                                "espesor_reservorio" => $IPR->espesor_reservorio,
                                "modulo_permeabilidad" => $IPR->modulo_permeabilidad,
                                "permeabilidad" => $IPR->permeabilidad,
                                "porosidad" => $IPR->porosidad,
                                "tipo_roca" => $IPR->tipo_roca,
                                "end_point_kr_aceite_gas" => $IPR->end_point_kr_aceite_gas,
                                "saturacion_gas_crit" => $IPR->saturacion_gas_crit,
                                "end_point_kr_gas" => $IPR->end_point_kr_gas,
                                "saturacion_aceite_irred_gas" => $IPR->saturacion_aceite_irred_gas,
                                "exponente_corey_aceite_gas" => $IPR->exponente_corey_aceite_gas, 
                                "exponente_corey_gas" => $IPR->exponente_corey_gas,
                                "end_point_kr_petroleo" => $IPR->end_point_kr_petroleo,
                                "saturacion_agua_irred" => $IPR->saturacion_agua_irred,                
                                "end_point_kr_agua" => $IPR->end_point_kr_agua,
                                "saturacion_aceite_irred" => $IPR->saturacion_aceite_irred,
                                "exponente_corey_petroleo" => $IPR->exponente_corey_petroleo,
                                "exponente_corey_agua" => $IPR->exponente_corey_agua,
                                "viscosidad_agua" => $IPR->viscosidad_agua,
                                "saturation_pressure" => $IPR->saturation_pressure,
                                "campo_a1" => $IPR->campo_a1,
                                "campo_b1" => $IPR->campo_b1,
                                "campo_c1" => $IPR->campo_c1,
                                "campo_d1" => $IPR->campo_d1,
                                "campo_a2" => $IPR->campo_a2,
                                "campo_b2" => $IPR->campo_b2,
                                "campo_c2" => $IPR->campo_c2,
                                "campo_d2" => $IPR->campo_d2,
                                "lista_sg" => $lista_sg, #Gas-oil
                                "lista_krg" => $lista_krg,
                                "lista_krosg" => $lista_krosg,
                                "lista_sw" => $lista_sw, #Water-oil
                                "lista_krw" => $lista_krw,
                                "lista_kro" => $lista_kro
                             ];
                        }
                        else if($request->input("fluido")=="2")
                        {

                            //PVT ipr Gas
                            $pvt_gas_ipr = json_decode(str_replace(",[null,null,null]","",$request->input("pvt_gas_ipr")));
                            $pvt_pressure_gas = array();
                            $pvt_gasViscosity_gas = array();
                            $pvt_gasCompressibility_gas = array();
                            foreach ($pvt_gas_ipr as $value) {
                                $ipr_pvt_gas = new ipr_pvt_gas;
                                $ipr_pvt_gas->pressure = str_replace(",", ".", $value[0]);
                                $pvt_pressure_gas[] = (float)$ipr_pvt_gas->pressure;

                                $ipr_pvt_gas->gas_viscosity = str_replace(",", ".", $value[1]);
                                $pvt_gasViscosity_gas[] = (float)$ipr_pvt_gas->gas_viscosity;

                                $ipr_pvt_gas->gas_compressibility_factor = str_replace(",", ".", $value[2]);
                                $pvt_gasCompressibility_gas[] = (float)$ipr_pvt_gas->gas_compressibility_factor;

                                $ipr_pvt_gas->id_ipr=$IPR->id;
                                $ipr_pvt_gas->save();
                            }
                            
                            if ($this->allZeroes($pvt_pressure_gas)) {
                                $pvt_pressure_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasViscosity_gas)) {
                                $pvt_gasViscosity_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasCompressibility_gas)) {
                                $pvt_gasCompressibility_gas = [0];
                            }
                            $pvt_pressure_gas = json_encode($pvt_pressure_gas);
                            $pvt_gasViscosity_gas = json_encode($pvt_gasViscosity_gas);
                            $pvt_gasCompressibility_gas = json_encode($pvt_gasCompressibility_gas);

                            $input_data = 
                            [

                                "fluido" => $IPR->fluido,
                                "radio_pozo" => $IPR->radio_pozo,
                                "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                                "presion_yacimiento" => $IPR->presion_yacimiento,
                                "factor_dano" => "-1",
                                "gas_rate_g" => $IPR->gas_rate_g,
                                "bhp_g" => $IPR->bhp_g,
                                "init_res_press_text_g" => $IPR->init_res_press_text_g,
                                "abs_perm_init_text_g" => $IPR->abs_perm_init_text_g,
                                "net_pay_text_g" => $IPR->net_pay_text_g,
                                "permeability_module_text_g" => $IPR->permeability_module_text_g,
                                "abs_perm_text_g" => $IPR->abs_perm_text_g,
                                "porosity_text_g" => $IPR->porosity_text_g,
                                "rock_type" => $IPR->rock_type,
                                "temperature_text_g" => $IPR->temperature_text_g,
                                "c1_viscosity_fluid_g" => $IPR->c1_viscosity_fluid_g,
                                "c2_viscosity_fluid_g" => $IPR->c2_viscosity_fluid_g,
                                "c3_viscosity_fluid_g" => $IPR->c3_viscosity_fluid_g,
                                "c4_viscosity_fluid_g" => $IPR->c4_viscosity_fluid_g,
                                "c1_compressibility_fluid_g" => $IPR->c1_compressibility_fluid_g,
                                "c2_compressibility_fluid_g" => $IPR->c2_compressibility_fluid_g, 
                                "c3_compressibility_fluid_g" => $IPR->c3_compressibility_fluid_g,
                                "c4_compressibility_fluid_g" => $IPR->c4_compressibility_fluid_g,
                                "pvt_pressure_gas" => $pvt_pressure_gas, #Pvt-Gas
                                "pvt_gasviscosity_gas" => $pvt_gasViscosity_gas,
                                "pvt_gascompressibility_gas" => $pvt_gasCompressibility_gas,
                            ];
                        }

                        else if($request->input("fluido") == "3")
                        {
                            #dd($request->input());
                            //Kr gas oil - Condensate gas
                            $kr_c_g_table_data = str_replace(",[null,null,null]","",$request->input("gas_oil_kr_cg"));
                            $sg_data = array();
                            $krg_data = array();
                            $krog_data = array();
                            $kr_c_g_table_data = json_decode($kr_c_g_table_data);
                            foreach ($kr_c_g_table_data as $value) 
                            {
                                $gas_oil_kr_c_g = new ipr_gas_oil_kr_c_g; 

                                $gas_oil_kr_c_g->sg = str_replace(",", ".", $value[0]); 
                                $gas_oil_kr_c_g->ipr_id = $IPR->id; 
                                $sg_data[] = (float)$gas_oil_kr_c_g->sg;

                                $gas_oil_kr_c_g->krg = str_replace(",", ".", $value[1]);
                                $krg_data[] = (float)$gas_oil_kr_c_g->krg; 

                                $gas_oil_kr_c_g->krog = str_replace(",", ".", $value[2]); 

                                $gas_oil_kr_c_g->save(); 

                                $krog_data[] = (float)$gas_oil_kr_c_g->krog;
                            }

                            $sg_data = json_encode($sg_data);
                            $krg_data = json_encode($krg_data);
                            $krog_data = json_encode($krog_data);

                            //PVT Condensate Gas
                            $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));
                            
                            $pressure_data = array();
                            $bo_data = array();
                            $vo_data = array();
                            $rs_data = array();
                            $bg_data = array();
                            $vg_data = array();
                            $og_ratio_data = array();
                            $pvt_c_g_table_data = json_decode($pvt_c_g_table_data);
                            foreach ($pvt_c_g_table_data as $value) 
                            {
                                $pvt_cg = new ipr_pvt_c_g;
                                $pvt_cg->ipr_id = $IPR->id;
                                $pvt_cg->pressure = str_replace(",", ".", $value[0]);
                                $pvt_cg->bo = str_replace(",", ".", $value[1]);
                                $pvt_cg->vo = str_replace(",", ".", $value[2]);
                                $pvt_cg->rs = str_replace(",", ".", $value[3]);
                                $pvt_cg->bg = str_replace(",", ".", $value[4]);
                                $pvt_cg->vg = str_replace(",", ".", $value[5]);
                                $pvt_cg->og_ratio = str_replace(",", ".", $value[6]);

                                $pvt_cg->save();

                                $pressure_data[] = (float)$pvt_cg->pressure; 
                                $bo_data[] = (float)$pvt_cg->bo; 
                                $vo_data[] = (float)$pvt_cg->vo; 
                                $rs_data[] = (float)$pvt_cg->rs; 
                                $bg_data[] = (float)$pvt_cg->bg; 
                                $vg_data[] = (float)$pvt_cg->vg; 
                                $og_ratio_data[] = (float)$pvt_cg->og_ratio; 
                            }

                            $pressure_data = json_encode($pressure_data);
                            $bo_data = json_encode($bo_data);
                            $vo_data = json_encode($vo_data);
                            $rs_data = json_encode($rs_data);
                            $bg_data = json_encode($bg_data);
                            $vg_data = json_encode($vg_data);
                            $og_ratio_data = json_encode($og_ratio_data);


                            //Drop out Condensate Gas
                            $dropout_c_g_table_data = str_replace(",[null,null]","",$request->input("dropout_cg"));

                            $dropout_pressure_data = array();
                            $dropout_liquid_percentage = array();
                            $dropout_c_g_table_data = json_decode($dropout_c_g_table_data);

                            foreach ($dropout_c_g_table_data as $value) 
                            {
                                $dropout_c_g = new ipr_dropout_c_g;
                                $dropout_c_g->ipr_id = $IPR->id;
                                $dropout_c_g->pressure = str_replace(",",".", $value[0]);
                                $dropout_c_g->liquid_percentage = str_replace(",",".", $value[1]);

                                $dropout_c_g->save();

                                $dropout_pressure_data[] = $dropout_c_g->pressure;
                                $dropout_liquid_percentage[] = $dropout_c_g->liquid_percentage;
                            }

                            $dropout_pressure_data = json_encode($dropout_pressure_data);
                            $dropout_liquid_percentage = json_encode($dropout_liquid_percentage);

                            $input_data =
                            [
                                "fluido" => $IPR->fluido,
                                "well_radius" => $IPR->radio_pozo,
                                "drainage_radius" => $IPR->radio_drenaje_yac,
                                "reservoir_pressure" => $IPR->presion_yacimiento,
                                "gas_rate_c_g" => $IPR->gas_rate_c_g,
                                "bhp_c_g" => $IPR->bhp_c_g,
                                "initial_pressure_c_g" => $IPR->initial_pressure_c_g,
                                "ini_abs_permeability_c_g" => $IPR->ini_abs_permeability_c_g,
                                "netpay_c_g" => $IPR->netpay_c_g,
                                "permeability_module_c_g" => $IPR->permeability_module_c_g,
                                "permeability_c_g" => $IPR->permeability_c_g,
                                "porosity_c_g" => $IPR->porosity_c_g,
                                "rock_type_c_g" => $IPR->rock_type_c_g,
                                "saturation_pressure_c_g" => $IPR->saturation_pressure_c_g,
                                "gor_c_g" => $IPR->gor_c_g,
                                "sg_data" => $sg_data,
                                "krg_data" => $krg_data,
                                "krog_data" => $krog_data,
                                "pressure_data" => $pressure_data,
                                "bo_data" => $bo_data,
                                "vo_data" => $vo_data,
                                "rs_data" => $rs_data,
                                "bg_data" => $bg_data,
                                "vg_data" => $vg_data,
                                "og_ratio_data" => $og_ratio_data,
                                "dropout_pressure_data" => $dropout_pressure_data,
                                "dropout_liquid_percentage" => $dropout_liquid_percentage,
                                "factor_dano" => -1,
                            ];
                        }
                        
                        #Cálculo módulo IPR
                        $ipr_function_results = json_decode($this->run_ipr($input_data));

                        #Captura de resultados y tratamiento de datos
                        
                        $ipr_resultados = array();
                        $categorias = array();
                        $eje_y = array();

                        $data = array();

                        $tasa_flujo_resultados = $ipr_function_results[0];
                        $presion_fondo_resultados = $ipr_function_results[1];
                        $skin_resultados = $ipr_function_results[2];

                        for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) 
                        { 
                            $ipr_resultado = new ipr_resultado;
                            $ipr_resultado->skin = $skin_resultados;
                            $ipr_resultado->tasa_flujo = floatval($tasa_flujo_resultados[$i]);
                            $ipr_resultado->presion_fondo = floatval($presion_fondo_resultados[$i]);
                            $ipr_resultado->id_ipr = $IPR->id;
                            
                            $data[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3)];

                            $categorias[] = round($ipr_resultado->tasa_flujo,3);
                            $eje_y[] = round($ipr_resultado->presion_fondo,3);
                            
                            $ipr_resultados[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3), $ipr_resultado->skin];
                            
                            $ipr_resultado->save();   

                            $skin = floatval($skin_resultados);
                            $skin_tmp = floatval($skin_resultados);
                        }

                        $categorias = json_encode($categorias);
                        $eje_y = json_encode($eje_y);
                        $data = json_encode($data);

                        $request->session()->flash('mensaje', 'Record successfully entered.');
                        
                        if($request->input("fluido")=="1")
                        {
                            $tasa_flujo = $IPR->tasa_flujo;
                            $presion_fondo = $IPR->presion_fondo;
                            $tipo_roca = $IPR->tipo_roca;
                        }
                        else if($request->input("fluido")=="2")
                        {
                            $tasa_flujo = $IPR->gas_rate_g;
                            $presion_fondo = $IPR->bhp_g;
                            $tipo_roca = $IPR->rock_type;
                        }
                        else if($request->input("fluido")=="3")
                        {
                            $tasa_flujo = $IPR->gas_rate_c_g;
                            $presion_fondo = $IPR->bhp_c_g;
                            $tipo_roca = $IPR->rock_type_c_g;
                        }
                        
                        
                        #Cálculo IPR Base, skin = 0

                                               $input_data["factor_dano"] = 0;
                                               $ipr_function_results = json_decode($this->run_ipr($input_data));

                                               #Captura de resultados y tratamiento de datos
                                               
                                               $categorias_i = array();
                                               $eje_yi = array();

                                               $data_i = array();

                                               $tasa_flujo_resultados = $ipr_function_results[0];
                                               $presion_fondo_resultados = $ipr_function_results[1];
                                               $skin_resultados = $ipr_function_results[2];

                                               for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) 
                                               { 
                                                   $ipr_resultado = new ipr_resultado_skin_ideal;
                                                   $ipr_resultado->skin = $skin_resultados;
                                                   $ipr_resultado->tasa_flujo = floatval($tasa_flujo_resultados[$i]);
                                                   $ipr_resultado->presion_fondo = floatval($presion_fondo_resultados[$i]);
                                                   $ipr_resultado->id_ipr = $IPR->id;
                                                   
                                                   $data_i[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3)];

                                                   $categorias_i[] = round($ipr_resultado->tasa_flujo,3);
                                                   $eje_yi[] = round($ipr_resultado->presion_fondo,3);
                                                   
                                                   $ipr_resultados[] = [round($ipr_resultado->tasa_flujo,3), round($ipr_resultado->presion_fondo,3), $ipr_resultado->skin];
                                                   
                                                   $ipr_resultado->save();   
                                               }

                                               $categorias_i = json_encode($categorias_i);
                                               $eje_yi = json_encode($eje_yi);
                                               $data_i = json_encode($data_i);                  
                                               
                                               $skin = $skin_tmp;
                                               
                        $escenario = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                        $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
                        $desagregacion =  DB::table('desagregacion')->where('id_escenario', $IPR->id_escenario)->orderBy('created_at', 'desc')->first();
                        $scenaryId = \Request::get('scenaryId');
                        $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                        $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
                        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();

                        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
                        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
                        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
                        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
                        $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');

                        #dd($categorias);
                        return view('IPRResults', compact('user','campo', 'IPR', 'ipr_resultados' , 'pozo', 'formacion','fluido', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca', 'data_i','desagregacion', 'i_tables','scenary_s','intervalo'));
                        }   
                    }
                    else if($request->input("accion") == "Save")
                    {
                        
                        $IPR= ipr::where("id_escenario", $id)->first();

                        // Delete and recreate
                        DB::table('ipr_tabla')->where('id_ipr', $IPR->id)->delete();
                        DB::table('ipr_tabla_gas')->where('id_ipr', $IPR->id)->delete();
                        DB::table('ipr_tabla_water')->where('id_ipr', $IPR->id)->delete();
                        DB::table('ipr_resultados')->where('id_ipr', $IPR->id)->delete();

                        DB::table('ipr_pvt_gas')->where('id_ipr', $IPR->id)->delete();

                        //Condesate gas
                        DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->delete();
                        DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->delete();
                        DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->delete();


                        if($request->input("fluido") == "1")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                            $IPR->factor_dano = null;
                            $IPR->bsw = $request->input("bsw");
                            $IPR->gor = null;
                            $IPR->tasa_flujo = $request->input("tasa_flujo");
                            $IPR->presion_fondo = $request->input("presion_fondo");
                            $IPR->presion_inicial = $request->input("presion_inicial");
                            $IPR->permeabilidad_abs_ini = $request->input("permeabilidad_abs_ini");           
                            $IPR->espesor_reservorio = $request->input("espesor_reservorio"); 
                            $IPR->modulo_permeabilidad = $request->input("modulo_permeabilidad");
                            $IPR->permeabilidad = $request->input("permeabilidad");
                            $IPR->porosidad = $request->input("porosidad");
                            $IPR->tipo_roca = $request->input("tipo_roca");
                            $IPR->end_point_kr_aceite_gas = $request->input("end_point_kr_aceite_gas");
                            $IPR->saturacion_gas_crit = $request->input("saturacion_gas_crit");
                            $IPR->end_point_kr_gas = $request->input("end_point_kr_gas");
                            $IPR->saturacion_aceite_irred_gas = $request->input("saturacion_aceite_irred_gas");
                            $IPR->saturacion_aceite_irred_gas = $request->input("saturacion_aceite_irred_gas");
                            $IPR->exponente_corey_aceite_gas = $request->input("exponente_corey_aceite_gas");
                            $IPR->exponente_corey_gas = $request->input("exponente_corey_gas");
                            $IPR->end_point_kr_petroleo = $request->input("end_point_kr_petroleo");
                            $IPR->saturacion_agua_irred = $request->input("saturacion_agua_irred");
                            $IPR->end_point_kr_agua = $request->input("end_point_kr_agua");
                            $IPR->saturacion_aceite_irred = $request->input("saturacion_aceite_irred");
                            $IPR->exponente_corey_petroleo = $request->input("exponente_corey_petroleo");
                            $IPR->exponente_corey_agua = $request->input("exponente_corey_agua");
                            $IPR->viscosidad_agua = $request->input("viscosidad_agua");
                            $IPR->saturation_pressure = $request->input("presion_saturacion");
                            $IPR->campo_a1 = $request->input("campo_a1");
                            $IPR->campo_b1 = $request->input("campo_b1");
                            $IPR->campo_c1 = $request->input("campo_c1");
                            $IPR->campo_d1 = $request->input("campo_d1");
                            $IPR->campo_a2 = $request->input("campo_a2");
                            $IPR->campo_b2 = $request->input("campo_b2");
                            $IPR->campo_c2 = $request->input("campo_c2");
                            $IPR->campo_d2 = $request->input("campo_d2");
                            $IPR->skin_g= null;
                            $IPR->gas_rate_g= null;
                            $IPR->bhp_g= null;
                            $IPR->init_res_press_text_g= null;
                            $IPR->abs_perm_init_text_g= null;
                            $IPR->net_pay_text_g= null;
                            $IPR->permeability_module_text_g= null;
                            $IPR->abs_perm_text_g= null;
                            $IPR->porosity_text_g= null;
                            $IPR->rock_type= null;
                            $IPR->temperature_text_g= null;
                            $IPR->c1_viscosity_fluid_g= null;
                            $IPR->c2_viscosity_fluid_g= null;
                            $IPR->c3_viscosity_fluid_g= null;
                            $IPR->c4_viscosity_fluid_g= null;
                            $IPR->c1_compressibility_fluid_g= null;
                            $IPR->c2_compressibility_fluid_g= null;
                            $IPR->c3_compressibility_fluid_g= null;
                            $IPR->c4_compressibility_fluid_g= null;
                            $IPR->id_escenario=$_SESSION['scenary_id'];

                            $attributtes = array();
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }

                            $IPR->save();

                            //Tablas
                            //Pvt - ipr_tabla
                            $IPR_TABLA = new ipr_tabla;
                            $tabla = str_replace(",[null,null,null,null]","",$request->input("presiones_table"));
                            $presionesv = array();
                            $viscosidades_aceite= array();
                            $factores_vol_aceite = array();
                            $viscosidades_agua= array();
                            $tabla = json_decode($tabla);
                            foreach ($tabla as $value) {
                                $IPR_TABLA = new ipr_tabla;
                                $IPR_TABLA->presion = str_replace(",", ".", $value[0]);
                                $presionesv[] = (int)str_replace(",", ".", $value[0]);
                                $IPR_TABLA->viscosidad_aceite = str_replace(",",".",$value[1]);
                                $viscosidades_aceite[] = (float)str_replace(",", ".", $value[1]);
                                $IPR_TABLA->factor_volumetrico_aceite = str_replace(",",".",$value[2]);
                                $factores_vol_aceite[] = (float)str_replace(",", ".", $value[2]);
                                $IPR_TABLA->viscosidad_agua = str_replace(",",".",$value[3]);
                                $viscosidades_agua[] = (float)str_replace(",", ".", $value[3]);
                                $IPR_TABLA->id_ipr=$IPR->id;
                                $IPR_TABLA->save();
                            }

                            $presiones = json_encode($tabla);
                            
                            if ($this->allZeroes($presionesv)) {
                                $presionesv = [0];
                            }

                            if ($this->allZeroes($viscosidades_aceite)) {
                                $viscosidades_aceite = [0];
                            }

                            if ($this->allZeroes($factores_vol_aceite)) {
                                $factores_vol_aceite = [0];
                            }

                            if ($this->allZeroes($viscosidades_agua)) {
                                $viscosidades_agua = [0];
                            }

                            //Water-oil - ipr_tabla_water

                            $i_tables = 0;
                            
                            $tabla_wateroil = json_decode(str_replace(",[null,null,null]","",$request->input("wateroil_hidden")));
                            
                            $lista_sw = array();
                            $lista_krw = array();
                            $lista_kro = array();
                            foreach ($tabla_wateroil as $value) {
                                $i_tables++;
                                $IPR_TABLA_WATEROIL = new ipr_tabla_wateroil;
                                $IPR_TABLA_WATEROIL->lista_sw = str_replace(",", ".", $value[0]);
                                $lista_sw[] = (float)$IPR_TABLA_WATEROIL->lista_sw;
                                $IPR_TABLA_WATEROIL->lista_krw = str_replace(",", ".", $value[1]);
                                $lista_krw[] = (float)$IPR_TABLA_WATEROIL->lista_krw;
                                $IPR_TABLA_WATEROIL->lista_kro = str_replace(",", ".", $value[2]);
                                $lista_kro[] = (float)$IPR_TABLA_WATEROIL->lista_kro;
                                $IPR_TABLA_WATEROIL->id_ipr=$IPR->id;
                                $IPR_TABLA_WATEROIL->save();
                            }
                            
                            if ($this->allZeroes($lista_sw)) {
                                $i_tables = 0;
                                $lista_sw = [0];
                            }

                            if ($this->allZeroes($lista_krw)) {
                                $i_tables = 0;
                                $lista_krw = [0];
                            }

                            if ($this->allZeroes($lista_kro)) {
                                $i_tables = 0;
                                $lista_kro = [0];
                            }
                            
                            $lista_kro = json_encode($lista_kro);
                            $lista_krw = json_encode($lista_krw);
                            $lista_sw = json_encode($lista_sw);

                            //Gas-Oil - ipr_tabla_gas

                            $tabla_gasliquid = json_decode(str_replace(",[null,null,null]","",$request->input("gasliquid_hidden")));
                            $lista_sg = array();
                            $lista_krg = array();
                            $lista_krosg = array();
                            foreach ($tabla_gasliquid as $value) {
                                $i_tables++;
                                $IPR_TABLA_GASLIQUID = new ipr_tabla_gasliquid;
                                $IPR_TABLA_GASLIQUID->lista_sg = str_replace(",", ".", $value[0]);
                                $lista_sg[] = (float)$IPR_TABLA_GASLIQUID->lista_sg;
                                $IPR_TABLA_GASLIQUID->lista_krg = str_replace(",", ".", $value[1]);
                                $lista_krg[] = (float)$IPR_TABLA_GASLIQUID->lista_krg;
                                $IPR_TABLA_GASLIQUID->lista_krosg = str_replace(",", ".", $value[2]);
                                $lista_krosg[] = (float)$IPR_TABLA_GASLIQUID->lista_krosg;
                                $IPR_TABLA_GASLIQUID->id_ipr=$IPR->id;
                                $IPR_TABLA_GASLIQUID->save();
                            }
                            
                            if ($this->allZeroes($lista_sg)) {
                                $i_tables = 0;
                                $lista_sg = [0];
                            }

                            if ($this->allZeroes($lista_krg)) {
                                $i_tables = 0;
                                $lista_krg = [0];
                            }

                            if ($this->allZeroes($lista_krosg)) {
                                $i_tables = 0;
                                $lista_krosg = [0];
                            }
                            $lista_krosg = json_encode($lista_krosg);
                            $lista_krg = json_encode($lista_krg);
                            $lista_sg = json_encode($lista_sg);

                        }
                        else if($request->input("fluido") == "2")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");

                            $IPR->factor_dano = null;
                            $IPR->bsw = null;
                            $IPR->gor = null;
                            $IPR->tasa_flujo = null;
                            $IPR->presion_fondo = null;
                            $IPR->presion_inicial = null;
                            $IPR->permeabilidad_abs_ini = null;           
                            $IPR->espesor_reservorio = null; 
                            $IPR->modulo_permeabilidad = null;
                            $IPR->permeabilidad = null;
                            $IPR->porosidad = null;
                            $IPR->tipo_roca = null;
                            $IPR->end_point_kr_aceite_gas = null;
                            $IPR->saturacion_gas_crit = null;
                            $IPR->end_point_kr_gas = null;
                            $IPR->saturacion_aceite_irred_gas = null;
                            $IPR->saturacion_aceite_irred_gas = null;
                            $IPR->exponente_corey_aceite_gas = null;
                            $IPR->exponente_corey_gas = null;
                            $IPR->end_point_kr_petroleo = null;
                            $IPR->saturacion_agua_irred = null;
                            $IPR->end_point_kr_agua = null;
                            $IPR->saturacion_aceite_irred = null;
                            $IPR->exponente_corey_petroleo = null;
                            $IPR->exponente_corey_agua = null;
                            $IPR->viscosidad_agua = null;
                            $IPR->campo_a1 = null;
                            $IPR->campo_b1 = null;
                            $IPR->campo_c1 = null;
                            $IPR->campo_d1 = null;
                            $IPR->campo_a2 = null;
                            $IPR->campo_b2 = null;
                            $IPR->campo_c2 = null;
                            $IPR->campo_d2 = null;

                            $IPR->skin_g= null;
                            $IPR->gas_rate_g= $request->input("gas_rate_g");
                            $IPR->bhp_g= $request->input("bhp_g");
                            $IPR->init_res_press_text_g= $request->input("init_res_press_text_g");
                            $IPR->abs_perm_init_text_g= $request->input("abs_perm_init_text_g");
                            $IPR->net_pay_text_g= $request->input("net_pay_text_g");
                            $IPR->permeability_module_text_g= $request->input("permeability_module_text_g");
                            $IPR->abs_perm_text_g= $request->input("abs_perm_text_g");
                            $IPR->porosity_text_g= $request->input("porosity_text_g");
                            $IPR->rock_type= $request->input("rock_type");
                            $IPR->temperature_text_g= $request->input("temperature_text_g");
                            $IPR->c1_viscosity_fluid_g= $request->input("c1_viscosity_fluid_g");
                            $IPR->c2_viscosity_fluid_g= $request->input("c2_viscosity_fluid_g");
                            $IPR->c3_viscosity_fluid_g= $request->input("c3_viscosity_fluid_g");
                            $IPR->c4_viscosity_fluid_g= $request->input("c4_viscosity_fluid_g");
                            $IPR->c1_compressibility_fluid_g= $request->input("c1_compressibility_fluid_g");
                            $IPR->c2_compressibility_fluid_g= $request->input("c2_compressibility_fluid_g");
                            $IPR->c3_compressibility_fluid_g= $request->input("c3_compressibility_fluid_g");
                            $IPR->c4_compressibility_fluid_g= $request->input("c4_compressibility_fluid_g");

                            $IPR->id_escenario=$_SESSION['scenary_id'];

                            //Tablas
                            //PVT ipr Gas
                            $pvt_gas_ipr = json_decode(str_replace(",[null,null,null]","",$request->input("pvt_gas_ipr")));
                            $pvt_pressure_gas = array();
                            $pvt_gasViscosity_gas = array();
                            $pvt_gasCompressibility_gas = array();
                            foreach ($pvt_gas_ipr as $value) {
                                $ipr_pvt_gas = new ipr_pvt_gas;
                                $ipr_pvt_gas->pressure = str_replace(",", ".", $value[0]);
                                $pvt_pressure_gas[] = (float)$ipr_pvt_gas->pressure;

                                $ipr_pvt_gas->gas_viscosity = str_replace(",", ".", $value[1]);
                                $pvt_gasViscosity_gas[] = (float)$ipr_pvt_gas->gas_viscosity;

                                $ipr_pvt_gas->gas_compressibility_factor = str_replace(",", ".", $value[2]);
                                $pvt_gasCompressibility_gas[] = (float)$ipr_pvt_gas->gas_compressibility_factor;

                                $ipr_pvt_gas->id_ipr=$IPR->id;
                                $ipr_pvt_gas->save();
                            }
                            
                            if ($this->allZeroes($pvt_pressure_gas)) {
                                $pvt_pressure_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasViscosity_gas)) {
                                $pvt_gasViscosity_gas = [0];
                            }

                            if ($this->allZeroes($pvt_gasCompressibility_gas)) {
                                $pvt_gasCompressibility_gas = [0];
                            }
                            $pvt_pressure_gas = json_encode($pvt_pressure_gas);
                            $pvt_gasViscosity_gas = json_encode($pvt_gasViscosity_gas);
                            $pvt_gasCompressibility_gas = json_encode($pvt_gasCompressibility_gas);
                        }
                        else if($request->input("fluido")=="3")
                        {
                            $IPR->fluido = $request->input("fluido");
                            $IPR->radio_pozo = $request->input("radio_pozo");
                            $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
                            $IPR->presion_yacimiento = $request->input("presion_yacimiento");
                            $IPR->gas_rate_c_g = $request->input("gas_rate_c_g");
                            $IPR->bhp_c_g = $request->input("bhp_c_g");
                            $IPR->initial_pressure_c_g = $request->input("presion_inicial_c_g");
                            $IPR->ini_abs_permeability_c_g = $request->input("permeabilidad_abs_ini_c_g");
                            $IPR->netpay_c_g = $request->input("espesor_reservorio_c_g");
                            $IPR->permeability_module_c_g = $request->input("modulo_permeabilidad_c_g");
                            $IPR->permeability_c_g = $request->input("permeabilidad_c_g");
                            $IPR->porosity_c_g = $request->input("porosidad_c_g");
                            $IPR->rock_type_c_g = $request->input("tipo_roca_c_g");
                            $IPR->saturation_pressure_c_g = $request->input("presion_saturacion_c_g");
                            $IPR->gor_c_g = $request->input("gor_c_g");
                            $IPR->id_escenario=$_SESSION['scenary_id'];

                            $attributtes = array();
                            
                            $tmp_attr = $IPR->toArray();
                            foreach($tmp_attr as $k => $v)
                            {
                                if($v == '')
                                {
                                    $v = null;
                                }
                                $attributtes[$k] = $v;
                            }
                            foreach($attributtes as $k => $v)
                            {
                                $IPR->$k = $v;
                            }

                            $IPR->save();

                            #Tablas
                            //Kr gas oil - Condensate gas
                            $kr_c_g_table_data = str_replace(",[null,null,null]","",$request->input("gas_oil_kr_cg"));
                            $sg_data = array();
                            $krg_data = array();
                            $krog_data = array();
                            $kr_c_g_table_data = json_decode($kr_c_g_table_data);
                            foreach ($kr_c_g_table_data as $value) 
                            {
                                $gas_oil_kr_c_g = new ipr_gas_oil_kr_c_g; 
                                $gas_oil_kr_c_g->ipr_id = $IPR->id;
                                $gas_oil_kr_c_g->sg = str_replace(",", ".", $value[0]); 
                                $sg_data[] = (float)$gas_oil_kr_c_g->sg;

                                $gas_oil_kr_c_g->krg = str_replace(",", ".", $value[1]);
                                $krg_data[] = (float)$gas_oil_kr_c_g->krg; 

                                $gas_oil_kr_c_g->krog = str_replace(",", ".", $value[2]); 
                                $krog_data[] = (float)$gas_oil_kr_c_g->krog;

                                $gas_oil_kr_c_g->save();
                            }

                            $sg_data = json_encode($sg_data);
                            $krg_data = json_encode($krg_data);
                            $krog_data = json_encode($krog_data);

                            //PVT Condensate Gas
                            $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));
                            
                            $pressure_data = array();
                            $bo_data = array();
                            $vo_data = array();
                            $rs_data = array();
                            $bg_data = array();
                            $vg_data = array();
                            $og_ratio_data = array();
                            $pvt_c_g_table_data = json_decode($pvt_c_g_table_data);
                            foreach ($pvt_c_g_table_data as $value) 
                            {
                                $pvt_cg = new ipr_pvt_c_g;
                                $pvt_cg->ipr_id = $IPR->id;
                                $pvt_cg->pressure = str_replace(",", ".", $value[0]);
                                $pvt_cg->bo = str_replace(",", ".", $value[1]);
                                $pvt_cg->vo = str_replace(",", ".", $value[2]);
                                $pvt_cg->rs = str_replace(",", ".", $value[3]);
                                $pvt_cg->bg = str_replace(",", ".", $value[4]);
                                $pvt_cg->vg = str_replace(",", ".", $value[5]);
                                $pvt_cg->og_ratio = str_replace(",", ".", $value[6]);

                                $pressure_data[] = (float)$pvt_cg->pressure; 
                                $bo_data[] = (float)$pvt_cg->bo; 
                                $vo_data[] = (float)$pvt_cg->vo; 
                                $rs_data[] = (float)$pvt_cg->rs; 
                                $bg_data[] = (float)$pvt_cg->bg; 
                                $vg_data[] = (float)$pvt_cg->vg; 
                                $og_ratio_data[] = (float)$pvt_cg->og_ratio; 

                                $pvt_cg->save();
                            }

                            $pressure_data = json_encode($pressure_data);
                            $bo_data = json_encode($bo_data);
                            $vo_data = json_encode($vo_data);
                            $rs_data = json_encode($rs_data);
                            $bg_data = json_encode($bg_data);
                            $vg_data = json_encode($vg_data);
                            $og_ratio_data = json_encode($og_ratio_data);


                            //Drop out Condensate Gas
                            $dropout_c_g_table_data = str_replace(",[null,null]","",$request->input("dropout_cg"));

                            $dropout_pressure_data = array();
                            $dropout_liquid_percentage = array();
                            $dropout_c_g_table_data = json_decode($dropout_c_g_table_data);

                            foreach ($dropout_c_g_table_data as $value) 
                            {
                                $dropout_c_g = new ipr_dropout_c_g;
                                
                                $dropout_c_g->ipr_id = $IPR->id;

                                $dropout_c_g->pressure = str_replace(",",".", $value[0]);
                                $dropout_c_g->liquid_percentage = str_replace(",",".", $value[1]);

                                $dropout_pressure_data[] = $dropout_c_g->pressure;
                                $dropout_liquid_percentage[] = $dropout_c_g->liquid_percentage;

                                $dropout_c_g->save();
                            }

                            $dropout_pressure_data = json_encode($dropout_pressure_data);
                            $dropout_liquid_percentage = json_encode($dropout_liquid_percentage);
                        }
                        

                        $attributtes = array();
                        
                        $tmp_attr = $IPR->toArray();
                        foreach($tmp_attr as $k => $v)
                        {
                            if($v == '')
                            {
                                $v = null;
                            }
                            $attributtes[$k] = $v;
                        }
                        foreach($attributtes as $k => $v)
                        {
                            $IPR->$k = $v;
                        }

                        $IPR->save();
                        


                        $_SESSION['ipr'] = $IPR->id;
                        $_SESSION['for_id'] = $IPR->formation_id;
                        $_SESSION['fie_id'] = $IPR->field_id;
                        $_SESSION['we_id'] = $IPR->well_id;

                        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
                        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
                        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
                        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
                        $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');

                        $scenaryId= DB::table('ipr')->where('id_escenario', $IPR->id_escenario)->pluck('id_escenario');
                        $scenary = escenario::find($scenaryId);
                        $scenary->estado=1;
                        if($validator->fails())
                        {
                            $scenary->completo=0;
                        }
                        else
                        {
                            $scenary->completo=0;
                        }
                        $scenary->save();   
               
                        return \Redirect::route('IPR.edit',$IPR->id_escenario);
                    }            
        }
        else
        {
            return view('loginfirst');
        }
    }

    /**
     * Prepara y construye el set de datos para la ejecución de sensibilidades en los escenarios de ipr.
     */
    public function apply(IPRRequest $request)
    {
        if (\Auth::check()) 
        {
                    $arreglo = json_decode($request->get("ipr"));
                    $ipr = array();
                    
                    
                    $input_data = [
                            "presiones" => str_replace("[0,0]", "[0]", json_encode($presionesv)),
                            "viscosidades_aceite" => str_replace("[0,0]", "[0]", json_encode($viscosidades_aceite)),
                            "factores_vol_aceite" => str_replace("[0,0]", "[0]", json_encode($factores_vol_aceite)),
                            "viscosidades_agua" => str_replace("[0,0]", "[0]", json_encode($viscosidades_agua)),
                            "radio_pozo" => $IPR->radio_pozo,
                            "permeabilidad_abs_ini" => $IPR->permeabilidad_abs_ini,
                            "modulo_permeabilidad" => $IPR->modulo_permeabilidad,
                            "porosidad" => $IPR->porosidad,
                            "permeabilidad" => $IPR->permeabilidad,
                            "tipo_roca" => $IPR->tipo_roca,
                            "presion_yacimiento" => $IPR->presion_yacimiento,
                            "presion_inicial" => $IPR->presion_inicial,
                            "espesor_reservorio" => $IPR->espesor_reservorio,
                            "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                            "factor_dano" => $IPR->factor_dano,
                            "bsw" => $IPR->bsw,
                            "saturacion_aceite_irred" => $IPR->saturacion_aceite_irred,
                            "end_point_kr_agua" => $IPR->end_point_kr_agua,
                            "end_point_kr_gas" => $IPR->end_point_kr_gas,
                            "end_point_kr_petroleo" => $IPR->end_point_kr_petroleo,
                            "gor" => $IPR->gor,
                            "viscosidad_agua" => $IPR->viscosidad_agua,
                            
                            "exponente_corey_agua" => $IPR->exponente_corey_agua,
                            "exponente_corey_gas" => $IPR->exponente_corey_gas,
                            "exponente_corey_petroleo" => $IPR->exponente_corey_petroleo,
                            "saturacion_agua_irred" => $IPR->saturacion_agua_irred,                
                            "tasa_flujo" => $IPR->tasa_flujo,
                            "presion_fondo" => $IPR->presion_fondo,
                            "saturacion_gas_crit" => $IPR->saturacion_gas_crit,
                            "saturacion_aceite_irred_gas" => $IPR->saturacion_aceite_irred_gas,
                            "fluido" => $IPR->fluido,
                            "temperatura" => $IPR->temperatura,
                            "factor_compresibilidad_gas" => $IPR->factor_compresibilidad_gas,
                            "campo_a" => $IPR->campo_a,
                            "campo_b" => $IPR->campo_b,
                            "campo_c" => $IPR->campo_c,
                            "campo_d" => $IPR->campo_d,
                            "campo_a1" => $IPR->campo_a1,
                            "campo_b1" => $IPR->campo_b1,
                            "campo_c1" => $IPR->campo_c1,
                            "campo_d1" => $IPR->campo_d1,
                            "campo_a2" => $IPR->campo_a2,
                            "campo_b2" => $IPR->campo_b2,
                            "campo_c2" => $IPR->campo_c2,
                            "campo_d2" => $IPR->campo_d2,
                            "exponente_corey_aceite_gas" => $IPR->exponente_corey_aceite_gas, 
                            "end_point_kr_aceite_gas" => $IPR->end_point_kr_aceite_gas
                        ];
                    
                   
                    #$http_data = http_build_query($input_data); # Convierto a cadena url válida
                    $http_data = http_build_query(array("url" => env('LARAVEL_IPR_ROUTE'))); # Convierto a cadena url válida
                    
                    /* Archivo */
                    
                    $json = json_encode($input_data, true);
                    
                    $this->print_file($json);
                    
                    $ctx = stream_context_create(array('http'=>
                        array(
                            'timeout' => 12000,  //1200 Seconds is 200 Minutes
                        )
                    ));
                    
                    $response = file_get_contents(env('DJANGO_SERVICE_ROUTE')."/ipr/llamado?$http_data", false, $ctx); # Hago la petición
                    
                    $response = json_decode($response, true);
                    
                    /* Archivo */
                    
                    $response = file_get_contents(env('DJANGO_SERVICE_ROUTE')."/ipr/file?$http_data", false, $ctx); # Hago la petición
        }
        else
        {
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
        //
    }

    /**
     * Genera el archivo txt con el set de datos que captura el módulo en python para la ejecución de los escenarios.
     */
    public function print_file($json="")
    {
        $write = \File::put("ipr_data.txt", $json);
        if ($write === false)
        {
            die(\Lang::get('app.error_write_file'));
        }
    }
    /**
     * Obtiene el archivo con los resultados generados desde el módulo de python para su posterior porcesamiento.
     */
    public function get_file(IPRRequest $request)
    {
         
        $contents = \File::get("ipr_data.txt");
        return $contents;
    }
    /**
     * Obtiene el archivo con los resultados generados y con formato desde el módulo de python para su posterior porcesamiento.
     */
    public function get_file_response()
    {
        $contents = \File::get("json_ipr");
        return $contents;
    }

    /**
     * Módulo cálculo IPR
     */

    public function gas_saturation($saturacion_aceite_irred, $saturacion_gas_crit)
    {
        $step = ((1-$saturacion_aceite_irred) - $saturacion_gas_crit) / 9;
        $lista_sg = [];
        $aux = $saturacion_gas_crit;

        for ($i=0; $i < 9; $i++) { 
            array_push($lista_sg, $aux);
            $aux += $step;
        }

        array_push($lista_sg, (1-$saturacion_aceite_irred));

        return $lista_sg;

    }

    public function water_saturation($saturacion_aceite_irred, $saturacion_agua_irred)
    {
        $step = ((1-$saturacion_aceite_irred) - $saturacion_agua_irred) / 9;
        $lista_sw = [];
        $aux = $saturacion_agua_irred;

        for ($i=0; $i < 9; $i++) { 
            array_push($lista_sw, $aux);
            $aux += $step;
        }

        array_push($lista_sw, (1-$saturacion_aceite_irred));

        return $lista_sw;

    }

    public function krosg($lista_sg, $saturacion_aceite_irred, $saturacion_gas_crit,
              $saturacion_aceite_irred_gas, $end_point_kr_aceite_gas, $exponente_corey_aceite_gas)
    {
        $lista_krosg = [];
        $denominador = 1 - $saturacion_gas_crit - $saturacion_aceite_irred_gas;

        for ($i=0; $i < count($lista_sg); $i++)
        {
            $numerador = 1 - $lista_sg[$i] - $saturacion_aceite_irred_gas; 
            $krosg = $end_point_kr_aceite_gas * (($numerador / $denominador) ** $exponente_corey_aceite_gas);
            array_push($lista_krosg, $krosg);
        }       

        return $lista_krosg;
    }

    public function krg($lista_sg, $saturacion_gas, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $exponente_corey_gas, $end_point_kr_gas)
    {
        $lista_krg = [];
        $denominador = 1.0 - $saturacion_gas_crit - $saturacion_aceite_irred_gas;

        for ($i=0; $i < count($lista_sg); $i++){
            $numerador = $lista_sg[$i] - $saturacion_gas_crit;
            $krg = $end_point_kr_gas * (($numerador / $denominador) ** $exponente_corey_gas);
            array_push($lista_krg, $krg);
        }

        return $lista_krg; 
    }

    public function krosw($lista_sw, $end_point_kr_petroleo, $saturacion_aceite_irred, $saturacion_agua_irred, $exponente_corey_petroleo)
    {
        $lista_krosw = [];
        $denominador = 1 - $saturacion_agua_irred - $saturacion_aceite_irred;

        for ($i=0; $i < count($lista_sw); $i++){
            $numerador = 1 - $lista_sw[$i] - $saturacion_aceite_irred;
            $krosw = $end_point_kr_petroleo * (($numerador / $denominador) ** $exponente_corey_petroleo);
            array_push($lista_krosw, $krosw);
        }

        return $lista_krosw;
    }

    public function krw($lista_sw, $end_point_kr_agua, $saturacion_aceite_irred, $saturacion_agua_irred, $exponente_corey_agua)
    {
        $lista_krw = [];
        $denominador = 1 - $saturacion_agua_irred - $saturacion_aceite_irred;

        for ($i=0; $i < count($lista_sw); $i++){
            $numerador = $lista_sw[$i] - $saturacion_agua_irred;
            $krw = $end_point_kr_agua * (($numerador / $denominador) ** $exponente_corey_agua);
            array_push($lista_krw, $krw);
        }

        return $lista_krw;
    }

    public function fw($kro, $krw, $uo, $uw)
    {
        if($krw * $uo == 0)
        {
            $fw = 0;
        }
        else
        {
            $fw = 1 / (1 + (($kro * $uw) / ($krw * $uo)));
        }

        return $fw;
    }

    public function cubica($a, $b, $c, $d, $pprom)
    {
       $valor_interpolado = $a * ($pprom ** 3) + $b * ($pprom ** 2) + $c * $pprom + $d;
       
       return $valor_interpolado; 
    }

    public function kabs($permeabilidad_abs_ini, $modulo_permeabilidad, $presion_yacimiento, $presion_inicial)
    {
        $permeabilidad_absoluta = $permeabilidad_abs_ini * exp($modulo_permeabilidad * ($presion_yacimiento - $presion_inicial));
        
        return $permeabilidad_absoluta;
    }

    public function flujo_fraccional($presiones, $viscosidades_agua, $viscosidades_aceite, $pprom, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua)
    {
        $sw = [];
        $kro = [];
        $krw = [];
        $fw = [];

        $uo = $this->interpolador($presiones, $viscosidades_aceite, $pprom);
        $uw = $this->interpolador($presiones, $viscosidades_agua, $pprom);

        $j = 0;
        $i = $lista_sw[0];

        while($i < end($lista_sw)) #Revisar.
        {
            array_push($sw, $i);
            array_push($kro, $this->interpolador($lista_sw, $lista_kro, $i));
            array_push($krw, $this->interpolador($lista_sw, $lista_krw, $i));
            array_push($fw , $this->fw($kro[$j], $krw[$j], $uo, $uw));
            $i = $i + 0.01;
            $j = $j + 1;
        }  

        array_push($sw, end($lista_sw)); #Revisar.
        array_push($kro, $this->interpolador($lista_sw, $lista_kro, end($lista_sw)));
        array_push($krw, $this->interpolador($lista_sw, $lista_krw, end($lista_sw)));
        array_push($fw, $this->fw($kro[$j], $krw[$j], $uo, $uw));

        return [$fw, $sw]; #Cambio.
    }

    public function saturacion_agua($presiones, $viscosidades_agua, $viscosidades_aceite, $pprom, $bsw, $lista_krw, $lista_kro, $viscosidad_agua)
    {
        $result = $this->flujo_fraccional($presiones, $viscosidades_agua, $viscosidades_aceite, $pprom, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);
        $fw = $result[0];
        $sw = $result[1];

        $saturacion_agua_pozo = $this->interpolador($fw, $sw, $bsw);

        return $saturacion_agua_pozo;
    }

    public function calcula_mod_permeabilidad($k, $theta, $tipo_roca)
    {
        $theta2 = (1 - $theta) / $theta;
        $rqi = 0.0314 * sqrt($k / $theta);
        $fzi = $rqi / $theta2;

        if($tipo_roca == 1)
        {
            $a = 0.000809399;
            $b = -0.986179237;
        }
        else if($tipo_roca == 2)
        {
            $a = 0.000433696;
            $b = -0.587596095;
        }
        else if($tipo_roca == 3)
        {
            $a = 0.000613657;
            $b = 0.371958564;
        }
        $mod_permeabilidad = $a * (($fzi) ** $b);
        
        return $mod_permeabilidad;
    }

    public function presionpromedio($presion_fondo_fluy, $presion_yacimiento)
    {
        $presion_promedio = ($presion_fondo_fluy + $presion_yacimiento) / 2;
        return $presion_promedio;
    }

    public function interpolador($p, $datos, $pprom)
    {
        $valor_interpolado = 0;
        $flag=0;
        for ($i=0; $i < count($p) ; $i++) 
        { 
            $p[$flag] = floatval($p[$i]);
            $flag = $flag+1;
        }
       
        $flag=0;
        for ($i=0; $i < count($datos) ; $i++) 
        { 
            $datos[$flag] = floatval($datos[$i]);
            $flag = $flag+1;
        }

        if(in_array($pprom, $p)) # Se verifica si el valor de la presion promedio ya se encuentra en los valores de presiones ingresadas por el usuario
        {
            $posicion = array_search($pprom,$p); #Revisar.
            $valor_interpolado = $datos[$posicion];
        }  
        else if($pprom < $p[0])# Se verifica si el valor de presion promedio es menor a las prseiones ingresadas por el usuario
        {
            $valor_interpolado = (($pprom - $p[0]) * ($datos[0] - $datos[1]) / ($p[0] - $p[1])) + $datos[0];
        }
        else if($pprom > end($p))# Se verifica si el valor de presion promedio es mayor a las presiones ingresadas por el usuario
        {
            $valor_interpolado = (($pprom - end($p)) * (end($datos) - $datos[count($datos)-2]) / (end($p) - $p[count($p)-2])) + end($datos); #Revisar.
        }
        else  # Se verifica si el valor de presion promedio esta entre dos presiones diferentes de cero
        {
            for ($i=0; $i < count($p); $i++) 
            { 
                if(($p[$i] < $pprom) and ($p[$i + 1] > $pprom))
                {
                    $valor_interpolado = (($pprom - $p[$i]) * ($datos[$i + 1] - $datos[$i]) / ($p[$i + 1] - $p[$i])) + $datos[$i];
                }
            }
        }

        return $valor_interpolado;
    }

    public function pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_final)
    {
        $delta = $presion_final / 25;
        $mp = [];
        $presiones = [];
        $p = 0.0;

        while($p <= $presion_final)
        {
            $visco = $this->interpolador($presiones_pvt, $viscosidades_gas, $p);
            $zeta = $this->interpolador($presiones_pvt, $z, $p);
            
            array_push($mp,((2 * $p)/($visco * $zeta)));
            array_push($presiones, $p);
            $p = $p + $delta;
        }

        if($p > $presion_final)
        {
            $p = $presion_final;
            $visco = $this->interpolador($presiones_pvt, $viscosidades_gas, $p);
            $zeta = $this->interpolador($presiones_pvt, $z, $p);
            
            array_push($mp,((2 * $p)/($visco * $zeta)));
            array_push($presiones,$p);
        }

        $integral = [0];
        $suma = 0;
        for ($i=0; $i < count($mp)-1; $i++) 
        { 
            $prom = ($mp[$i] + $mp[$i+1]) / 2;
            $delta = $presiones[$i+1] - $presiones[$i];
            $area = $prom * $delta;
            $suma = $suma + $area;
            array_push($integral, $suma); 
        }

        return [$presiones, $integral];
    }

    public function sumatoria($presiones, $integral, $presion_fondo)
    {
        $mp_pwf = $this->interpolador($presiones, $integral, $presion_fondo);
        $valor_integral = end($integral) - $mp_pwf;

        return $valor_integral;
    }

    public function skin_aceite($presion_burbuja, $presion_yacimiento, $presion_fondo, $tasa_aceite, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog,
                    $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw)
    {
        $skin = 0;

        if($presion_yacimiento > $presion_burbuja and $presion_fondo > $presion_burbuja)
        {
            $presion_promedio = $this->presionpromedio($presion_fondo, $presion_yacimiento);

            $sw = $this->saturacion_agua($presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $presion_promedio, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_PVT, $viscosidades_aceite, $presion_promedio);

            $bo = $this->interpolador($presiones_PVT, $factores_vol_aceite, $presion_promedio);

            $numerador = 0.00708*$permeabilidad_absoluta*$krow*$krog*$espesor_reservorio*($presion_yacimiento-$presion_fondo);
            $denominador = $uo*$bo*$tasa_aceite;
            $skin = ($numerador / $denominador) - log($radio_drenaje_yac/$radio_pozo) + 0.75;
        }
        else if($presion_yacimiento < $presion_burbuja and $presion_fondo < $presion_burbuja)
        {
            $sw = $this->saturacion_agua($presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $presion_burbuja, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_PVT, $viscosidades_aceite, $presion_burbuja);

            $bo = $this->interpolador($presiones_PVT, $factores_vol_aceite, $presion_burbuja);

            $numerador = 0.00708 * $permeabilidad_absoluta * $krow * $krog * $espesor_reservorio * ($presion_yacimiento**2 - $presion_fondo**2);
            $denominador = 2 * $uo * $bo * $tasa_aceite * $presion_burbuja;
            
            $skin = ($numerador / $denominador) - log($radio_drenaje_yac / $radio_pozo) + 0.75;
        }
        else if ($presion_yacimiento > $presion_burbuja and $presion_fondo < $presion_burbuja)
        {
            $presion_promedio = $this->presionpromedio($presion_burbuja, $presion_yacimiento);

            $sw = $this->saturacion_agua($presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $presion_promedio, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_PVT, $viscosidades_aceite, $presion_promedio);

            $bo = $this->interpolador($presiones_PVT, $factores_vol_aceite, $presion_promedio);

            $insaturado = (($krow*$krog)/($uo*$bo))*($presion_yacimiento - $presion_burbuja);

            $sw = $this->saturacion_agua($presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $presion_burbuja, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_PVT, $viscosidades_aceite, $presion_burbuja);

            $bo = $this->interpolador($presiones_PVT, $factores_vol_aceite, $presion_burbuja);

            $saturado = (($krow*$krog)/(2*$uo*$bo*$presion_burbuja))*($presion_burbuja**2 - $presion_fondo**2);

            $numerador = 0.00708 * $permeabilidad_absoluta * $espesor_reservorio * ($insaturado + $saturado);

            $skin = ($numerador / $tasa_aceite) + 0.75 - log($radio_drenaje_yac/$radio_pozo);
        }
        return $skin;
    }

    public function ipr_aceite($presion_burbuja, $presion_yacimiento, $skin, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw)
    {
        $delta = $presion_yacimiento / 19;
        $presion_fondo_lista = [];
        $tasa_aceite_lista = [];

        $presion_fondo = $presion_yacimiento;
        while($presion_fondo >= 0)
        {
          if($presion_yacimiento > $presion_burbuja and $presion_fondo > $presion_burbuja)
          {
            $presion_promedio = $this->presionpromedio($presion_fondo, $presion_yacimiento);

            $sw = $this->saturacion_agua($presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $presion_promedio, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_pvt, $viscosidades_aceite, $presion_promedio);

            $bo = $this->interpolador($presiones_pvt, $factores_vol_aceite, $presion_promedio);

            $numerador = 0.00708 * $permeabilidad_absoluta * $krow * $krog * $espesor_reservorio * ($presion_yacimiento - $presion_fondo);
            $denominador = $uo * $bo * (log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin);

            array_push($tasa_aceite_lista, ($numerador / $denominador));
            array_push($presion_fondo_lista, $presion_fondo);

            $presion_fondo = $presion_fondo - $delta;
          }

          if ($presion_yacimiento < $presion_burbuja and $presion_fondo < $presion_burbuja)
          {
            $sw = $this->saturacion_agua($presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $presion_burbuja, $bsw, $campo_a1, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_pvt, $viscosidades_aceite, $presion_burbuja);
                
            $bo = $this->interpolador($presiones_pvt, $factores_vol_aceite, $presion_burbuja);
                
            $numerador = 0.00708 * $permeabilidad_absoluta * $krow * $krog * $espesor_reservorio * ($presion_yacimiento ** 2 - $presion_fondo ** 2);
            $denominador = 2 * $presion_burbuja * $uo * $bo * (log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin);
            
            array_push($tasa_aceite_lista,($numerador / $denominador));
            array_push($presion_fondo_lista, $presion_fondo);

            $presion_fondo = $presion_fondo - $delta;
          }

          if($presion_yacimiento > $presion_burbuja and $presion_fondo < $presion_burbuja)
          {
            $presion_promedio = $this->presionpromedio($presion_burbuja, $presion_yacimiento);

            $sw = $this->saturacion_agua($presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $presion_promedio, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_pvt, $viscosidades_aceite, $presion_promedio);

            $bo = $this->interpolador($presiones_pvt, $factores_vol_aceite, $presion_promedio);

            $insaturado = (($krow * $krog) / ($uo * $bo)) * ($presion_yacimiento - $presion_burbuja);

            $sw = $this->saturacion_agua($presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $presion_burbuja, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

            $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

            $sg = 0;
            $so = 1;

            $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

            $uo = $this->interpolador($presiones_pvt, $viscosidades_aceite, $presion_burbuja);

            $bo = $this->interpolador($presiones_pvt, $factores_vol_aceite, $presion_burbuja);

            $saturado = (($krow * $krog) / (2 * $uo * $bo * $presion_burbuja)) * ($presion_burbuja ** 2 - $presion_fondo ** 2);
            $numerador = 0.00708 * $permeabilidad_absoluta * $espesor_reservorio * ($insaturado + $saturado);
            $denominador = log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin;

            array_push($tasa_aceite_lista, ($numerador/$denominador));
            array_push($presion_fondo_lista, $presion_fondo);

            $presion_fondo = $presion_fondo - $delta;  
          }
        }
        
        if($presion_fondo < 0)
        {
            $presion_fondo = 0;
            if ($presion_yacimiento < $presion_burbuja and $presion_fondo < $presion_burbuja)
            {
                $sw = $this->saturacion_agua($presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $presion_burbuja, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

                $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

                $sg = 0;
                $so = 1;

                $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

                $uo = $this->interpolador($presiones_pvt, $viscosidades_aceite, $presion_burbuja);

                $bo = $this->interpolador($presiones_pvt, $factores_vol_aceite, $presion_burbuja);

                $numerador = 0.00708 * $permeabilidad_absoluta * $krow * $krog * $espesor_reservorio * ($presion_yacimiento ** 2 - $presion_fondo ** 2);
                $denominador = 2 * $presion_burbuja * $uo * $bo * (log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin);

               array_push($tasa_aceite_lista, ($numerador / $denominador));
               array_push($presion_fondo_lista, $presion_fondo); 
            }

            if ($presion_yacimiento > $presion_burbuja and $presion_fondo < $presion_burbuja)
            {
                $presion_promedio = $this->presionpromedio($presion_burbuja, $presion_yacimiento);

                $sw = $this->saturacion_agua($presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $presion_promedio, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

                $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

                $sg = 0;
                $so = 1;

                $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

                $uo = $this->interpolador($presiones_pvt, $viscosidades_aceite, $presion_promedio);

                $bo = $this->interpolador($presiones_pvt, $factores_vol_aceite, $presion_promedio);

                $insaturado = (($krow * $krog) / ($uo * $bo)) * ($presion_yacimiento - $presion_burbuja);

                $sw = $this->saturacion_agua($presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $presion_burbuja, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua);

                $krow = $this->interpolador($lista_sw, $lista_kro, $sw);

                $sg = 0;
                $so = 1;

                $krog = $this->interpolador($lista_sg, $lista_krog, $sg);

                $uo = $this->interpolador($presiones_pvt, $viscosidades_aceite, $presion_burbuja);

                $bo = $this->interpolador($presiones_pvt, $factores_vol_aceite, $presion_burbuja);

                $saturado = (($krow * $krog) / (2 * $uo * $bo * $presion_burbuja)) * ($presion_burbuja ** 2 - $presion_fondo ** 2);

                $numerador = 0.00708 * $permeabilidad_absoluta * $espesor_reservorio * ($insaturado + $saturado);
                $denominador = (log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin);

                array_push($tasa_aceite_lista, ($numerador/$denominador));
                array_push($presion_fondo_lista, $presion_fondo);
            }


        }
        return [$tasa_aceite_lista, $presion_fondo_lista];
    }

    public function skin_gas($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $tasa_gas, $presiones_pvt, $viscosidades_gas, $z, $t, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo)
    {
        $pseudopresion_result = $this->pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_yacimiento);
        $presiones_integral = $pseudopresion_result[0];
        $tabla_integral =  $pseudopresion_result[1];
        $integral = $this->sumatoria($presiones_integral, $tabla_integral, $presion_yacimiento, $presion_fondo);
        $denominador = $t * $tasa_gas;
        $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * $integral;
        $skin = ($numerador/$denominador) - log($radio_drenaje_yac/$radio_pozo) + 0.75;

        return $skin;
    }

    public function ipr_gas($permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio)
    {
        $presion_fondo = $presion_yacimiento;

        $presion_fondo_lista = [];
        $tasa_gas_lista = [];
        
        $pseudopresion_result = $this->pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_yacimiento);
        $presiones_integral = $pseudopresion_result[0]; 
        $tabla_integral = $pseudopresion_result[1]; 

        $delta = $presion_yacimiento/19;

        while($presion_fondo >= 0)
        {
            $integral = $this->sumatoria($presiones_integral, $tabla_integral, $presion_yacimiento, $presion_fondo);
            $denominador = $t * (log($radio_drenaje_yac/$radio_pozo) - 0.75 + $skin);
            $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * $integral;

            array_push($tasa_gas_lista,($numerador/$denominador));
            array_push($presion_fondo_lista, $presion_fondo);

            $presion_fondo = $presion_fondo - $delta;
        }

        if($presion_fondo < 0)
        {
            $presion_fondo = 0;
            $integral = $this->sumatoria($presiones_integral, $tabla_integral, $presion_yacimiento, $presion_fondo);
            $denominador = $t * (log($radio_drenaje_yac/$radio_pozo) - 0.75 + $skin);
            $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * $integral;

            array_push($tasa_gas_lista, ($numerador/$denominador));
            array_push($presion_fondo_lista, $presion_fondo);
        }

        return [$tasa_gas_lista, $presion_fondo_lista];
    }

    public function calculo_p_asterisco($gor, $presiones, $ogr)
    {
        return $this->interpolador($ogr, $presiones, $gor);
    }

    public function integral_function($presion_yacimiento, $presion_rocio, $p_asterisco, $lista_presiones, $lista_bo, $lista_bg, $lista_uo, $lista_ug, $lista_rs, $lista_sg, $lista_krg, $lista_kro, $lista_presiones_so, $lista_so)
    {
        $function = [];
        $pwfs = [];

        if ($p_asterisco >= $presion_yacimiento)
        {
            $delta = $presion_yacimiento / 19;
            $p = 10;

            while($p < $presion_yacimiento)
            {
                $sg = 1.0 - $this->interpolador($lista_presiones_so, $lista_so, $p);
                $krg = $this->interpolador($lista_sg, $lista_krg, $sg);
                $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
                $ug = $this->interpolador($lista_presiones, $lista_ug, $p);
                $kro = $this->interpolador($lista_sg, $lista_kro, $sg);
                $bo = $this->interpolador($lista_presiones, $lista_bo, $p);
                $uo = $this->interpolador($lista_presiones, $lista_uo, $p);
                $rs = $this->interpolador($lista_presiones, $lista_rs, $p);

                array_push($function, ($krg / ($bg * $ug) + ($kro * $rs) / ($bo * $uo)));
                array_push($pwfs, $p);

                $p = $p + $delta;
            }

            $p = $presion_yacimiento;

            $sg = 1.0 - $this->interpolador($lista_presiones_so, $lista_so, $p);
            $krg = $this->interpolador($lista_sg, $lista_krg, $sg);
            $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
            $ug = $this->interpolador($lista_presiones, $lista_ug, $p);
            $kro = $this->interpolador($lista_sg, $lista_kro, $sg);
            $bo = $this->interpolador($lista_presiones, $lista_bo, $p);
            $uo = $this->interpolador($lista_presiones, $lista_uo, $p);
            $rs = $this->interpolador($lista_presiones, $lista_rs, $p);

            array_push($function, ($krg / ($bg * $ug) + ($kro * $rs) / ($bo * $uo)));
            array_push($pwfs, $p);
        }
        else if($presion_yacimiento > $presion_rocio)
        {
            $delta = $p_asterisco / 29;
            $p = 10;

            while($p < $p_asterisco)
            {
                $sg = 1.0 - $this->interpolador($lista_presiones_so, $lista_so, $p);
                $krg = $this->interpolador($lista_sg, $lista_krg, $sg);
                $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
                $ug = $this->interpolador($lista_presiones, $lista_ug, $p);
                $kro = $this->interpolador($lista_sg, $lista_kro, $sg);
                $bo = $this->interpolador($lista_presiones, $lista_bo, $p);
                $uo = $this->interpolador($lista_presiones, $lista_uo, $p);
                $rs = $this->interpolador($lista_presiones, $lista_rs, $p);

                array_push($function, ($krg / ($bg * $ug) + ($kro * $rs) / ($bo * $uo)));
                array_push($pwfs, $p);

                $p = $p + $delta;
            }


            $delta = ($presion_rocio - $p_asterisco) / 19;
            $p = $p_asterisco;

            while($p < $presion_rocio)
            {
                $sg = 1.0 - $this->interpolador($lista_presiones_so, $lista_so, $p);
                $krg = $this->interpolador($lista_sg, $lista_krg, $sg);
                $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
                $ug = $this->interpolador($lista_presiones, $lista_ug, $p);

                array_push($function, ($krg / ($bg * $ug)));
                array_push($pwfs, $p);

                $p = $p + $delta;
            }


            $delta = ($presion_yacimiento - $presion_rocio) / 19;
            $p = $presion_rocio;

            while($p < $presion_yacimiento)
            {
                $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
                $ug = $this->interpolador($lista_presiones, $lista_ug, $p);

                array_push($function, (end($lista_krg) / ($bg * $ug)));
                array_push($pwfs, $p);

                $p = $p + $delta;
            }

            $p = $presion_yacimiento;
            $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
            $ug = $this->interpolador($lista_presiones, $lista_ug, $p);

            array_push($function, (end($lista_krg) / ($bg * $ug)));
            array_push($pwfs, $p);
        }
        else if($presion_yacimiento < $presion_rocio)
        {
            $delta = $p_asterisco / 29;
            $p = 10;

            while($p < $p_asterisco)
            {
               $sg = 1.0 - $this->interpolador($lista_presiones_so, $lista_so, $p);
               $krg = $this->interpolador($lista_sg, $lista_krg, $sg);
               $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
               $ug = $this->interpolador($lista_presiones, $lista_ug, $p);
               $kro = $this->interpolador($lista_sg, $lista_kro, $sg);
               $bo = $this->interpolador($lista_presiones, $lista_bo, $p);
               $uo = $this->interpolador($lista_presiones, $lista_uo, $p);
               $rs = $this->interpolador($lista_presiones, $lista_rs, $p);

               array_push($function, ($krg / ($bg * $ug) + ($kro * $rs) / ($bo * $uo)));
               array_push($pwfs, $p);

               $p = $p + $delta; 
            }

            $delta = ($presion_yacimiento - $p_asterisco) / 19;
            $p = $p_asterisco;

            while($p < $presion_yacimiento)
            {
                $sg = 1.0 - $this->interpolador($lista_presiones_so, $lista_so, $p);
                $krg = $this->interpolador($lista_sg, $lista_krg, $sg);
                $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
                $ug = $this->interpolador($lista_presiones, $lista_ug, $p);

                array_push($function, ($krg / ($bg * $ug)));
                array_push($pwfs, $p);

                $p = $p + $delta;
            }

            $p = $presion_yacimiento;
            $sg = 1.0 - $this->interpolador($lista_presiones_so, $lista_so, $p);
            $krg = $this->interpolador($lista_sg, $lista_krg, $sg);
            $bg = $this->interpolador($lista_presiones, $lista_bg, $p);
            $ug = $this->interpolador($lista_presiones, $lista_ug, $p);

            array_push($function, ($krg / ($bg * $ug)));
            array_push($pwfs, $p);
        }
        return [$pwfs, $function];
    }

    public function skin_gas_condensado($permeabilidad_absoluta, $presion_yacimiento, $net_pay, $radio_drenaje_yac, $radio_pozo, $lista_bo, $lista_bg, $lista_uo,  $lista_ug, $gor, $lista_presiones, $lista_ogr, $lista_presiones_so, $lista_so, $presion_fondo, $caudal_gas, $presion_rocio, $lista_rs, $lista_sg, $lista_krg, $lista_kro)
    {
        $ogr = 1 / $gor;
        $p_asterisco = $this->calculo_p_asterisco($ogr, $lista_presiones, $lista_ogr);
        $integral_function_results = $this->integral_function($presion_yacimiento, $presion_rocio, $p_asterisco, $lista_presiones, $lista_bo, $lista_bg, $lista_uo, $lista_ug, $lista_rs, $lista_sg, $lista_krg, $lista_kro, $lista_presiones_so, $lista_so); 
        $pwfs = $integral_function_results[0];
        $function = $integral_function_results[1];
     
        $suma_acumulada = [0.0];
        $suma = 0; 

        for ($i=0; $i < count($pwfs)-1 ; $i++) 
        { 
            $prom = ($function[$i] + $function[$i+1]) / 2.0;
            $delta = $pwfs[$i+1] - $pwfs[$i];
            $area = $prom * $delta;
            $suma = $suma + $area;
            array_push($suma_acumulada, $suma);
        }

        $integral = end($suma_acumulada) - $this->interpolador($pwfs, $suma_acumulada, $presion_fondo);
        $denominador = $caudal_gas;
        $numerador = 0.00000000708188342074869 * $permeabilidad_absoluta * $net_pay * $integral; #revisar lo del factor de conversion
        $skin = ($numerador / $denominador) - log($radio_drenaje_yac / $radio_pozo) + 0.75;

        return $skin;
    }

    public function ipr_gas_condensado($permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, $skin, $espesor_reservorio, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug, $lista_rs, $lista_sg, $lista_krg, $lista_kro, $lista_presiones_so, $lista_so)
    {
        $ogr = 1 / $gor;

        $p_asterisco = $this->calculo_p_asterisco($ogr, $lista_presiones, $lista_ogr);
        $integral_function_results = $this->integral_function($presion_yacimiento, $presion_rocio, $p_asterisco, $lista_presiones, $lista_bo, $lista_bg, $lista_uo, $lista_ug, $lista_rs, $lista_sg, $lista_krg, $lista_kro, $lista_presiones_so, $lista_so); 
        $pwfs = $integral_function_results[0];
        $function = $integral_function_results[1];
        $suma_acumulada = [0.0];
        $suma = 0;

        for ($i=0; $i < count($pwfs)-1 ; $i++) 
        { 
            $prom = ($function[$i] + $function[$i + 1]) / 2.0;
            $delta = $pwfs[$i + 1] - $pwfs[$i];
            $area = $prom * $delta;
            $suma = $suma + $area;
            array_push($suma_acumulada, $suma);
        }

        $presion_fondo = $presion_yacimiento;
        $presion_fondo_lista = [];
        $tasa_gas_lista = [];
        $delta = $presion_yacimiento / 19;

        while($presion_fondo >= 10)
        {
            $integral = end($suma_acumulada) - $this->interpolador($pwfs, $suma_acumulada, $presion_fondo);
            $denominador = log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin;
            $numerador = 0.00000000708188342074869 * $permeabilidad_absoluta * $espesor_reservorio * $integral;
            array_push($tasa_gas_lista, ($numerador / $denominador));
            array_push($presion_fondo_lista, $presion_fondo);
            $presion_fondo = $presion_fondo - $delta;
        }

        if($presion_fondo < 10)
        {
            $presion_fondo = 10;
            $integral = end($suma_acumulada) - $this->interpolador($pwfs, $suma_acumulada, $presion_fondo);
            $denominador = log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin;
            $numerador = 0.00000000708188342074869 * $permeabilidad_absoluta * $espesor_reservorio * $integral;
            array_push($tasa_gas_lista,($numerador / $denominador));
            array_push($presion_fondo_lista, $presion_fondo);
        }
        return [$tasa_gas_lista, $presion_fondo_lista];
    }

    public function skinWaterInjector($permeabilidad_absoluta, $espesor_reservorio, $presion_fondo, $presion_yacimiento, $water_viscosity, $water_volumetric_factor, $tasa)
    {
        $skin = 0;

        $numerador = 0.00708*$permeabilidad_absoluta*$espesor_reservorio*($presion_fondo - $presion_yacimiento);
        $denominador = $water_viscosity*$water_volumetric_factor*$tasa;
        $skin = ($numerador / $denominador) - log($radio_drenaje_yac/$radio_pozo) + 0.75;
    }

    public function iirWater()
    {
        $delta = ($presion_ruptura - $presion_yacimiento) / 19;
        $presion_fondo_lista = [];
        $tasa_lista = [];

        $presion_fondo = $presion_ruptura;
        while($presion_fondo >= $presion_yacimiento)
        {
            $numerador = 0.00708 * $permeabilidad_absoluta * $espesor_reservorio * ($presion_fondo - $presion_yacimiento);
            $denominador = $water_viscosity*$water_volumetric_factor * (log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin);

            array_push($tasa_lista, ($numerador / $denominador));
            array_push($presion_fondo_lista, $presion_fondo);

            $presion_fondo = $presion_fondo - $delta;

            if($presion_fondo < $presion_yacimiento)
            {
                $presion_fondo = $presion_yacimiento;

                $numerador = 0.00708 * $permeabilidad_absoluta * $espesor_reservorio * ($presion_fondo - $presion_yacimiento);
                $denominador = $water_viscosity*$water_volumetric_factor * (log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin);

                array_push($tasa_lista, ($numerador / $denominador));
                array_push($presion_fondo_lista, $presion_fondo);

            }
        }
        return [$tasa_lista, $presion_fondo_lista];
    }

    public function skinGasinjector($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $presion_ruptura, $tasa_gas, $presiones_pvt, $viscosidades_gas, $z, $t, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo)
    {
        $pseudopresion_result = $this->pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_ruptura);
        $presiones_integral = $pseudopresion_result[0];
        $tabla_integral =  $pseudopresion_result[1];
        $integral_hasta_yacimiento = $this->interpolador($presiones_integral, $tabla_integral, $presion_yacimiento);
        $integral_hasta_fondo = $this->interpolador($presiones_integral, $tabla_integral, $presion_fondo);
        $denominador = $t * $tasa_gas;
        $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * ($integral_hasta_fondo - $integral_hasta_yacimiento);
        $skin = ($numerador/$denominador) - log($radio_drenaje_yac/$radio_pozo) + 0.75;

        return $skin;
    }

    public function iirGas($permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio)
    {
        $presion_fondo = $presion_ruptura;

        $presion_fondo_lista = [];
        $tasa_lista = [];
        
        $pseudopresion_result = $this->pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_ruptura);
        $presiones_integral = $pseudopresion_result[0]; 
        $tabla_integral = $pseudopresion_result[1]; 

        $delta = ($presion_ruptura - $presion_yacimiento)/19;

        while($presion_fondo >= $presion_yacimiento)
        {
            $integral_hasta_yacimiento = $this->interpolador($presiones_integral, $tabla_integral, $presion_yacimiento);
            $integral_hasta_fondo = $this->interpolador($presiones_integral, $tabla_integral, $presion_fondo);
            $denominador = $t * (log($radio_drenaje_yac/$radio_pozo) - 0.75 + $skin);
            $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * ($integral_hasta_fondo - $integral_hasta_yacimiento);

            array_push($tasa_lista,($numerador/$denominador));
            array_push($presion_fondo_lista, $presion_fondo);

            $presion_fondo = $presion_fondo - $delta;
        }

        if($presion_fondo < $presion_yacimiento)
        {
            $presion_fondo = $presion_yacimiento;
            $integral_hasta_yacimiento = $this->interpolador($presiones_integral, $tabla_integral, $presion_yacimiento);
            $integral_hasta_fondo = $this->interpolador($presiones_integral, $tabla_integral, $presion_fondo);
            $denominador = $t * (log($radio_drenaje_yac/$radio_pozo) - 0.75 + $skin);
            $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * ($integral_hasta_fondo - $integral_hasta_yacimiento);

            array_push($tasa_lista, ($numerador/$denominador));
            array_push($presion_fondo_lista, $presion_fondo);
        }

        return [$tasa_lista, $presion_fondo_lista];
    }
        
    public function retornogascondensado($absolute_permeability, $fluid, $well_radius, $drainage_radius, $reservoir_pressure, $gas_rate_c_g, $bhp_c_g, $initial_pressure_c_g, $ini_abs_permeability_c_g, $netpay_c_g, $permeability_module_c_g, $permeability_c_g, $porosity_c_g, $rock_type_c_g, $saturation_pressure_c_g, $gor_c_g, $sg_data, $krg_data, $krog_data, $pressure_data, $bo_data, $vo_data, $rs_data, $bg_data, $vg_data, $og_ratio_data, $dropout_pressure_data, $dropout_liquid_percentage, $skin)
    {
        if($skin != 0)
        {
            $skin = $this->skin_gas_condensado($absolute_permeability, $reservoir_pressure, $netpay_c_g, $drainage_radius, $well_radius, $bo_data, $bg_data, $vo_data, $vg_data, $gor_c_g, $pressure_data, $og_ratio_data, $dropout_pressure_data, $dropout_liquid_percentage, $bhp_c_g, $gas_rate_c_g, $saturation_pressure_c_g, $rs_data, $sg_data, $krg_data, $krog_data);
        }

        $ipr_data = $this->ipr_gas_condensado($absolute_permeability, $reservoir_pressure, $drainage_radius, $well_radius, $skin, $netpay_c_g, $saturation_pressure_c_g, $gor_c_g, $pressure_data, $og_ratio_data, $bo_data, $bg_data, $vo_data, $vg_data, $rs_data, $sg_data, $krg_data, $krog_data, $dropout_pressure_data, $dropout_liquid_percentage);

        return [$ipr_data[0],  $ipr_data[1], $skin];
    }
        
    public function retornogas($fluido,$radio_pozo,$radio_drenaje_yac,$presion_yacimiento,$gas_rate_g,$bhp_g,$init_res_press_text_g,$abs_perm_init_text_g,$net_pay_text_g, $permeability_module_text_g,$abs_perm_text_g,$porosity_text_g,$rock_type,$temperature_text_g,$c1_viscosity_fluid_g,$c2_viscosity_fluid_g, $c3_viscosity_fluid_g,$c4_viscosity_fluid_g,$c1_compressibility_fluid_g,$c2_compressibility_fluid_g,$c3_compressibility_fluid_g,$c4_compressibility_fluid_g, $pvt_pressure_gas,$pvt_gasviscosity_gas,$pvt_gascompressibility_gas, $factor_dano)
    {
        if($permeability_module_text_g == 0 and $abs_perm_text_g != 0 and $porosity_text_g != 0 and $rock_type != 0)
        {
            $permeability_module_text_g = $this->calcula_mod_permeabilidad($abs_perm_text_g, $porosity_text_g, $rock_type);
        }
        else if($permeability_module_text_g == 0 and $abs_perm_text_g == 0 and $porosity_text_g == 0 and $rock_type == 0)
        {
            $permeability_module_text_g = 0;
        }

        if($factor_dano == -1.0)
        {
            $skin = $this->skin_gas($abs_perm_init_text_g, $permeability_module_text_g, $bhp_g, $presion_yacimiento, $init_res_press_text_g, $gas_rate_g, $pvt_pressure_gas, $pvt_gasviscosity_gas, $pvt_gascompressibility_gas, $temperature_text_g, $net_pay_text_g, $radio_drenaje_yac, $radio_pozo, $c1_viscosity_fluid_g, $c2_viscosity_fluid_g, $c3_viscosity_fluid_g, $c4_viscosity_fluid_g, $c1_compressibility_fluid_g, $c2_compressibility_fluid_g, $c3_compressibility_fluid_g, $c4_compressibility_fluid_g);
        }
        else
        {
            $skin = $factor_dano;
        }

        $skin_g = $skin;
        $ipr_gas_results = $this->ipr_gas($abs_perm_init_text_g, $permeability_module_text_g, $presion_yacimiento, $init_res_press_text_g, $pvt_gascompressibility_gas, $temperature_text_g, $radio_drenaje_yac, $radio_pozo, $skin_g, $pvt_pressure_gas, $pvt_gasviscosity_gas, $net_pay_text_g, $c1_viscosity_fluid_g, $c2_viscosity_fluid_g, $c3_viscosity_fluid_g, $c4_viscosity_fluid_g, $c1_compressibility_fluid_g, $c2_compressibility_fluid_g, $c3_compressibility_fluid_g, $c4_compressibility_fluid_g);
        $tasas_flujo_lista = $ipr_gas_results[0]; 
        $presiones_fondo_lista = $ipr_gas_results[1]; 


        return [$tasas_flujo_lista, $presiones_fondo_lista, $skin];
    }

        
    public function retornoaceite($permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $porosidad_aceite, $permeabilidad_aceite, $tipo_roca_aceite, $presion_yacimiento_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $presiones_aceite, $viscosidades_aceite, $factores_vol_aceite, $viscosidades_agua, $bsw, $presion_fondo_aceite, $tasa_aceite, $end_point_kr_agua, $end_point_kr_gas, $end_point_kr_aceite_gas, $end_point_kr_petroleo, $exponente_corey_agua, $exponente_corey_gas, $exponente_corey_aceite_gas, $exponente_corey_petroleo, $saturacion_aceite_irred, $saturacion_agua_irred, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2, $viscosidad_agua, $lista_sg, $lista_krg, $lista_krosg, $lista_sw, $lista_krw, $lista_kro, $presion_burbuja, $factor_dano)
    {
        if ($modulo_permeabilidad_aceite == 0 and $permeabilidad_aceite != 0 and $porosidad_aceite != 0 and $tipo_roca_aceite != 0)
        {
            $modulo_permeabilidad_aceite = $this->calcula_mod_permeabilidad($permeabilidad_aceite, $porosidad_aceite, $tipo_roca_aceite);
        }
        else if($modulo_permeabilidad_aceite == 0 and $permeabilidad_aceite == 0 and $porosidad_aceite == 0 and $tipo_roca_aceite == 0)
        {
            $modulo_permeabilidad_aceite = 0;
        }

        if($factor_dano == -1.0)
        {
            $skin = $this->skin_aceite($presion_burbuja, $presion_yacimiento_aceite, $presion_fondo_aceite, $tasa_aceite, $permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $saturacion_agua_irred, $end_point_kr_petroleo, $end_point_kr_agua, $saturacion_aceite_irred, $exponente_corey_petroleo, $exponente_corey_agua, $end_point_kr_aceite_gas, $exponente_corey_aceite_gas, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krosg, $presiones_aceite, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2);
        }
        else
        {
            $skin = $factor_dano;
        }

        $factor_dano_aceite = $skin;
        $ipr_aceite_results = $this->ipr_aceite($presion_burbuja, $presion_yacimiento_aceite, $factor_dano_aceite, $permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $saturacion_agua_irred, $end_point_kr_petroleo, $end_point_kr_agua, $saturacion_aceite_irred, $exponente_corey_petroleo, $exponente_corey_agua, $end_point_kr_aceite_gas, $exponente_corey_aceite_gas, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krosg, $presiones_aceite, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2);

        $tasas_flujo_lista = $ipr_aceite_results[0];
        $presiones_fondo_lista = $ipr_aceite_results[1];

        return [$tasas_flujo_lista, $presiones_fondo_lista, $skin];
    }


    public function retorno($fluido, $permeabilidad_abs_ini_gas, $modulo_permeabilidad_gas, $porosidad_gas, $permeabilidad_gas, $tipo_roca_gas, $presion_yacimiento_gas, $presion_inicial_gas, $espesor_reservorio_gas, $radio_drenaje_yac_gas, $radio_pozo_gas, $presiones_gas, $viscosidades_gas, $z, $factor_dano_gas, $presion_fondo_gas, $tasa_gas, $t, $a1, $b1, $c1, $d1, $a2, $b2, $c2, $d2, $permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $porosidad_aceite, $permeabilidad_aceite, $tipo_roca_aceite, $presion_yacimiento_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $presiones_aceite, $viscosidades_aceite, $factores_vol_aceite, $viscosidades_agua, $factor_dano_aceite, $bsw, $presion_fondo_aceite, $tasa_aceite, $end_point_kr_agua, $end_point_kr_gas, $end_point_kr_aceite_gas, $end_point_kr_petroleo, $exponente_corey_agua, $exponente_corey_gas, $exponente_corey_aceite_gas, $exponente_corey_petroleo, $saturacion_aceite_irred, $saturacion_agua_irred, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2, $viscosidad_agua, $lista_sg, $lista_krg, $lista_krosg, $lista_sw, $lista_krw, $lista_kro, $presion_burbuja)
    {
        if ($fluido == 2)
        {
          if ($modulo_permeabilidad_gas == 0 and $permeabilidad_gas != 0 and $porosidad_gas != 0 and $tipo_roca_gas != 0)
          {
              $modulo_permeabilidad_gas = $this->calcula_mod_permeabilidad($permeabilidad_gas, $porosidad_gas, $tipo_roca_gas);
          }
          else if($modulo_permeabilidad_gas == 0 and $permeabilidad_gas == 0 and $porosidad_gas == 0 and $tipo_roca_gas == 0)
          {
              $modulo_permeabilidad_gas = 0;
          }

          if ($tasa_gas != 0.000001 and $presion_fondo_gas != 0.000001 and $factor_dano_gas == 1.0)
          {
            $skin = $this->skin_gas($permeabilidad_abs_ini_gas, $modulo_permeabilidad_gas, $presion_fondo_gas, $presion_yacimiento_gas,$presion_inicial_gas, $tasa_gas, $presiones_gas, $viscosidades_gas, $z, $t, $espesor_reservorio_gas, $radio_drenaje_yac_gas, $radio_pozo_gas, $a1, $b1, $c1, $d1, $a2, $b2, $c2, $d2);
            $factor_dano_gas = $skin;
            $ipr_gas_results = $this->ipr_gas($permeabilidad_abs_ini_gas, $modulo_permeabilidad_gas, $presion_yacimiento_gas, $presion_inicial_gas, $z, $t, $radio_drenaje_yac_gas, $radio_pozo_gas, $factor_dano_gas, $presiones_gas, $viscosidades_gas, $espesor_reservorio_gas, $a1, $b1, $c1, $d1, $a2, $b2, $c2, $d2);
            $tasas_flujo_lista = $ipr_gas_results[0];
            $presiones_fondo_lista = $ipr_gas_results[1];
          }
          else
          {
            $skin = $factor_dano_gas;
            $ipr_gas_results = $this->ipr_gas($permeabilidad_abs_ini_gas, $modulo_permeabilidad_gas, $presion_yacimiento_gas, $presion_inicial_gas, $z, $t, $radio_drenaje_yac_gas, $radio_pozo_gas, $factor_dano_gas, $presiones_gas, $viscosidades_gas, $espesor_reservorio_gas, $a1, $b1, $c1, $d1, $a2, $b2, $c2, $d2);
            $tasas_flujo_lista = $ipr_gas_results[0];
            $presiones_fondo_lista = $ipr_gas_results[1];
          }
        }
        else
        {
          if ($modulo_permeabilidad_aceite == 0 and $permeabilidad_aceite != 0 and $porosidad_aceite != 0 and $tipo_roca_aceite != 0)
          {
            $modulo_permeabilidad_aceite = $this->calcula_mod_permeabilidad($permeabilidad_aceite, $porosidad_aceite, $tipo_roca_aceite);
          }
          else if($modulo_permeabilidad_aceite == 0 and $permeabilidad_aceite == 0 and $porosidad_aceite == 0 and $tipo_roca_aceite == 0)
          {
            $modulo_permeabilidad_aceite = 0;
          }

          if ($tasa_aceite != 0.000001 and $presion_fondo_aceite != 0.000001 and $factor_dano_aceite == 1.0)
          {
            $skin = $this->skin_aceite($presion_burbuja, $presion_yacimiento_aceite, $presion_fondo_aceite, $tasa_aceite, $permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $saturacion_agua_irred, $end_point_kr_petroleo, $end_point_kr_agua, $saturacion_aceite_irred, $exponente_corey_petroleo, $exponente_corey_agua, $end_point_kr_aceite_gas, $exponente_corey_aceite_gas, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krosg, $presiones_aceite, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2);
            $factor_dano_aceite = $skin;

            $ipr_aceite_results = $this->ipr_aceite($presion_burbuja, $presion_yacimiento_aceite, $factor_dano_aceite, $permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $saturacion_agua_irred, $end_point_kr_petroleo, $end_point_kr_agua, $saturacion_aceite_irred, $exponente_corey_petroleo, $exponente_corey_agua, $end_point_kr_aceite_gas, $exponente_corey_aceite_gas, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krosg, $presiones_aceite, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2);

            $tasas_flujo_lista = $ipr_aceite_results[0];
            $presiones_fondo_lista = $ipr_aceite_results[1];
          }
          else
          {
            $skin = $factor_dano_aceite;
            $ipr_aceite_results = $this->ipr_aceite($presion_burbuja, $presion_yacimiento_aceite, $factor_dano_aceite, $permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $saturacion_agua_irred, $end_point_kr_petroleo, $end_point_kr_agua, $saturacion_aceite_irred, $exponente_corey_petroleo, $exponente_corey_agua, $end_point_kr_aceite_gas, $exponente_corey_aceite_gas, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krosg, $presiones_aceite, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2); 
            $tasas_flujo_lista =  $ipr_aceite_results[0];
            $presiones_fondo_lista =  $ipr_aceite_results[1];
          }
        }
        return [$tasas_flujo_lista, $presiones_fondo_lista, $skin];
    }

    public function run_ipr($input_data)
    {
        $data_to_return = [];
        if(floatval($input_data['fluido'])==2.0)
        {
            $fluido = floatval($input_data['fluido']);
            $radio_pozo = floatval($input_data['radio_pozo']);
            $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);
            $presion_yacimiento = floatval($input_data['presion_yacimiento']);
            $gas_rate_g = floatval($input_data['gas_rate_g']);
            $bhp_g = floatval($input_data['bhp_g']);
            $init_res_press_text_g = floatval($input_data['init_res_press_text_g']);
            $abs_perm_init_text_g = floatval($input_data['abs_perm_init_text_g']);
            $net_pay_text_g = floatval($input_data['net_pay_text_g']);

            if($input_data['abs_perm_text_g'] === null)
            {
                $permeability_module_text_g = floatval($input_data['permeability_module_text_g']);
                $abs_perm_text_g = 0;
                $porosity_text_g = 0;
                $rock_type = 0;
            }
            else
            {
                $permeability_module_text_g = 0;
                $abs_perm_text_g = floatval($input_data['abs_perm_text_g']);
                $porosity_text_g = floatval($input_data['porosity_text_g']);
                $rock_type = floatval($input_data['rock_type']);
            }

            $temperature_text_g = floatval($input_data['temperature_text_g'])+460;

            if($input_data['c1_viscosity_fluid_g'] === null)
            {
                $c1_viscosity_fluid_g = 0;
                $c2_viscosity_fluid_g = 0;
                $c3_viscosity_fluid_g = 0;
                $c4_viscosity_fluid_g = 0;
                $c1_compressibility_fluid_g = 0;
                $c2_compressibility_fluid_g = 0;
                $c3_compressibility_fluid_g = 0;
                $c4_compressibility_fluid_g = 0;
            }
            else
            {
                $c1_viscosity_fluid_g = floatval($input_data['c1_viscosity_fluid_g']);
                $c2_viscosity_fluid_g = floatval($input_data['c2_viscosity_fluid_g']);
                $c3_viscosity_fluid_g = floatval($input_data['c3_viscosity_fluid_g']);
                $c4_viscosity_fluid_g = floatval($input_data['c4_viscosity_fluid_g']);
                $c1_compressibility_fluid_g = floatval($input_data['c1_compressibility_fluid_g']);
                $c2_compressibility_fluid_g = floatval($input_data['c2_compressibility_fluid_g']);
                $c3_compressibility_fluid_g = floatval($input_data['c3_compressibility_fluid_g']);
                $c4_compressibility_fluid_g = floatval($input_data['c4_compressibility_fluid_g']);
            }


            $factor_dano = floatval($input_data['factor_dano']);
            $pvt_pressure_gas = json_decode($input_data['pvt_pressure_gas']);
            $pvt_gasviscosity_gas = json_decode($input_data['pvt_gasviscosity_gas']);
            $pvt_gascompressibility_gas = json_decode($input_data['pvt_gascompressibility_gas']);
            $data_to_return = $this->retornogas($fluido,$radio_pozo,$radio_drenaje_yac,$presion_yacimiento,$gas_rate_g,$bhp_g,$init_res_press_text_g,$abs_perm_init_text_g,$net_pay_text_g, $permeability_module_text_g,$abs_perm_text_g,$porosity_text_g,$rock_type,$temperature_text_g,$c1_viscosity_fluid_g,$c2_viscosity_fluid_g, $c3_viscosity_fluid_g,$c4_viscosity_fluid_g,$c1_compressibility_fluid_g,$c2_compressibility_fluid_g,$c3_compressibility_fluid_g,$c4_compressibility_fluid_g, $pvt_pressure_gas,$pvt_gasviscosity_gas,$pvt_gascompressibility_gas,$factor_dano);
        }
        else if(floatval($input_data['fluido']) == 1.0)
        {
            $fluido= floatval($input_data['fluido']);
            $radio_pozo = floatval($input_data['radio_pozo']); 
            $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);      
            $presion_yacimiento = floatval($input_data['presion_yacimiento']);                   
            $bsw = floatval($input_data['bsw']);
            $tasa_flujo= floatval($input_data['tasa_flujo']);
            $presion_fondo= floatval($input_data['presion_fondo']);
            $presion_inicial = floatval($input_data['presion_inicial']);                          
            $permeabilidad_abs_ini = floatval($input_data['permeabilidad_abs_ini']);              
            $espesor_reservorio= floatval($input_data['espesor_reservorio']);  

            if($input_data['modulo_permeabilidad'] === null)
            {
                $modulo_permeabilidad = 0;
                $permeabilidad = floatval($input_data['permeabilidad']);
                $porosidad = floatval($input_data['porosidad']);
                $tipo_roca = floatval($input_data['tipo_roca']);
            }
            else
            {
                $modulo_permeabilidad = floatval($input_data['modulo_permeabilidad']);
                $permeabilidad = 0;                 
                $porosidad= 0;          
                $tipo_roca = 0;
            }

                           
            if($input_data['end_point_kr_aceite_gas'] === null)
            {
                $end_point_kr_aceite_gas = 0;
                $saturacion_gas_crit= 0;
                $end_point_kr_gas = 0;
                $saturacion_aceite_irred_gas= 0;
                $exponente_corey_aceite_gas = 0;
                $exponente_corey_gas = 0;
                $end_point_kr_petroleo = 0;
                $saturacion_agua_irred= 0;
                $end_point_kr_agua = 0;
                $saturacion_aceite_irred = 0;
                $exponente_corey_petroleo = 0;
                $exponente_corey_agua = 0;
            }                         
            else
            {
                $end_point_kr_aceite_gas = floatval($input_data['end_point_kr_aceite_gas']);
                $saturacion_gas_crit=floatval($input_data['saturacion_gas_crit']);
                $end_point_kr_gas = floatval($input_data['end_point_kr_gas']);
                $saturacion_aceite_irred_gas=floatval($input_data['saturacion_aceite_irred_gas']);
                $exponente_corey_aceite_gas = floatval($input_data['exponente_corey_aceite_gas']);
                $exponente_corey_gas = floatval($input_data['exponente_corey_gas']);
                $end_point_kr_petroleo = floatval($input_data['end_point_kr_petroleo']);
                $saturacion_agua_irred=floatval($input_data['saturacion_agua_irred']);
                $end_point_kr_agua = floatval($input_data['end_point_kr_agua']);
                $saturacion_aceite_irred = floatval($input_data['saturacion_aceite_irred']);
                $exponente_corey_petroleo = floatval($input_data['exponente_corey_petroleo']);
                $exponente_corey_agua = floatval($input_data['exponente_corey_agua']);
            }

            $saturacion_pressure = floatval($input_data['saturation_pressure']);
            if($input_data['viscosidad_agua'] === null)
            {
                $viscosidad_agua=0;
                $campo_a1=0;
                $campo_b1=0;
                $campo_c1=0;
                $campo_d1=0;
                $campo_a2=0;
                $campo_b2=0;
                $campo_c2=0;
                $campo_d2=0;
            }
            else
            {
                $viscosidad_agua=floatval($input_data['viscosidad_agua']);
                $campo_a1=floatval($input_data['campo_a1']);
                $campo_b1=floatval($input_data['campo_b1']);
                $campo_c1=floatval($input_data['campo_c1']);
                $campo_d1=floatval($input_data['campo_d1']);
                $campo_a2=floatval($input_data['campo_a2']);
                $campo_b2=floatval($input_data['campo_b2']);
                $campo_c2=floatval($input_data['campo_c2']);
                $campo_d2=floatval($input_data['campo_d2']);
            }

            $input_data['presiones'] = str_replace("[0,0]","[0]",$input_data['presiones']);
            $input_data['viscosidades_aceite'] = str_replace("[0,0]","[0]",$input_data['viscosidades_aceite']);
            $input_data['viscosidades_agua'] = str_replace("[0,0]","[0]",$input_data['viscosidades_agua']);
            $input_data['factores_vol_aceite'] = str_replace("[0,0]","[0]",$input_data['factores_vol_aceite']);

            $presiones = json_decode($input_data['presiones']);
            $viscosidades_aceite = json_decode($input_data['viscosidades_aceite']);
            $factores_vol_aceite = json_decode($input_data['factores_vol_aceite']);
            $viscosidades_agua = json_decode($input_data['viscosidades_agua']);
            $lista_sg = json_decode($input_data['lista_sg']);
            $lista_krg = json_decode($input_data['lista_krg']);
            $lista_krosg = json_decode($input_data['lista_krosg']);
            $lista_sw = json_decode($input_data['lista_sw']);
            $lista_krw = json_decode($input_data['lista_krw']);
            $lista_kro = json_decode($input_data['lista_kro']);
            $factor_dano = floatval($input_data['factor_dano']);

        
            $data_to_return = $this->retornoaceite($permeabilidad_abs_ini, $modulo_permeabilidad, $porosidad, $permeabilidad, $tipo_roca, $presion_yacimiento, $presion_inicial, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $presiones, $viscosidades_aceite, $factores_vol_aceite,
                $viscosidades_agua,  $bsw, $presion_fondo, $tasa_flujo, $end_point_kr_agua, $end_point_kr_gas, $end_point_kr_aceite_gas, $end_point_kr_petroleo, $exponente_corey_agua, $exponente_corey_gas, $exponente_corey_aceite_gas, $exponente_corey_petroleo, $saturacion_aceite_irred,
                $saturacion_agua_irred, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2, $viscosidad_agua, $lista_sg, $lista_krg, $lista_krosg, $lista_sw, $lista_krw, $lista_kro, $saturacion_pressure,$factor_dano);
        }
        else if(floatval($input_data['fluido']) == 3.0)
        {
                $fluid= floatval($input_data['fluido']);
                $well_radius = floatval($input_data['well_radius']); 
                $drainage_radius = floatval($input_data['drainage_radius']);      
                $reservoir_pressure = floatval($input_data['reservoir_pressure']);                   
                $gas_rate_c_g = floatval($input_data['gas_rate_c_g']);                   
                $bhp_c_g = floatval($input_data['bhp_c_g']);
                $initial_pressure_c_g= floatval($input_data['initial_pressure_c_g']);
                $ini_abs_permeability_c_g= floatval($input_data['ini_abs_permeability_c_g']);
                $netpay_c_g = floatval($input_data['netpay_c_g']);     

                if($input_data['permeability_module_c_g'] === null)
                {
                    $permeability_module_c_g = 0;
                    $permeability_c_g = floatval($input_data['permeability_c_g']);
                    $porosity_c_g = floatval($input_data['porosity_c_g']);
                    $rock_type_c_g = floatval($input_data['rock_type_c_g']) ;
                }
                else
                {
                    $permeability_module_c_g = floatval($input_data['permeability_module_c_g']);
                    $permeability_c_g = 0;                 
                    $porosity_c_g= 0;          
                    $rock_type_c_g = 0;
                }

                $saturation_pressure_c_g = floatval($input_data['saturation_pressure_c_g']);              
                $gor_c_g = floatval($input_data['gor_c_g']);  
                $sg_data = json_decode($input_data['sg_data']);  
                $krg_data = json_decode($input_data['krg_data']);  
                $krog_data = json_decode($input_data['krog_data']);  
                $pressure_data = json_decode($input_data['pressure_data']);  
                $bo_data = json_decode($input_data['bo_data']);  
                $vo_data = json_decode($input_data['vo_data']);  
                $rs_data = json_decode($input_data['rs_data']);  
                $bg_data = json_decode($input_data['bg_data']);  
                $vg_data = json_decode($input_data['vg_data']);  
                $og_ratio_data = json_decode($input_data['og_ratio_data']);  
                $dropout_pressure_data = json_decode($input_data['dropout_pressure_data']);  
                $dropout_liquid_percentage = json_decode($input_data['dropout_liquid_percentage']);  
                $skin = floatval($input_data['factor_dano']);

                if($input_data['permeability_module_c_g'] === null)
                {
                    $modulo_permeabilidad = 0;
                    $permeability_c_g = floatval($input_data['permeability_c_g']);
                    $porosity_c_g = floatval($input_data['porosity_c_g']);
                    $rock_type_c_g = floatval($input_data['rock_type_c_g']);
                }
                else
                {
                    $permeability_module_c_g = floatval($input_data['permeability_module_c_g']);
                    $permeability_c_g = 0;                 
                    $porosity_c_g= 0;          
                    $rock_type_c_g = 0;
                }

                $data_to_return = $this->retornogascondensado($fluid, $well_radius, $drainage_radius, $reservoir_pressure, $gas_rate_c_g, $bhp_c_g, $initial_pressure_c_g, $ini_abs_permeability_c_g, $netpay_c_g, $permeability_module_c_g, $permeability_c_g, $porosity_c_g, $rock_type_c_g, $saturation_pressure_c_g, $gor_c_g, $sg_data, $krg_data, $krog_data, $pressure_data, $bo_data, $vo_data, $rs_data, $bg_data, $vg_data, $og_ratio_data, $dropout_pressure_data, $dropout_liquid_percentage, $skin);
        }

        return json_encode($data_to_return); 
    }
}

