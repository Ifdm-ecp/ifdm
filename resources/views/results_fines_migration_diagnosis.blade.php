@extends('layouts.ProjectGeneral')  
@section('title', 'IFDM Project')

@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
?>

@include('layouts/modal_error')
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
<center>
      {!! Form::label('Scenario: ') !!} {!! Form::label('scenary_name', $scenary->nombre) !!} {!! Form::label(' - Basin: ') !!} {!! Form::label('basin_name', $cuenca->nombre) !!} {!! Form::label(' - Field: ') !!} {!! Form::label('field_name', $campo->nombre) !!} {!! Form::label(' - Producing interval: ') !!} {!! Form::label('interval_name', $formacion->nombre) !!} {!! Form::label(' - Well: ') !!} {!! Form::label('well_name', $pozo->nombre) !!} {!! Form::label(' - User: ') !!} {!! Form::label('user_name', $user->fullName) !!}
   </center>
</div>
<p></p>

@if(!$fines_d_diagnosis->status_wr)
<h2 align="center">Fines Migration Results</h2>

<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#deposited_asphaltenes_results">Fines Results</a></li>
         <li><a data-toggle="tab" href="#skin_results">Skin Results</a></li>
      </ul>

      <div class="row tab-content">
         <div class="tab-pane active" id="deposited_asphaltenes_results">
            <div class="panel-body">
              <div class="col-md-12">
                <div class="row">
                  <label>Please choose one or more dates for plotting the results</label>
                  <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="date_select" multiple>
                    <option selected disabled>Dates</option>
                      @foreach ($dates_data as $date)
                          <option value = "{!! $date !!}">{!! $date !!}</option>
                      @endforeach
                  </select>
                </div>
                <br>
                <div class="row">
                  <div class="panel panel-default">
                     <div class="panel-heading"><b>Permeability</b></div>
                     <div class="panel-body">
                       <div id="permeability_chart"></div>
                     </div>
                  </div>
                </div>
                <div class="row">
                  <div class="panel panel-default">
                     <div class="panel-heading"><b>Fines Concentration</b></div>
                     <div class="panel-body">
                       <div id="co_chart"></div>
                     </div>
                  </div>
                </div>
              </div>
              <br>
            </div>
         </div>

         <div class="tab-pane" id="skin_results">
          <div class="panel-body">
            <div class="panel panel-default">
              <div class="panel-heading"><b>Damage Radius</b></div>
              <div class="panel-body">
                <div id="damage_radius_chart"></div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading"><b>Skin</b></div>
              <div class="panel-body">
                <div id="skin_chart"></div>
              </div>
            </div>
          </div>
         </div>
      </div>
   </div>
</div>
@else
<div class="jumbotron">
  <center>
    <span>Run has not been executed, there is no data to show.</span>
  </center>
</div>
@endif

<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
          <a href="{{ URL::to('finesMigrationDiagnosis/' . $scenaryId . '/edit') }}" class="btn btn-warning" role="button">Edit</a>
         <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Exit</a>
      </p>
   </div>
</div>

@endsection

@include('css/results_fines_migration_diagnosis')

@section('Scripts')
    @include('js/results_fines_migration_diagnosis')
@endsection