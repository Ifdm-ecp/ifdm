<script type="text/javascript">

/**
 * Este archivo contiene todo el javascript para el control del módulo de georreferenciación. 
 */
var basinf;
var camposf = [];
var formacionf;
var parametrof;
var zoom; 
var map;
var ccenter;
var c=0; //Bandera para saber si es primera vez entrando al "pintar polígonos" para calcular el zoom y el centro.
var cc = 0; //Bandera para saber si es la primera vez con cada combinación de campos para saber si se deja o no el zoom como está, si es primera vez se vuelve 0 para mostrar zoom estándar, si no, se deja como estaba antes de repintar el mapa.
var ccc = 0; //Bandera que cambia con el filtro basin para saber si es la primera vez que entra al filtro campo: sirve para habilitar o no el filtro mecanismos
var zoom_flag_well_scale = 0;
var zoom_flag_field_scale = 0;


var well_coordinates = [];

google.load('visualization', '1', {packages: ['corechart', 'bar']});

function fillBasicSelectors(cuenca, formaciones = null, sub_sp = null) {
  $.get("{{url('campos')}}",
    {cuenca : cuenca},
    function(data)
    {
      $("#Field").empty();
      $("#Field").selectpicker('refresh');

      $.each(data, function(index, value)
        {
          $("#Field").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        }
      );

      $("#Field").selectpicker('refresh');

      if (formaciones) {
        camposf = formaciones;
        $('#Field').val(formaciones);
        $('#Field').selectpicker('refresh');
      }
    }
  );

  $.get("{!! url('mecan_dano') !!}",
    function(data)
    {
      $("#Mecanismos").empty();

      $.each(data, function(index, value)
        {
          $("#Mecanismos").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        }
      );
      $("#Mecanismos").selectpicker('refresh');
      $('#Mecanismos').selectpicker('val', '');

      if (sub_sp) {
        parametrof = sub_sp;
        var mecdan_id = 0;

        if (sub_sp >= 1 && sub_sp <= 5) {
          mecdan_id = 1;
        } else if (sub_sp >= 6 && sub_sp <= 10) {
          mecdan_id = 2;
        } else if ((sub_sp >= 11 && sub_sp <= 14) || sub_sp == 30) {
          mecdan_id = 3;
        } else if (sub_sp >= 15 && sub_sp <= 18) {
          mecdan_id = 4;
        } else if (sub_sp >= 19 && sub_sp <= 22) {
          mecdan_id = 5;
        } else if (sub_sp >= 23 && sub_sp <= 26) {
          mecdan_id = 6;
        }

        $('#Mecanismos').prop('disabled', false);
        $('#Parameter').prop('disabled', false);

        $('#Mecanismos').val(mecdan_id);
        $('#Mecanismos').selectpicker('refresh');

        fillParameters(mecdan_id);
      }
    }
  );

  $('#Parameter').prop('disabled',true);
  $('#Parameter').selectpicker('val', '');

  $('#Mecanismos').prop('disabled',true);
  $('#Mecanismos').selectpicker('val', '');

  $('#Filtrox').prop('disabled',true);
  $('#Filtrox').selectpicker('val', '');

  $('#Field').selectpicker('val', '');
  //$("#Field").selectpicker('refresh');

  $('#Formation').selectpicker('val', '');
  //$("#Formation").selectpicker('refresh');

  $('#avgb').prop('disabled',true);
  $('#minb').prop('disabled',true);
  $('#maxb').prop('disabled',true);
  $('#lastb').prop('disabled',true);

  $('#wvr').attr('disabled',true);
  $('#fvr').attr('disabled',true);
}

function fillParameters(mec) {
  $.get("{!! url('parametros') !!}",
    {mec:mec},
    function(data)
    {
      $("#Parameter").empty();
      $("#Filtrox").empty();

      $.each(data, function(index, value)
      {
        $("#Parameter").append('<option value="'+value.id+'">'+value.nombre+'</option>');
      });

      $("#Parameter").append('</optgroup>');

      $.get("{{url('variables_dano')}}",
        {mec:mec},
        function(data)
        {
          $("#Parameter").append('<optgroup label="Another Damage Variables" disabled>');
          $.each(data, function(index, value){
              $("#Parameter").append('<option value="'+value.nombre+'">'+value.nombre+'</option>');
          });
          $("#Parameter").append('</optgroup>');
          $("#Parameter").selectpicker('refresh');
          $('#Parameter').selectpicker('val', '');

          if (parametrof) {
            $('#Parameter').val(parametrof);
            $('#Parameter').selectpicker('refresh');
            parametrof = null;
          }
        });

      $.get("{{url('config_dano')}}",
        {mec:mec},
        function(data)
        {
          $.each(data, function(index, value)
          {
            $("#Filtrox").append('<option value="'+value.id+'">'+value.nombre+'</option>');
          });
          $("#Filtrox").selectpicker('refresh');
          $('#Filtrox').selectpicker('val', '');
      });
    }
  );
}

