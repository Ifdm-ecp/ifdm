<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">

/* Drilling rulesets
 * This is a set of rules for each table and/or section of the form
 * Each element in the array corresponds to a rule assigned to the column
 * So element 0 has the rules for the column 0 of the table
*/
general_data_select_ruleset = [
  {
    column: "Producing Interval",
    rules: [
      {rule: "requiredselect"}
    ]
  },
];

general_data_table_ruleset = [
  {
    column: "Interval",
    rules: [
      {rule: "required"},
      {rule: "any"}
    ]
  },
  {
    column: "Top [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Bottom [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Reservoir Pressure [psi]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000},
    ]
  },
  {
    column: "Hole Diameter [in]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10},
    ]
  },
  {
    column: "Drill Pipe Diameter [in]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10},
    ]
  }
];

profile_select_ruleset = [
  {
    column: "Input Data Method",
    rules: [
      {rule: "requiredselect"}
    ]
  },
];

profile_table_ruleset = [
  {
    column: "Top [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Bottom [ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50000},
    ]
  },
  {
    column: "Porosity [-]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1},
    ]
  },
  {
    column: "Permeability [mD]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 10000},
    ]
  },
  {
    column: "Fracture Intensity [#/ft]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 100},
    ]
  },
  {
    column: "Irreducible Saturation [-]",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 1},
    ]
  }
];

filtration_function_tab_ruleset = [
  {
    column: "Filtration Function",
    rules: [
      {rule: "requiredselect"}
    ]
  },
  {
    column: "a",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "b",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  }
];

drilling_data_tab_ruleset = [
  {
    column: "Total Exposure Time",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "Pump Rate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 500},
    ]
  },
  {
    column: "Mud Density",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 20},
    ]
  },
  {
    column: "ROP",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 500},
    ]
  },
  {
    column: "ECD (Equivalent Circulating Density)",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 30},
    ]
  }
];

completion_data_tab_ruleset = [
  {
    column: "Total Exposure Time",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "Pump Rate",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 500},
    ]
  },
  {
    column: "Cement Slurry Density",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 50},
    ]
  },
  {
    column: "ECD (Equivalent Circulating Density)",
    rules: [
      {rule: "required"},
      {rule: "numeric"},
      {rule: "range", min: 0, max: 70},
    ]
  }
];

/* multiValidatorHandsonTable
 * returns a boolean with a check for specific rule
 * params {value: mixed, ruleset: object}
*/
function multiValidatorHandsonTable(value, ruleset)
{
  var isValid = false;

  $.each(ruleset.rules, function (key, set) {
    switch (set.rule) {
      case "any":
        isValid = true;
        return false;
        break;
      case "numeric":
        isValid = $.isNumeric(value);
        return isValid;
        break;
      case "range":
        isValid = (value >= set.min && value <= set.max);
        return isValid;
        break;
    }
  });

  return isValid;
};

/* multiValidatorTable
* returns an array with a boolean with a validation result and a message in case the validation fails
* params {value: mixed, tableName: string, tableRow: int, ruleset: object}
*/
function multiValidatorTable(value, tableName, tableRow, ruleset)
{
  var isValid = [];

  $.each(ruleset.rules, function (key, set) {
    switch (set.rule) {
      case "any":
        return false;
        break;
      case "required":
        if (value == null || value == "") {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " has an empty value"];
          return false;
        }
        break;
      case "numeric":
        if (!$.isNumeric(value)) {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " has a non numeric value"];
          return false
        }
        break;
      case "range":
        if (value < set.min || value > set.max) {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " has a value that is out of the numeric range [" + set.min + "," + set.max + "]"];
          return false;
        }
        break;
    }
  });

  return (isValid.length > 0 ? isValid : [true, ""]);
};

