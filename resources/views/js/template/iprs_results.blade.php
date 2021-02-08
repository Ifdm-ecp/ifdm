<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<!-- Libreria de graficación -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>
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
        startTableOutFlowCurve();
        $('#btn_active_skin').on('click', function(event) {
            event.preventDefault();

            if (clicks == 0) {
                $('#skin_sens').show();
                addSensibility();
                clicks++;
            }
        });

        $('#btn_active_others_var').on('click', function(event) {
            event.preventDefault();
            var nodo = $(this);
            if (clicks > 0) {
                var opcion = confirm("You will be redirected to a new window. Are you sure you want to get out of here?");
                if (opcion == true) {
                    location.href = nodo.attr('href');
                }
            } else {
                location.href = nodo.attr('href');
            }
        });

        $('#operative_point').collapse("hide");
    });

    function draw_again(data) {
        ser = new Array();
        var tamano = data.etiquetas_ejes.length;
        for (var i = 0; i < tamano; i++) {
            if(data.etiquetas_ejes[i] == "Production Data") {
                ser.push({name: data.etiquetas_ejes[i], data: data.ejes_dobles[i], marker:{enabled:true} });
            } else {
                ser.push({name: data.etiquetas_ejes[i], data: data.ejes_dobles[i]});
            }
        }

        $('#grafica').highcharts({
            chart: {
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
                    text: '{!! $IPR->fluido == 1 ? "Oil Rate [bbl/day]" : "Gas Rate [MMscf/day]" !!}'
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
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                }
            },
            series: ser
        });   
    }

    function addSensibility() {

        if(row_id > 9) {
            alert('Only 10 [ Skin ] are allowed.')
            return false;
        }

        if(!validateSensibilityList()){
            return false;
        }

        var row = '<div class="row" style="border-bottom: 1px solid #eee; margin-bottom: 10px;">' +
        '<div class="col-md-5">' +
        '<div class="form-group">' +
        '<label for="valor">Skin Value</label>' +
        '<input type="text" class="form-control" placeholder="Enter Skin Value" title="Only valid numbers are allowed." onkeyup="validateSensibilityValue(this);" name="skin" id="skin_'+row_id+'">' +
        '</div>' +
        '</div>' +
        '<div class="col-md-5">' +
        '<div class="form-group">' +
        '<label for="valor">Operative Point</label>' +
        '<input type="text" class="form-control" placeholder="" disabled title="Only valid numbers are allowed." onkeyup="validateSensibilityValue(this);" name="operative_point" id="'+row_id+'_operative_point">' +
        '<input type="hidden" name="d_categorias_ideal" id="'+row_id+'_d_categorias_ideal">' +
        '<input type="hidden" name="d_eje_y_ideal" id="'+row_id+'_d_eje_y_ideal">' +
        '<input type="hidden" name="d_categorias_current" id="'+row_id+'_d_categorias_current">' +
        '<input type="hidden" name="d_eje_y_current" id="'+row_id+'_d_eje_y_current">' +
        '</div>' +
        '</div>' +
        '<div class="col-md-2">' +
        '<div class="form-group">' +
        '<label for="valor"> </label>' +
        '<div class="input-group">' +
        '<button style="margin-top: 2px;" class="btn btn-danger btn-sm" name="remover" onclick="removeToGraph(this);" type="button" title="Remove sensitivity to the graph"><i class="fa fa-times"></i></button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

        $('#Sensitivities_list').append(row);
        $('#skin_'+row_id).focus();
        row_id++;
    }

    function startTableOutFlowCurve() {
        $excel_tabular_pvt_fluid_c_g = $('#tablaprueba');
        $excel_tabular_pvt_fluid_c_g.handsontable({
            colWidths: [150, 200] ,
            rowHeaders: true, 
            columns: [
            {title:"Pressure [psi] ", data: 0,type: 'numeric', format: '0[.]0000000'},
            {title:medida, data: 1,type: 'numeric', format: '0[.]0000000'},
            ],
            startRows: 6,
            minSpareRows: 2,
            contextMenu: true,
        });
    }

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

    function validateSensibilityList() {
        var rows = $('#Sensitivities_list').find('div.row');
        var mensaje = '';

        $.each(rows, function(index, val) {
            var nodo = $(val);
            var valor = nodo.find('[name=skin]');
            var operative_point = nodo.find('[name=operative_point]');

            operative_point.val('');

            if (valor.val() == '') {
                mensaje = 'There are fields with sensitivity or empty value.';
                return false;
            }
        });

        if(mensaje != ''){
            alert(mensaje);
            return false;
        } else {
            return true;
        }
    }

    function validateSensibilityValue(node) {
        var nodo = $(node);
        if(nodo.val() != '' && !isNumeric(nodo.val())){
            nodo.parent().addClass('has-error');
        } else {
            nodo.parent().removeClass('has-error');
        }
    }

    function removeToGraph(node) {
        var nodo = $(node);
        var row = nodo.parents('div.col-md-2').parent();
        row.remove();
    }

    function limpiar() {
        var rows = $('#Sensitivities_list').find('div.row');
        rows.remove();

        $('#skin_sens').hide();
        clicks--;
    }

    function draw() {
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
            series: [{
                name: 'Current IPR',
                data:  datos,
                marker: {
                    enabled: false
                } 
            },
            @if(floatval($tasa_flujo) != 0.000001 && floatval($presion_fondo) != 0.000001)
            {
                name: 'Production Data',
                data:  [[{!! $tasa_flujo !!},{!! $presion_fondo !!}]],
                marker: { 
                    enabled: true
                }
            }, 
            @endif
            {
                name: 'Skin = 0 (ideal)',
                data:  {!! $data_i !!},
                marker: { 
                    enabled: false 
                }
            }]
        });
    }

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function getSensibilities() {
        var row = $('#Sensitivities_list').find('div.row');
        if(row.find('.has-error').length > 0){
            alert("Please check the fields marked with red.");
            return false;
        }

        var sensibilidades = [];
        $.each(row, function(index, val) {
            var nodo = $(val);

            var valor = nodo.find('[name=skin]');
            // var operative_point = nodo.find('[name=operative_point]');

            sensibilidades.push({
                sensibilidad: 'factor_dano', 
                valor: valor.val()
            });
        });
        return sensibilidades;
    }

    function enviar() {

        if(!validateSensibilityList() && !sensibilidades) {
            return false;
        }

        var sensibilidades = getSensibilities();
        if(!sensibilidades) {
            return false;
        }

        document.getElementById('loading').style.display = 'block';

        $.ajax({
            url: "{!! url('IPR/sensibility') !!}",
            data: {
                id_ipr: {!! $IPR->id !!},
                sensibilidades: sensibilidades,
                outflowcurve: outflowcurve_ideal
            },
        })
        .done(function(data) {
            $.each(data.ipr_status, function(index, val) {
                $('#'+index+'_d_categorias_ideal').val(JSON.stringify(val.ideal[0]));
                $('#'+index+'_d_eje_y_ideal').val(JSON.stringify(val.ideal[1]));

                $('#'+index+'_d_categorias_current').val(JSON.stringify(val.current[0]));
                $('#'+index+'_d_eje_y_current').val(JSON.stringify(val.current[1]));
            });
            draw_again(data);
            $('#sticky').focus();
        })
        .fail(function(data) {
            console.log("error",data);
            alert("Please verify your sensitivity input");
        });

        setTimeout(function() {
            document.getElementById('loading').style.display = 'none';
        }, 1500);

        return false;
    }

    function calcularOutflow (argument) {

        var mensaje = false;
        var cantidad = 0;

        var rates_outflow = [];
        var pressures_outflow = [];

        var data_table = $("#tablaprueba").handsontable('getData');
        // var data_table = order_pvt($("#tablaprueba").handsontable('getData'));
        $.each(data_table, function(index, val) {
            var pressure = val[0];
            var rate = val[1];

            if(!(pressure == null && rate == null) && (pressure == null || rate == null)) {

                mensaje = 'All rows must contain a value.';
                return false;

            } else if(!(pressure == null && rate == null)) {

                rates_outflow.push( val[1] );
                pressures_outflow.push( val[0] );
                cantidad++;

            }
        });

        if(cantidad <= 1 && mensaje == '') {
            mensaje = 'At least two row is needed to perform the calculation.';
        }

        if(mensaje){
            alert(mensaje);
            return false;
        } else {

            var rates_inflow_ideal = $('#rates_inflow_ideal').val();
            var pressures_inflow_ideal = $('#pressures_inflow_ideal').val();

            outflowcurve_ideal = {
                rates_inflow: rates_inflow_ideal,
                pressures_inflow: pressures_inflow_ideal,
                rates_outflow: JSON.stringify(rates_outflow),
                pressures_outflow: JSON.stringify(pressures_outflow)
            };

            var rates_inflow_current = $('#rates_inflow_current').val();
            var pressures_inflow_current = $('#pressures_inflow_current').val();

            outflowcurve_current = {
                rates_inflow: rates_inflow_current,
                pressures_inflow: pressures_inflow_current,
                rates_outflow: JSON.stringify(rates_outflow),
                pressures_outflow: JSON.stringify(pressures_outflow)
            };

            calculateOperative(outflowcurve_ideal,'ideal');
            calculateOperative(outflowcurve_current,'current');

            enviar();

            var gsens = getSensibilities();

            for (var i = 0; i < gsens.length; i++) {
                var rates_inflow_ideal_for = $('#'+i+'_d_categorias_ideal').val();
                var pressures_inflow_ideal_for = $('#'+i+'_d_eje_y_ideal').val();

                var outflowcurve_ideal_for = {
                    rates_inflow: rates_inflow_ideal_for,
                    pressures_inflow: pressures_inflow_ideal_for,
                    rates_outflow: JSON.stringify(rates_outflow),
                    pressures_outflow: JSON.stringify(pressures_outflow)
                };

                // calculateOperative(outflowcurve_ideal_for,i);

                /* --------------------------------- */

                var rates_inflow_current_for = $('#'+i+'_d_categorias_current').val();
                var pressures_inflow_current_for = $('#'+i+'_d_eje_y_current').val();

                var outflowcurve_current_for = {
                    rates_inflow: rates_inflow_current_for,
                    pressures_inflow: pressures_inflow_current_for,
                    rates_outflow: JSON.stringify(rates_outflow),
                    pressures_outflow: JSON.stringify(pressures_outflow)
                };

                calculateOperative(outflowcurve_current_for,i);
            }

        }
    }

    function calculateOperative(outflowcurve,id) {
        document.getElementById('loading').style.display = 'block';
        $.ajax({
            url: "{!! url('IPR/operativePointGet') !!}",
            type: 'POST',
            data: outflowcurve,
        })
        .done(function(datos) {
            console.log("success: ");
            console.log(datos);
            setTimeout(function() {
                if (typeof id == 'number' ) {
                    var skin = $('#skin_'+id).val();
                    $('#grafica').highcharts().addSeries({ data: [datos],name: 'Operative Point Skin ' + skin, marker: { enabled: true } });
                } else {
                    $('#grafica').highcharts().addSeries({ data: [datos],name: 'Operative Point Skin ' + id, marker: { enabled: true } });
                }
                $('#'+id+'_operative_point').val(parseFloat(datos[0]).toFixed(2)+' '+exact_medida);
                document.getElementById('loading').style.display = 'none';
            }, 1500);
        })
        .fail(function() {
            console.log("error");
        });
    }
</script>