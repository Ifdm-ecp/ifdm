<?php

namespace App\Http\Controllers;
if(!isset($_SESSION)) {
    session_start();
}
use DB;
use Validator;
use Illuminate\Http\Request;
use App\desagregacion;
use App\desagregacionEdit;
use App\ipr;
use App\ipr_tabla;
use App\ipr_resultado;
use App\resultado_desagregacion;
use App\permeabilidades_resultado_desagregacion;
use App\radios_resultado_desagregacion;
use App\Http\Requests;
use App\Http\Requests\DesagregacionRequest;
use App\desagregacion_tabla;
use App\desagregacion_RESULTADO;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use App\formacionxpozo;
use App\escenario;
use View;

class desagregacionController extends Controller
{
/**
* Despliega la vista inicial del escenario de desagregación con la información de la ipr asociada.
*
* @return \Illuminate\Http\Response
*/
public function index()
{   
    if (\Auth::check()) {
        $scenario_id = \Request::get('scenaryId');
        $scenario = escenario::find(\Request::get('scenaryId'));
        $pozo = DB::table('pozos')->where('id', $scenario->pozo_id)->first();
        $intervalo = DB::table('formacionxpozos')->where('id',$scenario->formacion_id)->first();
        
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $produccion = DB::table('production_data')->where('pozo_id', $pozo->id)->first();

        return View::make('Desagregacion', compact(['fluido', 'formacion', 'pozo', 'produccion','intervalo', 'scenario', 'scenario_id']));
    } else {
        return view('loginfirst');
    }
}

/**
* Get the validation rules that apply to the request.
*
* @return array
*/
public function validateData($request,$type,$id = null)
{
    $validator = Validator::make($request->all(), [
        'radio_pozo' => 'required|numeric',
        'tipo_roca' => 'required|in:poco consolidada,consolidada,microfracturada',
        'presion_yacimiento' => 'required|numeric',
        'radio_drenaje_yac' => 'required|numeric',
        'tasa_flujo' => 'required|numeric|',
        'presion_fondo' => 'required|numeric|min:0',
        'profundidad_medida_pozo' => 'required|numeric|',
        'espesor_canoneado' => 'required|numeric|',
        'profundidad_penetracion_canones' => 'required|numeric|',
        'fase' => 'required|numeric|in:0,45,60,90,120,360',
        'radio_perforado' => 'required|numeric|',
        'forma_area_drenaje' => 'required|integer|min:1|max:16',
        'caudal_produccion_gas' => 'required|numeric|',
        'permeabilidad_abs_ini' => 'required|numeric|',
        'relacion_perm_horiz_vert' => 'required|numeric|',
        'viscosidad_aceite' => 'required|numeric|',
        'viscosidad_gas' => 'required|numeric|',
        'gravedad_especifica_gas' => 'required|numeric|',
        'factor_volumetrico_aceite' => 'required|numeric|',
        'gradiente_esfuerzo_horizontal_minimo' => 'required|numeric|',
        'gradiente_esfuerzo_horizontal_maximo' => 'required|numeric|',
        'gradiente_esfuerzo_vertical' => 'required|numeric|',
        'profundidad_real_formacion' => 'required|numeric|',
        'espesor_formacion_productora' => 'required|numeric|',
        'dano_total_pozo' => 'required|numeric',
    ])->setAttributeNames(array(
        'radio_pozo.required' => 'Well Radius is required.',
        'tipo_roca.required' => 'Rock Type is required.',
        'presion_yacimiento.required' => 'Reservoir Pressure is required.',
        'radio_drenaje_yac.required' => 'Reservoir Drainage Radius is required.',
        'tasa_flujo.required' => 'Production Rate is required.',
        'presion_fondo.required' => 'Bottomwhole Flowing Pressure is required.',
        'profundidad_medida_pozo.required' => 'Average Well Depth is required.',
        'espesor_canoneado.required' => 'Perforating Thickness is required.',
        'profundidad_penetracion_canones.required' => 'Perforating Penetretaion Depth is required.',
        'fase.required' => 'Phase is required.',
        'radio_perforado.required' => 'Perforated Radius is required.',
        'forma_area_drenaje.required' => 'Drainage area shape is required.',
        'caudal_produccion_gas.required' => 'Gas Production Rate is required.',
        'permeabilidad_abs_ini.required' => 'Absolute Permeability is required.',
        'relacion_perm_horiz_vert.required' => 'Horizontal Vertial Permeability Ratio is required.',
        'viscosidad_aceite.required' => 'Oil Viscosity is required.',
        'viscosidad_gas.required' => 'Gas Viscosity is required.',
        'gravedad_especifica_gas.required' => 'Gas Specific Gravity is required.',
        'factor_volumetrico_aceite.required' => 'Oil Volumetric Factor is required.',
        'gradiente_esfuerzo_horizontal_minimo.required' => 'Minimum Horizontal Stress Gradient is required.',
        'gradiente_esfuerzo_horizontal_maximo.required' => 'Maximum Horizontal Stress Gradient is required.',
        'gradiente_esfuerzo_vertical.required' => 'Vertical Stress Gradient is required.',
        'profundidad_real_formacion.required' => 'True Vertical Depth is required.',
        'espesor_formacion_productora.required' => 'Producing Formation Thickness is required.',
        'dano_total_pozo.required' => 'Total Well Damage is required.',

        'radio_pozo.numeric' => 'Well Radius must be numeric.',
        'tipo_roca.numeric' => 'Rock Type must be numeric.',
        'presion_yacimiento.numeric' => 'Reservoir Pressure must be numeric.',
        'radio_drenaje_yac.numeric' => 'Reservoir Drainage Radius must be numeric.',
        'tasa_flujo.numeric' => 'Production Rate must be numeric.',
        'presion_fondo.numeric' => 'Bottomwhole Flowing Pressure must be numeric.',
        'profundidad_medida_pozo.numeric' => 'Average Well Depth must be numeric.',
        'espesor_canoneado.numeric' => 'Perforating Thickness must be numeric.',
        'profundidad_penetracion_canones.numeric' => 'Perforating Penetretaion Depth must be numeric.',
        'fase.numeric' => 'Phase must be numeric.',
        'radio_perforado.numeric' => 'Perforated Radius must be numeric.',
        'forma_area_drenaje.numeric' => 'Drainage area shape must be numeric.',
        'caudal_produccion_gas.numeric' => 'Gas Production Rate must be numeric.',
        'permeabilidad_abs_ini.numeric' => 'Absolute Permeability must be numeric.',
        'relacion_perm_horiz_vert.numeric' => 'Horizontal Vertial Permeability Ratio must be numeric.',
        'viscosidad_aceite.numeric' => 'Oil Viscosity must be numeric.',
        'viscosidad_gas.numeric' => 'Gas Viscosity must be numeric.',
        'gravedad_especifica_gas.numeric' => 'Gas Specific Gravity must be numeric.',
        'factor_volumetrico_aceite.numeric' => 'Oil Volumetric Factor must be numeric.',
        'gradiente_esfuerzo_horizontal_minimo.numeric' => 'Minimum Horizontal Stress Gradient must be numeric.',
        'gradiente_esfuerzo_horizontal_maximo.numeric' => 'Maximum Horizontal Stress Gradient must be numeric.',
        'gradiente_esfuerzo_vertical.numeric' => 'Vertical Stress Gradient must be numeric.',
        'profundidad_real_formacion.numeric' => 'True Vertical Depth must be numeric.',
        'espesor_formacion_productora.numeric' => 'Producing Formation Thickness must be numeric.',
        'dano_total_pozo.numeric' => 'Total Well Damage must be numeric.'
    ));


if ($type == 'store') {
    if ($validator->fails()) {
        return redirect('Desagregacion?scenaryId='.$request->id_scenary)
        ->withErrors($validator)
        ->withInput();
    }
} else {
    if ($validator->fails()) {
        return redirect('Desagregacion?scenaryId='.$request->scenario_id)
        ->withErrors($validator)
        ->withInput();
    }
}

}


/**
* Guarda los datos del escenario de desagregación con base en la información almacenada en el formluario e inserta en la base de datos.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{ 
    if (\Auth::check()) {

        if (!isset($_POST['btn_os'])) {
            if ($res = $this->validateData($request,'update','')) {
                return $res;
            }
        }

        $scenaryId = $request->scenario_id;
        $scenary = escenario::find($scenaryId);
        $pozo = DB::table('pozos')->find($scenary->pozo_id);
        $cuenca = DB::table('cuencas')->find($scenary->cuenca_id);
        $formacion = DB::table('formacionxpozos')->where('nombre', $scenary->formacion_id)->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();

        $presion_yacimiento = $request->input("presion_yacimiento");

        $radio_pozo = $request->input("radio_pozo");

        $desagregacion= new desagregacion;
        $desagregacion->radio_pozo  = $request->input("radio_pozo");
        $desagregacion->tipo_roca  = $request->input("tipo_roca");
        $desagregacion->presion_reservorio  = $request->input("presion_yacimiento");
        $desagregacion->presion_promedio_yacimiento  = $request->input("presion_yacimiento");            
        $desagregacion->radio_drenaje_pozo  = $request->input("radio_drenaje_yac");
        $desagregacion->caudal_produccion_aceite  = $request->input("tasa_flujo");
        $desagregacion->presion_fondo_pozo  = $request->input("presion_fondo");
        $desagregacion->profundidad_medida_pozo  = $request->input("profundidad_medida_pozo");
        $desagregacion->espesor_canoneado  = $request->input("espesor_canoneado");
        $desagregacion->profundidad_penetracion_canones  = $request->input("profundidad_penetracion_canones");
        $desagregacion->angulo_fase_canoneo_perforado = $request->input("fase");
        $desagregacion->radio_perforado  = $request->input("radio_perforado");
        $desagregacion->forma_area_drenaje  = $request->input("forma_area_drenaje");
        $desagregacion->caudal_produccion_gas  = $request->input("caudal_produccion_gas");
        $desagregacion->permeabilidad_original  = $request->input("permeabilidad_abs_ini");
        $desagregacion->porosidad  = $request->input("porosity");
        $desagregacion->relacion_perm_horiz_vert  = $request->input("relacion_perm_horiz_vert");
        $desagregacion->viscosidad_aceite  = $request->input("viscosidad_aceite");
        $desagregacion->viscosidad_gas  = $request->input("viscosidad_gas");
        $desagregacion->gravedad_especifica_gas  = $request->input("gravedad_especifica_gas");
        $desagregacion->factor_volumetrico_aceite  = $request->input("factor_volumetrico_aceite");
        $desagregacion->gradiente_esfuerzo_horizontal_minimo  = $request->input("gradiente_esfuerzo_horizontal_minimo");
        $desagregacion->gradiente_esfuerzo_horizontal_maximo  = $request->input("gradiente_esfuerzo_horizontal_maximo");
        $desagregacion->gradiente_esfuerzo_vertical  = $request->input("gradiente_esfuerzo_vertical");
        $desagregacion->profundidad_real_formacion  = $request->input("profundidad_real_formacion");
        $desagregacion->permeabilidad_estimada_formacion  = $request->input("permeabilidad_abs_ini");
        $desagregacion->espesor_formacion_productora  = $request->input("espesor_formacion_productora");
        $desagregacion->dano_total_pozo  = $request->input("dano_total_pozo");
        $desagregacion->dano_total  = $request->input("dano_total_pozo");

        $desagregacion->status_wr = !isset($_POST['btn_os']);

        $desagregacion->id_escenario = $scenaryId;

        $desagregacion->save();

        $desagregacion_tabla = new desagregacion_tabla;

        $tabla = str_replace(",[null,null,null,null]","",$request->input("unidades_table"));


        $tabla = json_decode($tabla);
        foreach ($tabla as $value) {
            $desagregacion_tabla = new desagregacion_tabla;
            $desagregacion_tabla->Espesor = str_replace(",", ".", $value[0]);
            $desagregacion_tabla->fzi = str_replace(",",".",$value[1]);
            $desagregacion_tabla->porosidad_promedio = str_replace(",",".",$value[2]);
            $desagregacion_tabla->permeabilidad = str_replace(",",".",$value[3]);
            $desagregacion_tabla->id_desagregacion=$desagregacion->id;
            $desagregacion_tabla->save();
        }


        $arreglo = json_decode($request->get("unidades_table"));
        $datos_unidades_hidraulicas = array();
        foreach($arreglo as $value){
            if($value[0] != null){
                $datos_unidades_hidraulicas[] = $value;
            }
        }

        $datos_unidades_hidraulicas = json_encode($datos_unidades_hidraulicas);

        $vertical_stress_gradient = $request->get("gradiente_esfuerzo_vertical");
        $min_horizontal_stress_gradient = $request->get("gradiente_esfuerzo_horizontal_minimo");
        $max_horizontal_stress_gradient = $request->get("gradiente_esfuerzo_horizontal_maximo");
        $true_vertical_depth = $request->get("profundidad_real_formacion");
        $well_bottom_pressure = $request->get("presion_fondo");
        $oil_production_rate = $request->get("tasa_flujo");
        $oil_viscosity = $request->get("viscosidad_aceite");
        $oil_volumetric_factor = $request->get("factor_volumetrico_aceite");
        $formation_estimated_permeability = $request->get("permeabilidad_abs_ini");
        $producing_formation_thickness = $request->get("espesor_formacion_productora");
        $well_radius = $request->get("radio_pozo");
        $drainage_radius = $request->get("radio_drenaje_yac");
        $well_total_damage = $request->get("dano_total_pozo");
        $average_reservoir_pressure = $request->get("presion_yacimiento");
        $hidraulic_units_data = $datos_unidades_hidraulicas;
        $rock_type = $request->get("tipo_roca");
        $reservoir_pressure = $request->get("presion_yacimiento");
        $original_permeability = $request->get("permeabilidad_abs_ini");
        $gas_specific_gravity = $request->get("gravedad_especifica_gas");
        $gas_viscosity = $request->get("viscosidad_gas");
        $perforated_thickness = $request->get("espesor_canoneado");
        $gas_production_rate = $request->get("caudal_produccion_gas");
        $horizontal_vertical_permeability_ratio = $request->get("relacion_perm_horiz_vert");
        $average_well_depth = $request->get("profundidad_medida_pozo");
        $drainage_area_shape = $request->get("forma_area_drenaje");
        $cannon_penetrating_depth = $request->get("profundidad_penetracion_canones");
        $phase = $request->get("fase");
        $perforated_radius = $request->get("radio_perforado");
        $total_damage = $request->get("dano_total_pozo");

        $results = 0;
        $radios = 0;
        $permeabilidades = 0;
        $coeficiente_friccion = 0;
        $modulo_permeabilidad = 0;

        if (!isset($_POST['btn_os'])) {
            $results = $this->run_disaggregation_analysis($vertical_stress_gradient, $min_horizontal_stress_gradient, $max_horizontal_stress_gradient, $true_vertical_depth, $well_bottom_pressure, $oil_production_rate, $oil_viscosity, $oil_volumetric_factor, $formation_estimated_permeability, $producing_formation_thickness, $well_radius, $drainage_radius, $well_total_damage, $average_reservoir_pressure, $hidraulic_units_data, $rock_type, $reservoir_pressure, $original_permeability, $gas_specific_gravity, $gas_viscosity, $perforated_thickness, $gas_production_rate, $horizontal_vertical_permeability_ratio, $average_well_depth, $drainage_area_shape, $cannon_penetrating_depth, $phase, $perforated_radius, $total_damage);
            $radios = $results[5]; 
            $permeabilidades = $results[6]; 
            $coeficiente_friccion = $results[7];
            $modulo_permeabilidad = $results[8];
            $suma = $results[1] + $results[2] + $results[3] + $results[4];
            $total = $results[0];
            $results = array_slice($results, 0,5);

            /* Resultados spider */
            $resultado_desagregacion = new resultado_desagregacion;
            $resultado_desagregacion->id_desagregacion = $desagregacion->id;
            $resultado_desagregacion->total_skin = $results[0];
            $resultado_desagregacion->mechanical_skin = $results[1];
            $resultado_desagregacion->stress_skin = $results[2];
            $resultado_desagregacion->pseudo_skin = $results[3];
            $resultado_desagregacion->rate_skin = $results[4];
            $resultado_desagregacion->permeability_module = $modulo_permeabilidad;
            $resultado_desagregacion->friction_coefficient = $coeficiente_friccion;
            $resultado_desagregacion->save();

