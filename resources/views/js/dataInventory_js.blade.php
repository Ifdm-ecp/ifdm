<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script type="text/javascript" src="https://www.highcharts.com/samples/data/usdeur.js"></script>

<script type="text/javascript">
//Dinámicas select tab Well
$(document).ready(function()
{
	$("#basin").change(function(e)
	{	
		$("#field_data_inventory tr").remove();
		$("#field_data_inventory").empty();
		$(".basin_data").show();
		$(".field_data").hide();
		$(".formation_data").hide();
		$("#formation_data").hide();

		var basin = $("#basin").val(); 
		var body = '';
		$.get("{{url('fieldInventory')}}",
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
				if(data.fields_info.length==0)
				{
					body = "<h3><font color='red'>There's no Fields for this Basin. Please choose another one.</font></h3>";
					$("#field_data").hide();
				}
				else
				{
					body = "<thead><tr><th>Field</th><th>Multiparametric</th><th>IPR</th><th>Disaggregation</th><th>Drilling And Completion</th><th>Detailed Data</th></tr></thead><tbody>";
					$.each(data.fields_info, function(index,value)
					{
						body+= "<tr>";
						if(value[1])
						{
							body+= "<td>"+value[0]+"</td>"+"<td><font color='green'>NA</font></td>"+"<td><font color='green'><b>100%</b></font></td>"+"<td><font color='green'>NA</font></td>"+"<td><font color='green'>NA</font></td>";
						}
						else
						{
							body+= "<td>"+value[0]+"</td>"+"<td><font color='green'>NA</font></td>"+"<td><font color='red'>0%</font></td>"+"<td><font color='green'>NA</font></td>"+"<td><font color='green'>NA</font></td>";
						}
						var string_check = '<td><a href="{{route("detailed_data_field" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[2]);
						body+= string_check;
						body+= "</tr>";
					});
					$("#field_data").show();	
				}
				$("#field_data_inventory").append(body);

			});
	});	
	$("#field").change(function(e)
	{
		$("#well_data_inventory tr").remove();
		$("#formation_data_inventory tr").remove();
		$("#well_data_inventory").empty();
		$("#formation_data_inventory").empty();
		$(".field_data").show();
		$(".formation_data").hide();
		var field = $("#field").val(); 
		var body_formation = "";
		var body = "";

		//Gráficos
		//Categorías - eje x
		var categories_formations = [];
		var categories_well = [];

		//Series - eje y formación
		var serie_multiparametric = [];
		var serie_ipr = [];
		var serie_disaggregation = [];

		//Series - eje y formación
		var serie_multiparametric_well = [];
		var serie_oil_ipr_well = [];
		var serie_gas_ipr_well = [];
		var serie_condensate_gas_ipr_well = [];
		var serie_disaggregation_well = [];

		//$("#wellInfoPanels").hide();
		$.get("{{url('formationInventory')}}",
			{field : field},
			function(data)
			{
				if(data.formations.length==0)
				{
					body_formation = "<h3><font color='red'>There's no Formations for this Field. Please choose another one.</font></h3>";
					$("#formation_data").hide();
					$("#formation_chart").hide();
				}
				else
				{
					$("#formation").empty();
					$.each(data.formations, function(index,value)
					{
						$("#formation").append('<option value="'+value.id+'">'+value.nombre+'</option>');
						categories_formations.push(value.nombre);
					});
					$("#formation").selectpicker('refresh');
					$('#formation').selectpicker('val', '');
					body_formation = "<thead><tr><th>Formation</th><th>Multiparametric</th><th>Oil IPR</th><th>Gas IPR</th><th>Condensate Gas IPR</th><th>Disaggregation</th><th>Drilling And Completion</th><th>Detailed Data</th></tr></thead><tbody>";
					$.each(data.formations_inventory, function(index,value)
					{
						var mp_value = Math.round(((value[7]*100)/5));
						var ipr_value = Math.round(((value[8]*100)/2));
						var dis_value = Math.round(((value[9]*100)/3));
						body_formation+= "<tr><td>"+value[0]+"</td>";
						if(value[4])
						{
							body_formation+= "<td><font color='green'><b>"+mp_value+"%</b></font></td>";
						}
						else
						{
							body_formation+= "<td><font color='red'>"+mp_value+"%</font></td>";
						}
						if(value[1])
						{
							body_formation+= "<td><font color='green'><b>"+ipr_value+"%</b></font></td>";
						}
						else
						{
							body_formation+= "<td><font color='red'>"+ipr_value+"%</font></td>";
						}
						if(value[2])
						{
							body_formation+= "<td><font color='green'><b>"+ipr_value+"%</b></font></td>";
						}
						else
						{
							body_formation+= "<td><font color='red'>"+ipr_value+"%</font></td>";
						}
						if(value[3])
						{
							body_formation+= "<td><font color='green'><b>"+ipr_value+"%</b></font></td>";
						}
						else
						{
							body_formation+= "<td><font color='red'>"+ipr_value+"%</font></td>";
						}
						if(value[5])
						{
							body_formation+= "<td><font color='green'><b>"+dis_value+"%</b></font></td>";
						}
						else
						{
							body_formation+= "<td><font color='red'>"+dis_value+"%</font></td>";
						}
						body_formation+= "<td><font color='green'>NA</font></td>";


						var string_check = '<td><a href="{{route("detailed_data_formation" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[6]);
						body_formation+= string_check;
						body_formation+= "</tr>";

						//Población series
						serie_multiparametric.push(mp_value);
						serie_ipr.push(ipr_value);
						serie_disaggregation.push(dis_value);
					});	
					$("#formation_data").show();
					$("#formation_chart").show();

					//Gráfico
					Highcharts.chart('formation_chart', {
					    chart: {
					        type: 'bar',
					        zoomType: 'xy'
					    },
					    title: {
					        text: 'Percentage Of Formation Data By Analysis Type'
					    },
					    subtitle: {
					        text: 'Maximum amount of data - Multiparametric:5 - Oil IPR:2 - Gas IPR:2 - Condensate Gas IPR:2 - Disaggregation:3'
					    },
					    xAxis: {
					        categories: categories_formations,
					        title: {
					            text: null
					        }
					    },
					    yAxis: {
					        min: 0,
					        max: 100,
					        title: {
					            text: 'Percentage Of Data (%)',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: '<b>%</b>'
					    },			                
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: false,
					                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: -40,
					        y: 80,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Multiparametric',
					        data: serie_multiparametric
					    }, {
					        name: 'Oil IPR',
					        data: serie_ipr
					    }, {
					        name: 'Gas IPR',
					        data: serie_ipr
					    },{
					        name: 'Condensate Gas IPR',
					        data: serie_ipr
					    },{
					        name: 'Disaggregation',
					        data: serie_disaggregation
					    }]
					});

				}
				$("#formation_data_inventory").append(body_formation);


				//Gráficos
				//Formación

			});
		$.get("{{url('wellInventory')}}",
			{field : field},
			function(data)
			{
				if(data.wells_inventory.length==0)
				{
					body = "<h3><font color='red'>There's no Wells for this Field. Please choose another one.</font></h3>";
				}
				else
				{
					body = "<thead><tr><th>Well</th><th>Multiparametric</th><th>Oil IPR</th><th>Gas IPR</th><th>Condensate Gas IPR</th><th>Disaggregation</th><th>Drilling And Completion</th><th>Detailed Data</th></tr></thead><tbody>";
					$.each(data.wells_inventory, function(index,value)
					{
						body+= "<tr><td>"+value[0]+"</td>";
						categories_well.push(value[0]);

						var mp_value = Math.round(((value[7]*100)/14)); 
						var oil_ipr_value = Math.round(((value[8]*100)/6));
						var gas_ipr_value = Math.round(((value[9]*100)/5));
						var condensate_gas_value = Math.round(((value[10]*100)/7));
						var disaggregation_value = Math.round(((value[11]*100)/9));

						if(value[4])
						{
							body+= "<td><font color='green'><b>"+mp_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+mp_value+"%</font></td>";
						}
						if(value[1])
						{
							body+= "<td><font color='green'><b>"+oil_ipr_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+oil_ipr_value+"%</font></td>";
						}
						if(value[2])
						{
							body+= "<td><font color='green'><b>"+gas_ipr_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+gas_ipr_value+"%</font></td>";
						}
						if(value[3])
						{
							body+= "<td><font color='green'><b>"+condensate_gas_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+condensate_gas_value+"%</font></td>";
						}
						if(value[5])
						{
							body+= "<td><font color='green'><b>"+disaggregation_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+disaggregation_value+"%</font></td>";
						}
						body+= "<td><font color='green'>NA</font></td>";


						var string_check = '<td><a href="{{route("detailed_data" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[6]);
						body+= string_check;
						body+= "</tr>";

						//Población series well


						serie_multiparametric_well.push(mp_value);
						serie_oil_ipr_well.push(oil_ipr_value);
						serie_gas_ipr_well.push(gas_ipr_value);
						serie_condensate_gas_ipr_well.push(condensate_gas_value);
						serie_disaggregation_well.push(disaggregation_value);
					});	
					var serie_1 = [];
					var serie_2 = [];
					var serie_3 = [];
					var serie_4 = [];
					var serie_5 = [];
					for (var i = 0; i<categories_well.length; i++) 
					{
						serie_1.push([categories_well[i],((serie_multiparametric_well[i]*100)/14)]);
						serie_2.push([categories_well[i],((serie_oil_ipr_well[i]*100)/6)]);
						serie_3.push([categories_well[i],((serie_gas_ipr_well[i]*100)/5)]);
						serie_4.push([categories_well[i],((serie_condensate_gas_ipr_well[i]*100)/7)]);
						serie_5.push([categories_well[i],((serie_disaggregation_well[i]*100)/9)]);
					}
					console.log(serie_1);
					//Gráfico
					/*
					Highcharts.stockChart('well_chart', {

					    scrollbar: {
					        barBackgroundColor: 'gray',
					        barBorderRadius: 7,
					        barBorderWidth: 0,
					        buttonBackgroundColor: 'gray',
					        buttonBorderWidth: 0,
					        buttonArrowColor: 'yellow',
					        buttonBorderRadius: 7,
					        rifleColor: 'yellow',
					        trackBackgroundColor: 'white',
					        trackBorderWidth: 1,
					        trackBorderColor: 'silver',
					        trackBorderRadius: 7
					    },

					    rangeSelector: {
					        selected: 1
					    },

					    series: [{
					        name: 'Population',
					        data: serie_1
					    }]
					});

					Highcharts.chart('well_chart', {
					    chart: {
					        type: 'column'
					    },
					    title: {
					        text: 'World\'s largest cities per 2014'
					    },
					    subtitle: {
					        text: 'Source: <a href="https://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
					    },
					    xAxis: {
					        type: 'category',
					        labels: {
					            rotation: -45,
					            style: {
					                fontSize: '13px',
					                fontFamily: 'Verdana, sans-serif'
					            }
					        }
					    },
					    yAxis: {
					        min: 0,
					        title: {
					            text: 'Population (millions)'
					        }
					    },
					    legend: {
					        enabled: false
					    },
					    tooltip: {
					        pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
					    },
					    series: [{
					        name: 'Population',
					        data: serie_1
					    },{
					        name: 'Population',
					        data: serie_2
					    },{
					        name: 'Population',
					        data: serie_3
					    },{
					        name: 'Population',
					        data: serie_4
					    },{
					        name: 'Population',
					        data: serie_5
					    }]
					});
						*/
					Highcharts.chart('well_chart', 
					{
						chart: {
						    zoomType: 'xy'
						},
						title: {
						    text: 'Percentage Of Well Data By Analysis Type'
						},
						subtitle: {
						    text: 'Maximum amount of data - Multiparametric:14 - Oil IPR:6 - Gas IPR:5 - Condensate Gas IPR:7 - Disaggregation:9'
						},
    					xAxis: {
    					    categories: categories_well
    					},
    					yAxis: {
    					    min: 0,
    					    max: 100,

    					    title: {
    					        text: 'Percentage Of Data (%)',
    					        align: 'high'
    					    },
    					    labels: {
    					        overflow: 'justify'
    					    }
    					},
    					tooltip: {
    					    valueSuffix: '<b>%</b>'
    					},
    					plotOptions: {
    					    bar: {
    					        dataLabels: {
    					            enabled: true
    					        }
    					    }
    					},
    					legend: {
    					    layout: 'horizontal',
    					    align: 'right',
    					    verticalAlign: 'top',
    					    x: -40,
    					    y: 80,
    					    floating: true,
    					    borderWidth: 1,
    					    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
    					    shadow: true
    					},
    					credits: {
    					    enabled: false
    					},
    					series: [{
    						showInNavigator: true,
    					    name: 'Multiparametric',
    					    data: serie_multiparametric_well
    					}, {
    					    name: 'Oil IPR',
    					    showInNavigator: true,
    					    data: serie_oil_ipr_well
    					}, {
    					    name: 'Gas IPR',
    					    showInNavigator: true,
    					    data: serie_gas_ipr_well
    					},{
    					    name: 'Condensate Gas IPR',
    					    showInNavigator: true,
    					    data: serie_condensate_gas_ipr_well
    					},{
    					    name: 'Disaggregation',
    					    showInNavigator: true,
    					    data: serie_disaggregation_well
    					}]
					});
					/*
					Highcharts.chart('well_chart', {
					    chart: {
					        type: 'bar'
					    },
					    title: {
					        text: 'Amount of data by Well'
					    },
					    subtitle: {
					        text: 'Maximum amount of data - Multiparametric:14 - Oil IPR:6 - Gas IPR:5 - Condensate Gas IPR:7 - Disaggregation:9'
					    },
					    xAxis: {
					        categories: categories_well,
					        labels: 
					        {
					        	step: 1
					        },
					        title: {
					            text: null
					        }
					    },
					    yAxis: {
					        min: 0,
					        categories: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15],
					        title: {
					            text: 'Percentage Of Data (%)',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: ''
					    },
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: true
					            }
					        }
					    },
					    legend: {
					        layout: 'horizontal',
					        align: 'right',
					        verticalAlign: 'top',
					        x: -40,
					        y: 80,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					    	showInNavigator: true,
					        name: 'Multiparametric',
					        data: serie_multiparametric_well
					    }, {
					        name: 'Oil IPR',
					        showInNavigator: true,
					        data: serie_oil_ipr_well
					    }, {
					        name: 'Gas IPR',
					        showInNavigator: true,
					        data: serie_gas_ipr_well
					    },{
					        name: 'Condensate Gas IPR',
					        showInNavigator: true,
					        data: serie_condensate_gas_ipr_well
					    },{
					        name: 'Disaggregation',
					        showInNavigator: true,
					        data: serie_disaggregation_well
					    }]
					}); */

				}
				$("#well_data_inventory").append(body);
			});
	});	
	$("#formation").change(function(e)
	{
		$("#producing_interval_data_inventory tr").remove();
		$("#producing_interval_data_inventory").empty();
		$(".formation_data").show();
		$("#producing_interval_chart").hide();
		var formation = $("#formation").val(); 
		var body = "";

		var categories_producing_interval = [];
		var serie_multiparametric_producing_interval = [];
		var serie_oil_ipr_producing_interval = [];
		var serie_gas_ipr_producing_interval = [];
		var serie_condensate_gas_producing_interval = [];
		var serie_disaggregation_producing_interval = [];
		var serie_drilling_producing_interval = [];

		$.get("{{url('producing_interval_inventory')}}",
			{formation : formation},
			function(data)
			{
				if(data.length==0)
				{
					body = "<h3><font color='red'>There's no Producing Intervals for this Formation. Please choose another one.</font></h3>";
				}
				else
				{
					body = "<thead><tr><th>Producing Interval</th><th>Multiparametric</th><th>Oil IPR</th><th>Gas IPR</th><th>Condensate Gas IPR</th><th>Disaggregation</th><th>Drilling And Completion</th><th>Related Well</th><th>Detailed Data</th></tr></thead><tbody>";
					$.each(data, function(index,value)
					{

						var mp_value = Math.round(((value[9]*100)/2));
						var condensate_gas_value = Math.round(((value[11]*100)/1));
						var drilling_value = Math.round(((value[13]*100)/4));

						body+= "<tr><td>"+value[0]+"</td>";
						categories_producing_interval.push(value[0]);
						//Multiparamétrico
						body+= "<td><font color='green'>NA</font></td>";

						if(value[2])
						{
							body+= "<td><font color='green'><b>"+mp_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+mp_value+"%</font></td>";
						}

						//IPR Gas
						body+= "<td><font color='green'>NA</font></td>";

						if(value[4])
						{
							body+= "<td><font color='green'><b>"+condensate_gas_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+condensate_gas_value+"%</font></td>";
						}

						//Desagregación
						body+= "<td><font color='green'>NA</font></td>";

						if(value[6])
						{
							body+= "<td><font color='green'><b>"+drilling_value+"%</b></font></td>";
						}
						else
						{
							body+= "<td><font color='red'>"+drilling_value+"%</font></td>";
						}
						body+= "<td>"+value[7]+"</td>";
						var string_check = '<td><a href="{{route("detailed_data_interval" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[14]);
						body+= string_check;
						body+= "</tr>";

						serie_multiparametric_producing_interval.push(value[8]);
						serie_oil_ipr_producing_interval.push(mp_value);
						serie_gas_ipr_producing_interval.push(value[10]);
						serie_condensate_gas_producing_interval.push(condensate_gas_value);
						serie_disaggregation_producing_interval.push(value[12]);
						serie_drilling_producing_interval.push(drilling_value);
						$("#producing_interval_chart").show();
					});	

					//Gráfico
					Highcharts.chart('producing_interval_chart', {
					    chart: {
					        type: 'bar',
					        zoomType: 'xy'
					    },
					    title: {
					        text: 'Percentage Of Producing Interval Data By Analysis Type'
					    },
					    subtitle: {
					        text: 'Maximum amount of data - Oil IPR:2 - Condensate Gas IPR:1 - Drilling And Completion:4'
					    },
					    xAxis: {
					        categories: categories_producing_interval,
					        title: {
					            text: null
					        }
					    },
					    yAxis: {
					        min: 0,
					        max: 100,
					        title: {
					            text: 'Percentage Of Data (%)',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: '<b>%</b>'
					    },
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: false
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: -40,
					        y: 80,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Oil IPR',
					        data: serie_oil_ipr_producing_interval
					    },{
					        name: 'Condensate Gas IPR',
					        data: serie_condensate_gas_producing_interval
					    },{
					        name: 'Drilling And Completion',
					        data: serie_drilling_producing_interval
					    }]
					});
				}

				$("#producing_interval_data_inventory").append(body);
				
			});
	});	
	$("#basin_general").change(function(e)
	{	
		$("#field_data_general_inventory tr").remove();
		$("#field_data_general_inventory").empty();
		$(".basin_general_data").show();
		$(".field_general_data").hide();
		$(".formation_general_data").hide();
		$("#formation_general_data").hide();

		var basin = $("#basin_general").val(); 
		var body = '';
		$.get("{{url('fieldInventory_general')}}",
			{basin : basin},
			function(data)
			{
				$("#field_general").empty();
				$.each(data.fields, function(index,value)
				{
					$("#field_general").append('<option value="'+value.id+'">'+value.nombre+'</option>');
				});
				$("#field_general").selectpicker('refresh');
				$('#field_general').selectpicker('val', '');
				if(data.fields_info.length==0)
				{
					body = "<h3><font color='red'>There's no Fields for this Basin. Please choose another one.</font></h3>";
					$("#field_data").hide();
				}
				else
				{
					body = "<thead><tr><th>Field</th><th>PVT Data</th><th>Coordinates</th></tr></thead><tbody>";
					$.each(data.fields_info, function(index,value)
					{
						var pvt_bool = value[2];
						var coordinates_bool = value[3];
						var coordinates_value = "";
						var coordinates_value = "";
						if(pvt_bool)
						{
							pvt_value = "<font color='green'>Ok</font>";
						}
						else
						{
							pvt_value = "<font color='red'>Missing Information</font>";
						}
						if(coordinates_bool)
						{
							coordinates_value = "<font color='green'>Ok</font>";
						}
						else
						{
							coordinates_value = "<font color='red'>Missing Information</font>";
						}
						body+= "<tr>";
						body+= "<td>"+value[1]+"</td>"+"<td>"+pvt_value+"</td>"+"<td>"+coordinates_value+"</td>";
						var string_check = '<td><a href="{{route("detailed_data_field_general" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[0]);
						body+= string_check;
						body+= "</tr>";
					});
					$("#field_general_data").show();	
				}
				$("#field_data_general_inventory").append(body);

			});
	});	
	$("#field_general").change(function(e)
	{
		$("#well_data_general_inventory tr").remove();
		$("#formation_data_general_inventory tr").remove();

		$("#well_data_general_inventory").empty();
		$("#formation_data_general_inventory").empty();

		$(".field_general_data").show();
		$(".formation_general_data").hide();



		var field = $("#field_general").val(); 
		var body_formation = ""; //Formation
		var body = ""; //Well 
		var body_fluid = ""; //Fluid

		//Gráficos
		//Categorías - eje x
		var categories_formations = [];
		var categories_well = [];

		//Series - eje y formación
		var serie_count_formation = [];


		//Series - eje y well
		var serie_count_well = [];

		//$("#wellInfoPanels").hide();
		$.get("{{url('formationInventory_general')}}",
			{field : field},
			function(data)
			{
				if(data.formations.length==0)
				{
					body_formation = "<h3><font color='red'>There's no Formations for this Field. Please choose another one.</font></h3>";
					$("#formation_general_data").hide();
					$("#formation_general_chart").hide();
				}
				else
				{

					$("#formation_general").empty();
					$.each(data.formations, function(index,value)
					{
						$("#formation_general").append('<option value="'+value.id+'">'+value.nombre+'</option>');
						categories_formations.push(value.nombre);
					});
					$("#formation_general").selectpicker('refresh');
					$('#formation_general').selectpicker('val', '');
					body_formation = "<thead><tr><th>Formation</th><th>Top</th><th>Avg. Netpay</th><th>Avg. Porosity</th><th>Avg. Permeability</th><th>Reservoir Pressure</th><th>Water Oil Kr</th><th>Gas Liquid Kr</th><th>Detailed Data</th></tr></thead><tbody>";
					$.each(data.formations_inventory, function(index,value)
					{
						var top_bool = value[2];
						var netpay_bool = value[3];
						var porosity_bool = value[4];
						var permeability_bool = value[5];
						var reservoir_pressure_bool = value[6];
						var water_oil_bool = value[7];
						var gas_liquid_bool = value[8];

						var top_value = "";
						var netpay_value = "";
						var porosity_value = "";
						var permeability_value = "";
						var reservoir_pressure_value = "";

						if(top_bool){top_value = "<td><font color='green'>Ok</font></td>";}else{top_value="<td><font color='red'>Missing Information</font></td>";}
						if(netpay_bool){netpay_value = "<td><font color='green'>Ok</font></td>";}else{netpay_value="<td><font color='red'>Missing Information</font></td>";}
						if(porosity_bool){porosity_value = "<td><font color='green'>Ok</font></td>";}else{porosity_value="<td><font color='red'>Missing Information</font></td>";}
						if(permeability_bool){permeability_value = "<td><font color='green'>Ok</font></td>";}else{permeability_value="<td><font color='red'>Missing Information</font></td>";}
						if(reservoir_pressure_bool){reservoir_pressure_value = "<td><font color='green'>Ok</font></td>";}else{reservoir_pressure_value="<td><font color='red'>Missing Information</font></td>";}
						if(water_oil_bool){water_oil_value = "<td><font color='green'>Ok</font></td>";}else{water_oil_value="<td><font color='red'>Missing Information</font></td>";}
						if(gas_liquid_bool){gas_liquid_value = "<td><font color='green'>Ok</font></td>";}else{gas_liquid_value="<td><font color='red'>Missing Information</font></td>";}

						body_formation+= "<tr><td>"+value[1]+"</td>"+top_value+netpay_value+porosity_value+permeability_value+reservoir_pressure_value+water_oil_value+gas_liquid_value;
						var string_check = '<td><a href="{{route("detailed_data_formation_general" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[0]);
						body_formation+= string_check;
						body_formation+= "</tr>";

						//Población series
						serie_count_formation.push(Math.round(((value[9]*100)/7)));
					});	

					$("#formation_general_chart").show();
					$("#formation_general_data").show();
					//Gráfico
					Highcharts.chart('formation_general_chart', {
					    chart: {
					        type: 'bar',
					        zoomType: 'xy'
					    },
					    title: {
					        text: 'Percentage Of Formation Data'
					    },
					    subtitle: {
					        text: 'Maximum amount of data: 7'
					    },
					    xAxis: {
					        categories: categories_formations,
					        title: {
					            text: null
					        }
					    },
					    yAxis: {
					        min: 0,
					        max: 100,
					        title: {
					            text: 'Percentage Of Data (%)',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: '<b>%</b>'
					    },
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: false
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: -40,
					        y: 80,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'General Data',
					        data: serie_count_formation
					    }]
					});

				}
				$("#formation_data_general_inventory").append(body_formation);
			});

		$.get("{{url('wellInventory_general')}}",
			{field : field},
			function(data)
			{
				if(data.wells_inventory.length==0)
				{
					body = "<h3><font color='red'>There's no Wells for this Field. Please choose another one.</font></h3>";
					body_fluid = "<h3><font color='red'>There's no Wells for this Field. Please choose another one.</font></h3>";
				}
				else
				{
					body = "<thead><tr><th>Well</th><th>Radius</th><th>Drainage Radius</th><th>Oil Rate</th><th>Gas Rate</th><th>BHP</th><th>TVD</th><th>Location</th><th>Production Data</th><th>Detailed Data<td></tr></thead><tbody>";
					body_fluid = "<thead><tr><th>Well</th><th>Fluid Type</th><th>Saturation Pressure</th><th>Oil Viscosity</th><th>FVFO</th><th>Gas Viscosity</th><th>FVFG</th><th>Water Viscosity</th><th>FVFW</th><th>Gor</th><th>Specific Gas Gravity</th><th>API</th><th>WOR</th><th>CGR</th><th>LGR</th><th>GWR</th><th>Detailed Data<td></tr></thead><tbody>";
					$.each(data.wells_inventory, function(index,value)
					{

						categories_well.push(value[1]);

						var radius_bool = value[2];
						var drainage_radius_bool = value[3];
						var oil_rate_bool = value[4];
						var gas_rate_bool = value[5];
						var bhp_bool = value[6];
						var tvd_bool = value[7];
						var location_bool = value[8];
						
						var proudction_data_bool = value[9];

						var fluid_bool = value[10];
						var saturation_pressure_bool = value[11];
						var oil_viscosity_bool = value[12];
						var fvfo_bool = value[13];
						var gas_viscosity_bool = value[14];
						var fvfg_bool = value[15];
						var water_viscosity_bool = value[16];
						var fvfw_bool = value[17];
						var gor_bool = value[18];
						var specific_gas_gravity_bool = value[19];
						var api_gravity_bool = value[20];
						var wor_bool = value[21];
						var cgr_bool = value[22];
						var lgr_bool = value[23];
						var gwr_bool = value[24];


						var radius_value =  "";
						var drainage_radius_value =  "";
						var oil_rate_value =  "";
						var gas_rate_value =  "";
						var bhp_value =  "";
						var tvd_value =  "";
						var location_value =  "";
						var proudction_data_value =  "";
						var fluid_value =  "";
						var saturation_pressure_value =  "";
						var oil_viscosity_value =  "";
						var fvfo_value =  "";
						var gas_viscosity_value =  "";
						var fvfg_value =  "";
						var water_viscosity_value =  "";
						var fvfw_value =  "";
						var gor_value =  "";
						var specific_gas_gravity_value =  "";
						var api_gravity_value =  "";
						var wor_value =  "";
						var cgr_value =  "";
						var lgr_value =  "";
						var gwr_value =  "";


						if(radius_bool){radius_value="<td><font color='green'>Ok</font></td>";}else{radius_value="<td><font color='red'>Missing Information</font></td>";}
						if(drainage_radius_bool){drainage_radius_value="<td><font color='green'>Ok</font></td>";}else{drainage_radius_value="<td><font color='red'>Missing Information</font></td>";}
						if(oil_rate_bool){oil_rate_value="<td><font color='green'>Ok</font></td>";}else{oil_rate_value="<td><font color='red'>Missing Information</font></td>";}
						if(gas_rate_bool){gas_rate_value="<td><font color='green'>Ok</font></td>";}else{gas_rate_value="<td><font color='red'>Missing Information</font></td>";}
						if(bhp_bool){bhp_value="<td><font color='green'>Ok</font></td>";}else{bhp_value="<td><font color='red'>Missing Information</font></td>";}
						if(tvd_bool){tvd_value="<td><font color='green'>Ok</font></td>";}else{tvd_value="<td><font color='red'>Missing Information</font></td>";}
						if(location_bool){location_value="<td><font color='green'>Ok</font></td>";}else{location_value="<td><font color='red'>Missing Information</font></td>";}
						if(proudction_data_bool){proudction_data_value="<td><font color='green'>Ok</font></td>";}else{proudction_data_value="<td><font color='red'>Missing Information</font></td>";}
						if(fluid_bool){fluid_value="<td><font color='green'>Ok</font></td>";}else{fluid_value="<td><font color='red'>Miss.I.</font></td>";}
						if(saturation_pressure_bool){saturation_pressure_value="<td><font color='green'>Ok</font></td>";}else{saturation_pressure_value="<td><font color='red'>Miss.I.</font></td>";}
						if(oil_viscosity_bool){oil_viscosity_value="<td><font color='green'>Ok</font></td>";}else{oil_viscosity_value="<td><font color='red'>Miss.I.</font></td>";}
						if(fvfo_bool){fvfo_value="<td><font color='green'>Ok</font></td>";}else{fvfo_value="<td><font color='red'>Miss.I.</font></td>";}
						if(gas_viscosity_bool){gas_viscosity_value="<td><font color='green'>Ok</font></td>";}else{gas_viscosity_value="<td><font color='red'>Miss.I.</font></td>";}
						if(fvfg_bool){fvfg_value="<td><font color='green'>Ok</font></td>";}else{fvfg_value="<td><font color='red'>Miss.I.</font></td>";}
						if(water_viscosity_bool){water_viscosity_value="<td><font color='green'>Ok</font></td>";}else{water_viscosity_value="<td><font color='red'>Miss.I.</font></td>";}
						if(fvfw_bool){fvfw_value="<td><font color='green'>Ok</font></td>";}else{fvfw_value="<td><font color='red'>Miss.I.</font></td>";}
						if(gor_bool){gor_value="<td><font color='green'>Ok</font></td>";}else{gor_value="<td><font color='red'>Miss.I.</font></td>";}
						if(specific_gas_gravity_bool){specific_gas_gravity_value="<td><font color='green'>Ok</font></td>";}else{specific_gas_gravity_value="<td><font color='red'>Miss.I.</font></td>";}
						if(api_gravity_bool){api_gravity_value="<td><font color='green'>Ok</font></td>";}else{api_gravity_value="<td><font color='red'>Miss.I.</font></td>";}
						if(wor_bool){wor_value="<td><font color='green'>Ok</font></td>";}else{wor_value="<td><font color='red'>Miss.I.</font></td>";}
						if(cgr_bool){cgr_value="<td><font color='green'>Ok</font></td>";}else{cgr_value="<td><font color='red'>Miss.I.</font></td>";}
						if(lgr_bool){lgr_value="<td><font color='green'>Ok</font></td>";}else{lgr_value="<td><font color='red'>Miss.I.</font></td>";}
						if(gwr_bool){gwr_value="<td><font color='green'>Ok</font></td>";}else{gwr_value="<td><font color='red'>Miss.I.</font></td>";}

						body += "<tr><td>"+value[1]+"</td>"+radius_value+drainage_radius_value+oil_rate_value+gas_rate_value+bhp_value+tvd_value+location_value+proudction_data_value;
						body_fluid += "<tr><td>"+value[1]+"</td>"+fluid_value+saturation_pressure_value+oil_viscosity_value+fvfo_value+gas_viscosity_value+fvfg_value+water_viscosity_value+fvfw_value+gor_value+specific_gas_gravity_value+api_gravity_value+wor_value+cgr_value+lgr_value+gwr_value;
						var string_check = '<td><a href="{{route("detailed_data_general" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[0]);
						body+= string_check;
						body_fluid+= string_check;
						body+= "</tr>";
						body_fluid+= "</tr>";

						//Población series well
						serie_count_well.push(Math.round(((value[25]*100)/23)));
					});	
					var serie_1 = [];
					for (var i = 0; i<categories_well.length; i++) 
					{
						serie_1.push([categories_well[i],serie_count_well[i]]);
					}
					Highcharts.chart('well_general_chart', 
					{
						chart: {
						    zoomType: 'xy'
						},
						title: {
						    text: 'Percentage Of Well Data'
						},
						subtitle: {
						    text: 'Maximum amount of data: 23'
						},
	    				xAxis: {
	    				    categories: categories_well
	    				},
	    				yAxis: {
	    				    min: 0,
	    				    
	    				    title: {
	    				        text: 'Percentage Of Data (%)',
	    				        align: 'high'
	    				    },
	    				    labels: {
	    				        overflow: 'justify'
	    				    }
	    				},
	    				tooltip: {
	    				    valueSuffix: '<b>%</b>'
	    				},
	    				plotOptions: {
	    				    bar: {
	    				        dataLabels: {
	    				            enabled: true
	    				        }
	    				    }
	    				},
	    				legend: {
	    				    layout: 'horizontal',
	    				    align: 'right',
	    				    verticalAlign: 'top',
	    				    x: -40,
	    				    y: 80,
	    				    floating: true,
	    				    borderWidth: 1,
	    				    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
	    				    shadow: true
	    				},
	    				credits: {
	    				    enabled: false
	    				},
	    				series: [{
	    					showInNavigator: true,
	    				    name: 'General Data',
	    				    data: serie_count_well
	    				}]
					});
				}
				$("#well_data_general_inventory").append(body);
				$("#well_data_general_inventory2").append(body_fluid);
			});
	});	
	$("#formation_general").change(function(e)
	{
		$("#producing_interval_data_general_inventory tr").remove();
		$("#producing_interval_data_general_inventory").empty();
		$(".formation_general_data").show();
		$("#producing_interval_chart").hide();
		var formation = $("#formation_general").val(); 
		var body = "";

		var categories_producing_interval = [];
		var serie_count_interval = [];

		$.get("{{url('producing_interval_inventory_general')}}",
			{formation : formation},
			function(data)
			{
				if(data.length==0)
				{
					body = "<h3><font color='red'>There's no Producing Intervals for this Formation. Please choose another one.</font></h3>";
				}
				else
				{
					body = "<thead><tr><th>Producing Interval</th><th>Top</th><th>Netpay</th><th>Porosity</th><th>Permeability</th><th>Reservoir Pressure</th><th>Water Oil Kr</th><th>Gas Liquid Kr</th><th>HIstoric Reservoir Pressure</th><th>Related Well</th><th>Detailed Data</th></tr></thead><tbody>";
					$.each(data, function(index,value)
					{
						body+= "<tr><td>"+value[1]+"</td>";
						categories_producing_interval.push(value[1]);

						var top_bool = value[2];
						var netpay_bool = value[3];
						var porosity_bool = value[4];
						var permeability_bool = value[5];
						var reservoir_pressure_bool = value[6];
						var kr_water_bool = value[7];
						var kr_gas_bool = value[8];
						var historic_reservoir_bool = value[9];
						
						var top_val = "";
						var netpay_val = "";
						var porosity_val = "";
						var permeability_val = "";
						var reservoir_pressure_val = "";
						var kr_water_val = "";
						var kr_gas_val = "";
						var historic_reservoir_val = "";	


						if(top_bool){top_val = "<td><font color='green'>Ok</font></td>";}else{top_val = "<td><font color='red'>Missing Information</font></td>";}
						if(netpay_bool){netpay_val = "<td><font color='green'>Ok</font></td>";}else{netpay_val = "<td><font color='red'>Missing Information</font></td>";}
						if(porosity_bool){porosity_val = "<td><font color='green'>Ok</font></td>";}else{porosity_val = "<td><font color='red'>Missing Information</font></td>";}
						if(permeability_bool){permeability_val = "<td><font color='green'>Ok</font></td>";}else{permeability_val = "<td><font color='red'>Missing Information</font></td>";}
						if(reservoir_pressure_bool){reservoir_pressure_val = "<td><font color='green'>Ok</font></td>";}else{reservoir_pressure_val = "<td><font color='red'>Missing Information</font></td>";}
						if(kr_water_bool){kr_water_val = "<td><font color='green'>Ok</font></td>";}else{kr_water_val = "<td><font color='red'>Missing Information</font></td>";}
						if(kr_gas_bool){kr_gas_val = "<td><font color='green'>Ok</font></td>";}else{kr_gas_val = "<td><font color='red'>Missing Information</font></td>";}
						if(historic_reservoir_bool){historic_reservoir_val = "<td><font color='green'>Ok</font></td>";}else{historic_reservoir_val = "<td><font color='red'>Missing Information</font></td>";}

						body += top_val+netpay_val+porosity_val+permeability_val+reservoir_pressure_val+kr_water_val+kr_gas_val+historic_reservoir_val;
						body+= "<td>"+value[10]+"</td>";
						var string_check = '<td><a href="{{route("detailed_data_interval_general" ,["id" =>'xxx'])}}" target="_blank">Check Detailed Data</a></td>';
						string_check = string_check.replace('xxx',value[0]);
						body+= string_check;
						body+= "</tr>";

						serie_count_interval.push(Math.round(((value[11]*100)/8)));
						$("#producing_interval_general_chart").show();
					});	

					//Gráfico
					Highcharts.chart('producing_interval_general_chart', {
					    chart: {
					        type: 'bar',
					        zoomType: 'xy'
					    },
					    title: {
					        text: 'Percentage Of Producing Interval Data'
					    },
					    subtitle: {
					        text: 'Maximum amount of data: 8'
					    },
					    xAxis: {
					        categories: categories_producing_interval,
					        title: {
					            text: null
					        }
					    },
					    yAxis: {
					        min: 0,
					        max: 100,
					        title: {
					            text: 'Percentage Of Data (%)',
					            align: 'high'
					        },
					        labels: {
					            overflow: 'justify'
					        }
					    },
					    tooltip: {
					        valueSuffix: '<b>%</b>'
					    },			                
					    plotOptions: {
					        bar: {
					            dataLabels: {
					                enabled: false
					            }
					        }
					    },
					    legend: {
					        layout: 'vertical',
					        align: 'right',
					        verticalAlign: 'top',
					        x: -40,
					        y: 80,
					        floating: true,
					        borderWidth: 1,
					        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					        shadow: true
					    },
					    credits: {
					        enabled: false
					    },
					    series: [{
					        name: 'Amount of data by Interval',
					        data: serie_count_interval
					    }]
					});
				}

				$("#producing_interval_data_general_inventory").append(body);
				
			});
	});	
	$("#basin_scenarios").change(function(e)
	{
		var basin = $("#basin_scenarios").val(); 
		$("#field_data_analysis").hide();
		$.get("{{url('fieldInventory')}}",
			{basin : basin},
			function(data)
			{
				$("#field_scenarios").empty();
				$.each(data.fields, function(index,value)
				{
					$("#field_scenarios").append('<option value="'+value.id+'">'+value.nombre+'</option>');
				});
				$("#field_scenarios").selectpicker('refresh');
				$('#field_scenarios').selectpicker('val', '');
			});
	});
	$("#field_scenarios").change(function(e)
	{
		var field = $("#field_scenarios").val();
		var field_name = $("#field_scenarios option:selected").text();
		$("#field_data_analysis").show();
		$("#scenarios_by_field_table tr").remove();
		var table_body = '';
		$.get("{!! url('scenarios_by_field') !!}",
			{field:field},
			function(data)
			{
				console.log(data);
				var field_data = data.field_data; 
				var wells_in_field = field_data[0];

				var ipr_complete = field_data[1];
				var ipr_incomplete = field_data[5];
				var ipr_total = ipr_complete+ipr_incomplete;

				var mp_complete = field_data[2];
				var mp_incomplete = field_data[6];
				var mp_total = mp_complete+mp_incomplete;

				var drilling_complete = field_data[3];
				var drilling_incomplete = field_data[7];
				var drilling_total = drilling_complete+drilling_incomplete;

				var dissagrigation_complete = field_data[4];
				var dissagrigation_incomplete = field_data[8];
				var dissagrigation_total = dissagrigation_complete+dissagrigation_incomplete;

				pie_chart("chart_ipr", "IPR analysis by well in "+field_name, "Wells with IPR analysis", "Wells without IPR analysis",ipr_total, (wells_in_field-ipr_total));
				pie_chart("chart_mp", "Multiparametric analysis by well in "+field_name, "Wells with Multiparametric analysis", "Wells without Multiparametric analysis",mp_total, (wells_in_field-mp_total));
				pie_chart("chart_dis", "Dissagrigation analysis by well in "+field_name, "Wells with Dissagrigation analysis", "Wells without Dissagrigation analysis",drilling_total, (wells_in_field-drilling_total));
				pie_chart("chart_drilling", "Drilling and completion analysis by well in "+field_name, "Wells with Drilling and completion analysis", "Wells without Drilling and completion analysis",dissagrigation_total, (wells_in_field-dissagrigation_total));

				table_body = "<thead><tr><th>Well</th><th>IPR Analysis</th><th>Multiparametric Analysis</th><th>Disaggregation Analysis</th><th>Drilling And Completion Analysis</th></tr></thead><tbody>";
				$.each(data.well_data, function(index, value)
				{

					if(value[1]==0 && value[2]==0)
					{
						//ipr_message = "<td><font color='red'>There's no analysis for this well</font></td>";
						ipr_message = "<td><font color='green'>"+value[1]+" Complete</font> / <font color='red'>"+value[2]+" Incomplete</font></td>";
					}
					else
					{
						ipr_message = "<td><font color='green'><b>"+value[1]+" Complete</b></font> / <font color='red'>"+value[2]+" Incomplete</font></td>";
					}

					if(value[3]==0 && value[4]==0)
					{
						//mp_message = "<td><font color='red'>There's no analysis for this well</font></td>";
						mp_message = "<td><font color='green'>"+value[3]+" Complete</font> / <font color='red'>"+value[4]+" Incomplete</font></td>";
					}
					else
					{
						mp_message = "<td><font color='green'><b>"+value[3]+" Complete</b></font> / <font color='red'>"+value[4]+" Incomplete</font></td>";
					}

					if(value[5]==0 && value[6]==0)
					{
						//dis_message = "<td><font color='red'>There's no analysis for this well</font></td>";
						dis_message = "<td><font color='green'>"+value[5]+" Complete</font> / <font color='red'>"+value[6]+" Incomplete</font></td>";
					}
					else
					{
						dis_message = "<td><font color='green'><b>"+value[5]+" Complete</b></font> / <font color='red'>"+value[6]+" Incomplete</font></td>";
					}

					if(value[7]==0 && value[8]==0)
					{
						//drilling_message = "<td><font color='red'>There's no analysis for this well</font></td>";
						drilling_message = "<td><font color='green'>"+value[7]+" Complete</font> / <font color='red'>"+value[8]+" Incomplete</font></td>";
					}
					else
					{
						drilling_message = "<td><font color='green'><b>"+value[7]+" Complete</b></font> / <font color='red'>"+value[8]+" Incomplete</font></td>";
					}
					table_body+= "<tr><td>"+value[0]+"</td>"+ipr_message+mp_message+dis_message+drilling_message+"<tr>";

					//table_body+= "<tr><td>"+value[0]+"</td>"+"<td><font color='green'>"+value[1]+" Complete</font> / <font color='red'>"+value[2]+" Incomplete</font></td>"+"<td><font color='green'>"+value[3]+" Complete</font> / <font color='red'>"+value[4]+" Incomplete</font></td>"+"<td><font color='green'>"+value[5]+" Complete</font> / <font color='red'>"+value[6]+" Incomplete</font></td>"+"<td><font color='green'>"+value[7]+" Complete</font> / <font color='red'>"+value[8]+" Incomplete</font></td>"+"<tr>";
				});
				$("#scenarios_by_field_table").append(table_body);
			});
	});

	function pie_chart(div, text, data_name_true, data_name_false, true_quantity, false_quantity)
	{
		Highcharts.chart(div, {
		    chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: text
		    },
		    tooltip: {
		        pointFormat: '<b>{point.percentage:.1f}%</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: false,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		                style: {
		                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                }
		            },
                	showInLegend: true
		        }
		    },
		    series: [{
		        name: '',
		        colorByPoint: true,
		        data: [{
		            name: data_name_false,
		            y: false_quantity
		        }, {
		            name: data_name_true,
		            y: true_quantity,
		            sliced: true,
		            selected: true
		        }]
		    }]
		});
	}
});



</script>