$(document).ready(function()
{
  /* ***** Control de eventos ***** */
  $('#wvr').attr('disabled',true);
  $('#fvr').attr('disabled',true);

  //Funcionalidad desde input subparámetros

  /**
   * Acción de botón "Last data" - Mapea la variable de daño o configuración del daño 
   *escogida en el momento con el dato más actualizado en la base de datos para los filtros escogidos
   */
  $("#lastb").click(function(e)
  {

    var sp=$('#Parameter').find("option:selected").text();
    var cd=$("#Filtrox").find("option:selected").text();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var parametro = $('#Parameter').val();
    var filtrox = $('#Filtrox').val();
    var formacion = $('#Formation').val();
    var pb;
    var pm;
    var puntos=[];

    if(parametro == null)
    {
      mapConfDan_Well(1,filtrox,camposf,formacion,cd,puntos);
    }
    else
    {
    if(isVD(parametro))
    {
      mapIsVd_Well(1,parametro,camposf,formacion,sp, puntos);
    }
    else
    {
       map_Well(1,parametro,camposf,formacion,sp,puntos);
    } 
  }});

  /**
   * Acción de botón "Average data" - Mapea la variable de daño o configuración del daño 
   *escogida en el momento con el dato promedio calculado a partir de base de datos para los filtros escogidos
   */
  $("#avgb").click(function(e)
  {

    var sp=$('#Parameter').find("option:selected").text();
    var cd=$("#Filtrox").find("option:selected").text();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var parametro = $('#Parameter').val();
    var filtrox = $('#Filtrox').val();
    var formacion = $('#Formation').val();
    var pb;
    var pm;
    var puntos=[];
    if(parametro == null)
    {
     mapConfDan_Well(2,filtrox,camposf,formacion,cd,puntos);
    }
    else
    {
      if(isVD(parametro))
      {
        mapIsVd_Well(2,parametro,camposf,formacion,sp,puntos);
      }
      else
      {
        map_Well(2,parametro,camposf,formacion,sp,puntos);
      }
  }});

  /**
   * Acción de botón "Minimum data" - Mapea la variable de daño o configuración del daño 
   *escogida en el momento con el dato mínimo calculado a partir de base de datos para los filtros escogidos
   */
  $("#minb").click(function(e)
  {

    var sp=$('#Parameter').find("option:selected").text();
    var cd=$("#Filtrox").find("option:selected").text();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var parametro = $('#Parameter').val();
    var filtrox = $('#Filtrox').val();
    var formacion = $('#Formation').val();
    var pb;
    var pm;
    var puntos=[];

    if(parametro == null)
    {
      mapConfDan_Well(4,filtrox,camposf,formacion,cd,puntos);
    }
    else
    {
      if(isVD(parametro))
      {
        mapIsVd_Well(4,parametro,camposf,formacion,sp,puntos);
      }
      else
      {
       map_Well(4,parametro,camposf,formacion,sp,puntos);
      }
  }});

  /**
   * Acción de botón "Maximum data" - Mapea la variable de daño o configuración del daño 
   * escogida en el momento con el dato Máximo calculado a partir de base de datos para los filtros escogidos
   */
  $("#maxb").click(function(e)
  {

    var sp=$('#Parameter').find("option:selected").text();
    var cd=$("#Filtrox").find("option:selected").text();
    var parametro = $('#Parameter').val();
    var filtrox = $('#Filtrox').val();
    var formacion = $('#Formation').val();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var pb;
    var pm;
    var puntos=[];

    if(parametro == null)
    {
      mapConfDan_Well(3,filtrox,camposf,formacion,cd,puntos);
    }
    else
    {
      if(isVD(parametro))
      {
        mapIsVd_Well(3,parametro,camposf,formacion,sp,puntos);
      }
      else
      {
        map_Well(3,parametro,camposf,formacion,sp,puntos);
      }
  }});

  /**
  * Acción del filtro Basin que acota el número de opciones para el siguiente filtro en la lógica del módulo "Field"
  * Basado en la elección del select "Basin" aparecen los campos cuya cuenca fue la elegida en el filtro anterior y se despliegan 
  * en el select "Field"
   */
  $("#Basin").change(function(e)
  {
    ccc = 0;
    var cuenca = e.target.value;
    basinf = cuenca;

    fillBasicSelectors(cuenca);
  });

  /**
  * Acción del filtro Mecanismos que consulta los mecanismos de daño en la base de datos y los lista para acotar la búsqueda de 
  * una variable de daño o configuración del daño. 
  */
  $("#Mecanismos").change(function(e)
  {

    var mec = $('#Mecanismos').val();
    $('#Parameter').prop('disabled',false);
    $('#Filtrox').prop('disabled',false);
    $('#avgb').prop('disabled',true);
    $('#minb').prop('disabled',true);
    $('#maxb').prop('disabled',true);
    $('#lastb').prop('disabled',true);

    $('#wvr').attr('disabled',true);
    $('#fvr').attr('disabled',true);

    $('#Parameter').selectpicker('deselectAll');
    $("#Parameter").append('<optgroup label="Sub-Parameters">');

    $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> Please choose a Damage Variable or Damage Configuration</div>');   

    fillParameters(mec);
  });

  /**
  * Acción del filtro "Field" que basado en la elección del filtro anterior "Basin" se precarga con los campos pertenecientes a la cuenca escogida.
  * Al cambiar, traerá los datos para llenar el siguiente filtro "Formation" basado en las formaciones existentes en la base de datos que pertenezcan 
  * a dicho campo.
  */
  $("#Field").change(function(e)
  {
    ccc = ccc+1;
    cc = 0;
    c=0;
    var campos = $('#Field').val();
    camposf=campos;

    if(ccc == 1)
    {
      $('#Mecanismos').prop('disabled',false);
      $('#Mecanismos').selectpicker('refresh');
      $('#Mecanismos').selectpicker('val', '');
    }

    //$('#Parameter').prop('disabled',true);
    //$('#Parameter').selectpicker('refresh');
    $('#Parameter').selectpicker('val', '');

    //$('#Filtrox').prop('disabled',true);
    //$('#Filtrox').selectpicker('refresh');
    $('#Filtrox').selectpicker('val', '');

    $('#wvr').attr('disabled',true);
    $('#fvr').attr('disabled',true);

    $.get("{!! url('formaciones2') !!}",
            {campos: campos},
            function(data)
            {
              $("#Formation").empty();
              $.each(data, function(index, value)
              { 
                $("#Formation").append('<option value="'+value.id+'">'+value.nombre+'</option>');
                $("#Formation").selectpicker('refresh');
              });
            });
  });

  /***
  * Acción del radio buttom "Vista por pozo" que al elegirlo, basado en los filtros escogidos en la parte superior de ubicación y variable de daño o configuración
  * del daño, se mostrará en el mapa la tendencia de daño en el tipo de vista elegida, en este caso por pozos
  */
  $("#wvr").change(function(e)
  {
    var sp=$("#Parameter").find("option:selected").text();
    var cd=$("#Filtrox").find("option:selected").text();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var parametro = $('#Parameter').val();
    var formacion = $('#Formation').val();
    var filtrox = $('#Filtrox').val();
    var pb;
    var pm;
    var puntos=[];

    $('#avgb').prop('disabled',false);
    $('#minb').prop('disabled',false);
    $('#maxb').prop('disabled',false);
    $('#lastb').prop('disabled',false);

    if(parametro == null)
    {
      mapConfDan_Well(1,filtrox,camposf,formacion,cd,puntos);
    }
    else
    {
     $('#avgb').prop('disabled',false);
     $('#minb').prop('disabled',false);
     $('#maxb').prop('disabled',false);
     $('#lastb').prop('disabled',false);
     if(isVD(parametro))
     {
       mapIsVd_Well(1,parametro,camposf,formacion,sp,puntos);
     }
     else
     {
       map_Well(1,parametro,camposf,formacion,sp,puntos);
     }
    }
  });

  /***
  * Acción del radio buttom "Vista por campo" que al elegirlo, basado en los filtros escogidos en la parte superior de ubicación y variable de daño o configuración
  * del daño, se mostrará en el mapa la tendencia de daño en el tipo de vista elegida, en este caso por campos
  */
  $("#fvr").change(function(e)
  {
    var sp=$("#Parameter").find("option:selected").text();
    var cd=$("#Filtrox").find("option:selected").text();
    var parametro = $('#Parameter').val();
    var formacion = $('#Formation').val();
    var filtrox = $('#Filtrox').val();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var pb;
    var pm;
    var puntos=[];

     $('#avgb').prop('disabled',true);
     $('#minb').prop('disabled',true);
     $('#maxb').prop('disabled',true);
     $('#lastb').prop('disabled',true);

    if(parametro == null)
    {
        mapConfDano_Field(filtrox,camposf,formacion,cd,puntos);
    }
    else
    {
      if(isVD(parametro))
      {
        mapIsVd_Field(parametro,camposf,formacion,sp,puntos);
      }
      else
      {
        map_Field(parametro,camposf,formacion,sp,puntos);
      }
    }  
  });

  /***
  * Acción del filtro formación - Habilita los filtros de mecanismos de daño para elegir una variable para mapear.
  */
  $("#Formation").change(function(e)
  {
    $('#wvr').attr('disabled',true);
    $('#fvr').attr('disabled',true);

    $('#Mecanismos').prop('disabled',false);
    $('#Mecanismos').selectpicker('refresh');
    $('#Mecanismos').selectpicker('val', '');

    $('#Parameter').prop('disabled',true);
    $('#Parameter').selectpicker('refresh');
    $('#Parameter').selectpicker('val', '');

    $('#Filtrox').prop('disabled',true);
    $('#Filtrox').selectpicker('refresh');
    $('#Filtrox').selectpicker('val', '');

    $('#alert').html('<div class="alert alert-danger " role="alert"> <strong>Remember! </strong> Please choose a damage variable or damage configuration</div>');
  });

  /***
  *Acción del filtro "Damage variable" que contiene las variables de daño, pertenecientes a los subparámetros y a las demás variables de daño. 
  *Al elegir una de ellas, se desplegará en el mapa dependiendo del tipo de vista escogida en los radio buttom "Field view" o "Well view"
  *Los contornos y marcadores y valores correspondientes a los filtros escogidos.
  */
  $("#Parameter").change(function(e)
  {
    var sp=$(this).find("option:selected").text();
    var parametro = $('#Parameter').val();
    var formacion = $('#Formation').val();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var pb;
    var pm;
    var puntos=[];


    $('#avgb').prop('disabled',false);
    $('#minb').prop('disabled',false);
    $('#maxb').prop('disabled',false);
    $('#lastb').prop('disabled',false);

    $('#Filtrox').prop('disabled',false);
    $('#Filtrox').selectpicker('val', '');

    $('#wvr').attr('disabled',false);
    $('#fvr').attr('disabled',false);

    if($('#wvr').is(':checked')) 
    { 
      if(isVD(parametro))
      {
        mapIsVd_Well(1,parametro,camposf,formacion,sp,puntos);
      }
      else
      {
        map_Well(1,parametro,camposf,formacion,sp,puntos);
      }
    } 
    else if($('#fvr').is(':checked'))
    { 
      $('#avgb').prop('disabled',true);
      $('#minb').prop('disabled',true);
      $('#maxb').prop('disabled',true);
      $('#lastb').prop('disabled',true);
      if(isVD(parametro))
      {
        mapIsVd_Field(parametro,camposf,formacion,sp,puntos);
      }
      else
      {
        map_Field(parametro,camposf,formacion,sp,puntos);
      }
    }
    else
    {
     
      alert("You must choose well or field view");
      $('#Parameter').selectpicker('val', '');
      $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> Please choose well or field view</div>');
    } 
  });

  /***
  *Acción del filtro "Damage configuration" que contiene las variables de configuración del daño, pertenecientes a cada uno de los mecanismos de daño y a las demás variables de daño. 
  *Al elegir una de ellas, se desplegará en el mapa dependiendo del tipo de vista escogida en los radio buttom "Field view" o "Well view"
  *Los contornos y marcadores y valores correspondientes a los filtros escogidos.
  */
  $("#Filtrox").change(function(e)
  {
    
    var sp=$(this).find("option:selected").text();
    var filtrox = $('#Filtrox').val();
    var formacion = $('#Formation').val();
    var cs;
    var max; 
    var min;
    var sd;
    var avg;
    var pb;
    var pm;
    var puntos=[];

    $('#avgb').prop('disabled',false);
    $('#minb').prop('disabled',false);
    $('#maxb').prop('disabled',false);
    $('#lastb').prop('disabled',false);

    $('#Filtrox').prop('disabled',false);
    $('#Parameter').selectpicker('val', '');

    $('#wvr').attr('disabled',false);;
    $('#fvr').attr('disabled',false);;

    if($('#wvr').is(':checked'))
    { 
        mapConfDan_Well(1,filtrox,camposf,formacion,sp,puntos);
    } 
    else if($('#fvr').is(':checked'))
    { 
      $('#avgb').prop('disabled',true);
      $('#minb').prop('disabled',true);
      $('#maxb').prop('disabled',true);
      $('#lastb').prop('disabled',true);

        mapConfDano_Field(filtrox,camposf,formacion,sp,puntos);
    }
    else
    {
      alert("You must choose well or field view");
      $('#Parameter').selectpicker('val', '');
      $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> Please choose well or field view</div>');
    }
  });

  /** General Data */
  /** Field Scale */
  $("#field_options").change(function(e)
  {
    var basin_field_scale = $("#basins_field_scale").val();
    var field_option_field_scale = $("#field_options").val();
    map_field_scale(basin_field_scale, field_option_field_scale);
  }); 

  $("#basins_field_scale").change(function(e)
  {
    if($("#field_options").val())
    {
      var basin_field_scale = $("#basins_field_scale").val();
      var field_option_field_scale = $("#field_options").val();
      map_field_scale(basin_field_scale, field_option_field_scale);
    }
    else
    {
      $("#alert").empty();
      $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> Please choose a field variable to deploy</div>');
      $("#alert").show();
    }
  });

  /** Well Scale */
  $("#basins_well_scale").change(function(e)
  {
    var basin = $("#basins_well_scale").val();
    $.get("{{url('campos')}}",
            {cuenca : basin},
            function(data)
            {
              $("#fields_well_scale").empty();
              $("#fields_well_scale").selectpicker('refresh');
              $.each(data, function(index, value)
                {
                  $("#fields_well_scale").append('<option value="'+value.id+'">'+value.nombre+'</option>');
                }
              );
              $("#fields_well_scale").selectpicker('refresh');
              $('#fields_well_scale').selectpicker('val', '');

            }
        );
  });

  $("#fields_well_scale").change(function(e)
  {
    if($("#well_options").val())
    {
      var fields = $("#fields_well_scale").val();
      var option = $("#well_options").val();
      if(option === "plt" || option === "production_data")
      {
        map_well_scale_bool(fields, option);
      }
      else
      {
        heatmap_well_scale(fields, option);
      }
    }
    else
    {
      $("#alert").empty();
      $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> Please choose a well variable to deploy</div>');
      $("#alert").show();
    }
  });

  $("#well_options").change(function(e)
  {
    var fields = $("#fields_well_scale").val();
    var option = $("#well_options").val();
    if(option === "plt" || option === "production_data")
    {
      map_well_scale_bool(fields, option);
    }
    else
    {
      heatmap_well_scale(fields, option);
    }
  });

  /* Esta sección se encarga de recibir las peticiones desde el módulo multiparamétrico. Recibe el subparámetro y el universo de datos que se quiere desplegar en el mapa*/
  if("{{Request::get('subp')}}" && "{{Request::get('multi')}}") {
    var sub_sp = "{{Request::get('subp')}}";
    var multi_sp = "{{Request::get('multi')}}";
    var puntos5 = []; //Array necesario para operar la función
    var formacion = $('#Formation').val(); //Deja la posibilidad abierta a volver a filtrar por formaciones
    var campos_sp = [];
    var sp_f; //Nombre subparámetro

    $.get("{!! url('datos_geor') !!}",
    {
      multi: multi_sp,
      subp: sub_sp
    },
    function(data)
    {
      $.each(data.datos, function(index, value)
      {
        if(value.statistical == 'undefined' || value.statistical == null)
        {
          campos_sp = value.field_statistical.split(",");
          $('#Basin').val(value.basin_statistical);
          $('#Basin').selectpicker('refresh');
          fillBasicSelectors(value.basin_statistical, campos_sp, sub_sp);
        }
        else
        {
          $.each(data.colombia, function(index,value)
          {
            campos_sp.push(value.id);
          });
        }
      });

      $.each(data.sp, function(index,value)
      {
        sp_f = value.nombre;
      });

      map_Well(1, sub_sp, campos_sp, formacion, sp_f, puntos5);
    });
  } else {
    $.get("{!! url('allwellsgeoreference') !!}",
      function(data) {
        // Initializes the map to point to Colombia
        var ccenter = new google.maps.LatLng(4.624335, -74.063644);
        var mapOptions = {
          zoom : 6,
          center: ccenter,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        // Loops each well existing in the database and draws a marker for each one in the
        // Google maps canvas
        $.each(data.Wells, function(index, value) {
          var cs = value.Cnombre;
          var marker = new google.maps.Marker({
            position: new google.maps.LatLng(value.lat, value.lon),
            map: map,
            icon: 'http://labs.google.com/ridefinder/images/mm_20_white.png'
          });

          var infoW = new google.maps.InfoWindow({
            content: " <b>Well name: </b>" + value.nombre +
              "- <b>Field: </b>" + cs 
          });

          marker.addListener('click', function() {
            infoW.open(map, marker);
            currentPopup = infoW;
          });

          google.maps.event.addListener(infoW, 'closeclick', function () {
            map.panTo(center);
            currentPopup = null;
          });
        });

        // Fills the general data with at least the amount of wells present in the map
        $('#MaV').text('-');
        $('#MiV').text('-');
        $('#AVG').text('-');
        $('#SD').text('-');
        $('#GI').text('-')
        $('#WIF').text(data.Wells.length);
        $('#WWM').text('-');
      }
    );
  }
});



/* ***** Funciones ***** */

/** **************** General Data **************** */

function map_field_scale(basin, option)
{
  $("#scale").hide();
  $("#scale_values").hide();
  $("#chart_well_scale").hide();

  $("#alert_2").empty();
  $('#alert_2').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> A green polygon indicates that there is information for the current field and on the other hand, a red one, shows a lack of data</div>');
  $("#alert_2").show();

  var true_counter = 0; 
  var false_counter = 0; 

  zoom_flag_field_scale += 1;
  $.get("{!! url('field_scale') !!}",
    {basin: basin,
      option:option},
      function(data)
      {
        var map_center; 
        $.each(data.center, function(index,value)
        {
          map_center = new google.maps.LatLng(value.lat, value.lon);
        });
        $("#map").empty();
        $("#alert").empty();
        if(zoom_flag_field_scale==1)
        {
          zoom = 8;
          center = map_center;
        }
        else
        {
          zoom = map.getZoom();
          center = map.getCenter();
        }
        var mapOptions = {
            zoom : zoom,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        $.each(data.data, function(index,value)
        {
          var field_polygon_points = [];

          var field_coordinates = value[2];
          for (var i = 0; i<field_coordinates.length; i++) 
          {
            field_polygon_points.push(new google.maps.LatLng(field_coordinates[i][0],field_coordinates[i][1]));
          }
          if(value[3])
          {
            var color = '#02B22B';
            var is_pvt = '<font color="green"><b>Exist PVT Data for this field in database</b></font>';
            true_counter +=1; 
          }
          else
          {
            var color = '#FF0000';
            var is_pvt = '<font color="red"><b>Doesn\'t exist PVT Data for this field in database</b></font>';
            false_counter +=1; 
          }

          var polygon = new google.maps.Polygon({
            paths: field_polygon_points, 
            strokeColor: color, 
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: color,
            fillOpacity: 0.5
          });

          polygon.setMap(map);
          info_window = new google.maps.InfoWindow();
          google.maps.event.addListener(polygon, 'click', function(e)
          {
            var info_window_string = '<b>*** Field Scale *** </b><br><br>'+'<b>Field: </b>'+value[1]+'<br>'+is_pvt;

            info_window.setContent(info_window_string);
            info_window.setPosition(e.latLng);
            info_window.open(map);

          });
        });
        plot_field_scale('chart_field_scale', 'PVT Data',false_counter, true_counter); 
      });
}

function heatmap_well_scale(fields, option)
{
  $("#scale").show();
  $("#scale_values").show();
  well_coordinates = [];
  var markers = [];
  var marker_circles = [];
  var false_counter = 0;
  var true_counter = 0; 
  var total_min = 0; 
  var total_max = 0; 
  var total_avg = 0; 
  var total_sd = 0; 
  zoom_flag_well_scale += 1;

  $.get("{!! url('well_scale') !!}",
  {
    fields: fields,
    option: option
  },
  function(data)
  {
    var map_center; 
    var option_name = $("#well_options option:selected").text();
    $.each(data.center, function(index,value)
    {
      map_center = new google.maps.LatLng(value.lat, value.lon);
    });

    var general_info_total = data.general_info; 
    total_min = general_info_total.min;
    total_max = general_info_total.max;
    total_avg = general_info_total.avg;
    total_sd = general_info_total.sd;

    if(total_min === null && total_max === null)
    {
      $('#b1').text("NA");
      $('#b2').text("NA");
      $('#b3').text("NA");
      $('#b4').text("NA");
    }
    else
    {
      $('#b1').text(total_min.toFixed(2));
      $('#b2').text((total_max/3).toFixed(2));
      $('#b3').text((total_max/2).toFixed(2));
      $('#b4').text(total_max.toFixed(2));
    }

    $("#map").empty();
    $('#alert').empty();
    $("#alert_2").empty();
    if(zoom_flag_well_scale==1)
    {
      zoom = 11;
      center = map_center;
    }
    else
    {
      zoom = map.getZoom();
      center = map.getCenter();
    }
    var map_options = {
        zoom : zoom,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map'), map_options);
    heatmap = new google.maps.visualization.HeatmapLayer({
      data: well_coordinates,
      map: map,
      radius: 15
    });
    $.each(data.data, function(index,value)
    {
      var field_polygon_points = [];
      var field_coordinates = value[1];
      var well_info = value[2];
      var general_info = value[3];
      var max_value = general_info.max;
      var min_value = general_info.min;
      var avg_value = general_info.avg;
      if(avg_value != null && max_value != null && min_value != null)
      {
        avg_value = avg_value.toFixed(2);
      }
      else
      {
        max_value = "NA";
        avg_value = "NA";
        min_value = "NA";
      }

      for (var j = 0; j<field_coordinates.length; j++) 
      {
        field_polygon_points.push(new google.maps.LatLng(field_coordinates[j].lat,field_coordinates[j].lon));
      }
      if(field_polygon_points.length>0)
      {
        var field_color = '#FF0000';

        var polygon = new google.maps.Polygon({
          paths: field_polygon_points, 
          strokeColor: field_color, 
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: field_color,
          fillOpacity: 0.0
        });

        polygon.setMap(map);
        info_window = new google.maps.InfoWindow();
        google.maps.event.addListener(polygon, 'click', function(e)
        {
          var info_window_string = '<b>*** Field Scale *** </b><br><br>'+'<b>Field: </b>'+value[0]+'<br>'+'<b>Max '+option_name+' Value: </b>'+max_value+'<br>'+'<b>Min '+option_name+' Value: </b>'+min_value+'<br>'+'<b>Average '+option_name+' Value: </b>'+avg_value+'<br>';

          info_window.setContent(info_window_string);
          info_window.setPosition(e.latLng);
          info_window.open(map);

        });
      }

      for (var i = 0; i<well_info.length; i++) 
      {
        var well_coordinate = new google.maps.LatLng(well_info[i].lat, well_info[i].lon);

        if(well_info[i].value)
        {
          well_coordinates.push(well_coordinate);

          var well_icon = 'http://labs.google.com/ridefinder/images/mm_20_brown.png';
          true_counter += 1; 
          /* Asignación de color según el peso del valor del subparámetro consultado */
          var circle_color; 
          var current_value = well_info[i].value;
          var normalized_value = (current_value/max_value)*10;
          switch (true) 
          {
            case (normalized_value > 9 ):  circle_color = '#ff0000'; break;
            case (normalized_value < 9 && normalized_value > 8 ):  circle_color = '#ff7f00'; break;
            case (normalized_value <= 8 && normalized_value > 7 ): circle_color = '#ffc200'; break; 
            case (normalized_value <=7  && normalized_value > 6 ): circle_color = '#ffff00'; break;
            case (normalized_value <= 6 && normalized_value > 5 ): circle_color = '#b5d400'; break;
            case (normalized_value <= 5 && normalized_value > 4 ): circle_color = '#6caa00'; break;
            case (normalized_value <= 4 && normalized_value > 3 ): circle_color = '#008000'; break;
            case (normalized_value <= 3 && normalized_value > 2 ): circle_color = '#506666'; break;
            case (normalized_value <= 2 && normalized_value > 1 ): circle_color = '#5646b1'; break;
            case (normalized_value <= 1 ): circle_color = '#0000ff'; break;
          }
          var circle_options = {
              strokeColor: circle_color,
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: circle_color,
              fillOpacity: 0.35,
              map: null,
              center: well_coordinate,
              radius: 600
              };
          cityCircle = new google.maps.Circle(circle_options);
          marker_circles.push(cityCircle);
        }
        else
        {
          var well_icon = 'http://labs.google.com/ridefinder/images/mm_20_white.png';
          false_counter += 1; 
        }

        var well_marker = new google.maps.Marker(
        {
          position: well_coordinate,
          map: null,
          icon: well_icon
        });
        markers.push(well_marker);

        well_info_window = new google.maps.InfoWindow();
        var well_name = well_info[i].nombre;
        var well_value = well_info[i].value;
        if(!well_value)
        {
          well_value = '<font color=red> There is no value for this well</font>';
        }
        google.maps.event.addListener(well_marker, 'click', (function(well_name, well_coordinate, well_value){ 
          return function()
          {        
            var info_window_string = '<b>*** Well Scale *** </b><br><br>'+'<b>Well: </b>'+well_name+'<br><b>Field: </b>'+value[0]+'<br><b>'+option_name+': </b>'+well_value;
            info_window.setContent(info_window_string);
            info_window.setPosition(well_coordinate);
            info_window.open(map);
          };})(well_name,well_coordinate,well_value));
      }

    });


    google.maps.event.addListener(map, 'zoom_changed', function() 
    {
      var zoom = map.getZoom();
      if (zoom > 12) 
      {
        // hide the heatmap, show the markers
        console.log("Heatmap no");

        for (var i = 0; i < markers.length; i++) 
        {
          markers[i].setMap(map);
        }
        for (var i = 0; i < marker_circles.length; i++) 
        {
          marker_circles[i].setMap(map);
        }
        heatmap.setMap(null);
      }
      else 
      {
        // hide the markers, show the heatmap
        console.log("Heatmap yes");
        for (var i = 0; i < markers.length; i++) 
        {
          markers[i].setMap(null);
        }
        for (var i = 0; i < marker_circles.length; i++) 
        {
          marker_circles[i].setMap(null);
        }
        heatmap.setMap(map);
      }
    })

    plot_well_scale('chart_well_scale', option_name, false_counter, true_counter, total_max, total_min, total_avg, total_sd);

  });
}

function map_well_scale(fields, option)
{
  $("#scale").show();
  $("#scale_values").show();

  var false_counter = 0;
  var true_counter = 0; 
  var total_min = 0; 
  var total_max = 0; 
  var total_avg = 0; 
  var total_sd = 0; 
  zoom_flag_well_scale += 1;

  $.get("{!! url('well_scale') !!}",
  {
    fields: fields,
    option: option
  },
  function(data)
  {
    var map_center; 
    var option_name = $("#well_options option:selected").text();
    $.each(data.center, function(index,value)
    {
      map_center = new google.maps.LatLng(value.lat, value.lon);
    });

    var general_info_total = data.general_info; 
    total_min = general_info_total.min;
    total_max = general_info_total.max;
    total_avg = general_info_total.avg;
    total_sd = general_info_total.sd;

    if(total_min === null && total_max === null)
    {
      $('#b1').text("NA");
      $('#b2').text("NA");
      $('#b3').text("NA");
      $('#b4').text("NA");
    }
    else
    {
      $('#b1').text(total_min.toFixed(2));
      $('#b2').text((total_max/3).toFixed(2));
      $('#b3').text((total_max/2).toFixed(2));
      $('#b4').text(total_max.toFixed(2));
    }

    $("#map").empty();
    $('#alert').empty();
    $("#alert_2").empty();
    if(zoom_flag_well_scale==1)
    {
      zoom = 8;
      center = map_center;
    }
    else
    {
      zoom = map.getZoom();
      center = map.getCenter();
    }
    var map_options = {
        zoom : zoom,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map'), map_options);

    $.each(data.data, function(index,value)
    {
      var field_polygon_points = [];
      var field_coordinates = value[1];
      var well_info = value[2];
      var general_info = value[3];
      var max_value = general_info.max;
      var min_value = general_info.min;
      var avg_value = general_info.avg;
      if(avg_value != null && max_value != null && min_value != null)
      {
        avg_value = avg_value.toFixed(2);
      }
      else
      {
        max_value = "NA";
        avg_value = "NA";
        min_value = "NA";
      }

      for (var j = 0; j<field_coordinates.length; j++) 
      {
        field_polygon_points.push(new google.maps.LatLng(field_coordinates[j].lat,field_coordinates[j].lon));
      }
      if(field_polygon_points.length>0)
      {
        var field_color = '#FF0000';

        var polygon = new google.maps.Polygon({
          paths: field_polygon_points, 
          strokeColor: field_color, 
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: field_color,
          fillOpacity: 0.5
        });

        polygon.setMap(map);
        info_window = new google.maps.InfoWindow();
        google.maps.event.addListener(polygon, 'click', function(e)
        {
          var info_window_string = '<b>*** Field Scale *** </b><br><br>'+'<b>Field: </b>'+value[0]+'<br>'+'<b>Max '+option_name+' Value: </b>'+max_value+'<br>'+'<b>Min '+option_name+' Value: </b>'+min_value+'<br>'+'<b>Average '+option_name+' Value: </b>'+avg_value+'<br>';

          info_window.setContent(info_window_string);
          info_window.setPosition(e.latLng);
          info_window.open(map);

        });
      }

      for (var i = 0; i<well_info.length; i++) 
      {
        var well_coordinate = new google.maps.LatLng(well_info[i].lat, well_info[i].lon);

        if(well_info[i].value)
        {
          var well_icon = 'http://labs.google.com/ridefinder/images/mm_20_brown.png';
          true_counter += 1; 
          /* Asignación de color según el peso del valor del subparámetro consultado */
          var circle_color; 
          var current_value = well_info[i].value;
          var normalized_value = (current_value/max_value)*10;
          switch (true) 
          {
            case (normalized_value > 9 ):  circle_color = '#ff0000'; break;
            case (normalized_value < 9 && normalized_value > 8 ):  circle_color = '#ff7f00'; break;
            case (normalized_value <= 8 && normalized_value > 7 ): circle_color = '#ffc200'; break; 
            case (normalized_value <=7  && normalized_value > 6 ): circle_color = '#ffff00'; break;
            case (normalized_value <= 6 && normalized_value > 5 ): circle_color = '#b5d400'; break;
            case (normalized_value <= 5 && normalized_value > 4 ): circle_color = '#6caa00'; break;
            case (normalized_value <= 4 && normalized_value > 3 ): circle_color = '#008000'; break;
            case (normalized_value <= 3 && normalized_value > 2 ): circle_color = '#506666'; break;
            case (normalized_value <= 2 && normalized_value > 1 ): circle_color = '#5646b1'; break;
            case (normalized_value <= 1 ): circle_color = '#0000ff'; break;
          }
          var circle_options = {
              strokeColor: circle_color,
              strokeOpacity: 0.8,
              strokeWeight: 2,
              fillColor: circle_color,
              fillOpacity: 0.35,
              map: map,
              center: well_coordinate,
              radius: 600
              };
          cityCircle = new google.maps.Circle(circle_options);

        }
        else
        {
          var well_icon = 'http://labs.google.com/ridefinder/images/mm_20_white.png';
          false_counter += 1; 
        }

        var well_marker = new google.maps.Marker(
        {
          position: well_coordinate,
          map: map,
          icon: well_icon
        });

        well_info_window = new google.maps.InfoWindow();
        var well_name = well_info[i].nombre;
        var well_value = well_info[i].value;
        if(!well_value)
        {
          well_value = '<font color=red> There is no value for this well</font>';
        }
        google.maps.event.addListener(well_marker, 'click', (function(well_name, well_coordinate, well_value){ 
          return function()
          {        
            var info_window_string = '<b>*** Well Scale *** </b><br><br>'+'<b>Well: </b>'+well_name+'<br><b>Field: </b>'+value[0]+'<br><b>'+option_name+': </b>'+well_value;
            info_window.setContent(info_window_string);
            info_window.setPosition(well_coordinate);
            info_window.open(map);
          };})(well_name,well_coordinate,well_value));
      }
    });
    plot_well_scale('chart_well_scale', option_name, false_counter, true_counter, total_max, total_min, total_avg, total_sd);

  });
}
function map_well_scale_bool(fields, option)
{
  $("#scale").hide();
  $("#scale_values").hide();

  $("#chart_well_scale").show();
  $("#well_scale_general_data").hide();
  $("#chart_field_scale").hide();
  $("#field_scale_general_data").hide();

  zoom_flag_well_scale += 1;
  var false_counter = 0;
  var true_counter = 0; 
  $.get("{!! url('well_scale') !!}",
  {
    fields: fields,
    option: option
  },
  function(data)
  {
    var map_center; 
    var option_name = $("#well_options option:selected").text();
    $.each(data.center, function(index,value)
    {
      map_center = new google.maps.LatLng(value.lat, value.lon);
    });
    $("#map").empty();
    $('#alert').empty();
    $("#alert_2").empty();
    if(zoom_flag_well_scale==1)
    {
      zoom = 8;
      center = map_center;
    }
    else
    {
      zoom = map.getZoom();
      center = map.getCenter();
    }
    var map_options = {
        zoom : zoom,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map'), map_options);

    $.each(data.data, function(index,value)
    {
      var field_polygon_points = [];
      var field_coordinates = value[1];
      var well_info = value[2];
      var counters = value[3];
      var wells_with_data = counters[0];
      var total_wells = counters[0]+counters[1];
      false_counter += counters[1];
      true_counter += wells_with_data;

      for (var j = 0; j<field_coordinates.length; j++) 
      {
        field_polygon_points.push(new google.maps.LatLng(field_coordinates[j].lat,field_coordinates[j].lon));
      }
      var field_color = '#FF0000';

      var polygon = new google.maps.Polygon({
        paths: field_polygon_points, 
        strokeColor: field_color, 
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: field_color,
        fillOpacity: 0.5
      });

      polygon.setMap(map);
      info_window = new google.maps.InfoWindow();
      google.maps.event.addListener(polygon, 'click', function(e)
      {
        var info_window_string = '<b>*** Field Scale *** </b><br><br>'+'<b>Field: </b>'+value[0]+'<br>'+'<b>Wells with '+option_name+': </b>'+wells_with_data+'/'+total_wells;

        info_window.setContent(info_window_string);
        info_window.setPosition(e.latLng);
        info_window.open(map);

      });

      for (var i = 0; i<well_info.length; i++) 
      {
        var well_coordinate = new google.maps.LatLng(well_info[i][0].lat, well_info[i][0].lon);

        if(well_info[i][1])
        {
          var well_icon = 'http://labs.google.com/ridefinder/images/mm_20_brown.png';
          var well_value = '<font color=green> There is '+option_name+' for this well</font>';
        }
        else
        {
          var well_icon = 'http://labs.google.com/ridefinder/images/mm_20_white.png';
          var well_value = '<font color=red> There is no '+option_name+' for this well</font>';
        }

        var well_marker = new google.maps.Marker(
        {
          position: well_coordinate,
          map: map,
          icon: well_icon
        });

        well_info_window = new google.maps.InfoWindow();
        var well_name = well_info[i][0].nombre;

        google.maps.event.addListener(well_marker, 'click', (function(well_name, well_coordinate, well_value){ 
          return function()
          {        
            var info_window_string = '<b>*** Well Scale *** </b><br><br>'+'<b>Well: </b>'+well_name+'<br><b>Field: </b>'+value[0]+'<br><b>'+option_name+': </b>'+well_value;
            info_window.setContent(info_window_string);
            info_window.setPosition(well_coordinate);
            info_window.open(map);
          };})(well_name,well_coordinate,well_value));
      }
    });
    plot_well_scale_bool('chart_well_scale', option_name, false_counter, true_counter);
  });
}

function plot_well_scale(chart_div, option_name, false_counter, true_counter, max, min, avg, sd)
{
  $("#chart_well_scale").show();
  $("#well_scale_general_data").show();
  $("#chart_field_scale").hide();
  $("#field_scale_general_data").hide();
  $("#well_bool_scale_general_data").hide();

  $('#MaV_well_scale').text(max.toFixed(2));
  $('#MiV_well_scale').text(min.toFixed(2));
  $('#AVG_well_scale').text(avg.toFixed(2));
  $('#SD_well_scale').text(sd.toFixed(2));
  $('#GI_well_scale').text(option_name)
  $('#WIF_well_scale').text((true_counter+false_counter));
  $('#WWM_well_scale').text((true_counter));

  Highcharts.chart(chart_div, {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
          text: 'Wells with '+option_name+' in map'
      },
      tooltip: {
          pointFormat: '<b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                  style: {
                      color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                  }
              }
          }
      },
      series: [{
          name: '',
          colorByPoint: true,
          data: [{
              name: 'Wells without '+option_name,
              y: false_counter
          }, {
              name: 'Wells with '+option_name,
              y: true_counter,
              sliced: true,
              selected: true
          }]
      }]
  });
}
function plot_well_scale_bool(chart_div, option_name, false_counter, true_counter)
{
  $("#chart_well_scale").show();
  $("#well_scale_general_data").hide();
  $("#well_bool_scale_general_data").show();
  $("#chart_field_scale").hide();
  $("#field_scale_general_data").hide();

  $('#GI_well_bool_scale').text(option_name)
  $('#WIF_well_bool_scale').text((true_counter+false_counter));
  $('#WWM_well_bool_scale').text((true_counter));

  Highcharts.chart(chart_div, {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
          text: 'Wells with '+option_name+' in map'
      },
      tooltip: {
          pointFormat: '<b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                  style: {
                      color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                  }
              }
          }
      },
      series: [{
          name: '',
          colorByPoint: true,
          data: [{
              name: 'Wells without '+option_name,
              y: false_counter
          }, {
              name: 'Wells with '+option_name,
              y: true_counter,
              sliced: true,
              selected: true
          }]
      }]
  });
}
function plot_field_scale(chart_div, option_name, false_counter, true_counter)
{
  $("#chart_well_scale").hide();
  $("#chart_field_scale").show();

  $("#well_scale_general_data").hide();
  $("#well_bool_scale_general_data").hide();
  $("#field_scale_general_data").show();

  $('#GI_field_scale').text(option_name)
  $('#WIF_field_scale').text((true_counter+false_counter));
  $('#WWM_field_scale').text((true_counter));

  Highcharts.chart(chart_div, {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
          text: 'Fields with '+option_name+' in map'
      },
      tooltip: {
          pointFormat: '<b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                  style: {
                      color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                  }
              }
          }
      },
      series: [{
          name: '',
          colorByPoint: true,
          data: [{
              name: 'Wells without '+option_name,
              y: false_counter
          }, {
              name: 'Wells with '+option_name,
              y: true_counter,
              sliced: true,
              selected: true
          }]
      }]
  });
}
/** *********** Damage Variables Data ************* */

