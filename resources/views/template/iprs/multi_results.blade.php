@extends('layouts.ProjectGeneral')

@section('title', 'IFDM Project')

@section('content')

<?php if(!isset($_SESSION)) { session_start(); } ?>

@if (session()->has('mensaje'))

<div id="myModal" class="modal fade" hidden>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Success</h4>
      </div>
      <div class="modal-body">
        <p class="text-danger">
          <small>
            <p>{{ session()->get('mensaje') }}</p>
          </small>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

@endif
@if (count($errors) > 0)

<div id="myModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Error</h4>
      </div>
      <div class="modal-body">
        <p class="text-danger">
          <small>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </small>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

@endif
<div onload="multiparametrico();">
  <div id="sticky-anchor"  class="col-md-6"></div>
  <div id="sticky" tabindex='1' ><center>Scenario: {!! $scenary->nombre !!} </br> Basin: {!! $basin->nombre !!} - Field: {!! $field->nombre !!} - Well: {!!  $well->nombre !!} </br> User: {!! $user->fullName !!} </center></div>
  <br>
  <div class="panel panel-default" >
    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Results</h4></div>
    <div class="panel-body">
      <div id="Prod" class="panel-collapse collapse in">
        <div id="grafica">
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <center> 
                {!! Form::label('valor', 'Total Skin: ') !!}
                <!--{!! Form::label('valor', ($skin == 0 ? "0" : round($skin,2))) !!}-->  
                <b>{!! round($skin,2) !!}</b>
              </center>                         
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="loading" style="display: none;"></div>  
  <div class="row">
    <div class="col-xs-12">
      <a href="{!! route('ipr.edit',$scenary->id) !!}" class="btn btn-default" role="button">Edit</a>
    </div>
  </div>

  @endsection
  @section('Scripts')
  @include('js/template/iprs_multi_results')
  @endsection
  @include('css/iprresults_css')