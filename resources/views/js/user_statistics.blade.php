<script type="text/javascript">
//Creacion de grafico para mostrar estadisticas de sesion de usuarios por compañia
    $(document).ready(function() {

        var statistics = <?php echo $sessions; ?>;


        var date = [];
        var count_user = [];
        var count_user_bycompany = [];

        var sessions_company = <?php echo json_encode($sessionsbycompany ); ?>;

        for (var i = 0; i < statistics.length; i++) {
            var date = [];
            var datea = statistics[i]['date'];
            utc = Date.UTC(datea.split('-')[0], datea.split('-')[1] - 1, datea.split('-')[2]);

            date.push(utc, statistics[i]['count_users']);
            count_user.push(date);
        }

        var options = {
            plotOptions: {
                line: {
                    connectNulls: true
                }
            },
            chart: {
                zoomType: 'x',
                renderTo: 'container'
            },
            title: {
                text: 'Active Users Per Day',
                x: -20 //center
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                min: 0,
                tickInterval: 1,
                title: {
                    text: 'Users'
                },
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'All',
                type: 'area',
                data: count_user

            }]
        }



        chart = $('#statistics').highcharts(options, function(ch) {


            for (var i = 0; i < sessions_company.length; i++) {
                var aux = sessions_company[i][0];
                var count_user_bycompany = [];
                for (var j = 0; j < aux.length; j++) {
                    var date = [];
                    var datea = aux[j]['date'];
                    utc = Date.UTC(datea.split('-')[0], datea.split('-')[1] - 1, datea.split('-')[2]);
                    date.push(utc, aux[j]['count_users']);
                    count_user_bycompany.push(date);
                }
                ch.addSeries({
                    name: sessions_company[i][1],
                    data: count_user_bycompany,
                    dashStyle: "shortdot"
                }, true);
            }



            chart.redraw();
        });

    });
</script>