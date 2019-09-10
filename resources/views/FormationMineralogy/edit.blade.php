@extends('layouts.editData')
@section('title', 'IFDM Project')

@section('content')
<?php  
   if(!isset($_SESSION)) {
   session_start();
   }
?>
@include('layouts/modal_error')

{!!Form::model($data, ['route' => ['formation-mineralogy.update', $data->id], 'method' => 'PATCH', 'id' => 'form'])!!}
  <input type="hidden" name="action" value="update">
    <div class="panel panel-default">
      <div class="panel-heading"><center><h2>Mineralogy</h2></center></div>
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
          <div class="row" style="padding: 20px;">
            <div class="panel-default">
              <div class="panel-heading"><center><h2>Formation Mineralogys</h2></center></div>
              <div class="panel-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                         {!! Form::label('quarts', '%Quarts') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('quarts') ? 'has-error' : ''}}">
                            {!! Form::text('quarts', null, ['placeholder' => '%quarts', 'class' =>'form-control']) !!}
                            <span class="input-group-addon" id="basic-addon2">%<span>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         {!! Form::label('feldspar', '%Feldspar') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('feldspar') ? 'has-error' : ''}}">
                            {!! Form::text('feldspar', null, ['placeholder' => '%feldspar', 'class' =>'form-control']) !!}
                            <span class="input-group-addon" id="basic-addon2">%<span>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         {!! Form::label('clays', '%Clays') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                          <div class="input-group {{$errors->has('clays') ? 'has-error' : ''}}">
                            {!! Form::text('clays', null, ['placeholder' => '%clays', 'class' =>'form-control']) !!}
                            <span class="input-group-addon" id="basic-addon2">%<span>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div>
                    La suma de porcentajes de los minerales es de 
                    <span id="mineralogyPercentage">0%.</span>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"><center><h2>Fines Percentage</h2></center></div>
      <div class="panel-body">
        <div class="col-md-8 col-md-offset-2">
          <table class="table table-bordered">
              <thead>
                  <th><center>Fines</center></th>
                  <th><center>Percentage</center></th>
                  <th>Remove</th>
              </thead>
              <tbody>
                @if($data->finePercentage->count() > 0)
                  @foreach($data->finePercentage as $fines)
                  <tr>
                    <td>
                      <select class="form-control" name="finestraimentselection[]">
                        @foreach($finesDB as $fineSelect)
                          <option {{$fines->fines == $fineSelect ? 'selected' : ''}}>{{$fineSelect}}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <div class="input-group">
                        <input type="text" name="percentage[]" placeholder='%' class='form-control inputFinePercentage' onchange='sumarMinerales(false)' value="{{old('percentage.0') ? old('percentage.0') : $fines->percentage}}">
                        <span class="input-group-addon">%<span>
                      </div>
                    </td>
                    <td><button class="btn btn-danger borrar"> - </button></td>
                  </tr>

                  @endforeach
                @else
                  <tr style="display: none;" >
                  </tr>
                @endif
              </tbody>
          </table>
          <br>
          <div>
            La suma de porcentajes de los minerales es de 
            <span id="finesMineralogyPercentage">0%.</span>
          </div>
          <br>
          <button class="btn btn-info" id="addRow">Add Mineralogy</button>
        </div>
      </div>
    </div>


   <div class="row">
      <div class="col-xs-12">
         <p class="pull-right">
            {!! Form::submit('Save' , array('class' => 'btn btn-primary', 'id' => 'save')) !!}
            <a href="{!! url('database') !!}" class="btn btn-danger" role="button">Cancel</a>
         </p>
      </div>
   </div>

{!! Form::Close() !!}

<span class="red"> </span>
@endsection

@section('Scripts')
    @include('js/modal_error')

    {{-- archivo que carga los select dinamicos de basin field y formation--}}
    <script type="text/javascript" src="{{asset('js/formationMineralogy.js')}}"> </script>
    <script type="text/javascript">
      $(document).ready(function(){
        cargarBasin();
        cargarSelectedSelects();
        changeInputFormationMineralogy(false);
        sumarMinerales(false);
        cargarDataSelect(false, '#0', '');
        @if($data->finePercentage->count() > 0)
          @foreach($data->finePercentage as $finesJs)
            cargarDataSelect(false, '#{{$finesJs->finestraimentselection}}', '{{$finesJs->finestraimentselection}}');
          @endforeach
        @endif
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

      function cargarSelectedSelects()
      {
        //alert({{$data->formacion->campos->id}});
        $('#basin').selectpicker('refresh');
        $('#basin').val({{$data->formacion->campos->cuenca->id}});
        $('#field').selectpicker('refresh');
        $('#field').val({{$data->formacion->campos->id}});
        $('#formation').selectpicker('refresh');
        $('#formation').val({{$data->formacion->id}});
      }
      
      //SI HACE CLICK EL BOTON CON EL ID addRow AGREGA UN NUEVO TR CON LOS CAMPOS NECESARIOS PARA AGREGAR UN NUEVO CAMPO
      $('#addRow').on('click', function(e){
        e.preventDefault();
        $('table > tbody > tr:last').after('<tr><td><select name="finestraimentselection[]" class="form-control selectpicker selectFinesMineralogy" data-live-search="true" data-size="5" ><option>Albite</option><option>Analcime</option><option>Anhydrite</option><option>Ankeritec</option><option>AnkeriteIM</option><option>Baryte</option><option>Bentonite</option><option>Biotite</option><option>Brucite</option><option>Calcite</option><option>Cast</option><option>Celestine</option><option>Chloritec</option><option>Chamosite</option><option>Chabazite</option><option>ChloriteIM</option><option>Chloritem</option><option>Dolomite</option><option>Emectite</option><option>Gibbsite</option><option>Glauconite</option><option>Halite</option><option>Hematite</option><option>Heulandite</option><option>Illite</option> <option>Kaolinite</option><option>Magnetite</option><option>Melanterite</option><option>Microcline</option><option>Muscovite</option> <option>Natrolite</option><option>Orthoclase</option><option>PlagioClase</option><option>Pyrite</option><option>Pyrrhotite</option><option>Sideritec</option><option>SideriteIM</option><option>Stilbite</option><option>Troilite</option><option>Quarts</option></select></td><td><div class="input-group"><input type="text" name="percentage[]" placeholder="%"" class="form-control inputFinePercentage" onchange="sumarMinerales(false)" value="<?php old("'+percentage+'")?>"><span class="input-group-addon">%<span></div></td><td><button class="btn btn-danger borrar"> - </button></td></tr>');
      });
    </script>
@endsection