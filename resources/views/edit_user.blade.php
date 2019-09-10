@extends('layouts.userSidebar')
@section('title', 'IFDM User')

@section('content')
@include('layouts/modal_error')

<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-body">
         <center>
            <h1> User edit - <small>{{$user->name}}</small></h1>
         </center>

         <hr>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Company') ? 'has-error' : ''}}">
                  {!!Form::model($user, array('route' => array('UserC.update', $user->id), 'method' => 'PATCH'), array('role' => 'form'))!!}
                  {!! Form::label('Company', 'Company') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::select('Company', $company->lists('name','id'), $user->company, array('class'=>'form-control','placeholder' => '', 'id' => 'Company')) !!}
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Profile') ? 'has-error' : ''}}">
                  {!! Form::label('Profile', 'Profile') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::select('Profile', [
                  ''=>'',
                  '0' => 'System administrator',
                  '1' => 'Company administrator',
                  '2' => 'Engineer'
                  ],$user->office, array('class'=>'form-control', 'id'=>'Profile')
                  ) !!}
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Gender') ? 'has-error' : ''}}">
                  {!! Form::label('Gender', 'Gender') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::select('Gender', [
                  ''=>'',
                  '0' => 'Male',
                  '1' => 'Female'
                  ],$user->gender, array('class'=>'form-control', 'id'=>'Gender')
                  ) !!}
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Password') ? 'has-error' : ''}}">
                  {!! Form::label('Password', 'Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::password('password', ['class' =>'form-control', 'id' => 'Password']) !!}
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('PasswordC') ? 'has-error' : ''}}">
                  {!! Form::label('Password', 'Confirm Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::password('password_confirmation', ['class' =>'form-control', 'id' => 'PasswordC']) !!}
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Email') ? 'has-error' : ''}}">
                  {!! Form::label('Email', 'E-mail') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::text('Email',$user->email, ['class' =>'form-control', 'id' => 'Email']) !!}
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Charge') ? 'has-error' : ''}}">
                  {!! Form::label('Charge', 'Position') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::text('Charge',$user->charge, ['class' =>'form-control', 'id' => 'Charge']) !!}
               </div>
            </div>
         </div>

         </br>

         <div class="row pull-right">
            <div class="col-xs-12">
               <a href="{!! URL::route('UserC.index') !!}"><button class="btn btn-default"  type="button" data-toggle="modal">Back</button></a>
               {!! Form::submit('Next' , array('class' => 'btn btn-primary')) !!}
               {!! Form::Close() !!}
            </div>
         </div>
      </div>
   </div>
</div>
</div>

@endsection


@section('Scripts')
    @include('js/edit_user')
    @include('js/modal_error')
@endsection