<?php

namespace App\Http\Controllers;
if(!isset($_SESSION)) {
 session_start();
}
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\company;
use DB;
use Validator;
use App\Http\Requests\updateRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;

class list_user_controller extends Controller
{
    /**
     * Despliega la vista list_user con el listado de usuarios de la base de datos para el select. 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office==0){
                $users = DB::table('users')->paginate(15);
                return view('list_user', ['users' => $users]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    public function getUsersTable(Request $request)
    {
        $users = DB::table('users')->get();
        $total = count($users);
        foreach ($users as $k => $v) {
            $arr = [];
            $arr[] = $v->name;
            if($v->office == 0) {
                $arr[] = "System administrator";
            } elseif($v->office == 1) {
                $arr[] = "Company administrator";
            } elseif($v->office == 2) {
                $arr[] = "Engineer";
            }

            $arr[] = $v->email;
            $url_delete = url('UserC.destroy', $v->id);
            $v->actions = '<form class="form-inline" method="DELETE" action="$url_delete" id="form'.$v->id.'">';
            $v->actions .= '<form class="form-inline"><a href="'.route('UserC.show', $v->id).'" class="btn btn-info">Show</a>';
            $v->actions .= '<a href="'.route('UserC.edit', $v->id).'" class="btn btn-warning">Update</a>';
            if(\Auth::User()->id != $v->id) {
                $v->actions .= '<button class="eliminar btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar('.$v->id.');">Delete</button>';
            }
            $v->actions .= '</form>';
            $arr[] = $v->actions;
            $users[$k] = $arr;
        }

        return json_encode(["data" => $users]);
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
     * Despliega la vista show_user con la información específica de un sólo usuario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->profile==0){
                $user = User::find($id);

                if (is_null($user)) {
                    return 'No existe';
                }

                return view('show_user', ['user' => $user]);
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    /**
     * Despliega la vista edit_user con la información de un usuario específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office==0){
                $user = User::find($id);
                $company = company::all();

                return view('edit_user', ['user' => $user, 'company' => $company]);
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
    public function update(updateRequest $request, $id)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office==0){
               
                    //Editar valores generales de usuario
                    $User = User::find($id);
                    $User->charge = $request->input('Charge');
                    $User->company = $request->input('Company');
                    $User->office = $request->input('Profile');
                    $User->gender = $request->input('Gender');
                    if ($request->input('password') != '' | $request->input('password') != null) {
                        $User->password = bcrypt($request->input('Password'));
                    }
                    $User->email = $request->input('Email');
                    $User->remember_token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
                    $User->save();
                        

                    return redirect('UserC');
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
            if(\Auth::User()->office==0){
                User::destroy($id);
                $data['users'] = User::all();
                
                return redirect('UserC');
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }
}