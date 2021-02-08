<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">


<!-- Archivos para arbol de importar -->

    <link href="{{asset('css/tree.css')}}" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="{{asset('js/tree.js')}}"></script>

<!-- -->

<!-- Librerías Mapa-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <link rel="stylesheet" href="{!! asset('css/map.css') !!}">

    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV-sQFuLosUpjbqIlxTHAQ05vcsxF8s0U&callback=initMap">
    </script>
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=visualization&sensor=true_or_false"></script>-->
 
	<!-- Gráficos -->
   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  
  
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>

  <script src="../../js/index.js"></script>
	<!-- Otros -->
        <link rel="stylesheet" media="screen" href="http://nextgen.pl/_/scroll/dist/jquery.handsontable.full.css">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">

        <script>

            $(document).ready(function () {
            	var count=0;
              	$.get("{{url('request')}}",
	                {},
	                function(data){
	                  $.each(data, function(index, value){
	                    count++;
	                  });
	                $('#request').html(count);
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
                        <li class="icon-menu"><img src="/images/User-white.png" class="icons" alt="User">{!! link_to('homeC', $title = 'Home'); !!}<span class="sr-only">(current)</span></li>
                        @if(\Auth::User()->office == 2)
                            <li>{!! link_to('requestC', $title = 'Request'); !!}</li>
                        @endif
                        @if(\Auth::User()->office != 2)
                            <li class="icon-menu"><img src="/images/Database-icon.png" class="icons" alt="database">{!! link_to_route('database', $title = 'Database'); !!}</li>
                        @endif
                        <li class="icon-menu"><img src="/images/Project-management.png" class="icons" alt="project">{!! link_to('share_scenario', $title = 'Project Management'); !!}</li>
                        <li class="icon-menu"><img src="/images/Georeference.png" class="icons" alt="geo">{!! link_to('Geor', $title = 'Georeference'); !!}</li>
                        {{-- <li class="icon-menu"><img src="/images/Scenario-report.png" class="icons" alt="scenary">{!! link_to('scenarioR', $title = 'Scenario Report'); !!}</li> --}}
                        <li class="icon-menu"><img src="/images/Data-inventory.png" class="icons" alt="data">{!! link_to('dataInventory', $title = 'Data Inventory'); !!}</li>
                        <li class="dropdown icon-menu">
                            <img src="/images/Help.png" class="icons" alt="help">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Help<span class="caret"></span></a>
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
	








<div class="container-fluid">

			<div class ="row">

	        <div class= "col-xs-6 col-sm-7 sidebar-offcanvas" >
				@yield('map')
				<div class="row" style="height: 1%; float:center;"></div>
			<div class="row"> @yield('barra')</div>
			<div class="row"> 
			<div class= "col-xs-6 col-sm-3 sidebar-offcanvas" >
				@yield('barra1')
			</div>
			<div class= "col-xs-6 col-sm-3 sidebar-offcanvas" >
				@yield('barra2')
			</div>
			<div class= "col-xs-6 col-sm-3 sidebar-offcanvas" >
				@yield('barra3')
			</div>
			<div class= "col-xs-6 col-sm-3 sidebar-offcanvas" >
				@yield('barra4')
			</div>
			</div>
			</div>


			<div class="col-xs-6 col-sm-5 sidebar-offcanvas" id="sidebar2">
		      



    		<div class ="row"> 
    		<div class="col-xs-3 col-sm-6 sidebar-offcanvas" id="Filter1">

				@yield('Filter1')
			</div>
			<div class="col-xs-3 col-sm-6 sidebar-offcanvas" id="Filter2">
				@yield('Filter2')
			</div>

			<!--
			Eliminar comentario para mostrar el filtro Formation
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="Filter3">
				@yield('Filter3')
			</div>
			-->

    		</div>
			<div class ="row"> 
			<div class="col-xs-3 col-sm-6 sidebar-offcanvas" id="FA" align="center">

				Basin
			</div>
			<div class="col-xs-3 col-sm-6 sidebar-offcanvas" id="FB" align="center">
				Field
			</div>
			<!--
			Descomentar para mostrar la etiqueta del filtro Formation
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="FC" align="center">
				Formation
			</div>
			-->
			</div>
			<div class="row">
			<hr>
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="Filter6">
				@yield('Mecanismos')
			</div>
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="Filter4">
				@yield('Filter4')
			</div>
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="Filter5">
				@yield('Filtrox')
			</div>

			</div>
			<div class="row">
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="DM" align="center">
				Damage Mechanisms
			</div>
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="DV" align="center">
				Damage Variables
			</div>
			<div class="col-xs-3 col-sm-4 sidebar-offcanvas" id="DC" align="center">
				Damage Configuration 
			</div>
			</div>
				<hr>
				<div class="col-xs-3 col-sm-6 sidebar-offcanvas">
				<div class="row" align="center">

					<span class="label label-primary">Show Last, Average, Maximum, Minimum or Average data </span>
					<div class="row" style="height: 3%; float:center;"></div>
				</div>
				<div class="row spacer">  </div>
				<div class="col-xs-3 col-sm-3 sidebar-offcanvas" id="lastp">
					@yield('Lastb')
				</div>
				<div class="col-xs-3 col-sm-3 sidebar-offcanvas" id="avgp">
					@yield('Avgp')
				</div>
				<div class="col-xs-3 col-sm-3 sidebar-offcanvas" id="minp">
					@yield('Minp')
				</div>
			    <div class="col-xs-3 col-sm-3 sidebar-offcanvas" id="maxp">
					@yield('Maxp')
				</div>
				</div>
		    




			<div class="col-xs-3 col-sm-6 sidebar-offcanvas">
					<div class="row" align="center">
						
						<span class="label label-primary">Choose Well or Field view </span>
						<div class="row" style="height: 2%; float:center;"></div>
					</div>
					

					<div class ="row"> 
    		
				
								<div class="col-xs-3 col-sm-6 sidebar-offcanvas" id="avgp" align="center">
									@yield('WV')
								</div>
								<div class="col-xs-3 col-sm-6 sidebar-offcanvas" id="minp" align="center">
									@yield('FV')
								</div>

    		</div>
    		<div class="row" style="height: 2%; float:center;"></div>
			</div>
		    

    		

    		<div class ="row"> 
    		
			<div class="col-xs-3 col-sm-6 sidebar-offcanvas">

			</div>


    		</div>

    		<div class="row">
    		<div class="col-xs-12 col-sm-12 sidebar-offcanvas" id="chart">
				@yield('Graphic')
			</div>
    		</div>
    		<hr>
    		<div class="row">
    		<div class="col-xs-12 col-sm-12 sidebar-offcanvas" id="Info" align="center">
				@yield('Info')
			</div>
    		</div>

			</div>
        	</div>
	</div>

				@yield('Scripts')
	
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