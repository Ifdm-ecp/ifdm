<?php

namespace App\Http\Controllers;
if(!isset($_SESSION)) {
 session_start();
}
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use View;
use App\cuenca;
use App\fines_d_diagnosis;
use App\fines_d_historical_data;
use App\fines_d_phenomenological_constants;
use App\fines_d_pvt;
use App\escenario;
use App\Http\Requests\fines_migration_diagnosis_request;
use \SplFixedArray;
use App\fines_d_diagnosis_results;
use App\fines_d_diagnosis_results_skin;
use App\error_log;
use Redirect;

class add_fines_migration_diagnosis_controller extends Controller
{
    /**
     * Despliega la vista add_fines_migration_diagnosis. 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::check()) 
        {
            $scenaryId = \Request::get('scenaryId');
            $scenary = DB::table('escenarios')->where('id',$scenaryId)->first();

            #Variables para barra de informacion
            $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
            $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
            $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
            $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
            $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
            
            $user = DB::table('users')->select('users.fullName')->where('id','=',$scenary->user_id)->first();
            $advisor = $scenary->enable_advisor;

            $fines_d_diagnosis = new fines_d_diagnosis;
            $fines_d_diagnosis->scenario_id = $scenary->id;
            $fines_d_diagnosis->save();

            return View::make('add_fines_migration_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId','campo', 'cuenca','scenary','user', 'advisor']));
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

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
    public function duplicate($id,$duplicateFrom)
    {
        $_SESSION['scenary_id_dup'] = $id;
        return $this->edit($duplicateFrom);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(fines_migration_diagnosis_request $request)
    {
        $scenaryId = $request->input('scenaryId');
        $scenary = DB::table('escenarios')->where('id',$scenaryId)->first();
        $button_wr = isset($_POST['button_wr']);

        #Variables para barra de informacion
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        
        $user = DB::table('users')->select('users.fullName')->where('id','=',$scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        #Saber si el escenario se creo por lo menos una vez y si esta completo
        $scenary = escenario::find($scenary->id);
        $scenary->estado=1;
        $scenary->completo=1;
        $scenary->save();

        $fines_d_diagnosis = fines_d_diagnosis::where("scenario_id", "=", $scenaryId)->first();
        $fines_d_diagnosis->drainage_radius = $request->input('drainage_radius');
        $fines_d_diagnosis->formation_height = $request->input('formation_height');
        $fines_d_diagnosis->well_radius = $request->input('well_radius');
        if ($request->input('perforation_radius') === "") { $fines_d_diagnosis->perforation_radius = null; } else { $fines_d_diagnosis->perforation_radius = $request->input('perforation_radius'); }
        if ($request->input('perforation_density') === "") { $fines_d_diagnosis->perforation_density = null; } else { $fines_d_diagnosis->perforation_density = $request->input('perforation_density'); }
        // $fines_d_diagnosis->compressibility = $request->input('compressibility');
        $fines_d_diagnosis->initial_porosity = $request->input('initial_porosity');
        // $fines_d_diagnosis->porosity_limit_constant = $request->input('porosity_limit_constant');
        $fines_d_diagnosis->initial_permeability = $request->input('initial_permeability');
        $fines_d_diagnosis->initial_pressure = $request->input('initial_pressure');
        $fines_d_diagnosis->current_pressure = $request->input('current_pressure');
        $fines_d_diagnosis->type_of_suspension_flux = $request->input('type_of_suspension_flux');
        $fines_d_diagnosis->fine_density = $request->input('fine_density');
        $fines_d_diagnosis->initial_deposited_fines_concentration = $request->input('initial_deposited_fines_concentration');
        if ($request->input('water_volumetric_factor') === "") { $fines_d_diagnosis->water_volumetric_factor = null; } else { $fines_d_diagnosis->water_volumetric_factor = $request->input('water_volumetric_factor'); }
        if ($request->input('plug_radius') === "") { $fines_d_diagnosis->plug_radius = null; }else{ $fines_d_diagnosis->plug_radius = $request->input('plug_radius'); }
        $fines_d_diagnosis->critical_rate = $request->input('critical_rate');
        $fines_d_diagnosis->initial_fines_concentration_in_fluid = $request->input('initial_fines_concentration_in_fluid');
        $fines_d_diagnosis->length = $request->input('length');
        $fines_d_diagnosis->diameter = $request->input('diameter');
        $fines_d_diagnosis->porosity = $request->input('porosity');
        $fines_d_diagnosis->illite = $request->input('illite');
        $fines_d_diagnosis->kaolinite = $request->input('kaolinite');
        $fines_d_diagnosis->chlorite = $request->input('chlorite');
        $fines_d_diagnosis->emectite = $request->input('emectite');
        $fines_d_diagnosis->total_amount_of_clays = $request->input('total_amount_of_clays');
        $fines_d_diagnosis->quartz = $request->input('quartz');
        $fines_d_diagnosis->feldspar = $request->input('feldspar');
        $fines_d_diagnosis->final_date = date("Y/m/d", strtotime($request->input('final_date')));
        $button_wr = isset($_POST['button_wr']);
        $fines_d_diagnosis->status_wr = $button_wr;
        $fines_d_diagnosis->perform_historical_projection = $request->input('perform_historical_projection_oil');      
        $fines_d_diagnosis->scenario_id = $scenary->id;
        $fines_d_diagnosis->save();

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $pressure_data = [];
        $density_data = [];
        $oil_viscosity_data = [];
        $oil_volumetric_factor_data = [];

        #Guardar tabla PVT
        $pvt_data = json_decode($request->input("value_pvt_data"));
        $pvt_data = is_null($pvt_data) ? [] : $pvt_data;
        foreach ($pvt_data as $value) {
            $fines_d_pvt = new fines_d_pvt;
            $fines_d_pvt->fines_d_diagnosis_id = $fines_d_diagnosis->id;
            $fines_d_pvt->pressure = str_replace(",", ".", $value[0]);
            $fines_d_pvt->oil_density = str_replace(",", ".", $value[1]);
            $fines_d_pvt->oil_viscosity = str_replace(",", ".", $value[2]);
            $fines_d_pvt->volumetric_oil_factor = str_replace(",", ".", $value[3]);
            $fines_d_pvt->save();

            #Agregando datos para módulo de cálculo
            array_push($pressure_data, $fines_d_pvt->pressure);
            array_push($density_data, $fines_d_pvt->oil_density);
            array_push($oil_viscosity_data, $fines_d_pvt->oil_viscosity);
            array_push($oil_volumetric_factor_data, $fines_d_pvt->volumetric_oil_factor);
        }

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $dates_data = [];
        $bopd_data = [];

        #Guardar tabla de historicos
        fines_d_historical_data::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();
        $historical_data = json_decode($request->input("value_historical_data"));
        $historical_data = is_null($historical_data) ? [] : $historical_data;
        $historical_projection_data = json_decode($request->input("value_historical_projection_data"));
        $historical_data = array_merge($historical_data, $historical_projection_data);
        foreach ($historical_data as $value) {
            $fines_d_historical_data = new fines_d_historical_data;
            $fines_d_historical_data->fines_d_diagnosis_id = $fines_d_diagnosis->id;
            $fines_d_historical_data->date = date("Y/m/d", strtotime($value[0]));
            $fines_d_historical_data->bopd = str_replace(",", ".", $value[1]);
            $fines_d_historical_data->save();
        
            array_push($dates_data, $value[0]);
            array_push($bopd_data, $fines_d_historical_data->bopd);
        }

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $qlab_data = [];
        $permeability_lab_data = [];
        $k1_lab_data = [];
        $k2_lab_data = [];
        $dpdl_lab_data = [];
        $k3_lab_data = [];
        $k4_lab_data = [];
        $k5_lab_data = [];
        $dpdls_lab_data = [];
        $sigma_lab_data = [];
        $k6_lab_data = [];
        $ab2_lab_data = [];
        $ab_lab_data = [];

        #Guardar tabla de constantes fenomenologicas
        $phenomenological_constants = json_decode($request->input("value_phenomenological_constants"));
        $phenomenological_constants = is_null($phenomenological_constants) ? [] : $phenomenological_constants;
        foreach ($phenomenological_constants as $value) {
            $fines_d_phenomenological_constants = new fines_d_phenomenological_constants;
            $fines_d_phenomenological_constants->fines_d_diagnosis_id = $fines_d_diagnosis->id;
            $fines_d_phenomenological_constants->flow = str_replace(",", ".", $value[0]);
            $fines_d_phenomenological_constants->permeability = str_replace(",", ".", $value[1]);
            $fines_d_phenomenological_constants->k1 = str_replace(",", ".", $value[2]);
            $fines_d_phenomenological_constants->k2 = str_replace(",", ".", $value[3]);
            $fines_d_phenomenological_constants->dp_dl = str_replace(",", ".", $value[4]);
            $fines_d_phenomenological_constants->k3 = str_replace(",", ".", $value[5]);
            $fines_d_phenomenological_constants->k4 = str_replace(",", ".", $value[6]);
            $fines_d_phenomenological_constants->k5 = str_replace(",", ".", $value[7]);
            $fines_d_phenomenological_constants->dp_dl2 = str_replace(",", ".", $value[8]);
            $fines_d_phenomenological_constants->sigma = str_replace(",", ".", $value[9]);
            $fines_d_phenomenological_constants->k6 = str_replace(",", ".", $value[10]);
            $fines_d_phenomenological_constants->ab_2 = str_replace(",", ".", $value[11]);
            $fines_d_phenomenological_constants->ab = str_replace(",", ".", $value[12]);
            $fines_d_phenomenological_constants->save();

            #Agregando datos para módulo de cálculo
            array_push($qlab_data, $fines_d_phenomenological_constants->flow);
            array_push($permeability_lab_data, $fines_d_phenomenological_constants->permeability);
            array_push($k1_lab_data, $fines_d_phenomenological_constants->k1);
            array_push($k2_lab_data, $fines_d_phenomenological_constants->k2);
            array_push($dpdl_lab_data, $fines_d_phenomenological_constants->dp_dl);
            array_push($k3_lab_data, $fines_d_phenomenological_constants->k3);
            array_push($k4_lab_data, $fines_d_phenomenological_constants->k4);
            array_push($k5_lab_data, $fines_d_phenomenological_constants->k5);
            array_push($dpdls_lab_data, $fines_d_phenomenological_constants->dp_dl2);
            array_push($sigma_lab_data, $fines_d_phenomenological_constants->sigma);
            array_push($k6_lab_data, $fines_d_phenomenological_constants->k6);
            array_push($ab2_lab_data, $fines_d_phenomenological_constants->ab_2);
            array_push($ab_lab_data, $fines_d_phenomenological_constants->ab);

        }

        /* Módulo de cálculo */
        #Datos PVT
        $pvt_data = [$pressure_data, $density_data, $oil_viscosity_data, $oil_volumetric_factor_data];
        
        #Datos históricos
        $historical_data = [$dates_data, $bopd_data];
        
        #Datos Finos
        $fines_data = [$qlab_data, $permeability_lab_data, $k1_lab_data, $k2_lab_data, $dpdl_lab_data, $k3_lab_data, $k4_lab_data, $k5_lab_data, $dpdls_lab_data, $sigma_lab_data, $k6_lab_data, $ab2_lab_data, $ab_lab_data];

