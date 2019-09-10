@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
<?php if(!isset($_SESSION)) { session_start(); } ?>
@include('layouts/modal_error')
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky" style="position: relative;">
  <center><b>Scenario: </b>{!! $statistical->escenario->nombre !!} </br> Basin: {!! $statistical->escenario->cuenca->nombre !!} - Field: {!! $statistical->escenario->campo->nombre !!} - Producing interval: {!! $statistical->escenario->formacionxpozo->nombre !!} - Well: {!!  $statistical->escenario->pozo->nombre !!} - User: {!! $statistical->escenario->user->name !!}</center>
</div>

</br>
<hr>
@if(!$statistical->status_wr)
<div class="row">
  <div class="col-md-6">
    <div  id="container"></div>
  </div>
  <div class="col-md-4 col-md-offset-1">
    <p> </p>            
    <p> </p>            
    <p> </p>            
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Damage Mechanism</th>
          <th>Skin [%]</th>
        </tr>
      </thead>
      <tbody id="statistical_body">
      </tbody>
    </table>
  </div>
</div>
<hr>
@else
<div class="jumbotron">
 <center>
   <span>Run has not been executed, there is no data to show.</span>
 </center>
</div>
@endif

<p class="pull-right">            
  <a href="{{route('statistical.edit', $statistical->id)}}" class="btn btn-warning" role="button">Edit</a>
  <a type="button"  name="back" class="btn btn-danger" onClick="javascript:history.back(1)" role="button">Cancel</a>
</p>

@endsection
@section('Scripts')
@include('css/add_multiparametric')
@include('js/modal_error')
@if(!$statistical->status_wr)
<script type="text/javascript">
  $(document).ready(function(){
    graficar();
    tablaGraficar();
  });


  function graficar()
  {
    $('#container').highcharts({

      chart: {
        polar: true, 
        type: 'line'
      },

      title: {
        text: '',
        x: -80
      },
      subtitle: {
        text: '',
        x: -80
      },

      pane: {
        size: '80%'
      },

      xAxis: {
        categories: ['Mineral Scales','Fines Blockage','Organic Scales','Relative Permeability','Induced Damage','Geomechanical Damage'],
        tickmarkPlacement: 'on',
        lineWidth: 0
      },

      yAxis: {
        gridLineInterpolation: 'polygon',
        lineWidth: 0,
        min: 0
      },

      tooltip: {
        valueDecimals: 4,
        valueSuffix: ' %'
      },

      legend: {
        align: 'right',
        verticalAlign: 'top',
        y: 70,
        layout: 'vertical'
      },

      series: [{
        name: "Statistical Skin",
        data: {{ $datos }},
        pointPlacement: 'on'
      }]
    });
  }


  function tablaGraficar()
  {
    var data = {{ $datos }};
    var data_table_final = [];

    var data_table = [[data[0], "Mineral Scales"], [data[1], "Fines Blockage"], [data[2], "Organic Scales"], [data[3], "Relative Permeability"],[data[4], "Induced Damage"],[data[5], "Geomechanical Damage"]];

    for (var i = 0; i <= data_table.length; i++) 
    {
      for (var j = 0; j <= data_table.length; j++) 
      {
        if(parseFloat(data_table[j])<parseFloat(data_table[i]))
        {
          var aux = data_table[j];
          data_table[j] = data_table[i];
          data_table[i] = aux; 
        }
      }
    }
    data_table_final.push(data_table);

    $("#statistical_body").append (
      '<tr>'+
      '<th>'+data_table_final[0][0][1]+'</th>'+
      '<th>'+parseFloat(data_table_final[0][0][0]).toFixed(4)+' % </th>'+
      '</tr>' +
      '<tr>'+
      '<th>'+data_table_final[0][1][1]+'</th>'+
      '<th>'+parseFloat(data_table_final[0][1][0]).toFixed(4)+' % </th>'+
      '</tr>' +
      '<tr>'+
      '<th>'+data_table_final[0][2][1]+'</th>'+
      '<th>'+parseFloat(data_table_final[0][2][0]).toFixed(4)+' % </th>'+
      '</tr>' +
      '<tr>'+
      '<th>'+data_table_final[0][3][1]+'</th>'+
      '<th>'+parseFloat(data_table_final[0][3][0]).toFixed(4)+' % </th>'+
      '</tr>' +
      '<tr>'+
      '<th>'+data_table_final[0][4][1]+'</th>'+
      '<th>'+parseFloat(data_table_final[0][4][0]).toFixed(4)+' % </th>'+
      '</tr>' +
      '<tr>'+
      '<th>'+data_table_final[0][5][1]+'</th>'+
      '<th>'+parseFloat(data_table_final[0][5][0]).toFixed(4)+' % </th>'+
      '</tr>' 
      );
  }
</script>
@endif
@endsection
