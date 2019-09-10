<script type="text/javascript">
    $(document).ready(function() {

        //Fecha actual para comparar fechas de filtros
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var tod = year + "-" + month + "-" + day;

        var today = 0000 + "-" + 00 + "-" + 00;

        //Cargar valores en select de usuario de acuerdo a la compañia
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

        //Buscar proyectos de acuerdo a los filtros escogidos
        $('.find_projects').on('change', function() {
            var company = $('#company').val();
            var user = $('#userbycompany').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (new Date(end_date).getTime() - new Date(start_date).getTime() < 0) {
                document.getElementById('end_date').value = today;
                $('#end_date_lower_error').modal('show');
            } else if (new Date(tod).getTime() - new Date(start_date).getTime() < 0) {
                document.getElementById('start_date').value = today;
                $('#start_date_exceed_error').modal('show');
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
                        var  string_table ="";
                        $.each(data, function(index, value){
                          string_table = string_table.concat("<tr> <td><a href=\"{{ URL::route('listScenaryHome.edit' , "xxxxx") }}\">"+ value.nombre +"</a></td><td>"+ value.fecha +"</td><td>"+ value.descripcion +"</td></tr>");
                          string_table = string_table.replace("xxxxx",value.id);
                        });
                        $('#projects_id').html("<table class=\"table table-striped\"><thead><tr><th>Name</th><th>Date</th><th>Description</th></tr></thead>"+string_table+"</table>");
                        $("#projects_id").show();
                    });
            }
        });

    });
</script>