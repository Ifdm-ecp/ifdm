@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')
@section('content')
@include('layouts/modal_error')
<?php  
   if(!isset($_SESSION)) {
     session_start();
   }
   ?>
@if (session()->has('mensaje'))
<div id="myModal" class="modal fade">
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
@if (session()->has('validacion'))
<div id="myModal_val" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
            <p>Error in the input Data or skin value</p>
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
<div id="hydraulic_modal" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p>
               For calculating Hydraulic Units Data you'll need Producing Formation Thickness, Permeability, and Porosity data.
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>
<div id="hydraulic_modal_incomplete" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p>
               Error in Hydraulic Units Data. Check table values.
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>
<div onload="multiparametrico();">
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
   <center>Scenario: {!! $scenario->nombre !!} </br> 
    Basin: {!! $scenario->cuenca->nombre !!} - 
    Field: {!! $scenario->campo->nombre !!} - 
    Producing interval: {!!  $scenario->formacionxpozo->nombre !!} - 
    Well: {!!  $scenario->pozo->nombre !!}</br> 
    User: {!! $scenario->user->fullName !!}</center>
</div>
</br>

<!-- ***  -->
<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#well_data_c" id="well_data" onclick="switchTab()">Well Data</a></li>
            <li><a data-toggle="tab" href="#rock_properties_c" id="rock_properties" onclick="switchTab()">Reservoir Data</a></li>
            <li><a data-toggle="tab" href="#stress_gradients_c" id="stress_gradients" onclick="switchTab()">Hydraulic Units Data</a></li>
      </ul>
      <div class="tab-content">
         <div class="tab-pane active" id="well_data_c">
            <br>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Well Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Prod" class="panel-collapse collapse in">
                     {!!Form::open(array('url' => 'Desagregacion/store', 'method' => 'post', 'id' => 'disaggregationForm', 'style' => 'display:inline'))!!}
                     <input type="hidden" name="scenario_id" id="scenario_id" value="{{ $scenario->id }}">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('well Radius', 'Well Radius ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('well_radius') ? 'has-error' : ''}}">
                                 {!! Form::text('well_radius', (float) $disaggregation->well_radius, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'well_radius']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('well_radius', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('reservoir_pressure') ? 'has-error' : ''}}">
                              {!! Form::label('reservoir pressure', 'Reservoir Pressure ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('reservoir_pressure', $disaggregation->reservoir_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'reservoir_pressure']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
                              </div>
                              {!! $errors->first('reservoir_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('measured_well_depth') ? 'has-error' : ''}}">
                              {!! Form::label('measured well depth', 'Measured Well Depth ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('measured_well_depth', $disaggregation->measured_well_depth, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'measured_well_depth']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('measured_well_depth', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('real formation depth', 'True Vertical Depth ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('true_vertical_depth') ? 'has-error' : ''}}">
                                 {!! Form::text('true_vertical_depth', $disaggregation->true_vertical_depth, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'true_vertical_depth']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('true_vertical_depth', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('formation_thickness') ? 'has-error' : ''}}">
                              {!! Form::label('espesor formation', 'Formation Thickness ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('formation_thickness', $disaggregation->formation_thickness, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'formation_thickness']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('formation_thickness', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('perforated_thickness') ? 'has-error' : ''}}">
                              {!! Form::label('espesor canoneado', 'Perforated Thickness ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('perforated_thickness', $disaggregation->perforated_thickness, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'perforated_thickness']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('perforated_thickness', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div> 
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group {{$errors->has('well_completitions') ? 'has-error' : ''}}">
                              {!! Form::label('well completitions', 'Well Completitions ', array('class' => 'required')) !!}
                              {!! Form::select('well_completitions', array(1 => 'Open Hole', 2 => 'Slotted Liners', 3 => 'Perforated Liner'), $disaggregation->well_completitions, ['class' => 'form-control', 'id'=>'well_completitions', 'placeholder' => 'Select a well completition']) !!}
                           </div>
                           {!! $errors->first('well_completitions', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div id="hidden_div_perforated_liner" style="display: none;">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('perforation_penetration_depth') ? 'has-error' : ''}}">
                                 {!! Form::label('profundidad penetracion canones', 'Perforation Penetration Depth ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('perforation_penetration_depth', $disaggregation->perforation_penetration_depth, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'perforation_penetration_depth']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                 </div>
                                 {!! $errors->first('perforation_penetration_depth', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('perforating_phase_angle') ? 'has-error' : ''}}">
                                 {!! Form::label('perforated cannoned phase angle', 'Perforating Phase Angle ', array('class' => 'required')) !!}
                                 {!! Form::select('perforating_phase_angle', array(0.0 => '0°', 45.0 => '45°', 60.0 => '60°', 90.0 => '90°', 120.0 => '120°', 180.0 => '180°', 360.0 => '360°'), $disaggregation->perforating_phase_angle, ['class' => 'form-control', 'id'=>'perforating_phase_angle', 'placeholder' => 'Select an angle']) !!}
                                 {!! $errors->first('perforating_phase_angle', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('perforating_radius') ? 'has-error' : ''}}">
                                 {!! Form::label('perforated radius', 'Perforating Radius ', array('class' => 'required'))!!}
                                 <div class="input-group">
                                    {!! Form::text('perforating_radius', $disaggregation->perforating_radius, ['placeholder' => 'in', 'class' =>'form-control', 'id' => 'perforating_radius']) !!}
                                    <span class="input-group-addon" id="basic-addon2">in</span>
                                 </div>
                                 {!! $errors->first('perforating_radius', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('production formation thickness', 'Production Formation Thickness ', array('class' => 'required')) !!}
                                 <div class="input-group {{$errors->has('production_formation_thickness') ? 'has-error' : ''}}">
                                    {!! Form::text('production_formation_thickness', $disaggregation->production_formation_thickness, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'production_formation_thickness']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                 </div>
                                 {!! $errors->first('production_formation_thickness', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('horizontal_vertical_permeability_ratio') ? 'has-error' : ''}}">
                                 {!! Form::label('relacion permeabilidad vertical horizontal', 'Horizontal - Vertical Permeability Ratio ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('horizontal_vertical_permeability_ratio', $disaggregation->horizontal_vertical_permeability_ratio, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'horizontal_vertical_permeability_ratio']) !!}
                                    <span class="input-group-addon" id="basic-addon2">-</span>
                                 </div>
                                 {!! $errors->first('horizontal_vertical_permeability_ratio', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <!-- Ingreso forma de área de drenaje -->
                              <div class="form-group">
                                 {!! Form::label('drainage area Shape', 'Drainage Area Shape ', array('class' => 'required')) !!}
                                 <br>
                                 <div>
                                    <div style="height: 150px; overflow-y: scroll; overflow-x: hidden; border: 2px solid rgb(225,225,225);">
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '1', true) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma1.png') !!}" />
                                          </p>
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '2', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma2.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '3', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma3.png') !!}" />
                                          </p>
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '4', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma4.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '5', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma5.png') !!}" />
                                          </p>
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '6', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma6.png') !!}" />
                                          </p>
                                       </div>
                                       <br />  
                                       <div class="row">
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '7', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma7.png') !!}" />
                                          </p>
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '8', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma8.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '9', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma9.png') !!}" />
                                          </p>
                                          <p class="col-sm-6" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '10', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma10.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-12" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '11', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma11.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-12" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '12', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma12.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-12" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '13', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma13.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-12" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '14', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma14.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-12" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '15', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma15.png') !!}" />
                                          </p>
                                       </div>
                                       <br />
                                       <div class="row">
                                          <p class="col-sm-12" style="text-align: center;">
                                             {!! Form::radio('drainage_area_shape', '16', false) !!}
                                             <img src="{!! asset('images/drainage-shapes/desagregacion-forma16.png') !!}" />
                                          </p>
                                       </div>
                                    </div>
                                    {!! $errors->first('drainage_area_shape', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
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
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Product"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Product" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group {{$errors->has('fluid_of_interest') ? 'has-error' : ''}}">
                              {!! Form::label('fluid of interest', 'Fluid of Interest ', array('class' => 'required')) !!}
                              {!! Form::select('fluid_of_interest', array(1 => 'Oil', 2 => 'Gas', 3 => 'Water', 4 => 'Liquid (Oil + Water)'), $disaggregation->fluid_of_interest, ['class' => 'form-control', 'id'=>'fluid_of_interest', 'placeholder' => 'Select a fluid']) !!}
                           </div>
                           {!! $errors->first('fluid_of_interest', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div id="hidden_oil" style="display: none;">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('oil rate', 'Oil Rate ', array('class' => 'required')) !!}
                                 <div class="input-group {{$errors->has('oil_rate') ? 'has-error' : ''}}">
                                    {!! Form::text('oil_rate', $disaggregation->oil_rate, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'oil_rate']) !!}
                                    <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                                 </div>
                                 {!! $errors->first('oil_rate', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('flowing pressure', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                                 <div class="input-group {{$errors->has('oil_bottomhole_flowing_pressure') ? 'has-error' : ''}}">
                                    {!! Form::text('oil_bottomhole_flowing_pressure', $disaggregation->oil_bottomhole_flowing_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'oil_bottomhole_flowing_pressure']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                                 {!! $errors->first('oil_bottomhole_flowing_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('oil_viscosity') ? 'has-error' : ''}}">
                                 {!! Form::label('viscosidad del aceite', 'Oil Viscosity ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('oil_viscosity', $disaggregation->oil_viscosity, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'oil_viscosity']) !!}
                                    <span class="input-group-addon" id="basic-addon2">cp</span>
                                 </div>
                                 {!! $errors->first('oil_viscosity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('oil_volumetric_factor') ? 'has-error' : ''}}">
                                 {!! Form::label('volumetric oil factor', 'Oil Volume Factor ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('oil_volumetric_factor', $disaggregation->oil_volumetric_factor, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'oil_volumetric_factor']) !!}
                                    <span class="input-group-addon" id="basic-addon2">-</span>
                                 </div>
                                 {!! $errors->first('oil_volumetric_factor', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="hidden_gas" style="display: none;">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('gas rate', 'Gas Rate ', array('class' => 'required')) !!}
                                 <div class="input-group {{$errors->has('gas_rate') ? 'has-error' : ''}}">
                                    {!! Form::text('gas_rate', $disaggregation->gas_rate, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'gas_rate']) !!}
                                    <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                                 </div>
                                 {!! $errors->first('gas_rate', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('flowing pressure', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                                 <div class="input-group {{$errors->has('gas_bottomhole_flowing_pressure') ? 'has-error' : ''}}">
                                    {!! Form::text('gas_bottomhole_flowing_pressure', $disaggregation->gas_bottomhole_flowing_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'gas_bottomhole_flowing_pressure']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                                 {!! $errors->first('gas_bottomhole_flowing_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('gas_viscosity') ? 'has-error' : ''}}">
                                 {!! Form::label('viscosidad del gas', 'Gas Viscosity ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('gas_viscosity', $disaggregation->gas_viscosity, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'gas_viscosity']) !!}
                                    <span class="input-group-addon" id="basic-addon2">cp</span>
                                 </div>
                                 {!! $errors->first('gas_viscosity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('gas_volumetric_factor') ? 'has-error' : ''}}">
                                 {!! Form::label('factor volumetrico del gas', 'Gas Volume Factor ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('gas_volumetric_factor', $disaggregation->gas_volumetric_factor, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'gas_volumetric_factor']) !!}
                                    <span class="input-group-addon" id="basic-addon2">-</span>
                                 </div>
                                 {!! $errors->first('gas_volumetric_factor', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="hidden_water" style="display: none;">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('water rate', 'Water Rate ', array('class' => 'required')) !!}
                                 <div class="input-group {{$errors->has('water_rate') ? 'has-error' : ''}}">
                                    {!! Form::text('water_rate', $disaggregation->water_rate, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'water_rate']) !!}
                                    <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                                 </div>
                                 {!! $errors->first('water_rate', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('flowing pressure', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                                 <div class="input-group {{$errors->has('water_bottomhole_flowing_pressure') ? 'has-error' : ''}}">
                                    {!! Form::text('water_bottomhole_flowing_pressure', $disaggregation->water_bottomhole_flowing_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'water_bottomhole_flowing_pressure']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                                 {!! $errors->first('water_bottomhole_flowing_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('water_viscosity') ? 'has-error' : ''}}">
                                 {!! Form::label('viscosidad del agua', 'Water Viscosity ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('water_viscosity', $disaggregation->water_viscosity, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'water_viscosity']) !!}
                                    <span class="input-group-addon" id="basic-addon2">cp</span>
                                 </div>
                                 {!! $errors->first('water_viscosity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('water_volumetric_factor') ? 'has-error' : ''}}">
                                 {!! Form::label('volumetric water factor', 'Water Volume Factor ', array('class' => 'required')) !!}
                                 <div class="input-group">
                                    {!! Form::text('water_volumetric_factor', $disaggregation->water_volumetric_factor, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'water_volumetric_factor']) !!}
                                    <span class="input-group-addon" id="basic-addon2">-</span>
                                 </div>
                                 {!! $errors->first('water_volumetric_factor', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="hidden_mixture" style="display: none;">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('emulsion') ? 'has-error' : ''}}">
                                 {!! Form::label('emulsion', 'Emulsion ', array('class' => 'required')) !!}
                                 {!! Form::select('emulsion', array(1 => 'Yes', 2 => 'No'), $disaggregation->emulsion, ['class' => 'form-control', 'id'=>'emulsion', 'placeholder' => 'Select an option']) !!}
                              </div>
                              {!! $errors->first('emulsion', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                           <div class="col-md-6" id="characterized_mixture_group">
                              <div class="form-group {{$errors->has('characterized_mixture') ? 'has-error' : ''}}">
                                 {!! Form::label('characterized mixture', 'Characterized Emulsion ', array('class' => 'required')) !!}
                                 {!! Form::select('characterized_mixture', array(1 => 'Yes', 2 => 'No'), $disaggregation->characterized_mixture, ['class' => 'form-control', 'id'=>'characterized_mixture', 'placeholder' => 'Select an option']) !!}
                              </div>
                              {!! $errors->first('characterized_mixture', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div id="hidden_has_emulsion_has_characterized_mixture" style="display: none;">
                           <hr>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('flow rate 1 1', 'Flow Rate ', array('class' => 'required')) !!}
                                    <div class="input-group {{$errors->has('flow_rate_1_1') ? 'has-error' : ''}}">
                                       {!! Form::text('flow_rate_1_1', $disaggregation->flow_rate_1_1, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'flow_rate_1_1']) !!}
                                       <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                                    </div>
                                    {!! $errors->first('flow_rate_1_1', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('flowing pressure 1 1', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                                    <div class="input-group {{$errors->has('mixture_bottomhole_flowing_pressure_1_1') ? 'has-error' : ''}}">
                                       {!! Form::text('mixture_bottomhole_flowing_pressure_1_1', $disaggregation->mixture_bottomhole_flowing_pressure_1_1, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'mixture_bottomhole_flowing_pressure_1_1']) !!}
                                       <span class="input-group-addon" id="basic-addon2">psi</span>
                                    </div>
                                    {!! $errors->first('mixture_bottomhole_flowing_pressure_1_1', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_viscosity_1_1') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture viscosity 1 1', 'Emulsion Viscosity ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_viscosity_1_1', $disaggregation->mixture_viscosity_1_1, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'mixture_viscosity_1_1']) !!}
                                       <span class="input-group-addon" id="basic-addon2">cp</span>
                                    </div>
                                    {!! $errors->first('mixture_viscosity_1_1', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_volumetric_factor_1_1') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture oil volumetric factor 1 1', 'Oil Volume Factor ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_volumetric_factor_1_1', $disaggregation->mixture_oil_volumetric_factor_1_1, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'mixture_oil_volumetric_factor_1_1']) !!}
                                       <span class="input-group-addon" id="basic-addon2">-</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_volumetric_factor_1_1', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_volumetric_factor_1_1') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water volumetric factor 1 1', 'Water Volume Factor ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_volumetric_factor_1_1', $disaggregation->mixture_water_volumetric_factor_1_1, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'mixture_water_volumetric_factor_1_1']) !!}
                                       <span class="input-group-addon" id="basic-addon2">-</span>
                                    </div>
                                    {!! $errors->first('mixture_water_volumetric_factor_1_1', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_fraction_1_1') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture oil fraction 1 1', 'Oil Fraction ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_fraction_1_1', $disaggregation->mixture_oil_fraction_1_1, ['placeholder' => '[0-1]', 'class' =>'form-control', 'id' => 'mixture_oil_fraction_1_1']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_fraction_1_1', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_fraction_1_1') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water fraction 1 1', 'Water Fraction ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_fraction_1_1', $disaggregation->mixture_water_fraction_1_1, ['placeholder' => '[0-1]', 'class' =>'form-control', 'id' => 'mixture_water_fraction_1_1']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                                    </div>
                                    {!! $errors->first('mixture_water_fraction_1_1', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="hidden_has_emulsion_hasnt_characterized_mixture" style="display: none;">
                           <hr>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('flow rate 1 2', 'Flow Rate ', array('class' => 'required')) !!}
                                    <div class="input-group {{$errors->has('flow_rate_1_2') ? 'has-error' : ''}}">
                                       {!! Form::text('flow_rate_1_2', $disaggregation->flow_rate_1_2, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'flow_rate_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                                    </div>
                                    {!! $errors->first('flow_rate_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('flowing pressure 1 2', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                                    <div class="input-group {{$errors->has('mixture_bottomhole_flowing_pressure_1_2') ? 'has-error' : ''}}">
                                       {!! Form::text('mixture_bottomhole_flowing_pressure_1_2', $disaggregation->mixture_bottomhole_flowing_pressure_1_2, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'mixture_bottomhole_flowing_pressure_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">psi</span>
                                    </div>
                                    {!! $errors->first('mixture_bottomhole_flowing_pressure_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_viscosity_1_2') ? 'has-error' : ''}}">
                                    {!! Form::label('viscosidad del aceite 1 2', 'Oil Viscosity ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_viscosity_1_2', $disaggregation->mixture_oil_viscosity_1_2, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'mixture_oil_viscosity_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">cp</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_viscosity_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_viscosity_1_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water viscosity 1 2', 'Water Viscosity ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_viscosity_1_2', $disaggregation->mixture_water_viscosity_1_2, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'mixture_water_viscosity_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">cp</span>
                                    </div>
                                    {!! $errors->first('mixture_water_viscosity_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_fraction_1_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture oil fraction 1 2', 'Oil Fraction ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_fraction_1_2', $disaggregation->mixture_oil_fraction_1_2, ['placeholder' => '[0-1]', 'class' =>'form-control', 'id' => 'mixture_oil_fraction_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_fraction_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_fraction_1_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water fraction 1 2', 'Water Fraction ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_fraction_1_2', $disaggregation->mixture_water_fraction_1_2, ['placeholder' => '[0-1]', 'class' =>'form-control', 'id' => 'mixture_water_fraction_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                                    </div>
                                    {!! $errors->first('mixture_water_fraction_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_volumetric_factor_1_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture oil volumetric factor 1 2', 'Oil Volume Factor ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_volumetric_factor_1_2', $disaggregation->mixture_oil_volumetric_factor_1_2, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'mixture_oil_volumetric_factor_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">-</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_volumetric_factor_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_volumetric_factor_1_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water volumetric factor 1 2', 'Water Volume Factor ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_volumetric_factor_1_2', $disaggregation->mixture_water_volumetric_factor_1_2, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'mixture_water_volumetric_factor_1_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">-</span>
                                    </div>
                                    {!! $errors->first('mixture_water_volumetric_factor_1_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="hidden_hasnt_emulsion" style="display: none;">
                           <hr>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('flow rate 2', 'Flow Rate ', array('class' => 'required')) !!}
                                    <div class="input-group {{$errors->has('flow_rate_2') ? 'has-error' : ''}}">
                                       {!! Form::text('flow_rate_2', $disaggregation->flow_rate_2, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'flow_rate_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                                    </div>
                                    {!! $errors->first('flow_rate_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('flowing pressure 2', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                                    <div class="input-group {{$errors->has('mixture_bottomhole_flowing_pressure_2') ? 'has-error' : ''}}">
                                       {!! Form::text('mixture_bottomhole_flowing_pressure_2', $disaggregation->mixture_bottomhole_flowing_pressure_2, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'mixture_bottomhole_flowing_pressure_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">psi</span>
                                    </div>
                                    {!! $errors->first('mixture_bottomhole_flowing_pressure_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_viscosity_2') ? 'has-error' : ''}}">
                                    {!! Form::label('viscosidad del aceite 2', 'Oil Viscosity ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_viscosity_2', $disaggregation->mixture_oil_viscosity_2, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'mixture_oil_viscosity_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">cp</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_viscosity_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_viscosity_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water viscosity 2', 'Water Viscosity ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_viscosity_2', $disaggregation->mixture_water_viscosity_2, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'mixture_water_viscosity_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">cp</span>
                                    </div>
                                    {!! $errors->first('mixture_water_viscosity_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_fraction_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture oil fraction 2', 'Oil Fraction ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_fraction_2', $disaggregation->mixture_oil_fraction_2, ['placeholder' => '[0-1]', 'class' =>'form-control', 'id' => 'mixture_oil_fraction_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_fraction_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_fraction_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water fraction 2', 'Water Fraction ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_fraction_2', $disaggregation->mixture_water_fraction_2, ['placeholder' => '[0-1]', 'class' =>'form-control', 'id' => 'mixture_water_fraction_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                                    </div>
                                    {!! $errors->first('mixture_water_fraction_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_oil_volumetric_factor_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture oil volumetric factor 2', 'Oil Volume Factor ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_oil_volumetric_factor_2', $disaggregation->mixture_oil_volumetric_factor_2, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'mixture_oil_volumetric_factor_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">-</span>
                                    </div>
                                    {!! $errors->first('mixture_oil_volumetric_factor_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group {{$errors->has('mixture_water_volumetric_factor_2') ? 'has-error' : ''}}">
                                    {!! Form::label('mixture water volumetric factor 2', 'Water Volume Factor ', array('class' => 'required')) !!}
                                    <div class="input-group">
                                       {!! Form::text('mixture_water_volumetric_factor_2', $disaggregation->mixture_water_volumetric_factor_2, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'mixture_water_volumetric_factor_2']) !!}
                                       <span class="input-group-addon" id="basic-addon2">-</span>
                                    </div>
                                    {!! $errors->first('mixture_water_volumetric_factor_2', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
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
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Dam"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Damage</h4>
               </div>
               <div class="panel-body">
                  <div id="Dam" class="panel-collapse collapse in">
                     <fieldset>
                        <div class="col-md-6 input_skin">
                           <div class="form-group {{$errors->has('skin') ? 'has-error' : ''}}">
                              {!! Form::label('skin', 'Skin ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('skin') ? 'has-error' : ''}}">
                                 {!! Form::text('skin', $disaggregation->skin, ['placeholder' => '-', 'class' =>'form-control', 'name' => 'skin', 'id' => 'skin']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                              {!! $errors->first('skin', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </fieldset>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="rock_properties_c">
            <br>
            <div class="panel panel-default {{$errors->has('MSFormation') ? 'has-error' : ''}}">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#BasicP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Basic Petrophysics</h4>
               </div>
               <div class="panel-body">
                  <div id="BasicP" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('permeability') ? 'has-error' : ''}}">
                              {!! Form::label('permeabilidad absoluta inicial', 'Permeability  ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('permeability', $disaggregation->permeability, ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeability']) !!}
                                 <span class="input-group-addon" id="basic-addon2">md</span>
                              </div>
                              {!! $errors->first('permeability', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('rock_type') ? 'has-error' : ''}}">
                              {!! Form::label('tipo de roca', 'Rock Type ', array('class' => 'required')) !!}
                              {!! Form::select('rock_type', array('consolidada' => 'Consolidated', 'poco consolidada' => 'Unconsolidated', 'microfracturada' => 'Microfractured'), $disaggregation->rock_type, ['class' => 'form-control', 'id'=>'rock_type', 'placeholder' => 'Select a rock type']) !!}
                              {!! $errors->first('rock_type', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('porosity') ? 'has-error' : ''}}">
                              {!! Form::label('porosity_label', 'Porosity ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('porosity', $disaggregation->porosity, ['placeholder' =>  '[0-0.467]', 'class' =>'form-control', 'id' => 'porosity']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[0-0.467]</span>
                              </div>
                              {!! $errors->first('porosity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="stress_gradients_c">
            <br>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Unids"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right" ></span></a> Hydraulic Units Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Unids" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-12">
                           <!-- Tabla de Excel -->
                           <div class="form-group">
                              {!! Form::label('hidraulic units data', 'Hydraulic Units Data ', array('class' => 'required')) !!}
                              <div class="col-md-12">
                                 <div tabindex="0" id="hidraulic_units_data" class="dataTable" style="overflow: auto;  height: 150px; max-height: 150px; table-layout: auto; width: 100%;">
                                 </div>
                                 <div tabindex="0" id="hidraulic_units_data_hidden" class="dataTable" style="overflow: auto;  height: 150px; max-height: 150px; table-layout: auto; width: 100%;" hidden>
                                 </div>
                                 <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="For calculating the Hydraulic Units Data you'll need Permeability, Porosity, and Producing Formation Thickness Data." onclick="calculate_hydraulic_units_data()" type='button'>Calculate Hydraulic Units Data</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            {!! Form::hidden('hidraulic_units_data_table', '', array('id' => 'hidraulic_units_data_table')) !!}
            {!! Form::hidden('only_s', '', array('id' => 'only_s')) !!}
            <div class="col-md-12 scenario-buttons">
               <div align="left">
                  <button type="button" class="btn btn-success" onclick="verifyDisaggregation('save');">Save</button>
                  <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
               </div>
               <div align="right">
                  <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
                  <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
                  <button type="button" class="btn btn-primary" style="display: none" onclick="verifyDisaggregation('run');" id="run_calc">Run</button>
               </div>
            </div>
            {!! Form::hidden('unidades_table', '', array('id' => 'unidades_table')) !!}
            {!! Form::hidden('unidades_table_hidden', '', array('id' => 'unidades_table_hidden')) !!}
            <div id="loading" style="display:none;"></div>
            &nbsp;
            
            {!! Form::Close() !!}
         </div>
      </div>
   </div>
</div>
<!-- ***  -->


@endsection
@section('Scripts')
@include('js/frontend_validator')
@include('js/frontend_rules/disaggregation')
@include('js/desagregacion_edit')
@include('css/desagregacion_edit')
@include('js/modal_error')
@include('js/modal_error_frontend')
@include('css/modal_error_frontend')
@endsection