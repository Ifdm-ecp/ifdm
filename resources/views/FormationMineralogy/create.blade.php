@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
  <?php  
     if(!isset($_SESSION)) {
     session_start();
     }
  ?>
  @include('layouts/modal_error')
  {!!Form::open(['route' => ['formation-mineralogy.store'], 'method' => 'POST', 'id' => 'form'])!!}
    <input type="hidden" name="action" value="create">
      <div class="panel panel-default">
        <div class="panel-heading"><center><h2>Mineralogy</h2></center></div>
        <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group {{$errors->has('basin') ? 'has-error' : ''}}">
                   {!! Form::label('basin', 'Basin') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                   {!! Form::select('basin', $basin ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'basin')) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group {{$errors->has('field') ? 'has-error' : ''}}">
                   {!! Form::label('field', 'Field') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                   {!! Form::select('field', [] ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'field')) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group {{$errors->has('formacion_id') ? 'has-error' : ''}}">
                   {!! Form::label('formacion_id', 'Formation') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                   {!! Form::select('formacion_id', [] ,null, array('class'=>'form-control selectpicker show-tick', 'data-live-search'=>'true', 'data-style'=>'btn-default', 'id' => 'formation')) !!}
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
                      The sum of the percentages of the minerals is of
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
                  <tr>
                    <td>
                      <select class="form-control" id="0" name="finestraimentselection[]">
                        @foreach($finesDB as $fineSelect)
                          <option {{old('finestraimentselection.0') == $fineSelect ? 'selected' : ''}}>{{$fineSelect}}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <div class="input-group">
                        <input type="text" name="percentage[]" placeholder='%' class='form-control inputFinePercentage' id="percentage0" onchange='sumarMinerales(false)' value="{{old('percentage.0')}}">
                        <span class="input-group-addon">%<span>
                      </div>
                    </td>
                    <td>
                    </td>
                  </tr>
                </tbody>
            </table>
            <br>
            <div>
              The sum of the percentages of the minerals is of 
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

      <script type="text/javascript" src="{{asset('js/basinFieldFormationSelect.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/formationMineralogy.js')}}"> </script>
  
    <script type="text/javascript">
      $(document).ready(function(){
        //cargarDataSelect(false, 'table > tbody > tr > td select', '');
      });
    {{-- archivo que carga los select dinamicos de basin field y formation--}}



      //SI HACE CLICK EL BOTON CON EL ID addRow AGREGA UN NUEVO TR CON LOS CAMPOS NECESARIOS PARA AGREGAR UN NUEVO CAMPO
      $('#addRow').on('click', function(e){
        e.preventDefault();
        var contador= parseInt($(".table tr").length)-2;
        var percentage = 'percentage.'+contador;
        var fines = 'finestraimentselection.'+contador;
        console.log(contador);
        $('table > tbody > tr:last').after('<tr><td><select class="form-control" name="finestraimentselection[]">@foreach($finesDB as $fineSelect)<option {{old("'+fines+'") == $fineSelect ? 'selected' : ''}}>{{$fineSelect}}</option>@endforeach</select></td><td><div class="input-group"><input type="text" name="percentage[]" placeholder="%"" class="form-control inputFinePercentage" onchange="sumarMinerales(false)" value="<?php old("'+percentage+'")?>"><span class="input-group-addon">%<span></div></td><td><button class="btn btn-danger borrar"> - </button></td></tr>');
      });

    </script>
@endsection