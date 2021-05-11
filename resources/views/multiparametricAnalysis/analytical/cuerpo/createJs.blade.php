<script type="text/javascript">
    $(document).ready(function(){
        import_tree("Multiparametric", "multiparametrico_analytical");
        seleccionarFluido();
        loadSubparametersHistoricalByWell();
    });

    // Load all the subparameter historical data available for each subparameter
    function loadSubparametersHistoricalByWell() {
        $(".ms-subparameter-picker").empty();

        $.get("{{url('subparameterbywellanalytical')}}", {
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

        if (parameterAffected == 'FB3') {
            $("#critical_radius").val(parameterData[0]);
        } else if (parameterAffected == 'ID3') {
            $("#total_volumen").val(parameterData[0]);
        }
    });

    $('.input-group').prepend('<span class="input-group-btn"><button type="button" class="btn btn-default button-advisor onclick="pendejoclick()" conTooltip"><span class="glyphicon glyphicon-info-sign"></span></button></span>');
    $('.input-group > input[type=text]').each(function() {
        let name =  $(this).attr('name');
        $('input[name='+name+']').attr('id', name);
    });

	$('#fluid_type').change(function(){
		seleccionarFluido();
	});

	function seleccionarFluido()
	{
		var fluido = $('#fluid_type').val();
		if (fluido == 'Oil') {
            $("#div_oil").show();
            $("#div_gas").hide();
			$("#div_oil *, #input_oil *").attr('disabled', false).show();
            $("#div_gas *, #input_gas *").attr('disabled', true).hide();
            $("#permeability_type").text('Oil Permeability');
		} else if (fluido == 'Gas') {
            $("#div_oil").hide();
            $("#div_gas").show();
			$("#div_oil *, #input_oil *").attr('disabled', true).hide();
            $("#div_gas *, #input_gas *").attr('disabled', false).show();
            $("#permeability_type").text('Condensate Permeability');
		}
	}

	//Función para gráficos de presión vs radios 
	 $('#plot').click(function(e){
    	e.preventDefault();

        //se traen los valores
        var cp_msp = parseFloat($("input[name = mineral_scale_cp]").val());//Mineral Scales*
        var cp_osp = parseFloat($("input[name = organic_scale_cp]").val());//Organic Scales*
        var cp_krp = parseFloat($("input[name = saturation_presure]").val());//Saturation Pressure*
        var cp_gdp = parseFloat($("input[name = geomechanical_damage_cp]").val());//DrawDown*
        var cp_mcv = parseFloat($("input[name = critical_radius]").val());//Critical Radius Derived From Maximum Critical Velocity , Vc
        var tv_wbf = parseFloat($("input[name = total_volumen]").val());//Total Volumen Of Water Based Fluids Pumped Into The Well*
        var netpay = parseFloat($("input[name = netpay]").val());//netpay
        var condensate_perm = parseFloat($("input[name = absolute_permeability]").val());//Absolute Permeability
        var permeability = parseFloat($("input[name = permeability]").val());//Permeability
        var oil_viscosity = parseFloat($("input[name = viscosity_oil]").val());//viscosity
        var oil_vol_factor = parseFloat($("input[name = volumetric_factor_oil]").val());//Volumetric Factor
        var oil_rate = parseFloat($("input[name = fluid_rate_oil]").val());//fluid_rate
        var gas_rate = parseFloat($("input[name = fluid_rate_gas]").val());//fluid_rate
        var gas_viscosity = parseFloat($("input[name = viscosity_gas]").val());//viscosity
        var gas_vol_factor = parseFloat($("input[name = volumetric_factor_gas]").val());//Volumetric Factor
        var well_radius = parseFloat($("input[name = well_radius]").val());//Well Radius
        var radius = parseFloat($("input[name = well_radius]").val());//el mismo Well Radius
        var external_radius = parseFloat($("input[name = drainage_radius]").val());//Drainage Radius
        var porosity = parseFloat($("input[name = porosity]").val());//porosity
        var bhp = parseFloat($("input[name = bhp]").val());//nhp
        var type_of_well = $("#fluid_type").val();//Fluid Type

        //se crean los arrays en 0
        var pressures_total = [];
        var radius_total = [];
        var pressure_radius_final = [];
        var pressures_data = [];
        var radius_data = [];
        var pr = bhp;
        var fbp_radius = cp_mcv + radius;
        var idp_radius = Math.sqrt(((tv_wbf) / (Math.PI * netpay * porosity)) + well_radius);

        if (type_of_well == "Oil") {
            while (radius < external_radius) {
                pr = bhp + (((141.2 * oil_rate * oil_viscosity * oil_vol_factor) / (netpay * condensate_perm)) * (Math.log(radius / well_radius) - (0.5 * radius / Math.pow(external_radius, 2))));
                pressures_data.push(pr);
                radius_data.push(radius);
                radius = radius + 0.05;
            }
        } else {
            while (radius < external_radius) {
                pr = bhp + (((141.2 * gas_rate * 1000000 * gas_viscosity * gas_vol_factor) / (5.615 * netpay * condensate_perm)) * (Math.log(radius / well_radius) - (0.5 * radius / Math.pow(external_radius, 2))));
                pressures_data.push(pr);
                radius_data.push(radius);
                radius = radius + 0.05;
            }
        }

        var critical_pressures = [cp_msp, cp_osp, cp_krp, cp_gdp];

        //Interpolación lineal presiones críticas
        for (var i = 0; i < critical_pressures.length; i++) {
            if (critical_pressures[i] >= pressures_data[pressures_data.length - 1]) {
                pressure_radius = [external_radius + well_radius, critical_pressures[i]];
                pressure_radius_final.push(pressure_radius);
            } else if (critical_pressures[i] <= bhp) {
                pressure_radius = [well_radius, critical_pressures[i]];
                pressure_radius_final.push(pressure_radius);
            } else {
                for (var j = 0; j < pressures_data.length - 1; j++) {
                    var calculated_pressure = pressures_data[j] - critical_pressures[i];
                    
                    if (i == 3) {
                        calculated_pressure = 1 - ((pressures_data[pressures_data.length - 1] - pressures_data[j]) / (pressures_data[pressures_data.length - 1] - pressures_data[0]));
                    }

                    if (calculated_pressure >= well_radius) {
                        pressure_radius = [radius_data[j] + well_radius, critical_pressures[i]];
                        pressure_radius_final.push(pressure_radius);
                        break;
                    }
                }
            }
        }

        var radius_final = [pressure_radius_final[0][0], fbp_radius, pressure_radius_final[1][0], pressure_radius_final[2][0], idp_radius, pressure_radius_final[3][0]];

        var final_data = [];
        for (var i = 0; i < pressures_data.length; i++) {
            var row = [radius_data[i], pressures_data[i]];
            final_data.push(row);
        }

        $('#chart').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Critical Pressure vs Radius',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Radius [ft]'
                }
            },
            yAxis: {
                title: {
                    text: 'Pressure [psi]'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br>' + '<b>Radius: </b>' + this.key + ' ft' + '<br><b>Pressure:</b> ' + this.y + ' psi';
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 150,
                floating: true,
                borderWidth: 1,
                backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                shadow: true
            },
            series: [{
                    name: '',
                    data: final_data
                },
                {
                    name: 'Mineral Scales CP',
                    data: [pressure_radius_final[0]]
                },
                {
                    name: 'Organic Scales CP',
                    data: [pressure_radius_final[1]],
                    marker: {
                        enabled: true
                    }
                },
                {
                    name: 'Relative Permeability CP',
                    data: [pressure_radius_final[2]],
                    marker: {
                        enabled: true
                    }
                },
                {
                    name: 'Geomechanical Damage CP',
                    data: [pressure_radius_final[3]],
                    marker: {
                        enabled: true
                    }
                }
            ]
        });

        var analytical =  {
            netpay: parseFloat($("input[name = netpay]").val()),
            absolute_permeability: parseFloat($("input[name = absolute_permeability]").val()),
            porosity: parseFloat($("input[name = porosity]").val()),
            permeability: parseFloat($("input[name = permeability]").val()),
            fluid_type: $("#fluid_type").val(),
            viscosity: $("#fluid_type").val() == "Oil" ? parseFloat($("input[name = viscosity_oil]").val()) : parseFloat($("input[name = viscosity_gas]").val()),
            volumetric_factor: $("#fluid_type").val() == "Oil" ? parseFloat($("input[name = volumetric_factor_oil]").val()) : parseFloat($("input[name = volumetric_factor_gas]").val()),
            well_radius: parseFloat($("input[name = well_radius]").val()),
            drainage_radius: parseFloat($("input[name = drainage_radius]").val()),
            reservoir_pressure: parseFloat($("input[name = reservoir_pressure]").val()),
            fluid_rate: $("#fluid_type").val() == "Oil" ? parseFloat($("input[name = fluid_rate_oil]").val()) : parseFloat($("input[name = fluid_rate_gas]").val()),
            bhp: parseFloat($("input[name = bhp]").val()),
            critical_radius: parseFloat($("input[name = critical_radius]").val()),
            total_volumen: parseFloat($("input[name = total_volumen]").val()),
            saturation_presure: parseFloat($("input[name = saturation_presure]").val()),
            mineral_scale_cp: parseFloat($("input[name = mineral_scale_cp]").val()),
            organic_scale_cp: parseFloat($("input[name = organic_scale_cp]").val()),
            geomechanical_damage_cp: parseFloat($("input[name = geomechanical_damage_cp]").val())
        };
        
        result_profile = PvsR_profile(analytical);
        pressures_data = result_profile[1];
        radius_data = result_profile[0];

        PrPcMSP = calculate_Pr(pressures_data, analytical.mineral_scale_cp);
        PrPcOSP = calculate_Pr(pressures_data, analytical.organic_scale_cp);
        PrPcKRP = calculate_Pr(pressures_data, analytical.saturation_presure);
        PrPGDP = calculate_Pr(pressures_data, analytical.geomechanical_damage_cp);

        FractionMaxDD = FractionMaxDD(pressures_data);
        SumFractionMaxDD = FractionMaxDD.reduce((a, b) => a + b, 0);
        CummDD = CummDD(FractionMaxDD);

        //Skin Radius By FD Mechanism
        ms = SkinRadius(analytical, pressures_data, radius_data, PrPcMSP, PrPcOSP, PrPcKRP, PrPGDP, FractionMaxDD, SumFractionMaxDD, CummDD, 'ms');
        fb = SkinRadius(analytical, pressures_data, radius_data, PrPcMSP, PrPcOSP, PrPcKRP, PrPGDP, FractionMaxDD, SumFractionMaxDD, CummDD, 'fb');
        os = SkinRadius(analytical, pressures_data, radius_data, PrPcMSP, PrPcOSP, PrPcKRP, PrPGDP, FractionMaxDD, SumFractionMaxDD, CummDD, 'os');
        rp = SkinRadius(analytical, pressures_data, radius_data, PrPcMSP, PrPcOSP, PrPcKRP, PrPGDP, FractionMaxDD, SumFractionMaxDD, CummDD, 'rp');
        id = SkinRadius(analytical, pressures_data, radius_data, PrPcMSP, PrPcOSP, PrPcKRP, PrPGDP, FractionMaxDD, SumFractionMaxDD, CummDD, 'id');
        gd = SkinRadius(analytical, pressures_data, radius_data, PrPcMSP, PrPcOSP, PrPcKRP, PrPGDP, FractionMaxDD, SumFractionMaxDD, CummDD, 'gd');

        radius_final = [ms, fb, os, rp, id, gd];

        $('#chart_2').highcharts({
            chart: {
                type: 'bar',
                zoomType: 'xy'
            },
            title: {
                text: 'Radius By Damage Mechanism',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Damage Mechanism'
                },
                categories: ['Mineral Scales', 'Fine Blockage', 'Organic Scales', 'Relative Permeability', 'Induced Damage', 'Geomechanical Damage']
            },
            yAxis: {
                title: {
                    text: 'Radius [ft]'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true,
                        align: 'left',   
                        inside: true
                    }
                }
            },
            tooltip: {
                valueSuffix: ' ft'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                shadow: true
            },
            series: [{
                name: 'Radius',
                data: radius_final
            }]
        });
    });

    function PvsR_profile(analytical)
    {
        pressures_data = [];
        radius_data = [];
        Radius = analytical.well_radius;
        Pr = analytical.bhp;
        pressures_data.push(Pr);
        radius_data.push(Radius);
  
        counter = 1;

        while (counter <= 3171) {

            if (counter <= 200) {
                Radius = Radius + 0.05;
            } else {
                Radius = Radius + 0.5;
            }

            if (analytical.fluid_type == "Oil") {
                Pr = analytical.bhp + (((141.2 * analytical.fluid_rate * analytical.viscosity * analytical.volumetric_factor) / (analytical.netpay * analytical.absolute_permeability)) * (Math.log(Radius / analytical.well_radius) - ( (Radius * Radius) / (2 * analytical.drainage_radius * analytical.drainage_radius ))));
                Pr = Pr.toFixed(8);
                pressures_data.push(Pr);
                radius_data.push(Radius);
            } else {
                Pr = analytical.bhp + (((141.2 * (analytical.fluid_rate * 1000000 / 5.615) * analytical.viscosity * analytical.volumetric_factor) / (analytical.netpay * analytical.absolute_permeability)) * (Math.log(Radius / analytical.well_radius) - ( (Radius * Radius) / (2 * analytical.drainage_radius * analytical.drainage_radius ))));
                Pr = Pr.toFixed(8);
                pressures_data.push(Pr);
                radius_data.push(Radius);
            }

            counter = counter + 1;
        }
        return [radius_data, pressures_data];
    }

    function calculate_Pr(pressures_data, value_aux) 
    {
        PrArray = [];

        pressures_data.forEach(function(pressure) {
            PrArray.push(pressure - value_aux);
        });

        return PrArray;
    }

    function FractionMaxDD(pressures_data) 
    {
        FractionMaxDD = [];

        pressures_data.forEach(function(pressure) {
            FractionMaxDD.push((pressures_data[pressures_data.length - 1]-pressure)/(pressures_data[pressures_data.length - 1]-pressures_data[0]));
        });

        return FractionMaxDD;
    }

    function CummDD(FractionMaxDD)
    {
        CummDD = [];

        FractionMaxDD.forEach(function(fraction) {
            CummDD.push(1 - fraction);
        });

        return CummDD;
    }

    function SkinRadius(analytical, pressures_data, radius_data, PrPcMSP, PrPcOSP, PrPcKRP, PrPGDP, FractionMaxDD, SumFractionMaxDD, CummDD, funcion) {
        skinRadius = 0.0;
        if (funcion == "ms") {
            if (PrPcMSP[0] > 0) {
                skin = 0;
            } else {
                counter_flag = 0;
                for (i = 0; i < PrPcMSP.length; i++) {
                    if(PrPcMSP[i] > 0) {
                        counter_flag = i - 1;
                        break;
                    }
                }
                skin = analytical.well_radius + radius_data[counter_flag];
            }
        } else if (funcion == "fb") {
            skin = analytical.critical_radius + analytical.well_radius;
        } else if (funcion == "os") {
            if (PrPcOSP[0] > 0) {
                skin = 0;
            } else {
                counter_flag = 0;
                for (i = 0; i < PrPcOSP.length; i++) {
                    if(PrPcOSP[i] > 0) {
                        counter_flag = i - 1;
                        break;
                    }
                }
                skin = analytical.well_radius + radius_data[counter_flag];
            }
        } else if (funcion == "rp") {
            if (analytical.reservoir_pressure < analytical.saturation_presure) {
                skin = analytical.drainage_radius;
            } else {
                if (PrPcKRP[0] > 0) {
                    skin = 0;
                } else {
                    counter_flag = 0;
                    for (i = 0; i < PrPcKRP.length; i++) {
                        if(PrPcKRP[i] > 0) {
                            counter_flag = i - 1;
                            break;
                        }
                    }
                    skin = radius_data[counter_flag];
                }
            }
        } else if (funcion == "id") {
            skin = analytical.well_radius + Math.sqrt((analytical.total_volumen * 5.615) / (3.1416 * analytical.netpay * analytical.porosity));
        } else if (funcion == "gd") {
            counter_flag = 0;
            for (i = 0; i < CummDD.length; i++) {
                if(CummDD[i] > 0.25) {
                    counter_flag = i - 1;
                    break;
                }
            }
            skin = analytical.well_radius + radius_data[counter_flag];
        }

        return skin;
    }

    /* verifyMultiparametric
    * Validates the form entirely
    * params {action: string}
    */
    function verifyMultiparametric(action) {
        // Boolean for empty values for the save button
        var emptyValues = false;
        // Title tab for modal errors
        var titleTab = "";
        var tabTitle = "";
        //Saving tables...
        var validationMessages = [];
        var validationFunctionResult = [];

        // Validating Rock Properties
        tabTitle = "Tab: Rock Properties";

        // NetPay
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#netpay").val(), rock_properties_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#netpay").val() === null || $("#netpay").val() === "")) ? true: emptyValues;

        // Absolute Permeability
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#absolute_permeability").val(), rock_properties_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#absolute_permeability").val() === null || $("#absolute_permeability").val() === "")) ? true: emptyValues;

        // Porosity
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#porosity").val(), rock_properties_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#porosity").val() === null || $("#porosity").val() === "")) ? true: emptyValues;

        // Permeability
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#permeability").val(), rock_properties_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#permeability").val() === null || $("#permeability").val() === "")) ? true: emptyValues;


        // Validating Fluid Information
        titleTab = "";
        tabTitle = "Tab: Fluid Information";

        // Fluid Type
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#fluid_type").val(), fluid_information_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#fluid_type").val() === null || $("#fluid_type").val() === "")) ? true: emptyValues;

        if ($("#fluid_type").val() == 'Oil') {
            // Viscosity Oil
            validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#viscosity_oil").val(), fluid_information_tab_ruleset[1]);
            titleTab = validationFunctionResult[0];
            validationMessages = validationFunctionResult[1];
            emptyValues = (emptyValues === false && ($("#viscosity_oil").val() === null || $("#viscosity_oil").val() === "")) ? true: emptyValues;

            // Volumetric Factor Oil
            validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#volumetric_factor_oil").val(), fluid_information_tab_ruleset[2]);
            titleTab = validationFunctionResult[0];
            validationMessages = validationFunctionResult[1];
            emptyValues = (emptyValues === false && ($("#volumetric_factor_oil").val() === null || $("#volumetric_factor_oil").val() === "")) ? true: emptyValues;
        } else if ($("#fluid_type").val() == 'Gas') {
            // Viscosity Gas
            validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#viscosity_gas").val(), fluid_information_tab_ruleset[3]);
            titleTab = validationFunctionResult[0];
            validationMessages = validationFunctionResult[1];
            emptyValues = (emptyValues === false && ($("#viscosity_gas").val() === null || $("#viscosity_gas").val() === "")) ? true: emptyValues;

            // Volumetric Factor Gas
            validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#volumetric_factor_gas").val(), fluid_information_tab_ruleset[4]);
            titleTab = validationFunctionResult[0];
            validationMessages = validationFunctionResult[1];
            emptyValues = (emptyValues === false && ($("#volumetric_factor_gas").val() === null || $("#volumetric_factor_gas").val() === "")) ? true: emptyValues;
        }
        
        // Validating Production Data
        titleTab = "";
        tabTitle = "Tab: Production Data";

        // Well Radius
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#well_radius").val(), production_data_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#well_radius").val() === null || $("#well_radius").val() === "")) ? true: emptyValues;

        // Drainage Radius
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#drainage_radius").val(), production_data_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#drainage_radius").val() === null || $("#drainage_radius").val() === "")) ? true: emptyValues;

        // Reservoir Pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#reservoir_pressure").val(), production_data_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#reservoir_pressure").val() === null || $("#reservoir_pressure").val() === "")) ? true: emptyValues;

        if ($("#fluid_type").val() == 'Oil') {
            // Oil Rate
            validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#fluid_rate_oil").val(), production_data_tab_ruleset[3]);
            titleTab = validationFunctionResult[0];
            validationMessages = validationFunctionResult[1];
            emptyValues = (emptyValues === false && ($("#fluid_rate_oil").val() === null || $("#fluid_rate_oil").val() === "")) ? true: emptyValues;
        } else if ($("#fluid_type").val() == 'Gas') {
            // Gas Rate
            validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#fluid_rate_gas").val(), production_data_tab_ruleset[4]);
            titleTab = validationFunctionResult[0];
            validationMessages = validationFunctionResult[1];
            emptyValues = (emptyValues === false && ($("#fluid_rate_gas").val() === null || $("#fluid_rate_gas").val() === "")) ? true: emptyValues;
        }

        // BHP
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#bhp").val(), production_data_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#bhp").val() === null || $("#bhp").val() === "")) ? true: emptyValues;
        
        // Validating Multiparametric Analysis
        titleTab = "";
        tabTitle = "Tab: Multiparametric Analysis";

        // Critical Radius Derived From Maximum Critical Velocity, Vc
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#critical_radius").val(), multiparametric_analysis_tab_ruleset[0]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#critical_radius").val() === null || $("#critical_radius").val() === "")) ? true: emptyValues;

        // Total Volume Of Water Based Fluids Pumped Into The Well
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#total_volumen").val(), multiparametric_analysis_tab_ruleset[1]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#total_volumen").val() === null || $("#total_volumen").val() === "")) ? true: emptyValues;

        // Saturation Pressure
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#saturation_presure").val(), multiparametric_analysis_tab_ruleset[2]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#saturation_presure").val() === null || $("#saturation_presure").val() === "")) ? true: emptyValues;

        // Mineral Scales
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#mineral_scale_cp").val(), multiparametric_analysis_tab_ruleset[3]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#mineral_scale_cp").val() === null || $("#mineral_scale_cp").val() === "")) ? true: emptyValues;

        // Organic Scales
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#organic_scale_cp").val(), multiparametric_analysis_tab_ruleset[4]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#organic_scale_cp").val() === null || $("#organic_scale_cp").val() === "")) ? true: emptyValues;

        // Geomechanical Damage - Drawdown
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#geomechanical_damage_cp").val(), multiparametric_analysis_tab_ruleset[5]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#geomechanical_damage_cp").val() === null || $("#geomechanical_damage_cp").val() === "")) ? true: emptyValues;

        // Mineral Scales
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#mineral_scale_kd").val(), multiparametric_analysis_tab_ruleset[6]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#mineral_scale_kd").val() === null || $("#mineral_scale_kd").val() === "")) ? true: emptyValues;

        // Organic Scales
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#organic_scale_kd").val(), multiparametric_analysis_tab_ruleset[7]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#organic_scale_kd").val() === null || $("#organic_scale_kd").val() === "")) ? true: emptyValues;

        // Geomechanical Damage
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#geomechanical_damage_kd").val(), multiparametric_analysis_tab_ruleset[8]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#geomechanical_damage_kd").val() === null || $("#geomechanical_damage_kd").val() === "")) ? true: emptyValues;

        // Fines Blockage
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#fines_blockage_kd").val(), multiparametric_analysis_tab_ruleset[9]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#fines_blockage_kd").val() === null || $("#fines_blockage_kd").val() === "")) ? true: emptyValues;

        // Relative Permeability
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#relative_permeability_kd").val(), multiparametric_analysis_tab_ruleset[10]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#relative_permeability_kd").val() === null || $("#relative_permeability_kd").val() === "")) ? true: emptyValues;

        // Induced Damage
        validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#induced_damage_kd").val(), multiparametric_analysis_tab_ruleset[11]);
        titleTab = validationFunctionResult[0];
        validationMessages = validationFunctionResult[1];
        emptyValues = (emptyValues === false && ($("#induced_damage_kd").val() === null || $("#induced_damage_kd").val() === "")) ? true: emptyValues;

        if (validationMessages.length < 1) {
            if (emptyValues) {
                validationMessages.push(true);
                showFrontendErrors(validationMessages);
            } else {
                $("#only_s").val("run");
                $("#multiparametricAnalyticalForm").submit();
            }
        } else {
            showFrontendErrors(validationMessages);
        }
    }

    /* saveForm
    * Submits the form when the confirmation button from the modal is clicked
    */
    function saveForm() {
        $("#only_s").val("save");
        $("#multiparametricAnalyticalForm").submit();
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
</script>