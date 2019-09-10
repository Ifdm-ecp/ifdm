<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
//Dinámicas select tab Well

$(document).ready(function()
{
	query_data({{$formation_id}});

	$("#basin").change(function(e)
	{
		$("#multiparametric_data").hide();
		$("#oil_ipr_data").hide();
		$("#disaggregation_data").hide();
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
		$("#multiparametric_data").hide();
		$("#oil_ipr_data").hide();
		$("#disaggregation_data").hide();
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
		$("#multiparametric_data").show();
		$("#oil_ipr_data").show();
		$("#disaggregation_data").show();
		query_data($("#formation").val());
	});	

	function query_data(formation_id)
	{
		//Multiparamétrico
		$("#m_rock_properties_table tr").remove();
		$("#m_rock_properties_table").empty();

		$("#m_production_data_table tr").remove();
		$("#m_production_data_table").empty();

		//IPR
		$("#io_rock_properties_table tr").remove();
		$("#io_rock_properties_table").empty();

		//Desagregación
		$("#d_rock_properties_table tr").remove();
		$("#d_rock_properties_table").empty();


		//Multiparamétrico
		var m_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		var m_body_production = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		//IPR
		var io_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
		//Desagregación
		var d_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";

		$.get('{!! url("formation_inventory_detailed") !!}',
			{formation:formation_id},
			function(data)
			{
				$.each(data.formation, function(index,value)
				{
					m_body_rock+= "<tr>";
					if(value.top == null || value.top === '')
					{
						m_body_rock += "<td>Top</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						m_body_rock += "<td>Top</td><td><font color='green'>"+value.top+"</font></td>";
					}
					m_body_rock+= "</tr>";

					m_body_rock += "<tr>";
					io_body_rock += "<tr>";
					d_body_rock += "<tr>";
					if(value.netpay == null || value.netpay === '')
					{
						m_body_rock  += "<td>Netpay</td><td><font color='red'>Missing Information</font></td>";
						io_body_rock  += "<td>Netpay</td><td><font color='red'>Missing Information</font></td>";
						d_body_rock  += "<td>Netpay</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						m_body_rock  += "<td>Netpay</td><td><font color='green'>"+value.netpay+"</font></td>";
						io_body_rock  += "<td>Netpay</td><td><font color='green'>"+value.netpay+"</font></td>";
						d_body_rock  += "<td>Netpay</td><td><font color='green'>"+value.netpay+"</font></td>";
					}
					m_body_rock += "</tr>";
					io_body_rock += "</tr>";
					d_body_rock += "</tr>";


					m_body_rock += "<tr>";
					if(value.porosidad == null || value.porosidad === '')
					{
						m_body_rock  += "<td>Porosity</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						m_body_rock  += "<td>Porosity</td><td><font color='green'>"+value.porosidad+"</font></td>";
					}
					m_body_rock += "</tr>";

					m_body_rock += "<tr>";
					d_body_rock += "<tr>";
					if(value.permeabilidad == null || value.permeabilidad === '')
					{
						m_body_rock  += "<td>Permeability</td><td><font color='red'>Missing Information</font></td>";
						d_body_rock  += "<td>Permeability</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						m_body_rock  += "<td>Permeability</td><td><font color='green'>"+value.permeabilidad+"</font></td>";
						d_body_rock  += "<td>Permeability</td><td><font color='green'>"+value.permeabilidad+"</font></td>";
					}
					m_body_rock += "</tr>";
					d_body_rock += "</tr>";

					m_body_production += "<tr>";
					io_body_rock += "<tr>";
					d_body_rock += "<tr>";
					if(value.presion_reservorio == null || value.presion_reservorio === '')
					{
						m_body_production  += "<td>Reservoir Pressure</td><td><font color='red'>Missing Information</font></td>";
						io_body_rock  += "<td>Reservoir Pressure</td><td><font color='red'>Missing Information</font></td>";
						d_body_rock  += "<td>Reservoir Pressure</td><td><font color='red'>Missing Information</font></td>";
					}
					else
					{
						m_body_production  += "<td>Reservoir Pressure</td><td><font color='green'>"+value.presion_reservorio+"</font></td>";
						io_body_rock  += "<td>Reservoir Pressure</td><td><font color='green'>"+value.presion_reservorio+"</font></td>";
						d_body_rock  += "<td>Reservoir Pressure</td><td><font color='green'>"+value.presion_reservorio+"</font></td>";
					}
					m_body_production += "</tr>";
					io_body_rock += "</tr>";
					d_body_rock += "</tr>";

				});

				//Multiparamétrico
 				$("#m_rock_properties_table").append(m_body_rock);
				$("#m_production_data_table").append(m_body_production);
				//IPR
				$("#io_rock_properties_table").append(io_body_rock);
				//Desagregación
				$("#d_rock_properties_table").append(d_body_rock);

			});
	}
});



</script>