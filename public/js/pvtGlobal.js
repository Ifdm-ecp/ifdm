//CLICK DEL BOTON PLOT
    $('#plot').on('click', function(){
      plot_fieldPVT();
    });

    //formPvtGlobal
    $('#save').on('click', function(e){
      datos = dataTabla();
      savePvtGlobal(datos);
    });

    //validar y recolectar datos del form
    function savePvtGlobal(datos)
    {
        data = $("#table_field_pvt").handsontable('getData');
        estado = datos[9];
        $('input[name = pvt_table]').val(JSON.stringify(data));
        if(estado){
           $('#formPvtGlobal').submit();
        }
    }

    function dataTabla(){
        var evt = window.event || arguments.callee.caller.arguments[0];
        evt.preventDefault();
        let estado = true;
        data = $("#table_field_pvt").handsontable('getData');
        

        var pressure = [];
        var muO = [];
        var muG = [];
        var muW = [];
        var Bo = [];
        var Bg = [];
        var Bw = [];
        var Rs = [];
        var Rv = [];
        let conteoError = -9;
        for (var i = 0; i < data.length; i++) {

            pressure.push(data[i][0]);
            muO.push(data[i][1]);
            muG.push(data[i][2]);
            muW.push(data[i][3]);
            Bo.push(data[i][4]);
            Bg.push(data[i][5]);
            Bw.push(data[i][6]);
            Rs.push(data[i][7]);
            Rv.push(data[i][8]); 
            for (var a = 0; a <= 8; a++) {
                if(data[i][a] == 0 || data[i][a] == "" || data[i][a] == null ){
                    conteoError++;
                }
            }
        }
        console.log(conteoError);
        
              
        pressure.pop();
        muO.pop();
        muG.pop();
        muW.pop();
        Bo.pop();
        Bg.pop();
        Bw.pop();
        Rs.pop();
        Rv.pop();

        if(conteoError > 0 || pressure == '' || muO == '' || muG == '' || muW == '' || Bo == '' || Bg == '' || Bw == '' || Rs == '' || Rv == '')
        {
            $('#modal_error_js').modal();
            $('#alertErrorJS').html('');
            $('#alertErrorJS').append('<ul id="ulVerifyMineralogy"></ul>');
            if(conteoError < 1){
                conteoError = "All the";
            }
            $('#ulVerifyMineralogy').append('<li>'+conteoError+' fields of the pvt table can not be 0 or empty</li>');
            estado = false;
        }
        return [pressure, muO, muG, muW, Bo, Bg, Bw, Rs, Rv, estado];
    }

    //Grafica de valores de tabla pvt para campo
    function plot_fieldPVT() {
        datos = dataTabla();
        if(datos[9]){
            $('#pvtField').highcharts({
                title: {
                    text: 'Field\'s PVT',
                    x: -20 //center
                },
                xAxis: {
                    title: {
                        text: 'Pressure'
                    },
                    categories: datos[0]
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    valueSuffix: ''
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                        name: 'uo [cP]',
                        data: datos[1]
                    }, {
                        name: 'ug [cP]',
                        data: datos[2]
                    },
                    {
                        name: 'uw [cP]',
                        data: datos[3]
                    },
                    {
                        name: 'Bo [RB/STB]',
                        data: datos[4]
                    },
                    {
                        name: 'Bg [RCF/SCF]',
                        data: datos[5]
                    },
                    {
                        name: 'Bw [RB/STB]',
                        data: datos[6]
                    },
                    {
                        name: 'Rs [SCF/STB]',
                        data: datos[7]
                    },
                    {
                        name: 'Rv [STB/SCF]',
                        data: datos[8]
                    }
                ]
            });
        }
    }


    function tablaPvt()
    {
      data_pvt = $("input[name = pvt_table]").val();
      if (data_pvt === '') {
          data_pvt = [
              [null,null,null,null,null,null,null,null,null]
          ];
      } else {
          data_pvt = JSON.parse(data_pvt);
      }

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
