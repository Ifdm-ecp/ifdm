<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<!-- Libreria de graficación -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">   
$(document).ready(function()
{
    //Trayendo datos para gráfico de saturation data desde bd
    var reservoir_temperature = {!! !empty($asphaltenes_d_precipitated_analysis->reservoir_temperature) ? $asphaltenes_d_precipitated_analysis->reservoir_temperature : null !!};
    var current_reservoir_pressure = {!! !empty($asphaltenes_d_precipitated_analysis->current_reservoir_pressure) ? $asphaltenes_d_precipitated_analysis->current_reservoir_pressure : null !!};
    var initial_reservoir_pressure = {!! !empty($asphaltenes_d_precipitated_analysis->initial_reservoir_pressure) ? $asphaltenes_d_precipitated_analysis->initial_reservoir_pressure : null !!};
    var current_reservoir_conditions = [];
    if (reservoir_temperature !== null && current_reservoir_pressure !== null) {
        current_reservoir_conditions.push([reservoir_temperature, current_reservoir_pressure]);
    }
    var initial_reservoir_conditions = [];
    if (reservoir_temperature !== null && initial_reservoir_pressure !== null) {
        initial_reservoir_conditions.push([reservoir_temperature, initial_reservoir_pressure]);
    }
    var experimental_pressure_serie = [];

    var asphaltenes_d_precipitated_analysis_id = <?php 
        if($asphaltenes_d_precipitated_analysis_id){
            echo json_encode($asphaltenes_d_precipitated_analysis_id);
        }else{
            echo json_encode(null);
        }
    ?>;
    
    $.get("{{url('asphaltenes_d_precipitated_analysis_saturation_results')}}",
            {asphaltenes_d_precipitated_analysis_id : asphaltenes_d_precipitated_analysis_id},
            function(data)
            {
                saturation_data_serie = [];
                saturation_results_serie = [];
                $.each(data.saturation_data, function(index, value)
                    {
                        saturation_data_serie.push([value.temperature, value.bubble_pressure]);
                    });
                $.each(data.saturation_results, function(index, value)
                    {
                        saturation_results_serie.push([value.temperature, value.bubble_pressure]);
                    });
                plot_saturation_results(saturation_data_serie, saturation_results_serie, "saturation_results_chart");
            }
        );

    //Datos de solid A para resultados
    $.get("{{url('asphaltenes_d_precipitated_analysis_solid_a_results')}}",
            {asphaltenes_d_precipitated_analysis_id : asphaltenes_d_precipitated_analysis_id},
            function(data)
            {
                solid_a_series = [];
                $.each(data, function(index, temperature_group)
                {
                    console.log(temperature_group);
                    serie_name = (index - 460) + " [°F]";
                    serie_data = [];
                    $.each(temperature_group, function(index, value)
                    {
                        serie_data.push([value.pressure, value.a]);
                    });
                    solid_a_series.push({"name":serie_name, "data":serie_data});
                });
                plot_results("solid_a_results_chart", solid_a_series, "Asphaltene Soluble Fraction", "Pressure [psi]", "Soluble Fraction [0 - 1]");
            }
        );

    // Traer datos experimentales
    $.get("{{url('get_experimental_onset_pressures_data')}}",
        {asphaltenes_d_precipitated_analysis_id : asphaltenes_d_precipitated_analysis_id},
        function(data)
        {
            $.each(data, function(index, value)
            {
                experimental_pressure_serie.push([value.temperature, value.onset_pressure]);
            });
        }
    );

    //Datos para gráficos onset -onset pressure y asfaltenos solubles-
    $.get("{{url('asphaltenes_d_precipitated_analysis_onset_results')}}",
            {asphaltenes_d_precipitated_analysis_id : asphaltenes_d_precipitated_analysis_id},
            function(data)
            {
                onset_a_serie = [];
                onset_pressure_serie = [];
                bubble_pressure_serie = [];
                corrected_onset_pressure_serie = [];
                $.each(data, function(index, value)
                {
                    onset_a_serie.push([value.temperature, value.a]);
                    if (value.temperature > 0) {
                        onset_pressure_serie.push([value.temperature, value.onset_pressure]);
                        corrected_onset_pressure_serie.push([value.temperature, value.corrected_onset_pressure]);
                    }
                    bubble_pressure_serie.push([value.temperature, value.bubble_pressure]);
                    // if (index < experimental_pressure_serie.length) {
                    //     experimental_pressure_serie[index];
                    // }
                    // console.log(index, experimental_pressure_serie);
                });
                onset_series_data = [{"name":"Onset Pressure [psi]","data":onset_pressure_serie}, {"name":"Corrected Onset Pressure [psi]","data":corrected_onset_pressure_serie},{"name":"Bubble Pressure [psi]","data":bubble_pressure_serie}, {"name" : "Current Reservoir Conditions", "data" : current_reservoir_conditions}, {"name" : "Initial Reservoir Conditions", "data" : initial_reservoir_conditions}, {"name" : "Experimental Onset Pressures", "data" : experimental_pressure_serie}];
                asphaltenes_soluble_fraction_series_data = [{"name":"A [psi]","data":onset_a_serie}];
                plot_results("onset_pressure_chart", onset_series_data, "Asphaltene Onset Pressure", "Temperature [F]", "Pressure [psi]");
                plot_results("asphaltenes_soluble_fraction_chart", asphaltenes_soluble_fraction_series_data, "Asphaltenes Soluble Fraction", "Temperature [F]", "Soluble Asphaltenes [-]");
            }
        );
})

function plot_saturation_results(saturation_data, saturation_results, div)
{
    Highcharts.chart(div, {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Saturation Results'
        },

        yAxis: {
            title: {
                text: 'Bubble Pressure [psi]'
            }
        },
        xAxis: {
            title: {
                text: 'Temperature [F]'
            }
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },

        series: [{
            name: 'Base data',
            data: saturation_data,
            marker:
            {
                enabled: true
            }
        },
        {
          name: 'Calculated data',
          data: saturation_results
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
}
function plot_results(div, series_data, tittle, x_axis_tittle, y_axis_tittle)
{
    Highcharts.chart(div, {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: tittle
        },

        yAxis: {
            title: {
                text: y_axis_tittle
            }
        },
        xAxis: {
            min: 0,
            title: {
                text: x_axis_tittle
            }
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },

        series: series_data,

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
}
</script>