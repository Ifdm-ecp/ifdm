<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

    //Funcion para crear grafico de presion de reservorio
    function plot_reservoirPressure_Interval() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = $("#reservoirPressure_table").handsontable('getData');
        var date = [];
        var value = [];

        for (var i = 0; i < data.length; i++) {
            date.push(data[i][0]);
            value.push(data[i][1]);
        }
        date.pop();
        value.pop();
        $('#reservoirPressure_Interval').highcharts({
            title: {
                text: 'Reservoir Pressure',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Date'
                },
                categories: date
            },
            yAxis: {
                title: {
                    text: 'Value'
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
                name: 'Pressure [psi]',
                data: value
            }]
        });
    }

    //Funcion para crear grafico de datos water-oil
    function plot_waterOil_Formation_Well() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = $("#excel").handsontable('getData');
        var sw = [];
        var krw = [];
        var kro = [];
        var pcwo = [];

        for (var i = 0; i < data.length; i++) {
            sw.push(data[i][0]);
            krw.push(data[i][1]);
            kro.push(data[i][2]);
            pcwo.push(data[i][3]);
        }
        sw.pop();
        krw.pop();
        kro.pop();
        pcwo.pop();
        $('#waterOil_Interval_Well_g').highcharts({
            title: {
                text: 'Water-Oil Kr\'s',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Sw'
                },
                categories: sw
            },
            yAxis: {
                title: {
                    text: 'Krw,Kro & Pcwo'
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
                name: 'Krw',
                data: krw
            }, {
                name: 'Kro',
                data: kro
            }, {
                name: 'Pcwo [psi]',
                data: pcwo
            }]
        });
    }

    //Funcion para crear grafico para datos gas-oil
    function plot_gasOil_Formation_Well() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = $("#excel2").handsontable('getData');
        var sg = [];
        var krg = [];
        var krog = [];
        var pcgl = [];
        for (var i = 0; i < data.length; i++) {
            sg.push(data[i][0]);
            krg.push(data[i][1]);
            krog.push(data[i][2]);
            pcgl.push(data[i][3]);
        }
        sg.pop();
        krg.pop();
        krog.pop();
        pcgl.pop();
        $('#gasOil_Formation_Well_g').highcharts({
            title: {
                text: 'Gas-Oil Kr\'s',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                title: {
                    text: 'Sg'
                },
                categories: sg
            },
            yAxis: {
                title: {
                    text: 'Krg,Krog & Pcgl'
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
                name: 'Krg',
                data: krg
            }, {
                name: 'Krog',
                data: krog
            }, {
                name: 'Pcgl [psi]',
                data: pcgl
            }]
        });
    }

    //Funcion para mostrar y ocultar contenido de botones de tablas
    $(function() {
        $("#myModal").modal('show');
        $('.toggle').click(function(event) {
            event.preventDefault();
            var target = $(this).attr('href');
            $(target).toggleClass('hidden show');
        });
    });

    $(document).ready(function() {
        $("#myModal").modal('show');
    });

    var basinf;
    var camposf = [];
    var formacionf;
    var parametrof;
    $(document).ready(function() {
        //Cambiar valores de select anidados de acuerdo a opcion escogida
        $("#Basin2").change(function(e) {
            var cuenca = $('#Basin2').val();
            basinf = cuenca;
            $.get("{{url('formacion')}}", {
                cuenca: cuenca
            },
            function(data) {
                $("#top").empty();
                $("#netpay").empty();
                $("#porosity").empty();
                $("#permeability").empty();
                $("#reservoir").empty();
                $.each(data, function(index, value) {
                    document.getElementById("top").value = value.top;
                    document.getElementById("netpay").value = value.netpay;
                    document.getElementById("porosity").value = value.porosidad;
                    document.getElementById("permeability").value = value.permeabilidad;
                    document.getElementById("reservoir").value = value.presion_reservorio;
                });

                $.get("intervalos/"+cuenca, {
                    formacion: cuenca
                },
                function(data) {

                    $("#nameInterval").empty();
                    $.each(data, function(index, value) {
                        $("#nameInterval").append('<option value="' + value.nombre + '">' + value.nombre + '</option>');
                    });
                    $("#nameInterval").selectpicker('refresh');
                    $('#nameInterval').selectpicker('val', '');
                });
            });
        });
    });



    $(document).ready(function() {
        $("#myModal").modal('show');
    });

    $(document).ready(function() {
        $('.chevron_toggleable').on('click', function() {
            $(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        });

        //Tooltips
        $("#myModal").modal('show');
        $("#top").tooltip({
            title: 'Formation Top.'
        });
        $("#netpay").tooltip({
            title: 'Thickness Of Rock That Can Deliver Hydrocarbons To The Well.'
        });
        $("#permeability").tooltip({
            title: 'Absolute Permeability.'
        });
        $("#coorB1").tooltip({
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84).'
        });
        $('#Well > option[value="{{ $pozo->id }}"]').attr('selected', 'selected');

        //Creacion de divs para alertas de valores fuera de rangos normales
        top_alert = function() {}
        top_alert.warning = function(message) {
            $('#alert_top').html('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span>' + message + '</span></div>')
        }

        netpay_alert = function() {}
        netpay_alert.warning = function(message) {
            $('#alert_netpay').html('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span>' + message + '</span></div>')
        }

        porosity_alert = function() {}
        porosity_alert.warning = function(message) {
            $('#alert_porosity').html('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span>' + message + '</span></div>')
        }

        permeability_alert = function() {}
        permeability_alert.warning = function(message) {
            $('#alert_permeability').html('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span>' + message + '</span></div>')
        }

        reservoir_alert = function() {}
        reservoir_alert.warning = function(message) {
            $('#alert_reservoir').html('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span>' + message + '</span></div>')
        }
    });

    //Inicializacion de variables para tablas
    var maxed = false,
    resizeTimeout, availableWidth, availableHeight, $window = $(window),
    $excel = $('#excel');

    var maxed2 = false,
    resizeTimeout, availableWidth, availableHeight, $window = $(window),
    $excel2 = $('#excel2');

    var maxed1 = false,
    resizeTimeout, availableWidth, availableHeight, $window = $(window),
    $reservoirPressure_table = $('#reservoirPressure_table');

    $(document).ready(function() {
        //Creacion de tabla presion de reservorio
        data_reservoir = $("#reservoirPressure").val();
        if (data_reservoir === '') {
            data_reservoir = [
            [, , , ],
            [, , , ]
            ];
        } else {
            data_reservoir = JSON.parse(data_reservoir);
        }

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $reservoirPressure_table.handsontable({
            data: data_reservoir,
            colWidths: [240, 240, 240],
            rowHeaders: true,
            columns: [

            {
                title: "Date",
                data: 0,
                type: 'date',
                dateFormat: 'YYYY/MM/DD',
                correctFormat: true
            },
            {
                title: "Pressure [psi]",
                data: 1,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Comment",
                data: 2,
                type: 'text'
            },
            ],

            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed1 && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed1 ? availableWidth : 1600;
            },
            height: function() {
                if (maxed1 && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed1 ? availableHeight : 300;
            }
        });


        data_WO = $("#RelP").val();
        if (data_WO === '') {
            data_WO = [
            [, , , , ],
            [, , , , ]
            ];
        } else {
            data_WO = JSON.parse(data_WO);
        }

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel.handsontable({
            data: data_WO,
            colWidths: [80, 80, 80, 80],
            rowHeaders: true,
            columns: [

            {
                title: "Sw",
                data: 0,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Krw",
                data: 1,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Kro",
                data: 2,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Pcwo [psi]",
                data: 3,
                type: 'numeric',
                format: '0[.]0000000'
            },
            ],

            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 1600;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 300;
            }
        });




        data_GL = $("#RelP2").val();
        if (data_GL === '') {
            data_GL = [
            [, , , , ],
            [, , , , ]
            ];
        } else {
            data_GL = JSON.parse(data_GL);
        }


        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel2.handsontable({
            data: data_GL,
            colWidths: [80, 80, 80, 80],
            rowHeaders: true,
            columns: [

            {
                title: "Sg",
                data: 0,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Krg",
                data: 1,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Krl",
                data: 2,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Pcgl [psi]",
                data: 3,
                type: 'numeric',
                format: '0[.]0000000'
            }
            ],
            minSpareRows: 1,
            width: function() {
                if (maxed2 && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed2 ? availableWidth : 1600;
            },
            height: function() {
                if (maxed2 && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed2 ? availableHeight : 300;
            }
        });

    });

    //Funcion para validar valores del formulario. Saca ventana de error o warning
    function Mostrar() {
        var valid_reservoir = true;
        var valid_GL = true;
        var valid_WO = true;
        var valid_sw = true;
        var valid_krw = true;
        var valid_kro = true;
        var valid_sg = true;
        var valid_krg = true;
        var valid_krl = true;
        var valid_pcwo = true;
        var valid_pcgl = true;
        var valid_pressure = true;

        datos_reservoir = $reservoirPressure_table.handsontable('getData');
        $("#reservoirPressure").val(JSON.stringify(datos_reservoir));

        datos_WO = $excel.handsontable('getData');
        $("#RelP").val(JSON.stringify(datos_WO));

        datos_GL = $excel2.handsontable('getData');
        $("#RelP2").val(JSON.stringify(datos_GL));

        //Reservoir pressure data
        var reservoirP = $reservoirPressure_table.handsontable('getData');
        for (var i = 0; i < reservoirP.length; i++) {
            d0 = reservoirP[i][0];
            d1 = reservoirP[i][1];
            d2 = reservoirP[i][2];

            if ((isNaN(d1) && (d1 != null && d1 != ""))) {

                valid_reservoir = false;
                mensaje_reservoir = "Check your Reservoir Pressure data, there's must be numeric...";
            }
            if ((d1 < 0)) {
                valid_pressure = false;
                mensaje_pressure = "Reservoir Pressure must be greather than 0.";
            }
        }

        for (var i = 0; i < reservoirP.length; i++) {
            d0 = reservoirP[i][0];
            d1 = reservoirP[i][1];
            d2 = reservoirP[i][2];
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "") {
                    valid_reservoir = false;
                    mensaje_reservoir = "Check your Reservoir Pressure data, there's nothing there...";
                }
            }
        }


        //G-L
        var coordinates = $excel2.handsontable('getData');
        for (var i = 0; i < coordinates.length; i++) {
            d0 = coordinates[i][0];
            d1 = coordinates[i][1];
            d2 = coordinates[i][2];
            d3 = coordinates[i][3];


            if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != "")) || (isNaN(d2) && (d2 != null && d2 != "")) || (isNaN(d3) && (d3 != null && d3 != ""))) {
                valid_GL = false;
                mensaje_GL = "Check your Gas-Liquid data, there's must be numeric...";
            }
        }

        for (var i = 0; i < coordinates.length; i++) {
            d0 = coordinates[i][0];
            d1 = coordinates[i][1];
            d2 = coordinates[i][2];
            d3 = coordinates[i][3];
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "") {
                    valid_GL = false;
                    mensaje_GL = "Check your Gas-Liquid data, there's nothing there...";
                }
            }
            if ((d0 < 0) || (d0 > 1)) {
                valid_sg = false;
                mensaje_sg = "Gas-Oil Sg must be between 0 and 1.";
            }
            if ((d1 < 0) || (d1 > 1)) {
                valid_krg = false;
                mensaje_krg = "Gas-Oil Krg must be between 0 and 1.";
            }
            if ((d2 < 0) || (d2 > 1)) {
                valid_krl = false;
                mensaje_krl = "Gas-Oil Krl must be between 0 and 1.";
            }
            if ((d3 < 0)) {
                valid_pcgl = false;
                mensaje_pcgl = "Gas-Oil Pcgl must be greather than 0.";
            }
        }


        //W-O
        var coordinates = $excel.handsontable('getData');
        for (var i = 0; i < coordinates.length; i++) {
            d0 = coordinates[i][0];
            d1 = coordinates[i][1];
            d2 = coordinates[i][2];
            d3 = coordinates[i][3];


            if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != "")) || (isNaN(d2) && (d2 != null && d2 != "")) || (isNaN(d3) && (d3 != null && d3 != ""))) {
                valid_WO = false;
                mensaje_WO = "Check your Water-Oil data, there's must be numeric...";
            }
        }

        for (var i = 0; i < coordinates.length; i++) {
            d0 = coordinates[i][0];
            d1 = coordinates[i][1];
            d2 = coordinates[i][2];
            d3 = coordinates[i][3];
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "") {
                    valid_WO = false;
                    mensaje_WO = "Check your Water-Oil data, there's nothing there...";
                }
            }
            if ((d0 < 0) || (d0 > 1)) {
                valid_sw = false;
                mensaje_sw = "Water-Oil Sw must be between 0 and 1.";
            }
            if ((d1 < 0) || (d1 > 1)) {
                valid_krw = false;
                mensaje_krw = "Water-Oil Krw must be between 0 and 1.";
            }
            if ((d2 < 0) || (d2 > 1)) {
                valid_kro = false;
                mensaje_kro = "Water-Oil Kro must be between 0 and 1.";
            }
            if ((d3 < 0)) {
                valid_pcwo = false;
                mensaje_pcwo = "Water-Oil Pcwo must be greather than 0.";
            }
        }

        var evt = window.event || arguments.callee.caller.arguments[0];
        var outside = 0;

        //Validacion de valores con rangos normales. Mostrar div de alerta en caso de que no cumpla
        if (document.getElementById("top").value > 100000) {
            outside = 1;
            $('#alert_top').show();
            top_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_top").hide();
        }

        if (document.getElementById("netpay").value > 10000) {
            outside = 1;
            $('#alert_netpay').show();
            netpay_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_netpay").hide();
        }

        if (document.getElementById("porosity").value > 100) {
            outside = 1;
            $('#alert_porosity').show();
            porosity_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_porosity").hide();
        }

        if (document.getElementById("permeability").value > 1000000) {
            outside = 1;
            $('#alert_permeability').show();
            permeability_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_permeability").hide();
        }

        if (document.getElementById("reservoir").value > 20000) {
            outside = 1;
            $('#alert_top').show();
            reservoir_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_reservoir").hide();
        }

        var complete = 0;

        //Cambiar color de input y bordes en caso de que el valor este fuera de los rangos normales
        if ($('#reservoir').val() == '') {
            document.getElementById('reservoir-addon').style.backgroundColor = "#F2DEDE";
            document.getElementById('reservoir-addon').style.borderColor = "#A94442";
            document.getElementById('reservoir').style.borderColor = "#A94442";
            var complete = 1;
        } else {
            document.getElementById('reservoir-addon').style.backgroundColor = "#EEEEEE";
            document.getElementById('reservoir-addon').style.borderColor = "#CCCCCC";
            document.getElementById('reservoir').style.borderColor = "#CCCCCC";
        }

        if ($('#permeability').val() == '') {
            document.getElementById('permeability-addon').style.backgroundColor = "#F2DEDE";
            document.getElementById('permeability-addon').style.borderColor = "#A94442";
            document.getElementById('permeability').style.borderColor = "#A94442";
            var complete = 1;
        } else {
            document.getElementById('permeability-addon').style.backgroundColor = "#EEEEEE";
            document.getElementById('permeability-addon').style.borderColor = "#CCCCCC";
            document.getElementById('permeability').style.borderColor = "#CCCCCC";
        }

        if ($('#porosity').val() == '') {
            document.getElementById('porosity-addon').style.backgroundColor = "#F2DEDE";
            document.getElementById('porosity-addon').style.borderColor = "#A94442";
            document.getElementById('porosity').style.borderColor = "#A94442";
            var complete = 1;
        } else {
            document.getElementById('porosity-addon').style.backgroundColor = "#EEEEEE";
            document.getElementById('porosity-addon').style.borderColor = "#CCCCCC";
            document.getElementById('porosity').style.borderColor = "#CCCCCC";
        }

        if ($('#netpay').val() == '') {
            document.getElementById('netpay-addon').style.backgroundColor = "#F2DEDE";
            document.getElementById('netpay-addon').style.borderColor = "#A94442";
            document.getElementById('netpay').style.borderColor = "#A94442";
            var complete = 1;
        } else {
            document.getElementById('netpay-addon').style.backgroundColor = "#EEEEEE";
            document.getElementById('netpay-addon').style.borderColor = "#CCCCCC";
            document.getElementById('netpay').style.borderColor = "#CCCCCC";
        }

        if ($('#top').val() == '') {
            document.getElementById('top-addon').style.backgroundColor = "#F2DEDE";
            document.getElementById('top-addon').style.borderColor = "#A94442";
            document.getElementById('top').style.borderColor = "#A94442";
            var complete = 1;
        } else {
            document.getElementById('top-addon').style.backgroundColor = "#EEEEEE";
            document.getElementById('top-addon').style.borderColor = "#CCCCCC";
            document.getElementById('top').style.borderColor = "#CCCCCC";
        }

        //Creacion de variables para mensajes de error
        if (!valid_GL || !valid_WO || !valid_reservoir || !valid_sw || !valid_krw || !valid_kro || !valid_sg || !valid_krg || !valid_krl || !valid_pcwo || !valid_pcgl || !valid_pressure) {
            var mensaje = "";
            if (!valid_GL) {
                mensaje = mensaje + "\n" + mensaje_GL;
            }
            if (!valid_WO) {
                mensaje = mensaje + "\n" + mensaje_WO;
            }
            if (!valid_reservoir) {
                mensaje = mensaje + "\n" + mensaje_reservoir;
            }
            if (!valid_sw) {
                mensaje = mensaje + "\n" + mensaje_sw;
            }
            if (!valid_krw) {
                mensaje = mensaje + "\n" + mensaje_krw;
            }
            if (!valid_kro) {
                mensaje = mensaje + "\n" + mensaje_kro;
            }
            if (!valid_pcwo) {
                mensaje = mensaje + "\n" + mensaje_pcwo;
            }
            if (!valid_sg) {
                mensaje = mensaje + "\n" + mensaje_sg;
            }
            if (!valid_krg) {
                mensaje = mensaje + "\n" + mensaje_krg;
            }
            if (!valid_krl) {
                mensaje = mensaje + "\n" + mensaje_krl;
            }
            if (!valid_pcgl) {
                mensaje = mensaje + "\n" + mensaje_pcgl;
            }
            if (!valid_pressure) {
                mensaje = mensaje + "\n" + mensaje_pressure;
            }
            evt.preventDefault();
            alert(mensaje);
        } else {
            //Crear y mostrar ventana modal en caso de que exista advertencias de valores fuera de rangos normales
            //El usuario decide si desea guardar o modificar algun valor
            if (outside == 1) {

                $("#confirmWar").modal('show');
                $('#confirmWar').on('show.bs.modal', function(e) {
                    $message = $(e.relatedTarget).attr('data-message');
                    $(this).find('.modal-body p').text($message);
                    $title = $(e.relatedTarget).attr('data-title');
                    $(this).find('.modal-title').text($title);

                    var form = $(e.relatedTarget).closest('form');
                    $(this).find('.modal-footer #confirm').data('form', form);
                });
                evt.preventDefault();


                $('#confirmWar').find('.modal-footer #confirm').on('click', function() {
                    $('form#form').submit();
                });

            } else if (complete == 1) {

                $("#confirmDelete").modal('show');
                $('#confirmDelete').on('show.bs.modal', function(e) {
                    $message = $(e.relatedTarget).attr('data-message');
                    $(this).find('.modal-body p').text($message);
                    $title = $(e.relatedTarget).attr('data-title');
                    $(this).find('.modal-title').text($title);

                    var form = $(e.relatedTarget).closest('form');
                    $(this).find('.modal-footer #confirm').data('form', form);
                });
                evt.preventDefault();
                $('#confirmDelete').find('.modal-footer #confirm').on('click', function() {
                    $('form#form').submit();
                });

            } else {
                $('form#form').submit();
            }
        }
    }
</script>