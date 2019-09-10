<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
//Dinámicas select tab Well

$(document).ready(function()
{
	query_data({{$field_id}});

	$("#basin").change(function(e)
	{
		$("#oil_ipr_data").hide();
		var basin = $("#basin").val(); 
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
		query_data($("#field").val());
	});	

	function query_data(field_id)
	{
		//Tabla PVT
		$("#io_fluid_properties_table tr").remove();
		$("#io_fluid_properties_table").empty();		

		$("#location_data_table tr").remove();
		$("#location_data_table").empty();

		var io_body_fluid = "";
		var body_location = "";

		var pressures = [];
		var uo = [];
		var ug = [];
		var uw = [];
		var bo = [];
		var bg = [];
		var bw = [];
		var rs = [];
		var rv = [];

		var lat = [];
		var lon = [];

		$.get('{!! url("field_inventory_detailed") !!}',
			{field:field_id},
			function(data)
			{
				if(data.coordinates.length==0)
					{
						body_location =  "<h4><font color='red'>There's no PVT Data for this Field</font><h4>";
					}
					else
					{
						body_location= "<thead><tr><th>Latitude[°]</th><th>Longitude[°]</th></tr></thead><tbody>";
						$.each(data.coordinates, function(index, value)
						{
							body_location += "<tr><td>"+value[0]+"</td>"+"<td>"+value[1]+"</td></tr>";
						});
					}
				if(data.pvt.length==0)
				{
					io_body_fluid =  "<h4><font color='red'>There's no PVT Data for this Field</font><h4>";
					$("#pvt_chart").hide();
				}
				else
				{
					io_body_fluid= "<thead><tr><th>Pressure[psi]</th><th>Uo[cP]</th><th>Ug[cP]</th><th>Uw[cP]</th><th>Bo[RB/STB]</th><th>Bg[RCF/SCF]</th><th>Bw[SCF/STB]</th><th>Rs[SCF/STB]</th><th>Rv[STB/SCF]</th></tr></thead><tbody>";

					$.each(data.pvt, function(index, value)
					{
						io_body_fluid += "<tr><td>"+value[0]+"</td>"+"<td>"+value[1]+"</td>"+"<td>"+value[2]+"</td>"+"<td>"+value[3]+"</td>"+"<td>"+value[4]+"</td>"+"<td>"+value[5]+"</td>"+"<td>"+value[6]+"</td>"+"<td>"+value[7]+"</td>"+"<td>"+value[8]+"</td></tr>";

						pressures.push(value[0]);
						uo.push(value[1]);
						ug.push(value[2]);
						uw.push(value[3]);
						bo.push(value[4]);
						bg.push(value[5]);
						bw.push(value[6]);
						rs.push(value[7]);
						rv.push(value[8]);
					});

					$("#pvt_chart").show();
					$('#pvt_chart').highcharts({
						title: {
						    text: 'PVT Data',
						    x: -20 //center
						},
						xAxis: {
						 title: {
						   text: 'Pressure [psi]'
						 },
						    categories: pressures
						},
						yAxis: {
						    title: {
						        text: 'PVT Data'
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
						    name: 'Uo',
						    data: uo,
						    tooltip:{
						     valueSuffix: ' cP'
						    }
						}, {
						    name: 'Ug',
						    data: ug,
						    tooltip:{
						     valueSuffix: ' cP'
						    }
						}, {
						    name: 'Uw',
						    data: uw,
						    tooltip:{
						     valueSuffix: ' cp'
						    }
						},{
						    name: 'Bo',
						    data: bo,
						    tooltip:{
						     valueSuffix: ' RB/STB'
						    }
						}, {
						    name: 'Bg',
						    data: bg,
						    tooltip:{
						     valueSuffix: ' RCF/SCF'
						    }
						}, {
						    name: 'Bw',
						    data: bw,
						    tooltip:{
						     valueSuffix: ' SCF/STB'
						    }
						},{
						    name: 'Rs',
						    data: rs,
						    tooltip:{
						     valueSuffix: ' SCF/STB'
						    }
						}, {
			                name: 'Rv',
			                data: rv,
			                tooltip:{
			                 valueSuffix: ' STB/SCF'
			                }
			            }]
			        });
				}
				$("#oil_ipr_data").show();
				$("#io_fluid_properties_table").append(io_body_fluid);
				$("#location_data_table").append(body_location);
				
			});



	}
});



</script>