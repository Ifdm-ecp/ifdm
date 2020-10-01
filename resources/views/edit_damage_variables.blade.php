@extends('layouts.editData')
@section('title', 'IFDM Database')
@section('content')
@include('layouts/modal_error')

<h1>Edit Damage Variables</h1>
<br/>

<div class="row">
   <div class="col-md-4">
      <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
         {!! Form::label('basin', 'Basin') !!}
         {!! Form::select('basin', $basins->lists('nombre', 'id'), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick', 'id' => 'basin', 'data-live-search' => 'true', 'data-style' => 'btn-default')) !!}
      </div>
   </div>
   <div class="col-md-4">
      <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
         {!! Form::label('Field', 'Field') !!}
         {!! Form::select('field', array(), null, array('placeholder' => '', 'class' => 'form-control selectpicker show-tick', 'id' => 'field', 'data-live-search' => 'true', 'data-style' => 'btn-default')) !!}
      </div>
   </div>
   <div class="col-md-4">
      <div class="form-group {{$errors->has('well') ? 'has-error' : ''}}">
         {!! Form::label('well', 'Well') !!}
         {!! Form::select('well', array(), null, array('class'=>'form-control selectpicker show-tick', 'id' => 'well', 'data-live-search' => 'true', 'data-style' => 'btn-default')) !!}
      </div>
   </div>
</div>

<br> 

<div id="subparameter_tabs" style="display:none" class="nav">
   <div class="tabbable">
      <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
         <li class="active"><a data-toggle="tab" href="#mineral_scales_c" id="mineral_scales">Mineral Scales</a></li>
         <li><a data-toggle="tab" href="#fine_blockage_c" id="fine_blockage">Fine Blockage</a></li>
         <li><a data-toggle="tab" href="#organic_scales_c" id="organic_scales">Organic Scales</a></li>
         <li><a data-toggle="tab" href="#relative_permeability_c" id="relative_permeability">Relative Permeability</a></li>
         <li><a data-toggle="tab" href="#induce_damage_c" id="induce_damage">Induced Damage</a></li>
         <li><a data-toggle="tab" href="#geomechanical_damage_c" id="geomechanical_damage">Geomechanical Damage</a></li>
      </ul>
   </div>
   <div class="tab-content">
      <div id="mineral_scales_c" class="tab-pane active">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Mineral scales</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('SIC', 'Scale Index Of CaCO3') !!}   
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="MSP1_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('BaSO4', 'Scale Index Of BaSO4') !!}
                  </div>
               </div>
      
               <br>   
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="MSP2_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('SIS', 'Scale Index Of Iron Scales') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="MSP3_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Backflow [Ca]') !!}
                  </div>
               </div>
      
               <br> 
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="MSP4_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Backflow [Ba]') !!}
                  </div>
               </div>
      
               <br>  
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="MSP5_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="fine_blockage_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Fine Blockage</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('ACW', '[Al] on Produced Water') !!}
                  </div>
               </div>

               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="FBP1_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('SCW', '[Si] on produced water') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="FBP2_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('CRF', 'Critical Radius derived from maximum critical velocity, Vc') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="FBP3_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('MF', 'Mineralogy Factor') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="FBP4_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('CPF', 'Mass of crushed proppant inside Hydraulic Fractures') !!}
                  </div>
               </div>
      
               <br> 
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="FBP5_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="organic_scales_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Organic Scales</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'CII Factor: Colloidal Instability Index') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="OSP1_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Volume of HCL pumped into the formation') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="OSP2_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Cumulative Gas Produced') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="OSP3_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="OSP4_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'De Boer Criteria') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="OSP5_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="relative_permeability_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Relative Permeability</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Number Of Days Below Saturation Pressure') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="KrP1_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Difference between current reservoir pressure and saturation pressure') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="KrP2_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Cumulative Water Produced') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="KrP3_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('', 'Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k))') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="KrP4_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="induce_damage_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Induced Damage</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('MDF', 'Gross Pay') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="IDP1_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('PDF', 'Total polymer pumped during Hydraulic Fracturing') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="IDP2_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('IFF', 'Total volume of water based fluids pumped into the well') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="IDP3_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>
      
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('ML', 'Mud Losses') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="IDP4_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="geomechanical_damage_c" class="tab-pane">
         <br>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h4>Geomechanical Damage</h4>
            </div>
      
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('PNP', 'Percentage of Net Pay exihibiting Natural') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="GDP1_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('BHFP', 'Drawdown, i.e, reservoir pressure minus BHFP') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="GDP2_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('KH', 'Ratio of KH)matrix + fracture / KH)matrix') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="GDP3_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
      
               <hr>

               <div class="row">
                  <div class="col-md-12">
                     {!! Form::label('KH', 'Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP') !!}
                  </div>
               </div>
      
               <br>
      
               <div class="row">
                  <div class="col-xs-12">
                     <table id="GDP4_table" class="table table-striped table-bordered table-fixed-wordwrap dataTable" style="width:100%">
                        <thead>
                           <tr>
                              <th>Value</th>
                              <th>Date</th>
                              <th>Comment</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div id="modal_notification" class="modal fade" style="z-index: 99999">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 id="modal_notification_title" class="modal-title">Temp</h4>
         </div>
         <div class="modal-body">

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

@endsection

@section('Scripts')
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
   @include('js/edit_damage_variables')
   @include('js/frontend_validator')
   @include('js/frontend_rules/damage_variables')
   @include('js/modal_error')
   @include('js/modal_error_frontend')
   @include('css/modal_error_frontend')
@endsection