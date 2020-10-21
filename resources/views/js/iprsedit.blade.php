<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

  $(document).ready(function(){
    import_tree("IPR", "IPR");  
  });
   
   /* En este archivo se encuentra todo el javascript que controla las ventanas de ipr en modo editar */

   /* *********Sección Funciones********* */

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
     $('#corey_gasOil_g').highcharts({
            title: {
                text: 'Gas-Oil Kr\'s',
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
     $('#corey_waterOil_g').highcharts({
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

     $('#waterOil_g').highcharts({
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
            }]
        });
    }
    /* Descripción: esta función se encarga de capturar los datos de la tabla de kr gas-aceite desde el formulario ipr edit para fluido aceite en la sección "Rock properties", generar el gráfico y mostrarlo en la vista ipredit */
    function plot_gasOil()
    {
     var evt = window.event || arguments.callee.caller.arguments[0];
     evt.preventDefault(); 
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
       sg.push(parseFloat(data[i][0]));
       krg.push(parseFloat(data[i][1]));
       krog.push(parseFloat(data[i][2]));
     }

     $('#gasOil_g').highcharts({
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
            }]
        });
    }
    /* Descripción: esta función se encarga de capturar los datos de la tabla de PVT desde el formulario ipr edit para fluido aceite en la sección "Fluid properties", calcular el gráfico de pvt, generar el gráfico y mostrarlo en la vista ipredit */
    function plot_pvt_oil()
    {
     var evt = window.event || arguments.callee.caller.arguments[0];
     evt.preventDefault(); 
     var data = order_pvt(curr_hot.getData());
     //data = $("#excel_table").handsontable('getData');
     var pressure = [];
     var oil_viscosity = [];
     var oil_volumetric_factor = [];
     var water_viscosity = [];
     for (var i = 0; i < data.length; i++)
     {
       pressure.push(parseFloat(data[i][0]));
       oil_viscosity.push(parseFloat(data[i][1]));
       oil_volumetric_factor.push(parseFloat(data[i][2]));
       water_viscosity.push(parseFloat(data[i][3]));
     }
     pressure.pop();
     oil_viscosity.pop();
     oil_volumetric_factor.pop();
     water_viscosity.pop();
     console.log(pressure);
     $('#pvt_ipr_oil').highcharts({
            title: {
                text: 'Oil PVT',
                x: -20 //center
            },
            xAxis: {
                title: {
                    text: 'Pressure [psi]'
                },
                categories: pressure
            },
            yAxis: {
                title: {
                    text: 'Uo, Uw & Bo'
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
                name: 'Oil Viscosity',
                data: oil_viscosity,
                tooltip:{
                 valueSuffix: ' cp'
                }
            }, {
                name: 'Oil Volumetric Factor',
                data: oil_volumetric_factor,
                tooltip:{
                 valueSuffix: ' RB/STB'
                }
            }, {
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
     //var data = order_pvt(curr_hot.getData());
     var data = $("#excel_tabular_pvt_fluid_g").handsontable('getData');
     var pressure = [];
     var gas_viscosity = [];
     var gas_compressibility_factor = [];
     
     for (var i = 0; i < data.length; i++)
     {
       pressure.push(data[i][0]);
       gas_viscosity.push(data[i][1]);
       gas_compressibility_factor.push(data[i][2]);
     }

     pressure.pop();
     gas_viscosity.pop();
     gas_compressibility_factor.pop();
     
     $('#pvt_ipr_gas').highcharts({
            title: {
                text: 'Gas PVT',
                x: -20 //center
            },
            xAxis: {
             title: {
               text: 'Pressure [psi]'
             },
                categories: pressure
            },
            yAxis: {
                title: {
                    text: 'Ug & Bg'
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
                name: 'Gas Viscosity [cp]',
                data: gas_viscosity,
                tooltip: {
                 valueSuffix: ' cp'
                }
            }, {
                name: 'Gas Compressibility Factor',
                data: gas_compressibility_factor,
                tooltip:{
                 valueSuffix: ''
                }
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
      for (var j = 0; j < array.length; j++)
      {
          if ( parseFloat(array[j]) == parseFloat($("#presion_saturacion").val()) )
          {
            valid=true;
          }
          if(parseFloat(array[j]) > parseFloat($("#presion_saturacion").val()))
          {
            valid2=true;
          }
          if(parseFloat(array[j]) < parseFloat($("#presion_saturacion").val()))
          {
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
    /* Descripción: esta función se encarga de capturar los datos de la tabla de PVT desde el formulario ipr edit para fluido gas condensado en la sección "Fluid properties", generar el gráfico y mostrarlo en la vista ipredit */
    function plot_pvt_c_g()
    {
     var evt = window.event || arguments.callee.caller.arguments[0];
     evt.preventDefault(); 
     var data = $("#pvt_c_g").handsontable('getData');

     var pressure = [];
     var oil_viscosity = [];
     var rs = [];
     var gas_volumetric_factor = [];
     var gas_viscosity = [];
     var oil_gas_ratio = [];

     for (var i = 0; i < data.length; i++)
     {
       pressure.push(parseFloat(data[i][0]));
       oil_viscosity.push(parseFloat(data[i][1]));
       rs.push(parseFloat(data[i][2]));
       gas_volumetric_factor.push(parseFloat(data[i][3]));
       gas_viscosity.push(parseFloat(data[i][4]));
       oil_gas_ratio.push(parseFloat(data[i][5]));
     }

     pressure.pop();
     oil_viscosity.pop();
     rs.pop();
     gas_volumetric_factor.pop();
     gas_viscosity.pop();
     oil_gas_ratio.pop();

     $('#pvt_c_g_chart').highcharts({
            title: {
                text: 'Condensate Gas PVT',
                x: -20 //center
            },
            xAxis: {
             title: {
               text: 'Pressure [psi]'
             },
                categories: pressure
            },
            yAxis: {
                title: {
                    text: '...***...'
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
                name: 'Oil Viscosity',
                data: oil_viscosity,
                tooltip:{
                 valueSuffix: ' cp'
                }
            }, {
                name: 'RS',
                data: rs,
                tooltip:{
                 valueSuffix: ' RB/STB'
                }
            }, {
                name: 'Gas Volumetric Factor',
                data: gas_volumetric_factor,
                tooltip:{
                 valueSuffix: ' cp'
                }
            }, {
                name: 'Gas Viscosity',
                data: gas_viscosity,
                tooltip:{
                 valueSuffix: ' cp'
                }
            }, {
                name: 'Oil-Gas Ratio',
                data: oil_gas_ratio,
                tooltip:{
                 valueSuffix: ' cp'
                }
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

     $('#gas_oil_table_chart_c_g').highcharts({
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
            }]
        });
    }
    /* Descripción: esta función se encarga de capturar los datos de la tabla de Dropout desde el formulario ipr edit para fluido gas condensado en la sección "Fluid properties", generar el gráfico y mostrarlo en la vista ipredit */
    function plot_drop_out_c_g()
    {

     var evt = window.event || arguments.callee.caller.arguments[0];
     evt.preventDefault(); 
     data = $("#drop_out_c_g").handsontable('getData');
     var pressure = [];
     var liquid_fracture = [];

     for (var i = 0; i < data.length; i++)
     {
       pressure.push(data[i][0]);
       liquid_fracture.push(data[i][1]);
     }
     pressure.pop();
     liquid_fracture.pop();

     $('#drop_out_c_g_chart').highcharts({
            title: {
                text: 'Condensate Gas Drop Out',
                x: -20 //center
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
    /* Descripción: esta función guarda los datos de las tablas de la ipr que se está ejecutando sea aceite, gas o gas condensado en los campos hidden creados en el formulario para la recarga de la página y el almacenamiento temporal de los datos. Además, se encarga de normalizar los textfields y las tablas con base en los check box que se encuentran en el formulario para no guardar datos innecesarios. */
    function save()
    {

      /* Lectura y almacenado de datos de las tablas del formulario */

      //Oil
      var datos_unidades_hidraulicas = order_pvt(curr_hot.getData());
      var datos_wateroil = $excelwateroil.handsontable('getData');
      var datos_gasliquid = $excelgasliquid.handsontable('getData');
      //Gas
      var datos_pvt_ipr_g = order_pvt($excel_tabular_pvt_fluid.handsontable('getData'));


      $("#presiones_table").val(JSON.stringify(datos_unidades_hidraulicas));
      $("#wateroil_hidden").val(JSON.stringify(datos_wateroil));
      $("#gasliquid_hidden").val(JSON.stringify(datos_gasliquid));

      $('#pvt_gas_ipr').val(JSON.stringify(datos_pvt_ipr_g));
      
      //Condesate gas
      var pvt_cg_data = $("#pvt_c_g").handsontable('getData');
      var go_cg_data = $("#excelgasliquid_c_g").handsontable('getData');
      var dropout_cg_data = $("#drop_out_c_g").handsontable('getData');

      $('#pvt_cg').val(JSON.stringify(pvt_cg_data));
      $('#gas_oil_kr_cg').val(JSON.stringify(go_cg_data));
      $('#dropout_cg').val(JSON.stringify(dropout_cg_data));

      /* Normalización de textfields según fluido */

      if($("#fluido").val()=="1")
      {
         //Production Data
         if($("#check_skin_o").is(":checked"))
         {
            $("#tasa_flujo").val('');
            $("#presion_fondo").val('');
         }            

         if($("#check_testpoint_o").is(":checked"))
         {
            $("#factor_dano").val('');
         }            

         //Rock Properties
         //Permeability Module
         if($("#check_use_permeability_module_o").is(":checked"))
         {
            $("#permeabilidad").val('');
            $("#porosidad").val('');               
            $("#tipo_roca").val('');
         }            

         if($("#check_calculate_permeability_module_o").is(":checked"))
         {
            $("#modulo_permeabilidad").val('');
         }

         //Relative permeability data selection
         if($("#check_tabular_rel_perm_o").is(":checked"))
         {
            $("#end_point_kr_aceite_gas").val('');
            $("#saturacion_gas_crit").val('');               
            $("#end_point_kr_gas").val('');
            $("#saturacion_aceite_irred_gas").val('');
            $("#exponente_corey_aceite_gas").val('');
            $("#exponente_corey_gas").val('');

            $("#end_point_kr_petroleo").val('');
            $("#saturacion_agua_irred").val('');
            $("#end_point_kr_agua").val('');
            $("#saturacion_aceite_irred").val('');
            $("#exponente_corey_petroleo").val('');
            $("#exponente_corey_agua").val('');
         }            
      }
      else if($("#fluido").val()=="2")
      {
         //Production Data
         if($("#check_skin_g").is(":checked"))
         {
            $("#gas_rate_g").val('1');
            $("#bhp_g").val('');
         }
         if($("#check_testpoint_g").is(":checked"))
         {
            $("#skin_g").val('');
         }

         //Rock Properties
         if($("#check_use_permeability_module_g").is(":checked"))
         {
            $("#abs_perm_text_g").val('');
            $("#porosity_text_g").val('');
            $("#rock_type").val('');
            
         }
         if($("#check_calculate_permeability_module_g").is(":checked"))
         {
            $("#permeability_module_text_g").val('');
         }
      }
      else if($("#fluido").val()=="3")
      {
         //Rock Properties
         //Permeability Module
         if($("#check_use_permeability_module_c_g").is(":checked"))
         {
            $("#permeabilidad_c_g").val('');
            $("#porosidad_c_g").val('');               
            $("#tipo_roca_c_g").val('');
         }            

         if($("#check_calculate_permeability_module_c_g").is(":checked"))
         {
            $("#modulo_permeabilidad_c_g").val('');
         }
      }
      var form = $(this).parents('form:first');
      document.getElementById('loading').style.display = 'block';
      form.submit(); 
    }
    /* Descripción: esta función posiciona el marco descriptivo del escenario en el tope del formulario */
    function sticky_relocate()
    {
      var window_top = $(window).scrollTop();
      var div_top = $('#sticky-anchor').offset().top;
      if (window_top > div_top)
      {
        $('#sticky').addClass('stick');
      }
      else
      {
        $('#sticky').removeClass('stick');
      }
    }
   
    $(function ()
     {
       $(window).scroll(sticky_relocate);
       sticky_relocate();
     });
   
    /* Descripción: esta función define las porciones de la vista que se mostrarán con base en el fluido del escenario escogido dinámicamente. 
    Parámetros:
        fluido: tipo de fluido del escenario IPR.*/
    function defineView(fluido)
    {
      if(fluido.value == "1")
      {
         $(".gas_data").hide();
         $(".oil_data").show();
         $(".condensate_gas_data").hide();
      }
      else if(fluido.value == "2")
      {
         $(".gas_data").show();
         $(".oil_data").hide();
         $(".condensate_gas_data").hide();
      }
      else if(fluido.value=="3")
      {
         $(".gas_data").hide();
         $(".oil_data").hide();
         $(".condensate_gas_data").show();
      }
    }
    /* Descripción: esta función valida inicialmente los datos de las tablas de los escenarios ipr de aceite, gas y gas condensado; normaliza también los valores con base en los check box escogidos; normaliza los valores de las tablas y prepara todo el set de datos que será enviado al controlador. Dentro de la función hay una serie de validaciones que se van acumulando y si al final, pasan todas las validaciones, se redirecciona al controlador, si no, se queda en la vista y le informa al usuario los errores encontrados. 
    Parámetros:
        validate: sirve como flag dentro de la función para saber si se procede o no al controlador. Por defecto llega como true.*/
    function enviar(validate)
    {

        validate = validate || false;

        /* Lectura de datos de las tablas del formulario */
        //Oil
        var datos_unidades_hidraulicas = order_pvt(curr_hot.getData());
        var datos_wateroil = $excelwateroil.handsontable('getData');
        var datos_gasliquid = $excelgasliquid.handsontable('getData');
        

        //Gas
        var datos_pvt_ipr_g = order_pvt($excel_tabular_pvt_fluid.handsontable('getData'));

        //Condensate gas
        var pvt_cg_data = order_pvt($("#pvt_c_g").handsontable('getData'));
        var go_cg_data = $("#excelgasliquid_c_g").handsontable('getData');
        var dropout_cg_data = $("#drop_out_c_g").handsontable('getData');
   
        //Arreglos para evaluar los datos de cada una de las tablas.
        var sw_wateroil_rev = [];
        var krw_wateroil_rev = [];
        var kro_wateroil_rev = [];
        var sg_gasoil_rev = [];
        var krg_gasoil_rev = [];
        var krog_gasoil_rev = [];
        var pressure_pvtoil_rev = [];
        var pressure_pvtgas_rev = [];

        var pressure_pvt_cg_rev = [];

        //Flags para controlar la validez de los datos.
        var form = $(this).parents('form:first');
        var valid=true;
        var valid1 = true;
        var valid2 = true;
        var valid3 = true;
        var valid4 = true;
        var valid5 = true;
        var valid6 = true;
        var valid7 = true;
        var valid8 = true;
        var valid9 = true;
        var valid10 = true;
        var valid1_cg = true;
        var valid2_cg = true;
        var valid3_cg = true;
        var valid4_cg = true;
        var valid5_cg = true;
        var valid6_cg = true;
        var valid7_cg = true;
        var mensaje = ":'(";
   
        //Revisión tablas y checks
        if($("#fluido").val()=="1")
        {
           
           //Tablas
           //Relative Permeabilivy
           if($("#check_tabular_rel_perm_o").is(":checked"))
           {
              
              //Water oil
              d0 = datos_wateroil[0][0];
              d1 = datos_wateroil[0][1];
              d2 = datos_wateroil[0][2];
              d3 = datos_wateroil[1][0];
              d4 = datos_wateroil[1][1];
              d5 = datos_wateroil[1][2];

              if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
              {
                 valid1=false;
                 mensaje="Check your Water-Oil data for Oil IPR, there's nothing there...";
              }
              if(((d0 ==0) && (d1==0) && (d2==0)) || ((d3==0) && (d4==0) && (d5==0)))
              {
                 valid1=false;
                 mensaje="The whole row can't be 0 at Water-Oil data. Check your Relative Permeability Data";
              }
              if(d1!=0)
              {
                 valid1=false;
                 mensaje="The first Water-Oil krw value must be 0. Check your Relative Permeability Data";
              }
              for (var i = 0; i<datos_wateroil.length; i++)
              {
                 d0 = datos_wateroil[i][0];
                 d1 = datos_wateroil[i][1];
                 d2 = datos_wateroil[i][2];
                 if((d0==0) && (d1==0 ) && (d2==0))
                 {
                    if(d0==="" && d1==="" && d2==="")
                    {
                       continue;
                    }
                    else
                    {
                       valid1 = false;
                       mensaje = "The whole row can't be 0 at Water-Oil data. Check your Relative Permeability Data";
                    }

                 }

                 if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
                 {

                    if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
                    {
                       valid1=false;
                       mensaje="Check your Water-Oil data for Oil IPR, there's some incomplete data. Check your Relative Permeability Data";
                    }
                    if(d1<0 || d2<0 || d0<0)
                    {
                       valid1=false;
                       mensaje = "Your Water-Oil data must be greather than 0. Check your Relative Permeability Data";
                    }
                    if(d0<0 || d0>1)
                    {
                       valid1=false;
                       mensaje="Water-Oil Sw must be between 0 and 1. Check your Relative Permeability Data";
                    }
                    if(d1<0 || d1>1)
                    {
                       valid1=false;
                       mensaje="Water-Oil krw must be between 0 and 1. Check your Relative Permeability Data";
                    }
                    if(d2<0 || d2>1)
                    {
                       valid1=false;
                       mensaje="Water-Oil kro must be between 0 and 1. Check your Relative Permeability Data";
                    }
                    sw_wateroil_rev.push(d0);
                    krw_wateroil_rev.push(d1);
                    kro_wateroil_rev.push(d2);
                 }
              }
              if(!is_asc(sw_wateroil_rev))
              {
                 valid1 = false;
                 mensaje = "Water Oil sw data must be ascending sorted. Check your Relative Permeability Data";
              }
              if(!is_asc(krw_wateroil_rev))
              {
                 valid1 = false;
                 mensaje = "Water Oil krw data must be ascending sorted. Check your Relative Permeability Data";
              }
              if(!is_desc(kro_wateroil_rev))
              {
                 valid1=false;
                 mensaje="Water Oil kro must be descending sorted. Check your Relative Permeability Data";
              }
              else
              {
                 if(kro_wateroil_rev[kro_wateroil_rev.length-1]!=0)
                 {
                    valid1=false;
                    mensaje="The last Kro data must be 0. Check your Relative Permeability Data";
                 }
              }

              //Gas-oil
              d0 = datos_gasliquid[0][0];
              d1 = datos_gasliquid[0][1];
              d2 = datos_gasliquid[0][2];
              d3 = datos_gasliquid[1][0];
              d4 = datos_gasliquid[1][1];
              d5 = datos_gasliquid[1][2];

              if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
              {
                 valid1=false;
                 mensaje="Check your Gas-Oil data for Oil IPR, there's nothing there...";
              }
              if(((d0 ==0) && (d1==0) && (d2==0)) || ((d3==0) && (d4==0) && (d5==0)))
              {
                 valid1=false;
                 mensaje="The whole row can't be 0 at Gas-Oil data. Check your Relative Permeability Data";
              }
              for (var i = 0; i<datos_gasliquid.length; i++)
              {
                 d0 = datos_gasliquid[i][0];
                 d1 = datos_gasliquid[i][1];
                 d2 = datos_gasliquid[i][2];

                 if((d0==0) && (d1==0 ) && (d2==0))
                 {
                    if(d0==="" && d1==="" && d2==="")
                    {
                       continue;
                    }
                    else
                    {
                       valid1 = false;
                       mensaje = "The whole row can't be 0 at Gas-Oil data. Check your Relative Permeability Data";
                    }

                 }

                 if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
                 {

                    if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
                    {
                       valid1=false;
                       mensaje="Check your Water-Oil data for Oil IPR, there's some incomplete data";
                    }

                    if(d1<0 || d2<0 || d0<0)
                    {
                       valid1=false;
                       mensaje = "Your Gas-Oil data must be greather than 0. Check your Relative Permeability Data";
                    }
                    if(d0<0 || d0>1)
                    {
                       valid1=false;
                       mensaje="Gas-Oil Sg must be between 0 and 1. Check your Relative Permeability Data";
                    }
                    if(d1<0 || d1>1)
                    {
                       valid1=false;
                       mensaje="Gas-Oil krg must be between 0 and 1. Check your Relative Permeability Data";
                    }
                    if(d2<0 || d2>1)
                    {
                       valid1=false;
                       mensaje="Gas-Oil krog must be between 0 and 1. Check your Relative Permeability Data";
                    }
                    sg_gasoil_rev.push(d0);
                    krg_gasoil_rev.push(d1);
                    krog_gasoil_rev.push(d2);
                 }
              }
              if(!is_asc(sg_gasoil_rev))
              {
                 valid1 = false;
                 mensaje = "Gas Oil sg data must be ascending sorted. Check your Relative Permeability Data";
              }
              if(!is_asc(krg_gasoil_rev))
              {
                 valid1 = false;
                 mensaje = "Gas Oil krg data must be ascending sorted. Check your Relative Permeability Data";
              }
              if(!is_desc(krog_gasoil_rev))
              {
                 valid1=false;
                 mensaje="Gas Oil kro must be descending sorted. Check your Relative Permeability Data";
              }
              else
              {
                 if(krog_gasoil_rev[krog_gasoil_rev.length-1]!=0)
                 {
                    valid1=false;
                    mensaje="The last Krog data must be 0. Check your Relative Permeability Data";
                 }
              }
           }
           else
           {
              valid1=true;
           }


           //PVT Oil
           d0 = datos_unidades_hidraulicas[0][0];
           d1 = datos_unidades_hidraulicas[0][1];
           d2 = datos_unidades_hidraulicas[0][2];
           d3 = datos_unidades_hidraulicas[0][3];
           d4 = datos_unidades_hidraulicas[1][0];
           d5 = datos_unidades_hidraulicas[1][1];
           d6 = datos_unidades_hidraulicas[1][2];
           d7 = datos_unidades_hidraulicas[1][3];

           if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3 ==="" || d3 == null) || (d4==="" || d4 == null) && (d5==="" || d5 == null) && (d6 ==="" || d6 == null) && (d7==="" || d7 == null))
           {
              valid2=false;
              mensaje="Check your PVT data for Oil IPR, there's nothing there...";
           }

           for (var i = 0; i<datos_unidades_hidraulicas.length; i++)
           {
              d0 = datos_unidades_hidraulicas[i][0];
              d1 = datos_unidades_hidraulicas[i][1];
              d2 = datos_unidades_hidraulicas[i][2];
              d3 = datos_unidades_hidraulicas[i][3];
              if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!="") || (d3 != null && d3!=""))
              {

                 if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2===""|| d3==null || d3==="" )
                 {
                    valid2=false;
                    mensaje="Check your PVT data for Oil IPR, there's some incomplete rows";
                 }
                 if(d1<0 || d2<0 || d0<0 || d3<0)
                 {
                    valid2=false;
                    mensaje = "Your PVT data data must be greather than 0";
                 }
                 if(d0<14.7)
                 {
                    valid2=false;
                    mensaje = "The Pression values at PVT table must be greather than 14.7";
                 }
                 pressure_pvtoil_rev.push(d0);
              }
           }
           if(!is_asc(pressure_pvtoil_rev))
           {
              valid2=false;
              mensaje="The PVT pressures must be ascending sorted";
           }
           if(!verifyPVToil(pressure_pvtoil_rev))
           {
              valid2=false;
              mensaje="Check your PVT pressures data";
           }

           //Checks
           //Permeability module
           if($("#check_use_permeability_module_o").is(":checked") || $("#check_calculate_permeability_module_o").is(":checked"))
           {
              valid4=true;
           }
           else
           {
              valid4=false;
              mensaje ="Please select an option: Use permeability module or Calculate permeability module";
           }   

           //Relative permeability
           if($("#check_corey_rel_perm_o").is(":checked") || $("#check_tabular_rel_perm_o").is(":checked"))
           {
              valid5=true;

           }
           else
           {
              valid5=false;
              mensaje ="Please select an option: Use tabular data or Corey's Model";
           }   

           valid = valid1 && valid2 && valid4 && valid5 && valid10;
        }
        else if($("#fluido").val()=="2")
        {

           //Tablas
           //PVT Gas
           d0 = datos_pvt_ipr_g[0][0];
           d1 = datos_pvt_ipr_g[0][1];
           d2 = datos_pvt_ipr_g[0][2];
           d3 = datos_pvt_ipr_g[1][0];
           d4 = datos_pvt_ipr_g[1][1];
           d5 = datos_pvt_ipr_g[1][2];

           if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3 ==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
           {
              valid7=false;
              mensaje="Check Your PVT data for Gas IPR, there's nothing there...";
           }

           for (var i = 0; i<datos_pvt_ipr_g.length; i++)
           {
              d0 = datos_pvt_ipr_g[i][0];
              d1 = datos_pvt_ipr_g[i][1];
              d2 = datos_pvt_ipr_g[i][2];
              if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
              {

                 if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
                 {
                    valid7=false;
                    mensaje="Check your PVT data for Gas IPR, there's some incomplete data";
                 }
                 if(d0<0 || d1<0 || d2<0)
                 {
                    valid7=false;
                    mensaje = "Your PVT data data must be greather than 0";
                 }
                 if(d0<14.7)
                 {
                    valid7=false;
                    mensaje = "The minimum value for pressure is 14.7. Check your PVT data";
                 }
                 pressure_pvtgas_rev.push(d0);
              }
           }
           if(!is_asc(pressure_pvtgas_rev))
           {
              valid7=false;
              mensaje="The pressures must be ascending sorted. Check your PVT Data";
           }

           //Checks
           //Permeability module
           if($("#check_use_permeability_module_g").is(":checked") || $("#check_calculate_permeability_module_g").is(":checked"))
           {
              valid8=true;
           }
           else
           {
              valid8=false;
              mensaje ="Please select an option: Use permeability module or Calculate permeability module";
           }

           valid=valid7&&valid8;
        }
        else if($("#fluido").val()=="3")
        {

          //Validación orden y rango de datos a tabla kr-gasoil - Condensate gas
          d0 = go_cg_data[0][0];
          d1 = go_cg_data[0][1];
          d2 = go_cg_data[0][2];
          d3 = go_cg_data[1][0];
          d4 = go_cg_data[1][1];
          d5 = go_cg_data[1][2];

          if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
          {
             valid1_cg =false;
             mensaje="Check your Gas-Oil data for Oil IPR, there's nothing there...";
          }
          if(d1!=0)
          {
             valid1_cg =false;
             mensaje="The first Gas-Oil krg value must be 0";
          }
          if(((d0 ==0) && (d1==0) && (d2==0)) || ((d3==0) && (d4==0) && (d5==0)))
          {
             valid1_cg =false;
             mensaje="The whole row can't be 0 at Gas-Oil data";
          }
          for (var i = 0; i<go_cg_data.length; i++)
          {
             d0 = go_cg_data[i][0];
             d1 = go_cg_data[i][1];
             d2 = go_cg_data[i][2];

             if((d0==0) && (d1==0 ) && (d2==0))
             {
                if(d0==="" && d1==="" && d2==="")
                {
                   continue;
                }
                else
                {
                   valid1_cg  = false;
                   mensaje = "The whole row can't be 0 at Gas-Oil data";
                }

             }

             if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
             {

                if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
                {
                   valid1_cg =false;
                   mensaje="Check your Gas-Oil data for Condensate Gas IPR, there's some incomplete data";
                }

                if(d1<0 || d2<0 || d0<0)
                {
                   valid1_cg =false;
                   mensaje = "Your Gas-Oil data must be greather than 0";
                }
                if(d0<0 || d0>1)
                {
                   valid1_cg =false;
                   mensaje="Gas-Oil Sg must be between 0 and 1";
                }
                if(d1<0 || d1>1)
                {
                   valid1_cg =false;
                   mensaje="Gas-Oil krg must be between 0 and 1";
                }
                if(d2<0 || d2>1)
                {
                   valid1_cg =false;
                   mensaje="Gas-Oil krog must be between 0 and 1";
                }
                sg_gasoil_rev.push(d0);
                krg_gasoil_rev.push(d1);
                krog_gasoil_rev.push(d2);
             }
          }
          if(!is_asc(sg_gasoil_rev))
          {
             valid1_cg  = false;
             mensaje = "Gas Oil sg data must be ascending sorted";
          }
          if(!is_asc(krg_gasoil_rev))
          {
             valid1_cg  = false;
             mensaje = "Gas Oil krg data must be ascending sorted";
          }
          if(!is_desc(krog_gasoil_rev))
          {
             valid1_cg =false;
             mensaje="Gas Oil kro must be descending sorted";
          }
          else
          {
             if(krog_gasoil_rev[krog_gasoil_rev.length-1]!=0)
             {
                valid1_cg =false;
                mensaje="The last Krog data must be 0 at Gas Oil data";
             }
          }

          //Validación ordenamiento y rango de valores en tabla pvt Condensate Gas
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


          if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3 ==="" || d3 == null) || (d4==="" || d4 == null) && (d5==="" || d5 == null) && (d6 ==="" || d6 == null) && (d7 ==="" || d7 == null) && (d8==="" || d8 == null) && (d9==="" || d9 == null) && (d10 ==="" || d10 == null) || (d11==="" || d11 == null) && (d12==="" || d12 == null) && (d13 ==="" || d13 == null))
          {
             valid2_cg =false;
             mensaje="Check your PVT data for Condensate Gas IPR, there's nothing there...";
          }

          for (var i = 0; i<pvt_cg_data.length; i++)
          {
             d0 = pvt_cg_data[i][0];
             d1 = pvt_cg_data[i][1];
             d2 = pvt_cg_data[i][2];
             d3 = pvt_cg_data[i][3];
             d4 = pvt_cg_data[i][4];
             d5 = pvt_cg_data[i][5];
             d6 = pvt_cg_data[i][6];

             if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!="") || (d3 != null && d3!="") || (d4 != null && d4!="") || (d5 != null && d5!="") || (d6 != null && d6!=""))
             {

                if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2===""|| d3==null || d3==="" || d4==null || d4==="" || d5==null || d5==="" || d6==null || d6==="")
                {
                   valid2_cg =false;
                   mensaje="Check your PVT data for Condensate Gas IPR, there's some incomplete data";
                }
                if(d1<0 || d2<0 || d0<0 || d3<0 || d4<0 || d5<0 || d6<0)
                {
                   valid2_cg =false;
                   mensaje = "Your PVT data data must be greather than 0";
                }
                if(d0<14.7)
                {
                   valid2_cg =false;
                   mensaje = "The Pression values at PVT table must be greather than 14.7";
                }
                pressure_pvt_cg_rev.push(d0);
             }
          }

          if(!is_asc(pressure_pvt_cg_rev))
          {
             valid2_cg =false;
             mensaje="The PVT pressures must be ascending sorted";
          }
          if(!verifyPVT_cg(pressure_pvt_cg_rev))
          {
             valid2_cg =false;
             mensaje="Check your PVT pressures data, the saturation pressure value is not included in pressures column";
          }


          //Validación drop out curve
          d0 = dropout_cg_data[0][0];
          d1 = dropout_cg_data[0][1];
          d2 = dropout_cg_data[1][0];
          d3 = dropout_cg_data[2][1];

          if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3 ==="" || d3 == null))
          {
             valid3_cg =false;
             mensaje="Check your Dropout data for Condensate Gas IPR, there's nothing there...";
          }
          valid = valid1_cg && valid2_cg && valid3_cg;
        } 

        //Normalización de textfields
        if($("#fluido").val()=="1")
        {
            //Rock Properties
            //Permeability Module
            if($("#check_use_permeability_module_o").is(":checked"))
            {
               $("#permeabilidad").val('');
               $("#porosidad").val('');               
               $("#tipo_roca").val('');
               $("#check_rock_oil").val('1');
            }            
   
            if($("#check_calculate_permeability_module_o").is(":checked"))
            {
               $("#modulo_permeabilidad").val('');
               $("#check_rock_oil").val('2');
            }
   
            //Relative permeability data selection
            if($("#check_tabular_rel_perm_o").is(":checked"))
            {
               $("#end_point_kr_aceite_gas").val('');
               $("#saturacion_gas_crit").val('');               
               $("#end_point_kr_gas").val('');
               $("#saturacion_aceite_irred_gas").val('');
               $("#exponente_corey_aceite_gas").val('');
               $("#exponente_corey_gas").val('');
   
               $("#end_point_kr_petroleo").val('');
               $("#saturacion_agua_irred").val('');
               $("#end_point_kr_agua").val('');
               $("#saturacion_aceite_irred").val('');
               $("#exponente_corey_petroleo").val('');
               $("#exponente_corey_agua").val('');

               $("#check_rock2_oil").val('1');
            }
            else
            {
                $("#check_rock2_oil").val('2');
            }     

            if($("#check_corey_rel_perm_o").is(":checked"))
            {
               datos_wateroil = [[0,0,0]];
               datos_gasliquid = [[0,0,0]];
            }   

        }
        else if($("#fluido").val()=="2")
        {
               //Production Data
               if($("#check_skin_g").is(":checked"))
               {
                  $("#gas_rate_g").val('1');
                  $("#bhp_g").val('');
               }
               if($("#check_testpoint_g").is(":checked"))
               {
                  $("#skin_g").val('');
               }
   
               //Rock Properties
               if($("#check_use_permeability_module_g").is(":checked"))
               {
                  $("#abs_perm_text_g").val('');
                  $("#porosity_text_g").val('');
                  $("#rock_type").val('');

                  $("#check_rock_gas").val('1');
                  
               }
               if($("#check_calculate_permeability_module_g").is(":checked"))
               {
                  $("#permeability_module_text_g").val('');

                  $("#check_rock_gas").val('2');
               }
 
        }
        else if($("#fluido").val()=="3")
        {
          //Rock Properties
          //Permeability Module
          if($("#check_use_permeability_module_c_g").is(":checked"))
          {
             $("#permeabilidad_c_g").val('');
             $("#porosidad_c_g").val('');               
             $("#tipo_roca_c_g").val('');
          }            

          if($("#check_calculate_permeability_module_c_g").is(":checked"))
          {
             $("#modulo_permeabilidad_c_g").val('');
          }
        }
        
        //Normalización tablas
        var datos_wateroil_aux = [];
        var datos_gasliquid_aux = [];
        var datos_unidades_hidraulicas_aux = [];

        var datos_pvt_ipr_g_aux = [];

        var go_cg_data_aux = [];
        var pvt_cg_data_aux = [];
        var dropout_cg_data_aux = [];
            
        //Vistas oil    
        //Water oil kr    
        for (var i = 0; i < datos_wateroil.length; i++) 
        {
               d0 = datos_wateroil[i][0];
               d1 = datos_wateroil[i][1];
               d2 = datos_wateroil[i][2];

               if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
               {
                  continue;
               }
               else
               {
                  datos_wateroil_aux.push(datos_wateroil[i]);
               }
        }
        datos_wateroil = datos_wateroil_aux;

        //Gas oil kr 
        for (var i = 0; i < datos_gasliquid.length; i++) 
        {
               d0 = datos_gasliquid[i][0];
               d1 = datos_gasliquid[i][1];
               d2 = datos_gasliquid[i][2];

               if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
               {
                  continue;
               }
               else
               {
                  datos_gasliquid_aux.push(datos_gasliquid[i]);
               }
        }
        datos_gasliquid = datos_gasliquid_aux;

        //PVT oil
        for (var i = 0; i < datos_unidades_hidraulicas.length; i++) 
        {
            d0 = datos_unidades_hidraulicas[i][0];
            d1 = datos_unidades_hidraulicas[i][1];
            d2 = datos_unidades_hidraulicas[i][2];
            d3 = datos_unidades_hidraulicas[i][3];

            if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3==="" || d3 == null))
            {
               continue;
            }
            else
            {
               datos_unidades_hidraulicas_aux.push(datos_unidades_hidraulicas[i]);
            }
        }
        datos_unidades_hidraulicas = datos_unidades_hidraulicas_aux;

        //Vistas gas
        //PVT gas
        for (var i = 0; i < datos_pvt_ipr_g.length; i++) 
        {
            d0 = datos_pvt_ipr_g[i][0];
            d1 = datos_pvt_ipr_g[i][1];
            d2 = datos_pvt_ipr_g[i][2];

            if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
            {
               continue;
            }
            else
            {
               datos_pvt_ipr_g_aux.push(datos_pvt_ipr_g[i]);
            }
        }
        datos_pvt_ipr_g = datos_pvt_ipr_g_aux;

        //Vistas condensate gas
        //Gas oil kr 
        for(var i = 0; i<go_cg_data.length; i++)
        {
          d0 = go_cg_data[i][0];
          d1 = go_cg_data[i][1];
          d2 = go_cg_data[i][2];

          if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
          {
             continue;
          }
          else
          {
             go_cg_data_aux.push(go_cg_data[i]);
          }
        }
        go_cg_data = go_cg_data_aux;

        //PVT condensate gas
        for(var i = 0; i<pvt_cg_data.length; i++)
        {
          d0 = pvt_cg_data[i][0];
          d1 = pvt_cg_data[i][1];
          d2 = pvt_cg_data[i][2];
          d3 = pvt_cg_data[i][3];
          d4 = pvt_cg_data[i][4];
          d5 = pvt_cg_data[i][5];
          d6 = pvt_cg_data[i][6];

          if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
          {
             continue;
          }
          else
          {
             pvt_cg_data_aux.push(pvt_cg_data[i]);
          }
        }
        pvt_cg_data = pvt_cg_data_aux;

        //Drop out 
        for(var i = 0; i<dropout_cg_data.length; i++)
        {
          d0 = dropout_cg_data[i][0];
          d1 = dropout_cg_data[i][1];

          if((d0 ==="" || d0 == null) && (d1==="" || d1 == null))
          {
             continue;
          }
          else
          {
             dropout_cg_data_aux.push(dropout_cg_data[i]);
          }
        }
        dropout_cg_data = dropout_cg_data_aux;

        //Guarda datos tablas
        $("#wateroil_hidden").val(JSON.stringify(datos_wateroil));
        $("#gasliquid_hidden").val(JSON.stringify(datos_gasliquid));

        $('#pvt_gas_ipr').val(JSON.stringify(datos_pvt_ipr_g));
        $("#presiones_table").val(JSON.stringify(datos_unidades_hidraulicas));

        //Datos Condesate gas
        $('#pvt_cg').val(JSON.stringify(pvt_cg_data));
        $('#gas_oil_kr_cg').val(JSON.stringify(go_cg_data));
        $('#dropout_cg').val(JSON.stringify(dropout_cg_data));

        //Fluido
        $("#fluid_select_value").val($("#fluido").val());


        var evt = window.event || arguments.callee.caller.arguments[0];
        if(!valid)
        {
              evt.preventDefault(); 
              alert(mensaje);
        }
        else
        { 
           //evt.preventDefault();
           //alert("ok"); 
           document.getElementById('loading').style.display = 'block';
           form.submit(); 
        }  
       
   
        return false;
    }

   /* *********Sección de creación y población de tablas ********* */
   
   //Vistas gas
   
   //Pvt - Fluid properties
   $excel_tabular_pvt_fluid = $('#excel_tabular_pvt_fluid_g');
   $excel_tabular_pvt_fluid.handsontable({
      data: {!! $pvt_gas_table !!},
      colWidths: [200, 200, 200] ,
      rowHeaders: true, 
          columns: [
   
        {title:"Pressure [psi] ", data: 0,type:'numeric',format:'0.0000'},
        {title:"Gas Viscosity [cp] ",data: 1,type:'numeric',format:'0.0000'},
        {title:"Gas Compressibility Factor", data: 2,type:'numeric',format:'0.0000'}
      ],
      minSpareRows: 1,
      contextMenu: true,
   });
   
   
   //Vistas aceite
   
   //Relative Permeability - Rock Properties
   $excelgasliquid = $('#excelgasliquid');
   $excelwateroil = $('#excelwateroil');
   
   $excelwateroil.handsontable({
      data: {!! $water_table !!}, 
      colWidths: [80, 80, 80] ,
      rowHeaders: true, 
          columns: [
   
        {title:"Sw", data: 0,type:'numeric',format:'0.0000'},
        {title:"Krw",data: 1,type:'numeric',format:'0.0000'},
        {title:"Kro", data: 2,type:'numeric',format:'0.0000'}
      ],
      minSpareRows: 1,
      contextMenu: true,
   });
   
   $excelgasliquid.handsontable({
      data: {!! $gas_table !!}, 
      colWidths: [80, 80, 80] ,
      rowHeaders: true, 
          columns: [
   
        {title:"Sg", data: 0,type:'numeric',format:'0.0000'},
        {title:"Krg",data: 1,type:'numeric',format:'0.0000'},
        {title:"Krog", data: 2,type:'numeric',format:'0.0000'}
      ],
      minSpareRows: 1,
      contextMenu: true,
   });

   //Fluido Properties - PVT
   var myData = {!! $tabla !!};
   container = document.querySelector('#excel_table');
   
   
   var $hot = new Handsontable(container, {
       data: myData,
       type:'numeric',
       format:'0.0000',
       startRows: 5,
       startCols: 3,
       width: 100,
       minSpareCols: 0,
       //always keep at least 1 spare row at the right
       minSpareRows: 1,
       //always keep at least 1 spare row at the bottom,
       rowHeaders: true,
       viewportColumnRenderingOffset: 10,
       colWidths: [100,200,210,150,150],
       colHeaders: ['Pressure[psi]', 'Oil Viscosity [cp]','Oil Volumetric Factor [RB/STB]', "Water Viscosity [cp]"],
       contextMenu: true,
       stretchH:"all"
   });
   curr_hot = $hot;
   $hot.updateSettings({
   width: 750
   });
   $("#excel_table").css("width", "750px");
   $("#excel_table").css("max-width", "750px");
   
   //Vistas gas condensado

   // Gas-Oil table - Condensate gas
   $gas_oil_table_c_g = $("#excelgasliquid_c_g");
   $gas_oil_table_c_g.handsontable({
      data: {!! $ipr_cg_gasoil_table !!}, 
      colWidths: [80, 80, 80] ,
      rowHeaders: true, 
          columns: [

        {title:"Sg", data: 0,type: 'numeric', format: '0[.]0000000'},
        {title:"Krg",data: 1,type: 'numeric', format: '0[.]0000000'},
        {title:"Krog", data: 2,type: 'numeric', format: '0[.]0000000'}
      ],
      minSpareRows: 1,
      contextMenu: true,
   });

   //Pvt Condensate Gas
   $excel_pvt_cg = $('#pvt_c_g');
   $excel_pvt_cg.handsontable({
      data: {!! $ipr_cg_pvt_table !!}, 
      colWidths: [100, 100, 60,100, 100, 60,150] ,
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
      minSpareRows: 1,
      contextMenu: true,
   });

   //Drop out Condensate Gas
   $drop_out_c_g = $('#drop_out_c_g');
   $drop_out_c_g.handsontable({
       data: {!! $ipr_cg_dropout_table !!}, 
       colWidths: [130, 130] ,
       rowHeaders: true, 
           columns: [

         {title:"Pressure [psi]", data: 0,type: 'numeric', format: '0[.]0000000'},
         {title:"Liquid Fraction []",data: 1,type: 'numeric', format: '0[.]0000000'}
       ],
       minSpareRows: 1,
       contextMenu: true,
   });


   $("#check_tabular_rel_perm_o").tooltip({
     title : 'Please insert at least two complete rows. All data must be positive. No row must be completely 0. Sw, Krw, Kro and Sg, Krg, Krog values must be between 0 and 1. Sw,Krw and Sg,Krg must be ascending sorted and Kro and Krog must be descending sorted. The first Krw must be 0. The last Kro and Krog data must be 0'
   });

   /* *********Sección de control de eventos  ********* */
   //Botón run ipr
   $("#well_data").click(function()
   {
      $("#run").hide();
   });
   $("#production_data").click(function()
   {
      $("#run").hide();
   });
   $("#rock_properties").click(function()
   {
      $("#run").hide();
   });
   $("#fluid_properties").click(function()
   {
      $("#run").show();
   });

   //Vistas Gas
   
   //Rock Properties: check "Use permeability Module"
   $("#check_use_permeability_module_g").click(function()
   {
      $("#check_calculate_permeability_module_g").attr('checked', false);
      $(".check_calculate_permeability_g").hide();
      $(".check_use_permeability_g").show();
   });
   
   //Rock properties: Check "Calculate permeability module"
   $("#check_calculate_permeability_module_g").click(function()
   {
      $("#check_use_permeability_module_g").attr('checked', false);
      $(".check_calculate_permeability_g").show();
      $(".check_use_permeability_g").hide();
   });
   
   //Rock properties: Check "Tabular - Relative permeability"
   $("#check_tabular_rel_perm_g").click(function()
   {
      $("#check_corey_rel_perm_g").attr('checked', false);
      $(".use_corey_model_g").hide();
      $(".use_permeability_tables_g").show();
   });
   
   //Rock properties: Check "Corey's Model - Relative permeability"
   $("#check_corey_rel_perm_g").click(function()
   {
      $("#check_tabular_rel_perm_g").attr('checked', false);
      $(".use_corey_model_g").show();
      $(".use_permeability_tables_g").hide();
   });
   
   
   //Vistas Oil
   
   //Rock Properties: check "Use permeability Module"
   $("#check_use_permeability_module_o").click(function()
   {
      $("#check_calculate_permeability_module_o").attr('checked', false);
      $(".check_calculate_permeability_o").hide();
      $(".check_use_permeability_o").show();
   });
   
   //Rock properties: Check "Calculate permeability module"
   $("#check_calculate_permeability_module_o").click(function()
   {
      $("#check_use_permeability_module_o").attr('checked', false);
      $(".check_calculate_permeability_o").show();
      $(".check_use_permeability_o").hide();
   });
   
   //Rock properties: Check "Tabular - Relative permeability"
   $("#check_tabular_rel_perm_o").click(function()
   {
      $("#check_corey_rel_perm_o").attr('checked', false);
      $(".use_corey_model_o").hide();
      $(".use_permeability_tables_o").show();
      $("#flag_perm_oil").val("tabular");
   });
   
   //Rock properties: Check "Corey's Model - Relative permeability"
   $("#check_corey_rel_perm_o").click(function()
   {
      $("#check_tabular_rel_perm_o").attr('checked', false);
      $(".use_corey_model_o").show();
      $(".use_permeability_tables_o").hide();
      $("#flag_perm_oil").val("corey");
   });
   
   
   //Vistas Gas condensado
   //Relative permeability vistas Condensate Gas

   //Rock Properties: check "Use permeability Module"
   if($("#check_use_permeability_module_c_g").is(":checked"))
   {
      $("#check_calculate_permeability_module_c_g").attr('checked', false);
      $(".check_calculate_permeability_c_g").hide();
      $(".check_use_permeability_c_g").show();
   }

   //Rock properties: Check "Calculate permeability module"
   if($("#check_calculate_permeability_module_c_g").is(":checked"))
   {
      $("#check_use_permeability_module_c_g").attr('checked', false);
      $(".check_calculate_permeability_c_g").show();
      $(".check_use_permeability_c_g").hide();
   }

   //Rock Properties: check "Use permeability Module"
   $("#check_use_permeability_module_c_g").click(function()
   {
      $("#check_calculate_permeability_module_c_g").attr('checked', false);
      $(".check_calculate_permeability_c_g").hide();
      $(".check_use_permeability_c_g").show();
   });

   //Rock properties: Check "Calculate permeability module"
   $("#check_calculate_permeability_module_c_g").click(function()
   {
      $("#check_use_permeability_module_c_g").attr('checked', false);
      $(".check_calculate_permeability_c_g").show();
      $(".check_use_permeability_c_g").hide();
   });

   /* *********Sección de recarga de página********* */

   //Checks
   //Rock properties - Oil 
   if($("#permeabilidad").val()=='')
   {
      $("#check_use_permeability_module_o").prop('checked', true);
   }
   else if($("#modulo_permeabilidad").val()=='')
   {
      $("#check_calculate_permeability_module_o").prop('checked', true);
   }

   if($("#end_point_kr_aceite_gas").val()=='')
   {
      $("#check_tabular_rel_perm_o").prop('checked', true);
   }
   else
   {
      $("#check_corey_rel_perm_o").prop('checked', true);
   }


   //Rock properties . Gas
   if($("#abs_perm_text_g").val()=='')
   {
      $("#check_use_permeability_module_g").prop('checked', true);
   }
   else if($("#permeability_module_text_g").val()=='')
   {
      $("#check_calculate_permeability_module_g").prop('checked', true);
   }


   //Rock properties - Condensate Gas
   if($("#modulo_permeabilidad_c_g").val()=='')
   {
      $("#check_calculate_permeability_module_c_g").prop('checked', true);
   }
   else if($("#permeabilidad_c_g").val()=='')
   {
      $("#check_use_permeability_module_c_g").prop('checked', true);
   }
   else
   {
    $("#check_use_permeability_module_c_g").prop('checked', true);
   }

   //Errores
   $("#myModal").modal('show');

   if($("#fluido").val() == "1"){
      $(".gas_data").hide();
      $(".oil_data").show();
      $(".condensate_gas_data").hide();
   }
   else if($("#fluido").val() == "2")
   {
      $(".gas_data").show();
      $(".oil_data").hide();
      $(".condensate_gas_data").hide();
   }
   else if($("#fluido").val() == "3")
   {
      $(".gas_data").hide();
      $(".oil_data").hide();
      $(".condensate_gas_data").show();
   }
   //Rock Properties: check "Use permeability Module"
   if($("#check_use_permeability_module_g").is(":checked"))
   {
      $("#check_calculate_permeability_module_g").attr('checked', false);
      $(".check_calculate_permeability_g").hide();
      $(".check_use_permeability_g").show();
   };

   //Rock properties: Check "Calculate permeability module"
   if($("#check_calculate_permeability_module_g").is(":checked"))
   {
      $("#check_use_permeability_module_g").attr('checked', false);
      $(".check_calculate_permeability_g").show();
      $(".check_use_permeability_g").hide();
   }

   //Rock properties: Check "Tabular - Relative permeability"
   if($("#check_tabular_rel_perm_g").is(":checked"))
   {
      $("#check_corey_rel_perm_g").attr('checked', false);
      $(".use_corey_model_g").hide();
      $(".use_permeability_tables_g").show();
   }

   //Rock properties: Check "Corey's Model - Relative permeability"
   if($("#check_corey_rel_perm_g").is(":checked"))
   {
      $("#check_tabular_rel_perm_g").attr('checked', false);
      $(".use_corey_model_g").show();
      $(".use_permeability_tables_g").hide();
   }


   //Vistas Oil

   //Rock Properties: check "Use permeability Module"
   if($("#check_use_permeability_module_o").is(":checked"))
   {
      $("#check_calculate_permeability_module_o").attr('checked', false);
      $(".check_calculate_permeability_o").hide();
      $(".check_use_permeability_o").show();
   }

   //Rock properties: Check "Calculate permeability module"
   if($("#check_calculate_permeability_module_o").is(":checked"))
   {
      $("#check_use_permeability_module_o").attr('checked', false);
      $(".check_calculate_permeability_o").show();
      $(".check_use_permeability_o").hide();
   }

   //Rock properties: Check "Tabular - Relative permeability"
   if($("#check_tabular_rel_perm_o").is(":checked"))
   {
      $("#check_corey_rel_perm_o").attr('checked', false);
      $(".use_corey_model_o").hide();
      $(".use_permeability_tables_o").show();
      $("#flag_perm_oil").val("tabular");
   }

   //Rock properties: Check "Corey's Model - Relative permeability"
   if($("#check_corey_rel_perm_o").is(":checked"))
   {
      $("#check_tabular_rel_perm_o").attr('checked', false);
      $(".use_corey_model_o").show();
      $(".use_permeability_tables_o").hide();
      $("#flag_perm_oil").val("corey");
   }

    /* *********Sección de eventos de validación de pestañas ********* */
    fluido = $("#fluido").val();

    //Flags para validación de secciones y cambio de pestañas
    var wd1 = true; 
    var wd2 = true; 
    var pd1 = true; 
    var rp1 = true; 
    var rp2 = true; 
    var rp3 = true; 
    var fp1 = true; 
    var fp2 = true; 
    
    if(fluido=="1")
    {
        var datos_unidades_hidraulicas = order_pvt(curr_hot.getData());
        var datos_wateroil = $excelwateroil.handsontable('getData');
        var datos_gasliquid = $excelgasliquid.handsontable('getData');

        //Well Data
        if(!$("#radio_pozo").val()||!$("#radio_drenaje_yac").val()||!$("#presion_yacimiento").val())
        {
           wd1 = false;
        }
        else
        {
           wd1 = true;
        }
        //Production Data
        if(!$("#tasa_flujo").val()||!$("#presion_fondo").val()||!$("#bsw").val())
        {
           pd1 = false;
        }
        else
        {
           pd1 = true;
        }
        //Rock Properties
        if(!$("#presion_inicial").val()||!$("#permeabilidad_abs_ini").val()||!$("#espesor_reservorio").val())
        {
           rp1 = false;
        }
        else
        {
           rp1 = true;
        }
        if($("#check_use_permeability_module_o").is(":checked"))
        {
         if(!$("#modulo_permeabilidad").val())
         {
            rp2 = false;
         }
         else
         {
            rp2 = true;
         }
        }
        else if($("#check_calculate_permeability_module_o").is(":checked"))
        {
         if(!$("#permeabilidad").val()||!$("#porosidad").val()||!$("#tipo_roca").val())
         {
            rp2 = false;
         }
         else
         {
            rp2 = true;
         }
        }
        else
        {
           rp2 = false;
        }
        if($("#check_tabular_rel_perm_o").is(":checked"))
        {
         valid = true;
         //Water oil
         d0 = datos_wateroil[0][0];
         d1 = datos_wateroil[0][1];
         d2 = datos_wateroil[0][2];

         if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
         {
            valid=false;
         }

         for (var i = 0; i<datos_wateroil.length; i++)
         {
            d0 = datos_wateroil[i][0];
            d1 = datos_wateroil[i][1];
            d2 = datos_wateroil[i][2];
            if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
            {

               if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
               {
                  valid=false;
               }
            }
         }
         //Gas-oil
         d0 = datos_gasliquid[0][0];
         d1 = datos_gasliquid[0][1];
         d2 = datos_gasliquid[0][2];

         if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
         {
            valid=false;
         }

         for (var i = 0; i<datos_gasliquid.length; i++)
         {
            d0 = datos_gasliquid[i][0];
            d1 = datos_gasliquid[i][1];
            d2 = datos_gasliquid[i][2];
            if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
            {

               if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
               {
                  valid=false;
               }
            }
         }

         if(!valid)
         {
            rp3=false;
         }
         else
         {
            rp3=true;
         }
        }
        else if($("#check_corey_rel_perm_o").is(":checked"))
        {
           if(!$("#end_point_kr_aceite_gas").val()||!$("#saturacion_gas_crit").val()||!$("#end_point_kr_gas").val()||!$("#saturacion_aceite_irred_gas").val()||!$("#exponente_corey_aceite_gas").val()||!$("#exponente_corey_gas").val()||!$("#end_point_kr_petroleo").val()||!$("#saturacion_agua_irred").val()|!$("#end_point_kr_agua").val()|!$("#saturacion_aceite_irred").val()|!$("#exponente_corey_petroleo").val()|!$("#exponente_corey_agua").val())
            {
                rp3=false;
            }
            else
            {
              rp3=true;
            }
        }
        else
        {
          rp3=false;
        }

      //Fluid Properties
      if(!$("#presion_saturacion").val())
      {  
         fp1 = false;
      }
      else
      {
         fp1 = true;
      }

      //PVT Oil
      d0 = datos_unidades_hidraulicas[0][0];
      d1 = datos_unidades_hidraulicas[0][1];
      d2 = datos_unidades_hidraulicas[0][2];
      d3 = datos_unidades_hidraulicas[0][3];
      d4 = datos_unidades_hidraulicas[1][0];
      d5 = datos_unidades_hidraulicas[1][1];
      d6 = datos_unidades_hidraulicas[1][2];
      d7 = datos_unidades_hidraulicas[1][3];
      fp2 = true;
      if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3 ==="" || d3 == null) || (d4==="" || d4 == null) && (d5==="" || d5 == null) && (d6 ==="" || d6 == null) && (d7==="" || d7 == null))
      {
         fp2 = false;
      }

      for (var i = 0; i<datos_unidades_hidraulicas.length; i++)
      {
         d0 = datos_unidades_hidraulicas[i][0];
         d1 = datos_unidades_hidraulicas[i][1];
         d2 = datos_unidades_hidraulicas[i][2];
         d3 = datos_unidades_hidraulicas[i][3];
         if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!="") || (d3 != null && d3!=""))
         {

            if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2===""|| d3==null || d3==="" )
            {
               fp2 = false;
            }
            if(d1<0 || d2<0 || d0<0 || d3<0)
            {
               fp2=false;
            }
            if(d0<14.7)
            {
               fp2=false;
            }
         }
      }

      if(wd1)
      {
         document.getElementById('well_data').style.color = "#000000";
      }
      else
      {
         document.getElementById('well_data').style.color = "#DF0101";
      }

      if(pd1)
      {
         document.getElementById('production_data').style.color = "#000000";
      }
      else
      {
         document.getElementById('production_data').style.color = "#DF0101";
      }

      if(rp1&&rp2&&rp3)
      {
         document.getElementById('rock_properties').style.color = "#000000";
      }
      else
      {
         document.getElementById('rock_properties').style.color = "#DF0101";
      }

      if(fp1&&fp2)
      {
         document.getElementById('fluid_properties').style.color = "#000000";
      }
      else
      {
         document.getElementById('fluid_properties').style.color = "#DF0101";
      }
    }
    else if(fluido=="2")
    {
        var datos_pvt_ipr_g = order_pvt($excel_tabular_pvt_fluid.handsontable('getData'));

      //Well Data
      if(!$("#radio_pozo").val()||!$("#radio_drenaje_yac").val()||!$("#presion_yacimiento").val())
      {
         wd1 = false;
      }
      else
      {
         wd1 = true;
      }
      //Production Data
      if(!$("#gas_rate_g").val()||!$("#bhp_g").val())
      {
         pd1=false;
      }
      else
      {
         pd1=true;
      }
      //Rock Properties
      if(!$("#init_res_press_text_g").val()||!$("#abs_perm_init_text_g").val()||!$("#net_pay_text_g").val())
      {
         rp1=false;
      }
      else
      {
         rp1=true;
      }
      if($("#check_use_permeability_module_g").is(":checked"))
      {
         if(!$("#permeability_module_text_g").val())
         {
            rp2=false;
         }
         else
         {
            rp2=true;
         }
      }
      else if($("#check_calculate_permeability_module_g").is(":checked"))
      {
         if(!$("#abs_perm_text_g").val()||!$("#porosity_text_g").val()||!$("#rock_type").val())
         {
            rp2=false;
         }
         else
         {
            rp2=true;
         }
      }
      else
      {
         rp2=false;
      }
      //Fluid Properties
      if(!$("#temperature_text_g").val())
      {
         fp1=false;
      }
      else
      {
         fp1=true;
      }

      //PVT Gas
      d0 = datos_pvt_ipr_g[0][0];
      d1 = datos_pvt_ipr_g[0][1];
      d2 = datos_pvt_ipr_g[0][2];
      d3 = datos_pvt_ipr_g[1][0];
      d4 = datos_pvt_ipr_g[1][1];
      d5 = datos_pvt_ipr_g[1][2];
      fp2 = true;

      if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3 ==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
      {
         fp2=false;
      }

      for (var i = 0; i<datos_pvt_ipr_g.length; i++)
      {
         d0 = datos_pvt_ipr_g[i][0];
         d1 = datos_pvt_ipr_g[i][1];
         d2 = datos_pvt_ipr_g[i][2];
         if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
         {

            if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
            {
               fp2=false;
            }
         }

      }


      if(wd1)
      {
         document.getElementById('well_data').style.color = "#000000";
      }
      else
      {
         document.getElementById('well_data').style.color = "#DF0101";
      }

      if(pd1)
      {
         document.getElementById('production_data').style.color = "#000000";
      }
      else
      {
         document.getElementById('production_data').style.color = "#DF0101";
      }

      if(rp1&&rp2)
      {
         document.getElementById('rock_properties').style.color = "#000000";
      }
      else
      {
         document.getElementById('rock_properties').style.color = "#DF0101";
      }

      if(fp1&&fp2)
      {
         document.getElementById('fluid_properties').style.color = "#000000";
      }
      else
      {
         document.getElementById('fluid_properties').style.color = "#DF0101";
      }
    }
    else if(fluido == "3")
    {
     //Well data
     if(!$("#radio_pozo").val()||!$("#radio_drenaje_yac").val()||!$("#presion_yacimiento").val())
     {
        wd1 = false;
     }

     //Production Data
     if(!$("#gas_rate_c_g").val()||!$("#bhp_c_g").val())
     {
        pd1 = false;
     }

     //Rock Properties
     if(!$("#presion_inicial_c_g").val()||!$("#permeabilidad_abs_ini_c_g").val()||!$("#espesor_reservorio_c_g").val())
     {
        rp1 = false;
     }

     if($("#check_use_permeability_module_c_g").is(":checked"))
     {
        if(!$("#modulo_permeabilidad_c_g").val())
        {
           rp2 = false;
        }

     }
     else if($("#check_calculate_permeability_module_c_g").is(":checked"))
     {
        if(!$("#permeabilidad_c_g").val()||!$("#porosidad_c_g").val()||!$("#tipo_roca_c_g").val())
        {
           rp2 = false;
        }
     }
     else
     {
        rp2 = false;
     }

     //Fluid properties
     if(!$("#presion_saturacion_c_g").val()||!$("#gor_c_g").val())
     {
        fp1 = false;
     }

     if(wd1)
     {
        document.getElementById('well_data').style.color = "#000000";
     }
     else
     {
        document.getElementById('well_data').style.color = "#DF0101";
     }

     if(pd1)
     {
        document.getElementById('production_data').style.color = "#000000";
     }
     else
     {
        document.getElementById('production_data').style.color = "#DF0101";
     }

     if(rp1&&rp2)
     {
        document.getElementById('rock_properties').style.color = "#000000";
     }
     else
     {
        document.getElementById('rock_properties').style.color = "#DF0101";
     }

     if(fp1)
     {
        document.getElementById('fluid_properties').style.color = "#000000";
     }
     else
     {
        document.getElementById('fluid_properties').style.color = "#DF0101";
     }
    }

    $(".nav.nav-tabs li").on("click", function()
    {
        fluido = $("#fluido").val();

        if(fluido=="1")
        {
            var datos_unidades_hidraulicas = order_pvt(curr_hot.getData());
            var datos_wateroil = $excelwateroil.handsontable('getData');
            var datos_gasliquid = $excelgasliquid.handsontable('getData');
            //Well Data
            if(!$("#radio_pozo").val()||!$("#radio_drenaje_yac").val()||!$("#presion_yacimiento").val())
            {
               wd1 = false;
            }
            else
            {
               wd1 = true;
            }

            //Production Data
            if(!$("#tasa_flujo").val()||!$("#presion_fondo").val()||!$("#bsw").val())
            {
               pd1 = false;
            }
            else
            {
               pd1 = true;
            }

            //Rock Properties
            if(!$("#presion_inicial").val()||!$("#permeabilidad_abs_ini").val()||!$("#espesor_reservorio").val())
            {
               rp1 = false;
            }
            else
            {
               rp1 = true;
            }
            if($("#check_use_permeability_module_o").is(":checked"))
            {
               if(!$("#modulo_permeabilidad").val())
               {
                  rp2 = false;
               }
               else
               {
                  rp2 = true;
               }
            }
            else if($("#check_calculate_permeability_module_o").is(":checked"))
            {
               if(!$("#permeabilidad").val()||!$("#porosidad").val()||!$("#tipo_roca").val())
               {
                  rp2 = false;
               }
               else
               {
                  rp2 = true;
               }
            }
            else
            {
               rp2 = false;
            }
            if($("#check_tabular_rel_perm_o").is(":checked"))
            {
               rp3 = true;
               //Water oil
               d0 = datos_wateroil[0][0];
               d1 = datos_wateroil[0][1];
               d2 = datos_wateroil[0][2];
               d3 = datos_wateroil[1][0];
               d4 = datos_wateroil[1][1];
               d5 = datos_wateroil[1][2];

               if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
               {
                  rp3=false;
               }

               for (var i = 0; i<datos_wateroil.length; i++)
               {
                  d0 = datos_wateroil[i][0];
                  d1 = datos_wateroil[i][1];
                  d2 = datos_wateroil[i][2];
                  if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
                  {

                     if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
                     {
                        rp3=false;
                     }
                  }
               }

               //Gas-oil
               d0 = datos_gasliquid[0][0];
               d1 = datos_gasliquid[0][1];
               d2 = datos_gasliquid[0][2];
               d3 = datos_gasliquid[1][0];
               d4 = datos_gasliquid[1][1];
               d5 = datos_gasliquid[1][2];

               if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
               {
                  rp3=false;
               }

               for (var i = 0; i<datos_gasliquid.length; i++)
               {
                  d0 = datos_gasliquid[i][0];
                  d1 = datos_gasliquid[i][1];
                  d2 = datos_gasliquid[i][2];
                  if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
                  {
                     if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
                     {
                        rp3=false;
                     }
                  }
               }
            }
            else if($("#check_corey_rel_perm_o").is(":checked"))
            {
               if(!$("#end_point_kr_aceite_gas").val()||!$("#saturacion_gas_crit").val()||!$("#end_point_kr_gas").val()||!$("#saturacion_aceite_irred_gas").val()||!$("#exponente_corey_aceite_gas").val()||!$("#exponente_corey_gas").val()||!$("#end_point_kr_petroleo").val()||!$("#saturacion_agua_irred").val()|!$("#end_point_kr_agua").val()|!$("#saturacion_aceite_irred").val()|!$("#exponente_corey_petroleo").val()|!$("#exponente_corey_agua").val())
               {
                  rp3=false;
               }
               else
               {
                  rp3=true;
               }
            }
            else
            {
               rp3=false;
            }
   
            //Fluid Properties
            if(!$("#presion_saturacion").val())
            {  
               fp1 = false;
            }
            else
            {
               fp1 = true;
            }

            //PVT Oil
            d0 = datos_unidades_hidraulicas[0][0];
            d1 = datos_unidades_hidraulicas[0][1];
            d2 = datos_unidades_hidraulicas[0][2];
            d3 = datos_unidades_hidraulicas[0][3];
            d4 = datos_unidades_hidraulicas[1][0];
            d5 = datos_unidades_hidraulicas[1][1];
            d6 = datos_unidades_hidraulicas[1][2];
            d7 = datos_unidades_hidraulicas[1][3];

            fp2 = true;

            if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3 ==="" || d3 == null) || (d4==="" || d4 == null) && (d5==="" || d5 == null) && (d6 ==="" || d6 == null) && (d7==="" || d7 == null))
            {
               fp2 = false;
            }

            for (var i = 0; i<datos_unidades_hidraulicas.length; i++)
            {
               d0 = datos_unidades_hidraulicas[i][0];
               d1 = datos_unidades_hidraulicas[i][1];
               d2 = datos_unidades_hidraulicas[i][2];
               d3 = datos_unidades_hidraulicas[i][3];
               if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!="") || (d3 != null && d3!=""))
               {

                  if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2===""|| d3==null || d3==="" )
                  {
                     fp2 = false;
                  }
               }
            }

            if(wd1)
            {
               document.getElementById('well_data').style.color = "#000000";
            }
            else
            {
               document.getElementById('well_data').style.color = "#DF0101";
            }

            if(pd1)
            {
               document.getElementById('production_data').style.color = "#000000";
            }
            else
            {
               document.getElementById('production_data').style.color = "#DF0101";
            }

            if(rp1&&rp2&&rp3)
            {
               document.getElementById('rock_properties').style.color = "#000000";
            }
            else
            {
               document.getElementById('rock_properties').style.color = "#DF0101";
            }

            if(fp1&&fp2)
            {
               document.getElementById('fluid_properties').style.color = "#000000";
            }
            else
            {
               document.getElementById('fluid_properties').style.color = "#DF0101";
            }
        }
        else if(fluido=="2")
        {
            var datos_pvt_ipr_g = order_pvt($excel_tabular_pvt_fluid.handsontable('getData'));
            //Well Data
            if(!$("#radio_pozo").val()||!$("#radio_drenaje_yac").val()||!$("#presion_yacimiento").val())
            {
               wd1 = false;
            }
            else
            {
               wd1 = true;
            }
            //Production Data
            if(!$("#gas_rate_g").val()||!$("#bhp_g").val())
            {
               pd1=false;
            }
            else
            {
               pd1=true;
            }
            //Rock Properties
            if(!$("#init_res_press_text_g").val()||!$("#abs_perm_init_text_g").val()||!$("#net_pay_text_g").val())
            {
               rp1=false;
            }
            else
            {
               rp1=true;
            }
            if($("#check_use_permeability_module_g").is(":checked"))
            {
               if(!$("#permeability_module_text_g").val())
               {
                  rp2=false;
               }
               else
               {
                  rp2=true;
               }
            }
            else if($("#check_calculate_permeability_module_g").is(":checked"))
            {
               if(!$("#abs_perm_text_g").val()||!$("#porosity_text_g").val()||!$("#rock_type").val())
               {
                  rp2=false;
               }
               else
               {
                  rp2=true;
               }
            }
            else
            {
               rp2=false;
            }
            //Fluid Properties
            if(!$("#temperature_text_g").val())
            {
               fp1=false;
            }
            else
            {
               fp1=true;
            }

            //PVT Gas
            d0 = datos_pvt_ipr_g[0][0];
            d1 = datos_pvt_ipr_g[0][1];
            d2 = datos_pvt_ipr_g[0][2];
            d3 = datos_pvt_ipr_g[1][0];
            d4 = datos_pvt_ipr_g[1][1];
            d5 = datos_pvt_ipr_g[1][2];
            fp2 = true;

            if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) || (d3 ==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
            {
               fp2=false;
            }

            for (var i = 0; i<datos_pvt_ipr_g.length; i++)
            {
               d0 = datos_pvt_ipr_g[i][0];
               d1 = datos_pvt_ipr_g[i][1];
               d2 = datos_pvt_ipr_g[i][2];
               if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
               {

                  if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
                  {
                     fp2=false;
                  }
               }

            }

            if(wd1)
            {
               document.getElementById('well_data').style.color = "#000000";
            }
            else
            {
               document.getElementById('well_data').style.color = "#DF0101";
            }

            if(pd1)
            {
               document.getElementById('production_data').style.color = "#000000";
            }
            else
            {
               document.getElementById('production_data').style.color = "#DF0101";
            }

            if(rp1&&rp2)
            {
               document.getElementById('rock_properties').style.color = "#000000";
            }
            else
            {
               document.getElementById('rock_properties').style.color = "#DF0101";
            }

            if(fp1&&fp2)
            {
               document.getElementById('fluid_properties').style.color = "#000000";
            }
            else
            {
               document.getElementById('fluid_properties').style.color = "#DF0101";
            }
        }
        else if(fluido == "3")
        {

           //Well data
           if(!$("#radio_pozo").val()||!$("#radio_drenaje_yac").val()||!$("#presion_yacimiento").val())
           {
              wd1 = false;
           }
           else
           {
             wd1 = true;
           }

           //Production Data
           if(!$("#gas_rate_c_g").val()||!$("#bhp_c_g").val())
           {
              pd1 = false;
           }
           else
           {
             pd1 = true;
           }

           //Rock Properties
           if(!$("#presion_inicial_c_g").val()||!$("#permeabilidad_abs_ini_c_g").val()||!$("#espesor_reservorio_c_g").val())
           {
              rp1 = false;
           }
           else
           {
             rp1 = true;
           }

           if($("#check_use_permeability_module_c_g").is(":checked"))
           {
              if(!$("#modulo_permeabilidad_c_g").val())
              {
                 rp2 = false;
              }
              else
              {
                rp2 = true;
              }

           }
           else if($("#check_calculate_permeability_module_c_g").is(":checked"))
           {
              if(!$("#permeabilidad_c_g").val()||!$("#porosidad_c_g").val()||!$("#tipo_roca_c_g").val())
              {
                 rp2 = false;
              }
              else
              {
                rp2 = true;
              }
           }
           else
           {
              rp2 = false;
           }

           //Fluid properties
           if(!$("#presion_saturacion_c_g").val()||!$("#gor_c_g").val())
           {
              fp1 = false;
           }
           else
           {
             fp1 = true;
           }

           if(wd1)
           {
              document.getElementById('well_data').style.color = "#000000";
           }
           else
           {
              document.getElementById('well_data').style.color = "#DF0101";
           }

           if(pd1)
           {
              document.getElementById('production_data').style.color = "#000000";
           }
           else
           {
              document.getElementById('production_data').style.color = "#DF0101";
           }

           if(rp1&&rp2)
           {
              document.getElementById('rock_properties').style.color = "#000000";
           }
           else
           {
              document.getElementById('rock_properties').style.color = "#DF0101";
           }

           if(fp1)
           {
              document.getElementById('fluid_properties').style.color = "#000000";
           }
           else
           {
              document.getElementById('fluid_properties').style.color = "#DF0101";
           }
        }
    });

</script>
