@extends('layouts.basic')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h1>Add Formation</h1>
<br/>
<div class="panel panel-default">
   <div class="panel-heading">
      <h4>General Data</h4>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('fieldFormation') ? 'has-error' : ''}}">
               {!!Form::open(array('url' => 'AddFormationS', 'method' => 'post', 'id'=>'form'))!!}
               {!! Form::label('basin', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('fieldFormation', $campo->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('nameFormation') ? 'has-error' : ''}}">
               {!! Form::label('name', 'Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('nameFormation', '', array('class' => 'form-control')) !!}
            </div>
         </div>
      </div>

      <div class="row" style="display:none;">
         <div class="col-md-6">
            {!! Form::label('coordinates', 'Coordinates') !!}
            <a href="#credits1" class="toggle btn btn-default btn-block" id="coorB1"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span>Add coordinates</a>
            <div id="credits1" class="well hidden">
               <label class="radio-inline">
               <input type="radio" name="prof" value= "Top">Top
               </label>
               <label class="radio-inline">
               <input type="radio" name="prof" value= "Bottom">Bottom
               </label>
               </br></br>
               <div id="excel1" style="overflow: scroll" class="handsontable"></div>
               {!! Form::hidden('CoordF', '', array('class' => 'form-control', 'id' => 'CoordF', 'name' => 'CoordF')) !!}
            </div>
         </div>
      </div>
   </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
       <h4>Producing Intervals</h4>
    </div>

    <div class="panel-body">
       <div class="row">
          <div class="col-md-12">
            <div id="table_producing_intervals" style="overflow: scroll" class="handsontable"></div>
            {!! Form::hidden('producing_intervals', '', array('class' => 'form-control', 'id' => 'producing_intervals', 'name' => 'producing_intervals')) !!}
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
            <div class="form-group {{$errors->has('topFormation') ? 'has-error' : ''}}">
               {!! Form::label('top', 'Top') !!}
               <div id = "alert_top"></div>
               <div class="input-group">
                  {!! Form::text('topFormation',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'top']) !!}
                  <span class="input-group-addon" id="top-addon">ft</span>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('reservoirPressureFormation') ? 'has-error' : ''}}">
               {!! Form::label('reservoir pressure', 'Reservoir Pressure') !!}
               <div id = "alert_reservoir"></div>
               <div class="input-group">
                  {!! Form::text('reservoirPressureFormation',null, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'reservoir']) !!}
                  <span class="input-group-addon" id="reservoir-addon">psia</span>
               </div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('porosityFormation') ? 'has-error' : ''}}">
               {!! Form::label('porosity', 'Average Porosity') !!}
               <div id = "alert_porosity"></div>
               <div class="input-group">
                  {!! Form::text('porosityFormation',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'porosity']) !!}
                  <span class="input-group-addon" id="porosity-addon"</span>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('permeabilityFormation') ? 'has-error' : ''}}">
               {!! Form::label('permeability', 'Average Permeability') !!}
               <div id = "alert_permeability"></div>
               <div class="input-group">
                  {!! Form::text('permeabilityFormation',null, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'permeability']) !!}
                  <span class="input-group-addon" id="permeability-addon">mD</span>
               </div>
            </div>
         </div>
      </div>

      <hr>
      @include('pvtTable.pvtTable')

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
                  <div id="waterOil_Formation_g"></div>
               </div>
               <div class="col-md-12">
                  <button class="btn btn-primary pull-right" onclick="plot_waterOil_Formation()">Plot</button>
               </div>
               {!! Form::hidden('RelP', '', array('class' => 'form-control', 'id' => 'RelP', 'name' => 'RelP')) !!}
            </div>
         </div>

         <div class="col-md-6">
            <a href="#credits2" class="toggle btn btn-default btn-block"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span> Gas-Liquid</a>
            <div id="credits2" class="well hidden">
               <div id="excel3" style="overflow: scroll" class="handsontable"></div>
               <div class="row">
                  <div id="gasOil_Formation_g"></div>
               </div>
               <div class="col-md-12">
                  <button class="btn btn-primary pull-right" onclick="plot_gasOil_Formation()">Plot</button>
               </div>
               {!! Form::hidden('RelP2', '', array('class' => 'form-control', 'id' => 'RelP2', 'name' => 'RelP2')) !!}
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
         {!! Form::submit('Save' , array('class' => 'check_field_pvt btn btn-primary', 'data-toggle' => 'modal', 'OnClick'=>'javascript: Mostrar();')) !!}
         <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
      </p>
      {!! Form::Close() !!}
   </div>
</div>

<div class="modal fade" id="myModalCoor" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Coordinates Successfully Entered.</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
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
            <p>Relative Permeability Successfully Entered.</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
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

<div class="modal fade" id="modalImportarPvt" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Tree Pvt Data Globals</h4>
         </div>
         <div class="modal-body">
            <div id="jstree_demo_div"></div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

@endsection

@section('Scripts')
  @include('pvtTable.pvtTableJs');
  @include('js/add_formation')
  @include('js/modal_error')
@endsection
