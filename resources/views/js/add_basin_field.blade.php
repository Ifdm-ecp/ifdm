<script type="text/javascript">
    //Función para ocultar y mostrar contenido de los botones
    $(function() {
        $('.toggle').click(function(event) {
            event.preventDefault();
            var target = $(this).attr('href');
            $(target).toggleClass('hidden show');
        });
    });

    $(document).ready(function() {
        $('.chevron_toggleable').on('click', function() {
            $(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        });

        //Tooltips
        $("#tooltip_basin_coordinates").tooltip({
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84).'
        });
        $("#tooltip_coordinates_field").tooltip({
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84). Insert The Coordinates In The Right Order' // title option passed via javascript
        });
        $("#tooltip_pvt_field").tooltip({
            title: 'Fluid Properties Evaluations: Pressure, Volume, Temperature.'
        });

        //Funciones tabla de coordenadas para cuenca

        //Saber si es la primera vez que se entra a la interfaz para cargar valores en las tablas
        aux_value_basin_coordinates = $("#basin_coordinates").val();
        if (aux_value_basin_coordinates === '') {
            aux_value_basin_coordinates = [
                [, ],
                [, ]
            ];
        } else {
            aux_value_basin_coordinates = JSON.parse(aux_value_basin_coordinates);
        }


        var maxed = false,
            resizeTimeout, availableWidth, availableHeight, $window = $(window),
            $table_basin_coordinates = $('#table_basin_coordinates');

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };

        $window.on('resize', calculateSize);

        $table_basin_coordinates.handsontable({
            data: aux_value_basin_coordinates,
            colWidths: [150, 150],
            rowHeaders: true,

            columns: [{
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
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 400;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 300;
            }
        });

        //Convertir datos de la tabla de coordenadas para basin en formato json
        $('.check_basin').on('click', function() {
            datos_coord = $table_basin_coordinates.handsontable('getData');
            $("#basin_coordinates").val(JSON.stringify(datos_coord));
        });

        //Validaciones para datos de la tabla coordenadas para basin
        $('.check_coordinates').on('click', function() {
            var coordinates = $table_basin_coordinates.handsontable('getData');
            d0 = coordinates[0][0];
            d1 = coordinates[0][1];
            var valid = true;
            for (var i = 0; i < coordinates.length; i++) {
                d0 = coordinates[i][0];
                d1 = coordinates[i][1];
                if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != ""))) {
                    valid = false;
                    mensaje = "Check your coordinates, there's must be numeric...";
                }
            }

            for (var i = 0; i < coordinates.length; i++) {
                d0 = coordinates[i][0];
                d1 = coordinates[i][1];
                if ((d0 != null && d0 != "") || (d1 != null && d1 != "")) {

                    if (d1 == null || d1 === "" || d0 == null || d0 === "") {
                        valid = false;
                        mensaje = "Check your coordinates, there's nothing there...";
                    }
                }
            }
            var evt = window.event || arguments.callee.caller.arguments[0];

            if (!valid) {
                evt.preventDefault();
                alert(mensaje);
            }
        });



        //Funciones tablas para campo

        //Saber si es la primera vez que se entra a la interfaz para cargar valores en las tablas
        aux_value_field_coordinates = $("#field_coordinates").val();
        if (aux_value_field_coordinates === '') {
            aux_value_field_coordinates = [
                [, ],
                [, ]
            ];
        } else {
            aux_value_field_coordinates = JSON.parse(aux_value_field_coordinates);
        }

        var maxed = false,
            resizeTimeout, availableWidth, availableHeight, $window = $(window),
            $table_field_coordinates = $('#table_field_coordinates');

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };

        $window.on('resize', calculateSize);

        $table_field_coordinates.handsontable({
            data: aux_value_field_coordinates,
            colWidths: [150, 150],
            rowHeaders: true,
            columns: [{
                    title: "Latitude",
                    data: 0,
                    format: '0[.]0000000'
                },
                {
                    title: "Longitude",
                    data: 1,
                    format: '0[.]0000000'
                }
            ],

            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 400;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 300;
            }
        });

        //Convertir datos de la tabla de coordenadas para campo en formato json
        $('.check_field').on('click', function() {
            datos_coordf = $table_field_coordinates.handsontable('getData');
            $("#field_coordinates").val(JSON.stringify(datos_coordf));
        });

        //Validaciones para datos de la tabla coordenadas para basin
        $('.check_field').on('click', function() {
            
            //coordinates
            var coordinates = $table_field_coordinates.handsontable('getData');
            d0 = coordinates[0][0];
            d1 = coordinates[0][1];
            var valid = true;

            if(isNaN(d0)){
                for (var i = 0; i < coordinates.length; i++) {
                    d0 = coordinates[i][0];
                    d1 = coordinates[i][1];

                    if(d0 != null && d0 != ""){
                        value0 = d0.replace(/\s+/g,' ').trim().split(" ");
                        if (value0.length != 4){
                            valid = false;
                            mensaje = "Check your coordinates";
                        }else{
                            if (!isNaN(value0[0]) || isNaN(value0[1]) || isNaN(value0[2]) || isNaN(value0[3]) ){
                                valid = false;
                                mensaje = "Check your coordinates2";
                            }
                        }
                    }

                    if(d1 != null && d1 != ""){
                        value1 = d1.replace(/\s+/g,' ').trim().split(" ");
                        if (value1.length != 4){
                            valid = false;
                            mensaje = "Check your coordinates";
                        }else{
                            if (!isNaN(value1[0]) || isNaN(value1[1]) || isNaN(value1[2]) || isNaN(value1[3]) ){
                                valid = false;
                                mensaje = "Check your coordinates3";
                            }
                        }
                    }
                }
            }else{
                for (var i = 0; i < coordinates.length; i++) {
                    d0 = coordinates[i][0];
                    d1 = coordinates[i][1];
                    if ((isNaN(d0) && (d0 != null && d0 != "")) || (isNaN(d1) && (d1 != null && d1 != ""))) {
                        valid = false;
                        mensaje = "Check your coordinates, there's must be numeric...";
                    }
                }
            }

            for (var i = 0; i < coordinates.length; i++) {
                d0 = coordinates[i][0];
                d1 = coordinates[i][1];
                if ((d0 != null && d0 != "") || (d1 != null && d1 != "")) {

                    if (d1 == null || d1 === "" || d0 == null || d0 === "") {
                        valid = false;
                        mensaje = "Check your coordinates, there's nothing there...";
                    }
                }
            }
            var evt = window.event || arguments.callee.caller.arguments[0];

            if (!valid) {
                evt.preventDefault();
                alert(mensaje);
            }

        });
    });
</script>