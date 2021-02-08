@extends('layouts.about')
@section('title', 'About')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <center>
                    <img src={{ asset('images/IFDM.png') }} height="170px" width="500px">
                </center>
                <br>
                <p align="justify">The IFDM has been developed by the Hydrocarbon Reservoir Computational Laboratory of
                    Universidad Nacional de Colombia, with the sponsorship of Ecopetrol Group and the support with the
                    diagnostics specialized tools of Universidad Nacional de Colombia.
                </p>
                <center>
                    <form class="form-inline" role="form">
                        <a href="http://unal.edu.co/"><img src="{!! asset('images/unal.png') !!}" width="125"
                                height="70" HSPACE="30" /></a>
                        <a href="http://www.equion-energia.com/Paginas/default.aspx"><img
                                src="{!! asset('images/EQUION.jpg') !!}" width="120" height="80" HSPACE="30" /></a>
                        <a href="http://www.ecopetrol.com.co/wps/portal/web_es"><img
                                src="{!! asset('images/ECOPETROL.jpg') !!}" width="120" height="100" HSPACE="30" /></a>
                        {{-- <a href="http://www.hocol.com/"><img src="{!! asset('images/HOCOL-nueva.png') !!}" width="120"
                                height="80" HSPACE="30" /></a> --}}
                        {{-- <a href="https://exergy-ma.co/"><img src="{!! asset('images/exergy.png') !!}" width="140"
                                height="60" HSPACE="30" /></a> --}}
                    </form>
                </center>
            </div>
        </div>
    </div>
</div>
@endsection


@section('Scripts')

@endsection