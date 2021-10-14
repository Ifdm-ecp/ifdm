<?php

namespace App\Http\Controllers\MultiparametricAnalysis;

use App\cuenca;
use App\escenario;
use App\Http\Controllers\Controller;
use App\Http\Requests\MultiparametricStatisticalCreateRequest;
use App\Http\Requests\MultiparametricStatisticalRequest;
use App\Models\MultiparametricAnalysis\Statistical;
use App\subparameters_weight;
use App\Traits\StatisticalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

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
        return redirect()->route('statistical.edit', $statistical->id);
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

        /* se trae los arrays autoriazados por bloques */
        $statistical->msAvailable = array_map('intval', explode(',', $statistical->msAvailable));
        $statistical->fbAvailable = array_map('intval', explode(',', $statistical->fbAvailable));
        $statistical->osAvailable = array_map('intval', explode(',', $statistical->osAvailable));
        $statistical->rpAvailable = array_map('intval', explode(',', $statistical->rpAvailable));
        $statistical->idAvailable = array_map('intval', explode(',', $statistical->idAvailable));
        $statistical->gdAvailable = array_map('intval', explode(',', $statistical->gdAvailable));

        $button_wr = (bool) $statistical->status_wr;

        if (!$button_wr) {
            $datos = $this->graficoStatistical($statistical);
        } else {
            $datos = json_encode([]);
        }

        return view('multiparametricAnalysis.statistical.show', compact(['statistical', 'datos']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* se trae todos los datos de la tabla statistical con el id = $id */
        $statistical = Statistical::find($id);

        if (!$statistical) {
            $statistical = Statistical::where('escenario_id', $id)->first();
            if (!$statistical) {
                abort('404');
            }
        }

        $pozoId = escenario::find($statistical->escenario_id)->pozo_id;

        /* se convierten  los datos autoriazados por bloques de string a arrays */
        $statistical->msAvailable = array_map('intval', explode(',', $statistical->msAvailable));
        $statistical->fbAvailable = array_map('intval', explode(',', $statistical->fbAvailable));
        $statistical->osAvailable = array_map('intval', explode(',', $statistical->osAvailable));
        $statistical->rpAvailable = array_map('intval', explode(',', $statistical->rpAvailable));
        $statistical->idAvailable = array_map('intval', explode(',', $statistical->idAvailable));
        $statistical->gdAvailable = array_map('intval', explode(',', $statistical->gdAvailable));

        $statistical->date_ms1 = $statistical->date_ms1 !== '0000-00-00' ? Carbon::parse($statistical->date_ms1)->format('d/m/Y') : '';
        $statistical->date_ms2 = $statistical->date_ms2 !== '0000-00-00' ? Carbon::parse($statistical->date_ms2)->format('d/m/Y') : '';
        $statistical->date_ms3 = $statistical->date_ms3 !== '0000-00-00' ? Carbon::parse($statistical->date_ms3)->format('d/m/Y') : '';
        $statistical->date_ms4 = $statistical->date_ms4 !== '0000-00-00' ? Carbon::parse($statistical->date_ms4)->format('d/m/Y') : '';
        $statistical->date_ms5 = $statistical->date_ms5 !== '0000-00-00' ? Carbon::parse($statistical->date_ms5)->format('d/m/Y') : '';
        $statistical->date_fb1 = $statistical->date_fb1 !== '0000-00-00' ? Carbon::parse($statistical->date_fb1)->format('d/m/Y') : '';
        $statistical->date_fb2 = $statistical->date_fb2 !== '0000-00-00' ? Carbon::parse($statistical->date_fb2)->format('d/m/Y') : '';
        $statistical->date_fb3 = $statistical->date_fb3 !== '0000-00-00' ? Carbon::parse($statistical->date_fb3)->format('d/m/Y') : '';
        $statistical->date_fb4 = $statistical->date_fb4 !== '0000-00-00' ? Carbon::parse($statistical->date_fb4)->format('d/m/Y') : '';
        $statistical->date_fb5 = $statistical->date_fb5 !== '0000-00-00' ? Carbon::parse($statistical->date_fb5)->format('d/m/Y') : '';
        $statistical->date_os1 = $statistical->date_os1 !== '0000-00-00' ? Carbon::parse($statistical->date_os1)->format('d/m/Y') : '';
        $statistical->date_os2 = $statistical->date_os2 !== '0000-00-00' ? Carbon::parse($statistical->date_os2)->format('d/m/Y') : '';
        $statistical->date_os3 = $statistical->date_os3 !== '0000-00-00' ? Carbon::parse($statistical->date_os3)->format('d/m/Y') : '';
        $statistical->date_os4 = $statistical->date_os4 !== '0000-00-00' ? Carbon::parse($statistical->date_os4)->format('d/m/Y') : '';
        $statistical->date_os5 = $statistical->date_os5 !== '0000-00-00' ? Carbon::parse($statistical->date_os5)->format('d/m/Y') : '';
        $statistical->date_rp1 = $statistical->date_rp1 !== '0000-00-00' ? Carbon::parse($statistical->date_rp1)->format('d/m/Y') : '';
        $statistical->date_rp2 = $statistical->date_rp2 !== '0000-00-00' ? Carbon::parse($statistical->date_rp2)->format('d/m/Y') : '';
        $statistical->date_rp3 = $statistical->date_rp3 !== '0000-00-00' ? Carbon::parse($statistical->date_rp3)->format('d/m/Y') : '';
        $statistical->date_rp4 = $statistical->date_rp4 !== '0000-00-00' ? Carbon::parse($statistical->date_rp4)->format('d/m/Y') : '';
        $statistical->date_rp5 = $statistical->date_rp5 !== '0000-00-00' ? Carbon::parse($statistical->date_rp5)->format('d/m/Y') : '';
        $statistical->date_id1 = $statistical->date_id1 !== '0000-00-00' ? Carbon::parse($statistical->date_id1)->format('d/m/Y') : '';
        $statistical->date_id2 = $statistical->date_id2 !== '0000-00-00' ? Carbon::parse($statistical->date_id2)->format('d/m/Y') : '';
        $statistical->date_id3 = $statistical->date_id3 !== '0000-00-00' ? Carbon::parse($statistical->date_id3)->format('d/m/Y') : '';
        $statistical->date_id4 = $statistical->date_id4 !== '0000-00-00' ? Carbon::parse($statistical->date_id4)->format('d/m/Y') : '';
        $statistical->date_gd1 = $statistical->date_gd1 !== '0000-00-00' ? Carbon::parse($statistical->date_gd1)->format('d/m/Y') : '';
        $statistical->date_gd2 = $statistical->date_gd2 !== '0000-00-00' ? Carbon::parse($statistical->date_gd2)->format('d/m/Y') : '';
        $statistical->date_gd3 = $statistical->date_gd3 !== '0000-00-00' ? Carbon::parse($statistical->date_gd3)->format('d/m/Y') : '';
        $statistical->date_gd4 = $statistical->date_gd4 !== '0000-00-00' ? Carbon::parse($statistical->date_gd4)->format('d/m/Y') : '';

        //se trae todas las cuencas existentes
        $cuencas = cuenca::orderBy('nombre')->get();
        $complete = false;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        //dd(Session::get('GD4'));
        return view('multiparametricAnalysis.statistical.edit', compact(['statistical', 'cuencas', 'complete', 'pozoId', 'duplicateFrom']));
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
            if (isset($request->duplicate)) {
                
                unset($_SESSION['scenary_id_dup']);
                // unset($request->duplicate);
                // unset($request->id);
                // $input = $request->all();

                $scenario = escenario::find($request->duplicate);

                //se conviertelos arrays en cadenas
                if ($request->msAvailable) {
                    $input['msAvailable'] = implode(",", $request->msAvailable);
                } else {
                    $input['msAvailable'] = null;
                }

                if ($request->fbAvailable) {
                    $input['fbAvailable'] = implode(",", $request->fbAvailable);
                } else {
                    $input['fbAvailable'] = null;
                }

                if ($request->osAvailable) {
                    $input['osAvailable'] = implode(",", $request->osAvailable);
                } else {
                    $input['osAvailable'] = null;
                }

                if ($request->rpAvailable) {
                    $input['rpAvailable'] = implode(",", $request->rpAvailable);
                } else {
                    $input['rpAvailable'] = null;
                }

                if ($request->idAvailable) {
                    $input['idAvailable'] = implode(",", $request->idAvailable);
                } else {
                    $input['idAvailable'] = null;
                }

                if ($request->gdAvailable) {
                    $input['gdAvailable'] = implode(",", $request->gdAvailable);
                } else {
                    $input['gdAvailable'] = null;
                }

                $statistical = new Statistical;
                $statistical->escenario_id = $request->duplicate;
                
                if ($request->msAvailable) {
                    $availableArray = $request->msAvailable;

                    if (in_array('1', $availableArray)) {
                        $statistical->ms1 = $request->MS1;
                        $statistical->date_ms1 = Carbon::createFromFormat('d/m/Y', $request->dateMS1)->format('Y-m-d');
                        $statistical->comment_ms1 = $request->MS1comment;
                        $statistical->p10_ms1 = $request->p10_MS1;
                        $statistical->p90_ms1 = $request->p90_MS1;
                    }

                    if (in_array('2', $availableArray)) {
                        $statistical->ms2 = $request->MS2;
                        $statistical->date_ms2 = Carbon::createFromFormat('d/m/Y', $request->dateMS2)->format('Y-m-d');
                        $statistical->comment_ms2 = $request->MS2comment;
                        $statistical->p10_ms2 = $request->p10_MS2;
                        $statistical->p90_ms2 = $request->p90_MS2;
                    }

                    if (in_array('3', $availableArray)) {
                        $statistical->ms3 = $request->MS3;
                        $statistical->date_ms3 = Carbon::createFromFormat('d/m/Y', $request->dateMS3)->format('Y-m-d');
                        $statistical->comment_ms3 = $request->MS3comment;
                        $statistical->p10_ms3 = $request->p10_MS3;
                        $statistical->p90_ms3 = $request->p90_MS3;
                    }

                    if (in_array('4', $availableArray)) {
                        $statistical->ms4 = $request->MS4;
                        $statistical->date_ms4 = Carbon::createFromFormat('d/m/Y', $request->dateMS4)->format('Y-m-d');
                        $statistical->comment_ms4 = $request->MS4comment;
                        $statistical->p10_ms4 = $request->p10_MS4;
                        $statistical->p90_ms4 = $request->p90_MS4;
                    }

                    if (in_array('5', $availableArray)) {
                        $statistical->ms5 = $request->MS5;
                        $statistical->date_ms5 = Carbon::createFromFormat('d/m/Y', $request->dateMS5)->format('Y-m-d');
                        $statistical->comment_ms5 = $request->MS5comment;
                        $statistical->p10_ms5 = $request->p10_MS5;
                        $statistical->p90_ms5 = $request->p90_MS5;
                    }
                }

                if ($request->fbAvailable) {
                    $availableArray = $request->fbAvailable;

                    if (in_array('1', $availableArray)) {
                        $statistical->fb1 = $request->FB1;
                        $statistical->date_fb1 = Carbon::createFromFormat('d/m/Y', $request->dateFB1)->format('Y-m-d');
                        $statistical->comment_fb1 = $request->FB1comment;
                        $statistical->p10_fb1 = $request->p10_FB1;
                        $statistical->p90_fb1 = $request->p90_FB1;
                    }

                    if (in_array('2', $availableArray)) {
                        $statistical->fb2 = $request->FB2;
                        $statistical->date_fb2 = Carbon::createFromFormat('d/m/Y', $request->dateFB2)->format('Y-m-d');
                        $statistical->comment_fb2 = $request->FB2comment;
                        $statistical->p10_fb2 = $request->p10_FB2;
                        $statistical->p90_fb2 = $request->p90_FB2;
                    }

                    if (in_array('3', $availableArray)) {
                        $statistical->fb3 = $request->FB3;
                        $statistical->date_fb3 = Carbon::createFromFormat('d/m/Y', $request->dateFB3)->format('Y-m-d');
                        $statistical->comment_fb3 = $request->FB3comment;
                        $statistical->p10_fb3 = $request->p10_FB3;
                        $statistical->p90_fb3 = $request->p90_FB3;
                    }

                    if (in_array('4', $availableArray)) {
                        $statistical->fb4 = $request->FB4;
                        $statistical->date_fb4 = Carbon::createFromFormat('d/m/Y', $request->dateFB4)->format('Y-m-d');
                        $statistical->comment_fb4 = $request->FB4comment;
                        $statistical->p10_fb4 = $request->p10_FB4;
                        $statistical->p90_fb4 = $request->p90_FB4;
                    }

                    if (in_array('5', $availableArray)) {
                        $statistical->fb5 = $request->FB5;
                        $statistical->date_fb5 = Carbon::createFromFormat('d/m/Y', $request->dateFB5)->format('Y-m-d');
                        $statistical->comment_fb5 = $request->FB5comment;
                        $statistical->p10_fb5 = $request->p10_FB5;
                        $statistical->p90_fb5 = $request->p90_FB5;
                    }
                }

                if ($request->osAvailable) {
                    $availableArray = $request->osAvailable;

                    if (in_array('1', $availableArray)) {
                        $statistical->os1 = $request->OS1;
                        $statistical->date_os1 = Carbon::createFromFormat('d/m/Y', $request->dateOS1)->format('Y-m-d');
                        $statistical->comment_os1 = $request->OS1comment;
                        $statistical->p10_os1 = $request->p10_OS1;
                        $statistical->p90_os1 = $request->p90_OS1;
                    }

                    if (in_array('2', $availableArray)) {
                        $statistical->os2 = $request->OS2;
                        $statistical->date_os2 = Carbon::createFromFormat('d/m/Y', $request->dateOS2)->format('Y-m-d');
                        $statistical->comment_os2 = $request->OS2comment;
                        $statistical->p10_os2 = $request->p10_OS2;
                        $statistical->p90_os2 = $request->p90_OS2;
                    }

                    if (in_array('3', $availableArray)) {
                        $statistical->os3 = $request->OS3;
                        $statistical->date_os3 = Carbon::createFromFormat('d/m/Y', $request->dateOS3)->format('Y-m-d');
                        $statistical->comment_os3 = $request->OS3comment;
                        $statistical->p10_os3 = $request->p10_OS3;
                        $statistical->p90_os3 = $request->p90_OS3;
                    }

                    if (in_array('4', $availableArray)) {
                        $statistical->os4 = $request->OS4;
                        $statistical->date_os4 = Carbon::createFromFormat('d/m/Y', $request->dateOS4)->format('Y-m-d');
                        $statistical->comment_os4 = $request->OS4comment;
                        $statistical->p10_os4 = $request->p10_OS4;
                        $statistical->p90_os4 = $request->p90_OS4;
                    }

                    if (in_array('5', $availableArray)) {
                        $statistical->os5 = $request->OS5;
                        $statistical->date_os5 = Carbon::createFromFormat('d/m/Y', $request->dateOS5)->format('Y-m-d');
                        $statistical->comment_os5 = $request->OS5comment;
                        $statistical->p10_os5 = $request->p10_OS5;
                        $statistical->p90_os5 = $request->p90_OS5;
                    }
                }

                if ($request->rpAvailable) {
                    $availableArray = $request->rpAvailable;

                    if (in_array('1', $availableArray)) {
                        $statistical->rp1 = $request->RP1;
                        $statistical->date_rp1 = Carbon::createFromFormat('d/m/Y', $request->dateRP1)->format('Y-m-d');
                        $statistical->comment_rp1 = $request->RP1comment;
                        $statistical->p10_rp1 = $request->p10_RP1;
                        $statistical->p90_rp1 = $request->p90_RP1;
                    }

                    if (in_array('2', $availableArray)) {
                        $statistical->rp2 = $request->RP2;
                        $statistical->date_rp2 = Carbon::createFromFormat('d/m/Y', $request->dateRP2)->format('Y-m-d');
                        $statistical->comment_rp2 = $request->RP2comment;
                        $statistical->p10_rp2 = $request->p10_RP2;
                        $statistical->p90_rp2 = $request->p90_RP2;
                    }

                    if (in_array('3', $availableArray)) {
                        $statistical->rp3 = $request->RP3;
                        $statistical->date_rp3 = Carbon::createFromFormat('d/m/Y', $request->dateRP3)->format('Y-m-d');
                        $statistical->comment_rp3 = $request->RP3comment;
                        $statistical->p10_rp3 = $request->p10_RP3;
                        $statistical->p90_rp3 = $request->p90_RP3;
                    }

                    if (in_array('4', $availableArray)) {
                        $statistical->rp4 = $request->RP4;
                        $statistical->date_rp4 = Carbon::createFromFormat('d/m/Y', $request->dateRP4)->format('Y-m-d');
                        $statistical->comment_rp4 = $request->RP4comment;
                        $statistical->p10_rp4 = $request->p10_RP4;
                        $statistical->p90_rp4 = $request->p90_RP4;
                    }

                    if (in_array('5', $availableArray)) {
                        $statistical->rp5 = $request->RP5;
                        $statistical->date_rp5 = Carbon::createFromFormat('d/m/Y', $request->dateRP5)->format('Y-m-d');
                        $statistical->comment_rp5 = $request->RP5comment;
                        $statistical->p10_rp5 = $request->p10_RP5;
                        $statistical->p90_rp5 = $request->p90_RP5;
                    }
                }

                if ($request->idAvailable) {
                    $availableArray = $request->idAvailable;

                    if (in_array('1', $availableArray)) {
                        $statistical->id1 = $request->ID1;
                        $statistical->date_id1 = Carbon::createFromFormat('d/m/Y', $request->dateID1)->format('Y-m-d');
                        $statistical->comment_id1 = $request->ID1comment;
                        $statistical->p10_id1 = $request->p10_ID1;
                        $statistical->p90_id1 = $request->p90_ID1;
                    }

                    if (in_array('2', $availableArray)) {
                        $statistical->id2 = $request->ID2;
                        $statistical->date_id2 = Carbon::createFromFormat('d/m/Y', $request->dateID2)->format('Y-m-d');
                        $statistical->comment_id2 = $request->ID2comment;
                        $statistical->p10_id2 = $request->p10_ID2;
                        $statistical->p90_id2 = $request->p90_ID2;
                    }

                    if (in_array('3', $availableArray)) {
                        $statistical->id3 = $request->ID3;
                        $statistical->date_id3 = Carbon::createFromFormat('d/m/Y', $request->dateID3)->format('Y-m-d');
                        $statistical->comment_id3 = $request->ID3comment;
                        $statistical->p10_id3 = $request->p10_ID3;
                        $statistical->p90_id3 = $request->p90_ID3;
                    }

                    if (in_array('4', $availableArray)) {
                        $statistical->id4 = $request->ID4;
                        $statistical->date_id4 = Carbon::createFromFormat('d/m/Y', $request->dateID4)->format('Y-m-d');
                        $statistical->comment_id4 = $request->ID4comment;
                        $statistical->p10_id4 = $request->p10_ID4;
                        $statistical->p90_id4 = $request->p90_ID4;
                    }
                }

                if ($request->gdAvailable) {
                    $availableArray = $request->gdAvailable;

                    if (in_array('1', $availableArray)) {
                        $statistical->gd1 = $request->GD1;
                        $statistical->date_gd1 = Carbon::createFromFormat('d/m/Y', $request->dateGD1)->format('Y-m-d');
                        $statistical->comment_gd1 = $request->GD1comment;
                        $statistical->p10_gd1 = $request->p10_GD1;
                        $statistical->p90_gd1 = $request->p90_GD1;
                    }

                    if (in_array('2', $availableArray)) {
                        $statistical->gd2 = $request->GD2;
                        $statistical->date_gd2 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
                        $statistical->comment_gd2 = $request->GD2comment;
                        $statistical->p10_gd2 = $request->p10_GD2;
                        $statistical->p90_gd2 = $request->p90_GD2;
                    }

                    if (in_array('3', $availableArray)) {
                        $statistical->gd3 = $request->GD3;
                        $statistical->date_gd3 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
                        $statistical->comment_gd3 = $request->GD3comment;
                        $statistical->p10_gd3 = $request->p10_GD3;
                        $statistical->p90_gd3 = $request->p90_GD3;
                    }

                    if (in_array('4', $availableArray)) {
                        $statistical->gd4 = $request->GD4;
                        $statistical->date_gd4 = Carbon::createFromFormat('d/m/Y', $request->dateGD4)->format('Y-m-d');
                        $statistical->comment_gd4 = $request->GD4comment;
                        $statistical->p10_gd4 = $request->p10_GD4;
                        $statistical->p90_gd4 = $request->p90_GD4;
                    }
                }
                $statistical->kd = $request->kd;
                $statistical->msAvailable = $input['msAvailable'];
                $statistical->fbAvailable = $input['fbAvailable'];
                $statistical->osAvailable = $input['osAvailable'];
                $statistical->rpAvailable = $input['rpAvailable'];
                $statistical->idAvailable = $input['idAvailable'];
                $statistical->gdAvailable = $input['gdAvailable'];
                $statistical->status_wr = $request->only_s == "save" ? 1 : 0;
                $statistical->escenario_id = $request->id_scenary;

                $statistical->save();

                $scenario->completo = $request->only_s == "save" ? 0 : 1;
                $scenario->estado = 1;
                $scenario->save();

                subparameters_weight::create(['multiparametric_id' => $statistical->id]);

                // $statistical = Statistical::find($statistical->id);

                dd($request, $request->all(), $statistical->subaparameters, $statistical->id);

                /* ingresa los datos en la tabla subparameters_weight */
                // $inputs = $request->all();
                // $statistical->subparameters->update($inputs);

                return redirect()->route('statistical.show', $statistical->id);







                // $input = $request->all();
                // dd($request->duplicate, $request->id, $input);

                // /* se modifica el array del campo field_statistical con implode */
                // if ($request->field_statistical) {
                //     $input['field_statistical'] = implode(",", $request->field_statistical);
                // }
                
                // /* se pasa la variable calculate al funcion edit */
                // Session::flash('calculate', $request->calculate);
                
                // /* se ingresa los datos de la tabla statistical */
                // $statistical = Statistical::create($input);
                // dd('lolaaaaaaaa');
                // /* se guarda el parametro en la tabla subparameters_weight */
                // subparameters_weight::create(['multiparametric_id' => $statistical->id]);
        
                // unset($_SESSION['scenary_id_dup']);

                // //se redirecciona a la vista edit de statistical
                // return redirect()->route('statistical.edit', $statistical->id);
            } else {
                if ($request->calculate == "true") {
                    //se modifica el array del campo field_statistical con implode
                    if ($request->field_statistical) {
                        $input['field_statistical'] = implode(",", $request->field_statistical);
                        $request->statistical = null;
                    } else {
                        $input['field_statistical'] = null;
                        $request->basin_statistical = null;
                    }
    
                    //se guardan solo los campos field_statistical y statistical en la bbdd;
                    $statistical = Statistical::find($id);
                    $statistical->escenario_id = $request->id_scenary;
                    $statistical->field_statistical = $input['field_statistical'];
                    $statistical->basin_statistical = $request->basin_statistical;
                    $statistical->statistical = $request->statistical;
                    $statistical->save();
    
                    /* se pasa la variable calculate al funcion edit */
                    Session::flash('calculate', $request->calculate);
    
                    return redirect()->route('statistical.edit', $statistical->id);
                } else {
                    // if(isset($request->duplicate)) {
                    //     $quit_id = 1;
                    // }
                    unset($_SESSION['scenary_id_dup']);
                    $scenario = escenario::find($request->id_scenary);
    
                    //se conviertelos arrays en cadenas
                    if ($request->msAvailable) {
                        $input['msAvailable'] = implode(",", $request->msAvailable);
                    } else {
                        $input['msAvailable'] = null;
                    }
    
                    if ($request->fbAvailable) {
                        $input['fbAvailable'] = implode(",", $request->fbAvailable);
                    } else {
                        $input['fbAvailable'] = null;
                    }
    
                    if ($request->osAvailable) {
                        $input['osAvailable'] = implode(",", $request->osAvailable);
                    } else {
                        $input['osAvailable'] = null;
                    }
    
                    if ($request->rpAvailable) {
                        $input['rpAvailable'] = implode(",", $request->rpAvailable);
                    } else {
                        $input['rpAvailable'] = null;
                    }
    
                    if ($request->idAvailable) {
                        $input['idAvailable'] = implode(",", $request->idAvailable);
                    } else {
                        $input['idAvailable'] = null;
                    }
    
                    if ($request->gdAvailable) {
                        $input['gdAvailable'] = implode(",", $request->gdAvailable);
                    } else {
                        $input['gdAvailable'] = null;
                    }
    
                    //se ingresa los datos de la tabla statistical
                    $statistical = Statistical::find($id);
                    
                    // if(isset($request->duplicate)) {               
                    //     unset($request->duplicate);
                    //     unset($statistical->id);
                    // }
                    
    
                    if ($request->msAvailable) {
                        $availableArray = $request->msAvailable;
    
                        if (in_array('1', $availableArray)) {
                            $statistical->ms1 = $request->MS1;
                            $statistical->date_ms1 = Carbon::createFromFormat('d/m/Y', $request->dateMS1)->format('Y-m-d');
                            $statistical->comment_ms1 = $request->MS1comment;
                            $statistical->p10_ms1 = $request->p10_MS1;
                            $statistical->p90_ms1 = $request->p90_MS1;
                        }
    
                        if (in_array('2', $availableArray)) {
                            $statistical->ms2 = $request->MS2;
                            $statistical->date_ms2 = Carbon::createFromFormat('d/m/Y', $request->dateMS2)->format('Y-m-d');
                            $statistical->comment_ms2 = $request->MS2comment;
                            $statistical->p10_ms2 = $request->p10_MS2;
                            $statistical->p90_ms2 = $request->p90_MS2;
                        }
    
                        if (in_array('3', $availableArray)) {
                            $statistical->ms3 = $request->MS3;
                            $statistical->date_ms3 = Carbon::createFromFormat('d/m/Y', $request->dateMS3)->format('Y-m-d');
                            $statistical->comment_ms3 = $request->MS3comment;
                            $statistical->p10_ms3 = $request->p10_MS3;
                            $statistical->p90_ms3 = $request->p90_MS3;
                        }
    
                        if (in_array('4', $availableArray)) {
                            $statistical->ms4 = $request->MS4;
                            $statistical->date_ms4 = Carbon::createFromFormat('d/m/Y', $request->dateMS4)->format('Y-m-d');
                            $statistical->comment_ms4 = $request->MS4comment;
                            $statistical->p10_ms4 = $request->p10_MS4;
                            $statistical->p90_ms4 = $request->p90_MS4;
                        }
    
                        if (in_array('5', $availableArray)) {
                            $statistical->ms5 = $request->MS5;
                            $statistical->date_ms5 = Carbon::createFromFormat('d/m/Y', $request->dateMS5)->format('Y-m-d');
                            $statistical->comment_ms5 = $request->MS5comment;
                            $statistical->p10_ms5 = $request->p10_MS5;
                            $statistical->p90_ms5 = $request->p90_MS5;
                        }
                    }
    
                    if ($request->fbAvailable) {
                        $availableArray = $request->fbAvailable;
    
                        if (in_array('1', $availableArray)) {
                            $statistical->fb1 = $request->FB1;
                            $statistical->date_fb1 = Carbon::createFromFormat('d/m/Y', $request->dateFB1)->format('Y-m-d');
                            $statistical->comment_fb1 = $request->FB1comment;
                            $statistical->p10_fb1 = $request->p10_FB1;
                            $statistical->p90_fb1 = $request->p90_FB1;
                        }
    
                        if (in_array('2', $availableArray)) {
                            $statistical->fb2 = $request->FB2;
                            $statistical->date_fb2 = Carbon::createFromFormat('d/m/Y', $request->dateFB2)->format('Y-m-d');
                            $statistical->comment_fb2 = $request->FB2comment;
                            $statistical->p10_fb2 = $request->p10_FB2;
                            $statistical->p90_fb2 = $request->p90_FB2;
                        }
    
                        if (in_array('3', $availableArray)) {
                            $statistical->fb3 = $request->FB3;
                            $statistical->date_fb3 = Carbon::createFromFormat('d/m/Y', $request->dateFB3)->format('Y-m-d');
                            $statistical->comment_fb3 = $request->FB3comment;
                            $statistical->p10_fb3 = $request->p10_FB3;
                            $statistical->p90_fb3 = $request->p90_FB3;
                        }
    
                        if (in_array('4', $availableArray)) {
                            $statistical->fb4 = $request->FB4;
                            $statistical->date_fb4 = Carbon::createFromFormat('d/m/Y', $request->dateFB4)->format('Y-m-d');
                            $statistical->comment_fb4 = $request->FB4comment;
                            $statistical->p10_fb4 = $request->p10_FB4;
                            $statistical->p90_fb4 = $request->p90_FB4;
                        }
    
                        if (in_array('5', $availableArray)) {
                            $statistical->fb5 = $request->FB5;
                            $statistical->date_fb5 = Carbon::createFromFormat('d/m/Y', $request->dateFB5)->format('Y-m-d');
                            $statistical->comment_fb5 = $request->FB5comment;
                            $statistical->p10_fb5 = $request->p10_FB5;
                            $statistical->p90_fb5 = $request->p90_FB5;
                        }
                    }
    
                    if ($request->osAvailable) {
                        $availableArray = $request->osAvailable;
    
                        if (in_array('1', $availableArray)) {
                            $statistical->os1 = $request->OS1;
                            $statistical->date_os1 = Carbon::createFromFormat('d/m/Y', $request->dateOS1)->format('Y-m-d');
                            $statistical->comment_os1 = $request->OS1comment;
                            $statistical->p10_os1 = $request->p10_OS1;
                            $statistical->p90_os1 = $request->p90_OS1;
                        }
    
                        if (in_array('2', $availableArray)) {
                            $statistical->os2 = $request->OS2;
                            $statistical->date_os2 = Carbon::createFromFormat('d/m/Y', $request->dateOS2)->format('Y-m-d');
                            $statistical->comment_os2 = $request->OS2comment;
                            $statistical->p10_os2 = $request->p10_OS2;
                            $statistical->p90_os2 = $request->p90_OS2;
                        }
    
                        if (in_array('3', $availableArray)) {
                            $statistical->os3 = $request->OS3;
                            $statistical->date_os3 = Carbon::createFromFormat('d/m/Y', $request->dateOS3)->format('Y-m-d');
                            $statistical->comment_os3 = $request->OS3comment;
                            $statistical->p10_os3 = $request->p10_OS3;
                            $statistical->p90_os3 = $request->p90_OS3;
                        }
    
                        if (in_array('4', $availableArray)) {
                            $statistical->os4 = $request->OS4;
                            $statistical->date_os4 = Carbon::createFromFormat('d/m/Y', $request->dateOS4)->format('Y-m-d');
                            $statistical->comment_os4 = $request->OS4comment;
                            $statistical->p10_os4 = $request->p10_OS4;
                            $statistical->p90_os4 = $request->p90_OS4;
                        }
    
                        if (in_array('5', $availableArray)) {
                            $statistical->os5 = $request->OS5;
                            $statistical->date_os5 = Carbon::createFromFormat('d/m/Y', $request->dateOS5)->format('Y-m-d');
                            $statistical->comment_os5 = $request->OS5comment;
                            $statistical->p10_os5 = $request->p10_OS5;
                            $statistical->p90_os5 = $request->p90_OS5;
                        }
                    }
    
                    if ($request->rpAvailable) {
                        $availableArray = $request->rpAvailable;
    
                        if (in_array('1', $availableArray)) {
                            $statistical->rp1 = $request->RP1;
                            $statistical->date_rp1 = Carbon::createFromFormat('d/m/Y', $request->dateRP1)->format('Y-m-d');
                            $statistical->comment_rp1 = $request->RP1comment;
                            $statistical->p10_rp1 = $request->p10_RP1;
                            $statistical->p90_rp1 = $request->p90_RP1;
                        }
    
                        if (in_array('2', $availableArray)) {
                            $statistical->rp2 = $request->RP2;
                            $statistical->date_rp2 = Carbon::createFromFormat('d/m/Y', $request->dateRP2)->format('Y-m-d');
                            $statistical->comment_rp2 = $request->RP2comment;
                            $statistical->p10_rp2 = $request->p10_RP2;
                            $statistical->p90_rp2 = $request->p90_RP2;
                        }
    
                        if (in_array('3', $availableArray)) {
                            $statistical->rp3 = $request->RP3;
                            $statistical->date_rp3 = Carbon::createFromFormat('d/m/Y', $request->dateRP3)->format('Y-m-d');
                            $statistical->comment_rp3 = $request->RP3comment;
                            $statistical->p10_rp3 = $request->p10_RP3;
                            $statistical->p90_rp3 = $request->p90_RP3;
                        }
    
                        if (in_array('4', $availableArray)) {
                            $statistical->rp4 = $request->RP4;
                            $statistical->date_rp4 = Carbon::createFromFormat('d/m/Y', $request->dateRP4)->format('Y-m-d');
                            $statistical->comment_rp4 = $request->RP4comment;
                            $statistical->p10_rp4 = $request->p10_RP4;
                            $statistical->p90_rp4 = $request->p90_RP4;
                        }
    
                        if (in_array('5', $availableArray)) {
                            $statistical->rp5 = $request->RP5;
                            $statistical->date_rp5 = Carbon::createFromFormat('d/m/Y', $request->dateRP5)->format('Y-m-d');
                            $statistical->comment_rp5 = $request->RP5comment;
                            $statistical->p10_rp5 = $request->p10_RP5;
                            $statistical->p90_rp5 = $request->p90_RP5;
                        }
                    }
    
                    if ($request->idAvailable) {
                        $availableArray = $request->idAvailable;
    
                        if (in_array('1', $availableArray)) {
                            $statistical->id1 = $request->ID1;
                            $statistical->date_id1 = Carbon::createFromFormat('d/m/Y', $request->dateID1)->format('Y-m-d');
                            $statistical->comment_id1 = $request->ID1comment;
                            $statistical->p10_id1 = $request->p10_ID1;
                            $statistical->p90_id1 = $request->p90_ID1;
                        }
    
                        if (in_array('2', $availableArray)) {
                            $statistical->id2 = $request->ID2;
                            $statistical->date_id2 = Carbon::createFromFormat('d/m/Y', $request->dateID2)->format('Y-m-d');
                            $statistical->comment_id2 = $request->ID2comment;
                            $statistical->p10_id2 = $request->p10_ID2;
                            $statistical->p90_id2 = $request->p90_ID2;
                        }
    
                        if (in_array('3', $availableArray)) {
                            $statistical->id3 = $request->ID3;
                            $statistical->date_id3 = Carbon::createFromFormat('d/m/Y', $request->dateID3)->format('Y-m-d');
                            $statistical->comment_id3 = $request->ID3comment;
                            $statistical->p10_id3 = $request->p10_ID3;
                            $statistical->p90_id3 = $request->p90_ID3;
                        }
    
                        if (in_array('4', $availableArray)) {
                            $statistical->id4 = $request->ID4;
                            $statistical->date_id4 = Carbon::createFromFormat('d/m/Y', $request->dateID4)->format('Y-m-d');
                            $statistical->comment_id4 = $request->ID4comment;
                            $statistical->p10_id4 = $request->p10_ID4;
                            $statistical->p90_id4 = $request->p90_ID4;
                        }
                    }
    
                    if ($request->gdAvailable) {
                        $availableArray = $request->gdAvailable;
    
                        if (in_array('1', $availableArray)) {
                            $statistical->gd1 = $request->GD1;
                            $statistical->date_gd1 = Carbon::createFromFormat('d/m/Y', $request->dateGD1)->format('Y-m-d');
                            $statistical->comment_gd1 = $request->GD1comment;
                            $statistical->p10_gd1 = $request->p10_GD1;
                            $statistical->p90_gd1 = $request->p90_GD1;
                        }
    
                        if (in_array('2', $availableArray)) {
                            $statistical->gd2 = $request->GD2;
                            $statistical->date_gd2 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
                            $statistical->comment_gd2 = $request->GD2comment;
                            $statistical->p10_gd2 = $request->p10_GD2;
                            $statistical->p90_gd2 = $request->p90_GD2;
                        }
    
                        if (in_array('3', $availableArray)) {
                            $statistical->gd3 = $request->GD3;
                            $statistical->date_gd3 = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
                            $statistical->comment_gd3 = $request->GD3comment;
                            $statistical->p10_gd3 = $request->p10_GD3;
                            $statistical->p90_gd3 = $request->p90_GD3;
                        }
    
                        if (in_array('4', $availableArray)) {
                            $statistical->gd4 = $request->GD4;
                            $statistical->date_gd4 = Carbon::createFromFormat('d/m/Y', $request->dateGD4)->format('Y-m-d');
                            $statistical->comment_gd4 = $request->GD4comment;
                            $statistical->p10_gd4 = $request->p10_GD4;
                            $statistical->p90_gd4 = $request->p90_GD4;
                        }
                    }
                    $statistical->kd = $request->kd;
                    $statistical->msAvailable = $input['msAvailable'];
                    $statistical->fbAvailable = $input['fbAvailable'];
                    $statistical->osAvailable = $input['osAvailable'];
                    $statistical->rpAvailable = $input['rpAvailable'];
                    $statistical->idAvailable = $input['idAvailable'];
                    $statistical->gdAvailable = $input['gdAvailable'];
                    $statistical->status_wr = $request->only_s == "save" ? 1 : 0;
                    $statistical->escenario_id = $request->id_scenary;
    
                    // if(isset($quit_id)) {
                    //     unset($statistical->id);
                    // }
    
                    $statistical->save();
    
                    $scenario->completo = $request->only_s == "save" ? 0 : 1;
                    $scenario->estado = 1;
                    $scenario->save();
    
                    
                    //dd($statistical,$scenario);
                    /* ingresa los datos en la tabla subparameters_weight */
                    $inputs = $request->all();
                    dd('ES LA GUITARRA DE LOLO',  $statistical->subparameters);
                    $statistical->subparameters->update($inputs);
    
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

    public function graficoStatistical($statistical)
    {
        /* se busca que parametros estan activos o desactivados */
        $ms1 = 0;
        $ms2 = 0;
        $ms3 = 0;
        $ms4 = 0;
        $ms5 = 0;
        if ($statistical->msAvailable[$this->buscarArray(1, $statistical->msAvailable)] == 1) {
            $ms1 = $this->normalizacion($statistical->ms1, $statistical->p10_ms1, $statistical->p90_ms1, $statistical->subparameters->ms_scale_index_caco3);
        }

        if ($statistical->msAvailable[$this->buscarArray(2, $statistical->msAvailable)] == 2) {
            $ms2 = $this->normalizacion($statistical->ms2, $statistical->p10_ms2, $statistical->p90_ms2, $statistical->subparameters->ms_scale_index_baso4);
        }

        if ($statistical->msAvailable[$this->buscarArray(3, $statistical->msAvailable)] == 3) {
            $ms3 = $this->normalizacion($statistical->ms3, $statistical->p10_ms3, $statistical->p90_ms3, $statistical->subparameters->ms_scale_index_iron_scales);
        }

        if ($statistical->msAvailable[$this->buscarArray(4, $statistical->msAvailable)] == 4) {
            $ms4 = $this->normalizacion($statistical->ms4, $statistical->p10_ms4, $statistical->p90_ms4, $statistical->subparameters->ms_calcium_concentration);
        }

        if ($statistical->msAvailable[$this->buscarArray(5, $statistical->msAvailable)] == 5) {
            $ms5 = $this->normalizacion($statistical->ms5, $statistical->p10_ms5, $statistical->p90_ms5, $statistical->subparameters->ms_barium_concentration);
        }
        $msp = ($ms1 + $ms2 + $ms3 + $ms4 + $ms5);

        /* ---------------------------------------------------------------------------- */

        $fb1 = 0;
        $fb2 = 0;
        $fb3 = 0;
        $fb4 = 0;
        $fb5 = 0;
        if ($statistical->fbAvailable[$this->buscarArray(1, $statistical->fbAvailable)] == 1) {
            $fb1 = $this->normalizacion($statistical->fb1, $statistical->p10_fb1, $statistical->p90_fb1, $statistical->subparameters->fb_aluminum_concentration);
        }

        if ($statistical->fbAvailable[$this->buscarArray(2, $statistical->fbAvailable)] == 2) {
            $fb2 = $this->normalizacion($statistical->fb2, $statistical->p10_fb2, $statistical->p90_fb2, $statistical->subparameters->fb_silicon_concentration);
        }

        if ($statistical->fbAvailable[$this->buscarArray(3, $statistical->fbAvailable)] == 3) {
            $fb3 = $this->normalizacion($statistical->fb3, $statistical->p10_fb3, $statistical->p90_fb3, $statistical->subparameters->fb_critical_radius_factor);
        }

        if ($statistical->fbAvailable[$this->buscarArray(4, $statistical->fbAvailable)] == 4) {
            $fb4 = $this->normalizacion($statistical->fb4, $statistical->p10_fb4, $statistical->p90_fb4, $statistical->subparameters->fb_mineralogic_factor);
        }

        if ($statistical->fbAvailable[$this->buscarArray(5, $statistical->fbAvailable)] == 5) {
            $fb5 = $this->normalizacion($statistical->fb5, $statistical->p10_fb5, $statistical->p90_fb5, $statistical->subparameters->fb_crushed_proppant_factor);
        }
        $fbp = ($fb1 + $fb2 + $fb3 + $fb4 + $fb5);

        /* ---------------------------------------------------------------------------- */

        $os1 = 0;
        $os2 = 0;
        $os3 = 0;
        $os4 = 0;
        $os5 = 0;
        if ($statistical->osAvailable[$this->buscarArray(1, $statistical->osAvailable)] == 1) {
            $os1 = $this->normalizacion($statistical->os1, $statistical->p10_os1, $statistical->p90_os1, $statistical->subparameters->os_cll_factor);
        }

        if ($statistical->osAvailable[$this->buscarArray(2, $statistical->osAvailable)] == 2) {
            $os2 = $this->normalizacion($statistical->os2, $statistical->p10_os2, $statistical->p90_os2, $statistical->subparameters->os_volume_of_hcl);
        }

        if ($statistical->osAvailable[$this->buscarArray(3, $statistical->osAvailable)] == 3) {
            $os3 = $this->normalizacion($statistical->os3, $statistical->p10_os3, $statistical->p90_os3, $statistical->subparameters->os_compositional_factor);
        }

        if ($statistical->osAvailable[$this->buscarArray(4, $statistical->osAvailable)] == 4) {
            $os4 = $this->normalizacion($statistical->os4, $statistical->p10_os4, $statistical->p90_os4, $statistical->subparameters->os_pressure_factor);
        }

        if ($statistical->osAvailable[$this->buscarArray(5, $statistical->osAvailable)] == 5) {
            $os5 = $this->normalizacion($statistical->os5, $statistical->p10_os5, $statistical->p90_os5, $statistical->subparameters->os_high_impact_factor);
        }
        $osp = ($os1 + $os2 + $os3 + $os4 + $os5);

        /* ---------------------------------------------------------------------------- */

        $rp1 = 0;
        $rp2 = 0;
        $rp3 = 0;
        $rp4 = 0;
        $rp5 = 0;
        if ($statistical->rpAvailable[$this->buscarArray(1, $statistical->rpAvailable)] == 1) {
            $rp1 = $this->normalizacion($statistical->rp1, $statistical->p10_rp1, $statistical->p90_rp1, $statistical->subparameters->rp_days_below_saturation_pressure);
        }

        if ($statistical->rpAvailable[$this->buscarArray(2, $statistical->rpAvailable)] == 2) {
            $rp2 = $this->normalizacion($statistical->rp2, $statistical->p10_rp2, $statistical->p90_rp2, $statistical->subparameters->rp_delta_pressure_saturation);
        }

        if ($statistical->rpAvailable[$this->buscarArray(3, $statistical->rpAvailable)] == 3) {
            $rp3 = $this->normalizacion($statistical->rp3, $statistical->p10_rp3, $statistical->p90_rp3, $statistical->subparameters->rp_water_intrusion);
        }

        if ($statistical->rpAvailable[$this->buscarArray(4, $statistical->rpAvailable)] == 4) {
            $rp4 = $this->normalizacion($statistical->rp4, $statistical->p10_rp4, $statistical->p90_rp4, $statistical->subparameters->rp_high_impact_factor);
        }

        if ($statistical->rpAvailable[$this->buscarArray(5, $statistical->rpAvailable)] == 5) {
            $rp5 = $this->normalizacion($statistical->rp5, $statistical->p10_rp5, $statistical->p90_rp5, $statistical->subparameters->rp_velocity_estimated);
        }
        $rpp = ($rp1 + $rp2 + $rp3 + $rp4 + $rp5);

        /* ---------------------------------------------------------------------------- */

        $id1 = 0;
        $id2 = 0;
        $id3 = 0;
        $id4 = 0;
        if ($statistical->idAvailable[$this->buscarArray(1, $statistical->idAvailable)] == 1) {
            $id1 = $this->normalizacion($statistical->id1, $statistical->p10_id1, $statistical->p90_id1, $statistical->subparameters->id_gross_pay);
        }

        if ($statistical->idAvailable[$this->buscarArray(2, $statistical->idAvailable)] == 2) {
            $id2 = $this->normalizacion($statistical->id2, $statistical->p10_id2, $statistical->p90_id2, $statistical->subparameters->id_polymer_damage_factor);
        }

        if ($statistical->idAvailable[$this->buscarArray(3, $statistical->idAvailable)] == 3) {
            $id3 = $this->normalizacion($statistical->id3, $statistical->p10_id3, $statistical->p90_id3, $statistical->subparameters->id_total_volume_water);
        }

        if ($statistical->idAvailable[$this->buscarArray(4, $statistical->idAvailable)] == 4) {
            $id4 = $this->normalizacion($statistical->id4, $statistical->p10_id4, $statistical->p90_id4, $statistical->subparameters->id_mud_damage_factor);
        }
        $idp = ($id1 + $id2 + $id3 + $id4);

        /* ---------------------------------------------------------------------------- */

        $gd1 = 0;
        $gd2 = 0;
        $gd3 = 0;
        $gd4 = 0;
        if ($statistical->gdAvailable[$this->buscarArray(1, $statistical->gdAvailable)] == 1) {
            $gd1 = $this->normalizacion($statistical->gd1, $statistical->p10_gd1, $statistical->p90_gd1, $statistical->subparameters->gd_fraction_netpay);
        }

        if ($statistical->gdAvailable[$this->buscarArray(2, $statistical->gdAvailable)] == 2) {
            $gd2 = $this->normalizacion($statistical->gd2, $statistical->p10_gd2, $statistical->p90_gd2, $statistical->subparameters->gd_drawdown);
        }

        if ($statistical->gdAvailable[$this->buscarArray(3, $statistical->gdAvailable)] == 3) {
            $gd3 = $this->normalizacion($statistical->gd3, $statistical->p10_gd3, $statistical->p90_gd3, $statistical->subparameters->gd_ratio_kh_fracture);
        }

        if ($statistical->gdAvailable[$this->buscarArray(4, $statistical->gdAvailable)] == 4) {
            $gd4 = $this->normalizacion($statistical->gd4, $statistical->p10_gd4, $statistical->p90_gd4, $statistical->subparameters->gd_geomechanical_damage_fraction);
        }
        $gdp = ($gd1 + $gd2 + $gd3 + $gd4);

        $totalStatistical = $msp + $fbp + $osp + $rpp + $idp + $gdp;

        return collect([($msp / $totalStatistical) * 100, ($fbp / $totalStatistical) * 100, ($osp / $totalStatistical) * 100, ($rpp / $totalStatistical) * 100, ($idp / $totalStatistical) * 100, ($gdp / $totalStatistical) * 100]);
    }

    public function normalizacion($valor, $p10, $p90, $peso)
    {
        return (($valor - $p10) / ($p90 - $p10)) * $peso;
    }

    public function buscarArray($n, $available)
    {
        return array_search($n, $available, false);
    }
}
