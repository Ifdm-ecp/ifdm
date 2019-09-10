<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", ".share-button", function () {
            // Consultar usuarios y volverlos a cargar segun sea el caso
            var scenario_id = $(this).data('id');
            $("#scenario_t").val(scenario_id);
            $.get("{{ url('getUsersToShare') }}", function (data) {
                $.get("{{ url('getSharedUsers') }}", {scenario_id: scenario_id}, function (data2) {
                    $("#shared_users_s").empty();
                    for (var i = 0; i < data.length; i++) {
                        var aux = false;
                        for (var j = 0; j < data2.length; j++) {
                            if (data[i].fullName == data2[j].fullName) {
                                aux = true;
                                break;
                            }
                        }
                        if (aux) {
                            $("#shared_users_s").append('<option selected value="' + data[i].id + '">' + data[i].fullName + '</option>');
                        } else {
                            $("#shared_users_s").append('<option value="' + data[i].id + '">' + data[i].fullName + '</option>');
                        }
                        $("#shared_users_s").selectpicker('refresh');
                    }
                });
            });
        });


        //Cargar los datos del escenario para editarlo
        $(document).on("click", ".edit-button", function () {
            var scenario_id = $(this).data('id');
            $("#id_scenario").val(scenario_id);
            $.get("{!! url('getScenario') !!}", {
                scenario_id: scenario_id
            }, function (data) {
                $('#name_scenario').val(data[0]['nombre']);
                $('#description_scenario').val(data[0]['descripcion']);
            });
        });

    });
</script>