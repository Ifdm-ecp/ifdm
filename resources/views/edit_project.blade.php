@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
@include('layouts/modal_error')

<div class="panel panel-default">
   <div class="panel-heading">
      <h4>Project - <small>{{$project->nombre}}</small></h4>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('project') ? 'has-error' : ''}}">
               {!!Form::model($project, array('route' => array('ProjectC.update', $project->id), 'method' => 'PATCH'), array('role' => 'form'))!!}
               {!! Form::label('project', 'Project Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('project',$project->nombre, ['placeholder' => '', 'class' =>'form-control']) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('date') ? 'has-error' : ''}}">
               {!! Form::label('date', 'Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::date('date', $project->fecha, ['class' =>'form-control', 'id'=>'date']); !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('PDescription') ? 'has-error' : ''}}">
               {!! Form::label('PDescription', 'Project Description') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::textarea('PDescription',$project->descripcion, ['placeholder' => '', 'class' =>'form-control', 'rows' => '2']) !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-xs-12">
            {!! Form::hidden('id', $project->id) !!}
            <p class="pull-right">
               {!! Form::submit('Next', array('class' => 'btn btn-primary', $project->id)) !!}
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
  @include('js/edit_project')
  @include('js/modal_error')
@endsection