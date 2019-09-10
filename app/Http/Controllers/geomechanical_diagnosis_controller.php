<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\geomechanical_diagnosis_request;
use DB;
use App\cuenca;
use App\escenario;
use App\geomechanical_diagnosis;
use App\geomechanical_diagnosis_fractures_table;
use App\geomechanical_diagnosis_well_bottom_pressure_table;
use View;
use SplFixedArray;
use Log;
use Validator;
# use CArray;

class geomechanical_diagnosis_controller extends Controller
{

    const radius_step = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {

            $scenary_id = $_GET['scenaryId'];
            $scenario = escenario::find($scenary_id);
            $well = DB::table('pozos')->where('id', $scenario->pozo_id)->first();
            $basin = cuenca::where('id', $scenario->cuenca_id)->first();
            $formation = DB::table('formacionxpozos')->where('id', $scenario->formacion_id)->first();
            $field = DB::table('campos')->where('id', $scenario->campo_id)->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenario->id)->first();
            $interval = DB::table('formacionxpozos')->where('id',$scenario->formacion_id)->first();
            $formations = DB::table('formaciones')->where('campo_id','=',$scenario->campo_id);
            $advisor = $scenario->enable_advisor;

            return View::make('add_geomechanical', compact(['user','basin','well', 'formation', 'field', 'interval','scenario','formations', 'advisor'])); 

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

    private function validateData($request)
    {
        $validator = Validator::make($request->all(), [
            'well_azimuth'=>'required|numeric',
            'well_dip'=>'required|numeric',
            'well_radius'=>'required|numeric',
            'max_analysis_radius'=>'required|numeric',
            'matrix_permeability'=>'required|numeric',
            'poisson_ratio'=>'required|numeric',
            'biot_coefficient'=>'required|numeric',
            'azimuth_maximum_horizontal_stress'=>'required|numeric',
            'minimum_horizontal_stress_gradient'=>'required|numeric',
            'vertical_stress_gradient'=>'required|numeric',
            'maximum_horizontal_stress_gradient'=>'required|numeric',
            'initial_fracture_width'=>'required|numeric',
            'initial_fracture_toughness'=>'required|numeric',
            'fracture_closure_permeability'=>'required|numeric',
            'residual_fracture_closure_permeability'=>'required|numeric',
            'top'=>'required|numeric',
            'netpay'=>'required|numeric',
            'viscosity'=>'required|numeric',
            'volumetric_factor'=>'required|numeric',
            'rate'=>'required|numeric'
        ])->setAttributeNames([
            'well_azimuth.required'=>'A well azimuth value is required.',
            'well_dip.required'=>'A well dip value is required.',
            'well_radius.required'=>'A well radius value is required.',
            'max_analysis_radius.required'=>'A max analysis_radius value is required.',
            'matrix_permeability.required'=>'A matrix permeability value is required.',
            'poisson_ratio.required'=>'A poisson ratio value is required.',
            'biot_coefficient.required'=>'A biot coefficient value is required.',
            'azimuth_maximum_horizontal_stress.required'=>'An azimuth maximum horizontal stress value is required.',
            'minimum_horizontal_stress_gradient.required'=>'A minimum horizontal stress gradient value is required.',
            'vertical_stress_gradient.required'=>'A vertical stress gradient value is required.',
            'maximum_horizontal_stress_gradient.required'=>'A maximum horizontal stress gradient value is required.',
            'initial_fracture_width.required'=>'An initial fracture width value is required.',
            'initial_fracture_toughness.required'=>'An initial fracture toughness value is required.',
            'fracture_closure_permeability.required'=>'A fracture closure permeability value is required.',
            'residual_fracture_closure_permeability.required'=>'A residual fracture closure permeability value is required.',
            'well_azimuth.numeric'=>'The well azimuth value must be numeric.',
            'well_dip.numeric'=>'The well dip value must be numeric.',
            'well_radius.numeric'=>'The well radius value must be numeric.',
            'max_analysis_radius.numeric'=>'The max analysis_radius value must be numeric.',
            'analysis_interval.numeric'=>'The analysis interval value must be numeric.',
            'reservoir_pressure.numeric'=>'The reservoir pressure value must be numeric.',
            'matrix_permeability.numeric'=>'The matrix permeability value must be numeric.',
            'poisson_ratio.numeric'=>'The poisson ratio value must be numeric.',
            'biot_coefficient.numeric'=>'The biot coefficient value must be numeric.',
            'azimuth_maximum_horizontal_stress.numeric'=>'The azimuth maximum horizontal stress value must be numeric.',
            'minimum_horizontal_stress_gradient.numeric'=>'The minimum horizontal stress gradient value must be numeric.',
            'vertical_stress_gradient.numeric'=>'The vertical stress gradient value must be numeric.',
            'maximum_horizontal_stress_gradient.numeric'=>'The maximum horizontal stress gradient value must be numeric.',
            'initial_fracture_width.numeric'=>'The initial fracture width value must be numeric.',
            'initial_fracture_toughness.numeric'=>'The initial fracture toughness value must be numeric.',
            'fracture_closure_permeability.numeric'=>'The fracture closure permeability value must be numeric.',
            'residual_fracture_closure_permeability.numeric'=>'The residual fracture closure permeability value must be numeric.',
            'top.numeric'=>'The top value must be numeric.',
            'netpay.numeric'=>'The netpay value must be numeric.',
            'viscosity.numeric'=>'The viscosity value must be numeric.',
            'volumetric_factor.numeric'=>'The volumetric factor value must be numeric.',
            'rate.numeric'=>'The rate value must be numeric.',
        ]);

        if ($validator->fails()) {
            return redirect('geomechanical_diagnosis')
            ->withErrors($validator)
            ->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $button_sw = isset($_POST['button_sw']) ? true : false;
        if ($res = $this->validateData($request) && !$button_sw) {
            return $res;
        }

        #Datos formulario
        $geomechanical_diagnosis = new geomechanical_diagnosis();
        $geomechanical_diagnosis->scenario_id = $request->input("scenary_id");
        $geomechanical_diagnosis->well_azimuth = $request->input("well_azimuth");
        $geomechanical_diagnosis->well_dip = $request->input("well_dip");
        $geomechanical_diagnosis->well_radius = $request->input("well_radius");
        $geomechanical_diagnosis->max_analysis_radius = $request->input("max_analysis_radius");
        //$geomechanical_diagnosis->analysis_interval = $request->input("analysis_interval");
        $geomechanical_diagnosis->matrix_permeability = $request->input("matrix_permeability");
        $geomechanical_diagnosis->poisson_ratio = $request->input("poisson_ratio");
        $geomechanical_diagnosis->biot_coefficient = $request->input("biot_coefficient");
        $geomechanical_diagnosis->azimuth_maximum_horizontal_stress = $request->input("azimuth_maximum_horizontal_stress");
        $geomechanical_diagnosis->minimum_horizontal_stress_gradient = $request->input("minimum_horizontal_stress_gradient");
        $geomechanical_diagnosis->vertical_stress_gradient = $request->input("vertical_stress_gradient");
        $geomechanical_diagnosis->maximum_horizontal_stress_gradient = $request->input("maximum_horizontal_stress_gradient");
        $geomechanical_diagnosis->initial_fracture_width = $request->input("initial_fracture_width");
        $geomechanical_diagnosis->initial_fracture_toughness = $request->input("initial_fracture_toughness");
        $geomechanical_diagnosis->fracture_closure_permeability = $request->input("fracture_closure_permeability");
        $geomechanical_diagnosis->residual_fracture_closure_permeability = $request->input("residual_fracture_closure_permeability");
        $geomechanical_diagnosis->top = $request->input("top");
        $geomechanical_diagnosis->netpay = $request->input("netpay");
        $geomechanical_diagnosis->viscosity = $request->input("viscosity");
        $geomechanical_diagnosis->volumetric_factor = $request->input("volumetric_factor");
        $geomechanical_diagnosis->rate = $request->input("rate");
        $geomechanical_diagnosis->drainage_radius = $request->input("drainage_radius");
        $geomechanical_diagnosis->reservoir_pressure = $request->input("reservoir_pressure");
        $geomechanical_diagnosis->status_wr = $button_sw;
        $geomechanical_diagnosis->save();

        #Datos tablas
        #Well bottom pressure table
        // $well_bottom_pressure_table_data = json_decode($request->input("well_bottom_pressure_table_data"));

        // $well_bottom_pressure_array = [];

        // foreach ($well_bottom_pressure_table_data as $value) 
        // {
        //     $well_bottom_pressure_table = new geomechanical_diagnosis_well_bottom_pressure_table();
        //     $well_bottom_pressure_table->geomechanical_diagnosis_id = $geomechanical_diagnosis->id;
        //     $well_bottom_pressure_table->well_bottom_pressure = $value[0];
        //     $well_bottom_pressure_table->save();

        //     array_push($well_bottom_pressure_array, $well_bottom_pressure_table->well_bottom_pressure);
        // }

        #Fractures table
        $fractures_table_data = json_decode($request->input("fractures_table_data"));

        $fracture_depth_array = array();
        $fracture_dip_array = array();
        $fracture_dip_azimuth_array = array();

        $fractures_table_data = is_null($fractures_table_data) ? [] : $fractures_table_data;
        foreach ($fractures_table_data as $value) {
            $fractures_table = new geomechanical_diagnosis_fractures_table();
            $fractures_table->geomechanical_diagnosis_id = $geomechanical_diagnosis->id;
            $fractures_table->depth = $value[0];
            $fractures_table->dip = $value[1];
            $fractures_table->dip_azimuth = $value[2];
            $fractures_table->save();

            array_push($fracture_depth_array, $fractures_table->depth);
            array_push($fracture_dip_array, $fractures_table->dip);
            array_push($fracture_dip_azimuth_array, $fractures_table->dip_azimuth);
        }

        $scenario_id = $geomechanical_diagnosis->scenario_id;

        #Cálculos

        $well_azimuth = $geomechanical_diagnosis->well_azimuth;
        $well_dip = $geomechanical_diagnosis->well_dip;
        $well_radius = $geomechanical_diagnosis->well_radius;
        $radius_step = self::radius_step;
        $analysis_radius = $geomechanical_diagnosis->max_analysis_radius;
        $poisson_ratio = $geomechanical_diagnosis->poisson_ratio;
        $biot_factor = $geomechanical_diagnosis->biot_coefficient;
        $minimum_horizontal_stress_gradient = $geomechanical_diagnosis->minimum_horizontal_stress_gradient;
        $maximum_horizontal_stress_gradient = $geomechanical_diagnosis->maximum_horizontal_stress_gradient;
        $vertical_stress_gradient = $geomechanical_diagnosis->vertical_stress_gradient;
        $azimuth_maximum_horizontal_stress = $geomechanical_diagnosis->azimuth_maximum_horizontal_stress;
        $initial_fracture_width = $geomechanical_diagnosis->initial_fracture_width;
        $initial_fracture_toughness = $geomechanical_diagnosis->initial_fracture_toughness;
        $fracture_closure_permeability = $geomechanical_diagnosis->fracture_closure_permeability;
        $residual_fracture_closure_permeability = $geomechanical_diagnosis->residual_fracture_closure_permeability;
        $interval_size = $geomechanical_diagnosis->analysis_interval;
        $matriz_permeability = $geomechanical_diagnosis->matrix_permeability;

        $top = $geomechanical_diagnosis->top;
        $top = $top == '' ? 0 : $top;

        $netpay = $geomechanical_diagnosis->netpay;
        $netpay = $netpay == '' ? 0 : $netpay;

        $viscosity = $geomechanical_diagnosis->viscosity;
        $volumetric_factor = $geomechanical_diagnosis->volumetric_factor;
        $rate = $geomechanical_diagnosis->rate;
        $reservoir_pressure = $geomechanical_diagnosis->reservoir_pressure;
        $drainage_radius = $geomechanical_diagnosis->drainage_radius;

        if (!$geomechanical_diagnosis->status_wr) {
            $geomechanical_diagnosis_results = $this->geomechanical_diagnosis($well_azimuth, $well_dip, $well_radius, $analysis_radius, $radius_step, 
                $poisson_ratio, $biot_factor, $minimum_horizontal_stress_gradient, $maximum_horizontal_stress_gradient,
                $vertical_stress_gradient, $azimuth_maximum_horizontal_stress, $initial_fracture_width, $initial_fracture_toughness,
                $fracture_closure_permeability, $residual_fracture_closure_permeability, $fracture_depth_array, $fracture_dip_array,
                $fracture_dip_azimuth_array, $matriz_permeability, $top, $netpay, $viscosity, $volumetric_factor, $rate, $reservoir_pressure, $drainage_radius);

            $radii = $geomechanical_diagnosis_results[0];
            $thetas = $geomechanical_diagnosis_results[1];
            $Wfracture = $geomechanical_diagnosis_results[2];
            $Kfracture = $geomechanical_diagnosis_results[3];
            $z = $geomechanical_diagnosis_results[4];
            $Sn = $geomechanical_diagnosis_results[5];
            $ST1 = $geomechanical_diagnosis_results[6];
            $ST2 = $geomechanical_diagnosis_results[7];
            $ST3 = $geomechanical_diagnosis_results[8];
            $azimuth_maximum_horizontal_stress_index = $geomechanical_diagnosis_results[9];
            $pore_pressures = $geomechanical_diagnosis_results[10];
            $fracture_lines = $geomechanical_diagnosis_results[11];
            $Sr_graph = $geomechanical_diagnosis_results[12];
            $St_graph = $geomechanical_diagnosis_results[13];
            $Sz_graph = $geomechanical_diagnosis_results[14];
        } else {
            $radii = [];
            $thetas = [];
            $Wfracture = [];
            $Kfracture = [];
            $z = [];
            $Sn = [];
            $ST1 = [];
            $ST2 = [];
            $ST3 = [];
            $azimuth_maximum_horizontal_stress_index = 0;
            $pore_pressures = [];
            $fracture_lines = [];
            $Sr_graph = [];
            $St_graph = [];
            $Sz_graph = [];
        }

        
        $complete_pore_pressures = [];
        for($i=0; $i<count($pore_pressures); $i++){
            $aux7 = [];
            for($j=0; $j<count($pore_pressures[$i]); $j++){
                array_push($aux7, array($radii[$j], $pore_pressures[$i][$j]));
            }
            
            $auxObj = array(
                'name' => "Pore Pressure",
                'data' => $aux7
            );
            
            array_push($complete_pore_pressures, json_encode($auxObj));
        }

        #Conversion factor -> Fracture width in um
        for($f=0; $f<count($fracture_depth_array);$f++){
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    $Wfracture[$f][$r][$t] = $Wfracture[$f][$r][$t] * 304800;
                }
            }
        }
        $radii = $this->object_to_array($radii);
        #$thetas = $this->object_to_array($thetas);
        #$Wfracture = $this->object_to_array($Wfracture);
        #$Kfracture = $this->object_to_array($Kfracture);
        $max_radius = (count($radii) > 0) ? max($radii) : 0;

