<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

//*****/////*****
$(document).ready(function(){
  input_data_profile = $("#inputdata_profile_table").val();
      if(input_data_profile==="")
      {
        var data_aux = [[,,,],[,,,],[,,,],[,,,],[,,,]];
        create_profile_input_data_table(data_aux);
      }
      else
      {
        create_profile_input_data_table(JSON.parse(input_data_profile));
      }
});


//Cargar valores de select en recarga de página
window.onload = function() 
{
    //verifica si esta habilitado cementing
    cementingAvailable();

    var formation = [{!! $scenario->formacionxpozo->formacion_id !!}];
    var interval = [$("#select_interval_general_data").val()];
    var input_data_method = $("#select_input_data").val();
    var preset_function_values = $("#selects_filtration_function").val();

    $("#inputDataMethodSelect").val(input_data_method);
    $("#inputDataMethodSelect").selectpicker('refresh');

    if(isNaN(formation[0])){formation = [];};
    if(isNaN(interval)){interval = [];};

    //Select Input data method
    if(input_data_method == 1)
    {
      input_data_profile = $("#inputdata_profile_table").val();
      if(input_data_profile==="")
      {
        var data_aux = [[,,,],[,,,],[,,,],[,,,],[,,,]];
        create_profile_input_data_table(data_aux);
      }
      else
      {
        create_profile_input_data_table(JSON.parse(input_data_profile));
      }
    }
    else if(input_data_method == 2)
    {
        input_data_intervals = $("#inputdata_intervals_table").val();
        if(input_data_intervals==="")
        {
            var data_byIntervals = [];
            var intervals = $("#intervalSelect").val();
            $.get("{{url('intervalsInfoDrilling')}}",
                {intervals:intervals},
                function(data)
                {
                    $.each(data, function(index,value)
                    {
                        var data_row = [value.nombre,value.porosidad,value.permeabilidad,];
                        data_byIntervals.push(data_row);
                    });
                    create_intervals_input_data_table(data_byIntervals);
                });
        }
        else
        {
            create_intervals_input_data_table(JSON.parse(input_data_intervals));
        }
    }

    //Select intervalos
    /*$.get("{{url('intervalsDrilling')}}", {
            formations: formation
        },
        function(data) {
            $("#intervalSelect").empty();
            $.each(data, function(index, value) {
                $("#intervalSelect").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            $("#intervalSelect").val(interval);
            $("#intervalSelect").selectpicker('refresh');
        }
    );*/

    //General Data
    if(interval.length>0 && !isNaN(interval[0]))
    {
      var data_aux = [];
      var general_data_table = $("#generaldata_table").val();
      if(general_data_table === "")
      {
        $.get("{{url('intervalsInfoDrilling')}}",
          {intervals:interval},
          function(data)
          {
            $.each(data, function(index,value)
            {
              var data_row = [value.nombre, value.top,,value.presion_reservorio,,];
              data_aux.push(data_row);
            });
            create_interval_general_data_table(data_aux);
          });
      }
      else
      {
        data_aux = JSON.parse(general_data_table);
        create_interval_general_data_table(data_aux);
      }
    }

    //Select Filtration Function
    $.get('{{url("filtration_functions_by_formation_id")}}',
      {formation_id: formation[0]},
      function(data)
      {
        $("#filtration_function_select").empty();
        $("#filtration_function_select").append('<option value="disabled" disabled>Please, choose one.</option>');
        $.each(data, function(index, value) {
            $("#filtration_function_select").append('<option value="' + value.id + '">' + value.name + '</option>');
        });

        $('#filtration_function_select').val('disabled');
        $('#filtration_function_select').selectpicker('refresh');
        $('#filtration_function_select').selectpicker('deselectAll');

      });
}

$('#check_available').bind('change init', function() {
  cementingAvailable();
});

function cementingAvailable()
{
  if ($('#check_available').prop('checked')) {
    $("#div_cemeneting_data *").attr('disabled', false); 
  }else{
    $("#div_cemeneting_data *").attr('disabled', true); 
  }
}


//Functions
function json_toarray(json)
{
  if(json!="")
  {
    var array = [];
    var parsed = JSON.parse(json);

    for(var x in parsed)
    {
      array.push(parsed[x]);
    }

    return array;
  }
}