            /* Resultados radios */
            foreach ($radios as $value)
            {
                $resultado_radios = new radios_resultado_desagregacion;
                $resultado_radios->id_desagregacion = $desagregacion->id;
                $resultado_radios->radio = $value;
                $resultado_radios->save();
            }

            /* Resultados permeabilidades */
            foreach ($permeabilidades as $value)
            {
                $resultado_permeabilidades = new permeabilidades_resultado_desagregacion;
                $resultado_permeabilidades->id_desagregacion = $desagregacion->id;
                $resultado_permeabilidades->permeabilidad = $value;
                $resultado_permeabilidades->save();
            }

            $results = json_encode($results);
            $radios = json_encode($radios);
            $permeabilidades = json_encode($permeabilidades);
        }

        $scenary_s = DB::table('escenarios')->where('id', $desagregacion->id_escenario)->first();

        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();
        $campo = DB::table('campos')->where('id',$scenary_s->campo_id)->first();

        
        $scenary->completo = 1;
        $scenary->estado = 1;
        $scenary->save();

        return view('DesagregacionResults', compact('results', 'formacion', 'cuenca', 'pozo','radios','permeabilidades','desagregacion','suma', 'total', 'modulo_permeabilidad', 'coeficiente_friccion','scenary_s','intervalo', 'campo'));

    } 
    else 
    {
        return view('loginfirst');
    }
}

