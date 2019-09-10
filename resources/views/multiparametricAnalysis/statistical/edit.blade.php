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
   <center><b>Scenario: </b>{!! $statistical->escenario->nombre !!} </br> Basin: {!! $statistical->escenario->cuenca->nombre !!} - Field: {!! $statistical->escenario->campo->nombre !!} - Producing interval: {!!  $statistical->escenario->formacionxpozo->nombre !!} - Well: {!!  $statistical->escenario->pozo->nombre !!} - User: {!! $statistical->escenario->user->name !!}</center>
</div>

</br>
   <div class="nav">
      <div class="tab">
         <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
            <li class="active"><a data-toggle="tab" id="ocultarSave" href="#SB">Statistical DataBase</a></li>
            <li><a data-toggle="tab" class="ocultarCalculate" href="#MS">Mineral Scale</a></li>
            <li><a data-toggle="tab" class="ocultarCalculate" href="#FB">Fine Blockage</a></li>
            <li><a data-toggle="tab" class="ocultarCalculate" href="#OS">Organic Scales</a></li>
            <li><a data-toggle="tab" class="ocultarCalculate" href="#RP">Relative Permeability</a></li>
            <li><a data-toggle="tab" class="ocultarCalculate" href="#ID">Induced Damage</a></li>
            <li><a data-toggle="tab" class="ocultarCalculate" href="#GD">Geomechanical Damage</a></li>
         </ul>
      </div>
   </div>
{!!Form::model($statistical, ['route' => [$complete == true ? 'completeMultiparametric.update' : 'statistical.update_', $statistical->id], 'method' => 'POST'])!!}
  <input type="hidden" name="id_scenary" id="id_scenary" value="{{ !empty($duplicateFrom) ? $duplicateFrom : $statistical->escenario->id }}">
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
   <br>
   <br>
   <div class="row">
      <div class="col-xs-12">
         <p class="pull-right">
            <button class="btn btn-warning" id="calculate" >Calculate</button>
            <button class="btn btn-success" id="button_wr" name="button_wr">Save</button>
            <button class="btn btn-primary" id="save" style="display:none">Run</button>
            <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
         </p>
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
    @include('multiparametricAnalysis.statistical.cuerpo.createJs')
    @include('multiparametricAnalysis.statistical.cuerpo.editJs')
    @include('js/modal_error')
    <script type="text/javascript">
       $(document).ready(function(){
         validationFields();
         @if($statistical->p10_ms1 == null || $statistical->p10_fb1 == null || $statistical->p10_os1 == null || $statistical->p10_id1 == null || $statistical->p10_gd1 == null)
         multiparametricoStatistical();
         @endif
         cargarCamposBBDD();
         cargarAvailables();
      });
    </script>
@endsection