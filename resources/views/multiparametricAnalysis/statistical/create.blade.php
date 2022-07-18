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
   <center><b>Scenario: {!! $scenary->nombre !!} </br> 
      Basin: {!! $scenary->cuenca->nombre !!} - 
      Field: {!! $scenary->campo->nombre !!} - 
      {{-- Producing interval: {!!  $scenary->formacionxpozo->nombre !!} -  --}}
      Well: {!!  $scenary->pozo->nombre !!}</br> 
      User: {!! $scenary->user->fullName !!}
   </center>
</div>

</br>

<div class="nav">
   <div class="tab">
      <ul class="nav nav-tabs index-group" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#SB" id="SB_C">Statistical Database</a></li>
         <li><a data-toggle="tab" class="text-danger sectionDeactivated">Mineral Scale</a></li>
         <li><a data-toggle="tab" class="text-danger sectionDeactivated">Fine Blockage</a></li>
         <li><a data-toggle="tab" class="text-danger sectionDeactivated">Organic Scales</a></li>
         <li><a data-toggle="tab" class="text-danger sectionDeactivated">Relative Permeability</a></li>
         <li><a data-toggle="tab" class="text-danger sectionDeactivated">Induced Damage</a></li>
         <li><a data-toggle="tab" class="text-danger sectionDeactivated">Geomechanical Damage</a></li>
      </ul>
   </div>
</div>
{!!Form::open(['route' => [$complete == true ? 'completeMultiparametric.store' : 'statistical.store'], 'method' => 'POST'])!!}
   <input type="hidden" name="calculate" value="false">
   <input type="hidden" name="escenario_id" value="{{$scenary->id}}">
   <div class="tab-content">
      <br>
      @include('multiparametricAnalysis.statistical.cuerpo.sb')
   </div>
   <div class="row">
      <div class="col-md-12 scenario-buttons">
         <div align="left">
            <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
         </div>
      </div>
   </div>
{!! Form::Close() !!}

<div class="modal fade" id="myModalRP" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Production Test (PLT) Successfully Entered.</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="sectionDeactivated" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"> Incomplete Form</h4>
         </div>
         <div class="modal-body">
            <center>
               <p>complete the data of the section <strong>"Statistical Database"</strong>.</p>
            </center>
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
   @include('js/modal_error')
   <script type="text/javascript">
      $(document).ready(function(){
         cargarCamposDefault();
         validationFields();
      });
   </script>
@endsection