/* Descripción: esta función se encarga de consultar los datos, construir las estructuras de datos y desplegar en el mapa la información capturada a nivel de campos y para subparámetros regulares. 
Parámetros: 
  parametro: el subparámetro que se quiere consultar
  camposf: los id de los campos sobre los que se quiere consultar
  formacion: id de la formación sobre la que se quiere consultar
  sp: nombre del subparámetro
  puntos: coordenadas de los polígonos de los mapas*/
function map_Field(parametro,camposf,formacion,sp,puntos)
{
  c = c+1;
  cc = cc +1;
  var unit;
  $("#scale").show();
  $("#scale_values").show();
  $.get("{!! url('pozos') !!}",
    {parametro:parametro,
      campo:camposf,
      formacion:formacion},
    function(data){
      $.each(data.unidades, function(index,value)
      {
        unit = value.unidad;
      });

      $.each(data.Centro, function(index,value)
      {
        var lat = value.lat;
        var lon = value.lon;
        $("#chart_div").empty();

        
        google.setOnLoadCallback(chart(data.Chart,sp, unit));

        $("#map").empty();
        $('#alert').empty();
        $("#alert_2").empty();
          if(c == 1 || cc == 1)
          {
            zoom = 8;
            ccenter = new google.maps.LatLng(lat, lon);
          }
          else
          {
            zoom = map.getZoom();
            ccenter = map.getCenter();
          }


        var mapOptions = {
            zoom : zoom,
            center: ccenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);
      });

      $.each(data.General, function(index,value){
        max = value.Maximo;
        min = value.Minimo;
        sd = value.SD;
        avg = value.Media; 
        pb = value.pb;
      });
   
      $.each(data.General2, function(index,value){
        pm = value.pm;
      });

      max = parseFloat(max);
      min = parseFloat(min);
      sd = parseFloat(sd);
      avg = parseFloat(avg);
      
      $('#MaV').text(max.toFixed(2));
      $('#MiV').text(min.toFixed(2));
      $('#AVG').text(avg.toFixed(2));
      $('#SD').text(sd.toFixed(2));
      $('#GI').text(sp+" ["+unit+"]")
      $('#WIF').text((pb+pm));
      $('#WWM').text((pb));
      $('#b1').text(min.toFixed(2));
      $('#b2').text((max/3).toFixed(2));
      $('#b3').text((max/2).toFixed(2));
      $('#b4').text(max.toFixed(2));

      var canombres=[];
      var avgcampos=[];
      var sdcampos=[];
      var capoz = []; //Pozos con datos
      var pozoscampos = []; //Pozos en el campo
      var mincampos = [];
      var maxcampos = [];
      $.each(data.Gencampos,function(index,value)
      {
        
        $.each(value,function(index,value)
        {

          canombres.push(value.cnombre);
          avgcampos.push(value.avg);
          sdcampos.push(value.sd);
          capoz.push(value.count);
          mincampos.push(value.min);
          maxcampos.push(value.max);
          

        });
      });

      $.each(data.PozosC, function(index,value)
      {
        $.each(value,function(index,value)
        {
          pozoscampos.push(value.count);
        });
      });

      var jc=0;
      $.each(data.Coordenadasc, function(index,value)
      {
        var cnombre = canombres[jc];
        var cavg = parseFloat(avgcampos[jc]);
        var cami = parseFloat(mincampos[jc]);
        var camx = parseFloat(maxcampos[jc]);
        var casd = parseFloat(sdcampos[jc]);
        
        var cap = capoz[jc];
        var pozc = pozoscampos[jc];

        puntos.length=0;

        var color; 
        var r2 = (cavg/max)*10;
        
        switch (true) {
          case (r2 > 9 ):  color = '#ff0000'; break;
          case (r2 < 9 && r2 > 8 ):  color = '#ff7f00'; break;
          case (r2 <= 8 && r2 > 7 ): color = '#ffc200'; break; 
          case (r2 <=7  && r2 > 6 ): color = '#ffff00'; break;
          case (r2 <= 6 && r2 > 5 ): color = '#b5d400'; break;
          case (r2 <= 5 && r2 > 4 ): color = '#6caa00'; break;
          case (r2 <= 4 && r2 > 3 ): color = '#008000'; break;
          case (r2 <= 3 && r2 > 2 ): color = '#506666'; break;
          case (r2 <= 2 && r2 > 1 ): color = '#5646b1'; break;
          case (r2 <= 1 ): color = '#0000ff'; break;
        }
        
        $.each(value,function(index,value)
        {

        puntos.push(new google.maps.LatLng(value.lat,value.lon));
        
        });
      

        var Poligono = new google.maps.Polygon({
        paths: puntos,
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: color,
        fillOpacity: 0.5
      });

      Poligono.setMap(map);
      
      infoWindow = new google.maps.InfoWindow();

      google.maps.event.addListener(Poligono, 'click', function(e) {
        var contentString = '<b>*** Field Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>These are data for all field\'s formations</b> '+'<br><br><b>Average '+sp+' value: </b>' + cavg.toFixed(2)+
          '<br><b>Minimum  value: </b>' + cami.toFixed(2)+
          '<br><b>Maximum  value: </b>' + camx.toFixed(2)+

          '<br><b>Standard deviation '+sp+' value:</b> ' + casd.toFixed(2)+
          '<br><b>Wells in field: <b> '+pozc+
          '<br><b>Wells in field with data: <b> '+cap;

        infoWindow.setContent(contentString);
        infoWindow.setPosition(e.latLng);
        
        infoWindow.open(map);
      });

      jc++;
    });
  });
}