/**
* Actualiza un escenario de desagregación y todos sus componentes con base en un id específico.
*
*/
public function update(Request $request, $id)
{ 
    if (\Auth::check()) 
    {

        if (!isset($_POST['btn_os'])) {
            if ($res = $this->validateData($request,'update',$id)) {
                return $res;
            }
        }

        $scenaryId = $request->scenario_id;
        $scenary = escenario::find($scenaryId);
        $pozo = DB::table('pozos')->find($scenary->pozo_id);
        $cuenca = DB::table('cuencas')->find($scenary->cuenca_id);
        $formacion = DB::table('formacionxpozos')->where('nombre', $scenary->formacion_id)->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();

        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $presion_yacimiento = $request->input("presion_yacimiento");            
        $radio_pozo = $request->input("radio_pozo");

        $desagregacion = desagregacion::find($id);
        if (!$desagregacion) {
            $desagregacion = new desagregacion();
        }

        $desagregacion->radio_pozo  = $request->input("radio_pozo");
        $desagregacion->tipo_roca  = $request->input("tipo_roca");
        $desagregacion->presion_reservorio  = $request->input("presion_yacimiento");
        $desagregacion->presion_promedio_yacimiento  = $request->input("presion_yacimiento");            
        $desagregacion->radio_drenaje_pozo  = $request->input("radio_drenaje_yac");
        $desagregacion->caudal_produccion_aceite  = $request->input("tasa_flujo");
        $desagregacion->presion_fondo_pozo  = $request->input("presion_fondo");
        $desagregacion->profundidad_medida_pozo  = $request->input("profundidad_medida_pozo");
        $desagregacion->espesor_canoneado  = $request->input("espesor_canoneado");
        $desagregacion->profundidad_penetracion_canones  = $request->input("profundidad_penetracion_canones");
        $desagregacion->angulo_fase_canoneo_perforado = $request->input("fase");
        $desagregacion->radio_perforado  = $request->input("radio_perforado");
        $desagregacion->forma_area_drenaje  = $request->input("forma_area_drenaje");
        $desagregacion->caudal_produccion_gas  = $request->input("caudal_produccion_gas");
        $desagregacion->permeabilidad_original  = $request->input("permeabilidad_abs_ini");
        $desagregacion->porosidad  = $request->input("porosity");
        $desagregacion->relacion_perm_horiz_vert  = $request->input("relacion_perm_horiz_vert");
        $desagregacion->viscosidad_aceite  = $request->input("viscosidad_aceite");
        $desagregacion->viscosidad_gas  = $request->input("viscosidad_gas");
        $desagregacion->gravedad_especifica_gas  = $request->input("gravedad_especifica_gas");
        $desagregacion->factor_volumetrico_aceite  = $request->input("factor_volumetrico_aceite");
        $desagregacion->gradiente_esfuerzo_horizontal_minimo  = $request->input("gradiente_esfuerzo_horizontal_minimo");
        $desagregacion->gradiente_esfuerzo_horizontal_maximo  = $request->input("gradiente_esfuerzo_horizontal_maximo");
        $desagregacion->gradiente_esfuerzo_vertical  = $request->input("gradiente_esfuerzo_vertical");
        $desagregacion->profundidad_real_formacion  = $request->input("profundidad_real_formacion");
        $desagregacion->permeabilidad_estimada_formacion  = $request->input("permeabilidad_abs_ini");
        $desagregacion->espesor_formacion_productora  = $request->input("espesor_formacion_productora");
        $desagregacion->dano_total_pozo  = $request->input("dano_total_pozo");
        $desagregacion->dano_total = $request->input("dano_total_pozo");
        $desagregacion->status_wr = !isset($_POST['btn_os']);

        if ($desagregacion && $desagregacion->id_escenario == $id) {
            $desagregacion->id_escenario = $id;
        }

        $desagregacion->save();

        /** Borrando resultados y datos tablas*/
        DB::table('resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
        DB::table('permeabilidades_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
        DB::table('radios_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->delete();
        DB::table('desagregacion_tabla')->where('id_desagregacion', $desagregacion->id)->delete();

        $desagregacion_tabla = new desagregacion_tabla;
        $tabla = str_replace(",[null,null,null,null]","",$request->input("unidades_table"));
        $tabla = json_decode($tabla);
        foreach ($tabla as $value) {
            $desagregacion_tabla = new desagregacion_tabla;
            $desagregacion_tabla->Espesor = str_replace(",", ".", $value[0]);
            $desagregacion_tabla->fzi = str_replace(",",".",$value[1]);
            $desagregacion_tabla->porosidad_promedio = str_replace(",",".",$value[2]);
            $desagregacion_tabla->permeabilidad = str_replace(",",".",$value[3]);
            $desagregacion_tabla->id_desagregacion=$desagregacion->id;
            $desagregacion_tabla->save();
        }                

        $arreglo = json_decode($request->get("unidades_table"));
        $datos_unidades_hidraulicas = array();
        foreach($arreglo as $value) {
            if($value[0] != null){
                $datos_unidades_hidraulicas[] = $value;
            }
        }

        $datos_unidades_hidraulicas = json_encode($datos_unidades_hidraulicas);

        $vertical_stress_gradient = $request->get("gradiente_esfuerzo_vertical");
        $min_horizontal_stress_gradient = $request->get("gradiente_esfuerzo_horizontal_minimo");
        $max_horizontal_stress_gradient = $request->get("gradiente_esfuerzo_horizontal_maximo");
        $true_vertical_depth = $request->get("profundidad_real_formacion");
        $well_bottom_pressure = $request->get("presion_fondo");
        $oil_production_rate = $request->get("tasa_flujo");
        $oil_viscosity = $request->get("viscosidad_aceite");
        $oil_volumetric_factor = $request->get("factor_volumetrico_aceite");
        $formation_estimated_permeability = $request->get("permeabilidad_abs_ini");
        $producing_formation_thickness = $request->get("espesor_formacion_productora");
        $well_radius = $request->get("radio_pozo");
        $drainage_radius = $request->get("radio_drenaje_yac");
        $well_total_damage = $request->get("dano_total_pozo");
        $average_reservoir_pressure = $request->get("presion_yacimiento");
        $hidraulic_units_data = $datos_unidades_hidraulicas;
        $rock_type = $request->get("tipo_roca");
        $reservoir_pressure = $request->get("presion_yacimiento");
        $original_permeability = $request->get("permeabilidad_abs_ini");
        $gas_specific_gravity = $request->get("gravedad_especifica_gas");
        $gas_viscosity = $request->get("viscosidad_gas");
        $perforated_thickness = $request->get("espesor_canoneado");
        $gas_production_rate = $request->get("caudal_produccion_gas");
        $horizontal_vertical_permeability_ratio = $request->get("relacion_perm_horiz_vert");
        $average_well_depth = $request->get("profundidad_medida_pozo");
        $drainage_area_shape = $request->get("forma_area_drenaje");
        $cannon_penetrating_depth = $request->get("profundidad_penetracion_canones");
        $phase = $request->get("fase");
        $perforated_radius = $request->get("radio_perforado");
        $total_damage = $request->get("dano_total_pozo");

        $results = 0;
        $radios = 0;
        $permeabilidades = 0;
        $coeficiente_friccion = 0;
        $modulo_permeabilidad = 0;

        if (!isset($_POST['btn_os'])) {
            $results = $this->run_disaggregation_analysis($vertical_stress_gradient, $min_horizontal_stress_gradient, $max_horizontal_stress_gradient, $true_vertical_depth, $well_bottom_pressure, $oil_production_rate, $oil_viscosity, $oil_volumetric_factor, $formation_estimated_permeability, $producing_formation_thickness, $well_radius, $drainage_radius, $well_total_damage, $average_reservoir_pressure, $hidraulic_units_data, $rock_type, $reservoir_pressure, $original_permeability, $gas_specific_gravity, $gas_viscosity, $perforated_thickness, $gas_production_rate, $horizontal_vertical_permeability_ratio, $average_well_depth, $drainage_area_shape, $cannon_penetrating_depth, $phase, $perforated_radius, $total_damage);


            $radios = $results[5]; 
            $permeabilidades = $results[6]; 
            $coeficiente_friccion = $results[7];
            $modulo_permeabilidad = $results[8];
            $suma = $results[1] + $results[2] + $results[3] + $results[4];
            $total = $results[0];
            $results = array_slice($results, 0,5);

            /* Resultados spider */
            $resultado_desagregacion = new resultado_desagregacion;
            $resultado_desagregacion->id_desagregacion=$desagregacion->id;
            $resultado_desagregacion->total_skin=$results[0];
            $resultado_desagregacion->mechanical_skin=$results[1];
            $resultado_desagregacion->stress_skin=$results[2];
            $resultado_desagregacion->pseudo_skin=$results[3];
            $resultado_desagregacion->rate_skin=$results[4];
            $resultado_desagregacion->permeability_module=$modulo_permeabilidad;
            $resultado_desagregacion->friction_coefficient=$coeficiente_friccion;
            $resultado_desagregacion->save();

            /* Resultados radios */
            foreach ($radios as $value)
            {
                $resultado_radios = new radios_resultado_desagregacion;
                $resultado_radios->id_desagregacion = $desagregacion->id;
                $resultado_radios->radio = $value;
                $resultado_radios->save();
            }

            /* Resultados permeabilidades */
            foreach ($permeabilidades as $value)
            {
                $resultado_permeabilidades = new permeabilidades_resultado_desagregacion;
                $resultado_permeabilidades->id_desagregacion = $desagregacion->id;
                $resultado_permeabilidades->permeabilidad = $value;
                $resultado_permeabilidades->save();
            }

            $results = json_encode($results);
            $radios = json_encode($radios);
            $permeabilidades = json_encode($permeabilidades);
            $scenary_s = DB::table('escenarios')->where('id', $desagregacion->id_escenario)->first();
            $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();
            $campo = DB::table('campos')->where('id',$scenary_s->campo_id)->first();

            $scenario_query = escenario::find($desagregacion->id_escenario);
            $scenario_query->completo = 1;
            $scenario_query->estado = 1;
            $scenario_query->save();
        }

        return Redirect("Desagregacion/show/$scenaryId");

    } 
    else 
    {
        return view('loginfirst');
    }
}

/* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
public function duplicate($id,$duplicateFrom)
{
    $_SESSION['scenary_id_dup'] = $id;
    return $this->edit($duplicateFrom);
}

public function show_results($id)
{
    $scenario_id = $id;
    $scenary_s = DB::table('escenarios')->where('id',$scenario_id)->first();
    $desagregacion = DB::table('desagregacion')->where('id_escenario', $scenario_id)->first();
    $radius_array_query = DB::table('radios_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->get();
    $permeability_array_query = DB::table('permeabilidades_resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->get();
    $pozo = DB::table('pozos')->where('id', $scenary_s->pozo_id)->first();
    $formacion = DB::table('formaciones')->where('id', $scenary_s->formacion_id)->first();
    $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->where('pozo_id', $scenary_s->pozo_id)->first();
    $campo = DB::table('campos')->where('id',$scenary_s->campo_id)->first();

    $radios = [];
    foreach ($radius_array_query as $key => $value) {
        $radios[$key] = $value->radio;
    }

    $permeabilidades = [];
    foreach ($permeability_array_query as $key => $value) {
        $permeabilidades[$key] = $value->permeabilidad;
    }

    $radios = json_encode($radios);
    $permeabilidades = json_encode($permeabilidades);

    $total = 0;
    $suma = 0;
    $coeficiente_friccion = 0;
    $modulo_permeabilidad = 0;
    $results = json_encode([]);

    if ($desagregacion->status_wr) {
        $disaggregation_results_query = DB::table('resultado_desagregacion')->where('id_desagregacion', $desagregacion->id)->first();
        $total = $disaggregation_results_query->total_skin;
        $suma = $disaggregation_results_query->mechanical_skin + $disaggregation_results_query->stress_skin + $disaggregation_results_query->pseudo_skin + $disaggregation_results_query->rate_skin;
        $coeficiente_friccion = $disaggregation_results_query->friction_coefficient;
        $modulo_permeabilidad = $disaggregation_results_query->permeability_module;
        $results = json_encode(array($disaggregation_results_query->total_skin, $disaggregation_results_query->mechanical_skin, $disaggregation_results_query->stress_skin, $disaggregation_results_query->pseudo_skin, $disaggregation_results_query->rate_skin));
    }


    return view('DesagregacionResults', compact('results', 'formacion', 'pozo','radios','permeabilidades','desagregacion','suma', 'total', 'modulo_permeabilidad', 'coeficiente_friccion','scenary_s','intervalo','campo'));
}

/**
* Despliega la vista de resultados con la información de la ejecución del módulo en python.
*
*/
public function iprstore(DesagregacionRequest $request)
{ 
    dd("c");
    if (\Auth::check()) {

        if ($request->input('inputskins') == "back")
        {
            $request->session()->flash('mensaje', 'Back to IPR.');
        } else {
            $skins = json_decode($request->input('inputskins'), true);
            $skins = $skins["result"];
            $skins = [$skins[0], $skins[1], $skins[2] + $skins[3] + $skins[4]];
        }

        $scenary = escenario::find($_SESSION["scenary_id"]);
        $IPR = DB::table('IPR')->where('id_escenario', $scenary->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->first();
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->first();

        $IPR_TABLA = new ipr_tabla;
        /* Cambiar */
        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('cuenca_id'))->value('nombre');
        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('campo_id'))->value('nombre');
        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('formacion_id'))->value('nombre');
        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $IPR->id_escenario)->value('pozo_id'))->value('nombre');
        $_SESSION['esc']=DB::table('escenarios')->where('id',$IPR->id_escenario)->value('nombre');


        $data = array();
        $skin = 0;

        $ipr_result_calculated_skin = DB::table('ipr_resultados')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();   
        foreach ($ipr_result_calculated_skin as $value)
        {
            $data[]=[(float)$value->tasa_flujo,(float)$value->presion_fondo];
            $skin=$value->skin;
            $categorias[] = $value->tasa_flujo;
            $eje_y[] = $value->presion_fondo;
            $ipr_resultados[] = [$value->tasa_flujo, $value->presion_fondo, $value->skin];
        }

        $categorias = json_encode($categorias);
        $eje_y = json_encode($eje_y);
        $data = json_encode($data);
        $ipr_result_ideal_skin = DB::table('ipr_resultados_skin_ideal')->select('tasa_flujo', 'presion_fondo', 'skin')->where('id_ipr', $IPR->id)->get();
        foreach ($ipr_result_ideal_skin as $value)
        {
            $data_i[]=[(float)$value->tasa_flujo,(float)$value->presion_fondo];
            $ipr_resultados[] = [$value->tasa_flujo, $value->presion_fondo, $value->skin];
        }

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

        $escenario = DB::table('escenarios')->where('id', $_SESSION["scenary_id"])->first();
        $campo = DB::table('campos')->where('id', $escenario->campo_id)->first();
        $data_i = json_encode($data_i);


        $scenary_s = DB::table('escenarios')->where('id', $IPR->id_escenario)->first();
        $user = DB::table('users')->join('proyectos','users.id','=','proyectos.usuario_id')->select('users.fullName')->where('proyectos.id','=',$scenary_s->proyecto_id)->first();

        $desagregacion = DB::table('desagregacion')->where('id', $request->input("id"))->first();
        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();

        if ($request->input('inputskins') == "back")
        {   
            if(is_null($desagregacion)){
                return view('IPRResults', compact('user','campo','IPR', 'ipr_resultados' , 'pozo', 'formacion','fluido', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca', 'data_i', 'scenary_s','intervalo'));
            }
            else{
                return view('IPRResults', compact('user','campo','IPR', 'ipr_resultados' , 'pozo', 'formacion','fluido', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca', 'desagregacion', 'data_i', 'scenary_s','intervalo'));
            }
        } else {
            $skins = json_encode($skins);
            return view('IPRResults', compact('user','campo','IPR', 'ipr_resultados' , 'pozo', 'formacion','fluido', 'categorias', 'eje_y', 'skin', 'data', 'tasa_flujo', 'presion_fondo','tipo_roca','skins', 'desagregacion', 'data_i', 'scenary_s','intervalo'));
        }


    } else {
        return view('loginfirst');
    }

}

/**
* Despliega la vista de resultados con la información de la ejecución del módulo en python y la complemente con el resultado de la ipr asociada..
*
*/
public function load(DesagregacionRequest $request){
    dd("b");
    if (\Auth::check()) {

        $pozo = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
        $formacion = DB::table('formacionxpozos')->where('nombre', $_SESSION['formation'])->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();

        $presion_yacimiento = $request->input("presion_yacimiento");
        $radio_pozo = $request->input("radio_pozo");

        $desagregacion = DB::table('desagregacion')->where('id', $request->input('id'))->first();

        /* Cambiar*/
        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('cuenca_id'))->value('nombre');
        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('campo_id'))->value('nombre');
        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('formacion_id'))->value('nombre');
        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('pozo_id'))->value('nombre');
        $_SESSION['esc']=DB::table('escenarios')->where('id',$desagregacion->id_escenario)->value('nombre');
        /*  $tabla = DB::table('ipr_tabla')->where('id_ipr',$datos_ipr->id)->get();        */
        $fzi = array();
        $espesor = array();
        $porosidad_promedio = array();
        $permeabilidad = array();

        $tabla = DB::table('desagregacion_tabla')->where('id_desagregacion', $desagregacion->id)->get();


        $datos_unidades_hidraulicas = array();

        $contador = 0;



        foreach ($tabla as $fila) {
            $datos_unidades_hidraulicas[$contador][0] = (float) $fila->espesor;
            $datos_unidades_hidraulicas[$contador][1] = (float) $fila->fzi;
            $datos_unidades_hidraulicas[$contador][2] = (float) $fila->porosidad_promedio;
            $datos_unidades_hidraulicas[$contador][3] = (float) $fila->permeabilidad;
            $contador++;
        }

        $datos_unidades_hidraulicas = json_encode($datos_unidades_hidraulicas);


        $input_data = [
            "gradiente_esfuerzo_vertical" => $desagregacion->gradiente_esfuerzo_vertical,
            "gradiente_esfuerzo_horizontal_min" => $desagregacion->gradiente_esfuerzo_horizontal_minimo,
            "gradiente_esfuerzo_horizontal_max" => $desagregacion->gradiente_esfuerzo_horizontal_maximo,
            "profundidad_real_formacion" => $desagregacion->profundidad_real_formacion,
            "presion_fondo_pozo" => $desagregacion->presion_fondo_pozo,
            "caudal_produccion_aceite" => $desagregacion->caudal_produccion_aceite,
            "viscosidad_aceite" => $desagregacion->viscosidad_aceite,
            "factor_volumetrico_aceite" => $desagregacion->factor_volumetrico_aceite,
            "permeabilidad_estimada_para_formacion" => $desagregacion->permeabilidad_original,
            "espesor_formacion_productora" => $desagregacion->espesor_formacion_productora,
            "radio_pozo" => $desagregacion->radio_pozo,
            "radio_drenaje_pozo" => $desagregacion->radio_drenaje_pozo,
            "dano_total_pozo" => $desagregacion->dano_total_pozo,
            "presion_promedio_yacimiento" => $desagregacion->presion_promedio_yacimiento,
            "tipo_roca" => $desagregacion->tipo_roca,
            "presion_reservorio" => $desagregacion->presion_reservorio,
            "permeabilidad_original" => $desagregacion->permeabilidad_original,
            "gravedad_especifica_gas" => $desagregacion->gravedad_especifica_gas,
            "viscosidad_gas" => $desagregacion->viscosidad_gas,
            "espesor_canoneado" => $desagregacion->espesor_canoneado,
            "caudal_produccion_gas" => $desagregacion->caudal_produccion_gas,
            "relacion_permeabilidad_horizontal_vertical" => $desagregacion->relacion_perm_horiz_vert,
            "profundidad_medida_pozo" => $desagregacion->profundidad_medida_pozo,
            "forma_area_drenaje" => (string)$desagregacion->forma_area_drenaje,
            "profundidad_penetracion_canones" => $desagregacion->profundidad_penetracion_canones,
            "fase" => $desagregacion->angulo_fase_canoneo_perforado,
            "radio_del_perforado" => $desagregacion->radio_perforado,
            "dano_total" => $desagregacion->dano_total_pozo,
            "datos_unidades_hidraulicas" => $datos_unidades_hidraulicas,
        ]; 
        /* Variables de entrada*/

        $http_data = http_build_query(array("url" => env('LARAVEL_DESAGREGACION_ROUTE'))); /* Convierto a cadena url válida*/

        /* Archivo */

        $json = json_encode($input_data, true);

        $this->print_file($json);
        /* Archivo */
        $ctx = stream_context_create(array('http'=>
            array(
                'timeout' => 12000,  /*1200 Seconds is 200 Minutes*/
            )
        ));


        $response = file_get_contents(env('DJANGO_SERVICE_ROUTE')."/desagregacion/llamado?$http_data",false, $ctx);

        $response = json_decode($response,true);
        $radios = $response["result"][5]; 
        $permeabilidades = $response["result"][6];
        $coeficiente_friccion = $response["result"][7];
        $modulo_permeabilidad = $response["result"][8];
        $suma = $response["result"][1] + $response["result"][2] + $response["result"][3] + $response["result"][4];
        $total = $response["result"][0];
        $suma = json_encode($suma);
        $total = json_encode($total);
        $response["result"] = array_slice($response["result"], 0,5);
        $response = json_encode($response);
        $radios = json_encode($radios);
        $permeabilidades = json_encode($permeabilidades);
        $scenary_s = DB::table('escenarios')->where('id',$desagregacion->id_escenario)->first();
        $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();

        return view('DesagregacionResults', compact('response', 'formacion', 'pozo','radios','permeabilidades', 'desagregacion','suma', 'total', 'modulo_permeabilidad', 'coeficiente_friccion','scenary_s','intervalo'));

    } else {
        return view('loginfirst');
    }
}

