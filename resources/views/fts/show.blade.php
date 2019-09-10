@extends('layouts.ProjectGeneral')
@section('title', 'Result Fines Treatment Selection')

@section('content')
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky" tabindex='1' style="padding: 0.5ex;
    background-color: #333;
    color: #fff;
    width: 100%;
    font-size: 1.5em;
    border-radius: 0.5ex;">
  <center>
        <b>Scenario: </b>{!! $scenary->nombre !!} </br> Basin: {!! $scenary->cuenca->nombre !!} - Field: {!! $scenary->campo->nombre !!} - Producing interval: {!!  $scenary->formacionxpozo->nombre !!} - Well: {!!  $scenary->pozo->nombre !!} - User: {!! $scenary->user->name !!}
  </center>
</div>
<br>
@if(is_null($db->status_wr) || $db->status_wr)
	<div class="row">
		<div class="alert alert-info" role="alert">
			<ul>
				<li>The software does not include compatibility of treatments between stages.</li>
			</ul>
		</div>
		<div class="alert alert-info" role="alert">
			<ul>
				<li>The choice of each option in each stage is at the user's criterion.</li>
			</ul>
		</div>
		<div class="alert alert-info" role="alert">
			<ul>
				<li>Score: Quantifies the response confidence of the selection software. If the result is one hundred percent, it means that the information on all the specific conditions of application of the treatment is known.</li>
			</ul>
		</div>
	</div>
@endif
	<div class="row div-table">
		@if(is_null($db->status_wr) || $db->status_wr)
		<table class="table table-bordered">
			<thead>
				<th class="stage"><h2 class="text-center tittle bg-info">Stage</h2></th>
				<th class="treament"><h2 class="text-center tittle bg-info">Treament</h2></th>
				<th class="additive"><h2 class="text-center tittle bg-info">Additive</h2></th>
			</thead>
			<tbody>
				@foreach($result as $stage)
						<?php $conteo = 1 ?>
						<?php $stage['data'] = is_null($stage['data']) ? [] : $stage['data']; ?>
					@foreach($stage['data'] as $key => $treatment)
						@foreach($treatment['additive'] as $additive)
							<tr>
								<td><h3>{{$stage['name']}}</h3></td>
								<td>
									@if($treatment['tittle'] != '')
									<span><b>Option {{$key+1}}:</b></span>
									<br>
									<b style="margin-left: 80px;">{{$treatment['tittle']}}</b><br>
										<span class="text-primary" style="margin-left: 80px;">
											<b>Score: </b>
											{{$treatment['score']}}
											%.
										</span>
									@endif
								</td>
								@if(array_key_exists('data', $additive))
									<td>
										@if($additive['name'] != '')
											<span class="glyphicon glyphicon-chevron-down text-primary"></span>
											{{$additive['name']}}
										@endif
										<ul>
											@foreach($additive['data'] as $data)
									  			<li>
									  				<span class="glyphicon glyphicon-chevron-right text-primary"></span>
									  				{{$data}}
									  			</li>
									  		@endforeach
										</ul>
									</td>
								@else
									<td>
										@if($additive['name'] != '')
											<span class="glyphicon glyphicon-chevron-down text-primary"></span>
											{{$additive['name']}}

										@endif
									</td>
								@endif
							</tr>
						@endforeach
					@endforeach
				@endforeach
			</tbody>
		</table>
		@else
		<div class="jumbotron">
			<center>
				<span>Run has not been executed, there is no data to show.</span>
			</center>
		</div>
		@endif
	</div>
	<br>
	<div class="row pull-right">
		<a href="{{route('fts.edit', $db->escenario_id )}}" class="btn btn-warning">Edit</a>
	</div>
@stop

@section('Scripts')
	<link href="https://fonts.googleapis.com/css?family=Ranga" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('css/fts.css')}}">
	@if(is_null($db->status_wr) || $db->status_wr)
	<script type="text/javascript" src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>
	<style type="text/css">
		ul > li{
			 list-style:none;
		}

		.tittle{
			padding: 8px;
			border-radius: 5px;
		}

		.stage{
			width:20%
		}

		.treament{
			width:35%
		}

		.additive{
			width:45%
		}

		table {
		  border-collapse: separate;
		}

	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			var table = $('.table').DataTable({
			   	'rowsGroup': [0,1,2],
			   	"paging":   false,
		        "ordering": false,
		        "info":     false,
		        "searching":     false
			});
		});
	</script>
@endif
@stop
		

