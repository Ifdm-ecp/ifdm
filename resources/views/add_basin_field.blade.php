@extends('layouts.basic')
@section('title', 'IFDM Database')
@section('content')
@include('layouts/modal_error')

<div class="panel panel-default">
   <div class="panel-heading">
      <h2>Add Basin</h2>
   </div>
   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('basin_name') ? 'has-error' : ''}}">
               {!!Form::open(array('url' => 'AddDataBasinS', 'method' => 'post'))!!}
               {!! Form::label('name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('basin_name', '', array('class' => 'form-control', 'id' => 'basin_name', 'name' => 'basin_name')) !!}
            </div>
         </div>
         <div class="col-md-6" style="display:none;">
            {!! Form::label('coordinates', 'Coordinates') !!} </br>
            <a href="#button_basin_coordinates" class="toggle btn btn-default btn-block" id="tooltip_basin_coordinates"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Add Coordinates</a>
            <div id="button_basin_coordinates" class="well hidden">
               <div id="table_basin_coordinates" style="overflow: scroll" class="handsontable"></div>
               {!! Form::hidden('basin_coordinates', '', array('class' => 'form-control', 'id' => 'basin_coordinates', 'name' => 'basin_coordinates')) !!}
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xs-12">
            <p class="pull-right">
               {!! Form::submit('Save' , array('class' => 'check_basin check_coordinates btn btn-primary')) !!}
               <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
            </p>
            {!! Form::Close() !!}
         </div>
      </div>
   </div>
</div>

<div class="panel panel-default">
   <div class="panel-heading">
      <h2>Add Field</h2>
   </div>
   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('basin_for_field') ? 'has-error' : ''}}">
               {!!Form::open(array('url' => 'AddDataFieldS', 'method' => 'post'))!!}
               {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('basin_for_field', $basin->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('field_name') ? 'has-error' : ''}}">
               {!! Form::label('name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('field_name', '', array('class' => 'form-control')) !!}
            </div>
         </div>
      </div>
      <br>
      <div class="row">
         <div class="col-md-6">
            {!! Form::label('coordinates', 'Coordinates') !!}
            <a href="#button_field_coordinates" class="toggle btn btn-default btn-block" id="tooltip_coordinates_field"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Add Coordinates</a>
            <div id="button_field_coordinates" class="well hidden">
               <div id="table_field_coordinates" style="overflow: scroll" class="handsontable"></div>
               {!! Form::hidden('field_coordinates', '', array('class' => 'form-control', 'id' => 'field_coordinates', 'name' => 'field_coordinates')) !!}
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-xs-12">
            <p class="pull-right">
               {!! Form::submit('Save' , array('class' => 'check_field check_field_pvt btn btn-primary')) !!}
               <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
            </p>
            {!! Form::Close() !!}
         </div>
      </div>
   </div>
</div>

@endsection

@section('Scripts')
    <script src="https://nextgen.pl/_/scroll/dist/jquery.handsontable.full.js"></script>
    <link rel="stylesheet" media="screen" href="https://nextgen.pl/_/scroll/dist/jquery.handsontable.full.css">
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    @include('js/add_basin_field')
    @include('js/modal_error')
@endsection