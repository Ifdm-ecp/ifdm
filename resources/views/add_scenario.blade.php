@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
@include('layouts/modal_error')

<div class="panel panel-default">
   <div class="panel-heading">
      <h4>Scenario</h4>
   </div>

   <div class="panel-body">
      <div class="row">
         <div class="col-md-6">
            {!!Form::open(array('url' => 'ScenaryCS','id' => 'scenary_form' , 'method' => 'post'))!!}
            <div class="form-group {{$errors->has('type') ? 'has-error' : ''}}">
               {!! Form::label('type', 'Type') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}&nbsp;&nbsp;
               <span>
                  <i data-toggle="tooltip" title="Asphaltene Type" id="asphaltene_type_button" class="glyphicon glyphicon-th-list asphaltene_type_ev" style="color:black;font-size:15pt;"></i> 
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
                  <button type="button" class="btn btn-default s_scenary_mod">Scenario</button>
                  <button type="button" class="btn btn-danger s_scenary_clear" disabled>X</button>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="form-group {{$errors->has('project') ? 'has-error' : ''}}">
               {!! Form::label('project', 'Project Name') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('project', $proyectos->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'project', 'required'=>'')) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('scenary') ? 'has-error' : ''}}">
               {!! Form::label('scenary', 'Scenario Name') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::text('scenary',null, ['placeholder' => '', 'class' =>'form-control']) !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('date') ? 'has-error' : ''}}">
               {!! Form::label('date', 'Study Date (DD/MM/YY)') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::date('date', \Carbon\Carbon::now(), ['class' =>'form-control', 'id'=>'date']); !!}
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group {{$errors->has('SDescription') ? 'has-error' : ''}}">
               {!! Form::label('Scenary description', 'Description') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::textarea('SDescription',null, ['placeholder' => '', 'class' =>'form-control', 'rows' => '2']) !!}
            </div>
         </div>         
      </div>
      <hr>

      <div class="row">

         <div class="col-md-6">
            <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
               {!! Form::label('basin', 'Basin') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('basin', $cuenca->lists('nombre','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id'=>'basin')) !!}
            </div>
         </div>

         <div class="col-md-6">
            <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
               {!! Form::label('field', 'Field') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               <select class="selectpicker show-tick" data-live-search="true" data-width="100%" data-style="btn-default" id="field" name="field" >
                  <option selected disabled>-</option>
               </select>
            </div>
         </div>

      </div>

      <div class="row">

         <div class="col-md-6">
            <div class="form-group {{$errors->has('well') ? 'has-error' : ''}}">
               {!! Form::label('well', 'Well') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="well" name="well" >
                  <option selected disabled>-</option>
               </select>
            </div>
         </div>

         {{-- Inicio de formación --}}

         <div id="div_formation_wipr">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
                  {!! Form::label('formation', 'Producing Interval') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                  <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="formation" name="formation" class="form-control">
                     <option selected disabled>-</option>
                  </select>
               </div>
            </div>
         </div>

         <div id="div_formation_ipr" style="display: none;">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
                  {!! Form::label('formation_ipr', 'Producing Interval') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                  <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="formation_ipr" name="formation_ipr[]" class="form-control" multiple>
                     <option selected disabled>-</option>
                  </select>
               </div>
            </div>
         </div>         

         <div id="div_formation_multiparametric_statistical" style="display: none;">
            <div class="col-md-6">
               <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
                  {!! Form::label('formation_multiparametric_statistical', 'Formation') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                  <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="formation_multiparametric_statistical" name="formation_multiparametric_statistical[]" class="form-control" multiple>
                     <option selected disabled>-</option>
                  </select>
               </div>
            </div>
         </div>    

         <div class="col-md-6" style="display:none;">
            <div class="form-group {{$errors->has('copy_scenario') ? 'has-error' : ''}}">
               {!! Form::label('copy scenario', 'Copy Scenario') !!}&nbsp&nbsp{!! Form::checkbox('statistical', '', null, array('id'=>'copy')) !!}
               <select class="selectpicker show-tick" data-live-search="true" data-width="100%" data-style="btn-default" id="copy_scenario" name="copy_scenario" >
                  <option selected disabled>-</option>
               </select>
            </div>
         </div>
         <div class="col-md-6" id="div_Dformation" style="display:none;">
            <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
               {!! Form::label('Dformation_label', 'Drilling Dformation') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               <select class="selectpicker show-tick"  data-live-search="true" data-width="100%" data-style="btn-default" id="Dformation" name="Dformation">
                  <option selected disabled>-</option>
               </select>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-xs-12">
            <p class="pull-right">
               {!! Form::submit('Save' , array('class' => 'btn btn-primary', 'id' => "btn_sub_escenario")) !!}
               <a href="{!! url('share_scenario') !!}" class="btn btn-danger" role="button">Cancel</a>
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

<div id="aspahltene_remediation_m_" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Asphaltene Remediation</h4>
         </div>
         <div class="modal-body">
            <div class="form-group">
               {!! Form::label('asphaltene_remediation', 'Methodology') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
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
               {!! Form::label('asphaltene', 'Asphaltene Type') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
               {!! Form::select('asphaltene_type', ['Asphaltene stability analysis' => '1. Asphaltene Stability Analysis','Precipitated asphaltene analysis' => '2. Precipitated Asphaltene Analysis','Asphaltene diagnosis' => '3. Asphaltene Diagnosis'],null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'id'=>'asphaltene_type', 'onchange' => "update_duplicate_modal();") ) !!}
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
                  {!! Form::label('multiparametricType', 'Multiparametric Analysis Type') !!} {!! Form::label('*', '*', array('class' => 'red')) !!}
                  <select name="multiparametricType" id="multiparametricType" class="form-control">
                     <option value="statistical">Statistical Analysis</option>
                     <option value="analytical">Analytical Analysis</option>
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
@include('js/add_scenario')
@include('js/modal_error')

@endsection