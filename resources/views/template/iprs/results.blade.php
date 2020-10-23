

@section('title', 'IFDM Project')

@section('content')

<?php if(!isset($_SESSION)) { session_start(); } ?>

@if (session()->has('mensaje'))

<div id="myModal" class="modal fade" hidden>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Success</h4>
      </div>
      <div class="modal-body">
        <p class="text-danger">
          <small>
            <p>{{ session()->get('mensaje') }}</p>
          </small>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

@endif
@if (count($errors) > 0)

<div id="myModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Error</h4>
      </div>
      <div class="modal-body">
        <p class="text-danger">
          <small>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </small>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

@endif
<div onload="multiparametrico();">
  <div id="sticky-anchor"  class="col-md-6"></div>
  <div id="sticky" tabindex='1' ><center>Scenario: {!! $scenary->nombre !!} </br> Basin: {!! $basin->nombre !!} - Field: {!! $field->nombre !!} - Well: {!!  $well->nombre !!} </br> User: {!! $user->fullName !!} </center></div>
  <br>

  @if(is_null($IPR->status_wr) || !$IPR->status_wr)
  <div class="panel panel-default" >
    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Results</h4></div>
    <div class="panel-body">
      <div id="Prod" class="panel-collapse collapse in">
        <div id="grafica">
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <center> 
                {!! Form::label('valor', 'Total Skin: ') !!}
                <!--{!! Form::label('valor', ($skin == 0 ? "0" : round($skin,2))) !!}-->  
                <b>{!! round($skin,2) !!}</b>
              </center>                         
            </div>
          </div>
        </div>
        <div class="panel panel-default" >
          <div class="panel-heading"><h4> <a data-parent="#accordion" data-toggle="collapse" href="#operative_point"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Operative Point</h4></div>
          <div class="panel-body">
            <div id="operative_point" class="panel-collapse collapse in">
              <div class="row">
                <div class="col-md-6">
                  <h3>Outflow Curve</h3>
                  <div id="tablaprueba"></div>
                </div>
                <br>
                <br>
                <div class="col-md-6">
                  <div class="col-md-6">
                    <div class="input-group">
                      <label for="current_operative_point">Current Operative Point</label>
                      <input type="text" class="form-control" disabled  name="current_operative_point" id="current_operative_point">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <label for="ideal_operative_point">Ideal Operative Point</label>
                      <input type="text" class="form-control" disabled  name="ideal_operative_point" id="ideal_operative_point">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="col-md-8">
                      <input type="hidden" name="rates_inflow_ideal" id="rates_inflow_ideal" value="{{ isset($categorias_skin_ideal) ? $categorias_skin_ideal : '{}' }}">
                      <input type="hidden" name="pressures_inflow_ideal" id="pressures_inflow_ideal" value="{{ isset($eje_y_skin_ideal) ? $eje_y_skin_ideal : '{}' }}">

                      <input type="hidden" name="rates_inflow_current" id="rates_inflow_current" value="{{ isset($categorias) ? $categorias : '{}' }}">
                      <input type="hidden" name="pressures_inflow_current" id="pressures_inflow_current" value="{{ isset($eje_y) ? $eje_y : '{}' }}">
                      <br>
                      <center><button class="btn btn-primary btn-md pull-right" onclick="calcularOutflow();">Calculate</button></center>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-default" >
          <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod_s"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Sensitivities </h4></div>
          <div class="panel-body">
            <div id="Prod_s" class="panel-collapse collapse in">
              <div>
                <center>
                  <button type="button" class="btn btn-primary" id="btn_active_skin">Skin</button>
                  <button type="button" href="{{ url('IPR/result/'.$IPR->id_escenario.'/sensibilities') }}" class="btn btn-primary" id="btn_active_others_var">Other Variables</button>
                </center>
              </div>
              <div id="skin_sens" style="display: none;">
                <hr>
                <div class="row">
                  <div class="col-md-1">
                    <button type="button" class="btn btn-default" onclick="addSensibility();"><i class="fas fa-plus"></i></button>
                  </div>
                  <div class="col-md-11">
                    <div id="Sensitivities_list">
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-danger pull-left" onclick="limpiar();">Clear All</button>
                <button type="button" class="btn btn-primary pull-right" onclick="enviar();">Apply</button>
              </div>
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
      <a href="{!! route('ipr.edit',$scenary->id) !!}" class="btn btn-default" role="button">Edit</a>
    </div>
  </div>

  @endsection
  @section('Scripts')
  @if(is_null($IPR->status_wr) || !$IPR->status_wr)
  @include('js/template/iprs_results')
  @endif
  @endsection
  @include('css/iprresults_css')