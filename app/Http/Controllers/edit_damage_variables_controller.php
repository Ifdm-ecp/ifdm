<?php

namespace App\Http\Controllers;

use App\cuenca;
use App\formacion;
use App\Http\Controllers\Controller;
use App\medicion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Validator;

class edit_damage_variables_controller extends Controller
{
    public $subparameterRuleList = array(
        'MSP1' => 'required|numeric|min:0',
        'MSP2' => 'required|numeric|min:0',
        'MSP3' => 'required|numeric|min:0',
        'MSP4' => 'required|numeric|between:0,1000000',
        'MSP5' => 'required|numeric|between:0,1000000',
        'FBP1' => 'required|numeric|between:0,1000000',
        'FBP2' => 'required|numeric|between:0,1000000',
        'FBP3' => 'required|numeric|between:0,100',
        'FBP4' => 'required|numeric|between:0,1',
        'FBP5' => 'required|numeric|min:0',
        'OSP1' => 'required|numeric|between:0,14',
        'OSP2' => 'required|numeric|min:0',
        'OSP3' => 'required|numeric|min:0',
        'OSP4' => 'required|numeric|between:0,20000',
        'OSP5' => 'required|numeric',
        'KrP1' => 'required|numeric|between:0,20000',
        'KrP2' => 'required|numeric|between:-15000,15000',
        'KrP3' => 'required|numeric|min:0|not_in:0',
        'KrP4' => 'required|numeric|min:0|not_in:0',
        'KrP5' => 'required|numeric|min:0|not_in:0',
        'IDP1' => 'required|numeric|between:0,10000',
        'IDP2' => 'required|numeric|min:0',
        'IDP3' => 'required|numeric|between:0,1000000',
        'IDP4' => 'required|numeric|between:0,10000',
        'GDP1' => 'required|numeric|between:0,1',
        'GDP2' => 'required|numeric|between:0,10000',
        'GDP3' => 'required|numeric',
        'GDP4' => 'required|numeric|between:0,1',
    );

    /**
     * Despliega la vista add_damage_variables con la información de formaciones para popular los select de la vista.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if (\Auth::User()->office != 2) {
                $basins = cuenca::orderBy('nombre')->get();
                return view('edit_damage_variables', ['basins' => $basins]);
            } else {
                return view('permission');
            }
        } else {
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
     * Returns a list of subparameters filtered by well.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubparametersByWell(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'well' => 'required|numeric|exists:mediciones,pozo_id',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'errors' => $validator->messages(),
            ]);
        }

        $subparameters = medicion::select('mediciones.id', 'mediciones.valor', 'mediciones.fecha', 'mediciones.comentario', 's.sigla', 's.unidad')
            ->join('subparametros AS s', 'mediciones.subparametro_id', '=', 's.id')
            ->where('mediciones.pozo_id', $request->well)
            ->orderBy('mediciones.fecha', 'desc')
            ->get();

        return Response::json([
            'success' => true,
            'data' => $subparameters,
        ]);
    }

    /**
     * Edits a subparameter's data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editSubparameter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:mediciones,id',
            'value' => $this->subparameterRuleList[$request->initials],
            'date' => 'required|date_format:d/m/Y',
            'comment' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'errors' => $validator->messages(),
            ]);
        }

        $subparameter = medicion::find($request->id);
        $subparameter->update([
            'valor' => $request->value,
            'fecha' => Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'),
            'comentario' => $request->comment,
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Damage variable edited successfully.',
        ]);
    }

    /**
     * Removes a subparameter from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeSubparameter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:mediciones,id'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'errors' => $validator->messages(),
            ]);
        }

        $subparameter = medicion::find($request->id);
        $subparameter->delete();

        return Response::json([
            'success' => true,
            'message' => 'Damage variable removed successfully.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
