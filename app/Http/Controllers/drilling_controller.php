<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\drilling_request;
use App\Http\Controllers\Controller;
use App\drilling;
use App\drilling_results;
use App\drilling_results_chart;
use App\d_general_data;
use App\d_average_input_data;
use App\d_intervals_input_data;
use App\d_profile_input_data;
use App\d_filtration_test_data;
use App\d_filtration_function;
use App\d_filtration_function_data;
use App\d_laboratory_test_data;
use App\d_laboratory_test;
use App\escenario;
use App\formacionxpozo;


use DB;
use View;

class drilling_controller extends Controller
{
    /** 
     * Despliega la vista inicial del escenario Drilling con la información del usuario, el pozo, la formación y el intervalo productor.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) 
        {
            $scenario = escenario::find(\Request::get('scenaryId'));
            //dd($scenario);
            return View::make('drilling', compact('scenario')); 
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
    public function store(drilling_request $request)
    {
        if (\Auth::check())
        {
            if(!isset($request->cementingAvailable) || empty($request->cementingAvailable || $request->cementingAvailable == null)) {
                $request->c_pump_rate_t = 0;
                $request->cementingAvailable = 0;
            } else {
                $request->cementingAvailable = 1;
            }

            // Storing data phase
            $drilling = new drilling();
            $drilling->scenario_id = $request->scenary_id;
            $drilling->cementingAvailable = $request->cementingAvailable;
            $drilling->filtration_function_id = $request->select_filtration_function ? $request->select_filtration_function : null;
            $drilling->a_factor = $request->a_factor_t;
            $drilling->b_factor = $request->b_factor_t;
            $drilling->d_total_exposure_time = $request->d_total_exposure_time_t;
            $drilling->d_pump_rate = $request->d_pump_rate_t;
            $drilling->d_mud_density = $request->d_mud_density_t;
            $drilling->d_plastic_viscosity = $request->d_plastic_viscosity_t;
            $drilling->d_yield_point = $request->d_yield_point_t;
            $drilling->d_rop = $request->d_rop_t;
            $drilling->d_equivalent_circulating_density = $request->d_equivalent_circulating_density_t; 
            $drilling->c_total_exposure_time = $request->c_total_exposure_time_t;
            $drilling->c_pump_rate = $request->c_pump_rate_t;
            $drilling->c_cement_slurry = $request->c_cement_slurry_density_t;
            $drilling->c_plastic_viscosity = $request->c_plastic_viscosity_t;
            $drilling->c_yield_point = $request->c_yield_point_t;
            $drilling->c_equivalent_circulating_density = $request->c_equivalent_circulating_density_t; 
            $drilling->general_interval_select = $request->select_interval_general_data;
            $drilling->input_data_select = $request->select_input_data;
            $drilling->status_wr = $request->only_s == "save" ? 1 : 0;
            $drilling->save();
            
            // General data table
            $drilling_general = json_decode($request->generaldata_table);
            $drilling_general = is_null($drilling_general) ? [] : $drilling_general;

            foreach ($drilling_general as $index => $value) {
                $drilling_general_table = new d_general_data();
                $drilling_general_table->drilling_id = $drilling->id;
                $drilling_general_table->producing_interval_id = $request->array_select_interval_general_data[$index];
                $drilling_general_table->top = str_replace(",", ".", $value[1]);
                $drilling_general_table->bottom = str_replace(",", ".", $value[2]);
                $drilling_general_table->reservoir_pressure = str_replace(",", ".", $value[3]);
                $drilling_general_table->hole_diameter = str_replace(",", ".", $value[4]);
                $drilling_general_table->drill_pipe_diameter = str_replace(",", ".", $value[5]);
                $drilling_general_table->save();
            }

            if ($request->select_input_data == "1") {
                // Profile input data (different inputs planned in the future)
                $input_profile = json_decode($request->inputdata_profile_table);
                $input_profile = is_null($input_profile) ? [] : $input_profile;

                foreach ($input_profile as $value) {
                    $input_profile_table = new d_profile_input_data();
                    $input_profile_table->drilling_id = $drilling->id;
                    $input_profile_table->top = str_replace(",", ".", $value[0]);
                    $input_profile_table->bottom = str_replace(",", ".", $value[1]);
                    $input_profile_table->porosity = str_replace(",", ".", $value[2]);
                    $input_profile_table->permeability = str_replace(",", ".", $value[3]);
                    $input_profile_table->fracture_intensity = str_replace(",", ".", $value[4]);
                    $input_profile_table->irreducible_saturation = str_replace(",", ".", $value[5]);
                    $input_profile_table->save();
                }
            }

            return redirect(url('Drilling/result', $request->scenary_id));
        } else {
            return view('loginfirst');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store2(/*drilling_request*/Request $request)
    {
        dd($request);
        if (\Auth::check()) 
        {
            if(!isset($request->cementingAvailable) || empty($request->cementingAvailable || $request->cementingAvailable == null)) {
                $request->c_pump_rate_t = 0;
                $request->cementingAvailable = 0;
            } else {
                $request->cementingAvailable = 1;
            }

            //dd($request->cementingAvailable);
            $scenario = DB::table('escenarios')->where('id', $request->scenary_id)->first();

            if($request->input("accion")=="Run" || isset($_POST['only_s'])) {

                /* General data*/
                $drilling_general = json_decode(str_replace(",[null,null,null,null,null,null]","",$request->input("generaldata_table")));

                /* Cálculos automáticos */
                $hole_diameter_aux = is_null($drilling_general) ? 0 : $drilling_general[0][4];
                $drill_pipe_aux = is_null($drilling_general) ? 0 : $drilling_general[0][5];

                /* Nota: Variables que empiezan con d_ o c_ indican si son parte de la sección de drilling(_d) o cementación(_c) */
                $d_pump_rate = !empty($request->d_pump_rate_t) ? $request->d_pump_rate_t : 0;
                $c_pump_rate = !empty($request->c_pump_rate_t) ? $request->c_pump_rate_t : 0;

                $dividendo_d_pump = ($d_pump_rate*0.134);
                $dividendo_d_pump = $dividendo_d_pump == 0 ? 1 : $dividendo_d_pump; 

                $divisor = (0.0055*(($hole_diameter_aux*$hole_diameter_aux)-($drill_pipe_aux*$drill_pipe_aux)));
                $divisor = $divisor == 0 ? 1 : $divisor; 

                $d_anular_flow_velocity = $dividendo_d_pump / $divisor;
                $pre_dividendo = ($hole_diameter_aux-$drill_pipe_aux);
                $pre_dividendo = $pre_dividendo == 0 ? 1 : $pre_dividendo;

                $d_shear_rate = (2.4*$d_anular_flow_velocity)/$pre_dividendo;

                $dividendo_c_pump = ($c_pump_rate*0.134);
                $dividendo_c_pump = $dividendo_c_pump == 0 ? 1 : $dividendo_c_pump; 

                $c_anular_flow_velocity = $dividendo_c_pump/$divisor;
                $c_shear_rate = (2.4*$c_anular_flow_velocity)/$pre_dividendo;

                $drilling = new drilling();
                $drilling->scenario_id = $request->scenary_id;
                $drilling->cementingAvailable = $request->cementingAvailable;
                $drilling->d_total_exposure_time = $request->input("d_total_exposure_time_t");
                $drilling->d_pump_rate = $request->input("d_pump_rate_t");
                $drilling->d_mud_density = $request->input("d_mud_density_t");
                $drilling->d_anular_flow_velocity = $d_anular_flow_velocity;
                $drilling->d_shear_rate = $d_shear_rate;
                $drilling->d_rop = $request->input("d_rop_t");
                $drilling->d_equivalent_circulating_density = $request->input("d_equivalent_circulating_density_t"); 
                $drilling->c_total_exposure_time = $request->input("c_total_exposure_time_t");
                $drilling->c_pump_rate = $request->input("c_pump_rate_t");
                $drilling->c_cement_slurry = $request->input("c_cement_slurry_density_t");
                $drilling->c_shear_rate = $c_shear_rate;
                $drilling->c_anular_flow_velocity = $c_anular_flow_velocity;
                $drilling->c_equivalent_circulating_density = $request->input("c_equivalent_circulating_density_t"); 
                $drilling->general_interval_select = $request->input("select_interval_general_data");
                $drilling->filtration_function_id = $request->select_filtration_function ? $request->select_filtration_function : null;
                $drilling->input_data_select = $request->input("select_input_data");
                $drilling->a_factor = $request->input("a_factor_t");
                $drilling->b_factor = $request->input("b_factor_t");
                $drilling->status_wr = isset($_POST['only_s']);
                $drilling->save();

                $intervals_texpr = array();
                $intervals_op = array();
                $intervals_op_c = array();
                $intervals_data_all = array();

                #General data table
                $drilling_general = is_null($drilling_general) ? [] : $drilling_general;
                foreach ($drilling_general as $value) {
                    $row_intervals = array(); #Tiempo exposición por intervalo
                    $row_intervals_op = array(); #Overbalance Pressure por intervalo Drilling
                    $row_intervals_op_c = array(); #Overbalance Pressure por intervalo Cementation
                    $row_intervals_data = array(); #All intervals data

                    $drilling_general_table = new d_general_data();
                    $interval = DB::table('formacionxpozos')->select('id')->where('nombre','=',$value[0])->first();
                    $drilling_general_table->drilling_id = $drilling->id;
                    $drilling_general_table->producing_interval_id=$interval->id;
                    $drilling_general_table->top = str_replace(",", ".", $value[1]);
                    $drilling_general_table->bottom = str_replace(",", ".", $value[2]);
                    $drilling_general_table->reservoir_pressure = str_replace(",", ".", $value[3]);
                    $drilling_general_table->hole_diameter = str_replace(",", ".", $value[4]);
                    $drilling_general_table->drill_pipe_diameter = str_replace(",", ".", $value[5]);
                    $drilling_general_table->save();

                    #Llenado de los arrays con la data general para los cálculos posteriores. 

                    #Texp por intervalo
                    array_push($row_intervals,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals,$drilling_general_table->top);
                    array_push($row_intervals,$drilling_general_table->bottom);
                    array_push($intervals_texpr,$row_intervals);

                    #Overbalance Pressure por intervalo Drilling
                    array_push($row_intervals_op,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals_op,$drilling_general_table->bottom);
                    array_push($row_intervals_op,$drilling_general_table->reservoir_pressure);
                    array_push($row_intervals_op,$drilling_general_table->top);
                    array_push($intervals_op,$row_intervals_op);                

                    #Overbalance Pressure por intervalo Cementation
                    array_push($row_intervals_op_c,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals_op_c,$drilling_general_table->bottom);
                    array_push($row_intervals_op_c,$drilling_general_table->reservoir_pressure);
                    array_push($intervals_op_c,$row_intervals_op_c);

                    #All intervals data
                    array_push($row_intervals_data,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals_data,$drilling_general_table->top);
                    array_push($row_intervals_data,$drilling_general_table->bottom);
                    array_push($row_intervals_data,$drilling_general_table->reservoir_pressure);
                    array_push($row_intervals_data,$drilling_general_table->hole_diameter);
                    array_push($row_intervals_data,$drilling_general_table->drill_pipe_diameter);
                    array_push($intervals_data_all, $row_intervals_data);
                }

                $input_data_method = $request->input('inputDataMethodSelect');

                if($input_data_method == "1") {
                    #Profile input data
                    $data = $request->input("inputdata_profile_table");
                    $profile_data_input_all = array();
                    $input_profile = json_decode($data);

                    $input_profile = is_null($input_profile) ? [] : $input_profile;

                    foreach ($input_profile as $value) {

                        $input_profile_table = new d_profile_input_data();
                        $input_profile_table->drilling_id = $drilling->id;
                        $input_profile_table->depth = str_replace(",", ".", $value[0]);
                        $input_profile_table->porosity = str_replace(",", ".", $value[1]);
                        $input_profile_table->permeability = str_replace(",", ".", $value[2]);
                        $input_profile_table->fracture_intensity = str_replace(",", ".", $value[3]);
                        $input_profile_table->irreducible_saturation = str_replace(",", ".", $value[4]);
                        $input_profile_table->save();

                        $profile_data_input_row = array();
                        array_push($profile_data_input_row, $input_profile_table->id);
                        array_push($profile_data_input_row, $input_profile_table->depth);
                        array_push($profile_data_input_row, $input_profile_table->porosity);
                        array_push($profile_data_input_row, $input_profile_table->permeability);
                        array_push($profile_data_input_row, $input_profile_table->fracture_intensity);
                        array_push($profile_data_input_row, $input_profile_table->irreducible_saturation);
                        array_push($profile_data_input_all, $profile_data_input_row);

                    }

                } else if($input_data_method == "2" && !isset($_POST['only_s'])) {

                    #By intervals input data
                    $data = $request->input("inputdata_intervals_table");
                    $input_intervals = json_decode($data);
                    $intervals_data_input_all = array();
                    foreach ($input_intervals as $value) 
                    {
                        $intervals_data_input_row = array();

                        $input_intervals_table = new d_intervals_input_data();
                        $interval = DB::table('formacionxpozos')->select('id')->where('nombre','=',$value[0])->first();
                        $input_intervals_table->drilling_id = $drilling->id;
                        $input_intervals_table->producing_interval_id=$interval->id;
                        $input_intervals_table->porosity = str_replace(",", ".", $value[1]);
                        $input_intervals_table->permeability = str_replace(",", ".", $value[2]);
                        $input_intervals_table->fracture_intensity = str_replace(",", ".", $value[3]);
                        $input_intervals_table->irreducible_saturation= str_replace(",", ".", $value[4]);
                        $input_intervals_table->save();

                        array_push($intervals_data_input_row,$input_intervals_table->producing_interval_id);
                        array_push($intervals_data_input_row,$input_intervals_table->porosity);
                        array_push($intervals_data_input_row,$input_intervals_table->permeability);
                        array_push($intervals_data_input_row,$input_intervals_table->fracture_intensity);
                        array_push($intervals_data_input_row,$input_intervals_table->irreducible_saturation);
                        array_push($intervals_data_input_all,$intervals_data_input_row);
                    }
                }
                
                #Filtration test: asignación de información de funciones de filtrado por formación. 

                #Formaciones que son elegidas desde la primera pestaña y a las que se le va a definir una función de filtrado. 
                $formation_ids_ff = explode(",",$request->input('formations_select_gd'));

                #Id de la función de filtrado escogida para cada una de las formaciones. 
                $preset_function_values = json_decode($request->input('preset_function_values'));

                # $filtration_function_generalData = $this->assign_filtration_function_generalData($formation_ids_ff, $preset_function_values, $input_data_method);

                #Información función de filtrado para todos los intervalos (Ya que el caso es para uno o varios intervalos de UNA formación)
                $filtration_function = d_filtration_function::find($drilling->filtration_function_id);
                if ($filtration_function) {
                    $core_diameter = $filtration_function->core_diameter;
                    $kd_ki_drilling = $filtration_function->kdki_mud;
                    $kd_ki_cementation = $filtration_function->kdki_cement_slurry;
                    $a_factor = $filtration_function->a_factor;
                    $b_factor = $filtration_function->b_factor;
                } else { 
                    $core_diameter = 0;;
                    $kd_ki_drilling = 0;;
                    $kd_ki_cementation =0;;
                    $a_factor = 0;;
                    $b_factor = 0;;
                }

                /* $core_diameter_all = $filtration_function_generalData[0]; */
                /* $kd_ki_drilling = $filtration_function_generalData[1]; */
                /* $kd_ki_cementation = $filtration_function_generalData[2]; */
            }

            #Cálculos
            $MDtop = $request->input("MDtop"); #Top mayor
            $MDbottom = $request->input("MDbottom"); #Bottom menor
            $texp = floatval($drilling->d_total_exposure_time); #Texp de la interfaz
            $pmin = floatval($drilling->d_min_mud_density); #densidad mínima
            $pmax = floatval($drilling->d_max_mud_density); #densidad máxima
            $fhd = floatval($drilling->d_ecd); #Factor de corrección hidrostático / Equivalent circulating density (ECD) - Drilling
            $rop = floatval($drilling->d_rop); #ROP - drilling data
            $slurry_density = floatval($drilling->c_cement_slurry); #Densidad slurry cementación
            $fhd_c = floatval($drilling->c_equivalent_circulating_density); #Factor de corrección hidrostático / Equivalent circulating density (ECD) - Cementing
            $d_total_exposure_time = floatval($drilling->d_total_exposure_time); #Tiempo de exposición total Drilling
            $c_total_exposure_time = floatval($drilling->c_total_exposure_time); #Tiempo de exposición total Cementing

            #Opciones para input_data: 1- Profile; 2- Intervals
            if($input_data_method == "1" && !isset($_POST['only_s'])) {

                /* 4-Tiempo de exposición por rango (Perfil) */
                $texp_profile = $this->calculate_exposure_time($profile_data_input_all, null, $texp, null, $input_data_method);

                $drilling_ecd = $drilling->d_equivalent_circulating_density;
                $cementing_ecd = $drilling->c_equivalent_circulating_density;

                /* 5-Overbalance Pressure por perfil (Drilling) */
                $op_intervals = array();
                foreach ($intervals_op as $value) {
                    $row_op_profile = array();
                    #$op_value = (0.052*(($pmin+$pmax)/2)*$value[1])*$fhd-$value[2];
                    $op_value = 0.052 * $drilling_ecd * ($value[3] - $value[2]);
                    array_push($row_op_profile,$value[3]); #Top intervalo
                    array_push($row_op_profile,$value[1]); #Bottom intervalo
                    array_push($row_op_profile,$op_value); #PO por intervalo
                    array_push($op_intervals,$row_op_profile);
                }

                $op_profile = array();
                foreach ($profile_data_input_all as $depth) {
                    foreach ($op_intervals as $intervals_op_values) {
                        if($depth[1]>=$intervals_op_values[0] and $depth[1]<=$intervals_op_values[1]) {

                            array_push($op_profile, $intervals_op_values[2]);
                            break;

                        }
                    }    
                }

                #6-Overbalance Pressure por perfil (Cementation)
                $slurrydensity = $drilling->c_cement_slurry;
                $fhd_c = $drilling->c_ecd;
                $op_cementation_intervals = array();
                foreach ($intervals_op as $value) {

                    $row_op_cementation_profile = array();
                    #$op_value = (0.052*($slurrydensity)*$value[1])*$fhd_c-$value[2];
                    $op_value = $op_value = 0.052 * $cementing_ecd * ($value[3] - $value[2]);
                    array_push($row_op_cementation_profile,$value[3]); #Top intervalo
                    array_push($row_op_cementation_profile,$value[1]); #Bottom intervalo
                    array_push($row_op_cementation_profile,$op_value); #PO por intervalo
                    array_push($op_cementation_intervals,$row_op_cementation_profile);

                }

                $op_cementation_profile = array();
                foreach ($profile_data_input_all as $depth) {
                    foreach ($op_cementation_intervals as $intervals_op) {
                        if($depth[1]>=$intervals_op[0] and $depth[1]<=$intervals_op[1]) {

                            array_push($op_cementation_profile, $intervals_op[2]);
                            break;

                        }
                    }    
                }
                #dd($op_cementation_profile);

                #7- Flow Area (Afield) para perfil
                $flow_areas = array();
                $tops = array(); #Depth

                #Cálculo de flow areas por intervalos
                foreach ($intervals_data_all as $value) {

                    $flow_area_row = array();
                    $hole_diameter_value = floatval($value[4]);
                    $hole_diameter_value = ($hole_diameter_value*0.0833333)/2;
                    array_push($flow_area_row,$value[1]);
                    array_push($flow_area_row,$value[2]);
                    array_push($flow_area_row,$hole_diameter_value);
                    array_push($flow_areas,$flow_area_row);
                }

                #Asignación de Afield a profundidades a partir de intervalos
                $afield_profile = array();
                $i = 1;

                foreach ($profile_data_input_all as $depth) {
                    foreach ($flow_areas as $value) {
                        if($depth[1]>=$value[0] and $depth[1]<=$value[1]) {

                            $h_value = $profile_data_input_all[$i][1]-$profile_data_input_all[$i-1][1];
                            $flow_area_value = 2*pi()*$hole_diameter_value*$h_value;
                            array_push($afield_profile, $flow_area_value);

                            break;

                        }
                        $i++;
                    }    
                    array_push($tops,$depth[1]);
                }

                #8- Laboratory Area (Aflab) para perfil
                $lab_areas = array();

                #Cálculo de área de laboratorio por cada intervalo
                foreach ($intervals_data_all as $value) {
                    $row_lab_areas = array();
                    $aflab = (pi()*pow(($core_diameter/12), 2))/4;
                    array_push($row_lab_areas,$value[0]);
                    array_push($row_lab_areas,$aflab);
                    array_push($lab_areas,$row_lab_areas);
                }
                

                $aflabs_profile = array();

                foreach ($profile_data_input_all as $depth) {
                    $flag = 0;
                    foreach ($intervals_data_all as $value) {
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {

                            array_push($aflabs_profile,$lab_areas[$flag][1]);
                            break;

                        }
                        $flag++;
                    }    
                }

                #9- Cálculo volumen de filtrado (Drilling y Cementation)
                foreach ($profile_data_input_all as $depth) {
                    $flag = 0;
                    foreach ($intervals_data_all as $value) {
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {

                            array_push($aflabs_profile,$lab_areas[$flag][1]);
                            break;

                        }
                        $flag++;
                    }    
                }

                #Volumen de filtrado drilling y cementación por intervalos
                $filtered_volumes_drilling = array();
                $filtered_volumes_cementation = array();
                $total_filtered_volume = array();
                #dd($intervals_info_filtered_volumen);

                foreach ($profile_data_input_all as $depth) {
                    $c = 0;
                    foreach ($intervals_data_all as $value) {
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {

                            $a_factor_value = $a_factor;
                            $permeability_value = $depth[3]*(1+$depth[4]); #Permeability*Fracture intensity
                            $overbalance_pressure_value = $op_profile[$c];
                            $c_overbalance_pressure_value = $op_cementation_profile[$c];

                            $b_factor_value = $b_factor;
                            $d_total_exposure_time_value = floatval($drilling->d_total_exposure_time);
                            $c_total_exposure_time_value = floatval($drilling->c_total_exposure_time);
                            $aflab_value = $aflabs_profile[$c];
                            $affield_value = $afield_profile[$c];

                            if (($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value) >= 0) {
                                $filtered_volume_value_drilling =   ($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value)*sqrt($d_total_exposure_time_value*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_drilling = 0;
                            }

                            if (($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value) >= 0){
                                $filtered_volume_value_cementation = ($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value)*sqrt(($c_total_exposure_time_value/24)*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_cementation = 0;
                            }
                            
                            $total_filtered_volume_value = $filtered_volume_value_drilling + $filtered_volume_value_cementation;
                            break;

                        } else {

                            $filtered_volume_value_drilling = 0;
                            $filtered_volume_value_cementation = 0;
                            $total_filtered_volume_value = 0;

                        }

                        $c++;
                    }
                    array_push($filtered_volumes_drilling,$filtered_volume_value_drilling);
                    array_push($filtered_volumes_cementation,$filtered_volume_value_cementation);
                    array_push($total_filtered_volume,$total_filtered_volume_value);
                }

                #10 - Cálculo del radio de invasión por perfil (Drilling y Cementation)
                #Asignación de hole diameter y h a datos a profundidad a partir de intervalos
                $info_profile = array(); 
                foreach ($profile_data_input_all as $depth) {
                    foreach ($intervals_data_all as $value) {
                        $info_profile_row = array();
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {

                            $hole_diameter_value = floatval($value[4]);
                            $hole_diameter_value = $hole_diameter_value*0.0833333;
                            array_push($info_profile_row, $hole_diameter_value);
                            array_push($info_profile, $info_profile_row);
                            break;

                        } else {

                            array_push($info_profile_row, 0);
                            array_push($info_profile, 0);

                        }
                    }
                }

                $invasion_radius_drilling = array();
                $invasion_radius_cementation = array();
                $total_invasion_radius = array();
                $flag = 0;
                $i=1;

                $h_values = array();
                array_push($h_values, 1);
                for ($i=1; $i < count($profile_data_input_all) ; $i++) { 
                    array_push($h_values,$profile_data_input_all[$i][1]-$profile_data_input_all[$i-1][1]);
                }

                foreach ($profile_data_input_all as $depth) {

                    $hole_diameter_value = $info_profile[$flag][0];
                    $filtered_volume_drilling_value = $filtered_volumes_drilling[$flag];
                    $filtered_volume_cementation_value = $filtered_volumes_cementation[$flag];
                    $porosity_value = floatval($depth[2]);
                    $irreducible_saturation_value = floatval($depth[5]);
                    $invasion_radius_drilling_value =    sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_drilling_value   *5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_values[$flag]))));
                    $invasion_radius_cementation_value = sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_cementation_value*5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_values[$flag]))));
                    $total_invasion_radius_value = $invasion_radius_drilling_value + $invasion_radius_cementation_value;
                    array_push($invasion_radius_drilling,$invasion_radius_drilling_value);
                    array_push($invasion_radius_cementation,$invasion_radius_cementation_value);
                    array_push($total_invasion_radius, $total_invasion_radius_value);
                    $flag++;

                }

                #11 - Cálculo SDrilling y SCementation para cada intervalo

                $info_profile_skin = array(); 
                foreach ($profile_data_input_all as $depth) {
                    foreach ($intervals_data_all as $value) {
                        $info_profile_skin_row = array();
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {

                            $permeability = $value[3]*(1+$value[4]);
                            $hole_diameter_value = floatval($value[4]);
                            $kd_ki_cementation_value = floatval($kd_ki_cementation);
                            $kd_ki_drilling_value = floatval($kd_ki_drilling);

                            array_push($info_profile_skin_row, $permeability);
                            array_push($info_profile_skin_row, $hole_diameter_value);
                            array_push($info_profile_skin_row, $kd_ki_cementation_value);
                            array_push($info_profile_skin_row, $kd_ki_drilling_value);
                            array_push($info_profile_skin, $info_profile_skin_row);
                            break;

                        }
                    }
                }


                $flag = 0;
                $s_drilling = array();
                $s_cementation = array();
                $s_total = array();
                foreach ($profile_data_input_all as $depth) {

                    $invasion_radius_drilling_value_aux = $invasion_radius_drilling[$flag]; 
                    $invasion_radius_cementation_value_aux = $invasion_radius_cementation[$flag];
                    $permeability = $info_profile_skin[$flag][0];

                    $hole_diameter_value = $info_profile_skin[$flag][1];
                    $kd_ki_cementation_value = $info_profile_skin[$flag][2];
                    $kd_ki_drilling_value = $info_profile_skin[$flag][3];

                    $s_drilling_value =    ((($permeability/($kd_ki_drilling_value   *$permeability))-1)*log($invasion_radius_drilling_value_aux/($hole_diameter_value/2/12)));
                    $s_cementation_value = ((($permeability/($kd_ki_cementation_value*$permeability))-1)*log($invasion_radius_cementation_value_aux/($hole_diameter_value/2/12)));
                    $s_total_value = $s_drilling_value + $s_cementation_value;
                    array_push($s_drilling,$s_drilling_value);
                    array_push($s_cementation,$s_cementation_value);
                    array_push($s_total, $s_total_value);
                    $flag++;

                }

                #Results
                $results = $this->save_results($drilling->id, $drilling->scenario_id, $s_drilling, $invasion_radius_drilling, $s_cementation, $invasion_radius_cementation, $s_total, $total_filtered_volume, $total_invasion_radius, $tops);
                #Skin drilling

                $d_maximum_total_skin = max($s_drilling);
                $d_maximum_total_skin = number_format($d_maximum_total_skin,2,'.','');
                $d_average_total_skin = array_sum($s_drilling)/count($s_drilling);
                $d_average_total_skin = number_format($d_average_total_skin,2,'.','');

                #Invasion radius drilling
                
                $total_invasion_radius_drilling = array_sum($invasion_radius_drilling);
                $total_invasion_radius_drilling = number_format($total_invasion_radius_drilling,2,'.','');
                $maximum_invasion_radius_drilling = max($invasion_radius_drilling);
                $maximum_invasion_radius_drilling = number_format($maximum_invasion_radius_drilling,2,'.','');
                $average_invasion_radius_drilling = $total_invasion_radius_drilling/count($invasion_radius_drilling);
                $average_invasion_radius_drilling = number_format($average_invasion_radius_drilling,2,'.','');
                #Skin cementation
                
                $c_maximum_total_skin = max($s_cementation);
                $c_maximum_total_skin = number_format($c_maximum_total_skin,2,'.','');
                $c_average_total_skin = array_sum($s_cementation)/count($s_cementation);
                $c_average_total_skin = number_format($c_average_total_skin,2,'.','');

                #Invasion radius cementation
                
                $total_invasion_radius_cementation = array_sum($invasion_radius_cementation);
                $total_invasion_radius_cementation = number_format($total_invasion_radius_cementation,2,'.','');
                $maximum_invasion_radius_cementation = max($invasion_radius_cementation);
                $maximum_invasion_radius_cementation = number_format($maximum_invasion_radius_cementation,2,'.','');
                $average_invasion_radius_cementation = $total_invasion_radius_cementation/count($invasion_radius_cementation);
                $average_invasion_radius_cementation = number_format($average_invasion_radius_cementation,2,'.','');
                #Totals
                #Skin
                
                $maximum_total_skin = max($s_total);
                $maximum_total_skin = number_format($maximum_total_skin,2,'.','');
                $average_total_skin = array_sum($s_total)/count($s_total);
                $average_total_skin = number_format($average_total_skin,2,'.','');

                #Filtration volumes
                
                $maximum_total_filtration_volume = max($total_filtered_volume);
                $maximum_total_filtration_volume = number_format($maximum_total_filtration_volume,2,'.','');
                $average_total_filtration_volume = array_sum($total_filtered_volume)/count($total_filtered_volume);
                $average_total_filtration_volume = number_format($average_total_filtration_volume,2,'.','');

                #Invasion radius
                
                $maximum_total_invasion_radius = max($total_invasion_radius);
                $maximum_total_invasion_radius = number_format($maximum_total_invasion_radius,2,'.','');
                $average_total_invasion_radius = array_sum($total_invasion_radius)/count($total_invasion_radius);
                $average_total_invasion_radius = number_format($average_total_invasion_radius,2,'.','');
                #invasion radius
                #cementation -- invasion_radius_drilling
                #filtration  -- invasion_radius_cementation
                #dd($invasion_radius_drilling);

            } else if($input_data_method == "2" && !isset($_POST['only_s'])) {

                #4-Tiempo de exposición por intervalos 
                $texp_range = $this->calculate_exposure_time($intervals_texpr,$MDtop, $texp, $rop, $input_data_method);

                #5-Overbalance Pressure por intervalos (Drilling)
                $op_range = array();
                foreach ($intervals_op as $value) {
                    $row_op_range = array();
                    $op_value = (0.052*(($pmin+$pmax)/2)*$value[1])*$fhd-$value[2];
                    array_push($row_op_range,$value[0]);
                    array_push($row_op_range,$op_value);
                    array_push($op_range,$row_op_range);
                }

                #6-Overbalance Pressure por intervalos (Cementation)
                $slurrydensity = $drilling->c_cement_slurry;
                $fhd_c = $drilling->c_ecd;
                $op_c_range = array();
                foreach ($intervals_op_c as $value) {
                    $row_op_c_range = array();
                    $op_value = (0.052*($slurrydensity)*$value[1])*$fhd_c-$value[2];
                    array_push($row_op_c_range,$value[0]);
                    array_push($row_op_c_range,$op_value);
                    array_push($op_c_range,$row_op_c_range);
                }
                #dd($op_c_range);

                #7-Flow Area (Afield)
                $flow_areas = array();
                $tops = array();
                foreach ($intervals_data_all as $value) {
                    $flow_area_row = array();
                    $hole_diameter_value = floatval($value[4]);
                    $hole_diameter_value = ($hole_diameter_value*0.0833333)/2;
                    $h_value = $value[2]-$value[1];
                    array_push($tops,$value[1]);

                    $flow_area_value = 2*pi()*$hole_diameter_value*$h_value;
                    array_push($flow_area_row,$value[0]);
                    array_push($flow_area_row,$flow_area_value);
                    array_push($flow_areas,$flow_area_row);
                }
                #dd($flow_areas);

                #8-Laboratory Area (Aflab)
                $lab_areas = array();

                #Asignación de formaciones a intervalos
                $intervals_id = array();
                foreach ($intervals_data_all as $value) {
                    array_push($intervals_id,$value[0]);
                }

                #Asignación de core diameter a grupos de intervalos
                $intervals_by_formation = array();
                foreach ($core_diameter_all as $value) {
                    $row_intervals_by_formation = array();
                    $row_intervals_ids = array();
                    array_push($row_intervals_by_formation, $value[0]);
                    foreach ($value[1] as $value2) {
                        array_push($row_intervals_ids,$value2->id);
                    }
                    array_push($row_intervals_by_formation, $row_intervals_ids);
                    array_push($row_intervals_by_formation,floatval( $value[2]));
                    array_push($intervals_by_formation, $row_intervals_by_formation);

                }   
                
                #Asignación de core diameter a intervalo específico
                $core_diameter_by_intervals = array();
                foreach ($intervals_id as $value) {
                    $row_core_diameter_by_intervals = array();
                    foreach ($intervals_by_formation as $value2) {
                        if(in_array($value,$value2[1]))
                        {
                            array_push($row_core_diameter_by_intervals,$value);
                            array_push($row_core_diameter_by_intervals,$value2[2]);
                        }
                    }
                    array_push($core_diameter_by_intervals,$row_core_diameter_by_intervals);
                }

                #Cálculo de área de laboratorio por cada intervalo
                foreach ($core_diameter_by_intervals as $value) {
                    $row_lab_areas = array();
                    $aflab = (pi()*pow(($value[1]/12), 2))/4;
                    array_push($row_lab_areas,$value[0]);
                    array_push($row_lab_areas,$aflab);
                    array_push($lab_areas,$row_lab_areas);
                }
                #dd($lab_areas);

                #9-Cálculo Volumen de filtrado (Drilling y Cementación)
                $formation_function_info = array();
                $flag = 0; 
                foreach ([$formation_ids_ff] as $value) {
                    $formation_function_info_row = array();
                    array_push($formation_function_info_row,$value);
                    $intervals_by_formation_function = DB::table('formacionxpozos')->select('id')->where('formacion_id','=',$value)->get();
                    $intervals_by_formation_function_ids = array();

                    foreach ($intervals_by_formation_function as $value3) {
                        array_push($intervals_by_formation_function_ids,$value3->id);
                    }

                    $filtration_function = DB::table('d_filtration_function')->where('id',$preset_function_values[$flag])->get();
                    array_push($formation_function_info_row,$intervals_by_formation_function_ids);
                    array_push($formation_function_info_row,$filtration_function[0]->a_factor);
                    array_push($formation_function_info_row,$filtration_function[0]->b_factor);
                    array_push($formation_function_info,$formation_function_info_row);
                    $flag++;
                }

                #Intervalos por formación

                #Volumen de filtrado drilling y cementación por intervalos
                $filtered_volumes_drilling = array();
                $filtered_volumes_cementation = array();
                $total_filtered_volume = array();
                $c = 0;
                foreach ($intervals_data_input_all as $value) {
                    foreach ($formation_function_info as $value2) {
                        if(in_array($value[0], $value2[1])) {

                            $a_factor_value = $value2[2];
                            $permeability_value = $value[2]*(1+$value[3]); #Permeability*Fracture intensity
                            $overbalance_pressure_value = $op_range[$c][1];
                            $c_overbalance_pressure_value = $op_c_range[$c][1];
                            #dd($permeability_value, $overbalance_pressure_value);
                            $b_factor_value = $value2[3];
                            $d_total_exposure_time_value = floatval($drilling->d_total_exposure_time);
                            $c_total_exposure_time_value = floatval($drilling->c_total_exposure_time);
                            $aflab_value = $lab_areas[$c][1];
                            $affield_value = $flow_areas[$c][1];

                            if (($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value) >= 0){
                                $filtered_volume_value_drilling =   ($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value)*sqrt($d_total_exposure_time_value*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_drilling = 0;
                            }

                            if (($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value) >= 0) {
                                $filtered_volume_value_cementation = ($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value)*sqrt(($c_total_exposure_time_value/24)*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_cementation = 0;
                            }
                            
                            $total_filtered_volume_value = $filtered_volume_value_drilling + $filtered_volume_value_cementation;

                        }
                    }
                    array_push($filtered_volumes_drilling,$filtered_volume_value_drilling);
                    array_push($filtered_volumes_cementation,$filtered_volume_value_cementation);
                    array_push($total_filtered_volume,$total_filtered_volume_value);
                }
                
                # 10 - Cálculo de radio de invasión por intervalo (Drilling y Cementación)
                $invasion_radius_drilling = array();
                $invasion_radius_cementation = array();
                $total_invasion_radius = array();
                $flag = 0;
                foreach ($intervals_data_all as $value) {

                    $hole_diameter_value = floatval($value[4]);
                    $hole_diameter_value = $hole_diameter_value*0.0833333;
                    $h_value = floatval($value[2]-$value[1]);
                    $filtered_volume_drilling_value = $filtered_volumes_drilling[$flag];
                    $filtered_volume_cementation_value = $filtered_volumes_cementation[$flag];
                    $porosity_value = floatval($intervals_data_input_all[$flag][1]);
                    $irreducible_saturation_value = floatval($intervals_data_input_all[$flag][4]);

                    $invasion_radius_drilling_value =    sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_drilling_value   *5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_value))));
                    $invasion_radius_cementation_value = sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_cementation_value*5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_value))));
                    $total_invasion_radius_value = $invasion_radius_drilling_value + $invasion_radius_cementation_value;
                    array_push($invasion_radius_drilling,$invasion_radius_drilling_value);
                    array_push($invasion_radius_cementation,$invasion_radius_cementation_value);
                    array_push($total_invasion_radius, $total_invasion_radius_value);
                    $flag++;

                }

                #11 - Cálculo SDrilling y SCementation para cada intervalo

                #Asignación de kd/ki drilling a cada intervalo
                $intervals_by_formation = array();
                foreach ($kd_ki_drilling as $value) {
                    $row_intervals_by_formation = array();
                    $row_intervals_ids = array();
                    array_push($row_intervals_by_formation, $value[0]);
                    foreach ($value[1] as $value2) {
                        array_push($row_intervals_ids,$value2->id);
                    }
                    array_push($row_intervals_by_formation, $row_intervals_ids);
                    array_push($row_intervals_by_formation, $value[2]);
                    array_push($intervals_by_formation, $row_intervals_by_formation);
                }   

                #Asignación de kd/ki drilling a intervalo específico
                $kd_ki_drilling_by_intervals = array();
                foreach ($intervals_id as $value) {
                    $row_kd_ki_drilling_by_intervals = array();
                    foreach ($intervals_by_formation as $value2) {
                        if(in_array($value,$value2[1]))
                        {
                            array_push($row_kd_ki_drilling_by_intervals,$value);
                            array_push($row_kd_ki_drilling_by_intervals,$value2[2]);
                        }
                    }
                    array_push($kd_ki_drilling_by_intervals,$row_kd_ki_drilling_by_intervals);
                }

                #Asignación de kd/ki cementation a cada intervalo
                $intervals_by_formation = array();
                foreach ($kd_ki_cementation as $value) {
                    $row_intervals_by_formation = array();
                    $row_intervals_ids = array();
                    array_push($row_intervals_by_formation, $value[0]);
                    foreach ($value[1] as $value2) {
                        array_push($row_intervals_ids,$value2->id);
                    }
                    array_push($row_intervals_by_formation, $row_intervals_ids);
                    array_push($row_intervals_by_formation, $value[2]);
                    array_push($intervals_by_formation, $row_intervals_by_formation);
                }   
                
                #Asignación de kd/ki cementation a intervalo específico
                $kd_ki_cementation_by_intervals = array();
                foreach ($intervals_id as $value) {
                    $row_kd_ki_cementation_by_intervals = array();
                    foreach ($intervals_by_formation as $value2) {
                        if(in_array($value,$value2[1]))
                        {
                            array_push($row_kd_ki_cementation_by_intervals,$value);
                            array_push($row_kd_ki_cementation_by_intervals,$value2[2]);
                        }
                    }
                    array_push($kd_ki_cementation_by_intervals,$row_kd_ki_cementation_by_intervals);
                }

                $flag = 0;
                $s_drilling = array();
                $s_cementation = array();
                $s_total = array();
                foreach ($intervals_data_all as $value)
                {
                    $permeability = $intervals_data_input_all[$flag][2]*(1+$intervals_data_input_all[$flag][3]);
                    $invasion_radius_drilling_value_aux = $invasion_radius_drilling[$flag]; 
                    $invasion_radius_cementation_value_aux = $invasion_radius_cementation[$flag];
                    $hole_diameter = floatval($value[4]); 
                    $hole_diameter_value = $hole_diameter;
                    $kd_ki_cementation_value = floatval($kd_ki_cementation_by_intervals[$flag][1]);
                    $kd_ki_drilling_value = floatval($kd_ki_drilling_by_intervals[$flag][1]);

                    $s_drilling_value =    ((($permeability/($kd_ki_drilling_value   *$permeability))-1)*log($invasion_radius_drilling_value_aux/($hole_diameter_value/2/12)));
                    $s_cementation_value = ((($permeability/($kd_ki_cementation_value*$permeability))-1)*log($invasion_radius_cementation_value_aux/($hole_diameter_value/2/12)));
                    $s_total_value = $s_drilling_value + $s_cementation_value;
                    array_push($s_drilling,$s_drilling_value);
                    array_push($s_cementation,$s_cementation_value);
                    array_push($s_total, $s_total_value);
                    $flag++;
                }

                #Results
                $results = $this->save_results($drilling->id, $drilling->scenario_id, $s_drilling, $invasion_radius_drilling, $s_cementation, $invasion_radius_cementation, $s_total, $total_filtered_volume, $total_invasion_radius, $tops);
                #Skin drilling
                
                $d_maximum_total_skin = max($s_drilling);
                $d_maximum_total_skin = number_format($d_maximum_total_skin,2,'.','');
                $d_average_total_skin = array_sum($s_drilling)/count($s_drilling);
                $d_average_total_skin = number_format($d_average_total_skin,2,'.','');

                #Invasion radius drilling
                
                $total_invasion_radius_drilling = array_sum($invasion_radius_drilling);
                $total_invasion_radius_drilling = number_format($total_invasion_radius_drilling,2,'.','');
                $maximum_invasion_radius_drilling = max($invasion_radius_drilling);
                $maximum_invasion_radius_drilling = number_format($maximum_invasion_radius_drilling,2,'.','');
                $average_invasion_radius_drilling = $total_invasion_radius_drilling/count($invasion_radius_drilling);
                $average_invasion_radius_drilling = number_format($average_invasion_radius_drilling,2,'.','');
                #Skin cementation
                
                $c_maximum_total_skin = max($s_cementation);
                $c_maximum_total_skin = number_format($c_maximum_total_skin,2,'.','');
                $c_average_total_skin = array_sum($s_cementation)/count($s_cementation);
                $c_average_total_skin = number_format($c_average_total_skin,2,'.','');

                #Invasion radius cementation
                
                $total_invasion_radius_cementation = array_sum($invasion_radius_cementation);
                $total_invasion_radius_cementation = number_format($total_invasion_radius_cementation,2,'.','');
                $maximum_invasion_radius_cementation = max($invasion_radius_cementation);
                $maximum_invasion_radius_cementation = number_format($maximum_invasion_radius_cementation,2,'.','');
                $average_invasion_radius_cementation = $total_invasion_radius_cementation/count($invasion_radius_cementation);
                $average_invasion_radius_cementation = number_format($average_invasion_radius_cementation,2,'.','');
                #Totals
                #Skin
                
                $maximum_total_skin = max($s_total);
                $maximum_total_skin = number_format($maximum_total_skin,2,'.','');
                $average_total_skin = array_sum($s_total)/count($s_total);
                $average_total_skin = number_format($average_total_skin,2,'.','');

                #Filtration volumes
                
                $maximum_total_filtration_volume = max($total_filtered_volume);
                $maximum_total_filtration_volume = number_format($maximum_total_filtration_volume,2,'.','');
                $average_total_filtration_volume = array_sum($total_filtered_volume)/count($total_filtered_volume);
                $average_total_filtration_volume = number_format($average_total_filtration_volume,2,'.','');

                #Invasion radius
                
                $maximum_total_invasion_radius = max($total_invasion_radius);
                $maximum_total_invasion_radius = number_format($maximum_total_invasion_radius,2,'.','');
                $average_total_invasion_radius = array_sum($total_invasion_radius)/count($total_invasion_radius);
                $average_total_invasion_radius = number_format($average_total_invasion_radius,2,'.','');
                #invasion radius
                #cementation -- invasion_radius_drilling
                #filtration  -- invasion_radius_cementation
                #dd($invasion_radius_drilling);
            }

            $scenario_id = $drilling->scenario_id;
            $scenary = $drilling->escenario;
            //dd($drilling);
            $scenary->estado=1;
            $scenary->completo=1;
            $scenary->save();

            return redirect(url('Drilling/result',$scenario->id));
            // return View::make('drilling_results',compact('scenario','not_run','drilling','d_maximum_total_skin','d_average_total_skin','total_invasion_radius_drilling','invasion_radius_drilling','maximum_invasion_radius_drilling','average_invasion_radius_drilling','c_maximum_total_skin','c_average_total_skin','total_invasion_radius_cementation','invasion_radius_cementation','maximum_invasion_radius_cementation','average_invasion_radius_cementation','maximum_total_skin','average_total_skin','maximum_total_filtration_volume','average_total_filtration_volume','maximum_total_invasion_radius','average_total_invasion_radius','tops','scenario_id'));

        } else {
            return view('loginfirst');
        }
    }

    /** 
     * Asigna la información general de las funciones de filtrado (core diameter, kd_ki cementing y drilling) a nivel de formación. 
     *
     * @return Arreglo con la información de estos tres parámetros a nivel de formación. Incluye también un arreglo con los intervalos productores pertenecientes a cada una de las formaciones. 
     */
    public function assign_filtration_function_generalData($formation_ids, $filtration_function_ids, $input_data_method)
    {
        #Arrays para almacenar la información de core y kd_ki drilling y cementing. 
        $core_diameter_all = array();
        $kd_ki_drilling = array();
        $kd_ki_cementation = array();

        $flag = 0;
        foreach ($formation_ids as $value) 
        {
            #**Nota: Estas operaciones se hacen en el orden del arreglo de funciones. Tal cual aparecen en la interfaz. Para eso es $flag.  
            $row_core_diameter = array();
            $row_kd_ki_drilling = array();
            $row_kd_ki_cementation = array();

            #ids de todos los intervalos para cada una de las formaciones. 
            $intervals_x_formation = DB::table('formacionxpozos')->select('id')->where('formacion_id','=',$value)->get();
            $filtration_function = DB::table('d_filtration_function')->where('id', $filtration_function_ids[$flag])->get();

            array_push($row_core_diameter,$value);
            array_push($row_core_diameter,$intervals_x_formation);
            array_push($row_core_diameter,$filtration_function[0]->core_diameter);
            array_push($core_diameter_all,$row_core_diameter);

            array_push($row_kd_ki_drilling,$value);
            array_push($row_kd_ki_drilling,$intervals_x_formation);
            array_push($row_kd_ki_drilling,$filtration_function[0]->kdki_mud);
            array_push($kd_ki_drilling,$row_kd_ki_drilling);

            //datos cemetaring data
            array_push($row_kd_ki_cementation,$value);
            array_push($row_kd_ki_cementation,$intervals_x_formation);
            array_push($row_kd_ki_cementation,$filtration_function[0]->kdki_cement_slurry);
            array_push($kd_ki_cementation,$row_kd_ki_cementation);
            $flag++;
        }
        //dd($row_kd_ki_cementation);

        return array($core_diameter_all, $kd_ki_drilling, $kd_ki_cementation);
    }

    /** 
     * Calcula el tiempo de exposición por rango(formación, intervalo o perfil)
     *
     * @return Arreglo con los tiempos de exposición para cada uno de los valores del rango. 
     */
    public function calculate_exposure_time($range_data, $md_top, $texp, $d_rop, $input_data_method)
    {

        if($input_data_method == "1")
        {
            $texp_profile = array();
            array_push($texp_profile, $texp);
            for ($i=1; $i < count($range_data) ; $i++)
            { 
                $texpt_r = $texp_profile[$i-1] - (($range_data[$i][1]-$range_data[$i-1][1])/(-0.333 * 24));
                array_push($texp_profile,$texpt_r);
            }

            return $texp_profile; 
        }
        else if($input_data_method == "2")
        {
            $texp_range = array();
            foreach ($range_data as $value) 
            {
                $row_texp_range = array();
                $texpt_r = $texp - (($value[1]-$md_top)/($d_rop * 24));
                array_push($row_texp_range, $value[0]);
                array_push($row_texp_range, $texpt_r);
                array_push($texp_range,$row_texp_range);
            }

            return $texp_range; 
        }
    }

    /** 
     * Calcula la presión de overbalance drilling por rango. Recibe los datos del rango, la presión mínima y máxima, el ecd y el método de input. 
     *
     * @return Arreglo con las presiones de overbalance de cada rango. 
     */
    public function calculate_overbalance_pressure_drilling($range_data, $p_min, $p_max, $ecd_d, $input_method)
    {
        if($input_method == "1")
        {
            $op_range = array();
            foreach ($range_data as $value) 
            {
                $row_op_range = array();
                $op_value = (0.052*(($p_min+$p_max)/2)*$value[1])*$ecd_d-$value[2];
                array_push($row_op_range,$value[0]);
                array_push($row_op_range,$op_value);
                array_push($op_range,$row_op_range);
            }
            return $op_range;
        }
    }

    /** 
     * Calcula la presión de overbalance cementing por rango. Recibe los datos del rango, la densidad de cementación (slurry), el ecd y el método de input. 
     *
     * @return Arreglo con las presiones de overbalance de cada rango. 
     */
    public function calculate_overbalance_pressure_cementing($range_data, $slurry_density, $ecd_c, $input_data_method)
    {

        $op_c_range = array();
        foreach ($range_data as $value) 
        {
            $row_op_c_range = array();
            $op_value = (0.052*($slurry_density)*$value[1])*$ecd_c-$value[2];
            array_push($row_op_c_range,$value[0]);
            array_push($row_op_c_range,$op_value);
            array_push($op_c_range,$row_op_c_range);
        }
        return $op_c_range;
    }

    /** 
     * Calcula las áreas de flujo de cada elemento del rango.  
     *
     * @return Arreglo con las áreas de flujo de cada elemento del rango. 
     */
    public function calculate_flow_area($range_data)
    {
        $flow_areas = array();
        $tops = array();
        foreach ($range_data as $value) 
        {
            $flow_area_row = array();   
            $hole_diameter_value = (floatval($value[4])*0.0833333)/2;
            $h_value = $value[2]-$value[1];
            $flow_area_value = 2*pi()*$hole_diameter_value*$h_value;

            array_push($flow_area_row,$value[0]);
            array_push($flow_area_row,$flow_area_value);
            array_push($flow_areas,$flow_area_row);

            array_push($tops,$value[1]);
        }
        return array($flow_areas, $tops);
    }

    /** 
     * Calcula las áreas de laboratorio de cada elemento del rango. Recibe la información general del rango y la información del diámetro de núcleo.
     *
     * @return Arreglo con las áreas de laboratorio de cada elemento del rango. 
     */
    public function calculate_laboratory_areas($range_data, $core_diameter_data)
    {
        $lab_areas = array();

        #Asignación de formaciones a intervalos
        $intervals_id = array();
        foreach ($range_data as $value) 
        {
            array_push($intervals_id,$value[0]);
        }

        #Asignación de core diameter a grupos de intervalos
        $intervals_by_formation = array();
        foreach ($core_diameter_data as $value) 
        {
            $row_intervals_by_formation = array();
            $row_intervals_ids = array();
            array_push($row_intervals_by_formation, $value[0]);
            foreach ($value[1] as $value2) 
            {
                array_push($row_intervals_ids,$value2->id);
            }
            array_push($row_intervals_by_formation, $row_intervals_ids);
            array_push($row_intervals_by_formation,floatval( $value[2]));
            array_push($intervals_by_formation, $row_intervals_by_formation);

        }   
        
        #Asignación de core diameter a intervalo específico
        $core_diameter_by_intervals = array();
        foreach ($intervals_id as $value) {
            $row_core_diameter_by_intervals = array();
            foreach ($intervals_by_formation as $value2) 
            {
                if(in_array($value,$value2[1]))
                {
                    array_push($row_core_diameter_by_intervals,$value);
                    array_push($row_core_diameter_by_intervals,$value2[2]);
                }
                else #Revisar casos especiales.
                {
                    array_push($row_core_diameter_by_intervals,$value);
                    array_push($row_core_diameter_by_intervals,1);
                }
            }
            array_push($core_diameter_by_intervals,$row_core_diameter_by_intervals);
        }

        #Cálculo de área de laboratorio por cada intervalo
        foreach ($core_diameter_by_intervals as $value) 
        {
            $row_lab_areas = array();
            $aflab = (pi()*pow(($value[1]/12), 2))/4;
            array_push($row_lab_areas,$value[0]);
            array_push($row_lab_areas,$aflab);
            array_push($lab_areas,$row_lab_areas);
        }
        return array($lab_areas, $intervals_id); 
    }

    /** 
     * Calcula el volumen filtrado en fase de drilling y cementing. Recibe los ids de las formaciones involucradas, las funciones de filtrado escogidas, la información de la tabla input_data de la primera pestaña en drilling, las presiones overbalance de cada intervalo desde la interfaz, las presiones overbalance calculadas para drilling y cementing, el tiempo de exposición desde la interfaz para drilling y cementing, las áreas de flujo y de laboratorio calculadas en los campos anteriores.
     *
     * @return Arreglo con el volumen de filtrado de drilling y de cementing y el volumen total de filtrado.  
     */
    public function calculate_filtered_volume($formation_ids, $preset_function_values,$average_data_input_all, $intervals_overbalance_pressure, $overbalance_pressure_drilling, $overbalance_pressure_cementing, $total_exposure_time_drilling, $total_exposure_time_cementing, $flow_areas, $lab_areas)
    {
        #Id formaciones con función de filtrado
        $formation_function_info = array();
        $flag = 0;
        foreach ($formation_ids as $value) 
        {
            $formation_function_info_row = array();
            array_push($formation_function_info_row,$value);
            $intervals_by_formation_function = DB::table('formacionxpozos')->select('id')->where('formacion_id','=',$value)->get();
            $intervals_by_formation_function_ids = array();
            foreach ($intervals_by_formation_function as $value3) 
            {
                array_push($intervals_by_formation_function_ids,$value3->id);
            }

            $filtration_function = DB::table('d_filtration_function')->where('id',$preset_function_values[$flag])->get();
            array_push($formation_function_info_row,$intervals_by_formation_function_ids);
            array_push($formation_function_info_row,$filtration_function[0]->a_factor);
            array_push($formation_function_info_row,$filtration_function[0]->b_factor);
            array_push($formation_function_info,$formation_function_info_row);
            $flag++;
        }
        #Intervalos por formación
        $intervals_data_input_all = array();
        foreach ($intervals_overbalance_pressure as $value) 
        {
            $interval_query = DB::table('formacionxpozos')->where('id', '=', $value[0])->first();
            foreach ($average_data_input_all as $value2) 
            {
                $intervals_data_input_row = array();
                if($interval_query->formacion_id == $value2[0])
                {
                    array_push($intervals_data_input_row, $interval_query->id);
                    array_push($intervals_data_input_row, $value2[1]);
                    array_push($intervals_data_input_row, $value2[2]);
                    array_push($intervals_data_input_row, $value2[3]);
                    array_push($intervals_data_input_row, $value2[4]);
                    array_push($intervals_data_input_all, $intervals_data_input_row);
                    break;
                }
            }
        }

        #Volumen de filtrado drilling y cementación por intervalos
        $filtered_volumes_drilling = array();
        $filtered_volumes_cementation = array();
        $total_filtered_volume = array();
        $c = 0;
        foreach ($intervals_data_input_all as $value) 
        {
            foreach ($formation_function_info as $value2) 
            {
                if(in_array($value[0], $value2[1]))
                {
                    $a_factor_value = $value2[2];
                    $permeability_value = $value[2]*(1+$value[3]); #Permeability*Fracture intensity
                    $overbalance_pressure_value = $overbalance_pressure_drilling[$c][1];
                    $c_overbalance_pressure_value = $overbalance_pressure_cementing[$c][1];

                    $b_factor_value = $value2[3];
                    $d_total_exposure_time_value = floatval($total_exposure_time_drilling);
                    $c_total_exposure_time_value = floatval($total_exposure_time_cementing);
                    $aflab_value = $lab_areas[$c][1];
                    $affield_value = $flow_areas[$c][1];

                    if (($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value) >= 0) {
                        $filtered_volume_value_drilling = ($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value)*sqrt($d_total_exposure_time_value*1440)*0.0000063*($affield_value/$aflab_value);
                    } else {
                        $filtered_volume_value_drilling = 0;
                    }
                    
                    if (($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value) >= 0) {
                        $filtered_volume_value_cementation = ($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value)*sqrt(($c_total_exposure_time_value/24)*1440)*0.0000063*($affield_value/$aflab_value);
                    } else {
                        $filtered_volume_value_cementation = 0;
                    }
                    
                    $total_filtered_volume_value = $filtered_volume_value_drilling + $filtered_volume_value_cementation;
                }

            }
            array_push($filtered_volumes_drilling,$filtered_volume_value_drilling);
            array_push($filtered_volumes_cementation,$filtered_volume_value_cementation);
            array_push($total_filtered_volume,$total_filtered_volume_value);
            #dd($filtered_volumes_drilling,$filtered_volumes_cementation);
        }
        return(array($filtered_volumes_drilling, $filtered_volumes_cementation, $total_filtered_volume, $intervals_data_input_all));
    }

    /** 
     * Calcula el volumen filtrado en fase de drilling y cementing. Recibe los ids de las formaciones involucradas, las funciones de filtrado escogidas, la información de la tabla input_data de la primera pestaña en drilling, las presiones overbalance de cada intervalo desde la interfaz, las presiones overbalance calculadas para drilling y cementing, el tiempo de exposición desde la interfaz para drilling y cementing, las áreas de flujo y de laboratorio calculadas en los campos anteriores.
     *
     * @return Arreglo con el volumen de filtrado de drilling y de cementing y el volumen total de filtrado.  
     */
    public function calculate_invasion_radius($intervals_data, $filtered_volumes_drilling, $filtered_volumes_cementing, $intervals_data_input_all)
    {
        $invasion_radius_drilling = array();
        $invasion_radius_cementation = array();
        $total_invasion_radius = array();
        $flag = 0;
        foreach ($intervals_data as $value)
        {
            $hole_diameter_value = floatval($value[4])*0.0833333;
            $h_value = floatval($value[2]-$value[1]);

            $filtered_volume_drilling_value = $filtered_volumes_drilling[$flag];
            $filtered_volume_cementation_value = $filtered_volumes_cementing[$flag];
            $porosity_value = floatval($intervals_data_input_all[$flag][1]);
            $irreducible_saturation_value = floatval($intervals_data_input_all[$flag][4]);

            $invasion_radius_drilling_value =    sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_drilling_value   *5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_value))));
            $invasion_radius_cementation_value = sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_cementation_value*5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_value))));

            $total_invasion_radius_value = $invasion_radius_drilling_value + $invasion_radius_cementation_value;
            array_push($invasion_radius_drilling,$invasion_radius_drilling_value);
            array_push($invasion_radius_cementation,$invasion_radius_cementation_value);
            array_push($total_invasion_radius, $total_invasion_radius_value);
            $flag++;
        }
        return(array($invasion_radius_drilling, $invasion_radius_cementation, $total_invasion_radius));
    }

    /** 
     * Calcula el skin en drilling y cementing. Recibe al información de kd_ki para drilling y cementing, los id de los intervalos involucrados en el escenario, la información general de los intervalos que viene de las formaciones, la información de la tabla input_Data y los radios de invasión para drilling y cementación calculados en el paso anterior.
     *
     * @return Arreglo con el valor de skin para drilling, cementación y total. 
     */
    public function calculate_skin($kd_ki_drilling, $intervals_id, $kd_ki_cementation, $invertals_data, $intervals_data_input, $invasion_radius_drilling, $invasion_radius_cementation)
    {
        #Asignación de kd/ki drilling a cada intervalo
        $intervals_by_formation = array();
        foreach ($kd_ki_drilling as $value) 
        {
            $row_intervals_by_formation = array();
            $row_intervals_ids = array();
            array_push($row_intervals_by_formation, $value[0]);
            foreach ($value[1] as $value2) 
            {
                array_push($row_intervals_ids,$value2->id);
            }
            array_push($row_intervals_by_formation, $row_intervals_ids);
            array_push($row_intervals_by_formation, $value[2]);
            array_push($intervals_by_formation, $row_intervals_by_formation);
        }   

        #Asignación de kd/ki drilling a intervalo específico
        $kd_ki_drilling_by_intervals = array();
        foreach ($intervals_id as $value) 
        {
            $row_kd_ki_drilling_by_intervals = array();
            foreach ($intervals_by_formation as $value2) 
            {
                if(in_array($value,$value2[1]))
                {
                    array_push($row_kd_ki_drilling_by_intervals,$value);
                    array_push($row_kd_ki_drilling_by_intervals,$value2[2]);
                }
            }
            array_push($kd_ki_drilling_by_intervals,$row_kd_ki_drilling_by_intervals);
        }

        #Asignación de kd/ki cementation a cada intervalo
        $intervals_by_formation = array();
        foreach ($kd_ki_cementation as $value) 
        {
            $row_intervals_by_formation = array();
            $row_intervals_ids = array();
            array_push($row_intervals_by_formation, $value[0]);
            foreach ($value[1] as $value2) 
            {
                array_push($row_intervals_ids,$value2->id);
            }
            array_push($row_intervals_by_formation, $row_intervals_ids);
            array_push($row_intervals_by_formation, $value[2]);
            array_push($intervals_by_formation, $row_intervals_by_formation);
        }   

        #Asignación de kd/ki cementation a intervalo específico
        $kd_ki_cementation_by_intervals = array();
        foreach ($intervals_id as $value) {
            $row_kd_ki_cementation_by_intervals = array();
            foreach ($intervals_by_formation as $value2) 
            {
                if(in_array($value,$value2[1]))
                {
                    array_push($row_kd_ki_cementation_by_intervals,$value);
                    array_push($row_kd_ki_cementation_by_intervals,$value2[2]);
                }
            }
            array_push($kd_ki_cementation_by_intervals,$row_kd_ki_cementation_by_intervals);
        }

        $flag = 0;
        $s_drilling = array();
        $s_cementation = array();
        $s_total = array();
        foreach ($invertals_data as $value)
        {
            $permeability = $intervals_data_input[$flag][2]*(1+$intervals_data_input[$flag][3]);
            $invasion_radius_drilling_value_aux = $invasion_radius_drilling[$flag]; 
            $invasion_radius_cementation_value_aux = $invasion_radius_cementation[$flag];
            $hole_diameter = floatval($value[4]); 
            $hole_diameter_value = $hole_diameter;
            $kd_ki_cementation_value = floatval($kd_ki_cementation_by_intervals[$flag][1]);
            $kd_ki_drilling_value = floatval($kd_ki_drilling_by_intervals[$flag][1]);

            $s_drilling_value =    ((($permeability/($kd_ki_drilling_value   *$permeability))-1)*log($invasion_radius_drilling_value_aux/($hole_diameter_value/2/12)));
            $s_cementation_value = ((($permeability/($kd_ki_cementation_value*$permeability))-1)*log($invasion_radius_cementation_value_aux/($hole_diameter_value/2/12)));
            $s_total_value = $s_drilling_value + $s_cementation_value;
            array_push($s_drilling,$s_drilling_value);
            array_push($s_cementation,$s_cementation_value);
            array_push($s_total, $s_total_value);
            $flag++;
        }
        return (array($s_drilling, $s_cementation, $s_total));
    }

    /** 
     * Inserta los resultados a la base de datos y organiza los datos para reenviar a la vista de resultados.
     *
     * @return vista de resultados. 
     */
    public function save_results($drilling_id, $scenario_id, $s_drilling, $invasion_radius_drilling, $s_cementation, $invasion_radius_cementation, $s_total, $total_filtered_volume, $total_invasion_radius, $tops)
    {
        #Results

        #Skin drilling
        $d_maximum_total_skin = max($s_drilling);
        $d_maximum_total_skin = number_format($d_maximum_total_skin,2,'.','');
        $d_average_total_skin = array_sum($s_drilling)/count($s_drilling);
        $d_average_total_skin = number_format($d_average_total_skin,2,'.','');

        #Invasion radius drilling
        $total_invasion_radius_drilling = array_sum($invasion_radius_drilling);
        $total_invasion_radius_drilling = number_format($total_invasion_radius_drilling,2,'.','');
        $maximum_invasion_radius_drilling = max($invasion_radius_drilling);
        $maximum_invasion_radius_drilling = number_format($maximum_invasion_radius_drilling,2,'.','');
        $average_invasion_radius_drilling = $total_invasion_radius_drilling/count($invasion_radius_drilling);
        $average_invasion_radius_drilling = number_format($average_invasion_radius_drilling,2,'.','');

        #Skin cementation
        $c_maximum_total_skin = max($s_cementation);
        $c_maximum_total_skin = number_format($c_maximum_total_skin,2,'.','');
        $c_average_total_skin = array_sum($s_cementation)/count($s_cementation);//se cuenta
        $c_average_total_skin = number_format($c_average_total_skin,2,'.','');

        #Invasion radius cementation
        $total_invasion_radius_cementation = array_sum($invasion_radius_cementation);
        $total_invasion_radius_cementation = number_format($total_invasion_radius_cementation,2,'.','');
        $maximum_invasion_radius_cementation = max($invasion_radius_cementation);
        $maximum_invasion_radius_cementation = number_format($maximum_invasion_radius_cementation,2,'.','');
        $average_invasion_radius_cementation = $total_invasion_radius_cementation/count($invasion_radius_cementation);
        $average_invasion_radius_cementation = number_format($average_invasion_radius_cementation,2,'.','');

        #Totals
        #Skin
        $conteo = count($s_total);
        $maximum_total_skin = max($s_total);
        $maximum_total_skin = number_format($maximum_total_skin,2,'.','');
        
        $average_total_skin = array_sum($s_total)/count($s_total);
        $average_total_skin = number_format($average_total_skin,2,'.','');

        #Filtration volumes
        $maximum_total_filtration_volume = max($total_filtered_volume);
        $maximum_total_filtration_volume = number_format($maximum_total_filtration_volume,2,'.','');
        $average_total_filtration_volume = array_sum($total_filtered_volume)/count($total_filtered_volume);
        $average_total_filtration_volume = number_format($average_total_filtration_volume,2,'.','');

        #Invasion radius
        $maximum_total_invasion_radius = max($total_invasion_radius);
        $maximum_total_invasion_radius = number_format($maximum_total_invasion_radius,2,'.','');
        $average_total_invasion_radius = array_sum($total_invasion_radius)/count($total_invasion_radius);
        $average_total_invasion_radius = number_format($average_total_invasion_radius,2,'.','');

        #Borrando resultados antiguos 
        DB::table('drilling_results')->where('drilling_id','=',$drilling_id)->delete();
        DB::table('drilling_results_chart')->where('drilling_id','=',$drilling_id)->delete();

        #Guardando resultados
        $drilling_results_data = new drilling_results();
        $drilling_results_data->drilling_id = $drilling_id;
        $drilling_results_data->d_maximum_calculated_skin = $d_maximum_total_skin;
        $drilling_results_data->d_average_calculated_skin = $d_average_total_skin;
        $drilling_results_data->d_total_invasion_radius_volume = $total_invasion_radius_drilling;
        $drilling_results_data->d_maximum_invasion_radius = $maximum_invasion_radius_drilling;
        $drilling_results_data->d_average_invasion_radius = $average_invasion_radius_drilling;
        $drilling_results_data->c_maximum_calculated_skin = $c_maximum_total_skin;
        $drilling_results_data->c_average_calculated_skin = $c_average_total_skin;
        $drilling_results_data->c_total_invasion_radius_volume = $total_invasion_radius_cementation;
        $drilling_results_data->c_maximum_invasion_radius = $maximum_invasion_radius_cementation;
        $drilling_results_data->c_average_invasion_radius = $average_invasion_radius_cementation;
        $drilling_results_data->calculated_skin_max_total = $maximum_total_skin;
        $drilling_results_data->calculated_skin_avg_total = $average_total_skin;
        $drilling_results_data->filtration_volume_max_total = $maximum_total_filtration_volume;
        $drilling_results_data->filtration_volume_avg_total = $average_total_filtration_volume;
        $drilling_results_data->total_invasion_radius_max_total = $maximum_total_invasion_radius;
        $drilling_results_data->total_invasion_radius_avg_total = $average_total_invasion_radius;
        $drilling_results_data->save();
        
        for ($i=0; $i < count($tops); $i++) 
        { 
            $drilling_results_chart_data = new drilling_results_chart();
            $drilling_results_chart_data->drilling_id = $drilling_id;
            $drilling_results_chart_data->top = $tops[$i];
            $drilling_results_chart_data->d_invasion_radius = $invasion_radius_drilling[$i];
            $drilling_results_chart_data->c_invasion_radius = $invasion_radius_cementation[$i];
            $drilling_results_chart_data->save();
        }

        $scenario_id = $scenario_id;
        return array($d_maximum_total_skin,$d_average_total_skin,$total_invasion_radius_drilling,$invasion_radius_drilling,$maximum_invasion_radius_drilling,$average_invasion_radius_drilling,$c_maximum_total_skin,$c_average_total_skin,$total_invasion_radius_cementation,$invasion_radius_cementation,$maximum_invasion_radius_cementation,$average_invasion_radius_cementation,$maximum_total_skin,$average_total_skin,$maximum_total_filtration_volume,$average_total_filtration_volume,$maximum_total_invasion_radius,$average_total_invasion_radius,$tops,$scenario_id);
    }



    public function test()
    {
        return redirect()->action('DrillingController@edit');
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
        $_SESSION["scenary_id_dup"] = $id;
        return $this->edit($duplicateFrom);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id_escenario)
    {
        if (\Auth::check()) 
        {
            $scenario = escenario::find($id_escenario);
            $drilling_scenario = drilling::where('scenario_id', $scenario->id)->first();

            //Tablas
            $general_data = DB::table('d_general_data')->where('drilling_id', $drilling_scenario->id)->get();
            $general_data_table = [];
            foreach ($general_data as $value) 
            {
                $producing_interval_name = DB::table('formacionxpozos')->select('nombre')->where('id',$value->producing_interval_id)->first()->nombre;
                array_push($general_data_table, array($producing_interval_name, $value->top, $value->bottom, $value->reservoir_pressure, $value->hole_diameter, $value->drill_pipe_diameter));
            }

            $input_data_profile = DB::table('d_profile_input_data')->where('drilling_id','=',$drilling_scenario->id)->get();
            $input_data_profile_table = [];
            foreach ($input_data_profile as $value) 
            {
                array_push($input_data_profile_table, array($value->depth, $value->porosity, $value->permeability, $value->fracture_intensity, $value->irreducible_saturation));
            }

            $input_data_intervals = DB::table('d_intervals_input_data')->where('drilling_id','=',$drilling_scenario->id)->get();
            $input_data_intervals_table = [];
            foreach ($input_data_intervals as $value) 
            {
                $producing_interval_name = DB::table('formacionxpozos')->select('nombre')->where('id',$value->producing_interval_id)->first()->nombre;
                array_push($input_data_intervals_table, array($producing_interval_name, $value->porosity, $value->permeability, $value->fracture_intensity, $value->irreducible_saturation));
            }

            $well = DB::table('pozos')->where('id', $scenario->pozo_id)->first();
            $formation = DB::table('formacionxpozos')->where('id', $scenario->formacion_id)->first();
            $field = DB::table('campos')->where('id', $scenario->campo_id)->first();
            $scenario_id = \Request::get('scenaryId');
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenario->id)->first();
            $interval = DB::table('formacionxpozos')->where('id',$scenario->formacion_id)->first();
            $formations = DB::table('formaciones')->where('campo_id','=',$scenario->campo_id);
            $basin = DB::table('cuencas')->where('id', $scenario->cuenca_id)->first();

            return View::make('edit_drilling', compact(['user','formation','basin','well','field','drilling_scenario','general_data_table', 'input_data_profile_table', 'input_data_intervals_table','user','well', 'formation', 'field', 'interval','scenario','formations']));
        } else {
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
    public function update(/*drilling_request*/Request $request,$id_escenario)
    {
        if (\Auth::check()) 
        {
            if(!$request->cementingAvailable)
            {
                $request->c_pump_rate_t = 0;
            }else{
                $request->cementingAvailable = 1;
            }

            $scenario = escenario::find($id_escenario);
            if($request->input("accion")=="Run" || isset($_POST['only_s'])) {

                /* General data*/
                $drilling_general = json_decode(str_replace(",[null,null,null,null,null,null]","",$request->input("generaldata_table")));

                /* Cálculos automáticos */
                $hole_diameter_aux = is_null($drilling_general) ? 0 : $drilling_general[0][4];
                $drill_pipe_aux = is_null($drilling_general) ? 0 : $drilling_general[0][5];

                /* Nota: Variables que empiezan con d_ o c_ indican si son parte de la sección de drilling(_d) o cementación(_c) */
                $d_pump_rate = !empty($request->d_pump_rate_t) ? $request->d_pump_rate_t : 0;
                $c_pump_rate = !empty($request->c_pump_rate_t) ? $request->c_pump_rate_t : 0;

                $dividendo_d_pump = ($d_pump_rate*0.134);
                $dividendo_d_pump = $dividendo_d_pump == 0 ? 1 : $dividendo_d_pump; 

                $divisor = (0.0055*(($hole_diameter_aux*$hole_diameter_aux)-($drill_pipe_aux*$drill_pipe_aux)));
                $divisor = $divisor == 0 ? 1 : $divisor; 

                $d_anular_flow_velocity = $dividendo_d_pump / $divisor;
                $pre_dividendo = ($hole_diameter_aux-$drill_pipe_aux);
                $pre_dividendo = $pre_dividendo == 0 ? 1 : $pre_dividendo;

                $d_shear_rate = (2.4*$d_anular_flow_velocity)/$pre_dividendo;
                $dividendo_c_pump = ($c_pump_rate*0.134);
                $dividendo_c_pump = $dividendo_c_pump == 0 ? 1 : $dividendo_c_pump; 

                $c_anular_flow_velocity = $dividendo_c_pump/$divisor;
                $c_shear_rate = (2.4*$c_anular_flow_velocity)/$pre_dividendo;

                #Drilling general: datos a tabla drilling. Incluye todo el input excepto los datos tabulados.
                $drilling = drilling::where('scenario_id',$scenario->id)->first();
                if (!$drilling) {
                    $drilling = new drilling();
                }

                $drilling->scenario_id = $scenario->id;
                $drilling->cementingAvailable = $request->cementingAvailable;
                $drilling->d_total_exposure_time = $request->input("d_total_exposure_time_t");
                $drilling->d_pump_rate = $request->input("d_pump_rate_t");
                $drilling->d_mud_density = $request->input("d_mud_density_t");
                $drilling->d_anular_flow_velocity = $d_anular_flow_velocity;
                $drilling->d_shear_rate = $d_shear_rate;
                $drilling->d_rop = $request->input("d_rop_t");
                $drilling->d_equivalent_circulating_density = $request->input("d_equivalent_circulating_density_t"); 
                $drilling->c_total_exposure_time = $request->input("c_total_exposure_time_t");
                $drilling->c_pump_rate = $request->input("c_pump_rate_t");
                $drilling->c_cement_slurry = $request->input("c_cement_slurry_density_t");
                $drilling->c_shear_rate = $c_shear_rate;
                $drilling->c_anular_flow_velocity = $c_anular_flow_velocity;
                $drilling->c_equivalent_circulating_density = $request->input("c_equivalent_circulating_density_t"); 
                $drilling->general_interval_select = $request->input("select_interval_general_data");
                $drilling->input_data_select = $request->input("select_input_data");
                $drilling->filtration_function_id = $request->select_filtration_function ? $request->select_filtration_function : null;
                $drilling->a_factor = $request->input("a_factor_t");
                $drilling->b_factor = $request->input("b_factor_t");
                $drilling->status_wr = isset($_POST['only_s']);
                $drilling->save();

                $intervals_texpr = array();
                $intervals_op = array();
                $intervals_op_c = array();
                $intervals_data_all = array();

                #General data table

                #Borrando tabla vieja
                d_general_data::where('drilling_id',$drilling->id)->delete();
                $drilling_general = is_null($drilling_general) ? [] : $drilling_general;
                foreach ($drilling_general as $value) {

                    $row_intervals = array(); #Tiempo exposición por intervalo
                    $row_intervals_op = array(); #Overbalance Pressure por intervalo Drilling
                    $row_intervals_op_c = array(); #Overbalance Pressure por intervalo Cementation
                    $row_intervals_data = array(); #All intervals data

                    $drilling_general_table = new d_general_data();
                    $interval = DB::table('formacionxpozos')->select('id')->where('nombre','=',$value[0])->first();
                    $drilling_general_table->drilling_id = $drilling->id;
                    $drilling_general_table->producing_interval_id=$interval->id;
                    $drilling_general_table->top = str_replace(",", ".", $value[1]);
                    $drilling_general_table->bottom = str_replace(",", ".", $value[2]);
                    $drilling_general_table->reservoir_pressure = str_replace(",", ".", $value[3]);
                    $drilling_general_table->hole_diameter = str_replace(",", ".", $value[4]);
                    $drilling_general_table->drill_pipe_diameter = str_replace(",", ".", $value[5]);
                    $drilling_general_table->save();

                    #Llenado de los arrays con la data general para los cálculos posteriores. 

                    #Texp por intervalo
                    array_push($row_intervals,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals,$drilling_general_table->top);
                    array_push($row_intervals,$drilling_general_table->bottom);
                    array_push($intervals_texpr,$row_intervals);

                    #Overbalance Pressure por intervalo Drilling
                    array_push($row_intervals_op,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals_op,$drilling_general_table->bottom);
                    array_push($row_intervals_op,$drilling_general_table->reservoir_pressure);
                    array_push($row_intervals_op,$drilling_general_table->top);
                    array_push($intervals_op,$row_intervals_op);                

                    #Overbalance Pressure por intervalo Cementation
                    array_push($row_intervals_op_c,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals_op_c,$drilling_general_table->bottom);
                    array_push($row_intervals_op_c,$drilling_general_table->reservoir_pressure);
                    array_push($intervals_op_c,$row_intervals_op_c);

                    #All intervals data
                    array_push($row_intervals_data,$drilling_general_table->producing_interval_id);
                    array_push($row_intervals_data,$drilling_general_table->top);
                    array_push($row_intervals_data,$drilling_general_table->bottom);
                    array_push($row_intervals_data,$drilling_general_table->reservoir_pressure);
                    array_push($row_intervals_data,$drilling_general_table->hole_diameter);
                    array_push($row_intervals_data,$drilling_general_table->drill_pipe_diameter);
                    array_push($intervals_data_all, $row_intervals_data);
                }

                $input_data_method = $request->input('inputDataMethodSelect');
                if($input_data_method=="1") {

                    #Profile input data
                    $data = $request->input("inputdata_profile_table");
                    $profile_data_input_all = array();
                    $input_profile = json_decode($data);

                    #Borrando tabla anterior
                    d_profile_input_data::where('drilling_id',$drilling->id)->delete();
                    $input_profile = is_null($input_profile) ? [] : $input_profile;
                    foreach ($input_profile as $value) {
                        $input_profile_table = new d_profile_input_data();
                        $input_profile_table->drilling_id = $drilling->id;
                        $input_profile_table->depth = str_replace(",", ".", $value[0]);
                        $input_profile_table->porosity = str_replace(",", ".", $value[1]);
                        $input_profile_table->permeability = str_replace(",", ".", $value[2]);
                        $input_profile_table->fracture_intensity = str_replace(",", ".", $value[3]);
                        $input_profile_table->irreducible_saturation = str_replace(",", ".", $value[4]);
                        $input_profile_table->save();

                        $profile_data_input_row = array();
                        array_push($profile_data_input_row, $input_profile_table->id);
                        array_push($profile_data_input_row, $input_profile_table->depth);
                        array_push($profile_data_input_row, $input_profile_table->porosity);
                        array_push($profile_data_input_row, $input_profile_table->permeability);
                        array_push($profile_data_input_row, $input_profile_table->fracture_intensity);
                        array_push($profile_data_input_row, $input_profile_table->irreducible_saturation);
                        array_push($profile_data_input_all, $profile_data_input_row);

                    }

                } else if($input_data_method=="2") {

                    #By intervals input data
                    $data = $request->input("inputdata_intervals_table");
                    $input_intervals = json_decode($data);
                    $intervals_data_input_all = array();

                    #Borrando tabla anterior
                    d_intervals_input_data::where('drilling_id',$drilling->id)->delete();
                    $input_intervals = is_null($input_intervals) ? [] : $input_intervals;
                    foreach ($input_intervals as $value) {
                        $intervals_data_input_row = array();

                        $input_intervals_table = new d_intervals_input_data();
                        $interval = DB::table('formacionxpozos')->select('id')->where('nombre','=',$value[0])->first();
                        $input_intervals_table->drilling_id = $drilling->id;
                        $input_intervals_table->producing_interval_id=$interval->id;
                        $input_intervals_table->porosity = str_replace(",", ".", $value[1]);
                        $input_intervals_table->permeability = str_replace(",", ".", $value[2]);
                        $input_intervals_table->fracture_intensity = str_replace(",", ".", $value[3]);
                        $input_intervals_table->irreducible_saturation= str_replace(",", ".", $value[4]);
                        $input_intervals_table->save();

                        array_push($intervals_data_input_row,$input_intervals_table->producing_interval_id);
                        array_push($intervals_data_input_row,$input_intervals_table->porosity);
                        array_push($intervals_data_input_row,$input_intervals_table->permeability);
                        array_push($intervals_data_input_row,$input_intervals_table->fracture_intensity);
                        array_push($intervals_data_input_row,$input_intervals_table->irreducible_saturation);
                        array_push($intervals_data_input_all,$intervals_data_input_row);
                    }
                }

                #Filtration test: asignación de información de funciones de filtrado por formación. 

                #Información función de filtrado para todos los intervalos (Ya que el caso es para uno o varios intervalos de UNA formación)
                $filtration_function = d_filtration_function::find($drilling->filtration_function_id);
                if ($filtration_function) {
                    $core_diameter = $filtration_function->core_diameter;
                    $kd_ki_drilling = $filtration_function->kdki_mud;
                    $kd_ki_cementation = $filtration_function->kdki_cement_slurry;
                    $a_factor = $filtration_function->a_factor;
                    $b_factor = $filtration_function->b_factor;
                } else { 
                    $core_diameter = 0;;
                    $kd_ki_drilling = 0;;
                    $kd_ki_cementation =0;;
                    $a_factor = 0;;
                    $b_factor = 0;;
                }

                // #Formaciones que son elegidas desde la primera pestaña y a las que se le va a definir una función de filtrado. 
                // $formation_ids_ff = explode(",",$request->input('formations_select_gd'));

                // #Id de la función de filtrado escogida para cada una de las formaciones. 
                // $preset_function_values = json_decode($request->input('preset_function_values'));

                // $filtration_function_generalData = $this->assign_filtration_function_generalData($formation_ids_ff, $preset_function_values, $input_data_method);

                // $core_diameter_all = $filtration_function_generalData[0];
                // $kd_ki_drilling = $filtration_function_generalData[1];
                // $kd_ki_cementation = $filtration_function_generalData[2];
            }

            #Cálculos
            $MDtop = $request->input("MDtop"); #Top mayor
            $MDbottom = $request->input("MDbottom"); #Bottom menor
            $texp = floatval($drilling->d_total_exposure_time); #Texp de la interfaz
            $pmin = floatval($drilling->d_min_mud_density); #densidad mínima
            $pmax = floatval($drilling->d_max_mud_density); #densidad máxima
            $fhd = floatval($drilling->d_ecd); #Factor de corrección hidrostático / Equivalent circulating density (ECD) - Drilling
            $rop = floatval($drilling->d_rop); #ROP - drilling data
            $slurry_density = floatval($drilling->c_cement_slurry); #Densidad slurry cementación
            $fhd_c = floatval($drilling->c_equivalent_circulating_density); #Factor de corrección hidrostático / Equivalent circulating density (ECD) - Cementing
            $d_total_exposure_time = floatval($drilling->d_total_exposure_time); #Tiempo de exposición total Drilling
            $c_total_exposure_time = floatval($drilling->c_total_exposure_time); #Tiempo de exposición total Cementing

            #Opciones para input_data: 1- Formations; 2- Intervals; 3- Profile
            if($input_data_method=="1" && !isset($_POST['only_s'])) {

                #4-Tiempo de exposición por rango (Perfil)
                $texp_profile = $this->calculate_exposure_time($profile_data_input_all, null, $texp, null, $input_data_method);

                $drilling_ecd = $drilling->d_equivalent_circulating_density;
                $cementing_ecd = $drilling->c_equivalent_circulating_density;

                #5-Overbalance Pressure por perfil (Drilling)
                $op_intervals = array();
                foreach ($intervals_op as $value) {
                    $row_op_profile = array();
                    #$op_value = (0.052*(($pmin+$pmax)/2)*$value[1])*$fhd-$value[2];
                    $op_value = 0.052 * $drilling_ecd * ($value[3] - $value[2]);
                    array_push($row_op_profile,$value[3]); #Top intervalo
                    array_push($row_op_profile,$value[1]); #Bottom intervalo
                    array_push($row_op_profile,$op_value); #PO por intervalo
                    array_push($op_intervals,$row_op_profile);
                }

                $op_profile = array();
                foreach ($profile_data_input_all as $depth) {
                    foreach ($op_intervals as $intervals_op_values) {
                        if($depth[1]>=$intervals_op_values[0] and $depth[1]<=$intervals_op_values[1]) {
                            array_push($op_profile, $intervals_op_values[2]);
                            break;
                        }
                    }    
                }
                #dd($op_profile);

                #6-Overbalance Pressure por perfil (Cementation)
                $slurrydensity = $drilling->c_cement_slurry;
                $fhd_c = $drilling->c_ecd;
                $op_cementation_intervals = array();
                foreach ($intervals_op as $value) {
                    $row_op_cementation_profile = array();
                    $row_op_cementation_profile = array();
                    #$op_value = (0.052*($slurrydensity)*$value[1])*$fhd_c-$value[2];
                    $op_value = $op_value = 0.052 * $cementing_ecd * ($value[3] - $value[2]);
                    array_push($row_op_cementation_profile,$value[3]); #Top intervalo
                    array_push($row_op_cementation_profile,$value[1]); #Bottom intervalo
                    array_push($row_op_cementation_profile,$op_value); #PO por intervalo
                    array_push($op_cementation_intervals,$row_op_cementation_profile);
                }

                $op_cementation_profile = array();
                foreach ($profile_data_input_all as $depth) {
                    foreach ($op_cementation_intervals as $intervals_op) {
                        if($depth[1]>=$intervals_op[0] and $depth[1]<=$intervals_op[1]) {
                            array_push($op_cementation_profile, $intervals_op[2]);
                            break;
                        }
                    }    
                }
                #dd($op_cementation_profile);

                #7- Flow Area (Afield) para perfil
                $flow_areas = array();
                $tops = array(); #Depth

                #Cálculo de flow areas por intervalos
                foreach ($intervals_data_all as $value) {

                    $flow_area_row = array();
                    $hole_diameter_value = floatval($value[4]);
                    $hole_diameter_value = ($hole_diameter_value*0.0833333)/2;
                    array_push($flow_area_row,$value[1]);
                    array_push($flow_area_row,$value[2]);
                    array_push($flow_area_row,$hole_diameter_value);
                    array_push($flow_areas,$flow_area_row);
                }

                #Asignación de Afield a profundidades a partir de intervalos
                $afield_profile = array();
                $i = 1;
                
                foreach ($profile_data_input_all as $depth) {
                    foreach ($flow_areas as $value) {
                        if($depth[1]>=$value[0] and $depth[1]<=$value[1]) {

                            $h_value = $profile_data_input_all[$i][1]-$profile_data_input_all[$i-1][1];
                            $flow_area_value = 2*pi()*$hole_diameter_value*$h_value;
                            array_push($afield_profile, $flow_area_value);

                            break;
                        }
                        $i++;
                    }    
                    array_push($tops,$depth[1]);

                }


                #8- Laboratory Area (Aflab) para perfil
                $lab_areas = array();

                #Cálculo de área de laboratorio por cada intervalo
                foreach ($intervals_data_all as $value) {
                    $row_lab_areas = array();
                    $aflab = (pi()*pow(($core_diameter/12), 2))/4;
                    array_push($row_lab_areas,$value[0]);
                    array_push($row_lab_areas,$aflab);
                    array_push($lab_areas,$row_lab_areas);
                }
                

                $aflabs_profile = array();

                foreach ($profile_data_input_all as $depth) {
                    $flag = 0;
                    foreach ($intervals_data_all as $value) {
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {
                            array_push($aflabs_profile,$lab_areas[$flag][1]);
                            break;
                        }
                        $flag++;
                    }    
                }
                #dd($aflabs_profile);

                #9- Cálculo volumen de filtrado (Drilling y Cementation)
                foreach ($profile_data_input_all as $depth) {
                    $flag = 0;
                    foreach ($intervals_data_all as $value) {
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {
                            array_push($aflabs_profile,$lab_areas[$flag][1]);
                            break;
                        }
                        $flag++;
                    }    
                }

                #Volumen de filtrado drilling y cementación por intervalos
                $filtered_volumes_drilling = array();
                $filtered_volumes_cementation = array();
                $total_filtered_volume = array();
                #dd($intervals_info_filtered_volumen);


                foreach ($profile_data_input_all as $depth) {
                    $c = 0;
                    foreach ($intervals_data_all as $value) {
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {
                            $a_factor_value = $a_factor;
                            $permeability_value = $depth[3]*(1+$depth[4]); #Permeability*Fracture intensity
                            $overbalance_pressure_value = $op_profile[$c];
                            $c_overbalance_pressure_value = $op_cementation_profile[$c];
                            #dd($permeability_value, $overbalance_pressure_value);
                            $b_factor_value = $b_factor;
                            $d_total_exposure_time_value = floatval($drilling->d_total_exposure_time);
                            $c_total_exposure_time_value = floatval($drilling->c_total_exposure_time);
                            $aflab_value = $aflabs_profile[$c];
                            $affield_value = $afield_profile[$c];

                            if (($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value) >= 0) {
                                $filtered_volume_value_drilling = ($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value)*sqrt($d_total_exposure_time_value*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_drilling = 0;
                            }
                            
                            if (($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value) >= 0) {
                                $filtered_volume_value_cementation = ($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value)*sqrt(($c_total_exposure_time_value/24)*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_cementation = 0;
                            }
                            
                            $total_filtered_volume_value = $filtered_volume_value_drilling + $filtered_volume_value_cementation;
                            break;
                        } else {
                            $filtered_volume_value_drilling = 0;
                            $filtered_volume_value_cementation = 0;
                            $total_filtered_volume_value = 0;
                        }

                        $c++;
                    }
                    array_push($filtered_volumes_drilling,$filtered_volume_value_drilling);
                    array_push($filtered_volumes_cementation,$filtered_volume_value_cementation);
                    array_push($total_filtered_volume,$total_filtered_volume_value);

                }
                #dd($filtered_volumes_drilling,$filtered_volumes_cementation, $total_filtered_volume);


                #10 - Cálculo del radio de invasión por perfil (Drilling y Cementation)
                #Asignación de hole diameter y h a datos a profundidad a partir de intervalos
                $info_profile = array(); 
                foreach ($profile_data_input_all as $depth) {
                    foreach ($intervals_data_all as $value) {
                        $info_profile_row = array();
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {
                            $hole_diameter_value = floatval($value[4]);
                            $hole_diameter_value = $hole_diameter_value*0.0833333;
                            array_push($info_profile_row, $hole_diameter_value);
                            array_push($info_profile, $info_profile_row);
                            break;
                        } else {
                            array_push($info_profile_row, 0);
                            array_push($info_profile, 0);
                        }
                    }
                }

                $invasion_radius_drilling = array();
                $invasion_radius_cementation = array();
                $total_invasion_radius = array();
                $flag = 0;
                $i=1;

                $h_values = array();
                array_push($h_values, 1);
                for ($i=1; $i < count($profile_data_input_all) ; $i++) { 
                    array_push($h_values,$profile_data_input_all[$i][1]-$profile_data_input_all[$i-1][1]);
                }

                foreach ($profile_data_input_all as $depth) {
                    $hole_diameter_value = $info_profile[$flag][0];
                    $filtered_volume_drilling_value = $filtered_volumes_drilling[$flag];
                    $filtered_volume_cementation_value = $filtered_volumes_cementation[$flag];
                    $porosity_value = floatval($depth[2]);
                    $irreducible_saturation_value = floatval($depth[5]);
                    $invasion_radius_drilling_value =    sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_drilling_value   *5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_values[$flag]))));
                    $invasion_radius_cementation_value = sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_cementation_value*5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_values[$flag]))));
                    $total_invasion_radius_value = $invasion_radius_drilling_value + $invasion_radius_cementation_value;
                    array_push($invasion_radius_drilling,$invasion_radius_drilling_value);
                    array_push($invasion_radius_cementation,$invasion_radius_cementation_value);
                    array_push($total_invasion_radius, $total_invasion_radius_value);
                    $flag++;
                }           

                #dd($invasion_radius_drilling, $invasion_radius_cementation, $total_invasion_radius);
                #*/*/*/*/*/*/*/*/*/*/*/*/*/*/

                #11 - Cálculo SDrilling y SCementation para cada intervalo
                $info_profile_skin = array(); 
                foreach ($profile_data_input_all as $depth) {
                    $flag = 0;
                    foreach ($intervals_data_all as $value) {
                        $info_profile_skin_row = array();
                        if($depth[1]>=$value[1] and $depth[1]<=$value[2]) {

                            $permeability = $value[3]*(1+$value[4]);
                            $hole_diameter_value = floatval($value[4]);
                            $kd_ki_cementation_value = floatval($kd_ki_cementation);
                            $kd_ki_drilling_value = floatval($kd_ki_drilling);

                            array_push($info_profile_skin_row, $permeability);
                            array_push($info_profile_skin_row, $hole_diameter_value);
                            array_push($info_profile_skin_row, $kd_ki_cementation_value);
                            array_push($info_profile_skin_row, $kd_ki_drilling_value);
                            array_push($info_profile_skin, $info_profile_skin_row);
                            break;

                        }
                        $flag++;
                    }
                }


                $flag = 0;
                $s_drilling = array();
                $s_cementation = array();
                $s_total = array();
                foreach ($profile_data_input_all as $depth) {

                    $invasion_radius_drilling_value_aux = $invasion_radius_drilling[$flag]; 
                    $invasion_radius_cementation_value_aux = $invasion_radius_cementation[$flag];
                    $permeability = $info_profile_skin[$flag][0];

                    $hole_diameter_value = $info_profile_skin[$flag][1];
                    $kd_ki_cementation_value = $info_profile_skin[$flag][2];
                    $kd_ki_drilling_value = $info_profile_skin[$flag][3];

                    $s_drilling_value =    ((($permeability/($kd_ki_drilling_value   *$permeability))-1)*log($invasion_radius_drilling_value_aux/($hole_diameter_value/2/12)));
                    $s_cementation_value = ((($permeability/($kd_ki_cementation_value*$permeability))-1)*log($invasion_radius_cementation_value_aux/($hole_diameter_value/2/12)));
                    $s_total_value = $s_drilling_value + $s_cementation_value;
                    array_push($s_drilling,$s_drilling_value);
                    array_push($s_cementation,$s_cementation_value);
                    array_push($s_total, $s_total_value);
                    $flag++;
                }

                #Results
                $results = $this->save_results($drilling->id, $drilling->scenario_id, $s_drilling, $invasion_radius_drilling, $s_cementation, $invasion_radius_cementation, $s_total, $total_filtered_volume, $total_invasion_radius, $tops);
                #Skin drilling
                
                $d_maximum_total_skin = max($s_drilling);
                $d_maximum_total_skin = number_format($d_maximum_total_skin,2,'.','');
                $d_average_total_skin = array_sum($s_drilling)/count($s_drilling);
                $d_average_total_skin = number_format($d_average_total_skin,2,'.','');

                #Invasion radius drilling
                
                $total_invasion_radius_drilling = array_sum($invasion_radius_drilling);
                $total_invasion_radius_drilling = number_format($total_invasion_radius_drilling,2,'.','');
                $maximum_invasion_radius_drilling = max($invasion_radius_drilling);
                $maximum_invasion_radius_drilling = number_format($maximum_invasion_radius_drilling,2,'.','');
                $average_invasion_radius_drilling = $total_invasion_radius_drilling/count($invasion_radius_drilling);
                $average_invasion_radius_drilling = number_format($average_invasion_radius_drilling,2,'.','');
                #Skin cementation
                
                $c_maximum_total_skin = max($s_cementation);
                $c_maximum_total_skin = number_format($c_maximum_total_skin,2,'.','');
                $c_average_total_skin = array_sum($s_cementation)/count($s_cementation);
                $c_average_total_skin = number_format($c_average_total_skin,2,'.','');

                #Invasion radius cementation
                
                $total_invasion_radius_cementation = array_sum($invasion_radius_cementation);
                $total_invasion_radius_cementation = number_format($total_invasion_radius_cementation,2,'.','');
                $maximum_invasion_radius_cementation = max($invasion_radius_cementation);
                $maximum_invasion_radius_cementation = number_format($maximum_invasion_radius_cementation,2,'.','');
                $average_invasion_radius_cementation = $total_invasion_radius_cementation/count($invasion_radius_cementation);
                $average_invasion_radius_cementation = number_format($average_invasion_radius_cementation,2,'.','');
                #Totals
                #Skin
                
                $maximum_total_skin = max($s_total);
                $maximum_total_skin = number_format($maximum_total_skin,2,'.','');
                $average_total_skin = array_sum($s_total)/count($s_total);
                $average_total_skin = number_format($average_total_skin,2,'.','');

                #Filtration volumes
                
                $maximum_total_filtration_volume = max($total_filtered_volume);
                $maximum_total_filtration_volume = number_format($maximum_total_filtration_volume,2,'.','');
                $average_total_filtration_volume = array_sum($total_filtered_volume)/count($total_filtered_volume);
                $average_total_filtration_volume = number_format($average_total_filtration_volume,2,'.','');

                #Invasion radius
                
                $maximum_total_invasion_radius = max($total_invasion_radius);
                $maximum_total_invasion_radius = number_format($maximum_total_invasion_radius,2,'.','');
                $average_total_invasion_radius = array_sum($total_invasion_radius)/count($total_invasion_radius);
                $average_total_invasion_radius = number_format($average_total_invasion_radius,2,'.','');
                #invasion radius
                #cementation -- invasion_radius_drilling
                #filtration  -- invasion_radius_cementation
                #dd($invasion_radius_drilling);

            } else if($input_data_method=="2" && !isset($_POST['only_s'])) {

                #4-Tiempo de exposición por intervalos 
                $texp_range = $this->calculate_exposure_time($intervals_texpr,$MDtop, $texp, $rop, $input_data_method);

                #5-Overbalance Pressure por intervalos (Drilling)
                $op_range = array();
                foreach ($intervals_op as $value) {
                    $row_op_range = array();
                    $op_value = (0.052*(($pmin+$pmax)/2)*$value[1])*$fhd-$value[2];
                    array_push($row_op_range,$value[0]);
                    array_push($row_op_range,$op_value);
                    array_push($op_range,$row_op_range);
                }

                #6-Overbalance Pressure por intervalos (Cementation)
                $slurrydensity = $drilling->c_cement_slurry;
                $fhd_c = $drilling->c_ecd;
                $op_c_range = array();
                foreach ($intervals_op_c as $value) {
                    $row_op_c_range = array();
                    $op_value = (0.052*($slurrydensity)*$value[1])*$fhd_c-$value[2];
                    array_push($row_op_c_range,$value[0]);
                    array_push($row_op_c_range,$op_value);
                    array_push($op_c_range,$row_op_c_range);
                }
                #dd($op_c_range);

                #7-Flow Area (Afield)
                $flow_areas = array();
                $tops = array();
                foreach ($intervals_data_all as $value) {
                    $flow_area_row = array();
                    $hole_diameter_value = floatval($value[4]);
                    $hole_diameter_value = ($hole_diameter_value*0.0833333)/2;
                    $h_value = $value[2]-$value[1];
                    array_push($tops,$value[1]);

                    $flow_area_value = 2*pi()*$hole_diameter_value*$h_value;
                    array_push($flow_area_row,$value[0]);
                    array_push($flow_area_row,$flow_area_value);
                    array_push($flow_areas,$flow_area_row);
                }
                #dd($flow_areas);

                #8-Laboratory Area (Aflab)
                $lab_areas = array();

                #Asignación de formaciones a intervalos
                $intervals_id = array();
                foreach ($intervals_data_all as $value) {
                    array_push($intervals_id,$value[0]);
                }

                #Asignación de core diameter a grupos de intervalos
                $intervals_by_formation = array();
                foreach ($core_diameter_all as $value) {
                    $row_intervals_by_formation = array();
                    $row_intervals_ids = array();
                    array_push($row_intervals_by_formation, $value[0]);
                    foreach ($value[1] as $value2) {
                        array_push($row_intervals_ids,$value2->id);
                    }
                    array_push($row_intervals_by_formation, $row_intervals_ids);
                    array_push($row_intervals_by_formation,floatval( $value[2]));
                    array_push($intervals_by_formation, $row_intervals_by_formation);

                }   
                
                #Asignación de core diameter a intervalo específico
                $core_diameter_by_intervals = array();
                foreach ($intervals_id as $value) {
                    $row_core_diameter_by_intervals = array();
                    foreach ($intervals_by_formation as $value2) {
                        if(in_array($value,$value2[1])) {
                            array_push($row_core_diameter_by_intervals,$value);
                            array_push($row_core_diameter_by_intervals,$value2[2]);
                        }
                    }
                    array_push($core_diameter_by_intervals,$row_core_diameter_by_intervals);
                }

                #Cálculo de área de laboratorio por cada intervalo
                foreach ($core_diameter_by_intervals as $value) {
                    $row_lab_areas = array();
                    $aflab = (pi()*pow(($value[1]/12), 2))/4;
                    array_push($row_lab_areas,$value[0]);
                    array_push($row_lab_areas,$aflab);
                    array_push($lab_areas,$row_lab_areas);
                }
                #dd($lab_areas);

                #9-Cálculo Volumen de filtrado (Drilling y Cementación)
                $formation_function_info = array();
                $flag = 0; 
                foreach ([$formation_ids_ff] as $value) {
                    $formation_function_info_row = array();
                    array_push($formation_function_info_row,$value);
                    $intervals_by_formation_function = DB::table('formacionxpozos')->select('id')->where('formacion_id','=',$value)->get();
                    $intervals_by_formation_function_ids = array();
                    foreach ($intervals_by_formation_function as $value3) {
                        array_push($intervals_by_formation_function_ids,$value3->id);
                    }

                    $filtration_function = DB::table('d_filtration_function')->where('id',$preset_function_values[$flag])->get();
                    array_push($formation_function_info_row,$intervals_by_formation_function_ids);
                    array_push($formation_function_info_row,$filtration_function[0]->a_factor);
                    array_push($formation_function_info_row,$filtration_function[0]->b_factor);
                    array_push($formation_function_info,$formation_function_info_row);
                    $flag++;
                }

                #Intervalos por formación

                #Volumen de filtrado drilling y cementación por intervalos
                $filtered_volumes_drilling = array();
                $filtered_volumes_cementation = array();
                $total_filtered_volume = array();
                $c = 0;
                foreach ($intervals_data_input_all as $value) {
                    foreach ($formation_function_info as $value2) {
                        if(in_array($value[0], $value2[1])) {

                            $a_factor_value = $value2[2];
                            $permeability_value = $value[2]*(1+$value[3]); #Permeability*Fracture intensity
                            $overbalance_pressure_value = $op_range[$c][1];
                            $c_overbalance_pressure_value = $op_c_range[$c][1];
                            #dd($permeability_value, $overbalance_pressure_value);
                            $b_factor_value = $value2[3];
                            $d_total_exposure_time_value = floatval($drilling->d_total_exposure_time);
                            $c_total_exposure_time_value = floatval($drilling->c_total_exposure_time);
                            $aflab_value = $lab_areas[$c][1];
                            $affield_value = $flow_areas[$c][1];

                            if (($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value) >= 0) {
                                $filtered_volume_value_drilling = ($a_factor_value*$permeability_value*$overbalance_pressure_value+$b_factor_value)*sqrt($d_total_exposure_time_value*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_drilling = 0;
                            }

                            if (($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value) >= 0) {
                                $filtered_volume_value_cementation = ($a_factor_value*$permeability_value*$c_overbalance_pressure_value+$b_factor_value)*sqrt(($c_total_exposure_time_value/24)*1440)*0.0000063*($affield_value/$aflab_value);
                            } else {
                                $filtered_volume_value_cementation = 0;
                            }
                            
                            $total_filtered_volume_value = $filtered_volume_value_drilling + $filtered_volume_value_cementation;
                        }

                    }
                    array_push($filtered_volumes_drilling,$filtered_volume_value_drilling);
                    array_push($filtered_volumes_cementation,$filtered_volume_value_cementation);
                    array_push($total_filtered_volume,$total_filtered_volume_value);
                }
                
                # 10 - Cálculo de radio de invasión por intervalo (Drilling y Cementación)
                $invasion_radius_drilling = array();
                $invasion_radius_cementation = array();
                $total_invasion_radius = array();
                $flag = 0;
                foreach ($intervals_data_all as $value) {

                    $hole_diameter_value = floatval($value[4]);
                    $hole_diameter_value = $hole_diameter_value*0.0833333;
                    $h_value = floatval($value[2]-$value[1]);
                    $filtered_volume_drilling_value = $filtered_volumes_drilling[$flag];
                    $filtered_volume_cementation_value = $filtered_volumes_cementation[$flag];
                    $porosity_value = floatval($intervals_data_input_all[$flag][1]);
                    $irreducible_saturation_value = floatval($intervals_data_input_all[$flag][4]);

                    $invasion_radius_drilling_value =    sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_drilling_value   *5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_value))));
                    $invasion_radius_cementation_value = sqrt(abs(pow(($hole_diameter_value/(2/12)),2)+(($filtered_volume_cementation_value*5.615)/(pi()*$porosity_value*(1-$irreducible_saturation_value)*$h_value))));
                    $total_invasion_radius_value = $invasion_radius_drilling_value + $invasion_radius_cementation_value;
                    array_push($invasion_radius_drilling,$invasion_radius_drilling_value);
                    array_push($invasion_radius_cementation,$invasion_radius_cementation_value);
                    array_push($total_invasion_radius, $total_invasion_radius_value);
                    $flag++;
                }

                #11 - Cálculo SDrilling y SCementation para cada intervalo

                #Asignación de kd/ki drilling a cada intervalo
                $intervals_by_formation = array();
                foreach ($kd_ki_drilling as $value) {
                    $row_intervals_by_formation = array();
                    $row_intervals_ids = array();
                    array_push($row_intervals_by_formation, $value[0]);
                    foreach ($value[1] as $value2) {
                        array_push($row_intervals_ids,$value2->id);
                    }
                    array_push($row_intervals_by_formation, $row_intervals_ids);
                    array_push($row_intervals_by_formation, $value[2]);
                    array_push($intervals_by_formation, $row_intervals_by_formation);
                }   

                #Asignación de kd/ki drilling a intervalo específico
                $kd_ki_drilling_by_intervals = array();
                foreach ($intervals_id as $value) {
                    $row_kd_ki_drilling_by_intervals = array();
                    foreach ($intervals_by_formation as $value2) {
                        if(in_array($value,$value2[1])) {
                            array_push($row_kd_ki_drilling_by_intervals,$value);
                            array_push($row_kd_ki_drilling_by_intervals,$value2[2]);
                        }
                    }
                    array_push($kd_ki_drilling_by_intervals,$row_kd_ki_drilling_by_intervals);
                }

                #Asignación de kd/ki cementation a cada intervalo
                $intervals_by_formation = array();
                foreach ($kd_ki_cementation as $value) {
                    $row_intervals_by_formation = array();
                    $row_intervals_ids = array();
                    array_push($row_intervals_by_formation, $value[0]);
                    foreach ($value[1] as $value2) {
                        array_push($row_intervals_ids,$value2->id);
                    }
                    array_push($row_intervals_by_formation, $row_intervals_ids);
                    array_push($row_intervals_by_formation, $value[2]);
                    array_push($intervals_by_formation, $row_intervals_by_formation);
                }   
                
                #Asignación de kd/ki cementation a intervalo específico
                $kd_ki_cementation_by_intervals = array();
                foreach ($intervals_id as $value) {
                    $row_kd_ki_cementation_by_intervals = array();
                    foreach ($intervals_by_formation as $value2) {
                        if(in_array($value,$value2[1]))
                        {
                            array_push($row_kd_ki_cementation_by_intervals,$value);
                            array_push($row_kd_ki_cementation_by_intervals,$value2[2]);
                        }
                    }
                    array_push($kd_ki_cementation_by_intervals,$row_kd_ki_cementation_by_intervals);
                }

                $flag = 0;
                $s_drilling = array();
                $s_cementation = array();
                $s_total = array();
                foreach ($intervals_data_all as $value) {
                    $permeability = $intervals_data_input_all[$flag][2]*(1+$intervals_data_input_all[$flag][3]);
                    $invasion_radius_drilling_value_aux = $invasion_radius_drilling[$flag]; 
                    $invasion_radius_cementation_value_aux = $invasion_radius_cementation[$flag];
                    $hole_diameter = floatval($value[4]); 
                    $hole_diameter_value = $hole_diameter;
                    $kd_ki_cementation_value = floatval($kd_ki_cementation_by_intervals[$flag][1]);
                    $kd_ki_drilling_value = floatval($kd_ki_drilling_by_intervals[$flag][1]);

                    $s_drilling_value =    ((($permeability/($kd_ki_drilling_value   *$permeability))-1)*log($invasion_radius_drilling_value_aux/($hole_diameter_value/2/12)));
                    $s_cementation_value = ((($permeability/($kd_ki_cementation_value*$permeability))-1)*log($invasion_radius_cementation_value_aux/($hole_diameter_value/2/12)));
                    $s_total_value = $s_drilling_value + $s_cementation_value;
                    array_push($s_drilling,$s_drilling_value);
                    array_push($s_cementation,$s_cementation_value);
                    array_push($s_total, $s_total_value);
                    $flag++;
                }

                #Results
                $results = $this->save_results($drilling->id, $drilling->scenario_id, $s_drilling, $invasion_radius_drilling, $s_cementation, $invasion_radius_cementation, $s_total, $total_filtered_volume, $total_invasion_radius, $tops);
                #Skin drilling
                
                $d_maximum_total_skin = max($s_drilling);
                $d_maximum_total_skin = number_format($d_maximum_total_skin,2,'.','');
                $d_average_total_skin = array_sum($s_drilling)/count($s_drilling);
                $d_average_total_skin = number_format($d_average_total_skin,2,'.','');

                #Invasion radius drilling
                
                $total_invasion_radius_drilling = array_sum($invasion_radius_drilling);
                $total_invasion_radius_drilling = number_format($total_invasion_radius_drilling,2,'.','');
                $maximum_invasion_radius_drilling = max($invasion_radius_drilling);
                $maximum_invasion_radius_drilling = number_format($maximum_invasion_radius_drilling,2,'.','');
                $average_invasion_radius_drilling = $total_invasion_radius_drilling/count($invasion_radius_drilling);
                $average_invasion_radius_drilling = number_format($average_invasion_radius_drilling,2,'.','');
                #Skin cementation
                
                $c_maximum_total_skin = max($s_cementation);
                $c_maximum_total_skin = number_format($c_maximum_total_skin,2,'.','');
                $c_average_total_skin = array_sum($s_cementation)/count($s_cementation);
                $c_average_total_skin = number_format($c_average_total_skin,2,'.','');

                #Invasion radius cementation
                
                $total_invasion_radius_cementation = array_sum($invasion_radius_cementation);
                $total_invasion_radius_cementation = number_format($total_invasion_radius_cementation,2,'.','');
                $maximum_invasion_radius_cementation = max($invasion_radius_cementation);
                $maximum_invasion_radius_cementation = number_format($maximum_invasion_radius_cementation,2,'.','');
                $average_invasion_radius_cementation = $total_invasion_radius_cementation/count($invasion_radius_cementation);
                $average_invasion_radius_cementation = number_format($average_invasion_radius_cementation,2,'.','');
                #Totals
                #Skin
                
                $maximum_total_skin = max($s_total);
                $maximum_total_skin = number_format($maximum_total_skin,2,'.','');
                $average_total_skin = array_sum($s_total)/count($s_total);
                $average_total_skin = number_format($average_total_skin,2,'.','');

                #Filtration volumes
                
                $maximum_total_filtration_volume = max($total_filtered_volume);
                $maximum_total_filtration_volume = number_format($maximum_total_filtration_volume,2,'.','');
                $average_total_filtration_volume = array_sum($total_filtered_volume)/count($total_filtered_volume);
                $average_total_filtration_volume = number_format($average_total_filtration_volume,2,'.','');

                #Invasion radius
                
                $maximum_total_invasion_radius = max($total_invasion_radius);
                $maximum_total_invasion_radius = number_format($maximum_total_invasion_radius,2,'.','');
                $average_total_invasion_radius = array_sum($total_invasion_radius)/count($total_invasion_radius);
                $average_total_invasion_radius = number_format($average_total_invasion_radius,2,'.','');
                #invasion radius
                #cementation -- invasion_radius_drilling
                #filtration  -- invasion_radius_cementation
                #dd($invasion_radius_drilling);
            }

            $scenario_id = $drilling->scenario_id;
            $scenary = escenario::find($scenario_id);
            $scenary->estado=1;
            $scenary->completo=1;
            $scenary->save();

            return redirect(url('Drilling/result',$scenario->id));
            // return View::make('drilling_results',compact('scenario','drilling','d_maximum_total_skin','d_average_total_skin','total_invasion_radius_drilling','invasion_radius_drilling','maximum_invasion_radius_drilling','average_invasion_radius_drilling','c_maximum_total_skin','c_average_total_skin','total_invasion_radius_cementation','invasion_radius_cementation','maximum_invasion_radius_cementation','average_invasion_radius_cementation','maximum_total_skin','average_total_skin','maximum_total_filtration_volume','average_total_filtration_volume','maximum_total_invasion_radius','average_total_invasion_radius','tops','scenario_id'));
        }
        else
        {
            return view('loginfirst');
        }
    }

    /**
     * Makes the calculations for a drilling scenary and displays the results in the view.
     *
     * @param  int  $id
     * @return View
     */
    public function result($id)
    {   
        if (\Auth::check()) 
        {
            $scenario = escenario::find($id);
            $drilling = DB::table('drilling')->where('scenario_id', $id)->first();

            if (!$drilling->status_wr) {
                $drilling_general = DB::table('d_general_data')->where('drilling_id', $drilling->id)->first();
                $rows_profile_data = DB::table('d_profile_input_data')->where('drilling_id', $drilling->id)->get();

                // Calculations phase
                // Get filtration function data
                $filtration_function_data = DB::table('d_filtration_function')->where('id', $drilling->filtration_function_id)->first();
                
                // Get Hole Diameter from general data table;
                $hole_diameter = floatval($drilling_general->hole_diameter);

                // 1) Calculate perforation overbalance
                // Get media point for bottom and top in the profile table
                $media_point_profile_bottom = floatval($drilling_general->bottom);
                $media_point_profile_top = floatval($drilling_general->top);;

                // foreach ($rows_profile_data as $row) {
                //     $media_point_profile_bottom += floatval($row->bottom);
                //     $media_point_profile_top += floatval($row->top);
                // }

                // $media_point_profile_bottom /= count($rows_profile_data);
                // $media_point_profile_top /= count($rows_profile_data);

                $TVD = ($media_point_profile_bottom + $media_point_profile_top) / 2;

                // This is retrieving Reservoir Pressure from the first row of the general data table
                // This needs to be changed
                $RP = floatval($drilling_general->reservoir_pressure);

                $ob_perf = 0.052 * (floatval($drilling->d_equivalent_circulating_density) * $TVD) - $RP;

                // 2) Calculate completion/cementing overbalance
                $ob_cem = 0.052 * (floatval($drilling->c_equivalent_circulating_density) * $TVD) - $RP;

                // 3) Enter cycle to make calculations for each row in the profile table
                $t_exp_perf = array();
                $vf_perf = array();
                $rd_perf = array();
                $vf_cem = array();
                $rd_cem = array();
                $a_factor = floatval($drilling->a_factor);
                $b_factor = floatval($drilling->b_factor);

                foreach ($rows_profile_data as $row) {
                    $bottom = floatval($row->bottom);
                    $top = floatval($row->top);
                    $permeability = floatval($row->permeability);
                    $fracture_intensity = floatval($row->fracture_intensity);
                    $porosity = floatval($row->porosity);
                    $irreducible_saturation = floatval($row->irreducible_saturation);

                    // 3.1) Calculate k corrected
                    $k_corrected = $permeability * (1 + $fracture_intensity);

                    // 3.2) Calculate drilling exposure time
                    $t_exp_calc = floatval($drilling->d_total_exposure_time) - (($bottom - $top) / floatval($drilling->d_rop)) * 0.041666667;
                    array_push($t_exp_perf, $t_exp_calc);

                    // 3.3) Calculate drilling filtrate volume
                    // Calculate af_field and af_lab
                    $af_field = 2 * pi() * ($hole_diameter / (2 / 12)) * ($bottom - $top);
                    $af_lab = 2 * pi() * pow(floatval($filtration_function_data->core_diameter) / 2, 2);

                    $vf_perf_calc = $a_factor * (($k_corrected * $ob_perf) + $b_factor) * sqrt($t_exp_calc * 1440) * 0.0000063 * ($af_field / $af_lab);
                    array_push($vf_perf, $vf_perf_calc);

                    // 3.4) Calculate drilling invasion radius
                    $rd_perf_calc = sqrt(pow($hole_diameter / (2 / 12), 2) + ($vf_perf_calc * 5.615) / (pi() * $porosity * ($bottom - $top) * (1 - $irreducible_saturation)));
                    array_push($rd_perf, $rd_perf_calc);

                    // Do calculations for completion/cementation
                    if ($drilling->cementingAvailable == 1) {
                        // 3.5) Calculate cementing exposure time
                        $vf_cem_calc = $a_factor * (($k_corrected * $ob_cem) + $b_factor) * sqrt(floatval($drilling->c_total_exposure_time) * 1440) * 0.0000063 * ($af_field / $af_lab);
                        array_push($vf_cem, $vf_cem_calc);

                        // 3.6) Calculate cementing invasion radius
                        $rd_cem_calc = sqrt(pow($hole_diameter / (2 / 12), 2) + ($vf_cem_calc * 5.615) / (pi() * $porosity * ($bottom - $top) * (1 - $irreducible_saturation)));
                        array_push($rd_cem, $rd_cem_calc);
                    }
                }

                // 4) Calculate drilling average invasion radius
                $rd_perf_avg = array_sum($rd_perf);
                $rd_perf_avg /= count($rd_perf);

                // 5) Calculate drilling max invasion radius
                $rd_perf_max = max($rd_perf);

                // 6) Calculate drilling average skin
                $skin_perf_avg = (1 / floatval($filtration_function_data->kdki_mud)) * log($rd_perf_avg / ($hole_diameter / (2 / 12)));
            
                // 7) Calculate drilling max skin
                $skin_perf_max = (1 / floatval($filtration_function_data->kdki_mud)) * log($rd_perf_max / ($hole_diameter / (2 / 12)));

                if ($drilling->cementingAvailable == 1) {
                    // 8) Calculate cementing average invasion radius
                    $rd_cem_avg = array_sum($rd_cem);
                    $rd_cem_avg /= count($rd_cem);

                    // 9) Calculate cementing max invasion radius
                    $rd_cem_max = max($rd_cem);

                    // 10) Calculate cementing average skin
                    $skin_cem_avg = (1 / floatval($filtration_function_data->kdki_cement_slurry)) * log($rd_cem_avg / ($hole_diameter / (2 / 12)));
                
                    // 11) Calculate cementing max skin
                    $skin_cem_max = (1 / floatval($filtration_function_data->kdki_cement_slurry)) * log($rd_cem_max / ($hole_diameter / (2 / 12)));
                }

                // 12) Calculate drilling max filtrate volume
                $vf_perf_total = array_sum($vf_perf);

                if ($drilling->cementingAvailable == 1) {
                    // 13) Calculate cementing max filtrate volume
                    $vf_cem_total = array_sum($vf_cem);
                }

                // Calculations are done, now on to showing the results
                $graph_results_perf = array();
                $graph_results_perf_x = array();
                $graph_results_perf_y = array();
                $graph_results_cem = array();
                $graph_results_cem_x = array();
                $graph_results_cem_y = array();

                foreach ($rows_profile_data as $index => $row) {
                    $bottom = floatval($row->bottom);
                    $top = floatval($row->top);

                    array_push($graph_results_perf_x, $rd_perf[$index]);
                    array_push($graph_results_perf_y, ($bottom + $top) / 2);

                    if ($drilling->cementingAvailable == 1) {
                        array_push($graph_results_cem_x, $rd_cem[$index]);
                        array_push($graph_results_cem_y, ($bottom + $top) / 2);
                    }
                }
                
                array_push($graph_results_perf, array($graph_results_perf_x, $graph_results_perf_y));
                array_push($graph_results_cem, array($graph_results_cem_x, $graph_results_cem_y));

                $table_results = array(
                    array($skin_perf_avg, $skin_perf_max, $rd_perf_avg, $rd_perf_max, $vf_perf_total)
                );

                if ($drilling->cementingAvailable == 1) {
                    array_push($table_results, array($skin_cem_avg, $skin_cem_max, $rd_cem_avg, $rd_cem_max, $vf_cem_total));
                    array_push($table_results, array($skin_perf_avg + $skin_cem_avg, $skin_perf_max + $skin_cem_max, 0, 0, $vf_perf_total + $vf_cem_total));
                }

                return View::make('drilling_results', compact('scenario', 'drilling', 'graph_results_perf', 'graph_results_cem', 'table_results'));
            } else {
                return View::make('drilling_results', compact('scenario', 'drilling'));
            }
        } else {
            return view('loginfirst');
        }
    }

    #Despliega los resultados a partir de la BD
    public function result2($id)
    {   
        if (\Auth::check()) 
        {
            $scenario = escenario::find($id);
            $drilling = DB::table('drilling')->where('scenario_id','=',$id)->first();
            $results_data = drilling_results::where('drilling_id', '=',$drilling->id)->first();
            $chart_data = drilling_results_chart::where('drilling_id','=',$drilling->id)->get();

            if (!$results_data) {
                $not_run = true;
                $d_maximum_total_skin = 0;
                $d_average_total_skin = 0;
                $total_invasion_radius_drilling = 0;
                $maximum_invasion_radius_drilling = 0;
                $average_invasion_radius_drilling = 0;
                $c_maximum_total_skin = 0;
                $c_average_total_skin = 0;
                $total_invasion_radius_cementation = 0;
                $maximum_invasion_radius_cementation = 0;
                $average_invasion_radius_cementation = 0;
                $maximum_total_skin = 0;
                $average_total_skin = 0;
                $maximum_total_filtration_volume = 0;
                $average_total_filtration_volume = 0;
                $maximum_total_invasion_radius = 0;
                $average_total_invasion_radius = 0;
            } else {
                $not_run = false;
                $d_maximum_total_skin = $results_data->d_maximum_calculated_skin;
                $d_average_total_skin = $results_data->d_average_calculated_skin;
                $total_invasion_radius_drilling = $results_data->d_total_invasion_radius_volume;
                $maximum_invasion_radius_drilling = $results_data->d_maximum_invasion_radius;
                $average_invasion_radius_drilling = $results_data->d_average_invasion_radius;
                $c_maximum_total_skin = $results_data->c_maximum_calculated_skin;
                $c_average_total_skin = $results_data->c_average_calculated_skin;
                $total_invasion_radius_cementation = $results_data->c_total_invasion_radius_volume;
                $maximum_invasion_radius_cementation = $results_data->c_maximum_invasion_radius;
                $average_invasion_radius_cementation = $results_data->c_average_invasion_radius;
                $maximum_total_skin = $results_data->calculated_skin_max_total;
                $average_total_skin = $results_data->calculated_skin_avg_total;
                $maximum_total_filtration_volume = $results_data->filtration_volume_max_total;
                $average_total_filtration_volume = $results_data->filtration_volume_avg_total;
                $maximum_total_invasion_radius = $results_data->total_invasion_radius_max_total;
                $average_total_invasion_radius = $results_data->total_invasion_radius_avg_total;
            }

            $invasion_radius_drilling = [];
            $invasion_radius_cementation = [];
            $tops = [];
            foreach ($chart_data as $value) {
                array_push($invasion_radius_drilling,$value->d_invasion_radius);
                array_push($invasion_radius_cementation,$value->c_invasion_radius);
                array_push($tops,$value->top);
            }

            $scenario_id = $drilling->scenario_id;

            return View::make('drilling_results',compact('scenario','not_run','drilling','d_maximum_total_skin','d_average_total_skin','total_invasion_radius_drilling','invasion_radius_drilling','maximum_invasion_radius_drilling','average_invasion_radius_drilling','c_maximum_total_skin','c_average_total_skin','total_invasion_radius_cementation','invasion_radius_cementation','maximum_invasion_radius_cementation','average_invasion_radius_cementation','maximum_total_skin','average_total_skin','maximum_total_filtration_volume','average_total_filtration_volume','maximum_total_invasion_radius','average_total_invasion_radius','tops','scenario_id'));
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
}
