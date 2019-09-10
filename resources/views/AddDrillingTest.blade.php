@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Drilling')
@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Add Laboratory Test</h4>
	</div>
	<div class="panel-body">
		<div id="GD" class="panel-collapse collapse in">
			{!! Form::open(array('url'=>'AddDrillingTest', 'method' => 'post')) !!}
			<div class="col-md-6">
				<div class="row">
					{!! Form::label('testName', 'Test Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
					{!! Form::text('testName_t',null, ['placeholder' => '', 'class' =>'form-control', 'id' => 'testName_t']) !!}
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div id="filtration_test_t" class="handsontable"></div>
					</div>
				</div>
			<div class="row">
				<br>
				<div class="group_con" style="display:inline-block; margin-left: 25px;">
				   <span>
				   {!! Form::submit('Add Another Test' , array('class' => 'btn btn-primary', 'onclick' => 'verifyTest();', 'name' => 'accion', 'id'=>'run')) !!}
				   </span>
				   <span>
				   	{!! Form::button('Plot' , array('class' => 'btn btn-primary', 'onclick' => 'plotLaboratoryTest();', 'name' => 'accion', 'id'=>'run')) !!}
				   </span>
				   <span>
				   	{!! Form::submit('Cancel' , array('class' => 'btn btn-danger',  'name' => 'accion', 'id'=>'run')) !!}
				   </span>
				</div>
			</div>
			<div class="row">

				{!! Form::hidden('laboratorytest_table', '', array('id' => 'laboratorytest_table')) !!}
				{!! Form::hidden('fft_id', $fft_id, array('id' => 'fft_id')) !!}
			</div>
			</div>
			<div class="col-md-6">
				<div class="row"><p></p></div>
				<div class="row"><div id="laboratory_test_g"></div></div>
			</div>
		</div>
	</div>
</div>
{!! Form::Close() !!}
@endsection
@section('Scripts')
@include('js/AddDrillingTest_js')

@endsection