@extends('layouts.basic')
@section('title', 'IFDM Database')
@section('content')
@include('layouts/modal_error')

<h1>Add Damage Variables</h1>
<br/>

{!!Form::open(array('url' => 'AddMeasurementCS', 'method' => 'post'))!!}
<div class="row">
   <div class="col-md-4">
      <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
         {!! Form::label('basin', 'Basin') !!}
         {!! Form::select('basin', $cuenca->lists('nombre','id'),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'basin', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
      </div>
   </div>
   <div class="col-md-4">
      <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
         {!! Form::label('Field', 'Field') !!}
         {!! Form::select('field', array(),null, array('placeholder'=>'', 'class'=>'form-control selectpicker show-tick', 'id'=>'field', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
      </div>
   </div>
   <div class="col-md-4">
      <div class="form-group {{$errors->has('well') ? 'has-error' : ''}}">
         {!! Form::label('well', 'Well') !!}
         {!! Form::select('well', array(),null, array('class'=>'form-control selectpicker show-tick', 'id'=>'well', 'data-live-search'=>'true', 'data-style'=>'btn-default')) !!}
      </div>
   </div>
</div>

<br> 

<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#mineral_scales_c" id="mineral_scales" onclick="switchTab()">Mineral Scales</a></li>
         <li><a data-toggle="tab" href="#fine_blockage_c" id="fine_blockage" onclick="switchTab()">Fine Blockage</a></li>
         <li><a data-toggle="tab" href="#organic_scales_c" id="organic_scales" onclick="switchTab()">Organic Scales</a></li>
         <li><a data-toggle="tab" href="#relative_permeability_c" id="relative_permeability" onclick="switchTab()">Relative Permeability</a></li>
         <li><a data-toggle="tab" href="#induce_damage_c" id="induce_damage" onclick="switchTab()">Induced Damage</a></li>
         <li><a data-toggle="tab" href="#geomechanical_damage_c" id="geomechanical_damage" onclick="switchTab()">Geomechanical Damage</a></li>
      </ul>
   </div>
   <div class="tab-content">
      <div id="mineral_scales_c" class="tab-pane active">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Mineral scales</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('SIC', 'Scale Index Of CaCO3') !!}   
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS1') ? 'has-error' : ''}}">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group">
                           {!! Form::text('MS1', null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'MS1']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateMS1') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateMS1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateMS1']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS1comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('MS1comment',null, ['class' =>'form-control', 'id' => 'MS1comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('BaSO4', 'Scale Index Of BaSO4') !!}
                  </div>
               </div>
      
               <br>   
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS2') ? 'has-error' : ''}}">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group">
                           {!! Form::text('MS2',null, ['placeholder' => '-', 'class' =>'form-control pull-right', 'id' => 'MS2']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateMS2') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateMS2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateMS2']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS2comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('MS2comment',null, ['class' =>'form-control', 'id' => 'MS2comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('SIS', 'Scale Index Of Iron Scales') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS3') ? 'has-error' : ''}}">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group">
                           {!! Form::text('MS3',null, ['placeholder' => '-', 'class' =>'form-control pull-right', 'id' => 'MS3']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateMS3') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateMS3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateMS3']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS3comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('MS3comment',null, ['class' =>'form-control', 'id' => 'MS3comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Backflow [Ca]') !!}
                  </div>
               </div>
      
               <br> 
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('MS4') ? 'has-error' : ''}}">
                           {!! Form::text('MS4',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right', 'id' => 'MS4']) !!}
                           <span class="input-group-addon" id="basic-addon2">ppm</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateMS4') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateMS4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateMS4']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS4comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('MS4comment',null, ['class' =>'form-control', 'id' => 'MS4comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Backflow [Ba]') !!}
                  </div>
               </div>
      
               <br>  
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS5') ? 'has-error' : ''}}">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group">
                           {!! Form::text('MS5',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right', 'id' => 'MS5']) !!}
                           <span class="input-group-addon" id="basic-addon2">ppm</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateMS5') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateMS5', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateMS5']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('MS5comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('MS5comment',null, ['class' =>'form-control', 'id' => 'MS5comment']) !!}
                     </div>
                  </div>
               </div>
      
            </div>
         </div>
      </div>
      <div id="fine_blockage_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Fine Blockage</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('ACW', '[Al] on Produced Water') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('FB1') ? 'has-error' : ''}}">
                           {!! Form::text('FB1',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right', 'id' => 'FB1']) !!}
                           <span class="input-group-addon" id="basic-addon2">ppm</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateFB1') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateFB1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateFB1']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('FB1comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('FB1comment',null, ['class' =>'form-control', 'id' => 'FB1comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('SCW', '[Si] on produced water') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('FB2') ? 'has-error' : ''}}">
                           {!! Form::text('FB2',null, ['placeholder' => 'ppm', 'class' =>'form-control pull-right', 'id' => 'FB2']) !!}
                           <span class="input-group-addon" id="basic-addon2">ppm</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateFB2') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateFB2',null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateFB2']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('FB2comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('FB2comment',null, ['class' =>'form-control', 'id' => 'FB2comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('CRF', 'Critical Radius derived from maximum critical velocity, Vc') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('FB3') ? 'has-error' : ''}}">
                           {!! Form::text('FB3',null, ['placeholder' => 'ft', 'class' =>'form-control pull-right', 'id' => 'FB3']) !!}
                           <span class="input-group-addon" id="basic-addon2">ft</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateFB3') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateFB3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateFB3']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('FB3comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('FB3comment',null, ['class' =>'form-control', 'id' => 'FB3comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('MF', 'Mineralogy Factor') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('FB4') ? 'has-error' : ''}}">
                           {!! Form::text('FB4',null, ['placeholder' => '-', 'class' =>'form-control pull-right', 'id' => 'FB4']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateFB4') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateFB4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateFB4']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('FB4comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('FB4comment',null, ['class' =>'form-control', 'id' => 'FB4comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('CPF', 'Mass of crushed proppant inside Hydraulic Fractures') !!}
                  </div>
               </div>
      
               <br> 
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('FB5') ? 'has-error' : ''}}">
                           {!! Form::text('FB5',null, ['placeholder' => 'lbs', 'class' =>'form-control pull-right', 'id' => 'FB5']) !!}
                           <span class="input-group-addon" id="basic-addon2">lbs</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateFB5') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateFB5', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateFB5']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('FB5comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('FB5comment',null, ['class' =>'form-control', 'id' => 'FB5comment']) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="organic_scales_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Organic Scales</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'CII Factor: Colloidal Instability Index') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('OS1') ? 'has-error' : ''}}">
                           {!! Form::text('OS1',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'OS1']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateOS1') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateOS1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateOS1']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('OS1comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('OS1comment',null, ['class' =>'form-control', 'id' => 'OS1comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Volume of HCl pumped into the formation') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('OS2') ? 'has-error' : ''}}">
                           {!! Form::text('OS2',null, ['placeholder' => 'bbl', 'class' =>'form-control', 'id' => 'OS2']) !!}
                           <span class="input-group-addon" id="basic-addon2">bbl</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateOS2') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateOS2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateOS2']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('OS2comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('OS2comment',null, ['class' =>'form-control', 'id' => 'OS2comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Cumulative Gas Produced') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('OS3') ? 'has-error' : ''}}">
                           {!! Form::text('OS3',null, ['placeholder' => 'mMMSCF', 'class' =>'form-control', 'id' => 'OS3']) !!}
                           <span class="input-group-addon" id="basic-addon2">mMMSCF</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateOS3') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateOS3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateOS3']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('OS3comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('OS3comment',null, ['class' =>'form-control', 'id' => 'OS3comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('OS4') ? 'has-error' : ''}}">
                           {!! Form::text('OS4',null, ['placeholder' => 'Days', 'class' =>'form-control', 'id' => 'OS4']) !!}
                           <span class="input-group-addon" id="basic-addon2">Days</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateOS4') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateOS4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateOS4']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('OS4comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('OS4comment',null, ['class' =>'form-control', 'id' => 'OS4comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'De Boer Criteria') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('OS5') ? 'has-error' : ''}}">
                           {!! Form::text('OS5',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'OS5']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateOS5') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateOS5', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateOS5']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('OS5comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('OS5comment',null, ['class' =>'form-control', 'id' => 'OS5comment']) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="relative_permeability_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Relative Permeability</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('RP1') ? 'has-error' : ''}}">
                           {!! Form::text('RP1',null, ['placeholder' => 'days', 'class' =>'form-control', 'id' => 'RP1']) !!}
                           <span class="input-group-addon" id="basic-addon2">days</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateRP1') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateRP1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateRP1']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('RP1comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('RP1comment',null, ['class' =>'form-control', 'id' => 'RP1comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Difference between current reservoir pressure and saturation pressure') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('RP2') ? 'has-error' : ''}}">
                           {!! Form::text('RP2',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'RP2']) !!}
                           <span class="input-group-addon" id="basic-addon2">psi</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateRP2') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateRP2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateRP2']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('RP2comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('RP2comment',null, ['class' =>'form-control', 'id' => 'RP2comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Cumulative Water Produced') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('RP3') ? 'has-error' : ''}}">
                           {!! Form::text('RP3',null, ['placeholder' => 'MMbbl', 'class' =>'form-control', 'id' => 'RP3']) !!}
                           <span class="input-group-addon" id="basic-addon2">MMbbl</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateRP3') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateRP3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateRP3']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('RP3comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('RP3comment',null, ['class' =>'form-control', 'id' => 'RP3comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k))') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('RP4') ? 'has-error' : ''}}">
                           {!! Form::text('RP4',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'RP4']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateRP4') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateRP4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateRP4']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('RP4comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('RP4comment',null, ['class' =>'form-control', 'id' => 'RP4comment']) !!}
                     </div>
                  </div>
               </div>

               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Velocity parameter estimated from maximum critical velocity') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('RP5') ? 'has-error' : ''}}">
                           {!! Form::text('RP5',null, ['placeholder' => 'cc/min', 'class' =>'form-control', 'id' => 'RP5']) !!}
                           <span class="input-group-addon" id="basic-addon2">cc/min</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateRP5') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateRP5', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateRP5']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('RP4comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('RP5comment',null, ['class' =>'form-control', 'id' => 'RP5comment']) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="induce_damage_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Induced Damage</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('MDF', 'Gross Pay') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('ID1') ? 'has-error' : ''}}">
                           {!! Form::text('ID1',null, ['placeholder' => 'ft', 'class' =>'form-control pull-right', 'id' => 'ID1']) !!}
                           <span class="input-group-addon" id="basic-addon2">ft</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateID1') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateID1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateID1']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('ID1comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('ID1comment',null, ['class' =>'form-control', 'id' => 'ID1comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('PDF', 'Total polymer pumped during Hydraulic Fracturing') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('ID2') ? 'has-error' : ''}}">
                           {!! Form::text('ID2',null, ['placeholder' => 'lbs', 'class' =>'form-control pull-right', 'id' => 'ID2']) !!}
                           <span class="input-group-addon" id="basic-addon2">lbs</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateID2') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateID2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateID2']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('ID2comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('ID2comment',null, ['class' =>'form-control', 'id' => 'ID2comment']) !!}    
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('IFF', 'Total volume of water based fluids pumped into the well') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('ID3') ? 'has-error' : ''}}">
                           {!! Form::text('ID3',null, ['placeholder' => 'bbl', 'class' =>'form-control pull-right', 'id' => 'ID3']) !!}
                           <span class="input-group-addon" id="basic-addon2">bbl</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateID3') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateID3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateID3']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('ID3comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('ID3comment',null, ['class' =>'form-control', 'id' => 'ID3comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('ML', 'Mud Losses') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('ID4') ? 'has-error' : ''}}">
                           {!! Form::text('ID4',null, ['placeholder' => 'bbl', 'class' =>'form-control pull-right', 'id' => 'ID4']) !!}
                           <span class="input-group-addon" id="basic-addon2">bbl</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateID4') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateID4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateID4']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('ID4comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('ID4comment',null, ['class' =>'form-control', 'id' => 'ID4comment']) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="geomechanical_damage_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Geomechanical Damage</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('PNP', 'Fraction of Net Pay Exihibiting Natural Fractures') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('GD1') ? 'has-error' : ''}}">
                           {!! Form::text('GD1',null, ['placeholder' => 'fraction', 'class' =>'form-control pull-right', 'id' => 'GD1']) !!}
                           <span class="input-group-addon" id="basic-addon2">fraction</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateGD1') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateGD1', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateGD1']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('GD1comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('GD1comment',null, ['class' =>'form-control', 'id' => 'GD1comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('BHFP', 'Drawdown, i.e, reservoir pressure minus BHFP') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('GD2') ? 'has-error' : ''}}">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group">
                           {!! Form::text('GD2',null, ['placeholder' => 'psi', 'class' =>'form-control pull-right', 'id' => 'GD2']) !!}
                           <span class="input-group-addon" id="basic-addon2">psi</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateGD2') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateGD2', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateGD2']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('GD2comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('GD2comment',null, ['class' =>'form-control', 'id' => 'GD2comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('KH', 'Ratio of KH)matrix + fracture / KH)matrix') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('GD3') ? 'has-error' : ''}}">
                           {!! Form::text('GD3',null, ['placeholder' => '-', 'class' =>'form-control pull-right', 'id' => 'GD3']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateGD3') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateGD3', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateGD3']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('GD3comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('GD3comment',null, ['class' =>'form-control', 'id' => 'GD3comment']) !!}
                     </div>
                  </div>
               </div>
      
               <hr>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('KH', 'Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        {!! Form::label('value', 'Value') !!}
                        <div class="input-group {{$errors->has('GD4') ? 'has-error' : ''}}">
                           {!! Form::text('GD4',null, ['placeholder' => '-', 'class' =>'form-control pull-right', 'id' => 'GD4']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('dateGD4') ? 'has-error' : ''}}">
                        {!! Form::label('date', 'Monitoring Date') !!}
                        {!! Form::text('dateGD4', null, ['placeholder' => 'dd/mm/yyyy', 'class' =>'form-control jquery-datepicker', 'id' => 'dateGD4']); !!}
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group {{$errors->has('GD4comment') ? 'has-error' : ''}}">
                        {!! Form::label('comment', 'Comment') !!}
                        {!! Form::text('GD4comment',null, ['class' =>'form-control', 'id' => 'GD4comment']) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-12 scenario-buttons">
      <div align="left">
         {!! Form::submit('Save' , array('class' => 'btn btn-success', 'onclick' => 'verifyDamage();', 'name' => 'accion')) !!}
         <a href="{!! url('database') !!}" class="btn btn-danger">Cancel</a>
      </div>
      <div align="right">
         <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
         <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
      </div>
   </div>
</div>
{!! Form::Close() !!}

@endsection

@section('Scripts')
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
   @include('js/add_damage_variables')
   @include('js/frontend_validator')
   @include('js/frontend_rules/damage_variables')
   @include('js/modal_error')
   @include('js/modal_error_frontend')
   @include('css/modal_error_frontend')
@endsection