@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h2>Scenario List</h2>
</br>

<table class="table table-striped ">
   <tr>
      <th>Name</th>
      <th>Actions</th>
   </tr>
   @foreach  ($scenary as $scenarya)
   <tr>
      <td>{{ $scenarya->nombre }}</td>
      <td>
         {!! Form::open(['method' => 'DELETE', 'route' => ['DeleteScenary.destroy', $scenarya->id],  'id' => 'form'.$scenarya->id  ]) !!}
         <form class="form-inline">
            <a href="{{ URL::route('ScenaryC.edit', $scenarya->id) }}" class="btn btn-warning">Manage</a>
            <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{ $scenarya->id }});">Delete</button>
         </form>
         {!! Form::close() !!}
      </td>
   </tr>
   @endforeach
</table>

<p class="pull-right">
   <a href="{!! url('DeleteProject') !!}" class="btn btn-danger" role="button">Cancel</a>
</p>

{!! str_replace('/?', '?', $scenary->render()) !!}  

<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Do You Want To Delete This Scenary?</p>
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
    @include('js/list_scenario')
    @include('js/modal_error')
@endsection