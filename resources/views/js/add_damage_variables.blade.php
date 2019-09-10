<script type="text/javascript">

    //Guardar valores de select para cargar cuando salga error en el formulario.
    window.onbeforeunload = function() {
        var a = $('#well').val();
        localStorage.setItem('well', $('#well').val());
        var b = $('#field').val();
        localStorage.setItem('field', $('#field').val());
    }

    //Volver a cargar valores de select anidados cuando salga ventana modal de error.
    window.onload = function() {
        var basin = $('#basin').val();
        var field = localStorage.getItem('field');
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

        var field = localStorage.getItem('field');
        var pozo = localStorage.getItem('well');
        $.get("{{url('fields')}}", {
                field: field
            },
            function(data) {
                $("#well").empty();
                $.each(data, function(index, value) {
                    $("#well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#well > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', pozo);
                $(k).attr('selected', 'selected');
                $("#well").selectpicker('refresh');
            }
        );
    }


    $(function() {
        //Fecha actual
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var tod = year + "-" + month + "-" + day;

        var today = 0000 + "-" + 00 + "-" + 00;

        //Comparar fecha actual con fechas de SP, en caso de que sea mayor a la fecha actual muestra ventana de error
        $("#dateMS1").change(function(e) {
            var dateMS1 = $('#dateMS1').val();

            if (new Date(tod).getTime() - new Date(dateMS1).getTime() < 0) {
                document.getElementById('dateMS1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS2").change(function(e) {
            var dateMS2 = $('#dateMS2').val();

            if (new Date(tod).getTime() - new Date(dateMS2).getTime() < 0) {
                document.getElementById('dateMS2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS3").change(function(e) {
            var dateMS3 = $('#dateMS3').val();

            if (new Date(tod).getTime() - new Date(dateMS3).getTime() < 0) {
                document.getElementById('dateMS3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS4").change(function(e) {
            var dateMS4 = $('#dateMS4').val();

            if (new Date(tod).getTime() - new Date(dateMS4).getTime() < 0) {
                document.getElementById('dateMS4').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateMS5").change(function(e) {
            var dateMS5 = $('#dateMS5').val();

            if (new Date(tod).getTime() - new Date(dateMS5).getTime() < 0) {
                document.getElementById('dateMS5').value = today;
                $('#date').modal('show');
            }
        });


        $("#dateFB1").change(function(e) {
            var dateFB1 = $('#dateFB1').val();

            if (new Date(tod).getTime() - new Date(dateFB1).getTime() < 0) {
                document.getElementById('dateFB1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB2").change(function(e) {
            var dateFB2 = $('#dateFB2').val();

            if (new Date(tod).getTime() - new Date(dateFB2).getTime() < 0) {
                document.getElementById('dateFB2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB3").change(function(e) {
            var dateFB3 = $('#dateFB3').val();

            if (new Date(tod).getTime() - new Date(dateFB3).getTime() < 0) {
                document.getElementById('dateFB3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB4").change(function(e) {
            var dateFB4 = $('#dateFB4').val();

            if (new Date(tod).getTime() - new Date(dateFB4).getTime() < 0) {
                document.getElementById('dateFB4').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateFB5").change(function(e) {
            var dateFB5 = $('#dateFB5').val();

            if (new Date(tod).getTime() - new Date(dateFB5).getTime() < 0) {
                document.getElementById('dateFB5').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateOS1").change(function(e) {
            var dateOS1 = $('#dateOS1').val();

            if (new Date(tod).getTime() - new Date(dateOS1).getTime() < 0) {
                document.getElementById('dateOS1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateOS2").change(function(e) {
            var dateOS2 = $('#dateOS2').val();

            if (new Date(tod).getTime() - new Date(dateOS2).getTime() < 0) {
                document.getElementById('dateOS2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateOS3").change(function(e) {
            var dateOS3 = $('#dateOS3').val();

            if (new Date(tod).getTime() - new Date(dateOS3).getTime() < 0) {
                document.getElementById('dateOS3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateOS4").change(function(e) {
            var dateOS4 = $('#dateOS4').val();

            if (new Date(tod).getTime() - new Date(dateOS4).getTime() < 0) {
                document.getElementById('dateOS4').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateRP1").change(function(e) {
            var dateRP1 = $('#dateRP1').val();

            if (new Date(tod).getTime() - new Date(dateRP1).getTime() < 0) {
                document.getElementById('dateRP1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateRP2").change(function(e) {
            var dateRP2 = $('#dateRP2').val();

            if (new Date(tod).getTime() - new Date(dateRP2).getTime() < 0) {
                document.getElementById('dateRP2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateRP3").change(function(e) {
            var dateRP3 = $('#dateRP3').val();

            if (new Date(tod).getTime() - new Date(dateRP3).getTime() < 0) {
                document.getElementById('dateRP3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateRP4").change(function(e) {
            var dateRP4 = $('#dateRP4').val();

            if (new Date(tod).getTime() - new Date(dateRP4).getTime() < 0) {
                document.getElementById('dateRP4').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateID1").change(function(e) {
            var dateID1 = $('#dateID1').val();

            if (new Date(tod).getTime() - new Date(dateID1).getTime() < 0) {
                document.getElementById('dateID1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateID2").change(function(e) {
            var dateID2 = $('#dateID2').val();

            if (new Date(tod).getTime() - new Date(dateID2).getTime() < 0) {
                document.getElementById('dateID2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateID3").change(function(e) {
            var dateID3 = $('#dateID3').val();

            if (new Date(tod).getTime() - new Date(dateID3).getTime() < 0) {
                document.getElementById('dateID3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateID4").change(function(e) {
            var dateID4 = $('#dateID4').val();

            if (new Date(tod).getTime() - new Date(dateID4).getTime() < 0) {
                document.getElementById('dateID4').value = today;
                $('#date').modal('show');
            }
        });

        $("#dateGD1").change(function(e) {
            var dateGD1 = $('#dateGD1').val();

            if (new Date(tod).getTime() - new Date(dateGD1).getTime() < 0) {
                document.getElementById('dateGD1').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateGD2").change(function(e) {
            var dateGD2 = $('#dateGD2').val();

            if (new Date(tod).getTime() - new Date(dateGD2).getTime() < 0) {
                document.getElementById('dateGD2').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateGD3").change(function(e) {
            var dateGD3 = $('#dateGD3').val();

            if (new Date(tod).getTime() - new Date(dateGD3).getTime() < 0) {
                document.getElementById('dateGD3').value = today;
                $('#date').modal('show');
            }
        });
        $("#dateGD4").change(function(e) {
            var dateGD4 = $('#dateGD4').val();

            if (new Date(tod).getTime() - new Date(dateGD4).getTime() < 0) {
                document.getElementById('dateGD4').value = today;
                $('#date').modal('show');
            }
        });

        
        $("#myModal").modal('show');

        //Valores de select anidados, cargar datos de acuerdo a opcion escogida
        $("#basin").change(function(e) {
            var basin = $('#basin').val();
            $.get("{{url('fieldbybasin')}}", {
                    basin: basin
                },
                function(data) {
                    $("#well").empty();
                    $("#field").empty();

                    $.each(data, function(index, value) {
                        $("#field").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field").selectpicker('refresh');
                    $('#field').selectpicker('val', '');
                });
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
        });

    });

    window.addEventListener('load', MS, false);
//Mostrar y ocultar valores de pestañas
    function MS() {
        document.getElementById('MS').style.display = 'block';
        document.getElementById('FB').style.display = 'none';
        document.getElementById('OS').style.display = 'none';
        document.getElementById('RP').style.display = 'none';
        document.getElementById('ID').style.display = 'none';
        document.getElementById('GD').style.display = 'none';
    }

    function FB() {
        document.getElementById('MS').style.display = 'none';
        document.getElementById('FB').style.display = 'block';
        document.getElementById('OS').style.display = 'none';
        document.getElementById('RP').style.display = 'none';
        document.getElementById('ID').style.display = 'none';
        document.getElementById('GD').style.display = 'none';
    }

    function OS() {
        document.getElementById('MS').style.display = 'none';
        document.getElementById('FB').style.display = 'none';
        document.getElementById('OS').style.display = 'block';
        document.getElementById('RP').style.display = 'none';
        document.getElementById('ID').style.display = 'none';
        document.getElementById('GD').style.display = 'none';
    }

    function RP() {
        document.getElementById('MS').style.display = 'none';
        document.getElementById('FB').style.display = 'none';
        document.getElementById('OS').style.display = 'none';
        document.getElementById('RP').style.display = 'block';
        document.getElementById('ID').style.display = 'none';
        document.getElementById('GD').style.display = 'none';
    }

    function ID() {
        document.getElementById('MS').style.display = 'none';
        document.getElementById('FB').style.display = 'none';
        document.getElementById('OS').style.display = 'none';
        document.getElementById('RP').style.display = 'none';
        document.getElementById('ID').style.display = 'block';
        document.getElementById('GD').style.display = 'none';
    }

    function GD() {
        document.getElementById('MS').style.display = 'none';
        document.getElementById('FB').style.display = 'none';
        document.getElementById('OS').style.display = 'none';
        document.getElementById('RP').style.display = 'none';
        document.getElementById('ID').style.display = 'none';
        document.getElementById('GD').style.display = 'block';
    }
</script>