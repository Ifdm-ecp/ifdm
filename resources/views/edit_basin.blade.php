@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<div class="panel panel-default">
   <div class="panel-heading">
      <h2>Basin - <small>{{$basin->nombre}}</small></h2>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('basin_name') ? 'has-error' : ''}}">
               {!!Form::model($basin, array('route' => array('listBasinC.update', $basin->id), 'method' => 'PATCH'), array('role' => 'form'))!!}
               {!! Form::label('name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('basin_name', $basin->nombre, array('class' => 'form-control')) !!}
            </div>
         </div>

         <div class="col-md-6" style="display:none;">
            {!! Form::label('coordinates', 'Coordinates') !!} </br>
            <a href="#button_basin_coordinates" class="toggle btn btn-default btn-block" id="tooltip_basin_coordinates"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Edit Coordinates</a>
            <div id="button_basin_coordinates" class="well hidden">
               <div id="table_basin_coordinates" style="overflow: scroll" class="handsontable"></div>
               {!! Form::hidden('basin_coordinates', '', array('class' => 'form-control', 'id' => 'basin_coordinates', 'name' => 'basin_coordinates')) !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-xs-12">
            {!! Form::hidden('id', $basin->id) !!}
            <p class="pull-right">
               {!! Form::submit('Next', array('class' => 'btn btn-primary', $basin->id, 'OnClick'=>'javascript: Validate();')) !!}
               <a href="{!! url('listBasinC') !!}" class="btn btn-danger" role="button">Cancel</a>
            </p>
            {!! Form::Close() !!}
         </div>
      </div>
   </div>
</div>
@endsection

@section('Scripts')
  @include('js/modal_error')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.js"></script>
  <link rel="stylesheet" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.css">
  @include('js/edit_basin')
@endsection