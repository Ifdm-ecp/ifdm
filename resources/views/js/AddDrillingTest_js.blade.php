<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

//Tables
$filtrationTest = $("#filtration_test_t");
var data_aux = [[,],[,],[,],[,],[,]];
$filtrationTest.handsontable(
{
  data: data_aux, 
  rowHeaders: true, 
  colWidths: [150,150],
  columns: 
  [
    {title:"Time [min]", data: 0,type: 'numeric', format: '0[.]0000000'},
    {title:"Filtered Volume [ml]",data: 1,type: 'numeric', format: '0[.]0000000'}
  ],
  minSpareRows: 1,
  contextMenu: true,
});

function verifyTest()
{
  //Saving table
  var filtrationtest_table = $("#filtration_test_t").handsontable('getData');
  $('#laboratorytest_table').val(JSON.stringify(filtrationtest_table));

}

function plotLaboratoryTest()
{

  data = $("#filtration_test_t").handsontable('getData');
  var time = [];
  var filtered_volume = [];

  for (var i = 0; i < data.length; i++)
  {
    time.push(data[i][0]);
    filtered_volume.push(data[i][1]);
  }
  time.pop();
  filtered_volume.pop();
  $('#laboratory_test_g').highcharts({
         title: {
             text: 'Laboratory Test',
             x: -20 //center
         },
         xAxis: {
          title: {
            text: 'Time [min]'
          },
             categories: time
         },
         yAxis: {
             title: {
                 text: 'Filtered Volume [ml]'
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
             showInLegend: false,    
             name: 'Filtered Volume [ml]',
             data: filtered_volume
         }]
     });
}
</script>