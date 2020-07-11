<?php

namespace App\Http\Controllers\MultiparametricAnalysis;

use App\cuenca;
use App\escenario;
use App\Http\Controllers\Controller;
use App\Http\Requests\MultiparametricAnalyticalRequest;
use App\Models\MultiparametricAnalysis\Statistical;
use App\Traits\StatisticalTrait;
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
            $cuencas = cuenca::all();
            $complete = false;

            return view('multiparametricAnalysis.statistical.create', compact(['scenary', 'user', 'advisor', 'cuencas', 'complete']));
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
        $statistical = $this->storeStatistical($request);

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
        $statistical = $this->showStatistical($id);
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
        $statistical = $this->editStatistical($id);

        //se trae todas las cuencas existentes
        $cuencas = cuenca::all();
        $complete = false;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        //dd(Session::get('GD4'));
        return view('multiparametricAnalysis.statistical.edit', compact(['statistical', 'cuencas', 'complete', 'duplicateFrom']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MultiparametricAnalyticalRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MultiparametricAnalyticalRequest $request, $id)
    {
        if (\Auth::check()) {
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
                $statistical->ms1 = $request->MS1;
                $statistical->ms2 = $request->MS2;
                $statistical->ms3 = $request->MS3;
                $statistical->ms4 = $request->MS4;
                $statistical->ms5 = $request->MS5;
                $statistical->fb1 = $request->FB1;
                $statistical->fb2 = $request->FB2;
                $statistical->fb3 = $request->FB3;
                $statistical->fb4 = $request->FB4;
                $statistical->fb5 = $request->FB5;
                $statistical->os1 = $request->OS1;
                $statistical->os2 = $request->OS2;
                $statistical->os3 = $request->OS3;
                $statistical->os4 = $request->OS4;
                $statistical->os5 = $request->OS5;
                $statistical->rp1 = $request->RP1;
                $statistical->rp2 = $request->RP2;
                $statistical->rp3 = $request->RP3;
                $statistical->rp4 = $request->RP4;
                $statistical->id1 = $request->ID1;
                $statistical->id2 = $request->ID2;
                $statistical->id3 = $request->ID3;
                $statistical->id4 = $request->ID4;
                $statistical->gd1 = $request->GD1;
                $statistical->gd2 = $request->GD2;
                $statistical->gd3 = $request->GD3;
                $statistical->gd4 = $request->GD4;
                $statistical->kd = $request->kd;
                $statistical->date_ms1 = $request->dateMS1;
                $statistical->date_ms2 = $request->dateMS2;
                $statistical->date_ms3 = $request->dateMS3;
                $statistical->date_ms4 = $request->dateMS4;
                $statistical->date_ms5 = $request->dateMS5;
                $statistical->comment_ms1 = $request->MS1comment;
                $statistical->comment_ms2 = $request->MS2comment;
                $statistical->comment_ms3 = $request->MS3comment;
                $statistical->comment_ms4 = $request->MS4comment;
                $statistical->comment_ms5 = $request->MS5comment;
                $statistical->date_fb1 = $request->dateFB1;
                $statistical->date_fb2 = $request->dateFB2;
                $statistical->date_fb3 = $request->dateFB3;
                $statistical->date_fb4 = $request->dateFB4;
                $statistical->date_fb5 = $request->dateFB5;
                $statistical->comment_fb1 = $request->FB1comment;
                $statistical->comment_fb2 = $request->FB2comment;
                $statistical->comment_fb3 = $request->FB3comment;
                $statistical->comment_fb4 = $request->FB4comment;
                $statistical->comment_fb5 = $request->FB5comment;
                $statistical->date_os1 = $request->dateOS1;
                $statistical->date_os2 = $request->dateOS2;
                $statistical->date_os3 = $request->dateOS3;
                $statistical->date_os4 = $request->dateOS4;
                $statistical->date_os5 = $request->dateOS5;
                $statistical->comment_os1 = $request->OS1comment;
                $statistical->comment_os2 = $request->OS2comment;
                $statistical->comment_os3 = $request->OS3comment;
                $statistical->comment_os4 = $request->OS4comment;
                $statistical->comment_os5 = $request->OS5comment;
                $statistical->date_rp1 = $request->dateRP1;
                $statistical->date_rp2 = $request->dateRP2;
                $statistical->date_rp3 = $request->dateRP3;
                $statistical->date_rp4 = $request->dateRP4;
                $statistical->comment_rp1 = $request->RP1comment;
                $statistical->comment_rp2 = $request->RP2comment;
                $statistical->comment_rp3 = $request->RP3comment;
                $statistical->comment_rp4 = $request->RP4comment;
                $statistical->date_id1 = $request->dateID1;
                $statistical->date_id2 = $request->dateID2;
                $statistical->date_id3 = $request->dateID3;
                $statistical->date_id4 = $request->dateID4;
                $statistical->comment_id1 = $request->ID1comment;
                $statistical->comment_id2 = $request->ID2comment;
                $statistical->comment_id3 = $request->ID3comment;
                $statistical->comment_id4 = $request->ID4comment;
                $statistical->date_gd1 = $request->dateGD1;
                $statistical->date_gd2 = $request->dateGD2;
                $statistical->date_gd3 = $request->dateGD3;
                $statistical->date_gd4 = $request->dateGD4;
                $statistical->comment_gd1 = $request->GD1comment;
                $statistical->comment_gd2 = $request->GD2comment;
                $statistical->comment_gd3 = $request->GD3comment;
                $statistical->comment_gd4 = $request->GD4comment;
                $statistical->p10_ms1 = $request->p10_MS1;
                $statistical->p10_ms2 = $request->p10_MS2;
                $statistical->p10_ms3 = $request->p10_MS3;
                $statistical->p10_ms4 = $request->p10_MS4;
                $statistical->p10_ms5 = $request->p10_MS5;
                $statistical->p10_fb1 = $request->p10_FB1;
                $statistical->p10_fb2 = $request->p10_FB2;
                $statistical->p10_fb3 = $request->p10_FB3;
                $statistical->p10_fb4 = $request->p10_FB4;
                $statistical->p10_fb5 = $request->p10_FB5;
                $statistical->p10_os1 = $request->p10_OS1;
                $statistical->p10_os2 = $request->p10_OS2;
                $statistical->p10_os3 = $request->p10_OS3;
                $statistical->p10_os4 = $request->p10_OS4;
                $statistical->p10_os5 = $request->p10_OS5;
                $statistical->p10_rp1 = $request->p10_RP1;
                $statistical->p10_rp2 = $request->p10_RP2;
                $statistical->p10_rp3 = $request->p10_RP3;
                $statistical->p10_rp4 = $request->p10_RP4;
                $statistical->p10_id1 = $request->p10_ID1;
                $statistical->p10_id2 = $request->p10_ID2;
                $statistical->p10_id3 = $request->p10_ID3;
                $statistical->p10_id4 = $request->p10_ID4;
                $statistical->p10_gd1 = $request->p10_GD1;
                $statistical->p10_gd2 = $request->p10_GD2;
                $statistical->p10_gd3 = $request->p10_GD3;
                $statistical->p10_gd4 = $request->p10_GD4;
                $statistical->p90_ms1 = $request->p90_MS1;
                $statistical->p90_ms2 = $request->p90_MS2;
                $statistical->p90_ms3 = $request->p90_MS3;
                $statistical->p90_ms4 = $request->p90_MS4;
                $statistical->p90_ms5 = $request->p90_MS5;
                $statistical->p90_fb1 = $request->p90_FB1;
                $statistical->p90_fb2 = $request->p90_FB2;
                $statistical->p90_fb3 = $request->p90_FB3;
                $statistical->p90_fb4 = $request->p90_FB4;
                $statistical->p90_fb5 = $request->p90_FB5;
                $statistical->p90_os1 = $request->p90_OS1;
                $statistical->p90_os2 = $request->p90_OS2;
                $statistical->p90_os3 = $request->p90_OS3;
                $statistical->p90_os4 = $request->p90_OS4;
                $statistical->p90_os5 = $request->p90_OS5;
                $statistical->p90_rp1 = $request->p90_RP1;
                $statistical->p90_rp2 = $request->p90_RP2;
                $statistical->p90_rp3 = $request->p90_RP3;
                $statistical->p90_rp4 = $request->p90_RP4;
                $statistical->p90_id1 = $request->p90_ID1;
                $statistical->p90_id2 = $request->p90_ID2;
                $statistical->p90_id3 = $request->p90_ID3;
                $statistical->p90_id4 = $request->p90_ID4;
                $statistical->p90_gd1 = $request->p90_GD1;
                $statistical->p90_gd2 = $request->p90_GD2;
                $statistical->p90_gd3 = $request->p90_GD3;
                $statistical->p90_gd4 = $request->p90_GD4;
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

                /* ingresa los datos en la tabla subparameters_weight */
                $inputs = $request->all();
                $statistical->subparameters->update($inputs);

                return redirect()->route('statistical.show', $statistical->id);
            }
        } else {
            return view('loginfirst');
        }

        // $button_wr = (bool) isset($_POST['button_wr']);
        // $request->button_wr = $button_wr;

        // $update = $this->updateStatistical($request, $id);
        // $statistical = $update['statistical'];
        // unset($_SESSION['scenary_id_dup']);

        // if($update['opcion'] == 'campos'){
        //     return redirect()->route('statistical.edit', $statistical->id);
        // }else{
        //     //se cambia el completo del escenario de 0 a 1
        //     $statistical->escenario->completo = 1;
        //     $statistical->escenario->save();

        //     return redirect()->route('statistical.show', $statistical->id);
        // }
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

}