/* Descripción: esta función se encarga de consultar los datos, construir las estructuras de datos y desplegar en el mapa la información capturada a nivel de pozos y para subparámetros regulares. 
Parámetros: 
  parametro: el subparámetro que se quiere consultar
  camposf: los id de los campos sobre los que se quiere consultar
  formacion: id de la formación sobre la que se quiere consultar
  sp: nombre del subparámetro
  puntos: coordenadas de los pozos que se desplegarán en el mapa*/
function map_Well(op,parametro,camposf,formacion,sp,puntos)
{
    c = c+1;
    cc = cc+1;
    var unit;
    $("#scale").show();
    $("#scale_values").show();
    $.get("{!! url('pozos') !!}",
    {parametro:parametro,
      campo:camposf,
      formacion:formacion},
    function(data)
    {
      switch(op)
      {
        case 1:

          tipo_pozos = data.Pozos;
          tipo_info = "Last data";
          break;
        case 2:

          tipo_pozos = data.Pozosavg;
          tipo_info = "Average data";
          break;
        case 3: 

          tipo_pozos = data.Pozosmax;
          tipo_info = "Maximum data";
          break;
        case 4: 

          tipo_pozos = data.Pozosmin;
          tipo_info = "Minimum data";
          break;
      }

      $.each(data.unidades, function(index,value) {
        unit = value.unidad;
      });

      $.each(data.Centro, function(index,value) {
        var lat = value.lat;
        var lon = value.lon;

        $("#chart_div").empty();

        google.setOnLoadCallback(chart(data.Chart,sp,unit));

        $("#map").empty();
        $('#alert').empty();
        $("#alert_2").empty();
        if(c == 1 || cc == 1)
        {
          zoom = 8;
          ccenter = new google.maps.LatLng(lat, lon);
        }
        else
        {
          zoom = map.getZoom();
          ccenter = map.getCenter();
        }

        var mapOptions = {
            zoom : zoom,
            center: ccenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);
      });

      $.each(data.General, function(index,value) {
        max = value.Maximo;
        min = value.Minimo;
        sd = value.SD;
        avg = value.Media; 
        pb = value.pb;

      });

      $.each(data.General2, function(index,value) {
          pm = value.pm;
      });  

      max = parseFloat(max);
      min = parseFloat(min);
      sd = parseFloat(sd);
      avg = parseFloat(avg);
      
      $('#MaV').text(!isNaN(max) ? max.toFixed(2) : '-');
      $('#MiV').text(!isNaN(min) ? min.toFixed(2) : '-');
      $('#AVG').text(!isNaN(avg) ? avg.toFixed(2) : '-');
      $('#SD').text(!isNaN(sd) ? sd.toFixed(2) : '-');
      $('#GI').text(sp+" ["+unit+"]")
      $('#WIF').text((pb+pm));
      $('#WWM').text((pb));

      if (!isNaN(max) && !isNaN(min) && !isNaN(avg) && !isNaN(sd)) {
        $('#empty_scale_values').hide();
        $('#scale_values').show();

        $('#b1').text(min.toFixed(2));
        $('#b2').text((max/3).toFixed(2));
        $('#b3').text((max/2).toFixed(2));
        $('#b4').text(max.toFixed(2));
      } else {
        $('#empty_scale_values').show();
        $('#scale_values').hide();
      }

      $.each(tipo_pozos, function(index, value) {
        cs = value.Cnombre;  
        pt=new google.maps.LatLng(value.lat, value.lon);
        var val;
        var marker = new google.maps.Marker({
          position:pt ,
          map:map,
          icon: 'http://labs.google.com/ridefinder/images/mm_20_brown.png'
        });
        val = parseFloat(value.valor);
        val = val.toFixed(2);
        var infoW = new google.maps.InfoWindow({
          content:"<b>"+tipo_info +" </b><BR><b>Well Name: </b>"+value.nombre + " <b>Field: </b>"+cs+" <BR><b>"+ sp +"</b> : "+val +" <BR><b>Comment</b> - "+value.comentario+" <BR><b>Measure date</b> - "+value.fecha
        });

        marker.addListener('click', function()
        {

          infoW.open(map,marker);
          currentPopup=infoW;
        });
        google.maps.event.addListener(infoW, "closeclick", function () 
        {
          map.panTo(center);
          currentPopup = null;
        });

        /* Asignación de color según el peso del valor del subparámetro consultado */
        var color; 
        var r2 = (val/max)*10;
        switch (true) 
        {
          case (r2 > 9 ):  color = '#ff0000'; break;
          case (r2 < 9 && r2 > 8 ):  color = '#ff7f00'; break;
          case (r2 <= 8 && r2 > 7 ): color = '#ffc200'; break; 
          case (r2 <=7  && r2 > 6 ): color = '#ffff00'; break;
          case (r2 <= 6 && r2 > 5 ): color = '#b5d400'; break;
          case (r2 <= 5 && r2 > 4 ): color = '#6caa00'; break;
          case (r2 <= 4 && r2 > 3 ): color = '#008000'; break;
          case (r2 <= 3 && r2 > 2 ): color = '#506666'; break;
          case (r2 <= 2 && r2 > 1 ): color = '#5646b1'; break;
          case (r2 <= 1 ): color = '#0000ff'; break;
        }       

        var populationOptions = {
          strokeColor: color,
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: color,
          fillOpacity: 0.35,
          map: map,
          center: pt,
          radius: 600
        };

        cityCircle = new google.maps.Circle(populationOptions);
      });

      var canombres=[];
      var avgcampos=[];
      var sdcampos=[];
      var capoz = []; //Pozos con datos
      var pozoscampos = []; //Pozos en el campo
    
      $.each(data.Gencampos,function(index,value) {
        $.each(value,function(index,value) {
          canombres.push(value.cnombre);
          avgcampos.push(value.avg);
          sdcampos.push(value.sd);
          capoz.push(value.count);
        });
      });

      $.each(data.PozosC, function(index,value) {
        $.each(value,function(index,value) {
          pozoscampos.push(value.count);
        });
      });
    
      var j=0;
      $.each(data.Coordenadas, function(index,value) {
        var cnombre = canombres[j];
        var cavg = parseFloat(avgcampos[j]);
        var casd = parseFloat(sdcampos[j]);
        var cap = capoz[j];

        puntos.length=0;
        
        $.each(value,function(index,value) {
          puntos.push(new google.maps.LatLng(value.lat,value.lon));
        });

        var Poligono = new google.maps.Polygon({
          paths: puntos,
          strokeColor: '#EFFF00',
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: '#EFFF00',
          fillOpacity: 0.05
        });

        Poligono.setMap(map);
        
        infoWindow = new google.maps.InfoWindow();

        google.maps.event.addListener(Poligono, 'click', function(e) {
          var contentString = '<b>*** Producing interval Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>Average '+sp+' value: </b>' + avg.toFixed(2)+
            '<br><b>Standard deviation '+sp+' value:</b> ' + sd.toFixed(2);
          infoWindow.setContent(contentString);
          infoWindow.setPosition(e.latLng);
          infoWindow.open(map);
        });

        j++;
      });

      var jc=0;
      $.each(data.Coordenadasc, function(index,value) {
        var cnombre = canombres[jc];
        var cavg = parseFloat(avgcampos[jc]);
        var casd = parseFloat(sdcampos[jc]);
        var cap = capoz[jc];
        var pozc = pozoscampos[jc];

        puntos.length=0;
        $.each(value,function(index,value) {
          puntos.push(new google.maps.LatLng(value.lat,value.lon));
        });

        var Poligono = new google.maps.Polygon({
          paths: puntos,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: '#FF0000',
          fillOpacity: 0.1
        });

        Poligono.setMap(map);
        
        infoWindow = new google.maps.InfoWindow();

        google.maps.event.addListener(Poligono, 'click', function(e) {
          var contentString = '<b>*** Field Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>These are data for all field\'s formations</b> '+'<br><br><b>Average '+sp+' value: </b>' + cavg.toFixed(2)+
            '<br><b>Standard deviation '+sp+' value:</b> ' + casd.toFixed(2)+
            '<br><b>Wells in field: <b> '+pozc+
            '<br><b>Wells in field with data: <b> '+cap;

          infoWindow.setContent(contentString);
          infoWindow.setPosition(e.latLng);
          
          infoWindow.open(map);
        });

        jc++;
      });

      $.each(data.Pozos2, function(index, value) {
        var cs = value.Cnombre;
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(value.lat, value.lon),
          map:map,
          icon: 'http://labs.google.com/ridefinder/images/mm_20_white.png'
        });

        var infoW = new google.maps.InfoWindow({
          content: " <b>Well name: </b>"+value.nombre + "- <b>Field: </b>"+cs+" <BR> There's no measure for this well" 
        });

        marker.addListener('click', function() {
          infoW.open(map,marker);
          currentPopup=infoW;
        });
        google.maps.event.addListener(infoW, "closeclick", function () {
          map.panTo(center);
          currentPopup = null;
        });
      });
    });
}