        $rdre = floatval($fines_d_diagnosis->drainage_radius);
        $hf = floatval($fines_d_diagnosis->formation_height);
        $rw = floatval($fines_d_diagnosis->well_radius);
        $pact = floatval($fines_d_diagnosis->current_pressure);
        $pini = floatval($fines_d_diagnosis->initial_pressure);
        $phio = floatval($fines_d_diagnosis->initial_porosity);
        $ko = floatval($fines_d_diagnosis->initial_permeability);
        $rhop = floatval($fines_d_diagnosis->fine_density);
        $coi = floatval($fines_d_diagnosis->initial_fines_concentration_in_fluid);
        $sigmai = floatval($fines_d_diagnosis->initial_deposited_fines_concentration);
        $tcri = floatval($fines_d_diagnosis->critical_rate);
        $fmov = $fines_d_diagnosis->type_of_suspension_flux;
        if ($fines_d_diagnosis->perforation_density == null) { $tpp = null; } else { $tpp = floatval($fines_d_diagnosis->perforation_density); }
        if ($fines_d_diagnosis->perforation_radius == null) { $rp = null; } else { $rp = floatval($fines_d_diagnosis->perforation_radius); }
        $porosity_limit_constant = floatval($fines_d_diagnosis->porosity_limit_constant);
        if ($fines_d_diagnosis->water_volumetric_factor == null) { $bw = null; } else { $bw = floatval($fines_d_diagnosis->water_volumetric_factor); }
        if ($fines_d_diagnosis->plug_radius == null) { $rplug = null; } else { $rplug = floatval($fines_d_diagnosis->plug_radius); }

