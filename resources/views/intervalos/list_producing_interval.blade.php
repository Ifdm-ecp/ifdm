@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h2>Producing Interval List</h2>
<hr>

<div class="row">
   <div class="col-md-6">
      <div class="form-group">
         {!! Form::label('field', 'Field') !!}
         {!! Form::select('field', $basin->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'field')) !!}
      </div>
   </div>
   <div class="col-md-6">
      <div class="form-group">
         {!! Form::label('welll', 'Well') !!}
         {!! Form::select('well', array(),null, array('placeholder' => '','class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'well')) !!}
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-6">
      <div class="form-group">
         {!! Form::label('formationl', 'Formation') !!}
         {!! Form::select('formation', $formacion->lists('nombre','id'),null, array('placeholder' => '','class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'formation')) !!}
      </div>
   </div>
   <div class="col-md-6">
      <div class="form-group">
         {!! Form::label('interval', 'Producing Interval') !!}
         {!! Form::select('interval', array(),null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'interval')) !!}
      </div>
   </div>
</div>

<hr>
<br> 

<div name="table" id="table">
   <table class="table table-striped ">
      <tr>
         <th>Name</th>
         <th>Actions</th>
      </tr>
      @foreach  ($interval as $intervals)
      <tr>
         <td>{{ $intervals->nombre }}</td>
         <td>
            {!! Form::open(['method' => 'DELETE', 'route' => ['listIntervalC.destroy', $intervals->id], 'id' => 'form'.$intervals->id]) !!}
            <form class="form-inline">
               <a href="{{ URL::route('listIntervalC.edit', $intervals->id) }}" class="btn btn-warning">Manage</a>
               <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$intervals->id}});">Delete</button>
            </form>
            {!! Form::close() !!}
         </td>
      </tr>
      @endforeach
   </table>
   {!! str_replace('/?', '?', $interval->render()) !!}  
</div>

<a href="{!! url('database') !!}"><button class="btn btn-danger pull-right"  type="button" data-toggle="modal">Cancel</button></a>

<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Do You Want To Delete This Producing Interval?</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirm">Ok</button>
         </div>
      </div>
   </div>
</div>
@endsection


@section('Scripts')
    @include('js/list_producing_interval')
    @include('js/modal_error')
@endsection