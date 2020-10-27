<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\shared_scenario;
use App\escenario;
use App\User;
use App\subparameters_weight;
use DB;

class project_management_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$users = collect(DB::table('users')->where('company_id', '=', \Auth::user()->company_id)
                               ->where('id', '!=', \Auth::id())
                               ->get());*/
        return view('projectmanagement');
    }

    public function dataTable()
    {
        $user_id = \Auth::id();
        $user = User::where('id', '=', $user_id)->first();
        $rol = $user->office;
        $company = $user->company;

        #Consultar escenarios segun el rol del usuario
        if ($rol == 0) { #System administrator
            $scenarios = escenario::All();
        } else if ($rol == 2) { # Engineer
            $scenarios = escenario::where('user_id', \Auth::id())->get();
        } else if ($rol == 1) { #Company administrator
            $scenarios = escenario::join('users', "users.id", "=", "escenarios.user_id")
                ->where("users.company", "=", $company)
                ->select('escenarios.id', 'escenarios.nombre', 'escenarios.descripcion')
                ->get();
        }

        if ($scenarios->count() > 0) {

            foreach ($scenarios as $k => $v) {
                $arr = [];
                $shared = [];
                $arr[] = $v->nombre;
                $arr[] = $v->tipo;
                if(count($v->shared) > 0)
                {
                    foreach($v->shared as $sharedUser)
                    {
                        $shared[] = $sharedUser->user->name;
                    }
                    $arr[] = $shared;
                }else{
                    $arr[] = '';
                }
                $v->actions = '<div align="center" class="row align-middle">';
                $v->actions .= '<button type="button" class="btn btn-default edit-button" data-target="#edit_scenario" data-id="'.$v->id.'" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></button>  ';
                $v->actions .= '<button type="button" class="btn btn-info share-button" data-target="#shared_scenario" data-id="'.$v->id.'" data-toggle="modal">Share</button>  ';
                $v->actions .= '<button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: delete_modal('.$v->id.');">Delete</button>';
                $v->actions .= '</div>';
                $arr[] = $v->actions;
                $data[$k] = $arr;
            }
        }else{
            $data = [['','','','']];
        }

        return json_encode(["data" => $data]);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json();
        }
        DB::table('shared_scenario')->where('scenario_id', '=', $request->input('scenario_t'))->delete();

        if (!empty($request->input('shared_users_s'))) {
            foreach ($request->input('shared_users_s') as $key => $value) {
                $shared_scenario = new shared_scenario;
                $shared_scenario->scenario_id = $request->input('scenario_t');
                $shared_scenario->user_id = $value;
                $shared_scenario->save();
            }
        }
        $request->session()->flash('alert-success', 'Scenario successfully shared!');
        return redirect('share_scenario');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function manage(Request $request)
    {
        $id = $request->input('id_scenario');
        $scenario = escenario::find($id);
        $scenario->nombre = $request->input('name_scenario');
        $scenario->descripcion = $request->input('description_scenario');
        $scenario->save();

        $request->session()->flash('alert-success', 'Scenario successfully edited!');
        return redirect('share_scenario');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $escenario = escenario::find($id);
        
        if($escenario->tipo == 'Multiparametric' && $escenario->multiparametricType == 'statistical'){//Drilling, Fines Treatment Selection,
            subparameters_weight::where('multiparametric_id', $escenario->statistical->id)->delete();
        }
        escenario::destroy($id);
        return redirect('share_scenario')->with('alert-success', 'Scenario successfully deleted!');
    }
}
