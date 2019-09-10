<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script type="text/javascript">
    /*Llamar siempre la funcion import_tree para advisor. Ver archivo "Ayuda advisor"*/
    $(document).ready(function () {
        import_tree("Asphaltene precipitation", "asphaltene_stability_analysis");
    });

    $components_table = $('#components_table');

    $(document).ready(function () {

        //Calcular y pintar label Total SARA
        calculate_total_sara();

        //Inicializar tabla de componentes
        $components_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 0,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            stretchH: 'all',

            colWidths: [150, 150],
            columns: [{
                title: "Component",
                data: 0,
                type: 'text',
                readOnly: true
            },
                {
                    title: "Zi [0-1]",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
            ]

        });

        $('.save_table').on('click', function () {
            //Loading
            $("#loading_icon").show();
            
            //Limpiar tabla antes de guardar y enviar a controlador
            components_data = clean_table_data("components_table");
            $("#value_components_table").val(JSON.stringify(components_data));

            //Validar rangos de tabla components
            validate_components_data(components_data);

            //Validar datos de tabla antes de dejar guardar los datos
            validate_table([components_data], ["components table"], [["text", "numeric"]]);
        });

        $('.save_table_wr').on('click', function () {
            //Loading
            $("#loading_icon").show();
            
            //Limpiar tabla antes de guardar y enviar a controlador
            components_data = clean_table_data("components_table");
            $("#value_components_table").val(JSON.stringify(components_data));

            //Validar rangos de tabla components
            validate_components_data(components_data);
        });

        //Ayuda visual para la sumatoria de los elementos del análisis SARA
        $(".sara_data").change(function()
        {
            calculate_total_sara();
        });

        
        //Cuando el selector de componentes cambia, se debe actualizar la tabla compoentnes en la primera columna.

        $("#components").change(function (e) {
            var hot = $('#components_table').handsontable('getInstance');
            dataComponents = [];
            items = {};
            components = $("#components").val();

            zi = hot.getSourceDataAtCol(1);
            component = hot.getSourceDataAtCol(0);

            //Ligar el valor numerico de zi al componente
            for (i = 0; i < component.length; i++) {
                items [component[i]] = zi[i];
            }

            //Como se deben mantener los datos de las filas en la tabla, asi se cambien los componentes, se recorre
            // la tabla y se guarda para volver a cargar.
            if (components) {
                for (i = 0; i < components.length; i++) {
                    item = {};
                    item [0] = components[i];
                    item [1] = items[components[i]];

                    dataComponents.push(item);
                }
            }

            hot.updateSettings({
                data: dataComponents
            });
        });

        //Saber si es la primera vez que se entra a la interfaz para cargar valores en las tablas
        var hot_components_table = $('#components_table').handsontable('getInstance');
        aux_components_table = $("#value_components_table").val();
        if (aux_components_table === '') {
            //Variable para consultar datos del modulo de precipitados
            var asphaltenes_d_precipitated_analysis_id = <?php
                if ($asphaltenes_d_precipitated_analysis) {
                    echo json_encode($asphaltenes_d_precipitated_analysis->id);
                } else {
                    echo json_encode(null);
                }
                ?>;

            if (asphaltenes_d_precipitated_analysis_id) {
                var components_value = [];
                var zi_value = [];

                //En caso de que el modulo de precipitados este completo, se debe cargar la tabla de compoentes
                $.get("{!! url('get_components_table_from_precipitated') !!}", {
                    asphaltenes_d_precipitated_analysis_id: asphaltenes_d_precipitated_analysis_id
                }, function (data) {
                    $.each(data.components_stability_analysis, function (index, value) {
                        components_value.push(value.component);
                    });

                    //Saber si es la primera vez que se entra a la interfaz para cargar valores en las tablas
                    aux_components_table = $("#value_components_table").val();
                    if (aux_components_table === '') {
                        $('#components').selectpicker('val', components_value);

                        //Arreglar valores para poner en la tabla
                        for (var i = components_value.length - 1; i >= 0; i--) {
                            hot_components_table.setDataAtRowProp(i, 0, components_value[i]);
                        }

                        $.each(data.mole_fraction_stability_analysis, function (index, value) {
                            zi_value.push(value.zi);
                        });

                        for (var i = zi_value.length - 1; i >= 0; i--) {
                            hot_components_table.setDataAtRowProp(i, 1, zi_value[i]);
                        }
                    } else {
                        hot_components_table.updateSettings({
                            data: JSON.parse(aux_components_table),
                            stretchH: 'all'
                        });
                    }

                    hot_components_table.render();
                });

            }
        } else {
            hot_components_table.updateSettings({
                data: JSON.parse(aux_components_table),
                stretchH: 'all'
            });
            hot_components_table.render();
        }


    });

    //Llamarla antes de guardar todos los datos de tablas - elmina nulos
    function clean_table_data(table_div_id) {
        container = $("#" + table_div_id); //Div de la tabla
        var table_data = container.handsontable('getData');
        var cleaned_data = [];

        $.each(table_data, function (rowKey, object) {
            if (!container.handsontable('isEmptyRow', rowKey)) {
                cleaned_data[rowKey] = object;
            }
        });

        return cleaned_data;
    }

    //Llamar cada vez que se necesiten validar los datos de la tabla.
    //Se necesita indicar: id del div de la tabla, el nombre de la tabla y un array con el tipo de dato de cada columna(text y numeric)
    function validate_table(table_div_id, table_name, column_types) {
        container = $("#" + table_div_id); //Div de la tabla
        var table_data = container.handsontable('getData');

        var number_rows = table_data.length;

        var flag_numeric = false;
        var flag_value_empty = false;

        var message_empty = null;
        var message_numeric = null;
        var message_value_empty = null;

        var final_message = null;
        if (number_rows > 0) {
            var number_columns = table_data[0].length;
        } else {
            message_empty = "The table " + table_name + " is empty. Please check your data";
            return [message_empty];
        }

        for (var i = 0; i < number_columns; i++) {
            for (var j = 0; j < number_rows; j++) {
                if (column_types[i] == "numeric") {
                    if (!$.isNumeric(table_data[j][i])) {
                        message_numeric = "Some data for the table " + table_name + " must be numeric. Please check your data" + "<br>";
                        flag_numeric = true;
                    }
                    if (table_data[j][i] == null || table_data[j][i] === "") {
                        message_value_empty = "There's missing information for the table " + table_name + ". Please check your data";
                        flag_value_empty = true;
                    }
                }
            }
        }

        final_message = message_numeric + message_value_empty;
        return [final_message];
    }

    function calculate_total_sara()
    {
        var saturate_value = isNaN(parseFloat($("#saturated").val())) ? 0 : parseFloat($("#saturated").val());
        var aromatic_value = isNaN(parseFloat($("#aromatics").val())) ? 0 : parseFloat($("#aromatics").val());
        var resine_value = isNaN(parseFloat($("#resines").val())) ? 0 : parseFloat($("#resines").val());
        var asphaltene_value = isNaN(parseFloat($("#asphaltenes").val())) ? 0 : parseFloat($("#asphaltenes").val());

        var total_sara = saturate_value + aromatic_value + resine_value + asphaltene_value;
        if(total_sara >= 99.9 && total_sara <= 100.1)
        {
            $("#total_sara").attr('class', 'label label-success');
        }
        else
        {
            $("#total_sara").attr('class', 'label label-danger');
        }
        $("#total_sara").html(total_sara);
    }

    function validate_components_data(components_data)
    {
        var zi_range_flag = 1; //Determina si todos los valores de zi se encuentran entre 0 y 1
        var sum_zi = 0; //Valor para enviar a validación para controlar que la suma de todos los zi sean 1+-0.1

        for (var i = 0; i < components_data.length; i++) 
        {
            sum_zi += components_data[i][1];
            if(components_data[i][1] < 0 || components_data[i][1] > 1)
            {
                zi_range_flag = 0;
            }
        }

        $("#sum_zi_components_table").val(sum_zi);
        $("#zi_range_flag_components_table").val(zi_range_flag);

    }
</script>