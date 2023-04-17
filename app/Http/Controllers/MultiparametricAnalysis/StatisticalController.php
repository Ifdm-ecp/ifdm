<?php

namespace App\Http\Controllers\MultiparametricAnalysis;

use Validator;
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
        
        if ($statistical->status_wr == 1) {
            $escenario_id = $statistical->escenario_id;
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
            $elements = $formations_names;
    
            $button_wr = (bool) $statistical->status_wr;
            
            $datos_aux = $this->graficoStatistical($statistical, $formationsWithoutSpaces);
            
            $datos = [];
            foreach ($datos_aux as $key => $dato) {
                array_push($datos, [$elements[$key], $dato]);
            }
    
            // ELIMINAR PESTAÑAS DESACTIVADAS
            $generalCheckboxes = [];
            for ($i=0; $i < 6 ; $i++) { 
                array_push($generalCheckboxes, intval(explode(',', $statistical->generalAvailable)[$i]));
            }
            foreach ($datos as $key => $dato) {
                $datos[$key][1] = array_filter( $dato[1], 'strlen' );
            }
            
            $tableHeader_aux = ['Mineral Scales [%]', 'Fine Blockage [%]', 'Organic Scales [%]', 'Relative Permeability [%]', 'Induced Damage [%]', 'Geomechanical Damage [%]'];
            $tableHeader = ['Formation'];
            foreach ($tableHeader_aux as $key => $header) {
                if ($datos_aux[0][$key] !== null) {
                    array_push($tableHeader, $tableHeader_aux[$key]);
                }
            }
            $tableData = [];
            foreach ($formations as $keyFormation => $formation) {
                $tableRow = [];
                array_push($tableRow, $formation);
                foreach ($datos_aux[$keyFormation] as $keyDatos => $dato) {
                    if ($dato !== null) {
                        array_push($tableRow, $dato);
                    }
                }
                array_push($tableData, $tableRow);
            }
            // dd($statistical, $datos, $tableHeader, $tableData);

            return view('multiparametricAnalysis.statistical.show', compact(['statistical', 'datos', 'generalCheckboxes', 'tableHeader', 'tableData']));
        } else {

            return view('multiparametricAnalysis.statistical.show', compact(['statistical']));
        }

        
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

        $titles1 = ['MS1', 'MS2', 'MS3', 'MS4', 'MS5', 'FB1', 'FB2', 'FB3', 'FB4', 'FB5', 'OS1', 'OS2', 'OS3', 'OS4', 'OS5', 'RP1', 'RP2', 'RP3', 'RP4', 'RP5', 'ID1', 'ID2', 'ID3', 'ID4', 'GD1', 'GD2', 'GD3', 'GD4'];
        $titles2 = ['ScaleIndexOfCaCO3', 'ScaleIndexOfBaSO4', 'ScaleIndexOfIronScales', 'BackflowCa', 'BackflowBa', 'AlonProducedWater', 'Sionproducedwater', 'CriticalRadiusderivedfrommaximumcriticalvelocityVc', 'MineralogyFactor', 'MassofcrushedproppantinsideHydraulicFractures', 'CIIFactorColloidalInstabilityIndex', 'VolumeofHClpumpedintotheformation', 'CumulativeGasProduced', 'NumberOfDaysBelowSaturationPressure', 'DeBoerCriteria', 'NumberOfDaysBelowSaturationPressure2', 'Differencebetweencurrentreservoirpressureandsaturationpressure', 'CumulativeWaterProduced', 'PoreSizeDiameterApproximationByKatzAndThompsonCorrelation', 'Velocityparameterestimatedastheinverseofthecriticalradius', 'GrossPay', 'TotalpolymerpumpedduringHydraulicFracturing', 'Totalvolumeofwaterbasedfluidspumpedintothewell', 'MudLosses', 'FractionofNetPayExihibitingNaturalFractures', 'reservoirpressureminusBHFP', 'RatioofKH', 'GeomechanicalDamageExpressedAsFractionOfBasePermeabilityAtBHFP'];

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
        $pesos_query = subparameters_weight::where('multiparametric_id', $statistical->id)->get();
        $pesos = [];
        if ( count($pesos_query) == 0 ) {
            $pesos = null;
        } else {
            $pesos_query = $pesos_query[0];
            array_push($pesos, $pesos_query->ms_scale_index_caco3);
            array_push($pesos, $pesos_query->ms_scale_index_baso4);
            array_push($pesos, $pesos_query->ms_scale_index_iron_scales);
            array_push($pesos, $pesos_query->ms_calcium_concentration);
            array_push($pesos, $pesos_query->ms_barium_concentration);
            array_push($pesos, $pesos_query->fb_aluminum_concentration);
            array_push($pesos, $pesos_query->fb_silicon_concentration);
            array_push($pesos, $pesos_query->fb_critical_radius_factor);
            array_push($pesos, $pesos_query->fb_mineralogic_factor);
            array_push($pesos, $pesos_query->fb_crushed_proppant_factor);
            array_push($pesos, $pesos_query->os_cll_factor);
            array_push($pesos, $pesos_query->os_volume_of_hcl);
            array_push($pesos, $pesos_query->os_compositional_factor);
            array_push($pesos, $pesos_query->os_pressure_factor);
            array_push($pesos, $pesos_query->os_high_impact_factor);
            array_push($pesos, $pesos_query->rp_days_below_saturation_pressure);
            array_push($pesos, $pesos_query->rp_delta_pressure_saturation);
            array_push($pesos, $pesos_query->rp_water_intrusion);
            array_push($pesos, $pesos_query->rp_high_impact_factor);
            array_push($pesos, $pesos_query->rp_velocity_estimated);
            array_push($pesos, $pesos_query->id_gross_pay);
            array_push($pesos, $pesos_query->id_polymer_damage_factor);
            array_push($pesos, $pesos_query->id_total_volume_water);
            array_push($pesos, $pesos_query->id_mud_damage_factor);
            array_push($pesos, $pesos_query->gd_fraction_netpay);
            array_push($pesos, $pesos_query->gd_drawdown);
            array_push($pesos, $pesos_query->gd_ratio_kh_fracture);
            array_push($pesos, $pesos_query->gd_geomechanical_damage_fraction);
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
        
        $statistical->comment_ms1 = array_map('strval', explode(',', $statistical->comment_ms1));
        $statistical->comment_ms2 = array_map('strval', explode(',', $statistical->comment_ms2));
        $statistical->comment_ms3 = array_map('strval', explode(',', $statistical->comment_ms3));
        $statistical->comment_ms4 = array_map('strval', explode(',', $statistical->comment_ms4));
        $statistical->comment_ms5 = array_map('strval', explode(',', $statistical->comment_ms5));
        $statistical->comment_fb1 = array_map('strval', explode(',', $statistical->comment_fb1));
        $statistical->comment_fb2 = array_map('strval', explode(',', $statistical->comment_fb2));
        $statistical->comment_fb3 = array_map('strval', explode(',', $statistical->comment_fb3));
        $statistical->comment_fb4 = array_map('strval', explode(',', $statistical->comment_fb4));
        $statistical->comment_fb5 = array_map('strval', explode(',', $statistical->comment_fb5));
        $statistical->comment_os1 = array_map('strval', explode(',', $statistical->comment_os1));
        $statistical->comment_os2 = array_map('strval', explode(',', $statistical->comment_os2));
        $statistical->comment_os3 = array_map('strval', explode(',', $statistical->comment_os3));
        $statistical->comment_os4 = array_map('strval', explode(',', $statistical->comment_os4));
        $statistical->comment_os5 = array_map('strval', explode(',', $statistical->comment_os5));
        $statistical->comment_rp1 = array_map('strval', explode(',', $statistical->comment_rp1));
        $statistical->comment_rp2 = array_map('strval', explode(',', $statistical->comment_rp2));
        $statistical->comment_rp3 = array_map('strval', explode(',', $statistical->comment_rp3));
        $statistical->comment_rp4 = array_map('strval', explode(',', $statistical->comment_rp4));
        $statistical->comment_rp5 = array_map('strval', explode(',', $statistical->comment_rp5));
        $statistical->comment_id1 = array_map('strval', explode(',', $statistical->comment_id1));
        $statistical->comment_id2 = array_map('strval', explode(',', $statistical->comment_id2));
        $statistical->comment_id3 = array_map('strval', explode(',', $statistical->comment_id3));
        $statistical->comment_id4 = array_map('strval', explode(',', $statistical->comment_id4));
        $statistical->comment_gd1 = array_map('strval', explode(',', $statistical->comment_gd1));
        $statistical->comment_gd2 = array_map('strval', explode(',', $statistical->comment_gd2));
        $statistical->comment_gd3 = array_map('strval', explode(',', $statistical->comment_gd3));
        $statistical->comment_gd4 = array_map('strval', explode(',', $statistical->comment_gd4));

        $checkboxes = [];
        foreach ($statistical->msAvailable as $key => $value) { array_push($checkboxes, explode(',', $value)[0]); }
        foreach ($statistical->fbAvailable as $key => $value) { array_push($checkboxes, explode(',', $value)[0]); }
        foreach ($statistical->osAvailable as $key => $value) { array_push($checkboxes, explode(',', $value)[0]); }
        foreach ($statistical->rpAvailable as $key => $value) { array_push($checkboxes, explode(',', $value)[0]); }
        foreach ($statistical->idAvailable as $key => $value) { array_push($checkboxes, explode(',', $value)[0]); }
        foreach ($statistical->gdAvailable as $key => $value) { array_push($checkboxes, explode(',', $value)[0]); }

        $generalCheckboxes = [];
        foreach (explode(',', $statistical->generalAvailable) as $key => $value) { array_push($generalCheckboxes, explode(',', $value)[0]); }
        
        $indexes = ['ms1', 'ms2', 'ms3', 'ms4', 'ms5', 'fb1', 'fb2', 'fb3', 'fb4', 'fb5', 'os1', 'os2', 'os3', 'os4', 'os5', 'rp1', 'rp2', 'rp3', 'rp4', 'rp5', 'id1', 'id2', 'id3', 'id4', 'gd1', 'gd2', 'gd3', 'gd4'];
        $valores = [];
        foreach ($indexes as $keyI => $index) {
            
            $statistical->{$index} = array_map('strval', explode(',', $statistical->{$index}));
            foreach ($statistical->{$index} as $key => $value) {
                $valor = [];
                if ($value !== '' && $value !== '[]' && $value !== null) {
                    array_push($valor, $value);
                    array_push($valor, $statistical->{'date_'.$index}[$key]);
                    array_push($valor, $statistical->{'comment_'.$index}[$key]);
                    array_push($valor, strtoupper($index));
                    array_push($valor, $formationsWithoutSpaces[$key]);
                    array_push($valores, $valor);
                }
            }
        }
        // dd($valores);

        //se trae todas las cuencas existentes
        $cuencas = cuenca::orderBy('nombre')->get();
        $complete = false;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;


        
        
       
        // dd($formations);

        // dd($statistical);
        return view('multiparametricAnalysis.statistical.edit', compact(['statistical', 'cuencas', 'complete', 'pozoId', 'duplicateFrom', 'formations', 'mediciones', 'pesos', 'formationsWithoutSpaces', 'checkboxes', 'generalCheckboxes', 'valores', 'titles1', 'titles2']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MultiparametricStatisticalRequest $request, $id)
    {
        if (\Auth::check()) {

            //VALIDATE
            $validator = Validator::make($request->all(), [
                'value_MS1LA_PAZ_CG' => ['required'],
                'value_MS2MUGROSA' => ['required'],
            ]);
            
            if ($validator->fails()) {
                $scenario = escenario::find($request->id_scenary);
                $statistical = Statistical::find($id);
                $escenario_id = $statistical->escenario_id;

                return redirect()
                    ->route('statistical.edit', $statistical->escenario_id)
                    ->withErrors($validator)
                    ->withInput();

            }else{

                // Encontrar formaciones y nombres de campos de entrada
                $scenario = escenario::find($request->id_scenary);
                $statistical = Statistical::find($id);
                $escenario_id = $statistical->escenario_id;
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
                $elements = $formations_names;

                $titles = [['MS', 5], ['FB', 5], ['OS', 5], ['RP', 5], ['ID', 4], ['GD', 4]];
                
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
                
                // dd($formationsWithoutSpaces);

                // ORGANIZAR DATOS EN $statistical
                $generalCheckboxArray = [];
                foreach ($titles as $keyTitle => $title) {
                    $numberOfParameters = $title[1];
                    $title = $title[0];                    

                    if ( $request->{'checkbox_general_'.$title}  === 'on') {
                        array_push($generalCheckboxArray, 1);
                        for ($i=0; $i < $numberOfParameters; $i++) { 
                        
                            if ( $request->{$title.($i+1).'_checkbox'} === 'on' ) {

                                $valuesArray = [];
                                $datesArray = [];
                                $commentsArray = [];
                                foreach ($formationsWithoutSpaces as $keyFormation => $formation) {
                                    $name = $title.($i+1).$formation;
                                    array_push($valuesArray, $request->{'value_'.$name});
                                    array_push($datesArray, $request->{'date_'.$name});
                                    array_push($commentsArray, $request->{'comment_'.$name});   
                                }
                                $statistical->{strtolower($title).($i+1)} = implode(',', $valuesArray);
                                $statistical->{'date_'.strtolower($title).($i+1)} = implode(',', $datesArray);
                                $statistical->{'comment_'.strtolower($title).($i+1)} = implode(',', $commentsArray);
                                $statistical->{'p10_'.strtolower($title).($i+1)} = ($request->{'p10_'.$title.($i+1)} === "" ) ? null : $request->{'p10_'.$title.($i+1)};
                                $statistical->{'p90_'.strtolower($title).($i+1)} = ($request->{'p90_'.$title.($i+1)} === "" ) ? null : $request->{'p90_'.$title.($i+1)};

                            } else {

                                $defaultArray = [];
                                foreach ($formationsWithoutSpaces as $key => $formation) {
                                    array_push($defaultArray, null);
                                }
                                $statistical->{strtolower($title).($i+1)} = implode(',', $defaultArray);
                                $statistical->{'date_'.strtolower($title).($i+1)} = implode(',', $defaultArray);
                                $statistical->{'comment_'.strtolower($title).($i+1)} = implode(',', $defaultArray);
                                $statistical->{'p10_'.strtolower($title).($i+1)} = null;
                                $statistical->{'p90_'.strtolower($title).($i+1)} = null;

                            } 
                        }
                    } else {
                        array_push($generalCheckboxArray, 0);
                        for ($i=0; $i < $numberOfParameters; $i++) { 
                            $defaultArray = [];
                            foreach ($formationsWithoutSpaces as $key => $formation) {
                                array_push($defaultArray, null);
                            }
                            $statistical->{strtolower($title).($i+1)} = implode(',', $defaultArray);
                            $statistical->{'date_'.strtolower($title).($i+1)} = implode(',', $defaultArray);
                            $statistical->{'comment_'.strtolower($title).($i+1)} = implode(',', $defaultArray);
                            $statistical->{'p10_'.strtolower($title).($i+1)} = null;
                            $statistical->{'p90_'.strtolower($title).($i+1)} = null;
                        }
                    }
                    $statistical->generalAvailable = implode(',', $generalCheckboxArray);
                }

                foreach ($titles as $key => $title) {
                    $checkboxArray = [];
                    $numberOfParameters = $title[1];
                    $title = $title[0]; 
                    for ($i=0; $i < $numberOfParameters; $i++) { 
                        if ( $request->{$title.($i+1).'_checkbox'} === 'on' ) {
                            array_push($checkboxArray, 1);
                        } else {
                            array_push($checkboxArray, 0);
                        }
                    }
                    $statistical->{strtolower($title).'Available'} = implode(',', $checkboxArray);
                }
                
                $weights->ms_scale_index_caco3 = $request->weight_MS1;
                $weights->ms_scale_index_baso4 = $request->weight_MS2;
                $weights->ms_scale_index_iron_scales = $request->weight_MS3;
                $weights->ms_calcium_concentration = $request->weight_MS4;
                $weights->ms_barium_concentration = $request->weight_MS5;
                $weights->fb_aluminum_concentration = $request->weight_FB1;
                $weights->fb_silicon_concentration = $request->weight_FB2;
                $weights->fb_critical_radius_factor = $request->weight_FB3;
                $weights->fb_mineralogic_factor = $request->weight_FB4;
                $weights->fb_crushed_proppant_factor = $request->weight_FB5;
                $weights->os_cll_factor = $request->weight_OS1;
                $weights->os_volume_of_hcl = $request->weight_OS2;
                $weights->os_compositional_factor = $request->weight_OS3;
                $weights->os_pressure_factor = $request->weight_OS4;
                $weights->os_high_impact_factor = $request->weight_OS5;
                $weights->rp_days_below_saturation_pressure = $request->weight_RP1;
                $weights->rp_delta_pressure_saturation = $request->weight_RP2;
                $weights->rp_water_intrusion = $request->weight_RP3;
                $weights->rp_velocity_estimated = $request->weight_RP4;
                $weights->rp_high_impact_factor = $request->weight_RP5;
                $weights->id_gross_pay = $request->weight_ID1;
                $weights->id_polymer_damage_factor = $request->weight_ID2;
                $weights->id_total_volume_water = $request->weight_ID3;
                $weights->id_mud_damage_factor = $request->weight_ID4;
                $weights->gd_fraction_netpay = $request->weight_GD1;
                $weights->gd_drawdown = $request->weight_GD2;
                $weights->gd_ratio_kh_fracture = $request->weight_GD3;
                $weights->gd_geomechanical_damage_fraction = $request->weight_GD4;

                $statistical->kd = $request->kd;
                

                if ($request->only_s == "run") {
                    $statistical->status_wr = 1;
                    $statistical->save();
                    
                    $weights->save();

                    $scenario->completo = 1;
                    $scenario->estado = 1;
                    $scenario->save();

                    return redirect()->route('statistical.show', $statistical->id);
                } elseif ($request->only_s == "save") {
                    $statistical->status_wr = 0;
                    $statistical->save();
                    
                    $weights->save();

                    $scenario->completo = 0;
                    $scenario->estado = 1;
                    $scenario->save();
                    return redirect()->route('statistical.show', $statistical->id);

                }
            }

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

    public function graficoStatistical($statistical, $formations)
    {

        $results = [];
        $titles = [['ms', 5], ['fb', 5], ['os', 5], ['rp', 5], ['id', 4], ['gd', 4]];
        $weights = ['ms_scale_index_caco3', 'ms_scale_index_baso4', 'ms_scale_index_iron_scales', 'ms_calcium_concentration', 'ms_barium_concentration',
            'fb_aluminum_concentration', 'fb_silicon_concentration', 'fb_critical_radius_factor', 'fb_mineralogic_factor', 'fb_crushed_proppant_factor',
            'os_cll_factor', 'os_volume_of_hcl', 'os_compositional_factor', 'os_pressure_factor', 'os_high_impact_factor',
            'rp_days_below_saturation_pressure', 'rp_delta_pressure_saturation', 'rp_water_intrusion', 'rp_high_impact_factor', 'rp_velocity_estimated',
            'id_gross_pay', 'id_polymer_damage_factor', 'id_total_volume_water', 'id_mud_damage_factor',
            'gd_fraction_netpay', 'gd_drawdown', 'gd_ratio_kh_fracture', 'gd_geomechanical_damage_fraction'];

        $generalCheckboxes = [];
        $checkboxes = [];
        $p10 = [];
        $p90 = [];
        foreach ($titles as $keyTitles => $title) {
            $numberOfParameters = $title[1];
            $title = $title[0];
            for ($i=0; $i < $numberOfParameters; $i++) { 
                $name = $title.($i+1);
                array_push($checkboxes, intval(explode(',', $statistical->{$title.'Available'})[$i]));
                array_push($p10, ($statistical->{'p10_'.$name} === null) ? null : floatval($statistical->{'p10_'.$name}));
                array_push($p90, ($statistical->{'p90_'.$name} === null) ? null : floatval($statistical->{'p90_'.$name}));
            }
            array_push($generalCheckboxes, intval(explode(',', $statistical->generalAvailable)[$keyTitles]));
        }

        foreach ($formations as $keyFormations => $formation) {

            $values = [];
            // $dates = 
            // $comments = 
            foreach ($titles as $keyTitles => $title) {
                $numberOfParameters = $title[1];
                $title = $title[0];
                for ($i=0; $i < $numberOfParameters; $i++) { 
                    $name = $title.($i+1);
                    array_push($values, floatval(explode(',', $statistical->{$name})[$keyFormations]));
                }  
            }
            // dd($formations, $values);
            // EMPEZAMOS CON LOS CÁLCULOS
            $sums = []; 
            $msp = $fbp = $osp = $rpp = $idp = $gdp = [];
            
            $j = 0;
            foreach ($titles as $keyTitles => $title) {
                // dd($titles);
                if ($generalCheckboxes[$keyTitles] === 1) {
                    $numberOfParameters = $title[1];
                    $title = $title[0];
                    $sum = 0;
                    for ($i=0; $i < $numberOfParameters; $i++) { 
                        $name = $title.($i+1);
                        if ($checkboxes[$j] == 1) {
                            // dd($values[$j], $p10[$j], $p90[$j], $statistical->subparameters->{$weights[$j]});
                                $sum = $sum + $this->normalizacion($values[$j], $p10[$j], $p90[$j], $statistical->subparameters->{$weights[$j]});
                                // dd($statistical->subparameters);
                        }
                        $j++;
                    }  
                    array_push($sums, $sum);
                } else { 
                    array_push($sums, null);
                }                
            }

            $totalStatistical = array_sum($sums);
            
            foreach ($titles as $keyTitles => $title) {
                if ($sums[$keyTitles] !== null) {
                    // dd($sums, $sums[$keyTitles], $keyTitles, $formations, $statistical);   
                
                    $sums[$keyTitles] = $sums[$keyTitles] * 100 / $totalStatistical;
                }
            }
            
            array_push($results, $sums);
        }
        // dd($results);
        return $results;

        // // for ($i=1; $i <= count($elements) ; $i++) { 
        // //     $ms1_aux = explode(',', $statistical->ms1)[$i-1];
        // //     $ms2_aux = explode(',', $statistical->ms2)[$i-1];
        // //     $ms3_aux = explode(',', $statistical->ms3)[$i-1];
        // //     $ms4_aux = explode(',', $statistical->ms4)[$i-1];
        // //     $ms5_aux = explode(',', $statistical->ms5)[$i-1];
        // //     $fb1_aux = explode(',', $statistical->fb1)[$i-1];
        // //     $fb2_aux = explode(',', $statistical->fb2)[$i-1];
        // //     $fb3_aux = explode(',', $statistical->fb3)[$i-1];
        // //     $fb4_aux = explode(',', $statistical->fb4)[$i-1];
        // //     $fb5_aux = explode(',', $statistical->fb5)[$i-1];
        // //     $os1_aux = explode(',', $statistical->os1)[$i-1];
        // //     $os2_aux = explode(',', $statistical->os2)[$i-1];
        // //     $os3_aux = explode(',', $statistical->os3)[$i-1];
        // //     $os4_aux = explode(',', $statistical->os4)[$i-1];
        // //     $os5_aux = explode(',', $statistical->os5)[$i-1];
        // //     $rp1_aux = explode(',', $statistical->rp1)[$i-1];
        // //     $rp2_aux = explode(',', $statistical->rp2)[$i-1];
        // //     $rp3_aux = explode(',', $statistical->rp3)[$i-1];
        // //     $rp4_aux = explode(',', $statistical->rp4)[$i-1];
        // //     $rp5_aux = explode(',', $statistical->rp5)[$i-1];
        // //     $id1_aux = explode(',', $statistical->id1)[$i-1];
        // //     $id2_aux = explode(',', $statistical->id2)[$i-1];
        // //     $id3_aux = explode(',', $statistical->id3)[$i-1];
        // //     $id4_aux = explode(',', $statistical->id4)[$i-1];
        // //     $gd1_aux = explode(',', $statistical->gd1)[$i-1];
        // //     $gd2_aux = explode(',', $statistical->gd2)[$i-1];
        // //     $gd3_aux = explode(',', $statistical->gd3)[$i-1];
        // //     $gd4_aux = explode(',', $statistical->gd4)[$i-1];

        //     /* se busca que parametros estan activos o desactivados */
        //     $ms1 = $ms2 = $ms3 = $ms4 = $ms5 = 0;
        //     if ($statistical->checkbox_ms1 == 1) {
        //         $ms1 = $this->normalizacion($values[$i], $p10_ms1[$i], $p90_ms1[$i], $weights[$i]);
        //     }

        //     if ($statistical->checkbox_ms2 == 2) {
        //         $ms2 = $this->normalizacion($ms2_aux, $statistical->p10_ms2, $statistical->p90_ms2, $statistical->subparameters->ms_scale_index_baso4);
        //     }

        //     if ($statistical->checkbox_ms3 == 3) {
        //         $ms3 = $this->normalizacion($ms3_aux, $statistical->p10_ms3, $statistical->p90_ms3, $statistical->subparameters->ms_scale_index_iron_scales);
        //     }

        //     if ($statistical->checkbox_ms4 == 4) {
        //         $ms4 = $this->normalizacion($ms4_aux, $statistical->p10_ms4, $statistical->p90_ms4, $statistical->subparameters->ms_calcium_concentration);
        //     }

        //     if ($statistical->checkbox_ms5 == 5) {
        //         $ms5 = $this->normalizacion($ms5_aux, $statistical->p10_ms5, $statistical->p90_ms5, $statistical->subparameters->ms_barium_concentration);
        //     }
        //     $msp = ($ms1 + $ms2 + $ms3 + $ms4 + $ms5);

        //     /* ---------------------------------------------------------------------------- */
            
        //     $fb1 = 0;
        //     $fb2 = 0;
        //     $fb3 = 0;
        //     $fb4 = 0;
        //     $fb5 = 0;
        //     if ($statistical->checkbox_fb1 == 1) {
        //         $fb1 = $this->normalizacion($fb1_aux, $statistical->p10_fb1, $statistical->p90_fb1, $statistical->subparameters->fb_aluminum_concentration);
        //     }

        //     if ($statistical->checkbox_fb2 == 2) {
        //         $fb2 = $this->normalizacion($fb2_aux, $statistical->p10_fb2, $statistical->p90_fb2, $statistical->subparameters->fb_silicon_concentration);
        //     }

        //     if ($statistical->checkbox_fb3 == 3) {
        //         $fb3 = $this->normalizacion($fb3_aux, $statistical->p10_fb3, $statistical->p90_fb3, $statistical->subparameters->fb_critical_radius_factor);
        //     }

        //     if ($statistical->checkbox_fb4 == 4) {
        //         $fb4 = $this->normalizacion($fb4_aux, $statistical->p10_fb4, $statistical->p90_fb4, $statistical->subparameters->fb_mineralogic_factor);
        //     }

        //     if ($statistical->checkbox_fb5 == 5) {
        //         $fb5 = $this->normalizacion($fb5_aux, $statistical->p10_fb5, $statistical->p90_fb5, $statistical->subparameters->fb_crushed_proppant_factor);
        //     }
        //     $fbp = ($fb1 + $fb2 + $fb3 + $fb4 + $fb5);

        //     /* ---------------------------------------------------------------------------- */

        //     $os1 = 0;
        //     $os2 = 0;
        //     $os3 = 0;
        //     $os4 = 0;
        //     $os5 = 0;
        //     if ($statistical->checkbox_os1 == 1) {
        //         $os1 = $this->normalizacion($os1_aux, $statistical->p10_os1, $statistical->p90_os1, $statistical->subparameters->os_cll_factor);
        //     }

        //     if ($statistical->checkbox_os2 == 2) {
        //         $os2 = $this->normalizacion($os2_aux, $statistical->p10_os2, $statistical->p90_os2, $statistical->subparameters->os_volume_of_hcl);
        //     }

        //     if ($statistical->checkbox_os3 == 3) {
        //         $os3 = $this->normalizacion($os3_aux, $statistical->p10_os3, $statistical->p90_os3, $statistical->subparameters->os_compositional_factor);
        //     }

        //     if ($statistical->checkbox_os4 == 4) {
        //         $os4 = $this->normalizacion($os4_aux, $statistical->p10_os4, $statistical->p90_os4, $statistical->subparameters->os_pressure_factor);
        //     }

        //     if ($statistical->checkbox_os5 == 5) {
        //         $os5 = $this->normalizacion($os5_aux, $statistical->p10_os5, $statistical->p90_os5, $statistical->subparameters->os_high_impact_factor);
        //     }
        //     $osp = ($os1 + $os2 + $os3 + $os4 + $os5);

        //     /* ---------------------------------------------------------------------------- */
            
        //     $rp1 = 0;
        //     $rp2 = 0;
        //     $rp3 = 0;
        //     $rp4 = 0;
        //     $rp5 = 0;
        //     if ($statistical->checkbox_rp1 == 1) {
        //         $rp1 = $this->normalizacion($rp1_aux, $statistical->p10_rp1, $statistical->p90_rp1, $statistical->subparameters->rp_days_below_saturation_pressure);
        //     }

        //     if ($statistical->checkbox_rp2 == 2) {
        //         $rp2 = $this->normalizacion($rp2_aux, $statistical->p10_rp2, $statistical->p90_rp2, $statistical->subparameters->rp_delta_pressure_saturation);
        //     }

        //     if ($statistical->checkbox_rp3 == 3) {
        //         $rp3 = $this->normalizacion($rp3_aux, $statistical->p10_rp3, $statistical->p90_rp3, $statistical->subparameters->rp_water_intrusion);
        //     }

        //     if ($statistical->checkbox_rp4 == 4) {
        //         $rp4 = $this->normalizacion($rp4_aux, $statistical->p10_rp4, $statistical->p90_rp4, $statistical->subparameters->rp_high_impact_factor);
        //     }

        //     if ($statistical->checkbox_rp5 == 5) {
        //         $rp5 = $this->normalizacion($rp5_aux, $statistical->p10_rp5, $statistical->p90_rp5, $statistical->subparameters->rp_velocity_estimated);
        //     }
        //     $rpp = ($rp1 + $rp2 + $rp3 + $rp4 + $rp5);

        //     /* ---------------------------------------------------------------------------- */

        //     $id1 = 0;
        //     $id2 = 0;
        //     $id3 = 0;
        //     $id4 = 0;
        //     if ($statistical->checkbox_id1 == 1) {
        //         $id1 = $this->normalizacion($id1_aux, $statistical->p10_id1, $statistical->p90_id1, $statistical->subparameters->id_gross_pay);
        //     }

        //     if ($statistical->checkbox_id2 == 2) {
        //         $id2 = $this->normalizacion($id2_aux, $statistical->p10_id2, $statistical->p90_id2, $statistical->subparameters->id_polymer_damage_factor);
        //     }

        //     if ($statistical->checkbox_id3 == 3) {
        //         $id3 = $this->normalizacion($id3_aux, $statistical->p10_id3, $statistical->p90_id3, $statistical->subparameters->id_total_volume_water);
        //     }

        //     if ($statistical->checkbox_id4 == 4) {
        //         $id4 = $this->normalizacion($id4_aux, $statistical->p10_id4, $statistical->p90_id4, $statistical->subparameters->id_mud_damage_factor);
        //     }
        //     $idp = ($id1 + $id2 + $id3 + $id4);

        //     /* ---------------------------------------------------------------------------- */
            
        //     $gd1 = 0;
        //     $gd2 = 0;
        //     $gd3 = 0;
        //     $gd4 = 0;
        //     if ($statistical->checkbox_gd1 == 1) {
        //         $gd1 = $this->normalizacion($gd1, $statistical->p10_gd1, $statistical->p90_gd1, $statistical->subparameters->gd_fraction_netpay);
        //     }

        //     if ($statistical->checkbox_gd2 == 2) {
        //         $gd2 = $this->normalizacion($gd2, $statistical->p10_gd2, $statistical->p90_gd2, $statistical->subparameters->gd_drawdown);
        //     }

        //     if ($statistical->checkbox_gd3 == 3) {
        //         $gd3 = $this->normalizacion($gd3, $statistical->p10_gd3, $statistical->p90_gd3, $statistical->subparameters->gd_ratio_kh_fracture);
        //     }

        //     if ($statistical->checkbox_gd4 == 4) {
        //         $gd4 = $this->normalizacion($gd4, $statistical->p10_gd4, $statistical->p90_gd4, $statistical->subparameters->gd_geomechanical_damage_fraction);
        //     }
        //     $gdp = ($gd1 + $gd2 + $gd3 + $gd4);

        //     $totalStatistical = $msp + $fbp + $osp + $rpp + $idp + $gdp;

        //     array_push($results, [($msp / $totalStatistical) * 100, ($fbp / $totalStatistical) * 100, ($osp / $totalStatistical) * 100, ($rpp / $totalStatistical) * 100, ($idp / $totalStatistical) * 100, ($gdp / $totalStatistical) * 100]);
        
        // // dd(collect($results), 'epa');
    }

    public function normalizacion($valor, $p10, $p90, $peso)
    {
        if ( abs($p10 - $p90) !== 0) {
            if ($valor > (abs( $p90 ) + $p90)) {
                $sum = $peso * ( 2 * $p90 - $p10 ) / ( $p90 - $p10);
            } else {
                if ($valor < $p10) {
                    // $sum = $peso * ( 0.0001 ) / ( 0.0001 );
                    $sum = 0;
                } else {
                    // $sum = $peso * ( $valor - $p10 ) / ( 0.0001 );
                    $sum = $peso * ( $valor - $p10 ) / ( $p90 - $p10 );
                }
            }
        } else {
            $sum = 0;
        }
        
        // if ( abs(2 * $valor) > abs( $p90 ) ) {
        //     $sum =  ( 2 * $peso );
        // } else {
        //     $sum = (($valor - $p10) / ($p90 - $p10)) * $peso;
        // }

        # Si sum da negativo, que lo haga igual a cero
        if ($sum <= 0) {
            $sum = 0;
        }

        return $sum;
    }
}