/**
* Despliega la vista de edición del escenario de desagregación.
*
*/
public function back()
{   
    if (\Auth::check()) {

        $pozo = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
        $formacion = DB::table('formacionxpozos')->where('nombre', $_SESSION['formation'])->first();
        $datos_ipr = DB::table('ipr')->orderBy('created_at', 'desc')->first();
        $desagregacion = DB::table('desagregacion')->where('id', $request->input("id"))->first();
        $skin = DB::table('ipr_resultados')->select('skin')->where('id_ipr', $datos_ipr->id)->first();
        $pozo_identificador = DB::table('pozos')->where('Nombre', $_SESSION['well'])->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo_identificador->id)->first();


        /* Cambiar */
        $_SESSION['basin']=DB::table('cuencas')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('cuenca_id'))->value('nombre');
        $_SESSION['field']=DB::table('campos')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('campo_id'))->value('nombre');
        $_SESSION['formation']=DB::table('formaciones')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('formacion_id'))->value('nombre');
        $_SESSION['well']=DB::table('pozos')->where('id',DB::table('escenarios')->where('id', $desagregacion->id_escenario)->value('pozo_id'))->value('nombre');
        $_SESSION['esc']=DB::table('escenarios')->where('id',$desagregacion->id_escenario)->value('nombre');
        /* dd($desagregacion); */
        /* exit(); */

        $tabla = ARRAY();

        $table = DB::table('desagregacion_tabla')->select('espesor', 'fzi', 'porosidad_promedio', 'permeabilidad')->where('id_desagregacion', $desagregacion->id)->get();

        /* foreach($table as $v){ */
            /*     $tabla[] = ['espesor: '.$v->espesor, 'fzi: '.$v->fzi, 'porosidad_promedio: '.$v->porosidad_promedio, 'permeabilidad: '.$v->permeabilidad]; */
            /* } */

            foreach($table as $v){
                $tabla[] = [$v->espesor, $v->fzi, $v->porosidad_promedio, $v->permeabilidad];
            }

            $tabla = json_encode($tabla);

            /* dd($tabla); */
            /* exit(); */

            $produccion = DB::table('production_data')->where('pozo_id', $pozo_identificador->id)->first();
            $scenary_s = DB::table('escenarios')->where('id',$desagregacion->id_escenario)->first();
            $intervalo = DB::table('formacionxpozos')->where('id',$scenary_s->formacion_id)->first();

            return View::make('DesagregacionEdit', compact(['datos_ipr', 'fluido', 'formacion', 'pozo', 'produccion', 'skin', 'desagregacion', 'tabla','scenary_s','intervalo']));

        } else {
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
    if (\Auth::check()) {

        $scenaryId = $id;
        $scenario = escenario::find($scenaryId);
        $pozo = $scenario->pozo;
        $intervalo = $scenario->formacionxpozo;
        $formacion = $scenario->formacionxpozo;
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $produccion = DB::table('production_data')->where('pozo_id', $pozo->id)->first();
        $cuenca = $scenario->cuenca;
        $campo = $scenario->campo;
        $user = $scenario->user;

        $disaggregation_scenario = DB::table('desagregacion')->where('id_escenario', $id)->first();
        # $disaggregation_scenario = desagregacion::find($id);

        /* Leyendo datos desde base de datos */
        $hidraulic_units_data_query = DB::table('desagregacion_tabla')->where('id_desagregacion',$disaggregation_scenario->id)->get();

        /* Organizando datos para tablas */
        $hidraulic_units_data = [];
        foreach ($hidraulic_units_data_query as $value) 
        {
            array_push($hidraulic_units_data, array($value->espesor, $value->fzi, $value->porosidad_promedio, $value->permeabilidad));
        }

        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;
        return View::make('DesagregacionEdit', compact(['fluido', 'formacion', 'pozo', 'produccion','intervalo', 'campo', 'cuenca', 'scenario', 'scenaryId', 'hidraulic_units_data', 'disaggregation_scenario', 'duplicateFrom']));    

    } 
    else 
    {
        return view('loginfirst');
    }

}

