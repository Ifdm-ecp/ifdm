@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
@include('layouts/modal_error')
<div class="panel panel-default">
   <div class="panel-heading">
      <h4>Project</h4>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('project') ? 'has-error' : ''}}">
               {!!Form::open(array('url' => 'ProjectCS', 'method' => 'post'))!!}
               {!! Form::label('project', 'Project Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('project',null, ['placeholder' => '', 'class' =>'form-control']) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('date') ? 'has-error' : ''}}">
               {!! Form::label('date', 'Date (DD/MM/YY)') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::date('date', \Carbon\Carbon::now(), ['class' =>'form-control', 'id'=>'date']); !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('PDescription') ? 'has-error' : ''}}">
               {!! Form::label('PDescription', 'Project Description') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::textarea('PDescription',null, ['placeholder' => '', 'class' =>'form-control', 'rows' => '2']) !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-xs-12">
            <p class="pull-right">
               {!! Form::submit('Save' , array('class' => 'btn btn-primary')) !!}
               <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
            </p>
            {!! Form::Close() !!}
         </div>
      </div>
   </div>
</div>

<div id="datea" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
            <p>Date Should Not Exceed The Current Date.</p>
            </small>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

@endsection


@section('Scripts')
  @include('js/add_project')
  @include('js/modal_error')
@endsection