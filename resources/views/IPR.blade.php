@extends('layouts.ProjectGeneral')

@section('title', 'IFDM Project')

@section('content')
<?php  
  if(!isset($_SESSION)) {
    session_start();
  }
?>

@if (session()->has('mensaje'))
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

<div onload="multiparametrico();">



    </br>
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Petrophysics"><span class="chevron_toggleable1 glyphicon glyphicon-chevron-up pull-right"></span></a> Petrophysics</h4></div>
            <div class="panel-body">
                <div id="Petrophysics" class="panel-collapse collapse in">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!!Form::model($multiparametrico, array('route' => array('IPR.update', $multiparametrico->id), 'method' => 'PATCH', 'id'=>'form'), array('role' => 'form'))!!}
                                <input type='hidden' id='basin' name='basin' value="{!!$cuenca->nombre!!}">
                                <input type='hidden' id='field' name='field' value="{!!$campo->nombre!!}">
                                <input type='hidden' id='formation' name='formation' value="{!! $formacion->nombre !!}">
                                <input type='hidden' id='well' name='well' value="{!! $pozo->nombre !!}">

                                {!! Form::label('top', 'Top (*)') !!}
                                <div class="input-group {{$errors->has('top') ? 'has-error' : ''}}">
                                    {!! Form::text('top',$multiparametrico->top, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'top']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('bottom', 'TVD (*)') !!}
                                <div class="input-group {{$errors->has('bottom') ? 'has-error' : ''}}">
                                    {!! Form::text('bottom',$multiparametrico->bottom, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'bottom']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('netpay', 'NetPay (*)') !!}
                                <div class="input-group {{$errors->has('netpay') ? 'has-error' : ''}}">
                                    {!! Form::text('netpay',$multiparametrico->netPay, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'netpay']) !!}
                                    <span class="input-group-addon" id="basic-addon2">ft</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('porosity', 'Porosity (*)') !!}
                                <div class="input-group {{$errors->has('porosity') ? 'has-error' : ''}}">
                                    {!! Form::text('porosity',$multiparametrico->porosity, ['placeholder' => '%', 'class' =>'form-control', 'id' => 'porosity']) !!}
                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{$errors->has('permeability') ? 'has-error' : ''}}">
                                {!! Form::label('permeability', 'Absolute permeability (*)') !!}
                                <div class="input-group">
                                    {!! Form::text('permeability',$multiparametrico->absPermeability, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'permeability']) !!} 
                                    <span class="input-group-addon" id="basic-addon2">mD</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Fluid"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Fluid information at average reservoir pressure</h4></div>
        <div class="panel-body">
            <div id="Fluid" class="panel-collapse collapse">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('FType') ? 'has-error' : ''}}">
                            {!! Form::label('FType', 'Fluid type (*)') !!}
                            <select name="FType" class="form-control" id="FType">
                                <option></option>
                                <option value="Oil">Oil</option>
                                <option value="Gas">Gas</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('SPressure') ? 'has-error' : ''}}">
                            {!! Form::label('SPressure', 'Saturation pressure (*)') !!}
                            <div class="input-group">
                                {!! Form::text('SPressure',$multiparametrico->saturation_pressure, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'SPressure']) !!}
                                <span class="input-group-addon" id="basic-addon2">psia</span>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="panel panel-default {{$errors->has('MSFormation') ? 'has-error' : ''}}">
                    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#GasP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Gas properties</h4></div>
                    <div class="panel-body">
                        <div id="GasP" class="panel-collapse collapse">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('viscosityG') ? 'has-error' : ''}}">
                                        {!! Form::label('viscosity', 'Viscosity (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('viscosityG',$multiparametrico->viscosityG, ['placeholder' => 'cP', 'class' =>'form-control', 'id' => 'viscosityG']) !!}
                                            <span class="input-group-addon" id="basic-addon2">cP</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('FVFG') ? 'has-error' : ''}}">
                                        {!! Form::label('FVF', 'FVF (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('FVFG',$multiparametrico->fvfG, ['placeholder' => 'RCF/SCF', 'class' =>'form-control', 'id' => 'FVFG']) !!}
                                            <span class="input-group-addon" id="basic-addon2">RCF/SCF</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('RV') ? 'has-error' : ''}}">
                                        {!! Form::label('RV', 'RV (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('RV',$multiparametrico->rv, ['placeholder' => 'STB/SCF', 'class' =>'form-control', 'id' => 'RV']) !!}
                                            <span class="input-group-addon" id="basic-addon2">STB/SCF</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="panel panel-default">
                    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#OilP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Oil properties</h4></div>
                    <div class="panel-body">
                        <div id="OilP" class="panel-collapse collapse">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('viscosityO') ? 'has-error' : ''}}">
                                        {!! Form::label('viscosity', 'Viscosity (*)') !!}
                                        <div class="input-group">
                                            {!! Form::text('viscosityO',$multiparametrico->viscosityO, ['placeholder' => 'cP', 'class' =>'form-control', 'id' => 'viscosityO']) !!}
                                            <span class="input-group-addon" id="basic-addon2">cP</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('FVF', 'FVF (*)') !!}
                                        <div class="input-group {{$errors->has('FVFO') ? 'has-error' : ''}}">
                                            {!! Form::text('FVFO',$multiparametrico->fvfO, ['placeholder' => 'RB/STB', 'class' =>'form-control', 'id' => 'FVFO']) !!}
                                            <span class="input-group-addon" id="basic-addon2">RB/STB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('RS', 'RS (*)') !!}
                                        <div class="input-group {{$errors->has('RS') ? 'has-error' : ''}}">
                                            {!! Form::text('RS',$multiparametrico->rs, ['placeholder' => 'SCF/STB', 'class' =>'form-control', 'id' => 'RS']) !!}
                                            <span class="input-group-addon" id="basic-addon2">SCF/STB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="panel panel-default">
                    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#WaterP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Water properties</h4></div>
                    <div class="panel-body">
                        <div id="WaterP" class="panel-collapse collapse">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('viscosity', 'Viscosity (*)') !!}
                                        <div class="input-group {{$errors->has('viscosityW') ? 'has-error' : ''}}">
                                            {!! Form::text('viscosityW',$multiparametrico->viscosityW, ['placeholder' => 'cP', 'class' =>'form-control', 'id' => 'viscosityW']) !!}
                                            <span class="input-group-addon" id="basic-addon2">cP</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('FVF', 'FVF (*)') !!}
                                        <div class="input-group {{$errors->has('FVFW') ? 'has-error' : ''}}">
                                            {!! Form::text('FVFW',$multiparametrico->fvfW, ['placeholder' => 'RB/STB', 'class' =>'form-control', 'id' => 'FVFW']) !!}
                                            <span class="input-group-addon" id="basic-addon2">RB/STB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#Prod"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production data</h4></div>
        <div class="panel-body">
            <div id="Prod" class="panel-collapse collapse">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('well radius', 'Well radius (*)') !!}
                            <div class="input-group {{$errors->has('wellR') ? 'has-error' : ''}}">
                                {!! Form::text('wellR',$multiparametrico->well_radius, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'wellR']) !!}
                                <span class="input-group-addon" id="basic-addon2">ft</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('drainage radius', 'Drainage radius (*)') !!}
                            <div class="input-group {{$errors->has('drainageR') ? 'has-error' : ''}}">
                                {!! Form::text('drainageR',$multiparametrico->drainage_radius, ['placeholder' => 'ft', 'class' =>'form-control', 'id' => 'drainageR']) !!}
                                <span class="input-group-addon" id="basic-addon2">ft</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Reservoir pressure', 'Reservoir pressure (*)') !!}
                            <div class="input-group {{$errors->has('ReservoirP') ? 'has-error' : ''}}">
                                {!! Form::text('ReservoirP',$multiparametrico->reservoir_pressure, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'ReservoirP']) !!}
                                <span class="input-group-addon" id="basic-addon2">psia</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Bottom hole pressure', 'BHP (*)') !!}
                            <div class="input-group {{$errors->has('BHP') ? 'has-error' : ''}}">
                                {!! Form::text('BHP',$multiparametrico->bottom_HPressure, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'BHP']) !!}
                                <span class="input-group-addon" id="basic-addon2">psia</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Qo', 'Oil rate (*)') !!}
                            <div class="input-group {{$errors->has('Qo') ? 'has-error' : ''}}">
                                {!! Form::text('Qo',$multiparametrico->qo, ['placeholder' => 'STB/D', 'class' =>'form-control', 'id' => 'Qo']) !!}
                                <span class="input-group-addon" id="basic-addon2">STB/D</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Qg', 'Gas rate (*)') !!}
                            <div class="input-group {{$errors->has('Qg') ? 'has-error' : ''}}">
                                {!! Form::text('Qg',$multiparametrico->qg, ['placeholder' => 'MMSCF/D', 'class' =>'form-control', 'id' => 'Qg']) !!}
                                <span class="input-group-addon" id="basic-addon2">MMSCF/D</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Qw', 'Water rate (*)') !!}
                            <div class="input-group {{$errors->has('Qw') ? 'has-error' : ''}}">
                                {!! Form::text('Qw',$multiparametrico->qw, ['placeholder' => 'STB/D', 'class' =>'form-control', 'id' => 'Qw']) !!}
                                <span class="input-group-addon" id="basic-addon2">STB/D</span>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="panel panel-default">
                    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#PLT"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Production Test (PLT)</h4></div>
                    <div class="panel-body">
                        <div id="PLT" class="panel-collapse collapse">
                            <div id="excel" style="overflow: scroll" class="handsontable"></div>
                            <input type='hidden' id='ProdD' name='ProdD'>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#MP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Multiparametric analysis</h4></div>
        <div class="panel-body">
            <div id="MP" class="panel-collapse collapse">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#CP"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> Critical pressure by damage parameters <button type="button" class="btn btn-default" onclick="window.open('{!! asset('help.pdf') !!}')">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </button></h4></div>
                    <div class="panel-body">
                        <div id="CP" class="panel-collapse collapse">

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('MS', 'Mineral scales (*)') !!}
                                            <div class="input-group {{$errors->has('MS') ? 'has-error' : ''}}">
                                                {!! Form::text('MS',$multiparametrico->pc_MSP, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'MS']) !!}
                                                <span class="input-group-addon" id="basic-addon2">psia</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('OS', 'Organic scales (*)') !!}
                                            <div class="input-group {{$errors->has('OS') ? 'has-error' : ''}}">
                                                {!! Form::text('OS',$multiparametrico->pc_OSP, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'OS']) !!}
                                                <span class="input-group-addon" id="basic-addon2">psia</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('RPE', 'Relative permeability effects (*)') !!}
                                            <div class="input-group {{$errors->has('RPE') ? 'has-error' : ''}}">
                                                {!! Form::text('RPE',$multiparametrico->pc_KrP, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'RPE']) !!}
                                                <span class="input-group-addon" id="basic-addon2">psia</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('GD', 'Geomechanical damage (*)') !!}
                                            <div class="input-group {{$errors->has('GD') ? 'has-error' : ''}}">
                                                {!! Form::text('GD',$multiparametrico->pc_GDP, ['placeholder' => 'psia', 'class' =>'form-control', 'id' => 'GD']) !!}
                                                <span class="input-group-addon" id="basic-addon2">psia</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading"><h4><a data-parent="#accordion" data-toggle="collapse" href="#KD"><span class="chevron_toggleable glyphicon glyphicon-chevron-down pull-right"></span></a> K damaged and K base ratio (Kd/Kb) by damage parameter</h4></div>
                    <div class="panel-body">
                        <div id="KD" class="panel-collapse collapse">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('MSFormation') ? 'has-error' : ''}}">
                                        {!! Form::label('MS', 'Mineral scales (*)') !!}
                                        {!! Form::text('MSFormation',$multiparametrico->ms, ['placeholder' => '', 'class' =>'form-control', 'id' => 'MS']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('FBFormation') ? 'has-error' : ''}}">
                                        {!! Form::label('FB', 'Fines blockage (*)') !!}
                                        {!! Form::text('FBFormation',$multiparametrico->fb, ['placeholder' => '', 'class' =>'form-control', 'id' => 'FB']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('OSFormation') ? 'has-error' : ''}}">
                                        {!! Form::label('OS', 'Organic scales (*)') !!}
                                        {!! Form::text('OSFormation',$multiparametrico->os, ['placeholder' => '', 'class' =>'form-control', 'id' => 'OS']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('KrFormation') ? 'has-error' : ''}}">
                                        {!! Form::label('Kr', 'Relative permeability (*)') !!}
                                        {!! Form::text('KrFormation',$multiparametrico->kr, ['placeholder' => '', 'class' =>'form-control', 'id' => 'KrP']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('IDFormation') ? 'has-error' : ''}}">
                                        {!! Form::label('ID', 'Induced damage (*)') !!}
                                        {!! Form::text('IDFormation',$multiparametrico->idd, ['placeholder' => '', 'class' =>'form-control', 'id' => 'ID']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{$errors->has('GDFormation') ? 'has-error' : ''}}">
                                        {!! Form::label('GD', 'Geomechanical damage (*)') !!}
                                        {!! Form::text('GDFormation',$multiparametrico->gd, ['placeholder' => '', 'class' =>'form-control', 'id' => 'GD']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Kc', 'Kc (*)') !!}
                            <div class="input-group {{$errors->has('Kc') ? 'has-error' : ''}}">
                                {!! Form::text('Kc',$multiparametrico->kc, ['placeholder' => 'mD', 'class' =>'form-control', 'id' => 'Kc']) !!}
                                <span class="input-group-addon" id="basic-addon2">mD</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            {!! Form::hidden('id', $multiparametrico->id) !!}
            {!! Form::submit('Edit' , array('class' => 'btn btn-primary pull-right')) !!}
            {!! Form::Close() !!}
        </div>
    </div>



@endsection

@include('css/ipr')
@section('Scripts')

    <script src="https://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.js"></script>
    <link type="text/css" rel="stylesheet" href="https://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.min.css">

@endsection