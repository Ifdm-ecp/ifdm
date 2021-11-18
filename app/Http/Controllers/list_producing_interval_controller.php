<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\formacionxpozo;
use DB;
use Validator;
use App\pozo;
use App\cuenca;
use App\formacion;
use App\Http\Requests\updateRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\permeabilidad_relativaxf_wo;
use App\permeabilidad_relativaxf_gl;
use App\Http\Requests\IntervalProducerRequest;
use App\presion_yacimiento;
use App\campo;
use App\PvtFormacionXPozo;

class list_producing_interval_controller extends Controller
{
    /**
     * Despliega la vista list_producing_interval con la información de intervalos, formaciones y cuencas para los select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $interval = DB::table('formacionxpozos')->select('id', 'nombre')->paginate(15);

                $formacion = formacion::select('id', 'nombre')->orderBy('nombre')->get();
                $basin = campo::select('id', 'nombre')->orderBy('nombre')->get();
                return view('intervalos.list_producing_interval', ['interval' => $interval, 'formacion' => $formacion, 'basin' => $basin]);
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
     * Despliega la vista edit_producing_interval con base en la información de un intervalo productor específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                
                $basin = cuenca::select('id', 'nombre')->orderBy('nombre')->get();
                $data = formacionxpozo::find($id);
                //dd($data->pvtFormacionXPozo);
                //dd($tabla);

                //dd($data->pvtFormacionXPozo);
                if($data->pvtFormacionXPozo != null)
                {
                    $data->pvt_table = $tabla = json_encode(PvtFormacionXPozo::edit($data->pvtFormacionXPozo));
                    $data->saturation_pressure = $data->pvtFormacionXPozo->saturation_pressure;
                }
                //dd($data->pvt_table);
                return view('intervalos.edit_producing_interval', ['data' => $data, 'basin' => $basin]);
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
        //dd($request->all());
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){

                    //Editar datos generales de intervalo
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
                    $pozoId=$interval->pozo_id;
                    $intervalo=$interval->nombre;

                    //Borrar datos tablas para editar
                    permeabilidad_relativaxf_wo::where('formacionxpozo_id', $interval->id)->delete();
                    permeabilidad_relativaxf_gl::where('formacionxpozo_id', $interval->id)->delete();
                    presion_yacimiento::where('id_intervalo', $interval->id)->delete();


                     
                    if($request->input("RelP") != '[{}]')
                    {
                        $this->storeWo($request, $interval);
                        //dd($request->all());
                    }
                    //dd($request->all());

                    if($request->RelP2  != '[{}]')
                    {
                        //dd(10);
                        $this->storeGas($request, $interval);
                    }

                    if($request->input("reservoirPressure")  != '[{}]')
                    {
                        $this->storeYacimiento($request, $interval);
                    }
                    
                     //Guardar tabla de pvt de campos
                    if(!empty(json_decode($request->pvt_table)))
                    {
                        PvtFormacionXPozo::actualizarPvtGlobal($request, $interval);
                    }
            
                    return view('database');
                    /*return \Redirect::action('edit_production_test_controller@index', compact('pozoId', 'intervalo'));*/
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }


    public function storeWo($request, $interval){
        $relative_permeability_wo=new permeabilidad_relativaxf_wo;

        $table = str_replace(",[null,null,null,null]","",$request->input("RelP"));
        $table = str_replace(",{}","",$table);
        $Sw=array();
        $Krw=array();
        $Kro=array();
        $Pcwo=array();
        $table = json_decode($table, true);
        //dd($table);

        foreach ($table as $value) {
                    $relative_permeability_wo = new permeabilidad_relativaxf_wo;
                    $relative_permeability_wo->sw = str_replace(",", ".", $value[0]);
                    $Sw[] = (float)str_replace(",", ".", $value[0]);

                    $relative_permeability_wo->krw = str_replace(",",".",$value[1]);
                    $Krw[] = (float)str_replace(",", ".", $value[1]);

                    $relative_permeability_wo->kro = str_replace(",",".",$value[2]);
                    $Kro[] = (float)str_replace(",", ".", $value[2]);

                    $relative_permeability_wo->pcwo = str_replace(",",".",$value[3]);
                    $Pcwo[] = (float)str_replace(",", ".", $value[3]);

                    $relative_permeability_wo->formacionxpozo_id=$interval->id;
                    $relative_permeability_wo->save();
        }
    }

    public function storeGas($request, $interval)
    {
        $relative_permeability_gl=new permeabilidad_relativaxf_gl;
                    
        $table = str_replace(",[null,null,null,null]","",$request->input("RelP2"));
        $table = str_replace(",{}","",$table);
        
        $Sg=array();
        $Krg=array();
        $Krl=array();
        $Pcgl=array();
        $table = json_decode($table, true);

        foreach ($table as $value) {
                    $relative_permeability_gl = new permeabilidad_relativaxf_gl;
                    $relative_permeability_gl->sg = str_replace(",", ".", $value[0]);
                    $Sg[] = (float)str_replace(",", ".", $value[0]);

                    $relative_permeability_gl->krg = str_replace(",",".",$value[1]);
                    $Krg[] = (float)str_replace(",", ".", $value[1]);

                    $relative_permeability_gl->krl = str_replace(",",".",$value[2]);
                    $Krl[] = (float)str_replace(",", ".", $value[2]);

                    $relative_permeability_gl->pcgl = str_replace(",",".",$value[3]);
                    $Pcgl[] = (float)str_replace(",", ".", $value[3]);

                    $relative_permeability_gl->formacionxpozo_id=$interval->id;
                    $relative_permeability_gl->save();
        }

    }



    public function storeYacimiento($request, $interval)
    {
        $reservoir_pressure=new presion_yacimiento;
        
        $table = str_replace(",[null,null,null]","",$request->input("reservoirPressure"));
        $table = str_replace(",{}","",$table);
        $date=array();
        $value=array();
        $comment=array();
        $table = json_decode($table, true);
        

        foreach ($table as $value) {
                    $reservoir_pressure = new presion_yacimiento;
                    $reservoir_pressure->fecha = $value[0];

                    $reservoir_pressure->valor = str_replace(",",".",$value[1]);

                    $reservoir_pressure->comentario = $value[2];

                    $reservoir_pressure->id_intervalo=$interval->id;
                    $reservoir_pressure->save();
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
                formacionxpozo::destroy($id);
                
                $interval = DB::table('formacionxpozos')->paginate(15);

                $formacion = formacion::select('id', 'nombre')->orderBy('nombre')->get();
                $basin = campo::select('id', 'nombre')->orderBy('nombre')->get();
                return redirect('listIntervalC');

            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }
}
