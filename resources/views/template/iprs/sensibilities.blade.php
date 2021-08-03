@extends('layouts.general')
@section('title', 'IFDM Project')

@section('content')
<style src="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css"></style>
<div class="nav">
   <div class="panel panel-default" >
      <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod_s"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Sensitivities</h4></div>
      <div class="panel-body">
         <div id="Prod_s" class="panel-collapse collapse in">
            <div class="row">
               <div class="col-md-1">
                  <button type="button" class="btn btn-default" onclick="addSensibility();"><i class="fas fa-plus"></i></button>
               </div>
               <div class="col-md-11">
                  <div id="Sensitivities_list"></div>
               </div>
            </div>
            <button type="button" class="btn btn-danger pull-left" onclick="window.location.reload()">Clear All</button>
            <!-- <button type="button" class="btn btn-danger pull-left" onclick="limpiar();">Clear All</button> -->
            <button type="button" class="btn btn-primary pull-right" onclick="enviar();">Apply</button>
         </div>
      </div>
   </div>
   <div class="panel panel-default" id="results_sensibilities">
      <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Results</h4></div>
      <div class="panel-body">
         <div id="Prod" class="panel-collapse collapse in">
            <div id="grafica" tabindex="0">
            </div>
            <div class="row">
               <hr>
               <div class="col-md-12" id="div_tabla"></div>
               <br>
            </div>
         </div>
      </div>
   </div>

   {{-- <div class="panel panel-default" >
      <div class="panel-heading"><h4> <a data-parent="#accordion" data-toggle="collapse" href="#operative_point"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Operative Point</h4></div>
      <div class="panel-body">
         <div id="operative_point" class="panel-collapse collapse in">
            <div class="row">
               <div class="col-md-6">
                  <h3>Outflow Curve</h3>
                  <div id="tablaprueba"></div>
               </div>
               <div class="col-md-2">
                  <button class="btn btn-primary">Calculate</button>
               </div>
               <div class="col-md-2">
                  <div class="input-group">
                     <label for="current_operative_point">Current Operative Point</label>
                     <input type="text" class="form-control"  name="current_operative_point" id="current_operative_point">
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="input-group">
                     <label for="ideal_operative_point">Ideal Operative Point</label>
                     <input type="text" class="form-control"  name="ideal_operative_point" id="ideal_operative_point">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div> --}}
</div>

<div id="loading" style="display: none;"></div>
<div class="row">
   <div class="col-xs-12">
      <a href="{{ url('IPR/result/'.$IPR->id_escenario) }}" class="btn btn-default" role="button">Back To Results</a>
   </div>
</div>
@endsection

@section('Scripts')
@include('js/template/iprs_sensibilities')
@include('css/iprs_css')
@endsection