/* Descripción: esta función se encarga de consultar los datos, construir las estructuras de datos y desplegar en el mapa la información capturada a nivel de campos y para variables de configuración de daño. 
Parámetros: 
  filtrox: la variable de configuración de daño que se quiere consultar
  camposf: los id de los campos sobre los que se quiere consultar
  formacion: id de la formación sobre la que se quiere consultar
  sp: nombre del subparámetro
  puntos: coordenadas de los pozos que se desplegarán en el mapa*/
function mapConfDano_Field(filtrox,camposf,formacion,sp,puntos)
{

  c = c+1;
  cc = cc +1;
  var unit;
  $("#scale").show();
  $("#scale_values").show();
  $.get("{!! url('configdano') !!}",
    {varcon:filtrox,
      campo:camposf,
      formacion:formacion},
    function(data){

      $.each(data.Unidad, function(index,value)
      {
        unit = value.unidad;
      });

      $.each(data.Centro, function(index,value)
      {
        var lat = value.lat;
        var lon = value.lon;

        $("#chart_div").empty();

        
        google.setOnLoadCallback(chart(data.Chart,sp,unit));

        $("#map").empty();
        $('#alert').empty();
        $("#alert_2").empty();
          if(c == 1 || cc == 1)
          {
            zoom = 8;
            ccenter = new google.maps.LatLng(lat, lon);
          }
          else
          {
            zoom = map.getZoom();
            ccenter = map.getCenter();
          }


        var mapOptions = {
            zoom : zoom,
            center: ccenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
      });

      $.each(data.General, function(index,value){
        max = value.Maximo;
        min = value.Minimo;
        sd = value.SD;
        avg = value.Media; 
        pb = value.pb;

      });     
      $.each(data.General2, function(index,value){
        pm = value.pm;
      });  

      max = parseFloat(max);
      min = parseFloat(min);
      sd = parseFloat(sd);
      avg = parseFloat(avg);
      
      $('#MaV').text(max.toFixed(2));
      $('#MiV').text(min.toFixed(2));
      $('#AVG').text(avg.toFixed(2));
      $('#SD').text(sd.toFixed(2));
      $('#GI').text(sp+" ["+unit+"]")
      $('#WIF').text((pb+pm));
      $('#WWM').text((pb));
      $('#b1').text(min.toFixed(2));
      $('#b2').text((max/3).toFixed(2));
      $('#b3').text((max/2).toFixed(2));
      $('#b4').text(max.toFixed(2));

    var canombres=[];
    var avgcampos=[];
    var sdcampos=[];
    var capoz = []; //Pozos con datos
    var pozoscampos = []; //Pozos en el campo
    var mincampos = [];
    var maxcampos = [];

    $.each(data.Gencampos,function(index,value)
    {
      
      $.each(value,function(index,value)
      {

        canombres.push(value.cnombre);
        avgcampos.push(value.avg);
        sdcampos.push(value.sd);
        capoz.push(value.count);
        mincampos.push(value.min);
        maxcampos.push(value.max);

      });
    });

    $.each(data.PozosC, function(index,value)
    {
      $.each(value,function(index,value)
      {
        pozoscampos.push(value.count);
      });
    });


    var jc=0;
     $.each(data.Coordenadasc, function(index,value)
     {
      
     
      
      var cnombre = canombres[jc];
      var cavg = parseFloat(avgcampos[jc]);
      var cami = parseFloat(mincampos[jc]);
      var casd = parseFloat(sdcampos[jc]);
      var camx = parseFloat(maxcampos[jc]);
      var casd = parseFloat(sdcampos[jc]);
      var cap = capoz[jc];
      var pozc = pozoscampos[jc];

      puntos.length=0;

                var color; 
                var r2 = (cavg/max)*10;
               
                switch (true) {
                           case (r2 > 9 ):  color = '#ff0000'; break;
                           case (r2 < 9 && r2 > 8 ):  color = '#ff7f00'; break;
                           case (r2 <= 8 && r2 > 7 ): color = '#ffc200'; break; 
                           case (r2 <=7  && r2 > 6 ): color = '#ffff00'; break;
                           case (r2 <= 6 && r2 > 5 ): color = '#b5d400'; break;
                           case (r2 <= 5 && r2 > 4 ): color = '#6caa00'; break;
                           case (r2 <= 4 && r2 > 3 ): color = '#008000'; break;
                           case (r2 <= 3 && r2 > 2 ): color = '#506666'; break;
                           case (r2 <= 2 && r2 > 1 ): color = '#5646b1'; break;
                           case (r2 <= 1 ): color = '#0000ff'; break;
                       }
      
      $.each(value,function(index,value)
      {

      puntos.push(new google.maps.LatLng(value.lat,value.lon));
      
      });
    

      var Poligono = new google.maps.Polygon({
      paths: puntos,
      strokeColor: color,
      strokeOpacity: 0.8,
      strokeWeight: 3,
      fillColor: color,
      fillOpacity: 0.5
       });

      Poligono.setMap(map);
      
      infoWindow = new google.maps.InfoWindow();

      google.maps.event.addListener(Poligono, 'click', function(e)
    {

                var contentString = '<b>*** Field Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>These are data for all field\'s formations</b> '+'<br><br><b>Average '+sp+' value: </b>' + cavg.toFixed(2)+
                                    '<br><b>Minimum  value: </b>' + cami.toFixed(2)+
                                    '<br><b>Maximum  value: </b>' + camx.toFixed(2)+
                                    '<br><b>Standard deviation '+sp+' value:</b> ' + casd.toFixed(2)+
                                    '<br><b>Wells in field: <b> '+pozc+
                                    '<br><b>Wells in field with data: <b> '+cap;

                infoWindow.setContent(contentString);
                infoWindow.setPosition(e.latLng);
                
                infoWindow.open(map);
   
    });

  jc++;

      
     });

    }); 
}

/* Descripción: esta función se encarga de consultar los datos, construir las estructuras de datos y desplegar en el mapa la información capturada a nivel de pozos y para variables de configuración de daño. 
Parámetros: 
  op: opción de despliegue. average, minimum, maximum. 
  filtrox: la variable de configuración de daño que se quiere consultar
  camposf: los id de los campos sobre los que se quiere consultar
  formacion: id de la formación sobre la que se quiere consultar
  sp: nombre del subparámetro
  puntos: coordenadas de los pozos que se desplegarán en el mapa*/ 
function mapConfDan_Well(op,filtrox,camposf,formacion,sp,puntos)
{

  c = c+1;
  cc = cc +1;
  var unit;
  $("#scale").show();
  $("#scale_values").show();
    $.get("{!! url('configdano') !!}",
    {varcon:filtrox,
      campo:camposf,
      formacion:formacion},
    function(data){


      switch(op)
      {
          case 1:

            tipo_pozos = data.Pozos;
            tipo_info = "Last data";
            break;
          case 2:

            tipo_pozos = data.Pozosavg;
            tipo_info = "Average data";
            break;
          case 3: 

            tipo_pozos = data.Pozosmax;
            tipo_info = "Maximum data";
            break;
          case 4: 

            tipo_pozos = data.Pozosmin;
            tipo_info = "Minimum data";
            break;

      }

      $.each(data.Unidad, function(index,value)
      {
        unit = value.unidad;
      });

      $.each(data.Centro, function(index,value)
      {
        var lat = value.lat;
        var lon = value.lon;

        $("#chart_div").empty();

        
        google.setOnLoadCallback(chart(data.Chart,sp,unit));

        $("#map").empty();
        $('#alert').empty();
        $("#alert_2").empty();
          if(c == 1 || cc == 1)
          {
            zoom = 8;
            ccenter = new google.maps.LatLng(lat, lon);
          }
          else
          {
            zoom = map.getZoom();
            ccenter = map.getCenter();
          }


        var mapOptions = {
            zoom : zoom,
            center: ccenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);
      });



      

      $.each(data.General, function(index,value){
        max = value.Maximo;
        min = value.Minimo;
        sd = value.SD;
        avg = value.Media; 
        pb = value.pb;

      });     
     $.each(data.General2, function(index,value){
        pm = value.pm;

      });  

      max = parseFloat(max);
      min = parseFloat(min);
      sd = parseFloat(sd);
      avg = parseFloat(avg);
      
      $('#MaV').text(max.toFixed(2));
      $('#MiV').text(min.toFixed(2));
      $('#AVG').text(avg.toFixed(2));
      $('#SD').text(sd.toFixed(2));
      $('#GI').text(sp+" ["+unit+"]")
      $('#WIF').text((pb+pm));
      $('#WWM').text((pb));

      $('#b1').text(min.toFixed(2));
      $('#b2').text((max/3).toFixed(2));
      $('#b3').text((max/2).toFixed(2));
      $('#b4').text(max.toFixed(2));
      $.each(tipo_pozos, function(index, value){
      cs = value.Cnombre;  
      pt=new google.maps.LatLng(value.lat, value.lon);
      var val;
      var marker = new google.maps.Marker({
        position:pt ,
        map:map,
        icon: 'http://labs.google.com/ridefinder/images/mm_20_brown.png'
  });
      val = parseFloat(value.valor);
      val = val.toFixed(2);

      var infoW = new google.maps.InfoWindow({
        content:"<b>"+tipo_info+"  </b><BR><b>Well Name: </b>"+value.nombre + " <b>Field: </b>"+cs+" <BR><b>"+ sp +"</b> : "+val +" <BR><b>Comment</b> - "+value.Comentario+" <BR><b>Measure date</b> - "+value.fecha
      });


      marker.addListener('click', function(){

        infoW.open(map,marker);
        currentPopup=infoW;
      });
                      google.maps.event.addListener(infoW, "closeclick", function () {
                    map.panTo(center);
                    currentPopup = null;
                });


                var color; 
                var r2 = (val/max)*10;
               
                switch (true) {
                           case (r2 > 9 ):  color = '#ff0000'; break;
                           case (r2 < 9 && r2 > 8 ):  color = '#ff7f00'; break;
                           case (r2 <= 8 && r2 > 7 ): color = '#ffc200'; break; 
                           case (r2 <=7  && r2 > 6 ): color = '#ffff00'; break;
                           case (r2 <= 6 && r2 > 5 ): color = '#b5d400'; break;
                           case (r2 <= 5 && r2 > 4 ): color = '#6caa00'; break;
                           case (r2 <= 4 && r2 > 3 ): color = '#008000'; break;
                           case (r2 <= 3 && r2 > 2 ): color = '#506666'; break;
                           case (r2 <= 2 && r2 > 1 ): color = '#5646b1'; break;
                           case (r2 <= 1 ): color = '#0000ff'; break;
                       }

                      

                var populationOptions = {
                     strokeColor: color,
                     strokeOpacity: 0.8,
                     strokeWeight: 2,
                     fillColor: color,
                     fillOpacity: 0.35,
                     map: map,
                     center: pt,
                     radius: 600
                   };


                   
                   cityCircle = new google.maps.Circle(populationOptions);


      });

    var canombres=[];
    var avgcampos=[];
    var sdcampos=[];
    var capoz = []; //Pozos con datos
    var pozoscampos = []; //Pozos en el campo


    
    $.each(data.Gencampos,function(index,value)
    {
      
      $.each(value,function(index,value)
      {

        canombres.push(value.cnombre);
        avgcampos.push(value.avg);
        sdcampos.push(value.sd);
        capoz.push(value.count);
        

      });
    });

    $.each(data.PozosC, function(index,value)
    {
      $.each(value,function(index,value)
      {
        pozoscampos.push(value.count);
      });
    });
    
     var j=0;
     $.each(data.Coordenadas, function(index,value)
     {
      
     
      
      var cnombre = canombres[j];
      var cavg = parseFloat(avgcampos[j]);
      
      var casd = parseFloat(sdcampos[j]);
      
      var cap = capoz[j];

      puntos.length=0;

      
      $.each(value,function(index,value)
      {

      puntos.push(new google.maps.LatLng(value.lat,value.lon));
      
      });
    

    var Poligono = new google.maps.Polygon({
    paths: puntos,
    strokeColor: '#EFFF00',
    strokeOpacity: 0.8,
    strokeWeight: 3,
    fillColor: '#EFFF00',
    fillOpacity: 0.05
     });

      Poligono.setMap(map);
      
      infoWindow = new google.maps.InfoWindow();

      google.maps.event.addListener(Poligono, 'click', function(e)
    {

                var contentString = '<b>*** Producing interval Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>Average '+sp+' value: </b>' + avg.toFixed(2)+
                                    '<br><b>Standard deviation '+sp+' value:</b> ' + sd.toFixed(2);

                infoWindow.setContent(contentString);
                infoWindow.setPosition(e.latLng);
                
                infoWindow.open(map);
   
    });

  j++;

      
     });

    var jc=0;
     $.each(data.Coordenadasc, function(index,value)
     {
      
     
      
      var cnombre = canombres[jc];
      var cavg = parseFloat(avgcampos[jc]);
      
      var casd = parseFloat(sdcampos[jc]);
      
      var cap = capoz[jc];
      var pozc = pozoscampos[jc];

      puntos.length=0;

      
      $.each(value,function(index,value)
      {

      puntos.push(new google.maps.LatLng(value.lat,value.lon));
      
      });
    

    var Poligono = new google.maps.Polygon({
    paths: puntos,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 3,
    fillColor: '#FF0000',
    fillOpacity: 0.1
     });

      Poligono.setMap(map);
      
      infoWindow = new google.maps.InfoWindow();

      google.maps.event.addListener(Poligono, 'click', function(e)
    {

                var contentString = '<b>*** Field Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>These are data for all field\'s formations</b> '+'<br><br><b>Average '+sp+' value: </b>' + cavg.toFixed(2)+
                                    '<br><b>Standard deviation '+sp+' value:</b> ' + casd.toFixed(2)+
                                    '<br><b>Wells in field: <b> '+pozc+
                                    '<br><b>Wells in field with data: <b> '+cap;

                infoWindow.setContent(contentString);
                infoWindow.setPosition(e.latLng);
                
                infoWindow.open(map);
   
    });

  jc++;

      
     });


      $.each(data.Pozos2, function(index, value){
        
      var cs = value.Cnombre;
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(value.lat, value.lon),
        map:map,
        icon: 'http://labs.google.com/ridefinder/images/mm_20_white.png'
  });

      var infoW = new google.maps.InfoWindow({
        content: " <b>Well name: </b>"+value.nombre + "- <b>Field: </b>"+cs+" <BR> There's no measure for this well" 
      });

      marker.addListener('click', function(){

        infoW.open(map,marker);
        currentPopup=infoW;
      });
                      google.maps.event.addListener(infoW, "closeclick", function () {
                    map.panTo(center);
                    currentPopup = null;
                });

      });


    });
}


