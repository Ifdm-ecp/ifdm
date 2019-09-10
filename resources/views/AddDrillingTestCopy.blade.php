@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Drilling')
@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Results</h4>
	</div>
	<div class="panel-body">
		<div id="GD" class="panel-collapse collapse in">
			{!! Form::open(array('url'=>'AddDrillingTest', 'method' => 'post')) !!}
			<div class="col-md-12">
				<div class="row" id="grafico">
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						  <h3>Drilling Phase</h3>
						  <table class="table table-bordered">
						    <thead>
						      <tr>
						        <th>Maximum Calculated Skin</th>
						        <th>Average Calculated Skin</th>
						        <th>Total Invasion Radius Volume (bbl)</th>
						        <th>Maximum Invasion Radius (ft)</th>
						        <th>Average Invasion Radius (ft)</th>
						      </tr>
						    </thead>
						    <tbody>
						      <tr>
						        <td>3.61</td>
						        <td>2.92</td>
						        <td>3166</td>
						        <td>13.07</td>
						        <td>6.58</td>
						      </tr>
						    </tbody>
						  </table>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<h3>Cementation Phase</h3>
						<table class="table table-bordered">
						  <thead>
						    <tr>
						      <th>Maximum Calculated Skin</th>
						      <th>Average Calculated Skin</th>
						      <th>Total Invasion Radius Volume (bbl)</th>
						      <th>Maximum Invasion Radius (ft)</th>
						      <th>Average Invasion Radius (ft)</th>
						    </tr>
						  </thead>
						  <tbody>
						    <tr>
						      <td>37.31</td>
						      <td>28.57</td>
						      <td>113</td>
						      <td>2.52</td>
						      <td>1.59</td>
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
						      <th>Calculated Skin - Maximum Total</th>
						      <th>Calculated Skin - Average Total</th>
						      <th>PBU Skin</th>
						    </tr>
						  </thead>
						  <tbody>
						    <tr>
						      <td>40.92</td>
						      <td>31.50</td>
						      <td>50</td>
						    </tr>
						  </tbody>
						</table>
					</div>
				</div>
				<div class="row">
				<br>
				<div class="group_con" style="display:inline-block; margin-left: 25px;">
				   <span>
				   	{!! Form::submit('Edit' , array('class' => 'btn btn-warning', 'onclick' => 'verifyTest();', 'name' => 'accion', 'id'=>'run')) !!}
				   </span>
				</div>
			</div>
			<div class="col-md-6">
			<div class="row">
				<div id="laboratory_test_g"></div>
				{!! Form::hidden('laboratorytest_table', '', array('id' => 'laboratorytest_table')) !!}
				{!! Form::hidden('fft_id', $fft_id, array('id' => 'fft_id')) !!}
			</div>
			</div>
			</div>
		</div>
	</div>
</div>
{!! Form::Close() !!}
@endsection
@section('Scripts')
@include('js/AddDrillingTest_js')
@endsection