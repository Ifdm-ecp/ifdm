@extends('layouts.ProjectGeneral')

@section('title', 'IFDM Project')

@section('content')

<?php if(!isset($_SESSION)) { session_start(); } ?>

<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky" tabindex='1' >
  <center>
        <b>Scenario: </b>{!! $scenary->nombre !!} </br> Basin: {!! $scenary->cuenca->nombre !!} - Field: {!! $scenary->campo->nombre !!} - Producing interval: {!!  $scenary->formacionxpozo->nombre !!} - Well: {!!  $scenary->pozo->nombre !!} - User: {!! $scenary->user->name !!}
  </center>
</div>
<br>
@if(is_null($asphaltene->status_wr) || $asphaltene->status_wr)
<div class="panel panel-default" >
  <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#divTreatmentVolume"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Treatment Volume</h4></div>
  <div class="panel-body">
    <div id="divPorosity" class="panel-collapse collapse in">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <tbody>
              <strong><center>{{ round($data[0], 2) }} [bbl]</center></strong>
            </tbody>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default" >
  <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#divPorosity"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Porosity</h4></div>
  <div class="panel-body">
    <div id="divPorosity" class="panel-collapse collapse in">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <center>
              <div id="containerPorosity"></div>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default" >
  <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#divPermeability"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Permeability</h4></div>
  <div class="panel-body">
    <div id="divPermeability" class="panel-collapse collapse in">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <center>
              <div id="containerPermeability"></div>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default" >
  <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#divSkin"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Skin</h4></div>
  <div class="panel-body">
    <div id="divSkin" class="panel-collapse collapse in">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <center>
              <table class="table">
                  <tbody>
                    @for($i=0; $i<3; $i++)
                      <tr>
                        <th><center>Skin for efficiencie {{ ($data == 'incomplete') ? '-' : $data[6][$i] }}  = {{ ($data == 'incomplete') ? '-' : round($data[1][$i], 4)}}</center></th>
                      </tr>
                    @endfor
                  </tbody>
              </table>
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
    <a href="{!! url('asphalteneremediation/edit',$scenary->asphalteneRemediations->id) !!}" class="btn btn-default" role="button">Edit</a>
  </div>
</div>

@endsection
@section('Scripts')
  <script type="text/javascript">
    @if(is_null($asphaltene->status_wr) || $asphaltene->status_wr)
    $(document).ready(function(){
      var data = {{ ($data == 'incomplete') ? 'false' : json_encode($data) }};
      console.log(data);
      graficaPorosity(data);
      graficaPermeability(data);
    });


    function graficaPorosity(data)
    {

      Highcharts.chart('containerPorosity', {
        chart: {
          type: 'spline',
          description: '',
          zoomType: 'xy'
        },

        legend: {
          symbolWidth: 40
        },

        title: {
          text: 'Porosity Vs Radius'
        },

        yAxis: {
          title: {
            text: 'Porosity [-]'
          }
        },

        xAxis: {
          title: {
            text: 'Radius [ft]'
          },
          max: (!data ? '-' : data[9] + 5)
        },

        tooltip: {
          split: true
        },

        series: [
          {
            name: 'With damage',
            data: (!data ? '-' : data[8])
          }
          @if($data != 'incomplete')
          @foreach($data[3] as $key => $value)
          , {
            name: 'Efficiency {{ $data == 'incomplete' ? '-' : $data[6][$key] }}',
            data: {{json_encode($value)}}
          }
          @endforeach
          @endif


        ]
      });
    }



    function graficaPermeability(data)
    {

      Highcharts.chart('containerPermeability', {
        chart: {
          type: 'spline',
          description: '',
          zoomType: 'xy'
        },

        legend: {
          symbolWidth: 40
        },

        title: {
          text: 'Permeability Vs Radius'
        },


        yAxis: {
          title: {
            text: 'Permeability [mD]'
          }
        },

        xAxis: {
          title: {
            text: 'Radius [ft]'
          },
          max: (!data ? '-' : data[9] + 5)
        },

        tooltip: {
          split: true
        },

        series: [
          {
            name: 'With damage',
            data: (!data ? '-' : data[7])
          }

          @if($data != 'incomplete')
          @foreach($data[4] as $key => $value)
          , {
            name: 'Efficiency {{$data[6][$key]}}',
            data: {{json_encode($value)}}
          }
          @endforeach
          @endif


        ]
      });
    }

  </script>
@endif
@endsection
@include('css/iprresults_css')
