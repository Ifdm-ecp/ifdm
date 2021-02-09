@extends('layouts.ProjectGeneral')  
@section('title', 'IFDM Project')

@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
?>

@include('layouts/modal_error')
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
<center>
      {!! Form::label('Scenario: ') !!} {!! Form::label('scenary_name', $scenary->nombre) !!} {!! Form::label(' - Basin: ') !!} {!! Form::label('basin_name', $cuenca->nombre) !!} {!! Form::label(' - Field: ') !!} {!! Form::label('field_name', $campo->nombre) !!} {!! Form::label(' - Producing interval: ') !!} {!! Form::label('interval_name', $formacion->nombre) !!} {!! Form::label(' - Well: ') !!} {!! Form::label('well_name', $pozo->nombre) !!} {!! Form::label(' - User: ') !!} {!! Form::label('user_name', $user->fullName) !!}
   </center>
</div>
<p></p>
</br>

{!! Form::open(['action' => ['add_fines_migration_diagnosis_controller@store', 'scenaryId' => $scenaryId], 'method' => 'post']) !!}

@include('layouts/general_advisor')

<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#general_data" id="general_data_tab" onclick="switchTab()">General Data</a></li>
         <li><a data-toggle="tab" href="#pvt_data" id="pvt_data_tab" onclick="switchTab()">PVT Data</a></li>
         <li><a data-toggle="tab" href="#fines_data" id="fines_data_tab" onclick="switchTab()">Phenomenological Constants</a></li>
         <li><a data-toggle="tab" href="#historical_data" id="historical_data_tab" onclick="switchTab()">Historical Data</a></li>
      </ul>
      <div class="row tab-content">
         <div class="tab-pane active" id="general_data">
            <div class="panel-body">
               <div class="panel panel-default">
                  <div class="panel-heading"><b>Well Properties</b></div>
                  <div class="panel-body">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('drainage_radius_label', 'Drainage Radius') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                 <div class="input-group {{$errors->has('drainage_radius') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                      <span class="input-group-btn">
                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                      </span>
                                    @endif
                                    {!! Form::text('drainage_radius', null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'drainage_radius']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('formation_height_label', 'Net Pay') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                 <div class="input-group {{$errors->has('formation_height') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                      <span class="input-group-btn">
                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                      </span>
                                    @endif
                                    {!! Form::text('formation_height', null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'formation_height']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('well_radius_label', 'Well Radius') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                 <div class="input-group {{$errors->has('well_radius') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                      <span class="input-group-btn">
                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                      </span>
                                    @endif
                                    {!! Form::text('well_radius', null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'well_radius']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('perforating_radius_label', 'Perforation Radius') !!}
                                 <div class="input-group {{$errors->has('perforation_radius') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                      <span class="input-group-btn">
                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                      </span>
                                    @endif
                                    {!! Form::text('perforation_radius', null,  ['placeholder' => 'inch', 'class' =>'form-control', 'id' => 'perforation_radius']) !!}
                                    <span class="input-group-addon" id="basic-addon2">inch</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 {!! Form::label('numbers_of_perforations_label', 'Number Of Perforations') !!}
                                 <div class="input-group {{$errors->has('number_of_perforations') ? 'has-error' : ''}}">
                                    @if($advisor === "true")
                                      <span class="input-group-btn">
                                         <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                      </span>
                                    @endif
                                    {!! Form::text('number_of_perforations', null,  ['placeholder' => '-', 'class' =>'form-control', 'id' => 'number_of_perforations']) !!}
                                    <span class="input-group-addon" id="basic-addon2">-</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                  </div>
               </div>
               <div class="panel panel-default">
                  <div class="panel-heading"><b>Formation Properties</b></div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_permeability_label', 'Initial Permeability') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('initial_permeability') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                   <span class="input-group-btn">
                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                   </span>
                                 @endif
                                 {!! Form::text('initial_permeability', null,  ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'initial_permeability']) !!}
                                 <span class="input-group-addon" id="basic-addon2">mD</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_porosity_label', 'Initial Porosity') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('initial_porosity') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                 @endif
                                 {!! Form::text('initial_porosity', null,  ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'initial_porosity']) !!}
                                 <span class="input-group-addon" id="basic-addon2">Fraction</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                      <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('compressibility_label', 'Compressibility') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('compressibility') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                   <span class="input-group-btn">
                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                   </span>
                                 @endif
                                 {!! Form::text('compressibility', null,  ['placeholder' => '1/psi', 'class' =>'form-control', 'id' => 'compressibility']) !!}
                                 <span class="input-group-addon" id="basic-addon2">1/psi</span>
                              </div>
                           </div>
                        </div>
                      <div class="col-md-6">
                         <div class="form-group">
                            {!! Form::label('porosity_limit_constant_label', 'Constant For The Porosity Limit') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                            <div class="input-group {{$errors->has('porosity_limit_constant') ? 'has-error' : ''}}">
                               @if($advisor === "true")
                                 <span class="input-group-btn">
                                    <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                 </span>
                               @endif
                               {!! Form::text('porosity_limit_constant', null,  ['placeholder' => '-', 'class' =>'form-control', 'id' => 'porosity_limit_constant']) !!}
                               <span class="input-group-addon" id="basic-addon2">-</span>
                            </div>
                         </div>
                      </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('pore_diameter_label', 'Average Pore Diameter') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('average_pore_diameter') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                   <span class="input-group-btn">
                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                   </span>
                                 @endif
                                 {!! Form::text('average_pore_diameter', null,  ['placeholder' => 'μm', 'class' =>'form-control', 'id' => 'average_pore_diameter']) !!}
                                 <span class="input-group-addon" id="basic-addon2">μm</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_pressure_label', 'Initial Pressure') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('initial_pressure') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                   <span class="input-group-btn">
                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                   </span>
                                 @endif
                                 {!! Form::text('initial_pressure', null,  ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'initial_pressure']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="panel panel-default">
                  <div class="panel-heading"><b>Fines Properties</b></div>
                  <div class="panel-body">
                    <div class="form-group hidden">
                        <div class="form-group {{$errors->has('type_of_suspension_flux') ? 'has-error' : ''}}">
                           {!! Form::label('type_of_suspension_flux_label', 'Type of Suspension Flux') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                           {!! Form::select('type_of_suspension_flux', [
                              'oil' => 'Oil'],null, array('class'=>'form-control selectpicker show-tick', 'id'=>'type_of_suspension_flux')
                             ) !!}
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div clas="form-group">
                              {!! Form::label('fine_density_label', 'Fine Density') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('fine_density') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                   <span class="input-group-btn">
                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                   </span>
                                 @endif
                                 {!! Form::text('fine_density', null,  ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'fine_density']) !!}
                                 <span class="input-group-addon" id="basic-addon2">g/cc</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('fine_diameter_label', 'Fine Diameter') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('fine_diameter') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                   <span class="input-group-btn">
                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                   </span>
                                 @endif
                                 {!! Form::text('fine_diameter', null,  ['placeholder' => 'μm', 'class' =>'form-control', 'id' => 'fine_diameter']) !!}
                                 <span class="input-group-addon" id="basic-addon2">μm</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_deposited_fines_concentration_label', 'Initial Deposited Fines Concentration') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="form-inline">
                                <div class="input-group {{$errors->has('initial_deposited_fines_concentration') ? 'has-error' : ''}}">
                                   @if($advisor === "true")
                                     <span class="input-group-btn">
                                        <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                     </span>
                                   @endif
                                   {!! Form::text('initial_deposited_fines_concentration', null,  ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'initial_deposited_fines_concentration']) !!}
                                   <span class="input-group-addon" id="basic-addon2">g/cc</span>
                                  </div>
                                  <div class="pull-right">
                                    <button type="button" class="btn btn-primary concentration_ev">Calculate</button>
                                  </div>
                                </div>
                                </div>
                           </div>
                          <div class="col-md-6">
                             <div class="form-group">
                                {!! Form::label('critical_rate_label', 'Critical Rate') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                <div class="input-group {{$errors->has('critical_rate') ? 'has-error' : ''}}">
                                   @if($advisor === "true")
                                     <span class="input-group-btn">
                                        <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                     </span>
                                   @endif
                                   {!! Form::text('critical_rate', null,  ['placeholder' => 'cc/min', 'class' =>'form-control', 'id' => 'critical_rate']) !!}
                                   <span class="input-group-addon" id="basic-addon2">cc/min</span>
                                </div>
                             </div>
                          </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_fines_label', 'Initial Fines Concentration In Fluid') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('initial_fines_concentration_in_fluid') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                   <span class="input-group-btn">
                                      <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                   </span>
                                 @endif
                                 {!! Form::text('initial_fines_concentration_in_fluid', null,  ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'initial_fines_concentration_in_fluid']) !!}
                                 <span class="input-group-addon" id="basic-addon2">g/cc</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="pvt_data">
            <div class="panel-body">
               <div class="panel panel-default">
                  <div class="panel-heading"><b>PVT Data</b>&nbsp;&nbsp;@if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_pvt_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-12" style="overflow: auto;">
                           <div id="pvt_table"></div>
                           {!! Form::hidden('value_pvt_data', '', array('class' => 'form-control', 'id' => 'value_pvt_data')) !!}
                        </div>
                     </div><br>
                     <div class="row">
                        <div class="col-md-12">
                           <button class="btn btn-primary pull-right plot_pvt_table" type="button">Plot</button>   
                        </div>
                     </div>
                    <div class="row">
                      <div id="graphic_pvt_table_density"></div>
                    </div>
                    <div class="row">
                      <div id="graphic_pvt_table_viscosity"></div>
                    </div>
                    <div class="row">
                      <div id="graphic_pvt_table_volumetric"></div>
                    </div>
                  </div>
               </div>   
            </div>
         </div>
         <div class="tab-pane" id="fines_data">
            <div class="panel-body">
               <div class="panel panel-default">
                  <div class="panel-heading"><b>Phenomenological Constants</b>&nbsp;&nbsp;@if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_phenomenological_constants_table" style="color:black;font-size:15pt;"></i></span>@endif&nbsp;&nbsp;<a><span><i class="glyphicon glyphicon-list-alt import-phenomenological-data load-data" id="phenomenological_data_import" style="color:black;font-size:15pt;"></i></span></a></div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-12" style="overflow: auto;">
                           <div id="phenomenological_constants_table"></div>
                           {!! Form::hidden('value_phenomenological_constants', '', array('class' => 'form-control', 'id' => 'value_phenomenological_constants')) !!}
                        </div>
                     </div><br>
                     <div class="row" style="display: none;">
                        <div class="col-md-12">
                           <button class="btn btn-primary pull-right plot_phenomenological_constants_table" type="button">Plot</button>   
                        </div>
                     </div>
                     <div class="row">
                      <div id="graphic_phenomenological_constants_table"></div>
                    </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="historical_data">
            <div class="panel-body">
               <div class="panel panel-default">
                  <div class="panel-heading"><b>Historical Data</b>&nbsp;&nbsp;@if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_historical_data_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div id="historical_data_table"></div>
                           {!! Form::hidden('value_historical_data', '', array('class' => 'form-control', 'id' => 'value_historical_data')) !!}
                        </div>
                     </div><br>
                     <div class="row">
                       <div class="col-md-12">
                          <button class="btn btn-primary plot_historical_data_table pull-right" type="button">Plot</button>
                      </div>
                    </div>
                    <div class="row col-md-12">
                      <div id="graphic_historical_data_table"></div>
                    </div>
               </div>
             </div>
              <div class="panel panel-default" id="production_projection">
                  <div class="panel-heading"><b>Production Projection</b></div>
                  <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                           <div class="form-group" id="historical_oil">
                              <div class="form-group {{$errors->has('perform_historical_projection_oil') ? 'has-error' : ''}}">
                                 {!! Form::label('historical_projection_label', 'Please, choose a projection data if required') !!}
                                 {!! Form::select('perform_historical_projection_oil', ['without' => 'Without Projection', 'exponential' => 'Exponential', 'hyperbolic'=>'Hyperbolic'], null, array('class'=>'form-control',  'id'=>'perform_historical_projection_oil')) !!}
                              </div>
                           </div>
                        </div>
                      </div>
                        <div class="row" id="final_dates">
                          <div class="col-md-12">
                            <hr />
                          </div> 
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('final_date_label', 'Final Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              @if($advisor === "true")
                                <div class="input-group {{$errors->has('final_date') ? 'has-error' : ''}}">
                                   @if($advisor === "true")
                                     <span class="input-group-btn">
                                        <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                     </span>
                                   @endif
                                    {!! Form::date('final_date', null, ['class' =>'form-control', 'id'=>'final_date']); !!}
                                </div>
                              @else
                                <div class="form-group {{$errors->has('final_date') ? 'has-error' : ''}}">
                                   @if($advisor === "true")
                                     <span class="input-group-btn">
                                        <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                     </span>
                                   @endif
                                    {!! Form::date('final_date', null, ['class' =>'form-control', 'id'=>'final_date']); !!}
                                </div>
                              @endif
                           </div>
                        </div> 
                        <div class="col-md-6">
                          <br>
                          <button class="btn btn-primary calculate_historical_projection btn-block" style="margin-top: 5px" type="button">Calculate Projection</button>
                        </div> 
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div id="historical_projection_table">
                           {!! Form::hidden('value_historical_projection_data', '', array('class' => 'form-control', 'id' => 'value_historical_projection_data')) !!}
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div id="oil_projection_chart"></div>
                        </div>
                      </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
          <div class="col-md-12">
            <div class="col-md-6" align="left">
                {!! Form::submit('Save' , array('class' => 'save_table_wr btn btn-success', 'id' => 'button_wr', 'name' => 'button_wr')) !!}
                <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
            </div>
            <div class="col-md-6" align="right">
                <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
                <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
                {!! Form::submit('Run' , array('class' => 'maximize btn btn-primary save_table', 'style' => 'display: none', 'id' => 'run_calc')) !!}
            </div>
          </div>
         </div>
      </div>
      </div>
   </div>
</div>

</br>
<br>


<div id="historical_data_saved" class="modal fade" data-toggle="modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Historical Data</h4>
         </div>
         <div class="modal-body">
            <p>Historical Data has been saved successfully!</p>
        </div>
    </div>
</div>
</div>

<div id="fines_concentration_fluid" class="modal fade" data-toggle="modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Deposited Fines</h4>
         </div>
         <div class="modal-body">
            <div class="form-group">
               <div class="tab-pane" id="core_data">
                  <div class="panel-body">
                     <div class="panel panel-default">
                        <div class="panel-heading"><b>Core Data</b></div>
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('l_label', 'Length') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('length') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('length', null,  ['placeholder' => 'cm', 'class' =>'form-control', 'id' => 'length']) !!}
                                       <span class="input-group-addon" id="basic-addon2">cm</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('d_label', 'Diameter') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('diameter') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('diameter', null,  ['placeholder' => 'cm', 'class' =>'form-control', 'id' => 'diameter']) !!}
                                       <span class="input-group-addon" id="basic-addon2">cm</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('porosity_label', 'Porosity') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('porosity') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('porosity', null,  ['placeholder' => 'Fraction', 'class' =>'form-control', 'id' => 'porosity']) !!}
                                       <span class="input-group-addon" id="basic-addon2">Fraction</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-default">
                        <div class="panel-heading"><b>Clay Data</b></div>
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('ilita_label', 'Illite') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('illite') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('illite', null,  ['placeholder' => '%', 'class' =>'form-control', 'id' => 'illite']) !!}
                                       <span class="input-group-addon" id="basic-addon2">%</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('kaolinite_label', 'Kaolinite') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('kaolinite') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('kaolinite', null,  ['placeholder' => '%', 'class' =>'form-control', 'id' => 'kaolinite']) !!}
                                       <span class="input-group-addon" id="basic-addon2">%</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('chlorite_label', 'Chlorite') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('chlorite') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('chlorite', null,  ['placeholder' => '%', 'class' =>'form-control', 'id' => 'chlorite']) !!}
                                       <span class="input-group-addon" id="basic-addon2">%</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('smectite_label', 'Emectite') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('emectite') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('emectite', null,  ['placeholder' => '%', 'class' =>'form-control', 'id' => 'emectite']) !!}
                                       <span class="input-group-addon" id="basic-addon2">%</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('total_clay_quantity_label', 'Total Amount of Clays') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('total_amount_of_clays') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('total_amount_of_clays', null,  ['placeholder' => '%', 'class' =>'form-control', 'id' => 'total_amount_of_clays']) !!}
                                       <span class="input-group-addon" id="basic-addon2">%</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="panel panel-default">
                        <div class="panel-heading"><b>Mineral Data</b></div>
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('quartz_label', 'Quartz') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('quartz') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('quartz', null,  ['placeholder' => '%', 'class' =>'form-control', 'id' => 'quartz']) !!}
                                       <span class="input-group-addon" id="basic-addon2">%</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    {!! Form::label('feldspar_label', 'Feldspar') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                                    <div class="input-group {{$errors->has('feldspar') ? 'has-error' : ''}}">
                                       @if($advisor === "true")
                                         <span class="input-group-btn">
                                            <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                         </span>
                                       @endif
                                       {!! Form::text('feldspar', null,  ['placeholder' => '%', 'class' =>'form-control', 'id' => 'feldspar']) !!}
                                       <span class="input-group-addon" id="basic-addon2">%</span>
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
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="calculate_initial_deposited_fines()">Calculate</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>
{!! Form::Close() !!}
@endsection


@include('layouts/advisor_fines_migration_diagnosis')
@include('layouts/modal_advisor')
@include('layouts/modal_table')
@include('layouts/phenomenological_data_tree')
@section('Scripts')
   @include('css/add_multiparametric')
   @include('js/modal_error')
   @include('js/add_fines_migration_diagnosis')
   @include('js/advisor')
   @include('js/phenomenological_data_tree')
   @include('js/validate_table')
@endsection
