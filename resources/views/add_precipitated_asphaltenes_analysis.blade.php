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
</br>

{!!Form::open(['action' => ['add_precipitated_asphaltenes_analysis_controller@store', 'scenaryId' => $scenaryId], 'method' => 'post', 'id' => 'asphalteneForm'])!!}

@include('layouts/general_advisor')

<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#general_data" id="general_data_tab" onclick="switchTab()">Component Analysis</a></li>
         <li><a data-toggle="tab" href="#saturation_data" id="saturation_data_tab" onclick="switchTab()">Saturation Data</a></li>
         <li><a data-toggle="tab" href="#asphaltenes_data" id="asphaltenes_data_tab" onclick="switchTab()">Asphaltenes Data</a></li>
      </ul>

      <div class="tab-content">
         <div class="tab-pane active" id="general_data">
            <br>
            <div class="panel panel-default">
               <div class="panel-heading"><b>General Data</b></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('components', 'Components') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           {!! Form::select('components[]', [
                              'N2' => 'N2',
                              'CO2' => 'CO2',
                              'H2S' => 'H2S',
                              'C1' => 'C1',
                              'C2' => 'C2',
                              'C3' => 'C3',
                              'IC4' => 'IC4',
                              'NC4' => 'NC4',
                              'IC5' => 'IC5',
                              'NC5' => 'NC5',
                              'NC6' => 'NC6',
                              'NC7' => 'NC7',
                              'NC8' => 'NC8',
                              'NC9' => 'NC9',
                              'NC10' => 'NC10',
                              'NC11' => 'NC11',
                              'NC12' => 'NC12',
                              'NC13' => 'NC13',
                              'NC14' => 'NC14',
                              'NC15' => 'NC15',
                              'NC16' => 'NC16',
                              'NC17' => 'NC17',
                              'NC18' => 'NC18',
                              'NC19' => 'NC19',
                              'NC20' => 'NC20',
                              'NC21' => 'NC21',
                              'NC22' => 'NC22',
                              'NC23' => 'NC23',
                              'NC24' => 'NC24',
                              'FC6' => 'FC6',
                              'FC7' => 'FC7',
                              'FC8' => 'FC8',
                              'FC9' => 'FC9',
                              'FC10' => 'FC10',
                              'FC11' => 'FC11',
                              'FC12' => 'FC12',
                              'FC13' => 'FC13',
                              'FC14' => 'FC14',
                              'FC15' => 'FC15',
                              'FC16' => 'FC16',
                              'FC17' => 'FC17',
                              'FC18' => 'FC18',
                              'FC19' => 'FC19',
                              'FC20' => 'FC20',
                              'FC21' => 'FC21',
                              'FC22' => 'FC22',
                              'FC23' => 'FC23',
                              'FC24' => 'FC24',
                              'FC25' => 'FC25',
                              'FC26' => 'FC26',
                              'FC27' => 'FC27',
                              'FC28' => 'FC28',
                              'FC29' => 'FC29',
                              'FC30' => 'FC30',
                              'FC31' => 'FC31',
                              'FC32' => 'FC32',
                              'FC33' => 'FC33',
                              'FC34' => 'FC34',
                              'FC35' => 'FC35',
                              'FC36' => 'FC36',
                              'FC37' => 'FC37',
                              'FC38' => 'FC38',
                              'FC39' => 'FC39',
                              'FC40' => 'FC40',
                              'FC41' => 'FC41',
                              'FC42' => 'FC42',
                              'FC43' => 'FC43',
                              'FC44' => 'FC44',
                              'FC45' => 'FC45',
                              'SO2' => 'SO2',
                              'H2' => 'H2',
                              'Plus +' => 'Plus +'
                              ],old('components'), array('class'=>'selectpicker show-tick', 'data-live-search'=>'true', 'id'=>'components', 'data-width'=>'100%', 'data-style'=>'btn-success', 'multiple'=>'multiple')
                              ) !!}
                        </div>
                     </div>
                  </div>
                  <div class="row" style="overflow: auto;">
                     <div class="col-md-12">
                        <div class="form-group">
                           {!! Form::label('components_table_label', 'Components Data') !!} {!! Form::label('*', '*', array('class' => 'red')) !!} @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_components_table" style="color:black;font-size:15pt;"></i></span>@endif<a><span><i class="glyphicon glyphicon-list-alt import-components-data load-data" id="components_data_import" style="color:black;font-size:15pt;"></i></span></a>
                           <div id="components_table"></div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <h5><b>Total Zi </b><span class="label label-default" id="total_zi"></span></h5>
                                 </div>
                                 {!! $errors->first('sum_zi_components_table', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                           {!! Form::hidden('value_components_table', '', array('class' => 'form-control', 'id' => 'value_components_table')) !!}
                           {!! Form::hidden('sum_zi_components_table', '', array('class' => 'form-control', 'id' => 'sum_zi_components_table')) !!} 
                           {!! Form::hidden('zi_range_flag_components_table', '', array('class' => 'form-control', 'id' => 'zi_range_flag_components_table')) !!}
                        </div>
                     </div>
                  </div>
                  <br>
                  <div id="div_plus" class="row">
                     <div class="col-md-12">
                        <div class="panel-group">
                           <div class="panel panel-default">
                              <div class="panel-heading">
                                 <a data-toggle="collapse" href="#plus_charactetization">
                                    <h4 class="panel-title">
                                       <b>Plus Characterization</b>
                                    </h4>
                                 </a>
                              </div>
                              
                              <div id="plus_charactetization" class="panel-collapse collapse">
                                 <div class="panel-body">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             {!! Form::label('plus_fraction_molecular_weight_label', 'Plus Fraction Molecular Weight (MW)') !!}
                                             <div class="input-group {{$errors->has('plus_fraction_molecular_weight') ? 'has-error' : ''}}">
                                                @if($advisor === "true")
                                                   <span class="input-group-btn">
                                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                   </span>
                                                @endif
                                                {!! Form::text('plus_fraction_molecular_weight',null, ['placeholder' => 'lb/lbmol', 'class' =>'form-control', 'id' => 'plus_fraction_molecular_weight']) !!}
                                                <span class="input-group-addon" id="basic-addon2">lb/lbmol</span>
                                             </div>
                                             {!! $errors->first('plus_fraction_molecular_weight', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             {!! Form::label('plus_fraction_specific_gravity_label', 'Plus Fraction Specific Gravity') !!}
                                             <div class="input-group {{$errors->has('plus_fraction_specific_gravity') ? 'has-error' : ''}}">
                                                @if($advisor === "true")
                                                   <span class="input-group-btn">
                                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                   </span>
                                                @endif
                                                {!! Form::text('plus_fraction_specific_gravity',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'plus_fraction_specific_gravity']) !!}
                                                <span class="input-group-addon" id="basic-addon2">-</span>
                                             </div>
                                             {!! $errors->first('plus_fraction_specific_gravity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                          </div>
                                       </div>
                                    </div>

                                    <div class="row">
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             {!! Form::label('plus_fraction_boiling_temperature_label', 'Plus Fraction Boiling Temperature') !!}
                                             <div class="input-group {{$errors->has('plus_fraction_boiling_temperature') ? 'has-error' : ''}}">
                                                @if($advisor === "true")
                                                   <span class="input-group-btn">
                                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                   </span>
                                                @endif
                                                {!! Form::text('plus_fraction_boiling_temperature',null, ['placeholder' => 'R', 'class' =>'form-control', 'id' => 'plus_fraction_boiling_temperature']) !!}
                                                <span class="input-group-addon" id="basic-addon2">R</span>
                                             </div>
                                             {!! $errors->first('plus_fraction_boiling_temperature', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                          </div>
                                       </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             {!! Form::label('correlation_label', 'Correlation') !!}
                                             {!! Form::select('correlation', [
                                                ' ' => ' ',
                                                'Twu' => 'Twu',
                                                'Lee-Kesler' => 'Lee-Kesler: 60 < MW < 650 & Tbr > 0.8',
                                                'Kavett' => 'Cavett',
                                                'Pedersen' => 'Pedersen',
                                                'Riazzi Daubert' => 'Riazzi Daubert: 540 R < Tb < 1110 & 70 < MW < 700'],null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'id'=>'correlation')
                                             ) !!}
                                             {!! $errors->first('correlation', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             {!! Form::label('-', '-', array('style' => 'display: block', 'class' => 'invisible')) !!}
                                             <button type="button" style="display: block" class="btn btn-primary characterize_plus_component">Plus characterization</button>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group" style="overflow: auto;">
                           {!! Form::label('binary_interaction_label', 'Binary Interaction Coefficients Data') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}@if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_binary_interaction_coefficients_table" style="color:black;font-size:15pt;"></i></span>@endif
                           <button type="button" class="btn btn-primary btn-xs calculate-binary-interaction-coefficients">Calculate</button>&nbsp;&nbsp;<a><span><i class="glyphicon glyphicon-erase convert-to-zero" id="convert_to_zero" style="color:black;font-size:15pt;"></i></span></a>
                           <div id="binary_interaction_coefficients_table"></div>
                           {!! Form::hidden('value_binary_interaction_coefficients_table', '', array('class' => 'form-control', 'id' => 'value_binary_interaction_coefficients_table')) !!}
                           {!! Form::hidden('binary_coefficients_range_flag', '', array('class' => 'form-control', 'id' => 'binary_coefficients_range_flag')) !!} 
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="saturation_data">
            <br>
            <div class="panel panel-default">
               <div class="panel-heading"><b>Bubble Point Data</b> @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_bubble_point_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
               <div class="panel-body" style="overflow: auto;">
                  <div id="bubble_point_table"></div><br>
                  <div class="row">
                     <div class="col-md-12">
                        <button class="btn btn-primary pull-right" onclick="plot_bubble_point_table()">Plot</button>
                     </div>
                  </div>
                  {!! Form::hidden('value_bubble_point_table', '', array('class' => 'form-control', 'id' => 'value_bubble_point_table')) !!}
                  {!! Form::hidden('bubble_point_data_range_flag', '', array('class' => 'form-control', 'id' => 'bubble_point_data_range_flag')) !!}

                  <div class="row">
                     <div class="col-md-12">
                        <br>
                        <div id="graphic_bubble_point_table"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading"><b>Saturation Data</b></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('critical_temperature_label', 'Critical Temperature (Envolope phase)') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('critical_temperature') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('critical_temperature',null, ['placeholder' => '°F', 'class' =>'form-control', 'id' => 'critical_temperature']) !!}
                              <span class="input-group-addon" id="basic-addon2">°F</span>
                           </div>
                           {!! $errors->first('critical_temperature', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('critical_pressure_label', 'Critical Pressure (Envelope phase)') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('critical_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('critical_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'critical_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">psi</span>
                           </div>
                           {!! $errors->first('critical_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('reservoir_density_label', 'Density at Reservoir Pressure') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('density_at_reservoir_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('density_at_reservoir_pressure',null, ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'density_at_reservoir_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">g/cc</span>
                           </div>
                           {!! $errors->first('density_at_reservoir_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('bubble_density_label', 'Density at Bubble Pressure') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('density_at_bubble_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('density_at_bubble_pressure',null, ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'density_at_bubble_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">g/cc</span>
                           </div>
                           {!! $errors->first('density_at_bubble_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('density_14_7_label', 'Density at Atmospheric Pressure') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('density_at_atmospheric_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('density_at_atmospheric_pressure',null, ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'density_at_atmospheric_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">g/cc</span>
                           </div>
                           {!! $errors->first('density_at_atmospheric_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('reservoir_temperature_label', 'Reservoir Temperature') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('reservoir_temperature') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('reservoir_temperature',null, ['placeholder' => '°F', 'class' =>'form-control', 'id' => 'reservoir_temperature']) !!}
                              <span class="input-group-addon" id="basic-addon2">°F</span>
                           </div>
                           {!! $errors->first('reservoir_temperature', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('initial_reservoir_pressure_label', 'Initial Reservoir Pressure') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('initial_reservoir_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('initial_reservoir_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'initial_reservoir_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">psi</span>
                           </div>
                           {!! $errors->first('initial_reservoir_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('current_reservoir_pressure_label', 'Current Reservoir Pressure') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('current_reservoir_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('current_reservoir_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'current_reservoir_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">psi</span>
                           </div>
                           {!! $errors->first('current_reservoir_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('fluid_api_gravity_label', 'Fluid API Gravity') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('fluid_api_gravity') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('fluid_api_gravity',null, ['placeholder' => '°API', 'class' =>'form-control', 'id' => 'fluid_api_gravity']) !!}
                              <span class="input-group-addon" id="basic-addon2">°API</span>
                           </div>
                           {!! $errors->first('fluid_api_gravity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="asphaltenes_data">
            <br>
            <div class="panel panel-default">
               <div class="panel-heading"><b>Temperature Data</b></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('number_of_temperatures_label', 'Number Of Temperatures') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('number_of_temperatures') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('number_of_temperatures',20, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'number_of_temperatures']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('number_of_temperatures', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('temperature_delta_label', 'Temperature Delta') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('temperature_delta') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('temperature_delta',50, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'temperature_delta']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('temperature_delta', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="panel panel-default">
               <div class="panel-heading"><b>Asphaltenes Data  </b><a><span><i class="glyphicon glyphicon-list-alt import-asphaltenes-data load-data" id="asphaltenes_data_import" style="color:black;font-size:15pt;"></i></span></a></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('agregate_deviation_label', 'Asphaltene Particle Diameter') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('asphaltene_particle_diameter') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('asphaltene_particle_diameter',null, ['placeholder' => 'nm', 'class' =>'form-control', 'id' => 'asphaltene_particle_diameter']) !!}
                              <span class="input-group-addon" id="basic-addon2">nm</span>
                           </div>
                           {!! $errors->first('asphaltene_particle_diameter', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('asphaltene_molecular_weight_label', 'Asphaltene Molecular Weight') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('asphaltene_molecular_weight') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('asphaltene_molecular_weight',null, ['placeholder' => 'lb/lbm', 'class' =>'form-control', 'id' => 'asphaltene_molecular_weight']) !!}
                              <span class="input-group-addon" id="basic-addon2">lb/lbm</span>
                           </div>
                           {!! $errors->first('asphaltene_molecular_weight', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('bulk_density_label', 'Asphaltene Apparent Density') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('asphaltene_apparent_density') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('asphaltene_apparent_density',null, ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'asphaltene_apparent_density']) !!}
                              <span class="input-group-addon" id="basic-addon2">g/cc</span>
                           </div>
                           {!! $errors->first('asphaltene_apparent_density', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="panel panel-default">
               <div class="panel-heading"><b>SARA Analysis</b></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('saturate_label', 'Saturated') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('saturate') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('saturate',(empty($asphaltenes_d_stability_analysis) ? null : $asphaltenes_d_stability_analysis->saturated), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'saturate']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('saturate', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('aromatic_label', 'Aromatics') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('aromatic') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('aromatic',(empty($asphaltenes_d_stability_analysis) ? null : $asphaltenes_d_stability_analysis->aromatics), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'aromatic']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('aromatic', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('resine_label', 'Resines') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('resine') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('resine',(empty($asphaltenes_d_stability_analysis) ? null : $asphaltenes_d_stability_analysis->resines), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'resine']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('resine', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('asphaltene_label', 'Asphaltenes') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('asphaltene') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('asphaltene',(empty($asphaltenes_d_stability_analysis) ? null : $asphaltenes_d_stability_analysis->asphaltenes), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'asphaltene']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('asphaltene', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <h5><b>Total SARA </b><span class="label label-default" id="total_sara"></span></h5>
                        </div>
                        {!! $errors->first('sara_calc', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                     </div>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-md-6">
                  <div class="form-check">
                     {!! Form::checkbox('elemental_data_selector',1,null,array('class'=>'form-check-input', 'id'=>'elemental_data_selector')) !!}&nbsp<label class="form-check-label" for="elemental_data_selector">Include Elemental Asphaltene Analysis</label>
                  </div>
               </div>
            </div>

            <div class="panel panel-default">
               <div class="panel-heading"><b>Elemental Asphaltene Analysis Data</b></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('hydrogen_carbon_ratio_label', 'Hydrogen Carbon Ratio') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('hydrogen_carbon_ratio') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('hydrogen_carbon_ratio',null, ['placeholder' => '-', 'class' =>'form-control elemental_data', 'id' => 'hydrogen_carbon_ratio']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('hydrogen_carbon_ratio', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('oxygen_carbon_ratio_label', 'Oxygen Carbon Ratio') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('oxygen_carbon_ratio') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('oxygen_carbon_ratio',null, ['placeholder' => '-', 'class' =>'form-control elemental_data', 'id' => 'oxygen_carbon_ratio']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('oxygen_carbon_ratio', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('nitrogen_carbon_ratio_label', 'Nitrogen Carbon Ratio') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('nitrogen_carbon_ratio') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('nitrogen_carbon_ratio',null, ['placeholder' => '-', 'class' =>'form-control elemental_data', 'id' => 'nitrogen_carbon_ratio']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('nitrogen_carbon_ratio', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('sulphur_carbon_ratio_label', 'Sulphure Carbon Ratio') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('sulphure_carbon_ratio') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('sulphure_carbon_ratio',null, ['placeholder' => '-', 'class' =>'form-control elemental_data', 'id' => 'sulphure_carbon_ratio']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('sulphure_carbon_ratio', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('fa_aromaticity_label', 'FA Aromaticity') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('fa_aromaticity') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('fa_aromaticity',null, ['placeholder' => '-', 'class' =>'form-control elemental_data', 'id' => 'fa_aromaticity']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('fa_aromaticity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('vc_molar_volume_label', 'VC Molar Volume') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('vc_molar_volume') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('vc_molar_volume',null, ['placeholder' => '-', 'class' =>'form-control elemental_data', 'id' => 'vc_molar_volume']) !!}
                              <span class="input-group-addon" id="basic-addon2">-</span>
                           </div>
                           {!! $errors->first('vc_molar_volume', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         {!! Form::hidden('only_s', '', array('id' => 'only_s')) !!}
         <div class="col-md-12 scenario-buttons">
            <div align="left">
               <button type="button" class="btn btn-success" onclick="verifyAsphaltene('save');">Save</button>
               <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
            </div>
            <div align="right">
               <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
               <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
               <button type="button" class="btn btn-primary" style="display: none" onclick="verifyAsphaltene('run');" id="run_calc">Run</button>
            </div>
         </div>
      </div>
   </div>
</div>
{!! Form::Close() !!}
<br>

@include('layouts/modal_table')
@include('layouts/modal_advisor')
@include('layouts/advisor_precipitated_asphaltenes_analysis')
@include('layouts/asphaltenes_data_tree')
@endsection
@section('Scripts')
   @include('js/validate_table')
   @include('css/add_multiparametric')
   @include('js/frontend_validator')
   @include('js/frontend_rules/asphaltene_precipitated')
   @include('js/add_precipitated_asphaltenes_analysis')
   @include('js/advisor')
   @include('js/asphaltenes_data_tree')
   @include('js/modal_error')
   @include('js/modal_error_frontend')
   @include('css/modal_error_frontend')
@endsection