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

{!!Form::open(['action' => ['add_asphaltenes_diagnosis_controller@store', 'scenaryId' => $scenaryId], 'method' => 'post', 'id' => 'DiagnosisAsphaltenesForm'])!!}

@include('layouts/general_advisor')

<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#general_data" id="general_data_tab" onclick="switchTab()">General Data</a></li>
         <li><a data-toggle="tab" href="#pvt_data" id="pvt_data_tab" onclick="switchTab()">PVT Data</a></li>
         <li><a data-toggle="tab" href="#historical_data" id="historical_data_tab" onclick="switchTab()">Historical Data</a></li>
         <li><a data-toggle="tab" href="#asphaltenes_data" id="asphaltenes_data_tab" onclick="switchTab()">Asphaltenes Data</a></li>
      </ul>

      <div class="tab-content">
         <div class="tab-pane active" id="general_data">
            <div class="panel-body">
               <div class="panel panel-default">
                  <div class="panel-heading"><b>General Data</b></div>
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
                                 {!! Form::text('drainage_radius',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'drainage_radius']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('net_pay_label', 'Net Pay') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('net_pay') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                  @endif
                                 {!! Form::text('net_pay',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'net_pay']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('wellbore_radius_label', 'Wellbore Radius') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('wellbore_radius') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                  @endif
                                 {!! Form::text('wellbore_radius',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'wellbore_radius']) !!}
                                 <span class="input-group-addon" id="basic-addon2">ft</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('compressibility_label', 'Compressibility') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('compressibility') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                  @endif
                                 {!! Form::text('compressibility',null, ['placeholder' => '1/psi', 'class' =>'form-control', 'id' => 'compressibility']) !!}
                                 <span class="input-group-addon" id="basic-addon2">1/psi</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('initial_pressure_label', 'Initial Pressure') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('initial_pressure') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                  @endif
                                 {!! Form::text('initial_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'initial_pressure']) !!}
                                 <span class="input-group-addon" id="basic-addon2">psi</span>
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
                                 {!! Form::text('initial_porosity',null, ['placeholder' => 'decimal', 'class' =>'form-control', 'id' => 'initial_porosity']) !!}
                                 <span class="input-group-addon" id="basic-addon2">decimal</span>
                              </div>
                           </div>
                        </div>
                     </div>
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
                                 {!! Form::text('initial_permeability',null, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'initial_permeability']) !!}
                                 <span class="input-group-addon" id="basic-addon2">mD</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('average_pore_diameter_label', 'Average Pore Diameter') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('average_pore_diameter') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                  @endif
                                 {!! Form::text('average_pore_diameter',null, ['placeholder' => 'um', 'class' =>'form-control', 'id' => 'average_pore_diameter']) !!}
                                 <span class="input-group-addon" id="basic-addon2">um</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('asphaltene_particle_diameter_label', 'Asphaltene Particle Diameter') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('asphaltene_particle_diameter') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                  @endif
                                 {!! Form::text('asphaltene_particle_diameter',null, ['placeholder' => 'um', 'class' =>'form-control', 'id' => 'asphaltene_particle_diameter']) !!}
                                 <span class="input-group-addon" id="basic-addon2">um</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              {!! Form::label('asphaltene_apparent_density_label', 'Asphaltene Apparent Density') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                              <div class="input-group {{$errors->has('asphaltene_apparent_density') ? 'has-error' : ''}}">
                                 @if($advisor === "true")
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                    </span>
                                  @endif
                                 {!! Form::text('asphaltene_apparent_density',null, ['placeholder' => 'um', 'class' =>'form-control', 'id' => 'asphaltene_apparent_density']) !!}
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
                  <div class="panel-heading"><b>PVT Data</b> @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_pvt_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
                  <div class="panel-body" style="overflow: auto;">
                      <div id="pvt_table"></div>
                      {!! Form::hidden('value_pvt_table', '', array('class' => 'form-control', 'id' => 'value_pvt_table')) !!}
                      {!! Form::hidden('pvt_data_range_flag', '', array('class' => 'form-control', 'id' => 'pvt_data_range_flag')) !!}
                     <br>
                     <div class="row">
                        <div class="col-md-12">
                           <button class="btn btn-primary pull-right" onclick="plot_pvt_table()" type="button">Plot</button>
                        </div>
                     </div>
                     <div class="row">
                        <div id="pvt_density_chart"></div>
                        <div id="pvt_oil_viscosity_chart"></div>
                        <div id="pvt_oil_volumetric_factor_chart"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="historical_data">
            <div class="panel-body">
               <div class="panel panel-default">
                  <div class="panel-heading"><b>Historical Data</b> @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_historical_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
                  <div class="panel-body" style="overflow: auto;">
                    <div id="historical_table"></div>
                    {!! Form::hidden('value_historical_table', '', array('class' => 'form-control', 'id' => 'value_historical_table')) !!}
                    <br>
                     <div class="row">
                        <div class="col-md-12">
                           <button class="btn btn-primary pull-right" onclick="plot_historical_table()" type="button">Plot</button>   
                        </div>
                     </div>
                     <div class="row">
                        <div id="graphic_historical_bopd_table"></div>
                     </div>
                     <div class="row">
                        <div id="graphic_historical_asphaltenes_table"></div>
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
                                 {!! Form::select('perform_historical_projection_oil', ['without' => 'Without Projection', 'exponential' => 'Exponential', 'hyperbolic'=>'Hyperbolic'], null, array('class'=>'form-control', 'id'=>'perform_historical_projection_oil')) !!}
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
                                    {!! Form::date('final_date', \Carbon\Carbon::now()->format('Y-m-d'), ['class' =>'form-control', 'id'=>'final_date']); !!}
                                </div>
                              @else
                                <div class="form-group {{$errors->has('final_date') ? 'has-error' : ''}}">
                                   @if($advisor === "true")
                                     <span class="input-group-btn">
                                        <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                     </span>
                                   @endif
                                    {!! Form::date('final_date', \Carbon\Carbon::now()->format('Y-m-d'), ['class' =>'form-control', 'id'=>'final_date']); !!}
                                </div>
                              @endif
                           </div>
                        </div> 
                        <div  class="col-md-6">
                           <br>
                           <button class="btn btn-primary btn-block" onclick="perform_production_projection()" style="margin-top: 5px" type="button">Calculate Projection</button>
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

         <div class="tab-pane" id="asphaltenes_data">
            <div class="panel-body">
               <div class="panel panel-default">
                  <div class="panel-heading"><b>Asphaltenes Data</b> @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_asphaltenes_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
                  <div class="panel-body" style="overflow: auto;">
                     <div class="row">
                        <div class="col-md-12">
                           <div id="asphaltenes_table"></div>
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-md-12">
                           <button class="btn btn-primary pull-right" onclick="plot_asphaltene_table()">Plot</button>  
                           {!! Form::hidden('value_asphaltenes_table', '', array('class' => 'form-control', 'id' => 'value_asphaltenes_table')) !!}
                           {!! Form::hidden('asphaltenes_data_range_flag', '', array('class' => 'form-control', 'id' => 'asphaltenes_data_range_flag')) !!}
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div id="graphic_asphaltene_table"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="scenario-buttons">
   <div class="col-md-6" align="left">
      {!! Form::submit('Save' , array('class' => 'save_table_wr btn btn-success', 'id' => 'button_wr', 'name' => 'button_wr')) !!}
      <a href="{!! url('share_scenario') !!}" class="btn btn-danger">Cancel</a>
   </div>
   <div class="col-md-6" align="right">
      <button type="button" class="btn btn-primary" id="prev_button" style="display: none" onclick="tabStep('prev');">Previous</button>
      <button type="button" class="btn btn-primary" id="next_button" onclick="tabStep('next');">Next</button>
      {!! Form::submit('Run' , array('class' => 'btn btn-primary save_table', 'style' => 'display: none', 'id' => 'run_calc')) !!}
   </div>
</div>
{!! Form::Close() !!}

</br>
<br>



@include('layouts/modal_advisor')
@include('layouts/modal_table')
@include('layouts/advisor_asphaltenes_diagnosis')
@endsection



@section('Scripts')
   @include('css/add_multiparametric')
   @include('js/modal_error')
   @include('js/add_asphaltenes_diagnosis')
   @include('js/advisor')
   @include('js/validate_table')
@endsection


