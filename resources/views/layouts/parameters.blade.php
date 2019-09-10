@extends('layouts.ProjectGeneral')


@section('content')
<?php 
if(!isset($_SESSION)) {
session_start();
}
?>
<div class="masthead">
    <div id="sticky-anchor"  class="col-md-6"></div>
    <div id="sticky"><center> <b> Scenario: </b>  {!! $_SESSION['esc'] !!} </br>  Basin: {!! $_SESSION['basin'] !!} - Field: {!! $_SESSION['field'] !!} - Producing interval: {!!  $_SESSION['formation'] !!} - Well: {!!  $_SESSION['well'] !!} - User: {!! $user->fullName !!}</center></div>
    </br>
    <form class="form-inline">
        <h1>Multiparametric Analysis
          <!--
            <button type="button" class="btn btn-default">
                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
            </button>-->
        </h1><br/>
    </form>

    <ul class="nav nav-tabs">
        <li role="presentation" class="dropdown active">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                Parameters <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
            <li><a href="javascript: MS();">Mineral Scales</a></li>
            <li><a href="javascript: FB();">Fine Blockage</a></li>
            <li><a href="javascript: OS();">Organic Scales</a></li>
            <li><a href="javascript: RP();">Relative Permeability</a></li>
            <li><a href="javascript: ID();">Induced Damage</a></li>
            <li><a href="javascript: GD();">Geomechanical Damage</a></li>
            </ul>
        </li>
    </ul>
</div>


@yield('parameters')

@endsection
<style> 
#sticky {
    padding: 0.5ex;
    background-color: #333;
    color: #fff;
  width: 100%;
    font-size: 1.5em;
    border-radius: 0.5ex;
}
#sticky.stick {
    position: fixed;
    top: 0;
    width: 62.7%;
    z-index: 10000;
    border-radius: 0 0 0.5em 0.5em;
}
body {
    margin: 1em;
}
p {
    margin: 1em auto;
}

</style>

@section('Scripts')

  <script type="text/javascript">
    function sticky_relocate() {
      var window_top = $(window).scrollTop();
      var div_top = $('#sticky-anchor').offset().top;
      if (window_top > div_top) {
          $('#sticky').addClass('stick');
      } else {
          $('#sticky').removeClass('stick');
      }
  }

    $(function () {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });

  </script>
@endsection