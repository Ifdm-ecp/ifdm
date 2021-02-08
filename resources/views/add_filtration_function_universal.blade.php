@extends('layouts.basic')
@section('title', 'IFDM Database')
@section('content')
<div class="col-md-12">
	<div class="row">
		<h3>Add Filtration Function</h3>
	</div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading"><h4>Filtration Function Data</h4></div>
			<div class="panel-body">
				{!!Form::open(array('url' => 'add_filtration_function', 'method' => 'post'))!!}
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
						   {!! Form::label('basin', 'Basin') !!}
						   {!! Form::select('basin', $basins->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control', 'id'=>'basin')) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
						   {!! Form::label('field', 'Field') !!}
						   {!! Form::select('field', array(), null, array('placeholder' => '','class'=>'form-control', 'id'=>'field')) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::label('formation', 'Formation') !!}
							{!! Form::select('formation', array(), null, array('placeholder' => '','class'=>'form-control', 'id'=>'formation')) !!}
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="row">{!! Form::hidden('lab_test_counter', 3, array('id' => 'lab_test_counter')) !!}</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							   {!! Form::label('ff_name', 'Filtration Function Name') !!}
							   <div class="input-group" id="filtration_function_name_input">
							      {!! Form::text('filtration_function_name',null, ['placeholder' => '', 'class' =>'form-control','id'=>'filtration_function_name']) !!}
							      <span class="input-group-addon" id="ff_addon"></span>
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
									<div class="form-group">
									   {!! Form::label('a_factor_l', 'a') !!}
									   <div class="input-group" id="mud_density_input">
									      {!! Form::text('a_factor',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'a_factor']) !!}
									      <span class="input-group-addon" id="mud_density_addon">-</span>
									   </div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									   {!! Form::label('b_factor_l', 'b') !!}
									   <div class="input-group" id="kdki_cement_slurry_input">
									      {!! Form::text('b_factor',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'b_factor']) !!}
									      <span class="input-group-addon" id="kdki_cement_slurry_addon">-</span>
									   </div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									   {!! Form::label('mud_density_l', 'Mud Density') !!}
									   <div class="input-group" id="mud_density_input">
									      {!! Form::text('mud_density',null, ['placeholder' => 'lb/gal', 'class' =>'form-control','id'=>'mud_density']) !!}
									      <span class="input-group-addon" id="mud_density_addon">lb/gal</span>
									   </div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									   {!! Form::label('kdki_cement_slurry_l', 'Kd/Ki Cement Slurry') !!}
									   <div class="input-group" id="kdki_cement_slurry_input">
									      {!! Form::text('kdki_cement_slurry',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'kdki_cement_slurry']) !!}
									      <span class="input-group-addon" id="kdki_cement_slurry_addon">-</span>
									   </div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									   {!! Form::label('kdki_mud_l', 'Kd/Ki Mud') !!}
									   <div class="input-group" id="kdki_mud_input">
									      {!! Form::text('kdki_mud',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'kdki_mud']) !!}
									      <span class="input-group-addon" id="kdki_mud_addon">-</span>
									   </div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									   {!! Form::label('core_diameter_l', 'Core Diameter') !!}
									   <div class="input-group" id="core_diameter_input">
									      {!! Form::text('core_diameter',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'core_diameter']) !!}
									      <span class="input-group-addon" id="core_diameter_addon">-</span>
									   </div>
									</div>
								</div>
							</div>
							<div calss="row">
								<div id="a_b_chart"></div>
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
											<div class="form-group">
											   {!! Form::label('mud_density_l', 'Mud Density') !!}
											   <div class="input-group" id="mud_density_input">
											      {!! Form::text('mud_density',null, ['placeholder' => 'lb/gal', 'class' =>'form-control','id'=>'mud_density']) !!}
											      <span class="input-group-addon" id="mud_density_addon">lb/gal</span>
											   </div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
											   {!! Form::label('kdki_cement_slurry_l', 'Kd/Ki Cement Slurry') !!}
											   <div class="input-group" id="kdki_cement_slurry_input">
											      {!! Form::text('kdki_cement_slurry',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'kdki_cement_slurry']) !!}
											      <span class="input-group-addon" id="kdki_cement_slurry_addon">-</span>
											   </div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
											   {!! Form::label('kdki_mud_l', 'Kd/Ki Mud') !!}
											   <div class="input-group" id="kdki_mud_input">
											      {!! Form::text('kdki_mud',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'kdki_mud']) !!}
											      <span class="input-group-addon" id="kdki_mud_addon">-</span>
											   </div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
											   {!! Form::label('core_diameter_l', 'Core Diameter') !!}
											   <div class="input-group" id="core_diameter_input">
											      {!! Form::text('core_diameter',null, ['placeholder' => '-', 'class' =>'form-control','id'=>'core_diameter']) !!}
											      <span class="input-group-addon" id="core_diameter_addon">-</span>
											   </div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<h4>Laboratory Test #1</h4>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
													   {!! Form::label('k_lab_test_l', 'Permeability') !!}
													   <div class="input-group" id="k_lab_test_1_input">
													      {!! Form::text('k_lab_test_1',null, ['placeholder' => 'mD', 'class' =>'form-control k_value','id'=>'k_lab_test_1']) !!}
													      <span class="input-group-addon" id="k_lab_test_1_a">mD</span>
													   </div>
													</div>
												</div>
												<input type="hidden" class="k_hidden" id="k_lab_test_1_hidden" value="false">
												<div class="col-md-6">
													<div class="form-group">
													   {!! Form::label('p_lab_test_l', 'Pob') !!}
													   <div class="input-group" id="p_lab_test_1_input">
													      {!! Form::text('p_lab_test_1',null, ['placeholder' => 'psi', 'class' =>'form-control pob_value','id'=>'p_lab_test_1']) !!}
													      <span class="input-group-addon" id="p_lab_test_1_a">psi</span>
													   </div>
													</div>
												</div>
												<input type="hidden" class="p_hidden" id="p_lab_test_1_hidden" value="false">
											</div>
											<div id="lab_test_1_table" class="lab_test"></div>
											{!! Form::button('Plot' , array('class' => 'btn btn-primary btn-sm', 'onclick' => 'plot_lab_test(1);', 'name' => 'accion', 'id'=>'plot_1')) !!}
											<input type="hidden" class="lab_test_hidden" id="lab_test_1_hidden" value="false">
										</div>
										<div class="col-md-6">
											<h4>Laboratory Test #2</h4>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
													   {!! Form::label('k_lab_test_l', 'Permeability') !!}
													   <div class="input-group" id="k_lab_test_2_input">
													      {!! Form::text('k_lab_test_2',null, ['placeholder' => 'mD', 'class' =>'form-control k_value','id'=>'k_lab_test_2']) !!}
													      <span class="input-group-addon" id="k_lab_test_2_a">mD</span>
													   </div>
													</div>
												</div>
												<input type="hidden" class="k_hidden" id="k_lab_test_2_hidden" value="false">
												<div class="col-md-6">
													<div class="form-group">
													   {!! Form::label('p_lab_test_l', 'Pob') !!}
													   <div class="input-group" id="p_lab_test_2_input">
													      {!! Form::text('p_lab_test_2',null, ['placeholder' => 'psi', 'class' =>'form-control pob_value','id'=>'p_lab_test_2']) !!}
													      <span class="input-group-addon" id="p_lab_test_2_a">psi</span>
													   </div>
													</div>
												</div>
												<input type="hidden" class="p_hidden" id="p_lab_test_2_hidden" value="false">
											</div>
											<div id="lab_test_2_table" class="lab_test"></div>
											{!! Form::button('Plot' , array('class' => 'btn btn-primary btn-sm', 'onclick' => 'plot_lab_test(2);', 'name' => 'accion', 'id'=>'plot_2')) !!}
											<input type="hidden" class="lab_test_hidden" id="lab_test_2_hidden" value="false">
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6">
											<div id="lab_test_1_chart"></div>
										</div>
										<div class="col-md-6">
											<div id="lab_test_2_chart"></div>
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
								</div>
							</div>
						</div>
					</div>
					<br>
					<br>
					<div class="row" id="save_ff_div">
						<div class="col-md-6"></div>
						<div class="col-md-6" align="right">
							{!! Form::submit('Save Filtration Function' , array('class' => 'btn btn-primary', 'onclick' => 'save_filtration_function();', 'name' => 'accion')) !!}
							{!! Form::button('Cancel' , array('class' => 'btn btn-danger', 'onclick' => 'cancel();', 'name' => 'cancel')) !!}
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
  <script src="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.js"></script>
  <link type="text/css" rel="stylesheet" href="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.min.css">
  @include('js/regresion')
  @include('js/add_filtration_function')
@endsection