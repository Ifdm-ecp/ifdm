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
        $(".jquery-datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy"
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

    /* verifyDamage
    * Validates the form entirely
    */
    function verifyDamage() {
        // An action is mandatory for the validations in this case action = run to validate against required fields
        var action = "run";
        // Title tab for modal errors
        var titleTab = "";
        var tabTitle = "";
        //Saving tables...
        var validationMessages = [];
        var validationFunctionResult = [];

        tabTitle = "Tab: General error";

        var select_well_data = $("#well").val();
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_well_data, well_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Validating Mineral Scales
        tabTitle = "Tab: Mineral Scales";

        // Scale Index Of CaCO3
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS1").val(), mineral_scales_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS1").val(), mineral_scales_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS1comment").val(), mineral_scales_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Scale Index Of BaSO4
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS2").val(), mineral_scales_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS2").val(), mineral_scales_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS2comment").val(), mineral_scales_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Scale Index Of Iron Scales
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS3").val(), mineral_scales_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS3").val(), mineral_scales_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS3comment").val(), mineral_scales_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Backflow [Ca] (ppm)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS4").val(), mineral_scales_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS4").val(), mineral_scales_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS4comment").val(), mineral_scales_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        // Backflow [Ba] (ppm)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS5").val(), mineral_scales_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateMS5").val(), mineral_scales_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#MS5comment").val(), mineral_scales_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Validating Fine Blockage
        titleTab = "";
        tabTitle = "Tab: Fine Blockage";

        // [Al] on Produced Water (ppm)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB1").val(), fine_blockage_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB1").val(), fine_blockage_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB1comment").val(), fine_blockage_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // [Si] on produced water
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB2").val(), fine_blockage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB2").val(), fine_blockage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB2comment").val(), fine_blockage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Critical Radius derived from maximum critical velocity, Vc (ft)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB3").val(), fine_blockage_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB3").val(), fine_blockage_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB3comment").val(), fine_blockage_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Mineralogy Factor
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB4").val(), fine_blockage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB4").val(), fine_blockage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB4comment").val(), fine_blockage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        // Mass of crushed proppant inside Hydraulic Fractures (lbs)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB5").val(), fine_blockage_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateFB5").val(), fine_blockage_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#FB5comment").val(), fine_blockage_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Validating Organic Scales
        titleTab = "";
        tabTitle = "Tab: Organic Scales";

        // CII Factor: Colloidal Instability Index
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS1").val(), organic_scales_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS1").val(), organic_scales_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS1comment").val(), organic_scales_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Volume of HCL pumped into the formation (bbl)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS2").val(), organic_scales_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS2").val(), organic_scales_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS2comment").val(), organic_scales_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Cumulative Gas Produced
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS3").val(), organic_scales_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS3").val(), organic_scales_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS3comment").val(), organic_scales_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Number Of Days Below Saturation Pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS4").val(), organic_scales_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS4").val(), organic_scales_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS4comment").val(), organic_scales_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        // De Boer Criteria
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS5").val(), organic_scales_tab_ruleset[12]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateOS5").val(), organic_scales_tab_ruleset[13]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#OS5comment").val(), organic_scales_tab_ruleset[14]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        // Validating Relative Permeability
        titleTab = "";
        tabTitle = "Tab: Relative Permeability";

        // Number Of Days Below Saturation Pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP1").val(), relative_permeability_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP1").val(), relative_permeability_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP1comment").val(), relative_permeability_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Difference between current reservoir pressure and saturation pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP2").val(), relative_permeability_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP2").val(), relative_permeability_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP2comment").val(), relative_permeability_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Cumulative Water Produced
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP3").val(), relative_permeability_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP3").val(), relative_permeability_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP3comment").val(), relative_permeability_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Pore Size Diameter Approximation By Katz And Thompson Correlation (d = 1/√(ϕ * k))
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP4").val(), relative_permeability_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateRP4").val(), relative_permeability_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#RP4comment").val(), relative_permeability_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        // Validating Induced Damage
        titleTab = "";
        tabTitle = "Tab: Induced Damage";

        // Gross Pay (ft)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID1").val(), induce_damage_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID1").val(), induce_damage_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID1comment").val(), induce_damage_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Total polymer pumped during Hydraulic Fracturing (lbs)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID2").val(), induce_damage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID2").val(), induce_damage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID2comment").val(), induce_damage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Total volume of water based fluids pumped into the well (bbl)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID3").val(), induce_damage_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID3").val(), induce_damage_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID3comment").val(), induce_damage_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Mud Losses
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID4").val(), induce_damage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateID4").val(), induce_damage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#ID4comment").val(), induce_damage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        // Validating Geomechanical Damage
        titleTab = "";
        tabTitle = "Tab: Geomechanical Damage";

        // Percentage of Net Pay exihibiting Natural (fraction)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD1").val(), geomechanical_damage_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD1").val(), geomechanical_damage_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD1comment").val(), geomechanical_damage_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Drawdown, i.e, reservoir pressure minus BHFP (psi)
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD2").val(), geomechanical_damage_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD2").val(), geomechanical_damage_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD2comment").val(), geomechanical_damage_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Ratio of KH)matrix + fracture / KH)matrix
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD3").val(), geomechanical_damage_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD3").val(), geomechanical_damage_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD3comment").val(), geomechanical_damage_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        // Geomechanical Damage Expressed As Fraction Of Base Permeability At BHFP
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD4").val(), geomechanical_damage_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#dateGD4").val(), geomechanical_damage_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#GD4comment").val(), geomechanical_damage_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];

        if (validationMessages.length > 0) {
            var evt = window.event || arguments.callee.caller.arguments[0];
            evt.preventDefault();
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
    }
</script>