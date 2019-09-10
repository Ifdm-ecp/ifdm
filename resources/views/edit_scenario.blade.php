@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
@include('layouts/modal_error')

<div class="panel panel-default">
   <div class="panel-heading">
      <h4>Scenario - <small>{{$scenary->nombre}}</small></h4>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('type') ? 'has-error' : ''}}">
               {!! Form::label('type', 'Type') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}&nbsp;&nbsp;
               <span>

                  <i data-toggle="tooltip" title="Asphaltene Type" id="asphaltene_type_button" class="glyphicon glyphicon-th-list asphaltene_type_ev" style="color:black;font-size:15pt;display: none;"></i> 
                  <i data-toggle="tooltip" title="Asphaltene Type" id="asphaltene_rem_button" class="glyphicon glyphicon-th-list asphaltene_rem_ev" style="color:black;font-size:15pt;display: none;"></i> 

                  <i data-toggle="tooltip" title="Multiparametric Analysis Type" id="multiparametric_type_button" class="glyphicon glyphicon-th-list multiparametric_type_ev" style="color:black;font-size:15pt; display: none;"></i>
               </span>
               <select class="form-control" id="type" name="type" >
                  <option selected disabled>-</option>
                  <option value = "Multiparametric">Multiparametric Analysis </option>
                  <option value = "Dissagregation">Skin Dissagregation Analysis </option>
                  <option value = "IPR">IPR Analysis</option>
                  <optgroup label="Skin factor diagnostic">
                     <option value = "Drilling">Drilling And Completion </option>
                     <option value = "Asphaltene precipitation">Asphaltenes Precipitation</option>
                     <option value = "Swelling and fines migration">Fines Migration, Depositation, and Swelling</option>
                     <option value = "Geomechanics">Geomechanics</option>
                     <option value = "Parafines precipitation" disabled>Parafines Precipitation</option>
                  </optgroup>
                  <optgroup label="Skin factor remediation">
                     {{-- <option value = "Asphaltene precipitation" disabled>Asphaltenes Precipitation</option> --}}
                     {{-- <option value = "Swelling and fines migration" disabled>Fines Migration, Depositation, and Swelling</option> --}}
                     <option value = "Fines remediation">Fines Remediation</option>
                     <option value = "Asphaltene remediation">Asphaltene Remediation</option>
                     <option>Fines Treatment Selection</option>
                  </optgroup>
               </select>
            </div>
         </div>

         <div class="col-md-6">
            <label for="escenario_dup">Duplicate from...</label>
            <div class="input-group">
               <input type="text" class="form-control" disabled id="escenario_dup" name="escenario_dup" aria-label="...">
               <input type="hidden" class="form-control" id="id_escenario_dup" name="id_escenario_dup" aria-label="...">
               <div class="input-group-btn">
                  <button type="button" class="btn btn-default s_scenary_mod">Scenary</button>
               </div>
            </div>
         </div>

      </div>
      <div class="row">

         <div class="col-md-6">
            <div class="form-group {{$errors->has('project') ? 'has-error' : ''}}">
               {!! Form::label('project', 'Project Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="project" name="project" >
                  <option selected disabled>-</option>
                  @foreach ($proyecto as $proyectos)
                  <option value = "{!! $proyectos->id!!}">{!! $proyectos->nombre!!}</option>
                  @endforeach
               </select>
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group {{$errors->has('scenary') ? 'has-error' : ''}}">
               {!!Form::model($proyecto, array('route' => array('ScenaryC.update', $scenary->id), 'method' => 'PATCH'), array('role' => 'form'))!!}
               {!! Form::label('scenary', 'Scenario Name') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('scenary',$scenary->nombre, ['placeholder' => '', 'class' =>'form-control']) !!}
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('date') ? 'has-error' : ''}}">
               {!! Form::label('date', 'Study Date') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::date('date', $scenary->fecha, ['class' =>'form-control', 'id'=>'date']); !!}
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group {{$errors->has('SDescription') ? 'has-error' : ''}}">
               {!! Form::label('Scenary description', 'Description') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::textarea('SDescription',$scenary->descripcion, ['placeholder' => '', 'class' =>'form-control', 'rows' => '2']) !!}
            </div>
         </div>
      </div>
      <hr>
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
               {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="basin" name="basin" >
                  <option selected disabled>-</option>
                  @foreach ($cuenca as $cuencas)
                  <option value = "{!! $cuencas->id!!}">{!! $cuencas->nombre!!}</option>
                  @endforeach
               </select>
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
               {!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               <select class="selectpicker show-tick" data-live-search="true" data-width="100%" data-style="btn-default" id="field" name="field" >
                  <option selected disabled>-</option>
                  @foreach ($campo as $campos)
                  <option value = "{!! $campos->id!!}">{!! $campos->nombre!!}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('well') ? 'has-error' : ''}}">
               {!! Form::label('well', 'Well') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="well" name="well" >
                  <option selected disabled>-</option>
                  @foreach ($pozo as $pozos)
                  <option value = "{!! $pozos->id!!}">{!! $pozos->nombre!!}</option>
                  @endforeach
               </select>
            </div>
         </div>

         <div id="div_formation_wipr">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
                  {!! Form::label('formation', 'Producing Interval') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="formation" name="formation" class="form-control">
                     <option selected disabled>-</option>
                  </select>
               </div>
            </div>
         </div>

         <div id="div_formation_ipr" style="display: none;">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
                  {!! Form::label('formation_ipr', 'Producing Interval') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="formation_ipr" name="formation_ipr[]" class="form-control" multiple>
                     <option selected disabled>-</option>
                  </select>
               </div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-xs-12">
            {!! Form::hidden('id', $scenary->id) !!}
            <p class="pull-right">
               {!! Form::submit('Next', array('class' => 'btn btn-primary', $scenary->id)) !!}
               <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
            </p>
            <p class="pull-right">
               <div class="hidden"><button type="button" class="btn btn-default" disabled>Copy</button></div>
            </p>

         </div>
      </div>
   </div>
</div>

<div id="datea" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
                  <p>Date Should Not Exceed The Current Date.</p>
               </small>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>


<div id="datea" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
                  <p>Date Should Not Exceed The Current Date.</p>
               </small>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div id="aspahltene_remediation_m" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Asphaltene Remediation</h4>
         </div>
         <div class="modal-body">
            <div class="form-group">
               {!! Form::label('asphaltene_remediation', 'Methodology') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('asphaltene_remediation', ['Based upon asphaltene diagnosis model' => '1. Based Upon Asphaltene Diagnosis Model','Volumetric changes' => '2. Volumetric Changes'],null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'id'=>'asphaltene_remediation') ) !!}
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div id="aspahltene_type" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Asphaltene type</h4>
         </div>
         <div class="modal-body">
            <div class="form-group">
               {!! Form::label('asphaltene', 'Asphaltene Type') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('asphaltene_type', ['Asphaltene stability analysis' => '1. Asphaltene Stability Analysis','Precipitated asphaltene analysis' => '2. Precipitated Asphaltene Analysis','Asphaltene diagnosis' => '3. Asphaltene Diagnosis'],null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'id'=>'asphaltene_type') ) !!}
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div id="MultiparametricModal" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Multiparametric Analysis Type</h4>
         </div>
         <div class="modal-body">
            <div class="form-group">
               <div class="dropdown">
                  {!! Form::label('multiparametricType', 'Multiparametric Analysis Type') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                  <select name="multiparametricType" class="form-control">
                     <option value="statistical">Statistical Analysis</option>
                     <option value="analytical">Analytical Analysis</option>
                     <option value="completeMultiparametric">Complete Multiparametric</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-multiparametricType">Ok</button>
         </div>
      </div>
   </div>
</div>

<div id="scenaryExtModal" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Duplicate From</h4>
         </div>
         <div class="modal-body">

            <div id="Tree" class="tab-pane">
               <br>
               <div class="row">
                  <div class="form-inline d-none" id="text_tree">
                     {!! Form::text('value_db',null, ['class' =>'form-control', 'id' => 'value_db']) !!}
                     <button type="button" class="btn btn-default set_value" data-dismiss="modal">Set</button>
                  </div>
               </div>
               <div class="row">
                  <div id="treeDuplicateScenary"></div>
               </div>
            </div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
{!! Form::Close() !!}
@endsection


@section('Scripts')
@include('js/template/duplicate_scenary')
@include('js/edit_scenario')
@include('js/modal_error')
@endsection