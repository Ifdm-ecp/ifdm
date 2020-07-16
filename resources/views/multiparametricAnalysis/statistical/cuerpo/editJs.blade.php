<script type="text/javascript">
    $(function() {
        $(".jquery-datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy"
        });
    });

    function multiparametricoStatistical() {
        var count = <?php echo count($errors) ?>;
        var nps = [];
        var d;
        var p50;

        if("{{ $statistical->statistical}}" != "Colombia") {
            var dataarray = "{{ $statistical->field_statistical }}";
        } else {
            var dataarray = "Todos";
        }
        
        $.get("{!! url('P') !!}", {
            campo: dataarray,
        }, 
        function(data) { 
            $.each(data, function(index, value) {
                nps = [];
                $.each(value, function(index, value) {
                    d = parseFloat(value.valorchart);
                    d = Math.round(d * 100) / 100;
                    nps.push(d);
                });
                tam = 0;
                tam = nps.length;
                //console.log(tam);
                if (tam == 0) {
                    p10 = 0;
                    p50 = 0;
                    p90 = 0;
                } else {
                    p10 = nps[Math.floor(tam * 0.1)];
                    p50 = nps[Math.floor(tam * 0.5)];
                    p90 = nps[Math.floor(tam * 0.9)];
                }

                if (count < 1) {
                    $("#p10_" + index).val(p10);
                    $("#p90_" + index).val(p90);
                }

                $("#popover" + index).popover({
                    placement: 'top',
                    html: 'true',
                    title: '<span class="text-info"><strong>Percentile     </strong></span>',
                    content: '<b>p10: </b>' + p10 + '<br><b>p50: </b>' + p50 + '<br><b>p90: </b>' + p90
                });
            });
        });
    }

    function cargarCamposBBDD() {
        $('#basin > option[value="{{ $statistical->basin_statistical }}"]').attr('selected', 'selected');

        //Cargar valores de select anidados basados en opcion escogida
        $.get("{{url('fieldbybasin')}}", {
            basin: "{{ $statistical->basin_statistical }}"
        }, function(data) {
            $("#field").empty();

            $.each(data, function(index, value) {
                $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            $("#field").selectpicker('refresh');
            var data2 = "{{ $statistical->field_statistical }}";
            var dataarray = data2.split(",");
            $('#field').selectpicker('val', dataarray);
        });
    }

    //Asignar color a input en caso de que un valor este malo
    $('.check_weight').bind('init', function() {
        var this_name = '#' + $(this).attr('id');
        var this_value_div = '#' + $(this).attr('id') + '_div';
        if ($(this_name).prop('checked')) {
            $(this_name + '_hidden').val("false");
            $(this_value_div + " *").attr('disabled', false);
        } else {
            $(this_value_div + " *").attr('disabled', true);
            $(this_name + '_hidden').val("true");
        }
    }).trigger('init');

    $('.check_weight').bind('change', function() {
        var this_name = '#' + $(this).attr('id');
        var this_value_div = '#' + $(this).attr('id') + '_div';
        if ($(this_name).prop('checked')) {
            $(this_name + '_hidden').val("false");
            $(this_value_div + " *").attr('disabled', false);
            $(this_value_div + ' .ms-subparameter-picker').attr('disabled', false);
            $(this_value_div + ' .ms-subparameter-picker').selectpicker('refresh');
        } else {
            $(this_value_div + " *").attr('disabled', true);
            $(this_name + '_hidden').val("true");
            $(this_value_div + ' .ms-subparameter-picker').attr('disabled', true);
            $(this_value_div + ' .ms-subparameter-picker').selectpicker('refresh');
        }
    });

    $('#OS4').bind('change', function() {
        $('#RP1').val($(this).val());
    });

    $('#RP1').bind('change', function() {
        $('#OS4').val($(this).val());
    });

    function cargarAvailables() {
        var ms = {{json_encode($statistical->msAvailable)}};
        var fb = {{json_encode($statistical->fbAvailable)}};
        var os = {{json_encode($statistical->osAvailable)}};     
        var rp = {{json_encode($statistical->rpAvailable)}};     
        var id = {{json_encode($statistical->idAvailable)}};     
        var gd = {{json_encode($statistical->gdAvailable)}}; 

        $.each(ms, function(index, value) {
            $('#weight_ms_'+ value).prop("checked", true);
            $('#weight_ms_'+ value + '_div *').attr('disabled', false);
        });

        $.each(fb, function(index, value) {
            $('#weight_fb_'+ value).prop("checked", true);
            $('#weight_fb_'+ value + '_div *').attr('disabled', false);
        });

        $.each(os, function(index, value) {
            $('#weight_os_'+ value).prop("checked", true);
            $('#weight_os_'+ value + '_div *').attr('disabled', false);
        });

        $.each(rp, function(index, value) {
            $('#weight_rp_'+ value).prop("checked", true);
            $('#weight_rp_'+ value + '_div *').attr('disabled', false);
        });

        $.each(id, function(index, value) {
            $('#weight_id_'+ value).prop("checked", true);
            $('#weight_id_'+ value + '_div *').attr('disabled', false);
        });

        $.each(gd, function(index, value) {
            $('#weight_gd_'+ value).prop("checked", true);
            $('#weight_gd_'+ value + '_div *').attr('disabled', false);
        });
    }

    // Load all the subparameter historical data available for each subparameter
    function loadSubparametersHistoricalByWell() {
        $.get("{{url('subparameterbywell')}}", {
            pozoId: "{{ $pozoId }}"
        }, function(data) {
            $.each(data, function(index, value) {
                var textValue = value.valor + ' - ' + moment(value.fecha).format('DD/MM/YYYY');
                $(".select-stored-" + value.subparametro_id).append('<option value="' + textValue + '">' + textValue + '</option>');
            });

            $(".ms-subparameter-picker").selectpicker('render');
            $(".ms-subparameter-picker").selectpicker('refresh');
        });
    }

    // Load index subparameters data based in the well
    $(".ms-subparameter-picker").change(function() {
        var idSelected = $(this).attr('id');
        var parameterData = $(this).val().split(' - ');

        var parameterAffected = idSelected.substr(idSelected.length - 3);

        $("#" + parameterAffected).val(parameterData[0]);
        $("#date" + parameterAffected).val(parameterData[1]);
    });

    /* verifyMultiparametric
    * Validates the form entirely
    * params {action: string}
    */
    function verifyMultiparametric(action) {
        // Boolean for empty values for the save button
        var emptyValues = false;
        // An action is mandatory for the validations in this case action = run to validate against required fields
        var action = "run";
        // Title tab for modal errors
        var titleTab = "";
        var tabTitle = "";
        //Saving tables...
        var validationMessages = [];
        var validationFunctionResult = [];

        // Validating Mineral Scales
        tabTitle = "Tab: Mineral Scales";

        // Scale Index Of CaCO3
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS1").val(), mineral_scales_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS1").val() === null || $("#MS1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS1").val(), mineral_scales_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateMS1").val() === null || $("#dateMS1").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS1comment").val(), mineral_scales_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS1comment").val() === null || $("#MS1comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_27").val(), mineral_scales_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_27").val() === null || $("#p10_27").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_1").val(), mineral_scales_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_1").val() === null || $("#p90_1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_1_value").val(), mineral_scales_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_1_value").val() === null || $("#weight_1_value").val() === "")) ? true: emptyValues;

        // Scale Index Of BaSO4
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS2").val(), mineral_scales_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS2").val() === null || $("#MS2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS2").val(), mineral_scales_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateMS2").val() === null || $("#dateMS2").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS2comment").val(), mineral_scales_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS2comment").val() === null || $("#MS2comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_2").val(), mineral_scales_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_2").val() === null || $("#p10_2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_2").val(), mineral_scales_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_2").val() === null || $("#p90_2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_2_value").val(), mineral_scales_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_2_value").val() === null || $("#weight_2_value").val() === "")) ? true: emptyValues;

        // Scale Index Of Iron Scales
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS3").val(), mineral_scales_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS3").val() === null || $("#MS3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS3").val(), mineral_scales_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateMS3").val() === null || $("#dateMS3").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS3comment").val(), mineral_scales_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS3comment").val() === null || $("#MS3comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_3").val(), mineral_scales_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_3").val() === null || $("#p10_3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_3").val(), mineral_scales_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_3").val() === null || $("#p90_3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_3_value").val(), mineral_scales_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_3_value").val() === null || $("#weight_3_value").val() === "")) ? true: emptyValues;

        // Backflow [Ca] (ppm)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS4").val(), mineral_scales_tab_ruleset[18]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS4").val() === null || $("#MS4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS4").val(), mineral_scales_tab_ruleset[19]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateMS4").val() === null || $("#dateMS4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS4comment").val(), mineral_scales_tab_ruleset[20]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS4comment").val() === null || $("#MS4comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_4").val(), mineral_scales_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_4").val() === null || $("#p10_4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_4").val(), mineral_scales_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_4").val() === null || $("#p90_4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_4_value").val(), mineral_scales_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_4_value").val() === null || $("#weight_4_value").val() === "")) ? true: emptyValues;
        
        // Backflow [Ba] (ppm)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS5").val(), mineral_scales_tab_ruleset[24]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS5").val() === null || $("#MS5").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS5").val(), mineral_scales_tab_ruleset[25]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateMS5").val() === null || $("#dateMS5").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS5comment").val(), mineral_scales_tab_ruleset[26]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#MS5comment").val() === null || $("#MS5comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_5").val(), mineral_scales_tab_ruleset[27]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_5").val() === null || $("#p10_5").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_5").val(), mineral_scales_tab_ruleset[28]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_5").val() === null || $("#p90_5").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_5_value").val(), mineral_scales_tab_ruleset[29]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_5_value").val() === null || $("#weight_5_value").val() === "")) ? true: emptyValues;

        var sumWeights = parseFloat($("#weight_1_value").val()) + parseFloat($("#weight_2_value").val()) + parseFloat($("#weight_3_value").val()) + parseFloat($("#weight_4_value").val()) + parseFloat($("#weight_5_value").val());
        if (sumWeights > 1) {
            if (titleTab == "") {
                titleTab = "Tab: Mineral Scales";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat("The plus of the input Weight of the section Mineral Scales is older (" + sumWeights + ")");
        }

        // Validating Fine Blockage
        titleTab = "";
        tabTitle = "Tab: Fine Blockage";

        // [Al] on Produced Water (ppm)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB1").val(), fine_blockage_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB1").val() === null || $("#FB1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB1").val(), fine_blockage_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateFB1").val() === null || $("#dateFB1").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB1comment").val(), fine_blockage_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB1comment").val() === null || $("#FB1comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_6").val(), fine_blockage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_6").val() === null || $("#p10_6").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_6").val(), fine_blockage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_6").val() === null || $("#p90_6").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_6_value").val(), fine_blockage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_6_value").val() === null || $("#weight_6_value").val() === "")) ? true: emptyValues;

        // [Si] on produced water
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB2").val(), fine_blockage_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB2").val() === null || $("#FB2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB2").val(), fine_blockage_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateFB2").val() === null || $("#dateFB2").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB2comment").val(), fine_blockage_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB2comment").val() === null || $("#FB2comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_7").val(), fine_blockage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_7").val() === null || $("#p10_7").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_7").val(), fine_blockage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_7").val() === null || $("#p90_7").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_7_value").val(), fine_blockage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_7_value").val() === null || $("#weight_7_value").val() === "")) ? true: emptyValues;

        // Critical Radius derived from maximum critical velocity, Vc (ft)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB3").val(), fine_blockage_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB3").val() === null || $("#FB3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB3").val(), fine_blockage_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateFB3").val() === null || $("#dateFB3").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB3comment").val(), fine_blockage_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB3comment").val() === null || $("#FB3comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_8").val(), fine_blockage_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_8").val() === null || $("#p10_8").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_8").val(), fine_blockage_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_8").val() === null || $("#p90_8").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_8_value").val(), fine_blockage_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_8_value").val() === null || $("#weight_8_value").val() === "")) ? true: emptyValues;

        // Mineralogy Factor
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB4").val(), fine_blockage_tab_ruleset[18]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB4").val() === null || $("#FB4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB4").val(), fine_blockage_tab_ruleset[19]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateFB4").val() === null || $("#dateFB4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB4comment").val(), fine_blockage_tab_ruleset[20]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB4comment").val() === null || $("#FB4comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_9").val(), fine_blockage_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_9").val() === null || $("#p10_9").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_9").val(), fine_blockage_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_9").val() === null || $("#p90_9").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_9_value").val(), fine_blockage_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_9_value").val() === null || $("#weight_9_value").val() === "")) ? true: emptyValues;
        
        // Mass of crushed proppant inside Hydraulic Fractures (lbs)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB5").val(), fine_blockage_tab_ruleset[24]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB5").val() === null || $("#FB5").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB5").val(), fine_blockage_tab_ruleset[25]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateFB5").val() === null || $("#dateFB5").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB5comment").val(), fine_blockage_tab_ruleset[26]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#FB5comment").val() === null || $("#FB5comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_10").val(), fine_blockage_tab_ruleset[27]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_10").val() === null || $("#p10_10").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_10").val(), fine_blockage_tab_ruleset[28]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_10").val() === null || $("#p90_10").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_10_value").val(), fine_blockage_tab_ruleset[29]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_10_value").val() === null || $("#weight_10_value").val() === "")) ? true: emptyValues;

        sumWeights = parseFloat($("#weight_6_value").val()) + parseFloat($("#weight_7_value").val()) + parseFloat($("#weight_8_value").val()) + parseFloat($("#weight_9_value").val()) + parseFloat($("#weight_10_value").val());
        if (sumWeights > 1) {
            if (titleTab == "") {
                titleTab = "Tab: Fine Blockage";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat("The plus of the input Weight of the section Fine Blockage is older (" + sumWeights + ")");
        }
        
        // Validating Organic Scales
        titleTab = "";
        tabTitle = "Tab: Organic Scales";

        // CII Factor: Colloidal Instability Index
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS1").val(), organic_scales_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS1").val() === null || $("#OS1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS1").val(), organic_scales_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateOS1").val() === null || $("#dateOS1").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS1comment").val(), organic_scales_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS1comment").val() === null || $("#OS1comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_11").val(), organic_scales_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_11").val() === null || $("#p10_11").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_11").val(), organic_scales_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_11").val() === null || $("#p90_11").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_11_value").val(), organic_scales_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_11_value").val() === null || $("#weight_11_value").val() === "")) ? true: emptyValues;

        // Volume of HCL pumped into the formation (bbl)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS2").val(), organic_scales_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS2").val() === null || $("#OS2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS2").val(), organic_scales_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateOS2").val() === null || $("#dateOS2").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS2comment").val(), organic_scales_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS2comment").val() === null || $("#OS2comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_30").val(), organic_scales_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_30").val() === null || $("#p10_30").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_30").val(), organic_scales_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_30").val() === null || $("#p90_30").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_30_value").val(), organic_scales_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_30_value").val() === null || $("#weight_30_value").val() === "")) ? true: emptyValues;

        // Cumulative Gas Produced
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS3").val(), organic_scales_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS3").val() === null || $("#OS3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS3").val(), organic_scales_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateOS3").val() === null || $("#dateOS3").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS3comment").val(), organic_scales_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS3comment").val() === null || $("#OS3comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_12").val(), organic_scales_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_12").val() === null || $("#p10_12").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_12").val(), organic_scales_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_12").val() === null || $("#p90_12").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_12_value").val(), organic_scales_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_12_value").val() === null || $("#weight_12_value").val() === "")) ? true: emptyValues;

        // Number Of Days Below Saturation Pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS4").val(), organic_scales_tab_ruleset[18]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS4").val() === null || $("#OS4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS4").val(), organic_scales_tab_ruleset[19]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateOS4").val() === null || $("#dateOS4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS4comment").val(), organic_scales_tab_ruleset[20]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS4comment").val() === null || $("#OS4comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_13").val(), organic_scales_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_13").val() === null || $("#p10_13").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_13").val(), organic_scales_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_13").val() === null || $("#p90_13").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_13_value").val(), organic_scales_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_13_value").val() === null || $("#weight_13_value").val() === "")) ? true: emptyValues;
        
        // De Boer Criteria
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS5").val(), organic_scales_tab_ruleset[24]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS5").val() === null || $("#OS5").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS5").val(), organic_scales_tab_ruleset[25]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateOS5").val() === null || $("#dateOS5").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS5comment").val(), organic_scales_tab_ruleset[26]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#OS5comment").val() === null || $("#OS5comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_14").val(), organic_scales_tab_ruleset[27]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_14").val() === null || $("#p10_14").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_14").val(), organic_scales_tab_ruleset[28]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_14").val() === null || $("#p90_14").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_14_value").val(), organic_scales_tab_ruleset[29]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_14_value").val() === null || $("#weight_14_value").val() === "")) ? true: emptyValues;
        
        sumWeights = parseFloat($("#weight_11_value").val()) + parseFloat($("#weight_30_value").val()) + parseFloat($("#weight_12_value").val()) + parseFloat($("#weight_13_value").val()) + parseFloat($("#weight_14_value").val());
        if (sumWeights > 1) {
            if (titleTab == "") {
                titleTab = "Tab: Organic Scales";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat("The plus of the input Weight of the section Organic Scales is older (" + sumWeights + ")");
        }
        
        // Validating Relative Permeability
        titleTab = "";
        tabTitle = "Tab: Relative Permeability";

        // Number Of Days Below Saturation Pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP1").val(), relative_permeability_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP1").val() === null || $("#RP1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP1").val(), relative_permeability_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateRP1").val() === null || $("#dateRP1").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP1comment").val(), relative_permeability_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP1comment").val() === null || $("#RP1comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_15").val(), relative_permeability_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_15").val() === null || $("#p10_15").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_15").val(), relative_permeability_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_15").val() === null || $("#p90_15").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_15_value").val(), relative_permeability_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_15_value").val() === null || $("#weight_15_value").val() === "")) ? true: emptyValues;

        // Delta Pressure From Saturation Pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP2").val(), relative_permeability_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP2").val() === null || $("#RP2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP2").val(), relative_permeability_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateRP2").val() === null || $("#dateRP2").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP2comment").val(), relative_permeability_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP2comment").val() === null || $("#RP2comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_16").val(), relative_permeability_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_16").val() === null || $("#p10_16").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_16").val(), relative_permeability_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_16").val() === null || $("#p90_16").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_16_value").val(), relative_permeability_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_16_value").val() === null || $("#weight_16_value").val() === "")) ? true: emptyValues;

        // Cumulative Water Produced
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP3").val(), relative_permeability_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP3").val() === null || $("#RP3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP3").val(), relative_permeability_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateRP3").val() === null || $("#dateRP3").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP3comment").val(), relative_permeability_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP3comment").val() === null || $("#RP3comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_17").val(), relative_permeability_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_17").val() === null || $("#p10_17").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_17").val(), relative_permeability_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_17").val() === null || $("#p90_17").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_17_value").val(), relative_permeability_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_17_value").val() === null || $("#weight_17_value").val() === "")) ? true: emptyValues;

        // Pore Size Diameter Approximation By Katz And Thompson Correlation
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP4").val(), relative_permeability_tab_ruleset[18]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP4").val() === null || $("#RP4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP4").val(), relative_permeability_tab_ruleset[19]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateRP4").val() === null || $("#dateRP4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP4comment").val(), relative_permeability_tab_ruleset[20]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP4comment").val() === null || $("#RP4comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_18").val(), relative_permeability_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_18").val() === null || $("#p10_18").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_18").val(), relative_permeability_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_18").val() === null || $("#p90_18").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_18_value").val(), relative_permeability_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_18_value").val() === null || $("#weight_18_value").val() === "")) ? true: emptyValues;
        
        sumWeights = parseFloat($("#weight_15_value").val()) + parseFloat($("#weight_16_value").val()) + parseFloat($("#weight_17_value").val()) + parseFloat($("#weight_18_value").val());
        if (sumWeights > 1) {
            if (titleTab == "") {
                titleTab = "Tab: Relative Permeability";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat("The plus of the input Weight of the section Relative Permeability is older (" + sumWeights + ")");
        }
        
        // Validating Induce Damage
        titleTab = "";
        tabTitle = "Tab: Induce Damage";

        // Gross Pay (ft)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID1").val(), induce_damage_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID1").val() === null || $("#ID1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID1").val(), induce_damage_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateID1").val() === null || $("#dateID1").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID1comment").val(), induce_damage_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID1comment").val() === null || $("#ID1comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_19").val(), induce_damage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_19").val() === null || $("#p10_19").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_19").val(), induce_damage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_19").val() === null || $("#p90_19").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_19_value").val(), induce_damage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_19_value").val() === null || $("#weight_19_value").val() === "")) ? true: emptyValues;

        // Total polymer pumped during Hydraulic Fracturing (lbs)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID2").val(), induce_damage_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID2").val() === null || $("#ID2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID2").val(), induce_damage_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateID2").val() === null || $("#dateID2").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID2comment").val(), induce_damage_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID2comment").val() === null || $("#ID2comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_20").val(), induce_damage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_20").val() === null || $("#p10_20").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_20").val(), induce_damage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_20").val() === null || $("#p90_20").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_20_value").val(), induce_damage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_20_value").val() === null || $("#weight_20_value").val() === "")) ? true: emptyValues;

        // Total volume of water based fluids pumped into the well (bbl)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID3").val(), induce_damage_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID3").val() === null || $("#ID3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID3").val(), induce_damage_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateID3").val() === null || $("#dateID3").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID3comment").val(), induce_damage_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID3comment").val() === null || $("#ID3comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_21").val(), induce_damage_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_21").val() === null || $("#p10_21").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_21").val(), induce_damage_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_21").val() === null || $("#p90_21").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_21_value").val(), induce_damage_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_21_value").val() === null || $("#weight_21_value").val() === "")) ? true: emptyValues;

        // Mud Losses
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID4").val(), induce_damage_tab_ruleset[18]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID4").val() === null || $("#ID4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID4").val(), induce_damage_tab_ruleset[19]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateID4").val() === null || $("#dateID4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID4comment").val(), induce_damage_tab_ruleset[20]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#ID4comment").val() === null || $("#ID4comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_22").val(), induce_damage_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_22").val() === null || $("#p10_22").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_22").val(), induce_damage_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_22").val() === null || $("#p90_22").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_22_value").val(), induce_damage_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_22_value").val() === null || $("#weight_22_value").val() === "")) ? true: emptyValues;
        
        sumWeights = parseFloat($("#weight_19_value").val()) + parseFloat($("#weight_20_value").val()) + parseFloat($("#weight_21_value").val()) + parseFloat($("#weight_22_value").val());
        if (sumWeights > 1) {
            if (titleTab == "") {
                titleTab = "Tab: Induced Damage";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat("The plus of the input Weight of the section Induced Damage is older (" + sumWeights + ")");
        }
        
        // Validating Geomechanical Damage
        titleTab = "";
        tabTitle = "Tab: Geomechanical Damage";

        // Percentage of Net Pay exihibiting Natural (fraction)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD1").val(), geomechanical_damage_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD1").val() === null || $("#GD1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD1").val(), geomechanical_damage_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateGD1").val() === null || $("#dateGD1").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD1comment").val(), geomechanical_damage_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD1comment").val() === null || $("#GD1comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_23").val(), geomechanical_damage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_23").val() === null || $("#p10_23").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_23").val(), geomechanical_damage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_23").val() === null || $("#p90_23").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_23_value").val(), geomechanical_damage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_23_value").val() === null || $("#weight_23_value").val() === "")) ? true: emptyValues;

        // Drawdown, i.e, reservoir pressure minus BHFP (psi)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD2").val(), geomechanical_damage_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD2").val() === null || $("#GD2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD2").val(), geomechanical_damage_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateGD2").val() === null || $("#dateGD2").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD2comment").val(), geomechanical_damage_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD2comment").val() === null || $("#GD2comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_24").val(), geomechanical_damage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_24").val() === null || $("#p10_24").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_24").val(), geomechanical_damage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_24").val() === null || $("#p90_24").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_24_value").val(), geomechanical_damage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_24_value").val() === null || $("#weight_24_value").val() === "")) ? true: emptyValues;

        // Ratio of KH)matrix + fracture / KH)matrix
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD3").val(), geomechanical_damage_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD3").val() === null || $("#GD3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD3").val(), geomechanical_damage_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateGD3").val() === null || $("#dateGD3").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD3comment").val(), geomechanical_damage_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD3comment").val() === null || $("#GD3comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_25").val(), geomechanical_damage_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_25").val() === null || $("#p10_25").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_25").val(), geomechanical_damage_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_25").val() === null || $("#p90_25").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_25_value").val(), geomechanical_damage_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_25_value").val() === null || $("#weight_25_value").val() === "")) ? true: emptyValues;

        // Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD4").val(), geomechanical_damage_tab_ruleset[18]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD4").val() === null || $("#GD4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD4").val(), geomechanical_damage_tab_ruleset[19]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateGD4").val() === null || $("#dateGD4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD4comment").val(), geomechanical_damage_tab_ruleset[20]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#GD4comment").val() === null || $("#GD4comment").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_26").val(), geomechanical_damage_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_26").val() === null || $("#p10_26").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_26").val(), geomechanical_damage_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_26").val() === null || $("#p90_26").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_26_value").val(), geomechanical_damage_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_26_value").val() === null || $("#weight_26_value").val() === "")) ? true: emptyValues;

        sumWeights = parseFloat($("#weight_23_value").val()) + parseFloat($("#weight_24_value").val()) + parseFloat($("#weight_25_value").val()) + parseFloat($("#weight_26_value").val());
        if (sumWeights > 1) {
            if (titleTab == "") {
                titleTab = "Tab: Geomechanical Damage";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat("The plus of the input Weight of the section Geomechanical Damage is older (" + sumWeights + ")");
        }
        
        if (validationMessages.length < 1) {
            if (emptyValues) {
                validationMessages.push(true);
                showFrontendErrors(validationMessages);
            } else {
                $("#only_s").val("run");
                $("#multiparametricStatisticalForm").submit();
            }
        } else {
            showFrontendErrors(validationMessages);
        }
    }

    /* tabStep
    * After validating the current tab, it is changed to the next or previous tab depending on the
    * entry value
    * params {direction: string}
    */
    function tabStep(direction) {
        var tabToValidate = $(".nav.nav-tabs li.active a").attr("id");

        if (direction == "prev") {
            $(".nav.nav-tabs li.active").prev().children().click();
        } else {
            $(".nav.nav-tabs li.active").next().children().click();
        }

        $("#next_button").toggle($(".nav.nav-tabs li.active").next().is("li"));
        $("#prev_button").toggle($(".nav.nav-tabs li.active").prev().is("li"));
        $("#run_calc").toggle(!$(".nav.nav-tabs li.active").next().is("li"));
    }

    /* switchTab
    * Captures the tab clicking event to determine if a previous or next button has to be shown
    * and also the run button
    */
    function switchTab() {
        var event = window.event || arguments.callee.caller.arguments[0];
        var tabActiveElement = $(".nav.nav-tabs li.active");
        var nextPrevElement = $("#" + $(event.srcElement || event.originalTarget).attr('id')).parent();

        $("#next_button").toggle(nextPrevElement.next().is("li"));
        $("#prev_button").toggle(nextPrevElement.prev().is("li"));
        $("#run_calc").toggle(!nextPrevElement.next().is("li"));
    }

    /* saveForm
    * Submits the form when the confirmation button from the modal is clicked
    */
    function saveForm() {
        $("#only_s").val("save");
        $("#multiparametricStatisticalForm").submit();
    }
</script>