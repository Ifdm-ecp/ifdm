<script type="text/javascript">
    //Muestra modal de advertencia antes de pasar al controlador y destruir el objeto. 
    function Mostrar(a) 
    {
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
        $('#field').prop('disabled', true);
        $('#formation').prop('disabled', true);

        //Cambio de selector de cuenca
        $("#basin").change(function(e) 
        {
            $('#field').prop('disabled', true);
            $('#formation').prop('disabled', true);
            var basin = $('#basin').val();
            $.get("{{url('campos')}}", {
                    cuenca: basin
                },
                function(data) {
                    $("#field").empty();
                    $('#field').prop('disabled', false);
                    $.each(data, function(index, value) {
                        $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field").selectpicker('refresh');
                    $('#field').selectpicker('val', '');
                });
        });

        //Cambio de selector de campo
        $("#field").change(function(e) 
        {
            $('#formation').prop('disabled', true);
            var field = $('#field').val();
            $.get("{{url('formations_by_field')}}", {
                    field: field
                },
                function(data) {
                    $("#formation").empty();
                    $('#formation').prop('disabled', false);
                    $.each(data, function(index, value) {
                        $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#formation").selectpicker('refresh');
                    $('#formation').selectpicker('val', '');
                });
        });

        //Creacion de tabla con pozo escogido en los filtros
        $("#formation").change(function(e) {
            var formation_id = $('#formation').val();
            $.get("{{url('filtration_functions_by_formation_id')}}", {
                    formation_id: formation_id
                },
                function(data) {
                    var k = "";
                    $.each(data, function(index, value) {
                        var aux = value.id;

                        k = k.concat("<tr> <td>" + value.name + "</td><td>  <form method=\"POST\" action=\"{{ URL::route('filtration_function.destroy', "xxx") }}\"   id=\"form" + aux + "\"><input name=\"_method\" type=\"hidden\" value=\"DELETE\"><input name=\"_token\" type=\"hidden\" value=\"<?php echo csrf_token() ?>\"> <div class=\"form-inline\"><a href=\"{{ URL::route('filtration_function.edit', "yyy") }}\" class=\"btn btn-warning\">Manage</a><button class=\"btn btn-danger\" type=\"button\" data-toggle=\"modal\" OnClick=\"javascript: Mostrar(" + aux + ");\">Delete</button></div></form></td></tr>");


                        k = k.replace("xxx", aux);
                        k = k.replace("yyy", aux);
                        k = k.replace("xyx", aux);

                    });
                    $('#table').html("<table class=\"table table-striped\"><tr><th>Filtration Function</th><th>Options</th></tr>" + k + "</table>");
                });

        });

    });
</script>