function create_interval_general_data_table(data)
{
    $intervalGeneral_t = $("#intervalsGeneral_t");
    $intervalGeneral_t.handsontable(
    {
        data: data, 
        rowHeaders: true, 
        colWidths: [110, 100, 100, 165, 140, 155],
        columns: 
        [
          {title:"Interval", data: 0, readOnly: true},
          {title:"Top [ft]",data: 1,type: 'numeric', format: '0[.]0000000'},
          {title:"Bottom [ft]",data: 2,type: 'numeric', format: '0[.]0000000'},
          {title:"Reservoir Pressure [psi]",data: 3,type: 'numeric', format: '0[.]0000000'},
          {title:"Hole Diameter [in]",data: 4,type: 'numeric', format: '0[.]0000000'},
          {title:"Drill Pipe Diameter [in]",data: 5,type: 'numeric', format: '0[.]0000000'}
        ],
        minSpareRows: 1,
        contextMenu: true,
    });
}

function create_intervals_input_data_table(data)
{
    $("#averageInput_t").hide();
    $("#byIntervalsInput_t").show();
    $("#profileInput_t").hide();
    $("#profile_g").hide();
    $("#plotProfile").hide();

    $byIntervalsInput_t = $("#byIntervalsInput_t");
    $byIntervalsInput_t.handsontable(
    {
        data: data, 
        rowHeaders: true, 
        colWidths: [150, 100, 120, 150, 165],
        columns: 
        [
          {title:"Interval", data: 0, readOnly: true},
          {title:"Porosity [-]",data: 1,type: 'numeric', format: '0[.]0000000'},
          {title:"Permeability [mD]",data: 2,type: 'numeric', format: '0[.]0000000'},
          {title:"Fracture Intensity [#/ft]",data: 3,type: 'numeric', format: '0[.]0000000'},
          {title:"Irreducible Saturation [-]",data: 4,type: 'numeric', format: '0[.]0000000'}
        ],
        minSpareRows: 1,
        contextMenu: true,
    });
}
function create_profile_input_data_table(data)
{
    $("#averageInput_t").hide();
    $("#byIntervalsInput_t").hide();
    $("#profileInput_t").show();
    $("#profile_g").show();
    $("#plotProfile").show();

    $profileInput_t = $("#profileInput_t");
    $profileInput_t.handsontable(
    {
        data: data, 
        rowHeaders: true, 
        colWidths: [150, 100, 120, 150, 165],
        columns: 
        [
          {title:"Depth [ft]", data: 0, type: 'numeric', format:'0[.]0000000'},
          {title:"Porosity [-]",data: 1,type: 'numeric', format: '0[.]0000000'},
          {title:"Permeability [mD]",data: 2,type: 'numeric', format: '0[.]0000000'},
          {title:"Fracture Intensity [#/ft]",data: 3,type: 'numeric', format: '0[.]0000000'},
          {title:"Irreducible Saturation [-]",data: 4,type: 'numeric', format: '0[.]0000000'}
        ],
        minSpareRows: 1,
        contextMenu: true,
    });
}

