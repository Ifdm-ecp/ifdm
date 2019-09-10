$('#btnImportarPvt').on('click', function() {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault();
    $('#modalImportarPvt').modal();
    datosPvt();
});

$('#btnImportarPvtFxP').on('click', function() {
    var evt = window.event || arguments.callee.caller.arguments[0];
    evt.preventDefault();
    $('#modalImportarPvt').modal();
    datosPvt();
});


function datosPvt() {
    $.get('/treePvt', function(datos) {
        console.log(typeof datos);

        $('#jstree_demo_div')
            .on('changed.jstree', function(e, data) {
                var id = parseInt(data.selected);
                if (Number.isInteger(id)) {
                    $.get('/treePvt/' + id, function(data) {
                        datos = JSON.parse(data);
                        console.log(data);
                        $('input[name=saturation_pressure]').val(datos.saturacion_pressure);
                        tablaPvt(datos.tabla);
                        $('#modalImportarPvt').modal('hide');
                    });
                }

            })
            .jstree({
                'core': {
                    'data': JSON.parse(datos)
                }
            });
    });
}
/*
function datosPvtFxP() {
    $.get('/treePvt', function(datos) {
        console.log(typeof datos);

        $('#jstree_demo_div')
            .on('changed.jstree', function(e, data) {
                var id = parseInt(data.selected);
                if (Number.isInteger(id)) {
                    $.get('/treePvt/' + id, function(data) {
                        $datos = JSON.parse(data);
                        tablaPvt(data.tabla);
                        $('#modalImportarPvt').modal('hide');
                    });
                }

            })
            .jstree({
                'core': {
                    'data': JSON.parse(datos)
                }
            });
    });
}
*/
function tablaPvt(data_pvt) {
    $('#table_field_pvt').handsontable({
        data: data_pvt,
        colWidths: [100, 55, 55, 55, 90, 90, 90, 90, 90],
        rowHeaders: true,
        columns: [{
                title: "Pressure[psia]",
                data: 0,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "μo[cP]",
                data: 1,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "μg[cP]",
                data: 2,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "μw[cP]",
                data: 3,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Bo[RB/STB]",
                data: 4,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Bg[RCF/SCF]",
                data: 5,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Bw[RB/STB]",
                data: 6,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Rs[SCF/STB]",
                data: 7,
                type: 'numeric',
                format: '0[.]0000000'
            },
            {
                title: "Rv[STB/SCF]",
                data: 8,
                type: 'numeric',
                format: '0[.]0000000'
            }
        ],

        minSpareRows: 1,
        contextMenu: true
    });

}