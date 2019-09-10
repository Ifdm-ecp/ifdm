@extends('layouts.ProjectGeneral')

@section('title', 'IFDM Project')

@section('content')
<?php  
  if(!isset($_SESSION)) {
  session_start();
}
?>
@if (session()->has('mensaje') && false)

<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Success</h4>
            </div>
            <div class="modal-body">
                <p class="text-danger">
                    <small>
                      <p>{{ session()->get('mensaje') }}</p>
                    </small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

@endif
@if (count($errors) > 0)

<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Error</h4>
            </div>
            <div class="modal-body">
                <p class="text-danger">
                    <small>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                    </small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

@endif

<div onload="multiparametrico();">
    <div id="sticky-anchor"  class="col-md-6"></div>
    <div id="sticky"><center>Scenario: {!! $_SESSION['esc'] !!} </br> Basin: {!! $_SESSION['basin'] !!} - Field: {!! $_SESSION['field'] !!} - Producing interval: {!!  $_SESSION['formation'] !!} - Well: {!!  $_SESSION['well'] !!}asdfasdf</center></div>
    <p></p>
    <div id="sticky2"><center>This is the beta version of the IPR module. Use it under your own responsibility. </center></div>


    </br>


    <br>
    <div class="panel panel-default">
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Well Info</h4></div>
        <div class="panel-body">
            <div id="Prod" class="panel-collapse collapse in">
                {!!Form::open(array('route' => array('IPR.update', $IPR->id), 'method' => 'put', 'role' => 'form'))!!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('well radius', 'Well Radius (*)') !!}
                            <div class="input-group {{$errors->has('wellR') ? 'has-error' : ''}}">
                                {!! Form::text('radio_pozo',$IPR->radio_pozo, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_pozo']) !!}
                                <span class="input-group-addon" id="basic-addon2">ft</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('drainage radius', 'Reservoir Drainage Radius (*)') !!}
                            <div class="input-group {{$errors->has('drainageR') ? 'has-error' : ''}}">
                                {!! Form::text('radio_drenaje_yac',$IPR->radio_drenaje_yac, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'radio_drenaje_yac']) !!}
                                <span class="input-group-addon" id="basic-addon2">ft</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('Reservoir Pressure') ? 'has-error' : ''}}">
                            {!! Form::label('presion de yacimiento', 'Current reservoir pressure (*)') !!}
                            <div class="input-group">
                                {!! Form::text('presion_yacimiento',$IPR->presion_yacimiento, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_yacimiento']) !!}
                                <span class="input-group-addon" id="basic-addon2">psi</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('skin', 'Skin (*)') !!}
                            <div class="input-group {{$errors->has('Skin ') ? 'has-error' : ''}}">
                                {!! Form::text('factor_dano',$IPR->factor_dano, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'factor_dano']) !!}
                                <span class="input-group-addon" id="basic-addon2">[-]</span>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>
    </div>
    

     <br>
    <div class="panel panel-default">
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Product"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production Data</h4></div>
            <div class="panel-body">
                <div id="Product" class="panel-collapse collapse in">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('oil rate', 'Oil Rate (*)') !!}
                                <div class="input-group {{$errors->has('Oil Rate') ? 'has-error' : ''}}">
                                    {!! Form::text('tasa_flujo',$IPR->tasa_flujo, ['placeholder' => 'bbls/day', 'class' =>'form-control', 'id' => 'tasa_flujo']) !!}
                                    <span class="input-group-addon" id="basic-addon2">bbls/day</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('flowing pressure', 'BHP (*)') !!}
                                <div class="input-group {{$errors->has('Bottomhole Flowing Pressure') ? 'has-error' : ''}}">
                                    {!! Form::text('presion_fondo',$IPR->presion_fondo, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_fondo']) !!}
                                    <span class="input-group-addon" id="basic-addon2">psi</span>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('bsw', 'BSW (*)') !!}
                                <div class="input-group {{$errors->has('BSW') ? 'has-error' : ''}}">
                                    {!! Form::text('bsw',$IPR->bsw, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'bsw']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[-]</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('gor', 'GOR (*)') !!}
                                <div class="input-group {{$errors->has('gor') ? 'has-error' : ''}}">
                                    {!! Form::text('gor',$IPR->gor, ['placeholder' => 'MMSCF/STB', 'class' =>'form-control', 'id' => 'gor']) !!}
                                    <span class="input-group-addon" id="basic-addon2">MMSCF/STB</span>
                                </div>
                            </div>
                        </div>
                    </div>             
                </div>
            </div>
    </div>

    <br>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Fluid"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Rock Properties</h4></div>
        <div class="panel-body">
            <div id="Fluid" class="panel-collapse collapse in">
  

                <br>
        <div class="panel panel-default {{$errors->has('MSFormation') ? 'has-error' : ''}}">
                    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#BasicP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a>Basic Petrophysics</h4></div>
                    <div class="panel-body">
                        <div id="BasicP" class="panel-collapse collapse in">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('Absolute Permeability @ Original Pressure') ? 'has-error' : ''}}">
                                        {!! Form::label('permeabilidad absoluta inicial', 'Absolute Permeability @ Original Reservoir Pressure (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('permeabilidad_abs_ini',$IPR->permeabilidad_abs_ini, ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad_abs_ini']) !!}
                                            <span class="input-group-addon" id="basic-addon2">md</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('Original Pressure') ? 'has-error' : ''}}">
                                        {!! Form::label('presion inicial', ' Original Pressure (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('presion_inicial',$IPR->presion_inicial, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'presion_inicial']) !!}
                                            <span class="input-group-addon" id="basic-addon2">psi</span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('Reservoir Thickness') ? 'has-error' : ''}}">
                                        {!! Form::label('espesor del reservorio', 'Reservoir Thickness (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('espesor_reservorio',$IPR->espesor_reservorio, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'espesor_reservorio']) !!}
                                            <span class="input-group-addon" id="basic-addon2">ft</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!--<div class="form-group {{$errors->has('Permeability Module') ? 'has-error' : ''}}">
                                        {!! Form::label('modulo permeabilidad', 'Permeability Module (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('modulo_permeabilidad',null, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'modulo_permeabilidad']) !!}
                                            <span class="input-group-addon" id="basic-addon2">psi<sup>-1</sup></span>
                                        </div>
                                    </div>-->
                                </div>
                            
                            </div>
                            
                            <fieldset>
                              <legend>
                               {!! Form::radio('check_permeability_module', 'use_permeability_module', false, ["id" => "use_permeability_module"]) !!}
                                Use Permeability Module
                              </legend>
                              <div class="col-md-6 use_permeability_module">
                                    <div class="form-group {{$errors->has('Permeability Module') ? 'has-error' : ''}}">
                                        {!! Form::label('modulo permeabilidad', 'Permeability Module (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('modulo_permeabilidad',$IPR->modulo_permeabilidad, ['placeholder' => 'psi', 'class' =>'form-control', 'id' => 'modulo_permeabilidad']) !!}
                                            <span class="input-group-addon" id="basic-addon2">psi<sup>-1</sup></span>
                                        </div>
                                    </div>
                              </div>
                            
                            </fieldset>
                            
                            
                            <fieldset>
                              <legend>
                                {!! Form::radio('check_permeability_module', 'calculate_permeability_module', false, ["id" => "calculate_permeability_module"]) !!}
                                Calculate Permeability Module
                              </legend>
                              
                              <div class="row">
                                
                                 <div class="col-md-6 calculate_permeability_module">
                                    <div class="form-group {{$errors->has('Absolute Permeability') ? 'has-error' : ''}}">
                                        {!! Form::label('permeabilidad', 'Absolute Permeability (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('permeabilidad',$IPR->permeabilidad, ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad']) !!}
                                            <span class="input-group-addon" id="basic-addon2">md</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 calculate_permeability_module">
                                    <div class="form-group {{$errors->has('Porosity') ? 'has-error' : ''}}">
                                        {!! Form::label('porosidad', ' Porosity (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('porosidad',$IPR->porosidad, ['placeholder' => '%', 'class' =>'form-control', 'id' => 'porosidad']) !!}
                                            <span class="input-group-addon" id="basic-addon2">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6 calculate_permeability_module">
                                    <div class="form-group {{$errors->has('Rock Type') ? 'has-error' : ''}}">
                                        {!! Form::label('tipo de roca', 'Rock Type (*)') !!}
 
                                            <select name="tipo_roca" class="form-control" id="tipo_roca">
                                                <option value="0"></option>
                                                @if($IPR->tipo_roca == '1')
                                                    <option value="1" selected>Consolidated</option>
                                                @else
                                                    <option value="1">Consolidated</option>
                                                @endif
                                                @if($IPR->tipo_roca == '2')
                                                    <option value="2" selected>Unconsolidated</option>
                                                @else
                                                    <option value="2" >Unconsolidated</option>
                                                @endif
                                                @if($IPR->tipo_roca == '3')
                                                    <option value="3" selected>Microfractured</option>
                                                @else
                                                    <option value="3">Microfractured</option>
                                                @endif
                                            </select>
                                            
                                  
                                    </div>
                                </div>
                            </div>
                            </fieldset>
                            
                            <!--<div class="row">
                                
                                 <div class="col-md-6">
                                    <div class="form-group {{$errors->has('Absolute Permeability') ? 'has-error' : ''}}">
                                        {!! Form::label('permeabilidad', 'Absolute Permeability (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('permeabilidad',$formacion->permeabilidad, ['placeholder' => 'md', 'class' =>'form-control', 'id' => 'permeabilidad']) !!}
                                            <span class="input-group-addon" id="basic-addon2">md</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('Porosity') ? 'has-error' : ''}}">
                                        {!! Form::label('porosidad', ' Porosity (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('porosidad',$formacion->porosidad, ['placeholder' => '%', 'class' =>'form-control', 'id' => 'porosidad']) !!}
                                            <span class="input-group-addon" id="basic-addon2">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('Rock Type') ? 'has-error' : ''}}">
                                        {!! Form::label('tipo de roca', 'Rock Type (*)') !!}
 
                                            <select name="tipo_roca" class="form-control" id="tipo_roca">
                                                <option value="0"></option>
                                                <option value="1">Consolidated</option>
                                                <option value="2">Unconsolidated</option>
                                                <option value="3">Microfractured</option>
                                            </select>
                                            
                                  
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
                <br>
        <div class="panel panel-default">
            <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Permeability_Relative"><span class="chevron_toggleable1 glyphicon glyphicon-chevron-down pull-right"></span></a> Permeability Relative</h4></div>
            <div class="panel-body">
                <div id="Permeability_Relative" class="panel-collapse collapse in">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type='hidden' id='basin' name='basin' value="{!!$_SESSION['basin']!!}">
                                <input type='hidden' id='field' name='field' value="{!!$_SESSION['field']!!}">
                                <input type='hidden' id='formation' name='formation' value="{!! $_SESSION['formation'] !!}">
                                <input type='hidden' id='well' name='well' value="{!! $_SESSION['well'] !!}">

                                {!! Form::label('exponente_corey_petroleo', 'Corey Exponent-no (*)') !!}
                                <div class="input-group {{$errors->has('Corey Exponent ') ? 'has-error' : ''}}">
                                    {!! Form::text('exponente_corey_petroleo',$IPR->exponente_corey_petroleo, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'exponente_corey_petroleo']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[-]</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('end point', 'Kro End Point del petroleo (Pendiente por el nombre correcto) (*)') !!}
                                <div class="input-group {{$errors->has('Kro End Point ') ? 'has-error' : ''}}">
                                    {!! Form::text('end_point_kr_petroleo',$IPR->end_point_kr_petroleo, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'end_point_kr_petroleo']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[-]</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('saturacion de aceite irreducible', 'Residual Oil Saturation (*)') !!}
                                <div class="input-group {{$errors->has('Irreducible Oil Residual') ? 'has-error' : ''}}">
                                    {!! Form::text('saturacion_aceite_irred',$IPR->saturacion_aceite_irred, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'saturacion_aceite_irred']) !!}
                                    <span class="input-group-addon" id="basic-addon2">[-]</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Kro"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Kro-w</h4></div>
                            <div class="panel-body">
                                <div id="Kro" class="panel-collapse collapse in">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{$errors->has('Irreducible Water Saturation-Swi') ? 'has-error' : ''}}">
                                                {!! Form::label('saturacion_agua_irred', 'Irreducible Water Saturation-Swi (*)') !!}
                                                <div class="input-group">
                                                    {!! Form::text('saturacion_agua_irred',$IPR->saturacion_agua_irred, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'saturacion_agua_irred']) !!} 
                                                    <span class="input-group-addon" id="basic-addon2">[-]</span> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('exponente_corey_agua', 'Corey Exponent-nw (*)') !!}
                                            <div class="input-group {{$errors->has('Corey Exponent ') ? 'has-error' : ''}}">
                                                {!! Form::text('exponente_corey_agua',$IPR->exponente_corey_agua, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'exponente_corey_agua']) !!}
                                                <span class="input-group-addon" id="basic-addon2">[-]</span>
                                            </div>
                                        </div>    
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('end_point_kr_agua', 'Krw-Swi(*)') !!}
                                                <div class="input-group {{$errors->has('Kro End Point ') ? 'has-error' : ''}}">
                                                    {!! Form::text('end_point_kr_agua',$IPR->end_point_kr_agua, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'end_point_kr_agua']) !!}
                                                    <span class="input-group-addon" id="basic-addon2">[-]</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>          
                                </div>
                            </div>
                            </div>
                            <br>

                            <div class="panel panel-default">
                            <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Krog"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Kro-g</h4></div>
                            <div class="panel-body">
                                <div id="Krog" class="panel-collapse collapse in">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('exponente_corey_gas', 'Corey Exponent-ng (*)') !!}
                                            <div class="input-group {{$errors->has('Corey Exponent ') ? 'has-error' : ''}}">
                                                {!! Form::text('exponente_corey_gas',$IPR->exponente_corey_gas, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'exponente_corey_gas']) !!}
                                                <span class="input-group-addon" id="basic-addon2">[-]</span>
                                            </div>
                                        </div>    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('end_point_kr_gas', 'Kro End point del gas (Pendiente por el nombre correcto)(*)') !!}
                                                <div class="input-group {{$errors->has('Kro End Point ') ? 'has-error' : ''}}">
                                                    {!! Form::text('end_point_kr_gas',$IPR->end_point_kr_gas, ['placeholder' => '[-]', 'class' =>'form-control', 'id' => 'end_point_kr_gas']) !!}
                                                    <span class="input-group-addon" id="basic-addon2">[-]</span>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>   
                                </div>
                            </div>
                    </div>
                    <br>

                    

                </div>
            </div>
       
  

              

                

                
                
            </div>
        </div>
    </div>
    </div>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#MP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fluid properties</h4></div>
        <div class="panel-body">
            <div id="MP" class="panel-collapse collapse in">
                    
                <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('Fluid') ? 'has-error' : ''}}">
                            {!! Form::label('fluido', 'Fluid (*)') !!}
                            <select name="FType" class="form-control" id="FType">
                                <option></option>
                                <option value="Oil" selected>Oil</option>
                                
                            </select>
                        </div>
                    </div>
                    
                </div>                    
            </div>

                <div class="form-group">
                {!! Form::label('excel_table',  'Etiqueta', array('class' => 'control-label col-sm-6')) !!}
                <div class="col-sm-12">
                    <div id="excel_table" class="dataTable" style="overflow: auto; width: 200px; max-width: 200px; height: 100px; max-height: 100px"></div>
                    
                    <script>
                        var myData = {!! $tabla !!};

                        container = document.querySelector('#excel_table');


                        var $hot = new Handsontable(container, {
                            data: myData,
                            startRows: 5,
                            startCols: 3,
                            width: 100,
                            minSpareCols: 0,
                            //always keep at least 1 spare row at the right
                            minSpareRows: 1,
                            //always keep at least 1 spare row at the bottom,
                            rowHeaders: true,
                            colHeaders: ['Pressure[psi]', 'Oil Viscosity [cP]','Oil Volumetric Factor [rb/stb]', "Water Viscosity [cP]"],
                            contextMenu: true,
                            stretchH:"all"

                            });
                            
                            $hot.updateSettings({
                                width: 800
                            });
                            $("#excel_table").css("width", "800px");
                            $("#excel_table").css("max-width", "800px");
                            
                        /*$("#permeabilidad, #porosidad, #tipo_roca").change(function() {
                          if($("#permeabilidad").val() != '' && $("#porosidad").val() != '' && $("#tipo_roca").val() != '0'){
                              $("#modulo_permeabilidad").attr("readonly", true);
                              $("#modulo_permeabilidad").val(0);
                          }
                          else{
                              $("#modulo_permeabilidad").attr("readonly", false);
                          }
                        });
                        
                        $("#modulo_permeabilidad").change(function() {
                          if($(this).val() == ''){
                              $("#permeabilidad").attr("readonly", false);
                              $("#porosidad").attr("readonly", false);
                              $("#tipo_roca").attr("readonly", false).css("display", "inline");
                             
                          }
                          else{
                              $("#permeabilidad").attr("readonly", true);
                              $("#porosidad").attr("readonly", true);
                              $("#tipo_roca").attr("readonly", true).css("display", "none");
                              
                              $("#permeabilidad").val(0);
                              $("#porosidad").val(0);
                              $("#tipo_roca").val(0);
                          }
                        });*/
                        
                        if($("#tipo_roca").val() != '0'){
                            $("#calculate_permeability_module").attr("checked", true);
                            $("#modulo_permeabilidad").attr("readonly", true);
                              $("#modulo_permeabilidad").val(0);
                              $("#permeabilidad").attr("readonly", false);
                              $("#porosidad").attr("readonly", false);
                              $("#tipo_roca").attr("readonly", false).css("display", "inline");
                              
                              $(".use_permeability_module").hide();
                              $(".calculate_permeability_module").show();
                        }
                        else{
                            $("#use_permeability_module").attr("checked", true);
                            $("#permeabilidad").attr("readonly", true);
                              $("#porosidad").attr("readonly", true);
                              $("#tipo_roca").attr("readonly", true).css("display", "none");
                              
                              $("#permeabilidad").val(0);
                              $("#porosidad").val(0);
                              $("#tipo_roca").val(0);
                              
                              $("#modulo_permeabilidad").attr("readonly", false);
                              
                              $(".use_permeability_module").show();
                              $(".calculate_permeability_module").hide();
                        }
                        
                        
                    
                        
                        $("#calculate_permeability_module").click(function() {
                          //if($("#permeabilidad").val() != '' && $("#porosidad").val() != '' && $("#tipo_roca").val() != '0'){
                              $("#modulo_permeabilidad").attr("readonly", true);
                              $("#modulo_permeabilidad").val(0);
                              $("#permeabilidad").attr("readonly", false);
                              $("#porosidad").attr("readonly", false);
                              $("#tipo_roca").attr("readonly", false).css("display", "inline");
                              
                              $(".use_permeability_module").hide();
                              $(".calculate_permeability_module").show();
                              
                          //}
                          //else{
                              //$("#modulo_permeabilidad").attr("readonly", false);
                          //}
                        });
                        
                        $("#use_permeability_module").click(function() {
                          //if($(this).val() == ''){
                              //$("#permeabilidad").attr("readonly", false);
                              //$("#porosidad").attr("readonly", false);
                              //$("#tipo_roca").attr("readonly", false).css("display", "inline");
                             
                          //}
                          //else{
                              $("#permeabilidad").attr("readonly", true);
                              $("#porosidad").attr("readonly", true);
                              $("#tipo_roca").attr("readonly", true).css("display", "none");
                              
                              $("#permeabilidad").val(0);
                              $("#porosidad").val(0);
                              $("#tipo_roca").val(0);
                              
                              $("#modulo_permeabilidad").attr("readonly", false);
                              
                              $(".use_permeability_module").show();
                              $(".calculate_permeability_module").hide();
                          //}
                        });
                            
                            
                    </script>
                    
                </div>
            </div>
            </div>
        </div>
    </div>



   




<div class="row">
    <div class="col-xs-12">
        {!! Form::submit('Next' , array('class' => 'btn btn-primary pull-right', 'onclick' => 'enviar();')) !!}
        {!! Form::hidden('presiones_table', '', array('id' => 'presiones_table')) !!}
        {!! Form::Close() !!}
    </div>
</div>

@endsection




@section('Scripts')
@include('js/ipredit')
@include('css/ipredit')
@endsection