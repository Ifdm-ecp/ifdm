

<script type="text/javascript">

  function tablaComponents()
  {

    data = $("#data").val();
    console.log(data)
    if (data === '') {
      @if($filtration_function->mudComposicion)
          data = JSON.stringify({{$filtration_function->mudComposicion}});
      @else
        data = [[null,null], [null,null]]
      @endif
    } else {
        data = JSON.parse(data);
    }

    $('#tablaComponents').handsontable({
       data: data,
       colWidths: [360, 360] ,
       rowHeaders: true, 
           columns: [
         {title:"Component",type: 'text'},
         {title:"Concentration lb/b o gal/b",type: 'numeric', format: '0[.]0000000'},
       ],
        colHeaders: true,
        minSpareRows: 1
      });
  }

 function verificarComposicion()
 {
    var datos = JSON.stringify($("#tablaComponents").handsontable('getData'));
    
    $("#data").val(datos);
 }


var counter = $("#lab_test_counter").val();

//Variables para verificar la validación
var flag_lab_tests = false;


//Cargar valores de select en recarga de página
window.onload = function() {
    var cuenca = {!! $basin_id !!};
    var campo = {!! $field_id !!};
    var formation = {!! $formation_id !!};


    $("#basin").val(cuenca);
    $("#basin").selectpicker('refresh');
    $("#field").val(campo);
    $("#field").selectpicker('refresh');    
    $("#formation").val(formation);
    $("#formation").selectpicker('refresh');

    $.get("{{url('campos')}}", {
            cuenca: cuenca
        },
        function(data) {
            $("#field").empty();
            $.each(data, function(index, value) {
                $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            var k = '#field > option[value="{{ 'xxx'}}"]';
            k = k.replace('xxx', campo);
            $(k).attr('selected', 'selected');
            $("#field").selectpicker('refresh');
        }
    );

    $.get("{{url('formations_by_field')}}", {
            field: campo
        },
        function(data) {
            $("#formation").empty();
            $.each(data, function(index, value) {
                $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            var k = '#formation > option[value="{{ 'xxx'}}"]';
            k = k.replace('xxx', formation);
            $(k).attr('selected', 'selected');
            $("#formation").selectpicker('refresh');
        }
    );
}

$(document).ready(function() 
{
    //Checks cuando actualiza página
    if($("#filtration_function_factors_option").val()==0)
    {
        $("#check_set_function_factors").attr('checked', true);
        $("#check_manual_assigment").attr('checked', false);
        $("#function_factors").show();
        $("#manual_assigment").hide();
    }
    else if($("#filtration_function_factors_option").val()==1)
    {
        $("#check_set_function_factors").attr('checked', false);
        $("#check_manual_assigment").attr('checked', true);
        $("#function_factors").hide();
        $("#manual_assigment").show();
    }

    $("#basin").selectpicker('refresh');
    $("#field").selectpicker('refresh');
    $("#formation").selectpicker('refresh');

    //Cambio de selector de cuenca
    $("#basin").change(function(e) 
    {
        var basin = $('#basin').val();
        $.get("{{url('campos')}}", {
                cuenca: basin
            },
            function(data) {
                $("#field").empty();
                $.each(data, function(index, value) {
                    $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#field").selectpicker('refresh');
                $('#field').selectpicker('val', '');
            });
    });
    //Cambio de selector de campo
    $("#field").change(function(e) 
    {
        var field = $('#field').val();
        $.get("{{url('formations_by_field')}}", {
                field: field
            },
            function(data) {
                $("#formation").empty();
                $.each(data, function(index, value) {
                    $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#formation").selectpicker('refresh');
                $('#formation').selectpicker('val', '');
            });
    });
    //Cambio de selector de formación
    $("#formation").change(function(e)
    {
        $("#general_data").show();
    });

    //Validaciones

    $("#general_data").on('change','.k_value', function(e)
    {
        validate_ff_k_value($(this).attr("id"));
    });

    $("#general_data").on('change','.pob_value', function(e)
    {
        validate_ff_pob_value($(this).attr("id"));
    });

    create_lab_test_table("1");
    create_lab_test_table("2");
    a_b_chart()

    // graph when "a" and "b" are defined
    $("#a_factor").change(function(){
        if($.isNumeric($("#a_factor").val()) == true && $.isNumeric($("#b_factor").val()) == true && $("#b_factor").val() !== ''){
            a_b_chart()
        }
    });

    $("#b_factor").change(function(){
        if($.isNumeric($("#b_factor").val()) == true && $.isNumeric($("#a_factor").val()) == true && $("#a_factor").val() !== ''){
            a_b_chart()
        }
    });

    //Checks
    $("#check_set_function_factors").click(function()
    {
        $("#check_manual_assigment").attr('checked', false);
        $("#function_factors").show();
        $("#manual_assigment").hide();
        $("#filtration_function_factors_option").val('0'); //0: set filtration function factors; 1: create filtration function with laboratory test

    });

    $("#check_manual_assigment").click(function()
    {
        $("#check_set_function_factors").attr('checked', false);
        $("#function_factors").hide();
        $("#manual_assigment").show();
        $("#filtration_function_factors_option").val('1'); //0: set filtration function factors; 1: create filtration function with laboratory test
    });

    //On load page
    on_load_tables_data();

    //Carga de datos desde BD para edit
    load_lab_test_data();
});
//Validaciones
function validate_ff_k_value(input_id)
{
    var value = $("#"+input_id).val();
    var value_div = "#"+input_id+"_input";
    if(!$.isNumeric(value))
    {
        $(value_div).addClass('has-error');
        flag_k = false; 
        $("#"+input_id+"_hidden").val(flag_k);
    }
    else
    {
        $(value_div).removeClass('has-error');
        flag_k = true; 
        $("#"+input_id+"_hidden").val(flag_k);
    }
}
function validate_ff_pob_value(input_id)
{
    var value = $("#"+input_id).val();
    var value_div = "#"+input_id+"_input";
    if(!$.isNumeric(value))
    {
        $(value_div).addClass('has-error');
        flag_pob = false; 
        $("#"+input_id+"_hidden").val(flag_pob);
    }
    else
    {
        $(value_div).removeClass('has-error');
        flag_pob = true; 
        $("#"+input_id+"_hidden").val(flag_pob);
    }
}

//Grafica una prueba de laboratorio específica. 
function plot_lab_test(lab_test_id)
{
    data = $("#lab_test_"+lab_test_id+"_table").handsontable('getData');
    var time = [];
    var filtered_volume = [];
    for (var i = 0; i < data.length; i++)
    {
      time.push(data[i][0]);
      filtered_volume.push(data[i][1]);
    }
    time.pop();
    filtered_volume.pop();
    $("#lab_test_"+lab_test_id+"_chart").highcharts({
           title: {
               text: 'Laboratory Test #'+lab_test_id,
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

//Crea las tablas para las nuevas secciones de pruebas de laboratorio y las añade al div. 
function create_lab_test_table(lab_test_table_id)
{
    var lab_test_div = $("#lab_test_"+lab_test_table_id+"_table");
    var numeric_validator = function (value, callback)
    {
        if(!$.isNumeric(value))
        {
            callback(false);
        }
        else
        {
            callback(true);
        }
    };
    lab_test_div.handsontable(
    {
        data: [[,],[,],[,],[,]], 
        afterValidate: function(isValid, value,row, prop, source){
            if(!isValid)
            {
                flag_lab_tests = false;
                $("#lab_test_"+lab_test_table_id+"_hidden").val(flag_lab_tests);
            }
            else
            {
                flag_lab_tests = true;
                $("#lab_test_"+lab_test_table_id+"_hidden").val(flag_lab_tests);
            }
        },
        rowHeaders: true, 
        colWidths: [150, 150],
        columns: 
        [
          {title:"Time [s]", data: 0, type: 'numeric', format: '0[.]0000000'},
          {title:"Filtered Volume [ml]",data: 1, type: 'numeric', format: '0[.]0000000'}
        ],
        minSpareRows: 1,
        contextMenu: true,
    });
}

//Verficar si existen datos para las tablas o no. Si existen, se crean dinámicamente las pruebas de laboratorio faltantes y se precargan los datos en todas las tablas.
function on_load_tables_data()
{
  if($("#lab_test_data").val()==="")
  {
    console.log("Tablas vacías");
  }
  else
  {
    complete_data = JSON.parse($("#lab_test_data").val());
    k_data = JSON.parse($("#k_data").val());
    p_data = JSON.parse($("#p_data").val());
    console.log("Cargando datos");
    console.log(counter);
    
    for (var i = 3; i <counter; i++) 
    {
      add_extra_lab_test_on_load(i);
    }
    for (var i = 1; i < counter; i++)
    {
      lab_test_div_aux = $("#lab_test_"+i+"_table");
      lab_test_div_aux.handsontable({data:complete_data[i-1]});

      k_div_aux = $("#k_lab_test_"+i);
      p_div_aux = $("#p_lab_test_"+i);

      k_div_aux.val(k_data[i-1]);
      p_div_aux.val(p_data[i-1]);
    }
  }
}

//Consultar los datos desde la BD para cargar en las pruebas de laboratorio(tabla, permeabilidad y pob)
function load_lab_test_data()
{
  var option = $("#filtration_function_factors_option").val();
  if(option==1)
  {
    $.get("{!! url('laboratory_test_data') !!}", {
      filtration_function_id: <?php echo $filtration_function->id ?>
    }, function(data)
    {
      counter = data.length+1;
      for (var i = 3; i <counter; i++) 
      {
        add_extra_lab_test_on_load(i);
      }
      for (var i = 1; i < counter; i++)
      {
        lab_test_div_aux = $("#lab_test_"+i+"_table");
        lab_test_div_aux.handsontable({data:data[i-1][2]});

        k_div_aux = $("#k_lab_test_"+i);
        p_div_aux = $("#p_lab_test_"+i);

        k_div_aux.val(data[i-1][0]);
        p_div_aux.val(data[i-1][1]);

      }
    });
  }
}
//Añade una sección de prueba de laboratorio completa incluyendo la tabla de la prueba de laboratorio e inputs para permeability y pob. 
function add_extra_lab_test()
{
    $('#extra_lab_test').append('<div class="row" id="lab_test_'+counter+'_div"><div class="col-md-6"><h4>Laboratory Test #'+counter+'</h4><div class="row" id="lab_test_'+counter+'_table" class="lab_test"></div><button type="button" class="btn btn-primary btn-sm" onclick="plot_lab_test('+counter+')">Plot</button><button type="button" class="btn btn-danger btn-sm" onclick="delete_lab_test('+counter+')">Delete Laboratory Test</button></div><input type="hidden" class="lab_test_hidden" id="lab_test_'+counter+'_hidden" value="false"><div class="col-md-6"></br><div class="row"><div class="col-md-6"><div class="form-group {{$errors->has("k_lab_test_'+counter+'") ? "has-error" : ""}}"><label>Permeability</label><div class="input-group" id="k_lab_test_'+counter+'_input"><input placeholder="mD" class="form-control k_value" id="k_lab_test_'+counter+'" name="k_lab_test_'+counter+'" type="text"><span class="input-group-addon">mD</span></div></div></div><input type="hidden" class="k_hidden" id="k_lab_test_'+counter+'_hidden" value="false"><div class="col-md-6"><div class="form-group {{$errors->has("p_lab_test_'+counter+'") ? "has-error" : ""}}"><label>Pob</label><div class="input-group" id="p_lab_test_'+counter+'_input"><input placeholder="psi" class="form-control pob_value" id="p_lab_test_'+counter+'" name="p_lab_test_'+counter+'" type="text"><span class="input-group-addon">psi</span></div></div></div></div><input type="hidden" class="p_hidden" id="p_lab_test_'+counter+'_hidden" value="false"><div class="row"><div class="col-md-12"><div id="lab_test_'+counter+'_chart"></div></div></div></div></div></br>');
    create_lab_test_table(counter);
    counter++;
    $("#lab_test_counter").val(counter);
}

//Añade una sección de prueba de laboratorio completa incluyendo la tabla de la prueba de laboratorio e inputs para permeability y pob. Esta recrea las pruebas de laboratorio que existían antes de recargar la página por validación o refresco. 
function add_extra_lab_test_on_load(index)
{
    $('#extra_lab_test').append('<div class="row" id="lab_test_'+index+'_div"><div class="col-md-6"><h4>Laboratory Test #'+index+'</h4><div id="lab_test_'+index+'_table" class="lab_test"></div><button type="button" class="btn btn-primary btn-sm" onclick="plot_lab_test('+index+')">Plot</button><button type="button" class="btn btn-danger btn-sm" onclick="delete_lab_test('+index+')">Delete Laboratory Test</button></div><input type="hidden" class="lab_test_hidden" id="lab_test_'+index+'_hidden" value="false"><div class="col-md-6"></br><div class="row"><div class="col-md-6"><div class="form-group {{$errors->has("k_lab_test_'+index+'") ? "has-error" : ""}}"><label>Permeability</label><div class="input-group" id="k_lab_test_'+index+'_input"><input placeholder="mD" class="form-control k_value" id="k_lab_test_'+index+'" name="k_lab_test_'+index+'" type="text"><span class="input-group-addon">mD</span></div></div></div><input type="hidden" class="k_hidden" id="k_lab_test_'+index+'_hidden" value="false"><div class="col-md-6"><div class="form-group {{$errors->has("p_lab_test_'+index+'") ? "has-error" : ""}}"><label>Pob</label><div class="input-group" id="p_lab_test_'+index+'_input"><input placeholder="psi" class="form-control pob_value" id="p_lab_test_'+index+'" name="p_lab_test_'+index+'" type="text"><span class="input-group-addon">psi</span></div></div></div></div><input type="hidden" class="p_hidden" id="p_lab_test_'+index+'_hidden" value="false"><div class="row"><div class="col-md-12"><div id="lab_test_'+index+'_chart"></div></div></div></div></div></br>');
    create_lab_test_table(index);
}

function delete_lab_test(index)
{
  $("#lab_test_"+index+"_div").remove();
  counter--;
  $("#lab_test_counter").val(counter);
}
//Valida y limpia las tablas. 
function validate_lab_test_tables()
{
    var final_flag_lab_test = true;
    var final_lab_test_data = [];

    $(".lab_test_hidden").each(function()
    {
        var id = this["id"].substr(9,1);
        var data = $("#lab_test_"+id+"_table").handsontable('getData');
        var cleaned_data = [];
        var flag_lab_test_valid_data = true;
        var mensaje = "default";
        $.each(data, function( rowKey, object) 
        {
            if((object[0] != null && !(object[0]==="")) || (object[1] != null && !(object[1]==="")))
            {
                cleaned_data.push(object);
               if(object[1]==null || object[1]==="" || object[0]==null || object[0]==="")
               {
                  flag_lab_test_valid_data = false; 
               }
               if(!$.isNumeric(object[1]) || !$.isNumeric(object[0]))
               {
                  flag_lab_test_valid_data = false; 
               }
            }
        });
        final_lab_test_data.push(cleaned_data);
        var flag_lab_test_min_row;
        if(cleaned_data.length>=2)
        {
            flag_lab_test_min_row = true;
        }
        else
        {
            flag_lab_test_min_row = false;
        }
        final_flag_lab_test = final_flag_lab_test && (flag_lab_test_valid_data && flag_lab_test_min_row);
    });

    return [final_flag_lab_test, final_lab_test_data];
}

//Guarda los datos de las pruebas de laboratorio (tabla lab test, k y pob). 
function save_filtration_function()
{
    verificarComposicion();
    if($("#filtration_function_factors_option").val()==1)
    {
      cleaned_and_validated_data = validate_lab_test_tables();
      if(cleaned_and_validated_data[0])
      {
          $("#lab_test_data").val(JSON.stringify(cleaned_and_validated_data[1]));

          //K y pob para cada prueba de laboratorio
          var final_k_data = [];
          $( ".k_value" ).each(function() 
          {
              final_k_data.push(parseFloat($(this).val()));
          });
          $("#k_data").val(JSON.stringify(final_k_data));

          var final_p_data = [];
          $( ".pob_value" ).each(function() 
          {
              final_p_data.push(parseFloat($(this).val()));
          });
          $("#p_data").val(JSON.stringify(final_p_data));
      }
      else
      {
          var evt = window.event || arguments.callee.caller.arguments[0];
          evt.preventDefault();
          console.log("Error");
          alert("Please, check your laboratory test data. You must include at least two rows of numerical data.");

      }
    }
}

//Grafica la representación de las pruebas de laboratorio con datos fijos. 
function a_b_chart()
{
    x_value = 100000;
    x_value_2 = 100;
    m = parseFloat($("#a_factor").val());
    b = parseFloat($("#b_factor").val());
    data = [
        [0, b],
        [x_value, m*x_value + b],
        [x_value_2, m*x_value_2 + b]
    ];
    
    //data = [[0.2704,696],[0.4275,800],[0.0263,348],[0.1209,400]];
    data_aux = fitData(data).data;

    $("#a_b_chart").highcharts({
           title: {
               text: 'dVf/(dt^1/2) as function of K*Pob',
               x: -20 //center
           },
           subtitle:{
            text:"a: slope - b: intercept"
           },
           xAxis: {
            title: {
              text: 'K*Pob'
            }
           },
           yAxis: {
               title: {
                   text: 'dVf/(dt^1/2)'
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
             type: 'scatter',
             data: data
           },
           {
             type: 'line',
             marker: { enabled: false },
             /* function returns data for trend-line */
             data: data_aux
           }]
       });

}
</script>