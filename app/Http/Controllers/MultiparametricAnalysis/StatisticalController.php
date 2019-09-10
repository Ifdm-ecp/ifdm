<?php


namespace App\Http\Controllers\MultiparametricAnalysis;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\MultiparametricAnalysis\StatisticalRequest;
use App\Models\MultiparametricAnalysis\Statistical;
use App\escenario;
use App\cuenca;
use Session;
use App\Traits\StatisticalTrait;

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
        if (\Auth::check()){
            $scenary = escenario::find(\Request::get('scenaryId'));
            $user = $scenary->user;
            $advisor = $scenary->enable_advisor;
            $cuencas = cuenca::all();
            $complete = false;

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
        return view('multiparametricAnalysis.statistical.edit', compact(['statistical', 'cuencas', 'complete','duplicateFrom']));
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
        $button_wr = (bool) isset($_POST['button_wr']);
        $request->button_wr = $button_wr;

        $update = $this->updateStatistical($request, $id);  
        $statistical = $update['statistical'];
        unset($_SESSION['scenary_id_dup']);

        if($update['opcion'] == 'campos'){
            return redirect()->route('statistical.edit', $statistical->id);
        }else{
            //se cambia el completo del escenario de 0 a 1
            $statistical->escenario->completo = 1;
            $statistical->escenario->save();

            return redirect()->route('statistical.show', $statistical->id);
        }
    }

    /* Recibe id del nuevo escenario, duplicateFrom seria el id del duplicado */    
    public function duplicate($id,$duplicateFrom)
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
