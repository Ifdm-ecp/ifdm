@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
?>
@include('layouts/modal_error')

</br>

<h2 align="center">Asphaltene Diagnosis Results</h2>
   
<div class="nav">
  @if($asphaltenes_d_diagnosis->status_wr)
    <div class="jumbotron">
      <center>
        <span>Run has not been executed, there is no data to show.</span>
      </center>
    </div>
   @elseif($viscosity_error)
    @if ($viscosity_error == 1)
    <div class="jumbotron">
        <center>
          <span>This module is not designed to handle extra-heavy crude oils or crudes with viscosities higher than 20.000 cp.</span>
        </center>
      </div>
    @endif
   @else
    <div class="tabbable">
        <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
          <li class="active"><a data-toggle="tab" href="#deposited_asphaltenes_results">Deposited Asphaltenes Results</a></li>
          <li><a data-toggle="tab" href="#skin_results">Skin Results</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="deposited_asphaltenes_results">
              <div class="panel-body">
                <div class="col-md-12">
                  <div class="row">
                    <label>Please choose one or more dates for plotting the results</label>
                    <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="date_select" multiple>
                      <option selected disabled>Dates</option>
                        @foreach ($dates_data as $date)
                            <option value = "{!! $date !!}">{!! $date !!}</option>
                        @endforeach
                    </select>
                  </div>
                  <br>
                  <div class="row">
                    <div class="panel panel-default">
                      <div class="panel-heading"><b>Porosity</b></div>
                      <div class="panel-body">
                        <div id="porosity_chart"></div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="panel panel-default">
                      <div class="panel-heading"><b>Permeability</b></div>
                      <div class="panel-body">
                        <div id="permeability_chart"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
              </div>
          </div>

          <div class="tab-pane" id="skin_results">
            <div class="panel-body">
              <div class="panel panel-default">
                <div class="panel-heading"><b>Damage Radius</b></div>
                <div class="panel-body">
                  <div id="damage_radius_chart"></div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading"><b>Skin</b></div>
                <div class="panel-body">
                  <div id="skin_chart"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane" id="cii_analysis">
              <div class="panel panel-default">
                <div class="panel-heading"><b>Colloidal Instability Index Analysis</b></div>
                <div class="panel-body">
                  <div id="cii_chart"></div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading"><b>Stankiewicz Stability Index Analysis</b></div>
                <div class="panel-body">
                  <div id="stankiewicz_chart"></div>
                </div>
              </div>
          </div>
        </div>
    </div>
   @endif
</div>

</br>
<br>
<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
        @if(!$asphaltenes_d_diagnosis->status_wr)
          <a href="{!! url('run_asphaltene_stability',array('id'=>$scenaryId)) !!}" class="maximize btn btn-primary" role="button">Run Asphaltene Stability Analysis</a>
          <a href="{!! url('run_precipitated_asphaltene',array('id'=>$scenaryId)) !!}" class="maximize btn btn-primary" role="button">Run Precipitated Asphaltene Analysis</a>
        @endif
        <a href="{!! url('run_asphaltene_diagnosis',array('id'=>$scenaryId)) !!}" class="maximize btn btn-warning" role="button">Edit</a>
      </p>
   </div>
</div>

<div class="row pull-right">
  <div class="col-xs-12">
    <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
  </div>
</div>
@endsection

@include('css/add_multiparametric')

@section('Scripts')
   @include('js/modal_error')
   @if((boolean) !$asphaltenes_d_diagnosis->status_wr)
   @include('js/results_asphaltenes_diagnosis')
   @endif
@endsection