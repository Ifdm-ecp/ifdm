<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script type="text/javascript">

    /*Llamar siempre la funcion import_tree para advisor. Ver archivo "Ayuda advisor"*/
    $(document).ready(function () {
        import_tree("Asphaltene precipitation", "asphaltenes_precipitated_analysis");
    });
    $bubble_point_table = $('#bubble_point_table');
    $components_table = $('#components_table');
    $binary_interaction_coefficients_table = $('#binary_interaction_coefficients_table');

    //Graficar Bubble Point
    //Limpiar los datos para no graficar valores vacios
    function plot_bubble_point_table() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = order_matrix(clean_table_data("bubble_point_table"));

        var temperature = [];
        var bubble_pressure = [];

        if (data) {
            for (var i = 0; i < data.length; i++) {
                temperature.push(data[i][0]);
                bubble_pressure.push(data[i][1]);
            }
        }


        $('#graphic_bubble_point_table').highcharts({
            title: {
                text: 'Bubble Point',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Temperature (Bubble curve) [°F]'
                },
                categories: temperature,
                reversed: false
            },
            yAxis: {
                title: {
                    text: 'Bubble Pressure [psi]'
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
                name: 'Bubble Pressure [psi]',
                data: bubble_pressure
            }
            ]
        });
    }

    $(document).ready(function () {
        $(".load-data").tooltip({
            title: 'Load Data'
        });

        $(".convert-to-zero").tooltip({
            title: 'Convert To Zero'
        });

        //Calcular y pintar label Total SARA
        calculate_total_sara();
        
        //Ocultar y mostrar div plus segun sea el caso
        var select_components_init = $("#components").val();
        if (select_components_init === null) {
            $("#div_plus").hide();
        } else {
            if (select_components_init.indexOf("Plus +") != -1) {
                $("#div_plus").show();
            } else {
                $("#div_plus").hide();
            }
        }


        var asphaltenes_d_stability_analysis_id = <?php
        if ($asphaltenes_d_stability_analysis) {
            echo json_encode($asphaltenes_d_stability_analysis->id);
        } else {
            echo json_encode(null);
        }
        ?>;

        //Inicializar tabla componentes
        $components_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 0,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            stretchH: 'all',

            columns: [{
                title: "Components",
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
            {
                title: "MW[lb]",
                data: 2,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Pc[psi]",
                data: 3,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Tc[F]",
                data: 4,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "W",
                data: 5,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Shift",
                data: 6,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "SG",
                data: 7,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Tb[R]",
                data: 8,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Vc[ft3/lbmol]",
                data: 9,
                type: 'numeric',
                format: '0[.]0000000'
            }
            ]
        });

        //Inicializar tabla de interaccion binaria
        $binary_interaction_coefficients_table.handsontable({
            height: 200,
            minSpareRows: 0,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            stretchH: 'all',
            colWidths: 100
        });

        //Inicializar tabla bubble point
        $bubble_point_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 1,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            stretchH: 'all',
            contextMenu: true,


            colWidths: [360, 360],
            columns: [{
                title: "Temperature (Bubble curve) [°F]",
                data: 0,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Bubble Pressure [psi]",
                data: 1,
                type: 'numeric',
                format: '0[.]0000000'
            },
            ]
        });

        //Ayuda visual para la sumatoria de los elementos del análisis SARA
        $(".sara_data").change(function()
        {
            calculate_total_sara();
        });
        
        //Controlar check cuando se inicializa y se cambia el valor, mostrar u ocultar elemental_data segun sea el caso
        $(".elemental_data").prop('disabled', true);
        $("#elemental_data_selector").bind('init change', function () {
            if (this.checked) {
                $(".elemental_data").prop('disabled', false);
            } else {
                $(".elemental_data").prop('disabled', true);
            }
        }).trigger('init');

        //MOstrar modal para importar datos asfaltenos
        $('.import-asphaltenes-data').on('click', function () {
            $("#asphaltenesData").modal('show');
        });

        //Importar daots para tabla componentes segun componentes seleccionados
        $('.import-components-data').on('click', function () {
            var select_components = $("#components").val();
            var table = [];
            var plus = {};

            if (select_components === null) {
                alert("Please select at least one component");
            } else {
                $.get("{!! url('import_components_data') !!}", {
                    components: select_components.toString()
                }, function (data) {
                    $.each(data, function (index, value) {
                        table.push(Object.values(value));
                    });

                    //Si se seleccion Plus + se deben cargar los valores nulos para esta fila, en la BD no se encuentran los valores
                    //para Plus +
                    if (select_components.indexOf("Plus +") != -1) {
                        plus["components"] = "Plus +";
                        plus["mw"] = null;
                        plus["pc"] = null;
                        plus["sg"] = null;
                        plus["shift"] = null;
                        plus["tb"] = null;
                        plus["tc"] = null;
                        plus["vc"] = null;
                        plus["w"] = null;
                        plus["zi"] = null;

                        table.push(Object.values(plus));
                    }

                    $("#components_table").handsontable({data: table});
                    $("#components_table").handsontable('render');
                });
            }
        });

        //Antes de guardar los datos se deben limpiar y validar los datos de las tablas
        $('.save_table').on('click', function () {
            //Loading
            $("#loading_icon").show();

            bubble_point_data = order_matrix(clean_table_data("bubble_point_table"));
            components_data = clean_table_data("components_table");
            binary_interaction_coefficients_data = clean_table_data("binary_interaction_coefficients_table");

            //Validar rangos de tabla components
            validate_components_data(components_data);

            //Validar rangos de tabla binary coefficients
            validate_binary_coefficients_data(binary_interaction_coefficients_data);

            //Validar rangos de tabla bubble point
            validate_bubble_point_data(bubble_point_data);

            $("#value_components_table").val(JSON.stringify(components_data));
            $("#value_binary_interaction_coefficients_table").val(JSON.stringify(binary_interaction_coefficients_data));
            $("#value_bubble_point_table").val(JSON.stringify(bubble_point_data));

            var evt = window.event || arguments.callee.caller.arguments[0];
            if (bubble_point_data.length < 5) {
                evt.preventDefault();
                alert("Bubble point table must have more than 5 rows.");
                $("#loading_icon").hide();
            }

            //Validaciones adicionales tabla components - La suma de Zi debe ser 1+-0.1
            
            //Validaciones adicionales a tabla temperaturas - Temperaturas y presiones mayores a cero
            var flag_bubble_point_table = false;
            for (var i = 0; i <  bubble_point_data.length; i++) 
            {
                if(bubble_point_data[i][0] < 0 || bubble_point_data[i][1] < 0)
                {
                    flag_bubble_point_table = true;
                }
            }

            if(flag_bubble_point_table)
            {
                evt.preventDefault();
                alert("Temperature and Bubble Pressure data must be greater than 0.");
                $("#loading_icon").hide();
            }

            //Como las columnas de la tabla de coeficientes de interaccion binaria son dinamicos,
            //se debe armar un vector dinamicamente indicando el tipo de dato que tiene cada columna
            var num_columns_binary_interaction = binary_interaction_coefficients_data.length;
            var type_columns_binary_interaction = ["text"];

            for (var i = 0; i < num_columns_binary_interaction; i++) {
                type_columns_binary_interaction.push("numeric");
            }

            validate_table([bubble_point_data, components_data, binary_interaction_coefficients_data], ["bubble point table", "components table", "binary interaction coefficients table"], [["numeric", "numeric"], ["text", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric"], type_columns_binary_interaction]);
        });

        /* Antes de guardar los datos se deben limpiar y validar los datos de las tablas */
        $('.save_table_wr').on('click', function () {
            /* Loading */
            $("#loading_icon").show();

            bubble_point_data = order_matrix(clean_table_data("bubble_point_table"));
            components_data = clean_table_data("components_table");
            binary_interaction_coefficients_data = clean_table_data("binary_interaction_coefficients_table");

            /* Validar rangos de tabla components */
            validate_components_data(components_data);

            /* Validar rangos de tabla binary coefficients */
            validate_binary_coefficients_data(binary_interaction_coefficients_data);

            /* Validar rangos de tabla bubble point */
            validate_bubble_point_data(bubble_point_data);

            $("#value_components_table").val(JSON.stringify(components_data));
            $("#value_binary_interaction_coefficients_table").val(JSON.stringify(binary_interaction_coefficients_data));
            $("#value_bubble_point_table").val(JSON.stringify(bubble_point_data));

            /* Validaciones adicionales tabla components - La suma de Zi debe ser 1+-0.1 */
            /* Validaciones adicionales a tabla temperaturas - Temperaturas y presiones mayores a cero */
            var flag_bubble_point_table = false;
            for (var i = 0; i <  bubble_point_data.length; i++) {
                if(bubble_point_data[i][0] < 0 || bubble_point_data[i][1] < 0) {
                    flag_bubble_point_table = true;
                }
            }

            /* Como las columnas de la tabla de coeficientes de interaccion binaria son dinamicos, */
            /* se debe armar un vector dinamicamente indicando el tipo de dato que tiene cada columna */
            var num_columns_binary_interaction = binary_interaction_coefficients_data.length;
            var type_columns_binary_interaction = ["text"];

            for (var i = 0; i < num_columns_binary_interaction; i++) {
                type_columns_binary_interaction.push("numeric");
            }
        });

        //Variables para armar tabla de componentes
        col_values = [];

        item = {}
        item ["title"] = "Components";
        item ["data"] = "components";
        item ["type"] = "text";
        item ["readOnly"] = true;

        col_values.push(item);

        var hot_coefficients_table = $('#binary_interaction_coefficients_table').handsontable('getInstance');
        var hot_components_table = $('#components_table').handsontable('getInstance');

        component_value = [];
        zi_value = [];

        var aux_components_table = $("#value_components_table").val();

        if (aux_components_table === '') {//Si se hizo el modulo de estabilidad importar datos de tabla componentes
            if (asphaltenes_d_stability_analysis_id) {
                $.get("{!! url('asphaltenes_d_stability_analysis_components') !!}", {
                    asphaltenes_d_stability_analysis_id: asphaltenes_d_stability_analysis_id
                }, function (data) {
                    $.each(data.components_stability_analysis, function (index, value) {
                        item = {}
                        item ["title"] = value.component;
                        item ["data"] = value.component;
                        item ["type"] = 'numeric';
                        item ["format"] = '0[.]0000000';

                        col_values.push(item);
                        component_value.push(value.component);
                    });

                    if (component_value) {
                        $('#components').selectpicker('val', component_value);

                        if (component_value.indexOf("Plus +") != -1) {
                            $("#div_plus").show();
                        } else {
                            $("#div_plus").hide();
                        }

                        //Armar columnas para la tabla componentes segun modulo de estabilidad
                        hot_coefficients_table.updateSettings({
                            columns: col_values,
                            stretchH: 'all'
                        });
                        hot_coefficients_table.render();

                        //Armar datos de tablas componentes y coeficientes con datos del modulo de estabilidad
                        for (var i = component_value.length - 1; i >= 0; i--) {
                            hot_coefficients_table.setDataAtRowProp(i, "components", component_value[i]);
                            hot_components_table.setDataAtRowProp(i, 0, component_value[i]);
                        }


                        $.each(data.mole_fraction_stability_analysis, function (index, value) {
                            zi_value.push(value.mole_fraction);
                        });

                        for (var i = zi_value.length - 1; i >= 0; i--) {
                            hot_components_table.setDataAtRowProp(i, 1, zi_value[i]);
                        }
                    }
                });
            }
        } else {//Si se han ingresado valores a las tablas, volver a cargarlos
            hot_components_table.updateSettings({
                data: JSON.parse(aux_components_table),
                stretchH: 'all'
            });


            var old_col_values_binary_interaction = [];
            var all_binary_interaction = [];

            //Armar la configuracion para tablas de interaccion binaria
            old_item_binary_interaction = {}
            old_item_binary_interaction ["title"] = "Components";
            old_item_binary_interaction ["data"] = "components";
            old_item_binary_interaction ["type"] = "text";
            old_item_binary_interaction ["readOnly"] = true;
            old_col_values_binary_interaction.push(old_item_binary_interaction);
            var aux_components = $("#components").val();

            if (aux_components) {
                //Para cada uno de los componentes armar configuracion de filas y columnas
                for (i = 0; i < aux_components.length; i++) {
                    old_item_binary_interaction = {}
                    old_item_binary_interaction ["title"] = aux_components[i];
                    old_item_binary_interaction ["data"] = aux_components[i];
                    old_item_binary_interaction ["type"] = 'numeric';
                    old_item_binary_interaction ["format"] = '0[.]0000000';
                    old_col_values_binary_interaction.push(old_item_binary_interaction);
                }

                //Armar datos de tabla con valores de componentes
                //Se ligan los valores ingresados por el usuario a cada uno de los componentes para saber
                //cuales valores se conservan y cuales se eliminan segun el selector de componentes.
                var aux_binary_interaction_coefficients_table = $("#value_binary_interaction_coefficients_table").val();
                aux_binary_interaction_coefficients_table = JSON.parse(aux_binary_interaction_coefficients_table);
                for (i = 0; i < aux_binary_interaction_coefficients_table.length; i++) {
                    var binary_interaction = {};
                    binary_interaction["components"] = aux_binary_interaction_coefficients_table[i][0];
                    for (j = 1; j < aux_binary_interaction_coefficients_table[i].length; j++) {
                        binary_interaction[aux_components[j - 1]] = aux_binary_interaction_coefficients_table[i][j];
                    }
                    all_binary_interaction.push(binary_interaction);
                }

                hot_coefficients_table.updateSettings({
                    columns: old_col_values_binary_interaction,
                    data: all_binary_interaction,
                    stretchH: 'all'
                });
            }

        }


        var hot_bubble_point_table = $('#bubble_point_table').handsontable('getInstance');
        var aux_bubble_point_table = $("#value_bubble_point_table").val();

        if (aux_bubble_point_table !== '') {//Si se han ingresado datos en la tabla volver a cargarlos
            hot_bubble_point_table.updateSettings({
                data: JSON.parse(aux_bubble_point_table),
                stretchH: 'all'
            });
        }
        hot_bubble_point_table.render();

        //Cuando el selector de componentes cambia, se debe cambiar las filas de la tabla componentes y filas y
        //columnas de la tabla de coeficientes de interaccion binaria
        $("#components").change(function (e) {
            var col_values_binary_interaction = [];
            var data_components = [];
            var data_binary_interaction = [];
            var components = $("#components").val();
            if (components) {
                if (components.indexOf("Plus +") != -1) {
                    $("#div_plus").show();
                } else {
                    $("#div_plus").hide();
                }

                //Inicializar configuracion de tabla de coeficientes de interaccion binaria
                item_binary_interaction = {}
                item_binary_interaction ["title"] = "Components";
                item_binary_interaction ["data"] = "components";
                item_binary_interaction ["type"] = "text";
                item_binary_interaction ["readOnly"] = true;
                col_values_binary_interaction.push(item_binary_interaction);

                hot_components_table = $('#components_table').handsontable('getInstance');

                items = {};

                items_binary_interaction = {};


                zi = hot_components_table.getSourceDataAtCol(1);
                component = hot_components_table.getSourceDataAtCol(0);

                //Organizar variables con los datos de cada tabla
                for (i = 0; i < component.length; i++) {
                    items_binary_interaction_component = {};
                    items_components_data = {};

                    for (j = 1; j < component.length; j++) {
                        items_binary_interaction_component [component[j - 1]] = hot_coefficients_table.getSourceDataAtCol(j)[i];
                    }

                    for (h = 1; h < 14; h++) {
                        items_components_data [h - 1] = hot_components_table.getSourceDataAtCol(h - 1)[i];
                    }

                    items_binary_interaction [component[i]] = items_binary_interaction_component;
                    items [component[i]] = items_components_data;
                }

                //Organizar los datos para tabla componentes e interaccion binaria segun selector de componentes
                for (i = 0; i < components.length; i++) {
                    item_binary_interaction = {};
                    item = {}

                    item_binary_interaction["components"] = components[i];
                    for (j = 0; j < components.length; j++) {
                        if (items_binary_interaction [components[i]]) {
                            //alert(components[i]);
                            item_binary_interaction[components[j]] = items_binary_interaction [components[i]][components[j]];
                        } else {
                            item_binary_interaction[components[j]] = null;
                        }
                    }

                    for (h = 0; h < 14; h++) {
                        if (items [components[i]]) {
                            item[h] = items [components[i]][h];
                        } else {
                            if (h == 0) {
                                item[h] = components[i];
                            } else {
                                item[h] = null;
                            }
                        }
                    }

                    data_binary_interaction.push(item_binary_interaction);
                    data_components.push(item);

                    item_binary_interaction = {}
                    item_binary_interaction ["title"] = components[i];
                    item_binary_interaction ["data"] = components[i];
                    item_binary_interaction ["type"] = 'numeric';
                    item_binary_interaction ["format"] = '0[.]0000000';

                    col_values_binary_interaction.push(item_binary_interaction);
                }


            }
            //Recargar tabla con nuevos valores y columnas segun los compoentes
            hot_coefficients_table.updateSettings({
                columns: col_values_binary_interaction,
                data: data_binary_interaction,
                stretchH: 'all'
            });

            hot_coefficients_table.render();

            hot_components_table.updateSettings({
                data: data_components
            });
        });

});

function createArray(length) {
    var arr = new Array(length || 0),
    i = length;

    if (arguments.length > 1) {
        var args = Array.prototype.slice.call(arguments, 1);
        while (i--) arr[length - 1 - i] = createArray.apply(this, args);
    }

    return arr;
}

    //Llenar toda la tabla de interaccion binaria con ceros
    $('.convert-to-zero').on('click', function () {
        var hot_coefficients_table = $('#binary_interaction_coefficients_table').handsontable('getInstance');
        var components = $("#components").val();

        var binary_interaction_coefficients = [];
        for (var i = 0; i < components.length; i++) {
            var aux = {};
            aux["components"] = components[i];
            for (var j = 0; j < components.length; j++) {
                aux[components[j]] = 0;
            }
            binary_interaction_coefficients.push(aux);
        }
        hot_coefficients_table.updateSettings({
            data: binary_interaction_coefficients,
            stretchH: 'all'
        });

        hot_coefficients_table.render();
    });

    //Llamar función desde botón
    $('.calculate-binary-interaction-coefficients').on('click', function () {
        var hot_coefficients_table = $('#binary_interaction_coefficients_table').handsontable('getInstance');
        var select_components = $("#components").val();
        if (select_components === null) {
            alert("Please select at least one component");
        } else {
            v1 = [0.0, 0.0, 0.176, 0.0311, 0.0515, 0.852, 0.1, 0.0711, 0.1, 0.1, 0.1496, 0.1441, 0.15, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155, 0.155];
            v2 = [0.0, 0.0, 0.088, 0.107, 0.1322, 0.1241, 0.14, 0.1333, 0.14, 0.14, 0.145, 0.145, 0.14, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145, 0.0145];
            v3 = [0.176, 0.088, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v4 = [0.0311, 0.107, 0.08, 0.0, 0.0026, 0.014, 0.0256, 0.0133, -0.0056, 0.0236, 0.0422, 0.0352, 0.047, 0.047, 0.05, 0.05, 0.05, 0.05, 0.05, 0.06, 0.06, 0.06, 0.06, 0.06, 0.07, 0.07, 0.07, 0.07, 0.07];
            v5 = [0.0515, 0.1322, 0.095, 0.0026, 0.0, 0.0011, -0.0067, 0.0096, 0.008, 0.0078, 0.014, 0.015, 0.016, 0.019, 0.03, 0.03, 0.03, 0.03, 0.03, 0.04, 0.04, 0.04, 0.04, 0.04, 0.05, 0.05, 0.05, 0.05, 0.05];
            v6 = [0.852, 0.1241, 0.088, 0.014, 0.0011, 0.0, -0.0078, 0.0033, 0.0111, 0.012, 0.0267, 0.056, 0.059, 0.007, 0.02, 0.02, 0.02, 0.02, 0.02, 0.025, 0.025, 0.025, 0.025, 0.025, 0.03, 0.03, 0.03, 0.03, 0.03];
            v7 = [0.1, 0.14, 0.05, 0.0256, -0.0067, -0.0078, 0.0, -0.004, 0.002, 0.024, 0.025, 0.025, 0.026, 0.006, 0.01, 0.01, 0.01, 0.01, 0.01, 0.015, 0.015, 0.015, 0.015, 0.015, 0.02, 0.02, 0.02, 0.02, 0.02];
            v8 = [0.0711, 0.1333, 0.05, 0.0133, 0.0096, 0.0033, -0.004, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v9 = [0.1, 0.14, 0.047, -0.0056, 0.008, 0.0111, 0.002, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v10 = [0.1, 0.14, 0.0, 0.0236, 0.0078, 0.012, 0.024, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v11 = [0.1496, 0.145, 0.0, 0.0422, 0.014, 0.0267, 0.025, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v12 = [0.1441, 0.145, 0.0, 0.0352, 0.015, 0.056, 0.025, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v13 = [0.15, 0.14, 0.0, 0.047, 0.016, 0.059, 0.026, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v14 = [0.155, 0.0145, 0.0, 0.047, 0.019, 0.007, 0.006, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v15 = [0.155, 0.0145, 0.0, 0.05, 0.03, 0.02, 0.01, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v16 = [0.155, 0.0145, 0.0, 0.05, 0.03, 0.02, 0.01, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v17 = [0.155, 0.0145, 0.0, 0.05, 0.03, 0.02, 0.01, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v18 = [0.155, 0.0145, 0.0, 0.05, 0.03, 0.02, 0.01, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v19 = [0.155, 0.0145, 0.0, 0.05, 0.03, 0.02, 0.01, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v20 = [0.155, 0.0145, 0.0, 0.06, 0.04, 0.025, 0.015, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0009];
            v21 = [0.155, 0.0145, 0.0, 0.06, 0.04, 0.025, 0.015, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v22 = [0.155, 0.0145, 0.0, 0.06, 0.04, 0.025, 0.015, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v23 = [0.155, 0.0145, 0.0, 0.06, 0.04, 0.025, 0.015, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v24 = [0.155, 0.0145, 0.0, 0.06, 0.04, 0.025, 0.015, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v25 = [0.155, 0.0145, 0.0, 0.07, 0.05, 0.03, 0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v26 = [0.155, 0.0145, 0.0, 0.07, 0.05, 0.03, 0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v27 = [0.155, 0.0145, 0.0, 0.07, 0.05, 0.03, 0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v28 = [0.155, 0.0145, 0.0, 0.07, 0.05, 0.03, 0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
            v29 = [0.155, 0.0145, 0.0, 0.07, 0.05, 0.03, 0.02, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];

            binary_interaction_coefficients_database = [v1, v2, v3, v4, v5, v6, v7, v8, v9, v10, v11, v12, v13, v14, v15, v16, v17, v18, v19, v20, v21, v22, v23, v24, v25, v26, v27, v28];

            components_id = {
                "N2": 1,
                "CO2": 2,
                "H2S": 3,
                "C1": 4,
                "C2": 5,
                "C3": 6,
                "IC4": 7,
                "NC4": 8,
                "IC5": 9,
                "NC5": 10,
                "NC6": 11,
                "NC7": 12,
                "NC8": 13,
                "NC9": 14,
                "NC10": 15,
                "NC11": 16,
                "NC12": 17,
                "NC13": 18,
                "NC14": 19,
                "NC15": 20,
                "NC16": 21,
                "NC17": 22,
                "NC18": 23,
                "NC19": 24,
                "NC20": 25,
                "NC21": 26,
                "NC22": 27,
                "NC23": 28,
                "NC24": 29
            };


            table_data = $("#components_table").handsontable('getData');
            table_data_length = select_components.length;
            binary_interaction_coefficients = createArray(table_data_length, table_data_length);
            aux_ciy = 0;
            aux_cix = 0;
            for (var j = 0; j < table_data_length; j++) {
                ciy = components_id[table_data[j][0]];
                if (ciy == null) {
                    ciy = aux_ciy;
                }
                else {
                    aux_ciy = ciy;
                }
                for (var i = 0; i < table_data_length; i++) {
                    cix = components_id[table_data[i][0]];
                    if (cix == null) {
                        cix = aux_cix;
                    }
                    else {
                        aux_cix = cix;
                    }
                    if (table_data[i][0] === "Plus +" || table_data[j][0] === "Plus +") {
                        vci = table_data[i][9];
                        vcj = table_data[i][9];
                        num = 2 * Math.pow(vci, 0.1666667) * Math.pow(vcj, 0.1666667);
                        den = Math.pow(vci, 0.333333) + Math.pow(vcj, 0.333333);
                        cib = 0.18 * (1.0 - (Math.pow(num / den), 6));
                    }
                    if (i == j) {
                        cib = 0;
                    }
                    else {
                        cib = binary_interaction_coefficients_database[cix - 1][ciy - 1];
                    }
                    binary_interaction_coefficients[i][j] = cib;
                }
            }

            var data = [];
            for (j = 0; j < select_components.length; j++) {
                item_binary_interaction = {};

                item_binary_interaction["components"] = select_components[j];
                for (i = 0; i < select_components.length; i++) {
                    item_binary_interaction[select_components[i]] = binary_interaction_coefficients[j][i];
                }
                data.push(item_binary_interaction);
            }

            hot_coefficients_table.updateSettings({
                data: data,
                stretchH: 'all'
            });
            hot_coefficients_table.render();
        }
    });


    //Llenar valores de la fila de Plus+ en tabla componentes
    $('.characterize_plus_component').on('click', function () {
        var hot_components_table = $('#components_table').handsontable('getInstance');

        //Parámetros lee -kesler
        var a1 = -5.92714;
        var a2 = 6.09648;
        var a3 = 1.28862;
        var a4 = -0.169347;
        var a5 = 15.2518;
        var a6 = -15.6875;
        var a7 = -13.4721;
        var a8 = 0.43577;

        var components = $("#components").val();
        var nc = components.length; //Número de componentes - Traer desde vista *Karen
        var mw = $("#plus_fraction_molecular_weight").val(); //Traer desde vista *karen 396.93
        var sg = $("#plus_fraction_specific_gravity").val();
        ; //Traer desde vista *karen 0.9256
        var tb = $("#plus_fraction_boiling_temperature").val();
        ; //Traer desde vista  *karen 1000

        var components_data = $components_table.handsontable('getData');

        var zi_plus_data = components_data[nc - 1][1];

        components_data.splice(-2);

        var correlation_select = $("#correlation").val(); //leer selector *Karen
        console.log(correlation_select);
        if (mw && sg && tb && zi_plus_data && !isNaN(mw) && !isNaN(sg) && !isNaN(tb) && !isNaN(mw) && !isNaN(zi_plus_data) && correlation_select != ' ') {


            if (correlation_select == "Twu") {
                num = 0.533272 + 0.000191017 * tb + 0.0000000779681 * Math.pow(tb, 2) - 2.84376e-11 * Math.pow(tb, 3) + 95.9468 / Math.pow((0.01 * tb), 13);
                tcb = tb / num;
                a = 1 - (tb / tcb);
                pcb = Math.pow((3.83354 + 1.19629 * Math.pow(a, 0.5) + 34.8888 * a + 36.1652 * a * a + 104.193 * Math.pow(a, 4)), 2);
                vcb = Math.pow((1 - (0.419 - 0.505839 * a - 1.56436 * Math.pow(a, 3) - 9481.7 * Math.pow(a, 14))), -8);
                sgb = 0.843593 - 0.12862 * a - 3.36159 * Math.pow(a, 3) - 13749.5 * Math.pow(a, 12);

                dsgt = Math.exp(5 * (sgb - sg)) - 1.0;
                ft = dsgt * (-0.362456 / Math.pow(tb, 0.5) + dsgt * (0.0398285 - 0.948125 / Math.pow(tb, 0.5)));
                tcplus = tcb * Math.pow(((1 + 2 * ft) / (1 - 2 * ft)), 2);

                dsgv = Math.exp(4.0 * (sgb * sgb - sg * sg)) - 1.0;
                fv = dsgv * (0.46659 / Math.pow(tb, 0.5) + dsgv * (-0.182421 + 3.01721 / Math.pow(tb, 0.5)));
                vcplus = vcb * Math.pow(((1 + 2 * ft) / (1 - 2 * ft)), 2);

                dsgp = Math.exp(0.5 * (sgb - sg)) - 1.0;
                fp = dsgp * ((2.53262 - 46.1955 / Math.pow(tb, 0.5) - 0.00127885 * tb) + dsgv * ((-11.4277 + 252.14 / Math.pow(tb, 0.5) + 0.00230535 * tb)));
                pcplus = pcb * Math.pow(((1 + 2 * ft) / (1 - 2 * ft)), 2);
                tbr = tb / tcplus;

                if (tbr < 0.8) {
                    num = -Math.log(pcplus / 14.7) + a1 + (a2 / tbr) + a3 * Math.log(tbr) + a4 * Math.pow(tbr, 6);
                    den = a5 + (a6 / tbr) + a7 * Math.log(tbr) + a8 * Math.pow(tbr, 6);
                    omega = num / den;
                }
                else {
                    sum = 0;
                    zplus = components_data[nc - 1][2]; //Valor zi de plus+ en components data
                    for (var i = 0; i < nc; i++) {
                        dummy = components_data[8 + i][2];
                        dummy3 = components_data[8 + i][3];
                        dummy3 = components_data[8 + i][8];
                        sum = sum + dummy * Math.pow(dummy2, 0.82053);
                    }

                    kw = Math.pow((0.16637 * sg * sum / (zplus * mw)), -0.84573);
                    omega = -7.904 + 0.1352 * kw - 0.007465 * Math.pow(kw, 2) + 8.359 * tbr + (1.408 - 0.01063 * kw) / tbr;
                }
            }
            else if (correlation_select == "Lee-Kesler") {
                //Lee - Kesler
                tcplus = 341.7 + 811 * sg + (0.4244 + 0.1174 * sg) * tb + (0.4669 - 3.2623 * sg) * 100000.0 / tb;
                dummy = tb * (0.001 * (0.24244 + (2.2898 / sg) + 0.11857 / (sg * sg)));
                dummy2 = tb * tb * (0.0000001 * (0.1685 + (3.648 / sg) + 0.47277 / (sg * sg)));
                dummy3 = tb * tb * tb * (0.0000000001 * (0.1685 + 1.6977 / (sg * sg)));
                pcplus = Math.exp(8.3634 - (0.0566 / sg) - dummy + dummy2 - dummy3);
                tbr = tb / tcplus;
                if (tbr < 0.8) {

                    num = -Math.log(pcplus / 14.7) + a1 + (a2 / tbr) + a3 * Math.log(tbr) + a4 * Math.pow(tbr, 6);
                    den = a5 + (a6 / tbr) + a7 * Math.log(tbr) + a8 * Math.pow(tbr, 6);
                    omega = num / den;
                }
                else {
                    sum = 0;
                    zplus = components_data[nc - 1][2];
                    for (var i = 0; i < nc; i++) {
                        dummy = components_data[8 + i][2];
                        dummy2 = components_data[8 + i][3];
                        dummy3 = components_data[8 + i][8];
                        sum = sum + dummy * Math.pow(dummy2, 0.82053);
                    }
                    kw = Math.pow((0.16637 * sg * sum / (zplus * mw)), -0.84573);
                    omega = -7.904 + 0.1352 * kw - 0.007465 * Math.pow(kw, 2) + 8.359 * tbr + (1.408 - 0.01063 * kw) / tbr;
                }

                vcplus = (7.0434 * 0.0000001) * Math.pow(tb, 2.3829) * Math.pow(sg, -1.683);
            }
            else if (correlation_select == "Kavett") {
                //Cavett
                sgapi = (141.5 / sg) - 131.5;
                tbf = tb - 459.67;

                dummy = 1.7133693 * tbf - (0.10834003 * 0.01) * Math.pow(tbf, 2) - (0.89212579 * 0.01) * sgapi * tbf;
                dummy2 = (0.38890584 * 0.000001) * Math.pow(tbf, 3) + (0.5309492 * 0.00001) * sgapi * Math.pow(tbf, 2);
                dummy3 = (0.327116 * 0.0000001) * Math.pow(sgapi, 2) * Math.pow(tbf, 2);
                tcplus = 768.07121 + dummy - dummy2 + dummy3;
                tcplus = tcplus + 460;
                dummy = 2.8290406 + (0.94120109 * 0.001) * tbf - (0.30474749 * 0.00001) * Math.pow(tbf, 2);
                dummy2 = -(0.2087611 * 0.0001) * (tbf * sgapi) + (0.15184103 * 0.00000001) * Math.pow(tbf, 3);
                dummy3 = (0.11047899 * 0.0000001) * sgapi * Math.pow(tbf, 2) - (0.48271599 * 0.0000001) * Math.pow(sgapi ^ 2) * tbf;
                dummy4 = (0.13949619 * 0.000000001) * Math.pow(sgapi, 2) * Math.pow(tbf, 2);
                pcplus = Math.pow(10, (dummy + dummy2 + dummy3 + dummy4));

                tbr = tb / tcplus;
                if (tbr < 0.8) {
                    num = -Math.log(pcplus / 14.7) + a1 + (a2 / tbr) + a3 * Math.log(tbr) + a4 * Math.pow(tbr, 6);
                    den = a5 + (a6 / tbr) + a7 * Math.log(tbr) + a8 * Math(tbr, 6);
                    omega = num / den;
                }
                else {
                    sum = 0;
                    zplus = components_data[nc - 1][2];
                    for (var i = 0; i < nc; i++) {
                        dummy = components_data[8 + i][2];
                        dummy2 = components_data[8 + i][3];
                        dummy3 = components_data[8 + i][8];
                        sum = sum + dummy * Math.pow(dummy2, 0.82053);
                    }
                    kw = Math.pow((0.16637 * sg * sum / (zplus * mw)), -0.84573);
                    omega = -7.904 + 0.1352 * kw - 0.007465 * Math.pow(kw, 2) + 8.359 * tbr + (1.408 - 0.01063 * kw) / tbr;
                }

                vcplus = (7.0434 * 0.0000001) * Math.pow(tb, 2.3829) * Math.pow(sg, -1.683);
            }
            else if (correlation_select == "Pedersen") {
                //Pedersen
                tcplus = 386.83 * Math.log(mw) - 759.59;
                pcplus = Math.pow(5982.70392636 * mw, -0.595409513949082);
                omega = (0.000000000564797) * Math.pow(mw, 3) + (-0.00000203162) * Math.pow(mw, 2) + 0.003232621 * mw + 0.024358215;
                vcplus = (7.0434 * 0.0000001) * Math.pow(tb, 2.3829) * Math.pow(sg, -1.683);
            }
            else if (correlation_select == "Riazzi Daubert") {
                //Riazi Daubert
                tcplus = 24.272871 * Math.pow(tb, 0.58848) * Math.pow(sg, 0.3596);
                pcplus = (3.12281 * 1000000000.0) * Math.pow(tb, -2.3125) * Math.pow(sg, 2.3201);
                tbr = tb / tcplus;
                if (tbr < 0.8) {
                    num = -Math.log(pcplus / 14.7) + a1 + (a2 / tbr) + a3 * Math.log(tbr) + a4 * Math.pow(tbr, 6);
                    den = a5 + (a6 / tbr) + a7 * Math.log(tbr) + a8 * Math.pow(tbr, 6);
                    omega = num / den;
                }
                else {
                    sum = 0;
                    zplus = components_data[nc - 1][2];
                    for (var i = 0; i < nc - 1; i++) {
                        dummy = components_data[8 + i][2];
                        dummy2 = components_data[8 + i][3];
                        sum = sum + dummy * dummy2;
                    }

                    sum = sum + mw * zplus;
                    kw = 0.16637 * sg * sum / (zplus * mw);
                    omega = -7.904 + 0.1352 * kw - 0.007465 * Math.pow(kw, 4) + 8.359 * tbr + (1.408 - 0.01063 * kw) / tbr;
                }

                vcplus = (7.0434 * 0.0000001) * Math.pow(tb, 2.3829) * Math.pow(sg, -1.683);
            }

            pch = -11.4 + 3.23 * mw - 0.0022 * Math.pow(mw, 2);
            zra = 0.19844 - 0.00877 * omega;


            plus_data = []; //Resultados *Karen -->aquí están todas las variables en orden a partir de mw. Para hacer los cálculos debe estar el valor de fracción molar (zi) en plus.

            plus_data.push("Plus +");
            plus_data.push(zi_plus_data); //mw
            plus_data.push(Number(mw)); //mw
            plus_data.push(pcplus + 14.7); //pcplus
            plus_data.push(tcplus - 460); //tc
            plus_data.push(omega); //omega
            plus_data.push(1 - 2.5105 / Math.pow(mw, 0.2051)); //shift
            plus_data.push(Number(sg)); //sg
            plus_data.push(Number(tb)); //tb
            plus_data.push(vcplus); //vc
            //plus_data.push(pch); //pch
            //plus_data.push(zra); //zra


            var value_table = $components_table.handsontable('getData');
            value_table[nc - 1] = plus_data;

            hot_components_table.updateSettings({
                data: value_table
            });

            hot_components_table.render();
        } else {
            alert("Missing data. Please complete the data and try again.");
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

    function order_matrix(matrix)
    {
        var row_aux;
        for (var i = 0 ; i<matrix.length; i++) 
        {
           for (var j=0; j<matrix.length; j++) {
             if(matrix[j][0]>matrix[i][0] && matrix[i][0])
             {
               row_aux = matrix[j];
               matrix[j] = matrix[i];
               matrix[i] = row_aux;
           }
       }   
   }
   return matrix;
}

function calculate_total_sara()
{
    var saturate_value = isNaN(parseFloat($("#saturate").val())) ? 0 : parseFloat($("#saturate").val());
    var aromatic_value = isNaN(parseFloat($("#aromatic").val())) ? 0 : parseFloat($("#aromatic").val());
    var resine_value = isNaN(parseFloat($("#resine").val())) ? 0 : parseFloat($("#resine").val());
    var asphaltene_value = isNaN(parseFloat($("#asphaltene").val())) ? 0 : parseFloat($("#asphaltene").val());

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

    function validate_binary_coefficients_data(binary_interaction_coefficients_data)
    {
        var binary_coefficients_range_flag = 1;

        for (var i = 0; i < binary_interaction_coefficients_data.length; i++) 
        {
            for (var j = 1; j < binary_interaction_coefficients_data[i].length; j++) 
            {
                if(binary_interaction_coefficients_data[i][j] < -10 || binary_interaction_coefficients_data[i][j] > 10)
                {
                    binary_coefficients_range_flag = 0;
                }
            }
        }

        $("#binary_coefficients_range_flag").val(binary_coefficients_range_flag);
    }

    function validate_bubble_point_data(bubble_point_data)
    {
        var bubble_point_data_range_flag = 1;
        for (var i = 0; i < bubble_point_data.length; i++) 
        {
            if(bubble_point_data[i][0] < 0 || bubble_point_data[i][1] < 0)
            {
                bubble_point_data_range_flag = 0;
            }
        }

        $("#bubble_point_data_range_flag").val(bubble_point_data_range_flag);
    }
</script>