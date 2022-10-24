<script type="text/javascript">
    $( document ).ready(function() {

        //ms blade
        addInputGroup('MS1', 'ScaleIndexOfCaCO3');
        addInputGroup('MS2', 'ScaleIndexOfBaSO4');
        addInputGroup('MS3', 'ScaleIndexOfIronScales');
        addInputGroup('MS4', 'BackflowCa');
        addInputGroup('MS5', 'BackflowBa');

        //fb blade
        addInputGroup('FB1', 'AlonProducedWater');
        addInputGroup('FB2', 'Sionproducedwater');
        addInputGroup('FB3', 'CriticalRadiusderivedfrommaximumcriticalvelocityVc');
        addInputGroup('FB4', 'MineralogyFactor');
        addInputGroup('FB5', 'MassofcrushedproppantinsideHydraulicFractures');
        
        //os blade
        addInputGroup('OS1', 'CIIFactorColloidalInstabilityIndex');
        addInputGroup('OS2', 'VolumeofHClpumpedintotheformation');
        addInputGroup('OS3', 'CumulativeGasProduced');
        addInputGroup('OS4', 'NumberOfDaysBelowSaturationPressure');
        addInputGroup('OS5', 'DeBoerCriteria');

        //rp blade
        addInputGroup('RP1', 'NumberOfDaysBelowSaturationPressure2');
        addInputGroup('RP2', 'Differencebetweencurrentreservoirpressureandsaturationpressure');
        addInputGroup('RP3', 'CumulativeWaterProduced');
        addInputGroup('RP4', 'PoreSizeDiameterApproximationByKatzAndThompsonCorrelation');
        addInputGroup('RP5', 'Velocityparameterestimatedastheinverseofthecriticalradius');
        
        //id blade
        addInputGroup('ID1', 'GrossPay');
        addInputGroup('ID2', 'TotalpolymerpumpedduringHydraulicFracturing');
        addInputGroup('ID3', 'Totalvolumeofwaterbasedfluidspumpedintothewell');
        addInputGroup('ID4', 'MudLosses');
        
        //gd blade
        addInputGroup('GD1', 'FractionofNetPayExihibitingNaturalFractures');
        addInputGroup('GD2', 'reservoirpressureminusBHFP');
        addInputGroup('GD3', 'RatioofKH');
        addInputGroup('GD4', 'GeomechanicalDamageExpressedAsFractionOfBasePermeabilityAtBHFP');

        //Calculate p10 and p90
        multiparametricoStatistical();

        //Fill Weights
        multiparametricWeights();
    });

    function addInputGroup(title, destination_div) {
        
        html = '<div role="tabpanel_formation"><ul class="nav nav-tabs" role="tablist">';
        flag = 0;
        Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
            name = title + <?php echo json_encode($formationsWithoutSpaces); ?>[key];

            if (flag == 0) {
                html = html + '<li role="presentation" class="nav active"><a data-toggle="tab" href="#tab' + name +'" id="tab' + name + '_D" role="tab">' + <?php echo json_encode($formations); ?>[key] + '</a></li>';
                flag++;
            } else {
                html = html + '<li role="presentation" class="nav"><a data-toggle="tab" href="#tab' + name +'" id="tab' + name + '_D" role="tab">' + <?php echo json_encode($formations); ?>[key] + '</a></li>';
                
            }
        });
        html = html + '</ul><div class="tab-content">';
        flag = 0;
        <?php echo json_encode($formationsWithoutSpaces); ?>.forEach(element => {
            name = title + element;
            if (flag == 0) {
                html = html + '<div role="tabpanel" class="tab-pane fade in active" id="tab' + name + '">';
                flag++;
            } else {
                html = html + '<div role="tabpanel" class="tab-pane fade in" id="tab' + name + '">';
            }

            //CONTENT
            html = html + '<div class="tabcontent"><div class="row"><div class="col-md-4"><div class="form-group"><label for="selectStored_' + name + '">Stored Previously</label><select name="selectStored_' + name + '" id="selectStored_' + name + '" class=" form-control form-select selectpicker show-tick" onchange="updateData(`selectStored_' + name + '`,`' + name + '`)">';
            html = html + '<option value="none" selected hidden>Nothing Selected</option>';
            html = html + organizeSelectOptions(title, element, <?php echo json_encode($mediciones); ?>);   


            html = html + '</select></div></div></div>';
            html = html + '<div class="row"><div class="col-md-4"><div class="form-group"><label for="value_' + name + '">Value</label> <label class="red">*</label><div class="input-group"><input type="text" id="value_' + name + '" name="value_' + name +'" class="form-control value_edit"><span class="input-group-addon" id="basic-addon2">-</span></div></div></div>';
            html = html + '<div class="col-md-4"><div class="form-group"><label for="date_' + name + '">Monitoring Date</label> <label class="red">*</label><input type="text" id="date_' + name + '" name="date_' + name + '" placeholder="dd/mm/yyyy" class="form-control value_edit jquery-datepicker"></div></div>';
            html = html + '<div class="col-md-4"><div class="form-group"><label for="comment_' + name + '">Comment</label><input type="text" id="comment_' + name + '" name="comment_' + name + '" class="form-control validate"></div></div></div>';
           
            html = html + '</div></div>';
        });
        html = html + '<br><div class="row"><div class="col-md-4"><div class="form-group"><label for="p10_' + title + '">p10</label> <label class="red">*</label><input type="text" id="p10_' + title + '" name="p10_' + title + '" class="form-control validate"></div></div>';
        html = html + '<div class="col-md-4"><div class="form-group"><label for="p90_' + title + '">p90</label> <label class="red">*</label><input type="text" id="p90_' + title + '" name="p90_' + title + '" class="form-control validate"></div></div>';
        html = html + '<div class="col-md-4"><div class="form-group"><label for="weight_' + title + '">Weight</label> <label class="red">*</label><input type="text" id="weight_' + title + '" name="weight_' + title + '" class="form-control weight_ms_count"></div></div></div>';  

        html = html + '</div></div>';
        $("#"+destination_div).append(html);
    }

    $("#checkbox_general_MS").change(function() { index = "MS"; numberOfParameters = 5; availableEnableDisableGeneralFields(index, numberOfParameters); }); 
    $("#checkbox_general_FB").change(function() { index = "FB"; numberOfParameters = 5; availableEnableDisableGeneralFields(index, numberOfParameters); }); 
    $("#checkbox_general_OS").change(function() { index = "OS"; numberOfParameters = 5; availableEnableDisableGeneralFields(index, numberOfParameters); }); 
    $("#checkbox_general_RP").change(function() { index = "RP"; numberOfParameters = 5; availableEnableDisableGeneralFields(index, numberOfParameters); }); 
    $("#checkbox_general_ID").change(function() { index = "ID"; numberOfParameters = 4; availableEnableDisableGeneralFields(index, numberOfParameters); }); 
    $("#checkbox_general_GD").change(function() { index = "GD"; numberOfParameters = 4; availableEnableDisableGeneralFields(index, numberOfParameters); }); 

    function availableEnableDisableGeneralFields(index, numberOfParameters) {
        if ($("#checkbox_general_" + index).prop('checked') == true) {
            for (let i = 0; i < numberOfParameters; i++) {
                $("#"+index+(i+1)+"_checkbox").prop('checked', true);
                $('#'+index+(i+1)+'_checkbox').attr('disabled', false);
                availableEnableDisableFields(index+[i+1]);
            }
        } else {
            for (let i = 0; i < numberOfParameters; i++) {
                $("#"+index+(i+1)+"_checkbox").prop('checked', false);
                $('#'+index+(i+1)+'_checkbox').attr('disabled', true);
                availableEnableDisableFields(index+[i+1]);
            }
        }
    }

    function availableEnableDisableCheckboxes(index) {
        if ($("#"+index+"_checkbox").prop('checked') == true) {
            <?php echo json_encode($formationsWithoutSpaces); ?>.forEach(formation => {
                name = index + formation;
                $('#selectStored_' + name).attr('disabled', false);
                $('#value_' + name).attr('disabled', false);
                $('#date_' + name).attr('disabled', false);
                $('#comment_' + name).attr('disabled', false);
            });
            $('#p10_'+index).attr('disabled', false);
            $('#p90_'+index).attr('disabled', false);
            $('#weight_'+index).attr('disabled', false);
            $('#'+index+'_checkbox').attr('disabled', false);

        } else {
            <?php echo json_encode($formationsWithoutSpaces); ?>.forEach(formation => {
                name = index + formation;
                $('#selectStored_' + name).attr('disabled', true);
                $('#value_' + name).attr('disabled', true);
                $('#date_' + name).attr('disabled', true);
                $('#comment_' + name).attr('disabled', true);
            });
            $('#p10_'+index).attr('disabled', true);
            $('#p90_'+index).attr('disabled', true);
            $('#weight_'+index).attr('disabled', true);
            $('#'+index+'_checkbox').attr('disabled', true);
            
        }
    }

    $("#MS1_checkbox").change(function() { index = "MS1"; availableEnableDisableFields(index); }); 
    $("#MS2_checkbox").change(function() { index = "MS2"; availableEnableDisableFields(index); }); 
    $("#MS3_checkbox").change(function() { index = "MS3"; availableEnableDisableFields(index); }); 
    $("#MS4_checkbox").change(function() { index = "MS4"; availableEnableDisableFields(index); }); 
    $("#MS5_checkbox").change(function() { index = "MS5"; availableEnableDisableFields(index); }); 
    $("#FB1_checkbox").change(function() { index = "FB1"; availableEnableDisableFields(index); }); 
    $("#FB2_checkbox").change(function() { index = "FB2"; availableEnableDisableFields(index); }); 
    $("#FB3_checkbox").change(function() { index = "FB3"; availableEnableDisableFields(index); }); 
    $("#FB4_checkbox").change(function() { index = "FB4"; availableEnableDisableFields(index); }); 
    $("#FB5_checkbox").change(function() { index = "FB5"; availableEnableDisableFields(index); }); 
    $("#OS1_checkbox").change(function() { index = "OS1"; availableEnableDisableFields(index); });
    $("#OS2_checkbox").change(function() { index = "OS2"; availableEnableDisableFields(index); });
    $("#OS3_checkbox").change(function() { index = "OS3"; availableEnableDisableFields(index); });
    $("#OS4_checkbox").change(function() { index = "OS4"; availableEnableDisableFields(index); });
    $("#OS5_checkbox").change(function() { index = "OS5"; availableEnableDisableFields(index); });
    $("#RP1_checkbox").change(function() { index = "RP1"; availableEnableDisableFields(index); });
    $("#RP2_checkbox").change(function() { index = "RP2"; availableEnableDisableFields(index); });
    $("#RP3_checkbox").change(function() { index = "RP3"; availableEnableDisableFields(index); });
    $("#RP4_checkbox").change(function() { index = "RP4"; availableEnableDisableFields(index); });
    $("#RP5_checkbox").change(function() { index = "RP5"; availableEnableDisableFields(index); });
    $("#ID1_checkbox").change(function() { index = "ID1"; availableEnableDisableFields(index); });
    $("#ID2_checkbox").change(function() { index = "ID2"; availableEnableDisableFields(index); });
    $("#ID3_checkbox").change(function() { index = "ID3"; availableEnableDisableFields(index); });
    $("#ID4_checkbox").change(function() { index = "ID4"; availableEnableDisableFields(index); });
    $("#GD1_checkbox").change(function() { index = "GD1"; availableEnableDisableFields(index); });
    $("#GD2_checkbox").change(function() { index = "GD2"; availableEnableDisableFields(index); });
    $("#GD3_checkbox").change(function() { index = "GD3"; availableEnableDisableFields(index); });
    $("#GD4_checkbox").change(function() { index = "GD4"; availableEnableDisableFields(index); });

    function availableEnableDisableFields(index) {
        if ($("#"+index+"_checkbox").prop('checked') == true) {
            <?php echo json_encode($formationsWithoutSpaces); ?>.forEach(formation => {
                name = index + formation;
                $('#selectStored_' + name).attr('disabled', false);
                $('#value_' + name).attr('disabled', false);
                $('#date_' + name).attr('disabled', false);
                $('#comment_' + name).attr('disabled', false);
            });
            $('#p10_'+index).attr('disabled', false);
            $('#p90_'+index).attr('disabled', false);
            $('#weight_'+index).attr('disabled', false);
        } else {
            <?php echo json_encode($formationsWithoutSpaces); ?>.forEach(formation => {
                name = index + formation;
                $('#selectStored_' + name).attr('disabled', true);
                $('#value_' + name).attr('disabled', true);
                $('#date_' + name).attr('disabled', true);
                $('#comment_' + name).attr('disabled', true);
            });
            $('#p10_'+index).attr('disabled', true);
            $('#p90_'+index).attr('disabled', true);
            $('#weight_'+index).attr('disabled', true);
        }
    }

    function organizeSelectOptions(title, formation, mediciones) {
        html_aux = '';
        mediciones.forEach(medicion => {
            if (medicion[7] === title && medicion[8] === formation) {
                
                html_aux = html_aux + '<option value="' + medicion[0] + '">' + medicion[5] + '</option>';
            }
        });
        // console.log(formation,title,html_aux);
        return html_aux;
    }
    
    function updateData(select_id, name) {
        <?php echo json_encode($mediciones); ?>.forEach(element => {
            if ($('#'+select_id).val() == element[0]) {
                $('#value_'+name).val(element[4]);
                $('#date_'+name).val(element[5]);
                $('#comment_'+name).val(element[6]);
            }
        }); 
    }

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
        
        $.get("{!! url('P_mediciones') !!}", {
            campo: dataarray,
        }, 
        function(data) { 
            $.each(data, function(index, value) {
                if (data[index] != null) {
                    nps = [];
                    $.each(value, function(index, value ) {
                        d = parseFloat(value.valor);
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
                        switch (index) {
                            case '1':
                                multiparametricoStatisticalEach(p10, p90, 'MS1');
                                break;
                            case '2':
                                multiparametricoStatisticalEach(p10, p90, 'MS2');
                                break;
                            case '3':
                                multiparametricoStatisticalEach(p10, p90, 'MS3');
                                break;
                            case '4':
                                multiparametricoStatisticalEach(p10, p90, 'MS4');
                                break;
                            case '5':
                                multiparametricoStatisticalEach(p10, p90, 'MS5');
                                break;
                            case '6':
                                multiparametricoStatisticalEach(p10, p90, 'FB1');
                                break;
                            case '7':
                                multiparametricoStatisticalEach(p10, p90, 'FB2');
                                break;
                            case '8':
                                multiparametricoStatisticalEach(p10, p90, 'FB3');
                                break;
                            case '9':
                                multiparametricoStatisticalEach(p10, p90, 'FB4');
                                break;
                            case '10':
                                multiparametricoStatisticalEach(p10, p90, 'FB5');
                                break;
                            case '11':
                                multiparametricoStatisticalEach(p10, p90, 'OS1');
                                break;
                            case '12':
                                multiparametricoStatisticalEach(p10, p90, 'OS2');
                                break;
                            case '13':
                                multiparametricoStatisticalEach(p10, p90, 'OS3');
                                break;
                            case '14':
                                multiparametricoStatisticalEach(p10, p90, 'OS4');
                                break;
                            case '15':
                                multiparametricoStatisticalEach(p10, p90, 'OS5');
                                break;
                            case '16':
                                multiparametricoStatisticalEach(p10, p90, 'RP1');
                                break;
                            case '17':
                                multiparametricoStatisticalEach(p10, p90, 'RP2');
                                break;
                            case '18':
                                multiparametricoStatisticalEach(p10, p90, 'RP3');
                                break;
                            case '19':
                                multiparametricoStatisticalEach(p10, p90, 'RP4');
                                break;
                            case '20':
                                multiparametricoStatisticalEach(p10, p90, 'RP5');
                                break;
                            case '21':
                                multiparametricoStatisticalEach(p10, p90, 'ID1');
                                break;
                            case '22':
                                multiparametricoStatisticalEach(p10, p90, 'ID2');
                                break;
                            case '23':
                                multiparametricoStatisticalEach(p10, p90, 'ID3');
                                break;
                            case '24':
                                multiparametricoStatisticalEach(p10, p90, 'ID4');
                                break;
                            case '25':
                                multiparametricoStatisticalEach(p10, p90, 'GD1');
                                break;
                            case '26':
                                multiparametricoStatisticalEach(p10, p90, 'GD2');
                                break;
                            case '27':
                                multiparametricoStatisticalEach(p10, p90, 'GD3');
                                break;
                            case '28':
                                multiparametricoStatisticalEach(p10, p90, 'GD4');
                                break;
                        }
                    }
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

    function multiparametricoStatisticalEach(p10, p90, title) {
        $("#p10_" + title).val(p10);
        $("#p90_" + title).val(p90);
    }

    function multiparametricWeights() {
        if (<?php echo json_encode($pesos); ?> != null) {
            $("#weight_MS1").val(<?php echo json_encode($pesos); ?>[1]);
            $("#weight_MS2").val(<?php echo json_encode($pesos); ?>[2]);
            $("#weight_MS3").val(<?php echo json_encode($pesos); ?>[3]);
            $("#weight_MS4").val(<?php echo json_encode($pesos); ?>[4]);
            $("#weight_MS5").val(<?php echo json_encode($pesos); ?>[5]);
            $("#weight_FB1").val(<?php echo json_encode($pesos); ?>[6]);
            $("#weight_FB2").val(<?php echo json_encode($pesos); ?>[7]);
            $("#weight_FB3").val(<?php echo json_encode($pesos); ?>[8]);
            $("#weight_FB4").val(<?php echo json_encode($pesos); ?>[9]);
            $("#weight_FB5").val(<?php echo json_encode($pesos); ?>[10]);
            $("#weight_OS1").val(<?php echo json_encode($pesos); ?>[11]);
            $("#weight_OS2").val(<?php echo json_encode($pesos); ?>[12]);
            $("#weight_OS3").val(<?php echo json_encode($pesos); ?>[13]);
            $("#weight_OS4").val(<?php echo json_encode($pesos); ?>[14]);
            $("#weight_OS5").val(<?php echo json_encode($pesos); ?>[15]);
            $("#weight_RP1").val(<?php echo json_encode($pesos); ?>[16]);
            $("#weight_RP2").val(<?php echo json_encode($pesos); ?>[17]);
            $("#weight_RP3").val(<?php echo json_encode($pesos); ?>[18]);
            $("#weight_RP4").val(<?php echo json_encode($pesos); ?>[19]);
            $("#weight_RP5").val(<?php echo json_encode($pesos); ?>[20]);
            $("#weight_ID1").val(<?php echo json_encode($pesos); ?>[21]);
            $("#weight_ID2").val(<?php echo json_encode($pesos); ?>[22]);
            $("#weight_ID3").val(<?php echo json_encode($pesos); ?>[23]);
            $("#weight_ID4").val(<?php echo json_encode($pesos); ?>[24]);
            $("#weight_GD1").val(<?php echo json_encode($pesos); ?>[25]);
            $("#weight_GD2").val(<?php echo json_encode($pesos); ?>[26]);
            $("#weight_GD3").val(<?php echo json_encode($pesos); ?>[27]);
            $("#weight_GD4").val(<?php echo json_encode($pesos); ?>[28]);
        }
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

    function runMultiparametric() {
        $("#only_s").val("run");
        $("#multiparametricStatisticalForm").submit();
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

    $('#p10_13').bind('change', function() {
        $('#p10_15').val($(this).val());
    });

    $('#p90_13').bind('change', function() {
        $('#p90_15').val($(this).val());
    });

    $('#RP1').bind('change', function() {
        $('#OS4').val($(this).val());
    });

    $('#p10_15').bind('change', function() {
        $('#p10_13').val($(this).val());
    });

    $('#p90_15').bind('change', function() {
        $('#p90_13').val($(this).val());
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
        $(".ms-subparameter-picker").empty();

        $.get("{{url('subparameterbywell')}}", {
            pozoId: "{{ $pozoId }}"
        }, function(data) {
            $.each(data, function(index, value) {
                var textValue = value.valor + ' - ' + moment(value.fecha).format('DD/MM/YYYY');
                $("select.select-stored-" + value.subparametro_id).append('<option value="' + textValue + '">' + textValue + '</option>');
            });

            $(".ms-subparameter-picker").selectpicker('render');
            $(".ms-subparameter-picker").selectpicker('refresh');
            $(".ms-subparameter-picker").selectpicker('val', '');
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_27").val(), mineral_scales_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_27").val() === null || $("#p10_27").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_1").val(), mineral_scales_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_1").val() === null || $("#p90_1").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_ms_1_value").val(), mineral_scales_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_ms_1_value").val() === null || $("#weight_ms_1_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_2").val(), mineral_scales_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_2").val() === null || $("#p10_2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_2").val(), mineral_scales_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_2").val() === null || $("#p90_2").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_ms_2_value").val(), mineral_scales_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_ms_2_value").val() === null || $("#weight_ms_2_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_3").val(), mineral_scales_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_3").val() === null || $("#p10_3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_3").val(), mineral_scales_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_3").val() === null || $("#p90_3").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_ms_3_value").val(), mineral_scales_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_ms_3_value").val() === null || $("#weight_ms_3_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_4").val(), mineral_scales_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_4").val() === null || $("#p10_4").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_4").val(), mineral_scales_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_4").val() === null || $("#p90_4").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_ms_4_value").val(), mineral_scales_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_ms_4_value").val() === null || $("#weight_ms_4_value").val() === "")) ? true: emptyValues;
        
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_5").val(), mineral_scales_tab_ruleset[27]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_5").val() === null || $("#p10_5").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_5").val(), mineral_scales_tab_ruleset[28]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_5").val() === null || $("#p90_5").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_ms_5_value").val(), mineral_scales_tab_ruleset[29]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_ms_5_value").val() === null || $("#weight_ms_5_value").val() === "")) ? true: emptyValues;

        var sumWeights = parseFloat($("#weight_ms_1_value").val()) + parseFloat($("#weight_ms_2_value").val()) + parseFloat($("#weight_ms_3_value").val()) + parseFloat($("#weight_ms_4_value").val()) + parseFloat($("#weight_ms_5_value").val());
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_6").val(), fine_blockage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_6").val() === null || $("#p10_6").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_6").val(), fine_blockage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_6").val() === null || $("#p90_6").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_fb_1_value").val(), fine_blockage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_fb_1_value").val() === null || $("#weight_fb_1_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_7").val(), fine_blockage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_7").val() === null || $("#p10_7").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_7").val(), fine_blockage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_7").val() === null || $("#p90_7").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_fb_2_value").val(), fine_blockage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_fb_2_value").val() === null || $("#weight_fb_2_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_8").val(), fine_blockage_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_8").val() === null || $("#p10_8").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_8").val(), fine_blockage_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_8").val() === null || $("#p90_8").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_fb_3_value").val(), fine_blockage_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_fb_3_value").val() === null || $("#weight_fb_3_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_9").val(), fine_blockage_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_9").val() === null || $("#p10_9").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_9").val(), fine_blockage_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_9").val() === null || $("#p90_9").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_fb_4_value").val(), fine_blockage_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_fb_4_value").val() === null || $("#weight_fb_4_value").val() === "")) ? true: emptyValues;
        
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_10").val(), fine_blockage_tab_ruleset[27]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_10").val() === null || $("#p10_10").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_10").val(), fine_blockage_tab_ruleset[28]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_10").val() === null || $("#p90_10").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_fb_5_value").val(), fine_blockage_tab_ruleset[29]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_fb_5_value").val() === null || $("#weight_fb_5_value").val() === "")) ? true: emptyValues;

        sumWeights = parseFloat($("#weight_fb_1_value").val()) + parseFloat($("#weight_fb_2_value").val()) + parseFloat($("#weight_fb_3_value").val()) + parseFloat($("#weight_fb_4_value").val()) + parseFloat($("#weight_fb_5_value").val());
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_11").val(), organic_scales_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_11").val() === null || $("#p10_11").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_11").val(), organic_scales_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_11").val() === null || $("#p90_11").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_os_1_value").val(), organic_scales_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_os_1_value").val() === null || $("#weight_os_1_value").val() === "")) ? true: emptyValues;

        // Volume of HCl pumped into the formation (bbl)
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_30").val(), organic_scales_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_30").val() === null || $("#p10_30").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_30").val(), organic_scales_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_30").val() === null || $("#p90_30").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_os_2_value").val(), organic_scales_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_os_2_value").val() === null || $("#weight_os_2_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_12").val(), organic_scales_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_12").val() === null || $("#p10_12").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_12").val(), organic_scales_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_12").val() === null || $("#p90_12").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_os_3_value").val(), organic_scales_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_os_3_value").val() === null || $("#weight_os_3_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_13").val(), organic_scales_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_13").val() === null || $("#p10_13").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_13").val(), organic_scales_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_13").val() === null || $("#p90_13").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_os_4_value").val(), organic_scales_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_os_4_value").val() === null || $("#weight_os_4_value").val() === "")) ? true: emptyValues;
        
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_14").val(), organic_scales_tab_ruleset[27]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_14").val() === null || $("#p10_14").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_14").val(), organic_scales_tab_ruleset[28]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_14").val() === null || $("#p90_14").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_os_5_value").val(), organic_scales_tab_ruleset[29]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_os_5_value").val() === null || $("#weight_os_5_value").val() === "")) ? true: emptyValues;
        
        sumWeights = parseFloat($("#weight_os_1_value").val()) + parseFloat($("#weight_os_2_value").val()) + parseFloat($("#weight_os_3_value").val()) + parseFloat($("#weight_os_4_value").val()) + parseFloat($("#weight_os_5_value").val());
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_15").val(), relative_permeability_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_15").val() === null || $("#p10_15").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_15").val(), relative_permeability_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_15").val() === null || $("#p90_15").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_rp_1_value").val(), relative_permeability_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_rp_1_value").val() === null || $("#weight_rp_1_value").val() === "")) ? true: emptyValues;

        // Difference between current reservoir pressure and saturation pressure
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_16").val(), relative_permeability_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_16").val() === null || $("#p10_16").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_16").val(), relative_permeability_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_16").val() === null || $("#p90_16").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_rp_2_value").val(), relative_permeability_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_rp_2_value").val() === null || $("#weight_rp_2_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_17").val(), relative_permeability_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_17").val() === null || $("#p10_17").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_17").val(), relative_permeability_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_17").val() === null || $("#p90_17").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_rp_3_value").val(), relative_permeability_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_rp_3_value").val() === null || $("#weight_rp_3_value").val() === "")) ? true: emptyValues;

        // Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k))
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_18").val(), relative_permeability_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_18").val() === null || $("#p10_18").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_18").val(), relative_permeability_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_18").val() === null || $("#p90_18").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_rp_4_value").val(), relative_permeability_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_rp_4_value").val() === null || $("#weight_rp_4_value").val() === "")) ? true: emptyValues;

        // Velocity parameter estimated from maximum critical velocity
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP5").val(), relative_permeability_tab_ruleset[24]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#RP5").val() === null || $("#RP5").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP5").val(), relative_permeability_tab_ruleset[25]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#dateRP5").val() === null || $("#dateRP5").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP5comment").val(), relative_permeability_tab_ruleset[26]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_31").val(), relative_permeability_tab_ruleset[27]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_31").val() === null || $("#p10_31").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_31").val(), relative_permeability_tab_ruleset[28]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_31").val() === null || $("#p90_31").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_rp_5_value").val(), relative_permeability_tab_ruleset[29]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_rp_5_value").val() === null || $("#weight_rp_5_value").val() === "")) ? true: emptyValues;
        
        sumWeights = parseFloat($("#weight_rp_1_value").val()) + parseFloat($("#weight_rp_2_value").val()) + parseFloat($("#weight_rp_3_value").val()) + parseFloat($("#weight_rp_4_value").val()) + parseFloat($("#weight_rp_5_value").val());
        if (sumWeights > 1) {
            if (titleTab == "") {
                titleTab = "Tab: Relative Permeability";
                validationMessages = validationMessages.concat(titleTab);
            }
            validationMessages = validationMessages.concat("The plus of the input Weight of the section Relative Permeability is older (" + sumWeights + ")");
        }
        
        // Validating Induced Damage
        titleTab = "";
        tabTitle = "Tab: Induced Damage";

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_19").val(), induce_damage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_19").val() === null || $("#p10_19").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_19").val(), induce_damage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_19").val() === null || $("#p90_19").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_id_1_value").val(), induce_damage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_id_1_value").val() === null || $("#weight_id_1_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_20").val(), induce_damage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_20").val() === null || $("#p10_20").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_20").val(), induce_damage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_20").val() === null || $("#p90_20").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_id_2_value").val(), induce_damage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_id_2_value").val() === null || $("#weight_id_2_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_21").val(), induce_damage_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_21").val() === null || $("#p10_21").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_21").val(), induce_damage_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_21").val() === null || $("#p90_21").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_id_3_value").val(), induce_damage_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_id_3_value").val() === null || $("#weight_id_3_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_22").val(), induce_damage_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_22").val() === null || $("#p10_22").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_22").val(), induce_damage_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_22").val() === null || $("#p90_22").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_id_4_value").val(), induce_damage_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_id_4_value").val() === null || $("#weight_id_4_value").val() === "")) ? true: emptyValues;
        
        sumWeights = parseFloat($("#weight_id_1_value").val()) + parseFloat($("#weight_id_2_value").val()) + parseFloat($("#weight_id_3_value").val()) + parseFloat($("#weight_id_4_value").val());
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

        // Fraction of Net Pay Exihibiting Natural Fractures (fraction)
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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_23").val(), geomechanical_damage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_23").val() === null || $("#p10_23").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_23").val(), geomechanical_damage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_23").val() === null || $("#p90_23").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_gd_1_value").val(), geomechanical_damage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_gd_1_value").val() === null || $("#weight_gd_1_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_24").val(), geomechanical_damage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_24").val() === null || $("#p10_24").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_24").val(), geomechanical_damage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_24").val() === null || $("#p90_24").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_gd_2_value").val(), geomechanical_damage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_gd_2_value").val() === null || $("#weight_gd_2_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_25").val(), geomechanical_damage_tab_ruleset[15]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_25").val() === null || $("#p10_25").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_25").val(), geomechanical_damage_tab_ruleset[16]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_25").val() === null || $("#p90_25").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_gd_3_value").val(), geomechanical_damage_tab_ruleset[17]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_gd_3_value").val() === null || $("#weight_gd_3_value").val() === "")) ? true: emptyValues;

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

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p10_26").val(), geomechanical_damage_tab_ruleset[21]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p10_26").val() === null || $("#p10_26").val() === "")) ? true: emptyValues;

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#p90_26").val(), geomechanical_damage_tab_ruleset[22]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#p90_26").val() === null || $("#p90_26").val() === "")) ? true: emptyValues;
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#weight_gd_4_value").val(), geomechanical_damage_tab_ruleset[23]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#weight_gd_4_value").val() === null || $("#weight_gd_4_value").val() === "")) ? true: emptyValues;

        sumWeights = parseFloat($("#weight_gd_1_value").val()) + parseFloat($("#weight_gd_2_value").val()) + parseFloat($("#weight_gd_3_value").val()) + parseFloat($("#weight_gd_4_value").val());
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
        var tabToValidate = $(".nav.nav-tabs.index-group li.active a").attr("id");

        if (direction == "prev") {
            $(".nav.nav-tabs.index-group li.active").prev().children().click();
        } else {
            $(".nav.nav-tabs.index-group li.active").next().children().click();
        }

        $("#next_button").toggle($(".nav.nav-tabs.index-group li.active").next().is("li"));
        $("#prev_button").toggle($(".nav.nav-tabs.index-group li.active").prev().is("li"));
        $("#run_calc").toggle(!$(".nav.nav-tabs.index-group li.active").next().is("li"));
    }

    /* switchTab
    * Captures the tab clicking event to determine if a previous or next button has to be shown
    * and also the run button
    */
    function switchTab() {
        var event = window.event || arguments.callee.caller.arguments[0];
        var tabActiveElement = $(".nav.nav-tabs.index-group li.active");
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