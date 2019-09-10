@extends('layouts.editData')
@section('title', 'IFDM Database')
@section('content')
@include('layouts/modal_error')

<h2>Filtration Function List</h2>

<hr>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('basin_l', 'Basin') !!}
            {!! Form::select('basin', $basin->lists('nombre','id'),null, array('placeholder' => '','class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'basin')) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('field_l', 'Field') !!}
            {!! Form::select('field', array(),null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'field')) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('formation_l', 'Formation') !!}
            {!! Form::select('formation', array(),null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'formation')) !!}
        </div>
    </div>
</div>  

<hr>
<br> 

<div name="table" id="table">
    <table class="table table-striped ">
        <tr>
            <th>Filtration Function</th>
            <th>Options</th>
        </tr>
        @foreach  ($filtration_functions as $filtration_function)
        <tr>
            <td>{{ $filtration_function->name }}</td>
            <td>
                {!! Form::open(['method' => 'DELETE', 'route' => ['filtration_function.destroy', $filtration_function->id], 'id' => 'form'.$filtration_function->id ]) !!}
                <form class="form-inline">
                    <a href="{{ URL::route('filtration_function.edit', $filtration_function->id) }}" class="btn btn-warning">Manage</a>
                    <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$filtration_function->id}});">Delete</button>
                </form>
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </table>
    {!! str_replace('/?', '?', $filtration_functions->render()) !!} 
</div>


<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Do You Want To Delete This Filtration Function?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm">Ok</button>
            </div>
        </div>
    </div>
</div>

<a href="{!! url('database') !!}"><button class="btn btn-danger pull-right"  type="button" data-toggle="modal">Cancel</button></a>


@endsection

@section('Scripts')
  @include('js/list_filtration_function')
  @include('js/modal_error')
@endsection