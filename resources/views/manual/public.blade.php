@extends('layouts.general')

@section('title', 'Tutorial')

@section('sidebar')
    <div class="row" id="acordeon">
    	<select class="form-control" id="tagSelects">
    		@foreach($data as $tag)
    			<option value="{{$tag->id}}" {{$tag->id == $primer->id ? 'selected' : ''}} >{{$tag->tittle}}</option>
    		@endforeach
    	</select>
    	<br>
    	@foreach($data as $tag)
    	@if($tag->manual->count() > 0)
	    	<ul class="list-group {{$tag->id == $primer->id ? '' : 'ocultar'}}" id="tagManual{{$tag->id}}">
    		@foreach($tag->manual as $manual)
	    		<li>
	    			<a 
	    				href="javascript:showTutorial({{$manual->id}})" 
	    				class="btn btn-link btn-block" 
	    				data-toggle="popover" 
      					data-trigger="hover"
      					title="{!! $manual->tittle !!}"
      					data-content="">
	    					{!!str_limit($manual->tittle, 33)!!}  
	    					<span class="glyphicon glyphicon-chevron-right pull-right"></span>
	    			</a>
	    		</li>
	    	@endforeach
	    	</ul>
	    @endif
	    @endforeach
    </div>
@stop

@section('content')
<div class="row">
	<div class="panel panel-default">
		<div class="panel-heading">
			<center><h2 id="tutorial-tittle"></h2></center>
		</div>
		<div class="panel-body tutorialCuerpo">
			<br>
			<div class="col-md-10 col-md-offset-1" id="tutorial-body" ></div>
		</div>
		<div class="panel-footer">
			<center><ul class="pagination"></ul></center>
		</div>
	</div>
</div>


@endsection


@section('Scripts')
<style type="text/css">
	.ocultar{
		display: none;
	}

	.tutorialCuerpo{
		overflow: hidden;
		overflow-y: scroll;
		height: 550px;
	}

	#accordion, #acordeon{

		 position: fixed;
	}

	.panel > .panel-heading, .panel > .panel-footer, #acordeon > select{
	    background-image: none;
	    background-color: #030a13c9;
	    color: white;
	}

	#acordeon > select > option
	{
		background-image: none;
	    background-color:white;
	    color: #030a13c9;
	}

	.list-group > li{
		list-style: none;
		margin-top: 8px;
		padding: 4px;
		border-radius: 5px;
	}

	.list-group
	{
		padding: 2px;
	}

	#acordeon{
		-webkit-box-shadow: 2px 13px 16px -9px rgba(0,0,0,0.75);
		-moz-box-shadow: 2px 13px 16px -9px rgba(0,0,0,0.75);
		box-shadow: 2px 13px 16px -9px rgba(0,0,0,0.75);
	}
	
</style>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		showTutorial({{$primer->manual->first()->id}});
		dataPopover();
	});

	function dataPopover()
	{
		$('[data-toggle="popover"]').popover({
	      html: true,
	      placement: 'bottom'
	    });
	}

	$('#tagSelects').on('change', function(){
		tutorialAside($('#tagSelects').val());
	});

	function tutorialAside(tag)
	{
		$('.list-group').hide();
		$('#tagManual'+tag).show();
	}

	function showTutorial(id)
	{
		$.get('/manual-public/'+id, function(data){
			var datos = data.data;
			$('#tutorial-tittle').text(datos.tittle);
			$('#tutorial-body').html(datos.body);

			$('.pagination').html('');
			if(data.anterior == null)
			{
				$('.pagination').append('<li class="disabled"><a>&laquo;</a></li>');
			}else{
				$('.pagination').append('<li><a href="javascript:showTutorial('+data.anterior.id+');">&laquo;</a></li>');
			}

			if(data.siguiente == null)
			{
				$('.pagination').append('<li class="disabled"><a>&raquo;</a></li>');
			}else{
				$('.pagination').append('<li><a href="javascript:showTutorial('+data.siguiente.id+');">&raquo;</a></li>');
			}
		})
	}
</script>

@endsection


