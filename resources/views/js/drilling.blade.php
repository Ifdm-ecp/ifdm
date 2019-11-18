<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

//*****/////*****
$(document).ready(function(){
  input_data_profile = $("#inputdata_profile_table").val();
  if(input_data_profile === "") {
    var data_aux = [[,,,],[,,,],[,,,],[,,,],[,,,]];
    create_profile_input_data_table(data_aux);
  } else {
    create_profile_input_data_table(JSON.parse(input_data_profile));
  }
});


//Cargar valores de select en recarga de página
window.onload = function() {
  //verifica si esta habilitado cementing
  cementingAvailable();
  var formation = [{!! $scenario->pozo->formacionesxpozo->first()->formacion_id !!}];
  var interval = $("#select_interval_general_data").val();
  var input_general_data = $("#generaldata_table").val();
  var input_data_method = $("#select_input_data").val();
  var filtration_function_select_values = $("#select_filtration_function").val();

  $("#inputDataMethodSelect").val(input_data_method);
  $("#inputDataMethodSelect").selectpicker('refresh');

  if (isNaN(formation[0])) {formation = [];};

  //Select Input data method
  if (input_data_method == 1) {
    input_data_profile = $("#inputdata_profile_table").val();
    
    if (input_data_profile === "") {
      var data_aux = [[,,,],[,,,],[,,,],[,,,],[,,,]];
      create_profile_input_data_table(data_aux);
    } else {
      create_profile_input_data_table(JSON.parse(input_data_profile));
    }
  } else if (input_data_method == 2) {
    input_data_intervals = $("#inputdata_intervals_table").val();
    
    if (input_data_intervals === "") {
      var data_byIntervals = [];
      var intervals = $("#intervalSelect").val();
      $.get("{{url('intervalsInfoDrilling')}}",
        {intervals: intervals},
        function(data) {
          $.each(data, function(index, value) {
            var data_row = [value.nombre, value.porosidad, value.permeabilidad,];
            data_byIntervals.push(data_row);
          });
          create_intervals_input_data_table(data_byIntervals);
        });
    } else {
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
  if (interval.length > 0) {
    interval = JSON.parse(interval);
    if (!Array.isArray(interval)) {interval = [];};

    $('#intervalSelect').selectpicker('val', interval);
    var data_aux = [];
    var general_data_table = $("#generaldata_table").val();
    if (general_data_table === "") {
      $.get("{{url('intervalsInfoDrilling')}}",
        {intervals: interval},
        function(data) {
          $.each(data, function(index,value) {
            var data_row = [value.nombre, value.top, , value.presion_reservorio, ];
            data_aux.push(data_row);
          });
          create_interval_general_data_table(data_aux);
        });
    } else {
      data_aux = JSON.parse(general_data_table);
      create_interval_general_data_table(data_aux);
    }
  } else {
    create_interval_general_data_table([]);
  }

  //Select Filtration Function
  $.get('{{url("filtration_functions_by_formation_id")}}',
    {formation_id: formation[0]},
    function(data) {
      $("#filtration_function_select").empty();
      $("#filtration_function_select").append('<option value="disabled" disabled>Please, choose one.</option>');
      $.each(data, function(index, value) {
        $("#filtration_function_select").append('<option value="' + value.id + '">' + value.name + '</option>');
      });

      $('#filtration_function_select').selectpicker('refresh');
      if (filtration_function_select_values.length == 0) {
        $('#filtration_function_select').val('disabled');
        $('#filtration_function_select').selectpicker('deselectAll');
      } else {
        $('#filtration_function_select').selectpicker('val', filtration_function_select_values);
      }
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
      {title: general_data_table_ruleset[0].column, data: 0, readOnly: true},
      {title: general_data_table_ruleset[1].column, data: 1, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[1])); }},
      {title: general_data_table_ruleset[2].column, data: 2, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[2])); }},
      {title: general_data_table_ruleset[3].column, data: 3, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[3])); }},
      {title: general_data_table_ruleset[4].column, data: 4, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[4])); }},
      {title: general_data_table_ruleset[5].column, data: 5, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[5])); }}
    ],
    minSpareRows: 1,
    contextMenu: true
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
          {title:"Porosity [-]", data: 1, type: 'numeric', format: '0[.]00'},
          {title:"Permeability [mD]",data: 2,type: 'numeric', format: '0[.]00'},
          {title:"Fracture Intensity [#/ft]",data: 3,type: 'numeric', format: '0[.]00'},
          {title:"Irreducible Saturation [-]",data: 4,type: 'numeric', format: '0[.]00'}
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
    colWidths: [100, 100, 100, 120, 150, 165],
    columns: 
    [
      {title: profile_table_ruleset[0].column, data: 0, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[0])); }},
      {title: profile_table_ruleset[1].column,data: 1, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[1])); }},
      {title: profile_table_ruleset[2].column,data: 2, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[2])); }},
      {title: profile_table_ruleset[3].column,data: 3, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[3])); }},
      {title: profile_table_ruleset[4].column, data: 4, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[4])); }},
      {title: profile_table_ruleset[5].column, data: 5, type: 'numeric', format: '0[.]00', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[5])); }}
    ],
    minSpareRows: 1,
    contextMenu: true
  });
}

