@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')
@section('content')
<?php  
   if(!isset($_SESSION)) {
     session_start();
   }
   ?>
@if (session()->has('mensaje'))
<div id="myModal" class="modal fade">
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
@if (session()->has('validacion'))
<div id="myModal_val" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
            <p>Error in the input Data or skin value</p>
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
            </small></p>
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
<div id="sticky">
   <center>Scenario: {!! $scenario->nombre !!} </br> 
    Basin: {!! $scenario->cuenca->nombre !!} - 
    Field: {!! $scenario->campo->nombre !!} - 
    Producing interval: {!!  $scenario->formacionxpozo->nombre !!} - 
    Well: {!!  $scenario->pozo->nombre !!}</br> 
    User: {!! $scenario->user->fullName !!}</center>
</div>
</br>

<!-- ***  -->
<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
          <li class="active"><a data-toggle="tab" href="#well_data_c" id="well_data">Well Data</a></li>
          <li><a data-toggle="tab" href="#rock_properties_c" id="rock_properties">Reservoir Data</a></li>
          <li><a data-toggle="tab" href="#stress_gradients_c" id="stress_gradients">Stress Gradients Data</a></li>
      </ul>
      <div class="tab-content">
         <div class="tab-pane active" id="well_data_c">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Well Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Prod" class="panel-collapse collapse in">
                     {!!Form::open(array('url' => 'Desagregacion/store', 'method' => 'post','style'=>'display:inline'))!!}
                     <input type="hidden" name="scenario_id" id="scenario_id" value="{{ $scenario->id }}">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('well Radius', 'Well Radius ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('radio_pozo') ? 'has-error' : ''}}">
                                 {!! Form::number('radio_pozo', null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_pozo', 'min' => '0', 'step' => '0.01']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('radio_pozo', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('drainage radius', 'Reservoir Drainage Radius ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('radio_drenaje_yac') ? 'has-error' : ''}}">
                                 {!! Form::number('radio_drenaje_yac', null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_drenaje_yac']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('radio_drenaje_yac', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('presion_yacimiento') ? 'has-error' : ''}}">
                              {!! Form::label('presion de yacimiento', 'Reservoir Pressure ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('presion_yacimiento', null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_yacimiento']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
                              </div>
                              {!! $errors->first('presion_yacimiento', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('profundidad_medida_pozo') ? 'has-error' : ''}}">
                              {!! Form::label('profundidad medida del pozo', 'Measured Well Depth ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('profundidad_medida_pozo',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'profundidad_medida_pozo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('profundidad_medida_pozo', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('espesor_canoneado') ? 'has-error' : ''}}">
                              {!! Form::label('espesor canoneado', 'Perforated Thickness', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('espesor_canoneado',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'espesor_canoneado']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('espesor_canoneado', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('profundidad_penetracion_canones') ? 'has-error' : ''}}">
                              {!! Form::label('profundidad penetracion canones', 'Perforation Penetration Depth ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('profundidad_penetracion_canones',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'profundidad_penetracion_canones', 'min' => '0', 'step' => '0.01']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('profundidad_penetracion_canones', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('fase') ? 'has-error' : ''}}">
                              {!! Form::label('perforated cannoned phase angle', 'Perforating Phase Angle ', array('class' => 'required')) !!}
                              {!! Form::select('fase', array(0.0 => '0°', 45.0 => '45°', 60.0 => '60°', 90.0 => '90°', 120.0 => '120°', 360.0 => '360°'), 'S', ['class' => 'form-control', 'id'=>'fase']) !!}
                              <!--</div>-->
                           </div>
                           {!! $errors->first('fase', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('radio_perforado') ? 'has-error' : ''}}">
                              {!! Form::label('perforated radius', 'Perforating Radius ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('radio_perforado',null, ['placeholder' => 'in', 'class' =>'form-control', 'id' => 'radio_perforado', 'min' => '0', 'step' => '0.01']) !!}
                                 <span class="input-group-addon" id="basic-addon2">in</span>
                              </div>
                              {!! $errors->first('radio_perforado', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('real formation depth', 'True Vertical Depth ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('profundidad_real_formacion') ? 'has-error' : ''}}">
                                 {!! Form::number('profundidad_real_formacion',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'profundidad_real_formacion']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('profundidad_real_formacion', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('production formation thickness', 'Production Formation Thickness ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('espesor_formacion_productora') ? 'has-error' : ''}}">
                                 {!! Form::number('espesor_formacion_productora',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'espesor_formacion_productora'], ) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                              {!! $errors->first('espesor_formacion_productora', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <!-- Ingreso forma de área de drenaje -->
                           <div class="form-group">
                              {!! Form::label('drainage area Shape', 'Drainage Area Shape ', array('class' => 'required')) !!}
                              <br>
                              <div class="col-sm-12">
                                 <div style="height: 150px; overflow-y: scroll; overflow-x: hidden; border: 2px solid rgb(225,225,225);">
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '1', true) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma1.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '2', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma2.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '3', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma3.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '4', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma4.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '5', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma5.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '6', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma6.png') !!}" />
                                       </p>
                                    </div>
                                    <br />  
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '7', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma7.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '8', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma8.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '9', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma9.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '10', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma10.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '11', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma11.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '12', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma12.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '13', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma13.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '14', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma14.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '15', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma15.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '16', false) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma16.png') !!}" />
                                       </p>
                                    </div>
                                 </div>
                                 {!! $errors->first('forma_area_drenaje', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Product"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Product" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('oil rate', 'Oil Rate ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('tasa_flujo') ? 'has-error' : ''}}">
                                 {!! Form::number('tasa_flujo',null, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'tasa_flujo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                              </div>
                              {!! $errors->first('tasa_flujo', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('flowing pressure', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('presion_fondo') ? 'has-error' : ''}}">
                                 {!! Form::number('presion_fondo', null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_fondo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
                              </div>
                              {!! $errors->first('presion_fondo', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('gas rate', 'Gas Rate ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('caudal_produccion_gas') ? 'has-error' : ''}}">
                                 {!! Form::number('caudal_produccion_gas',null, ['placeholder' => 'MMscf/d', 'class' =>'form-control', 'id' => 'caudal_produccion_gas']) !!}
                                 <span class="input-group-addon" id="basic-addon2">MMscf/d</span>
                              </div>
                              {!! $errors->first('caudal_produccion_gas', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Dam"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Damage</h4>
               </div>
               <div class="panel-body">
                  <div id="Dam" class="panel-collapse collapse in">
                     <fieldset>
                        <div class="col-md-6 input_skin">
                           <div class="form-group {{$errors->has('Skin') ? 'has-error' : ''}}">
                              {!! Form::label('well total damage', 'Skin', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('dano_total_pozo') ? 'has-error' : ''}}">
                                 {!! Form::number('dano_total_pozo',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'dano_total_pozo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                              {!! $errors->first('dano_total_pozo', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </fieldset>
                  </div>
               </div>
            </div>
            {!! Form::submit('Save' , array('class' => 'btn btn-success', 'id' => 'btn_os', 'name' => 'btn_os', 'onclick' => 'enviar();')) !!}
            <a class="btn btn-primary pull-right btnNext" >Next</a>
         </div>
         <div class="tab-pane" id="rock_properties_c">
            <div class="panel panel-default {{$errors->has('MSFormation') ? 'has-error' : ''}}">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#BasicP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Basic Petrophysics</h4>
               </div>
               <div class="panel-body">
                  <div id="BasicP" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('permeabilidad_abs_ini') ? 'has-error' : ''}}">
                              {!! Form::label('permeabilidad absoluta inicial', 'Permeability  ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('permeabilidad_abs_ini', null, ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad_abs_ini']) !!}
                                 <span class="input-group-addon" id="basic-addon2">md</span>
                              </div>
                              {!! $errors->first('permeabilidad_abs_ini', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('tipo_roca') ? 'has-error' : ''}}">
                              {!! Form::label('tipo de roca', 'Rock Type ', array('class' => 'required')) !!}
                              <select name="tipo_roca" class="form-control" id="tipo_roca">
                                 <option value="0"></option>
                                 @if(session('tipo_roca') == 'consolidada')
                                 <option value="consolidada" selected>Consolidated</option>
                                 @else
                                 <option value="consolidada">Consolidated</option>
                                 @endif
                                 @if(session('tipo_roca') == 'poco consolidada')
                                 <option value="poco consolidada" selected>Unconsolidated</option>
                                 @else
                                 <option value="poco consolidada" >Unconsolidated</option>
                                 @endif
                                 @if(session('tipo_roca')=='microfracturada')
                                 <option value="microfracturada" selected>Microfractured</option>
                                 @else
                                 <option value="microfracturada">Microfractured</option>
                                 @endif
                              </select>
                              <!--</div>-->
                           </div>
                           {!! $errors->first('tipo_roca', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('relacion_perm_horiz_vert') ? 'has-error' : ''}}">
                              {!! Form::label('relacion permeabilidad vertical horizontal', 'Horizontal - Vertical Permeability Ratio ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('relacion_perm_horiz_vert',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'relacion_perm_horiz_vert']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                              {!! $errors->first('relacion_perm_horiz_vert', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('porosity') ? 'has-error' : ''}}">
                              {!! Form::label('porosity_label', 'Porosity', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('porosity', null, ['placeholder' =>  '-', 'class' =>'form-control', 'id' => 'porosity', 'min' => '0', 'max' => '1', 'step' => '0.01']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                              </div>
                              {!! $errors->first('porosity', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#MP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fluid Properties</h4>
               </div>
               <div class="panel-body">
                  <div id="MP" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('viscosidad_aceite') ? 'has-error' : ''}}">
                              {!! Form::label('viscosidad del aceite', 'Oil Viscosity ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('viscosidad_aceite',null, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'viscosidad_aceite', 'min' => '0', 'step' => '0.00001']) !!}
                                 <span class="input-group-addon" id="basic-addon2">cp</span>
                              </div>
                              {!! $errors->first('viscosidad_aceite', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('viscosidad_gas') ? 'has-error' : ''}}">
                              {!! Form::label('viscosidad del gas', 'Gas Viscosity ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('viscosidad_gas',null, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'viscosidad_gas', 'min' => '0', 'step' => '0.00001']) !!}
                                 <span class="input-group-addon" id="basic-addon2">cp</span>
                              </div>
                              {!! $errors->first('viscosidad_gas', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('gravedad_especifica_gas') ? 'has-error' : ''}}">
                              {!! Form::label('gravedad especifica del gas', 'Specific Gas Gravity ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('gravedad_especifica_gas',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'gravedad_especifica_gas', 'min' => '0', 'step' => '0.00001']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                              {!! $errors->first('gravedad_especifica_gas', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('factor_volumetrico_aceite') ? 'has-error' : ''}}">
                              {!! Form::label('volumetric oil factor', 'Oil Volumetric Factor ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::number('factor_volumetrico_aceite',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'factor_volumetrico_aceite',  'min' => '0', 'step' => '0.00001']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                              {!! $errors->first('factor_volumetrico_aceite', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            {!! Form::submit('Save' , array('class' => 'btn btn-success', 'id' => 'btn_os', 'name' => 'btn_os', 'onclick' => 'enviar();')) !!}
            <a class="btn btn-primary pull-right btnNext" >Next</a>
            <a class="btn btn-primary pull-right btnPrevious" style="margin-right: 15px;">Previous</a>
         </div>
         <div class="tab-pane" id="stress_gradients_c">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Grad"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Stress Gradients</h4>
               </div>
               <div class="panel-body">
                  <div id="Grad" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('minimun horizontal stress gradient', 'Minimum Horizontal Stress Gradient ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('gradiente_esfuerzo_horizontal_minimo') ? 'has-error' : ''}}">
                                 {!! Form::number('gradiente_esfuerzo_horizontal_minimo',null, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'gradiente_esfuerzo_horizontal_minimo',  'min' => '0', 'step' => '0.01']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                              </div>
                              {!! $errors->first('gradiente_esfuerzo_horizontal_minimo', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('maximum horizontal stress gradient', 'Maximum horizontal stress gradient ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('gradiente_esfuerzo_horizontal_maximo') ? 'has-error' : ''}}">
                                 {!! Form::number('gradiente_esfuerzo_horizontal_maximo',null, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'gradiente_esfuerzo_horizontal_maximo', 'min' => '0', 'step' => '0.01']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                              </div>
                              {!! $errors->first('gradiente_esfuerzo_horizontal_maximo', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('vertical stress gradient', 'Vertical Stress Gradient ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('gradiente_esfuerzo_vertical') ? 'has-error' : ''}}">
                                 {!! Form::number('gradiente_esfuerzo_vertical',null, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'gradiente_esfuerzo_vertical',  'min' => '0', 'step' => '0.01']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                              </div>
                              {!! $errors->first('gradiente_esfuerzo_vertical', '<p class="help-block" style="font-size: 11px; color: #ba6063">:message</p>') !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Unids"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right" ></span></a> Hydraulic Units Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Unids" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-12">
                           <!-- Tabla de Excel -->
                           <div class="form-group">
                              {!! Form::label('hidraulic units data', 'Hydraulic Units Data ', array('class' => 'required')) !!}
                              <div class="col-md-12">
                                 <div tabindex="0" id="hidraulic_units_data" class="dataTable" style="overflow: auto;  height: 150px; max-height: 150px; table-layout: auto; width: 100%;">
                                 </div>
                                 <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="For calculating the Hydraulic Units Data you'll need Permeability, Porosity, and Producing Formation Thickness Data." onclick="calculate_hydraulic_units_data()">Calculate Hydraulic Units Data</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="row">
               <div class="col-xs-12">
                 {!! Form::submit('Save' , array('class' => 'btn btn-success', 'id' => 'btn_os', 'name' => 'btn_os', 'onclick' => 'enviar();')) !!}
                  {!! Form::submit('Next' , array('class' => 'btn btn-primary pull-right', 'onclick' => 'enviar();')) !!}
                  <a class="btn btn-primary pull-right btnPrevious" style="margin-right: 15px;">Previous</a>
                  <div id="loading" style="display:none;"></div>
                  &nbsp;
                  {!! Form::hidden('unidades_table', '', array('id' => 'unidades_table')) !!}
                  {!! Form::Close() !!}
                  {!!Form::open(array('url' => 'IPR/storeIPR', 'method' => 'post','style'=>'display:inline'))!!}
                  {!! Form::hidden('inputskins', "back" , array('id' => 'inputskins')) !!}
                  &nbsp;
                  {!! Form::Close() !!}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- ***  -->


@endsection
@section('Scripts')
@include('js/desagregacion')
@include('css/desagregacion')
@endsection