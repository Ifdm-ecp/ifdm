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
                $formacion = formacion::select('id', 'nombre')->orderBy('nombre')->get();
                $cuenca = cuenca::select('id', 'nombre')->orderBy('nombre')->get();
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
                $measurement = new medicion;
                $measurement->valor = $request->input('MS1');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS1)->format('Y-m-d');
                $measurement->comentario = $request->input('MS1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 1;
                $measurement->save();
            }

            if ($request->input('MS2')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('MS2');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS2)->format('Y-m-d');
                $measurement->comentario = $request->input('MS2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 2;
                $measurement->save();
            }

            if ($request->input('MS3')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('MS3');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS3)->format('Y-m-d');
                $measurement->comentario = $request->input('MS3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 3;
                $measurement->save();
            }

            if ($request->input('MS4')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('MS4');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS4)->format('Y-m-d');
                $measurement->comentario = $request->input('MS4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 4;
                $measurement->save();
            }

            if ($request->input('MS5')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('MS5');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateMS5)->format('Y-m-d');
                $measurement->comentario = $request->input('MS5comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 5;
                $measurement->save();
            }

            if ($request->input('FB1')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('FB1');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB1)->format('Y-m-d');
                $measurement->comentario = $request->input('FB1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 6;
                $measurement->save();
            }

            if ($request->input('FB2')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('FB2');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB2)->format('Y-m-d');
                $measurement->comentario = $request->input('FB2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 7;
                $measurement->save();
            }

            if ($request->input('FB3')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('FB3');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB3)->format('Y-m-d');
                $measurement->comentario = $request->input('FB3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 8;
                $measurement->save();
            }

            if ($request->input('FB4')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('FB4');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB4)->format('Y-m-d');
                $measurement->comentario = $request->input('FB4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 9;
                $measurement->save();
            }

            if ($request->input('FB5')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('FB5');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateFB5)->format('Y-m-d');
                $measurement->comentario = $request->input('FB5comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 10;
                $measurement->save();
            }

            if ($request->input('OS1')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('OS1');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS1)->format('Y-m-d');
                $measurement->comentario = $request->input('OS1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 11;
                $measurement->save();
            }

            if ($request->input('OS2')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('OS2');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS2)->format('Y-m-d');
                $measurement->comentario = $request->input('OS2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 30;
                $measurement->save();
            }

            if ($request->input('OS3')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('OS3');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS3)->format('Y-m-d');
                $measurement->comentario = $request->input('OS3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 12;
                $measurement->save();
            }

            if ($request->input('OS4')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('OS4');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS4)->format('Y-m-d');
                $measurement->comentario = $request->input('OS4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 13;
                $measurement->save();
            }

            if ($request->input('OS5')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('OS5');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateOS5)->format('Y-m-d');
                $measurement->comentario = $request->input('OS5comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 14;
                $measurement->save();
            }

            if ($request->input('RP1')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('RP1');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP1)->format('Y-m-d');
                $measurement->comentario = $request->input('RP1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 15;
                $measurement->save();
            }

            if ($request->input('RP2')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('RP2');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP2)->format('Y-m-d');
                $measurement->comentario = $request->input('RP2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 16;
                $measurement->save();
            }

            if ($request->input('RP3')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('RP3');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP3)->format('Y-m-d');
                $measurement->comentario = $request->input('RP3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 17;
                $measurement->save();
            }

            if ($request->input('RP4')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('RP4');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP4)->format('Y-m-d');
                $measurement->comentario = $request->input('RP4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 18;
                $measurement->save();
            }

            if ($request->input('RP5')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('RP5');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateRP5)->format('Y-m-d');
                $measurement->comentario = $request->input('RP5comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 31;
                $measurement->save();
            }

            if ($request->input('ID1')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('ID1');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID1)->format('Y-m-d');
                $measurement->comentario = $request->input('ID1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 19;
                $measurement->save();
            }

            if ($request->input('ID2')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('ID2');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID2)->format('Y-m-d');
                $measurement->comentario = $request->input('ID2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 20;
                $measurement->save();
            }

            if ($request->input('ID3')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('ID3');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID3)->format('Y-m-d');
                $measurement->comentario = $request->input('ID3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 21;
                $measurement->save();
            }

            if ($request->input('ID4')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('ID4');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateID4)->format('Y-m-d');
                $measurement->comentario = $request->input('ID4comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 22;
                $measurement->save();
            }

            if ($request->input('GD1')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('GD1');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD1)->format('Y-m-d');
                $measurement->comentario = $request->input('GD1comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 23;
                $measurement->save();
            }

            if ($request->input('GD2')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('GD2');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD2)->format('Y-m-d');
                $measurement->comentario = $request->input('GD2comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 24;
                $measurement->save();
            }

            if ($request->input('GD3')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('GD3');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD3)->format('Y-m-d');
                $measurement->comentario = $request->input('GD3comment');
                $measurement->formacion_id = null;
                $measurement->pozo_id = $request->input('well');
                $measurement->subparametro_id = 25;
                $measurement->save();
            }

            if ($request->input('GD4')) {
                $measurement = new medicion;
                $measurement->valor = $request->input('GD4');
                $measurement->fecha = Carbon::createFromFormat('d/m/Y', $request->dateGD4)->format('Y-m-d');
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
