@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h2>Field list</h2>
<hr>

<div class="row">
   <div class="col-md-6">
      <div class="form-group">
         {!! Form::label('Basin', 'Basin') !!}
         {!! Form::select('basin', $basin->lists('nombre','id'),null, array('placeholder' => '','class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'basin')) !!}
      </div>
   </div>
   <div class="col-md-6">
      <div class="form-group">
         {!! Form::label('Field', 'Field') !!}
         {!! Form::select('field', array(),null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'field')) !!}
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
      @foreach  ($field as $fields)
      <tr>
         <td>{{ $fields->nombre }}</td>
         <td>
            {!! Form::open(['method' => 'DELETE', 'route' => ['listFieldC.destroy', $fields->id], 'id' => 'form'.$fields->id ]) !!}
            <div class="form-inline">
               <a href="{{ URL::route('listFieldC.edit', $fields->id) }}" class="btn btn-warning">Manage</a>
               <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{ $fields->id}});">Delete</button>
            </div>
            </form>
         </td>
      </tr>
      @endforeach
   </table>
   {!! str_replace('/?', '?', $field->render()) !!}  
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
            <p>Do You Want To Delete This Field?</p>
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
  @include('js/modal_error')
  @include('js/list_field')
@endsection