<?php

namespace App\Http\Controllers;

if(!isset($_SESSION)) { session_start(); }
use DB;
use Validator;
use Illuminate\Http\Request;
use App\ipr;
use Log;

use App\Http\Requests;
use App\Http\Requests\IPRRequest;
use App\ipr_tabla;
use App\ipr_intervalo_results;
use App\ipr_tabla_gasliquid;
use App\ipr_tabla_wateroil;
use App\ipr_resultado;
use App\ipr_resultado_skin_ideal;
use App\ipr_pvt_gas;
use App\ipr_gas_oil_kr_c_g;
use App\ipr_dropout_c_g;
use App\ipr_pvt_c_g;
use App\ipr_shrinkage_curve;

/* Para nueva forma de usar formaciones */
use App\formations_scenary;

/* use App\Pozo; */
use App\pozo;
use App\cuenca;
use App\campo;
use App\formacion;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use App\formacionxpozo;
use App\escenario;
use View;

class IPR2Controller extends Controller
{
    /**
    * Despliega la vista iprs y precarga información del pozo, intervalo productor y formación.
    *
    * @return \Illuminate\Http\Response
    */
    public function index($id_escenario) {

        if (\Auth::check()) {

            $escenario = DB::table('escenarios')->where('id', $id_escenario)->first();
            $pozo = Pozo::find($escenario->pozo_id);
            $IPR = new ipr();

            if (strpos($escenario->formacion_id, ',')) {
                $forms_id = explode(',', $escenario->formacion_id);
                $pvt = [];
                $formacion = [];
                foreach ($forms_id as $form_id) {
                    $pvt[] = DB::table('pvt_formacion_x_pozos')->where('formacionxpozos_id',$form_id)->get();
                    $formacion[] = DB::table('formacionxpozos')->where('id','=',$form_id)->first();
                }
            } else {
                $formacion = DB::table('formacionxpozos')->where([
                    'id' => $escenario->formacion_id
                ])->get();
            }

            $cuenca = cuenca::find($escenario->cuenca_id);
            $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
            $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$escenario->id)->first();
            $advisor = 'true';

            return View('template.iprs.create', compact(['user', 'pozo', 'formacion', 'fluido', 'campo', 'IPR', 'escenario', 'advisor', 'cuenca']));

        } else {
            return view('loginfirst');
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
        abort('404');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(IPRRequest $request,$id_escenario) { 
        if (\Auth::check()) {

            $tipo_save = ($request->modo_submit == 1 ? false : true);
            $datos_vista = null;
            if($request->input("fluido") == "1") {

                $datos_vista = $this->saveBlackOil($id_escenario,$request,$tipo_save);

            } else if($request->input("fluido") == "2") {

                $datos_vista = $this->saveDryGas($id_escenario,$request,$tipo_save);

            } else if($request->input("fluido") == "3") {

                $datos_vista = $this->saveCondensateGas($id_escenario,$request,$tipo_save);

            } else if($request->input("fluido") == "4") {

                $datos_vista = $this->saveInjectorWater($id_escenario,$request,$tipo_save);

            } else if($request->input("fluido") == "5") {

                $datos_vista = $this->saveInjectorGas($id_escenario,$request,$tipo_save);

            }

            if(!is_null($datos_vista)){
                $id = $datos_vista['IPR']->id_escenario;
                return redirect(url('IPR/result',$id));
            } else {
                abort('404');
            }

        } else {
            return view('loginfirst');
        }

    }

    private function saveBasicPetrophysicsData($id_escenario, $stress_sensitive_reservoir, $initial_reservoir_pressure, $absolute_permeability_it_initial_reservoir_pressure, $permeability_module, $current_reservoir_pressure, $net_pay, $reservoir_parting_pressure, $absolute_permeability)
    {
        $escenario = escenario::find($id_escenario);
        $formaciones = explode(',', $escenario->formacion_id);
        foreach ($formaciones as $key => $id_formation) {

            $formationScenary = formations_scenary::where('id_scenary',$id_escenario)->where('id_formation',$id_formation)->first();
            if (!$formationScenary) {
                $formationScenary = new formations_scenary();
            }

            $formationScenary->id_scenary = $id_escenario;
            $formationScenary->id_formation = $id_formation;
            $formationScenary->stress_sensitive_reservoir = isset($stress_sensitive_reservoir[$key]) ? $stress_sensitive_reservoir[$key] : null; /* stress_sensitive_reservoir */
            $formationScenary->initial_reservoir_pressure = isset($initial_reservoir_pressure[$key]) ? $initial_reservoir_pressure[$key] : null; /* presion_inicial */
            $formationScenary->absolute_permeability_it_initial_reservoir_pressure = isset($absolute_permeability_it_initial_reservoir_pressure[$key]) ? $absolute_permeability_it_initial_reservoir_pressure[$key] : null; /* permeabilidad_abs_ini */
            $formationScenary->permeability_module = isset($permeability_module[$key]) ? $permeability_module[$key] : null; /* modulo_permeabilidad */
            $formationScenary->current_reservoir_pressure = isset($current_reservoir_pressure[$key]) ? $current_reservoir_pressure[$key] : null; /* presion_yacimiento */
            $formationScenary->net_pay = isset($net_pay[$key]) ? $net_pay[$key] : null; /* espesor_reservorio */
            $formationScenary->absolute_permeability = isset($absolute_permeability[$key]) ? $absolute_permeability[$key] : null; /*  */

            $formationScenary->reservoir_parting_pressure = isset($reservoir_parting_pressure[$key]) ? $reservoir_parting_pressure[$key] : null; /* Solo se muestra si es injector el tipo de pozo */
            
            $formationScenary->save();

        }
    }

    /**
    * Esta función reconstruye el input enviado al módulo de python con base en las sensibilidades 
    * que el usuario escoge, ejecuta de nuevo el módulo y presenta los nuevos resultados junto al caso base.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function sensibility(IPRRequest $request)
    {
        if (\Auth::check()) {
            set_time_limit(360); 
            $IPR = ipr::find($request->get("id_ipr"));
            $scenary = escenario::find($IPR->id_escenario);

            /* Resultado ipr calculado e ipr skin ideal */
            $ipr_result_calculated_skin = ipr_resultado::select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();        
            $ipr_result_ideal_skin = ipr_resultado_skin_ideal::select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();
            $skin_resultante = ipr_resultado::select('skin')->where('id_ipr',$IPR->id)->first();

            $skin_resultante_final = $skin_resultante->skin;

            $i_tables = 0;
            $ipr_resultados = array();
            $categorias = array();
            $eje_y = array();
            $data = array(); 
            $skin = 0; 
            $skin_tmp = 0; 

            foreach ($ipr_result_calculated_skin as $value) {   
                $skin = $value->skin;
                $data[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias[] = (float)$value->tasa_flujo;
                $eje_y[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }

            $rates_inflow = $categorias;
            $pressures_inflow = $eje_y;

            $categorias = json_encode($categorias);
            $eje_y = json_encode($eje_y);

            /* $data = json_encode($data); */
            $categorias_skin_ideal = array();
            $eje_y_skin_ideal = array();
            $data_skin_ideal = array();

            foreach ($ipr_result_ideal_skin as $value) {   
                $data_skin_ideal[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias_skin_ideal[] = (float)$value->tasa_flujo;
                $eje_y_skin_ideal[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }

            $categorias_skin_ideal = json_encode($categorias_skin_ideal);
            $eje_y_skin_ideal = json_encode($eje_y_skin_ideal);
            /* $data_skin_ideal = json_encode($data_skin_ideal); */

            if($IPR->fluido =="1") {

                $tasa_flujo = $IPR->tasa_flujo;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = isset($IPR->tipo_roca) ? $IPR->tipo_roca : 0;

            } else if($IPR->fluido =="2") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = isset($IPR->rock_type) ? $IPR->rock_type : 0;

            } else if($IPR->fluido =="3") {

                $tasa_flujo = $IPR->gas_rate_c_g;
                $presion_fondo = $IPR->bhp_c_g;
                $tipo_roca = isset($IPR->rock_type_c_g) ? $IPR->rock_type_c_g : 0;

            } else if($IPR->fluido =="4") {

                $tasa_flujo = $IPR->injection_rate;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = 0;

            } else if($IPR->fluido =="5") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = 0;
                
            }

            $data_i = $data_skin_ideal;
            $ejes_dobles[] = $data;
            $ejes_dobles[] = $data_i;
            $etiquetas_ejes[] = 'Current IPR';
            $etiquetas_ejes[] = 'Base Case';

            /* Generación del set de datos y normalización de las tablas para el envío al módulo de python. */
            if($IPR->fluido == "1") {
                $table = ipr_tabla::select('presion', 'factor_volumetrico_aceite', 'viscosidad_aceite', 'viscosidad_agua')->where('id_ipr', $IPR->id)->get();
                $valid_table = false;
                $a = 0;
                $presionesv = array();
                $viscosidades_aceite= array();
                $factores_vol_aceite = array();
                $viscosidades_agua= array();

                foreach ($table as $value) {
                    $presionesv[] = (float)$value->presion;
                    $viscosidades_aceite[] = (float)$value->viscosidad_aceite;
                    $factores_vol_aceite[] = (float)$value->factor_volumetrico_aceite;
                    $viscosidades_agua[] = (float)$value->viscosidad_agua;
                }

                /* Water oil table - ipr oil */
                $tabla_wateroil = DB::table('ipr_tabla_water')->select('lista_sw', 'lista_krw', 'lista_kro')->where('id_ipr', $IPR->id)->get();
                $lista_sw = array();
                $lista_krw = array();
                $lista_kro = array();
                foreach ($tabla_wateroil as $value) {
                    $i_tables++;

                    $lista_sw[] = (float)$value->lista_sw;
                    $lista_krw[] = (float)$value->lista_krw;
                    $lista_kro[] = (float)$value->lista_kro;
                }

                /* Gas oil table - ipr oil */
                $tabla_gasliquid = DB::table('ipr_tabla_gas')->select('lista_sg', 'lista_krg', 'lista_krosg')->where('id_ipr', $IPR->id)->get();
                $lista_sg = array();
                $lista_krg = array();
                $lista_krosg = array();
                foreach ($tabla_gasliquid as $value) {
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


                $gas_table = json_encode($gas_table); /* Oil */
                $tabla = json_encode($table); /* Oil */
                $lista_sg = json_encode($lista_sg);
                $lista_krg = json_encode($lista_krg);
                $lista_krosg = json_encode($lista_krosg);
                $lista_sw = json_encode($lista_sw);
                $lista_krw = json_encode($lista_krw);
                $lista_kro = json_encode($lista_kro);

            } else if($IPR->fluido == "2") {
                /* Gas oil table - ipr oil */
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

                $water_table = "[[0,0,0]]"; /* Oil */
                $gas_table = "[[0,0,0]]"; /* Oil */
                $tabla = "[[0,0,0]]"; /* Oil */
                $lista_pressure = json_encode($lista_pressure); /* Gas */
                $lista_gas_viscosity = json_encode($lista_gas_viscosity); /* Gas */
                $lista_gas_compressibility = json_encode($lista_gas_compressibility); /* Gas */

            } else if($IPR->fluido == "3") {
                /* Condesate Gas Tables */
                $water_table = "[[0,0,0]]"; /* Oil */
                $gas_table = "[[0,0,0]]"; /* Oil */
                $tabla = "[[0,0,0]]"; /* Oil */
                $pvt_gas_table = "[[0,0,0]]"; /* Gas */
                $sg_data = array();
                $krg_data = array();
                $krog_data = array();
                $kr_c_g_table_data = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->get();

                foreach ($kr_c_g_table_data as $value) {
                    $sg_data[] = (float)$value->sg;
                    $krg_data[] = (float)$value->krg; 
                    $krog_data[] = (float)$value->krog;
                }

                $sg_data = json_encode($sg_data);
                $krg_data = json_encode($krg_data);
                $krog_data = json_encode($krog_data);

                /* PVT Condensate Gas */
                $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));
                $pressure_data = array();
                $bo_data = array();
                $vo_data = array();
                $rs_data = array();
                $bg_data = array();
                $vg_data = array();
                $og_ratio_data = array();
                $pvt_c_g_table_data = DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->get();

                foreach ($pvt_c_g_table_data as $value) {
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

                /* Drop out Condensate Gas */
                $dropout_c_g_table_data = DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->get();
                foreach ($dropout_c_g_table_data as $value) {
                    $dropout_pressure_data[] = $value->pressure;
                    $dropout_liquid_percentage[] = $value->liquid_percentage;
                }

                $dropout_pressure_data = json_encode($dropout_pressure_data);
                $dropout_liquid_percentage = json_encode($dropout_liquid_percentage);
                
            } else if($IPR->fluido == "4") {

                /* Input Data */

            } else if($IPR->fluido == "5") {

                /* Gas oil table - ipr oil */
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

                $water_table = "[[0,0,0]]"; /* Oil */
                $gas_table = "[[0,0,0]]"; /* Oil */
                $tabla = "[[0,0,0]]"; /* Oil */
                $lista_pressure = json_encode($lista_pressure); /* Gas */
                $lista_gas_viscosity = json_encode($lista_gas_viscosity); /* Gas */
                $lista_gas_compressibility = json_encode($lista_gas_compressibility); /* Gas */

            }

            /* Definición de valores y tipos de las sensibilidades. */
            $inputs = [];
            $ipr_res_status = [];
            $sensibilidades = !is_null($request->get("sensibilidades")) ? $request->get("sensibilidades") : [];
            foreach($sensibilidades as $key => $sensibilidad) {
                $k_index = $key;
                $key = $key + 1;
                $label = "";
                $valor = $sensibilidad['valor'];
                $sensibilidad = $sensibilidad['sensibilidad'];

                if($IPR->fluido == "1") {

                    $var_mod_perm = "modulo_permeabilidad";
                    $var_net_pay = "espesor_reservorio";
                    $var_abs_perm = "permeabilidad_abs_ini";
                    $var_bhp = "presion_fondo";

                    $input_data =  [
                        "presiones" => json_encode($presionesv),
                        "viscosidades_aceite" => json_encode($viscosidades_aceite),
                        "factores_vol_aceite" => json_encode($factores_vol_aceite),
                        "viscosidades_agua" => json_encode($viscosidades_agua),
                        "fluido" => $IPR->fluido,
                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "presion_yacimiento" => $IPR->presion_yacimiento,
                        "bsw" => $IPR->bsw,
                        "tasa_flujo" => $IPR->tasa_flujo,
                        "presion_fondo" => $IPR->presion_fondo,
                        "stress_sensitive_reservoir" => $IPR->stress_sensitive_reservoir,
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
                        "lista_sg" => $lista_sg, /* Gas-oil */
                        "lista_krg" => $lista_krg,
                        "lista_krosg" => $lista_krosg,
                        "lista_sw" => $lista_sw, /* Water-oil */
                        "lista_krw" => $lista_krw,
                        "lista_kro" => $lista_kro,
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="2") {

                    $var_mod_perm = "permeability_module_text_g";
                    $var_net_pay = "net_pay_text_g";
                    $var_abs_perm = "abs_perm_init_text_g";
                    $var_bhp = "bhp_g";

                    $input_data = [
                        "fluido" => $IPR->fluido,
                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "presion_yacimiento" => $IPR->presion_yacimiento,
                        "gas_rate_g" => $IPR->gas_rate_g,
                        "tasa_flujo" => $IPR->gas_rate_g,
                        "bhp_g" => $IPR->bhp_g,
                        "stress_sensitive_reservoir" => $IPR->stress_sensitive_reservoir,
                        "init_res_press_text_g" => $IPR->init_res_press_text_g,
                        "abs_perm_init_text_g" => $IPR->abs_perm_init_text_g,
                        "net_pay_text_g" => $IPR->net_pay_text_g,
                        "permeability_module_text_g" => $IPR->permeability_module_text_g,
                        "abs_perm_text_g" => $IPR->abs_perm_text_g,
                        "porosity_text_g" => $IPR->porosity_text_g,
                        "rock_type" => $IPR->rock_type,
                        "temperature_text_g" => $IPR->temperature_text_g,
                        "pvt_pressure_gas" => $lista_pressure, /* Pvt-Gas */
                        "pvt_gasviscosity_gas" => $lista_gas_viscosity,
                        "pvt_gascompressibility_gas" => $lista_gas_compressibility,
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="3") {

                    $var_mod_perm = "permeability_module_c_g";
                    $var_net_pay = "netpay_c_g";
                    $var_abs_perm = "ini_abs_permeability_c_g";
                    $var_bhp = "bhp_c_g";

                    $input_data = [
                        "well_type" => $IPR->well_Type,
                        "fluido" => $IPR->fluido,
                        "well_radius" => $IPR->radio_pozo,
                        "drainage_radius" => $IPR->radio_drenaje_yac,
                        "reservoir_pressure" => $IPR->presion_yacimiento,
                        "gas_rate_c_g" => $IPR->gas_rate_c_g,
                        "tasa_flujo" => $IPR->gas_rate_c_g,
                        "bhp_c_g" => $IPR->bhp_c_g,
                        "stress_sensitive_reservoir" => $IPR->stress_sensitive_reservoir,
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
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="4") {

                    $var_mod_perm = "modulo_permeabilidad";
                    $var_net_pay = "espesor_reservorio";
                    $var_abs_perm = "permeabilidad";
                    $var_bhp = "bhfp";

                    $input_data = [
                        "well_type" => $IPR->well_Type,
                        "fluido" => $IPR->fluido,
                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "injection_rate" => $IPR->injection_rate,
                        "tasa_flujo" => $IPR->injection_rate,
                        "bhfp" => $IPR->presion_fondo,
                        "stress_sensitive_reservoir" => $IPR->stress_sensitive_reservoir,
                        "presion_inicial" => $IPR->presion_inicial,
                        "permeabilidad_abs_ini" => $IPR->permeabilidad_abs_ini,
                        "espesor_reservorio" => $IPR->espesor_reservorio,
                        "presion_yacimiento" => $IPR->presion_yacimiento,
                        "presion_separacion" => $IPR->reservoir_parting_pressure,
                        "modulo_permeabilidad" => $IPR->modulo_permeabilidad,
                        "permeabilidad" => $IPR->permeabilidad,
                        "water_volumetric_factor" => $IPR->water_volumetric_factor,
                        "water_viscosity" => $IPR->water_viscosity,
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="5") {

                    $var_mod_perm = "permeability_module_text_g";
                    $var_net_pay = "net_pay_text_g";
                    $var_abs_perm = "abs_perm_init_text_g";
                    $var_bhp = "bhp_g";

                    $input_data = [
                        "fluido" => $IPR->fluido,
                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "presion_yacimiento" => $IPR->presion_yacimiento,
                        "gas_rate_g" => $IPR->gas_rate_g,
                        "tasa_flujo" => $IPR->gas_rate_g,
                        "bhp_g" => $IPR->bhp_g,
                        "stress_sensitive_reservoir" => $IPR->stress_sensitive_reservoir,
                        "init_res_press_text_g" => $IPR->init_res_press_text_g,
                        "abs_perm_init_text_g" => $IPR->abs_perm_init_text_g,
                        "net_pay_text_g" => $IPR->net_pay_text_g,
                        "permeability_module_text_g" => $IPR->permeability_module_text_g,
                        "abs_perm_text_g" => $IPR->abs_perm_text_g,
                        "presion_separacion" => $IPR->reservoir_parting_pressure,
                        "porosity_text_g" => $IPR->porosity_text_g,
                        "rock_type" => $IPR->rock_type,
                        "temperature_text_g" => $IPR->temperature_text_g,
                        "pvt_pressure_gas" => $lista_pressure, /* Pvt-Gas */
                        "pvt_gasviscosity_gas" => $lista_gas_viscosity,
                        "pvt_gascompressibility_gas" => $lista_gas_compressibility,
                        "sensibilidad" => $sensibilidad,
                    ];

                }

                $input_data["id_escenario"] = $scenary->id;

                switch ($sensibilidad) {
                    case 'factor_dano':

                    $label = "Skin | Value";
                    $input_data["factor_dano"] = (float)$valor;

                    break;
                    case 'modulo_permeabilidad':

                    $label = "Permeability Module - Value";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data[$var_mod_perm] = (float)$valor;
                    $input_data['modificado'] = $var_mod_perm;

                    break;
                    case 'netpay':

                    $label = "Net Pay - Value";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data[$var_net_pay] = (float)$valor;
                    $input_data['modificado'] = $var_net_pay;

                    break;
                    case 'absolute_permeability':

                    $label = "Abosulte Permeability - Value";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data[$var_abs_perm] = (float)$valor;
                    $input_data['modificado'] = $var_abs_perm;

                    break;
                    case 'bhp':

                    $label = "BHP - Value";
                    $input_data[$var_bhp] = (float)$valor;
                    $input_data['modificado'] = $var_bhp;

                    break;
                    case 'bsw':

                    $label = "BSW - Value";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data["bsw"] = (float)$valor;
                    $input_data['modificado'] = "bsw";

                    break;
                    case 'corey':

                    $label = "Corey Exponent Oil - Value";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data["exponente_corey_petroleo"] = (float)$valor;
                    $input_data['modificado'] = "exponente_corey_petroleo";

                    break;
                    default:

                    $label = "Skin - Value";
                    $input_data["factor_dano"] = (float)$valor;
                    $input_data['modificado'] = "factor_dano";

                    break;
                }

                $inputs[] = $input_data ;

                /* Cálculo módulo IPR */
                // $ipr_resultados_skins = json_decode($this->run_ipr($input_data));
                $ipr_resultados_skins = json_decode($this->multirun_ipr($input_data,$input_data["factor_dano"]));
                $ipr_res_status[$k_index] = ['ideal' => $ipr_resultados_skins->ipr_cero, 'current' => $ipr_resultados_skins->ipr];

                /* Captura de resultados y tratamiento de datos */
                $ipr_resultados = array();
                $categorias = array();
                $eje_y = array();

                $data = array();

                $tasa_flujo_resultados = $ipr_resultados_skins->ipr[0];
                $presion_fondo_resultados = $ipr_resultados_skins->ipr[1];
                $skin_resultados = $ipr_resultados_skins->skin;

                for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) { 
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

                $rates_outflow = $categorias;
                $pressures_outflow = $eje_y;

                $eje_y = json_encode($eje_y);

                $ejes_dobles[] = $data;
                $etiquetas_ejes[] = $label . ": " . $valor;

                $valores[] = $valor;

                $ejes[] = $eje_y;

                if($IPR->fluido =="1") {

                    $tasa_flujo = $IPR->tasa_flujo;
                    $presion_fondo = $IPR->presion_fondo;

                } else if($IPR->fluido =="2") {

                    $tasa_flujo = $IPR->gas_rate_g;
                    $presion_fondo = $IPR->bhp_g;

                } else if($IPR->fluido =="3") {

                    $tasa_flujo = $IPR->gas_rate_c_g;
                    $presion_fondo = $IPR->bhp_c_g;

                } else if($IPR->fluido =="4") {

                    $tasa_flujo = $IPR->injection_rate;
                    $presion_fondo = $IPR->presion_fondo;

                } else if($IPR->fluido =="5") {

                    $tasa_flujo = $IPR->gas_rate_g;
                    $presion_fondo = $IPR->bhp_g;

                }
            }

            $operpo = [];
            if(isset($request->outflowcurve)) {
                $outflowcurve = $request->outflowcurve;
                $outFlowTable = [];

                $rates_outflow = json_decode($outflowcurve['rates_outflow']);
                $pressures_outflow = json_decode($outflowcurve['pressures_outflow']);
                $rates_inflow = json_decode($outflowcurve['rates_inflow']);
                $pressures_inflow = json_decode($outflowcurve['pressures_inflow']);

                foreach ($rates_outflow as $key => $value) {
                    $outFlowTable[] = [round($value, 3), round($pressures_outflow[$key], 3)];
                }
                $ejes_dobles[] = $outFlowTable;
                $etiquetas_ejes[] = 'Outflow Data';
            }

            if((float)$IPR->tasa_flujo != 0.000001 && (float)$IPR->presion_fondo != 0.000001) {
                $ejes_dobles[] = [[round($tasa_flujo, 3), round($presion_fondo, 3)]];
                $etiquetas_ejes[] = "Production Data";
            }  

            return response()->json(['etiquetas_ejes' => $etiquetas_ejes, 'ejes_dobles' => $ejes_dobles , 'ipr_status' => $ipr_res_status]);   
        } else {
            return view('loginfirst');
        }
    }

    /**
    * Esta función reconstruye el input enviado al módulo de python con base en las sensibilidades 
    * que el usuario escoge, ejecuta de nuevo el módulo y presenta los nuevos resultados junto al caso base.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function sensibility_advanced(IPRRequest $request)
    {   
        if (\Auth::check()) {
            set_time_limit(360); 
            $IPR = ipr::find($request->get("id_ipr"));
            $scenary = escenario::find($IPR->id_escenario);

            /* Resultado ipr calculado e ipr skin ideal */
            $ipr_result_calculated_skin = ipr_resultado::select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();        
            $ipr_result_ideal_skin = ipr_resultado_skin_ideal::select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();
            $skin_resultante = ipr_resultado::select('skin')->where('id_ipr',$IPR->id)->first();
            $skin_resultante_final = $skin_resultante->skin;

            $i_tables = 0;
            $ipr_resultados = array();
            $categorias = array();
            $eje_y = array();
            $data = array(); 
            $skin = 0; 
            $skin_tmp = 0; 

            foreach ($ipr_result_calculated_skin as $value) {   
                $skin = $value->skin;
                $data[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias[] = (float)$value->tasa_flujo;
                $eje_y[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }

            $rates_inflow = $categorias;
            $pressures_inflow = $eje_y;

            $categorias = json_encode($categorias);
            $eje_y = json_encode($eje_y);

            /* $data = json_encode($data); */
            $categorias_skin_ideal = array();
            $eje_y_skin_ideal = array();
            $data_skin_ideal = array();

            foreach ($ipr_result_ideal_skin as $value) {   
                $data_skin_ideal[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias_skin_ideal[] = (float)$value->tasa_flujo;
                $eje_y_skin_ideal[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }

            $categorias_skin_ideal = json_encode($categorias_skin_ideal);
            $eje_y_skin_ideal = json_encode($eje_y_skin_ideal);
            /* $data_skin_ideal = json_encode($data_skin_ideal); */

            if($IPR->fluido =="1") {

                $tasa_flujo = $IPR->tasa_flujo;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = isset($IPR->tipo_roca) ? $IPR->tipo_roca : 0;

            } else if($IPR->fluido =="2") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = isset($IPR->rock_type) ? $IPR->rock_type : 0;

            } else if($IPR->fluido =="3") {

                $tasa_flujo = $IPR->gas_rate_c_g;
                $presion_fondo = $IPR->bhp_c_g;
                $tipo_roca = isset($IPR->rock_type_c_g) ? $IPR->rock_type_c_g : 0;

            } else if($IPR->fluido =="4") {

                $tasa_flujo = $IPR->injection_rate;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = 0;

            } else if($IPR->fluido =="5") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = 0;
                
            }

            $data_i = $data_skin_ideal;
            $ejes_dobles[] = $data;
            $ejes_dobles[] = $data_i;
            $etiquetas_ejes[] = 'Current IPR';
            $etiquetas_ejes[] = 'Base Case';

            /* Generación del set de datos y normalización de las tablas para el envío al módulo de python. */
            if($IPR->fluido == "1") {
                $table = ipr_tabla::select('presion', 'factor_volumetrico_aceite', 'viscosidad_aceite', 'viscosidad_agua')->where('id_ipr', $IPR->id)->get();
                $valid_table = false;
                $a = 0;
                $presionesv = array();
                $viscosidades_aceite= array();
                $factores_vol_aceite = array();
                $viscosidades_agua= array();

                foreach ($table as $value) {
                    $presionesv[] = (float)$value->presion;
                    $viscosidades_aceite[] = (float)$value->viscosidad_aceite;
                    $factores_vol_aceite[] = (float)$value->factor_volumetrico_aceite;
                    $viscosidades_agua[] = (float)$value->viscosidad_agua;
                }

                /* Water oil table - ipr oil */
                $tabla_wateroil = DB::table('ipr_tabla_water')->select('lista_sw', 'lista_krw', 'lista_kro')->where('id_ipr', $IPR->id)->get();
                $lista_sw = array();
                $lista_krw = array();
                $lista_kro = array();
                foreach ($tabla_wateroil as $value) {
                    $i_tables++;

                    $lista_sw[] = (float)$value->lista_sw;
                    $lista_krw[] = (float)$value->lista_krw;
                    $lista_kro[] = (float)$value->lista_kro;
                }

                /* Gas oil table - ipr oil */
                $tabla_gasliquid = DB::table('ipr_tabla_gas')->select('lista_sg', 'lista_krg', 'lista_krosg')->where('id_ipr', $IPR->id)->get();
                $lista_sg = array();
                $lista_krg = array();
                $lista_krosg = array();
                foreach ($tabla_gasliquid as $value) {
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


                $gas_table = json_encode($gas_table); /* Oil */
                $tabla = json_encode($table); /* Oil */
                $lista_sg = json_encode($lista_sg);
                $lista_krg = json_encode($lista_krg);
                $lista_krosg = json_encode($lista_krosg);
                $lista_sw = json_encode($lista_sw);
                $lista_krw = json_encode($lista_krw);
                $lista_kro = json_encode($lista_kro);

            } else if($IPR->fluido == "2") {
                /* Gas oil table - ipr oil */
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

                $water_table = "[[0,0,0]]"; /* Oil */
                $gas_table = "[[0,0,0]]"; /* Oil */
                $tabla = "[[0,0,0]]"; /* Oil */
                $lista_pressure = json_encode($lista_pressure); /* Gas */
                $lista_gas_viscosity = json_encode($lista_gas_viscosity); /* Gas */
                $lista_gas_compressibility = json_encode($lista_gas_compressibility); /* Gas */

            } else if($IPR->fluido == "3") {
                /* Condesate Gas Tables */
                $water_table = "[[0,0,0]]"; /* Oil */
                $gas_table = "[[0,0,0]]"; /* Oil */
                $tabla = "[[0,0,0]]"; /* Oil */
                $pvt_gas_table = "[[0,0,0]]"; /* Gas */
                $sg_data = array();
                $krg_data = array();
                $krog_data = array();
                $kr_c_g_table_data = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->get();

                foreach ($kr_c_g_table_data as $value) {
                    $sg_data[] = (float)$value->sg;
                    $krg_data[] = (float)$value->krg; 
                    $krog_data[] = (float)$value->krog;
                }

                $sg_data = json_encode($sg_data);
                $krg_data = json_encode($krg_data);
                $krog_data = json_encode($krog_data);

                /* PVT Condensate Gas */
                $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));
                $pressure_data = array();
                $bo_data = array();
                $vo_data = array();
                $rs_data = array();
                $bg_data = array();
                $vg_data = array();
                $og_ratio_data = array();
                $pvt_c_g_table_data = DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->get();

                foreach ($pvt_c_g_table_data as $value) {
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

                /* Drop out Condensate Gas */
                $dropout_c_g_table_data = DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->get();
                foreach ($dropout_c_g_table_data as $value) {
                    $dropout_pressure_data[] = $value->pressure;
                    $dropout_liquid_percentage[] = $value->liquid_percentage;
                }

                $dropout_pressure_data = json_encode($dropout_pressure_data);
                $dropout_liquid_percentage = json_encode($dropout_liquid_percentage);
                
            } else if($IPR->fluido == "4") {

                /* Input Data */

            } else if($IPR->fluido == "5") {

                /* Gas oil table - ipr oil */
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

                $water_table = "[[0,0,0]]"; /* Oil */
                $gas_table = "[[0,0,0]]"; /* Oil */
                $tabla = "[[0,0,0]]"; /* Oil */
                $lista_pressure = json_encode($lista_pressure); /* Gas */
                $lista_gas_viscosity = json_encode($lista_gas_viscosity); /* Gas */
                $lista_gas_compressibility = json_encode($lista_gas_compressibility); /* Gas */

            }

            $intervalo = formations_scenary::where('id_scenary',$scenary->id)->first();

            /* Definición de valores y tipos de las sensibilidades. */
            $inputs = [];
            $sensibilidades = !is_null($request->get("sensibilidades")) ? $request->get("sensibilidades") : [];
            foreach($sensibilidades as $key => $sensibilidad) {

                $key = $key + 1;
                $label = "";
                $valor = $sensibilidad['valor'];
                $sensibilidad = $sensibilidad['sensibilidad'];
                $condicional_sensitive = $IPR->stress_sensitive_reservoir;

                $var_rpp = "presion_separacion";
                $var_rdr = "radio_drenaje_yac";
                $var_mod_perm = "permeability_module";
                $var_net_pay = "net_pay";
                $var_abs_perm = "absolute_permeability";
                $var_bhp = "presion_fondo";
                $var_py = "current_reservoir_pressure";

                if($IPR->fluido == "1") {

                    $input_data =  [
                        "presiones" => json_encode($presionesv),
                        "viscosidades_aceite" => json_encode($viscosidades_aceite),
                        "factores_vol_aceite" => json_encode($factores_vol_aceite),
                        "viscosidades_agua" => json_encode($viscosidades_agua),
                        "fluido" => $IPR->fluido,

                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "bsw" => $IPR->bsw,
                        "tasa_flujo" => $IPR->tasa_flujo,
                        "presion_fondo" => $IPR->presion_fondo,

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
                        "lista_sg" => $lista_sg, /* Gas-oil */
                        "lista_krg" => $lista_krg,
                        "lista_krosg" => $lista_krosg,
                        "lista_sw" => $lista_sw, /* Water-oil */
                        "lista_krw" => $lista_krw,
                        "lista_kro" => $lista_kro,
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="2") {

                    $input_data = [
                        "fluido" => $IPR->fluido,
                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "gas_rate_g" => $IPR->gas_rate_g,
                        "tasa_flujo" => $IPR->gas_rate_g,
                        "presion_fondo" => $IPR->bhp_g,

                        "porosity_text_g" => $IPR->porosity_text_g,
                        "rock_type" => $IPR->rock_type,
                        "temperature_text_g" => $IPR->temperature_text_g,
                        "pvt_pressure_gas" => $lista_pressure, /* Pvt-Gas */
                        "pvt_gasviscosity_gas" => $lista_gas_viscosity,
                        "pvt_gascompressibility_gas" => $lista_gas_compressibility,
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="3") {

                    $input_data = [
                        "well_type" => $IPR->well_Type,
                        "fluido" => $IPR->fluido,
                        "well_radius" => $IPR->radio_pozo,
                        "drainage_radius" => $IPR->radio_drenaje_yac,
                        "gas_rate_c_g" => $IPR->gas_rate_c_g,
                        "tasa_flujo" => $IPR->gas_rate_c_g,
                        "presion_fondo" => $IPR->bhp_c_g,


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
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="4") {

                    $input_data = [
                        "well_type" => $IPR->well_Type,
                        "fluido" => $IPR->fluido,
                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "injection_rate" => $IPR->injection_rate,
                        "tasa_flujo" => $IPR->injection_rate,
                        "presion_fondo" => $IPR->presion_fondo,

                        "water_volumetric_factor" => $IPR->water_volumetric_factor,
                        "water_viscosity" => $IPR->water_viscosity,
                        "sensibilidad" => $sensibilidad,
                    ];

                } else if($IPR->fluido =="5") {

                    $input_data = [
                        "fluido" => $IPR->fluido,
                        "radio_pozo" => $IPR->radio_pozo,
                        "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
                        "presion_yacimiento" => $IPR->presion_yacimiento,
                        "gas_rate_g" => $IPR->gas_rate_g,
                        "tasa_flujo" => $IPR->gas_rate_g,
                        "presion_fondo" => $IPR->bhp_g,
                        "temperature_text_g" => $IPR->temperature_text_g,
                        "pvt_pressure_gas" => $lista_pressure, /* Pvt-Gas */
                        "pvt_gasviscosity_gas" => $lista_gas_viscosity,
                        "pvt_gascompressibility_gas" => $lista_gas_compressibility,
                        "sensibilidad" => $sensibilidad,
                    ];

                }

                $input_data = array_merge($input_data,[
                    "stress_sensitive_reservoir" => $intervalo->stress_sensitive_reservoir,
                    "current_reservoir_pressure" => $intervalo->current_reservoir_pressure,
                    "initial_reservoir_pressure" => $intervalo->initial_reservoir_pressure,
                    "absolute_permeability_it_initial_reservoir_pressure" => $intervalo->absolute_permeability_it_initial_reservoir_pressure,
                    "net_pay" => $intervalo->net_pay,
                    "permeability_module" => $intervalo->permeability_module,
                    "absolute_permeability" => $intervalo->absolute_permeability,
                    "presion_separacion" => $intervalo->reservoir_parting_pressure
                ]);

                $input_data["id_escenario"] = $scenary->id;
                $base = $input_data;
                if ($condicional_sensitive == 1) {
                    $input_data[$var_mod_perm] = ($input_data[$var_mod_perm] == 0.000001 && !is_null($input_data[$var_mod_perm])) ? $input_data[$var_mod_perm] : (float) $input_data[$var_perm];
                }


                switch ($sensibilidad) {
                    case 'factor_dano':

                    $label = "Skin - Row: ".$key." | Value";
                    $input_data["factor_dano"] = (float)$valor;

                    break;
                    case 'modulo_permeabilidad':

                    $label = "Permeability Module - Value";
                    $input_data[$var_mod_perm] = (float)$valor;
                    $input_data['modificado'] = $var_mod_perm;

                    break;
                    case 'netpay':

                    $label = "Net Pay - Value";
                    $input_data[$var_net_pay] = (float)$valor;
                    $input_data['modificado'] = $var_net_pay;

                    break;
                    case 'absolute_permeability':

                    $label = "Abosulte Permeability - Value";
                    $input_data[$var_abs_perm] = (float)$valor;
                    $input_data['modificado'] = $var_abs_perm;

                    break;
                    case 'bhp':

                    $label = "BHP - Value";
                    $input_data[$var_bhp] = (float)$valor;
                    $input_data['modificado'] = $var_bhp;

                    break;
                    case 'bsw':

                    $label = "BSW - Value";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data["bsw"] = (float)$valor;
                    $input_data['modificado'] = "bsw";

                    break;
                    case 'corey':

                    $label = "Corey Exponent Oil - Value";
                    $input_data["factor_dano"] = (float)$skin_resultante_final;
                    $input_data["exponente_corey_petroleo"] = (float)$valor;
                    $input_data['modificado'] = "exponente_corey_petroleo";

                    break;
                    case 'presion_separacion':

                    $label = "Reservoir Parting Pressure - Value";
                    $input_data[$var_rpp] = (float)$valor;
                    $input_data['modificado'] = $var_rpp;

                    break;
                    case 'reservoir_pressure':

                    $label = "Reservoir Pressure - Value";
                    $input_data[$var_py] = (float)$valor;
                    $input_data['modificado'] = $var_py;

                    break;
                    case 'radio_drenaje_yac':

                    $label = "Drainage Radius - Value";
                    $input_data[$var_rdr] = (float)$valor;
                    $input_data['modificado'] = $var_rdr;

                    break;
                    default:

                    $label = "Skin - Value";
                    $input_data["factor_dano"] = (float)$valor;
                    $input_data['modificado'] = "factor_dano";

                    break;
                }


                /* Cálculo módulo IPR */
                // $ipr_resultados_skins = json_decode($this->validateNextRun($IPR,$input_data,1));
                // dd($ipr_resultados_skins);

                $ipr_resultados_skins = json_decode($this->multirun_ipr($input_data,$valor));

                $inputs[] = $input_data ;
                /* Captura de resultados y tratamiento de datos */

                $ipr_resultados = array();
                $categorias = array();
                $eje_y = array();

                $data = array();

                $tasa_flujo_resultados = $ipr_resultados_skins->ipr[0];
                $presion_fondo_resultados = $ipr_resultados_skins->ipr[1];
                $skin_resultados = $ipr_resultados_skins->skin;

                for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) { 
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

                $rates_outflow = $categorias;
                $pressures_outflow = $eje_y;

                $eje_y = json_encode($eje_y);

                $ejes_dobles[] = $data;
                $etiquetas_ejes[] = $label . ": " . $valor;

                $valores[] = $valor;

                $ejes[] = $eje_y;

                if($IPR->fluido =="1") {

                    $tasa_flujo = $IPR->tasa_flujo;
                    $presion_fondo = $IPR->presion_fondo;

                } else if($IPR->fluido =="2") {

                    $tasa_flujo = $IPR->gas_rate_g;
                    $presion_fondo = $IPR->bhp_g;

                } else if($IPR->fluido =="3") {

                    $tasa_flujo = $IPR->gas_rate_c_g;
                    $presion_fondo = $IPR->bhp_c_g;

                } else if($IPR->fluido =="4") {

                    $tasa_flujo = $IPR->injection_rate;
                    $presion_fondo = $IPR->presion_fondo;

                } else if($IPR->fluido =="5") {

                    $tasa_flujo = $IPR->gas_rate_g;
                    $presion_fondo = $IPR->bhp_g;

                }
            }

            $dataTable = $this->mixInputs($inputs,$base);
            // dd($dataTable);
            // $dataTable = $this->executeCombinations($inputs,$base);

            if(isset($request->outflowcurve)) {
                $outflowcurve = $request->outflowcurve;
                $outFlowTable = [];

                $rates_outflow = json_decode($outflowcurve['rates_outflow']);
                $pressures_outflow = json_decode($outflowcurve['pressures_outflow']);
                $rates_inflow = json_decode($outflowcurve['rates_inflow']);
                $pressures_inflow = json_decode($outflowcurve['pressures_inflow']);

                foreach ($rates_outflow as $key => $value) {
                    $outFlowTable[] = [round($value, 3), round($pressures_outflow[$key], 3)];
                }
                $ejes_dobles[] = $outFlowTable;
                $etiquetas_ejes[] = 'Outflow Data';
                
                /* dd($rates_inflow, $pressures_inflow, $rates_outflow, $pressures_outflow); */
                $prueba = $this->operativePoint($rates_inflow, $pressures_inflow, $rates_outflow, $pressures_outflow);
                /*  dd($prueba); */
                /*  $ejes_dobles[] = $this->operativePoint($rates_inflow, $pressures_inflow, $rates_outflow, $pressures_outflow); */
                /*  $etiquetas_ejes[] = "Operative Point Data"; */
            }

            if((float)$IPR->tasa_flujo != 0.000001 && (float)$IPR->presion_fondo != 0.000001) {
                $ejes_dobles[] = [[round($tasa_flujo, 3), round($presion_fondo, 3)]];
                $etiquetas_ejes[] = "Production Data";
            }  
            // dd($dataTable);
            return response()->json(['etiquetas_ejes' => $etiquetas_ejes, 'ejes_dobles' => $ejes_dobles, 'dataTable' => $dataTable]);   
        } else {
            return view('loginfirst');
        }
    }

    /* Esta función se encarga de combinar los diferentes datos, definir los titulos y retornar el la tabla formateada */
    /* y lista para mostrar la información respectiva. */
    public function mixInputs($inputs,$base)
    {
        $inputs = collect($this->clearFormatInputs($inputs,$base));

        $tabla_cnf = [];
        foreach ($inputs as $k =>$item) {
            $item = collect($item);
            $mod = $item["modificado"];
            $tabla_cnf[$mod] = $this->formatTitle($item['sensibilidad']);;
        }
        $tabla_cnf = collect($tabla_cnf)->unique('title')->toArray();

        $inputs = $inputs->groupBy('modificado');
        $inputs = $inputs->toArray();

        $traits = [];
        foreach ($inputs as $v) {
            $traits[] = $v;
        }

        $this->showCombinations('', $traits, 0);
        $p_combinate = $this->arreglos;

        $pos_conf = 0;
        $widths = [];
        $titulos = [];
        foreach ($tabla_cnf as $k => $v) {
            $tabla_cnf[$k]['data'] = $pos_conf;
            $titulos[] = $tabla_cnf[$k];
            $widths[] = 220;
            $pos_conf++;
        }

        $tabla = [];
        foreach ($p_combinate as $k => $v) {
            $columnas = [];
            $campos = explode("@", $v);
            for ($i = 0; $i < count($campos); $i++) { 

                $campo = explode('||', $campos[$i]);
                $nombre = $campo[0];
                
                $tab_config = $tabla_cnf[$nombre];

                $valor = $campo[1];
                $base[$nombre] = $valor;

                $columnas[$tab_config['data']] = $valor;
            }

            $ipr_results = json_decode($this->multirun_ipr($base,'@'));
            $columnas[count($columnas)] = (float) number_format($ipr_results->skin, 2, '.', '');
            $columnas[count($columnas)] = "<center><input type='hidden' name='hidden_data_@@id@@' id='hidden_data_@@id@@' value='".json_encode($ipr_results->ipr[0])."'><input type='hidden' name='hidden_eje_@@id@@' id='hidden_eje_@@id@@' value='".json_encode($ipr_results->ipr[1])."'><input type='hidden' name='cero_hidden_data_@@id@@' id='cero_hidden_data_@@id@@' value='".json_encode($ipr_results->ipr_cero[0])."'><input type='hidden' name='cero_hidden_eje_@@id@@' id='cero_hidden_eje_@@id@@' value='".json_encode($ipr_results->ipr_cero[1])."'><input type='hidden' name='label_@@id@@' id='label_@@id@@' value='aaaaaaaaaa'><input type='hidden' name='value_@@id@@' id='value_@@id@@' value='bbbbbbbbbb'><button title='Show this row in the graphic.' type='button' class='btn btn-xs btn-primary btn_showgraph' data_id='@@id@@' onclick='graphValue(this);'><i class='fa fa-chart-bar'></i></button> </center>";

            $tabla[] = $columnas;
        }

        $cont = 0;
        foreach ($tabla as $k => $v) {
            $data = $v;
            end($data);
            $id = key($data);

            $button = $data[$id];
            $button = str_replace('@@id@@', $cont, $button);

            $tabla[$k][$id] = $button;
            $cont = $cont + 1;
        }

        $titulos[] = [ "title" => "Skin", "data" => count($tabla_cnf), "renderer" => 'html', 'readOnly' =>  true ];
        $widths[] = 70;
        $titulos[] = [ "title" => " - ", "data" => count($tabla_cnf)+1, "renderer" => 'html', 'readOnly' =>  true ];
        $widths[] = 40;
        return [ "datos" => $tabla, "titulos" => $titulos, "widths" => $widths ];
        
    }

    protected $arreglos = [];
    private function showCombinations($string, $traits, $i)
    {
        if ($i >= count($traits)){
            $this->arreglos[] = substr(trim($string), 1);
        } else {
            foreach ($traits[$i] as $trait) {
                $mod = $trait['modificado'];
                $this->showCombinations("$string@$mod||".$trait[$mod], $traits, $i + 1);
            }
        }
    }

    public function clearFormatInputs($inputs,$base)
    {
        $input_n = [];
        $input_b = [];

        foreach ($inputs as $k => $v) {
            $modificado = $v['modificado'];
            
            $data = [];
            $data['modificado'] = $modificado;
            $data['id_escenario'] = $v['id_escenario'];
            $data['sensibilidad'] = $v['sensibilidad'];
            $data[$modificado] = floatval($v[$modificado]);

            $data_b['modificado'] = $modificado;
            $data_b['id_escenario'] = $v['id_escenario'];
            $data_b['sensibilidad'] = $v['sensibilidad'];
            $data_b[$modificado] = floatval($base[$modificado]);

            $input_b[] = collect($data_b);
            $input_n[] = collect($data);
        }

        $input_b = collect($input_b)->unique()->toArray();
        $arrays = array_merge($input_n,$input_b);

        return $arrays;
    }

    /* Esta función se encarga de generar una linea separadora para la tabla. */
    private function getSeparator($keys)
    {
        $cantidad = count($keys) + 2;
        $data = [];
        for ($i=0; $i < $cantidad; $i++) { 
            $data[] = '<center><span style="color:gray;" title="Separator line."> -- </span></center>';
        }
        return $data;
    }

    /* Se encarga de buscar la clave definida en cada input segun el tipo de fluido */
    private function getIndex($name,$fluido)
    {
        $var_net_pay = "net_pay";
        $var_abs_perm = "absolute_permeability";
        $var_bhp = "presion_fondo";
        $var_mod_perm = "permeability_module";
        $var_reservoir_pressure = "current_reservoir_pressure";

        $var_radio_drenaje_yac = "radio_drenaje_yac";

        switch ($name) {
            case 'modulo_permeabilidad':

            return $var_mod_perm;

            break;
            case 'netpay':

            return $var_net_pay;

            break;
            case 'absolute_permeability':

            return $var_abs_perm;

            break;
            case 'reservoir_pressure':

            return $var_reservoir_pressure;

            break;
            case 'bhp':

            return $var_bhp;

            break;
            case 'bsw':

            return "bsw";

            break;
            case 'corey':

            return 'exponente_corey_petroleo';

            break;

            case 'presion_separacion':

            return 'presion_separacion';

            break;

            case 'radio_drenaje_yac':

            return $var_radio_drenaje_yac;

            break;
            default:

            return $var_net_pay;

            break;
        }
    }

    /**
    * Función encargada de validar los datos y eliminar los valores repetidos a mostrar
    * en la tabla.
    *
    * @author  Esneider Mejia Ciro
    * @datetime  06/11/2018 14:27
    * @param Array data  Se encarga de traer la información que sera procesada
    * @param Array keys  Se utiliza para ubicar y contar la cantidad de sensibilidades
    * 
    * @return Array data
    */
    private function validateData($data,$keys)
    {
        $data = $data;
        $posicionesIn = count($keys);
        foreach ($data as $k => $d) {
            $datos = collect($d);
            
            /* Se quita el skin de la data para organizarla */
            /* Se quitan los botones de la data para organizarla */
            /* Se ordena en ascendente*/
            $datos->forget($posicionesIn);
            $datos->forget($posicionesIn + 1);

            /* Se verifican los datos para rellenar los vacios */
            if (!isset($datos[0]) || is_null($datos[0])) {
                $datos->put(0, 0);
            }

            if (!isset($datos[1]) || is_null($datos[1])) {
                $datos->put(1, 0);
            }

            if (($posicionesIn + 1) != 2 && (!isset($datos[2]) || is_null($datos[2]))) {
                $datos->put(2, 0);
            }

            /* Se convierte a texto para que sea mas facil */
            $datos = implode(',',$datos->toArray());

            foreach ($data as $ko => $o) {
                if ($ko == $k) {
                    continue;
                }

                $valor = collect($o);

                /* Se quita el skin de la data para organizarla */
                /* Se quitan los botones de la data para organizarla */
                /* Se ordena en ascendente*/
                $valor->forget($posicionesIn);
                $valor->forget($posicionesIn + 1);

                /* Se verifican los datos para rellenar los vacios */
                if (!isset($valor[0]) || is_null($valor[0])) {
                    $valor->put(0, 0);
                }

                if (!isset($valor[1]) || is_null($valor[1])) {
                    $valor->put(1, 0);
                }

                if (($posicionesIn + 1) != 2 && !isset($valor[2]) || is_null($valor[2])) {
                    $valor->put(2, 0);
                }

                /* Se convierte a texto para que sea mas facil */
                $valor = implode(',',$valor->toArray());

                $o1 = isset($d[0]) ? $d[0] : 0;
                $o2 = isset($d[1]) ? $d[1] : 0;
                $o3 = isset($d[2]) ? $d[2] : 0;

                if(strpos($valor, 'span') || strpos($o1, 'span') || strpos($o2, 'span') || strpos($o3, 'span')){
                    continue;
                }

                if($valor == "$o1,$o2,$o3" || $valor == "$o1,$o3,$o2" || $valor == "$o2,$o1,$o3" || $valor == "$o2,$o3,$o1" || $valor == "$o3,$o1,$o2" || $valor == "$o3,$o2,$o1") {
                    unset($data[$k]);
                }
            }
        }

        return $data;
    }

    /**
    * Función encargada de dar el formato especifico para cada celda 
    *
    * @author  Esneider Mejia Ciro
    * @datetime  06/11/2018 14:27
    * @param string columna  Se encarga de traer el nombre para configurar cada titulo
    * 
    * @return Array data
    */
    private function formatTitle($columna)
    {
        if ($columna == "netpay") {

            return [ "title" => "Net Pay - hnet [ft]", "data" => 0, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        } else if ($columna == "absolute_permeability") {

            return [ "title" => "Absolute Permeability - Kabs [md]", "data" => 1, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        } else if ($columna == "bhp") {

            return [ "title" => "Bottom Hole Pressure - BHP [psi]", "data" => 2, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        } else if ($columna == "modulo_permeabilidad") {

            return [ "title" => "Permeability Module - Perm. Mod [1/psi]", "data" => 3, "type" => 'numeric', "renderer" => 'html', "format" => '0[.]0000', "readOnly" =>  true ];

        } else if ($columna == "bsw") {

            return [ "title" => "Water Cut - BSW [-]", "data" => 4, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        } else if ($columna == "corey") {

            return [ "title" => "Corey Exponent Oil [-]", "data" => 5, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        } else if ($columna == "presion_separacion") {

            return [ "title" => "Reservoir Parting Pressure - RPP [psi]", "data" => 6, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        } else if ($columna == "reservoir_pressure") {

            return [ "title" => "Reservoir Pressure - Pres [psi]", "data" => 7, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        } else if ($columna == "radio_drenaje_yac") {

            return [ "title" => "Drainage Radius - Re [ft]", "data" => 8, "type" => 'numeric', "format" => '0[.]0000', "renderer" => 'html', "readOnly" =>  true ];

        }
    }

    /**
    * Esta función reconstruye el input enviado al módulo de python con base en las sensibilidades 
    * que el usuario escoge, ejecuta de nuevo el módulo y presenta los nuevos resultados junto al caso base.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function operativePointGet(Request $request)
    {

        $rates_inflow = json_decode($request->rates_inflow);
        $pressures_inflow = json_decode($request->pressures_inflow);
        $rates_outflow = json_decode($request->rates_outflow);
        $pressures_outflow = json_decode($request->pressures_outflow);

        $valor = $this->operativePoint($rates_inflow, $pressures_inflow, $rates_outflow, $pressures_outflow);
        return $valor;
    }

    /**
    * 
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function operativePoint($rates_inflow, $pressures_inflow, $rates_outflow, $pressures_outflow)
    {   
        if (\Auth::check()) {
            $outflow_points = count($rates_outflow);
            $inflow_points = count($rates_inflow);

            $operativePointRate = 0;
            $operativePointPressure = 0;

            for ($i = 0; $i < $outflow_points - 1; $i++) { 
                $x1o = $rates_outflow[$i];
                $x2o = $rates_outflow[$i + 1];
                $y1o = $pressures_outflow[$i];
                $y2o = $pressures_outflow[$i + 1];


                for ($j=0; $j < $inflow_points - 1; $j++) { 
                    $x1i = $rates_inflow[$j];
                    $x2i = $rates_inflow[$j+1];
                    $y1i = $pressures_inflow[$j];
                    $y2i = $pressures_inflow[$j+1];

                    if (($x1i == $x2i) && ($y1i == $y2i)) {
                        $operativePointRate = $x1i;
                        $operativePointPressure = $y1i;
                        return [$operativePointRate, $operativePointPressure];
                    }

                    if ( ( ($x1o >= $x1i) && ($x1o <= $x2i) ) || ( ($x2o >= $x1i) && ($x2o <= $x2i) ) || ( ($x1i >= $x1o) && ($x1i <= $x2o) ) || ( ($x2i >= $x1o) && ($x2i <= $x2o) ) ) {

                        /* dd("($y2i - $y1i) / ($x2i - $x1i)"); */
                        $mi = (float) ($y2i - $y1i) / ($x2i - $x1i);
                        $mo = (float) ($y2o - $y1o) / ($x2o - $x1o);

                        if ($mi != $mo) {

                            $x = (float) (-$mo * $x1o + $y1o + $mi * $x1i - $y1i) / ($mi - $mo);

                            if ( ($x >= $x1o) and ($x <= $x2o) and ($x >= $x1i) and ($x <= $x2i) ) {
                                $operativePointRate = (float) $x;
                                $operativePointPressure = (float) $mi * $x - $mi * $x1i + $y1i;
                            }
                        }
                    }

                }
            }

            return [$operativePointRate, $operativePointPressure];
        } else {
            return view('loginfirst');
        }
    }

    /**
    * Display the specified resource.
    *
    * @author  Esneider Mejia Ciro
    * @datetime  16/10/2018 13:41
    * @param  Request  $request
    * @param  id  Cuando llega el id es el id del escenario.
    * @return \Illuminate\Http\Response
    */
    private function saveBlackOil($id_escenario,$request, $tipo_save, $id = null)
    {
        /* Se definen las variables que se utilizaran para las validaciones */
        $presion_yacimiento = $request->input("presion_yacimiento");
        $radio_pozo = $request->input("radio_pozo");
        $escenario = escenario::find($id_escenario);

        /* Se definen las validaciones que se utilizaran para las validaciones */
        if (!$tipo_save) {

            $por_validar = [
                /* Sección Well Data */
                'well_type'=>'numeric|min:0',
                'fluido'=>'numeric|min:0',

                'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$radio_pozo,

                /* Sección Operative Data */
                'tasa_flujo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'presion_fondo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'bsw' => 'required|numeric|min:0|max:1',

                /* Sección Coreys model de Rock Properties */
                'end_point_kr_aceite_gas' => 'numeric|between:0,1|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
                'saturacion_gas_crit' => 'numeric|min:0|max:1|not_in:1',
                'end_point_kr_gas' => 'numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                'saturacion_aceite_irred_gas' => 'numeric|min:0|max:1|not_in:1',
                'exponente_corey_aceite_gas' => 'numeric|min:0|not_in:0',
                'exponente_corey_gas' => 'numeric|min:0|not_in:0', 
                'end_point_kr_petroleo' => 'numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                'saturacion_agua_irred' => 'numeric|between:0,1|not_in:1',
                'end_point_kr_agua' => 'numeric|between:0,1|not_in:0|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', 
                'saturacion_aceite_irred' => 'numeric|between:0,1|not_in:1',
                'exponente_corey_petroleo' => 'numeric|min:0|not_in:0',
                'exponente_corey_agua' => 'numeric|min:0|not_in:0',
                /* Fin - Sección Coreys model de Rock Properties */

                /* Sección Fluid Properties */
                'presion_saturacion' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                /* Fin - Sección Fluid Properties */
            ];

            // $por_validar_plus = [
            //     /* Sección Rock Properties */
            //     'stress_sensitive_reservoir.*.stress_sensitive_reservoir'=>'required|numeric|min:1|max:3',

            //     /* Se muestran solo si stress_sensitive_reservoir == yes */
            //     'presion_inicial' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',
            //     'permeabilidad_abs_ini' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
            //     'modulo_permeabilidad' => 'numeric|min:0',
            //     'permeabilidad' => 'numeric|min:1',

            //     /* Se muestran siempre */
            //     'presion_yacimiento' => 'required_if:stress_sensitive_reservoir,==,"yes"|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
            //     'espesor_reservorio' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',

            //     /*Se muestra solo si el tipo de pozo elegido es Injector */
            //     'presion_separacion' => 'required_if:well_type,==,2|numeric|min:0',

            //     /* No se muestra, pero estan ahi, pueden tener datos o no */
            //     'porosidad' => 'numeric|between:0,0.5|not_in:0',
            //     'tipo_roca' => 'numeric|min:1'
            // ];
            // $por_validar = array_merge($por_validar,$por_validar_plus);

            $validator = Validator::make($request->all(), $por_validar)->setAttributeNames(array(
                'well_type'=>'Well Type',
                'fluido'=>'Fluid Type',
                'radio_pozo' => 'Well Radius',
                'radio_drenaje_yac' => 'Reservoir Drainage Radius',
                'tasa_flujo' => 'Oil Rate',
                'presion_fondo' => 'BHP',
                'bsw' => 'BSW',
                'stress_sensitive_reservoir'=>'Stress Sensitive Reservoir',
                'presion_inicial' => 'Initial Reservoir Pressure',
                'permeabilidad_abs_ini' => 'Absolute Permeability At Initial Reservoir Pressure',
                'permeabilidad' => 'Absolute Permeability',
                'modulo_permeabilidad' => 'Permeability Module',
                'presion_yacimiento' => 'Current Reservoir Pressure',
                'espesor_reservorio' => 'Net Pay',
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
            ));

            if ($validator->fails()) {
                $url = $request->fullUrl();

                if (strpos($url, 'update')) {
                    $action = str_replace('update', 'edit', $url);
                } else {
                    $action = str_replace('/store', '', $url);
                }

                return redirect($action)
                ->withErrors($validator)
                ->withInput();
            }
        }

        if(is_null($id)){
            $IPR = new ipr();
        } else {
            $IPR = ipr::where(["id_escenario" => $id])->first();
            if (!$IPR) { $IPR = new ipr(); }
        }

        /* Se definen para hacer mas facil algunas validaciones */
        $presion_inicial = $request->input("presion_inicial");
        $permeabilidad_abs_ini = $request->input("permeabilidad_abs_ini");
        $modulo_permeabilidad = $request->input("modulo_permeabilidad");
        $porosidad = $request->input("porosidad");
        $tipo_roca = $request->input("tipo_roca");

        /* Well Type */
        $IPR->well_type = $request->input("well_type"); /* campo nuevo */
        $IPR->fluido = $request->input("fluido");
        $IPR->radio_pozo = $request->input("radio_pozo");
        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");

        /* Operative Data */
        $IPR->tasa_flujo = $request->input("tasa_flujo");
        $IPR->presion_fondo = $request->input("presion_fondo");
        $IPR->bsw = $request->input("bsw");


        /* Rock Properties*/
        $stress_sensitive_reservoir = $request->input("stress_sensitive_reservoir"); /* campo nuevo */
        $presion_inicial = empty($presion_inicial) ? 0 : $presion_inicial; /* En caso de null se reemplaza por 0 */
        $permeabilidad_abs_ini = empty($permeabilidad_abs_ini) ? 0 : $permeabilidad_abs_ini; /* En caso de null se reemplaza por 0 */
        $modulo_permeabilidad = empty($modulo_permeabilidad) ? 0 : $modulo_permeabilidad; /* En caso de null se reemplaza por 0 */
        $presion_yacimiento = $request->input("presion_yacimiento");
        $espesor_reservorio = $request->input("espesor_reservorio");
        $permeabilidad = $request->input("permeabilidad");
        $reservoir_parting_pressure = null; /* campo nuevo */

        $this->saveBasicPetrophysicsData($id_escenario, $stress_sensitive_reservoir, $presion_inicial, $permeabilidad_abs_ini, $modulo_permeabilidad, $presion_yacimiento, $espesor_reservorio, $reservoir_parting_pressure, $permeabilidad);

        $IPR->porosidad = empty($porosidad) ? 0 : $porosidad;
        $IPR->tipo_roca = empty($tipo_roca) ? 0 : $tipo_roca;
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
        $IPR->status_wr = $tipo_save;
        /* Fluid Properties */
        $IPR->saturation_pressure = $request->input("presion_saturacion");

        $IPR->id_escenario = !is_null($id) ? $id : $id_escenario;
        $attributtes = array();

        $tmp_attr = $IPR->toArray();
        foreach($tmp_attr as $k => $v) {
            if($v == '') {
                $v = null;
            }
            $attributtes[$k] = $v;
        }
        foreach($attributtes as $k => $v) {
            $IPR->$k = $v;
        }

        $relative_perm_data_sel = null;
        if(!is_null($request->input("check_tabular_rel_perm"))) {
            $relative_perm_data_sel = $request->input("check_tabular_rel_perm");
        } else {
            $relative_perm_data_sel = $request->input("check_corey_rel_perm");
        }

        $IPR->save();

        $scenary = escenario::find($id_escenario);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        /* Pvt - ipr_tabla */
        $IPR_TABLA = new ipr_tabla;
        $tabla = str_replace(",[null,null,null,null]","",$request->input("presiones_table"));
        $presionesv = array();
        $viscosidades_aceite= array();
        $factores_vol_aceite = array();
        $viscosidades_agua= array();
        $tabla = json_decode($tabla);
        $tabla = is_null($tabla) ? [] : $tabla;

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_tabla::where('id_ipr','=',$IPR->id)->delete();
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

        /* Water-oil - ipr_tabla_water */
        $i_tables = 0;
        $tabla_wateroil = json_decode(str_replace(",[null,null,null]","",$request->input("wateroil_hidden")));
        $tabla_wateroil = is_null($tabla_wateroil) ? [] : $tabla_wateroil;

        $lista_sw = array();
        $lista_krw = array();
        $lista_kro = array();

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_tabla_wateroil::where('id_ipr','=',$IPR->id)->delete();

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

        /* Gas-Oil - ipr_tabla_gas */

        $tabla_gasliquid = json_decode(str_replace(",[null,null,null]","",$request->input("gasliquid_hidden")));
        $tabla_gasliquid = is_null($tabla_gasliquid) ? [] : $tabla_gasliquid;

        $lista_sg = array();
        $lista_krg = array();
        $lista_krosg = array();

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_tabla_gasliquid::where('id_ipr','=',$IPR->id)->delete();

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

        /* Input Data */
        $input_data = [
            "id_escenario" => $IPR->id_escenario,
            "presiones" => json_encode($presionesv),
            "viscosidades_aceite" => json_encode($viscosidades_aceite),
            "factores_vol_aceite" => json_encode($factores_vol_aceite),
            "viscosidades_agua" => json_encode($viscosidades_agua),
            "fluido" => $IPR->fluido,
            "radio_pozo" => $IPR->radio_pozo,
            "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
            "presion_yacimiento" => $IPR->presion_yacimiento,
            "relative_perm_data_sel" => $relative_perm_data_sel,
            "stress_sensitive_reservoir" => $IPR->stress_sensitive_reservoir,
            "bsw" => $IPR->bsw,
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
            "lista_sg" => $lista_sg, /* Gas-oil */
            "lista_krg" => $lista_krg,
            "lista_krosg" => $lista_krosg,
            "lista_sw" => $lista_sw, /* Water-oil */
            "lista_krw" => $lista_krw,
            "lista_kro" => $lista_kro,
        ];
        
        return $this->validateNextRun($IPR,$input_data,$tipo_save);
    }

    /**
    * Esta función se encarga de dirigir el proceso dependiendo del fluido y del tipo de guardar
    *
    **/
    private function validateNextRun($IPR,$input_data,$tipo_save)
    {
        $scenary = escenario::find($input_data['id_escenario']);
        $ipr_resultados_skins = [];
        $scenary->completo = 0;
        if (!$tipo_save) {
            $scenary->completo = 1;
            $ipr_resultados_skins = json_decode($this->multirun_ipr($input_data));;
        }
        $scenary->save();


        return $this->runIprAfterSave($IPR,$ipr_resultados_skins);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    private function saveDryGas($id_escenario,$request, $tipo_save, $id = null)
    {
        /* Se definen las variables que se utilizaran para las validaciones */
        $presion_yacimiento = $request->input("presion_yacimiento");
        $radio_pozo = $request->input("radio_pozo");
        /* Se definen las validaciones que se utilizaran para las validaciones */
        if (!$tipo_save) {
            $validator = Validator::make($request->all(), [
                /* Sección Well Data */
                'well_type'=>'required|numeric|min:0',
                'fluido'=>'required|numeric|min:0',
                'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$radio_pozo,

                /* Sección Operative Data */
                'gas_rate_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'bhp_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',

                /* Sección Rock Properties */
                /* 'stress_sensitive_reservoir'=>'required|numeric|min:1|max:3', */

                /* 'init_res_press_text_g' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/', */
                /* 'abs_perm_init_text_g' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'net_pay_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'presion_yacimiento' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'abs_perm_text_g' => 'required_if:stress_sensitive_reservoir,==,no|numeric|min:0', */


                /* Sección Fluid Properties */
                'temperature_text_g'=>'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
            ])->setAttributeNames(array(
                'well_type'=>'Well Type',
                'fluido'=>'Fluid Type',
                'radio_pozo' => 'Well Radius',
                'radio_drenaje_yac' => 'Reservoir Drainage Radius',

                'gas_rate_g' => 'Gas Rate',
                'bhp_g' => 'BHP',

                'stress_sensitive_reservoir'=>'Stress Sensitive Reservoir',
                'init_res_press_text_g' => 'Initial Reservoir Pressure',
                'abs_perm_init_text_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                'net_pay_text_g' => 'Net pay',
                'presion_yacimiento' => 'Current Reservoir Pressure',
                'abs_perm_text_g' => 'Absolute Permeability',
                'temperature_text_g'=>'Temperature',
            ));

            if ($validator->fails()) {
                $url = $request->fullUrl();

                if (strpos($url, 'update')) {
                    $action = str_replace('update', 'edit', $url);
                } else {
                    $action = str_replace('/store', '', $url);
                }

                return redirect($action)
                ->withErrors($validator)
                ->withInput();
            }
        }

        if(is_null($id)){
            $IPR = new ipr();
        } else {
            $IPR = ipr::where(["id_escenario" => $id])->first();
            if (!$IPR) { $IPR = new ipr(); }
        }

        /* Se definen algunas variables para realizar calculos mas facil */
        $init_res_press_text_g = $request->input('init_res_press_text_g');
        $abs_perm_init_text_g = $request->input('abs_perm_init_text_g');
        $permeability_module_text = $request->input('permeability_module_text');

        /* Sección Well Data */
        $IPR->well_type = $request->input("well_type");
        $IPR->fluido = $request->input("fluido");
        $IPR->radio_pozo = $request->input("radio_pozo");
        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
        
        /* Sección Operative Data */
        $IPR->gas_rate_g= $request->input("gas_rate_g");
        $IPR->bhp_g= $request->input("bhp_g");

        /* Sección Rock Properties */
        $stress_sensitive_reservoir = $request->input("stress_sensitive_reservoir");
        $presion_inicial = empty($init_res_press_text_g) ? 0 : $init_res_press_text_g;
        $permeabilidad_abs_ini = empty($abs_perm_init_text_g) ? 0 : $abs_perm_init_text_g;
        $permeability_module_text = empty($permeability_module_text) ? 0 : $permeability_module_text;
        $presion_yacimiento = $request->input("presion_yacimiento");
        $espesor_reservorio = $request->input("net_pay_text_g");
        $permeabilidad = $request->input("abs_perm_text_g");
        $reservoir_parting_pressure = null; /* campo nuevo */

        $this->saveBasicPetrophysicsData($id_escenario, $stress_sensitive_reservoir, $presion_inicial, $permeabilidad_abs_ini, $permeability_module_text, $presion_yacimiento, $espesor_reservorio, $reservoir_parting_pressure, $permeabilidad);

        /* Sección Fluid Properties */
        $IPR->temperature_text_g = $request->input("temperature_text_g");

        $IPR->status_wr = $tipo_save;
        $IPR->id_escenario = !is_null($id) ? $id : $id_escenario;

        $attributtes = array();

        $tmp_attr = $IPR->toArray();
        foreach($tmp_attr as $k => $v) {
            if($v == '') {
                $v = null;
            }
            $attributtes[$k] = $v;
        }

        foreach($attributtes as $k => $v) {
            $IPR->$k = $v;
        }

        $IPR->save();

        $scenary = escenario::find($id_escenario);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        /* PVT ipr Gas */
        $pvt_gas_ipr = json_decode(str_replace(",[null,null,null]","",$request->input("pvt_gas_ipr")));
        $pvt_pressure_gas = array();
        $pvt_gasViscosity_gas = array();
        $pvt_gasCompressibility_gas = array();

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_pvt_gas::where('id_ipr','=',$IPR->id)->delete();

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

        $input_data = [
            "id_escenario" => $IPR->id_escenario,
            "fluido" => $IPR->fluido,
            "radio_pozo" => $IPR->radio_pozo,
            "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
            "presion_yacimiento" => $IPR->presion_yacimiento,
            "tasa_flujo" => $IPR->gas_rate_g,
            "presion_fondo" => $IPR->bhp_g,
            "temperature_text_g" => $IPR->temperature_text_g,
            "pvt_pressure_gas" => $pvt_pressure_gas, /* Pvt-Gas */
            "pvt_gasviscosity_gas" => $pvt_gasViscosity_gas,
            "pvt_gascompressibility_gas" => $pvt_gasCompressibility_gas,
        ];

        return $this->validateNextRun($IPR,$input_data,$tipo_save);
    }

    /**
    * Esta función se encarga de guardar la información respectiva de condensate gas.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    private function saveCondensateGas($id_escenario,$request, $tipo_save, $id = null)
    {
        /* Se definen las variables que se utilizaran para las validaciones */
        $radio_pozo = $request->input("radio_pozo");

        /* Se definen las validaciones que se utilizaran para las validaciones */
        if (!$tipo_save) {
            $validator = Validator::make($request->all(), [
                /* Section Well Data */
                'well_type'=>'numeric|min:0',
                'fluido' => 'numeric|min:0',
                'radio_pozo' => 'required|numeric|min:0',
                'radio_drenaje_yac' => 'required|numeric|min:'.$radio_pozo,

                /* Section Operative Data */
                'gas_rate_c_g' => 'required|numeric',
                'bhp_c_g' => 'required|numeric',

                /* Section Fluid Properties */
                'presion_saturacion_c_g' => 'required|numeric',
                'gor_c_g' => 'required|numeric',
            ])->setAttributeNames(array(
                'well_type' => 'Well Type',
                'fluido' => 'Fluid Type',
                'radio_pozo' => 'Well Radius',
                'radio_drenaje_yac' => 'Reservoir Drainage Radius',

                'gas_rate_c_g' => 'Gas Rate',
                'bhp_c_g' => 'BHP',

                'stress_sensitive_reservoir'=>'Stress Sensitive Reservoir',
                'presion_yacimiento' => 'Current Reservoir Pressure',
                'presion_inicial_c_g' => 'Initial Reservoir Pressure',
                'permeabilidad_abs_ini_c_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                'espesor_reservorio_c_g' => 'Net Pay',
                'modulo_permeabilidad_c_g' => 'Permeability Module',
                'permeabilidad_c_g' => 'Absolute Permeability',

                'gas_rate_c_g' => 'Gas Rate',
                'bhp_c_g' => 'BHP',

                'stress_sensitive_reservoir'=>'Stress Sensitive Reservoir',
                'presion_yacimiento' => 'Current Reservoir Pressure',
                'presion_inicial_c_g' => 'Initial Reservoir Pressure',
                'permeabilidad_abs_ini_c_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                'espesor_reservorio_c_g' => 'Net Pay',
                'modulo_permeabilidad_c_g' => 'Permeability Module',
                'permeabilidad_c_g' => 'Absolute Permeability',
                'presion_saturacion_c_g' => 'Saturation Pressure',
                'gor_c_g' => 'Gor',
            ));

            if ($validator->fails()) {
                $url = $request->fullUrl();

                if (strpos($url, 'update')) {
                    $action = str_replace('update', 'edit', $url);
                } else {
                    $action = str_replace('/store', '', $url);
                }

                return redirect($action)
                ->withErrors($validator)
                ->withInput();
            }
        }

        if(is_null($id)){
            $IPR = new ipr();
        } else {
            $IPR = ipr::where(["id_escenario" => $id])->first();
            if (!$IPR) { $IPR = new ipr(); }
        }

        /* Se definen estas variables para hacer mas sencillo su uso en validaciones */
        $initial_pressure_c_g = $request->input("presion_inicial_c_g");
        $ini_abs_permeability_c_g = $request->input("permeabilidad_abs_ini_c_g");
        $permeability_module_c_g = $request->input("modulo_permeabilidad_c_g");

        /* Section Well Data */
        $IPR->well_type = $request->input("well_type");
        $IPR->fluido = $request->input("fluido");
        $IPR->radio_pozo = $request->input("radio_pozo");
        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");

        /* Section Operative Data */
        $IPR->gas_rate_c_g = $request->input("gas_rate_c_g");
        $IPR->bhp_c_g = $request->input("bhp_c_g");

        /* Section Rock Properties */
        $stress_sensitive_reservoir = $request->input("stress_sensitive_reservoir");
        $presion_inicial = empty($initial_pressure_c_g) ? 0 : $initial_pressure_c_g;
        $permeabilidad_abs_ini = empty($ini_abs_permeability_c_g) ? 0 : $ini_abs_permeability_c_g;
        $espesor_reservorio = $request->input("espesor_reservorio_c_g");
        $presion_yacimiento = $request->input("presion_yacimiento");
        $permeability_module_text = $request->input("permeabilidad_c_g");

        $reservoir_parting_pressure = null;
        $permeabilidad = empty($permeability_module_c_g) ? 0 : $permeability_module_c_g;

        $this->saveBasicPetrophysicsData($id_escenario, $stress_sensitive_reservoir, $presion_inicial, $permeabilidad_abs_ini, $permeability_module_text, $presion_yacimiento, $espesor_reservorio, $reservoir_parting_pressure, $permeabilidad);
        
        /* Section Fluid Properties */
        $IPR->saturation_pressure_c_g = $request->input("presion_saturacion_c_g");
        $IPR->gor_c_g = $request->input("gor_c_g");
        
        $IPR->status_wr = $tipo_save;

        $IPR->id_escenario = $id_escenario;

        $attributtes = array();

        $tmp_attr = $IPR->toArray();
        foreach($tmp_attr as $k => $v) {
            if($v == '') {
                $v = null;
            }
            $attributtes[$k] = $v;
        }

        foreach($attributtes as $k => $v) {
            $IPR->$k = $v;
        }

        $IPR->save();

        $scenary = escenario::find($id_escenario);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        /* Kr gas oil - Condensate gas */
        $kr_c_g_table_data = str_replace(",[null,null,null]","",$request->input("gas_oil_kr_cg"));
        $sg_data = array();
        $krg_data = array();
        $krog_data = array();
        $kr_c_g_table_data = json_decode($kr_c_g_table_data);

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_gas_oil_kr_c_g::where('ipr_id','=',$IPR->id)->delete();

        foreach ($kr_c_g_table_data as $value) {
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

        /* PVT Condensate Gas */
        $pvt_c_g_table_data = str_replace(",[null,null,null,null,null,null,null]","",$request->input("pvt_cg"));

        $pressure_data = array();
        $bo_data = array();
        $vo_data = array();
        $rs_data = array();
        $bg_data = array();
        $vg_data = array();
        $og_ratio_data = array();
        $pvt_c_g_table_data = json_decode($pvt_c_g_table_data);

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_pvt_c_g::where('ipr_id','=',$IPR->id)->delete();

        foreach ($pvt_c_g_table_data as $value) {
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


        /* Drop out Condensate Gas */
        $dropout_c_g_table_data = str_replace(",[null,null]","",$request->input("dropout_cg"));

        $dropout_pressure_data = array();
        $dropout_liquid_percentage = array();
        $dropout_c_g_table_data = json_decode($dropout_c_g_table_data);

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_dropout_c_g::where('ipr_id','=',$IPR->id)->delete();

        foreach ($dropout_c_g_table_data as $value) {
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

        $input_data = [
            "id_escenario" => $IPR->id_escenario,
            "well_type" => $IPR->well_type,
            "fluido" => $IPR->fluido,
            "radio_pozo" => $IPR->radio_pozo,
            "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
            "reservoir_pressure" => $IPR->presion_yacimiento,
            "tasa_flujo" => $IPR->gas_rate_c_g,
            "presion_fondo" => $IPR->bhp_c_g,
            "stress_sensitive_reservoir" => $IPR->stress_sensitive_reservoir,
            "presion_inicial" => $IPR->initial_pressure_c_g,
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
        ];

        return $this->validateNextRun($IPR,$input_data,$tipo_save);
    }

    /**
    * Display the specified resource.
    *
    * @author  Esneider Mejia Ciro
    * @datetime  16/10/2018 13:41
    * @param  Request  $request
    * @param  id  Cuando llega el id es el id del escenario.
    * @return \Illuminate\Http\Response
    */
    private function saveInjectorWater($id_escenario,$request, $tipo_save, $id = null)
    {
        /* Se definen las variables que se utilizaran para las validaciones */
        $radio_pozo = $request->input("radio_pozo");

        /* Se definen las validaciones que se utilizaran para las validaciones */
        if (!$tipo_save) {
            $validator = Validator::make($request->all(), [
                /* Sección Well Data */
                'well_type' => 'required|numeric|min:0',
                'fluido' => 'required|numeric|min:0',
                'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$radio_pozo,

                /* Sección Operative Data */
                'injection_rate' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'bhfp' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',

                /* Sección Rock Properties */
                /* 'stress_sensitive_reservoir' => 'required|numeric|min:1|max:3', */

                /* Se muestran solo si stress_sensitive_reservoir == yes */
                /* 'presion_inicial' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$presion_yacimiento, */
                /* 'permeabilidad_abs_ini' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'modulo_permeabilidad' => 'numeric|min:0', */

                /* Se muestran solo si stress_sensitive_reservoir == yes */
                /* 'permeabilidad' => 'required_if:stress_sensitive_reservoir,==,no|numeric|min:1', */

                /* Se muestran siempre */
                /* 'presion_yacimiento' => 'required_if:stress_sensitive_reservoir,==,"yes"|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'espesor_reservorio' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */

                /*Se muestra solo si el tipo de pozo elegido es Injector */
                /* 'presion_separacion' => 'required_if:well_type,==,2|numeric|min:0', */

                /* Sección Fluid Properties */
                'water_volumetric_factor' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'water_viscosity' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                /* Fin - Sección Fluid Properties */
            ])->setAttributeNames(array(
                'well_type'=>'Well Type',
                'fluido'=>'Fluid Type',
                'radio_pozo' => 'Well Radius',
                'radio_drenaje_yac' => 'Reservoir Drainage Radius',

                'injection_rate' => 'Injection Rate',
                'bhfp' => 'BHFP',

                'stress_sensitive_reservoir'=>'Stress Sensitive Reservoir',
                'presion_inicial' => 'Initial Reservoir Pressure',
                'permeabilidad_abs_ini' => 'Absolute Permeability At Initial Reservoir Pressure',
                'permeabilidad' => 'Absolute Permeability',
                'modulo_permeabilidad' => 'Permeability Module',
                'presion_yacimiento' => 'Current Reservoir Pressure',
                'espesor_reservorio' => 'Net Pay',
                'presion_separacion' => 'Reservoir Parting Pressure',

                'water_volumetric_factor' => 'Water Volumetric Factor',
                'water_viscosity' => 'Water Viscosity',
            ));

            if ($validator->fails()) {
                $url = $request->fullUrl();

                if (strpos($url, 'update')) {
                    $action = str_replace('update', 'edit', $url);
                } else {
                    $action = str_replace('/store', '', $url);
                }

                return redirect($action)
                ->withErrors($validator)
                ->withInput();
            }
        }

        if(is_null($id)){
            $IPR = new ipr();
        } else {
            $IPR = ipr::where(["id_escenario" => $id])->first();
            if (!$IPR) { $IPR = new ipr(); }
        }

        /* Se definen para hacer mas facil algunas validaciones */
        $presion_inicial = $request->input("presion_inicial");
        $permeabilidad_abs_ini = $request->input("permeabilidad_abs_ini");
        $modulo_permeabilidad = $request->input("modulo_permeabilidad");
        $porosidad = $request->input("porosidad");
        $tipo_roca = $request->input("tipo_roca");

        /* Well Type */
        $IPR->well_type = $request->input("well_type"); /* campo nuevo */
        $IPR->fluido = $request->input("fluido");
        $IPR->radio_pozo = $request->input("radio_pozo");
        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");

        /* Operative Data */
        $IPR->injection_rate = $request->input("injection_rate");
        $IPR->presion_fondo = $request->input("bhfp");

        /* Rock Properties*/
        $stress_sensitive_reservoir = $request->input("stress_sensitive_reservoir"); /* campo nuevo */
        $presion_inicial = is_null($presion_inicial) ? 0 : $presion_inicial; /* En caso de null se reemplaza por 0 */
        $permeabilidad_abs_ini = is_null($permeabilidad_abs_ini) ? 0 : $permeabilidad_abs_ini; /* En caso de null se reemplaza por 0 */
        $modulo_permeabilidad = is_null($modulo_permeabilidad) ? 0 : $modulo_permeabilidad; /* En caso de null se reemplaza por 0 */
        $presion_yacimiento = $request->input("presion_yacimiento");
        $espesor_reservorio = $request->input("espesor_reservorio");
        $reservoir_parting_pressure = $request->input("presion_separacion"); /* campo nuevo */
        $permeabilidad = $request->input("permeabilidad");

        $this->saveBasicPetrophysicsData($id_escenario, $stress_sensitive_reservoir, $presion_inicial, $permeabilidad_abs_ini, $modulo_permeabilidad, $presion_yacimiento, $espesor_reservorio, $reservoir_parting_pressure, $permeabilidad);

        /* Fluid Properties */
        $IPR->water_volumetric_factor = $request->input("water_volumetric_factor");
        $IPR->water_viscosity = $request->input("water_viscosity");

        $IPR->status_wr = $tipo_save;
        $IPR->id_escenario = !is_null($id) ? $id : $id_escenario;

        $attributtes = array();

        $tmp_attr = $IPR->toArray();
        foreach($tmp_attr as $k => $v) {
            if($v == '') {
                $v = null;
            }
            $attributtes[$k] = $v;
        }
        foreach($attributtes as $k => $v) {
            $IPR->$k = $v;
        }

        $IPR->save();

        $scenary = escenario::find($id_escenario);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        /* Input Data */
        $input_data = [
            "id_escenario" => $IPR->id_escenario,
            "well_type" => $IPR->well_type,
            "fluido" => $IPR->fluido,
            "radio_pozo" => $IPR->radio_pozo,
            "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
            "tasa_flujo" => $IPR->injection_rate,
            "presion_inicial" => $IPR->presion_inicial,
            "presion_fondo" => $IPR->presion_fondo,
            "water_volumetric_factor" => $IPR->water_volumetric_factor,
            "water_viscosity" => $IPR->water_viscosity,
        ];

        return $this->validateNextRun($IPR,$input_data,$tipo_save);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    private function saveInjectorGas($id_escenario,$request, $tipo_save, $id = null)
    {

        /* Se definen las variables que se utilizaran para las validaciones */
        $radio_pozo = $request->input("radio_pozo");

        /* Se definen las validaciones que se utilizaran para las validaciones */
        if (!$tipo_save) {
            $validator = Validator::make($request->all(), [
                /* Sección Well Data */
                'well_type'=>'required|numeric|min:0',
                'fluido'=>'required|numeric|min:0',
                'radio_pozo' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'radio_drenaje_yac' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/',

                /* Sección Operative Data */
                'gas_rate_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
                'bhp_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',

                /* Sección Rock Properties */
                /* 'stress_sensitive_reservoir'=>'required|numeric|min:1|max:3', */

                /* 'init_res_press_text_g' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:'.$presion_yacimiento, */
                /* 'abs_perm_init_text_g' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'net_pay_text_g' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'presion_yacimiento' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0', */
                /* 'permeability_module_text' => 'required_if:stress_sensitive_reservoir,==,yes|numeric|min:0', */
                /* 'abs_perm_text_g' => 'required_if:stress_sensitive_reservoir,==,no|numeric|min:0', */
                /* 'presion_separacion' => 'required|numeric|min:0', */

                /* Sección Fluid Properties */
                'temperature_text_g'=>'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,11})?\s*$/|min:0',
            ])->setAttributeNames(array(
                'well_type'=>'Well Type',
                'fluido'=>'Fluid Type',
                'radio_pozo' => 'Well Radius',
                'radio_drenaje_yac' => 'Reservoir Drainage Radius',

                'gas_rate_g' => 'Gas Rate',
                'bhp_g' => 'BHP',

                'stress_sensitive_reservoir'=>'Stress Sensitive Reservoir',
                'init_res_press_text_g' => 'Initial Reservoir Pressure',
                'abs_perm_init_text_g' => 'Absolute Permeability At Initial Reservoir Pressure',
                'net_pay_text_g' => 'Net pay',
                'presion_yacimiento' => 'Current Reservoir Pressure',
                'abs_perm_text_g' => 'Absolute Permeability',
                'permeability_module_text' => 'Permeability',
                'presion_separacion' => 'Reservoir Parting Pressure',

                'temperature_text_g'=>'Temperature',
            ));

            if ($validator->fails()) {
                $url = $request->fullUrl();

                if (strpos($url, 'update')) {
                    $action = str_replace('update', 'edit', $url);
                } else {
                    $action = str_replace('/store', '', $url);
                }

                return redirect($action)
                ->withErrors($validator)
                ->withInput();
            }
        }

        if(is_null($id)){
            $IPR = new ipr();
        } else {
            $IPR = ipr::where(["id_escenario" => $id])->first();
            if (!$IPR) { $IPR = new ipr(); }
        }

        /* Se definen algunas variables para realizar calculos mas facil */

        /* Sección Well Data */
        $IPR->well_type = $request->input("well_type");
        $IPR->fluido = $request->input("fluido");
        $IPR->radio_pozo = $request->input("radio_pozo");
        $IPR->radio_drenaje_yac = $request->input("radio_drenaje_yac");
        
        /* Sección Operative Data */
        $IPR->gas_rate_g= $request->input("gas_rate_g");
        $IPR->bhp_g= $request->input("bhp_g");

        /* Sección Rock Properties */
        $stress_sensitive_reservoir = $request->input('stress_sensitive_reservoir');
        $init_res_press_text_g = $request->input('init_res_press_text_g');
        $abs_perm_init_text_g = $request->input('abs_perm_init_text_g');
        $permeability_module_text = $request->input('permeability_module_text');
        $presion_yacimiento = $request->input('presion_yacimiento');
        $net_pay_text_g = $request->input('net_pay_text_g');
        $presion_separacion = $request->input('presion_separacion');
        $abs_perm_text_g = $request->input('abs_perm_text_g');

        $this->saveBasicPetrophysicsData($id_escenario,$stress_sensitive_reservoir,$init_res_press_text_g,$abs_perm_init_text_g,$permeability_module_text,$presion_yacimiento,$net_pay_text_g,$presion_separacion,$abs_perm_text_g);

        /* Sección Fluid Properties */
        $IPR->temperature_text_g = $request->input("temperature_text_g");

        $IPR->status_wr = $tipo_save;
        $IPR->id_escenario = !is_null($id) ? $id : $id_escenario;
        
        $attributtes = array();

        $tmp_attr = $IPR->toArray();
        foreach($tmp_attr as $k => $v) {
            if($v == '') {
                $v = null;
            }
            $attributtes[$k] = $v;
        }

        foreach($attributtes as $k => $v) {
            $IPR->$k = $v;
        }

        $IPR->save();

        $scenary = escenario::find($id_escenario);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        /* PVT ipr Gas */
        $pvt_gas_ipr = json_decode(str_replace(",[null,null,null]","",$request->input("pvt_gas_ipr")));
        $pvt_pressure_gas = array();
        $pvt_gasViscosity_gas = array();
        $pvt_gasCompressibility_gas = array();

        /* Se borran las tablas que estaban guardadas para agregar las nuevas */
        ipr_pvt_gas::where('id_ipr','=',$IPR->id)->delete();
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

        $input_data = [
            "id_escenario" => $IPR->id_escenario,
            "fluido" => $IPR->fluido,
            "radio_pozo" => $IPR->radio_pozo,
            "radio_drenaje_yac" => $IPR->radio_drenaje_yac,
            "presion_yacimiento" => $IPR->presion_yacimiento,
            "tasa_flujo" => $IPR->gas_rate_g,
            "presion_fondo" => $IPR->bhp_g,
            "temperature_text_g" => $IPR->temperature_text_g,
            "pvt_pressure_gas" => $pvt_pressure_gas, /* Pvt-Gas */
            "pvt_gasviscosity_gas" => $pvt_gasViscosity_gas,
            "pvt_gascompressibility_gas" => $pvt_gasCompressibility_gas,
        ];

        return $this->validateNextRun($IPR,$input_data,$tipo_save);
    }

    /**
    * Esta función se ejecutar los respectivos calculos necesarios.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function runIprAfterSave($IPR,$ipr_resultados_skins)
    {
        /* Captura de resultados y tratamiento de datos */ 
        $ipr_resultados = array();
        $categorias = array();
        $eje_y = array();

        $data = array();
        $skin_tmp = 0;
        $tasa_flujo_resultados = (isset($ipr_resultados_skins->ipr[0]) ? $ipr_resultados_skins->ipr[0] : []);
        $presion_fondo_resultados = (isset($ipr_resultados_skins->ipr[1]) ? $ipr_resultados_skins->ipr[1] : []);
        $skin_resultados = (isset($ipr_resultados_skins->skin) ? $ipr_resultados_skins->skin : 0);

        /* Se eliminan resultados anteriores de este mismo IPR*/
        ipr_resultado::where('id_ipr','=',$IPR->id)->delete();
        
        for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) { 
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
        /* Captura de resultados y tratamiento de datos */

        $categorias_i = array();
        $eje_yi = array();

        $data_i = array();

        $tasa_flujo_resultados = (isset($ipr_resultados_skins->ipr_cero[0]) ? $ipr_resultados_skins->ipr_cero[0] : []);
        $presion_fondo_resultados = (isset($ipr_resultados_skins->ipr_cero[1]) ? $ipr_resultados_skins->ipr_cero[1] : []);
        $skin_resultados = (isset($ipr_resultados_skins->skin) ? $ipr_resultados_skins->skin : 0);
        
        /* Se eliminan resultados anteriores de este mismo IPR*/
        ipr_resultado_skin_ideal::where('id_ipr','=',$IPR->id)->delete();

        for ($i=0; $i < count($tasa_flujo_resultados) ; $i++) { 
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

        $escenario = escenario::find($IPR->id_escenario);

        if (strpos($escenario->formacion_id, ',')) {
            $ipr_multiresultados = isset($ipr_resultados_skins->cu_intervalos) ? $ipr_resultados_skins->cu_intervalos : [];

            /* Se borran los datos anteriores, se ingresan los nuevos */
            ipr_intervalo_results::where('id_scenary',$escenario->id)->delete();
            foreach ($ipr_multiresultados as $k => $v) {
                $ipr_intervalo_results = new ipr_intervalo_results();
                $ipr_intervalo_results->id_scenary = $escenario->id;
                $ipr_intervalo_results->id_formations_scenary = $v->id_formations_scenary;
                $ipr_intervalo_results->skin = $v->skin;
                $ipr_intervalo_results->ipr_skin = json_encode($v->ipr_);
                $ipr_intervalo_results->ipr_skin_cero = json_encode($v->ipr_skin_cero_);
                $ipr_intervalo_results->save();
            }
        }

        $skin = $skin_tmp;

        if($IPR->fluido == "1") {

            $tasa_flujo = $IPR->tasa_flujo;
            $presion_fondo = $IPR->presion_fondo;
            $tipo_roca = $IPR->tipo_roca;

        } else if($IPR->fluido == "2") {

            $tasa_flujo = $IPR->gas_rate_g;
            $presion_fondo = $IPR->bhp_g;
            $tipo_roca = $IPR->rock_type;

        } else if($IPR->fluido == "3") {

            $tasa_flujo = $IPR->gas_rate_c_g;
            $presion_fondo = $IPR->bhp_c_g;
            $tipo_roca = $IPR->rock_type_c_g;

        } else if($IPR->fluido =="4") {

            $tasa_flujo = $IPR->injection_rate;
            $presion_fondo = $IPR->presion_fondo;
            $tipo_roca = 0;

        } else if($IPR->fluido =="5") {

            $tasa_flujo = $IPR->gas_rate_g;
            $presion_fondo = $IPR->bhp_g;
            $tipo_roca = 0;

        }

        $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
        $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$escenario->id)->first();

        return compact(
            'user',
            'campo',
            'IPR',
            'ipr_resultados',
            'categorias',
            'eje_y',
            'skin',
            'data',
            'tasa_flujo',
            'presion_fondo',
            'tipo_roca',
            'data_i',
            'escenario'
        );
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        abort('404');
    }

    /**
     * Prepara y construye el set de datos que se envía a la vista de resultados.
     */
    public function result($id)
    {
        if (\Auth::check()) 
        {
            $scenary = escenario::find($id);
            $IPR = DB::table('ipr')->where('id_escenario', $scenary->id)->first();
            $basin = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
            $field = DB::table('campos')->where('id', $scenary->campo_id)->first();
            $well = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();

            $ipr_intervalo_results = ipr_intervalo_results::where('id_scenary',$scenary->id)->get();

            if (strpos($scenary->formacion_id, ',')) {
                $forms_id = explode(',', $scenary->formacion_id);
                $formacion = [];
                $pvt = [];
                foreach ($forms_id as $form_id) {
                    $pvt[] = DB::table('pvt_formacion_x_pozos')->where('formacionxpozos_id',$form_id)->get();
                    $formacion[] = DB::table('formacionxpozos')->where('id','=',$form_id)->first();
                }
            } else {
                $formacion = DB::table('formacionxpozos')->where([
                    'formacion_id' => $scenary->formacion_id
                ])->first();
            }

            $intervalos = [];
            if (strpos($scenary->formacion_id, ',')) {

                /* Se borran los datos anteriores, se ingresan los nuevos */
                $ipr_intervalo_results = ipr_intervalo_results::where('id_scenary',$scenary->id)->get();
                foreach ($ipr_intervalo_results as $k => $v) {
                    $formations_scenary = formations_scenary::find($v->id_formations_scenary);
                    $nombre = DB::table('formacionxpozos')->where('id',$formations_scenary->id_formation)->first()->nombre;
                    $int = ['skin' => $v->skin, 'nombre' => $nombre, 'ipr' => json_decode($v->ipr_skin), 'ipr_cero' => json_decode($v->ipr_skin_cero)];
                    $intervalos[] = $int;
                }
            }

            $i_tables = 0;
            $ipr_resultados = array();
            $categorias = array();
            $eje_y = array();
            $data = array(); 
            $skin = 0; 
            $skin_tmp = 0; 

            $ipr_result_calculated_skin = DB::table('ipr_resultados')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();        
            foreach ($ipr_result_calculated_skin as $value) {   
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
            $escenario = DB::table('escenarios')->where('id', $id)->first();
            $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
            $categorias_skin_ideal = array();
            $eje_y_skin_ideal = array();
            $data_skin_ideal = array();

            $ipr_result_ideal_skin = DB::table('ipr_resultados_skin_ideal')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();
            foreach ($ipr_result_ideal_skin as $value) {   
                $data_skin_ideal[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
                $categorias_skin_ideal[] = (float)$value->tasa_flujo;
                $eje_y_skin_ideal[] = (float)$value->presion_fondo;
                $ipr_resultados[] = [(float)$value->tasa_flujo, (float)$value->presion_fondo];
            }

            $categorias_skin_ideal = json_encode($categorias_skin_ideal);
            $eje_y_skin_ideal = json_encode($eje_y_skin_ideal);
            $data_skin_ideal = json_encode($data_skin_ideal);


            if($IPR->fluido =="1") {

                $tasa_flujo = $IPR->tasa_flujo;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = isset($IPR->tipo_roca) ? $IPR->tipo_roca : 0;

            } else if($IPR->fluido =="2") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = isset($IPR->rock_type) ? $IPR->rock_type : 0;

            } else if($IPR->fluido =="3") {

                $tasa_flujo = $IPR->gas_rate_c_g;
                $presion_fondo = $IPR->bhp_c_g;
                $tipo_roca = isset($IPR->rock_type_c_g) ? $IPR->rock_type_c_g : 0;

            } else if($IPR->fluido =="4") {

                $tasa_flujo = $IPR->injection_rate;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = 0;

            } else if($IPR->fluido =="5") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = 0;
                
            }

            $data_i = $data_skin_ideal;
            $intervalo = DB::table('formacionxpozos')->where('id',$scenary->formacion_id)->first();

            if (!strpos($scenary->formacion_id, ',')) {
                return view('template.iprs.results', compact('user','campo', 'IPR', 'ipr_resultados', 'well', 'basin', 'field', 'formacion', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca', 'desagregacion', 'data_i', 'i_tables','scenary','intervalo','categorias_skin_ideal','eje_y_skin_ideal'));
            }

            return view('template.iprs.multi_results', compact('user','campo', 'IPR', 'ipr_resultados', 'well', 'basin', 'field', 'formacion', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca', 'desagregacion', 'data_i', 'i_tables','scenary','intervalo','categorias_skin_ideal','eje_y_skin_ideal','intervalos'));

        }
        else
        {
            return view('loginfirst');
        }
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
    public function duplicate($id,$duplicateFrom)
    {
        $_SESSION['scenary_id'] = $duplicateFrom;
        return $this->back($duplicateFrom,$id);
    }

    /**
     * Prepara y construye el set de datos que se envía a la vista de resultados.
     */
    public function sensibilitiesAdvanced($id)
    {
        if (\Auth::check()) 
        {
            $scenary = escenario::find($id);
            $IPR = ipr::where('id_escenario', $scenary->id)->first();
            $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
            $campo = DB::table('campos')->where('id', $scenary->campo_id)->first();
            $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->first();

            $IPR->intervalo = formations_scenary::where('id_scenary',$scenary->id)->first();

            $campo = DB::table('campos')->where('id', $scenary->campo_id)->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();

            if($IPR->fluido =="1") {

                $tasa_flujo = $IPR->tasa_flujo;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = isset($IPR->tipo_roca) ? $IPR->tipo_roca : 0;

            } else if($IPR->fluido =="2") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = isset($IPR->rock_type) ? $IPR->rock_type : 0;

            } else if($IPR->fluido =="3") {

                $tasa_flujo = $IPR->gas_rate_c_g;
                $presion_fondo = $IPR->bhp_c_g;
                $tipo_roca = isset($IPR->rock_type_c_g) ? $IPR->rock_type_c_g : 0;

            } else if($IPR->fluido =="4") {

                $tasa_flujo = $IPR->injection_rate;
                $presion_fondo = $IPR->presion_fondo;
                $tipo_roca = 0;

            } else if($IPR->fluido =="5") {

                $tasa_flujo = $IPR->gas_rate_g;
                $presion_fondo = $IPR->bhp_g;
                $tipo_roca = 0;
                
            }

            $advisor = false;

            return view('template.iprs.sensibilities', compact('advisor','user','campo', 'IPR', 'pozo', 'formacion', 'tasa_flujo', 'presion_fondo','tipo_roca'));
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
    public function edit($id, $duplicateFrom = null)
    {
        if (\Auth::check()) 
        {
            $_SESSION["scenary_id"] = $id;
            $scenary = escenario::find($id);
            $IPR = DB::table('ipr')->where('id_escenario', $scenary->id)->first();
            if (is_null($IPR)) {
                $scenary->delete();
                return redirect(url('share_scenario'));
            }
            $advisor = $scenary->enable_advisor;
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();
            $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
            $campo = DB::table('campos')->where('id', $scenary->campo_id)->first();
            $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->first();
            $intervalo = DB::table('formacionxpozos')->where('id',$scenary->formacion_id)->first();
            $tabla = [[null,null,null,null]];
            $tabla_water = [];
            $tabla_gas = [];

            $_SESSION['basin'] = DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
            $_SESSION['field'] = DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
            $_SESSION['formation'] = DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
            $_SESSION['well'] = DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
            $_SESSION['esc'] = DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');

            $scenaryId = \Request::get('scenaryId');
            $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();

            if(is_null($IPR)) {
                $valid_table = false;
                return view('IPRS', ['user'=>$user,'IPR' => $IPR, 'cuenca' => $cuenca, 'campo' => $campo, 'pozo' => $pozo, 'formacion' => $formacion, 'tabla' => [], "valid_table" => $valid_table, 'scenary_s'=>$scenary_s,'intervalo'=>$intervalo, 'advisor'=>$advisor]); 
            } else {
                $valid_table=false;
                $i_tables=0;
                $water_table = array();
                $gas_table = array();
                $tabla = array();
                $ipr_cg_dropout_table = array();
                $ipr_cg_gasoil_table = array();
                $ipr_cg_pvt_table = array();
                $pvt_gas_table = array();
                if($IPR->fluido=="1") {
                    $table = DB::table('ipr_tabla')->select('presion', 'factor_volumetrico_aceite', 'viscosidad_aceite', 'viscosidad_agua')->where('id_ipr', $IPR->id)->get();
                    $valid_table = false;
                    $a = 0;
                    foreach($table as $v){
                        if($a == 0) {
                            $tabla = [];
                            $a = 1;
                        }

                        $row = [(float)$v->presion, (float)$v->viscosidad_aceite, (float)$v->factor_volumetrico_aceite, (float)$v->viscosidad_agua];
                        $tabla[] = $row;

                        if($this->allZeroes($row) == false) {
                            $valid_table = true;
                        }
                    }
                    $_SESSION['formation']=$formacion->nombre;
                    /* Water oil table - ipr oil */
                    $tabla_wateroil = DB::table('ipr_tabla_water')->select('lista_sw', 'lista_krw', 'lista_kro')->where('id_ipr', $IPR->id)->get();
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

                    /* Gas oil table - ipr oil */
                    $tabla_gasliquid = DB::table('ipr_tabla_gas')->select('lista_sg', 'lista_krg', 'lista_krosg')->where('id_ipr', $IPR->id)->get();
                    $lista_sg = array();
                    $lista_krg = array();
                    $lista_krosg = array();
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

                    $water_table = json_encode($water_table); /* Oil */
                    $gas_table = json_encode($gas_table); /* Oil */
                    $tabla = json_encode($tabla); /* Oil */
                    $pvt_gas_table = "[[0,0,0]]"; /* Gas */
                    $ipr_cg_gasoil_table = "[[0,0,0]]";   /* Condensate Gas */
                    $ipr_cg_dropout_table = "[[0,0]]"; /* Condensate Gas */
                    $ipr_cg_pvt_table = "[[0,0,0,0,0,0,0]]"; /* Condensate Gas */
                } else if($IPR->fluido=="2") {
                    /* Gas oil table - ipr oil */
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
                    $water_table = "[[0,0,0]]"; /* Oil */
                    $gas_table = "[[0,0,0]]"; /* Oil */
                    $tabla = "[[0,0,0]]"; /* Oil */

                    $pvt_gas_table = json_encode($pvt_gas_table); /* Gas */
                    $ipr_cg_gasoil_table = "[[0,0,0]]";   /* Condensate Gas */
                    $ipr_cg_dropout_table = "[[0,0]]"; /* Condensate Gas */
                    $ipr_cg_pvt_table = "[[0,0,0,0,0,0,0]]"; /* Condensate Gas */
                } else if($IPR->fluido=="3") {
                    $ipr_cg_gasoil = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->get();
                    $ipr_cg_gasoil_table = array();

                    foreach ($ipr_cg_gasoil as $value) {
                        $ipr_cg_gasoil_table[] = [floatval($value->sg),floatval($value->krg),floatval($value->krog)];
                    }

                    /* Drop out */
                    $ipr_cg_dropout = DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->get();
                    $ipr_cg_dropout_table = array();
                    foreach ($ipr_cg_dropout as $value) {
                        $ipr_cg_dropout_table[] = [floatval($value->pressure),floatval($value->liquid_percentage)];
                    }

                    /* PVT */
                    $ipr_cg_pvt = DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->get();
                    $ipr_cg_pvt_table = array();
                    foreach ($ipr_cg_pvt as $value) {
                        $ipr_cg_pvt_table[] = [floatval($value->pressure),floatval($value->bo),floatval($value->vo),floatval($value->rs),floatval($value->bg),floatval($value->vg),floatval($value->og_ratio)];
                    }

                    $water_table = "[[0,0,0]]"; /* Oil */
                    $gas_table = "[[0,0,0]]"; /* Oil */
                    $tabla = "[[0,0,0]]"; /* Oil */

                    $pvt_gas_table = "[[0,0,0]]"; /* Gas */

                    /* Condensate Gas */
                    $ipr_cg_gasoil_table = json_encode($ipr_cg_gasoil_table);
                    $ipr_cg_dropout_table = json_encode($ipr_cg_dropout_table);
                    $ipr_cg_pvt_table = json_encode($ipr_cg_pvt_table);
                }

                /* dd([$ipr_cg_dropout_table, $ipr_cg_gasoil_table, $ipr_cg_pvt_table]); */
                $scenaryId = \Request::get('scenaryId');
                $scenary_s = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
                $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary_s->id)->first();
                /* dd($pvt_gas_table); */
                return view('IPRSEdit', ['user'=>$user,'IPR' => $IPR, 'cuenca' => $cuenca, 'campo' => $campo, 'pozo' => $pozo, 'formacion' => $formacion, 'tabla' => $tabla, "valid_table" => $valid_table, "i_tables" => $i_tables, "water_table" => $water_table, "gas_table" => $gas_table, 'scenary_s'=>$scenary_s,'pvt_gas_table' =>$pvt_gas_table,'intervalo'=>$intervalo, 'ipr_cg_dropout_table'=>$ipr_cg_dropout_table,'ipr_cg_gasoil_table'=>$ipr_cg_gasoil_table,'ipr_cg_pvt_table'=>$ipr_cg_pvt_table, 'advisor'=>$advisor, 'duplicateFrom' => $duplicateFrom]); 
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
    public function back($id_escenario, $duplicateFrom = null)
    {   
        if (\Auth::check()) 
        {
            $scenary = escenario::find($id_escenario);
            $advisor = $scenary->enable_advisor;
            $IPR = ipr::where('id_escenario', $scenary->id)->first();
            $basin = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
            $field = DB::table('campos')->where('id', $scenary->campo_id)->first();
            $well = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();

            $valid_table = false;

            $formacion_edit = formations_scenary::where('id_scenary',$id_escenario)->get();
            $formacion = [];
            foreach ($formacion_edit as $f_edit) {
                $actual = $f_edit;
                $nombre_f = formacionxpozo::where('id','=',$actual->id_formation)->first()->nombre;
                $actual->nombre = $nombre_f;
                $formacion[] = $f_edit;
            }

            $a = 0;
            $tabla = [[null,null,null,null]];
            $table = DB::table('ipr_tabla')->select('presion', 'factor_volumetrico_aceite', 'viscosidad_aceite', 'viscosidad_agua')->where('id_ipr', $IPR->id)->get();
            foreach($table as $v) {
                if($a == 0) {
                    $tabla = [];
                    $a = 1;
                }

                $row = [floatval($v->presion), floatval($v->viscosidad_aceite), floatval($v->factor_volumetrico_aceite), floatval($v->viscosidad_agua)];
                $tabla[] = $row;
                if($this->allZeroes($row) == false) {
                    $valid_table = true;
                }
            }

            $tabla = json_encode($tabla);

            $water_table = array();
            $gas_table = array();

            /* Water oil table - ipr oil */
            $tabla_wateroil = DB::table('ipr_tabla_water')->select('lista_sw', 'lista_krw', 'lista_kro')->where('id_ipr', $IPR->id)->get();

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


            /* Gas oil table - ipr oil */
            $tabla_gasliquid = DB::table('ipr_tabla_gas')->select('lista_sg', 'lista_krg', 'lista_krosg')->where('id_ipr', $IPR->id)->get();
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

            /* Pvt table - ipr gas */
            $tabla_pvt_gas = DB::table('ipr_pvt_gas')->select('pressure', 'gas_viscosity', 'gas_compressibility_factor')->where('id_ipr', $IPR->id)->get();
            $lista_pressure_gas = array();
            $lista_gas_viscosity = array();
            $lista_gas_compressibility = array();
            $pvt_gas_table = array();
            foreach ($tabla_pvt_gas as $pvt_gas) {
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

            /* Condesate Gas Tables */
            /* Gas oil  */
            $ipr_cg_gasoil = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $IPR->id)->get();
            $ipr_cg_gasoil_table = array();
            foreach ($ipr_cg_gasoil as $value) {
                $ipr_cg_gasoil_table[] = [floatval($value->sg),floatval($value->krg),floatval($value->krog)];
            }

            /* Drop out */
            $ipr_cg_dropout = DB::table('ipr_dropout_c_g')->where('ipr_id', $IPR->id)->get();
            $ipr_cg_dropout_table = array();
            foreach ($ipr_cg_dropout as $value) {
                $ipr_cg_dropout_table[] = [floatval($value->pressure),floatval($value->liquid_percentage)];
            }

            /* PVT */
            $ipr_cg_pvt = DB::table('ipr_pvt_c_g')->where('ipr_id', $IPR->id)->get();
            $ipr_cg_pvt_table = array();
            foreach ($ipr_cg_pvt as $value) {
                $ipr_cg_pvt_table[] = [floatval($value->pressure),floatval($value->bo),floatval($value->vo),floatval($value->rs),floatval($value->bg),floatval($value->vg),floatval($value->og_ratio)];
            }

            $water_table = json_encode($water_table); /* Oil */
            $gas_table = json_encode($gas_table); /* Oil */

            $pvt_gas_table = json_encode($pvt_gas_table); /* Gas */

            /* Condensate Gas */
            $ipr_cg_gasoil_table = json_encode($ipr_cg_gasoil_table);
            $ipr_cg_dropout_table = json_encode($ipr_cg_dropout_table);
            $ipr_cg_pvt_table = json_encode($ipr_cg_pvt_table);

            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();
            return view('template.iprs.edit', ['user'=>$user,'IPR' => $IPR, 'basin' => $basin, 'field' => $field, 'well' => $well, 'formacion' => $formacion, 'tabla' => $tabla,'valid_table'=>$valid_table, "i_tables" => $i_tables, "water_table" => $water_table, "gas_table" => $gas_table, 'scenary'=>$scenary, 'pvt_gas_table' =>$pvt_gas_table, 'ipr_cg_dropout_table'=>$ipr_cg_dropout_table,'ipr_cg_gasoil_table'=>$ipr_cg_gasoil_table,'ipr_cg_pvt_table'=>$ipr_cg_pvt_table, 'advisor'=>$advisor, 'duplicateFrom' => $duplicateFrom]); 
        }
        else
        {
            return view('loginfirst');
        }
    }

    public function method_calcula_mod_permeabilidad(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'permeabilidad' => 'required|numeric|min:0',
            'porosidad' => 'required|numeric|between:0,0.5|not_in:0',
            'tipo_roca' => 'numeric'
        ]);

        $validator->setAttributeNames(array(
            'permeabilidad' => 'Absolute Permeability',
            'porosidad' => 'Porosity', 
            'tipo_roca' => 'Rock Type'
        ));

        if($validator->fails())
        {
            return $validator->errors();
        }

        return $this->calcula_mod_permeabilidad($request->permeabilidad, $request->porosidad, $request->tipo_roca);
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
            /* Si es tipo 1 es save con run ipr, si es 2 es only save */
            $id_escenario = $id;
            $tipo_save = ($request->modo_submit == 1 ? false : true);
            $datos_vista = null;
            if($request->input("fluido") == "1") {

                $datos_vista = $this->saveBlackOil($id_escenario,$request, $tipo_save, $id);

            } else if($request->input("fluido") == "2") {

                $datos_vista = $this->saveDryGas($id_escenario,$request, $tipo_save, $id);

            } else if($request->input("fluido") == "3") {

                $datos_vista = $this->saveCondensateGas($id_escenario,$request, $tipo_save, $id);

            } else if($request->input("fluido") == "4") {

                $datos_vista = $this->saveInjectorWater($id_escenario,$request, $tipo_save, $id);

            } else if($request->input("fluido") == "5") {

                $datos_vista = $this->saveInjectorGas($id_escenario,$request, $tipo_save, $id);

            }

            if(!is_null($datos_vista)){

                return redirect(url('IPR/result',$id));

            } else {

                abort('404');
                
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


            /* $http_data = http_build_query($input_data); # Convierto a cadena url válida*/
            $http_data = http_build_query(array("url" => env('LARAVEL_IPR_ROUTE'))); /* Convierto a cadena url válida*/

            /* Archivo */

            $json = json_encode($input_data, true);

            $this->print_file($json);

            $ctx = stream_context_create(array('http'=>
                array(
                    'timeout' => 12000,  /*1200 Seconds is 200 Minutes*/
                )
            ));

            $response = file_get_contents(env('DJANGO_SERVICE_ROUTE')."/ipr/llamado?$http_data", false, $ctx); /* Hago la petición*/

            $response = json_decode($response, true);

            /* Archivo */

            $response = file_get_contents(env('DJANGO_SERVICE_ROUTE')."/ipr/file?$http_data", false, $ctx); /* Hago la petición*/
        }
        else
        {
            return view('loginfirst');
        }
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
    public function get_file(IPRRequest $request) {
        $contents = \File::get("ipr_data.txt");
        return $contents;
    }
    /**
     * Obtiene el archivo con los resultados generados y con formato desde el módulo de python para su posterior porcesamiento.
     */
    public function get_file_response() {
        $contents = \File::get("json_ipr");
        return $contents;
    }

    /**
     * Módulo cálculo IPR
    **/

    public function get_list_rates($lista_presiones_yacimiento, $lista_netpay, $lista_permeability, $presion_fondo, $total_well_rate)
    {
        $list_rates = [];
        $rate_by_layer = [];

        foreach ($lista_presiones_yacimiento as $i => $presion_yacimiento) {
            $aux = $lista_netpay[$i] * $lista_permeability[$i] * ($presion_yacimiento - $presion_fondo);
            array_push($rate_by_layer, $aux);
        }

        $total_sum = array_sum($rate_by_layer);

        foreach ($rate_by_layer as $rate) {
            array_push($list_rates, ($rate * $total_well_rate)/$total_sum);
        }

        return $list_rates;

    }

    public function pressures_range($initial_pressure, $final_pressure)
    {
        $step = ($final_pressure - $initial_pressure) / 19;

        return range($initial_pressure, $final_pressure, $step);
    }

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

    public function krosg($lista_sg, $saturacion_aceite_irred, $saturacion_gas_crit,$saturacion_aceite_irred_gas, $end_point_kr_aceite_gas, $exponente_corey_aceite_gas)
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

    public function krg($lista_sg, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $exponente_corey_gas, $end_point_kr_gas)
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

    public function saturacion_agua($presiones, $viscosidades_agua, $viscosidades_aceite, $pprom, $bsw, $lista_sw, $lista_krw, $lista_kro, $viscosidad_agua)
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
            $valor_interpolado = (($pprom - end($p)) * (end($datos) - $datos[count($datos)-2]) / (end($p) - $p[count($p)-2])) + end($datos); /* Revisar. */
        }
        else  /* Se verifica si el valor de presion promedio esta entre dos presiones diferentes de cero */
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

    public function skin_aceite($presion_burbuja, $presion_yacimiento, $presion_fondo, $tasa_aceite, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw)
    {
        $skin = 0;
        if($presion_yacimiento > $presion_burbuja and $presion_fondo >= $presion_burbuja)
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
        else if($presion_yacimiento <= $presion_burbuja and $presion_fondo < $presion_burbuja)
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
        else if ($presion_yacimiento >= $presion_burbuja and $presion_fondo < $presion_burbuja)
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

    public function ipr_aceite($rango_presiones, $presion_burbuja, $presion_yacimiento, $skin, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_pvt, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw)
    {
        $rango_presiones = array_reverse($rango_presiones);
        $tasa_aceite_lista = [];

        foreach ($rango_presiones as $presion_fondo)
        {
            if ($presion_fondo < $presion_yacimiento) {
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

                }

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

                    array_push($tasa_aceite_lista,($numerador / $denominador));

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

                }
            } else {
                array_push($tasa_aceite_lista, 0);                
            }
        }

        return [$tasa_aceite_lista, $rango_presiones];
    }

    public function skin_gas($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $tasa_gas, $presiones_pvt, $viscosidades_gas, $z, $t, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo)
    {
        $pseudopresion_result = $this->pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_yacimiento);
        $presiones_integral = $pseudopresion_result[0];
        $tabla_integral =  $pseudopresion_result[1];
        $integral = $this->sumatoria($presiones_integral, $tabla_integral, $presion_fondo);
        $denominador = $t * $tasa_gas;
        $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * $integral;
        $skin = ($numerador/$denominador) - log($radio_drenaje_yac/$radio_pozo) + 0.75;

        return $skin;
    }

    public function ipr_gas($rango_presiones, $permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio)
    {
        $rango_presiones = array_reverse($rango_presiones);

        $tasa_gas_lista = [];

        $pseudopresion_result = $this->pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_yacimiento);
        $presiones_integral = $pseudopresion_result[0]; 
        $tabla_integral = $pseudopresion_result[1]; 

        foreach ($rango_presiones as $presion_fondo)
        {
            if ($presion_fondo < $presion_yacimiento) {
                $integral = $this->sumatoria($presiones_integral, $tabla_integral, $presion_fondo);
                $denominador = $t * (log($radio_drenaje_yac/$radio_pozo) - 0.75 + $skin);
                $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * $integral;

                array_push($tasa_gas_lista,($numerador/$denominador));
            } else {
                array_push($tasa_gas_lista,0);
            }

        }

        return [$tasa_gas_lista, $rango_presiones];
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

    public function ipr_gas_condensado($rango_presiones, $permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, $skin, $espesor_reservorio, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug, $lista_rs, $lista_sg, $lista_krg, $lista_kro, $lista_presiones_so, $lista_so)
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

        $tasa_gas_lista = [];
        $rango_presiones = array_reverse($rango_presiones);

        foreach ($rango_presiones as $presion_fondo)
        {
            if ($presion_fondo < $presion_yacimiento) {
                $integral = end($suma_acumulada) - $this->interpolador($pwfs, $suma_acumulada, $presion_fondo);
                $denominador = log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin;
                $numerador = 0.00000000708188342074869 * $permeabilidad_absoluta * $espesor_reservorio * $integral;
                /* eliminar esta parte de abajo */
                /* Aqui comparo y recibo los valores para ejecutar pruebas. */
                if ($denominador == 0) {
                    // dd($numerador,log($radio_drenaje_yac / $radio_pozo)," - 0.75 + $skin");
                    $denominador = 1;
                }
                array_push($tasa_gas_lista, ($numerador / $denominador));
                /* eliminar esta parte de arriba */
                // array_push($tasa_gas_lista, ($numerador / $denominador)); /* descomentar apenas este solucionado*/
            } else {
                array_push($tasa_gas_lista, 0);
            }
        }

        return [$tasa_gas_lista, $rango_presiones];
    }

    public function skinWaterInjector($permeabilidad_absoluta, $espesor_reservorio, $presion_fondo, $presion_yacimiento, $water_viscosity, $water_volumetric_factor, $tasa, $radio_drenaje_yac, $radio_pozo)
    {
        $numerador = 0.00708 * $permeabilidad_absoluta * $espesor_reservorio * ($presion_fondo - $presion_yacimiento);
        $denominador = $water_viscosity*$water_volumetric_factor*$tasa;
        return ($numerador / $denominador) - log($radio_drenaje_yac/$radio_pozo) + 0.75;
    }

    public function iirWater($rango_presiones, $skin, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity)
    {

        $tasa_lista = [];

        foreach ($rango_presiones as $presion_fondo)
        {
            if ($presion_fondo > $presion_yacimiento && $presion_fondo <= $presion_ruptura) {
                $numerador = 0.00708 * $permeabilidad_absoluta * $espesor_reservorio * ($presion_fondo - $presion_yacimiento);
                $denominador = $water_viscosity*$water_volumetric_factor * (log($radio_drenaje_yac / $radio_pozo) - 0.75 + $skin);

                array_push($tasa_lista, ($numerador / $denominador));
            } else {
                array_push($tasa_lista, 0);
            }
        }
        
        return [$tasa_lista, $rango_presiones];
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

    public function iirGas($rango_presiones, $permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio)
    {

        $tasa_lista = [];
        
        $pseudopresion_result = $this->pseudopresion($z, $viscosidades_gas, $presiones_pvt, $presion_ruptura);
        $presiones_integral = $pseudopresion_result[0]; 
        $tabla_integral = $pseudopresion_result[1]; 

        foreach ($rango_presiones as $presion_fondo)
        {
            if ($presion_fondo > $presion_yacimiento && $presion_fondo <= $presion_ruptura) {
                $integral_hasta_yacimiento = $this->interpolador($presiones_integral, $tabla_integral, $presion_yacimiento);
                $integral_hasta_fondo = $this->interpolador($presiones_integral, $tabla_integral, $presion_fondo);
                $denominador = $t * (log($radio_drenaje_yac/$radio_pozo) - 0.75 + $skin);
                $numerador = 0.000000703 * $permeabilidad_absoluta * $espesor_reservorio * ($integral_hasta_fondo - $integral_hasta_yacimiento);

                array_push($tasa_lista,($numerador/$denominador));
            } else {
                array_push($tasa_lista, 0);
            }
        }

        return [$tasa_lista, $rango_presiones];
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


    public function retornoaceite($permeabilidad_abs_ini_aceite, $modulo_permeabilidad_aceite, $porosidad_aceite, $permeabilidad_aceite, $tipo_roca_aceite, $presion_yacimiento_aceite, $presion_inicial_aceite, $espesor_reservorio_aceite, $radio_drenaje_yac_aceite, $radio_pozo_aceite, $presiones_aceite, $viscosidades_aceite,$factores_vol_aceite, $viscosidades_agua, $bsw, $presion_fondo_aceite, $tasa_aceite, $end_point_kr_agua, $end_point_kr_gas, $end_point_kr_aceite_gas, $end_point_kr_petroleo, $exponente_corey_agua, $exponente_corey_gas, $exponente_corey_aceite_gas, $exponente_corey_petroleo, $saturacion_aceite_irred, $saturacion_agua_irred, $saturacion_gas_crit, $saturacion_aceite_irred_gas, $campo_a1, $campo_b1, $campo_c1, $campo_d1, $campo_a2, $campo_b2, $campo_c2, $campo_d2, $viscosidad_agua, $lista_sg, $lista_krg, $lista_krosg, $lista_sw, $lista_krw, $lista_kro, $presion_burbuja, $factor_dano)
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

    public function run_ipr($input_data) /* Prepare data */
    {
        $fluido = $input_data['fluido'];
        if($fluido == 1) {
            $presion_yacimiento = floatval($input_data['presion_yacimiento']);
            $presion_inicial = floatval($input_data['presion_inicial']);
            
            if($input_data['stress_sensitive_reservoir'] == '2') {
                $permeabilidad_abs_ini = floatval($input_data['permeabilidad_abs_ini']);
                $modulo_permeabilidad = floatval($input_data['modulo_permeabilidad']);
                $permeabilidad_absoluta = $this->kabs($permeabilidad_abs_ini, $modulo_permeabilidad, $presion_yacimiento, $presion_inicial);
            } else {
                $permeabilidad_abs_ini = 0;
                $modulo_permeabilidad = floatval($input_data['modulo_permeabilidad']);
                $permeabilidad_absoluta = floatval($input_data['permeabilidad']);
            }
            
            $presiones_PVT = json_decode($input_data['presiones']);
            $presion_burbuja = floatval($input_data['saturation_pressure']);
            $radio_pozo = floatval($input_data['radio_pozo']);
            $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);
            $bsw = floatval($input_data['bsw']);
            $presion_fondo = floatval($input_data['presion_fondo']);
            $espesor_reservorio = floatval($input_data['espesor_reservorio']);
            $tasa_aceite = floatval($input_data['tasa_flujo']);

            $saturacion_agua_irred = floatval($input_data['saturacion_agua_irred']);
            $end_point_kr_agua = floatval($input_data['end_point_kr_agua']);
            $saturacion_aceite_irred = floatval($input_data['saturacion_aceite_irred']);
            $exponente_corey_petroleo = floatval($input_data['exponente_corey_petroleo']);
            $exponente_corey_agua = floatval($input_data['exponente_corey_agua']);
            $end_point_kr_aceite_gas = floatval($input_data['end_point_kr_aceite_gas']);
            $saturacion_gas_crit = floatval($input_data['saturacion_gas_crit']);
            $end_point_kr_gas = floatval($input_data['end_point_kr_gas']);
            $saturacion_aceite_irred_gas = floatval($input_data['saturacion_aceite_irred_gas']);
            $exponente_corey_aceite_gas = floatval($input_data['exponente_corey_aceite_gas']);
            $exponente_corey_gas = floatval($input_data['exponente_corey_gas']);
            $end_point_kr_petroleo = floatval($input_data['end_point_kr_petroleo']);

            $viscosidades_aceite = json_decode($input_data['viscosidades_aceite']);
            $factores_vol_aceite = json_decode($input_data['factores_vol_aceite']);
            $viscosidades_agua = json_decode($input_data['viscosidades_agua']);

            $rango_presiones = $this->pressures_range(0,$presion_yacimiento);
            // $lista_taza = $this->get_list_rates($lista_presiones_yacimiento, $lista_netpay, $lista_permeability, $presion_fondo, $total_well_rate);

            if(!isset($input_data['viscosidad_agua']) || is_null($input_data['viscosidad_agua'])){
                $viscosidad_agua = 0;
            } else {
                $viscosidad_agua = floatval($input_data['viscosidad_agua']);
            }

            if(isset($input_data['relative_perm_data_sel']) && $input_data['relative_perm_data_sel'] == 'check_corey_rel_perm') {
                $lista_sg = $this->gas_saturation($saturacion_aceite_irred, $saturacion_gas_crit);
                $lista_krg = $this->krg($lista_sg,$saturacion_gas_crit,$saturacion_aceite_irred_gas,$exponente_corey_gas,$end_point_kr_gas);
                $lista_krog = $this->krosg($lista_sg,$saturacion_aceite_irred,$saturacion_gas_crit,$saturacion_aceite_irred_gas,$end_point_kr_aceite_gas,$exponente_corey_aceite_gas);
                
                $lista_sw = $this->water_saturation($saturacion_aceite_irred, $saturacion_agua_irred);
                $lista_krw = $this->krw($lista_sw,$end_point_kr_agua,$saturacion_aceite_irred,$saturacion_agua_irred, $exponente_corey_agua);
                $lista_kro = $this->krosw($lista_sw,$end_point_kr_petroleo,$saturacion_aceite_irred,$saturacion_agua_irred,$exponente_corey_petroleo);
            } else {
                $lista_sg = json_decode($input_data['lista_sg']);
                $lista_krg = json_decode($input_data['lista_krg']);
                $lista_krog = json_decode($input_data['lista_krosg']);

                $lista_sw = json_decode($input_data['lista_sw']);
                $lista_krw = json_decode($input_data['lista_krw']);
                $lista_kro = json_decode($input_data['lista_kro']);
            }
            if (isset($input_data['factor_dano'])) {
                $skin = $input_data['factor_dano'];
            } else {
                $skin = $this->skin_aceite($presion_burbuja, $presion_yacimiento, $presion_fondo, $tasa_aceite, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo,$lista_sw,$lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog,$presiones_PVT,$viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);
            }

            $ipr_aceite = $this->ipr_aceite($rango_presiones,$presion_burbuja, $presion_yacimiento, $skin, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);
            $ipr_aceite_skin_cero = $this->ipr_aceite($rango_presiones,$presion_burbuja, $presion_yacimiento, 0, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);

            $data_to_return = ["ipr_cero" => $ipr_aceite_skin_cero, "skin" => $skin,"ipr" => $ipr_aceite];
            return json_encode($data_to_return); 
        } else if ($fluido == 2) {
            $presion_yacimiento = floatval($input_data['presion_yacimiento']);
            $presion_inicial = floatval($input_data['init_res_press_text_g']);
            
            if($input_data['stress_sensitive_reservoir'] == '2') {
                $abs_perm_init_text_g = floatval($input_data['abs_perm_init_text_g']);
                $modulo_permeabilidad = floatval($input_data['permeability_module_text_g']);
                $permeabilidad_absoluta = $this->kabs($abs_perm_init_text_g, $modulo_permeabilidad, $presion_yacimiento, $presion_inicial);
            } else {
                $abs_perm_init_text_g = 0;
                $modulo_permeabilidad = 0;
                $permeabilidad_absoluta = floatval($input_data['abs_perm_text_g']);
            }

            $fluido = floatval($input_data['fluido']);

            $radio_pozo = floatval($input_data['radio_pozo']);
            $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);
            
            $tasa_gas = floatval($input_data['gas_rate_g']);
            $presion_fondo = floatval($input_data['bhp_g']);

            $espesor_reservorio = floatval($input_data['net_pay_text_g']);
            
            
            $presiones_pvt = json_decode($input_data['pvt_pressure_gas']);
            $viscosidades_gas = json_decode($input_data['pvt_gasviscosity_gas']);
            $z = json_decode($input_data['pvt_gascompressibility_gas']);

            $t = floatval($input_data['temperature_text_g']) + 460;

            if (isset($input_data['factor_dano'])) {
                $skin = $input_data['factor_dano'];
            } else {
                $skin = $this->skin_gas($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $tasa_gas, $presiones_pvt, $viscosidades_gas, $z, $t, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo);
            }

            $rango_presiones = $this->pressures_range(0,$presion_yacimiento);

            $ipr_dry_gas_skin_cero = $this->ipr_gas($rango_presiones,$permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, 0, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
            $ipr_dry_gas = $this->ipr_gas($rango_presiones,$permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);

            $data_to_return = ["ipr_cero" => $ipr_dry_gas_skin_cero, "skin" => $skin,"ipr" => $ipr_dry_gas];
            
            return json_encode($data_to_return); 
        } else if ($fluido == 3) {
            $presion_yacimiento = floatval($input_data['reservoir_pressure']);
            $presion_inicial = floatval($input_data['initial_pressure_c_g']);

            if($input_data['stress_sensitive_reservoir'] == '2') {
                $abs_perm_init_text_g = floatval($input_data['permeability_c_g']);
                $modulo_permeabilidad = floatval($input_data['permeability_module_c_g']);
                $permeabilidad_absoluta = $this->kabs($abs_perm_init_text_g, $modulo_permeabilidad, $presion_yacimiento, $presion_inicial);
            } else {
                $abs_perm_init_text_g = 0;
                $modulo_permeabilidad = 0;
                $permeabilidad_absoluta = floatval($input_data['permeability_c_g']);
            }

            $radio_pozo = floatval($input_data['well_radius']); 
            $radio_drenaje_yac = floatval($input_data['drainage_radius']);

            $caudal_gas = floatval($input_data['gas_rate_c_g']);                   
            $presion_fondo = floatval($input_data['bhp_c_g']);

            $ini_abs_permeability = floatval($input_data['ini_abs_permeability_c_g']);
            $net_pay = floatval($input_data['netpay_c_g']);

            $presion_rocio = floatval($input_data['saturation_pressure_c_g']);

            $lista_bo = json_decode($input_data['bo_data']);
            $lista_bg = json_decode($input_data['bg_data']);
            $lista_uo = json_decode($input_data['vo_data']);
            $lista_ug = json_decode($input_data['vg_data']);
            $gor = floatval($input_data['gor_c_g']);
            $lista_presiones = json_decode($input_data['pressure_data']);
            $lista_ogr = json_decode($input_data['og_ratio_data']);

            $lista_presiones_so = json_decode($input_data['dropout_pressure_data']);
            $lista_so = json_decode($input_data['dropout_liquid_percentage']);

            $lista_rs = json_decode($input_data['rs_data']);
            $lista_sg = json_decode($input_data['sg_data']);
            $lista_krg = json_decode($input_data['krg_data']);
            $lista_kro = json_decode($input_data['krog_data']);

            if (isset($input_data['factor_dano'])) {
                $skin = $input_data['factor_dano'];
            } else {
                $skin = $this->skin_gas_condensado($permeabilidad_absoluta, $presion_yacimiento, $net_pay, $radio_drenaje_yac, $radio_pozo, $lista_bo, $lista_bg, $lista_uo,$lista_ug,$gor, $lista_presiones, $lista_ogr, $lista_presiones_so, $lista_so, $presion_fondo, $caudal_gas, $presion_rocio, $lista_rs, $lista_sg, $lista_krg, $lista_kro);
            }

            $rango_presiones = $this->pressures_range(0,$presion_yacimiento);
            
            $ipr_condensate_gas_skin_cero = $this->ipr_gas_condensado($rango_presiones, $permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, 0, $net_pay, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug,$lista_rs, $lista_sg,$lista_krg, $lista_kro, $lista_presiones_so, $lista_so);
            $ipr_condensate_gas = $this->ipr_gas_condensado($rango_presiones, $permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, $skin, $net_pay, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug,$lista_rs, $lista_sg,$lista_krg, $lista_kro, $lista_presiones_so, $lista_so);

            $data_to_return = ["ipr_cero" => $ipr_condensate_gas_skin_cero, "skin" => $skin,"ipr" => $ipr_condensate_gas];
            
            return json_encode($data_to_return); 
        } else if ($fluido == 4) {
            $presion_yacimiento = floatval($input_data['presion_yacimiento']);
            $presion_inicial = floatval($input_data['presion_inicial']);

            if($input_data['stress_sensitive_reservoir'] == '2') {

                $permeabilidad_abs_ini = floatval($input_data['permeabilidad_abs_ini']);
                $modulo_permeabilidad = floatval($input_data['modulo_permeabilidad']);
                $permeabilidad_absoluta = $this->kabs($permeabilidad_abs_ini, $modulo_permeabilidad, $presion_yacimiento, $presion_inicial);

            } else {

                $permeabilidad_abs_ini = 0;
                $modulo_permeabilidad = 0;
                $permeabilidad_absoluta = floatval($input_data['permeabilidad']);

            }


            $radio_pozo = floatval($input_data['radio_pozo']);
            $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);
            $tasa = json_decode($input_data['injection_rate']);
            $presion_fondo = floatval($input_data['bhfp']);
            $espesor_reservorio = floatval($input_data['espesor_reservorio']);
            $presion_ruptura = floatval($input_data['presion_separacion']);
            
            $water_volumetric_factor = floatval($input_data['water_volumetric_factor']);
            $water_viscosity = floatval($input_data['water_viscosity']);

            if (isset($input_data['factor_dano'])) {
                $skin = $input_data['factor_dano'];
            } else {
                $skin = $this->skinWaterInjector($permeabilidad_absoluta,$espesor_reservorio, $presion_fondo, $presion_yacimiento, $water_viscosity, $water_volumetric_factor, $tasa,$radio_drenaje_yac,$radio_pozo);
            }

            $rango_presiones = $this->pressures_range($presion_yacimiento,$presion_ruptura);

            $iir_water = $this->iirWater($rango_presiones, $skin, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity);
            $iir_water_skin_cero = $this->iirWater($rango_presiones, 0, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity);

            $data_to_return = ["ipr_cero" => $iir_water_skin_cero, "skin" => $skin,"ipr" => $iir_water];
            return json_encode($data_to_return); 
        } else if ($fluido == 5) {

            $presion_yacimiento = floatval($input_data['presion_yacimiento']);
            $presion_inicial = floatval($input_data['init_res_press_text_g']);
            
            if($input_data['stress_sensitive_reservoir'] == '2') {
                $abs_perm_init_text_g = floatval($input_data['abs_perm_init_text_g']);
                $modulo_permeabilidad = floatval($input_data['permeability_module_text_g']);
                $permeabilidad_absoluta = $this->kabs($abs_perm_init_text_g, $modulo_permeabilidad, $presion_yacimiento, $presion_inicial);
            } else {
                $abs_perm_init_text_g = 0;
                $modulo_permeabilidad = 0;
                $permeabilidad_absoluta = floatval($input_data['abs_perm_text_g']);
            }

            $fluido = floatval($input_data['fluido']);

            $radio_pozo = floatval($input_data['radio_pozo']);
            $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);
            
            $tasa_gas = floatval($input_data['gas_rate_g']);
            $presion_fondo = floatval($input_data['bhp_g']);

            $espesor_reservorio = floatval($input_data['net_pay_text_g']);
            $presion_ruptura = floatval($input_data['presion_separacion']);
            
            $presiones_pvt = json_decode($input_data['pvt_pressure_gas']);
            $viscosidades_gas = json_decode($input_data['pvt_gasviscosity_gas']);
            $z = json_decode($input_data['pvt_gascompressibility_gas']);

            $t = floatval($input_data['temperature_text_g']) + 460;

            if (isset($input_data['factor_dano'])) {
                $skin = $input_data['factor_dano'];
            } else {
                $skin = $this->skinGasinjector($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $presion_ruptura, $tasa_gas, $presiones_pvt, $viscosidades_gas, $z, $t, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo);
            }

            $rango_presiones = $this->pressures_range($presion_yacimiento,$presion_ruptura);

            $ipr_gas = $this->iirGas($rango_presiones, $permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
            $ipr_gas_skin_cero = $this->iirGas($rango_presiones, $permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, 0, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
            $data_to_return = ["ipr_cero" => $ipr_gas_skin_cero, "skin" => $skin,"ipr" => $ipr_gas];
            
            return json_encode($data_to_return); 
        }
    }

    public function multirun_ipr($input_data, $skin = null) /* Prepare data */
    {
        if ($skin == '@') {
            return $this->externalRun($input_data);
        }
        $id_escenario = $input_data['id_escenario'];
        $intervalos = formations_scenary::where('id_scenary',$id_escenario)->get();

        $fluido = $input_data['fluido'];

        $intervalos_list = $intervalos;
        if($fluido == 4 || $fluido == 5) {
            $presion_yacimiento_min = $intervalos_list->pluck('current_reservoir_pressure')->min();
            $reservoir_parting_pressure_min = $intervalos_list->pluck('reservoir_parting_pressure')->min();
            $pressures_range = $this->pressures_range($presion_yacimiento_min,$reservoir_parting_pressure_min);
        } else {
            $presion_yacimiento_max = $intervalos_list->pluck('current_reservoir_pressure')->max();
            $pressures_range = $this->pressures_range(0,$presion_yacimiento_max);
        }

        $radio_pozo = floatval($input_data['radio_pozo']);
        $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);

        $lista_presiones_yacimiento = $intervalos_list->pluck('current_reservoir_pressure');
        $lista_netpay = $intervalos_list->pluck('net_pay');
        $presion_fondo = $input_data['presion_fondo'];
        $well_rate = floatval($input_data['tasa_flujo']);

        /* Tasa de flujo */

        $lista_permeability = [];
        foreach ($intervalos as $intervalo) {
            $stress_sensitive_reservoir = $intervalo->stress_sensitive_reservoir;
            $absolute_permeability_it_initial_reservoir_pressure = $intervalo->absolute_permeability_it_initial_reservoir_pressure;
            $absolute_permeability = $intervalo->absolute_permeability;
            $permeability_module = $intervalo->permeability_module;
            $initial_reservoir_pressure = $intervalo->initial_reservoir_pressure;
            $current_reservoir_pressure = $intervalo->current_reservoir_pressure;

            if($stress_sensitive_reservoir == '2') {
                $lista_permeability[] = $this->kabs($absolute_permeability_it_initial_reservoir_pressure, $permeability_module, $current_reservoir_pressure, $initial_reservoir_pressure);
            } else {
                $lista_permeability[] = floatval($absolute_permeability);
            }
        }

        $list_rates = $this->get_list_rates($lista_presiones_yacimiento, $lista_netpay, $lista_permeability, $presion_fondo, $well_rate);
        /* $list_rates[$i_i] Reemplaza caudal en cualquier skin */
        
        /* Aqui se definiran los intervalos*/
        /* $i_i = Id Intervalos */
        $iprs = [];
        foreach ($intervalos as $i_i => $intervalo) {

            $id_formations_scenary = $intervalo->id;
            $stress_sensitive_reservoir = $intervalo->stress_sensitive_reservoir;
            $initial_reservoir_pressure = $intervalo->initial_reservoir_pressure;
            $absolute_permeability_it_initial_reservoir_pressure = $intervalo->absolute_permeability_it_initial_reservoir_pressure;
            $net_pay = $intervalo->net_pay;
            $current_reservoir_pressure = $intervalo->current_reservoir_pressure;
            $permeability_module = $intervalo->permeability_module;
            $reservoir_parting_pressure = $intervalo->reservoir_parting_pressure;
            $absolute_permeability = $intervalo->absolute_permeability;

            $presion_yacimiento = floatval($current_reservoir_pressure);
            $presion_inicial = floatval($initial_reservoir_pressure);
            $espesor_reservorio = floatval($net_pay);

            if($stress_sensitive_reservoir == '2') {
                $absolute_permeability_it_initial_reservoir_pressure = floatval($absolute_permeability_it_initial_reservoir_pressure);
                $permeabilidad_absoluta = $this->kabs($absolute_permeability_it_initial_reservoir_pressure, $permeability_module, $presion_yacimiento, $presion_inicial);
            } else {
                $absolute_permeability_it_initial_reservoir_pressure = 0;
                $permeabilidad_absoluta = floatval($absolute_permeability);
            }

            if($fluido == 1) {

                $presiones_PVT = json_decode($input_data['presiones']);
                
                $presion_burbuja = floatval($input_data['saturation_pressure']);
                $bsw = floatval($input_data['bsw']);
                // $tasa_aceite = floatval($input_data['tasa_flujo']); /* Equivale tambien a la variable $well_rate */

                $saturacion_agua_irred = floatval($input_data['saturacion_agua_irred']);
                $end_point_kr_agua = floatval($input_data['end_point_kr_agua']);
                $saturacion_aceite_irred = floatval($input_data['saturacion_aceite_irred']);
                $exponente_corey_petroleo = floatval($input_data['exponente_corey_petroleo']);
                $exponente_corey_agua = floatval($input_data['exponente_corey_agua']);
                $end_point_kr_aceite_gas = floatval($input_data['end_point_kr_aceite_gas']);
                $saturacion_gas_crit = floatval($input_data['saturacion_gas_crit']);
                $end_point_kr_gas = floatval($input_data['end_point_kr_gas']);
                $saturacion_aceite_irred_gas = floatval($input_data['saturacion_aceite_irred_gas']);
                $exponente_corey_aceite_gas = floatval($input_data['exponente_corey_aceite_gas']);
                $exponente_corey_gas = floatval($input_data['exponente_corey_gas']);
                $end_point_kr_petroleo = floatval($input_data['end_point_kr_petroleo']);

                $viscosidades_aceite = json_decode($input_data['viscosidades_aceite']);
                $factores_vol_aceite = json_decode($input_data['factores_vol_aceite']);
                $viscosidades_agua = json_decode($input_data['viscosidades_agua']);

                if(!isset($input_data['viscosidad_agua']) || is_null($input_data['viscosidad_agua'])){
                    $viscosidad_agua = 0;
                } else {
                    $viscosidad_agua = floatval($input_data['viscosidad_agua']);
                }

                /* if(isset($input_data['relative_perm_data_sel']) && $input_data['relative_perm_data_sel'] == 'check_corey_rel_perm') { */
                    if(($input_data['lista_sg'] == '[0]' && $input_data['lista_krg'] == '[0]' && $input_data['lista_krosg'] == '[0]') || ($input_data['lista_sw'] == '[0]' && $input_data['lista_krw'] == '[0]' && $input_data['lista_kro'] == '[0]')) {
                        $lista_sg = $this->gas_saturation($saturacion_aceite_irred, $saturacion_gas_crit);
                        $lista_krg = $this->krg($lista_sg,$saturacion_gas_crit,$saturacion_aceite_irred_gas,$exponente_corey_gas,$end_point_kr_gas);
                        $lista_krog = $this->krosg($lista_sg,$saturacion_aceite_irred,$saturacion_gas_crit,$saturacion_aceite_irred_gas,$end_point_kr_aceite_gas,$exponente_corey_aceite_gas);
                        
                        $lista_sw = $this->water_saturation($saturacion_aceite_irred, $saturacion_agua_irred);
                        $lista_krw = $this->krw($lista_sw,$end_point_kr_agua,$saturacion_aceite_irred,$saturacion_agua_irred, $exponente_corey_agua);
                        $lista_kro = $this->krosw($lista_sw,$end_point_kr_petroleo,$saturacion_aceite_irred,$saturacion_agua_irred,$exponente_corey_petroleo);
                    } else {
                        $lista_sg = json_decode($input_data['lista_sg']);
                        $lista_krg = json_decode($input_data['lista_krg']);
                        $lista_krog = json_decode($input_data['lista_krosg']);

                        $lista_sw = json_decode($input_data['lista_sw']);
                        $lista_krw = json_decode($input_data['lista_krw']);
                        $lista_kro = json_decode($input_data['lista_kro']);
                    }

                    if (is_null($skin)) {
                        $skin = $this->skin_aceite($presion_burbuja, $presion_yacimiento, $presion_fondo, $list_rates[$i_i], $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo,$lista_sw,$lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog,$presiones_PVT,$viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);
                    }
                    
                    $ipr_ = $this->ipr_aceite($pressures_range,$presion_burbuja, $presion_yacimiento, $skin, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);
                    $ipr_skin_cero_ = $this->ipr_aceite($pressures_range,$presion_burbuja, $presion_yacimiento, 0, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);

                } else if($fluido == 2) {

                    $presiones_pvt = json_decode($input_data['pvt_pressure_gas']);
                    $viscosidades_gas = json_decode($input_data['pvt_gasviscosity_gas']);
                    $z = json_decode($input_data['pvt_gascompressibility_gas']);

                    $t = floatval($input_data['temperature_text_g']) + 460;

                    if (is_null($skin)) {
                        $skin = $this->skin_gas($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $list_rates[$i_i], $presiones_pvt, $viscosidades_gas, $z, $t, $net_pay, $radio_drenaje_yac, $radio_pozo);
                    }

                    $ipr_ = $this->ipr_gas($pressures_range,$permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
                    $ipr_skin_cero_ = $this->ipr_gas($pressures_range,$permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, 0, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);

                } else if($fluido == 3) {

                    $caudal_gas = floatval($input_data['tasa_flujo']);
                    $presion_rocio = floatval($input_data['saturation_pressure_c_g']);

                    $lista_bo = json_decode($input_data['bo_data']);
                    $lista_bg = json_decode($input_data['bg_data']);
                    $lista_uo = json_decode($input_data['vo_data']);
                    $lista_ug = json_decode($input_data['vg_data']);
                    $gor = floatval($input_data['gor_c_g']);
                    $lista_presiones = json_decode($input_data['pressure_data']);
                    $lista_ogr = json_decode($input_data['og_ratio_data']);

                    $lista_presiones_so = json_decode($input_data['dropout_pressure_data']);
                    $lista_so = json_decode($input_data['dropout_liquid_percentage']);

                    $lista_rs = json_decode($input_data['rs_data']);
                    $lista_sg = json_decode($input_data['sg_data']);
                    $lista_krg = json_decode($input_data['krg_data']);
                    $lista_kro = json_decode($input_data['krog_data']);

                    if (is_null($skin)) {
                        $skin = $this->skin_gas_condensado($permeabilidad_absoluta, $presion_yacimiento, $net_pay, $radio_drenaje_yac, $radio_pozo, $lista_bo, $lista_bg, $lista_uo,$lista_ug,$gor, $lista_presiones, $lista_ogr, $lista_presiones_so, $lista_so, $presion_fondo, $list_rates[$i_i], $presion_rocio, $lista_rs, $lista_sg, $lista_krg, $lista_kro);
                    }

                    $ipr_ = $this->ipr_gas_condensado($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, $skin, $net_pay, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug,$lista_rs, $lista_sg,$lista_krg, $lista_kro, $lista_presiones_so, $lista_so);
                    $ipr_skin_cero_ = $this->ipr_gas_condensado($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, 0, $net_pay, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug,$lista_rs, $lista_sg,$lista_krg, $lista_kro, $lista_presiones_so, $lista_so);

                } else if($fluido == 4) {

                    $presion_ruptura = floatval($reservoir_parting_pressure);

                    $water_volumetric_factor = floatval($input_data['water_volumetric_factor']);
                    $water_viscosity = floatval($input_data['water_viscosity']);

                    if (is_null($skin)) {
                        $skin = $this->skinWaterInjector($permeabilidad_absoluta,$espesor_reservorio, $presion_fondo, $presion_yacimiento, $water_viscosity, $water_volumetric_factor, $list_rates[$i_i],$radio_drenaje_yac,$radio_pozo);
                    }
                #dd($pressures_range, $skin, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity);
                    $ipr_ = $this->iirWater($pressures_range, $skin, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity);
                    $ipr_skin_cero_ = $this->iirWater($pressures_range, 0, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity);

                } else if($fluido == 5) {

                    $presion_ruptura = floatval($reservoir_parting_pressure);
                    $presiones_pvt = json_decode($input_data['pvt_pressure_gas']);
                    $viscosidades_gas = json_decode($input_data['pvt_gasviscosity_gas']);

                    $z = json_decode($input_data['pvt_gascompressibility_gas']);
                    $t = floatval($input_data['temperature_text_g']) + 460;

                    if (is_null($skin)) {
                        $skin = $this->skinGasinjector($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $presion_ruptura, $list_rates[$i_i], $presiones_pvt, $viscosidades_gas, $z, $t, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo);
                    }

                    $ipr_ = $this->iirGas($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
                    $ipr_skin_cero_ = $this->iirGas($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, 0, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
                }

                array_push($iprs, compact(['id_formations_scenary','skin','ipr_','ipr_skin_cero_']));
            }

            $skin_total = 0;
            $iprs_total = [];
            $iprs_cero_total = [];
            $iprs_cero_total = [];
            $iprs_base = [];

            foreach ($iprs as $id_ext => $ipr_results_ext) {
                $caudales_ext = $ipr_results_ext['ipr_'][0];
                $caudales_cero_ext = $ipr_results_ext['ipr_skin_cero_'][0];

                $iprs_base = array_merge($iprs_base, $ipr_results_ext['ipr_'][1]);

                $skin_total = $ipr_results_ext['skin'];
                $iprs_total = array_merge($iprs_total, $caudales_ext);
                $iprs_cero_total = array_merge($iprs_cero_total, $caudales_cero_ext);

                foreach ($iprs as $id_int => $ipr_results_int) {
                    if ($id_ext != $id_int) {

                        $skin_total = $skin_total + $ipr_results_int['skin'];
                        $caudales_int = $ipr_results_int['ipr_'][0];
                        $caudales__cero_int = $ipr_results_int['ipr_skin_cero_'][0];

                        for ($i=0; $i < count($caudales_int); $i++) { 
                            $iprs_total[$i] = doubleval($iprs_total[$i]) + doubleval($caudales_int[$i]);
                            $iprs_cero_total[$i] = doubleval($iprs_cero_total[$i]) + doubleval($caudales__cero_int[$i]);
                        }
                    }
                }
                break;
            }
            
            $ipr = [];
            $ipr_cero = [];

            array_push($ipr,$iprs_total);
            array_push($ipr,$iprs_base);

            array_push($ipr_cero,$iprs_cero_total);
            array_push($ipr_cero,$iprs_base);

            $data_to_return = ["ipr_cero" => $ipr_cero, "skin" => $skin_total/count($iprs),"ipr" => $ipr, "cu_intervalos" => $iprs];
            return json_encode($data_to_return); 
        }

    /**
     * Description: Se encarga de ejecutar el run para cada uno de la cantidad de datos
     * Last update: 12/05/2019
     * Author: Esneider Mejia Ciro
     */
    public function externalRun($input_data)
    {
        $id_escenario = $input_data['id_escenario'];
        $fluido = $input_data['fluido'];
        $intervalos = formations_scenary::where('id_scenary',$id_escenario)->get();

        $presion_fondo = $input_data['presion_fondo'];
        $radio_pozo = floatval($input_data['radio_pozo']);
        $radio_drenaje_yac = floatval($input_data['radio_drenaje_yac']);

        if($fluido == 4 || $fluido == 5) {
            $presion_yacimiento_min = $input_data['current_reservoir_pressure'];
            $reservoir_parting_pressure_max = $input_data['presion_separacion'];
            $pressures_range = $this->pressures_range($presion_yacimiento_min,$reservoir_parting_pressure_max);
        } else {
            $presion_yacimiento_max = $input_data['current_reservoir_pressure'];
            $pressures_range = $this->pressures_range(0,$presion_yacimiento_max);
        }

        $iprs = [];

        $lista_permeability = [];
        foreach ($intervalos as $intervalo) {
            $stress_sensitive_reservoir = $input_data['stress_sensitive_reservoir'];
            $absolute_permeability_it_initial_reservoir_pressure = $input_data['absolute_permeability_it_initial_reservoir_pressure'];
            $absolute_permeability = $input_data['absolute_permeability'];
            $permeability_module = $input_data['permeability_module'];
            $initial_reservoir_pressure = $input_data['initial_reservoir_pressure'];
            $current_reservoir_pressure = $input_data['current_reservoir_pressure'];
            $net_pay = $input_data['net_pay'];
            $reservoir_parting_pressure = isset($input_data['presion_separacion']) ? $input_data['presion_separacion'] : $intervalo->reservoir_parting_pressure;          

            if($stress_sensitive_reservoir == '2') {
                $lista_permeability[] = $this->kabs($absolute_permeability_it_initial_reservoir_pressure, $permeability_module, $current_reservoir_pressure, $initial_reservoir_pressure);
            } else {
                $lista_permeability[] = floatval($absolute_permeability);
            }
        }

        if (isset($input_data['modificado'])) {
            $modificado = $input_data['modificado'];
            $$modificado = $input_data[$modificado];
        }

        $lista_presiones_yacimiento = $intervalos->pluck('current_reservoir_pressure');
        $lista_netpay = $intervalos->pluck('net_pay');
        $presion_fondo = $input_data['presion_fondo'];
        $well_rate = floatval($input_data['tasa_flujo']);
        
        // if ($fluido == 1) {
            
        //     $well_rate = floatval($input_data['tasa_flujo']);

        // } else if ($fluido == 2) {
            
        //     $well_rate = floatval($input_data['gas_rate_g']);
            
        // } else if ($fluido == 3) {
            
        //     $well_rate = floatval($input_data['gas_rate_c_g']);
            
        // } else if ($fluido == 4) {
            
        //     $well_rate = floatval($input_data['injection_rate']);
            
        // } else if ($fluido == 5) {
            
        //     $well_rate = floatval($input_data['gas_rate_g']);
            
        // }

        $list_rates = $this->get_list_rates($lista_presiones_yacimiento, $lista_netpay, $lista_permeability, $presion_fondo, $well_rate);
        $i_i = 0;

        $presion_yacimiento = floatval($current_reservoir_pressure);
        $presion_inicial = floatval($initial_reservoir_pressure);
        $espesor_reservorio = floatval($net_pay);

        if($stress_sensitive_reservoir == '2') {
            $absolute_permeability_it_initial_reservoir_pressure = floatval($absolute_permeability_it_initial_reservoir_pressure);
            $permeabilidad_absoluta = $this->kabs($absolute_permeability_it_initial_reservoir_pressure, $permeability_module, $presion_yacimiento, $presion_inicial);
        } else {
            $absolute_permeability_it_initial_reservoir_pressure = 0;
            $permeabilidad_absoluta = floatval($absolute_permeability);
        }

        if($fluido == 1) {

            $presiones_PVT = json_decode($input_data['presiones']);

            $presion_burbuja = floatval($input_data['saturation_pressure']);
            $bsw = floatval($input_data['bsw']);
                // $tasa_aceite = floatval($input_data['tasa_flujo']); /* Equivale tambien a la variable $well_rate */

            $saturacion_agua_irred = floatval($input_data['saturacion_agua_irred']);
            $end_point_kr_agua = floatval($input_data['end_point_kr_agua']);
            $saturacion_aceite_irred = floatval($input_data['saturacion_aceite_irred']);
            $exponente_corey_petroleo = floatval($input_data['exponente_corey_petroleo']);
            $exponente_corey_agua = floatval($input_data['exponente_corey_agua']);
            $end_point_kr_aceite_gas = floatval($input_data['end_point_kr_aceite_gas']);
            $saturacion_gas_crit = floatval($input_data['saturacion_gas_crit']);
            $end_point_kr_gas = floatval($input_data['end_point_kr_gas']);
            $saturacion_aceite_irred_gas = floatval($input_data['saturacion_aceite_irred_gas']);
            $exponente_corey_aceite_gas = floatval($input_data['exponente_corey_aceite_gas']);
            $exponente_corey_gas = floatval($input_data['exponente_corey_gas']);
            $end_point_kr_petroleo = floatval($input_data['end_point_kr_petroleo']);

            $viscosidades_aceite = json_decode($input_data['viscosidades_aceite']);
            $factores_vol_aceite = json_decode($input_data['factores_vol_aceite']);
            $viscosidades_agua = json_decode($input_data['viscosidades_agua']);

            if(!isset($input_data['viscosidad_agua']) || is_null($input_data['viscosidad_agua'])){
                $viscosidad_agua = 0;
            } else {
                $viscosidad_agua = floatval($input_data['viscosidad_agua']);
            }

            if(($input_data['lista_sg'] == '[0]' && $input_data['lista_krg'] == '[0]' && $input_data['lista_krosg'] == '[0]') || ($input_data['lista_sw'] == '[0]' && $input_data['lista_krw'] == '[0]' && $input_data['lista_kro'] == '[0]')) {
            # if(isset($input_data['relative_perm_data_sel']) && $input_data['relative_perm_data_sel'] == 'check_corey_rel_perm') {
                $lista_sg = $this->gas_saturation($saturacion_aceite_irred, $saturacion_gas_crit);
                $lista_krg = $this->krg($lista_sg,$saturacion_gas_crit,$saturacion_aceite_irred_gas,$exponente_corey_gas,$end_point_kr_gas);
                $lista_krog = $this->krosg($lista_sg,$saturacion_aceite_irred,$saturacion_gas_crit,$saturacion_aceite_irred_gas,$end_point_kr_aceite_gas,$exponente_corey_aceite_gas);

                $lista_sw = $this->water_saturation($saturacion_aceite_irred, $saturacion_agua_irred);
                $lista_krw = $this->krw($lista_sw,$end_point_kr_agua,$saturacion_aceite_irred,$saturacion_agua_irred, $exponente_corey_agua);
                $lista_kro = $this->krosw($lista_sw,$end_point_kr_petroleo,$saturacion_aceite_irred,$saturacion_agua_irred,$exponente_corey_petroleo);
            } else {
                $lista_sg = json_decode($input_data['lista_sg']);
                $lista_krg = json_decode($input_data['lista_krg']);
                $lista_krog = json_decode($input_data['lista_krosg']);

                $lista_sw = json_decode($input_data['lista_sw']);
                $lista_krw = json_decode($input_data['lista_krw']);
                $lista_kro = json_decode($input_data['lista_kro']);
            }

            $skin = $this->skin_aceite($presion_burbuja, $presion_yacimiento, $presion_fondo, $list_rates[$i_i], $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo,$lista_sw,$lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog,$presiones_PVT,$viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);
            $ipr_ = $this->ipr_aceite($pressures_range,$presion_burbuja, $presion_yacimiento, $skin, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);
            $ipr_skin_cero_ = $this->ipr_aceite($pressures_range,$presion_burbuja, $presion_yacimiento, 0, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $lista_sw, $lista_krw, $lista_kro, $lista_sg, $lista_krg, $lista_krog, $presiones_PVT, $viscosidades_agua, $viscosidades_aceite, $viscosidad_agua, $factores_vol_aceite, $bsw);

        } else if($fluido == 2) {

            $presiones_pvt = json_decode($input_data['pvt_pressure_gas']);
            $viscosidades_gas = json_decode($input_data['pvt_gasviscosity_gas']);
            $z = json_decode($input_data['pvt_gascompressibility_gas']);

            $t = floatval($input_data['temperature_text_g']) + 460;

            $skin = $this->skin_gas($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $list_rates[$i_i], $presiones_pvt, $viscosidades_gas, $z, $t, $net_pay, $radio_drenaje_yac, $radio_pozo);
            $ipr_ = $this->ipr_gas($pressures_range,$permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
            $ipr_skin_cero_ = $this->ipr_gas($pressures_range,$permeabilidad_absoluta, $presion_yacimiento, $z, $t, $radio_drenaje_yac, $radio_pozo, 0, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);

        } else if($fluido == 3) {

            $caudal_gas = floatval($input_data['tasa_flujo']);
            $presion_rocio = floatval($input_data['saturation_pressure_c_g']);

            $lista_bo = json_decode($input_data['bo_data']);
            $lista_bg = json_decode($input_data['bg_data']);
            $lista_uo = json_decode($input_data['vo_data']);
            $lista_ug = json_decode($input_data['vg_data']);
            $gor = floatval($input_data['gor_c_g']);
            $lista_presiones = json_decode($input_data['pressure_data']);
            $lista_ogr = json_decode($input_data['og_ratio_data']);

            $lista_presiones_so = json_decode($input_data['dropout_pressure_data']);
            $lista_so = json_decode($input_data['dropout_liquid_percentage']);

            $lista_rs = json_decode($input_data['rs_data']);
            $lista_sg = json_decode($input_data['sg_data']);
            $lista_krg = json_decode($input_data['krg_data']);
            $lista_kro = json_decode($input_data['krog_data']);

            $skin = $this->skin_gas_condensado($permeabilidad_absoluta, $presion_yacimiento, $net_pay, $radio_drenaje_yac, $radio_pozo, $lista_bo, $lista_bg, $lista_uo,$lista_ug,$gor, $lista_presiones, $lista_ogr, $lista_presiones_so, $lista_so, $presion_fondo, $list_rates[$i_i], $presion_rocio, $lista_rs, $lista_sg, $lista_krg, $lista_kro);
            $ipr_ = $this->ipr_gas_condensado($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, $skin, $net_pay, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug,$lista_rs, $lista_sg,$lista_krg, $lista_kro, $lista_presiones_so, $lista_so);
            $ipr_skin_cero_ = $this->ipr_gas_condensado($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $radio_drenaje_yac, $radio_pozo, 0, $net_pay, $presion_rocio, $gor, $lista_presiones, $lista_ogr, $lista_bo, $lista_bg, $lista_uo, $lista_ug,$lista_rs, $lista_sg,$lista_krg, $lista_kro, $lista_presiones_so, $lista_so);

        } else if($fluido == 4) {

            $presion_ruptura = floatval($reservoir_parting_pressure);

            $water_volumetric_factor = floatval($input_data['water_volumetric_factor']);
            $water_viscosity = floatval($input_data['water_viscosity']);

            $skin = $this->skinWaterInjector($permeabilidad_absoluta,$espesor_reservorio, $presion_fondo, $presion_yacimiento, $water_viscosity, $water_volumetric_factor, $list_rates[$i_i],$radio_drenaje_yac,$radio_pozo);
            $ipr_ = $this->iirWater($pressures_range, $skin, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity);
            $ipr_skin_cero_ = $this->iirWater($pressures_range, 0, $presion_ruptura, $presion_yacimiento, $permeabilidad_absoluta, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo, $water_volumetric_factor, $water_viscosity);

        } else if($fluido == 5) {

            $presion_ruptura = floatval($reservoir_parting_pressure);
            $presiones_pvt = json_decode($input_data['pvt_pressure_gas']);
            $viscosidades_gas = json_decode($input_data['pvt_gasviscosity_gas']);

            $z = json_decode($input_data['pvt_gascompressibility_gas']);
            $t = floatval($input_data['temperature_text_g']) + 460;

            $skin = $this->skinGasinjector($permeabilidad_absoluta, $presion_fondo, $presion_yacimiento, $presion_ruptura, $list_rates[$i_i], $presiones_pvt, $viscosidades_gas, $z, $t, $espesor_reservorio, $radio_drenaje_yac, $radio_pozo);
            $ipr_ = $this->iirGas($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, $skin, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
            $ipr_skin_cero_ = $this->iirGas($pressures_range, $permeabilidad_absoluta, $presion_yacimiento, $presion_ruptura, $z, $t, $radio_drenaje_yac, $radio_pozo, 0, $presiones_pvt, $viscosidades_gas, $espesor_reservorio);
        }

        array_push($iprs, compact(['skin','ipr_','ipr_skin_cero_']));

        $skin_total = 0;
        $iprs_total = [];
        $iprs_cero_total = [];
        $iprs_cero_total = [];
        $iprs_base = [];

        foreach ($iprs as $id_ext => $ipr_results_ext) {
            $caudales_ext = $ipr_results_ext['ipr_'][0];
            $caudales_cero_ext = $ipr_results_ext['ipr_skin_cero_'][0];

            $iprs_base = array_merge($iprs_base, $ipr_results_ext['ipr_'][1]);

            $skin_total = $ipr_results_ext['skin'];
            $iprs_total = array_merge($iprs_total, $caudales_ext);
            $iprs_cero_total = array_merge($iprs_cero_total, $caudales_cero_ext);

            foreach ($iprs as $id_int => $ipr_results_int) {
                if ($id_ext != $id_int) {

                    $skin_total = $skin_total + $ipr_results_int['skin'];
                    $caudales_int = $ipr_results_int['ipr_'][0];
                    $caudales__cero_int = $ipr_results_int['ipr_skin_cero_'][0];

                    for ($i=0; $i < count($caudales_int); $i++) { 
                        $iprs_total[$i] = doubleval($iprs_total[$i]) + doubleval($caudales_int[$i]);
                        $iprs_cero_total[$i] = doubleval($iprs_cero_total[$i]) + doubleval($caudales__cero_int[$i]);
                    }
                }
            }
            break;
        }

        $ipr = [];
        $ipr_cero = [];

        array_push($ipr,$iprs_total);
        array_push($ipr,$iprs_base);

        array_push($ipr_cero,$iprs_cero_total);
        array_push($ipr_cero,$iprs_base);

        $data_to_return = ["ipr_cero" => $ipr_cero, "skin" => $skin_total,"ipr" => $ipr, "cu_intervalos" => $iprs];
        return json_encode($data_to_return); 
    }
}