/* Descripción: esta función se encarga de consultar los datos, construir las estructuras de datos y desplegar en el mapa la información capturada a nivel de campos y para variables de daño. 
Parámetros: 
  parametro: el subparámetro que se quiere consultar
  camposf: los id de los campos sobre los que se quiere consultar
  formacion: id de la formación sobre la que se quiere consultar
  sp: nombre del subparámetro
  puntos: coordenadas de los pozos que se desplegarán en el mapa*/
function mapIsVd_Field(parametro,camposf,formacion,sp,puntos)
{
  c = c+1;
  cc = cc+1;
  var unit;
  $("#scale").show();
  $("#scale_values").show();
  $.get("{!! url('pozosvd') !!}",
    {vardan:parametro,
      campo:camposf,
      formacion:formacion},
    function(data){

      $.each(data.Unidad, function(index,value)
      {
        unit = value.unidad;
      });
      $.each(data.Centro, function(index,value)
      {
        var lat = value.lat;
        var lon = value.lon;

        $("#chart_div").empty();

        
        google.setOnLoadCallback(chart(data.Chart,sp,unit));

        $("#map").empty();
        $('#alert').empty();
        $("#alert_2").empty();
          if(c == 1 || cc == 1)
          {
            zoom = 8;
            ccenter = new google.maps.LatLng(lat, lon);
          }
          else
          {
            zoom = map.getZoom();
            ccenter = map.getCenter();
          }


        var mapOptions = {
            zoom : zoom,
            center: ccenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);
      });

      $.each(data.General, function(index,value){
        max = value.Maximo;
        min = value.Minimo;
        sd = value.SD;
        avg = value.Media; 
        pb = value.pb;


      });     
      $.each(data.General2, function(index,value){
        pm = value.pm;
      });  

      max = parseFloat(max);
      min = parseFloat(min);
      sd = parseFloat(sd);
      avg = parseFloat(avg);
      
      $('#MaV').text(max.toFixed(2));
      $('#MiV').text(min.toFixed(2));
      $('#AVG').text(avg.toFixed(2));
      $('#SD').text(sd.toFixed(2));
      $('#GI').text(sp+" ["+unit+"]")
      $('#WIF').text((pb+pm));
      $('#WWM').text((pb));
      $('#b1').text(min.toFixed(2));
      $('#b2').text((max/3).toFixed(2));
      $('#b3').text((max/2).toFixed(2));
      $('#b4').text(max.toFixed(2));

    var canombres=[];
    var avgcampos=[];
    var maxcampos= [];
    var mincampos = [];
    var sdcampos=[];
    var capoz = []; //Pozos con datos
    var pozoscampos = []; //Pozos en el campo

    $.each(data.Gencampos,function(index,value)
    {
      
      $.each(value,function(index,value)
      {

        canombres.push(value.cnombre);
        avgcampos.push(value.avg);
        mincampos.push(value.min);
        maxcampos.push(value.max);
        sdcampos.push(value.sd);
        capoz.push(value.count);


      });
    });

    $.each(data.PozosC, function(index,value)
    {
      $.each(value,function(index,value)
      {
        pozoscampos.push(value.count);
      });
    });

    var jc=0;
     $.each(data.Coordenadasc, function(index,value)
     {
      
     
      
      var cnombre = canombres[jc];
      var cavg = parseFloat(avgcampos[jc]);

      var cami = parseFloat(mincampos[jc]);
      var camx = parseFloat(maxcampos[jc]);
      var casd = parseFloat(sdcampos[jc]);

      var cap = capoz[jc];
      var pozc = pozoscampos[jc];

      puntos.length=0;

                var color; 
                var r2 = (cavg/max)*10;
               
                switch (true) {
                           case (r2 > 9 ):  color = '#ff0000'; break;
                           case (r2 < 9 && r2 > 8 ):  color = '#ff7f00'; break;
                           case (r2 <= 8 && r2 > 7 ): color = '#ffc200'; break; 
                           case (r2 <=7  && r2 > 6 ): color = '#ffff00'; break;
                           case (r2 <= 6 && r2 > 5 ): color = '#b5d400'; break;
                           case (r2 <= 5 && r2 > 4 ): color = '#6caa00'; break;
                           case (r2 <= 4 && r2 > 3 ): color = '#008000'; break;
                           case (r2 <= 3 && r2 > 2 ): color = '#506666'; break;
                           case (r2 <= 2 && r2 > 1 ): color = '#5646b1'; break;
                           case (r2 <= 1 ): color = '#0000ff'; break;
                       }
      
      $.each(value,function(index,value)
      {

      puntos.push(new google.maps.LatLng(value.lat,value.lon));
      
      });
    

      var Poligono = new google.maps.Polygon({
      paths: puntos,
      strokeColor: color,
      strokeOpacity: 0.8,
      strokeWeight: 3,
      fillColor: color,

      fillOpacity: 0.5

       });

      Poligono.setMap(map);
      
      infoWindow = new google.maps.InfoWindow();

      google.maps.event.addListener(Poligono, 'click', function(e)
    {

                var contentString = '<b>*** Field Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>These are data for all field\'s formations</b> '+'<br><br><b>Average '+sp+' value: </b>' + cavg.toFixed(2)+

                                    '<br><b>Minimum  value: </b>' + cami.toFixed(2)+
                                    '<br><b>Maximum  value: </b>' + camx.toFixed(2)+
                                    '<br><b>Standard deviation '+sp+' value:</b> ' + casd.toFixed(2)+
                                    '<br><b>Wells in field: <b> '+pozc+
                                    '<br><b>Wells in field with data: <b> '+cap;

                infoWindow.setContent(contentString);
                infoWindow.setPosition(e.latLng);
                
                infoWindow.open(map);
   
    });

  jc++;

      
     });

    }); 
}



