<script src="http://nextgen.pl/_/scroll/dist/jquery.handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="http://nextgen.pl/_/scroll/dist/jquery.handsontable.full.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script type="text/javascript" src="{{asset('js/arbol.js')}}"></script>
<script>
$(document).ready(function(){
    @if(isset($data))
        $("#pvt_table").val(JSON.stringify({!!$data->pvt_table!!}));
    @endif
    cargarTable();
});

////eventos
$('.check_field_pvt').on('click', function(e) {
    organizarData();
 });

 function organizarData()
 {
     
    let datos_pvt = $("#table_field_pvt").handsontable('getData');
        let dpvt = [];
        for (var i = 0; i<datos_pvt.length; i++) {
            d0 = datos_pvt[i][0];
            d1 = datos_pvt[i][1];
            d2 = datos_pvt[i][2];
            d3 = datos_pvt[i][3];
            d4 = datos_pvt[i][4];
            d5 = datos_pvt[i][5];
            d6 = datos_pvt[i][6];
            d7 = datos_pvt[i][7];
            d8 = datos_pvt[i][8];

            if(d0 === "" && d1 === "" && d2 === "" && d3 === "" && d4 === "" && d5 === "" && d6 === "" && d7 === "" && d8 === "") {
                continue;
            } else if(d0 === null && d1 === null && d2 === null && d3 === null && d4 === null && d5 === null && d6 === null && d7 === null && d8 === null) {
                continue;
            } else if((d0 == 0) && (d1 == 0 ) && (d2 == 0) && (d3 == 0) && (d4 == 0 ) && (d5 == 0) && (d6 == 0 ) && (d7 == 0) && (d8 == 0)){
                continue;
            } else {
                dpvt.push([d0,d1,d2,d3,d4,d5,d6,d7,d8]);
            }

        }

        $("#pvt_table").val(JSON.stringify(dpvt));
        
 }



//////funciones
function plot_fieldPVT() {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault();
    data = $("#table_field_pvt").handsontable('getData');
    var pressure = [];
    var muO = [];
    var muG = [];
    var muW = [];
    var Bo = [];
    var Bg = [];
    var Bw = [];
    var Rs = [];
    var Rv = [];

    for (var i = 0; i < data.length; i++) {
        pressure.push(data[i][0]);
        muO.push(data[i][1]);
        muG.push(data[i][2]);
        muW.push(data[i][3]);
        Bo.push(data[i][4]);
        Bg.push(data[i][5]);
        Bw.push(data[i][6]);
        Rs.push(data[i][7]);
        Rv.push(data[i][8]);
    }
    pressure.pop();
    muO.pop();
    muG.pop();
    muW.pop();
    Bo.pop();
    Bg.pop();
    Bw.pop();
    Rs.pop();
    Rv.pop();

    $('#pvtField').highcharts({
        title: {
            text: 'Field\'s PVT',
            x: -20 //center
        },
        xAxis: {
            title: {
                text: 'Pressure'
            },
            categories: pressure
        },
        yAxis: {
            title: {
                text: ''
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
        series: [{
                name: 'uo [cP]',
                data: muO
            }, {
                name: 'ug [cP]',
                data: muG
            },
            {
                name: 'uw [cP]',
                data: muW
            },
            {
                name: 'Bo [RB/STB]',
                data: Bo
            },
            {
                name: 'Bg [RCF/SCF]',
                data: Bg
            },
            {
                name: 'Bw [RB/STB]',
                data: Bw
            },
            {
                name: 'Rs [SCF/STB]',
                data: Rs
            },
            {
                name: 'Rv [STB/SCF]',
                data: Rv
            }
        ]
    });
}

function cargarTable()
{
    data_pvt = $("#pvt_table").val();
    if (data_pvt === '') {
        data_pvt = [
            [, , , , , , , , ],
            [, , , , , , , , , ]
        ];
    } else {
        data_pvt = JSON.parse(data_pvt);
        console.log('import')
        console.log(data_pvt)
    }

    var maxed = false,
        resizeTimeout, 
        availableWidth, 
        availableHeight, 
        $window = $(window),
        $table_field_pvt = $('#table_field_pvt');

    var calculateSize = function() {
        var offset = $example1.offset();
        availableWidth = $window.width() - offset.left + $window.scrollLeft();
        availableHeight = $window.height() - offset.top + $window.scrollTop();
    };

    $window.on('resize', calculateSize);

    $table_field_pvt.handsontable({
        data: data_pvt,
        colWidths: [100, 55, 55, 55, 90, 90, 90, 90, 90],
        rowHeaders: true,
        columns: [{
                title: "Pressure[psia]",
                data: 0,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "μo[cP]",
                data: 1,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "μg[cP]",
                data: 2,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "μw[cP]",
                data: 3,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Bo[RB/STB]",
                data: 4,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Bg[RCF/SCF]",
                data: 5,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Bw[RB/STB]",
                data: 6,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Rs[SCF/STB]",
                data: 7,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Rv[STB/SCF]",
                data: 8,
                type: 'numeric',
                format: '0[.]0000000'
            }
        ],

        minSpareRows: 1,
        contextMenu: true,
        width: function() {
            if (maxed && availableWidth === void 0) {
                calculateSize();
            }
            return maxed ? availableWidth : 1000;
        },
        height: function() {
            if (maxed && availableHeight === void 0) {
                calculateSize();
            }
            return maxed ? availableHeight : 300;
        }
    });
}



</script>