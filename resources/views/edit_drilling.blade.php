@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Drilling')
@section('content')
@include('layouts/modal_error')
<div id="sticky-anchor"  class="col-md-6"></div>
<div class="jumbotron" id="sticky">
   <center>
      Scenario: {!! $scenario->nombre !!} </br> 
      Basin: {!! $scenario->cuenca->nombre !!} - 
      Field: {!! $scenario->campo->nombre !!} - 
      {{-- Producing interval: {!!  $scenario->formacionxpozo->nombre !!} -  --}}
      Well: {!!  $scenario->pozo->nombre !!}</br> 
      User: {!! $scenario->user->fullName !!}
   </center>
</div>
<p></p>
</br>
<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#general_data_c" id="general_data" onclick="switchTab()">General Data</a></li>
         <li><a data-toggle="tab" href="#filtration_functions_c" id="filtration_functions" onclick="switchTab()">Filtration Functions</a></li>
         <li><a data-toggle="tab" href="#drilling_data_c" id="drilling_data" onclick="switchTab()">Drilling Data</a></li>
         <li><a data-toggle="tab" href="#cementing_data_c" id="cementing_data" onclick="switchTab()">Completion Data</a></li>
      </ul>
      <div class="tab-content">
         <div id="general_data_c" class="tab-pane active">
            <br>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> General Data</h4>
               </div>
               <div class="panel-body">
                  <div id="GD" class="panel-collapse collapse in">
                     {!! Form::model('drilling_scenario', ['url' => 'Drilling/update/'.(isset($_SESSION["scenary_id_dup"]) ? $_SESSION["scenary_id_dup"] : $scenario->id), 'method' => 'post', 'id' => 'drillingForm']) !!}
                     <input type="hidden" name="scenary_id" id="scenary_id" value="{{ isset($_SESSION["scenary_id_dup"]) ? $_SESSION["scenary_id_dup"] : $scenario->id }}">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('array_select_interval_general_data') ? 'has-error' : ''}}">
                              {!! Form::label('interval', 'Producing Interval') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                              {!! Form::select('intervalSelect', $scenario->pozo->formacionesxpozo->pluck('nombre', 'id'), null, ['class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'intervalSelect', 'multiple']) !!}
                              {!! $errors->first('array_select_interval_general_data', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-8">
                           <div id="intervalsGeneral_t" class="handsontable"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#InputData"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Input Data</h4>
               </div>
               <div class="panel-body">
                  <div id="InputData" class="panel-collapse collpase in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('inputDataMethodSelect') ? 'has-error' : ''}}">
                              {!! Form::label('inputDataMethod', 'Input Data Method') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                              {!! Form::select('inputDataMethodSelect', ['1'=>'Profile'], null, ['class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'inputDataMethodSelect']) !!}
                              {!! $errors->first('inputDataMethodSelect', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              {{--{!! Form::select('inputDataMethodSelect', ['1'=>'Profile','2'=>'By Intervals'],null, ['class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'inputDataMethodSelect']) !!}--}}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <p></p>
                           <div class="col-md-4"></div>
                           <div class="col-md-3"></div>
                           <div class="col-md-4">{!! Form::button('Plot' , array('class' => 'btn btn-primary', 'onclick' => 'plotProfileData();', 'name' => 'accion', 'id'=>'plotProfile', 'style'=>'display:none;')) !!}</div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div id="profile_g"></div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-8">
                           <div id="byIntervalsInput_t" class="handsontable" style="display:none"></div>
                           <div id="profileInput_t" class="handsontable" style="display:none"></div>
                        </div>
                     </div>
                  </div>
                  {!! Form::hidden('generaldata_table', '', array('id' => 'generaldata_table')) !!}
                  {!! Form::hidden('inputdata_intervals_table', '', array('id' => 'inputdata_intervals_table')) !!}
                  {!! Form::hidden('inputdata_profile_table', '', array('id' => 'inputdata_profile_table')) !!}
                  {!! Form::hidden('MDtop', '', array('id' => 'MDtop')) !!}
                  {!! Form::hidden('MDbottom', '', array('id' => 'MDbottom')) !!}

                  {!! Form::hidden('select_interval_general_data', '', array('id' => 'select_interval_general_data')) !!}
                  {!! Form::hidden('select_input_data', '', array('id' => 'select_input_data')) !!}
                  {!! Form::hidden('select_filtration_function', '', array('id' => 'select_filtration_function')) !!}
                  {!! Form::hidden('only_s', '', array('id' => 'only_s')) !!}
               </div>
            </div>
         </div>
         <div id="filtration_functions_c" class="tab-pane" >
            <br>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#filtration_function"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Filtration Function</h4>
               </div>
               <div class="panel-body">
                  <div id="filtration_function" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('select_filtration_function') ? 'has-error' : ''}}">
                              {!! Form::label('filtration_function_l', 'Filtration Function') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                              {!! Form::select('filtration_function_select', [], null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'filtration_function_select')) !!}
                              {!! $errors->first('select_filtration_function', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('a_factor_l', 'a') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('a_factor_t') ? 'has-error' : ''}}">
                                 {!! Form::text('a_factor_t', $drilling_scenario->a_factor, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'a_factor_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                              {!! $errors->first('a_factor_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('b_factor_l', 'b') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('b_factor_t') ? 'has-error' : ''}}">
                                 {!! Form::text('b_factor_t', $drilling_scenario->b_factor, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'b_factor_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                              {!! $errors->first('b_factor_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div id="drilling_data_c" class="tab-pane" >
            <br>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#DD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Drilling Data</h4>
               </div>
               <div class="panel-body">
                  <div id="DD" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('d_total_exposure_time_l', 'Pumping Time') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('d_total_exposure_time_t') ? 'has-error' : ''}}">
                                 {!! Form::text('d_total_exposure_time_t', $drilling_scenario->d_total_exposure_time, ['placeholder' => 'd', 'class' =>'form-control', 'id' => 'd_total_exposure_time_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">d</span>
                              </div>
                              {!! $errors->first('d_total_exposure_time_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('d_pump_rate_l', 'Pump Rate') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('d_pump_rate_t') ? 'has-error' : ''}}">
                                 {!! Form::text('d_pump_rate_t', $drilling_scenario->d_pump_rate, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'd_pump_rate_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">gpm</span>
                              </div>
                              {!! $errors->first('d_pump_rate_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('d_mud_density_l', 'Mud Density') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('d_mud_density_t') ? 'has-error' : ''}}">
                                 {!! Form::text('d_mud_density_t', $drilling_scenario->d_mud_density, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'd_mud_density_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">lb/gal</span>
                              </div>
                              {!! $errors->first('d_mud_density_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('d_plastic_viscosity_l', 'Plastic Viscosity') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('d_plastic_viscosity_t') ? 'has-error' : ''}}">
                                 {!! Form::text('d_plastic_viscosity_t', $drilling_scenario->d_plastic_viscosity, ['placeholder' => 'cP', 'class' =>'form-control', 'id' => 'd_plastic_viscosity_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">cP</span>
                              </div>
                              {!! $errors->first('d_plastic_viscosity_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('d_yield_point_l', 'Yield Point') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('d_yield_point_t') ? 'has-error' : ''}}">
                                 {!! Form::text('d_yield_point_t', $drilling_scenario->d_yield_point, ['placeholder' => 'bf/100ft²', 'class' =>'form-control', 'id' => 'd_yield_point_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">lbf/100ft²</span>
                              </div>
                              {!! $errors->first('d_yield_point_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('d_rop_l', 'ROP') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('d_rop_t') ? 'has-error' : ''}}">
                                 {!! Form::text('d_rop_t', $drilling_scenario->d_rop, ['placeholder' => 'ft/h', 'class' =>'form-control', 'id' => 'd_rop_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft/h</span>
                              </div>
                              {!! $errors->first('d_rop_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('d_equivalent_circulating_density_l', 'ECD (Equivalent Circulating Density)') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('d_equivalent_circulating_density_t') ? 'has-error' : ''}}">
                                 {!! Form::text('d_equivalent_circulating_density_t', $drilling_scenario->d_equivalent_circulating_density, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'd_equivalent_circulating_density_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">gpm</span>
                              </div>
                              {!! $errors->first('d_equivalent_circulating_density_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              <br>
                              <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="For calculating the ECD you'll need several data: Hole Diameter and Drill Pipe Diameter (General Data Table), Mud Density, and Pump Rate." onclick="calculate_ecd(0)">Calculate ECD</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div id="cementing_data_c" class="tab-pane" >
            <br>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4>
                     Completion Data
                     <div class="pull-right">
                        {!! Form::checkbox('cementingAvailable', $drilling_scenario->cementingAvailable, ($drilling_scenario->cementingAvailable == 1 ? true : false), array('id' => 'check_available')) !!}
                        {!! Form::label('available', 'Available') !!}
                     </div>
                  </h4>
               </div>
               <div class="panel-body" id="div_cemeneting_data">
                  <div id="CD" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('c_total_exposure_time_l', 'Pumping Time') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('c_total_exposure_time_t') ? 'has-error' : ''}}">
                                 {!! Form::text('c_total_exposure_time_t', $drilling_scenario->c_total_exposure_time, ['placeholder' => 'd', 'class' =>'form-control', 'id' => 'c_total_exposure_time_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">d</span>
                              </div>
                              {!! $errors->first('c_total_exposure_time_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('c_pump_rate_l', 'Pump Rate') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('c_pump_rate_t') ? 'has-error' : ''}}">
                                 {!! Form::text('c_pump_rate_t', $drilling_scenario->c_pump_rate, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'c_pump_rate_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">gpm</span>
                              </div>
                              {!! $errors->first('c_pump_rate_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('c_cement_slurry_density_l', 'Cement Slurry Density') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('c_cement_slurry_density_t') ? 'has-error' : ''}}">
                                 {!! Form::text('c_cement_slurry_density_t', $drilling_scenario->c_cement_slurry, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'c_cement_slurry_density_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">lb/gal</span>
                              </div>
                              {!! $errors->first('c_cement_slurry_density_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('c_plastic_viscosity_l', 'Plastic Viscosity') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('c_plastic_viscosity_t') ? 'has-error' : ''}}">
                                 {!! Form::text('c_plastic_viscosity_t', $drilling_scenario->c_plastic_viscosity, ['placeholder' => 'cP', 'class' =>'form-control', 'id' => 'c_plastic_viscosity_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">cP</span>
                              </div>
                              {!! $errors->first('c_plastic_viscosity_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('c_yield_point_l', 'Yield Point') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('c_yield_point_t') ? 'has-error' : ''}}">
                                 {!! Form::text('c_yield_point_t', $drilling_scenario->c_yield_point, ['placeholder' => 'bf/100ft²', 'class' =>'form-control', 'id' => 'c_yield_point_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">bf/100ft²</span>
                              </div>
                              {!! $errors->first('c_yield_point_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('c_equivalent_circulating_density_l', 'ECD (Equivalent Circulating Density)') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('c_equivalent_circulating_density_t') ? 'has-error' : ''}}">
                                 {!! Form::text('c_equivalent_circulating_density_t', $drilling_scenario->c_equivalent_circulating_density, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'c_equivalent_circulating_density_t']) !!}
                                 <span class="input-group-addon" id="basic-addon2">gpm</span>
                              </div>
                              {!! $errors->first('c_equivalent_circulating_density_t', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              <br>
                              <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="For calculating the ECD you'll need several data: Hole Diameter and Drill Pipe Diameter (General Data Table), Cement Slurry Density, and Pump Rate." onclick="calculate_ecd(1)">Calculate ECD</button>
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
<div class="row">
   <div class="col-md-12 scenario-buttons">
      <div align="left">
         <button type="button" class="btn btn-success" onclick="verifyDrilling('save');">Save</button>
         <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
      </div>
      <div align="right">
         <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
         <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
         <button type="button" class="btn btn-primary" style="display: none" onclick="verifyDrilling('run');" id="run_calc">Run</button>
      </div>
   </div>
</div>
{!! Form::Close() !!}
@endsection
@section('Scripts')
   @include('js/regresion')
   @include('js/frontend_validator')
   @include('js/frontend_rules/drilling')
   @include('js/edit_drilling')
   @include('css/edit_drilling')
   @include('js/modal_error')
   @include('js/modal_error_frontend')
   @include('css/modal_error_frontend')
@endsection