<!-- HandsOnTable JS -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script type='text/javascript' src="{{asset('js/tipsy/jquery.tipsy.js')}}"></script>
@if ($option_remediation == 1)
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">
<script src="https://code.highcharts.com/modules/exporting.js"></script>
@endif


<script type="text/javascript">
   var dp = $('#data_input').datepicker({
      dateFormat: "yy/mm/dd",
   });

   $(document).ready(function() {
      setTimeout(function() {
         import_tree("Asphaltene remediation", "asphaltene_remediation");
         init();
         initModalImport();
         $('#loading').hide();
      }, 2000);

   });


   function initModalImport() {
      var type_scenario = "Asphaltene precipitation";
      var Tree = [];
      var getImportTree = $.get("{{url('ImportExternalTreeAd')}}", { type: type_scenario}, function (data) {
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
            url: "{{ url('getTreeExternalDataAd') }}/"+sel.id,
         })
         .done(function(res) {
            var datos = JSON.parse(res);

            console.log(datos);
            var excel_changes_along_the_radius = $('#excel_changes_along_the_radius');
            excel_changes_along_the_radius.handsontable({
               width: 820,
               height: 320,
               colWidths: [100, 200, 200, 200] ,
               rowHeaders: true,
               data: datos,
               columns: [
               {title:"Radius [ft] ", data: 0,type: 'numeric', format: '0[.]0000000'},
               {title:"Permeability [mD] ",data: 1,type: 'numeric', format: '0[.]0000000'},
               {title:"Porosity ", data: 2,type: 'numeric', format: '0[.]0000000'},
               {title:"Deposited Asphaltenes [%wt] ", data: 3,type: 'numeric', format: '0[.]0000000'}
               ],
               startRows: 6,
               minSpareRows: 1,
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

   /* Función encargada de inicializar todo lo necesario de js */
   function init() {

      $('#option_treatment').on('change', function(event) {
         var nodo = $(this);
         if (nodo.val() != '') {

            var nodo_option = nodo.find('option[value='+nodo.val()+']');
            var tabla = nodo_option.attr('data_per');
            $('#prueba_tabla').html(tabla);

         } else {
            $('#prueba_tabla').html('No option treatment selected.');
         }
      });

      $('#option_treatment').change();

      @if (count($errors) > 0)
      $('#modal_errors').modal('show');
      @endif

      validate_stimate_ior({{ (isset($asphaltene->stimate_ior) && $asphaltene->stimate_ior == 'on') ? true : false }});

      validate_chemical_treatment_impl("{{ isset($asphaltene->chemical_treatment_impl) ? $asphaltene->chemical_treatment_impl : 'yes' }}");

      $('#stimate_ior').on('change', function(event) {
         validate_stimate_ior($(this).is(':checked'));
      });

      $('[name=chemical_treatment_impl]').on('change', function(event) {
         validate_chemical_treatment_impl($(this).val());
      });

      $('#run').on('click', function(event) {
         run(0);
      });

      $('#button_swr').on('click', function(event) {
         run(1);
      });

      @if ($option_remediation == 1)

      var excel_changes_along_the_radius = $('#excel_changes_along_the_radius');
      // var datos = [];
      var datos = {!! !empty($asphaltene->excel_changes_along_the_radius) ? "JSON.parse($asphaltene->excel_changes_along_the_radius)" : '{}' !!};

      excel_changes_along_the_radius.handsontable({
         width: 820,
         height: 320,
         colWidths: [100, 200, 200, 200] ,
         rowHeaders: true,
         data: datos,
         columns: [
         {title:"Radius [ft] ", data: 0,type: 'numeric', format: '0[.]0000000'},
         {title:"Permeability [mD] ",data: 1,type: 'numeric', format: '0[.]0000000'},
         {title:"Porosity ", data: 2,type: 'numeric', format: '0[.]0000000'},
         {title:"Deposited Asphaltenes [%wt] ", data: 3,type: 'numeric', format: '0[.]0000000'}
         ],
         startRows: 6,
         minSpareRows: 1,
         contextMenu: true,
      });    

      @endif
   }

   /* Encargado de realizar las validaciones a los respectivos campos */
   function validateInputs(sw) {

      if (sw == 1) {
         return true;
      }

      /* Reservoir Data con tabla */
      var initial_porosity = $('#initial_porosity');
      if (initial_porosity.val() <= 0 || initial_porosity.val() > 1) {
         initial_porosity.parents('div.input-group').addClass('has-error');
         alert("The [ Initial Porosity ] must be between 0.1 and 1");
         return false;
      } else {
         initial_porosity.parents('div.input-group').removeClass('has-error');
      }

      var net_pay = $('#net_pay');
      if (net_pay.val() <= 0) {
         net_pay.parents('div.input-group').addClass('has-error');
         alert("The [ Net Pay ] must be greater than 0.");
         return false;
      } else {
         net_pay.parents('div.input-group').removeClass('has-error');
      }

      var water_saturation = $('#water_saturation');
      if (water_saturation.val() <= 0) {
         water_saturation.parents('div.input-group').addClass('has-error');
         alert("The [ Water Saturation ] must be greater than 0.");
         return false;
      } else {
         water_saturation.parents('div.input-group').removeClass('has-error');
      }

      var initial_permeability = $('#initial_permeability');
      if (initial_permeability.val() <= 0) {
         initial_permeability.parents('div.input-group').addClass('has-error');
         alert("The [ Initial Permeability ] must be greater than 0.");
         return false;
      } else {
         initial_permeability.parents('div.input-group').removeClass('has-error');
      }

      /* Reservoir data sin tabla*/
      var current_permeability = $('#current_permeability');
      if (current_permeability.val() <= 0 || current_permeability.val() > initial_permeability.val()) {
         current_permeability.parents('div.input-group').addClass('has-error');
         alert("The [ Current Permeability ] must be greater than 0 and lower than [ Initial Permeability ] value.");
         return false;
      } else {
         current_permeability.parents('div.input-group').removeClass('has-error');
      }

      var sum_skins = 0;

      if ($('#skin_characterization_scale')) {
         var skin_characterization_scale = $('#skin_characterization_scale');
         if (skin_characterization_scale.val() <= 0) {
            skin_characterization_scale.parents('div.input-group').addClass('has-error');
            alert("The [ Scale ] on Skin Characterization section must be greater than 0.");
            return false;
         } else {
            sum_skins = sum_skins + parseFloat((skin_characterization_scale.val() == '') ? 0 : skin_characterization_scale.val());
            skin_characterization_scale.parents('div.input-group').removeClass('has-error');
         }
      }

      if ($('#skin_characterization_induced')) {
         var skin_characterization_induced = $('#skin_characterization_induced');
         if (skin_characterization_induced.val() <= 0) {
            skin_characterization_induced.parents('div.input-group').addClass('has-error');
            alert("The [ Induced ] on Skin Characterization section must be greater than 0.");
            return false;
         } else {
            sum_skins = sum_skins + parseFloat((skin_characterization_induced.val() == '') ? 0 : skin_characterization_induced.val());
            skin_characterization_induced.parents('div.input-group').removeClass('has-error');
         }
      }

      if ($('#skin_characterization_asphaltene')) {
         var skin_characterization_asphaltene = $('#skin_characterization_asphaltene');
         if (skin_characterization_asphaltene.val() <= 0) {
            skin_characterization_asphaltene.parents('div.input-group').addClass('has-error');
            alert("The [ Asphaltene ] on Skin Characterization section must be greater than 0.");
            return false;
         } else {
            sum_skins = sum_skins + parseFloat((skin_characterization_asphaltene.val() == '') ? 0 : skin_characterization_asphaltene.val());
            skin_characterization_asphaltene.parents('div.input-group').removeClass('has-error');
         }
      }

      if ($('#skin_characterization_fines')) {
         var skin_characterization_fines = $('#skin_characterization_fines');
         if (skin_characterization_fines.val() <= 0) {
            skin_characterization_fines.parents('div.input-group').addClass('has-error');
            alert("The [ Fines ] on Skin Characterization section must be greater than 0.");
            return false;
         } else {
            sum_skins = sum_skins + parseFloat((skin_characterization_fines.val() == '') ? 0 : skin_characterization_fines.val());
            skin_characterization_fines.parents('div.input-group').removeClass('has-error');
         }
      }

      if ($('#skin_characterization_non_darcy')) {
         var skin_characterization_non_darcy = $('#skin_characterization_non_darcy');
         if (skin_characterization_non_darcy.val() != '' && skin_characterization_non_darcy.val() <= 0) {
            skin_characterization_non_darcy.parents('div.input-group').addClass('has-error');
            alert("The [ Non Darcy ] on Skin Characterization section must be greater than 0.");
            return false;
         } else {
            sum_skins = sum_skins + parseFloat((skin_characterization_non_darcy.val() == '') ? 0 : skin_characterization_non_darcy.val());
            skin_characterization_non_darcy.parents('div.input-group').removeClass('has-error');
         }
      }

      if ($('#skin_characterization_geomechanical')) {
         var skin_characterization_geomechanical = $('#skin_characterization_geomechanical');
         if (skin_characterization_non_darcy.val() != '' && skin_characterization_geomechanical.val() <= 0) {
            skin_characterization_geomechanical.parents('div.input-group').addClass('has-error');
            alert("The [ Geomechanical ] on Skin Characterization section must be greater than 0.");
            return false;
         } else {
            sum_skins = sum_skins + parseFloat((skin_characterization_geomechanical.val() == '') ? 0 : skin_characterization_geomechanical.val());
            skin_characterization_geomechanical.parents('div.input-group').removeClass('has-error');
         }
      }

      console.log(sum_skins);
      if (sum_skins > 100) {
         skin_characterization_scale.parents('div.input-group').addClass('has-error');
         skin_characterization_induced.parents('div.input-group').addClass('has-error');
         skin_characterization_asphaltene.parents('div.input-group').addClass('has-error');
         skin_characterization_fines.parents('div.input-group').addClass('has-error');
         skin_characterization_non_darcy.parents('div.input-group').addClass('has-error');
         skin_characterization_geomechanical.parents('div.input-group').addClass('has-error');

         alert("The [ Skin Characterization ] sum of values must be lower than or equal 100.");
         return false;
      } else {
         skin_characterization_scale.parents('div.input-group').removeClass('has-error');
         skin_characterization_induced.parents('div.input-group').removeClass('has-error');
         skin_characterization_asphaltene.parents('div.input-group').removeClass('has-error');
         skin_characterization_fines.parents('div.input-group').removeClass('has-error');
         skin_characterization_non_darcy.parents('div.input-group').removeClass('has-error');
         skin_characterization_geomechanical.parents('div.input-group').removeClass('has-error');
      }

      /* Asphaltene Data */
      var asphaltene_apparent_density = $('#asphaltene_apparent_density');
      if (asphaltene_apparent_density.val() < 0.95 || asphaltene_apparent_density.val() > 1.2) {
         asphaltene_apparent_density.parents('div.input-group').addClass('has-error');
         alert("The [ Asphaltene Apparent Density ] must be between 0.95 and 1.2");
         return false;
      } else {
         asphaltene_apparent_density.parents('div.input-group').removeClass('has-error');
      }

      /* Treatment Data */
      /* Asphaltene Dilution Capacity */
      if($('#chemical_treatment_impl_yes').is(':checked')) {
         var asphaltene_dilution_capacity = $('#asphaltene_dilution_capacity');
         if (asphaltene_dilution_capacity.val() <= 0) {
            asphaltene_dilution_capacity.parents('div.input-group').addClass('has-error');
            alert("The [ Asphaltene Dilution Capacity ] must be greater than 0.");
            return false;
         } else {
            asphaltene_dilution_capacity.parents('div.input-group').removeClass('has-error');
         }
      } else {
         var option_treatment = $('#option_treatment');
         if (option_treatment.val() == '') {
            option_treatment.parents('div.input-group').addClass('has-error');
            alert("The [ Option Treatment ] must have a selected option.");
            return false;
         } else {
            option_treatment.parents('div.input-group').removeClass('has-error');
         }
      }

      /* Treatment Data */
      var treatment_radius = $('#treatment_radius');
      var wellbore_radius = $('#wellbore_radius');

      if (wellbore_radius.val() <= 0) {
         wellbore_radius.parents('div.input-group').addClass('has-error');
         alert("The [ Wellbore Radius ] must be greater than 0.");
         return false;
      } else {
         wellbore_radius.parents('div.input-group').removeClass('has-error');
      }

      if (treatment_radius.val() <= 0 || treatment_radius.val() < wellbore_radius.val()) {
         treatment_radius.parents('div.input-group').addClass('has-error');
         alert("The [ Treatment Radius ] must be greater than 0 and greater than [ Wellbore Radius ] value.");
         return false;
      } else {
         treatment_radius.parents('div.input-group').removeClass('has-error');
      }

      var start = $('#asphaltene_remotion_efficiency_range_a');
      var end = $('#asphaltene_remotion_efficiency_range_b');

      if (start.val() <= 0 || end.val() <= 0 || start.val() > end.val() ||  start.val() > 100 || end.val() > 100) {
         start.parents('div.input-group').addClass('has-error');
         alert("On [Asphaltene Remotion Efficiency Range] start range must be greater than 0 and lower than the final range and this must be lower to 100.");
         return false;
      } else {
         start.parents('div.input-group').removeClass('has-error');
      }

      /* Treatment Reward */
      if ($('#stimate_ior').is(':checked')) {

         var monthly_decline_rate = $('#monthly_decline_rate');
         if (monthly_decline_rate.val() <= 0) {
            monthly_decline_rate.parents('div.input-group').addClass('has-error');
            alert("The [ Monthly Decline Rate ] must be greater than 0.");
            return false;
         } else {
            monthly_decline_rate.parents('div.input-group').removeClass('has-error');
         }

         var current_oil_production = $('#current_oil_production');
         if (current_oil_production.val() <= 0) {
            current_oil_production.parents('div.input-group').addClass('has-error');
            alert("The [ Current Oil Production ] must be greater than 0.");
            return false;
         } else {
            current_oil_production.parents('div.input-group').removeClass('has-error');
         }

         var date = $('#data_input');
         var date_in = date.val();
         var date_for = date_in.replace('/', '-');
         date_for = new Date(date_for);
         var hoy = new Date('{{ date('Y-m-d') }}');
         hoy.setHours(0,0,0,0);

         if (date_for > hoy) {
            date.parents('div.input-group').addClass('has-error');
            alert("The [Date of current oil production] can not be higher than the current date.");
            return false;
         } else {
            date.parents('div.input-group').removeClass('has-error');
         }

      }


      return true;

   }

   function run(valor) {
      $('#loading').show();
      $('#_button_swr').val(valor);


      @if ($option_remediation == 1)
      capturarTabla(valor);
      @endif

      setTimeout(function() {
         if ($("#excel_changes_along_the_radius_input").val() != 'error' && validateInputs(valor)) {
            var form = $('#form_asphaltene');
            form.submit();
         }
         $('#loading').hide();
      }, 1000);


   }

   function validate_chemical_treatment_impl(option) {
      var div_chemical_treatment_impl_no_div = $('#chemical_treatment_impl_no_div');
      var div_chemical_treatment_impl_yes_div = $('#chemical_treatment_impl_yes_div');

      if (option == 'yes') {
         div_chemical_treatment_impl_yes_div.show();
         div_chemical_treatment_impl_no_div.hide();
      } else {
         div_chemical_treatment_impl_no_div.show();
         div_chemical_treatment_impl_yes_div.hide();
      }
   }

   function validate_stimate_ior(show) {
      var div_check = $('#stimate_ior_checked');
      var monthly_decline_rate = $('#monthly_decline_rate');
      var current_oil_production = $('#current_oil_production');
      var data_input = $('#data_input');
      var stimate_ior_input = $('#stimate_ior_input');

      if (show) {
         stimate_ior_input.val('on');
         div_check.show();
         monthly_decline_rate.val({{ isset($asphaltene->monthly_decline_rate) ? $asphaltene->monthly_decline_rate : '' }});
         current_oil_production.val({{ isset($asphaltene->current_oil_production) ? $asphaltene->current_oil_production : '' }});

         @if(isset($asphaltene->data_input))
         data_input.datepicker("setDate", "{{ $asphaltene->data_input }}");
         @else
         data_input.val('');
         @endif

      } else {
         stimate_ior_input.val('off');
         div_check.hide();
      }
   }

   function capturarTabla(sw) {
      var excel_changes_along_the_radius = order_table($("#excel_changes_along_the_radius").handsontable('getData'));
      var v_excel_changes_along_the_radius = [];
      var mensaje = '';
      var initial_permeability = $('#initial_permeability').val();

      for (var i = 0; i < excel_changes_along_the_radius.length; i++) {
         d0 = excel_changes_along_the_radius[i][0];
         d1 = excel_changes_along_the_radius[i][1];
         d2 = excel_changes_along_the_radius[i][2];

         if((d0 ==="" || d0 == null) && (d1==="" || d1 == null) && (d2==="" || d2 == null)) {
            continue;
         } else {

            if (d0 <= 0 || d1 <= 0 || d2 < 0 && sw != 1) {
               mensaje = "All values must contain a value greater than zero in the table Changes Along The Radius on the [ Reservoir Data ] section.";
               break;
            } else {
               v_excel_changes_along_the_radius.push(excel_changes_along_the_radius[i]);
            }

         }
      }

      if (mensaje == '' && v_excel_changes_along_the_radius.length < 2 && sw != 1) {
         mensaje = "At least two rows of valid data are required in the table Changes Along The Radius on the [ Reservoir Data ] section.";
      }

      if (mensaje != '' && sw != 1) {
         alert(mensaje);
         $("#excel_changes_along_the_radius_input").val('error');
      } else {
         $("#excel_changes_along_the_radius_input").val(JSON.stringify(v_excel_changes_along_the_radius));
      }
   }

   function order_table(tabla)
   {
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