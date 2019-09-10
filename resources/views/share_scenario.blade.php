@extends('layouts.generaldatabase')
@section('title', 'Share Scenario')


@section('content')
<center><h2>Share Scenario</h2></center>

<div class="panel panel-default">
    <div class="panel-body">
		<div class="col-sm-12">
		    <table class="table table-striped table-bordered table-fixed-wordwrap">
		       <tr>
		          <th>Name</th>
		          <th>Description</th>
		          <th>Shared With</th>
		          <th>Action</th>
		       </tr>
		       @foreach  ($scenarios as $scenario)
		       {!! Form::open(['method' => 'DELETE', 'route' => ['share_scenario.destroy', $scenario->id], 'id' => 'form'.$scenario->id  ]) !!}
		       <tr>
		          <td>{{ $scenario->nombre }}</td>
		          <td>{{ $scenario->descripcion }}</td>		          
		          <td>
		           	@if (array_key_exists($scenario->id, $shared_scenario_list))
		           		@foreach ($shared_scenario_list[$scenario->id] as $shared_user)
		           			@if(end($shared_scenario_list[$scenario->id]) == $shared_user)
		           				{{ $shared_user }}
		           			@else
		           				{{ $shared_user.";"}}
		           			@endif
		           			
		           		@endforeach
		           	@endif
		          </td>
		          <td class="col-sm-2">
                    <div align="center" class="row">
                    	<button type="button" class="btn btn-default edit-button" data-target="#edit_scenario" data-id="{{$scenario->id}}" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></button>
                        <button type="button" class="btn btn-info share-button" data-target="#shared_scenario" data-id="{{$scenario->id}}" data-toggle="modal">Share</button>
                        <button class="btn btn-danger" type="button" data-toggle="modal" OnClick="javascript: delete_modal({{$scenario->id}}, 'scenario');">Delete</button>
                    </div>
		          </td>
		       </tr>
		       {!! Form::Close() !!}
		       @endforeach
		    </table>
		</div>
    </div>
</div>
@include('layouts.confirm_delete')

@include('layouts/share_scenario_modal')
@endsection
@section('Scripts')
    @include('js/share_scenario')
    @include('js/delete_modal')
@endsection