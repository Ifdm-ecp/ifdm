<script type="text/javascript">
    var Tree=[];
    var getImportTree;
    var valueField;
    var type;
    var input_id;
    var input_name;
    var value_db;

    function import_tree(type_scenario, type_advisor){
        type = type_scenario;
        advisor = type_advisor;
        getImportTree = $.get("{{url('getAdvisorTree')}}", { type: type_scenario}, function (data) {
            Tree = data;
        });
    }

    

    $(document).ready(function(){
        $(".button-advisor").tooltip({
            title: 'Help'
        });   

        $(".show-table-advisor").tooltip({
            title: 'Help'
        });
        getImportTree.done(function () {
            $('#treeAdvisor').tree(Tree);
        });

        $(document).on('click','a[href="#link"]',function(){
            var table = input_name.includes("code_table_");

            if(!table){
                $("#text_tree").removeClass("d-none");
                $("#text_tree").appendTo($(this).find("span"));
            }

            var var_href = $(this).attr('href');
            var sel;

            if(var_href == "#link"){
                sel = Tree;
            }

            var pos=$(this).find('span').attr('id');
            var pos=pos.split("_");

            for (var i=1;i<=pos.length-1;i++){
                sel=sel[pos[i]];
                var k=i+1;
                if (k<=pos.length-1){
                    sel=sel['child'];
                }
            }

            if(table){
                input_name = input_name.replace("code_table_",'');
                var table = [];
                var components = [];

                if(advisor == "asphaltenes_precipitated_analysis"){
                    if(input_name == "components_table" || input_name == "binary_interaction_coefficients_table"){
                        var col_values_binary_interaction = [];
                        item_binary_interaction = {}
                        item_binary_interaction ["title"] = "Components";
                        item_binary_interaction ["data"] = "components";
                        item_binary_interaction ["type"] = "text";
                        item_binary_interaction ["readOnly"] = true;
                        col_values_binary_interaction.push(item_binary_interaction);

                        var hot_coefficients_table = $('#binary_interaction_coefficients_table').handsontable('getInstance');
                    }
                }

                var getValueField = $.get("{{url('getAdvisorValue')}}", { scenario_id: sel['id'], type: type, input_name:input_name, advisor: advisor }, function (data) {
                    $.each(data, function(index,value){
                        table.push(Object.values(value));
                        if(input_name == "components_table" || input_name == "binary_interaction_coefficients_table"){
                            components.push(value.component);

                            if(advisor == "asphaltenes_precipitated_analysis"){
                                item_binary_interaction = {}
                                item_binary_interaction ["title"] = value.component;
                                item_binary_interaction ["data"] = value.component;
                                item_binary_interaction ["type"] = 'numeric';
                                item_binary_interaction ["format"] = '0[.]0000000';
                                col_values_binary_interaction.push(item_binary_interaction);
                            }
                        }
                    });

                    if(input_name == "pvt_table" && type == "Swelling and fines migration"){
                        $("#graphic_pvt_table" ).empty();
                        var type_flux = null;
                        var pvt_table_advisor = $pvt_table.handsontable('getInstance');
                        var getValueFinesFlux = $.get("{{url('get_type_of_suspension_flux')}}", { scenario_id: sel['id']}, function (data) {
                            $.each(data, function(index,value){
                                type_flux = value;
                            });
                            
                            var pvt = [];
                            if(type_flux == "oil"){
                                var new_item = [];
                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Pressure [psi]";
                                item_suspension_flux ["data"] = 0;
                                new_item.push(item_suspension_flux);

                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Oil Density [g/cc]";
                                item_suspension_flux ["data"] = 1;
                                new_item.push(item_suspension_flux);

                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Oil Viscosity [cP]";
                                item_suspension_flux ["data"] = 2;
                                new_item.push(item_suspension_flux);

                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Oil Volumetric Factor [bbl/BN]";
                                item_suspension_flux ["data"] = 3;
                                new_item.push(item_suspension_flux);

                                var getValueFines = $.get("{{url('get_advisor_pvt_table_oil')}}", { scenario_id: sel['id']}, function (data) {
                                    $.each(data, function(index,value){
                                        pvt.push(Object.values(value));
                                    });
                                    pvt_table_advisor.updateSettings({
                                        columns: new_item,
                                        data: pvt,
                                        stretchH: 'all'
                                    });
                                });
                                $('#type_of_suspension_flux').selectpicker('val', "oil"); 
                                $('#historical_water').hide(); 
                                $('#historical_oil').show(); 
                            }else if(type_flux == "water"){
                                var new_item = [];
                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Pressure [psi]";
                                item_suspension_flux ["data"] = 0;
                                new_item.push(item_suspension_flux);

                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Water Volumetric Factor [bbl/BN]";
                                item_suspension_flux ["data"] = 1;
                                new_item.push(item_suspension_flux);

                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Water Viscosity [cP]";
                                item_suspension_flux ["data"] = 2;
                                new_item.push(item_suspension_flux);

                                var item_suspension_flux = {}
                                item_suspension_flux ["type"] = "numeric";
                                item_suspension_flux ["format"] = "0[.]0000000";
                                item_suspension_flux ["title"] = "Water Density [g/cc]";
                                item_suspension_flux ["data"] = 3;
                                new_item.push(item_suspension_flux);

                                var getValueFines = $.get("{{url('get_advisor_pvt_table_water')}}", { scenario_id: sel['id']}, function (data) {
                                    $.each(data, function(index,value){
                                        pvt.push(Object.values(value));
                                    });
                                    pvt_table_advisor.updateSettings({
                                        columns: new_item,
                                        data: pvt,
                                        stretchH: 'all'
                                    });
                                });
                                $('#type_of_suspension_flux').selectpicker('val', "water"); 
                                $('#historical_water').show(); 
                                $('#historical_oil').hide(); 
                            }
                        });
                        pvt_table_advisor.render();
                    }
                    if(input_name == "components_table" || input_name == "binary_interaction_coefficients_table"){
                        $("#components").selectpicker('val', components);

                        if(advisor == "asphaltenes_precipitated_analysis"){


                            var getValueField = $.get("{{url('get_precipitated_analysis_binary_coefficients')}}", { scenario_id: sel['id']}, function (data) {
                                $.each(data, function(index,value){
                                    hot_coefficients_table.updateSettings({
                                        columns: col_values_binary_interaction,
                                        data: JSON.parse(value),
                                        stretchH: 'all'
                                    });
                                });
                            });

                            hot_coefficients_table.render();

                            if(components.indexOf("Plus +") != -1){
                                $("#div_plus").show();
                            }else{
                                $("#div_plus").hide();
                            }
                        }
                        
                    }

                    if(input_name == "binary_interaction_coefficients_table"){
                        $("#components_table").handsontable({data : table});
                        $("#components_table").handsontable('render');
                    }else if(input_name != "pvt_table"){
                        $("#"+input_name).handsontable({data : table});
                        $("#"+input_name).handsontable('render');
                    }
                });
                $("#advisorModalButton").modal('hide');
            }else{
                var getValueField = $.get("{{url('getAdvisorValue')}}", { scenario_id: sel['id'], type: type, input_name:input_name, advisor: advisor }, function (data) {
                    if(data.length > 0){
                        value_db = null;
                        key = Object.keys(data[0]);
                        value_db = data[0][key];
                        $("#value_db").val("");

                        if(value_db == null || value_db == ""){
                            $("#value_db").val("There is no value");
                        }else{
                            $("#value_db").val(value_db);
                        }
                    }else{
                       $("#value_db").val("There is no value");
                    }
                });
            }
        });    

        $('#general_advisor').empty();
        $('#general_advisor').append($('#text_general_advisor').html());

        //$("#advisorModal").modal('show');
        var actual;

        $('.button-info').on('click',function(){
            $("#text_tree").addClass("d-none");
            $("#value_db").val("");
            actual = $(this).parent().next();
            input_name = actual.attr("id");

            input_id = "#advisor_" + input_name;
            input_ref = "#advisor_ref_" + input_name;
            $('#input_text_advisor').empty();
            $('#input_text_advisor').append($(input_id).html());

            $('#input_ref_advisor').empty();
            $('#input_ref_advisor').append($(input_ref).html());

            $("#info_tab").show();
            $("#Info").addClass('fade in active');
            $("#Info").addClass('fade');
            $("#info_tab").addClass('active');       

            $("#import_tab").hide();
            $("#Tree").removeClass('fade in active');
            $("#Tree").removeClass('fade');
            $("#import_tab").removeClass('active');

            $("#advisorModalButton").modal('show');

            if(! $(input_ref).length){
                $("#advisor_footer").addClass("d-none");
            }else{
                $("#advisor_footer").removeClass("d-none");
            }
        });

        $('.button-advisor').on('click',function(){
            $("#text_tree").addClass("d-none");
            $("#value_db").val("");
            actual = $(this).parent().next();
  
            input_name = actual.attr("id");
            input_id = "#advisor_" + input_name;
            input_ref = "#advisor_ref_" + input_name;
            $('#input_text_advisor').empty();
            $('#input_text_advisor').append($(input_id).html());

            $('#input_ref_advisor').empty();
            $('#input_ref_advisor').append($(input_ref).html());
            
            search_advisor(input_name);
            $("#advisorModalButton").modal('show');

            if(! $(input_ref).length){
                $("#advisor_footer").addClass("d-none");
            }else{
                $("#advisor_footer").removeClass("d-none");
            }
        });

        $('.show-panel-advisor').on('click',function(){
            panel_name = $(this).attr("id");

            input_id = "#advisor_" + panel_name;

            $('#general_advisor').empty();
            $('#general_advisor').append($(input_id).html());

            //$("#advisorModal").modal('show');
        });

        $('.set_value').on('click',function(){
            actual.val($("#value_db").val());
            $("#advisorModalButton").modal('hide');
        });

        $(document).on('click','.show-advisor',function(){
            $("#text_tree").addClass("d-none");
            $("#value_db").val("");
            actual = $(this);
            input_name = $(this).attr("id");
            search_advisor(input_name);
            input_id = "#advisor_" + input_name;
            input_ref = "#advisor_ref_" + input_name;

            $('#input_text_advisor').empty();
            $('#input_text_advisor').append($(input_id).html());

            $('#input_ref_advisor').empty();
            $('#input_ref_advisor').append($(input_ref).html());

            $(this).removeClass('show-advisor');
            $("#advisorModalButton").modal('show');

            if(! $(input_ref).length){
                $("#advisor_footer").addClass("d-none");
            }else{
                $("#advisor_footer").removeClass("d-none");
            }
        });

        //Advisor para tablas

        $('.show-table-advisor').on('click',function(){
            //Se debe poner el id del boton de la siguiente manera: id="table_(poner aca el id de la tabla)"
            $("#text_tree").addClass("d-none");
            $("#value_db").val("");
            input_name = $(this).attr("id");
            search_advisor(input_name);
            input_id = "#advisor_" + input_name;
            input_ref = "#advisor_ref_" + input_name;

            $('#input_text_advisor').empty();
            $('#input_text_advisor').append($(input_id).html());

            $('#input_ref_advisor').empty();
            $('#input_ref_advisor').append($(input_ref).html());

            $(this).removeClass('show-advisor');
            $("#advisorModalButton").modal('show');

            if(! $(input_ref).length){
                $("#advisor_footer").addClass("d-none");
            }else{
                $("#advisor_footer").removeClass("d-none");
            }

/*
            input_id = "#advisor_" + table_name;

            $('#general_advisor').empty();
            $('#general_advisor').append($(input_id).html());

            $("#advisorModal").modal('show');*/
        });
    });

</script>
