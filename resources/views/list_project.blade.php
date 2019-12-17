@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')

@if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
   <script>
      $(function() { 
         $('#scenarioError').modal('show');
      });
   </script>
@endif

<div id="scenarioError" class="modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
            <p>The project can't be deleted because it still has scenarios associated.</p>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div id="endDateS" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
            <p>End Date Should Not Lower Than Start Date.</p>
            </small>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div id="endDate" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
            <p>End Date Should Not Exceed The Current Date.</p>
            </small>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<div id="startDate" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
            <p>Start Date Should Not Exceed The Current Date.</p>
            </small>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>


@if (count($errors) > 0)
<div id="myModal" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Error</h4>
         </div>
         <div class="modal-body">
            <p class="text-danger">
               <small>
                  @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
            </small>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>
@endif

<div class="row">
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-body">
            <center>
               <h1>Projects</h1>
            </center>

            <hr>

            <div class="row">
               @if(\Auth::User()->office == 0)
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('companyL', 'Company') !!}
                     {!! Form::select('company', $company_all->lists('name','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick find_projects', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'company')) !!}
                  </div>
               </div>
               @endif
               @if(\Auth::User()->office != 2)
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('userL', 'User') !!}
                     <select class="find_projects selectpicker show-tick form-control"  data-live-search="true" data-width="100%" data-style="btn-default" id="userbycompany" name="userbycompany">
                        <option></option>
                        @foreach($user as $users)
                        <option value="{{ $users->id }}">{{ $users->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               @endif
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('fechaL', 'Start Date') !!}
                     {!! Form::date('dateS', null, ['class' =>'find_projects form-control', 'id' => 'dateS']); !!}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('fechaL', 'End Date') !!}
                     {!! Form::date('dateF', null, ['class' =>'find_projects form-control', 'id' => 'dateF']); !!}
                  </div>
               </div>
            </div>

            <hr>

            <div class="panel-group" id="accordion">
               @if($company[0] != null)
               <div class="panel panel-default" id="UN">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">UN</a>
                     </h4>
                  </div>
                  <div id="collapse1" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="col-md-12">
                           <div class="row">
                              <table class="table table-striped">
                                 <thead>
                                    <tr>
                                       <th>Name</th>
                                       <th>Date</th>
                                       <th>Actions</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach ($project_UN as $project)
                                    <tr>
                                       <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                       <td>{{ $project->fecha }}</td>
                                       <td>
                                          {!! Form::open(['method' => 'DELETE', 'route' => ['DeleteProject.destroy', $project->id], 'id' => 'form'.$project->id ]) !!}
                                          <form class="form-inline">
                                             <a href="{{ URL::route('DeleteProject.edit', $project->id) }}" class="btn btn-info">View</a>
                                             <a href="{{ URL::route('ProjectC.edit', $project->id) }}" class="btn btn-warning">Manage</a>
                                             <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$project->id}});">Delete</button>
                                          </form>
                                          {!! Form::close() !!}
                                       </td>
                                    </tr>
                                    @endforeach 
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @endif

               @if($company[1] != null)
               <div class="panel panel-default" id="equion">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Equion</a>
                     </h4>
                  </div>
                  <div id="collapse2" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Actions</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_equion as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>
                                             {!! Form::open(['method' => 'DELETE', 'route' => ['DeleteProject.destroy', $project->id], 'id' => 'form'.$project->id ]) !!}
                                             <form class="form-inline">
                                                <a href="{{ URL::route('DeleteProject.edit', $project->id) }}" class="btn btn-info">View</a>
                                                <a href="{{ URL::route('ProjectC.edit', $project->id) }}" class="btn btn-warning">Manage</a>
                                                <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$project->id}});">Delete</button>
                                             </form>
                                             {!! Form::close() !!}
                                          </td>
                                       </tr>
                                       @endforeach 
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @endif

               @if($company[2] != null)
               <div class="panel panel-default" id="ecopetrol">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Ecopetrol</a>
                     </h4>
                  </div>
                  <div id="collapse3" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Actions</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_ecopetrol as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>
                                             {!! Form::open(['method' => 'DELETE', 'route' => ['DeleteProject.destroy', $project->id], 'id' => 'form'.$project->id ]) !!}
                                             <form class="form-inline">
                                                <a href="{{ URL::route('DeleteProject.edit', $project->id) }}" class="btn btn-info">View</a>
                                                <a href="{{ URL::route('ProjectC.edit', $project->id) }}" class="btn btn-warning">Manage</a>
                                                <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$project->id}});">Delete</button>
                                             </form>
                                             {!! Form::close() !!}
                                          </td>
                                       </tr>
                                       @endforeach 
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @endif

               @if($company[3] != null)
               <div class="panel panel-default" id="hocol">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Hocol</a>
                     </h4>
                  </div>
                  <div id="collapse4" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Actions</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_hocol as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>
                                             {!! Form::open(['method' => 'DELETE', 'route' => ['DeleteProject.destroy', $project->id], 'id' => 'form'.$project->id ]) !!}
                                             <form class="form-inline">
                                                <a href="{{ URL::route('DeleteProject.edit', $project->id) }}" class="btn btn-info">View</a>
                                                <a href="{{ URL::route('ProjectC.edit', $project->id) }}" class="btn btn-warning">Manage</a>
                                                <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$project->id}});">Delete</button>
                                             </form>
                                             {!! Form::close() !!}
                                          </td>
                                       </tr>
                                       @endforeach 
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @endif

               @if($company[4] != null)
               <div class="panel panel-default" id="uis">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">UIS</a>
                     </h4>
                  </div>
                  <div id="collapse4" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Actions</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_uis as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>
                                             {!! Form::open(['method' => 'DELETE', 'route' => ['DeleteProject.destroy', $project->id], 'id' => 'form'.$project->id ]) !!}
                                             <form class="form-inline">
                                                <a href="{{ URL::route('DeleteProject.edit', $project->id) }}" class="btn btn-info">View</a>
                                                <a href="{{ URL::route('ProjectC.edit', $project->id) }}" class="btn btn-warning">Manage</a>
                                                <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$project->id}});">Delete</button>
                                             </form>
                                             {!! Form::close() !!}
                                          </td>
                                       </tr>
                                       @endforeach 
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @endif

               <div class="col-md-12">
                  <div class="row">
                     <div style="display:none;" id="proyectos"></div>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>

<a href="{!! url('database') !!}" class="btn btn-danger pull-right" role="button">Cancel</a>
<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Do You Want To Delete This Project?</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirm">Ok</button>
         </div>
      </div>
   </div>
</div>
@endsection


@section('Scripts')
    @include('js/list_project')
@endsection