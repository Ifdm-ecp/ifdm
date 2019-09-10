@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')
@section('content')
<?php if(!isset($_SESSION)) { session_start(); } ?>

<script type="text/javascript">
   $('#loading').show();
</script>

@if (count($errors) > 0)
<script>
   var curr_hot = null;
</script>
<div id="modal_errors" class="modal fade">
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

<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
   <center>
      {!! Form::label('Scenario: ') !!} {!! $scenary_s->nombre !!} {!! Form::label(' - Basin: ') !!} {!! $basin->nombre !!} {!! Form::label(' - Field: ') !!} {!! $campo->nombre !!} {!! Form::label(' - Producing interval: ') !!} {!!  $intervalo->nombre !!} {!! Form::label(' - Well: ') !!} {!! $pozo->nombre !!} {!! Form::label(' - User: ') !!} {!! $user->fullName !!}
   </center>
</div>
</br>

<!-- ***  -->
@include('layouts/general_advisor')
<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#reservoir_data" id="p_reservoir_data">Reservoir Properties</a></li>
         <li><a data-toggle="tab" href="#damage_diagnosis" id="p_damage_diagnosis">Damage Diagnosis</a></li>
         <li><a data-toggle="tab" href="#treatment_data" id="p_treatment_data">Treatment Data</a></li>
         <li><a data-toggle="tab" href="#minerals_data" id="p_minerals_data">Minerals Data</a></li>
      </ul>
      <div class="tab-content">

         <div class="tab-pane active" id="reservoir_data">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#prop_reservoir_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Reservoir Properties</h4>
               </div>
               <div class="panel-body">
                  <div id="prop_reservoir_pleg" class="panel-collapse collapse in">
                     {!!Form::open(array('id' => 'form_fines_remediation', 'url' => url('finesremediation/store'), 'method' => 'post'))!!}
                     <input type="hidden" name="id_scenary" id="id_scenary" value="{{ $scenary_s->id }}">
                     <div class="row">

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_porosity', 'Initial Porosity') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('initial_porosity') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('initial_porosity',null, ['placeholder' => 'Initial Porosity', 'class' =>'form-control', 'id' => 'initial_porosity']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ - ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_permeability', 'Initial Permeability') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('initial_permeability') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('initial_permeability',null, ['placeholder' => 'Initial Permeability', 'class' =>'form-control', 'id' => 'initial_permeability']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ mD ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('temperature', 'Temperature') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('temperature') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('temperature',null, ['placeholder' => 'Temperature', 'class' =>'form-control', 'id' => 'temperature']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ °F ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('well_radius', 'Well Radius') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('well_radius') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('well_radius',null, ['placeholder' => 'Well Radius', 'class' =>'form-control', 'id' => 'well_radius']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ in ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="clearfix"></div>
                        <br>
                        
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('damage_radius', 'Damage Radius') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('damage_radius') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('damage_radius',null, ['placeholder' => 'Damage Radius', 'class' =>'form-control', 'id' => 'damage_radius']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ ft ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('net_pay', 'Net Pay') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('net_pay') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('net_pay',null, ['placeholder' => 'Net Pay', 'class' =>'form-control', 'id' => 'net_pay']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ ft ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('rock_compressibility', 'Rock Compressibility') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('rock_compressibility') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('rock_compressibility',null, ['placeholder' => 'Rock Compressibility', 'class' =>'form-control', 'id' => 'rock_compressibility']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ Psi<sup>-1</sup> ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('pressure', 'Pressure') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('pressure') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('pressure',null, ['placeholder' => 'Pressure', 'class' =>'form-control', 'id' => 'pressure']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ Psi ]</span>
                              </div>
                           </div>
                        </div>

                     </div>

                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="damage_diagnosis">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#damage_diagnosis_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Damage Diagnosis</h4>
               </div>
               <div class="panel-body">
                  <div id="damage_diagnosis_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-12">
                           <div class="pull-right"><button type="button" class="btn btn-default" onclick="$('#externalModalButton').modal('show')">Import data from ...</button></div> 
                           <fieldset>
                              <legend>Damage Diagnosis @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-only-advisor" id="code_table_excel_damage_diagnosis" style="color:black;font-size:15pt;"></i></span>@endif </legend>
                              <div id="excel_damage_diagnosis" class="handsontable"></div>
                           </fieldset>
                           <input type="hidden" name="excel_damage_diagnosis_input" id="excel_damage_diagnosis_input">
                        </div>

                        <div class="clearfix"></div>
                        <br>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="treatment_data">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#treatment_data_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Treatment Data</h4>
               </div>
               <div class="panel-body">
                  <div id="treatment_data_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('acid_concentration', 'Acid Concentration') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('acid_concentration') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('acid_concentration',null, ['placeholder' => 'Acid Concentration', 'class' =>'form-control', 'id' => 'acid_concentration']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ % ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('injection_rate', 'Injection Rate') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('injection_rate') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('injection_rate',null, ['placeholder' => 'Injection Rate', 'class' =>'form-control', 'id' => 'injection_rate']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ <sup>gal</sup>/<small>min</small>  ]</span>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('invasion_radius', 'Invasion Radius') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('invasion_radius') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('invasion_radius',null, ['placeholder' => 'Invasion Radius', 'class' =>'form-control', 'id' => 'invasion_radius']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ ft ]</span>
                              </div>
                           </div>
                        </div>

                  {{--       <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('viscosity', 'Viscosity') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('viscosity') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-info"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('viscosity',null, ['placeholder' => 'Viscosity', 'class' =>'form-control', 'id' => 'viscosity']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ cp ]</span>
                              </div>
                           </div>
                        </div> --}}

                     </div>

                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="minerals_data">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#rock_composition_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Rock Composition</h4>
               </div>
               <div class="panel-body">
                  <div id="rock_composition_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-12">
                           <fieldset>
                              <legend>Rock Composition @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-only-advisor" id="code_table_excel_rock_composition" style="color:black;font-size:15pt;"></i></span>@endif </legend>
                              <div id="excel_rock_composition" class="handsontable"></div>
                           </fieldset>
                           <input type="hidden" name="excel_rock_composition_input" id="excel_rock_composition_input">
                        </div>

                        <div class="clearfix"></div>
                        <br>
                     </div>
                  </div>
               </div>
            </div>

            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#choose_minerals_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Choose Minerals</h4>
               </div>
               <div class="panel-body">
                  <div id="choose_minerals_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-12">

                           <ul>
                              @foreach($mineral_data as $k => $mineral)
                              <li style="list-style:none;">
                                 <div class="form-group">
                                    <input type="checkbox" style="margin-top: 10px;" name="check_minerals" onchange="defineMinerals();" id="check_minerals_{{ $k }}" value="{{ $mineral->id }}">
                                    <label for="check_minerals_{{ $k }}">{{ $mineral->name }}</label>
                                 </div>
                              </li>
                              @endforeach
                           </ul>
                           <input type="hidden" name="check_minerals_input" id="check_minerals_input" value="">

                        </div>

                        <div class="clearfix"></div>
                        <br>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>

   <!-- ***  -->
   <div class="row">
      <div class="col-md-12">
         <input type="hidden" name="_button_swr" id="_button_swr">
         <button type="button" class="btn btn-success" name="button_sw" id="button_sw">Save</button>
         <p class="pull-right">
            <input type="button" class="btn btn-primary" name="run" id="run" value="Run">
            <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
         </p>
         {!! Form::Close() !!}
      </div>
   </div>
   <script type="text/javascript">
      $('#loading').hide();
   </script>
   @include('layouts/modal_advisor')
   @include('layouts/advisor_fines_remediation')
   @include('layouts/template/modal_import_external')

   @endsection

   @section('Scripts')
   
   @include('js/template/fines_remediation')
   @include('css/iprs_css')
   @include('js/advisor')

   @endsection


