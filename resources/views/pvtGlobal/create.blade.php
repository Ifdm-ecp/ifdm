@extends('layouts.basic')
@section('title', 'Pvt Globals')

@section('content')
<?php
   if(!isset($_SESSION)) {
   session_start();
   }
?>
@include('layouts/modal_error')

{!!Form::open(['route' => ['pvt-global.store'], 'method' => 'POST', 'id' => 'formPvtGlobal'])!!}
    <div class="panel panel-default">
      <div class="panel-heading"><center><h2>Pvt Data Global</h2></center></div>
      <div class="panel-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
                 {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                 {!! Form::select('basin', $basin ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'basin')) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
                 {!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                 {!! Form::select('field', [] ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'field')) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
                 {!! Form::label('fromation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                 {!! Form::select('formacion_id', [] ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'formation')) !!}
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group {{$errors->has('descripcion') ? 'has-error' : ''}}">
                {!! Form::label('descripcion', 'Description') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('saturation_pressure') ? 'has-error' : ''}}">
                  {!! Form::label('saturation_pressure', 'Saturation Pressure') !!}
                  <div id = "alert_top"></div>
                  <div class="input-group">
                     {!! Form::text('saturation_pressure',null, ['placeholder' => 'Psi', 'class' =>'form-control']) !!}
                     <span class="input-group-addon" id="top-addon">Psi</span>
                  </div>
               </div>
            </div>
          </div>
          <br>

          <div class="row" >
            <div class="col-md-6">
              <div id="table_field_pvt" class="handsontable"></div>
            </div>
            </div>
            <br>
            <div class="row pull-right">
              <button class="btn btn-primary" id="plot" style="margin-right: 50px">Plot</button>
            </div>
            <div class="row">
              <div id="pvtField"></div>
            </div>

            {!! Form::hidden('pvt_table', '', ['class' => 'form-control']) !!}
          </div>
      </div>
    </div>


   <div class="row">
      <div class="col-xs-12">
         <p class="pull-right">
            {!! Form::submit('Save' , ['class' => 'btn btn-primary', 'id' => 'save']) !!}
            <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
         </p>
      </div>
   </div>

{!! Form::Close() !!}
@endsection

@section('Scripts')
    @include('js/modal_error')
    <link rel="stylesheet" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.js"></script>
    <script type="text/javascript" src="{{asset('js/basinFieldFormationSelect.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/pvtGlobal.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
          tablaPvt();
        });
    </script>
@endsection
