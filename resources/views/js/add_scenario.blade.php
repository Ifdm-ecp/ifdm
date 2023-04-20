<script type="text/javascript">

    $(document).ready(function(){
        $('.s_scenary_mod').on('click', function(event) {
            if ($('#type').val() != null) {
                setTimeout(function() {
                    update_duplicate_modal();
                    $('#scenaryExtModal').modal('show');
                }, 100);
            } else {
                alert('Please select an type of scenary.');
            }
        });

        $('.s_scenary_clear').on('click', function(event) {
            $('#type').change();
        });

        $('.link_external_tree').off();
    });

    /* Guardar valores del formulario para volver a cargar en caso de que salga error en valores */
    window.onbeforeunload = function() {
        var a = $('#well').val();
        localStorage.setItem('well', $('#well').val());
        var b = $('#field').val();
        localStorage.setItem('field', $('#field').val());
        var c = $('#formation').val();
        var c2 = $('#formation_ipr').val();
        var c3 = $('#formation_multiparametric_statistical').val();
        localStorage.setItem('formation', $('#formation').val());
        localStorage.setItem('formation_ipr', $('#formation_ipr').val());
        localStorage.setItem('formation_multiparametric_statistical', $('#formation_multiparametric_statistical').val());
    }

    $('#btn_sub_escenario').on('click', function (e) {
        e.preventDefault();

        $('#loading').show();

        $('#basin').removeAttr('disabled');
        $('#field').removeAttr('disabled');
        $('#well').removeAttr('disabled');
        $('#formation_ipr').removeAttr('disabled');
        $('.selectpicker').selectpicker('refresh');

        setTimeout(function() {
            $('#scenary_form').submit();
        }, 500);
    });

    /* ocultar el advisor si es Multiparametric o drilling el type de escenario */
    $('#type').change(function(){
        var type =  $('#type').val();
        if(type == 'Multiparametric' || type == 'Drilling') {
            $('#div_advisor').hide();
        } else {
            $('#div_advisor').show();
        }

        $('#basin').removeAttr('disabled');
        $('#field').removeAttr('disabled');
        $('#well').removeAttr('disabled');
        $('#formation_ipr').removeAttr('disabled');

        $('.selectpicker').selectpicker('refresh');

        update_duplicate_modal();

        $('#escenario_dup').val('');
        $('#id_escenario_dup').val('');
        $('.s_scenary_clear').attr('disabled','true');
    });

    /* Ocultar el selector del intervalo productor si se selecciona estadístico en multiparamétrico */
    $('[name=multiparametricType]').change(function(){
        var sub_type = $('[name=multiparametricType]').val();

        if (sub_type == 'statistical') {
            // $('#div_formation_wipr').hide();
            $('#div_formation_multiparametric_statistical').show();
            $('#div_formation_wipr').hide();
        } else {
            $('#div_formation_multiparametric_statistical').show();
            $('#div_formation_wipr').hide();
        }
    });

    function update_duplicate_modal() {
        var Tree = [];
        var getImportTree;
        var input_name;

        var type = $('#type').val();
        var sub_type = '';
        if (type == 'Multiparametric') {
            sub_type = $('[name=multiparametricType]').val();
        } else if (type == 'Asphaltene precipitation') {
            sub_type = $('#asphaltene_type').val();
        } else if (type == 'Asphaltene remediation') {
            sub_type = $('#asphaltene_remediation').val();
        }

        getImportTree = $.get("{{url('getDuplicateTree')}}", { type: type, sub_type: sub_type}, function (data) {
            Tree = data;
        });

        $('.link_external_tree').off();

        getImportTree.done(function () {
            $('#treeDuplicateScenary').html('');
            if (Tree.length > 0) {
                $('#treeDuplicateScenary').tree(Tree);
                setTimeout(function() {
                    $('.link_external_tree').on('click', function(){
                        $('#scenaryExtModal').modal('hide');
                        $('#loading').show();
                        console.log('se eejecutan ya las funciones');
                        var var_href = $(this).attr('href');
                        var sel;

                        if(var_href == "#link"){
                            sel = Tree;
                        }

                        var pos = $(this).find('span').attr('id');
                        pos = pos.split("_");

                        for (var i = 1 ; i <= pos.length - 1 ; i++){
                            sel = sel[pos[i]];
                            var k = i + 1;
                            if (k <= pos.length-1){
                                sel = sel['child'];
                            }
                        }
                        $('#escenario_dup').val(sel.nombre);
                        $('#id_escenario_dup').val(sel.id);
                        $('.s_scenary_clear').removeAttr('disabled');

                        var escenario = [];
                        var getImportScenario = $.get("{{url('getScenarioInfo')}}", { scenario: sel.id}, function (data) {
                            escenario = data;
                        });

                        getImportScenario.done(function () {

                            setTimeout(function() {

                                /* Cuenca */
                                $('#basin').selectpicker('val', escenario.cuenca_id);
                                $("#basin").selectpicker('refresh');
                                $("#basin").change();

                                setTimeout(function() {
                                    /* Cuenca */
                                    $('#field').selectpicker('val', escenario.campo_id);
                                    $("#field").selectpicker('refresh');
                                    $("#field").change();

                                    setTimeout(function() {
                                        /* Cuenca */
                                        $('#well').selectpicker('val', escenario.pozo_id);
                                        $("#well").selectpicker('refresh');
                                        $("#well").change();

                                        setTimeout(function() {
                                            /* Formaciones */
                                            var formaciones = escenario.formacion_id.split(',');
                                            for (var i = 0; i < formaciones.length; i++) {
                                                formaciones[i] = parseInt(formaciones[i]);
                                            }

                                            $('#formation_ipr').selectpicker('val', formaciones);
                                            $("#formation_ipr").selectpicker('refresh');
                                            $("#formation_ipr").change();

                                            if (type == 'IPR') {
                                                $('#basin').attr('disabled','disabled');
                                                $('#field').attr('disabled','disabled');
                                                $('#well').attr('disabled','disabled');
                                                $('#formation_ipr').attr('disabled','disabled');
                                            }

                                            $(".selectpicker").selectpicker('refresh');

                                            $('#loading').hide();
                                            $('.link_external_tree').off();
                                        }, 500);

                                    }, 500);

                                }, 500);

                            }, 500);
                        });
                    }); 
                }, 1000);
} else {
    $('#treeDuplicateScenary').html('<center><h3>There are no scenarios of the selected type.</h3></center>');
}
});
}

