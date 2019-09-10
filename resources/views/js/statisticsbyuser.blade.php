<script type="text/javascript">
    //Mostrar grafico con estadisticas de sesion de usuario
    $(document).ready(function() {

        var statistics = <?php echo $sessionsbyuser; ?>;


        var date = [];
        var count_user = [];
        var count_user_bycompany = [];
        var user_name;
        for (var i = 0; i < statistics.length; i++) {
            var date = [];
            var datea = statistics[i]['date'];
            utc = Date.UTC(datea.split('-')[0], datea.split('-')[1] - 1, datea.split('-')[2]);

            date.push(utc, Math.round(statistics[i]['time'] / 3600));
            count_user.push(date);
            user_name = statistics[i]['user_name'];
        }
        $('#statistics').highcharts({
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
                text: user_name + ' Online Time',
                x: -20 //center
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                min: 0,
                tickInterval: 1,
                title: {
                    text: 'Hours'
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
                name: 'Hours',
                dashStyle: "shortdot",
                data: count_user

            }]
        });


    });
</script>