function plotProfileData()
{
  var data = $("#profileInput_t").handsontable('getData');
  var data_aux = [];
  //Input Data Profile
  for (var i = 0; i < data.length; i++) 
  {
    d0 = data[i][0];
    d1 = data[i][1];
    d2 = data[i][2];
    d3 = data[i][3];
    d4 = data[i][4];

    if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3 ==="" || d3 == null) && (d4==="" || d4 == null))
    {
       continue;
    }
    else
    {
       data_aux.push(data[i]);
    }
  }
  data = data_aux;

  var depth = [];
  var porosity = [];
  var permeability = [];
  var fracture_intensity = [];
  var irreducible_saturation = [];
  for (var i = 0; i < data.length; i++)
  {
    depth.push(data[i][0]);
    porosity.push(data[i][1]);
    permeability.push(data[i][2]);
    fracture_intensity.push(data[i][3]);
    irreducible_saturation.push(data[i][4]);
  }
  porosity.pop();
  permeability.pop();
  fracture_intensity.pop();
  irreducible_saturation.pop();
  $('#profile_g').highcharts({
        chart: {
            type: 'line',
            zoomType: 'x',
            inverted: true
        },
         title: {
             text: 'Profile Data',
             x: -20 //center
         },
         xAxis: {
          title: {
            text: 'Depth[ft]'
          },
             categories: depth
         },
         yAxis: {
             title: {
                 text: 'Profile Data'
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
             name: 'Porosity [-]',
             data: porosity
         }, {
             name: 'Permeability [mD]',
             data: permeability
         }, {
             name: 'Fracture Intesity [#/ft]',
             data: fracture_intensity
         }, {
             name: 'Irreducible Saturation[-]',
             data: irreducible_saturation
         }]
     });
}
function validate_table(table_name,table_data, start_column)
{
    var message = "";
    var flag = true;
    var number_rows = table_data.length;
    if(number_rows>0)
    {
        var number_columns = table_data[0].length;  
    }
    else
    {
        flag = false;
        message = "The table "+table_name+" is empty. Please check your data";
        return [flag,message];
    }
    console.log(number_columns);
    console.log(table_data);

    for (var i = 0; i < number_rows; i++) 
    {
        var flag_row = true;
        for (var j = start_column; j < number_columns; j++) 
        {
            if(!$.isNumeric(table_data[i][j]))
            {
                flag_row = flag_row && false;
                message = "The data for the table "+table_name+" must be numeric. Please check your data";
            }
            else
            {
                flag_row = flag_row && true;
            }
            if(table_data[i][j] == null || table_data[i][j] === "" )
            {
                flag_row = flag_row && false;
                message = "There's missing information for the table "+table_name+". Please check your data";
            }
            else
            {
                flag_row = flag_row && true;
            }

        }
        flag = flag && flag_row;
    }

    return [flag, message];
}
function verifyDrilling(w_verificate_data)
{

    //var evt = window.event || arguments.callee.caller.arguments[0];
    //evt.preventDefault();

    //Saving tables...
  //General Data
  var generaldata_table = clean_table_data("intervalsGeneral_t");
  validate_return = validate_table("General Data", generaldata_table, 1);
  flag_general_data = validate_return[0]; 

    //Normalización de valores de tablas
    var generaldata_table_aux = [];
    var inputdata_intervals_table_aux = [];
    var inputdata_profile_table_aux = [];

    //Flags para tablas
    var flag_input_method_1 = true;
    var flag_input_method_2 = true;
  var flag_general_data = true;
    var flag_filtration_function = true;
    var flag_message = "";
    var validate_return = [];

  if($("#inputDataMethodSelect").val()=="1")
  {
    //Limpiando datos de tablas
    var inputdata_profile_table = clean_table_data("profileInput_t");

    validate_return = validate_table("Input Data", inputdata_profile_table, 0);
    flag_input_method_1 = validate_return[0]; 
    flag_message = validate_return[1];

  }
    else if($("#inputDataMethodSelect").val()=="2")
    {
    //Limpiando datos de tablas
    var inputdata_intervals_table = clean_table_data("byIntervalsInput_t");

        validate_return = validate_table("Input Data", inputdata_intervals_table, 1);
        flag_input_method_2 = validate_return[0]; 
        flag_message = validate_return[1];
    }

  //Guardando los valores de los selectores
  var select_interval_general_data = $("#intervalSelect").val();
  var select_input_data = $("#inputDataMethodSelect").val();

  $("#select_interval_general_data").val(JSON.stringify(remove_nulls(select_interval_general_data)));
  $("#select_input_data").val(select_input_data);
  $("#select_filtration_function").val($("#filtration_function_select").val());

    if(!flag_general_data)
    {
        flag_message = validate_return[1];
    }

    if(flag_input_method_1 && flag_input_method_2 && flag_general_data)
    {
        //var evt = window.event || arguments.callee.caller.arguments[0];
        //evt.preventDefault();

        //Guardando los datos de tablas validadas y limpiadas en formulario
        $("#generaldata_table").val(JSON.stringify(generaldata_table));
        $("#inputdata_intervals_table").val(JSON.stringify(inputdata_intervals_table));
        $("#inputdata_profile_table").val(JSON.stringify(inputdata_profile_table));
    }
    else
    {
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        alert(flag_message);
    }
}
function sticky_relocate() 
{
   var window_top = $(window).scrollTop();
   var div_top = $('#sticky-anchor').offset().top;
   if (window_top > div_top) {
       $('#sticky').addClass('stick');
   } else {
       $('#sticky').removeClass('stick');
   }
}
function remove_nulls(array)
{   
    //array = array.split(",");
    var array_aux = [];
    for (var i = 0; i<array.length; i++) 
    {
        if(array[i]==null || array[i]=='')
        {
            continue;
        }
        else
        {
            array_aux.push(array[i]);
        }
    }
    return array_aux;
}


