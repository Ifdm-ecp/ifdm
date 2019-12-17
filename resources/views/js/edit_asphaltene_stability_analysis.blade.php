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
            afterChange: function(changes, source) {
                var components_table = clean_table_data("components_table");
                var sumZi = 0;

                for (var i = 0; i < components_table.length; i++) {
                    if (components_table[i] != undefined) {
                        if (components_table[i][1] != "" && components_table[i][1] != undefined && components_table[i][1] != null && $.isNumeric(components_table[i][1])) {
                            sumZi += components_table[i][1];
                        }
                    }
                }

                sumZi = parseFloat(sumZi.toFixed(2));

                if (sumZi >= 0.9 && sumZi <= 1.1) {
                    $("#total_zi").attr('class', 'label label-success');
                } else {
                    $("#total_zi").attr('class', 'label label-danger');
                }

                $("#total_zi").html(sumZi);
            },
            columns: [{
                title: components_table_ruleset[0].column,
                data: 0,
                type: 'text',
                readOnly: true,
                validator: function(value, callback) { callback(multiValidatorHandsonTable(value, components_table_ruleset[0])); }
            },
            {
                title: components_table_ruleset[1].column,
                data: 1,
                type: 'numeric',
                format: '0[.]0000000',
                validator: function(value, callback) { callback(multiValidatorHandsonTable(value, components_table_ruleset[1])); }
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
                    item [1] = items[components[i]] !== undefined ? items[components[i]] : "";

                    dataComponents.push(item);
                }
            }

            hot.updateSettings({
                data: dataComponents
            });
        });
        
        var asphaltenes_d_stability_analysis_id = <?php echo json_encode($asphaltenes_d_stability_analysis->id); ?>;
        var hot_components_table = $('#components_table').handsontable('getInstance');

        var components_value = [];

        var zi_value = [];

        $.get("{!! url('asphaltenes_d_stability_analysis_components') !!}", { //Como estamos en el editar, se necesita consultar los valores guardados previamente
            asphaltenes_d_stability_analysis_id: asphaltenes_d_stability_analysis_id
        }, function (data) {
            $.each(data.components_stability_analysis, function (index, value) {
                components_value.push(value.component);
            });

            //Saber si es la primera vez que se entra a la interfaz para cargar valores en las tablas
            aux_components_table = $("#value_components_table").val();
            if (aux_components_table === '') {//Si es la primera vez que se ingresa a la interfaz, cargamos los valores de la BD
                $('#components').selectpicker();
                $('#components').selectpicker('val', components_value);

                for (var i = components_value.length - 1; i >= 0; i--) {
                    hot_components_table.setDataAtRowProp(i, 0, components_value[i]);
                }

                $.each(data.mole_fraction_stability_analysis, function (index, value) {
                    zi_value.push(value.mole_fraction);
                });

                for (var i = zi_value.length - 1; i >= 0; i--) {
                    hot_components_table.setDataAtRowProp(i, 1, zi_value[i]);
                }
            } else {//Si no es la primera vez que se ingresa a la interfaz, cargar nuevamente los valores que habia ingresado el usuario
                hot_components_table.updateSettings({
                    data: JSON.parse(aux_components_table),
                    stretchH: 'all'
                });
            }

            hot_components_table.render();
        });
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

    function verifyAsphaltene(action) {
        // Loading
        $("#loading_icon").show();

        // Boolean for empty values for the save button
        var emptyValues = false;
        // Title tab for modal errors
        var titleTab = "";
        var tabTitle = "";
        //Saving tables...
        var validationMessages = [];
        var validationFunctionResult = [];

        // Validating Components data
        tabTitle = "Section: Components Data";

        var select_components_data = $("#components").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_components_data, components_select_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (select_components_data === null || select_components_data === "")) ? true: emptyValues;

        var passedSelector = false;

        if (titleTab === "" && select_components_data !== null) {
            passedSelector = true;
        }

        var components_data = clean_table_data("components_table");
        tableValidator = validateTable("Components Data", components_data, components_table_ruleset, action);
        if (tableValidator.length > 0) {
            if (titleTab == "") {
                titleTab = "Section: Components Data";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat(tableValidator);
        } else if (components_data.length > 0) {
            var sumZi = 0;

            for (var i = 0; i < components_data.length; i++) {
                sumZi += components_data[i][1];
            }

            sumZi = parseFloat(sumZi.toFixed(2));

            if (sumZi < 0.9 || sumZi > 1.1) {
                if (titleTab == "") {
                    titleTab = "Section: Components Data";
                    validationMessages = validationMessages.concat(titleTab);
                }
                validationMessages = validationMessages.concat("The total sum for the Zi in the Components Data table is out of the numeric range [0.9, 1.1]");
            }
        }

        // Validating SARA analysis
        titleTab = "";
        tabTitle = "Section: SARA Analysis";

        var saturate = $("#saturated").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, saturate, sara_section_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (saturate === null || saturate === "")) ? true: emptyValues;

        var aromatic = $("#aromatics").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, aromatic, sara_section_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (aromatic === null || aromatic === "")) ? true: emptyValues;

        var resine = $("#resines").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, resine, sara_section_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (resine === null || resine === "")) ? true: emptyValues;

        var asphaltene = $("#asphaltenes").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, asphaltene, sara_section_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (asphaltene === null || asphaltene === "")) ? true: emptyValues;
    
        // Validating Saturation data
        titleTab = "";
        tabTitle = "Section: Saturation Data";

        var reservoir_initial_pressure = $("#reservoir_initial_pressure").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, reservoir_initial_pressure, saturate_section_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (reservoir_initial_pressure === null || reservoir_initial_pressure === "")) ? true: emptyValues;

        var bubble_pressure = $("#bubble_pressure").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, bubble_pressure, saturate_section_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (bubble_pressure === null || bubble_pressure === "")) ? true: emptyValues;

        var density_at_reservoir_temperature = $("#density_at_reservoir_temperature").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, density_at_reservoir_temperature, saturate_section_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (density_at_reservoir_temperature === null || density_at_reservoir_temperature === "")) ? true: emptyValues;

        var api_gravity = $("#api_gravity").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, api_gravity, saturate_section_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && (api_gravity === null || api_gravity === "")) ? true: emptyValues;

        if (validationMessages.length < 1) {
            $("#value_components_table").val(JSON.stringify(components_data));
            validate_components_data(components_data);

            if (emptyValues) {
                $("#loading_icon").hide();
                validationMessages.push(true);
                showFrontendErrors(validationMessages);
            } else {
                $("#only_s").val("run");
                $("#asphalteneForm").submit();
            }
        } else {
            $("#loading_icon").hide();
            showFrontendErrors(validationMessages);
        }
    }

    /* saveForm
    * Submits the form when the confirmation button from the modal is clicked
    */
    function saveForm() {
        $("#loading_icon").show();
        $("#only_s").val("save");
        $("#asphalteneForm").submit();
    }
</script>