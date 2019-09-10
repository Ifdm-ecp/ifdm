<script type="text/javascript">
    //Funcion para mostrar y ocultar contenido de boton de tablas
    $(function() {
        $("#myModal").modal('show');
        $('.toggle').click(function(event) {
            event.preventDefault();
            var target = $(this).attr('href');
            $(target).toggleClass('hidden show');
        });

    });

    window.addEventListener('load', MS, false);

    //Mostrar y ocultar contenido de pestañas de parametros
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

    $(document).ready(function() {


        var count = <?php echo count($errors) ?>;

        //Validar pesos de subparametros. Suma no puede pasar al valor 1.
        $('.validate_weight').on('click', function() {

            var boolean_SP = false;
            var message = "";

            var count_MS = 0;
            var count_FB = 0;
            var count_OS = 0;
            var count_RP = 0;
            var count_ID = 0;
            var count_GD = 0;

            $('.modal-body').html('');

            for (var i = 1; i <= 26; i++) {
                var this_value = '#weight_' + i + '_value';
                if ($(this_value).val()) {
                    if (i > 0 && i < 6) {
                        count_MS = count_MS + parseFloat($(this_value).val());
                    } else if (i > 5 && i < 11) {
                        count_FB = count_FB + parseFloat($(this_value).val());
                    } else if (i > 10 && i < 15) {
                        count_OS = count_OS + parseFloat($(this_value).val());
                    } else if (i > 14 && i < 19) {
                        count_RP = count_RP + parseFloat($(this_value).val());
                    } else if (i > 18 && i < 23) {
                        count_ID = count_ID + parseFloat($(this_value).val());
                    } else if (i > 22 && i < 27) {
                        count_GD = count_GD + parseFloat($(this_value).val());
                    }
                }
            }

            if (count_MS < 1) {
                boolean_SP = true;
                message = message + "The sum of Mineral Scales weight must be 1.<br>";
            }

            if (count_FB < 1) {
                boolean_SP = true;
                message = message + "The sum of Fine Blockage weight must be 1.<br>";
            }

            if (count_OS < 1) {
                boolean_SP = true;
                message = message + "The sum of Organic Scales weight must be 1.<br>";
            }

            if (count_RP < 1) {
                boolean_SP = true;
                message = message + "The sum of Relative Permeability weight must be 1.<br>";
            }

            if (count_ID < 1) {
                boolean_SP = true;
                message = message + "The sum of Induced Damage weight must be 1.<br>";
            }

            if (count_GD < 1) {
                boolean_SP = true;
                message = message + "The sum of Geomechanical Damage weight must be 1.<br>";
            }


            if (boolean_SP == true) {
                $('#modal_SP').append(message);
                $('#exampleModal').modal('show');
                var evt = window.event || arguments.callee.caller.arguments[0];
                evt.preventDefault();
            } else {

                $('form#form').submit();
            }

        });

        //Cambio de valor input cuando el usuario edita campo
        $('.value_edit').on('change', function() {
            var this_name = $(this).attr('name') + 'comment';
            var this_name = this_name.replace("date", "");
            document.getElementById(this_name).value = "This value was edited by you";
        });

        //Contadores de valores para SP en cada parametro
        $('.check_weight').on('change', function() {
            var this_name = '#' + $(this).attr('name');
            if ($(this_name).prop('checked')) {
                var count_MS = 0;
                var count_FB = 0;
                var count_OS = 0;
                var count_RP = 0;
                var count_ID = 0;
                var count_GD = 0;

                var not_null_MS = 0;
                var not_null_FB = 0;
                var not_null_OS = 0;
                var not_null_RP = 0;
                var not_null_ID = 0;
                var not_null_GD = 0;
                for (var i = 1; i <= 26; i++) {
                    var this_value = '#weight_' + i + '_value';
                    if (parseFloat($(this_value).val()) > 0) {
                        if (i > 0 && i < 6) {
                            count_MS = count_MS + parseFloat($(this_value).val());
                            not_null_MS++;
                        } else if (i > 5 && i < 11) {
                            count_FB = count_FB + parseFloat($(this_value).val());
                            not_null_FB++;
                        } else if (i > 10 && i < 15) {
                            count_OS = count_OS + parseFloat($(this_value).val());
                            not_null_OS++;
                        } else if (i > 14 && i < 19) {
                            count_RP = count_RP + parseFloat($(this_value).val());
                            not_null_RP++;
                        } else if (i > 18 && i < 23) {
                            count_ID = count_ID + parseFloat($(this_value).val());
                            not_null_ID++;
                        } else if (i > 22 && i < 27) {
                            count_GD = count_GD + parseFloat($(this_value).val());
                            not_null_GD++;
                        }
                    }
                }
                var this_name_id = $(this).attr('id') + '_value';
                var this_name = $(this).attr('id');
                this_name = this_name.replace("weight_", "");
                this_name = this_name.replace("_value", "");
                if (this_name > 0 && this_name < 6) {
                    var num = parseFloat(1 - count_MS);
                    var den = parseFloat(5 - not_null_MS);
                    $('#' + this_name_id).val((num / den).toFixed(2));
                } else if (this_name > 5 && this_name < 11) {
                    var num = parseFloat(1 - count_FB);
                    var den = parseFloat(5 - not_null_FB);
                    $('#' + this_name_id).val((num / den).toFixed(2));
                } else if (this_name > 10 && this_name < 15) {
                    var num = parseFloat(1 - count_OS);
                    var den = parseFloat(4 - not_null_OS);
                    $('#' + this_name_id).val((num / den).toFixed(2));
                } else if (this_name > 14 && this_name < 19) {
                    var num = parseFloat(1 - count_RP);
                    var den = parseFloat(4 - not_null_RP);
                    $('#' + this_name_id).val((num / den).toFixed(2));
                } else if (this_name > 18 && this_name < 23) {
                    var num = parseFloat(1 - count_ID);
                    var den = parseFloat(4 - not_null_ID);
                    $('#' + this_name_id).val((num / den).toFixed(2));
                } else if (this_name > 22 && this_name < 27) {
                    var num = parseFloat(1 - count_GD);
                    var den = parseFloat(4 - not_null_GD);
                    $('#' + this_name_id).val((num / den).toFixed(2));
                }
            }
        });

        $('.check_weight').bind('init', function() {

            var this_name = '#' + $(this).attr('name');
            var this_value = $(this).attr('name') + '_value';
            var this_value_div = '#' + $(this).attr('name') + '_div';
            document.getElementById(this_value).style.borderColor = "#CCCCCC";

            if ($('#' + this_value).val() == 0) {
                //$(this_value_div+' :input').attr('disabled', true);
                //$(this_value_div).attr('disabled', true);
                $(this_name).prop('checked', false);

                $(this_name + '_hidden').val("true");
                $(this_value_div + " *").prop('readonly', true);
            } else {
                $(this_name).prop('checked', true);
                //$(this_value_div+' :input').attr('disabled', false);
                $(this_value_div + " *").prop('readonly', false);
                //$('#'+this_value).val("");
                $(this_name + '_hidden').val("false");
            }
        }).trigger('init');

        $('.check_weight').on('change', function() {
            var this_name = '#' + $(this).attr('name');
            var this_value = $(this).attr('name') + '_value';
            var this_value_div = '#' + $(this).attr('name') + '_div';
            document.getElementById(this_value).style.borderColor = "#CCCCCC";
            if ($(this_name).prop('checked')) {
                //$(this_value_div+' :input').attr('disabled', true);
                //$(this_value_div).attr('disabled', true);
                $(this_name + '_hidden').val("false");
                $(this_value_div + " *").prop('readonly', false);
            } else {
                //$(this_value_div+' :input').attr('disabled', false);
                $(this_value_div + " *").prop('readonly', true);
                $('#' + this_value).val("");
                $(this_name + '_hidden').val("true");
            }
        });

        $('.weight_count').on('change', function() {

            var count_MS = 0;
            var count_FB = 0;
            var count_OS = 0;
            var count_RP = 0;
            var count_ID = 0;
            var count_GD = 0;

            var this_name_id = $(this).attr('id');
            var this_name = $(this).attr('id');
            this_name = this_name.replace("weight_", "");
            this_name = this_name.replace("_value", "");

            var this_value_id = '#' + $(this).attr('id');


            if (!isNaN($(this_value_id).val())) {
                for (var i = 1; i <= 26; i++) {
                    var this_value = '#weight_' + i + '_value';
                    if (parseFloat($(this_value).val()) > 0) {
                        if (i > 0 && i < 6) {
                            count_MS = count_MS + parseFloat($(this_value).val());
                        } else if (i > 5 && i < 11) {
                            count_FB = count_FB + parseFloat($(this_value).val());
                        } else if (i > 10 && i < 15) {
                            count_OS = count_OS + parseFloat($(this_value).val());
                        } else if (i > 14 && i < 19) {
                            count_RP = count_RP + parseFloat($(this_value).val());
                        } else if (i > 18 && i < 23) {
                            count_ID = count_ID + parseFloat($(this_value).val());
                        } else if (i > 22 && i < 27) {
                            count_GD = count_GD + parseFloat($(this_value).val());
                        }
                    }
                }


                if ((count_MS.toFixed(2) > 1 || count_MS.toFixed(2) < 0) && (this_name > 0 && this_name < 6)) {
                    $("#count_error").modal('show');
                    $('#' + this_name_id).val("");
                    document.getElementById(this_name_id).style.borderColor = "#A94442";
                } else if ((count_MS.toFixed(2) >= 0 && count_MS.toFixed(2) <= 1) && this_name > 0 && this_name < 6) {
                    document.getElementById(this_name_id).style.borderColor = "#CCCCCC";
                }

                if ((count_FB.toFixed(2) > 1 || count_FB.toFixed(2) < 0) && (this_name > 5 && this_name < 11)) {
                    $("#count_error").modal('show');
                    $('#' + this_name_id).val("");
                    document.getElementById(this_name_id).style.borderColor = "#A94442";
                } else if ((count_FB.toFixed(2) >= 0 && count_FB.toFixed(2) <= 1) && this_name > 5 && this_name < 11) {
                    document.getElementById(this_name_id).style.borderColor = "#CCCCCC";
                }

                if ((count_OS.toFixed(2) > 1 || count_OS.toFixed(2) < 0) && (this_name > 10 && this_name < 15)) {
                    $("#count_error").modal('show');
                    $('#' + this_name_id).val("");
                    document.getElementById(this_name_id).style.borderColor = "#A94442";
                } else if ((count_OS.toFixed(2) >= 0 && count_OS.toFixed(2) <= 1) && this_name > 10 && this_name < 15) {
                    document.getElementById(this_name_id).style.borderColor = "#CCCCCC";
                }

                if ((count_RP.toFixed(2) > 1 || count_RP.toFixed(2) < 0) && (this_name > 14 && this_name < 19)) {
                    $("#count_error").modal('show');
                    $('#' + this_name_id).val("");
                    document.getElementById(this_name_id).style.borderColor = "#A94442";
                } else if ((count_RP.toFixed(2) >= 0 && count_RP.toFixed(2) <= 1) && this_name > 14 && this_name < 19) {
                    document.getElementById(this_name_id).style.borderColor = "#CCCCCC";
                }

                if ((count_ID.toFixed(2) > 1 || count_ID.toFixed(2) < 0) && (this_name > 18 && this_name < 23)) {
                    $("#count_error").modal('show');
                    $('#' + this_name_id).val("");
                    document.getElementById(this_name_id).style.borderColor = "#A94442";
                } else if ((count_ID.toFixed(2) >= 0 && count_ID.toFixed(2) <= 1) && this_name > 18 && this_name < 23) {
                    document.getElementById(this_name_id).style.borderColor = "#CCCCCC";
                }

                if ((count_GD.toFixed(2) > 1 || count_GD.toFixed(2) < 0) && (this_name > 22 && this_name < 27)) {
                    $("#count_error").modal('show');
                    $('#' + this_name_id).val("");
                    document.getElementById(this_name_id).style.borderColor = "#A94442";
                } else if ((count_GD.toFixed(2) >= 0 && count_GD.toFixed(2) <= 1) && this_name > 22 && this_name < 27) {
                    document.getElementById(this_name_id).style.borderColor = "#CCCCCC";
                }
            } else {
                $(this_value_id).val("");
            }
        });


        $(function() {
            $('[data-toggle="popover"]').popover()
        });

        $(document).ready(function() {
            var nps = [];
            var d;
            var p50;
            var i = 1;
            if ("{{ $multiparametrico->statistical}}" != "Colombia") {
                var dataarray = "{{ $multiparametrico->field_statistical }}";
            } else {
                var dataarray = "Todos";
            }
            $.get("{!! url('P') !!}", {
                campo: dataarray,

            }, function(data) {
                $.each(data, function(index, value) {
                    nps = [];
                    $.each(value, function(index, value) {
                        d = parseFloat(value.valorchart);
                        d = Math.round(d * 100) / 100;
                        nps.push(d);
                    });

                    tam = 0;
                    tam = nps.length;
                    if (tam == 0) {
                        p10 = 0;
                        p50 = 0;
                        p90 = 0;
                    } else {
                        p10 = nps[Math.floor(tam * 0.1)];
                        p50 = nps[Math.floor(tam * 0.5)];
                        p90 = nps[Math.floor(tam * 0.9)];
                    }



                    $("#popover" + i).popover({

                        placement: 'top',
                        html: 'true',


                        title: '<span class="text-info"><strong>Percentile     </strong></span>',


                        content: '<b>p10: </b>' + p10 + '<br><b>p50: </b>' + p50 + '<br><b>p90: </b>' + p90
                    });
                    i++;
                });
            });



        });

        $("#MS1").tooltip({
            title: 'Well Scale Index Of CaCO3 (Scale Chem -Software).'
        });
        $("#MS2").tooltip({
            title: 'Well Scale Index Of BaSO4 Calculated (Scale Chem -Software).'
        });
        $("#MS3").tooltip({
            title: 'Well Scale Index Of Iron Scales Calculated (Scale Chem -Software).'
        });
        $("#MS4").tooltip({
            title: 'Calcium Concentration Measured On Backflow Data.'
        });
        $("#MS5").tooltip({
            title: 'Barium Concentration Measured On Backflow Data.'
        });


        $("#FB1").tooltip({
            title: 'Aluminium Concentration Obtained From Physicochemical Analysis Of Produced Water.'
        });
        $("#FB2").tooltip({
            title: 'Silicon Concentration Obtained From Physicochemical Analysis Of Produced Water.'
        });
        $("#FB3").tooltip({
            title: 'Critical Radius Of The Well Derived From Critical Velocity Lab, Tests At The Maximum Fluid Equivalent Producing Rate.'
        });
        $("#FB4").tooltip({
            title: 'Defined Based On Maximum Kaolinite, Illite And Chlorite Percentages Recorded.'
        });
        $("#FB5").tooltip({
            title: 'Total Lbm. Of Fines In Hydraulic Fractures Generated From The Proppant Materials.'
        });


        $("#GD1").tooltip({
            title: 'Number Of Fractures Per Feet.'
        });
        $("#GD4").tooltip({
            title: 'Expressed As Fraction Of Base Permeability At BHFP.'
        });


        $("#ID1").tooltip({
            title: 'Invasion Radius Measured From The Well Due To Mud Losses.'
        });
        $("#ID2").tooltip({
            title: 'Total Polymer Pumped During Hydraulic Fracturing.'
        });


        $("#OS1").tooltip({
            title: 'Colloidal Instability Index: CII = (Sa+As)/(R+Ar).'
        });
        $("#OS2").tooltip({
            title: 'Cumulative Gas Produced By The Well.'
        });
        $("#OS3").tooltip({
            title: 'Number Of Days Below Saturation Pressure.'
        });

        $("#Kd1").tooltip({
            title: 'From Coreflood Data.'
        });
        $("#Kd2").tooltip({
            title: 'From Coreflood Data.'
        });
        $("#Kd3").tooltip({
            title: 'From Coreflood Data.'
        });
        $("#Kd4").tooltip({
            title: 'From Coreflood Data.'
        });
        $("#Kd5").tooltip({
            title: 'From Coreflood Data.'
        });
        $("#Kd6").tooltip({
            title: 'From Coreflood Data.'
        });

    });
</script>