function clean_item(item)
{
    if(!isNaN(item))
    {
        return [item];
    }
    else
    {
        var len = item.length;
        if(len>1)
        {
            var cleaned_item = remove_nulls(item.split(","));
            for (var i = 0; i < cleaned_item.length; i++) 
            {
                cleaned_item[i] = parseInt(cleaned_item[i]);
            }
            return cleaned_item;
        }
    }
}
function check_nulls(array_list)
{
  console.log("function");
  console.log(array_list);
  for (var i = 0; i < array_list.length; i++) 
  {
    if(array_list[i]===null)
    {
      console.log("null");
      return false;
    }
  }
  console.log("true null");
  return true;
}

//Llamarla antes de guardar todos los datos de tablas - elmina nulos
function clean_table_data(table_div_id) 
{
    container = $("#" + table_div_id); //Div de la tabla
    var table_data = container.handsontable('getData');
    var cleaned_data = [];

    $.each(table_data, function (rowKey, object) {
        if (!container.handsontable('isEmptyRow', rowKey)) {
            cleaned_data[rowKey] = object;
        }
    });

    return cleaned_data;
}

function calculate_ecd(option)
{
  if($("#intervalSelect").val() == null)
  {
    alert("For calculating the ECD you'll need several data: Hole Diameter and Drill Pipe Diameter (General Data Table), Mud Density, and Pump Rate. ");
  }
  else
  {
    var mud_density = 0;
    var pump_rate = 0;
    var result_div_id = "";
    var general_data_table = clean_table_data("intervalsGeneral_t");
    var filtration_function_id = $("#filtration_function_select").val();
    var yield_point = null;
    var plastic_viscosity = null;
    var ecd = 0;

    $.get("{{url('filtration_function_data')}}",
      {ff_id:filtration_function_id},
      function(data)
      {
        $.each(data, function(index,value)
        {
          yield_point = value.yield_point;
          plastic_viscosity = value.plastic_viscosity;
        });

        if(option == 0)
        {
          mud_density = parseFloat($("#d_mud_density_t").val());
          pump_rate = parseFloat($("#d_pump_rate_t").val());
          result_div_id = "d_equivalent_circulating_density_t";
        }
        else if(option == 1)
        {
          mud_density = parseFloat($("#c_cement_slurry_density_t").val());
          pump_rate = parseFloat($("#c_pump_rate_t").val());
          result_div_id = "c_equivalent_circulating_density_t";
        }

        var hole_diameter_sum = 0;
        var drill_pipe_diameter_sum = 0;
        var general_data_table_length = general_data_table.length;

        for (var i = 0; i < general_data_table_length; i++) 
        {
          hole_diameter_sum += parseFloat(general_data_table[i][4]);  
          drill_pipe_diameter_sum += parseFloat(general_data_table[i][5]);  
        }

        var hole_diameter = hole_diameter_sum / general_data_table_length;
        var drill_pipe_diameter = drill_pipe_diameter_sum / general_data_table_length;
        var annular_velocity = 24.5 * (pump_rate / (Math.pow(hole_diameter, 2) - Math.pow(drill_pipe_diameter, 2)));

        if(mud_density < 13)
        {
          ecd = mud_density + ((0.1 * yield_point)/ (hole_diameter - drill_pipe_diameter));
        }
        else
        {
          ecd = mud_density + ((0.1 / (hole_diameter - drill_pipe_diameter)) * (yield_point + ((plastic_viscosity * annular_velocity) / (300 * (hole_diameter - drill_pipe_diameter)))));
        }
        if(isNaN(ecd))
        {
          alert("For calculating the ECD you'll need several data: Hole Diameter and Drill Pipe Diameter (General Data Table), Mud Density, and Pump Rate. ");
        }
        else
        {
          $("#"+result_div_id).val(ecd);
        }
      });
  }
}

$(function () 
{
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});

