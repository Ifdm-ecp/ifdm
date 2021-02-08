@extends('layouts.generaldatabase')

@section('title', 'IFDM request')

@section('content')
@include('layouts/modal_error')

  <h1>Request</h1>
  <hr>

  <div class="row">
    <div class="col-md-4">
      <div class="form-group {{$errors->has('request') ? 'has-error' : ''}}">
        {!! Form::label('req', 'Please Select The Request You Want To Send') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
        {!! Form::select('request', ['' => '', 'Damage variables' => 'Damage Variables', 'Well' => 'Well', 'Producing interval' => 'Producing Interval'],null, array('class'=>'form-control selectpicker show-tick', 'id'=>'request', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
      </div>
    </div>
  </div>
  <hr>

  <div name="damage_variables_div" id="damage_variables_div">
    <div class="row">
      <div class="col-md-4">
          <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
              {!!Form::open(array('url' => 'requestDamageVariables', 'method' => 'post'))!!}
              {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('basin', $cuenca->lists('nombre','id'),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'basin', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
              {!! Form::label('Field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('field', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'field', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('well') ? 'has-error' : ''}}">
              {!! Form::label('well', 'Well') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('well', array(),null, array('class'=>'form-control selectpicker show-tick', 'id'=>'well', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}

          </div>
      </div>
    </div> 

    <div class="row">
      <div class="col-md-4">
          <div class="form-group {{$errors->has('mecan_dano') ? 'has-error' : ''}}">
              {!! Form::label('mecan_dano', 'Damage Mechanisms') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('mecan_dano', $mecan_dano->lists('nombre','id'),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'mecan_dano', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('damage_variables') ? 'has-error' : ''}}">
              {!! Form::label('damage_variables', 'Damage Variables') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('damage_variables', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'damage_variables', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('damage_configuration') ? 'has-error' : ''}}">
              {!! Form::label('damage_configuration', 'Damage Configuration') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('damage_configuration', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'damage_configuration', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
    </div> 

    <div class="row">
      <div class="col-md-4">
          <div class="form-group {{$errors->has('value') ? 'has-error' : ''}}">
              {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::text('value', '', array('class' => 'form-control')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('date') ? 'has-error' : ''}}">
              {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::date('date', null, ['class' =>'form-control', 'id' => 'date']); !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('comment') ? 'has-error' : ''}}">
              {!! Form::label('comment', 'Comment') !!}
              {!! Form::text('comment', '', array('class' => 'form-control')) !!}
          </div>
      </div>
    </div> <br>
    <div class="row">
      <div class="col-xs-12">
        <p class="pull-right">
          {!! Form::submit('Save' , array('class' => 'btn btn-primary')) !!}
          <a href="{!! url('homeC') !!}" class="btn btn-danger" role="button">Cancel</a>
        </p>
      </div>
    </div> 
    {!! Form::Close() !!}
  </div> 



  <div name="well_div" id="well_div">
    <div class="row">
      <div class="col-md-4">
          <div class="form-group {{$errors->has('basin_well') ? 'has-error' : ''}}">
              {!!Form::open(array('url' => 'requestWell', 'method' => 'post'))!!}
              {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('basin_well', $cuenca->lists('nombre','id'),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'basin_well', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('field_well') ? 'has-error' : ''}}">
              {!! Form::label('Field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('field_well', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'field_well', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('well_well') ? 'has-error' : ''}}">
              {!! Form::label('well', 'Well Data') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('well_well', array(),null, array('class'=>'form-control selectpicker show-tick', 'id'=>'well_well', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
    </div> 

    <div class="row">
      <div class="col-md-4">
          <div class="form-group {{$errors->has('variable_name') ? 'has-error' : ''}}">
              {!! Form::label('Variable Name', 'Variable Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('variable_name', ['' => '', 'BHP' => 'BHP', 'Well radius' => 'Well radius', 'Drainage radius' => 'Drainage radius', 'Latitude' => 'Latitude', 'Longitude' => 'Longitude', 'TVD' => 'TVD', 'Production data' => 'Production data'],null, array('class'=>'form-control selectpicker show-tick', 'id'=>'variable_name', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4" name="well_div_date" id="well_div_date">
          <div class="form-group {{$errors->has('date_well') ? 'has-error' : ''}}">
              {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::date('date_well', null, ['class' =>'form-control', 'id' => 'date_well']); !!}
          </div>
      </div>
      <div class="col-md-4" name="well_div_com" id="well_div_com">
          <div class="form-group {{$errors->has('comment_well') ? 'has-error' : ''}}">
              {!! Form::label('comment', 'Comment') !!}
              {!! Form::text('comment_well', '', array('class' => 'form-control')) !!}
          </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4" name="well_div_val" id="well_div_val">
          <div class="form-group {{$errors->has('value_well') ? 'has-error' : ''}}">
              {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::text('value_well', '', ['class' => 'form-control', 'id' => 'value_well']); !!}
          </div>
      </div>
      <div name="well_div_tab" id="well_div_tab">
        <br>
        <div class="panel-body">
          <div id="excel2" style="overflow: scroll" class="handsontable"></div>
          {!! Form::hidden('ProdD2', 'secret', array('id' => 'ProdD2')) !!}
        </div>
      </div>
    </div> <br>
    <div class="row">
      <div class="col-xs-12">
        <p class="pull-right">
          {!! Form::submit('Save' , array('class' => 'btn btn-primary maximize max')) !!}
          <a href="{!! url('homeC') !!}" class="btn btn-danger" role="button">Cancel</a>
        </p>
      </div>
    </div> 
    {!! Form::Close() !!}
  </div> 


  <div name="interval_div" id="interval_div">
    <div class="row">
      <div class="col-md-4">
          <div class="form-group {{$errors->has('basin_interval') ? 'has-error' : ''}}">
              {!!Form::open(array('url' => 'requestInterval', 'method' => 'post'))!!}
              {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('basin_interval', $cuenca->lists('nombre','id'),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'basin_interval', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('field_interval') ? 'has-error' : ''}}">
              {!! Form::label('Field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('field_interval', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'field_interval', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('well_interval') ? 'has-error' : ''}}">
              {!! Form::label('well', 'Well') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('well_interval', array(),null, array('class'=>'form-control selectpicker show-tick', 'id'=>'well_interval', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
    </div> 

    <div class="row">
      <div class="col-md-4">
          <div class="form-group {{$errors->has('formation_interval') ? 'has-error' : ''}}">
              {!! Form::label('Formation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('formation_interval', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'formation_interval', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('interval_interval') ? 'has-error' : ''}}">
              {!! Form::label('Producing Interval', 'Producing Interval Data') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('interval_interval', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'interval_interval', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('variable_name') ? 'has-error' : ''}}">
              {!! Form::label('Variable name', 'Variable Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::select('variable_name', ['' => '', 'Top' => 'Top', 'Net pay' => 'Net pay', 'Porosity' => 'Porosity', 'Permeability' => 'Permeability', 'Reservoir pressure' => 'Reservoir pressure', 'Relative permeability and capilar pressure' => 'Relative permeability and capilar pressure'],null, array('class'=>'form-control selectpicker show-tick', 'id'=>'variable_name_int', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
          </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4" name="interval_div_val" id="interval_div_val">
          <div class="form-group {{$errors->has('value_interval') ? 'has-error' : ''}}">
              {!! Form::label('value', 'Value') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::text('value_interval', '', array('class' => 'form-control')) !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('date_interval') ? 'has-error' : ''}}">
              {!! Form::label('date', 'Monitoring Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
              {!! Form::date('date_interval', null, ['class' =>'form-control', 'id' => 'date_interval']); !!}
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group {{$errors->has('comment_interval') ? 'has-error' : ''}}">
              {!! Form::label('comment', 'Comment') !!}
              {!! Form::text('comment_interval', '', array('class' => 'form-control')) !!}
          </div>
      </div>
    </div> 
    <div class="row" name="interval_div_tab" id="interval_div_tab">
      <div class="col-md-6">
        {!! Form::label('WO', 'Water-Oil') !!}
        <div id="excel" style="overflow: scroll" class="handsontable"></div>
        <input type='hidden' id='RelP' name='RelP'>
      </div>   
      <div class="col-md-6">
        {!! Form::label('GL', 'Gas-Liquid') !!}
        <div id="excel3" style="overflow: scroll" class="handsontable"></div>
        <input type='hidden' id='RelP2' name='RelP2'>
      </div>     
    </div><br>
    <div class="row">
      <div class="col-xs-12">
        <p class="pull-right">
          {!! Form::submit('Save' , array('class' => 'btn btn-primary maximize3 maximize waterOil gasLiquid')) !!}
          <a href="{!! url('homeC') !!}" class="btn btn-danger" role="button">Cancel</a>
        </p>
      </div>
    </div> 
    {!! Form::Close() !!}
  </div> 

  <div id="date_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Error</h4>
        </div>
        <div class="modal-body">
          <p class="text-danger">
            <small>
              <p>Date Should Not Exceed The Current Date.</p>
            </small>
          </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('Scripts')
  <script src="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.js"></script>
  <link type="text/css" rel="stylesheet" href="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.min.css">
  @include('js/add_request')
  @include('js/modal_error')
@endsection


