@extends('layouts.generaldatabase')
@section('title', 'IFDM Database')

@section('content')
<div class="row">
   <div class="panel panel-default">
      <div class="panel-body">
         <b>
            <center>
               <h1>DATABASE</h1>
            </center>
         </b>
         <br>
         <p align="justify">The Database module is an information management system for the mapping and characterization of different formation damage mechanisms from production chemistry and operational variables data. This module allows big data insertion and extraction and contains practical graphical interfaces.</p>
         <p align="justify">Databases contain information about basic production chemistry, well history and reservoir data. In addition, project data of multiple users can be managed within the module. For security reasons, this module grant permission to specific users (company leaders and system administrators).</p>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-6">
      <div class="panel panel-default">
         <div class="panel-body">
            <b>
               <center>
                  <h2>Add Data</h2>
               </center>
            </b>
            <hr>
            <p align="justify">In this module, new data can be stored in the database. The data fields containing a red asterisk (<span style="color:red">*</span>) are mandatory.</p>
            <br>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('AddDataC') !!}" class="btn btn-block btn-primary col-md-12" role="button">Basin</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('AddDataC') !!}" class="btn btn-block btn-primary col-md-12" role="button">Field</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('AddFormationC') !!}" class="btn btn-block btn-primary col-md-12" role="button">Formation</a>
                  </div>
               </div>
            </div>
            <br>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('AddFormationWC') !!}" class="btn btn-block btn-primary col-md-12" role="button">Well</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('AddFormationWellC') !!}" class="btn btn-block btn-primary col-md-12" role="button">Producing Interval</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('AddMeasurementC') !!}" class="btn btn-block btn-primary col-md-12" role="button">Damage Variables</a>
                  </div>
               </div>
            </div>
            <br>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('filtration_function') !!}" class="btn btn-block btn-primary col-md-12" role="button">Filtration Function</a>
                  </div>
                  <div class="col-xs-12 col-sm-4" style="display:none;">
                     <a href="{!! url('add_filtration_function_u') !!}" class="btn btn-block btn-primary col-md-12" role="button">Universal Filt. Func.</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! route('formation-mineralogy.create') !!}" class="btn btn-block btn-primary col-md-12" role="button"><small>Formation Mineralogy</small></a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! route('pvt-global.create') !!}" class="btn btn-block btn-primary col-md-12" role="button">PVT Library </a>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
   
   <div class="col-md-6">
      <div class="panel panel-default">
         <div class="panel-body">
            <b>
               <center>
                  <h2>Data Management</h2>
               </center>
            </b>
            <hr>
            <p align="justify">Existing data, projects and scenarios can be modified and deleted in this module.</p>
            <br><br>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('listBasinC') !!}" class="btn btn-block btn-success col-md-12" role="button">Basin</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('listFieldC') !!}" class="btn btn-block btn-success col-md-12" role="button">Field</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('listFormationC') !!}" class="btn btn-block btn-success col-md-12" role="button">Formation</a>
                  </div>
               </div>
            </div>
            <br>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('listWellC') !!}" class="btn btn-block btn-success col-md-12" role="button">Well</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('listIntervalC') !!}" class="btn btn-block btn-success col-md-12" role="button">Producing Interval</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('EditMeasurementC') !!}" class="btn btn-block btn-success col-md-12" role="button">Damage Variables</a>
                  </div>
               </div>
            </div>
            <br>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('DeleteProject') !!}" class="btn btn-block btn-success col-md-12" role="button">Project</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! url('filtration_function_list') !!}" class="btn btn-block btn-success col-md-12" role="button">Filtration Function</a>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! route('formation-mineralogy.index') !!}" class="btn btn-block btn-success col-md-12" role="button"><small>Formation Mineralogy</small></a>
                  </div>
               </div>
            </div>
            <br>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xs-12 col-sm-4">
                     <a href="{!! route('pvt-global.index') !!}" class="btn btn-block btn-success col-md-12" role="button">PVT Library </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection