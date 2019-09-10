@extends('layouts.editData')
@section('title', 'IFDM Database')
@section('content')
@include('layouts/modal_error')

<h2>Pvt Data Globals List</h2>

<hr>
{!!Form::open(['route' => ['pvt-global.index'], 'method' => 'GET', 'id' => 'buscadorForm']) !!}
<div class="row">
    <div class="col-md-4">
      <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
         {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
         {!! Form::select('basin', $basin , null , array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'basin')) !!}
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
         {!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
         {!! Form::select('field', [] ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'field')) !!}
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
         {!! Form::label('fromation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
         {!! Form::select('formacion_id', [] ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'formation')) !!}
      </div>
    </div>
</div> 
{!! Form::close() !!} 

<hr>
<br> 

<div class="row">
    <table class="table table-striped ">
        <thead>
            <tr>
                <th>Pvt Data Globalsy</th>
                <th>Options</th>
            </tr>  
        </thead>
        <tbody>
            @if($data->count() > 0)
                @foreach  ($data as $datos)
                    <tr>
                        <td>{{ $datos->descripcion }}</td>
                        <td>
                            <div class="form-inline">
                                <a href="{{ URL::route('pvt-global.edit', $datos->id) }}" class="btn btn-warning">Manage</a>
                                <button class="btn btn-danger delete" type="button" data-toggle="modal" id="{{$datos->id}}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2"><center><h2>No records found</h2></center></td>
                </tr>
            @endif
        </tbody>
    </table>
    @if($data->count() > 0)
        {!! $data->appends(Request::only('formacion_id'))->render() !!}
    @endif
</div>


<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Do You Want To Delete This Pvt Data Globals?</p>
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

<a href="{!! url('database') !!}"><button class="btn btn-danger pull-right"  type="button" data-toggle="modal">Cancel</button></a>


@endsection

@section('Scripts')
  @include('js/modal_error')
  <script type="text/javascript">
      $('.delete').on('click', function(){
        var data = $(this).attr('id');
        console.log(data);
        $('#formDelete').attr('action', '/pvt-global/'+data);
        $('#confirmDelete').modal();
      });
  </script>
  <script type="text/javascript">
      $(document).ready(function(){
        cargarBasin();
      });

      $('#formation').change(function(){
        $('#buscadorForm').submit();
      });

      $('#basin').change(function(){
        var basin = $(this).val();
        selectAjax('/basinField/'+basin, 'field', true, '');
      });

      $('#field').change(function(){
        var field = $(this).val();
        selectAjax('/fieldFormation/'+field, 'formation', false, '');
      });

      function cargarBasin()
      {
        var basin = $('#basin').val();
        var cargar = selectAjax('/basinField/'+basin, 'field', true, '');
      }

      function cargarField()
      {
        var field = $('#field').val();
        selectAjax('/fieldFormation/'+field, 'formation', false, '');
      }

      function selectAjax(ruta, elemento, cargar, selected)
      {
        $.getJSON(ruta, function(data){
            $("#"+elemento).empty();
            $("#"+elemento).selectpicker('refresh');
            $.each(data, function(index, value) {
                $("#"+elemento).append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            $("#"+elemento).selectpicker('refresh');
            $("#"+elemento).selectpicker('val', selected);
            if(cargar == true)
            {
              cargarField();
            }
        });
      }
  </script>
@endsection