        # Line of max_azimuth
        $aux8 = [];
        $max_azimuth_line = json_encode([]);
        if (isset($thetas[$azimuth_maximum_horizontal_stress_index])) {
            if($thetas[$azimuth_maximum_horizontal_stress_index] > pi()){
                array_push($aux8, array($max_radius*cos($thetas[$azimuth_maximum_horizontal_stress_index]-pi()), $max_radius*sin($thetas[$azimuth_maximum_horizontal_stress_index]-pi())));
            } else {
                array_push($aux8, array($max_radius*cos($thetas[$azimuth_maximum_horizontal_stress_index]+pi()), $max_radius*sin($thetas[$azimuth_maximum_horizontal_stress_index]+pi())));
            }
            array_push($aux8, array($max_radius*cos($thetas[$azimuth_maximum_horizontal_stress_index]), $max_radius*sin($thetas[$azimuth_maximum_horizontal_stress_index])));
            $max_azimuth_line = json_encode(array(
                'name' => "Max Azimuth " . round(rad2deg($thetas[$azimuth_maximum_horizontal_stress_index]), 2) . " °",
                'data' => $aux8,
                'dashStyle' => 'shortdot'
            ));
        }

        $scenary = escenario::find($scenario_id);
        $scenary->estado = 1;
        $scenary->completo = 1;
        $scenary->save();

