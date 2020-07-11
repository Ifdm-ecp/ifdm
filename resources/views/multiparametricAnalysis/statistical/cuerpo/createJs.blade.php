<script type="text/javascript">
    $(function() {
        $(".jquery-datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy"
        });
    });

    //alert de seccion statistical database incompleta
    $('.sectionDeactivated').click(function(){
         $('#sectionDeactivated').modal('show');
    });

    //validacion de campos
    $("#statistical").change(function(){
        validationFields();
    });



    //cargar campos al cargar pagina
    function cargarCamposDefault(){
        var basin = $('#selectBasin').val();
        $.get("{{url('fieldbybasin')}}", {
                basin: basin
            },
            function(data){
                $("#field").empty();
                $.each(data, function(index, value) {
                    $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#field").selectpicker('refresh');
                $('#field').selectpicker('val', false);
            });
    }

    //Cargar valores para select anidado de acuerdo a opcion escogida
    $("#selectBasin").change(function() {
        cargarCamposDefault();
    });


    //redireccionar Calculate o Save
    $('#run_calc').click(function(){
        $('input[name = calculate]').val(false);
    });

    $('#save_calc').click(function(){
        $('input[name = calculate]').val(false);
    });

    $('#calculate').click(function(){
        $('input[name = calculate]').val(true);        
    });


    function validationFields()
    {
        if (!$("#statistical").prop('checked')) {
            $('#selectBasin').attr('disabled', false);
            $("#selectBasin").selectpicker('refresh');
            $('#field').attr('disabled', false);
            $("#field").selectpicker('refresh');
        } else {
            $('#selectBasin').attr('disabled', true);
            $("#selectBasin").selectpicker('refresh');
            $('#field').attr('disabled', true);
            $("#field").selectpicker('refresh');
        }
    }
</script>