/**
* Corre el caso base de ipr para adicionarlo a los resultados del escenario de desagregación.
*
* @param  \Illuminate\Http\Request  $request
* @param  int  $id
* @return \Illuminate\Http\Response
*/

public function apply(IPRRequest $request)
{

    if (\Auth::check()) {

        $arreglo = json_decode($request->get("ipr"));
        $ipr = array();

        $input_data = [
            "permeabilidad_abs_ini" => $request->get("permeabilidad_abs_ini"),
            "modulo_permeabilidad" => $request->get("modulo_permeabilidad"),
            "porosidad" => $request->get("porosidad"),
            "permeabilidad" => $request->get("permeabilidad"),
            "tipo_roca" => $request->get("tipo_roca"),
            "presion_yacimiento" => $request->get("presion_yacimiento"),
            "presion_inicial" => $request->get("presion_inicial"),
            "espesor_reservorio" => $request->get("espesor_reservorio"),
            "radio_drenanje_yac" => $request->get("radio_drenanje_yac"),
            "presiones" => $request->get("presiones"),
            "viscosidades_aceite" => $request->get("viscosidades_aceite"),
            "factores_vol_aceite" => $request->get("factores_vol_aceite"),
            "radio_pozo" => $request->get("radio_pozo"),
            "factor_dano" => $request->get("factor_dano"),
            "bsw" => $request->get("bsw"),
            "saturacion_aceite_irred" => $request->get("saturacion_aceite_irred"),
            "end_point_kro_petroleo" => $request->get("end_point_kro_petroleo"),
            "gor" => $request->get("gor"),
            "exponente_corey_agua" => $request->get("exponente_corey_agua"),
            "exponente_corey_gas" => $request->get("exponente_corey_gas"),
            "exponente_corey_petroleo" => $request->get("exponente_corey_petroleo"),
            "saturacion_agua_irred" => $request->get("saturacion_agua_irred"),   
            "end_point_kro_agua" => $request->get("end_point_kro_agua"),
            "end_point_kro_gas" => $request->get("end_point_kro_gas"),
            "tasa_flujo" => $request->get("tasa_flujo"),
            "presion_fondo" => $request->get("presion_fondo"),

        ];

        $http_data = http_build_query(array("url" => env('LARAVEL_IPR_ROUTE'))); /* Convierto a cadena url válida */

        /* Archivo */

        $json = json_encode($input_data, true);

        $this->print_file($json);

        $ctx = stream_context_create(array('http'=>
            array(
                'timeout' => 12000,  /* 1200 Seconds is 200 Minutes */
            )
        ));

        $response = file_get_contents(env('DJANGO_SERVICE_ROUTE')."/ipr/llamado?$http_data", false, $ctx); /*  Hago la petición*/

        $response = json_decode($response, true);

        /* Archivo */

        $response = file_get_contents(env('DJANGO_SERVICE_ROUTE')."/ipr/file?$http_data", false, $ctx); /*  Hago la petición*/

    } else {
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


/* Módulo de cálculo */
/* Cálculo del daño por efecto de los esfuerzos */

/**Distancia del pozo al punto i*/
/* Parámetro 1: @well_radius, radio del pozo */
/* Parámetro 2: @drainage_radius, radio de drenaje del pozo */
/* Return: Vector de intervalos de distancias del pozo a distintos puntos */
public function well_point_i_distance($well_radius, $drainage_radius)
{
    $well_point_i_distance = [];
#Distancia en la que aumentan los puntos a partir de los 10 pies
    $longitude = 1;
    $cont_1 = 0;

    /**Se establecen las distancias del pozo a los puntos a estudiar. Los primeros 10 pies se estudian con minuciosidad,  por ello las distancias aumentan 0.2 */
    while($well_radius + (1 * $cont_1) <= 10)
    {
        array_push($well_point_i_distance, ($well_radius + (1 * $cont_1)));
        $cont_1++;
    }

    /* Después de los 10 pies las distancias aumentan en la longitud ya calculada */
    for ($j=0; $j < 100 ; $j++) 
    { 
        $element = ($longitude + $well_point_i_distance[count($well_point_i_distance) - 1]); /* Revisar último índice */
        if($element <= $drainage_radius)
        {
            array_push($well_point_i_distance, ($longitude + $well_point_i_distance[count($well_point_i_distance) - 1]));
        }

    }

    array_push($well_point_i_distance, $drainage_radius);

    return $well_point_i_distance;
}

/* Esfuerzo total (4.1) (Verificada) */
/* Parámetro 1: @gradiente_esfuerzo_vertical, Gradiente del esfuerzo vertical */
/* Parámetro 2: @gradiente_esfuerzo_horizontal_min,  */
/* Gradiente del esfuerzo horizontal mínimo */
/* Parámetro 3: @gradiente_esfuerzo_horizontal_max, */
/* Gradiente del esfuerzo horizontal máximo */
/* Parámetro 4: @profundidad_real_formacion, */
/* Profundidad real de la formación */
/* Return: Float del esfuerzo total */
public function total_stress($vertical_stress_gradient, $min_horizontal_stress_gradient, $max_horizontal_stress_gradient, $true_vertical_depth)
{
    return((($vertical_stress_gradient + $min_horizontal_stress_gradient + $max_horizontal_stress_gradient) / 3) * $true_vertical_depth);
}

/* Presión de poro (4.2) (Verificada) */
/* Parámetro 1: @presion_fondo_pozo, Presión en el fondo del pozo */
/* Parámetro 2: @caudal_produccion_aceite, Caudal de produccion de aceite */
/* Parámetro 3: @viscosidad_aceite, Viscosidad del aceite */
/* Parámetro 4: @factor_volumetrico_aceite, Factor volumétrico del aceite */
/* Parámetro 5: @permeabilidad_estimada_para_formacion, */
/* Permeabilidad estimada para la formación */
/* Parámetro 6: @espesor_formacion_productora, Espesor de la formación productora */
/* Parámetro 7: @radio_pozo, Radio del pozo */
/* Parámetro 8: @radio_drenaje_pozo, Radio de drenaje del pozo */
/* Parámetro 9: @dano_total_pozo, Daño total en el pozo */
/* Parámetro 10: @presion_promedio_yacimiento, Presión promedio del yacimiento */
/* Return: Vector de presión de poro */
public function pore_pressure ($well_bottom_pressure, $oil_production_rate, $oil_viscosity, $oil_volumetric_factor, $formation_estimated_permeability, $producing_formation_thickness, $well_radius, $drainage_radius, $well_total_damage, $average_reservoir_pressure)
{
    $pore_pressure_at_point_i = [];
    $well_point_i_distance = $this->well_point_i_distance($well_radius, $drainage_radius);

    /* El primer valor de presión es la presión en el fondo del pozo*/
    array_push($pore_pressure_at_point_i, $well_bottom_pressure);

    /* Se llena los valores de presión para cada punto del vector de distancias*/
    foreach ($well_point_i_distance as $key => $pressure) 
    {
        $pressure = ($well_bottom_pressure + ((141.2*$oil_production_rate * $oil_viscosity * $oil_volumetric_factor) / ($formation_estimated_permeability * $producing_formation_thickness)) * (log($well_point_i_distance[$key] / $well_radius) +  $well_total_damage - 0.75));

        /** Si la presión calculada es igual o superior a la presión promedio,se ignora la calculada y se coloca en su lugar la promedio */

        if ($pressure >= $average_reservoir_pressure)
        {
            $pressure = $average_reservoir_pressure;
        }

        array_push($pore_pressure_at_point_i, $pressure);
    }

    return $pore_pressure_at_point_i;
}

/* Módulo de permeabilidad (4.3) (verificada) */
/* Parámetro 1: @indice_zona_flujo_para_tipo_roca_j, Índice de zona de flujo para */
/* el tipo de roca j */
/* Parámetro 2: @tipo_roca, tipo de roca */
/* Return: Vector de permeabilidad */
public function permeability_module($hidraulic_units_data, $rock_type)
{
    $calculated_hidraulic_units_data = [];

    if($rock_type == "consolidada")
    {
        $a = 0.000809399;
        $b = -0.986179237;
    }
    else if($rock_type == "poco consolidada")
    {
        $a = 0.000433696;
        $b = -0.587596095;
    }
    else if($rock_type == "microfracturada")
    {
        $a = 0.000613657;
        $b = 0.371958564;
    }

    foreach ($hidraulic_units_data as $hidraulic_unit) 
    {   
        array_push($hidraulic_unit, ($a * (pow(floatval($hidraulic_unit[1]), $b))));
        array_push($calculated_hidraulic_units_data, $hidraulic_unit);
    }

    return $calculated_hidraulic_units_data; 
}

/* Esfuerzo efectivo inicial (4.4) (Verificada)*/
/* Parámetro 1: @esfuerzo_total, esfuerzo total */
/* Parámetro 2: @presion_reservorio, presión del reservorio */
/* Return: Float del esfuerzo efectivo inicial */
public function initial_effective_stress($total_stress, $reservoir_pressure)
{
    return floatval($total_stress - $reservoir_pressure);
}

/**Esfuerzo efectivo (4.5) (verificada) */
/* Parámetro 1: @esfuerzo_total, esfuerzo total */
/* Parámetro 2: @presion_poro, vector de presión de poro en el punto i */
/* Return: Vector del esfuerzo efectivo */
public function effective_stress($total_stress, $pore_pressure)
{
    $effective_stress = [];

    foreach ($pore_pressure as $pressure) 
    {
        array_push($effective_stress, ($total_stress - $pressure));
    }

    return $effective_stress;
}

/* Módulo de permebilidad del pozo (4.6) (Verificada) */
/* Parámetro 1: @datos_unidades_hidraulicas, matriz con información de las unidades */
/* hidráulicas */
/* Return: Float del módulo de permeabilidad del pozo            */
public function well_permeability_module($hidraulic_units_data)
{   
    $a = 0; /* Dividendo */
    $b = 0; /* Divisor */

    foreach ($hidraulic_units_data as $hidraulic_unit) 
    {
        /* *unidad[0] = Espesor, unidad[4] = Permeabilidad promedio */
        $a += floatval($hidraulic_unit[0]) * $hidraulic_unit[4];
        $b += floatval($hidraulic_unit[0]);
    }

    return $a/$b;
}

/* Permeabilidad de la zona afectada por esfuerzos (vector) (4.7) VERIFICAR */
/* Parámetro 1: @permeabilidad_original, permeabilidad original */
/* Parámetro 2: @modulo_permeabilidad, módulo de permeabilidad */
/* Parámetro 3: @esfuerzo_efectivo_inicial, esfuerzo efectivo inicial */
/* Parámetro 4: @esfuerzo_efectivo, esfuerzo efectivo */
/* Return: Vector de la permeabilidad de la zona afectada por efecto de los */
/* esfuerzos */
public function affected_area_permeability($original_permeability, $well_permeability_module, $initial_effective_stress, $effective_stress)
{
    $affected_area_permeability = [];

    foreach ($effective_stress as $value) 
    {
        array_push($affected_area_permeability, ($original_permeability * (exp($well_permeability_module * ($initial_effective_stress - $value)))));
    }   

    return $affected_area_permeability;
}

/* Daño por esfuerzos (4.8) */
/* Parámetro 1: @permeabilidad_original */
/* Parámetro 2: @radio_drenaje_pozo */
/* Parámetro 3: @radio_pozo */
/* Parámetro 4: @permeabilidad_zona_afectada */
public function stress_damage ($original_permeability, $drainage_radius, $well_radius, $affected_area_permeability)
{
#Promedio de permeablidad_zona_afectada de a dos, en lugar de valor puntual
    $well_point_i_distance = $this->well_point_i_distance($well_radius, $drainage_radius);
    $sum = 0;

    for ($i=0; $i < count($well_point_i_distance) - 1 ; $i++) 
    { 
        $sum += (log($well_point_i_distance[$i+1] / $well_point_i_distance[$i])) / ($affected_area_permeability[$i]);
    }

    $result = $original_permeability * $sum;
    $result = $result - log($drainage_radius / $well_radius);

    return array($result, $well_point_i_distance);
}  

/* Cálculo del daño por efecto de la tasa */

/* Coeficiente  de fricción (vector) (4.9) (verificada) */
public function friction_coefficient($hidraulic_units_data)
{
    $a = 140043503030.2;
    $b = 1.096638;
    $c = -1.588135;
    $flag = 0;

    $new_hidraulic_units_data = [];
    foreach ($hidraulic_units_data as $hidraulic_unit) 
    {
        if ($hidraulic_unit[3] == 0)
        {
            $hidraulic_unit[3] = 0.000001;
            $flag = 1;
        }

        /* Posible error: división por cero */
        array_push($hidraulic_unit, ($a * pow($b, $hidraulic_unit[2]) * pow($hidraulic_unit[3], $c)));

        if($flag == 1)
        {
            $hidraulic_unit[3] = 0;
        }

        array_push($new_hidraulic_units_data, $hidraulic_unit);
    }

    return $new_hidraulic_units_data;
}

/* -Coeficiente de fricción del pozo (escalar) (4.10) (verificada) */
public function well_friction_coefficient($hidraulic_units_data)
{
$a = 0; #Numerador
$b = 0; #Denominador
foreach ($hidraulic_units_data as $hidraulic_unit) 
{
    $a += $hidraulic_unit[0] * $hidraulic_unit[5];
    $b += $hidraulic_unit[0];
}

return $a/$b;
}

/* --Coeficiente de flujo no Darcy (escalar)  */
public function non_darcy_flow_coefficient($gas_specific_gravity, $original_permeability, $gas_viscosity, $well_radius, $perforated_thickness, $well_friction_coefficient)
{
    $a = (2.22 * pow(10, -15)) * $gas_specific_gravity * $original_permeability;
    $b = $gas_viscosity * $well_radius * $perforated_thickness;

    return($well_friction_coefficient * ($a / $b) * 1000);
}

/* ----Daño por tasa (escalar) */
public function damage_rate ($non_darcy_flow_coefficient, $gas_production_rate)
{
    return $non_darcy_flow_coefficient * $gas_production_rate; 
}

/* Determinación del daño susceptible a ser mejorado por estimulación química */

/* Pseudo-daño total (4.13-4.28) */

/* -Pseudo-daño por desviación 1 (4.13) */
public function pseudo_damage_deviation_1 ($producing_formation_thickness, $well_radius, $horizontal_vertical_permeability_ratio)
{
    return (($producing_formation_thickness / $well_radius) * sqrt($horizontal_vertical_permeability_ratio));
}

/* -Pseudo-daño por desviación 2 (4.14) */
public function pseudo_damage_deviation_2 ($horizontal_vertical_permeability_ratio, $true_vertical_depth, $average_well_depth)
{
    $well_deviation_angle = asin($true_vertical_depth / $average_well_depth);
    return (atan(sqrt($horizontal_vertical_permeability_ratio) * tan($well_deviation_angle)));
}

/* -Pseudo-daño por desviación 3 (4.15) */
public function pseudo_damage_deviation_3 ($damage_deviation_2, $damage_deviation_1)
{
    return(-pow(($damage_deviation_2 / 41), 2.06) - pow(($damage_deviation_2 / 56), 1.865) * log10($damage_deviation_1 / 100));
}

/* -Pseudo-daño por penetración parcial (4.16) */
public function pseudo_damage_partial_penetration ($producing_formation_thickness, $perforated_thickness, $horizontal_vertical_permeability_ratio, $well_radius)
{
    return ((($producing_formation_thickness/$perforated_thickness)-1) * ((pow(($horizontal_vertical_permeability_ratio), 0.5) * log($producing_formation_thickness / $well_radius))-2));
}

/* -Pseudo-daño por la forma del reservorio (4.17) */
public function pseudo_damage_reservoir_shape($drainage_area_shape)
{
    if ($drainage_area_shape == 1)
    {
        $reservoir_shape = 30.88;
    }
    else if($drainage_area_shape == 2)
    {
        $reservoir_shape = 12.99;
    }
    else if($drainage_area_shape == 3)
    {
        $reservoir_shape = 4.51;
    }
    else if($drainage_area_shape == 4)
    {
        $reservoir_shape = 3.34;
    }
    else if($drainage_area_shape == 5)
    {
        $reservoir_shape = 21.84;
    }
    else if($drainage_area_shape == 6)
    {
        $reservoir_shape = 10.84;
    }
    else if($drainage_area_shape == 7)
    {
        $reservoir_shape = 4.51;
    }
    else if($drainage_area_shape == 8)
    {
        $reservoir_shape = 2.08;
    }
    else if($drainage_area_shape == 9)
    {
        $reservoir_shape = 3.16;
    }
    else if($drainage_area_shape == 10)
    {
        $reservoir_shape = 0.581;
    }
    else if($drainage_area_shape == 11)
    {
        $reservoir_shape = 0.111;
    }
    else if($drainage_area_shape == 12)
    {
        $reservoir_shape = 5.38;
    }
    else if($drainage_area_shape == 13)
    {
        $reservoir_shape = 2.69;
    }
    else if($drainage_area_shape == 14)
    {
        $reservoir_shape = 0.232;
    }
    else if($drainage_area_shape == 15)
    {
        $reservoir_shape = 0.116;
    }
    else if($drainage_area_shape == 16)
    {
        $reservoir_shape = 2.36;
    }

    return (0.5 * log(31.62 / $reservoir_shape));
}

/* ---Pseudo-daño por cañoneo 1 (4.18) */
public function pseudo_damage_perforation_1 ($pseudo_damage_perforation_8, $pseudo_damage_perforation_2, $pseudo_damage_perforation_5)
{
    return ($pseudo_damage_perforation_8 + $pseudo_damage_perforation_2 + $pseudo_damage_perforation_5);
}

/* ---Pseudo-daño por cañoneo 2 (4.19) */
public function pseudo_damage_perforation_2 ($well_radius, $equivalent_well_radius)
{
    return log($well_radius/$equivalent_well_radius);
}

/* ---Pseudo-daño por cañoneo 3 (4.20) */
public function pseudo_damage_perforation_3 ($phase, $cannon_penetrating_depth, $well_radius)
{
    $alpha0 = 0;

    if ($phase == 360)
    {
        $alpha0 = 0.25;
    }
    else if ($phase == 45)
    {
        $alpha0 = 0.86;
    }
    else if ($phase == 60)
    {
        $alpha0 = 0.813;
    }
    else if ($phase == 90)
    {
        $alpha0 = 0.726;
    }
    else if ($phase == 120)
    {
        $alpha0 = 0.648;
    }
    else if ($phase == 180)
    {
        $alpha0 = 0.5;
    }

    if($phase==0)
    {
        $pseudo_damage_perforation_3 = $cannon_penetrating_depth/4;
    }
    else
    {
        $pseudo_damage_perforation_3 = ($alpha0 * ($well_radius + $cannon_penetrating_depth));
    }

    return $pseudo_damage_perforation_3;
}


/* --Pseudo-daño por cañoneo 4 (4.21) */
public function pseudo_damage_perforation_4 ($well_radius, $cannon_penetrating_depth)
{
    return($well_radius / ($cannon_penetrating_depth + $well_radius));
}

/* ---Pseudo-daño por cañoneo 5 (4.22) */
public function pseudo_damage_perforation_5 ($phase, $pseudo_damage_perforation_4)
{
    if ($phase == 0 or $phase == 360)
    {
        $c1 = 0.16;
        $c2 = 2.675;
    }
    else if ($phase == 45)
    {
        $c1 = 0.00046;
        $c2 = 8.791;
    }
    else if ($phase == 60)
    {
        $c1 = 0.0003;
        $c2 = 7.509;
    }
    else if ($phase == 90)
    {
        $c1 = 0.0019;
        $c2 = 6.155;
    }
    else if ($phase == 120)
    {
        $c1 = 0.0066;
        $c2 = 5.32;
    }
    else if ($phase == 180)
    {
        $c1 = 0.026;
        $c2 = 4.532;
    }

    return ($c1 * exp($c2 * $pseudo_damage_perforation_4));
}

/*  ---Pseudo-daño por cañoneo 6 (4.23)*/
public function pseudo_damage_perforation_6 ($perforated_radius, $perforated_thickness, $horizontal_vertical_permeability_ratio)
{
    return (($perforated_radius/(24 * $perforated_thickness)) * (1 + sqrt($horizontal_vertical_permeability_ratio)));
}

/* ---Pseudo-daño por cañoneo 7 (4.24) */
public function pseudo_damage_perforation_7 ($perforated_thickness, $cannon_penetrating_depth, $horizontal_vertical_permeability_ratio)
{
    return(($perforated_thickness/$cannon_penetrating_depth) * sqrt($horizontal_vertical_permeability_ratio));
}

/* ---Pseudo-daño por cañoneo 10 (4.27) */
public function pseudo_damage_perforation_8 ($pseudo_damage_perforation_7, $pseudo_damage_perforation_6, $phase)
{
    if ($phase == 0 or $phase == 360)
    {
        $a1 = -2.091;
        $a2 = 0.0453;
        $b1 = 5.1313;
        $b2 = 1.8672;
    }
    else if ($phase == 45)
    {
        $a1 = -1.788;
        $a2 = 0.2398;
        $b1 = 1.1915;
        $b2 = 1.6392;
    }
    else if ($phase == 60)
    {
        $a1 = -1.898;
        $a2 = 0.1023;
        $b1 = 1.3654;
        $b2 = 1.6490;
    }
    else if ($phase == 90)
    {
        $a1 = -1.905;
        $a2 = 0.1038;
        $b1 = 1.5674;
        $b2 = 1.6935;
    }
    else if ($phase == 120)
    {
        $a1 = -20.18;
        $a2 = 0.0634;
        $b1 = 1.6136;
        $b2 = 1.7770;
    }
    else if ($phase == 180)
    {
        $a1 = -2.025;
        $a2 = 0.953;
        $b1 = 3.0373;
        $b2 = 1.8115;
    }

    $a = $a1 * log10($pseudo_damage_perforation_6) + $a2;
    $b = $b1 * $pseudo_damage_perforation_6 + $a2;

    return (pow(10, (-1 * $a)) * pow($pseudo_damage_perforation_7, ($b-1)) * pow($pseudo_damage_perforation_6, $b));
}

/* Pseudo daño (4.28) */
public function pseudo_damage($pseudo_damage_perforation_1, $pseudo_damage_partial_penetration, $reservoir_shape_damage, $pseudo_damage_deviation_3)
{
    return ($pseudo_damage_perforation_1 + $pseudo_damage_partial_penetration + $reservoir_shape_damage + $pseudo_damage_deviation_3);
}

/* Cálculo del daño total (4.29) ---Ovmr */
public function total_damage ($original_permeability, $producing_formation_thickness, $average_reservoir_pressure, $well_bottom_pressure, $oil_production_rate, $oil_viscosity, $oil_volumetric_factor, $drainage_radius, $well_radius)
{
    $constant = 7.082 * pow(10, -3);
    $a = ($original_permeability * $producing_formation_thickness * ($average_reservoir_pressure - $well_bottom_pressure)); /* Numerador */
    $b = ($oil_production_rate * $oil_viscosity * $oil_volumetric_factor); /* Denominador */
    $log =  log($drainage_radius / $well_radius);  
    return $constant * ($a / $b) - $log + 0.75;
}

/* Cálculo del daño mecánico (Potencialmente estimulable químicamente) (4.30) */
public function mechanical_damage ($total_damage, $stress_damage, $pseudo_damage, $damage_rate)
{
    return ($total_damage - $stress_damage - $pseudo_damage - $damage_rate);
}

public function run_disaggregation_analysis($vertical_stress_gradient, $min_horizontal_stress_gradient, $max_horizontal_stress_gradient, $true_vertical_depth, $well_bottom_pressure, $oil_production_rate, $oil_viscosity, $oil_volumetric_factor, $formation_estimated_permeability, $producing_formation_thickness, $well_radius, $drainage_radius, $well_total_damage, $average_reservoir_pressure, $hidraulic_units_data, $rock_type, $reservoir_pressure, $original_permeability, $gas_specific_gravity, $gas_viscosity, $perforated_thickness, $gas_production_rate, $horizontal_vertical_permeability_ratio, $average_well_depth, $drainage_area_shape, $cannon_penetrating_depth, $phase, $perforated_radius, $total_damage)
{

    $vertical_stress_gradient = floatval($vertical_stress_gradient); 
    $min_horizontal_stress_gradient = floatval($min_horizontal_stress_gradient); 
    $max_horizontal_stress_gradient = floatval($max_horizontal_stress_gradient); 
    $true_vertical_depth = floatval($true_vertical_depth); 
    $well_bottom_pressure = floatval($well_bottom_pressure); 
    $oil_production_rate = floatval($oil_production_rate); 
    $oil_viscosity = floatval($oil_viscosity); 
    $oil_volumetric_factor = floatval($oil_volumetric_factor); 
    $formation_estimated_permeability = floatval($formation_estimated_permeability); 
    $producing_formation_thickness = floatval($producing_formation_thickness); 
    $well_radius = floatval($well_radius); 
    $drainage_radius = floatval($drainage_radius); 
    $well_total_damage = floatval($well_total_damage); 
    $average_reservoir_pressure = floatval($average_reservoir_pressure); 
    $reservoir_pressure = floatval($reservoir_pressure); 
    $original_permeability = floatval($original_permeability); 
    $gas_specific_gravity = floatval($gas_specific_gravity); 
    $gas_viscosity = floatval($gas_viscosity); 
    $perforated_thickness = floatval($perforated_thickness); 
    $gas_production_rate = floatval($gas_production_rate); 
    $horizontal_vertical_permeability_ratio = floatval($horizontal_vertical_permeability_ratio); 
    $average_well_depth = floatval($average_well_depth); 
    $drainage_area_shape = floatval($drainage_area_shape); 
    $cannon_penetrating_depth = floatval($cannon_penetrating_depth); 
    $phase = floatval($phase); 
    $perforated_radius = floatval($perforated_radius); 
    $total_damage = floatval($total_damage); 
    $hidraulic_units_data = json_decode($hidraulic_units_data);

    /* Caso Base  */
    /* $vertical_stress_gradient = 1.08;  */
    /* $min_horizontal_stress_gradient = 1.2;  */
    /* $max_horizontal_stress_gradient = 0.6;  */
    /* $true_vertical_depth = 10525;  */
    /* $well_bottom_pressure = 2562;  */
    /* $oil_production_rate = 1907;  */
    /* $oil_viscosity = 0.474;  */
    /* $oil_volumetric_factor = 1.324;  */
    /* $formation_estimated_permeability = 19.6;  */
    /* $producing_formation_thickness = 261;  */
    /* $well_radius = 0.7080;  */
    /* $drainage_radius = 1500;  */
    /* $well_total_damage = 108;  */
    /* $average_reservoir_pressure = 6375;  */
    /* $reservoir_pressure = 6375;  */
    /* $original_permeability = 19.6;  */
    /* $gas_specific_gravity = 0.797;  */
    /* $gas_viscosity = 0.021;  */
    /* $perforated_thickness = 380;  */
    /* $gas_production_rate = 12.19;  */
    /* $horizontal_vertical_permeability_ratio = 0.5;  */
    /* $average_well_depth = 13702;  */
    /* $drainage_area_shape = 1;  */
    /* $cannon_penetrating_depth = 1;  */
    /* $phase = 0;  */
    /* $perforated_radius = 3.5;  */
    /* $total_damage = -1000;  */
    /* $hidraulic_units_data = [[310.5, 1.44, 5.7, 0.6], [98.3, 2.29, 5.8, 1.5],[93.8, 3.79, 6.9, 6.3]]; */
    /* $rock_type = "microfracturada"; */

    /* Caso 1 */
    /* $vertical_stress_gradient = 1.08; */
    /* $min_horizontal_stress_gradient = 0.6; */
    /* $max_horizontal_stress_gradient = 1.2; */
    /* $true_vertical_depth = 17823; */
    /* $well_bottom_pressure = 2900; */
    /* $oil_production_rate = 4914; */
    /* $oil_viscosity = 0.31; */
    /* $oil_volumetric_factor = 1.06; */
    /* $formation_estimated_permeability = 5.38; */
    /* $producing_formation_thickness = 261; */
    /* $well_radius = 0.354; */
    /* $drainage_radius = 1500; */
    /* $well_total_damage = 17; */
    /* $average_reservoir_pressure = 6750; */
    /* $reservoir_pressure = 6750; */
    /* $original_permeability = 5.38; */
    /* $gas_specific_gravity = 0.797; */
    /* $gas_viscosity = 0.01; */
    /* $perforated_thickness = 260; */
    /* $gas_production_rate = 48.9; */
    /* $horizontal_vertical_permeability_ratio = 0.5; */
    /* $average_well_depth = 18160; */
    /* $drainage_area_shape = 1; */
    /* $cannon_penetrating_depth = 0.5; */
    /* $phase = 0; */
    /* $perforated_radius = 1.8; */
    /* $total_damage = 17; */
    /* $hidraulic_units_data = [[161.3000000,1.4400000,5.7000000,0.6000000],[51.0000000,2.2900000,5.8000000,1.5000000],[48.7000000,3.7900000,6.9000000,6.3000000]]; */


    /* Caso 2 */
    /* $vertical_stress_gradient = 1.08; */
    /* $min_horizontal_stress_gradient = 0.6; */
    /* $max_horizontal_stress_gradient = 1.2; */
    /* $true_vertical_depth = 13350; */
    /* $well_bottom_pressure = 1830; */
    /* $oil_production_rate = 560; */
    /* $oil_viscosity = 0.31; */
    /* $oil_volumetric_factor = 1.06; */
    /* $formation_estimated_permeability = 50; */
    /* $producing_formation_thickness = 200; */
    /* $well_radius = 0.3; */
    /* $drainage_radius = 1000; */
    /* $well_total_damage = 10; */
    /* $average_reservoir_pressure = 3000; */
    /* $reservoir_pressure = 3000; */
    /* $original_permeability = 50; */
    /* $gas_specific_gravity = 0.9; */
    /* $gas_viscosity = 0.025; */
    /* $perforated_thickness = 40; */
    /* $gas_production_rate = 4.6; */
    /* $horizontal_vertical_permeability_ratio = 0.5; */
    /* $average_well_depth = 13350; */
    /* $drainage_area_shape = 1; */
    /* $cannon_penetrating_depth = 0.5; */
    /* $phase = 0; */
    /* $perforated_radius = 1.8; */
    /* $total_damage = 10; */
    /* $hidraulic_units_data = [[40,6.32,10,50]]; */
    /* $rock_type = "microfracturada"; */

    /* Caso 3 */
    /* $vertical_stress_gradient = 1.08; */
    /* $min_horizontal_stress_gradient = 0.6; */
    /* $max_horizontal_stress_gradient = 1.2; */
    /* $true_vertical_depth = 10525; */
    /* $well_bottom_pressure = 2562; */
    /* $oil_production_rate = 1907; */
    /* $oil_viscosity = 0.474; */
    /* $oil_volumetric_factor = 1.324; */
    /* $formation_estimated_permeability = 19.6; */
    /* $producing_formation_thickness = 261; */
    /* $well_radius = 0.708; */
    /* $drainage_radius = 1500; */
    /* $well_total_damage = 108; */
    /* $average_reservoir_pressure = 6375; */
    /* $reservoir_pressure = 6375; */
    /* $original_permeability = 19.6; */
    /* $gas_specific_gravity = 0.797; */
    /* $gas_viscosity = 0.021; */
    /* $perforated_thickness = 261; */
    /* $gas_production_rate = 12.19; */
    /* $horizontal_vertical_permeability_ratio = 0.5; */
    /* $average_well_depth = 13702; */
    /* $drainage_area_shape = 1; */
    /* $cannon_penetrating_depth = 0.5; */
    /* $phase = 0; */
    /* $perforated_radius = 1.8; */
    /* $total_damage = 108; */
    /* $hidraulic_units_data = [[198,1.44,5.7,0.6],[51,2.29,5.8,1.5],[11,3.79,6.9,6.3]]; */
    /* $rock_type = "microfracturada"; */

    $total_stress = $this->total_stress($vertical_stress_gradient, $min_horizontal_stress_gradient, $max_horizontal_stress_gradient, $true_vertical_depth);
    $pore_pressure = $this->pore_pressure($well_bottom_pressure, $oil_production_rate, $oil_viscosity, $oil_volumetric_factor, $formation_estimated_permeability, $producing_formation_thickness, $well_radius, $drainage_radius, $well_total_damage, $average_reservoir_pressure);

    $permeability_module = $this->permeability_module($hidraulic_units_data, $rock_type);

    $initial_effective_stress = $this->initial_effective_stress($total_stress, $reservoir_pressure);

    $effective_stress = $this->effective_stress($total_stress, $pore_pressure);

    $well_permeability_module = $this->well_permeability_module($permeability_module); /* Nuevos datos de unidades hidráulicas calculadas en la función permeability_module (Antes estaba hidraulic_units_data) */

    $affected_area_permeability = $this->affected_area_permeability($original_permeability, $well_permeability_module, $initial_effective_stress, $effective_stress);

    $permeability_axis = $affected_area_permeability;

    $stress_damage_results = $this->stress_damage($original_permeability, $drainage_radius, $well_radius, $affected_area_permeability);

    $stress_damage = $stress_damage_results[0];
    $pressure_axis = $stress_damage_results[1];

    $hidraulic_units_data = $this->friction_coefficient($permeability_module); /* Nuevos datos de unidades hidráulicas calculadas en la función permeability_module (Antes estaba hidraulic_units_data) */

    $well_friction_coefficient = $this->well_friction_coefficient($hidraulic_units_data);

    $non_darcy_flow_coefficient = $this->non_darcy_flow_coefficient($gas_specific_gravity, $original_permeability, $gas_viscosity, $well_radius, $perforated_thickness, $well_friction_coefficient);

    $damage_ratio = $this->damage_rate($non_darcy_flow_coefficient, $gas_production_rate);

    $damage_deviation_1 = $this->pseudo_damage_deviation_1($producing_formation_thickness, $well_radius, $horizontal_vertical_permeability_ratio);

    $damage_deviation_2 = $this->pseudo_damage_deviation_2($horizontal_vertical_permeability_ratio, $true_vertical_depth, $average_well_depth);

    $damage_deviation_3 = $this->pseudo_damage_deviation_3($damage_deviation_2, $damage_deviation_1);

    $pseudo_damage_partial_penetration = $this->pseudo_damage_partial_penetration($producing_formation_thickness, $perforated_thickness, $horizontal_vertical_permeability_ratio, $well_radius); 

    $pseudo_damage_reservoir_shape = $this->pseudo_damage_reservoir_shape($drainage_area_shape);

    $well_radius_equivalente = $this->pseudo_damage_perforation_3($phase, $cannon_penetrating_depth, $well_radius);

    $pseudo_damage_perforation_2 = $this->pseudo_damage_perforation_2($well_radius, $well_radius_equivalente);

    $pseudo_damage_perforation_4 = $this->pseudo_damage_perforation_4($well_radius, $cannon_penetrating_depth);

    $pseudo_damage_perforation_5 = $this->pseudo_damage_perforation_5($phase, $pseudo_damage_perforation_4);

    $pseudo_damage_perforation_6 = $this->pseudo_damage_perforation_6($perforated_radius, $perforated_thickness, $horizontal_vertical_permeability_ratio);

    $pseudo_damage_perforation_7 = $this->pseudo_damage_perforation_7($perforated_thickness, $cannon_penetrating_depth, $horizontal_vertical_permeability_ratio);

    $pseudo_damage_perforation_8 = $this->pseudo_damage_perforation_8($pseudo_damage_perforation_7, $pseudo_damage_perforation_6, $phase);

    $pseudo_damage_perforation_1 = $this->pseudo_damage_perforation_1($pseudo_damage_perforation_8, $pseudo_damage_perforation_2, $pseudo_damage_perforation_5);

    $pseudo_damage = $this->pseudo_damage($pseudo_damage_perforation_1, $pseudo_damage_partial_penetration, $pseudo_damage_reservoir_shape, $damage_deviation_3);

    if($total_damage == -1000)
    {
        $total_damage =  $this->total_damage($original_permeability, $producing_formation_thickness, $average_reservoir_pressure, $well_bottom_pressure, $oil_production_rate, $oil_viscosity, $oil_volumetric_factor, $drainage_radius, $well_radius);
    }


    $mechanical_damage = $this->mechanical_damage($total_damage, $stress_damage, $pseudo_damage, $damage_ratio);

    $results = array($total_damage, $mechanical_damage, $stress_damage, $pseudo_damage, $damage_ratio, $pressure_axis, $permeability_axis, $well_friction_coefficient, $well_permeability_module);

    return $results;
}

}



