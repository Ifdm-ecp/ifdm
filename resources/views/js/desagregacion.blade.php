<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">

<script type="text/javascript">

  $(document).ready(function() {
    $("#myModal").modal('show');
    $("#myModal_val").modal('show');

    /** Crear la tabla de unidades hidráulicas*/
    create_hydraulic_units_data_table();
  });

  /** Maneja los botones de next y previous para cambiar de pestañas */
  $('.btnNext').click(function(){
    $('.nav-tabs > .active').next('li').find('a').trigger('click');
  });
  $('.btnPrevious').click(function(){
    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
  });



  function guardar() {
    /* Loading */
    $("#loading_icon").show();
    hidraulic_units_data = clean_table_data("hidraulic_units_data");
    $("#unidades_table").val(JSON.stringify(hidraulic_units_data));

    /*
    var thickness = parseFloat($("#production_formation_thickness").val());
    var average_porosity = parseFloat($("#porosity").val())/100;
    var average_permeability = parseFloat($("#permeability").val());

    if(thickness && average_porosity && average_permeability) {
      console.log("if")
      validate_table(hidraulic_units_data, ["Hidraulic Units Data Table"], [["numeric", "numeric", "numeric", "numeric"]]);
      console.log("a_validate_table");
    }
      console.log("end enviar");
      */

  }

  function enviar() {
    /* Loading */
    $("#loading_icon").show();
    //calculate_hydraulic_units_data();
    hidraulic_units_data = clean_table_data("hidraulic_units_data");

    if (hidraulic_units_data.length == 0 ) {
      calculate_hydraulic_units_data();
      hidraulic_units_data = clean_table_data("hidraulic_units_data");
    }

    $("#unidades_table").val(JSON.stringify(hidraulic_units_data));
    
    validate_table(hidraulic_units_data, ["Hidraulic Units Data Table"], [["numeric", "numeric", "numeric", "numeric"]]);

  }

  //Llamar cada vez que se necesiten validar los datos de la tabla.
  //Se necesita indicar: id del div de la tabla, el nombre de la tabla y un array con el tipo de dato de cada columna(text y numeric)
  function validate_table(table_div_id, table_name, column_types) {
      console.log(table_div_id)
      $("#hidraulic_units_data").handsontable(config);
      container = $("#" + table_div_id); //Div de la tabla
      console.log(container);
      //$("#hidraulic_units_data");
      var table_data = container.handsontable('getData');
      
      console.log(String(table_data));

      if(!table_data){
        console.log("if table");
        table_data = [];
      }
      console.log(table_data);
      var number_rows = table_data.length;
      console.log([table_data, number_rows]);
      var flag_numeric = false;
      var flag_value_empty = false;

      var message_empty = null;
      var message_numeric = null;
      var message_value_empty = null;

      var final_message = null;
      if (number_rows > 0) {
          var number_columns = table_data[0].length;
          console.log("if 1");
      } else {
        console.log("else");

          message_empty = "The table " + table_name + " is empty. Please check your data";
          return [message_empty];
      }

      for (var i = 0; i < number_columns; i++) {
          for (var j = 0; j < number_rows; j++) {
              if (column_types[i] == "numeric") {
                  if (!$.isNumeric(table_data[j][i])) {
                      message_numeric = "Some data for the table " + table_name + " must be numeric. Please check your data" + "<br>";
                      flag_numeric = true;
                  }
                  if (table_data[j][i] == null || table_data[j][i] === "") {
                      message_value_empty = "There's missing information for the table " + table_name + ". Please check your data";
                      flag_value_empty = true;
                  }
              }
          }
      }

      final_message = message_numeric + message_value_empty;
      return [final_message];
  }

  /** Valida que el formulario esté completo por pestaña: negro a formularios completos, rojo a incompletos */
  function validate_form_data() {
    /* Unidades hidráulicas */
    var datos_unidades_hidraulicas = clean_table_data("hidraulic_units_data");
    d0 = datos_unidades_hidraulicas[0][0];
    d1 = datos_unidades_hidraulicas[0][1];
    d2 = datos_unidades_hidraulicas[0][2];
    var valid = true;

    if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null))
    {
     valid=false;
     mensaje="Check your Hidraulic Units Data, there's nothing there.";
   }

   for (var i = 0; i<datos_unidades_hidraulicas.length; i++)
   {
     d0 = datos_unidades_hidraulicas[i][0];
     d1 = datos_unidades_hidraulicas[i][1];
     d2 = datos_unidades_hidraulicas[i][2];
     if((d0 != null && d0!="") || (d1 != null && d1!="") || (d2 != null && d2!=""))
     {

      if(d1==null || d1==="" || d0==null || d0==="" || d2==null || d2==="")
      {
       valid=false;
       mensaje="Check your Hidraulic Units Data, there's some incomplete data";
     }
   }
 }

 if($("#drainage_area_shape").prop('checked') || !$('#well_radius').val() || !$('#radio_drenaje_yac').val() || !$('#presion_yacimiento').val() || !$('#profundidad_medida_pozo').val() || !$('#espesor_canoneado').val() || !$('#profundidad_penetracion_canones').val() || !$('#radio_perforado').val() || !$('#profundidad_real_formacion').val() || !$('#espesor_formacion_productora').val())
 {
  document.getElementById('well_data').style.color = "#DF0101";
}
else
{
  document.getElementById('well_data').style.color = "#000000";
}
if(!$('#tasa_flujo').val() || !$('#presion_fondo').val() || !$('#caudal_produccion_gas').val())
{
  document.getElementById('production_data').style.color = "#DF0101";
}
else
{
  document.getElementById('production_data').style.color = "#000000";
}
if($('#tipo_roca').val()=="0" || !$('#permeabilidad_abs_ini').val() || !$('#relacion_perm_horiz_vert').val())
{
  document.getElementById('rock_properties').style.color = "#DF0101";
}
else
{
  document.getElementById('rock_properties').style.color = "#000000";
}
if(!$('#viscosidad_aceite').val() || !$('#viscosidad_gas').val() || !$('#gravedad_especifica_gas').val() || !$('#factor_volumetrico_aceite').val())
{
  document.getElementById('fluid_properties').style.color = "#DF0101";
}
else
{
  document.getElementById('fluid_properties').style.color = "#000000";
}
if(!$('#gradiente_esfuerzo_horizontal_minimo').val() || !$('#gradiente_esfuerzo_horizontal_maximo').val() || !$('#gradiente_esfuerzo_vertical').val())
{
  document.getElementById('stress_gradients').style.color = "#DF0101";
}
else
{
  document.getElementById('stress_gradients').style.color = "#000000";
}
if(!$('#skin').val() && !$('#dano_total_pozo').val())
{
  document.getElementById('damage').style.color = "#DF0101";
}
else
{
  document.getElementById('damage').style.color = "#000000";
}
if(!valid)
{
  document.getElementById('hidraulic_units').style.color = "#DF0101";
}
else
{
  document.getElementById('hidraulic_units').style.color = "#000000";
}
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

  /** Banner info escenario */
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

 function create_hydraulic_units_data_table()
 {
  $hidraulic_units_data_table = $("#hidraulic_units_data");
  $hidraulic_units_data_table.handsontable({
    data: [[],[],[],[]],
    height: 200,
    colHeaders: true,
    minSpareRows: 4,
    viewportColumnRenderingOffset: 10,
    rowHeaders: true,
    contextMenu: true,
    stretchH: 'all',
    colWidths: [180, 180, 180, 180],
    columns: [
    {
      title: "Thickness [ft]",
      data: 0,
      type: 'numeric',
      format: '0[.]0000000'
    },
    {
      title: "Flow Zone Index [µm]",
      data: 1,
      type: 'numeric',
      format: '0[.]0000000'
    }, 
    {
      title: "Average Porosity [0-1]",
      data: 2,
      type: 'numeric',
      format: '0[.]0000000'
    },
    {
      title: "Average Permeability [mD]",
      data: 3,
      type: 'numeric',
      format: '0[.]0000000'
    },
    ]

  });
}

