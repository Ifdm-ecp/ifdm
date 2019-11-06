<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<!-- Highcharts  -->

<script type="text/javascript">
    var graph_results_perf = [];
    var graph_results_cem = [];
    var depths = [];

    <?php
        $graph_results_perf = isset($graph_results_perf) ? $graph_results_perf : [];
        $graph_results_cem = isset($graph_results_cem) ? $graph_results_cem : [];
    ?>

    @foreach ($graph_results_perf[0][1] as $graph_aux)
        var aux = {{ $graph_aux }};
        depths.push(aux);
    @endforeach

    @foreach ($graph_results_perf[0][0] as $graph_aux)
        var aux = {{ $graph_aux }};
        graph_results_perf.push(aux);
    @endforeach

    @foreach ($graph_results_cem[0][0] as $graph_aux)
        var aux = {{ $graph_aux }};
        graph_results_cem.push(aux);
    @endforeach

    $(document).ready(function()
    {
        $('#graph').highcharts({
            chart: {
                inverted: true,
                zoomType: 'x'
            },
            title: {
                text: 'Invasion Radius',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Depth [ft]'
                },
                categories: depths
            },
            yAxis: {
                title: {
                    text: 'Invasion Radius [ft]'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
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
            series: [
            {
                name: 'Invasion Radius (Drilling Phase)',
                data: graph_results_perf
            }, 
            @if ($drilling->cementingAvailable == 1)
            {
                name: 'Invasion Radius (Cementing Phase)',
                data: graph_results_cem
            }
            @endif
            ]
        });

        // var tops = [];
        // var invasion_radius_drilling = [];
        // var invasion_radius_cementation = [];
        
        // <?php
        //     $tops = isset($tops) ? $tops : [];
        //     $invasion_radius_drilling = isset($invasion_radius_drilling) ? $invasion_radius_drilling : [];
        //     $invasion_radius_cementation = isset($invasion_radius_cementation) ? $invasion_radius_cementation : [];
        // ?>

        {{-- @foreach ($tops as $top)
            var aux = {{$top}};
            tops.push(aux);
        @endforeach --}}

        {{-- @foreach ($invasion_radius_drilling as $d_invasion_radius)
            var aux = {{$d_invasion_radius}};
            invasion_radius_drilling.push(aux);
        @endforeach --}}

        {{-- @foreach ($invasion_radius_cementation as $c_invasion_radius)
            var aux = {{$c_invasion_radius}};
            invasion_radius_cementation.push(aux);
        @endforeach --}}
        
        // $('#grafico').highcharts({
        //     chart: {
        //     inverted: true,
        //     zoomType: 'x'
        //     },
        //     title: {
        //         text: 'Invasion Radius',
        //         x: -20 //center
        //     },
        //     xAxis: {
        //     title: {
        //         text: 'Depth [ft]'
        //     },
        //         categories: tops
        //     },
        //     yAxis: {
        //         title: {
        //             text: 'Invasion Radius [ft]'
        //         },
        //         plotLines: [{
        //             value: 0,
        //             width: 1,
        //             color: '#808080'
        //         }]
        //     },
        //     tooltip: {
        //         valueSuffix: ''
        //     },
        //     legend: {
        //         layout: 'vertical',
        //         align: 'right',
        //         verticalAlign: 'middle',
        //         borderWidth: 0
        //     },
        //     series: [
        //     {
        //         name: 'Invasion Radius (Drilling Phase)',
        //         data: invasion_radius_drilling
        //     }, 
        //     @if($drilling->cementingAvailable)
        //     {
        //         name: 'Invasion Radius (Cementing Phase)',
        //         data: invasion_radius_cementation
        //     }
        //     @endif
        //     ]
        // });

        // function verifyTest()
        // {
        //     //Saving table
        //     var filtrationtest_table = $("#filtration_test_t").handsontable('getData');
        //     $('#laboratorytest_table').val(JSON.stringify(filtrationtest_table));
        // }
    });
</script>
