<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<!-- Libreria de graficación -->
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

<script type="text/javascript">
    var datos = {!! $data !!};
    var clicks = 0;
    var row_id = 0;
    var outflowcurve_ideal = [];

    @if ($IPR->fluido == 1)
    var name = "Oil";
    var medida = "Oil Rate [bbl/day]";
    var exact_medida = "[bbl/day]";
    @elseif ($IPR->fluido == 2)
    var name = "Dry Gas";
    var medida = "Gas Rate [MMscf/day]";
    var exact_medida = "[MMscf/day]";
    @elseif ($IPR->fluido == 3)
    var name = "Condensate Gas";
    var medida = "Gas Rate [MMscf/day]";
    var exact_medida = "[MMscf/day]";
    @elseif ($IPR->fluido == 4)
    var name = "Water";
    var medida = "Water Rate [bbl/day]";
    var exact_medida = "[bbl/day]";
    @elseif ($IPR->fluido == 5)
    var name = "Gas";
    var medida = "Gas Rate [MMscf/day]";
    var exact_medida = "[MMscf/day]";
    @endif

    $(document).ready(function() {
        draw();
    });

    /* *********Sección Funciones********* */
    /* Descripción: esta función ordena el contenido de la tabla pvt en todos los fluidos con base en los valores de la columna de presión. */
    /* Adicionales: aunque las pvt para aceite, gas y gas condensado son diferentes, la función sólo depende de la columna de presión para ordenar todos los datos. */
    function order_pvt(matrix) {
        var row_aux;
        for (var i = 0 ; i<matrix.length; i++) {
            for (var j=0; j<matrix.length; j++) {
                if(matrix[j][0]>matrix[i][0] && matrix[i][0]) {
                    row_aux = matrix[j];
                    matrix[j] = matrix[i];
                    matrix[i] = row_aux;
                }
            }   
        }
        return matrix;
    }

    function draw() {
        var data_series = [{
            name: 'Current IPR',
            data:  datos,
            marker: {
                enabled: false
            } 
        },{
            name: 'Skin = 0 (ideal)',
            data:  {!! $data_i !!},
            marker: { 
                enabled: false 
            }
        }];

        @if(floatval($tasa_flujo) != 0.000001 && floatval($presion_fondo) != 0.000001)
        var production_data = {
            name: 'Production Data',
            data:  [[{!! $tasa_flujo !!},{!! $presion_fondo !!}]],
            marker: { 
                enabled: true
            }
        };
        data_series.push(production_data);
        @endif
        @foreach($intervalos as $int)
        
        var ipr_0 = JSON.parse("{{ json_encode($int['ipr'][0]) }}");
        var ipr_1 = JSON.parse("{{ json_encode($int['ipr'][1]) }}");

        var ipr_cero_0 = JSON.parse("{{ json_encode($int['ipr_cero'][0]) }}");
        var ipr_cero_1 = JSON.parse("{{ json_encode($int['ipr_cero'][1]) }}");

        var datos_ipr = [];
        var datos_ipr_cero = [];
        for (var i = 0; i < ipr_0.length; i++) {
            datos_ipr.push([ipr_0[i],ipr_1[i]]);
            datos_ipr_cero.push([ipr_cero_0[i],ipr_cero_1[i]]);
        }

        data_series.push({
            name: '{{ $int['nombre'] }}',
            data:  datos_ipr,
            marker: { 
                enabled: false
            }
        });

        data_series.push({
            name: '{{ $int['nombre'] }} - Skin 0',
            data:  datos_ipr_cero,
            marker: { 
                enabled: false
            }
        });
        @endforeach

        setTimeout(function() {
            $('#grafica').highcharts({
                chart: {
                    type: 'spline',
                    scrollablePlotArea: {
                        minWidth: 600,
                        scrollPositionX: 1
                    },
                    zoomType: 'xy'
                },

                title: {
                    text: 'Bottomhole Flowing Pressure Vs. '+ name +' Rate',
                    x: -20 /* center */
                },
                credits: {
                    enabled: false
                },

                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br />',
                    pointFormat: 'x = {point.x}, y = {point.y}'
                },
                xAxis: {
                    title: {
                        text: medida
                    },
                },
                yAxis: {
                    title: {
                        text: 'Bottomhole Flowing Pressure [psi]'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }],
                    labels: {
                        format: '{value:,.0f}'
                    }
                },
                series: data_series
            });
        }, 500);

    }
</script>