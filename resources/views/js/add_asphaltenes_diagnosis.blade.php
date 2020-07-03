{{-- <script src="{{ asset('js/highcharts.js') }}"></script> --}}
{{-- <script src="http://code.highcharts.com/modules/exporting.js"></script> --}}
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script type="text/javascript">
    /*Llamar siempre la funcion import_tree para advisor. Ver archivo "Ayuda advisor"*/
    $(document).ready(function () {
        import_tree("Asphaltene precipitation", "asphaltenes_diagnosis");
    });

    $pvt_table = $('#pvt_table');
    $historical_table = $('#historical_table');
    $asphaltenes_table = $('#asphaltenes_table');
    $historical_projection_table = $("#historical_projection_table");

    //Graficar las dos tablas de historicos: bopd y asphaltenes
    //Limpiar los datos para no graficar valores vacios
    function plot_historical_table() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = clean_table_data("historical_table");
        if (data.length > 0) {
           var date = [];
            var bopd = [];
            var asphaltenes = [];
            for (var i = 0; i < data.length; i++) {
                date.push(data[i][0]);
                bopd.push(parseFloat(data[i][1]));
                asphaltenes.push(parseFloat(data[i][2]));
            }

            $('#graphic_historical_bopd_table').highcharts({
                title: {
                    text: 'Historical Data - BOPD',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Date [YYYY-MM-DD]'
                    },
                    dateFormat: 'YYYY-mm-dd',
                    type: 'datetime',
                    categories: date
                },
                yAxis: {
                    title: {
                        text: 'BOPD'
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
                    align: 'center',
                    verticalAlign: 'bottom',
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

            $('#graphic_historical_asphaltenes_table').highcharts({
                title: {
                    text: 'Historical Data - Asphaltenes',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Date [YYYY-MM-DD]'
                    },
                    dateFormat: 'YYYY-mm-dd',
                    type: 'datetime',
                    categories: date
                },
                yAxis: {
                    title: {
                        text: 'Asphaltenes'
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
                    align: 'center',
                    verticalAlign: 'bottom',
                    borderWidth: 0
                },
                series: [{
                    name: 'Asphaltenes [%wt]',
                    data: asphaltenes,
                    tooltip: {
                        valueSuffix: ''
                    }
                }]
            }); 
        } else {
            alert('Please complete historical data table.');
        }
    }

    //Graficar tabla PVT
    //Limpiar los datos para no graficar valores vacios
    function plot_pvt_table() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = order_matrix(clean_table_data("pvt_table"));

        if (data.length > 0) {
            var pressure = [];
            var density = [];
            var oil_viscosity = [];
            var oil_formation_volume_factor = [];
            for (var i = 0; i < data.length; i++) {
                pressure.push(parseFloat(data[i][0]));
                density.push([parseFloat(data[i][0]), parseFloat(data[i][1])]);
                oil_viscosity.push([parseFloat(data[i][0]), parseFloat(data[i][2])]);
                oil_formation_volume_factor.push([parseFloat(data[i][0]), parseFloat(data[i][3])]);
            }

            plot_line_chart("pvt_density_chart", density, "Oil Density [g/cc]", "Oil Density");
            plot_line_chart("pvt_oil_viscosity_chart", oil_viscosity, "Oil Viscosity [cp]", "Oil Viscosity");
            plot_line_chart("pvt_oil_volumetric_factor_chart", oil_formation_volume_factor, "Oil Volumetric Factor [bbl/STB]", "Oil Volumetric Factor");
        } else {
            alert('Please complete all the information in "PVT Data" section.');
        }
    }

    /* tabStep
  * After validating the current tab, it is changed to the next or previous tab depending on the
  * entry value
  * params {direction: string}
  */
  function tabStep(direction) {
    var tabToValidate = $(".nav.nav-tabs li.active a").attr("id");

    if (direction == "prev") {
      $(".nav.nav-tabs li.active").prev().children().click();
    } else {
      $(".nav.nav-tabs li.active").next().children().click();
    }

    $("#next_button").toggle($(".nav.nav-tabs li.active").next().is("li"));
    $("#prev_button").toggle($(".nav.nav-tabs li.active").prev().is("li"));
    $("#run_calc").toggle(!$(".nav.nav-tabs li.active").next().is("li"));
  }

  /* switchTab
  * Captures the tab clicking event to determine if a previous or next button has to be shown
  * and also the run button
  */
  function switchTab() {
    var event = window.event || arguments.callee.caller.arguments[0];
    var tabActiveElement = $(".nav.nav-tabs li.active");
    var nextPrevElement = $("#" + $(event.srcElement || event.originalTarget).attr('id')).parent();

    $("#next_button").toggle(nextPrevElement.next().is("li"));
    $("#prev_button").toggle(nextPrevElement.prev().is("li"));
    $("#run_calc").toggle(!nextPrevElement.next().is("li"));
  }

    //Función general para crear gráfico de líneas
    function plot_line_chart(div, data, data_name, title)
    {

      Highcharts.chart(div, {

        chart: {
            type: 'line',
            zoomType: 'x',
            inverted: false
        },
         title: {
             text: title,
             x: -20 //center
         },
         xAxis: {
          title: {
            text: 'Pressure [psi]'
          }
         },
         yAxis: {
             title: {
                 text: title
             },
             plotLines: [{
                 value: 0,
                 width: 1,
                 color: '#808080'
             }]
         },
         tooltip: {
             valueSuffix: 'k'
         },
         legend: {
             layout: 'vertical',
             align: 'center',
             verticalAlign: 'bottom',
             borderWidth: 0
         },
         series: [{
             name: data_name,
             data: data
         }]

      });
    }

    //Grafica de valores de tabla Asphaltenes Data
    //Limpiar los datos para no graficar valores vacios
    function plot_asphaltene_table() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = clean_table_data("asphaltenes_table");
        var pressure = [];
        var asphaltene_soluble_fraction = [];

        if (data.length > 0) {
            if (data) {
                for (var i = 0; i < data.length; i++) {
                    pressure.push(data[i][0]);
                    asphaltene_soluble_fraction.push(data[i][1]);
                }
            }


            $('#graphic_asphaltene_table').highcharts({
                title: {
                    text: 'Asphaltenes Data',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Pressure [psi]'
                    },
                    categories: pressure,
                    reversed: false
                },
                yAxis: {
                    title: {
                        text: 'Asphaltene Soluble Fraction [Fraction]'
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
                    borderWidth: 0
                },
                series: [{
                    name: 'Asphaltene Soluble Fraction [Fraction]',
                    data: data
                }
                ]
            });
        } else {
            alert('Please complete all the information in "Asphaltenes Data" section.');
        }
    }

    $(document).ready(function () {
        //Inicializar valores de tabla PVT
        $pvt_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 8,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            contextMenu: true,
            stretchH: 'all',
            colWidths: [180, 180, 180, 265],
            columns: [{
                title: "Pressure [psi]",
                data: 0,
                type: 'numeric',
                format: '0[.]0000000'
            },
                {
                    title: "Density [g/cc]",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                }, {
                    title: "Oil Viscosity [cp]",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Oil Formation Volumetric Factor [bbl/STB]",
                    data: 3,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
            ]

        });

        //Inicializar valores de tabla historicos
        $historical_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 8,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            contextMenu: true,
            stretchH: 'all',
            colWidths: [240, 240, 240],
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
                }, {
                    title: "Asphaltenes [%wt]",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
            ]

        });

        //Inicializar tabla de proyecciones
        $historical_projection_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 8,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            contextMenu: true,
            stretchH: 'all',
            colWidths: [240, 240, 240],

            columns: [{
                    title: "Date [YYYY-MM-DD]",
                    data: 0,
                    type: 'date',
                    dateFormat: 'YYYY-MM-DD',
                    readOnly: true
                },
                {
                    title: "BOPD [bbl/d]",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000',
                    readOnly: true
                },
                {
                    title: "Asphaltenes [%wt]",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000',
                    readOnly: true
                }
            ]
        });

        //Inicializar valores de tabla de asfaltenos
        $asphaltenes_table.handsontable({
            height: 200,
            colHeaders: true,
            minSpareRows: 8,
            viewportColumnRenderingOffset: 10,
            rowHeaders: true,
            contextMenu: true,
            stretchH: 'all',
            colWidths: [360, 360],
            columns: [{
                    title: "Pressure [psi]",
                    data: 0,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Asphaltene Soluble Fraction [Fraction]",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
            ]

        });


        //Antes de guardar los datos se deben limpiar y validar los datos de las tablas
        $('.save_table').on('click', function () {
            //Loading
            $("#loading_icon").show();
            
            pvt_table = order_matrix(clean_table_data("pvt_table"));
            historical_table = clean_table_data("historical_table");
            historical_projection_table = clean_table_data("historical_projection_table");
            asphaltenes_table = clean_table_data("asphaltenes_table");

            //Validar datos tabla PVT (Mayores a cero)
            validate_pvt_data(pvt_table);

            $("#value_pvt_table").val(JSON.stringify(pvt_table));
            $("#value_historical_table").val(JSON.stringify(historical_table));
            $("#value_historical_projection_data").val(JSON.stringify(historical_projection_table));
            $("#value_asphaltenes_table").val(JSON.stringify(asphaltenes_table));

            validate_table([pvt_table, historical_table, historical_projection_table, asphaltenes_table], ["pvt table", "historical table", "asphaltenes table"], [["numeric", "numeric", "numeric", "numeric"], ["text", "numeric", "numeric"], ["numeric", "numeric"]]);
        });

        /* Antes de guardar los datos se deben limpiar y validar los datos de las tablas */
        $('.save_table_wr').on('click', function () {
            /* Loading */
            $("#loading_icon").show();
            
            pvt_table = order_matrix(clean_table_data("pvt_table"));
            historical_table = clean_table_data("historical_table");
            historical_projection_table = clean_table_data("historical_projection_table");
            asphaltenes_table = clean_table_data("asphaltenes_table");

            /* Validar datos tabla PVT (Mayores a cero) */
            validate_pvt_data(pvt_table);

            $("#value_pvt_table").val(JSON.stringify(pvt_table));
            $("#value_historical_table").val(JSON.stringify(historical_table));
            $("#value_historical_projection_data").val(JSON.stringify(historical_projection_table));
            $("#value_asphaltenes_table").val(JSON.stringify(asphaltenes_table));
        });

        //Actualizar elementos de Production Projection al cambiar el select perform_historical_projection_oil
        $('#perform_historical_projection_oil').bind('init change',function(){
            if($(this).val() == 'without'){
                $("#final_dates").hide();
                $("#historical_projection_table").hide();
                $("#oil_projection_chart").hide();
            }else{
                $("#final_dates").show();
                $("#historical_projection_table").show();
                $("#oil_projection_chart").show();
            }
        }).trigger('init');

        var scenario_id = <?php
            if ($scenary) {
                echo json_encode($scenary->id);
            } else {
                echo json_encode(null);
            }
            ?>;

        var hot_asphaltenes_table = $('#asphaltenes_table').handsontable('getInstance');
        var hot_pvt_table = $('#pvt_table').handsontable('getInstance');
        var hot_historical_table = $('#historical_table').handsontable('getInstance');


        //Recordar tablas

        var aux_hot_asphaltenes_table = $("#value_asphaltenes_table").val();

        if (aux_hot_asphaltenes_table !== '') {//Si se han ingresado datos en la tabla volver a cargarlos
            hot_asphaltenes_table.updateSettings({
                data: JSON.parse(aux_hot_asphaltenes_table),
                stretchH: 'all'
            });
            hot_asphaltenes_table.render();
        } else {//Si no se han ingresado cargar los de la tabla solid_a
            if (scenario_id) {
                var table_solid_a = [];
                $.get("{!! url('get_solid_a_results') !!}", {
                    scenario_id: scenario_id
                }, function (data) {
                    $.each(data, function (index, value) {
                        var solid_a = [];
                        solid_a.push(value.pressure);
                        solid_a.push(value.a);

                        table_solid_a.push(solid_a);
                    });
                    table_solid_a.reverse();
                    hot_asphaltenes_table.updateSettings({
                        data: table_solid_a
                    });

                    hot_asphaltenes_table.render();
                });
            }
        }


        var aux_hot_pvt_table = $("#value_pvt_table").val();

        if (aux_hot_pvt_table !== '') {//En caso de haber ingresado valores a la tabla, volver a cargarlos
            hot_pvt_table.updateSettings({
                data: JSON.parse(aux_hot_pvt_table),
                stretchH: 'all'
            });
            hot_pvt_table.render();
        }


        var aux_hot_historical_table = $("#value_historical_table").val();

        if (aux_hot_historical_table !== '') {//En caso de haber ingresado valores a la tabla, volver a cargarlos
            hot_historical_table.updateSettings({
                data: JSON.parse(aux_hot_historical_table),
                stretchH: 'all'
            });
            hot_historical_table.render();
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

    //Pronóstico --> Ligar botón a perform_production_projection() - Traer datos desde interfaz y agregar divs para dos gráficos.
    function perform_production_projection() 
    {
        var hot_historical_data = $('#historical_table').handsontable('getInstance');
        var new_table = [];
        final_date = $("#final_date").val();
        table_div = "historical_table";
        historical_data = clean_table_data(table_div);

        historical_data_length = historical_data.length;

        if (historical_data_length > 0) { 

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

                for (var i = 0; i < historical_data.length; i++) {
                    oil_original_data.push([Date.parse(historical_data[i][0]), parseFloat(historical_data[i][1])]);
                }
                for (var i = 0; i < oil_production_projection[0][0].length; i++) {
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
                        new_historical_table.push(historical_data[historical_data_length - 1][2]);

                        new_table.push(new_historical_table);
                    }
                } else if ($("#perform_historical_projection_oil").val() == "hyperbolic") {
                    for (i = 0; i < oil_exponential_serie.length; i++) {
                        var new_historical_table = [];

                        new_historical_table.push(date2str(new Date(parseInt(oil_hyperbolic_serie[i][0])), "yyyy-MM-dd"));
                        new_historical_table.push(oil_hyperbolic_serie[i][1]);
                        new_historical_table.push(historical_data[historical_data_length - 1][2]);

                        new_table.push(new_historical_table);
                    }
                }

                if($('#perform_historical_projection_oil').val() != 'without'){ // cuando se escogió exponencial o hiperbólica llenar value_historical_data para mandar al backend
                    var hot_historical_data = $('#historical_projection_table').handsontable('getInstance');

                    hot_historical_data.updateSettings({
                        data: new_table,
                        stretchH: 'all'
                    });
                    hot_historical_data.render();
                    
                }else{  
                    historical_data = clean_table_data("historical_data_table");
                    $("#value_historical_data").val(JSON.stringify(historical_data));
                }
            } else {
                alert('Please complete all the information in "Historical Data" section.');
            }
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
                dateFormat: 'YYYY-mm-dd',
                type: 'datetime'
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

    function date_diff(date1, date2) {
        date1.setHours(0);
        date1.setMinutes(0, 0, 0);
        date2.setHours(0);
        date2.setMinutes(0, 0, 0);
        var datediff = Math.abs(date1.getTime() - date2.getTime()); // difference 
        return parseInt(datediff / (24 * 60 * 60 * 1000), 10); //Convert values days and return value      
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

    function validate_pvt_data(pvt_data)
    {
        var pvt_data_range_flag = 1;
        for (var i = 0; i < pvt_data.length; i++) 
        {
            if(pvt_data[i][0] < 0 || pvt_data[i][1] < 0 || pvt_data[i][2] < 0 || pvt_data[i][3] < 0)
            {
                pvt_data_range_flag = 0;
            }
        }

        $("#pvt_data_range_flag").val(pvt_data_range_flag);
    }

    function validate_asphaltenes_data(asphaltenes_data)
    {
        var asphaltenes_data_range_flag = 1;
        for (var i = 0; i < asphaltenes_data.length; i++) 
        {
            if(asphaltenes_data[i][0] < 0 || asphaltenes_data[i][1] < 0)
            {
                asphaltenes_data_range_flag = 0;
            }
        }

        $("#asphaltenes_data_range_flag").val(asphaltenes_data_range_flag);
    }

</script>