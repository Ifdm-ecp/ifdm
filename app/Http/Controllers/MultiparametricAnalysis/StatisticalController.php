<?php

namespace App\Http\Controllers\MultiparametricAnalysis;

use App\cuenca;
use App\escenario;
use App\formacionxpozo;
use App\formacion;
use App\medicion;
use App\Http\Controllers\Controller;
use App\Http\Requests\MultiparametricStatisticalCreateRequest;
use App\Http\Requests\MultiparametricStatisticalRequest;
use App\Models\MultiparametricAnalysis\Statistical;
use App\subparameters_weight;
use App\Traits\StatisticalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use DB;

class StatisticalController extends Controller
{
    use StatisticalTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(MultiparametricTrait::demo());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {
            $scenary = escenario::find(\Request::get('scenaryId'));
            $user = $scenary->user;
            $advisor = $scenary->enable_advisor;
            $cuencas = cuenca::orderBy('nombre')->get();
            $complete = false;

            return view('multiparametricAnalysis.statistical.create', compact(['scenary', 'user', 'advisor', 'cuencas', 'complete']));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MultiparametricStatisticalCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MultiparametricStatisticalCreateRequest $request)
    {
        $input = $request->all();

        /* se modifica el array del campo field_statistical con implode */
        if ($request->field_statistical) {
            $input['field_statistical'] = implode(",", $request->field_statistical);
        }

        /* se pasa la variable calculate al funcion edit */
        Session::flash('calculate', $request->calculate);

        /* se ingresa los datos de la tabla statistical */
        $statistical = Statistical::create($input);

        /* se guarda el parametro en la tabla subparameters_weight */
        subparameters_weight::create(['multiparametric_id' => $statistical->id]);

        //se redirecciona a la vista edit de statistical
        // return view('multiparametricAnalysis.statistical.edit', compact(['statistical']));
        return redirect()->route('statistical.edit', $statistical->escenario_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $statistical = Statistical::find($id);
        if ($statistical == null) {
            $statistical = Statistical::where('escenario_id', $id)->first();
        }

        $escenario_id = $statistical->escenario_id;
        $formations = escenario::where('id',$escenario_id)->first();
        $formations = $formations->formacion_id;
        $formations = explode(",", $formations);
        $formations_names = [];
        foreach ($formations as $v) {
            array_push($formations_names, formacionxpozo::where('id', $v)->first()->nombre);
        }
        $elements = $formations_names;

        $button_wr = (bool) $statistical->status_wr;

        if (!$button_wr) {
            $datos = $this->graficoStatistical($statistical, $elements);
        } else {
            $datos = json_encode([]);
        }

        return view('multiparametricAnalysis.statistical.show', compact(['statistical', 'datos']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $i
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* se trae todos los datos de la tabla statistical con el id = $id */
        $escenario_id = $id;
        $campo_id = escenario::where('id',$escenario_id)->first()->campo_id;
        $statistical = Statistical::where('escenario_id', $escenario_id)->first();
        $formations = escenario::where('id',$escenario_id)->first();
        $formations = $formations->formacion_id;
        $formations = explode(",", $formations);
        $formations_names = [];
        foreach ($formations as $v) {
            array_push($formations_names, formacion::where('id', $v)->first()->nombre);
        }
        $formations = $formations_names;
        $formationsWithoutSpaces = [];
        foreach ($formations_names as $key => $formation) {
            array_push($formationsWithoutSpaces, str_replace(" ", "_", $formation));
        }
        
        if (!$statistical) {
            $statistical = Statistical::where('escenario_id', $id)->first();
            if (!$statistical) {
                abort('404');
            }
        }

        $escenario = escenario::where('id',$escenario_id)->first();
        $pozo_id = $escenario->pozo_id;

        // ORGANIZAR MEDICIONES POR 1 POZO, 1 CAMPO Y MULTIPLES FORMACIONES
        $mediciones = [];
        foreach ($formations as $key1 => $formation) {
            $formacion_id = formacion::where('nombre', $formation)->where('campo_id', $campo_id)->first()->id;
            $mediciones_aux = medicion::where('pozo_id', $pozo_id)->where('formacion_id', $formacion_id)->get();
            if (!$mediciones_aux->isEmpty()) {
                foreach ($mediciones_aux as $key => $medicion_aux) {
                    $subparametro = DB::table('subparametros')->where('id', $medicion_aux->subparametro_id)->first()->sigla;
                    array_push($mediciones, [$medicion_aux->id, $medicion_aux->pozo_id, $medicion_aux->formacion_id, $medicion_aux->subparametro_id, $medicion_aux->valor, Carbon::parse($medicion_aux->fecha)->format('d/m/Y'), $medicion_aux->comentario, $subparametro, $formationsWithoutSpaces[$key1]]);
                }  
            }
        }

        $pozoId = escenario::find($statistical->escenario_id)->pozo_id;

        //Encontrar Weights
        $pesos_query = subparameters_weight::where('multiparametric_id', $id)->get();
        $pesos = [];
        if ( count($pesos_query) == 0 ) {
            $pesos = null;
        } else {
            $pesos_query = $pesos_query[0];
            $pesos[1] = $pesos_query->ms_scale_index_caco3;
            $pesos[2] = $pesos_query->ms_scale_index_baso4;
            $pesos[3] = $pesos_query->ms_scale_index_iron_scales;
            $pesos[4] = $pesos_query->ms_calcium_concentration;
            $pesos[5] = $pesos_query->ms_barium_concentration;
            $pesos[6] = $pesos_query->fb_aluminum_concentration;
            $pesos[7] = $pesos_query->fb_silicon_concentration;
            $pesos[8] = $pesos_query->fb_critical_radius_factor;
            $pesos[9] = $pesos_query->fb_mineralogic_factor;
            $pesos[10] = $pesos_query->fb_crushed_proppant_factor;
            $pesos[11] = $pesos_query->os_cll_factor;
            $pesos[12] = $pesos_query->os_volume_of_hcl;
            $pesos[13] = $pesos_query->os_compositional_factor;
            $pesos[14] = $pesos_query->os_pressure_factor;
            $pesos[15] = $pesos_query->os_high_impact_factor;
            $pesos[16] = $pesos_query->rp_days_below_saturation_pressure;
            $pesos[17] = $pesos_query->rp_delta_pressure_saturation;
            $pesos[18] = $pesos_query->rp_water_intrusion;
            $pesos[19] = $pesos_query->rp_high_impact_factor;
            $pesos[20] = $pesos_query->rp_velocity_estimated;
            $pesos[21] = $pesos_query->id_gross_pay;
            $pesos[22] = $pesos_query->id_polymer_damage_factor;
            $pesos[23] = $pesos_query->id_total_volume_water;
            $pesos[24] = $pesos_query->id_mud_damage_factor;
            $pesos[25] = $pesos_query->gd_fraction_netpay;
            $pesos[26] = $pesos_query->gd_drawdown;
            $pesos[27] = $pesos_query->gd_ratio_kh_fracture;
            $pesos[28] = $pesos_query->gd_geomechanical_damage_fraction;
        }

        /* se convierten  los datos autoriazados por bloques de string a arrays */
        $statistical->msAvailable = array_map('intval', explode(',', $statistical->msAvailable));
        $statistical->fbAvailable = array_map('intval', explode(',', $statistical->fbAvailable));
        $statistical->osAvailable = array_map('intval', explode(',', $statistical->osAvailable));
        $statistical->rpAvailable = array_map('intval', explode(',', $statistical->rpAvailable));
        $statistical->idAvailable = array_map('intval', explode(',', $statistical->idAvailable));
        $statistical->gdAvailable = array_map('intval', explode(',', $statistical->gdAvailable));

        $statistical->date_ms1 = array_map('strval', explode(',', $statistical->date_ms1));
        $statistical->date_ms2 = array_map('strval', explode(',', $statistical->date_ms2));
        $statistical->date_ms3 = array_map('strval', explode(',', $statistical->date_ms3));
        $statistical->date_ms4 = array_map('strval', explode(',', $statistical->date_ms4));
        $statistical->date_ms5 = array_map('strval', explode(',', $statistical->date_ms5));
        $statistical->date_fb1 = array_map('strval', explode(',', $statistical->date_fb1));
        $statistical->date_fb2 = array_map('strval', explode(',', $statistical->date_fb2));
        $statistical->date_fb3 = array_map('strval', explode(',', $statistical->date_fb3));
        $statistical->date_fb4 = array_map('strval', explode(',', $statistical->date_fb4));
        $statistical->date_fb5 = array_map('strval', explode(',', $statistical->date_fb5));
        $statistical->date_os1 = array_map('strval', explode(',', $statistical->date_os1));
        $statistical->date_os2 = array_map('strval', explode(',', $statistical->date_os2));
        $statistical->date_os3 = array_map('strval', explode(',', $statistical->date_os3));
        $statistical->date_os4 = array_map('strval', explode(',', $statistical->date_os4));
        $statistical->date_os5 = array_map('strval', explode(',', $statistical->date_os5));
        $statistical->date_rp1 = array_map('strval', explode(',', $statistical->date_rp1));
        $statistical->date_rp2 = array_map('strval', explode(',', $statistical->date_rp2));
        $statistical->date_rp3 = array_map('strval', explode(',', $statistical->date_rp3));
        $statistical->date_rp4 = array_map('strval', explode(',', $statistical->date_rp4));
        $statistical->date_rp5 = array_map('strval', explode(',', $statistical->date_rp5));
        $statistical->date_id1 = array_map('strval', explode(',', $statistical->date_id1));
        $statistical->date_id2 = array_map('strval', explode(',', $statistical->date_id2));
        $statistical->date_id3 = array_map('strval', explode(',', $statistical->date_id3));
        $statistical->date_id4 = array_map('strval', explode(',', $statistical->date_id4));
        $statistical->date_gd1 = array_map('strval', explode(',', $statistical->date_gd1));
        $statistical->date_gd2 = array_map('strval', explode(',', $statistical->date_gd2));
        $statistical->date_gd3 = array_map('strval', explode(',', $statistical->date_gd3));
        $statistical->date_gd4 = array_map('strval', explode(',', $statistical->date_gd4));

        //se trae todas las cuencas existentes
        $cuencas = cuenca::orderBy('nombre')->get();
        $complete = false;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        // dd($statistical);
        return view('multiparametricAnalysis.statistical.edit', compact(['statistical', 'cuencas', 'complete', 'pozoId', 'duplicateFrom', 'formations', 'mediciones', 'pesos', 'formationsWithoutSpaces']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MultiparametricStatisticalRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MultiparametricStatisticalRequest $request, $id)
    {
        if (\Auth::check()) {

            if ($request->only_s == "run") {
                //VALIDATE

                // Encontrar formaciones y nombres de campos de entrada
                $scenario = escenario::find($request->id_scenary);
                $statistical = Statistical::find($id);
                $escenario_id = $statistical->escenario_id;
                $formations = escenario::where('id',$escenario_id)->first();
                $formations = $formations->formacion_id;
                $formations = explode(",", $formations);
                $formations_names = [];
                foreach ($formations as $v) {
                    array_push($formations_names, formacionxpozo::where('id', $v)->first()->nombre);
                }
                $elements = $formations_names;
                $title = [  'MS1', 'MS2', 'MS3', 'MS4', 'MS5', 
                            'FB1', 'FB2', 'FB3', 'FB4', 'FB5',
                            'OS1', 'OS2', 'OS3', 'OS4', 'OS5',
                            'RP1', 'RP2', 'RP3', 'RP4', 'RP5',
                            'ID1', 'ID2', 'ID3', 'ID4',
                            'GD1', 'GD2', 'GD3', 'GD4'     ];

                $weights = subparameters_weight::where('multiparametric_id', $id)->first();

                //se modifica el array del campo field_statistical con implode
                if ($request->field_statistical) {
                    $input['field_statistical'] = implode(",", $request->field_statistical);
                    $request->statistical = null;
                } else {
                    $input['field_statistical'] = null;
                    $request->basin_statistical = null;
                }

                // se guardan solo los campos field_statistical y statistical en la bbdd;
                $statistical->escenario_id = $request->id_scenary;
                $statistical->field_statistical = $input['field_statistical'];
                $statistical->basin_statistical = $request->basin_statistical;
                $statistical->statistical = $request->statistical;
                
                // Pestaña Mineral Scales
                for ($i=1; $i <= 5; $i++) { 
                    $value_aux = [];
                    $date_aux = [];
                    $comment_aux = [];
                    $title_aux = array_shift($title);
                    foreach ($elements as $element) {
                        array_push($value_aux, $request->{'value_'.$title_aux.$element});
                        array_push($date_aux, $request->{'date_'.$title_aux.$element});
                        array_push($comment_aux, $request->{'comment_'.$title_aux.$element});
                    }
                    $statistical->{'ms'.$i} = implode(',', $value_aux);
                    $statistical->{'date_ms'.$i} = implode(',', $date_aux);
                    $statistical->{'comment_ms'.$i} = implode(',', $comment_aux);
                    $statistical->{'p10_ms'.$i} = $request->{'p10_'.$title_aux};
                    $statistical->{'p90_ms'.$i} = $request->{'p90_'.$title_aux};
                    if ($request->{$title_aux.'_checkbox'} == null) {
                        $statistical->{'checkbox_ms'.$i} = 0;
                    } else {
                        $statistical->{'checkbox_ms'.$i} = 1;
                    }
                }
                $weights->ms_scale_index_caco3 = $request->weight_MS1;
                $weights->ms_scale_index_baso4 = $request->weight_MS2;
                $weights->ms_scale_index_iron_scales = $request->weight_MS3;
                $weights->ms_calcium_concentration = $request->weight_MS4;
                $weights->ms_barium_concentration = $request->weight_MS5;
                
                // Pestaña Fine Blockage
                for ($i=1; $i <= 5; $i++) { 
                    $value_aux = [];
                    $date_aux = [];
                    $comment_aux = [];
                    $title_aux = array_shift($title);
                    foreach ($elements as $element) {
                        array_push($value_aux, $request->{'value_'.$title_aux.$element});
                        array_push($date_aux, $request->{'date_'.$title_aux.$element});
                        array_push($comment_aux, $request->{'comment_'.$title_aux.$element});
                    }
                    $statistical->{'fb'.$i} = implode(',', $value_aux);
                    $statistical->{'date_fb'.$i} = implode(',', $date_aux);
                    $statistical->{'comment_fb'.$i} = implode(',', $comment_aux);
                    $statistical->{'p10_fb'.$i} = $request->{'p10_'.$title_aux};
                    $statistical->{'p90_fb'.$i} = $request->{'p90_'.$title_aux};
                    if ($request->{$title_aux.'_checkbox'} == null) {
                        $statistical->{'checkbox_fb'.$i} = 0;
                    } else {
                        $statistical->{'checkbox_fb'.$i} = 1;
                    }
                }
                $weights->fb_aluminum_concentration = $request->weight_FB1;
                $weights->fb_silicon_concentration = $request->weight_FB2;
                $weights->fb_critical_radius_factor = $request->weight_FB3;
                $weights->fb_mineralogic_factor = $request->weight_FB4;
                $weights->fb_crushed_proppant_factor = $request->weight_FB5;

                // Pestaña Organic Scales
                for ($i=1; $i <= 5; $i++) { 
                    $value_aux = [];
                    $date_aux = [];
                    $comment_aux = [];
                    $title_aux = array_shift($title);
                    foreach ($elements as $element) {
                        array_push($value_aux, $request->{'value_'.$title_aux.$element});
                        array_push($date_aux, $request->{'date_'.$title_aux.$element});
                        array_push($comment_aux, $request->{'comment_'.$title_aux.$element});
                    }
                    $statistical->{'os'.$i} = implode(',', $value_aux);
                    $statistical->{'date_os'.$i} = implode(',', $date_aux);
                    $statistical->{'comment_os'.$i} = implode(',', $comment_aux);
                    $statistical->{'p10_os'.$i} = $request->{'p10_'.$title_aux};
                    $statistical->{'p90_os'.$i} = $request->{'p90_'.$title_aux};
                    if ($request->{$title_aux.'_checkbox'} == null) {
                        $statistical->{'checkbox_os'.$i} = 0;
                    } else {
                        $statistical->{'checkbox_os'.$i} = 1;
                    }
                }
                $weights->os_cll_factor = $request->weight_OS1;
                $weights->os_volume_of_hcl = $request->weight_OS2;
                $weights->os_compositional_factor = $request->weight_OS3;
                $weights->os_pressure_factor = $request->weight_OS4;
                $weights->os_high_impact_factor = $request->weight_OS5;
                
                // Relative Permeability
                for ($i=1; $i <= 5; $i++) { 
                    $value_aux = [];
                    $date_aux = [];
                    $comment_aux = [];
                    $title_aux = array_shift($title);
                    foreach ($elements as $element) {
                        array_push($value_aux, $request->{'value_'.$title_aux.$element});
                        array_push($date_aux, $request->{'date_'.$title_aux.$element});
                        array_push($comment_aux, $request->{'comment_'.$title_aux.$element});
                    }
                    $statistical->{'rp'.$i} = implode(',', $value_aux);
                    $statistical->{'date_rp'.$i} = implode(',', $date_aux);
                    $statistical->{'comment_rp'.$i} = implode(',', $comment_aux);
                    $statistical->{'p10_rp'.$i} = $request->{'p10_'.$title_aux};
                    $statistical->{'p90_rp'.$i} = $request->{'p90_'.$title_aux};
                    if ($request->{$title_aux.'_checkbox'} == null) {
                        $statistical->{'checkbox_rp'.$i} = 0;
                    } else {
                        $statistical->{'checkbox_rp'.$i} = 1;
                    }
                }
                $weights->rp_days_below_saturation_pressure = $request->weight_RP1;
                $weights->rp_delta_pressure_saturation = $request->weight_RP2;
                $weights->rp_water_intrusion = $request->weight_RP3;
                $weights->rp_velocity_estimated = $request->weight_RP4;
                $weights->rp_high_impact_factor = $request->weight_RP5;
                
                // Induced Damage
                for ($i=1; $i <= 4; $i++) { 
                    $value_aux = [];
                    $date_aux = [];
                    $comment_aux = [];
                    $title_aux = array_shift($title);
                    foreach ($elements as $element) {
                        array_push($value_aux, $request->{'value_'.$title_aux.$element});
                        array_push($date_aux, $request->{'date_'.$title_aux.$element});
                        array_push($comment_aux, $request->{'comment_'.$title_aux.$element});
                    }
                    $statistical->{'id'.$i} = implode(',', $value_aux);
                    $statistical->{'date_id'.$i} = implode(',', $date_aux);
                    $statistical->{'comment_id'.$i} = implode(',', $comment_aux);
                    $statistical->{'p10_id'.$i} = $request->{'p10_'.$title_aux};
                    $statistical->{'p90_id'.$i} = $request->{'p90_'.$title_aux};
                    if ($request->{$title_aux.'_checkbox'} == null) {
                        $statistical->{'checkbox_id'.$i} = 0;
                    } else {
                        $statistical->{'checkbox_id'.$i} = 1;
                    }
                }
                $weights->id_gross_pay = $request->weight_ID1;
                $weights->id_polymer_damage_factor = $request->weight_ID2;
                $weights->id_total_volume_water = $request->weight_ID3;
                $weights->id_mud_damage_factor = $request->weight_ID4;
                
                // Geomechanical Damage
                for ($i=1; $i <= 4; $i++) { 
                    $value_aux = [];
                    $date_aux = [];
                    $comment_aux = [];
                    $title_aux = array_shift($title);
                    foreach ($elements as $element) {
                        array_push($value_aux, $request->{'value_'.$title_aux.$element});
                        array_push($date_aux, $request->{'date_'.$title_aux.$element});
                        array_push($comment_aux, $request->{'comment_'.$title_aux.$element});
                    }
                    $statistical->{'gd'.$i} = implode(',', $value_aux);
                    $statistical->{'date_gd'.$i} = implode(',', $date_aux);
                    $statistical->{'comment_gd'.$i} = implode(',', $comment_aux);
                    $statistical->{'p10_gd'.$i} = $request->{'p10_'.$title_aux};
                    $statistical->{'p90_gd'.$i} = $request->{'p90_'.$title_aux};
                    if ($request->{$title_aux.'_checkbox'} == null) {
                        $statistical->{'checkbox_gd'.$i} = 0;
                    } else {
                        $statistical->{'checkbox_gd'.$i} = 1;
                    }
                }
                $weights->gd_fraction_netpay = $request->weight_GD1;
                $weights->gd_drawdown = $request->weight_GD2;
                $weights->gd_ratio_kh_fracture = $request->weight_GD3;
                $weights->gd_geomechanical_damage_fraction = $request->weight_GD4;

                $statistical->kd = $request->kd;
                $statistical->status_wr = 0;
                $statistical->save();
                
                $weights->save();

                $scenario->completo = 1;
                $scenario->estado = 1;
                $scenario->save();

                return redirect()->route('statistical.show', $statistical->id);

            }










    





            // if (isset($request->duplicate)) {
                
            //     unset($_SESSION['scenary_id_dup']);

            //     $scenario = escenario::find($request->duplicate);

            //     //se conviertelos arrays en cadenas
            //     if ($request->msAvailable) {
            //         $input['msAvailable'] = implode(",", $request->msAvailable);
            //     } else {
            //         $input['msAvailable'] = null;
            //     }

            //     if ($request->fbAvailable) {
            //         $input['fbAvailable'] = implode(",", $request->fbAvailable);
            //     } else {
            //         $input['fbAvailable'] = null;
            //     }

            //     if ($request->osAvailable) {
            //         $input['osAvailable'] = implode(",", $request->osAvailable);
            //     } else {
            //         $input['osAvailable'] = null;
            //     }

            //     if ($request->rpAvailable) {
            //         $input['rpAvailable'] = implode(",", $request->rpAvailable);
            //     } else {
            //         $input['rpAvailable'] = null;
            //     }

            //     if ($request->idAvailable) {
            //         $input['idAvailable'] = implode(",", $request->idAvailable);
            //     } else {
            //         $input['idAvailable'] = null;
            //     }

            //     if ($request->gdAvailable) {
            //         $input['gdAvailable'] = implode(",", $request->gdAvailable);
            //     } else {
            //         $input['gdAvailable'] = null;
            //     }

            //     $statistical = new Statistical;
            //     $statistical->escenario_id = $request->duplicate;
                
            //     if ($request->msAvailable) {
            //         $availableArray = $request->msAvailable;

            //         if (in_array('1', $availableArray)) {
            //             $statistical->ms1 = $request->MS1;
            //             $statistical->date_ms1 = Carbon::createFromFormat('d/m/Y', $request->dateMS1)->format('Y-m-d');
            //             $statistical->comment_ms1 = $request->MS1comment;
            //             $statistical->p10_ms1 = $request->p10_MS1;
            //             $statistical->p90_ms1 = $request->p90_MS1;
            //         }

            //         if (in_array('2', $availableArray)) {
            //             $statistical->ms2 = $request->MS2;
            //             $statistical->date_ms2 = Carbon::createFromFormat('d/m/Y', $request->dateMS2)->format('Y-m-d');
            //             $statistical->comment_ms2 = $request->MS2comment;
            //             $statistical->p10_ms2 = $request->p10_MS2;
            //             $statistical->p90_ms2 = $request->p90_MS2;
            //         }

            //         if (in_array('3', $availableArray)) {
            //             $statistical->ms3 = $request->MS3;
            //             $statistical->date_ms3 = Carbon::createFromFormat('d/m/Y', $request->dateMS3)->format('Y-m-d');
            //             $statistical->comment_ms3 = $request->MS3comment;
            //             $statistical->p10_ms3 = $request->p10_MS3;
            //             $statistical->p90_ms3 = $request->p90_MS3;
            //         }

            //         if (in_array('4', $availableArray)) {
            //             $statistical->ms4 = $request->MS4;
            //             $statistical->date_ms4 = Carbon::createFromFormat('d/m/Y', $request->dateMS4)->format('Y-m-d');
            //             $statistical->comment_ms4 = $request->MS4comment;
            //             $statistical->p10_ms4 = $request->p10_MS4;
            //             $statistical->p90_ms4 = $request->p90_MS4;
            //         }

            //         if (in_array('5', $availableArray)) {
            //             $statistical->ms5 = $request->MS5;
            //             $statistical->date_ms5 = Carbon::createFromFormat('d/m/Y', $request->dateMS5)->format('Y-m-d');
            //             $statistical->comment_ms5 = $request->MS5comment;
            //             $statistical->p10_ms5 = $request->p10_MS5;
            //             $statistical->p90_ms5 = $request->p90_MS5;
            //         }
            //     }

            //     if ($request->fbAvailable) {
            //         $availableArray = $request->fbAvailable;

            //         if (in_array('1', $availableArray)) {
            //             $statistical->fb1 = $request->FB1;
            //             $statistical->date_fb1 = Carbon::createFromFormat('d/m/Y', $request->dateFB1)->format('Y-m-d');
            //             $statistical->comment_fb1 = $request->FB1comment;
            //             $statistical->p10_fb1 = $request->p10_FB1;
            //             $statistical->p90_fb1 = $request->p90_FB1;
            //         }

            //         if (in_array('2', $availableArray)) {
            //             $statistical->fb2 = $request->FB2;
            //             $statistical->date_fb2 = Carbon::createFromFormat('d/m/Y', $request->dateFB2)->format('Y-m-d');
            //             $statistical->comment_fb2 = $request->FB2comment;
            //             $statistical->p10_fb2 = $request->p10_FB2;
            //             $statistical->p90_fb2 = $request->p90_FB2;
            //         }

            //         if (in_array('3', $availableArray)) {
            //             $statistical->fb3 = $request->FB3;
            //             $statistical->date_fb3 = Carbon::createFromFormat('d/m/Y', $request->dateFB3)->format('Y-m-d');
            //             $statistical->comment_fb3 = $request->FB3comment;
            //             $statistical->p10_fb3 = $request->p10_FB3;
            //             $statistical->p90_fb3 = $request->p90_FB3;
            //         }

            //         if (in_array('4', $availableArray)) {
            //             $statistical->fb4 = $request->FB4;
            //             $statistical->date_fb4 = Carbon::createFromFormat('d/m/Y', $request->dateFB4)->format('Y-m-d');
            //             $statistical->comment_fb4 = $request->FB4comment;
            //             $statistical->p10_fb4 = $request->p10_FB4;
            //             $statistical->p90_fb4 = $request->p90_FB4;
            //         }

            //         if (in_array('5', $availableArray)) {
            //             $statistical->fb5 = $request->FB5;
            //             $statistical->date_fb5 = Carbon::createFromFormat('d/m/Y', $request->dateFB5)->format('Y-m-d');
            //             $statistical->comment_fb5 = $request->FB5comment;
            //             $statistical->p10_fb5 = $request->p10_FB5;
            //             $statistical->p90_fb5 = $request->p90_FB5;
            //         }
            //     }

            //     if ($request->osAvailable) {
            //         $availableArray = $request->osAvailable;

            //         if (in_array('1', $availableArray)) {
            //             $statistical->os1 = $request->OS1;
            //             $statistical->date_os1 = Carbon::createFromFormat('d/m/Y', $request->dateOS1)->format('Y-m-d');
            //             $statistical->comment_os1 = $request->OS1comment;
            //             $statistical->p10_os1 = $request->p10_OS1;
            //             $statistical->p90_os1 = $request->p90_OS1;
            //         }

            //         if (in_array('2', $availableArray)) {
            //             $statistical->os2 = $request->OS2;
            //             $statistical->date_os2 = Carbon::createFromFormat('d/m/Y', $request->dateOS2)->format('Y-m-d');
            //             $statistical->comment_os2 = $request->OS2comment;
            //             $statistical->p10_os2 = $request->p10_OS2;
            //             $statistical->p90_os2 = $request->p90_OS2;
            //         }

            //         if (in_array('3', $availableArray)) {
            //             $statistical->os3 = $request->OS3;
            //             $statistical->date_os3 = Carbon::createFromFormat('d/m/Y', $request->dateOS3)->format('Y-m-d');
            //             $statistical->comment_os3 = $request->OS3comment;
            //             $statistical->p10_os3 = $request->p10_OS3;
            //             $statistical->p90_os3 = $request->p90_OS3;
            //         }

            //         if (in_array('4', $availableArray)) {
            //             $statistical->os4 = $request->OS4;
            //             $statistical->date_os4 = Carbon::createFromFormat('d/m/Y', $request->dateOS4)->format('Y-m-d');
            //             $statistical->comment_os4 = $request->OS4comment;
            //             $statistical->p10_os4 = $request->p10_OS4;
            //             $statistical->p90_os4 = $request->p90_OS4;
            //         }

            //         if (in_array('5', $availableArray)) {
            //             $statistical->os5 = $request->OS5;
            //             $statistical->date_os5 = Carbon::createFromFormat('d/m/Y', $request->dateOS5)->format('Y-m-d');
            //             $statistical->comment_os5 = $request->OS5comment;
            //             $statistical->p10_os5 = $request->p10_OS5;
            //             $statistical->p90_os5 = $request->p90_OS5;
            //         }
            //     }

            //     if ($request->rpAvailable) {
            //         $availableArray = $request->rpAvailable;

            //         if (in_array('1', $availableArray)) {
            //             $statistical->rp1 = $request->RP1;
            //             $statistical->date_rp1 = Carbon::createFromFormat('d/m/Y', $request->dateRP1)->format('Y-m-d');
            //             $statistical->comment_rp1 = $request->RP1comment;
            //             $statistical->p10_rp1 = $request->p10_RP1;
            //             $statistical->p90_rp1 = $request->p90_RP1;
            //         }

            //         if (in_array('2', $availableArray)) {
            //             $statistical->rp2 = $request->RP2;
            //             $statistical->date_rp2 = Carbon::createFromFormat('d/m/Y', $request->dateRP2)->format('Y-m-d');
            //             $statistical->comment_rp2 = $request->RP2comment;
            //             $statistical->p10_rp2 = $request->p10_RP2;
            //             $statistical->p90_rp2 = $request->p90_RP2;
            //         }

            //         if (in_array('3', $availableArray)) {
            //             $statistical->rp3 = $request->RP3;
            //             $statistical->date_rp3 = Carbon::createFromFormat('d/m/Y', $request->dateRP3)->format('Y-m-d');
            //             $statistical->comment_rp3 = $request->RP3comment;
            //             $statistical->p10_rp3 = $request->p10_RP3;
            //             $statistical->p90_rp3 = $request->p90_RP3;
            //         }

            //         if (in_array('4', $availableArray)) {
            //             $statistical->rp4 = $request->RP4;
            //             $statistical->date_rp4 = Carbon::createFromFormat('d/m/Y', $request->dateRP4)->format('Y-m-d');
            //             $statistical->comment_rp4 = $request->RP4comment;
            //             $statistical->p10_rp4 = $request->p10_RP4;
            //             $statistical->p90_rp4 = $request->p90_RP4;
            //         }

            //         if (in_array('5', $availableArray)) {
            //             $statistical->rp5 = $request->RP5;
            //             $statistical->date_rp5 = Carbon::createFromFormat('d/m/Y', $request->dateRP5)->format('Y-m-d');
            //             $statistical->comment_rp5 = $request->RP5comment;
            //             $statistical->p10_rp5 = $request->p10_RP5;
            //             $statistical->p90_rp5 = $request->p90_RP5;
            //         }
            //     }

            //     if ($request->idAvailable) {
            //         $availableArray = $request->idAvailable;

            //         if (in_array('1', $availableArray)) {
            //             $statistical->id1 = $request->ID1;
            //             $statistical->date_id1 = Carbon::createFromFormat('d/m/Y', $request->dateID1)->format('Y-m-d');
            //             $statistical->comment_id1 = $request->ID1comment;
            //             $statistical->p10_id1 = $request->p10_ID1;
            //             $statistical->p90_id1 = $request->p90_ID1;
            //         }

            //         if (in_array('2', $availableArray)) {
            //             $statistical->id2 = $request->ID2;
            //             $statistical->date_id2 = Carbon::createFromFormat('d/m/Y', $request->dateID2)->format('Y-m-d');
            //             $statistical->comment_id2 = $request->ID2comment;
            //             $statistical->p10_id2 = $request->p10_ID2;
            //             $statistical->p90_id2 = $request->p90_ID2;
            //         }

            //         if (in_array('3', $availableArray)) {
            //             $statistical->id3 = $request->ID3;
            //             $statistical->date_id3 = Carbon::createFromFormat('d/m/Y', $request->dateID3)->format('Y-m-d');
            //             $statistical->comment_id3 = $request->ID3comment;
            //             $statistical->p10_id3 = $request->p10_ID3;
            //             $statistical->p90_id3 = $request->p90_ID3;
            //         }

            //         if (in_array('4', $availableArray)) {
            //             $statistical->id4 = $request->ID4;
            //             $statistical->date_id4 = Carbon::createFromFormat('d/m/Y', $request->dateID4)->format('Y-m-d');
            //             $statistical->comment_id4 = $request->ID4comment;
            //             $statistical->p10_id4 = $request->p10_ID4;
            //             $statistical->p90_id4 = $request->p90_ID4;
            //         }
            //     }

            //     if ($request->gdAvailable) {
            //         $availableArray = $request->gdAvailable;

            //         if (in_array('1', $availableArray)) {
            //             $statistical->gd1 = $request->GD1;
            //             $statistical->date_gd1 = Carbon::createFromFormat('d/m/Y', $request->dateGD1)->format('Y-m-d');
            //             $statistical->comment_gd1 = $request->GD1comment;
            //             $statistical->p10_gd1 = $request->p10_GD1;
            //             $statistical->p90_gd1 = $request->p90_GD1;
            //         }

            //         if (in_array('2', $availableArray)) {
            //             $statistical->gd2 = $request->GD2;
            //             $statistical->date_gd2 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
            //             $statistical->comment_gd2 = $request->GD2comment;
            //             $statistical->p10_gd2 = $request->p10_GD2;
            //             $statistical->p90_gd2 = $request->p90_GD2;
            //         }

            //         if (in_array('3', $availableArray)) {
            //             $statistical->gd3 = $request->GD3;
            //             $statistical->date_gd3 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
            //             $statistical->comment_gd3 = $request->GD3comment;
            //             $statistical->p10_gd3 = $request->p10_GD3;
            //             $statistical->p90_gd3 = $request->p90_GD3;
            //         }

            //         if (in_array('4', $availableArray)) {
            //             $statistical->gd4 = $request->GD4;
            //             $statistical->date_gd4 = Carbon::createFromFormat('d/m/Y', $request->dateGD4)->format('Y-m-d');
            //             $statistical->comment_gd4 = $request->GD4comment;
            //             $statistical->p10_gd4 = $request->p10_GD4;
            //             $statistical->p90_gd4 = $request->p90_GD4;
            //         }
            //     }
            //     $statistical->kd = $request->kd;
            //     $statistical->msAvailable = $input['msAvailable'];
            //     $statistical->fbAvailable = $input['fbAvailable'];
            //     $statistical->osAvailable = $input['osAvailable'];
            //     $statistical->rpAvailable = $input['rpAvailable'];
            //     $statistical->idAvailable = $input['idAvailable'];
            //     $statistical->gdAvailable = $input['gdAvailable'];
            //     $statistical->status_wr = $request->only_s == "save" ? 1 : 0;
            //     $statistical->escenario_id = $request->id_scenary;
            //     $statistical->save();

            //     $scenario->completo = $request->only_s == "save" ? 0 : 1;
            //     $scenario->estado = 1;
            //     $scenario->save();

            //     $subparameters_weight = subparameters_weight::create(['multiparametric_id' => $statistical->id]);
            //     $subparameters_weight->update($request->all());

            //     return redirect()->route('statistical.show', $statistical->id);
            // } else {
            //     if ($request->calculate == "true") {
            //         //se modifica el array del campo field_statistical con implode
            //         if ($request->field_statistical) {
            //             $input['field_statistical'] = implode(",", $request->field_statistical);
            //             $request->statistical = null;
            //         } else {
            //             $input['field_statistical'] = null;
            //             $request->basin_statistical = null;
            //         }
    
            //         //se guardan solo los campos field_statistical y statistical en la bbdd;
            //         $statistical = Statistical::find($id);
            //         $statistical->escenario_id = $request->id_scenary;
            //         $statistical->field_statistical = $input['field_statistical'];
            //         $statistical->basin_statistical = $request->basin_statistical;
            //         $statistical->statistical = $request->statistical;
            //         $statistical->save();
    
            //         /* se pasa la variable calculate al funcion edit */
            //         Session::flash('calculate', $request->calculate);
    
            //         return redirect()->route('statistical.edit', $statistical->id);
            //     } else {
            //         unset($_SESSION['scenary_id_dup']);
            //         $scenario = escenario::find($request->id_scenary);
    
            //         //se conviertelos arrays en cadenas
            //         if ($request->msAvailable) {
            //             $input['msAvailable'] = implode(",", $request->msAvailable);
            //         } else {
            //             $input['msAvailable'] = null;
            //         }
    
            //         if ($request->fbAvailable) {
            //             $input['fbAvailable'] = implode(",", $request->fbAvailable);
            //         } else {
            //             $input['fbAvailable'] = null;
            //         }
    
            //         if ($request->osAvailable) {
            //             $input['osAvailable'] = implode(",", $request->osAvailable);
            //         } else {
            //             $input['osAvailable'] = null;
            //         }
    
            //         if ($request->rpAvailable) {
            //             $input['rpAvailable'] = implode(",", $request->rpAvailable);
            //         } else {
            //             $input['rpAvailable'] = null;
            //         }
    
            //         if ($request->idAvailable) {
            //             $input['idAvailable'] = implode(",", $request->idAvailable);
            //         } else {
            //             $input['idAvailable'] = null;
            //         }
    
            //         if ($request->gdAvailable) {
            //             $input['gdAvailable'] = implode(",", $request->gdAvailable);
            //         } else {
            //             $input['gdAvailable'] = null;
            //         }
    
            //         //se ingresa los datos de la tabla statistical
            //         $statistical = Statistical::find($id);
    
            //         if ($request->msAvailable) {
            //             $availableArray = $request->msAvailable;
    
            //             if (in_array('1', $availableArray)) {
            //                 $statistical->ms1 = $request->MS1;
            //                 $statistical->date_ms1 = Carbon::createFromFormat('d/m/Y', $request->dateMS1)->format('Y-m-d');
            //                 $statistical->comment_ms1 = $request->MS1comment;
            //                 $statistical->p10_ms1 = $request->p10_MS1;
            //                 $statistical->p90_ms1 = $request->p90_MS1;
            //             }
    
            //             if (in_array('2', $availableArray)) {
            //                 $statistical->ms2 = $request->MS2;
            //                 $statistical->date_ms2 = Carbon::createFromFormat('d/m/Y', $request->dateMS2)->format('Y-m-d');
            //                 $statistical->comment_ms2 = $request->MS2comment;
            //                 $statistical->p10_ms2 = $request->p10_MS2;
            //                 $statistical->p90_ms2 = $request->p90_MS2;
            //             }
    
            //             if (in_array('3', $availableArray)) {
            //                 $statistical->ms3 = $request->MS3;
            //                 $statistical->date_ms3 = Carbon::createFromFormat('d/m/Y', $request->dateMS3)->format('Y-m-d');
            //                 $statistical->comment_ms3 = $request->MS3comment;
            //                 $statistical->p10_ms3 = $request->p10_MS3;
            //                 $statistical->p90_ms3 = $request->p90_MS3;
            //             }
    
            //             if (in_array('4', $availableArray)) {
            //                 $statistical->ms4 = $request->MS4;
            //                 $statistical->date_ms4 = Carbon::createFromFormat('d/m/Y', $request->dateMS4)->format('Y-m-d');
            //                 $statistical->comment_ms4 = $request->MS4comment;
            //                 $statistical->p10_ms4 = $request->p10_MS4;
            //                 $statistical->p90_ms4 = $request->p90_MS4;
            //             }
    
            //             if (in_array('5', $availableArray)) {
            //                 $statistical->ms5 = $request->MS5;
            //                 $statistical->date_ms5 = Carbon::createFromFormat('d/m/Y', $request->dateMS5)->format('Y-m-d');
            //                 $statistical->comment_ms5 = $request->MS5comment;
            //                 $statistical->p10_ms5 = $request->p10_MS5;
            //                 $statistical->p90_ms5 = $request->p90_MS5;
            //             }
            //         }
    
            //         if ($request->fbAvailable) {
            //             $availableArray = $request->fbAvailable;
    
            //             if (in_array('1', $availableArray)) {
            //                 $statistical->fb1 = $request->FB1;
            //                 $statistical->date_fb1 = Carbon::createFromFormat('d/m/Y', $request->dateFB1)->format('Y-m-d');
            //                 $statistical->comment_fb1 = $request->FB1comment;
            //                 $statistical->p10_fb1 = $request->p10_FB1;
            //                 $statistical->p90_fb1 = $request->p90_FB1;
            //             }
    
            //             if (in_array('2', $availableArray)) {
            //                 $statistical->fb2 = $request->FB2;
            //                 $statistical->date_fb2 = Carbon::createFromFormat('d/m/Y', $request->dateFB2)->format('Y-m-d');
            //                 $statistical->comment_fb2 = $request->FB2comment;
            //                 $statistical->p10_fb2 = $request->p10_FB2;
            //                 $statistical->p90_fb2 = $request->p90_FB2;
            //             }
    
            //             if (in_array('3', $availableArray)) {
            //                 $statistical->fb3 = $request->FB3;
            //                 $statistical->date_fb3 = Carbon::createFromFormat('d/m/Y', $request->dateFB3)->format('Y-m-d');
            //                 $statistical->comment_fb3 = $request->FB3comment;
            //                 $statistical->p10_fb3 = $request->p10_FB3;
            //                 $statistical->p90_fb3 = $request->p90_FB3;
            //             }
    
            //             if (in_array('4', $availableArray)) {
            //                 $statistical->fb4 = $request->FB4;
            //                 $statistical->date_fb4 = Carbon::createFromFormat('d/m/Y', $request->dateFB4)->format('Y-m-d');
            //                 $statistical->comment_fb4 = $request->FB4comment;
            //                 $statistical->p10_fb4 = $request->p10_FB4;
            //                 $statistical->p90_fb4 = $request->p90_FB4;
            //             }
    
            //             if (in_array('5', $availableArray)) {
            //                 $statistical->fb5 = $request->FB5;
            //                 $statistical->date_fb5 = Carbon::createFromFormat('d/m/Y', $request->dateFB5)->format('Y-m-d');
            //                 $statistical->comment_fb5 = $request->FB5comment;
            //                 $statistical->p10_fb5 = $request->p10_FB5;
            //                 $statistical->p90_fb5 = $request->p90_FB5;
            //             }
            //         }
    
            //         if ($request->osAvailable) {
            //             $availableArray = $request->osAvailable;
    
            //             if (in_array('1', $availableArray)) {
            //                 $statistical->os1 = $request->OS1;
            //                 $statistical->date_os1 = Carbon::createFromFormat('d/m/Y', $request->dateOS1)->format('Y-m-d');
            //                 $statistical->comment_os1 = $request->OS1comment;
            //                 $statistical->p10_os1 = $request->p10_OS1;
            //                 $statistical->p90_os1 = $request->p90_OS1;
            //             }
    
            //             if (in_array('2', $availableArray)) {
            //                 $statistical->os2 = $request->OS2;
            //                 $statistical->date_os2 = Carbon::createFromFormat('d/m/Y', $request->dateOS2)->format('Y-m-d');
            //                 $statistical->comment_os2 = $request->OS2comment;
            //                 $statistical->p10_os2 = $request->p10_OS2;
            //                 $statistical->p90_os2 = $request->p90_OS2;
            //             }
    
            //             if (in_array('3', $availableArray)) {
            //                 $statistical->os3 = $request->OS3;
            //                 $statistical->date_os3 = Carbon::createFromFormat('d/m/Y', $request->dateOS3)->format('Y-m-d');
            //                 $statistical->comment_os3 = $request->OS3comment;
            //                 $statistical->p10_os3 = $request->p10_OS3;
            //                 $statistical->p90_os3 = $request->p90_OS3;
            //             }
    
            //             if (in_array('4', $availableArray)) {
            //                 $statistical->os4 = $request->OS4;
            //                 $statistical->date_os4 = Carbon::createFromFormat('d/m/Y', $request->dateOS4)->format('Y-m-d');
            //                 $statistical->comment_os4 = $request->OS4comment;
            //                 $statistical->p10_os4 = $request->p10_OS4;
            //                 $statistical->p90_os4 = $request->p90_OS4;
            //             }
    
            //             if (in_array('5', $availableArray)) {
            //                 $statistical->os5 = $request->OS5;
            //                 $statistical->date_os5 = Carbon::createFromFormat('d/m/Y', $request->dateOS5)->format('Y-m-d');
            //                 $statistical->comment_os5 = $request->OS5comment;
            //                 $statistical->p10_os5 = $request->p10_OS5;
            //                 $statistical->p90_os5 = $request->p90_OS5;
            //             }
            //         }
    
            //         if ($request->rpAvailable) {
            //             $availableArray = $request->rpAvailable;
    
            //             if (in_array('1', $availableArray)) {
            //                 $statistical->rp1 = $request->RP1;
            //                 $statistical->date_rp1 = Carbon::createFromFormat('d/m/Y', $request->dateRP1)->format('Y-m-d');
            //                 $statistical->comment_rp1 = $request->RP1comment;
            //                 $statistical->p10_rp1 = $request->p10_RP1;
            //                 $statistical->p90_rp1 = $request->p90_RP1;
            //             }
    
            //             if (in_array('2', $availableArray)) {
            //                 $statistical->rp2 = $request->RP2;
            //                 $statistical->date_rp2 = Carbon::createFromFormat('d/m/Y', $request->dateRP2)->format('Y-m-d');
            //                 $statistical->comment_rp2 = $request->RP2comment;
            //                 $statistical->p10_rp2 = $request->p10_RP2;
            //                 $statistical->p90_rp2 = $request->p90_RP2;
            //             }
    
            //             if (in_array('3', $availableArray)) {
            //                 $statistical->rp3 = $request->RP3;
            //                 $statistical->date_rp3 = Carbon::createFromFormat('d/m/Y', $request->dateRP3)->format('Y-m-d');
            //                 $statistical->comment_rp3 = $request->RP3comment;
            //                 $statistical->p10_rp3 = $request->p10_RP3;
            //                 $statistical->p90_rp3 = $request->p90_RP3;
            //             }
    
            //             if (in_array('4', $availableArray)) {
            //                 $statistical->rp4 = $request->RP4;
            //                 $statistical->date_rp4 = Carbon::createFromFormat('d/m/Y', $request->dateRP4)->format('Y-m-d');
            //                 $statistical->comment_rp4 = $request->RP4comment;
            //                 $statistical->p10_rp4 = $request->p10_RP4;
            //                 $statistical->p90_rp4 = $request->p90_RP4;
            //             }
    
            //             if (in_array('5', $availableArray)) {
            //                 $statistical->rp5 = $request->RP5;
            //                 $statistical->date_rp5 = Carbon::createFromFormat('d/m/Y', $request->dateRP5)->format('Y-m-d');
            //                 $statistical->comment_rp5 = $request->RP5comment;
            //                 $statistical->p10_rp5 = $request->p10_RP5;
            //                 $statistical->p90_rp5 = $request->p90_RP5;
            //             }
            //         }
    
            //         if ($request->idAvailable) {
            //             $availableArray = $request->idAvailable;
    
            //             if (in_array('1', $availableArray)) {
            //                 $statistical->id1 = $request->ID1;
            //                 $statistical->date_id1 = Carbon::createFromFormat('d/m/Y', $request->dateID1)->format('Y-m-d');
            //                 $statistical->comment_id1 = $request->ID1comment;
            //                 $statistical->p10_id1 = $request->p10_ID1;
            //                 $statistical->p90_id1 = $request->p90_ID1;
            //             }
    
            //             if (in_array('2', $availableArray)) {
            //                 $statistical->id2 = $request->ID2;
            //                 $statistical->date_id2 = Carbon::createFromFormat('d/m/Y', $request->dateID2)->format('Y-m-d');
            //                 $statistical->comment_id2 = $request->ID2comment;
            //                 $statistical->p10_id2 = $request->p10_ID2;
            //                 $statistical->p90_id2 = $request->p90_ID2;
            //             }
    
            //             if (in_array('3', $availableArray)) {
            //                 $statistical->id3 = $request->ID3;
            //                 $statistical->date_id3 = Carbon::createFromFormat('d/m/Y', $request->dateID3)->format('Y-m-d');
            //                 $statistical->comment_id3 = $request->ID3comment;
            //                 $statistical->p10_id3 = $request->p10_ID3;
            //                 $statistical->p90_id3 = $request->p90_ID3;
            //             }
    
            //             if (in_array('4', $availableArray)) {
            //                 $statistical->id4 = $request->ID4;
            //                 $statistical->date_id4 = Carbon::createFromFormat('d/m/Y', $request->dateID4)->format('Y-m-d');
            //                 $statistical->comment_id4 = $request->ID4comment;
            //                 $statistical->p10_id4 = $request->p10_ID4;
            //                 $statistical->p90_id4 = $request->p90_ID4;
            //             }
            //         }
    
            //         if ($request->gdAvailable) {
            //             $availableArray = $request->gdAvailable;
    
            //             if (in_array('1', $availableArray)) {
            //                 $statistical->gd1 = $request->GD1;
            //                 $statistical->date_gd1 = Carbon::createFromFormat('d/m/Y', $request->dateGD1)->format('Y-m-d');
            //                 $statistical->comment_gd1 = $request->GD1comment;
            //                 $statistical->p10_gd1 = $request->p10_GD1;
            //                 $statistical->p90_gd1 = $request->p90_GD1;
            //             }
    
            //             if (in_array('2', $availableArray)) {
            //                 $statistical->gd2 = $request->GD2;
            //                 $statistical->date_gd2 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
            //                 $statistical->comment_gd2 = $request->GD2comment;
            //                 $statistical->p10_gd2 = $request->p10_GD2;
            //                 $statistical->p90_gd2 = $request->p90_GD2;
            //             }
    
            //             if (in_array('3', $availableArray)) {
            //                 $statistical->gd3 = $request->GD3;
            //                 $statistical->date_gd3 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
            //                 $statistical->comment_gd3 = $request->GD3comment;
            //                 $statistical->p10_gd3 = $request->p10_GD3;
            //                 $statistical->p90_gd3 = $request->p90_GD3;
            //             }
    
            //             if (in_array('4', $availableArray)) {
            //                 $statistical->gd4 = $request->GD4;
            //                 $statistical->date_gd4 = Carbon::createFromFormat('d/m/Y', $request->dateGD4)->format('Y-m-d');
            //                 $statistical->comment_gd4 = $request->GD4comment;
            //                 $statistical->p10_gd4 = $request->p10_GD4;
            //                 $statistical->p90_gd4 = $request->p90_GD4;
            //             }
            //         }
            //         $statistical->kd = $request->kd;
            //         $statistical->msAvailable = $input['msAvailable'];
            //         $statistical->fbAvailable = $input['fbAvailable'];
            //         $statistical->osAvailable = $input['osAvailable'];
            //         $statistical->rpAvailable = $input['rpAvailable'];
            //         $statistical->idAvailable = $input['idAvailable'];
            //         $statistical->gdAvailable = $input['gdAvailable'];
            //         $statistical->status_wr = $request->only_s == "save" ? 1 : 0;
            //         $statistical->escenario_id = $request->id_scenary;
            //         $statistical->save();
    
            //         $scenario->completo = $request->only_s == "save" ? 0 : 1;
            //         $scenario->estado = 1;
            //         $scenario->save();
    
            //         /* ingresa los datos en la tabla subparameters_weight */
            //         $inputs = $request->all();
            //         $statistical->subparameters->update($inputs);
    
            //         return redirect()->route('statistical.show', $statistical->id);
            //     }
            // }
        } else {
            return view('loginfirst');
        }
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */
    public function duplicate($id, $duplicateFrom)
    {
        $_SESSION['scenary_id_dup'] = $id;
        return $this->edit($duplicateFrom);
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

    public function graficoStatistical($statistical, $elements)
    {

        $results = [];

        // dd($statistical);

        for ($i=1; $i <= count($elements) ; $i++) { 
            $ms1_aux = explode(',', $statistical->ms1)[$i-1];
            $ms2_aux = explode(',', $statistical->ms2)[$i-1];
            $ms3_aux = explode(',', $statistical->ms3)[$i-1];
            $ms4_aux = explode(',', $statistical->ms4)[$i-1];
            $ms5_aux = explode(',', $statistical->ms5)[$i-1];
            $fb1_aux = explode(',', $statistical->fb1)[$i-1];
            $fb2_aux = explode(',', $statistical->fb2)[$i-1];
            $fb3_aux = explode(',', $statistical->fb3)[$i-1];
            $fb4_aux = explode(',', $statistical->fb4)[$i-1];
            $fb5_aux = explode(',', $statistical->fb5)[$i-1];
            $os1_aux = explode(',', $statistical->os1)[$i-1];
            $os2_aux = explode(',', $statistical->os2)[$i-1];
            $os3_aux = explode(',', $statistical->os3)[$i-1];
            $os4_aux = explode(',', $statistical->os4)[$i-1];
            $os5_aux = explode(',', $statistical->os5)[$i-1];
            $rp1_aux = explode(',', $statistical->rp1)[$i-1];
            $rp2_aux = explode(',', $statistical->rp2)[$i-1];
            $rp3_aux = explode(',', $statistical->rp3)[$i-1];
            $rp4_aux = explode(',', $statistical->rp4)[$i-1];
            $rp5_aux = explode(',', $statistical->rp5)[$i-1];
            $id1_aux = explode(',', $statistical->id1)[$i-1];
            $id2_aux = explode(',', $statistical->id2)[$i-1];
            $id3_aux = explode(',', $statistical->id3)[$i-1];
            $id4_aux = explode(',', $statistical->id4)[$i-1];
            $gd1_aux = explode(',', $statistical->gd1)[$i-1];
            $gd2_aux = explode(',', $statistical->gd2)[$i-1];
            $gd3_aux = explode(',', $statistical->gd3)[$i-1];
            $gd4_aux = explode(',', $statistical->gd4)[$i-1];

            /* se busca que parametros estan activos o desactivados */
            $ms1 = 0;
            $ms2 = 0;
            $ms3 = 0;
            $ms4 = 0;
            $ms5 = 0;
            if ($statistical->checkbox_ms1 == 1) {
                $ms1 = $this->normalizacion($ms1_aux, $statistical->p10_ms1, $statistical->p90_ms1, $statistical->subparameters->ms_scale_index_caco3);
            }

            if ($statistical->checkbox_ms2 == 2) {
                $ms2 = $this->normalizacion($ms2_aux, $statistical->p10_ms2, $statistical->p90_ms2, $statistical->subparameters->ms_scale_index_baso4);
            }

            if ($statistical->checkbox_ms3 == 3) {
                $ms3 = $this->normalizacion($ms3_aux, $statistical->p10_ms3, $statistical->p90_ms3, $statistical->subparameters->ms_scale_index_iron_scales);
            }

            if ($statistical->checkbox_ms4 == 4) {
                $ms4 = $this->normalizacion($ms4_aux, $statistical->p10_ms4, $statistical->p90_ms4, $statistical->subparameters->ms_calcium_concentration);
            }

            if ($statistical->checkbox_ms5 == 5) {
                $ms5 = $this->normalizacion($ms5_aux, $statistical->p10_ms5, $statistical->p90_ms5, $statistical->subparameters->ms_barium_concentration);
            }
            $msp = ($ms1 + $ms2 + $ms3 + $ms4 + $ms5);

            /* ---------------------------------------------------------------------------- */

            $fb1 = 0;
            $fb2 = 0;
            $fb3 = 0;
            $fb4 = 0;
            $fb5 = 0;
            if ($statistical->checkbox_fb1 == 1) {
                $fb1 = $this->normalizacion($fb1_aux, $statistical->p10_fb1, $statistical->p90_fb1, $statistical->subparameters->fb_aluminum_concentration);
            }

            if ($statistical->checkbox_fb2 == 2) {
                $fb2 = $this->normalizacion($fb2_aux, $statistical->p10_fb2, $statistical->p90_fb2, $statistical->subparameters->fb_silicon_concentration);
            }

            if ($statistical->checkbox_fb3 == 3) {
                $fb3 = $this->normalizacion($fb3_aux, $statistical->p10_fb3, $statistical->p90_fb3, $statistical->subparameters->fb_critical_radius_factor);
            }

            if ($statistical->checkbox_fb4 == 4) {
                $fb4 = $this->normalizacion($fb4_aux, $statistical->p10_fb4, $statistical->p90_fb4, $statistical->subparameters->fb_mineralogic_factor);
            }

            if ($statistical->checkbox_fb5 == 5) {
                $fb5 = $this->normalizacion($fb5_aux, $statistical->p10_fb5, $statistical->p90_fb5, $statistical->subparameters->fb_crushed_proppant_factor);
            }
            $fbp = ($fb1 + $fb2 + $fb3 + $fb4 + $fb5);

            /* ---------------------------------------------------------------------------- */

            $os1 = 0;
            $os2 = 0;
            $os3 = 0;
            $os4 = 0;
            $os5 = 0;
            if ($statistical->checkbox_os1 == 1) {
                $os1 = $this->normalizacion($os1_aux, $statistical->p10_os1, $statistical->p90_os1, $statistical->subparameters->os_cll_factor);
            }

            if ($statistical->checkbox_os2 == 2) {
                $os2 = $this->normalizacion($os2_aux, $statistical->p10_os2, $statistical->p90_os2, $statistical->subparameters->os_volume_of_hcl);
            }

            if ($statistical->checkbox_os3 == 3) {
                $os3 = $this->normalizacion($os3_aux, $statistical->p10_os3, $statistical->p90_os3, $statistical->subparameters->os_compositional_factor);
            }

            if ($statistical->checkbox_os4 == 4) {
                $os4 = $this->normalizacion($os4_aux, $statistical->p10_os4, $statistical->p90_os4, $statistical->subparameters->os_pressure_factor);
            }

            if ($statistical->checkbox_os5 == 5) {
                $os5 = $this->normalizacion($os5_aux, $statistical->p10_os5, $statistical->p90_os5, $statistical->subparameters->os_high_impact_factor);
            }
            $osp = ($os1 + $os2 + $os3 + $os4 + $os5);

            /* ---------------------------------------------------------------------------- */

            $rp1 = 0;
            $rp2 = 0;
            $rp3 = 0;
            $rp4 = 0;
            $rp5 = 0;
            if ($statistical->checkbox_rp1 == 1) {
                $rp1 = $this->normalizacion($rp1_aux, $statistical->p10_rp1, $statistical->p90_rp1, $statistical->subparameters->rp_days_below_saturation_pressure);
            }

            if ($statistical->checkbox_rp2 == 2) {
                $rp2 = $this->normalizacion($rp2_aux, $statistical->p10_rp2, $statistical->p90_rp2, $statistical->subparameters->rp_delta_pressure_saturation);
            }

            if ($statistical->checkbox_rp3 == 3) {
                $rp3 = $this->normalizacion($rp3_aux, $statistical->p10_rp3, $statistical->p90_rp3, $statistical->subparameters->rp_water_intrusion);
            }

            if ($statistical->checkbox_rp4 == 4) {
                $rp4 = $this->normalizacion($rp4_aux, $statistical->p10_rp4, $statistical->p90_rp4, $statistical->subparameters->rp_high_impact_factor);
            }

            if ($statistical->checkbox_rp5 == 5) {
                $rp5 = $this->normalizacion($rp5_aux, $statistical->p10_rp5, $statistical->p90_rp5, $statistical->subparameters->rp_velocity_estimated);
            }
            $rpp = ($rp1 + $rp2 + $rp3 + $rp4 + $rp5);

            /* ---------------------------------------------------------------------------- */

            $id1 = 0;
            $id2 = 0;
            $id3 = 0;
            $id4 = 0;
            if ($statistical->checkbox_id1 == 1) {
                $id1 = $this->normalizacion($id1_aux, $statistical->p10_id1, $statistical->p90_id1, $statistical->subparameters->id_gross_pay);
            }

            if ($statistical->checkbox_id2 == 2) {
                $id2 = $this->normalizacion($id2_aux, $statistical->p10_id2, $statistical->p90_id2, $statistical->subparameters->id_polymer_damage_factor);
            }

            if ($statistical->checkbox_id3 == 3) {
                $id3 = $this->normalizacion($id3_aux, $statistical->p10_id3, $statistical->p90_id3, $statistical->subparameters->id_total_volume_water);
            }

            if ($statistical->checkbox_id4 == 4) {
                $id4 = $this->normalizacion($id4_aux, $statistical->p10_id4, $statistical->p90_id4, $statistical->subparameters->id_mud_damage_factor);
            }
            $idp = ($id1 + $id2 + $id3 + $id4);

            /* ---------------------------------------------------------------------------- */

            $gd1 = 0;
            $gd2 = 0;
            $gd3 = 0;
            $gd4 = 0;
            if ($statistical->checkbox_gd1 == 1) {
                $gd1 = $this->normalizacion($gd1, $statistical->p10_gd1, $statistical->p90_gd1, $statistical->subparameters->gd_fraction_netpay);
            }

            if ($statistical->checkbox_gd2 == 2) {
                $gd2 = $this->normalizacion($gd2, $statistical->p10_gd2, $statistical->p90_gd2, $statistical->subparameters->gd_drawdown);
            }

            if ($statistical->checkbox_gd3 == 3) {
                $gd3 = $this->normalizacion($gd3, $statistical->p10_gd3, $statistical->p90_gd3, $statistical->subparameters->gd_ratio_kh_fracture);
            }

            if ($statistical->checkbox_gd4 == 4) {
                $gd4 = $this->normalizacion($gd4, $statistical->p10_gd4, $statistical->p90_gd4, $statistical->subparameters->gd_geomechanical_damage_fraction);
            }
            $gdp = ($gd1 + $gd2 + $gd3 + $gd4);

            $totalStatistical = $msp + $fbp + $osp + $rpp + $idp + $gdp;

            array_push($results, [($msp / $totalStatistical) * 100, ($fbp / $totalStatistical) * 100, ($osp / $totalStatistical) * 100, ($rpp / $totalStatistical) * 100, ($idp / $totalStatistical) * 100, ($gdp / $totalStatistical) * 100]);
        }

        dd(collect($results), "ñpñ");
    }

    public function normalizacion($valor, $p10, $p90, $peso)
    {
        if ( abs(2 * $valor) > abs( $p90 ) ) {
            $sum =  ( 2 * $peso );
        } else {
            $sum = (($valor - $p10) / ($p90 - $p10)) * $peso;
        }

        # Si sum da negativo, que lo haga igual a cero
        if ($sum < 0) {
            $sum = 0;
        }

        return $sum;
    }

    public function buscarArray($n, $available)
    {
        return array_search($n, $available, false);
    }
}
