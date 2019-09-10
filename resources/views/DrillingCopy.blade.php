@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Drilling')
@section('content')

<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
   <center>Scenario: {!! $scenario->nombre !!} </br> Basin: {!! $_SESSION['basin'] !!} - Field: {!! $_SESSION['field'] !!} - Producing interval: {!!  $interval->nombre !!} - Well: {!!  $_SESSION['well'] !!}</br> User: {!! $user->fullName !!}
   </center>
</div>
<p></p>
</br>


<div class="nav">
   <div class="tabbable">
	   <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
	       <li class="active"><a data-toggle="tab" href="#general_data_c" id="general_data">General Data</a></li>
	       <li><a data-toggle="tab" href="#drilling_data_c" id="drilling_data">Drilling Data</a></li>
	       <li><a data-toggle="tab" href="#cementation_data_c" id="cementation_data">Cementation Data</a></li>
	       <li><a data-toggle="tab" href="#filtration_functions_c" id="filtration_functions">Filtration Functions</a></li>
	   </ul>
	   <div class="tab-content">
		   <div id="general_data_c" class="tab-pane active">
		   		<div class="panel panel-default">
		   			<div class="panel-heading">
		   				<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> General Data</h4>
		   			</div>
		   			<div class="panel-body">
		   				<div id="GD" class="panel-collapse collapse in">
		   					{!! Form::open(array('url'=>'Drilling/store', 'method' => 'post')) !!}
		   					<div class="row">
		   						<div class="col-md-6">
		   						  <div class="form-group {{$errors->has('project') ? 'has-error' : ''}}">
		   						    {!! Form::label('formation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}

		   						    {!! Form::select('formationSelect', $formations->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'formationSelect', 'multiple')) !!}

		   						  </div>
		   						</div>
		   						<div class="col-md-6">
		   						  <div class="form-group {{$errors->has('interval') ? 'has-error' : ''}}">
		   						    {!! Form::label('interval', 'Producing Interval') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
		   						    <select class="selectpicker show-tick" data-live-search="true" data-width="100%" data-style="btn-default" id="intervalSelect" name="intervalSelect" multiple>
		   						      <option selected disabled>-</option>
		   						    </select>
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
		   					  <div class="form-group {{$errors->has('project') ? 'has-error' : ''}}">
		   					    {!! Form::label('inputDataMethod', 'Input Data Method') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}

		   					    {!! Form::select('inputDataMethodSelect', array('1'=>'Average','2'=>'By Intervals','3'=>'Profile'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'inputDataMethodSelect')) !!}

		   					  </div>
		   					</div>
		   				</div>
		   				<div class="row">
		   					<div class="col-md-8">
		   						<div id="averageInput_t" class="handsontable" style="display:none"></div>
		   						<div id="byIntervalsInput_t" class="handsontable" style="display:none"></div>
		   						<div id="profileInput_t" class="handsontable" style="display:none"></div>
		   					</div>

		   				</div>
		   				</div>
		   			</div>
		   		</div>	 
		   		<div class="panel panel-default" style="display:none;">
		   			<div class="panel-heading">
		   				<h4><a data-parent="#accordion" data-toggle="collapse" href="#Porosity"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Porosity</h4>
		   			</div>
		   			<div class="panel-body">
		   				<div id="Porosity" class="panel-collapse collpase in">
		   					<div class="row">
		   						<div class="col-md-6">
		   							<div class="form-group {{$errors->has('porosity_select') ? 'has-error' : ''}}">
		   							   {!! Form::label('porosity_select', 'Input Data') !!}<span style='color:red;'>*</span>
		   							   {!! Form::select('input_data_porosity', array(1 => 'Average Porosity', 2 => 'By Intervals', 3 => 'Table'), 'S', ['class' => 'form-control', 'id'=>'input_data_porosity']) !!}
		   							   <!--</div>-->
		   							</div>
		   						</div>
		   						<div class="col-md-6" id="col_average_porosity">
		   							<div class="form-group">
		   							   {!! Form::label('average_porosity', 'Average Porosity') !!}<span style='color:red;'>*</span>
		   							   <div class="input-group {{$errors->has('average_porosity_t') ? 'has-error' : ''}}">
		   							      {!! Form::text('average_porosity_t',null, ['placeholder' => 'xxx', 'class' =>'form-control', 'id' => 'average_porosity_t']) !!}
		   							      <span class="input-group-addon" id="basic-addon2">xxx</span>
		   							   </div>
		   							</div>
		   						</div>
		   					</div>
		   					<div class="row">
		   						<div class="col-md-6">
		   							<div id="porosity_interval" style="display:none;">
		   								<fieldset>
		   									<legend>Porosity by Intervals</legend>
		   									<div id="porosity_interval_t" class="handsontable"></div>
		   								</fieldset>
		   							</div>
		   							<div id="porosity_profile" style="display:none;">
		   								<fieldset>
		   									<legend>Porosity Profile</legend>
		   									<div id="porosity_table_t" class="handsontable"></div>
		   								</fieldset>
		   							</div>
		   						</div>
		   					</div>
		   				</div>
		   			</div>
		   		</div>	   						
		   		<div class="panel panel-default" style="display:none;">
		   			<div class="panel-heading">
		   				<h4><a data-parent="#accordion" data-toggle="collapse" href="#Permeability"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Permeability</h4>
		   			</div>
		   			<div class="panel-body">
		   				<div id="Permeability" class="panel-collapse collpase in">
		   					<div class="row">
		   						<div class="col-md-6">
		   							<div class="form-group {{$errors->has('fractures_density_select') ? 'has-error' : ''}}">
		   							   {!! Form::label('permeability_select', 'Input Data') !!}<span style='color:red;'>*</span>
		   							   {!! Form::select('input_data_permeability', array(1 => 'Average Permeability', 2 => 'By Intervals', 3 => 'Table'), 'S', ['class' => 'form-control', 'id'=>'input_data_permeability']) !!}
		   							   <!--</div>-->
		   							</div>
		   						</div>
		   						<div class="col-md-6" id="col_average_permeability">
		   							<div class="form-group">
		   							   {!! Form::label('average_permeability', 'Average Permeability') !!}<span style='color:red;'>*</span>
		   							   <div class="input-group {{$errors->has('average_porosity_t') ? 'has-error' : ''}}">
		   							      {!! Form::text('average_ppermeability_t',null, ['placeholder' => 'xxx', 'class' =>'form-control', 'id' => 'average_permeability_t']) !!}
		   							      <span class="input-group-addon" id="basic-addon2">xxx</span>
		   							   </div>
		   							</div>
		   						</div>
		   					</div>
		   					<div class="row">
		   						<div class="col-md-6">
		   							<div id="permeability_interval" style="display:none;">
		   								<fieldset>
		   									<legend>Permeability by Intervals</legend>
		   									<div id="permeability_interval_t" class="handsontable"></div>
		   								</fieldset>
		   							</div>
		   							<div id="permeability_profile" style="display:none;">
		   								<fieldset>
		   									<legend>Permeability Profile</legend>
		   									<div id="permeability_table_t" class="handsontable"></div>
		   								</fieldset>
		   							</div>
		   						</div>
		   					</div>
		   				</div>
		   			</div>
		   		</div>	   						
		   		<div class="panel panel-default" style="display:none;">
		   			<div class="panel-heading">
		   				<h4><a data-parent="#accordion" data-toggle="collapse" href="#Fractures_density"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fractures Density</h4>
		   			</div>
		   			<div class="panel-body">
		   				<div id="Fractures_density" class="panel-collapse collpase in">
		   					<div class="row">
		   						<div class="col-md-6">
		   							<div class="form-group {{$errors->has('fractures_density_select') ? 'has-error' : ''}}">
		   							   {!! Form::label('fractures_density_select', 'Input Data') !!}<span style='color:red;'>*</span>
		   							   {!! Form::select('input_data_fractures_density', array(1 => 'Average Fracture Density', 2 => 'By Intervals', 3 => 'Table'), 'S', ['class' => 'form-control', 'id'=>'input_data_fractures_density']) !!}
		   							   <!--</div>-->
		   							</div>
		   						</div>
		   						<div class="col-md-6" id="col_average_fracturesDensity">
		   							<div class="form-group">
		   							   {!! Form::label('average_fractures_density', 'Average Fractures Density') !!}<span style='color:red;'>*</span>
		   							   <div class="input-group {{$errors->has('average_porosity_t') ? 'has-error' : ''}}">
		   							      {!! Form::text('average_fractures_density_t',null, ['placeholder' => 'xxx', 'class' =>'form-control', 'id' => 'average_fractures_density_t']) !!}
		   							      <span class="input-group-addon" id="basic-addon2">xxx</span>
		   							   </div>
		   							</div>
		   						</div>
		   					</div>
		   					<div class="row">
		   						<div class="col-md-6">
		   							<div id="fracturesDensity_interval" style="display:none;">
		   								<fieldset>
		   									<legend>Fractures Density by Intervals</legend>
		   									<div id="fracturesDensity_interval_t" class="handsontable"></div>
		   								</fieldset>
		   							</div>
		   							<div id="fracturesDensity_table" style="display:none;">
		   								<fieldset>
		   									<legend>Fractures Density Profile</legend>
		   									<div id="fracturesDensity_table_t" class="handsontable"></div>
		   								</fieldset>
		   							</div>
		   						</div>
		   					</div>
		   				</div>
		   			</div>
		   		</div>	   						
		   </div>
		   <div id="drilling_data_c" class="tab-pane" >
		   		<div class="panel panel-default">
		   			<div class="panel-heading">
		   				<h4><a data-parent="#accordion" data-toggle="collapse" href="#DD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Drilling Data</h4>
		   			</div>
		   			<div class="panel-body">
		   				<div id="DD" class="panel-collapse collapse in">
		   					<div class="row">
		   						<div class="col-md-6">
			   						<div class="form-group">
			   						   {!! Form::label('total_exposure_time', 'Total Exposure Time') !!} <span style='color:red;'>*</span>
			   						   <div class="input-group {{$errors->has('total_exposure_time_t') ? 'has-error' : ''}}">
			   						      {!! Form::text('total_exposure_time_t',null, ['placeholder' => 'd', 'class' =>'form-control', 'id' => 'total_exposure_time_t']) !!}
			   						      <span class="input-group-addon" id="basic-addon2">d</span>
			   						   </div>
			   						</div>
		   						</div>
		   						<div class="col-md-6">
			   						<div class="form-group">
			   						   {!! Form::label('pump_rate', 'Pump Rate') !!} <span style='color:red;'>*</span>
			   						   <div class="input-group {{$errors->has('pump_rate_t') ? 'has-error' : ''}}">
			   						      {!! Form::text('pump_rate_t',null, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'pump_rate_t']) !!}
			   						      <span class="input-group-addon" id="basic-addon2">gpm</span>
			   						   </div>
			   						</div>
		   						</div>
		   					</div>
		   					<div class="row">
		   						<div class="col-md-6">
			   						<div class="form-group">
			   						   {!! Form::label('max_p_mud', 'Max Mud Density') !!} <span style='color:red;'>*</span>
			   						   <div class="input-group {{$errors->has('max_p_mud_t') ? 'has-error' : ''}}">
			   						      {!! Form::text('max_p_mud_t',null, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'max_p_mud_t']) !!}
			   						      <span class="input-group-addon" id="basic-addon2">lb/gal</span>
			   						   </div>
			   						</div>
		   						</div>
		   						   <div class="col-md-6">
		   								<div class="form-group">
		   							   						   {!! Form::label('min_p_mud', 'Min Mud Density') !!} <span style='color:red;'>*</span>
		   							   						   <div class="input-group {{$errors->has('min_p_mud_t') ? 'has-error' : ''}}">
		   							   						      {!! Form::text('min_p_mud_t',null, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'min_p_mud_t']) !!}
		   							   						      <span class="input-group-addon" id="basic-addon2">lb/gal</span>
		   							   						   </div>
		   								</div>
		   						   </div>

		   					</div>
		   					<div class="row">
		   						<div class="col-md-6">
			   						<div class="form-group">
			   						   {!! Form::label('anular_flow_velocity', 'Anular Flow Velocity') !!} <span style='color:red;'>*</span>
			   						   <div class="input-group {{$errors->has('anular_flow_velocity_t') ? 'has-error' : ''}}">
			   						      {!! Form::text('anular_flow_velocity_t',null, ['placeholder' => 'ft/min', 'class' =>'form-control', 'id' => 'anular_flow_velocity_t']) !!}
			   						      <span class="input-group-addon" id="basic-addon2">ft/min</span>
			   						   </div>
			   						</div>
		   						</div>
		   						<div class="col-md-6">
			   						<div class="form-group">
			   						   {!! Form::label('cost_rate', 'Shear Rate') !!} <span style='color:red;'>*</span>
			   						   <div class="input-group {{$errors->has('cost_rate_t') ? 'has-error' : ''}}">
			   						      {!! Form::text('cost_rate_t',null, ['placeholder' => '1/s', 'class' =>'form-control', 'id' => 'cost_rate_t']) !!}
			   						      <span class="input-group-addon" id="basic-addon2">1/s</span>
			   						   </div>
			   						</div>
		   						</div>
		   					</div>
		   					<div class="row">
		   					   						<div class="col-md-6">
		   						   						<div class="form-group">
		   						   						   {!! Form::label('rop', 'ROP') !!} <span style='color:red;'>*</span>
		   						   						   <div class="input-group {{$errors->has('rop_t') ? 'has-error' : ''}}">
		   						   						      {!! Form::text('rop_t',null, ['placeholder' => 'ft/h', 'class' =>'form-control', 'id' => 'rop_t']) !!}
		   						   						      <span class="input-group-addon" id="basic-addon2">ft/ho</span>
		   						   						   </div>
		   						   						</div>
		   					   						</div>
		   					   						<div class="col-md-6">
		   						   						<div class="form-group">
		   						   						   {!! Form::label('correction_factor', 'Hydrostatic Correction Factor') !!} <span style='color:red;'>*</span>
		   						   						   <div class="input-group {{$errors->has('correction_factor_t') ? 'has-error' : ''}}">
		   						   						      {!! Form::text('correction_factor_t',null, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'correction_factor_t']) !!}
		   						   						      <span class="input-group-addon" id="basic-addon2">gpm</span>
		   						   						   </div>
		   						   						</div>
		   					   						</div>
		   					</div>
		   					<div class="row">
	  						<div class="col-md-6">
			   						<div class="form-group">
			   						   {!! Form::label('overbalance_pressure', 'Overbalance Pressure') !!} <span style='color:red;'>*</span>
			   						   <div class="input-group {{$errors->has('overbalance_pressure_t') ? 'has-error' : ''}}">
			   						      {!! Form::text('overbalance_pressure_t',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'overbalance_pressure_t']) !!}
			   						      <span class="input-group-addon" id="basic-addon2">psi</span>
			   						   </div>
			   						</div>
		   						</div>
		   					</div>
		   				</div>
		   			</div>
		   		</div>
		   </div>
		   <div id="cementation_data_c" class="tab-pane" >
		   	   		<div class="panel panel-default">
		   	   			<div class="panel-heading">
		   	   				<h4><a data-parent="#accordion" data-toggle="collapse" href="#CD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Cementation Data</h4>
		   	   			</div>
		   	   			<div class="panel-body">
		   	   				<div id="CD" class="panel-collapse collapse in">
		   	   					<div class="row">
		   	   						<div class="col-md-6">
		   		   						<div class="form-group">
		   		   						   {!! Form::label('total_exposure_time', 'Total Exposure Time') !!} <span style='color:red;'>*</span>
		   		   						   <div class="input-group {{$errors->has('total_exposure_time_t') ? 'has-error' : ''}}">
		   		   						      {!! Form::text('total_exposure_time_t',null, ['placeholder' => 'd', 'class' =>'form-control', 'id' => 'total_exposure_time_t']) !!}
		   		   						      <span class="input-group-addon" id="basic-addon2">d</span>
		   		   						   </div>
		   		   						</div>
		   	   						</div>
		   	   						<div class="col-md-6">
		   		   						<div class="form-group">
		   		   						   {!! Form::label('pump_rate', 'Pump Rate') !!} <span style='color:red;'>*</span>
		   		   						   <div class="input-group {{$errors->has('pump_rate_t') ? 'has-error' : ''}}">
		   		   						      {!! Form::text('pump_rate_t',null, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'pump_rate_t']) !!}
		   		   						      <span class="input-group-addon" id="basic-addon2">gpm</span>
		   		   						   </div>
		   		   						</div>
		   	   						</div>
		   	   					</div>
		   	   					<div class="row">
		   	   						<div class="col-md-6">
		   		   						<div class="form-group">
		   		   						   {!! Form::label('max_p_mud', 'Max Mud Density') !!} <span style='color:red;'>*</span>
		   		   						   <div class="input-group {{$errors->has('max_p_mud_t') ? 'has-error' : ''}}">
		   		   						      {!! Form::text('max_p_mud_t',null, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'max_p_mud_t']) !!}
		   		   						      <span class="input-group-addon" id="basic-addon2">lb/gal</span>
		   		   						   </div>
		   		   						</div>
		   	   						</div>
		   	   					   						<div class="col-md-6">
		   	   						   						<div class="form-group">
		   	   						   						   {!! Form::label('min_p_mud', 'Min Mud Density') !!} <span style='color:red;'>*</span>
		   	   						   						   <div class="input-group {{$errors->has('min_p_mud_t') ? 'has-error' : ''}}">
		   	   						   						      {!! Form::text('min_p_mud_t',null, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'min_p_mud_t']) !!}
		   	   						   						      <span class="input-group-addon" id="basic-addon2">lb/gal</span>
		   	   						   						   </div>
		   	   						   						</div>
		   	   					   						</div>
		   	   					</div>
		   	   					<div class="row">
		   	   						<div class="col-md-6">
		   		   						<div class="form-group">
		   		   						   {!! Form::label('anular_flow_velocity', 'Anular Flow Velocity') !!} <span style='color:red;'>*</span>
		   		   						   <div class="input-group {{$errors->has('anular_flow_velocity_t') ? 'has-error' : ''}}">
		   		   						      {!! Form::text('anular_flow_velocity_t',null, ['placeholder' => 'ft/min', 'class' =>'form-control', 'id' => 'anular_flow_velocity_t']) !!}
		   		   						      <span class="input-group-addon" id="basic-addon2">ft/min</span>
		   		   						   </div>
		   		   						</div>
		   	   						</div>
		   	   						<div class="col-md-6">
		   		   						<div class="form-group">
		   		   						   {!! Form::label('cost_rate', 'Shear Rate') !!} <span style='color:red;'>*</span>
		   		   						   <div class="input-group {{$errors->has('cost_rate_t') ? 'has-error' : ''}}">
		   		   						      {!! Form::text('cost_rate_t',null, ['placeholder' => '1/s', 'class' =>'form-control', 'id' => 'cost_rate_t']) !!}
		   		   						      <span class="input-group-addon" id="basic-addon2">1/s</span>
		   		   						   </div>
		   		   						</div>
		   	   						</div>
		   	   					</div>
		   	   					<div class="row">
		   	   					   	<div class="col-md-6">
		   	   						   						<div class="form-group">
		   	   						   						   {!! Form::label('overbalance_pressure', 'Overbalance Pressure') !!} <span style='color:red;'>*</span>
		   	   						   						   <div class="input-group {{$errors->has('overbalance_pressure_t') ? 'has-error' : ''}}">
		   	   						   						      {!! Form::text('overbalance_pressure_t',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'overbalance_pressure_t']) !!}
		   	   						   						      <span class="input-group-addon" id="basic-addon2">psi</span>
		   	   						   						   </div>
		   	   						   						</div>
		   	   					   	</div>

		   	   					   	<div class="col-md-6">
		   	   						   						<div class="form-group">
		   	   						   						   {!! Form::label('correction_factor', 'Hydrostatic Correction Factor') !!} <span style='color:red;'>*</span>
		   	   						   						   <div class="input-group {{$errors->has('correction_factor_t') ? 'has-error' : ''}}">
		   	   						   						      {!! Form::text('correction_factor_t',null, ['placeholder' => 'gpm', 'class' =>'form-control', 'id' => 'correction_factor_t']) !!}
		   	   						   						      <span class="input-group-addon" id="basic-addon2">gpm</span>
		   	   						   						   </div>
		   	   						   						</div>
		   	   					   	</div>
		   	   					</div>
		   	   				</div>
		   	   			</div>
		   	   		</div>
		   </div>
		   <div id="filtration_functions_c" class="tab-pane">
		   		<div class="panel panel-default">
		   			<div class="panel-heading">
		   				<h4><a data-parent="#accordion" data-toggle="collapse" href="#FF"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Dynamic Filtration Functions</h4>
		   			</div>
		   			<div class="panel-body">
		   				<div id="FF" class="panel-collapse collapse in">
		   				<div class="row">
		   					<div class="col-md-6">
		   						<fieldset>
		   							<legend>
		   								{!! Form::radio('check_preset_functions', 'check_preset_functions', true, ["id" => "check_preset_functions"]) !!}
		   								<font size=3><b>Preset Filtration Functions</b></font>
		   							</legend>
		   						</fieldset>
		   					</div>
		   				</div>
		   				<div class="div_preset_functions">
		   					<div class="row div_preset_functions">
		   					   	<div class="col-md-6">
			   					   	<div class="form-group {{$errors->has('porosity_select') ? 'has-error' : ''}}">
			   					   	   {!! Form::label('preset_function_select', 'Preset Function') !!}<span style='color:red;'>*</span>
			   					   	   {!! Form::select('preset_function', array(1 => 'Function 1', 2 => 'Function 2', 3 => 'Function 3'), 'S', ['class' => 'form-control', 'id'=>'preset_function']) !!}
			   					   	   <!--</div>-->
			   					   	</div>
		   					   	</div>
		   					</div>
		   					<div class="row div_preset_functions">
		   						<div class="col-md-6">
			   						<div class="form-group">
			   							  {!! Form::label('a_factor', 'a') !!} <span style='color:red;'>*</span>
			   							  <div class="input-group {{$errors->has('a_factor_t') ? 'has-error' : ''}}">
			   							   		{!! Form::text('a_factor_t',null, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'a_factor_t']) !!}
			   							   		<span class="input-group-addon" id="basic-addon2">lb/gal</span>
			   							  </div>
			   						</div>
		   						</div>
		   						<div class="col-md-6 div_preset_functions">
			   						<div class="form-group">
			   							  {!! Form::label('b_factor', 'b') !!} <span style='color:red;'>*</span>
			   							  <div class="input-group {{$errors->has('b_factor_t') ? 'has-error' : ''}}">
			   							   		{!! Form::text('b_factor_t',null, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'b_factor_t']) !!}
			   							   		<span class="input-group-addon" id="basic-addon2">lb/gal</span>
			   							  </div>
			   						</div>
		   						</div>
		   					</div>
		   				</div>
		   				<div class="row">
		   					<div class="col-md-6">
		   						<fieldset>
		   							<legend>
		   								{!! Form::radio('check_laboratory_tests', 'check_laboratory_tests', false, ["id" => "check_laboratory_tests"]) !!}
		   								<font size=3><b>Laboratory Tests</b></font>
		   							</legend>
		   						</fieldset>
		   					</div>
		   				</div>
		   				<div class="div_laboratory_tests">
		   					<div class="row div_laboratory_tests">
		   						<div class="col-md-6">
		   							<div class="form-group">
		   								  {!! Form::label('p_mud', 'Mud Density [lb/gal]') !!} <span style='color:red;'>*</span>
		   								  <div class="input-group {{$errors->has('mud_p_t') ? 'has-error' : ''}}">
		   								   		{!! Form::text('mud_p_t',null, ['placeholder' => 'lb/gal', 'class' =>'form-control', 'id' => 'mud_p_t']) !!}
		   								   		<span class="input-group-addon" id="basic-addon2">lb/gal</span>
		   								  </div>
		   							</div>
		   						</div>
		   						<div class="col-md-6">
		   							<div class="form-group">
		   								  {!! Form::label('nd_n_mud', 'kd/ki Cement Slurry') !!} <span style='color:red;'>*</span>
		   								  <div class="input-group {{$errors->has('nd_n_mud_t') ? 'has-error' : ''}}">
		   								   		{!! Form::text('nd_n_mud_t',null, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'nd_n_mud_t']) !!}
		   								   		<span class="input-group-addon" id="basic-addon2">[-]</span>
		   								  </div>
		   							</div>
		   						</div>
		   					</div>
		   					<div class="row div_laboratory_tests">
		   						<div class="col-md-6">
		   							<div class="form-group">
		   								  {!! Form::label('nd_ni_mud', 'kd/ki Mud') !!} <span style='color:red;'>*</span>
		   								  <div class="input-group {{$errors->has('nd/ni Mud') ? 'has-error' : ''}}">
		   								   		{!! Form::text('nd/ni Mud',null, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'nd/ni Mud']) !!}
		   								   		<span class="input-group-addon" id="basic-addon2">[-]</span>
		   								  </div>
		   							</div>
		   						</div>
		   					</div>
		   					<div class="row div_laboratory_tests">
		   						<div class="col-md-6">
		   							<div id="filtration_test">
		   								<fieldset>
		   									<legend>Filtration Tests</legend>
		   									<div id="filtration_test_t" class="handsontable"></div>
		   								</fieldset>
		   							</div>
		   						</div>
		   					</div>
		   					<div class="row div_laboratory_tests">	
		   						<div class="col-md-10">
		   							<div id="filtration_test">
		   								<fieldset>
		   									<legend>Dynamic Filtration</legend>
		   									<div id="dynamic_filtration_t" class="handsontable"></div>
		   								</fieldset>
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
</div>
@endsection
@section('Scripts')
@include('js/Drilling_js')
@endsection