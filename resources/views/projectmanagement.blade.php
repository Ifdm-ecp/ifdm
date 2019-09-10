@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Database')

@section('content')
<br>

<div class="row">
   <div class="panel panel-default">
      <div class="panel-body">
         <b>
            <center>
               <h1>PROJECT MANAGEMENT</h1>
            </center>
         </b>
         <br>
         <p align="justify">In the project management module, the user can create new projects. A project is defined as a workspace where the user develops the workflow of a formation damage study. The workflow might involve the use of specialized tools for the diagnostics, discretization and remediation of different formation damage mechanisms. These specialized tools are introduced to the project via scenarios. In this way and for a given project, multiple scenarios can be created.</p>
         <br>
         <div class="row">
            <div class="col-xs-12">
               <p align="center">
                  <a href="{!! url('Project') !!}" class="btn btn-primary" role="button">Add Project</a>
                  <a href="{!! url('ScenaryC') !!}" class="btn btn-primary" role="button">Add Scenario</a>
               </p>
               {!! Form::Close() !!}
            </div>
         </div>
      </div>
   </div>
</div>


<div class="row">
   <div class="panel panel-default">
      <div class="panel-body">
         <b><center><h2>Share Scenario</h2></center></b>
         <table class="table table-striped table-bordered table-fixed-wordwrap" id="table_Scenary">
            <thead>
               <th WIDTH="20%">Name</th>
               <th WIDTH="20%">Type</th>
               <th WIDTH="30%">Shared With</th>
               <th>Action</th>
            </thead>
         </table>
      </div>
   </div>
</div>

<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Do You Want To Delete This Scenary?</p>
         </div>
         <div class="modal-footer">
            {!! Form::open(['method' => 'DELETE', 'url' => ['scenario/destroy'], 'id' => 'formDelete']) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirm">Ok</button>
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>


@include('layouts.confirm_delete')

@include('layouts/share_scenario_modal')
@endsection

@section('Scripts')
   @include('js/share_scenario')
   @include('js/delete_modal')
    <script>
      $(document).ready(function() {
         var table = $('#table_Scenary').DataTable({
            "processing": true,
            // "serverSide": true,
            ajax: "{{ url('getShareScenaryTable') }}"
         });
      });

      function delete_modal(a) {
           $('#formDelete').attr('action', window.location+'/'+a);
           $('#confirmDelete').modal();
       }
   </script>
@endsection