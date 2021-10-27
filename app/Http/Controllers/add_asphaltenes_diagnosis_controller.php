<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\cuenca;
use View;
use App\asphaltenes_d_diagnosis;
use App\escenario;
use App\asphaltenes_d_diagnosis_pvt;//se guardan los pvt
use App\asphaltenes_d_diagnosis_historical_data;//historical data
use App\asphaltenes_d_diagnosis_historical_projection_data;//historical projection data
use App\asphaltenes_d_diagnosis_soluble_asphaltenes;//asphaltenes data
use App\asphaltenes_d_diagnosis_results;
use App\asphaltenes_d_diagnosis_results_skin;
use App\Http\Requests\asphaltene_diagnosis_request;
use \SplFixedArray;
use App\error_log;
use App\User;
use App\company;
use App\proyecto;
use App\pozo;
use Redirect;


class add_asphaltenes_diagnosis_controller extends Controller
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

            return View::make('add_asphaltenes_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor']));
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
    public function store(asphaltene_diagnosis_request $request)
    {

        $button_wr = (bool) isset($_POST['button_wr']);

        $scenaryId = \Request::get('scenaryId');
        $scenary = DB::table('escenarios')->where('id', $scenaryId)->first();

        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();

        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        /* Saber si el escenario se comrrio por lo menos una vez */
        $scenary = escenario::find($scenary->id);
        $scenary->estado = 1;
        $scenary->asphaltene_type = "Asphaltene diagnosis";
        $scenary->save();

        $asphaltenes_d_diagnosis = new asphaltenes_d_diagnosis;
        $asphaltenes_d_diagnosis->scenario_id = $scenary->id;
        $asphaltenes_d_diagnosis->drainage_radius = $request->input('drainage_radius');
        $asphaltenes_d_diagnosis->net_pay = $request->input('net_pay');
        $asphaltenes_d_diagnosis->wellbore_radius = $request->input('wellbore_radius');
        $asphaltenes_d_diagnosis->current_pressure = $request->input('current_pressure');
        $asphaltenes_d_diagnosis->initial_pressure = $request->input('initial_pressure');
        $asphaltenes_d_diagnosis->initial_porosity = $request->input('initial_porosity');
        $asphaltenes_d_diagnosis->initial_permeability = $request->input('initial_permeability');
        $asphaltenes_d_diagnosis->average_pore_diameter = $request->input('average_pore_diameter');
        $asphaltenes_d_diagnosis->asphaltene_particle_diameter = $request->input('asphaltene_particle_diameter');
        $asphaltenes_d_diagnosis->asphaltene_apparent_density = $request->input('asphaltene_apparent_density');
        $asphaltenes_d_diagnosis->final_date = date("Y/m/d", strtotime($request->input('final_date')));
        $asphaltenes_d_diagnosis->perform_historical_projection = $request->input('perform_historical_projection_oil');

        $asphaltenes_d_diagnosis->status_wr = $button_wr;

        $asphaltenes_d_diagnosis->save();

        $value_pvt_table = json_decode($request->input("value_pvt_table"));
        $value_pvt_table = is_null($value_pvt_table) ? [] : $value_pvt_table;

        /* Arreglos para guardar los datos organizados - módulo de cálculo */
        $pressure_data = [];
        $density_data = [];
        $oil_viscosity_data = [];
        $oil_formation_volume_factor_data = [];
        foreach ($value_pvt_table as $value) {
            $asphaltenes_d_diagnosis_pvt = new asphaltenes_d_diagnosis_pvt;
            $asphaltenes_d_diagnosis_pvt->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
            $asphaltenes_d_diagnosis_pvt->pressure = str_replace(",", ".", $value[0]);
            $asphaltenes_d_diagnosis_pvt->density = str_replace(",", ".", $value[1]);
            $asphaltenes_d_diagnosis_pvt->oil_viscosity = str_replace(",", ".", $value[2]);
            $asphaltenes_d_diagnosis_pvt->oil_formation_volume_factor = str_replace(",", ".", $value[3]);
            $asphaltenes_d_diagnosis_pvt->save();

            array_push($pressure_data, $asphaltenes_d_diagnosis_pvt->pressure);
            array_push($density_data, $asphaltenes_d_diagnosis_pvt->density);
            array_push($oil_viscosity_data, $asphaltenes_d_diagnosis_pvt->oil_viscosity);
            array_push($oil_formation_volume_factor_data, $asphaltenes_d_diagnosis_pvt->oil_formation_volume_factor);
        }


        $value_historical_table = json_decode($request->input("value_historical_table"));
        $value_historical_table = is_null($value_historical_table) ? [] : $value_historical_table;

        /* Arreglos para guardar los datos organizados - módulo de cálculo */
        $dates_data = [];
        $bopd_data = [];
        $asphaltenes_wt_data = [];
        foreach ($value_historical_table as $value) {
            $asphaltenes_d_diagnosis_historical_data = new asphaltenes_d_diagnosis_historical_data;
            $asphaltenes_d_diagnosis_historical_data->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
            $asphaltenes_d_diagnosis_historical_data->date = date("Y/m/d", strtotime($value[0]));
            $asphaltenes_d_diagnosis_historical_data->bopd = str_replace(",", ".", $value[1]);
            $asphaltenes_d_diagnosis_historical_data->asphaltenes = str_replace(",", ".", $value[2]);
            $asphaltenes_d_diagnosis_historical_data->save();

            array_push($dates_data, $value[0]);
            array_push($bopd_data, $asphaltenes_d_diagnosis_historical_data->bopd);
            array_push($asphaltenes_wt_data, $asphaltenes_d_diagnosis_historical_data->asphaltenes);
        }


        if ( $request->input('perform_historical_projection_oil') != 'without' ) {
            $value_historical_projection_table = json_decode($request->input("value_historical_projection_data"));
            $value_historical_projection_table = is_null($value_historical_projection_table) ? [] : $value_historical_projection_table;

            /* Arreglos para guardar los datos organizados - módulo de cálculo */
            foreach ($value_historical_projection_table as $value) {
                $asphaltenes_d_diagnosis_historical_projection_data = new asphaltenes_d_diagnosis_historical_projection_data;
                $asphaltenes_d_diagnosis_historical_projection_data->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
                $asphaltenes_d_diagnosis_historical_projection_data->date = date("Y/m/d", strtotime($value[0]));
                $asphaltenes_d_diagnosis_historical_projection_data->bopd = str_replace(",", ".", $value[1]);
                $asphaltenes_d_diagnosis_historical_projection_data->asphaltenes = str_replace(",", ".", $value[2]);
                $asphaltenes_d_diagnosis_historical_projection_data->save();

                array_push($dates_data, $value[0]);
                array_push($bopd_data, $asphaltenes_d_diagnosis_historical_projection_data->bopd);
                array_push($asphaltenes_wt_data, $asphaltenes_d_diagnosis_historical_projection_data->asphaltenes);
            }
        }


        $value_asphaltenes_table = json_decode($request->input("value_asphaltenes_table"));
        $value_asphaltenes_table = is_null($value_asphaltenes_table) ? [] : $value_asphaltenes_table;

        /* Arreglos para guardar los datos organizados - módulo de cálculo */
        $asphaltenes_pressure_data = [];
        $asphaltenes_soluble_fraction = [];
        foreach ($value_asphaltenes_table as $value) {
            $asphaltenes_d_diagnosis_soluble_asphaltenes = new asphaltenes_d_diagnosis_soluble_asphaltenes;
            $asphaltenes_d_diagnosis_soluble_asphaltenes->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
            $asphaltenes_d_diagnosis_soluble_asphaltenes->pressure = str_replace(",", ".", $value[0]);
            $asphaltenes_d_diagnosis_soluble_asphaltenes->asphaltene_soluble_fraction = str_replace(",", ".", $value[1]);
            $asphaltenes_d_diagnosis_soluble_asphaltenes->save();

            array_push($asphaltenes_pressure_data, $asphaltenes_d_diagnosis_soluble_asphaltenes->pressure);
            array_push($asphaltenes_soluble_fraction, $asphaltenes_d_diagnosis_soluble_asphaltenes->asphaltene_soluble_fraction);
        }

        /*Escenario completo*/
        $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();
        $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $scenary->id)->first();
        $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->first();

        if ($asphaltenes_d_stability_analysis && $asphaltenes_d_diagnosis && $asphaltenes_d_precipitated_analysis && !$asphaltenes_d_stability_analysis->status_wr && !$asphaltenes_d_diagnosis->status_wr && $asphaltenes_d_precipitated_analysis->status_wr) {
            $scenary = escenario::find($scenary->id);
            $scenary->completo = 0;
            $scenary->save();
        } else {
            $scenary = escenario::find($scenary->id);
            $scenary->completo = 1;
            $scenary->save();
        }

        $pvt_data = [$pressure_data, $density_data, $oil_viscosity_data, $oil_formation_volume_factor_data];

        /* Históricos */
        /*
        $date_raw = [1 => "15/01/2010","15/02/2010","15/03/2010","15/04/2010","15/05/2010","15/06/2010","15/07/2010","15/08/2010","15/09/2010","15/10/2010","15/11/2010","15/12/2010","15/01/2011","15/02/2011","15/03/2011","15/04/2011","15/05/2011","15/06/2011","15/07/2011","15/08/2011","15/09/2011","15/10/2011","15/11/2011","15/12/2011","15/01/2012","15/02/2012","15/03/2012","15/04/2012","15/05/2012","15/06/2012","15/07/2012","15/08/2012","15/09/2012","15/10/2012","15/11/2012","15/12/2012","15/01/2013","15/02/2013","15/03/2013","15/04/2013","15/05/2013","15/06/2013","15/07/2013","15/08/2013","15/09/2013","15/10/2013","15/11/2013","15/12/2013","15/01/2014","15/02/2014","15/03/2014","15/04/2014","15/05/2014","15/06/2014","15/07/2014","15/08/2014","15/09/2014","15/10/2014","15/11/2014","15/12/2014","15/01/2015","15/02/2015","15/03/2015","15/04/2015"];
        
        foreach ($date_raw as $key => $value) 
        {
            $date_split = explode("/", $value);
            $dates[$key] = $date_split[0]."-".$date_split[1]."-".$date_split[2];
        }

        $bopd = [1 => 839,110,874,961,957,946,932,922,867,1027,1012,1016,644,933,887,774,864,809,784,772,726,644,627,580,573,520,499,543,537,198,640,557,515,202,504,78,237,332,370,396,415,429,450,295,318,484,466,427,431,422,408,397,405,404,405,405,250,187,272,265,285,325,285,292];
        $wt_percentage = [1=>2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2];
        #$historical_data = [$dates, $bopd, $wt_percentage];
        */

        $historical_data = [$dates_data, $bopd_data, $asphaltenes_wt_data];

        /* Asfaltenos */
        /*
        $asphaltenes_pressure = [1 => 147,294,441,588,735,882,1029,1176,1323,1470,1617,1764,1911,2058,2205,2352,2499,2646,2793,2940,3087,3234,3381,3528,3675,3822,3969,4116,4263,4410,4557,4704,4851,4998,5145,5292,5439,5586,5733,5880,6027,6174,6321,6468,6615,6762,6909,7056,7203,7350,7497,7644,7791,7938];
        $sol = [1 => 0.9585,0.95682,0.95514,0.95342,0.95163,0.94977,0.94783,0.94578,0.94363,0.94136,0.93896,0.93641,0.93372,0.93086,0.92782,0.92459,0.92114,0.91747,0.91354,0.90934,0.90485,0.90004,0.89487,0.88931,0.89185,0.89399,0.89606,0.89804,0.89995,0.9018,0.90357,0.90528,0.90693,0.90851,0.91005,0.91152,0.91295,0.91433,0.91566,0.91621,0.91818,0.91938,0.92054,0.92607,0.92694,0.92779,0.92862,0.92943,0.93022,0.93099,0.93174,0.93247,0.93009,0.93094];
        #$asphaltenes_data = [$asphaltenes_pressure, $sol];
        */

        $asphaltenes_data = [$asphaltenes_pressure_data, $asphaltenes_soluble_fraction];

        $drainage_radius = $asphaltenes_d_diagnosis->drainage_radius; #rdre
        $formation_height = $asphaltenes_d_diagnosis->net_pay; #hf
        $well_radius = $asphaltenes_d_diagnosis->wellbore_radius; #rw ?
        $current_pressure = $asphaltenes_d_diagnosis->current_pressure; #pact ?
        $reservoir_initial_pressure = $asphaltenes_d_diagnosis->initial_pressure; #pini ?
        $reservoir_initial_porosity = $asphaltenes_d_diagnosis->initial_porosity; #phio ?
        $reservoir_initial_permeability = $asphaltenes_d_diagnosis->initial_permeability; #ko ? 
        $pore_throat_diameter = $asphaltenes_d_diagnosis->average_pore_diameter; #dporo ?
        $asphaltene_particle_diameter = $asphaltenes_d_diagnosis->asphaltene_particle_diameter; #dpart ?
        $agregated_asphaltenes_density = $asphaltenes_d_diagnosis->asphaltene_apparent_density; #rhop ?

        try {
            if (!$button_wr) {
                $simulation_results = $this->simulate_deposited_asphaltenes($drainage_radius, $formation_height, $well_radius, $current_pressure, $reservoir_initial_pressure, $reservoir_initial_porosity, $reservoir_initial_permeability, $pore_throat_diameter, $asphaltene_particle_diameter, $agregated_asphaltenes_density, $pvt_data, $historical_data, $asphaltenes_data);

                if ($simulation_results[0] == false) {
                    $asphaltenes_d_diagnosis->status_wr = true;
                    $asphaltenes_d_diagnosis->save();
                    $scenary = escenario::find($scenary->id);
                    $scenary->completo = 0;
                    $scenary->save();
                    return redirect('/asphaltenesDiagnosis/'.$scenaryId.'/edit')->withErrors($simulation_results[1]);
                }

                if ($simulation_results == 'viscosity_error') {
                    $viscosity_error = true;
                    return View::make('results_asphaltenes_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_diagnosis', 'dates_data', 'asphaltenes_d_diagnosis', 'viscosity_error']));
                } else { 
                    $viscosity_error = false;
                }

                /* Guardando resultados */
                $properties_results = $simulation_results[0];
                $skin_results = $simulation_results[1];

                /* Optimizando consultas */
                foreach ($skin_results as $key => $value) {
                    $asphaltenes_d_diagnosis_results_inserts = [];
                    $asphaltenes_d_diagnosis_results_skin_inserts = [];

                    array_push($asphaltenes_d_diagnosis_results_skin_inserts, array('asphaltenes_d_diagnosis_id' => $asphaltenes_d_diagnosis->id, 'date' => $value[0], 'damage_radius' => round($value[1], 3), 'skin' => round($value[2], 3)));
                    $properties_value = $properties_results[$key - 1];

                    //array_shift($properties_value);
                    //array_pop($properties_value);

                    foreach ($properties_value as $value_aux) {
                        array_push($asphaltenes_d_diagnosis_results_inserts, array('asphaltenes_d_diagnosis_id' => $asphaltenes_d_diagnosis->id, 'radius' => round($value_aux[0], 3), 'pressure' => round($value_aux[1], 7), 'porosity' => round($value_aux[2], 7), 'permeability' => round($value_aux[3], 7), 'deposited_asphaltenes' => round($value_aux[4], 7), 'soluble_asphaltenes' => round($value_aux[5], 7), 'date' => $value[0], 'viscosity_error' => $viscosity_error));
                    }

                    DB::table('asphaltenes_d_diagnosis_results')->insert($asphaltenes_d_diagnosis_results_inserts);
                    DB::table('asphaltenes_d_diagnosis_results_skin')->insert($asphaltenes_d_diagnosis_results_skin_inserts);
                }
            }


            /*
            foreach ($skin_results as $key => $value) 
            {
                $asphaltenes_d_diagnosis_results_skin = new asphaltenes_d_diagnosis_results_skin;
                $asphaltenes_d_diagnosis_results_skin->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
                $asphaltenes_d_diagnosis_results_skin->date = $value[0];
                $asphaltenes_d_diagnosis_results_skin->damage_radius = $value[1];
                $asphaltenes_d_diagnosis_results_skin->skin = $value[2];
                $asphaltenes_d_diagnosis_results_skin->save();

                $properties_value = $properties_results[$key - 1];

                foreach ($properties_value as $value_aux) 
                {
                    $asphaltenes_d_diagnosis_results = new asphaltenes_d_diagnosis_results;
                    $asphaltenes_d_diagnosis_results->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
                    $asphaltenes_d_diagnosis_results->radius = $value_aux[0];
                    $asphaltenes_d_diagnosis_results->pressure = $value_aux[1];
                    $asphaltenes_d_diagnosis_results->porosity = $value_aux[2];
                    $asphaltenes_d_diagnosis_results->permeability = $value_aux[3];
                    $asphaltenes_d_diagnosis_results->deposited_asphaltenes = $value_aux[4];
                    $asphaltenes_d_diagnosis_results->soluble_asphaltenes = $value_aux[5];
                    $asphaltenes_d_diagnosis_results->date = $value[0];
                    $asphaltenes_d_diagnosis_results->save();
                }
            }
            */

            /*Escenario completo*/
            $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();
            $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $scenary->id)->first();
            $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->first();

            if ($asphaltenes_d_stability_analysis && $asphaltenes_d_diagnosis && $asphaltenes_d_precipitated_analysis && !$asphaltenes_d_stability_analysis->status_wr && !$asphaltenes_d_diagnosis->status_wr && !$asphaltenes_d_precipitated_analysis->status_wr) {
                $scenary = escenario::find($scenary->id);
                $scenary->completo = 1;
                $scenary->save();
            } else {
                $scenary = escenario::find($scenary->id);
                $scenary->completo = 0;
                $scenary->save();
            }

            if (!isset($viscosity_error)) {
                $viscosity_error = 0;
            }

            return View::make('results_asphaltenes_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'dates_data', 'asphaltenes_d_diagnosis', 'viscosity_error']));
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        #Mostrar el modulo desde los resultados de los demas modulos de asfaltenos. Agregar o editar segun el caso.
        $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $id)->first();

        if ($asphaltenes_d_diagnosis) {
            return \Redirect::route('asphaltenesDiagnosis.edit', $id);
        } else {
            return \Redirect::action('add_asphaltenes_diagnosis_controller@index', array('scenaryId' => $id));
        }
    }

    public function result($scenaryId)
    {
        $asphaltenes_d_diagnosis = asphaltenes_d_diagnosis::where('scenario_id', $scenaryId)->first();
        $scenary = DB::table('escenarios')->where('id', $scenaryId)->first();
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $viscosity_error = DB::table('asphaltenes_d_diagnosis_results')->where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->first();
        if ($viscosity_error == null) {
            $viscosity_error = false;
        } else {
            $viscosity_error = $viscosity_error->viscosity_error;
        }

        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        $asphaltenes_diagnosis_historical_data = asphaltenes_d_diagnosis_historical_data::where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->get();

        /* Arreglos para guardar los datos organizados - módulo de cálculo */
        $dates_data = [];
        $bopd_data = [];
        $asphaltenes_wt_data = [];
        foreach ($asphaltenes_diagnosis_historical_data as $value) {
            array_push($dates_data, $value->date);
        }

        return View::make('results_asphaltenes_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_diagnosis', 'dates_data', 'viscosity_error']));
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
        $asphaltenes_d_diagnosis = asphaltenes_d_diagnosis::where('scenario_id', '=', $id)->first();

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

        return View::make('edit_asphaltenes_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_diagnosis','duplicateFrom']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(asphaltene_diagnosis_request $request, $id)
    {    

        $button_wr = (bool) isset($_POST['button_wr']);
        
        if (isset($_SESSION['scenary_id_dup'])) {
            $asphaltenes_d_diagnosis = new asphaltenes_d_diagnosis();
        } else {
            $asphaltenes_d_diagnosis = asphaltenes_d_diagnosis::find($id);
            if (!$asphaltenes_d_diagnosis) {
                $asphaltenes_d_diagnosis = new asphaltenes_d_diagnosis();
            }
        }

        // $asphaltenes_d_diagnosis = asphaltenes_d_diagnosis::find($id);
        // if (!$asphaltenes_d_diagnosis) {
        //     $asphaltenes_d_diagnosis = new asphaltenes_d_diagnosis();
        // }

        // if (isset($_SESSION['scenary_id_dup'])) {
        //     $asphaltenes_d_diagnosis = new asphaltenes_d_diagnosis();
        // } else {
        //     $asphaltenes_d_diagnosis = asphaltenes_d_diagnosis::where("id", "=", $id)->first();
        // }

        $scenaryId = $request->id_scenary;
        $scenary = escenario::find($scenaryId);
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();

        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id', '=', $scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        $scenary->estado = 1;
        $scenary->asphaltene_type = "Asphaltene diagnosis";
        $scenary->save();

        $asphaltenes_d_diagnosis->scenario_id = $scenary->id;
        $asphaltenes_d_diagnosis->status_wr = $button_wr;

        $asphaltenes_d_diagnosis->drainage_radius = $request->input('drainage_radius');
        $asphaltenes_d_diagnosis->net_pay = $request->input('net_pay');
        $asphaltenes_d_diagnosis->wellbore_radius = $request->input('wellbore_radius');
        $asphaltenes_d_diagnosis->current_pressure = $request->input('current_pressure');
        $asphaltenes_d_diagnosis->initial_pressure = $request->input('initial_pressure');
        $asphaltenes_d_diagnosis->initial_porosity = $request->input('initial_porosity');
        $asphaltenes_d_diagnosis->initial_permeability = $request->input('initial_permeability');
        $asphaltenes_d_diagnosis->average_pore_diameter = $request->input('average_pore_diameter');
        $asphaltenes_d_diagnosis->asphaltene_particle_diameter = $request->input('asphaltene_particle_diameter');
        $asphaltenes_d_diagnosis->asphaltene_apparent_density = $request->input('asphaltene_apparent_density');
        $asphaltenes_d_diagnosis->final_date = date("Y/m/d", strtotime($request->input('final_date')));
        $asphaltenes_d_diagnosis->perform_historical_projection = $request->input('perform_historical_projection_oil');
        $asphaltenes_d_diagnosis->save();

        asphaltenes_d_diagnosis_pvt::where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->delete();
        $value_pvt_table = json_decode($request->input("value_pvt_table"));
        $value_pvt_table = is_null($value_pvt_table) ? [] : $value_pvt_table;

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $pressure_data = [];
        $density_data = [];
        $oil_viscosity_data = [];
        $oil_formation_volume_factor_data = [];
        foreach ($value_pvt_table as $value) {
            $asphaltenes_d_diagnosis_pvt = new asphaltenes_d_diagnosis_pvt;
            $asphaltenes_d_diagnosis_pvt->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
            $asphaltenes_d_diagnosis_pvt->pressure = str_replace(",", ".", $value[0]);
            $asphaltenes_d_diagnosis_pvt->density = str_replace(",", ".", $value[1]);
            $asphaltenes_d_diagnosis_pvt->oil_viscosity = str_replace(",", ".", $value[2]);
            $asphaltenes_d_diagnosis_pvt->oil_formation_volume_factor = str_replace(",", ".", $value[3]);
            $asphaltenes_d_diagnosis_pvt->save();

            array_push($pressure_data, $asphaltenes_d_diagnosis_pvt->pressure);
            array_push($density_data, $asphaltenes_d_diagnosis_pvt->density);
            array_push($oil_viscosity_data, $asphaltenes_d_diagnosis_pvt->oil_viscosity);
            array_push($oil_formation_volume_factor_data, $asphaltenes_d_diagnosis_pvt->oil_formation_volume_factor);
        }

        asphaltenes_d_diagnosis_historical_data::where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->delete();
        $value_historical_table = json_decode($request->input("value_historical_table"));
        $value_historical_table = is_null($value_historical_table) ? [] : $value_historical_table;

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $dates_data = [];
        $bopd_data = [];
        $asphaltenes_wt_data = [];
        foreach ($value_historical_table as $value) {
            $asphaltenes_d_diagnosis_historical_data = new asphaltenes_d_diagnosis_historical_data;
            $asphaltenes_d_diagnosis_historical_data->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
            $asphaltenes_d_diagnosis_historical_data->date = date("Y/m/d", strtotime($value[0]));
            $asphaltenes_d_diagnosis_historical_data->bopd = str_replace(",", ".", $value[1]);
            $asphaltenes_d_diagnosis_historical_data->asphaltenes = str_replace(",", ".", $value[2]);
            $asphaltenes_d_diagnosis_historical_data->save();

            array_push($dates_data, $value[0]);
            array_push($bopd_data, $asphaltenes_d_diagnosis_historical_data->bopd);
            array_push($asphaltenes_wt_data, $asphaltenes_d_diagnosis_historical_data->asphaltenes);
        }


        asphaltenes_d_diagnosis_historical_projection_data::where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->delete();

        if ( $request->input('perform_historical_projection_oil') != 'without') {
            $value_historical_projection_table = json_decode($request->input("value_historical_projection_data"));
            $value_historical_projection_table = is_null($value_historical_projection_table) ? [] : $value_historical_projection_table;

            /* Arreglos para guardar los datos organizados - módulo de cálculo */
            foreach ($value_historical_projection_table as $value) {
                $asphaltenes_d_diagnosis_historical_projection_data = new asphaltenes_d_diagnosis_historical_projection_data;
                $asphaltenes_d_diagnosis_historical_projection_data->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
                $asphaltenes_d_diagnosis_historical_projection_data->date = date("Y/m/d", strtotime($value[0]));
                $asphaltenes_d_diagnosis_historical_projection_data->bopd = str_replace(",", ".", $value[1]);
                $asphaltenes_d_diagnosis_historical_projection_data->asphaltenes = str_replace(",", ".", $value[2]);
                $asphaltenes_d_diagnosis_historical_projection_data->save();

                array_push($dates_data, $value[0]);
                array_push($bopd_data, $asphaltenes_d_diagnosis_historical_projection_data->bopd);
                array_push($asphaltenes_wt_data, $asphaltenes_d_diagnosis_historical_projection_data->asphaltenes);
            }
        }


        asphaltenes_d_diagnosis_soluble_asphaltenes::where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->delete();
        $value_asphaltenes_table = json_decode($request->input("value_asphaltenes_table"));
        $value_asphaltenes_table = is_null($value_asphaltenes_table) ? [] : $value_asphaltenes_table;

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $asphaltenes_pressure_data = [];
        $asphaltenes_soluble_fraction = [];
        foreach ($value_asphaltenes_table as $value) {
            $asphaltenes_d_diagnosis_soluble_asphaltenes = new asphaltenes_d_diagnosis_soluble_asphaltenes;
            $asphaltenes_d_diagnosis_soluble_asphaltenes->asphaltenes_d_diagnosis_id = $asphaltenes_d_diagnosis->id;
            $asphaltenes_d_diagnosis_soluble_asphaltenes->pressure = str_replace(",", ".", $value[0]);
            $asphaltenes_d_diagnosis_soluble_asphaltenes->asphaltene_soluble_fraction = str_replace(",", ".", $value[1]);
            $asphaltenes_d_diagnosis_soluble_asphaltenes->save();

            array_push($asphaltenes_pressure_data, $asphaltenes_d_diagnosis_soluble_asphaltenes->pressure);
            array_push($asphaltenes_soluble_fraction, $asphaltenes_d_diagnosis_soluble_asphaltenes->asphaltene_soluble_fraction);
        }

        #Módulo de cálculo
        #Datos de entrada
        #PVT
        $pvt_data = [$pressure_data, $density_data, $oil_viscosity_data, $oil_formation_volume_factor_data];

        #Históricos
        $historical_data = [$dates_data, $bopd_data, $asphaltenes_wt_data];

        #Asfaltenos
        $asphaltenes_data = [$asphaltenes_pressure_data, $asphaltenes_soluble_fraction];

        $drainage_radius = $asphaltenes_d_diagnosis->drainage_radius; #rdre
        $formation_height = $asphaltenes_d_diagnosis->net_pay; #hf
        $well_radius = $asphaltenes_d_diagnosis->wellbore_radius; #rw ?
        $current_pressure = $asphaltenes_d_diagnosis->current_pressure; #pact ?
        $reservoir_initial_pressure = $asphaltenes_d_diagnosis->initial_pressure; #pini ?
        $reservoir_initial_porosity = $asphaltenes_d_diagnosis->initial_porosity; #phio ?
        $reservoir_initial_permeability = $asphaltenes_d_diagnosis->initial_permeability; #ko ? 
        $pore_throat_diameter = $asphaltenes_d_diagnosis->average_pore_diameter; #dporo ?
        $asphaltene_particle_diameter = $asphaltenes_d_diagnosis->asphaltene_particle_diameter; #dpart ?
        $agregated_asphaltenes_density = $asphaltenes_d_diagnosis->asphaltene_apparent_density; #rhop ?

        try {
            if (!$button_wr) {
                $simulation_results = $this->simulate_deposited_asphaltenes($drainage_radius, $formation_height, $well_radius, $current_pressure, $reservoir_initial_pressure, $reservoir_initial_porosity, $reservoir_initial_permeability, $pore_throat_diameter, $asphaltene_particle_diameter, $agregated_asphaltenes_density, $pvt_data, $historical_data, $asphaltenes_data);

                if ($simulation_results[0] == false) {
                    $asphaltenes_d_diagnosis->status_wr = true;
                    $asphaltenes_d_diagnosis->save();
                    $scenary = escenario::find($scenary->id);
                    $scenary->completo = 0;
                    $scenary->save();
                    return Redirect::back()->withErrors($simulation_results[1]);
                }

                if ($simulation_results == 'viscosity_error') {
                    $viscosity_error = true;
                    return View::make('results_asphaltenes_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_diagnosis', 'dates_data', 'asphaltenes_d_diagnosis', 'viscosity_error']));
                } else { 
                    $viscosity_error = false;
                }

                /* Guardando resultados */
                $properties_results = $simulation_results[0];
                $skin_results = $simulation_results[1];

                /* Eliminando resultados anteriores */
                asphaltenes_d_diagnosis_results::where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->delete();
                asphaltenes_d_diagnosis_results_skin::where('asphaltenes_d_diagnosis_id', $asphaltenes_d_diagnosis->id)->delete();
                /* Optimizando consultas */
                foreach ($skin_results as $key => $value) {
                    $asphaltenes_d_diagnosis_results_inserts = [];
                    $asphaltenes_d_diagnosis_results_skin_inserts = [];

                    array_push($asphaltenes_d_diagnosis_results_skin_inserts, array('asphaltenes_d_diagnosis_id' => $asphaltenes_d_diagnosis->id, 'date' => $value[0], 'damage_radius' => round($value[1], 3), 'skin' => round($value[2], 3)));
                    $properties_value = $properties_results[$key - 1];

                    //array_shift($properties_value);
                    //array_pop($properties_value);

                    foreach ($properties_value as $value_aux) {
                        array_push($asphaltenes_d_diagnosis_results_inserts, array('asphaltenes_d_diagnosis_id' => $asphaltenes_d_diagnosis->id, 'radius' => round($value_aux[0], 3), 'pressure' => round($value_aux[1], 7), 'porosity' => round($value_aux[2], 7), 'permeability' => round($value_aux[3], 7), 'deposited_asphaltenes' => round($value_aux[4], 7), 'soluble_asphaltenes' => round($value_aux[5], 7), 'date' => $value[0], 'viscosity_error' => $viscosity_error));
                    }

                    DB::table('asphaltenes_d_diagnosis_results')->insert($asphaltenes_d_diagnosis_results_inserts);
                    DB::table('asphaltenes_d_diagnosis_results_skin')->insert($asphaltenes_d_diagnosis_results_skin_inserts);
                }
            }

            unset($_SESSION['scenary_id_dup']);

            /*Escenario completo*/
            $asphaltenes_d_stability_analysis = DB::table('asphaltenes_d_stability_analysis')->where('scenario_id', $scenary->id)->first();
            $asphaltenes_d_diagnosis = DB::table('asphaltenes_d_diagnosis')->where('scenario_id', $scenary->id)->first();
            $asphaltenes_d_precipitated_analysis = DB::table('asphaltenes_d_precipitated_analysis')->where('scenario_id', $scenary->id)->first();

            if ($asphaltenes_d_stability_analysis && $asphaltenes_d_diagnosis && $asphaltenes_d_precipitated_analysis && !$asphaltenes_d_stability_analysis->status_wr && !$asphaltenes_d_diagnosis->status_wr && !$asphaltenes_d_precipitated_analysis->status_wr) {
                $scenary = escenario::find($scenary->id);
                $scenary->completo = 1;
                $scenary->save();
            } else {
                $scenary = escenario::find($scenary->id);
                $scenary->completo = 0;
                $scenary->save();
            }

            if (!isset($viscosity_error)) {
                $viscosity_error = 0;
            }

            return View::make('results_asphaltenes_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId', 'campo', 'cuenca', 'scenary', 'user', 'advisor', 'asphaltenes_d_diagnosis', 'dates_data', 'asphaltenes_d_diagnosis', 'viscosity_error']));
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    #Módulo de cálculo - Diagnóstico asfaltenos

    function dateDifference($date_1, $date_2, $differenceFormat)
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

    function interpolation($x, $n, $xt, $yt)
    {
        $y = 0;
        $aux_i = 1;
        if ($x < $xt[1]) {
            $extrapolation_result = $this->extrapolation($xt, $yt, $n, $x, $y);
            //$extrapolation_result = $this->extrapolation($xt, $yt, $n, $x, $y);
            $y = $extrapolation_result[0];
        }
        if ($x > $xt[$n]) {
            $extrapolation_result = $this->extrapolation($xt, $yt, $n, $x, $y);
            //$extrapolation_result = $this->extrapolation($xt, $yt, $n, $x, $y);
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
        //dd($xa, $ya, $n, $x);
        
        $n_max = $n;
        //$n_max = $n;
        $c = array_fill(1, 10, 0);
        //$c = array_fill(1, $n, 0);
        $d = array_fill(1, 10, 0);
        //$d = array_fill(1, $n, 0);
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
                //if(!isset($ya[$i]) ){dd($n_max, $ya, $c);}
                //if(!isset($ya[$i]) ){dd($xa, $ya, $n, $x);}
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

    function porosity_change($nr, $dpart, $dporo, $rhop, $rhof, $muo, $boi, $rw, $hf, $r1, $r, $dr, $phin, $co, $ea, $u, $dt)
    {

        $pi = 3.14159265359;
        $fcor = 0.8;

        $dps = array_fill(1, $nr, 0);
        $sigma = array_fill(1, $nr, 0);
        $vporo = array_fill(1, $nr, 0);
        $rrpd = array_fill(1, $nr, 0);
        $ent = array_fill(1, $nr, 0);
        $dgp = array_fill(1, $nr, 0);

        for ($i = 1; $i <= $nr; $i++) {
            $vt = 5450 * pow($dpart, 2) * ($rhop - $rhof[$i]) / ($muo[$i]);
            if ($vt < 0) {
                $vt = 0;
            }

            #Depositación superficial
            if ($muo[$i] > 300) {
                $pm1 = 0;
            }
            if ($muo[$i] > 100 and $muo[$i] <= 300) {
                $pm1 = 0.01;
            }
            if ($muo[$i] > 10 and $muo[$i] <= 100) {
                $pm1 = 1.5;
            }
            if ($muo[$i] > 1 and $muo[$i] <= 10) {
                $pm1 = 4.5;
            }
            if ($muo[$i] <= 1) {
                $pm1 = 10.5;
            }

            $dps[$i] = 0.01 * (0.00092903) * $pm1 * $u[$i] * $dpart * $co[$i] * $dr[$i];

            #volumen poroso
            if ($i == 1) {
                $vporo[$i] = $phin[$i] * $pi * $hf * (pow($r1[1], 2) - pow($rw, 2)) / (5.615 * $boi[$i]);
            } else {
                $vporo[$i] = $phin[$i] * $pi * $hf * (pow($r[$i], 2) - pow($r[$i - 1], 2)) / (5.615 * $boi[$i]);
            }

            $mtotal = $vporo[$i] * $rhof[$i] * 28316.8;

            #Relación de reducción porosidad [g/g]
            $rrpd[$i] = 2 * (0.00092903) * $dps[$i] * $dt / $mtotal;

            #Entranpamiento  y arrastre
            if ($dporo > 2.5) {
                $pm2 = 0;
            }
            if ($dporo > 1.5 and $dporo <= 2.5) {
                $pm2 = 0.0035;
            }
            if ($dporo > 0.5 and $dporo <= 1.5) {
                $pm2 = 0.0058;
            }
            if ($vt < $u[$i]) {
                $ent[$i] = (-1) * 0.01 * 0.00092903 * $pm2 * $ea[$i] * ($u[$i] - $vt);
            } else {
                $ent[$i] = 0;
            }

            #Depositación en la garganta deporo
            if ($dpart / $dporo > 0.33) {
                $dgp[$i] = 0.01 * (0.00092903) * 1.05 * $u[$i] * (1 - $fcor) * $co[$i];
            } else {
                $dgp[$i] = 0;
            }
            #Cambio de porosidad
        }
        for ($i = 1; $i <= $nr; $i++) {
            $sigma[$i] = $rrpd[$i] - $ent[$i] + $dgp[$i];
            $phic[$i] = $phin[$i] - $sigma[$i];
            $deadt[$i] = 0.001 * $dps[$i] * $dt;
        }

        return array($phic, $deadt);
    }

    function concentration_change($rw, $l, $hf, $nx, $phic, $uc, $rc, $dt, $con, $deadt, $dphi, $rl)
    {

        $radio = array_fill(0, 100, 0);
        $vporo = array_fill(1, 100, 0);
        $vc = array_fill(1, 100, 0);
        $u = array_fill(1, 100, 0);
        $beta = array_fill(1, 100, 0);
        $gamma = array_fill(1, 100, 0);
        $a1 = array_fill(1, 100, 0);
        $a2 = array_fill(1, 100, 0);
        $a3 = array_fill(1, 100, 0);
        $d = array_fill(1, 100, 0);
        $c = array_fill(1, 100, 0);
        $deadt_co = array_fill(1, 100, 0);
        $dphi_co = array_fill(1, 100, 0);
        $coc = array_fill(1, 100, 0);
        $phi_co = array_fill(1, 100, 0);

        $dx = $l / 100;
        $radio[1] = $rw;
        for ($i = 2; $i <= 100; $i++) {
            $radio[$i] = $radio[$i - 1] + $dx;
            $uci = $this->interpolation($radio[$i], $nx, $rc, $uc);
            $coi = $this->interpolation($radio[$i], $nx, $rc, $con);
            $deadti = $this->interpolation($radio[$i], $nx, $rc, $deadt);
            $dphii = $this->interpolation($radio[$i], $nx, $rc, $dphi);
            $phici = $this->interpolation($radio[$i], $nx, $rc, $phic);
            $rl[$i] = $radio[$i];
            $u[$i] = 0.0000092903 * $uci;
            $coc[$i] = $coi;
            $deadt_co[$i] = $deadti;
            $dphi_co[$i] = $dphii;
            $phi_co[$i] = $phici;
        }

        #Solución de la ecuación de concentración de particulas
        for ($m2 = 2; $m2 < 100; $m2++) {
            $a1[$m2] = -$u[$m2] * $dt / (2.0 * $dx);
            $a2[$m2] = $phi_co[$m2] + 0.0001 * $dt * $dphi_co[$m2] + ($u[$m2 + 1] - $u[$m2 - 1]) * $dt / (2.0 * $dx);
            $a3[$m2] = $u[$m2] * $dt / (2.0 * $dx);
            $d[$m2] = $phi_co[$m2] * $coc[$m2] - 0.0001 * $dt * ($deadt_co[$m2]);

        }


        $d[2] = $d[2] - $a1[2] * $coc[1];
        $a1[2] = 0;
        $a1[100] = -$u[100] * $dt / $dx;
        $a2[100] = $phi_co[100] + $dt * $dphi_co[100] + (2.0 * $u[100] - $u[99]) * $dt / $dx;
        $a3[100] = 0;
        $d[100] = $phi_co[100] * $coc[100] - 0.0001 * $dt * ($deadt_co[100]);

        #Aalgoritmo de thomas
        $beta[2] = $a2[2];
        $gamma[2] = $d[2] / $a2[2];

        for ($m2 = 3; $m2 <= 100; $m2++) {
            $beta[$m2] = $a2[$m2] - $a1[$m2] * $a3[$m2 - 1] / $beta[$m2 - 1];
            $gamma[$m2] = $d[$m2] / $beta[$m2] - $a1[$m2] * $gamma[$m2 - 1] / $beta[$m2];
        }
        $co[100] = $gamma[100];
        for ($m2 = 99; $m2 >= 2; $m2--) {
            $co[$m2] = $gamma[$m2] - $a3[$m2] * $co[$m2 + 1] / $beta[$m2];
        }
        $co[1] = $co[2];

        return array($co, $rl);
    }

    function simulate_deposited_asphaltenes($rdre, $hf, $rw, $pact, $pini, $phio, $ko, $dporo, $dpart, $rhop, $pvt_data, $historical_data, $asphaltenes_data)
    {

        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '-1');
        set_time_limit(1800);

        $complete_simulated_results = [];
        $complete_damage_results = [];

        $simulated_results = [];
        $damage_results = [];

        if ($dporo <= 2.54) {
            $dpart = $dpart * 1;
        }else{
            $dpart = $dpart * 10;
        }

        $pi = 3.14159265359;
        $x = 0;
        $radio_dam = 0;
        #Datos pvt
        $nv = count($pvt_data[0]);
        $ppvt = $this->set_array($pvt_data[0], $nv);
        $dopvt = $this->set_array($pvt_data[1], $nv);
        $uopvt = $this->set_array($pvt_data[2], $nv);
        $bopvt = $this->set_array($pvt_data[3], $nv);

        #Datos históricos
        $nh = count($historical_data[0]);
        $hist = $this->set_array_string($historical_data[0], $nh);
        $bopd = $this->set_array($historical_data[1], $nh);
        $wtasf = $this->set_array($historical_data[2], $nh);

        #Datos asfaltenos
        $ns = count($asphaltenes_data[0]);
        $pasf = $this->set_array($asphaltenes_data[0], $ns);
        $sasf = $this->set_array($asphaltenes_data[1], $ns);

        #Discretizando el medio (Geometría radial)
        $nr = 300;
        $ri = 0;

        $r = array_fill(1, $nr, 0);
        $dr = array_fill(1, $nr, 0);
        $r1 = array_fill(1, $nr, 0);
        $dr1 = array_fill(1, $nr - 1, 0);
        $alfa = pow(($rdre / $rw), (1 / ($nr - 1)));

        $r[1] = $rw;
        for ($i = 2; $i <= $nr; $i++) {
            $r[$i] = $alfa * $r[$i - 1];
        }

        for ($i = 1; $i < $nr; $i++) {
            $dr1[$i] = $r[$i + 1] - $r[$i];
        }

        for ($i = 1; $i <= $nr; $i++) {
            if ($i == $nr) {
                $r1[$i] = $rdre;
            } else {
                $r1[$i] = (($alfa - 1) * $r[$i]) / (log($alfa));
                if ($r1[$i] < $ri) {
                    $x = $i;
                }
            }
        }
        for ($i = 1; $i <= $nr; $i++) {
            if ($i == 1) {
                $dr[$i] = $r1[$i] - $r[$i];
            } else {
                $dr[$i] = $r1[$i] - $r1[$i - 1];
            }
        }

        #Inicializando varibles iniciales
        $pn = array_fill(1, $nr, $pini);
        $phin = array_fill(1, $nr, $phio);
        $kn = array_fill(1, $nr, $ko);
        $co = array_fill(1, $nr, 0);
        $ea = array_fill(1, $nr, 0);

        for ($i = 1; $i <= $nr; $i++) #Optimizar
        {
            $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
            $coi = $this->interpolation($pini, $ns, $pasf, $sasf);
            if ( ($rho) < 0.00000000001 ) {
                $co[$i] = 0;
            } else {
                $co[$i] = ($wtasf[1] * 10000 * (1 - $coi)) / ($rho);
            }
            $ea[$i] = 0;
        }
        
        #Dimensionamiento
        $b = array_fill(1, $nr, 0);
        $a = array_fill(1, $nr, 0);
        $f = array_fill(1, $nr, 0);
        $c = array_fill(1, $nr, 0);
        $d = array_fill(1, $nr, 0);
        $qq = array_fill(1, $nr, 0);
        $gg = array_fill(1, $nr, 0);
        $w = array_fill(1, $nr, 0);
        $pcal = array_fill(1, $nr, 0);
        $dpre = array_fill(1, $nr, 0);
        $u = array_fill(1, $nr, 0);
        $phic = array_fill(1, $nr, 0);
        $dphi = array_fill(1, $nr, 0);
        $deadt = array_fill(1, $nr, 0);
        $muo = array_fill(1, $nr, 0);
        $rhof = array_fill(1, $nr, 0);
        $boi = array_fill(1, $nr, 0);
        $kc = array_fill(1, $nr, 0);
        $cocal = array_fill(1, 100, 0);
        $rl = array_fill(1, 100, 0);
        $coc = array_fill(1, $nr, 0);
        $cocs = array_fill(1, $nr, 0);
        $cri = array_fill(1, 6, 0);
        $pite = array_fill(1, 4, 0);
        $crite = array_fill(1, 4, 0);
        $tiempo = array_fill(1, $nh, 0);

        $n = 0.5;
        $dt = 10;
        $un = 3;

        #Delta de tiempo
        for ($i = 1; $i <= $nh; $i++) {
            if ($i == 1) {
                $tiempo[$i] = 30;
            } else {
                $tiempo[$i] = floatval($this->dateDifference($hist[$i], $hist[$i - 1], "%a"));
            }
        }

        $mu1 = $this->interpolation($pini, $nv, $ppvt, $uopvt);
        $mu2 = $this->interpolation(14.7, $nv, $ppvt, $uopvt);

        if ($mu1 > $mu2) {
            $mu_ref = $mu1;
        } else { 
            $mu_ref = $mu2;
        }
    
        if ($mu_ref > 0 && $mu_ref < 100) {
            $cri = array(1 => 0.01, 0.005, 0.001, 0.0005, 0.0001, 0.000005, 0.000001, 0.0000005);
            $crite = array(1 => 0.01, 0.005, 0.001, 0.0005, 0.0001);
        } elseif ($mu_ref >= 100 && $mu_ref < 1000) {
            $cri = array(1 => 0.5, 0.1, 0.05, 0.001, 0.005, 0.000005, 0.000001, 0.0000005);
            $crite = array(1 => 0.5, 0.1, 0.05, 0.001, 0.005);
        } elseif ($mu_ref >= 1000 && $mu_ref < 20000) {
            $cri = array(1 => 0.9, 0.5, 0.1, 0.05, 0.001, 0.005, 0.000005, 0.000001);
            $crite = array(1 => 0.9, 0.5, 0.1, 0.05, 0.001);
        } else {
            return ('viscosity_error');
        }

        #Variables nuevas
        //$cri = array(1 => 0.5, 0.005, 0.001, 0.0005, 0.0001, 0.000005, 0.000001, 0.0000005);
        $pite = array(1 => 0, 0, 0, 0, 0);
        //$crite = array(1 => 0.5, 0.005, 0.001, 0.0005, 0.0001);
        $cr = $cri[1];
        $flag_ran_xx_7 = 0;
        $flag_p_ultima = 0;
        $flag_xx = 0;

        for ($xx = 1; $xx <= 7; $xx++) {    #Nuevo ciclo
            //if ($xx == 2) {dd($xx);}
            for ($kk = 1; $kk <= $nh; $kk++) {
                $ndt = 24 * $tiempo[$kk] / $dt;
                $qo = -$bopd[$kk];
                for ($v = 1; $v <= $ndt; $v++) {
                    //if ($xx == 1 && $kk == 4 && $v == 10) { dd($pcal, $kc, $cr); }  
                    //if ($xx == 7 && $kk == (15)) { dd('LLEGO AL SEGUNDO FOR DEL CICLO 7', $ndt, $x, $nr, $ri); }
                    #coeficientes matriz tridiagonal
                    $i = 1;
                    while ($i == $x + 1) {
                        $i = $i + 1;
                        $b[$i] = pow($r1[$i - 1], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i - 1]);
                    }
                    for ($i = $x + 2; $i <= $nr; $i++) {
                        $b[$i] = $r1[$i - 1] / ($r[$i] * $dr[$i] * $dr1[$i - 1]);
                    }

                    $i = 0;
                    if ($x == 0) {
                        $x = 1;
                    }
                    while ($i == $x - 1) {
                        $i = $i + 1;
                        $a[$i] = pow($r1[$i], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i]);
                    }

                    for ($i = $x; $i < $nr; $i++) {
                        $a[$i] = $r1[$i] / ($r[$i] * $dr[$i] * $dr1[$i]);
                    }

                    $i = 0;
                    while ($i == $x) {
                        $i = $i + 1;
                        $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                        $g1 = 3792.58489625175 * $n * $phin[$i] * $cr / $kn[$i];
                        $f[$i] = $g1 * $mu / $dt; #g1 * uapp[$i] / dt
                    }

                    for ($i = $x + 1; $i < $nr; $i++) {
                        $g2 = 3792.58489625175 * $phin[$i] * $cr * $un / $kn[$i];
                        $f[$i] = $g2 / $dt;
                    }

                    if ($ri > 0 and $ri < $re) {
                        $b[$x] = $dr1[$x] / $dr1[$x - 1];
                        $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                        $a[$x] = $mu / $un; #uapp[$x] / un
                        $f[$x] = 0;
                    }

                    $c[1] = -($a[1] + $f[1]);
                    $c[$nr] = -($b[$nr] + $f[$nr]);

                    for ($i = 2; $i < $nr; $i++) {
                        $c[$i] = -($a[$i] + $b[$i] + $f[$i]);
                    }

                    for ($i = 1; $i <= $nr; $i++) {
                        if ($i == 1) {
                            $beta = $this->interpolation($pn[$i], $nv, $ppvt, $bopvt);
                            $vm = $pi * $hf * (pow($r1[1], 2) - pow($rw, 2)) / (5.615 * $beta);
                            $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                            $d[$i] = -$f[$i] * $pn[$i] - 158.024370659982 * ($qo / ($kn[$i] * $vm)) * $mu;
                        } else {
                            $d[$i] = -$f[$i] * $pn[$i];
                        }
                    }
                    
                    $qq[1] = $a[1] / $c[1];
                    $gg[1] = $d[1] / $c[1];

                    for ($j = 2; $j <= $nr; $j++) {
                        $w[$j] = $c[$j] - ($b[$j] * $qq[$j - 1]);
                        $gg[$j] = ($d[$j] - ($b[$j] * $gg[$j - 1])) / $w[$j];
                        $qq[$j] = $a[$j] / $w[$j];
                    }

                    $pcal[$nr] = $gg[$nr];
                    for ($j = $nr - 1; $j >= 1; $j--) {
                        $pcal[$j] = ($gg[$j] - ($qq[$j] * $pcal[$j + 1]));
                    }

                    # Nuevo
                    if ($pcal[1] < 0 || ($flag_p_ultima == 1 && $xx == $flag_xx+1)) {
                        // dd('entró', $pact, $pite, $crite, $cr, $pcal, $xx);
                        if ($xx == 7) {
                            $xx = 6; 
                            $flag_ran_xx_7 = 1;
                            break 2;
                        }else if ($xx == 1) {
                            return [false, ['msg' => 'Negative bottom hole pressures estimated. Please check the input data.']];
                        }else{
                            //dd($xx, $pcal[1]);
                            $xx = 6;
                            break 2;
                        }
                    }

                    # Cuando la presión calculada es menor a la presión pedida, no se necesitan más presiones
                    if ( $pcal[299] < $pact && $flag_p_ultima == 0 && $xx > 6) { 
                        // dd('entró 2', $pact, $pite, $crite, $cr, $pcal, $xx);
                        $flag_p_ultima = 1;
                        $flag_xx = $xx;
                    }
                    
                    for ($i = 1; $i <= $nr; $i++) {
                        //if($xx == 4 && $kk == 4 && $v == 56) {dd('eh ave maría pues ome', $nh, $ndt, $nr);}
                        //if($xx == 4 && $kk == 4 && $v == 56) {dd($pcal, $nv, $ppvt, $dopvt, $ns, $pasf,);}
                        $rho = $this->interpolation($pcal[$i], $nv, $ppvt, $dopvt);
                        $coi = $this->interpolation($pcal[$i], $ns, $pasf, $sasf);
                        if ( ($rho) < 0.00000000001 ) {
                            $co[$i] = 0;
                        } else {
                            $co[$i] = ($wtasf[$kk] * 10000 * (1 - $coi)) / ($rho);
                        }
                    }
                    
                    #Cálculo del flux
                    for ($i = 2; $i < $nr; $i++) {
                        $dpre[$i] = -($pcal[$i] - $pcal[$i - 1]) / (2 * $dr[$i]);
                    }
                    $dpre[$nr] = 0;
                    $u[1] = -2.5 * 158.024370659982 * $qo / (2 * $pi * $rw * $hf); #ft/dia

                    for ($i = 2; $i <= $nr; $i++) {
                        $mu = $this->interpolation($pcal[$i], $nv, $ppvt, $uopvt);
                        //if( $mu == 0) {
                        //    dd($kn[$i], $dpre[$i], $mu, $pcal, $kk);
                        //}
                        $u[$i] = ((-$kn[$i]) * $dpre[$i]) / $mu;
                        if ($u[$i] < 0.000001) {
                            $u[$i] = 0;
                        }
                    }
                    
                    #Cambio de porosidad
                    for ($i = 1; $i <= $nr; $i++) {
                        $mu = $this->interpolation($pcal[$i], $nv, $ppvt, $uopvt);
                        $rho = $this->interpolation($pcal[$i], $nv, $ppvt, $dopvt);
                        $beta = $this->interpolation($pcal[$i], $nv, $ppvt, $bopvt);
                        $muo[$i] = $mu;
                        $rhof[$i] = $rho;
                        $boi[$i] = $beta;
                    }

                    $porosity_change_results = $this->porosity_change($nr, $dpart, $dporo, $rhop, $rhof, $muo, $boi, $rw, $hf, $r1, $r, $dr, $phin, $co, $ea, $u, $dt);
                    $phic = $porosity_change_results[0];
                    $deadt = $porosity_change_results[1];

                    for ($i = 1; $i <= $nr; $i++) {
                        $dphi[$i] = $phin[$i] - $phic[$i];
                    }

                    #Cambio de permeabilidad
                    for ($i = 1; $i <= $nr; $i++) {
                        $ea[$i] = $ea[$i] + $deadt[$i];
                        $kc[$i] = $kn[$i] * pow((($phic[$i]) / $phin[$i]), 2.5);
                    }

                    #Solución de la ecuacion de concentracion de particulas
                    $concentration_change_results = $this->concentration_change($rw, $rdre, $hf, $nr, $phic, $u, $r, $dt, $co, $deadt, $dphi, $rl);
                    $cocal = $concentration_change_results[0];
                    $rl = $concentration_change_results[1];

                    for ($i = 1; $i < $nr; $i++) {
                        $coi = $this->interpolation($r[$i], 100, $rl, $cocal);
                        $coc[$i] = $coi;
                    }

                    $coc[$nr] = $co[$nr];

                    for ($i = 1; $i <= $nr; $i++) {
                        $pn[$i] = $pcal[$i];
                        $phin[$i] = $phic[$i];
                        $kn[$i] = $kc[$i];
                        $co[$i] = $coc[$i];
                        $coi = $this->interpolation($pn[$i], $ns, $pasf, $sasf);
                        $cocs[$i] = (($wtasf[$kk] * 10000 * $coi) / ($rho)) - $co[$i];
                    }

                }


                $radioo = array_fill(1, $nr, 0);

                #Radio de daño
                for ($i = 1; $i <= $nr; $i++) {
                    if (($ko - $kc[$i]) > (0.01 * $ko)) {
                        $radioo[$i] = $r[$i];
                    }else{
                        $radioo[$i] = 0;
                    }
                }

                $radio_dam = max($radioo);
                
                #Cambios cálculos de skin  
                $skin = 0;
                $skin_array = [];
                for ($i = 1; $i <= $nr; $i++) 
                {
                    if ($radioo[$i] != 0) 
                    {
                        $skin = (($ko / $kc[$i]) - 1.0) * log($radio_dam / $rw);
                    }
                    else
                    {
                        $skin = 0;
                    }
                    array_push($skin_array, $skin);
                }
                
                $max_skin = max($skin_array);


                /*
                VERSION PARA PRUEBAS ELIANA
        
                #Radio de daño
                for ($i = 2; $i <= $nr; $i++) {
                    if (($ko - $kc[$i]) > 0.05 * $ko) {
                        $radio_dam = ($r[$i] + $r[$i - 1]) / 2;
                    }
                }
                
                
                #Cambios cálculos de skin  
                $skin = 0;
                $skin_array = [];
                for ($i = 1; $i <= $nr; $i++) 
                {
                    if ($radio_dam != 0) 
                    {
                        $skin = (($ko / $kc[$i]) - 1.0) * log($radio_dam / $rw);
                    }
                    else
                    {
                        $skin = 0;
                    }
                    array_push($skin_array, $skin);
                }
                
                $max_skin = max($skin_array); /*  

                /*
                CAMBIOS DE ANDRES
                #Radio de daño
                for ($i = 1; $i <= $nr; $i++) {
                    if (abs($ko - $kc[$i]) > (0.05 * $ko)) {
                        $radio_dam[$i] = $r[$i];
                    }else{
                        $radio_dam[$i] = 0;
                    }
                }

                $r_damage = max($radio_dam);
                
                #Cambios cálculos de skin  
                $skin = [];
                for ($i = 1; $i <= $nr; $i++) 
                {
                    if ($radio_dam[$i] != 0) 
                    {
                        $skin[$i] = (($ko / $kc[$i]) - 1.0) * log($r_damage / $rw);
                    }
                    else
                    {
                        $skin[$i] = 0;
                    }
                }
                
                $max_skin = max($skin); */                 
                if ($xx == 7) {
                    for ($i = 1; $i <= $nr; $i++) 
                    {
                        $simulated_results[$i] = [$r[$i], $pcal[$i], $phic[$i], $kc[$i], $ea[$i], $cocs[$i]];
                    }

                    //if ($xx == 7 && $kk == $nh) {
                    //    dd($pcal, $simulated_results);
                    //}

                    $damage_results[$kk] = [$hist[$kk], $radio_dam, $max_skin];

                    array_push($complete_simulated_results, $simulated_results);
                }
                
            }

            #Nueva sección
            if ($xx < 6) {
                $pite[$xx] = $pcal[299];
                $crite[$xx] = $cr;

                for ($i = 1; $i <= $nr; $i++) {
                    $pn[$i] = $pini;
                    $phin[$i] = $phio;
                    $kn[$i] = $ko;
                }
                for ($i = 1; $i <= $nr; $i++) { 
                    $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                    $coi = $this->interpolation($pini, $ns, $pasf, $sasf);
                    $co[$i] = ($wtasf[1] * 10000 * (1 - $coi)) / $rho;
                    $ea[$i] = 0;
                }

                $cr = $cri[$xx + 1];
            }

            if ($xx == 6) {
                //dd('LLEGA AL INICIO DEL xx = 6',$pite);
                //dd(count($pite));

                #Eliminar posiciones en 0
                while (count($pite) > 1 && $pite[count($pite)] == 0) { 
                    if ($pite[count($pite)] == 0) { 
                        array_pop($pite);
                    }
                }
                
                if (count($pite) > 1) {
                    for ($j = 1; $j <= (count($pite) - 1); $j++) {  #length(crite)-1
                        if ($pact > $pite[1]) {
                            $cr = $crite[1] + (($crite[2] - $crite[1]) / ($pite[2] - $pite[1])) * ($pact - $pite[1]);
                            if ($cr < 0) { 
                                $cr = $crite[count($pite)];
                            }
                            for ($i = 1; $i <= $nr; $i++) {
                                $pn[$i] = $pini;
                                $phin[$i] = $phio;
                                $kn[$i] = $ko;
                            }
                            for ($i = 1; $i <= $nr; $i++) {
                                $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                                $coi = $this->interpolation($pini, $ns, $pasf, $sasf);
                                $co[$i] = ($wtasf[1] * 10000 * (1 - $coi)) / $rho; #cambio
                                $ea[$i] = 0;
                            }
                            // dd($pact, $pite, $crite, $cr, 1, $xx);
                            break;
                        }elseif (($pact < $pite[$j]) && ($pact > $pite[$j + 1])) { 
                            $cr = $crite[$j] + (($crite[$j + 1] - $crite[$j]) / ($pite[$j + 1] - $pite[$j])) * ($pact - $pite[$j]);
                            if ($cr < 0) { 
                                $cr = $crite[count($pite)];
                            }
                            for ($i = 1; $i <= $nr; $i++) {
                                $pn[$i] = $pini;
                                $phin[$i] = $phio;
                                $kn[$i] = $ko;
                            }
                            for ($i = 1; $i <= $nr; $i++) {
                                $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                                $coi = $this->interpolation($pini, $ns, $pasf, $sasf);
                                $co[$i] = ($wtasf[1] * 10000 * (1 - $coi)) / $rho; #cambio
                                $ea[$i] = 0;
                            }
                            // dd($pact, $pite, $crite, $cr, 2, $xx);
                            break;
                        }elseif ($pact < $pite[count($pite)]) {
                            if ($flag_ran_xx_7 == 0) { 
                                $cr = $crite[count($pite) - 1] + (($crite[count($pite)] - $crite[count($pite) - 1]) / ($pite[count($pite)] - $pite[count($pite) - 1])) * ($pact - $pite[count($pite) - 1]);
                            }else{
                                $cr = $crite[count($pite)];
                            }
                            if ($cr < 0) { 
                                $cr = $crite[count($pite)];
                            }
                            for ($i = 1; $i <= $nr; $i++) {
                                $pn[$i] = $pini;
                                $phin[$i] = $phio;
                                $kn[$i] = $ko;
                            }
                            for ($i = 1; $i <= $nr; $i++) {
                                $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                                $coi = $this->interpolation($pini, $ns, $pasf, $sasf);
                                $co[$i] = ($wtasf[1] * 10000 * (1 - $coi)) / $rho; #cambio
                                $ea[$i] = 0;
                            }
                            // dd($pact, $pite, $crite, $cr, 3, $xx);
                            break;
                        }
                    }
                }elseif (count($pite) == 1) {
                    $cr = $crite[1];
                    for ($i = 1; $i <= $nr; $i++) {
                        $pn[$i] = $pini;
                        $phin[$i] = $phio;
                        $kn[$i] = $ko;
                    }
                    for ($i = 1; $i <= $nr; $i++) {
                        $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                        $coi = $this->interpolation($pini, $ns, $pasf, $sasf);
                        $co[$i] = ($wtasf[1] * 10000 * (1 - $coi)) / $rho; #cambio
                        $ea[$i] = 0;
                    }
                }
                
                // dd($pact, $pite, $crite, $cr);

                
            }

            //if ($xx == 7) { dd($pact, $pcal[1], $pcal[299]); } #AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
        }

        return array($complete_simulated_results, $damage_results);
    }

    function set_array($original_array, $n)
    {
        $fixed_array = new SplFixedArray($n + 1);
        for ($i = 0; $i < count($original_array); $i++) {
            $fixed_array[$i + 1] = floatval($original_array[$i]);
        }
        return $fixed_array;
    }

    function set_array_string($original_array, $n)
    {
        $fixed_array = new SplFixedArray($n + 1);
        for ($i = 0; $i < count($original_array); $i++) {
            $fixed_array[$i + 1] = $original_array[$i];
        }
        return $fixed_array;
    }



    public function getTreeExternalData($id)
    {
        $fdd = asphaltenes_d_diagnosis::where('scenario_id','=',$id)->orderBy('created_at','DESC')->first();
        if (!$fdd || empty($fdd)) {
            return json_encode([]);
        }

        $fdds = asphaltenes_d_diagnosis_results::where('asphaltenes_d_diagnosis_id','=',$fdd->id)->orderBy('date','DESC')->get();
        if(!$fdds || empty($fdds) || $fdds->count() == 0) {
            return json_encode([]);
        }

        $arreglo = [];
        $fdds = collect($fdds)->groupBy('date')->sortBy('radius')->first();
        foreach ($fdds as $v) {
            $arreglo[] = [$v->radius,$v->permeability,$v->porosity,$v->deposited_asphaltenes];
        }

        return json_encode($arreglo);
    }

    public function getImportExternalTree(Request $request)
    {
        $user_id = \Auth::id();
        $user = User::where('id', '=', $user_id)->first();
        $rol = $user->office;
        $type = $request->type;

        if($rol == 0){
            $company_tree = company::select('company.id', 'company.name')
            ->join('proyectos', 'proyectos.compania', '=', 'company.id')
            ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
            ->raw('COUNT(escenarios.id) > 0')
            ->where('escenarios.estado','=',1)
            ->where('escenarios.asphaltene_type', '=', "Asphaltene diagnosis")
            ->where('escenarios.tipo', '=', $type)
            ->distinct()
            ->get();

            //dd($company_tree);

            $tree = [];
            foreach ($company_tree as $company) {
                $company['icon'] = url('images/icon-company.png');
                $company['href'] = '';
                $projects = proyecto::select('proyectos.id as id', 'proyectos.nombre as name')
                ->join('users', 'users.id', '=', 'proyectos.usuario_id')
                ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
                ->raw('COUNT(escenarios.id) > 0')
                ->where('escenarios.estado','=',1)
                ->where('escenarios.asphaltene_type', '=', "Asphaltene diagnosis")
                ->where('escenarios.tipo', '=', $type)
                ->where('proyectos.compania',"=", $company->id)
                ->distinct()
                ->get();
                $this->add_simulations_to_projects($projects, $type);

                $company['child'] = $projects;
            }
            $tree = $company_tree;
        } else {
            $projects = proyecto::select('proyectos.id', 'proyectos.nombre', 'proyectos.compania')
            ->join('escenarios', 'escenarios.proyecto_id', '=', 'proyectos.id')
            ->raw('COUNT(escenarios.id) > 0')
            ->where('escenarios.estado','=',1)
            ->where('escenarios.asphaltene_type', '=', "Asphaltene diagnosis")
            ->where('escenarios.tipo', '=', $type)
            ->where('proyectos.compania',"=", $user->company)
            ->distinct()
            ->get();
            $this->add_simulations_to_projects($projects, $type);

            $tree = $projects;
        }

        return Response()->json($tree);
    }

    private function add_simulations_to_projects($project_tree, $type)
    {
        $group = [];
        foreach ($project_tree as $project) {
            $project['name'] = $project->name;
            $project['icon'] = url('images/icon-folder.png');
            $project['href'] = '';

            $wells = pozo::join('escenarios', 'escenarios.pozo_id', '=', 'pozos.id')
            ->where('escenarios.proyecto_id', '=', $project->id)
            ->where('escenarios.estado','=',1)
            ->where('escenarios.tipo', '=', $type)
            ->where('escenarios.asphaltene_type', '=', "Asphaltene diagnosis")
            ->select('pozos.id as id', 'pozos.nombre as name')
            ->raw('COUNT(escenarios.id) > 0')
            ->distinct()
            ->get();

            foreach ($wells as $well) {
                $well['icon'] = url('images/icon-well.png');
                $well['href'] = '';
                $well['id'] = $well['id'];
                $well['name'] = $well['name'];

                $scenary = escenario::where('pozo_id', '=', $well->id)
                ->where('proyecto_id', '=', $project->id)
                ->where('escenarios.estado','=',1)
                ->where('escenarios.tipo', '=', $type)
                ->where('escenarios.asphaltene_type', '=', "Asphaltene diagnosis")
                ->select('id', 'nombre')
                ->get();

                foreach ($scenary as $sce) {
                    $sce['icon'] = url('images/icon-scenario.png');
                    $sce['href'] = url('#link_external');
                    $sce['id'] = $sce['id'];
                    $sce['name'] = $sce['nombre'];
                    $sce['child'] = [];
                }
                $well['child'] = $scenary;
            }
            $project['child'] = $wells;
        }
    }

}
