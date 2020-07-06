<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\pozo;
use App\Http\Requests\measurementRequest;
use App\formacion;
use Validator;
use App\medicion;
use App\cuenca;


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
            if(\Auth::User()->office!=2){
                $formacion = formacion::select('id', 'nombre');
                $cuenca = cuenca::select('id', 'nombre');
                return view('add_damage_variables', ['formacion' => $formacion, 'cuenca' => $cuenca]);
            }else{
                return view('permission');
            }
        }else{
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
            if(\Auth::User()->profile!=5){
                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'MS1' => 'numeric|required_with:dateMS1',
                    'MS2' => 'numeric|required_with:dateMS2',
                    'MS3' => 'numeric|required_with:dateMS3',
                    'MS4' => 'numeric|required_with:dateMS4',
                    'MS5' => 'numeric|required_with:dateMS5',
                    'FB1' => 'numeric|required_with:dateFB1',
                    'FB2' => 'numeric|required_with:dateFB2',
                    'FB3' => 'numeric|required_with:dateFB3',
                    'FB4' => 'numeric|required_with:dateFB4',
                    'FB5' => 'numeric|required_with:dateFB5',
                    'OS1' => 'numeric|required_with:dateOS1',
                    'OS2' => 'numeric|required_with:dateOS2',
                    'OS3' => 'numeric|required_with:dateOS3',
                    'OS4' => 'numeric|required_with:dateOS4',
                    'RP1' => 'numeric|required_with:dateRP1',
                    'RP2' => 'numeric|required_with:dateRP2',
                    'RP3' => 'numeric|required_with:dateRP3',
                    'RP4' => 'numeric|required_with:dateRP4',
                    'ID1' => 'numeric|required_with:dateID1',
                    'ID2' => 'numeric|required_with:dateID2',
                    'ID3' => 'numeric|required_with:dateID3',
                    'ID4' => 'numeric|required_with:dateID4',
                    'GD1' => 'numeric|required_with:dateGD1',
                    'GD2' => 'numeric|required_with:dateGD2',
                    'GD3' => 'numeric|required_with:dateGD3',
                    'GD4' => 'numeric|required_with:dateGD4',

                    'dateMS1' => 'required_with:MS1',
                    'dateMS2' => 'required_with:MS2',
                    'dateMS3' => 'required_with:MS3',
                    'dateMS4' => 'required_with:MS4',
                    'dateMS5' => 'required_with:MS5',
                    'dateFB1' => 'required_with:FB1',
                    'dateFB2' => 'required_with:FB2',
                    'dateFB3' => 'required_with:FB3',
                    'dateFB4' => 'required_with:FB4',
                    'dateFB5' => 'required_with:FB5',
                    'dateOS1' => 'required_with:OS1',
                    'dateOS2' => 'required_with:OS2',
                    'dateOS3' => 'required_with:OS3',
                    'dateOS4' => 'required_with:OS4',
                    'dateRP1' => 'required_with:RP1',
                    'dateRP2' => 'required_with:RP2',
                    'dateRP3' => 'required_with:RP3',
                    'dateRP4' => 'required_with:RP4',
                    'dateID1' => 'required_with:ID1',
                    'dateID2' => 'required_with:ID2',
                    'dateID3' => 'required_with:ID3',
                    'dateID4' => 'required_with:ID4',
                    'dateGD1' => 'required_with:GD1',
                    'dateGD2' => 'required_with:GD2',
                    'dateGD3' => 'required_with:GD3',
                    'dateGD4' => 'required_with:GD4',

                    'well' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect('AddMeasurementC')
                                ->withErrors($validator)
                                ->withInput();
                }else{

                    //Verificar si los valores de cada SP son ingresados para guardar todo el formulario

                    if($request->input('MS1')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('MS1');
                        $measurement->fecha = $request->input('dateMS1');
                        $measurement->comentario = $request->input('MS1comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 1;
                        $measurement->save();
                    }

                    if($request->input('MS2')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('MS2');
                        $measurement->fecha = $request->input('dateMS2');
                        $measurement->comentario = $request->input('MS2comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 2;
                        $measurement->save();
                    }

                    if($request->input('MS3')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('MS3');
                        $measurement->fecha = $request->input('dateMS3');
                        $measurement->comentario = $request->input('MS3comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 3;
                        $measurement->save();
                    }

                    if($request->input('MS4')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('MS4');
                        $measurement->fecha = $request->input('dateMS4');
                        $measurement->comentario = $request->input('MS4comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 4;
                        $measurement->save();
                    }

                    if($request->input('MS5')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('MS5');
                        $measurement->fecha = $request->input('dateMS5');
                        $measurement->comentario = $request->input('MS5comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 5;
                        $measurement->save();
                    }

                    if($request->input('FB1')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('FB1');
                        $measurement->fecha = $request->input('dateFB1');
                        $measurement->comentario = $request->input('FB1comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 6;
                        $measurement->save();
                    }

                    if($request->input('FB2')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('FB2');
                        $measurement->fecha = $request->input('dateFB2');
                        $measurement->comentario = $request->input('FB2comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 7;
                        $measurement->save();
                    }

                    if($request->input('FB3')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('FB3');
                        $measurement->fecha = $request->input('dateFB3');
                        $measurement->comentario = $request->input('FB3comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 8;
                        $measurement->save();
                    }

                    if($request->input('FB4')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('FB4');
                        $measurement->fecha = $request->input('dateFB4');
                        $measurement->comentario = $request->input('FB4comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 9;
                        $measurement->save();
                    }

                    if($request->input('FB5')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('FB5');
                        $measurement->fecha = $request->input('dateFB5');
                        $measurement->comentario = $request->input('FB5comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 10;
                        $measurement->save();
                    }

                    if($request->input('OS1')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('OS1');
                        $measurement->fecha = $request->input('dateOS1');
                        $measurement->comentario = $request->input('OS1comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 11;
                        $measurement->save();
                    }

                    if($request->input('OS2')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('OS2');
                        $measurement->fecha = $request->input('dateOS2');
                        $measurement->comentario = $request->input('OS2comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 30;
                        $measurement->save();
                    }

                    if($request->input('OS3')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('OS3');
                        $measurement->fecha = $request->input('dateOS3');
                        $measurement->comentario = $request->input('OS3comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 12;
                        $measurement->save();
                    }

                    if($request->input('OS4')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('OS4');
                        $measurement->fecha = $request->input('dateOS4');
                        $measurement->comentario = $request->input('OS4comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 13;
                        $measurement->save();
                    }

                    if($request->input('OS5')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('OS5');
                        $measurement->fecha = $request->input('dateOS5');
                        $measurement->comentario = $request->input('OS5comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 14;
                        $measurement->save();
                    }

                    if($request->input('RP1')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('RP1');
                        $measurement->fecha = $request->input('dateRP1');
                        $measurement->comentario = $request->input('RP1comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 15;
                        $measurement->save();
                    }

                    if($request->input('RP2')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('RP2');
                        $measurement->fecha = $request->input('dateRP2');
                        $measurement->comentario = $request->input('RP2comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 16;
                        $measurement->save();
                    }

                    if($request->input('RP3')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('RP3');
                        $measurement->fecha = $request->input('dateRP3');
                        $measurement->comentario = $request->input('RP3comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 17;
                        $measurement->save();
                    }

                    if($request->input('RP4')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('RP4');
                        $measurement->fecha = $request->input('dateRP4');
                        $measurement->comentario = $request->input('RP4comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 18;
                        $measurement->save();
                    }

                    if($request->input('ID1')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('ID1');
                        $measurement->fecha = $request->input('dateID1');
                        $measurement->comentario = $request->input('ID1comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 19;
                        $measurement->save();
                    }

                    if($request->input('ID2')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('ID2');
                        $measurement->fecha = $request->input('dateID2');
                        $measurement->comentario = $request->input('ID2comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 20;
                        $measurement->save();
                    }

                    if($request->input('ID3')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('ID3');
                        $measurement->fecha = $request->input('dateID3');
                        $measurement->comentario = $request->input('ID3comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 21;
                        $measurement->save();
                    }

                    if($request->input('ID4')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('ID4');
                        $measurement->fecha = $request->input('dateID4');
                        $measurement->comentario = $request->input('ID4comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 22;
                        $measurement->save();
                    }

                    if($request->input('GD1')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('GD1');
                        $measurement->fecha = $request->input('dateGD1');
                        $measurement->comentario = $request->input('GD1comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 23;
                        $measurement->save();
                    }

                    if($request->input('GD2')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('GD2');
                        $measurement->fecha = $request->input('dateGD2');
                        $measurement->comentario = $request->input('GD2comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 24;
                        $measurement->save();
                    }

                    if($request->input('GD3')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('GD3');
                        $measurement->fecha = $request->input('dateGD3');
                        $measurement->comentario = $request->input('GD3comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 25;
                        $measurement->save();
                    }

                    if($request->input('GD4')){
                        $measurement=new medicion;
                        $measurement->valor = $request->input('GD4');
                        $measurement->fecha = $request->input('dateGD4');
                        $measurement->comentario = $request->input('GD4comment');
                        $measurement->formacion_id = null;
                        $measurement->pozo_id = $request->input('well');
                        $measurement->subparametro_id = 26;
                        $measurement->save();
                    }
                }
                    
                $request->session()->flash('mensaje', 'Record successfully entered.');

                return view('database');
            }else{
                return view('permission');
            }
        }else{
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
