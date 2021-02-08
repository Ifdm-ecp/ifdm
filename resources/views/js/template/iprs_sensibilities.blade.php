<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<!-- Libreria de graficación -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

<script type="text/javascript">

    var datos = {!! isset($data) ? $data : '{}' !!};
    var cantidad_tablas = 0;

    @if ($IPR->fluido == 1)
    var name = "Oil";
    var medida = "Oil Rate [bbl/day]";
    @elseif ($IPR->fluido == 2)
    var name = "Dry Gas";
    var medida = "Gas Rate [MMscf/day]";
    @elseif ($IPR->fluido == 3)
    var name = "Condensate Gas";
    var medida = "Gas Rate [MMscf/day]";
    @elseif ($IPR->fluido == 4)
    var name = "Water";
    var medida = "Water Rate [bbl/day]";
    @elseif ($IPR->fluido == 5)
    var name = "Gas";
    var medida = "Gas Rate [MMscf/day]";
    @endif

    $(document).ready(function() {
        draw();
        addSensibility();
        /* $('#results_sensibilities').hide(); */
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
        // if(!validateSensibilityList()){
        //     return false;
        // }

        var row = '<div class="row" style="border-bottom: 1px solid #eee; margin-bottom: 10px;">' +
        '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<label for="sensitivity_type">Sensitivity</label>' +
        '<select name="sensitivity_type" class="form-control">' +
        '<option value="" selected>Select a sensitivity</option>' +
        '<option value="netpay">Net Pay - hnet [ft]</option>' +
        '<option value="absolute_permeability" {{ $IPR->intervalo->stress_sensitive_reservoir == 2 ? 'disabled' : '' }}>Absolute Permeability - Kabs [md]</option>' +
        '<option value="bhp">Bottom Hole Pressure - BHP [psi]</option>' +
        '<option value="modulo_permeabilidad" {{ $IPR->intervalo->stress_sensitive_reservoir == 1 ? 'disabled' : '' }}>Permeability Module - Perm. Mod [1/psi]</option>' +
        '<option value="presion_separacion" {{ $IPR->well_Type == 1 ? 'disabled' : '' }}>Reservoir Parting Pressure - RPP [psi]</option>' +
        '<option value="reservoir_pressure">Reservoir Pressure - Pres [psi]</option>' +
        '<option value="radio_drenaje_yac">Drainage Radius - Re [ft]</option>' +
        '<option value="bsw" {!! $IPR->fluido == 1 ? "" : "disabled" !!}>Water Cut - BSW [-]</option>' +
        '<option value="corey" {{ isset($IPR->exponente_corey_petroleo) ? "" : "disabled" }}>Corey Exponent Oil [-]</option>' +
        '</select>' +
        '<div id="resultado"></div>' +
        '</div>' +
        '</div>' +
        '<div class="col-md-4">' +
        '<div class="form-group">' +
        '<label for="valor">Value</label>' +
        '<input type="text" class="form-control" placeholder="Enter Value" title="Only valid numbers are allowed." onkeyup="validateSensibilityValue(this);" name="valor" id="">' +
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
    }

    function startTableOutFlowCurve() {
        $excel_tabular_pvt_fluid_c_g = $('#tablaprueba');
        $excel_tabular_pvt_fluid_c_g.handsontable({
            colWidths: [150, 150] ,
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

    function startTableSensibilities(data) {

        $('#div_tabla').html('');
        if(data.titulos.length > 5) {
            alert('Only a maximum of 3 sensitivities is allowed.');
            return false;
        }
        $('#div_tabla').html('<h3>Sensibility Table</h3><div id="tabla_sensibilidades"</div>');

        $excel_tabular_pvt_fluid_c_g = $('#tabla_sensibilidades');
        $excel_tabular_pvt_fluid_c_g.handsontable({
            data: data.datos,
            colWidths: data.widths,
            rowHeaders: true, 
            colHeaders: true,
            columns: data.titulos,
            startRows: 2,
            className: "htCenter",
            contextMenu: true,
        });

        cantidad_tablas++;
        $('[data_id=0]').click();
    }

    function validateSensibilityList() {
        var rows = $('#Sensitivities_list').find('div.row');
        var mensaje = '';

        $.each(rows, function(index, val) {
            var nodo = $(val);
            var sensitivity = nodo.find('[name=sensitivity_type]');
            var valor = nodo.find('[name=valor]');

            if (sensitivity.val() == '' || valor.val() == '') {
                mensaje = 'There are fields with sensitivity or empty value.';
                return false;
            }

            if(sensitivity.val() == 'reservoir_parting_pressure' && (valor.val() < {{ $presion_fondo }} || valor.val() < {{ $IPR->intervalo->current_reservoir_pressure }}) ) {
                mensaje = 'The [ Reservoir Parting Pressure ] value must be greater than [ BHFP = '+parseFloat({{ $presion_fondo }})+' ] and [ Reservoir Pressure = '+parseFloat({{ $IPR->intervalo->current_reservoir_pressure }})+' ].';
                return false;
            }

            if(sensitivity.val() == 'reservoir_pressure' && {{ $IPR->well_Type }} == 1 && valor.val() < {{ $presion_fondo }}) {
                mensaje = 'The [ Reservoir Pressure ] value must be greater than [ BHFP = {{ $presion_fondo }} ].';
                return false;
            }

            if(sensitivity.val() == 'bhp' && {{ $IPR->well_Type }} == 2 && (valor.val() < {{ $IPR->intervalo->current_reservoir_pressure }} )) {
                var mens = '';

                @if(isset($IPR->intervalo->reservoir_parting_pressure) && !empty($IPR->intervalo->reservoir_parting_pressure))
                mens = " and must be less than [ Reservoir Parting Pressure = '+parseFloat({{ $IPR->intervalo->reservoir_parting_pressure }})+' ]"
                @endif

                mensaje = 'The [ BHP ] value must be greater than [ Reservoir Pressure = '+parseFloat({{ $IPR->intervalo->current_reservoir_pressure }})+' ] '+mens+'.';
                return false;

            } else if(sensitivity.val() == 'bhp' && {{ $IPR->well_Type }} == 1 && valor.val() > {{ $IPR->intervalo->current_reservoir_pressure }}) {

                mensaje = 'The [ BHP ] value must be less than [ Reservoir Pressure = '+parseFloat({{ $IPR->intervalo->current_reservoir_pressure }})+' ].';
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

    function graphValue(node) {
        var nodo = $(node);
        if(nodo.hasClass('btn-success')) {
            nodo.removeClass('btn-success');
        } else {
            nodo.addClass('btn-success');
        }

        var data = [];
        var botones = $('.btn_showgraph');
        $.each(botones, function(index, val) {
            var boton = $(val);
            if(boton.hasClass('btn-success')) {
                var ser = prepareData(boton);
                data = data.concat(ser);
            }
        });

        graphicate(data);
    }

    function prepareData(nodo) {
        var data = [];

        var skin = [];
        var cero = [];

        //console.log(nodo.attr('data_id'));

        var id_inputs = parseInt(nodo.attr('data_id'));
        var indice = (id_inputs + 1);

        //valores de leyenda
        var colHeaderList = $("#tabla_sensibilidades").handsontable("getColHeader");
        colHeaderList.pop();
        colHeaderList.pop();
        colHeaderList.reverse();

        var tableValueList = $("#tabla_sensibilidades").data('handsontable').getDataAtRow(indice-1);
        tableValueList.pop();
        var skin_val = tableValueList.pop();
        tableValueList.reverse();

        var antes = '';
        var final = '';
        var header_val = '';
        var data_val = 0;
        $.each(colHeaderList, function(val) {
            header_val = colHeaderList.pop();
            data_val = tableValueList.pop();
            if (header_val == 'Net Pay - hnet [ft]') {
                final = final + ', hnet = ' + data_val;
            } else if (header_val == 'Absolute Permeability - Kabs [md]') {
                final = final + ', Kabs = ' + data_val;
            } else if (header_val == 'Bottom Hole Pressure - BHP [psi]') {
                final = final + ', BHP = ' + data_val;
            } else if (header_val == 'Permeability Module - Perm. Mod [1/psi]') {
                final = final + ', Perm. Mod = ' + data_val;
            } else if (header_val == 'Reservoir Parting Pressure - RPP [psi]') {
                final = final + ', RPP = ' + data_val;
            } else if (header_val == 'Reservoir Pressure - Pres [psi]') {
                final = final + ', Pres = ' + data_val;
            } else if (header_val == 'Drainage Radius - Re [ft]') {
                final = final + ', Re = ' + data_val;
            } else if (header_val == 'Water Cut - BSW [-]') {
                final = final + ', BSW = ' + data_val;
            } else {  //hader_val == 'Corey Exponent Oil [-]'
                final = final + ', CE = ' + data_val;
            }
        });
        final = final.substring(1);

        var label_skin = 'Skin = ' + skin_val + ' |'+ final;

        var label_cero = 'Ideal |' + final;

        var data_skin = JSON.parse($('#hidden_data_'+id_inputs).val());
        var ejey_skin = JSON.parse($('#hidden_eje_'+id_inputs).val());

        var data_cero = JSON.parse($('#cero_hidden_data_'+id_inputs).val());
        var ejey_cero = JSON.parse($('#cero_hidden_eje_'+id_inputs).val());

        $.each(data_skin, function(index, d_skin) {
            var d_skin = d_skin;
            var y_skin = ejey_skin[index];

            var d_cero = data_cero[index];
            var y_cero = ejey_cero[index];

            skin.push([d_skin,y_skin]);
            cero.push([d_cero,y_cero]);
        });

        data.push(cero);
        data.push(skin);

        return [
        {name: label_cero, data: data[0]},
        {name: label_skin, data: data[1]}
        ];
    }

    function graphicate(data) {        
        $('#grafica').html('');
        $('#grafica').highcharts({
            title: {
                text: 'Bottomhole Flowing Pressure Vs. '+ name +' Rate',
                x: -20
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
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                }
            },
            series: data
        });   
        
        setTimeout(function() {
            $('#grafica').focus();
        }, 100);
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
        var sensitivity = row.find('[name=sensitivity_type]');
        var valor = row.find('[name=valor]');
        row.remove();
    }

    function limpiar() {
        var rows = $('#Sensitivities_list').find('div.row');
        rows.remove();
        addSensibility();
        draw_again({ etiquetas_ejes: [] });
        $('#results_sensibilities').hide();
    }

    function draw() {
        $('#grafica').highcharts({
            chart: {
                type: 'spline',
                scrollablePlotArea: {
                    minWidth: 600,
                    scrollPositionX: 1
                }
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
                data:  {!! isset($data_i) ? $data_i : '{}' !!},
                marker: { 
                    enabled: false 
                }
            }]
        });
    }

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function enviar() {

        if(!validateSensibilityList()) {
            return false;
        }

        var row = $('#Sensitivities_list').find('div.row');
        if(row.find('.has-error').length > 0){
            alert("Please check the fields marked with red.");
            return false;
        }

        var sensibilidades = [];
        $.each(row, function(index, val) {
            var nodo = $(val);
            var sensitivity = nodo.find('[name=sensitivity_type]');
            var valor = nodo.find('[name=valor]');
            sensibilidades.push({
                sensibilidad: sensitivity.val(), 
                valor: valor.val()
            });
        });

        document.getElementById('loading').style.display = 'block';

        $.ajax({
            url: "{!! url('IPR/sensibility_advanced') !!}",
            data: {
                id_ipr: {!! $IPR->id !!},
                sensibilidades: sensibilidades
            },
        })
        .done(function(data) {
            console.log("success");
            draw_again(data);
            startTableSensibilities(data.dataTable);

            $('#results_sensibilities').show();
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
</script>