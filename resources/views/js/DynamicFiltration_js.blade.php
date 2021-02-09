<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

//Tables


function verifyFiltrationFunction()
{
	//Saving table
	var filtrationFunctions = $("#filtration_test_t").handsontable('getData');
	$('#filtrationfunctions_table').val(JSON.stringify(filtrationFunctions));

}

$("#formationSelect").change(function(e)
{
	var formation = $("#formationSelect").val();
	var data_aux = [];
	$.get("{!! url('dynamicFiltrationData') !!}",
	{
		scenario_id: {{ $scenario_id }},
		formation: formation

	},
	function(data)
	{
		$.each(data.d_filtration_function_data, function(index, value)
		{
			var row = [];
			row.push(value.test_name);
			row.push(value.permeability);
			row.push(value.pob);
			row.push(value.dv_dt);
			row.push(value.kpob);
			data_aux.push(row);
		});
		$.each(data.d_filtration_function, function(index, value)
		{
			$("#filtration_function_name_t").val(value);
		});


		if(data_aux.length == 0)
		{
			data_aux = [[,,,,],[,,,,],[,,,,],[,,,,],[,,,,]];
		}

		$filtrationTest = $("#filtration_test_t");
		$filtrationTest.handsontable(
		{
			data: data_aux, 
			rowHeaders: true, 
			colWidths: [150,150,150,150,150],
			columns: 
			[
			  {title:"Test", data: 0, readOnly: true},
			  {title:"K[mD]",data: 1,type: 'numeric', format: '0[.]0000000'},
			  {title:"Pob [psi]",data: 2,type: 'numeric', format: '0[.]0000000'},
			  {title:"dv/dt 0.5",data: 3,type: 'numeric', format: '0[.]0000000', readOnly: true},
			  {title:"kPob [mD*psi]",data: 4,type: 'numeric', format: '0[.]0000000', readOnly:false}
			],
			minSpareRows: 1,
			contextMenu: true,
		});
	});



	if(formation == "" || formation == null )
	{
		$("#filtration_test_t").hide();
	}
	else
	{
		$("#filtration_test_t").show();
	}
});
</script>
