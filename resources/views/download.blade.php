@extends('layouts.ProjectGeneral')

@section('title', 'Download')

@section('content')
<br>
<p></p>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Help Info</h4>
	</div>
	<div class="panel-body">
		<div id="GD" class="panel-collapse collapse">
		<ul>
		      <li><a href="#" onclick="window.open('{!! asset('manual.pdf') !!}')"><h4>User Manual</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('help.pdf') !!}')"><h4>General Help</h4></a></li>
		</ul>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD2"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Formation Damage Course Dec/2016</h4>
	</div>
	<div class="panel-body">
		<div id="GD2" class="panel-collapse collapse">
		<ul>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Agenda Curso DF.pdf') !!}')"><h4>Agenda</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Introduccion.pdf') !!}')"><h4>Introducción</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Analisis Probabilistico.pdf') !!}')"><h4>Análisis Probabilístico</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Asistente Estimulacion.pdf') !!}')"><h4>Asistente Estimulación</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Dano Geomecanico.pdf') !!}')"><h4>Daño Geomecánico</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Dano por Asfaltenos.pdf') !!}')"><h4>Daño por Asfaltenos</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Dano por Componentes.pdf') !!}')"><h4>Daño por Componentes</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Dano por Finos.pdf') !!}')"><h4>Daño por Finos</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Dano por Fluidos de Perforacion.pdf') !!}')"><h4>Daño por Fluidos de Perforación</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Dano por Parafinas.pdf') !!}')"><h4>Daño por Parafinas</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Escamas Minerales.pdf') !!}')"><h4>Escamas Minerales</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Flujo de Diagnostico Ecopetrol.pdf') !!}')"><h4>Flujo de Diagnostico Ecopetrol</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Herramienta Modelamiento de DF.pdf') !!}')"><h4>Herramienta Modelamiento de DF</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Nanotecnologia.pdf') !!}')"><h4>Nanotecnología</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Simulacion Molecular.pdf') !!}')"><h4>Simulación Molecular</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Lista Asistencia Ecopetrol Bogota.pdf') !!}')"><h4>Lista Asistencia Ecopetrol Bogotá</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Lista Asistencia Equion Bogota.pdf') !!}')"><h4>Lista Asistencia Equion Bogotá</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2016/Lista Asistencia On-line.pdf') !!}')"><h4>Lista Asistencia On-line</h4></a></li>

		</ul>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4><a data-parent="#accordion" data-toggle="collapse" href="#GD3"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Formation Damage Course May/2018</h4>
	</div>
	<div class="panel-body">
		<div id="GD3" class="panel-collapse collapse">
		<ul>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM - IFDM.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - IFDM</h4></a></li>
		      <li><a href ='{!! asset('curso2018/Curso Dano de Formacion_IFDM_Plan de trabajo.xlsx') !!}' download><h4>Curso Daño de Formación_IFDM - Plan de trabajo</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM - Dano de Formacion.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - Daño de Formación</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM_Dano Inducido.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - Daño Inducido</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM_Estretegia.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - Estretegia</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM_Finos.pdf') !!}')"><h4>Curso Daño de Formacion_IFDM - Finos</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM_Introduccion A.Restrepo.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - Introducción A.Restrepo</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM_Modelo Multiparametrico.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - Modelo Multiparamétrico</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM_RS_14_Formation Damage_OriginDiagnosisandTreatment Strategy.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - RS_14_Formation Damage_Origin, Diagnosis and Treatment Strategy</h4></a></li>
		      <li><a href="#" onclick="window.open('{!! asset('curso2018/Curso Dano de Formacion_IFDM - Escamas minerales.pdf') !!}')"><h4>Curso Daño de Formación_IFDM - Daño por escamas minerales</h4></a></li>
		      <li><a href="#" onclick="window.open('https://goo.gl/forms/udLlHRsEIaI1KiNk1')"><h4>Encuesta Curso IFDM</h4></a></li>

		</ul>
		</div>
	</div>
</div>


@endsection


@section('Scripts')



@endsection


