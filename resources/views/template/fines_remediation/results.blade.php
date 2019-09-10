@extends('layouts.ProjectGeneral')

@section('title', 'IFDM Project')

@section('content')

<?php if(!isset($_SESSION)) { session_start(); } ?>

<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky" tabindex='1' ><center>Scenario: {!! $scenary_s->nombre !!} </br> Basin: {!! $basin->nombre !!} - Field: {!! $campo->nombre !!} - Well: {!!  $pozo->nombre !!} </br> User: {!! $user->fullName !!} </center></div>
<br>
@if(is_null($fines->status_wr) || $fines->status_wr)
<div class="panel panel-default" >
  <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Results</h4></div>
  <div class="panel-body">
    <div id="Prod" class="panel-collapse collapse in">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <center>
              <table class="table table-hover table-bordered">
                <tr>
                  <th>Total volumen to inject </th>
                  <th>{{ floatval($acid_test['total_sv']) }} bbl</th>
                </tr>
                <tr>
                  <th>Penetration radius </th>
                  <th>{{ round($fines->invasion_radius,2) }} ft</th>
                </tr>
              </table>
              <hr>
              <div id="grafica_porosity"></div>
              <hr>
              <div id="grafica_permeability"></div>
            </center>                         
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

<div id="loading" style="display: none;"></div>  
<div class="row">
  <div class="col-xs-12">
    <a href="{!! url('finesremediation/edit',$fines->id) !!}" class="btn btn-default" role="button">Edit</a>
  </div>
</div>

@endsection
@section('Scripts')
<script>
  @if(is_null($fines->status_wr) || $fines->status_wr)
  @if(!is_null($acid_test))
  $(document).ready(function() {
    var datos_phi = '{{ $acid_test['data_phi'] }}';
    datos_phi = datos_phi.replace(/&quot;/g,'"');
    datos_phi = JSON.parse(datos_phi);

    var d_phi = [];
    $.each(datos_phi, function(index, val) {
      var val1 = parseFloat(val[0]).toFixed(4);
      var val2 = parseFloat(val[1]).toFixed(4)
      d_phi.push([parseFloat(val1), parseFloat(val2)]);
    });

    var datos_perm = '{{ $acid_test['data_perm'] }}';
    datos_perm = datos_perm.replace(/&quot;/g,'"');
    datos_perm = JSON.parse(datos_perm);

    var d_perm = [];
    $.each(datos_perm, function(index, val) {
      var val1 = parseFloat(val[0]).toFixed(4);
      var val2 = parseFloat(val[1]).toFixed(4);
      d_perm.push([parseFloat(val1), parseFloat(val2)]);
    });

    /* Anteriores arriba, contrarios abajo */

    var datos_phi_ant = '{{ $acid_test['data_phi_ant'] }}';
    datos_phi_ant = datos_phi_ant.replace(/&quot;/g,'"');
    datos_phi_ant = JSON.parse(datos_phi_ant);

    var d_phi_ant = [];
    $.each(datos_phi_ant, function(index, val) {
      var val1 = parseFloat(val[0]).toFixed(4);
      var val2 = parseFloat(val[1]).toFixed(4);
      d_phi_ant.push([parseFloat(val1), parseFloat(val2)]);
    });

    var datos_perm_ant = '{{ $acid_test['data_perm_ant'] }}';
    datos_perm_ant = datos_perm_ant.replace(/&quot;/g,'"');
    datos_perm_ant = JSON.parse(datos_perm_ant);

    var d_perm_ant = [];
    $.each(datos_perm_ant, function(index, val) {
      var val1 = parseFloat(val[0]).toFixed(4);
      var val2 = parseFloat(val[1]).toFixed(4);
      d_perm_ant.push([parseFloat(val1), parseFloat(val2)]);
    });

    var ser_phi = [ {
      name: 'Pre Treatment',
      data: d_phi_ant,
      type: "spline"
    },{
      name: 'Post Treatment',
      data: d_phi,
      type: "spline"
    }];

    var ser_perm = [ {
      name: 'Pre Treatment',
      data: d_perm_ant,
      type: "spline"
    },{
      name: 'Post Treatment',
      data: d_perm,
      type: "spline"
    }];

    setTimeout(function() {
      var graph_por = $('#grafica_porosity').highcharts({
        title: {
          text: 'Porosity',
          x: -20 /* center */
        },
        credits: {
          enabled: false
        },

        legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'middle',
          borderWidth: 0
        },
        tooltip: {
          headerFormat: '<b>{series.name}</b><br />',
          pointFormat: 'x = {point.x}, y = {point.y}'
        },
        xAxis: {
          title: {
            text: 'Radius [ ft ]'
          },
        },
        yAxis: {
          title: {
            text: 'Porosity [ - ]'
          },
          plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
          }]
        },
        plotOptions: {
          series: {
            marker: {
              enabled: false
            }
          }
        },
        series: ser_phi
      });

      console.log(ser_phi);

      var graph_perm = $('#grafica_permeability').highcharts({
        title: {
          text: 'Permeability',
          x: -20 /* center */
        },
        credits: {
          enabled: false
        },

        legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'middle',
          borderWidth: 0
        },
        tooltip: {
          headerFormat: '<b>{series.name}</b><br />',
          pointFormat: 'x = {point.x}, y = {point.y}'
        },
        xAxis: {
          title: {
            text: 'Radius [ ft ]'
          },
        },
        yAxis: {
          title: {
            text: 'Permeability [ mD ]'
          },
          plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
          }]
        },
        plotOptions: {
          series: {
            marker: {
              enabled: false
            }
          }
        },
        series: ser_perm
      });
    }, 100);
  });
  @endif
  @endif
</script>
@endsection
@include('css/iprresults_css')