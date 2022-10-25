<script type="text/javascript">

    //Guardar valores de select para cargar cuando salga error en el formulario.
    window.onbeforeunload = function() {

        var c = $('#basin').val();
        localStorage.setItem('basin', $('#basin').val());
        var a = $('#well').val();
        localStorage.setItem('well', $('#well').val());
        var b = $('#field').val();
        localStorage.setItem('field', $('#field').val());
        var c = $('#formation').val();
        localStorage.setItem('formation', $('#formation').val());
        
    }

    //Volver a cargar valores de select anidados cuando salga ventana modal de error.
    window.onload = function() {
        $("#field").empty();
        $("#well").empty();
        $("#formation").empty();

        var basin = localStorage.getItem('basin');
        var well = localStorage.getItem('well');
        var field = localStorage.getItem('field');
        var formation = localStorage.getItem('formation');

        $("#basin").val(basin).change();

        $.get("{{url('fieldbybasinselect')}}", {
                basin: basin
            },
            function(data) {
                $("#field").empty();
                $.each(data, function(index, value) {
                    $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#field > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', field);
                $(k).attr('selected', 'selected');
                $("#field").selectpicker('refresh');
            }
        );

        $.get("{{url('wellbyfield')}}", {
                field: field
            },
            function(data) {
                $("#well").empty();
                $.each(data, function(index, value) {
                    $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#well > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', well);
                $(k).attr('selected', 'selected');
                $("#well").selectpicker('refresh');
            }
        );

        $.get("{{url('formacionbyfield')}}", {
                field: field
            },
            function(data) {
                $("#formation").empty();
                $.each(data, function(index, value) {
                    $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#formation > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', formation);
                $(k).attr('selected', 'selected');
                $("#formation").selectpicker('refresh');
            }
        );
    }

    $(function() {
        $(".jquery-datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy"
        });
        
        $("#myModal").modal('show');

        $("#basinSpreadsheet").change(function(e) {
            var basin = $('#basinSpreadsheet').val();
            $.get("{{url('fieldbybasin')}}", {
                    basin: basin
                },
                function(data) {
                    $("#fieldSpreadsheet").empty();

                    $.each(data, function(index, value) {
                        $("#fieldSpreadsheet").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#fieldSpreadsheet").selectpicker('refresh');
                    $('#fieldSpreadsheet').selectpicker('val', '');
                });
        });

        //Valores de select anidados, cargar datos de acuerdo a opcion escogida
        $("#basin").change(function(e) {
            var basin = $('#basin').val();
            $.get("{{url('fieldbybasin')}}", {
                    basin: basin
                },
                function(data) {
                    $("#well").empty();
                    $("#field").empty();
                    $("#formation").empty();

                    $.each(data, function(index, value) {
                        $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field").selectpicker('refresh');
                    $('#field').selectpicker('val', '');
                });
                $("#well").empty();
                $("#formation").empty();
                $("#well").selectpicker('refresh');
                $("#well").selectpicker('val', '');
                $("#formation").selectpicker('refresh');
                $("#formation").selectpicker('val', '');
        });

        $("#field").change(function(e) {
            var field = $('#field').val();
            $.get("{{url('wellbyfield')}}", {
                    field: field
            },
            function(data) {
                $("#well").empty();
                $.each(data, function(index, value) {
                    $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#well").selectpicker('refresh');
                $('#well').selectpicker('val', '');
            });
            $.get("{{url('formacionbyfield')}}", {
                    field: field
            },
            function(data) {
                $("#formation").empty();
                $.each(data, function(index, value) {
                    $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#formation").selectpicker('refresh');
                $('#formation').selectpicker('val', '');
            });
        });

    });

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
    }
</script>