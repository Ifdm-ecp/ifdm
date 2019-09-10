<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/maps/modules/map.js"></script>
<script src="https://rawgithub.com/paulo-raca/highcharts-contour/master/highcharts-contour.js"></script>
<script src="https://rawgithub.com/ironwallaby/delaunay/master/delaunay.js"></script>
<script src="https://code.highcharts.com/modules/annotations.js"></script>
<script src="https://code.highcharts.com/modules/heatmap.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<link rel="stylesheet" media="screen" href="http://nextgen.pl/_/scroll/dist/jquery.handsontable.full.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">

<div class="col-md-12">
	<div class="row">
		<div class="col-md-6" id="table"></div>
	</div>
	<div class="row">
		   	<div id="pvt_table"></div>
		  		{!! Form::text('value_pvt_data', '', array('class' => 'form-control', 'id' => 'value_pvt_data')) !!}
			</div>
		<div id="oil_projection_chart"></div>
		<div id="water_projection_chart"></div>
	</div>
	  <div class="row">
	    <div class="col-md-6">
	      <div class="form-check">
	        {!! Form::checkbox('perform_production_projection_selector',0,null,array('class'=>'form-check-input', 'id'=>'perform_production_projection_selector')) !!}&nbsp<label class="form-check-label" for="perform_production_projection_selector">Perform Production Projection</label>
	      </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-3" id="container2">
			<button type="button" class="btn" onclick="perform_production_projection()">Basic</button>
		</div>
	</div>
