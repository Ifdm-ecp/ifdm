<script type="text/javascript">
    $(document).ready(function() {
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
                $("#field").selectpicker('val', '');
                $("#well").selectpicker('refresh');
                $("#well").selectpicker('val', '');
                $("#formation").selectpicker('refresh');
                $("#formation").selectpicker('val', '');
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
                $("#well").selectpicker('val', '');
            });
            $.get("{{url('formacionbyfield')}}", {
                    field: field
            },
            function(data) {
                console.log(data);
                $("#formation").empty();
                $.each(data, function(index, value) {
                    $("#formation").append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $("#formation").selectpicker('refresh');
                $('#formation').selectpicker('val', '');
            });
        });

        $("#well").change(function(e) {

            if ( $("#well").val() !== null && $("#formation").val() !== null ) {

                var well = $('#well').val();
                var formation = $("#formation").val();

                $.get("{{url('subparametersbywellandformation')}}", {
                    well: well,
                    formation: formation
                },
                function(data) {
                    $("#modal_notification .modal-body").html('');
                    // if (data.success === true) {

                        var mecdanName = '';
                        var tableIdArray = [];
                        $(".dataTable tbody").html('');
                        $("#subparameter_tabs").show('fast');

                        $.each(data.data, function(index, value) {
                            mecdanName = value.sigla + '_table';
                            if (value.comentario === null) {
                                value.comentario = '';
                            }
                            if (!tableIdArray.includes(mecdanName)) {
                                tableIdArray.push(mecdanName);
                            }
                            
                            var constructedRow = '<tr>' +
                                '<td><span style="display:none">' + value.valor + '</span><input placeholder="' + value.unidad + '" style="width:100%" class="form-control input-sm" id="subvalue_' + value.id + '"" type="text" value="' + value.valor + '"></td>' +
                                '<td><span style="display:none">' + moment(value.fecha).format('DD/MM/YYYY') + '</span><input placeholder="dd/mm/yy" style="width:100%" class="form-control input-sm jquery-datepicker" id="subdate_' + value.id + '"" type="text" value="' + moment(value.fecha).format('DD/MM/YYYY') + '"></td>' +
                                '<td><span style="display:none">' + value.comentario + '</span><input style="width:100%" class="form-control input-sm" id="subcomment_' + value.id + '"" type="text" value="' + value.comentario + '"></td>' +
                                '<td align="center"><button type="button" class="btn btn-sm btn-primary" onclick="editSubparameter(' + value.id + ',&quot;' + value.sigla + '&quot;);">Edit</button> ' +
                                '<button type="button" class="btn btn-sm btn-danger" onclick="removeSubparameter(' + value.id + ',&quot;' + value.sigla + '&quot;);">Remove</button></td>' +
                                '</tr>';
                            $("#" + mecdanName + " tbody").append(constructedRow);
                        });

                        $.each(tableIdArray, function(index, value) {
                            if ($.fn.dataTable.isDataTable("#" + value)) {
                                $("#" + value).DataTable();
                            } else {
                                $("#" + value).DataTable({
                                    "order": [[1, "desc"]]
                                });
                            }
                        });

                        $(".jquery-datepicker").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: "dd/mm/yy"
                        });
                    
                    // } else {
                    //     $("#modal_notification_title").html('Error');
                    //     var errorList = '<ul>';
                    //     $.each(data.errors, function(index, value) {
                    //         $.each(value, function(index2, value2) {
                    //             errorList += '<li>' + value2 + '</li>';
                    //         });
                    //     });

                    //     errorList += '</ul>';

                    //     $("#modal_notification .modal-body").html(errorList);
                    //     $("#modal_notification").modal('show');
                    // }
                });
            }
        });

        $("#formation").change(function(e) {

            if ( $("#well").val() !== null && $("#formation").val() !== null ) {

                var well = $('#well').val();
                var formation = $("#formation").val();

                $.get("{{url('subparametersbywellandformation')}}", {
                    well: well,
                    formation: formation
                },
                function(data) {
                    $("#modal_notification .modal-body").html('');
                    // if (data.success === true) {

                        var mecdanName = '';
                        var tableIdArray = [];
                        $(".dataTable tbody").html('');
                        $("#subparameter_tabs").show('fast');

                        $.each(data.data, function(index, value) {
                            mecdanName = value.sigla + '_table';
                            if (value.comentario === null) {
                                value.comentario = '';
                            }
                            if (!tableIdArray.includes(mecdanName)) {
                                tableIdArray.push(mecdanName);
                            }
                            
                            var constructedRow = '<tr>' +
                                '<td><span style="display:none">' + value.valor + '</span><input placeholder="' + value.unidad + '" style="width:100%" class="form-control input-sm" id="subvalue_' + value.id + '"" type="text" value="' + value.valor + '"></td>' +
                                '<td><span style="display:none">' + moment(value.fecha).format('DD/MM/YYYY') + '</span><input placeholder="dd/mm/yy" style="width:100%" class="form-control input-sm jquery-datepicker" id="subdate_' + value.id + '"" type="text" value="' + moment(value.fecha).format('DD/MM/YYYY') + '"></td>' +
                                '<td><span style="display:none">' + value.comentario + '</span><input style="width:100%" class="form-control input-sm" id="subcomment_' + value.id + '"" type="text" value="' + value.comentario + '"></td>' +
                                '<td align="center"><button type="button" class="btn btn-sm btn-primary" onclick="editSubparameter(' + value.id + ',&quot;' + value.sigla + '&quot;);">Edit</button> ' +
                                '<button type="button" class="btn btn-sm btn-danger" onclick="removeSubparameter(' + value.id + ',&quot;' + value.sigla + '&quot;);">Remove</button></td>' +
                                '</tr>';
                            $("#" + mecdanName + " tbody").append(constructedRow);
                        });

                        $.each(tableIdArray, function(index, value) {
                            if ($.fn.dataTable.isDataTable("#" + value)) {
                                $("#" + value).DataTable();
                            } else {
                                $("#" + value).DataTable({
                                    "order": [[1, "desc"]]
                                });
                            }
                        });

                        $(".jquery-datepicker").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: "dd/mm/yy"
                        });
                    
                    // } else {
                    //     $("#modal_notification_title").html('Error');
                    //     var errorList = '<ul>';
                    //     $.each(data.errors, function(index, value) {
                    //         $.each(value, function(index2, value2) {
                    //             errorList += '<li>' + value2 + '</li>';
                    //         });
                    //     });

                    //     errorList += '</ul>';

                    //     $("#modal_notification .modal-body").html(errorList);
                    //     $("#modal_notification").modal('show');
                    // }
                });
            }
        });

        $(".dataTable").on('page.dt', function () {
            setTimeout(function() {
                $(".jquery-datepicker").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd/mm/yy"
                });
            }, 50);
        });
    });

    /* editSubparameter
    * Edits the subparameter with the new data submitted
    */
    function editSubparameter(subparameterId, subInitials) {
        var subValue = $('#subvalue_' + subparameterId).val();
        var subDate = $('#subdate_' + subparameterId).val();
        var subComment = $('#subcomment_' + subparameterId).val();

        $.get("{{url('EditSubparameterC')}}", {
            id: subparameterId,
            value: subValue,
            date: subDate,
            comment: subComment,
            initials: subInitials
        },
        function(data) {
            $("#modal_notification .modal-body").html('');

            if (data.success === true) {
                $("#modal_notification_title").html('Success');
                $("#modal_notification .modal-body").html('<p>' + data.message + '</p>');
            } else {
                $("#modal_notification_title").html('Error');
                var errorList = '<ul>';
                $.each(data.errors, function(index, value) {
                    $.each(value, function(index2, value2) {
                        errorList += '<li>' + value2 + '</li>';
                    });
                });

                errorList += '</ul>';
                $("#modal_notification .modal-body").html(errorList);
            }

            $("#modal_notification").modal('show');
        });
    }

    /* removeSubparameter
    * Removes the subparameter with the new data submitted
    */
    function removeSubparameter(subparameterId, subInitials) {
        $.get("{{url('RemoveSubparameterC')}}", {
            id: subparameterId
        },
        function(data) {
            $("#modal_notification .modal-body").html('');

            if (data.success === true) {
                var table = $('#' + subInitials + '_table').DataTable();
                table.row( $('#subvalue_' + subparameterId).parents('tr') ).remove().draw();

                $(".jquery-datepicker").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd/mm/yy"
                });

                $("#modal_notification_title").html('Success');
                $("#modal_notification .modal-body").html('<p>' + data.message + '</p>');
            } else {
                $("#modal_notification_title").html('Error');
                var errorList = '<ul>';
                $.each(data.errors, function(index, value) {
                    $.each(value, function(index2, value2) {
                        errorList += '<li>' + value2 + '</li>';
                    });
                });

                errorList += '</ul>';
                $("#modal_notification .modal-body").html(errorList);
            }

            $("#modal_notification").modal('show');
        });
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

    function reload() {
        location.reload(true);
    }

    // window.onbeforeunload = function() {

    //     localStorage.setItem('basin', $('#basin').val());
    //     localStorage.setItem('well', $('#well').val());
    //     localStorage.setItem('field', $('#field').val());
    //     localStorage.setItem('formation', $('#formation').val());

    // }

    // window.onload = function() {
    //     $("#field").empty();
    //     $("#well").empty();
    //     $("#formation").empty();

    //     var basin = localStorage.getItem('basin');
    //     var well = localStorage.getItem('well');
    //     var field = localStorage.getItem('field');
    //     var formation = localStorage.getItem('formation');

    //     $("#basin").val(basin).change();
    // }
</script>