<script type="text/javascript">
//Mostrar mensaje modal para borrar un intervalo. Se debe mandar el id del intervalo unido al formulario para saber cual borrar
    function Mostrar(a) {
        var aux = 'form#form' + a;

        $("#confirmDelete").modal('show');
        $('#confirmDelete').on('show.bs.modal', function(e) {
            $message = $(e.relatedTarget).attr('data-message');
            $(this).find('.modal-body p').text($message);
            $title = $(e.relatedTarget).attr('data-title');
            $(this).find('.modal-title').text($title);

            var form = $(e.relatedTarget).closest('form');
            $(this).find('.modal-footer #confirm').data('form', form);

        });

        $('#confirmDelete').find('.modal-footer #confirm').on('click', function() {
            $(aux).submit();
        });

    }

    $(document).ready(function() {
        $("#myModal").modal('show');

        //Carga de valores en select anidados de acuerdo a opcion escogida
        $("#field").change(function(e) {
            var field = $('#field').val();

            $.get("{{url('wellbyfield')}}", {
                    field: field
                },
                function(data) {
                    $("#well").empty();
                    $("#well").selectpicker('refresh');
                    $.each(data, function(index, value) {
                        $('#well').prop('disabled', false);
                        $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#well").selectpicker('refresh');
                    $('#well').selectpicker('val', '');
                });

            $.get("{{url('formationbyfield')}}", {
                    field: field
                },
                function(data) {
                    $("#formation").empty();
                    $("#formation").selectpicker('refresh');
                    $.each(data, function(index, value) {
                        $('#formation').prop('disabled', false);
                        $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#formation").selectpicker('refresh');
                    $('#formation').selectpicker('val', '');
                });
        });

        $("#formation").change(function(e) {
            $('#interval').prop('disabled', true);
            var formation = $('#formation').val();
            var well = $('#well').val();
            $.get("{{url('intervalformation')}}", {
                    formation: formation,
                    well: well
                },
                function(data) {
                    $("#interval").empty();
                    $("#interval").selectpicker('refresh');
                    $.each(data, function(index, value) {
                        $('#interval').prop('disabled', false);
                        $("#interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#interval").selectpicker('refresh');
                    $('#interval').selectpicker('val', '');
                });
        });


        //Creacion de tabla para mostrar intervalo escogido en filtro
        $("#interval").change(function(e) {
            var interval = $('#interval').val();
            $.get("{{url('intervalformation2')}}", {
                    interval: interval
                },
                function(data) {
                    var k = "";
                    $.each(data, function(index, value) {
                        var aux = value.id;

                        k = k.concat("<tr> <td>" + value.nombre + "</td><td>  <form method=\"POST\" action=\"{{ URL::route('listIntervalC.destroy', "xxx") }}\"   id=\"form" + aux + "\"><input name=\"_method\" type=\"hidden\" value=\"DELETE\"><input name=\"_token\" type=\"hidden\" value=\"<?php echo csrf_token() ?>\"> <div class=\"form-inline\"><a href=\"{{ URL::route('listIntervalC.edit', "yyy") }}\" class=\"btn btn-warning\">Manage</a><button class=\"btn btn-danger\" type=\"button\" data-toggle=\"modal\" OnClick=\"javascript: Mostrar(" + aux + ");\">Delete</button></div></form></td></tr>");


                        k = k.replace("xxx", aux);
                        k = k.replace("yyy", aux);
                        k = k.replace("xyx", aux);

                    });
                    $('#table').html("<table class=\"table table-striped\"><tr><th>Name</th><th>Actions</th></tr>" + k + "</table>");
                });
        });


    });
</script>