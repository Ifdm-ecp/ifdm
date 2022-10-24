@extends('layouts.ProjectGeneral')
@section('title', 'IFDM Project')

@section('content')
<?php if(!isset($_SESSION)) { session_start(); } ?>
@include('layouts/modal_error')
<div id="sticky-anchor"  class="col-md-6"></div>
<div id="sticky" style="position: relative;">
  <center>
    <b>Scenario: </b>{!! $statistical->escenario->nombre !!} </br>
    Basin: {!! $statistical->escenario->cuenca->nombre !!} - 
    Field: {!! $statistical->escenario->campo->nombre !!} - 
    {{-- Producing interval: {!! $statistical->escenario->formacionxpozo->nombre !!} -  --}}
    Well: {!!  $statistical->escenario->pozo->nombre !!} - 
    User: {!! $statistical->escenario->user->name !!}</center>
</div>

</br>
<hr>
@if(!$statistical->status_wr)
<div class="row">
  <div class="col-md-8">
    <div id="container"></div>
  </div>
  <div class="col-md-4">
    <p> </p>            
    <p> </p>            
    <p> </p>            
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Damage Mechanism</th>
          <th>Skin [%]</th>
        </tr>
      </thead>
      <tbody id="statistical_body">
      </tbody>
    </table>
  </div>
</div>
<hr>
@else
<div class="jumbotron">
 <center>
   <span>Run has not been executed, there is no data to show.</span>
 </center>
</div>
@endif

<p class="pull-right">            
  <a href="{{route('statistical.edit', $statistical->id)}}" class="btn btn-warning" role="button">Edit</a>
  <a type="button"  name="back" class="btn btn-danger" onClick="javascript:history.back(1)" role="button">Cancel</a>
</p>

@endsection
@section('Scripts')
@include('css/add_multiparametric')
@include('js/modal_error')
@if(!$statistical->status_wr)
  @include('multiparametricAnalysis.statistical.showJs')
@endif
@endsection