function calculate_hydraulic_units_data()
{
  var production_formation_thickness = parseFloat($("#production_formation_thickness").val());
  var formation_thickness = parseFloat($("#formation_thickness").val());
  var porosity = parseFloat($("#porosity").val());
  var permeability = parseFloat($("#permeability").val());
  var well_completitions = parseFloat($("#well_completitions").val());

  if (well_completitions == 3) {
    if(production_formation_thickness && porosity && permeability)
    {
      //Cálculo de Flow Zone Index (FZI)
      var rqi = 0.0314 * Math.sqrt(permeability/porosity);
      var porosity_z = porosity / (1 - porosity);
      var flow_zone_index = rqi / porosity_z;

      var hydraulic_units_data = [[production_formation_thickness, flow_zone_index, porosity, permeability]];

      var hydraulic_units_data_table = $("#hidraulic_units_data").handsontable('getInstance');
      hydraulic_units_data_table.updateSettings(
      {
        data: hydraulic_units_data,
        stretchH: 'all'
      });

      hydraulic_units_data_table.render();
    }
    else
    {
      $('#hydraulic_modal').modal("show");
    }
  }
  else if (well_completitions == 1 || well_completitions == 2) 
  {
    if(formation_thickness && porosity && permeability)
    {
      //Cálculo de Flow Zone Index (FZI)
      var rqi = 0.0314 * Math.sqrt(permeability/porosity);
      var porosity_z = porosity / (1 - porosity);
      var flow_zone_index = rqi / porosity_z;

      var hydraulic_units_data = [[formation_thickness, flow_zone_index, porosity, permeability]];

      var hydraulic_units_data_table = $("#hidraulic_units_data").handsontable('getInstance');
      hydraulic_units_data_table.updateSettings(
      {
        data: hydraulic_units_data,
        stretchH: 'all'
      });

      hydraulic_units_data_table.render();
    }
  }  
  else
  {
    $('#hydraulic_modal_incomplete').modal("show");
  }
}

document.getElementById('well_completitions').addEventListener('change', function () {
    var style = this.value == 3 ? 'block' : 'none';
    document.getElementById('hidden_div_perforated_liner').style.display = style;
});

document.getElementById('fluid_of_interest').addEventListener('change', function () {
    var style = this.value == 1 ? 'block' : 'none';
    document.getElementById('hidden_oil').style.display = style;
    var style = this.value == 2 ? 'block' : 'none';
    document.getElementById('hidden_gas').style.display = style;
    var style = this.value == 3 ? 'block' : 'none';
    document.getElementById('hidden_water').style.display = style;
});

</script>