        #Data for contour plots
        $fracture_permeability = [];
        # $fracture_width = [];
        $normal_stress = [];
        for($f=0; $f<count($fracture_depth_array);$f++) {
            $aux1 = [];
            # $aux2 = [];
            $aux3 = [];
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Kfracture[$f][$r][$t]));
                    # array_push($aux2, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Wfracture[$i][$f][$r][$t]));
                    array_push($aux3, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Sn[$f][$r][$t]));
                }
            }
            array_push($fracture_permeability, $aux1);
            # array_push($fracture_width, $aux2);
            array_push($normal_stress, $aux3);
        }


        #Data for avreage permeability (only fractures)
        // $average_permeability_only_fractures = [];
        // for($i=0; $i<count($well_bottom_pressure_array);$i++){
        //     $inter = 0;
        //     foreach ($intervals as $interval) {
        //         $aux1 = [];
        //         for($r=0; $r<count($radii); $r++){
        //             for($t=0; $t<count($thetas); $t++){

        //                 for($f=0; $f<count($fracture_depth_array);$f++){
        //                     #dd($z[$f][$r][$t], floatval($interval[0]), $interval[1]);
        //                     if (($z[$f][$r][$t] > floatval($interval[0])) and ($z[$f][$r][$t] < $interval[1])){
        //                         array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $average_permeability[$i][$inter][$r][$t]));
        //                         #continue 2;
        //                     }
        //                 }

        //             }
        //         }
        //         array_push($average_permeability_only_fractures, $aux1);
        //         $inter++;
        //     }
        // }
        
        // $average_permeability_only_fractures = [];
        // for($i=0; $i<count($well_bottom_pressure_array);$i++){
        //     for($inter=0; $inter<count($intervals);$inter++){
        //         $aux1 = [];
        //         for($r=0; $r<count($radii); $r++){
        //             for($t=0; $t<count($thetas); $t++){
        //                 if ($average_permeability[$i][$inter][$r][$t] > $matriz_permeability){
        //                     array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $average_permeability[$i][$inter][$r][$t]));
        //                 }
        //             }
        //         }
        //         array_push($average_permeability_only_fractures, $aux1);
        //     }
        // }

        #Depths
        $depths = [];
        for($f=0; $f<count($fracture_depth_array);$f++){
            $aux1 = [];
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $z[$f][$r][$t]));                
                }
            }
            array_push($depths, $aux1);
        }

        /* Thetas in degree */
        $thetas_array_aux = [];
        foreach ($thetas as $value) {
            array_push($thetas_array_aux, rad2deg($value));
        }

        $thetas = $thetas_array_aux;

        /* Rounding radii */
        $radii_array_aux = [];
        foreach ($radii as $value) {
            array_push($radii_array_aux, round($value, 4));
        }
        
        $radii = $radii_array_aux;

        /* Data for 2d plots */
        $fracture_permeability_given_radius = [];
        $fracture_width_given_radius = [];
        for($f=0; $f<count($fracture_depth_array);$f++){
            $aux1 = [];
            $aux2 = [];
            for($r=0; $r<count($radii); $r++){
                $aux3 = [];
                $aux4 = [];
                for($t=0; $t<count($thetas); $t++){
                    array_push($aux3, array(round($thetas[$t], 4), round($Kfracture[$f][$r][$t], 4)));
                    array_push($aux4, array(round($thetas[$t], 4), round($Wfracture[$f][$r][$t], 4)));
                }
                array_push($aux1, $aux3);
                array_push($aux2, $aux4);
            }
            array_push($fracture_permeability_given_radius, $aux1);
            array_push($fracture_width_given_radius, $aux2);
        }

        $fracture_permeability_given_theta = [];
        $fracture_width_given_theta = [];
        $fracture_permeability_vs_stress_given_theta = [];
        for($f=0; $f<count($fracture_depth_array);$f++){
            $aux1 = [];
            $aux2 = [];
            $aux5 = [];
            for($t=0; $t<count($thetas); $t++){
                $aux3 = [];
                $aux4 = [];
                $aux6 = [];
                for($r=0; $r<count($radii); $r++){
                    array_push($aux3, array(round($radii[$r], 4), round($Kfracture[$f][$r][$t], 4)));
                    array_push($aux4, array(round($radii[$r], 4), round($Wfracture[$f][$r][$t], 4)));
                    array_push($aux6, array(round($Sn[$f][$r][$t], 4), round($Kfracture[$f][$r][$t], 4)));
                }
                array_push($aux1, $aux3);
                array_push($aux2, $aux4);
                array_push($aux5, $aux6);
            }
            array_push($fracture_permeability_given_theta, $aux1);
            array_push($fracture_width_given_theta, $aux2);
            array_push($fracture_permeability_vs_stress_given_theta, $aux5);
        }

        $min_depth = ceil($top);
        $max_depth = floor($top + $netpay);

        return View::make('geomechanical_diagnosis_results', compact('geomechanical_diagnosis','scenario_id','fracture_depth_array','max_radius','complete_pore_pressures', 'radii', 'thetas', 'fracture_permeability', 'fracture_permeability_given_radius', 'fracture_width_given_radius', 'fracture_permeability_given_theta', 'fracture_width_given_theta', 'depths', 'min_depth', 'max_depth', 'fracture_lines', 'well_radius', 'Sr_graph', 'St_graph', 'Sz_graph', 'normal_stress', 'azimuth_maximum_horizontal_stress_index', 'fracture_permeability_vs_stress_given_theta', 'max_azimuth_line','scenary'));
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

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
    public function duplicate($id,$duplicateFrom)
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
        $scenario = DB::table('escenarios')->where('id', $id)->first();
        $advisor = $scenario->enable_advisor;

        #Leyendo datos desde base de datos
        $geomechanical_diagnosis_scenario = DB::table('geomechanical_diagnosis')->where('scenario_id',$id)->first();
        $well_bottom_pressure_table = DB::table('geomechanical_diagnosis_well_bottom_pressure_table')->where('geomechanical_diagnosis_id',$geomechanical_diagnosis_scenario->id)->get(); 
        $fractures_table = DB::table('geomechanical_diagnosis_fractures_table')->where('geomechanical_diagnosis_id',$geomechanical_diagnosis_scenario->id)->get(); 

        #Organizando datos para tablas
        $well_bottom_pressure_table_data = [];
        foreach ($well_bottom_pressure_table as $value) 
        {
            array_push($well_bottom_pressure_table_data, $value->well_bottom_pressure);
        }

        $fractures_table_data = [];
        foreach ($fractures_table as $value) 
        {
            array_push($fractures_table_data, array($value->depth, $value->dip, $value->dip_azimuth));
        }

        $well = DB::table('pozos')->where('id', $scenario->pozo_id)->first();
        $formation = DB::table('formacionxpozos')->where('id', $scenario->formacion_id)->first();
        $field = DB::table('campos')->where('id', $scenario->campo_id)->first();
        $scenario_id = \Request::get('scenaryId');
        $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenario->id)->first();
        $interval = DB::table('formacionxpozos')->where('id',$scenario->formacion_id)->first();
        $formations = DB::table('formaciones')->where('campo_id','=',$scenario->campo_id);
        $basin = DB::table('cuencas')->where('id', $scenario->cuenca_id)->first();

        $_SESSION["basin"] =  $basin->nombre;
        $_SESSION["field"] =  $field->nombre;
        $_SESSION["well"] =  $well->nombre;
        $_SESSION["formation"] =  $formation->nombre;
        $_SESSION["scenary_id"] =  $scenario->id;

        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        return View::make('edit_geomechanical_diagnosis', compact(['geomechanical_diagnosis_scenario','well_bottom_pressure_table_data', 'fractures_table_data','user','well', 'formation', 'field', 'interval','scenario','formations','basin', 'advisor','duplicateFrom']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $button_sw = isset($_POST['button_sw']) ? true : false;
        if ($res = $this->validateData($request) && !$button_sw) {
            return $res;
        }

        if (isset($_SESSION['scenary_id_dup'])) {
            $geomechanical_diagnosis = new geomechanical_diagnosis();
        } else {
            $geomechanical_diagnosis_scenario = DB::table('geomechanical_diagnosis')->where('scenario_id',$_SESSION["scenary_id"])->first();
            $geomechanical_diagnosis = geomechanical_diagnosis::find($geomechanical_diagnosis_scenario->id);
        }

        /* Datos formulario */
        $geomechanical_diagnosis->scenario_id = $request->input("id_scenary");
        $geomechanical_diagnosis->well_azimuth = $request->input("well_azimuth");
        $geomechanical_diagnosis->well_dip = $request->input("well_dip");
        $geomechanical_diagnosis->well_radius = $request->input("well_radius");
        $geomechanical_diagnosis->max_analysis_radius = $request->input("max_analysis_radius");
        $geomechanical_diagnosis->analysis_interval = $request->input("analysis_interval");
        $geomechanical_diagnosis->reservoir_pressure = $request->input("reservoir_pressure");
        $geomechanical_diagnosis->matrix_permeability = $request->input("matrix_permeability");
        $geomechanical_diagnosis->poisson_ratio = $request->input("poisson_ratio");
        $geomechanical_diagnosis->biot_coefficient = $request->input("biot_coefficient");
        $geomechanical_diagnosis->azimuth_maximum_horizontal_stress = $request->input("azimuth_maximum_horizontal_stress");
        $geomechanical_diagnosis->minimum_horizontal_stress_gradient = $request->input("minimum_horizontal_stress_gradient");
        $geomechanical_diagnosis->vertical_stress_gradient = $request->input("vertical_stress_gradient");
        $geomechanical_diagnosis->maximum_horizontal_stress_gradient = $request->input("maximum_horizontal_stress_gradient");
        $geomechanical_diagnosis->initial_fracture_width = $request->input("initial_fracture_width");
        $geomechanical_diagnosis->initial_fracture_toughness = $request->input("initial_fracture_toughness");
        $geomechanical_diagnosis->fracture_closure_permeability = $request->input("fracture_closure_permeability");
        $geomechanical_diagnosis->residual_fracture_closure_permeability = $request->input("residual_fracture_closure_permeability");
        $geomechanical_diagnosis->top = $request->input("top");
        $geomechanical_diagnosis->netpay = $request->input("netpay");
        $geomechanical_diagnosis->viscosity = $request->input("viscosity");
        $geomechanical_diagnosis->volumetric_factor = $request->input("volumetric_factor");
        $geomechanical_diagnosis->rate = $request->input("rate");
        $geomechanical_diagnosis->drainage_radius = $request->input("drainage_radius");
        $geomechanical_diagnosis->status_wr = $button_sw;
        $geomechanical_diagnosis->save();
        
        
        #Datos tablas
        #Eliminando anteriores
        geomechanical_diagnosis_well_bottom_pressure_table::where('geomechanical_diagnosis_id',$geomechanical_diagnosis->id)->delete();
        geomechanical_diagnosis_fractures_table::where('geomechanical_diagnosis_id',$geomechanical_diagnosis->id)->delete();
        #Well bottom pressure table
        // $well_bottom_pressure_table_data = json_decode($request->input("well_bottom_pressure_table_data"));

        // $well_bottom_pressure_array = [];

        // foreach ($well_bottom_pressure_table_data as $value) 
        // {
        //     $well_bottom_pressure_table = new geomechanical_diagnosis_well_bottom_pressure_table();
        //     $well_bottom_pressure_table->geomechanical_diagnosis_id = $geomechanical_diagnosis->id;
        //     $well_bottom_pressure_table->well_bottom_pressure = $value[0];
        //     $well_bottom_pressure_table->save();

        //     array_push($well_bottom_pressure_array, $well_bottom_pressure_table->well_bottom_pressure);
        // }

        #Fractures table
        $fractures_table_data = json_decode($request->input("fractures_table_data"));

        $fracture_depth_array = array();
        $fracture_dip_array = array();
        $fracture_dip_azimuth_array = array();

        $fractures_table_data = is_null($fractures_table_data) ? [] : $fractures_table_data;
        foreach ($fractures_table_data as $value) {
            $fractures_table = new geomechanical_diagnosis_fractures_table();
            $fractures_table->geomechanical_diagnosis_id = $geomechanical_diagnosis->id;
            $fractures_table->depth = $value[0];
            $fractures_table->dip = $value[1];
            $fractures_table->dip_azimuth = $value[2];
            $fractures_table->save();

            array_push($fracture_depth_array, $fractures_table->depth);
            array_push($fracture_dip_array, $fractures_table->dip);
            array_push($fracture_dip_azimuth_array, $fractures_table->dip_azimuth);
        }

        $scenario_id = $geomechanical_diagnosis->scenario_id;

        #Cálculos

        $well_azimuth = $geomechanical_diagnosis->well_azimuth;
        $well_dip = $geomechanical_diagnosis->well_dip;
        $well_radius = $geomechanical_diagnosis->well_radius;
        $radius_step = self::radius_step;
        $analysis_radius = $geomechanical_diagnosis->max_analysis_radius;
        $poisson_ratio = $geomechanical_diagnosis->poisson_ratio;
        $biot_factor = $geomechanical_diagnosis->biot_coefficient;
        $minimum_horizontal_stress_gradient = $geomechanical_diagnosis->minimum_horizontal_stress_gradient;
        $maximum_horizontal_stress_gradient = $geomechanical_diagnosis->maximum_horizontal_stress_gradient;
        $vertical_stress_gradient = $geomechanical_diagnosis->vertical_stress_gradient;
        $azimuth_maximum_horizontal_stress = $geomechanical_diagnosis->azimuth_maximum_horizontal_stress;
        $initial_fracture_width = $geomechanical_diagnosis->initial_fracture_width;
        $initial_fracture_toughness = $geomechanical_diagnosis->initial_fracture_toughness;
        $fracture_closure_permeability = $geomechanical_diagnosis->fracture_closure_permeability;
        $residual_fracture_closure_permeability = $geomechanical_diagnosis->residual_fracture_closure_permeability;
        $interval_size = $geomechanical_diagnosis->analysis_interval;
        $matriz_permeability = $geomechanical_diagnosis->matrix_permeability;

        $top = $geomechanical_diagnosis->top;
        $top = $top == '' ? 0 : $top;

        $netpay = $geomechanical_diagnosis->netpay;
        $netpay = $netpay == '' ? 0 : $netpay;

        $viscosity = $geomechanical_diagnosis->viscosity;
        $volumetric_factor = $geomechanical_diagnosis->volumetric_factor;
        $rate = $geomechanical_diagnosis->rate;
        $reservoir_pressure = $geomechanical_diagnosis->reservoir_pressure;
        $drainage_radius = $geomechanical_diagnosis->drainage_radius;

        if (!$geomechanical_diagnosis->status_wr) {
            $geomechanical_diagnosis_results = $this->geomechanical_diagnosis($well_azimuth, $well_dip, $well_radius, $analysis_radius, $radius_step, $poisson_ratio, $biot_factor, $minimum_horizontal_stress_gradient, $maximum_horizontal_stress_gradient,$vertical_stress_gradient, $azimuth_maximum_horizontal_stress, $initial_fracture_width, $initial_fracture_toughness, $fracture_closure_permeability, $residual_fracture_closure_permeability, $fracture_depth_array, $fracture_dip_array, $fracture_dip_azimuth_array, $matriz_permeability, $top, $netpay, $viscosity, $volumetric_factor, $rate, $reservoir_pressure, $drainage_radius);


            $radii = $geomechanical_diagnosis_results[0];
            $thetas = $geomechanical_diagnosis_results[1];
            $Wfracture = $geomechanical_diagnosis_results[2];
            $Kfracture = $geomechanical_diagnosis_results[3];
            $z = $geomechanical_diagnosis_results[4];
            $Sn = $geomechanical_diagnosis_results[5];
            $ST1 = $geomechanical_diagnosis_results[6];
            $ST2 = $geomechanical_diagnosis_results[7];
            $ST3 = $geomechanical_diagnosis_results[8];
            $azimuth_maximum_horizontal_stress_index = $geomechanical_diagnosis_results[9];
            $pore_pressures = $geomechanical_diagnosis_results[10];
            $fracture_lines = $geomechanical_diagnosis_results[11];
            $Sr_graph = $geomechanical_diagnosis_results[12];
            $St_graph = $geomechanical_diagnosis_results[13];
            $Sz_graph = $geomechanical_diagnosis_results[14];
        } else {
            $radii = [];
            $thetas = [];
            $Wfracture = [];
            $Kfracture = [];
            $z = [];
            $Sn = [];
            $ST1 = [];
            $ST2 = [];
            $ST3 = [];
            $azimuth_maximum_horizontal_stress_index = 0;
            $pore_pressures = [];
            $fracture_lines = [];
            $Sr_graph = [];
            $St_graph = [];
            $Sz_graph = [];
        }
        
        $complete_pore_pressures = [];
        for($i=0; $i<count($pore_pressures); $i++){
            $aux7 = [];
            for($j=0; $j<count($pore_pressures[$i]); $j++){
                array_push($aux7, array($radii[$j], $pore_pressures[$i][$j]));
            }
            
            $auxObj = array(
                'name' => "Pore Pressure",
                'data' => $aux7
            );
            
            array_push($complete_pore_pressures, json_encode($auxObj));
        }

        #Conversion factor -> Fracture width in um
        for($f=0; $f<count($fracture_depth_array);$f++){
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    $Wfracture[$f][$r][$t] = $Wfracture[$f][$r][$t] * 304800;
                }
            }
        }

        $radii = $this->object_to_array($radii);
        #$thetas = $this->object_to_array($thetas);
        #$Wfracture = $this->object_to_array($Wfracture);
        #$Kfracture = $this->object_to_array($Kfracture);
        $max_radius = (count($radii) > 0) ? max($radii) : 0;

        /*  Line of max_azimuth */
        $aux8 = [];
        $max_azimuth_line = json_encode([]);
        if (isset($thetas[$azimuth_maximum_horizontal_stress_index])) {
            if($thetas[$azimuth_maximum_horizontal_stress_index] > pi()){
                array_push($aux8, array($max_radius*cos($thetas[$azimuth_maximum_horizontal_stress_index]-pi()), $max_radius*sin($thetas[$azimuth_maximum_horizontal_stress_index]-pi())));
            } else {
                array_push($aux8, array($max_radius*cos($thetas[$azimuth_maximum_horizontal_stress_index]+pi()), $max_radius*sin($thetas[$azimuth_maximum_horizontal_stress_index]+pi())));
            }
            /* array_push($aux8, array($max_radius*cos($thetas[$azimuth_maximum_horizontal_stress_index+pi()]), $max_radius*sin($thetas[$azimuth_maximum_horizontal_stress_index+pi()]))); */
            array_push($aux8, array($max_radius*cos($thetas[$azimuth_maximum_horizontal_stress_index]), $max_radius*sin($thetas[$azimuth_maximum_horizontal_stress_index])));
            $max_azimuth_line = json_encode(array(
                'name' => "Max Azimuth " . round(rad2deg($thetas[$azimuth_maximum_horizontal_stress_index]), 2) . " °",
                'data' => $aux8,
                'dashStyle' => 'shortdot'
            ));
        }

        $scenary = escenario::find($scenario_id);
        $scenary->estado=1;
        $scenary->completo=1;
        $scenary->save();

        #Data for contour plots
        $fracture_permeability = [];
        # $fracture_width = [];
        $normal_stress = [];
        for($f=0; $f<count($fracture_depth_array);$f++){
            $aux1 = [];
            # $aux2 = [];
            $aux3 = [];
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Kfracture[$f][$r][$t]));
                    # array_push($aux2, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Wfracture[$i][$f][$r][$t]));
                    array_push($aux3, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Sn[$f][$r][$t]));
                }
            }
            array_push($fracture_permeability, $aux1);
            # array_push($fracture_width, $aux2);
            array_push($normal_stress, $aux3);
        }

        #Data for avreage permeability (only fractures)
        // $average_permeability_only_fractures = [];
        // for($i=0; $i<count($well_bottom_pressure_array);$i++){
        //     $inter = 0;
        //     foreach ($intervals as $interval) {
        //         $aux1 = [];
        //         for($r=0; $r<count($radii); $r++){
        //             for($t=0; $t<count($thetas); $t++){

        //                 for($f=0; $f<count($fracture_depth_array);$f++){
        //                     #dd($z[$f][$r][$t], floatval($interval[0]), $interval[1]);
        //                     if (($z[$f][$r][$t] > floatval($interval[0])) and ($z[$f][$r][$t] < $interval[1])){
        //                         array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $average_permeability[$i][$inter][$r][$t]));
        //                         #continue 2;
        //                     }
        //                 }

        //             }
        //         }
        //         array_push($average_permeability_only_fractures, $aux1);
        //         $inter++;
        //     }
        // }
        
        // $average_permeability_only_fractures = [];
        // for($i=0; $i<count($well_bottom_pressure_array);$i++){
        //     for($inter=0; $inter<count($intervals);$inter++){
        //         $aux1 = [];
        //         for($r=0; $r<count($radii); $r++){
        //             for($t=0; $t<count($thetas); $t++){
        //                 if ($average_permeability[$i][$inter][$r][$t] > $matriz_permeability){
        //                     array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $average_permeability[$i][$inter][$r][$t]));
        //                 }
        //             }
        //         }
        //         array_push($average_permeability_only_fractures, $aux1);
        //     }
        // }

        /* Depths */
        $depths = [];
        for($f=0; $f<count($fracture_depth_array);$f++){
            $aux1 = [];
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $z[$f][$r][$t]));                
                }
            }
            array_push($depths, $aux1);
        }

        /* Thetas in degree */
        $thetas_array_aux = [];
        foreach ($thetas as $value) {
            array_push($thetas_array_aux, rad2deg($value));
        }
        $thetas = $thetas_array_aux;

        #Rounding radii
        $radii_array_aux = [];
        foreach ($radii as $value) {
            array_push($radii_array_aux, round($value, 4));
        }
        $radii = $radii_array_aux;

        /* Data for 2d plots */
        $fracture_permeability_given_radius = [];
        $fracture_width_given_radius = [];
        for($f=0; $f<count($fracture_depth_array);$f++) {
            $aux1 = [];
            $aux2 = [];
            for($r=0; $r<count($radii); $r++){
                $aux3 = [];
                $aux4 = [];
                for($t=0; $t<count($thetas); $t++){
                    array_push($aux3, array(round($thetas[$t], 4), round($Kfracture[$f][$r][$t], 4)));
                    array_push($aux4, array(round($thetas[$t], 4), round($Wfracture[$f][$r][$t], 4)));
                }
                array_push($aux1, $aux3);
                array_push($aux2, $aux4);
            }
            array_push($fracture_permeability_given_radius, $aux1);
            array_push($fracture_width_given_radius, $aux2);
        }

        $fracture_permeability_given_theta = [];
        $fracture_width_given_theta = [];
        $fracture_permeability_vs_stress_given_theta = [];
        for($f=0; $f<count($fracture_depth_array);$f++) {
            $aux1 = [];
            $aux2 = [];
            $aux5 = [];
            for($t=0; $t<count($thetas); $t++) {
                $aux3 = [];
                $aux4 = [];
                $aux6 = [];
                for($r=0; $r<count($radii); $r++) {
                    array_push($aux3, array(round($radii[$r], 4), round($Kfracture[$f][$r][$t], 4)));
                    array_push($aux4, array(round($radii[$r], 4), round($Wfracture[$f][$r][$t], 4)));
                    array_push($aux6, array(round($Sn[$f][$r][$t], 4), round($Kfracture[$f][$r][$t], 4)));
                }
                array_push($aux1, $aux3);
                array_push($aux2, $aux4);
                array_push($aux5, $aux6);
            }
            array_push($fracture_permeability_given_theta, $aux1);
            array_push($fracture_width_given_theta, $aux2);
            array_push($fracture_permeability_vs_stress_given_theta, $aux5);
        }

        $min_depth = ceil($top);
        $max_depth = floor($top + $netpay);

        unset($_SESSION['scenary_id_dup']);

        return View::make('geomechanical_diagnosis_results', compact('geomechanical_diagnosis','scenario_id','fracture_depth_array','max_radius','complete_pore_pressures', 'radii', 'thetas', 'fracture_permeability', 'fracture_permeability_given_radius', 'fracture_width_given_radius', 'fracture_permeability_given_theta', 'fracture_width_given_theta', 'depths', 'min_depth', 'max_depth', 'fracture_lines', 'well_radius', 'Sr_graph', 'St_graph', 'Sz_graph', 'normal_stress', 'azimuth_maximum_horizontal_stress_index', 'fracture_permeability_vs_stress_given_theta', 'max_azimuth_line','scenary'));
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

    public function fracture_plane_constants($fracture_depth_array, $fracture_dip_array, $fracture_dip_azimuth_array, $well_radius, $azimuth_maximum_horizontal_stress)
    {
        $n1 = [];
        $n2 = [];
        $n3 = [];
        $A = [];
        $B = [];
        $C = [];
        $D = [];

        $fracture_dip_azimuth_array_aux = [];
        $fracture_dip_azimuth_array2 = [];
        foreach ($fracture_dip_azimuth_array as $value) 
        {

            # dip azimuth correction
            if ($value >= $azimuth_maximum_horizontal_stress)
            {
                $value -= $azimuth_maximum_horizontal_stress;
            }
            else
            {
                $value = (2 * pi()) - ($azimuth_maximum_horizontal_stress - $value);
            }

            array_push($fracture_dip_azimuth_array_aux, $value);
        }
        $fracture_dip_azimuth_array2 = $fracture_dip_azimuth_array_aux;

        #Loop over all fractures
        for ($f=0; $f<count($fracture_depth_array); $f++)
        {
            $n1[$f] = sin($fracture_dip_array[$f]) * cos($fracture_dip_azimuth_array2[$f]);
            $n2[$f] = sin($fracture_dip_array[$f]) * sin($fracture_dip_azimuth_array2[$f]);
            $n3[$f] = cos($fracture_dip_array[$f]);
            #dd(array($n1[$f], $n2[$f], $n3[$f]));
            if ($fracture_dip_azimuth_array2[$f] > pi()){
                $x1 = $well_radius * cos($fracture_dip_azimuth_array2[$f] - pi());
                $y1 = $well_radius * sin($fracture_dip_azimuth_array2[$f] - pi());
                $z1 = $fracture_depth_array[$f];
            } 
            else
            {
                $x1 = $well_radius * cos($fracture_dip_azimuth_array2[$f] + pi());
                $y1 = $well_radius * sin($fracture_dip_azimuth_array2[$f] + pi());
                $z1 = $fracture_depth_array[$f];
            }

            # Plane constants
            $A[$f] = $n1[$f];
            $B[$f] = $n2[$f];
            $C[$f] = $n3[$f];
            $D[$f] = - ($n1[$f] * $x1) - ($n2[$f] * $y1) - ($n3[$f] * $z1);
        }

        return array($n1, $n2, $n3, $A, $B, $C, $D);

    }

    public function fracture_plane_constants_for_lines_graph($fracture_depth_array, $fracture_dip_array, $fracture_dip_azimuth_array, $well_radius)
    {
        $n1 = [];
        $n2 = [];
        $n3 = [];
        $A = [];
        $B = [];
        $C = [];
        $D = [];

        #Loop over all fractures
        for ($f=0; $f<count($fracture_depth_array); $f++)
        {
            $n1[$f] = sin($fracture_dip_array[$f]) * cos($fracture_dip_azimuth_array[$f]);
            $n2[$f] = sin($fracture_dip_array[$f]) * sin($fracture_dip_azimuth_array[$f]);
            $n3[$f] = cos($fracture_dip_array[$f]);

            if ($fracture_dip_azimuth_array[$f] > pi()){
                $x1 = $well_radius * cos($fracture_dip_azimuth_array[$f] - pi());
                $y1 = $well_radius * sin($fracture_dip_azimuth_array[$f] - pi());
                $z1 = $fracture_depth_array[$f];
            } 
            else
            {
                $x1 = $well_radius * cos($fracture_dip_azimuth_array[$f] + pi());
                $y1 = $well_radius * sin($fracture_dip_azimuth_array[$f] + pi());
                $z1 = $fracture_depth_array[$f];
            }

            # Plane constants
            $A[$f] = $n1[$f];
            $B[$f] = $n2[$f];
            $C[$f] = $n3[$f];
            $D[$f] = - ($n1[$f] * $x1) - ($n2[$f] * $y1) - ($n3[$f] * $z1);
        }

        return array($n1, $n2, $n3, $A, $B, $C, $D);

    }

    public function fracture_depth($A, $B, $C, $D, $radii, $thetas)
    {
        #Sizing arrays
        $dimension = array(count($A), count($radii), count($thetas));
        $Z = $this->create_fixed_array($dimension);
        
        for($i=0; $i<count($radii); $i++){
            for($j=0; $j<count($thetas); $j++){
                for($f=0; $f<count($A); $f++){
                    $x = $radii[$i] * cos($thetas[$j]);
                    $y = $radii[$i] * sin($thetas[$j]);

                    #The fracture depth is positive
                    $Z[$f][$i][$j] = abs((-$D[$f] - ($A[$f] * $x) - ($B[$f] * $y)) / $C[$f]);
                }
            }
        }

        return $Z;
    }

    public function fracture_lines_for_depth($fracture_dip_array, $fracture_dip_azimuth_array,$A, $B, $C, $D, $max_radius, $min_depth, $max_depth)
    {
        $fracture_lines = [];
        $x_array = $this->linspace(-$max_radius,$max_radius,self::radius_step * 2);
        $y_array = $this->linspace(-$max_radius,$max_radius,self::radius_step * 2);
        for($d=$min_depth; $d <= $max_depth; $d++){
            $aux1 = [];
            for($f=0; $f<count($A); $f++){
                if($B[$f] <> 0){
                    $aux2 = [];
                    foreach($x_array as $x){
                        $y = (-$D[$f] - ($A[$f] * $x) - ($C[$f] * -$d)) / $B[$f];
                        if(abs($y) < $max_radius){
                            array_push($aux2, array($x, $y));
                        }
                    }
                    if(! empty($aux2)){

                        array_push($aux1, array($aux2, "Fracture: " . ($f+1) . " Dip: " . rad2deg($fracture_dip_array[$f]) . " ° Dip Azimuth: " . rad2deg($fracture_dip_azimuth_array[$f]) . " °"));
                    }
                } else {
                    $aux2 = [];
                    $x = (-$D[$f] - ($C[$f] * -$d)) / $A[$f];
                    if(abs($x) < $max_radius){
                        foreach($y_array as $y){
                            array_push($aux2, array($x, $y));
                        }
                    }
                    if(! empty($aux2)){
                        array_push($aux1, array($aux2, "Fracture: " . ($f+1) . " Dip: " . rad2deg($fracture_dip_array[$f]) . " ° Dip Azimuth: " . rad2deg($fracture_dip_azimuth_array[$f]) . " °"));
                    }
                }
            }
            
            array_push($fracture_lines, $aux1);
            
        }

        return $fracture_lines;
    }

    public function initial_fracture_permeability($fracture_closure_permeability, $residual_fracture_closure_permeability, $fractures_number,
        $radii_number, $thetas_number)
    {
        #Sizing array
        $dimension = array($fractures_number, $radii_number, $thetas_number);
        $initial_fracture_permeability = $this->create_fixed_array($dimension);

        #Fracture permeability intiated with the harmonic average
        for($f=0; $f<$fractures_number; $f++){
            for($i=0; $i<$radii_number; $i++){
                for($j=0; $j<$thetas_number; $j++){
                    $initial_fracture_permeability[$f][$i][$j] = 2.0/((1.0/$fracture_closure_permeability)+(1.0/$residual_fracture_closure_permeability));
                }
            }
        }

        return $initial_fracture_permeability;
    }

    public function average_fractures_permeability($fracture_permeability_array, $top, $netpay, $radii, $thetas, $Z, $fractures_number)
    {
        $n = 0;
        $sum = 0;
        $sum2 = 0;

        for($f=0; $f<$fractures_number; $f++){
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    if ($Z[$f][$r][$t] >= $top and $Z[$f][$r][$t] <= ($top + $netpay)){
                        $sum += (1.0/$fracture_permeability_array[$f][$r][$t]);
                        $n += 1;
                    }
                }
            }
            $kf = $n / $sum;
            $sum2 += (1.0/$kf);
        }

        $fractures_permeability = $fractures_number / $sum2;
        
        return $fractures_permeability;
    }

    public function pressure_vr_radius_oil($well_dip, $matriz_permeability, $fractures_permeability, $netpay, $viscosity, $volumetric_factor, $well_radius, $radii, $reservoir_pressure, $rate, $drainage_radius)
    {
        $dimension = array(count($radii));
        $pore_pressures = $this->create_fixed_array($dimension);

        $well_dip = rad2deg($well_dip);

        $s = -pow(($well_dip/41.0),2.06)-pow(($well_dip/56.0),1.865)*log($netpay/(100 *$well_radius));

        $numerador = 141.2*$rate*$viscosity*$volumetric_factor*(log($drainage_radius/$well_radius) - (pow($drainage_radius,2)/(2*pow($drainage_radius,2))) + $s);
        $denominador = ($matriz_permeability+$fractures_permeability) * $netpay;
        $pwf = $reservoir_pressure - $numerador/$denominador;

        for($i=0; $i<count($radii); $i++){
            $numerador = 141.2*$rate*$viscosity*$volumetric_factor*(log($radii[$i]/$well_radius) - (pow($radii[$i],2)/(2*pow($drainage_radius,2))) + $s);
            $denominador = ($matriz_permeability+$fractures_permeability) * $netpay;
            $pore_pressures[$i] = $pwf + $numerador/$denominador;
        }

        return $pore_pressures;
    }

    public function geomechanical_diagnosis($well_azimuth, $well_dip, $well_radius, $max_analysis_radius, $radius_step, $poisson_ratio, $biot_factor, $minimum_horizontal_stress_gradient,
       $maximum_horizontal_stress_gradient, $vertical_stress_gradient, $azimuth_maximum_horizontal_stress, $initial_fracture_width,
       $initial_fracture_toughness, $fracture_closure_permeability, $residual_fracture_closure_permeability, $fracture_depth_array,
       $fracture_dip_array, $fracture_dip_azimuth_array, $matriz_permeability, $top, $netpay,
       $viscosity, $volumetric_factor, $rate, $reservoir_pressure, $drainage_radius)
    {
        ini_set('memory_limit', '-1');

        # initial fracture with must be in ft
        $initial_fracture_width = $initial_fracture_width * 3.2808e-6;

        # Negative depths - negative Z axis
        $fracture_depth_array_aux = [];
        foreach ($fracture_depth_array as $value) 
        {
            array_push($fracture_depth_array_aux, -$value);
        }
        $fracture_depth_array = $fracture_depth_array_aux;

        # Converting from degree to radians
        $well_azimuth = deg2rad($well_azimuth);
        $well_dip = deg2rad($well_dip);
        $azimuth_maximum_horizontal_stress = deg2rad($azimuth_maximum_horizontal_stress);
        $fracture_dip_array_aux = [];
        foreach ($fracture_dip_array as $value) 
        {
            array_push($fracture_dip_array_aux, deg2rad($value));
        }
        $fracture_dip_array = $fracture_dip_array_aux;
        $fracture_dip_azimuth_array_aux = [];
        foreach ($fracture_dip_azimuth_array as $value) 
        {
            array_push($fracture_dip_azimuth_array_aux, deg2rad($value));
        }
        $fracture_dip_azimuth_array = $fracture_dip_azimuth_array_aux;
        
        # Well azimuth correction
        #if ($well_azimuth >= $azimuth_maximum_horizontal_stress)
        #{
        #    $well_azimuth -= $azimuth_maximum_horizontal_stress;
        #}
        #else
        #{
        #    $well_azimuth = (2 * pi()) - ($azimuth_maximum_horizontal_stress - $well_azimuth);
        #}

        # Rotations calculation
        $lxx = cos($well_azimuth) * cos($well_dip);
        $lxy = sin($well_azimuth) * cos($well_dip);
        $lxz = -sin($well_dip);
        $lyx = -sin($well_azimuth);
        $lyy = cos($well_azimuth);
        $lyz = 0;
        $lzx = cos($well_azimuth) * sin($well_dip);
        $lzy = sin($well_azimuth) * sin($well_dip);
        $lzz = cos($well_dip);

        #dd($lxx, $lxy, $lxz, $lyx, $lyy, $lyz, $lzx, $lzy, $lzz);
        # Calculating $radius, $pressures, and $thetas
        $radii = $this->logspace($well_radius, $max_analysis_radius, $radius_step); #Geom
        $thetas = range(0, 359);
        #dd($thetas);
        $thetas_array_aux = [];
        foreach ($thetas as $value) 
        {
            array_push($thetas_array_aux, deg2rad($value));
        }
        $thetas = $thetas_array_aux;
        if(! in_array($azimuth_maximum_horizontal_stress, $thetas)){
            array_push($thetas, $azimuth_maximum_horizontal_stress);
            sort($thetas);
        }
        $azimuth_maximum_horizontal_stress_index = array_search($azimuth_maximum_horizontal_stress, $thetas);
        
        #Sizing arrays
        $dimension = array(count($fracture_depth_array), count($radii), count($thetas));

        $Wfracture = $this->create_fixed_array($dimension);
        $Kfracture = $this->create_fixed_array($dimension);
        $ST1 = $this->create_fixed_array($dimension);
        $ST2 = $this->create_fixed_array($dimension);
        $ST3 = $this->create_fixed_array($dimension);
        $Sn = $this->create_fixed_array($dimension);
        #$z = $this->create_fixed_array($dimension);

        $dimension = array($radius_step);
        $relative_error = $this->create_fixed_array($dimension);

        #Calculating $intervals for $averages
        // $intervals = [[$top, $top + $interval_size]];
        // $last_depth = $top + $interval_size;

        // while(($last_depth + $interval_size) < ($top + $netpay))
        // {
        //     array_push($intervals, array($last_depth, $last_depth + $interval_size));
        //     $last_depth += $interval_size;
        // }

        // array_push($intervals, array($last_depth, $top + $netpay));

        #Calculating fracture plane constants
        $result = $this->fracture_plane_constants($fracture_depth_array, $fracture_dip_array, $fracture_dip_azimuth_array, $well_radius, $azimuth_maximum_horizontal_stress);

        $n1 = $result[0];
        $n2 = $result[1];
        $n3 = $result[2];
        $A = $result[3];
        $B = $result[4];
        $C = $result[5];
        $D = $result[6];

        $result = $this->fracture_plane_constants_for_lines_graph($fracture_depth_array, $fracture_dip_array, $fracture_dip_azimuth_array, $well_radius);

        $n1_l = $result[0];
        $n2_l = $result[1];
        $n3_l = $result[2];
        $A_l = $result[3];
        $B_l = $result[4];
        $C_l = $result[5];
        $D_l = $result[6];

        #Calculating fractures depth for a given [r,theta] point
        $Z = $this->fracture_depth($A, $B, $C, $D, $radii, $thetas);

        $min_depth = ceil($top);
        $max_depth = floor($top + $netpay);

        $fracture_lines = $this->fracture_lines_for_depth($fracture_dip_array, $fracture_dip_azimuth_array,$A_l, $B_l, $C_l, $D_l, $max_analysis_radius, $min_depth, $max_depth);

        $dimension = array($max_depth - $min_depth + 1, count($thetas), count($radii));
        $Sr_graph = $this->create_fixed_array($dimension);
        $St_graph = $this->create_fixed_array($dimension);
        $Sz_graph = $this->create_fixed_array($dimension);

        $all_pore_pressure = [];
        
        #Assigment of a initial value for fractures permeability
        $aux = $this->initial_fracture_permeability($fracture_closure_permeability, $residual_fracture_closure_permeability,
            count($A), count($radii), count($thetas));
        
        #Sizing aux array
        $dimension = array(count($A), count($radii), count($thetas));
        $aux2 = $this->create_fixed_array($dimension);
        
        for($f=0; $f<count($A); $f++){
            for($r=0; $r<count($radii); $r++){
                for($t=0; $t<count($thetas); $t++){
                    $Kfracture[$f][$r][$t] = $aux[$f][$r][$t];
                    $aux2[$f][$r][$t] = $aux[$f][$r][$t];
                }
            }
        }
        
        $fractures_permeability = $this->average_fractures_permeability($aux2, $top, $netpay, $radii, $thetas, $Z, count($A));
        
        $pore_pressures_old = $this->pressure_vr_radius_oil($well_dip, $matriz_permeability, $fractures_permeability, $netpay, $viscosity, $volumetric_factor,
            $well_radius, $radii, $reservoir_pressure, $rate, $drainage_radius);

        $converged = false;

        while(!$converged){

            for ($f=0; $f < count($fracture_depth_array) ; $f++)
            {
                for ($r=0; $r < count($radii) ; $r++) 
                {
                    $radius = $radii[$r];
                    $pore_pressure = $pore_pressures_old[$r];
                    $pwf = $pore_pressures_old[0];

                    for ($t=0; $t < count($thetas) ; $t++) 
                    {
                        $theta = $thetas[$t];
                        
                        # Theta correction with max $stress azimuth
                        if($theta >= $azimuth_maximum_horizontal_stress)
                        {
                            $theta -= $azimuth_maximum_horizontal_stress;
                        }
                        else if($theta < $azimuth_maximum_horizontal_stress)
                        {
                            $theta = (2 * pi()) - ($azimuth_maximum_horizontal_stress - $theta);
                        }

                        $SH = $Z[$f][$r][$t] * $maximum_horizontal_stress_gradient;  # $maximum_horizontal_stress
                        $Sh = $Z[$f][$r][$t] * $minimum_horizontal_stress_gradient;  # $minimum_horizontal_stress
                        $Sv = $Z[$f][$r][$t] * $vertical_stress_gradient;  # vertical_stress

                        # Initial $stress
                        $s0x = (($lxx ** 2) * $SH) + (($lxy ** 2) * $Sh) + (($lxz ** 2) * $Sv);
                        $s0y = (($lyx ** 2) * $SH) + (($lyy ** 2) * $Sh) + (($lyz ** 2) * $Sv);
                        $s0z = (($lzx ** 2) * $SH) + (($lzy ** 2) * $Sh) + (($lzz ** 2) * $Sv);
                        $t0xy = ($lxx * $lyx * $SH) + ($lxy * $lyy * $Sh) + ($lxz * $lyz * $Sv);
                        $t0yz = ($lyx * $lzx * $SH) + ($lyy * $lzy * $Sh) + ($lyz * $lzz * $Sv);
                        $t0xz = ($lxx * $lzx * $SH) + ($lxy * $lzy * $Sh) + ($lxz * $lzz * $Sv);

                        # Stresses around the well
                        $Sr = ((($s0x + $s0y) / 2) * (1 - (($well_radius ** 2) / ($radius ** 2)))) + ((($s0x - $s0y) / 2) * (1 + (3 * ($well_radius ** 4 / $radius ** 4)) - (4 * ($well_radius ** 2 / $radius ** 2))) * cos(2 * $theta)) + ($t0xy * (1 + (3 * ($well_radius ** 4 / $radius ** 4)) - (4 * ($well_radius ** 2 / $radius ** 2))) * sin(2 * $theta)) + (($pwf * ($well_radius ** 2 / $radius ** 2))) - ($biot_factor * $pore_pressure);

                        $St = ((($s0x + $s0y) / 2.0) * (1.0 + ($well_radius ** 2 / $radius ** 2))) - ((($s0x - $s0y) / 2.0) * (1.0 + (3.0 * $well_radius ** 4 / $radius ** 4)) * cos(2.0 * $theta)) - ($t0xy * (1.0 + (3.0 * $well_radius ** 4 / $radius ** 4)) * sin(2.0 * $theta)) - ($pwf * $well_radius ** 2 / $radius ** 2) - ($biot_factor * $pore_pressure);

                        $Sz = $s0z - ($poisson_ratio * ((2 * ($s0x - $s0y) * ($well_radius ** 2 / $radius ** 2) * cos(2 * $theta)) + (4 * $t0xy * ($well_radius ** 2 / $radius ** 2) * sin(2 * $theta)))) - ($biot_factor * $pore_pressure);

                        $Trt = (($s0x - $s0y / 2) * (1 - (3 * ($well_radius ** 4 / $radius ** 4)) + (2 * ($well_radius ** 2 / $radius ** 2))) * sin(2 * $theta)) + ($t0xy * (1 - (3 * ($well_radius ** 4 / $radius ** 4)) + (2 * ($well_radius ** 2 / $radius ** 2))) * cos(2 * $theta));

                        $Ttz = ((-$t0xz * sin($theta)) + ($t0yz * cos($theta))) * (1 + ($well_radius ** 2 / $radius ** 2));

                        $Trz = (($t0xz * cos($theta)) + ($t0yz * sin($theta))) * (1 - ($well_radius ** 2 / $radius ** 2));

                        # Normal $stress
                        $ST1[$f][$r][$t] = ($Sr * $n1[$f]) + ($Trt * $n2[$f]) + ($Trz * $n3[$f]);
                        $ST2[$f][$r][$t] = ($Trt * $n1[$f]) + ($St * $n2[$f]) + ($Ttz * $n3[$f]);
                        $ST3[$f][$r][$t] = ($Trz * $n1[$f]) + ($Ttz * $n2[$f]) + ($Sz * $n3[$f]);

                        // $a = CArray::fromArray(array(
                        //     array($Sr, $Trt, $Trz),
                        //     array($Trt, $St, $Ttz),
                        //     array($Trz, $Ttz, $Sz)
                        // ));
                        // $result_call = CArray::eigvals($a);
                        // # dd(array($n1[$f], $n2[$f], $n3[$f]),CArray::toArray($result_call[0]), CArray::toArray($result_call[1]));
                        // $max_stresses = CArray::toArray($result_call);
                        // $max_stresses = [];
                        // array_push($max_stresses, $ST1[$i][$f][$r][$t]);
                        // array_push($max_stresses, $ST2[$i][$f][$r][$t]);
                        // array_push($max_stresses, $ST3[$i][$f][$r][$t]);

                        // rsort($max_stresses);
                        
                        // $c1 = $max_stresses[1] * $max_stresses[2];
                        // $c2 = -$max_stresses[0]*$max_stresses[2] + $max_stresses[0];
                        // $c3 = -$max_stresses[0] + $max_stresses[0]*$max_stresses[1];

                        // $size_vector = sqrt(($c1 * $c1) + ($c2 * $c2) + ($c3 * $c3));

                        // $c1 = $c1 / $size_vector;
                        // $c2 = $c2 / $size_vector;
                        // $c3 = $c3 / $size_vector;

                        # $Sn[$i][$f][$r][$t] = ($max_stresses[0] * pow($c1,2)) + ($max_stresses[1] * pow($c2,2)) + ($max_stresses[2] * pow($c3,2));
                        # $Sn[$i][$f][$r][$t] = ($max_stresses[0] * pow($n1[$f],2)) + ($max_stresses[1] * pow($n2[$f],2)) + ($max_stresses[2] * pow($n3[$f],2));
                        $Sn[$f][$r][$t] = ($ST1[$f][$r][$t] * $n1[$f]) + ($ST2[$f][$r][$t] * $n2[$f]) + ($ST3[$f][$r][$t] * $n3[$f]);
                        # $Sn[$i][$f][$r][$t] = $max_stresses[2];

                        # Fracture width and permeability
                        $BB1 = $initial_fracture_width * (1 - (($residual_fracture_closure_permeability / $fracture_closure_permeability) ** (1 / 4)));
                        $BB2 = $Sn[$f][$r][$t] / ($initial_fracture_toughness + ($Sn[$f][$r][$t] / $BB1));
                        #Log::debug($BB2 * 304800);
                        $Wfracture[$f][$r][$t] = $initial_fracture_width - $BB2;
                        $Kfracture[$f][$r][$t] = $fracture_closure_permeability * (($Wfracture[$f][$r][$t] / $initial_fracture_width) ** 4);

                    }
                }            
            }
            
            for($l=0; $l<count($A); $l++){
                for($m=0; $m<count($radii); $m++){
                    for($n=0; $n<count($thetas); $n++){
                        $aux2[$l][$m][$n] = $Kfracture[$l][$m][$n];
                    }
                }
            }

            $fractures_permeability = $this->average_fractures_permeability($aux2, $top, $netpay, $radii, $thetas, $Z, count($A));

            $pore_pressures_new = $this->pressure_vr_radius_oil($well_dip, $matriz_permeability, $fractures_permeability, $netpay, $viscosity, $volumetric_factor,
                $well_radius, $radii, $reservoir_pressure, $rate, $drainage_radius);

            
            for($v=0; $v<count($pore_pressures_old); $v++){
                $relative_error[$v]= abs($pore_pressures_old[$v] - $pore_pressures_new[$v]) / $pore_pressures_old[$v];
            }

            if (max($relative_error->toArray()) < 0.000001){
                $converged = true;
            } else {
                $pore_pressures_old = $pore_pressures_new;
            }
        }

        array_push($all_pore_pressure, $pore_pressures_new);

        # Effective stresses for graphs
        for ($d=$min_depth; $d <= $max_depth ; $d++)
        {
            for ($t=0; $t < count($thetas) ; $t++) 
            {

                $theta = $thetas[$t];

                # Theta correction with max $stress azimuth
                if($theta >= $azimuth_maximum_horizontal_stress)
                {
                    $theta -= $azimuth_maximum_horizontal_stress;
                }
                else if($theta < $azimuth_maximum_horizontal_stress)
                {
                    $theta = (2 * pi()) - ($azimuth_maximum_horizontal_stress - $theta);
                }

                for ($r=0; $r < count($radii) ; $r++) 
                {
                    $radius = $radii[$r];
                    $pore_pressure = $pore_pressures_old[$r];

                    $SH = $d * $maximum_horizontal_stress_gradient;  # $maximum_horizontal_stress
                    $Sh = $d * $minimum_horizontal_stress_gradient;  # $minimum_horizontal_stress
                    $Sv = $d * $vertical_stress_gradient;  # vertical_stress

                    # Initial $stress
                    $s0x = (($lxx ** 2) * $SH) + (($lxy ** 2) * $Sh) + (($lxz ** 2) * $Sv);
                    $s0y = (($lyx ** 2) * $SH) + (($lyy ** 2) * $Sh) + (($lyz ** 2) * $Sv);
                    $s0z = (($lzx ** 2) * $SH) + (($lzy ** 2) * $Sh) + (($lzz ** 2) * $Sv);
                    $t0xy = ($lxx * $lyx * $SH) + ($lxy * $lyy * $Sh) + ($lxz * $lyz * $Sv);
                    $t0yz = ($lyx * $lzx * $SH) + ($lyy * $lzy * $Sh) + ($lyz * $lzz * $Sv);
                    $t0xz = ($lxx * $lzx * $SH) + ($lxy * $lzy * $Sh) + ($lxz * $lzz * $Sv);

                    # Stresses around the well
                    $Sr_graph[$d-$min_depth][$t][$r] = ((($s0x + $s0y) / 2) * (1 - (($well_radius ** 2) / ($radius ** 2)))) + ((($s0x - $s0y) / 2) * (1 + (3 * ($well_radius ** 4 / $radius ** 4)) - (4 * ($well_radius ** 2 / $radius ** 2))) * cos(2 * $theta)) + ($t0xy * (1 + (3 * ($well_radius ** 4 / $radius ** 4)) - (4 * ($well_radius ** 2 / $radius ** 2))) * sin(2 * $theta)) + (($pwf * ($well_radius ** 2 / $radius ** 2))) - ($biot_factor * $pore_pressure);

                    $St_graph[$d-$min_depth][$t][$r] = ((($s0x + $s0y) / 2.0) * (1.0 + ($well_radius ** 2 / $radius ** 2))) - ((($s0x - $s0y) / 2.0) * (1.0 + (3.0 * $well_radius ** 4 / $radius ** 4)) * cos(2.0 * $theta)) - ($t0xy * (1.0 + (3.0 * $well_radius ** 4 / $radius ** 4)) * sin(2.0 * $theta)) - ($pwf * $well_radius ** 2 / $radius ** 2) - ($biot_factor * $pore_pressure);

                    $Sz_graph[$d-$min_depth][$t][$r] = $s0z - ($poisson_ratio * ((2 * ($s0x - $s0y) * ($well_radius ** 2 / $radius ** 2) * cos(2 * $theta)) + (4 * $t0xy * ($well_radius ** 2 / $radius ** 2) * sin(2 * $theta)))) - ($biot_factor * $pore_pressure);

                }
            }
        }
        
        return array($radii, $thetas, $Wfracture, $Kfracture, $Z, $Sn, $ST1, $ST2, $ST3, $azimuth_maximum_horizontal_stress_index, $all_pore_pressure, $fracture_lines, $Sr_graph->toArray(), $St_graph->toArray(), $Sz_graph->toArray());
    }

    function results_from_tree($id)
    {
        $scenario_id = $id;
        $geomechanical_diagnosis = DB::table('geomechanical_diagnosis')->where('scenario_id',$scenario_id)->first();
        $well_azimuth = $geomechanical_diagnosis->well_azimuth;
        $well_dip = $geomechanical_diagnosis->well_dip;
        $well_radius = $geomechanical_diagnosis->well_radius;
        $radius_step = 50;
        $analysis_radius = $geomechanical_diagnosis->max_analysis_radius;
        $poisson_ratio = $geomechanical_diagnosis->poisson_ratio;
        $biot_factor = $geomechanical_diagnosis->biot_coefficient;
        $minimum_horizontal_stress_gradient = $geomechanical_diagnosis->minimum_horizontal_stress_gradient;
        $maximum_horizontal_stress_gradient = $geomechanical_diagnosis->maximum_horizontal_stress_gradient;
        $vertical_stress_gradient = $geomechanical_diagnosis->vertical_stress_gradient;
        $azimuth_maximum_horizontal_stress = $geomechanical_diagnosis->azimuth_maximum_horizontal_stress;
        $initial_fracture_width = $geomechanical_diagnosis->initial_fracture_width;
        $initial_fracture_toughness = $geomechanical_diagnosis->initial_fracture_toughness;
        $fracture_closure_permeability = $geomechanical_diagnosis->fracture_closure_permeability;
        $residual_fracture_closure_permeability = $geomechanical_diagnosis->residual_fracture_closure_permeability;
        $interval_size = $geomechanical_diagnosis->analysis_interval;
        $matriz_permeability = $geomechanical_diagnosis->matrix_permeability;
        $top = $geomechanical_diagnosis->top;
        $netpay = $geomechanical_diagnosis->netpay;
        $viscosity = $geomechanical_diagnosis->viscosity;
        $volumetric_factor = $geomechanical_diagnosis->volumetric_factor;
        $rate = $geomechanical_diagnosis->rate;

        #Trayendo datos de tablas
        #Well bottom pressure table
        $well_bottom_pressure_table_data = geomechanical_diagnosis_well_bottom_pressure_table::where('geomechanical_diagnosis_id',$geomechanical_diagnosis->id)->first();
        $well_bottom_pressure_array = [];
        foreach ($well_bottom_pressure_table_data as $value) 
        {
            array_push($well_bottom_pressure_array,$value[0]);
        }

        #Fractures table
        $fractures_table_data = geomechanical_diagnosis_fractures_table::where('geomechanical_diagnosis_id',$geomechanical_diagnosis->id)->first();

        $fracture_depth_array = array();
        $fracture_dip_array = array();
        $fracture_dip_azimuth_array = array();

        foreach ($fractures_table_data as $value) 
        {
            array_push($fracture_depth_array, $value[0]);
            array_push($fracture_dip_array, $value[1]);
            array_push($fracture_dip_azimuth_array, $value[2]);
        }

        $geomechanical_diagnosis_results = $this->geomechanical_diagnosis($well_azimuth, $well_dip, $well_radius, $analysis_radius, $radius_step, $well_bottom_pressure_array, $poisson_ratio, $biot_factor, $minimum_horizontal_stress_gradient, $maximum_horizontal_stress_gradient,$vertical_stress_gradient, $azimuth_maximum_horizontal_stress, $initial_fracture_width, $initial_fracture_toughness, $fracture_closure_permeability, $residual_fracture_closure_permeability, $fracture_depth_array, $fracture_dip_array, $fracture_dip_azimuth_array, $interval_size, $matriz_permeability, $top, $netpay,
            $viscosity, $volumetric_factor, $rate);

        $radii = $geomechanical_diagnosis_results[0];
        $thetas = $geomechanical_diagnosis_results[1];
        $Wfracture = $geomechanical_diagnosis_results[2];
        $Kfracture = $geomechanical_diagnosis_results[3];
        $z = $geomechanical_diagnosis_results[4];
        $Sn = $geomechanical_diagnosis_results[5];
        $ST1 = $geomechanical_diagnosis_results[6];
        $ST2 = $geomechanical_diagnosis_results[7];
        $ST3 = $geomechanical_diagnosis_results[8];
        $average_permeability = $geomechanical_diagnosis_results[9];
        $azimuth_maximum_horizontal_stress_index = $geomechanical_diagnosis_results[10];
        $intervals = $geomechanical_diagnosis_results[11];
        $pore_pressures = $geomechanical_diagnosis_results[12];

        $complete_pore_pressures = [];
        for($i=0; $i<count($pore_pressures); $i++){
            array_push($complete_pore_pressures, array($radii[$i], $pore_pressures[$i]));
        }
        
        #Conversion factor -> Fracture width in um
        for($i=0; $i<count($well_bottom_pressure_array);$i++){
            for($f=0; $f<count($fracture_depth_array);$f++){
                for($r=0; $r<count($radii); $r++){
                    for($t=0; $t<count($thetas); $t++){
                        $Wfracture[$i][$f][$r][$t] = $Wfracture[$i][$f][$r][$t] * 304800;
                    }
                }
            }
        }

        $complete_data_sorted_wfracture = $this->sorting_data_for_charts($Wfracture, $radii, $thetas);
        $complete_data_sorted_kfracture = $this->sorting_data_for_charts($Kfracture, $radii, $thetas);
        $complete_data_sorted_average = $this->sorting_data_for_charts($average_permeability, $radii, $thetas);

        $radii = $this->object_to_array($radii);
        #$thetas = $this->object_to_array($thetas);
        #$Wfracture = $this->object_to_array($Wfracture);
        #$Kfracture = $this->object_to_array($Kfracture);
        $max_radius = max($radii);

        #Data for contour plots
        $fracture_permeability = [];
        $fracture_width = [];
        for($i=0; $i<count($well_bottom_pressure_array);$i++){
            for($f=0; $f<count($fracture_depth_array);$f++){
                $aux1 = [];
                $aux2 = [];
                for($r=0; $r<count($radii); $r++){
                    for($t=0; $t<count($thetas); $t++){
                        array_push($aux1, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Kfracture[$i][$f][$r][$t]));
                        array_push($aux2, array($radii[$r]*cos($thetas[$t]), $radii[$r]*sin($thetas[$t]), $Wfracture[$i][$f][$r][$t]));
                    }
                }
                array_push($fracture_permeability, $aux1);
                array_push($fracture_width, $aux2);
            }
        }

        #Thetas in degree
        $thetas_array_aux = [];
        foreach ($thetas as $value) 
        {
            array_push($thetas_array_aux, round(rad2deg($value),0));
        }
        $thetas = $thetas_array_aux;

        #Rounding radii
        $radii_array_aux = [];
        foreach ($radii as $value) 
        {
            array_push($radii_array_aux, round($value, 4));
        }
        $radii = $radii_array_aux;

        #Data for 2d plots
        $fracture_permeability_given_radius = [];
        $fracture_width_given_radius = [];
        for($i=0; $i<count($well_bottom_pressure_array);$i++){
            for($f=0; $f<count($fracture_depth_array);$f++){
                $aux1 = [];
                $aux2 = [];
                for($r=0; $r<count($radii); $r++){
                    $aux3 = [];
                    $aux4 = [];
                    for($t=0; $t<count($thetas); $t++){
                        array_push($aux3, array(round($thetas[$t], 4), round($Kfracture[$i][$f][$r][$t], 4)));
                        array_push($aux4, array(round($thetas[$t], 4), round($Wfracture[$i][$f][$r][$t], 4)));
                    }
                    array_push($aux1, $aux3);
                    array_push($aux2, $aux4);
                }
                array_push($fracture_permeability_given_radius, $aux1);
                array_push($fracture_width_given_radius, $aux2);
            }
        }

        $fracture_permeability_given_theta = [];
        $fracture_width_given_theta = [];
        for($i=0; $i<count($well_bottom_pressure_array);$i++){
            for($f=0; $f<count($fracture_depth_array);$f++){
                $aux1 = [];
                $aux2 = [];
                for($t=0; $t<count($thetas); $t++){
                    $aux3 = [];
                    $aux4 = [];
                    for($r=0; $r<count($radii); $r++){
                        array_push($aux3, array(round($radii[$r], 4), round($Kfracture[$i][$f][$r][$t], 4)));
                        array_push($aux4, array(round($radii[$r], 4), round($Wfracture[$i][$f][$r][$t], 4)));
                    }
                    array_push($aux1, $aux3);
                    array_push($aux2, $aux4);
                }
                array_push($fracture_permeability_given_theta, $aux1);
                array_push($fracture_width_given_theta, $aux2);
            }
        }

        return View::make('geomechanical_diagnosis_results', compact('scenario_id','complete_data_sorted_average','well_bottom_pressure_array','fracture_depth_array','intervals','max_radius','complete_pore_pressures', 'radii', 'thetas', 'fracture_permeability', 'fracture_width', 'fracture_permeability_given_radius', 'fracture_width_given_radius', 'fracture_permeability_given_theta', 'fracture_width_given_theta'));

        #return view('geomechanical_diagnosis_results')->with('scenario_id',$scenario_id)->with('complete_data_sorted_wfracture',$complete_data_sorted_wfracture);
    }
    function object_to_array($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }
    function sorting_data_for_charts($original_data, $radius, $thetas, $azimuth_maximum_horizontal_stress_index)
    {
        $complete_data_sorted = [];
        for ($m=0; $m < count($original_data) ; $m++) 
        { 
            for ($n=0; $n < count($original_data[$m]) ; $n++) 
            { 
                $data = $this->create_fixed_array(array(count($radius),count($thetas)));
                for ($i=0; $i < count($radius); $i++) 
                { 
                    for ($j=0; $j < count($thetas) ; $j++) 
                    { 
                        $data[$i][$j] = $original_data[$m][$n][$i][$j];
                    }
                }
                $meshgrid_data = $this->generate_mesh($thetas, $radius);
                $x_data = $meshgrid_data[0];
                $y_data = $meshgrid_data[1];

                $complete_data = $this->create_fixed_array(array(count($x_data),count($x_data[0])));
                for ($i=0; $i <count($x_data) ; $i++) 
                { 
                    for ($j=0; $j < count($x_data[$i]) ; $j++) 
                    { 
                        $complete_data[$i][$j] = array(round($x_data[$i][$j],3),round($y_data[$i][$j],3),round($data[$i][$j],3));
                    }
                }

                $sorted_data = array();
                foreach ($complete_data as $value) 
                {
                    foreach ($value as $value_x) 
                    {
                        array_push($sorted_data, $value_x);
                    }
                }
                array_push($complete_data_sorted, $sorted_data);
            }
        }

        #Gráficos 2D
        $line_chart_data = [];
        foreach ($original_data as $value) 
        {
            foreach ($value as $value_x) 
            {
                $data_x = [];
                foreach ($value_x as $key =>  $value_y) 
                {
                    array_push($data_x, array($radius[$key], $value_y[$azimuth_maximum_horizontal_stress_index]));
                }
                array_push($line_chart_data,$data_x);
            }
        }

        return array($complete_data_sorted, $line_chart_data);
    }
    function generate_mesh($x,$y)
    {
        $xx = [];
        $yy = [];

        foreach ($y as $value) 
        {
            $aux_array = [];
            foreach ($x as $value_x) 
            {
                array_push($aux_array, $value);
            }
            array_push($yy, $aux_array);
        }

        for ($j=0; $j < count($y) ; $j++) 
        { 
            array_push($xx, $x);
        }

        $m = count($yy);
        $n = count($xx[0]);
        $p = count($xx);

        $x_data = $this->create_fixed_array(array($m,$n));
        $y_data = $this->create_fixed_array(array($m,$n));        

        #$x_data_aux = $this->create_fixed_array(array($m,$n));
        #$y_data_aux = $this->create_fixed_array(array($m,$n));

        #for ($i=0; $i < $m; $i++) 
        #{ 
        #    for ($j=0; $j < $n ; $j++) 
        #    { 
        #        $x_data[$i][$j] = 0;
        #        $y_data[$i][$j] = 0;
        #        for ($k=0; $k < $p; $k++) 
        #        { 
        #            $x_data[$i][$j] += $yy[$i][$k] * cos($xx[$k][$j]); 
        #            $y_data[$i][$j] += $yy[$i][$k] * sin($xx[$k][$j]); 
        #        }
        #    }
        #}

        for ($i=0; $i < count($xx); $i++) 
        { 
            for ($j=0; $j < count($xx[$i]) ; $j++) 
            { 
                $x_data[$i][$j] = $yy[$i][$j]*cos($xx[$i][$j]);
                $y_data[$i][$j] = $yy[$i][$j]*sin($xx[$i][$j]);
            }
            
        }

        return array($x_data,$y_data);
    }
    function create_fixed_array() 
    {
      $args = func_get_arg(0);
      $array = new SplFixedArray($args[0]);

      if (isset($args[1])) 
      {
        $newArgs = array_splice($args, 1);
        for ($i=0; $i<$args[0]; $i++) 
        {
          $array[$i] = $this->create_fixed_array($newArgs);
      }
  }
  return $array;
}

function linspace($i,$f,$n)
{
    $step = ($f-$i)/($n-1);
    return range ($i,$f,$step);
}

function logspace($a, $b, $len)
{
    $a = log10($a);
    $b = log10($b);
    $end = $len-1;
    $d = ( $b-$a ) / $end;
    $arr = new SplFixedArray($len);
    $tmp = $a;
    $arr[0] = pow(10, $tmp);
    for ($i = 1; $i < $end; $i++) 
    {
        $tmp += $d;
        $arr[$i] = pow(10, $tmp);
    }
    $arr[$end] = pow(10, $b);

    return $arr;
}
}
?>