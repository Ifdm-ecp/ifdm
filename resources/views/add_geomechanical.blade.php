@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
?>

@include('layouts/modal_error')
@include('layouts/advisor_geomechanical')
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky">
   <center><b>Scenario: </b>{!! $scenario->nombre !!} </br> Basin: {!! $basin->nombre !!} - Field: {!! $field->nombre !!} - Producing interval: {!!  $formation->nombre !!} - Well: {!!  $well->nombre !!} - User: {!! $user->fullName !!}</center>
</div>

</br>

{!!Form::open(['action' => ['geomechanical_diagnosis_controller@store'], 'method' => 'post'])!!}
@include('layouts/general_advisor')
<input type="hidden" name="scenary_id" id="scenary_id" value="{{ $scenario->id }}">
<div class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#general_data">General Data</a></li>
         <li><a data-toggle="tab" href="#geomechanical_properties">Geomechanical Properties</a></li>
         <li><a data-toggle="tab" href="#fracture_model">Fracture Model</a></li>
      </ul>
      <div class="tab-content">
         <div class="tab-pane active" id="general_data">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('well_azimuth_label', 'Well Azimuth') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('well_azimuth') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                             <span class="input-group-btn">
                                <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                             </span>
                           @endif
                           {!! Form::text('well_azimuth',null, ['placeholder' => '°', 'class' =>'form-control', 'id' => 'well_azimuth']) !!}
                           <span class="input-group-addon" id="basic-addon2">°</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('well_dip_label', 'Well Deviation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('well_dip') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('well_dip',null, ['placeholder' => '°', 'class' =>'form-control', 'id' => 'well_dip']) !!}
                           <span class="input-group-addon" id="basic-addon2">°</span>
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
                           {!! Form::text('well_radius',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'well_radius']) !!}
                           <span class="input-group-addon" id="basic-addon2">ft</span>
                        </div>
                     </div>
                  </div>
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
               </div>
               <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                         {!! Form::label('max_analysis_radius_label', 'Max Analysis Radius') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                         <div class="input-group {{$errors->has('max_analysis_radius') ? 'has-error' : ''}}">
                            @if($advisor === "true")
                               <span class="input-group-btn">
                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                               </span>
                            @endif
                            {!! Form::text('max_analysis_radius',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'max_analysis_radius']) !!}
                            <span class="input-group-addon" id="basic-addon2">ft</span>
                         </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                       {!! Form::label('rate_label', 'Rate') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                       <div class="input-group {{$errors->has('rate') ? 'has-error' : ''}}">
                          @if($advisor === "true")
                             <span class="input-group-btn">
                                <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                             </span>
                          @endif
                          {!! Form::text('rate',null, ['placeholder' => 'bbl/day', 'class' =>'form-control', 'id' => 'rate']) !!}
                          <span class="input-group-addon" id="basic-addon2">bbl/day</span>
                       </div>
                    </div>
                 </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('matrix_permeability_label', 'Matrix Permeability') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('matrix_permeability') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('matrix_permeability',null, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'matrix_permeability']) !!}
                           <span class="input-group-addon" id="basic-addon2">mD</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('top_label', 'Top') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('top') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('top',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'top']) !!}
                           <span class="input-group-addon" id="basic-addon2">ft</span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('netpay_label', 'Netpay') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('netpay') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('netpay',null, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'netpay']) !!}
                           <span class="input-group-addon" id="basic-addon2">ft</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('viscosity_label', 'Viscosity') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('viscosity') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('viscosity',null, ['placeholder' => 'cp', 'class' =>'form-control', 'id' => 'viscosity']) !!}
                           <span class="input-group-addon" id="basic-addon2">cp</span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('volumetric_factor_label', 'Volumetric Factor') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('volumetric_factor') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('volumetric_factor',null, ['placeholder' => 'RB/STB', 'class' =>'form-control', 'id' => 'volumetric_factor']) !!}
                           <span class="input-group-addon" id="basic-addon2">RB/STB</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                         {!! Form::label('reservoir_pressure_label', 'Reservoir Pressure') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                         <div class="input-group {{$errors->has('reservoir_pressure') ? 'has-error' : ''}}">
                            @if($advisor === "true")
                               <span class="input-group-btn">
                                  <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                               </span>
                            @endif
                            {!! Form::text('reservoir_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'reservoir_pressure']) !!}
                            <span class="input-group-addon" id="basic-addon2">psi</span>
                         </div>
                      </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="geomechanical_properties">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('poisson_ratio_label', 'Poisson Ratio') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('poisson_ratio') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('poisson_ratio',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'poisson_ratio']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('biot_coefficient_label', 'Biot Coefficient') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('biot_coefficient') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('biot_coefficient',null, ['placeholder' => '-', 'class' =>'form-control', 'id' => 'biot_coefficient']) !!}
                           <span class="input-group-addon" id="basic-addon2">-</span>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('azimuth_maximum_horizontal_stress_label', 'Azimuth Maximum Horizontal Stress') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('azimuth_maximum_horizontal_stress') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('azimuth_maximum_horizontal_stress',null, ['placeholder' => '°', 'class' =>'form-control', 'id' => 'azimuth_maximum_horizontal_stress']) !!}
                           <span class="input-group-addon" id="basic-addon2">°</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('minimum_horizontal_stress_gradient_label', 'Minimum Horizontal Stress Gradient') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('minimum_horizontal_stress_gradient') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('minimum_horizontal_stress_gradient',null, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'minimum_horizontal_stress_gradient']) !!}
                           <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('vertical_stress_gradient_label', 'Vertical Stress Gradient') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('vertical_stress_gradient') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('vertical_stress_gradient',null, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'vertical_stress_gradient']) !!}
                           <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('maximum_horizontal_stress_gradient_label', 'Maximum Horizontal Stress Gradient') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('maximum_horizontal_stress_gradient') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('maximum_horizontal_stress_gradient',null, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'maximum_horizontal_stress_gradient']) !!}
                           <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="tab-pane" id="fracture_model">
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('initial_fracture_width_label', 'Initial Fracture Width') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('initial_fracture_width') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('initial_fracture_width',null, ['placeholder' => 'μm', 'class' =>'form-control', 'id' => 'initial_fracture_width']) !!}
                           <span class="input-group-addon" id="basic-addon2">μm</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('initial_fracture_toughness_label', 'Initial Normal Fracture Stiffness') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('initial_fracture_toughness') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('initial_fracture_toughness',null, ['placeholder' => 'psi/ft', 'class' =>'form-control', 'id' => 'initial_fracture_toughness']) !!}
                           <span class="input-group-addon" id="basic-addon2">psi/ft</span>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('fracture_closure_permeability_label', 'Fracture Closure Permeability') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('fracture_closure_permeability') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('fracture_closure_permeability',null, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'fracture_closure_permeability']) !!}
                           <span class="input-group-addon" id="basic-addon2">mD</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        {!! Form::label('residual_fracture_closure_permeability_label', 'Residual Fracture Closure Permeability') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        <div class="input-group {{$errors->has('residual_fracture_closure_permeability') ? 'has-error' : ''}}">
                           @if($advisor === "true")
                              <span class="input-group-btn">
                                 <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                              </span>
                           @endif
                           {!! Form::text('residual_fracture_closure_permeability',null, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'residual_fracture_closure_permeability']) !!}
                           <span class="input-group-addon" id="basic-addon2">mD</span>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="panel panel-default">
                  <div class="panel-heading"><b>Fractures</b> @if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_fractures_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
                  <div class="panel-body">
                     <div id="fractures_table"></div>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>

</br>
<br>

<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
         {!! Form::submit('Save' , array('class' => 'maximize btn btn-success', 'name' => 'button_sw', 'id'=>'button_sw')) !!}
         {!! Form::submit('Run' , array('class' => 'maximize btn btn-primary', 'onclick' => 'verify_geomechanical_diagnosis_data();', 'name' => 'action', 'id'=>'run')) !!}
         <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
      </p>
      {!! Form::Close() !!}
   </div>
   {!! Form::hidden('fractures_table_data', '', array('id' => 'fractures_table_data')) !!}
   {!! Form::hidden('well_bottom_pressure_table_data', '', array('id' => 'well_bottom_pressure_table_data')) !!}
</div>
@include('layouts/modal_advisor')
@include('layouts/advisor_geomechanical')
@endsection

@section('Scripts')
   @include('css/add_multiparametric')
   @include('js/add_geomechanical')
   @include('js/modal_error')
   @include('js/advisor')
@endsection