<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
    //Funcion para crear y mostrar grafico para valores de tabla PLT
    function plot_plt_Interval() {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        data = $("#excel").handsontable('getData');
        var interval = [];
        var date = [];
        var qo = [];
        var qg = [];
        var qw = [];

        for (var i = 0; i < data.length; i++) {
            interval.push(data[i][0]);
            date.push(data[i][1]);
            qo.push(data[i][2]);
            qg.push(data[i][3]);
            qw.push(data[i][4]);
        }

        $('#plt_Interval').highcharts({
            title: {
                text: 'Production Test',
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
                    text: '%Qo, %Qg & %Qw'
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
            }]
        });
    }
    $(function() {
        $("#myModal").modal('show');
    });

    //Inicializacion de variables para la creacion de tablas
    var maxed = false,
        resizeTimeout, availableWidth, availableHeight, $window = $(window),
        $excel = $('#excel');


    //Creacion de tablas y carga de valores con consulta a la BD
    $(document).ready(function() {
        var prods = [];
        $.get("{!! url('excel8080') !!}", {
            pozo: <?php echo Input::get('pozoId') ?>
        }, function(data) {
            $.each(data, function(index, value) {
                var prodD = [];
                prodD.push(value.intervalo);
                prodD.push(value.fecha);
                prodD.push(value.pqo);
                prodD.push(value.pqg);
                prodD.push(value.pqw);
                prods.push(prodD);
            });

            if ($("#ProdT").val() != '') {
                prods = $("#ProdT").val();
                if (prods === '') {
                    prods = [
                        [, , , , ],
                        [, , , , ]
                    ];
                } else {
                    prods = JSON.parse(prods);
                }
            }

            intervals='<?php echo $intervals ?>'.split(',');

            if(prods.length < intervals.length){
                for (var i = intervals.length - 1; i >= 0; i--) {
                    for (var j = prods.length - 1; j >= 0; j--) {
                        var exist_interval = false;
                        if(JSON.parse(intervals)[i].nombre != prods[j][0]){
                            exist_interval = true;
                        }
                    };
                    if(exist_interval){
                        prods.push([JSON.parse(intervals)[i].nombre]);
                    }
                };
            }

            if (prods == '') {
                for (var i = intervals.length - 1; i >= 0; i--) {
                    prods.push([JSON.parse(intervals)[i].nombre]);                    
                };
            }


            var calculateSize = function() {
                var offset = $example1.offset();
                availableWidth = $window.width() - offset.left + $window.scrollLeft();
                availableHeight = $window.height() - offset.top + $window.scrollTop();
            };
            $window.on('resize', calculateSize);

            $excel.handsontable({
                data: prods,
                colWidths: [145, 130, 180, 180, 180],
                rowHeaders: false,
                columns: [

                    {
                        title: "Producing Interval",
                        data: 0,
                        readOnly: true
                    },
                    {
                        title: "Date",
                        data: 1,
                        type: 'date',
                        dateFormat: 'YYYY/MM/DD',
                        correctFormat: true
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
                    return maxed ? availableHeight : 200;
                }
            });


        });
    });


    //Funcion para validar valores de tablas
    function Mostrar() {
        var valid = true;
        var mensaje_ = "";

        datos_pro = $excel.handsontable('getData');
        $("#ProdT").val(JSON.stringify(datos_pro));

        //PLT
        var plt = $excel.handsontable('getData');
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
            alert(mensaje_);
        }
    }
</script>