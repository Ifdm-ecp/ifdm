@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
?>
@include('layouts/modal_error')


</br>

<h2 align="center">Precipitated Asphaltene Analysis Results</h2>
<div class="nav">
  @if(!$asphaltenes_d_precipitated_analysis->status_wr)
  <div class="tabbable">
    <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
      <li class="active"><a data-toggle="tab" href="#saturation_results" id="saturation_results_tag">Saturation Results</a></li>
      <li><a data-toggle="tab" href="#solid_results" id="solid_results_tag">Solid Results</a></li>
      <li><a data-toggle="tab" href="#onset_pressure_results" id="onset_pressure_results_tag">Onset Pressure Results</a></li>
    </ul>
    <div class="tab-content">
      <div id="saturation_results" class="tab-pane active">
        <div class="col-md-12">
          <br>
          <div class="row">
            <div class="panel panel-default">
               <div class="panel-heading"><b>Saturation Results</b></div>
               <div class="panel-body">
                 <div id="saturation_results_chart"></div>
               </div>
            </div>
          </div>
        </div>
      </div>
      <div id="solid_results" class="tab-pane">
        <div class="col-md-12">
          <br>
          <div class="row">
            <div class="panel panel-default">
               <div class="panel-heading"><b>Solid Results</b></div>
               <div class="panel-body">
                <div id="solid_a_results_chart"></div> 
               </div>
            </div>
          </div>
        </div>
      </div>
      <div id="onset_pressure_results" class="tab-pane">
        <div class="col-md-12">
          <br>
          <div class="row">
            <div class="panel panel-default">
               <div class="panel-heading"><b>Onset Pressure Results</b></div>
               <div class="panel-body">
                 <div id="onset_pressure_chart"></div>
               </div>
            </div>
          </div>
          <div class="row">
            <div class="panel panel-default">
               <div class="panel-heading"><b>Asphaltenes Soluble Fraction</b></div>
               <div class="panel-body">
                 <div id="asphaltenes_soluble_fraction_chart"></div>
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
</div>
</br>
<br>
{!! Form::hidden('scenaryId', $scenaryId, array('id' => 'scenaryId')) !!}
<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
        @if(!$asphaltenes_d_precipitated_analysis->status_wr)
          <a href="{!! url('run_asphaltene_diagnosis',array('id'=>$scenaryId)) !!}" class="maximize btn btn-primary" role="button">Run Asphaltene Diagnosis</a>
         <a href="{!! url('run_asphaltene_stability',array('id'=>$scenaryId)) !!}" class="maximize btn btn-primary" role="button">Run Asphaltene Stability Analysis</a>
         @endif
         <a href="{!! action('add_precipitated_asphaltenes_analysis_controller@edit',$scenaryId) !!}" class="maximize btn btn-warning" role="button">Edit</a>
      </p>
   </div>
</div>

<div class="row pull-right">
  <div class="col-xs-12">
    <a href="{!! action('add_precipitated_asphaltenes_analysis_controller@result',$scenaryId) !!}" class="btn btn-danger" role="button">Cancel</a>
  </div>
</div>
@endsection

@include('css/add_multiparametric')

@section('Scripts')
   @include('js/modal_error')
   @if(!$asphaltenes_d_precipitated_analysis->status_wr)
   @include('js/results_precipitated_asphaltenes_analysis')
   @endif
@endsection