</div>
<script type="text/javascript">

	historical_data_table = $("#table");
	historical_data_table.handsontable({
	    height: 200,
	    colHeaders: true,
	    minSpareRows: 1,
	    viewportColumnRenderingOffset: 10,
	    rowHeaders: true,
	    contextMenu: true,
	    stretchH: 'all',
	    colWidths: [150,150,150],

	    columns: [{
	            title: "Date [YYYY-MM-DD]",
	            data: 0,
	            type: 'date',
	            dateFormat: 'YYYY-MM-DD'
	        },
	        {
	            title: "BOPD(bbl/d)",
	            data: 1,
	            type: 'numeric',
	            format: '0[.]0000000'
	        },
	        {
	            title: "BWPD(bbl/d)",
	            data: 2,
	            type: 'numeric',
	            format: '0[.]0000000'
	        },
	    ]
	});


	//Pronóstico --> Ligar botón a perform_production_projection() - Traer datos desde interfaz y agregar divs para dos gráficos.
	function perform_production_projection()
	{
		final_date = "2027-12-31"; // traer de interfaz *karen
		amount_of_dates = 120; //Delta de tiempo - traer de interfaz *karen
		table_div = "table";

		//Devuelve pronóstico hiperbólico y exponencial, pero sólo se usa el hiperbólico
		water_production_projection = production_projection(table_div, "water", final_date, amount_of_dates);

		//Devuelve pronóstico hiperbólico y exponencial para gráfico 
		oil_production_projection = production_projection(table_div, "oil", final_date, amount_of_dates); 

		water_hyperbolic_serie = [];
		water_original_data = [];
		oil_exponential_serie = [];
		oil_hyperbolic_serie = [];
		oil_original_data = [];

		for (var i = 0; i < water_production_projection[0][0].length; i++) 
		{
			water_hyperbolic_serie.push([water_production_projection[1][0][i], parseFloat(water_production_projection[1][1][i])]);
			water_original_data.push([water_production_projection[2][0][i], parseFloat(water_production_projection[2][1][i])]);
			oil_exponential_serie.push([oil_production_projection[0][0][i], parseFloat(oil_production_projection[0][1][i])]);
			oil_hyperbolic_serie.push([oil_production_projection[1][0][i], parseFloat(oil_production_projection[1][1][i])]);
			oil_original_data.push([oil_production_projection[2][0][i], parseFloat(oil_production_projection[2][1][i])]);
		}

		water_projection_series = [{name:"Water Production Hyperbolic Projection", data:water_hyperbolic_serie}, {name:"Water Production", data:water_original_data}];
		oil_projection_series = [{name:"Oil Production Hyperbolic Projection", data:oil_hyperbolic_serie}, {name:"Oil Production Exponential Projection", data:oil_exponential_serie}, {name:"Oil Production", data:oil_original_data}];

		plot_projection_chart("oil_projection_chart", oil_projection_series, "BOPD [bbl/d]");
		plot_projection_chart("water_projection_chart", water_projection_series, "BWPD [bbl/d]");
	}
	function production_projection(div, fluid_type, final_date, amount_of_dates)
	{

		var historical_data = clean_table_data(div);

		var n = historical_data.length;
		
		var dq = new Array(n); 
		var dt = new Array(n); 
		var qprom = new Array(n); 
		var de = new Array(n); 
		var dhr = new Array(n); 
		var b = new Array(n); 
		var dh = new Array(n); 
		var q = new Array(n); 
		var lnq = new Array(n); 
		var fecha = new Array(n);
		var original_dates = [];
		var dhp = 0;

		if(fluid_type === "oil")
		{
			q_data_index = 1; //Caudal en posición 1 de la tabla de históricos
		}
		else if(fluid_type === "water")
		{
			q_data_index = 2; //Caudal en posición 1 de la tabla de históricos 
		}

		/*
		Las fechas se tratan de esta manera ya que la conversión de string a date varía con base en el navegador. 
		Es más seguro implementarlo con enteros. YYYY/mm/dd -> los meses empiezan en 0. 
		*/
		for (var i = 0; i < fecha.length; i++) 
		{
			date_string = historical_data[i][0].split("-");
			original_dates.push(Date.UTC(parseInt(date_string[0]),parseInt(date_string[1]),parseInt(date_string[2])));
			fecha[i] = new Date(parseInt(date_string[0]),parseInt(date_string[1]) - 1,parseInt(date_string[2])); 
			q[i] = historical_data[i][q_data_index];
		} 

		//Se retornan para gráficos

		original_q = q;
		promedio = new Array(n);

		//Pronóstico 
		//Suavizada
		var fechaf = fecha[fecha.length - 1];
		qf = q[q.length - 1];

		a = n
		for (var i = 0; i < a; i++) 
		{
		    if (q[i] == 0)
		    {
			    n = n - 1;
			    for (var j = i; j < n; j++) 
			    {
		        	q[j] = q[j + 1];
		        	fecha[j] = fecha[j + 1];
			    }
		        i = i - 1;
		    }
			if (i == n)
			{
				break;
			}
		}

		final_date_string = final_date.split("-"); //fecha final de pronóstico - traer de interfaz *karen
		final_date = new Date(parseInt(final_date_string[0]),parseInt(final_date_string[1]),parseInt(final_date_string[2])); 

		nt = amount_of_dates;
		deltatf = Math.round(date_diff(final_date, fechaf) / nt);

		qe = new Array(nt);
		t = new Array(nt);
		qh = new Array(nt);

		for (var f = 3; f < a - 4; f++) 
		{
			promedio[f] = (q[f - 3] + q[f - 2] + q[f - 1] + q[f + 3] + q[f + 2] + q[f + 1]) / 6;
			if (q[f] < (promedio[f] * 0.9) || q[f] > (promedio[f] * 1.1))
			{
				n = n - 1;
				for (var j = f; j < n; j++) 
				{
		        	q[j] = q[j + 1];
		        	fecha[j] = fecha[j + 1];
				}
		        f = f - 1;
			}
			if (f == n)
			{
				break;
			}
		}

		//Qo
		if (qf < q[n-1])
		{
		    q[n-1] = qf;
		}

		//Deltas
		for (var i = 1; i < n; i++) 
		{
		    dq[i] = q[i] - q[i - 1];
		    dt[i] = date_diff(fecha[i], fecha[i - 1]);
		    qprom[i] = (q[i] + q[i - 1]) / 2;
		}

		//Exponencial
		dep = 0;
		j = 0;

		for (var i = 1; i < n; i++) 
		{
		    de[i] = -(dq[i] / qprom[i]) / dt[i];
		    dep = dep + de[i];
		}

		depp = dep / n;
		if(fluid_type === "oil")
		{
			for (var i = 0; i < nt; i++) 
			{
			    t[i] = deltatf * (i+1);
			    if (depp > 0)
			    {
			        qe[i] = q[n-1] * Math.exp(-depp * t[i]);
			    }
			    else
			    {
			        qe[i] = q[n-1] * Math.exp(depp * t[i]);
			    }
			}
		}
		else if(fluid_type === "water")
		{
			for (var i = 0; i < nt; i++) 
			{
			    t[i] = deltatf * (i+1);
			    if (depp < 0)
			    {
			        qe[i] = q[n-1] * Math.exp(-depp * t[i]);
			    }
			    else
			    {
			        qe[i] = q[n-1] * Math.exp(depp * t[i]);
			    }
			}
		}

		projection_dates = [];
		for (var i = 1; i <= nt; i++) 
		{
			fecha_aux = new Date(fechaf.getFullYear(), fechaf.getMonth(), fechaf.getDate());
			fecha_aux.setDate(fecha_aux.getDate() + (i * deltatf));
			original_dates.push(Date.UTC(parseInt(date_string[0]),parseInt(date_string[1]),parseInt(date_string[2])));
			projection_dates.push(Date.UTC(fecha_aux.getFullYear(),fecha_aux.getMonth()+1,fecha_aux.getDate()));
		}

		exponential_projection = [projection_dates, qe];

		//Hyperbolic projection
	    bp = 0;
	    for (var i = 1; i < n; i++) 
	    {
		    if (dq[i] !== 0)
		    {
		        dhr[i] = -(qprom[i] / (dq[i] / dt[i]));
		        dh[i] = 1 / dhr[i];
		    }
		    else
		    {
		        dhr[i] = 0;
		        dh[i] = 0;
		    }
		    dhp = dhp + dh[i];
	    }

		dhp = dhp / (n - 1);
		for (var i = 2; i < n; i++) 
		{
		    b[i] = (dhr[i] - dhr[i - 1]) / dt[i];
		    bp = bp + b[i];
		}

		bpp = bp / (n - 1);

		if(fluid_type === "oil")
		{
			if (bpp <= 0.09)
			{
				bp = 0;
				h = 0;
				for (var i = 2; i < n; i++) 
				{
				    b[i] = (dhr[i] - dhr[i - 1]) / dt[i];
				    if (b[i] <= 0.01)
				    {
				    	continue; //Cambiar
				    }
				    else
				    {
					    bp = bp + b[i];
					    h = h + 1;
				    }
				}
				bpp = bp / (h);
			}
		}
		else if(fluid_type === "water")
		{
			if (bpp <= 0.09)
			{
				bp = 0;
				h = 0;
				for (var i = 2; i < n; i++) 
				{
				    b[i] = (dhr[i] - dhr[i - 1]) / dt[i];
				    if (b[i] <= 0.01 || b[i] >= 1)
				    {
				    	continue; //Cambiar
				    }
				    else
				    {
					    bp = bp + b[i];
					    h = h + 1;
				    }
				}
				bpp = bp / (h);
			}
		}


		bppr = 1 / bpp;
		if(fluid_type === "oil")
		{
			for (var i = 0; i < nt; i++) 
			{
			    t[i] = deltatf * (i+1);
			    if (dh[n-1] < 0)
			    {
			        qh[i] = q[n-1] / Math.pow((1 + (bpp * -dh[n-1] * t[i])), bppr);
			    }
			    else
			    {
			        qh[i] = q[n-1] / Math.pow((1 + (bpp * dh[n-1] * t[i])), bppr);
			    }
			}
		}
		else if(fluid_type === "water")
		{
			for (var i = 0; i < nt; i++) 
			{
			    t[i] = deltatf * (i+1);
			    if (dh[n-1] > 0)
			    {
			        qh[i] = q[n-1] / Math.pow((1 + (bpp * -dh[n-1] * t[i])), bppr);
			    }
			    else
			    {
			        qh[i] = q[n-1] / Math.pow((1 + (bpp * dh[n-1] * t[i])), bppr);
			    }
			}
		}


		hyperbolic_projection = [projection_dates, qh];
		original_data = [original_dates, original_q];

		return [exponential_projection, hyperbolic_projection, original_data];
	}
	function plot_projection_chart(div, series_data, tittle, y_axis_tittle)
	{
	    Highcharts.chart(div, {
	        chart: {
	            zoomType: 'xy'
	        },
	        title: {
	            text: tittle
	        },

	        yAxis: {
	            title: {
	                text: y_axis_tittle
	            }
	        },
	        xAxis: {
	            type: 'datetime',
	            dateFormat: 'dd/mm/YYYY'
	        },
	        plotOptions: {
	            series: {
	                label: {
	                    connectorAllowed: false
	                }
	            }
	        },

	        series: series_data,

	        responsive: {
	            rules: [{
	                condition: {
	                    maxWidth: 500
	                },
	                chartOptions: {
	                    legend: {
	                        layout: 'horizontal',
	                        align: 'center',
	                        verticalAlign: 'bottom'
	                    }
	                }
	            }]
	        }
	    });
	}

	//Llamarla antes de guardar todos los datos de tablas - elmina nulos
	function clean_table_data(table_div_id)
	{
		container = $("#"+table_div_id); //Div de la tabla
		var table_data = container.handsontable('getData');
		var cleaned_data = [];

		$.each( table_data, function( rowKey, object) 
		{
		    if (!container.handsontable('isEmptyRow', rowKey))
		    {
		    	cleaned_data[rowKey] = object;	
		    } 
		});

		return cleaned_data;
	}
	function date_diff(date1, date2) 
	{
	    date1.setHours(0);
	    date1.setMinutes(0, 0, 0);
	    date2.setHours(0);
	    date2.setMinutes(0, 0, 0);
	    var datediff = Math.abs(date1.getTime() - date2.getTime()); // difference 
	    return parseInt(datediff / (24 * 60 * 60 * 1000), 10); //Convert values days and return value      
	}

	//Initial deposited fines concentration - popup
	function calculate_initial_deposited_fines()
	{
		pi = 3.14159265359;

		//Traer desde interfaz
		length = 4.218;
		diameter = 3.811;
		porosity = 0.147;

		illite = 4;
		kaolinite = 60;
		chlorite = 25;
		esmectite = 5;
		total_clays = 10;
		quartz = 89; //El de mineral information --> cambiemos information por data
		feldespar = 0;

		//Cálculo de finos depositados

		vol = length * (Math.pow(diameter / 2), 2) * pi;
		vp = vol * porosity;
		vplug = vol - vp;

		fracilita = illite / 100;
		frackaolinita = kaolinite / 100;
		fracclorita = chlorite / 100;
		fracesmectita = esmectite / 100;
		fraccuarzo = quartz / 100;
		fracfeldespato = feldespar / 100;
		fractarcilla = total_clays / 100;


		parcilla = (fracilita * 2.8) + (frackaolinita * 2.6) + (fracclorita * 2.6) + (fracesmectita * 2);
		pmineral = (fraccuarzo * 2.63) + (fracfeldespato * 2.5) + (fractarcilla * parcilla);

		masatotal = pmineral * vplug;
		masafinos = masatotal * fractarcilla;
		depositos = masafinos * 0.02;
		finosdep = depositos / vplug;

		return finosdep;
	}

</script>
