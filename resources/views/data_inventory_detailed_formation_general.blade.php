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
        <b><center><h1>DETAILED DATA INVENTORY - FORMATION</h1></center></b><br>
        <p align="justify">In the Detailed Data Inventory module, the user can check the existent and missing information for each formation in database.</p>
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
          <div class="col-md-4">
            <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="formation">
            </select>
          </div>
        </div>
        <br>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4><a data-parent="#accordion" data-toggle="collapse" href="#multiparametric_data"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> General Data</h4>
          </div>
          <div class="panel-body">
            <div class="panel-collapse collapse in" id="multiparametric_data">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <div class="panel panel-default">
                    <div class="panel-heading">Reservoir Data</div>
                    <div class="panel-body"><table class="table" id="m_rock_properties_table"></table></div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="panel panel-default">
                    <div class="panel-heading">Water Oil Kr</div>
                    <div class="panel-body"><table class="table" id="water_oil_table"></table></div>
                  </div>
                </div>
                <div class="col-md-6"><div class="highcharts" id="water_oil_chart"></div></div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="panel panel-default">
                    <div class="panel-heading">Gas Liquid Kr</div>
                    <div class="panel-body"><table class="table" id="gas_liquid_table"></table></div>
                  </div>
                </div>
                <div class="col-md-6"><div class="highcharts" id="gas_liquid_chart"></div></div>
              </div>
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
@include('js/data_inventory_detailed_formation_general')
@endsection