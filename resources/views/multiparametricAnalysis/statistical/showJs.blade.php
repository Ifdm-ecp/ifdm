<script type="text/javascript">
  $(document).ready(function(){
    graficar();
    tablaGraficar();
  });


    function graficar() {
        data = [];
        <?php echo json_encode($datos); ?>.forEach(element => {
            data.push({
                name: element[0],
                data: element[1],
                pointPlacement: 'on'
            });
        });
        categories = [];
        titles = ['Mineral Scales', 'Fine Blockage', 'Organic Scales', 'Relative Permeability', 'Induced Damage', 'Geomechanical Damage'];
        for (let i = 0; i < <?php echo json_encode($generalCheckboxes); ?>.length; i++) {
            if (<?php echo json_encode($generalCheckboxes); ?>[i] === 1) {
                categories.push(titles[i]);
            }  
        }
        console.log(categories );
        $('#container').highcharts({

            chart: {
                polar: true,
                type: 'line'
            },

            accessibility: {
                description: ''
            },

            title: {
                text: 'Damage ',
                x: -80
            },

            pane: {
                size: '80%'
            },

            xAxis: {
                categories: categories,
                tickmarkPlacement: 'on',
                lineWidth: 0
            },

            yAxis: {
                gridLineInterpolation: 'polygon',
                lineWidth: 0,
                min: 0
            },

            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
            },

            legend: {
                align: 'right',
                verticalAlign: 'middle',
                layout: 'vertical'
            },

            series: data,

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            align: 'center',
                            verticalAlign: 'bottom',
                            layout: 'horizontal'
                        },
                        pane: {
                            size: '70%'
                        }
                    }
                }]
            }
        });
    }

    


//  
</script>