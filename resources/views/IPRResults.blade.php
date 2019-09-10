@extends('layouts.ProjectGeneral')

@section('title', 'IFDM Project')

@section('content')
<?php  
  if(!isset($_SESSION)) {
  session_start();
}
?>

@if (session()->has('mensaje'))

<div id="myModal" class="modal fade" hidden>
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
        <div id="sticky"><center>Scenario: {!! $scenary_s->nombre !!} </br> Basin: {!! $_SESSION['basin'] !!} - Field: {!! $_SESSION['field'] !!} - Producing interval: {!!  $intervalo->nombre !!} - Well: {!!  $_SESSION['well'] !!} </br> User: {!! $user->fullName !!} </center></div>
        <p></p>


    </br>


    <br>

    <div class="panel panel-default" >
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Results</h4></div>
        <div class="panel-body">
            <div id="Prod" class="panel-collapse collapse in">
                <div id="grafica">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                           <center> 
						     {!! Form::label('valor', 'Total Skin: ') !!}
                             <!--{!! Form::label('valor', ($skin == 0 ? "0" : round($skin,2))) !!}-->  
						     <b>{!! round($skin,2) !!}</b>
						   </center>                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
<?php if(isset($skins) and strcmp($skins, "") != 0){ ?>
    <div class="panel panel-default" >
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod_dis"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Results Disaggregation</h4></div>
        <div class="panel-body">
            <div id="Prod_dis" class="panel-collapse collapse in">
                <div id="grafica_dis">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                           <center> {!! Form::label('valor', 'Total Skin: ') !!}
                            {!! Form::label('valor', round(json_decode($skins)[0],2)) !!}   </center>                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
<?php } ?>


    {!!Form::open(array('url' => 'IPR/store', 'method' => 'post'))!!}
    <?php if(!isset($skins)){ ?>

    <div class="panel panel-default" >
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod_s"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Sensitivities</h4></div>
        <div class="panel-body">
            <div id="Prod_s" class="panel-collapse collapse in">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('Fluid') ? 'has-error' : ''}}">
                            {!! Form::label('sensibility', 'Sensitivity ', array('class' => 'required')) !!}
                            <select name="FType" class="form-control" id="FType" onchange="mostrar(this);">
                                <option></option>
                                <option value="factor_dano">Skin [-]</option>
                                <option value="modulo_permeabilidad">Permeability Module [1/psi]</option>
                                <option value="netpay">Net Pay [ft]</option>
                                <option value="absolute_permeability">Absolute Permeability [md]</option>
                                <option value="bhp">BHP [psi]</option>
                                <option value="bsw" {!! $IPR->fluido == 1 ? "" : "disabled" !!}>BSW [-]</option>
                                <option value="corey" {{ isset($IPR->exponente_corey_petroleo) ? "" : "disabled" }}>Corey Exponent Oil [-]</option>

                            </select>
                            <div id="resultado"></div>
                        </div>
                    </div>

                    <div id ="if_desagregacion" style='visibility: hidden;'>
                            <div class="col-md-4">
                                <div class="form-group"  id="value_id">
                                    {!! Form::label('valor', 'Enter Porosity and Permeability ', array('class' => 'required')) !!}

                                    <div class="input-group">
                                        {!! Form::text('valor2[]',null, ['placeholder' => '-', 'class' =>'form-control sensibilidad', 'id' => 'sensibilidad']) !!}

                                        <span class="input-group-addon" id="basic-addon2">-</span>
                                    </div>                                    
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('valor', 'Enter Porosity and Permeability Value', array('class' => 'required'), ["style" => "visibility: hidden;"]) !!}
                                    <div class="input-group">
                                      {!! Form::button('[+]', array('id' => 'add_value_desagregacion')) !!}

                                                                            
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div id ="if_otro" style='visibility: hidden;'>
                            <div class="col-md-4">
                                <div class="form-group"  id="value_id2">
                                    {!! Form::label('valor', 'Enter Value ', array('class' => 'required')) !!}

                                    <div class="input-group">
                                        {!! Form::text('valor1[]',null, ['placeholder' => '-', 'class' =>'form-control sensibilidad', 'id' => 'sensibilidad']) !!}

                                        <span class="input-group-addon" id="basic-addon2">-</span>

                                    </div>                                    
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('Add value', '', array('class' => 'required'), ["style" => "visibility: hidden;"]) !!}
                                    <div class="input-group">
                                      {!! form::button('[+]', array('id' => 'add_value')) !!}
                                                                            
                                    </div>
                                </div>
                            </div>
                    </div>
                    </div>

                    <ul id="lista" class="list-group">
                    </ul>
                    {!! Form::button('Apply' , array('class' => 'btn btn-primary pull-right', 'onclick' => 'enviar();')) !!}
            </div>
        </div>
    </div>

    <?php } ?>

    {!! Form::hidden('id_ipr', $IPR->id, array('id' => 'id_ipr')) !!}
    
<div id="loading" style="display: none;"></div>  
    <div class="row">
        <div class="col-xs-12">
    <?php if(!isset($skins)){ ?>
            &nbsp;
            <a href="{!! url('IPREdit') !!}" class="btn btn-warning" role="button">Edit</a>
            <?php } ?>
        </div>
    </div>



    {!! Form::Close() !!}
    
    <?php if(!isset($skins)){ 

     if(isset($desagregacion) and !is_null($desagregacion)) {?>
        {!!Form::open(array('url' => 'Desagregacion/load', 'method' => 'post'))!!}
        {!! Form::hidden('id', $desagregacion->id , array('id' => 'id')) !!}
        {!! Form::submit('Skin disaggregation' , array('class' => 'btn btn-primary pull-left', "style"=>"display:none;")) !!}
        {!! Form::Close() !!}
    <?php } else { ?>    
        <a href="{!! url('Desagregacion') !!}" class="btn btn-primary pull-left" role="button" style="Display:none">Disaggregation</a>
    <?php } 
     } else{?>
        {!!Form::open(array('url' => 'Desagregacion/load', 'method' => 'post'))!!}
        {!! Form::hidden('id', $desagregacion->id , array('id' => 'id')) !!}
        {!! Form::submit('Back' , array('class' => 'btn btn-default pull-left')) !!}
        {!! Form::Close() !!}
        <a href="{!! url('share_scenario') !!}" class="btn btn-danger pull-right" role="button">Exit</a>
    <?php }?>
</div>

@endsection
@section('Scripts')
@include('js/iprresults_js')
@endsection
@include('css/iprresults_css')
