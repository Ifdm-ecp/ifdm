@extends('layouts.generaldatabase')
@section('title', 'IFDM Home')

@section('content')
<div id="end_date_error" class="modal fade">
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

<div id="end_date_lower_error" class="modal fade">
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

<div id="start_date_exceed_error" class="modal fade">
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

<div class="row">
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-body">
            <b>
               <center>
                  <h1>IFDM</h1>
               </center>
            </b>
            <p align="justify">The Integrated Formation Damage Model (IFDM) is a software tool for the integral analysis of formation damage from diagnostics to control.</p>
            <p align="justify">The IFDM has been developed by the Hydrocarbon Reservoir Computational Laboratory of Universidad Nacional de Colombia, with the sponsorship of Ecopetrol Group and the support with the diagnostics specialized tools of Universidad Nacional de Colombia and Universidad Industrial de Santander.</p>
         </div>
      </div>
   </div>
</div>

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
                     {!! Form::label('company_label', 'Company') !!}
                     {!! Form::select('company', $company_all->lists('name','id'),null, array('placeholder' => '', 'class'=>'form-control selectpicker show-tick find_projects', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'company')) !!}
                  </div>
               </div>
               @endif
               @if(\Auth::User()->office != 2)
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('user_label', 'User') !!}
                     <select class="find_projects selectpicker show-tick form-control"  data-live-search="true" data-width="100%" data-style="btn-default" id="userbycompany" name="userbycompany">
                        <option></option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               @endif
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('start_date_label', 'Start Date') !!}
                     {!! Form::date('start_date', null, ['class' =>'find_projects form-control', 'id' => 'start_date']); !!}
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('end_date_label', 'End Date') !!}
                     {!! Form::date('end_date', null, ['class' =>'find_projects form-control', 'id' => 'end_date']); !!}
                  </div>
               </div>
            </div>

            <hr>

            <div class="panel-group" id="accordion">
               @if($company[0] != null)
               <div class="panel panel-default" id="UN">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_un">UN</a>
                     </h4>
                  </div>

                  <div id="collapse_un" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="col-md-12">
                           <div class="row">
                              <table class="table table-striped">
                                 <thead>
                                    <tr>
                                       <th>Name</th>
                                       <th>Date</th>
                                       <th>Description</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach ($project_UN as $project)
                                    <tr>
                                       <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                       <td>{{ $project->fecha }}</td>
                                       <td>{{ $project->descripcion }}</td>
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
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_equion">Equion</a>
                     </h4>
                  </div>

                  <div id="collapse_equion" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_equion as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>{{ $project->descripcion }}</td>
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
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_ecopetrol">Ecopetrol</a>
                     </h4>
                  </div>

                  <div id="collapse_ecopetrol" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_ecopetrol as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>{{ $project->descripcion }}</td>
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
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_hocol">Hocol</a>
                     </h4>
                  </div>
                  <div id="collapse_hocol" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_hocol as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>{{ $project->descripcion }}</td>
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
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_uis">UIS</a>
                     </h4>
                  </div>
                  <div id="collapse_uis" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_uis as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>{{ $project->descripcion }}</td>
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

               @if(\Auth::user()->office != 0)
               <div class="panel panel-default" id="uis">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_uis">Shared Scenarios</a>
                     </h4>
                  </div>
                  <div id="collapse_uis" class="panel-collapse collapse">
                     <div class="panel-body">
                        <div class="panel-body">
                           <div class="col-md-12">
                              <div class="row">
                                 <table class="table table-striped">
                                    <thead>
                                       <tr>
                                          <th>Name</th>
                                          <th>Date</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($project_uis as $project)
                                       <tr>
                                          <td><a href="{{ URL::route('listScenaryHome.edit' , $project->id) }}">{{ $project->nombre }}</a></td>
                                          <td>{{ $project->fecha }}</td>
                                          <td>{{ $project->descripcion }}</td>
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
                     <div style="display:none;" id="projects_id"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection


@section('Scripts')
  @include('js/js_home')
@endsection