@extends('layouts.general')

@section('title', 'Manuals')

@section('content')
<br>
<p></p>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> User Manual</h4>
	</div>
	<div class="panel-body">
		<div id="GD" class="panel-collapse collapse">
		<ul>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/user_manual.pdf') !!}')"><h4>User Manual</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/ipr.pdf') !!}')"><h4>IPR</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/asphaltenes.pdf') !!}')"><h4>Asphaltenes</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/dissagregation.pdf') !!}')"><h4>Skin Dissagregation</h4></a></li>
			<!-- <li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/geomechanics.pdf') !!}')"><h4>Geomechanics</h4></a></li> -->
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/drilling.pdf') !!}')"><h4>Drilling & Completion</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/fines.pdf') !!}')"><h4>Fines Migration</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/user_manual/multiparametric.pdf') !!}')"><h4>Multiparametric</h4></a></li>
		</ul>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD2"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Reservoir Damage Documentation</h4>
	</div>
	<div class="panel-body">
		<div id="GD2" class="panel-collapse collapse">
		<ul>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/general_help.pdf') !!}')"><h4>General Help</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/ipr.pdf') !!}')"><h4>IPR</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/asphaltenes.pdf') !!}')"><h4>Asphaltenes</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/dissagregation.pdf') !!}')"><h4>Skin Dissagregation</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/fines.pdf') !!}')"><h4>Fines Migration</h4></a></li>
			<!-- <li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/geomechanical.pdf') !!}')"><h4>Geomechanics</h4></a></li> -->
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/drilling.pdf') !!}')"><h4>Drilling and Completion</h4></a></li>
			<li><a href="#" onclick="window.open('{!! asset('general_manuals/documentation/multiparametric.pdf') !!}')"><h4>Multiparametric</h4></a></li>
		</ul>
		</div>
	</div>
</div>

@endsection


@section('Scripts')



@endsection


