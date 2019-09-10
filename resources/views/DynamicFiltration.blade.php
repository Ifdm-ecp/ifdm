@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Drilling')
@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
   ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Dynamic Filtration Functions</h4>
	</div>
	<div class="panel-body">
		<div id="GD" class="panel-collapse collapse in">
			{!! Form::open(array('url'=>'DynamicFiltration', 'method' => 'post')) !!}
			<div class="row">
				<div class="col-md-6">
					{!! Form::label('formation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}

					{!! Form::select('formationSelect', $formations->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'formationSelect')) !!}

				</div>
				<div class="col-md-6">
					{!! Form::label('testName', 'Filtration Function Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
					{!! Form::text('filtration_function_name_t',null, ['placeholder' => '', 'class' =>'form-control', 'id' => 'filtration_function_name_t']) !!}
				</div>
				<br>
				<p></p>
			</div>
			<br>
			<div class="row">
					<div class="col-md-12">
						<div id="filtration_test_t" class="handsontable" style="display:none;"></div>
					</div>
			</div>
			<br>
			<div class="row">
					<div class="group_con" style="display:inline-block; margin-left: 25px;">
				   <span>
				   {!! Form::submit('Save' , array('class' => 'btn btn-primary', 'onclick' => 'verifyFiltrationFunction();', 'name' => 'accion', 'id'=>'run')) !!}
				   </span>
				   <span style="display:none;">
				   	{!! Form::submit('Add Other Filtration Function' , array('class' => 'btn btn-primary', 'onclick' => 'verifyFiltrationFunction();', 'name' => 'accion', 'id'=>'run')) !!}
				   </span>
				   <span>
				   	{!! Form::submit('Add Laboratory Test' , array('class' => 'btn btn-primary',  'onclick' => 'verifyFiltrationFunction();', 'name' => 'accion', 'id'=>'run')) !!}
				   </span>
				   <span>
				   	{!! Form::submit('Cancel' , array('class' => 'btn btn-danger', 'name' => 'accion', 'id'=>'run')) !!}
				   </span>
					</div>
					{!! Form::hidden('filtrationfunctions_table', '', array('id' => 'filtrationfunctions_table')) !!}
			</div>
		</div>
	</div>
</div>
{!! Form::Close() !!}
@endsection
@section('Scripts')
@include('js/DynamicFiltration_js')
@endsection