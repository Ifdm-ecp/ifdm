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
<div id="sticky" style="position: relative;">
   <center><b>Scenario: </b>{!! $statistical->escenario->nombre !!} </br>
      Basin: {!! $statistical->escenario->cuenca->nombre !!} - 
      Field: {!! $statistical->escenario->campo->nombre !!} - 
      {{-- Producing interval: {!!  $statistical->escenario->formacionxpozo->nombre !!} -  --}}
      Well: {!!  $statistical->escenario->pozo->nombre !!} - 
      User: {!! $statistical->escenario->user->name !!}
   </center>
</div>

</br>
<div class="nav">
   <div class="tab">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" id="ocultarSave" href="#SB" onclick="switchTab()">Statistical DataBase</a></li>
         <li><a data-toggle="tab" class="ocultarCalculate" href="#MS" id="MS_C" onclick="switchTab()">Mineral Scales</a></li>
         <li><a data-toggle="tab" class="ocultarCalculate" href="#FB" id="FB_C" onclick="switchTab()">Fine Blockage</a></li>
         <li><a data-toggle="tab" class="ocultarCalculate" href="#OS" id="OS_C" onclick="switchTab()">Organic Scales</a></li>
         <li><a data-toggle="tab" class="ocultarCalculate" href="#RP" id="RP_C" onclick="switchTab()">Relative Permeability</a></li>
         <li><a data-toggle="tab" class="ocultarCalculate" href="#ID" id="ID_C" onclick="switchTab()">Induced Damage</a></li>
         <li><a data-toggle="tab" class="ocultarCalculate" href="#GD" id="GD_C" onclick="switchTab()">Geomechanical Damage</a></li>
      </ul>
   </div>
</div>
{!!Form::model($statistical, ['route' => [$complete == true ? 'completeMultiparametric.update' : 'statistical.update_', $statistical->id], 'method' => 'POST', 'id' => 'multiparametricStatisticalForm'])!!}
  <input type="hidden" name="id_scenary" id="id_scenary" value="{{ !empty($duplicateFrom) ? $duplicateFrom : $statistical->escenario->id }}">
  @if (!empty($duplicateFrom))
   <input type="hidden" name="duplicate" id="duplicate" value="{{ $duplicateFrom }}">
  @endif

  <input type="hidden" name="calculate" value="false">
    <div class="tab-content">
      <br>
      @include('multiparametricAnalysis.statistical.cuerpo.sb')
      @include('multiparametricAnalysis.statistical.cuerpo.ms')
      @include('multiparametricAnalysis.statistical.cuerpo.fb')
      @include('multiparametricAnalysis.statistical.cuerpo.os')
      @include('multiparametricAnalysis.statistical.cuerpo.rp')
      @include('multiparametricAnalysis.statistical.cuerpo.id')
      @include('multiparametricAnalysis.statistical.cuerpo.gd')
   </div>
   <div class="row">
      {!! Form::hidden('only_s', '', array('id' => 'only_s')) !!}
      <div class="col-md-12 scenario-buttons">
         <div align="left">
            <button type="button" class="btn btn-success" onclick="verifyMultiparametric('save');" id="save_calc">Save</button>
            <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
         </div>
         <div align="right">
            <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
            <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
            <button type="button" class="btn btn-primary" style="display: none" onclick="verifyMultiparametric('run');" id="run_calc">Run</button>
         </div>
      </div>
   </div>
{!! Form::Close() !!}

<div class="modal fade" id="errors" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-center">P10 and P90 can not be the same.</h4>
         </div>
         <div class="modal-body">
            <ul></ul>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

@endsection

@include('css/add_multiparametric')

@section('Scripts')
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
   @include('multiparametricAnalysis.statistical.cuerpo.createJs')
   @include('multiparametricAnalysis.statistical.cuerpo.editJs')
   @include('js/frontend_validator')
   @include('js/frontend_rules/multiparametric_statistical')
   @include('js/modal_error')
   @include('js/modal_error_frontend')
   @include('css/modal_error_frontend')
   <script type="text/javascript">
      $(document).ready(function() {
         validationFields();
         @if($statistical->p10_ms1 == null || $statistical->p10_fb1 == null || $statistical->p10_os1 == null || $statistical->p10_id1 == null || $statistical->p10_gd1 == null)
         multiparametricoStatistical();
         @endif
         cargarCamposBBDD();
         cargarAvailables();
         loadSubparametersHistoricalByWell();
      });
   </script>
@endsection