<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
  <title>Georreference</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Librerías Mapa-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" href="{!! asset('css/map.css') !!}">
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV-sQFuLosUpjbqIlxTHAQ05vcsxF8s0U&libraries=visualization">
</script>

  <!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=visualization&sensor=true_or_false"></script>-->
  <!-- Gráficos -->
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
  <script src="../../js/index.js"></script>
  <!-- Otros -->
  <link rel="stylesheet" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="{!! asset('assets/css/estilos.css') !!}">
  {!!Html::style( asset('css/fileinput.css'))!!}
  <link href="../css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
  <script src="{!! asset('js/fileinput.min.js') !!}" type="text/javascript"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
  <script src="../../js/index.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
  <script src="{!! asset('assets/arbol.js') !!}"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">

  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script>
    $(document).ready(function () 
    {
      var count=0;
      $.get("{{url('request')}}",
        {},
        function(data){
          $.each(data, function(index, value){
            count++;
          });
        $('#request').html(count);
      });
      var url = window.location;
      $('ul.nav a[href="' + url + '"]').parent().addClass('active');
      $('ul.nav a').filter(function () {
      return this.href == url;
      }).parent().addClass('active').parent().parent().addClass('active');
    });
  </script>
  </head>
  <body>
    <nav class="navbar navbar-inverse">
    <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="icon-menu">
                <a href="/homeC">
                    <img src="/images/User-white.png" class="icons" alt="User" width="20" height="20">
                    Home<span class="sr-only">(current)</span>
                </a>
            </li>
            @if(\Auth::User()->office == 2)
                <li>{!! link_to('requestC', $title = 'Request'); !!}</li>
            @endif
            @if(\Auth::User()->office != 2)
                <li class="icon-menu">
                    <a href="/database">
                        <img src="/images/Database-icon.png" class="icons" alt="database" width="20" height="20">
                        Database
                    </a>
                </li>
            @endif
            <li class="icon-menu">
                <a href="/share_scenario">
                    <img src="/images/Project-management.png" class="icons" alt="project" width="15" height="20">
                    Project Management
                </a>
            </li>
            <li class="icon-menu">
                <a href="/Geor">
                    <img src="/images/Georeference.png" class="icons" alt="geo" width="20" height="20">
                    Georeference
                </a>
            </li>
            {{-- <li class="icon-menu"><img src="/images/Scenario-report.png" class="icons" alt="scenary">{!! link_to('scenarioR', $title = 'Scenario Report'); !!}</li>
            <li class="icon-menu"><img src="/images/Data-inventory.png" class="icons" alt="data">{!! link_to('dataInventory', $title = 'Data Inventory'); !!}</li> --}}
            <li class="dropdown icon-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="/images/Help.png" class="icons" alt="help" width="20" height="20">
                    Help<span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>{!! link_to_route('manuals', $title = 'Manuals'); !!}</li>
                    <li>{!! link_to_route('download', $title = 'Other Downloads'); !!}</li>
                    <li><a href="{{route('manual-public.index')}}">Interactive Guide</a></li>
                    <li>{!! link_to_route('about', $title = 'About'); !!}</li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            @if(\Auth::User()->office == 0)
            <li role="presentation"><a href="{!! url('requestA') !!}">Request <span class="badge" id="requestV">0</span></a></li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> Users<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>{!! link_to('registerC', $title = 'Sign Up'); !!}</li>
                    <li>{!! link_to('UserC', $title = 'User Management'); !!}</li>
                    <li>{!! link_to('UserStatistics', $title = 'User Statistics'); !!}</li>
                </ul> 
            </li>
            @endif
            <li>{!! link_to("logout", $title = 'Log Out') !!}</li>
        </ul>
    </div>
    </div>
    </nav>

    <div class="container-fluid">
      <div class ="row">
        <div class= "col-sm-7 sidebar-offcanvas" >
          <div id ="alert" class = "col-sm-12"></div>
          <div id="map" class = "col-sm-12">
            <h3 class="map-message text-center">Fill the form next to this section to get the georeference information</h3>
          </div>
          <div class="row" style="height: 1%; float:center;"></div>
          <div class="row text-center" id="scale"> <img src="{!! asset('images/barra.png') !!}" width= 90% height= 5% HSPACE="30"/></div>
          <div class="row" id="scale_values"> 
            <div class= "col-sm-3 sidebar-offcanvas" >
              <p align="center" id="b1"></p>
            </div>
            <div class= "col-sm-3 sidebar-offcanvas" >
              <p align="center" id="b2"></p>
            </div>
            <div class= "col-sm-3 sidebar-offcanvas" >
              <p align="center" id="b3"></p>
            </div>
            <div class= "col-sm-3 sidebar-offcanvas" >
              <p align="center" id="b4"></p>
            </div>
          </div>
          <div class="row" id="empty_scale_values" style="display:none">
            <h3 class="text-center text-danger">The filtered data doesn't have enough information</h3>
          </div>
          <br>
          <div class="row" id="alert_2"></div>
        </div>
        <div class="col-sm-5 sidebar-offcanvas" id="sidebar2">
          <div class ="nav">
            <div class="tabbable">
              <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
                  <li class="active"><a data-toggle="tab" href="#damage_data_c" id="damage_data">Damage Variables Data</a></li>
                  <a href="{{ route('histo') }}" id="historical_link" class="btn btn-primary" target="_blank">Historic Data</a>
                  {{-- <li><a data-toggle="tab" href="#general_data_c" id="general_data">General Data</a></li> --}}
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="damage_data_c">
                  <br>
                  <div class ="row"> 
                    <div class="col-sm-6 sidebar-offcanvas" id="Filter1">
                      <div class = "row">
                        <div class ="col-sm-12">
                          <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="Basin" >
                            <option selected disabled>Basin</option>
                              @foreach ($cuencas as $cuenca)
                                <option value = "{!! $cuenca->id !!}">{!! $cuenca->nombre!!}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row" align ="center">Basin</div>
                    </div>
                    <div class="col-sm-6 sidebar-offcanvas" id="Filter2">
                    <div class="row">
                      <div class = "col-sm-12">
                        <select class="selectpicker show-tick" data-live-search="true" data-width="100%" data-style="btn-primary" id="Field" name="Field" title="Field" multiple></select>
                      </div>
                    </div>
                    <div class = "row" align="center">
                      Field
                    </div>
                    </div>
                    <!--
                    Eliminar comentario para mostrar el filtro Formation
                    <div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="Filter3">
                      @yield('Filter3')
                    </div>
                    -->
                  </div>
                  <!--
                   Descomentar para mostrar la etiqueta del filtro Formation
                   <div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="FC" align="center">
                     Formation
                   </div>
                   -->
                  <div class="row">
                    <hr>
                    <div class="col-sm-6 sidebar-offcanvas" id="Filter6">
                      <div class = "row">
                        <div class = "col-sm-12">
                          <select class="selectpicker show-tick" disabled data-live-search="true" data-width="100%" data-style="btn-primary" id="Mecanismos" >
                            <option selected disabled>Damage Mechanisms</option>
                          </select>
                        </div>
                      </div>
                      <div class = "row" align = "center">Damage Mechanisms</div>
                    </div>
                    <div class="col-sm-6 sidebar-offcanvas" id="Filter4">
                      <div class = "row">
                        <div class ="col-sm-12">
                         <select class="selectpicker show-tick" disabled data-live-search="true" data-width="100%" data-style="btn-primary" id="Parameter" >
                           <option selected disabled>Damage Variables</option>
                         </select> 
                        </div>
                      </div>
                      <div class = "row" align="center">Damage Variables</div>
                    </div>
                    <div style="display:none" class="col-sm-4 sidebar-offcanvas" id="Filter5">
                      <div class = "row">
                        <div class ="col-sm-12">
                          <select class="selectpicker show-tick" disabled data-live-search="true" data-width="100%" data-style="btn-success" id="Filtrox" >
                            <option selected disabled>Damage Configuration</option>
                          </select>
                        </div>
                      </div>
                      <div class ="row" align = "center">Damage Configuration</div>
                    </div>
                  </div>
                  <hr>
                  <div class="col-sm-6 sidebar-offcanvas">
                    <div class="row" align="center">
                      <div class = "col-sm-12">
                        <span class="label label-primary" align ="center">Choose A Data Type </span>
                        <p></p>
                      </div>
                    </div>
                    <div class = "row top-buffer" align ="center">
                      <div class="col-sm-3 sidebar-offcanvas" id="lastp" align = "center">
                        <div class = "col-sm-12">
                          <button type="button" class="btn btn-primary btn-xs" id="lastb" disabled>Last data</button>
                        </div>
                      </div>
                      <div class="col-sm-3 sidebar-offcanvas" id="avgp" align = "center">
                        <div class = "col-sm-12">
                          <button type="button" class="btn btn-primary btn-xs" id="avgb" disabled>Average</button>
                        </div>
                      </div>
                      <div class="col-sm-3 sidebar-offcanvas" id="minp" align = "center">   
                        <div class ="col-sm-12">
                          <button type="button" class="btn btn-primary btn-xs" id="minb" disabled>Minimum</button>
                        </div>  
                      </div>
                      <div class="col-sm-3 sidebar-offcanvas" id="maxp" align = "center">
                        <div class="col-sm-12">
                          <button type="button" class="btn btn-primary btn-xs" id="maxb" disabled>Maximum</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 sidebar-offcanvas">
                    <div class="row" align="center">
                      <div class ="col-sm-12">
                        <span class="label label-primary">Choose A View Type </span>
                      </div>
                    </div>
                    <div class ="row"> 
                      <div class="col-sm-6 sidebar-offcanvas" id="avgp" align="center">
                      <div class ="col-sm-12">
                        <div class="radio">
                          <label><input checked type="radio" name="optradio" id="wvr">Well view</label>
                        </div>
                      </div>
                      </div>
                      <div class="col-sm-6 sidebar-offcanvas" id="minp" align="center">
                        <div class ="col-sm-12">
                          <div class="radio">
                            <label><input type="radio" name="optradio" id="fvr">Field view</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class ="row"> 
                    <div class="col-sm-6 sidebar-offcanvas"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 sidebar-offcanvas" id="chart">
                      <div id="chart_div"> </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12 sidebar-offcanvas" id="Info" align="center">
                      <div class="panel panel-info">
                        <div class="panel-heading">
                          <h3 class="panel-title" id="GI">General information</h3>
                        </div>
                        <div class="panel-body">
                          <div class="row">
                            <div class= "col-sm-4 sidebar-offcanvas" >
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h3 class="panel-title">Wells in map</h3>
                                </div>
                                <div class="panel-body" id="WIF"></div>
                              </div>
                            </div>
                            <div class= "col-sm-4 sidebar-offcanvas" >
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                   <h3 class="panel-title">Wells with data</h3>
                                </div>
                                <div class="panel-body" id="WWM"></div>
                              </div>
                            </div>
                            <div class= "col-sm-4 sidebar-offcanvas" >
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h3 class="panel-title">Maximum value</h3>
                                </div>
                                <div class="panel-body" id="MaV"></div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class= "col-sm-4 sidebar-offcanvas" >
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Minimum value</h3>
                                </div>
                                <div class="panel-body" id="MiV"></div>
                              </div>
                            </div>
                            <div class= "col-sm-4 sidebar-offcanvas" >
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                   <h3 class="panel-title">Media</h3>
                                </div>
                                  <div class="panel-body" id="AVG"></div>
                              </div>
                            </div>
                            <div class= "col-sm-4 sidebar-offcanvas" >
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h3 class="panel-title">Standard deviation</h3>
                                </div>
                                <div class="panel-body" id="SD"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="general_data_c">
                  <div class="panel panel-defualt">
                    <div class="panel-heading">
                       <h4 align="center"><a data-parent="#accordion" data-toggle="collapse" href="#field_scale"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a><b>Field Scale</b></h4>
                    </div>
                    <div class="panel-body">
                      <div id="field_scale" class="panel-collapse collapse">
                        <div class = "row">
                          <div class ="col-md-6">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basins_field_scale" >
                              <option selected disabled>Basin</option>
                                @foreach ($cuencas as $cuenca)
                                    <option value = "{!! $cuenca->id!!}">{!! $cuenca->nombre!!}</option>
                                @endforeach
                            </select>
                          </div>
                          <div class ="col-md-6">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-success" id="field_options" >
                              <option selected disabled>Field Options</option>
                              <option value = "1">PVT Data</option>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="highcharts" id="chart_field_scale"></div>
                        </div>
                        <br>
                        <div class="row" id="field_scale_general_data" style="display:none;">
                          <div class="col-sm-12 sidebar-offcanvas" id="Info" align="center">
                            <div class="panel panel-info">
                              <div class="panel-heading">
                                <h3 class="panel-title" id="GI_field_scale">General information</h3>
                              </div>
                              <div class="panel-body">
                                <div class="row">
                                  <div class= "col-sm-6 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                        <h3 class="panel-title">Fields in map</h3>
                                      </div>
                                      <div class="panel-body" id="WIF_field_scale"></div>
                                    </div>
                                  </div>
                                  <div class= "col-sm-6 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                         <h3 class="panel-title">Fields with data</h3>
                                      </div>
                                      <div class="panel-body" id="WWM_field_scale"></div>
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
                  <div class="panel panel-defualt" style="display:none;">
                    <div class="panel-heading">
                       <h4 align="center"><a data-parent="#accordion" data-toggle="collapse" href="#formation_scale"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a><b>Producing Interval Scale</b></h4>
                    </div>
                    <div class="panel-body">
                      <div id="formation_scale" class="panel-collapse collapse">
                        <div class = "row">
                          <div class ="col-md-4">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basins_formation_scale" >
                              <option selected disabled>Basin</option>
                                @foreach ($cuencas as $cuenca)
                                    <option value = "{!! $cuenca->id!!}">{!! $cuenca->nombre!!}</option>
                                @endforeach
                            </select>
                          </div>
                          <div class ="col-md-4">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="fields_formations_scale" >
                              <option selected disabled>Field</option>
                            </select>
                          </div>
                          <div class ="col-md-4">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-success" id="formation_options" >
                              <option selected disabled>Formation Options</option>
                              <option>Top</option>
                              <option>Netpay</option>
                              <option>Porosity</option>
                              <option>Permeability</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-defualt">
                    <div class="panel-heading">
                       <h4 align="center"><a data-parent="#accordion" data-toggle="collapse" href="#well_scale"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a><b>Well Scale</b></h4>
                    </div>
                    <div class="panel-body">
                      <div id="well_scale" class="panel-collapse collapse">
                        <div class = "row">
                          <div class ="col-md-4">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="basins_well_scale" >
                              <option selected disabled>Basin</option>
                                @foreach ($cuencas as $cuenca)
                                    <option value = "{!! $cuenca->id!!}">{!! $cuenca->nombre!!}</option>
                                @endforeach
                            </select>
                          </div>
                          <div class ="col-md-4">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-primary" id="fields_well_scale" multiple>
                              <option selected disabled>Field</option>
                            </select>
                          </div>
                          <div class ="col-md-4">
                            <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-success" id="well_options" >
                              <option selected disabled>Well Options</option>
                              <option value="radius">Radius</option>
                              <option value="bhp">BHP</option>
                              <option value="drainage_radius">Drainage Radius</option>
                              <option value="oil_rate">Oil Rate</option>
                              <option value="gas_rate">Gas Rate</option>
                              <option value="tdv">TVD</option>
                              <option value="api">API</option>
                              <option value="wor">WOR</option>
                              <option value="saturation_pressure">Saturation Pressure</option>
                              <option value="gor">GOR</option>
                              <option value="cgr">CGR</option>
                              <option value="specific_gas">Specific Gas Gravity</option>
                              <option value="oil_viscosity">Oil Viscosity</option>
                              <option value="gas_viscosity">Gas Viscosity</option>
                              <option value="water_viscosity">Water Viscosity</option>
                              <option value="fvfo">FVFO</option>
                              <option value="fvfg">FVFG</option>
                              <option value="plt">PLT Data</option>
                              <option value="production_data">Production Data</option>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="highcharts" id="chart_well_scale"></div>
                        </div>
                        <br>
                        <div class="row" id="well_scale_general_data" style="display:none;">
                          <div class="col-sm-12 sidebar-offcanvas" id="Info" align="center">
                            <div class="panel panel-info">
                              <div class="panel-heading">
                                <h3 class="panel-title" id="GI_well_scale">General information</h3>
                              </div>
                              <div class="panel-body">
                                <div class="row">
                                  <div class= "col-sm-4 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                        <h3 class="panel-title">Wells in map</h3>
                                      </div>
                                      <div class="panel-body" id="WIF_well_scale"></div>
                                    </div>
                                  </div>
                                  <div class= "col-sm-4 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                         <h3 class="panel-title">Wells with data</h3>
                                      </div>
                                      <div class="panel-body" id="WWM_well_scale"></div>
                                    </div>
                                  </div>
                                  <div class= "col-sm-4 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                        <h3 class="panel-title">Maximum value</h3>
                                      </div>
                                      <div class="panel-body" id="MaV_well_scale"></div>
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class= "col-sm-4 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                          <h3 class="panel-title">Minimum value</h3>
                                      </div>
                                      <div class="panel-body" id="MiV_well_scale"></div>
                                    </div>
                                  </div>
                                  <div class= "col-sm-4 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                         <h3 class="panel-title">Media</h3>
                                      </div>
                                        <div class="panel-body" id="AVG_well_scale"></div>
                                    </div>
                                  </div>
                                  <div class= "col-sm-4 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                        <h3 class="panel-title">Standard deviation</h3>
                                      </div>
                                      <div class="panel-body" id="SD_well_scale"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row" id="well_bool_scale_general_data" style="display:none;">
                          <div class="col-sm-12 sidebar-offcanvas" id="Info" align="center">
                            <div class="panel panel-info">
                              <div class="panel-heading">
                                <h3 class="panel-title" id="GI_well_bool_scale">General information</h3>
                              </div>
                              <div class="panel-body">
                                <div class="row">
                                  <div class= "col-sm-6 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                        <h3 class="panel-title">Wells in map</h3>
                                      </div>
                                      <div class="panel-body" id="WIF_well_bool_scale"></div>
                                    </div>
                                  </div>
                                  <div class= "col-sm-6 sidebar-offcanvas" >
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                         <h3 class="panel-title">Wells with data</h3>
                                      </div>
                                      <div class="panel-body" id="WWM_well_bool_scale"></div>
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
        </div>
      </div>
    </div>

    @include('js/geor_js')
  
    <footer>  
      <hr>
      <center>
        <form class="form-inline" role="form">
            <a href="https://unal.edu.co/"><img src="{!! asset('images/UN.png') !!}" width="125" height="80" HSPACE="30"/></a>
            <a href="https://www.equion-energia.com/Paginas/default.aspx"><img src="{!! asset('images/EQUION.jpg') !!}" width="120" height="80" HSPACE="30"/></a>
            <a href="https://www.ecopetrol.com.co/wps/portal/web_es"><img src="{!! asset('images/ECOPETROL.jpg') !!}" width="120" height="100" HSPACE="30"/></a>
            <a href="https://www.hocol.com/"><img src="{!! asset('images/HOCOL-nueva.png') !!}" width="120" height="80" HSPACE="30"/></a>
        </form>
        <form action="">
            <center><img src="{!! asset('images/IFDM.png') !!}" width="250" height="100" HSPACE="30"/></center>
        </form>
      </center></br>
    </footer>
    <style>
      .red {
        color: red;
      }
      .icon-menu>.icons{
        width: 20px;
        height: auto;
        padding-top: 15px;
        padding-left: 5px;
      }
      .nav>li{
        display: -webkit-box;
      }
      #myTab {
        position: relative;
      }
      #myTab #historical_link {
        position: absolute;
        right: 0;
      }
    </style>
  </body>
</html>