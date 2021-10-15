
<script type="text/javascript">

    //Crear grafico para presiones de reservorio
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
                    text: ''
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

    //Crear grafico para datos water-oil
    function plot_waterOil_Interval() {
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
        $('#waterOil_Interval_g').highcharts({
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
                    text: ''
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

    //Crear grafico para datos gas-oil
    function plot_gasOil_Interval() {
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
        $('#gasOil_Interval_g').highcharts({
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
                    text: ''
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

    //Guardar datos de select para volver a cargar en caso de que salga error en el formulario
    window.onbeforeunload = function() {
        var a = $('#field').val();
        localStorage.setItem('field', $('#field').val());
        var b = $('#Well').val();
        localStorage.setItem('Well', $('#Well').val());
    }

    //Cargar datos que se guardaron en caso de salir error en el formulario
    window.onload = function() {
        var cuenca = $('#basin').val();
        var campo = localStorage.getItem('field');
        $.get("{{url('campos')}}", {
                cuenca: cuenca
            },
            function(data) {
                $("#field").empty();
                $.each(data, function(index, value) {
                    $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = "{{ 'xxx'}}";
                k = k.replace('xxx', campo);
                $('#field').selectpicker('val', k);
                $("#field").selectpicker('refresh');
            }
        );


        var campo2 = localStorage.getItem('field');
        var pozo = localStorage.getItem('Well');
        $.get("{{url('wellbyfield')}}", {
                field: campo2
            },
            function(data) {
                $("#Well").empty();
                $.each(data, function(index, value) {
                    $("#Well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = "{{ 'xxx'}}";
                k = k.replace('xxx', pozo);
                $('#Well').selectpicker('val', k);
                $("#Well").selectpicker('refresh');
            }
        );
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
        //Cargar valores de select anidados de acuerdo a la opcion escogida
        $("#basin").change(function(e) {
            var basin = $('#basin').val();
            $.get("{{url('fieldbybasin')}}", {
                    basin: basin
                },
                function(data) {
                    $("#field").empty();
                    console.log(data);
                    $.each(data, function(index, value) {
                        $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field").selectpicker('refresh');
                    $('#field').selectpicker('val', '');
                });
        });

        $("#field").change(function(e) {
            var field = $('#field').val();
            $.get("{{url('wellbyfield')}}", {
                    field: field
                },
                function(data) {
                    $("#Well").empty();
                    $.each(data, function(index, value) {
                        $("#Well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#Well").selectpicker('refresh');
                    $('#Well').selectpicker('val', '');
                });

            $.get("{{url('formationbyfield')}}", {
                field: field
            },
            function(data) {
                $("#formacionName").empty();
                $.each(data, function(index, value) {
                    $("#formacionName").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#formacionName").selectpicker('refresh');
                $('#formacionName').selectpicker('val', '');
            });
        });


        $("#formacionName").change(function(e) {
            var formacion = $('#formacionName').val();
            
            
            $.get("/intervalos/"+formacion, {
                    formacion: formacion
                },
                function(data) {

                    $("#nameInterval").empty();
                    $.each(data, function(index, value) {
                        $("#nameInterval").append('<option value="' + value.nombre + '">' + value.nombre + '</option>');
                    });
                    $("#nameInterval").selectpicker('refresh');
                    $('#nameInterval').selectpicker('val', '');
                });
            
            $.get("{{url('formacion')}}", {
                formacion: formacion
            },
            function(data){
                $("#porosity").empty();
                $("#permeability").empty();
                $("#reservoir").empty();
            
                    
                    document.getElementById("porosity").value = data.porosidad;
                    document.getElementById("permeability").value = data.permeabilidad;
                    document.getElementById("reservoir").value = data.presion_reservorio;
             
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

        $("#myModal").modal('show');

        //Tooltips
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


        //Crear div de alerta para mostrar en caso de que un valor se salga de rangos correctos.
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

        //Tabla presion de reservorio
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
                    dateFormat: 'yy/mm/dd',
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


        data_aux = $("#RelP").val();
        if (data_aux === '') {
            data_aux = [
                [, ],
                [, ]
            ];
        } else {
            data_aux = JSON.parse(data_aux);
        }

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel.handsontable({
            data: data_aux,
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

    //Validar valores de tablas y rangos de inputs
    function Mostrar() {
        var valid_reservoir = true;
        var valid_GL = true;
        var valid_WO = true;
        var valid_GL0 = true;
        var valid_WO0 = true;
        var valid_sw = true;
        var valid_krw = true;
        var valid_kro = true;
        var valid_sg = true;
        var valid_krg = true;
        var valid_krl = true;
        var valid_pcwo = true;
        var valid_pcgl = true;
        var valid_pressure = true;
        var evt = window.event || arguments.callee.caller.arguments[0];
        var outside = 0;

        datos_reservoir = $reservoirPressure_table.handsontable('getData');
        $("#reservoirPressure").val(JSON.stringify(datos_reservoir));


        datos_GL = $excel2.handsontable('getData');
        $("#RelP2").val(JSON.stringify(datos_GL));

        datos_WO = $excel.handsontable('getData');
        $("#RelP").val(JSON.stringify(datos_WO));

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
        var gl = $excel2.handsontable('getData');
        for (var i = 0; i < gl.length; i++) {
            d0 = gl[i][0];
            d1 = gl[i][1];
            d2 = gl[i][2];
            d3 = gl[i][3];
            if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != "")) || (isNaN(d2) && (d2 != null && d2 != "")) || (isNaN(d3) && (d3 != null && d3 != ""))) {
                valid_GL = false;
                mensaje_GL = "Check your Gas-Liquid data, there's must be numeric...";
            }
        }

        for (var i = 0; i < gl.length; i++) {
            d0 = gl[i][0];
            d1 = gl[i][1];
            d2 = gl[i][2];
            d3 = gl[i][3];
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "") {
                    valid_GL = false;
                    mensaje_GL = "Check your Gas-Liquid data, there's nothing there...";
                }

            }
            if (d0 == 0 && d1 == 0 && d2 == 0 && d3 == 0) {
                    valid_GL0 = false;
                    mensaje_GL0 = "All Gas-Liquid data can not be 0.";
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
        var wo = $excel.handsontable('getData');
        for (var i = 0; i < wo.length; i++) {
            d0 = wo[i][0];
            d1 = wo[i][1];
            d2 = wo[i][2];
            d3 = wo[i][3];

            if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != "")) || (isNaN(d2) && (d2 != null && d2 != "")) || (isNaN(d3) && (d3 != null && d3 != ""))) {
                valid_WO = false;
                mensaje_WO = "Check your Water-Oil data, there's must be numeric...";
            }
            if (d0 == 0 && d1 == 0 && d2 == 0 && d3 == 0) {
                    valid_WO0 = false;
                    mensaje_WO0 = "All Water-Oil data can not be 0.";
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

        for (var i = 0; i < wo.length; i++) {
            d0 = wo[i][0];
            d1 = wo[i][1];
            d2 = wo[i][2];
            d3 = wo[i][3];
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "") {
                    valid_WO = false;
                    mensaje_WO = "Check your Water-Oil data, there's nothing there...";
                }
            }
        }

        

        //En caso de que un valor de input se salga de los rangos normales mostrar alerta. El usuario decide si desea seguir y guardar
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

        //Cambiar color de input y borde en caso de que exista una alerta de valores fuera de rangos normales
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


        //Creacion de mensajes de error
        if (!valid_GL || !valid_WO || !valid_GL0 || !valid_WO0 || !valid_reservoir || !valid_sw || !valid_krw || !valid_kro || !valid_sg || !valid_krg || !valid_krl || !valid_pcwo || !valid_pcgl || !valid_pressure) {
            var mensaje = "";
            if (!valid_GL) {
                mensaje = mensaje + "\n" + mensaje_GL;
            }
            if (!valid_WO) {
                mensaje = mensaje + "\n" + mensaje_WO;
            }

            if (!valid_GL0) {
                mensaje = mensaje + "\n" + mensaje_GL0;
            }
            if (!valid_WO0) {
                mensaje = mensaje + "\n" + mensaje_WO0;
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
            $("#modal_error_js").modal('show');
            $('#alertErrorJS').html('<span>'+ mensaje +'</span>');
        } else {
            //Crear ventana modal para mostrar alertas. El usuario decide si desea seguir y guardar o regresar y modificar valores 
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

    $(document).ready(function(){
        //Saber si es la primera vez que se entra a la interfaz para cargar valores en las tablas
        data_pvt = $("#pvt_table").val();
        if (data_pvt === '') {
            data_pvt = [
                [, , , , , , , , ],
                [, , , , , , , , , ]
            ];
        } else {
            data_pvt = JSON.parse(data_pvt);
        }

        var maxed = false,
            resizeTimeout, 
            availableWidth, 
            availableHeight, 
            $window = $(window),
            $table_field_pvt = $('#table_field_pvt');

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };

        $window.on('resize', calculateSize);

        $table_field_pvt.handsontable({
            data: data_pvt,
            colWidths: [100, 55, 55, 55, 90, 90, 90, 90, 90],
            rowHeaders: true,
            columns: [{
                    title: "Pressure[psia]",
                    data: 0,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "μo[cP]",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "μg[cP]",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "μw[cP]",
                    data: 3,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Bo[RB/STB]",
                    data: 4,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Bg[RCF/SCF]",
                    data: 5,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Bw[RB/STB]",
                    data: 6,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Rs[SCF/STB]",
                    data: 7,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Rv[STB/SCF]",
                    data: 8,
                    type: 'numeric',
                    format: '0[.]0000000'
                }
            ],

            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 1000;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 300;
            }
        });
    });

    	 //Grafica de valores de tabla pvt para campo
         function plot_fieldPVT() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = $("#table_field_pvt").handsontable('getData');
        var pressure = [];
        var muO = [];
        var muG = [];
        var muW = [];
        var Bo = [];
        var Bg = [];
        var Bw = [];
        var Rs = [];
        var Rv = [];

        for (var i = 0; i < data.length; i++) {
            pressure.push(data[i][0]);
            muO.push(data[i][1]);
            muG.push(data[i][2]);
            muW.push(data[i][3]);
            Bo.push(data[i][4]);
            Bg.push(data[i][5]);
            Bw.push(data[i][6]);
            Rs.push(data[i][7]);
            Rv.push(data[i][8]);
        }
        pressure.pop();
        muO.pop();
        muG.pop();
        muW.pop();
        Bo.pop();
        Bg.pop();
        Bw.pop();
        Rs.pop();
        Rv.pop();

        $('#pvtField').highcharts({
            title: {
                text: 'Field\'s PVT',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Pressure'
                },
                categories: pressure
            },
            yAxis: {
                title: {
                    text: ''
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
                    name: 'uo [cP]',
                    data: muO
                }, {
                    name: 'ug [cP]',
                    data: muG
                },
                {
                    name: 'uw [cP]',
                    data: muW
                },
                {
                    name: 'Bo [RB/STB]',
                    data: Bo
                },
                {
                    name: 'Bg [RCF/SCF]',
                    data: Bg
                },
                {
                    name: 'Bw [RB/STB]',
                    data: Bw
                },
                {
                    name: 'Rs [SCF/STB]',
                    data: Rs
                },
                {
                    name: 'Rv [STB/SCF]',
                    data: Rv
                }
            ]
        });
    }

    
</script>