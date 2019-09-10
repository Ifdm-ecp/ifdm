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
        <b><center><h1>DETAILED DATA INVENTORY - PRODUCING INTERVAL</h1></center></b><br>
        <p align="justify">In the Detailed Data Inventory module, the user can check the existent and missing information for each producing interval in database</p>
        <br>
        <div class="row">
          <div class="col-md-3">
            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basin" >
              <option selected disabled>Basin</option>
              @foreach ($cuencas as $cuenca)
                <option value="{!!$cuenca->id!!}">{!!$cuenca->nombre!!}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="field">
              <option selected disabled>Field</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="formation">
              <option selected disabled>Formation</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="interval">
              <option selected disabled>Producing Interval</option>
            </select>
          </div>
        </div>
        <br>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4><a data-parent="#accordion" data-toggle="collapse" href="#oil_ipr_data"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Detailed Data</h4>
          </div>
            <div class="panel-body">
              <div class="panel-collapse collapse in" id="drilling_data">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="panel panel-default">
                        <div class="panel-heading">General Data</div>
                        <div class="panel-body"><table class="table" id="d_general_data_table"></table></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="panel-collapse collapse in" id="oil_ipr_data">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">Rock Properties - Gas-Oil Kr</div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-6">
                              <table class="table" id="gas_oil_table"></table>
                            </div>
                            <div class="col-md-6">
                              <div id="gas_oil_chart"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">Rock Properties - Water-Oil Kr</div>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-md-6">
                              <table class="table" id="water_oil_table"></table>
                            </div>
                            <div class="col-md-6">
                              <div id="water_oil_chart"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
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
@include('js/data_inventory_detailed_interval_general')
@endsection