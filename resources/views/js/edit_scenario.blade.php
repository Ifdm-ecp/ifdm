<script type="text/javascript">
    $('#loading').show();
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
        /* Comparar fecha actual con fecha de escenario. En caso de que la fecha del escenario supere la actual mostrar error */
        $("#date").change(function(e) {
            var date1 = $('#date').val();

            if (new Date(tod).getTime() - new Date(date1).getTime() < 0) {
                document.getElementById('date').value = today;
                $('#datea').modal('show');
            }

        });

        function update_duplicate_modal() {
            var Tree = [];
            var getImportTree;
            var input_name;

            var type = $('#type').val();
            getImportTree = $.get("{{url('getDuplicateTree')}}", { type: type}, function (data) {
                Tree = data;
            });

            getImportTree.done(function () {
                $('#treeDuplicateScenary').html('');
                if (Tree.length > 0) {
                    $('#treeDuplicateScenary').tree(Tree);
                } else {
                    $('#treeDuplicateScenary').html('<center><h3>There are no scenarios of the selected type.</h3></center>');
                }
            });

            $('.s_scenary_mod').on('click', function(event) {
                if ($('#type').val() != null) {
                    $('#scenaryExtModal').modal('show');
                } else {
                    alert('Please select an type of scenary.');
                }
            });

            $(document).on('click','a[href="#link"]',function(){
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

                $('#scenaryExtModal').modal('hide');

            }); 
        }

        /* Cargar valores de select con informacion de BD */
        $("#myModal").modal('show');
        $(document).ready(function(){
            setTimeout(function() {
                $('#project').selectpicker('val', {{ $scenary->proyecto_id }} );
                setTimeout(function() {
                    $('#basin').selectpicker('val', {{ $scenary->cuenca_id }} );
                    setTimeout(function() {
                        $('#field').selectpicker('val', {{ $scenary->campo_id }} );
                        setTimeout(function() {
                            $('#well').selectpicker('val', {{ $scenary->pozo_id }} ).trigger('change');
                            setTimeout(function() {
                                $('#formation').selectpicker('val', "{{ $scenary->formacion_id }}" );
                                $('#formation_ipr').selectpicker('val', "" );
                                $('#formation_ipr').selectpicker('val', [ {{ $scenary->formacion_id }} ] );
                                setTimeout(function() {
                                    $('.selectpicker').selectpicker('refresh');
                                    $('#loading').hide();
                                }, 500);
                            }, 2000);
                        }, 200);
                    }, 200);
                }, 200);
            }, 100);
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

        $('#type option[value="{{ $scenary->tipo }}"]').prop('selected', true);

        /* Cargar valores de select anidados de acuerdo a opcion escogida */
        $("#basin").change(function(e) {
            var cuenca = $('#basin').val();

            $.get("{{url('campos')}}", {
                cuenca: cuenca
            }, function(data) {
                $("#field").empty();
                $("#well").empty();
                $("#formation").empty();
                $("#formation_ipr").empty();

                $.each(data, function(index, value) {
                    $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });

                $("#field").selectpicker('refresh');
                $('#field').selectpicker('val', '');

            });
        });

        $("#field").change(function(e) {

            var campo = $('#field').val();
            $.get("{{url('pozosF')}}", {
                campo: campo
            }, function(data) {
                $("#well").empty();
                $("#formation").empty();
                $("#formation_ipr").empty();
                $.each(data, function(index, value) {
                    $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#well").selectpicker('refresh');
                $("#formation").selectpicker('refresh');
                $("#formation_ipr").selectpicker('refresh');
                $('#well').selectpicker('val', '');

            });
        });

        $("#well").change(function(e) {
            // console.log(pozo);
            var pozo = $('#well').val();
            $.get("{{url('formacionW')}}", {
                pozo: pozo
            }, function(data) {
                $("#formation").empty();
                $("#formation_ipr").empty();

                $.each(data, function(index, value) {
                    $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    $("#formation_ipr").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });

                $("#formation").selectpicker('refresh');
                $('#formation').selectpicker('val', '');

                $("#formation_ipr").selectpicker('refresh');
                $('#formation_ipr').selectpicker('val', '');
            });
        });


        $("#type").change(function(e) {
            var type = $("#type").val();
            if (type == "Asphaltene precipitation") {
                $("#aspahltene_type").modal('show');

                $("#asphaltene_type_button").show();
                $("#asphaltene_rem_button").hide();

                $("#MultiparametricModal").modal('hide');
                $("#multiparametric_type_button").hide();

            } else if (type == "Asphaltene remediation") {
                $("#aspahltene_remediation_m").modal('show');

                $("#asphaltene_type_button").hide();
                $("#asphaltene_rem_button").show();

                $("#MultiparametricModal").modal('hide'); 
                $("#multiparametric_type_button").hide(); 

            } else if(type == "Multiparametric"){
                $("#aspahltene_type").modal('hide');

                $("#asphaltene_type_button").hide();
                $("#asphaltene_rem_button").hide();

                $("#multiparametric_type_button").show();
                $("#MultiparametricModal").modal('show');
            }else {
                $("#aspahltene_type").modal('hide');

                $("#asphaltene_type_button").hide();
                $("#asphaltene_rem_button").hide();

                $("#MultiparametricModal").modal('hide');
                $("#multiparametric_type_button").hide();
            }

            if (type == "IPR") {                
                $('#div_formation_ipr').show();
                $('#div_formation_wipr').hide();
            } else {
                $('#div_formation_wipr').show();
                $('#div_formation_ipr').hide();
            }

            update_duplicate_modal();
        });

        setTimeout(function() {
            $("#type").change();
        }, 500);

        $('.asphaltene_type_ev').on('click', function() {
            $("#aspahltene_type").modal('show');
        });

        $('.asphaltene_rem_ev').on('click', function() {
            $("#aspahltene_remediation_m").modal('show');
        });

        $('.multiparametric_type_ev').on('click', function() {
            $("#MultiparametricModal").modal('show');
        });
    });

</script>