/* multiValidator
* returns an array with a boolean with a validation result and a message in case the validation fails
* params {value: mixed, tableName: string, tableRow: int, ruleset: object}
*/
function multiValidatorGeneral(value, ruleset)
{
  var isValid = [];

  $.each(ruleset.rules, function (key, set) {
    switch (set.rule) {
      case "any":
        return false;
        break;
      case "required":
        if (value == null || value == "") {
          isValid = [false, "The field " + ruleset.column + " has an empty value"];
          return false;
        }
        break;
      case "requiredselect":
        if (value == null || value == "") {
          isValid = [false, "There is no " + ruleset.column + " selected"];
          return false;
        }
        break;
      case "numeric":
        if (!$.isNumeric(value)) {
          isValid = [false, "The field " + ruleset.column + " has a non numeric value"];
          return false
        }
        break;
      case "range":
        if (value < set.min || value > set.max) {
          isValid = [false, "The field " + ruleset.column + " has a value that is out of the numeric range [" + set.min + "," + set.max + "]"];
          return false;
        }
        break;
    }
  });

  return (isValid.length > 0 ? isValid : [true, ""]);
};

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
    var formation = [{!! $scenario->pozo->formacionesxpozo->first()->formacion_id !!}];
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
      {title: general_data_table_ruleset[0].column, data: 0, readOnly: true},
      {title: general_data_table_ruleset[1].column, data: 1, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[1])); }},
      {title: general_data_table_ruleset[2].column, data: 2, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[2])); }},
      {title: general_data_table_ruleset[3].column, data: 3, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[3])); }},
      {title: general_data_table_ruleset[4].column, data: 4, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[4])); }},
      {title: general_data_table_ruleset[5].column, data: 5, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, general_data_table_ruleset[5])); }}
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
          {title:"Porosity [-]", data: 1, type: 'numeric', format: '0[.]0000000'},
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
        colWidths: [100, 100, 100, 120, 150, 165],
        columns: 
        [
          {title: profile_table_ruleset[0].column, data: 0, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[0])); }},
          {title: profile_table_ruleset[1].column,data: 1, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[1])); }},
          {title: profile_table_ruleset[2].column,data: 2, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[2])); }},
          {title: profile_table_ruleset[3].column,data: 3, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[3])); }},
          {title: profile_table_ruleset[4].column, data: 4, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[4])); }},
          {title: profile_table_ruleset[5].column, data: 5, type: 'numeric', format: '0[.]0000000', validator: function(value, callback) { callback(multiValidatorHandsonTable(value, profile_table_ruleset[5])); }}
        ],
        minSpareRows: 1,
        contextMenu: true,
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
  console.log(xAxisCalc);

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

function validateTable(tableName, tableData, tableRuleset) {
  var message = "";
  var tableLength = tableData.length;
  var rowValidation = [];
  var errorMessages = [];

  if (tableLength < 1) {
    message = "The table " + tableName + " is empty. Please check your data";
    return [message];
  } else {
    var tableColumnLength = tableData[0].length;

    for (var i = 0; i < tableLength; i++) {
      for (var j = 0; j < tableColumnLength; j++) {
        var rowValidation = multiValidatorTable(tableData[i][j], tableName, i, tableRuleset[j]);
        if (!rowValidation[0]) {
          errorMessages.push(rowValidation[1]);
        }
      }
    }
  }

  if (errorMessages.length > 1) {
    return [{message: "The table " + tableName + " has validation errors (click to expand)", errors: errorMessages}];
  } else {
    return [];
  }
}

