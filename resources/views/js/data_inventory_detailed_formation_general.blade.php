<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
//Dinámicas select tab Well

$(document).ready(function()
{
  query_data({{$formation_id}});

  $("#basin").change(function(e)
  {
    $("#multiparametric_data").hide();
    $("#oil_ipr_data").hide();
    $("#disaggregation_data").hide();
    var basin = $("#basin").val(); 
    $.get("{!! url('fieldInventory') !!}",
      {basin : basin},
      function(data)
      {
        $("#field").empty();
        $.each(data.fields, function(index,value)
        {
          $("#field").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        });
        $("#field").selectpicker('refresh');
        $('#field').selectpicker('val', '');
      });
  }); 

  $("#field").change(function(e)
  {
    $("#multiparametric_data").hide();
    $("#oil_ipr_data").hide();
    $("#disaggregation_data").hide();
    var field = $("#field").val(); 

    $.get("{!! url('formationInventory') !!}",
      {field : field},
      function(data)
      {
        $("#formation").empty();
        $.each(data.formations, function(index,value)
        {
          $("#formation").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        });
        $("#formation").selectpicker('refresh');
        $('#formation').selectpicker('val', '');
      });
  }); 

  $("#formation").change(function(e)
  {
    $("#multiparametric_data").show();
    $("#oil_ipr_data").show();
    $("#disaggregation_data").show();
    query_data($("#formation").val());
  }); 

  function query_data(formation_id)
  {
    //General Data
    $("#m_rock_properties_table tr").remove();
    $("#m_rock_properties_table").empty();

    //Water oil
    $("#water_oil_table tr").remove();
    $("#water_oil_table").empty();

    //Gas liquid
    $("#gas_liquid_table tr").remove();
    $("#gas_liquid_table").empty();

    //General Data
    var m_body_rock = "<thead><tr><th>Data</th><th>Value</th></tr></thead><tbody>";
    var body_water_oil = "";
    var body_gas_liquid = "";

    //Arreglos para gráficos water_oil
    var sw = [];
    var krw = [];
    var kro = [];
    var pcwo = [];

    //Arreglos para gráficos gas_liquid
    var sg = [];
    var krg = [];
    var krog = [];
    var pcgl = [];

    $.get('{!! url("formation_inventory_detailed") !!}',
      {formation:formation_id},
      function(data)
      {
        $.each(data.formation, function(index,value)
        {
          m_body_rock+= "<tr>";
          if(value.top == null || value.top === '')
          {
            m_body_rock += "<td>Top</td><td><font color='red'>Missing Information</font></td>";
          }
          else
          {
            m_body_rock += "<td>Top</td><td><font color='green'>"+value.top+"</font></td>";
          }
          m_body_rock+= "</tr>";

          m_body_rock += "<tr>";
          if(value.netpay == null || value.netpay === '')
          {
            m_body_rock  += "<td>Netpay</td><td><font color='red'>Missing Information</font></td>";
          }
          else
          {
            m_body_rock  += "<td>Netpay</td><td><font color='green'>"+value.netpay+"</font></td>";
          }
          m_body_rock += "</tr>";

          m_body_rock += "<tr>";
          if(value.porosidad == null || value.porosidad === '')
          {
            m_body_rock  += "<td>Porosity</td><td><font color='red'>Missing Information</font></td>";
          }
          else
          {
            m_body_rock  += "<td>Porosity</td><td><font color='green'>"+value.porosidad+"</font></td>";
          }
          m_body_rock += "</tr>";

          m_body_rock += "<tr>";
          if(value.permeabilidad == null || value.permeabilidad === '')
          {
            m_body_rock  += "<td>Permeability</td><td><font color='red'>Missing Information</font></td>";
          }
          else
          {
            m_body_rock  += "<td>Permeability</td><td><font color='green'>"+value.permeabilidad+"</font></td>";
          }
          m_body_rock += "</tr>";

          m_body_rock += "<tr>";
          if(value.presion_reservorio == null || value.presion_reservorio === '')
          {
            m_body_rock  += "<td>Reservoir Pressure</td><td><font color='red'>Missing Information</font></td>";
          }
          else
          {
            m_body_rock  += "<td>Reservoir Pressure</td><td><font color='green'>"+value.presion_reservorio+"</font></td>";
          }
          m_body_rock += "</tr>";

        });

        if(data.gas_liquid.length==0)
        {
          body_gas_liquid += "<h4><font color='red'>There's no Gas-Oil Data for this Producing Interval</font></h4>";
          $("#gas_liquid_chart").hide();
        }
        else
        {

          body_gas_liquid = "<thead><tr><th>Sg</th><th>Krg</th><th>Krl</th><th>Pcgl</th></tr></thead><tbody>";
          $.each(data.gas_liquid, function(index, value)
          {
            body_gas_liquid += "<tr><td>"+value[0]+"</td>"+"<td>"+value[1]+"</td>"+"<td>"+value[2]+"<td>"+value[3]+"</td></tr>";
            sg.push(value[0]);
            krg.push(value[1]);
            krog.push(value[2]);
            pcgl.push(value[3]);
          });

          $('#gas_liquid_chart').highcharts({
                 title: {
                     text: 'Gas-Oil Kr\'s',
                     x: -20 //center
                 },
                 subtitle: {
                     text: '',
                     x: -20
                 },
                 xAxis: {
                    title: {text:'Sg'},
                     categories: sg
                 },
                 yAxis: {
                     title: {
                         text: 'Krg & Krog'
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
                     name: 'Krg',
                     data: krg
                 }, {
                     name: 'Krog',
                     data: krog
                 }, {
                     name: 'Pcgl',
                     data: pcgl
                 }]
             });

          $("#gas_liquid_chart").show();
        }

        //Datos y gráficos tabla water-oil
        if(data.water_oil.length==0)
        {
          body_water_oil += "<h4><font color='red'>There's no Water-Oil Data for this Producing Interval</font></h4>";
          $("#water_oil_chart").hide();
        }
        else
        {
          body_water_oil = "<thead><tr><th>Sw</th><th>Krw</th><th>Kro</th><th>Pcwo</th></tr></thead><tbody>";
          $.each(data.water_oil, function(index, value)
          {
            body_water_oil += "<tr><td>"+value[0]+"</td>"+"<td>"+value[1]+"</td>"+"<td>"+value[2]+"<td>"+value[3]+"</td></tr>";
            sw.push(value[0]);
            krw.push(value[1]);
            kro.push(value[2]);
            pcwo.push(value[3]);
          });

          $('#water_oil_chart').highcharts({
                 title: {
                     text: 'Water-Oil Kr\'s',
                     x: -20 //center
                 },
                 xAxis: {
                  title: {
                    text: 'Sw'
                  },
                     categories: sw
                 },
                 yAxis: {
                     title: {
                         text: 'Krw & Kro'
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
                     name: 'Krw',
                     data: krw
                 }, {
                     name: 'Kro',
                     data: kro
                 }, {
                     name: 'Pcwo',
                     data: pcwo
                 }]
             });

          $("#water_oil_chart").show();
        }

        //Reservoir Data
        $("#m_rock_properties_table").append(m_body_rock);
        $("#water_oil_table").append(body_water_oil);
        $("#gas_liquid_table").append(body_gas_liquid);
      });
  }
});



</script>