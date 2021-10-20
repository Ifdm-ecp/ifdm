<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\sessions;
use App\campo;
use App\cuenca;
use App\formacion;
use App\pozo;
use App\formacionxpozo;
use App\proyecto;
use App\escenario;
use App\User;
use DB;

class user_statistics_controller extends Controller
{
    /**
     * Despliega vista inicial user statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office!=2){

                //Contar usuarios activos por fecha para todas las compañias
                $sessions = sessions::select(DB::raw('count(distinct user_id) as count_users, date(first_activity) as date'))
                ->groupby('date')
                ->orderBy('date', 'asc')
                ->get();

                $company = DB::table('company')->get();

                $sessionsbycompany = [];


                for ($i=0; $i < count($company); $i++) { 
                    $aux = [];

                    //Contar usuarios activos por fecha para cada compañia
                    $sessionsbycompany_aux = sessions::select(DB::raw('count(distinct user_id) as count_users, date(first_activity) as date'))
                    ->groupby('date')
                    ->orderBy('date', 'asc')
                    ->join('users', 'users.id', '=', 'sessions.user_id')
                    ->where('users.company', '=', $company[$i]->id)
                    ->get();
                    array_push($aux, $sessionsbycompany_aux);
                    array_push($aux, $company[$i]->name);
                    array_push($sessionsbycompany,$aux);
                }

                //Tiempo total en horas por usuarios
                $timebyusers = sessions::select(DB::raw("concat(floor(SUM(TIMESTAMPDIFF(minute, first_activity, last_activity))/60),'h ', mod(SUM(TIMESTAMPDIFF(minute, first_activity, last_activity)),60), 'm') as time, sessions.user_id, users.company, users.name, company.name as company, users.id as id"))
                    ->groupby('user_id')
                    ->join('users', 'users.id', '=', 'sessions.user_id')
                    ->join('company', 'company.id', '=', 'users.company')
                    ->get();

                return view('user_statistics', ['sessions' => $sessions, 'sessionsbycompany' => $sessionsbycompany, 'timebyusers' => $timebyusers]);
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

        if (\Auth::check()) {
            if(\Auth::User()->office!=2){
                //Tiempo activo en horas para cada fecha por usuario
                $sessionsbyuser = sessions::select(DB::raw('SUM(TIME_TO_SEC(timediff(last_activity, first_activity))) as time, date(first_activity) as date, user_id, users.name as user_name'))
                ->groupby('date')
                ->orderBy('date', 'asc')
                ->join('users', 'users.id', '=', 'sessions.user_id')
                ->where('sessions.user_id', '=', $id)
                ->get();


                //Historial de acciones por usuario para el modulo de database: Agregar/Editar-campos, cuencas, formaciones, pozos, intervalos, proyectos, escenarios y usuarios
                $basins = cuenca::select('cuencas.*')
                ->join('revisions', 'revisions.row_id', '=', 'cuencas.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "cuencas")
                ->distinct('cuencas.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                $fields = campo::select('campos.*')
                ->join('revisions', 'revisions.row_id', '=', 'campos.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "campos")
                ->distinct('campos.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                $formations = formacion::select('formaciones.*')
                ->join('revisions', 'revisions.row_id', '=', 'formaciones.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "formaciones")
                ->distinct('formaciones.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                $wells = pozo::select('pozos.*')
                ->join('revisions', 'revisions.row_id', '=', 'pozos.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "pozos")
                ->distinct('pozos.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                $producing_intervals = formacionxpozo::select('formacionxpozos.*')
                ->join('revisions', 'revisions.row_id', '=', 'formacionxpozos.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "formacionxpozos")
                ->distinct('formacionxpozos.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                $projects = proyecto::select('proyectos.*')
                ->join('revisions', 'revisions.row_id', '=', 'proyectos.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "proyectos")
                ->distinct('proyectos.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                $scenarios = escenario::select('escenarios.*')
                ->join('revisions', 'revisions.row_id', '=', 'escenarios.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "escenarios")
                ->distinct('escenarios.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                $users = user::select('users.*')
                ->join('revisions', 'revisions.row_id', '=', 'users.id')
                ->where('revisions.user', '=', $id)
                ->where('revisions.table_name', '=', "users")
                ->distinct('users.id')
                ->orderby('revisions.created_at', 'desc')
                ->get();

                dd([$sessionsbyuser, $basins, $fields, $formations, $wells, $producing_intervals, $projects, $scenarios, $users]);

                return view('statisticsbyuser', compact(['sessionsbyuser', 'basins', 'fields', 'formations', 'wells', 'producing_intervals', 'projects', 'scenarios', 'users']));
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
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
