@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h2>Producing Interval - <small>{{$data->nombre}}</small></h2>
<br/>

{!!Form::model($data, array('route' => array('listIntervalC.update', $data->id), 'method' => 'PATCH', 'id'=>'form'), array('role' => 'form'))!!}
   <div class="panel panel-default">
   <div class="panel-heading" id="Basin">
      <h4>General Data</h4>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('basin', $basin->lists('nombre','id'),$data->pozo->cuenca_id, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'basin')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               {!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('field', [],null, array('placeholder' => '','class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'field')) !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('wellName') ? 'has-error' : ''}}">
               {!! Form::label('Well', 'Well') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('wellName', [],null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'wellName')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('formacionName') ? 'has-error' : ''}}">
               {!! Form::label('Formation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('formacionName', [],null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'formacionName')) !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('nameInterval') ? 'has-error' : ''}}">
               {!! Form::label('Name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('nameInterval', [],null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'nameInterval')) !!}
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
                  {!! Form::text('top', $data->top, array('class' => 'form-control','id'=>'top', 'placeholder' => 'ft')) !!}
                  <span class="input-group-addon" id="top-addon">ft</span>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('netpay') ? 'has-error' : ''}}">
               {!! Form::label('netpay', 'Net Pay') !!}
               <div id = "alert_netpay"></div>
               <div class="input-group">
                  {!! Form::text('netpay',$data->netpay, ['placeholder' => 'ft','id'=>'netpay', 'class' =>'form-control']) !!}
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
                  {!! Form::text('porosity',$data->porosidad, ['placeholder' => '-','id'=>'porosity', 'class' =>'form-control']) !!}
                  <span class="input-group-addon" id="porosity-addon">-</span>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('permeability') ? 'has-error' : ''}}">
               {!! Form::label('permeability', 'Permeability') !!}
               <div id = "alert_permeability"></div>
               <div class="input-group">
                  {!! Form::text('permeability',$data->permeabilidad, ['placeholder' => 'mD','id'=>'permeability', 'class' =>'form-control']) !!}
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
                  {!! Form::text('reservoir',$data->presion_reservorio, ['placeholder' => 'psia','id'=>'reservoir', 'class' =>'form-control']) !!}
                  <span class="input-group-addon" id="reservoir-addon">psia</span>
               </div>
            </div>
         </div>
      </div>

      <hr>

      <center>
         <h4><b>Relative Permeability And Capilar Pressure</b></h4>
      </center>

      </br>

      <div class="row">
         <div class="col-md-6">
            <a href="#credits" class="toggle btn btn-default btn-block"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Water-Oil</a>
            <div id="credits" class="well hidden">
               <div id="excel" style="overflow: scroll" class="handsontable"></div>
               <div class="row">
                  <div id="waterOil_Interval_g"></div>
               </div>
               <div class="col-md-12">
                  <button class="btn btn-primary pull-right" onclick="plot_waterOil_Interval()">Plot</button>
               </div>
               {!! Form::hidden('RelP', '', array('class' => 'form-control', 'id' => 'RelP', 'name' => 'RelP')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <a href="#credits2" class="toggle btn btn-default btn-block"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Gas-Liquid</a>
            <div id="credits2" class="well hidden">
               <div id="excel2" style="overflow: scroll" class="handsontable"></div>
               <div class="row">
                  <div id="gasOil_Interval_g"></div>
               </div>
               <div class="col-md-12">
                  <button class="btn btn-primary pull-right" onclick="plot_gasOil_Interval()">Plot</button>
               </div>
               {!! Form::hidden('RelP2', '', array('class' => 'form-control', 'id' => 'RelP2', 'name' => 'RelP2')) !!}
            </div>
         </div>
      </div>

      </br>
         @include('pvtTable.pvtTable')
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

   </br>

   <div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
         {!! Form::hidden('id', $data->id) !!}
         {!! Form::submit('Next' , array('class' => 'maximize maximize2 btn btn-primary check_field_pvt', 'data-toggle' => 'modal', 'OnClick'=>'javascript: Mostrar();')) !!}
         <a href="{!! url('listIntervalC') !!}" class="btn btn-danger" role="button">Cancel</a>
      </p>
      
   </div>
   </div>

   </div>
{!! Form::Close() !!}
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

@include('css/edit_producing_interval')

@section('Scripts')
   @include('pvtTable.pvtTableJs')
   @include('js/edit_producing_interval')
   @include('js/modal_error')
   <script
  src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"
  integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk="
  crossorigin="anonymous"></script>
@endsection
