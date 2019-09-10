<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
//Dinámicas select tab Well

$(document).ready(function()
{
	query_data({{$well_id}});

	$("#basin").change(function(e)
	{

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
		var field = $("#field").val(); 
		$("#wellInfoPanels").hide();
		$.get("{!! url('wellInventory') !!}",
			{field : field},
			function(data)
			{
				$("#well").empty();
				$.each(data.wells, function(index,value)
				{
					$("#well").append('<option value="'+value.id+'">'+value.nombre+'</option>');
				});
				$("#well").selectpicker('refresh');
				$('#well').selectpicker('val', '');
			});
	});	

	$("#well").change(function(e)
	{
		query_data($("#well").val());
	});	

	function query_data(well_id)
	{
		//Multiparamétrico
		$("#m_rock_properties_table tr").remove();
		$("#m_rock_properties_table").empty();

		$("#m_production_data_table tr").remove();
		$("#m_production_data_table").empty();

		$("#m_fluid_properties_table tr").remove();
		$("#m_fluid_properties_table").empty();

		$("#production_data_table tr").remove();
		$("#production_data_table").empty();
		
		var body_rock = '';
		var body_production = '';
		var body_fluid = '';
		var body_historic_production = '';

		//Multiparamétrico
		body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		body_production = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		body_fluid = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		


		$.get('{!! url("well_inventory_detailed") !!}',
			{well:well_id},
			function(data)
			{
				$.each(data.generalData, function(index,value)
				{
					body_rock+= "<tr>";
					if(value.tdv == null || value.tdv === '')
					{
						body_rock += "<td>TVD</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_rock += "<td>TVD</td><td><font color='green'>"+value.tdv+"</font></td>";
					}
					body_rock+= "</tr>";

					body_production += "<tr>";
					if(value.radius == null || value.radius === '')
					{
						body_production  += "<td>Well Radius</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Well Radius</td><td><font color='green'>"+value.radius+"</font></td>";
					}
					body_production += "</tr>";

					body_production += "<tr>";
					if(value.drainage_radius == null || value.drainage_radius === '')
					{
						body_production  += "<td>Drainage Radius</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Drainage Radius</td><td><font color='green'>"+value.drainage_radius+"</font></td>";
					}
					body_production += "</tr>";

					body_production += "<tr>";
					if(value.bhp == null || value.bhp === '')
					{
						body_production  += "<td>BHP</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>BHP</td><td><font color='green'>"+value.bhp+"</font></td>";
					}
					body_production += "</tr>";

					body_production += "<tr>";
					if(value.oil_rate == null || value.oil_rate === '')
					{
						body_production  += "<td>Oil Rate</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Oil Rate</td><td><font color='green'>"+value.oil_rate+"</font></td>";
					}
					body_production += "</tr>";

					body_production += "<tr>";
					if(value.gas_rate == null || value.gas_rate === '')
					{
						body_production  += "<td>Gas Rate</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Gas Rate</td><td><font color='green'>"+value.gas_rate+"</font></td>";
					}
					body_production += "</tr>";
				});
				$.each(data.fluidData, function(index, value)
				{
					body_fluid += "<tr>";
					if(value.tipo == null || !value.tipo=='')
					{
						body_fluid  += "<td>Fluid Type</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Fluid Type</td><td><font color='green'>"+value.tipo+"</font></td>";
					}
					body_fluid += "</tr>";

					body_fluid += "<tr>";
					if(value.saturation_pressure == null || value.saturation_pressure === '')
					{
						body_fluid  += "<td>Saturation Pressure</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Saturation Pressure</td><td><font color='green'>"+value.saturation_pressure+"</font></td>";
					}
					body_fluid += "</tr>";

					body_fluid += "<tr>";
					if(value.oil_viscosity == null || value.oil_viscosity === '')
					{
						body_fluid  += "<td>Oil Viscosity</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Oil Viscosity</td><td><font color='green'>"+value.oil_viscosity+"</font></td>";
					}
					body_fluid += "</tr>";

					body_fluid += "<tr>";
					if(value.fvfo == null || value.fvfo === '')
					{
						body_fluid  += "<td>Oil FVF</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Oil FVF</td><td><font color='green'>"+value.fvfo+"</font></td>";
					}
					body_fluid += "</tr>";

					body_fluid += "<tr>";
					if(value.gas_viscosity == null || value.gas_viscosity === '')
					{
						body_fluid  += "<td>Gas Viscosity</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Gas Viscosity</td><td><font color='green'>"+value.gas_viscosity+"</font></td>";
					}
					body_fluid += "</tr>";

					body_fluid += "<tr>";
					if(value.fvfg == null || value.fvfg === '')
					{
						body_fluid  += "<td>Gas FVF</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Gas FVF</td><td><font color='green'>"+value.fvfg+"</font></td>";
					}
					body_fluid += "</tr>";

					body_fluid += "<tr>";
					if(value.fvfw == null || value.fvfw === '')
					{
						body_fluid  += "<td>Water FVF</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Water FVF</td><td><font color='green'>"+value.fvfw+"</font></td>";
					}
					body_fluid += "</tr>";

					body_fluid += "<tr>";
					if(value.gor == null || value.gor === '')
					{
						body_fluid  += "<td>Gor</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Gor</td><td><font color='green'>"+value.gor+"</font></td>";
					}
					body_fluid += "</tr>";

					
					body_fluid += "<tr>";
					if(value.specific_gas == null || value.specific_gas === '')
					{
						body_fluid  += "<td>Specific Gas Gravity</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Specific Gas Gravity</td><td><font color='green'>"+value.specific_gas+"</font></td>";
					}
					body_fluid += "</tr>";

				});

				if(data.productionData.length==0)
				{
					body_historic_production += "<h4><font color='red'>There's no historic production data for this well</font></h4>"; 
				}
				else
				{
					body_historic_production = "<thead><tr><th>Date</th><th>Qo</th><th>Cummulative Qo</th><th>Qg</th><th>Cummulative Qg</th><th>Qw</th><th>Cummulative Qw</th></tr></thead><tbody>";
					$.each(data.productionData, function(index, value)
					{
						body_historic_production += "<tr><td>"+value[0]+"</td><td>"+value[1]+"</td><td>"+value[2]+"</td><td>"+value[3]+"</td><td>"+value[4]+"</td><td>"+value[5]+"</td><td>"+value[6]+"</td></tr>";
					});
				}

				//Multiparamétrico
 				$("#m_rock_properties_table").append(body_rock);
				$("#m_production_data_table").append(body_production);
				$("#m_fluid_properties_table").append(body_fluid);
				$("#production_data_table").append(body_historic_production);

			});
	}
});



</script>