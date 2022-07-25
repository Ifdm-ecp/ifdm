@extends('layouts.ProjectGeneral')

@section('title', 'Tutorial')

@section('content')
	<div class="row">
		<center><h2>{{$data->tittle}}</h2></center>
		<br><br>
		<a href="{{url('/manual/create?tagManual='.$data->id)}}" class="btn btn-primary">New Section</a>
		<br><br>
		<table class="table">
			<thead>
				<th>Name</th>
				<th>Options</th>
			</thead>
			<tbody>
				@foreach($data->manual as $manual)
					<tr>
						<td>{{$manual->tittle}}</td>
						<td>
							<a href="{{route('manual.edit', $manual->id)}}" class="btn btn-success">Edit</a>
							<button class="btn btn-danger delete" type="button" data-toggle="modal" id="{{$manual->id}}">X</button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                <h4 class="modal-title">Confirmation</h4>
		            </div>
		            <div class="modal-body">
		                <p>Do You Want To Delete This Filtration Function?</p>
		            </div>
		            <div class="modal-footer">
		                {!! Form::open(['method' => 'DELETE', 'url' => ['formation-mineralogy.destroy'], 'id' => 'formDelete']) !!}
		                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                    <button type="submit" class="btn btn-danger" id="confirm">Ok</button>
		                {!! Form::close() !!}
		            </div>
		        </div>
		    </div>
		</div>

	</div>
@endsection


@section('Scripts')

<script type="text/javascript">
	$('.delete').on('click', function(){
        var data = $(this).attr('id');
        // console.log(data);
        $('#formDelete').attr('action', '/manual/'+data);
        $('#confirmDelete').modal();
      });
</script>


@endsection


