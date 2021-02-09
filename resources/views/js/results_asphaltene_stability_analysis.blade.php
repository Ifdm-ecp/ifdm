<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<!-- Libreria de graficación -->
{{-- <script src="{{ asset('js/highcharts.js') }}"></script> --}}
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/annotations.js"></script>
<script type="text/javascript">   

    $(document).ready(function() {
        var asphaltenes_d_stability_analysis_results_id = <?php 
        if($asphaltenes_d_stability_analysis_results){
            echo json_encode($asphaltenes_d_stability_analysis_results->id);
        }else{
            echo json_encode(null);
        }
        ?>;

        $.get("{!! url('asphaltenes_d_stability_analysis_results') !!}", {
            asphaltenes_d_stability_analysis_results_id: asphaltenes_d_stability_analysis_results_id
        }, function (data) {
            value = data.asphaltenes_d_stability_analysis_results;

            light_analysis_problem_level = text_div(value.light_analysis_problem_level, "light_analysis_problem_level");
            light_analysis_conclusion = text_div(value.light_analysis_conclusion, "light_analysis_conclusion");
            light_analysis_probability = text_div(value.light_analysis_probability, "light_analysis_probability");

            sara_analysis_problem_level = text_div(value.sara_analysis_problem_level, "sara_analysis_problem_level");
            sara_analysis_conclusion = text_div(value.sara_analysis_conclusion, "sara_analysis_conclusion");
            sara_analysis_probability = text_div(value.sara_analysis_probability, "sara_analysis_probability");

            colloidal_analysis_problem_level = text_div(value.colloidal_analysis_problem_level, "colloidal_analysis_problem_level");
            colloidal_analysis_conclusion = text_div(value.colloidal_analysis_conclusion, "colloidal_analysis_conclusion");
            colloidal_analysis_probability = text_div(value.colloidal_analysis_probability, "colloidal_analysis_probability");

            precipitation_risk_light = color_panel(value.precipitation_risk_light, "precipitation_risk_light");
            precipitation_risk_sara = color_panel(value.precipitation_risk_sara, "precipitation_risk_sara");
            precipitation_risk_colloidal = color_panel(value.precipitation_risk_colloidal, "precipitation_risk_colloidal");
            precipitation_risk_fluid = text_div(value.precipitation_risk_fluid, "precipitation_risk_fluid");

            boer_stability_analysis_chart_point = value.boer_stability_analysis_chart_point;
            colloidal_analysis_chart_point = value.colloidal_analysis_chart_point;
            stankiewicz_analysis_chart_point = value.stankiewicz_analysis_chart_point;

            pozo = data.pozo.well_name;

            f_boer_chart(boer_stability_analysis_chart_point, pozo);
            f_cii_chart(colloidal_analysis_chart_point, pozo);
            f_stankiewicz_chart(stankiewicz_analysis_chart_point, pozo);

        });
    });


    function text_div(text, div_name){
        if(text != null){
            $('#'+div_name).html("<p>"+text+"</p>");
        }
    }

    function color_panel(text, div_name){
        if (text != null) {
            daño = text.split(" ");
            daño = daño[daño.length-1];

            if(daño == 0 || daño == 1 || daño == 2){
                $('#'+div_name).html('<div class="alert alert-success">'+text+'</div>');
            }else if(daño == 3 || daño == 4){
                $('#'+div_name).html('<div class="alert alert-warning">'+text+'</div>');
            }else if(daño == 5 || daño == 6 || daño == 7){
                $('#'+div_name).html('<div class="alert alert-danger">'+text+'</div>');
            }
        }
    }

    function f_boer_chart(point, field){
        /* Boer chart */
        point = JSON.parse(point);
        point_x = parseFloat(point[0]);
        point_y = parseFloat(point[1]);

        Highcharts.chart('boer_chart', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Boer Stability Analysis'
            },

            yAxis: {
                title: {
                    text: 'Reservoir Initial Pressure - Bubble Pressure [psia]'
                },
                max : 10000
            },
            xAxis: {
                title: {
                    text: 'In-situ Density [g/cc]'
                }
            },
            annotations: [{
                labels: [{
                    point: {
                        xAxis: 0,
                        yAxis: 0,
                        x: 0.58,
                        y: 7500
                    },
                    text: 'High Probability'
                }, {
                    point: {
                        xAxis: 0,
                        yAxis: 0,
                        x: 0.75,
                        y: 5000
                    },
                    text: 'Medium Probability'
                }, {
                    point: {
                        xAxis: 0,
                        yAxis: 0,
                        x: 0.95,
                        y: 4000
                    },
                    text: 'Low Probability'
                }]
            }],

            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                    pointStart: 2010
                }
            },

            series: [{
                name: 'B1',
                data: [[0.99773786,8043.748649],[0.991374815,7812.292912],[0.98853907,7598.207069],[0.983583965,7330.801448],[0.977923648,7063.496668],[0.973674997,6813.864306],[0.968015922,6564.433627],[0.963060817,6297.028005],[0.958813407,6065.269744],[0.952444154,5744.443504],[0.949613375,5601.854063],[0.944661995,5388.070744],[0.937588772,5085.219445],[0.934045332,4835.486242],[0.926975834,4586.257245],[0.922026937,4408.222127],[0.915661409,4141.018189],[0.909293398,3838.066048],[0.905758649,3713.45155],[0.900099574,3464.020871],[0.890911958,3179.346196],[0.883139731,2965.966241],[0.875367505,2752.586287],[0.866186096,2557.282115],[0.857706175,2308.254801],[0.849236186,2202.220291],[0.842176621,2095.984099],[0.832293726,1954.403071],[0.822409589,1794.947942],[0.816055235,1688.610909],[0.806174823,1582.778082],[0.797702351,1440.995371],[0.788529634,1370.809904],[0.774414227,1211.959822],[0.765944239,1105.925312],[0.756769038,999.9916441],[0.740541722,895.0663876],[0.72713401,771.8636658],[0.713731265,720.1573465],[0.698210401,633.0053495],[0.684808898,599.1731308],[0.665061731,584.1225834],[0.647426474,515.1472106],[0.63825624,480.7099448],[0.622031407,411.5328896],[0.608632386,413.4488722],[0.589587948,362.5492823],[0.572661628,347.0953702],[0.558554912,313.3639927],[0.540218169,298.111763],[0.52752436,299.9269044],[0.514124097,283.9687863],[0.504251135,285.380563]],
                color: '#1844e2',
                showInLegend: false
            }, {
                name: 'B2',
                data: [[0.939180844,10161.28679],[0.935617455,9965.179272],[0.933458504,9697.373817],[0.930592354,9429.669488],[0.927738655,9251.334768],[0.925577214,8965.655391],[0.923433205,8805.093469],[0.919155146,8555.465312],[0.916293976,8323.508827],[0.914842225,8055.602246],[0.910576617,7895.3437],[0.907003267,7627.740496],[0.904137117,7360.036166],[0.901995597,7217.348166],[0.899846607,7021.038399],[0.896987928,6806.955836],[0.892714849,6593.075523],[0.889853679,6361.119038],[0.884158731,6093.81921],[0.880577911,5772.594239],[0.874178254,5523.269459],[0.872029264,5326.959692],[0.867756186,5113.07938],[0.861359018,4881.628522],[0.858510299,4739.041647],[0.854951891,4578.681975],[0.851393482,4418.322304],[0.848544763,4275.735429],[0.842160046,4133.654181],[0.836472569,3919.976119],[0.832211941,3795.465417],[0.827953803,3688.828637],[0.825107574,3564.115684],[0.818712897,3350.538748],[0.8130304,  3172.608531],[0.804521594,3012.956737],[0.796717499,2835.329896],[0.788203713,2639.930259],[0.781814016,2462.101167],[0.77614148, 2355.666638],[0.769049564,2213.686515],[0.759121379,2018.489129],[0.755572931,1929.625145],[0.748490976,1859.140711],[0.738557811,1628.19548],[0.735019323,1610.827185],[0.726520479,1522.67108],[0.719436033,1434.312724],[0.7095203,  1328.484948],[0.702428384,1186.504826],[0.69252012, 1134.298816],[0.681189988,1028.67329],[0.671988924,976.3661545],[0.658547153,942.5396938],[0.641566895,891.3449381],[0.625288856,804.3012128],[0.613968684,770.1713758],[0.598402825,718.7743692],[0.581422567,667.5796136],[0.566568887,651.8293258],[0.551715208,636.079038],[0.544643213,637.0902924],[0.532613351,585.1876587],[0.51987878, 551.2600725],[0.511392386,552.4735778],[0.502903502,535.813161],[0.499367505,536.3187882]],
                color: '#1844e2',
                showInLegend: false
            }, {
                name: 'M1',
                data: [[0.867041516,10135.85374],[0.861326646,9725.562535],[0.859162716,9422.009235],[0.854872206,9083.011468],[0.851291386,8761.786498],[0.845576517,8351.495293],[0.839156939,7959.179136],[0.832005259,7388.224883],[0.822037233,6907.044743],[0.814912945,6532.703634],[0.807786167,6140.488602],[0.799235029,5676.980133],[0.791406032,5320.614071],[0.780723336,4785.91329],[0.77005309, 4340.582119],[0.762231563,4037.837823],[0.751586219,3771.245874],[0.743045042,3379.233093],[0.732392227,3059.019377],[0.718908124,2721.33624],[0.70755807, 2472.719338],[0.698337085,2277.420826],[0.69054046, 2153.415751],[0.682041615,2065.259646],[0.669999303,1923.987402],[0.657961971,1818.463002],[0.647344018,1748.484195],[0.638142954,1696.17706],[0.621872385,1662.755101],[0.601348659,1558.444206],[0.590730706,1488.465399],[0.576574266,1401.118298],[0.554638631,1314.883576],[0.538360592,1227.839851],[0.523496952,1140.593874],[0.508635802,1071.22182],[0.500141938,1018.813559]],
                color: '#d88d2b',
                showInLegend: false
            }, {
                name: 'M2',
                data: [[0.787130463,10165.15484],[0.783554624,9879.67771],[0.780681003,9558.351614],[0.777812363,9272.773362],[0.773521853,8933.775595],[0.766395076,8541.560564],[0.759965536,8077.748718],[0.756384717,7756.523748],[0.754230746,7524.466137],[0.749233037,7185.569495],[0.744930077,6757.202118],[0.740644548,6453.952195],[0.734252361,6258.249181],[0.729964341,5937.125336],[0.722122893,5491.389663],[0.714283936,5063.527913],[0.7057577,  4778.758665],[0.693675545,4351.503667],[0.684439619,4048.961622],[0.671675166,3800.546971],[0.656774174,3445.192163],[0.641898084,3268.576577],[0.627734173,3127.607709],[0.608614885,2951.598875],[0.58666929, 2793.868465],[0.571795689,2635.1268],[0.557634268,2512.031854],[0.547723515,2441.951922],[0.538512491,2318.149098],[0.530016136,2247.866915],[0.515147516,2124.873095],[0.504524583,2019.146444],[0.500278896,2001.879274]],
                color: '#d88d2b',
                showInLegend: false
            }, {
                name: 'A1',
                data: [[0.745403203,10153.24732],[0.741125144,9903.619159],[0.736117475,9493.226829],[0.731809534,9029.111607],[0.726094665,8618.820402],[0.721796685,8226.200869],[0.716801466,7905.178149],[0.709669708,7477.215274],[0.703247639,7067.025194],[0.69895464, 6710.153505],[0.692550002,6425.080881],[0.686842603,6068.411442],[0.674767919,5694.778211],[0.664817323,5338.715525],[0.655591358,5107.669169],[0.644960955,4948.320752],[0.631494283,4735.75507],[0.618744771,4594.583951],[0.599618013,4364.953351],[0.580496235,4171.070595],[0.563491075,3941.136618],[0.548605024,3693.025343],[0.535143332,3516.207506],[0.523800749,3321.21237],[0.508924658,3144.596783],[0.501830252,2984.742739]],
                color: '#f71818',
                showInLegend: false
            }, {
                name: 'A2',
                data: [[0.714291406,10193.44468],[0.711425256,9925.740349],[0.707829495,9497.271846],[0.703538986,9158.274079],[0.699245986,8801.40239],[0.694245787,8444.631826],[0.686409319,8034.643998],[0.68213126, 7785.015841],[0.677146002,7535.48881],[0.667905096,7197.198921],[0.662222599,7019.268704],[0.656537611,6823.464564],[0.645897247,6592.620459],[0.637385952,6415.094743],[0.630294036,6273.114621],[0.618236782,6024.598844],[0.611147356,5900.492644],[0.602646022,5794.462617],[0.592722818,5635.013074],[0.586338101,5492.931826],[0.5728789,  5333.987911],[0.55515907, 5050.533293],[0.543106797,4837.765361],[0.537419319,4624.087299],[0.527496115,4464.637757],[0.521113889,4340.430431],[0.512597613,4127.156871],[0.506207917,3949.327779],[0.502651998,3806.84203]],
                color: '#f71818',
                showInLegend: false
            }, {
                name: field,
                data: [[point_x, point_y]],
                color: "#11e07c",
                marker: {
                    enabled: true,
                    radius: 6,
                    symbol: 'diamond'
                }
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

function f_cii_chart(point, field){
    /*  CII chart */
    point = JSON.parse(point);
    point_x = parseFloat(point[0]);
    point_y = parseFloat(point[1]);

    Highcharts.chart('cii_chart', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Colloidal Instability Index Analysis'
        },

        yAxis: {
            title: {
                text: 'Saturated + Asphaltenes'
            },
            max : 100
        },
        xAxis: {
            title: {
                text: 'Aromatics + Resines'
            }
        },
        annotations: [{
            labels: [{
                point: {
                    xAxis: 0,
                    yAxis: 0,
                    x: 25,
                    y: 90
                },
                text: 'Unstable Zone'
            }, {
                point: {
                    xAxis: 0,
                    yAxis: 0,
                    x: 80,
                    y: 9
                },
                text: 'Stable Zone'
            }, {
                point: {
                    xAxis: 0,
                    yAxis: 0,
                    x: 90,
                    y: 60
                },
                text: 'Meta-Stable Zone'
            }]
        }],

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },

        series: [{
            showInLegend: false,
            name: ' ',
            data: [[0,0],[100,70]],
            color: '#000000'
        }, {
            showInLegend: false,
            name: ' ',
            data: [[0,0],[100,90]],
            color: '#000000'
        },{
            name: field,
            data: [[point_x, point_y]],
            color: "#11e07c",
            marker: {
                enabled: true,
                radius: 7,
                symbol: 'diamond'
            }
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

function f_stankiewicz_chart(point, field){
    /* Stankiewicz chart */
    point = JSON.parse(point);
    point_x = parseFloat(point[0]);
    point_y = parseFloat(point[1]);

    Highcharts.chart('stankiewicz_chart', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Stankiewicz Stability Index Analysis'
        },

        yAxis: {
            title: {
                text: 'Saturated/Aromatics'
            },
            max : 25
        },
        xAxis: {
            title: {
                text: 'Asphaltenes/Resines'
            },
            max: 2.5
        },
        annotations: [{
            labels: [{
                point: {
                    xAxis: 0,
                    yAxis: 0,
                    x: 0.5,
                    y: 15
                },
                text: 'Unstable Zone'
            }]
        }],

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },

        series: [{
            showInLegend: false,
            name: ' ',
            data: [[0.05,24.69],[0.05,20.75],[0.05,16.13],[0.05,11.58],[0.05,7.57],[0.06,5.30],[0.06,4.06],[0.07,3.26],[0.07,2.52],[0.09,1.91],[0.10,1.60],[0.15,1.11],[0.19,0.86],[0.27,0.55],[0.34,0.49],[0.41,0.43],[0.48,0.37],[0.55,0.31],[0.67,0.37],[0.80,0.25],[0.92,0.25],[1.03,0.25],[1.15,0.25],[1.26,0.25],[2.40,0.25]],
            color: '#000000'
        },{
            name: field,
            data: [[point_x, point_y]],
            color: "#11e07c",
            marker: {
                enabled: true,
                radius: 7
            }
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
</script>