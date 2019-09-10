<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\formacionxpozo;
use DB;
use Validator;
use App\pozo;
use App\formacion;
use App\Http\Requests\updateRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\permeabilidad_relativaxf_wo;
use App\permeabilidad_relativaxf_gl;
use App\Http\Requests\IntervalProducerRequest;

class listIntervalWellController extends Controller
{
    /**
     * Despliega la vista de intervalwelllist con la información de intervalos y pozos para popular los select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $pozo = \Request::get('pozoId');
                $pozoN = DB::table('pozos')->where('id',$pozo)->value('nombre');
                $interval = DB::table('formacionxpozos')->where('pozo_id',$pozo)->select('id', 'nombre')->paginate(15);
                return view('IntervalWellList', ['interval' => $interval, 'pozo' => $pozo, 'pozoN' => $pozoN]);
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
     * Despliega la vista edit_producing_interval con la información específica para un intervalo productor..
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $well = pozo::All();
                $formacion = formacion::All();
                $intervalo = formacionxpozo::find($id);

                return view('edit_producing_interval', ['formacion' => $formacion, 'well' => $well, 'intervalo' => $intervalo]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IntervalProducerRequest $request, $id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){

                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'formacionName' => 'required',
                    'nameInterval' => 'required|unique:formacionxpozos,nombre,'.$id,
                    'wellName' => 'required',
                    'top' => 'numeric|min:0',
                    'netpay' => 'numeric|min:0',
                    'porosity' => 'numeric|between:0,100',
                    'permeability' => 'numeric|min:0',
                    'reservoir' => 'numeric|min:0',
                 ]);


                if ($validator->fails()) {
                    return redirect('listIntervalC')
                        ->withErrors($validator)
                        ->withInput();
                }else{
                    //Editar datos generales de intervalo productor
                    $interval=formacionxpozo::find($id);
                    $interval->nombre = $request->input('nameInterval');
                    $interval->top = $request->input('top');
                    $interval->netpay = $request->input('netpay');
                    $interval->porosidad = $request->input('porosity');
                    $interval->permeabilidad = $request->input('permeability');
                    $interval->presion_reservorio = $request->input('reservoir');
                    $interval->pozo_id = $request->input('wellName');
                    $interval->formacion_id = $request->input('formacionName');
                    $interval->save();

                    //Editar informacion tabla permeabilidad relativa w_o y g_l

                    //Tabla W-O
                    $RelP1 = $request->input('RelP');
                    $RelP = explode(",", $RelP1);

                    $Sw=array();
                    $Krw=array();
                    $Kro=array();
                    $Pcwo=array();
                    $aux_empty_wo=0;

                    for ($i = 0; $i < count(array_filter($RelP))/4; $i++) {     
                        $aux_empty_wo=1; 
                        $Sw[$i] = $RelP[(4*$i)];   
                        $Krw[$i] = $RelP[(4*$i)+1];
                        $Kro[$i] = $RelP[(4*$i)+2];
                        $Pcwo[$i] = $RelP[(4*$i)+3];
                    }

                    if($aux_empty_wo==1){
                        permeabilidad_relativaxf_wo::where('formacionxpozo_id', $interval->id)->delete();
                    }

                    for ($i = 0; $i < count($Sw); $i++) {
                        $relative_permeability_wo=new permeabilidad_relativaxf_wo;
                        $relative_permeability_wo->sw=$Sw[$i];
                        $relative_permeability_wo->krw=$Krw[$i];
                        $relative_permeability_wo->kro=$Kro[$i];
                        $relative_permeability_wo->pcwo=$Pcwo[$i];
                        $relative_permeability_wo->formacionxpozo_id=$interval->id;
                        $relative_permeability_wo->save(); 
                    }

                    //Tabla G-L
                    $RelP2 = $request->input('RelP2');
                    $RelP3 = explode(",", $RelP2);

                    $Sg=array();
                    $Krg=array();
                    $Krl=array();
                    $Pcgl=array();
                    $aux_empty_gl=0;

                    for ($i = 0; $i < count(array_filter($RelP3))/4; $i++) {      
                        $aux_empty_gl=1;
                        $Sg[$i] = $RelP3[(4*$i)];   
                        $Krg[$i] = $RelP3[(4*$i)+1];
                        $Krl[$i] = $RelP3[(4*$i)+2];
                        $Pcgl[$i] = $RelP3[(4*$i)+3];
                        
                    }

                    if($aux_empty_gl==1){
                        permeabilidad_relativaxf_gl::where('formacionxpozo_id', $interval->id)->delete();
                    }

                    for ($i = 0; $i < count($Sg); $i++) {
                        $relative_permeability_gl=new permeabilidad_relativaxf_gl;
                        $relative_permeability_gl->sg=$Sg[$i];
                        $relative_permeability_gl->krg=$Krg[$i];
                        $relative_permeability_gl->krl=$Krl[$i];
                        $relative_permeability_gl->pcgl=$Pcgl[$i];
                        $relative_permeability_gl->formacionxpozo_id=$interval->id;
                        $relative_permeability_gl->save(); 
                    }
                    return redirect('listIntervalC');

                }
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
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
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $pozo = DB::table('formacionxpozos')->where('id',$id)->value('pozo_id');
                formacionxpozo::destroy($id);

                $pozoN = DB::table('pozos')->where('id',$pozo)->value('nombre');
                $interval = DB::table('formacionxpozos')->where('pozo_id',$pozo)->paginate(15);
                return redirect('listIntervalC');
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }
}