function plotProfileData()
{
  var container = $("#profileInput_t");
  var data = container.handsontable('getData');
  var data_aux = [];
  //Input Data Profile
  for (var i = 0; i < data.length; i++) 
  {
    d0 = data[i][0];
    d1 = data[i][1];
    d2 = data[i][2];
    d3 = data[i][3];
    d4 = data[i][4];
    d5 = data[i][5];

    if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null) && (d3 ==="" || d3 == null) && (d4==="" || d4 == null) && (d5==="" || d5 == null))
    {
      continue;
    }
    else
    {
      data_aux.push(data[i]);
    }
  }
  data = data_aux;

  var top = [];
  var bottom = [];
  var porosity = [];
  var permeability = [];
  var fracture_intensity = [];
  var irreducible_saturation = [];
  for (var i = 0; i < data.length; i++)
  {
    top.push(data[i][0]);
    bottom.push(data[i][1]);
    porosity.push(data[i][2]);
    permeability.push(data[i][3]);
    fracture_intensity.push(data[i][4]);
    irreducible_saturation.push(data[i][5]);
  }
  
  var xAxisCalc = [];
  $.each(data, function (rowKey, object) {
    if (!container.handsontable('isEmptyRow', rowKey) && !container.handsontable('isEmptyRow', rowKey)) {
      xAxisCalc[rowKey] = '' + ((bottom[rowKey] + top[rowKey]) / 2);
    }
  });

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
      categories: xAxisCalc
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