/* Cargar valores de formulario en caso de que salga error */
window.onload = function() {
    var basin = $('#basin').val();
    var field = {{ count($errors) > 0 && old('field') ? old('field') : json_encode(null) }};
    $.get("{{url('fieldbybasinselect')}}", {
        basin: basin
    }, function(data) {
        $("#field").empty();
        $.each(data, function(index, value) {
            $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
        });
        var k = '#field > option[value="{{ 'xxx'}}"]';
        k = k.replace('xxx', field);
        $(k).attr('selected', 'selected');
        $("#field").selectpicker('refresh');

    });

    var pozo = {{ count($errors) > 0 && old('well') ? old('well') : json_encode(null) }};
    $.get("{{url('fields')}}", {
        field: field
    }, function(data) {
        $("#well").empty();
        $.each(data, function(index, value) {
            $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
        });
        var k = '#well > option[value="{{ 'xxx'}}"]';
        k = k.replace('xxx', pozo);
        $(k).attr('selected', 'selected');
        $("#well").selectpicker('refresh');

    });

    var well = {{ count($errors) > 0 && old('well') ? old('well') : json_encode(null) }};
    var formation = {{ count($errors) > 0 && old('formation') ? old('formation') : json_encode(null) }};
    var formation_ipr = {{ count($errors) > 0 && old('formation_ipr') ? old('formation_ipr') : json_encode(null) }};
    var formation_multiparametric_statistical = {{ count($errors) > 0 && old('formation_multiparametric_statistical') ? old('formation_multiparametric_statistical') : json_encode(null) }};
    $.get("{{url('formacionW')}}", {
        pozo: well
    }, function(data) {
        $("#formation").empty();
        $("#formation_ipr").empty();
        $("#formation_multiparametric_statistical").empty();
        $.each(data, function(index, value) {
            $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            $("#formation_ipr").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            $("#formation_multiparametric_statistical").append('<option value="' + value.id + '">' + value.nombre + '</option>');
        });

        var k = '#formation > option[value="{{ 'xxx'}}"]';
        var k2 = '#formation_ipr > option[value="{{ 'xxx'}}"]';
        var k3 = '#formation_multiparametric_statistical > option[value="{{ 'xxx'}}"]';

        k = k.replace('xxx', formation);
        k2 = k2.replace('xxx', formation_ipr);
        k3 = k3.replace('xxx', formation_multiparametric_statistical);

        $(k).attr('selected', 'selected');
        $(k2).attr('selected', 'selected');
        $("#formation").selectpicker('refresh');
        $("#formation_ipr").selectpicker('refresh');
        $("#formation_multiparametric_statistical").selectpicker('refresh');

    });
}

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();   
    /* Fecha actual */
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;
    var tod = year + "-" + month + "-" + day;

    var today =  "0000-00-00";

    /* Comparar y validar fecha seleccionada con la fecha actual */
    $("#date").change(function(e) {
        var date1 = $('#date').val();

        if (new Date(tod).getTime() - new Date(date1).getTime() < 0) {
            document.getElementById('date').value = today;
            $('#datea').modal('show');
        }
    });


    /* Cargar valores de select anidado al cambiar valores */
    $("#myModal").modal('show');
    $("#basin").change(function(e) {
        var cuenca = $('#basin').val();
        $.get("{{url('campos')}}", {
            cuenca: cuenca
        }, function(data) {
            $("#field").empty();
            $("#well").empty();
            $("#formation").empty();
            $("#formation_ipr").empty();
            $("#formation_multiparametric_statistical").empty();
            $.each(data, function(index, value) {
                $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            $("#field").selectpicker('refresh');
            $('#field').selectpicker('val', '');
            $("#well").selectpicker('refresh');
            $("#formation_multiparametric_statistical").selectpicker('refresh');
        });
    });

    var type = $("#type").val();
    if (type == "Asphaltene precipitation") {

        $("#asphaltene_type_button").show();

    } else if (type == "Asphaltene remediation") {

        $("#asphaltene_rem_button").hide();

    } else {

        $("#asphaltene_type_button").hide();
        $("#asphaltene_rem_button").hide();

    }

    $("#type").change(function(e) {
        var type = $("#type").val();
        if (type == "Asphaltene precipitation") {
            $("#aspahltene_type").modal('show');

            $("#asphaltene_type_button").show();
            $("#asphaltene_rem_button").hide();

            $("#MultiparametricModal").modal('hide');
            $("#multiparametric_type_button").hide();

        } else if (type == "Asphaltene remediation") {
            /* $("#aspahltene_remediation_m").modal('show'); */

            $("#asphaltene_type_button").hide();
            $("#asphaltene_rem_button").show();

            $("#MultiparametricModal").modal('hide'); 
            $("#multiparametric_type_button").hide(); 

        } else if (type == "Multiparametric") {

            $("#aspahltene_type").modal('hide');

            $("#asphaltene_type_button").hide();
            $("#asphaltene_rem_button").hide();

            $("#multiparametric_type_button").show();
            $("#MultiparametricModal").modal('show');
        } else {
            $("#aspahltene_type").modal('hide');

            $("#asphaltene_type_button").hide();
            $("#asphaltene_rem_button").hide();

            $("#MultiparametricModal").modal('hide');
            $("#multiparametric_type_button").hide();
        }

        if (type == "IPR") {                
            $('#div_formation_ipr').show();
            $('#div_formation_wipr').hide();
            $('#div_formation_multiparametric_statistical').hide();

        } else {
            $('#div_formation_wipr').show();
            $('#div_formation_ipr').hide();
            $('#div_formation_multiparametric_statistical').hide();

            $('#basin').removeAttr('disabled');
            $('#field').removeAttr('disabled');
            $('#well').removeAttr('disabled');
            $('#formation_ipr').removeAttr('disabled');

        }

        if (type == "Drilling") {
            $('#div_formation_wipr').hide();
            $('#div_formation_multiparametric_statistical').hide();
            /*$("#div_Dformation").show();*/
        } else {
            $("#div_Dformation").hide();
            $('#div_formation_multiparametric_statistical').hide();
        }

        if (type == "Multiparametric") {
            var sub_type = $('[name=multiparametricType]').val();

            if (sub_type == 'statistical') {
                // $('#div_formation_wipr').hide();
                $('#div_formation_multiparametric_statistical').show();
                $('#div_formation_wipr').hide();
                $('#div_formation_ipr').hide();
            } else {
                $('#div_formation_multiparametric_statistical').show();
                $('#div_formation_wipr').hide();
                $('#div_formation_ipr').hide();
            }
        }

        $('.selectpicker').selectpicker('refresh');

        update_duplicate_modal();
    });

    $('.asphaltene_type_ev').on('click', function() {
        $("#aspahltene_type").modal('show');
    });

    $('.asphaltene_rem_ev').on('click', function() {
        /*$("#aspahltene_remediation_m").modal('show');*/
    });

    $('.multiparametric_type_ev').on('click', function() { 
        $("#MultiparametricModal").modal('show');

        var sub_type = $('[name=multiparametricType]').val();

        if (sub_type == 'statistical') {
            // $('#div_formation_wipr').hide();
            $('#div_formation_multiparametric_statistical').show();
            $('#div_formation_wipr').hide();
        } else {
            $('#div_formation_multiparametric_statistical').show();
            $('#div_formation_wipr').hide();
        }
    });

    $("#field").change(function(e) {

        var campo = $('#field').val();
        $.get("{{url('pozosF')}}", {
            campo: campo
        },
        function(data) {
            $("#well").empty();
            $("#formation").empty();
            $("#formation_ipr").empty();
            $("#formation_multiparametric_statistical").empty();

            $.each(data, function(index, value) {
                $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
            });
            $("#well").selectpicker('refresh');
            $('#well').selectpicker('val', '');
        }); 
        
        if ($("#type").val() == "Multiparametric" && $("#multiparametricType").val() == "statistical") {
            $.get("{{url('formationbyfield')}}", {
                field: campo
            },
            function(data) {
                $.each(data, function(index, value) {
                    $("#formation_multiparametric_statistical").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#formation_multiparametric_statistical").selectpicker('refresh');
                $('#formation_multiparametric_statistical').selectpicker('val', '');
                $('#formation_multiparametric_statistical').selectpicker('destroy');
                $('#formation_multiparametric_statistical').selectpicker('render');
                $("#formation_multiparametric_statistical").selectpicker('refresh');
            });
        }
       
    });

    $("#well").change(function(e) {
        var pozo = $('#well').val();
        $.get("{{url('formacionW')}}", {
            pozo: pozo
        },
        function(data) {
            $("#formation").empty();
            $("#formation_ipr").empty();
            if (data.length === 0) {
                $("#formation").append('<option value="4657">B2</option>');
                $("#formation_ipr").append('<option value="4657">B2</option>');
            } else {
                $.each(data, function(index, value) {
                    $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    $("#formation_ipr").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
            }
            $("#formation").selectpicker('refresh');
            $('#formation').selectpicker('val', '');

            $("#formation_ipr").selectpicker('refresh');
            $('#formation_ipr').selectpicker('val', '');
        });
    });

    $("#copy").change(function(e) {
        if ($("#copy").attr('checked', true)) {
            var type = $("#type").val();
            $.get("{{url('select_copy_scenario')}}", {
                type: type
            },
            function(data) {

                $.each(data, function(index, value) {

                });
            });

        } else {
            alert("b");
        }

    });
});

</script>