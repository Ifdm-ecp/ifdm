<?php

namespace App\Http\Controllers;

use App\cuenca;
use App\formacion;
use App\Http\Controllers\Controller;
use App\medicion;
use Illuminate\Http\Request;
use Response;
use Validator;

class edit_damage_variables_controller extends Controller
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubparametersByWell(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'well' => 'required|integer|exists:mediciones,pozo_id',
        // ]);

        // if ($validator->fails()) {
        //     return Response::json([
        //         'failed' => true,
        //         'errors' => $validator,
        //     ]);
        // }

        $subparameters = medicion::select('mediciones.id', 'mediciones.valor', 'mediciones.fecha', 'mediciones.comentario', 's.sigla', 's.unidad')
            ->join('subparametros AS s', 'mediciones.subparametro_id', '=', 's.id')
            ->where('mediciones.pozo_id', $request->well)
            ->orderBy('mediciones.fecha', 'desc')
            ->get();

        return Response::json($subparameters);
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
