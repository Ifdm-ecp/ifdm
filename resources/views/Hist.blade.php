<!DOCTYPE html>
<html>
  <head>

  <title>Historic Data</title>
  <script src="https://nextgen.pl/_/scroll/lib/jquery.min.js"></script>
  <link rel="stylesheet" href="{!! asset('plugins/bootstrap/css/bootstrap.css') !!}">
  <script src="{!! asset('plugins/bootstrap/js/bootstrap.min.js') !!}"></script>
  <!-- Estilos select -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-latest.js"></script>

  
  <!-- Librerías select Bootstrap   -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">

  <link rel="stylesheet" href="{!! asset('plugins/bootstrap/css/bootstrap.css') !!}">
  <script src="{!! asset('plugins/bootstrap/js/bootstrap.min.js') !!}"></script>
  </head>
  <body>
  <br>
  <br>
  <div class ="row">
  <div class = "container-fluid">
    <div class ="col-sm-2"></div>
      <div class ="col-sm-8">
        <div class ="row" id="alert"></div>
        <div  class="container-fluid" style="border:1px solid #cecece;">
          <div class ="row"  align="center">
            <h1>
              <div id="titulo">
                <b>Historic Data</b>
              </div>
            </h1>
            <h4>
              <div id="subtitulo"></div>
            </h4>
          </div>
          <hr>
          <div class="row">
            <div class= "col-sm-6" align="center">
              <div class ="col-sm-12">
                <div class ="row">Basin</div>
                <div class ="row">
                  <select class="selectpicker show-tick"  data-live-search="true" data-width="100%"  id="Basin" >
                    @foreach ($cuencas as $cuenca)
                        <option value = "{!! $cuenca->id!!}">{!! $cuenca->nombre!!}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class= "col-sm-6" align="center">
              <div class ="col-sm-12">
                <div class ="row">Field</div>
                <div class ="row">
                  <select class="selectpicker show-tick" data-live-search="true" data-width="100%"  id="Field" name="Field" title="Field" multiple>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class= "col-sm-4" align="center">
              <div class ="col-sm-12">
                <div class ="row">Damage Mechanisms</div>
                <div class ="row">
                <select class="selectpicker show-tick" disabled data-live-search="true" data-width="100%"  id="Mecanismos" >
                  <option selected disabled>Damage Mechanisms</option>
                </select>
                </div>
              </div>
            </div>
            <div class= "col-sm-4" align="center">
              <div class ="col-sm-12">
                <div class ="row">Damage Variables</div>
                <div class ="row">
                <select class="selectpicker show-tick" disabled data-live-search="true" data-width="100%"  id="Parameter" >
                  <option selected disabled>Damage Variables</option>
                </select>
                </div>
              </div>
            </div>
            <div class= "col-sm-4" align="center">
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
          <br>
          <hr>
          <div class ="row">
            <div class ="col-sm-12">
              <div id="container"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class ="col-sm-2"></div>
  </div>
  @include('js/historic_js')
  </body>
</html>