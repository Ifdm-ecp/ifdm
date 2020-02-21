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

<h2 align="center">Stability Analysis Results</h2>
<div class="nav">
   @if(!$asphaltenes_d_stability_analysis->status_wr)
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#conclusions">Conclusions</a></li>
         <li><a data-toggle="tab" href="#boer_stability_analysis">Boer Stability Analysis</a></li>
         <li><a data-toggle="tab" href="#cii_analysis">Colloidal Instability Index Analysis</a></li>
      </ul>

      <div class="tab-content">
         <div class="tab-pane active" id="conclusions">
            <br>
            <div class="panel panel-danger">
              <div class="panel-heading"><b>Light Components And Precipitated Asphaltenes</b></div>
              <div class="panel-body">
                  <div id = "light_analysis_problem_level"></div>
                  <div id = "light_analysis_conclusion"></div>
                  <div id = "light_analysis_probability"></div>
              </div>
            </div>

            <div class="panel panel-warning">
              <div class="panel-heading"><b>SARA Stability Analysis</b></div>
              <div class="panel-body">
                  <div id = "sara_analysis_problem_level"></div>
                  <div id = "sara_analysis_conclusion"></div>
                  <div id = "sara_analysis_probability"></div>
              </div>
            </div>

            <div class="panel panel-success">
              <div class="panel-heading"><b>Colloidal Instability Index Analysis</b></div>
              <div class="panel-body">
                  <div id = "colloidal_analysis_problem_level"></div>
                  <div id = "colloidal_analysis_conclusion"></div>
                  <div id = "colloidal_analysis_probability"></div>
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading"><b>Risk Analysis</b></div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-8">
                    <p></p>
                    <div id = "precipitation_risk_light"></div>
                    <div id = "precipitation_risk_sara"></div>
                    <div id = "precipitation_risk_colloidal"></div>
                    <div class="well well-sm" id = "precipitation_risk_fluid"></div>
                  </div>
                  <div class="col-md-4">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Risk</th>
                          <th>Level</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="success">
                          <td>0</td>
                          <td>None</td>
                        </tr>
                        <tr class="success">
                          <td>1</td>
                          <td>Low-low</td>
                        </tr>
                        <tr class="success">
                          <td>2</td>
                          <td>Low-high</td>
                        </tr>
                        <tr class="warning">
                          <td>3</td>
                          <td>Medium-low</td>
                        </tr>
                        <tr class="warning">
                          <td>4</td>
                          <td>Medium-high</td>
                        </tr>
                        <tr class="danger">
                          <td>5</td>
                          <td>High-low</td>
                        </tr>
                        <tr class="danger">
                          <td>6</td>
                          <td>High-high</td>
                        </tr>
                        <tr class="danger">
                          <td>7</td>
                          <td>Severe</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
         </div>

         <div class="tab-pane" id="boer_stability_analysis">
            <br>
            <div id="boer_chart"></div>
         </div>

         <div class="tab-pane" id="cii_analysis">
            <br>
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
   @else
   <div class="jumbotron">
     <center>
       <span>Run has not been executed, there is no data to show.</span>
     </center>
   </div>
   @endif
</div>

</br>
<br>
<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
        @if(!$asphaltenes_d_stability_analysis->status_wr)
        <a href="{!! url('run_asphaltene_diagnosis',array('id'=>$scenaryId)) !!}" class="maximize btn btn-primary" role="button">Run Asphaltene Diagnosis</a>
        <a href="{!! url('run_precipitated_asphaltene',array('id'=>$scenaryId)) !!}" class="maximize btn btn-primary" role="button">Run Precipitated Asphaltene Analysis</a>
        @endif
        <a href="{!! url('run_asphaltene_stability',array('id'=>$scenaryId)) !!}" class="maximize btn btn-warning" role="button">Edit</a>
      </p>
   </div>
</div>

<div class="row pull-right">
  <div class="col-xs-12">
    <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Exit</a>
  </div>
</div>

@endsection

@include('css/add_multiparametric')

@section('Scripts')
   @include('js/modal_error')
   @if(!$asphaltenes_d_stability_analysis->status_wr)
   @include('js/results_asphaltene_stability_analysis')
   @endif
@endsection