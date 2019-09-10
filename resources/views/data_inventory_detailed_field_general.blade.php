@extends('layouts.generaldatabase')

@section('title', 'Data Inventory')
<?php 
if (!\Auth::check()) 
{
  return view('loginfirst');
}
 ?>
@section('content')

<div class="row">
  <div class="panel panel-default">
      <div class="panel-body">
        <b><center><h1>DETAILED DATA INVENTORY - FIELD</h1></center></b><br>
        <p align="justify">In the Detailed Data Inventory module, the user can check the existent and missing information for each well in database based on the different types of scenarios implemented (Multiparametric, IPR, Disaggregation and Drilling and Completion).</p>
        <br>
        <div class="row">
          <div class="col-md-4">
            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basin" >
              <option selected disabled>Basin</option>
              @foreach ($cuencas as $cuenca)
                <option value="{!!$cuenca->id!!}">{!!$cuenca->nombre!!}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="field">
              <option selected disabled>Field</option>
            </select>
          </div>
        </div>
        <br>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4><a data-parent="#accordion" data-toggle="collapse" href="#general_data"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>General Data</h4>
          </div>
            <div class="panel-body">
              <div class="panel-collapse collapse" id="general_data">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">Fluid Properties</div>
                        <div class="panel-body"><table class="table" id="io_fluid_properties_table"></table></div>
                      </div>
                    </div>
                  </div>
                  <div class="highcharts" id="pvt_chart"></div>
                </div>
              </div>
            </div>
        </div>
        <br>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4><a data-parent="#accordion" data-toggle="collapse" href="#location_data"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Location Data</h4>
          </div>
            <div class="panel-body">
              <div class="panel-collapse collapse" id="location_data">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">Coordinates</div>
                        <div class="panel-body"><table class="table" id="location_data_table"></table></div>
                      </div>
                    </div>
                  </div>
                  <div class="highcharts" id="pvt_chart"></div>
                </div>
              </div>
            </div>
        </div>
        </div>
      </div>
  </div>
</div>

@endsection


@section('Scripts')
@include('js/data_inventory_detailed_field_general')
@endsection