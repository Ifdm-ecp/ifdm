<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\campo;
use App\cuenca;
use DB;
use Validator;
use App\Http\Requests\updateRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\coordenada_campos;
use App\Http\Requests\FieldCreateRequest;
use App\pvt;

class list_field_controller extends Controller
{
    /**
     * Despliega la vista list_field con la información de formación y cuenca para los select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $field = DB::table('campos')->paginate(15);

                $basin = cuenca::select('id', 'nombre')->orderBy('nombre')->get();
                return view('list_field', ['field' => $field, 'basin' => $basin]);
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
     * Despliega la visat edit_field con la información de un campo específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $basin = cuenca::orderBy('nombre')->get();
                $field = campo::find($id);

                return view('edit_field', ['field' => $field, 'basin' => $basin]);
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
    public function update(FieldCreateRequest $request, $id)
        {
            if (\Auth::check()) {
                if(\Auth::User()->office!=2){

                    //Validaciones para formulario
                    $validator = Validator::make($request->all(), [
                        'field_name' => 'required|unique:campos,nombre,'.$id,
                        'basin_for_field' => 'required',
                        'coordinates' => 'mimes:xlsx',
                    ]);

                    if ($validator->fails()) {
                        return redirect('listFieldC')
                            ->withErrors($validator)
                            ->withInput();
                    }else{
                        //Editar datos generales de campo
                        $field=campo::find($id);
                        $field->nombre = $request->input('field_name');
                        $field->cuenca_id = $request->input('basin_for_field');
                        $field->save();

                        coordenada_campos::where('campo_id', $id)->delete();

                        //Editar tabla coordenadas de campos
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
                    
                    return redirect('listFieldC');
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
                campo::destroy($id);

                $basin = cuenca::select('id', 'nombre')->orderBy('nombre')->get();
                $field = DB::table('campos')->paginate(15);
                return redirect('listFieldC');
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }
}
