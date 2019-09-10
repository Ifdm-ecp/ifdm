<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\cuenca;
use View;
use App\asphaltenes_d_stability_analysis;
use App\escenario;
use App\asphaltenes_d_stability_analysis_components;
use App\asphaltenes_d_stability_analysis_results;
use App\Http\Requests\asphaltene_stability_analysis_request;

class add_asphaltene_stability_analysis_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            $scenaryId = \Request::get('scenaryId');
            $scenary = DB::table('escenarios')->where('id', $scenaryId)->first();

            #Variables para barra de información
            $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
            $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
            $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
            $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();

            $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();
            $advisor = $scenary->enable_advisor;

            #Variable para cargar datos que se ingresan en módulo de precipitados
            $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->first();

            return View::make('add_asphaltene_stability_analysis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_precipitated_analysis']));
        } else {
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(asphaltene_stability_analysis_request $request)
    {
        $button_wr = isset($_POST['button_wr']);
        $scenaryId = $request->input('scenaryId');
        $scenary = DB::table('escenarios')->where('id', $scenaryId)->first();

        #Variables para barra de información
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();

        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        #Saber si el escenario se corrio por lo menos una vez
        $scenary = escenario::find($scenary->id);
        $scenary->estado = 1;
        $scenary->asphaltene_type = "Asphaltene stability analysis";
        $scenary->save();

        $asphaltenes_d_stability_analysis = new asphaltenes_d_stability_analysis;
        $asphaltenes_d_stability_analysis->scenario_id = $scenary->id;
        $asphaltenes_d_stability_analysis->saturated = $request->input('saturated');
        $asphaltenes_d_stability_analysis->aromatics = $request->input('aromatics');
        $asphaltenes_d_stability_analysis->resines = $request->input('resines');
        $asphaltenes_d_stability_analysis->asphaltenes = $request->input('asphaltenes');
        $asphaltenes_d_stability_analysis->reservoir_initial_pressure = $request->input('reservoir_initial_pressure');
        $asphaltenes_d_stability_analysis->bubble_pressure = $request->input('bubble_pressure');
        $asphaltenes_d_stability_analysis->density_at_reservoir_temperature = $request->input('density_at_reservoir_temperature');
        $asphaltenes_d_stability_analysis->current_reservoir_pressure = $request->input('current_reservoir_pressure');
        $asphaltenes_d_stability_analysis->api_gravity = $request->input('api_gravity');
        $asphaltenes_d_stability_analysis->status_wr = $button_wr;
        $asphaltenes_d_stability_analysis->save();

        #Tabla componentes
        $components_table = json_decode($request->input("value_components_table"));

        $components_data = [];
        $molar_fraction_data = [];

        foreach ($components_table as $value) {
            $asphaltenes_d_stability_analysis_components = new asphaltenes_d_stability_analysis_components;
            $asphaltenes_d_stability_analysis_components->asphaltenes_d_stability_analysis_id = $asphaltenes_d_stability_analysis->id;
            $asphaltenes_d_stability_analysis_components->component = str_replace(",", ".", $value[0]);
            $asphaltenes_d_stability_analysis_components->mole_fraction = str_replace(",", ".", $value[1]);
            $asphaltenes_d_stability_analysis_components->save();

            array_push($components_data, $asphaltenes_d_stability_analysis_components->component);
            array_push($molar_fraction_data, $asphaltenes_d_stability_analysis_components->mole_fraction * 100);
        }


        #Módulo análisis de estabilidad
        #Componentes [Composición, %moles, Zi]
        $components = [$components_data, $molar_fraction_data];

        #Análisis SARA
        $input_saturated = $asphaltenes_d_stability_analysis->saturated;
        $input_aromatics = $asphaltenes_d_stability_analysis->aromatics;
        $input_resines = $asphaltenes_d_stability_analysis->resines;
        $input_asphaltenes = $asphaltenes_d_stability_analysis->asphaltenes;
        $input_sara = [$input_saturated, $input_aromatics, $input_resines, $input_asphaltenes];

        #Saturación
        $input_field = $campo->nombre;
        $input_reservoir_initial_pressure = $asphaltenes_d_stability_analysis->reservoir_initial_pressure;
        $input_bubble_pressure = $asphaltenes_d_stability_analysis->bubble_pressure;
        $input_reservoir_density_at_t = $asphaltenes_d_stability_analysis->density_at_reservoir_temperature;
        $input_reservoir_current_pressure = $asphaltenes_d_stability_analysis->current_reservoir_pressure;
        $input_api_gravity = $asphaltenes_d_stability_analysis->api_gravity;
        $input_saturation = [$input_field, $input_reservoir_initial_pressure, $input_bubble_pressure, $input_reservoir_density_at_t, $input_reservoir_current_pressure, $input_api_gravity];

        if (!$button_wr) {
            $calculate_boer_stability_criteria_results = $this->calculate_boer_stability_criteria($components, $input_sara, $input_saturation);
            $conclusions = $calculate_boer_stability_criteria_results[0];
            $risk_level_conclusion = $calculate_boer_stability_criteria_results[1];
            $analysis_type = $calculate_boer_stability_criteria_results[2];
        } else {
            $conclusions = [];
            $analysis_type = [];
            $risk_level_conclusion = 0;
        }

        $asphaltenes_d_stability_analysis_results = new asphaltenes_d_stability_analysis_results;
        $asphaltenes_d_stability_analysis_results->asphaltenes_d_stability_analysis_id = $asphaltenes_d_stability_analysis->id;
        
        if (!empty($conclusions) && !empty($analysis_type)) {
            $asphaltenes_d_stability_analysis_results->light_analysis_problem_level = $conclusions[1][0];
            $asphaltenes_d_stability_analysis_results->light_analysis_conclusion = $conclusions[1][1];

            $asphaltenes_d_stability_analysis_results->sara_analysis_problem_level = $conclusions[2][0];
            $asphaltenes_d_stability_analysis_results->sara_analysis_conclusion = $conclusions[2][1];
            $asphaltenes_d_stability_analysis_results->sara_analysis_probability = $conclusions[2][2];

            $asphaltenes_d_stability_analysis_results->colloidal_analysis_problem_level = $conclusions[0][0];
            $asphaltenes_d_stability_analysis_results->colloidal_analysis_conclusion = $conclusions[0][1];
            /* $asphaltenes_d_stability_analysis_results->colloidal_analysis_probability = $conclusions[0][2]; */

            $asphaltenes_d_stability_analysis_results->precipitation_risk_colloidal = $analysis_type[0];
            $asphaltenes_d_stability_analysis_results->precipitation_risk_light = $analysis_type[1];
            $asphaltenes_d_stability_analysis_results->precipitation_risk_sara = $analysis_type[2];
        }

        $asphaltenes_d_stability_analysis_results->precipitation_risk_fluid = $risk_level_conclusion; /* Risk level conclusion */

        $input_reservoir_initial_pressure = empty($input_reservoir_initial_pressure) ? 1 : $input_reservoir_initial_pressure;
        $input_bubble_pressure = empty($input_bubble_pressure) ? 1 : $input_bubble_pressure;
        $input_reservoir_density_at_t = empty($input_reservoir_density_at_t) ? 1 : $input_reservoir_density_at_t;
        $input_asphaltenes = empty($input_asphaltenes) ? 1 : $input_asphaltenes;
        $input_aromatics = empty($input_aromatics) ? 1 : $input_aromatics;
        $input_saturated = empty($input_saturated) ? 1 : $input_saturated;
        $input_resines = empty($input_resines) ? 1 : $input_resines;
        
        $deboer_analysis_chart_point = array(round($input_reservoir_density_at_t, 2), round($input_reservoir_initial_pressure - $input_bubble_pressure, 2));
        $asphaltenes_d_stability_analysis_results->boer_stability_analysis_chart_point = json_encode($deboer_analysis_chart_point);

        $colloidal_analysis_chart_point = array(round($input_aromatics + $input_resines, 2), round($input_saturated + $input_asphaltenes, 2));
        $asphaltenes_d_stability_analysis_results->colloidal_analysis_chart_point = json_encode($colloidal_analysis_chart_point);

        $stankiewicz_analysis_chart_point = array(round($input_asphaltenes / $input_resines, 2), round($input_saturated / $input_aromatics, 2));
        $asphaltenes_d_stability_analysis_results->stankiewicz_analysis_chart_point = json_encode($stankiewicz_analysis_chart_point);

        $asphaltenes_d_stability_analysis_results->save();

        /* dd([$conclusions, $total_probability, $total_type]);   */

        /*Escenario completo*/
        $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();
        $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $scenary->id)->first();
        $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->select('id')->first();

        if ($asphaltenes_d_stability_analysis and $asphaltenes_d_diagnosis and $asphaltenes_d_precipitated_analysis) {
            $scenary = escenario::find($scenary->id);
            $scenary->completo = 1;
            $scenary->save();
        } else {
            $scenary = escenario::find($scenary->id);
            $scenary->completo = 0;
            $scenary->save();
        }

        return redirect(route('asa.result',$scenaryId));
    }

    public function test()
    {

    }

    public function result($id_escenario)
    {
        $scenary = escenario::find($id_escenario);
        $scenaryId  = $id_escenario;

        /* Variables para barra de información */ 
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();

        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();

        $asphaltenes_d_stability_analysis = asphaltenes_d_stability_analysis::where(['scenario_id' => $scenary->id])->first();
        $asphaltenes_d_stability_analysis_results = asphaltenes_d_stability_analysis_results::where(['asphaltenes_d_stability_analysis_id' => $asphaltenes_d_stability_analysis->id])->first();

        return View::make('results_asphaltene_stability_analysis', compact(['asphaltenes_d_stability_analysis','pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'asphaltenes_d_stability_analysis_results']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        #Mostrar el modulo desde los resultados de los demas modulos de asfaltenos. Agregar o editar segun el caso.
        $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $id)->first();

        if ($asphaltenes_d_stability_analysis) {
            return \Redirect::route('asphalteneStabilityAnalysis.edit', $id);
        } else {
            return \Redirect::action('add_asphaltene_stability_analysis_controller@index', array('scenaryId' => $id));
        }
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
    public function duplicate($id,$duplicateFrom)
    {
        $_SESSION['scenary_id_dup'] = $id;
        return $this->edit($duplicateFrom);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asphaltenes_d_stability_analysis = asphaltenes_d_stability_analysis::where('scenario_id', '=', $id)->first();

        #Variables para barra de informacion
        $scenary = DB::table('escenarios')->where('id', $id)->first();
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $scenaryId = $id;
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();

        $advisor = $scenary->enable_advisor;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        return View::make('edit_asphaltene_stability_analysis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_stability_analysis', 'duplicateFrom']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(asphaltene_stability_analysis_request $request, $id)
    {
        $button_wr = isset($_POST['button_wr']);
        if (isset($_SESSION['scenary_id_dup'])) {
            $asphaltenes_d_stability_analysis = new asphaltenes_d_stability_analysis();
        } else {
            $asphaltenes_d_stability_analysis = asphaltenes_d_stability_analysis::where("id", "=", $id)->first();
        }

        #Variables para barra de información
        $scenaryId = $request->id_scenary;
        $scenary = DB::table('escenarios')->where('id', $scenaryId)->first();
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();

        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        $asphaltenes_d_stability_analysis->scenario_id = $scenaryId;
        $asphaltenes_d_stability_analysis->saturated = $request->input('saturated');
        $asphaltenes_d_stability_analysis->aromatics = $request->input('aromatics');
        $asphaltenes_d_stability_analysis->resines = $request->input('resines');
        $asphaltenes_d_stability_analysis->asphaltenes = $request->input('asphaltenes');
        $asphaltenes_d_stability_analysis->reservoir_initial_pressure = $request->input('reservoir_initial_pressure');
        $asphaltenes_d_stability_analysis->bubble_pressure = $request->input('bubble_pressure');
        $asphaltenes_d_stability_analysis->density_at_reservoir_temperature = $request->input('density_at_reservoir_temperature');
        $asphaltenes_d_stability_analysis->current_reservoir_pressure = $request->input('current_reservoir_pressure');
        $asphaltenes_d_stability_analysis->api_gravity = $request->input('api_gravity');
        $asphaltenes_d_stability_analysis->status_wr = $button_wr;
        $asphaltenes_d_stability_analysis->save();

        #Tabla componentes
        asphaltenes_d_stability_analysis_components::where('asphaltenes_d_stability_analysis_id', $asphaltenes_d_stability_analysis->id)->delete();
        $components_table = json_decode($request->input("value_components_table"));

        $components_data = [];
        $molar_fraction_data = [];

        $components_table = is_null($components_table) ? [] : $components_table;
        foreach ($components_table as $value) {
            $asphaltenes_d_stability_analysis_components = new asphaltenes_d_stability_analysis_components;
            $asphaltenes_d_stability_analysis_components->asphaltenes_d_stability_analysis_id = $asphaltenes_d_stability_analysis->id;
            $asphaltenes_d_stability_analysis_components->component = str_replace(",", ".", $value[0]);
            $asphaltenes_d_stability_analysis_components->mole_fraction = str_replace(",", ".", $value[1]);
            $asphaltenes_d_stability_analysis_components->save();

            array_push($components_data, $asphaltenes_d_stability_analysis_components->component);
            array_push($molar_fraction_data, $asphaltenes_d_stability_analysis_components->mole_fraction * 100);
        }


        #Módulo análisis de estabilidad
        #Componentes [Composición, %moles, Zi]
        $components = [$components_data, $molar_fraction_data];

        #Análisis SARA
        $input_saturated = $asphaltenes_d_stability_analysis->saturated;
        $input_aromatics = $asphaltenes_d_stability_analysis->aromatics;
        $input_resines = $asphaltenes_d_stability_analysis->resines;
        $input_asphaltenes = $asphaltenes_d_stability_analysis->asphaltenes;
        $input_sara = [$input_saturated, $input_aromatics, $input_resines, $input_asphaltenes];

        #Saturación
        $input_field = $campo->nombre;
        $input_reservoir_initial_pressure = $asphaltenes_d_stability_analysis->reservoir_initial_pressure;
        $input_bubble_pressure = $asphaltenes_d_stability_analysis->bubble_pressure;
        $input_reservoir_density_at_t = $asphaltenes_d_stability_analysis->density_at_reservoir_temperature;
        $input_reservoir_current_pressure = $asphaltenes_d_stability_analysis->current_reservoir_pressure;
        $input_api_gravity = $asphaltenes_d_stability_analysis->api_gravity;
        $input_saturation = [$input_field, $input_reservoir_initial_pressure, $input_bubble_pressure, $input_reservoir_density_at_t, $input_reservoir_current_pressure, $input_api_gravity];

        if (!$button_wr) {
            $calculate_boer_stability_criteria_results = $this->calculate_boer_stability_criteria($components, $input_sara, $input_saturation);
            $conclusions = $calculate_boer_stability_criteria_results[0];
            $risk_level_conclusion = $calculate_boer_stability_criteria_results[1];
            $analysis_type = $calculate_boer_stability_criteria_results[2];
        } else {
            $conclusions = [];
            $analysis_type = [];
            $risk_level_conclusion = 0;
        }

        $asphaltenes_d_stability_analysis_results = new asphaltenes_d_stability_analysis_results;
        $asphaltenes_d_stability_analysis_results->asphaltenes_d_stability_analysis_id = $asphaltenes_d_stability_analysis->id;
        if (!empty($conclusions) && !empty($analysis_type)) {

            $asphaltenes_d_stability_analysis_results->light_analysis_problem_level = $conclusions[1][0];
            $asphaltenes_d_stability_analysis_results->light_analysis_conclusion = $conclusions[1][1];

            $asphaltenes_d_stability_analysis_results->sara_analysis_problem_level = $conclusions[2][0];
            $asphaltenes_d_stability_analysis_results->sara_analysis_conclusion = $conclusions[2][1];
            $asphaltenes_d_stability_analysis_results->sara_analysis_probability = $conclusions[2][2];

            $asphaltenes_d_stability_analysis_results->colloidal_analysis_problem_level = $conclusions[0][0];
            $asphaltenes_d_stability_analysis_results->colloidal_analysis_conclusion = $conclusions[0][1];
            /* $asphaltenes_d_stability_analysis_results->colloidal_analysis_probability = $conclusions[0][2]; */

            $asphaltenes_d_stability_analysis_results->precipitation_risk_colloidal = $analysis_type[0];
            $asphaltenes_d_stability_analysis_results->precipitation_risk_light = $analysis_type[1];
            $asphaltenes_d_stability_analysis_results->precipitation_risk_sara = $analysis_type[2];
        }

        $input_reservoir_initial_pressure = empty($input_reservoir_initial_pressure) ? 1 : $input_reservoir_initial_pressure;
        $input_bubble_pressure = empty($input_bubble_pressure) ? 1 : $input_bubble_pressure;
        $input_reservoir_density_at_t = empty($input_reservoir_density_at_t) ? 1 : $input_reservoir_density_at_t;
        $input_asphaltenes = empty($input_asphaltenes) ? 1 : $input_asphaltenes;
        $input_aromatics = empty($input_aromatics) ? 1 : $input_aromatics;
        $input_saturated = empty($input_saturated) ? 1 : $input_saturated;
        $input_resines = empty($input_resines) ? 1 : $input_resines;

        $asphaltenes_d_stability_analysis_results->precipitation_risk_fluid = $risk_level_conclusion; #Risk level conclusion

        $deboer_analysis_chart_point = array(round($input_reservoir_density_at_t, 2), round($input_reservoir_initial_pressure - $input_bubble_pressure, 2));
        $asphaltenes_d_stability_analysis_results->boer_stability_analysis_chart_point = json_encode($deboer_analysis_chart_point);

        $colloidal_analysis_chart_point = array(round($input_aromatics + $input_resines, 2), round($input_saturated + $input_asphaltenes, 2));
        $asphaltenes_d_stability_analysis_results->colloidal_analysis_chart_point = json_encode($colloidal_analysis_chart_point);

        $stankiewicz_analysis_chart_point = array(round($input_asphaltenes / $input_resines, 2), round($input_saturated / $input_aromatics, 2));
        $asphaltenes_d_stability_analysis_results->stankiewicz_analysis_chart_point = json_encode($stankiewicz_analysis_chart_point);

        $asphaltenes_d_stability_analysis_results->save();

        /*Escenario completo*/
        $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();
        $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $scenary->id)->first();
        $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->select('id')->first();

        if ($asphaltenes_d_stability_analysis and $asphaltenes_d_diagnosis and $asphaltenes_d_precipitated_analysis) {
            $scenary = escenario::find($scenary->id);
            $scenary->completo = 1;
            $scenary->save();
        } else {
            $scenary = escenario::find($scenary->id);
            $scenary->completo = 0;
            $scenary->save();
        }

        unset($_SESSION['scenary_id_dup']);

        // return \Redirect(url('asphalteneStabilityAnalysis?scenaryId='.$scenaryId));
        return redirect(route('asa.result',$scenaryId));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Función: Evalúa los datos del análisis SARA.
     * Retorno: Define la conclusión inicial para la verificación del sara y reenvía al método xxx
     */

    public function verificate_sara($saturated_value, $aromatic_value, $resins_value, $asphaltenes_value)
    {
        $sara_verification_conclusion = "";
        if ($saturated_value == 0 and $resins_value == 0 and $aromatic_value == 0) {
            $sara_verification_conclusion = "There's no SARA analysis data available.";
        } else if ($saturated_value + $aromatic_value + $resins_value + $asphaltenes_value < 98) {
            $sara_verification_conclusion = "The summ of the SARA analysis values is less than 100";
        }
        return $sara_verification_conclusion;
    }

    /**
     * Función: Calcula el tipo y probabilidad del índice de inestabilidad coloidal.
     * Retorno: conclusión de análisis de inestabilidad coloidal, diagnóstico, tipo y probabilidad de índice de inestabilidad coloidal.
     */
    public function calculate_colloidal_instability_index($saturated_value, $asphaltenes_value, $resins_value, $aromatic_value)
    {
        $colloidal_instability_index = ($saturated_value + $asphaltenes_value) / ($resins_value + $aromatic_value); #cii
        $rta = $asphaltenes_value / $resins_value; #Relación asfaltenos-resinas
        if ($asphaltenes_value == 0) {
            $colloidal_instability_index = 0;
            $cii_conclusion = "CII = 0, there's no asphaltene presence";
            $cii_diagnosis_conclusion = "<b>Diagnosis:</b> none";
            $colloidal_type = 0;
            $colloidal_probability = 0;
        } else {
            if ($colloidal_instability_index < 0.7 and $rta > 2.5) {
                $cii_conclusion = "<b>Problems:</b> none";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are stable";
                $colloidal_type = 0;
                $colloidal_probability = 0;
            } else if ($colloidal_instability_index < 0.7 and $rta < 2.5 and $rta > 1.5) {
                $cii_conclusion = "<b>Problems:</b> none";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are stable";
                $colloidal_type = 0;
                $colloidal_probability = 0;
            } else if ($colloidal_instability_index < 0.7 and $rta < 1.5) {
                $cii_conclusion = "<b>Problems:</b> low";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> there's a <b>low</b> proabability of precipited asphaltenes";
                $colloidal_type = 1;
                $colloidal_probability = 15;
            } else if ($colloidal_instability_index > 0.9 and $rta > 2.5) {
                $cii_conclusion = "<b>Problems:</b> medium";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are unstable, there's a <b>50%</b> probability of having stable agregates";
                $colloidal_type = 3;
                $colloidal_probability = 50;
            } else if ($colloidal_instability_index > 0.9 and $rta < 2.5 and $rta > 1.5) {
                $cii_conclusion = "<b>Problems:</b> high";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are unstable, there's a <b>70%</b> probability of having asphaltene agregates";
                $colloidal_type = 4;
                $colloidal_probability = 70;
            } else if ($colloidal_instability_index > 0.9 and $rta < 1.5) {
                $cii_conclusion = "<b>Problems:</b> high";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are unstable, there's a <b>95%</b> probability of having asphaltene agregates";
                $colloidal_type = 5;
                $colloidal_probability = 95;
            } else if ($colloidal_instability_index < 0.9 and $colloidal_instability_index > 0.7 and $rta > 2.5) {
                $cii_conclusion = "<b>Problems:</b> medium";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are pseudo-stable, there's a <b>15%</b> probability of having stable agregates";
                $colloidal_type = 1;
                $colloidal_probability = 15;
            } else if ($colloidal_instability_index < 0.9 and $colloidal_instability_index > 0.7 and $rta < 2.5 and $rta > 1.5) {
                $cii_conclusion = "<b>Problems:</b> medium";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are pseudo-stable, there's a <b>35%</b> probability of having stable agregates";
                $colloidal_type = 3;
                $colloidal_probability = 35;
            } else if ($colloidal_instability_index < 0.9 and $colloidal_instability_index > 0.7 and $rta < 1.5) {
                $cii_conclusion = "<b>Problems:</b> medium";
                $cii_diagnosis_conclusion = "<b>Diagnosis:</b> asphaltenes are pseudo-stable, there's a <b>45%</b> probability of having stable agregates";
                $colloidal_type = 3;
                $colloidal_probability = 45;
            }
        }

        return [$cii_conclusion, $cii_diagnosis_conclusion, $colloidal_type, $colloidal_probability];
    }

    /**
     * Función: Calcula el criterio de estabilidad de boer. Recibe de manera tabular los componentes y sus fracciones molares, el input de análisis sara y los datos de saturación.
     * Retorno: Conclusión y diagnóstico para el análisis sara, índice de inestabilidad coloidal y contenido de livianos y asfaltenos.
     */
    public function calculate_boer_stability_criteria($components, $input_sara, $input_saturation)
    {
        #$components = component - %moles - Zi
        $c_components_sum = 0;
        $plus_component_sum = 0;
        $co2_component_sum = 0;
        $nc5_component_sum = 0;
        $nc6_component_sum = 0;

        #Input SARA
        $saturated_value = $input_sara[0];
        $aromatic_value = $input_sara[1];
        $resins_value = $input_sara[2];
        $asphaltenes_value = $input_sara[3];

        #Input Saturation
        $field_name = $input_saturation[0];
        $reservoir_initial_pressure_value = $input_saturation[1];
        $bubble_pressure_value = $input_saturation[2];
        $reservoir_density_at_t_value = $input_saturation[3];
        $current_reservoir_pressure = $input_saturation[4];
        $api_gravity = $input_saturation[5];

        $calculate_colloidal_instability_index_results = $this->calculate_colloidal_instability_index($saturated_value, $asphaltenes_value, $resins_value, $aromatic_value);
        $cii_conclusion = $calculate_colloidal_instability_index_results[0];
        $cii_diagnosis_conclusion = $calculate_colloidal_instability_index_results[1];
        $colloidal_type = $calculate_colloidal_instability_index_results[2];
        $colloidal_probability = $calculate_colloidal_instability_index_results[3];

        #Conclusiones
        $boer_stability_conclusion_1 = "";
        $boer_stability_conclusion_2 = "";
        $boer_stability_conclusion_3 = "";

        for ($i = 0; $i < count($components[0]); $i++) {
            if ($components[0][$i] == "C1" or $components[0][$i] == "C2" or $components[0][$i] == "C3") {
                $c_components_sum += $components[1][$i];
            } else if ($components[0][$i] == "Plus +") {
                $plus_component_sum += $components[1][$i];
            } else if ($components[0][$i] == "CO2") {
                $co2_component_sum += $components[1][$i];
            } else if ($components[0][$i] == "NC5") {
                $nc5_component_sum += $components[1][$i];
            } else if ($components[0][$i] == "NC6") {
                $nc6_component_sum += $components[1][$i];
            }
        }


        #*** Revisar flujo
        if ($c_components_sum <= 0 and $plus_component_sum == 0 and $nc5_component_sum == 0 and $nc6_component_sum == 0) {
            $boer_stability_conclusion_1 = "There's no presence of light and heavy hidrocarbons";
        }

        if ($plus_component_sum <= 0 and $co2_component_sum == 0 and $nc5_component_sum == 0 and $nc6_component_sum == 0) {
            $boer_stability_conclusion_1 = "There's no presence of heavy hidrocarbons and CO2";
        }

        if ($nc5_component_sum > 10) {
            $c_components_sum = $nc5_component_sum;
        }

        if ($nc6_component_sum > 10) {
            $c_components_sum = $nc6_component_sum;
        }

        #Contenido de livianos
        if ($c_components_sum <= 8) {
            $light_hidrocarbons_content = 1;
        } else if ($c_components_sum > 8 and $c_components_sum < 25) {
            $light_hidrocarbons_content = 2;
        } else if ($c_components_sum >= 25) {
            $light_hidrocarbons_content = 3;
        }

        #Contenido de fracción pesada
        if ($plus_component_sum >= 75) {
            $heavy_fraction_content = 1;
        } else if ($plus_component_sum > 45 and $plus_component_sum < 75) {
            $heavy_fraction_content = 2;
        } else if ($plus_component_sum <= 45) {
            $heavy_fraction_content = 3;
        }

        #Contenido saturados
        if ($saturated_value <= 62) {
            $saturated_content = 1;
        }
        if ($saturated_value < 75 and $saturated_value > 62) {
            $saturated_content = 2;
        }
        if ($saturated_value >= 75) {
            $saturated_content = 3;
        }

        #Contenido aromáticos
        if ($aromatic_value >= 26) {
            $aromatic_content = 1;
        }
        if ($aromatic_value < 26) {
            $aromatic_content = 2;
        }

        #Contenido resinas
        if ($resins_value >= 11) {
            $resins_content = 1;
        }
        if ($resins_value < 11) {
            $resins_content = 2;
        }

        #Contenido asfaltenos
        if ($asphaltenes_value >= 3) {
            $asphaltene_content = 1;
        }
        if ($asphaltenes_value < 3 and $asphaltenes_value > 1) {
            $asphaltene_content = 2;
        }
        if ($asphaltenes_value <= 1) {
            $asphaltene_content = 3;
        }

        if ($plus_component_sum > 0.01) {
            if ($light_hidrocarbons_content == 1 and $heavy_fraction_content == 1) {
                $boer_stability_conclusion_1 = "<b>Problems:</b> non or very low";
                $boer_stability_conclusion_2 = "<b>Very low</b> light components saturation. The asphaltenes doesn't precipitates";
                $light_hidrocarbon_type = 0;
                $light_hidrocarbon_probability = 0;
            } else if ($light_hidrocarbons_content == 1 and $heavy_fraction_content == 2) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> low";
                    $boer_stability_conclusion_2 = "<b>Very low</b> light components saturation. There's a <b>15%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 1;
                    $light_hidrocarbon_probability = 10;
                } else {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> low";
                    $boer_stability_conclusion_2 = "<b>Very low</b> light components saturation. There's a <b>7.5%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 1;
                    $light_hidrocarbon_probability = 7;
                }

            } else if ($light_hidrocarbons_content == 1 and $heavy_fraction_content == 3) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> medium";
                    $boer_stability_conclusion_2 = "<b>Low</b> light components saturation. There's a <b>45%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 3;
                    $light_hidrocarbon_probability = 45;
                } else {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> medium";
                    $boer_stability_conclusion_2 = "<b>Low</b> light components saturation. There's a <b>30%</b> probability or less for asphaltenes precipitation";
                    $light_hidrocarbon_type = 3;
                    $light_hidrocarbon_probability = 30;
                }

            } else if ($light_hidrocarbons_content == 2 and $heavy_fraction_content == 1) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> low";
                    $boer_stability_conclusion_2 = "The presence of CO2 makes asphaltenes unstable. There's a <b>10%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 1;
                    $light_hidrocarbon_probability = 10;
                } else {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> none or very low";
                    $boer_stability_conclusion_2 = "<b>Very Low</b> light components saturation. The asphaltenes doesn't precipitates";
                    $light_hidrocarbon_type = 0;
                    $light_hidrocarbon_probability = 0;
                }

            } else if ($light_hidrocarbons_content == 2 and $heavy_fraction_content == 2) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> medium";
                    $boer_stability_conclusion_2 = "<b>Low</b> light components saturation. There's a <b>45%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 3;
                    $light_hidrocarbon_probability = 35;
                } else {//Falta low en $boer_stability_conclusion_2??
                    $boer_stability_conclusion_1 = "<b>Problems:</b> medium";
                    $boer_stability_conclusion_2 = "light components saturation. There's a <b>30%</b> probability or less for asphaltenes precipitation";
                    $light_hidrocarbon_type = 3;
                    $light_hidrocarbon_probability = 30;
                }
            } else if ($light_hidrocarbons_content == 2 and $heavy_fraction_content == 3) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> high";
                    $boer_stability_conclusion_2 = "<b>Low</b> light components saturation. There's a <b>65%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 4;
                    $light_hidrocarbon_probability = 65;
                } else {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> medium";
                    $boer_stability_conclusion_2 = "<b>Low</b> light components saturation. There's a <b>45%</b> probability or less for asphaltenes precipitation";
                    $light_hidrocarbon_type = 3;
                    $light_hidrocarbon_probability = 30;
                }
            } else if ($light_hidrocarbons_content == 3 and $heavy_fraction_content == 1) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> low";
                    $boer_stability_conclusion_2 = "<b>Very low</b> light components saturation. Asphaltenes doesn't precipitates";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 0;
                    $light_hidrocarbon_probability = 0;
                } else {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> none or very low";
                    $boer_stability_conclusion_2 = "<b>Very low</b> light components saturation. Asphaltenes doesn't precipitates";
                    $light_hidrocarbon_type = 0;
                    $light_hidrocarbon_probability = 0;
                }

            } else if ($light_hidrocarbons_content == 3 and $heavy_fraction_content == 2) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> high";
                    $boer_stability_conclusion_2 = "<b>Medium</b> light components saturation. There's a <b>60%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 4;
                    $light_hidrocarbon_probability = 60;
                } else {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> medium";
                    $boer_stability_conclusion_2 = "<b>Medium</b> light components saturation. There's a <b>45%</b> probability or less for asphaltenes precipitation";
                    $light_hidrocarbon_type = 3;
                    $light_hidrocarbon_probability = 45;
                }
            } else if ($light_hidrocarbons_content == 3 and $heavy_fraction_content == 3) {
                if ($co2_component_sum > 5) {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> severe";
                    $boer_stability_conclusion_2 = "<b>High</b> light components saturation. There's a <b>85%</b> probability or less for asphaltenes precipitation";
                    $boer_stability_conclusion_3 = "There's a CO2 concentration higher than <b>5%</b>, an additional precipitation effect is expected";
                    $light_hidrocarbon_type = 7;
                    $light_hidrocarbon_probability = 85;
                } else {
                    $boer_stability_conclusion_1 = "<b>Problems:</b> severe";
                    $boer_stability_conclusion_2 = "<b>High</b> light components saturation. There's a <b>75%</b> probability or less for asphaltenes precipitation";
                    $light_hidrocarbon_type = 6;
                    $light_hidrocarbon_probability = 75;
                }

            }
        } else {
            $boer_stability_conclusion_1 = "<b>Problems:</b> none";
            $boer_stability_conclusion_2 = "There's no asphaltene presence in this heavy fraction";
            $light_hidrocarbon_type = 0;
            $light_hidrocarbon_probability = 0;
        }

        #Análisis SARA
        if ($asphaltenes_value > 0) {
            if ($saturated_content == 1 and $asphaltene_content == 1 and $resins_content == 1 and $aromatic_content == 1)#1'
            {
                $sara_conclusion_1 = "<b>Problems:</b> none";
                $sara_conclusion_2 = "<b>Low</b> content of saturated + <b>High</b> content of resins + <b>High</b> content of aromatics";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>2%</b> or less";
                $sara_type = 0;
                $sara_probability = 0;
            } else if ($saturated_content == 1 and $asphaltene_content == 1 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> none";
                $sara_conclusion_2 = "Aromatics/Resins rate is higher than <b>2.5</b>. There's a <b>very high</b> asphaltene agregates stability";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>2%</b> or less";
                $sara_type = 0;
                $sara_probability = 0;
            } else if ($saturated_content == 1 and $asphaltene_content == 1 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> low low";
                $sara_conclusion_2 = "Aromatics/Resins rate is higher than <b>2.5</b>. There's a <b>high</b> asphaltene molecules stability";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>10%</b> or less";
                $sara_type = 10;
                $sara_probability = 1;
            } else if ($saturated_content == 1 and $asphaltene_content == 1 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium low";
                $sara_conclusion_2 = "Aromatics/Resins rate increments the instability";
                $sara_type = 20;
                $sara_probability = 1;
            } else if ($saturated_content == 1 and $asphaltene_content == 2 and $resins_content == 1 and $aromatic_content == 1) #2'
            {
                $sara_conclusion_1 = "<b>Problems:</b> none";
                $sara_conclusion_2 = "<b>High</b> content of aromatics and resins, <b>low</b> content of saturated";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>2%</b> or less";
                $sara_type = 0;
                $sara_probability = 0;
            } else if ($saturated_content == 1 and $asphaltene_content == 2 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> none";
                $sara_conclusion_2 = "<b>High</b> content of aromatics and resins, <b>low</b> content of saturated";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>2%</b> or less";
                $sara_type = 0;
                $sara_probability = 0;
            } else if ($saturated_content == 1 and $asphaltene_content == 2 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> low low";
                $sara_conclusion_2 = "Aromatics/Resins rate highter than <b>2.5</b>";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>10%</b> or less";
                $sara_type = 1;
                $sara_probability = 10;
            } else if ($saturated_content == 1 and $asphaltene_content == 2 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium low";
                $sara_conclusion_2 = "Aromatics/Resins rate highter than <b>2.5</b>";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>25%</b> or less";
                $sara_type = 2;
                $sara_probability = 25;
            } else if ($saturated_content == 1 and $asphaltene_content == 3 and $resins_content == 1 and $aromatic_content == 1) #3'
            {
                $sara_conclusion_1 = "<b>Problems:</b> none";
                $sara_conclusion_2 = "<b>High</b> content of aromatics and resins, <b>low</b> content of saturated";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>2%</b> or less";
                $sara_type = 0;
                $sara_probability = 0;
            } else if ($saturated_content == 1 and $asphaltene_content == 3 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> none";
                $sara_conclusion_2 = "<b>High</b> content of aromatics and resins, <b>low</b> content of saturated";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>2%</b> or less";
                $sara_type = 0;
                $sara_probability = 0;
            } else if ($saturated_content == 1 and $asphaltene_content == 3 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> none";
                $sara_conclusion_2 = "Aromatics/Resins rate highter than <b>2.5</b>";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>10%</b> or less";
                $sara_type = 1;
                $sara_probability = 10;
            } else if ($saturated_content == 1 and $asphaltene_content == 3 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> low low";
                $sara_conclusion_2 = "Unlikely, check the content of saturated";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>10%</b> or less";
                $sara_type = 1;
                $sara_probability = 10;
            } else if ($saturated_content == 2 and $asphaltene_content == 1 and $resins_content == 1 and $aromatic_content == 1) #4'
            {
                $sara_conclusion_1 = "<b>Problems:</b> low low";
                $sara_conclusion_2 = "Some asphaltenes could precipitate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>5%</b> or less";
                $sara_type = 1;
                $sara_probability = 5;
            } else if ($saturated_content == 2 and $asphaltene_content == 1 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> low low";
                $sara_conclusion_2 = "Some asphaltenes could precipitate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>5%</b> or less";
                $sara_type = 1;
                $sara_probability = 5;
            } else if ($saturated_content == 2 and $asphaltene_content == 1 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium low";
                $sara_conclusion_2 = "<b>High</b> content of asphaltenes, <b>low</b> aromatics/resins rate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>35%</b> or less";
                $sara_type = 3;
                $sara_probability = 35;
            } else if ($saturated_content == 2 and $asphaltene_content == 1 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "<b>High</b> content of asphaltenes, <b>low</b> content of aromatics";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>60%</b> or less";
                $sara_type = 4;
                $sara_probability = 60;
            } else if ($saturated_content == 2 and $asphaltene_content == 2 and $resins_content == 1 and $aromatic_content == 1) #5'
            {
                $sara_conclusion_1 = "<b>Problems:</b> low low";
                $sara_conclusion_2 = "Some asphaltenes could precipitate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>5%</b> or less";
                $sara_type = 1;
                $sara_probability = 5;
            } else if ($saturated_content == 2 and $asphaltene_content == 2 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> low high";
                $sara_conclusion_2 = "<b>High</b> content of asphaltenes, <b>low</b> aromatics/resins rate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>17%</b> or less";
                $sara_type = 1;
                $sara_probability = 17;
            } else if ($saturated_content == 2 and $asphaltene_content == 2 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium low";
                $sara_conclusion_2 = "<b>High</b> content of asphaltenes, <b>low</b> aromatics/resins rate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>35%</b> or less";
                $sara_type = 3;
                $sara_probability = 35;
            } else if ($saturated_content == 2 and $asphaltene_content == 2 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "<b>High</b> content of asphaltenes, <b>low</b> content of aromatics";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>50%</b> or less";
                $sara_type = 4;
                $sara_probability = 60;
            } else if ($saturated_content == 2 and $asphaltene_content == 3 and $resins_content == 1 and $aromatic_content == 1) #6'
            {
                $sara_conclusion_1 = "<b>Problems:</b> low high";
                $sara_conclusion_2 = "Some asphaltenes could precipitate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>17%</b> or less";
                $sara_type = 1;
                $sara_probability = 17;
            } else if ($saturated_content == 2 and $asphaltene_content == 3 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> medium low";
                $sara_conclusion_2 = "Some asphaltenes could precipitate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>35%</b> or less";
                $sara_type = 2;
                $sara_probability = 35;
            } else if ($saturated_content == 2 and $asphaltene_content == 3 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium low";
                $sara_conclusion_2 = "<b>High</b> content of asphaltenes, <b>low</b> aromatics/resins rate";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>35%</b> or less";
                $sara_type = 2;
                $sara_probability = 35;
            } else if ($saturated_content == 2 and $asphaltene_content == 3 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "<b>High</b> content of asphaltenes, <b>low</b> content of aromatics";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>50%</b> or less";
                $sara_type = 3;
                $sara_probability = 50;
            } else if ($saturated_content == 3 and $asphaltene_content == 1 and $resins_content == 1 and $aromatic_content == 1) #7'
            {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "Unlikely, check the SARA content";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>50%</b> or less";
                $sara_type = 3;
                $sara_probability = 50;
            } else if ($saturated_content == 3 and $asphaltene_content == 1 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "Unlikely, check the SARA content";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>75%</b> or less";
                $sara_type = 4;
                $sara_probability = 75;
            } else if ($saturated_content == 3 and $asphaltene_content == 1 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> high high";
                $sara_conclusion_2 = "<b>High</b> content of saturated, there's a <b>high</b> probability of precipitated asphaltenes";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>80%</b> or less";
                $sara_type = 4;
                $sara_probability = 80;
            } else if ($saturated_content == 3 and $asphaltene_content == 1 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> severe";
                $sara_conclusion_2 = "<b>High</b> content of saturated + <b>low</b> aromatics/resines rate, there's a <b>high</b> probability of precipitated asphaltenes";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>95%</b> or less";
                $sara_type = 7;
                $sara_probability = 95;
            } else if ($saturated_content == 3 and $asphaltene_content == 2 and $resins_content == 1 and $aromatic_content == 1) #8'
            {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "Unlikely, check the SARA content";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>35%</b> or less";
                $sara_type = 3;
                $sara_probability = 35;
            } else if ($saturated_content == 3 and $asphaltene_content == 2 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "Unlikely, check the SARA content";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>50%</b> or less";
                $sara_type = 3;
                $sara_probability = 50;
            } else if ($saturated_content == 3 and $asphaltene_content == 2 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> high high";
                $sara_conclusion_2 = "<b>High</b> content of saturated, there's a <b>high</b> probability of precipitated asphaltenes";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>75%</b> or less";
                $sara_type = 5;
                $sara_probability = 75;
            } else if ($saturated_content == 3 and $asphaltene_content == 2 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> severe";
                $sara_conclusion_2 = "<b>High</b> content of saturated + <b>low</b> aromatics/resines rate, there's a <b>high</b> probability of precipitated asphaltenes";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>90%</b> or less";
                $sara_type = 6;
                $sara_probability = 90;
            } else if ($saturated_content == 3 and $asphaltene_content == 3 and $resins_content == 1 and $aromatic_content == 1) #9'
            {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "Unlikely, check the SARA content";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>35%</b> or less";
                $sara_type = 3;
                $sara_probability = 35;
            } else if ($saturated_content == 3 and $asphaltene_content == 3 and $resins_content == 2 and $aromatic_content == 1) {
                $sara_conclusion_1 = "<b>Problems:</b> medium high";
                $sara_conclusion_2 = "Unlikely, check the SARA content";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>35%</b> or less";
                $sara_type = 3;
                $sara_probability = 35;
            } else if ($saturated_content == 3 and $asphaltene_content == 3 and $resins_content == 1 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> high high";
                $sara_conclusion_2 = "<b>High</b> content of saturated, there's a <b>high</b> probability of precipitated asphaltenes";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>75%</b> or less";
                $sara_type = 5;
                $sara_probability = 75;
            } else if ($saturated_content == 3 and $asphaltene_content == 3 and $resins_content == 2 and $aromatic_content == 2) {
                $sara_conclusion_1 = "<b>Problems:</b> severe";
                $sara_conclusion_2 = "<b>High</b> content of saturated + <b>low</b> aromatics/resines rate, there's a <b>high</b> probability of precipitated asphaltenes";
                $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>95%</b> or less";
                $sara_type = 7;
                $sara_probability = 95;
            }
        } else {
            $sara_conclusion_1 = "<b>Problems:</b> none";
            $sara_conclusion_2 = "There's no presence of asphaltenes";
            $sara_conclusion_3 = "The probability of precipitated asphaltenes is <b>2%</b> or less";
            $sara_type = 0;
            $sara_probability = 0;
        }

        #Por revisar
        $FR4 = 0;
        if ($asphaltenes_value > 0 and $saturated_value > 45) {
            if ($saturated_value < 62 and $asphaltenes_value < 1 and $resins_value > 11 and $aromatic_value > 26) #1'
            {
                $FR4 = 5;
            }
            if ($saturated_value < 62 and $asphaltenes_value < 1 and $resins_value < 11 and $aromatic_value > 26) {
                $FR4 = 5;
            }
            if ($saturated_value > 62 and $asphaltenes_value < 1 and $resins_value > 11 and $aromatic_value < 26) {
                $FR4 = 17;
            }
            if ($saturated_value < 62 and $asphaltenes_value > 1 and $asphaltenes_value < 3 and $resins_value > 11 and $aromatic_value > 26) #2'
            {
                $FR4 = 17;
            }
            if ($saturated_value < 62 and $asphaltenes_value > 1 and $asphaltenes_value < 3 and $resins_value < 11 and $aromatic_value < 26) {
                $FR4 = 5;
            }
            if ($saturated_value < 62 and $asphaltenes_value > 1 and $asphaltenes_value < 3 and $resins_value > 11 and $aromatic_value < 26) {
                $FR4 = 17;
            }
            if ($saturated_value < 62 and $asphaltenes_value > 3 and $resins_value > 11 and $aromatic_value > 26) #3'
            {
                $FR4 = 2;
            }
            if ($saturated_value < 62 and $asphaltenes_value > 3 and $resins_value < 11 and $aromatic_value > 26) {
                $FR4 = 17;
            }
            if ($saturated_value > 62 and $asphaltenes_value > 3 and $resins_value > 11 and $aromatic_value < 26) {
                $FR4 = 17;
            }
            if ($saturated_value > 62 and $asphaltenes_value > 3 and $resins_value < 11 and $aromatic_value < 26) {
                $FR4 = 35;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 1 and $resins_value > 11 and $aromatic_value > 26) #4'
            {
                $FR4 = 17;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 1 and $resins_value < 11 and $aromatic_value > 26) {
                $FR4 = 35;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 1 and $resins_value > 11 and $aromatic_value < 26) {
                $FR4 = 35;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 1 and $resins_value > 11 and $aromatic_value < 26) {
                $FR4 = 65;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 3 and $asphaltenes_value > 1 and $resins_value > 11 and $aromatic_value > 26) #5'
            {
                $FR4 = 35;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 3 and $asphaltenes_value > 1 and $resins_value < 11 and $aromatic_value > 26) {
                $FR4 = 50;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 3 and $asphaltenes_value > 1 and $resins_value > 11 and $aromatic_value < 26) {
                $FR4 = 50;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value < 3 and $asphaltenes_value > 1 and $resins_value < 11 and $aromatic_value < 26) {
                $FR4 = 65;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value > 3 and $resins_value > 11 and $aromatic_value > 26) #6'
            {
                $FR4 = 35;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value > 3 and $resins_value < 11 and $aromatic_value > 26) {
                $FR4 = 50;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value > 3 and $resins_value > 11 and $aromatic_value < 26) {
                $FR4 = 65;
            }
            if ($saturated_value > 62 and $saturated_value < 75 and $asphaltenes_value > 3 and $resins_value < 11 and $aromatic_value < 26) {
                $FR4 = 80;
            }
            if ($saturated_value > 75 and $asphaltenes_value < 1 and $resins_value > 11 and $aromatic_value < 26) #7'
            {
                $FR4 = 95;
            }
            if ($saturated_value > 75 and $asphaltenes_value < 1 and $resins_value < 11 and $aromatic_value < 26) {
                $FR4 = 95;
            }
            if ($saturated_value > 75 and $asphaltenes_value < 3 and $asphaltenes_value > 1 and $resins_value > 11 and $aromatic_value < 26) #8'
            {
                $FR4 = 95;
            }
            if ($saturated_value > 75 and $asphaltenes_value < 3 and $asphaltenes_value > 1 and $resins_value < 11 and $aromatic_value < 26) {
                $FR4 = 95;
            }
            if ($saturated_value > 75 and $asphaltenes_value > 3 and $resins_value > 11 and $aromatic_value < 26) #9'
            {
                $FR4 = 95;
            }
            if ($saturated_value > 75 and $asphaltenes_value > 1 and $resins_value < 11 and $aromatic_value < 26) {
                $FR4 = 95;
            }
        }


        $total_probability = ((0.99 * $colloidal_probability) + (0.98 * $light_hidrocarbon_probability) + (0.99 * $sara_probability)) / 3;
        $total_type = round(($colloidal_type + $light_hidrocarbon_type + $sara_type) / 3, 2);

        $colloidal_conclusions = [$cii_conclusion, $cii_diagnosis_conclusion]; #Extraer de función anterior
        $light_hidrocarbons_conclusions = [$boer_stability_conclusion_1, $boer_stability_conclusion_2, $boer_stability_conclusion_3];
        $sara_conclusions = [$sara_conclusion_1, $sara_conclusion_2, $sara_conclusion_3];
        $conclusions = [$colloidal_conclusions, $light_hidrocarbons_conclusions, $sara_conclusions];

        $analysis_types = ["Precipitation risk according to the colloidal analysis: " . $colloidal_type, "Precipitation risk according to the presence of light hydrocarbons in fluid: " . $light_hidrocarbon_type, "Precipitation risk according to the presence of asphaltenes in the SARA analysis: " . $sara_type]; #Risks levels.

        $risk_level_conclusion = "The risk level by fluid precipitation is <b>" . $total_type . "</b>, the risk probability is <b>" . $total_probability . "%</b>.";

        return [$conclusions, $risk_level_conclusion, $analysis_types];
    }
}
