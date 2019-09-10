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

{!!Form::model($asphaltenes_d_diagnosis, array('route' => array('asphaltenesDiagnosis.update', $asphaltenes_d_diagnosis->id), 'method' => 'PATCH'), array('role' => 'form'))!!}
<input type="hidden" name="id_scenary" id="id_scenary" value="{{ !is_null($duplicateFrom) ? $duplicateFrom : $scenary->id }}">

@include('layouts/general_advisor')

<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#general_data">General Data</a></li>
         <li><a data-toggle="tab" href="#pvt_data">PVT Data</a></li>
         <li><a data-toggle="tab" href="#historical_data">Historical Data</a></li>
         <li><a data-toggle="tab" href="#asphaltenes_data">Asphaltenes Data</a></li>
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
                                 {!! Form::text('drainage_radius',$asphaltenes_d_diagnosis->drainage_radius, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'drainage_radius']) !!}
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
                                 {!! Form::text('net_pay',$asphaltenes_d_diagnosis->net_pay, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'net_pay']) !!}
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
                                 {!! Form::text('wellbore_radius',$asphaltenes_d_diagnosis->wellbore_radius, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'wellbore_radius']) !!}
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
                                 {!! Form::text('compressibility',$asphaltenes_d_diagnosis->compressibility, ['placeholder' => '1/psi', 'class' =>'form-control', 'id' => 'compressibility']) !!}
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
                                 {!! Form::text('initial_pressure',$asphaltenes_d_diagnosis->initial_pressure, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'initial_pressure']) !!}
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
                                 {!! Form::text('initial_porosity',$asphaltenes_d_diagnosis->initial_porosity, ['placeholder' => 'decimal', 'class' =>'form-control', 'id' => 'initial_porosity']) !!}
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
                                 {!! Form::text('initial_permeability',$asphaltenes_d_diagnosis->initial_permeability, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'initial_permeability']) !!}
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
                                 {!! Form::text('average_pore_diameter',$asphaltenes_d_diagnosis->average_pore_diameter, ['placeholder' => 'um', 'class' =>'form-control', 'id' => 'average_pore_diameter']) !!}
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
                                 {!! Form::text('asphaltene_particle_diameter',$asphaltenes_d_diagnosis->asphaltene_particle_diameter, ['placeholder' => 'um', 'class' =>'form-control', 'id' => 'asphaltene_particle_diameter']) !!}
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
                                 {!! Form::text('asphaltene_apparent_density',$asphaltenes_d_diagnosis->asphaltene_apparent_density, ['placeholder' => 'um', 'class' =>'form-control', 'id' => 'asphaltene_apparent_density']) !!}
                                 <span class="input-group-addon" id="basic-addon2">um</span>
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
                      <br><br>
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
                    <br><br>
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
                     <div class="row">
                         <div class="col-md-6">
                           <div class="form-check">
                             {!! Form::checkbox('perform_production_projection_selector',0,null,array('class'=>'form-check-input', 'id'=>'perform_production_projection_selector')) !!}&nbsp<label class="form-check-label" for="perform_production_projection_selector">Perform Production Projection</label>
                           </div>
                         </div>
                     </div>
                  </div>
               </div>
               <div class="panel panel-default" id="production_projection">
                  <div class="panel-heading"><b>Production Projection</b></div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">
                           <div class="form-group" id="historical_oil">
                              <div class="form-group {{$errors->has('perform_historical_projection_oil') ? 'has-error' : ''}}">
                                 {!! Form::label('historical_projection_label', 'Please, choose a projection data to be included in the historical data') !!}
                                 {!! Form::select('perform_historical_projection_oil', ['exponential' => 'Exponential', 'hyperbolic'=>'Hyperbolic'], null, array('class'=>'form-control', 'id'=>'perform_historical_projection_oil')) !!}
                              </div>
                           </div>
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
                     </div>
                     <div class="row col-md-6">  
                         <button type="button" class="btn btn-primary" onclick="perform_production_projection()">Calculate Production Projection</button>
                    </div>
                    <div class="row">
                      <div id="oil_projection_chart"></div>
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
                   <div id="asphaltenes_table"></div><br><br>
                   <button class="btn btn-primary pull-right" onclick="plot_asphaltene_table()">Plot</button> 
                   {!! Form::hidden('value_asphaltenes_table', '', array('class' => 'form-control', 'id' => 'value_asphaltenes_table')) !!}
                   {!! Form::hidden('asphaltenes_data_range_flag', '', array('class' => 'form-control', 'id' => 'asphaltenes_data_range_flag')) !!}
                   <div class="row">
                     <div id="graphic_asphaltene_table"></div>
                  </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-xs-12">
                  <p class="pull-right">
                     {!! Form::submit('Run' , array('class' => 'save_table btn btn-primary')) !!}
                     <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
                  </p>
                  {!! Form::Close() !!}
               </div>
            </div>
         </div>
      </div>
   </div>
   {!! Form::submit('Save' , array('class' => 'save_table_wr btn btn-success pull-right', 'id' => 'button_wr', 'name' => 'button_wr')) !!}
</div>

</br>
<br>



@include('layouts/modal_advisor')
@include('layouts/modal_table')
@include('layouts/advisor_asphaltenes_diagnosis')
@endsection



@section('Scripts')
   @include('css/add_multiparametric')
   @include('js/modal_error')
   @include('js/edit_asphaltenes_diagnosis')
   @include('js/advisor')
   @include('js/validate_table')
@endsection


