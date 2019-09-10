@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Geomechanical Diagnosis')
@section('content')
<style type="text/css">
#sticky {
	padding: 0.5ex;
	background-color: #333;
	color: #fff;
	width: 100%;
	font-size: 1.5em;
	border-radius: 0.5ex;
}
</style>
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
	<center>
		Scenario: {!! $scenary->nombre !!} </br> 
		Basin: {!! $scenary->cuenca->nombre !!} - 
		Field: {!! $scenary->campo->nombre !!} - 
		Producing interval: {!!  $scenary->formacionxpozo->nombre !!} - 
		Well: {!!  $scenary->pozo->nombre !!}</br> 
		User: {!! $scenary->user->fullName !!}
	</center>
</div>
<p></p>
</br>
<div class="nav">
	@if(is_null($geomechanical_diagnosis->status_wr) || !$geomechanical_diagnosis->status_wr)
	<div class="tabbable">
		<ul class="nav nav-tabs" data-tabs="tabs" id="results_tab">
			<li class="active"><a data-toggle="tab" href="#fractures_results">Fracture Scale Results</a></li>
			{{-- <li><a data-toggle="tab" href="#intervals_results">Interval Scale Results</a></li> --}}
			<li><a data-toggle="tab" href="#fracture_spatial_distributioin">Fracture Spatial Distribution</a></li>
			<li><a data-toggle="tab" href="#pore_pressure">Pore Pressure Results</a></li>
			<li><a data-toggle="tab" href="#effective_stresses">Effective Stresses</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="fractures_results">
				<div class="col-md-12">
					<br>
					<div class="row">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4><a data-parent="#accordion"></a>Single Fracture Results</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											{!! Form::label('fracture_results', 'Please, choose the fracture') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
											{!! Form::select('fracture_select', [], null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'fracture_select')) !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12"><div id="kfracture_polar_chart"></div></div>
								</div>
								{{--
								<div class="row">
									<div class="col-md-12"><div id="wfracture_polar_chart"></div></div>
								</div>
								--}}
								<div class="row">
									<div class="col-md-12"><div id="normal_stress_polar_chart"></div></div>
								</div>
								<br>
								<br>
								<div class="row">
									<div class="col-md-12">	
										<div id="2d_graphs_by_fracture" style="Display:None">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4><a data-parent="#accordion"></a>Permeability vs Radius</h4>
												</div>
												<div class="panel-body">
													
													<div class="col-md-8">
														<div class="form-group">
															{!! Form::label('permeability_radius', 'Select angles [º]', array('id'=>'permeability_radius')) !!}
															{!! Form::select('thetas', $thetas, null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'permeability_radius_select', 'multiple'=>'multiple')) !!}
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-12"><div id="permeability_radius_graph"></div></div>
													</div>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4><a data-parent="#accordion"></a>Width vs Radius</h4>
												</div>
												<div class="panel-body">
													
													<div class="col-md-8">
														<div class="form-group">
															{!! Form::label('width_radius', 'Select angles [º]', array('id'=>'width_radius')) !!}
															{!! Form::select('thetas', $thetas, null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'width_radius_select', 'multiple'=>'multiple')) !!}
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-12"><div id="width_radius_graph"></div></div>
													</div>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4><a data-parent="#accordion"></a>Permeability vs Effective Normal Stress</h4>
												</div>
												<div class="panel-body">

													<div class="col-md-8">
														<div class="form-group">
															{!! Form::label('normal_stress_radius', 'Select angles [º]', array('id'=>'normal_stress_radius')) !!}
															{!! Form::select('thetas', $thetas, null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'normal_stress_radius_select', 'multiple'=>'multiple')) !!}
														</div>
													</div>

													<div class="row">
														<div class="col-md-12"><div id="permeability_normal_stress_graph"></div></div>
													</div>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4><a data-parent="#accordion"></a>Permeability vs Angle</h4>
												</div>
												<div class="panel-body">
													<div class="col-md-8">
														<div class="form-group">
															{!! Form::label('permeability_theta', 'Select radii [ft]', array('id'=>'width_theta')) !!}
															{!! Form::select('radii', $radii, null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'permeability_theta_select', 'multiple'=>'multiple')) !!}
														</div>
													</div>
													<div class="row">
														<div class="col-md-12"><div id="permeability_theta_graph"></div></div>
													</div>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4><a data-parent="#accordion"></a>Width vs Angle</h4>
												</div>
												<div class="panel-body">
													<div class="col-md-8">
														<div class="form-group">
															{!! Form::label('width_theta', 'Select radii [ft]', array('id'=>'width_theta')) !!}
															{!! Form::select('radii', $radii, null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'width_theta_select', 'multiple'=>'multiple')) !!}
														</div>
													</div>
													<div class="row">
														<div class="col-md-12"><div id="width_theta_graph"></div></div>
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
			{{-- 
			<div class="tab-pane" id="intervals_results">
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4><a data-parent="#accordion"></a>Average Results</h4>
					</div>
					<div class="panel-body">
						<div id="GD" class="panel-collapse collapse in">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
									  {!! Form::label('intervals_results', 'Please, choose one') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
									  {!! Form::select('interval_select', [], null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'interval_select')) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12"><div id="average_polar_chart"></div></div>
							</div>
							<div class="row">
								<div class="col-md-12"><div id="average_only_fractures_polar_chart"></div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			--}}
			<div class="tab-pane" id="fracture_spatial_distributioin">
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4><a data-parent="#accordion"></a>Fracture Spatial Distribution</h4>
					</div>
					<div class="panel-body">
						<div id="GD" class="panel-collapse collapse in">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										{!! Form::label('depths', 'Current depth') !!}{!! Form::label('depth_value', ":   " . $min_depth . " ft", array('id' => 'depth_value', 'text-align' => 'center')) !!}
										<input type="range" class="form-control-range" min="{{$min_depth}}" max="{{$max_depth}}" value="{{$min_depth}}" step="1" id="depth_range" name="depth_range">
										<output for="depth_range" onforminput="value = depth_range.valueAsNumber;"></output>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12"><div id="fracture_spatial_distribution_chart"></div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="effective_stresses">
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4><a data-parent="#accordion"></a>Effective Stresses</h4>
					</div>
					<div class="panel-body">
						<div id="GD" class="panel-collapse collapse in">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										{!! Form::label('depths_effective', 'Current depth') !!}{!! Form::label('depth_value', ":   " . $min_depth . " ft", array('id' => 'depth_value_effective_stresses', 'text-align' => 'center')) !!}
										<input type="range" class="form-control-range" min="{{$min_depth}}" max="{{$max_depth}}" value="{{$min_depth}}" step="1" id="depth_range_effective_stresses" name="depth_range_effective_stresses">
										<output for="depth_range_effective_stresses" onforminput="value = depth_range_effective_stresses.valueAsNumber;"></output>
										<div class="row">
											<div class="col-md-8"> 
												{!! Form::label('angles_effective', 'Select angle [º]', array('id'=>'angles_effective')) !!}
												{!! Form::select('thetas_effective_stresses', $thetas, null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'thetas_effective_stresses')) !!}
											</div>
										</div>
										<br>
									  {{-- 
									  <div class="row">
										<div class="col-md-8"> 
									  		{!! Form::label('bhfp_effective', 'Select BHFP [psi]', array('id'=>'bhfp_effective')) !!}
									  		{!! Form::select('bhfp_effective_stresses', $well_bottom_pressure_array, null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'bhfp_effective_stresses')) !!}
										</div>
									  </div>
									  --}}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12"><div id="effective_stresses_chart"></div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="pore_pressure">
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4><a data-parent="#accordion"></a>Pore Pressure Vs Radius</h4>
					</div>
					<div class="panel-body">
						<div id="GD" class="panel-collapse collapse in">
							<div class="row">
								<div class="col-md-12"><div id="pore_pressure_chart"></div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@else
	<div class="jumbotron">
		<center>
			<span>Run has not been executed, there is no data to show.</span>
		</center>
	</div>
	@endif

	<div class="row">
		<br>
		<div class="group_con" style="display:inline-block; margin-left: 25px;">
			<span>
				<a href="{{ URL::to('geomechanical_diagnosis/' . $scenario_id . '/edit') }}" class="btn btn-warning" role="button">Edit</a>
			</span>
		</div>
	</div>
</div>

{!! Form::Close() !!}
@endsection
@section('Scripts')
@include('js/geomechanical_diagnosis_results')
@endsection