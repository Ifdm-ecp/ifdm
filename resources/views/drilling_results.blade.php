@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Drilling')
@section('content')
@include('layouts/modal_error')
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
<div id="sticky-anchor" class="col-md-6"></div>
<div id="sticky">
	<center>
		Scenario: {!! $scenario->nombre !!} </br> 
		Basin: {!! $scenario->cuenca->nombre !!} - 
		Field: {!! $scenario->campo->nombre !!} - 
		{{-- Producing interval: {!!  $scenario->formacionxpozo->nombre !!} -  --}}
		Well: {!! $scenario->pozo->nombre !!}</br> 
		User: {!! $scenario->user->fullName !!}
	</center>
</div>
<p></p>
</br>
<div class="nav">
	@if(!$drilling->status_wr)
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Results</h4>
			</div>
			<div class="panel-body">
				<div id="GD" class="panel-collapse collapse in">
					<div class="col-md-12">
						<div class="row" id="graph"></div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th></th>
											<th>Average Calculated Skin [-]</th>
											<th>Maximum Calculated Skin [-]</th>
											<th>Average Invasion Radius [ft]</th>
											<th>Maximum Invasion Radius [ft]</th>
											<th>Total Invasion Volume [bbl]</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>Drilling</th>
											<td>{!! $table_results[0][0] !!}</td>
											<td>{!! $table_results[0][1] !!}</td>
											<td>{!! $table_results[0][2] !!}</td>
											<td>{!! $table_results[0][3] !!}</td>
											<td>{!! $table_results[0][4] !!}</td>
										</tr>

										@if(array_key_exists(1, $table_results) && count($table_results[1]) > 0)
											<tr>
												<th>Completion</th>
												<td>{!! $table_results[1][0] !!}</td>
												<td>{!! $table_results[1][1] !!}</td>
												<td>{!! $table_results[1][2] !!}</td>
												<td>{!! $table_results[1][3] !!}</td>
												<td>{!! $table_results[1][4] !!}</td>
											</tr>
											<tr>
												<th>Total</th>
												<td>{!! $table_results[2][0] !!}</td>
												<td>{!! $table_results[2][1] !!}</td>
												<td>-</td>
												<td>-</td>
												<td>{!! $table_results[2][4] !!}</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						{{-- <div class="row" id="grafico"></div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<h3>Drilling Phase</h3>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Maximum Calculated Skin [-]</th>
											<th>Average Calculated Skin [-]</th>
											<th>Total Invasion Volume (bbl)</th>
											<th>Maximum Invasion Radius (ft)</th>
											<th>Average Invasion Radius (ft)</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>{!! isset($d_maximum_total_skin) ? $d_maximum_total_skin : '-' !!}</td>
											<td>{!! isset($d_average_total_skin) ? $d_average_total_skin : '-' !!}</td>
											<td>{!! isset($total_invasion_radius_drilling) ? $total_invasion_radius_drilling : '-' !!}</td>
											<td>{!! isset($maximum_invasion_radius_drilling) ? $maximum_invasion_radius_drilling : '-' !!}</td>
											<td>{!! isset($average_invasion_radius_drilling) ? $average_invasion_radius_drilling : '-' !!}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<br>
						@if($drilling->cementingAvailable == 1)
							<div class="row">
								<div class="col-md-12">
									<h3>Cementing Phase</h3>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Maximum Calculated Skin [-]</th>
												<th>Average Calculated Skin [-]</th>
												<th>Total Invasion Volume (bbl)</th>
												<th>Maximum Invasion Radius (ft)</th>
												<th>Average Invasion Radius (ft)</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{!! isset($c_maximum_total_skin) ? $c_maximum_total_skin : '-' !!}</td>
												<td>{!! isset($c_average_total_skin) ? $c_average_total_skin : '-' !!}</td>
												<td>{!! isset($total_invasion_radius_cementation) ? $total_invasion_radius_cementation : '-' !!}</td>
												<td>{!! isset($maximum_invasion_radius_cementation) ? $maximum_invasion_radius_cementation : '-' !!}</td>
												<td>{!! isset($average_invasion_radius_cementation) ? $average_invasion_radius_cementation : '-' !!}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<h3>Total Skin</h3>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Calculated Skin - Maximum Total [-]</th>
												<th>Calculated Skin - Average Total [-]</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{!! isset($maximum_total_skin) ? $maximum_total_skin : '-' !!}</td>
												<td>{!! isset($average_total_skin) ? $average_total_skin : '-' !!}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h3>Total Filtration Volume</h3>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Filtration Volume - Maximum Total [bbl]</th>
												<th>Filtration Volume - Average Total [bbl]</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{!! isset($maximum_total_filtration_volume) ? $maximum_total_filtration_volume : '-' !!}</td>
												<td>{!! isset($average_total_filtration_volume) ? $average_total_filtration_volume : '-' !!}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h3>Total Invasion Radius</h3>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Total Invasion Radius - Maximum Total [ft]</th>
												<th>Total Invasion Radius - Average Total [ft]</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{!! isset($maximum_total_invasion_radius) ? $maximum_total_invasion_radius : '-' !!}</td>
												<td>{!! isset($average_total_invasion_radius) ? $average_total_invasion_radius : '-' !!}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						@endif --}}
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
</div>
<div class="row pull-right">
	<br>
	<div class="group_con" style="display:inline-block; margin-left: 25px;">
		<span>
			<a href="{!! url('Drilling/edit', $scenario->id) !!}" class="btn btn-warning" role="button">Edit</a>
		</span>
	</div>
</div>
{!! Form::Close() !!}
@endsection
@section('Scripts')
@if(!$drilling->status_wr)
	@include('js/drilling_results_js')
@endif
@endsection