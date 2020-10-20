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
		if(fluido == 'Oil')
		{
			$("#div_oil *, #input_oil *").attr('disabled', false).show();
			$("#div_gas *, #input_gas *").attr('disabled', true).hide();
		}else if(fluido == 'Gas')
		{
			$("#div_oil *, #input_oil *").attr('disabled', true).hide();
			$("#div_gas *, #input_gas *").attr('disabled', false).show();
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
        var oil_viscosity = parseFloat($("input[name = viscosity]").val());//viscosity
        var oil_vol_factor = parseFloat($("input[name = volumetric_factor]").val());//Volumetric Factor
        var oil_rate = parseFloat($("input[name = fluid_rate]").val());//fluid_rate
        var gas_rate = parseFloat($("input[name = fluid_rate]").val());//fluid_rate
        var gas_viscosity = parseFloat($("input[name = viscosity]").val());//viscosity
        var gas_vol_factor = parseFloat($("input[name = volumetric_factor]").val());//Volumetric Factor
        var well_radius = parseFloat($("input[name = well_radius]").val());//Well Radius
        var radius = parseFloat($("input[name = well_radius]").val());//el mismo Well Radius
        var external_radius = parseFloat($("input[name = drainage_radius]").val());//Drainage Radius
        var porosity = parseFloat($("input[name = porosity]").val());//porosity
        var bhp = parseFloat($("input[name = bhp]").val());//nhp
        var type_of_well = $("#fluid_type").val();//Fluid Type
		//alert('cp_msp: '+cp_msp+' - '+'cp_osp: '+cp_osp+' - '+'cp_krp: '+cp_krp+' - '+'cp_mcv: '+cp_mcv+' - '+'tv_wbf: '+tv_wbf+' - '+'netpay: '+netpay+' - '+'condensate_perm: '+condensate_perm+' - '+'oil_viscosity: '+oil_viscosity+' - '+'oil_vol_factor: '+oil_vol_factor+' - '+'oil_rate: '+oil_rate+' - '+'gas_rate: '+gas_rate+' - '+'gas_viscosity: '+gas_viscosity+' - '+'gas_vol_factor: '+gas_vol_factor+' - '+'well_radius: '+well_radius+' - '+'radius: '+radius+' - '+'external_radius: '+external_radius+' - '+'porosity: '+porosity+' - '+'bhp: '+bhp+' - '+'type_of_well: '+type_of_well);        

        console.log("Entré");
        //se crean los arrays en 0
        var pressures_total = [];
        var radius_total = [];
        var pressure_radius_final = [];
        var pressures_data = [];
        var radius_data = [];


        var pr = bhp;
        var fbp_radius = cp_mcv + radius;
        var idp_radius = Math.sqrt(((tv_wbf * 5.615) / (Math.PI * netpay * porosity)) + Math.pow(well_radius, 2));

        if (type_of_well == "Oil") {
            while (radius < external_radius) {
                
                pr = bhp + (((141.2 * oil_rate * oil_viscosity * oil_vol_factor) / (netpay * condensate_perm)) * (Math.log(radius / well_radius)-0.75));
                pressures_data.push(pr);
                radius_data.push(radius);
                radius = radius + 0.5;
            }

            // var Pr_Rmax = bhp + (((141.2 * oil_rate * oil_viscosity * oil_vol_factor) / (netpay * condensate_perm)) * Math.log(external_radius / well_radius)) - (0.5 * (Math.pow((external_radius / external_radius), 2)));
            // var Pr_Rmin = bhp + (((141.2 * oil_rate * oil_viscosity * oil_vol_factor) / (netpay * condensate_perm)) * Math.log(well_radius / well_radius)) - (0.5 * (Math.pow((well_radius / external_radius), 2)));
        } else {
            while (radius < external_radius) {

                pr = bhp + (((141.2 * gas_rate * (1000000) * gas_viscosity * gas_vol_factor) / (5.615 * netpay * condensate_perm)) * (Math.log(radius / well_radius)-0.75));
                pressures_data.push(pr);
                radius_data.push(radius);
                radius = radius + 0.5;
            }

            // var Pr_Rmax = bhp + (((141.2 * gas_rate * (1000000) * gas_viscosity * gas_vol_factor) / (5.615 * netpay * condensate_perm)) * Math.log(external_radius / well_radius)) - (0.5 * (Math.pow((external_radius / external_radius), 2)));
            // var Pr_Rmin = bhp + (((141.2 * gas_rate * (1000000) * gas_viscosity * gas_vol_factor) / (5.615 * netpay * condensate_perm)) * Math.log(well_radius / well_radius)) - (0.5 * (Math.pow((well_radius / external_radius), 2)));
        }

        // radius = parseFloat($("input[name = well_radius]").val());
        // var MaxDrawDown_fraction = (Pr_Rmax - Pr_Rmin) / (Pr_Rmax - Pr_Rmin);
        // var CummDrawDown = 1.0 - MaxDrawDown_fraction;

        // while (CummDrawDown < 0.25 && radius < external_radius) {
        //     radius = radius + 0.01;
        //     if (type_of_well == "Oil") {
        //         var pr_gdp = bhp + (((141.2 * oil_rate * oil_viscosity * oil_vol_factor) / (netpay * condensate_perm)) * Math.log(radius / well_radius)) - (0.5 * (Math.pow((radius / external_radius), 2)));
        //     } else {
        //         var pr_gdp = bhp + (((141.2 * gas_rate * (1000000) * gas_viscosity * gas_vol_factor) / (5.615 * netpay * condensate_perm)) * Math.log(radius / well_radius)) - (0.5 * (Math.pow((radius / external_radius), 2)));
        //     }

        //     MaxDrawDown_fraction = (Pr_Rmax - pr_gdp) / (Pr_Rmax - Pr_Rmin);
        //     CummDrawDown = 1.0 - MaxDrawDown_fraction;
        // }

        // if (radius >= external_radius) {
        //     radius = external_radius - WellRadius;
        // }

        // pr_gdp_final = pr_gdp;
        // radius_gdp_final = radius;
        // console.log(pr_gdp_final);
        // console.log(radius_gdp_final);

        // var cp_gdp = parseFloat(pressures_data[pressures_data.length - 1]);
        var critical_pressures = [cp_msp, cp_osp, cp_krp, bhp + cp_gdp];

        //Interpolación lineal presiones críticas
        for (var i = 0; i < critical_pressures.length; i++) {
            // pressure_aux = critical_pressures[i];
            // for (var j = 0; j < pressures_data.length; j++) {
            //     if (pressure_aux > pressures_data[j]) {
            //         continue;
            //     } else {
            //         pressure_y1 = pressures_data[j - 1];
            //         radius_x1 = radius_data[j - 1];
            //         break;
            //     }
            // }
            // for (var k = j + 1; k < pressures_data.length; i++) {
            //     if (pressure_aux > pressures_data[k]) {
            //         continue;
            //     } else {
            //         pressure_y2 = pressures_data[k - 1];
            //         radius_x2 = radius_data[k - 1];
            //         break;
            //     }
            // }

            // if (pressure_aux < pressures_data[0]) {
            //     pressure_y1 = pressures_data[0];
            // }
            
            if (critical_pressures[i] >= pressures_data[pressures_data.length-1]){
                pressure_radius = [external_radius, critical_pressures[i]];
                pressure_radius_final.push(pressure_radius);
            } else if(critical_pressures[i] <= bhp ){
                pressure_radius = [well_radius, critical_pressures[i]];
                pressure_radius_final.push(pressure_radius);
            } else {
                for (var j = 0; j < pressures_data.length-1; j++) {
                    if (critical_pressures[i] >= pressures_data[j] && critical_pressures[i] < pressures_data[j+1]){
                        interpolated_radius = ((radius_data[j+1]-radius_data[j])/(pressures_data[j+1]-pressures_data[j])) * (critical_pressures[i]-pressures_data[j]) + radius_data[j];
                        pressure_radius = [interpolated_radius, critical_pressures[i]];
                        pressure_radius_final.push(pressure_radius);
                        break;
                    }
                }
            }

            // interpolated_radius = (((pressure_aux - pressure_y1) / (pressure_y2 - pressure_y1)) * (radius_x2 - radius_x1)) + radius_x1;

            // pressure_radius = [interpolated_radius, critical_pressures[i]];
            // pressure_radius_final.push(pressure_radius);
        }

        // pressure_radius_final.push([radius_gdp_final, pr_gdp_final]);

        var radius_final = [pressure_radius_final[0][0], pressure_radius_final[1][0], pressure_radius_final[2][0], pressure_radius_final[3][0], fbp_radius, idp_radius];

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
                categories: ['Mineral Scales', 'Organic Scales', 'Relative Permeability', 'Geomechanical Damage', 'Fine Blockage', 'Induced Damage']
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
    
</script>