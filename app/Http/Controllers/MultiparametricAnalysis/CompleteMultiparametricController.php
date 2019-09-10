<?php

namespace App\Http\Controllers\MultiparametricAnalysis;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\escenario;
use App\cuenca;
use App\Http\Requests\MultiparametricAnalysis\AnalyticalRequest;
use App\Models\MultiparametricAnalysis\Analytical;
use App\Traits\StatisticalTrait;
use App\Traits\AnalyticalTrait;



class CompleteMultiparametricController extends Controller
{
    use StatisticalTrait;
    use AnalyticalTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()){
            $scenary = escenario::find(\Request::get('scenaryId'));
            $user = $scenary->user;
            $advisor = $scenary->enable_advisor;
            $cuencas = cuenca::all();
            $complete = true;

            return view('multiparametricAnalysis.statistical.create', compact(['scenary','user', 'advisor', 'cuencas', 'complete']));
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
        return redirect()->route('completeMultiparametric.edit', $statistical->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $analytical = Analytical::find($id);
        if($analytical == null) {
            $analytical = Analytical::where('escenario_id', $id)->first();
        }
        
        $statistical = $this->showStatistical($analytical->escenario->statistical->id);

        if (!$analytical->status_wr && !$statistical->status_wr) {
            $datosStatistical = $this->graficoStatistical($statistical);
            $datosAnalytical = $this->graficoAnalytical($analytical);
            $datosAverage = collect([ ($datosStatistical[0]+$datosAnalytical[0])/2,  ($datosStatistical[1]+$datosAnalytical[1])/2,  ($datosStatistical[2]+$datosAnalytical[2])/2,  ($datosStatistical[3]+$datosAnalytical[3])/2,  ($datosStatistical[4]+$datosAnalytical[4])/2, ($datosStatistical[5]+$datosAnalytical[5])/2]);
        } else {
            $datosStatistical = [];
            $datosAnalytical = [];
            $datosAverage = [];
        }


        //dd($datosAverage);
        return view('multiparametricAnalysis.complete.show', compact(['statistical', 'analytical', 'datosStatistical', 'datosAnalytical', 'datosAverage']));
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

        $complete = true;
        
        //dd(Session::get('GD4'));
        return view('multiparametricAnalysis.statistical.edit', compact(['statistical', 'cuencas', 'complete']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status_wr = isset($_POST['button_wr']);
        $request->status_wr = $status_wr;
        $update = $this->updateStatistical($request, $id);  
        $statistical = $update['statistical'];

        if($update['opcion'] == 'campos'){
            return redirect()->route('completeMultiparametric.edit', $statistical->id);
        }else{
            //se cambia el completo del escenario de 0 a 1
            $statistical->escenario->completo = 1;
            $statistical->escenario->save();
            
            if($statistical->escenario->analytical) {
                return redirect()->route('completeAnalytical.edit', $statistical->escenario->analytical->id);
            }else{
                return redirect()->route('completeAnalytical.create', $statistical->escenario->id);
            }


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

    public function createAnalytical($id)
    {
        if (\Auth::check()){
            $escenario = escenario::find($id);
            $cuencas = cuenca::all();
            $complete = true;

            return view('multiparametricAnalysis.analytical.create', compact(['escenario','cuencas', 'complete']));
        }
    }

    public function storeAnalytical(AnalyticalRequest $request)
    {
        $status_wr = isset($_POST['button_wr']);
        $inputs = $request->all();
        $inputs = array_merge($inputs,['status_wr' => $status_wr]);
        
        $store = Analytical::create($inputs);
        return redirect()->route('completeMultiparametric.show', $store->id);
    }

    public function editAnalytical($id)
    {
        $analytical = Analytical::find($id);
        $complete = true;
        $advisor = true;
        return view('multiparametricAnalysis.analytical.edit', compact(['analytical', 'complete','advisor']));
    }

    public function updateAnalytical(AnalyticalRequest $request, $id)
    {
        $status_wr = isset($_POST['button_wr']);
        $inputs = $request->all();
        $inputs = array_merge($inputs,['status_wr' => $status_wr]);

        $analytical = Analytical::find($id)->update($inputs);
        return redirect()->route('completeMultiparametric.show', $id);
    }

}
