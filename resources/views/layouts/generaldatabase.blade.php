<!DOCTYPE html>
<html lang="en">
    <?php if(!isset($_SESSION)) {
    session_start();
    } ?>
    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{--configuracion en el meta para usar el csrfToken en todas las peticiones ajax global--}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" media="screen" href="http://nextgen.pl/_/scroll/dist/jquery.handsontable.full.css">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        {{--configuracion en el script para usar el csrfToken en todas las peticiones ajax global--}}
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
        <script src="http://code.highcharts.com/modules/offline-exporting.js"></script>
        <script src="https://code.highcharts.com/highcharts-more.js"></script>

        <!-- Archivos para arbol de importar -->

            <link href="{{asset('css/tree.css')}}" rel="stylesheet" type="text/css"/>
            <script type="text/javascript" src="{{asset('js/tree.js')}}"></script>

        <!-- -->

        @yield('Scripts')

        <script>

            $(document).ready(function () {
              var count=0;
              $.get("{{url('request')}}",
                {},
                function(data){
                  $.each(data, function(index, value){
                    count++;
                  });
                $('#requestV').html(count);
                });

                
	            var url = window.location;
	            $('ul.nav a[href="' + url + '"]').parent().addClass('active');
	            $('ul.nav a').filter(function () {
	            return this.href == url;
	            }).parent().addClass('active').parent().parent().addClass('active');

            });
        </script>

    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="icon-menu">
                            <a href="/homeC">
                                <img src="/images/User-white.png" class="icons" alt="User" width="20" height="20">
                                Home<span class="sr-only">(current)</span>
                            </a>
                        </li>
                        @if(\Auth::User()->office == 2)
                            <li>{!! link_to('requestC', $title = 'Request'); !!}</li>
                        @endif
                        @if(\Auth::User()->office != 2)
                            <li class="icon-menu">
                                <a href="/database">
                                    <img src="/images/Database-icon.png" class="icons" alt="database" width="20" height="20">
                                    Database
                                </a>
                            </li>
                        @endif
                        <li class="icon-menu">
                            <a href="/share_scenario">
                                <img src="/images/Project-management.png" class="icons" alt="project" width="15" height="20">
                                Project Management
                            </a>
                        </li>
                        <li class="icon-menu">
                            <a href="/Geor">
                                <img src="/images/Georeference.png" class="icons" alt="geo" width="20" height="20">
                                Georeference
                            </a>
                        </li>
                        {{-- <li class="icon-menu"><img src="/images/Scenario-report.png" class="icons" alt="scenary">{!! link_to('scenarioR', $title = 'Scenario Report'); !!}</li>
                        <li class="icon-menu"><img src="/images/Data-inventory.png" class="icons" alt="data">{!! link_to('dataInventory', $title = 'Data Inventory'); !!}</li> --}}
                        <li class="dropdown icon-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="/images/Help.png" class="icons" alt="help" width="20" height="20">
                                Help<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>{!! link_to_route('manuals', $title = 'Manuals'); !!}</li>
                                <li>{!! link_to_route('download', $title = 'Other Downloads'); !!}</li>
                                <li><a href="{{route('manual-public.index')}}">Interactive Guide</a></li>
                                <li>{!! link_to_route('about', $title = 'About'); !!}</li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @if(\Auth::User()->office == 0)
                        <li role="presentation"><a href="{!! url('requestA') !!}">Request <span class="badge" id="requestV">0</span></a></li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> Users<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>{!! link_to('registerC', $title = 'Sign Up'); !!}</li>
                                <li>{!! link_to('UserC', $title = 'User Management'); !!}</li>
                                <li>{!! link_to('changepassword', $title = 'Change Password'); !!}</li>
                                <li>{!! link_to('UserStatistics', $title = 'User Statistics'); !!}</li>
                            </ul> 
                        </li>
                        @endif  
                        <li>{!! link_to("logout", $title = 'Log Out') !!}</li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="container">
            <div class="row">
                    @yield('content')

            </div>
        </div>
        <footer>	
            <hr>
            <center>
                <form class="form-inline" role="form">
                    <a href="http://unal.edu.co/"><img src="{!! asset('images/unal.png') !!}" width="125" height="70" HSPACE="30"/></a>
                    <a href="http://www.equion-energia.com/Paginas/default.aspx"><img src="{!! asset('images/EQUION.jpg') !!}" width="120" height="80" HSPACE="30"/></a>
                    <a href="http://www.ecopetrol.com.co/wps/portal/web_es"><img src="{!! asset('images/ECOPETROL.jpg') !!}" width="120" height="100" HSPACE="30"/></a>
                    {{-- <a href="http://www.hocol.com/"><img src="{!! asset('images/HOCOL-nueva.png') !!}" width="120" height="80" HSPACE="30"/></a> --}}
                    <!--<a href="http://www.uis.edu.co/webUIS/es/index.jsp"><img src="{!! asset('images/UIS.png') !!}" width="80" height="80" HSPACE="30"/></a>-->
				</form>

                <form action="">
                    <center><img src="{!! asset('images/IFDM.png') !!}" width="250" height="100" HSPACE="30"/></center>
                </form>
            </center></br>
        </footer>
    </body>

    <style>
      .red {
        color: red;
      }
      .icon-menu>.icons{
        width: 20px;
        height: auto;
        padding-top: 15px;
        padding-left: 5px;
      }
      .nav>li{
            display: -webkit-box;
      }
    </style>
</html>