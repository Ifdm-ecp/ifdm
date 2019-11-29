@extends('layouts.basic')
@section('title', 'IFDM Database')
@section('content')
@include('layouts/modal_error')
<div class="col-md-12">
	<div class="row">
		<h3>Add Filtration Function</h3>
	</div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading"><h4>Filtration Function Data</h4></div>
			<div class="panel-body">
				{!!Form::open(array('url' => 'filtration_function', 'method' => 'post'))!!}
				<div class="row">
					<div class="col-md-4">
						<div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
							{!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
							{!! Form::select('basin', $basins->lists('nombre','id'),null, array('class'=>'form-control', 'id'=>'basin')) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
							{!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
							{!! Form::select('field', array(), null, array('class'=>'form-control', 'id'=>'field')) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
							{!! Form::label('formation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
							{!! Form::select('formation', array(), null, array('class'=>'form-control', 'id'=>'formation')) !!}
						</div>
					</div>
					{!! Form::hidden('select_basin', '', array('id' => 'select_basin')) !!}
					{!! Form::hidden('select_field', '', array('id' => 'select_field')) !!}
					{!! Form::hidden('select_formation', '', array('id' => 'select_formation')) !!}
				</div>
				<div class="col-md-12">
					<div class="row">
						{!! Form::hidden('filtration_function_factors_option', 0, array('id' => 'filtration_function_factors_option')) !!}
						{!! Form::hidden('lab_test_counter', 2, array('id' => 'lab_test_counter')) !!}
					</div>
					<div class="row">
						<div class="form-group {{$errors->has('ff_name') ? 'has-error' : ''}}">
							{!! Form::label('ff_name', 'Filtration Function Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
							{!! Form::text('filtration_function_name', null, ['placeholder' => '', 'class' =>'form-control','id'=>'filtration_function_name']) !!}
						</div>
					</div>

					<div class="row" >
						<div class="panel panel-default">
						  	<div class="panel-heading">Mud Properties</div>
						  	<div class="panel-body">
						    	<div class="row">
						    		<div class="col-md-4">
						    			<div class="form-group {{$errors->has('mud_density') ? 'has-error' : ''}}">
											{!! Form::label('mud_density', 'Density') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
											<div class="input-group">
												{!! Form::text('mud_density', null, ['placeholder' => 'lb/gal', 'class' =>'form-control']) !!}
												<span class="input-group-addon">lb/gal</span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
						    			<div class="form-group {{$errors->has('plastic_viscosity') ? 'has-error' : ''}}">
											{!! Form::label('plastic_viscosity', 'Plastic Viscosity') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
											<div class="input-group">
												{!! Form::text('plastic_viscosity', null, ['placeholder' => 'cP', 'class' =>'form-control']) !!}
												<span class="input-group-addon">cP</span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
						    			<div class="form-group {{$errors->has('yield_point') ? 'has-error' : ''}}">
											{!! Form::label('yield_point', 'Yield Point') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
											<div class="input-group">
												{!! Form::text('yield_point', null, ['placeholder' => 'bf/100ft²', 'class' =>'form-control']) !!}
												<span class="input-group-addon">bf/100ft²</span>
											</div>
										</div>
									</div>
						    	</div>
						    	<div class="row">
						    		<div class="col-md-6">
						    			<div class="form-group {{$errors->has('lplt_filtrate') ? 'has-error' : ''}}">
											{!! Form::label('lplt_filtrate', 'LPLT Filtrate') !!}
											<div class="input-group">
												{!! Form::text('lplt_filtrate', null, ['placeholder' => 'mL', 'class' => 'form-control']) !!}
												<span class="input-group-addon">mL</span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
						    			<div class="form-group {{$errors->has('hpht_filtrate') ? 'has-error' : ''}}">
											{!! Form::label('hpht_filtrate', 'HPHT Filtrate') !!}
											<div class="input-group">
												{!! Form::text('hpht_filtrate', null, ['placeholder' => 'mL', 'class' =>'form-control']) !!}
												<span class="input-group-addon">mL</span>
											</div>
										</div>
									</div>
						    	</div>
						    	<div class="row">
						    		<div class="col-md-6">
						    			<div class="form-group {{$errors->has('ph') ? 'has-error' : ''}}">
											{!! Form::label('ph', 'PH') !!}
											<div class="input-group">
												{!! Form::text('ph', null, ['placeholder' => 'PH', 'class' =>'form-control']) !!}
												<span class="input-group-addon">PH</span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
						    			<div class="form-group {{$errors->has('gel_strength') ? 'has-error' : ''}}">
											{!! Form::label('gel_strength', 'Gel Strength') !!}
											<div class="input-group">
												{!! Form::text('gel_strength', null, ['placeholder' => 'bf/100ft²', 'class' =>'form-control']) !!}
												<span class="input-group-addon">bf/100ft²</span>
											</div>
										</div>
									</div>
						    	</div>
						  	</div>
						</div>
					</div>

					<div class="row" >
						<div class="panel panel-default">
						  	<div class="panel-heading">Cement Properties</div>
						  	<div class="panel-body">
						    	<div class="row">
						    		<div class="col-md-4">
						    			<div class="form-group {{$errors->has('cement_density') ? 'has-error' : ''}}">
											{!! Form::label('cement_density', 'Density') !!}
											<div class="input-group">
													{!! Form::text('cement_density', null, ['placeholder' => 'lb/gal', 'class' =>'form-control']) !!}
													<span class="input-group-addon">lb/gal</span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
						    			<div class="form-group {{$errors->has('cement_plastic_viscosity') ? 'has-error' : ''}}">
											{!! Form::label('cement_plastic_viscosity', 'Plastic Viscosity') !!}
											<div class="input-group">
													{!! Form::text('cement_plastic_viscosity', null, ['placeholder' => 'cP', 'class' =>'form-control']) !!}
													<span class="input-group-addon">cP</span>
											</div>
										</div>
									</div>
									<div class="col-md-4">
						    			<div class="form-group {{$errors->has('cement_yield_point') ? 'has-error' : ''}}">
											{!! Form::label('cement_yield_point', 'Yield Point') !!}
											<div class="input-group">
													{!! Form::text('cement_yield_point', null, ['placeholder' => 'bf/100ft²', 'class' =>'form-control']) !!}
													<span class="input-group-addon">bf/100ft²</span>
											</div>
										</div>
									</div>
						    	</div>
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="panel panel-default">
						  	<div class="panel-heading">Drilling fluid formulation</div>
						  	<div class="panel-body">
						    	<div id="tablaComponents"></div>
						    	{!! Form::hidden('mudComposicion', null, ['id' => 'data']) !!}
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group {{$errors->has('kdki_cement_slurry') ? 'has-error' : ''}}">
								<input type="checkbox" name="check_set_completition_fluids" id="check_set_completition_fluids" >
								{!! Form::label('kdki_cement_slurry', 'Set Kd/Ki Completition Fluids') !!}
								<div class="input-group">
									{!! Form::text('kdki_cement_slurry', null, ['placeholder' => '-', 'class' => 'form-control', 'id' => 'kdki_cement_slurry_factors', 'disabled']) !!}
									<span class="input-group-addon">-</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group {{$errors->has('kdki_mud') ? 'has-error' : ''}}">
								{!! Form::label('kdki_mud', 'Kd/Ki Mud') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
								<div class="input-group">
									{!! Form::text('kdki_mud', null, ['placeholder' => '-', 'class' => 'form-control', 'id' => 'kdki_mud_factors']) !!}
									<span class="input-group-addon">-</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group {{$errors->has('core_diameter') ? 'has-error' : ''}}">
								{!! Form::label('core_diameter', 'Core Diameter') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
								<div class="input-group">
									{!! Form::text('core_diameter', null, ['placeholder' => 'cm', 'class' => 'form-control', 'id' => 'core_diameter_factors']) !!}
									<span class="input-group-addon">cm</span>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<legend>
							{!! Form::radio('check_set_function_factors', 'check_set_function_factors', true, ["id" => "check_set_function_factors"]) !!}
							<font size=3><b>Set Filtration Function Factors</b></font>
						</legend>
					</div>
					<div class="row" id="function_factors">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group {{$errors->has('a_factor') ? 'has-error' : ''}}">
										{!! Form::label('a_factor_l', 'a') !!}
										<div class="input-group" id="a_factor_input">
											{!! Form::text('a_factor', null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'a_factor']) !!}
											<span class="input-group-addon" id="a_factor_addon">-</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group {{$errors->has('b_factor') ? 'has-error' : ''}}">
										{!! Form::label('b_factor_l', 'b') !!}
										<div class="input-group" id="b_factor_input">
											{!! Form::text('b_factor', null, ['placeholder' => '-', 'class' => 'form-control', 'id' => 'b_factor']) !!}
											<span class="input-group-addon" id="b_factor_addon">-</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<legend>
							{!! Form::radio('check_manual_assigment', 'check_manual_assigment', false, ["id" => "check_manual_assigment"]) !!}
							<font size=3><b>Create Filtration Function</b></font>
						</legend>
					</div>
					<div class="row" id="manual_assigment" style="display:none;">
						<div class="col-md-12">
							<div class="row" id="general_data">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6">
											<h4>Laboratory Test #1</h4>
											<div id="lab_test_1_table" class="lab_test"></div>
											{!! Form::button('Plot' , array('class' => 'btn btn-primary btn-sm', 'onclick' => 'plot_lab_test(1);', 'name' => 'accion', 'id'=>'plot_1')) !!}
											<input type="hidden" class="lab_test_hidden" id="lab_test_1_hidden" value="false">
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group {{$errors->has('k_lab_test_1') ? 'has-error' : ''}}">
														{!! Form::label('k_lab_test_l', 'Permeability') !!}
														<div class="input-group" id="k_lab_test_1_input">
															{!! Form::text('k_lab_test_1', null, ['placeholder' => 'mD', 'class' =>'form-control k_value','id'=>'k_lab_test_1']) !!}
															<span class="input-group-addon" id="k_lab_test_1_a">mD</span>
														</div>
													</div>
												</div>
												<input type="hidden" class="k_hidden" id="k_lab_test_1_hidden" value="false">
												<div class="col-md-6">
													<div class="form-group {{$errors->has('p_lab_test_1') ? 'has-error' : ''}}">
														{!! Form::label('p_lab_test_l', 'Pob') !!}
														<div class="input-group" id="p_lab_test_1_input">
															{!! Form::text('p_lab_test_1', null, ['placeholder' => 'psi', 'class' =>'form-control pob_value','id'=>'p_lab_test_1']) !!}
															<span class="input-group-addon" id="p_lab_test_1_a">psi</span>
														</div>
													</div>
												</div>
												<input type="hidden" class="p_hidden" id="p_lab_test_1_hidden" value="false">
											</div>
											<div id="lab_test_1_chart"></div>
										</div>
									</div>									
									<hr>
									<div id="extra_lab_test"></div>
									{!! Form::hidden('lab_test_data', '', array('id' => 'lab_test_data')) !!}
									{!! Form::hidden('k_data', '', array('id' => 'k_data')) !!}
									{!! Form::hidden('p_data', '', array('id' => 'p_data')) !!}
									<div class="row">
										<div class="col-md-8">
											{!! Form::button('Add Extra Laboratory Test' , array('class' => 'btn btn-warning btn-sm', 'onclick' => 'add_extra_lab_test();', 'name' => 'accion', 'id'=>'plot_1')) !!}
										</div>
									</div>
									<br>
									{!! Form::button('Linear Regresssion' , array('class' => 'btn btn-primary btn-sm', 'onclick' => 'linear_regression_plot();')) !!}
								</div>
							</div>
						</div>
					</div>
					<br>
					<br>
					<div class="row" id="function_factors_graph">
						<div id="a_b_chart"></div>
					</div>
					<div class="row" id="manual_assigment_graph">
						<div id="a_b_chart_manual"></div>
					</div>
					<br>
					<br>
					<div class="row" id="save_ff_div">
						<div class="col-md-6"></div>
						<div class="col-md-6" align="right">
							{!! Form::submit('Save Filtration Function' , array('class' => 'btn btn-primary', 'onclick' => 'save_filtration_function();', 'name' => 'accion')) !!}
							<a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
						</div>
					</div>
				</div>

				{!! Form::Close() !!}
			</div>
		</div>
	</div>
</div>

@endsection
@section('Scripts')
  <script src="{{ asset('js/highcharts.js') }}"></script>
  <script src="http://code.highcharts.com/modules/exporting.js"></script>
  <script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
  <link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
  @include('js/regresion')
  <script type="text/javascript">
  	$(document).ready(function(){
  		tablaComponents();
  	});
  </script>
  @include('js/frontend_validator')
  @include('js/frontend_rules/filtration_function')
  @include('filtrationFunction.cuerpo.createJs')
  @include('js/modal_error')
  @include('js/modal_error_frontend')
  @include('css/modal_error_frontend')
@endsection