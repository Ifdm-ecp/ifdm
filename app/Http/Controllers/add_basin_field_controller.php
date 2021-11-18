<?php

namespace App\Http\Controllers;
use DB;
use App\cuenca;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\BasinCreateRequest;
use App\Http\Requests\FieldCreateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\campo;
use App\coordenada_cuenca;
use App\coordenada_campos;

class add_basin_field_controller extends Controller
{
    /**
     * Despliega la vista add_basin_field con la información de las cuencas para popular el select de la visat.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $data['basin'] = cuenca::orderBy('nombre')->get();
                return view('add_basin_field', $data);
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
    public function storeBasin(BasinCreateRequest $request)
    {
        if (\Auth::check()) {

            if(\Auth::User()->office!=2){

                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'basin_name' => 'required|unique:cuencas,nombre',
                ]);
                if ($validator->fails()) {
                    return redirect('AddDataC')
                                ->withErrors($validator)
                                ->withInput();
                }else{

                    //Guardar cuenca
                    $basin=new cuenca;
                    $basin->nombre = $request->input('basin_name');
                    $basin->save();

                    //Guardar tabla de coordenadas de cuenca
                    $basin_coordinates=new coordenada_cuenca;
                    $table = str_replace(",[null,null]","",$request->input("basin_coordinates"));
                    $latitude=array();
                    $longitude=array();
                    $table = json_decode($table);

                    foreach ($table as $value) {
                        if($value[0]){
                            $basin_coordinates = new coordenada_cuenca;
                            $basin_coordinates->lat = str_replace(",", ".", $value[0]);
                            $latitude[] = (float)str_replace(",", ".", $value[0]);
                            $basin_coordinates->lon = str_replace(",",".",$value[1]);
                            $longitude[] = (float)str_replace(",", ".", $value[1]);
                            $basin_coordinates->cuenca_id=$basin->id;
                            $basin_coordinates->save();
                        }
                    }

                }

                    return redirect('database');
                }else{
                return view('permission');
            }
            }else{
                return view('loginfirst');
            }
    }
        

        public function storeField(FieldCreateRequest $request)
        {
            //dd($request->all());
            if (\Auth::check()) {
                if(\Auth::User()->office!=2){

                    //Validaciones para formulario
                    $validator = Validator::make($request->all(), [
                        'field_name' => 'required|unique:campos,nombre',
                        'basin_for_field' => 'required',
                    ]);

                    if ($validator->fails()) {
                        return redirect('AddDataC')
                            ->withErrors($validator)
                            ->withInput();
                    }else{

                        //Guardar campo
                        $field=new campo;
                        $field->nombre = $request->input('field_name');
                        $field->cuenca_id = $request->input('basin_for_field');
                        $field->save();

                        //Guardar tabla de coordenadas de campos
                        $field_coordinates=new coordenada_campos;
                        $table = str_replace(",[null,null]","",$request->input("field_coordinates"));
                        $latitude=array();
                        $longitude=array();
                        $order=array();
                        $table = json_decode($table);
                        foreach ($table as $value) {
                            if($value[0]){
                                if(!is_numeric($table[0][0])){
                                    $field_coordinates = new coordenada_campos;

                                    $field_coordinates->lat_gms = $value[0];
                                    $field_coordinates->lon_gms = $value[1];


                                    $lat_x = explode(" ", $value[0]);
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
                                    $field_coordinates->lat = $lat_value;
                                    
                                    $latitude[] = (float)str_replace(",", ".", $value[0]);

                                    $lon_x = explode(" ", $value[1]);
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
                                    $field_coordinates->lon = $lon_value;

                                    $longitude[] = (float)str_replace(",", ".", $value[1]);
                                    $field_coordinates->orden = null;
                                    $order[] = null;
                                    $field_coordinates->campo_id=$field->id;

                                    $field_coordinates->save();
                                }else{
                                    $field_coordinates = new coordenada_campos;
                                    $field_coordinates->lat = str_replace(",", ".", $value[0]);
                                    $latitude[] = (float)str_replace(",", ".", $value[0]);
                                    $field_coordinates->lon = str_replace(",",".",$value[1]);
                                    $longitude[] = (float)str_replace(",", ".", $value[1]);
                                    $field_coordinates->orden = null;
                                    $order[] = null;
                                    $field_coordinates->campo_id=$field->id;

                                    $field_coordinates->lat_gms = null;
                                    $field_coordinates->lon_gms = null;

                                    $field_coordinates->save();
                                }
                                
                            }
                        }


                    }
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


    public function basinField($id)
    {
        return json_encode(cuenca::basinField($id));
    }

    public function fieldFormation($id)
    {
        return json_encode(campo::fieldFormation($id));
    }

    
}
