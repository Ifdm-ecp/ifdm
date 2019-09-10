<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\cuenca;
use DB;
use Validator;
use App\Http\Requests\updateRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\Http\Requests\BasinCreateRequest;
use App\coordenada_cuenca;
use View;

class list_basin_controller extends Controller
{
    /**
     * Despliega la vista list_basin con la información de las cuencas en la base de datos para popular el select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $basin = DB::table('cuencas')->paginate(15);
                return View::make('list_basin', compact(['basin']));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                $basin = cuenca::find($id);

                return view('edit_basin', ['basin' => $basin]);
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
    public function update(BasinCreateRequest $request, $id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){

                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'basin_name' => 'required|unique:cuencas,nombre,'.$id,
                ]);

                if ($validator->fails()) {
                    return redirect('listBasinC')
                                ->withErrors($validator)
                                ->withInput();
                }else{
                    
                    //Editar datos generales de cuenca
                    $basin = cuenca::find($id);
                    $basin->nombre = $request->input('basin_name');
                    $basin->save();

                    //Guardar tabla cuenca
                    $basin_coordinates=new coordenada_cuenca;
                    $table = str_replace(",[null,null]","",$request->input("basin_coordinates"));
                    $latitude=array();
                    $longitude=array();
                    $table = json_decode($table,true);

                    coordenada_cuenca::where('cuenca_id', $basin->id)->delete();

                }
                    return redirect('listBasinC');
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
                cuenca::destroy($id);

                $basin = DB::table('cuencas')->paginate(15);
                return redirect('listBasinC');
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }
}
