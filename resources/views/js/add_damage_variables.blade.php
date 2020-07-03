<script type="text/javascript">

    //Guardar valores de select para cargar cuando salga error en el formulario.
    window.onbeforeunload = function() {
        var a = $('#well').val();
        localStorage.setItem('well', $('#well').val());
        var b = $('#field').val();
        localStorage.setItem('field', $('#field').val());
    }

    //Volver a cargar valores de select anidados cuando salga ventana modal de error.
    window.onload = function() {
        var basin = $('#basin').val();
        var field = localStorage.getItem('field');
        $.get("{{url('fieldbybasinselect')}}", {
                basin: basin
            },
            function(data) {
                $("#field").empty();
                $.each(data, function(index, value) {
                    $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#field > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', field);
                $(k).attr('selected', 'selected');
                $("#field").selectpicker('refresh');
            }
        );

        var field = localStorage.getItem('field');
        var pozo = localStorage.getItem('well');
        $.get("{{url('fields')}}", {
                field: field
            },
            function(data) {
                $("#well").empty();
                $.each(data, function(index, value) {
                    $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#well > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', pozo);
                $(k).attr('selected', 'selected');
                $("#well").selectpicker('refresh');
            }
        );
    }


    $(function() {
        //Fecha actual
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var tod = year + "-" + month + "-" + day;

        var today = 0000 + "-" + 00 + "-" + 00;

        //Comparar fecha actual con fechas de SP, en caso de que sea mayor a la fecha actual muestra ventana de error
        $("#dateMS1").change(function(e) {
            var dateMS1 = $('#dateMS1').val();

            if (new Date(tod).getTime() - new Date(dateMS1).getTime() < 0) {
                document.getElementById('dateMS1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS2").change(function(e) {
            var dateMS2 = $('#dateMS2').val();

            if (new Date(tod).getTime() - new Date(dateMS2).getTime() < 0) {
                document.getElementById('dateMS2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS3").change(function(e) {
            var dateMS3 = $('#dateMS3').val();

            if (new Date(tod).getTime() - new Date(dateMS3).getTime() < 0) {
                document.getElementById('dateMS3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS4").change(function(e) {
            var dateMS4 = $('#dateMS4').val();

            if (new Date(tod).getTime() - new Date(dateMS4).getTime() < 0) {
                document.getElementById('dateMS4').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS5").change(function(e) {
            var dateMS5 = $('#dateMS5').val();

            if (new Date(tod).getTime() - new Date(dateMS5).getTime() < 0) {
                document.getElementById('dateMS5').value = today;
                $('#date').modal('show');
            }
        });


        $("#dateFB1").change(function(e) {
            var dateFB1 = $('#dateFB1').val();

            if (new Date(tod).getTime() - new Date(dateFB1).getTime() < 0) {
                document.getElementById('dateFB1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB2").change(function(e) {
            var dateFB2 = $('#dateFB2').val();

            if (new Date(tod).getTime() - new Date(dateFB2).getTime() < 0) {
                document.getElementById('dateFB2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB3").change(function(e) {
            var dateFB3 = $('#dateFB3').val();

            if (new Date(tod).getTime() - new Date(dateFB3).getTime() < 0) {
                document.getElementById('dateFB3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB4").change(function(e) {
            var dateFB4 = $('#dateFB4').val();

            if (new Date(tod).getTime() - new Date(dateFB4).getTime() < 0) {
                document.getElementById('dateFB4').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB5").change(function(e) {
            var dateFB5 = $('#dateFB5').val();

            if (new Date(tod).getTime() - new Date(dateFB5).getTime() < 0) {
                document.getElementById('dateFB5').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateOS1").change(function(e) {
            var dateOS1 = $('#dateOS1').val();

            if (new Date(tod).getTime() - new Date(dateOS1).getTime() < 0) {
                document.getElementById('dateOS1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateOS2").change(function(e) {
            var dateOS2 = $('#dateOS2').val();

            if (new Date(tod).getTime() - new Date(dateOS2).getTime() < 0) {
                document.getElementById('dateOS2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateOS3").change(function(e) {
            var dateOS3 = $('#dateOS3').val();

            if (new Date(tod).getTime() - new Date(dateOS3).getTime() < 0) {
                document.getElementById('dateOS3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateOS4").change(function(e) {
            var dateOS4 = $('#dateOS4').val();

            if (new Date(tod).getTime() - new Date(dateOS4).getTime() < 0) {
                document.getElementById('dateOS4').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateRP1").change(function(e) {
            var dateRP1 = $('#dateRP1').val();

            if (new Date(tod).getTime() - new Date(dateRP1).getTime() < 0) {
                document.getElementById('dateRP1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateRP2").change(function(e) {
            var dateRP2 = $('#dateRP2').val();

            if (new Date(tod).getTime() - new Date(dateRP2).getTime() < 0) {
                document.getElementById('dateRP2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateRP3").change(function(e) {
            var dateRP3 = $('#dateRP3').val();

            if (new Date(tod).getTime() - new Date(dateRP3).getTime() < 0) {
                document.getElementById('dateRP3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateRP4").change(function(e) {
            var dateRP4 = $('#dateRP4').val();

            if (new Date(tod).getTime() - new Date(dateRP4).getTime() < 0) {
                document.getElementById('dateRP4').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateID1").change(function(e) {
            var dateID1 = $('#dateID1').val();

            if (new Date(tod).getTime() - new Date(dateID1).getTime() < 0) {
                document.getElementById('dateID1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateID2").change(function(e) {
            var dateID2 = $('#dateID2').val();

            if (new Date(tod).getTime() - new Date(dateID2).getTime() < 0) {
                document.getElementById('dateID2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateID3").change(function(e) {
            var dateID3 = $('#dateID3').val();

            if (new Date(tod).getTime() - new Date(dateID3).getTime() < 0) {
                document.getElementById('dateID3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateID4").change(function(e) {
            var dateID4 = $('#dateID4').val();

            if (new Date(tod).getTime() - new Date(dateID4).getTime() < 0) {
                document.getElementById('dateID4').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateGD1").change(function(e) {
            var dateGD1 = $('#dateGD1').val();

            if (new Date(tod).getTime() - new Date(dateGD1).getTime() < 0) {
                document.getElementById('dateGD1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateGD2").change(function(e) {
            var dateGD2 = $('#dateGD2').val();

            if (new Date(tod).getTime() - new Date(dateGD2).getTime() < 0) {
                document.getElementById('dateGD2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateGD3").change(function(e) {
            var dateGD3 = $('#dateGD3').val();

            if (new Date(tod).getTime() - new Date(dateGD3).getTime() < 0) {
                document.getElementById('dateGD3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateGD4").change(function(e) {
            var dateGD4 = $('#dateGD4').val();

            if (new Date(tod).getTime() - new Date(dateGD4).getTime() < 0) {
                document.getElementById('dateGD4').value = today;
                $('#date').modal('show');
            }
        });

        
        $("#myModal").modal('show');

        //Valores de select anidados, cargar datos de acuerdo a opcion escogida
        $("#basin").change(function(e) {
            var basin = $('#basin').val();
            $.get("{{url('fieldbybasin')}}", {
                    basin: basin
                },
                function(data) {
                    $("#well").empty();
                    $("#field").empty();

                    $.each(data, function(index, value) {
                        $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field").selectpicker('refresh');
                    $('#field').selectpicker('val', '');
                });
        });

        $("#field").change(function(e) {
            var field = $('#field').val();
            $.get("{{url('wellbyfield')}}", {
                    field: field
                },
                function(data) {
                    $("#well").empty();
                    $.each(data, function(index, value) {
                        $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#well").selectpicker('refresh');
                    $('#well').selectpicker('val', '');
                });
        });

    });

    /* verifyDrilling
    * Validates the form entirely
    * params {action: string}
    */
    function verifyDamage(action) {
        // // Boolean for empty values for the save button
        // var emptyValues = false;
        // // Title tab for modal errors
        // var titleTab = "";
        // var tabTitle = "";
        // //Saving tables...
        // var validationMessages = [];
        // var validationFunctionResult = [];

        // // Validating General Data
        // tabTitle = "Tab: General Data";

        // var select_interval_general_data = $("#intervalSelect").val();
        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_interval_general_data, general_data_select_ruleset[0]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && (select_interval_general_data === null || select_interval_general_data === "")) ? true: emptyValues;

        // var generaldata_table = clean_table_data("intervalsGeneral_t");
        // var generalValidator = validateTable("General Data", generaldata_table, general_data_table_ruleset);
        // if (generalValidator.length > 0) {
        //     if (titleTab == "") {
        //         titleTab = "Tab: General Data";
        //         validationMessages = validationMessages.concat(titleTab);
        //     }
        //     validationMessages = validationMessages.concat(generalValidator);
        // }

        // var select_input_data = $("#inputDataMethodSelect").val();
        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_input_data, profile_select_ruleset[0]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && (select_input_data === null || select_input_data === "")) ? true: emptyValues;

        // if (select_input_data == "1") {
        //     //Limpiando datos de tablas
        //     var inputdata_profile_table = clean_table_data("profileInput_t");
        //     var generalValidator = validateTable("Input Data", inputdata_profile_table, profile_table_ruleset);
        //     if (generalValidator.length > 0) {
        //     if (titleTab == "") {
        //         titleTab = "Tab: General Data";
        //         validationMessages = validationMessages.concat(titleTab);
        //     }
        //     validationMessages = validationMessages.concat(generalValidator);
        //     }
        // } else if (select_input_data == "2") {
        //     // This condition is never met, pending future developments
        //     // Limpiando datos de tablas
        //     var inputdata_intervals_table = clean_table_data("byIntervalsInput_t");
        // }

        // // Validating Filtration Function data
        // titleTab = "";
        // tabTitle = "Tab: Filtration Functions";

        // // Guardando los valores de los selectores
        // var select_filtration_function = $("#filtration_function_select").val();
        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_filtration_function, filtration_function_tab_ruleset[0]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && (select_filtration_function === null || select_filtration_function === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#a_factor_t").val(), filtration_function_tab_ruleset[1]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#a_factor_t").val() === null || $("#a_factor_t").val() === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#b_factor_t").val(), filtration_function_tab_ruleset[2]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#b_factor_t").val() === null || $("#b_factor_t").val() === "")) ? true: emptyValues;

        // // Validating Drilling Data
        // titleTab = "";
        // tabTitle = "Tab: Drilling Data";

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_total_exposure_time_t").val(), drilling_data_tab_ruleset[0]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#d_total_exposure_time_t").val() === null || $("#d_total_exposure_time_t").val() === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_pump_rate_t").val(), drilling_data_tab_ruleset[1]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#d_pump_rate_t").val() === null || $("#d_pump_rate_t").val() === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_mud_density_t").val(), drilling_data_tab_ruleset[2]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#d_mud_density_t").val() === null || $("#d_mud_density_t").val() === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_plastic_viscosity_t").val(), drilling_data_tab_ruleset[3]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#d_plastic_viscosity_t").val() === null || $("#d_plastic_viscosity_t").val() === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_yield_point_t").val(), drilling_data_tab_ruleset[4]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#d_yield_point_t").val() === null || $("#d_yield_point_t").val() === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_rop_t").val(), drilling_data_tab_ruleset[5]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#d_rop_t").val() === null || $("#d_rop_t").val() === "")) ? true: emptyValues;

        // validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_equivalent_circulating_density_t").val(), drilling_data_tab_ruleset[6]);
        // titleTab = validationFunctionResult[0];
        // validationMessages = validationFunctionResult[1];
        // emptyValues = (emptyValues === false && ($("#d_equivalent_circulating_density_t").val() === null || $("#d_equivalent_circulating_density_t").val() === "")) ? true: emptyValues;

        // // Validating Completion Data
        // if ($("#check_available").prop("checked")) {
        //     titleTab = "";
        //     tabTitle = "Tab: Completion Data";

        //     validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_total_exposure_time_t").val(), completion_data_tab_ruleset[0]);
        //     titleTab = validationFunctionResult[0];
        //     validationMessages = validationFunctionResult[1];
        //     emptyValues = (emptyValues === false && ($("#c_total_exposure_time_t").val() === null || $("#c_total_exposure_time_t").val() === "")) ? true: emptyValues;

        //     validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_pump_rate_t").val(), completion_data_tab_ruleset[1]);
        //     titleTab = validationFunctionResult[0];
        //     validationMessages = validationFunctionResult[1];
        //     emptyValues = (emptyValues === false && ($("#c_pump_rate_t").val() === null || $("#c_pump_rate_t").val() === "")) ? true: emptyValues;

        //     validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_cement_slurry_density_t").val(), completion_data_tab_ruleset[2]);
        //     titleTab = validationFunctionResult[0];
        //     validationMessages = validationFunctionResult[1];
        //     emptyValues = (emptyValues === false && ($("#c_cement_slurry_density_t").val() === null || $("#c_cement_slurry_density_t").val() === "")) ? true: emptyValues;

        //     validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_plastic_viscosity_t").val(), completion_data_tab_ruleset[3]);
        //     titleTab = validationFunctionResult[0];
        //     validationMessages = validationFunctionResult[1];
        //     emptyValues = (emptyValues === false && ($("#c_plastic_viscosity_t").val() === null || $("#c_plastic_viscosity_t").val() === "")) ? true: emptyValues;

        //     validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_yield_point_t").val(), completion_data_tab_ruleset[4]);
        //     titleTab = validationFunctionResult[0];
        //     validationMessages = validationFunctionResult[1];
        //     emptyValues = (emptyValues === false && ($("#c_yield_point_t").val() === null || $("#c_yield_point_t").val() === "")) ? true: emptyValues;

        //     validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_equivalent_circulating_density_t").val(), completion_data_tab_ruleset[5]);
        //     titleTab = validationFunctionResult[0];
        //     validationMessages = validationFunctionResult[1];
        //     emptyValues = (emptyValues === false && ($("#c_equivalent_circulating_density_t").val() === null || $("#c_equivalent_circulating_density_t").val() === "")) ? true: emptyValues;
        // }

        // if (validationMessages.length < 1) {
        //     // Guardando los datos de tablas validadas y limpiadas en formulario
        //     $("#generaldata_table").val(JSON.stringify(generaldata_table));
        //     $("#inputdata_intervals_table").val(JSON.stringify(inputdata_intervals_table));
        //     $("#inputdata_profile_table").val(JSON.stringify(inputdata_profile_table));
        //     $("#select_interval_general_data").val(JSON.stringify(remove_nulls(select_interval_general_data)));
        //     $("#select_input_data").val(select_input_data);
        //     $("#select_filtration_function").val($("#filtration_function_select").val());

        //     if (emptyValues) {
        //     validationMessages.push(true);
        //     showFrontendErrors(validationMessages);
        //     } else {
        //     $("#only_s").val("run");
        //     $("#drillingForm").submit();
        //     }
        // } else {
        //     showFrontendErrors(validationMessages);
        // }
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
        console.log(nextPrevElement);

        $("#next_button").toggle(nextPrevElement.next().is("li"));
        $("#prev_button").toggle(nextPrevElement.prev().is("li"));
        $("#run_calc").toggle(!nextPrevElement.next().is("li"));
        console.log('im called')
    }
</script>