/* verifyDrilling
 * Validates the form entirely
 * params {action: string}
*/
function verifyDrilling(action) {
  // Boolean for empty values for the save button
  var emptyValues = false;
  // Title tab for modal errors
  var titleTab = "";
  var tabTitle = "";
  //Saving tables...
  var validationMessages = [];
  var validationFunctionResult = [];

  // Validating General Data
  tabTitle = "Tab: General Data";

  var select_interval_general_data = $("#intervalSelect").val();
  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_interval_general_data, general_data_select_ruleset[0]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && (select_interval_general_data === null || select_interval_general_data === "")) ? true: emptyValues;

  var generaldata_table = clean_table_data("intervalsGeneral_t");
  var generalValidator = validateTable("General Data", generaldata_table, general_data_table_ruleset);
  if (generalValidator.length > 0) {
    if (titleTab == "") {
      titleTab = "Tab: General Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator);
  }

  var select_input_data = $("#inputDataMethodSelect").val();
  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_input_data, profile_select_ruleset[0]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && (select_input_data === null || select_input_data === "")) ? true: emptyValues;

  if (select_input_data == "1") {
    //Limpiando datos de tablas
    var inputdata_profile_table = clean_table_data("profileInput_t");
    var generalValidator = validateTable("Input Data", inputdata_profile_table, profile_table_ruleset);
    if (generalValidator.length > 0) {
      if (titleTab == "") {
        titleTab = "Tab: General Data";
        validationMessages = validationMessages.concat(titleTab);
      }
      validationMessages = validationMessages.concat(generalValidator);
    }
  } else if (select_input_data == "2") {
    // This condition is never met, pending future developments
    // Limpiando datos de tablas
    var inputdata_intervals_table = clean_table_data("byIntervalsInput_t");
  }

  // Validating Filtration Function data
  titleTab = "";
  tabTitle = "Tab: Filtration Functions";

  // Guardando los valores de los selectores
  var select_filtration_function = $("#filtration_function_select").val();
  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, select_filtration_function, filtration_function_tab_ruleset[0]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && (select_filtration_function === null || select_filtration_function === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#a_factor_t").val(), filtration_function_tab_ruleset[1]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#a_factor_t").val() === null || $("#a_factor_t").val() === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#b_factor_t").val(), filtration_function_tab_ruleset[2]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#b_factor_t").val() === null || $("#b_factor_t").val() === "")) ? true: emptyValues;

  // Validating Drilling Data
  titleTab = "";
  tabTitle = "Tab: Drilling Data";

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_total_exposure_time_t").val(), drilling_data_tab_ruleset[0]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#d_total_exposure_time_t").val() === null || $("#d_total_exposure_time_t").val() === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_pump_rate_t").val(), drilling_data_tab_ruleset[1]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#d_pump_rate_t").val() === null || $("#d_pump_rate_t").val() === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_mud_density_t").val(), drilling_data_tab_ruleset[2]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#d_mud_density_t").val() === null || $("#d_mud_density_t").val() === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_plastic_viscosity_t").val(), drilling_data_tab_ruleset[3]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#d_plastic_viscosity_t").val() === null || $("#d_plastic_viscosity_t").val() === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_yield_point_t").val(), drilling_data_tab_ruleset[4]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#d_yield_point_t").val() === null || $("#d_yield_point_t").val() === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_rop_t").val(), drilling_data_tab_ruleset[5]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#d_rop_t").val() === null || $("#d_rop_t").val() === "")) ? true: emptyValues;

  validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#d_equivalent_circulating_density_t").val(), drilling_data_tab_ruleset[6]);
  titleTab = validationFunctionResult[0];
  validationMessages = validationFunctionResult[1];
  emptyValues = (emptyValues === false && ($("#d_equivalent_circulating_density_t").val() === null || $("#d_equivalent_circulating_density_t").val() === "")) ? true: emptyValues;

  // Validating Completion Data
  if ($("#check_available").prop("checked")) {
    titleTab = "";
    tabTitle = "Tab: Completion Data";

    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_total_exposure_time_t").val(), completion_data_tab_ruleset[0]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && ($("#c_total_exposure_time_t").val() === null || $("#c_total_exposure_time_t").val() === "")) ? true: emptyValues;

    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_pump_rate_t").val(), completion_data_tab_ruleset[1]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && ($("#c_pump_rate_t").val() === null || $("#c_pump_rate_t").val() === "")) ? true: emptyValues;

    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_cement_slurry_density_t").val(), completion_data_tab_ruleset[2]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && ($("#c_cement_slurry_density_t").val() === null || $("#c_cement_slurry_density_t").val() === "")) ? true: emptyValues;

    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_plastic_viscosity_t").val(), completion_data_tab_ruleset[3]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && ($("#c_plastic_viscosity_t").val() === null || $("#c_plastic_viscosity_t").val() === "")) ? true: emptyValues;

    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_yield_point_t").val(), completion_data_tab_ruleset[4]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && ($("#c_yield_point_t").val() === null || $("#c_yield_point_t").val() === "")) ? true: emptyValues;

    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, $("#c_equivalent_circulating_density_t").val(), completion_data_tab_ruleset[5]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && ($("#c_equivalent_circulating_density_t").val() === null || $("#c_equivalent_circulating_density_t").val() === "")) ? true: emptyValues;
  }

  if (validationMessages.length < 1) {
    // Guardando los datos de tablas validadas y limpiadas en formulario
    $("#generaldata_table").val(JSON.stringify(generaldata_table));
    $("#inputdata_intervals_table").val(JSON.stringify(inputdata_intervals_table));
    $("#inputdata_profile_table").val(JSON.stringify(inputdata_profile_table));
    $("#select_interval_general_data").val(JSON.stringify(remove_nulls(select_interval_general_data)));
    $("#select_input_data").val(select_input_data);
    $("#select_filtration_function").val($("#filtration_function_select").val());

    if (emptyValues) {
      validationMessages.push(true);
      showFrontendErrors(validationMessages);
    } else {
      $("#only_s").val("run");
      $("#drillingForm").submit();
    }
  } else {
    showFrontendErrors(validationMessages);
  }
}

