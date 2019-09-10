<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
//Dinámicas select tab Well

$(document).ready(function()
{
	query_data({{$interval_id}});

	$("#basin").change(function(e)
	{

		var basin = $("#basin").val(); 
		$("#oil_ipr_data").hide();
		$("#condensate_gas_ipr_data").hide();
		$("#drilling_data").hide();
		$.get("{!! url('fieldInventory') !!}",
			{basin : basin},
			function(data)
			{
				$("#field").empty();
				$.each(data.fields, function(index,value)
				{
					$("#field").append('<option value="'+value.id+'">'+value.nombre+'</option>');
				});
				$("#field").selectpicker('refresh');
				$('#field').selectpicker('val', '');
			});
	});	

	$("#field").change(function(e)
	{
		$("#oil_ipr_data").hide();
		$("#condensate_gas_ipr_data").hide();
		$("#drilling_data").hide();
		var field = $("#field").val(); 
		$.get("{!! url('formationInventory') !!}",
			{field : field},
			function(data)
			{
				$("#formation").empty();
				$.each(data.formations, function(index,value)
				{
					$("#formation").append('<option value="'+value.id+'">'+value.nombre+'</option>');
				});
				$("#formation").selectpicker('refresh');
				$('#formation').selectpicker('val', '');
			});
	});	

	$("#formation").change(function(e)
	{
		$("#oil_ipr_data").hide();
		$("#condensate_gas_ipr_data").hide();
		$("#drilling_data").hide();
		var formation = $("#formation").val(); 
		$.get("{!! url('intervals_inventory_filter') !!}",
			{formation : formation},
			function(data)
			{
				$("#interval").empty();
				$.each(data.intervals, function(index,value)
				{
					$("#interval").append('<option value="'+value.id+'">'+value.nombre+'</option>');
				});
				$("#interval").selectpicker('refresh');
				$('#interval').selectpicker('val', '');
			});
	});		

	$("#interval").change(function(e)
	{
		$("#oil_ipr_data").show();
		$("#condensate_gas_ipr_data").show();
		$("#drilling_data").show();
		query_data($("#interval").val());
	});	


	function query_data(interval_id)
	{
		//Detailed Data Interval
		$("#gas_oil_table tr").remove();
		$("#water_oil_table tr").remove();

		$("#water_oil_table").empty();
		$("#gas_oil_table").empty();

		$("#d_general_data_table tr").remove();
		$("#d_general_data_table").empty();

		//IPR Oil y Gas condensado
		var gas_oil_table = "";
		var water_oil_table = "";
		//Gráficos
		var sg = [];
		var krg = [];
		var krog = [];
		
		var sw = [];
		var krw = [];
		var kro = [];

		//General Data
		var d_body = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";

		$.get('{!! url("interval_inventory_detailed") !!}',
			{interval:interval_id},
			function(data)
			{
				$.each(data.interval, function(index,value)
				{
					d_body+= "<tr>";
					if(value.top == null || value.top === '')
					{
						d_body += "<td>Top</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						d_body += "<td>Top</td><td><font color='green'>"+value.top+"</font></td>";
					}
					d_body+= "</tr>";

					d_body+= "<tr>";
					if(value.netpay == null || value.netpay === '')
					{
						d_body += "<td>Netpay</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						d_body += "<td>Netpay</td><td><font color='green'>"+value.netpay+"</font></td>";
					}
					d_body+= "</tr>";

					d_body += "<tr>";
					if(value.presion_reservorio == null || value.presion_reservorio === '')
					{
						d_body  += "<td>Reservoir Pressure</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						d_body  += "<td>Reservoir Pressure</td><td><font color='green'>"+value.presion_reservorio+"</font></td>";
					}
					d_body += "</tr>";


					d_body += "<tr>";
					if(value.porosidad == null || value.porosidad === '')
					{
						d_body  += "<td>Drainage Radius</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						d_body  += "<td>Drainage Radius</td><td><font color='green'>"+value.porosidad+"</font></td>";
					}
					d_body += "</tr>";


					d_body += "<tr>";
					if(value.permeabilidad == null || value.permeabilidad === '')
					{
						d_body  += "<td>Permeability</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						d_body  += "<td>Permeability</td><td><font color='green'>"+value.permeabilidad+"</font></td>";
					}
					d_body += "</tr>";
				});

				if(data.gas_oil.length==0)
				{
					gas_oil_table += "<h4><font color='red'>There's no Gas-Oil Data for this Producing Interval</font></h4>";
					$("#gas_oil_chart").hide();
					$("#water_oil_chart").hide();
				}
				else
				{
					gas_oil_table = "<thead><tr><th>Sg</th><th>Krg</th><th>Krl</th></tr></thead><tbody>";
					$.each(data.gas_oil, function(index, value)
					{
						gas_oil_table += "<tr><td>"+value[0]+"</td>"+"<td>"+value[1]+"</td>"+"<td>"+value[2]+"</td></tr>";
						sg.push(value[0]);
						krg.push(value[1]);
						krog.push(value[2]);
					});


					$('#gas_oil_chart').highcharts({
					       title: {
					           text: 'Gas-Oil Kr\'s',
					           x: -20 //center
					       },
					       subtitle: {
					           text: '',
					           x: -20
					       },
					       xAxis: {
					          title: {text:'Sg'},
					           categories: sg
					       },
					       yAxis: {
					           title: {
					               text: 'Krg & Krog'
					           },
					           plotLines: [{
					               value: 0,
					               width: 1,
					               color: '#808080'
					           }]
					       },
					       tooltip: {
					           valueSuffix: ''
					       },
					       legend: {
					           layout: 'vertical',
					           align: 'right',
					           verticalAlign: 'middle',
					           borderWidth: 0
					       },
					       series: [{
					           name: 'Krg',
					           data: krg
					       }, {
					           name: 'Krog',
					           data: krog
					       }]
					   });

					$("#gas_oil_chart").show();
				}

				if(data.water_oil.length==0)
				{
					water_oil_table += "<h4><font color='red'>There's no Water-Oil Data for this Producing Interval</font></h4>";
					$("#gas_oil_chart").hide();
					$("#water_oil_chart").hide();
				}
				else
				{
					water_oil_table = "<thead><tr><th>Sw</th><th>Krw</th><th>Kro</th></tr></thead><tbody>";
					$.each(data.water_oil, function(index, value)
					{
						water_oil_table += "<tr><td>"+value[0]+"</td>"+"<td>"+value[1]+"</td>"+"<td>"+value[2]+"</td></tr>";
						sw.push(value[0]);
						krw.push(value[1]);
						kro.push(value[2]);
					});

					$('#water_oil_chart').highcharts({
					       title: {
					           text: 'Water-Oil Kr\'s',
					           x: -20 //center
					       },
					       xAxis: {
					        title: {
					          text: 'Sw'
					        },
					           categories: sw
					       },
					       yAxis: {
					           title: {
					               text: 'Krw & Kro'
					           },
					           plotLines: [{
					               value: 0,
					               width: 1,
					               color: '#808080'
					           }]
					       },
					       tooltip: {
					           valueSuffix: ''
					       },
					       legend: {
					           layout: 'vertical',
					           align: 'right',
					           verticalAlign: 'middle',
					           borderWidth: 0
					       },
					       series: [{
					           name: 'Krw',
					           data: krw
					       }, {
					           name: 'Kro',
					           data: kro
					       }]
					   });

					$("#water_oil_chart").show();
				}

				//General Data
				$("#d_general_data_table").append(d_body);
				$("#gas_oil_table").append(gas_oil_table);
				$("#water_oil_table").append(water_oil_table);

			});
	}
});



</script>