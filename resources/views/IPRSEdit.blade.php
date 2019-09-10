@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')
@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
   ?>
@if (session()->has('mensaje') && false)
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
<script>
   var curr_hot = null;
</script>
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
<div>
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
   <center>Scenario: {!! $scenary_s->nombre !!} </br> Basin: {!! $_SESSION['basin'] !!} - Field: {!! $_SESSION['field'] !!} - Producing interval: {!!  $intervalo->nombre !!} - Well: {!!  $_SESSION['well'] !!} </br> User: {!! $user->fullName !!} </center>
</div>
<p></p>
</br>
<!-- ***  -->
@include('layouts/general_advisor')
<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#well_data_c" id="well_data">Well Data</a></li>
         <li><a data-toggle="tab" href="#production_data_c" id="production_data">Production Data</a></li>
         <li><a data-toggle="tab" href="#rock_properties_c" id="rock_properties">Rock Properties</a></li>
         <li><a data-toggle="tab" href="#fluid_properties_c" id="fluid_properties">Fluid Properties</a></li>
      </ul>
      <div class="tab-content">
         <div class="tab-pane active" id="well_data_c">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Well Data</h4>
               </div>
               <div class="panel-body">
                  <div id="Prod" class="panel-collapse collapse in">
                     {!!Form::open(array('route' => array('IPR.update', (!is_null($duplicateFrom) ? $duplicateFrom : $IPR->id_escenario)), 'method' => 'put', 'role' => 'form'))!!}
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group {{$errors->has('fluido') ? 'has-error' : ''}}">
                              {!! Form::label('fluido', 'Fluid ') !!}<span style='color:red;'>*</span>
                              <select name="fluido" onchange="defineView(this)" class="form-control" id="fluido">
                                 @if($IPR->fluido == '1')
                                 <option value="1" selected>Black Oil</option>
                                 @else
                                 <option value="1">Black Oil</option>
                                 @endif
                                 @if($IPR->fluido == '2')
                                 <option value="2" selected>Dry Gas</option>
                                 @else
                                 <option value="2" >Dry Gas</option>
                                 @endif
                                 @if($IPR->fluido == '3')
                                 <option value="3" selected>Condensate Gas</option>
                                 @else
                                 <option value="3" >Condensate Gas</option>
                                 @endif
                              </select>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('well radius', 'Well Radius') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('radio_pozo') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                 @endif
                                 {!! Form::text('radio_pozo',(is_null($IPR->radio_pozo) ? "" : round($IPR->radio_pozo, 5)), ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_pozo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('drainage radius', 'Reservoir Drainage Radius') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('radio_drenaje_yac') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                 @endif
                                 {!! Form::text('radio_drenaje_yac',(is_null($IPR->radio_drenaje_yac) ? "" : round($IPR->radio_drenaje_yac, 5)), ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_drenaje_yac']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('presion de yacimiento', 'Reservoir Pressure') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('presion_yacimiento') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                 @endif
                                 {!! Form::text('presion_yacimiento',(is_null($IPR->presion_yacimiento) ? "" : round($IPR->presion_yacimiento, 5)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_yacimiento']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="production_data_c">
            <div class="oil_data">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#production_data_g"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production Data @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-panel-advisor" id="panel_production_data" style="color:black;font-size:15pt;"></i></span>@endif</h4>
                  </div>
                  <div class="panel-body">
                     <div id="production_data_g" class="panel-collapse collapse in">
                        <div class="row test_point_data">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('oil rate', 'Oil Rate', ['id'=>'labelSelect']) !!} <span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('tasa_flujo') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('tasa_flujo',(is_null($IPR->tasa_flujo) ? "" : round($IPR->tasa_flujo, 5)), ['placeholder' => 'bbl/day', 'class' =>'form-control', 'id' => 'tasa_flujo']) !!}
                                    <span class="input-group-addon" id="basic-addon2">bbl/day</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('flowing pressure', 'BHP ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('presion_fondo') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('presion_fondo',(is_null($IPR->presion_fondo) ? "" : round($IPR->presion_fondo, 5)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_fondo']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('bsw', 'BSW') !!} <span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('bsw') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('bsw',(is_null($IPR->bsw) ? "" : round($IPR->bsw, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'bsw']) !!}
                                    <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6" style="display:none;">
                              <div class="form-group">
                                 {!! Form::label('gor', 'GOR ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('gor') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('gor',(is_null($IPR->gor) ? "" : round($IPR->gor, 5)), ['placeholder' => 'SCF/STB', 'class' =>'form-control', 'id' => 'gor']) !!}
                                    <span class="input-group-addon" id="basic-addon2">SCF/STB</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="gas_data" style="display:none;">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#Product"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production Data</h4>
                  </div>
                  <div class="panel-body">
                     <div id="Product" class="panel-collapse collapse in">
                        <div class="row test_point_data_g">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('gas rate', 'Gas Rate', ['id'=>'labelSelect']) !!} <span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('gas_rate_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('gas_rate_g',(is_null($IPR->gas_rate_g) ? "" : round($IPR->gas_rate_g, 5)), ['placeholder' => 'MMscf/day', 'class' =>'form-control', 'id' => 'gas_rate_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">MMscf/day</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('bjp', 'BHP ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('bhp_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('bhp_g',(is_null($IPR->bhp_g) ? "" : round($IPR->bhp_g, 5)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'bhp_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="condensate_gas_data" style="display:none;">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#production_data_panel_c_g"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production Data </h4>
                  </div>
                  <div class="panel-body">
                     <div class="panel-collapse collapse in" id="production_data_panel_c_g">
                        <div class="row test_point_data_g">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('gas rate', 'Gas Rate', ['id' => 'labelSelect']) !!} <span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('gas_rate_c_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('gas_rate_c_g',(is_null($IPR->gas_rate_c_g) ? "" : round($IPR->gas_rate_c_g, 5)), ['placeholder' => 'MMscf/day', 'class' =>'form-control', 'id' => 'gas_rate_c_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">MMscf/day</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('bhp', 'BHP ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('bhp_c_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('bhp_c_g',(is_null($IPR->bhp_c_g) ? "" : round($IPR->bhp_c_g, 5)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'bhp_c_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="rock_properties_c">
            <div class="oil_data">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#Fluid"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Rock Properties</h4>
                  </div>
                  <div class="panel-body">
                     <div id="Fluid" class="panel-collapse collapse in">
                        <br>
                        <div class="panel panel-default {{$errors->has('MSFormation') ? 'has-error' : ''}}">
                           <div class="panel-heading">
                              <h4><a data-parent="#accordion" data-toggle="collapse" href="#BasicP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Basic Petrophysics</h4>
                           </div>
                           <div class="panel-body">
                              <div id="BasicP" class="panel-collapse collapse in">
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          {!! Form::label('presion inicial', ' Initial Reservoir Pressure ') !!}<span style='color:red;'>*</span>
                                          <div class="input-group  {{$errors->has('presion_inicial') ? 'has-error' : ''}}">
                                             @if($advisor === "true")
                                                <span class="input-group-btn">
                                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                </span>
                                             @endif
                                             {!! Form::text('presion_inicial',(is_null($IPR->presion_inicial) ? "" : round($IPR->presion_inicial, 5)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_inicial']) !!}
                                             <span class="input-group-addon" id="basic-addon2">psi</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          {!! Form::label('permeabilidad absoluta inicial', 'Absolute Permeability At Initial Reservoir Pressure ') !!}<span style='color:red;'>*</span>
                                          <div class="input-group {{$errors->has('permeabilidad_abs_ini') ? 'has-error' : ''}}">
                                             @if($advisor === "true")
                                                <span class="input-group-btn">
                                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                </span>
                                             @endif
                                             {!! Form::text('permeabilidad_abs_ini',(is_null($IPR->permeabilidad_abs_ini) ? "" : round($IPR->permeabilidad_abs_ini, 5)), ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad_abs_ini']) !!}
                                             <span class="input-group-addon" id="basic-addon2">md</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          {!! Form::label('espesor del reservorio', 'Net Pay ') !!}<span style='color:red;'>*</span>
                                          <div class="input-group {{$errors->has('espesor_reservorio') ? 'has-error' : ''}}">
                                             @if($advisor === "true")
                                                <span class="input-group-btn">
                                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                </span>
                                             @endif
                                             {!! Form::text('espesor_reservorio',(is_null($IPR->espesor_reservorio) ? "" : round($IPR->espesor_reservorio, 5)), ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'espesor_reservorio']) !!}
                                             <span class="input-group-addon" id="basic-addon2">ft</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                 </div>
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_use_permeability_module_o', 'check_use_permeability_module_o', false, ["id" => "check_use_permeability_module_o"]) !!}
                                       <font size=3><b>Use Permeability Module</b></font>
                                       <input type="hidden" name="check_rock_oil" id="check_rock_oil">
                                    </legend>
                                    <div class="check_use_permeability_o" style="display:none;">
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             {!! Form::label('modulo permeabilidad', 'Permeability Module') !!} <span style='color:red;'>*</span>
                                             <div class="input-group {{$errors->has('modulo_permeabilidad') ? 'has-error' : ''}}">
                                                @if($advisor === "true")
                                                   <span class="input-group-btn">
                                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                   </span>
                                                @endif
                                                {!! Form::text('modulo_permeabilidad',(is_null($IPR->modulo_permeabilidad) ? "" : round($IPR->modulo_permeabilidad, 7)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'modulo_permeabilidad']) !!}
                                                <span class="input-group-addon" id="basic-addon2">psi<sup>-1</sup></span>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_calculate_permeability_module_o', 'check_calculate_permeability_module_o', false, ["id" => "check_calculate_permeability_module_o"]) !!}
                                       <font size=3><b>Calculate Permeability Module @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-panel-advisor" id="panel_calculate_permeability_module" style="color:black;font-size:15pt;"></i></span>@endif</b></font>
                                    </legend>
                                    <div class="check_calculate_permeability_o" style="display:none;">
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                {!! Form::label('permeabilidad', 'Absolute Permeability ') !!}<span style='color:red;'>*</span>
                                                <div class="input-group {{$errors->has('permeabilidad') ? 'has-error' : ''}}">
                                                   @if($advisor === "true")
                                                      <span class="input-group-btn">
                                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                      </span>
                                                   @endif
                                                   {!! Form::text('permeabilidad',(is_null($IPR->permeabilidad) ? "" : round($IPR->permeabilidad, 5)), ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad']) !!}
                                                   <span class="input-group-addon" id="basic-addon2">md</span>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group {{$errors->has('Porosidad') ? 'has-error' : ''}}">
                                                {!! Form::label('porosidad', ' Porosity') !!} <span style='color:red;'>*</span>
                                                <div class="input-group">
                                                   @if($advisor === "true")
                                                      <span class="input-group-btn">
                                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                      </span>
                                                   @endif
                                                   {!! Form::text('porosidad',(is_null($IPR->porosidad) ? "" : round($IPR->porosidad, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'porosidad']) !!}
                                                   <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group {{$errors->has('tipo_roca') ? 'has-error' : ''}}">
                                                {!! Form::label('tipo de roca', 'Rock Type') !!} <span style='color:red;'>*</span>
                                                <select name="tipo_roca" class="form-control" id="tipo_roca">
                                                   <option value="0"></option>
                                                   @if($IPR->tipo_roca == '1')
                                                   <option value="1" selected>Consolidated</option>
                                                   @else
                                                   <option value="1">Consolidated</option>
                                                   @endif
                                                   @if($IPR->tipo_roca == '2')
                                                   <option value="2" selected>Unconsolidated</option>
                                                   @else
                                                   <option value="2" >Unconsolidated</option>
                                                   @endif
                                                   @if($IPR->tipo_roca == '3')
                                                   <option value="3" selected>Microfractured</option>
                                                   @else
                                                   <option value="3">Microfractured</option>
                                                   @endif
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                        <br>
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4><a data-parent="#accordion" data-toggle="collapse" href="#Permeability_Relative"><span class="chevron_toggleable1 glyphicon glyphicon-chevron-down pull-right"></span></a> Relative Permeability Data Selection</h4>
                           </div>
                           <div class="panel-body">
                              <div id="Permeability_Relative" class="panel-collapse collapse in">
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_tabular_rel_perm_o', 'check_tabular_rel_perm_o', false, ["id" => "check_tabular_rel_perm_o"]) !!}
                                       <font size=3><b>Tabular</b></font>
                                       <input type="hidden" name="check_rock2_oil" id="check_rock2_oil">
                                    </legend>
                                    <div class="use_permeability_tables_o" style="display:none;">
                                       <div class="row">
                                          <div class="col-md-6" id="row_wateroil">
                                             <fieldset>
                                                <legend id="legend_wateroil">Water-Oil</legend>
                                                <div id="excelwateroil" class="handsontable"></div>
                                             </fieldset>
                                             <div class="row">
                                                <div class="col-md-6">
                                                   <button class="btn btn-primary pull-right" onclick="plot_waterOil()">Plot</button>  
                                                </div>
                                             </div>
                                             <div class="row">
                                                   <div id="waterOil_g"></div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <fieldset>
                                                <legend id="legend_gasliquid">Gas-Oil</legend>
                                                <div id="excelgasliquid" class="handsontable"></div>
                                                {!! Form::hidden('flag_perm_oil', 'secret', array('id' => 'flag_perm_oil')) !!}
                                             </fieldset>
                                             <div class="row">
                                                <div class="col-md-6">
                                                   <button class="btn btn-primary pull-right" onclick="plot_gasOil()">Plot</button>  
                                                </div>
                                             </div>
                                             <div class="row">
                                                   <div id="gasOil_g"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_corey_rel_perm_o', 'check_corey_rel_perm_o', false, ["id" => "check_corey_rel_perm_o"]) !!}
                                       <font size=3><b>Corey's Model</b></font>
                                    </legend>
                                    <div class="use_corey_model_o" style="display:none;">
                                       <div class="panel panel-default">
                                          <div class="panel-heading">
                                             <h4><a data-parent="#accordion" data-toggle="collapse" href="#Kro"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Gas/Oil End-Point Parameters</h4>
                                          </div>
                                          <div class="panel-body">
                                             <div id="Kro" class="panel-collapse collapse in">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      {!! Form::label('end_point_kr_aceite_gas', 'Kro (Sgc)') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('end_point_kr_aceite_gas') ? 'has-error' : ''}}">
                                                         @if($advisor === "true")
                                                            <span class="input-group-btn">
                                                               <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                            </span>
                                                         @endif
                                                         {!! Form::text('end_point_kr_aceite_gas',(is_null($IPR->end_point_kr_aceite_gas) ? "" : round($IPR->end_point_kr_aceite_gas, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'end_point_kr_aceite_gas']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      {!! Form::label('saturacion_gas_crit', 'Sgc') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('saturacion_gas_crit') ? 'has-error' : ''}}">
                                                         @if($advisor === "true")
                                                            <span class="input-group-btn">
                                                               <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                            </span>
                                                         @endif
                                                         {!! Form::text('saturacion_gas_crit',(is_null($IPR->saturacion_gas_crit) ? "" : round($IPR->saturacion_gas_crit, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'saturacion_gas_crit']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('end_point_kr_gas', 'Krg (Sorg)') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('end_point_kr_gas') ? 'has-error' : ''}}">
                                                            @if($advisor === "true")
                                                               <span class="input-group-btn">
                                                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                               </span>
                                                            @endif
                                                            {!! Form::text('end_point_kr_gas',(is_null($IPR->end_point_kr_gas) ? "" : round($IPR->end_point_kr_gas, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'end_point_kr_gas']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('saturacion_aceite_irred_gas', 'Sorg') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('saturacion_aceite_irred_gas') ? 'has-error' : ''}}">
                                                            @if($advisor === "true")
                                                               <span class="input-group-btn">
                                                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                               </span>
                                                            @endif
                                                            {!! Form::text('saturacion_aceite_irred_gas',(is_null($IPR->saturacion_aceite_irred_gas) ? "" : round($IPR->saturacion_aceite_irred_gas, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'saturacion_aceite_irred_gas']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group {{$errors->has('exponente_corey_aceite_gas') ? 'has-error' : ''}}">
                                                         {!! Form::label('exponente_corey_aceite_gas', 'Corey Exponent Oil/Gas') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('exponente_corey_aceite_gas') ? 'has-error' : ''}}">
                                                            @if($advisor === "true")
                                                               <span class="input-group-btn">
                                                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                               </span>
                                                            @endif
                                                            {!! Form::text('exponente_corey_aceite_gas',(is_null($IPR->exponente_corey_aceite_gas) ? "" : round($IPR->exponente_corey_aceite_gas, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'exponente_corey_aceite_gas']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      {!! Form::label('exponente_corey_gas', 'Corey Exponent Gas ') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('exponente_corey_gas') ? 'has-error' : ''}}">
                                                         @if($advisor === "true")
                                                            <span class="input-group-btn">
                                                               <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                            </span>
                                                         @endif
                                                         {!! Form::text('exponente_corey_gas',(is_null($IPR->exponente_corey_gas) ? "" : round($IPR->exponente_corey_gas, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'exponente_corey_gas']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                   <button class="btn btn-primary pull-right" onclick="plot_corey_gasOil()">Plot</button>  
                                                </div>
                                             </div>
                                             <div class="row">
                                                   <div id="corey_gasOil_g"></div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="panel panel-default" id="krw">
                                          <div class="panel-heading">
                                             <h4><a data-parent="#accordion" data-toggle="collapse" href="#Krw"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Oil/Water End-Point Parameters</h4>
                                          </div>
                                          <div class="panel-body">
                                             <div id="Krw" class="panel-collapse collapse in">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      {!! Form::label('end point', 'Kro (Swi)') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('end_point_kr_petroleo') ? 'has-error' : ''}}">
                                                         @if($advisor === "true")
                                                            <span class="input-group-btn">
                                                               <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                            </span>
                                                         @endif
                                                         {!! Form::text('end_point_kr_petroleo',(is_null($IPR->end_point_kr_petroleo) ? "" : round($IPR->end_point_kr_petroleo, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'end_point_kr_petroleo']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <div class="form-group {{$errors->has('saturacion_agua_irred') ? 'has-error' : ''}}">
                                                         {!! Form::label('saturacion_agua_irred', 'Swi') !!} <span style='color:red;'>*</span>
                                                         <div class="input-group">
                                                            @if($advisor === "true")
                                                               <span class="input-group-btn">
                                                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                               </span>
                                                            @endif
                                                            {!! Form::text('saturacion_agua_irred',(is_null($IPR->saturacion_agua_irred) ? "" : round($IPR->saturacion_agua_irred, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'saturacion_agua_irred']) !!} 
                                                            <span class="input-group-addon" id="basic-addon2">Fraction</span> 
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('end_point_kr_agua', 'Krw (Sor)') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('end_point_kr_agua') ? 'has-error' : ''}}">
                                                            @if($advisor === "true")
                                                               <span class="input-group-btn">
                                                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                               </span>
                                                            @endif
                                                            {!! Form::text('end_point_kr_agua',(is_null($IPR->end_point_kr_agua) ? "" : round($IPR->end_point_kr_agua, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'end_point_kr_agua']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('saturacion de aceite irreducible', 'Sor') !!} <span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('saturacion_aceite_irred') ? 'has-error' : ''}}">
                                                            @if($advisor === "true")
                                                               <span class="input-group-btn">
                                                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                               </span>
                                                            @endif
                                                            {!! Form::text('saturacion_aceite_irred',(is_null($IPR->saturacion_aceite_irred) ? "" : round($IPR->saturacion_aceite_irred, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'saturacion_aceite_irred']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group {{$errors->has('exponente_corey_petroleo') ? 'has-error' : ''}}">
                                                         {!! Form::label('exponente_corey_petroleo', 'Corey Exponent Oil ') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('Corey Exponent ') ? 'has-error' : ''}}">
                                                            @if($advisor === "true")
                                                               <span class="input-group-btn">
                                                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                               </span>
                                                            @endif
                                                            {!! Form::text('exponente_corey_petroleo',(is_null($IPR->exponente_corey_petroleo) ? "" : round($IPR->exponente_corey_petroleo, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'exponente_corey_petroleo']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      {!! Form::label('exponente_corey_agua', 'Corey Exponent Water ') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('exponente_corey_agua') ? 'has-error' : ''}}">
                                                         @if($advisor === "true")
                                                            <span class="input-group-btn">
                                                               <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                            </span>
                                                         @endif
                                                         {!! Form::text('exponente_corey_agua',(is_null($IPR->exponente_corey_agua) ? "" : round($IPR->exponente_corey_agua, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'exponente_corey_agua']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                   <button class="btn btn-primary pull-right" onclick="plot_corey_waterOil()">Plot</button>  
                                                </div>
                                             </div>
                                             <div class="row">
                                                   <div id="corey_waterOil_g"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="gas_data" style="display:none;">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#rock_properties_g"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Rock Properties</h4>
                  </div>
                  <div class="panel-body">
                     <div id="rock_properties_g" class="panel-collapse collapse in">
                        <br>
                        <div class="panel panel-default {{$errors->has('MSFormation') ? 'has-error' : ''}}">
                           <div class="panel-heading">
                              <h4><a data-parent="#accordion" data-toggle="collapse" href="#basic_petrophysics_g"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Basic Petrophysics</h4>
                           </div>
                           <div class="panel-body">
                              <div id="basic_petrophysics_g" class="panel-collapse collapse in">
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group {{$errors->has('init_res_press_text_g') ? 'has-error' : ''}}">
                                          {!! Form::label('init_res_press_label_g', ' Initial Reservoir Pressure ') !!}<span style='color:red;'>*</span>
                                          <div class="input-group">
                                             @if($advisor === "true")
                                                <span class="input-group-btn">
                                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                </span>
                                             @endif
                                             {!! Form::text('init_res_press_text_g',(is_null($IPR->init_res_press_text_g) ? "" : round($IPR->init_res_press_text_g, 5)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'init_res_press_text_g']) !!}
                                             <span class="input-group-addon" id="basic-addon2">psi</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group {{$errors->has('abs_perm_init_text_g') ? 'has-error' : ''}}">
                                          {!! Form::label('abs_perm_init_label_g', 'Absolute Permeability At Initial Reservoir Pressure ') !!}<span style='color:red;'>*</span>
                                          <div class="input-group">
                                             @if($advisor === "true")
                                                <span class="input-group-btn">
                                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                </span>
                                             @endif
                                             {!! Form::text('abs_perm_init_text_g',(is_null($IPR->abs_perm_init_text_g) ? "" : round($IPR->abs_perm_init_text_g, 5)), ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'abs_perm_init_text_g']) !!}
                                             <span class="input-group-addon" id="basic-addon2">md</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group {{$errors->has('net_pay_text_g') ? 'has-error' : ''}}">
                                          {!! Form::label('net_pay_label_g', 'Net Pay ') !!}<span style='color:red;'>*</span>
                                          <div class="input-group">
                                             @if($advisor === "true")
                                                <span class="input-group-btn">
                                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                </span>
                                             @endif
                                             {!! Form::text('net_pay_text_g',(is_null($IPR->net_pay_text_g) ? "" : round($IPR->net_pay_text_g, 5)), ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'net_pay_text_g']) !!}
                                             <span class="input-group-addon" id="basic-addon2">ft</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                 </div>
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_use_permeability_module_g', 'check_use_permeability_module_g', false, ["id" => "check_use_permeability_module_g"]) !!}
                                       <font size=3><b>Use Permeability Module</b></font>
                                       <input type="hidden" name="check_rock_gas" id="check_rock_gas">
                                    </legend>
                                    <div class="check_use_permeability_g" style="display:none;">
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group {{$errors->has('permeability_module_text_g') ? 'has-error' : ''}}">
                                                {!! Form::label('permeability_module_label_g', 'Permeability Module') !!} <span style='color:red;'>*</span>
                                                <div class="input-group">
                                                   @if($advisor === "true")
                                                      <span class="input-group-btn">
                                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                      </span>
                                                   @endif
                                                   {!! Form::text('permeability_module_text_g',(is_null($IPR->permeability_module_text_g) ? "" : round($IPR->permeability_module_text_g, 7)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'permeability_module_text_g']) !!}
                                                   <span class="input-group-addon" id="basic-addon2">psi<sup>-1</sup></span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_calculate_permeability_module_g', 'check_calculate_permeability_module_g', false, ["id" => "check_calculate_permeability_module_g"]) !!}
                                       <font size=3><b>Calculate Permeability Module</b></font>
                                    </legend>
                                    <div class="check_calculate_permeability_g"  style="display:none">
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group {{$errors->has('abs_perm_text_g') ? 'has-error' : ''}}">
                                                {!! Form::label('abs_perm_label_g', 'Absolute Permeability ') !!}<span style='color:red;'>*</span>
                                                <div class="input-group">
                                                   @if($advisor === "true")
                                                      <span class="input-group-btn">
                                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                      </span>
                                                   @endif
                                                   {!! Form::text('abs_perm_text_g',(is_null($IPR->abs_perm_text_g) ? "" : round($IPR->abs_perm_text_g, 5)), ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'abs_perm_text_g']) !!}
                                                   <span class="input-group-addon" id="basic-addon2">md</span>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group {{$errors->has('porosity_text_g') ? 'has-error' : ''}}">
                                                {!! Form::label('porosity_label_g', ' Porosity') !!} <span style='color:red;'>*</span>
                                                <div class="input-group">
                                                   @if($advisor === "true")
                                                      <span class="input-group-btn">
                                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                      </span>
                                                   @endif
                                                   {!! Form::text('porosity_text_g',(is_null($IPR->porosity_text_g) ? "" : round($IPR->porosity_text_g, 5)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'porosity_text_g']) !!}
                                                   <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group {{$errors->has('rock_type') ? 'has-error' : ''}}">
                                                {!! Form::label('rock_type_g', 'Rock Type') !!} <span style='color:red;'>*</span>
                                                <select name="rock_type" class="form-control" id="rock_type">
                                                   <option value="0"></option>
                                                   @if($IPR->rock_type == '1')
                                                   <option value="1" selected>Consolidated</option>
                                                   @else
                                                   <option value="1">Consolidated</option>
                                                   @endif
                                                   @if($IPR->rock_type == '2')
                                                   <option value="2" selected>Unconsolidated</option>
                                                   @else
                                                   <option value="2" >Unconsolidated</option>
                                                   @endif
                                                   @if($IPR->rock_type == '3')
                                                   <option value="3" selected>Microfractured</option>
                                                   @else
                                                   <option value="3">Microfractured</option>
                                                   @endif
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                        <br>
                        <div class="panel panel-default" style="display:none;">
                           <div class="panel-heading">
                              <h4><a data-parent="#accordion" data-toggle="collapse" href="#relative_permeability_g"><span class="chevron_toggleable1 glyphicon glyphicon-chevron-down pull-right"></span></a> Relative Permeability Data Selection</h4>
                           </div>
                           <div class="panel-body">
                              <div id="relative_permeability_g" class="panel-collapse collapse in">
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_relative_permeability_g', 'check_tabular_rel_perm_g', true, ["id" => "check_tabular_rel_perm_g"]) !!}
                                       <font size=3><b>Tabular</b></font>
                                       <input type="hidden" name="check_fluid_oil" id="check_fluid_oil">
                                    </legend>
                                    <div class="use_permeability_tables_g">
                                       <div class="row">
                                          <div class="col-md-6" id="row_gasoil">
                                             <fieldset>
                                                <legend id="legend_wateroil">Gas-Oil</legend>
                                                <div id="excel_gas_oil_g" class="handsontable"></div>
                                             </fieldset>
                                          </div>
                                          <div class="col-md-6">
                                             <fieldset>
                                                <legend id="legend_gasliquid">Gas-Water</legend>
                                                <div id="excel_gas_water_g" class="handsontable"></div>
                                             </fieldset>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_relative_permeability', 'check_corey_rel_perm_g', false, ["id" => "check_corey_rel_perm_g"]) !!}
                                       <font size=3><b>Corey's Model</b></font>
                                    </legend>
                                    <div class="use_corey_model_g" style="display:none;">
                                       <div class="panel panel-default" id="kro">
                                          <div class="panel-heading">
                                             <h4><a data-parent="#accordion" data-toggle="collapse" href="#Kro"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Gas/Oil End-Point Parameters</h4>
                                          </div>
                                          <div class="panel-body">
                                             <div id="Kro" class="panel-collapse collapse in">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      {!! Form::label('end_point_kr_aceite_gas', 'Kro (Sgc)') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('end_point_kr_aceite_gas') ? 'has-error' : ''}}">
                                                         {!! Form::text('end_point_kr_aceite_gas_g',(is_null($IPR->end_point_kr_aceite_gas) ? "" : round($IPR->end_point_kr_aceite_gas, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'end_point_kr_aceite_gas_g']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">-</span>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      {!! Form::label('saturacion_gas_crit', 'Sgc') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('saturacion_gas_crit') ? 'has-error' : ''}}">
                                                         {!! Form::text('saturacion_gas_crit_g',(is_null($IPR->saturacion_gas_crit) ? "" : round($IPR->saturacion_gas_crit, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'saturacion_gas_crit_g']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">-</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('end_point_kr_gas', 'Krg (Sorg)') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('end_point_kr_gas') ? 'has-error' : ''}}">
                                                            {!! Form::text('end_point_kr_gas_g',(is_null($IPR->end_point_kr_gas) ? "" : round($IPR->end_point_kr_gas, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'end_point_kr_gas_g']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">-</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('saturacion_aceite_irred_gas', 'Sorg') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('saturacion_aceite_irred_gas') ? 'has-error' : ''}}">
                                                            {!! Form::text('saturacion_aceite_irred_gas_g',(is_null($IPR->saturacion_aceite_irred_gas) ? "" : round($IPR->saturacion_aceite_irred_gas, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'saturacion_aceite_irred_gas_g']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">-</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group {{$errors->has('exponente_corey_aceite_gas') ? 'has-error' : ''}}">
                                                         {!! Form::label('exponente_corey_aceite_gas', 'Corey Exponent Oil/Gas') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('exponente_corey_aceite_gas') ? 'has-error' : ''}}">
                                                            {!! Form::text('exponente_corey_aceite_gas_g',(is_null($IPR->exponente_corey_aceite_gas) ? "" : round($IPR->exponente_corey_aceite_gas, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'exponente_corey_aceite_gas_g']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">-</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      {!! Form::label('exponente_corey_gas', 'Corey Exponent Gas ') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('exponente_corey_gas') ? 'has-error' : ''}}">
                                                         {!! Form::text('exponente_corey_gas_g',(is_null($IPR->exponente_corey_gas) ? "" : round($IPR->exponente_corey_gas, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'exponente_corey_gas_g']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">-</span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="panel panel-default" id="krw">
                                          <div class="panel-heading">
                                             <h4><a data-parent="#accordion" data-toggle="collapse" href="#Krw"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Gas/Water End-Point Parameters</h4>
                                          </div>
                                          <div class="panel-body">
                                             <div id="Krw" class="panel-collapse collapse in">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      {!! Form::label('end point', 'Krg (Swi)') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('end_point_kr_petroleo') ? 'has-error' : ''}}">
                                                         {!! Form::text('end_point_kr_petroleo_g',(is_null($IPR->end_point_kr_petroleo) ? "" : round($IPR->end_point_kr_petroleo, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'end_point_kr_petroleo_g']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">-</span>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <div class="form-group {{$errors->has('saturacion_agua_irred') ? 'has-error' : ''}}">
                                                         {!! Form::label('saturacion_agua_irred', 'Swi') !!} <span style='color:red;'>*</span>
                                                         <div class="input-group">
                                                            {!! Form::text('saturacion_agua_irred_g',(is_null($IPR->saturacion_agua_irred) ? "" : round($IPR->saturacion_agua_irred, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'saturacion_agua_irred_g']) !!} 
                                                            <span class="input-group-addon" id="basic-addon2">-</span> 
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('end_point_kr_agua', 'Krw (Sgr)') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('end_point_kr_agua') ? 'has-error' : ''}}">
                                                            {!! Form::text('end_point_kr_agua_g',(is_null($IPR->end_point_kr_agua) ? "" : round($IPR->end_point_kr_agua, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'end_point_kr_agua_g']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">-</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      <div class="form-group">
                                                         {!! Form::label('saturacion de aceite irreducible', 'Sgr') !!} <span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('saturacion_aceite_irred') ? 'has-error' : ''}}">
                                                            {!! Form::text('saturacion_aceite_irred_g',(is_null($IPR->saturacion_aceite_irred) ? "" : round($IPR->saturacion_aceite_irred, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'saturacion_aceite_irred_g']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">-</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <div class="form-group {{$errors->has('exponente_corey_petroleo') ? 'has-error' : ''}}">
                                                         {!! Form::label('exponente_corey_petroleo', 'Corey Exponent Gas ') !!}<span style='color:red;'>*</span>
                                                         <div class="input-group {{$errors->has('Corey Exponent ') ? 'has-error' : ''}}">
                                                            {!! Form::text('exponente_corey_petroleo_g',(is_null($IPR->exponente_corey_petroleo) ? "" : round($IPR->exponente_corey_petroleo, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'exponente_corey_petroleo_g']) !!}
                                                            <span class="input-group-addon" id="basic-addon2">-</span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-6">
                                                      {!! Form::label('exponente_corey_agua', 'Corey Exponent Water ') !!}<span style='color:red;'>*</span>
                                                      <div class="input-group {{$errors->has('exponente_corey_agua') ? 'has-error' : ''}}">
                                                         {!! Form::text('exponente_corey_agua_g',(is_null($IPR->exponente_corey_agua) ? "" : round($IPR->exponente_corey_agua, 5)), ['placeholder' => '-', 'class' =>'form-control', 'id' => 'exponente_corey_agua_g']) !!}
                                                         <span class="input-group-addon" id="basic-addon2">-</span>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="condensate_gas_data" style="display:none;">
               <br>
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#BasicP_c_g"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Basic Petrophysics</h4>
                  </div>
                  <div class="panel-body">
                     <div id="BasicP_c_g" class="panel-collapse collapse in">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('presion inicial', ' Initial Reservoir Pressure ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('presion_inicial_c_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('presion_inicial_c_g',(is_null($IPR->initial_pressure_c_g) ? "" : round($IPR->initial_pressure_c_g, 2)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_inicial_c_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('permeabilidad absoluta inicial', 'Absolute Permeability At Initial Reservoir Pressure ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('permeabilidad_abs_ini_c_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('permeabilidad_abs_ini_c_g',(is_null($IPR->ini_abs_permeability_c_g) ? "" : round($IPR->ini_abs_permeability_c_g, 2)), ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad_abs_ini_c_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">md</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('espesor del reservorio', 'Net Pay') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('espesor_reservorio_c_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('espesor_reservorio_c_g',(is_null($IPR->netpay_c_g) ? "" : round($IPR->netpay_c_g, 2)), ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'espesor_reservorio_c_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                           </div>
                        </div>
                        <fieldset>
                           <legend>
                              {!! Form::radio('check_use_permeability_module_c_g', 'check_use_permeability_module_c_g', false, ["id" => "check_use_permeability_module_c_g"]) !!}
                              <font size=3><b>Use Permeability Module</b></font>
                           </legend>
                           <div class="check_use_permeability_c_g" style="display:none;">
                              <div class="col-md-6 use_permeability_module">
                                 <div class="form-group">
                                    {!! Form::label('modulo permeabilidad', 'Permeability Module') !!} <span style='color:red;'>*</span>
                                    <div class="input-group {{$errors->has('modulo_permeabilidad_c_g') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                       @endif
                                       {!! Form::text('modulo_permeabilidad_c_g',(is_null($IPR->permeability_module_c_g) ? "" : round($IPR->permeability_module_c_g, 2)), ['placeholder' => '1/psi', 'class' =>'form-control', 'id' => 'modulo_permeabilidad_c_g']) !!}
                                       <span class="input-group-addon" id="basic-addon2">1/psi<sup>-1</sup></span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <legend>
                              {!! Form::radio('check_calculate_permeability_module_c_g', 'check_calculate_permeability_module_c_g', false, ["id" => "check_calculate_permeability_module_c_g"]) !!}
                              <font size=3><b>Calculate Permeability Module</b></font>
                           </legend>
                           <div class="check_calculate_permeability_c_g" style="display:none;">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       {!! Form::label('permeabilidad', 'Absolute Permeability ') !!}<span style='color:red;'>*</span>
                                       <div class="input-group {{$errors->has('permeabilidad_c_g') ? 'has-error' : ''}}">
                                          @if($advisor === "true")
                                             <span class="input-group-btn">
                                                <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                             </span>
                                          @endif
                                          {!! Form::text('permeabilidad_c_g',(is_null($IPR->permeability_c_g) ? "" : round($IPR->permeability_c_g, 2)), ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad_c_g']) !!}
                                          <span class="input-group-addon" id="basic-addon2">md</span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       {!! Form::label('porosidad', ' Porosity') !!} <span style='color:red;'>*</span>
                                       <div class="input-group {{$errors->has('porosidad_c_g') ? 'has-error' : ''}}">
                                          @if($advisor === "true")
                                             <span class="input-group-btn">
                                                <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                             </span>
                                          @endif
                                          {!! Form::text('porosidad_c_g',(is_null($IPR->porosity_c_g) ? "" : round($IPR->porosity_c_g, 2)), ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'porosidad_c_g']) !!}
                                          <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       {!! Form::label('tipo de roca', 'Rock Type') !!} <span style='color:red;'>*</span>
                                       <select name="tipo_roca_c_g" class="form-control" id="tipo_roca_c_g">
                                          <option value="0"></option>
                                          @if($IPR->rock_type_c_g == '1')
                                          <option value="1" selected>Consolidated</option>
                                          @else
                                          <option value="1">Consolidated</option>
                                          @endif
                                          @if($IPR->rock_type_c_g == '2')
                                          <option value="2" selected>Unconsolidated</option>
                                          @else
                                          <option value="2" >Unconsolidated</option>
                                          @endif
                                          @if($IPR->rock_type_c_g =='3')
                                          <option value="3" selected>Microfractured</option>
                                          @else
                                          <option value="3">Microfractured</option>
                                          @endif
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </fieldset>
                     </div>
                  </div>
               </div>
               <br>
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#Permeability_Relative"><span class="chevron_toggleable1 glyphicon glyphicon-chevron-down pull-right"></span></a> Relative Permeability Data</h4>
                  </div>
                  <div class="panel-body">
                     <div id="Permeability_Relative" class="panel-collapse collapse in">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <input type='hidden' id='basin' name='basin' value="{!!$_SESSION['basin']!!}">
                                 <input type='hidden' id='field' name='field' value="{!!$_SESSION['field']!!}">
                                 <input type='hidden' id='formation' name='formation' value="{!! $_SESSION['formation'] !!}">
                                 <input type='hidden' id='well' name='well' value="{!! $_SESSION['well'] !!}">
                              </div>
                           </div>
                        </div>
                        <fieldset>
                              <div class="row">
                                 <div class="col-md-5">
                                    <fieldset>
                                       <legend align="center">Gas-Oil</legend>
                                       <div id="excelgasliquid_c_g" class="handsontable"></div>
                                    </fieldset>
                                    <div class="row">
                                       <div class="col-md-6">
                                          <button class="btn btn-primary pull-right" onclick="plot_gasOil_c_g()">Plot</button>  
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-7">
                                    <div id="gas_oil_table_chart_c_g"></div>
                                 </div>
                              </div>
                        </fieldset>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="fluid_properties_c">
            <div class="oil_data">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#MP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fluid properties</h4>
                  </div>
                  <div class="panel-body">
                     <div id="MP" class="panel-collapse collapse in">
                        <!-- **-->       
                        <!-- Reemplazar por validación real-->
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('presion_saturacion') ? 'has-error' : ''}}">
                                 {!! Form::label('presion saturacion', ' Saturation Pressure ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('presion_saturacion',(is_null($IPR->saturation_pressure) ? "" : round($IPR->saturation_pressure, 15)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_saturacion']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4><a data-parent="#accordion" data-toggle="collapse" href="#MP2"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> PVT Data</h4>
                           </div>
                           <div class="panel-body">
                              <div id="MP2" class="panel-collapse collapse in">
                                 <!-- **-->
                                 <div class="panel-body">
                                 </div>
                                 <fieldset>
                                    <div class="tabular_fluid_o"> 
                                       <div class="col-md-12">
                                          <div id="excel_table" class="dataTable" style="overflow: auto; width: 200px; max-width: 200px; height: 100px; max-height: 100px"></div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-6">
                                             <button class="btn btn-primary pull-right" onclick="plot_pvt_oil()">Plot</button>  
                                          </div>
                                       </div>
                                       <div class="row">
                                             <div id="pvt_ipr_oil"></div>
                                       </div>
                                    </div>
                                 </fieldset>
                              </div>
                              <!-- **-->
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="gas_data" style="display:none;">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#fluid_properties_g"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fluid properties</h4>
                  </div>
                  <div class="panel-body">
                     <div id="fluid_properties_g" class="panel-collapse collapse in">
                        <div class="row" style="display:none;">
                           <div class="col-md-6">
                              <div class="form-group {{$errors->has('saturation_pressure_text_g') ? 'has-error' : ''}}">
                                 {!! Form::label('saturation_pressure_label_g', ' Saturation Pressure ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group">
                                    {!! Form::text('saturation_pressure_text_g',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'saturation_pressure_text_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4><a data-parent="#accordion" data-toggle="collapse" href="#pvt_data_g"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> PVT Data</h4>
                           </div>
                           <div class="panel-body">
                              <div id="pvt_data_g" class="panel-collapse collapse in">
                                 <!-- **-->
                                 <div class="panel-body">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <div class="form-group" id="temperatura_id">
                                             {!! Form::label('temperature_label_g', 'Temperature') !!}<span style='color:red;'>*</span>
                                             <div class="input-group {{$errors->has('temperature_text_g') ? 'has-error' : ''}}">
                                                @if($advisor === "true")
                                                   <span class="input-group-btn">
                                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                   </span>
                                                @endif
                                                {!! Form::text('temperature_text_g',(is_null($IPR->temperature_text_g) ? "" : round($IPR->temperature_text_g, 2)), ['placeholder' => 'F', 'class' =>'form-control', 'id' => 'temperature_text_g']) !!}
                                                <span class="input-group-addon" id="basic-addon2">F</span>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          {!! Form::hidden('flag_pvt_gas', 'secret', array('id' => 'flag_pvt_gas')) !!}
                                       </div>
                                    </div>
                                 </div>
                                 <fieldset>
                                    <div class="row tabular_fluid_g">
                                       <div class="col-md-12">
                                          <div id="excel_tabular_pvt_fluid_g" class="dataTable"></div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-6">
                                             <button class="btn btn-primary pull-right" onclick="plot_pvt_gas()">Plot</button>  
                                          </div>
                                       </div>
                                       <div class="row">
                                             <div id="pvt_ipr_gas"></div>
                                       </div>
                                    </div>
                                 </fieldset><!--
                                 <fieldset>
                                    <legend>
                                       {!! Form::radio('check_cub_eq_fluid_g', 'check_cub_eq_fluid_g', false, ["id" => "check_cub_eq_fluid_g"]) !!}
                                       <font size=3><b>Cubic Equations</b></font>
                                    </legend>
                                    <br/>
                                    <div class="cub_eq_fluid_g" style="display:none;">
                                       <legend>
                                          <font size=3><b>Gas Viscosity Coefficients</b><span style='color:red;'>*</span></font>
                                       </legend>
                                       <div class="row">
                                          <div class="group_con" >
                                             <div class="group_con" style="display:inline-block; margin-left: 30px;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c1_viscosity_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c1_viscosity_fluid_g',(is_null($IPR->c1_viscosity_fluid_g) ? "" : round($IPR->c1_viscosity_fluid_g, 15)), ['placeholder' => 'a', 'class' =>'form-control', 'id' => 'c1_viscosity_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                                <span >P<SUP>3</SUP>+</span>
                                             </div>
                                             <div class="group_con" style="display:inline-block;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c2_viscosity_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c2_viscosity_fluid_g',(is_null($IPR->c2_viscosity_fluid_g) ? "" : round($IPR->c2_viscosity_fluid_g, 15)), ['placeholder' => 'b', 'class' =>'form-control', 'id' => 'c2_viscosity_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                                <span >P<SUP>2</SUP>+</span>
                                             </div>
                                             <div class="group_con" style="display:inline-block;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c3_viscosity_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c3_viscosity_fluid_g',(is_null($IPR->c3_viscosity_fluid_g) ? "" : round($IPR->c3_viscosity_fluid_g, 15)), ['placeholder' => 'c', 'class' =>'form-control', 'id' => 'c3_viscosity_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                                <span>P+</span>
                                             </div>
                                             <div class="group_con" style="display:inline-block;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c4_viscosity_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c4_viscosity_fluid_g',(is_null($IPR->c4_viscosity_fluid_g) ? "" : round($IPR->c4_viscosity_fluid_g, 15)), ['placeholder' => 'd', 'class' =>'form-control', 'id' => 'c4_viscosity_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                             </div>
                                             <div class="group_con" style="display:inline-block; margin-left: 30px;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <button class="btn btn-primary pull-right" onclick="plot_cubic_eq_gas_viscosity()">Plot</button>  
                                                   </div>
                                                </span>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                             <div id="cub_eq_gas_viscosity_g"></div>
                                       </div>
                                       <legend>
                                          <font size=3><b>Gas Compressibility Coefficients</b><span style='color:red;'>*</span></font>
                                       </legend>
                                       <div class="row" >
                                          <div class="group_con " >
                                             <div class="group_con" style="display:inline-block; margin-left: 30px;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c1_compressibility_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c1_compressibility_fluid_g',(is_null($IPR->c1_compressibility_fluid_g) ? "" : round($IPR->c1_compressibility_fluid_g, 15)), ['placeholder' => 'a', 'class' =>'form-control', 'id' => 'c1_compressibility_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                                <span >P<SUP>3</SUP>+</span>
                                             </div>
                                             <div class="group_con" style="display:inline-block;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c2_compressibility_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c2_compressibility_fluid_g',(is_null($IPR->c2_compressibility_fluid_g) ? "" : round($IPR->c2_compressibility_fluid_g, 15)), ['placeholder' => 'b', 'class' =>'form-control', 'id' => 'c2_compressibility_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                                <span >P<SUP>2</SUP>+</span>
                                             </div>
                                             <div class="group_con" style="display:inline-block;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c3_compressibility_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c3_compressibility_fluid_g',(is_null($IPR->c3_compressibility_fluid_g) ? "" : round($IPR->c3_compressibility_fluid_g, 15)), ['placeholder' => 'c', 'class' =>'form-control', 'id' => 'c3_compressibility_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                                <span>P+</span>
                                             </div>
                                             <div class="group_con" style="display:inline-block;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <div class="input {{$errors->has('c4_compressibility_fluid_g') ? 'has-error' : ''}}">
                                                         {!! Form::text('c4_compressibility_fluid_g',(is_null($IPR->c4_compressibility_fluid_g) ? "" : round($IPR->c4_compressibility_fluid_g, 15)), ['placeholder' => 'd', 'class' =>'form-control', 'id' => 'c4_compressibility_fluid_g']) !!}
                                                      </div>
                                                   </div>
                                                </span>
                                             </div>
                                             <div class="group_con" style="display:inline-block; margin-left: 30px;">
                                                <span>
                                                   <div class="form-group" id="" style="width: 100px; height: 39px; display:inline-block;">
                                                      <button class="btn btn-primary pull-right" onclick="plot_cubic_eq_gas_compressibility()">Plot</button>  
                                                   </div>
                                                </span>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="row">
                                             <div id="cub_eq_gas_compressibility_g"></div>
                                       </div>
                                    </div>
                                 </fieldset>-->
                              </div>
                              <!-- **-->
                           </div>
                        </div>
                        <div class="panel panel-default" style="display:none">
                           <div class="panel-heading">
                              <h4><a data-parent="#accordion" data-toggle="collapse" href="#D_curve"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> 
                                 Drop-out Curve
                              </h4>
                           </div>
                           <div class="panel-body">
                              <div id="D_curve" class="panel-collapse collapse in">
                                 <fieldset>
                                    <div class="row">
                                       <div class="col-md-12">
                                          <fieldset>
                                             <legend id="legend_wateroil">Liquid Phase Fraction vs. Pressure </legend>
                                             <div id="drop_out_curve_g" class="handsontable"></div>
                                          </fieldset>
                                       </div>
                                    </div>
                                 </fieldset>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="condensate_gas_data" style="display:none;">
               <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4><a data-parent="#accordion" data-toggle="collapse" href="#MP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fluid properties</h4>
                  </div>
                  <div class="panel-body">
                     <div id="MP" class="panel-collapse collapse in">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('presion saturacion', ' Saturation Pressure ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('presion_saturacion_c_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('presion_saturacion_c_g',(is_null($IPR->saturation_pressure_c_g) ? "" : round($IPR->saturation_pressure_c_g, 2)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_saturacion_c_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('gor', ' GOR ') !!}<span style='color:red;'>*</span>
                                 <div class="input-group {{$errors->has('gor_c_g') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                       <span class="input-group-btn">
                                          <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                       </span>
                                    @endif
                                    {!! Form::text('gor_c_g',(is_null($IPR->gor_c_g) ? "" : round($IPR->gor_c_g, 2)), ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'gor_c_g']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <h4 align="center">PVT Data</h4>
                           <hr>
                           <div class="row" id="pvt_c_g" class="handsontable"></div>
                           <div class="row" id="pvt_c_g_chart"></div>
                           <div class="row">
                                 <button class="btn btn-primary pull-right" onclick="plot_pvt_c_g()">Plot</button>  
                           </div>
                           <p></p>
                           <h4 align="center">Drop-Out Data</h4>
                           <hr>
                           <div class="row">
                              <div class="col-md-5">
                                 <div class="row" id="drop_out_c_g" class="handsontable"></div>
                              </div>
                              <div class="col-md-7">
                                 <div class="row" id="drop_out_c_g_chart"></div>
                              </div>
                           </div>
                           <div class="row">
                                 <button class="btn btn-primary pull-right" onclick="plot_drop_out_c_g()">Plot</button>  
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
<div id="loading" style="display: none;"></div>
<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
         {!! Form::submit('Run IPR' , array('class' => 'btn btn-primary', 'onclick' => 'enviar(true);', 'name' => 'accion', 'id'=>'run', 'style'=>'display:none;')) !!}
         {!! Form::submit('Save' , array('class' => 'btn btn-primary', 'onclick' => 'save();', 'name' => 'accion', 'style'=>'display:none;')) !!}
         <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
         {!! Form::hidden('presiones_table', '', array('id' => 'presiones_table')) !!}
         {!! Form::hidden('wateroil_hidden', '', array('id' => 'wateroil_hidden')) !!}
         {!! Form::hidden('gasliquid_hidden', '', array('id' => 'gasliquid_hidden')) !!}

         {!! Form::hidden('pvt_gas_ipr', '', array('id'=>'pvt_gas_ipr')) !!}

         {!! Form::hidden('pvt_cg', '', array('id'=>'pvt_cg')) !!}
         {!! Form::hidden('gas_oil_kr_cg', '', array('id'=>'gas_oil_kr_cg')) !!}
         {!! Form::hidden('dropout_cg', '', array('id'=>'dropout_cg')) !!}

         {!! Form::hidden('fluid_select_value', '', array('id'=>'fluid_select_value')) !!}
      </p>
      {!! Form::Close() !!}
   </div>
</div>
<!-- **-->

@include('layouts/modal_advisor')
@include('layouts/advisor_ipr')

@endsection

@section('Scripts')
   @include('js/iprsedit')
   @include('css/iprs_css')
   @include('js/advisor')
@endsection