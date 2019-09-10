<?php

namespace App\Http\Controllers;
use DB;
use App\cuenca;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\registerRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\MessageBag;
use App\User;
use App\company;

class add_user_controller extends Controller
{
    /**
     * Despliega la vista add_user con la información de las compañías para popular el select.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            if(\Auth::User()->office==0){
                $company = company::all();
                return view('add_user', compact(['company']));
            }else{
                return view('permission');
            }
        }else{
            return view('loginfirst');
        }
    }

    public function changepassword($value='')
    {
        if (\Auth::check()) {
            // dd(\Auth::User());
            $company = company::all();
            return view('template.users_management.change_password', compact(['company']));
        }else{
            return view('loginfirst');
        }
    }

    public function update_password(Request $request)
    {
        /* Validaciones para formulario */
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'Password' => 'required|same:PasswordC',
            'PasswordC' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect('changepassword')
            ->withErrors($validator)
            ->withInput();
        }else{
            $user = User::find($request->id);
            $user->password = bcrypt($request->Password);
            $user->save();

            return redirect('UserC');
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
    public function store(registerRequest $request)
    {
        if (\Auth::check()) {
            if(\Auth::User()->office==0){

                //Validaciones para formulario
                $validator = Validator::make($request->all(), [
                    'Name' => 'required|unique:users,name',
                    'Charge' => 'required',
                    'fullName' => 'required',
                    'Company' => 'required',
                    'Profile' => 'required',
                    'Gender' => 'required',
                    'Password' => 'required|same:PasswordC',
                    'PasswordC' => 'required',
                    'Email' => 'required|email',
                ]);


                if ($validator->fails()) {
                    return redirect('registerC')
                    ->withErrors($validator)
                    ->withInput();
                }else{

                    //Guardar informacion general del usuario
                    $User=new User;
                    $User->name = $request->input('Name');
                    $User->fullName = $request->input('fullName');
                    $User->charge = $request->input('Charge');
                    $User->company = $request->input('Company');
                    $User->office = $request->input('Profile');
                    $User->gender = $request->input('Gender');
                    $User->password = bcrypt($request->input('Password'));
                    $User->email = $request->input('Email');
                    $User->remember_token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 60);
                    $User->save();
                    return redirect('UserC');
                }
            }else{
                return view('permission');
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