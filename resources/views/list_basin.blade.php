@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h2>Basin List</h2>
</br>

<table class="table table-striped ">
   <tr>
      <th>Name</th>
      <th>Actions</th>
   </tr>
   @foreach  ($basin as $basins)
   <tr>
      <td>{{ $basins->nombre }}</td>
      <td>
         {!! Form::open(['method' => 'DELETE', 'route' => ['listBasinC.destroy', $basins->id], 'id' => 'form'.$basins->id  ]) !!}
         <form class="form-inline">
            <a href="{{ URL::route('listBasinC.edit', $basins->id) }}" class="btn btn-warning">Manage</a>
            <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: show_modal({{ $basins->id }});">Delete</button>
         </form>
         {!! Form::close() !!}
      </td>
   </tr>
   @endforeach
</table>

<a href="{!! url('database') !!}"><button class="btn btn-danger pull-right"  type="button" data-toggle="modal">Cancel</button></a>
{!! str_replace('/?', '?', $basin->render()) !!}  

<div class="modal fade" id="confirm_delete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Do You Want To Delete This Basin?</p>
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
  @include('js/list_basin')
  @include('js/modal_error')
@endsection