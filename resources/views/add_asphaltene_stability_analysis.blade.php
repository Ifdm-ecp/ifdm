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

{!!Form::open(['action' => ['add_asphaltene_stability_analysis_controller@store', 'scenaryId' => $scenaryId], 'method' => 'post'])!!}

@include('layouts/general_advisor')

<div class="nav">
  <div class="tabbable">
    <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
      <li class="active"><a data-toggle="tab" href="#stability_analysis">Stability Analysis</a></li>
    </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="stability_analysis">
        <div class="panel-body">
           <div class="panel panel-default">
              <div class="panel-heading"><b>Components Data </b>@if($advisor === "true")<span><i class="glyphicon glyphicon-info-sign show-table-advisor" id="code_table_components_table" style="color:black;font-size:15pt;"></i></span>@endif</div>
              <div class="panel-body">
                 <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('components', 'Components') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          {!! Form::select('components[]', [
                             'N2' => 'N2',
                             'CO2' => 'CO2',
                             'H2S' => 'H2S',
                             'C1' => 'C1',
                             'C2' => 'C2',
                             'C3' => 'C3',
                             'IC4' => 'IC4',
                             'NC4' => 'NC4',
                             'IC5' => 'IC5',
                             'NC5' => 'NC5',
                             'NC6' => 'NC6',
                             'NC7' => 'NC7',
                             'NC8' => 'NC8',
                             'NC9' => 'NC9',
                             'NC10' => 'NC10',
                             'NC11' => 'NC11',
                             'NC12' => 'NC12',
                             'NC13' => 'NC13',
                             'NC14' => 'NC14',
                             'NC15' => 'NC15',
                             'NC16' => 'NC16',
                             'NC17' => 'NC17',
                             'NC18' => 'NC18',
                             'NC19' => 'NC19',
                             'NC20' => 'NC20',
                             'NC21' => 'NC21',
                             'NC22' => 'NC22',
                             'NC23' => 'NC23',
                             'NC24' => 'NC24',
                             'FC6' => 'FC6',
                             'FC7' => 'FC7',
                             'FC8' => 'FC8',
                             'FC9' => 'FC9',
                             'FC10' => 'FC10',
                             'FC11' => 'FC11',
                             'FC12' => 'FC12',
                             'FC13' => 'FC13',
                             'FC14' => 'FC14',
                             'FC15' => 'FC15',
                             'FC16' => 'FC16',
                             'FC17' => 'FC17',
                             'FC18' => 'FC18',
                             'FC19' => 'FC19',
                             'FC20' => 'FC20',
                             'FC21' => 'FC21',
                             'FC22' => 'FC22',
                             'FC23' => 'FC23',
                             'FC24' => 'FC24',
                             'FC25' => 'FC25',
                             'FC26' => 'FC26',
                             'FC27' => 'FC27',
                             'FC28' => 'FC28',
                             'FC29' => 'FC29',
                             'FC30' => 'FC30',
                             'FC31' => 'FC31',
                             'FC32' => 'FC32',
                             'FC33' => 'FC33',
                             'FC34' => 'FC34',
                             'FC35' => 'FC35',
                             'FC36' => 'FC36',
                             'FC37' => 'FC37',
                             'FC38' => 'FC38',
                             'FC39' => 'FC39',
                             'FC40' => 'FC40',
                             'FC41' => 'FC41',
                             'FC42' => 'FC42',
                             'FC43' => 'FC43',
                             'FC44' => 'FC44',
                             'FC45' => 'FC45',
                             'SO2' => 'SO2',
                             'H2' => 'H2',
                             'Plus +' => 'Plus +'
                             ],old('components'), array('class'=>'selectpicker show-tick', 'data-live-search'=>'true', 'id'=>'components', 'data-width'=>'100%', 'data-style'=>'btn-success', 'multiple'=>'multiple')
                            ) !!}
                       </div>
                    </div>
                     <div class="col-md-6" style="overflow: auto;">
                        <div id="components_table"></div>
                        {!! Form::hidden('value_components_table', '', array('class' => 'form-control', 'id' => 'value_components_table')) !!}
                        {!! Form::hidden('sum_zi_components_table', '', array('class' => 'form-control', 'id' => 'sum_zi_components_table')) !!} 
                        {!! Form::hidden('zi_range_flag_components_table', '', array('class' => 'form-control', 'id' => 'zi_range_flag_components_table')) !!}
                     </div>
                 </div>
              </div>
           </div>

           <div class="panel panel-default">
              <div class="panel-heading"><b>SARA Analysis</b></div>
              <div class="panel-body">
                 <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('saturated_label', 'Saturated') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('saturated') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('saturated',(empty($asphaltenes_d_precipitated_analysis) ? null : $asphaltenes_d_precipitated_analysis->saturate), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'saturated']) !!}
                             <span class="input-group-addon" id="basic-addon2">% Weight</span>
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('aromatics_label', 'Aromatics') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('aromatics') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('aromatics',(empty($asphaltenes_d_precipitated_analysis) ? null : $asphaltenes_d_precipitated_analysis->aromatic), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'aromatics']) !!}
                             <span class="input-group-addon" id="basic-addon2">% Weight</span>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('resines_label', 'Resines') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('resines') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('resines',(empty($asphaltenes_d_precipitated_analysis) ? null : $asphaltenes_d_precipitated_analysis->resine), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'resines']) !!}
                             <span class="input-group-addon" id="basic-addon2">% Weight</span>
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('asphaltenes_label', 'Asphaltenes') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('asphaltenes') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('asphaltenes',(empty($asphaltenes_d_precipitated_analysis) ? null : $asphaltenes_d_precipitated_analysis->asphaltene), ['placeholder' => '% Weight', 'class' =>'form-control sara_data', 'id' => 'asphaltenes']) !!}
                             <span class="input-group-addon" id="basic-addon2">% Weight</span>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="row">
                   <div class="col-md-6">
                     <div class="form-group">
                       <h5><b>Total SARA </b><span class="label label-default" id="total_sara"></span></h5>
                     </div>
                   </div>
                 </div>
              </div>
           </div>

        </div>

        <div class="panel-body">
           <div class="panel panel-default">
              <div class="panel-heading"><b>Saturation Data</b></div>
              <div class="panel-body">
                 <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('reservoir_initial_pressure_label', 'Reservoir Initial Pressure') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('reservoir_initial_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('reservoir_initial_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'reservoir_initial_pressure']) !!}
                             <span class="input-group-addon" id="basic-addon2">psi</span>
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('bubble_pressure_label', 'Bubble Pressure') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('bubble_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('bubble_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'bubble_pressure']) !!}
                             <span class="input-group-addon" id="basic-addon2">psi</span>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('density_at_reservoir_temperature_label', 'Density At Reservoir Temperature') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('density_at_reservoir_temperature') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('density_at_reservoir_temperature',null, ['placeholder' => 'g/cc', 'class' =>'form-control', 'id' => 'density_at_reservoir_temperature']) !!}
                             <span class="input-group-addon" id="basic-addon2">g/cc</span>
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('current_reservoir_pressure_label', 'Current Reservoir Pressure') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('current_reservoir_pressure') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('current_reservoir_pressure',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'current_reservoir_pressure']) !!}
                             <span class="input-group-addon" id="basic-addon2">psi</span>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                          {!! Form::label('api_gravity_label', 'API Gravity') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('api_gravity') ? 'has-error' : ''}}">
                              @if($advisor === "true")
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button>
                                </span>
                              @endif
                             {!! Form::text('api_gravity',null, ['placeholder' => '°API', 'class' =>'form-control', 'id' => 'api_gravity']) !!}
                             <span class="input-group-addon" id="basic-addon2">°API</span>
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


</br>
<br>

<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
        {!! Form::submit('Save' , array('class' => 'save_table_wr btn btn-success', 'id' => 'button_wr', 'name' => 'button_wr')) !!}
         {!! Form::submit('Run' , array('class' => 'save_table btn btn-primary')) !!}
         <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
      </p>
      {!! Form::Close() !!}
   </div>
</div>


@include('layouts/modal_table')
@include('layouts/modal_advisor')
@include('layouts/advisor_asphaltene_stability_analysis')
@endsection
@section('Scripts')
  @include('css/add_multiparametric')
   @include('js/modal_error')
   @include('js/add_asphaltene_stability_analysis')
   @include('js/advisor')
   @include('js/validate_table')
@endsection