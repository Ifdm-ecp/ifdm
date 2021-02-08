@extends('layouts.editData')
@section('title', 'IFDM Database')

@section('content')
@include('layouts/modal_error')

<h2>Production Test (PLT) - <small>Well: {{$pozoN}}</small></h2>
</br>

{!!Form::open(['action' => ['edit_production_test_controller@store', 'pozoId' => $pozo], 'method' => 'post'])!!}
<div class="col-md-12">
   <div id="excel" style="overflow: scroll" class="handsontable"></div>
   {!! Form::hidden('ProdT', '', array('class' => 'form-control', 'id' => 'ProdT', 'name' => 'ProdT')) !!}
   {!! Form::hidden('pltqo', '', array('class' => 'form-control', 'id' => 'pltqo', 'name' => 'pltqo')) !!}
   {!! Form::hidden('pltqg', '', array('class' => 'form-control', 'id' => 'pltqg', 'name' => 'pltqg')) !!}
   {!! Form::hidden('pltqw', '', array('class' => 'form-control', 'id' => 'pltqw', 'name' => 'pltqw')) !!}
</div>

<div class="row">
   <div id="plt_Interval"></div>
</div>

</br>

<div class="col-md-12">
   <button class="btn btn-primary pull-right" onclick="plot_plt_Interval()">Plot</button>  
</div>

</br>

<div class="row">
   <div class="col-xs-12">
      <p class="pull-right">
         </br>
         {!! Form::submit('Next' , array('class' => 'btn btn-warning', 'OnClick'=>'javascript: Mostrar();')) !!}
         <a href="{!! url('listIntervalC') !!}" class="btn btn-danger" role="button">Cancel</a>
      </p>
      {!! Form::Close() !!}
   </div>
</div>

@endsection


@section('Scripts')
  <script src="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.js"></script>
  <link type="text/css" rel="stylesheet" href="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.min.css">
  @include('js/edit_production_test')
  @include('js/modal_error')
@endsection