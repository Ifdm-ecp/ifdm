@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')
@section('content')
<?php if(!isset($_SESSION)) { session_start(); } ?>
<div>
    <div id="sticky-anchor"  class="col-md-6"></div>
    <div id="sticky"><center>
        <b>Scenario:</b> {!! $scenary_s->nombre !!} - Field: {!! $campo->nombre !!} - Producing interval: {!!  $intervalo->nombre !!} - Well: {!! $pozo->nombre !!}</center></div>
    </br>
    <br>
    @if(is_null($desagregacion->status_wr) || $desagregacion->status_wr)
    <div class="panel panel-default" >      
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Results By Components</h4></div>
        <div class="panel-body">
            <div id="Prod" class="panel-collapse collapse in">
                <div class="panel-body">
                    <br/>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Skin Configuration</th>
                                <th style="text-align:center">Value</th>
                                <th style="text-align:center">Total Skin Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Skin</td>
                                <td id="tabla11"  align="center"></td>
                                <td id="tabla12" align="center">0</td>
                            </tr>
                            <tr>
                                <td>Mechanical Skin</td>
                                <td id="tabla21" align="center"></td>
                                <td id="tabla22" align="center">0</td>
                            </tr>
                            <tr>
                                <td>Stress-dependent Skin</td>
                                <td id="tabla31" align="center"></td>
                                <td id="tabla32" align="center">0</td>
                            </tr>
                            <tr>
                                <td>Pseudo Skin</td>
                                <td id="tabla41" align="center"></td>
                                <td id="tabla42" align="center">0</td>
                            </tr>
                            <tr>
                                <td>Rate-dependent Skin</td>
                                <td id="tabla51" align="center"></td>
                                <td id="tabla52" align="center">0</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Well friction coefficient [1/ft]</th>
                                <td>{!! sprintf("%E", round($coeficiente_friccion,2)) !!} </td>
                            </tr>
                            <tr>
                                <th>Well permeability module [1/PSI]</th>
                                <td>{!! round($modulo_permeabilidad,6) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="grafica_desagregacion"></div>
                    <div id="grafica_pres_perm"></div>
                    <div id="grafica_aumentada"></div>
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
<div id="loading" style="display:none;"></div>
<div class="row">
    <div class="col-md-12">
        {!! Form::hidden('inputskins', $results , array('id' => 'inputskins')) !!}
        {!! Form::hidden('id', $desagregacion->id, array('id' => 'id')) !!}
        {!! Form::hidden('id_escenario', $desagregacion->id_escenario, array('id' => 'id_escenario')) !!}

        <a href="{{ URL::to('Desagregacion/' . $desagregacion->id_escenario . '/edit') }}" class="btn btn-warning" role="button">Edit</a>
    </div>
</div>

</div>



@endsection

@section('Scripts')
@if(is_null($desagregacion->status_wr) || $desagregacion->status_wr)
@include('js/desagregacion_results')
@endif
@include('css/desagregacion_results')
@endsection