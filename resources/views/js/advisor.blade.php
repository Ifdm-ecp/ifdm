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
        init_advisor();
    }

    function search_advisor(input_name){
        $("#advisorModalButton").modal('show');
        var advisor_name = document.getElementById("advisor_" + input_name);
        if (advisor_name === null){
            $("#info_tab").hide();
            $("#Info").removeClass('fade in active');
            $("#Info").addClass('fade');
            $("#info_tab").removeClass('active');       

            $("#import_tab").show();
            $("#Tree").addClass('fade in active');
            $("#Tree").removeClass('fade');
            $("#import_tab").addClass('active');
        }else{
            $("#info_tab").show();
            $("#Info").addClass('fade in active');
            $("#Info").removeClass('fade');
            $("#info_tab").addClass('active');

            $("#Tree").removeClass('fade in active');
            $("#Tree").addClass('fade');
            $("#import_tab").removeClass('active');
        }
    }

    function init_advisor() {
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

                $.get("{{url('getAdvisorValue')}}", { scenario_id: sel['id'], type: type, input_name:input_name, advisor: advisor }, function (data) {
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



                });
                $("#advisorModalButton").modal('hide');
            } else {
                $.get("{{url('getAdvisorValue')}}", { scenario_id: sel['id'], type: type, input_name:input_name, advisor: advisor }, function (data) {

                    if(data.length > 0) {
                        $.each(data, function(index, value) {

                            var i = 0;
                            $.each(value, function(index, val) {
                                i++;
                            });

                            if (i > 1 && typeof value == 'object') {
                                /*  $('#text_tree').html(''); */
                                /*  $('#text_tree').append('<div class="form-group">'); */

                                var valor_concatenado = '';
                                var inputs = [];
                                $.each(value, function(in_key, val) {
                                    if(val == null || val == ""){
                                        val = "There is no value";
                                    }

                                    inputs.push(in_key);
                                    valor_concatenado += val+' a ';

                                });

                                $('#text_tree').find('button.set_value').hide();
                                if ($('.set_value_double').length < 1) {
                                    $('#text_tree').append('<button type="button" class="btn btn-default set_value_double" data-dismiss="modal">Set</button>');
                                }

                                valor_concatenado = valor_concatenado.substring(0,(valor_concatenado.length - 3));
                                $("#value_db").val(valor_concatenado);

                                $('.set_value_double').on('click',function(){
                                    var valores = $("#value_db").val();
                                    if (valores != "There is no value") {
                                        var arr_valores = valores.split(' a ');
                                        for (var i = 0; i < inputs.length; i++) {
                                            $('#'+inputs[i]).val(parseFloat(arr_valores[i]));
                                        }
                                    }

                                    $("#advisorModalButton").modal('hide');
                                });

                            } else {
                                $('#text_tree').find('button.set_value').show();
                                $('#text_tree').find('button.set_value_double').hide();

                                value_db = null;
                                key = Object.keys(data[0]);
                                value_db = data[0][key];
                                $("#value_db").val("");

                                if(value_db == null || value_db == ""){
                                    $("#value_db").val("There is no value");
                                } else {
                                    $("#value_db").val(value_db);
                                }
                            }
                        });
                    } else {
                        $("#value_db").val("There is no value");
                    }
                });
            }
        });    
        //
        $('#general_advisor').empty();
        $('#general_advisor').append($('#text_general_advisor').html());

        /* $("#advisorModal").modal('show'); */
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
            
            if ($(this).hasClass('conTooltip')){
                $(".button-advisor").tooltip('hide');
            }      

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

            /* $("#advisorModal").modal('show'); */
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

        /* Advisor para tablas */

        $('.show-table-advisor').on('click',function(){
            /* Se debe poner el id del boton de la siguiente manera: id="table_(poner aca el id de la tabla)" */
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
        });

        /* Advisor para titulos de tabla que no se seleccionan */
        $('.show-only-advisor').on('click',function(){
            /* Se debe poner el id del boton de la siguiente manera: id="table_(poner aca el id de la tabla)" */
            $("#text_tree").addClass("d-none");
            $("#value_db").val("");
            input_name = $(this).attr("id");
            search_advisor(input_name);
            input_id = "#advisor_" + input_name;
            input_ref = "#advisor_ref_" + input_name;

            $('#input_text_advisor').empty();
            $('#input_text_advisor').append($(input_id).html());

            $('#import_tab').remove();

            $("#advisorModalButton").modal('show');

            if(! $(input_ref).length){
                $("#advisor_footer").addClass("d-none");
            }else{
                $("#advisor_footer").removeClass("d-none");
            }
        });
    }

</script>
