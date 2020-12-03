@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')
@section('content')
<?php  
if(!isset($_SESSION)) {
   session_start();
}
?>
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

   <div id="sticky-anchor"  class="col-md-6"></div>
   <div id="sticky">
      <center>
         {!! Form::label('Scenario: ') !!} {!! $scenary->nombre !!} {!! Form::label(' - Basin: ') !!} {!! $basin->nombre !!} {!! Form::label(' - Field: ') !!} {!! $field->nombre !!} {!! Form::label(' - Well: ') !!} {!!  $well->nombre !!} {!! Form::label(' - User: ') !!} {!! $user->fullName !!}
      </center>
   </div>
   <p></p>
</br>

<!-- ***  -->
@include('layouts/general_advisor')
<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#well_data_c" id="well_data">Well Data</a></li>
         <li><a data-toggle="tab" href="#production_data_c" id="production_data">Operative Data</a></li>
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
                     {!!Form::open(array('id' => 'form_scenario', 'url' => url('IPR/update',(!is_null($duplicateFrom) ? $duplicateFrom : $IPR->id_escenario)), 'method' => 'post'))!!}
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('well_type', 'Well Type ') !!}<span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('fluido') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {{-- {{ dd($tabla) }} --}}
                                 <select name="well_type" onchange="defineWellType(this)" class="form-control" style="border-radius: 4px;" id="well_type">
                                    @if(intval($IPR->well_Type) == 1)
                                    <option value="1" selected>Producer</option>
                                    @else
                                    <option value="1">Producer</option>
                                    @endif
                                    @if(intval($IPR->well_Type) == 2)
                                    <option value="2" selected>Injector</option>
                                    @else
                                    <option value="2">Injector</option>
                                    @endif
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('fluido', 'Fluid ') !!}<span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('fluido') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 <select name="fluido" onchange="defineView(this,$('#well_type').val())" class="form-control" style="border-radius: 4px;" id="fluido">
                                    <option value="0" selected disabled></option>
                                 </select>
                              </div>
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
                                 {!! Form::text('radio_pozo',floatval($IPR->radio_pozo), ['placeholder' => 'Well Radius', 'class' =>'form-control', 'id' => 'radio_pozo']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('drainage radius', 'Drainage Radius') !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('radio_drenaje_yac') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('radio_drenaje_yac',floatval($IPR->radio_drenaje_yac), ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_drenaje_yac']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane " id="production_data_c">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Product"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Operative Data </h4>
               </div>
               <div class="panel-body">
                  <div id="Product" class="panel-collapse collapse in">
                     {{-- De ejemplo tomo lo que antes era Black Oil --}}
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group data_first_input">
                              {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('',null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                 <span class="input-group-addon" name="medida"></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group data_second_input">
                              {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('',null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                 <span class="input-group-addon" name="medida"></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group data_third_input">
                              {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                              <div class="input-group {{$errors->has('') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                                 @endif
                                 {!! Form::text('',null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                 <span class="input-group-addon" name="medida"></span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane " id="rock_properties_c">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#Fluid"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Rock Properties</h4>
               </div>
               <div class="panel-body">
                  <div id="Fluid" class="panel-collapse collapse in">
                     <br>

                     @foreach($formacion as $key => $form)
                     <input type="hidden" name="id_bp" id="id_bp" value="{{ $form->id }}">
                     <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4><a data-parent="#accordion" data-toggle="collapse" href="#BasicP_{{ $key }}_"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Basic Petrophysics - [ {{ $form->nombre }} ]</h4>
                        </div>
                        <div id="BasicP_{{ $key }}_" class="panel-collapse collapse in BasicP">
                           <div class="panel-body">
                              <div class="row">

                                 <div class="col-md-12">
                                    <div class="form-group">
                                       {!! Form::label('stress_sensitive_reservoir', 'Stress Sensitive Reservoir ') !!}<span style='color:red;'>*</span>
                                       <div class="input-group {{$errors->has('fluido') ? 'has-error' : ''}}">
                                          <select name="stress_sensitive_reservoir[]" onchange="defineRockProps(this)" style="border-radius: 4px;" class="form-control stress_sensitive_reservoir_class" id="stress_sensitive_reservoir">
                                             <option value="2" {{ $form->stress_sensitive_reservoir == 2 ? 'selected' : '' }}>Yes</option>
                                             <option value="1" {{ $form->stress_sensitive_reservoir == 1 ? 'selected' : '' }}>No</option>
                                          </select>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group data_first_input_rock">
                                       {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                                       <div class="input-group">
                                          @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('', $form->initial_reservoir_pressure , ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                          <span class="input-group-addon" name="medida"></span>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group data_second_input_rock">
                                       {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                                       <div class="input-group">
                                          @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif

                                          {!! Form::text('',$form->absolute_permeability_it_initial_reservoir_pressure, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                          {{-- {!! Form::text('',$form->absolute_permeability_it_initial_reservoir_pressure, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!} --}}
                                          <span class="input-group-addon" name="medida"></span>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group data_third_input_rock">
                                       {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                                       <div class="input-group">
                                          @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('',$form->net_pay, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                          <span class="input-group-addon" name="medida"></span>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group">
                                       {!! Form::label('presion_yacimiento', 'Current Reservoir Pressure', ['id' => 'label_presion_yacimiento']) !!} <span style='color:red;'>*</span>
                                       <div class="input-group {{$errors->has('presion_yacimiento') ? 'has-error' : ''}}">
                                          @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('presion_yacimiento[]',$form->current_reservoir_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_yacimiento']) !!}
                                          <span class="input-group-addon" id="basic-addon2">psi</span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group data_fourth_input_rock">
                                       {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                                       <div class="input-group">
                                          @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('',$form->absolute_permeability, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                          <span class="input-group-addon" name="medida"></span>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col-md-6">
                                    <div class="form-group presion_separacion_div">
                                       {!! Form::label('presion_separacion', 'Reservoir Parting Pressure') !!} <span style='color:red;'>*</span>
                                       <div class="input-group {{$errors->has('presion_separacion') ? 'has-error' : ''}}">
                                          @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('presion_separacion[]',$form->reservoir_parting_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_separacion']) !!}
                                          <span class="input-group-addon" id="basic-addon2">psi</span>
                                       </div>
                                    </div>
                                 </div>

                              </div>
                              <div class="row permeabilidad_module">
                                 <hr>
                                 <div class="col-md-6">
                                    <div class="form-group data_fifth_input_rock">
                                       {!! Form::label('', '', ['name' => 'label']) !!} <span style='color:red;'>*</span>
                                       <div class="input-group">
                                          @if($advisor === "true")
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                          </span>
                                          @endif
                                          {!! Form::text('',$form->permeability_module, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                          <span class="input-group-addon" name="medida"></span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <button type="button" class="btn btn-primary b_calculate" style="margin-top: 22px;">Calculate by Correlation</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     
                     <div class="panel panel-default relative_permeability_data_section">
                        <div class="panel-heading">
                           <h4><a data-parent="#accordion" data-toggle="collapse" href="#Permeability_Relative"><span class="chevron_toggleable1 glyphicon glyphicon-chevron-down pull-right"></span></a> Relative Permeability Data Selection</h4>
                        </div>
                        <div class="panel-body">
                           <div id="Permeability_Relative" class="panel-collapse collapse in">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <input type='hidden' id='basin' name='basin' value="{!!$basin->nombre !!}">
                                       <input type='hidden' id='field' name='field' value="{!!$field->nombre !!}">
                                       <input type='hidden' id='well' name='well' value="{!! $well->nombre  !!}">
                                    </div>
                                 </div>
                              </div>
                              <fieldset class="fieldset_tab">
                                 <legend>
                                    {!! Form::radio('', '', true, ["id" => "", "class" => "input_check_use_tab"]) !!}
                                    <font size=3><b>Tabular</b></font>
                                 </legend>
                                 <div class="use_permeability_tables" style="display:none";>
                                    <div class="row">
                                       <div class="col-md-6 rpds_left" id="row_wateroil">
                                          <fieldset>
                                             <legend >Water-Oil @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_excelwateroil" style="color:black;font-size:15pt;"></i></span>@endif</legend>
                                             <div id="excelwateroil" class="handsontable"></div>
                                          </fieldset>
                                          <div class="row">
                                             <div class="col-md-6">
                                                <button type="button" class="btn btn-primary pull-right btn_plot_pt_left">Plot</button>  
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div id="graph_left"></div>
                                          </div>
                                       </div>
                                       <div class="col-md-6 rpds_right">
                                          <fieldset>
                                             <legend>Gas-Oil @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_excelgasliquid" style="color:black;font-size:15pt;"></i></span>@endif</legend>
                                             <div id="excelgasliquid" class="handsontable"></div>
                                          </fieldset>
                                          <div class="row">
                                             <div class="col-md-6">
                                                <button type="button" class="btn btn-primary pull-right btn_plot_pt_right">Plot</button>  
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div id="graph_right"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </fieldset>

                              <fieldset class="fieldset_corey">
                                 <legend>
                                    {!! Form::radio('', '', false, ["id" => "", "class" => "input_check_use_corey"]) !!}
                                    <font size=3><b>Corey's Model</b></font>
                                 </legend>
                                 <div class="use_corey_model" style="display:none;">
                                    <div class="panel panel-default" id="kro">
                                       <div class="panel-heading">
                                          <h4><a data-parent="#accordion" data-toggle="collapse" href="#Kro"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Gas/Oil End-Point Parameters</h4>
                                       </div>
                                       <div class="panel-body">
                                          <div id="Kro" class="panel-collapse collapse in">
                                             <div class="row">
                                                <div class="col-md-6">
                                                   <div class="form-group data_first_input_corey">
                                                      {!! Form::label('','', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_second_input_corey">
                                                      {!! Form::label('', '', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_third_input_corey">
                                                      {!! Form::label('', '', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_fourth_input_corey">
                                                      {!! Form::label('', '', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_fifth_input_corey">
                                                      {!! Form::label('', '', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_sixth_input_corey">
                                                      {!! Form::label('', '', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
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
                                                <div id="corey_gasOil"></div>
                                             </div>
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
                                                   <div class="form-group data_first_input_corey_ow">
                                                      {!! Form::label('','', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_second_input_corey_ow">
                                                      {!! Form::label('','', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_third_input_corey_ow">
                                                      {!! Form::label('','', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_fourth_input_corey_ow">
                                                      {!! Form::label('','', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_fifth_input_corey_ow">
                                                      {!! Form::label('','', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="form-group data_sixth_input_corey_ow">
                                                      {!! Form::label('','', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                                                      <div class="input-group">
                                                         {!! Form::text('', null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                                         <span class="input-group-addon" name="medida"></span>
                                                      </div>
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
                                             <div id="corey_waterOil"></div>
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

         <div class="tab-pane " id="fluid_properties_c">
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#MP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fluid properties</h4>
               </div>
               <div class="panel-body">
                  <div id="MP" class="panel-collapse collapse in">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group data_first_input_fp">
                              {!! Form::label('', '', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                              <div class="input-group">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                 @endif
                                 {!! Form::text('',null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                 <span class="input-group-addon" name="medida"></span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group data_second_input_fp">
                              {!! Form::label('', '', ['name' => 'label']) !!}<span style='color:red;'>*</span>
                              <div class="input-group">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                 @endif
                                 {!! Form::text('',null, ['placeholder' => '', 'class' =>'form-control', 'id' => '']) !!}
                                 <span class="input-group-addon" name="medida"></span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="panel panel-default pvt_data">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#MP2"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> PVT Data</h4>
               </div>
               <div class="panel-body">
                  <div id="MP2" class="panel-collapse collapse in">
                     <fieldset>
                        <div class="tabular">
                           <div id="excel_table_pvt" class="handsontable"></div>
                           <br>
                           <div class="row">
                              <div class="col-md-12">
                                 <button class="btn btn-primary pull-right btn_plot_fp_first">Plot</button>  
                              </div>
                           </div>
                           <div class="row">
                              <div id="graph_oil_viscosity"></div>
                           </div>
                           <div class="row">
                              <div id="graph_oil_volumetric_factor"></div>
                           </div>
                           <div class="row">
                              <div id="graph_water_viscosity"></div>
                           </div>
                            <div class="row">
                              <div id="graph_gas_viscosity"></div>
                           </div>
                            <div class="row">
                              <div id="graph_gas_compressibility_factor"></div>
                           </div>
                           <div class="row">
                              <div id="graph_up"></div>
                           </div>
                           <div class="row">
                              <div id="graph_bo"></div>
                           </div>
                           <div class="row">
                              <div id="graph_uo"></div>
                           </div>
                           <div class="row">
                              <div id="graph_rs"></div>
                           </div>
                           <div class="row">
                              <div id="graph_bg"></div>
                           </div>
                           <div class="row">
                              <div id="graph_ug"></div>
                           </div>
                           <div class="row">
                              <div id="graph_ogratio"></div>
                           </div>
                        </div>
                     </fieldset> 
                  </div>
               </div>
            </div>

            <div class="panel panel-default drop_out_data">
               <div class="panel-heading">
                  <h4><a data-parent="#accordion" data-toggle="collapse" href="#MP2"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Drop-Out Data</h4>
               </div>
               <div class="panel-body">
                  <div id="MP2" class="panel-collapse collapse in">
                     <fieldset>
                        <div class="tabular">
                           <div class="col-sm-12">
                              <div id="excel_table_dod" class="handsontable"></div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <button class="btn btn-primary pull-right btn_plot_fp_second">Plot</button>  
                              </div>
                           </div>
                           <div class="row">
                              <div id="graph_down"></div>
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

<div id="modal_calculate" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Calculate by Correlation</h4>
         </div>
         <div class="modal-body">

            <center>
               <div class="alert alert-danger errors_modal_calc_permeability" style="display: none;">
                  <h5><strong>Errors!</strong></h5> <br>
                  <ul id="list-err-modal" style="text-decoration: none;">

                  </ul>
               </div>
            </center>

            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     {!! Form::label('permeabilidad_modal', 'Absolute Permeability ') !!}<span style='color:red;'>*</span>
                     <div class="input-group">
                        {!! Form::text('permeabilidad_modal',null, ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad_modal']) !!}
                        <span class="input-group-addon" id="basic-addon2">md</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     {!! Form::label('porosidad_modal', ' Porosity') !!} <span style='color:red;'>*</span>
                     <div class="input-group {{$errors->has('porosidad') ? 'has-error' : ''}}">
                        {!! Form::text('porosidad_modal',null, ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'porosidad_modal']) !!}
                        <span class="input-group-addon" id="basic-addon2">Fraction</span>
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                  <div class="form-group">
                     {!! Form::label('rock_type_modal', 'Rock Type') !!} <span style='color:red;'>*</span>
                     <div class="input-group {{$errors->has('tipo_roca') ? 'has-error' : ''}}">
                        <select name="rock_type_modal" class="form-control" style="border-radius: 4px;" id="rock_type_modal">
                           @if($IPR->rock_type == 1)
                           <option value="1" selected>Consolidated</option>
                           @else
                           <option value="1">Consolidated</option>
                           @endif
                           @if($IPR->rock_type == 2)
                           <option value="2" selected>Unconsolidated</option>
                           @else
                           <option value="2">Unconsolidated</option>
                           @endif
                           @if($IPR->rock_type == 3)
                           <option value="3" selected>Microfractured</option>
                           @else
                           <option value="3">Microfractured</option>
                           @endif
                        </select>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <center><button type="button" class="btn btn-primary btn_calculate_final">Calculate</button></center>
         </div>
      </div>
   </div>
</div>

<div id="alert_errors" title='Errors'>
   <center>
      <div class="alert alert-danger errors_modal_calc_permeability" style="display: none;">
         <h5><strong>Errors!</strong></h5> <br>
         <ul id="list-err-modal" style="text-decoration: none;">

         </ul>
      </div>
   </center>
</div>


<!-- ***  -->
<div id="loading" style="display: none;"></div>
<div class="row">
   <div class="col-md-12">
      <p class="pull-right">
         {{-- {!! Form::submit('Run IPR' , array('class' => 'btn btn-primary', 'onclick' => 'enviar(true);', 'name' => 'accion', 'id'=>'run', 'style'=>'display:none;')) !!} --}}
         {{-- {!! Form::submit('Save' , array('class' => 'btn btn-primary', 'onclick' => 'save();', 'name' => 'accion', "style"=>"display:none;")) !!} --}}
         <input type="hidden" name="modo_submit" id="modo_submit">
         <input type="button" class="btn btn-primary" onclick="enviar(true,1);" name="accion" id="run" style="display:none;" value="Run IPR">
         <input type="button" class="btn btn-success" onclick="enviar(true,2);" name="save" id="Save" value="Save">
         <a href="{!! url('IPR/result',$IPR->id_escenario) !!}" class="btn btn-danger" role="button">Cancel</a>
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


@include('layouts/modal_advisor')
@include('layouts/advisor_ipr')

@endsection

@section('Scripts')
@include('js/template/iprs')
@include('css/iprs_css')
@include('js/advisor')
@endsection