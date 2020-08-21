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
use App\multiparametrico;
use View;

class add_subparameter_controller extends Controller
{
    /**
     * Despliega la vista add_subparameter con la información de las variables de daño de un escenario multiparamétrico específico.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $mult = \Request::get('multId');
        $multiparametrico = multiparametrico::find($mult);
        $scenary = DB::table('escenarios')->where('Nombre', $_SESSION['scenary'])->first();
        $user = DB::table('users')->join('escenarios','users.id','=','escenarios.user_id')->select('users.fullName')->where('escenarios.id','=',$scenary->id)->first();
        if(!strcmp($multiparametrico->statistical, "Colombia")){
            $MS1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 1)->orderBy('fecha', 'desc')->first();
            $MS2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 2)->orderBy('fecha', 'desc')->first();        
            $MS3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 3)->orderBy('fecha', 'desc')->first();        
            $MS4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 4)->orderBy('fecha', 'desc')->first();        
            $MS5 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 5)->orderBy('fecha', 'desc')->first();

            $FB1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 6)->orderBy('fecha', 'desc')->first();        
            $FB2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 7)->orderBy('fecha', 'desc')->first();      
            $FB3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 8)->orderBy('fecha', 'desc')->first();      
            $FB4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 9)->orderBy('fecha', 'desc')->first();        
            $FB5 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 10)->orderBy('fecha', 'desc')->first();

            $OS1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 11)->orderBy('fecha', 'desc')->first();
            $OS2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 13)->orderBy('fecha', 'desc')->first();
            $OS3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 14)->orderBy('fecha', 'desc')->first();
            $OS4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 15)->orderBy('fecha', 'desc')->first();

            $RP1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 17)->orderBy('fecha', 'desc')->first();
            $RP2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 18)->orderBy('fecha', 'desc')->first();
            $RP3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 19)->orderBy('fecha', 'desc')->first();
            $RP4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 20)->orderBy('fecha', 'desc')->first();

            $ID1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 21)->orderBy('fecha', 'desc')->first();
            $ID2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 22)->orderBy('fecha', 'desc')->first();
            $ID3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 23)->orderBy('fecha', 'desc')->first();
            $ID4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 24)->orderBy('fecha', 'desc')->first();

            $GD1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 25)->orderBy('fecha', 'desc')->first();
            $GD2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 26)->orderBy('fecha', 'desc')->first();
            $GD3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 27)->orderBy('fecha', 'desc')->first();
            $GD4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->where('subparametro_id', 28)->orderBy('fecha', 'desc')->first();
        }else{
            $campos=$multiparametrico->field_statistical;
            $MS1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 1)->orderBy('fecha', 'desc')->first();
            $MS2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 2)->orderBy('fecha', 'desc')->first();        
            $MS3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 3)->orderBy('fecha', 'desc')->first();        
            $MS4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 4)->orderBy('fecha', 'desc')->first();        
            $MS5 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 5)->orderBy('fecha', 'desc')->first();

            $FB1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 6)->orderBy('fecha', 'desc')->first();        
            $FB2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 7)->orderBy('fecha', 'desc')->first();      
            $FB3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 8)->orderBy('fecha', 'desc')->first();      
            $FB4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 9)->orderBy('fecha', 'desc')->first();        
            $FB5 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 10)->orderBy('fecha', 'desc')->first();

            $OS1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 11)->orderBy('fecha', 'desc')->first();
            $OS2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 13)->orderBy('fecha', 'desc')->first();
            $OS3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 14)->orderBy('fecha', 'desc')->first();
            $OS4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 15)->orderBy('fecha', 'desc')->first();

            $RP1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 17)->orderBy('fecha', 'desc')->first();
            $RP2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 18)->orderBy('fecha', 'desc')->first();
            $RP3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 19)->orderBy('fecha', 'desc')->first();
            $RP4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 20)->orderBy('fecha', 'desc')->first();

            $ID1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 21)->orderBy('fecha', 'desc')->first();
            $ID2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 22)->orderBy('fecha', 'desc')->first();
            $ID3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 23)->orderBy('fecha', 'desc')->first();
            $ID4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 24)->orderBy('fecha', 'desc')->first();

            $GD1 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 25)->orderBy('fecha', 'desc')->first();
            $GD2 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 26)->orderBy('fecha', 'desc')->first();
            $GD3 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 27)->orderBy('fecha', 'desc')->first();
            $GD4 = DB::table('mediciones')->join('pozos as p', 'mediciones.pozo_id','=','p.id')->wherein('p.campo_id',explode(',',$campos))->where('subparametro_id', 28)->orderBy('fecha', 'desc')->first();
        }

        $subparameters_weight = DB::table('subparameters_weight')->where('multiparametric_id', $multiparametrico->id)->first();

        $scenary = DB::table('escenarios')->where('Nombre', $_SESSION['scenary'])->select('descripcion')->first();
        return View::make('add_subparameter', compact(['MS1', 'MS2', 'MS3', 'MS4', 'MS5', 'FB1', 'FB2', 'FB3', 'FB4', 'FB5', 'OS1', 'OS2', 'OS3', 'OS4', 'RP1', 'RP2', 'RP3', 'RP4', 'ID1', 'ID2', 'ID3', 'ID4', 'GD1', 'GD2', 'GD3', 'GD4','scenary', 'multiparametrico', 'user', 'subparameters_weight']));
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
