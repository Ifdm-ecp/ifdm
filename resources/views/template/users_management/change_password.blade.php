@extends('layouts.userSidebar')
@section('title', 'IFDM User')

@section('content')
@include('layouts/modal_error')

<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-body">
         <center>
            <h1> Change Password</h1>
         </center>

         <hr>

         {!!Form::open(array('url' => url('changepassword/update'), 'method' => 'post'))!!}

         <div class="row">
            <div class="col-md-6">
               <input type="hidden" name="id" id="id" value="{{ Auth::id() }}">
               <div class="form-group {{$errors->has('Password') ? 'has-error' : ''}}">
                  {!! Form::label('Password', 'Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::password('Password', ['placeholder' => 'Password', 'class' =>'form-control', 'id' => 'Password']) !!}
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('PasswordC') ? 'has-error' : ''}}">
                  {!! Form::label('Password', 'Confirm Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  {!! Form::password('PasswordC', ['placeholder' => 'Confirm Password', 'class' =>'form-control', 'id' => 'PasswordC']) !!}
               </div>
            </div>
         </div>

      </br>

      <div class="row">
         <div class="col-xs-12">
            {!! Form::submit('Change' , array('class' => 'btn btn-primary pull-right')) !!}
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