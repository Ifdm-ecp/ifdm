@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<div class="panel panel-default">
   <div class="panel-heading">
      <h2>Field - <small>{{$field->nombre}}</small></h2>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('basin_for_field') ? 'has-error' : ''}}">
               {!!Form::model($field, array('route' => array('listFieldC.update', $field->id), 'method' => 'PATCH'), array('role' => 'form'))!!}
               {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('basin_for_field', $basin->lists('nombre','id'),null, array('placeholder' => '','class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'basin_for_field')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('field_name') ? 'has-error' : ''}}">
               {!! Form::label('name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('field_name', $field->nombre, array('class' => 'form-control')) !!}
            </div>
         </div>
      </div>
      <br>

      <div class="row">
         <div class="col-md-6">
            {!! Form::label('coordinates', 'Coordinates') !!}
            <a href="#button_field_coordinates" class="toggle btn btn-default btn-block" id="tooltip_coordinates_field"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Edit Coordinates</a>
            <div id="button_field_coordinates" class="well hidden">
               <div id="table_field_coordinates" style="overflow: scroll" class="handsontable"></div>
               {!! Form::hidden('field_coordinates', '', array('class' => 'form-control', 'id' => 'field_coordinates', 'name' => 'field_coordinates')) !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-xs-12">
            {!! Form::hidden('id', $field->id) !!}
            <p class="pull-right">
               {!! Form::submit('Next' , array('class' => 'btn btn-primary', 'OnClick'=>'javascript: show_error();')) !!}
               <a href="{!! url('listFieldC') !!}" class="btn btn-danger" role="button">Cancel</a>
            </p>
            {!! Form::Close() !!}
         </div>
      </div>
   </div>
</div>
@endsection


@section('Scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.js"></script>
  <link rel="stylesheet" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.css">
  @include('js/edit_field')
  @include('js/modal_error')
@endsection