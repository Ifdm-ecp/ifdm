<script type="text/javascript">
  $(document).ready(function(){
    graficar();
    tablaGraficar();
  });


    function graficar() {
        data = [];
        <?php echo json_encode($datos); ?>.forEach(element => {
            points = [];
            console.log(element[1]);
            points.push(element[1]);
            data.push({
                name: element[0],
                data: element[1],
                pointPlacement: 'on'
            });
        });
        categories = [];
        titles = <?php echo json_encode($tableHeader); ?>;
        titles.shift();
        titles = ['Mineral Scales', 'Fine Blockage', 'Organic Scales', 'Relative Permeability', 'Induced Damage', 'Geomechanical Damage'];
        for (let i = 0; i < <?php echo json_encode($generalCheckboxes); ?>.length; i++) {
            if (<?php echo json_encode($generalCheckboxes); ?>[i] === 1) {
                categories.push(titles[i]);
            }  
        }

        console.log('datos', <?php echo json_encode($datos); ?>, 'generalcheckboxes',  <?php echo json_encode($generalCheckboxes); ?>, 'table header', <?php echo json_encode($tableHeader); ?>, 'tabledata',  <?php echo json_encode($tableData); ?>);
        console.log('data', data, 'categories', categories);

        $('#container').highcharts({

            chart: {
                polar: true,
                type: 'line'
            },

            accessibility: {
                description: ''
            },

            title: {
                text: 'Damage Distribution'
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
                pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.2f} %</b><br/>'
            },

            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal'
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

    function tablaGraficar()
  {
    

    var tableHeader = '<tr class="active">';
    <?php echo json_encode($tableHeader); ?>.forEach(header => {
        tableHeader = tableHeader + '<th style="text-align:center; vertical-align:middle;">' + header + '</th>';
    });
    tableHeader = tableHeader + '</tr>';
    $("#statistical_header").append(tableHeader);


    var tableBody = '';
    <?php echo json_encode($tableData); ?>.forEach(element => {
        tableBody = tableBody + '<tr>';
        var titleFlag = 0;
        element.forEach(formationData => {
            if (titleFlag === 0) {
                tableBody = tableBody + '<th style="text-align:center; vertical-align:middle;">' + formationData + '</th>';
            } else {
                tableBody = tableBody + '<th class="info" style="text-align:center; vertical-align:middle; font-weight:normal;">' + parseFloat(formationData).toFixed(2) + ' %</th>';
            }
            titleFlag = 1;
        });
        tableBody = tableBody + '</tr>';
    });
    $("#statistical_body").append (tableBody);
    
  }


//  
</script>