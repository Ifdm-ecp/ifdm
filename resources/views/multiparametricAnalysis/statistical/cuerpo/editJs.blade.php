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
        // multiparametricoStatistical();

        //Fill Weights
        multiparametricWeights();

        fillInputFields(); 

        var d = new Date();
        var strDate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
        if( $('#checkbox_general_MS').val() == 'on' ) {
            if ( $("#MS1_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_MS1'+formation).val() === '') { $('#date_MS1'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#MS2_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_MS2'+formation).val() === '') { $('#date_MS2'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#MS3_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_MS3'+formation).val() === '') { $('#date_MS3'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#MS4_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_MS4'+formation).val() === '') { $('#date_MS4'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#MS5_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_MS5'+formation).val() === '') { $('#date_MS5'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
        }
        if( $('#checkbox_general_FB').val() == 'on' ) {
            if ( $("#FB1_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_FB1'+formation).val() === '') { $('#date_FB1'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#FB2_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_FB2'+formation).val() === '') { $('#date_FB2'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#FB3_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_FB3'+formation).val() === '') { $('#date_FB3'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#FB4_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_FB4'+formation).val() === '') { $('#date_FB4'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#FB5_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_FB5'+formation).val() === '') { $('#date_FB5'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
        }
        if( $('#checkbox_general_OS').val() == 'on' ) {
            if ( $("#OS1_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_OS1'+formation).val() === '') { $('#date_OS1'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#OS2_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_OS2'+formation).val() === '') { $('#date_OS2'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#OS3_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_OS3'+formation).val() === '') { $('#date_OS3'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#OS4_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_OS4'+formation).val() === '') { $('#date_OS4'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#OS5_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_OS5'+formation).val() === '') { $('#date_OS5'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
        }
        if( $('#checkbox_general_RP').val() == 'on' ) {
            if ( $("#RP1_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_RP1'+formation).val() === '') { $('#date_RP1'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#RP2_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_RP2'+formation).val() === '') { $('#date_RP2'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#RP3_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_RP3'+formation).val() === '') { $('#date_RP3'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#RP4_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_RP4'+formation).val() === '') { $('#date_RP4'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#RP5_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_RP5'+formation).val() === '') { $('#date_RP5'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
        }
        if( $('#checkbox_general_ID').val() == 'on' ) {
            if ( $("#ID1_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_ID1'+formation).val() === '') { $('#date_ID1'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#ID2_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_ID2'+formation).val() === '') { $('#date_ID2'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#ID3_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_ID3'+formation).val() === '') { $('#date_ID3'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#ID4_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_ID4'+formation).val() === '') { $('#date_ID4'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
        }
        if( $('#checkbox_general_GD').val() == 'on' ) {
            if ( $("#GD1_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_GD1'+formation).val() === '') { $('#date_GD1'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#GD2_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_GD2'+formation).val() === '') { $('#date_GD2'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#GD3_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_GD3'+formation).val() === '') { $('#date_GD3'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
            if ( $("#GD4_checkbox").prop('checked') == true ) {
                Object.keys(<?php echo json_encode($formationsWithoutSpaces); ?>).forEach(key => {
                    formation = <?php echo json_encode($formationsWithoutSpaces); ?>[key];
                    if( $('#date_GD4'+formation).val() === '') { $('#date_GD4'+formation).val(d.getDate() + '/' + (d.getMonth()+1) + "/" + d.getFullYear()); }
                });
            }
        }

        
        if ($("#checkbox_general_MS").prop('checked') == true) {
            if ($("#MS1_checkbox").prop('checked') == true && $('#weight_MS1').val() == '') {
                $('#weight_MS1').val(0.20);
            }
            if ($("#MS2_checkbox").prop('checked') === true && $('#weight_MS2').val() === '') {
                $('#weight_MS2').val(0.20);
            }
            if ($("#MS3_checkbox").prop('checked') === true && $('#weight_MS3').val() === '') {
                $('#weight_MS3').val(0.20);
            }
            if ($("#MS4_checkbox").prop('checked') === true && $('#weight_MS4').val() === '') {
                $('#weight_MS4').val(0.20);
            }
            if ($("#MS5_checkbox").prop('checked') === true && $('#weight_MS5').val() === '') {
                $('#weight_MS5').val(0.20);
            }
            if ($("#FB1_checkbox").prop('checked') === true && $('#weight_FB1').val() === '') {
                $('#weight_FB1').val(0.20);
            }
            if ($("#FB2_checkbox").prop('checked') === true && $('#weight_FB2').val() === '') {
                $('#weight_FB2').val(0.20);
            }
            if ($("#FB3_checkbox").prop('checked') === true && $('#weight_FB3').val() === '') {
                $('#weight_FB3').val(0.20);
            }
            if ($("#FB4_checkbox").prop('checked') === true && $('#weight_FB4').val() === '') {
                $('#weight_FB4').val(0.20);
            }
            if ($("#FB5_checkbox").prop('checked') === true && $('#weight_FB5').val() === '') {
                $('#weight_FB5').val(0.20);
            }
            if ($("#OS1_checkbox").prop('checked') === true && $('#weight_OS1').val() === '') {
                $('#weight_OS1').val(0.20);
            }
            if ($("#OS2_checkbox").prop('checked') === true && $('#weight_OS2').val() === '') {
                $('#weight_OS2').val(0.20);
            }
            if ($("#OS3_checkbox").prop('checked') === true && $('#weight_OS3').val() === '') {
                $('#weight_OS3').val(0.20);
            }
            if ($("#OS4_checkbox").prop('checked') === true && $('#weight_OS4').val() === '') {
                $('#weight_OS4').val(0.20);
            }
            if ($("#OS5_checkbox").prop('checked') === true && $('#weight_OS5').val() === '') {
                $('#weight_OS5').val(0.20);
            }
            if ($("#RP1_checkbox").prop('checked') === true && $('#weight_RP1').val() === '') {
                $('#weight_RP1').val(0.20);
            }
            if ($("#RP2_checkbox").prop('checked') === true && $('#weight_RP2').val() === '') {
                $('#weight_RP2').val(0.20);
            }
            if ($("#RP3_checkbox").prop('checked') === true && $('#weight_RP3').val() === '') {
                $('#weight_RP3').val(0.20);
            }
            if ($("#RP4_checkbox").prop('checked') === true && $('#weight_RP4').val() === '') {
                $('#weight_RP4').val(0.20);
            }
            if ($("#RP5_checkbox").prop('checked') === true && $('#weight_RP5').val() === '') {
                $('#weight_RP5').val(0.20);
            }
            if ($("#ID1_checkbox").prop('checked') === true && $('#weight_ID1').val() === '') {
                $('#weight_ID1').val(0.25);
            }
            if ($("#ID2_checkbox").prop('checked') === true && $('#weight_ID2').val() === '') {
                $('#weight_ID2').val(0.25);
            }
            if ($("#ID3_checkbox").prop('checked') === true && $('#weight_ID3').val() === '') {
                $('#weight_ID3').val(0.25);
            }
            if ($("#ID4_checkbox").prop('checked') === true && $('#weight_ID4').val() === '') {
                $('#weight_ID4').val(0.25);
            }
            if ($("#GD1_checkbox").prop('checked') === true && $('#weight_GD1').val() === '') {
                $('#weight_GD1').val(0.25);
            }
            if ($("#GD2_checkbox").prop('checked') === true && $('#weight_GD2').val() === '') {
                $('#weight_GD2').val(0.25);
            }
            if ($("#GD3_checkbox").prop('checked') === true && $('#weight_GD3').val() === '') {
                $('#weight_GD3').val(0.25);
            }
            if ($("#GD4_checkbox").prop('checked') === true && $('#weight_GD4').val() === '') {
                $('#weight_GD4').val(0.25);
            }
        }


        setTimeout(function (){
            
            if( $("#statistical").prop('checked') == true ) {
                if ($('#p10_MS1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS1'}, function(data) {$('#p10_MS1').val(data[0]);}); }
                if ($('#p10_MS2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS2'}, function(data) {$('#p10_MS2').val(data[0]);}); }
                if ($('#p10_MS3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS3'}, function(data) {$('#p10_MS3').val(data[0]);}); }
                if ($('#p10_MS4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS4'}, function(data) {$('#p10_MS4').val(data[0]);}); }
                if ($('#p10_MS5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS5'}, function(data) {$('#p10_MS5').val(data[0]);}); }
                if ($('#p10_FB1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB1'}, function(data) {$('#p10_FB1').val(data[0]);}); }
                if ($('#p10_FB2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB2'}, function(data) {$('#p10_FB2').val(data[0]);}); }
                if ($('#p10_FB3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB3'}, function(data) {$('#p10_FB3').val(data[0]);}); }
                if ($('#p10_FB4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB4'}, function(data) {$('#p10_FB4').val(data[0]);}); }
                if ($('#p10_FB5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB5'}, function(data) {$('#p10_FB5').val(data[0]);}); }
                if ($('#p10_OS1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS1'}, function(data) {$('#p10_OS1').val(data[0]);}); }
                if ($('#p10_OS2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS2'}, function(data) {$('#p10_OS2').val(data[0]);}); }
                if ($('#p10_OS3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS3'}, function(data) {$('#p10_OS3').val(data[0]);}); }
                if ($('#p10_OS4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS4'}, function(data) {$('#p10_OS4').val(data[0]);}); }
                if ($('#p10_OS5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS5'}, function(data) {$('#p10_OS5').val(data[0]);}); }
                if ($('#p10_RP1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP1'}, function(data) {$('#p10_RP1').val(data[0]);}); }
                if ($('#p10_RP2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP2'}, function(data) {$('#p10_RP2').val(data[0]);}); }
                if ($('#p10_RP3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP3'}, function(data) {$('#p10_RP3').val(data[0]);}); }
                if ($('#p10_RP4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP4'}, function(data) {$('#p10_RP4').val(data[0]);}); }
                if ($('#p10_RP5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP5'}, function(data) {$('#p10_RP5').val(data[0]);}); }
                if ($('#p10_ID1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID1'}, function(data) {$('#p10_ID1').val(data[0]);}); }
                if ($('#p10_ID2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID2'}, function(data) {$('#p10_ID2').val(data[0]);}); }
                if ($('#p10_ID3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID3'}, function(data) {$('#p10_ID3').val(data[0]);}); }
                if ($('#p10_ID4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID4'}, function(data) {$('#p10_ID4').val(data[0]);}); }
                if ($('#p10_GD1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD1'}, function(data) {$('#p10_GD1').val(data[0]);}); }
                if ($('#p10_GD2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD2'}, function(data) {$('#p10_GD2').val(data[0]);}); }
                if ($('#p10_GD3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD3'}, function(data) {$('#p10_GD3').val(data[0]);}); }
                if ($('#p10_GD4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD4'}, function(data) {$('#p10_GD4').val(data[0]);}); }
                if ($('#p90_MS1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS1'}, function(data) {$('#p90_MS1').val(data[1]);}); }
                if ($('#p90_MS2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS2'}, function(data) {$('#p90_MS2').val(data[1]);}); }
                if ($('#p90_MS3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS3'}, function(data) {$('#p90_MS3').val(data[1]);}); }
                if ($('#p90_MS4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS4'}, function(data) {$('#p90_MS4').val(data[1]);}); }
                if ($('#p90_MS5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS5'}, function(data) {$('#p90_MS5').val(data[1]);}); }
                if ($('#p90_FB1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB1'}, function(data) {$('#p90_FB1').val(data[1]);}); }
                if ($('#p90_FB2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB2'}, function(data) {$('#p90_FB2').val(data[1]);}); }
                if ($('#p90_FB3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB3'}, function(data) {$('#p90_FB3').val(data[1]);}); }
                if ($('#p90_FB4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB4'}, function(data) {$('#p90_FB4').val(data[1]);}); }
                if ($('#p90_FB5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'FB5'}, function(data) {$('#p90_FB5').val(data[1]);}); }
                if ($('#p90_OS1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS1'}, function(data) {$('#p90_OS1').val(data[1]);}); }
                if ($('#p90_OS2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS2'}, function(data) {$('#p90_OS2').val(data[1]);}); }
                if ($('#p90_OS3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS3'}, function(data) {$('#p90_OS3').val(data[1]);}); }
                if ($('#p90_OS4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS4'}, function(data) {$('#p90_OS4').val(data[1]);}); }
                if ($('#p90_OS5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'OS5'}, function(data) {$('#p90_OS5').val(data[1]);}); }
                if ($('#p90_RP1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP1'}, function(data) {$('#p90_RP1').val(data[1]);}); }
                if ($('#p90_RP2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP2'}, function(data) {$('#p90_RP2').val(data[1]);}); }
                if ($('#p90_RP3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP3'}, function(data) {$('#p90_RP3').val(data[1]);}); }
                if ($('#p90_RP4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP4'}, function(data) {$('#p90_RP4').val(data[1]);}); }
                if ($('#p90_RP5').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'RP5'}, function(data) {$('#p90_RP5').val(data[1]);}); }
                if ($('#p90_ID1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID1'}, function(data) {$('#p90_ID1').val(data[1]);}); }
                if ($('#p90_ID2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID2'}, function(data) {$('#p90_ID2').val(data[1]);}); }
                if ($('#p90_ID3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID3'}, function(data) {$('#p90_ID3').val(data[1]);}); }
                if ($('#p90_ID4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'ID4'}, function(data) {$('#p90_ID4').val(data[1]);}); }
                if ($('#p90_GD1').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD1'}, function(data) {$('#p90_GD1').val(data[1]);}); }
                if ($('#p90_GD2').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD2'}, function(data) {$('#p90_GD2').val(data[1]);}); }
                if ($('#p90_GD3').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD3'}, function(data) {$('#p90_GD3').val(data[1]);}); }
                if ($('#p90_GD4').val() === '') { $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'GD4'}, function(data) {$('#p90_GD4').val(data[1]);}); }
            } else {

                var myArray = encodeURIComponent(JSON.stringify($('#field').val()));
                if( $('#checkbox_general_MS').val() == 'on' ) {
                    if ( $("#MS1_checkbox").prop('checked') == true ) {
                        if ( $("#p10_MS1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_MS1').val(data[0]);}); }
                        if ( $("#p90_MS1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_MS1').val(data[1]);}); }
                    }
                    if ( $("#MS2_checkbox").prop('checked') == true ) {
                        if ( $("#p10_MS2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_MS2').val(data[0]);}); }
                        if ( $("#p90_MS2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_MS2').val(data[1]);}); }
                    }
                    if ( $("#MS3_checkbox").prop('checked') == true ) {
                        if ( $("#p10_MS3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_MS3').val(data[0]);}); }
                        if ( $("#p90_MS3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_MS3').val(data[1]);}); }
                    }
                    if ( $("#MS4_checkbox").prop('checked') == true ) {
                        if ( $("#p10_MS4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_MS4').val(data[0]);}); }
                        if ( $("#p90_MS4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_MS4').val(data[1]);}); }
                    }
                    if ( $("#MS5_checkbox").prop('checked') == true ) {
                        if ( $("#p10_MS5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_MS5').val(data[0]);}); }
                        if ( $("#p90_MS5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_MS5').val(data[1]);}); }
                    }
                }
                if( $('#checkbox_general_FB').val() == 'on' ) {
                    if ( $("#FB1_checkbox").prop('checked') == true ) {
                        if ( $("#p10_FB1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_FB1').val(data[0]);}); }
                        if ( $("#p90_FB1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_FB1').val(data[1]);}); }
                    }
                    if ( $("#FB2_checkbox").prop('checked') == true ) {
                        if ( $("#p10_FB2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_FB2').val(data[0]);}); }
                        if ( $("#p90_FB2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_FB2').val(data[1]);}); }
                    }
                    if ( $("#FB3_checkbox").prop('checked') == true ) {
                        if ( $("#p10_FB3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_FB3').val(data[0]);}); }
                        if ( $("#p90_FB3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_FB3').val(data[1]);}); }
                    }
                    if ( $("#FB4_checkbox").prop('checked') == true ) {
                        if ( $("#p10_FB4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_FB4').val(data[0]);}); }
                        if ( $("#p90_FB4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_OS1').val(data[1]);}); }
                    }
                    if ( $("#FB5_checkbox").prop('checked') == true ) {
                        if ( $("#p10_FB5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_FB5').val(data[0]);}); }
                        if ( $("#p90_FB5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'FB5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_FB5').val(data[1]);}); }
                    }
                }
                if( $('#checkbox_general_OS').val() == 'on' ) {
                    if ( $("#OS1_checkbox").prop('checked') == true ) {
                        if ( $("#p10_OS1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_OS1').val(data[0]);}); }
                        if ( $("#p90_OS1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_OS1').val(data[1]);}); }
                    }
                    if ( $("#OS2_checkbox").prop('checked') == true ) {
                        if ( $("#p10_OS2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_OS2').val(data[0]);}); }
                        if ( $("#p90_OS2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_OS2').val(data[1]);}); }
                    }
                    if ( $("#OS3_checkbox").prop('checked') == true ) {
                        if ( $("#p10_OS3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_OS3').val(data[0]);}); }
                        if ( $("#p90_OS3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_OS3').val(data[1]);}); }
                    }
                    if ( $("#OS4_checkbox").prop('checked') == true ) {
                        if ( $("#p10_OS4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_OS4').val(data[0]);}); }
                        if ( $("#p90_OS4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_OS4').val(data[1]);}); }
                    }
                    if ( $("#OS5_checkbox").prop('checked') == true ) {
                        if ( $("#p10_OS5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_OS5').val(data[0]);}); }
                        if ( $("#p90_OS5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'OS5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_OS5').val(data[1]);}); }
                    }
                }
                if( $('#checkbox_general_RP').val() == 'on' ) {
                    if ( $("#RP1_checkbox").prop('checked') == true ) {
                        if ( $("#p10_RP1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_RP1').val(data[0]);}); }
                        if ( $("#p90_RP1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_RP1').val(data[1]);}); }
                    }
                    if ( $("#RP2_checkbox").prop('checked') == true ) {
                        if ( $("#p10_RP2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_RP2').val(data[0]);}); }
                        if ( $("#p90_RP2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_RP2').val(data[1]);}); }
                    }
                    if ( $("#RP3_checkbox").prop('checked') == true ) {
                        if ( $("#p10_RP3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_RP3').val(data[0]);}); }
                        if ( $("#p90_RP3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_RP3').val(data[1]);}); }
                    }
                    if ( $("#RP4_checkbox").prop('checked') == true ) {
                        if ( $("#p10_RP4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_RP4').val(data[0]);}); }
                        if ( $("#p90_RP4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_RP4').val(data[1]);}); }
                    }
                    if ( $("#RP5_checkbox").prop('checked') == true ) {
                        if ( $("#p10_RP5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_RP5').val(data[0]);}); }
                        if ( $("#p90_RP5").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'RP5', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_RP5').val(data[1]);}); }
                    }
                }
                if( $('#checkbox_general_ID').val() == 'on' ) {
                    if ( $("#ID1_checkbox").prop('checked') == true ) {
                        if ( $("#p10_ID1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_ID1').val(data[0]);}); }
                        if ( $("#p90_ID1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_ID1').val(data[1]);}); }
                    }
                    if ( $("#ID2_checkbox").prop('checked') == true ) {
                        if ( $("#p10_ID2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_ID2').val(data[0]);}); }
                        if ( $("#p90_ID2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_ID2').val(data[1]);}); }
                    }
                    if ( $("#ID3_checkbox").prop('checked') == true ) {
                        if ( $("#p10_ID3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_ID3').val(data[0]);}); }
                        if ( $("#p90_ID3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_ID3').val(data[1]);}); }
                    }
                    if ( $("#ID4_checkbox").prop('checked') == true ) {
                        if ( $("#p10_ID4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_ID4').val(data[0]);}); }
                        if ( $("#p90_ID4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'ID4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_ID4').val(data[1]);}); }
                    }
                }
                if( $('#checkbox_general_GD').val() == 'on' ) {
                    if ( $("#GD1_checkbox").prop('checked') == true ) {
                        if ( $("#p10_GD1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_GD1').val(data[0]);}); }
                        if ( $("#p90_GD1").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD1', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_GD1').val(data[1]);}); }
                    }
                    if ( $("#GD2_checkbox").prop('checked') == true ) {
                        if ( $("#p10_GD2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_GD2').val(data[0]);}); }
                        if ( $("#p90_GD2").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD2', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_GD2').val(data[1]);}); }
                    }
                    if ( $("#GD3_checkbox").prop('checked') == true ) {
                        if ( $("#p10_GD3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_GD3').val(data[0]);}); }
                        if ( $("#p90_GD3").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD3', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_GD3').val(data[1]);}); }
                    }
                    if ( $("#GD4_checkbox").prop('checked') == true ) {
                        if ( $("#p10_GD4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p10_GD4').val(data[0]);}); }
                        if ( $("#p90_GD4").val() === '' ) { $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'GD4', basin: $("#selectBasin").val(), fields: myArray}, function(data) {$('#p90_GD4').val(data[1]);}); }
                    }
                }

            }

        }, 1500);
  
        // }
        // if( $("#statistical").prop('checked') == false ) {
        //     < ? ph p echo json_encode(explode(",", $statistical->field_statistical)); ?>.forEach(element => { 
        //         $.get("{!! url('p10p90Calculate') !!}", {subparameterId: 'MS1', fieldId: element, basinId: $("#selectBasin").val() }, function(data) {$('#p10_MS1').val(data[0]); $('#p90_MS1').val(data[1]);});
        //     }); 
            // $.get("{!! url('p10p90Colombia') !!}", {subparameterId: 'MS1'}, function(data) {$('#p10_MS1').val(data[0]); $('#p90_MS1').val(data[1]);});
            // console.log($("#selectBasin").val(),'23');
        // }
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
            html = html + '<div class="tabcontent"><div class="row"><div class="col-md-4"><div class="form-group"><label for="selectStored_' + name + '">Stored Previously</label><select name="selectStored_' + name + '" id="selectStored_' + name + '" class=" form-control form-select show-tick" onchange="updateData(`selectStored_' + name + '`,`' + name + '`)">';
            html = html + '<option value="none" selected hidden>Nothing Selected</option>';
            html = html + organizeSelectOptions(title, element, <?php echo json_encode($mediciones); ?>);   


            html = html + '</select></div></div></div>';
            html = html + '<div class="row"><div class="col-md-4"><div class="form-group"><label for="value_' + name + '">Value</label> <label class="red">*</label><div class="input-group ';
            @if ($errors->has('value_MS1LA_PAZ_CG')) 
                html = html + 'has-error';
            @endif
            html = html + '"><input type="text" id="value_' + name + '" name="value_' + name +'" class="form-control value_edit"><span class="input-group-addon" id="basic-addon2">-</span></div></div></div>';
            html = html + '<div class="col-md-4"><div class="form-group"><label for="date_' + name + '">Monitoring Date</label> <label class="red">*</label><div class="input-group ';
            @if ($errors->has('value_MS1LA_PAZ_CG')) 
                html = html + 'has-error';
            @endif
            html = html + '<input type="text" id="date_' + name + '" name="date_' + name + '" placeholder="dd/mm/yyyy" class="form-control value_edit jquery-datepicker"></div></div></div>';
            html = html + '<div class="col-md-4"><div class="form-group"><label for="comment_' + name + '">Comment</label><input type="text" id="comment_' + name + '" name="comment_' + name + '" class="form-control validate"></div></div></div>';
           
            html = html + '</div></div>';
        });
        html = html + '<br><div class="row"><div class="col-md-4"><div class="form-group"><label for="p10_' + title + '">p10</label> <label class="red">*</label><input type="text" id="p10_' + title + '" name="p10_' + title + '" class="form-control validate"></div></div>';
        html = html + '<div class="col-md-4"><div class="form-group"><label for="p90_' + title + '">p90</label> <label class="red">*</label><input type="text" id="p90_' + title + '" name="p90_' + title + '" class="form-control validate"></div></div>';
        html = html + '<div class="col-md-4"><div class="form-group"><label for="weight_' + title + '">Weight</label> <label class="red">*</label><input type="text" id="weight_' + title + '" name="weight_' + title + '" class="form-control weight_ms_count"></div></div></div>';  

        html = html + '</div></div>';
        $("#"+destination_div).append(html);
    }

    function fillInputFields() {
        var indexes = ['MS1', 'MS2', 'MS3', 'MS4', 'MS5', 'FB1', 'FB2', 'FB3', 'FB4', 'FB5', 'OS1', 'OS2', 'OS3', 'OS4', 'OS5', 'RP1', 'RP2', 'RP3', 'RP4', 'RP5', 'ID1', 'ID2', 'ID3', 'ID4', 'GD1', 'GD2', 'GD3', 'GD4'];
        <?php echo json_encode($valores); ?>.forEach(valor => {
            $("#value_"+valor[3]+valor[4]).val(valor[0]);
            $("#date_"+valor[3]+valor[4]).val(valor[1]);
            $("#comment_"+valor[3]+valor[4]).val(valor[2]);
        });
        for (let index = 0; index < <?php echo json_encode($pesos); ?>.length; index++) {
            $("#weight_"+indexes[index]).val(<?php echo json_encode($pesos); ?>[index]);
            // console.log("#p10_"+indexes[index], <?php echo json_encode($statistical); ?>['p10_'+indexes[index].toLowerCase()]);
            $("#p10_"+indexes[index]).val(<?php echo json_encode($statistical); ?>['p10_'+indexes[index].toLowerCase()]);
            $("#p90_"+indexes[index]).val(<?php echo json_encode($statistical); ?>['p90_'+indexes[index].toLowerCase()]);
        }
        for (let index = 0; index < indexes.length; index++) {
            if (<?php echo json_encode($checkboxes); ?>[index] == 1) {
                $("#"+indexes[index]+"_checkbox").prop('checked', true).change();
                // availableEnableDisableFields(indexes[index]);
            } else {
                $("#"+indexes[index]+"_checkbox").prop('checked', false).change();
                // availableEnableDisableFields(indexes[index]);
            }
        }
        if (<?php echo json_encode($generalCheckboxes); ?>[0] == 0) { $("#checkbox_general_MS").prop('checked', false).change(); }
        if (<?php echo json_encode($generalCheckboxes); ?>[1] == 0) { $("#checkbox_general_FB").prop('checked', false).change(); }
        if (<?php echo json_encode($generalCheckboxes); ?>[2] == 0) { $("#checkbox_general_OS").prop('checked', false).change(); }
        if (<?php echo json_encode($generalCheckboxes); ?>[3] == 0) { $("#checkbox_general_RP").prop('checked', false).change(); }
        if (<?php echo json_encode($generalCheckboxes); ?>[4] == 0) { $("#checkbox_general_ID").prop('checked', false).change(); }
        if (<?php echo json_encode($generalCheckboxes); ?>[5] == 0) { $("#checkbox_general_GD").prop('checked', false).change(); }

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
    function saveMultiparametric(action) {
        $("#only_s").val("save");
        $("#multiparametricStatisticalForm").submit(); 
    }

    function runMultiparametric() {
        $("#only_s").val("run");
        $("#multiparametricStatisticalForm").submit();
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