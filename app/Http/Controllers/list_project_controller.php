<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Validator;
use App\Http\Requests;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\proyecto;
use App\User;
use App\company;
use View;
use Redirect;

class list_project_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::check()) {
            $user1 = User::where('name','=',\Auth::User()->name)->first();

            $company_all = company::all();
            $company = [[], [], [], [], []];
            $project_UN = [];
            $project_equion = [];
            $project_ecopetrol = [];
            $project_hocol = [];
            $project_uis = [];

            if(\Auth::User()->office == 0){
                #Todos los proyectos, compañias y usuarios. Perfil administrador del sistema
                $project_UN= proyecto::where('compania','=', 0)->take(8)->get();
                $project_ecopetrol= proyecto::where('compania','=', 1)->take(8)->get();
                $project_equion= proyecto::where('compania','=', 2)->take(8)->get();
                $project_hocol= proyecto::where('compania','=', 3)->take(8)->get();
                $project_uis= proyecto::where('compania','=', 4)->take(8)->get();

                $company = array("UN", "Equion", "Ecopetrol", "Hocol", "UIS");

                $user = User::all();
            } else if(\Auth::User()->office == 1){
                #Todos los proyectos y usuarios por compañia. Perfil administrador por compañia
                if(\Auth::User()->company == 0){
                    $project_UN= proyecto::where('compania','=', \Auth::User()->company)->take(8)->get();
                    $company[0] = "UN";
                    $user= User::where('company','=', \Auth::User()->company)->get();
                }else if(\Auth::User()->company == 1){
                    $project_equion= proyecto::where('compania','=', \Auth::User()->company)->take(8)->get();
                    $company[1] = "Equion";
                    $user= User::where('company','=', \Auth::User()->company)->get();
                }else if(\Auth::User()->company == 2){
                    $project_ecopetrol= proyecto::where('compania','=', \Auth::User()->company)->take(8)->get();
                    $company[2] = "Ecopetrol";
                    $user= User::where('company','=', \Auth::User()->company)->get();
                }else if(\Auth::User()->company == 3){
                    $project_hocol= proyecto::where('compania','=', \Auth::User()->company)->take(8)->get();
                    $company[3] = "Hocol";
                    $user= User::where('company','=', \Auth::User()->company)->get();
                }else if(\Auth::User()->company == 4){
                    $project_uis= proyecto::where('compania','=', \Auth::User()->company)->take(8)->get();
                    $company[4] = "UIS";
                    $user= User::where('company','=', \Auth::User()->company)->get();
                }

            } else if(\Auth::User()->office == 2){
                #Todos los proyectos del usuario que ingresó al sistema. Perfil Ingeniero
                if(\Auth::User()->company == 0){
                    $project_UN= proyecto::where('usuario_id','=', \Auth::User()->id)->take(8)->get();
                    $company[0] = "UN";
                }else if(\Auth::User()->company == 1){
                    $project_equion= proyecto::where('usuario_id','=', \Auth::User()->id)->take(8)->get();
                    $company[1] = "Equion";
                }else if(\Auth::User()->company == 2){
                    $project_ecopetrol= proyecto::where('usuario_id','=', \Auth::User()->id)->take(8)->get();
                    $company[2] = "Ecopetrol";
                }else if(\Auth::User()->company == 3){
                    $project_hocol= proyecto::where('usuario_id','=', \Auth::User()->id)->take(8)->get();
                    $company[3] = "Hocol";
                }else if(\Auth::User()->company == 4){
                    $project_uis= proyecto::where('usuario_id','=', \Auth::User()->id)->take(8)->get();
                    $company[4] = "UIS";
                }

                $user= User::where('id','=',$user1->id)->get();
            }
            
            $gender=\Auth::User()->gender;
            return view('list_project', ['user' => $user, 'project_UN' => $project_UN, 'project_ecopetrol' => $project_ecopetrol, 'project_equion' => $project_equion, 'project_hocol' => $project_hocol, 'project_uis' => $project_uis, 'gender' => $gender, 'company' => $company, 'company_all' => $company_all]);
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
     * Despliega la vista list_scenario con la información de un escenario específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            $scenary = DB::table('escenarios')->where('proyecto_id','=', $id)->where('estado','=', 1)->paginate(15);
            return View::make('list_scenario', compact(['scenary']));
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
        if (\Auth::check()) {
            $escenarios = DB::table('escenarios')->where('proyecto_id', $id)->get();
            if ($escenarios) {
                return Redirect::back()->with('error_code', 5);
            }else{
                proyecto::destroy($id);
                
                if(\Auth::User()->office == 0){
                    $project = DB::table('proyectos')->paginate(15);
                } else if(\Auth::User()->office == 1){
                    $project = DB::table('proyectos')->where('compania','=', \Auth::User()->company)->paginate(15);
                } else if(\Auth::User()->office == 2){
                    $project = DB::table('proyectos')->where('usuario_id','=', \Auth::User()->id)->paginate(15);
                } 
                return redirect('DeleteProject');
                return View::make('list_project', compact(['project']));
            }
        }else{
            return view('loginfirst');
        }
    }
}
