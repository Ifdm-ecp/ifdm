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
      <div class ="tab-content">
         <div class="tab-pane active" id="well_data_c">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Well Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Prod" class="panel-collapse collapse in">
                  {!!Form::open(array('route' => array('Desagregacion.update', (!is_null($duplicateFrom) ? $duplicateFrom : $disaggregation_scenario->id)), 'method' => 'PATCH'), array('role' => 'form'))!!}
                  <input type="hidden" name="scenario_id" id="scenario_id" value="{{ $scenario->id }}">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('well radius', 'Well Radius ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('wellR') ? 'has-error' : ''}}">
                                 {!! Form::text('radio_pozo',(float) $disaggregation_scenario->radio_pozo, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_pozo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('drainage radius', 'Reservoir Drainage Radius ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('drainageR') ? 'has-error' : ''}}">
                                 {!! Form::text('radio_drenaje_yac',(float) $disaggregation_scenario->radio_drenaje_pozo, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_drenaje_yac']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Reservoir pressure') ? 'has-error' : ''}}">
                              {!! Form::label('presion de yacimiento', 'Reservoir Pressure ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('presion_yacimiento',(float) $disaggregation_scenario->presion_reservorio, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_yacimiento']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Measured well depth') ? 'has-error' : ''}}">
                              {!! Form::label('profundidad medida del pozo', 'Measured Well Depth ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('profundidad_medida_pozo',(float) $disaggregation_scenario->profundidad_medida_pozo, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'profundidad_medida_pozo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('espesor_canoneado') ? 'has-error' : ''}}">
                              {!! Form::label('espesor canoneado', 'Perforated Thickness ', array('class' => 'required')) !!}
                              <div class="input-group ">
                                 {!! Form::text('espesor_canoneado',(float) $disaggregation_scenario->espesor_canoneado, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'espesor_canoneado']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('profundidad penetracion canones') ? 'has-error' : ''}}">
                              {!! Form::label('profundidad penetracion canones', 'Perforation Penetration Depth ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('profundidad_penetracion_canones',(float) $disaggregation_scenario->profundidad_penetracion_canones, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'profundidad_penetracion_canones']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Rock Type') ? 'has-error' : ''}}">
                              {!! Form::label('perforated cannoned phase angle', 'Perforating Phase Angle ', array('class' => 'required')) !!}
                              {!! Form::select('fase', array(0.0 => '0°', 45.0 => '45°', 60.0 => '60°', 90.0 => '90°', 120.0 => '120°', 360.0 => '360°'), $disaggregation_scenario->angulo_fase_canoneo_perforado, ['class' => 'form-control', 'id'=>'fase']) !!}
                              <!--</div>-->
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Perforating radius') ? 'has-error' : ''}}">
                              {!! Form::label('perforated radius', 'Perforating Radius ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('radio_perforado',(float) $disaggregation_scenario->radio_perforado, ['placeholder' => 'in', 'class' =>'form-control', 'id' => 'radio_perforado']) !!}
                                 <span class="input-group-addon" id="basic-addon2">in</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('real formation depth', 'True Vertical Depth ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('True vertical depth') ? 'has-error' : ''}}">
                                 {!! Form::text('profundidad_real_formacion',(float) $disaggregation_scenario->profundidad_real_formacion, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'profundidad_real_formacion']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('production formation thickness', 'Producing Formation Thickness ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('Production formation thickness') ? 'has-error' : ''}}">
                                 {!! Form::text('espesor_formacion_productora',(float) $disaggregation_scenario->espesor_formacion_productora, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'espesor_formacion_productora']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
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
                                          {!! Form::radio('forma_area_drenaje', '1', ($disaggregation_scenario->forma_area_drenaje == '1') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma1.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '2', ($disaggregation_scenario->forma_area_drenaje == '2') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma2.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '3', ($disaggregation_scenario->forma_area_drenaje == '3') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma3.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '4', ($disaggregation_scenario->forma_area_drenaje == '4') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma4.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '5', ($disaggregation_scenario->forma_area_drenaje == '5') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma5.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '6', ($disaggregation_scenario->forma_area_drenaje == '6') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma6.png') !!}" />
                                       </p>
                                    </div>
                                    <br />  
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '7', ($disaggregation_scenario->forma_area_drenaje == '7') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma7.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '8', ($disaggregation_scenario->forma_area_drenaje == '8') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma8.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '9', ($disaggregation_scenario->forma_area_drenaje == '9') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma9.png') !!}" />
                                       </p>
                                       <p class="col-sm-6" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '10', ($disaggregation_scenario->forma_area_drenaje == '10') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma10.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '11', ($disaggregation_scenario->forma_area_drenaje == '11') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma11.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '12', ($disaggregation_scenario->forma_area_drenaje == '12') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma12.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '13', ($disaggregation_scenario->forma_area_drenaje == '13') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma13.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '14', ($disaggregation_scenario->forma_area_drenaje == '14') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma14.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '15', ($disaggregation_scenario->forma_area_drenaje == '15') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma15.png') !!}" />
                                       </p>
                                    </div>
                                    <br />
                                    <div class="row">
                                       <p class="col-sm-12" style="text-align: center;">
                                          {!! Form::radio('forma_area_drenaje', '16', ($disaggregation_scenario->forma_area_drenaje == '16') ? true:false ) !!}
                                          <img src="{!! asset('images/drainage-shapes/desagregacion-forma16.png') !!}" />
                                       </p>
                                    </div>
                                 </div>
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
                              <div class="input-group {{$errors->has('Oil rate') ? 'has-error' : ''}}">
                                 {!! Form::text('tasa_flujo',(float) $disaggregation_scenario->caudal_produccion_aceite, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'tasa_flujo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('flowing pressure', 'Bottomhole Flowing Pressure ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('Bottomhole flowing pressure') ? 'has-error' : ''}}">
                                 {!! Form::text('presion_fondo',(float) $disaggregation_scenario->presion_fondo_pozo, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_fondo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('gas rate', 'Gas Rate ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('Gas rate') ? 'has-error' : ''}}">
                                 {!! Form::text('caudal_produccion_gas',(float) $disaggregation_scenario->caudal_produccion_gas, ['placeholder' => 'MMscf/d', 'class' =>'form-control', 'id' => 'caudal_produccion_gas']) !!}
                                 <span class="input-group-addon" id="basic-addon2">MMscf/d</span>
                              </div>
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
                              <div class="input-group {{$errors->has('Well total damage') ? 'has-error' : ''}}">
                                 {!! Form::text('dano_total_pozo',round((float) $disaggregation_scenario->dano_total,2), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'dano_total_pozo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                           </div>
                        </div>
                     </fieldset>
                  </div>
               </div>
            </div>
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
                           <div class="form-group {{$errors->has('permeabilidad absoluta inicial') ? 'has-error' : ''}}">
                              {!! Form::label('permeabilidad absoluta inicial', 'Permeability  ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('permeabilidad_abs_ini',(float) $disaggregation_scenario->permeabilidad_original, ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad_abs_ini']) !!}
                                 <span class="input-group-addon" id="basic-addon2">md</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Rock Type') ? 'has-error' : ''}}">
                              {!! Form::label('tipo de roca', 'Rock Type ', array('class' => 'required')) !!}
                              <select name="tipo_roca" class="form-control" id="tipo_roca">
                                 <option value="0"></option>
                                 <option value="consolidada" <?php if ($disaggregation_scenario->tipo_roca == "consolidada") echo "selected"; ?>>Consolidated</option>
                                 <option value="poco consolidada" <?php if ($disaggregation_scenario->tipo_roca == "poco consolidada") echo "selected"; ?>>Unconsolidated</option>
                                 <option value="microfracturada" <?php if ($disaggregation_scenario->tipo_roca == "microfracturada") echo "selected"; ?>>Microfractured</option>
                              </select>
                              <!--</div>-->
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Horizontal - Vertical permeability ratio') ? 'has-error' : ''}}">
                              {!! Form::label('relacion permeabilidad vertical horizontal', 'Horizontal - Vertical Permeability Ratio ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('relacion_perm_horiz_vert',(float) $disaggregation_scenario->relacion_perm_horiz_vert, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'relacion_perm_horiz_vert']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('porosity') ? 'has-error' : ''}}">
                              {!! Form::label('porosity_label', 'Porosity', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('porosity',(float) $disaggregation_scenario->porosidad, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'porosity']) !!}
                                 <span class="input-group-addon" id="basic-addon2">[0-1]</span>
                              </div>
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
                           <div class="form-group {{$errors->has('Oil viscosity') ? 'has-error' : ''}}">
                              {!! Form::label('viscosidad del aceite', 'Oil Viscosity ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('viscosidad_aceite',(float)$disaggregation_scenario->viscosidad_aceite, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'viscosidad_aceite']) !!}
                                 <span class="input-group-addon" id="basic-addon2">cp</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Gas viscosity') ? 'has-error' : ''}}">
                              {!! Form::label('viscosidad del gas', 'Gas Viscosity ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('viscosidad_gas',(float) $disaggregation_scenario->viscosidad_gas, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'viscosidad_gas']) !!}
                                 <span class="input-group-addon" id="basic-addon2">cp</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Specific gas gravity') ? 'has-error' : ''}}">
                              {!! Form::label('gravedad especifica del gas', 'Specific Gas Gravity ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('gravedad_especifica_gas',(float) $disaggregation_scenario->gravedad_especifica_gas, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'gravedad_especifica_gas']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('Volumetric oil factor') ? 'has-error' : ''}}">
                              {!! Form::label('volumetric oil factor', 'Oil Volumetric Factor ', array('class' => 'required')) !!}
                              <div class="input-group">
                                 {!! Form::text('factor_volumetrico_aceite',(float) $disaggregation_scenario->factor_volumetrico_aceite, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'factor_volumetrico_aceite']) !!}
                                 <span class="input-group-addon" id="basic-addon2">-</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
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
                              <div class="input-group {{$errors->has('Minimum horizontal stress gradient') ? 'has-error' : ''}}">
                                 {!! Form::text('gradiente_esfuerzo_horizontal_minimo',(float) $disaggregation_scenario->gradiente_esfuerzo_horizontal_minimo, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'gradiente_esfuerzo_horizontal_minimo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('maximum horizontal stress gradient', 'Maximum Horizontal Stress Gradient ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('Maximum horizontal stress gradient') ? 'has-error' : ''}}">
                                 {!! Form::text('gradiente_esfuerzo_horizontal_maximo',(float) $disaggregation_scenario->gradiente_esfuerzo_horizontal_maximo, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'gradiente_esfuerzo_horizontal_maximo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('vertical stress gradient', 'Vertical Stress Gradient ', array('class' => 'required')) !!}
                              <div class="input-group {{$errors->has('Vertical stress gradient') ? 'has-error' : ''}}">
                                 {!! Form::text('gradiente_esfuerzo_vertical',(float) $disaggregation_scenario->gradiente_esfuerzo_vertical, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'gradiente_esfuerzo_vertical']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Unids"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Hydraulic Units Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Unids" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-12">
                           <!-- Tabla de Excel -->
                           <div class="form-group">
                              {!! Form::label('hidraulic units data', 'Hydraulic Units Data ', array('class' => 'required')) !!}
                              <div class="col-md-12">
                                 <div tabindex="0" id="hidraulic_units_data" class="dataTable" style="overflow: auto; table-layout: auto; width: 100%; height: 150px; max-height: 150px;"></div>
                                 <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="For calculating the Hydraulic Units Data you'll need Permeability, Porosity, and Producing Formation Thickness Data." onclick="calculate_hydraulic_units_data()">Calculate Hydraulic Units Data</button> 
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
<!-- ***  -->

<div id="loading" style="display:none;"></div>
<div class="row">
   <div class="col-xs-12">
      {!! Form::submit('Save' , array('class' => 'btn btn-success', 'id' => 'btn_os', 'name' => 'btn_os', 'onclick' => 'enviar();')) !!}
      {!! Form::submit('Next' , array('class' => 'btn btn-primary pull-right', 'onclick' => 'enviar();')) !!}
      &nbsp;
      {!! Form::hidden('unidades_table', '', array('id' => 'unidades_table')) !!}
      {!! Form::Close() !!}
      {!!Form::open(array('url' => 'IPR/storeIPR', 'method' => 'post','style'=>'display:inline'))!!}
      {!! Form::hidden('inputskins', "back" , array('id' => 'inputskins')) !!}
      {!! Form::hidden('id', (float) $disaggregation_scenario->id , array('id' => 'id')) !!}

      &nbsp;
      {!! Form::Close() !!}
   </div>
</div>

@endsection
@section('Scripts')
@include('js/desagregacion_edit')
@include('css/desagregacion_edit')
@endsection