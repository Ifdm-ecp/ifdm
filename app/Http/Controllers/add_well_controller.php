<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormationWellRequest;
use App\formacionxpozo;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\pozo;
use App\fluidoxpozos;
use App\formacion;
use App\cuenca;
use App\datos_produccion;
use App\plt;

class add_well_controller extends Controller
{
    /**
     * DDespliega la vista add_well con la información de cuencas para popular el select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $data['cuenca'] = cuenca::All();
                return view('add_well', $data);
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
        //dd($request->all());
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){

                //Validaciones para formulario
                $validator = Validator::make($request->all(), [

                    'basin' => 'required',
                    'field' => 'required',
                    'uwi' => 'required',
                    'nameWell' => 'required|unique:pozos,nombre',

                    'wellRadius' => 'numeric|min:0',
                    'XW' => 'required',
                    'YW' => 'required',

                    'TDVW' => 'numeric'



                 ]);


                if ($validator->fails()) {
                    return redirect('AddFormationWC')
                        ->withErrors($validator)
                        ->withInput();
                }else{

                    $lat_value = $request->input("XW");
                    $lon_value = $request->input("YW");

                    if(!is_numeric($lat_value))
                    {
                        $lat_x = explode(" ", $lat_value);
                        unset($lat_x[0]);
                        $latitude = array();
                        foreach ($lat_x as $value2) 
                        {
                            if($value2!="")
                            {
                                array_push($latitude, $value2);
                            }
                        }
                        $lat_value = floatval($latitude[0]+$latitude[1]/60+$latitude[2]/3600);
                    }

                    if(!is_numeric($lon_value))
                    {
                        $lon_x = explode(" ", $lon_value);
                        unset($lon_x[0]);
                        $longitude = array();
                        foreach ($lon_x as $value3) 
                        {
                            if($value3!="")
                            {
                                array_push($longitude, $value3);
                            }
                        }
                        $lon_value = floatval($longitude[0]+$longitude[1]/60+$longitude[2]/3600);
                        $lon_value = -1*abs($lon_value);
                    }




                    $well=new pozo;

                    //Guardar informacion general del pozo
                    $well->nombre = $request->input('nameWell');
                    $well->campo_id = $request->input('field');
                    $well->uwi = $request->input('uwi');
                    $well->radius = $request->input('wellRadius');
                    $well->drainage_radius = $request->input('drainageRadius');
                    $well->lat = $lat_value;
                    $well->lon = $lon_value;
                    $well->tdv = $request->input('TDVW');
                    $well->cuenca_id = $request->input('basin');
                    $well->type =$request->input('type');
                    $well->save();

                    $pozoId = $well->id;

                    //Guardar tabla datos de produccion
                    $production_data=new datos_produccion;
                    $table = str_replace(",[null,null,null,null,null,null,null]","",$request->input("ProdD2"));
                    $Qg=array();
                    $Qo=array();
                    $Qw=array();
                    $cummulativeQg=array();
                    $cummulativeQo=array();
                    $cummulativeQw=array();
                    $date=array();
                    $table = json_decode($table);

                    foreach ($table as $value) {
                        if($value[0]){
                            $production_data = new datos_produccion;

                            $production_data->date = str_replace(",", ".", $value[0]);
                            $date[] = (float)str_replace(",", ".", $value[0]);

                            $production_data->qo = str_replace(",",".",$value[1]);
                            $Qo[] = (float)str_replace(",", ".", $value[1]);

                            $production_data->cummulative_qo = str_replace(",",".",$value[2]);
                            $cummulativeQo[] = (float)str_replace(",", ".", $value[2]);

                            $production_data->qg = str_replace(",",".",$value[3]);
                            $Qg[] = (float)str_replace(",", ".", $value[3]);

                            $production_data->cummulative_qg = str_replace(",",".",$value[4]);
                            $cummulativeQg[] = (float)str_replace(",", ".", $value[4]);

                            $production_data->qw = str_replace(",",".",$value[5]);
                            $Qw[] = (float)str_replace(",", ".", $value[5]);

                            $production_data->cummulative_qw = str_replace(",",".",$value[6]);
                            $cummulativeQw[] = (float)str_replace(",", ".", $value[6]);
                            $production_data->pozo_id=$well->id;
                            $production_data->save();
                        }
                    }


                    dd($request->input("plt"));

                    //Guardar tabla PLT
                    if ($request->input("plt") != "[{}]") { 
                        $plt=new plt;
                        $table = str_replace(",[null,null,null,null,null]","",$request->input("plt"));
                        $top=array();
                        $bottom=array();
                        $pqo=array();
                        $pqg=array();
                        $pqw=array();
                        $table = json_decode($table);
    
                        foreach ($table as $value) {
                            if(count($table)>=1){
                                $plt = new plt;
                                $plt->top = str_replace(",", ".", $value[0]);
                                $top[] = (float)str_replace(",", ".", $value[0]);
    
                                $plt->bottom = str_replace(",",".",$value[1]);
                                $bottom[] = (float)str_replace(",", ".", $value[1]);
    
                                $plt->pqo = str_replace(",", ".", $value[2]);
                                $pqo[] = (float)str_replace(",", ".", $value[2]);
    
                                $plt->pqg = str_replace(",",".",$value[3]);
                                $pqg[] = (float)str_replace(",", ".", $value[3]);
    
                                $plt->pqw = str_replace(",",".",$value[4]);
                                $pqw[] = (float)str_replace(",", ".", $value[4]);
    
                                $plt->pozo_id=$well->id;
                                $plt->save();
                            }
                        }
                    }
                   

                    return \Redirect::action('add_producing_interval_well_controller@index', compact('pozoId'));
                }
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
