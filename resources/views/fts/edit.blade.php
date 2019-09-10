@extends('layouts.ProjectGeneral')
@section('title', 'Fines Treatment Selection')

@section('content')
@include('layouts/modal_error')

	<div id="sticky">
	   <center>
	   		<b>Scenario: </b>{!! $scenary->nombre !!} </br> Basin: {!! $_SESSION['basin'] !!} - Field: {!! $_SESSION['field'] !!} - Producing interval: {!!  $_SESSION['formation_id'] !!} - Well: {!!  $_SESSION['well'] !!} - User: {!! $user->fullName !!}
	   	</center>
	</div>
	<br><br>
	@include('layouts/general_advisor')
	@include('layouts/advisor_fines_treatment_selection')
	<ul class="nav nav-tabs">
	    <li class="active"><a data-toggle="tab" href="#general">General data</a></li>
	    <li><a data-toggle="tab" href="#mineral">Mineralogy</a></li>
	</ul>
	@include('fts.form.form', ['datos' => $datos, 'route' => ['fts.update', $datos->id], 'method' => 'PATCH', 'duplicateFrom' => $duplicateFrom])
@stop
@section('Scripts')

  @include('css/add_multiparametric')
  @include('js/modal_error')

  	@if($advisor == true)	
	  	<script type="text/javascript">
		  	$(document).ready(function () {
		        import_tree("Fines Treatment Selection", "fines_treatment_selection");
		    });
	  	</script>
	  	<script type="text/javascript" src="{{asset('js/fts.js')}}"></script>
	  	@include('layouts/modal_advisor')
	  	@include('js/advisor')
	@endif

  
@endsection