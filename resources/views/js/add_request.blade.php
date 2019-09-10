<script type="text/javascript">

    //Guardar valores de formulario para volver a cargar en caso de que salga error en el formulario
    window.onbeforeunload = function() {
        localStorage.setItem('well', $('#well').val());
        localStorage.setItem('field', $('#field').val());
        localStorage.setItem('damage_variables', $('#damage_variables').val());
        localStorage.setItem('damage_configuration', $('#damage_configuration').val());
        localStorage.setItem('request', $('#request').val());
        localStorage.setItem('well_well', $('#well_well').val());
        localStorage.setItem('field_well', $('#field_well').val());
        localStorage.setItem('well_interval', $('#well_interval').val());
        localStorage.setItem('field_interval', $('#field_interval').val());
        localStorage.setItem('formation_interval', $('#formation_interval').val());
        localStorage.setItem('interval_interval', $('#interval_interval').val());
    }

    //Cargar variables guardadas del formulario en caso de que salga error en valores
    window.onload = function() {
        ///////////////Damage variables
        var req = localStorage.getItem('request');
        $('#request > option[value= "' + req + '" ]').attr('selected', 'selected');
        $("#request").selectpicker('refresh');

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

        //Volver a cargar valores de select en caso de que salga error en formulario
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

            });


        var mec = $('#mecan_dano').val();

        $('#damage_variables').selectpicker('deselectAll');
        $("#damage_variables").append('<optgroup label="Sub-Parameters">');

        $.get("{!! url('parametros') !!}", {
                mec: mec
            },
            function(data) {

                $("#damage_variables").empty();
                $("#damage_configuration").empty();

                $.each(data, function(index, value) {
                    $("#damage_variables").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });

                $("#damage_variables").append('</optgroup>');


                $.get("{{url('variables_dano')}}", {
                        mec: mec
                    },
                    function(data) {
                        $("#damage_variables").append('<optgroup label="Another Damage Variables" disabled>');
                        $.each(data, function(index, value) {

                            $("#damage_variables").append('<option value="' + value.nombre + '">' + value.nombre + '</option>');
                        });
                        $("#damage_variables").append('</optgroup>');
                        $("#damage_variables").selectpicker('refresh');
                        $('#damage_variables').selectpicker('val', localStorage.getItem('damage_variables'));
                    });

                $.get("{{url('config_dano')}}", {
                        mec: mec
                    },
                    function(data) {
                        $.each(data, function(index, value) {
                            $("#damage_configuration").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        $("#damage_configuration").selectpicker('refresh');
                        $('#damage_configuration').selectpicker('val', localStorage.getItem('damage_configuration'));
                    });
            });
        ////////////////////////

        /////////Well

        var variable_name = $('#variable_name').val();
        if (variable_name == '') {
            document.getElementById('well_div_val').style.display = 'none';
            document.getElementById('well_div_tab').style.display = 'none';
            $("#value_well").val("");
            $("#ProdD2").val("");
        }
        if ((variable_name == "BHP") || (variable_name == "Well radius") || (variable_name == "Drainage radius") || (variable_name == "Latitude") || (variable_name == "Longitude") || (variable_name == "TVD")) {
            document.getElementById('well_div_val').style.display = 'block';
            document.getElementById('well_div_tab').style.display = 'none';
            $("#ProdD2").val("");
        }
        if (variable_name == "Production data") {
            document.getElementById('well_div_tab').style.display = 'block';
            document.getElementById('well_div_val').style.display = 'none';
            $("#value_well").val("");
        }


        var basin_well = $('#basin_well').val();
        var field_well = localStorage.getItem('field_well');
        $.get("{{url('fieldbybasinselect')}}", {
                basin: basin_well
            },
            function(data) {
                $("#field_well").empty();
                $.each(data, function(index, value) {
                    $("#field_well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#field_well > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', field_well);
                $(k).attr('selected', 'selected');
                $("#field_well").selectpicker('refresh');

            }

        );

        var field_well = localStorage.getItem('field_well');
        var pozo_well = localStorage.getItem('well_well');
        $.get("{{url('fields')}}", {
                field: field_well
            },
            function(data) {
                $("#well_well").empty();
                $.each(data, function(index, value) {
                    $("#well_well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#well_well > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', pozo_well);
                $(k).attr('selected', 'selected');
                $("#well_well").selectpicker('refresh');

            });

        ////////////////////////


        /////////Interval

        var variable_name_int = $('#variable_name_int').val();
        if (variable_name_int == '') {
            document.getElementById('interval_div_val').style.display = 'none';
            document.getElementById('interval_div_tab').style.display = 'none';
            $("#value_interval").val("");
            $("#ProdD2").val("");
        }
        if ((variable_name_int == "Top") || (variable_name_int == "Net pay") || (variable_name_int == "Porosity") || (variable_name_int == "Permeability") || (variable_name_int == "Reservoir pressure")) {
            document.getElementById('interval_div_val').style.display = 'block';
            document.getElementById('interval_div_tab').style.display = 'none';
            $("#RelP2").val("");
            $("#RelP").val("");
        }
        if (variable_name_int == "Relative permeability and capilar pressure") {
            document.getElementById('interval_div_tab').style.display = 'block';
            document.getElementById('interval_div_val').style.display = 'none';
            $("#value_interval").val("");
        }

        var basin_interval = $('#basin_interval').val();
        var field_interval = localStorage.getItem('field_interval');
        $.get("{{url('fieldbybasinselect')}}", {
                basin: basin_interval
            },
            function(data) {
                $("#field_interval").empty();
                $.each(data, function(index, value) {
                    $("#field_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#field_interval > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', field_interval);
                $(k).attr('selected', 'selected');
                $("#field_interval").selectpicker('refresh');

            }

        );

        var field_interval = localStorage.getItem('field_interval');
        var pozo_interval = localStorage.getItem('well_interval');
        $.get("{{url('fields')}}", {
                field: field_interval
            },
            function(data) {
                $("#well_interval").empty();
                $.each(data, function(index, value) {
                    $("#well_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#well_interval > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', pozo_interval);
                $(k).attr('selected', 'selected');
                $("#well_interval").selectpicker('refresh');

            });

        var formation_interval = localStorage.getItem('formation_interval');
        $.get("{{url('formationbyfield')}}", {
                field: field_interval
            },
            function(data) {
                $("#formation_interval").empty();
                $.each(data, function(index, value) {
                    $("#formation_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#formation_interval > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', formation_interval);
                $(k).attr('selected', 'selected');
                $("#formation_interval").selectpicker('refresh');
            });

        var interval_interval = localStorage.getItem('interval_interval');
        $.get("{{url('intervalbyformationwell')}}", {
                formacion: formation_interval,
                well: pozo_interval
            },
            function(data) {
                $("#interval_interval").empty();
                $.each(data, function(index, value) {
                    $("#interval_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                var k = '#interval_interval > option[value="{{ 'xxx'}}"]';
                k = k.replace('xxx', interval_interval);
                $(k).attr('selected', 'selected');
                $("#interval_interval").selectpicker('refresh');
            });

        ////////////////////////


        if (req == "Damage variables") {
            document.getElementById('damage_variables_div').style.display = 'block';
            document.getElementById('well_div').style.display = 'none';
            document.getElementById('interval_div').style.display = 'none';
        } else if (req == "Well") {
            document.getElementById('well_div').style.display = 'block';
            document.getElementById('damage_variables_div').style.display = 'none';
            document.getElementById('interval_div').style.display = 'none';
        } else if (req == "Producing interval") {
            document.getElementById('interval_div').style.display = 'block';
            document.getElementById('damage_variables_div').style.display = 'none';
            document.getElementById('well_div').style.display = 'none';
        } else {
            document.getElementById('damage_variables_div').style.display = 'none';
            document.getElementById('well_div').style.display = 'none';
            document.getElementById('interval_div').style.display = 'none';
        }

    }
    $(document).ready(function() {

        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var tod = year + "-" + month + "-" + day;

        var today = 0000 + "-" + 00 + "-" + 00;

        $("#request").change(function(e) {
            var request = $('#request').val();
            if (request == '') {
                document.getElementById('damage_variables_div').style.display = 'none';
                document.getElementById('well_div').style.display = 'none';
                document.getElementById('interval_div').style.display = 'none';
            }

            if (request == "Damage variables") {
                document.getElementById('damage_variables_div').style.display = 'block';
                document.getElementById('well_div').style.display = 'none';
                document.getElementById('interval_div').style.display = 'none';
            }

            if (request == "Well") {
                document.getElementById('well_div').style.display = 'block';
                document.getElementById('damage_variables_div').style.display = 'none';
                document.getElementById('interval_div').style.display = 'none';
            }

            if (request == "Producing interval") {
                document.getElementById('interval_div').style.display = 'block';
                document.getElementById('damage_variables_div').style.display = 'none';
                document.getElementById('well_div').style.display = 'none';
            }
        });

        $("#myModal").modal('show');


        //Damage variables
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


        $("#mecan_dano").change(function(e) {

            var mec = $('#mecan_dano').val();

            $('#damage_variables').selectpicker('deselectAll');
            $("#damage_variables").append('<optgroup label="Sub-Parameters">');

            $.get("{!! url('parametros') !!}", {
                    mec: mec
                },
                function(data) {

                    $("#damage_variables").empty();
                    $("#damage_configuration").empty();

                    $.each(data, function(index, value) {
                        $("#damage_variables").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });

                    $("#damage_variables").append('</optgroup>');


                    $.get("{{url('variables_dano')}}", {
                            mec: mec
                        },
                        function(data) {
                            $("#damage_variables").append('<optgroup label="Another Damage Variables" disabled>');
                            $.each(data, function(index, value) {

                                $("#damage_variables").append('<option value="' + value.nombre + '">' + value.nombre + '</option>');
                            });
                            $("#damage_variables").append('</optgroup>');
                            $("#damage_variables").selectpicker('refresh');
                            $('#damage_variables').selectpicker('val', '');
                        });

                    $.get("{{url('config_dano')}}", {
                            mec: mec
                        },
                        function(data) {
                            $.each(data, function(index, value) {
                                $("#damage_configuration").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                            });
                            $("#damage_configuration").selectpicker('refresh');
                            $('#damage_configuration').selectpicker('val', '');
                        });
                });
        });



        $("#damage_variables").change(function(e) {
            $('#damage_configuration').prop('disabled', false);
            $('#damage_configuration').selectpicker('val', '');

        });

        $("#damage_configuration").change(function(e) {
            $('#damage_variables').prop('disabled', false);
            $('#damage_variables').selectpicker('val', '');

        });


        $("#date").change(function(e) {
            var date = $('#date').val();

            if (new Date(tod).getTime() - new Date(date).getTime() < 0) {
                document.getElementById('date').value = today;
                $('#date_modal').modal('show');
            }
        });

        //////////////////////////

        //Well
        $('.max').on('click', function() {
            //Production data
            var variable_name = $('#variable_name').val();
            var production_data = $excel2.handsontable('getData');
            d0 = production_data[0][0];
            d1 = production_data[0][1];
            d2 = production_data[0][2];
            d3 = production_data[0][3];
            d4 = production_data[0][4];
            d5 = production_data[0][5];
            d6 = production_data[0][6];
            var valid = true;

            if ((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) && (d3 === "" || d3 == null) && (d4 === "" || d4 == null) && (d5 === "" || d5 == null) && (d6 === "" || d6 == null)) {
                valid = false;
                mensaje = "Check your production data, there's nothing there...";
            }

            for (var i = 0; i < production_data.length; i++) {
                d0 = production_data[i][0];
                d1 = production_data[i][1];
                d2 = production_data[i][2];
                d3 = production_data[i][3];
                d4 = production_data[i][4];
                d5 = production_data[i][5];
                d6 = production_data[i][6];
                if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "") || (d4 != null && d4 != "") || (d5 != null && d5 != "") || (d6 != null && d6 != "")) {

                    if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "" || d4 == null || d4 === "" || d5 == null || d5 === "" || d6 == null || d6 === "") {
                        valid = false;
                        mensaje = "Check your production data, there's nothing there...";
                    }
                }
            }
            var evt = window.event || arguments.callee.caller.arguments[0];

            if (!valid && variable_name == "Production data") {
                evt.preventDefault();
                alert(mensaje);
            }
        });

        $("#variable_name").change(function(e) {
            var variable_name = $('#variable_name').val();
            if (variable_name == '') {
                document.getElementById('well_div_val').style.display = 'none';
                document.getElementById('well_div_tab').style.display = 'none';
                $("#value_well").val("");
                $("#ProdD2").val("");
            }
            if ((variable_name == "BHP") || (variable_name == "Well radius") || (variable_name == "Drainage radius") || (variable_name == "Latitude") || (variable_name == "Longitude") || (variable_name == "TVD")) {
                document.getElementById('well_div_val').style.display = 'block';
                document.getElementById('well_div_tab').style.display = 'none';
                $("#ProdD2").val("");
            }
            if (variable_name == "Production data") {
                document.getElementById('well_div_tab').style.display = 'block';
                document.getElementById('well_div_val').style.display = 'none';
                $("#value_well").val("");
            }
        });

        //Table

        var data = [
            [, , ],
            [, , ]
        ];

        var maxed = false,
            resizeTimeout, availableWidth, availableHeight, $window = $(window),
            $excel2 = $('#excel2');

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel2.handsontable({
            data: data,
            colWidths: [150, 125, 200, 125, 200, 125, 200],
            rowHeaders: true,
            columns: [{
                    title: "Date",
                    data: 0,
                    type: 'date',
                    dateFormat: 'YYYY/MM/DD',
                    correctFormat: true
                },
                {
                    title: "Qo <br> [bbl/day]",
                    data: 1,
                    type: 'numeric'
                },
                {
                    title: "Cummulative Qo <br> [bbl]",
                    data: 2,
                    type: 'numeric'
                },
                {
                    title: "Qg <br> [MMScf/day]",
                    data: 3,
                    type: 'numeric'
                },
                {
                    title: "Cummulative Qg <br> [MMScf]",
                    data: 4,
                    type: 'numeric'
                },
                {
                    title: "Qw <br> [bbl/day]",
                    data: 5,
                    type: 'numeric'
                },
                {
                    title: "Cummulative Qw <br> [bbl]",
                    data: 6,
                    type: 'numeric'
                },
            ],


            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 2600;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 200;
            }
        });

        $('.maximize').on('click', function() {
            document.getElementById("ProdD2").value = $excel2.handsontable('getData');
        });

        ///Table--




        $("#basin_well").change(function(e) {
            var basin = $('#basin_well').val();
            $.get("{{url('fieldbybasin')}}", {
                    basin: basin
                },
                function(data) {
                    $("#well_well").empty();
                    $("#field_well").empty();

                    $.each(data, function(index, value) {
                        $("#field_well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field_well").selectpicker('refresh');
                    $('#field_well').selectpicker('val', '');
                });
        });

        $("#field_well").change(function(e) {
            var field = $('#field_well').val();
            $.get("{{url('wellbyfield')}}", {
                    field: field
                },
                function(data) {
                    $("#well_well").empty();
                    $.each(data, function(index, value) {
                        $("#well_well").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#well_well").selectpicker('refresh');
                    $('#well_well').selectpicker('val', '');
                });
        });

        $("#date_well").change(function(e) {
            var date = $('#date_well').val();

            if (new Date(tod).getTime() - new Date(date).getTime() < 0) {
                document.getElementById('date_well').value = today;
                $('#date_modal').modal('show');
            }
        });

        //////////////////////////


        //Interval
        $('.gasLiquid').on('click', function() {
            //Production data
            var variable_name_int = $('#variable_name_int').val();
            var gasLiquid = $excel3.handsontable('getData');
            d0 = gasLiquid[0][0];
            d1 = gasLiquid[0][1];
            d2 = gasLiquid[0][2];
            d3 = gasLiquid[0][3];
            var valid = true;

            if ((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) && (d3 === "" || d3 == null)) {
                valid = false;
                mensaje1 = "Check your Gas-Liquid data, there's nothing there...";
            }

            for (var i = 0; i < gasLiquid.length; i++) {
                d0 = gasLiquid[i][0];
                d1 = gasLiquid[i][1];
                d2 = gasLiquid[i][2];
                d3 = gasLiquid[i][3];

                if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "")) {

                    if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "") {
                        valid = false;
                        mensaje1 = "Check your Gas-Liquid data, there's nothing there...";
                    }
                }
            }
            var evt = window.event || arguments.callee.caller.arguments[0];

            if (!valid && variable_name_int == "Relative permeability and capilar pressure") {
                evt.preventDefault();
                alert(mensaje1);
            }
        });

        $('.waterOil').on('click', function() {
            //Production data
            var variable_name_int = $('#variable_name_int').val();
            var waterOil = $excel.handsontable('getData');
            d0 = waterOil[0][0];
            d1 = waterOil[0][1];
            d2 = waterOil[0][2];
            d3 = waterOil[0][3];
            var valid = true;

            if ((d0 === "" || d0 == null) && (d1 === "" || d1 == null) && (d2 === "" || d2 == null) && (d3 === "" || d3 == null)) {
                valid = false;
                mensaje2 = "Check your Water-Oil data, there's nothing there...";
            }

            for (var i = 0; i < waterOil.length; i++) {
                d0 = waterOil[i][0];
                d1 = waterOil[i][1];
                d2 = waterOil[i][2];
                d3 = waterOil[i][3];
                if ((d0 != null && d0 != "") || (d1 != null && d1 != "") || (d2 != null && d2 != "") || (d3 != null && d3 != "")) {

                    if (d1 == null || d1 === "" || d0 == null || d0 === "" || d2 == null || d2 === "" || d3 == null || d3 === "") {
                        valid = false;
                        mensaje2 = "Check your Water-Oil data, there's nothing there...";
                    }
                }
            }
            var evt = window.event || arguments.callee.caller.arguments[0];

            if (!valid && variable_name_int == "Relative permeability and capilar pressure") {
                evt.preventDefault();
                alert(mensaje2);
            }
        });
        $("#variable_name_int").change(function(e) {
            var variable_name_int = $('#variable_name_int').val();
            if (variable_name_int == '') {
                document.getElementById('interval_div_val').style.display = 'none';
                document.getElementById('interval_div_tab').style.display = 'none';
                $("#value_interval").val("");
                $("#ProdD2").val("");
            }
            if ((variable_name_int == "Top") || (variable_name_int == "Net pay") || (variable_name_int == "Porosity") || (variable_name_int == "Permeability") || (variable_name_int == "Reservoir pressure")) {
                document.getElementById('interval_div_val').style.display = 'block';
                document.getElementById('interval_div_tab').style.display = 'none';
                $("#RelP2").val("");
                $("#RelP").val("");
            }
            if (variable_name_int == "Relative permeability and capilar pressure") {
                document.getElementById('interval_div_tab').style.display = 'block';
                document.getElementById('interval_div_val').style.display = 'none';
                $("#value_interval").val("");
            }
        });

        //table 
        var data = [
            [, , , ],
            [, , , ]
        ];


        var maxed = false,
            resizeTimeout, availableWidth, availableHeight, $window = $(window),
            $excel = $('#excel');

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel.handsontable({
            data: data,
            colWidths: [130, 130, 130, 130],
            rowHeaders: true,
            columns: [

                {
                    title: "Sw",
                    data: 0,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Krw",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Kro",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Pcwo",
                    data: 3,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
            ],

            minSpareRows: 1,
            contextMenu: true,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 1300;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 300;
            }
        });

        $('.maximize').on('click', function() {
            document.getElementById("RelP").value = $excel.handsontable('getData');
        });


        var data = [
            [, , , ],
            [, , , ]
        ];


        var maxed = false,
            resizeTimeout, availableWidth, availableHeight, $window = $(window),
            $excel3 = $('#excel3');

        var calculateSize = function() {
            var offset = $example1.offset();
            availableWidth = $window.width() - offset.left + $window.scrollLeft();
            availableHeight = $window.height() - offset.top + $window.scrollTop();
        };
        $window.on('resize', calculateSize);

        $excel3.handsontable({
            data: data,
            colWidths: [130, 130, 130, 130],
            rowHeaders: true,
            columns: [

                {
                    title: "Sg",
                    data: 0,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Krg",
                    data: 1,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Krl",
                    data: 2,
                    type: 'numeric',
                    format: '0[.]0000000'
                },
                {
                    title: "Pcgl",
                    data: 3,
                    type: 'numeric',
                    format: '0[.]0000000'
                }
            ],
            minSpareRows: 1,
            width: function() {
                if (maxed && availableWidth === void 0) {
                    calculateSize();
                }
                return maxed ? availableWidth : 1300;
            },
            height: function() {
                if (maxed && availableHeight === void 0) {
                    calculateSize();
                }
                return maxed ? availableHeight : 300;
            }
        });


        $('.maximize3').on('click', function() {
            document.getElementById("RelP2").value = $excel3.handsontable('getData');
        });

        //table ----
        $("#basin_interval").change(function(e) {
            var basin = $('#basin_interval').val();
            $.get("{{url('fieldbybasin')}}", {
                    basin: basin
                },
                function(data) {
                    $("#well_interval").empty();
                    $("#field_interval").empty();

                    $.each(data, function(index, value) {
                        $("#field_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#field_interval").selectpicker('refresh');
                    $('#field_interval').selectpicker('val', '');
                });
        });

        $("#field_interval").change(function(e) {
            var field = $('#field_interval').val();
            $.get("{{url('wellbyfield')}}", {
                    field: field
                },
                function(data) {
                    $("#well_interval").empty();
                    $.each(data, function(index, value) {
                        $("#well_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#well_interval").selectpicker('refresh');
                    $('#well_interval').selectpicker('val', '');
                });
            $.get("{{url('formationbyfield')}}", {
                    field: field
                },
                function(data) {
                    $("#formation_interval").empty();
                    $.each(data, function(index, value) {
                        $("#formation_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#formation_interval").selectpicker('refresh');
                    $('#formation_interval').selectpicker('val', '');
                });
        });

        $("#formation_interval").change(function(e) {
            var formacion = $('#formation_interval').val();
            var well = $('#well_interval').val();
            $.get("{{url('intervalbyformationwell')}}", {
                    formacion: formacion,
                    well: well
                },
                function(data) {
                    $("#interval_interval").empty();
                    $.each(data, function(index, value) {
                        $("#interval_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#interval_interval").selectpicker('refresh');
                    $('#interval_interval').selectpicker('val', '');
                });
        });

        $("#well_interval").change(function(e) {
            var formacion = $('#formation_interval').val();
            var well = $('#well_interval').val();
            $.get("{{url('intervalbyformationwell')}}", {
                    formacion: formacion,
                    well: well
                },
                function(data) {
                    $("#interval_interval").empty();
                    $.each(data, function(index, value) {
                        $("#interval_interval").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                    });
                    $("#interval_interval").selectpicker('refresh');
                    $('#interval_interval').selectpicker('val', '');
                });
        });

        $("#date_interval").change(function(e) {
            var date = $('#date_interval').val();

            if (new Date(tod).getTime() - new Date(date).getTime() < 0) {
                document.getElementById('date_interval').value = today;
                $('#date_modal').modal('show');
            }
        });

        //////////////////////////
    });
</script>