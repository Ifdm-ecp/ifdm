<?php

namespace App\Http\Controllers;
if(!isset($_SESSION)) {
     session_start();
}
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\proyecto;

class add_project_controller extends Controller
{
    /**
     * Devuelve la vista add_project para la inserción de proyectos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            return view('add_project');
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
    public function store(ProjectCreateRequest $request)
    {
        if (\Auth::check()) {

            //Validaciones para formulario
            $validator = Validator::make($request->all(), [
                'project' => 'required|unique:proyectos,nombre',
                'date' => 'required',
                'PDescription' => 'required',
             ]);

            if ($validator->fails()) {
                return redirect('ProjectC')
                    ->withErrors($validator)
                    ->withInput();
            }else{

                //Guardar datos generales de proyecto
                $project=new proyecto;
                $project->nombre = $request->input('project');
                $user = DB::table('users')->where('name', \Auth::User()->name)->value('id');
                $userC = DB::table('users')->where('name', \Auth::User()->name)->value('company');
                $project->usuario_id = $user;
                $project->fecha = $request->input('date');
                $project->descripcion = $request->input('PDescription');
                $project->compania = $userC;
                $project->save();

                return redirect('share_scenario');
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
        if (\Auth::check()) {
            $project = proyecto::find($id);
            return view('edit_project', ['project' => $project]);
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
    public function update(ProjectCreateRequest $request, $id)
    {
        if (\Auth::check()) {
            //Validaciones para formulario
            $validator = Validator::make($request->all(), [
                'project' => 'required|unique:proyectos,nombre,'.$id,
                'date' => 'required',
                'PDescription' => 'required',
             ]);

            if ($validator->fails()) {
                return redirect('ProjectC')
                    ->withErrors($validator)
                    ->withInput();
            }else{
                //Editar valores generales del formulario
                $project=proyecto::find($id);
                $project->Nombre = $request->input('project');
                $user = DB::table('users')->where('name', \Auth::User()->name)->value('id');
                $userC = DB::table('users')->where('name', \Auth::User()->name)->value('company');
                $project->usuario_id = $user;
                $project->fecha = $request->input('date');
                $project->descripcion = $request->input('PDescription');
                $project->compania = $userC;
                $project->save();
                
                return redirect('share_scenario');
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
        //
    }
}
