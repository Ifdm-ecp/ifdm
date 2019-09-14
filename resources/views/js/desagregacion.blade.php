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

  function enviar() {
    /* Loading */
    $("#loading_icon").show();
    hidraulic_units_data = clean_table_data("hidraulic_units_data");
    $("#unidades_table").val(JSON.stringify(hidraulic_units_data));
    validate_table([hidraulic_units_data], ["Hidraulic Units Data Table"], [["numeric", "numeric", "numeric", "numeric"]]);
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

 if($("#forma_area_drenaje").prop('checked') || !$('#radio_pozo').val() || !$('#radio_drenaje_yac').val() || !$('#presion_yacimiento').val() || !$('#profundidad_medida_pozo').val() || !$('#espesor_canoneado').val() || !$('#profundidad_penetracion_canones').val() || !$('#radio_perforado').val() || !$('#profundidad_real_formacion').val() || !$('#espesor_formacion_productora').val())
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
    minSpareRows: 1,
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
    }, {
      title: "Average Porosity [%]",
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
  var thickness = parseFloat($("#espesor_formacion_productora").val());
  var average_porosity = parseFloat($("#porosity").val());
  var average_permeability = parseFloat($("#permeabilidad_abs_ini").val());

  if(thickness && average_porosity && average_permeability)
  {
    //Cálculo de Flow Zone Index (FZI)
    var rqi = 0.0314 * Math.sqrt(average_permeability/average_porosity);
    var porosity_z = average_porosity / (1 - average_porosity);
    var flow_zone_index = rqi / porosity_z;

    var hydraulic_units_data = [[thickness, flow_zone_index, average_porosity, average_permeability]];

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
    alert("For calculating hydraulic_units_data you'll need Producing Formation Thickness, Average Permeability, and Average Porosity Data.");
  }
}
</script>