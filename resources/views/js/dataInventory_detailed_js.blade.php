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

		//IPR Oil
		$("#io_rock_properties_table tr").remove();
		$("#io_rock_properties_table").empty();

		$("#io_production_data_table tr").remove();
		$("#io_production_data_table").empty();

		$("#io_fluid_properties_table tr").remove();
		$("#io_fluid_properties_table").empty();

		//IPR Gas
		$("#ig_rock_properties_table tr").remove();
		$("#ig_rock_properties_table").empty();

		$("#ig_production_data_table tr").remove();
		$("#ig_production_data_table").empty();

		//IPR Gas Condensado
		$("#icg_rock_properties_table tr").remove();
		$("#icg_rock_properties_table").empty();

		$("#icg_production_data_table tr").remove();
		$("#icg_production_data_table").empty();

		$("#icg_fluid_properties_table tr").remove();
		$("#icg_fluid_properties_table").empty();

		//IPR Gas Condensado
		$("#d_rock_properties_table tr").remove();
		$("#d_rock_properties_table").empty();

		$("#d_production_data_table tr").remove();
		$("#d_production_data_table").empty();

		$("#d_fluid_properties_table tr").remove();
		$("#d_fluid_properties_table").empty();


		var body_rock = '';
		var body_production = '';
		var body_fluid = '';

		//Multiparamétrico
		body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		body_production = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		body_fluid = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";

		//IPR Oil
		var io_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var io_body_production = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var io_body_fluid = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";

		//IPR Gas
		var ig_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var ig_body_production = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";


		//IPR Condensate Gas
		var icg_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var icg_body_production = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var icg_body_fluid = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";

		//Disaggregation
		var d_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var d_body_production = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var d_body_fluid = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";


		$.get('{!! url("well_inventory_detailed") !!}',
			{well:well_id},
			function(data)
			{
				$.each(data.generalData, function(index,value)
				{
					body_rock+= "<tr>";
					d_body_rock+= "<tr>";
					if(value.tdv == null || value.tdv === '')
					{
						body_rock += "<td>TVD</td><td><font color='red'>Missing Information</font></td>";
						d_body_rock += "<td>TVD</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_rock += "<td>TVD</td><td><font color='green'>"+value.tdv+"</font></td>";
						d_body_rock += "<td>TVD</td><td><font color='green'>"+value.tdv+"</font></td>";
					}
					body_rock+= "</tr>";
					d_body_rock+= "</tr>";

					body_production += "<tr>";
					io_body_rock += "<tr>";
					ig_body_rock += "<tr>";
					icg_body_rock += "<tr>";
					d_body_rock += "<tr>";
					if(value.radius == null || value.radius === '')
					{
						body_production  += "<td>Well Radius</td><td><font color='red'>Missing Information</font></td>";
						io_body_rock  += "<td>Well Radius</td><td><font color='red'>Missing Information</font></td>";
						ig_body_rock  += "<td>Well Radius</td><td><font color='red'>Missing Information</font></td>";
						icg_body_rock  += "<td>Well Radius</td><td><font color='red'>Missing Information</font></td>";
						d_body_rock  += "<td>Well Radius</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Well Radius</td><td><font color='green'>"+value.radius+"</font></td>";
						io_body_rock  += "<td>Well Radius</td><td><font color='green'>"+value.radius+"</font></td>";
						ig_body_rock  += "<td>Well Radius</td><td><font color='green'>"+value.radius+"</font></td>";
						icg_body_rock  += "<td>Well Radius</td><td><font color='green'>"+value.radius+"</font></td>";
						d_body_rock  += "<td>Well Radius</td><td><font color='green'>"+value.radius+"</font></td>";
					}
					body_production += "</tr>";
					io_body_rock += "</tr>";
					ig_body_rock += "</tr>";
					icg_body_rock += "</tr>";
					d_body_rock += "</tr>";

					body_production += "<tr>";
					io_body_rock += "<tr>";
					ig_body_rock += "<tr>";
					icg_body_rock += "<tr>";
					d_body_rock += "<tr>";
					if(value.drainage_radius == null || value.drainage_radius === '')
					{
						body_production  += "<td>Drainage Radius</td><td><font color='red'>Missing Information</font></td>";
						io_body_rock  += "<td>Drainage Radius</td><td><font color='red'>Missing Information</font></td>";
						ig_body_rock  += "<td>Drainage Radius</td><td><font color='red'>Missing Information</font></td>";
						icg_body_rock  += "<td>Drainage Radius</td><td><font color='red'>Missing Information</font></td>";
						d_body_rock  += "<td>Drainage Radius</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Drainage Radius</td><td><font color='green'>"+value.drainage_radius+"</font></td>";
						io_body_rock  += "<td>Drainage Radius</td><td><font color='green'>"+value.drainage_radius+"</font></td>";
						ig_body_rock  += "<td>Drainage Radius</td><td><font color='green'>"+value.drainage_radius+"</font></td>";
						icg_body_rock  += "<td>Drainage Radius</td><td><font color='green'>"+value.drainage_radius+"</font></td>";
						d_body_rock  += "<td>Drainage Radius</td><td><font color='green'>"+value.drainage_radius+"</font></td>";
					}
					body_production += "</tr>";
					io_body_rock += "</tr>";
					ig_body_rock += "</tr>";
					icg_body_rock += "</tr>";
					d_body_rock += "</tr>";

					body_production += "<tr>";
					io_body_production += "<tr>";
					ig_body_production += "<tr>";
					icg_body_production += "<tr>";
					d_body_production += "<tr>";
					if(value.bhp == null || value.bhp === '')
					{
						body_production  += "<td>BHP</td><td><font color='red'>Missing Information</font></td>";
						io_body_production  += "<td>BHP</td><td><font color='red'>Missing Information</font></td>";
						ig_body_production  += "<td>BHP</td><td><font color='red'>Missing Information</font></td>";
						icg_body_production  += "<td>BHP</td><td><font color='red'>Missing Information</font></td>";
						d_body_production  += "<td>BHP</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>BHP</td><td><font color='green'>"+value.bhp+"</font></td>";
						io_body_production  += "<td>BHP</td><td><font color='green'>"+value.bhp+"</font></td>";
						ig_body_production  += "<td>BHP</td><td><font color='green'>"+value.bhp+"</font></td>";
						icg_body_production  += "<td>BHP</td><td><font color='green'>"+value.bhp+"</font></td>";
						d_body_production  += "<td>BHP</td><td><font color='green'>"+value.bhp+"</font></td>";
					}
					body_production += "</tr>";
					io_body_production += "</tr>";
					ig_body_production += "</tr>";
					icg_body_production += "</tr>";
					d_body_production += "</tr>";

					body_production += "<tr>";
					d_body_production += "<tr>";
					if(value.oil_rate == null || value.oil_rate === '')
					{
						body_production  += "<td>Oil Rate</td><td><font color='red'>Missing Information</font></td>";
						d_body_production  += "<td>Oil Rate</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Oil Rate</td><td><font color='green'>"+value.oil_rate+"</font></td>";
						d_body_production  += "<td>Oil Rate</td><td><font color='green'>"+value.oil_rate+"</font></td>";
					}
					body_production += "</tr>";
					d_body_production += "</tr>";

					body_production += "<tr>";
					ig_body_production += "<tr>";
					icg_body_production += "<tr>";
					d_body_production += "<tr>";
					if(value.gas_rate == null || value.gas_rate === '')
					{
						body_production  += "<td>Gas Rate</td><td><font color='red'>Missing Information</font></td>";
						ig_body_production  += "<td>Gas Rate</td><td><font color='red'>Missing Information</font></td>";
						icg_body_production  += "<td>Gas Rate</td><td><font color='red'>Missing Information</font></td>";
						d_body_production  += "<td>Gas Rate</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_production  += "<td>Gas Rate</td><td><font color='green'>"+value.gas_rate+"</font></td>";
						ig_body_production  += "<td>Gas Rate</td><td><font color='green'>"+value.gas_rate+"</font></td>";
						icg_body_production  += "<td>Gas Rate</td><td><font color='green'>"+value.gas_rate+"</font></td>";
						d_body_production  += "<td>Gas Rate</td><td><font color='green'>"+value.gas_rate+"</font></td>";
					}
					body_production += "</tr>";
					ig_body_production += "</tr>";
					icg_body_production += "</tr>";
					d_body_production += "</tr>";
				});
				$.each(data.fluidData, function(index, value)
				{
					body_fluid += "<tr>";
					io_body_rock += "<tr>";
					ig_body_rock += "<tr>";
					icg_body_rock += "<tr>";
					if(value.tipo == null || !value.tipo=='')
					{
						body_fluid  += "<td>Fluid Type</td><td><font color='red'>Missing Information</font></td>";
						io_body_rock  += "<td>Fluid Type</td><td><font color='red'>Missing Information</font></td>";
						ig_body_rock  += "<td>Fluid Type</td><td><font color='red'>Missing Information</font></td>";
						icg_body_rock  += "<td>Fluid Type</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Fluid Type</td><td><font color='green'>"+value.tipo+"</font></td>";
						io_body_rock  += "<td>Fluid Type</td><td><font color='green'>"+value.tipo+"</font></td>";
						ig_body_rock  += "<td>Fluid Type</td><td><font color='green'>"+value.tipo+"</font></td>";
						icg_body_rock  += "<td>Fluid Type</td><td><font color='green'>"+value.tipo+"</font></td>";
					}
					body_fluid += "</tr>";
					io_body_rock += "</tr>";
					ig_body_rock += "</tr>";
					icg_body_rock += "</tr>";

					body_fluid += "<tr>";
					io_body_fluid += "<tr>";
					icg_body_fluid += "<tr>";
					if(value.saturation_pressure == null || value.saturation_pressure === '')
					{
						body_fluid  += "<td>Saturation Pressure</td><td><font color='red'>Missing Information</font></td>";
						io_body_fluid  += "<td>Saturation Pressure</td><td><font color='red'>Missing Information</font></td>";
						icg_body_fluid  += "<td>Saturation Pressure</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Saturation Pressure</td><td><font color='green'>"+value.saturation_pressure+"</font></td>";
						io_body_fluid  += "<td>Saturation Pressure</td><td><font color='green'>"+value.saturation_pressure+"</font></td>";
						icg_body_fluid  += "<td>Saturation Pressure</td><td><font color='green'>"+value.saturation_pressure+"</font></td>";
					}
					body_fluid += "</tr>";
					io_body_fluid += "</tr>";
					icg_body_fluid += "</tr>";

					body_fluid += "<tr>";
					d_body_fluid += "<tr>";
					if(value.oil_viscosity == null || value.oil_viscosity === '')
					{
						body_fluid  += "<td>Oil Viscosity</td><td><font color='red'>Missing Information</font></td>";
						d_body_fluid  += "<td>Oil Viscosity</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Oil Viscosity</td><td><font color='green'>"+value.oil_viscosity+"</font></td>";
						d_body_fluid  += "<td>Oil Viscosity</td><td><font color='green'>"+value.oil_viscosity+"</font></td>";
					}
					body_fluid += "</tr>";
					d_body_fluid += "</tr>";

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
					d_body_fluid += "<tr>";
					if(value.gas_viscosity == null || value.gas_viscosity === '')
					{
						body_fluid  += "<td>Gas Viscosity</td><td><font color='red'>Missing Information</font></td>";
						d_body_fluid  += "<td>Gas Viscosity</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						body_fluid  += "<td>Gas Viscosity</td><td><font color='green'>"+value.gas_viscosity+"</font></td>";
						d_body_fluid  += "<td>Gas Viscosity</td><td><font color='green'>"+value.gas_viscosity+"</font></td>";
					}
					body_fluid += "</tr>";
					d_body_fluid += "</tr>";

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

					icg_body_fluid += "<tr>";
					if(value.gor == null || value.gor === '')
					{
						icg_body_fluid  += "<td>Gor</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						icg_body_fluid  += "<td>Gor</td><td><font color='green'>"+value.gor+"</font></td>";
					}
					icg_body_fluid += "</tr>";

					
					d_body_fluid += "<tr>";
					if(value.specific_gas == null || value.specific_gas === '')
					{
						d_body_fluid  += "<td>Specific Gas Gravity</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						d_body_fluid  += "<td>Specific Gas Gravity</td><td><font color='green'>"+value.specific_gas+"</font></td>";
					}
					d_body_fluid += "</tr>";

				});
				//Multiparamétrico
 				$("#m_rock_properties_table").append(body_rock);
				$("#m_production_data_table").append(body_production);
				$("#m_fluid_properties_table").append(body_fluid);
				//IPR Oil
				$("#io_rock_properties_table").append(io_body_rock);
				$("#io_production_data_table").append(io_body_production);
				$("#io_fluid_properties_table").append(io_body_fluid);
				//IPR Gas
				$("#ig_rock_properties_table").append(ig_body_rock);
				$("#ig_production_data_table").append(ig_body_production);
				//IPR Gas Condensado
				$("#icg_rock_properties_table").append(icg_body_rock);
				$("#icg_production_data_table").append(icg_body_production);
				$("#icg_fluid_properties_table").append(icg_body_fluid);
				//Desagregación
				$("#d_rock_properties_table").append(d_body_rock);
				$("#d_production_data_table").append(d_body_production);
				$("#d_fluid_properties_table").append(d_body_fluid);
			});
	}
});



</script>