/* Descripción: esta función se encarga de consultar los datos, construir las estructuras de datos y desplegar en el mapa la información capturada a nivel de pozos y para variables de daño. 
Parámetros: 
  parametro: el subparámetro que se quiere consultar
  camposf: los id de los campos sobre los que se quiere consultar
  formacion: id de la formación sobre la que se quiere consultar
  sp: nombre del subparámetro
  puntos: coordenadas de los pozos que se desplegarán en el mapa*/
function mapIsVd_Well(op, parametro,camposf,formacion,sp, puntos)
{
    c = c+1;
    cc = cc +1;
    var tipo_pozos;
    var tipo_info;
    var unit;
    $("#scale").show();
    $("#scale_values").show();
    $.get("{!! url('pozosvd') !!}",
    {vardan:parametro,
      campo:camposf,
      formacion:formacion},
    function(data){


      switch(op)
      {
          case 1:

            tipo_pozos = data.Pozos;
            tipo_info = "Last data";
            break;
          case 2:

            tipo_pozos = data.Pozosavg;
            tipo_info = "Average data";
            break;
          case 3: 

            tipo_pozos = data.Pozosmax;
            tipo_info = "Maximum data";
            break;
          case 4: 

            tipo_pozos = data.Pozosmin;
            tipo_info = "Minimum data";
            break;

      }

      $.each(data.Unidad, function(index,value)
      {
        unit = value.unidad;
      });
      $.each(data.Centro, function(index,value)
      {
        var lat = value.lat;
        var lon = value.lon;


        $("#chart_div").empty();

        
        google.setOnLoadCallback(chart(data.Chart,sp,unit));

        $("#map").empty();
        $('#alert').empty();
        $("#alert_2").empty();
          if(c == 1 || cc == 1)
          {
            zoom = 8;
            ccenter = new google.maps.LatLng(lat, lon);
          }
          else
          {
            zoom = map.getZoom();
            ccenter = map.getCenter();
          }


        var mapOptions = {
            zoom : zoom,
            center: ccenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);

      });

      $.each(data.General, function(index,value){
        max = value.Maximo;
        min = value.Minimo;
        sd = value.SD;
        avg = value.Media; 
        pb = value.pb;

      });     

     $.each(data.General2, function(index,value){
        pm = value.pm;

      });  

      max = parseFloat(max);
      min = parseFloat(min);
      sd = parseFloat(sd);
      avg = parseFloat(avg);
      
      $('#MaV').text(max.toFixed(2));
      $('#MiV').text(min.toFixed(2));
      $('#AVG').text(avg.toFixed(2));
      $('#SD').text(sd.toFixed(2));
      $('#GI').text(sp+" ["+unit+"]")
      $('#WIF').text((pb+pm));
      $('#WWM').text((pb));

      $('#b1').text(min.toFixed(2));
      $('#b2').text((max/3).toFixed(2));
      $('#b3').text((max/2).toFixed(2));
      $('#b4').text(max.toFixed(2));
      $.each(tipo_pozos, function(index, value){
      cs = value.Cnombre;  
      pt=new google.maps.LatLng(value.lat, value.lon);
      var val;
      var marker = new google.maps.Marker({
        position:pt ,
        map:map,
        icon: 'http://labs.google.com/ridefinder/images/mm_20_brown.png'
    });
      val = parseFloat(value.valor);
      val = val.toFixed(2);
      var sp=$('#Parameter').find("option:selected").text();
      var infoW = new google.maps.InfoWindow({
        content:"<b>"+tipo_info+"  </b><BR><b>Well Name: </b>"+value.nombre + " <b>Field: </b>"+cs+" <BR><b>"+ sp +"</b> : "+val +" <BR><b>Comment</b> - "+value.comentario+" <BR><b>Measure date</b> - "+value.fecha
      });


      marker.addListener('click', function(){

        infoW.open(map,marker);
        currentPopup=infoW;
      });
                      google.maps.event.addListener(infoW, "closeclick", function () {
                    map.panTo(center);
                    currentPopup = null;
                });


                var color; 
                var r2 = (val/max)*10;
               
                switch (true) {
                           case (r2 > 9 ):  color = '#ff0000'; break;
                           case (r2 < 9 && r2 > 8 ):  color = '#ff7f00'; break;
                           case (r2 <= 8 && r2 > 7 ): color = '#ffc200'; break; 
                           case (r2 <=7  && r2 > 6 ): color = '#ffff00'; break;
                           case (r2 <= 6 && r2 > 5 ): color = '#b5d400'; break;
                           case (r2 <= 5 && r2 > 4 ): color = '#6caa00'; break;
                           case (r2 <= 4 && r2 > 3 ): color = '#008000'; break;
                           case (r2 <= 3 && r2 > 2 ): color = '#506666'; break;
                           case (r2 <= 2 && r2 > 1 ): color = '#5646b1'; break;
                           case (r2 <= 1 ): color = '#0000ff'; break;
                       }

                      

                var populationOptions = {
                     strokeColor: color,
                     strokeOpacity: 0.8,
                     strokeWeight: 2,
                     fillColor: color,
                     fillOpacity: 0.35,
                     map: map,
                     center: pt,
                     radius: 600
                   };


                   
                   cityCircle = new google.maps.Circle(populationOptions);


      });

    var canombres=[];
    var avgcampos=[];
    var sdcampos=[];
    var capoz = [];
    var pozoscampos = [];


    
    $.each(data.Gencampos,function(index,value)
    {
      
      $.each(value,function(index,value)
      {

        canombres.push(value.cnombre);
        avgcampos.push(value.avg);
        sdcampos.push(value.sd);
        capoz.push(value.count);


      });
    });

    $.each(data.PozosC, function(index,value)
    {
      $.each(value,function(index,value)
      {
        pozoscampos.push(value.count);
      });
    });


    
     var j=0;
     $.each(data.Coordenadas, function(index,value)
     {
      
     
      
      var cnombre = canombres[j];
      var cavg = parseFloat(avgcampos[j]);
      var casd = parseFloat(sdcampos[j]);
      var cap = capoz[j];


      puntos.length=0;

      
      $.each(value,function(index,value)
      {

      puntos.push(new google.maps.LatLng(value.lat,value.lon));
      
      });
    

      var Poligono = new google.maps.Polygon({
      paths: puntos,
      strokeColor: '#EFFF00',
      strokeOpacity: 0.8,
      strokeWeight: 3,
      fillColor: '#EFFF00',
      fillOpacity: 0.05
     });

      Poligono.setMap(map);
      
      infoWindow = new google.maps.InfoWindow();

      google.maps.event.addListener(Poligono, 'click', function(e)
    {

                var contentString = '<b>*** Producing interval Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>Average '+sp+' value: </b>' + avg.toFixed(2)+
                                    '<br><b>Standard deviation '+sp+' value:</b> ' + sd.toFixed(2);

                infoWindow.setContent(contentString);
                infoWindow.setPosition(e.latLng);
                
                infoWindow.open(map);
   
      });

      j++;

      });

    var jc=0;
     $.each(data.Coordenadasc, function(index,value)
     {
      
     
      
      var cnombre = canombres[jc];
      var cavg = parseFloat(avgcampos[jc]);
      var casd = parseFloat(sdcampos[jc]);
      var cap = capoz[jc];
      var pozc = pozoscampos[jc];

      puntos.length=0;

      
      $.each(value,function(index,value)
      {

      puntos.push(new google.maps.LatLng(value.lat,value.lon));
      
      });
    

    var Poligono = new google.maps.Polygon({
    paths: puntos,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 3,
    fillColor: '#FF0000',
    fillOpacity: 0.1
     });

      Poligono.setMap(map);
      
      infoWindow = new google.maps.InfoWindow();

      google.maps.event.addListener(Poligono, 'click', function(e)
    {

                var contentString = '<b>*** Field Data *** </b><br>'+'<b>Field: </b>'+cnombre+'<br><b>These are data for all field\'s formations</b> '+'<br><br><b>Average '+sp+' value: </b>' + cavg.toFixed(2)+
                                    '<br><b>Standard deviation '+sp+' value:</b> ' + casd.toFixed(2)+
                                    '<br><b>Wells in field: <b> '+pozc+
                                    '<br><b>Wells in field with data: <b> '+cap;

                infoWindow.setContent(contentString);
                infoWindow.setPosition(e.latLng);
                
                infoWindow.open(map);
   
    });

    jc++;

      
     });


      $.each(data.Pozos2, function(index, value){
        
      var cs = value.Cnombre;
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(value.lat, value.lon),
        map:map,
        icon: 'http://labs.google.com/ridefinder/images/mm_20_white.png'
    });

      var infoW = new google.maps.InfoWindow({
        content: " <b>Well name: </b>"+value.nombre + "- <b>Field: </b>"+cs+" <BR> There's no measure for this well" 
      });

      marker.addListener('click', function(){

        infoW.open(map,marker);
        currentPopup=infoW;
      });
                      google.maps.event.addListener(infoW, "closeclick", function () {
                    map.panTo(center);
                    currentPopup = null;
                });

      });


    });
}

