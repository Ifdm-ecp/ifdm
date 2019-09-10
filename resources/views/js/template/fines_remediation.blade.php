<!-- HandsOnTable JS -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script type='text/javascript' src="{{asset('js/tipsy/jquery.tipsy.js')}}"></script>
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script>
   var excel_damage_diagnosis = '';
   $(document).ready(function() {

      import_tree("Fines remediation", "fines_remediation");

      @if (count($errors) > 0)
      $('#modal_errors').modal('show');
      @endif

      init();

      setTimeout(function() {
         $('#loading').hide();
         initModalImport();
      }, 2000);
      
   });

   function initModalImport() {
      var type_scenario = "Swelling and fines migration";
      var Tree = [];
      var getImportTree = $.get("{{url('ImportExternalTree')}}", { type: type_scenario}, function (data) {
         Tree = data;
      });

      getImportTree.done(function () {
         $('#treeImport').tree(Tree);
      });

      $(document).on('click','a[href="#link_external"]',function(node){
         var nodo = $(this);
         var var_href = $(this).attr('href');
         var sel;

         if(var_href == "#link_external"){
            sel = Tree;
         }

         var pos = $(this).find('span').attr('id');
         var pos = pos.split("_");

         for (var i = 1; i <= pos.length-1 ; i++){
            sel = sel[pos[i]];
            var k = i + 1;
            if (k <= pos.length - 1){
               sel = sel['child'];
            }
         }
         
         $.ajax({
            url: "{{ url('getTreeExternalData') }}/"+sel.id,
         })
         .done(function(res) {
            var datos = JSON.parse(res);

            var excel_damage_diagnosis = $('#excel_damage_diagnosis');
            excel_damage_diagnosis.handsontable({
               colWidths: [100, 200, 100] ,
               rowHeaders: true,
               data: datos,
               height: 600,
               columns: [
               {title:"Radius [ft] ", data: 0,type: 'numeric', format: '0[.]0000000'},
               {title:"Permeability [mD] ",data: 1,type: 'numeric', format: '0[.]0000000'},
               {title:"Porosity ", data: 2,type: 'numeric', format: '0[.]0000000'}
               ],
               startRows: 6,
               minSpareRows: 2,
               contextMenu: true,
            });

            $('#externalModalButton').modal('hide');
         })
         .fail(function(res) {
            return false;
            console.log("error");
         });
         
      });
   }

   function init() {

      $('#run').on('click', function(event) {
         run(0);
      });

      $('#button_sw').on('click', function(event) {
         run(1);
      });

      /* Se define el init de la tabla excel_damage_diagnosis */
      var excel_damage_diagnosis = $('#excel_damage_diagnosis');
      var datos_excel_damage_diagnosis = {!! !empty($fines->excel_damage_diagnosis_input) ? "JSON.parse($fines->excel_damage_diagnosis_input)" : '{}' !!};

      excel_damage_diagnosis.handsontable({
         colWidths: [100, 200, 100] ,
         rowHeaders: true,
         data: datos_excel_damage_diagnosis,
         height: 600,
         columns: [
         {title:"Radius [ft] ", data: 0,type: 'numeric', format: '0[.]0000000'},
         {title:"Permeability [mD] ",data: 1,type: 'numeric', format: '0[.]0000000'},
         {title:"Porosity ", data: 2,type: 'numeric', format: '0[.]0000000'}
         ],
         startRows: 6,
         minSpareRows: 2,
         contextMenu: true,
      });

      /* Se define el init de la tabla excel_rock_composition */
      var excel_rock_composition = $('#excel_rock_composition');
      var datos_excel_rock_composition = {!! !empty($fines->excel_rock_composition_input) ? "JSON.parse($fines->excel_rock_composition_input)" : $data_excel_rock_composition !!};

      excel_rock_composition.handsontable({
         colWidths: [200, 100, 100] ,
         rowHeaders: true,
         data: datos_excel_rock_composition,
         columns: [
         {title:"Mineral ", data: 0,type: 'text', 'readOnly': true },
         {title:" %wt ",data: 1,type: 'numeric', format: '0[.]0000000'},
         {title:"Density ", data: 2,type: 'numeric', format: '0[.]0000000'}
         ],
         startRows: 3,
         contextMenu: true,
      });

      /* Sirve al editar, da el formato especial a los minerales si existen. */
      var minerals = [];
      @if(isset($fines->check_minerals_input))
      minerals = "{{ $fines->check_minerals_input }}";
      minerals = minerals.split(',');
      @endif

      setTimeout(function() {
         $.each($('[name=check_minerals]'), function(index, val) {
            var nodo = $(val);
            nodo.removeAttr('checked')

            $.each(minerals, function(index, val2) {
               if (nodo.val() == val2) {
                  nodo.prop('checked',true);
               }
            });
         });
      }, 500);

   }

   /* Función encargada de capturar los datos y hacer submit al form */
   function run(valor) {
      $('#loading').show();
      $('#_button_swr').val(valor);

      var sw = (valor == 0) ? false : true;

      capturarTablaDamageDiagnosis(sw);
      capturarTablaRockComposition(sw);
      defineMinerals(sw);

      setTimeout(function() {
         if($("#excel_damage_diagnosis_input").val() != 'error' && $("#excel_rock_composition_input").val() != 'error' && validateInputs(sw)){
            var form = $('#form_fines_remediation');
            form.submit();
         }
         $('#loading').hide();
      }, 1000);

   }

   /* Encargado de realizar las validaciones a los respectivos campos */
   function validateInputs(sw) {

      if (sw) {
         return true;   
      }


      /* Reservoir Properties */
      var initial_porosity = $('#initial_porosity');
      if (parseFloat(initial_porosity.val()) <= 0 || parseFloat(initial_porosity.val()) > 1) {
         initial_porosity.parents('div.input-group').addClass('has-error');
         alert("The [ Initial Porosity ] must be between 0.1 and 1");
         return false;
      }

      var initial_permeability = $('#initial_permeability');
      if (parseFloat(initial_permeability.val()) <= 0) {
         initial_permeability.parents('div.input-group').addClass('has-error');
         alert("The [ Initial Permeability ] must be greater than 0.");
         return false;
      }

      var temperature = $('#temperature');
      if (parseFloat(temperature.val()) <= 0) {
         temperature.parents('div.input-group').addClass('has-error');
         alert("The [ Temperature ] must be greater than 0.");
         return false;
      }

      var well_radius = $('#well_radius');
      if (parseFloat(well_radius.val()) <= 0) {
         well_radius.parents('div.input-group').addClass('has-error');
         alert("The [ Well Radius ] must be greater than 0.");
         return false;
      }

      var damage_radius = $('#damage_radius');
      if (parseFloat(damage_radius.val()) <= 0 || parseFloat(damage_radius.val()) <= parseFloat(well_radius.val())) {
         damage_radius.parents('div.input-group').addClass('has-error');
         alert("The [ Damage Radius ] must be greater than 0 and [ Well Radius value ].");
         return false;
      }

      var netpay = $('#netpay');
      if (parseFloat(netpay.val()) <= 0) {
         netpay.parents('div.input-group').addClass('has-error');
         alert("The [ Net Pay ] must be greater than 0.");
         return false;
      }

      var rock_compressibility = $('#rock_compressibility');
      if (parseFloat(rock_compressibility.val()) <= 0) {
         rock_compressibility.parents('div.input-group').addClass('has-error');
         alert("The [ Rock Compressibility ] must be greater than 0.");
         return false;
      }

      var pressure = $('#pressure');
      if (parseFloat(pressure.val()) <= 0) {
         pressure.parents('div.input-group').addClass('has-error');
         alert("The [ Pressure ] must be greater than 0.");
         return false;
      }

      /* Treatment Data */
      var acid_concentration = $('#acid_concentration');
      if (parseFloat(acid_concentration.val()) <= 0 || acid_concentration.val() > 100) {
         acid_concentration.parents('div.input-group').addClass('has-error');
         alert("The [ Acid Concentration ] be between 0 and 100.");
         return false;
      }

      var injection_rate = $('#injection_rate');
      if (parseFloat(injection_rate.val()) <= 0) {
         injection_rate.parents('div.input-group').addClass('has-error');
         alert("The [ Injection Rate ] must be greater than 0.");
         return false;
      }

      var invasion_radius = $('#invasion_radius');
      if (parseFloat(invasion_radius.val()) <= 0 || parseFloat(invasion_radius.val()) <= parseFloat(well_radius.val())) {
         invasion_radius.parents('div.input-group').addClass('has-error');
         alert("The [ Invasion Radius ] must be greater than 0 and [ Well Radius value ].");
         return false;
      }
      return true;

   }

   /* Esta función se encarga de definir los minerales seleccionados y pasarlos a un input con un separador tipo , */
   function defineMinerals() {

      var nodos = $('[name=check_minerals]');
      var minerals = '';
      $.each(nodos, function(index, val) {
         var nodo = $(val);
         if (nodo.is(':checked')) {
            minerals = minerals + nodo.val() + ',';
         }
      });

      minerals = minerals.substring(0, (minerals.length -1));
      $('#check_minerals_input').val(minerals);

   }

   /* Función encargada de realizar las capturas de datos en la tabla Damage Diagnosis */
   function capturarTablaDamageDiagnosis(sw) {

      var excel_damage_diagnosis = order_table($("#excel_damage_diagnosis").handsontable('getData'));
      var v_excel_damage_diagnosis = [];
      var mensaje = "";

      for (var i = 0; i < excel_damage_diagnosis.length; i++) {
         d0 = excel_damage_diagnosis[i][0];
         d1 = excel_damage_diagnosis[i][1];
         d2 = excel_damage_diagnosis[i][2];

         if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) {
            continue;
         } else {

            if (d0 <= 0 || d1 <= 0 || d2 <= 0 && !sw) {
               mensaje = "All values must contain a value greater than zero in the table Damage Diagnosis.";
               break;
            } else {
               v_excel_damage_diagnosis.push(excel_damage_diagnosis[i]);
            }

         }
      }

      if (mensaje == '' && v_excel_damage_diagnosis.length < 2 && !sw) {
         mensaje = "At least two rows of valid data are required in the table Changes Along The Radius on the [ Reservoir Data ] section.";
      }

      if (mensaje != '' && !sw) {
         alert(mensaje);
         $("#excel_damage_diagnosis_input").val('error');
      } else {
         $("#excel_damage_diagnosis_input").val(JSON.stringify(v_excel_damage_diagnosis));
      }

   }

   /* Función encargada de realizar las capturas de datos en la tabla Rock Composition */
   function capturarTablaRockComposition(sw) {

      var excel_rock_composition = order_table($("#excel_rock_composition").handsontable('getData'));
      var v_excel_rock_composition = [];
      var mensaje = '';
      var suma = 0;

      for (var i = 0; i < excel_rock_composition.length; i++) {
         console.log(excel_rock_composition[i]);
         d0 = excel_rock_composition[i][0];
         d1 = excel_rock_composition[i][1];
         d2 = excel_rock_composition[i][2];

         if(((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) && !sw) {

            continue;

         } else {

            if ((d1 < 0 || d2 <= 0) && !sw) {
               mensaje = "All values must contain a value greater than zero in the table Damage Diagnosis.";
               break;
            } else {
               suma = (suma + parseFloat(d1));
               v_excel_rock_composition.push(excel_rock_composition[i]);
            }

         }
      }

      suma = suma.toFixed(2)

      if ((mensaje == '' && (suma < 100 || suma > 100)) && !sw) {
         mensaje = 'The rows [ %wt ] sum must be equal to 100.';
      }

      if (mensaje != '' && !sw) {
         alert(mensaje);
         $("#excel_rock_composition_input").val('error');
      } else {
         $("#excel_rock_composition_input").val(JSON.stringify(v_excel_rock_composition));
      }

   }

   /* Función encargada de ordenar las tablas */
   function order_table(tabla) {

      var row_aux;
      for (var i = 0 ; i<tabla.length; i++) {
         for (var j=0; j<tabla.length; j++) {
            if(tabla[j][0]>tabla[i][0] && tabla[i][0]) {
               row_aux = tabla[j];
               tabla[j] = tabla[i];
               tabla[i] = row_aux;
            }
         }   
      }
      return tabla;

   }
</script>