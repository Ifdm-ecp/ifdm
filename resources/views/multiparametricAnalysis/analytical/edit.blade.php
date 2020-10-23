@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
<?php  
  if(!isset($_SESSION)) {
    session_start();
  }
?>

<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky" style="position: relative;">
   <center><b>Scenario: </b>{!! $analytical->escenario->nombre !!} </br> Basin: {!! $analytical->escenario->cuenca->nombre !!} - Field: {!! $analytical->escenario->campo->nombre !!} - Producing interval: {!! $analytical->escenario->formacionxpozo->nombre !!} - Well: {!!  $analytical->escenario->pozo->nombre !!} - User: {!! $analytical->escenario->user->name !!}</center>
</div>

</br>
@include('layouts/modal_error')
@include('layouts/general_advisor')
  <div class="nav">
    <div class="tab">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#FI" id="FI_C" onclick="switchTab()">Fluid Information</a></li>
        <li><a data-toggle="tab" href="#RP" id="RP_C" onclick="switchTab()">Rock Properties</a></li>
        <li><a data-toggle="tab" href="#PD" id="PD_C" onclick="switchTab()">Production Data</a></li>
        <li><a data-toggle="tab" href="#MA" id="MA_C" onclick="switchTab()">Multiparametric Analysis</a></li>
      </ul>
    </div>
  </div>
  {!!Form::model($analytical, ['route' => [$complete == true ? 'completeAnalytical.update' : 'analytical.update_', $analytical->id], 'method' => 'POST', 'id' => 'multiparametricAnalyticalForm'])!!}
  <input type="hidden" name="escenario_id" id="escenario_id" value="{{ !empty($duplicateFrom) ? $duplicateFrom : $analytical->escenario->id }}">
  <div class="tab-content">
    <br>
    {{--bloque fi--}}
    <div class="tab-pane active" id="FI">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>Fluid Information</h4>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <label>Fluid Type</label><label class="red">*</label>
              {!! Form::select('fluid_type',['Oil' => 'Oil', 'Gas' => 'Gas'], null, ['class'=>'form-control', 'id' => 'fluid_type']) !!}
            </div>
          </div>
          <hr>
          <div class="panel panel-default" id="div_oil">
            <div class="panel-heading">
              <h4 id="ms-title">Oil Properties </h4>
            </div>

            <div class="panel-body">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="viscosity_oil">Oil Viscosity</label><label class="red">*</label>
                  <div class="input-group">
                    {!! Form::text('viscosity_oil',$analytical->viscosity, ['class' =>'form-control', 'placeholder' => 'cP']) !!}
                    <span class="input-group-addon" id="basic-addon2">cP</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="volumetric_factor_oil">Oil Volumetric Factor</label><label class="red">*</label>
                  <div class="input-group">
                    {!! Form::text('volumetric_factor_oil',$analytical->volumetric_factor, ['class' =>'form-control', 'placeholder' => '-']) !!}
                    <span class="input-group-addon" id="basic-addon2">-</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default" id="div_gas">
            <div class="panel-heading">
              <h4 id="ms-title">Gas Properties </h4>
            </div>

            <div class="panel-body">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="viscosity_gas">Gas Viscosity</label><label class="red">*</label>
                  <div class="input-group">
                    {!! Form::text('viscosity_gas',$analytical->viscosity, ['class' =>'form-control', 'placeholder' => 'cP']) !!}
                    <span class="input-group-addon" id="basic-addon2">cP</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="volumetric_factor_gas">Gas Volumetric Factor</label><label class="red">*</label>
                  <div class="input-group">
                    {!! Form::text('volumetric_factor_gas',$analytical->volumetric_factor, ['class' =>'form-control', 'placeholder' => '-']) !!}
                    <span class="input-group-addon" id="basic-addon2">-</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{--bloque rp--}}
    <div class="tab-pane" id="RP">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>Rock Properties</h4>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="netpay">NetPay</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('netpay',null, ['class' =>'form-control', 'placeholder' => 'ft']) !!} 
                  <span class="input-group-addon" id="basic-addon2">ft</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="absolute_permeability" id="permeability_type">Absolute Permeability</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('absolute_permeability',null, ['class' =>'form-control', 'placeholder' => 'mD']) !!}
                  <span class="input-group-addon" id="basic-addon2">mD</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="porosity">Porosity</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('porosity',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
                  <span class="input-group-addon" id="basic-addon2">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{--bloque pd--}}
    <div class="tab-pane" id="PD">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>Production Data</h4>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="well_radius">Well Radius</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('well_radius',null, ['class' =>'form-control', 'placeholder' => 'ft']) !!}
                  <span class="input-group-addon" id="basic-addon2">ft</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="drainage_radius">Drainage Radius</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('drainage_radius',null, ['class' =>'form-control', 'placeholder' => 'ft']) !!}
                  <span class="input-group-addon" id="basic-addon2">ft</span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="reservoir_pressure">Reservoir Pressure</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('reservoir_pressure',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
                  <span class="input-group-addon" id="basic-addon2">psia</span>
                </div>
              </div>
            </div>
            <div class="col-md-6" id="input_oil">
              <div class="form-group">
                <label for="oil_rate">Oil Rate</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('fluid_rate_oil',$analytical->fluid_rate, ['class' =>'form-control', 'placeholder' => 'SBT/D']) !!}
                  <span class="input-group-addon" id="basic-addon2">SBT/D</span>
                </div>
              </div>
            </div>
            <div class="col-md-6" id="input_gas">
              <div class="form-group">
                <label for="gas_rate">Gas Rate</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('fluid_rate_gas',$analytical->fluid_rate, ['class' =>'form-control', 'placeholder' => 'MMSCF/D']) !!}
                  <span class="input-group-addon" id="basic-addon2">MMSCF/D</span>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="bhp">BHP</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('bhp',null, ['class' =>'form-control', 'placeholder' => 'BHP']) !!}
                  <span class="input-group-addon" id="basic-addon2">BHP</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{--bloque ma--}}
    <div class="tab-pane" id="MA">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>Damage Variables</h4>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="critical_radius">Critical Radius Derived From Maximum Critical Velocity, Vc <span class="red">*</span></label>
                <div class="form-group">
                  {!! Form::label('stored', 'Stored previously') !!}
                  {!! Form::select('selectStoredFB3', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-8', 'data-style' => 'btn-default', 'id' => 'selectStoredFB3')) !!}
                </div>
                <div class="input-group">
                  {!! Form::text('critical_radius',null, ['class' =>'form-control', 'placeholder' => 'ft', 'id' => 'FB3']) !!}
                  <span class="input-group-addon" id="basic-addon2">ft</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="total_volumen">Total Volume Of Water Based Fluids Pumped Into The Well <span class="red">*</span></label>
                <div class="form-group">
                  {!! Form::label('stored', 'Stored previously') !!}
                  {!! Form::select('selectStoredID3', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick ms-subparameter-picker select-stored-21', 'data-style' => 'btn-default', 'id' => 'selectStoredID3')) !!}
                </div>
                <div class="input-group">
                  {!! Form::text('total_volumen',null, ['class' =>'form-control', 'placeholder' => 'bbl', 'id' => 'ID3']) !!}
                  <span class="input-group-addon" id="basic-addon2">bbl</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="total_volumen">Saturation Pressure</label><label class="red">*</label>
                <div class="input-group">
                  {!! Form::text('saturation_presure',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
                  <span class="input-group-addon" id="basic-addon2">psia</span>
                </div>
              </div>
            </div>
          </div>
          <hr>

          <div class="row" style="margin: 10px;">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4>Critical Pressure By Damage Parameters</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mineral_scale_cp">Mineral Scales</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('mineral_scale_cp',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
                        <span class="input-group-addon" id="basic-addon2">psia</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="organic_scale_cp">Organic Scales</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('organic_scale_cp',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
                        <span class="input-group-addon" id="basic-addon2">psia</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="geomechanical_damage_cp">Geomechanical Damage - Drawdown</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('geomechanical_damage_cp',null, ['class' =>'form-control', 'placeholder' => 'psia']) !!}
                        <span class="input-group-addon" id="basic-addon2">psia</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button class="btn btn-primary pull-right" id="plot">Plot</button>
            <div class="row">
              <div class="col-md-12"><div id="chart"></div></div>
            </div>
            <div class="row">
              <div class="col-md-12"><div id="chart_2"></div></div>
            </div>
          </div>
          <hr>

          <div class="row" style="margin: 10px;">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4>K Damaged And K Base Ratio (Kd/Kb) By Damage Parameter</h4>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mineral_scale_kd">Mineral Scales</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('mineral_scale_kd',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="organic_scale_kd">Organic Scales</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('organic_scale_kd',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="geomechanical_damage_kd">Geomechanical Damage</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('geomechanical_damage_kd',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="fines_blockage">Fines Blockage</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('fines_blockage_kd',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="relative_permeability">Relative Permeability</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('relative_permeability_kd',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="induced_damage">Induced Damage</label><label for="*" class="red">*</label>
                      <div class="input-group">
                        {!! Form::text('induced_damage_kd',null, ['class' =>'form-control', 'placeholder' => '-']) !!}
                        <span class="input-group-addon" id="basic-addon2">-</span>
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
  <div class="row">
    {!! Form::hidden('only_s', '', array('id' => 'only_s')) !!}
    <div class="col-md-12 scenario-buttons">
      <div align="left">
      <button type="button" class="btn btn-success" onclick="verifyMultiparametric('save');" id="save_calc">Save</button>
      <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
      </div>
      <div align="right">
      <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
      <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
      <button type="button" class="btn btn-primary" style="display: none" onclick="verifyMultiparametric('run');" id="run_calc">Run</button>
      </div>
    </div>
  </div>
  {{-- <div class="row">
    <div class="col-xs-12">
      <p class="pull-right">
        <button class="btn validate_wr btn-success" id="button_wr" name="button_wr">Save</button>
        {!! Form::submit('Run' , array('class' => 'btn btn-primary', 'id' => 'save')) !!}
        <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
      </p>
    </div>
  </div> --}}
  {!! Form::Close() !!}

  @include('layouts/modal_advisor')
  @include('layouts/template/modal_import_external')
@endsection

{{--/////////////////////////////////////////////////////////////////////////////////////////////////--}}

@section('Scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
  @include('css/add_multiparametric')
  @include('multiparametricAnalysis.analytical.cuerpo.createJs')
  @include('js/frontend_validator')
  @include('js/frontend_rules/multiparametric_analytical')
  @include('js/modal_error')
  @include('js/advisor')
  @include('js/modal_error_frontend')
  @include('css/modal_error_frontend')
@endsection