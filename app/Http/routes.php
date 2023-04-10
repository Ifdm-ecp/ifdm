<?php
if(!isset($_SESSION)) {
     session_start();
}
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/login', function () {
    return \Redirect::to('/auth/login');
});

Route::get('/', function () {
    if (\Auth::check()) {
       return \Redirect::to('homeC');
    }else{
        return \Redirect::to('/auth/login');
    }
});

Route::get('/.well-known/pki-validation/C3524CCE68099C98DFAA4F334F99B095.txt', function () {
    return Storage::url('/.well-known/pki-validation/C3524CCE68099C98DFAA4F334F99B095.txt');
});


//buscar los uintervalos pertenecientes de una formacion
Route::get('intervalos/{formacion}', function($formacion){
    $formacion = \App\formacion::find($formacion);
    return $formacion->intervalosProductores;
});


#Login
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin')->name('entrar');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('reset_password', 'Auth\PasswordController@getEmail');
Route::post('reset_password/sendmail', 'Auth\PasswordController@postEmail')->name('enviar_mail');

Route::get('reset_password/change/{token}', 'Auth\PasswordController@getReset');
Route::post('reset_password/setp', 'Auth\PasswordController@postReset')->name('setp');

Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::group(['middleware' => 'auth'], function(){ 
    //ruta para compartir scenary
    Route::get('getShareScenaryTable', 'project_management_controller@dataTable');
    

    //modulo pvt global
    Route::get('treePvt', 'PvtGlobalController@tree')->name('pvt-global.tree');
    Route::get('treePvt/{id}', 'PvtGlobalController@treeDatosPvt')->name('pvt-global.treeDatosPvt');
    Route::resource('pvt-global', 'PvtGlobalController');


    //modulo pvt global FXP
    Route::get('treePvt-FxP', 'PvtGlobalController@tree-FxP')->name('pvt-global-FxP.tree');
    Route::get('treePvt-FxP/{id}', 'PvtGlobalController@treeDatosPvt-FxP')->name('pvt-global-FxP.treeDatosPvt');

    //manual al publico
    Route::resource('manual-public', 'ManualPublicController');
    //tags del manual
    Route::post('manual-tags/order', 'TagManualController@order');
    Route::resource('manual-tags', 'TagManualController');
    //tutos del manual
    Route::resource('manual', 'ManualController');

    //obtener el field por basin
    Route::get('basinField/{id}', 'add_basin_field_controller@basinField'); 
    //obtener el formation por field
    Route::get('fieldFormation/{id}', 'add_basin_field_controller@fieldFormation');
    //minerales por formacion
    Route::resource('formation-mineralogy', 'FormationMineralogy\FormationMineralogyController');
    Route::get('fines-fts/{mineralogy}', 'FormationMineralogy\FormationMineralogyController@fines')->name('fts.fines');

    //escenario finestretamentselection
    Route::resource('fts','FinesTreatmentSelectionController');
    Route::get('fts/duplicate/{id}/{dup}', 'FinesTreatmentSelectionController@duplicate');
    
    //escenario multiparametrico analitical, statistical, completo o mixto
    Route::post('statistical/update/{id}', 'MultiparametricAnalysis\StatisticalController@update')->name('statistical.update_');
    Route::resource('statistical', 'MultiparametricAnalysis\StatisticalController');

    /* Para duplicar */
    Route::get('statistical/duplicate/{id}/{dup}', 'MultiparametricAnalysis\StatisticalController@duplicate');
    Route::get('analytical/duplicate/{id}/{dup}', 'MultiparametricAnalysis\AnalyticalController@duplicate');

    Route::post('analytical/update/{id}', 'MultiparametricAnalysis\AnalyticalController@update')->name('analytical.update_');

    Route::resource('analytical', 'MultiparametricAnalysis\AnalyticalController');
    Route::resource('completeMultiparametric', 'MultiparametricAnalysis\CompleteMultiparametricController');
    Route::get('completeAnalytical/create/{id}', 'MultiparametricAnalysis\CompleteMultiparametricController@createAnalytical')->name('completeAnalytical.create');
    Route::post('completeAnalytical/', 'MultiparametricAnalysis\CompleteMultiparametricController@storeAnalytical')->name('completeAnalytical.store');
    Route::get('completeAnalytical/{id}/edit', 'MultiparametricAnalysis\CompleteMultiparametricController@editAnalytical')->name('completeAnalytical.edit');
    Route::post('completeAnalytical/update/{id}', 'MultiparametricAnalysis\CompleteMultiparametricController@updateAnalytical')->name('completeAnalytical.update');


    /*Share scenario*/
    Route::resource('share_scenario', 'project_management_controller');
    Route::resource('edit_scenario', 'project_management_controller@manage');

    Route::get('getUsersToShare', function () {

        $user_id = \Auth::id();
        $user = DB::table('users')->where('id', '=', $user_id)->first();
        $rol = $user->office;

        /*
        if ($rol == 0) {#System administrator
            $users = collect(DB::table('users')->where('id', '!=', \Auth::id())->where('office', '!=', 0)
                               ->get());
        } else {
            $users = collect(DB::table('users')->where('company', '=', \Auth::user()->company)->where('office', '=', 2)
                ->where('id', '!=', \Auth::id())
                ->get());
        }*/

        $users = collect(DB::table('users')->where('id', '!=', \Auth::id())->where('office', '!=', 0)
            ->where('id', '!=', \Auth::id())
            ->get());
        
        return Response::json($users);

    });

    Route::get('getSharedScenarios', function () {
        $shared_scenarios = collect(DB::table('shared_scenario')
            ->where('shared_scenario.user_id', '=', Auth::id())
            ->join('escenarios','shared_scenario.scenario_id','=','escenarios.id')
            ->orderBy('nombre', 'ASC')
            ->get());

        foreach ($shared_scenarios as $ke => $ve) {

            $respuesta = 1;
            $id = $ve->scenario_id;

            if($ve->tipo == 'Asphaltene precipitation') {

                $scenarios_extras = [];

                $resp_asphaltenes_d_stability_analysis = App\asphaltenes_d_stability_analysis::where('scenario_id',$id)->first();
                if ($resp_asphaltenes_d_stability_analysis) {
                    $resp_asphaltenes_d_stability_analysis->nombre = '[A_S]';
                    $resp_asphaltenes_d_stability_analysis->route = route("asa.result",$id);
                    $scenarios_extras[] = $resp_asphaltenes_d_stability_analysis;
                }
                $respuesta_as = ($resp_asphaltenes_d_stability_analysis) ? 0 : 1;


                $resp_asphaltenes_d_precipitated_analysis = App\asphaltenes_d_precipitated_analysis::where('scenario_id',$id)->first();
                if ($resp_asphaltenes_d_precipitated_analysis) {
                    $resp_asphaltenes_d_precipitated_analysis->nombre = '[A_P]';
                    $resp_asphaltenes_d_precipitated_analysis->route = route("asp.result",$id);
                    $scenarios_extras[] = $resp_asphaltenes_d_precipitated_analysis;
                }
                $respuesta_ap = ($resp_asphaltenes_d_precipitated_analysis) ? 0 : 1;


                $resp_asphaltenes_d_diagnosis = App\asphaltenes_d_diagnosis::where('scenario_id',$id)->first();
                if ($resp_asphaltenes_d_diagnosis) {
                    $resp_asphaltenes_d_diagnosis->nombre = '[A_D]';
                    $resp_asphaltenes_d_diagnosis->route = route("asd.result",$id);
                    $scenarios_extras[] = $resp_asphaltenes_d_diagnosis;
                }
                $respuesta_ad = ($resp_asphaltenes_d_diagnosis) ? 0 : 1;

                $shared_scenarios[$ke]->extra_ = $scenarios_extras;

                if ($respuesta_as == 0 && $respuesta_ad == 0 && $respuesta_ap == 0) {
                    $respuesta = 0;
                }

            }

            if ($respuesta == 0) {
                $shared_scenarios[$ke]->res_ = $respuesta;
            } 

        }

        //array_push($escenarioscompleto,$escenarios);

        return Response::json($shared_scenarios);

    });


    Route::get('getSharedUsers', function () {
        $scenario_id = Input::get('scenario_id');
        $users = DB::table('shared_scenario')
                            ->where('shared_scenario.scenario_id', '=', $scenario_id)
                            ->join('users', "users.id", "=", "shared_scenario.user_id")
                            ->select('users.id', 'users.fullName')
                            ->get();
        return Response::json($users);

    });

    Route::get('getScenario', function () {
        $scenario_id = Input::get('scenario_id');
        $scenario = DB::table('escenarios')
                ->where('id', '=', $scenario_id)
                ->get();
        return Response::json($scenario);
    });

    /**/

    /*Rutas reportes drilling*/
    Route::get('drilling_report', function(){

        $id = Input::get('esc_id');
        $drilling = DB::table('drilling')->where('scenario_id', $id)->first();

        return Response::json($drilling);

    });

    Route::get('d_intervals_input_data', function(){

        $id = Input::get('drilling_id');
        $d_intervals_input_data = DB::table('d_intervals_input_data')->where('drilling_id', $id)->join('formacionxpozos', 'formacionxpozos.id', '=', 'd_intervals_input_data.producing_interval_id')->select('d_intervals_input_data.porosity as porosity', 'd_intervals_input_data.permeability as permeability', 'd_intervals_input_data.fracture_intensity as fracture_intensity', 'd_intervals_input_data.irreducible_saturation as irreducible_saturation', 'formacionxpozos.nombre as interval')->get();

        return Response::json($d_intervals_input_data);

    });

    Route::get('d_average_input_data', function(){

        $id = Input::get('drilling_id');
        $d_average_input_data = DB::table('d_average_input_data')->where('drilling_id', $id)->join('formaciones', 'formaciones.id', '=', 'd_average_input_data.formation_id')->select('d_average_input_data.porosity as porosity', 'd_average_input_data.permeability as permeability', 'd_average_input_data.fracture_intensity as fracture_intensity', 'd_average_input_data.irreducible_saturation as irreducible_saturation', 'formaciones.nombre as formation')->get();

        return Response::json($d_average_input_data);

    });

    Route::get('d_general_data', function(){

        $id = Input::get('drilling_id');
        $d_general_data = DB::table('d_general_data')->where('drilling_id', $id)->join('formacionxpozos', 'formacionxpozos.id', '=', 'd_general_data.producing_interval_id')->select('d_general_data.top as top', 'd_general_data.bottom as bottom', 'd_general_data.reservoir_pressure as reservoir_pressure', 'd_general_data.hole_diameter as hole_diameter', 'd_general_data.drill_pipe_diameter as drill_pipe_diameter', 'formacionxpozos.nombre as interval')->get();

        return Response::json($d_general_data);

    });


    Route::get('d_profile_input_data', function(){

        $id = Input::get('drilling_id');
        $d_profile_input_data = DB::table('d_profile_input_data')->where('drilling_id', $id)->get();

        return Response::json($d_profile_input_data);

    });

    Route::get('drilling_results_chart', function(){

        $id = Input::get('drilling_id');
        $drilling_results_chart = DB::table('drilling_results_chart')->where('drilling_id', $id)->get();

        return Response::json($drilling_results_chart);

    });

    Route::get('drilling_results', function(){

        $id = Input::get('drilling_id');
        $drilling_results = DB::table('drilling_results')->where('drilling_id', $id)->first();

        return Response::json($drilling_results);

    });

    /**/

    /*Rutas Finos*/
    Route::get('get_historical_data_fines', function()
    {
        $scenario_id = Input::get('scenario_id');

        $fines_d_historical_data = DB::table('fines_d_historical_data')->join('fines_d_diagnosis', 'fines_d_diagnosis.id', '=', 'fines_d_historical_data.fines_d_diagnosis_id')->where('fines_d_diagnosis.scenario_id', $scenario_id)->select('date', 'bopd', 'bwpd')->distinct()->get();

        return Response::json($fines_d_historical_data);
    });

    Route::get('get_phenomenological_data_fines', function()
    {
        $scenario_id = Input::get('scenario_id');

        $fines_d_phenomenological_constants = DB::table('fines_d_phenomenological_constants')->join('fines_d_diagnosis', 'fines_d_diagnosis.id', '=', 'fines_d_phenomenological_constants.fines_d_diagnosis_id')->where('fines_d_diagnosis.scenario_id', $scenario_id)->select('flow', 'permeability', 'k1', 'k2', 'dp_dl', 'k3', 'k4', 'k5', 'dp_dl2', 'sigma', 'k6', 'ab_2', 'ab')->orderBy('fines_d_phenomenological_constants.id', 'asc')->distinct()->get();

        return Response::json($fines_d_phenomenological_constants);
    });

    Route::get('get_advisor_pvt_table_oil', function()
    {
        $scenario_id = Input::get('scenario_id');

        $fines_d_diagnosis = DB::table('fines_d_pvt')->join('fines_d_diagnosis', 'fines_d_diagnosis.id', '=', 'fines_d_pvt.fines_d_diagnosis_id')->where('fines_d_diagnosis.scenario_id', $scenario_id)->select('pressure', 'oil_density', 'oil_viscosity', 'volumetric_oil_factor')->orderBy('fines_d_pvt.id', 'asc')->distinct()->get();

        return Response::json($fines_d_diagnosis);
    });

    Route::get('get_advisor_pvt_table_water', function()
    {
        $scenario_id = Input::get('scenario_id');

        $fines_d_diagnosis = DB::table('fines_d_pvt')->join('fines_d_diagnosis', 'fines_d_diagnosis.id', '=', 'fines_d_pvt.fines_d_diagnosis_id')->where('fines_d_diagnosis.scenario_id', $scenario_id)->select('pressure', 'volumetric_water_factor', 'water_viscosity', 'water_density')->orderBy('fines_d_pvt.id', 'asc')->distinct()->get();

        return Response::json($fines_d_diagnosis);
    });

    Route::get('get_type_of_suspension_flux', function()
    {
        $scenario_id = Input::get('scenario_id');

        $fines_d_diagnosis = DB::table('fines_d_diagnosis')->where('fines_d_diagnosis.scenario_id', $scenario_id)->select('type_of_suspension_flux')->orderBy('id', 'asc')->first();

        return Response::json($fines_d_diagnosis);
    });


    /**/

    /*Rutas para asfaltenos*/
    Route::resource('run_asphaltene_diagnosis', 'add_asphaltenes_diagnosis_controller@show');
    Route::resource('run_precipitated_asphaltene', 'add_precipitated_asphaltenes_analysis_controller@show');
    Route::resource('run_asphaltene_stability', 'add_asphaltene_stability_analysis_controller@show');

    Route::get('get_asphaltenes_table', function()
    {
        $asphaltenes_d_diagnosis_id = Input::get('asphaltenes_d_diagnosis_id');

        $asphaltenes_d_diagnosis_soluble_asphaltenes = DB::table('asphaltenes_d_diagnosis_soluble_asphaltenes')->where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis_id)->select('pressure', 'asphaltene_soluble_fraction')->orderBy('id', 'asc')->get();

        return Response::json($asphaltenes_d_diagnosis_soluble_asphaltenes);
    });

    Route::get('get_pvt_table', function()
    {
        $asphaltenes_d_diagnosis_id = Input::get('asphaltenes_d_diagnosis_id');

        $asphaltenes_d_diagnosis_pvt = DB::table('asphaltenes_d_diagnosis_pvt')->where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis_id)->select('pressure', 'density', 'oil_viscosity', 'oil_formation_volume_factor')->orderBy('id', 'asc')->get();

        return Response::json($asphaltenes_d_diagnosis_pvt);
    });


    Route::get('get_historical_table', function()
    {
        $asphaltenes_d_diagnosis_id = Input::get('asphaltenes_d_diagnosis_id');

        $asphaltenes_d_diagnosis_historical_data = DB::table('asphaltenes_d_diagnosis_historical_data')->where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis_id)->select('date', 'bopd', 'asphaltenes')->orderBy('id', 'asc')->get();

        return Response::json($asphaltenes_d_diagnosis_historical_data);
    });

    Route::get('get_solid_a_results', function()
    {
        $scenario_id = Input::get('scenario_id');

        $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenario_id)->select('id', 'reservoir_temperature')->first();

        $asphaltenes_d_precipitated_analysis_solid_a_results = [];
        if($asphaltenes_d_precipitated_analysis){

            $temperatura = $asphaltenes_d_precipitated_analysis->reservoir_temperature + 460;

            $asphaltenes_d_precipitated_analysis_solid_a_results = DB::table('asphaltenes_d_precipitated_analysis_solid_a_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->select('temperature')->distinct('temperature')->get();

            $diferencia = abs($asphaltenes_d_precipitated_analysis_solid_a_results[0]->temperature - $temperatura);

            $temperature = $asphaltenes_d_precipitated_analysis_solid_a_results[0]->temperature;

            foreach ($asphaltenes_d_precipitated_analysis_solid_a_results as $value){
                $aux_diferencia = abs($value->temperature - $temperatura);

                if($aux_diferencia < $diferencia){
                    $diferencia = $aux_diferencia;
                    $temperature = $value->temperature;
                }
            }

            $asphaltenes_d_precipitated_analysis_solid_a_results = DB::table('asphaltenes_d_precipitated_analysis_solid_a_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis->id)->where('temperature',$temperature)->select('pressure', 'a')->orderBy('id', 'asc')->get();
        }
        

        return Response::json($asphaltenes_d_precipitated_analysis_solid_a_results);
    });

    Route::get('import_components_data', function()
    {
        $components = Input::get('components');
        $components = explode(',', $components);

        $asphaltenes_d_precipitated_analysis_components_database = DB::table('asphaltenes_d_precipitated_analysis_components_database')->wherein('component', $components)->select('component', 'zi', 'mw', 'pc', 'tc', 'w', 'shift', 'sg', 'tb', 'vc')->orderBy('id', 'asc')->get();

        return Response::json($asphaltenes_d_precipitated_analysis_components_database);
    });

    Route::get('get_components_table_from_precipitated', function()
    {
        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');

        $components_stability_analysis = DB::table('asphaltenes_d_precipitated_analysis_components_data')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->select('component')
            ->orderBy('id', 'asc')->get();

        $mole_fraction_stability_analysis = DB::table('asphaltenes_d_precipitated_analysis_components_data')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->select('zi')
        ->orderBy('id', 'asc')->get();

        $data = array('components_stability_analysis' => $components_stability_analysis, 'mole_fraction_stability_analysis'=>$mole_fraction_stability_analysis);

        return Response::json($data);
    });


    Route::get('get_bubble_point_data', function()
    {
        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');

        $bubble_point_table = DB::table('asphaltenes_d_precipitated_analysis_temperatures')->select('temperature', 'bubble_pressure')->where('asphaltenes_d_precipitated_analysis_temperatures.asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->orderBy('asphaltenes_d_precipitated_analysis_temperatures.id', 'asc')->get();

        return Response::json($bubble_point_table);
    });

    Route::get('get_experimental_onset_pressures_data', function()
    {
        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');

        $experimental_onset_pressure_table = DB::table('asphaltenes_d_precipitated_analysis_experimental_onset_pressures')->select('temperature', 'onset_pressure')->where('asphaltenes_d_precipitated_analysis_experimental_onset_pressures.asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->orderBy('asphaltenes_d_precipitated_analysis_experimental_onset_pressures.temperature', 'asc')->get();

        return Response::json($experimental_onset_pressure_table);
    });

    Route::get('get_precipitated_analysis_components', function()
    {
        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');
        $precipitated_analysis_components = DB::table('asphaltenes_d_precipitated_analysis_components_data')
        ->select('component', 'zi', 'mw', 'pc', 'tc', 'w', 'shift', 'sg', 'tb', 'vc')
        ->where('asphaltenes_d_precipitated_analysis_components_data.asphaltenes_d_precipitated_analysis_id', 
            $asphaltenes_d_precipitated_analysis_id)
        ->orderBy('asphaltenes_d_precipitated_analysis_components_data.id', 'asc')->get();

        return Response::json($precipitated_analysis_components);
    });

    Route::get('get_precipitated_analysis_binary_coefficients', function()
    {
        $scenario_id = Input::get('scenario_id');

        $precipitated_analysis_binary_coefficients = DB::table('asphaltenes_d_precipitated_analysis_binary_coefficients')->join('asphaltenes_d_precipitated_analysis', 'asphaltenes_d_precipitated_analysis.id', '=', 'asphaltenes_d_precipitated_analysis_binary_coefficients.asphaltenes_d_precipitated_analysis_id')->join('escenarios', 'escenarios.id', '=', 'asphaltenes_d_precipitated_analysis.scenario_id')->where('escenarios.id', $scenario_id)->select('asphaltenes_d_precipitated_analysis_binary_coefficients.coefficient')->first();

        return Response::json($precipitated_analysis_binary_coefficients);
    });


    Route::get('asphaltenes_d_stability_analysis_results', function()
    {
        $asphaltenes_d_stability_analysis_results_id = Input::get('asphaltenes_d_stability_analysis_results_id');

        $asphaltenes_d_stability_analysis_results = DB::table('asphaltenes_d_stability_analysis_results')->where('id', $asphaltenes_d_stability_analysis_results_id)->first();

        //$pozo = DB::table('asphaltenes_d_stability_analysis')->where('asphaltenes_d_stability_analysis.id', $asphaltenes_d_stability_analysis_results->asphaltenes_d_stability_analysis_id)->join('escenarios', 'escenarios.id', '=', 'asphaltenes_d_stability_analysis.scenario_id')->join('campos', 'campos.id', '=', 'escenarios.campo_id')->select('campos.id as well_name')->first();

        $scenario_id = DB::table('asphaltenes_d_stability_analysis')->where('id', $asphaltenes_d_stability_analysis_results->asphaltenes_d_stability_analysis_id)->select('asphaltenes_d_stability_analysis.scenario_id as id')->first();

        $pozo_id = DB::table('escenarios')->where('id', $scenario_id->id)->select('escenarios.pozo_id as id')->first();

        $pozo = DB::table('pozos')->where('id', $pozo_id->id)->select('pozos.nombre as well_name')->first();

        $data = array('asphaltenes_d_stability_analysis_results' => $asphaltenes_d_stability_analysis_results, 'pozo'=>$pozo);

        return Response::json($data);
    });

    Route::get('asphaltenes_d_stability_analysis_components', function()
    {
        $asphaltenes_d_stability_analysis_id = Input::get('asphaltenes_d_stability_analysis_id');

        $components_stability_analysis = DB::table('asphaltenes_d_stability_analysis_components')->where('asphaltenes_d_stability_analysis_id', $asphaltenes_d_stability_analysis_id)->select('component')
            ->orderBy('id', 'asc')->get();

        $mole_fraction_stability_analysis = DB::table('asphaltenes_d_stability_analysis_components')->where('asphaltenes_d_stability_analysis_id', $asphaltenes_d_stability_analysis_id)->select('mole_fraction')
        ->orderBy('id', 'asc')->get();

        $data = array('components_stability_analysis' => $components_stability_analysis, 'mole_fraction_stability_analysis'=>$mole_fraction_stability_analysis);

        return Response::json($data);
    });

    /* Rutas para resultados de análisis de asfaltenos precipitados */
    Route::get('asphaltenes_d_precipitated_analysis_saturation_results', function(){

        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');
        $saturation_results = DB::table('asphaltenes_d_precipitated_analysis_saturation_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->get();
        $saturation_data = DB::table('asphaltenes_d_precipitated_analysis_temperatures')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->get();
        $data = array("saturation_data"=>$saturation_data, "saturation_results"=>$saturation_results);
        return Response::json($data);
    });

    Route::get('asphaltenes_d_precipitated_analysis_solid_wat_results', function(){

        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');
        $temperatures = DB::table('asphaltenes_d_precipitated_analysis_solid_wat_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->select('temperature')->distinct()->get();
        foreach ($temperatures as $value) 
        {
            $solid_wat_row = DB::table('asphaltenes_d_precipitated_analysis_solid_wat_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->where('temperature', $value->temperature)->get();
            $solid_wat_results[$value->temperature] = $solid_wat_row;
        }

        return Response::json($solid_wat_results);
    });

    Route::get('asphaltenes_d_precipitated_analysis_solid_s_results', function(){

        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');
        $temperatures = DB::table('asphaltenes_d_precipitated_analysis_solid_s_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->select('temperature')->distinct()->get();
        foreach ($temperatures as $value) 
        {
            $solid_s_row = DB::table('asphaltenes_d_precipitated_analysis_solid_s_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->where('temperature', $value->temperature)->get();
            $solid_s_results[$value->temperature] = $solid_s_row;
        }

        return Response::json($solid_s_results);
    });

    Route::get('asphaltenes_d_precipitated_analysis_solid_a_results', function(){

        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');
        $temperatures = DB::table('asphaltenes_d_precipitated_analysis_solid_a_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->select('temperature')->distinct()->get();
        $solid_a_results = [];
        foreach ($temperatures as $value) {
            $solid_a_row = DB::table('asphaltenes_d_precipitated_analysis_solid_a_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->where('temperature', $value->temperature)->get();
            $solid_a_results[$value->temperature] = $solid_a_row;
        }

        return Response::json($solid_a_results);
    });

    Route::get('asphaltenes_d_precipitated_analysis_onset_results', function(){

        $asphaltenes_d_precipitated_analysis_id = Input::get('asphaltenes_d_precipitated_analysis_id');
        $onset_results = DB::table('asphaltenes_d_precipitated_analysis_onset_results')->where('asphaltenes_d_precipitated_analysis_id', $asphaltenes_d_precipitated_analysis_id)->get();
        return Response::json($onset_results);
    });

    /*Ruta para resultados diagnóstico asfaltenos*/
    Route::get('asphaltenes_d_diagnosis_results', function(){

        $asphaltenes_d_diagnosis_id = Input::get('asphaltenes_d_diagnosis_id');
        $dates = Input::get('dates');
        $dates = is_null($dates) ? [] : $dates;
        $results = [];
        foreach ($dates as $date) {
            $results_by_date = DB::table('asphaltenes_d_diagnosis_results')->where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis_id)->where('date', $date)->get();
            array_push($results, $results_by_date);
        }

        $max_damage_radius = DB::table('asphaltenes_d_diagnosis_results_skin')->where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis_id)->max('damage_radius');

        return Response::json(array($results, $max_damage_radius));
    });

    Route::get('asphaltenes_d_diagnosis_results_skin', function(){

        $asphaltenes_d_diagnosis_id = Input::get('asphaltenes_d_diagnosis_id');
        $data = DB::table('asphaltenes_d_diagnosis_results_skin')->where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis_id)->get();

        return Response::json($data);
    });

    /*Ruta para resultados diagnóstico finos*/
    Route::get('fines_d_diagnosis_results', function(){

        $fines_d_diagnosis_id = Input::get('fines_d_diagnosis_id');
        $dates = Input::get('dates');
        $results = [];
        foreach ($dates as $date) 
        {
            $results_by_date = DB::table('fines_d_diagnosis_results')->where('fines_d_diagnosis_id', $fines_d_diagnosis_id)->where('date', $date)->get();
            array_push($results, $results_by_date);
        }

        $max_damage_radius = DB::table('fines_d_diagnosis_results_skin')->where('fines_d_diagnosis_id', $fines_d_diagnosis_id)->max('damage_radius');

        return Response::json(array($results, $max_damage_radius));
    });

    Route::get('fines_d_diagnosis_results_skin', function(){

        $fines_d_diagnosis_id = Input::get('fines_d_diagnosis_id');
        $data = DB::table('fines_d_diagnosis_results_skin')->where('fines_d_diagnosis_id', $fines_d_diagnosis_id)->get();

        return Response::json($data);
    });


    /*Ruta para arbol de BD phenomenological asfaltenos*/
    Route::get('getPhenomenologicalData', function () {

        $fines_phenomenological_data_tree = App\fines_d_phenomenological_constants_database::select('id', 'field')
                                        ->get();
            $tree = [];
            foreach ($fines_phenomenological_data_tree as $fines_d_phenomenological_constants_database) {
                $fines_d_phenomenological_constants_database['icon'] = url('images/icon-field.png');
                $fines_d_phenomenological_constants_database['href'] = url('#link_phenomenological_data');
                $fines_d_phenomenological_constants_database['name'] = $fines_d_phenomenological_constants_database->field;          
                $fines_d_phenomenological_constants_database['id'] = $fines_d_phenomenological_constants_database->id; 
                $fines_d_phenomenological_constants_database['child'] = [];
            }
            $tree = $fines_phenomenological_data_tree;
            
        return Response::json($tree);
    });

    Route::get('get_phenomenological_data', function()
    {
        $phenomenological_data = Input::get('phenomenological_data');

        $fines_d_phenomenological_constants_database = DB::table('fines_d_phenomenological_constants_database')->where('id', $phenomenological_data)->select('value')->first();

        return Response::json($fines_d_phenomenological_constants_database);
    });

    /*Ruta para arbol de BD asfaltenos*/
    Route::get('getAsphaltenesData', function () {

        $asphaltenes_d_precipitated_analysis_data_tree = App\asphaltenes_d_precipitated_analysis_data::select('id', 'field')
                                        ->get();
            $tree = [];
            foreach ($asphaltenes_d_precipitated_analysis_data_tree as $asphaltenes_d_precipitated_analysis_data) {
                $asphaltenes_d_precipitated_analysis_data['icon'] = url('images/icon-field.png');
                $asphaltenes_d_precipitated_analysis_data['href'] = url('#link_asplatenes_data');
                $asphaltenes_d_precipitated_analysis_data['name'] = $asphaltenes_d_precipitated_analysis_data->field;          
                $asphaltenes_d_precipitated_analysis_data['id'] = $asphaltenes_d_precipitated_analysis_data->id; 
                $asphaltenes_d_precipitated_analysis_data['child'] = [];
            }
            $tree = $asphaltenes_d_precipitated_analysis_data_tree;
            
        return Response::json($tree);
    });

    Route::get('get_asphaltenes_data', function()
    {
        $asphaltenes_data = Input::get('asphaltenes_data');

        $asphaltenes_d_precipitated_analysis_data = DB::table('asphaltenes_d_precipitated_analysis_data')->where('id', $asphaltenes_data)->select('asphaltene_particle_diameter', 'asphaltene_molecular_weight', 'asphaltene_apparent_density')->first();

        return Response::json($asphaltenes_d_precipitated_analysis_data);
    });

    /*--------------------*/

    /*Ruta para arbol de advisor*/
    Route::get('getAdvisorTree', function () {
        $user_id = Auth::id();
        $user = App\User::where('id', '=', $user_id)->first();
        $rol = $user->office;
        $type = Input::get('type');

        function add_simulations_to_projects($project_tree, $type)
        {
            $group = [];
            foreach ($project_tree as $project) {
                $project['name'] = $project->name;
                $project['icon'] = url('images/icon-folder.png');
                $project['href'] = '';

                $wells = App\pozo::join('escenarios', 'escenarios.pozo_id', '=', 'pozos.id')
                    ->where('escenarios.proyecto_id', '=', $project->id)
                    ->where('escenarios.estado','=',1)
                    ->where('escenarios.tipo', '=', $type)
                    ->select('pozos.id as id', 'pozos.nombre as name')
                    ->raw('COUNT(escenarios.id) > 0')
                    ->distinct()
                    ->get();
                
                foreach ($wells as $well) {
                    $well['icon'] = url('images/icon-well.png');
                    $well['href'] = '';
                    $well['id'] = $well['id'];
                    $well['name'] = $well['name'];

                    $scenary = App\escenario::where('pozo_id', '=', $well->id)
                        ->where('proyecto_id', '=', $project->id)
                        ->where('escenarios.estado','=',1)
                        ->where('escenarios.tipo', '=', $type)
                        ->select('id', 'nombre')
                        ->get();

                    foreach ($scenary as $sce) {
                        $sce['icon'] = url('images/icon-scenario.png');
                        $sce['href'] = url('#link');
                        $sce['id'] = $sce['id'];
                        $sce['name'] = $sce['nombre'];
                        $sce['child'] = [];
                    }
                    $well['child'] = $scenary;
                }
                $project['child'] = $wells;
            }
        }

        if($rol == 0){
            $company_tree = App\company::select('company.id', 'company.name')
                ->join('proyectos', 'proyectos.compania', '=', 'company.id')
                ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                ->raw('COUNT(escenarios.id) > 0')
                ->where('escenarios.estado','=',1)
                ->where('escenarios.tipo', '=', $type)
                ->distinct()
                ->get();

            $tree = [];
            foreach ($company_tree as $company) {
                $company['icon'] = url('images/icon-company.png');
                $company['href'] = '';
                $projects = App\proyecto::select('proyectos.id as id', 'proyectos.nombre as name')
                    ->join('users', 'users.id', '=', 'proyectos.usuario_id')
                    ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                    ->raw('COUNT(escenarios.id) > 0')
                    ->where('escenarios.estado','=',1)
                    ->where('escenarios.tipo', '=', $type)
                    ->where('proyectos.compania',"=", $company->id)
                    ->distinct()
                    ->get();
                add_simulations_to_projects($projects, $type);

                $company['child'] = $projects;
            }
            $tree = $company_tree;
        } else {
            $projects = App\proyecto::select('proyectos.id', 'proyectos.nombre', 'proyectos.compania')
                ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                ->raw('COUNT(escenarios.id) > 0')
                ->where('escenarios.estado','=',1)
                ->where('escenarios.tipo', '=', $type)
                ->where('proyectos.compania',"=", $user->company)
                ->distinct()
                ->get();
            add_simulations_to_projects($projects, $type);
            
            $tree = $projects;
        }

        return Response::json($tree);
    });

    /*Ruta para arbol de advisor*/
    Route::get('getDuplicateTree', function () {
        $user_id = Auth::id();
        $user = App\User::where('id', '=', $user_id)->first();
        $rol = $user->office;
        $type = Input::get('type');
        $sub_type = Input::get('sub_type');

        function add_simulations_to_projects_dp($project_tree, $type, $sub_type)
        {
            $group = [];
            foreach ($project_tree as $project) {
                $project['name'] = $project->name;
                $project['icon'] = url('images/icon-folder.png');
                $project['href'] = '';

                $wells = App\pozo::join('escenarios', 'escenarios.pozo_id', '=', 'pozos.id')
                ->where('escenarios.proyecto_id', '=', $project->id)
                ->where('escenarios.estado','=',1)
                ->where('escenarios.tipo', '=', $type)
                ->select('pozos.id as id', 'pozos.nombre as name')
                ->raw('COUNT(escenarios.id) > 0')
                ->distinct();

                if ($sub_type != '' && $type != 'Asphaltene precipitation') {
                    if ($type == 'Multiparametric') {
                        $f_sub_type = 'multiparametricType';
                    } else if ($type == 'Asphaltene remediation') {
                        $f_sub_type = 'asphaltene_remediation';
                    }
                    $wells->where("escenarios.$f_sub_type","=","$sub_type");
                } else if ($type == 'Asphaltene precipitation') {

                    if ($sub_type == 'Asphaltene stability analysis') {
                        $wells->join('asphaltenes_d_stability_analysis', 'escenarios.id', '=', 'asphaltenes_d_stability_analysis.scenario_id');
                    } else if($sub_type == 'Asphaltene diagnosis') {
                        $wells->join('asphaltenes_d_diagnosis', 'escenarios.id', '=', 'asphaltenes_d_diagnosis.scenario_id');
                    } else if($sub_type == 'Precipitated asphaltene analysis') {
                        $wells->join('asphaltenes_d_precipitated_analysis', 'escenarios.id', '=', 'asphaltenes_d_precipitated_analysis.scenario_id');
                    }

                }
                
                $wells = $wells->get();

                foreach ($wells as $well) {
                    $well['icon'] = url('images/icon-well.png');
                    $well['href'] = '';
                    $well['id'] = $well['id'];
                    $well['name'] = $well['name'];

                    $scenary = App\escenario::where('pozo_id', '=', $well->id)
                                            ->where('proyecto_id', '=', $project->id)
                                            ->where('escenarios.estado','=',1)
                                            ->where('escenarios.tipo', '=', $type)
                                            ->select('escenarios.id', 'escenarios.nombre');

                    if ($sub_type != '' && $type != 'Asphaltene precipitation') {
                        if ($type == 'Multiparametric') {
                            $f_sub_type = 'multiparametricType';
                        } else if ($type == 'Asphaltene remediation') {
                            $f_sub_type = 'asphaltene_remediation';
                        }
                        $scenary->where("escenarios.$f_sub_type","=","$sub_type");
                    } else if ($type == 'Asphaltene precipitation') {

                        if ($sub_type == 'Asphaltene stability analysis') {
                            $scenary->join('asphaltenes_d_stability_analysis', 'escenarios.id', '=', 'asphaltenes_d_stability_analysis.scenario_id');
                        } else if($sub_type == 'Asphaltene diagnosis') {
                            $scenary->join('asphaltenes_d_diagnosis', 'escenarios.id', '=', 'asphaltenes_d_diagnosis.scenario_id');
                        } else if($sub_type == 'Precipitated asphaltene analysis') {
                            $scenary->join('asphaltenes_d_precipitated_analysis', 'escenarios.id', '=', 'asphaltenes_d_precipitated_analysis.scenario_id');
                        }

                    }
                
                    $scenary = $scenary->get();

                    foreach ($scenary as $sce) {
                        $sce['icon'] = url('images/icon-scenario.png');
                        $sce['href'] = url('#link');
                        $sce['class'] = 'link_external_tree';
                        $sce['id'] = $sce['id'];
                        $sce['name'] = $sce['nombre'];
                        $sce['child'] = [];
                    }
                    $well['child'] = $scenary;
                }
                $project['child'] = $wells;
            }
        }

        if($rol == 0){
            $company_tree = App\company::select('company.id', 'company.name')
                                        ->join('proyectos', 'proyectos.compania', '=', 'company.id')
                                        ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                                        ->raw('COUNT(escenarios.id) > 0')
                                        // ->where('escenarios.completo','=',1)
                                        ->where('escenarios.estado','=',1)
                                        ->where('escenarios.tipo', '=', $type)
                                        ->distinct()
                                        ->get();

            $tree = [];
            
            foreach ($company_tree as $company) {
                $company['icon'] = url('images/icon-company.png');
                $company['href'] = '';
                $projects = App\proyecto::select('proyectos.id as id', 'proyectos.nombre as name')
                            ->join('users', 'users.id', '=', 'proyectos.usuario_id')
                            ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                            ->raw('COUNT(escenarios.id) > 0')
                            ->where('escenarios.estado','=',1)
                            // ->where('escenarios.completo','=',1)
                            ->where('escenarios.tipo', '=', $type)
                            ->where('proyectos.compania',"=", $company->id)
                            ->distinct()
                            ->get();
                add_simulations_to_projects_dp($projects, $type, $sub_type);

                $company['child'] = $projects;
            }
            $tree = $company_tree;
            
        } else {
            $projects = App\proyecto::select('proyectos.id', 'proyectos.nombre as name', 'proyectos.compania')
                                    ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                                    ->raw('COUNT(escenarios.id) > 0')
                                    ->where('escenarios.estado','=',1)
                                    // ->where('escenarios.completo','=',1)
                                    ->where('escenarios.tipo', '=', $type)
                                    ->where('proyectos.compania',"=", $user->company)
                                    ->distinct()
                                    ->get();
            add_simulations_to_projects_dp($projects, $type, $sub_type);
            
            $tree = $projects;
        }

        return Response::json($tree);
    });

    Route::get('ImportExternalTree','fines_remediationController@getImportExternalTree');
    Route::get('getTreeExternalData/{id}','fines_remediationController@getTreeExternalData');
    Route::get('ImportExternalTreeAd','add_asphaltenes_diagnosis_controller@getImportExternalTree');
    Route::get('getTreeExternalDataAd/{id}','add_asphaltenes_diagnosis_controller@getTreeExternalData');

    Route::get('getAdvisorValue', function () {
        $scenario_id = Input::get('scenario_id');
        $type = Input::get('type');
        $input_name = Input::get('input_name');
        $type = strtolower($type);
        $type_advisor = Input::get('advisor');
        $type_advisor = strtolower($type_advisor);


        $query = \DB::table('advisor')->select($type_advisor)->first();


        $query = $query->$type_advisor;
        $query = json_decode($query); 
        $query = $query->$input_name;
        
        $query = str_replace("put_scenario_id", $scenario_id, (string)$query);

        $value = \DB::select($query);

        return Response::json($value);
    });

    /*--------------------*/


    #*************/////////////
    #Add filtration function
    Route::resource('filtration_function_list','filtration_function_controller@list_filtration_functions');
    Route::resource('filtration_function', 'filtration_function_controller');
    Route::resource('add_filtration_function_u', 'add_filtration_function_universal_controller');
    Route::resource('list_filtration_function', 'list_filtration_function_controller');

    Route::get('laboratory_test_data', function()
    {
        $filtration_function_id = Input::get('filtration_function_id');
        $complete_laboratory_tests_data = []; //[[k,pob,[lab test]],...]
        $laboratory_tests = App\d_laboratory_test::where('d_filtration_function_id',$filtration_function_id)->get();
        foreach ($laboratory_tests as $lab_test) 
        {
            $laboratory_test_data = [];
            $laboratory_test_data_query = App\d_laboratory_test_data::where('d_laboratory_test_id',$lab_test->id)->get();
            foreach ($laboratory_test_data_query as $lab_test_data) 
            {
                array_push($laboratory_test_data,array($lab_test_data->time, $lab_test_data->filtered_volume));
            }
            array_push($complete_laboratory_tests_data, array($lab_test->permeability, $lab_test->pob, $laboratory_test_data));
        }

        return Response::json($complete_laboratory_tests_data);
    });

    Route::get('field_basin_ids_by_formation', function()
    {
        $formation_id = Input::get('formation_id');
        $formation_query = App\formacion::where('id',$formation_id)->first();
        $field_query = App\campo::where('id',$formation_query->campo_id)->first();
        $basin_query = App\cuenca::where('id',$field_query->cuenca_id)->first();

        return Response::json(array($field_query->id, $basin_query->id)); 
    });

    #Data Inventory
    #Detailed data - well
    Route::get('detailed_data', array('as'=>'detailed_data', function()
    {
        $well_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();
        $pozos = App\pozo::orderBy('nombre')->get();
        $data = ['cuencas' => $cuencas,'pozos' => $pozos, 'well_id' => $well_id];
        #return Response::json($data);
        return  view('dataInventory_detailed', ['cuencas' => $cuencas,'pozos' => $pozos, 'well_id' => $well_id]);
    }));

    Route::get('detailed_data_general', array('as'=>'detailed_data_general', function()
    {
        $well_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();
        $pozos = App\pozo::orderBy('nombre')->get();
        $data = ['cuencas' => $cuencas,'pozos' => $pozos, 'well_id' => $well_id];
        #return Response::json($data);
        return  view('data_inventory_detailed_well_general', ['cuencas' => $cuencas,'pozos' => $pozos, 'well_id' => $well_id]);
    }));

    #Detailed data - field
    Route::get('detailed_data_field', array('as'=>'detailed_data_field', function()
    {
        $field_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();

        return  view('data_inventory_detailed_field', ['cuencas' => $cuencas, 'field_id' => $field_id]);
    }));

    #Detailed data - field-general
    Route::get('detailed_data_field_general', array('as'=>'detailed_data_field_general', function()
    {
        $field_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();

        return  view('data_inventory_detailed_field_general', ['cuencas' => $cuencas, 'field_id' => $field_id]);
    }));

    #Detailed data interval 
    Route::get('detailed_data_interval', array('as'=>'detailed_data_interval', function()
    {
        $interval_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();

        return  view('data_inventory_detailed_interval', ['cuencas' => $cuencas, 'interval_id' => $interval_id]);
    }));

    #Detailed data interval - general
    Route::get('detailed_data_interval_general', array('as'=>'detailed_data_interval_general', function()
    {
        $interval_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();

        return  view('data_inventory_detailed_interval_general', ['cuencas' => $cuencas, 'interval_id' => $interval_id]);
    }));

    Route::get('detailed_data_formation', array('as'=>'detailed_data_formation', function()
    {
        $formation_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();

        return  view('data_inventory_detailed_formation', ['cuencas' => $cuencas, 'formation_id' => $formation_id]);
    }));

    Route::get('detailed_data_formation_general', array('as'=>'detailed_data_formation_general', function()
    {
        $formation_id = Input::get('id');
        $cuencas = App\cuenca::orderBy('nombre')->get();

        return  view('data_inventory_detailed_formation_general', ['cuencas' => $cuencas, 'formation_id' => $formation_id]);
    }));

    Route::get('dataInventory', array('as'=>'dataInventory', function()
    {
        if (\Auth::check()) {
            $cuencas = App\cuenca::orderBy('nombre')->get();
            $pozos = App\pozo::orderBy('nombre')->get();
            return  view('dataInventory', ['cuencas' => $cuencas,'pozos' => $pozos]);
        }else{
            return view('loginfirst');
        }
    }));

    #Scenario inventory
    Route::get('fieldInventory', array('as'=>'fieldInventory', function()
    {
        $basin = Input::get('basin');
        $fields = App\campo::where('cuenca_id','=',$basin)->get();
        $fields_with_pvt = App\pvt::select('campo_id')->distinct()->get();
        $fields_ids = [];
        foreach ($fields_with_pvt as $field_pvt) 
        {
            array_push($fields_ids, $field_pvt->campo_id);
        }
        $fields_with_pvt_info = [];
        foreach ($fields as $field) 
        {
            if(in_array($field->id, $fields_ids))
            {
                $aux = [];
                array_push($aux, $field->nombre);
                array_push($aux, True);
                array_push($aux, $field->id);
                array_push($fields_with_pvt_info, $aux);
            }
            else
            {
                $aux = [];
                array_push($aux, $field->nombre);
                array_push($aux, False);
                array_push($aux, $field->id);
                array_push($fields_with_pvt_info, $aux);
            }
        }

        $data = array('fields' => $fields, 'fields_info'=>$fields_with_pvt_info);
        return Response::json($data);
    }));

    #Scenarios by field
    Route::get('scenarios_by_field', array('as'=>'scenarios_by_field', function()
    {
        $field = Input::get('field');
        $wells = App\pozo::where('campo_id',$field)->get();
        $well_data = [];
        foreach ($wells as $well) 
        {
            $well_row = [];
            array_push($well_row, $well->nombre);
            $ipr_query_well = App\escenario::select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','IPR')->where('completo',1)->first();
            $ipr_query_well_incomplete = App\escenario::select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','IPR')->where('completo',0)->first();
            $mp_query_well = App\escenario::select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','Multiparametric')->where('completo',1)->first();
            $mp_query_well_incomplete = App\escenario::select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','Multiparametric')->where('completo',0)->first();
            $drilling_query_well = App\escenario::select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','Drilling')->where('completo',1)->first();
            $drilling_query_well_incomplete = App\escenario::select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','Drilling')->where('completo',0)->first();
            $dissagrigation_query_well = App\escenario::join('desagregacion as d', 'd.id_escenario','=','escenarios.id')->select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','IPR')->where('completo',1)->where('completo',0)->first();
            $dissagrigation_query_well_incomplete = App\escenario::join('desagregacion as d', 'd.id_escenario','=','escenarios.id')->select(DB::raw('count(*) as count'))->where('pozo_id', $well->id)->where('tipo','IPR')->first();
            array_push($well_row, $ipr_query_well->count);
            array_push($well_row, $ipr_query_well_incomplete->count);
            array_push($well_row, $mp_query_well->count);
            array_push($well_row, $mp_query_well_incomplete->count);
            array_push($well_row, $drilling_query_well->count);
            array_push($well_row, $drilling_query_well_incomplete->count);
            array_push($well_row, $dissagrigation_query_well->count);
            array_push($well_row, $dissagrigation_query_well_incomplete->count);
            array_push($well_data, $well_row);
        }

        $ipr_query_field = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','IPR')->where('p.campo_id',$field)
        ->where('estado',1)->where('completo',1)->first();

        $mp_query_field = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','Multiparametric')
        ->where('p.campo_id',$field)
        ->where('estado',1)
        ->where('completo',1)->first();

        $drilling_query_field = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','Drilling')
        ->where('p.campo_id',$field)
        ->where('estado',1)
        ->where('completo',1)->first();

        $dissagrigation_query_field = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->join('desagregacion as d','escenarios.id','=','d.id_escenario')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','IPR')
        ->where('p.campo_id',$field)
        ->where('estado',1)
        ->where('completo',1)->first();

        $ipr_query_field_incomplete = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','IPR')->where('p.campo_id',$field)
        ->where('completo',0)->first();

        $mp_query_field_incomplete = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','Multiparametric')
        ->where('p.campo_id',$field)
        ->where('completo',0)->first();

        $drilling_query_field_incomplete = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','Drilling')
        ->where('p.campo_id',$field)
        ->where('completo',0)->first();

        $dissagrigation_query_field_incomplete = App\escenario::join('pozos as p', 'p.id','=','escenarios.pozo_id')
        ->join('desagregacion as d','escenarios.id','=','d.id_escenario')
        ->select(DB::raw('count(distinct p.id) as count'))
        ->where('tipo','IPR')
        ->where('p.campo_id',$field)
        ->where('completo',0)->first();

        $wells_amount = App\pozo::select(DB::raw('count(*) as count'))->where('campo_id',$field)->first();
        $field_data = [$wells_amount->count, $ipr_query_field->count,$mp_query_field->count,$drilling_query_field->count,$dissagrigation_query_field->count,$ipr_query_field_incomplete->count,$mp_query_field_incomplete->count,$drilling_query_field_incomplete->count,$dissagrigation_query_field_incomplete->count];

        $data = array('well_data' => $well_data, 'field_data'=>$field_data);
        return Response::json($data);
    }));

    #General data inventory: field
    Route::get('fieldInventory_general', array('as'=>'fieldInventory_general', function()
    {
        $basin = Input::get('basin');
        $fields = App\campo::where('cuenca_id','=',$basin)->get();
        $fields_with_pvt = App\pvt::select('campo_id')->distinct()->get();
        $fields_with_coordinates = App\coordenada_campo::select('campo_id')->distinct()->get();
        $fields_ids_pvt = [];
        $fields_ids_coordinates = [];
        foreach ($fields_with_pvt as $field_pvt) 
        {
            array_push($fields_ids_pvt, $field_pvt->campo_id);
        }
        foreach ($fields_with_coordinates as $field_coordinates) 
        {
            array_push($fields_ids_coordinates, $field_coordinates->campo_id);
        }
        $fields_info = [];
        foreach ($fields as $field) 
        {
            $aux = [];
            if(in_array($field->id, $fields_ids_pvt))
            {
                $pvt_flag = True;
            }
            else
            {
                $pvt_flag = False;
            }
            if(in_array($field->id, $fields_ids_coordinates))
            {
                $coordinate_flag = True;
            }
            else
            {
                $coordinate_flag = False;
            }
            array_push($aux, $field->id);
            array_push($aux, $field->nombre);
            array_push($aux, $pvt_flag);
            array_push($aux, $coordinate_flag);
            array_push($fields_info, $aux);

        }

        $data = array('fields' => $fields, 'fields_info'=>$fields_info);
        return Response::json($data);
    }));

    Route::get('field_inventory_detailed', array('as'=>'field_inventory_detailed', function()
    {
        $field = Input::get('field');
        $pvt_query = App\pvt::where('campo_id',$field)->get();
        $coordinates_query = App\coordenada_campos::where('campo_id',$field)->get();
        $pvt = [];
        $coordinates = [];
        foreach ($pvt_query as $value) 
        {
            $row_aux = [];
            array_push($row_aux, $value->pressure);
            array_push($row_aux, $value->uo);
            array_push($row_aux, $value->ug);
            array_push($row_aux, $value->uw);
            array_push($row_aux, $value->bo);
            array_push($row_aux, $value->bg);
            array_push($row_aux, $value->bw);
            array_push($row_aux, $value->rs);
            array_push($row_aux, $value->rv);
            array_push($pvt, $row_aux);
        }
        foreach ($coordinates_query as $value) 
        {
            $row_aux = [];
            array_push($row_aux, $value->lat);
            array_push($row_aux, $value->lon);
            array_push($coordinates, $row_aux);
        }
        $data = array('pvt' => $pvt, 'coordinates' => $coordinates);
        return Response::json($data);
    }));

    Route::get('interval_inventory_detailed', array('as'=>'interval_inventory_detailed', function()
    {
        $interval = Input::get('interval');
        $interval_query = App\formacionxpozo::where('id',$interval)->get();
        $gas_oil_query = App\permeabilidad_relativaxf_gl::where('formacionxpozo_id',$interval)->get();
        $water_oil_query = App\permeabilidad_relativaxf_wo::where('formacionxpozo_id',$interval)->get();
        $gas_oil = [];
        $water_oil = [];
        foreach ($gas_oil_query as $value) 
        {
            $row_aux = [];
            array_push($row_aux, $value->sg);
            array_push($row_aux, $value->krg);
            array_push($row_aux, $value->krl);
            array_push($gas_oil, $row_aux);
        }
        foreach ($water_oil_query as $value) 
        {
            $row_aux = [];
            array_push($row_aux, $value->sw);
            array_push($row_aux, $value->krw);
            array_push($row_aux, $value->kro);
            array_push($water_oil, $row_aux);
        }

        $data = array('interval' => $interval_query, 'gas_oil' => $gas_oil, 'water_oil'=>$water_oil);
        return Response::json($data);
    }));

    Route::get('formation_inventory_detailed', array('as'=>'formation_inventory_detailed', function()
    {
        $formation = Input::get('formation');
        $formation_query = App\formacion::where('id',$formation)->get();
        $water_oil_query = App\permeabilidad_relativa_wo::where('formacion_id', $formation)->get();
        $gas_oil_query = App\permeabilidad_relativa_gl::where('formacion_id', $formation)->get();

        $water_oil_data = [];
        $gas_liquid_data = [];
        foreach ($water_oil_query as $value) 
        {
            array_push($water_oil_data, [$value->sw, $value->krw, $value->kro, $value->pcwo]);
        }
        foreach ($gas_oil_query as $value) 
        {
            array_push($gas_liquid_data, [$value->sg, $value->krg, $value->krl, $value->pcgl]);
        }
        $data = array('formation' => $formation_query, 'water_oil'=>$water_oil_data, 'gas_liquid'=>$gas_liquid_data);
        return Response::json($data);
    }));

    Route::get('producing_interval_inventory', array('as'=>'producing_interval_inventory', function()
    {
        $formation = Input::get('formation');
        $producing_intervals = App\formacionxpozo::where('formacion_id','=',$formation)->get();
        $kr_gas_info = App\permeabilidad_relativaxf_gl::select('formacionxpozo_id')->distinct()->get();
        $kr_water_info = App\permeabilidad_relativaxf_wo::select('formacionxpozo_id')->distinct()->get();

        $kr_gas_ids =[];
        $kr_water_ids =[];
        foreach ($kr_gas_info as $kr_gas_info_value) 
        {
            array_push($kr_gas_ids, $kr_gas_info_value->formacionxpozo_id);
        }
        foreach ($kr_water_info as $kr_water_info_value) 
        {
            array_push($kr_water_ids, $kr_water_info_value->formacionxpozo_id);
        }

        $producing_intervals_ids = [];
        $intervals_info = [];
        foreach ($producing_intervals as $producing_interval) 
        {
            array_push($producing_intervals_ids, $producing_interval->id);
            $top_flag = True;
            $netpay_flag = True;
            $porosity_flag = True;
            $permeability_flag = True;
            $reservoir_pressure_flag = True;

            #Contadores
            $c_multiparametric = 0;
            $c_oil_ipr = 0;
            $c_gas_ipr = 0;
            $c_condensate_gas_ipr = 0;
            $c_disaggregation = 0;
            $c_drilling = 0;


            if(is_null($producing_interval->top) || $producing_interval->top === '')
            {
                $top_flag=False;
            }
            else
            {
                $top_flag=True;

                $c_drilling += 1;
            }
            if(is_null($producing_interval->netpay) || $producing_interval->netpay === '')
            {
                $netpay_flag=False;
            }
            else
            {
                $netpay_flag=True;

                $c_disaggregation += 1;
            }
            if(is_null($producing_interval->porosidad) || $producing_interval->porosidad === '')
            {
                $porosity_flag=False;
            }
            else
            {
                $porosity_flag=True;

                $c_drilling += 1;
            }
            if(is_null($producing_interval->permeabilidad) || $producing_interval->permeabilidad === '')
            {
                $permeability_flag=False;
            }
            else
            {
                $permeability_flag=True;

                $c_drilling += 1;
            }
            if(is_null($producing_interval->presion_reservorio) || $producing_interval->presion_reservorio === '')
            {
                $reservoir_pressure_flag=False;
            }
            else
            {
                $reservoir_pressure_flag=True;

                $c_drilling += 1;
            }
            if(in_array($producing_interval->id, $kr_gas_ids))
            {
                $kr_gas_flag = True;

                $c_oil_ipr += 1;
                $c_condensate_gas_ipr += 1;
            }
            else
            {
                $kr_gas_flag = False;
            }
            if(in_array($producing_interval->id, $kr_water_ids))
            {
                $kr_water_flag = True;

                $c_oil_ipr += 1;
            }
            else
            {
                $kr_water_flag = False;
            }

            $ipr_oil_status = $kr_gas_flag && $kr_water_flag;
            $ipr_gas_status = True;
            $ipr_condensate_gas_status = $kr_gas_flag;

            $mp_status = True;

            $dissagrigation_status = $reservoir_pressure_flag && $netpay_flag && $permeability_flag;

            $drilling_status = $top_flag && $reservoir_pressure_flag && $porosity_flag && $permeability_flag;

            $aux = [];
            $well = App\pozo::where('id',$producing_interval->pozo_id)->first();
            array_push($aux, $producing_interval->nombre);
            array_push($aux, $mp_status);
            array_push($aux, $ipr_oil_status);
            array_push($aux, $ipr_gas_status);
            array_push($aux, $ipr_condensate_gas_status);
            array_push($aux, $dissagrigation_status);
            array_push($aux, $drilling_status);
            array_push($aux, $well->nombre);
            array_push($aux, $c_multiparametric);
            array_push($aux, $c_oil_ipr);
            array_push($aux, $c_gas_ipr);
            array_push($aux, $c_condensate_gas_ipr);
            array_push($aux, $c_disaggregation);
            array_push($aux, $c_drilling);
            array_push($aux, $producing_interval->id);
            array_push($intervals_info, $aux);
        }

        return Response::json($intervals_info);
    }));

    #Intervalo en data inventory general
    Route::get('producing_interval_inventory_general', array('as'=>'producing_interval_inventory_general', function()
    {
        $formation = Input::get('formation');
        $producing_intervals = App\formacionxpozo::where('formacion_id','=',$formation)->get();
        $kr_gas_info = App\permeabilidad_relativaxf_gl::select('formacionxpozo_id')->distinct()->get();
        $kr_water_info = App\permeabilidad_relativaxf_wo::select('formacionxpozo_id')->distinct()->get();

        $kr_gas_ids =[];
        $kr_water_ids =[];
        foreach ($kr_gas_info as $kr_gas_info_value) 
        {
            array_push($kr_gas_ids, $kr_gas_info_value->formacionxpozo_id);
        }
        foreach ($kr_water_info as $kr_water_info_value) 
        {
            array_push($kr_water_ids, $kr_water_info_value->formacionxpozo_id);
        }

        $producing_intervals_ids = [];
        $intervals_info = [];
        foreach ($producing_intervals as $producing_interval) 
        {
            array_push($producing_intervals_ids, $producing_interval->id);
            $top_flag = True;
            $netpay_flag = True;
            $porosity_flag = True;
            $permeability_flag = True;
            $reservoir_pressure_flag = True;

            #Contadores
            $c_data = 0;

            if(is_null($producing_interval->top) || $producing_interval->top === '')
            {
                $top_flag=False;
            }
            else
            {
                $top_flag=True;
                $c_data += 1;
            }
            if(is_null($producing_interval->netpay) || $producing_interval->netpay === '')
            {
                $netpay_flag=False;
            }
            else
            {
                $netpay_flag=True;
                $c_data += 1;
            }
            if(is_null($producing_interval->porosidad) || $producing_interval->porosidad === '')
            {
                $porosity_flag=False;
            }
            else
            {
                $porosity_flag=True;
                $c_data += 1;
            }
            if(is_null($producing_interval->permeabilidad) || $producing_interval->permeabilidad === '')
            {
                $permeability_flag=False;
            }
            else
            {
                $permeability_flag=True;
                $c_data += 1;
            }
            if(is_null($producing_interval->presion_reservorio) || $producing_interval->presion_reservorio === '')
            {
                $reservoir_pressure_flag=False;
            }
            else
            {
                $reservoir_pressure_flag=True;
                $c_data += 1;
            }
            if(in_array($producing_interval->id, $kr_gas_ids))
            {
                $kr_gas_flag = True;
                $c_data += 1;
            }
            else
            {
                $kr_gas_flag = False;
            }
            if(in_array($producing_interval->id, $kr_water_ids))
            {
                $kr_water_flag = True;
                $c_data += 1;
            }
            else
            {
                $kr_water_flag = False;
            }

            $reservoir_pressure_historic_query = App\presion_yacimiento::select(DB::raw('count(*) as count'))->where('id_intervalo',$producing_interval->id)->first();
            if($reservoir_pressure_historic_query->count == 0)
            {
                $reservoir_pressure_historic_flag = False;
            }
            else
            {
                $reservoir_pressure_historic_flag = True;   
                $c_data += 1;
            }

            $aux = [];
            $well = App\pozo::where('id',$producing_interval->pozo_id)->first();
            array_push($aux, $producing_interval->id);
            array_push($aux, $producing_interval->nombre);
            array_push($aux, $top_flag);
            array_push($aux, $netpay_flag);
            array_push($aux, $porosity_flag);
            array_push($aux, $permeability_flag);
            array_push($aux, $reservoir_pressure_flag);
            array_push($aux, $kr_water_flag);
            array_push($aux, $kr_gas_flag);
            array_push($aux, $reservoir_pressure_historic_flag);
            array_push($aux, $well->nombre);
            array_push($aux, $c_data);

            array_push($intervals_info, $aux);
        }

        return Response::json($intervals_info);
    }));

    #Well Inventory - Scenarios
    Route::get('wellInventory', array('as'=>'wellInventory', function()
    {
        $field = Input::get('field');
        $wells = App\pozo::where('campo_id','=',$field)->get();
        $wells_inventory = [];
        foreach ($wells as $well)
        {
            #Well 
            $radius_flag=False;
            $drainage_radius_flag=False;
            $oil_rate_flag=False;
            $gas_rate_flag=False;
            $bhp_flag=False;
            $tvd_flag=False;

            #Fluid x Well
            $saturation_pressure_flag=False;
            $fluid_flag=False;
            $oil_viscosity_flag=False;
            $fvfo_flag=False;
            $gas_viscosity_flag=False;
            $fvfg_flag=False;
            $water_viscosity_flag=False;
            $fvfw_flag=False;
            $gor_flag=False;
            $specific_gas_gravity_flag=False;

            #Contadores
            $c_multiparametric = 0;
            $c_oil_ipr = 0;
            $c_gas_ipr = 0;
            $c_condensate_gas_ipr = 0;
            $c_disaggregation = 0;

            if(is_null($well->radius) || $well->radius === '')
            {
                $radius_flag=False;
            }
            else
            {
                $radius_flag=True;
                $c_multiparametric += 1;
                $c_oil_ipr += 1;
                $c_gas_ipr += 1;
                $c_condensate_gas_ipr += 1;
                $c_disaggregation += 1;                
            }
            if(is_null($well->drainage_radius) || $well->drainage_radius === '')
            {
                $drainage_radius_flag=False;
            }
            else
            {
                $drainage_radius_flag=True;
                $c_multiparametric += 1;
                $c_oil_ipr += 1;
                $c_gas_ipr += 1;
                $c_condensate_gas_ipr += 1;
                $c_disaggregation += 1;                
            }
            if(is_null($well->oil_rate) || $well->oil_rate === '')
            {
                $oil_rate_flag=False;
            }
            else
            {
                $oil_rate_flag=True;
                $c_multiparametric += 1;
                $c_oil_ipr += 1;
                $c_disaggregation += 1;                
            }
            if(is_null($well->gas_rate) || $well->gas_rate === '')
            {
                $gas_rate_flag=False;
            }
            else
            {
                $gas_rate_flag=True;
                $c_multiparametric += 1;
                $c_gas_ipr += 1;
                $c_condensate_gas_ipr += 1;
                $c_disaggregation += 1;                
            }
            if(is_null($well->bhp) || $well->bhp === '')
            {
                $bhp_flag=False;
            }
            else
            {
                $bhp_flag=True;
                $c_multiparametric += 1;
                $c_oil_ipr += 1;
                $c_gas_ipr += 1;
                $c_condensate_gas_ipr += 1;
                $c_disaggregation += 1;                
            }
            if(is_null($well->tdv) || $well->tdv === '')
            {
                $tvd_flag=False;
            }
            else
            {
                $tvd_flag=True;
                $c_multiparametric += 1;
                $c_disaggregation += 1;                
            }
            $fluid = App\fluidoxpozos::where('pozo_id','=',$well->id)->groupBy('pozo_id')->get();
            foreach ($fluid as $value) 
            {
                if(is_null($value->saturation_pressure) || $value->saturation_pressure === '')
                {
                    $saturation_pressure_flag=False;
                }
                else
                {
                    $saturation_pressure_flag=True;
                    $c_multiparametric += 1;
                    $c_oil_ipr += 1;
                    $c_condensate_gas_ipr += 1;           
                }
                if(is_null($value->oil_viscosity) || $value->oil_viscosity === '')
                {
                    $oil_viscosity_flag=False;
                }
                else
                {
                    $oil_viscosity_flag=True;
                    $c_multiparametric += 1;
                    $c_disaggregation += 1;                
                }
                if(is_null($value->fvfo) || $value->fvfo === '')
                {
                    $fvfo_flag=False;
                }
                else
                {
                    $fvfo_flag=True;
                    $c_multiparametric += 1;              
                }
                if(is_null($value->gas_viscosity) || $value->gas_viscosity === '')
                {
                    $gas_viscosity_flag=False;
                }
                else
                {
                    $gas_viscosity_flag=True;
                    $c_multiparametric += 1;
                    $c_disaggregation += 1;                
                }
                if(is_null($value->fvfg) || $value->fvfg === '')
                {
                    $fvfg_flag=False;
                }
                else
                {
                    $fvfg_flag=True;
                    $c_multiparametric += 1;            
                }
                if(is_null($value->water_viscosity) || $value->water_viscosity === '')
                {
                    $water_viscosity_flag=False;
                }
                else
                {
                    $water_viscosity_flag=True;
                    $c_multiparametric += 1;             
                }
                if(is_null($value->fvfw) || $value->fvfw === '')
                {
                    $fvfw_flag=False;
                }
                else
                {
                    $fvfw_flag=True;
                    $c_multiparametric += 1;
                   
                }
                if(is_null($value->gor) || $value->gor === '')
                {
                    $gor_flag=False;
                }
                else
                {
                    $gor_flag=True;
                    $c_condensate_gas_ipr += 1;        
                }
                if(is_null($value->specific_gas) || $value->specific_gas === '')
                {
                    $specific_gas_gravity_flag=False;
                }
                else
                {
                    $specific_gas_gravity_flag=True;
                    $c_disaggregation += 1;                
                }
                if(is_null($value->tipo) || $value->tipo === '' || $value->tipo === ' ')
                {
                    $fluid_flag=False;
                }
                else
                {
                    $fluid_flag=True;
                    $c_multiparametric += 1;
                    $c_oil_ipr += 1;
                    $c_gas_ipr += 1;
                    $c_condensate_gas_ipr += 1;              
                }
            }

            $exist_interval = App\formacionxpozo::select(DB::raw('count(*) as count'))->where('pozo_id','=', $well->id)->first();
            if($exist_interval->count==0)
            {
                $interval_flag = False;
            }
            else
            {
                $interval_flag = True;
            }

            $ipr_oil_status = $fluid_flag && $radius_flag && $drainage_radius_flag && $oil_rate_flag && $bhp_flag && $saturation_pressure_flag;
            $ipr_gas_status = $fluid_flag && $radius_flag && $drainage_radius_flag && $gas_rate_flag && $bhp_flag;
            $ipr_condensate_gas_status = $fluid_flag && $radius_flag && $drainage_radius_flag && $gas_rate_flag && $bhp_flag && $saturation_pressure_flag && $gor_flag;

            $mp_status = $tvd_flag && $radius_flag && $drainage_radius_flag && $bhp_flag && $oil_rate_flag && $gas_rate_flag && $fluid_flag && $saturation_pressure_flag && $oil_viscosity_flag && $fvfo_flag && $gas_viscosity_flag && $fvfg_flag && $water_viscosity_flag && $fvfw_flag;

            $dissagrigation_status = $tvd_flag && $radius_flag && $drainage_radius_flag && $bhp_flag && $oil_rate_flag && $gas_rate_flag && $oil_viscosity_flag && $gas_viscosity_flag && $specific_gas_gravity_flag;

            $aux=[];
            array_push($aux,$well->nombre);
            array_push($aux,$ipr_oil_status);
            array_push($aux,$ipr_gas_status);
            array_push($aux,$ipr_condensate_gas_status);
            array_push($aux,$mp_status);
            array_push($aux,$dissagrigation_status);
            array_push($aux,$well->id);
            array_push($aux,$c_multiparametric);
            array_push($aux,$c_oil_ipr);
            array_push($aux,$c_gas_ipr);
            array_push($aux,$c_condensate_gas_ipr);
            array_push($aux,$c_disaggregation);
            array_push($wells_inventory, $aux);
        }
        $data = array("wells"=>$wells, "wells_inventory"=>$wells_inventory);
        return Response::json($data);
    }));
    #Well Inventory - General
    Route::get('wellInventory_general', array('as'=>'wellInventory_general', function()
    {
        $field = Input::get('field');
        $wells = App\pozo::where('campo_id','=',$field)->get();
        $wells_inventory = [];
        foreach ($wells as $well)
        {
            #Well 
            $radius_flag=False;
            $drainage_radius_flag=False;
            $oil_rate_flag=False;
            $gas_rate_flag=False;
            $bhp_flag=False;
            $tvd_flag=False;
            $latitude_flag=False;
            $longitude_flag=False;
            $production_data_flag=False;

            #Fluid x Well
            $saturation_pressure_flag=False;
            $fluid_flag=False;
            $oil_viscosity_flag=False;
            $fvfo_flag=False;
            $gas_viscosity_flag=False;
            $fvfg_flag=False;
            $water_viscosity_flag=False;
            $fvfw_flag=False;
            $gor_flag=False;
            $specific_gas_gravity_flag=False;
            $api_gravity_flag=False;
            $wor_flag=False;
            $cgr_flag=False;
            $lgr_flag=False;
            $gwr_flag=False;
            
            #Production Data
            $production_data_flag = False; 

            #Contadores
            $c_data = 0;


            if(is_null($well->radius) || $well->radius === '')
            {
                $radius_flag=False;
            }
            else
            {
                $radius_flag=True;
                $c_data += 1;        
            }
            if(is_null($well->drainage_radius) || $well->drainage_radius === '')
            {
                $drainage_radius_flag=False;
            }
            else
            {
                $drainage_radius_flag=True;
                $c_data += 1;            
            }
            if(is_null($well->oil_rate) || $well->oil_rate === '')
            {
                $oil_rate_flag=False;
            }
            else
            {
                $oil_rate_flag=True;
                $c_data += 1;                
            }
            if(is_null($well->gas_rate) || $well->gas_rate === '')
            {
                $gas_rate_flag=False;
            }
            else
            {
                $gas_rate_flag=True;
                $c_data += 1;              
            }
            if(is_null($well->bhp) || $well->bhp === '')
            {
                $bhp_flag=False;
            }
            else
            {
                $bhp_flag=True;
                $c_data += 1;               
            }
            if(is_null($well->tdv) || $well->tdv === '')
            {
                $tvd_flag=False;
            }
            else
            {
                $tvd_flag=True;
                $c_data += 1;             
            }
            if(is_null($well->lat) || $well->lat === '')
            {
                $latitude_flag=False;
            }
            else
            {
                $latitude_flag=True;          
            }
            if(is_null($well->lon) || $well->lon === '')
            {
                $longitude_flag=False;
            }
            else
            {
                $longitude_flag=True;          
            }

            $fluid = App\fluidoxpozos::where('pozo_id','=',$well->id)->groupBy('pozo_id')->get();
            foreach ($fluid as $value) 
            {
                if(is_null($value->saturation_pressure) || $value->saturation_pressure === '')
                {
                    $saturation_pressure_flag=False;
                }
                else
                {
                    $saturation_pressure_flag=True;
                    $c_data += 1;       
                }
                if(is_null($value->oil_viscosity) || $value->oil_viscosity === '')
                {
                    $oil_viscosity_flag=False;
                }
                else
                {
                    $oil_viscosity_flag=True;
                    $c_data += 1;             
                }
                if(is_null($value->fvfo) || $value->fvfo === '')
                {
                    $fvfo_flag=False;
                }
                else
                {
                    $fvfo_flag=True;
                    $c_data += 1;              
                }
                if(is_null($value->gas_viscosity) || $value->gas_viscosity === '')
                {
                    $gas_viscosity_flag=False;
                }
                else
                {
                    $gas_viscosity_flag=True;
                    $c_data += 1;              
                }
                if(is_null($value->fvfg) || $value->fvfg === '')
                {
                    $fvfg_flag=False;
                }
                else
                {
                    $fvfg_flag=True;
                    $c_data += 1;            
                }
                if(is_null($value->water_viscosity) || $value->water_viscosity === '')
                {
                    $water_viscosity_flag=False;
                }
                else
                {
                    $water_viscosity_flag=True;
                    $c_data += 1;             
                }
                if(is_null($value->fvfw) || $value->fvfw === '')
                {
                    $fvfw_flag=False;
                }
                else
                {
                    $fvfw_flag=True;
                    $c_data += 1;
                   
                }
                if(is_null($value->gor) || $value->gor === '')
                {
                    $gor_flag=False;
                }
                else
                {
                    $gor_flag=True;
                    $c_data += 1;        
                }
                if(is_null($value->specific_gas) || $value->specific_gas === '')
                {
                    $specific_gas_gravity_flag=False;
                }
                else
                {
                    $specific_gas_gravity_flag=True;
                    $c_data += 1;                
                }
                if(is_null($value->tipo) || $value->tipo === '' || $value->tipo === ' ')
                {
                    $fluid_flag=False;
                }
                else
                {
                    $fluid_flag=True;
                    $c_data += 1;             
                }
                if(is_null($value->api) || $value->api === '' || $value->api === ' ')
                {
                    $api_gravity_flag=False;
                }
                else
                {
                    $api_gravity_flag=True;
                    $c_data += 1;             
                }
                if(is_null($value->wor) || $value->wor === '' || $value->wor === ' ')
                {
                    $wor_flag=False;
                }
                else
                {
                    $wor_flag=True;
                    $c_data += 1;             
                }
                if(is_null($value->cgr) || $value->cgr === '' || $value->cgr === ' ')
                {
                    $cgr_flag=False;
                }
                else
                {
                    $cgr_flag=True;
                    $c_data += 1;             
                }
                if(is_null($value->lgr) || $value->lgr === '' || $value->lgr === ' ')
                {
                    $lgr_flag=False;
                }
                else
                {
                    $lgr_flag=True;
                    $c_data += 1;             
                }
                if(is_null($value->gwr) || $value->gwr === '' || $value->gwr === ' ')
                {
                    $gwr=False;
                }
                else
                {
                    $gwr=True;
                    $c_data += 1;             
                }
            }


            $exist_production_data = App\production_data::select(DB::raw('count(*) as count'))->where('pozo_id','=', $well->id)->first();

            if($exist_production_data->count==0)
            {
                $production_data_flag = False;
            }
            else
            {
                $production_data_flag = True;
                $c_data += 1;
            }

            $aux=[];

            $location_flag = $latitude_flag and $longitude_flag; 
            if($location_flag){$c_data+=1;}
            array_push($aux,$well->id);
            array_push($aux,$well->nombre);
            array_push($aux,$radius_flag);
            array_push($aux,$drainage_radius_flag);
            array_push($aux,$oil_rate_flag);
            array_push($aux,$gas_rate_flag);
            array_push($aux,$bhp_flag);
            array_push($aux,$tvd_flag);
            array_push($aux,$location_flag);
            array_push($aux,$production_data_flag);
            array_push($aux,$saturation_pressure_flag);
            array_push($aux,$fluid_flag);
            array_push($aux,$oil_viscosity_flag);
            array_push($aux,$fvfo_flag);
            array_push($aux,$gas_viscosity_flag);
            array_push($aux,$fvfg_flag);
            array_push($aux,$water_viscosity_flag);
            array_push($aux,$fvfw_flag);
            array_push($aux,$gor_flag);
            array_push($aux,$specific_gas_gravity_flag);
            array_push($aux,$api_gravity_flag);
            array_push($aux,$wor_flag);
            array_push($aux,$cgr_flag);
            array_push($aux,$lgr_flag);
            array_push($aux,$gwr_flag);
            array_push($aux,$c_data);

            array_push($wells_inventory, $aux);
        }
        $data = array("wells"=>$wells, "wells_inventory"=>$wells_inventory);
        return Response::json($data);
    }));

    Route::get('intervals_inventory_filter', array('as'=>'intervals_inventory_filter', function()
    {
        $formation = Input::get('formation');
        $intervals = App\formacionxpozo::where('formacion_id','=',$formation)->get();
        
        $data = array("intervals"=>$intervals);
        return Response::json($data);
    }));

    Route::get('formationInventory', array('as'=>'wellInventory', function()
    {
        $field = Input::get('field');
        $formations = App\formacion::where('campo_id','=',$field)->get();
        $formations_inventory = [];
        foreach ($formations as $formation)
        { 
            $top_flag=True;
            $netpay_flag=True;
            $porosity_flag=True;
            $permeability_flag=True;
            $reservoir_pressure_flag=True;

            #Contadores
            $c_multiparametric = 0;
            $c_ipr = 0;
            $c_disaggregation = 0;

            if(is_null($formation->top) || $formation->top === '')
            {
                $top_flag=False;
            }
            else
            {
                $top_flag=True;     
                $c_multiparametric += 1;         
            }
            if(is_null($formation->netpay) || $formation->netpay === '')
            {
                $netpay_flag=False;
            }
            else
            {
                $netpay_flag=True;              
                $c_multiparametric += 1;
                $c_ipr += 1;
                $c_disaggregation += 1;    
            }
            if(is_null($formation->porosidad) || $formation->porosidad === '')
            {
                $porosity_flag=False;
            }
            else
            {
                $porosity_flag=True;   
                $c_multiparametric += 1;             
            }
            if(is_null($formation->permeabilidad) || $formation->permeabilidad === '')
            {
                $permeability_flag=False;
            }
            else
            {
                $permeability_flag=True;     
                $c_multiparametric += 1;
                $c_disaggregation += 1;               
            }
            if(is_null($formation->presion_reservorio) || $formation->presion_reservorio === '')
            {
                $reservoir_pressure_flag=False;
            }
            else
            {
                $reservoir_pressure_flag=True;      
                $c_multiparametric += 1;
                $c_ipr += 1;
                $c_disaggregation += 1;              
            }

            $ipr_oil_status = $reservoir_pressure_flag && $netpay_flag;
            $ipr_gas_status = $reservoir_pressure_flag && $netpay_flag;
            $ipr_condensate_gas_status = $reservoir_pressure_flag && $netpay_flag;

            $mp_status = $reservoir_pressure_flag && $netpay_flag && $top_flag && $porosity_flag && $permeability_flag;

            $dissagrigation_status = $reservoir_pressure_flag && $netpay_flag && $permeability_flag;

            $aux=[];
            array_push($aux,$formation->nombre);
            array_push($aux,$ipr_oil_status);
            array_push($aux,$ipr_gas_status);
            array_push($aux,$ipr_condensate_gas_status);
            array_push($aux,$mp_status);
            array_push($aux,$dissagrigation_status);
            array_push($aux,$formation->id);
            array_push($aux,$c_multiparametric);
            array_push($aux,$c_ipr);
            array_push($aux,$c_disaggregation);
            array_push($formations_inventory, $aux);
        }
        $data = array("formations"=>$formations, "formations_inventory"=>$formations_inventory);
        return Response::json($data);
    }));

    Route::get('formationInventory_general', array('as'=>'formationInventory_general', function()
    {
        $field = Input::get('field');
        $formations = App\formacion::where('campo_id','=',$field)->get();

        $formations_inventory = [];

        foreach ($formations as $formation)
        { 
            $top_flag=True;
            $netpay_flag=True;
            $porosity_flag=True;
            $permeability_flag=True;
            $reservoir_pressure_flag=True;
            $water_oil_flag=True;
            $gas_liquid_flag=True;
            $c_data = 0;

            if(is_null($formation->top) || $formation->top === '')
            {
                $top_flag=False;
            }
            else
            {
                $top_flag=True; 
                $c_data+=1;        
            }
            if(is_null($formation->netpay) || $formation->netpay === '')
            {
                $netpay_flag=False;
            }
            else
            {
                $netpay_flag=True;    
                $c_data+=1;           
            }
            if(is_null($formation->porosidad) || $formation->porosidad === '')
            {
                $porosity_flag=False;
            }
            else
            {
                $porosity_flag=True;  
                $c_data+=1;            
            }
            if(is_null($formation->permeabilidad) || $formation->permeabilidad === '')
            {
                $permeability_flag=False;
            }
            else
            {
                $permeability_flag=True;   
                $c_data+=1;               
            }
            if(is_null($formation->presion_reservorio) || $formation->presion_reservorio === '')
            {
                $reservoir_pressure_flag=False;
            }
            else
            {
                $reservoir_pressure_flag=True; 
                $c_data+=1;               
            }

            $water_oil_query = App\permeabilidad_relativa_wo::select(DB::raw('count(*) as count'))->where('formacion_id','=',$formation->id)->first();
            $gas_liquid_query = App\permeabilidad_relativa_gl::select(DB::raw('count(*) as count'))->where('formacion_id','=',$formation->id)->first();

            if($water_oil_query->count==0)
            {
                $water_oil_flag = False;
            }
            else
            {
                $water_oil_flag = True;
                $c_data += 1;
            }

            if($gas_liquid_query->count==0)
            {
                $gas_liquid_flag = False;
            }
            else
            {
                $gas_liquid_flag = True;
                $c_data += 1;
            }
            $aux=[];
            array_push($aux,$formation->id);
            array_push($aux,$formation->nombre);
            array_push($aux,$top_flag);
            array_push($aux,$netpay_flag);
            array_push($aux,$porosity_flag);
            array_push($aux,$permeability_flag);
            array_push($aux,$reservoir_pressure_flag);
            array_push($aux,$water_oil_flag);
            array_push($aux,$gas_liquid_flag);
            array_push($aux,$c_data);
            array_push($formations_inventory, $aux);
        }
        $data = array("formations"=>$formations, "formations_inventory"=>$formations_inventory);
        return Response::json($data);
    }));

    Route::get('wellInventoryData', array('as'=>'wellInventoryData', function()
    {
        $well = Input::get('well');
        $generalData = App\pozo::where('id','=',$well)->get();
        $productionData = App\production_data::where('pozo_id','=',$well)->get();
        $fluidData = [App\fluidoxpozos::where('pozo_id','=',$well)->first()];
        $data = array('generalData'=>$generalData,'fluidData'=>$fluidData,'productionData'=>$productionData);
        return Response::json($data);
    }));

    Route::get('wellScenario', array('as'=>'wellScenario', function()
    {
        $basin = Input::get('basin');
        $fields = App\campo::where('cuenca_id','=',$basin)->get();
        $fieldsAndWellRows =[];
        $wellsWithProducingInterval = App\formacionxpozo::select('pozo_id')->distinct()->get();
        $ids = [];
        foreach ($wellsWithProducingInterval as $well)
        {
            array_push($ids, $well->pozo_id);
        }
        foreach ($fields as $field) 
        {
            $auxRow = [];
            array_push($auxRow,$field->nombre);
            $wells = App\pozo::select('nombre')->wherein('id',$ids)->where('campo_id','=',$field->id)->get();
            array_push($auxRow,$wells);
            array_push($fieldsAndWellRows, $auxRow);
        }
        
        return Response::json($fieldsAndWellRows);
    }));

    #Data inventory detailed

    Route::get('well_inventory_detailed', array('as'=>'wellInventoryData', function()
    {
        $well = Input::get('well');
        $generalData = App\pozo::where('id','=',$well)->get();
        $production_data_query = App\production_data::where('pozo_id','=',$well)->get();
        $fluidData = [App\fluidoxpozos::where('pozo_id','=',$well)->first()];
        $productionData = [];
        foreach ($production_data_query as $value) 
        {
            array_push($productionData, [$value->date,$value->oil_rate,$value->cummulative_oil,$value->gas_rate,$value->cummulative_vas,$value->water_rate,$value->cummulative_water]);
        }
        $data = array('generalData'=>$generalData,'fluidData'=>$fluidData,'productionData'=>$productionData);
        return Response::json($data);
    }));

    #Copy scenario

    Route::get('select_copy_scenario', function()
    {

        $type = Input::get('type');
        $user_id = \Auth::User()->id; 
        $scenarios = App\escenario::join('proyectos as p', 'escenario.proyecto_id','=','p.id')
        ->select('escenario.id','escenario.nombre')
        ->where('p.usuario_id','=',$user_id)
        ->where('escenario.tipo','=',$type)
        ->get();

        return Response::json($scenarios);

    });

    #Test
    Route::resource('test', 'test_controller');


    // Drilling module routes - checked
    Route::post('Drilling/store', 'drilling_controller@store');
    Route::get('Drilling/show/{id}', 'drilling_controller@show');
    Route::get('Drilling/edit/{id}', ['as' => 'drilling.edit', 'uses' => 'drilling_controller@edit']);
    Route::resource('Drilling/update', 'drilling_controller@update');
    Route::resource('Drilling', 'drilling_controller');

    #Módulo de daño Inducido
    Route::resource('DrillingStore', 'drilling_controller@store');

    Route::get('Drilling/result/{id_escenario}',  ['as' => 'drilling.result', 'uses' => 'drilling_controller@result']);
    Route::post('Drilling/update/{id_escenario}', 'drilling_controller@update');

    Route::resource('DrillingUpdate', 'drilling_controller@update');
    Route::resource('AddDrillingTest', 'drilling_controller@drillingTest');
    Route::resource('DynamicFiltration', 'drilling_controller@dynamicFiltration');

    Route::get('Drilling/duplicate/{id}/{dup}', 'drilling_controller@duplicate');

    #Filtration Function
    Route::post('add_filtration_function', 'add_filtration_function_controller@store');
    #Route::resource('ResultsEdit', 'DrillingController@test');

    // Route::get('/ResultsEdit/{id}',[
    //     'as' => 'ResultsEdit',
    //     'uses' => 'DrillingController@edit'
    // ]);

    // Route::get('/drilling_results/{id}',[
    //     'as' => 'drilling_results',
    //     'uses' => 'drilling_controller@result'
    // ]);

    Route::get('Dformation', function()
    {
        $field = Input::get('field');
        $data = App\formacion::where('campo_id','=',$field)->get();
        
        return Response::json($data);
    });

    Route::get('intervalsDrilling', function()
    {
        $formations = Input::get('formations');
        $data = App\formacionxpozo::wherein('formacion_id',$formations)->get();
        return Response::json($data);
    });

    Route::get('intervalsInfoDrilling', function()
    {
        $intervals = Input::get('intervals');
        $data = App\formacionxpozo::wherein('id', $intervals)->get();
        return Response::json($data);
    });

    Route::get('formationsInfoDrill', function()
    {
        $formations = Input::get('formations');
        $data = App\formacion::wherein('id',$formations)->get();
        
        return Response::json($data);
    });

    #Información de funciones de filtrado por formación 
    Route::get('filtration_functions_by_formation', function()
    {
        $formation_name = Input::get('formation_name');
        $formation = App\formacion::where('nombre','=',$formation_name)->first();

        $data = App\d_filtration_function::where('formation_id',$formation->id)->get();

        return Response::json($data);
    });

    #Información de funciones de filtrado por id de formación 
    Route::get('filtration_functions_by_formation_id', function()
    {
        $formation_id = Input::get('formation_id');
        $data = App\d_filtration_function::where('formation_id',$formation_id)->get();

        return Response::json($data);
    });


    #Información de función de filtrado específica
    Route::get('filtration_function_data', function()
    {
        $ff_id = Input::get('ff_id');
        $data = App\d_filtration_function::where('id',$ff_id)->get();

        return Response::json($data);
    });

    #Dynamic Filtration data
    Route::get('dynamicFiltrationData', function()
    {
        $scenario_id = Input::get('scenario_id');
        $formation = Input::get('formation');

        $d_filtration_function = App\d_filtration_function::select('id','name')
        ->where('scenario_id','=',$scenario_id)
        ->where('formation_id','=',$formation)
        ->first();


        $d_filtration_function_data = App\d_laboratory_test::where('d_filtration_function_id','=',$d_filtration_function->id)->get();
        
        $data = array('d_filtration_function' => $d_filtration_function, 'd_filtration_function_data'=>$d_filtration_function_data);

        return Response::json($data);
    });

    #*************/////////////
    #Ipr pvt initial data
    Route::get('initial_pvt_ipr', function()
    {
        $field = Input::get('field');
        $data = App\pvt::where('formacion_id','=',$field)->get();
        
        return Response::json($data);
    });

    Route::get('initial_wo_ipr', function()
    {
        $well = Input::get('well');
        $formation = Input::get('formation');
        $data = App\permeabilidad_relativaxf_wo::where('formacionxpozo_id','=',$formation)->get();
        
        return Response::json($data);
    });

    Route::get('initial_gl_ipr', function()
    {
        $well = Input::get('well');
        $formation = Input::get('formation');
        $data = App\permeabilidad_relativaxf_gl::where('formacionxpozo_id','=',$formation)->get();
        
        return Response::json($data);
    });


    #**/*/*/*/*/
    #Download
    Route::get('download', array('as'=>'download', function()
    {
        if (\Auth::check()) {
            return View::make('download');
        }else{
            return view('loginfirst');
        }
    }));

    // General manuals
    Route::get('manuals', array('as'=>'manuals', function()
    {
        if (\Auth::check()) {
            return View::make('manuals');
        }else{
            return view('loginfirst');
        }
    }));

    // About
    Route::get('about', array('as'=>'about', function()
    {
        if (\Auth::check()) {
            return View::make('about');
        }else{
            return view('loginfirst');
        }
    }));

    #ScenarioReport
    Route::get('scenarioR', array('as'=>'scenarioR', function()
    {
        if (\Auth::check()) {
            return View::make('ScenarioReportHome');
        }else{
            return view('loginfirst');
        }
    }));
    Route::get('scenarioRep', array('as'=>'scenarioRep', function()
    {
        if (\Auth::check()) {
            return View::make('ScenarioReport');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('tipo_esc', function(){

        $id = Input::get('esc_id');
        $tipo = App\escenario::where('id','=',$id)
        ->select('tipo','nombre','descripcion','fecha')
        ->get();

        return Response::json($tipo);

    });
    Route::get('mp_info', function(){

        $id = Input::get('esc_id');
        $mp = App\multiparametrico::where('scenary_id','=',$id)->get();

        return Response::json($mp);

    });

    Route::get('desagregacion_info', function(){

        $id = Input::get('esc_id');
        $desagregacion = App\desagregacion::where('id_escenario','=',$id)->get();

        return Response::json($desagregacion);

    });

    Route::get('ipr_info', function(){

        $id = Input::get('esc_id');
        $ipr = DB::table('ipr')->where('id_escenario', $id)->get();

        return Response::json($ipr);

    });

    Route::get('ipr_tabla_water', function(){

        $id_ipr = Input::get('id_ipr');
        $tabla_water = DB::table('ipr_tabla_water')->where('id_ipr', $id_ipr)->get();

        return Response::json($tabla_water);

    });

    Route::get('statistical_field', function(){

        $field_id = Input::get('field_id');
        $campo = DB::table('campos')->where('id', $field_id)->get();

        return Response::json($campo);

    });

    Route::get('grafica1_ipr', function(){

        $id_ipr = Input::get('id_ipr');
        $grafica = DB::table('ipr_resultados')->where('id_ipr', $id_ipr)->get();

        return Response::json($grafica);

    });

    Route::get('grafica2_ipr', function(){

        $id_ipr = Input::get('id_ipr');
        $grafica = DB::table('ipr_resultados_skin_ideal')->where('id_ipr', $id_ipr)->get();

        return Response::json($grafica);

    });

    Route::get('ipr_tabla_gas', function(){

        $id_ipr = Input::get('id_ipr');
        $tabla_water = DB::table('ipr_tabla_gas')->where('id_ipr', $id_ipr)->get();

        return Response::json($tabla_water);

    });

    Route::get('ipr_tabla_gas_cg', function(){

        $id_ipr = Input::get('id_ipr');
        $tabla_gas = DB::table('ipr_gas_oil_kr_c_g')->where('ipr_id', $id_ipr)->get();

        return Response::json($tabla_gas);

    });

    Route::get('ipr_dropout_cg', function(){

        $id_ipr = Input::get('id_ipr');
        $dropout_data = DB::table('ipr_dropout_c_g')->where('ipr_id', $id_ipr)->get();

        return Response::json($dropout_data);

    });

    Route::get('ipr_tabla', function(){

        $id_ipr = Input::get('id_ipr');
        $ipr_tabla = DB::table('ipr_tabla')->where('id_ipr', $id_ipr)->get();

        return Response::json($ipr_tabla);

    });

    Route::get('ipr_tabla_pvtgas', function(){

        $id_ipr = Input::get('id_ipr');
        $ipr_tabla = DB::table('ipr_pvt_gas')->where('id_ipr', $id_ipr)->get();

        return Response::json($ipr_tabla);

    });

    Route::get('ipr_tabla_pvt_cg', function(){

        $id_ipr = Input::get('id_ipr');
        $ipr_tabla = DB::table('ipr_pvt_c_g')->where('ipr_id', $id_ipr)->get();

        return Response::json($ipr_tabla);

    });

    Route::get('ipr_shrinkage_curve', function(){

        $id_ipr = Input::get('id_ipr');
        $ipr_shrinkage_curve = DB::table('ipr_shrinkage_curve')->where('id_ipr', $id_ipr)->get();

        return Response::json($ipr_shrinkage_curve);

    });
    Route::get('desagregacion_tabla', function(){

        $id_desagregacion = Input::get('id_desagregacion');
        $tabla = DB::table('desagregacion_tabla')->where('id_desagregacion', $id_desagregacion)->get();

        return Response::json($tabla);

    });
    Route::get('skin_ipr', function(){

        $id_ipr = Input::get('id_ipr');
        $tabla = DB::table('desagregacion')->select('skin')->where('id', $id_ipr)->get();

        return Response::json($tabla);

    });
    #ScenarioReport_

    #KKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKKK



    Route::get('highcharts_sessions_user_id', function(){

        $user_id = Input::get('user_id');
        $sessionsbyuser = App\sessions::select(DB::raw('SUM(TIME_TO_SEC(timediff(last_activity, first_activity))) as time, date(first_activity) as date, user_id, users.name, company.name as company'))
        ->groupby('date')
        ->orderBy('date', 'asc')
        ->join('users', 'users.id', '=', 'sessions.user_id')
        ->join('company', 'company.id', '=', 'users.company')
        ->where('sessions.user_id', '=', $user_id)
        ->get();

        return Response::json($sessionsbyuser);

    });

    ##

    Route::resource('requestC', 'requestController');
    Route::resource('requestA', 'list_request_controller');

    Route::resource('homeC', 'home_controller');

    Route::resource('controller', 'ejemploController');
    Route::resource('controllerC', 'ejemploController@a');

    Route::resource('ProjectC', 'add_project_controller');
    Route::resource('DeleteProject', 'list_project_controller');
    Route::resource('ProjectCS', 'add_project_controller@store');

    Route::resource('listScenaryHome', 'listScenaryHome');

    Route::resource('DeleteScenary', 'DeleteScenaryController');
    Route::resource('ScenaryC', 'add_scenario_controller');
    Route::resource('ScenaryCS', 'add_scenario_controller@store');
    
    Route::get('validateTreeScenary', 'add_scenario_controller@validateScenary');

    Route::resource('AddDataC', 'add_basin_field_controller');
    Route::resource('AddFormationWC', 'add_well_controller');
    Route::resource('AddFormationWellC', 'add_producing_interval_controller');


    Route::resource('AddProducingIntervalC', 'add_producing_interval_well_controller');
    Route::resource('AddProductionTestC', 'add_production_test_well_controller');

    Route::resource('AddProductionTestCE', 'edit_production_test_well_controller');

    Route::resource('AddProductionTestCS', 'add_production_test_well_controller@store');
    Route::resource('AddFormationWCS', 'add_well_controller@store');
    Route::resource('AddFormationWellCS', 'add_producing_interval_controller@store');
    Route::resource('AddProducingIntervalCS', 'add_producing_interval_well_controller@store');

    Route::resource('AddFormationC', 'add_formation_controller');
    Route::resource('AddDataCS', 'AddDataController@store');

    Route::resource('AddMeasurementC', 'add_damage_variables_controller');
    Route::resource('AddMeasurementCS', 'add_damage_variables_controller@store');
    Route::resource('AddMeasurementCSxlsx', 'add_damage_variables_controller@storeSpreadsheet');

    Route::resource('EditMeasurementC', 'edit_damage_variables_controller');
    Route::resource('subparametersbywellandformation', 'edit_damage_variables_controller@getSubparametersByWellAndFormation');
    Route::resource('EditSubparameterC', 'edit_damage_variables_controller@editSubparameter');
    Route::resource('RemoveSubparameterC', 'edit_damage_variables_controller@removeSubparameter');

    Route::resource('AddDataBasin', 'AddDataController');

    Route::resource('AddFormationS', 'add_formation_controller@store');
    Route::resource('AddDataSectorS', 'add_basin_field_controller@storeSector');
    Route::resource('AddDataFieldS', 'add_basin_field_controller@storeField');
    Route::resource('AddDataBasinS', 'add_basin_field_controller@storeBasin');

    Route::resource('AddMPAnalysis', 'measurementScenaryController');
    Route::resource('AddMPAnalysisA', 'measurementScenaryAController');


    Route::resource('requestDamageVariables', 'requestController@storeDamageVariables');
    Route::resource('requestWell', 'requestController@storeWell');
    Route::resource('requestInterval', 'requestController@storeIterval');


    Route::resource('GeneralInformationC', 'add_multiparametric_controller');
    Route::resource('GeneralInformationCS', 'add_multiparametric_controller@store');

    #Geomecánica
    Route::resource('Geomechanics', 'geomechanical_diagnosis_controller');
    Route::resource('geomechanical_diagnosis_edit', 'geomechanical_diagnosis_controller@edit');
    Route::resource('geomechanical_diagnosis', 'geomechanical_diagnosis_controller');
    Route::resource('geomechanical_diagnosis_update', 'geomechanical_diagnosis_controller@update');
    Route::get('/geomechanical_diagnosis/{scenario_id}/results_from_tree', ['as' => 'geomechanical_diagnosis.results_from_tree', 'uses' => 'geomechanical_diagnosis_controller@results_from_tree']);

    Route::get('geomechanical_diagnosis/duplicate/{id}/{dup}', 'geomechanical_diagnosis_controller@duplicate');

    #Asfaltenos diagnóstico
    Route::get('asphalteneStabilityAnalysis/result/{id_escenario}', ['as' => 'asa.result', 'uses' => 'add_asphaltene_stability_analysis_controller@result']);
    Route::resource('asphalteneStabilityAnalysis', 'add_asphaltene_stability_analysis_controller');
    Route::get('asphalteneStabilityAnalysis/duplicate/{id}/{dup}', 'add_asphaltene_stability_analysis_controller@duplicate');

    Route::get('asphaltenesPrecipitated/result/{id_escenario}', ['as' => 'asp.result', 'uses' => 'add_precipitated_asphaltenes_analysis_controller@result']);
    Route::resource('asphaltenesPrecipitatedIndex', 'add_precipitated_asphaltenes_analysis_controller@index');
    Route::resource('asphaltenesPrecipitated', 'add_precipitated_asphaltenes_analysis_controller');
    Route::get('asphaltenesPrecipitated/duplicate/{id}/{dup}', 'add_precipitated_asphaltenes_analysis_controller@duplicate');

    Route::get('asphaltenesDiagnosis/result/{id_escenario}', ['as' => 'asd.result', 'uses' => 'add_asphaltenes_diagnosis_controller@result']);
    Route::resource('asphaltenesDiagnosis', 'add_asphaltenes_diagnosis_controller');
    Route::get('asphaltenesDiagnosis/duplicate/{id}/{dup}', 'add_asphaltenes_diagnosis_controller@duplicate');

    #Migración finos diagnóstico
    Route::resource('finesMigrationDiagnosis', 'add_fines_migration_diagnosis_controller');
    
    Route::get('finesMigrationDiagnosis/duplicate/{id}/{dup}', 'add_fines_migration_diagnosis_controller@duplicate');

    Route::resource('finesMigrationDiagnosisStore', 'add_fines_migration_diagnosis_controller@store');
    Route::get('/finesMigrationDiagnosis/{scenario_id}/show_results', ['as' => 'finesMigrationDiagnosis.show_results', 'uses' => 'add_fines_migration_diagnosis_controller@show_results']);

    Route::get('/finesMigrationDiagnosis/{id}/edit', ['as' => 'finesMigrationDiagnosis.edit', 'uses' => 'add_fines_migration_diagnosis_controller@edit']);

    Route::get('/scenarioR/{scenario_id}/show_asphaltene_report', ['as' => 'scenario_report.show_asphaltene_report', 'uses' => 'scenario_report_controller@show_asphaltene_report']);
    Route::get('/scenarioR/{scenario_id}/show_fines_report', ['as' => 'scenario_report.show_fines_report', 'uses' => 'scenario_report_controller@show_fines_report']);

    Route::resource('scenario_report', 'scenario_report_controller');

    #IPR
    Route::post('IPR/store', 'IPR2Controller@store');
    Route::get('IPR/sensibility', 'IPR2Controller@sensibility');
    Route::post('IPR/sensibility', 'IPR2Controller@sensibility');
    
    /* Para duplicar */
    Route::get('IPR/duplicate/{id}/{dup}', 'IPR2Controller@duplicate');

    Route::get('IPR/sensibility_advanced', 'IPR2Controller@sensibility_advanced');
    Route::post('IPR/sensibility_advanced', 'IPR2Controller@sensibility_advanced');


    Route::post('IPR/operativePointGet', 'IPR2Controller@operativePointGet');
    Route::get('IPR/get_file', 'IPR2Controller@get_file');

    Route::get('IPR2/method_calcula_mod_permeabilidad', 'IPR2Controller@method_calcula_mod_permeabilidad');

    // Route::post('IPR/store', 'IPR2Controller@store');
    // Route::post('IPR2/store', 'IPR2Controller@store');
    Route::post('IPR/update/{id}', 'IPR2Controller@update');
    // Route::post('IPR2/update/{id}', 'IPR2Controller@update');

    Route::get('IPR/{id_escenario}', 'IPR2Controller@index');
    Route::post('IPR/store/{id_escenario}', 'IPR2Controller@store');
    
    Route::get('IPR/edit/{id_escenario}', ['as' => 'ipr.edit', 'uses' => 'IPR2Controller@back']);
    Route::post('IPR/update/{id_escenario}', 'IPR2Controller@update');

    Route::resource('IPR', 'IPR2Controller');
    // Route::resource('IPR2', 'IPR2Controller');
    // Route::resource('IPREdit2', 'IPR2Controller@back');

    Route::get('IPR/{id_escenario}/edit', array('as'=>'ipr.edit2', function($id_escenario) {
        return Redirect::to(URL::route('ipr.edit',$id_escenario));
    }));

    Route::resource('IPR', 'IPR2Controller');

    #Drilling
    Route::get('Drilling/edit/{id_escenario}', 'drilling_controller@edit');
    // Route::resource('drilling_edit', 'drilling_controller@edit');

    Route::get('/IPR/result/{id}',[
        'as' => 'IPR.result',
        'uses' => 'IPR2Controller@result'
    ]);

    Route::get('/IPR/result/{id}/sensibilities',[
        'as' => 'IPR.result_sensibilities',
        'uses' => 'IPR2Controller@sensibilitiesAdvanced'
    ]);

    // Route::get('/IPR2/result/{id}',[
    //     'as' => 'IPR.result',
    //     'uses' => 'IPR2Controller@result'
    // ]);
    // 
    
    /* Asphaltene remediation controller */
    Route::get('asphalteneremediation/{scenaryId}', 'asphaltene_remediationController@index');
    Route::post('asphalteneremediation/store', 'asphaltene_remediationController@store');
    Route::get('asphalteneremediation/edit/{id}', 'asphaltene_remediationController@edit');
    Route::post('asphalteneremediation/update/{id}', 'asphaltene_remediationController@update');
    Route::get('asphalteneremediation/results/{id}', 'asphaltene_remediationController@show');
    
    Route::get('asphalteneremediation/duplicate/{id}/{dup}', 'asphaltene_remediationController@duplicate');

    /* Fines remediation controller */
    Route::get('finesremediation/{scenaryId}', 'fines_remediationController@index');
    Route::post('finesremediation/store', 'fines_remediationController@store');
    Route::get('finesremediation/edit/{id}', 'fines_remediationController@edit');
    Route::post('finesremediation/update/{id}', 'fines_remediationController@update');
    Route::get('finesremediation/results/{id}', 'fines_remediationController@show');

    Route::get('finesremediation/duplicate/{id}/{dup}', 'fines_remediationController@duplicate');

    // Rutas de desagregación que ya están funcionando
    Route::post('Desagregacion/store', 'DesagregacionController@store');
    Route::get('Desagregacion/show/{id}', 'DesagregacionController@show');
    Route::get('Desagregacion/edit/{id}', 'DesagregacionController@edit');
    Route::get('Desagregacion/duplicate/{id}/{dup}', 'DesagregacionController@duplicate');
    Route::resource('Desagregacion/update', 'DesagregacionController@update');
    Route::resource('Desagregacion', 'DesagregacionController');


    Route::get('/DamageAnalysis/{scenario_id}/show_results', ['as' => 'desagregacion.show_results', 'uses' => 'DesagregacionController@show_results']);

    Route::get('resultado_desagregacion', function(){

        $id_desagregacion = Input::get('id_desagregacion');
        $resultado_desagregacion = App\resultado_desagregacion::where('id_desagregacion','=',$id_desagregacion)->get();

        return Response::json($resultado_desagregacion);

    });
    Route::get('radios_desagregacion', function(){

        $id_desagregacion = Input::get('id_desagregacion');
        $radios_desagregacion = App\radios_resultado_desagregacion::where('id_desagregacion','=',$id_desagregacion)->get();

        return Response::json($radios_desagregacion);
    });
    Route::get('permeabilidades_desagregacion', function(){

        $id_desagregacion = Input::get('id_desagregacion');
        $permeabilidades_desagregacion = App\permeabilidades_resultado_desagregacion::where('id_desagregacion','=',$id_desagregacion)->get();

        return Response::json($permeabilidades_desagregacion);

    });
    Route::get('spider_desagregacion', function(){

        $id_desagregacion = Input::get('id_desagregacion');
        $skins = App\resultado_desagregacion::where('id_desagregacion','=',$id_desagregacion)->get();

        return Response::json($skins);

    });

    Route::resource('registerC', 'add_user_controller');
    Route::resource('registerCS', 'add_user_controller@store');
    
    Route::get('changepassword', 'add_user_controller@changepassword');
    Route::post('changepassword/update', 'add_user_controller@update_password');

    Route::resource('HistC', 'histController');

    Route::resource('UserStatistics', 'user_statistics_controller');

    Route::resource('UserC', 'list_user_controller');
    Route::get('getUsersTable', 'list_user_controller@getUsersTable');
    Route::resource('listBasinC', 'list_basin_controller');
    Route::resource('listFieldC', 'list_field_controller');
    Route::resource('listFormationC', 'list_formation_controller');
    //Route::resource('formationEdit', 'list_formation_controller@edit');
    Route::resource('listWellC', 'list_well_controller');
    Route::resource('listIntervalC', 'list_producing_interval_controller');


    Route::resource('listIntervalWellCE', 'listIntervalWellControllerE');

    Route::resource('listIntervalWellC', 'listIntervalWellController');
    Route::resource('listIntervalWellCD', 'listIntervalWellController@destroy');
    Route::resource('listIntervalCD', 'list_producing_interval_controller@destroy');
    Route::resource('listProductionTestIC', 'add_production_test_controller');
    Route::resource('listProductionTestICE', 'edit_production_test_controller');


    Route::resource('logout', 'logoutController');
    Route::resource('MS', 'AddMSAController');


    Route::resource('MSC', 'edit_subparameter_controller');


    Route::resource('MSCA', 'edit_subparameter_controller');

    Route::resource('MPAnalysis', 'add_subparameter_controller');
    Route::resource('OSC', 'OSController');
    Route::resource('RPC', 'RPController');
    Route::resource('IDC', 'IDController');
    Route::resource('GDC', 'GDController');

    Route::resource('revisions', 'revisions_controller');

    Route::get('UserInformation', array('as'=>'UserInformation', function()
    {
        if (\Auth::check()) {
            return View::make('show_auth_user');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('database', array('as'=>'database', function()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                return View::make('database');
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('projectmanagement', array('as'=>'projectmanagement', function()
    {
        if (\Auth::check()) {
            return View::make('projectmanagement');
        }else{
            return view('loginfirst');
        }
    }));


    Route::get('ejm', array('as'=>'ejm', function()
    {
        return View::make('ejm');
    }));

    Route::get('error', array('as'=>'error', function()
    {
        return View::make('error');
    }));

    Route::get('home', array('as'=>'home', function()
    {
        if (\Auth::check()) {
            return View::make('home');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('register', array('as'=>'register', function()
    {
        if (\Auth::check()) {
            return View::make('add_user');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('multiparametrico', array('as'=>'multiparametrico', function()
    {
        if (\Auth::check()) {
            return View::make('Mult');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('error', array('as'=>'error', function()
    {
        return View::make('error');
    }));



    Route::get('Excel', function(){


        $datos = App\coordenada_campos::all();

        $data = array('Datos'=>$datos);
        return Response::json($data);

    });



    Route::get('formacion', function(){

        $formacion_id = Input::get('formacion');
        $formacion = App\formacion::find($formacion_id);
    
        return Response::json($formacion);
    
    });    

    Route::get('well', function(){

        $well = Input::get('well');
        $pozo = App\pozo::where('id','=',$well)->get();

        return Response::json($pozo);

    });

    Route::get('formacionW', function(){

        $pozo = Input::get('pozo');
        $formacion = App\formacionxpozo::where('pozo_id','=',$pozo)->select('id', 'nombre')->orderBy('nombre')->get();

        return Response::json($formacion);

    });

    Route::get('fieldstatistical', function(){

        $multi = Input::get('multi');
        
        $field_statistical = App\multiparametrico::where('id','=',$multi)->select('field_statistical')->get();

        return Response::json($field_statistical);

    });

    Route::get('sectores', function(){

        $campos = Input::get('campos');
        
        $sectores = App\sector::wherein('campo_id',$campos)->get();

        return Response::json($sectores);

    });

    Route::get('formaciones', function(){

        $formacion = Input::get('formaciones');
        
        $formaciones = App\formacion::wherein('nombre',$formacion)->get();

        return Response::json($formaciones);

    });

    Route::get('formations_by_field', function(){

        $field = Input::get('field');
        
        $formations = App\formacion::where('campo_id',$field)->get();

        return Response::json($formations);

    });


    Route::get('home', array('as'=>'home',function()
    {
        if (\Auth::check()) {
            return View::make('home');
        }else{
            return view('loginfirst');
        }

    }));

    Route::get('Excel1', array('as'=>'Excel1', function()
    {
        return view('Excel1');
    }));


    Route::get('AddData', array('as'=>'AddData', function()
    {
        if (\Auth::check()) {
            return  view('add_basin_field');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('AddFormation', array('as'=>'AddFormation', function()
    {
        if (\Auth::check()) {
            return view('add_formation');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('AddWell', array('as'=>'AddWell', function()
    {
        if (\Auth::check()) {
            return view('AddWell');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('add_well', array('as'=>'add_well', function() 
    {
        if (\Auth::check()) {
            return view('add_well');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('Fluid', array('as'=>'Fluid', function()
    {
        if (\Auth::check()) {
            return view('FluidCharacterization');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('Project', array('as'=>'Project', function()
    {
        if (\Auth::check()) {
            return view('add_project');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('ProjectDD', array('as'=>'ProjectDD', function()
    {
        if (\Auth::check()) {
            return view('ProjectGeneral1');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('Scenary', array('as'=>'Scenary', function()
    {
        if (\Auth::check()) {
            return view('add_scenario');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('ScenaryGI', array('as'=>'ScenaryGI', function()
    {
        if (\Auth::check()) {
            return view('add_multiparametric');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('Multiparametric', array('as'=>'Multiparametric', function()
    {
        if (\Auth::check()) {
            return view('MultiparametricAnalysis');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('FB', array('as'=>'FB', function()
    {
        return view('Parameters-FB');
    }));

    Route::get('GD', array('as'=>'GD', function()
    {
        return view('Parameters-GD');
    }));

    Route::get('ID', array('as'=>'ID', function()
    {
        return view('Parameters-ID');
    }));

    Route::get('MS', array('as'=>'MS', function()
    {
        return view('edit_subparameter');
    }));

    Route::get('OS', array('as'=>'OS', function()
    {
        return view('Parameters-OS');
    }));

    Route::get('RP', array('as'=>'RP', function()
    {
        return view('Parameters-RP');
    }));

    Route::get('AddBasin', array('as'=>'AddBasin', function()
    {
        $url = URL::route('AddData') . '#Basin';
        return Redirect::to($url);
    }));

    Route::get('AddField', array('as'=>'AddField', function()
    {
        $url = URL::route('AddData') . '#Field';
        return Redirect::to($url);
    }));

    Route::get('AddSector', array('as'=>'AddSector', function()
    {
        $url = URL::route('AddData') . '#Sector';
        return Redirect::to($url);
    }));

    Route::get('AddPad', array('as'=>'AddPad', function()
    {
        $url = URL::route('AddData') . '#Pad';
        return Redirect::to($url);
    }));

    Route::get('GeneralInformation', array('as'=>'GeneralInformation', function()
    {
        if (\Auth::check()) {
            $cuencas = App\cuenca::all();
            
            return  view('add_multiparametric', ['cuencas' => $cuencas]);
        }else{
            return view('loginfirst');
        }
    }));


    Route::get('pvt', function()
    {
        $campo = Input::get('campo');
        $maxp = App\pvt::select(DB::raw('max(pressure) as presion'))
        ->where('campo_id','=',$campo)
        ->get();
        $minp = App\pvt::select(DB::raw('min(pressure)'))
        ->where('campo_id','=',$campo)
        ->get();

        $pvtmax = App\pvt::wherein('pressure',$maxp)
        ->where('campo_id','=',$campo)
        ->get();
        $pvtmin = App\pvt::wherein('pressure',$minp)
        ->where('campo_id','=',$campo)
        ->get();

        $data = array('Max'=>$pvtmax,'Min'=>$pvtmin);
        return Response::json($data);
    });


    Route::get('Petrophysics', array('as'=>'Petrophysics', function()
    {
        $url = URL::route('ScenaryGI') . '#Petrophysics';
        return Redirect::to($url);
    }));


    Route::get('FluidInformation', array('as'=>'FluidInformation', function()
    {
        $url = URL::route('ScenaryGI') . '#FluidInformation';
        return Redirect::to($url);
    }));


    Route::get('ProductionData', array('as'=>'ProductionData', function()
    {
        $url = URL::route('ScenaryGI') . '#ProductionData';
        return Redirect::to($url);
    }));



    Route::get('arbol', function()
    {
        $escenarioscompleto = []; #Aquí van los conjuntos de escenarios

        $proyectosxcompañia = [[],[],[],[],[]];

        $proyectos = [];
       
        $usuario = Input::get('usuario');  #Recibe el id del usuario para hacer las consultas
        $compañias = [[],[],[],[],[]];
        



        if(\Auth::User()->office == 0){

            $proyectosxcompañia[0] = App\proyecto::where('compania','=', 0)->orderBy('nombre')->get();
            $proyectosxcompañia[1] = App\proyecto::where('compania','=', 1)->orderBy('nombre')->get();
            $proyectosxcompañia[2] = App\proyecto::where('compania','=', 2)->orderBy('nombre')->get();
            $proyectosxcompañia[3] = App\proyecto::where('compania','=', 3)->orderBy('nombre')->get();
            $proyectosxcompañia[4] = App\proyecto::where('compania','=', 4)->orderBy('nombre')->get();
            $compañias = array("UN", "Equion", "Ecopetrol", "Hocol", "UIS");

        } else if(\Auth::User()->office == 1){

            $proyectosxcompañia[\Auth::User()->company] = App\proyecto::where('compania','=', \Auth::User()->company)->orderBy('nombre')->get();
            if(\Auth::User()->company == 0){
                $compañias[0] = "UN";
            }
            if(\Auth::User()->company == 1){
                $compañias[1] = "Equion";
            }
            if(\Auth::User()->company == 2){
                $compañias[2] = "Ecopetrol";
            }
            if(\Auth::User()->company == 3){
                $compañias[3] = "Hocol";
            }
            if(\Auth::User()->company == 4){
                $compañias[4] = "UIS";
            }
        } else if(\Auth::User()->office == 2){

            $proyectosxcompañia[\Auth::User()->company] = App\proyecto::where('proyectos.usuario_id','=',$usuario)->orderBy('nombre')->get();
            if(\Auth::User()->company == 0){
                $compañias[0] = "UN";
            }
            if(\Auth::User()->company == 1){
                $compañias[1] = "Equion";
            }
            if(\Auth::User()->company == 2){
                $compañias[2] = "Ecopetrol";
            }
            if(\Auth::User()->company == 3){
                $compañias[3] = "Hocol";
            }
            if(\Auth::User()->company == 4){
                $compañias[3] = "UIS";
            }
        }

        for ($i = 0; $i <= 3; $i++) {
            foreach ($proyectosxcompañia[$i] as $value) {
                $escenarios = App\escenario::where('escenarios.proyecto_id','=',$value->id)
                ->where('escenarios.estado','=',1) /* Escenarios dependiendo del id del proyecto */
                ->orderBy('nombre')
                ->get();

                foreach ($escenarios as $ke => $ve) {

                    $respuesta = 1;
                    $id = $ve->id;

                    /* Controlar el tipo de escenario */
                    if($ve->tipo == 'IPR') {

                        $resp = App\ipr::where('id_Escenario',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    } else if($ve->tipo == 'Multiparametric') {
                        
                        if (is_null($ve->multiparametricType)) {
                            $respuesta = 1;
                            continue;
                        } else {
                            
                            if ($ve->multiparametricType == 'analitical') {

                                $resp = App\Models\MultiparametricAnalysis\Analitical::where('escenario_id',$id);

                            } else {

                                $resp = App\Models\MultiparametricAnalysis\Statistical::where('escenario_id',$id);

                            }

                            $respuesta = $resp->count() > 0 ? 0 : 1;
                        }

                    } else if($ve->tipo == 'Fines Treatment Selection') {

                        $resp = App\FinesTreatmentSelection::where('escenario_id',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    } else if($ve->tipo == 'Drilling') {

                        $resp = App\drilling::where('scenario_id',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    } else if($ve->tipo == 'Geomechanics') {

                        /* geomechanical_diagnosis_controller@index */
                        $resp = App\geomechanical_diagnosis::where('scenario_id',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    } else if($ve->tipo == 'Asphaltene precipitation') {

                        $scenarios_extras = [];

                        $resp_asphaltenes_d_stability_analysis = App\asphaltenes_d_stability_analysis::where('scenario_id',$id)->first();
                        if ($resp_asphaltenes_d_stability_analysis) {
                            $resp_asphaltenes_d_stability_analysis->nombre = '[A_S]';
                            $resp_asphaltenes_d_stability_analysis->route = route("asa.result",$id);
                            $scenarios_extras[] = $resp_asphaltenes_d_stability_analysis;
                        }
                        $respuesta_as = ($resp_asphaltenes_d_stability_analysis) ? 0 : 1;


                        $resp_asphaltenes_d_precipitated_analysis = App\asphaltenes_d_precipitated_analysis::where('scenario_id',$id)->first();
                        if ($resp_asphaltenes_d_precipitated_analysis) {
                            $resp_asphaltenes_d_precipitated_analysis->nombre = '[A_P]';
                            $resp_asphaltenes_d_precipitated_analysis->route = route("asp.result",$id);
                            $scenarios_extras[] = $resp_asphaltenes_d_precipitated_analysis;
                        }
                        $respuesta_ap = ($resp_asphaltenes_d_precipitated_analysis) ? 0 : 1;


                        $resp_asphaltenes_d_diagnosis = App\asphaltenes_d_diagnosis::where('scenario_id',$id)->first();
                        if ($resp_asphaltenes_d_diagnosis) {
                            $resp_asphaltenes_d_diagnosis->nombre = '[A_D]';
                            $resp_asphaltenes_d_diagnosis->route = route("asd.result",$id);
                            $scenarios_extras[] = $resp_asphaltenes_d_diagnosis;
                        }
                        $respuesta_ad = ($resp_asphaltenes_d_diagnosis) ? 0 : 1;

                        $escenarios[$ke]->extra_ = $scenarios_extras;

                        if ($respuesta_as == 0 && $respuesta_ad == 0 && $respuesta_ap == 0) {
                            $respuesta = 0;
                        }

                    } else if($ve->tipo == "Swelling and fines migration") {

                        $resp = App\fines_d_diagnosis::where('scenario_id',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    } else if($ve->tipo == "Asphaltene remediation") {

                        $resp = App\asphaltene_remediations::where('id_scenary',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    }  else if($ve->tipo == "Fines remediation") {

                        $resp = App\fines_remediation::where('id_scenary',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    } else if($ve->tipo == "Dissagregation") {

                        /* DesagregacionController@index */
                        $resp = App\desagregacion::where('id_escenario',$id);
                        $respuesta = $resp->count() > 0 ? 0 : 1;

                    }

                    if ($respuesta == 0) {
                        $escenarios[$ke]->res_ = $respuesta;
                    }

                }

                array_push($escenarioscompleto,$escenarios); 
            }
            
        }

        $data = array('Escenarios'=> $escenarioscompleto, 'ProyectosxCompanias' => $proyectosxcompañia, 'Compañias' => $compañias); #Armo el json para enviarlo devuelta
        return Response::json($data); #Lo envío 
    });

    Route::get('getScenarioInfo', function()
    {
        $scenario_id = Input::get('scenario');
        $scenario = App\escenario::find($scenario_id);

        return Response::json($scenario);
    });

    Route::get('login4', function()
    {
        $username = Input::get('username');
        $password = Input::get('password');
        $perm = App\User::select(DB::raw('*'))
        ->where('name','=',$username)
        ->where('password','=',$password)
        ->get();

        return Response::json($perm);
    });

    Route::get('excel2', function()
    {
        $formacion = Input::get('formacion');
        $perm = App\permeabilidad_relativa_wo::select(DB::raw('*'))
        ->where('formacion_id','=',$formacion)
        ->get();

        return Response::json($perm);
    });



    Route::get('excel10', function()
    {
        $pozo = Input::get('pozo');
        $prodD = App\production_data::select(DB::raw('*'))
        ->where('pozo_id','=',$pozo)
        ->get();

        return Response::json($prodD);
    });

    Route::get('excel22', function()
    {
        $formacion = Input::get('formacion');
        $perm = App\permeabilidad_relativa_gl::select(DB::raw('*'))
        ->where('formacion_id','=',$formacion)
        ->get();

        return Response::json($perm);
    });



    Route::get('excel3', function()
    {
        $formacion = Input::get('formacion');
        $perm = App\permeabilidad_relativaxf_wo::select(DB::raw('*'))
        ->where('formacionxpozo_id','=',$formacion)
        ->get();

        return Response::json($perm);
    });

    Route::get('excel_reservoir_interval', function()
    {
        $formacion = Input::get('formacion');
        $interval = App\presion_yacimiento::select(DB::raw('*'))
        ->where('id_intervalo','=',$formacion)
        ->get();

        return Response::json($interval);
    });

    Route::get('excel_int3', function()
    {
        $peticion = Input::get('peticion');
        $perm = App\permeabilidad_relativaxf_wo_peticion::select(DB::raw('*'))
        ->where('peticion_id','=',$peticion)
        ->get();

        return Response::json($perm);
    });

    Route::get('excel_int33', function()
    {
        $peticion = Input::get('peticion');
        $perm = App\permeabilidad_relativaxf_gl_peticion::select(DB::raw('*'))
        ->where('peticion_id','=',$peticion)
        ->get();

        return Response::json($perm);
    });

    Route::get('request', function()
    {
        $req = DB::table('peticiones')->get();

        return Response::json($req);
    });

    Route::get('excel33', function()
    {
        $formacion = Input::get('formacion');
        $perm = App\permeabilidad_relativaxf_gl::select(DB::raw('*'))
        ->where('formacionxpozo_id','=',$formacion)
        ->get();

        return Response::json($perm);
    });


    Route::get('userbycompany', function()
    {
        $company = Input::get('company');
        if($company == ""){
            $user = App\User::all();
        }else{
            $user = App\User::select(DB::raw('*'))
            ->where('company','=',$company)
            ->get();  
        }

        return Response::json($user);
    });



    Route::get('fieldbybasin', function()
    {
        $basin = Input::get('basin');
        $field = App\campo::select(DB::raw('*'))
        ->where('cuenca_id','=',$basin)
        ->orderBy('nombre', 'asc')
        ->get();

        return Response::json($field);
    });

    Route::get('fieldbasin', function()
    {
        $field = Input::get('field');
        $field = App\campo::select(DB::raw('*'))
        ->where('id','=',$field)
        ->get();

        return Response::json($field);
    });


    Route::get('fieldbybasinselect', function()
    {
        $basin = Input::get('basin');
        $field = App\campo::select(DB::raw('*'))
        ->where('cuenca_id','=',$basin)
        ->orderBy('nombre')
        ->get();

        return Response::json($field);
    });


    Route::get('formationbyfield', function()
    {
        $field = Input::get('field');
        $formation = App\formacion::select(DB::raw('*'))
        ->where('campo_id','=',$field)
        ->get();

        return Response::json($formation);
    });

    Route::get('formationfield', function()
    {
        $formation = Input::get('formation');
        $formationr = App\formacion::select(DB::raw('*'))
        ->where('id','=',$formation)
        ->get();

        return Response::json($formationr);
    });


    Route::get('wellbyfield', function()
    {
        $field = Input::get('field');
        $pozo = App\pozo::select(DB::raw('*'))
        ->where('campo_id','=',$field)
        ->select('id', 'nombre')
        ->get();

        return Response::json($pozo);
    });

    Route::get('formacionbyfield', function()
    {
        $field = Input::get('field');
        $formaciones = App\formacion::select(DB::raw('*'))
        ->where('campo_id','=',$field)
        ->select('id', 'nombre')
        ->get();

        return Response::json($formaciones);
    });

    Route::get('wellfield', function()
    {
        $well = Input::get('well');
        $pozo = App\pozo::select(DB::raw('*'))
        ->where('id','=',$well)
        ->select('id', 'nombre')
        ->get();

        return Response::json($pozo);
    });

    Route::get('projects', function()
    {
        $user = Input::get('user');
        $company = Input::get('company');
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        if($user=="")
        {
            if(\Auth::User()->office == 2){
                $user = \Auth::User()->id;
                $user_aux = '=';
            }else{
                $user = 0;
                $user_aux = '>=';
            }
        }else{
            $user_aux = "=";
        }

        if($company=="")
        {
            if(\Auth::User()->office == 0){
                $company = 0;
                $company_aux = '>=';
            } else if(\Auth::User()->office == 2){
                $company = \Auth::User()->company;
                $company_aux = '=';
            } else {
                $company = \Auth::User()->company;
                $company_aux = '=';
            }
        }
        else
        {
            $company_aux = "=";
        }

        if($start_date=="")
        {
            $start_date = "1000-01-01";
        }

        if($end_date=="")
        {
            $end_date = "3000-01-01";
        }

        $projects = App\proyecto::where('usuario_id',$user_aux,$user)
        ->where('compania',$company_aux,$company)
        ->whereBetween('fecha', [$start_date, $end_date])
        ->get();

        return Response::json($projects);
    });

    Route::get('byWellFormation', function()
    {
        $well = Input::get('well');
        $formation = Input::get('formation');
        $prodInterval = App\formacionxpozo::select(DB::raw('*'))
        ->where('pozo_id','=',$well)
        ->where('FProductora_id','=',$formation)
        ->get();

        return Response::json($prodInterval);
    });

    Route::get('intervalbyformation', function()
    {
        $formacion = Input::get('formacion');
        $prodInterval = App\formacionxpozo::select(DB::raw('*'))
        ->where('formacion_id','=',$formacion)
        ->get();

        return Response::json($prodInterval);
    });



    Route::get('intervalformation', function()
    {
        $formation = Input::get('formation');
        $well = Input::get('well');
        $prodInterval = App\formacionxpozo::select(DB::raw('*'))
        ->where('formacion_id','=',$formation)
        ->where('pozo_id','=',$well)
        ->get();

        return Response::json($prodInterval);
    });

    Route::get('intervalformation2', function()
    {
        $interval = Input::get('interval');
        $prodInterval = App\formacionxpozo::select(DB::raw('*'))
        ->where('id','=',$interval)
        ->get();

        return Response::json($prodInterval);
    });


    Route::get('intervalbyformationwell', function()
    {
        $formacion = Input::get('formacion');
        $pozo = Input::get('well');
        $prodInterval = App\formacionxpozo::select(DB::raw('*'))
        ->where('formacion_id','=',$formacion)
        ->where('pozo_id','=',$pozo)
        ->get();

        return Response::json($prodInterval);
    });



    Route::get('excel4', function()
    {
        $cuenca = Input::get('cuenca');
        $perm = App\coordenada_cuenca::select(DB::raw('*'))
        ->where('cuenca_id','=',$cuenca)
        ->select('lat', 'lon')
        ->get();

        return Response::json($perm);
    });

    Route::get('excel5', function()
    {
        $campo = Input::get('campo');
        $formacion = Input::get('formacion');
        $perm = App\coordenada_formacion::select(DB::raw('*'))
        ->where('campo_id','=',$campo)->where('formacion_id','=',$formacion)
        ->get();

        return Response::json($perm);
    });


    Route::get('excel77', function()
    {

        $id = Input::get('multiparametrico');
        $perm = App\production_data::select(DB::raw('*'))
        ->where('cummulative_oil','=',$id)
        ->get();

        return Response::json($perm);
    });

    Route::get('excel79', function()
    {
        $id = Input::get('formation');
        $perm = App\formacionxpozo::select(DB::raw('*'))
        ->where('id','=',$id)
        ->select('id', 'nombre')
        ->get();

        return Response::json($perm);
    });


    Route::get('excel78', function()
    {
        $id = Input::get('formation');
        $perm = App\formacionxpozo::select(DB::raw('*'))
        ->get();

        return Response::json($perm);
    });

    Route::get('excel80', function()
    {
        $pozo_id = Input::get('pozo');
        $perm = App\formacionxpozo::select(DB::raw('*'))
        ->where('pozo_id','=',$pozo_id)
        ->select('id', 'nombre')
        ->get();

        return Response::json($perm);
    });

    Route::get('excel8080', function()
    {
        $pozo_id = Input::get('pozo');
        $perm = App\plt::select(DB::raw('*'))
        ->where('pozo_id','=',$pozo_id)
        ->get();

        return Response::json($perm);
    });

    Route::get('excel81', function()
    {
        $intervalo_id = Input::get('intervalo');
        $perm = App\formacionxpozo::select(DB::raw('*'))
        ->where('id','=',$intervalo_id)
        ->select('id', 'nombre')
        ->get();

        return Response::json($perm);
    });

    Route::get('excel811', function()
    {
        $intervalo = Input::get('well');
        $perm = App\plt::select(DB::raw('*'))
        ->where('pozo_id','=',$intervalo)
        ->get();

        return Response::json($perm);
    });


    Route::get('excel53', function()
    {
        $campo = Input::get('campo');
        $perm = App\coordenada_campos::select(DB::raw('*'))
        ->where('campo_id','=',$campo)
        ->get();

        return Response::json($perm);
    });

    Route::get('excel531', function()
    {
        $formacion = Input::get('formacion');
        $perm = App\pvt::select(DB::raw('*'))
        ->where('formacion_id','=',$formacion)
        ->get();

        return Response::json($perm);
    });

    Route::get('excelPvtIntervalos', function()
    {
        $formacion = Input::get('formacion');
        $perm = App\PvtFormacionXPozo::select(DB::raw('*'))
        ->where('formacionxpozos_id','=',$formacion)
        ->get();

        return Response::json($perm);
    });



    Route::get('excel12', function()
    {
        $pozo = Input::get('pozo');
        $perm = App\datos_produccion::select(DB::raw('*'))
        ->where('pozo_id','=',$pozo)
        ->get();

        return Response::json($perm);
    });

    Route::get('excel_pro', function()
    {
        $peticion = Input::get('peticion');
        $perm = App\datos_produccion_peticion::select(DB::raw('*'))
        ->where('peticion_id','=',$peticion)
        ->get();

        return Response::json($perm);
    });


    Route::get('multiparametrico2', function()
    {
        $scenary = Input::get('scenary');
        $multi = App\Models\MultiparametricAnalysis\Statistical::select(DB::raw('*'))
        ->where('escenario_id','=',$scenary)
        ->select('id')
        ->get();

        return Response::json($multi);
    });


    Route::get('map', 'MapController@maps');

    #****////****////****////


    # Georreferenciación - General Data

    Route::get('field_scale', function()
    {
        $basin = Input::get('basin');
        $option = Input::get('option');

        $fields_coordinates = [];
        $fields_info = [];

        $fields_with_pvt = App\pvt::select('campo_id')->distinct()->get();
        $field_pvt_ids = [];

        $field_ids = [];
        foreach ($fields_with_pvt as $field_pvt) 
        {
            array_push($field_pvt_ids, $field_pvt->campo_id);
        }

        $fields = App\campo::where('cuenca_id',$basin)->get();
        foreach ($fields as $field) 
        {
            $field_info_row = [];
            $field_coordinates_row = [];

            array_push($field_info_row, $field->id);
            array_push($field_info_row, $field->nombre);
            array_push($field_ids, $field->id);
            $coordinates_query = App\coordenada_campos::where('campo_id',$field->id)->orderBy('orden')->get();
            foreach ($coordinates_query as $value) 
            {
                array_push($field_coordinates_row, [$value->lat, $value->lon]);
            }
            array_push($field_info_row, $field_coordinates_row);

            if($option==1)
            {
                if(in_array($field->id, $field_pvt_ids))
                {
                    array_push($field_info_row, True);
                }
                else
                {
                    array_push($field_info_row, False);
                }
            }
            array_push($fields_info, $field_info_row);
        }
        $center = App\pozo::select(DB::raw('avg(lat) as lat, avg(lon) as lon'))
        ->wherein('campo_id',$field_ids)
        ->get();
        return Response::json(["data" => $fields_info, "center" => $center]);
    });

    Route::get('well_scale', function()
    {
        $fields = Input::get('fields');
        $option = Input::get('option');
        $general_info_total = [];
        if($option == "plt")
        {
            #Fields information
            $fields_query = App\campo::wherein('id',$fields)->get();
            $fields_info = [];
            $field_ids = [];

            foreach ($fields_query as $field) 
            {
                $c_has_plt_field = 0;
                $c_hasnt_plt_field = 0;
                $fields_info_row = [];
                $wells_with_plt = [];
                array_push($fields_info_row, $field->nombre);
                #Coordinates
                $coordinates_query = App\coordenada_campos::where('campo_id',$field->id)->orderBy('orden')->get();
                array_push($fields_info_row, $coordinates_query);
                #Wells with plt
                $wells_ids_plt = App\plt::select('pozo_id')->distinct()->get();
                $plt_wells_ids = [];
                foreach ($wells_ids_plt as $value) 
                {
                    array_push($plt_wells_ids, $value->pozo_id);
                }
                $wellls_by_field = App\pozo::select('id','lat','lon','nombre')
                ->where('campo_id',$field->id)
                ->get();
                foreach ($wellls_by_field as $value) 
                {
                    if(in_array($value->id, $plt_wells_ids))
                    {
                        array_push($wells_with_plt, [$value, True]);
                        $c_has_plt_field +=1;
                    }
                    else
                    {
                        array_push($wells_with_plt, [$value, False]);
                        $c_hasnt_plt_field +=1;
                    }
                }


                array_push($fields_info_row, $wells_with_plt);
                array_push($fields_info_row, [$c_has_plt_field, $c_hasnt_plt_field]);
                array_push($fields_info, $fields_info_row);
                array_push($field_ids, $field->id);
            }
            $center = App\pozo::select(DB::raw('avg(lat) as lat, avg(lon) as lon'))
            ->wherein('campo_id',$field_ids)
            ->get();
        }
        elseif($option == "production_data")
        {
            #Fields information
            $fields_query = App\campo::wherein('id',$fields)->get();
            $fields_info = [];
            $field_ids = [];
            foreach ($fields_query as $field) 
            {
                $c_has_plt_field = 0;
                $c_hasnt_plt_field = 0;
                $fields_info_row = [];
                $wells_with_production_data = [];
                array_push($fields_info_row, $field->nombre);
                #Coordinates
                $coordinates_query = App\coordenada_campos::where('campo_id',$field->id)->orderBy('orden')->get();
                array_push($fields_info_row, $coordinates_query);
                #Wells with production data
                $wells_ids_production_data = App\production_data::select('pozo_id')->distinct()->get();
                $production_data_wells_ids = [];
                foreach ($wells_ids_production_data as $value) 
                {
                    array_push($production_data_wells_ids, $value->pozo_id);
                }
                $wellls_by_field = App\pozo::select('id','lat','lon','nombre')
                ->where('campo_id',$field->id)
                ->get();
                foreach ($wellls_by_field as $value) 
                {
                    if(in_array($value->id, $production_data_wells_ids))
                    {
                        array_push($wells_with_production_data, [$value, True]);
                        $c_has_plt_field +=1;
                    }
                    else
                    {
                        array_push($wells_with_production_data, [$value, False]);
                        $c_hasnt_plt_field +=1;
                    }
                }


                array_push($fields_info_row, $wells_with_production_data);
                array_push($fields_info_row, [$c_has_plt_field, $c_hasnt_plt_field]);
                array_push($fields_info, $fields_info_row);
                array_push($field_ids, $field->id);
            }
            $center = App\pozo::select(DB::raw('avg(lat) as lat, avg(lon) as lon'))
            ->wherein('campo_id',$field_ids)
            ->get();
        }
        else
        {
            #Fields information
            $fields_query = App\campo::wherein('id',$fields)->get();
            $fields_info = [];
            $field_ids = [];
            foreach ($fields_query as $field) 
            {
                $fields_info_row = [];
                array_push($fields_info_row, $field->nombre);
                #Coordinates
                $coordinates_query = App\coordenada_campos::where('campo_id',$field->id)->orderBy('orden')->get();
                array_push($fields_info_row, $coordinates_query);
                #Well and fluid info by field
                $well_fluid_query = App\pozo::join('fluidoxpozos as f', 'f.pozo_id','=','pozos.id')
                ->select(DB::raw('lat,lon,nombre,'.$option.' as value'))
                ->where('campo_id',$field->id)
                ->groupBy('pozos.id')
                ->get();
                $general_info = App\pozo::join('fluidoxpozos as f', 'f.pozo_id','=','pozos.id')
                ->select(DB::raw('max('.$option.') as max, min('.$option.') as min, avg('.$option.') as avg'))
                ->where('campo_id',$field->id)
                ->first();
                array_push($fields_info_row, $well_fluid_query);
                array_push($fields_info_row, $general_info);
                array_push($fields_info, $fields_info_row);
                array_push($field_ids, $field->id);
            }
            $center = App\pozo::select(DB::raw('avg(lat) as lat, avg(lon) as lon'))
            ->wherein('campo_id',$field_ids)
            ->get();
            $general_info_total = App\pozo::join('fluidoxpozos as f', 'f.pozo_id','=','pozos.id')
            ->select(DB::raw('max('.$option.') as max, min('.$option.') as min, avg('.$option.') as avg, std('.$option.') as sd'))
            ->wherein('campo_id',$field_ids)
            ->first();
        }

        return Response::json(["data" => $fields_info, "center" => $center, "general_info" => $general_info_total]);
    });

    #GeoR - Histórico - Frecuencias - Multiparamétrico

    Route::get('multidata', function(){

        $parametro = Input::get('parametro');
        $formacion = Input::get('formacion');
        $campo = Input::get('campo');

        $chart = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select(DB::raw('count(*) as Freq'),'mediciones.valor as valorchart')
        ->where('mediciones.subparametro_id','=',$parametro)
        ->where('mediciones.formacion_id','=',$formacion)
        ->where('p.campo_id','=',$campo)
        ->groupBy('mediciones.valor')
        ->get();

        $data = array('Chart'=>$chart);
        return Response::json($data);

    });

    Route::get('variables_dano', function()
    {
        $datos = App\variable_dano::all();

        return Response::json($datos);
    });

    Route::get('Multiparametrico', array('as'=>'Multiparametrico', function()
    {
        if (\Auth::check()) {
            return View::make('Mult');
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('freq', array('as'=>'freq', function()
    {
        if (\Auth::check()) {
            $statistical = App\Models\MultiparametricAnalysis\Statistical::find(Input::get('statistical'));
            $cuencas = App\cuenca::orderBy('nombre')->get();
            return  view('Freq', ['cuencas' => $cuencas, 'statistical' => $statistical]);
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('histo', array('as'=>'histo', function()
    {

        if (\Auth::check()) {
            $statisticalInput = Input::get('statistical');
            $statistical = 'false';
            
            if ($statisticalInput) {
                $statistical = App\Models\MultiparametricAnalysis\Statistical::find(Input::get('statistical'));
            }
            $cuencas = App\cuenca::orderBy('nombre')->get();
            return view('Hist', ['cuencas' => $cuencas, 'statistical' => $statistical]);
        } else {
            return view('loginfirst');
        }
    }));

    Route::get('frecuencia', function(){

        $parametro = Input::get('parametro');
        $formacion = Input::get('formacion');
        $campo = Input::get('campo');

        
        $chart = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select(DB::raw('count(mediciones.valor) as Freq'),'mediciones.valor as valorchart')
        ->where('mediciones.subparametro_id','=',$parametro)
        ->where('mediciones.formacion_id','=',$formacion)
        ->where('p.campo_id','=',$campo)
        ->groupBy('mediciones.valor')
        ->get();



        return Response::json($chart);

    });

    Route::get('Geor', array('as'=>'Geor', function()
    {
        if (\Auth::check()) {
            $cuencas = App\cuenca::orderBy('nombre')->get();
            
            return  view('Geor', ['cuencas' => $cuencas]);
        }else{
            return view('loginfirst');
        }
    }));

    Route::get('campos', function(){

        $cuenca = Input::get('cuenca');
        $campos = App\campo::where('cuenca_id','=',$cuenca)->select('id', 'nombre')->orderBy('nombre')->get();

        return Response::json($campos);

    });



    Route::get('fields', function(){

        $field = Input::get('field');
        $pozos = App\pozo::where('campo_id','=',$field)->select('id', 'nombre')->orderBy('nombre')->get();

        return Response::json($pozos);

    });

    Route::get('P', function() {
        $arreglo = [];
        $array = App\subparametro::select('id')->orderBy('id', 'asc')->get();
        $formacion = Input::get('formacion');
        $campo = Input::get('campo');
        if(!strcmp($campo, "Todos")) {
            foreach ($array as $arr) {
                $chart = DB::table('mediciones')
                    ->join('pozos as p', 'mediciones.pozo_id', '=', 'p.id')
                    ->select('mediciones.valor as valorchart')
                    ->where('subparametro_id', $arr['id'])
                    ->orderBy('valorchart')
                    ->get();
                $arreglo[$arr['id']] = $chart;
            }
        } else {
            foreach ($array as $arr) {
                $chart = DB::table('mediciones')
                    ->join('pozos as p', 'mediciones.pozo_id', '=', 'p.id')
                    ->select('mediciones.valor as valorchart')
                    ->wherein('p.campo_id', explode(',', $campo))
                    ->where('subparametro_id', $arr['id'])
                    ->orderBy('valorchart')
                    ->get();
                $arreglo[$arr['id']] = $chart;
            }
        }

        return Response::json($arreglo);
    });

    Route::get('P_mediciones', function() {
        $arreglo = [];
        $aux_count = 1;
        $max_aux_count = App\subparametro::count();
        for ($i=$aux_count; $i <= $max_aux_count; $i++) { 
            $chart = DB::table('mediciones')->select('valor')->where('subparametro_id', strval($i))->orderBy('valor')->get();
            if ( count($chart) == 0 ) {
                $arreglo[$i] = null;
            } else {
                $arreglo[$i] = $chart;
            }
        }

        return Response::json($arreglo);
    });

    Route::get('p10p90Colombia', function() {
        $subparameterId = Input::get('subparameterId');
        $subparameterId = DB::table('subparametros')->select('id')->where('sigla', $subparameterId)->get()[0]->id;
        $chart_aux = DB::table('mediciones')->select('valor')->where('subparametro_id', strval($subparameterId))->get();

        $chart = [];
        for ($i=0; $i < count($chart_aux); $i++) { 
            array_push($chart, $chart_aux[$i]->valor);
        }

        // Calcular p10 y p90 a partir de 10 valores 
        if ( count($chart) < 10 ) {
            return Response::json([0,0]);
        } else {
            $arreglo = $chart;
            dd($arreglo);
            // $arreglo = [0.297896658,0.354257558,0.43355952,0.442299617,0.375702706,0.456600133,0.447127094,0.41067616,0.406965806,0.354161922,0.425308864,0.40079783,0.375234556,0.401198404,0.380552345,0.462244928,0.354702895,0.375542513,0.374199177,0.335397535,0.45625853,0.382470309,0.381922201,0.421178752,0.355043769,0.412005688,0.35260965,0.401246569,0.441996619,0.41948255,0.433160085,0.416589267,0.40025943,0.453368651,0.460083791,0.383147822,0.415865992,0.420839643,0.392147078,0.394490425,0.359935031,0.38009886,0.384763234,0.446469279,0.426045828,0.377337132,0.423085908,0.3464339,0.448219737,0.328457345,0.401154499,0.365199677,0.41845962,0.365378878,0.385054612,0.430075915,0.362967779,0.371563661,0.407672044,0.388743947,0.405286257,0.335381974,0.416006426,0.377520317,0.369251181,0.431954977,0.406073612,0.390196492,0.399165096,0.378326383,0.343617315,0.386289569,0.420358899,0.409256908,0.337022808,0.37043789,0.371965883,0.411103083,0.391055317,0.457055383,0.434949799,0.494094813,0.408956089,0.338910591,0.471030456,0.347089662,0.336304054,0.389447719,0.373505032,0.345695719,0.371096321,0.391479544,0.371052378,0.401859206,0.415098454,0.449038596,0.354626138,0.431635281,0.3832322,0.395222702,0.428169715,0.392788073,0.39947353,0.415820426,0.387079217,0.402947321,0.368838268,0.402538669,0.45087479,0.42039406,0.398001776,0.407368536,0.404945016,0.46253544,0.364684595,0.415857386,0.379831126,0.417301347,0.420359096,0.350088521,0.405928397,0.37571104,0.409641636,0.330875147,0.432472564,0.38709562,0.386380277,0.378072694,0.423204313,0.417176601,0.383587632,0.384217302,0.41276013,0.402347621,0.427672415,0.400133226,0.405787423,0.46102486,0.391342908,0.391628347,0.395401977,0.404830473,0.381893467,0.433423769,0.395332168,0.391919622,0.442535675,0.442687392,0.410070809,0.403610979,0.36491,0.409951745];
            $min = min($arreglo);
            $max = max($arreglo);
            $range = abs($max - $min);
            
            $a = $range/100;
            $k = 100;
            
            $li = [];
            array_push($li, $min);
            for ($i=0; $i < $k-1; $i++) { 
                array_push($li, $li[count($li)-1]+$a );
            }

            $ls = [];
            array_push($ls, $min+$a);
            for ($i=0; $i < $k-1; $i++) { 
                array_push($ls, $ls[count($ls)-1]+$a );
            }

            $pm = [];
            for ($i=0; $i < $k; $i++) { 
                array_push($pm, ($li[$i]+$ls[$i])/2);
            }

            $fi =[];
            for ($i=0; $i < $k; $i++) { 
                $aux1 = [];
                $aux2 = [];
                for ($j=0; $j < count($arreglo); $j++) { 
                    if ($arreglo[$j] >= $li[$i]) {
                        array_push($aux1, $arreglo[$j]);
                    }
                }
                if($i < $k-1) {
                    for ($j=0; $j < count($arreglo); $j++) { 
                        if ($arreglo[$j] > $ls[$i]) {
                            array_push($aux2, $arreglo[$j]);
                        }
                    }
                    array_push($fi, count($aux1)-count($aux2));
                } else { 
                    array_push($fi, count($aux1));
                }
            }
            
            $Fi = [];
            array_push($Fi, $fi[0]);
            for ($i=1; $i < $k; $i++) { 
                array_push($Fi, $Fi[$i-1]+$fi[$i]);
            }

            $p10aux = 10*count($arreglo)/100;
            for ($i=0; $i < $k; $i++) { 
                if ($Fi[$i] > $p10aux) {
                    $p10pos = $i;
                    break;
                }
            }
            $p10li = $li[$p10pos];
            // Arreglo
            if ($p10pos == 0) {
                $p10Fi = $Fi[$p10pos];
            } else {
                $p10Fi = $Fi[$p10pos-1];
            }
            $p10fi = $fi[$p10pos];
            $p10 = $p10li + ($a * ((( (count($arreglo) * 10) / 100 ) - $p10Fi) / $p10fi) );

            $p90aux = 90*count($arreglo)/100;
            for ($i=0; $i < $k; $i++) { 
                if ($Fi[$i] > $p90aux) {
                    $p90pos = $i;
                    break;
                }
            }
            $p90li = $li[$p90pos];
            $p90Fi = $Fi[$p90pos-1];
            $p90fi = $fi[$p90pos];
            $p90 = $p90li + ($a * ((( (count($arreglo) * 90) / 100 ) - $p90Fi) / $p90fi) );

            return Response::json([$p10, $p90]);
        }

    });

    Route::get('p10p90Calculate', function() {
        $subparameterId = Input::get('subparameterId');
        $subparameterId = DB::table('subparametros')->select('id')->where('sigla', $subparameterId)->get()[0]->id;
        $basin = Input::get('basin');
        $basin = intval($basin);
        $fields = Input::get('fields');
        $fields = urldecode($fields); //converts to array
        $fields = json_decode($fields);
        $fields = array_map('intval', $fields);
        // pozos que se relacionen con los fields
        $pozos = [];

        for ($i=0; $i < count($fields); $i++) { 
            $aux_pozos = DB::table('pozos')->where('campo_id', $fields[$i])->get();
            $pozos = array_merge($pozos, $aux_pozos);
        }
        
        $mediciones = [];
        for ($i=0; $i < count($pozos); $i++) { 
            $aux_mediciones = DB::table('mediciones')->where('pozo_id', $pozos[$i]->id)->get();
            $mediciones = array_merge($mediciones, $aux_mediciones);
        }

        $arreglo = [];
        for ($i=0; $i < count($mediciones); $i++) { 
            if ($mediciones[$i]->subparametro_id == $subparameterId) { 
                array_push($arreglo, $mediciones[$i]->valor);
            }
        }

        // dd($arreglo);
        // dd($arreglo, $mediciones, $pozos, $fields, $subparameterId);
        
        //////////////////////////////////////////////////////////

        // Calcular p10 y p90 a partir de 10 valores 
        if ( count($arreglo) < 10 ) {
            return Response::json([0,0]);
        } else {

            $min = min($arreglo);
            $max = max($arreglo);
            $range = abs($max - $min);

            $a = $range/100;
            $k = 100;

            $li = [];
            array_push($li, $min);
            for ($i=0; $i < $k-1; $i++) { 
                array_push($li, $li[count($li)-1]+$a );
            }

            $ls = [];
            array_push($ls, $min+$a);
            for ($i=0; $i < $k-1; $i++) { 
                array_push($ls, $ls[count($ls)-1]+$a );
            }
            
            $pm = [];
            for ($i=0; $i < $k; $i++) { 
                array_push($pm, ($li[$i]+$ls[$i])/2);
            }

            $fi =[];
            for ($i=0; $i < $k; $i++) { 
                $aux1 = [];
                $aux2 = [];
                for ($j=0; $j < count($arreglo); $j++) { 
                    if ($arreglo[$j] >= $li[$i]) {
                        array_push($aux1, $arreglo[$j]);
                    }
                }
                if($i < $k-1) {
                    for ($j=0; $j < count($arreglo); $j++) { 
                        if ($arreglo[$j] >= $ls[$i]) {
                            array_push($aux2, $arreglo[$j]);
                        }
                    }
                    array_push($fi, count($aux1)-count($aux2));
                } else { 
                    array_push($fi, count($aux1));
                }
            }
            
            $Fi = [];
            array_push($Fi, $fi[0]);
            for ($i=1; $i < $k; $i++) { 
                array_push($Fi, $Fi[$i-1]+$fi[$i]);
            }
            
            $p10aux = 10*count($arreglo)/100;
            for ($i=0; $i < $k; $i++) { 
                if ($Fi[$i] > $p10aux) {
                    $p10pos = $i;
                    break;
                }
            }
            $p10li = $li[$p10pos];
            if ($p10pos == 0 ) {
                $p10Fi = $Fi[$p10pos];
            } else {
                $p10Fi = $Fi[$p10pos-1];
            }
            $p10fi = $fi[$p10pos];
            $p10 = $p10li + ($a * ((( (count($arreglo) * 10) / 100 ) - $p10Fi) / $p10fi) );

            $p90aux = 90*count($arreglo)/100;
            for ($i=0; $i < $k; $i++) { 
                if ($Fi[$i] > $p90aux) {
                    $p90pos = $i;
                    break;
                }
            }
            $p90li = $li[$p90pos];
            if ($p90pos == 0 ) {
                $p90Fi = $Fi[$p90pos];
            } else {
                $p90Fi = $Fi[$p90pos-1];
            }
            $p90fi = $fi[$p90pos];
            $p90 = $p90li + ($a * ((( (count($arreglo) * 90) / 100 ) - $p90Fi) / $p90fi) );

            if ($p10 < $min) {
                $p10 = $min; 
            }
            if ($p90 > $max) {
                $p90 = $max;
            }

            return Response::json([$p10, $p90]);
        }
    });

    Route::get('subparameterbywell', function() {
        $subparameters = DB::table('mediciones')
            ->where('pozo_id', Input::get('pozoId'))
            ->orderBy('subparametro_id')
            ->get();

        return Response::json($subparameters);
    });

    Route::get('subparameterbywellanalytical', function() {
        $subparameters = DB::table('mediciones')
            ->where('pozo_id', Input::get('pozoId'))
            ->wherein('subparametro_id', [8, 21])
            ->orderBy('subparametro_id')
            ->get();

        return Response::json($subparameters);
    });

    Route::get('pozosF', function(){

        $campo = Input::get('campo');
        $pozo = App\pozo::where('campo_id','=',$campo)->select('id', 'nombre')->orderBy('nombre')->get();

        return Response::json($pozo);

    });

    Route::get('parametros', function(){

        $mec = Input::get('mec');
        $parametros = App\subparametro::where('mecdan_id','=',$mec)
        ->get();

        return Response::json($parametros);

    });




    Route::get('spiderinfo',function()
    {
        $mp = Input::get('mp');
        $multiparametric = App\Models\MultiparametricAnalysis\Statistical::find($mp);


        $datos =  collect();
        $datos->put('fecha', $multiparametric->escenario->fecha);
        $datos->put('scnombre ', $multiparametric->escenario->nombre );
        $datos->put('nombre', $multiparametric->escenario->pozo->nombre);
        $datos->put('Fnombre', $multiparametric->escenario->formacionxpozo->nombre);
        $datos->put('Cnombre', $multiparametric->escenario->pozo->campo->nombre);
        /*$datos = App\Models\MultiparametricAnalysis\Statistical::join('escenarios', 'escenarios.id','=', 'multiparametric_analysis_statistical.escenario_id')
        ->join('pozos AS p','p.id','=','escenarios.pozo_id')
        ->join('campos as c','p.campo_id','=','c.id')
        ->join('Formaciones as f','escenarios.formacion_id','=','f.id')
        ->select('escenarios.fecha', 'escenarios.nombre as scnombre', 'p.nombre','f.nombre as Fnombre','c.nombre as Cnombre')
        ->where('multiparametric_analysis_statistical.id','=',$mp)->get();*/

        $intervalo = $multiparametric->escenario->formacionxpozo;

        
        $data = array('datos'=>$datos,'intervalo'=>$intervalo);

        return Response::json($data);
    });

    Route::get('nombresp', function(){

        $parametro = Input::get('parametro');
        $pozo = Input::get('pozo');
        $cid = Input::get('campo');

        $campo = App\pozo::join('campos AS c','pozos.campo_id','=','c.id')
        ->select('c.nombre as nombre')
        ->where('pozos.id','=',$pozo)
        ->get();

        $ncampo = App\campo::select('nombre')
        ->wherein('id',$cid)
        ->get();

        $nombre = App\subparametro::select('nombre')
        ->where('id','=',$parametro)
        ->get();

        $npozo = App\pozo::select('nombre')
        ->where('id','=',$pozo)
        ->get();
         $data = array('nombre'=>$nombre,'campo'=>$campo, 'NCampo'=>$ncampo, 'NPozo'=>$npozo);

        return Response::json($data);

    });

    Route::get('pozosFiltros',function()
        {
            $cid = Input::get('campo');
            $pozos = App\pozo::
            select('id','nombre')
            ->wherein('campo_id',$cid)
            ->get();

            return Response::json($pozos);
        });

    Route::get('pozosHist', function()
    {
        $pozo = Input::get('pozo');
        $sp = Input::get('parametro');

        $datos = App\medicion::select('mediciones.*', 'p.nombre as nombre')
            ->join('pozos as p', 'p.id', '=', 'mediciones.pozo_id')
            ->where('pozo_id', $pozo)
            ->where('subparametro_id', $sp)
            ->orderBy('fecha')
            ->get();

        return Response::json($datos);
    });

    Route::get('allwellsgeoreference', function() {
        $wells = App\pozo::select('c.nombre as Cnombre', 'pozos.*')
            ->join('campos as c', 'c.id', '=', 'pozos.campo_id')
            ->get();

        $data = array('Wells' => $wells);
        return Response::json($data);
    });

    Route::get('basinwellsgeoreference', function() {
        $basin = Input::get('basin');

        $wells = App\pozo::select('c.nombre as Cnombre', 'pozos.*')
            ->join('campos as c', 'c.id', '=', 'pozos.campo_id')
            ->where('c.cuenca_id', $basin)
            ->get();

        $data = array('Wells' => $wells);
        return Response::json($data);
    });

    Route::get('fieldwellsgeoreference', function() {
        $fields = Input::get('fields');
        $basin = Input::get('basin');

        if ($fields) {
            $wells = App\pozo::select('c.nombre as Cnombre', 'pozos.*')
                ->join('campos as c', 'c.id', '=', 'pozos.campo_id')
                ->wherein('c.id', $fields)
                ->get();

            $coords = [];
            $genfields = [];
            $wellsxField = [];

            foreach ($fields as $field) {
                $coord = App\coordenada_campo::where('campo_id', $field)
                    ->orderBy('id')
                    ->get();

                $genfield = App\campo::select(DB::raw('nombre'))
                    ->where('id', $field)
                    ->get();

                $wellsxfield = App\pozo::select(DB::raw('count(*) as count'))
                    ->where('campo_id', $field)
                    ->get();

                array_push($coords, $coord);
                array_push($genfields, $genfield);
                array_push($wellsxField, $wellsxfield);
            }
        } else {
            $wells = App\pozo::select('c.nombre as Cnombre', 'pozos.*')
                ->join('campos as c', 'c.id', '=', 'pozos.campo_id')
                ->where('c.cuenca_id', $basin)
                ->get();

            $coords = [];
            $genfields = [];
            $wellsxField = [];
        }

        $data = array('Wells' => $wells, 'Coords' => $coords, 'Genfields' => $genfields, 'WellsxField' => $wellsxField);
        return Response::json($data);
    });

    Route::get('pozos', function(){

        $parametro = Input::get('parametro');
        $campo = Input::get('campo');
        $formacion = Input::get('formacion');
        $multi = Input::get('multi');
        $coors = [];
        $coorsc = [];
        $gencampos = [];
        $pozosc = [];
        $i=0;

        $datos = array();

        if ($multi) {
            $datos = App\Models\MultiparametricAnalysis\Statistical::select('statistical', 'basin_statistical', 'field_statistical')
                ->where('id', $multi)
                ->get();
        }

        $unidades = App\subparametro::where('id',$parametro)->get();

        $centro = App\pozo::select(DB::raw('avg(lat) as lat, avg(lon) as lon'))
        ->wherein('campo_id',$campo)
        ->get();

         $pozosavg = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->select('c.nombre as Cnombre','mediciones.fecha','mediciones.Comentario as comentario','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', DB::raw('AVG(mediciones.valor) as valor') )
        ->where('mediciones.subparametro_id','=',$parametro)
        #->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozosmin = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->select('c.nombre as Cnombre','mediciones.fecha','mediciones.Comentario as comentario','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', DB::raw('MIN(mediciones.valor) as valor') )
        ->where('mediciones.subparametro_id','=',$parametro)
        #->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozosmax = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->select('c.nombre as Cnombre','mediciones.fecha','mediciones.Comentario as comentario','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', DB::raw('MAX(mediciones.valor) as valor') )
        ->where('mediciones.subparametro_id','=',$parametro)
        #->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozos = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->select('c.nombre as Cnombre','mediciones.fecha','mediciones.Comentario as comentario','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', 'mediciones.valor as valor')
        ->where('mediciones.subparametro_id','=',$parametro)
        #->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $aux = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select('p.Id AS Id')
        ->where('mediciones.subparametro_id','=',$parametro)
        #->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozos2 = App\pozo::join('campos as c', 'c.id','=','pozos.campo_id')
        ->select('c.nombre as Cnombre','pozos.*')
        ->wherenotin('pozos.Id',$aux)
        ->wherein('pozos.campo_id',$campo)
        ->get();

        $general = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->distinct()
        ->select(DB::raw('MAX(mediciones.valor) as Maximo'), DB::raw('MIN(mediciones.valor) as Minimo'),
            DB::raw('AVG(mediciones.valor) as Media'),DB::raw('STD(mediciones.valor) as SD') , DB::raw('count(distinct(p.nombre)) as pb'))
        ->where('mediciones.subparametro_id','=',$parametro)
        #->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campo)
        ->get();

        $general2 = App\pozo::wherenotin('Id',$aux)
        ->select(DB::raw('count(distinct(pozos.nombre)) as pm'))
        ->wherein('pozos.campo_id',$campo)
        ->get();

        $chart = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select(DB::raw('count(*) as Freq'),'mediciones.valor as valorchart')
        ->where('mediciones.subparametro_id','=',$parametro)
        #ñ->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campo)
        ->groupBy('mediciones.valor')
        ->orderBy('valorchart')
        ->get();

        $chart2 = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select(DB::raw('distinct p.id, mediciones.valor as valorchart'))
        ->where('mediciones.subparametro_id','=',$parametro)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.id')
        ->orderBy('valorchart')
        ->get();


        foreach($campo as $c)
        {
            $coordenadasc = App\coordenada_campo::where('campo_id','=',$c)
            ->orderBy('Orden')
            ->get();

            $coordenadas = App\coordenada_formacion::where('campo_id','=',$c)
            ->where('formacion_id','=',$formacion)
            ->orderBy('Orden')
            ->get();

            $gencampo = App\medicion::join('pozos as p', 'mediciones.pozo_id','=','p.Id')
            ->join('campos as c','c.id','=','p.campo_id')
            ->select(DB::raw('avg(mediciones.valor) as avg'),DB::raw('min(mediciones.valor) as min'),DB::raw('max(mediciones.valor) as max'),DB::raw('std(mediciones.valor) as sd'),DB::raw('count(distinct(p.Id)) as count'),'c.nombre as cnombre')
            ->where('p.campo_id','=',$c)
            ->where('mediciones.subparametro_id','=',$parametro)
            ->get();

            $pozoscampos = App\pozo::select(DB::raw('count(*) as count'))
            ->where('campo_id','=',$c)
            ->get();

            array_push($gencampos,$gencampo);
            array_push($coors, $coordenadas);
            array_push($coorsc, $coordenadasc);
            array_push($pozosc,$pozoscampos);
        }

        $data = array('PozosC'=>$pozosc,'Pozosmin'=>$pozosmin, 'Pozosmax'=>$pozosmax,'Pozosavg'=> $pozosavg, 'Coordenadasc'=>$coorsc, 'Gencampos'=>$gencampos ,'Coordenadas'=>$coors,'Pozos'=>$pozos,'Pozos2'=>$pozos2, 'General'=>$general,'Chart'=>$chart,'Chart2'=>$chart2,'General2'=>$general2,'Centro'=>$centro,'unidades'=>$unidades,'datos'=>$datos);
        return Response::json($data);
    });





    Route::get('campos2', function(){

        $cuenca = Input::get('cuenca');
        $campos = App\campo::where('cuenca_id','=',$cuenca)->get();

        return Response::json($campos);
    });



    Route::get('formaciones2', function(){

        $campos = Input::get('campos');
        
        $formaciones = App\formacion::orderBy('nombre')->get();

        return Response::json($formaciones);
    });

    Route::get('parametros2', function(){

        $parametros = App\subparametro::all();

        return Response::json($parametros);
    });

    Route::get('historico', function(){

        $parametro = Input::get('parametro');
        $pozo = Input::get('pozo');
        $campo = Input::get('campo');
        $multi = Input::get('multi');
        
        $datos = array();

        if ($multi) {
            $datos = App\Models\MultiparametricAnalysis\Statistical::select('statistical', 'basin_statistical', 'field_statistical')
                ->where('id', $multi)
                ->get();
        }

        $chart = App\medicion::select('mediciones.fecha as fecha', 'mediciones.valor as valorchart', 'p.nombre as nombre')
            ->join('pozos AS p', 'mediciones.pozo_id', '=', 'p.Id')
            ->where('mediciones.subparametro_id', $parametro)
            ->wherein('p.campo_id', $campo)
            ->orderBy('mediciones.fecha')
            ->get();

        $data = array('datos' => $datos, 'Chart' => $chart);
        return Response::json($data);

    });




    Route::get('datosM', function()
    {

        $ultimos = [];
        $maximos = [];
        $valores = [];

        $valoresxx = [];
        $ps = [];
        $mp = Input::get('mpid');

        $weights_data = App\subparameters_weight::where('multiparametric_id', '=',$mp)->first();

        $weights = [$weights_data->ms_scale_index_caco3,$weights_data->ms_scale_index_baso4,$weights_data->ms_scale_index_iron_scales,$weights_data->ms_calcium_concentration,$weights_data->ms_barium_concentration,$weights_data->fb_aluminum_concentration,$weights_data->fb_silicon_concentration,$weights_data->fb_critical_radius_factor,$weights_data->fb_mineralogic_factor,$weights_data->fb_crushed_proppant_factor,$weights_data->os_cll_factor,$weights_data->os_compositional_factor,$weights_data->os_pressure_factor,$weights_data->os_high_impact_factor,$weights_data->rp_days_below_saturation_pressure,$weights_data->rp_delta_pressure_saturation,$weights_data->rp_water_intrusion,$weights_data->rp_high_impact_factor,$weights_data->id_gross_pay,$weights_data->id_polymer_damage_factor,$weights_data->id_total_volume_water,$weights_data->id_mud_damage_factor,$weights_data->gd_fraction_netpay,$weights_data->gd_drawdown,$weights_data->gd_ratio_kh_fracture,$weights_data->gd_geomechanical_damage_fraction]; 


        $mpdatos = App\Models\MultiparametricAnalysis\Statistical::where('id','=',$mp)
        ->get();
        $ids = App\subparametro::select('id')
        ->get()
        ->keyBy('id');
        $xxx = App\Models\MultiparametricAnalysis\Statistical::select('statistical')
        ->where('id','=',$mp)
        ->get();

        if(is_null($xxx))
        {
            foreach ($ids as $id) {

                $aux = App\medicion::join('pozos as p', 'p.id','=','mediciones.pozo_id')
                ->select('valor')
                ->where('fecha','=',DB::raw('(select max(fecha) from mediciones where subparametro_id ='. $id['id'] .') limit 1'))
                ->wherein('p.campo_id',explode(" ",$mpdatos['field_statistical']))
                ->get();

                $aux2 = App\medicion::join('pozos as p', 'p.id','=','mediciones.pozo_id')
                ->select(DB::raw('count(*) as Cantidad'))
                ->where('subparametro_id','=',$id['id'])
                ->wherein('p.campo_id',explode(" ",$mpdatos['field_statistical']))
                ->get();

                $aux3 = App\medicion::join('pozos as p', 'p.id','=','mediciones.pozo_id')
                ->select('valor')
                ->where('subparametro_id','=',$id['id'])
                ->wherein('p.campo_id',explode(" ",$mpdatos['field_statistical']))
                ->orderBy('valor')  
                ->get();


                array_push($ultimos, $aux);
                array_push($maximos, $aux2);
                array_push($valores, $aux3);
                
            }  
        }
        else
        {
         foreach ($ids as $id) {

             $aux = App\medicion::select('valor')
             ->where('fecha','=',DB::raw('(select max(fecha) from mediciones where subparametro_id ='. $id['id'] .') limit 1'))
             ->get();

             $aux2 = App\medicion::select(DB::raw('count(*) as Cantidad'))
             ->where('subparametro_id','=',$id['id'])
             ->get();
             //select count(*) as cantidad from mediciones where subparametro_id = 1

             $aux3 = App\medicion::select('valor')
             ->where('subparametro_id','=',$id['id'])
             ->orderBy('valor')  
             ->get();

             //select valor from mediciones from subparametro_id = 1 orderBy valor


             array_push($ultimos, $aux); //valor de las ultimas fechas por parametro en mediciones
             array_push($maximos, $aux2);//conteo de registro por parametros en mediciones
             array_push($valores, $aux3);//total de registros por parametros en mediciones
             
         }   
        }


        $f = 0;
        


        foreach ($valores as $key) {
            $valoresx = [];
            foreach ($key as $val) {
                array_push($valoresx,$val['valor']);
            }
            array_push($valoresxx,$valoresx);
        }


        foreach($maximos as $m)
        {
            //dd($m);
                $pst = [];
                $p1 = floor($m[0]['Cantidad']*0.1);//se multiplica * 0.1 la cantidad de la consulta aux2 ejemplo 64 = 6.4 lo aproxima al mas cercano que es 6
                $p9 = floor($m[0]['Cantidad']*0.9);//se multiplica * 0.9 la cantidad de la consulta aux2 ejemplo 64 = 57.6 lo aproxima al mas cercano que es 57
                //dd($p1+$p9);
                if ($p1>0 and $p9>0) {
                    $vaux = $valoresxx[$f];
                    $p10 = $vaux[$p1];
                    $p90 = $vaux[$p9];

                    array_push($pst, $p10);
                    array_push($pst, $p90);
                    array_push($ps, $pst);
                }else{
                    $p10 = 0.6;
                    $p90 = 6;

            //dd($valoresxx);
                    array_push($pst, $p10);
                    array_push($pst, $p90);
                    array_push($ps, $pst);
                }


                $f++;


        }


        $data = array('Data'=>$mpdatos, 'Ultimos'=>$ultimos, 'Pes'=>$ps, 'Weights' => $weights);
        return Response::json($data);

    });


    Route::get('pozosFreq',function()
    {
        $pozo = Input::get('pozo');
        $for = Input::get('formacion');
        $sp = Input::get('sp');

        $chart = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select(DB::raw('count(*) as Freq'),'mediciones.valor as valorchart')
        ->where('mediciones.subparametro_id','=',$sp)
        ->where('p.id',$pozo)
        ->groupBy('mediciones.valor')
        ->get();

        $general = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->distinct()
        ->select(DB::raw('MAX(mediciones.valor) as Maximo'), DB::raw('MIN(mediciones.valor) as Minimo'),
            DB::raw('AVG(mediciones.valor) as Media'),DB::raw('STD(mediciones.valor) as SD') , DB::raw('count(distinct(mediciones.valor)) as pb'))
        ->where('mediciones.subparametro_id','=',$sp)
        ->where('mediciones.pozo_id','=',$pozo)
        ->get();

        $datos = array('Chart'=>$chart,'General'=>$general);

        return Response::json($datos);
    });





    #Nueva
    Route::get('mecan_dano', function()
    {
        $datos = App\mecanismos_dano::all();
        return Response::json($datos);
    });


    #Nueva
    Route::get('variables_dano', function()
    {
        $mec = Input::get('mec');
        $datos = App\variable_dano::where('mecdan_id','=',$mec)
        ->get();

        return Response::json($datos);
    });


    #Nueva
    Route::get('config_dano', function(){

        $mec = Input::get('mec');
        $config = App\configuracion_dano::where('mecdan_id','=',$mec)
        ->get();

        return Response::json($config);

    });

    #Nueva
    Route::get('pozosvd', function(){


        $vardan = Input::get('vardan');
        $campo = Input::get('campo');
        $formacion = Input::get('formacion');
        $coors = [];
        $coorsc = [];
        $gencampos = [];
        $pozosc = [];
        $i=0;

        $unidad = App\variable_dano::where('nombre',$vardan)->get();

        $centro = App\coordenada_campo::select(DB::raw('avg(lat) as lat, avg(lon) as lon'))
        ->wherein('campo_id',$campo)
        ->get();

        #pozos-avg

         $pozosavg = App\variable_dano_medicion::join('pozos AS p','vd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('variables_dano as vd','vd.id','=','vd_mediciones.vd_id')
        ->select('c.nombre as Cnombre','vd_mediciones.Comentario as comentario','vd_mediciones.fecha','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', DB::raw('AVG(vd_mediciones.valor) as valor') )
        ->where('vd.nombre','=',$vardan)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

         $pozosmin = App\variable_dano_medicion::join('pozos AS p','vd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('variables_dano as vd','vd.id','=','vd_mediciones.vd_id')
        ->select('c.nombre as Cnombre','vd_mediciones.Comentario as comentario','vd_mediciones.fecha','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', DB::raw('MIN(vd_mediciones.valor) as valor') )
        ->where('vd.nombre','=',$vardan)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

         $pozosmax = App\variable_dano_medicion::join('pozos AS p','vd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('variables_dano as vd','vd.id','=','vd_mediciones.vd_id')
        ->select('c.nombre as Cnombre','vd_mediciones.Comentario as comentario','vd_mediciones.fecha','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', DB::raw('MAX(vd_mediciones.valor) as valor') )
        ->where('vd.nombre','=',$vardan)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozos = App\variable_dano_medicion::join('pozos AS p','vd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('variables_dano as vd','vd.id','=','vd_mediciones.vd_id')
        ->select('c.nombre as Cnombre','vd_mediciones.Comentario as comentario','vd_mediciones.fecha','p.Id AS Id','p.nombre as nombre','p.lat as lat', 'p.lon as lon', 'vd_mediciones.valor as valor')
        ->where('vd.nombre','=',$vardan)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

         $aux = App\variable_dano_medicion::join('pozos AS p','vd_mediciones.pozo_id','=','p.Id')
        ->select('p.Id AS Id')
        ->where('vd_mediciones.vd_id','=',$vardan)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozos2 = App\pozo::join('campos as c', 'c.id','=','pozos.campo_id')
        ->select('c.nombre as Cnombre','pozos.*')
        ->wherenotin('pozos.Id',$aux)
        ->wherein('pozos.campo_id',$campo)
        ->get();

        $general = App\variable_dano_medicion::join('pozos AS p','vd_mediciones.pozo_id','=','p.Id')
        ->join('variables_dano as vd','vd.id','=','vd_mediciones.vd_id')
        ->distinct()
        ->select(DB::raw('MAX(vd_mediciones.valor) as Maximo'), DB::raw('MIN(vd_mediciones.valor) as Minimo'),
            DB::raw('AVG(vd_mediciones.valor) as Media'),DB::raw('STD(vd_mediciones.valor) as SD') , DB::raw('count(distinct(p.nombre)) as pb'))
        ->where('vd.nombre','=',$vardan)
        ->wherein('p.campo_id',$campo)
        ->get();

        $general2 = App\pozo::wherenotin('Id',$aux)
        ->select(DB::raw('count(distinct(pozos.nombre)) as pm'))
        ->wherein('pozos.campo_id',$campo)
        ->get();

        $chart = App\variable_dano_medicion::join('pozos AS p','vd_mediciones.pozo_id','=','p.Id')
        ->join('variables_dano as vd','vd.id','=','vd_mediciones.vd_id')
        ->select(DB::raw('count(*) as Freq'),'vd_mediciones.valor as valorchart')
        ->where('vd.nombre','=',$vardan)
        ->wherein('p.campo_id',$campo)
        ->groupBy('vd_mediciones.valor')
        ->get();


        foreach($campo as $c)
        {

        $coordenadasc = App\coordenada_campo::where('campo_id','=',$c)
        ->orderBy('Orden')
        ->get();

        $coordenadas = App\coordenada_formacion::where('campo_id','=',$c)
        ->where('formacion_id','=',$formacion)
        ->orderBy('Orden')
        ->get();

        $gencampo = App\variable_dano_medicion::join('pozos as p', 'vd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.campo_id')
        ->join('variables_dano as vd','vd.id','=','vd_mediciones.vd_id')
        ->select(DB::raw('avg(vd_mediciones.valor) as avg'),DB::raw('min(vd_mediciones.valor) as min'),DB::raw('max(vd_mediciones.valor) as max'),DB::raw('std(vd_mediciones.valor) as sd'),DB::raw('count(distinct(p.Id)) as count'),'c.nombre as cnombre')
        ->where('p.campo_id','=',$c)
        ->where('vd.nombre','=',$vardan)
        ->get();

        $pozoscampos = App\pozo::select(DB::raw('count(*) as count'))
        ->where('campo_id','=',$c)
        ->get();

        array_push($gencampos,$gencampo);
        array_push($coors, $coordenadas);
        array_push($coorsc, $coordenadasc);
        array_push($pozosc,$pozoscampos);

        
        }

        $data = array('PozosC'=>$pozosc,'Pozosmin'=>$pozosmin, 'Pozosmax'=>$pozosmax,'Pozosavg'=> $pozosavg, 'Coordenadasc'=>$coorsc, 'Gencampos'=>$gencampos ,'Coordenadas'=>$coors,'Pozos'=>$pozos,'Pozos2'=>$pozos2, 'General'=>$general,'Chart'=>$chart,'General2'=>$general2, 'Centro'=>$centro,'Unidad'=>$unidad);
        return Response::json($data);

    });

    #Nueva
    Route::get('configdano', function()
    {

        $varcon = Input::get('varcon');
        $campo = Input::get('campo');
        $formacion = Input::get('formacion');
        $coors = [];
        $coorsc = [];
        $gencampos = [];
        $pozosc = [];
        $i=0;

        $unidad = App\configuracion_dano::where('id',$varcon)->get();

        $centro = App\coordenada_campo::select(DB::raw('avg(lat) as lat, avg(lon) as lon'))
        ->wherein('campo_id',$campo)
        ->get();

        #pozos-avg

         $pozosavg = App\configuracion_dano_medicion::join('pozos AS p','cd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('Configuracion_dano as cd','cd.id','=','cd_mediciones.cd_id')
        ->select('c.nombre as Cnombre','cd_mediciones.Comentario as Comentario','cd_mediciones.fecha','p.Id AS Id','p.nombre AS nombre','p.lat AS lat', 'p.lon AS lon', DB::raw('AVG(cd_mediciones.valor) as valor') )
        ->where('cd.id','=',$varcon)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

         $pozosmin = App\configuracion_dano_medicion::join('pozos AS p','cd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('Configuracion_dano as cd','cd.id','=','cd_mediciones.cd_id')
        ->select('c.nombre as Cnombre','cd_mediciones.Comentario as Comentario','cd_mediciones.fecha','p.Id AS Id','p.nombre AS nombre','p.lat AS lat', 'p.lon AS lon', DB::raw('MIN(cd_mediciones.valor) as valor') )
        ->where('cd.id','=',$varcon)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

         $pozosmax = App\configuracion_dano_medicion::join('pozos AS p','cd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('Configuracion_dano as cd','cd.id','=','cd_mediciones.cd_id')
        ->select('c.nombre as Cnombre','cd_mediciones.Comentario as Comentario','cd_mediciones.fecha','p.Id AS Id','p.nombre AS nombre','p.lat AS lat', 'p.lon AS lon', DB::raw('MAX(cd_mediciones.valor) as valor') )
        ->where('cd.id','=',$varcon)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozos = App\configuracion_dano_medicion::join('pozos AS p','cd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.Campo_id')
        ->join('configuracion_dano as cd','cd.id','=','cd_mediciones.cd_id')
        ->select('c.nombre as Cnombre','cd_mediciones.Comentario as Comentario','cd_mediciones.fecha','p.Id AS Id','p.nombre AS nombre','p.lat AS lat', 'p.lon AS lon', 'cd_mediciones.valor as valor')
        ->where('cd.id','=',$varcon)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

         $aux = App\configuracion_dano_medicion::join('pozos AS p','cd_mediciones.pozo_id','=','p.Id')
        ->select('p.Id AS Id')
        ->where('cd_mediciones.cd_id','=',$varcon)
        ->wherein('p.campo_id',$campo)
        ->groupBy('p.Id')
        ->get();

        $pozos2 = App\pozo::join('campos as c', 'c.id','=','pozos.campo_id')
        ->select('c.nombre as Cnombre','pozos.*')
        ->wherenotin('pozos.Id',$aux)
        ->wherein('pozos.campo_id',$campo)
        ->get();

        $general = App\configuracion_dano_medicion::join('pozos AS p','cd_mediciones.pozo_id','=','p.Id')
        ->join('configuracion_dano as cd','cd.id','=','cd_mediciones.cd_id')
        ->distinct()
        ->select(DB::raw('MAX(cd_mediciones.valor) as Maximo'), DB::raw('MIN(cd_mediciones.valor) as Minimo'),
            DB::raw('AVG(cd_mediciones.valor) as Media'),DB::raw('STD(cd_mediciones.valor) as SD') , DB::raw('count(distinct(p.nombre)) as pb'))
        ->where('cd.id','=',$varcon)
        ->wherein('p.campo_id',$campo)
        ->get();

        $general2 = App\pozo::wherenotin('Id',$aux)
        ->select(DB::raw('count(distinct(pozos.nombre)) as pm'))
        ->wherein('pozos.campo_id',$campo)
        ->get();

        $chart = App\configuracion_dano_medicion::join('pozos AS p','cd_mediciones.pozo_id','=','p.Id')
        ->join('configuracion_dano as cd','cd.id','=','cd_mediciones.cd_id')
        ->select(DB::raw('count(*) as Freq'),'cd_mediciones.valor AS valorchart')
        ->where('cd.id','=',$varcon)
        ->wherein('p.campo_id',$campo)
        ->groupBy('cd_mediciones.valor')
        ->get();


        foreach($campo as $c)
        {

        $coordenadasc = App\coordenada_campo::where('campo_id','=',$c)
        ->orderBy('Orden')
        ->get();

        $coordenadas = App\coordenada_formacion::where('campo_id','=',$c)
        ->where('formacion_id','=',$formacion)
        ->orderBy('Orden')
        ->get();

        $gencampo = App\configuracion_dano_medicion::join('pozos as p', 'cd_mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.campo_id')
        ->join('configuracion_dano as cd','cd.id','=','cd_mediciones.cd_id')
        ->select(DB::raw('avg(cd_mediciones.valor) as avg'),DB::raw('min(cd_mediciones.valor) as min'),DB::raw('max(cd_mediciones.valor) as max'),DB::raw('std(cd_mediciones.valor) as sd'),DB::raw('count(distinct(p.Id)) as count'),'c.nombre as cnombre')
        ->where('p.campo_id','=',$c)
        ->where('cd.id','=',$varcon)
        ->get();

        $pozoscampos = App\pozo::select(DB::raw('count(*) as count'))
        ->where('campo_id','=',$c)
        ->get();

        array_push($gencampos,$gencampo);
        array_push($coors, $coordenadas);
        array_push($coorsc, $coordenadasc);
        array_push($pozosc,$pozoscampos);

        
        }

        $data = array('PozosC'=>$pozosc,'Pozosmin'=>$pozosmin, 'Pozosmax'=>$pozosmax,'Pozosavg'=> $pozosavg, 'Coordenadasc'=>$coorsc, 'Gencampos'=>$gencampos ,'Coordenadas'=>$coors,'Pozos'=>$pozos,'Pozos2'=>$pozos2, 'General'=>$general,'Chart'=>$chart,'General2'=>$general2, 'Centro'=>$centro,'Unidad'=>$unidad);
        return Response::json($data);


    });

    //Geor desde botones SP
    Route::get('datos_geor', function()
    {
        $multi = Input::get('multi');
        $sup = Input::get('subp');

        $colombia = null;

        $datos = App\Models\MultiparametricAnalysis\Statistical::select('statistical', 'basin_statistical', 'field_statistical')
            ->where('id', $multi)
            ->get();

        if ($datos[0]->statistical !== null) {
            $colombia = App\campo::select('id')
                ->get();
        }

        $sp = App\subparametro::select('nombre')
            ->where('id', $sup)
            ->get();

        $data = Array('datos' => $datos, 'sp' => $sp, 'colombia' => $colombia);
        return Response::json($data);
    });

    Route::get('fieldview', function()
    {

        $campos = Input::get('campos');
        $parametro = Input::get('sp');
        $formacion = Input::get('formacion');

        $coor = [];
        $gencampos = [];

        $general = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->distinct()
        ->select(DB::raw('MAX(mediciones.valor) as Maximo'), DB::raw('MIN(mediciones.valor) as Minimo'),
            DB::raw('AVG(mediciones.valor) as Media'),DB::raw('STD(mediciones.valor) as SD') , DB::raw('count(distinct(p.nombre)) as pb'))
        ->where('mediciones.subparametro_id','=',$parametro)
        ->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campos)
        ->get();

        $aux = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select('p.Id AS Id')
        ->where('mediciones.subparametro_id','=',$parametro)
        ->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campos)
        ->groupBy('p.Id')
        ->get();

        $general2 = App\pozo::wherenotin('Id',$aux)
        ->select(DB::raw('count(distinct(pozos.nombre)) as pm'))
        ->wherein('pozos.campo_id',$campos)
        ->get();

        $chart = App\medicion::join('pozos AS p','mediciones.pozo_id','=','p.Id')
        ->select(DB::raw('count(*) as Freq'),'mediciones.valor as valorchart')
        ->where('mediciones.subparametro_id','=',$parametro)
        ->where('mediciones.formacion_id','=',$formacion)
        ->wherein('p.campo_id',$campos)
        ->groupBy('mediciones.valor')
        ->get();
        foreach($campos as $c)
        {

        $coordenadasc = App\coordenada_campo::where('campo_id','=',$c)
        ->orderBy('Orden')
        ->get();


        $gencampo = App\medicion::join('pozos as p', 'mediciones.pozo_id','=','p.Id')
        ->join('campos as c','c.id','=','p.campo_id')
        ->select(DB::raw('avg(mediciones.valor) as avg'),DB::raw('std(mediciones.valor) as sd'),DB::raw('count(distinct(p.Id)) as count'),'c.nombre as cnombre')
        ->where('p.campo_id','=',$c)
        ->where('mediciones.subparametro_id','=',$parametro)
        ->get();

        array_push($gencampos,$gencampo);
        array_push($coor, $coordenadasc);

        
        }

        $datos = array('Coordenadasc'=>$coor, 'Gencampos'=>$gencampos ,'General'=>$general,'General2'=>$general2, 'Chart'=>$chart, 'Centro'=>$centro);
        return Response::json($datos);
    });

});


#****////****////****////


?>













