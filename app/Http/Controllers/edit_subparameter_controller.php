<?php

namespace App\Http\Controllers;
if(!isset($_SESSION)) {
     session_start();
}
use DB;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\GeneralInformationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Session;
use View;
use App\multiparametrico;

class edit_subparameter_controller extends Controller
{
    /**
     * Despliega la vista edit_subparameter con la información del escenario multiparamétrico y los pesos de los subparámetros de daño para la vista del multiparamétrico.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            $mult = \Request::get('multId');
            $multiparametrico = multiparametrico::find($mult);

            $subparameters_weight = DB::table('subparameters_weight')->where('multiparametric_id', $multiparametrico->id)->first();

            $scenary = DB::table('escenarios')->where('Nombre', $_SESSION['scenary'])->first();
            $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();
            return View::make('edit_subparameter', compact(['scenary', 'multiparametrico', 'user', 'subparameters_weight']));
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
