<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PvtGlobalRequest;
use App\Http\Controllers\Controller;
use App\cuenca;
use App\PvtGlobal;


class PvtGlobalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = PvtGlobal::formacion_id($request->get('formacion_id'))->paginate(15);
        $basin = cuenca::all()->pluck('nombre', 'id')->sort();
        return view('pvtGlobal.index', compact('data','basin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $basin =  cuenca::all()->pluck('nombre', 'id')->sort();
        return view('pvtGlobal.create', compact('basin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PvtGlobalRequest $request)
    {
        PvtGlobal::store($request);
        return redirect('database');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $basin =  cuenca::all()->pluck('nombre', 'id')->sort();
        $data = PvtGlobal::find($id);//
        $datos = PvtGlobal::edit($data);
        //dd($data);
        //dd($datos);

        return view('pvtGlobal.edit', compact('data', 'basin', 'datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PvtGlobalRequest $request, $id)
    {
        PvtGlobal::actualizarPvtGlobal($request, $id);
        return redirect('database');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PvtGlobal::find($id)->delete();
        return back();
    }


    public function tree()
    {
       return PvtGlobal::tree();
    }

    public function treeDatosPvt($id)
    {
        $data = PvtGlobal::find($id);
        $tabla = PvtGlobal::edit($data);  

        return json_encode(collect(['saturacion_pressure' => $data->saturation_pressure, 'tabla' => $tabla]));
    }
}
