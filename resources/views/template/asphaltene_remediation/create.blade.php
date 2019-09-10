@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')
@section('content')
<?php if(!isset($_SESSION)) { session_start(); } $option_remediation = 1; if($scenary_s->asphaltene_remediation == 'Based upon asphaltene diagnosis model'){ $option_remediation = 1; } else if($scenary_s->asphaltene_remediation == 'Volumetric changes'){ $option_remediation = 2; }?>

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
         <li class="active"><a data-toggle="tab" href="#reservoir_data" id="p_reservoir_data">Reservoir Data</a></li>
         <li><a data-toggle="tab" href="#asphaltene_data" id="p_asphaltene_data">Asphaltene Data</a></li>
         <li><a data-toggle="tab" href="#treatment_data" id="p_treatment_data">Treatment Data</a></li>
         {{--<li><a data-toggle="tab" href="#treatment_reward" id="p_treatment_reward">Treatment Reward</a></li>--}}
      </ul>
      <div class="tab-content">

         <div class="tab-pane active" id="reservoir_data">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#petrophysic_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Petrophysic</h4>
               </div>
               <div class="panel-body">
                  <div id="petrophysic_pleg" class="panel-collapse collapse in">
                     {!!Form::open(array('id' => 'form_asphaltene', 'url' => url('asphalteneremediation/store'), 'method' => 'post'))!!}
                     <input type="hidden" name="id_scenary" id="id_scenary" value="{{ $scenary_s->id }}">
                     <input type="hidden" name="type_asphaltene" id="type_asphaltene" value="{{ $option_remediation }}">
                     <div class="row">

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_porosity', 'Initial Porosity') !!} <span style='color:red;'>*</span>
                              <div class="input-group">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor" ><span class="glyphicon glyphicon-info-sign" ></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('initial_porosity',null, ['placeholder' => 'Initial Porosity', 'class' =>'form-control', 'id' => 'initial_porosity']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ Fraction ]</span>
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
                              {!! Form::label('water_saturation', 'Water Saturation') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('water_saturation') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('water_saturation',null, ['placeholder' => 'Water Saturation', 'class' =>'form-control', 'id' => 'water_saturation']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ Fraction ]</span>
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

                        @if ($option_remediation == 2)

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('current_permeability', 'Current Permeability') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('current_permeability') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('current_permeability',null, ['placeholder' => 'Current Permeability', 'class' =>'form-control', 'id' => 'current_permeability']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ mD ]</span>
                              </div>
                           </div>
                        </div>

                        @endif

                     </div>

                     <hr>

                     @if ($option_remediation == 1)
                     <div class="row">
                        <div class="col-md-12">
                           <div class="pull-right"><button type="button" class="btn btn-default" onclick="$('#externalModalButton').modal('show')">Import data from ...</button></div>
                           <fieldset>
                              <legend>Damage Data @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_excel_changes_along_the_radius_input" style="color:black;font-size:15pt;"></i></span>@endif </legend>
                              <div id="excel_changes_along_the_radius" class="handsontable"></div>
                           </fieldset>
                           <input type="hidden" name="excel_changes_along_the_radius_input" id="excel_changes_along_the_radius_input">
                        </div>
                     </div>
                     @endif
                  </div>
               </div>
            </div>

            @if ($option_remediation == 2)
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4>
                     <a data-parent="#accordion" data-toggle="collapse" href="#skin_characterization_pleg">
                        <span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span>
                     </a>
                     Skin Characterization
                     @if($advisor === "true")
                     <span>
                        <i class="glyphicon glyphicon-info-sign show-only-advisor" id="skin_characterization" style="color:black;font-size:15pt;"></i>
                     </span>
                     @endif
                  </h4>
               </div>
               <div class="panel-body">
                  <div id="skin_characterization_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-12">

                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('skin_characterization_scale', 'Scale') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('skin_characterization_scale') ? 'has-error' : ''}}">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('skin_characterization_scale',null, ['placeholder' => 'Scale', 'class' =>'form-control', 'id' => 'skin_characterization_scale']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ % ]</span>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('skin_characterization_induced', 'Induced') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('skin_characterization_induced') ? 'has-error' : ''}}">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('skin_characterization_induced',null, ['placeholder' => 'Induced', 'class' =>'form-control', 'id' => 'skin_characterization_induced']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ % ]</span>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('skin_characterization_asphaltene', 'Asphaltene') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('skin_characterization_asphaltene') ? 'has-error' : ''}}">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('skin_characterization_asphaltene',null, ['placeholder' => 'Asphaltene', 'class' =>'form-control', 'id' => 'skin_characterization_asphaltene']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ % ]</span>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('skin_characterization_fines', 'Fines') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('skin_characterization_fines') ? 'has-error' : ''}}">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('skin_characterization_fines',null, ['placeholder' => 'Fines', 'class' =>'form-control', 'id' => 'skin_characterization_fines']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ % ]</span>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('skin_characterization_non_darcy', 'Non Darcy') !!}
                                 <div class="input-group {{$errors->has('skin_characterization_non_darcy') ? 'has-error' : ''}}">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('skin_characterization_non_darcy',null, ['placeholder' => 'Non Darcy', 'class' =>'form-control', 'id' => 'skin_characterization_non_darcy']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ % ]</span>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('skin_characterization_geomechanical', 'Geomechanical') !!}
                                 <div class="input-group {{$errors->has('skin_characterization_geomechanical') ? 'has-error' : ''}}">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('skin_characterization_geomechanical',null, ['placeholder' => 'Geomechanical', 'class' =>'form-control', 'id' => 'skin_characterization_geomechanical']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ % ]</span>
                                 </div>
                              </div>
                           </div>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
            @endif
         </div>

         <div class="tab-pane" id="asphaltene_data">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#asphaltene_data_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Asphaltene Data</h4>
               </div>
               <div class="panel-body">
                  <div id="asphaltene_data_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('asphaltene_apparent_density', 'Asphaltene Apparent Density') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('asphaltene_apparent_density') ? 'has-error' : ''}}">
                                 @if($advisor == true)
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('asphaltene_apparent_density','1.2', ['placeholder' => 'Asphaltene Apparent Density', 'class' =>'form-control', 'id' => 'asphaltene_apparent_density']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[ g/cc ]</span>
                              </div>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="treatment_reward">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#treatment_reward_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Treatment Reward</h4>
               </div>
               <div class="panel-body">
                  <div id="treatment_reward_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-12">
                           <legend>
                              <input type="checkbox" name="stimate_ior" id="stimate_ior">
                              <input type="hidden" name="stimate_ior_input" id="stimate_ior_input">
                              <font size=3><b>Stimate IOR after chemical stimulation</b></font>
                           </legend>
                        </div>

                        <div id="stimate_ior_checked">
                           <div class="col-md-12">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <div class="form-group">
                                       {!! Form::label('monthly_decline_rate', 'Monthly Decline Rate') !!}<span style='color:red;'>*</span>
                                       <div class="input-group {{$errors->has('monthly_decline_rate') ? 'has-error' : ''}}">
                                          @if($advisor == true)
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('monthly_decline_rate',null, ['placeholder' => 'Monthly Decline Rate', 'class' =>'form-control', 'id' => 'monthly_decline_rate']) !!}
                                          <span class="input-group-addon" id="basic-addon2">[ Fraction ]</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="clearfix"></div>
                           <div class="col-md-12">

                              <div class="col-md-6">
                                 {!! Form::label('current_oil_production', 'Current Oil Production') !!}<span style='color:red;'>*</span>
                              </div>
                              <div class="col-md-6">
                                 {!! Form::label('data_input', 'Date') !!}<span style='color:red;'>*</span>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <div class="form-group">
                                       <div class="input-group {{$errors->has('data_input') ? 'has-error' : ''}}">
                                          @if($advisor == true)
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('current_oil_production',null, ['placeholder' => 'Current Oil Production', 'class' =>'form-control', 'id' => 'current_oil_production']) !!}
                                          <span class="input-group-addon" id="basic-addon1">[ bbl/day ]</span>

                                          {!! Form::text('data_input',null, ['placeholder' => 'Date with format YYYY/MM/DD', 'class' =>'form-control datepicker', 'id' => 'data_input', 'required' => 'required']) !!}
                                          <span class="input-group-addon" id="basic-addon2">[ Y/M/D ]</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane " id="treatment_data">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#asphaltene_dilution_capacity_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Asphaltene Dilution Capacity</h4>
               </div>
               <div class="panel-body">
                  <div id="asphaltene_dilution_capacity_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-6">
                           <input type="radio" class="form-button" value="yes" name="chemical_treatment_impl" checked id="chemical_treatment_impl_yes"> Enter manually
                           <input type="radio" class="form-button" value="no" name="chemical_treatment_impl" id="chemical_treatment_impl_no"> Import from library
                        </div>

                        <div class="col-md-6" id="chemical_treatment_impl_no_div">
                           <div class="col-md-12">
                              <div class="form-group">
                                 {!! Form::label('option_treatment', 'Select one option treatment') !!} <span style='color:red;'>*</span>
                                 @if($advisor == true)
                                 <span>
                                    <i class="glyphicon glyphicon-info-sign" id="code_option_treatment" style="color:black;font-size:15pt;"></i>
                                 </span>
                                 @endif
                                 <select name="option_treatment" style="border-radius: 4px;" class="form-control" id="option_treatment">
                                    {!! $asphaltene_treatment !!}
                                 </select>
                              </div>
                              <div class="col-md-12">
                                 <div class="panel panel-default">
                                    <div class="panel-heading">
                                       <h5><a data-parent="#accordion" data-toggle="collapse" href="#tabla_options_treatment_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Treatment properties</h5>
                                    </div>
                                    <div class="panel-body">
                                       <div id="tabla_options_treatment_pleg" class="panel-collapse collapse in">
                                          <div id="prueba_tabla"> No option treatment selected.</div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6" id="chemical_treatment_impl_yes_div">
                           <div class="col-md-12">
                              <div class="form-group">
                                 {!! Form::label('asphaltene_dilution_capacity', 'Asphaltene Dilution Capacity') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('asphaltene_dilution_capacity') ? 'has-error' : ''}}">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('asphaltene_dilution_capacity',null, ['placeholder' => 'Asphaltene Dilution Capacity', 'class' =>'form-control', 'id' => 'asphaltene_dilution_capacity']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ mg/L ]</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#treatment_data_pleg"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Treatment Data</h4>
               </div>
               <div class="panel-body">
                  <div id="treatment_data_pleg" class="panel-collapse collapse in">
                     <div class="row">

                        <div class="col-md-12">

                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="form-group">
                                    {!! Form::label('treatment_radius', 'Treatment Radius') !!}<span style='color:red;'>*</span>
                                    <div class="input-group {{$errors->has('treatment_radius') ? 'has-error' : ''}}">
                                       @if($advisor == true)
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                       @endif
                                       {!! Form::text('treatment_radius',null, ['placeholder' => 'Treatment Radius', 'class' =>'form-control', 'id' => 'treatment_radius']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[ ft]</span>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="form-group">
                                    {!! Form::label('wellbore_radius', 'Wellbore Radius') !!}<span style='color:red;'>*</span>
                                    <div class="input-group {{$errors->has('wellbore_radius') ? 'has-error' : ''}}">
                                       @if($advisor == true)
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                       @endif
                                       {!! Form::text('wellbore_radius',null, ['placeholder' => 'Wellbore Radius', 'class' =>'form-control', 'id' => 'wellbore_radius']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[ ft]</span>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('asphaltene_remotion_efficiency_range', 'Asphaltene Remotion Efficiency Range') !!} <span style='color:red;'>*</span>
                                 <div class="input-group">
                                    @if($advisor == true)
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                    @endif
                                    {!! Form::text('asphaltene_remotion_efficiency_range_a',null, ['placeholder' => 'Range start', 'class' =>'form-control', 'id' => 'asphaltene_remotion_efficiency_range_a']) !!}
                                    <span class="input-group-addon" id="basic-addon1">-</span>
                                    {!! Form::text('asphaltene_remotion_efficiency_range_b',null, ['placeholder' => 'Range end', 'class' =>'form-control', 'id' => 'asphaltene_remotion_efficiency_range_b']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[ Fraction ]</span>
                                 </div>
                              </div>
                           </div>

                        </div>
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
         <button type="button" class="btn btn-success" name="button_swr" id="button_swr">Save</button>
         <p class="pull-right">
            <input type="button" class="btn btn-primary" name="run" id="run" value="Run">
            <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
         </p>
         {!! Form::Close() !!}
      </div>
   </div>
   @include('layouts/modal_advisor')
   @include('layouts/advisor_asphaltene_remediation')
   @include('layouts/template/modal_import_external')

   @endsection

   @section('Scripts')
   @include('js/template/asphaltene_remediation')
   @include('css/iprs_css')
   @include('js/advisor')

   @endsection


