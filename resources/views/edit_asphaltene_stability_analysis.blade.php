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

{!!Form::model($asphaltenes_d_stability_analysis, array('route' => array('asphalteneStabilityAnalysis.update', $asphaltenes_d_stability_analysis->id), 'method' => 'PATCH', 'id' => 'asphalteneForm'), array('role' => 'form'))!!}
<input type="hidden" name="id_scenary" id="id_scenary" value="{{ !empty($duplicateFrom) ? $duplicateFrom : $scenary->id }}">

@include('layouts/general_advisor')

<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#stability_analysis">Stability Analysis</a></li>
      </ul>

      <div class="tab-content">
         <div class="tab-pane active" id="stability_analysis">
            <br>
            <div class="panel panel-default">
               <div class="panel-heading"><b>Components Data </b>@if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_components_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
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
                              ],old('components'), array('class'=>'selectpicker mh show-tick', 'data-live-search'=>'true', 'id'=>'components', 'data-width'=>'100%', 'data-style'=>'btn-success', 'multiple'=>'multiple')) !!}
                        </div>
                     </div>
                     <div class="col-md-6" style="overflow: auto;">
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
            </div>

            <div class="panel panel-default">
               <div class="panel-heading"><b>SARA Analysis</b></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('saturated_label', 'Saturated') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('saturated') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('saturated',$asphaltenes_d_stability_analysis->saturated, ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'saturated']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('saturated', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('aromatics_label', 'Aromatics') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('aromatics') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('aromatics',$asphaltenes_d_stability_analysis->aromatics, ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'aromatics']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('aromatics', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('resines_label', 'Resines') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('resines') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('resines',$asphaltenes_d_stability_analysis->resines, ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'resines']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('resines', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('asphaltenes_label', 'Asphaltenes') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('asphaltenes') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('asphaltenes',$asphaltenes_d_stability_analysis->asphaltenes, ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'asphaltenes']) !!}
                              <span class="input-group-addon" id="basic-addon2">% Weight</span>
                           </div>
                           {!! $errors->first('asphaltenes', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
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

            <div class="panel panel-default">
               <div class="panel-heading"><b>Saturation Data</b></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('reservoir_initial_pressure_label', 'Reservoir Initial Pressure') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('reservoir_initial_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('reservoir_initial_pressure',$asphaltenes_d_stability_analysis->reservoir_initial_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'reservoir_initial_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">psi</span>
                           </div>
                           {!! $errors->first('reservoir_initial_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('bubble_pressure_label', 'Bubble Pressure') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('bubble_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('bubble_pressure',$asphaltenes_d_stability_analysis->bubble_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'bubble_pressure']) !!}
                              <span class="input-group-addon" id="basic-addon2">psi</span>
                           </div>
                           {!! $errors->first('bubble_pressure', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('density_at_reservoir_temperature_label', 'Density At Reservoir Temperature') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('density_at_reservoir_temperature') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('density_at_reservoir_temperature',$asphaltenes_d_stability_analysis->density_at_reservoir_temperature, ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'density_at_reservoir_temperature']) !!}
                              <span class="input-group-addon" id="basic-addon2">g/cc</span>
                           </div>
                           {!! $errors->first('density_at_reservoir_temperature', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('api_gravity_label', 'API Gravity') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                           <div class="input-group {{$errors->has('api_gravity') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                              @endif
                              {!! Form::text('api_gravity',$asphaltenes_d_stability_analysis->api_gravity, ['placeholder' => '°API', 'class' =>'form-control', 'id' => 'api_gravity']) !!}
                              <span class="input-group-addon" id="basic-addon2">°API</span>
                           </div>
                           {!! $errors->first('api_gravity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<br>

<div class="row">
   {!! Form::hidden('only_s', '', array('id' => 'only_s')) !!}
   <div class="col-md-12 scenario-buttons">
      <div align="left">
         <button type="button" class="btn btn-success" onclick="verifyAsphaltene('save');">Save</button>
         <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
      </div>
      <div align="right">
         <button type="button" class="btn btn-primary" onclick="verifyAsphaltene('run');" id="run_calc">Run</button>
      </div>
   </div>
</div>
{!! Form::Close() !!}

@include('layouts/modal_table')
@include('layouts/modal_advisor')
@include('layouts/advisor_asphaltene_stability_analysis')
@endsection
@section('Scripts')
   @include('js/validate_table')
   @include('css/add_multiparametric')
   @include('js/frontend_validator')
   @include('js/frontend_rules/asphaltene_stability')
   @include('js/edit_asphaltene_stability_analysis')
   @include('js/advisor')
   @include('js/modal_error')
   @include('js/modal_error_frontend')
   @include('css/modal_error_frontend')
   @include('css/asphaltene_stability');
@endsection