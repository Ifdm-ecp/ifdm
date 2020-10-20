<?php

namespace App\Http\Controllers\MultiparametricAnalysis;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\MultiparametricAnalysis\AnalyticalRequest;
use App\Models\MultiparametricAnalysis\Analytical;
use App\escenario;
use App\cuenca;
use App\Traits\AnalyticalTrait;

class analyticalController extends Controller
{
    use AnalyticalTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()){
            $escenario = escenario::find(\Request::get('scenaryId'));
            $complete = false;
            $advisor = 'true';
            $pozoId = $escenario->pozo_id;

            return view('multiparametricAnalysis.analytical.create', compact(['escenario', 'complete', 'advisor', 'pozoId']));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnalyticalRequest $request)
    {
        $status_wr = isset($_POST['button_wr']);

        $store_data = $request->all();
        $store_data = array_merge($store_data, ['status_wr' => $status_wr]);

        $store = Analytical::create($store_data);

        return redirect()->route('analytical.show', $store->escenario->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $escenario = escenario::find($id);
        $analytical = $escenario->analytical;

        if (!$analytical->status_wr) {
            $grafico = $this->graficoAnalytical($analytical);
        } else {
            $grafico = [];
        }

        $analytical->escenario->estado = 1;
        $analytical->escenario->completo = 1;
        $analytical->escenario->save();
        return view('multiparametricAnalysis.analytical.show', compact(['analytical', 'grafico']));
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
        $analytical = Analytical::find($id);
        if (!$analytical) {
            $analytical = Analytical::where('escenario_id', $id)->first();
        }
        $escenario = escenario::find($id);
        $complete = false;
        $advisor = true;
        $pozoId = $escenario->pozo_id;
        $duplicateFrom = isset($_SESSION['scenary_id_dup']) ? $_SESSION['scenary_id_dup'] : null;

        return view('multiparametricAnalysis.analytical.edit', compact(['analytical', 'complete', 'advisor', 'duplicateFrom']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AnalyticalRequest $request, $id)
    {
        $status_wr = isset($_POST['button_wr']);

        $update_data = $request->all();
        $update_data = array_merge($update_data, ['status_wr' => $status_wr]);

        $analytical = Analytical::find($id)->update($update_data);
        unset($_SESSION['scenary_id_dup']);

        return redirect()->route('analytical.show', Analytical::find($id)->escenario_id);

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
