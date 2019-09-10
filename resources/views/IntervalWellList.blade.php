@extends('layouts.basic')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h2>Producing Interval - <small>Pozo: {{$pozoN}}</small></h2>
</br>

<table class="table table-striped ">
   <tr>
      <th>Name</th>
      <th>Actions</th>
   </tr>
   @foreach  ($interval as $intervals)
   <tr>
      <td>{{ $intervals->nombre }}</td>
      <td>
         {!! Form::open(['method' => 'DELETE', 'route' => ['listIntervalWellCD.destroy', $intervals->id], 'id' => 'form'.$intervals->id ]) !!}
         <form class="form-inline">
            <a href="{{ URL::route('listIntervalC.edit', $intervals->id) }}" class="btn btn-warning" target="_blank">Manage</a>
            <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar();">Delete</button>
         </form>
         {!! Form::close() !!}
      </td>
   </tr>
   @endforeach
</table>

<p class="pull-right">
   <a href="{{ URL::route('AddProducingIntervalC.index', ['pozoId' => $pozo]) }}" class="btn btn-info" role="button"><span class="glyphicon glyphicon-plus"></span> Add new producing interval</a>
   <a href="{{ url('listWellC') }}" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-list-alt"></span> Finish</a>
   <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
</p>

{!! str_replace('/?', '?', $interval->render()) !!}  
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
  @include('js/list_intervalwell')
  @include('js/modal_error')
@endsection