    <!-- HandsOnTable JS -->
    <script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
    <!-- HandsOnTable CSS -->
    <link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
    <!-- Libreria de graficación -->
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
    
    <script type="text/javascript">   
        function draw_again(data)
        {
            ser = new Array();
            var tamano = data.etiquetas_ejes.length;
            for (var i = 0; i < tamano; i++) {
                if(data.etiquetas_ejes[i] == "Production Data") {
                    ser.push({name: data.etiquetas_ejes[i], data: data.ejes_dobles[i], marker:{enabled:true} });
                } else {
                    ser.push({name: data.etiquetas_ejes[i], data: data.ejes_dobles[i]});
                }
            }

            $('#grafica').highcharts({
                title: {
                    text: 'Bottomhole Flowing Pressure Vs. {!! $IPR->fluido == 1 ? "Oil" : "Gas" !!} Rate',
                x: -20 //center
            },
            credits: {
                enabled: false
            },
            
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br />',
                pointFormat: 'x = {point.x}, y = {point.y}'
            },
            xAxis: {
                title: {
                    text: '{!! $IPR->fluido == 1 ? "Oil Rate [bbl/day]" : "Gas Rate [MMscf/day]" !!}'
                },
            },
            yAxis: {
                title: {
                    text: 'Bottomhole Flowing Pressure [psi]'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                labels: {
                    format: '{value:,.0f}'
                }
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                }
            },
            series: ser
        });   
        }

        function draw_disaggregation(data)
        {
            ser = new Array();
            var tamano = data.etiquetas_ejes.length;
            for (var i = 0; i < tamano; i++) 
            {
                ser.push({name: data.etiquetas_ejes[i], data: data.ejes_dobles[i]});
            }

            $('#grafica_dis').highcharts({
                title: {
                    text: 'Bottomhole Flowing Pressure Vs. {!! $IPR->fluido == 1 ? "Oil" : "Gas" !!} Rate',
                x: -20 //center
            },
            credits: {
                enabled: false
            },
            
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br />',
                pointFormat: 'x = {point.x}, y = {point.y}'
            },
            xAxis: {
                title: {
                    text: '{!! $IPR->fluido == 1 ? "Oil Rate [bbl/day]" : "Gas Rate [MMscf/day]" !!}'
                },
            },
            yAxis: {
                title: {
                    text: 'Bottomhole Flowing Pressure [psi]'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                labels: {
                    format: '{value:,.0f}'
                }
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                }
            },
            series: ser});

            document.getElementById('loading').style.display = 'none';
        }
        
        function goBack() {
            location.href = '../IPREdit';
        }

        function des_sens(array, roca)
        { 
            sensibi_desa = [];
            contador=0;
            if (roca == 0){
                return 0
            }
            else if (roca == 1){
                a = 0.000809399;
                b = -0.986179237;
            }
            else if (roca == 2){
                a = 0.000433696;
                b = -0.587596095;
            }
            else if (roca == 3){
                a = 0.000613657;
                b = 0.371958564;
            }
            for (i=0; i < array.length; i =i+2){
                theta2 = (1 - array[i+1])/array[i+1];
                rqi = 0.0314 * Math.sqrt(array[i] / array[i+1]);
                fzi = Math.abs(rqi / theta2);
                pot= Math.pow(fzi,b);
                x = a * (pot);
                sensibi_desa.push(x);
            }
            return sensibi_desa;
        }

        function mostrar(obj)
        {
            var tipo_rock = "{!! isset($tipo_roca)? $tipo_roca : 0 !!}" ;
            tipo = obj[ obj.selectedIndex ].value;
            var node;
            if((obj[ obj.selectedIndex ].value) == "desagregacion" && tipo_rock != 0)
            {
                node = document.getElementById('if_desagregacion');
                document.getElementById('if_otro').style.display = 'none';
                document.getElementById('if_desagregacion').style.display = 'block';
            }
            else if ((obj[ obj.selectedIndex ].value) != "desagregacion")
            {
                node = document.getElementById('if_otro');
                document.getElementById('if_desagregacion').style.display = 'none';
                document.getElementById('if_otro').style.display = 'block';
            }
            node.style.visibility = 'visible';
        }

        function draw()
        {

            $('#grafica').highcharts({
                title: {
                    text: 'Bottomhole Flowing Pressure Vs. {!! $IPR->fluido == 1 ? "Oil" : "Gas" !!} Rate',
                x: -20 //center
            },
            credits: {
                enabled: false
            },
            
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br />',
                pointFormat: 'x = {point.x}, y = {point.y}'
            },
            xAxis: {
                title: {
                    text: '{!! $IPR->fluido == 1 ? "Oil Rate [bbl/day]" : "Gas Rate [MMscf/day]" !!}'
                },
            },
            yAxis: {
                title: {
                    text: 'Bottomhole Flowing Pressure [psi]'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                labels: {
                    format: '{value:,.0f}'
                }
            },
            series: [{
                name: 'Current IPR',
                data:  {!! $data !!},
                marker: {enabled:false} 
            },
            @if(isset($tasa_flujo) && (float)$tasa_flujo != 0.000001 && (float)$presion_fondo != 0.000001)
            {
                name: 'Production Data',
                data:  [[{!! isset($tasa_flujo) ? $tasa_flujo : '{}' !!},{!! $presion_fondo !!}]],
                marker: {enabled:true}
            }, 
            @endif
            {
                name: 'Skin = 0 (ideal)',
                data:  {!! $data_i !!},
                marker: {enabled:false}
            }]
        });
        }

        function disag_sensibility(skins)
        {
            //document.getElementById('loading').style.display = 'block';
            console.log("entre aca");
            var arr_sensibilidad = skins;
            $( ".sensibilidad" ).each(function( index ) 
            {
                arr_sensibilidad.push($( this ).val() );
            });
            
            $.ajax(this.href, {
                url: "{!! url('IPR/sensibility') !!}",
                data: {id_ipr: {!!$IPR->id!!},  FType: "desagregacion", sensibilidad: arr_sensibilidad},
                success: function(data) 
                {
                    console.log("Success");
                    draw_disaggregation(data);
                    
                },
                error: function() 
                {
                    console.log("Fail");
                }

            });
             //form.submit();       
             return false;
         }
         
         @if(isset($skins) and strcmp($skins, "") != 0)
         disag_sensibility(".$skins.");
         @endif
         draw();

         function isNumeric(n) {
          return !isNaN(parseFloat(n)) && isFinite(n);
      }

      function enviar()
      {
        if (tipo == "desagregacion")
        {
            document.getElementById('loading').style.display = 'block';
            arr_sensibilidad = [];
            $( ".sensibilidad" ).each(function( index ) {
                arr_sensibilidad.push($( this ).val() );
            });
            var tipo_rock = "{{ isset($tipo_roca)? $tipo_roca : 0 }}" ;
            array_desagregacion = des_sens(arr_sensibilidad, tipo_rock)
            var sens = "";
            for (var i = 0; i < array_desagregacion.length; i++ )
            {
                sens += "<li class='list-group-item'>"  + "Y value " + (i+1) + ": " +  Math.round(array_desagregacion[i]*100)/100 + "</li>";
            }
            document.getElementById("lista").innerHTML = sens;
            return false; 
        }
        else
        {
            //document.getElementById('loading').style.display = 'block';
            arr_sensibilidad = [];
            valid = true;
            $( ".sensibilidad" ).each(function( index )
            {
                arr_sensibilidad.push($( this ).val() );
            }); 
            for(var i=0;i<arr_sensibilidad.length;i++)
            {
                if(!isNumeric(arr_sensibilidad[i]))
                {
                    valid=false;
                }
            }
            if(valid)
            {
                document.getElementById('loading').style.display = 'block';
                $.ajax(this.href, 
                {

                    url: "{!! url('IPR/sensibility') !!}",
                    data: {id_ipr: {!!$IPR->id!!},  FType: $("#FType").val(), sensibilidad: arr_sensibilidad},
                    success: function(data)
                    {
                        draw_again(data);
                        document.getElementById('loading').style.display = 'none';     
                    },
                    error: function()
                    {
                        document.getElementById('loading').style.display = 'none';   
                        alert("Please verify your sensitivity input");
                    }

                });  
            }
            else
            {
                document.getElementById('loading').style.display = 'none';   
                alert("Please verify your sensitivity input");
            }
            
            return false;
        }
    }

    //Eventos

    //var FType = document.getElementById('FType').value;
    //alert('op'+FType.options[FType.selectedIndex].value);
    $("#FType").change(function() {
      $(".sensibilidad").parent().remove();
  });                           
    

    $( "#add_value" ).click(function() 
    {
      salto = document.createElement('br');
      div = $("<div></div>");
      div.attr("class", "input-group");
      span = $("<span></span>");
      span.attr("class", "input-group-addon");
      input = $("<input/>");
      input.attr("placeholder", "-").attr("class", "form-control sensibilidad").attr("id", "sensibilidad").attr("name", "valor[]");
      input.attr("type", "text");
      span.html("-");
      div.append(input);
      div.append(span);
      div.append(salto);
      $("#value_id2").append(div);
  });

    $( "#add_value_desagregacion" ).click(function()
    {
      salto = document.createElement('br');
      div = $("<div></div>");
      div.attr("class", "input-group");
      div2 = $("<div></div>");
      div2.attr("class", "input-group");
      span = $("<span></span>");
      span.attr("class", "input-group-addon");
      span2 = $("<span></span>");
      span2.attr("class", "input-group-addon");
      input = $("<input/>");
      input2 = $("<input/>");
      input.attr("placeholder", "Porosity").attr("class", "form-control sensibilidad").attr("id", "sensibilidad").attr("name", "valor[]");
      input2.attr("placeholder", "Permeability").attr("class", "form-control sensibilidad").attr("id", "sensibilidad").attr("name", "valor[]");
      input.attr("type", "text");
      input2.attr("type", "text");
      span.html("-");
      span2.html("[mD]");
      div.append(input);
      div2.append(input2);
      div.append(span);  
      div2.append(span2);
      div2.append(salto);
      $("#value_id").append(div,div2);
  });
</script>