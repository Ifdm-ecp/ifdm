
<!-- HandsOnTable JS -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    defineWellType(document.getElementById('well_type'));
    import_tree("IPR", "IPR");
    assignEvents();
    @if (isset($IPR->id))
    setDefaultValues();
    @endif

    setTimeout(function() {
      $('.stress_sensitive_reservoir_class').change();
    }, 1000);
  });
  /* En este archivo se encuentra todo el javascript que controla las ventanas de ipr en modo Crear y editar */

  /**
  * Author: Esneider Mejia Ciro
  * Date: 16/10/2018
  * Descripción: Esta función se encarga de inicializar los eventos principales
  */
  function assignEvents() {
    $("#well_data").click(function() {
      $("#run").hide();
      validateTabs(false);
    });

    $("#production_data").click(function() {
      $("#run").hide();
      validateTabs(false);
    });

    $("#rock_properties").click(function() {
      $("#run").hide();
      validateTabs(false);
    });

    $("#fluid_properties").click(function() {
      $("#run").show();
      validateTabs(false);
    });

    setTimeout(function() {
      validateTabs(false);
      $('.tabbable').on('keyup', function(event) {
        event.preventDefault();
        validateTabs(false);
      });
    }, 100);
  }

  /* *********Sección Funciones********* */
  /* Descripción: esta función ordena el contenido de la tabla pvt en todos los fluidos con base en los valores de la columna de presión. 
  Adicionales: aunque las pvt para aceite, gas y gas condensado son diferentes, la función sólo depende de la columna de presión para ordenar todos los datos. */
  function order_pvt(matrix)
  {
    var row_aux;
    for (var i = 0 ; i<matrix.length; i++) 
    {
      for (var j=0; j<matrix.length; j++) {
        if(matrix[j][0]>matrix[i][0] && matrix[i][0])
        {
          row_aux = matrix[j];
          matrix[j] = matrix[i];
          matrix[i] = row_aux;
        }
      }   
    }
    return matrix;
  }

  /* Descripción: esta función se encarga de capturar los datos del formulario ipr edit en la sección de exponentes de corey para gas-aceite, calcular el gráfico de kr, generar el gráfico y mostrarlo en la vista ipredit */
  function plot_corey_gasOil()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault();
    var sgc = $("#saturacion_gas_crit").val();
    var sorg = $("#saturacion_aceite_irred_gas").val();
    var kromax = $("#end_point_kr_aceite_gas").val();
    var krgmax = $("#end_point_kr_gas").val();
    var no = $("#exponente_corey_aceite_gas").val();
    var ng = $("#exponente_corey_gas").val();

    var den = 1-sgc-sorg;
    var increment = den/9;
    var sw = [];
    var swn = [];
    var kro = [];
    var krw = [];

    sw.push(parseFloat(sgc));
    kro.push(parseFloat(kromax));
    krw.push(parseFloat(0));
    for (var i = 1; i < 9; i++) 
    {
      sw.push((parseFloat(sw[i-1])+increment).toFixed(2));
    }
    for (var i = 0; i < 10; i++) 
    {
      swn.push((sw[i]-sgc)/den);
    }
    for (var i = 1; i < 9 ; i++) 
    {
      kro.push(parseFloat((kromax*(Math.pow(1-swn[i],no)))));
    }
    kro.push(parseFloat(0));
    for (var i = 1; i < 9; i++)
    {
      krw.push(parseFloat((krgmax*(Math.pow(swn[i],ng)))));
    }
    krw.push(parseFloat(krgmax));
    sw.push(parseFloat(1-sorg));
    $('#corey_gasOil').highcharts({
      title: {
        text: 'Gas-Oil Kr\'s',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Sw'
        },
        categories: sw
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
        name: 'Krw',
        data: krw
      }, {
        name: 'Kro',
        data: kro
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos del formulario ipr edit en la sección de exponentes de corey para agua-aceite, calcular el gráfico de kr, generar el gráfico y mostrarlo en la vista ipredit */
  function plot_corey_waterOil()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault();
    var swi = $("#saturacion_agua_irred").val();
    var sor = $("#saturacion_aceite_irred").val();
    var kromax = $("#end_point_kr_petroleo").val();
    var krwmax = $("#end_point_kr_agua").val();
    var no = $("#exponente_corey_petroleo").val();
    var nw = $("#exponente_corey_agua").val();

    var den = 1-swi-sor;
    var increment = den/9;
    var sw = [];
    var swn = [];
    var kro = [];
    var krw = [];
    sw.push(parseFloat(swi));
    kro.push(parseFloat(kromax));
    krw.push(parseFloat(0));

    for (var i = 1; i < 9; i++) 
    {
      sw.push((parseFloat(sw[i-1])+increment).toFixed(2));
    }
    for (var i = 0; i < 10; i++) 
    {
      swn.push((sw[i]-swi)/den);
    }
    for (var i = 1; i < 9 ; i++) 
    {
      kro.push(parseFloat((kromax*(Math.pow(1-swn[i],no)))));
    }
    kro.push(parseFloat(0));
    for (var i = 1; i < 9; i++)
    {
      krw.push(parseFloat((krwmax*(Math.pow(swn[i],nw)))));
    }
    krw.push(parseFloat(krwmax));
    sw.push(parseFloat(1-sor));

    $('#corey_waterOil').highcharts({
      title: {
        text: 'Water-Oil Kr\'s',
        x: -20
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
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos de la tabla de PVT desde el formulario ipr edit para fluido aceite en la sección "Fluid properties", calcular el gráfico de pvt, generar el gráfico y mostrarlo en la vista ipredit */
  function plot_pvt_oil()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault(); 
    var data = $("#excel_table_pvt").handsontable('getData');
    var pressure = [];
    var oil_viscosity = [];
    var oil_volumetric_factor = [];
    var water_viscosity = [];
    for (var i = 0; i < data.length; i++)
    {
      //pressure.push(parseFloat(data[i][0]));
      oil_viscosity.push([parseFloat(data[i][0]), parseFloat(data[i][1])]);
      oil_volumetric_factor.push([parseFloat(data[i][0]), parseFloat(data[i][2])]);
      water_viscosity.push([parseFloat(data[i][0]), parseFloat(data[i][3])]);
    }
    //pressure.pop();
    //pressure.pop();
    oil_viscosity.pop();
    oil_viscosity.pop();
    oil_volumetric_factor.pop();
    oil_volumetric_factor.pop();
    water_viscosity.pop();
    water_viscosity.pop();


    $('#graph_oil_viscosity').highcharts({
      title: {
        type: 'line',
        text: 'Oil Viscosity',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Viscosity [cp]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Oil Viscosity',
        data: oil_viscosity,
        tooltip:{
          valueSuffix: ' cp'
        }
      }]
    });

    $('#graph_oil_volumetric_factor').highcharts({
      title: {
        type: 'line',
        text: 'Oil Volumetric Factor',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Volumetric Factor [RB/STB]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Oil Volumetric Factor',
        data: oil_volumetric_factor,
        tooltip:{
          valueSuffix: ' RB/STB'
        }
      }]
    });

    $('#graph_water_viscosity').highcharts({
      title: {
        type: 'line',
        text: 'Water Viscosity',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Viscosity [cp]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Water Viscosity',
        data: water_viscosity,
        tooltip:{
          valueSuffix: ' cp'
        }
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos de la tabla de PVT desde el formulario ipr edit para fluido gas en la sección "Fluid properties", calcular el gráfico de pvt, generar el gráfico y mostrarlo en la vista ipredit */
  function plot_pvt_gas()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault(); 
    var data = $("#excel_table_pvt").handsontable('getData');
    var pressure = [];
    var gas_viscosity = [];
    var gas_compressibility_factor = [];

    for (var i = 0; i < data.length; i++)
    {
      //pressure.push(parseFloat(data[i][0]));
      gas_viscosity.push([parseFloat(data[i][0]), parseFloat(data[i][1])]);
      gas_compressibility_factor.push([parseFloat(data[i][0]), parseFloat(data[i][2])]);
    }

    //pressure.pop();
    gas_viscosity.pop();
    gas_viscosity.pop();
    gas_compressibility_factor.pop();
    gas_compressibility_factor.pop();

    console.log(gas_viscosity);
    console.log(gas_compressibility_factor);

    $('#graph_gas_viscosity').highcharts({
      title: {
        text: 'Gas Viscosity',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Viscosity [cp]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Gas Viscosity [cp]',
        data: gas_viscosity,
        tooltip: {
          valueSuffix: ' cp'
        }
      }]
    });
    
    $('#graph_gas_compressibility_factor').highcharts({
      title: {
        text: 'Gas Compressibility Factor',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Compressibility Factor [-]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Gas Compressibility Factor',
        data: gas_compressibility_factor,
        tooltip:{
          valueSuffix: ' [-]'
        }
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos de la tabla de PVT desde el formulario ipr edit para fluido gas condensado en la sección "Fluid properties", generar el gráfico y mostrarlo en la vista ipredit */
  function plot_pvt_c_g()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault(); 
    var data = $("#excel_table_pvt").handsontable('getData');

    var oil_volumetric_factor = [];
    var oil_viscosity = [];
    var rs = [];
    var gas_volumetric_factor = [];
    var gas_viscosity = [];
    var oil_gas_ratio = [];

    for (var i = 0; i < data.length; i++)
    {
      oil_volumetric_factor.push([parseFloat(data[i][0]), parseFloat(data[i][1])]);
      oil_viscosity.push([parseFloat(data[i][0]), parseFloat(data[i][2])]);
      rs.push([parseFloat(data[i][0]), parseFloat(data[i][3])]);
      gas_volumetric_factor.push([parseFloat(data[i][0]), parseFloat(data[i][4])]);
      gas_viscosity.push([parseFloat(data[i][0]), parseFloat(data[i][5])]);
      oil_gas_ratio.push([parseFloat(data[i][0]), parseFloat(data[i][6])]);
    }

    oil_volumetric_factor.pop();
    oil_viscosity.pop();
    rs.pop();
    gas_volumetric_factor.pop();
    gas_viscosity.pop();
    oil_gas_ratio.pop();

    $('#graph_bo').highcharts({
      title: {
        type: 'line',
        text: 'Oil Volumetric Factor',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Bo [RB/STB]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Oil Volumetric Factor',
        data: oil_volumetric_factor,
        tooltip:{
          valueSuffix: ' RB/STB'
        }
      }]
    });

    $('#graph_uo').highcharts({
      title: {
        type: 'line',
        text: 'Oil Viscosity',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Uo [cp]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Oil Viscosity',
        data: oil_viscosity,
        tooltip:{
          valueSuffix: ' cp'
        }
      }]
    });

    $('#graph_rs').highcharts({
      title: {
        type: 'line',
        text: 'Solution Oil - Gas Ratio',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'RS [SCF/STB]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Solution Oil - Gas Ratio',
        data: rs,
        tooltip:{
          valueSuffix: ' SCF/STB'
        }
      }]
    });

    $('#graph_bg').highcharts({
      title: {
        type: 'line',
        text: 'Gas Volumetric Factor',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Bg [RCS/SCF]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Gas Volumetric Factor',
        data: gas_volumetric_factor,
        tooltip:{
          valueSuffix: ' RCS/SCF'
        }
      }]
    });

    $('#graph_ug').highcharts({
      title: {
        type: 'line',
        text: 'Gas Viscosity',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'Ug [cp]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Gas Viscosity',
        data: gas_viscosity,
        tooltip:{
          valueSuffix: ' cp'
        }
      }]
    });

    $('#graph_ogratio').highcharts({
      title: {
        type: 'line',
        text: 'Oil - Gas Ratio',
        x: -20
      },
      xAxis: {
        title: {
          text: 'Pressure [psi]'
        }
      },
      yAxis: {
        title: {
          text: 'O-G Ratio [STB/SCF]'
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
        align: 'center',
        verticalAlign: 'bottom',
        borderWidth: 0
      },
      series: [{
        name: 'Oil - Gas Ratio',
        data: oil_gas_ratio,
        tooltip:{
          valueSuffix: ' STB/SCF'
        }
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos de la tabla de kr agua-aceite desde el formulario ipr edit para fluido aceite en la sección "Rock properties", generar el gráfico y mostrarlo en la vista ipredit */
  function plot_waterOil()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault(); 
    data = $("#excelwateroil").handsontable('getData');

    var data_aux = [];
    //Input Data Profile
    for (var i = 0; i < data.length; i++) 
    {
      d0 = data[i][0];
      d1 = data[i][1];
      d2 = data[i][2];

      if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
      {
        continue;
      }
      else
      {
        data_aux.push(data[i]);
      }
    }
    data = data_aux;

    var sw = [];
    var krw = [];
    var kro = [];
    for (var i = 0; i < data.length; i++)
    {
      sw.push(data[i][0]);
      krw.push(data[i][1]);
      kro.push(data[i][2]);
    }

    sw = sw.map(Number);
    krw = krw.map(Number);
    kro = kro.map(Number);

    $('#graph_left').highcharts({
      title: {
        text: 'Water-Oil Kr\'s',
        x: -20
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
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos de la tabla de kr gas-aceite desde el formulario ipr edit para fluido aceite en la sección "Rock properties", generar el gráfico y mostrarlo en la vista ipredit */
  function plot_gasOil()
  {
    data = $("#excelgasliquid").handsontable('getData');
    var data_aux = [];
    //Input Data Profile
    for (var i = 0; i < data.length; i++) 
    {
      d0 = data[i][0];
      d1 = data[i][1];
      d2 = data[i][2];

      if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
      {
        continue;
      }
      else
      {
        data_aux.push(data[i]);
      }
    }
    data = data_aux;

    var sg = [];
    var krg = [];
    var krog = [];
    for (var i = 0; i < data.length; i++)
    {
      sg.push(data[i][0]);
      krg.push(data[i][1]);
      krog.push(data[i][2]);
    }


    sg = sg.map(Number);
    krg = krg.map(Number);
    krog = krog.map(Number);
    
    $('#graph_right').highcharts({
      title: {
        text: 'Gas-Oil Kr\'s',
        x: -20
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
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos de la tabla de kr gas-aceite desde el formulario ipr edit para fluido gas condensado en la sección "Rock properties", generar el gráfico y mostrarlo en la vista ipredit */
  function plot_gasOil_c_g()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault(); 
    data = $("#excelgasliquid_c_g").handsontable('getData');

    var data_aux = [];
    //Input Data Profile
    for (var i = 0; i < data.length; i++) 
    {
      d0 = data[i][0];
      d1 = data[i][1];
      d2 = data[i][2];

      if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
      {
        continue;
      }
      else
      {
        data_aux.push(data[i]);
      }
    }
    data = data_aux;

    var sg = [];
    var krg = [];
    var krog = [];
    for (var i = 0; i < data.length; i++)
    {
      sg.push(data[i][0]);
      krg.push(data[i][1]);
      krog.push(data[i][2]);
    }


    sg = sg.map(Number);
    krg = krg.map(Number);
    krog = krog.map(Number);
    $('#graph_left').highcharts({
      title: {
        text: 'Gas-Oil Kr\'s',
        x: -20
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
      }]
    });
  }

  /* Descripción: esta función se encarga de capturar los datos de la tabla de Dropout desde el formulario ipr edit para fluido gas condensado en la sección "Fluid properties", generar el gráfico y mostrarlo en la vista ipredit */
  function plot_drop_out_c_g()
  {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault(); 
    data = $("#excel_table_dod").handsontable('getData');
    var pressure = [];
    var liquid_fracture = [];

    for (var i = 0; i < data.length; i++)
    {
      pressure.push(data[i][0]);
      liquid_fracture.push(data[i][1]);
    }
    pressure.pop();
    liquid_fracture.pop();

    $('#graph_down').highcharts({
      title: {
        text: 'Condensate Gas Drop Out',
        x: -20
      },
      subtitle: {
        text: '',
        x: -20
      },
      xAxis: {
        title: {text:'Pressure'},
        categories: pressure
      },
      yAxis: {
        title: {
          text: 'Liquid Fraction'
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
        name: 'Liquid Fraction',
        data: liquid_fracture
      }]
    });
  }
  /* Descripción: esta función verifica si en la tabla PVT para fluido aceite existen la presión saturación (Saturation Pressure), existe un valor de presión menor y un valor de presión mayor para la adecuada ejecución del módulo ipr. 

  Parámetros:
  array: arreglo con las presiones de la tabla pvt. 
  Adicionales: dentro de la función se extrae el valor de presión saturación desde el formulario */
  function verifyPVToil(array)
  {
    var valid=false;
    var valid2=false;
    var valid3=false;

    for (var j = 0; j < array.length; j++) {
      if ( array[j] == $("#presion_saturacion").val()) {
        valid=true;
      }

      if(array[j] > $("#presion_saturacion").val()) {
        valid2=true;
      }

      if(array[j] < $("#presion_saturacion").val()) {
        valid3=true;
      }

    }
    return(valid&&valid2&&valid3);
  }

  /* Descripción: esta función verifica si en la tabla PVT para fluido gas condensado existen la presión saturación (Saturation Pressure), existe un valor de presión menor y un valor de presión mayor para la adecuada ejecución del módulo ipr. 

  Parámetros:
  array: arreglo con las presiones de la tabla pvt. 
  Adicionales: dentro de la función se extrae el valor de presión saturación desde el formulario */
  function verifyPVT_cg(array)
  {
    var valid=false;
    var valid2=false;
    var valid3=false;

    for (var j = 0; j < array.length; j++)
    {
      if ( array[j] == $("#presion_saturacion_c_g").val())
      {
        valid=true;
      }
      if(array[j] > $("#presion_saturacion_c_g").val())
      {
        valid2=true;
      }
      if(array[j] < $("#presion_saturacion_c_g").val())
      {
        valid3=true;
      }   
    }
    return(valid&&valid2&&valid3);
  }

  /* Descripción: esta función verifica si un arreglo dado está ordenado de manera ascendente. 
  Parámetros:
  array: arreglo con los valores a evaluar. */
  function is_asc(array)
  {
    var valid=true;
    for (var j = 1; j < array.length; j++)
    {
      if ( array[j+1] < array[j] ){
        valid=false;
      }

    }
    return valid;
  }

  /* Descripción: esta función verifica si un arreglo dado está ordenado de manera descendente. 
  Parámetros:
  array: arreglo con los valores a evaluar. */
  function is_desc(array)
  {
    var valid=true;
    for (var j = 1; j < array.length; j++)
    {
      if ( array[j+1] > array[j] ){
        valid=false;
      }

    }
    return valid;
  }

  /* Descripción: esta función guarda los datos de las tablas de la ipr que se está ejecutando sea aceite, gas o gas condensado en los campos hidden creados en el formulario para la recarga de la página y el almacenamiento temporal de los datos. Además, se encarga de normalizar los textfields y las tablas con base en los check box que se encuentran en el formulario para no guardar datos innecesarios. */
  function save() {
    /* Lectura y almacenado de datos de las tablas del formulario */
    /* Oil */
    var datos_unidades_hidraulicas = order_pvt(curr_hot.getData());
    var datos_wateroil = $("#excelwateroil").handsontable('getData');
    var datos_gasliquid = $("#excelgasliquid").handsontable('getData');

    /* Gas */
    var datos_pvt_ipr_g = order_pvt($excel_tabular_pvt_fluid.handsontable('getData'));

    $("#presiones_table").val(JSON.stringify(datos_unidades_hidraulicas));
    $("#wateroil_hidden").val(JSON.stringify(datos_wateroil));
    $("#gasliquid_hidden").val(JSON.stringify(datos_gasliquid));

    $('#pvt_gas_ipr').val(JSON.stringify(datos_pvt_ipr_g));

    /* Datos Condesate gas */
    var pvt_cg_data = $("#pvt_c_g").handsontable('getData');
    var go_cg_data = $("#excelgasliquid_c_g").handsontable('getData');
    var dropout_cg_data = $("#excel_table_dod").handsontable('getData');

    $('#pvt_cg').val(JSON.stringify(pvt_cg_data));
    $('#gas_oil_kr_cg').val(JSON.stringify(go_cg_data));
    $('#dropout_cg').val(JSON.stringify(dropout_cg_data));

    var form = $(this).parents('form:first');
    document.getElementById('loading').style.display = 'block';
    form.submit(); 
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 16/10/2018
  * Descripción: Esta función se encarga de realizar la respectivas validaciones cuando el tipo de
  * pozo es 'Producer' y el fluido es 'Black Oil'.
  */
  function validateBlackOil(validate_fast) {
    /* Se definen estas variables para la revisión de los datos */
    /* Para revisión de Water - Oil */
    var sw_wateroil_rev = [];
    var krw_wateroil_rev = [];
    var kro_wateroil_rev = [];

    /* Para revisión de Gas - Oil */
    var sg_gasoil_rev = [];
    var krg_gasoil_rev = [];
    var krog_gasoil_rev = [];

    /* Para revisión de PVT */
    var pressure_pvtoil_rev = [];

    /* Para normalizar las tablas */
    var datos_wateroil_aux = [];
    var datos_gasliquid_aux = [];
    var datos_unidades_hidraulicas_aux = [];
    
    /* Para verificar el estado de las validaciones, contendra los mensajes de error*/
    var validaciones = [];

    /* Para fluid properties en oil black existen dos tablas, water-oil y gas-oil */
    var datos_wateroil = $("#excelwateroil").handsontable('getData');
    var datos_gasliquid = $("#excelgasliquid").handsontable('getData');  

    /* Para fluid properties en oil black existe una tabla, pvt*/
    var datos_unidades_hidraulicas = order_pvt($("#excel_table_pvt").handsontable('getData'));

    if($("#check_tabular_rel_perm").is(":checked"))
    {
      /**** Inicio validaciones para la tabla Water-Oil cuando 'Tabular' esta activo ****/
      /* Water oil */
      d0 = datos_wateroil[0][0];
      d1 = datos_wateroil[0][1];
      d2 = datos_wateroil[0][2];
      d3 = datos_wateroil[1][0];
      d4 = datos_wateroil[1][1];
      d5 = datos_wateroil[1][2];

      if((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) || (d3 === "" || d3 == null) && (d4 ==="" || d4 == null) && (d5 === "" || d5 == null)) {
        validaciones.push({"mensaje": "Check your Water-Oil data for Oil IPR, there's nothing there..."});
      }

      if(d1 != 0) {
        validaciones.push({"mensaje": "The first Water-Oil krw value must be 0. Check your Relative Permeability Data"});
      }

      if(((d0 == 0) && (d1 == 0) && (d2 == 0)) || ((d3 == 0) && (d4 == 0) && (d5 == 0))) {
        validaciones.push({"mensaje": "The whole row can't be 0 at Water-Oil data. Check your Relative Permeability Data"});
      }

      for (var i = 0; i < datos_wateroil.length; i++) {
        d0 = datos_wateroil[i][0];
        d1 = datos_wateroil[i][1];
        d2 = datos_wateroil[i][2];

        if((d0 == 0) && (d1 == 0 ) && (d2 == 0)) {
          if(d0 === "" && d1 === "" && d2 === "") {
            continue;
          } else {
            validaciones.push({"mensaje": "The whole row can't be 0 at Water-Oil data. Check your Relative Permeability Data."});
          }
        }

        if((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "")) {
          if(d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "") {
            validaciones.push({"mensaje": "Check your Water-Oil data for Oil IPR, there's some incomplete data. Check your Relative Permeability Data."});
          }

          if(d1<0 || d2<0 || d0<0) {
            validaciones.push({"mensaje": "Your Water-Oil data must be greather than 0. Check your Relative Permeability Data."});
          }

          if(d0<0 || d0>1) {
            validaciones.push({"mensaje": "Water-Oil Sw must be between 0 and 1. Check your Relative Permeability Data."});
          }

          if(d1<0 || d1>1) {
            validaciones.push({"mensaje": "Water-Oil krw must be between 0 and 1. Check your Relative Permeability Data."});
          }

          if(d2<0 || d2>1) {
            validaciones.push({"mensaje": "Water-Oil kro must be between 0 and 1. Check your Relative Permeability Data."});
          }

          sw_wateroil_rev.push(d0);
          krw_wateroil_rev.push(d1);
          kro_wateroil_rev.push(d2);
        }
      }

      if(!is_asc(sw_wateroil_rev)) {
        validaciones.push({"mensaje": "Water Oil sw data must be ascending sorted. Check your Relative Permeability Data."});
      }

      if(!is_asc(krw_wateroil_rev)) {
        validaciones.push({"mensaje": "Water Oil krw data must be ascending sorted. Check your Relative Permeability Data."});
      }

      if(!is_desc(kro_wateroil_rev)) {
        validaciones.push({"mensaje": "Water Oil kro must be descending sorted. Check your Relative Permeability Data."});
      } else {
        if(kro_wateroil_rev[kro_wateroil_rev.length-1]!=0) {
          validaciones.push({"mensaje": "The last Kro data must be 0. Check your Relative Permeability Data."});
        }
      }

      for (var i = 0; i < datos_wateroil.length; i++) {
        d0 = datos_wateroil[i][0];
        d1 = datos_wateroil[i][1];
        d2 = datos_wateroil[i][2];

        if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) {
          continue;
        } else {
          datos_wateroil_aux.push(datos_wateroil[i]);
        }
      }
      datos_wateroil = datos_wateroil_aux;
      /**** Fin validaciones para la tabla Water-Oil cuando 'Tabular' esta activo ****/

      /**** Inicio validaciones para la tabla Gas-Oil cuando 'Tabular' esta activo ****/
      /* Gas-oil */
      d0 = datos_gasliquid[0][0];
      d1 = datos_gasliquid[0][1];
      d2 = datos_gasliquid[0][2];
      d3 = datos_gasliquid[1][0];
      d4 = datos_gasliquid[1][1];
      d5 = datos_gasliquid[1][2];

      if((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) || (d3 === "" || d3 == null) && (d4 === "" || d4 == null) && (d5 === "" || d5 == null)) {
        validaciones.push({"mensaje": "Check your Gas-Oil data for Oil IPR, there's nothing there..."});
      }

      if(d1 != 0) {
        validaciones.push({"mensaje": "The first Gas-Oil krg value must be 0. Check your Relative Permeability Data."});
      }

      if(((d0 == 0) && (d1 == 0) && (d2 == 0)) || ((d3 == 0) && (d4 == 0) && (d5 == 0))) {
        validaciones.push({"mensaje": "The whole row can't be 0 at Gas-Oil data. Check your Relative Permeability Data."});
      }

      for (var i = 0; i < datos_gasliquid.length; i++) {
        d0 = datos_gasliquid[i][0];
        d1 = datos_gasliquid[i][1];
        d2 = datos_gasliquid[i][2];

        if((d0 == 0) && (d1 == 0 ) && (d2 == 0)) {
          if(d0 === "" && d1 === "" && d2 === "") {
            continue;
          } else {
            validaciones.push({"mensaje": "The whole row can't be 0 at Gas-Oil data. Check your Relative Permeability Data."});
          }
        }

        if((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "")) {
          if(d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "") {
            validaciones.push({"mensaje": "Check your Water-Oil data for Oil IPR, there's some incomplete data. Check your Relative Permeability Data."});
          }

          if(d1 < 0 || d2 < 0 || d0 < 0) {
            validaciones.push({"mensaje": "Your Gas-Oil data must be greather than 0. Check your Relative Permeability Data."});
          }

          if(d0 < 0 || d0 > 1) {
            validaciones.push({"mensaje": "Gas-Oil Sg must be between 0 and 1. Check your Relative Permeability Data."});
          }

          if(d1 < 0 || d1 > 1) {
            validaciones.push({"mensaje": "Gas-Oil krg must be between 0 and 1. Check your Relative Permeability Data."});
          }

          if(d2 < 0 || d2 > 1) {
            validaciones.push({"mensaje": "Gas-Oil krog must be between 0 and 1. Check your Relative Permeability Data."});
          }

          sg_gasoil_rev.push(d0);
          krg_gasoil_rev.push(d1);
          krog_gasoil_rev.push(d2);
        }
      }

      if(!is_asc(sg_gasoil_rev)) {
        validaciones.push({"mensaje": "Gas Oil sg data must be ascending sorted. Check your Relative Permeability Data."});
      }

      if(!is_asc(krg_gasoil_rev)) {
        validaciones.push({"mensaje": "Gas Oil krg data must be ascending sorted. Check your Relative Permeability Data."});
      }

      if(!is_desc(krog_gasoil_rev)) {
        validaciones.push({"mensaje": "Gas Oil kro must be descending sorted. Check your Relative Permeability Data."});
      } else {
        if(krog_gasoil_rev[krog_gasoil_rev.length-1]!=0) {
          validaciones.push({"mensaje": "The last Krog data must be 0. Check your Relative Permeability Data."});
        }
      }

      for (var i = 0; i < datos_gasliquid.length; i++) {
        d0 = datos_gasliquid[i][0];
        d1 = datos_gasliquid[i][1];
        d2 = datos_gasliquid[i][2];

        if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) {
          continue;
        } else {
          datos_gasliquid_aux.push(datos_gasliquid[i]);
        }
      }
      datos_gasliquid = datos_gasliquid_aux;
      /**** Fin validaciones para la tabla Gas-Oil cuando 'Tabular' esta activo ****/
    }

    /**** Inicio validaciones para la tabla PVT en la sección 'Fluid Properties' ****/
    d0 = datos_unidades_hidraulicas[0][0];
    d1 = datos_unidades_hidraulicas[0][1];
    d2 = datos_unidades_hidraulicas[0][2];
    d3 = datos_unidades_hidraulicas[0][3];
    d4 = datos_unidades_hidraulicas[1][0];
    d5 = datos_unidades_hidraulicas[1][1];
    d6 = datos_unidades_hidraulicas[1][2];
    d7 = datos_unidades_hidraulicas[1][3];

    if((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) && (d3 === "" || d3 == null) || (d4 === "" || d4 == null) && (d5 === "" || d5 == null) && (d6 === "" || d6 == null) && (d7 === "" || d7 == null)) {
      validaciones.push({"mensaje": "Check your PVT data for Oil IPR, there's nothing there..."});
    }

    for (var i = 0; i < datos_unidades_hidraulicas.length; i++) {
      d0 = datos_unidades_hidraulicas[i][0];
      d1 = datos_unidades_hidraulicas[i][1];
      d2 = datos_unidades_hidraulicas[i][2];
      d3 = datos_unidades_hidraulicas[i][3];
      if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!="") || (d3 != null && d3!="")) {
        if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2===""|| d3==null || d3==="" ) {
          validaciones.push({"mensaje": "Check your PVT data for Oil IPR, there's some incomplete rows."});
        }

        if(d1<0 || d2<0 || d0<0 || d3<0) {
          validaciones.push({"mensaje": "Your PVT data must be greather than 0."});
        }

        if(d0<14.7) {
          validaciones.push({"mensaje": "The Pression values at PVT table must be greather than 14.7."});
        }

        pressure_pvtoil_rev.push(d0);
      }
    }

    if(!is_asc(pressure_pvtoil_rev)) {
      validaciones.push({"mensaje": "The PVT pressures must be ascending sorted."});
    }

    if(!verifyPVToil(pressure_pvtoil_rev)) {
      validaciones.push({"mensaje": "Check your PVT pressures data."});
    }

    /* Checks Relative permeability */
    if(!$("#check_corey_rel_perm").is(":checked") && !$("#check_tabular_rel_perm").is(":checked")) {
      validaciones.push({"mensaje": "Please select an option: Use tabular data or Corey's Model."});
    }

    for (var i = 0; i < datos_unidades_hidraulicas.length; i++) {
      d0 = datos_unidades_hidraulicas[i][0];
      d1 = datos_unidades_hidraulicas[i][1];
      d2 = datos_unidades_hidraulicas[i][2];
      d3 = datos_unidades_hidraulicas[i][3];

      if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3==="" || d3 == null)) {
        continue;
      } else {
        datos_unidades_hidraulicas_aux.push(datos_unidades_hidraulicas[i]);
      }
    }
    datos_unidades_hidraulicas = datos_unidades_hidraulicas_aux;
    /**** Fin validaciones para la tabla PVT en la sección 'Fluid Properties' ****/

    /* Se envian los datos procesados a los respectivos inputs */
    $("#wateroil_hidden").val(JSON.stringify(datos_wateroil));
    $("#gasliquid_hidden").val(JSON.stringify(datos_gasliquid));
    $("#presiones_table").val(JSON.stringify(datos_unidades_hidraulicas));

    /**** Esta condición verifica si hay errores y se encarga de mostrarlos. ****/
    if (validate_fast && validaciones.length > 0) {
      var mensaje_alerta = "";
      $.each(validaciones, function(index, val) {
        mensaje_alerta += val.mensaje+"\n";
      });
      alert(mensaje_alerta);
      return false;
    }

    return true;
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 16/10/2018
  * Descripción: Esta función se encarga de realizar la respectivas validaciones cuando el tipo de
  * pozo es 'Producer' y el fluido es 'Dry Gas'.
  */
  function validateDryGas(validate_fast) {
    /* Para revisión de Gas - Oil */
    var pressure_pvtgas_rev = [];

    /* Para normalizar las tablas */
    var datos_pvt_ipr_g_aux = [];

    /* Para verificar el estado de las validaciones, contendra los mensajes de error*/
    var validaciones = [];

    /* Para fluid properties en dry gas existe una tabla, pvt */
    var datos_pvt_ipr_g = order_pvt($("#excel_table_pvt").handsontable('getData'));

    /**** Inicio validaciones para la tabla PVT en la sección fluid properties ****/

    d0 = datos_pvt_ipr_g[0][0];
    d1 = datos_pvt_ipr_g[0][1];
    d2 = datos_pvt_ipr_g[0][2];
    d3 = datos_pvt_ipr_g[1][0];
    d4 = datos_pvt_ipr_g[1][1];
    d5 = datos_pvt_ipr_g[1][2];

    if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3 ==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null)) {
      mensaje="Check your PVT data for Gas IPR, there's nothing there...";
    }

    for (var i = 0; i<datos_pvt_ipr_g.length; i++) {

      d0 = datos_pvt_ipr_g[i][0];
      d1 = datos_pvt_ipr_g[i][1];
      d2 = datos_pvt_ipr_g[i][2];

      if((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "")) {
        if(d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "") {
          validaciones.push({"mensaje": "Check your PVT data for Gas IPR, there's some incomplete data."});
        }

        if(d0 < 0 || d1 < 0 || d2 < 0) {
          validaciones.push({"mensaje": "Your PVT data data must be greather than 0."});
        }

        if(d0 < 14.7) {
          validaciones.push({"mensaje": "The minimum value for pressure is 14.7. Check your PVT Data."});
        }
        pressure_pvtgas_rev.push(d0);
      }
    }

    if(!is_asc(pressure_pvtgas_rev)) {
      validaciones.push({"mensaje": "The pressures must be ascending sorted. Check your PVT Data."});
    }

    if(datos_pvt_ipr_g.length > 0) { 
      for (var i = 0; i < datos_pvt_ipr_g.length; i++) {
        d0 = datos_pvt_ipr_g[i][0];
        d1 = datos_pvt_ipr_g[i][1];
        d2 = datos_pvt_ipr_g[i][2];

        if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) {
          continue;
        } else {
          datos_pvt_ipr_g_aux.push(datos_pvt_ipr_g[i]);
        }
      }
    }
    datos_pvt_ipr_g = datos_pvt_ipr_g_aux;

    /**** Fin validaciones para la tabla PVT en la sección fluid properties ****/

    /* Se envian los datos procesados a los respectivos inputs */
    $('#pvt_gas_ipr').val(JSON.stringify(datos_pvt_ipr_g));

    /**** Esta condición verifica si hay errores y se encarga de mostrarlos. ****/
    if (validate_fast && validaciones.length > 0) {
      var mensaje_alerta = "";
      $.each(validaciones, function(index, val) {
        mensaje_alerta += val.mensaje+"\n";
      });
      alert(mensaje_alerta);
      return false;
    }

    return true;
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 16/10/2018
  * Descripción: Esta función se encarga de realizar la respectivas validaciones cuando el tipo de
  * pozo es 'Producer' y el fluido es 'Condensate Gas'.
  */
  function validateCondensateGas(validate_fast) {
    /* Se definen estas variables para la revisión de los datos */
    /* Para revisión de Water - Oil */
    var sg_gasoil_rev = [];
    var krg_gasoil_rev = [];
    var krog_gasoil_rev = [];

    /* Para revisión de PVT */
    var pressure_pvt_cg_rev = [];

    /* Para normalizar las tablas */
    var go_cg_data_aux = [];
    var pvt_cg_data_aux = [];
    var dropout_cg_data_aux = [];

    /* Para verificar el estado de las validaciones, contendra los mensajes de error*/
    var validaciones = [];

    /* Para rock properties en condensate gas existe una sola tabla de gas-oil */
    var go_cg_data = $("#excelgasliquid_c_g").handsontable('getData');

    /* Para fluid properties en condensate gas existen dos tablas, pvt y Drop-out*/
    var pvt_cg_data = order_pvt($("#excel_table_pvt").handsontable('getData'));
    var dropout_cg_data = $("#excel_table_dod").handsontable('getData');

    /**** Inicio validaciones para la tabla Gas-Oil cuando 'Tabular' esta activo ****/
    d0 = go_cg_data[0][0];
    d1 = go_cg_data[0][1];
    d2 = go_cg_data[0][2];
    d3 = go_cg_data[1][0];
    d4 = go_cg_data[1][1];
    d5 = go_cg_data[1][2];

    if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null)) {
      validaciones.push({"mensaje": "Check your Gas-Oil data for Oil IPR, there's nothing there..."});
    }

    if(d1!=0) {
      validaciones.push({"mensaje": "The first Gas-Oil krg value must be 0. Check your Relative Permeability Data."});
    }

    if(((d0 ==0) && (d1==0) && (d2==0)) || ((d3==0) && (d4==0) && (d5==0))) {
      validaciones.push({"mensaje": "The whole row can't be 0 at Gas-Oil data. Check your Relative Permeability Data."});
    }

    for (var i = 0; i<go_cg_data.length; i++) {
      d0 = go_cg_data[i][0];
      d1 = go_cg_data[i][1];
      d2 = go_cg_data[i][2];

      if((d0 == 0) && (d1 == 0 ) && (d2 == 0)) {
        if(d0 === "" && d1 === "" && d2 === "") {
          continue;
        } else {
          validaciones.push({"mensaje": "The whole row can't be 0 at Gas-Oil data. Check your Relative Permeability Data."});
        }
      }

      if((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "")) {
        if(d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "") {
          validaciones.push({"mensaje": "Check your Gas-Oil data for Condensate Gas IPR, there's some incomplete data."});
        }

        if(d1<0 || d2<0 || d0<0) {
          validaciones.push({"mensaje": "Your Gas-Oil data must be greather than 0. Check your Relative Permeability Data."});
        }

        if(d0<0 || d0>1) {
          validaciones.push({"mensaje": "Gas-Oil Sg must be between 0 and 1. Check your Relative Permeability Data."});
        }

        if(d1<0 || d1>1) {
          validaciones.push({"mensaje": "Gas-Oil krg must be between 0 and 1. Check your Relative Permeability Data."});
        }

        if(d2<0 || d2>1) {
          validaciones.push({"mensaje": "Gas-Oil krog must be between 0 and 1. Check your Relative Permeability Data."});
        }

        sg_gasoil_rev.push(d0);
        krg_gasoil_rev.push(d1);
        krog_gasoil_rev.push(d2);
      }
    }

    if(!is_asc(sg_gasoil_rev)) {
      validaciones.push({"mensaje": "Gas Oil sg data must be ascending sorted. Check your Relative Permeability Data."});
    }

    if(!is_asc(krg_gasoil_rev)) {
      validaciones.push({"mensaje": "Gas Oil krg data must be ascending sorted. Check your Relative Permeability Data."});
    }

    if(!is_desc(krog_gasoil_rev)) {
      validaciones.push({"mensaje": "Gas Oil kro must be descending sorted. Check your Relative Permeability Data."});
    } else {
      if(krog_gasoil_rev[krog_gasoil_rev.length-1]!=0) {
        validaciones.push({"mensaje": "The last Krog data must be 0 at Gas Oil data. Check your Relative Permeability Data."});
      }
    }

    if(go_cg_data.length > 0) { 
      for(var i = 0; i<go_cg_data.length; i++) {
        d0 = go_cg_data[i][0];
        d1 = go_cg_data[i][1];
        d2 = go_cg_data[i][2];

        if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) {
          continue;
        } else {
          go_cg_data_aux.push(go_cg_data[i]);
        }
      }
    }
    go_cg_data = go_cg_data_aux;

    /**** Fin validaciones para la tabla Gas-Oil cuando 'Tabular' esta activo ****/

    /**** Inicio validaciones para la tabla PVT en la sección fluid properties ****/

    d0 = pvt_cg_data[0][0];
    d1 = pvt_cg_data[0][1];
    d2 = pvt_cg_data[0][2];
    d3 = pvt_cg_data[0][3];
    d4 = pvt_cg_data[0][4];
    d5 = pvt_cg_data[0][5];
    d6 = pvt_cg_data[0][6];
    d7 = pvt_cg_data[1][0];
    d8 = pvt_cg_data[1][1];
    d9 = pvt_cg_data[1][2];
    d10 = pvt_cg_data[1][3];
    d11 = pvt_cg_data[1][4];
    d12 = pvt_cg_data[1][5];
    d13 = pvt_cg_data[1][6];


    if((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) && (d3 === "" || d3 == null) || (d4 === "" || d4 == null) && (d5 === "" || d5 == null) && (d6 === "" || d6 == null) && (d7 === "" || d7 == null) && (d8 === "" || d8 == null) && (d9 === "" || d9 == null) && (d10 === "" || d10 == null) || (d11 === "" || d11 == null) && (d12 === "" || d12 == null) && (d13 === "" || d13 == null)) {
      validaciones.push({"mensaje": "Check your PVT data for Condensate Gas IPR, there's nothing there..."});
    }

    for (var i = 0; i<pvt_cg_data.length; i++) {
      d0 = pvt_cg_data[i][0];
      d1 = pvt_cg_data[i][1];
      d2 = pvt_cg_data[i][2];
      d3 = pvt_cg_data[i][3];
      d4 = pvt_cg_data[i][4];
      d5 = pvt_cg_data[i][5];
      d6 = pvt_cg_data[i][6];

      if((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "") || (d4 != null && d4 != "") || (d5 != null && d5 != "") || (d6 != null && d6 != "")) {
        if(d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === ""|| d3 == null || d3 === "" || d4 == null || d4 === "" || d5 == null || d5 === "" || d6 == null || d6 === "") {
          validaciones.push({"mensaje": "Check your PVT data for Condensate Gas IPR, there's some incomplete data."});
        }

        if(d1 < 0 || d2 < 0 || d0 < 0 || d3 < 0 || d4 < 0 || d5 < 0 || d6 < 0) {
          validaciones.push({"mensaje": "Your PVT data data must be greather than 0."});
        }

        if(d0 < 14.7) {
          validaciones.push({"mensaje": "The Pression values at PVT table must be greather than 14.7."});
        }

        pressure_pvt_cg_rev.push(d0);
      }
    }

    if(!is_asc(pressure_pvt_cg_rev)) {
      validaciones.push({"mensaje": "The PVT pressures must be ascending sorted."});
    }

    if(!verifyPVT_cg(pressure_pvt_cg_rev)) {
      validaciones.push({"mensaje": "Check your PVT pressure data. You must include the saturation pressure, a higher value, and a lower value."});
    }

    if(pvt_cg_data.length > 0) { 
      for(var i = 0; i<pvt_cg_data.length; i++) {
        d0 = pvt_cg_data[i][0];
        d1 = pvt_cg_data[i][1];
        d2 = pvt_cg_data[i][2];
        d3 = pvt_cg_data[i][3];
        d4 = pvt_cg_data[i][4];
        d5 = pvt_cg_data[i][5];
        d6 = pvt_cg_data[i][6];

        if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) {
          continue;
        } else {
          pvt_cg_data_aux.push(pvt_cg_data[i]);
        }
      }
    }
    pvt_cg_data = pvt_cg_data_aux;

    /**** Fin validaciones para la tabla PVT en la sección fluid properties ****/

    /**** Inicio validaciones para la tabla DROP-OUT en la sección fluid properties ****/

    d0 = dropout_cg_data[0][0];
    d1 = dropout_cg_data[0][1];
    d2 = dropout_cg_data[1][0];
    d3 = dropout_cg_data[2][1];

    if((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) && (d3 === "" || d3 == null)) {
      validaciones.push({"mensaje": "Check your Dropout data for Condensate Gas IPR, there's nothing there..."});
    }

    if(dropout_cg_data.length > 0) { 
      for(var i = 0; i<dropout_cg_data.length; i++) {
        d0 = dropout_cg_data[i][0];
        d1 = dropout_cg_data[i][1];

        if((d0 ==="" || d0 == null) && (d1==="" || d1 == null)) {
          continue;
        } else {
          dropout_cg_data_aux.push(dropout_cg_data[i]);
        }
      }
    }
    dropout_cg_data = dropout_cg_data_aux;
    /**** Fin validaciones para la tabla DROP-OUT en la sección fluid properties ****/

    /* Se envian los datos procesados a los respectivos inputs */
    $('#pvt_cg').val(JSON.stringify(pvt_cg_data));
    $('#gas_oil_kr_cg').val(JSON.stringify(go_cg_data));
    $('#dropout_cg').val(JSON.stringify(dropout_cg_data));

    /**** Esta condición verifica si hay errores y se encarga de mostrarlos. ****/
    if (validate_fast && validaciones.length > 0) {
      var mensaje_alerta = "";
      $.each(validaciones, function(index, val) {
        mensaje_alerta += val.mensaje+"\n";
      });
      alert(mensaje_alerta);
      return false;
    }

    return true;
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 16/10/2018
  * Descripción: Esta función se encarga de realizar la respectivas validaciones cuando el tipo de
  * pozo es 'Injector' y el fluido es 'Water'.
  */
  function validateWater(validate_fast) {
    return true;
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 16/10/2018
  * Descripción: Esta función se encarga de realizar la respectivas validaciones cuando el tipo de
  * pozo es 'Injector' y el fluido es 'Gas'.
  */
  function validateGas(validate_fast) {
    return validateDryGas(validate_fast);
  }

  /* Descripción: esta función valida inicialmente los datos de las tablas de los escenarios ipr de aceite, gas y gas condensado; normaliza también los valores con base en los check box escogidos; normaliza los valores de las tablas y prepara todo el set de datos que será enviado al controlador. Dentro de la función hay una serie de validaciones que se van acumulando y si al final, pasan todas las validaciones, se redirecciona al controlador, si no, se queda en la vista y le informa al usuario los errores encontrados. 
  Parámetros:
  validate: sirve como flag dentro de la función para saber si se procede o no al controlador. Por defecto llega como true.
  tipo: si es 1 es run si es dos es only save.
  */
  function enviar(validate,tipo) {
    var fluido = $('#fluido').val();
    var form = $('#form_scenario');

    /**** Validación de los inputs  ****/
    if (tipo == 1) {
      if(validateTabs(true)){
        alert('Check all fields marked in red.');
        return false;
      }
    }

    var formSub = true;
    if (fluido == 1) {
      formSub = validateBlackOil(tipo == 1 ? true : false);
    } else if (fluido == 2) {
      formSub = validateDryGas(tipo == 1 ? true : false);
    } else if (fluido == 3) {
      formSub = validateCondensateGas(tipo == 1 ? true : false);
    } else if (fluido == 4) {
      formSub = validateWater(tipo == 1 ? true : false);
    } else if (fluido == 5) {
      formSub = validateGas(tipo == 1 ? true : false);
    }

    if(formSub || tipo == 2){
      $("#modo_submit").val(tipo);
      form.submit();
      return true;
    }

    $("#modo_submit").val('');
    return true;
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 10/01/2018 14:15 PM
  * Descripción: Esta función se encarga de definir los campos a mostrar
  * en caso de ser 'Yes ( 1 )' o 'No ( 0 )' en la sección 'Basic Petrophysics' perteneciente a 'Rock properties'.
  */
  function defineRockProps(elemento,tipo)
  {
    validateTabs(false);
    /* Aqui se listan los grupos de input disponibles para ser modificados dependiendo de cada fluido. */
    var select_RP = $(elemento);
    var div_padre = select_RP.parents('.BasicP');

    var irp = div_padre.find('.data_first_input_rock'); /* Initial Reservoir Pressure */
    var apair = div_padre.find('.data_second_input_rock'); /* Absolute Permeability At Initial Reservoir Pressure */
    var np = div_padre.find('.data_third_input_rock'); /* Net Pay */
    var ap = div_padre.find('.data_fourth_input_rock'); /* Absolute Permeability */
    var pmvar = div_padre.find('.data_fifth_input_rock'); /* PM VAR */
    var pm = div_padre.find('.permeabilidad_module'); /* Permeabilidad */

    var url_calculate_permeabilidad = "{{ action('IPR2Controller@method_calcula_mod_permeabilidad') }}";

    if(select_RP.val() == 2) {

      irp.show();
      apair.show();
      np.show();
      ap.hide();
      pmvar.show();
      pm.show();

      $('[name="label_presion_yacimiento"]').text('Current Reservoir Pressure');

      $('.btn_calculate_final').on('click', function(event) {
        event.preventDefault();

        var id_input_pm = $('.b_calculate').attr('pm_id');
        var permeabilidad = $('#permeabilidad_modal').val();
        var porosidad = $('#porosidad_modal').val();
        var rock_type = $('#rock_type_modal').val();

        $.ajax({
          url: url_calculate_permeabilidad,
          data: {
            permeabilidad: permeabilidad,
            porosidad: porosidad,
            tipo_roca: rock_type
          },
        })
        .done(function(res) {
          if(typeof res == 'object') {
            $('#list-err-modal').html('');
            $.each(res, function(index, val) {
              $('#list-err-modal').prepend('<li>'+val[0]+'</li>');
            });

            $('.errors_modal_calc_permeability').show();
          } else {

            $('#'+id_input_pm).val(res);
            $('#'+id_input_pm).append('<input type="hidden" name="porosidad" id="porosidad" value="'+porosidad+'">');
            $('#'+id_input_pm).append('<input type="hidden" name="rock_type" id="rock_type" value="'+rock_type+'">');

            $('#modal_calculate').modal('hide');
            $('.errors_modal_calc_permeability').hide();
          }
        })
        .fail(function(res) {
          console.log("error",res);
        });

      });

    } else {

      irp.hide();
      apair.hide();
      np.show();
      ap.show();
      pmvar.hide();
      pm.hide();
      $('[name="label_presion_yacimiento"]').text('Reservoir Pressure');
    }
    validateTabs(false);
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 04/10/2018 08:58 PM
  * Descripción: Esta función se encarga de definir las opciones del select
  * Fluid, donde dependiendo del tipo de pozo (well_type), se definen las opciones de Fluid (fluido).
  */
  function defineWellType(elemento)
  {
    var well_type = $(elemento).val();
    var fluido_select = $('#fluido');
    
    fluido_select.html('');

    if(well_type == 1){
      @if($IPR->fluido == '1')
      fluido_select.append(new Option("Black Oil", "1", true, true));
      @else
      fluido_select.append(new Option("Black Oil", "1"));
      @endif
      @if($IPR->fluido == '2')
      fluido_select.append(new Option("Dry Gas", "2", true, true));
      @else
      fluido_select.append(new Option("Dry Gas", "2"));
      @endif
      @if($IPR->fluido == '3')
      fluido_select.append(new Option("Condensate Gas", "3", true, true));
      @else
      fluido_select.append(new Option("Condensate Gas", "3"));
      @endif
    } else if(well_type == 2) {
      @if($IPR->fluido == '4')
      fluido_select.append(new Option("Water", "4", true, true));
      @else
      fluido_select.append(new Option("Water", "4"));
      @endif
      @if($IPR->fluido == '5')
      fluido_select.append(new Option("Gas", "5", true, true));
      @else
      fluido_select.append(new Option("Gas", "5"));
      @endif
    }

    defineView(document.getElementById('fluido'),well_type);
    defineRockProps(document.getElementById('stress_sensitive_reservoir'),'oil');
    validateTabs(false);
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 04/10/2018 09:53 AM
  * Descripción: Esta función se encarga de definir los campos a mostrar en cada una de las
  * secciones dependiendo su tipo.
  */
  function defineView(fluido,well_type)
  {
    document.getElementById('loading').style.display = 'block';
    setTimeout(function() {
      /*  Se hace un clear en los divs de graphs */
      $('#graph_left').html('');

      /* Se hace un clear en los divs de graphs */
      $('#graph_right').html('');

      if(well_type == 1) {
        $('.relative_permeability_data_section').show();
        $('.presion_separacion_div').hide();

        if(fluido.value == "1"){
          oilBlack();
        } else if(fluido.value == "2"){
          dryGas();
        } else if(fluido.value == "3"){
          condensateGas();
        }
      } else if(well_type == 2) {
        $('.relative_permeability_data_section').hide();
        $('.presion_separacion_div').show();

        if(fluido.value == "4"){
          oilBlack();
          ajusteInjectorWater();
        } else if(fluido.value == "5"){
          dryGas();
          ajusteInjectorGas();
        }
      }

      activateActionButtons();
      validateTabs(false);
      document.getElementById('loading').style.display = 'none';
    }, 1000);
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 11/10/2018
  * Descripción: Esta función se encarga de definir los campos y tablas a mostrar siempre y cuando 
  * el tipo de pozo sea Producer y el tipo de fluido sea Oil Black
  */
  function oilBlack() {

    /* Aqui se listan los grupos de input disponibles para ser modificados dependiendo de cada fluido.*/
    var group_input_1 = $('.data_first_input');
    var group_input_2 = $('.data_second_input');
    var group_input_3 = $('.data_third_input');

    var group_input_1_rock = $('.data_first_input_rock');
    var group_input_2_rock = $('.data_second_input_rock');
    var group_input_3_rock = $('.data_third_input_rock');
    var group_input_4_rock = $('.data_fourth_input_rock');

    /* Check de fieldset (Permeability module) */
    var group_input_1_pm_rock = $('.data_fifth_input_rock');

    /* Checks de fieldset Relative Permeability Data Selection */
    var check_fieldset_tab = $('.input_check_use_tab');
    var check_fieldset_cor = $('.input_check_use_corey');

    /* start Gas/Oil End-Point Parameters */
    var group_input_1_corey = $('.data_first_input_corey');
    var group_input_2_corey = $('.data_second_input_corey');
    var group_input_3_corey = $('.data_third_input_corey');
    var group_input_4_corey = $('.data_fourth_input_corey');
    var group_input_5_corey = $('.data_fifth_input_corey');
    var group_input_6_corey = $('.data_sixth_input_corey');
    /* end Gas/Oil End-Point Parameters */

    /* start Oil/Water End-Point Parameters */
    var group_input_1_corey_ow = $('.data_first_input_corey_ow');
    var group_input_2_corey_ow = $('.data_second_input_corey_ow');
    var group_input_3_corey_ow = $('.data_third_input_corey_ow');
    var group_input_4_corey_ow = $('.data_fourth_input_corey_ow');
    var group_input_5_corey_ow = $('.data_fifth_input_corey_ow');
    var group_input_6_corey_ow = $('.data_sixth_input_corey_ow');
    /* end Oil/Water End-Point Parameters */

    /* start Fluid Properties */
    var input_first_fp = $('.data_first_input_fp');
    var input_second_fp = $('.data_second_input_fp');
    /* end Fluid Properties */

    // *****************************
    //  Inicia sección Production Data - Tipo: Oil Black
    // *****************************

    // Input tasa de flujo
    group_input_1.find("[name = 'label']").text('Oil Rate').attr('for','tasa_flujo');
    group_input_1.find(".input-group").addClass("{{$errors->has('tasa_flujo') ? 'has-error' : ''}}");
    group_input_1.find("input").attr('id','tasa_flujo').attr('name','tasa_flujo').attr('placeholder','Oil Rate').val({{ ($IPR->tasa_flujo) ? $IPR->tasa_flujo : '' }});
    group_input_1.find("[name = 'medida']").text('bbl/day');

    // Input presión de fondo
    group_input_2.find("[name = 'label']").text('BHP').attr('for','presion_fondo');
    group_input_2.find(".input-group").addClass("{{$errors->has('presion_fondo') ? 'has-error' : ''}}");
    group_input_2.find("input").attr('id','presion_fondo').attr('name','presion_fondo').attr('placeholder','BHP').val({{ ($IPR->presion_fondo) ? $IPR->presion_fondo : '' }});
    group_input_2.find("[name = 'medida']").text('psi');

    // Input bsw
    group_input_3.find("[name = 'label']").text('BSW').attr('for','bsw');
    group_input_3.find(".input-group").addClass("{{$errors->has('bsw') ? 'has-error' : ''}}");
    group_input_3.find("input").attr('id','bsw').attr('name','bsw').attr('placeholder','BSW').val({{ ($IPR->bsw) ? $IPR->bsw : '' }});
    group_input_3.find("[name = 'medida']").text('Fraction');
    group_input_3.show();

    // *****************************
    //  Fin sección Production Data - Tipo: Oil Black
    // *****************************

    // *****************************
    //  Inicia sección Rock Properties - Tipo: Oil Black
    // *****************************

    // Input Initial Reservoir Pressure
    group_input_1_rock.find("[name = 'label']").text('Initial Reservoir Pressure').attr('for','presion_inicial');
    group_input_1_rock.find(".input-group").addClass("{{$errors->has('presion_inicial') ? 'has-error' : ''}}");
    group_input_1_rock.find("input").attr('id','presion_inicial').attr('name','presion_inicial[]').attr('placeholder','Initial Reservoir Pressure');
    group_input_1_rock.find("[name = 'medida']").text('psi');

    // Input Absolute Permeability At Initial Reservoir Pressure
    group_input_2_rock.find("[name = 'label']").text('Absolute Permeability At Initial Reservoir Pressure').attr('for','permeabilidad_abs_ini');
    group_input_2_rock.find(".input-group").addClass("{{$errors->has('permeabilidad_abs_ini') ? 'has-error' : ''}}");
    group_input_2_rock.find("input").attr('id','permeabilidad_abs_ini').attr('name','permeabilidad_abs_ini[]').attr('placeholder','Absolute Permeability At Initial Reservoir Pressure');
    group_input_2_rock.find("[name = 'medida']").text('md');

    //  Input netpay
    group_input_3_rock.find("[name = 'label']").text('Net Pay').attr('for','espesor_reservorio');
    group_input_3_rock.find(".input-group").addClass("{{$errors->has('espesor_reservorio') ? 'has-error' : ''}}");
    group_input_3_rock.find("input").attr('id','espesor_reservorio').attr('name','espesor_reservorio[]').attr('placeholder','Net Pay');
    group_input_3_rock.find("[name = 'medida']").text('ft');

    // Input permeabilidad absoluta
    group_input_4_rock.find("[name = 'label']").text('Absolute Permeability').attr('for','permeabilidad');
    group_input_4_rock.find(".input-group").addClass("{{$errors->has('permeabilidad') ? 'has-error' : ''}}");
    group_input_4_rock.find("input").attr('id','permeabilidad').attr('name','permeabilidad[]').attr('placeholder','Absolute Permeability');
    group_input_4_rock.find("[name = 'medida']").text('md');

    // Input modulo de permeabilidad
    group_input_1_pm_rock.find("[name = 'label']").text('Permeability Module').attr('for','modulo_permeabilidad');
    group_input_1_pm_rock.find(".input-group").addClass("{{$errors->has('modulo_permeabilidad') ? 'has-error' : ''}}");
    group_input_1_pm_rock.find("input").attr('id','modulo_permeabilidad').attr('name','modulo_permeabilidad[]').attr('placeholder','Absolute Permeability');
    group_input_1_pm_rock.find("[name = 'medida']").html('psi<sup>-1</sup>');

    // Boton que permite abrir el modal para calcular el modulo de permeabilidad
    $('.b_calculate').attr('pm_id', 'modulo_permeabilidad');


    /* *********Sección de creación y población de tablas ********* */
    $('.rpds_right').show();
    $('.fieldset_tab').children('legend').show();
    $('.fieldset_corey').show();
    $('.use_permeability_tables').show();


    /* Water-Oil - Oil - table */
    $('.rpds_left').find('legend').text('Water-Oil');
    $('.rpds_left').find('fieldset').find('.handsontable:first').attr('id', 'excelwateroil');

    var datos = {!! !empty($water_table) ? $water_table : '{}' !!};

    $excelwateroil = $('#excelwateroil');
    $excelwateroil.handsontable({
      data: datos,
      colWidths: [80, 80, 80] ,
      rowHeaders: true, 
      columns: [
      {title:"Sw", data: 0,type: 'numeric', format: '0[.]0000000'},
      {title:"Krw",data: 1,type: 'numeric', format: '0[.]0000000'},
      {title:"Kro", data: 2,type: 'numeric', format: '0[.]0000000'}
      ],
      startRows: 6,
      minSpareRows: 2,
      contextMenu: true,
      afterChange: function( changes, source ) {
        validateTabs(false);
      }
    });

    /* Gas Oil - Table */
    $('.rpds_right').find('legend').text('Gas-Oil');
    $('.rpds_right').find('fieldset').find('.handsontable:first').attr('id', 'excelgasliquid');

    var datos = {!! !empty($gas_table) ? $gas_table : '{}' !!};
    $excelgasliquid = $('#excelgasliquid');
    $excelgasliquid.handsontable({
      data: datos,
      colWidths: [80, 80, 80] ,
      rowHeaders: true, 
      columns: [
      {title:"Sg", data: 0,type: 'numeric', format: '0[.]0000000'},
      {title:"Krg",data: 1,type: 'numeric', format: '0[.]0000000'},
      {title:"Krog", data: 2,type: 'numeric', format: '0[.]0000000'}
      ],
      startRows: 6,
      minSpareRows: 2,
      contextMenu: true,
      afterChange: function( changes, source ) {
        validateTabs(false);
      }
    });

    /* Se agregan los enventos para los botones */
    setTimeout(function() {
      $('.btn_plot_pt_left').attr('onclick','plot_waterOil()');
      $('.btn_plot_pt_right').attr('onclick','plot_gasOil()');
    }, 10);

    /* Check calculate permeability module */
    check_fieldset_tab
    .val('check_tabular_rel_perm')
    .attr('id','check_tabular_rel_perm')
    .attr('name','check_tabular_rel_perm')
    .change(function(event) {
      event.preventDefault();
      var checkbox = $(this);
      if(checkbox.is(":checked"))
      {
        $("#check_corey_rel_perm").attr('checked', false);
        $(".use_permeability_tables").show();
        $(".use_corey_model").hide();
      }
      validateTabs(false);
    }).change();

    /* START Gas/Oil End-Point Parameters */

    // Kro (Sgc) - Corey rock properties
    group_input_1_corey.find("[name = 'label']").text('Kro (Sgc) ').attr('for','end_point_kr_aceite_gas');
    group_input_1_corey.find(".input-group").addClass("{{$errors->has('end_point_kr_aceite_gas') ? 'has-error' : ''}}");
    group_input_1_corey.find("input").attr('id','end_point_kr_aceite_gas').attr('name','end_point_kr_aceite_gas').attr('placeholder','Kro (Sgc) ').val({{ ($IPR->end_point_kr_aceite_gas) ? $IPR->end_point_kr_aceite_gas : '' }});
    group_input_1_corey.find("[name = 'medida']").html('Fraction');

    // Sgc - Corey rock properties
    group_input_2_corey.find("[name = 'label']").text('Sgc ').attr('for','saturacion_gas_crit');
    group_input_2_corey.find(".input-group").addClass("{{$errors->has('saturacion_gas_crit') ? 'has-error' : ''}}");
    group_input_2_corey.find("input").attr('id','saturacion_gas_crit').attr('name','saturacion_gas_crit').attr('placeholder','Sgc ').val({{ ($IPR->saturacion_gas_crit) ? $IPR->saturacion_gas_crit : '' }});
    group_input_2_corey.find("[name = 'medida']").html('Fraction');

    // Krg (Sorg) - Corey rock properties
    group_input_3_corey.find("[name = 'label']").text('Krg (Sorg) ').attr('for','end_point_kr_gas');
    group_input_3_corey.find(".input-group").addClass("{{$errors->has('end_point_kr_gas') ? 'has-error' : ''}}");
    group_input_3_corey.find("input").attr('id','end_point_kr_gas').attr('name','end_point_kr_gas').attr('placeholder','Krg (Sorg) ').val({{ ($IPR->end_point_kr_gas) ? $IPR->end_point_kr_gas : '' }});
    group_input_3_corey.find("[name = 'medida']").html('Fraction');

    // Sorg - Corey rock properties
    group_input_4_corey.find("[name = 'label']").text('Sorg ').attr('for','saturacion_aceite_irred_gas');
    group_input_4_corey.find(".input-group").addClass("{{$errors->has('saturacion_aceite_irred_gas') ? 'has-error' : ''}}");
    group_input_4_corey.find("input").attr('id','saturacion_aceite_irred_gas').attr('name','saturacion_aceite_irred_gas').attr('placeholder','Sorg ').val({{ ($IPR->saturacion_aceite_irred_gas) ? $IPR->saturacion_aceite_irred_gas : '' }});
    group_input_4_corey.find("[name = 'medida']").html('Fraction');

    // Corey Exponent Oil/Gas - Corey rock properties
    group_input_5_corey.find("[name = 'label']").text('Corey Exponent Oil/Gas ').attr('for','exponente_corey_aceite_gas');
    group_input_5_corey.find(".input-group").addClass("{{$errors->has('exponente_corey_aceite_gas') ? 'has-error' : ''}}");
    group_input_5_corey.find("input").attr('id','exponente_corey_aceite_gas').attr('name','exponente_corey_aceite_gas').attr('placeholder','Corey Exponent Oil/Gas ').val({{ ($IPR->exponente_corey_aceite_gas) ? $IPR->exponente_corey_aceite_gas : '' }});
    group_input_5_corey.find("[name = 'medida']").html('Fraction');

    // Corey Exponent Gas - Corey rock properties
    group_input_6_corey.find("[name = 'label']").text('Corey Exponent Gas ').attr('for','exponente_corey_gas');
    group_input_6_corey.find(".input-group").addClass("{{$errors->has('exponente_corey_gas') ? 'has-error' : ''}}");
    group_input_6_corey.find("input").attr('id','exponente_corey_gas').attr('name','exponente_corey_gas').attr('placeholder','Corey Exponent Gas ').val({{ ($IPR->exponente_corey_gas) ? $IPR->exponente_corey_gas : '' }});
    group_input_6_corey.find("[name = 'medida']").html('Fraction');

    /* END Gas/Oil End-Point Parameters */

    /* START Oil/Water End-Point Parameters */

    // Kro (Sgc) - Corey rock properties
    group_input_1_corey_ow.find("[name = 'label']").text('Kro (Swi) ').attr('for','end_point_kr_petroleo');
    group_input_1_corey_ow.find(".input-group").addClass("{{$errors->has('end_point_kr_petroleo') ? 'has-error' : ''}}");
    group_input_1_corey_ow.find("input").attr('id','end_point_kr_petroleo').attr('name','end_point_kr_petroleo').attr('placeholder','Kro (Swi) ').val({{ ($IPR->end_point_kr_petroleo) ? $IPR->end_point_kr_petroleo : '' }});
    group_input_1_corey_ow.find("[name = 'medida']").html('Fraction');

    // Sgc - Corey rock properties
    group_input_2_corey_ow.find("[name = 'label']").text('Swi ').attr('for','saturacion_agua_irred');
    group_input_2_corey_ow.find(".input-group").addClass("{{$errors->has('saturacion_agua_irred') ? 'has-error' : ''}}");
    group_input_2_corey_ow.find("input").attr('id','saturacion_agua_irred').attr('name','saturacion_agua_irred').attr('placeholder','Swi ').val({{ ($IPR->saturacion_agua_irred) ? $IPR->saturacion_agua_irred : '' }});
    group_input_2_corey_ow.find("[name = 'medida']").html('Fraction');

    // Krg (Sorg) - Corey rock properties
    group_input_3_corey_ow.find("[name = 'label']").text('Krw (Sor) ').attr('for','end_point_kr_agua');
    group_input_3_corey_ow.find(".input-group").addClass("{{$errors->has('end_point_kr_agua') ? 'has-error' : ''}}");
    group_input_3_corey_ow.find("input").attr('id','end_point_kr_agua').attr('name','end_point_kr_agua').attr('placeholder','Krw (Sor) ').val({{ ($IPR->end_point_kr_agua) ? $IPR->end_point_kr_agua : '' }});
    group_input_3_corey_ow.find("[name = 'medida']").html('Fraction');

    // Sorg - Corey rock properties
    group_input_4_corey_ow.find("[name = 'label']").text('Sor ').attr('for','saturacion_aceite_irred');
    group_input_4_corey_ow.find(".input-group").addClass("{{$errors->has('saturacion_aceite_irred') ? 'has-error' : ''}}");
    group_input_4_corey_ow.find("input").attr('id','saturacion_aceite_irred').attr('name','saturacion_aceite_irred').attr('placeholder','Sor ').val({{ ($IPR->saturacion_aceite_irred) ? $IPR->saturacion_aceite_irred : '' }});
    group_input_4_corey_ow.find("[name = 'medida']").html('Fraction');

    // Corey Exponent Oil/Gas - Corey rock properties
    group_input_5_corey_ow.find("[name = 'label']").text('Corey Exponent Oil ').attr('for','exponente_corey_petroleo');
    group_input_5_corey_ow.find(".input-group").addClass("{{$errors->has('exponente_corey_petroleo') ? 'has-error' : ''}}");
    group_input_5_corey_ow.find("input").attr('id','exponente_corey_petroleo').attr('name','exponente_corey_petroleo').attr('placeholder','Corey Exponent Oil ').val({{ ($IPR->exponente_corey_petroleo) ? $IPR->exponente_corey_petroleo : '' }});
    group_input_5_corey_ow.find("[name = 'medida']").html('Fraction');

    // Corey Exponent Gas - Corey rock properties
    group_input_6_corey_ow.find("[name = 'label']").text('Corey Exponent Water ').attr('for','exponente_corey_agua');
    group_input_6_corey_ow.find(".input-group").addClass("{{$errors->has('exponente_corey_agua') ? 'has-error' : ''}}");
    group_input_6_corey_ow.find("input").attr('id','exponente_corey_agua').attr('name','exponente_corey_agua').attr('placeholder','Corey Exponent Gas ').val({{ ($IPR->exponente_corey_agua) ? $IPR->exponente_corey_agua : '' }});
    group_input_6_corey_ow.find("[name = 'medida']").html('Fraction');

    /* END Oil/Water End-Point Parameters */


    /* Check calculate permeability module */
    check_fieldset_cor
    .val('check_corey_rel_perm')
    .attr('id','check_corey_rel_perm')
    .attr('name','check_corey_rel_perm')
    .change(function(event) {
      event.preventDefault();
      var checkbox = $(this);
      if(checkbox.is(":checked"))
      {
        $("#check_tabular_rel_perm").attr('checked', false);
        $(".use_permeability_tables").hide();
        $(".use_corey_model").show();
      }
      validateTabs(false);
    }).change();

    /***************************** Fin sección Rock Properties - Tipo: Oil Black *****************************/

    /* Water volumetric factor */
    input_first_fp.find("[name = 'label']").text('Saturation Pressure').attr('for','presion_saturacion');
    input_first_fp.find(".input-group").addClass("{{$errors->has('presion_saturacion') ? 'has-error' : ''}}");
    input_first_fp.find(".input-group-btn").remove();
    input_first_fp.find(".input-group").prepend('<span class="input-group-btn"> <button type="button" class="btn btn-default button-advisor"><span class="glyphicon glyphicon-info-sign"></span></button> </span>');
    refreshAdvisorButtons();
    input_first_fp.find("input").attr('id','presion_saturacion').attr('name','presion_saturacion').attr('placeholder','Saturation Pressure').val({{ (is_null($IPR->saturation_pressure) ? "" : round($IPR->saturation_pressure, 15)) }});
    input_first_fp.find("[name = 'medida']").text('psi');

    /* Water Viscosity */
    input_second_fp.find("[name = 'label']").text('').attr('for','water_viscosity');
    input_second_fp.find(".input-group").removeClass("{{$errors->has('water_viscosity') ? 'has-error' : ''}}");
    input_second_fp.find(".input-group-btn").remove();
    input_second_fp.find("input").attr('id','water_viscosity').attr('name','water_viscosity').attr('placeholder','').val({{ ($IPR->water_viscosity) ? $IPR->water_viscosity : '' }});
    input_second_fp.find("[name = 'medida']").text('');
    input_second_fp.hide();

    // Gas Oil Table - Oil Black
    $('.rpds_right').find('legend').text('Gas-Oil');
    $('.rpds_right').find('fieldset').find('.handsontable:first').attr('id', 'excelgasliquid');


    //Pvt - Oil Black
    var hot = $('#excel_table_pvt');
    hot.handsontable({
      colWidths: [188, 188, 200, 188],
      rowHeaders: true, 
      columns: [
      {title:"Pressure [psi]", data: 0,type: 'numeric', format: '0[.]0000000'},
      {title:"Oil Viscosity [cp]",data: 1,type: 'numeric', format: '0[.]0000000'},
      {title:"Oil Volumetric Factor [RB/STB]",data: 2,type: 'numeric', format: '0[.]0000000'},
      {title:"Water Viscosity [cp]",data: 3,type: 'numeric', format: '0[.]0000000'}
      ],
      startRows: 6,
      minSpareRows: 2,
      contextMenu: true,
      afterChange: function( changes, source ) {
        validateTabs(false);
      }
    });

    @if(!empty($tabla))
    var datos = {!! !empty($tabla) ? $tabla : '{}' !!};
    hot.handsontable({
      data: datos,
    });
    @endif

    setTimeout(function() {
      hot.handsontable('render');
    }, 1000);

    curr_hot = hot;

    $('.pvt_data').show();
    $('.rpds_up').find('legend').text('PVT Data');
    $('#graph_up').html('');
    $('#graph_oil_viscosity').html('');
    $('#graph_oil_volumetric_factor').html('');
    $('#graph_water_viscosity').html('');
    $('#graph_gas_viscosity').html('');
    $('#graph_gas_compressibility_factor').html('');
    $('#graph_bo').html('');
    $('#graph_uo').html('');
    $('#graph_rs').html('');
    $('#graph_bg').html('');
    $('#graph_ug').html('');
    $('#graph_ogratio').html('');

    $('.rpds_up').find('fieldset').find('.handsontable:first').attr('id', 'excel_table_pvt');
    $('.btn_plot_fp_first').attr('onclick','plot_pvt_oil()');

    $('.drop_out_data').hide();
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 11/10/2018
  * Descripción: Esta función se encarga de definir los campos y tablas a mostrar siempre y cuando 
  * el tipo de pozo sea Producer y el tipo de fluido sea Dry Gas
  */
  function dryGas() {

    /* Aqui se listan los grupos de input disponibles para ser modificados dependiendo de cada fluido.*/
    var group_input_1 = $('.data_first_input');
    var group_input_2 = $('.data_second_input');
    var group_input_3 = $('.data_third_input');

    var group_input_1_rock = $('.data_first_input_rock');
    var group_input_2_rock = $('.data_second_input_rock');
    var group_input_3_rock = $('.data_third_input_rock');
    var group_input_4_rock = $('.data_fourth_input_rock');

    /* Check de fieldset (Permeability module) */
    var group_input_1_pm_rock = $('.data_fifth_input_rock');

    /* Checks de fieldset Relative Permeability Data Selection */
    var check_fieldset_tab = $('.input_check_use_tab');
    var check_fieldset_cor = $('.input_check_use_corey');

    /* start Gas/Oil End-Point Parameters */
    var group_input_1_corey = $('.data_first_input_corey');
    var group_input_2_corey = $('.data_second_input_corey');
    var group_input_3_corey = $('.data_third_input_corey');
    var group_input_4_corey = $('.data_fourth_input_corey');
    var group_input_5_corey = $('.data_fifth_input_corey');
    var group_input_6_corey = $('.data_sixth_input_corey');
    /* end Gas/Oil End-Point Parameters */

    /* start Oil/Water End-Point Parameters */
    var group_input_1_corey_ow = $('.data_first_input_corey_ow');
    var group_input_2_corey_ow = $('.data_second_input_corey_ow');
    var group_input_3_corey_ow = $('.data_third_input_corey_ow');
    var group_input_4_corey_ow = $('.data_fourth_input_corey_ow');
    var group_input_5_corey_ow = $('.data_fifth_input_corey_ow');
    var group_input_6_corey_ow = $('.data_sixth_input_corey_ow');
    /* end Oil/Water End-Point Parameters */

    /* start Fluid Properties */
    var input_first_fp = $('.data_first_input_fp');
    var input_second_fp = $('.data_second_input_fp');
    /* end Fluid Properties */

    // *****************************
    //  Inicia sección Production Data
    // *****************************

    // Input tasa de flujo
    group_input_1.find("[name = 'label']").text('Gas Rate').attr('for','gas_rate_g');
    group_input_1.find(".input-group").addClass("{{$errors->has('gas_rate_g') ? 'has-error' : ''}}");
    group_input_1.find("input").attr('id','gas_rate_g').attr('name','gas_rate_g').attr('placeholder','Gas Rate').val({{ isset($IPR->gas_rate_g) ? $IPR->gas_rate_g : '' }});
    group_input_1.find("[name = 'medida']").text('MMscf/day');

    // Input presión de fondo
    group_input_2.find("[name = 'label']").text('BHP').attr('for','bhp_g');
    group_input_2.find(".input-group").addClass("{{$errors->has('bhp_g') ? 'has-error' : ''}}");
    group_input_2.find("input").attr('id','bhp_g').attr('name','bhp_g').attr('placeholder','BHP').val({{ isset($IPR->bhp_g) ? $IPR->bhp_g : '' }});
    group_input_2.find("[name = 'medida']").text('psi');

    // Input bsw
    group_input_3.find("[name = 'label']").text('').attr('for','');
    group_input_3.find(".input-group").removeClass("{{$errors->has('bsw') ? 'has-error' : ''}}");
    group_input_3.find("input").attr('id','').attr('name','').attr('placeholder','').val('');
    group_input_3.find("[name = 'medida']").text('');
    group_input_3.hide();

    // *****************************
    //  Inicia sección Production Data
    // *****************************

    // *****************************
    // Inicio sección Rock Properties
    // *****************************

    // Input Initial Reservoir Pressure
    group_input_1_rock.find("[name = 'label']").text('Initial Reservoir Pressure').attr('for','init_res_press_text_g');
    group_input_1_rock.find(".input-group").addClass("{{$errors->has('init_res_press_text_g') ? 'has-error' : ''}}");
    group_input_1_rock.find("input").attr('id','init_res_press_text_g').attr('name','init_res_press_text_g[]').attr('placeholder','Initial Reservoir Pressure').val({{ isset($IPR->init_res_press_text_g) ? $IPR->init_res_press_text_g : '' }});
    group_input_1_rock.find("[name = 'medida']").text('psi');

    // Input Absolute Permeability At Initial Reservoir Pressure
    group_input_2_rock.find("[name = 'label']").text('Absolute Permeability At Initial Reservoir Pressure').attr('for','abs_perm_init_text_g');
    group_input_2_rock.find(".input-group").addClass("{{$errors->has('abs_perm_init_text_g') ? 'has-error' : ''}}");
    group_input_2_rock.find("input").attr('id','abs_perm_init_text_g').attr('name','abs_perm_init_text_g[]').attr('placeholder','Absolute Permeability At Initial Reservoir Pressure').val({{ isset($IPR->abs_perm_init_text_g) ? $IPR->abs_perm_init_text_g : '' }});
    group_input_2_rock.find("[name = 'medida']").text('md');

    // Input netpay
    group_input_3_rock.find("[name = 'label']").text('Net Pay').attr('for','net_pay_text_g');
    group_input_3_rock.find(".input-group").addClass("{{$errors->has('net_pay_text_g') ? 'has-error' : ''}}");
    group_input_3_rock.find("input").attr('id','net_pay_text_g').attr('name','net_pay_text_g[]').attr('placeholder','Net Pay').val({{ isset($IPR->net_pay_text_g) ? $IPR->net_pay_text_g : '' }});
    group_input_3_rock.find("[name = 'medida']").text('ft');

    // Input permeabilidad absoluta
    group_input_4_rock.find("[name = 'label']").text('Absolute Permeability').attr('for','abs_perm_text_g');
    group_input_4_rock.find(".input-group").addClass("{{$errors->has('abs_perm_text_g') ? 'has-error' : ''}}");
    group_input_4_rock.find("input").attr('id','abs_perm_text_g').attr('name','abs_perm_text_g[]').attr('placeholder','Absolute Permeability').val({{ isset($IPR->abs_perm_text_g) ? $IPR->abs_perm_text_g : '' }});;
    group_input_4_rock.find("[name = 'medida']").text('md');

    // Input modulo de permeabilidad
    group_input_1_pm_rock.find("[name = 'label']").text('Permeability Module').attr('for','permeability_module_text');
    group_input_1_pm_rock.find(".input-group").addClass("{{$errors->has('permeability_module_text') ? 'has-error' : ''}}");
    group_input_1_pm_rock.find("input").attr('id','permeability_module_text').attr('name','permeability_module_text[]').attr('placeholder','Absolute Permeability').val({{ isset($IPR->permeability_module_text) ? $IPR->permeability_module_text : '' }});;
    group_input_1_pm_rock.find("[name = 'medida']").html('psi<sup>-1</sup>');

    // Boton que permite abrir el modal para calcular el modulo de permeabilidad
    $('.b_calculate').attr('pm_id', 'permeability_module_text');

    /* Se oculta relative_permeability_data_section, sección donde estan las tablas */
    $('.relative_permeability_data_section').hide();


    /* Water volumetric factor */
    input_first_fp.find("[name = 'label']").text('Temperature').attr('for','temperature_text_g');
    input_first_fp.find(".input-group").addClass("{{$errors->has('temperature_text_g') ? 'has-error' : ''}}");
    input_first_fp.find(".input-group-btn").remove();
    input_first_fp.find("input").attr('id','temperature_text_g').attr('name','temperature_text_g').attr('placeholder','Temperature').val({{ isset($IPR->temperature_text_g) ? $IPR->temperature_text_g : '' }});;;
    input_first_fp.find("[name = 'medida']").text('F');

    /* Water Viscosity */
    input_second_fp.find("[name = 'label']").text('').attr('for','water_viscosity');
    input_second_fp.find(".input-group").removeClass("{{$errors->has('water_viscosity') ? 'has-error' : ''}}");
    input_second_fp.find(".input-group-btn").remove();
    input_second_fp.find("input").attr('id','water_viscosity').attr('name','water_viscosity').attr('placeholder','').val('');
    input_second_fp.find("[name = 'medida']").text('');
    input_second_fp.hide();

    $('.rpds_up').find('legend').text('PVT Data');
    $('#graph_up').html('');
    $('#graph_oil_viscosity').html('');
    $('#graph_oil_volumetric_factor').html('');
    $('#graph_water_viscosity').html('');
    $('#graph_gas_viscosity').html('');
    $('#graph_gas_compressibility_factor').html('');
    $('#graph_bo').html('');
    $('#graph_uo').html('');
    $('#graph_rs').html('');
    $('#graph_bg').html('');
    $('#graph_ug').html('');
    $('#graph_ogratio').html('');

    $('.rpds_up').find('fieldset').find('.handsontable:first').attr('id', 'excel_table_pvt');
    $('.btn_plot_fp_first').attr('onclick','plot_pvt_gas()');


    /* Pvt Gas */
    var datos = {!! !empty($pvt_gas_table) ? $pvt_gas_table : '{}' !!};

    $excel_tabular_pvt_fluid = $('#excel_table_pvt');
    $excel_tabular_pvt_fluid.handsontable({
      data: datos, 
      colWidths: [255, 255, 254] ,
      rowHeaders: true, 
      columns: [

      {title:"Pressure [psi] ", data: 0,type: 'numeric', format: '0[.]0000000'},
      {title:"Gas Visosity [cp] ",data: 1,type: 'numeric', format: '0[.]0000000'},
      {title:"Gas Compressibility Factor", data: 2,type: 'numeric', format: '0[.]0000000'}
      ],
      startRows: 6,
      minSpareRows: 2,
      contextMenu: true,
      afterChange: function( changes, source ) {
        validateTabs(false);
      }
    });

    $('.pvt_data').show();
    $('.rpds_down').find('legend').text('Drop-Out Data');
    $('.rpds_down').find('fieldset').find('.handsontable:first').attr('id', 'excel_table_dod');
    $('.btn_plot_fp_second').attr('onclick','plot_drop_out_c_g()');  
    $('#graph_down').html('');

    $('.drop_out_data').hide();

    /***************************** Fin sección Rock Properties - DRY GAS *****************************/
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 11/10/2018
  * Descripción: Esta función se encarga de definir los campos y tablas a mostrar siempre y cuando 
  * el tipo de pozo sea Producer y el tipo de fluido sea Condensate Gas
  */
  function condensateGas() {

    /* Aqui se listan los grupos de input disponibles para ser modificados dependiendo de cada fluido.*/
    var group_input_1 = $('.data_first_input');
    var group_input_2 = $('.data_second_input');
    var group_input_3 = $('.data_third_input');

    var group_input_1_rock = $('.data_first_input_rock');
    var group_input_2_rock = $('.data_second_input_rock');
    var group_input_3_rock = $('.data_third_input_rock');
    var group_input_4_rock = $('.data_fourth_input_rock');

    /* Check de fieldset (Permeability module) */
    var group_input_1_pm_rock = $('.data_fifth_input_rock');

    /* Checks de fieldset Relative Permeability Data Selection */
    var check_fieldset_tab = $('.input_check_use_tab');
    var check_fieldset_cor = $('.input_check_use_corey');

    /* start Gas/Oil End-Point Parameters */
    var group_input_1_corey = $('.data_first_input_corey');
    var group_input_2_corey = $('.data_second_input_corey');
    var group_input_3_corey = $('.data_third_input_corey');
    var group_input_4_corey = $('.data_fourth_input_corey');
    var group_input_5_corey = $('.data_fifth_input_corey');
    var group_input_6_corey = $('.data_sixth_input_corey');
    /* end Gas/Oil End-Point Parameters */

    /* start Oil/Water End-Point Parameters */
    var group_input_1_corey_ow = $('.data_first_input_corey_ow');
    var group_input_2_corey_ow = $('.data_second_input_corey_ow');
    var group_input_3_corey_ow = $('.data_third_input_corey_ow');
    var group_input_4_corey_ow = $('.data_fourth_input_corey_ow');
    var group_input_5_corey_ow = $('.data_fifth_input_corey_ow');
    var group_input_6_corey_ow = $('.data_sixth_input_corey_ow');
    /* end Oil/Water End-Point Parameters */

    /* start Fluid Properties */
    var input_first_fp = $('.data_first_input_fp');
    var input_second_fp = $('.data_second_input_fp');
    /* end Fluid Properties */

    /***************************** Inicio sección Production Data ******************************/

    /* Input tasa de flujo */
    group_input_1.find("[name = 'label']").text('Gas Rate').attr('for','gas_rate_c_g');
    group_input_1.find(".input-group").addClass("{{$errors->has('gas_rate_c_g') ? 'has-error' : ''}}");
    group_input_1.find("input").attr('id','gas_rate_c_g').attr('name','gas_rate_c_g').attr('placeholder','Gas Rate').val('{{ isset($IPR->gas_rate_c_g) ? $IPR->gas_rate_c_g : '' }}');
    group_input_1.find("[name = 'medida']").text('MMscf/day');

    /* Input presión de fondo */
    group_input_2.find("[name = 'label']").text('BHP').attr('for','bhp_c_g');
    group_input_2.find(".input-group").addClass("{{$errors->has('bhp_c_g') ? 'has-error' : ''}}");
    group_input_2.find("input").attr('id','bhp_c_g').attr('name','bhp_c_g').attr('placeholder','BHP').val('{{ isset($IPR->bhp_c_g) ? $IPR->bhp_c_g : '' }}');
    group_input_2.find("[name = 'medida']").text('psi');

    /* Input bsw */
    group_input_3.find("[name = 'label']").text('').attr('for','');
    group_input_3.find(".input-group").removeClass("{{$errors->has('bsw') ? 'has-error' : ''}}").val('{{ isset($IPR->bsw) ? $IPR->bsw : '' }}');
    group_input_3.find("input").attr('id','').attr('name','').attr('placeholder','');
    group_input_3.find("[name = 'medida']").text('');
    group_input_3.hide();

    /***************************** Fin sección Production Data *****************************/

    /***************************** Inicio sección Rock Properties ****************************/

    /* Input Initial Reservoir Pressure */
    group_input_1_rock.find("[name = 'label']").text('Initial Reservoir Pressure').attr('for','presion_inicial_c_g');
    group_input_1_rock.find(".input-group").addClass("{{$errors->has('presion_inicial_c_g') ? 'has-error' : ''}}");
    group_input_1_rock.find("input").attr('id','presion_inicial_c_g').attr('name','presion_inicial_c_g[]').attr('placeholder','Initial Reservoir Pressure');
    group_input_1_rock.find("[name = 'medida']").text('psi');

    /* Input Absolute Permeability At Initial Reservoir Pressure */
    group_input_2_rock.find("[name = 'label']").text('Absolute Permeability At Initial Reservoir Pressure').attr('for','permeabilidad_abs_ini_c_g');
    group_input_2_rock.find(".input-group").addClass("{{$errors->has('permeabilidad_abs_ini_c_g') ? 'has-error' : ''}}");
    group_input_2_rock.find("input").attr('id','permeabilidad_abs_ini_c_g').attr('name','permeabilidad_abs_ini_c_g[]').attr('placeholder','Absolute Permeability At Initial Reservoir Pressure');
    group_input_2_rock.find("[name = 'medida']").text('md');

    /* Input netpay */
    group_input_3_rock.find("[name = 'label']").text('Net Pay').attr('for','espesor_reservorio_c_g');
    group_input_3_rock.find(".input-group").addClass("{{$errors->has('espesor_reservorio_c_g') ? 'has-error' : ''}}");
    group_input_3_rock.find("input").attr('id','espesor_reservorio_c_g').attr('name','espesor_reservorio_c_g[]').attr('placeholder','Net Pay')
    group_input_3_rock.find("[name = 'medida']").text('ft');

    /* Input permeabilidad absoluta */
    group_input_4_rock.find("[name = 'label']").text('Absolute Permeability').attr('for','permeabilidad_c_g');
    group_input_4_rock.find(".input-group").addClass("{{$errors->has('permeabilidad') ? 'has-error' : ''}}");
    group_input_4_rock.find("input").attr('id','permeabilidad_c_g').attr('name','permeabilidad_c_g[]').attr('placeholder','Absolute Permeability')
    group_input_4_rock.find("[name = 'medida']").text('md');

    /* Input modulo de permeabilidad */
    group_input_1_pm_rock.find("[name = 'label']").text('Permeability Module').attr('for','modulo_permeabilidad_c_g');
    group_input_1_pm_rock.find(".input-group").addClass("{{$errors->has('modulo_permeabilidad_c_g') ? 'has-error' : ''}}");
    group_input_1_pm_rock.find("input").attr('id','modulo_permeabilidad_c_g').attr('name','modulo_permeabilidad_c_g[]').attr('placeholder','Absolute Permeability');
    group_input_1_pm_rock.find("[name = 'medida']").html('psi<sup>-1</sup>');

    /* Boton que permite abrir el modal para calcular el modulo de permeabilidad */
    $('.b_calculate').attr('pm_id', 'modulo_permeabilidad_c_g');

    /* *********Sección de creación y población de tablas ********* */

    /* Water-Oil - Oil - table */
    $('.rpds_right').hide();
    $('.fieldset_tab').children('legend').hide();
    $('.fieldset_corey').hide();
    $('.use_permeability_tables').show();

    $('.rpds_left').find('legend').text('Gas-Oil');
    $('.rpds_left').find('fieldset').find('.handsontable:first').attr('id', 'excelgasliquid_c_g');
    $('.btn_plot_pt_left').attr('onclick','plot_gasOil_c_g()');

    /* Gas-Oil - Oil - table */
    var datos = {!! isset($ipr_cg_gasoil_table) ? $ipr_cg_gasoil_table : '{}' !!};

    $excelwateroil = $('#excelgasliquid_c_g');
    $excelwateroil.handsontable({
      data: datos, 
      colWidths: [80, 80, 80] ,
      rowHeaders: true, 
      columns: [
      {title:"Sg", data: 0,type: 'numeric', format: '0[.]0000000'},
      {title:"Krg",data: 1,type: 'numeric', format: '0[.]0000000'},
      {title:"Krog", data: 2,type: 'numeric', format: '0[.]0000000'}
      ],
      startRows: 6,
      minSpareRows: 2,
      contextMenu: true,
      afterChange: function( changes, source ) {
        validateTabs(false);
      }
    });

    /***************************** Fin sección Rock Properties - Condensate gas *****************************/

    /* Water volumetric factor */
    input_first_fp.find("[name = 'label']").text('Saturation Pressure').attr('for','presion_saturacion_c_g');
    input_first_fp.find(".input-group").addClass("{{$errors->has('presion_saturacion_c_g') ? 'has-error' : ''}}");
    input_first_fp.find(".input-group-btn").remove();
    input_first_fp.find("input").attr('id','presion_saturacion_c_g').attr('name','presion_saturacion_c_g').attr('placeholder','Saturation Pressure').val('{{ isset($IPR->saturation_pressure_c_g) ? $IPR->saturation_pressure_c_g : '' }}');
    input_first_fp.find("[name = 'medida']").text('psi');

    /* Water Viscosity */
    input_second_fp.show();
    input_second_fp.find("[name = 'label']").text('GOR').attr('for','gor_c_g');
    input_second_fp.find(".input-group").addClass("{{$errors->has('gor_c_g') ? 'has-error' : ''}}");
    input_second_fp.find(".input-group-btn").remove();
    input_second_fp.find("input").attr('id','gor_c_g').attr('name','gor_c_g').attr('placeholder','GOR').val('{{ isset($IPR->gor_c_g) ? $IPR->gor_c_g : '' }}');
    input_second_fp.find("[name = 'medida']").text('psi');

    $('.pvt_data').show();
    $('.rpds_up').find('legend').text('PVT Data');
    $('#graph_up').html('');
    $('#graph_oil_viscosity').html('');
    $('#graph_oil_volumetric_factor').html('');
    $('#graph_water_viscosity').html('');
    $('#graph_gas_viscosity').html('');
    $('#graph_gas_compressibility_factor').html('');
    $('#graph_bo').html('');
    $('#graph_uo').html('');
    $('#graph_rs').html('');
    $('#graph_bg').html('');
    $('#graph_ug').html('');
    $('#graph_ogratio').html('');
    
    $('.rpds_up').find('fieldset').find('.handsontable:first').attr('id', 'excel_table_pvt');
    $('.btn_plot_fp_first').attr('onclick','plot_pvt_c_g()');

    /* Pvt Condensate Gas */
    var datos = {!! !empty($ipr_cg_pvt_table) ? $ipr_cg_pvt_table : '{}' !!};

    $excel_tabular_pvt_fluid_c_g = $('#excel_table_pvt');
    $excel_tabular_pvt_fluid_c_g.handsontable({
      data: datos, 
      colWidths: [102, 102, 102, 102, 102, 102, 152] ,
      rowHeaders: true, 
      columns: [
      {title:"Pressure [psi] ", data: 0,type: 'numeric', format: '0[.]0000000'},
      {title:"Bo [RB/STB] ", data: 1,type: 'numeric', format: '0[.]0000000'},
      {title:"Uo [cP] ", data: 2,type: 'numeric', format: '0[.]0000000'},
      {title:"RS [SCF/STB] ", data: 3,type: 'numeric', format: '0[.]0000000'},
      {title:"Bg [RCF/SCF] ", data: 4,type: 'numeric', format: '0[.]0000000'},
      {title:"Ug [cP] ",data: 5,type: 'numeric', format: '0[.]0000000'},
      {title:"O-G Ratio [STB/SCF]", data: 6,type: 'numeric', format: '0[.]0000000'}
      ],
      startRows: 6,
      minSpareRows: 2,
      contextMenu: true,
      afterChange: function( changes, source ) {
        validateTabs(false);
      }
    });

    $('.drop_out_data').show();
    $('.rpds_down').find('legend').text('Drop-Out Data');
    $('.rpds_down').find('fieldset').find('.handsontable:first').attr('id', 'excel_table_dod');
    $('.btn_plot_fp_second').attr('onclick','plot_drop_out_c_g()');  

    /* Drop out Condensate Gas */
    var datos = {!! !empty($ipr_cg_dropout_table) ? $ipr_cg_dropout_table : '{}' !!};

    $drop_out_c_g = $('#excel_table_dod');
    $drop_out_c_g.handsontable({
      data: datos, 
      colWidths: [130, 130] ,
      rowHeaders: true, 
      columns: [

      {title:"Pressure [psi]", data: 0,type: 'numeric', format: '0[.]0000000'},
      {title:"Liquid Fraction []",data: 1,type: 'numeric', format: '0[.]0000000'}
      ],
      startRows: 6,
      minSpareRows: 2,
      contextMenu: true,
      afterChange: function( changes, source ) {
        validateTabs(false);
      }
    });
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 11/10/2018
  * Descripción: Esta función se encarga de definir los campos y tablas a mostrar siempre y cuando 
  * el tipo de pozo sea Injector y el tipo de fluido sea Water
  */
  function ajusteInjectorWater() {

    /* START - Operative Data */
    var group_input_1 = $('.data_first_input');
    var group_input_2 = $('.data_second_input');
    var group_input_3 = $('.data_third_input');

    /* OD - Injection Rate */
    /* OD */group_input_1.show();
    /* OD */group_input_1.find("[name = 'label']").text('Injection Rate').attr('for','injection_rate');
    /* OD */group_input_1.find(".input-group").addClass("{{$errors->has('injection_rate') ? 'has-error' : ''}}");
    /* OD */group_input_1.find("input").attr('id','injection_rate').attr('name','injection_rate').attr('placeholder','Injection Rate').val({!! isset($IPR->injection_rate) ? $IPR->injection_rate : '' !!});
    /* OD */group_input_1.find("[name = 'medida']").text('bbl/day');
    /* OD - Injection Rate */

    /* OD - BHFP */
    /* OD */group_input_2.show();
    /* OD */group_input_2.find("[name = 'label']").text('BHP').attr('for','bhfp');
    /* OD */group_input_2.find(".input-group").addClass("{{$errors->has('bhfp') ? 'has-error' : ''}}");
    /* OD */group_input_2.find("input").attr('id','bhfp').attr('name','bhfp').attr('placeholder','BHP').val({!! isset($IPR->bhfp) ? $IPR->bhfp : '' !!});
    /* OD */group_input_2.find("[name = 'medida']").text('psi');
    /* OD - BHFP */

    /* OD - Hide */
    /* OD */group_input_3.find("[name = 'label']").text('').attr('for','');
    /* OD */group_input_3.find(".input-group").removeClass("has-error");
    /* OD */group_input_3.find("input").attr('id','').attr('name','').attr('placeholder','').val('');
    /* OD */group_input_3.find("[name = 'medida']").text('');
    /* OD */group_input_3.hide();
    /* OD - Hide */

    /* RP - Reservoir Parting Pressure */
    $('#presion_separacion');
    
    /* END - Operative Data */

    /* START - Fluid Properties */
    var input_first_fp = $('.data_first_input_fp');
    var input_second_fp = $('.data_second_input_fp');

    /* FP - Water volumetric factor */
    /* FP */input_first_fp.find("[name = 'label']").text('Water Volumetric Factor').attr('for','water_volumetric_factor');
    /* FP */input_first_fp.find(".input-group").addClass("{{$errors->has('water_volumetric_factor') ? 'has-error' : ''}}");
    /* FP */input_first_fp.find(".input-group-btn").remove();
    /* FP */input_first_fp.find("input").attr('id','water_volumetric_factor').attr('name','water_volumetric_factor').attr('placeholder','Water Volumetric Factor').val({!! isset($IPR->water_volumetric_factor) ? $IPR->water_volumetric_factor : '' !!});
    /* FP */input_first_fp.find("[name = 'medida']").text('RB/STB');
    /* FP - Water volumetric factor */

    /* FP - Water Viscosity */
    /* FP */input_second_fp.show();
    /* FP */input_second_fp.find("[name = 'label']").text('Water Viscosity').attr('for','water_viscosity');
    /* FP */input_second_fp.find(".input-group").addClass("{{$errors->has('water_viscosity') ? 'has-error' : ''}}");
    /* FP */input_second_fp.find(".input-group-btn").remove();
    /* FP */input_second_fp.find("input").attr('id','water_viscosity').attr('name','water_viscosity').attr('placeholder','Water Viscosity').val({!! isset($IPR->water_viscosity) ? $IPR->water_viscosity : '' !!});
    /* FP */input_second_fp.find("[name = 'medida']").text('CP');
    /* FP - Water Viscosity */


    /* FP */$('.pvt_data').hide();
    /* FP */$('.drop_out_data').hide();

    /* END - Fluid Properties */
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 11/10/2018
  * Descripción: Esta función se encarga de definir los campos y tablas a mostrar siempre y cuando 
  * el tipo de pozo sea Injector y el tipo de fluido sea Gas
  */
  function ajusteInjectorGas() {
    /* START - Operative Data */
    var group_input_1 = $('.data_first_input');
    var group_input_2 = $('.data_second_input');
    var group_input_3 = $('.data_third_input');

    /* START - Fluid Properties */
    var input_first_fp = $('.data_first_input_fp');

    /* OD - Injection Rate */
    /* OD */group_input_1.show();
    /* OD */group_input_1.find("[name = 'label']").text('Injection Rate').attr('for','gas_rate_gg');
    /* OD */group_input_1.find(".input-group").addClass("{{$errors->has('gas_rate_gg') ? 'has-error' : ''}}");
    /* OD */group_input_1.find("input").attr('id','gas_rate_gg').attr('placeholder','Injection Rate').val({!! isset($IPR->gas_rate_g) ? $IPR->gas_rate_g : '' !!});
    /* OD */group_input_1.find("[name = 'medida']").text('MMscf/day');
    /* OD - Injection Rate */

    /* OD - BHFP */
    /* OD */group_input_2.show();
    /* OD */group_input_2.find("[name = 'label']").text('BHP').attr('for','bhfp');
    /* OD */group_input_2.find(".input-group").addClass("{{$errors->has('bhfp') ? 'has-error' : ''}}");
    /* OD */group_input_2.find("input").attr('id','bhfp').attr('placeholder','BHP').val({!! isset($IPR->bhp_g) ? $IPR->bhp_g : '' !!});
    /* OD */group_input_2.find("[name = 'medida']").text('psi');
    /* OD - BHFP */

    /* OD - Hide */
    /* OD */group_input_3.find("[name = 'label']").text('').attr('for','');
    /* OD */group_input_3.find(".input-group").removeClass("has-error");
    /* OD */group_input_3.find("input").attr('id','').attr('placeholder','').val('');
    /* OD */group_input_3.find("[name = 'medida']").text('');
    /* OD */group_input_3.hide();
    /* OD - Hide */

    /* FP - Water volumetric factor */
    /* FP */$('#temperature_text_g').val({!! isset($IPR->temperature_text_g) ? $IPR->temperature_text_g : '' !!});
    /* FP - Water volumetric factor */

  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 05/10/2018
  * Descripción: Esta función se encarga de definir las acciones de los botones del modal.
  */
  function activateActionButtons() {
    var btn_start = $('.b_calculate');
    var modal_obj = $('#modal_calculate');

    btn_start.on('click', function(event) {
      modal_obj.modal('show');
    });
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 10/10/2018
  * Descripción: Esta función se encarga de realizar las respectivas validaciones para mostrar los diferentes valores.
  */
  function validateTabs(validar){
    var tabs = $('.tabbable').children('ul').children('li');

    var errores = 0;
    $.each(tabs, function(index_tab, tab) {
      var li_tab = $(tab).children('a');
      var id_div = li_tab.prop('href').split('#')[1];
      
      /* Esta validación retornara algun valor solo si hay algun error en algun campo */
      if (validateDivs(id_div)) {
        li_tab.css('color','#d9534f');
        errores++;
      } else {
        li_tab.css('color','#337ab7');
        errores = (errores == 0) ? 0 : errores -1;
      }

    });
    if (validar) {
      return (errores > 0);
    }
  }

  /**
  * Author: Esneider Mejia Ciro
  * Date: 10/10/2018
  * Descripción: Esta función se encarga de realizar la respectivas validaciones de los diferentes tipos
  * de campos(inputs) que hay en los divs. { validateTabs = validateDivs, validateInputs, validateFieldsets }.
  */
  function validateDivs(id){
    var div = $('#'+id);
    var validateRockProps = (id == 'rock_properties_c') ? true : false;
    var fieldsets_div = div.find('fieldset');
    var inputs_div = div.find('input');
    var handsontable_div = div.find('.handsontable');

    validateInputs(inputs_div);
    validateFieldsets(fieldsets_div);
    validateTables(handsontable_div);

    var errores = 0;
    $.each(div.find('div.has-error'), function(index, val) {
      var val = $(val);
      var parent_fieldset = val.parents('fieldset');

      if(parent_fieldset.length > 0) {
        if(parent_fieldset.attr('style') != 'display: none;'){
          errores++;
        }
      } else {
        if(val.attr('style') != 'display: none;'){
          errores++;
        }  
      }
    });

    return errores > 0;
  }

  function validateInputs(inputs) {
    $.each(inputs, function(index_input, input) {
      var input = $(input);
      if (input.parents('fieldset').length == 0) {
        var form_group_div = input.parents('div.form-group');
        var tipo = input.attr('type');
        if (tipo != 'hidden' && form_group_div.attr('style') != "display: none;") {
          if (input.val() == '') {
            form_group_div.addClass('has-error');
          } else {
            form_group_div.removeClass('has-error');
          }
        }
      }
    });
  }

  function validateFieldsets(fieldsets){
    $.each(fieldsets, function(index_fieldset, fieldset) {
      var fieldset = $(fieldset);
      var fieldsetPanel = fieldset.parents('.panel.panel-default');
      var inputs_fieldset = fieldset.find('input');
      var canValidate = false;

      if (fieldsetPanel.length > 0) {
        fieldsetPanelJ = $(fieldsetPanel[0]);

        if (fieldsetPanelJ.attr('style') != 'display: none;' || fieldset.attr('style') != 'display: none;') {
          if (inputs_fieldset.length > 0) {
            $.each(inputs_fieldset, function(index_i_fieldset, input) {
              var input = $(input);
              if (input.attr('type') == 'radio') {
                if (input.is(':checked')) {
                  canValidate = true;
                } else {
                  canValidate = false;
                }
              }
            });
          } else {
            canValidate = true;
          }
        }
      }

      if (canValidate === true) {
        $.each(inputs_fieldset, function(index_i_fieldset, input) {
          var input = $(input);
          if (input.attr('type') == 'radio') {
            if (input.is(':checked')) {
              inputs_fieldset_ = input.parents('fieldset').find('input[type=text]');
              $.each(inputs_fieldset_, function(index_i_fieldset_, input_fieldset_) {
                var input_fieldset_ = $(input_fieldset_);
                var form_group_fieldset = input_fieldset_.parents('div.form-group');
                var tipo = input_fieldset_.attr('type');

                if (tipo != 'hidden' && form_group_fieldset.attr('style') != "display: none;") {
                  if (input_fieldset_.val() == '') {
                    form_group_fieldset.addClass('has-error');
                  } else {
                    form_group_fieldset.removeClass('has-error');
                  }
                }
              });
            } else {
              inputs_fieldset_ = input.parents('fieldset').find('input[type=text]');
              $.each(inputs_fieldset_, function(index_i_fieldset_, input_fieldset_) {
                var input_fieldset_ = $(input_fieldset_);
                var form_group_fieldset = input_fieldset_.parents('div.form-group');
                form_group_fieldset.removeClass('has-error');
              });
            }
          }
        });
      } else {
        $.each(inputs_fieldset, function(index_i_fieldset, input) {
          var input = $(input);

          inputs_fieldset_ = input.parents('fieldset').find('input[type=text]');
          $.each(inputs_fieldset_, function(index_i_fieldset_, input_fieldset_) {
            var input_fieldset_ = $(input_fieldset_);
            var form_group_fieldset = input_fieldset_.parents('div.form-group');
            form_group_fieldset.removeClass('has-error');
          });
        });
      }
    });
  }

  function validateTables(tables) {
    $.each(tables, function(index_table, table) {
      var tableElement = $(table);
      tableElement.removeClass('has-error');
      var tableFieldset = tableElement.parents('fieldset');
      var fieldsetPanel = tableFieldset.parents('.panel.panel-default');
      var inputs_fieldset = tableFieldset.find('input[type=radio]');
      var divs_tables_left = tableElement.parents('.rpds_left');
      var divs_tables_right = tableElement.parents('.rpds_right');
      var canValidate = false;

      if ((divs_tables_left.length <= 0 && divs_tables_right.length <= 0) || ((divs_tables_left.length > 0 && $(divs_tables_left[0]).attr('style') != 'display: none;') || (divs_tables_right.length > 0 && $(divs_tables_right[0]).attr('style') != 'display: none;'))) {
        if (fieldsetPanel.length > 0) {
          var fieldsetPanelJ = $(fieldsetPanel[0]);

          if (fieldsetPanelJ.attr('style') != 'display: none;') {
            if (inputs_fieldset.length > 0) {
              $.each(inputs_fieldset, function(index_i_fieldset, input) {
                var input = $(input);
                if (input.attr('type') == 'radio') {
                  if (input.is(':checked')) {
                    canValidate = true;
                  } else {
                    canValidate = false;
                  }
                }
              });
            } else {
              canValidate = true;
            }
          }
        }
      }

      if (canValidate === true) {
        var datatables = tableElement.handsontable('getData');

        if (datatables !== undefined) {
          var hasData = false;
          var previousRowHasData = true;

          for (var i = 0; i < datatables.length; i++) {
            var data = datatables[i];
            var rowHasData = false;
            var previousColumnHasData = false;
            var failedNestedFor = false;

            for (var j = 0; j < data.length; j++) {
              if (data[j] !== null && data[j] !== '') {
                if (previousColumnHasData === false && j > 0) {
                  tableElement.addClass('has-error');
                  failedNestedFor = true;
                  break;
                } else {
                  previousColumnHasData = true;
                  rowHasData = true;
                  hasData = true;
                }
              } else if (rowHasData === true) {
                tableElement.addClass('has-error');
                failedNestedFor = true;
                break;
              } else {
                previousColumnHasData = false;
              }
            }

            if (failedNestedFor === true) {
              break;
            } else if (previousRowHasData === false && rowHasData === true) {
              tableElement.addClass('has-error');
              break;
            }

            previousRowHasData = rowHasData;
          }

          if (hasData === false) {
            tableElement.addClass('has-error');
          }
        }
      }
    });
  }
  /*********************** FIN DE SECCIÓN CREAR *********************************/
  
  /*********************** INICIO DE SECCIÓN EDITAR *********************************/
  
  /**
  * Author: Esneider Mejia Ciro
  * Date: 17/10/2018
  * Descripción: Esta función se encarga de realizar setear los diferentes valores en cada campo 
  * y de ejecutar las respectivas funciones extras.
  */
  function setDefaultValues() {
    /* Se ejecuta acción */
    $('#well_type').change();
  }

</script>