/* saveForm
 * Submits the form when the confirmation button from the modal is clicked
*/
function saveForm() {
  $("#only_s").val("save");
  $("#drillingForm").submit();
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
  for (var i = 0; i < array.length; i++) {
    if(array[i] == null || array[i] == '') {
      continue;
    } else {
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

/* calculate_ecd
 * Makes the calculations to get either the Drilling Data Cementing Data ECD depending on the
 * param value
 * params {option: integer}
*/
function calculate_ecd(option) {
  if ($("#intervalSelect").val() == null) {
    showFrontendErrorsBasic("To calculate the ECD you need the following data: <li>Hole Diameter (from General Data Table)</li><li>Drill Pipe Diameter (from General Data Table)</li><li>Mud Density</li><li>Pump Rate</li>");
  } else {
    var mud_density = 0;
    var pump_rate = 0;
    var result_div_id = "";
    var general_data_table = clean_table_data("intervalsGeneral_t");
    var filtration_function_id = $("#filtration_function_select").val();
    var yield_point = null;
    var plastic_viscosity = null;
    var ecd = 0;

    if (option == 0) {
      mud_density = parseFloat($("#d_mud_density_t").val());
      pump_rate = parseFloat($("#d_pump_rate_t").val());
      plastic_viscosity = parseFloat($("#d_plastic_viscosity_t").val());
      yield_point = parseFloat($("#d_yield_point_t").val());
      result_div_id = "d_equivalent_circulating_density_t";
    } else if (option == 1) {
      mud_density = parseFloat($("#c_cement_slurry_density_t").val());
      pump_rate = parseFloat($("#c_pump_rate_t").val());
      plastic_viscosity = parseFloat($("#c_plastic_viscosity_t").val());
      yield_point = parseFloat($("#c_yield_point_t").val());
      result_div_id = "c_equivalent_circulating_density_t";
    }

    var hole_diameter_sum = 0;
    var drill_pipe_diameter_sum = 0;
    var general_data_table_length = general_data_table.length;

    // In the backburner until multiple data is supported
    // for (var i = 0; i < general_data_table_length; i++) {
    //   hole_diameter_sum += parseFloat(general_data_table[i][4]);  
    //   drill_pipe_diameter_sum += parseFloat(general_data_table[i][5]);  
    // }

    // var hole_diameter = hole_diameter_sum / general_data_table_length;
    // var drill_pipe_diameter = drill_pipe_diameter_sum / general_data_table_length;
    
    var hole_diameter = parseFloat(general_data_table[0][4]);
    var drill_pipe_diameter = parseFloat(general_data_table[0][5]);

    var annular_velocity = 24.5 * (pump_rate / (Math.pow(hole_diameter, 2) - Math.pow(drill_pipe_diameter, 2)));

    if(mud_density < 13) {
      ecd = mud_density + ((0.1 * yield_point) / (hole_diameter - drill_pipe_diameter));
    } else {
      ecd = mud_density + ((0.1 / (hole_diameter - drill_pipe_diameter)) * (yield_point + ((plastic_viscosity * annular_velocity) / (300 * (hole_diameter - drill_pipe_diameter)))));
    }

    if (isNaN(ecd)) {
      showFrontendErrorsBasic("To calculate the ECD you need the following data: <li>Hole Diameter (from General Data Table)</li><li>Drill Pipe Diameter (from General Data Table)</li><li>Mud Density</li><li>Pump Rate</li>");
    } else {
      $("#" + result_div_id).val(ecd.toFixed(2));
    }
  }
}

/* tabStep
 * After validating the current tab, it is changed to the next or previous tab depending on the
 * entry value
 * params {direction: string}
*/
function tabStep(direction) {
  var tabToValidate = $(".nav.nav-tabs li.active a").attr("id");

  if (direction == "prev") {
    $(".nav.nav-tabs li.active").prev().children().click();
  } else {
    $(".nav.nav-tabs li.active").next().children().click();
  }

  $("#next_button").toggle($(".nav.nav-tabs li.active").next().is("li"));
  $("#prev_button").toggle($(".nav.nav-tabs li.active").prev().is("li"));
  $("#run_calc").toggle(!$(".nav.nav-tabs li.active").next().is("li"));
}

/* validateTab
 * Forces a validation on the current tab before performing the bootstrap tab default event
 * entry value
 * params {direction: string}
*/
// function validateTab() {
//   var event = window.event || arguments.callee.caller.arguments[0];
//   var tabToValidate = $(".nav.nav-tabs li.active a").attr("id");
//   var tabActiveElement = $(".nav.nav-tabs li.active");
//   var nextPrevElement = $("#" + event.explicitOriginalTarget.id).parent();

//   if (nextPrevElement.prevAll().filter(tabActiveElement).length !== 0) {
//     if (!verifyDrilling(tabToValidate, true)) {
//       event.stopImmediatePropagation();
//       event.stopPropagation();
//       event.preventDefault();
//     } else {
//       $("#next_button").toggle(nextPrevElement.next().is("li"));
//       $("#prev_button").toggle(nextPrevElement.prev().is("li"));
//     }
//   } else {
//     $("#next_button").toggle(nextPrevElement.next().is("li"));
//     $("#prev_button").toggle(nextPrevElement.prev().is("li"));
//   }
// }

/* switchTab
 * Captures the tab clicking event to determine if a previous or next button has to be shown
 * and also the run button
*/
function switchTab() {
  var event = window.event || arguments.callee.caller.arguments[0];
  var tabActiveElement = $(".nav.nav-tabs li.active");
  var nextPrevElement = $("#" + event.explicitOriginalTarget.id).parent();

  $("#next_button").toggle(nextPrevElement.next().is("li"));
  $("#prev_button").toggle(nextPrevElement.prev().is("li"));
  $("#run_calc").toggle(!nextPrevElement.next().is("li"));
}

$(function () 
{
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});

// Dynamic content
// ROP calculation (This needs further development, since it is calculating based on just one top and bottom when
// Handsontable can have more than one interval)
$("#d_total_exposure_time_t").change(function(e) {
  var general_data_table = $("#intervalsGeneral_t").handsontable("getData");
  // In the backburner until multiple cases are supported
  // var tops = [];
  // var bottoms = [];
  var texp = parseFloat($("#d_total_exposure_time_t").val());
  // for (var i = 0; i < general_data_table.length; i++) {
  //   if(general_data_table[i][0] != null && general_data_table[i][0] !== "") {
  //     tops.push(parseFloat(general_data_table[i][0]));
  //   }
  //   if(general_data_table[i][1] != null && input_table_data[i][1] !== "") {
  //     bottoms.push(parseFloat(general_data_table[i][1]));
  //   }
  // }
  // var MDbottom = 0;
  // var MDtop = 0;
  // for (var i = 0; i < bottoms.length; i++) {
  //   MDbottom += bottoms[i];
  //   MDtop += tops[i];
  // }
  // $("#MDbottom").val(MDbottom / bottoms.length);
  // $("#MDtop").val(MDtop / tops.length);
  var MDbottom = general_data_table[0][2];
  var MDtop = general_data_table[0][1];
  var rop = (MDbottom - MDtop) / (texp * 24);
  // var rop = (MDbottom / bottoms.length - MDtop / tops.length) / (texp * 24);
  $("#d_rop_t").val(rop.toFixed(2));
});

$("#d_rop_t").change(function(e) {
  if ($("#d_total_exposure_time_t").val() == '') {
    var generaldata_table_data = $("#intervalsGeneral_t").handsontable('getData');
    var tops = [];
    var bottoms = [];
    var d_rop_t = $("#d_rop_t").val();
    for (var i = 0; i < generaldata_table_data.length; i++) {
      if (generaldata_table_data[i][1] == null || generaldata_table_data[i][1] === '') {
        continue;
      } else {
        tops.push(parseFloat(generaldata_table_data[i][1]));
      } if(generaldata_table_data[i][2] == null || generaldata_table_data[i][2] === '') {
        continue;
      } else {
        bottoms.push(parseFloat(generaldata_table_data[i][2]));
      }
    }
    var MDbottom = Math.max.apply(Math, bottoms); 
    var MDtop = Math.min.apply(Math, tops); 
    $("#MDbottom").val(MDbottom);
    $("#MDtop").val(MDtop);
    var texp = (MDbottom - MDtop) / (d_rop_t * 24);
    $("#d_total_exposure_time_t").val(texp);
  }
});


//Selects  **/**
//General Data
$("#filtration_function_select").change(function(e) {
  $.get("{{url('filtration_function_data')}}",
    {ff_id: $(this).val()},
    function(data) {
      $.each(data, function(index,value) {
        a_factor = value.a_factor;
        b_factor = value.b_factor;
        mud_density = value.mud_density;
        plastic_viscosity = value.plastic_viscosity;
        yield_point = value.yield_point;
        cement_slurry_density = value.cement_slurry_density;
        cement_plastic_viscosity = value.cement_plastic_viscosity;
        cement_yield_point = value.cement_yield_point;
      });

      $("#a_factor_t").val(a_factor);
      $("#b_factor_t").val(b_factor);
      $("#d_mud_density_t").val(mud_density);
      $("#d_plastic_viscosity_t").val(plastic_viscosity);
      $("#d_yield_point_t").val(yield_point);

      if (cement_slurry_density != null) {
        $("#c_cement_slurry_density_t").val(cement_slurry_density);
        $("#c_plastic_viscosity_t").val(cement_plastic_viscosity != null ? cement_plastic_viscosity : plastic_viscosity);
        $("#c_yield_point_t").val(cement_yield_point != null ? cement_yield_point : yield_point);
        $("#check_available").prop("checked", true);
        cementingAvailable();
      } else {
        $("#check_available").prop("checked", false);
        cementingAvailable();
      }
    });
});

//IntervalSelect: crea la tabla de general data para diligenciar la información de los intervalos a analizar.
$("#intervalSelect").change(function(e) {
  var intervals = $("#intervalSelect").val();
  var generaldata_table = clean_table_data("intervalsGeneral_t");
  var data_aux = [];
  $.get("{{url('intervalsInfoDrilling')}}",
    { intervals: intervals },
    function(data) {
      $.each(data, function(index, value) {
        var data_row = [];

        for (var i = 0; i < generaldata_table.length; i++) {
          if (generaldata_table[i][0] === value.nombre) {
            data_row = generaldata_table[i];
            generaldata_table.splice(i, 1);
            break;
          }
        }

        if (data_row.length === 0) {
          data_row = [value.nombre, value.top, , value.presion_reservorio, ];
        }

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
