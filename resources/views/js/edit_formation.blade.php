
<script type="text/javascript">
    //Funcion para graficar tabla de PVT

    //Funcion para crear y mostrar grafico de water-oil
    function plot_waterOil_Formation() {
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
        $('#waterOil_Formation_g').highcharts({
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

    //Funcion para crear y mostrar grafico de gas-oil
    function plot_gasOil_Formation() {
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
        $('#gasOil_Formation_g').highcharts({
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

    //Funcion para mostrar y ocultar contenido de boton de tablas
    $(function() {
        $("#myModal").modal('show');
        $('.toggle').click(function(event) {
            event.preventDefault();
            var target = $(this).attr('href');
            $(target).toggleClass('hidden show');
        });
    });



    $(document).ready(function() {
        $('#fieldFormation > option[value="{{ $data->campo_id }}"]').attr('selected', 'selected');

        $('.chevron_toggleable').on('click', function() {
            $(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        });

        $("#myModal").modal('show');

        $("#tooltip_pvt_field").tooltip({
            title: 'Fluid Properties Evaluations: Pressure, Volume, Temperature.'
        });
    });

    //Inicializacion de variables para creacion de tablas
    var maxed = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $excel = $('#excel');

    var maxed2 = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $excel2 = $('#excel2');

    var maxed1 = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $excel1 = $('#excel1');

    var maxed3 = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $table_field_pvt = $('#table_field_pvt');


    $(document).ready(function() {
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
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84). Insert The Coordinates In The Right Order'
        });

        //Creacion de alertas en div para mostrar advertencias por valor fuera de rango normal
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


    //Creacion de tablas y carga de valores, consultas a BD para mostrar valores pre-cargados
    var perms = [];
    $.get("{!! url('excel2') !!}", {
        formacion: <?php echo $data->id ?>
    }, function(data) {
        $.each(data, function(index, value) {
            var per = [];
            per.push(value.sw);
            per.push(value.krw);
            per.push(value.kro);
            per.push(value.pcwo);
            perms.push(per);
        });
        if ($("#RelP").val() != '') {
            perms = $("#RelP").val();
            if (perms === '') {
                perms = [
                    [, ],
                    [, ],
                    [, ],
                    [, ]
                ];
            } else {
                perms = JSON.parse(perms);
            }
        }
        
        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel.handsontable({
            data: perms,
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


    });


    //Creacion y carga de valores para tabla
    var perms2 = [];
    $.get("{!! url('excel22') !!}", {
        formacion: <?php echo $data->id ?>
    }, function(data) {
        $.each(data, function(index, value) {
            var per = [];
            per.push(value.sg);
            per.push(value.krg);
            per.push(value.krl);
            per.push(value.pcgl);
            perms2.push(per);

        });

        if ($("#RelP2").val() != '') {
            perms2 = $("#RelP2").val();
            if (perms2 === '') {
                perms2 = [
                    [, ],
                    [, ],
                    [, ],
                    [, ]
                ];
            } else {
                perms2 = JSON.parse(perms2);
            }
        }



        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel2.handsontable({
            data: perms2,
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
            contextMenu: true,
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


    //Creacion yy carga de valores para tabla de coordenadas
    var perms1 = [];
    $.get("{!! url('excel5') !!}", {
        campo: <?php echo $data->campo_id ?>,
        formacion: <?php echo $data->id ?>
    }, function(data) {
        $.each(data, function(index, value) {
            var per1 = [];
            per1.push(value.lat);
            per1.push(value.lon);
            perms1.push(per1);
            $('input[name=prof][value=' + value.depth + ']').prop('checked', true);
        });

        if ($("#CoordF").val() != '') {
            perms1 = $("#CoordF").val();
            if (perms1 === '') {
                perms1 = [
                    [, ],
                    [, ]
                ];
            } else {
                perms1 = JSON.parse(perms1);
            }
        }



        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel1.handsontable({
            data: perms1,
            colWidths: [150, 150],
            rowHeaders: true,
            columns: [

                {
                    title: "Latitude",
                    data: 0,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Longitude",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                }
            ],
            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed1 && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed1 ? availableWidth : 350;
            },
            height: function() {
                if (maxed1 && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed1 ? availableHeight : 300;
            }
        });

    });


    //Tabla intervalos productores
    data_in = $("#producing_intervals").val();

    if (data_in === "") {
        data_in = [
            [, ],
            [, ]
        ];
    } else {
        producing_intervals = [];
        data_in = JSON.parse(data_in);
        data_in.forEach(element => {
            producing_intervals.push([String(element)]);
        });
    }

    var calculateSize = function() {
        var offset = $example1.offset();
        availableWidth = $window.width() - offset.left + $window.scrollLeft();
        availableHeight = $window.height() - offset.top + $window.scrollTop();
    };

    $window.on('resize', calculateSize);
    
    $('#table_producing_intervals').handsontable({
        data: producing_intervals,
        colWidths: [400],
        rowHeaders: true,
        columns: [

                {
                    title: "Nombre",
                    data: 0,
                    type: 'text'
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


    //Funcion para validar valores de tablas y datos fuera de rango normal. Mostrar errores y advertencias
    function Mostrar() {

        var valid_GL = true;
        var valid_WO = true;
        var valid_coordinates = true;
        var valid_sw = true;
        var valid_krw = true;
        var valid_kro = true;
        var valid_sg = true;
        var valid_krg = true;
        var valid_krl = true;
        var valid_pcwo = true;
        var valid_pcgl = true;

        datos_WO = $excel.handsontable('getData');
        $("#RelP").val(JSON.stringify(datos_WO));

        datos_GL = $excel2.handsontable('getData');
        $("#RelP2").val(JSON.stringify(datos_GL));

        datos_coord = $excel1.handsontable('getData');
        $("#CoordF").val(JSON.stringify(datos_coord));

        datos_pvt = $table_field_pvt.handsontable('getData');
        $("#pvt_table").val(JSON.stringify(datos_pvt));

        datos_producing_intervals = $('#table_producing_intervals').handsontable('getData');
        $("#producing_intervals").val(JSON.stringify(datos_producing_intervals));

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
                valid_pcwo = false;
                mensaje_pcwo = "Gas-Oil Pcwo must be greather than 0.";
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
                valid_pcgl = false;
                mensaje_pcgl = "Water-Oil Pcgl must be greather than 0.";
            }
        }

        //coordinates
        var coordinates = $excel1.handsontable('getData');
        for (var i = 0; i < coordinates.length; i++) {
            d0 = coordinates[i][0];
            d1 = coordinates[i][1];

            if (((isNaN(d0)) && (d0 != null && d0 != "")) || ((isNaN(d1)) && (d1 != null && d1 != ""))) {
                valid_coordinates = false;
                mensaje_coordinates = "Check your coordinates, there's must be numeric...";
            }
        }

        for (var i = 0; i < coordinates.length; i++) {
            d0 = coordinates[i][0];
            d1 = coordinates[i][1];
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "") {
                    valid_coordinates = false;
                    mensaje_coordinates = "Check your coordinates, there's nothing there...";
                }
            }
        }



        var evt = window.event || arguments.callee.caller.arguments[0];
        var outside = 0;

        //MOstrar advertencias a valores fuera de rango normal
        if (document.getElementById("top").value > 100000) {
            outside = 1;
            $("#alert_top").show();
            top_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_top").hide();
        }

        if (document.getElementById("netpay").value > 10000) {
            outside = 1;
            $("#alert_netpay").show();
            netpay_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_netpay").hide();
        }

        if (document.getElementById("porosity").value > 100) {
            outside = 1;
            $("#alert_porosity").show();
            porosity_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_porosity").hide();
        }

        if (document.getElementById("permeability").value > 1000000) {
            outside = 1;
            $("#alert_permeability").show();
            permeability_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_permeability").hide();
        }

        if (document.getElementById("reservoir").value > 20000) {
            outside = 1;
            $("#alert_reservoir").show();
            reservoir_alert.warning('<strong>Warning!</strong> Value Outside Normal Limits.');
        } else {
            $("#alert_reservoir").hide();
        }

        var complete = 0;


        //Cambiar color de input y bordes en caso de que el valor esta fuera del rango normal
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


        //Creacion de variables para mostrar mensaje con errores y advertencias
        if (!valid_coordinates || !valid_GL || !valid_WO || !valid_sw || !valid_krw || !valid_kro || !valid_sg || !valid_krg || !valid_krl || !valid_pcwo || !valid_pcgl) {
            var mensaje = "";
            if (!valid_coordinates) {
                mensaje = mensaje + "\n" + mensaje_coordinates;
            }
            if (!valid_GL) {
                mensaje = mensaje + "\n" + mensaje_GL;
            }
            if (!valid_WO) {
                mensaje = mensaje + "\n" + mensaje_WO;
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
            if (!valid_pcwo) {
                mensaje = mensaje + "\n" + mensaje_pcwo;
            }
            evt.preventDefault();
            alert(mensaje);
        } else {
            //Creacion de ventana modal para mostrar errores y advertencias de datos malos en formulario
            //En caso de que sea una advertencia de valores fuera de rango el usuario decide si desea guardar y seguir o modificar algun valor
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


        //Tabla PVT
        for (var i = 0; i < pvt.length; i++) {
            d0 = pvt[i][0];
            d1 = pvt[i][1];
            d2 = pvt[i][2];
            d3 = pvt[i][3];
            d4 = pvt[i][4];
            d5 = pvt[i][5];
            d6 = pvt[i][6];
            d7 = pvt[i][7];
            d8 = pvt[i][8];
            if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != "")) || (isNaN(d2) && (d2 != null && d2 != "")) || (isNaN(d3) && (d3 != null && d3 != "")) || (isNaN(d4) && (d4 != null && d4 != "")) || (isNaN(d5) && (d5 != null && d5 != "")) || (isNaN(d6) && (d6 != null && d6 != "")) || (isNaN(d7) && (d7 != null && d7 != "")) || (isNaN(d8) && (d8 != null && d8 != ""))) {
                valid_pvt = false;
                mensaje_pvt = "Check your pvt data, there's must be numeric...";
            }

            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "") || (d4 != null && d4 != "") || (d5 != null && d5 != "") || (d6 != null && d6 != "") || (d7 != null && d7 != "") || (d8 != null && d8 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "" || d4 == null || d4 === "" || d5 == null || d5 === "" || d6 == null || d6 === "" || d7 == null || d7 === "" || d8 == null || d8 === "") {
                    valid_pvt = false;
                    mensaje_pvt = "Check your pvt data, there's nothing there...";
                }
            }
            if ((d0 < 0) || (d1 < 0) || (d2 < 0) || (d3 < 0) || (d4 < 0) || (d5 < 0) || (d6 < 0) || (d7 < 0) || (d8 < 0)) {
                valid_pvt_range = false;
                mensaje_pvt_range = "Your pvt data must be greather than 0.";
            }
        }

        if (!valid_pvt || !valid_coordinates || !valid_pvt_range) {
            var mensajeT = "";
            if (!valid_coordinates) {
                mensajeT = mensajeT + "\n" + mensaje_coordinates;
            }
            if (!valid_pvt) {
                mensajeT = mensajeT + "\n" + mensaje_pvt;
            }
            if (!valid_pvt_range) {
                mensajeT = mensajeT + "\n" + mensaje_pvt_range;
            }
            evt.preventDefault();
            alert(mensajeT);
        }
    }
</script>