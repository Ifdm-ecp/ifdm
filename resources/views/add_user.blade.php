@extends('layouts.userSidebar')
@section('title', 'IFDM User')

@section('content')
@include('layouts/modal_error')

<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-body">
         <center>
            <h1> User Registration</h1>
         </center>

         <hr>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Name') ? 'has-error' : ''}}">
                  {!!Form::open(array('url' => 'registerCS', 'method' => 'post'))!!}
                  {!! Form::label('Name', 'User Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::text('Name',null, ['class' =>'form-control', 'id' => 'Name']) !!}
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('fullName') ? 'has-error' : ''}}">
                  {!! Form::label('fullName', 'Full Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::text('fullName',null, ['class' =>'form-control', 'id' => 'Charge']) !!}
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Company') ? 'has-error' : ''}}">
                  {!! Form::label('Company', 'Company') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::select('Company', $company->lists('name','id'),null, array('class'=>'form-control','placeholder' => '', 'id' => 'Company')) !!}
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
                  ],null, array('class'=>'form-control', 'id'=>'Profile')
                  ) !!}
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Charge') ? 'has-error' : ''}}">
                  {!! Form::label('Charge', 'Position') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::text('Charge',null, ['class' =>'form-control', 'id' => 'Charge']) !!}
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Gender') ? 'has-error' : ''}}">
                  {!! Form::label('Gender', 'Gender') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::select('Gender', [
                  ''=>'',
                  '0' => 'Male',
                  '1' => 'Female'
                  ],null, array('class'=>'form-control', 'id'=>'Gender')
                  ) !!}
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Password') ? 'has-error' : ''}}">
                  {!! Form::label('Password', 'Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::password('Password', ['class' =>'form-control', 'id' => 'Password']) !!}
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('PasswordC') ? 'has-error' : ''}}">
                  {!! Form::label('Password', 'Confirm Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::password('PasswordC', ['class' =>'form-control', 'id' => 'PasswordC']) !!}
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('Email') ? 'has-error' : ''}}">
                  {!! Form::label('Email', 'E-mail') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::text('Email',null, ['class' =>'form-control', 'id' => 'Email']) !!}
               </div>
            </div>
         </div>

         </br>

         <div class="row">
            <div class="col-xs-12">
               {!! Form::submit('Add' , array('class' => 'btn btn-primary pull-right')) !!}
               {!! Form::Close() !!}
            </div>
         </div>
      </div>
   </div>
</div>
</div>

@endsection

@section('Scripts')
    @include('js/add_user')
    @include('js/modal_error')
@endsection