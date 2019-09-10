@extends('layouts.basic')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h1>Producing Interval - <small>Well: {{ $pozo->nombre }}</small> </h1>
<br/>

{!!Form::open(array('url' => 'AddFormationWellCS', 'method' => 'post', 'id'=>'form'))!!}
<div class="panel panel-default">
   <div class="panel-heading" id="Basin">
      <h4>General Data</h4>
   </div>

   <div class="panel-body">
      <select name="wellName" class="form-control hidden" id="Well">
         <option></option>
         @foreach($well as $wells)
         <option value="{{ $wells->id }}">{{ $wells->nombre }}</option>
         @endforeach
      </select>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('formacionName') ? 'has-error' : ''}}">
               {!! Form::label('Formation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('formacionName', $formacion->lists('nombre','id'),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'Basin2')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('nameInterval') ? 'has-error' : ''}}">
               {!! Form::label('Name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('nameInterval', $intervalo->lists('nombre','id'),null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'nameInterval')) !!}
            </div>
         </div>
      </div>
   </div>
</div>

<div class="panel panel-default">
   <div class="panel-heading">
      <h4>Reservoir Data</h4>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('top') ? 'has-error' : ''}}">
               {!! Form::label('top', 'Top') !!}
               <div id = "alert_top"></div>
               <div class="input-group">
                  {!! Form::text('top', '', array('class' => 'form-control','id'=>'top', 'placeholder' => 'ft')) !!}
                  <span class="input-group-addon" id="top-addon">ft</span>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('netpay') ? 'has-error' : ''}}">
               {!! Form::label('netpay', 'Net Pay') !!}
               <div id = "alert_netpay"></div>
               <div class="input-group">
                  {!! Form::text('netpay',null, ['placeholder' => 'ft','id'=>'netpay', 'class' =>'form-control']) !!}
                  <span class="input-group-addon" id="netpay-addon">ft</span>
               </div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('porosity') ? 'has-error' : ''}}">
               {!! Form::label('porosity', 'Porosity') !!}
               <div id = "alert_porosity"></div>
               <div class="input-group">
                  {!! Form::text('porosity',null, ['placeholder' => '%','id'=>'porosity', 'class' =>'form-control']) !!}
                  <span class="input-group-addon" id="porosity-addon">%</span>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('permeability') ? 'has-error' : ''}}">
               {!! Form::label('permeability', 'Permeability') !!}
               <div id = "alert_permeability"></div>
               <div class="input-group">
                  {!! Form::text('permeability',null, ['placeholder' => 'mD','id'=>'permeability', 'class' =>'form-control']) !!}
                  <span class="input-group-addon" id="permeability-addon">mD</span>
               </div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('reservoir') ? 'has-error' : ''}}">
               {!! Form::label('reservoir pressure', 'Reservoir Pressure') !!}
               <div id = "alert_reservoir"></div>
               <div class="input-group">
                  {!! Form::text('reservoir',null, ['placeholder' => 'psia','id'=>'reservoir', 'class' =>'form-control']) !!}
                  <span class="input-group-addon" id="reservoir-addon">psia</span>
               </div>
            </div>
         </div>
      </div>
      <hr>

      <center>
         <h4><b>Relative Permeability And Capilar Pressure</b></h4>
      </center>

   <br>

   <div class="row">
      <div class="col-md-6">
         <a href="#credits" class="toggle btn btn-default btn-block"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Water-Oil</a>
         <div id="credits" class="well hidden">
            <div id="excel" style="overflow: scroll" class="handsontable"></div>
            <div class="row">
               <div id="waterOil_Interval_Well_g"></div>
            </div>
            <div class="col-md-12">
               <button class="btn btn-primary pull-right" onclick="plot_waterOil_Formation_Well()">Plot</button>  
            </div>
            {!! Form::hidden('RelP', '', array('class' => 'form-control', 'id' => 'RelP', 'name' => 'RelP')) !!}
         </div>
      </div>
      <div class="col-md-6">
         <a href="#credits2" class="toggle btn btn-default btn-block"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Gas-Liquid</a>
         <div id="credits2" class="well hidden">
            <div id="excel2" style="overflow: scroll" class="handsontable"></div>
            <div class="row">
               <div id="gasOil_Formation_Well_g"></div>
            </div>
            <div class="col-md-12">
               <button class="btn btn-primary pull-right" onclick="plot_gasOil_Formation_Well()">Plot</button>  
            </div>
            {!! Form::hidden('RelP2', '', array('class' => 'form-control', 'id' => 'RelP2', 'name' => 'RelP2')) !!}
         </div>
      </div>
   </div>

</br>

<hr>
   @include('pvtTable.pvtTable')
</br>

<hr>
<div class="row">
   <div class="col-md-12">
      <a href="#reservoirPressure_button" class="toggle btn btn-default btn-block"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Reservoir Pressure Data</a>
      <div id="reservoirPressure_button" class="well hidden">
         <div id="reservoirPressure_table" style="overflow: scroll" class="handsontable"></div>
         <div class="row">
            <div id="reservoirPressure_Interval"></div>
         </div>
      </br>
      <div class="col-md-12">
         <button class="btn btn-primary pull-right" onclick="plot_reservoirPressure_Interval()">Plot</button>  
      </div>
   </br></br>
   {!! Form::hidden('reservoirPressure', '', array('class' => 'form-control', 'id' => 'reservoirPressure', 'name' => 'reservoirPressure')) !!}
</div>
</div>
</div>
</div>
</div>
</div>

</br>

<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
         {!! Form::submit('Add' , array('class' => 'btn btn-primary check_field_pvt', 'data-toggle' => 'modal', 'OnClick'=>'javascript: Mostrar();')) !!}
         <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
      </p>
      {!! Form::Close() !!}
   </div>
</div>

<div class="modal fade" id="myModalRP" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Relative Permeability Successfully Entered.</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="confirmWar" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Warning</h4>
         </div>
         <div class="modal-body">
            <p>Values Outside Normal Limits. Do You Want To Save? </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirm">Ok</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Missing Information. Do You Want To Save?</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirm">Ok</button>
         </div>
      </div>
   </div>
</div>

@endsection


@section('Scripts')
<script
  src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"
  integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk="
  crossorigin="anonymous"></script>
@include('pvtTable.pvtTableJs');
@include('js/add_producing_interval_well')
@include('js/modal_error')
@endsection