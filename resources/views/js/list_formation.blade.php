<script type="text/javascript">
//Mostrar mensaje modal para borrar una formacion. Se debe mandar el id de la formacion unido al formulario para saber cual borrar
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

        //Cambiar valores de select anidados de acuerdo a opcion escogida
        $('#field').prop('disabled', true);
        $('#formation').prop('disabled', true);
        $("#basin").change(function(e) {
            $('#field').prop('disabled', true);
            var basin = $('#basin').val();

            $.get("{{url('fieldbybasinselect')}}", {
                    basin: basin
                },
                function(data) {
                    $("#field").empty();
                    $("#field").selectpicker('refresh');
                    $.each(data, function(index, value) {
                        $('#field').prop('disabled', false);
                        $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field").selectpicker('refresh');
                    $('#field').selectpicker('val', '');
                });
        });


        $("#field").change(function(e) {
            $('#formation').prop('disabled', true);
            var field = $('#field').val();

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

        //Creacion de tabla para mostrar formacion escogida en el filtro
        $("#formation").change(function(e) {
            var formation = $('#formation').val();
            $.get("{{url('formationfield')}}", {
                    formation: formation
                },
                function(data) {
                    var k = "";
                    $.each(data, function(index, value) {
                        var aux = value.id;

                        k = k.concat("<tr> <td>" + value.nombre + "</td><td>  <form method=\"POST\" action=\"{{ URL::route('listFormationC.destroy', "xxx") }}\"   id=\"form" + aux + "\"><input name=\"_method\" type=\"hidden\" value=\"DELETE\"><input name=\"_token\" type=\"hidden\" value=\"<?php echo csrf_token() ?>\"> <div class=\"form-inline\"><a href=\"{{ URL::route('listFormationC.edit', "yyy") }}\" class=\"btn btn-warning\">Manage</a><button class=\"btn btn-danger\" type=\"button\" data-toggle=\"modal\" OnClick=\"javascript: Mostrar(" + aux + ");\">Delete</button></div></form></td></tr>");


                        k = k.replace("xxx", aux);
                        k = k.replace("yyy", aux);
                        k = k.replace("xyx", aux);

                    });
                    $('#table').html("<table class=\"table table-striped\"><tr><th>Name</th><th>Actions</th></tr>" + k + "</table>");
                });

        });

    });
</script>