function verifyDrilling(w_verificate_data)
{
  //var evt = window.event || arguments.callee.caller.arguments[0];
  //evt.preventDefault();
  // Title tab for modal errors
  var titleTab = "";
  //Saving tables...
  var validationMessages = [];

  // Validating General Data
  var select_interval_general_data = $("#intervalSelect").val();
  var generalValidator = multiValidatorGeneral(select_interval_general_data, general_data_select_ruleset[0]);
  if (generalValidator[0]) {
    $("#select_interval_general_data").val(JSON.stringify(remove_nulls(select_interval_general_data)));
  } else {
    titleTab = "Tab: General Data";
    validationMessages = validationMessages.concat(titleTab);
    validationMessages = validationMessages.concat(generalValidator[1]);
  }
  var generaldata_table = clean_table_data("intervalsGeneral_t");
  // validate_return = validate_table("General Data", generaldata_table, general_data_table_ruleset);
  var generalValidator = validateTable("General Data", generaldata_table, general_data_table_ruleset);
  if (generalValidator.length > 0) {
    if (titleTab == "") {
      titleTab = "Tab: General Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator);
  }
  
  // flag_general_data = validate_return[0]; 

  //Normalización de valores de tablas
  // var generaldata_table_aux = [];
  // var inputdata_intervals_table_aux = [];
  // var inputdata_profile_table_aux = [];

  // Flags para tablas
  // var flag_input_method_1 = true;
  // var flag_input_method_2 = true;
  // var flag_general_data = true;
  // var flag_filtration_function = true;
  // var flag_message = "";
  // var validate_return = [];

  var select_input_data = $("#inputDataMethodSelect").val();
  generalValidator = multiValidatorGeneral(select_input_data, profile_select_ruleset[0]);
  if (generalValidator[0]) {
    $("#select_input_data").val(select_input_data);
  } else {
    if (titleTab == "") {
      titleTab = "Tab: General Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  if (select_input_data == "1") {
    //Limpiando datos de tablas
    var inputdata_profile_table = clean_table_data("profileInput_t");

    // validate_return = validate_table("Input Data", inputdata_profile_table, 0);
    var generalValidator = validateTable("Input Data", inputdata_profile_table, profile_table_ruleset);
    if (generalValidator.length > 0) {
      if (titleTab == "") {
        titleTab = "Tab: General Data";
        validationMessages = validationMessages.concat(titleTab);
      }
      validationMessages = validationMessages.concat(generalValidator);
    }
    // flag_input_method_1 = validate_return[0];
  } else if (select_input_data == "2") {
    // This condition is never met, pending future developments
    // Limpiando datos de tablas
    var inputdata_intervals_table = clean_table_data("byIntervalsInput_t");

    // validate_return = validate_table("Input Data", inputdata_intervals_table, 1);
    // validationMessages = validationMessages.concat(validateTable("Input Data", inputdata_intervals_table, profile_table_ruleset));
    // flag_input_method_2 = validate_return[0]; 
    // flag_message = validate_return[1];
  }

  // Validating Filtration Function data
  titleTab = "";

  // Guardando los valores de los selectores
  var select_filtration_function = $("#filtration_function_select").val();
  generalValidator = multiValidatorGeneral(select_filtration_function, filtration_function_tab_ruleset[0]);
  if (generalValidator[0]) {
    $("#select_filtration_function").val(select_filtration_function);
  } else {
    titleTab = "Tab: Filtration Functions";
    validationMessages = validationMessages.concat(titleTab);
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#a_factor_t").val(), filtration_function_tab_ruleset[1]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Filtration Functions";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#b_factor_t").val(), filtration_function_tab_ruleset[2]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Filtration Functions";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  // Validating Drilling Data
  titleTab = "";

  generalValidator = multiValidatorGeneral($("#d_total_exposure_time_t").val(), drilling_data_tab_ruleset[0]);
  if (!generalValidator[0]) {
    titleTab = "Tab: Drilling Data";
    validationMessages = validationMessages.concat(titleTab);
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#d_pump_rate_t").val(), drilling_data_tab_ruleset[1]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Drilling Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#d_mud_density_t").val(), drilling_data_tab_ruleset[2]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Drilling Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#d_rop_t").val(), drilling_data_tab_ruleset[3]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Drilling Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#d_equivalent_circulating_density_t").val(), drilling_data_tab_ruleset[4]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Drilling Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  // Validating Completion Data
  titleTab = "";

  generalValidator = multiValidatorGeneral($("#c_total_exposure_time_t").val(), completion_data_tab_ruleset[0]);
  if (!generalValidator[0]) {
    titleTab = "Tab: Completion Data";
    validationMessages = validationMessages.concat(titleTab);
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#c_pump_rate_t").val(), completion_data_tab_ruleset[1]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Completion Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#c_cement_slurry_density_t").val(), completion_data_tab_ruleset[2]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Completion Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  generalValidator = multiValidatorGeneral($("#c_equivalent_circulating_density_t").val(), completion_data_tab_ruleset[3]);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = "Tab: Completion Data";
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  if (validationMessages.length < 1) {
    // var evt = window.event || arguments.callee.caller.arguments[0];
    // evt.preventDefault();

    // Guardando los datos de tablas validadas y limpiadas en formulario
    $("#generaldata_table").val(JSON.stringify(generaldata_table));
    $("#inputdata_intervals_table").val(JSON.stringify(inputdata_intervals_table));
    $("#inputdata_profile_table").val(JSON.stringify(inputdata_profile_table));

    $("#drillingForm").submit();
  } else {
    showFrontendErrors(validationMessages);
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

function tabStep(direction) {
  if (direction == "prev") {
    $(".nav.nav-tabs li.active").prev().children().click();
  } else {
    $(".nav.nav-tabs li.active").next().children().click();
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

