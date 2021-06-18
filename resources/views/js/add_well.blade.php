<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
     //Funcion para crear y mostrar grafico de PLT
    function plotPlt() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = $("#excelPlt").handsontable('getData');
        var top = [];
        var bottom = [];
        var qo = [];
        var qg = [];
        var qw = [];
        var counter = [];

        for (var i = 0; i < data.length; i++) {
            top.push(data[i][0]);
            bottom.push(data[i][1]);
            qo.push(data[i][2]);
            qg.push(data[i][3]);
            qw.push(data[i][4]);
            counter.push(i+1);
        }
        top.pop();
        bottom.pop();
        qo.pop();
        qg.pop();
        qw.pop();

        $('#plt_Interval').highcharts({
            title: {
                text: 'Production Test',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'cantidad'
                },
                categories: counter
            },
            yAxis: {
                title: {
                    text: '%Qo, %Qg, %Qw, Top & Bottom'
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
                name: '%Qo',
                data: qo
            }, {
                name: '%Qg',
                data: qg
            }, {
                name: '%Qw',
                data: qw
            }, {
                name: 'Top',
                data: top
            }, {
                name: 'Bottom',
                data: bottom
            }]
        });
    }
    $(function() {
        $("#myModal").modal('show');
    });


     //Inicializacion de variable para tabla
   

    //Guardar valores de select para volver a cargar en caso de error en el formulario
    window.onbeforeunload = function() {
        var a = $('#field').val();
        localStorage.setItem('field', $('#field').val());
    }


    //Cargar valores de select en caso de error en el formulario
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
                var k = '#field > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', campo);
                $(k).attr('selected', 'selected');
            }
        );
    }


    //Inicializacion de variables para tablas
    var maxed = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $excel2 = $('#excel2');

     var maxed = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $excelPlt = $('#excelPlt');

    $(document).ready(function() {

        //Cargar tabla con valores pre-cargados en la BD

            prods = $("#plt").val();
            if (prods === '') {
                prods = [
                    [, , , , ]
                ];
            } else {
                prods = JSON.parse(prods);
            }



            // //Creacion de tabla
            // var calculateSize = function() {
            //     var offset = $example1.offset();
            //     availableWidth = $window.width() - offset.left + $window.scrollLeft();
            //     availableHeight = $window.height() - offset.top + $window.scrollTop();
            // };
            // $window.on('resize', calculateSize);

            $excelPlt.handsontable({
                data: prods,
                colWidths: [50,50,125, 110, 160, 160, 160],
                rowHeaders: true,
                columns: [

                    {
                        title: "Top",
                        data: 0,
                        type: 'numeric'
                    },
                    {
                        title: "Bottom",
                        data: 1,
                        type: 'numeric'
                    },
                    {
                        title: "%Qo",
                        data: 2,
                        type: 'numeric'
                    },
                    {
                        title: "%Qg",
                        data: 3,
                        type: 'numeric'
                    },
                    {
                        title: "%Qw",
                        data: 4,
                        type: 'numeric'
                    },
                ],
                minSpareRows: 1,
                contextMenu: true,
                width: function() {
                    if (maxed && availableWidth === void 0) {
                        //calculateSize();
                    }
                    return maxed ? availableWidth : 1600;
                },
                height: function() {
                    if (maxed && availableHeight === void 0) {
                        //calculateSize();
                    }
                    return maxed ? availableHeight : 200;
                }
            });


        //Creacion de tablas y carga de valores en caso de error
        data_prood = $("#ProdD2").val();
        if (data_prood === '') {
            data_prood = [
                [, , , , , , ]
            ];
        } else {
            data_prood = JSON.parse(data_prood);
        }

        // var calculateSize = function() {
        //     var offset = $example1.offset();
        //     availableWidth = $window.width() - offset.left + $window.scrollLeft();
        //     availableHeight = $window.height() - offset.top + $window.scrollTop();
        // };
        // $window.on('resize', calculateSize);


        $excel2.handsontable({
            data: data_prood,
            colWidths: [100, 70, 110, 70, 110, 70, 110, 65, 65],
            rowHeaders: true,
            columns: [{
                    title: "Date",
                    data: 0,
                    type: 'date',
                    dateFormat: 'YYYY/MM/DD',
                    correctFormat: true
                },
                {
                    title: "Qo <br> [bbl/day]",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Cummulative Qo <br> [bbl]",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Qg <br> [MMScf/day]",
                    data: 3,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Cummulative Qg <br> [MMScf]",
                    data: 4,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Qw <br> [bbl/day]",
                    data: 5,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Cummulative Qw <br> [bbl]",
                    data: 6,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "GOR <br> [Scf/bbl]",
                    data: 7,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "WOR <br> [bbl/bbl]",
                    data: 8,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
            ],
            afterChange: function(changes, source) {
                    if (!changes) {
                        return;
                    }else{
                        var row = changes[0][0];
                        var col = changes[0][1];
                        var antiguo = changes[0][2];
                        var nuevo = changes[0][3];
                        validarDatePD("change");

                        if(changes[0][1] == 1 || changes[0][1] == 3){

                            
                                rqg = $excel2.handsontable('getDataAtCell', row, 3);
                                rqo = $excel2.handsontable('getDataAtCell', row, 1);
                                if(!rqo){
                                    rgor = 0;
                                }else{
                                    rgor = (1000000 * rqg)/rqo;
                                }
                                

                                $excel2.handsontable('setDataAtCell', row, 7, rgor); 
                            
                        }
                        if(changes[0][1] == 1 || changes[0][1] == 5){
                            rqw = $excel2.handsontable('getDataAtCell', row, 5);
                            rqo = $excel2.handsontable('getDataAtCell', row, 1);
                            if(!rqo){
                                rwor = 0;
                            }else{
                                rwor = rqw/rqo;
                            }
                            

                            $excel2.handsontable('setDataAtCell', row, 8, rwor);
                        } 
                    }
                   
                },
            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    //calculateSize();
                }
                return maxed ? availableWidth : 1600;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    //calculateSize();
                }
                return maxed ? availableHeight : 200;
            }
        });



        //Tooltips
        $("#myModal").modal('show');
        $("#FVFw").tooltip({
            title: 'Water Formation Volume Factor.'
        });
        $("#gwr").tooltip({
            title: 'Gas/Water Ratio: The Ratio Of Produced Gas To Produced Water.'
        });
        $("#lgr").tooltip({
            title: 'Liquid/ Gas Ratio: The Ratio Of Produced Liquid To Produced Gas.'
        });
        $("#fluidType").tooltip({
            title: 'Choose Your Fluid Type.'
        });
        $("#type").tooltip({
            title: 'Choose The Well Type. Producer/Injector.'
        });
        $("#wor").tooltip({
            title: 'Water/Oil Ratio: The Ratio Of Produced Water To Produced Oil.'
        });
        $("#gor").tooltip({
            title: 'Gas/Oil Ratio: The Ratio Of Produced Gas To Produced As.'
        });
        $("#cgr").tooltip({
            title: 'Condensate/Gas Ratio: The Ratio Of Produced Condensate To Produced Gas.'
        });
        $("#FVFo").tooltip({
            title: 'Oil Formation Volume Factor.'
        });
        $("#FVFg").tooltip({
            title: 'Gas Formation Volume Factor.'
        });
        $("#TDVW").tooltip({
            title: 'True Vertical Depth.'
        });
        $("#bhp").tooltip({
            title: 'Bottom-Hole Pressure.'
        });
        $("#XW").tooltip({
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84).'
        });
        $("#YW").tooltip({
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84).'
        });

        bhp_alert = function() {}
        bhp_alert.warning = function(message) {
            $('#alert_bhp').html('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span>' + message + '</span></div>')
        }
    });


    //Funcion para creacion y despliegue de grafico de daos de produccion
    function plot_wellProductionData() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = $("#excel2").handsontable('getData');
        var date = [];
        var Qo = [];
        var cummulative_Qo = [];
        var Qg = [];
        var cummulative_Qg = [];
        var Qw = [];
        var cummulative_Qw = [];
        var Gor = [];
        var Wor = [];
 


        for (var i = 0; i < data.length; i++) {

            date.push(data[i][0]);
            Qo.push(data[i][1]);
            cummulative_Qo.push(data[i][2]);
            Qg.push(data[i][3]);
            cummulative_Qg.push(data[i][4]);
            Qw.push(data[i][5]);
            cummulative_Qw.push(data[i][6]);


        }
        date.pop();
        Qo.pop();
        cummulative_Qo.pop();
        Qg.pop();
        cummulative_Qg.pop();
        Qw.pop();
        cummulative_Qw.pop();
        Gor.pop();
        Wor.pop();

        $('#productionData_Well_g').highcharts({
            title: {
                text: 'Field\'s Production Data',
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
                    text: 'Production Data'
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
                    name: 'Qo',
                    data: Qo
                }, {
                    name: 'Cummulative Qo',
                    data: cummulative_Qo
                },
                {
                    name: 'Qg',
                    data: Qg
                },
                {
                    name: 'Cummulative Qg',
                    data: cummulative_Qg
                },
                {
                    name: 'Qw',
                    data: cummulative_Qw
                }
            ]
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

    //Mostrar y ocultar contenido de pestañas de pozo y fluido
    function well() {
        document.getElementById('well').style.display = 'block';
    }

    function fluid() {
        document.getElementById('well').style.display = 'none';
        document.getElementById('fluid').style.display = 'block';
    }
    window.addEventListener('load', well, false);

    $(document).ready(function() {
        $('#field').attr('disabled', 'disabled');
        //Carga de valores de select anidado de acuerdo a opcion escogida
        $("#basin").change(function(e) {
            var cuenca = $('#basin').val();
            $.get("{{url('campos')}}", {
                cuenca: cuenca
            },
            function(data) {
                $("#field").empty();
                if (data.length == 0) {
                    $flag = 1;
                    var $validator = $("#form").validate();
                    var errors;
                    errors = { field: "The Basin has no Fields." };
                    /* Show errors on the form */
                    $validator.showErrors(errors);

                    $('#field').attr('disabled', 'disabled');
                }else{
                    $('#field').removeAttr('disabled');
                    $('label[class="error"]').css({"display":"none"})
                    
                    $.each(data, function(index, value) {
                        $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                }
            });
        });
    });


    //Validar valores de formulario. Mostrar ventana modal de error en caso de inconsistencias
    function Mostrar() {

        var valid = true;
        var mensaje_ = "";

        datos_pro = $excelPlt.handsontable('getData');
        $("#plt").val(JSON.stringify(datos_pro));


        //PLT
        var plt = $excelPlt.handsontable('getData');
        for (var i = 0; i < plt.length; i++) {
            d0 = plt[i][0];
            d1 = plt[i][1];
            d2 = plt[i][2];
            d3 = plt[i][3];
            d4 = plt[i][4];


            if ((isNaN(d2) && (d2 != null && d2 != "")) || (isNaN(d3) && (d3 != null && d3 != "")) || (isNaN(d4) && (d4 != null && d4 != ""))) {
                valid = false;
                mensaje_ = mensaje_ + "\n" + "Check your PLT data, there's must be numeric...";
            }
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "") || (d4 != null && d4 != "")) {

                if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "" || d4 == null || d4 === "") {
                    valid = false;
                    mensaje_ = mensaje_ + "\n" + "Check your PLT data, there's nothing there...";
                }
            }
            if (plt[i][2] > 100 || plt[i][2] < 0) {
                valid = false;
                mensaje_ = mensaje_ + "\n" + "Oil percentage must be between 0% and 100%.";
            }

            if (plt[i][3] > 100 || plt[i][3] < 0) {
                valid = false;
                mensaje_ = mensaje_ + "\n" + "Gas percentage must be between 0% and 100%.";
            }

            if (plt[i][4] > 100 || plt[i][4] < 0) {
                valid = false;
                mensaje_ = mensaje_ + "\n" + "Water percentage must be between 0% and 100%.";
            }
        }




        var evt = window.event || arguments.callee.caller.arguments[0];

        if (!valid) {
            evt.preventDefault();
            $("#modal_error_js").modal('show');
            $('#alertErrorJS').html('<span>'+ mensaje_ +'</span>');
        }







        var valid = true;
        var valid_qo = true;
        var valid_cumm_qo = true;
        var valid_qg = true;
        var valid_cumm_qg = true;
        var valid_qw = true;
        var valid_cumm_qw = true;
        datos_prood = $excel2.handsontable('getData');
        $("#ProdD2").val(JSON.stringify(datos_prood));

        //Production Data
        var production_data = $excel2.handsontable('getData');
        for (var i = 0; i < production_data.length; i++) {
            d1 = production_data[i][1];
            d2 = production_data[i][2];
            d3 = production_data[i][3];
            d4 = production_data[i][4];
            d5 = production_data[i][5];
            d6 = production_data[i][6];


            if ((isNaN(d1) && (d1 != null && d1 != "")) || (isNaN(d2) && (d2 != null && d2 != "")) || (isNaN(d3) && (d3 != null && d3 != "")) || (isNaN(d4) && (d4 != null && d4 != "")) || (isNaN(d5) && (d5 != null && d5 != "")) || (isNaN(d6) && (d6 != null && d6 != ""))) {
                valid = false;
                mensaje_num = "Check your production data, there's must be numeric...";
            }
            if ((d1 < 0)) {
                valid_qo = false;
                mensaje_qo = "Production data Qo must be greather than 0.";
            }
            if ((d2 < 0)) {
                valid_cumm_qo = false;
                mensaje_cumm_qo = "Production data Cummulative Qo must be greather than 0.";
            }
            if ((d3 < 0)) {
                valid_qg = false;
                mensaje_qg = "Production data Qg must be greather than 0.";
            }
            if ((d4 < 0)) {
                valid_cumm_qg = false;
                mensaje_cumm_qg = "Production data Cummulative Qg must be greather than 0.";
            }
            if ((d5 < 0)) {
                valid_qw = false;
                mensaje_qw = "Production data Qw must be greather than 0.";
            }
            if ((d6 < 0)) {
                valid_cumm_qw = false;
                mensaje_cumm_qw = "Production data Cummulative Qw must be greather than 0.";
            }
        }

        for (var i = 0; i < production_data.length; i++) {
            d0 = production_data[i][0];
            d1 = production_data[i][1];
            d2 = production_data[i][2];
            d3 = production_data[i][3];
            d4 = production_data[i][4];
            d5 = production_data[i][5];
            d6 = production_data[i][6];
            if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "") || (d4 != null && d4 != "") || (d5 != null && d5 != "") || (d6 != null && d6 != "")) {

                if (d0 == null || d0 === "" || d1 == null || d1 === "" || d2 == null || d2 === "" || d3 == null || d3 === "" || d4 == null || d4 === "" || d5 == null || d5 === "" || d6 == null || d6 === "") {
                    valid = false;
                    mensaje_num = "Check your production data, missing data there";
                }
            }
        }

        var evt = window.event || arguments.callee.caller.arguments[0];
        var outside = 0;
        
        var complete = 0;

        //Cambiar color de inputs para mostrar advertencias de rangos de valores de formulario

        if ($('#wellRadius').val() == '') {
            document.getElementById('wellRadius-addon').style.backgroundColor = "#F2DEDE";
            document.getElementById('wellRadius-addon').style.borderColor = "#A94442";
            document.getElementById('wellRadius').style.borderColor = "#A94442";
            var complete = 1;
        } else {
            document.getElementById('wellRadius-addon').style.backgroundColor = "#EEEEEE";
            document.getElementById('wellRadius-addon').style.borderColor = "#CCCCCC";
            document.getElementById('wellRadius').style.borderColor = "#CCCCCC";
        }

        if ($('#drainageRadius').val() == '') {
            document.getElementById('drainageRadius-addon').style.backgroundColor = "#F2DEDE";
            document.getElementById('drainageRadius-addon').style.borderColor = "#A94442";
            document.getElementById('drainageRadius').style.borderColor = "#A94442";
            var complete = 1;
        } else {
            document.getElementById('drainageRadius-addon').style.backgroundColor = "#EEEEEE";
            document.getElementById('drainageRadius-addon').style.borderColor = "#CCCCCC";
            document.getElementById('drainageRadius').style.borderColor = "#CCCCCC";
        }


        //Creacion de mensajes de error
        if (!valid || !valid_qo || !valid_cumm_qo || !valid_qg || !valid_cumm_qg || !valid_qw || !valid_cumm_qw) {
            var mensaje = "";
            if (!valid) {
                mensaje = mensaje + "\n" + mensaje_num;
            }
            if (!valid_qo) {
                mensaje = mensaje + "\n" + mensaje_qo;
            }
            if (!valid_cumm_qo) {
                mensaje = mensaje + "\n" + mensaje_cumm_qo;
            }
            if (!valid_qg) {
                mensaje = mensaje + "\n" + mensaje_qg;
            }
            if (!valid_cumm_qg) {
                mensaje = mensaje + "\n" + mensaje_cumm_qg;
            }
            if (!valid_qw) {
                mensaje = mensaje + "\n" + mensaje_qw;
            }
            if (!valid_cumm_qw) {
                mensaje = mensaje + "\n" + mensaje_cumm_qw;
            }
            evt.preventDefault();
            $("#modal_error_js").modal('show');
            $('#alertErrorJS').html('<span>'+ mensaje +'</span>');
        } else {

            //Creacion y despliegue de ventana modal para mostrar errores y advertencias en el formulario
            //El usuario decide si desea continuar y guardar o modificar algun valor del formulario
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
                    form.submit();
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
                    form.submit();
                });
            } else {
                form.submit();
            }
        }

    }

    function validarDatePD(action)
    {
        var countRows = $excel2.handsontable('countRows')-1;
        for(var i = 0; i < countRows; i++){
            if(!$excel2.handsontable('getDataAtCell', i, 0)){
                if(action == "change"){
                    var cell = $excel2.handsontable('getCell',i,0);                                    
                    cell.style.backgroundColor = "red";
                }else if(action == "save")
                {
                    return true;
                }
                
            }                  
        }
    }
</script>