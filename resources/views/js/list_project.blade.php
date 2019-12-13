<script type="text/javascript">
//Mostrar mensaje modal para borrar un proyecto. Se debe mandar el id del proyecto unido al formulario para saber cual borrar
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
        //Fecha actual
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var tod = year + "-" + month + "-" + day;

        var today = 0000 + "-" + 00 + "-" + 00;

        //Carga de valores en select anidados de acuerdo a opcion escogida
        $("#company").change(function(e) {
            var company = $('#company').val();
            $('#userbycompany').selectpicker('val', '');
            $.get("{{url('userbycompany')}}", {
                    company: company
                },
                function(data) {
                    $("#userbycompany").empty();
                    $("#userbycompany").selectpicker('refresh');
                    $("#userbycompany").append('<option value=""></option>');
                    $.each(data, function(index, value) {
                        $("#userbycompany").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    $("#userbycompany").selectpicker('refresh');
                    $('#userbycompany').selectpicker('val', '');
                });
        });


        //Buscar proyectos de acuerdo a valores en los filtros
        $('.find_projects').on('change', function() {
            var company = $('#company').val();
            var user = $('#userbycompany').val();
            var start_date = $('#dateS').val();
            var end_date = $('#dateF').val();
            if (new Date(end_date).getTime() - new Date(start_date).getTime() < 0) {
                document.getElementById('dateF').value = today;
                $('#endDateS').modal('show');
            } else if (new Date(tod).getTime() - new Date(start_date).getTime() < 0) {
                document.getElementById('dateS').value = today;
                $('#startDate').modal('show');
            } else {

                $("#UN").hide();
                $("#equion").hide();
                $("#ecopetrol").hide();
                $("#hocol").hide();
                $("#uis").hide();
                $.get("{{url('projects')}}", {
                        company: company,
                        user: user,
                        start_date: start_date,
                        end_date: end_date
                    },
                    function(data) {
                        var k = "";
                        $.each(data, function(index, value) {
                            var aux = value.id;
                            var currentString = "<tr> <td>" + value.nombre + "</td><td>" + value.fecha + "</td><td>  <form method=\"POST\" action=\"{{ URL::route('DeleteProject.destroy', "yyy") }}\"   id=\"form" + aux + "\"><input name=\"_method\" type=\"hidden\" value=\"DELETE\"><input name=\"_token\" type=\"hidden\" value=\"<?php echo csrf_token() ?>\"> <div class=\"form-inline\"><a href=\"{{ URL::route('DeleteProject.edit', "yyy") }}\" class=\"btn btn-info\">View</a> <a href=\"{{ URL::route('ProjectC.edit', "yyy") }}\" class=\"btn btn-warning\">Manage</a> <button class=\"btn btn-danger\" type=\"button\" data-toggle=\"modal\" OnClick=\"javascript: Mostrar(" + aux + ");\">Delete</button></div></form></td></tr>"
                            currentString = currentString.replace(/yyy/g, aux);
                            k += currentString;
                        });
                        
                        $('#proyectos').html("<table class=\"table table-striped\"><thead><tr><th>Name</th><th>Date</th><th>Actions</th></tr></thead>" + k + "</table>");
                        $("#proyectos").show();
                    });
            }

        });
    });
</script>