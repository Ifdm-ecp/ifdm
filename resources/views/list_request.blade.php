@extends('layouts.generaldatabase')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h1>Request List</h1>
<br>

<div class="nav">
   <div class="container">
      <div class="tabbable">
         <ul class="nav nav-tabs" data-tabs="tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#damage">Damage Variables</a></li>
            <li><a data-toggle="tab" href="#well">Well</a></li>
            <li><a data-toggle="tab" href="#interval">Producing Interval</a></li>
         </ul>

         <div class="tab-content">
            <div class="tab-pane active" id="damage">
               <div name="table" id="table">
                  <table class="table table-striped ">
                     <tr>
                        <th>Well</th>
                        <th>Damage Mechanisms</th>
                        <th>Damage Variables</th>
                        <th>Damage Configuration</th>
                        <th>Value</th>
                        <th>Monitoring Date</th>
                        <th>Comment</th>
                        <th>User</th>
                        <th>Actions</th>
                     </tr>
                     @foreach  ($damage as $damages)
                     <tr>
                        <td>{{ $damages->pozos->nombre }}</td>
                        <td>{{ $damages->mecdans->nombre }}</td>
                        @if (isset($damages->subparametro_id))
                        @if (is_numeric($damages->subparametro_id))
                        <td>{{ $damages->damag_1->nombre }}</td>
                        @else
                        <td>{{ $damages->damag_2->nombre }}</td>
                        @endif
                        @else
                        <td></td>
                        @endif
                        @if (isset($damages->configuracion_daño))
                        <td>{{ $damages->damag_conf->nombre }}</td>
                        @else
                        <td></td>
                        @endif
                        <td>{{ $damages->valor }}</td>
                        <td>{{ $damages->fecha_monitoreo }}</td>
                        <td>{{ $damages->comentario }}</td>
                        <td>{{ $damages->usuario->name }}</td>
                        <td>
                           {!! Form::open(['method' => 'DELETE', 'route' => ['requestA.destroy', $damages->id], 'id' => 'form'.$damages->id]) !!}
                           <form class="form-inline">
                              <a href="{{ URL::route('requestA.edit', $damages->id) }}" class="btn btn-primary">Save</a>
                              <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$damages->id}});">Ignore</button>
                           </form>
                           {!! Form::close() !!}
                        </td>
                     </tr>
                     @endforeach
                  </table>
               </div>
            </div>

            <div class="tab-pane" id="well">
               <div name="table" id="table">
                  <table class="table table-striped ">
                     <tr>
                        <th>Well</th>
                        <th>Variable Name</th>
                        <th>Value</th>
                        <th>Monitoring Date</th>
                        <th>Comment</th>
                        <th>User</th>
                        <th>Actions</th>
                     </tr>
                     @foreach  ($well as $wells)
                     <tr>
                        <td>{{ $wells->pozos->nombre }}</td>
                        <td>{{ $wells->variable }}</td>
                        @if (isset($wells->valor))
                        <td>{{ $wells->valor }}</td>
                        @else
                        <td><button class="btn btn-info" type="button" data-toggle="modal" OnClick="javascript: Table({{$wells->id}});">View</button></td>
                        @endif
                        <td>{{ $wells->fecha_monitoreo }}</td>
                        <td>{{ $wells->comentario }}</td>
                        <td>{{$wells->usuario->name}}</td>
                        <td>
                           {!! Form::open(['method' => 'DELETE', 'route' => ['requestA.destroy', $wells->id], 'id' => 'form'.$wells->id]) !!}
                           <form class="form-inline">
                              <a href="{{ URL::route('requestA.edit', $wells->id) }}" class="btn btn-primary">Save</a>
                              <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$wells->id}});">Ignore</button>
                           </form>
                           {!! Form::close() !!}
                        </td>
                     </tr>
                     @endforeach
                  </table>
               </div>

               <div class="modal fade" id="table_well" role="dialog" aria-labelledby="table_wellLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                           <h4 class="modal-title">Production Data</h4>
                        </div>
                        <div class="modal-body">
                           <div class="panel-body">
                              <div id="excel2" style="overflow: scroll" class="handsontable"></div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="tab-pane" id="interval">
               <div name="table" id="table">
                  <table class="table table-striped ">
                     <tr>
                        <th>Producing Interval</th>
                        <th>Variable Name</th>
                        <th>Value</th>
                        <th>Monitoring Date</th>
                        <th>Comment</th>
                        <th>User</th>
                        <th>Actions</th>
                     </tr>
                     @foreach  ($interval as $intervals)
                     <tr>
                        <td>{{ $intervals->interv->nombre }}</td>
                        <td>{{ $intervals->variable }}</td>
                        @if (isset($intervals->valor))
                        <td>{{ $intervals->valor }}</td>
                        @else
                        <td><button class="btn btn-info" type="button" data-toggle="modal" OnClick="javascript: TableInterval({{$intervals->id}});">View</button></td>
                        @endif
                        <td>{{ $intervals->fecha_monitoreo }}</td>
                        <td>{{ $intervals->comentario }}</td>
                        <td>{{$intervals->usuario->name}}</td>
                        <td>
                           {!! Form::open(['method' => 'DELETE', 'route' => ['requestA.destroy', $intervals->id], 'id' => 'form'.$intervals->id]) !!}
                           <form class="form-inline">
                              <a href="{{ URL::route('requestA.edit', $intervals->id) }}" class="btn btn-primary">Save</a>
                              <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: Mostrar({{$intervals->id}});">Ignore</button>
                           </form>
                           {!! Form::close() !!}
                        </td>
                     </tr>
                     @endforeach
                  </table>
               </div>

               <div class="modal fade" id="table_int" role="dialog" aria-labelledby="table_intLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                           <h4 class="modal-title">Relative Permeability And Capilar Pressure</h4>
                        </div>
                        <div class="modal-body">
                           <div class="panel-body">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div id="excel" style="overflow: scroll" class="handsontable"></div>
                                 </div>
                                 <div class="col-md-6">
                                    <div id="excel4" style="overflow: scroll" class="handsontable"></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<br>

<a href="{!! url('homeC') !!}"><button class="btn btn-danger pull-right"  type="button" data-toggle="modal">Cancel</button></a>

<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Do You Want To Delete This Request?</p>
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
  <script src="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.js"></script>
  <link type="text/css" rel="stylesheet" href="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.min.css">
  @include('js/list_request')
  @include('js/modal_error')
@endsection