<script type="text/javascript">
//Mostrar mensaje modal para borrar una peticion. Se debe mandar el id de la peticion unido al formulario para saber cual borrar
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


    //Crear y cargar datos de tabla con consulta a la BD
    function Table(a) {

        $("#table_well").modal('show');

        $('#table_well').on('show.bs.modal', function(e) {
            $message = $(e.relatedTarget).attr('data-message');
            $(this).find('.modal-body p').text($message);
            $title = $(e.relatedTarget).attr('data-title');
            $(this).find('.modal-title').text($title);

            var form = $(e.relatedTarget).closest('form');
            $(this).find('.modal-footer #confirm').data('form', form);
        });


        var perms = [];
        var aux = a;
        $.get("{!! url('excel_pro') !!}", {
            peticion: aux

        }, function(data) {
            $.each(data, function(index, value) {
                var per = [];
                per.push(value.date);
                per.push(value.qo);
                per.push(value.cummulative_qo);
                per.push(value.qg);
                per.push(value.cummulative_qg);
                per.push(value.qw);
                per.push(value.cummulative_qw);
                perms.push(per);
            });

            if (perms.length == 0) {
                perms = [
                    [, , ],
                    [, , ]
                ];
            }

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
                data: perms,
                colWidths: [125, 85, 130, 85, 130, 85, 130],
                rowHeaders: true,
                columns: [

                    {
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

                minSpareRows: 0,
                contextMenu: true,

                width: function() {
                    if (maxed && availableWidth === void 0) {
                        calculateSize();
                    }
                    return maxed ? availableWidth : 1600;
                },
                height: function() {
                    if (maxed && availableHeight === void 0) {
                        calculateSize();
                    }
                    return maxed ? availableHeight : 200;
                }
            });

        });
    }

    function TableInterval(a) {
        var aux = a;
        $("#table_int").modal('show');

        $('#table_int').on('show.bs.modal', function(e) {
            $message = $(e.relatedTarget).attr('data-message');
            $(this).find('.modal-body p').text($message);
            $title = $(e.relatedTarget).attr('data-title');
            $(this).find('.modal-title').text($title);

            var form = $(e.relatedTarget).closest('form');
            $(this).find('.modal-footer #confirm').data('form', form);
        });


        var perms = [];
        $.get("{!! url('excel_int3') !!}", {
            peticion: aux
        }, function(data) {
            $.each(data, function(index, value) {
                var per = [];
                per.push(value.sw);
                per.push(value.krw);
                per.push(value.kro);
                per.push(value.pcwo);
                perms.push(per);

            });

            if (perms.length == 0) {
                perms = [
                    [, , , , , ],
                    [, , , , , ]
                ];
            }

            var data = [
                [, , , , , ],
                [, , , , , ]
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
                data: perms,
                colWidths: [80, 80, 80, 80],
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
                    return maxed ? availableWidth : 1600;
                },
                height: function() {
                    if (maxed && availableHeight === void 0) {
                        calculateSize();
                    }
                    return maxed ? availableHeight : 300;
                }
            });
        });


        var perms2 = [];
        $.get("{!! url('excel_int33') !!}", {
            peticion: aux
        }, function(data) {
            $.each(data, function(index, value) {
                var per = [];
                per.push(value.sg);
                per.push(value.krg);
                per.push(value.krl);
                per.push(value.pcgl);
                perms2.push(per);

            });

            if (perms2.length == 0) {
                perms2 = [
                    [, , , , , ],
                    [, , , , , ]
                ];

            }

            var data = [
                [, , , , , , , , ],
                [, , , , , , , , ]
            ];

            var maxed = false,
                resizeTimeout, availableWidth, availableHeight, $window = $(window),
                $excel4 = $('#excel4');

            var calculateSize = function() {
                var offset = $example1.offset();
                availableWidth = $window.width() - offset.left + $window.scrollLeft();
                availableHeight = $window.height() - offset.top + $window.scrollTop();
            };
            $window.on('resize', calculateSize);

            $excel4.handsontable({
                data: perms2,
                colWidths: [80, 80, 80, 80],
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
                contextMenu: true,
                width: function() {
                    if (maxed && availableWidth === void 0) {
                        calculateSize();
                    }
                    return maxed ? availableWidth : 1600;
                },
                height: function() {
                    if (maxed && availableHeight === void 0) {
                        calculateSize();
                    }
                    return maxed ? availableHeight : 300;
                }
            });
        });
    }

    $(document).ready(function() {
        $("#myModal").modal('show');
    });
</script>