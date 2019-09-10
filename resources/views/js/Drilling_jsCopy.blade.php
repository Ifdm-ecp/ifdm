<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

//Tables
	//Porosity
	$porosityByIntervals = $("#porosity_interval_t");
	var data_aux = [[,],[,],[,],[,],[,]];
	$porosityByIntervals.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		columns:
	    [

		  {title:"Interval", data: 0},
		  {title:"Porosity Value",data: 1,type: 'numeric', format: '0[.]0000000'}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

	$porosityByTable = $("#porosity_table_t");
	var data_aux = [[,],[,],[,],[,],[,]];
	$porosityByTable.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		columns: 
		[
		  {title:"Depth", data: 0,type: 'numeric', format: '0[.]0000000'},
		  {title:"Porosity Value",data: 1,type: 'numeric', format: '0[.]0000000'}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

	//Permeability
	$permeabilityByIntervals = $("#permeability_interval_t");
	var data_aux = [[,],[,],[,],[,],[,]];
	$permeabilityByIntervals.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		columns:
	    [

		  {title:"Interval", data: 0},
		  {title:"Permeability Value",data: 1,type: 'numeric', format: '0[.]0000000'}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

	$permeabilityByTable = $("#permeability_table_t");
	var data_aux = [[,],[,],[,],[,],[,]];
	$permeabilityByTable.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		columns: 
		[
		  {title:"Depth", data: 0,type: 'numeric', format: '0[.]0000000'},
		  {title:"Permeability Value",data: 1,type: 'numeric', format: '0[.]0000000'}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

	//Fractures Density
	$fracturesDensityByIntervals = $("#fracturesDensity_interval_t");
	var data_aux = [[,],[,],[,],[,],[,]];
	$fracturesDensityByIntervals.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		columns:
	    [

		  {title:"Interval", data: 0},
		  {title:"Fractures Density Value",data: 1,type: 'numeric', format: '0[.]0000000'}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

	$fracturesDensityByTable = $("#fracturesDensity_table_t");
	var data_aux = [[,],[,],[,],[,],[,]];
	$fracturesDensityByTable.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		columns: 
		[
		  {title:"Depth", data: 0,type: 'numeric', format: '0[.]0000000'},
		  {title:"Fractures Density Value",data: 1,type: 'numeric', format: '0[.]0000000'}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

	//Filtration functions

	//Filtration tests
	$filtrationTest = $("#filtration_test_t");
	var data_aux = [[,],[,],[,],[,],[,]];
	$filtrationTest.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		colWidths: [110, 130],
		columns: 
		[
		  {title:"Time (min)", data: 0,type: 'numeric', format: '0[.]0000000'},
		  {title:"Filtered Volume [ml]",data: 1,type: 'numeric', format: '0[.]0000000'}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

	//Dynamic filtration
	$dynamicFiltration = $("#dynamic_filtration_t");
	var data_aux = [[,,,,],[,,,,],[,,,,],[,,,,],[,,,,]];
	$dynamicFiltration.handsontable(
	{
		data: data_aux, 
		rowHeaders: true, 
		colWidths: [110, 100, 100, 100, 100],
		columns: 
		[
		  {title:"Test", data: 0,type: 'numeric', format: '0[.]0000000'},
		  {title:"K [mD]",data: 1,type: 'numeric', format: '0[.]0000000'},
		  {title:"Pob [psi]",data: 2,type: 'numeric', format: '0[.]0000000'},
		  {title:"dv/dt 0.5",data: 3,type: 'numeric', format: '0[.]0000000',readOnly: true},
		  {title:"kPob [mD*psi]",data: 4,type: 'numeric', format: '0[.]0000000',readOnly: true}
		],
		minSpareRows: 1,
		contextMenu: true,
	});

//*****/////*****

//Dinamyc content

//Selects  **/**

//FormationSelect
$("#formationSelect").change(function(e)
{
	var formations = $("#formationSelect").val();
	$.get("{{url('intervalsDrilling')}}",
		{formations: formations},
		function(data)
		{
			$("#intervalSelect").empty();
			$.each(data, function(index, value)
			{

				$("#intervalSelect").append('<option value="'+value.id+'">'+value.nombre+'</option>');
				});
				$("#intervalSelect").selectpicker('refresh');
				$('#intervalSelect').selectpicker('val', '');
		})
});

//IntervalSelect
$("#intervalSelect").change(function(e)
{
	var intervals = $("#intervalSelect").val();
	var data_aux = [];
	$.get("{{url('intervalsInfoDrilling')}}",
		{intervals:intervals},
		function(data)
		{
			$.each(data, function(index,value)
			{
				var data_row = [value.nombre, value.top,,value.presion_reservorio,,];
				data_aux.push(data_row);
			});

			$intervalGeneral_t = $("#intervalsGeneral_t");
			$intervalGeneral_t.handsontable(
			{
				data: data_aux, 
				rowHeaders: true, 
				colWidths: [110, 100, 100, 165, 140, 155],
				columns: 
				[
				  {title:"Interval", data: 0},
				  {title:"Top [ft]",data: 1,type: 'numeric', format: '0[.]0000000'},
				  {title:"Bottom [ft]",data: 2,type: 'numeric', format: '0[.]0000000'},
				  {title:"Reservoir Pressure [psi]",data: 3,type: 'numeric', format: '0[.]0000000'},
				  {title:"Hole Diameter [in]",data: 4,type: 'numeric', format: '0[.]0000000'},
				  {title:"Drill Pipe Diameter [in]",data: 4,type: 'numeric', format: '0[.]0000000'}
				],
				minSpareRows: 1,
				contextMenu: true,
			});
		});
});

//InputDataMethodSelect
$("#inputDataMethodSelect").change(function(e)
{

	if($("#inputDataMethodSelect").val()==1)
	{
		$("#averageInput_t").show();
		$("#byIntervalsInput_t").hide();
		$("#profileInput_t").hide();
		var data_average = [];
		var formations = $("#formationSelect").val();
		$.get("{{url('formationsInfoDrill')}}",
			{formations:formations},
			function(data)
			{	
				$.each(data, function(index,value)
				{
					var data_row = [value.nombre,,,];
					data_average.push(data_row);
				});
			});
		
		$averageInput_t = $("#averageInput_t");
		$averageInput_t.handsontable(
		{
			data: data_average, 
			rowHeaders: true, 
			colWidths: [150, 100, 120, 150],
			columns: 
			[
			  {title:"Formation", data: 0},
			  {title:"Porosity [-]",data: 1,type: 'numeric', format: '0[.]0000000'},
			  {title:"Permeability [mD]",data: 2,type: 'numeric', format: '0[.]0000000'},
			  {title:"Fracture Intensity [#/ft]",data: 3,type: 'numeric', format: '0[.]0000000'}
			],
			minSpareRows: 1,
			contextMenu: true,
		});
	}
	else if($("#inputDataMethodSelect").val()==2)
	{
		$("#averageInput_t").hide();
		$("#byIntervalsInput_t").show();
		$("#profileInput_t").hide();
		var data_byIntervals = [];
		var intervals = $("#intervalSelect").val();
		$.get("{{url('intervalsInfoDrilling')}}",
			{intervals:intervals},
			function(data)
			{
				$.each(data, function(index,value)
				{
					var data_row = [value.nombre,,,];
					data_byIntervals.push(data_row);
				});
			})

		$byIntervalsInput_t = $("#byIntervalsInput_t");
		$byIntervalsInput_t.handsontable(
		{
			data: data_byIntervals, 
			rowHeaders: true, 
			colWidths: [150, 100, 120, 150],
			columns: 
			[
			  {title:"Intervals", data: 0},
			  {title:"Porosity [-]",data: 1,type: 'numeric', format: '0[.]0000000'},
			  {title:"Permeability [mD]",data: 2,type: 'numeric', format: '0[.]0000000'},
			  {title:"Fracture Intensity [#/ft]",data: 3,type: 'numeric', format: '0[.]0000000'}
			],
			minSpareRows: 1,
			contextMenu: true,
		});
	}
	else
	{
		$("#averageInput_t").hide();
		$("#byIntervalsInput_t").hide();
		$("#profileInput_t").show();
		var data_aux = [[,,,],[,,,],[,,,],[,,,]];
		$profileInput_t = $("#profileInput_t");
		$profileInput_t.handsontable(
		{
			data: data_aux, 
			rowHeaders: true, 
			colWidths: [120, 100, 120, 150],
			columns: 
			[
			  {title:"Depth [ft]", data: 0},
			  {title:"Porosity [-]",data: 1,type: 'numeric', format: '0[.]0000000'},
			  {title:"Permeability [mD]",data: 2,type: 'numeric', format: '0[.]0000000'},
			  {title:"Fracture Intensity [#/ft]",data: 3,type: 'numeric', format: '0[.]0000000'}
			],
			minSpareRows: 1,
			contextMenu: true,
		});
	}




});

//Porosity select
$("#input_data_porosity").change(function(e)
{
	var option = $("#input_data_porosity").val();
	if(option==1)
	{
		$("#col_average_porosity").show();
		$("#porosity_interval").hide();
		$("#porosity_profile").hide();
	}
	else if(option==2)
	{
		$("#col_average_porosity").hide();
		$("#porosity_interval").show();
		$("#porosity_profile").hide();
	}
	else if(option==3)
	{
		$("#col_average_porosity").hide();
		$("#porosity_interval").hide();
		$("#porosity_profile").show();
	}
});

//Permeability select
$("#input_data_permeability").change(function(e)
{
	var option = $("#input_data_permeability").val();
	if(option==1)
	{
		$("#col_average_permeability").show();
		$("#permeability_interval").hide();
		$("#permeability_profile").hide();
	}
	else if(option==2)
	{
		$("#col_average_permeability").hide();
		$("#permeability_interval").show();
		$("#permeability_profile").hide();
	}
	else if(option==3)
	{
		$("#col_average_permeability").hide();
		$("#permeability_interval").hide();
		$("#permeability_profile").show();
	}
});

//Fractures Density select
$("#input_data_fractures_density").change(function(e)
{
	var option = $("#input_data_fractures_density").val();
	if(option==1)
	{
		$("#col_average_fracturesDensity").show();
		$("#fracturesDensity_interval").hide();
		$("#fracturesDensity_table").hide();
	}
	else if(option==2)
	{
		$("#col_average_fracturesDensity").hide();
		$("#fracturesDensity_interval").show();
		$("#fracturesDensity_table").hide();
	}
	else if(option==3)
	{
		$("#col_average_fracturesDensity").hide();
		$("#fracturesDensity_interval").hide();
		$("#fracturesDensity_table").show();
	}
});

//Checks **/**

//Preset Functions
$(".div_laboratory_tests").hide();

$("#check_preset_functions").click(function()
{
   $("#check_laboratory_tests").attr('checked', false);
   $(".div_preset_functions").show();
   $(".div_laboratory_tests").hide();

});

//Laboratory Tests
$("#check_laboratory_tests").click(function()
{
   $("#check_preset_functions").attr('checked', false);
   $(".div_preset_functions").hide();
   $(".div_laboratory_tests").show();
});

//Functions
	function sticky_relocate() 
	{
	   var window_top = $(window).scrollTop();
	   var div_top = $('#sticky-anchor').offset().top;
	   if (window_top > div_top) {
	       $('#sticky').addClass('stick');
	   } else {
	       $('#sticky').removeClass('stick');
	   }
	}

	$(function () 
	{
		$(window).scroll(sticky_relocate);
	    sticky_relocate();
	});
</script>
<style> 
   #sticky {
   padding: 0.5ex;
   background-color: #333;
   color: #fff;
   width: 100%;
   font-size: 1.5em;
   border-radius: 0.5ex;
   }
   #sticky.stick {
   position: fixed;
   top: 0;
   width: 62.7%;
   z-index: 10000;
   border-radius: 0 0 0.5em 0.5em;
   }
   #sticky2 {
   padding: 0.5ex;
   background-color: #333;
   color: #FF0000;
   width: 100%;
   font-size: 1.5em;
   border-radius: 0.5ex;
   }

   #container.handsontable table{
   width:100%;
   }
   #loading{
   width: 50px;
   height: 50px;
   border: 5px solid #ccc;
   border-top-color: #ff6a00;
   border-radius: 100%;
   position: fixed;
   left: 0;
   right: 0;
   top: 0;
   bottom: 0;
   margin: auto;
   animation: round 2s linear infinite;
   z-index: 9999;
   }
   @keyframes round{
   from{transform: rotate(0deg)}
   to{transform: rotate(360deg)}
   }
</style>