/* Descripción: esta función define si un subparámetro es una variable de daño o no. 
  Parámetros: 
    s: nombre del subparámetro. 
  Adicional: el nombre a los subparámetros se le asigna con el nombre desde la base de datos a los subparámetros y con el id a las variables de daño, así que las variables de daño tendrán un nombre "numérico" */
function isVD(s)
{
    return /[a-z]/i.test(s);
}


/* Descripción: esta función se encarga de grafica las distribuciones de frecuencia por cada petición del usuario. 
  Parámetros: 
    datos: estructura de datos con los marcadores, valores de subparámetro y nombres de pozo y campos.
    parametro: nombre del subparámetro que se consulta.
    unidad: unidades del subparámetro que se consulta. 
 */
function chart(datos,parametro, unidad)
{
      data = new google.visualization.DataTable();
      data.addColumn('number', parametro);
      var aux=[];
      var m =[];
      var i=0;
      $.each(datos, function(index,value){
        aux = [value.valorchart];
        data.addRow(aux);

      });  
      

      var options = {
            title: 'Frequency distribution',
            vAxis: {title:"Frequency"},
            hAxis: {title:parametro+" ["+unidad+"]"}, 
            chart: 
            {
                title: 'Frequency distribution...',
                subtitle: 'Subparameter: '+parametro,
            },
            legend: {position: 'left'}
        };

        var chart = new google.visualization.Histogram(document.getElementById('chart_div'));
        chart.draw(data, options);
}


</script>