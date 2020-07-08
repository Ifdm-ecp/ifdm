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
    $('#save').click(function(){
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

    /* tabStep
    * After validating the current tab, it is changed to the next or previous tab depending on the
    * entry value
    * params {direction: string}
    */
    function tabStep(direction) {
        var tabToValidate = $(".nav.nav-tabs li.active a").attr("id");

        if (direction == "prev") {
            $(".nav.nav-tabs li.active").prev().children().click();
        } else {
            $(".nav.nav-tabs li.active").next().children().click();
        }

        $("#next_button").toggle($(".nav.nav-tabs li.active").next().is("li"));
        $("#prev_button").toggle($(".nav.nav-tabs li.active").prev().is("li"));
        $("#run_calc").toggle(!$(".nav.nav-tabs li.active").next().is("li"));
    }

    /* switchTab
    * Captures the tab clicking event to determine if a previous or next button has to be shown
    * and also the run button
    */
    function switchTab() {
        var event = window.event || arguments.callee.caller.arguments[0];
        var tabActiveElement = $(".nav.nav-tabs li.active");
        var nextPrevElement = $("#" + $(event.srcElement || event.originalTarget).attr('id')).parent();

        $("#next_button").toggle(nextPrevElement.next().is("li"));
        $("#prev_button").toggle(nextPrevElement.prev().is("li"));
        $("#run_calc").toggle(!nextPrevElement.next().is("li"));
    }
</script>