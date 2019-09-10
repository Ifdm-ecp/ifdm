<!DOCTYPE html>
<html>
<head>

  <title>Frequency Distribution</title>
  <script src="http://nextgen.pl/_/scroll/lib/jquery.min.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" href="{!! asset('css/map.css') !!}">

  <script type="text/javascript" src="https://www.google.com/jsapi"></script>

  <link rel="stylesheet" href="{!! asset('plugins/bootstrap/css/bootstrap.css') !!}">
  <script src="{!! asset('plugins/bootstrap/js/bootstrap.min.js') !!}"></script>
  <!-- Estilos select -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-latest.js"></script>

  
  <!-- Librerías select Bootstrap   -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">

  <link rel="stylesheet" href="{!! asset('plugins/bootstrap/css/bootstrap.css') !!}">
  <script src="{!! asset('plugins/bootstrap/js/bootstrap.min.js') !!}"></script>
</head>
<body>
  <div class="container-fluid">
  <div class ="col-sm-1"></div>
  <div class ="col-sm-10">
    <div class ="row">
      <div class ="row" id="alert"></div>
      <div class ="row"  align="center"><h1><div id="titulo"></div></h1></div>
        <hr>
        <div class="row">
          <div class= "col-sm-3 " align="center">
          <div class ="col-sm-12">
            <div class ="row">Basin</div>
            <div class ="row">
              <select class="selectpicker show-tick"  data-live-search="true" data-width="100%"  id="Basin" >
              <option selected disabled>Basin</option>
                  @foreach ($cuencas as $cuenca)
                      <option value = "{!! $cuenca->id!!}">{!! $cuenca->nombre!!}</option>
                  @endforeach
              </select>
            </div>
          </div>
          </div>
          <div class= "col-sm-3 " align="center">
          <div class ="col-sm-12">
            <div class ="row">Field</div>
            <div class ="row">
              <select class="selectpicker show-tick" data-header="One or Many" data-live-search="true" data-width="100%"  id="Field" name="Field" title="Field" multiple>
              </select>
            </div>
          </div>
          </div>
          <div class= "col-sm-3 " align="center">
          <div class ="col-sm-12">
            <div class ="row">Sub-Parameter</div>
              <div class ="row">
                <select class="selectpicker show-tick" disabled data-live-search="true" data-width="100%"  id="Parameter" >
                  <option selected disabled>Sub-Prameter</option>
                </select>
              </div>
            </div>
          </div>
          <div class= "col-sm-3 " align="center">
          <div class ="col-sm-12">
            <div class ="row">Well</div>
            <div class ="row">
                <select class="selectpicker show-tick" disabled data-live-search="true" data-width="100%"  id="Well" >
                  <option selected disabled>Well</option>
                </select>
            </div>
          </div>
          </div>
        </div>
        <hr>
        <div class ="row">
            <div class="col-sm-6 " id="chart" >
              <div class ="col-sm-12">
                <div id= "chart2_div"></div>
              </div>
            </div>
            <div class="col-sm-6 " id="chart2" >
              <div class ="col-sm-12">
                <div id= "chart_div"></div>
              </div>
            </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-12 " id="Info">
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <h3 class="panel-title" id="GI">General information</h3>
                  </div>
                  <div class="panel-body">
                    <div class="row">
                      <div class= "col-sm-3 " >
                        <div class ="col-sm-12">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                                <h3 class="panel-title" align="center">Maximum value</h3>
                              </div>
                               <div class="panel-body" id="MaV" align="center"></div>
                            </div>
                        </div>
                      </div>
                      <div class= "col-sm-3 " >
                        <div class ="col-sm-12">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                                <h3 class="panel-title" align="center">Minimum value</h3>
                              </div>
                              <div class="panel-body" id="MiV" align="center"></div>
                            </div>
                        </div>
                      </div>
                      <div class= "col-sm-3 " >
                        <div class ="col-sm-12">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                                <h3 class="panel-title" align="center">Number of data</h3>
                              </div>
                              <div class="panel-body" id="len" align="center"></div>
                            </div>
                        </div>
                      </div>
                      <div class= "col-sm-3" >
                        <div class ="col-sm-12">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <h3 class="panel-title" align="center">Mean</h3>
                            </div>
                            <div class="panel-body" id="AVG" align="center"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class= "col-sm-3 " >
                            <div class = "col-sm-12">
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h3 class="panel-title" align="center">Standard deviation</h3>
                                </div>
                                <div class="panel-body" id="SD" align="center"></div>
                              </div>
                            </div>
                      </div>
                      <div class= "col-sm-3 " >
                            <div class ="col-sm-12">
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h3 class="panel-title" align="center">P10</h3>
                                </div>
                                <div class="panel-body" id="p10" align="center"></div>
                              </div>
                            </div>
                      </div>
                      <div class= " col-sm-3 " >
                            <div class ="col-sm-12">
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h3 class="panel-title" align="center">P50</h3>
                                </div>
                                <div class="panel-body" id="p50" align="center"></div>
                              </div>
                            </div>
                      </div>
                      <div class= " col-sm-3 " >
                            <div class ="col-sm-12">
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                  <h3 class="panel-title" align="center">P90</h3>
                                </div>
                                <div class="panel-body" id="p90" align="center"></div>
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
  <div class ="col-sm-1"></div>
  </div>
  </div>
  @include('js/freq_js')

</body>
</html>