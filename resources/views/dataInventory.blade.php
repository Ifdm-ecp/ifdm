@extends('layouts.general_datainventory')

@section('title', 'Data Inventory')

@section('content')
<div class="row">
  <b><center><h1>DATA INVENTORY</h1></center></b><br>

  <p align="justify">In the Data Inventory module, the user may query for real time information stored in the IFDM database. Such information may be retrieved by filtering in field, producing interval, and well scale. In addition, the user may check the detailed information of any of the database elements by clicking in the "Check" link. </p>
  <br>
  <hr>
</div>
<div class="row">
  <div class="nav">
    <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
          <li class="active"><a data-toggle="tab" href="#scenarios_data_inventory_c" id="scenarios_data_inventory">Scenarios Data Inventory</a></li>
          <li><a data-toggle="tab" href="#general_data_inventory_c" id="general_data_inventory">General Data Inventory</a></li>
          <li><a data-toggle="tab" href="#scenarios_by_field_c" id="scenarios_by_field">Data Inventory By Analysis Type</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane" id="general_data_inventory_c">
          <br>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> General Data Inventory</h4>
            </div>
            <div class="panel-body">
              <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                      <h4 align="center"><b>Check data inventory at Field scale filtering by Basin</b></h4>
                      <hr>
                      <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basin_general" >
                        <option selected disabled>Basin</option>
                        @foreach ($cuencas as $cuenca)
                          <option value="{!!$cuenca->id!!}">{!!$cuenca->nombre!!}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-1"></div>
                  <div class="col-md-10 basin_general_data" style="display:none;">
                    <br>
                    <div class="panel panel-default">
                      <div class="panel-heading" align="center"><b>Field Data</b></div>
                      <div class="panel-body">
                        <table class="table" id="field_data_general_inventory" align="center">
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1"></div>
                </div>
                <hr>
                <div id="field_general_data" style="display:none;">
                    <div class="row">
                      <div class="col-md-4">
                        <h4 align="center"><b>Check data inventory at Well and Formation scale filtering by Field</b></h4>
                        <hr>
                        <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="field_general">
                          <option selected disabled>Field</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-1"></div>
                      <div class="col-md-10 field_general_data" style="display:none;">
                      <br>
                      <div class="highchart" id="formation_general_chart"></div>
                      <div class="row">
                        <div class="panel panel-default">
                          <div class="panel-heading" align="center"><b>Formation Data</b></div>
                            <div class="panel-body">
                              <div class="row">
                                <table class="table" id="formation_data_general_inventory">
                                </table>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="highchart" id="well_general_chart"></div>
                      <div class="row">
                        <div class="panel panel-default">
                          <div class="panel-heading" align="center"><b>Well Data</b></div>
                            <div class="panel-body">
                              <div class="row">
                                <div class="nav">
                                  <div class="tabbable">
                                    <ul class="nav nav-tabs" data-tabs="tabs" id="tab_well">
                                        <li class="active"><a data-toggle="tab" href="#well_data_c_general" id="well_data_general">Well Data</a></li>
                                        <li><a data-toggle="tab" href="#production_data_c_general" id="production_data_general">Fluid Characterization Data</a></li>
                                    </ul>
                                    <div class="tab-content">
                                      <div class="tab-pane active" id="well_data_c_general">
                                        <br>
                                        <table class="table" id="well_data_general_inventory"></table>
                                      </div>
                                      <div class="tab-pane" id="production_data_c_general">
                                        <br>
                                        <table class="table" id="well_data_general_inventory2"></table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
                      </div>
                      <div class="col-md-1"></div>
                    </div>
                </div>
                <hr>
                <div id="formation_general_data" style="display:none;">
                    <div class="row">
                      <div class="col-md-4">
                        <h4 align="center"><b>Check data inventory at Producing Interval scale filtering by Formation</b></h4>
                        <hr>
                        <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="formation_general">
                          <option selected disabled>Formation</option>
                        </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-1"></div>
                      <div class="col-md-10 formation_general_data" style="display:none;">
                        <div class="highchart" id="producing_interval_general_chart"></div>
                        <div class="row">
                          <div class="panel panel-default">
                            <div class="panel-heading" align="center"><b>Producing Interval Data</b></div>
                            <div class="panel-body">
                              <table class="table" id="producing_interval_data_general_inventory">
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-1"></div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane active" id="scenarios_data_inventory_c">
          <br>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4><a data-parent="#accordion" data-toggle="collapse" href="#scenario_data"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Scenarios Data Inventory</h4>
              </div>
              <div class="panel-body">
                  <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                          <h4 align="center"><b>Check data inventory at Field scale filtering by Basin</b></h4>
                          <hr>
                          <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basin" >
                            <option selected disabled>Basin</option>
                            @foreach ($cuencas as $cuenca)
                              <option value="{!!$cuenca->id!!}">{!!$cuenca->nombre!!}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-1"></div>
                      <div class="col-md-10 basin_data" style="display:none;">
                        <br>
                        <div class="panel panel-default">
                          <div class="panel-heading" align="center"><b>Percentage Of Field Data Needed By Analysis Type</b></div>
                          <div class="panel-body">
                            <table class="table" id="field_data_inventory" align="center">
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-1"></div>
                    </div>
                    <hr>
                    <div id="field_data" style="display:none;">
                        <div class="row">
                          <div class="col-md-4">
                            <h4 align="center"><b>Check data inventory at Well and Formation scale filtering by Field</b></h4>
                            <hr>
                            <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="field">
                              <option selected disabled>Field</option>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-1"></div>
                          <div class="col-md-10 field_data" style="display:none;">
                          <br>
                          <div class="highchart" id="formation_chart"></div>
                          <div class="row">
                            <div class="panel panel-default">
                              <div class="panel-heading" align="center"><b>Percentage Of Formation Data Needed By Analysis Type</b></div>
                                <div class="panel-body">
                                  <div class="row">
                                    <table class="table" id="formation_data_inventory">
                                    </table>
                                  </div>
                                </div>
                            </div>
                          </div>
                          <div class="highchart" id="well_chart"></div>
                          <div class="row">
                            <div class="panel panel-default">
                              <div class="panel-heading" align="center"><b>Percentage Of Well Data Needed By Analysis Type</b></div>
                                <div class="panel-body">
                                  <div class="row">
                                    <table class="table" id="well_data_inventory">
                                    </table>
                                  </div>
                                </div>
                            </div>
                          </div>
                          </div>
                          <div class="col-md-1"></div>
                        </div>
                    </div>
                    <hr>
                    <div id="formation_data" style="display:none;">
                        <div class="row">
                          <div class="col-md-4">
                            <h4 align="center"><b>Check data inventory at Producing Interval scale filtering by Formation</b></h4>
                            <hr>
                            <select class="selectpicker show-tick" data-live-search="true" data-style="btn-primary" data-width="100%" id="formation">
                              <option selected disabled>Formation</option>
                            </select>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-md-1"></div>
                          <div class="col-md-10 formation_data" style="display:none;">
                            <div class="highchart" id="producing_interval_chart"></div>
                            <div class="row">
                              <div class="panel panel-default">
                                <div class="panel-heading" align="center"><b>Percentage Of Producing Interval Data Needed By Analysis Type</b></div>
                                <div class="panel-body">
                                  <table class="table" id="producing_interval_data_inventory">
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-1"></div>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>
        <div class="tab-pane" id="scenarios_by_field_c">
        <br>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4><a data-parent="#accordion" data-toggle="collapse" href="#scenarios_by"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Scenarios By Analysis Type </h4>
            </div>
            <div class="panel-body">
              <div class="col-md-12">
                <div class="row">
                  <h4 align="center"><b>Check Scenarios Inventory At Field Scale By Analysis Type</b></h4>
                  <hr>
                  <div class="col-md-2"></div>
                  <div class="col-md-4">
                    <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basin_scenarios" >
                      <option selected disabled>Basin</option>
                      @foreach ($cuencas as $cuenca)
                        <option value="{!!$cuenca->id!!}">{!!$cuenca->nombre!!}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="field_scenarios">
                      <option selected disabled>Field</option>
                    </select>
                  </div>
                </div>
                <div id="field_data_analysis" style="display:none;">
                <br>
                <div class="row">
                  <div class="col-md-3"><div class="highcharts" id="chart_ipr"></div></div>
                  <div class="col-md-3"><div class="highcharts" id="chart_mp"></div></div>
                  <div class="col-md-3"><div class="highcharts" id="chart_dis"></div></div>
                  <div class="col-md-3"><div class="highcharts" id="chart_drilling"></div></div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8"><h4 align="center"><b>Detailed Data By Well</b></h4></div>
                </div>
                <br>
                <br>
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8"><div class="table" id="scenarios_by_field_table"></div></div>
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
@include('js/dataInventory_js')
@endsection