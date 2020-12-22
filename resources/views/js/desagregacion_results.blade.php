<script>
    function enviar()
    {
        document.getElementById('loading').style.display = 'block';      
        var form = $(this).parents('form:first');
        form.submit();
        return false;
    }
    
    function plot_spider_chart(data, chart_js, labels, title) 
    {
        $('#' + chart_js).highcharts({
            chart: {
                polar: true,
                type: 'line'
            },
            title: {
                text: title,
                x: -80
            },
            pane: {
                size: '80%'
            },
            xAxis: {
                categories: labels,
                tickmarkPlacement: 'on',
                lineWidth: 0
            },
            yAxis: {
                gridLineInterpolation: 'polygon',
                lineWidth: 0,
                min: 0,
                maxTickInterval: 5
            },
            tooltip: {
                shared: true,
                pointFormat: '<b>{point.y:,.0f}</b><br/>'
            },
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                y: 70,
                layout: 'vertical'
            },
            series: [{
                name: title,
                data: data,
                pointPlacement: 'on'
            }]
        });
    }
    
    function draw_results_table(data)
    {
        document.getElementById("tabla"/* + numero */+ "11").innerHTML = data[0].toFixed(4);
        document.getElementById("tabla"/* + numero */+ "21").innerHTML = data[1].toFixed(4);
        document.getElementById("tabla"/* + numero */+ "31").innerHTML = data[2].toFixed(4);
        document.getElementById("tabla"/* + numero */+ "41").innerHTML = data[3].toFixed(4);
        document.getElementById("tabla"/* + numero */+ "51").innerHTML = data[4].toFixed(4);
        //document.getElementById("tabla"/* + numero */+ "51").innerHTML = Math.round(data[4]*100)/100;

        console.log(data);
    
        if (data[0] != 0) 
        {
            var porcentaje0 = Math.round(((data[0]/data[0]) * 100)*100)/100 + "%";
            var porcentaje1 = Math.round(((data[1]/data[0]) * 100)*100)/100 + "%";
            var porcentaje2 = Math.round(((data[2]/data[0]) * 100)*100)/100 + "%";
            var porcentaje3 = Math.round(((data[3]/data[0]) * 100)*100)/100 + "%";
            var porcentaje4 = Math.round(((data[4]/data[0]) * 100)*100)/100 + "%";
    
            document.getElementById("tabla"/* + numero */+ "12").innerHTML = porcentaje0;
            document.getElementById("tabla"/* + numero */+ "32").innerHTML = porcentaje2;
            document.getElementById("tabla"/* + numero */+ "22").innerHTML = porcentaje1;
            document.getElementById("tabla"/* + numero */+ "42").innerHTML = porcentaje3;
            document.getElementById("tabla"/* + numero */+ "52").innerHTML = porcentaje4;
        }
    }
    
    function plot_line_chart(div, eje_x, eje_y, title, zoomed)
    {
        var data = new Array(eje_x.length);
        for (var i = 0; i < data.length; i++) 
        {
            data[i] = [eje_x[i], eje_y[i]];
        }
        
        if(zoomed)
        {
        max_eje_x = 10;
        //title += " (Zoomed "+Math.min.apply(Math, eje_x)+"< radius < 10)";
        }
        else
        {
        max_eje_x = 10;
        //max_eje_x = Math.max.apply(Math, eje_x);
        }
        Highcharts.chart(div, {
    
        chart: {
            type: 'line',
            zoomType: 'x',
            inverted: false
        },
            title: {
                text: title,
                x: -20 //center
            },
            xAxis: {
            //min: Math.min.apply(Math, eje_x),
            max: max_eje_x,
            title: {
            text: 'r(i)'
            }
            },
            yAxis: {
            //min: Math.min.apply(Math, eje_y),
            //max: Math.max.apply(Math, eje_y),
                title: {
                    text: 'Skin by Effective Stress'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: 'k'
            },
            legend: {
                layout: 'vertical',
                align: 'center',
                verticalAlign: 'bottom',
                borderWidth: 0
            },
            series: [{
                name: title,
                data: data
            }]
    
        });
    }
    
    plot_spider_chart( {!! $results !!}, "grafica_desagregacion", ["Total Skin", "Mechanical Skin", "Stress-dependent Skin", "Pseudo Skin", "Rate-dependent Skin"], 'Skin By Components');
    draw_results_table({!! $results !!});
    plot_line_chart("grafica_pres_perm",{!! $ri !!} ,{!! $skin_by_stress !!} , "r(i) vs. Skin by Effective Stress", true);
</script>