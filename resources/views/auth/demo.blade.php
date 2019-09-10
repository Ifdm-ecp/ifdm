@extends('layouts.ProjectGeneral')
@section('title', 'Result Fines Treatment Selection')

@section('content')

	<div class="row div-table">
		<table class="table">
			<thead>
				<th class="stage"><h2 class="text-center tittle bg-info">Stage</h2></th>
				<th class="treament"><h2 class="text-center tittle bg-info">Treament</h2></th>
				<th class="additive"><h2 class="text-center tittle bg-info">Additive</h2></th>
			</thead>
			<tbody>
				@foreach($result as $stage)
					@foreach($stage['data'] as $treatment)
						@foreach($treatment['additive'] as $additive)
							<tr>
								<td><h3>{{$stage['name']}}</h3></td>
								<td><b>{{$treatment['tittle']}}</b></td>
								@if(array_key_exists('data', $additive))
									<td>
										<span class="glyphicon glyphicon-chevron-down text-primary"></span>
										{{$additive['name']}}
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
										<span class="glyphicon glyphicon-chevron-down text-primary"></span>
										{{$additive['name']}}
									</td>
								@endif
							</tr>
						@endforeach
					@endforeach
				@endforeach
			</tbody>
		</table>
@stop

@section('Scripts')
	<link href="https://fonts.googleapis.com/css?family=Ranga" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('css/fts.css')}}">
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

@stop
		

