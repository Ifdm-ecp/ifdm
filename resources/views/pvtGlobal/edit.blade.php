@extends('layouts.editData')
@section('title', 'Pvt Globals')

@section('content')
<?php
   if(!isset($_SESSION)) {
   session_start();
   }
?>
@include('layouts/modal_error')

{!!Form::model($data, ['route' => ['pvt-global.update', $data->id], 'method' => 'PATCH', 'id' => 'formPvtGlobal'])!!}
    <div class="panel panel-default">
      <div class="panel-heading"><center><h2>Pvt Data Global</h2></center></div>
      <div class="panel-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
                 {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                 {!! Form::select('basin', $basin , $data->formacion->campos->cuenca->id, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'basin')) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
                 {!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                 {!! Form::select('field', [] , $data->formacion->campos->id, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'field')) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group {{$errors->has('formation') ? 'has-error' : ''}}">
                 {!! Form::label('fromation', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                 {!! Form::select('formacion_id', [] ,$data->formacion->id, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'formation')) !!}
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group {{$errors->has('descripcion') ? 'has-error' : ''}}">
                {!! Form::label('descripcion', 'Description') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="col-md-6">
               <div class="form-group {{$errors->has('saturation_pressure') ? 'has-error' : ''}}">
                  {!! Form::label('saturation_pressure', 'Saturation Pressure') !!}
                  <div id = "alert_top"></div>
                  <div class="input-group">
                     {!! Form::text('saturation_pressure',null, ['placeholder' => 'Psi', 'class' =>'form-control']) !!}
                     <span class="input-group-addon" id="top-addon">Psi</span>
                  </div>
               </div>
            </div>
          </div>
          <br>
          <div class="row" >
            <div class="row" style="display: flex; justify-content: center;">
              <div id="table_field_pvt" style="overflow: scroll" class="handsontable"></div>
            </div>
            <br>
            <div class="row pull-right">
              <button class="btn btn-primary" id="plot" style="margin-right: 50px">Plot</button>
            </div>
            <div class="row">
              <div id="pvtField"></div>
            </div>

            {!! Form::hidden('pvt_table', '', ['class' => 'form-control']) !!}
          </div>
      </div>
    </div>

   <div class="row">
      <div class="col-xs-12">
         <p class="pull-right">
            {!! Form::submit('Save' , ['class' => 'btn btn-primary', 'id' => 'save']) !!}
            <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
         </p>
      </div>
   </div>

{!! Form::Close() !!}

<span class="red"> </span>
@endsection

@section('Scripts')
    @include('js/modal_error')
    <link rel="stylesheet" media="screen" href="http://nextgen.pl/_/scroll/dist/jquery.handsontable.full.css">
    <script src="http://nextgen.pl/_/scroll/dist/jquery.handsontable.full.js"></script>

    <script type="text/javascript" src="{{asset('js/pvtGlobal.js')}}"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        cargarBasin();
        loadDataTabla();
        tablaPvt();
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
        $("#basin").selectpicker('refresh');
        $("#basin").selectpicker('val', {{$data->formacion->campos->cuenca->id}});
        var basin = {{$data->formacion->campos->cuenca->id}};
        var cargar = selectAjax('/basinField/'+basin, 'field', true, {{$data->formacion->campos->id}});
      }

      function cargarField()
      {
        var field = {{$data->formacion->campos->id}};
        selectAjax('/fieldFormation/'+field, 'formation', false, {{$data->formacion->id}});
      }

      function selectAjax(ruta, elemento, cargar, selected)
      {
        //alert(ruta);
        $.getJSON(ruta, function(data){
            //console.log(data);
            $("#"+elemento).empty();
            $("#"+elemento).selectpicker('refresh');
            $.each(data, function(index, value) {
                $("#"+elemento).append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            $("#"+elemento).selectpicker('refresh');
            $("#"+elemento).selectpicker('val', selected);
            //$('#'+elemento).append('response');
            if(cargar == true)
            {
              cargarField();
            }
        });
      }
    </script>
    <script type="text/javascript">
      function loadDataTabla(){
        $('input[name = pvt_table]').val(JSON.stringify({{json_encode($datos)}}));
      }
    </script>
@endsection
