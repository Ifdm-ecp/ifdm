<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\cuenca;
use View;
use App\asphaltenes_d_precipitated_analysis;
use App\asphaltenes_d_precipitated_analysis_binary_coefficients;
Use App\asphaltenes_d_precipitated_analysis_experimental_onset_pressures;
use App\asphaltenes_d_precipitated_analysis_components_data;
use App\asphaltenes_d_precipitated_analysis_temperatures;
use App\asphaltenes_d_precipitated_analysis_onset_results;
use App\asphaltenes_d_precipitated_analysis_saturation_results;
use App\asphaltenes_d_precipitated_analysis_solid_a_results;
use App\asphaltenes_d_precipitated_analysis_solid_s_results;
use App\asphaltenes_d_precipitated_analysis_solid_wat_results;
use App\escenario;
use App\Http\Requests\precipitated_asphaltene_analysis_request;
use \SplFixedArray;
use App\error_log;


class add_precipitated_asphaltenes_analysis_controller extends Controller
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

            #Variables para barra de informacion
            $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
            $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
            $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
            $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();

            $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();

            $advisor = $scenary->enable_advisor;

            $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();

            return View::make('add_precipitated_asphaltenes_analysis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_stability_analysis']));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(precipitated_asphaltene_analysis_request $request)
    {
        /* Variables para barra de informacion */
        $scenaryId = $request->input('scenaryId');
        $scenary = escenario::find($scenaryId);
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();

        /* Arreglos para módulo de cálculo - Tablas */
        /* Componentes */
        $component_data = [];
        $zi_data = [];
        $mw_data = [];
        $pc_data = [];
        $tc_data = [];
        $w_data = [];
        $shift_data = [];
        $sg_data = [];
        $tb_data = [];
        $vc_data = [];

        /* Temperatura - Presión burbuja */
        $temperature_data = [];
        $bubble_pressure_data = [];

        $advisor = $scenary->enable_advisor;

        $scenary->estado = 1;
        $scenary->asphaltene_type = "Precipitated asphaltene analysis";
        $scenary->save();

        $asphaltenes_d_stability_analysis_id = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->select('id')->first();

        $asphaltenes_d_precipitated_analysis = new asphaltenes_d_precipitated_analysis;

        $asphaltenes_d_precipitated_analysis->status_wr = $request->only_s == "save" ? 1 : 0;

        $asphaltenes_d_precipitated_analysis->scenario_id = $scenary->id;
        $asphaltenes_d_precipitated_analysis->plus_fraction_molecular_weight = $request->plus_fraction_molecular_weight !== "" ? $request->plus_fraction_molecular_weight : null;
        $asphaltenes_d_precipitated_analysis->plus_fraction_specific_gravity = $request->plus_fraction_specific_gravity !== "" ? $request->plus_fraction_specific_gravity : null;
        $asphaltenes_d_precipitated_analysis->plus_fraction_boiling_temperature = $request->plus_fraction_boiling_temperature !== "" ? $request->plus_fraction_boiling_temperature : null;
        $asphaltenes_d_precipitated_analysis->correlation = $request->correlation !== "" ? $request->correlation : null;
        $asphaltenes_d_precipitated_analysis->critical_temperature = $request->critical_temperature !== "" ? $request->critical_temperature : null;
        $asphaltenes_d_precipitated_analysis->critical_pressure = $request->critical_pressure !== "" ? $request->critical_pressure : null;
        $asphaltenes_d_precipitated_analysis->density_at_reservoir_pressure = $request->density_at_reservoir_pressure !== "" ? $request->density_at_reservoir_pressure : null;
        $asphaltenes_d_precipitated_analysis->density_at_bubble_pressure = $request->density_at_bubble_pressure !== "" ? $request->density_at_bubble_pressure : null;
        $asphaltenes_d_precipitated_analysis->density_at_atmospheric_pressure = $request->density_at_atmospheric_pressure !== "" ? $request->density_at_atmospheric_pressure : null;
        $asphaltenes_d_precipitated_analysis->reservoir_temperature = $request->reservoir_temperature !== "" ? $request->reservoir_temperature : null;
        $asphaltenes_d_precipitated_analysis->initial_reservoir_pressure = $request->initial_reservoir_pressure !== "" ? $request->initial_reservoir_pressure : null;
        $asphaltenes_d_precipitated_analysis->current_reservoir_pressure = $request->current_reservoir_pressure !== "" ? $request->current_reservoir_pressure : null;
        $asphaltenes_d_precipitated_analysis->fluid_api_gravity = $request->fluid_api_gravity !== "" ? $request->fluid_api_gravity : null;

        $asphaltenes_d_precipitated_analysis->initial_temperature = 400;
        $asphaltenes_d_precipitated_analysis->number_of_temperatures = $request->number_of_temperatures !== "" ? $request->number_of_temperatures : null;
        $asphaltenes_d_precipitated_analysis->temperature_delta = $request->temperature_delta !== "" ? $request->temperature_delta : null;

        $asphaltenes_d_precipitated_analysis->asphaltene_particle_diameter = $request->asphaltene_particle_diameter !== "" ? $request->asphaltene_particle_diameter : null;
        $asphaltenes_d_precipitated_analysis->asphaltene_molecular_weight = $request->asphaltene_molecular_weight !== "" ? $request->asphaltene_molecular_weight : null;
        $asphaltenes_d_precipitated_analysis->asphaltene_apparent_density = $request->asphaltene_apparent_density !== "" ? $request->asphaltene_apparent_density : null;

        $asphaltenes_d_precipitated_analysis->saturate = $request->saturate !== "" ? $request->saturate : null;
        $asphaltenes_d_precipitated_analysis->aromatic = $request->aromatic !== "" ? $request->aromatic : null;
        $asphaltenes_d_precipitated_analysis->resine = $request->resine !== "" ? $request->resine : null;
        $asphaltenes_d_precipitated_analysis->asphaltene = $request->asphaltene !== "" ? $request->asphaltene : null;

        $asphaltenes_d_precipitated_analysis->include_elemental_asphaltene_analysis = ($request->input('elemental_data_selector') === 'on') ? true : false;

        if ($request->input('elemental_data_selector') === 'on') {
            $asphaltenes_d_precipitated_analysis->hydrogen_carbon_ratio = $request->input('hydrogen_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->oxygen_carbon_ratio = $request->input('oxygen_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->nitrogen_carbon_ratio = $request->input('nitrogen_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->sulphure_carbon_ratio = $request->input('sulphure_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->fa_aromaticity = $request->input('fa_aromaticity');
            $asphaltenes_d_precipitated_analysis->vc_molar_volume = $request->input('vc_molar_volume');
        } else {
            $asphaltenes_d_precipitated_analysis->hydrogen_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->oxygen_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->nitrogen_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->sulphure_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->fa_aromaticity = null;
            $asphaltenes_d_precipitated_analysis->vc_molar_volume = null;
        }

        $asphaltenes_d_precipitated_analysis->save();

        /* Guardar tabla de componentes */
        $components_table = json_decode($request->input("value_components_table"));
        $components_table = is_null($components_table) ? [] : $components_table;
        foreach ($components_table as $value) {
            $asphaltenes_d_precipitated_analysis_components_data = new asphaltenes_d_precipitated_analysis_components_data;
            $asphaltenes_d_precipitated_analysis_components_data->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
            $asphaltenes_d_precipitated_analysis_components_data->component = str_replace(",", ".", $value[0]);
            $asphaltenes_d_precipitated_analysis_components_data->zi = str_replace(",", ".", $value[1]);
            $asphaltenes_d_precipitated_analysis_components_data->mw = str_replace(",", ".", $value[2]);
            $asphaltenes_d_precipitated_analysis_components_data->pc = str_replace(",", ".", $value[3]);
            $asphaltenes_d_precipitated_analysis_components_data->tc = str_replace(",", ".", $value[4]);
            $asphaltenes_d_precipitated_analysis_components_data->w = str_replace(",", ".", $value[5]);
            $asphaltenes_d_precipitated_analysis_components_data->shift = str_replace(",", ".", $value[6]);
            $asphaltenes_d_precipitated_analysis_components_data->sg = str_replace(",", ".", $value[7]);
            $asphaltenes_d_precipitated_analysis_components_data->tb = str_replace(",", ".", $value[8]);
            $asphaltenes_d_precipitated_analysis_components_data->vc = str_replace(",", ".", $value[9]);
            $asphaltenes_d_precipitated_analysis_components_data->save();

            array_push($component_data, $asphaltenes_d_precipitated_analysis_components_data->component);
            array_push($zi_data, $asphaltenes_d_precipitated_analysis_components_data->zi);
            array_push($mw_data, $asphaltenes_d_precipitated_analysis_components_data->mw);
            array_push($pc_data, $asphaltenes_d_precipitated_analysis_components_data->pc);
            array_push($tc_data, $asphaltenes_d_precipitated_analysis_components_data->tc);
            array_push($w_data, $asphaltenes_d_precipitated_analysis_components_data->w);
            array_push($shift_data, $asphaltenes_d_precipitated_analysis_components_data->shift);
            array_push($sg_data, $asphaltenes_d_precipitated_analysis_components_data->sg);
            array_push($tb_data, $asphaltenes_d_precipitated_analysis_components_data->tb);
            array_push($vc_data, $asphaltenes_d_precipitated_analysis_components_data->vc);
        }

        /* Datos de la tabla de componentes para el módulo de cálculos */
        $components_complete_data = [$component_data, $zi_data, $mw_data, $pc_data, $tc_data, $w_data, $shift_data, $sg_data, $tb_data, $vc_data];

        /* Guardar tabla bubble point */
        $value_bubble_point_table = json_decode($request->input("value_bubble_point_table"));
        $value_bubble_point_table = is_null($value_bubble_point_table) ? [] : $value_bubble_point_table;
        foreach ($value_bubble_point_table as $value) {
            $asphaltenes_d_precipitated_analysis_temperatures = new asphaltenes_d_precipitated_analysis_temperatures;
            $asphaltenes_d_precipitated_analysis_temperatures->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
            $asphaltenes_d_precipitated_analysis_temperatures->temperature = str_replace(",", ".", $value[0]);
            $asphaltenes_d_precipitated_analysis_temperatures->bubble_pressure = str_replace(",", ".", $value[1]);
            $asphaltenes_d_precipitated_analysis_temperatures->save();

            array_push($temperature_data, $asphaltenes_d_precipitated_analysis_temperatures->temperature);
            array_push($bubble_pressure_data, $asphaltenes_d_precipitated_analysis_temperatures->bubble_pressure);
        }

        $value_binary_interaction_coefficients_table = json_decode($request->input("value_binary_interaction_coefficients_table"));
        $value_binary_interaction_coefficients_table = is_null($value_binary_interaction_coefficients_table) ? [] : $value_binary_interaction_coefficients_table;
        $components = [];
        foreach ($value_binary_interaction_coefficients_table as $value) {
            if ($value[0] != null) {
                array_push($components, $value[0]);
            }
        }

        $binary_interaction_coefficients = [];
        foreach ($value_binary_interaction_coefficients_table as $value) {
            if ($value[0] != null) {
                $binary_interaction_coefficients_table = [];

                $binary_interaction_coefficients_table["components"] = $value[0];
                $aux = 1;

                foreach ($components as $component) {
                    $binary_interaction_coefficients_table[$component] = $value[$aux];
                    $aux++;
                }
                array_push($binary_interaction_coefficients, $binary_interaction_coefficients_table);
            }
        }

        $asphaltenes_d_precipitated_analysis_binary_coefficients = new asphaltenes_d_precipitated_analysis_binary_coefficients;
        $asphaltenes_d_precipitated_analysis_binary_coefficients->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
        $asphaltenes_d_precipitated_analysis_binary_coefficients->coefficient = json_encode($binary_interaction_coefficients);
        $asphaltenes_d_precipitated_analysis_binary_coefficients->save();

        $binary_interaction_coefficients_data = $value_binary_interaction_coefficients_table;

        try {
            $asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
            if (!$asphaltenes_d_precipitated_analysis->status_wr) {
                $precipitated_asphaltene_analysis_results = $this->run_precipitated_asphaltenes_analysis($components_complete_data, $binary_interaction_coefficients_data, $temperature_data, $bubble_pressure_data, $asphaltenes_d_precipitated_analysis["attributes"]);
                
                if ($precipitated_asphaltene_analysis_results[0] == false) {
                    $asphaltenes_d_precipitated_analysis->status_wr = true;
                    $asphaltenes_d_precipitated_analysis->save();
                    $scenary = escenario::find($scenary->id);
                    $scenary->completo = 0;
                    $scenary->save();
                    return redirect('/asphaltenesPrecipitated/'.$scenaryId.'/edit')->withErrors($precipitated_asphaltene_analysis_results[1]);
                }
                
                /* dd($precipitated_asphaltene_analysis_results); */
                /* Guardando resultados */
                $saturation_results = $precipitated_asphaltene_analysis_results[0]; /* Temperature - Bubble pressure */
                $onset_pressure_results = $precipitated_asphaltene_analysis_results[1][0];
                $solid_wat_results = $precipitated_asphaltene_analysis_results[1][1];
                $solid_s_results = $precipitated_asphaltene_analysis_results[1][2];
                $solid_a_results = $precipitated_asphaltene_analysis_results[1][3];
                $temperature_vector = $precipitated_asphaltene_analysis_results[2];

                /* Saturation results */
                for ($i = 1; $i <= count($saturation_results[0]); $i++) {
                    $saturation_results_database = new asphaltenes_d_precipitated_analysis_saturation_results();
                    $saturation_results_database->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
                    $saturation_results_database->temperature = $saturation_results[0][$i];
                    $saturation_results_database->bubble_pressure = $saturation_results[1][$i];
                    $saturation_results_database->save();
                }

                /* Onset results */
                for ($i = 0; $i < count($onset_pressure_results[0]); $i++) {
                    $onset_pressure_results_database = new asphaltenes_d_precipitated_analysis_onset_results();
                    $onset_pressure_results_database->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
                    $onset_pressure_results_database->temperature = $onset_pressure_results[0][$i];
                    $onset_pressure_results_database->was = $onset_pressure_results[1][$i];
                    $onset_pressure_results_database->a = $onset_pressure_results[2][$i];
                    $onset_pressure_results_database->onset_pressure = $onset_pressure_results[3][$i];
                    $onset_pressure_results_database->bubble_pressure = $onset_pressure_results[4][$i];
                    $onset_pressure_results_database->corrected_onset_pressure = $onset_pressure_results[5][$i];
                    $onset_pressure_results_database->save();
                }

                /* solid_a_results */
                for ($i = 1; $i <= count($solid_a_results); $i++) {
                    $temperature_aux = $temperature_vector[$i];
                    $indexes = array_keys($solid_a_results[$i][0]);
                    $initial_index = $indexes[0];
                    $final_index = end($indexes);
                    for ($j = $initial_index; $j <= $final_index; $j++) {
                        $solid_a_results_database = new asphaltenes_d_precipitated_analysis_solid_a_results();
                        $solid_a_results_database->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
                        $solid_a_results_database->pressure = $solid_a_results[$i][0][$j];
                        $solid_a_results_database->a = $solid_a_results[$i][1][$j];
                        $solid_a_results_database->temperature = $temperature_aux;
                        $solid_a_results_database->save();
                    }
                }

                /* Escenario completo */
                $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();
                $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $scenary->id)->first();
                $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->first();

                if ($asphaltenes_d_stability_analysis && $asphaltenes_d_diagnosis && $asphaltenes_d_precipitated_analysis && !$asphaltenes_d_stability_analysis->status_wr && !$asphaltenes_d_diagnosis->status_wr && !$asphaltenes_d_precipitated_analysis->status_wr) {
                    $scenary->completo = 1;
                    $scenary->save();
                } else {
                    $scenary->completo = 0;
                    $scenary->save();
                }
            }

            return redirect(route('asp.result', $scenary->id));
        } catch (Exception $e) {
            $error_log = new error_log;
            $error_log->scenario_id = $scenaryId;
            $error_log->message = $e->getMessage();
            $error_log->line = $e->getLine();
            $error_log->args = json_encode($e->getTrace()[0]["args"][4]);
            $error_log->file = $e->getFile();
            $error_log->save();

            return $this->edit($error_log->scenario_id);
        }
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

        $asphaltenes_d_precipitated_analysis = asphaltenes_d_precipitated_analysis::where('scenario_id', $scenary->id)->first();
        $asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;

        /* Agregar experimental onset pressures */
        $experimental_onset_pressure_table = DB::table('asphaltenes_d_precipitated_analysis_experimental_onset_pressures')->select('temperature', 'onset_pressure')->where('asphaltenes_d_precipitated_analysis_experimental_onset_pressures.asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->orderBy('asphaltenes_d_precipitated_analysis_experimental_onset_pressures.temperature', 'asc')->get();
        dd($experimental_onset_pressure_table);

        $advisor = $scenary->enable_advisor;

        return View::make('results_precipitated_asphaltenes_analysis', compact(['asphaltenes_d_precipitated_analysis', 'pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_precipitated_analysis_id']));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        #Mostrar el modulo desde los resultados de los demas modulos de asfaltenos. Agregar o editar segun el caso.
        $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $id)->first();

        if ($asphaltenes_d_precipitated_analysis) {
            return \Redirect::route('asphaltenesPrecipitated.edit', $id);
        } else {
            return \Redirect::action('add_precipitated_asphaltenes_analysis_controller@index', array('scenaryId' => $id));
        }
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */
    public function duplicate($id, $duplicateFrom)
    {
        $_SESSION['scenary_id_dup'] = $id;
        return $this->edit($duplicateFrom);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $asphaltenes_d_precipitated_analysis = asphaltenes_d_precipitated_analysis::where('scenario_id', '=', $id)->first();

        #Variables para barra de informacion
        $scenary = escenario::find($id);
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $scenaryId = $id;
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();

        $advisor = $scenary->enable_advisor;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        return View::make('edit_precipitated_asphaltenes_analysis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_precipitated_analysis', 'duplicateFrom']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(precipitated_asphaltene_analysis_request $request, $id)
    {
        if (!empty($request->scenary_id)) {
            $asphaltenes_d_precipitated_analysis = new asphaltenes_d_precipitated_analysis();
            $scenaryId = $request->scenary_id;
        } else {
            $asphaltenes_d_precipitated_analysis = asphaltenes_d_precipitated_analysis::find($id);
            $scenaryId = $asphaltenes_d_precipitated_analysis->scenario_id;
        }

        /* Variables para barra de informacion */
        $scenary = escenario::find($scenaryId);
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();

        /* Arreglos para módulo de cálculo - Tablas */
        /* Experimental Onset Pressures */
        $temperature_experimental_data = [];
        $onset_pressure_experimental_data = [];

        /* Componentes */
        $component_data = [];
        $zi_data = [];
        $mw_data = [];
        $pc_data = [];
        $tc_data = [];
        $w_data = [];
        $shift_data = [];
        $sg_data = [];
        $tb_data = [];
        $vc_data = [];

        /* Temperatura - Presión burbuja */
        $temperature_data = [];
        $bubble_pressure_data = [];

        $advisor = $scenary->enable_advisor;
        $scenary->estado = 1;
        $scenary->save();

        $asphaltenes_d_precipitated_analysis->scenario_id = $scenary->id;
        $asphaltenes_d_precipitated_analysis->status_wr = $request->only_s == "save" ? 1 : 0;

        $asphaltenes_d_precipitated_analysis->plus_fraction_molecular_weight = $request->plus_fraction_molecular_weight !== "" ? $request->plus_fraction_molecular_weight : null;
        $asphaltenes_d_precipitated_analysis->plus_fraction_specific_gravity = $request->plus_fraction_specific_gravity !== "" ? $request->plus_fraction_specific_gravity : null;
        $asphaltenes_d_precipitated_analysis->plus_fraction_boiling_temperature = $request->plus_fraction_boiling_temperature !== "" ? $request->plus_fraction_boiling_temperature : null;
        $asphaltenes_d_precipitated_analysis->correlation = $request->correlation !== "" ? $request->correlation : null;
        $asphaltenes_d_precipitated_analysis->critical_temperature = $request->critical_temperature !== "" ? $request->critical_temperature : null;
        $asphaltenes_d_precipitated_analysis->critical_pressure = $request->critical_pressure !== "" ? $request->critical_pressure : null;
        $asphaltenes_d_precipitated_analysis->density_at_reservoir_pressure = $request->density_at_reservoir_pressure !== "" ? $request->density_at_reservoir_pressure : null;
        $asphaltenes_d_precipitated_analysis->density_at_bubble_pressure = $request->density_at_bubble_pressure !== "" ? $request->density_at_bubble_pressure : null;
        $asphaltenes_d_precipitated_analysis->density_at_atmospheric_pressure = $request->density_at_atmospheric_pressure !== "" ? $request->density_at_atmospheric_pressure : null;
        $asphaltenes_d_precipitated_analysis->reservoir_temperature = $request->reservoir_temperature !== "" ? $request->reservoir_temperature : null;
        $asphaltenes_d_precipitated_analysis->initial_reservoir_pressure = $request->initial_reservoir_pressure !== "" ? $request->initial_reservoir_pressure : null;
        $asphaltenes_d_precipitated_analysis->current_reservoir_pressure = $request->current_reservoir_pressure !== "" ? $request->current_reservoir_pressure : null;
        $asphaltenes_d_precipitated_analysis->fluid_api_gravity = $request->fluid_api_gravity !== "" ? $request->fluid_api_gravity : null;

        $asphaltenes_d_precipitated_analysis->initial_temperature = 400;
        $asphaltenes_d_precipitated_analysis->number_of_temperatures = $request->number_of_temperatures !== "" ? $request->number_of_temperatures : null;
        $asphaltenes_d_precipitated_analysis->temperature_delta = $request->temperature_delta !== "" ? $request->temperature_delta : null;

        $asphaltenes_d_precipitated_analysis->asphaltene_particle_diameter = $request->asphaltene_particle_diameter !== "" ? $request->asphaltene_particle_diameter : null;
        $asphaltenes_d_precipitated_analysis->asphaltene_molecular_weight = $request->asphaltene_molecular_weight !== "" ? $request->asphaltene_molecular_weight : null;
        $asphaltenes_d_precipitated_analysis->asphaltene_apparent_density = $request->asphaltene_apparent_density !== "" ? $request->asphaltene_apparent_density : null;

        $asphaltenes_d_precipitated_analysis->saturate = $request->saturate !== "" ? $request->saturate : null;
        $asphaltenes_d_precipitated_analysis->aromatic = $request->aromatic !== "" ? $request->aromatic : null;
        $asphaltenes_d_precipitated_analysis->resine = $request->resine !== "" ? $request->resine : null;
        $asphaltenes_d_precipitated_analysis->asphaltene = $request->asphaltene !== "" ? $request->asphaltene : null;

        $asphaltenes_d_precipitated_analysis->include_elemental_asphaltene_analysis = ($request->input('elemental_data_selector') === 'on') ? true : false;

        if ($request->input('elemental_data_selector') === 'on') {
            $asphaltenes_d_precipitated_analysis->hydrogen_carbon_ratio = $request->input('hydrogen_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->oxygen_carbon_ratio = $request->input('oxygen_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->nitrogen_carbon_ratio = $request->input('nitrogen_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->sulphure_carbon_ratio = $request->input('sulphure_carbon_ratio');
            $asphaltenes_d_precipitated_analysis->fa_aromaticity = $request->input('fa_aromaticity');
            $asphaltenes_d_precipitated_analysis->vc_molar_volume = $request->input('vc_molar_volume');
        } else {
            $asphaltenes_d_precipitated_analysis->hydrogen_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->oxygen_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->nitrogen_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->sulphure_carbon_ratio = null;
            $asphaltenes_d_precipitated_analysis->fa_aromaticity = null;
            $asphaltenes_d_precipitated_analysis->vc_molar_volume = null;
        }

        $asphaltenes_d_precipitated_analysis->save();

        $id = $asphaltenes_d_precipitated_analysis->id;

        /* Guardar tabla de datos onset experimentales */
        $experimental_table = json_decode($request->input("value_asphaltenes_experimental_onset_pressures_table"));
        $experimental_table = is_null($experimental_table) ? [] : $experimental_table;

        asphaltenes_d_precipitated_analysis_experimental_onset_pressures::where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->delete();

        foreach ($experimental_table as $value) {
            $asphaltenes_d_precipitated_analysis_experimental_data = new asphaltenes_d_precipitated_analysis_experimental_onset_pressures;
            $asphaltenes_d_precipitated_analysis_experimental_data->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
            $asphaltenes_d_precipitated_analysis_experimental_data->temperature = str_replace(",", ".", $value[0]);
            $asphaltenes_d_precipitated_analysis_experimental_data->onset_pressure = str_replace(",", ".", $value[1]);
            $asphaltenes_d_precipitated_analysis_experimental_data->save();

            array_push($temperature_experimental_data, $asphaltenes_d_precipitated_analysis_experimental_data->temperature);
            array_push($onset_pressure_experimental_data, $asphaltenes_d_precipitated_analysis_experimental_data->onset_pressure);
        }

        /* Guardar tabla de componentes */
        $components_table = json_decode($request->input("value_components_table"));
        $components_table = is_null($components_table) ? [] : $components_table;

        asphaltenes_d_precipitated_analysis_components_data::where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->delete();

        foreach ($components_table as $value) {
            $asphaltenes_d_precipitated_analysis_components_data = new asphaltenes_d_precipitated_analysis_components_data;
            $asphaltenes_d_precipitated_analysis_components_data->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
            $asphaltenes_d_precipitated_analysis_components_data->component = str_replace(",", ".", $value[0]);
            $asphaltenes_d_precipitated_analysis_components_data->zi = str_replace(",", ".", $value[1]);
            $asphaltenes_d_precipitated_analysis_components_data->mw = str_replace(",", ".", $value[2]);
            $asphaltenes_d_precipitated_analysis_components_data->pc = str_replace(",", ".", $value[3]);
            $asphaltenes_d_precipitated_analysis_components_data->tc = str_replace(",", ".", $value[4]);
            $asphaltenes_d_precipitated_analysis_components_data->w = str_replace(",", ".", $value[5]);
            $asphaltenes_d_precipitated_analysis_components_data->shift = str_replace(",", ".", $value[6]);
            $asphaltenes_d_precipitated_analysis_components_data->sg = str_replace(",", ".", $value[7]);
            $asphaltenes_d_precipitated_analysis_components_data->tb = str_replace(",", ".", $value[8]);
            $asphaltenes_d_precipitated_analysis_components_data->vc = str_replace(",", ".", $value[9]);
            $asphaltenes_d_precipitated_analysis_components_data->save();

            array_push($component_data, $asphaltenes_d_precipitated_analysis_components_data->component);
            array_push($zi_data, $asphaltenes_d_precipitated_analysis_components_data->zi);
            array_push($mw_data, $asphaltenes_d_precipitated_analysis_components_data->mw);
            array_push($pc_data, $asphaltenes_d_precipitated_analysis_components_data->pc);
            array_push($tc_data, $asphaltenes_d_precipitated_analysis_components_data->tc);
            array_push($w_data, $asphaltenes_d_precipitated_analysis_components_data->w);
            array_push($shift_data, $asphaltenes_d_precipitated_analysis_components_data->shift);
            array_push($sg_data, $asphaltenes_d_precipitated_analysis_components_data->sg);
            array_push($tb_data, $asphaltenes_d_precipitated_analysis_components_data->tb);
            array_push($vc_data, $asphaltenes_d_precipitated_analysis_components_data->vc);
        }

        /* Datos de la tabla de componentes para el módulo de cálculos */
        $components_complete_data = [$component_data, $zi_data, $mw_data, $pc_data, $tc_data, $w_data, $shift_data, $sg_data, $tb_data, $vc_data];

        /* Guardar tabla bubble point */
        $value_bubble_point_table = json_decode($request->input("value_bubble_point_table"));
        $value_bubble_point_table = is_null($value_bubble_point_table) ? [] : $value_bubble_point_table;

        asphaltenes_d_precipitated_analysis_temperatures::where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->delete();
        foreach ($value_bubble_point_table as $value) {
            $asphaltenes_d_precipitated_analysis_temperatures = new asphaltenes_d_precipitated_analysis_temperatures;
            $asphaltenes_d_precipitated_analysis_temperatures->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
            $asphaltenes_d_precipitated_analysis_temperatures->temperature = str_replace(",", ".", $value[0]);
            $asphaltenes_d_precipitated_analysis_temperatures->bubble_pressure = str_replace(",", ".", $value[1]);
            $asphaltenes_d_precipitated_analysis_temperatures->save();

            array_push($temperature_data, $asphaltenes_d_precipitated_analysis_temperatures->temperature);
            array_push($bubble_pressure_data, $asphaltenes_d_precipitated_analysis_temperatures->bubble_pressure);
        }

        $value_binary_interaction_coefficients_table = json_decode($request->input("value_binary_interaction_coefficients_table"));
        $value_binary_interaction_coefficients_table = is_null($value_binary_interaction_coefficients_table) ? [] : $value_binary_interaction_coefficients_table;
        $components = [];

        foreach ($value_binary_interaction_coefficients_table as $value) {
            if ($value[0] != null) {
                array_push($components, $value[0]);
            }
        }

        $binary_interaction_coefficients = [];
        foreach ($value_binary_interaction_coefficients_table as $value) {
            if ($value[0] != null) {
                $binary_interaction_coefficients_table = [];

                $binary_interaction_coefficients_table["components"] = $value[0];
                $aux = 1;

                foreach ($components as $component) {
                    $binary_interaction_coefficients_table[$component] = $value[$aux];
                    $aux++;
                }
                array_push($binary_interaction_coefficients, $binary_interaction_coefficients_table);
            }
        }

        $asphaltenes_d_precipitated_analysis_binary_coefficients = asphaltenes_d_precipitated_analysis_binary_coefficients::where("asphaltenes_d_precipitated_analysis_id", "=", $id)->first();
        if (!$asphaltenes_d_precipitated_analysis_binary_coefficients) {
            $asphaltenes_d_precipitated_analysis_binary_coefficients = new asphaltenes_d_precipitated_analysis_binary_coefficients();
        }

        $asphaltenes_d_precipitated_analysis_binary_coefficients->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
        $asphaltenes_d_precipitated_analysis_binary_coefficients->coefficient = json_encode($binary_interaction_coefficients);
        $asphaltenes_d_precipitated_analysis_binary_coefficients->save();

        $binary_interaction_coefficients_data = $value_binary_interaction_coefficients_table;

        try {
            $asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
            if (!$asphaltenes_d_precipitated_analysis->status_wr) {
                $precipitated_asphaltene_analysis_results = $this->run_precipitated_asphaltenes_analysis($components_complete_data, $binary_interaction_coefficients_data, $temperature_data, $bubble_pressure_data, $asphaltenes_d_precipitated_analysis["attributes"]);
                
                if ($precipitated_asphaltene_analysis_results[0] == false) {
                    $asphaltenes_d_precipitated_analysis->status_wr = true;
                    $asphaltenes_d_precipitated_analysis->save();
                    $scenary = escenario::find($scenary->id);
                    $scenary->completo = 0;
                    $scenary->save();
                    return Redirect::back()->withErrors($precipitated_asphaltene_analysis_results[1]);
                }
                
                /* dd($precipitated_asphaltene_analysis_results); */

                /* Borrando resultados viejos */
                asphaltenes_d_precipitated_analysis_saturation_results::where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->delete();
                asphaltenes_d_precipitated_analysis_onset_results::where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->delete();
                asphaltenes_d_precipitated_analysis_solid_a_results::where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->delete();

                /* Guardando resultados */
                $saturation_results = $precipitated_asphaltene_analysis_results[0]; /* Temperature - Bubble pressure */
                $onset_pressure_results = $precipitated_asphaltene_analysis_results[1][0];
                $solid_wat_results = $precipitated_asphaltene_analysis_results[1][1];
                $solid_s_results = $precipitated_asphaltene_analysis_results[1][2];
                $solid_a_results = $precipitated_asphaltene_analysis_results[1][3];
                $temperature_vector = $precipitated_asphaltene_analysis_results[2];

                /* Saturation results */
                for ($i = 1; $i <= count($saturation_results[0]); $i++) {
                    $saturation_results_database = new asphaltenes_d_precipitated_analysis_saturation_results();
                    $saturation_results_database->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
                    $saturation_results_database->temperature = $saturation_results[0][$i];
                    $saturation_results_database->bubble_pressure = $saturation_results[1][$i];
                    $saturation_results_database->save();
                }

                /* Onset results */
                for ($i = 0; $i < count($onset_pressure_results[0]); $i++) {
                    $onset_pressure_results_database = new asphaltenes_d_precipitated_analysis_onset_results();
                    $onset_pressure_results_database->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
                    $onset_pressure_results_database->temperature = $onset_pressure_results[0][$i];
                    $onset_pressure_results_database->was = $onset_pressure_results[1][$i];
                    $onset_pressure_results_database->a = $onset_pressure_results[2][$i];
                    $onset_pressure_results_database->onset_pressure = $onset_pressure_results[3][$i];
                    $onset_pressure_results_database->bubble_pressure = $onset_pressure_results[4][$i];
                    $onset_pressure_results_database->corrected_onset_pressure = $onset_pressure_results[5][$i];
                    $onset_pressure_results_database->save();
                }

                /* solid_a_results */
                /* dd(array_keys($solid_a_results[1][0])); */

                for ($i = 1; $i <= count($solid_a_results); $i++) {
                    $temperature_aux = $temperature_vector[$i];
                    $indexes = array_keys($solid_a_results[$i][0]);
                    $initial_index = $indexes[0];
                    $final_index = end($indexes);
                    for ($j = $initial_index; $j <= $final_index; $j++) {
                        $solid_a_results_database = new asphaltenes_d_precipitated_analysis_solid_a_results();
                        $solid_a_results_database->asphaltenes_d_precipitated_analysis_id = $asphaltenes_d_precipitated_analysis->id;
                        $solid_a_results_database->pressure = $solid_a_results[$i][0][$j];
                        $solid_a_results_database->a = $solid_a_results[$i][1][$j];
                        $solid_a_results_database->temperature = $temperature_aux;
                        $solid_a_results_database->save();
                    }
                }

                /* Escenario completo */
                $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();
                $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $scenary->id)->first();
                $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->first();

                if ($asphaltenes_d_stability_analysis && $asphaltenes_d_diagnosis && $asphaltenes_d_precipitated_analysis && !$asphaltenes_d_stability_analysis->status_wr && !$asphaltenes_d_diagnosis->status_wr && !$asphaltenes_d_precipitated_analysis->status_wr) {
                    $scenary->completo = 1;
                    $scenary->save();
                } else {
                    $scenary->completo = 0;
                    $scenary->save();
                }
            }

            unset($_SESSION['scenary_id_dup']);

            return redirect(route('asp.result', $scenary->id));
        } catch (Exception $e) {
            $error_log = new error_log;
            $error_log->scenario_id = $scenaryId;
            $error_log->message = $e->getMessage();
            $error_log->line = $e->getLine();
            $error_log->args = json_encode($e->getTrace()[0]["args"][4]);
            $error_log->file = $e->getFile();
            $error_log->save();

            return $this->edit($error_log->scenario_id);
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


    #Funciones módulo de cálculo - Pendientes por comentar
    function run_precipitated_asphaltenes_analysis($components_complete_data, $binary_interaction_coefficients_data, $temperature_data, $bubble_pressure_data, $scenario_data)
    {
        set_time_limit(600);

        #Hoja DATA
        $component_amount = count($components_complete_data[0]);
        $components = array_fill(1, $component_amount, 0);
        for ($i = 1; $i <= $component_amount; $i++) {
            $components[$i] = $components_complete_data[0][$i - 1];
        }

        #$moles = [1 => 0.266578777,0.504021431,17.44349519,4.118824352,5.971938088,1.090284343,4.022525091,1.824805702,2.320604722,3.782760212,4.922595401,5.216320061,4.264225953,3.785040496,3.269207469,37.2];
        $zi = $this->set_array($components_complete_data[1], 100);
        $mwi = $this->set_array($components_complete_data[2], 100);
        $pci = $this->set_array($components_complete_data[3], 100);
        $tci = $components_complete_data[4];
        for ($i = 0; $i < count($tci); $i++) {
            $tci[$i] = $tci[$i] + 460;
        }
        $tci = $this->set_array($tci, 100);
        $wi = $this->set_array($components_complete_data[5], 100);
        $si = $this->set_array($components_complete_data[6], 100);
        $sgi = $this->set_array($components_complete_data[7], 100);
        $tbi = $this->set_array($components_complete_data[8], 100);
        $vci = $this->set_array($components_complete_data[9], 100);

        $components_data = [$components, $zi, $mwi, $pci, $tci, $wi, $si, $sgi, $tbi, $vci];

        #Caracterización de la fracción pesada -- Revisar
        $mw = 396.93;
        $sg = 0.9256;
        $tb = 1000;
        $peso_muestra = 190.22;

        $n = count($components); #Número de componentes

        $r = 10.73146;
        $act_plus = 0;
        if ($components[count($components)] == "Plus +") {
            $act_plus = 1;
        }

        #Lectura coeficientes de interacción binaria 
        $cib = [];
        foreach ($binary_interaction_coefficients_data as $value) {
            array_push($cib, $this->set_array2(array_slice($value, 1, count($value) - 1), 100));
        }

        $cib = $this->set_array2($cib, 100);

        #Datos asfaltenos
        $n_max_a = floatval($scenario_data["asphaltene_particle_diameter"]);
        $rhoa = floatval($scenario_data["asphaltene_apparent_density"]);
        $mwa = floatval($scenario_data["asphaltene_molecular_weight"]);
        $saturated = floatval($scenario_data["saturate"]);
        $aromatics = floatval($scenario_data["aromatic"]);
        $resines = floatval($scenario_data["resine"]);
        $asphaltenes = floatval($scenario_data["asphaltene"]);

        #Información adicional
        $h_c_ratio = floatval($scenario_data["hydrogen_carbon_ratio"]);
        $o_c_ratio = floatval($scenario_data["oxygen_carbon_ratio"]);
        $n_c_ratio = floatval($scenario_data["nitrogen_carbon_ratio"]);
        $s_c_ratio = floatval($scenario_data["sulphure_carbon_ratio"]);
        $fa_aromaticity = floatval($scenario_data["fa_aromaticity"]);
        $molar_volume_vc = floatval($scenario_data["vc_molar_volume"]);
        $asphaltenes_data = [$h_c_ratio, $o_c_ratio, $n_c_ratio, $s_c_ratio, $fa_aromaticity, $molar_volume_vc];
        #Opción asfaltenos
        if ($scenario_data["include_elemental_asphaltene_analysis"]) {
            $asphaltenes_optional_data = 1;
        } else {
            $asphaltenes_optional_data = 0;
        }

        $sum_sara_data = $saturated + $aromatics + $resines + $asphaltenes;

        #Datos saturación
        $temperature = $temperature_data;

        #Número de datos (temperatura y presiones burbuja)
        $nsat = count($temperature);

        $temperature = $this->set_array($temperature, 100);

        $bubble_pressure = $bubble_pressure_data;
        $bubble_pressure = $this->set_array($bubble_pressure, 100);

        $critical_temperature = floatval($scenario_data["critical_temperature"]);
        $critical_pressure = floatval($scenario_data["critical_pressure"]);
        $reservoir_density = floatval($scenario_data["density_at_reservoir_pressure"]);
        $bubble_density = floatval($scenario_data["density_at_bubble_pressure"]);
        $density_14_7 = floatval($scenario_data["density_at_atmospheric_pressure"]);
        $reservoir_temperature = floatval($scenario_data["reservoir_temperature"]);
        $reservoir_pressure = floatval($scenario_data["current_reservoir_pressure"]);
        $api_gravity = floatval($scenario_data["fluid_api_gravity"]);

        #Call spline - Guardar en resultados - Envolvente
        $spline_results = $this->spline($nsat - 1, $temperature, $bubble_pressure);
        $tspline = $spline_results[0];
        $pspline = $spline_results[1];

        #Lectura hoja soluble - lectura y formación de vector de temperaturas
        $initial_temperature = floatval($scenario_data["initial_temperature"]); #[R] != 0
        $temperature_amount = floatval($scenario_data["number_of_temperatures"]); #1<temperature_amount<20
        $temperature_delta = floatval($scenario_data["temperature_delta"]); #0<temperature_delta<100

        $temp = array_fill(1, 20, 0);
        #Vector de temperaturas
        for ($i = 1; $i <= $temperature_amount; $i++) {
            $temp[$i] = $initial_temperature + ($i - 1) * $temperature_delta;
        }

        #dd(array($n, $zi, $reservoir_pressure, $reservoir_temperature, $mwi, $pci, $tci, $vci, $wi, $si, $sgi, $cib, $pspline, $tspline, $density_14_7, $bubble_density, $reservoir_density));
        $density_correction = $this->density_correction($n, $zi, $reservoir_pressure, $reservoir_temperature, $mwi, $pci, $tci, $vci, $wi, $si, $sgi, $cib, $pspline, $tspline, $density_14_7, $bubble_density, $reservoir_density);

        #Retorna resultados - onset, wat, s, a
        $stability_results = $this->stability($n, $zi, $mwi, $sgi, $pci, $tci, $vci, $wi, $si, $cib, $pspline, $tspline, $critical_temperature, $critical_pressure, $temperature_amount, $temp, $api_gravity, $density_correction, $mwa, $rhoa, $n_max_a, $asphaltenes, $resines, $saturated, $aromatics, $act_plus, $asphaltenes_optional_data, $asphaltenes_data, $mwi, $zi); #Se agrega mw_data(mwi) y zi para corrección de solubilidad

        if ($stability_results[0] == false) {
            return $stability_results;
        }

        $saturation_results = [array_filter($tspline), array_filter($pspline)];
        return array($saturation_results, $stability_results, $temp);
    }

    function set_array_string($original_array, $n)
    {
        $fixed_array = new SplFixedArray($n + 1);
        for ($i = 0; $i < count($original_array); $i++) {
            $fixed_array[$i + 1] = $original_array[$i];
        }
        return $fixed_array;
    }

    function set_array($original_array, $n)
    {
        $fixed_array = new SplFixedArray($n + 1);
        for ($i = 0; $i < count($original_array); $i++) {
            $fixed_array[$i + 1] = floatval($original_array[$i]);
        }
        return $fixed_array;
    }

    function set_array2($original_array, $n)
    {
        $fixed_array = new SplFixedArray($n + 1);
        for ($i = 0; $i < count($original_array); $i++) {
            $fixed_array[$i] = $original_array[$i];
        }
        return $fixed_array;
    }

    function interpolation($x, $n, $xt, $yt)
    {
        $y = 0;
        $aux_i = 0;
        if ($x < $xt[1]) {
            $extrapolation_result = $this->extrapolation($xt, $yt, 100, $x, $y);
            $y = $extrapolation_result[0];
        }
        if ($x > $xt[$n]) {
            $extrapolation_result = $this->extrapolation($xt, $yt, 100, $x, $y);
            $y = $extrapolation_result[0];
        }
        if ($x < $xt[$n]) {
            for ($i = 2; $i <= $n; $i++) {
                if (!($x >= $xt[$i])) {
                    $y = $yt[$i - 1] + ($x - $xt[$i - 1]) * ($yt[$i] - $yt[$i - 1]) / ($xt[$i] - $xt[$i - 1]);
                    return $y;
                }
                $aux_i = $i;
            }
        }
        if ($x == $xt[$aux_i]) {
            $y = $yt[$aux_i];
        }

        return $y;
    }

    function extrapolation($xa, $ya, $n, $x)
    {
        $n_max = 10;
        $c = array_fill(1, 10, 0);
        $d = array_fill(1, 10, 0);
        $ns = 1;
        $dif = abs($x - $xa[1]);

        if ($x > $xa[$n]) {
            $y = $ya[$n];
            for ($i = 1; $i <= $n_max; $i++) {
                $c[$i] = $ya[$n_max];
                $d[$i] = $ya[$n_max];
            }
        } else if ($x < $xa[$n]) {
            $y = $ya[1];
            for ($i = 1; $i <= $n_max; $i++) {
                $c[$i] = $ya[$i];
                $d[$i] = $ya[$i];
            }
        } else {
            for ($i = 1; $i <= $n_max; $i++) {
                $c[$i] = $ya[$i];
                $d[$i] = $ya[$i];
            }
        }

        $ns = $ns - 1;
        for ($m = 1; $m < $n_max - 1; $m++) {
            for ($i = 1; $i < $n_max - $m; $i++) {
                $ho = $xa[$i] - $x;
                $hp = $xa[$i + $m] - $x;
                $w = $c[$i + 1] - $d[$i];
                $den = $ho - $hp;
                if ($den == 0) {
                    #Error
                    return -555;
                }
                $den = $w / $den;
                $d[$i] = $hp * $den;
                $c[$i] = $ho * $den;
            }
            if ((2 * $ns) < ($n_max - $m)) {
                $dy = $c[$ns + 1];
            } else {
                $dy = $d[$ns];
                $ns = $ns - 1;
            }
            $y = $y + $dy;
        }

        return [$y, $dy];
    }

    function spline($n, $x_data, $y_data)
    {
        #$p2 = array_fill(0,100,null);
        $t_spline = array_fill(0, 100, null);
        $p_spline = array_fill(0, 100, null);
        $m = 100;

        $t_spline[1] = $x_data[1];
        $p_spline[1] = $y_data[1];
        $t_spline[100] = $x_data[$n];
        $p_spline[100] = $y_data[$n];

        $p2 = $this->cubic_spline($n, $x_data, $y_data);

        #Find the approximation of the function
        $h = ($x_data[$n + 1] - $x_data[1]) / $m; #Revisar
        $x = $x_data[1];
        for ($i = 1; $i <= $m - 1; $i++) {
            $x = $x + $h;
            $k = 1;
            $dx = $x - $x_data[1];
            while ($dx > 0) {
                $k++;
                $dx = $x - $x_data[$k];
            }
            $k--;
            $dx = $x_data[$k + 1] - $x_data[$k];
            $alpha = $p2[$k + 1] / (6 * $dx);
            $beta = -$p2[$k] / (6 * $dx);
            $gamma = $y_data[$k + 1] / $dx - $dx * $p2[$k + 1] / 6;
            $eta = $dx * $p2[$k] / 6 - $y_data[$k] / $dx;
            $f = $alpha * ($x - $x_data[$k]) * ($x - $x_data[$k]) * ($x - $x_data[$k]) + $beta * ($x - $x_data[$k + 1]) * ($x - $x_data[$k + 1]) * ($x - $x_data[$k + 1]) + $gamma * ($x - $x_data[$k]) + $eta * ($x - $x_data[$k + 1]);
            $t_spline[$i + 1] = $x;
            #Corrección límites presión
            if ($f < 14.7) {
                $f = 14.7;
            }
            if ($f > 10000) {
                $f = 1000;
            }
            $p_spline[$i + 1] = $f;
        }

        return array($t_spline, $p_spline); #Resultados Saturation Data
    }

    function cubic_spline($n, $x_data, $y_data)
    {
        $g = array_fill(0, 100, null);
        $h = array_fill(0, 100, null);
        $d = array_fill(0, 100, null);
        $b = array_fill(0, 100, null);
        $c = array_fill(0, 100, null);

        for ($i = 1; $i <= $n; $i++) {
            $h[$i] = $x_data[$i + 1] - $x_data[$i];
            $g[$i] = $y_data[$i + 1] - $y_data[$i];
        }

        for ($i = 1; $i <= $n - 1; $i++) {
            $d[$i] = 2 * ($h[$i + 1] + $h[$i]);
            $b[$i] = 6 * ($g[$i + 1] / $h[$i + 1] - $g[$i] / $h[$i]);
            $c[$i] = $h[$i + 1];
        }

        $l = $n - 1;
        $g = $this->tridiagonal_linear_equation($l, $d, $c, $c, $b, $g);
        $p2 = array_fill(0, 100, 0);
        $p2[1] = 0;
        $p2[$n + 1] = 0;
        for ($i = 2; $i <= $n; $i++) {
            $p2[$i] = $g[$i - 1];
        }

        return $p2;
    }

    function tridiagonal_linear_equation($l, $dt, $et, $ct, $bt, $zt)
    {

        $yt = array_fill(0, 100, null);
        $wt = array_fill(0, 100, null);
        $vt = array_fill(0, 100, null);
        $tt = array_fill(0, 100, null);

        $wt[1] = $dt[1];
        $vt[1] = $ct[1];
        $tt[1] = $et[1] / $wt[1];
        for ($i = 2; $i <= $l - 1; $i++) {
            $wt[$i] = $dt[$i] - $vt[$i - 1] * $tt[$i - 1];
            $vt[$i] = $ct[$i];
        }

        $wt[$l] = $dt[$l] - $vt[$l - 1] * $tt[$l - 1];

        if ($wt[1] == 0) {
            $yt[1] = 0;
        } else {
            $yt[1] = $bt[1] / $wt[1];
        }

        for ($i = 2; $i <= $l; $i++) {
            $yt[$i] = ($bt[$i] - $vt[$i - 1] * $yt[$i - 1]) / $wt[$i];
        }

        #$zt = array_fill(0, 100, null);
        $zt[$l] = $yt[$l];
        for ($i = $l - 1; $i >= 1; $i--) {
            $zt[$i] = $yt[$i] - $tt[$i] * $zt[$i + 1];
        }

        return $zt;
    }

    function density_correction($n, $zi, $p_yto, $t_yto, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $pspline, $tspline, $deno1, $deno2, $deno3)
    {
        $denoaux = array_fill(1, 100, 0);
        $vliqaux = array_fill(1, 100, 0);

        $t_ytor = $t_yto + 460;
        $pb = $this->interpolation($t_ytor, 100, $tspline, $pspline);
        $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium(20, $t_ytor, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);

        $x_data = $liquid_steam_equilibrium_results[0];
        $y_data = $liquid_steam_equilibrium_results[1];
        $z_liq = $liquid_steam_equilibrium_results[2];
        $z_vap = $liquid_steam_equilibrium_results[3];
        $v = $liquid_steam_equilibrium_results[4];

        $mo = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $mo = $mo + $mwi[$i] * $x_data[$i];
        }

        if ($mo == 0) {
            $mo = 0;
            for ($i = 1; $i <= $n; $i++) {
                $x_data[$i] = $zi[$i];
                $mo = $mo + $x_data[$i] * $mwi[$i];
            }
        }

        #Densidad del petróleo
        for ($i = 1; $i <= $n; $i++) {
            $denoaux[$i] = $rhoi[$i] * 62.4;
            $vliqaux[$i] = $mwi[$i] * $x_data[$i] / $denoaux[$i];
        }

        #Volumen del líquido
        $vliq = 0;
        for ($i = 1; $i <= $n; $i++) {
            $vliq = $vliq + $vliqaux[$i];
        }

        #Densidad
        $deno = $mo / $vliq;
        $dif1 = $deno - ($deno1 * 62.4);

        $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium($pb, $t_ytor, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);
        $x_data = $liquid_steam_equilibrium_results[0];
        $y_data = $liquid_steam_equilibrium_results[1];
        $z_liq = $liquid_steam_equilibrium_results[2];
        $z_vap = $liquid_steam_equilibrium_results[3];
        $v = $liquid_steam_equilibrium_results[4];

        $mo = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $mo = $mo + $mwi[$i] * $x_data[$i];
        }

        if ($mo == 0) {
            $mo = 0;
            for ($i = 1; $i <= $n; $i++) {
                $x_data[$i] = $zi[$i];
                $mo = $mo + $x_data[$i] * $mwi[$i];
            }
        }

        #Densidad del petróleo
        for ($i = 1; $i <= $n; $i++) {
            $denoaux[$i] = $rhoi[$i] * 62.4;
            $vliqaux[$i] = $mwi[$i] * $x_data[$i] / $denoaux[$i];
        }

        #Volumen del líquido
        $vliq = 0;
        for ($i = 1; $i <= $n; $i++) {
            $vliq = $vliq + $vliqaux[$i];
        }

        #Densidad
        $deno = $mo / $vliq;
        $dif2 = $deno - ($deno2 * 62.4);
        if ($p_yto < $pb) {
            $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium($p_yto, $t_ytor, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);
            $x_data = $liquid_steam_equilibrium_results[0];
            $y_data = $liquid_steam_equilibrium_results[1];
            $z_liq = $liquid_steam_equilibrium_results[2];
            $z_vap = $liquid_steam_equilibrium_results[3];
            $v = $liquid_steam_equilibrium_results[4];
        }
        $mo = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $mo = $mo + $mwi[$i] * $x_data[$i];
        }

        if ($mo == 0) {
            $mo = 0;
            for ($i = 1; $i <= $n; $i++) {
                $x_data[$i] = $zi[$i];
                $mo = $mo + $x_data[$i] * $mwi[$i];
            }
        }

        #Densidad del petróleo
        for ($i = 1; $i <= $n; $i++) {
            $denoaux[$i] = $rhoi[$i] * 62.4;
            $vliqaux[$i] = $mwi[$i] * $x_data[$i] / $denoaux[$i];
        }

        #Volumen de líquido
        $vliq = 0;
        for ($i = 1; $i <= $n; $i++) {
            $vliq = $vliq + $vliqaux[$i];
        }

        #Densidad
        $deno = $mo / $vliq;
        $dif3 = $deno - ($deno3 * 62.4);

        $corr_do = ($dif1 + $dif2 + $dif3) / 3.0;

        return $corr_do;
    }

    function liquid_steam_equilibrium($p, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $sgi, $cib)
    {
        $ki = array_fill(1, 100, 0);
        $errorf = array_fill(1, 100, 0);
        $tolf = 1E-18;
        $iterf = 1;
        $ki = $this->equilibrium_constants($n, $p, $t, $zi, $tci, $pci, $mwi, $wi, $sgi);
        $maxef = 11;
        while ($maxef > $tolf) {
            $flash_equilibrium_results = $this->flash_equilibrium($p, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $cib, $ki);

            $xi = $flash_equilibrium_results[0];
            $yi = $flash_equilibrium_results[1];
            $fugv = $flash_equilibrium_results[2];
            $fugl = $flash_equilibrium_results[3];
            $v = $flash_equilibrium_results[4];
            $zliq = $flash_equilibrium_results[5];
            $zvap = $flash_equilibrium_results[6];

            for ($i = 1; $i <= $n; $i++) {
                $ki[$i] = $ki[$i] * $fugl[$i] / $fugv[$i];
            }
            for ($i = 1; $i <= $n; $i++) {
                $errorf[$i] = pow((($fugl[$i] / $fugv[$i]) - 1), 2);
            }

            $maxef = max($errorf);
            $iterf++;
            if ($iterf == 10 or $iterf == 25 or $iterf == 50) {
                $tolf = $tolf * 10;
            }
            if ($iterf > 150) {
                $maxef = 0.0;
            }
        }

        return array($xi, $yi, $zliq, $zvap, $v); #Posible error - nombre variable
    }

    function equilibrium_constants($n, $p, $t, $zi, $tci, $pci, $mwi, $wi, $sgi)
    {
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);

        if (($zi[$n] > 0.3) and ($t > 510) and ($mwi[$n] > 300)) {
            $pk = 2381.8542 + 46.341487 * $mwi[$n] * $sgi[$n] + 6124.3049 * ($mwi[$n] * $sgi[$n] / ($t - 459.6)) - 2753.2538 * (pow(($mwi[$n] * $sgi[$n] / ($t - 460)), 2)) + 415.42049 * (pow(($mwi[$n] * $sgi[$n] / ($t - 460)), 3));
            $a = 1.0 - pow(($p / $pk), 0.7);
            for ($i = 1; $i <= $n; $i++) {
                $tri[$i] = $t / $tci[$i];
                $pri[$i] = $p / $pci[$i];
                $ki[$i] = pow(($pci[$i] / $pk), ($a - 1)) * ($pci[$i] / $p) * exp(5.3727 * $a * (1.0 + $wi[$i]) * (1.0 - 1.0 / $tri[$i])); #Revisar
            }
        } else {
            for ($i = 1; $i <= $n; $i++) {
                $tri[$i] = $t / $tci[$i];
                $pri[$i] = $p / $pci[$i];
                $ki[$i] = (1.0 / $pri[$i]) * exp(5.3727 * (1.0 + $wi[$i]) * (1.0 - 1.0 / $tri[$i]));
            }
        }

        return $ki;
    }

    function liquid_raschford($n, $zi, $ki)
    {

        $fvapor = 0.5; #primer valor para fvapor a iterar
        $sumfv = 0.25;
        $tol_eq = 0.00000001;
        $xi = array_fill(1, $n, 0);
        $yi = array_fill(1, $n, 0);

        while (1) #Revisar
        {
            $tol1 = 1.0; #limite para tol. para entrar al ciclo iterativo
            $cont = 0;
            $while_flag = True;
            while ($tol1 > $tol_eq) {
                $cont = $cont + 1;
                $sum_xi = 0.0;
                for ($i = 1; $i <= $n; $i++) {
                    $xi[$i] = $zi[$i] / ($fvapor * ($ki[$i] - 1) + 1); #calcula comp del liquido
                    $sum_xi = $sum_xi + $xi[$i];
                }

                $fv1 = $fvapor;
                if ($sum_xi > 1) {
                    $fvapor = $fvapor - $sumfv;
                } else {
                    $fvapor = $fvapor + $sumfv;
                }

                $sumfv = $sumfv / 2.0;
                $tol1 = abs(($fvapor - $fv1) / $fv1);
                if ($cont > 1000) {
                    $tol_eq = $tol_eq * 10; #Revisar - cambio
                    $while_flag = False;
                    break;
                }
            }
            if ($while_flag) {
                break;
            }
        }

        $v = $fvapor;
        $sum_yi = 0;
        for ($i = 1; $i <= $n; $i++) {
            $yi[$i] = $ki[$i] * $xi[$i]; #calcula composicion del vapor
            $sum_yi = $sum_yi + $yi[$i];
        }
        for ($i = 1; $i <= $n; $i++) {
            $xi[$i] = $xi[$i] / $sum_xi; #normaliza comp. del liquido
        }
        if ($sum_yi != 0) {
            for ($i = 1; $i <= $n; $i++) {
                $yi[$i] = $yi[$i] / $sum_yi; #normaliza comp. del vapor
            }
        }

        return array($xi, $yi, $v);
    }

    function solid_raschford($n, $zi, $kis)
    {

        $tol = 1e-17;
        $tol1 = 1.0;
        $xi = array_fill(1, $n, 0);
        $si = array_fill(1, $n, 0);

        $fsolido = 0.5; # primer valor para fvapor a iterar
        $sumfs = 0.25;

        #límite para tol. para entrar al ciclo iterativo
        $cont = 0.0;

        while ($tol1 > $tol) {
            $cont = $cont + 1;
            $sum_xi = 0.0;
            for ($i = 1; $i <= $n; $i++) {
                if ($fsolido > 0.000001) {
                    if (($fsolido * ($kis[$i] - 1.0) + 1.0) == 0) {
                        return [false, ['msg' => 'Invalid Plus+ characterization, please use other correlation.']];
                    }
                    $xi[$i] = $zi[$i] / ($fsolido * ($kis[$i] - 1.0) + 1.0); #calcula comp del liquido
                    $sum_xi = $sum_xi + $xi[$i];
                } else {
                    $fsolido = 0;
                }
            }

            $fs1 = $fsolido;
            if ($sum_xi > 1.0) {
                $fsolido = $fsolido - $sumfs;
            } else {
                $fsolido = $fsolido + $sumfs;
            }

            $sumfs = $sumfs / 2.0;
            if ($fs1 > 0.000001) #Posible error
            {
                $tol1 = abs(($fsolido - $fs1) / $fs1);
                if ($cont == 100) {
                    $tol = $tol * 10;
                }
                if ($cont == 250) {
                    $tol = $tol * 10;
                }
                if ($cont == 500) {
                    $tol = $tol * 10;
                }
                if ($cont > 1500) {
                    $fsolido = 0.0;
                    break; #Revisar goto
                }
            } else {
                break; #Revisar goto
            }
        }

        $sum_si = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $si[$i] = $kis[$i] * $xi[$i];
            $sum_si = $sum_si + $si[$i];
        }
        for ($i = 1; $i <= $n; $i++) {
            if ($sum_xi != 0) {
                $xi[$i] = $xi[$i] / $sum_xi; #normaliza comp. del liquido
            }
        }
        if ($sum_si != 0) {
            $si[$i - 1] = $si[$i - 1] / $sum_si; #normaliza comp. del solido #Revisar
            if ($si[$i - 1] < 0.0000001) {
                $si[$i - 1] = 0; #Corrección 
            }
            #para la salida de la subrutina
        }

        return array($xi, $si, $fsolido);
    }

    function flash_equilibrium($p, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $cib, $ki)
    {
        $zz = array_fill(1, 3, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $lncf1 = array_fill(1, 100, 0);
        $lncf2 = array_fill(1, 100, 0);
        $lncf3 = array_fill(1, 100, 0);
        $fug1 = array_fill(1, 100, 0);
        $fug2 = array_fill(1, 100, 0);
        $fug3 = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);

        $liquid_raschford_results = $this->liquid_raschford($n, $zi, $ki);
        $xi = $liquid_raschford_results[0];
        $yi = $liquid_raschford_results[1];
        $fvap = $liquid_raschford_results[2];

        $sumxi = array_sum($xi);
        $sumyi = array_sum($yi);

        if ($sumxi != 0) {
            $preprocessing_results = $this->preprocessing($p, $t, $n, $yi, $mwi, $pci, $tci, $vci, $wi, $si, $cib);
            $tri = $preprocessing_results[0];
            $pri = $preprocessing_results[1];
            $ati = $preprocessing_results[2];
            $bi = $preprocessing_results[3];
            $ci = $preprocessing_results[4];
            $s_aj = $preprocessing_results[5];
            $daidt = $preprocessing_results[6];
            $amin = $preprocessing_results[7];
            $bmin = $preprocessing_results[8];
            $amay = $preprocessing_results[9];
            $bmay = $preprocessing_results[10];

            $zz = $this->cubic($amay, $bmay);

            $fugacity_results = $this->fugacity($p, $t, $n, $yi, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);
            $lncf1 =  $fugacity_results[0];
            $lncf2 =  $fugacity_results[1];
            $lncf3 =  $fugacity_results[2];
            $fug1 =  $fugacity_results[3];
            $fug2 =  $fugacity_results[4];
            $fug3 =  $fugacity_results[5];

            $gibbs_stability_results = $this->gibbs_stability($n, $yi, $fug1, $fug2, $fug3, $zz);
            $fugv = $gibbs_stability_results[0];
            $zvap = $gibbs_stability_results[1]; #Revisar últimas cifras decimales.
        }
        if ($sumyi != 0) {
            $preprocessing_results = $this->preprocessing($p, $t, $n, $xi, $mwi, $pci, $tci, $vci, $wi, $si, $cib);
            $tri = $preprocessing_results[0];
            $pri = $preprocessing_results[1];
            $ati = $preprocessing_results[2];
            $bi = $preprocessing_results[3];
            $ci = $preprocessing_results[4];
            $s_aj = $preprocessing_results[5];
            $daidt = $preprocessing_results[6];
            $amin = $preprocessing_results[7];
            $bmin = $preprocessing_results[8];
            $amay = $preprocessing_results[9];
            $bmay = $preprocessing_results[10];

            $zz = $this->cubic($amay, $bmay);

            $fugacity_results = $this->fugacity($p, $t, $n, $xi, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);
            $lncf1 =  $fugacity_results[0];
            $lncf2 =  $fugacity_results[1];
            $lncf3 =  $fugacity_results[2];
            $fug1 =  $fugacity_results[3];
            $fug2 =  $fugacity_results[4];
            $fug3 =  $fugacity_results[5];

            $gibbs_stability_results = $this->gibbs_stability($n, $xi, $fug1, $fug2, $fug3, $zz);

            $fugl = $gibbs_stability_results[0];
            $zliq = $gibbs_stability_results[1];
        }

        return array($xi, $yi, $fugv, $fugl, $fvap, $zliq, $zvap);
    }

    function preprocessing($p, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $cib)
    {

        $mi = array_fill(1, 100, 0);
        $alfai = array_fill(1, 100, 0);
        $aci = array_fill(1, 100, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);
        $amin = array_fill(1, 100, 0);
        $bmin = array_fill(1, 100, 0);

        $r = 10.73146;
        for ($i = 1; $i <= $n; $i++) {
            $tri[$i] = $t / $tci[$i];
            $pri[$i] = $p / $pci[$i];
            if ($wi[$i] <= 0.49) {
                $mi[$i] = (0.37464 + 1.54226 * $wi[$i] - 0.26992 * pow($wi[$i], 2));
            } else {
                $mi[$i] = 0.3796 + 1.485 * $wi[$i] - 0.1644 * pow($wi[$i], 2) + 0.01667 * pow($wi[$i], 3);
            }

            $arg1 = $tri[$i];
            if ($arg1 < 0.000000001) {
                $arg1 = 0.000000001;
            }
            $alfai[$i] = pow((1 + $mi[$i] * (1 - pow($arg1, 0.5))), 2);
            #phase behavior whitson 4.21a sin el alfa
            $aci[$i] = 0.45724 * pow(($r * $tci[$i]), 2) / $pci[$i];
            $bi[$i] = 0.0778 * ($r * $tci[$i]) / $pci[$i];

            if ($wi[$n] > 0.89) {
                #coef. de translacion de volumen ecu phase behavior whitson 4.28 sin el alfa
                $ci[$i] = $si[$i] * $bi[$i];
            } else {
                #corrección peneloux
                $ci[$i] = (0.0115831168 + 0.411844152 * $wi[$i]) * ($tci[$i] / $pci[$i]);
            }
            #ecu phase behavior whitson 4.21a con el alfa
            $ati[$i] = $aci[$i] * $alfai[$i];
        }

        #derivada da/dt
        for ($i = 1; $i <= $n; $i++) {
            $daidt[$i] = -$aci[$i] * $mi[$i] / sqrt($t * $tci[$i]) * (1.0 + $mi[$i] * (1.0 - sqrt($t / $tci[$i])));
        }

        $amin = 0;
        $suma_aij = 0;
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $amin = $amin + (1 - $cib[$i][$j]) * ($zi[$i] * $zi[$j]) * pow(($ati[$i] * $ati[$j]), 0.5); #Posible error...
                $suma_aij = $suma_aij + (1 - $cib[$i][$j]) * $zi[$j] * pow(($ati[$i] * $ati[$j]), 0.5);
            }
            $s_aj[$i] = $suma_aij;
            $suma_aij = 0;
        }
        $bmin = 0;
        for ($i = 1; $i <= $n; $i++) {
            $bmin = $bmin + $zi[$i] * $bi[$i];
        }

        $amay = ($amin * $p) / (pow($r, 2) * pow($t, 2));
        $bmay = ($bmin * $p) / ($r * $t);

        return array($tri, $pri, $ati, $bi, $ci, $s_aj, $daidt, $amin, $bmin, $amay, $bmay);
    }

    function cubic($amay, $bmay)
    {

        $zroot = array_fill(1, 3, 0);
        $zz = array_fill(1, 3, 0);
        $eps = 2.71828182846;
        $z1c = 0;
        $z2c = 0;
        $z3c = 0;
        $a = 1.0;
        $b = $bmay - 1.0;
        $c = $amay - (2 * $bmay) - 3 * (pow($bmay, 2));
        $d = -($amay * $bmay) + pow($bmay, 2) + pow($bmay, 3);
        $a1 = $a / $a;
        $a2 = $b / $a;
        $a3 = $c / $a;
        $a4 = $d / $a;
        $pp = 1 / 3.0 * (3.0 * $a3 - pow($a2, 2));
        $qq = 1 / 27.0 * (2 * pow($a2, 3) - 9 * $a2 * $a3 + 27 * $a4);
        $e = 1 / 4.0 * pow($qq, 2) + 1 / 27.0 * pow($pp, 3);
        $pq = $pp * $qq;
        $zlogical = 0;

        if ($e > 0) {
            $jj = -$qq / 2.0 + sqrt($e);
            $ll = -$qq / 2.0 - sqrt($e);

            if ($jj < 0) {
                $p1 = -(pow(-$jj, (1 / 3.0)));
            } else {
                $p1 = pow($jj, 0.333333);
            }

            if ($ll < 0) {
                $q1 = -(pow(-$ll, (1.0 / 3.0)));
            } else {
                $q1 = pow($ll, (1.0 / 3.0));
            }

            $x1 = $p1 + $q1 - $a2 / 3.0;

            #Condición 1
            $zlogical = 1;
        } else if (($e == 0) and ($pp == 0) and ($qq == 0)) {
            $x1 = -$a2 / 3.0;
            $x2 = -$a2 / 3.0;
            $x3 = -$a2 / 3.0;
            $zlogical = 1;
        } else if (($e == 0) and ($pq != 0)) {
            $x1 = -3 * $qq / 2.0 * $pp - $a2 / 3.0;
            $x3 = -4 * pow($pp,  2) / 9 * $qq - $a2 / 3.0;
            #condicion 2
            $zlogical = 2;
        } else if ($e < 0.0) {
            $x1 = 2 * sqrt(-$pp / 3.0) * cos((acos((-$qq / 2.0) / (sqrt(pow((-$pp / 3.0), 3.0)))) + 0) / 3.0) - $a2 / 3.0;
            $x2 = 2 * sqrt(-$pp / 3.0) * cos((acos((-$qq / 2.0) / (sqrt(pow((-$pp / 3.0), 3)))) + 360) / 3.0) - $a2 / 3.0;
            $x3 = 2 * sqrt(-$pp / 3.0) * cos((acos((-$qq / 2.0) / (sqrt(pow((-$pp / 3.0), 3)))) + 720) / 3.0) - $a2 / 3.0;

            #condicion 3
            $zlogical = 3;
        }

        #Se toman las dos unicas raices reales (hay dos iguales)
        if ($zlogical == 1) {
            $z1c = $x1;
        } else if ($zlogical == 2) {
            $z1c = $x1;
            $z3c = $x3;
            if ($z1c < 0.0) {
                $zlogical = 1;
                $z1c = $x3;
            } else if ($z3c < 0.0) {
                $zlogical = 1;
            }
        } else if ($zlogical == 3) {
            $zroot[1] = $x1;
            $zroot[2] = $x2;
            $zroot[3] = $x3;
            for ($i = 1; $i <= 3; $i++) {
                for ($k = 1; $k <= 3; $k++) {
                    if ($zroot[$i] > $zroot[$k]) {
                        $aux = $zroot[$i];
                        $zroot[$i] = $zroot[$k];
                        $zroot[$k] = $aux;
                    }
                }
            }
            $z1c = $zroot[1];
            $z2c = $zroot[2];
            $z3c = $zroot[3];
            if ($z2c < 0.0) {
                $zlogical = 1;
            } else if ($z3c < 0.0) {
                $zlogical = 2;
                $z3c = $z2c;
            }
        }

        $zz[1] = $z1c;
        $zz[2] = $z2c;
        $zz[3] = $z3c;

        return $zz;
    }

    function fugacity($p, $t, $n, $zi, $bi, $ci, $s_aj, $am, $bm, $a, $b, $zz)
    {
        $z1 = $zz[1]; #Revisar cómo entran los índices
        $z2 = $zz[2];
        $z3 = $zz[3];

        $lncf1 =  array_fill(1, $n, 0);
        $lncf2 =  array_fill(1, $n, 0);
        $lncf3 =  array_fill(1, $n, 0);

        $f1 =  array_fill(1, $n, 0);
        $f2 =  array_fill(1, $n, 0);
        $f3 =  array_fill(1, $n, 0);

        $r = 10.73146;
        #Primera raíz
        for ($i = 1; $i <= $n; $i++) {
            $z = $z1;
            $arg1 = $z - $p * $bm / ($r * $t);
            $num = ($z + 2.4142135 * $b);
            $den = ($z - 0.4142135 * $b);
            $arg2 = $num / $den;
            if ($arg1 < 0.000000001) {
                $arg1 = 0.000000001;
            }

            if ($arg2 < 0.000000001) {
                $arg2 = 0.000000001;
            }

            $lncf1[$i] = -log($arg1) + ($bi[$i] / $bm) * ($z - 1) - ($a / (2.0 * $b * pow(2.0, 0.5))) * (2.0 * $s_aj[$i] / $am - $bi[$i] / $bm) * log($arg2);
            $f1[$i] = $p * $zi[$i] * exp($lncf1[$i]);
        }

        #Segunda raíz
        if ($z2 == 0) {
            for ($i = 1; $i <= $n; $i++) {
                $lncf2[$i] = 0;
                $f2[$i] = 0;
            }
        } else {
            $z = $z2;
            for ($i = 1; $i <= $n; $i++) {
                $arg1 = $z - $p * $bm / ($r * $t);
                $num = ($z + 2.4142135 * $b);
                $den = ($z - 0.4142135 * $b);
                $arg2 = $num / $den;

                if ($arg1 < 0.000000001) {
                    $arg1 = 0.000000001;
                }

                if ($arg2 < 0.000000001) {
                    $arg2 = 0.000000001;
                }

                $lncf2[$i] = -log($arg1) + ($bi[$i] / $bm) * ($z - 1) - ($a / (2 * $b * pow(2, 0.5))) * (2 * $s_aj[$i] / $am - $bi[$i] / $bm) * log($arg2);
                $f2[$i] = $p * $zi[$i] * exp($lncf2[$i]);
            }
        }

        #Tercera raíz
        if ($z3 == 0) {
            for ($i = 1; $i <= $n; $i++) {
                $lncf3[$i] = 0;
                $f3[$i] = 0;
            }
        } else {
            $z = $z2;
            for ($i = 1; $i <= $n; $i++) {
                $z = $z3;
                $arg1 = $z - $p * $bm / ($r * $t);
                $num = ($z + 2.4142135 * $b);
                $den = ($z - 0.4142135 * $b);
                $arg2 = $num / $den;
                if ($arg1 < 0.000000001) {
                    $arg1 = 0.000000001;
                }
                if ($arg2 < 0.000000001) {
                    $arg2 = 0.000000001;
                }
                $lncf3[$i] = -log($arg1) + ($bi[$i] / $bm) * ($z - 1) - ($a / (2.0 * $b * pow(2.0, 0.5))) * (2.0 * $s_aj[$i] / $am - $bi[$i] / $bm) * log($arg2);
                $f3[$i] = $p * $zi[$i] * exp($lncf3[$i]);
            }
        }

        return array($lncf1, $lncf2, $lncf3, $f1, $f2, $f3);
    }

    function pure_fugacity($p, $t, $a, $b, $z)
    {
        $arg1 = $z - $b;
        $num = ($z + 2.4142135 * $b);
        $den = ($z - 0.4142135 * $b);
        $arg2 = $num / $den;
        if ($arg1 < 0.000000001) {
            $arg1 = 0.000000001;
        }
        if ($arg2 < 0.000000001) {
            $arg2 = 0.000000001;
        }
        $fugp = $p * exp($z - 1.0 - log($arg1) - ($a / (2.82843 * $b)) * log($arg2));

        return $fugp;
    }

    function pure_fugacity_solid($n, $p, $t, $mwi, $fugpuro)
    {
        $r = 10.73146;
        $fugsolpuro = array_fill(1, $n, 0);
        for ($i = 1; $i <= $n; $i++) {
            $tfus = (((374.5 + 0.02617 * $mwi[$i] - 20172 / $mwi[$i]) - 273.15) * 1.8) + 491.67;
            $hfus = 0.1426 * $mwi[$i] * $tfus * 3.086;
            $fugsolpuro[$i] = ($fugpuro[$i] * exp(-$hfus / ($r * $t) * (1.0 - $t / $tfus)));
        }

        return $fugsolpuro;
    }

    function asphaltene_pure_fugacity_solid($n, $p, $t, $mwi, $fugpuro)
    {
        $r = 10.73146;
        $fugsolpuro = array_fill(1, $n, 0);
        for ($i = 1; $i <= $n; $i++) {
            $tfus = (((374.5 + 0.02617 * $mwi[$i] - 20172 / $mwi[$i]) - 273.15) * 1.8) + 491.67;
            $hfus = 0.1426 * $mwi[$i] * $tfus * 3.086;
            #parametro original flory-huggins. el termino de solubilidad no cuadra
            $sol = 0; #0.32 / (r * t)
            $fugsolpuro[$i] = ($fugpuro[$i] * exp(-$hfus / ($r * $t) * (1.0 - $t / $tfus)) + $sol);
        }
        return $fugsolpuro;
    }

    function gibbs_stability($nc, $zi, $f1, $f2, $f3, $zz)
    {
        $z1 = $zz[1];
        $z2 = $zz[2];
        $z3 = $zz[3];

        $arg1 = 0;
        $arg2 = 0;
        $arg3 = 0;

        $fg = array_fill(1, $nc, 0);
        $result = [];

        if ($z2 == 0 and $z3 == 0) #Posible error - operadores lógicos condicionales
        {
            for ($i = 1; $i <= $nc; $i++) {
                $fg[$i] = $f1[$i];
            }
            $zg = $z1;
            return array($fg, $zg);
        }

        if ($z3 == 0 and $z2 != 0) {
            $sum1 = 0;
            $sum2 = 0;
            for ($i = 1; $i < $nc; $i++) {
                $arg1 = $f1[$i];
                if ($arg1 < 0.000000001) {
                    $arg1 = 0.000000001;
                }
                $sum1 = $sum1 + $zi[$i] * log($arg1);
                $arg2 = $f2[$i];
                if ($arg2 < 0.000000001) {
                    $arg2 = 0.000000001;
                }
                $sum2 = $sum2 + $zi[$i] * log($arg2);
            }

            $gz1 = $sum1;
            $gz2 = $sum2;
            $gmin = min($sum1, $sum2);

            if ($gmin == $gz1) {
                for ($i = 1; $i <= $nc; $i++) {
                    $fg[$i] = $f1[$i];
                }
                $zg = $z1;
            } else {
                for ($i = 1; $i <= $nc; $i++) {
                    $fg[$i] = $f2[$i];
                }
                $zg = $z2;
            }
        }

        if ($z2 == 0 and $z3 != 0) {
            $sum1 = 0;
            $sum3 = 0;
            for ($i = 1; $i <= $nc; $i++) {
                $arg1 = $f1[$i];
                if ($arg1 < 0.000000001) {
                    $arg1 = 0.000000001;
                }
                $sum1 = $sum1 + $zi[$i] * log($arg1);
                $arg3 = $f3[$i];
                if ($arg3 < 0.000000001) {
                    $arg3 = 0.000000001;
                }
                $sum3 = $sum3 + $zi[$i] * log($arg3);
            }

            $gz1 = $sum1;
            $gz3 = $sum3;
            $gmin = min($gz1, $gz3);

            if ($gmin == $gz1) {
                for ($i = 1; $i <= $nc; $i++) {
                    $fg[$i] = $f1[$i];
                }
                $zg = $z1;
            } else {
                for ($i = 2; $i <= $nc; $i++) {
                    $fg[$i] = $f3[$i];
                }
                $zg = $z3;
            }
        }

        if ($z2 != 0 and $z3 != 0) {
            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;

            for ($i = 1; $i <= $nc; $i++) {
                $arg1 = $f1[$i];
                if ($arg1 < 0.000000001) {
                    $arg1 = 0.000000001;
                }
                $sum1 = $sum1 + $zi[$i] * log($arg1);
                $arg2 = $f2[$i];
                if ($arg2 < 0.000000001) {
                    $arg2 = 0.000000002;
                }
                $sum2 = $sum2 + $zi[$i] * log($arg2);
                $arg2 = $f3[$i];
                if ($arg3 < 0.000000001) {
                    $arg3 = 0.000000001;
                }
                $sum3 = $sum3 + $zi[$i] * log($arg3);
            }

            $gz1 = $sum1;
            $gz3 = $sum3;
            $gz2 = $sum2;

            #se selecciona la energía libre de gibbs menor
            $gmin = min($gz1, min($gz2, $gz3));

            #se toma la raíz correspondiente a la energía de gibbs menor
            if ($gmin == $gz1) {
                for ($i = 1; $i <= $nc; $i++) {
                    $fg[$i] = $f1[$i];
                }
                $zg = $z1;
            } else if ($gmin == $gz2) {
                for ($i = 1; $i <= $nc; $i++) {
                    $fg[$i] = $f2[$i];
                }
                $zg = $z2;
            } else {
                for ($i = 1; $i <= $nc; $i++) {
                    $fg[$i] = $f3[$i];
                }
                $zg = $z3;
            }
        }

        return array($fg, $zg);
    }

    function asphaltenes_region_1($n, $p, $t, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat, $aro, $res, $asf, $rhoa, $mwa, $dsa, $cordo, $gapi, $pb, $ponsetc, $nmaxa, $amax)
    {

        $zz = array_fill(1, 3, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $lncf1 = array_fill(1, 100, 0);
        $lncf2 = array_fill(1, 100, 0);
        $lncf3 = array_fill(1, 100, 0);
        $fug1 = array_fill(1, 100, 0);
        $fug2 = array_fill(1, 100, 0);
        $fug3 = array_fill(1, 100, 0);
        $fg = array_fill(1, 100, 0);
        $fugpuro = array_fill(1, 100, 0);
        $fugsolpuro = array_fill(1, 100, 0);
        $fugsol = array_fill(1, 100, 0);
        $denpro = array_fill(1, 100, 0);
        $ziini = array_fill(1, 100, 0);
        $zia = array_fill(1, 100, 0);
        $zino = array_fill(1, 100, 0);
        $zit = array_fill(1, 100, 0);
        $fliq = array_fill(1, 100, 0);
        $fliqa = array_fill(1, 100, 0);
        $fsola = array_fill(1, 100, 0);
        $kia = array_fill(1, 100, 0);
        $errorf = array_fill(1, 100, 0);
        $criterio1 = array_fill(1, 100, 0);
        $xil = array_fill(1, 100, 0);
        $xia = array_fill(1, 100, 0);
        $xig = array_fill(1, 100, 0);
        $pcia = array_fill(1, 100, 0);
        $pcina = array_fill(1, 100, 0);
        $rhop = array_fill(1, 100, 0);
        $pcibar = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);
        $dsl = 0; #Posible error - no están definidas, inicializar con 0?
        $dslm = 0; #Posible error - no están definidas, inicializar con 0?

        #Máxima cantidad en el punto de burbuja
        #Asfaltenos
        for ($i = 1; $i <= $n; $i++) {
            $zia[$i] = $zi[$i];    #Los demas componentes puros
            $pcia[$i] = $pci[$i];
        }

        $r = 10.73146;

        #Se amplía en 1 más
        $m = $n + 1;

        #Teniendo encuenta el SARA
        $zia[$m] = $zi[$n] * ($asf / 100);
        $zia[$n] = $zi[$n] * (($sat + $aro + $res) / 100);

        #Llenando la siguiente tabla
        $mwi[$m] = $mwa;
        $rhoi[$m] = $rhoa;
        $pci[$m] = $pci[$n];
        $tci[$m] = $tci[$n];
        $vci[$m] = $vci[$n];
        $wi[$m] = $wi[$n];
        $si[$m] = $si[$n];

        #el factor acentrico se deja igual.
        $pcia[$m] = exp(9.77968 - 3.0755 * pow($mwa, 0.15));

        #Corrección
        for ($i = $n; $i <= $m; $i++) {
            $pcibar[$i] = 14.7 * ($pci[$i] * 0.0689475729);

            #densidad de pesados como función del peso de los asfaltenos
            if ($i == $n) {
                $rhop[$n] = 0.3915 + 0.0675 * log($mwi[$n]);
                $pcia[$n] = $pcibar[$i] * pow(($rhop[$n] / $rhoi[$n]), 3.46);
                $pcia[$n] = $pcia[$i] / 0.0689475729;
            } else if ($i == $m) {
                $rhop[$m] = 0.3915 + 0.0675 * log($mwi[$m]);
                #corrección de presión critica en funcion de la densidad solidos y el peso molucular
                $pcia[$m] = 0.2 * $pcia[$m] + 0.8 * (1891 * pow($mwa, -0.7975) * pow(($rhop[$i] / $rhoi[$i]), 3.46)) / 0.0689475729;

                #El promedio de la temperatura crítica... no se sabe nada más
                $tci[$m] = (77.85 * pow($mwa, 0.4708) + $tci[$n]) / 2;
            }
        }

        for ($j = 1; $j <= $m; $j++) {
            $cib[$m][$j] = 0;
            $cib[$j][$m] = 0;
        }

        $cib[$m][$n] = -0.0078109 + 0.000038852 * $mwa;
        $cib[$m][$n] = -0.0078109 + 0.000038852 * $mwa;

        $preprocessing_results = $this->preprocessing($p, $t, $m, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $cib);

        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $zz = $this->cubic($amay, $bmay);

        $fugacity_results = $this->fugacity($p, $t, $m, $zia, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);
        $lncf1 = $fugacity_results[0];
        $lncf2 = $fugacity_results[1];
        $lncf3 = $fugacity_results[2];
        $fug1 = $fugacity_results[3];
        $fug2 = $fugacity_results[4];
        $fug3 = $fugacity_results[5];

        $gibbs_stability_results = $this->gibbs_stability($m, $zia, $fug1, $fug2, $fug3, $zz);
        $fg = $gibbs_stability_results[0];
        $zg = $gibbs_stability_results[1];

        #Primera aproximación
        for ($i = 1; $i <= $m; $i++) {
            $fliq[$i] = $zia[$i] * $fg[$i];
        }

        $fugpuro = $this->pure_compound($m, $p, $t, $pcia, $tci, $wi);
        $fugsolpuro = $this->asphaltene_pure_fugacity_solid($m, $p, $t, $mwi, $fugpuro);

        for ($i = 1; $i <= $m; $i++) {
            $fugsol[$i] = $zia[$i] * $fugsolpuro[$i];
        }

        for ($i = 1; $i <= $m; $i++) {
            $kia[$i] = $fliq[$i] / $fugsol[$i];
        }

        $iterf = 1;
        $tol = 1E-18;

        while (1) {
            $maxerror = 10;
            $while_flag = True;

            while ($maxerror > $tol) {
                $solid_equilibrium_results = $this->solid_equilibrium($m, $p, $t, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $rhoi, $cib, $kia, $fugsolpuro);

                if ($solid_equilibrium_results[0] == false) {
                    return $solid_equilibrium_results;
                }

                $fsola = $solid_equilibrium_results[0];
                $fliqa = $solid_equilibrium_results[1];
                $a = $solid_equilibrium_results[2];
                $xil = $solid_equilibrium_results[3];
                $xia = $solid_equilibrium_results[4];

                for ($i = 1; $i <= $m; $i++) {
                    if ($fsola[$i] != 0) {
                        $kia[$i] = $kia[$i] * ($fliqa[$i] / $fsola[$i]); #Corrección
                    }
                }

                for ($i = 1; $i <= $m; $i++) {
                    if ($fsola[$i] != 0) {
                        $errorf[$i] = pow((($fliqa[$i] / $fsola[$i]) - 1.0),  2); #Corrección
                    }
                }

                $maxerror = max($errorf);
                $iterf = $iterf + 1;

                if ($iterf > 200) {
                    $tol = $tol * 10;
                    $while_flag = False;
                    break;
                }

                $while_flag = True;
            }

            if ($while_flag) {
                break;
            }
        }

        if ($a < 0.000001) #Posible error $a por $s
        {
            $a = 0;
        }
        #Criterio de estabilidad
        for ($i = 1; $i <= $n; $i++) {
            $criterio1[$i] = $fliqa[$i] - $fsola[$i];
        }

        #Segunda Aproximación

        $preprocessing_results = $this->preprocessing($p, $t, $m, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $dadt = 0.0;

        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $m; $j++) {
                $da = sqrt($ati[$i] / $ati[$j]) * $daidt[$j] / 2.0 + sqrt($ati[$j] / $ati[$i]) * $daidt[$i] / 2.0;
                $dadt = $dadt + $xil[$i] * $xil[$j] * (1.0 - $cib[$i][$j]) * $da;
            }
        }

        for ($i = 1; $i <= $m; $i++) {
            $xig[$i] = 0;
        }

        $deno = $this->liquid_density($m, $p, $t, $pb, $zi, $zi, $xig, $mwi, $rhoi, $gapi, $cordo);

        #volumen líquido en el punto de burbuja
        $sumwt = 0; #Posible error sumwt por summwt
        for ($i = 1; $i <= $n; $i++) {
            $sumwt = $sumwt + $zi[$i] * $mwi[$i];
        }

        $vsl = $sumwt / $deno;

        #parámetro de sulubildiad en el punto de burbuja

        $num = ($vsl + (1.0 + pow(2.0, 0.5)) * $bmin);
        $den = ($vsl - (pow(2.0, 0.5) - 1) * $bmin);

        #Corrección
        $arg1 = 0;
        if ($den != 0) {
            $arg1 = $num / $den;
        }

        #dsl mixture

        $dsl = $dsl * (1 - $xia[$m]) + $dsa * $xia[$m];
        $num = $dslm - $dsa;
        $ax = 1.01597203520425;
        //if ($mwa <= 750) {
        //    $ax = 1.01597203520425;
        //} else {
        //    $ax = 0.45;
        //}

        $xsolu = exp(-$ax * pow($num, 2));

        if ($arg1 < 0.000000001) {
            $arg1 = 0.000000001;
        }

        $dsl = pow((1.0 / (pow(2.0, 1.5) * $bmin * $vsl) * ($amin - $t * $dadt) * log($arg1)), 0.5);
        $xsolu = exp(($vsl / ($r * $t)) * pow(($dsl - $dsa), 2.0));
        $num = $vsl;
        $den = $mwa * $xia[$m] / ($rhoa * 62.4);

        if ($den != 0) #Corrección
        {
            $arg1 = $num / $den;
        }

        if ($arg1 < 0.000001) {
            $arg1 = 0.000001;
        }
        $xvolu = exp(log($arg1) + 1 - $arg1);

        #Encontrando argunmento de solubilidad
        $arg2 = log($amax);
        $xref = (-$ax * $arg2); #referencia al punto de burbujeo
        if ($p >= $ponsetc) {
            $xnew = 0.00001;
        } else {
            $xnew = $xref - ($p - $pb) * ($xref) / ($ponsetc - $pb); #el nuevo punto de referncia
        }

        $wap = exp(-$ax * $xnew);
        if ($wap < 0.000001) {
            $wap = 0.0000001;
        }
        if ($wap > 1) {
            $wap = 0.9999;
        }

        return array($dsl, $deno, $wap, $a);
    }

    function asphaltenes_region_2($n, $p, $t, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat, $aro, $res, $asf, $rhoa, $mwa, $dsa, $cordo, $gapi, $pb, $ponsetc, $nmaxa, $amax)
    {

        $zz = array_fill(1, 3, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $lncf1 = array_fill(1, 100, 0);
        $lncf2 = array_fill(1, 100, 0);
        $lncf3 = array_fill(1, 100, 0);
        $fug1 = array_fill(1, 100, 0);
        $fug2 = array_fill(1, 100, 0);
        $fug3 = array_fill(1, 100, 0);
        $fg = array_fill(1, 100, 0);
        $fugpuro = array_fill(1, 100, 0);
        $fugsolpuro = array_fill(1, 100, 0);
        $fugsol = array_fill(1, 100, 0);
        $denpro = array_fill(1, 100, 0);
        $ziini = array_fill(1, 100, 0);
        $zia = array_fill(1, 100, 0);
        $zino = array_fill(1, 100, 0);
        $zit = array_fill(1, 100, 0);
        $fliq = array_fill(1, 100, 0);
        $fliqa = array_fill(1, 100, 0);
        $fsola = array_fill(1, 100, 0);
        $kia = array_fill(1, 100, 0);
        $errorf = array_fill(1, 100, 0);
        $criterio1 = array_fill(1, 100, 0);
        $xil = array_fill(1, 100, 0);
        $xia = array_fill(1, 100, 0);
        $xi = array_fill(1, 100, 0);
        $yi = array_fill(1, 100, 0);
        $pcia = array_fill(1, 100, 0);
        $pcina = array_fill(1, 100, 0);
        $rhop = array_fill(1, 100, 0);
        $pcibar = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);

        $liquid_steam_equilibrium_results =  $this->liquid_steam_equilibrium($p, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);

        $xi = $liquid_steam_equilibrium_results[0];
        $yi = $liquid_steam_equilibrium_results[1];
        $zliq = $liquid_steam_equilibrium_results[2];
        $zvap = $liquid_steam_equilibrium_results[3];
        $v = $liquid_steam_equilibrium_results[4];

        #Asfaltenos
        for ($i = 1; $i <= $n; $i++) {
            $zia[$i] = $xi[$i]; # Los demas componentes puros
            $pcia[$i] = $pci[$i];
        }

        $r = 10.73146;

        #se amplia en 1 mas
        $m = $n + 1;

        #teniendo encuenta el sara
        $zia[$m] = $xi[$n] * ($asf / 100);
        $zia[$n] = $xi[$n] * (($sat + $asf + $res) / 100);

        #el factor acéntrico se deja igual.
        $pcia[$m] = exp(9.77968 - 3.0755 * pow($mwa, 0.15));

        #Corrección
        for ($i = $n; $i <= $m; $i++) {
            $pcibar[$i] = 14.7 * ($pci[$i] * 0.0689475729);
            #densidad de pesados como función del peso de los asfaltenos

            if ($i == $n) {
                $rhop[$n] = 0.3915 + 0.0675 * log($mwi[$n]);
                $pcia[$n] = $pcibar[$i] * pow(($rhop[$n] / $rhoi[$n]), 3.46);
                $pcia[$n] = $pcia[$i] / 0.0689475729;
            } else if ($i == $m) {
                $rhop[$m] = 0.3915 + 0.0675 * log($mwi[$m]);
                #corrección de presión critica en funcion de la densidad solidos y el peso molucular
                $pcia[$m] = 0.2 * $pcia[$m] + 0.8 * (1891 * pow($mwa, (-0.7975)) * pow(($rhop[$i] / $rhoi[$i]), 3.46)) / 0.0689475729;

                #el promedio de la temperatura critica.. no se sabe nada mas
                $tci[$m] = (77.85 * pow($mwa, 0.4708) + $tci[$n]) / 2;
            }
        }
        for ($j = 1; $j <= $m; $j++) {
            $cib[$m][$j] = 0;
            $cib[$j][$m] = 0;
        }

        $cib[$m][$n] = -0.0078109 + 0.000038852 * $mwa;
        $cib[$n][$m] = -0.0078109 + 0.000038852 * $mwa;

        $preprocessing_results = $this->preprocessing($p, $t, $m, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $zz = $this->cubic($amay, $bmay);

        $fugacity_results = $this->fugacity($p, $t, $m, $zia, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);
        $lncf1 = $fugacity_results[0];
        $lncf2 = $fugacity_results[1];
        $lncf3 = $fugacity_results[2];
        $fug1 = $fugacity_results[3];
        $fug2 = $fugacity_results[4];
        $fug3 = $fugacity_results[5];

        $gibbs_stability_results = $this->gibbs_stability($m, $zia, $fug1, $fug2, $fug3, $zz);
        $fg = $gibbs_stability_results[0];
        $zg = $gibbs_stability_results[1];

        #Primera aproximación
        for ($i = 1; $i <= $m; $i++) {
            $fliq[$i] = $zia[$i] * $fg[$i];
        }

        $fugpuro = $this->pure_compound($m, $p, $t, $pcia, $tci, $wi);
        $fugsolpuro = $this->asphaltene_pure_fugacity_solid($m, $p, $t, $mwi, $fugpuro);

        for ($i = 1; $i <= $m; $i++) {
            $fugsol[$i] = $zia[$i] * $fugsolpuro[$i];
        }

        for ($i = 1; $i <= $m; $i++) {
            $kia[$i] = $fliq[$i] / $fugsol[$i];
        }

        $iterf = 1;
        $tol = 1E-18;

        while (1) {
            $maxerror = 10;
            $while_flag = True;

            while ($maxerror > $tol) {
                $solid_equilibrium_results = $this->solid_equilibrium($m, $p, $t, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $rhoi, $cib, $kia, $fugsolpuro);

                if ($solid_equilibrium_results[0] == false) {
                    return $solid_equilibrium_results;
                }

                $fsola = $solid_equilibrium_results[0];
                $fliqa = $solid_equilibrium_results[1];
                $a = $solid_equilibrium_results[2];
                $xil = $solid_equilibrium_results[3];
                $xia = $solid_equilibrium_results[4];

                for ($i = 1; $i <= $m; $i++) {
                    if ($fsola[$i] != 0) {
                        $kia[$i] = $kia[$i] * ($fliqa[$i] / $fsola[$i]); #Corrección
                    }
                }

                for ($i = 1; $i <= $m; $i++) {
                    if ($fsola[$i] != 0) {
                        $errorf[$i] = pow((($fliqa[$i] / $fsola[$i]) - 1.0),  2); #Corrección
                    }
                }

                $maxerror = max($errorf);
                $iterf = $iterf + 1;

                if ($iterf > 200) {
                    $tol = $tol * 10;
                    $while_flag = False;
                    break;
                }

                $while_flag = True;
            }

            if ($while_flag) {
                break;
            }
        }

        if ($a < 0.000001) #Posible error - $a por $s
        {
            $a = 0;
        }
        #Criterio de estabilidad
        for ($i = 1; $i <= $n; $i++) {
            $criterio1[$i] = $fliqa[$i] - $fsola[$i];
        }

        #Segunda Aproximación

        $preprocessing_results = $this->preprocessing($p, $t, $m, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $dadt = 0.0;

        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $m; $j++) {
                $da = sqrt($ati[$i] / $ati[$j]) * $daidt[$j] / 2.0 + sqrt($ati[$j] / $ati[$i]) * $daidt[$i] / 2.0;
                $dadt = $dadt + $xil[$i] * $xil[$j] * (1.0 - $cib[$i][$j]) * $da;
            }
        }

        $deno = $this->liquid_density($n, $p, $t, $pb, $xi, $xi, $yi, $mwi, $rhoi, $gapi, $cordo);
        #Volumen líquido en el punto de burbuja
        $sumwt = 0; #Posible error sumwt por summwt
        for ($i = 1; $i <= $n; $i++) {
            $sumwt = $sumwt + $xi[$i] * $mwi[$i];
        }
        $vsl = $sumwt / $deno;

        #Parámetro de sulubildiad en el punto de burbuja
        $num = ($vsl + (1.0 + pow(2.0, 0.5)) * $bmin);
        $den = ($vsl - (pow(2.0, 0.5) - 1) * $bmin);

        $arg1 = 0; #Corrección
        if ($den != 0) {
            $arg1 = $num / $den;
        }

        if ($arg1 < 0.000000001) {
            $arg1 = 0.000000001;
        }
        $dsl = pow((1.0 / (pow(2.0, 1.5) * $bmin * $vsl) * ($amin - $t * $dadt) * log($arg1)), 0.5);
        $dslm = $dsl * (1 - $xia[$m]) + $dsa * $xia[$m];
        $num = $dslm - $dsa;

        #Realmente depende del tamaño del agregado
        $ax = 1.01597203520425;
        //if ($mwa <= 750) {
        //    $ax = 1.01597203520425;
        //} else {
        //    $ax = 0.45;
        //}

        $xsolu = exp(-$ax * pow($num, 2));
        $den = $mwa * $xia[$m] / ($rhoa * 62.4);

        if ($den != 0) #Corrección
        {
            $arg1 = $num / $den;
        }

        if ($arg1 < 0.000001) {
            $arg1 = 0.000001;
        }

        $xvolu = exp(log($arg1) + 1 - $arg1);
        $wap = $xvolu * $xsolu;

        if ($wap < 0.000001) {
            $wap = 0;
        }
        if ($wap > 1) {
            $wap = 1;
        }
        if ($arg1 < 0.000001) {
            $arg1 = 0.000001;
        }

        $xvolu = exp(log($arg1) + 1 - $arg1);
        $wap = $xvolu * $xsolu;
        $arg2 = log($amax);
        $xref = (-$ax * $arg2); #referencia al punto de burbujeo

        if ($p >= $ponsetc) {
            $xnew = 0.00001;
        } else {
            $xnew = $xref - ($pb - $p) * ($xref) / ($pb - 14.7); #el nuevo punto de referncia
        }

        $wap = exp(-$ax * $xnew);
        if ($wap < 0.000001) {
            $wap = 0.0000001;
        }
        if ($wap > 1) {
            $wap = 0.9999;
        }

        return array($dsl, $deno, $wap, $a);
    }

    function pure_compound($n, $p, $t, $pci, $tci, $wi)
    {

        $ap = array_fill(1, 100, 0);
        $bp = array_fill(1, 100, 0);
        $mp = array_fill(1, 100, 0);
        $alfap = array_fill(1, 100, 0);
        $az = array_fill(1, 100, 0);
        $zz = array_fill(1, 100, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $fugpuro = array_fill(1, 100, 0);

        $r = 10.73146;
        for ($i = 1; $i <= $n; $i++) {
            $tri[$i] = $t / $tci[$i];
            $pri[$i] = $p / $pci[$i];
            $arg1 = $tri[$i];
            if ($arg1 < 0.000000001) {
                $arg1 = 0.000000001;
            }

            $ap[$i] = 0.45724 * (pow($r, 2) * pow($tci[$i], 2)) / ($pci[$i]);
            $bp[$i] = 0.0778 * ((10.73 * $tci[$i]) / ($pci[$i]));
            $mp[$i] = 0.3746 + 1.5423 * $wi[$i] - (0.2699 * pow($wi[$i], 2));
            $alfap[$i] = pow((1 + $mp[$i] * (1.0 - pow($arg1, 0.5))), 2);
            $amayu = ($ap[$i] * $alfap[$i] * $p) / pow(($r * $t), 2);
            $bmayu = ($bp[$i] * $p) / ($r * $t);
            $zz = $this->cubic($amayu, $bmayu);
            $fugp = $this->pure_fugacity($p, $t, $amayu, $bmayu, $zz[1]);
            $fugpuro[$i] = $fugp;
        }

        return $fugpuro;
    }

    function density_correction_x($n, $zi, $p_yto, $t_yto, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $pspline, $tspline, $deno1, $deno2, $deno3)
    {
        $xi = array_fill(1, 100, 0);
        $yi = array_fill(1, 100, 0);
        $denoaux = array_fill(1, 100, 0);
        $vliqaux = array_fill(1, 100, 0);

        #tspline [f]
        #pspline [f]
        $t_ytor = $t_yto + 460;
        $pb = $this->interpolation($t_ytor, 100, $tspline, $pspline);
        $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium(20, $t_ytor, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);
        $xi = $liquid_steam_equilibrium_results[0];
        $yi = $liquid_steam_equilibrium_results[1];
        $zliq = $liquid_steam_equilibrium_results[2];
        $zvap = $liquid_steam_equilibrium_results[3];
        $v = $liquid_steam_equilibrium_results[4];

        $mo = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $mo = $mo + $mwi[$i] * $xi[$i];
        }

        if ($mo == 0) {
            $mo = 0;
            for ($i = 1; $i <= $n; $i++) {
                $xi[$i] = $zi[$i];
                $mo = $mo + $xi[$i] * $mwi[$i];
            }
        }

        #densidad del petróleo
        for ($i = 1; $i <= $n; $i++) {
            $denoaux[$i] = $rhoi[$i] * 62.4;
            $vliqaux[$i] = $mwi[$i] * $xi[$i] / $denoaux[$i];
        }

        #volumen de líquido
        $vliq = 0;
        for ($i = 1; $i <= $n; $i++) {
            $vliq = $vliq + $vliqaux[$i];
        }

        #densidad  (lbm/ft3)
        $deno = $mo / $vliq;
        $dif1 = $deno - ($deno1 * 62.4);
        $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium($pb, $t_ytor, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);
        $xi = $liquid_steam_equilibrium_results[0];
        $yi = $liquid_steam_equilibrium_results[1];
        $zliq = $liquid_steam_equilibrium_results[2];
        $zvap = $liquid_steam_equilibrium_results[3];
        $v = $liquid_steam_equilibrium_results[4];

        $mo = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $mo = $mo + $mwi[$i] * $xi[$i];
        }

        if ($mo == 0) {
            $mo = 0;
            for ($i = 1; $i <= $n; $i++) {
                $xi[$i] = $zi[$i];
                $mo = $mo + $xi[$i] * $mwi[$i];
            }
        }


        #densidad del petroleo
        for ($i = 1; $i <= $n; $i++) {
            $denoaux[$i] = $rhoi[$i] * 62.4;
            $vliqaux[$i] = $mwi[$i] * $xi[$i] / $denoaux[$i];
        }

        #volumen de líquido
        $vliq = 0;
        for ($i = 1; $i <= $n; $i++) {
            $vliq = $vliq + $vliqaux[$i];
        }

        #densidad  (lbm/ft3)
        $deno = $mo / $vliq;
        $dif2 = $deno - ($deno2 * 62.4);
        if ($p_yto < $pb) {
            $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium($p_yto, $t_ytor, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);
            $xi = $liquid_steam_equilibrium_results[0];
            $yi = $liquid_steam_equilibrium_results[1];
            $zliq = $liquid_steam_equilibrium_results[2];
            $zvap = $liquid_steam_equilibrium_results[3];
            $v = $liquid_steam_equilibrium_results[4];
        }

        $mo = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $mo = $mo + $mwi[$i] * $xi[$i];
        }

        if ($mo == 0) {
            $mo = 0;
            for ($i = 1; $i <= $n; $i++) {
                $xi[$i] = $zi[$i];
                $mo = $mo + $xi[$i] * $mwi[$i];
            }
        }

        #densidad del petróleo
        for ($i = 1; $i <= $n; $i++) {
            $denoaux[$i] = $rhoi[$i] * 62.4;
            $vliqaux[$i] = $mwi[$i] * $xi[$i] / $denoaux[$i];
        }

        #volumen de líquido
        $vliq = 0;
        for ($i = 1; $i <= $n; $i++) {
            $vliq = $vliq + $vliqaux[$i];
        }

        #densidad  (lbm/ft3)
        $deno = $mo / $vliq;
        $dif3 = $deno - ($deno3 * 62.4);

        $corr_do = ($dif1 + $dif2 + $dif3) / 3.0;

        return $corr_do;
    }

    function liquid_density($n, $p, $t, $pb, $zi, $xi, $yi, $mwi, $sgi, $gapi, $cordo)
    {
        $yg = 0;
        $yo = 0;
        for ($i = 1; $i <= $n; $i++) {
            $yo = $yo + $xi[$i] * $sgi[$i];
            $yg = $yg + $yi[$i] * $sgi[$i];
        }

        if ($yg == 0) {
            $yg = 141.5 / (131.5 + $gapi);
        }

        $deno = $yo * 62.4;
        #correlacion  vazquez y beggs

        if ($gapi > 30) {
            $c1 = 0.0178;
            $c2 = 1.187;
            $c3 = 23.931;
        } else {
            $c1 = 0.0362;
            $c2 = 1.0937;
            $c3 = 25.724;
        }

        if ($p > $pb) {
            $rs = $c1 * $yg * pow($pb, $c2) * exp($c3 * ($gapi / $t));
        } else {
            $rs = $c1 * $yg * pow($p, $c2) * exp($c3 * ($gapi / $t));
        }

        #compresibilidad del crudo
        if ($p > $pb) # petrosky-farshad's correlation - t(°r)
        {
            $co = (0.0000001705) * pow($rs, 0.69357) * pow($yg, 0.1885) * pow($gapi, 0.3272) * pow(($t - 400), 0.6729) * pow($p, (-0.5906));
        } else {
            if ($pb == 0) #mccain
            {
                $a = -7.633 - (1.497 * log($p)) + (1.115 * log($t)) + (0.533 * log($gapi)) + (0.184 * log($rs + 1));
            } else {
                $a = -7.573 - (1.45 * log($p)) - (0.383 * log($pb)) + (1.402 * log($t)) + (0.256 * log($gapi)) + (0.449 * log($rs + 1));
            }
            $co = exp($a);
        }

        if ($gapi > 30) {
            $c1 = 0.000467;
            $c2 = 0.000011;
            $c3 = 0.000000001337;
        } else {
            $c1 = 0.0004677;
            $c2 = 0.00001751;
            $c3 = -0.00000001811;
        }

        $bo = 1 + ($c1 * $rs) + (($t - 519.67) * ($gapi / $yg) * ($c2 + $c3 * $rs));
        if ($p > $pb) {
            $bo = $bo * (exp($co * ($pb - $p)));
        }

        $c1 = 0.00009662 * ($p + 14.7) - 0.08219027;
        $c2 = 98.131558211679 - (1.18271982332515e-04) * $p + (8.2045841608832e-10) * pow($p, 2.5);
        if ($deno != 0) {
            $ddeno = $c1 * exp($c2 / $deno);
            $c3 = ($deno + $ddeno);
            $c4 = pow(10, (-0.764 * $c3));
            $tdeno = ($t - 520.0) * (0.0133 + 152.4 * pow($c3, (-2.45))) - (pow(($t - 520.0), 2)) * (0.0000081 - 0.0622 * $c4);
            $deno = $c3 - $tdeno - $cordo;
        }

        if ($p > $pb) {
            $deno = $deno * exp($co * ($p - $pb));
        }

        return  $deno;
    }

    function solid_equilibrium($n, $p, $t, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $kis, $fugsolpuro)
    {
        $zz = array_fill(1, 3, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $lncf1 = array_fill(1, 100, 0);
        $lncf2 = array_fill(1, 100, 0);
        $lncf3 = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);
        $fug1 = array_fill(1, 100, 0);
        $fug2 = array_fill(1, 100, 0);
        $fug3 = array_fill(1, 100, 0);

        $solid_raschford_results = $this->solid_raschford($n, $zi, $kis);
        if ($solid_raschford_results[0] == false) {
            return $solid_raschford_results;
        }
        $xil = $solid_raschford_results[0];
        $xis = $solid_raschford_results[1];
        $s = $solid_raschford_results[2];

        #parte del líquido
        $preprocessing_results = $this->preprocessing($p, $t, $n, $xil, $mwi, $pci, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $zz = $this->cubic($amay, $bmay);

        $fugacity_results = $this->fugacity($p, $t, $n, $xil, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);
        $lncf1 = $fugacity_results[0];
        $lncf2 = $fugacity_results[1];
        $lncf3 = $fugacity_results[2];
        $fug1 = $fugacity_results[3];
        $fug2 = $fugacity_results[4];
        $fug3 = $fugacity_results[5];

        $gibbs_stability_results = $this->gibbs_stability($n, $xil, $fug1, $fug2, $fug3, $zz);

        $fliq = $gibbs_stability_results[0];
        $zl = $gibbs_stability_results[1];

        #parte del solido
        for ($i = 1; $i <= $n; $i++) {
            $fugsol[$i] = $xis[$i] * $fugsolpuro[$i];

            if ($i > 1) #Corrección
            {
                if ($fugsol[$i] > $fugsol[$i - 1]) {
                    $fugsol[$i] = 0; #Corrección
                }
            }
        }

        return array($fugsol, $fliq, $s, $xil, $xis);
    }

    function stability($n, $zi, $mwi, $rhoi, $pci, $tci, $vci, $wi, $si, $cib, $pspline, $tspline, $tc, $pc, $nt, $temp, $gapi, $cordo, $mwa, $rhoa, $nmaxa, $asf, $res, $sat, $aro, $act_plus, $act_dsa, $asphaltenes_data)
    {

        $zis = array_fill(0, 100, 0);
        $ziini = array_fill(0, 100, 0);
        $pburb = array_fill(0, 100, 0);
        $pw_aps_1 = array_fill(0, 100, 0);
        $wat_aps_1 = array_fill(0, 100, 0);
        $pw_aps_2 = array_fill(0, 100, 0);
        $wat_aps_2 = array_fill(0, 100, 0);
        $dsl_aps_1 = array_fill(0, 100, 0);
        $wap_aps_1 = array_fill(0, 100, 0);
        $a_aps_1 = array_fill(0, 100, 0);
        $dsl_aps_2 = array_fill(0, 100, 0);
        $wap_aps_2 = array_fill(0, 100, 0);
        $a_aps_2 = array_fill(0, 100, 0);
        $s_aps_2 = array_fill(0, 100, 0);
        $s_aps_1 = array_fill(0, 100, 0);
        $pa_aps_1 = array_fill(0, 100, 0);
        $pa_aps_2 = array_fill(0, 100, 0);
        $pa_salida = array_fill(0, 200, null); #Resultados 
        $wap_salida = array_fill(0, 200, 0);
        $a_salida = array_fill(0, 200, null); #Resultados 
        $dsl_salida = array_fill(0, 200, 0);
        $asfaps_1 = array_fill(0, 100, 0);
        $pwonset = array_fill(0, 100, 0);
        $waonset = array_fill(0, 100, 0);
        $p_enc = array_fill(0, 50, 0);
        $p_deb = array_fill(0, 50, 0);
        $p_salida = array_fill(0, 200, null); #Resultados
        $wat_salida = array_fill(0, 200, null); #Resultados
        $s_salida = array_fill(0, 100, null); #Resultados
        $p_spline = array_fill(0, 100, 0);
        $w_spline = array_fill(0, 100, 0);

        $onset_temp_data = [];
        $onset_was_data = [];
        $onset_a_data = [];
        $onset_ponset_data = [];
        $onset_pburbuja_data = [];
        $onset_ponset_c_data = [];

        #Incio
        $flag_s = 1; #sólidos
        $flag_a = 1; #asfaltenos

        #Bandera plus
        if ($act_plus == 0)        #no hay asfaltenos
        {
            $flag_a = 0;
        }

        #bandesa dsa
        #Opción asfaltenos - Asphaltenes_Data
        if ($act_dsa == 1) {
            $h_c = $asphaltenes_data[0];
            $o_c = $asphaltenes_data[1];
            $n_c = $asphaltenes_data[2];
            $s_c = $asphaltenes_data[3];
            $faro = $asphaltenes_data[4];
            $vca = $asphaltenes_data[5];
        }

        for ($i = 1; $i <= $nt; $i++) {
            $taps_f = $temp[$i] - 460;
            $pburb[$i] = $this->interpolation($taps_f, 100, $tspline, $pspline);
        }

        $ponset = 0;

        for ($i = 1; $i <= $nt; $i++) {

            for ($j = 1; $j <= 100; $j++) {
                $s_salida[$j] = 0;
            }

            $taps = $temp[$i];

            #región por encima del punto de burbuja

            $ns = 1;

            // if ($flag_s == 1) {
            //     $ns = 1;
            //     for ($j = 1; $j <= 20; $j++) {
            //         if ($taps < ($tc + 460)) {
            //             $paps = $p_enc[$j];
            //             $solid_region_1_results = $this->solid_region_1($n, $paps, $taps, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat);
                        
            //                if ($solid_region_1_results[0] == false) {
            //                     return $solid_region_1_results;
            //                }
                            
            //                $wat = $solid_region_1_results[0];
            //             $s = $solid_region_1_results[1];
            //             $pw_aps_1[$ns] = $paps;
            //             $wat_aps_1[$ns] = $wat;
            //             $s_aps_1[$ns] = $s;
            //             $ns = $ns + 1;
            //             if ($s == 0) {
            //                 $j = 20;
            //             }
            //         }
            //     }

                // $nd = 1;
                // for ($j = 1; $j <= 20; $j++) {
                //     $paps = $p_deb[$j];
                //     if ($paps > 14.7) {
                //         if ($taps < ($tc + 460)) {
                //             $solid_region_2_results = $this->solid_region_2($paps, $taps, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat);
                            
                //             if ($solid_region_2_results[0] == false) {
                //                 return $solid_region_2_results;
                //             }
                            
                //             $wat = $solid_region_2_results[0];
                //             $s = $solid_region_2_results[1];
                //             $pw_aps_2[$nd] = $paps;
                //             $wat_aps_2[$nd] = $wat;
            //                 $s_aps_2[$nd] = $s;
            //                 $nd = $nd + 1;
            //             }
            //         }
            //     }

            //     #Impresiones - Revisar orden
            //     #Resultados - Solidos WAT, S
            //     for ($j = 1; $j <= $ns; $j++) #Revisar
            //     {
            //         $p_salida[$j] = $pw_aps_1[$ns - $j];
            //         $wat_salida[$j] = $wat_aps_1[$ns - $j];
            //         $s_salida[$j] = $s_aps_1[$ns - $j];
            //     }

            //     for ($j = 1; $j <= $nd - 1; $j++) #Revisar
            //     {
            //         $p_salida[$ns - 1 + $j] = $pw_aps_2[$j];
            //         $wat_salida[$ns - 1 + $j] = $wat_aps_2[$j];
            //         $s_salida[$ns - 1 + $j] = $s_aps_2[$j];
            //         if ($s == 0) {
            //             $j = 20;
            //         };
            //     }
            //     #array_push($wat_solid_results, array($p_salida,$wat_salida)); #Es la misma columna de presión para solid_wat y solid_s
            //     $wat_solid_results[$i] = array(array_filter($p_salida), array_filter($wat_salida));
            //     $s_solid_results[$i] = array(array_filter($p_salida), array_filter($s_salida));

            //     #array_push($s_solid_results, array($p_salida,$s_salida)); 
            // }

            $at = 0;
            $vma = $mwa / $rhoa;
            if ($flag_a == 1) {
                $nsa = 1;
                $t = 1; #Posible error
                if ($act_dsa == 1) #Posible error - act_dsa x opc_dsa
                {
                    $dsa = (140 * $h_c + 614 * $o_c + 235 * $n_c + 460 * $s_c + 136 * $faro) / $vca;
                } else {
                    $dsa = 221 * (1 - 0.00001 * $t);
                    if ($ponset != $pburb[$i - 1]) {
                        $vma = 1.493 * (pow($mwa, 0.936));
                        $at = 0.579 - 0.00075 * ($taps * 0.5556);
                        $dsa = pow(((1000 * $at * $mwa / $vma) / 0.00689475729), 1/2);
                    }else{
                        $dsa = $dsa;
                    }
                }

                
                if ( $i > 1 ) {
                    if ( $ponset != $pburb[$i-1] ) {
                        $asphaltenes_maximum_results = $this->asphaltenes_maximum($n, $taps, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat, $aro, $res, $asf, $rhoa, $mwa, $dsa, $nmaxa, $cordo, $gapi, $pburb[$i], $mwi, $zi); #Se agrega mw_data (mwi) y zi para corrección de solubilidad
                        
                        if ($asphaltenes_maximum_results[0] == false) {
                            return $asphaltenes_maximum_results;
                        }
                        
                        $max_wap = $asphaltenes_maximum_results[0];
                        $max_a = $asphaltenes_maximum_results[1];
                        $ponset = $asphaltenes_maximum_results[2];
                        $ponsetc = $asphaltenes_maximum_results[3];
                    } else {
                        $max_wap = 0;
                        $max_a = 1;
                        $ponset = $pburb[$i];
                        $ponsetc = $pburb[$i];
                    }
                } elseif ( $i == 1) {
                    $asphaltenes_maximum_results = $this->asphaltenes_maximum($n, $taps, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat, $aro, $res, $asf, $rhoa, $mwa, $dsa, $nmaxa, $cordo, $gapi, $pburb[$i], $mwi, $zi); #Se agrega mw_data (mwi) y zi para corrección de solubilidad
                    
                    if ($asphaltenes_maximum_results[0] == false) {
                        return $asphaltenes_maximum_results;
                    }
                    
                    $max_wap = $asphaltenes_maximum_results[0];
                    $max_a = $asphaltenes_maximum_results[1];
                    $ponset = $asphaltenes_maximum_results[2];
                    $ponsetc = $asphaltenes_maximum_results[3];
                }

                $flag_xxx = 0;

                for ($j = 1; $j <= 50; $j++) {
                    if  ( $j == 1 ) {
                        $p_enc[$j] = $pburb[$i] + 1;
                        $p_deb[$j] = $pburb[$i] - 1;
                    } elseif ( ($pburb[$i] + 100 * ($j - 1)) < $ponsetc ) {
                        $p_enc[$j] = $pburb[$i] + 100 * ($j-1);
                        $p_deb[$j] = $pburb[$i] - 100 * ($j-1);
                    } elseif ( (($pburb[$i] + 100 * ($j - 1)) >= $ponsetc) && $flag_xxx == 0 ) {
                        $p_enc[$j] = $ponsetc;
                        $p_deb[$j] = $pburb[$i] - 100 * ($j-1);
                        $flag_xxx = 1;
                    } else {
                        $p_enc[$j] = $pburb[$i] + 100 * ($j-1);
                        $p_deb[$j] = $pburb[$i] - 100 * ($j-1);
                    }
                }

                for ($j = 1; $j <= 50; $j++) {
                    if ($taps < ($tc + 460)) {
                        $paps = $p_enc[$j];
                        $asphaltenes_region_1_results = $this->asphaltenes_region_1($n, $paps, $taps, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat, $aro, $res, $asf, $rhoa, $mwa, $dsa, $cordo, $gapi, $pburb[$i], $ponsetc, $nmaxa, $max_a);

                        if ($asphaltenes_region_1_results[0] == false) { 
                            return $asphaltenes_region_1_results;
                        }

                        $dsl = $asphaltenes_region_1_results[0]; #Posible error -Revisar decimales dsl
                        $deno = $asphaltenes_region_1_results[1];
                        $wap = $asphaltenes_region_1_results[2];
                        $a = $asphaltenes_region_1_results[3];

                        $pa_aps_1[$nsa] = $paps;
                        $dsl_aps_1[$nsa] = $dsl;
                        $wap_aps_1[$nsa] = $wap;
                        $a_aps_1[$nsa] = $a;
                        $nsa = $nsa + 1;
                        if ($a == 0) {
                            $a = 50;
                        }
                    }
                }

                $nda = 1;
                $wap = 0;
                $dsl = 0;
                $a = 0;
                for ($j = 1; $j <= 50; $j++) {
                    $paps = $p_deb[$j];
                    if ($paps > 14.7) {
                        if ($taps < ($tc + 460)) {
                            $asphaltenes_region_2_results = $this->asphaltenes_region_2($n, $paps, $taps, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat, $aro, $res, $asf, $rhoa, $mwa, $dsa, $cordo, $gapi, $pburb[$i], $ponsetc, $nmaxa, $max_a);
                            
                            if ($asphaltenes_region_2_results[0] == false) {
                                return $asphaltenes_region_2_results;
                            }
                            
                            $dsl = $asphaltenes_region_2_results[0]; #Revisar variación decimales
                            $deno = $asphaltenes_region_2_results[1];
                            $wap = $asphaltenes_region_2_results[2];
                            $a = $asphaltenes_region_2_results[3];
                            $pa_aps_2[$nda] = $paps;
                            $dsl_aps_2[$nda] = $dsl;
                            $wap_aps_2[$nda] = $wap;
                            $a_aps_2[$nda] = $a;
                            $nda = $nda + 1;
                        }
                    }
                }

                #impresiones - Revisar
                for ($j = 1; $j <= $nsa; $j++) #Posible error
                {
                    $pa_salida[$j] = $pa_aps_1[$nsa - $j];
                    $wap_salida[$j] = $wap_aps_1[$nsa - $j];
                    $dsl_salida[$j] = $dsl_aps_1[$nsa - $j];
                    $a_salida[$j] = $a_aps_1[$nsa - $j];
                }

                for ($j = 1; $j <= $nda - 1; $j++) {
                    $pa_salida[$nsa - 1 + $j] = $pa_aps_2[$j]; #Posible error, se agregó -1
                    $wap_salida[$nsa - 1 + $j] = $wap_aps_2[$j];
                    $a_salida[$nsa - 1 + $j] = $a_aps_2[$j];
                    if ($a == 0) {
                        $j = 50;
                    }
                }
                $a_solid_results[$i] = array(array_filter($pa_salida), array_filter($wap_salida));

                #Revisar
                #for j = 1 to nda - 1
                #    worksheets("solidos_a").cells(nsa + j, (i - 1) * 2 + 3).value = pa_aps_2[$j]
                #    worksheets("solidos_a").cells(nsa + j, i * 2 + 2).value = wap_aps_2[$j]
                #next j
            }


            // $ndatos = $ns + $nd;
            // $sums = 0;
            // for ($j = 1; $j <= $ndatos - 1; $j++) {
            //     $sums = $sums + $s_salida[$j];
            // }

            // if ($sums == 0) {
            //     $flag_s = 0;  #flag de salida ya no hay mas solidos saturados
            // }

            #asfaltenos
            #estimando volumen de liquido
            array_push($onset_temp_data, $taps - 460);
            array_push($onset_was_data, $max_wap);
            array_push($onset_a_data, $max_a);
            array_push($onset_ponset_data, $ponset);
            array_push($onset_pburbuja_data, $pburb[$i]);
            array_push($onset_ponset_c_data, $ponsetc);
        }

        $onset_data = array($onset_temp_data, $onset_was_data, $onset_a_data, $onset_ponset_data, $onset_pburbuja_data, $onset_ponset_c_data);

        #Resultados onset, solid_wat, solid_s y solid_a
        // return array($onset_data, $wat_solid_results, $s_solid_results, $a_solid_results);
        return array($onset_data, [], [], $a_solid_results);
    }

    function asphaltenes_maximum($n, $t, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat, $aro, $res, $asf, $rhoa, $mwa, $dsa, $nmaxa, $cordo, $gapi, $pb)
    {
        $zz = array_fill(1, 3, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $lncf1 = array_fill(1, 100, 0);
        $lncf2 = array_fill(1, 100, 0);
        $lncf3 = array_fill(1, 100, 0);
        $fug1 = array_fill(1, 100, 0);
        $fug2 = array_fill(1, 100, 0);
        $fug3 = array_fill(1, 100, 0);
        $fg = array_fill(1, 100, 0);
        $fugpuro = array_fill(1, 100, 0);
        $fugsolpuro = array_fill(1, 100, 0);
        $fugsol = array_fill(1, 100, 0);
        $denpro = array_fill(1, 100, 0);
        $ziini = array_fill(1, 100, 0);
        $zia = array_fill(1, 100, 0);
        $zino = array_fill(1, 100, 0);
        $zit = array_fill(1, 100, 0);
        $fliq = array_fill(1, 100, 0);
        $fliqa = array_fill(1, 100, 0);
        $fsola = array_fill(1, 100, 0);
        $kia = array_fill(1, 100, 0);
        $errorf = array_fill(1, 100, 0);
        $criterio1 = array_fill(1, 100, 0);
        $xil = array_fill(1, 100, 0);
        $xia = array_fill(1, 100, 0);
        $xi = array_fill(1, 100, 0);
        $yi = array_fill(1, 100, 0);
        $pcia = array_fill(1, 100, 0);
        $pcina = array_fill(1, 100, 0);
        $rhop = array_fill(1, 100, 0);
        $pcibar = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);

        $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium($pb, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);

        $xi = $liquid_steam_equilibrium_results[0];
        $yi = $liquid_steam_equilibrium_results[1];
        $zliq = $liquid_steam_equilibrium_results[2];
        $zvap = $liquid_steam_equilibrium_results[3];
        $v = $liquid_steam_equilibrium_results[4];

        #Asfaltenos
        for ($i = 1; $i <= $n; $i++) {
            $zia[$i] = $zi[$i]; #los demas componentes puros
            $pcia[$i] = $pci[$i];
        }

        $r = 10.73146;

        #se amplia en 1 mas
        $m = $n + 1;

        #teniendo encuenta el sara
        $zia[$m] = $xi[$n] * ($asf / 100);
        $zia[$n] = $xi[$n] * (($sat + $asf + $res) / 100);

        #llenando la siguiente tabla
        $mwi[$m] = $mwa;
        $rhoi[$m] = $rhoa;
        $pci[$m] = $pci[$n];
        $tci[$m] = $tci[$n];
        $vci[$m] = $vci[$n];
        $wi[$m] = $wi[$n];
        $si[$m] = $si[$n];

        #el factor acentrico se deja igual.
        $pcia[$m] = exp(9.77968 - 3.0755 * pow($mwa, 0.15));

        #correccion
        for ($i = $n; $i <= $m; $i++) {
            $pcibar[$i] = 14.7 * ($pci[$i] * 0.0689475729);

            #densidad de pesados como función del peso de los asfaltenos
            if ($i == $n) {
                $rhop[$n] = 0.3915 + 0.0675 * log($mwi[$n]);
                $pcia[$n] = $pcibar[$i] * pow(($rhop[$n] / $rhoi[$n]), 3.46);
                $pcia[$n] = $pcia[$i] / 0.0689475729;
            } else if ($i == $m) {
                $rhop[$m] = 0.3915 + 0.0675 * log($mwi[$m]);
                #corrección de presión critica en funcion de la densidad solidos y el peso molucular
                $pcia[$m] = 0.2 * $pcia[$m] + 0.8 * (1891 * pow($mwa, -0.7975) * (pow($rhop[$i] / $rhoi[$i], 3.46))) / 0.0689475729;
                #el promedio de la temperatura critica.. no se sabe nada mas
                $tci[$m] = (77.85 * pow($mwa, 0.4708) + $tci[$n]) / 2;
            }
        }

        for ($j = 1; $j <= $m; $j++) {
            $cib[$m][$j] = 0;
            $cib[$j][$m] = 0;
        }
        $cib[$m][$n] = -0.0078109 + 0.000038852 * $mwa;
        $cib[$n][$m] = -0.0078109 + 0.000038852 * $mwa;

        $preprocessing_results = $this->preprocessing($pb, $t, $m, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $zz = $this->cubic($amay, $bmay);

        $fugacity_results = $this->fugacity($pb, $t, $m, $zia, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);

        $lncf1 = $fugacity_results[0];
        $lncf2 = $fugacity_results[1];
        $lncf3 = $fugacity_results[2];
        $fug1 = $fugacity_results[3];
        $fug2 = $fugacity_results[4];
        $fug3 = $fugacity_results[5];


        $gibbs_stability_results = $this->gibbs_stability($m, $zia, $fug1, $fug2, $fug3, $zz);

        $fg = $gibbs_stability_results[0];
        $zg = $gibbs_stability_results[1];

        #primera aproximacion
        for ($i = 1; $i <= $m; $i++) {
            $fliq[$i] = $zia[$i] * $fg[$i];
        }

        $fugpuro = $this->pure_compound($m, $pb, $t, $pcia, $tci, $wi);
        $fugsolpuro = $this->asphaltene_pure_fugacity_solid($m, $pb, $t, $mwi, $fugpuro);

        for ($i = 1; $i <= $m; $i++) {
            $fugsol[$i] = $zia[$i] * $fugsolpuro[$i];
        }

        for ($i = 1; $i <= $m; $i++) {
            $kia[$i] = $fliq[$i] / $fugsol[$i];
        }

        $iterf = 1;
        $tol = 1E-18;

        while (1) {
            $maxerror = 10;
            $while_flag = True;

            while ($maxerror > $tol) {
                $solid_equilibrium_results = $this->solid_equilibrium($m, $pb, $t, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $rhoi, $cib, $kia, $fugsolpuro);
                
                if ($solid_equilibrium_results[0] == false) {
                    return $solid_equilibrium_results;
                }

                $fsola = $solid_equilibrium_results[0];
                $fliqa = $solid_equilibrium_results[1];
                $a = $solid_equilibrium_results[2];
                $xil = $solid_equilibrium_results[3];
                $xia = $solid_equilibrium_results[4];

                for ($i = 1; $i <= $m; $i++) {
                    if ($fsola[$i] != 0) #Corrección
                    {
                        $kia[$i] = $kia[$i] * ($fliqa[$i] / $fsola[$i]);
                    }
                }

                for ($i = 1; $i <= $m; $i++) {
                    if ($fsola[$i] != 0) #Corrección
                    {
                        $errorf[$i] = pow((($fliqa[$i] / $fsola[$i]) - 1.0),  2);
                    }
                }

                $maxerror = max($errorf);
                $iterf = $iterf + 1;

                if ($iterf > 200) {
                    $tol = $tol * 10;
                    $while_flag = False;
                    break;
                }

                $while_flag = True;
            }

            if ($while_flag) {
                break;
            }
        }

        if ($a < 0.000001) {
            $a = 0;
        }

        #Criterio de estabilidad
        for ($i = 1; $i <= $n; $i++) {
            $criterio1[$i] = $fliqa[$i] - $fsola[$i];
        }

        $preprocessing_results = $this->preprocessing($pb, $t, $m, $zia, $mwi, $pcia, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $dadt = 0.0;

        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $m; $j++) {
                $da = sqrt($ati[$i] / $ati[$j]) * $daidt[$j] / 2.0 + sqrt($ati[$j] / $ati[$i]) * $daidt[$i] / 2.0;
                $dadt = $dadt + $xil[$i] * $xil[$j] * (1.0 - $cib[$i][$j]) * $da;
            }
        }

        $deno = $this->liquid_density($n, $pb, $t, $pb, $xi, $xi, $yi, $mwi, $rhoi, $gapi, $cordo);

        #volumen líquido en el punto de burbuja
        $sumwt = 0; #Posible error sumwt por summwt
        for ($i = 1; $i <= $n; $i++) {
            $sumwt = $sumwt + $xi[$i] * $mwi[$i];
        }

        $vsl = $sumwt / $deno;

        #parámetro de sulubildiad en el punto de burbuja

        $num = ($vsl + (1.0 + pow(2.0, 0.5)) * $bmin);
        $den = ($vsl - (pow(2.0, 0.5) - 1) * $bmin);
        $arg1 = $num / $den;

        if ($arg1 < 0.000000001) {
            $arg1 = 0.000000001;
        }

        $solubility_correction_results = $this->solubility_correction($pb, $t, $n, $mwi, $zi);
        $solco2 = $solubility_correction_results[0];
        $solch4 = $solubility_correction_results[1];
        $soln2 = $solubility_correction_results[2];

        $dsl = pow((1.0 / (pow(2.0, 1.5) * $bmin * $vsl) * ($amin - $t * $dadt) * log($arg1)), 0.5);
        $dslc = pow((1.0 / (pow(2.0, 1.5) * $bmin * $vsl) * ($amin - $t * $dadt) * log($arg1)), 0.5) + $solco2 + $solch4 + $soln2;
        $xsolu = exp(($vsl / ($r * $t)) * pow(($dsl - $dsa), 2.0));
        $den = $mwa / ($rhoa * 62.4);
        $arg1 = $num / $den;
        if ($arg1 < 0.000001) {
            $arg1 = 0.000001;
        }
        $xvolu = exp(log($arg1) + 1 - $arg1);
        $wap = $xvolu * $xsolu;
        $maxa = 1 - $a;

        if ($wap < 0.000001) {
            $wap = 0;
        }
        if ($wap > 1) {
            $wap = 1;
        }
        if ($arg1 < 0.000001) {
            $arg1 = 0.000001;
        }
        $xvolu = exp(log($arg1) + 1 - $arg1);
        $wap = 1 - $xvolu * $xsolu;
        if ($wap < 0.000001) {
            $wap = 0.0000001;
        }
        if ($wap > 1) {
            $wap = 0.9999;
        }

        #resultado de extrapolar la derivada de la fraccion soluble de hirscberg (se usa una funcion cuadratica de ajuste)
        if ($t >= 460) {
            $ponset = 6.83 * $mwa - ($vsl * $nmaxa) * ($t - 459.4) + $dsl * pow(log($t - 459.4), 2);
            $ponsetc = 6.83 * $mwa - ($vsl * $nmaxa) * ($t - 459.4) + $dslc * pow(log($t - 459.4), 2);
            if ($ponset < $pb) {
                $ponset = $pb;
            }
            if ($ponsetc < $pb) {
                $ponsetc = $pb;
            }
        } else {
            $ponset = 10000;
            $ponsetc = 10000;
        }

        /* POSIBLE MEJORA
        if ($ponset == $pb) {
            $maxa = 1;
        }*/

        return array($wap, $maxa, $ponset, $ponsetc);
    }

    function solubility_correction($p, $t, $n, $mw_data, $molar_fraction_data) #Traer desde components data
    {
        for ($i = 0; $i <= $n; $i++) {
            if ($mw_data[$i] >= 44.009 and $mw_data[$i] <= 44.02) {
                $co2 = $molar_fraction_data[$i]; #Revisar!
                break;
            } else {
                $co2 = 0;
            }
        }

        for ($i = 0; $i <= $n; $i++) #Revisar índices
        {
            if ($mw_data[$i] >= 16.042 and $mw_data[$i] <= 16.044) {
                $c1 = $molar_fraction_data[$i]; #Revisar!
                break;
            } else {
                $c1 = 0;
            }
        }

        for ($i = 0; $i <= $n; $i++) #Revisar índices
        {
            if ($mw_data[$i] >= 28.013 and $mw_data[$i] <= 28.015) {
                $n2 = $molar_fraction_data[$i]; #Revisar!
                break;
            } else {
                $n2 = 0;
            }
        }

        $deltaco = 0;
        $deltac1 = 0;
        $deltan2 = 0;
        $nd = 0;

        #corrección co2 y ch4
        if ($t < 528) {
            $nd = (528 - $t) / 18;
            $deltaco = 7858.6 - (2782.63 * log($p)) + (329.02 * pow(log($p), 2)) - (12.98 * pow(log($p), 3));
            $deltac1 = 42.56 - (28.31 * log($p)) + (5.06 * pow(log($p), 2)) - (0.27 * pow(log($p), 3));
            $solco = (-4448.735 + (1650.99 * log($p)) - (198.36 * pow(log($p), 2)) + (8.04 * pow(log($p), 3))) + ($nd * $deltaco);
            $solc1 = (3838.699 - (1504.41 * log($p)) + (192.99 * pow(log($p), 2)) - (7.99 * pow(log($p), 3))) + ($nd * $deltac1);
        } else if ($t >= 528 and $t < 546) {
            $solco = -4448.735 + (1650.99 * log($p)) - (198.36 * pow(log($p), 2)) + (8.04 * pow(log($p), 3));
            $solc1 = 3838.699 - (1504.41 * log($p)) + (192.99 * pow(log($p), 2)) - (7.99 * pow(log($p), 3));
        } else if ($t >= 546 and $t < 564) {
            $solco = -5722.71 + (2058.37 * log($p)) - (241.96 * pow(log($p), 2)) + (9.59 * pow(log($p), 3));
            $solc1 = 3754.63 - (1457.87 * log($p)) + (185.27 * pow(log($p), 2)) - (7.595 * pow(log($p), 3));
        } else if ($t >= 564 and $t < 582) {
            $solco = -17862.96 + (6385.99 * log($p)) - (756.59 * pow(log($p), 2)) + (29.99 * pow(log($p), 3));
            $solc1 = 3791.41 - (1459.499 * log($p)) + (183.97 * pow(log($p), 2)) - (7.48 * pow(log($p), 3));
        } else if ($t >= 582 and $t < 600) {
            $solco = -24748.74 + (8793.4 * log($p)) - (1037.84 * pow(log($p), 2)) + (40.97 * pow(log($p), 3));
            $solc1 = 3589.7 - (1373.24 * log($p)) + (171.96 * pow(log($p), 2)) - (6.94 * pow(log($p), 3));
        } else if ($t >= 600 and $t < 618) {
            $solco = -17101.28 + (5935.12 * log($p)) - (684.05 * pow(log($p), 2)) + (26.44 * pow(log($p), 3));
            $solc1 = 3861.15 - (1454.56 * log($p)) + (179.67 * pow(log($p), 2)) - (7.17 * pow(log($p), 3));
        } else if ($t >= 618 and $t < 636) {
            $solco = -2772.23 + (643.68 * log($p)) - (35.19 * pow(log($p), 2));
            $solc1 = 3107.85 - (1162.71 * log($p)) + (142.17 * pow(log($p), 2)) - (5.58 * pow(log($p), 3));
        } else if ($t >= 636 and $t < 654) {
            $solco = 1869.92 - (1030.25 * log($p)) + (164.66 * pow(log($p), 2)) - (7.92 * pow(log($p), 3));
            $solc1 = 3017.796 - (1124.59 * log($p)) + (136.93 * pow(log($p), 2)) - (5.35 * pow(log($p), 3));
        } else if ($t >= 654 and $t < 672) {
            $solco = 7652.34 - (3112.63 * log($p)) + (413.39 * pow(log($p), 2)) - (17.79 * pow(log($p), 3));
            $solc1 = 3347.14 - (1244.599 * log($p)) + (151.497 * pow(log($p), 2)) - (5.94 * pow(log($p), 3));
        } else if ($t >= 672 and $t < 690) {
            $solco = 10591.77 - (4148.77 * log($p)) + (534.29 * pow(log($p), 2)) - (22.47 * pow(log($p), 3));
            $solc1 = 2836.91 - (1049.25 * log($p)) + (126.68 * pow(log($p), 2)) - (4.897 * pow(log($p), 3));
        } else if ($t >= 690) {
            $nd = ($t - 690) / 18;
            $deltaco = -5734.69 + (2072.17 * log($p)) - (248.39 * pow(log($p), 2)) + (9.89 * pow(log($p), 3));
            $deltac1 = -208.93 + (70.898 * log($p)) - (7.98 * pow(log($p), 2)) + (0.302 * pow(log($p), 3));
            $solco = 10591.77 - (4148.77 * log($p)) + (534.29 * pow(log($p), 2)) - (22.47 * pow(log($p), 3)) + ($nd * $deltaco);
            $solc1 = 2836.91 - (1049.25 * log($p)) + (126.68 * pow(log($p), 2)) - (4.897 * pow(log($p), 3)) + ($nd * $deltac1);
        }


        #corrección n2
        if ($t < 528) {
            $nd = (528 - $t) / 36;
            $deltan2 = -35.74 + (8.17 * log($p)) - (0.44 * pow(log($p), 2));
            $soln = 101.14 - (44.14 * log($p)) + (4.49 * pow(log($p), 2)) + ($nd * $deltan2);
        } else if ($t >= 528 and $t < 546) {
            $soln = 101.14 - (44.14 * log($p)) + (4.49 * pow(log($p), 2));
        } else if ($t >= 546 and $t < 564) {
            $soln = 138.03 - (25.82 * log($p)) + (4.97 * pow(log($p), 2));
        } else if ($t >= 564 and $t < 582) {
            $soln = 134.76 - (51.82 * log($p)) + (4.83 * pow(log($p), 2));
        } else if ($t >= 582 and $t < 600) {
            $soln = 314.95 - (93.55 * log($p)) + (7.25 * pow(log($p), 2));
        } else if ($t >= 600 and $t < 618) {
            $soln = 89.75 - (39.72 * log($p)) + (4.02 * pow(log($p), 2));
        } else if ($t >= 618 and $t < 636) {
            $soln = 213.32 - (68.04 * log($p)) + (5.62 * pow(log($p), 2));
        } else if ($t >= 636 and $t < 654) {
            $soln = 170.48 - (58.41 * log($p)) + (5.06 * pow(log($p), 2));
        } else if ($t >= 654) {
            $nd = ($t - 654) / 36;
            $deltan2 = -499.31 + (114.43 * log($p)) - (6.54 * pow(log($p), 2));
            $soln = 170.48 - (58.41 * log($p)) + (5.06 * pow(log($p), 2)) + ($nd * $deltan2);
        }

        if ( $solco > 0 ) {
            $solco2 = $solco * $co2;
        } else {
            $solco2 = $co2 * 143.31 / 2;
        }

        if ( $solc1 > 0 ) {
            $solch4 = $solc1 * $c1;
        } else {
            $solch4 = $c1 * 143.31 / 2;
        }

        //$solco2 = $solco * $co2;
        //$solch4 = $solc1 * $c1;

        if ($soln > 0) {
            $soln2 = $soln * $n2;
        } else {
            $soln2 = $n2 * 143.31 / 2;
        }

        return array($solco2, $solch4, $soln2);
    }

    function critical_pressures($n, $zi, $rhoi, $mwi, $zis, $pci)
    {
        $rhop = array_fill(1, 100, 0);
        $pcibar = array_fill(1, 100, 0);
        $a = array_fill(1, 100, 0);
        $b = array_fill(1, 100, 0);
        $pcis = array_fill(1, $n, 0);
        $pcins = array_fill(1, $n, 0);

        $tol1 = 0.00001;
        for ($i = 1; $i <= $n; $i++) {
            $pcibar[$i] = $pci[$i] * 0.0689475729;
        }

        for ($i = 1; $i <= $n; $i++) {
            if ($mwi[$i] < 270) {
                $pcis[$i] = $pcibar[$i];
            } else {
                $rhop[$i] = 0.3915 + 0.0675 * log($mwi[$i]);
                $pcis[$i] = $pcibar[$i] * pow(($rhop[$i] / $rhoi[$i]), 3.46);
            }
        }

        for ($i = 1; $i <= $n; $i++) {
            if ($mwi[$i] < 270) {
                $pcins[$i] = $pcibar[$i];
            } else {

                $tol1 = 0.00001;
                $iter = 1;
                $a[$i] = ($zi[$i] - $zis[$i]) / $zi[$i];
                $b[$i] = ($zis[$i] / $zi[$i]);
                while (1) #Revisar
                {
                    $pcinss = $pcis[$i];
                    $tol = 10;
                    $flag_while = True;
                    while ($tol > $tol1) {
                        $fpcins = pow($a[$i], 2) / 0.95 * $pcinss + pow($b[$i], 2) / $pcis[$i] + 2.0 * $a[$i] * $b[$i] / pow($pcinss, 0.5) * pow($pcis[$i], 0.25) - 1.0 / $pcibar[$i];
                        $dpcins = -pow($a[$i], 2) / 0.95 * pow($pcinss, 2) - ($a[$i] * $b[$i]) / (pow($pcis[$i], 0.25) * pow($pcinss, 1.5));
                        $pcinsc = $pcinss - $fpcins / $dpcins;
                        $tol = abs($pcinsc - $pcinss);
                        $iter = $iter + 1;
                        if ($iter > 10000) {
                            $tol1 = $tol1 * 10;
                            $flag_while = False;
                            break;
                        }
                        $pcinss = $pcinsc;
                    }
                    $pcins[$i] = $pcinsc;
                    if ($flag_while) {
                        break;
                    }
                }
            }
        }

        for ($i = 1; $i <= $n; $i++) {
            $pcis[$i] = $pcis[$i] / 0.0689475729;
            $pcins[$i] = $pcins[$i] / 0.0689475729;
        }

        return array($pcis, $pcins);
    }


    function solid_region_1($n, $p, $t, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat)
    {

        $zz = array_fill(1, 3, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $lncf1 = array_fill(1, 100, 0);
        $lncf2 = array_fill(1, 100, 0);
        $lncf3 = array_fill(1, 100, 0);
        $fug1 = array_fill(1, 100, 0);
        $fug2 = array_fill(1, 100, 0);
        $fug3 = array_fill(1, 100, 0);
        $fg = array_fill(1, 100, 0);
        $fugpuro = array_fill(1, 100, 0);
        $fugsolpuro = array_fill(1, 100, 0);
        $fugsol = array_fill(1, 100, 0);
        $denpro = array_fill(1, 100, 0);
        $ziini = array_fill(1, 100, 0);
        $zis = array_fill(1, 100, 0);
        $zino = array_fill(1, 100, 0);
        $zit = array_fill(1, 100, 0);
        $fliq = array_fill(1, 100, 0);
        $fliqs = array_fill(1, 100, 0);
        $fsols = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);
        $kis = array_fill(1, 100, 0);
        $errorf = array_fill(1, 100, 0);
        $criterio1 = array_fill(1, 100, 0);
        $xil = array_fill(1, 100, 0);
        $xis = array_fill(1, 100, 0);
        $pcis = array_fill(1, 100, 0);
        $pcins = array_fill(1, 100, 0);

        $flag_esta = 0;
        for ($i = 1; $i <= $n; $i++) {
            $denpro[$i] = 0.3915 + 0.0675 * log($mwi[$i]);
            if (($denpro[$i] - $rhoi[$i]) > 0) {
                $zis[$i] = 0.000001;
            } else {
                $zis[$i] = $zi[$i] * (1 - (1.074 + (0.0006584 * $mwi[$i])) * pow((($rhoi[$i] - $denpro[$i]) / ($denpro[$i])), 0.1915)); #Revisar
            }
        }
        $zitot = 0;
        for ($i = 1; $i <= $n; $i++) {
            $zitot = $zitot + $zis[$i];
        }
        for ($i = 1; $i <= $n; $i++) {
            $zis[$i] = $zis[$i] / $zitot;
        }

        $critical_pressure_results = $this->critical_pressures($n, $zi, $rhoi, $mwi, $zis, $pci);

        $pcis = $critical_pressure_results[0];
        $pcins = $critical_pressure_results[1];

        $preprocessing_results = $this->preprocessing($p, $t, $n, $zis, $mwi, $pcis, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $zz = $this->cubic($amay, $bmay);

        $fugacity_results = $this->fugacity($p, $t, $n, $zis, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);
        $lncf1 = $fugacity_results[0];
        $lncf2 = $fugacity_results[1];
        $lncf3 = $fugacity_results[2];
        $fug1 = $fugacity_results[3];
        $fug2 = $fugacity_results[4];
        $fug3 = $fugacity_results[5];

        $gibbs_stability_results = $this->gibbs_stability($n, $zis, $fug1, $fug2, $fug3, $zz);
        $fg = $gibbs_stability_results[0];
        $zg = $gibbs_stability_results[1];

        #primera aproximacion
        for ($i = 1; $i <= $n; $i++) {
            $fliq[$i] = $zis[$i] * $fg[$i];
        }

        $fugpuro = $this->pure_compound($n, $p, $t, $pcis, $tci, $wi);
        $fugsolpuro = $this->pure_fugacity_solid($n, $p, $t, $mwi, $fugpuro);

        for ($i = 1; $i <= $n; $i++) {
            $fugsol[$i] = $zis[$i] * $fugsolpuro[$i];
        }

        for ($i = 1; $i <= $n; $i++) {
            $kis[$i] = $fliq[$i] / $fugsol[$i];
        }

        $iterf = 1;
        $tol = 1e-18;
        $goto_flag = True;
        while (1) #Revisar goto
        {
            $maxerror = 10;
            $goto_flag = True;

            while ($maxerror > $tol) {
                $solid_equilibrium_results = $this->solid_equilibrium($n, $p, $t, $zis, $mwi, $pcis, $tci, $vci, $wi, $si, $rhoi, $cib, $kis, $fugsolpuro);

                if ($solid_equilibrium_results[0] == false) {
                    return $solid_equilibrium_results;
                }

                $fsols = $solid_equilibrium_results[0];
                $fliqs = $solid_equilibrium_results[1];
                $s = $solid_equilibrium_results[2];
                $xil = $solid_equilibrium_results[3];
                $xis = $solid_equilibrium_results[4];
                for ($i = 1; $i <= $n; $i++) {
                    if ($fsols[$i] != 0) #Corrección
                    {
                        $kis[$i] = $kis[$i] * ($fliqs[$i] / $fsols[$i]);
                    }
                }
                for ($i = 1; $i <= $n; $i++) {
                    if ($fsols[$i] != 0) #Corrección
                    {
                        $errorf[$i] = pow((($fliqs[$i] / $fsols[$i]) - 1.0), 2);
                    }
                }
                $maxerror = max($errorf);
                $iterf = $iterf + 1;
                if ($iterf > 200) {
                    $tol = $tol * 10;
                    $goto_flag = False;
                    break; #Revisar goto
                }
            }

            if ($goto_flag) {
                break;
            }
        }

        if ($s < 0.000001) {
            $s = 0;
        }
        #criterio de estabilidad
        for ($i = 1; $i <= $n; $i++) {
            $criterio1[$i] = $fliqs[$i] - $fsols[$i];
            if ($criterio1[$i] < 0) {
                $flag_esta = 1;
            }
        }

        if ($flag_esta > 0) {
            #para fluidos muy livianos no existe el problemas de solidos
            if ($n < 9) {
                $wat = 0;
            }
            #masa total
            $summw = 0;
            $summwt = 0;
            $sumwat = 0; #Revisar - Posible error
            for ($i = 1; $i <= $n; $i++) {
                $summw = $summw + $zis[$i] * $mwi[$i];
                $summwt = $summwt + $zi[$i] * $mwi[$i];
            }

            if ($mwi[$n] < 226.446 and $mwi[$n] > 114.231) {
                $wat = (($xis[$n] * $mwi[$n] * $s) / $summw) * 100;
            }

            if ($mwi[$n] > 226.446) {
                #con un solo rr la fraccion solida es igual a ns
                if ($s > 0.0001) {
                    $sumwat = 0;
                    for ($i = 1; $i <= $n; $i++) {
                        if ($mwi[$i] > 114.231) {
                            $sumwat = $sumwat + ($xis[$i] * $mwi[$i]);
                        }
                    }
                }
                #tomado de chinese j.ch.e(vol.14.no5) pag 688
                $wat = (($sumwat * $s * ($sat / 100)) / $summwt) * 100;
            } else {
                $wat = 0;
            }
        } else #Revisar if
        {
            $wat = 0;
        }

        return array($wat, $s);
    }

    function solid_region_2($p, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib, $sat)
    {
        $zz = array_fill(1, 3, 0);
        $tri = array_fill(1, 100, 0);
        $pri = array_fill(1, 100, 0);
        $ati = array_fill(1, 100, 0);
        $bi = array_fill(1, 100, 0);
        $ci = array_fill(1, 100, 0);
        $s_aj = array_fill(1, 100, 0);
        $lncf1 = array_fill(1, 100, 0);
        $lncf2 = array_fill(1, 100, 0);
        $lncf3 = array_fill(1, 100, 0);
        $fug1 = array_fill(1, 100, 0);
        $fug2 = array_fill(1, 100, 0);
        $fug3 = array_fill(1, 100, 0);
        $fg = array_fill(1, 100, 0);
        $fugpuro = array_fill(1, 100, 0);
        $fugsolpuro = array_fill(1, 100, 0);
        $fugsol = array_fill(1, 100, 0);
        $denpro = array_fill(1, 100, 0);
        $ziini = array_fill(1, 100, 0);
        $zis = array_fill(1, 100, 0);
        $zino = array_fill(1, 100, 0);
        $zit = array_fill(1, 100, 0);
        $fliq = array_fill(1, 100, 0);
        $fliqs = array_fill(1, 100, 0);
        $fsols = array_fill(1, 100, 0);
        $kis = array_fill(1, 100, 0);
        $pcis = array_fill(1, 100, 0);
        $pcins = array_fill(1, 100, 0);
        $errorf = array_fill(1, 100, 0);
        $criterio1 = array_fill(1, 100, 0);
        $xil = array_fill(1, 100, 0);
        $xis = array_fill(1, 100, 0);
        $daidt = array_fill(1, 100, 0);
        $xi = array_fill(1, 100, 0);
        $yi = array_fill(1, 100, 0);

        $liquid_steam_equilibrium_results = $this->liquid_steam_equilibrium($p, $t, $n, $zi, $mwi, $pci, $tci, $vci, $wi, $si, $rhoi, $cib);
        $xi = $liquid_steam_equilibrium_results[0];
        $yi = $liquid_steam_equilibrium_results[1];
        $zliq = $liquid_steam_equilibrium_results[2];
        $zvap = $liquid_steam_equilibrium_results[3];
        $v = $liquid_steam_equilibrium_results[4];

        $flag_esta = 0;
        for ($i = 1; $i <= $n; $i++) {
            $denpro[$i] = 0.3915 + 0.0675 * log($mwi[$i]);
            if (($denpro[$i] - $rhoi[$i]) > 0) {
                $zis[$i] = 0.00000000001;
            } else {
                $zis[$i] = $zi[$i] * (1 - (1.074 + (0.0006584 * $mwi[$i])) * pow((($rhoi[$i] - $denpro[$i]) / ($denpro[$i])), 0.1915)); #Revisar
            }
        }

        $zitot = 0;
        for ($i = 1; $i <= $n; $i++) {
            $zitot = $zitot + $zis[$i];
        }
        for ($i = 1; $i <= $n; $i++) {
            $zis[$i] = $zis[$i] / $zitot;
        }

        $critical_pressure_results = $this->critical_pressures($n, $xi, $rhoi, $mwi, $zis, $pci);
        $pcis = $critical_pressure_results[0];
        $pcins = $critical_pressure_results[1];

        $preprocessing_results = $this->preprocessing($p, $t, $n, $zis, $mwi, $pcis, $tci, $vci, $wi, $si, $cib);
        $tri = $preprocessing_results[0];
        $pri = $preprocessing_results[1];
        $ati = $preprocessing_results[2];
        $bi = $preprocessing_results[3];
        $ci = $preprocessing_results[4];
        $s_aj = $preprocessing_results[5];
        $daidt = $preprocessing_results[6];
        $amin = $preprocessing_results[7];
        $bmin = $preprocessing_results[8];
        $amay = $preprocessing_results[9];
        $bmay = $preprocessing_results[10];

        $zz = $this->cubic($amay, $bmay);
        $fugacity_results = $this->fugacity($p, $t, $n, $zis, $bi, $ci, $s_aj, $amin, $bmin, $amay, $bmay, $zz);
        $lncf1 = $fugacity_results[0];
        $lncf2 = $fugacity_results[1];
        $lncf3 = $fugacity_results[2];
        $fug1 = $fugacity_results[3];
        $fug2 = $fugacity_results[4];
        $fug3 = $fugacity_results[5];

        $gibbs_stability_results = $this->gibbs_stability($n, $zis, $fug1, $fug2, $fug3, $zz);
        $fg = $gibbs_stability_results[0];
        $zg = $gibbs_stability_results[1];

        #primera aproximacion
        for ($i = 1; $i <= $n; $i++) {
            $fliq[$i] = $zis[$i] * $fg[$i];
        }

        $fugpuro = $this->pure_compound($n, $p, $t, $pcis, $tci, $wi);
        $fugsolpuro = $this->pure_fugacity_solid($n, $p, $t, $mwi, $fugpuro);

        for ($i = 1; $i <= $n; $i++) {
            $fugsol[$i] = $zis[$i] * $fugsolpuro[$i];
        }

        for ($i = 1; $i <= $n; $i++) {
            $kis[$i] = $fliq[$i] / $fugsol[$i];
        }

        $iterf = 1;
        $tol = 1e-18;

        while (1) #Revisar goto
        {
            $maxerror = 10;
            $goto_flag = True;

            while ($maxerror > $tol) {
                $solid_equilibrium_results = $this->solid_equilibrium($n, $p, $t, $zis, $mwi, $pcis, $tci, $vci, $wi, $si, $rhoi, $cib, $kis, $fugsolpuro, $fsols);
                
                if ($solid_equilibrium_results[0] == false) {
                    return $solid_equilibrium_results;
                }

                $fsols = $solid_equilibrium_results[0];
                $fliqs = $solid_equilibrium_results[1];
                $s = $solid_equilibrium_results[2];
                $xil = $solid_equilibrium_results[3];
                $xis = $solid_equilibrium_results[4];
                for ($i = 1; $i <= $n; $i++) {
                    if ($fsols[$i] != 0) #Corección
                    {
                        $kis[$i] = $kis[$i] * ($fliqs[$i] / $fsols[$i]);
                    }
                }
                for ($i = 1; $i <= $n; $i++) {
                    if ($fsols[$i] != 0) #Corrección
                    {
                        $errorf[$i] = pow((($fliqs[$i] / $fsols[$i]) - 1.0), 2);
                    }
                }
                $maxerror = max($errorf);
                $iterf = $iterf + 1;
                if ($iterf > 200) {
                    $tol = $tol * 10;
                    $goto_flag = False;
                    break; #Revisar goto
                }
            }

            if ($goto_flag) {
                break;
            }
        }

        if ($s < 0.000001) {
            $s = 0;
        }

        #criterio de estabilidad
        for ($i = 1; $i <= $n; $i++) {
            $criterio1[$i] = $fliqs[$i] - $fsols[$i];
        }

        #para fluidos muy livianos no existe el problemas de solidos
        if ($n < 9) {
            $wat = 0;
        }

        #masa total
        $summw = 0;
        $summwt = 0;
        $sumwat = 0; #Revisar - posible error

        for ($i = 1; $i <= $n; $i++) {
            $summw = $summw + $zis[$i] * $mwi[$i];
            $summwt = $summwt + $zi[$i] * $mwi[$i];
        }

        if ($mwi[$n] < 226.446 and $mwi[$n] > 114.231) {
            $wat = (($xis[$n] * $mwi[$n] * $s) / $summw) * 100;
        }

        if ($n > 15) {
            #con un solo rr la fraccion solida es igual a ns
            if ($s > 0.0001) {
                $sumwat = 0;
                for ($i = 1; $i <= $n; $i++) {
                    if ($mwi[$i] > 114.231) {
                        $sumwat = $sumwat + ($xis[$i] * $mwi[$i]);
                    }
                }
            }
            #tomado de chinese j.ch.e(vol.14.no5) pag 688
            $wat = (($sumwat * $s * ($sat / 100)) / $summwt) * 100;
        } else {
            $wat = 0;
        }

        return array($wat, $s);
    }

    function create_multidimensional_fixed_array()
    {
        $args = func_get_arg(0);
        $array = new SplFixedArray($args[0]);

        if (isset($args[1])) {
            $newArgs = array_splice($args, 1);
            for ($i = 0; $i < $args[0]; $i++) {
                $array[$i] = $this->create_fixed_array($newArgs);
            }
        }
        return $array;
    }

    function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    function create_fixed_array($n)
    {
        $array = $this->object_to_array(new SplFixedArray($n + 1));
        unset($array[0]);
        return $array;
    }
}
