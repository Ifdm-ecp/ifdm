<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
    

    //Funcion para mostrar y ocultar contenido de botones
    $(function() {
        $('.toggle').click(function(event) {
            event.preventDefault();
            var target = $(this).attr('href');
            $(target).toggleClass('hidden show');
        });
    });

    //Inicializacion variables para libreria de tablas
    var maxed = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $table_field_coordinates = $('#table_field_coordinates');

    var maxed3 = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $table_field_pvt = $('#table_field_pvt');

    $(document).ready(function() {
        $('.chevron_toggleable').on('click', function() {
            $(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        });

        //Tooltips
        $('#basin_for_field > option[value="{{ $field->cuenca_id }}"]').attr('selected', 'selected');
        $("#tooltip_coordinates_field").tooltip({
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84). Insert the coordinates in the right order' // title option passed via javascript
        });
        $("#tooltip_pvt_field").tooltip({
            title: 'Fluid Properties Evaluations: Pressure, Volume, Temperature.'
        });
    });

    //Contruir tabla y llenar con valores cuardados anteriormente en la BD

    //Tabla coordenadas
    var aux_value_table = [];
    $.get("{!! url('excel53') !!}", {
        campo: <?php echo $field->id ?>
    }, function(data) {
        $.each(data, function(index, value) {
            var per = [];
            if(value.lat_gms){
                per.push(value.lat_gms);
                per.push(value.lon_gms);
            }else{
                per.push(value.lat);
                per.push(value.lon);
            }
            
            aux_value_table.push(per);
        });

        if ($("#field_coordinates").val() != '') {
            aux_value_table = $("#field_coordinates").val();
            if (aux_value_table === '') {
                aux_value_table = [
                    [, ],
                    [, ]
                ];
            } else {
                aux_value_table = JSON.parse(aux_value_table);
            }
        }


        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $table_field_coordinates.handsontable({
            data: aux_value_table,
            colWidths: [150, 150],
            rowHeaders: true,
            columns: [

                {
                    title: "Latitude",
                    data: 0,
                    type: 'text',
                    format: '0[.]0000000'
                },
                {
                    title: "Longitude",
                    data: 1,
                    type: 'text',
                    format: '0[.]0000000'
                }
            ],
            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 350;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 300;
            }
        });



    });

    

    //Mensaje con errores de tablas
    function show_error() {
        var valid_coordinates = true;
        var valid_pvt = true;
        var valid_pvt_range = true;

        datos_coordf = $table_field_coordinates.handsontable('getData');
        $("#field_coordinates").val(JSON.stringify(datos_coordf));

        datos_pvt = $table_field_pvt.handsontable('getData');
        $("#pvt_table").val(JSON.stringify(datos_pvt));

        var pvt = $table_field_pvt.handsontable('getData');
        var coordinates = datos_coordf;

        var evt = window.event || arguments.callee.caller.arguments[0];

        //Tabla coordenadas
        d0 = coordinates[0][0];
        d1 = coordinates[0][1];

        if(isNaN(d0)){
            for (var i = 0; i < coordinates.length; i++) {
                d0 = coordinates[i][0];
                d1 = coordinates[i][1];

                if(d0 != null && d0 != ""){
                    value0 = d0.replace(/\s+/g,' ').trim().split(" ");
                    if (value0.length != 4){
                        valid_coordinates = false;
                        mensaje_coordinates = "Check your coordinates";
                    }else{
                        if (!isNaN(value0[0]) || isNaN(value0[1]) || isNaN(value0[2]) || isNaN(value0[3]) ){
                            valid_coordinates = false;
                            mensaje_coordinates = "Check your coordinates";
                        }
                    }
                }

                if(d1 != null && d1 != ""){
                    value1 = d1.replace(/\s+/g,' ').trim().split(" ");
                    if (value1.length != 4){
                        valid_coordinates = false;
                        mensaje_coordinates = "Check your coordinates";
                    }else{
                        if (!isNaN(value1[0]) || isNaN(value1[1]) || isNaN(value1[2]) || isNaN(value1[3]) ){
                            valid_coordinates = false;
                            mensaje_coordinates = "Check your coordinates";
                        }
                    }
                }
            }
        }else{
            for (var i = 0; i < coordinates.length; i++) {
                d0 = coordinates[i][0];
                d1 = coordinates[i][1];
                if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != ""))) {
                    valid_coordinates = false;
                    mensaje_coordinates = "Check your coordinates, there's must be numeric...";
                }
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