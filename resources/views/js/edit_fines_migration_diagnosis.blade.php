<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">

<script type="text/javascript">

    //Arreglar error de modal de bootstrap. Cuando hay dos modales abiertos se bloquea completamente la pantalla
    $(document).on({
        'show.bs.modal': function () {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function () {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        },
        'hidden.bs.modal': function () {
            if ($('.modal:visible').length > 0) {
                // restore the modal-open class to the body element, so that scrolling works
                // properly after de-stacking a modal.
                setTimeout(function () {
                    $(document.body).addClass('modal-open');
                }, 0);
            }
        }
    }, '.modal');

    /*Llamar siempre la funcion import_tree para advisor. Ver archivo "Ayuda advisor"*/
    $(document).ready(function () {
        import_tree("Swelling and fines migration", "fines_migration_depositation_swelling");
    });

    $pvt_table = $("#pvt_table");
    $phenomenological_constants_table = $("#phenomenological_constants_table");
    $historical_data_table = $("#historical_data_table");


    $(document).ready(function () {
        $(".load-data").tooltip({
            title: 'Load Data'
        });

        //Inicializar tabla PVT
        $pvt_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 1,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            contextMenu: true,
            stretchH: 'all',
            colWidths: [100, 215, 215, 200],
        });

        //Inicializar tabla de constantes fenomenologicas
        $phenomenological_constants_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 1,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            contextMenu: true,
            stretchH: 'all',
            colWidths: [100, 80, 80, 120, 80, 80, 80, 120, 100, 80, 100, 80],


            columns: [{
                title: "Flow [cc/min]",
                data: 0,
                type: 'numeric',
                format: '0[.]0000000'
            },
                {
                    title: "K1",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "K2",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "DP/DL [atm/cm]",
                    data: 3,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "K3",
                    data: 4,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "K4",
                    data: 5,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "K5",
                    data: 6,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "DP/DL [atm/cm]",
                    data: 7,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "SIGMA",
                    data: 8,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "K6",
                    data: 9,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "2AB",
                    data: 10,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "AB",
                    data: 11,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
            ]
        });

        //Inicializar tabla de historicos
        $historical_data_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 1,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            contextMenu: true,
            stretchH: 'all',
            colWidths: [240, 240],

            columns: [{
                title: "Date [YYYY-MM-DD]",
                data: 0,
                type: 'date',
                dateFormat: 'YYYY-MM-DD'
            },
                {
                    title: "BOPD [bbl/d]",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                }
            ]
        });

        $('.concentration_ev').on('click', function () {
            $("#fines_concentration_fluid").modal('toggle');
        });

        //Antes de guardar los datos se deben limpiar y validar los datos de las tablas
        $('.save_table').on('click', function () {
            //Loading
            $("#loading_icon").show();

            historical_data = clean_table_data("historical_data_table");
            $("#value_historical_data").val(JSON.stringify(historical_data));

            phenomenological_constants = clean_table_data("phenomenological_constants_table");
            $("#value_phenomenological_constants").val(JSON.stringify(phenomenological_constants));

            pvt_data = clean_table_data("pvt_table");
            $("#value_pvt_data").val(JSON.stringify(pvt_data));

            validate_table([pvt_data, historical_data, phenomenological_constants], ["pvt table", "historical table", "phenomenological constants table"], [["numeric", "numeric", "numeric", "numeric"], ["text", "numeric"], ["numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric", "numeric"]]);

        });

        //Antes de guardar los datos se deben limpiar y validar los datos de las tablas
        $('.save_table_wr').on('click', function () {
            //Loading
            $("#loading_icon").show();

            historical_data = clean_table_data("historical_data_table");
            $("#value_historical_data").val(JSON.stringify(historical_data));

            phenomenological_constants = clean_table_data("phenomenological_constants_table");
            $("#value_phenomenological_constants").val(JSON.stringify(phenomenological_constants));

            pvt_data = clean_table_data("pvt_table");
            $("#value_pvt_data").val(JSON.stringify(pvt_data));

        });

        //Abrir modal para importar datos
        $('.import-phenomenological-data').on('click', function () {
            $("#phenomenologicalData").modal('show');
        });

        $("#perform_production_projection_selector").bind('init change', function () {
            if (this.checked) {
                $("#production_projection").show();
            } else {
                $("#production_projection").hide();
            }
            ;
        }).trigger('init');


        //Si el tipo de fluido cambia, cambiar columnas de tabla PVT
        $("#type_of_suspension_flux").bind('init change', function () {
            $("#graphic_pvt_table").empty();
            var type_suspension_flux = $("#type_of_suspension_flux").val();
            var instance_pvt_table = $pvt_table.handsontable('getInstance');


            var new_item = [];

            if (type_suspension_flux == "water") {
                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Pressure [psi]";
                item_suspension_flux ["data"] = 0;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Water Volumetric Factor [bbl/BN]";
                item_suspension_flux ["data"] = 1;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Water Viscosity [cP]";
                item_suspension_flux ["data"] = 2;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Water Density [g/cc]";
                item_suspension_flux ["data"] = 3;
                new_item.push(item_suspension_flux);


                //$('#historical_oil').hide();
                //$('#historical_water').show();
            } else if (type_suspension_flux == "oil") {
                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Pressure [psi]";
                item_suspension_flux ["data"] = 0;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Oil Density [g/cc]";
                item_suspension_flux ["data"] = 1;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Oil Viscosity [cP]";
                item_suspension_flux ["data"] = 2;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Oil Volumetric Factor [bbl/BN]";
                item_suspension_flux ["data"] = 3;
                new_item.push(item_suspension_flux);

                //$('#historical_oil').show();
                //$('#historical_water').hide();
            }

            instance_pvt_table.updateSettings({
                columns: new_item,
                stretchH: 'all'
            });
            instance_pvt_table.render();
        }).trigger('init');


        //Recordar datos de tablas//
        var scenario_id = <?php
            if ($fines_d_diagnosis) {
                echo json_encode($fines_d_diagnosis->scenario_id);
            } else {
                echo json_encode(null);
            }
            ?>;

        var aux_phenomenological_constants_table = $("#value_phenomenological_constants").val();
        var hot_phenomenological_constants_table = $('#phenomenological_constants_table').handsontable('getInstance');
        if (aux_phenomenological_constants_table !== '') {//Si se han ingresado datos en la tabla volver a cargarlos
            hot_phenomenological_constants_table.updateSettings({
                data: JSON.parse(aux_phenomenological_constants_table),
                stretchH: 'all'
            });
            hot_phenomenological_constants_table.render();
        } else {//Si es la primera vez que se ingresa a la interfaz, cargar datos desde tabla phenomenological en BD
            var aux_phenomenological_data = [];
            $.get("{!! url('get_phenomenological_data_fines') !!}", {
                scenario_id: scenario_id
            }, function (data) {
                $.each(data, function (index, value) {
                    var temp_phenomenological_table = [];
                    temp_phenomenological_table.push(value.flow);
                    temp_phenomenological_table.push(value.k1);
                    temp_phenomenological_table.push(value.k2);
                    temp_phenomenological_table.push(value.dp_dl);
                    temp_phenomenological_table.push(value.k3);
                    temp_phenomenological_table.push(value.k4);
                    temp_phenomenological_table.push(value.k5);
                    temp_phenomenological_table.push(value.dp_dl2);
                    temp_phenomenological_table.push(value.sigma);
                    temp_phenomenological_table.push(value.k6);
                    temp_phenomenological_table.push(value.ab_2);
                    temp_phenomenological_table.push(value.ab);

                    aux_phenomenological_data.push(temp_phenomenological_table);
                });
                hot_phenomenological_constants_table.updateSettings({
                    data: aux_phenomenological_data
                });

                hot_phenomenological_constants_table.render();
            });

        }
        var aux_historical_data_table = $("#value_historical_data").val();
        var hot_historical_data_table = $('#historical_data_table').handsontable('getInstance');
        if (aux_historical_data_table !== '') {//Si se han ingresado datos en la tabla volver a cargarlos
            hot_historical_data_table.updateSettings({
                data: JSON.parse(aux_historical_data_table),
                stretchH: 'all'
            });
            hot_historical_data_table.render();
        } else {//Si es la primera vez que se ingresa a la interfaz, cargar datos de tabla historical en BD
            var aux_historical_data = [];
            $.get("{!! url('get_historical_data_fines') !!}", {
                scenario_id: scenario_id
            }, function (data) {
                $.each(data, function (index, value) {
                    var temp_historical_table = [];
                    temp_historical_table.push(value.date);
                    temp_historical_table.push(value.bopd);

                    aux_historical_data.push(temp_historical_table);
                });
                hot_historical_data_table.updateSettings({
                    data: aux_historical_data
                });

                hot_historical_data_table.render();
            });
        }

        var aux_pvt_table = $("#value_pvt_data").val();
        var hot_pvt_table = $('#pvt_table').handsontable('getInstance');
        if (aux_pvt_table !== '') {//Si se han ingresado datos en la tabla volver a cargarlos
            hot_pvt_table.updateSettings({
                data: JSON.parse(aux_pvt_table),
                stretchH: 'all'
            });
            hot_pvt_table.render();
        } else {//Si es la primera vez que se ingresa a la interfaz, cargar datos de tabla PVT segun fluido. Configurar tabla segun fluido.
            var type_flux = $("#type_of_suspension_flux").val();
            ;
            var pvt_table_advisor = $pvt_table.handsontable('getInstance');

            var pvt = [];
            if (type_flux == "oil") {
                var new_item = [];
                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Pressure [psi]";
                item_suspension_flux ["data"] = 0;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Oil Density [g/cc]";
                item_suspension_flux ["data"] = 1;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Oil Viscosity [cP]";
                item_suspension_flux ["data"] = 2;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Oil Volumetric Factor [bbl/BN]";
                item_suspension_flux ["data"] = 3;
                new_item.push(item_suspension_flux);

                var getValueFines = $.get("{{url('get_advisor_pvt_table_oil')}}", {scenario_id: scenario_id}, function (data) {
                    $.each(data, function (index, value) {
                        pvt.push(Object.values(value));
                    });
                    pvt_table_advisor.updateSettings({
                        columns: new_item,
                        data: pvt,
                        stretchH: 'all'
                    });
                });
            } else if (type_flux == "water") {
                var new_item = [];
                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Pressure [psi]";
                item_suspension_flux ["data"] = 0;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Water Volumetric Factor [bbl/BN]";
                item_suspension_flux ["data"] = 1;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Water Viscosity [cP]";
                item_suspension_flux ["data"] = 2;
                new_item.push(item_suspension_flux);

                var item_suspension_flux = {}
                item_suspension_flux ["type"] = "numeric";
                item_suspension_flux ["format"] = "0[.]0000000";
                item_suspension_flux ["title"] = "Water Density [g/cc]";
                item_suspension_flux ["data"] = 3;
                new_item.push(item_suspension_flux);

                var getValueFines = $.get("{{url('get_advisor_pvt_table_water')}}", {scenario_id: scenario_id}, function (data) {
                    $.each(data, function (index, value) {
                        pvt.push(Object.values(value));
                    });
                    pvt_table_advisor.updateSettings({
                        columns: new_item,
                        data: pvt,
                        stretchH: 'all'
                    });
                });
            }
            pvt_table_advisor.render();
        }
    });

    //Graficar PVT segun fluido escogido
    //Limpiar los datos para no graficar valores vacios
    $('.plot_pvt_table').on('click', function () {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = clean_table_data("pvt_table");

        var type_suspension_flux = $("#type_of_suspension_flux").val();

        if (type_suspension_flux == "water") {
            var pressure = [];
            var volumetric_water_factor = [];
            var water_viscosity = [];
            var water_density = [];
            for (var i = 0; i < data.length; i++) {
                pressure.push(parseFloat(data[i][0]));
                volumetric_water_factor.push(parseFloat(data[i][1]));
                water_viscosity.push(parseFloat(data[i][2]));
                water_density.push(parseFloat(data[i][3]));
            }

            $('#graphic_pvt_table').highcharts({
                title: {
                    text: 'Water PVT',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Pressure [psi]'
                    },
                    categories: pressure
                },
                yAxis: {
                    title: {
                        text: 'miuw, bw & denw'
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
                    name: 'Water Volumetric Factor [bbl/BN]',
                    data: volumetric_water_factor,
                    tooltip: {
                        valueSuffix: ''
                    }
                }, {
                    name: 'Water Viscosity [cP]',
                    data: water_viscosity,
                    tooltip: {
                        valueSuffix: ''
                    }
                }, {
                    name: 'Water Density [g/cc]',
                    data: water_density,
                    tooltip: {
                        valueSuffix: ''
                    }
                }]
            });
        } else if (type_suspension_flux == "oil") {
            var pressure = [];
            var oil_density = [];
            var oil_viscosity = [];
            var volumetric_oil_factor = [];
            for (var i = 0; i < data.length; i++) {
                pressure.push(parseFloat(data[i][0]));
                oil_density.push(parseFloat(data[i][1]));
                oil_viscosity.push(parseFloat(data[i][2]));
                volumetric_oil_factor.push(parseFloat(data[i][3]));
            }

            $('#graphic_pvt_table').highcharts({
                title: {
                    text: 'Oil PVT',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Pressure [psi]'
                    },
                    categories: pressure
                },
                yAxis: {
                    title: {
                        text: 'oil density, miuo & bo'
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
                    name: 'Oil Density [g/cc]',
                    data: oil_density,
                    tooltip: {
                        valueSuffix: ''
                    }
                }, {
                    name: 'Oil Viscosity [cP]',
                    data: oil_viscosity,
                    tooltip: {
                        valueSuffix: ''
                    }
                }, {
                    name: 'Oil Volumetric Factor [bbl/BN]',
                    data: volumetric_oil_factor,
                    tooltip: {
                        valueSuffix: ''
                    }
                }]
            });
        }
    });


    //Graficar historicos
    //Limpiar los datos para no graficar valores vacios
    $('.plot_historical_data_table').on('click', function () {
        data = clean_table_data("historical_data_table");
        historical_data = clean_table_data("historical_data_table");
        if (historical_data.length > 0) {
            var evt = window.event || arguments.callee.caller.arguments[0];
            evt.preventDefault();


            var date = [];
            var bopd = [];
            for (var i = 0; i < data.length; i++) {
                date.push(data[i][0]);
                bopd.push(parseFloat(data[i][1]));
            }

            $('#graphic_historical_data_table').highcharts({
                title: {
                    text: 'Historical Data',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Date'
                    },
                    dateFormat: 'YYYY-mm-dd',
                    type: 'datetime',
                    categories: date
                },
                yAxis: {
                    title: {
                        text: 'bopd'
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
                    name: 'BOPD [bbl/d]',
                    data: bopd,
                    tooltip: {
                        valueSuffix: ''
                    }
                }]
            });
        } else {
            alert('Please complete historical data table.');
        }
    });


    //Pronóstico --> Ligar botón a perform_production_projection() - Traer datos desde interfaz y agregar divs para dos gráficos.
    function perform_production_projection() {
        var hot_historical_data = $('#historical_data_table').handsontable('getInstance');
        var new_table = [];
        final_date = $("#final_date").val();
        table_div = "historical_data_table";
        historical_data = clean_table_data(table_div);
        historical_data_length = historical_data.length;

        //Cálculo cantidad de fechas - amount of dates
        final_date_historical_data = historical_data[historical_data_length - 1][0];
        final_date_splitted = final_date.split("-");
        final_date_historical_data_splitted = final_date_historical_data.split("-");

        amount_of_dates = Math.round(date_diff(new Date(parseInt(final_date_splitted[0]), parseInt(final_date_splitted[1]), parseInt(final_date_splitted[2])), new Date(parseInt(final_date_historical_data_splitted[0]), parseInt(final_date_historical_data_splitted[1]), parseInt(final_date_historical_data_splitted[2]))) * 0.0328767); //Convirtiendo a meses

        if (final_date && historical_data_length > 0) {

            //Devuelve pronóstico hiperbólico y exponencial para gráfico
            oil_production_projection = production_projection(table_div, "oil", final_date, amount_of_dates);

            oil_exponential_serie = [];
            oil_hyperbolic_serie = [];
            oil_original_data = [];
            //console.log(water_production_projection);
            for (var i = 0; i < historical_data.length; i++) 
            {
                oil_original_data.push([Date.parse(historical_data[i][0]), parseFloat(historical_data[i][1])]);
            }

            for (var i = 0; i < oil_production_projection[0][0].length; i++) 
            {
                oil_exponential_serie.push([oil_production_projection[0][0][i], parseFloat(oil_production_projection[0][1][i])]);
                oil_hyperbolic_serie.push([oil_production_projection[1][0][i], parseFloat(oil_production_projection[1][1][i])]);
            }


            oil_projection_series = [{
                name: "Oil Production Hyperbolic Projection",
                data: oil_hyperbolic_serie
            }, {name: "Oil Production Exponential Projection", data: oil_exponential_serie}, {
                name: "Oil Production",
                data: oil_original_data
            }];

            plot_projection_chart("oil_projection_chart", oil_projection_series, "BOPD [bbl/d]");

            if ($("#perform_historical_projection_oil").val() == "exponential") {
                for (i = 0; i < oil_exponential_serie.length; i++) {
                    var new_historical_table = [];

                    new_historical_table.push(date2str(new Date(parseInt(oil_exponential_serie[i][0])), "yyyy-MM-dd"));
                    new_historical_table.push(oil_exponential_serie[i][1]);
                    
                    new_table.push(new_historical_table);
                }
            } else if ($("#perform_historical_projection_oil").val() == "hyperbolic") {
                for (i = 0; i < oil_exponential_serie.length; i++) {
                    var new_historical_table = [];

                    new_historical_table.push(date2str(new Date(parseInt(oil_hyperbolic_serie[i][0])), "yyyy-MM-dd"));
                    new_historical_table.push(oil_hyperbolic_serie[i][1]);

                    new_table.push(new_historical_table);
                }
            }

            var final_historical = historical_data.concat(new_table);
            hot_historical_data.updateSettings({
                data: final_historical,
                stretchH: 'all'
            });
            hot_historical_data.render();
        } else {
            alert('Please complete all the information in "Historical Data" section.');
        }
    }

    function date2str(x, y) {
        var z = {
            M: x.getMonth() + 1,
            d: x.getDate(),
            h: x.getHours(),
            m: x.getMinutes(),
            s: x.getSeconds()
        };
        y = y.replace(/(M+|d+|h+|m+|s+)/g, function (v) {
            return ((v.length > 1 ? "0" : "") + eval('z.' + v.slice(-1))).slice(-2)
        });

        return y.replace(/(y+)/g, function (v) {
            return x.getFullYear().toString().slice(-v.length)
        });
    }

    function production_projection(div, fluid_type, final_date, amount_of_dates) {

        var historical_data = clean_table_data(div);

        var n = historical_data.length;

        var dq = new Array(n);
        var dt = new Array(n);
        var qprom = new Array(n);
        var de = new Array(n);
        var dhr = new Array(n);
        var b = new Array(n);
        var dh = new Array(n);
        var q = new Array(n);
        var lnq = new Array(n);
        var fecha = new Array(n);
        var original_dates = [];
        var dhp = 0;

        if (fluid_type === "oil") {
            q_data_index = 1; //Caudal en posición 1 de la tabla de históricos
        }
        else if (fluid_type === "water") {
            q_data_index = 2; //Caudal en posición 1 de la tabla de históricos 
        }

        /*
        Las fechas se tratan de esta manera ya que la conversión de string a date varía con base en el navegador. 
        Es más seguro implementarlo con enteros. YYYY/mm/dd -> los meses empiezan en 0. 
        */
        for (var i = 0; i < fecha.length; i++) {
            date_string = historical_data[i][0].split("-");
            original_dates.push(Date.UTC(parseInt(date_string[0]), parseInt(date_string[1]), parseInt(date_string[2])));
            fecha[i] = new Date(parseInt(date_string[0]), parseInt(date_string[1]) - 1, parseInt(date_string[2]));
            q[i] = historical_data[i][q_data_index];
        }
        //Se retornan para gráficos

        var original_q = q;
        var promedio = new Array(n);

        //Pronóstico 
        //Suavizada
        var fechaf = fecha[fecha.length - 1];
        var qf = q[q.length - 1];

        var a = n
        for (var i = 0; i < a; i++) {
            if (q[i] == 0) {
                n = n - 1;
                for (var j = i; j < n; j++) {
                    q[j] = q[j + 1];
                    fecha[j] = fecha[j + 1];
                }
                i = i - 1;
            }
            if (i == n) {
                break;
            }
        }

        var final_date_string = final_date.split("-"); //fecha final de pronóstico 
        var final_date = new Date(parseInt(final_date_string[0]), parseInt(final_date_string[1]), parseInt(final_date_string[2]));

        var nt = amount_of_dates;
        var deltatf = Math.round(date_diff(final_date, fechaf) / nt);

        var qe = new Array(nt);
        var t = new Array(nt);
        var qh = new Array(nt);
        for (var f = 3; f < a - 4; f++) {
            promedio[f] = (q[f - 3] + q[f - 2] + q[f - 1] + q[f + 3] + q[f + 2] + q[f + 1]) / 6;
            if (q[f] < (promedio[f] * 0.9) || q[f] > (promedio[f] * 1.1)) {
                n = n - 1;
                for (var j = f; j < n; j++) {
                    q[j] = q[j + 1];
                    fecha[j] = fecha[j + 1];
                }
                f = f - 1;
            }
            if (f == n) {
                break;
            }
        }

        //Qo
        if (qf < q[n - 1]) {
            q[n - 1] = qf;
        }

        //Deltas
        for (var i = 1; i < n; i++) {
            dq[i] = q[i] - q[i - 1];
            dt[i] = date_diff(fecha[i], fecha[i - 1]);
            qprom[i] = (q[i] + q[i - 1]) / 2;
        }

        //Exponencial
        var dep = 0;
        var j = 0;

        for (var i = 1; i < n; i++) {
            de[i] = -(dq[i] / qprom[i]) / dt[i];
            dep = dep + de[i];
        }

        var depp = dep / n;

        for (var i = 0; i < nt; i++) {
            t[i] = deltatf * (i + 1);
            if (depp > 0) {
                qe[i] = q[n - 1] * Math.exp(-depp * t[i]);
            }
            else {
                qe[i] = q[n - 1] * Math.exp(depp * t[i]);
            }
        }

        var projection_dates = [];
        for (var i = 1; i <= nt; i++) {
            fecha_aux = new Date(fechaf.getFullYear(), fechaf.getMonth(), fechaf.getDate());
            fecha_aux.setDate(fecha_aux.getDate() + (i * deltatf));
            original_dates.push(Date.UTC(parseInt(date_string[0]), parseInt(date_string[1]), parseInt(date_string[2])));
            projection_dates.push(Date.UTC(fecha_aux.getFullYear(), fecha_aux.getMonth() + 1, fecha_aux.getDate()));
        }

        var exponential_projection = [projection_dates, qe];

        //Hyperbolic projection
        var bp = 0;
        for (var i = 1; i < n; i++) {
            if (dq[i] != 0) {
                dhr[i] = -(qprom[i] / (dq[i] / dt[i]));
                dh[i] = 1 / dhr[i];
            }
            else {
                dhr[i] = 0;
                dh[i] = 0;
            }
            dhp = dhp + dh[i];
        }

        var dhp = dhp / (n - 1);
        for (var i = 2; i < n; i++) {
            b[i] = (dhr[i] - dhr[i - 1]) / dt[i];
            bp = bp + b[i];
        }

        var bpp = bp / (n - 1);

        if (fluid_type === "oil") {
            if (bpp <= 0.09) {
                bp = 0;
                h = 0;
                for (var i = 2; i < n; i++) {
                    b[i] = (dhr[i] - dhr[i - 1]) / dt[i];
                    if (b[i] <= 0.01) {
                        continue; //Cambiar
                    }
                    else {
                        bp = bp + b[i];
                        h = h + 1;
                    }
                }
                bpp = bp / (h);
            }
        }
        else if (fluid_type === "water") {
            if (bpp <= 0.09) {
                bp = 0;
                h = 0;
                for (var i = 2; i < n; i++) {
                    b[i] = (dhr[i] - dhr[i - 1]) / dt[i];
                    if (b[i] <= 0.01 || b[i] >= 1) {
                        continue; //Cambiar
                    }
                    else {
                        bp = bp + b[i];
                        h = h + 1;
                    }
                }
                bpp = bp / (h);
            }
        }


        var bppr = 1 / bpp;
        if (fluid_type === "oil") {
            for (var i = 0; i < nt; i++) {
                t[i] = deltatf * (i + 1);
                if (dh[n - 1] < 0) {
                    qh[i] = q[n - 1] / Math.pow((1 + (bpp * -dh[n - 1] * t[i])), bppr);
                }
                else {
                    qh[i] = q[n - 1] / Math.pow((1 + (bpp * dh[n - 1] * t[i])), bppr);
                }
            }
        }
        else if (fluid_type === "water") {
            for (var i = 0; i < nt; i++) {
                t[i] = deltatf * (i + 1);
                if (dh[n - 1] > 0) {
                    qh[i] = q[n - 1] / Math.pow((1 + (bpp * -dh[n - 1] * t[i])), bppr);
                }
                else {
                    qh[i] = q[n - 1] / Math.pow((1 + (bpp * dh[n - 1] * t[i])), bppr);
                }
            }
        }


        var hyperbolic_projection = [projection_dates, qh];
        var original_data = [original_dates, original_q];

        return [exponential_projection, hyperbolic_projection, original_data];
    }

    function plot_projection_chart(div, series_data, tittle, y_axis_tittle) {
        Highcharts.chart(div, {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: tittle
            },

            yAxis: {
                title: {
                    text: y_axis_tittle
                }
            },
            xAxis: {
                type: 'datetime',
                dateFormat: 'dd/mm/YYYY'
            },
            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    }
                }
            },

            series: series_data,

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    }

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

    function date_diff(date1, date2) {
        date1.setHours(0);
        date1.setMinutes(0, 0, 0);
        date2.setHours(0);
        date2.setMinutes(0, 0, 0);
        var datediff = Math.abs(date1.getTime() - date2.getTime()); // difference 
        return parseInt(datediff / (24 * 60 * 60 * 1000), 10); //Convert values days and return value      
    }

    //Initial deposited fines concentration - popup
    function calculate_initial_deposited_fines() {
        pi = 3.14159265359;

        //Traer desde interfaz
        length = $("#length").val();
        diameter = $("#diameter").val();
        porosity = $("#porosity").val();

        illite = $("#illite").val();
        kaolinite = $("#kaolinite").val();
        chlorite = $("#chlorite").val();
        esmectite = $("#emectite").val();
        total_clays = $("#total_amount_of_clays").val();
        quartz = $("#quartz").val(); //El de mineral information --> cambiemos information por data
        feldespar = $("#feldspar").val();

        if (length && diameter && porosity && illite && kaolinite && chlorite && esmectite && total_clays && quartz && feldespar) {
            //Cálculo de finos depositados

            vol = length * (Math.pow(diameter / 2), 2) * pi;
            vp = vol * porosity;
            vplug = vol - vp;

            fracilita = illite / 100;
            frackaolinita = kaolinite / 100;
            fracclorita = chlorite / 100;
            fracesmectita = esmectite / 100;
            fraccuarzo = quartz / 100;
            fracfeldespato = feldespar / 100;
            fractarcilla = total_clays / 100;


            parcilla = (fracilita * 2.8) + (frackaolinita * 2.6) + (fracclorita * 2.6) + (fracesmectita * 2);
            pmineral = (fraccuarzo * 2.63) + (fracfeldespato * 2.5) + (fractarcilla * parcilla);

            masatotal = pmineral * vplug;
            masafinos = masatotal * fractarcilla;
            depositos = masafinos * 0.02;
            finosdep = depositos / vplug;

            $("#initial_deposited_fines_concentration").val(finosdep);
            $("#fines_concentration_fluid").modal('hide');
        } else {
            alert('Please complete all the information in "Deposited Fines" section.');
        }

    }

</script>