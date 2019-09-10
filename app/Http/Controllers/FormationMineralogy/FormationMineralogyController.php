<?php

namespace App\Http\Controllers\FormationMineralogy;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RequestFormationMineralogy;
use App\Http\Controllers\Controller;
use App\campo;
use App\formacion;
use App\cuenca;
use App\escenario;
use App\Models\FormationMineralogy\FormationMineralogy;
use App\Models\FormationMineralogy\FinePercentage;
use Input;

class FormationMineralogyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = FormationMineralogy::formacion_id($request->get('formacion_id'))->paginate(15);
        $basin = cuenca::all()->pluck('nombre', 'id');

        return view('formationMineralogy.index', compact('data', 'basin', 'finesDB'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $basin = cuenca::all()->pluck('nombre', 'id');
        $finesDB = collect(
            ['Albite','Analcime','Anhydrite', 'Ankeritec','AnkeriteIM','Baryte',  'Bentonite','Biotite', 'Brucite','Calcite','Cast', 'Celestine',  'Chloritec','Chamosite','Chabazite','ChloriteIM','Chloritem', 'Dolomite', 'Emectite', 'Gibbsite','Glauconite', 'Halite', 'Hematite','Heulandite','Illite', 'Kaolinite', 'Magnetite', 'Melanterite','Microcline', 'Muscovite', 'Natrolite','Orthoclase', 'PlagioClase','Pyrite', 'Pyrrhotite', 'Sideritec', 'SideriteIM', 'Stilbite', 'Troilite','Quartz']);

        return view('FormationMineralogy.create', compact('basin', 'finesDB'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestFormationMineralogy $request)
    {
        $formationMineralogy = FormationMineralogy::create($request->except('percentage', 'finestraimentselection', 'basin', 'field'));
        FinePercentage::crear($request->except('formacion_id', 'quarts', 'feldspar', 'clays', 'basin', 'field'), $formationMineralogy->id); 
        return view('database');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = FormationMineralogy::find($id);
        $basin = cuenca::all()->pluck('nombre', 'id');
        $finesDB = collect(
            ['Albite','Analcime','Anhydrite', 'Ankeritec','AnkeriteIM','Baryte',  'Bentonite','Biotite', 'Brucite','Calcite','Cast', 'Celestine',  'Chloritec','Chamosite','Chabazite','ChloriteIM','Chloritem', 'Dolomite', 'Emectite', 'Gibbsite','Glauconite', 'Halite', 'Hematite','Heulandite','Illite', 'Kaolinite', 'Magnetite', 'Melanterite','Microcline', 'Muscovite', 'Natrolite','Orthoclase', 'PlagioClase','Pyrite', 'Pyrrhotite', 'Sideritec', 'SideriteIM', 'Stilbite', 'Troilite','Quartz']);

        return view('FormationMineralogy.edit', compact('basin', 'data', 'finesDB'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestFormationMineralogy $request, $id)
    {
        $formationMineralogy = FormationMineralogy::find($id);
        $formationMineralogy->update($request->except('percentage', 'finestraimentselection', 'basin', 'field'));
        FinePercentage::actualizar($request->except('formacion_id', 'quarts', 'feldspar', 'clays', 'basin', 'field'), $formationMineralogy); 
        return view('database');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $borrar = FormationMineralogy::find($id); 
        //dd($borrar);
        FinePercentage::borrar($borrar);
        $borrar->delete();
        return back();
    }

    /*public function validar($request)
    {
        $errors = collect();
        if(FormationMineralogy::where('formacion_id', $request->formacion_id)->get()->count() > 0)
        {
            $errors->put('formacion_id', 'the formation_id is repeat');
        }

        return $errors;
    }*/

    public function fines($formacion)
    {
       $data =  escenario::find($formacion);

         if(!is_null($data->formacionxpozo->formacion->mineralogy))
            {
                return json_encode($data->formacionxpozo->formacion->mineralogy->finePercentage);
            }
            else{
                return 0;
            }

    }
}
