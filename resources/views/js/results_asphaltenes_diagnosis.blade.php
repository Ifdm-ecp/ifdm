<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<!-- Libreria de graficación -->
{{-- <script src="{{ asset('js/highcharts.js') }}"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script> --}}
<script type="text/javascript">   
$("#date_select").change(function() {
    selected_dates = $("#date_select").val();
    var asphaltenes_d_diagnosis_id = {!!json_encode($asphaltenes_d_diagnosis->id)!!};
    on_change_date_select_plot(asphaltenes_d_diagnosis_id, selected_dates);

});

var asphaltenes_d_diagnosis_id = {!!json_encode($asphaltenes_d_diagnosis->id)!!};

//Trayendo datos para gráficos de pestaña resultados asfaltenos depositados
$.get("{{url('asphaltenes_d_diagnosis_results_skin')}}",
    {asphaltenes_d_diagnosis_id : asphaltenes_d_diagnosis_id},
    function(data)
    {   
        damage_radius_serie = [];
        skin_serie = [];
        $.each(data, function(index, value)
        {
            date_value = value.date.split("-");
            date_value = Date.UTC(date_value[0],date_value[1],date_value[2]);
            damage_radius_serie.push([date_value, value.damage_radius]);
            skin_serie.push([date_value, value.skin]);
        });

        damage_radius_serie.shift();
        skin_serie.shift();

        damage_radius_serie_data = [{"name": "Damage Radius [ft]", "data":damage_radius_serie}];
        skin_serie_data = [{"name": "Skin", "data":skin_serie}];
        console.log('damage_radius_serie_data');
        console.log(damage_radius_serie_data);
        console.log('skin_serie_data');
        console.log(skin_serie_data);
        plot_damage_radius_results("damage_radius_chart", damage_radius_serie_data, "Damage Radius", "Damage Radius [ft]");
        plot_damage_radius_results("skin_chart", skin_serie_data, "Skin", "Skin");
    });

//Función para graficar los datos de la pestaña de resultados asfaltenos. Consulata los datos con base en las fechas y grafica porosidad, permeabilidad, asfaltenos depositados y asfaltenos solubles.
function on_change_date_select_plot(asphaltenes_d_diagnosis_id,selected_dates)
{
    //Trayendo datos para gráficos de pestaña resultados asfaltenos depositados
    $.get("{{url('asphaltenes_d_diagnosis_results')}}",
        {asphaltenes_d_diagnosis_id : asphaltenes_d_diagnosis_id,
            dates:selected_dates},
        function(data)
        {   
            porosity_radius = [];
            permeability_radius = [];
            deposited_asphaltenes_radius = [];
            soluble_asphaltenes_radius = [];

            $.each(data[0], function(index, date)
            {
                porosity_radius_row = [];
                permeability_radius_row = [];
                deposited_asphaltenes_radius_row = [];
                soluble_asphaltenes_radius_row = [];

                contador = 0;

                date_aux = date[0].date;
                console.log('esta es la fecha');
                console.log(date_aux);
                $.each(date, function(index, value)
                {
                    porosity_radius_row.push([value.radius, value.porosity]);
                    permeability_radius_row.push([value.radius, value.permeability]);
                    deposited_asphaltenes_radius_row.push([value.radius, value.deposited_asphaltenes]);
                    soluble_asphaltenes_radius_row.push([value.radius, value.soluble_asphaltenes]);
                });

                porosity_radius.reverse();
                permeability_radius.reverse();

                porosity_radius.push({"name":"Porosity on date: "+date_aux, "data":porosity_radius_row});
                permeability_radius.push({"name":"Permeability on date: "+date_aux, "data":permeability_radius_row});
                deposited_asphaltenes_radius.push({"name":"Deposited Asphaltenes on date: "+date_aux, "data":deposited_asphaltenes_radius_row});
                soluble_asphaltenes_radius.push({"name":"Soluble Asphaltenes on date: "+date_aux, "data":soluble_asphaltenes_radius_row});

                contador =+ 1;
            });

            console.log('porosity_radius');
            console.log(porosity_radius);
            console.log('permeability_radius');
            console.log(permeability_radius);
            console.log('deposited_asphaltenes_radius');
            console.log(deposited_asphaltenes_radius);
            console.log('soluble_asphaltenes_radius');
            console.log(soluble_asphaltenes_radius);

            plot_results("porosity_chart", porosity_radius, "Porosity", "Radius [ft]", "Porosity [-]", 50);
            plot_results("permeability_chart", permeability_radius, "Permeability", "Radius [ft]", "Permeability [mD]", 50);
            plot_results("deposited_asphaltenes_chart", deposited_asphaltenes_radius, "Deposited Asphaltenes", "Radius [ft]", "Deposited Asphaltenes [%wt]", 500);
            plot_results("soluble_asphaltenes_chart", soluble_asphaltenes_radius, "Soluble Asphaltenes", "Radius [ft]", "Soluble Asphaltenes [ppm]", 500);
        });
}
function plot_results(div, series_data, tittle, x_axis_tittle, y_axis_tittle, max_x_value)
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
            title: {
                text: x_axis_tittle
            },
            max: max_x_value
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

function plot_damage_radius_results(div, series_data, tittle, y_axis_tittle)
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
            type: 'datetime',
            dateFormat: 'dd/mm/YYYY',
            title: 
            {
                text: 'Date'
            }
        },
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                }
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

//Acciones iniciales
$(document).ready(function(){

    //Mostrando los resultados de la última fecha por defecto. El usuario puede modificar qué fechas quiere desde la vista.
    fines_d_diagnosis_id = {!!json_encode($asphaltenes_d_diagnosis->id)!!};
    final_date = {!!json_encode($dates_data)!!}.slice(-1);
    on_change_date_select_plot(fines_d_diagnosis_id, final_date);

});
</script>