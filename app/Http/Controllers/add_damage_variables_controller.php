<?php

namespace App\Http\Controllers;

use App\cuenca;
use App\formacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\measurementRequest;
use App\medicion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class add_damage_variables_controller extends Controller
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
                $formacion = formacion::select('id', 'nombre');
                $cuenca = cuenca::select('id', 'nombre');
                return view('add_damage_variables', ['formacion' => $formacion, 'cuenca' => $cuenca]);
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
    public function store(measurementRequest $request)
    {
        if (\Auth::check()) {
            //Verificar si los valores de cada SP son ingresados para guardar todo el formulario

            if ($request->input('MS1')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateMS1'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('MS1');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('MS1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 1;
                $measurement->save();
            }

            if ($request->input('MS2')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateMS2'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('MS2');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('MS2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 2;
                $measurement->save();
            }

            if ($request->input('MS3')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateMS3'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('MS3');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('MS3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 3;
                $measurement->save();
            }

            if ($request->input('MS4')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateMS4'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('MS4');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('MS4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 4;
                $measurement->save();
            }

            if ($request->input('MS5')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateMS5'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('MS5');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('MS5comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 5;
                $measurement->save();
            }

            if ($request->input('FB1')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateFB1'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('FB1');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('FB1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 6;
                $measurement->save();
            }

            if ($request->input('FB2')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateFB2'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('FB2');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('FB2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 7;
                $measurement->save();
            }

            if ($request->input('FB3')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateFB3'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('FB3');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('FB3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 8;
                $measurement->save();
            }

            if ($request->input('FB4')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateFB4'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('FB4');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('FB4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 9;
                $measurement->save();
            }

            if ($request->input('FB5')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateFB5'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('FB5');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('FB5comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 10;
                $measurement->save();
            }

            if ($request->input('OS1')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateOS1'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('OS1');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('OS1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 11;
                $measurement->save();
            }

            if ($request->input('OS2')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateOS2'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('OS2');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('OS2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 30;
                $measurement->save();
            }

            if ($request->input('OS3')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateOS3'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('OS3');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('OS3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 12;
                $measurement->save();
            }

            if ($request->input('OS4')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateOS4'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('OS4');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('OS4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 13;
                $measurement->save();
            }

            if ($request->input('OS5')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateOS5'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('OS5');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('OS5comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 14;
                $measurement->save();
            }

            if ($request->input('RP1')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateRP1'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('RP1');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('RP1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 15;
                $measurement->save();
            }

            if ($request->input('RP2')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateRP2'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('RP2');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('RP2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 16;
                $measurement->save();
            }

            if ($request->input('RP3')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateRP3'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('RP3');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('RP3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 17;
                $measurement->save();
            }

            if ($request->input('RP4')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateRP4'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('RP4');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('RP4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 18;
                $measurement->save();
            }

            if ($request->input('ID1')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateID1'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('ID1');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('ID1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 19;
                $measurement->save();
            }

            if ($request->input('ID2')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateID2'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('ID2');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('ID2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 20;
                $measurement->save();
            }

            if ($request->input('ID3')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateID3'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('ID3');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('ID3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 21;
                $measurement->save();
            }

            if ($request->input('ID4')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateID4'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('ID4');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('ID4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 22;
                $measurement->save();
            }

            if ($request->input('GD1')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateGD1'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('GD1');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('GD1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 23;
                $measurement->save();
            }

            if ($request->input('GD2')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateGD2'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('GD2');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('GD2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 24;
                $measurement->save();
            }

            if ($request->input('GD3')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateGD3'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('GD3');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('GD3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 25;
                $measurement->save();
            }

            if ($request->input('GD4')) {
                $formatDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request->input('dateGD4'))));
                $measurement = new medicion;
                $measurement->valor = $request->input('GD4');
                $measurement->fecha = $formatDate->toDateString();
                $measurement->comentario = $request->input('GD4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 26;
                $measurement->save();
            }

            $request->session()->flash('mensaje', 'Record successfully entered.');

            return view('database');
        } else {
            return view('loginfirst');
        }
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
