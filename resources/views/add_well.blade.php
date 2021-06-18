@extends('layouts.basic')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<div id="page_1">
<div class="masthead">
   <h1>Add Well</h1>
</div>


<div onload="well();">
   {!!Form::open(array('url' => 'AddFormationWCS', 'method' => 'post', 'id'=>'form'))!!}
   <div id="well">
      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>General Data</h4>
         </div>

         <div class="panel-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
                     {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::select('basin', $cuenca->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control', 'id'=>'basin')) !!}
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
                     {!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::select('field', [], null, array('placeholder' => '','class'=>'form-control', 'id'=>'field')) !!}
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('nameWell') ? 'has-error' : ''}}">
                     {!! Form::label('name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                     {!! Form::text('nameWell', '', array('class' => 'form-control','id'=>'nameWell')) !!}
                  </div>
               </div>
               <div class="col-md-6">
                <div class="form-group {{$errors->has('uwi') ? 'has-error' : ''}}">
                   {!! Form::label('uwi_label', 'UWI') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                   {!! Form::text('uwi', '', array('class' => 'form-control','id'=>'uwi')) !!}
                </div>
             </div>
            </div>
         </div>
      </div>

      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>Well data</h4>
         </div>
         <div class="panel-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('type') ? 'has-error' : ''}}">
                     {!! Form::label('type', 'Type') !!}
                     {!! Form::select('type', [
                     ' ' => ' ',
                     'Injector' => 'Injector',
                     'Producer' => 'Producer',
                     'Dead' => 'Dead',
                     'Shut-off' => 'Shut-off'],null, array('class'=>'form-control', 'id'=>'type')
                     ) !!}
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('wellRadius') ? 'has-error' : ''}}">
                     {!! Form::label('well radius', 'Well Radius') !!}
                     <div class="input-group">
                        {!! Form::text('wellRadius',null, ['placeholder' => 'ft', 'class' =>'form-control','id'=>'wellRadius']) !!}
                        <span class="input-group-addon" id="wellRadius-addon">ft</span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('drainageRadius') ? 'has-error' : ''}}">
                     {!! Form::label('drainage radius', 'Drainage Radius') !!}
                     <div class="input-group">
                        {!! Form::text('drainageRadius',null, ['placeholder' => 'ft', 'class' =>'form-control','id'=>'drainageRadius']) !!}
                        <span class="input-group-addon" id="drainageRadius-addon">ft</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>Production Data</h4>
         </div>
         <div class="panel-body">
            <div id="excel2" style="overflow: scroll" class="handsontable"></div>
            <div class="row">
               <div id="productionData_Well_g"></div>
            </div>
            <div class="col-md-12">
               <button class="btn btn-primary pull-right" onclick="plot_wellProductionData()">Plot</button>  
            </div>
            {!! Form::hidden('ProdD2', '', array('class' => 'form-control', 'id' => 'ProdD2', 'name' => 'ProdD2')) !!}
         </div>
      </div>

      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>Surface Coordinates</h4>
         </div>
         <div class="panel-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('XW') ? 'has-error' : ''}}">
                     {!! Form::label('X', 'Latitude') !!}
                     <div class="input-group">
                        {!! Form::text('XW',null, ['placeholder' => '°', 'class' =>'form-control','id'=>'XW']) !!}
                        <span class="input-group-addon" id="basic-addon2">°</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('YW') ? 'has-error' : ''}}">
                     {!! Form::label('Y', 'Longitude') !!}
                     <div class="input-group">
                        {!! Form::text('YW',null, ['placeholder' => '°', 'class' =>'form-control','id'=>'YW']) !!}
                        <span class="input-group-addon" id="basic-addon2">°</span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group {{$errors->has('TDVW') ? 'has-error' : ''}}">
                     {!! Form::label('TVD', 'Total TVD') !!}
                     <div class="input-group">
                        {!! Form::text('TDVW',null, ['placeholder' => 'ft', 'class' =>'form-control','id'=>'TDVW']) !!}
                        <span class="input-group-addon" id="basic-addon2">ft</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>Production Test (PLT)</h4>
         </div>
         <div class="panel-body">
            <div id="excelPlt" style="overflow: scroll" class="handsontable"></div>
            <div class="row">
               <div id="plt_Interval"></div>
            </div>
            <div class="col-md-12">
               <button class="btn btn-primary pull-right" onclick="plotPlt()">Plot</button>  
            </div>
            {!! Form::hidden('plt', '', array('class' => 'form-control', 'id' => 'plt', 'name' => 'plt')) !!}
         </div>
      </div>
   </div>


   </br>

   <div class="row">
      <div class="col-xs-12">
         <p class="pull-right">
            {!! Form::submit('Save' , array('class' => 'btn btn-primary', 'data-toggle' => 'modal', 'OnClick'=>'javascript: Mostrar();')) !!}
            <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
         </p>
         {!! Form::Close() !!}
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

<div class="modal fade" id="myModalRP" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Production Data Successfully Entered.</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>
@endsection


@section('Scripts')
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.js"></script>
  <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/0.15.0-beta6/handsontable.full.min.css">
  @include('js/add_well')
  @include('js/modal_error')
  @include('css/add_well')
@endsection