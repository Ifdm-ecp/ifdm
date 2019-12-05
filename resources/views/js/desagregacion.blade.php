<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">

<script type="text/javascript">

  $(document).ready(function() {
    // Muestra los campos correspondientes a los de la lista desplegable seleccionada
    if ($('#well_completitions').val() == 3) { $("#hidden_div_perforated_liner").show(); }
    if ($('#fluid_of_interest').val() == 1) { $("#hidden_oil").show(); }
    if ($('#fluid_of_interest').val() == 2) { $("#hidden_gas").show(); }
    if ($('#fluid_of_interest').val() == 3) { $("#hidden_water").show(); }

    /** Modales de error formulario */
    $("#myModal").modal('show');
    $("#myModal_val").modal('show');

    /** Crear la tabla de unidades hidráulicas*/
    var hidraulic_units_data_table = $("#hidraulic_units_data_table").val();
    if (hidraulic_units_data_table === "") {
      var data_aux = [];
      create_hydraulic_units_data_table(data_aux);
    } else {
      create_hydraulic_units_data_table(JSON.parse(hidraulic_units_data_table));
    }
  });

  /* verifyDisaggregation
  * Validates the form entirely
  * params {action: string}
  */
  function verifyDisaggregation(action) {
    // Boolean for empty values for the save button
    var emptyValues = false;
    // Title tab for modal errors
    var titleTab = "";
    var tabTitle = "";
    //Saving tables...
    var validationMessages = [];
    var validationFunctionResult = [];

    // Validating Well Data
    tabTitle = "Tab: Well Data";

    var well_radius = $("#well_radius").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, well_radius, well_data_ruleset[0]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (well_radius === null || well_radius === "")) ? true: emptyValues;

    var reservoir_pressure = $("#reservoir_pressure").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, reservoir_pressure, well_data_ruleset[1]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (reservoir_pressure === null || reservoir_pressure === "")) ? true: emptyValues;

    var measured_well_depth = $("#measured_well_depth").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, measured_well_depth, well_data_ruleset[2]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (measured_well_depth === null || measured_well_depth === "")) ? true: emptyValues;

    var true_vertical_depth = $("#true_vertical_depth").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, true_vertical_depth, well_data_ruleset[3]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (true_vertical_depth === null || true_vertical_depth === "")) ? true: emptyValues;

    var formation_thickness = $("#formation_thickness").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, formation_thickness, well_data_ruleset[4]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (formation_thickness === null || formation_thickness === "")) ? true: emptyValues;

    var perforated_thickness = $("#perforated_thickness").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, perforated_thickness, well_data_ruleset[5]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (perforated_thickness === null || perforated_thickness === "")) ? true: emptyValues;

    var well_completitions = $("#well_completitions").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, well_completitions, well_data_ruleset[6]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (well_completitions === null || well_completitions === "")) ? true: emptyValues;

    if (well_completitions == "3") {
      var perforation_penetration_depth = $("#perforation_penetration_depth").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, perforation_penetration_depth, well_data_ruleset[7]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (perforation_penetration_depth === null || perforation_penetration_depth === "")) ? true: emptyValues;

      var perforating_phase_angle = $("#perforating_phase_angle").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, perforating_phase_angle, well_data_ruleset[8]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (perforating_phase_angle === null || perforating_phase_angle === "")) ? true: emptyValues;

      var perforating_radius = $("#perforating_radius").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, perforating_radius, well_data_ruleset[9]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (perforating_radius === null || perforating_radius === "")) ? true: emptyValues;

      var production_formation_thickness = $("#production_formation_thickness").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, production_formation_thickness, well_data_ruleset[10]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (production_formation_thickness === null || production_formation_thickness === "")) ? true: emptyValues;

      var horizontal_vertical_permeability_ratio = $("#horizontal_vertical_permeability_ratio").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, horizontal_vertical_permeability_ratio, well_data_ruleset[11]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (horizontal_vertical_permeability_ratio === null || horizontal_vertical_permeability_ratio === "")) ? true: emptyValues;

      var drainage_area_shape = $("#drainage_area_shape").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, drainage_area_shape, well_data_ruleset[12]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (drainage_area_shape === null || drainage_area_shape === "")) ? true: emptyValues;
    }
  
    // Production data section
    var fluid_of_interest = $("#fluid_of_interest").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, fluid_of_interest, production_data_ruleset[0]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (fluid_of_interest === null || fluid_of_interest === "")) ? true: emptyValues;

    if (fluid_of_interest == "1") {
      var oil_rate = $("#oil_rate").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, oil_rate, production_data_ruleset[1]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (oil_rate === null || oil_rate === "")) ? true: emptyValues;

      var oil_bottomhole_flowing_pressure = $("#oil_bottomhole_flowing_pressure").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, oil_bottomhole_flowing_pressure, production_data_ruleset[2]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (oil_bottomhole_flowing_pressure === null || oil_bottomhole_flowing_pressure === "")) ? true: emptyValues;

      var oil_viscosity = $("#oil_viscosity").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, oil_viscosity, production_data_ruleset[3]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (oil_viscosity === null || oil_viscosity === "")) ? true: emptyValues;

      var oil_volumetric_factor = $("#oil_volumetric_factor").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, oil_volumetric_factor, production_data_ruleset[4]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (oil_volumetric_factor === null || oil_volumetric_factor === "")) ? true: emptyValues;
    }

    if (fluid_of_interest == "2") {
      var gas_rate = $("#gas_rate").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, gas_rate, production_data_ruleset[5]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (gas_rate === null || gas_rate === "")) ? true: emptyValues;

      var gas_bottomhole_flowing_pressure = $("#gas_bottomhole_flowing_pressure").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, gas_bottomhole_flowing_pressure, production_data_ruleset[6]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (gas_bottomhole_flowing_pressure === null || gas_bottomhole_flowing_pressure === "")) ? true: emptyValues;

      var gas_viscosity = $("#gas_viscosity").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, gas_viscosity, production_data_ruleset[7]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (gas_viscosity === null || gas_viscosity === "")) ? true: emptyValues;

      var gas_volumetric_factor = $("#gas_volumetric_factor").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, gas_volumetric_factor, production_data_ruleset[8]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (gas_volumetric_factor === null || gas_volumetric_factor === "")) ? true: emptyValues;
    }

    if (fluid_of_interest == "3") {
      var water_rate = $("#water_rate").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, water_rate, production_data_ruleset[9]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (water_rate === null || water_rate === "")) ? true: emptyValues;

      var water_bottomhole_flowing_pressure = $("#water_bottomhole_flowing_pressure").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, water_bottomhole_flowing_pressure, production_data_ruleset[10]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (water_bottomhole_flowing_pressure === null || water_bottomhole_flowing_pressure === "")) ? true: emptyValues;

      var water_viscosity = $("#water_viscosity").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, water_viscosity, production_data_ruleset[11]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (water_viscosity === null || water_viscosity === "")) ? true: emptyValues;

      var water_volumetric_factor = $("#water_volumetric_factor").val();
      validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, water_volumetric_factor, production_data_ruleset[12]);
      titleTab = validationFunctionResult[0];
      validationMessages = validationFunctionResult[1];
      emptyValues = (emptyValues === false && (water_volumetric_factor === null || water_volumetric_factor === "")) ? true: emptyValues;
    }

    // Damage data section
    var skin = $("#skin").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, skin, damage_ruleset[0]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (skin === null || skin === "")) ? true: emptyValues;
  
    // Validating Reservoir data
    titleTab = "";
    tabTitle = "Tab: Reservoir data";

    var permeability = $("#permeability").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, permeability, basic_petrophysics_ruleset[0]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (permeability === null || permeability === "")) ? true: emptyValues;

    var rock_type = $("#rock_type").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, rock_type, basic_petrophysics_ruleset[1]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (rock_type === null || rock_type === "")) ? true: emptyValues;

    var porosity = $("#porosity").val();
    validationFunctionResult = validateField(action, titleTab, tabTitle, validationMessages, porosity, basic_petrophysics_ruleset[2]);
    titleTab = validationFunctionResult[0];
    validationMessages = validationFunctionResult[1];
    emptyValues = (emptyValues === false && (porosity === null || porosity === "")) ? true: emptyValues;
    
    // Validating Hydraulic Units data
    titleTab = "";
    tabTitle = "Tab: Hydraulic Units data";

    var hidraulic_units_data_table = clean_table_data("hidraulic_units_data");
    var generalValidator = validateTable("Hydraulic Units Data", hidraulic_units_data_table, hydraulic_units_data_table_ruleset, action);
    if (generalValidator.length > 0) {
      if (titleTab == "") {
        titleTab = "Tab: Hydraulic Units data";
        validationMessages = validationMessages.concat(titleTab);
      }
      validationMessages = validationMessages.concat(generalValidator);
    }

    if (validationMessages.length < 1) {
      $("#hidraulic_units_data_table").val(JSON.stringify(hidraulic_units_data_table));

      if (emptyValues) {
        validationMessages.push(true);
        showFrontendErrors(validationMessages);
      } else {
        enviar();
        $("#only_s").val("run");
        $("#disaggregationForm").submit();
      }
    } else {
      showFrontendErrors(validationMessages);
    }
  }

  /* saveForm
   * Submits the form when the confirmation button from the modal is clicked
  */
  function saveForm() {
    guardar();
    $("#only_s").val("save");
    $("#disaggregationForm").submit();
  }

  function guardar() {
    /* Loading */
    $("#loading_icon").show();
    hidraulic_units_data = clean_table_data("hidraulic_units_data");
    hidraulic_units_data_hidden = clean_table_data("hidraulic_units_data_hidden");

    // Si tabla sin el FZI está llena
    if (hidraulic_units_data.length != 0) {
      // Para calcular los valores de la tabla de unidades hidráulicas con el FZI
      var hidraulic_units_data_hidden = $('#hidraulic_units_data').handsontable('getData');
      for (var i = hidraulic_units_data_hidden.length - 1; i >= 0; i--) {
        if (hidraulic_units_data_hidden[i][0] != null && hidraulic_units_data_hidden[i][1] != null && hidraulic_units_data_hidden[i][2] != null) {
          var value = 0.0314 * Math.sqrt(hidraulic_units_data_hidden[i][2] / hidraulic_units_data_hidden[i][1]) / (hidraulic_units_data_hidden[i][1]/(1-hidraulic_units_data_hidden[i][1]));
          hidraulic_units_data_hidden[i].splice(1,0,value);
        } else {
          hidraulic_units_data_hidden[i].splice(1,0,null);
        }
      }

      // Actualizar los valores de la tabla de unidades hidráulicas sin el FZI
      var hidraulic_units_data_table_hidden = $("#hidraulic_units_data_hidden").handsontable('getInstance');
      hidraulic_units_data_table_hidden.updateSettings({
        data: hidraulic_units_data_hidden,
        stretchH: 'all'
      });
      hidraulic_units_data_table_hidden.render();

      hidraulic_units_data = clean_table_data("hidraulic_units_data");
      hidraulic_units_data_hidden = clean_table_data("hidraulic_units_data_hidden");

      hidraulic_units_data_hidden = parseArrayToNumeric(hidraulic_units_data_hidden);

      $("#unidades_table_hidden").val(JSON.stringify(hidraulic_units_data_hidden));
    } else {
      $("#unidades_table_hidden").val("[]");
    }

    /*
    hidraulic_units_data = clean_table_data("hidraulic_units_data");
    $("#unidades_table").val(JSON.stringify(hidraulic_units_data));

    hidraulic_units_data_hidden = clean_table_data("hidraulic_units_data_hidden");
    $("#unidades_table_hidden").val(JSON.stringify(hidraulic_units_data_hidden));
    */

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
    hidraulic_units_data_hidden = clean_table_data("hidraulic_units_data_hidden");

    // si tabla vacía, entonces calcular y después calcular hidden. Por último valida
    // si tabla llena, entonces calcular hidden y después valida
    if (hidraulic_units_data.length == 0) {
      calculate_hydraulic_units_data();
      hidraulic_units_data = clean_table_data("hidraulic_units_data");
      hidraulic_units_data_hidden = clean_table_data("hidraulic_units_data_hidden");

      hidraulic_units_data_hidden = parseArrayToNumeric(hidraulic_units_data_hidden);

      $("#unidades_table_hidden").val(JSON.stringify(hidraulic_units_data_hidden));
    } else {
      // Para calcular los valores de la tabla de unidades hidráulicas con el FZI
      var hidraulic_units_data_hidden = $('#hidraulic_units_data').handsontable('getData');
      for (var i = hidraulic_units_data_hidden.length - 1; i >= 0; i--) {
        if (hidraulic_units_data_hidden[i][0] != null && hidraulic_units_data_hidden[i][1] != null && hidraulic_units_data_hidden[i][2] != null) {
          var value = 0.0314 * Math.sqrt(hidraulic_units_data_hidden[i][2] / hidraulic_units_data_hidden[i][1]) / (hidraulic_units_data_hidden[i][1]/(1-hidraulic_units_data_hidden[i][1]));
          hidraulic_units_data_hidden[i].splice(1,0,value);
        } else {
          hidraulic_units_data_hidden[i].splice(1,0,null);
        }
      }

      // Actualizar los valores de la tabla de unidades hidráulicas sin el FZI
      var hidraulic_units_data_table_hidden = $("#hidraulic_units_data_hidden").handsontable('getInstance');
      hidraulic_units_data_table_hidden.updateSettings(
      {
        data: hidraulic_units_data_hidden,
        stretchH: 'all'
      });
      hidraulic_units_data_table_hidden.render();

      hidraulic_units_data = clean_table_data("hidraulic_units_data");
      hidraulic_units_data_hidden = clean_table_data("hidraulic_units_data_hidden");

      hidraulic_units_data_hidden = parseArrayToNumeric(hidraulic_units_data_hidden);

      $("#unidades_table_hidden").val(JSON.stringify(hidraulic_units_data_hidden));
    }
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

  /* switchTab
  * Captures the tab clicking event to determine if a previous or next button has to be shown
  * and also the run button
  */
  function switchTab() {
    var event = window.event || arguments.callee.caller.arguments[0];
    var tabActiveElement = $(".nav.nav-tabs li.active");
    var nextPrevElement = $("#" + $(event.srcElement || event.originalTarget).attr('id')).parent();

    $("#next_button").toggle(nextPrevElement.next().is("li"));
    $("#prev_button").toggle(nextPrevElement.prev().is("li"));
    $("#run_calc").toggle(!nextPrevElement.next().is("li"));
  }

  //Llamarla antes de guardar todos los datos de tablas - elmina nulos
  function clean_table_data(table_div_id) {
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

  function create_hydraulic_units_data_table(data) {
    // Para mostrar valores de la tabla de unidades hidráulicas sin el FZI
    $hidraulic_units_data_table = $("#hidraulic_units_data");
    $hidraulic_units_data_table.handsontable({
      data: data,
      height: 200,
      colHeaders: true,
      minSpareRows: 4,
      viewportColumnRenderingOffset: 10,
      rowHeaders: true,
      contextMenu: true,
      stretchH: 'all',
      colWidths: [240, 240, 240],
      columns: [
        {
            title: hydraulic_units_data_table_ruleset[0].column,
            data: 0,
            type: 'numeric',
            format: '0[.]0000000',
            validator: function(value, callback) { callback(multiValidatorHandsonTable(value, hydraulic_units_data_table_ruleset[0])); }
        },
        {
            title: hydraulic_units_data_table_ruleset[1].column,
            data: 1,
            type: 'numeric',
            format: '0[.]0000000',
            validator: function(value, callback) { callback(multiValidatorHandsonTable(value, hydraulic_units_data_table_ruleset[1])); }
        },
        {
            title: hydraulic_units_data_table_ruleset[2].column,
            data: 2,
            type: 'numeric',
            format: '0[.]0000000',
            validator: function(value, callback) { callback(multiValidatorHandsonTable(value, hydraulic_units_data_table_ruleset[2])); }
        }
      ]
    });

    // Para calcular los valores de la tabla de unidades hidráulicas con el FZI
    var hidraulic_units_data_hidden = $('#hidraulic_units_data').handsontable('getData');

    for (var i = hidraulic_units_data_hidden.length - 1; i >= 0; i--) {
      if (hidraulic_units_data_hidden[i][0] != null && hidraulic_units_data_hidden[i][1] != null && hidraulic_units_data_hidden[i][2] != null) {
        var value = 0.0314 * Math.sqrt(hidraulic_units_data_hidden[i][2] / hidraulic_units_data_hidden[i][1]) / (hidraulic_units_data_hidden[i][1]/(1-hidraulic_units_data_hidden[i][1]));
        hidraulic_units_data_hidden[i].splice(1,0,value);
      }else{
        hidraulic_units_data_hidden[i].splice(1,0,null);
      }
    }

    $hidraulic_units_data_table = $("#hidraulic_units_data_hidden");
    $hidraulic_units_data_table.handsontable({
      data: hidraulic_units_data_hidden,
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
        }, {
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

  function parseArrayToNumeric(multidimensionalArray) {
    for (var i = multidimensionalArray.length - 1; i >= 0; i--) {
      for (var j = multidimensionalArray[i].length - 1; j >= 0; j--) {
        multidimensionalArray[i][j] = parseFloat(multidimensionalArray[i][j]);
      }
    }

    return multidimensionalArray;
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
        // Cálculo de la tabla sin el FZI
        var hydraulic_units_data = [[production_formation_thickness, porosity, permeability]];
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

      // Para calcular los valores de la tabla de unidades hidráulicas con el FZI
      var hidraulic_units_data_hidden = $('#hidraulic_units_data').handsontable('getData');
      for (var i = hidraulic_units_data_hidden.length - 1; i >= 0; i--) {
        if (hidraulic_units_data_hidden[i][0] != null && hidraulic_units_data_hidden[i][1] != null && hidraulic_units_data_hidden[i][2] != null) {
          var value = 0.0314 * Math.sqrt(hidraulic_units_data_hidden[i][2] / hidraulic_units_data_hidden[i][1]) / (hidraulic_units_data_hidden[i][1]/(1-hidraulic_units_data_hidden[i][1]));
          hidraulic_units_data_hidden[i].splice(1,0,value);
        }else{
          hidraulic_units_data_hidden[i].splice(1,0,null);
        }
      }

      // Actualizar los valores de la tabla de unidades hidráulicas sin el FZI
      var hidraulic_units_data_table_hidden = $("#hidraulic_units_data_hidden").handsontable('getInstance');
      hidraulic_units_data_table_hidden.updateSettings(
      {
        data: hidraulic_units_data_hidden,
        stretchH: 'all'
      });
      hidraulic_units_data_table_hidden.render();

    }
    else if (well_completitions == 1 || well_completitions == 2) 
    {
      if(formation_thickness && porosity && permeability)
      {
        //Cálculo de la tabla sin el FZI
        var hydraulic_units_data = [[formation_thickness, porosity, permeability]];
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

    // Para calcular los valores de la tabla de unidades hidráulicas con el FZI
    var hidraulic_units_data_hidden = $('#hidraulic_units_data').handsontable('getData');
    for (var i = hidraulic_units_data_hidden.length - 1; i >= 0; i--) {
      if (hidraulic_units_data_hidden[i][0] != null && hidraulic_units_data_hidden[i][1] != null && hidraulic_units_data_hidden[i][2] != null) {
        var value = 0.0314 * Math.sqrt(hidraulic_units_data_hidden[i][2] / hidraulic_units_data_hidden[i][1]) / (hidraulic_units_data_hidden[i][1]/(1-hidraulic_units_data_hidden[i][1]));
        hidraulic_units_data_hidden[i].splice(1,0,value);
      }else{
        hidraulic_units_data_hidden[i].splice(1,0,null);
      }
    }

    // Actualizar los valores de la tabla de unidades hidráulicas sin el FZI
    var hidraulic_units_data_table_hidden = $("#hidraulic_units_data_hidden").handsontable('getInstance');
    hidraulic_units_data_table_hidden.updateSettings(
    {
      data: hidraulic_units_data_hidden,
      stretchH: 'all'
    });
    hidraulic_units_data_table_hidden.render();
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