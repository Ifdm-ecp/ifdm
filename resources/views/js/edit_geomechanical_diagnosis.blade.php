<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script type="text/javascript">

	$(document).ready(function(){
        import_tree("Geomechanics", "geomechanics");  
    });
    
	window.onload = function() {
		var fractures_table_data = $("#fractures_table_data").val();
		create_tables(fractures_table_data);
	}

	function verify_geomechanical_diagnosis_data(wr) {
		//Saving tables...
		var fractures_table_data = $("#fractures_table").handsontable('getData');

		//Normalizando valores tablas
		var fractures_table_data_aux = [];

		//Flags para validación
		var flag_fractures_table = true;

		//Fractures table
		for (var i = 0; i < fractures_table_data.length; i++) {
			d0 = fractures_table_data[i][0];
			d1 = fractures_table_data[i][1];
			d2 = fractures_table_data[i][2];

			if(!((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))) {
			  fractures_table_data_aux.push(fractures_table_data[i]);
			}

		}
		fractures_table_data = fractures_table_data_aux;

		if (wr) {
			validation = validate_table("Fractures table", fractures_table_data, 0);
			flag_fractures_table = validation[0];
			flag_message = validation[1];

			if(flag_fractures_table) {
				//var evt = window.event || arguments.callee.caller.arguments[0];
				//evt.preventDefault();

				//Guardando los datos de tablas validadas 
				$("#fractures_table_data").val(JSON.stringify(fractures_table_data));
			} else {
				var evt = window.event || arguments.callee.caller.arguments[0];
				evt.preventDefault();
				alert(flag_message);
			}
		} else {
			$("#fractures_table_data").val(JSON.stringify(fractures_table_data));
		}
	}

	function create_tables(fractures_table_data)
	{
		//Verificando si hay datos después de la recarga de la página para las tablas.

		//Tabla fractures
		if(fractures_table_data === "")
		{
			fractures_table_data = {!!json_encode($fractures_table_data)!!};
		}
		else
		{
			fractures_table_data = JSON.parse(fractures_table_data);
		}

		//Tablas 

		//Facture Model - fractures_table
		$fractures_table = $("#fractures_table");
		$fractures_table.handsontable(
		{
			data: fractures_table_data, 
			rowHeaders: true, 
			colWidths: [240,240,240],
			columns: 
			[
			  {title:"Depth [ft]", data: 0, type: 'numeric', format: '0[.]0000000'},
			  {title:"Dip [°]", data: 1, type: 'numeric', format: '0[.]0000000'},
			  {title:"Dip Azimuth [°]", data: 2, type: 'numeric', format: '0[.]0000000'}
			],
			minSpareRows: 1,
			contextMenu: true,
		});
	}
	function validate_table(table_name,table_data, start_column)
	{
		var message = "";
		var flag = true;
		var number_rows = table_data.length;
		if(number_rows>0)
		{
			var number_columns = table_data[0].length;	
		}
		else
		{
			flag = false;
			message = "The table "+table_name+" is empty. Please check your data";
			return [flag,message];
		}

		for (var i = 0; i < number_rows; i++) 
		{
			var flag_row = true;
			for (var j = start_column; j < number_columns; j++) 
			{
				if(!$.isNumeric(table_data[i][j]))
				{
					flag_row = flag_row && false;
					message = "The data for the table "+table_name+" must be numeric. Please check your data";
				}
				else
				{
					flag_row = flag_row && true;
				}
				if(table_data[i][j] == null || table_data[i][j] === "" )
				{
					flag_row = flag_row && false;
					message = "There's missing information for the table "+table_name+". Please check your data";
				}
				else
				{
					flag_row = flag_row && true;
				}
				if (j == 1 && table_name === "Fractures table"){
					if(parseInt(table_data[i][j]) === 90 || parseInt(table_data[i][j]) === 0 )
					{
						flag_row = flag_row && false;
						message = "There's a Dip Fracture equal to 0 or 90. Please check your data";
					}
					else
					{
						flag_row = flag_row && true;
					}
				}

			}
			flag = flag && flag_row;
		}

		return [flag, message];
	}

</script>