        try {
            if (!$button_wr) {
                $simulation_results = $this->run_simulation($rdre, $hf, $rw, $pact, $pini, $phio, $ko, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data, $porosity_limit_constant, $bw, $rplug);

                if($simulation_results[0] === false) {
                    return $simulation_results[1];
                }

                #Agregando datos para módulo de cálculo
                $historical_projection_data = json_decode($request->input("value_historical_projection_data"));
                $historical_data = array_merge($historical_data, $historical_projection_data);
                
                /* Guardando resultados */
                $properties_results = $simulation_results[0];
                $skin_results = $simulation_results[1];

                /* Optimizando consultas */
                foreach ($skin_results as $key => $value) {
                    $fines_diagnosis_results_inserts = [];
                    $fines_diagnosis_results_skin_inserts = [];

                    array_push($fines_diagnosis_results_skin_inserts, array('fines_d_diagnosis_id'=>$fines_d_diagnosis->id, 'date'=>$value[0], 'damage_radius'=>round($value[1], 3), 'skin'=>round($value[2], 4)));
                    $properties_value = $properties_results[$key - 1];

                    foreach ($properties_value as $value_aux) {
                        array_push($fines_diagnosis_results_inserts, array('fines_d_diagnosis_id'=>$fines_d_diagnosis->id, 'radius'=>round($value_aux[0], 7), 'porosity'=>round($value_aux[2], 7), 'permeability'=>round($value_aux[3], 7), 'co'=>round($value_aux[4], 7), 'date'=>$value[0]));
                    }

                    DB::table('fines_d_diagnosis_results')->insert($fines_diagnosis_results_inserts);
                    DB::table('fines_d_diagnosis_results_skin')->insert($fines_diagnosis_results_skin_inserts);
                }
            }

            #Guardar tabla de historicos sin proyección
            fines_d_historical_data::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();
            $historical_data = json_decode($request->input("value_historical_data"));
            $historical_data = is_null($historical_data) ? [] : $historical_data;
            foreach ($historical_data as $value) {
                $fines_d_historical_data = new fines_d_historical_data;
                $fines_d_historical_data->fines_d_diagnosis_id = $fines_d_diagnosis->id;
                $fines_d_historical_data->date = date("Y/m/d", strtotime($value[0]));
                $fines_d_historical_data->bopd = str_replace(",", ".", $value[1]);
                $fines_d_historical_data->save();
            }

            $constantite = array(1 => 0.0005, 0.005, 0.01, 0.1, 0.2, 0.3, 0.4, 0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 5.0);

            $source = "store";
            return View::make('results_fines_migration_diagnosis',compact(['pozo', 'formacion', 'fluido', 'scenaryId','campo', 'cuenca','scenary','user', 'advisor', 'dates_data', 'fines_d_diagnosis', 'source', 'constantite']));
        }
        catch (Exception $e) 
        {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        #Mostrar el modulo desde los resultados de los demas modulos de asfaltenos. Agregar o editar segun el caso.
        $fines_d_diagnosis = DB::table('fines_d_diagnosis')->where('scenario_id', $id)->first();

        if($fines_d_diagnosis){
            return \Redirect::route('finesMigrationDiagnosis.edit',$id); 
        }else{
            return \Redirect::action('add_fines_migration_diagnosis_controller@index', array('scenaryId'=>$id));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_results($id)
    {
        $scenaryId = $id;
        $scenary = DB::table('escenarios')->where('id',$scenaryId)->first();

        #Variables para barra de informacion
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        
        $user = DB::table('users')->select('users.fullName')->where('id','=',$scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        $fines_d_diagnosis = DB::table('fines_d_diagnosis')->where('scenario_id', $id)->first();

        $dates_data = [];
        $fines_d_diagnosis_data = DB::table('fines_d_historical_data')->where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->select('date')->get();
        
        foreach ($fines_d_diagnosis_data as $value) {
            array_push($dates_data, $value->date);
        }

        $source = "show_results";

        $constantite = array(1 => 0.0005, 0.005, 0.01, 0.1, 0.2, 0.3, 0.4, 0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 5.0);

        return View::make('results_fines_migration_diagnosis',compact(['pozo', 'formacion', 'fluido', 'scenaryId','campo', 'cuenca','scenary','user', 'advisor', 'dates_data', 'fines_d_diagnosis', 'source', 'constantite']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd('dentro de edit', $id);
        $fines_d_diagnosis = fines_d_diagnosis::where('scenario_id', '=', $id)->first();

        #Variables para barra de informacion
        $scenary = DB::table('escenarios')->where('id',$id)->first();
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $scenaryId = $id;
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        $user = DB::table('users')->select('users.fullName')->where('id','=',$scenary->user_id)->first();

        $advisor = $scenary->enable_advisor;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        return View::make('edit_fines_migration_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId','campo', 'cuenca','scenary','user', 'advisor', 'fines_d_diagnosis', 'duplicateFrom']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(fines_migration_diagnosis_request $request, $id)
    {
        if (!empty($request->duplicateFrom)) {
            $scenaryId = $request->duplicateFrom;
            $fines_d_diagnosis = new fines_d_diagnosis();
        } else {
            $fines_d_diagnosis = fines_d_diagnosis::where("id", "=", $id)->first();
            $scenaryId = $fines_d_diagnosis->scenario_id;
        }

        $scenary = DB::table('escenarios')->where('id',$scenaryId)->first();

        #Variables para barra de informacion
        $pozo = DB::table('pozos')->where('id', $scenary->pozo_id)->first();
        $formacion = DB::table('formacionxpozos')->where('id', $scenary->formacion_id)->select('nombre')->first();
        $campo = DB::table('campos')->where('id', $scenary->campo_id)->select('nombre')->first();
        $fluido = DB::table('fluidoxpozos')->where('pozo_id', $pozo->id)->first();
        $cuenca = DB::table('cuencas')->where('id', $scenary->cuenca_id)->select('nombre')->first();
        
        $user = DB::table('users')->select('users.fullName')->where('id','=',$scenary->user_id)->first();
        $advisor = $scenary->enable_advisor;

        #Saber si se entro por lo menos una vez al escenario
        $scenary = escenario::find($scenary->id);
        $scenary->estado=1;
        $scenary->completo=1;
        $scenary->save();

        $fines_d_diagnosis->drainage_radius = $request->input('drainage_radius');
        $fines_d_diagnosis->formation_height = $request->input('formation_height');
        $fines_d_diagnosis->well_radius = $request->input('well_radius');
        if ($request->input('perforation_radius') === "") { $fines_d_diagnosis->perforation_radius = null; } else { $fines_d_diagnosis->perforation_radius = $request->input('perforation_radius'); }
        if ($request->input('perforation_density') === "") { $fines_d_diagnosis->perforation_density = null; } else { $fines_d_diagnosis->perforation_density = $request->input('perforation_density'); }
        // $fines_d_diagnosis->compressibility = $request->input('compressibility');
        $fines_d_diagnosis->initial_porosity = $request->input('initial_porosity');
        // $fines_d_diagnosis->porosity_limit_constant = $request->input('porosity_limit_constant');
        $fines_d_diagnosis->initial_permeability = $request->input('initial_permeability'); 
        $fines_d_diagnosis->initial_pressure = $request->input('initial_pressure');
        $fines_d_diagnosis->current_pressure = $request->input('current_pressure');
        $fines_d_diagnosis->type_of_suspension_flux = $request->input('type_of_suspension_flux');
        $fines_d_diagnosis->fine_density = $request->input('fine_density');
        $fines_d_diagnosis->initial_deposited_fines_concentration = $request->input('initial_deposited_fines_concentration');
        $fines_d_diagnosis->critical_rate = $request->input('critical_rate');
        $fines_d_diagnosis->initial_fines_concentration_in_fluid = $request->input('initial_fines_concentration_in_fluid');
        if ($request->input('water_volumetric_factor') === "") { $fines_d_diagnosis->water_volumetric_factor = null; } else { $fines_d_diagnosis->water_volumetric_factor = $request->input('water_volumetric_factor'); }
        if ($request->input('plug_radius') === "") { $fines_d_diagnosis->plug_radius = null; }else{ $fines_d_diagnosis->plug_radius = $request->input('plug_radius'); }
        $fines_d_diagnosis->length = $request->input('length');
        $fines_d_diagnosis->diameter = $request->input('diameter');
        $fines_d_diagnosis->porosity = $request->input('porosity');
        $fines_d_diagnosis->illite = $request->input('illite');
        $fines_d_diagnosis->kaolinite = $request->input('kaolinite');
        $fines_d_diagnosis->chlorite = $request->input('chlorite');
        $fines_d_diagnosis->emectite = $request->input('emectite');
        $fines_d_diagnosis->total_amount_of_clays = $request->input('total_amount_of_clays');
        $fines_d_diagnosis->quartz = $request->input('quartz');
        $fines_d_diagnosis->feldspar = $request->input('feldspar');
        $fines_d_diagnosis->final_date = date("Y/m/d", strtotime($request->input('final_date')));
        $button_wr = isset($_POST['button_wr']);
        $fines_d_diagnosis->status_wr = $button_wr;
        $fines_d_diagnosis->perform_historical_projection = $request->input('perform_historical_projection_oil');
        $fines_d_diagnosis->scenario_id = $scenary->id;
        $fines_d_diagnosis->save();

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $pressure_data = [];
        $density_data = [];
        $oil_viscosity_data = [];
        $oil_volumetric_factor_data = [];

        unset($_SESSION['scenary_id_dup']);

        fines_d_pvt::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();
        $pvt_data = json_decode($request->input("value_pvt_data"));
        $pvt_data = is_null($pvt_data) ? [] : $pvt_data;

        #Guardar tabla PVT segun caso agua o aceite
        foreach ($pvt_data as $value) {
            $fines_d_pvt = new fines_d_pvt;
            $fines_d_pvt->fines_d_diagnosis_id = $fines_d_diagnosis->id;
            $fines_d_pvt->pressure = str_replace(",", ".", $value[0]);
            $fines_d_pvt->oil_density = str_replace(",", ".", $value[1]);
            $fines_d_pvt->oil_viscosity = str_replace(",", ".", $value[2]);
            $fines_d_pvt->volumetric_oil_factor = str_replace(",", ".", $value[3]);
            $fines_d_pvt->save();

            #Agregando datos para módulo de cálculo
            array_push($pressure_data, $fines_d_pvt->pressure);
            array_push($density_data, $fines_d_pvt->oil_density);
            array_push($oil_viscosity_data, $fines_d_pvt->oil_viscosity);
            array_push($oil_volumetric_factor_data, $fines_d_pvt->volumetric_oil_factor);
        }

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $dates_data = [];
        $bopd_data = [];

        #Arreglos para guardar los datos organizados - módulo de cálculo
        $dates_data = [];
        $bopd_data = [];

        #Guardar tabla de historicos
        fines_d_historical_data::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();
        $historical_data = json_decode($request->input("value_historical_data"));
        $historical_data = is_null($historical_data) ? [] : $historical_data;
        $historical_projection_data = json_decode($request->input("value_historical_projection_data"));
        $historical_data = array_merge($historical_data, $historical_projection_data);
        foreach ($historical_data as $value) {
            $fines_d_historical_data = new fines_d_historical_data;
            $fines_d_historical_data->fines_d_diagnosis_id = $fines_d_diagnosis->id;
            $fines_d_historical_data->date = date("Y/m/d", strtotime($value[0]));
            $fines_d_historical_data->bopd = str_replace(",", ".", $value[1]);
            $fines_d_historical_data->save();
        
            array_push($dates_data, $value[0]);
            array_push($bopd_data, $fines_d_historical_data->bopd);
        }
        
        #Arreglos para guardar los datos organizados - módulo de cálculo
        $qlab_data = [];
        $permeability_lab_data = [];
        $k1_lab_data = [];
        $k2_lab_data = [];
        $dpdl_lab_data = [];
        $k3_lab_data = [];
        $k4_lab_data = [];
        $k5_lab_data = [];
        $dpdls_lab_data = [];
        $sigma_lab_data = [];
        $k6_lab_data = [];
        $ab2_lab_data = [];
        $ab_lab_data = [];

        #Guardar tabla de constantes fenomenologicas
        fines_d_phenomenological_constants::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();
        $phenomenological_constants = json_decode($request->input("value_phenomenological_constants"));
        $phenomenological_constants = is_null($phenomenological_constants) ? [] : $phenomenological_constants;
        foreach ($phenomenological_constants as $value) {
            $fines_d_phenomenological_constants = new fines_d_phenomenological_constants;
            $fines_d_phenomenological_constants->fines_d_diagnosis_id = $fines_d_diagnosis->id;
            $fines_d_phenomenological_constants->flow = str_replace(",", ".", $value[0]);
            $fines_d_phenomenological_constants->permeability = str_replace(",", ".", $value[1]);
            $fines_d_phenomenological_constants->k1 = str_replace(",", ".", $value[2]);
            $fines_d_phenomenological_constants->k2 = str_replace(",", ".", $value[3]);
            $fines_d_phenomenological_constants->dp_dl = str_replace(",", ".", $value[4]);
            $fines_d_phenomenological_constants->k3 = str_replace(",", ".", $value[5]);
            $fines_d_phenomenological_constants->k4 = str_replace(",", ".", $value[6]);
            $fines_d_phenomenological_constants->k5 = str_replace(",", ".", $value[7]);
            $fines_d_phenomenological_constants->dp_dl2 = str_replace(",", ".", $value[8]);
            $fines_d_phenomenological_constants->sigma = str_replace(",", ".", $value[9]);
            $fines_d_phenomenological_constants->k6 = str_replace(",", ".", $value[10]);
            $fines_d_phenomenological_constants->ab_2 = str_replace(",", ".", $value[11]);
            $fines_d_phenomenological_constants->ab = str_replace(",", ".", $value[12]);
            $fines_d_phenomenological_constants->save();

            #Agregando datos para módulo de cálculo
            array_push($qlab_data, $fines_d_phenomenological_constants->flow);
            array_push($permeability_lab_data, $fines_d_phenomenological_constants->permeability);
            array_push($k1_lab_data, $fines_d_phenomenological_constants->k1);
            array_push($k2_lab_data, $fines_d_phenomenological_constants->k2);
            array_push($dpdl_lab_data, $fines_d_phenomenological_constants->dp_dl);
            array_push($k3_lab_data, $fines_d_phenomenological_constants->k3);
            array_push($k4_lab_data, $fines_d_phenomenological_constants->k4);
            array_push($k5_lab_data, $fines_d_phenomenological_constants->k5);
            array_push($dpdls_lab_data, $fines_d_phenomenological_constants->dp_dl2);
            array_push($sigma_lab_data, $fines_d_phenomenological_constants->sigma);
            array_push($k6_lab_data, $fines_d_phenomenological_constants->k6);
            array_push($ab2_lab_data, $fines_d_phenomenological_constants->ab_2);
            array_push($ab_lab_data, $fines_d_phenomenological_constants->ab);
        }

        #Datos PVT
        $pvt_data = [$pressure_data, $density_data, $oil_viscosity_data, $oil_volumetric_factor_data];
        
        #Datos históricos
        $historical_data = [$dates_data, $bopd_data];
        
        #Datos Finos
        $fines_data = [$qlab_data, $permeability_lab_data, $k1_lab_data, $k2_lab_data, $dpdl_lab_data, $k3_lab_data, $k4_lab_data, $k5_lab_data, $dpdls_lab_data, $sigma_lab_data, $k6_lab_data, $ab2_lab_data, $ab_lab_data];

        $rdre = floatval($fines_d_diagnosis->drainage_radius);
        $hf = floatval($fines_d_diagnosis->formation_height);
        $rw = floatval($fines_d_diagnosis->well_radius);
        $pact = floatval($fines_d_diagnosis->current_pressure);
        $pini = floatval($fines_d_diagnosis->initial_pressure);
        $phio = floatval($fines_d_diagnosis->initial_porosity);
        $ko = floatval($fines_d_diagnosis->initial_permeability);
        $rhop = floatval($fines_d_diagnosis->fine_density);
        $coi = floatval($fines_d_diagnosis->initial_fines_concentration_in_fluid);
        $sigmai = floatval($fines_d_diagnosis->initial_deposited_fines_concentration);
        $tcri = floatval($fines_d_diagnosis->critical_rate);
        $fmov = $fines_d_diagnosis->type_of_suspension_flux;
        if ($fines_d_diagnosis->perforation_density == null) { $tpp = null; } else { $tpp = floatval($fines_d_diagnosis->perforation_density); }
        if ($fines_d_diagnosis->perforation_radius == null) { $rp = null; } else { $rp = floatval($fines_d_diagnosis->perforation_radius); }
        $porosity_limit_constant = floatval($fines_d_diagnosis->porosity_limit_constant);
        if ($fines_d_diagnosis->water_volumetric_factor == null) { $bw = null; } else { $bw = floatval($fines_d_diagnosis->water_volumetric_factor); }
        if ($fines_d_diagnosis->plug_radius == null) { $rplug = null; } else { $rplug = floatval($fines_d_diagnosis->plug_radius); }

        try 
        {
            if (!$button_wr) {
                /* dd(json_encode(array($rdre, $hf, $rw, $cr, $pini, $phio, $ko, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data, $porosity_limit_constant))); */
                //dd('rdre', $rdre,'hf', $hf,'rw', $rw,'cr', $cr,'pini', $pini,'phio', $phio,'ko', $ko,'rhop', $rhop,'coi', $coi,'sigmai', $sigmai,'tcri', $tcri,'fmov', $fmov,'tpp', $tpp,'rp', $rp,'pvt_data', $pvt_data,'historical_data', $historical_data,'fines_data', $fines_data,'porosity_limit_constant' $porosity_limit_constant);
                $simulation_results = $this->run_simulation($rdre, $hf, $rw, $pact, $pini, $phio, $ko, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data, $porosity_limit_constant, $bw, $rplug);

                if($simulation_results[0] === false) {
                    return $simulation_results[1];
                }

                #Agregando datos para módulo de cálculo
                $historical_projection_data = json_decode($request->input("value_historical_projection_data"));
                $historical_data = array_merge($historical_data, $historical_projection_data);

                /* Guardando resultados */
                $properties_results = $simulation_results[0];
                $skin_results = $simulation_results[1];

                /* Eliminando resultados anteriores */
                fines_d_diagnosis_results::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();
                fines_d_diagnosis_results_skin::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();

                /* Optimizando consultas */
                foreach ($skin_results as $key => $value) 
                {
                    $fines_diagnosis_results_inserts = [];
                    $fines_diagnosis_results_skin_inserts = [];

                    array_push($fines_diagnosis_results_skin_inserts, array('fines_d_diagnosis_id'=>$fines_d_diagnosis->id, 'date'=>$value[0], 'damage_radius'=>round($value[1], 7), 'skin'=>round($value[2], 7)));
                    $properties_value = $properties_results[$key - 1];

                    foreach ($properties_value as $value_aux) 
                    {
                        array_push($fines_diagnosis_results_inserts, array('fines_d_diagnosis_id'=>$fines_d_diagnosis->id, 'radius'=>round($value_aux[0], 7), 'porosity'=>round($value_aux[2], 7), 'permeability'=>round($value_aux[3], 7), 'co'=>round($value_aux[4], 7), 'date'=>$value[0]));
                    }

                    DB::table('fines_d_diagnosis_results')->insert($fines_diagnosis_results_inserts);
                    DB::table('fines_d_diagnosis_results_skin')->insert($fines_diagnosis_results_skin_inserts);
                }
            }

            #Guardar tabla de historicos sin proyección
            fines_d_historical_data::where('fines_d_diagnosis_id', $fines_d_diagnosis->id)->delete();
            $historical_data = json_decode($request->input("value_historical_data"));
            $historical_data = is_null($historical_data) ? [] : $historical_data;
            foreach ($historical_data as $value) {
                $fines_d_historical_data = new fines_d_historical_data;
                $fines_d_historical_data->fines_d_diagnosis_id = $fines_d_diagnosis->id;
                $fines_d_historical_data->date = date("Y/m/d", strtotime($value[0]));
                $fines_d_historical_data->bopd = str_replace(",", ".", $value[1]);
                $fines_d_historical_data->save();
            }

            $source = "update";

            $constantite = array(1 => 0.0005, 0.005, 0.01, 0.1, 0.2, 0.3, 0.4, 0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 5.0);

            return View::make('results_fines_migration_diagnosis', compact(['pozo', 'formacion', 'fluido', 'scenaryId','campo', 'cuenca','scenary','user', 'advisor', 'fines_d_diagnosis', 'dates_data', 'fines_d_diagnosis', 'source', 'constantite']));
        }
        catch (Exception $e) 
        {
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

    /* Módulo de cálculo --> empieza en run_simulation() */
    /*function concentration_change($rw, $l, $hf, $cr, $qwi, $nx, $rc, $bo, $phin, $phic, $uc, $dt, $con, $deadt, $dphi, $rl)
    { 
        $v = array_fill(1,100,0); 
        $radio = array_fill(0,100,0); 
        $vporo = array_fill(1,100,0); 
        $vc = array_fill(1,100,0); 
        $u = array_fill(1,100,0);
        $deadt_co = array_fill(1,100,0); 
        $dphi_co = array_fill(1,100,0); 
        $coc = array_fill(1,100,0); 
        $phi_co = array_fill(1,100,0); 
        $bo_co = array_fill(1,100,0); 
        $dbo = array_fill(1,100,0);
        $cpoo = array_fill(1,100,0); 
        $cswo = array_fill(1,100,0); 
        $cpow = array_fill(1,100,0); 
        $csww = array_fill(1,100,0);
        $a = array_fill(1,100,0); 
        $c = array_fill(1,100,0); 
        $b = array_fill(1,100,0); 
        $d = array_fill(1,100,0); 
        $bb = array_fill(1,100,0); 
        $dd = array_fill(1,100,0); 
        $p = array_fill(1,100,0);

        $dv = $l / 100;
        $v[1] = 0.001;
        $radio[1] = $v[1];
        for ($i=2; $i <= 100 ; $i++) 
        { 
            $v[$i] = $v[$i - 1] + $dv;
            $radio[$i] = $v[$i];
        }
        $area = $dv * $hf;

        for ($i=1; $i <= 100 ; $i++) 
        { 
            $uci = $this->interpolation($radio[$i], $nx, $rc, $uc);
            $coi = $this->interpolation($radio[$i], $nx, $rc, $con);
            $deadti = $this->interpolation($radio[$i], $nx, $rc, $deadt);
            $dphii = $this->interpolation($radio[$i], $nx, $rc, $dphi);
            $phici = $this->interpolation($radio[$i], $nx, $rc, $phic);
            $boi = $this->interpolation($radio[$i], $nx, $rc, $bo);
            $rl[$i] = $radio[$i];
            $u[$i] = 0.0000092903 * $uci;
            $coc[$i] = $coi;
            $deadt_co[$i] = $deadti;
            $dphi_co[$i] = $dphii;
            $phi_co[$i] = $phici;
            $bo_co[$i] = $boi;
        }

        $dbo[1] = ($bo_co[2] - $bo_co[1]) / ($rl[2] - $rl[1]);
        $dbo[100] = ($bo_co[100] - $bo_co[99]) / ($rl[100] - $rl[99]);

        for ($i=2; $i < 100 ; $i++) 
        { 
            $dbo[$i] = ($bo_co[$i] - $bo_co[$i - 1]) / ($rl[$i] - $rl[$i - 1]);
        }

        for ($i=1; $i <= 100 ; $i++) 
        { 
            #-----------------------------------storage coefficients
            $cpoo[$i] = (1.0 - $con[$i]) * $phin[$i] * ($cr * $bo_co[$i] + $dbo[$i]) / $dt;
            $cswo[$i] = -$phic[$i] * $bo_co[$i] / $dt;
            $cpow[$i] = $con[$i] * $phic[$i] * $cr / $dt;
            $csww[$i] = $phic[$i] / $dt;
            #-----------------------------------matrix coefficients
            $alfa = -$cswo[$i] / $csww[$i];
            $a[$i] = $u[$i] + $alfa * $u[$i];
            $c[$i] = $u[$i] + $alfa * $u[$i];
            if ($i != 1)
            {
                $b[$i] = -(2 * $u[$i] + $cpoo[$i]) - (2 * $u[$i] + $cpow[$i]) * $alfa;
            }

            if ($i == 1)
            {
                $d[$i] = -($cpoo[$i] + $alfa * $cpow[$i]) * $coc[$i] + $alfa * (-$qwi / $dv / $area);
                $b[$i] = -($u[$i] + $cpoo[$i]) - (2 * $u[$i]) * $alfa;
            }
            else if ($i == 100)
            {
                $d[$i] = -($cpoo[$i] + $alfa * $cpow[$i]) * $coc[$i] - ($u[$i] + $alfa * $u[$i]) * $coc[100];
            } 
            else
            {
                $d[$i] = -($cpoo[$i] + $alfa * $cpow[$i]) * $coc[$i];
            }
        }

        $bb[1] = $b[1];
        $dd[1] = $d[1];
        for ($i=2; $i <= 100 ; $i++) 
        { 
          $x = $a[$i] / $bb[$i - 1];
          $bb[$i] = $b[$i] - $x * $c[$i - 1];
          $dd[$i] = $d[$i] - $x * $dd[$i - 1];
      }

      $co[100] = $dd[100] / $bb[100];
      for ($kk=2; $kk <= 100 ; $kk++) 
      { 
          $i = 100 - $kk + 1;
          $co[$i] = ($dd[$i] - $c[$i] * $co[$i + 1]) / $bb[$i];
      }

      return array($co, $rl);
  }*/
 
  function porosity_change($nx, $t, $dt, $ko, $phin, $u, $ucri_esc, $sigmaini, $dp, $rhop, $con, $k1, $k2, $k3, $k4, $k5, $k6, $dpdl, $dpdlc, $sigmai, $ab, $ab2, $porosity_limit_constant)
  {
    $sigma1 = array_fill(1, $nx, 0);
    $dphi = array_fill(1, $nx, 0); 
    $phisw = array_fill(1, $nx, 0); 
    $phip = array_fill(1, $nx, 0); 
    $sigma = array_fill(1, $nx, 0);
    $u_escala = array_fill(1, $nx, 0);
    $dp_escala = array_fill(1, $nx, 0);

    for ($i=1; $i <= $nx ; $i++) 
    {
        $u_escala[$i] = $u[$i] * 0.32774;
        $dp_escala[$i] = $dp[$i] * 0.002232;
    }

        #Rango de constantes fenomenológicas        
        #Cálculo de la tasa de depositacion y la depositacion de finos.
        #Daño de porosidad por hinchamiento.
    $s = $ab * pow($t, -0.5);
    $relperm = $k6 + (1 - $k6) * exp(-$ab2 * pow($t, 0.5));

    for ($i=1; $i <= $nx ; $i++) 
    { 
        //$phisw[$i] = $phin[$i] * pow($relperm, (1.0 / 3.0));
        $phisw[$i] = $phin[$i] * pow(1 - $relperm, (1.0 / 3.0));
        if (-$dp_escala[$i] > -$dpdl)
        {
            $dsigma[$i] = $k1 * $u_escala[$i] * $rhop * $con[$i] * $phin[$i] - ($k2 * $con[$i] * ( (-$dp_escala[$i]) - (-$dpdl) ));
        }
        else
        {
            $dsigma[$i] = $k1 * $u_escala[$i] * $rhop * $con[$i] * $phin[$i];
        }

        if ($u[$i] == 0)
        {
            $sigma[$i] = $sigmaini[$i];
        }
        else
        {
            if ($dsigma[$i] > 0) {
                $sigma[$i] = $sigmaini[$i] + 0.0000092903 * $dsigma[$i] * $dt;
            }else{
                $sigma[$i] = $sigmaini[$i];
            }
        }
    }

        #Cálculo de la tasa de liberación y la liberación de finos
    $sigma1 = $con;
    for ($i=1; $i <= $nx ; $i++) 
    { 
        if(-$dp_escala[$i] > -$dpdl)
        {
            $dsigma1[$i] = $k3 * $sigmai * (1.0 - exp(-0.00092903 * $k4 * pow($t, 0.5))) * exp(-0.00092903 * $k5 * $sigma1[$i]) * ( (-$dp_escala[$i]) - (-$dpdl) );
        }
        else
        {
            $dsigma1[$i] = 0.0;
        }

        if ($u[$i] == 0)
        {
                $sigma1[$i] = $sigma1[$i]; #*
            }
            else
            {
                $sigma1[$i] = $sigma1[$i] + 0.0000092903 * $dt * $dsigma1[$i];
            }
        }

        $sigma2 = $sigma1;

        #Cálculo de la porosidad efectiva y la permeabilidad en el modelo, derivada de porosidad.
        for ($i=1; $i <= $nx ; $i++) 
        { 
            $phip[$i] = $porosity_limit_constant * $sigma[$i] / $rhop;
            //$phip[$i] = $7.92422141219959E-05 * $sigma[$i] / $rhop;
            #beta=((8.91*10^-8)*tao)/(phio*ko)  ---- ajuste del modelo multitasa
            //$dphi[$i] = -$phin[$i] / 3.0 / pow($relperm, (2.0 / 3.0)) * (1.0 - 0.00092903 * $k6) * exp(-$ab * pow($t, 0.5)) * $ab / (2.0 * pow($t, 0.5)) - $dsigma[$i] / $rhop;

            if ($dsigma[$i] != 0)
            {
                //$phic[$i] = $phisw[$i] - $phip[$i];
                $phic[$i] = $phin[$i] - $phisw[$i] - $phip[$i];
            }
            else
            {
                $phic[$i] = $phin[$i];
                //$sigma[$i] = $sigmaini[$i];
            }
            $sigmasal[$i] = $sigma[$i];
        }

        return array($phic, $dsigma, $dsigma1, $sigmasal, $sigma2);
    }

    function rate_scaling($rw, $tcri, $hf, $rplug, $tpp, $rp, $bw)
    {
        $tpp_aux = $tpp;
        #$bw = $volumetricfactor
        #$rplug = $radiodeplug

        #Hueco abierto
        if ($tpp_aux == 0 || $tpp_aux == null) {
            $tcri_esc = 0.009057 * $tcri * (1.0 / $bw) * 2 * $rw * $hf / pow($rplug, 2); #stb/dia

        #Hueco cementado
        } else if ($tpp_aux =! 0) {
            if ($rw < 0.375) {
                $fp3 = (1.036 * $tpp * $rp) / ((0.9932 * $tpp * $rp) + 0.7718);
                $tcri_esc = $fp3 * 0.009057 * $tcri * (1.0 / $bw) * 2 * $rw * $hf / pow($rplug, 2);
            } else {
                $fp6 = 1.0258 * ($tpp * $rp) / (0.9742 * $tpp * $rp + 0.8845);
                $tcri_esc = $fp6 * 0.009057 * $tcri * (1.0 / $bw) * 2 * $rw * $hf / pow($rplug, 2);
            }
        }    

        return $tcri_esc;
    }

    function fines_interpolation($x, $n, $xt, $yt)
    {
        $y = 0.0;

        #interpolación entre dos puntos.
        if ($x < $xt[1])
        {
            $y = $yt[1];
        }

        if ($x > $xt[$n])
        {
            $y = $yt[$n];
        }

        if ($x < $xt[$n])
        {
            for ($i=2; $i <= $n ; $i++) 
            {                 
                if (!($x >= $xt[$i]))
                {
                    $y = $yt[$i - 1];
                    return $y;
                }
                if ($x = $xt[$i])
                {
                    $y = $yt[$i];
                }
            }
        }
        return $y;
    }

    function dateDifference($date_1 , $date_2 , $differenceFormat)
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
        if($x < $xt[1])
        {
            $extrapolation_result = $this->extrapolation($xt, $yt, $n, $x, $y);
            $y = $extrapolation_result[0];
        }
        if($x>$xt[$n])
        {
            $extrapolation_result = $this->extrapolation($xt, $yt, $n, $x, $y);
            $y = $extrapolation_result[0];
        }
        if($x < $xt[$n])
        {
            for ($i=2; $i <= $n ; $i++) 
            { 
                if(!($x >= $xt[$i]))
                {
                    $y = $yt[$i - 1] + ($x - $xt[$i-1]) * ($yt[$i] - $yt[$i - 1]) / ($xt[$i] - $xt[$i - 1]);
                    return $y;
                }
                $aux_i = $i;
            }
        }
        if($x == $xt[$aux_i])
        {
            $y = $yt[$aux_i];
        }

        return $y;
    }

    function extrapolation($xa, $ya, $n, $x)
    {
        //$n_max = 10; -> cambiado
        $n_max = $n;  // prototipo
        //$c = array_fill(1,10,0);
        $c = array_fill(1, $n, 0);
        //$d = array_fill(1,10,0);
        $d = array_fill(1, $n, 0);
        $ns = 1;
        $dif = abs($x-$xa[1]);

        if($x>$xa[$n])
        {
            $y = $ya[$n];
            for ($i=1; $i <= $n_max; $i++) 
            { 
                $c[$i] = $ya[$n_max]; 
                $d[$i] = $ya[$n_max]; 
            }
        }
        else if($x < $xa[$n])
        {
            $y = $ya[1];
            for ($i=1; $i <= $n_max; $i++) 
            { 
                $c[$i] = $ya[$i];
                $d[$i] = $ya[$i];
            }
        }
        else
        {
            for ($i=1; $i <= $n_max; $i++) 
            { 
                $c[$i] = $ya[$i];
                $d[$i] = $ya[$i];
            }
        }

        $ns = $ns - 1;
        for ($m=1; $m < $n_max-1; $m++) 
        { 
            for ($i=1; $i < $n_max - $m; $i++) 
            { 
                $ho = $xa[$i] - $x;
                $hp = $xa[$i + $m] - $x;
                $w = $c[$i + 1] - $d[$i];
                $den = $ho - $hp;
                if($den == 0)
                {
                    #Error
                    return -555;
                }
                $den = $w / $den;
                $d[$i] = $hp * $den;
                $c[$i] = $ho * $den;
            }
            if((2 * $ns) < ($n_max - $m))
            {
                $dy = $c[$ns + 1];
            }
            else
            {
                $dy = $d[$ns];
                $ns = $ns-1;
            }
            $y = $y + $dy;
        }

        return [$y, $dy];
    }
    function run_simulation($rdre, $hf, $rw, $pact, $pini, $phio, $ko, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data, $porosity_limit_constant, $bw, $rplug)   
    {

        #Formateando arreglos - empezando índices en 1 
        #Datos PVT
        foreach ($pvt_data as $key => $value) 
        {
            $pvt_data[$key] = $this->set_array($value, count($value));
        }

        #Datos históricos
        foreach ($historical_data as $key => $value) 
        {
            if($key != 0)
            {
                $historical_data[$key] = $this->set_array($value, count($value));
            }
            else
            {
                $historical_data[$key] = $this->set_array_string($value, count($value));
            }
        }

        #Datos finos
        foreach ($fines_data as $key => $value) 
        {
            $fines_data[$key] = $this->set_array($value, count($value));
        }

        $simulation_results = $this->simulate_deposited_fines($rdre, $hf, $rw, $pact, $pini, $phio, $ko, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data, $porosity_limit_constant, $bw, $rplug);
        // dd('run simulation');
        return $simulation_results;
    }
    function simulate_deposited_fines($rdre, $hf, $rw, $pact, $pini, $phio, $ko, $rhop, $coi, $sigmai, $tcri, $fmov, $tpp, $rp, $pvt_data, $historical_data, $fines_data, $porosity_limit_constant, $bw, $rplug) 
    {
        set_time_limit(1800); //Cambiar
        $complete_simulated_results = [];

        $nv = count($pvt_data[0]) - 1;
        $ppvt = $pvt_data[0];
        $dopvt = $pvt_data[1];
        $uopvt = $pvt_data[2];
        $bopvt = $pvt_data[3];

        $simgaineverchanges = $sigmai;

        //dd('ppvt', $ppvt, 'dopvt', $dopvt, 'uopvt', $uopvt, 'bopvt', $bopvt);

        #Revisar ajuste nh
        $nh = count($historical_data[0]) - 1;
        $hist = $historical_data[0];
        $bopd = $historical_data[1];

        $ns = count($fines_data[0]) - 1;
        $qlab = $fines_data[0];
        $permeability_lab = $fines_data[1];
        $k1_lab = $fines_data[2];
        $k2_lab = $fines_data[3];
        $dpdl_lab = $fines_data[4];
        $k3_lab = $fines_data[5];
        $k4_lab = $fines_data[6];
        $k5_lab = $fines_data[7];
        $dpdls_lab = $fines_data[8];
        $sigma_lab = $fines_data[9];
        $k6_lab = $fines_data[10];
        $ab2_lab = $fines_data[11];
        $ab_lab = $fines_data[12];

        $pi = 3.14159265359;
        $x = 0;
        $ki = [];
        
        #Controlar variables
        if ($bw == null) {
            $bw = 1.1;
        }
        if ($rplug == null) {
            $rplug = 0.061; #Medición estandar para un núcleo de laboratorio [ft]
        }

        #Escalamiento de tasa crítica escalada
        $tcri_esc = $this->rate_scaling($rw, $tcri, $hf, $rplug, $tpp, $rp, $bw);

        #Conversión tasas de laboratorio
        for ($i=1; $i <= $ns ; $i++) 
        { 
            $qlab[$i] = $this->rate_scaling($rw, $qlab[$i], $hf, $rplug, $tpp, $rp, $bw);
        }

        #Discretizando el medio (geometría radial)
        $nr = 500;
        $ri = 0;

        $r = array_fill(1, $nr, 0); 
        $dr = array_fill(1, $nr, 0);
        $r1 = array_fill(1, $nr, 0);
        $dr1 = array_fill(1, $nr - 1, 0);
        $alfa = pow(($rdre / $rw), (1 / ($nr - 1)));
        $r[1] = $rw;

        //dd($r[1], $alfa);

        for ($i=2; $i <= $nr ; $i++) 
        { 
            $r[$i] = $alfa * $r[$i - 1];
        }

        //dd($r, $alfa);

        for ($i=1; $i < $nr; $i++) 
        //for ($i=1; $i < ($nr - 1); $i++) 
        { 
            $dr1[$i] = $r[$i + 1] - $r[$i];
        }

        for ($i=1; $i <= $nr ; $i++) 
        { 
            if ($i == $nr)
            {
                $r1[$i] = $rdre;
            }
            else
            {   
                $r1[$i] = (($alfa - 1) * $r[$i]) / (log($alfa));
                if ($r1[$i] < $ri)
                {
                    $x = $i;
                }
            }
        }

        for ($i=1; $i <= $nr ; $i++) 
        { 
            if ($i == 1)
            {
                $dr[$i] = $r1[$i] - $r[$i];
            }
            else
            {
                $dr[$i] = $r1[$i] - $r1[$i - 1];
            }
        }

        #Inicializando varibles iniciales
        $pn = array_fill(1, $nr, $pini); 
        $phin = array_fill(1, $nr, $phio); 
        $kn = array_fill(1, $nr, $ko); 
        $co = array_fill(1, $nr, $coi); 
        $cod = array_fill(1, $nr, 0); 
        $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
        $bo = array_fill(1, $nr, 0);
        $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);

        //HASTA AQUÍ LLEGAMOS

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
        $cod = array_fill(1, $nr, 0); 
        $sigmasal = array_fill(1, $nr, 0);
        $cri = array_fill(1, 6, 0);
        $pite = array_fill(1, 4, 0);
        $crite = array_fill(1, 4, 0);
        $dsigma = array_fill(1, $nr, 0); 
        $dsigma1 = array_fill(1, $nr, 0); 
        $rdamage = array_fill(1, $nr, 0);
        $tiempo = array_fill(1, $nh, 0);

        $n = 0.5;
        $dt = 30;
        $un = 3;

        #Delta de tiempo
        for ($i=1; $i <= $nh ; $i++) 
        { 
            if ($i == 1)
            {
                $tiempo[$i] = 30;
            }
            else
            {
                $tiempo[$i] = floatval($this->dateDifference($hist[$i], $hist[$i - 1], "%a"));
            }
        }

        #Variables nuevas
        $cri = array(1 => 0.1, 0.005, 0.001, 0.0005, 0.0001, 0.000005, 0.000001, 0.0000005);
        $pite = array(1 => 0, 0, 0, 0, 0);
        $crite = array(1 => 0.1, 0.005, 0.001, 0.0005, 0.0001);
        $cr = $cri[1];
        $porosity_limit_constanti = array(1 => 0.0005, 0.005, 0.01, 0.1, 0.2, 0.3, 0.4, 0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 5.0, 0);
        $porosity_limit_constantite = array(1 => 0.0005, 0.005, 0.01, 0.1, 0.2, 0.3, 0.4, 0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 5.0);

        //$porosity_limit_constanti = array(1 => 0.0005, 0.001, 0.005, 0.01, 0.5, 0);
        //$porosity_limit_constantite = array(1 => 0.0005, 0.001, 0.005, 0.01, 0.5);
        $kite = array(1 => 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $porosity_limit_constant = $porosity_limit_constanti[1];
        $flag_ran_xx_7 = 0;

        for ($xx = 1; $xx <= 7; $xx++) {
            for ($kk=1; $kk <= $nh; $kk++)
            {
                $ndt = $tiempo[$kk] / $dt;
                $qo = -$bopd[$kk];

                for ($v=1; $v <= $ndt ; $v++) 
                {   
                    #coeficientes matriz tridiagonal
                    $i = 1;
                    while ($i == $x + 1)
                    {
                        $i = $i + 1;
                        $b[$i] = pow($r1[$i - 1], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i - 1]);
                    }

                    for ($i= $x + 2; $i <= $nr ; $i++) 
                    { 
                        $b[$i] = $r1[$i - 1] / ($r[$i] * $dr[$i] * $dr1[$i - 1]);
                    }

                    $i = 0;
                    if ($x == 0)
                    {
                        $x = 1;
                    }

                    while ($i == $x - 1)
                    {
                        $i = $i + 1;
                        $a[$i] = pow($r1[$i], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i]);
                    }

                    for ($i=$x; $i < $nr ; $i++)
                    //for ($i=$x; $i < $nr - 1 ; $i++)
                    { 
                        $a[$i] = $r1[$i] / ($r[$i] * $dr[$i] * $dr1[$i]);
                    }

                    $i = 0;

                    while ($i == $x)
                    {
                        $i = $i + 1;
                        $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                        $g1 = 3792.58489625175 * $n * $phin[$i] * $cr / $kn[$i];
                        $f[$i] = $g1 * $mu / $dt; #g1 * uapp[$i] / dt
                    }

                    for ($i=$x + 1; $i < $nr ; $i++) 
                    //for ($i=$x + 1; $i < $nr - 1; $i++) 
                    { 
                        $g2 = 3792.58489625175 * $phin[$i] * $cr * $un / $kn[$i];
                        $f[$i] = $g2 / $dt;
                    }
                    
                    if ($ri > 0 and $ri < $re)
                    {
                        $b[$x] = $dr1[$x] / $dr1[$x - 1];
                        $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                        $a[$x] = $mu / $un; #uapp[$x] / un
                        $f[$x] = 0;
                    }


                    $c[1] = -($a[1] + $f[1]);
                    $c[$nr] = -($b[$nr] + $f[$nr]);

                    for ($i=2; $i < $nr ; $i++) 
                    //for ($i=2; $i < $nr - 1; $i++) 
                    { 
                        $c[$i] = -($a[$i] + $b[$i] + $f[$i]);
                    }

                    for ($i=1; $i <= $nr ; $i++) 
                    { 
                        if ($i == 1)
                        {
                            $beta = $this->interpolation($pn[$i], $nv, $ppvt, $bopvt);
                            //dd('Verificar si el interpolation esta bien','pn', $pn, 'nv', $nv, 'ppvt', $ppvt, 'bopvt', $bopvt, 'dopvt', $dopvt);
                            $vm = $pi * $hf * (pow($r1[1], 2) - pow($rw, 2)) / (5.615 * $beta);
                            $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                            $d[$i] = -$f[$i] * $pn[$i] - 158.024370659982 * ($qo / ($kn[$i] * $vm)) * $mu;
                            //dd('f', $f, 'pn', $pn, 'qo', $qo, 'kn', $kn, 'vm', $vm, 'mu', $mu, 'beta', $beta, 'nv', $nv);
                        }
                        else
                        {
                            $d[$i] = -$f[$i] * $pn[$i];
                            //dd('d', $d, 'f', $f, 'pn', $pn);
                        }
                    }

                    $qq[1] = $a[1] / $c[1];
                    $gg[1] = $d[1] / $c[1];

                    for ($j=2; $j <= $nr ; $j++) 
                    { 
                        $w[$j] = $c[$j] - ($b[$j] * $qq[$j - 1]);
                        $gg[$j] = ($d[$j] - ($b[$j] * $gg[$j - 1])) / $w[$j];
                        $qq[$j] = $a[$j] / $w[$j];
                    }
                    

                    $pcal[$nr] = $gg[$nr];
    
                    for ($j=$nr - 1; $j >= 1 ; $j--) 
                    { 
                        $pcal[$j] = ($gg[$j] - ($qq[$j] * $pcal[$j + 1]));
                    }
                        
                    //dd('pcal', $pcal, 'gg', $gg, 'qq', $qq, 'd', $d, 'b', $b, 'w', $w, 'nr', $nr, 'c', $c, 'a', $a);
                    #Nuevo
                    
                    if ($pcal[1] < 0) {
                        if ($xx == 7) {
                            $xx = 6;
                            $flag_ran_xx_7 = 1;
                            break 2;
                        }else{
                            if ($xx == 1) {
                                return [false, Redirect::back()
                                ->withErrors(['msg' => 'Negative bottom hole pressures estimated. Please check the input data.'])];
                            }
                            $xx = 6;
                            //if($xx==6 && $kk==19 && $v==1) {dd($ndt, 'eh ave maría pues ome!', $cr); }
                            break 2;
                        }
                    }

                    for ($i=1; $i <= $nr ; $i++) 
                    { 
                        $rho = $this->interpolation($pcal[$i], $nv, $ppvt, $dopvt); #Revisar -->quitar y evaluar con último valor de pcal.
                    }

                    #Cálculo del flux
                    for ($i=2; $i < $nr; $i++) 
                    //for ($i=2; $i < $nr - 1 ; $i++) 
                    { 
                        $dpre[$i] = -($pcal[$i] - $pcal[$i - 1]) / (2 * $dr[$i]);
                    }

                    $dpre[$nr] = 0;
                    $u[1] = -2.5 * 158.024370659982 * $qo / (2 * $pi * $rw * $hf); #ft/dia
                    $ucri_esc = 2.5 * 158.024370659982 * $tcri_esc / (2 * $pi * $rw * $hf);  #ft/dia
                    for ($i=2; $i <= $nr ; $i++) 
                    { 
                        $mu = $this->interpolation($pcal[$i], $nv, $ppvt, $uopvt);
                        $u[$i] = -$kn[$i] * $dpre[$i] / $mu;
                        if ($u[$i] < 0.00001)
                        {
                            $u[$i] = 0;
                        }
                    }

                    #Identificación de fenomenos
                    $k1i = $this->fines_interpolation(-$qo, $ns, $qlab, $k1_lab);
                    $k2i = $this->fines_interpolation(-$qo, $ns, $qlab, $k2_lab);
                    $dpdli = $this->fines_interpolation(-$qo, $ns, $qlab, $dpdl_lab);
                    $k3i = $this->fines_interpolation(-$qo, $ns, $qlab, $k3_lab);
                    $k4i = $this->fines_interpolation(-$qo, $ns, $qlab, $k4_lab);
                    $k5i = $this->fines_interpolation(-$qo, $ns, $qlab, $k5_lab);
                    $dpdlsi = $this->fines_interpolation(-$qo, $ns, $qlab, $dpdls_lab);
                    $sigmai = $this->fines_interpolation(-$qo, $ns, $qlab, $sigma_lab);
                    $k6i = $this->fines_interpolation(-$qo, $ns, $qlab, $k6_lab);
                    $ab2i = $this->fines_interpolation(-$qo, $ns, $qlab, $ab2_lab);
                    $abi = $this->fines_interpolation(-$qo, $ns, $qlab, $ab_lab);

                    #Cambio de porosidad - No se usa ki para estos escenarios --> ajuste del modelo multitasa. Revisar y quitar
                    $porosity_change = $this->porosity_change($nr, $ndt * $tiempo[$kk], $tiempo[$kk], $ki, $phin, $u, $ucri_esc, $sigmaini, $dpre, $rhop, $co, $k1i, $k2i, $k3i, $k4i, $k5i, $k6i, $dpdli, $dpdlsi, $sigmai, $abi, $ab2i, $porosity_limit_constant);
                    //dd($porosity_change);
                    $phic = $porosity_change[0];
                    $dsigma = $porosity_change[1];
                    $dsigma1 = $porosity_change[2];
                    $sigmasal = $porosity_change[3];
                    $sigma2 = $porosity_change[4];

                    #Delta de porosidad
                    for ($i=1; $i <= $nr ; $i++) 
                    { 
                        if ($u[$i] > $ucri_esc)
                        {
                            $dphi[$i] = $phin[$i] - $phic[$i];
                        }
                        else
                        {
                            $dphi[$i] = 0;
                            //$sigmasal[$i] = 0;
                        }
                        $beta = $this->interpolation($pcal[$i], $nv, $ppvt, $bopvt, $beta);
                        $boi[$i] = $beta;
                    }

                    #Factor de corrección ecuación de partículas
                    /*
                    $fcorr = 0.899;
                    $concentration_change_results = $this->concentration_change($rw, $rdre, $hf, $cr, $qo, $nr, $r1, $boi, $phin, $phic, $u, $dt, $co, $dsigma1, $dphi, $rl);
                    $cocal = $concentration_change_results[0];
                    $rl = $concentration_change_results[1];

                    for ($i=1; $i < $nr ; $i++) 
                    { 
                        $coi = $this->interpolation($r[$i], 100, $rl, $cocal);
                        $coc[$i] = $coi;
                    }

                    $coc[$nr] = $co[$nr];

                    **/

                    $coc = $sigma2;

                    #Cambio de permeabilidad
                    for ($i=1; $i <= $nr ; $i++) 
                    { 
                        //$cod[$i] = $cod[$i] + $sigmasal[$i];
                        $cod[$i] = $sigmasal[$i];
                        $kc[$i] = $kn[$i] * pow($phic[$i] / $phin[$i], 3.0); #Revisar
                    }

                    for ($i=1; $i <= $nr ; $i++) 
                    { 
                        $pn[$i] = $pcal[$i];
                        $phin[$i] = $phic[$i];
                        $kn[$i] = $kc[$i];
                        $co[$i] = $coc[$i];
                        $sigmaini[$i] = $sigmasal[$i];
                    }
                    
                }

                #Radio de daño
                /*for ($i=2; $i <= $nr ; $i++) 
                { 
                    $rdamage[$i] = (($ko - $kc[$i]) / $phio) * 100;
                }*/

                #Reducción del 10% de permeabilidad
                /*$radio_dam = $this->interpolation(10, $nr, $r, $rdamage);

                if ($radio_dam >= $rdre)
                {
                    $radio_dam = $rw; #fallo inter
                }

                if ($radio_dam < $rw)
                {
                    $radio_dam = $rw; #fallo inter
                }

                $skinprom = 0;
                $npro = 1;
                for ($i=1; $i <= $nr ; $i++) 
                { 
                    if ($r[$i] < $radio_dam)
                    {
                        $npro = $npro + 1;
                        $skinprom = $kc[$i] + $skinprom;
                    }
                }

                $skinprom = $skinprom / $npro;
                $skin = (($kc[1] / $ko) - 1.0) * log($rw / $radio_dam);

                */

                for ($i=1; $i <= $nr ; $i++) { 
                    if ( abs($kc[$nr] - $kc[$i]) > (0.05 * $kc[$nr]) ) {
                        $radio_dam[$i] = $r[$i];
                    }else{
                        $radio_dam[$i] = 0;
                    }
                }

                $r_damage = max($radio_dam);

                for ($i=1; $i <= $nr ; $i++) { 
                    if ( $radio_dam[$i] != 0 ) {
                        $skin[$i] = (($kc[$nr] / $kc[$i]) - 1 ) * log($r_damage / $rw);
                    }else{
                        $skin[$i] = 0;
                    }
                }

                // if ($xx == 7) {
                //     for ($i=1; $i <= $nr ; $i++) 
                //     { 
                //         $simulation_results[$i] = array($r[$i], $pcal[$i], $phic[$i], $kc[$i], $cod[$i]);
                //     }
                //     //dd($r, $pcal, $phic, $kc, $coc, 'lel');
                //     $damage_results[$kk] = array($hist[$kk], $r_damage, $skin[2]);
                //     array_push($complete_simulated_results, $simulation_results);
                // }
                
            }

            //dd($complete_simulated_results);
           
            #Nueva sección
            if ($xx < 6) {
                $pite[$xx] = $pcal[499];
                $crite[$xx] = $cr;

                $pn = array_fill(1, $nr, $pini); 
                $phin = array_fill(1, $nr, $phio); 
                $kn = array_fill(1, $nr, $ko); 
                $co = array_fill(1, $nr, $coi); 
                $cod = array_fill(1, $nr, 0); 
                $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                $bo = array_fill(1, $nr, 0);
                $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);

                $cr = $cri[$xx + 1];
            }

            if ($xx == 6) {
                // dd('llegué a 6');
                // dd('LLEGA AL INICIO DEL xx = 6',$pite);
                // dd(count($pite));

                #Eliminar posiciones en 0
                while (count($pite) > 1 && $pite[count($pite)] == 0) { 
                    if ($pite[count($pite)] == 0) { 
                        array_pop($pite);
                    }
                }

                
                if (count($pite) == 1) {
                    $cr = $crite[1];
                    $pn = array_fill(1, $nr, $pini); 
                    $phin = array_fill(1, $nr, $phio); 
                    $kn = array_fill(1, $nr, $ko); 
                    $co = array_fill(1, $nr, $coi); 
                    $cod = array_fill(1, $nr, 0); 
                    $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                    $bo = array_fill(1, $nr, 0);
                    $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                    // dd($pact, $pite, $crite, $cr, 0, $xx);
                } else { 
                    for ($j = 1; $j <= (count($pite) - 1); $j++) {  #length(crite)-1
                        if ($pact > $pite[1]) {
                            $cr = $crite[1] + (($crite[2] - $crite[1]) / ($pite[2] - $pite[1])) * ($pact - $pite[1]);
                            if ($cr < 0) { 
                                $cr = $crite[count($pite)];
                            }
                            $pn = array_fill(1, $nr, $pini); 
                            $phin = array_fill(1, $nr, $phio); 
                            $kn = array_fill(1, $nr, $ko); 
                            $co = array_fill(1, $nr, $coi); 
                            $cod = array_fill(1, $nr, 0); 
                            $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                            $bo = array_fill(1, $nr, 0);
                            $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                            // dd($pact, $pite, $crite, $cr, 1, $xx);
                            break;
                        }elseif (($pact < $pite[$j]) && ($pact > $pite[$j + 1])) { 
                            $cr = $crite[$j] + (($crite[$j + 1] - $crite[$j]) / ($pite[$j + 1] - $pite[$j])) * ($pact - $pite[$j]);
                            if ($cr < 0) { 
                                $cr = $crite[count($pite)];
                            }
                            $pn = array_fill(1, $nr, $pini); 
                            $phin = array_fill(1, $nr, $phio); 
                            $kn = array_fill(1, $nr, $ko); 
                            $co = array_fill(1, $nr, $coi); 
                            $cod = array_fill(1, $nr, 0); 
                            $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                            $bo = array_fill(1, $nr, 0);
                            $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
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
                            $pn = array_fill(1, $nr, $pini); 
                            $phin = array_fill(1, $nr, $phio); 
                            $kn = array_fill(1, $nr, $ko); 
                            $co = array_fill(1, $nr, $coi); 
                            $cod = array_fill(1, $nr, 0); 
                            $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                            $bo = array_fill(1, $nr, 0);
                            $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                            // dd($pact, $pite, $crite, $cr, 3, $xx);
                            break;
                        }
                    }
                }
            }

            if ($xx == 7) {   
                $flag_ran_yy_11 = 0;     
                for ($yy = 1; $yy <= count($porosity_limit_constanti) + 2; $yy++) {
                    
                    $pn = array_fill(1, $nr, $pini); 
                    $phin = array_fill(1, $nr, $phio); 
                    $kn = array_fill(1, $nr, $ko); 
                    $co = array_fill(1, $nr, $coi); 
                    $cod = array_fill(1, $nr, 0); 
                    $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                    $bo = array_fill(1, $nr, 0);
                    $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                    $constantes = [];

                    $array_aux = [];

                    for ($kk=1; $kk <= $nh ; $kk++)
                    {
                        
                        $ndt = $tiempo[$kk] / $dt;
                        $qo = -$bopd[$kk];
                        
                        if (-$qo < $qlab[1]) {
                            $k_lab_qo = $permeability_lab[1];
                        }else if (-$qo > $qlab[count($qlab)-1]) {
                            $k_lab_qo = $permeability_lab[count($permeability_lab)-1];
                        }else{
                            $k_lab_qo = $this->interpolation(-$qo, count($qlab)-1, $qlab, $permeability_lab);
                        }

                        for ($v=1; $v <= $ndt ; $v++) 
                        { 
                            
                            #coeficientes matriz tridiagonal
                            $i = 1;
                            while ($i == $x + 1)
                            {
                                $i = $i + 1;
                                $b[$i] = pow($r1[$i - 1], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i - 1]);
                            }

                            for ($i= $x + 2; $i <= $nr ; $i++) 
                            { 
                                $b[$i] = $r1[$i - 1] / ($r[$i] * $dr[$i] * $dr1[$i - 1]);
                            }

                            $i = 0;
                            if ($x == 0)
                            {
                                $x = 1;
                            }

                            while ($i == $x - 1)
                            {
                                $i = $i + 1;
                                $a[$i] = pow($r1[$i], $n) / (pow($r[$i], $n) * $dr[$i] * $dr1[$i]);
                            }

                            for ($i=$x; $i < $nr ; $i++)
                            //for ($i=$x; $i < $nr - 1 ; $i++)
                            { 
                                $a[$i] = $r1[$i] / ($r[$i] * $dr[$i] * $dr1[$i]);
                            }

                            $i = 0;

                            while ($i == $x)
                            {
                                $i = $i + 1;
                                $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                                $g1 = 3792.58489625175 * $n * $phin[$i] * $cr / $kn[$i];
                                $f[$i] = $g1 * $mu / $dt; #g1 * uapp[$i] / dt
                            }

                            for ($i=$x + 1; $i < $nr ; $i++) 
                            //for ($i=$x + 1; $i < $nr - 1; $i++) 
                            { 
                                $g2 = 3792.58489625175 * $phin[$i] * $cr * $un / $kn[$i];
                                $f[$i] = $g2 / $dt;
                            }

                            if ($ri > 0 and $ri < $re)
                            {
                                $b[$x] = $dr1[$x] / $dr1[$x - 1];
                                $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                                $a[$x] = $mu / $un; #uapp[$x] / un
                                $f[$x] = 0;
                            }

                            $c[1] = -($a[1] + $f[1]);
                            $c[$nr] = -($b[$nr] + $f[$nr]);

                            for ($i=2; $i < $nr ; $i++) 
                            //for ($i=2; $i < $nr - 1; $i++) 
                            { 
                                $c[$i] = -($a[$i] + $b[$i] + $f[$i]);
                            }

                            for ($i=1; $i <= $nr ; $i++) 
                            { 
                                if ($i == 1)
                                {
                                    $beta = $this->interpolation($pn[$i], $nv, $ppvt, $bopvt);
                                    //dd('Verificar si el interpolation esta bien','pn', $pn, 'nv', $nv, 'ppvt', $ppvt, 'bopvt', $bopvt, 'dopvt', $dopvt);
                                    $vm = $pi * $hf * (pow($r1[1], 2) - pow($rw, 2)) / (5.615 * $beta);
                                    $mu = $this->interpolation($pn[$i], $nv, $ppvt, $uopvt);
                                    $d[$i] = -$f[$i] * $pn[$i] - 158.024370659982 * ($qo / ($kn[$i] * $vm)) * $mu;
                                    //dd('f', $f, 'pn', $pn, 'qo', $qo, 'kn', $kn, 'vm', $vm, 'mu', $mu, 'beta', $beta, 'nv', $nv);
                                }
                                else
                                {
                                    $d[$i] = -$f[$i] * $pn[$i];
                                    //dd('d', $d, 'f', $f, 'pn', $pn);
                                }
                            }

                            $qq[1] = $a[1] / $c[1];
                            $gg[1] = $d[1] / $c[1];

                            for ($j=2; $j <= $nr ; $j++) 
                            { 
                                $w[$j] = $c[$j] - ($b[$j] * $qq[$j - 1]);
                                $gg[$j] = ($d[$j] - ($b[$j] * $gg[$j - 1])) / $w[$j];
                                $qq[$j] = $a[$j] / $w[$j];
                            }

                            $pcal[$nr] = $gg[$nr];

                            for ($j=$nr - 1; $j >= 1 ; $j--) 
                            { 
                                $pcal[$j] = ($gg[$j] - ($qq[$j] * $pcal[$j + 1]));
                            }

                            // if($yy==18 && $kk==109) {dd($pcal, $porosity_limit_constant);}

                            if ($pcal[1] < 0) {
                                if ($yy == 1) {
                                    return [false, Redirect::back()
                                    ->withErrors(['msg' => 'Negative bottom hole pressures estimated. Please check the input data.'])];
                                }else if ($yy < 17) {
                                    $yy = 17;
                                    break 2;
                                }
                            }

                            //if ($xx==7 && $yy==1) {dd($f, $pn, $qo, $kn, $vm, $mu);}
                                
                            //dd('pcal', $pcal, 'gg', $gg, 'qq', $qq, 'd', $d, 'b', $b, 'w', $w, 'nr', $nr, 'c', $c, 'a', $a);
                            
                            #Nuevo
                            //if ($pcal[1] < 0) {
                                //dd($xx, $pcal[1]);
                                //$xx = 6;
                                //if ($xx == 7  && $yy == 5 && $kk == 135 && $v == 1 ) { dd($pcal[1], $xx,$yy,count($porosity_limit_constanti) + 1, 'antes del ciclon', $nh, $ndt); }
                        
                                
                                

                                //break;
                            //}

                            for ($i=1; $i <= $nr ; $i++) 
                            { 
                                $rho = $this->interpolation($pcal[$i], $nv, $ppvt, $dopvt); #Revisar -->quitar y evaluar con último valor de pcal.
                            }

                            #Cálculo del flux
                            for ($i=2; $i < $nr; $i++) 
                            //for ($i=2; $i < $nr - 1 ; $i++) 
                            { 
                                $dpre[$i] = -($pcal[$i] - $pcal[$i - 1]) / (2 * $dr[$i]);
                            }

                            $dpre[$nr] = 0;
                            $u[1] = -2.5 * 158.024370659982 * $qo / (2 * $pi * $rw * $hf); #ft/dia
                            $ucri_esc = 2.5 * 158.024370659982 * $tcri_esc / (2 * $pi * $rw * $hf);  #ft/dia
                            for ($i=2; $i <= $nr ; $i++) 
                            { 
                                $mu = $this->interpolation($pcal[$i], $nv, $ppvt, $uopvt);
                                $u[$i] = -$kn[$i] * $dpre[$i] / $mu;
                                if ($u[$i] < 0.00001)
                                {
                                    $u[$i] = 0;
                                }
                            }

                            #Identificación de fenomenos
                            $k1i = $this->fines_interpolation(-$qo, $ns, $qlab, $k1_lab);
                            $k2i = $this->fines_interpolation(-$qo, $ns, $qlab, $k2_lab);
                            $dpdli = $this->fines_interpolation(-$qo, $ns, $qlab, $dpdl_lab);
                            $k3i = $this->fines_interpolation(-$qo, $ns, $qlab, $k3_lab);
                            $k4i = $this->fines_interpolation(-$qo, $ns, $qlab, $k4_lab);
                            $k5i = $this->fines_interpolation(-$qo, $ns, $qlab, $k5_lab);
                            $dpdlsi = $this->fines_interpolation(-$qo, $ns, $qlab, $dpdls_lab);
                            $sigmai = $this->fines_interpolation(-$qo, $ns, $qlab, $sigma_lab);
                            $k6i = $this->fines_interpolation(-$qo, $ns, $qlab, $k6_lab);
                            $ab2i = $this->fines_interpolation(-$qo, $ns, $qlab, $ab2_lab);
                            $abi = $this->fines_interpolation(-$qo, $ns, $qlab, $ab_lab);

                            #Cambio de porosidad - No se usa ki para estos escenarios --> ajuste del modelo multitasa. Revisar y quitar
                            if ($yy == 18) {
                                $kite_reverse = array_reverse($kite);
                                $porosity_limit_constantite_reverse = array_reverse($porosity_limit_constantite);
                                $porosity_limit_constant = $this->interpolation($k_lab_qo, count($kite)-1, $kite_reverse, $porosity_limit_constantite_reverse);
                                // dd($kite_reverse, $porosity_limit_constantite_reverse, $porosity_limit_constant, $k_lab_qo);
                                array_push($array_aux, $porosity_limit_constant);
                                // if ($kk == $nh) {
                                //     dd($kite_reverse, $porosity_limit_constantite_reverse, $porosity_limit_constant, $k_lab_qo, $qlab, $qo, $array_aux);
                                // }
                            }
                            $porosity_change = $this->porosity_change($nr, $ndt * $tiempo[$kk], $tiempo[$kk], $ki, $phin, $u, $ucri_esc, $sigmaini, $dpre, $rhop, $co, $k1i, $k2i, $k3i, $k4i, $k5i, $k6i, $dpdli, $dpdlsi, $sigmai, $abi, $ab2i, $porosity_limit_constant);
                            //dd($porosity_change);
                            $phic = $porosity_change[0];
                            $dsigma = $porosity_change[1];
                            $dsigma1 = $porosity_change[2];
                            $sigmasal = $porosity_change[3];
                            $sigma2 = $porosity_change[4];

                            #Delta de porosidad
                            for ($i=1; $i <= $nr ; $i++) 
                            { 
                                if ($u[$i] > $ucri_esc)
                                {
                                    $dphi[$i] = $phin[$i] - $phic[$i];
                                }
                                else
                                {
                                    $dphi[$i] = 0;
                                    //$sigmasal[$i] = 0;
                                }
                                $beta = $this->interpolation($pcal[$i], $nv, $ppvt, $bopvt, $beta);
                                $boi[$i] = $beta;
                            }

                            #Factor de corrección ecuación de partículas
                            /*
                            $fcorr = 0.899;
                            $concentration_change_results = $this->concentration_change($rw, $rdre, $hf, $cr, $qo, $nr, $r1, $boi, $phin, $phic, $u, $dt, $co, $dsigma1, $dphi, $rl);
                            $cocal = $concentration_change_results[0];
                            $rl = $concentration_change_results[1];

                            for ($i=1; $i < $nr ; $i++) 
                            { 
                                $coi = $this->interpolation($r[$i], 100, $rl, $cocal);
                                $coc[$i] = $coi;
                            }

                            $coc[$nr] = $co[$nr];

                            **/

                            $coc = $sigma2;

                            #Cambio de permeabilidad
                            for ($i=1; $i <= $nr ; $i++) 
                            { 
                                //$cod[$i] = $cod[$i] + $sigmasal[$i];
                                $cod[$i] = $sigmasal[$i];
                                $kc[$i] = $kn[$i] * pow($phic[$i] / $phin[$i], 3.0); #Revisar
                            }
                            
                            if ($kc[2] < 0) {
                                if ($yy < 17) {
                                    $yy = 17;
                                    break 2;
                                }
                            }

                            for ($i=1; $i <= $nr ; $i++) 
                            { 
                                $pn[$i] = $pcal[$i];
                                $phin[$i] = $phic[$i];
                                $kn[$i] = $kc[$i];
                                $co[$i] = $coc[$i];
                                $sigmaini[$i] = $sigmasal[$i];
                            }
                        }

                        #Radio de daño
                        /*for ($i=2; $i <= $nr ; $i++) 
                        { 
                            $rdamage[$i] = (($ko - $kc[$i]) / $phio) * 100;
                        }*/

                        #Reducción del 10% de permeabilidad
                        /*$radio_dam = $this->interpolation(10, $nr, $r, $rdamage);

                        if ($radio_dam >= $rdre)
                        {
                            $radio_dam = $rw; #fallo inter
                        }

                        if ($radio_dam < $rw)
                        {
                            $radio_dam = $rw; #fallo inter
                        }

                        $skinprom = 0;
                        $npro = 1;
                        for ($i=1; $i <= $nr ; $i++) 
                        { 
                            if ($r[$i] < $radio_dam)
                            {
                                $npro = $npro + 1;
                                $skinprom = $kc[$i] + $skinprom;
                            }
                        }

                        $skinprom = $skinprom / $npro;
                        $skin = (($kc[1] / $ko) - 1.0) * log($rw / $radio_dam);

                        */

                        for ($i=1; $i <= $nr ; $i++) { 
                            if ( abs($kc[$nr] - $kc[$i]) > (0.05 * $kc[$nr]) ) {
                                $radio_dam[$i] = $r[$i];
                            }else{
                                $radio_dam[$i] = 0;
                            }
                        }

                        $r_damage = max($radio_dam);

                        for ($i=1; $i <= $nr ; $i++) { 
                            if ( $radio_dam[$i] != 0 ) {
                                $skin[$i] = (($kc[$nr] / $kc[$i]) - 1 ) * log($r_damage / $rw);
                            }else{
                                $skin[$i] = 0;
                            }
                        }

                        if ($xx == 7 && $yy == 18) {
                            for ($i=2; $i <= $nr - 1; $i++) 
                            { 
                                $simulation_results[$i] = array($r[$i], $pcal[$i], $phic[$i], $kc[$i], $cod[$i]);
                            }
                            // dd($cr, $porosity_limit_constant, $simulation_results);
                            //dd($r, $pcal, $phic, $kc, $coc, 'lel');
                            $damage_results[$kk] = array($hist[$kk], $r_damage, $skin[2]);
                            array_push($constantes, $porosity_limit_constant);
                            array_push($complete_simulated_results, $simulation_results);
                            // if ($kk == $nh) {
                            //     dd($constantes);
                            // }
                        }
                    }

                    //if ($xx == 7  && $yy == 5) { dd($xx,$yy,count($porosity_limit_constanti) + 1, 'despues del ciclo'); }

                    #Nueva sección
                    if ($yy < 17) {
                        //if($xx==7 && $yy==1 ){dd($phic, $kc, $pcal);}
                        $kite[$yy] = $kc[2];
                        $porosity_limit_constantite[$yy] = $porosity_limit_constant;

                        $pn = array_fill(1, $nr, $pini); 
                        $phin = array_fill(1, $nr, $phio); 
                        $kn = array_fill(1, $nr, $ko); 
                        $co = array_fill(1, $nr, $coi); 
                        $cod = array_fill(1, $nr, 0); 
                        $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                        $bo = array_fill(1, $nr, 0);
                        $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);

                        $porosity_limit_constant = $porosity_limit_constanti[$yy + 1];
                    }

                    if ($yy == 17) {
                        //dd('LLEGA AL INICIO DEL xx = 6',$kite);
                        //dd(count($kite));

                        #Eliminar posiciones en 0
                        while (count($kite) > 1 && $kite[count($kite)] == 0) { 
                            if ($kite[count($kite)] == 0) { 
                                array_pop($kite);
                            }
                        }

                        while (count($porosity_limit_constantite) > count($kite)) {
                            array_pop($porosity_limit_constantite);
                        }

                        // dd([$pite, $crite, $kite, $porosity_limit_constantite, $cr, $pact]);

                        //dd($kite);
                        
                        // for ($j = 1; $j <= (count($kite) - 1); $j++) {  #length(crite)-1
                        //     if ($kact > $kite[1]) {
                        //         if (($kite[2] - $kite[1]) == 0) {
                        //             $porosity_limit_constant = $porosity_limit_constantite[1];
                        //         }else{
                        //             $porosity_limit_constant = $porosity_limit_constantite[1] + (($porosity_limit_constantite[2] - $porosity_limit_constantite[1]) / ($kite[2] - $kite[1])) * ($pact - $kite[1]);
                        //         }
                        //         # Acotar la constante
                        //         if ($porosity_limit_constant <= 0) { 
                        //             $porosity_limit_constant = $porosity_limit_constantite[1];
                        //         }elseif ($porosity_limit_constant >= 1.5) {
                        //             $porosity_limit_constant = 1.5;
                        //         }
                        //         $pn = array_fill(1, $nr, $pini); 
                        //         $phin = array_fill(1, $nr, $phio); 
                        //         $kn = array_fill(1, $nr, $ko); 
                        //         $co = array_fill(1, $nr, $coi); 
                        //         $cod = array_fill(1, $nr, 0); 
                        //         $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                        //         $bo = array_fill(1, $nr, 0);
                        //         $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                        //         // dd([$kact, $pite, $crite, $kite, $porosity_limit_constantite, $cr, $porosity_limit_constant, 1]);
                        //         // dd($kact, $kite, $porosity_limit_constantite, $porosity_limit_constant, $cr, $pcal, 1, $xx);
                        //         break;
                        //     }elseif (($kact < $kite[$j]) && ($kact > $kite[$j + 1])) { 
                        //         if (($kite[$j + 1] - $kite[$j]) == 0) {
                        //             $porosity_limit_constant = $porosity_limit_constantite[$j];
                        //         }else{
                        //             $porosity_limit_constant = $porosity_limit_constantite[$j] + (($porosity_limit_constantite[$j + 1] - $porosity_limit_constantite[$j]) / ($kite[$j + 1] - $kite[$j])) * ($kact - $kite[$j]);
                        //         }
                        //         # Acotar la constante
                        //         if ($porosity_limit_constant <= 0) { 
                        //             $porosity_limit_constant = $porosity_limit_constantite[1];
                        //         }elseif ($porosity_limit_constant >= 1.5) {
                        //             $porosity_limit_constant = 1.5;
                        //         }
                        //         $pn = array_fill(1, $nr, $pini); 
                        //         $phin = array_fill(1, $nr, $phio); 
                        //         $kn = array_fill(1, $nr, $ko); 
                        //         $co = array_fill(1, $nr, $coi); 
                        //         $cod = array_fill(1, $nr, 0); 
                        //         $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                        //         $bo = array_fill(1, $nr, 0);
                        //         $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                        //         // dd([$kact, $pite, $crite, $kite, $porosity_limit_constantite, $cr, $porosity_limit_constant, 2]);
                        //         // dd($kact, $kite, $porosity_limit_constantite, $porosity_limit_constant, $cr, $pcal, 2, $xx);
                        //         break;
                        //     }elseif ($kact < $kite[count($kite)]) {
                        //         if ($flag_ran_yy_11 == 0) {
                        //             if(($kite[count($kite)] - $kite[count($kite) - 1]) == 0) { 
                        //                 $porosity_limit_constant = $porosity_limit_constantite[count($kite) - 1];
                        //             }else{
                        //                 $porosity_limit_constant = $porosity_limit_constantite[count($kite) - 1] + (($porosity_limit_constantite[count($kite)] - $porosity_limit_constantite[count($kite) - 1]) / ($kite[count($kite)] - $kite[count($kite) - 1])) * ($kact - $kite[count($kite) - 1]);
                        //             }
                        //         }else{
                        //             $porosity_limit_constant = $porosity_limit_constantite[count($kite)];
                        //         }
                        //         # Acotar la constante
                        //         if ($porosity_limit_constant <= 0) { 
                        //             $porosity_limit_constant = $porosity_limit_constantite[1];
                        //         }elseif ($porosity_limit_constant >= 1.5) {
                        //             $porosity_limit_constant = 1.5;
                        //         }
                        //         $pn = array_fill(1, $nr, $pini); 
                        //         $phin = array_fill(1, $nr, $phio); 
                        //         $kn = array_fill(1, $nr, $ko); 
                        //         $co = array_fill(1, $nr, $coi); 
                        //         $cod = array_fill(1, $nr, 0); 
                        //         $sigmaini = array_fill(1, $nr, $simgaineverchanges); 
                        //         $bo = array_fill(1, $nr, 0);
                        //         $rho = $this->interpolation($pini, $nv, $ppvt, $dopvt);
                        //         // dd([$kact, $pite, $crite, $kite, $porosity_limit_constantite, $cr, $porosity_limit_constant, $flag_ran_yy_11, 3]);
                        //         // dd($kact, $kite, $porosity_limit_constantite, $porosity_limit_constant, $cr, $pcal, 3, $xx);
                        //         break;
                        //     }
                        // }
                    }
                    
                }
                // if ($yy == 18) {
                //     break 1;
                // }
            }
        }
        // dd([ $pite, $crite, $kite, $porosity_limit_constantite, $cr, $porosity_limit_constant, 3, $simulation_results]);
        return array($complete_simulated_results, $damage_results);
    }

    function set_array($original_array, $n)
    {
        $fixed_array = new SplFixedArray($n+1);
        for ($i=0; $i < count($original_array) ; $i++) 
        { 
            $fixed_array[$i+1] = floatval($original_array[$i]);
        }
        return $fixed_array;
    }
    function set_array_string($original_array, $n)
    {
        $fixed_array = new SplFixedArray($n+1);
        for ($i=0; $i < count($original_array) ; $i++) 
        { 
            $fixed_array[$i+1] = $original_array[$i];
        }
        return $fixed_array;
    }
}