//Dinamyc content
//ROP & Total exposure time
$("#d_total_exposure_time_t").change(function(e)
{
    var generaldata_table_data = $("#intervalsGeneral_t").handsontable('getData');
    var tops = [];
    var bottoms = [];
    var texp = $("#d_total_exposure_time_t").val();
    for (var i = 0; i<generaldata_table_data.length; i++) 
    {
        if(generaldata_table_data[i][1] == null || generaldata_table_data[i][1] === '')
        {
            continue;
        }
        else
        {
            tops.push(parseFloat(generaldata_table_data[i][1]));
        }
        if(generaldata_table_data[i][2] == null || generaldata_table_data[i][2] === '')
        {
            continue;
        }
        else
        {
            bottoms.push(parseFloat(generaldata_table_data[i][2]));
        }
    }
    var MDbottom = Math.max.apply(Math,bottoms); 
    var MDtop = Math.min.apply(Math,tops);
    $("#MDbottom").val(MDbottom);
    $("#MDtop").val(MDtop);
    var rop =  (MDbottom-MDtop)/(texp*24);
    $("#d_rop_t").val(rop);
});

$("#d_rop_t").change(function(e)
{
    if($("#d_total_exposure_time_t").val()=='')
    {
        var generaldata_table_data = $("#intervalsGeneral_t").handsontable('getData');
        var tops = [];
        var bottoms = [];
        var d_rop_t = $("#d_rop_t").val();
        for (var i = 0; i<generaldata_table_data.length; i++) 
        {
            if(generaldata_table_data[i][1] == null || generaldata_table_data[i][1] === '')
            {
                continue;
            }
            else
            {
                tops.push(parseFloat(generaldata_table_data[i][1]));
            }
            if(generaldata_table_data[i][2] == null || generaldata_table_data[i][2] === '')
            {
                continue;
            }
            else
            {
                bottoms.push(parseFloat(generaldata_table_data[i][2]));
            }
        }
        var MDbottom = Math.max.apply(Math,bottoms); 
        var MDtop = Math.min.apply(Math,tops); 
        $("#MDbottom").val(MDbottom);
        $("#MDtop").val(MDtop);
        var texp =  (MDbottom-MDtop)/(d_rop_t*24);
        $("#d_total_exposure_time_t").val(texp);
    }
});


//Selects  **/**
//General Data
$("#filtration_function_select").change(function(e)
{
  $.get("{{url('filtration_function_data')}}",
    {ff_id:$(this).val()},
    function(data)
    {
      $.each(data, function(index,value)
      {
        a_factor = value.a_factor;
        b_factor = value.b_factor;
        mud_density = value.mud_density;
      });

      $("#a_factor_t").val(a_factor);
      $("#b_factor_t").val(b_factor);
      $("#d_mud_density_t").val(mud_density);
      $("#c_cement_slurry_density_t").val(mud_density);

    });
});

//IntervalSelect: crea la tabla de general data para diligenciar la información de los intervalos a analizar.
$("#intervalSelect").change(function(e)
{
    var intervals = $("#intervalSelect").val();
    var data_aux = [];
    $.get("{{url('intervalsInfoDrilling')}}",
        {intervals:intervals},
        function(data)
        {
            $.each(data, function(index,value)
            {
                var data_row = [value.nombre, value.top,,value.presion_reservorio,,];
                data_aux.push(data_row);
            });

            create_interval_general_data_table(data_aux);
        });
});

//InputDataMethodSelect
$("#inputDataMethodSelect").change(function(e)
{
  if($("#inputDataMethodSelect").val()==1)
  {
    var data_aux = [[,,,],[,,,],[,,,],[,,,],[,,,]];
    create_profile_input_data_table(data_aux);
  }
    else if($("#inputDataMethodSelect").val()==2)
    {
        var data_byIntervals = [];
        var intervals = $("#intervalSelect").val();
        $.get("{{url('intervalsInfoDrilling')}}",
            {intervals:intervals},
            function(data)
            {
                $.each(data, function(index,value)
                {
                    var data_row = [value.nombre,value.porosidad,value.permeabilidad,];
                    data_byIntervals.push(data_row);
                });
                create_intervals_input_data_table(data_byIntervals);
            });
    }
    else
    {
        $("#averageInput_t").hide();
        $("#byIntervalsInput_t").hide();
        $("#profileInput_t").hide();
        $("#profile_g").hide();
        $("#plotProfile").hide();
    }
});

</script>

