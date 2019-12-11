<script src="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.js"></script>
<link type="text/css" rel="stylesheet" href="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.min.css">
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">

    $("#date_select").change(function()
    {
        selected_dates = $("#date_select").val();
        var fines_d_diagnosis_id = {!!json_encode($fines_d_diagnosis->id)!!};
        on_change_date_select_plot(fines_d_diagnosis_id, selected_dates);
    });

    var fines_d_diagnosis_id = {!!json_encode($fines_d_diagnosis->id)!!};

    //Trayendo datos para gráficos de pestaña resultados asfaltenos depositados
    $.get("{{url('fines_d_diagnosis_results_skin')}}",
        {fines_d_diagnosis_id : fines_d_diagnosis_id},
        function(data)
        {   
            console.log(data);
            damage_radius_serie = [];
            skin_serie = [];
            $.each(data, function(index, value)
            {
                date_value = value.date.split("-");
                date_value = Date.UTC(date_value[0],date_value[1],date_value[2]);
                damage_radius_serie.push([date_value, value.damage_radius]);
                skin_serie.push([date_value, value.skin]);
            });

            damage_radius_serie_data = [{"name": "Damage Radius [ft]", "data":damage_radius_serie}];
            skin_serie_data = [{"name": "Skin", "data":skin_serie}];
            console.log('radio de daño');
            console.log(damage_radius_serie_data);
            console.log('skin');
            console.log(skin_serie_data);
            plot_damage_radius_results("damage_radius_chart", damage_radius_serie_data, "Damage Radius", "Damage Radius [ft]");
            plot_damage_radius_results("skin_chart", skin_serie_data, "Skin", "Skin");
        });

    //Función para graficar los datos de la pestaña de resultados finos. Consulata los datos con base en las fechas y grafica porosidad, permeabilidad y concentración de finos.
    function on_change_date_select_plot(fines_d_diagnosis_id, selected_dates)
    {
        //Trayendo datos para gráficos de pestaña resultados finos
        $.get("{{url('fines_d_diagnosis_results')}}",
            {fines_d_diagnosis_id : fines_d_diagnosis_id,
                dates:selected_dates},
            function(data)
            {   
                pressure_radius = [];
                porosity_radius = [];
                permeability_radius = [];
                co_radius = [];

                $.each(data[0], function(index, date)
                {
                    porosity_radius_row = [];
                    permeability_radius_row = [];
                    co_radius_row = [];
                    date_aux = date[0].date;
                    $.each(date, function(index, value)
                    {
                        porosity_radius_row.push([value.radius, value.porosity]);
                        permeability_radius_row.push([value.radius, value.permeability]);
                        co_radius_row.push([value.radius, value.co]);
                    });

                    porosity_radius.push({"name":"Porosity on date: "+date_aux, "data":porosity_radius_row});
                    permeability_radius.push({"name":"Permeability on date: "+date_aux, "data":permeability_radius_row});
                    co_radius.push({"name":"Fines Concentration on date: "+date_aux, "data":co_radius_row});
                });

                console.log('porosidad');
                console.log(porosity_radius);
                console.log('permeability');
                console.log(permeability_radius);
                console.log('co');
                console.log(co_radius);
                plot_results("porosity_chart", porosity_radius, "Porosity", "Radius [ft]", "Porosity [Fraction]", data[1]);
                plot_results("permeability_chart", permeability_radius, "Permeability", "Radius [ft]", "Permeability [mD]", data[1]);
                plot_results("co_chart", co_radius, "Fines Concentration", "Radius [ft]", "Fines Concentration [% Weight]", data[1]);
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

    //Barra de informacion de escenario
    function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('#sticky-anchor').offset().top;
        if (window_top > div_top) {
            $('#sticky').addClass('stick');
        } else {
            $('#sticky').removeClass('stick');
        }
    }
    $(function() {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });

    //Acciones iniciales
    $(document).ready(function(){

        //Mostrando los resultados de la última fecha por defecto. El usuario puede modificar qué fechas quiere desde la vista.
        fines_d_diagnosis_id = {!!json_encode($fines_d_diagnosis->id)!!};
        final_date = {!!json_encode($dates_data)!!}.slice(-1);
        on_change_date_select_plot(fines_d_diagnosis_id, final_date);

    });
</script>