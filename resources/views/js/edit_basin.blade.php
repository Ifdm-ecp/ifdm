<script type="text/javascript">
    //Funcion para mostrar y ocultar contenido de botones
    $(function() {
        $('.toggle').click(function(event) {
            event.preventDefault();
            var target = $(this).attr('href');
            $(target).toggleClass('hidden show');
        });
    });

    var maxed = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $table_basin_coordinates = $('#table_basin_coordinates');

    $(document).ready(function() {
        $('.chevron_toggleable').on('click', function() {
            $(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        });

        //Tooltips
        $("#tooltip_basin_coordinates").tooltip({
            title: 'Coordinate Units: Decimal Degrees (Ej: -75.48761). Coordinate System: MAGNA-SIRGAS (WGS84).'
        });
    });




    var perms = [];
    $.get("{!! url('table_basin_coordinates4') !!}", {
        cuenca: <?php echo $basin->id ?>

    }, function(data) {
        $.each(data, function(index, value) {
            var per = [];
            per.push(value.lat);
            per.push(value.lon);
            perms.push(per);
        });

        //Guardar valores de tabla para recargarlos cuando haya un error
        if ($("#basin_coordinates").val() != '') {
            perms = $("#basin_coordinates").val();
            if (perms === '') {
                perms = [
                    [, ],
                    [, ]
                ];
            } else {
                perms = JSON.parse(perms);
            }
        }




        //Crear tabla coordenadas para cuenca
        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $table_basin_coordinates.handsontable({
            data: perms,

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
                },
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

    //Validar valores de tabla coordenadas de cuenca
    function Validate() {
        var valid = true;
        datos_coord = $table_basin_coordinates.handsontable('getData');
        $("#basin_coordinates").val(JSON.stringify(datos_coord));

        var coordinates = $table_basin_coordinates.handsontable('getData');
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
    }
</script>