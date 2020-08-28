<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script type="text/javascript">
  var datos = [];
  var freq = [];
  var wellnames = [];
  var cum = [];
  cum[0]=0;
  var i =0;
  var parametro;
  var parametrof;
  var campo; 
  var formacion; 
  var parametrod;
  var campod;
  var formaciond;

  function fillBasicSelectors(cuenca, formaciones = null, sub_sp = null) {
    $.get("{{url('campos')}}",
      {cuenca : cuenca},
      function(data)
      {
        $("#Field").empty();
        $("#Field").selectpicker('refresh');

        $.each(data, function(index, value)
          {
            $("#Field").append('<option value="'+value.id+'">'+value.nombre+'</option>');
          }
        );

        $("#Field").selectpicker('refresh');

        if (formaciones) {
          camposf = formaciones;
          $('#Field').val(formaciones);
          $('#Field').selectpicker('refresh');
        }
      }
    );

    $.get("{!! url('mecan_dano') !!}",
      function(data)
      {
        $("#Mecanismos").empty();

        $.each(data, function(index, value)
          {
            $("#Mecanismos").append('<option value="'+value.id+'">'+value.nombre+'</option>');
          }
        );
        $("#Mecanismos").selectpicker('refresh');
        $('#Mecanismos').selectpicker('val', '');

        if (sub_sp) {
          parametrof = sub_sp;
          var mecdan_id = 0;

          if (sub_sp >= 1 && sub_sp <= 5) {
            mecdan_id = 1;
          } else if (sub_sp >= 6 && sub_sp <= 10) {
            mecdan_id = 2;
          } else if ((sub_sp >= 11 && sub_sp <= 14) || sub_sp == 30) {
            mecdan_id = 3;
          } else if (sub_sp >= 15 && sub_sp <= 18) {
            mecdan_id = 4;
          } else if (sub_sp >= 19 && sub_sp <= 22) {
            mecdan_id = 5;
          } else if (sub_sp >= 23 && sub_sp <= 26) {
            mecdan_id = 6;
          }

          $('#Mecanismos').prop('disabled', false);
          $('#Parameter').prop('disabled', false);

          $('#Mecanismos').val(mecdan_id);
          $('#Mecanismos').selectpicker('refresh');

          fillParameters(mecdan_id);
        }
      }
    );
  }

  function fillParameters(mec) {
    $.get("{!! url('parametros') !!}",
      {mec:mec},
      function(data)
      {
        $("#Parameter").empty();

        $.each(data, function(index, value)
        {
          $("#Parameter").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        });

        $("#Parameter").append('</optgroup>');

        $.get("{{url('variables_dano')}}",
          {mec:mec},
          function(data)
          {
            $("#Parameter").append('<optgroup label="Another Damage Variables" disabled>');
            $.each(data, function(index, value){
                $("#Parameter").append('<option value="'+value.nombre+'">'+value.nombre+'</option>');
            });
            $("#Parameter").append('</optgroup>');
            $("#Parameter").selectpicker('refresh');
            $('#Parameter').selectpicker('val', '');

            if (parametrof) {
              $('#Parameter').val(parametrof);
              $('#Parameter').selectpicker('refresh');
              parametrof = null;
            }
          });
      }
    );
  }

  $(document).ready(function()
  {
    //Universo de datos escogido en Multiparamétrico

    if("{{Request::get('subp')}}" && "{{Request::get('statistical')}}") {
      var Sub = {{ Input::get('subp') ? Input::get('subp') : 'false' }};
      var statId = {{ Input::get('statistical') ? Input::get('statistical') : 'false' }};
      var Poz = {{ $statistical !== 'false' ? $statistical->escenario->pozo->id : 'false' }};
      var Ca = [{{ $statistical !== 'false' ? $statistical->escenario->campo->id : 'false' }}];
      var x;
      $.get("{{url('historico')}}",
        {
          parametro : Sub,
          pozo: Poz,
          campo: Ca,
          multi: statId
        },
        function(data)
        {
          $.each(data.datos, function(index, value)
          {
            if(value.statistical == 'undefined' || value.statistical == null)
            {
              campos_sp = value.field_statistical.split(",");
              $('#Basin').val(value.basin_statistical);
              $('#Basin').selectpicker('refresh');
              fillBasicSelectors(value.basin_statistical, campos_sp, Sub);
            }
          });

          $.each(data.Chart, function(index, value)
          {
            x=value.valorchart;
            x = parseFloat(x);
            x = Math.round(x * 100) / 100;
            datos.push(x);
            freq.push(value.fecha);
            wellnames.push(value.nombre);
          });

          $.get("{{'nombresp'}}",
            {
            parametro: Sub,
            pozo: Poz,
            campo: Ca
            },
            function(data)
            {
            $.each(data.nombre, function(index,value)
            {
              parametro = value.nombre;             
            });
            $.each(data.campo, function(index,value)
            {
              campo = value.nombre;
            });
            $.each(data.NCampo, function(index,value)
            {
              campo = value.nombre;
            });
            chart(datos, freq, wellnames, parametro, campo, '0');
          });
        });
    }

    $("#Basin").change(function(e)
    {
      var cuenca = e.target.value;
      var basinf = cuenca;
      $('#Well').prop('disabled', true);
      $.get("{{url('campos')}}",
        {cuenca : cuenca},
        function(data)
        {
          $("#Field").empty();
          $.each(data, function(index, value)
          {
            $("#Field").append('<option value="'+value.id+'">'+value.nombre+'</option>');
          });
          $("#Field").selectpicker('refresh');
        });

        $.get("{!! url('mecan_dano') !!}",
          function(data)
          {
            $("#Mecanismos").empty();

            $.each(data, function(index, value)
              {
                $("#Mecanismos").append('<option value="'+value.id+'">'+value.nombre+'</option>');
              }
            );
            $('#Mecanismos').selectpicker('refresh');
            $('#Mecanismos').selectpicker('val', '');
          }
        );

        $('#Parameter').empty();
        $('#Parameter').prop('disabled', true);
        $('#Parameter').selectpicker('refresh');

        $('#Mecanismos').prop('disabled', true);
        $('#Mecanismos').selectpicker('val', '');
    });

    $("#Field").change(function(e)
    {
      $('#Parameter').prop('disabled', false);
      $('#Well').prop('disabled', true);

      var campos = $('#Field').val();
      camposf=campos;

      $('#Mecanismos').prop('disabled',false);
      $('#Mecanismos').selectpicker('refresh');
      $('#Mecanismos').selectpicker('val', '');

      $.get("{!! url('formaciones2') !!}",
        {campos: campos},
        function(data)
        {
          $("#Formation").empty();
          $.each(data, function(index, value)
          {  
            $("#Formation").append('<option value="'+value.id+'">'+value.nombre+'</option>');
          });
          $("#Formation").selectpicker('refresh');
        });

      $('#Parameter').empty();
      $('#Parameter').prop('disabled', true);
      $('#Parameter').selectpicker('refresh');
    });

    /**
    * Acción del filtro Mecanismos que consulta los mecanismos de daño en la base de datos y los lista para acotar la búsqueda de 
    * una variable de daño o configuración del daño. 
    */
    $("#Mecanismos").change(function(e)
    {
      var mec = $('#Mecanismos').val();
      $('#Parameter').prop('disabled',false);

      $('#Parameter').selectpicker('deselectAll');
      $("#Parameter").append('<optgroup label="Sub-Parameters">');

        $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> Please choose a Damage Variable</div>');

      fillParameters(mec);
    });

    $("#Formation").change(function(e)
    {
      $('#Parameter').selectpicker('deselectAll');
      $('#alert').html('<div class="alert alert-danger " role="alert"> <strong>Remember! </strong> Please choose a subparameter</div>');
      $('#Parameter').selectpicker('val', '');
      $('#Well').prop('disabled',true);
    });

    $("#Parameter").change(function(e)
    {
      $('#Well').prop('disabled',false);
      $('#alert').html('<div class="alert alert-warning" role="alert">    If you want to see historic data for a <strong> specific well </strong> Please choose one</div>');

      var parametrox = $('#Parameter').val();
      var campox = $('#Field').val();
      var pozox = $('#Well').val();
      var campod = " ";

      var datosn=[];
      var freqn=[];
      var wellnames = [];

      $.get("{{url('historico')}}",
        {
          parametro : parametrox,
          campo: campox
        },
        function(data)
        {
          $.each(data.Chart, function(index, value)
          {
            x=value.valorchart;
            x = parseFloat(x);
            x = Math.round(x * 100) / 100;
            datosn.push(x);
            freqn.push(value.fecha);
            wellnames.push(value.nombre);
          });

          $.get("{{'nombresp'}}",
            {
              parametro: parametrox,
              campo:campox 
            },
            function(data)
            {
              $.each(data.nombre, function(index,value)
              {
                parametrod = value.nombre; 
              });
              $.each(data.NCampo, function(index,value)
              {
                campod = value.nombre + ' - '+campod;
              });
              chart(datosn, freqn, wellnames, parametrod, campod, '0');
            });
      });

      //Consultas pozos dependientes de filtro
      $.get("{!! url('pozosFiltros') !!}",
      {
        campo:camposf
      },
      function(data)
      {
        $("#Well").empty();
        $.each(data, function(index, value)
        {
          $("#Well").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        });
        $("#Well").selectpicker('refresh');
        $('#Well').selectpicker('val', '');
      });
    });

    $('#Well').change(function(e)
    {
      var parametrox = $('#Parameter').val();
      var pozox = $('#Well').val();
      var datosn = [];
      var freqn = [];
      var wellnames = [];
      var pozod;
      $("#alert").empty();

      $.get("{!! url('pozosHist') !!}",
      {
        pozo: pozox,
        parametro: parametrox
      },
      function(data)
      {
        $.each(data, function(index, value){
            x=value.valor;
            x = parseFloat(x);
            x = Math.round(x * 100) / 100;
            datosn.push(x);
            freqn.push(value.fecha);
            wellnames.push(value.nombre);
        });
        $.get("{{'nombresp'}}",
        {
          pozo: pozox,
        },
        function(data)
        {
          $.each(data.NPozo, function(index,value)
          {
            pozod = value.nombre; 
          });
          $.each(data.campo, function(index,value)
          {
            campod = value.nombre;                
          });
          chart(datosn, freqn, wellnames, parametrod, campod, pozod);
        });
      });
    });

    function chart(data, freq, wellnames, parametro, campo, pozo)
    {
      var dataMatrix = [];
      for (i = 0; i < data.length; i++) {
        date = new Date(freq[i])
        date = Date.UTC(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate());
        dataMatrix.push({x: date, y: data[i], wellName: wellnames[i], trueDate: freq[i]});
      }

      var tx;
      if(pozo == '0')
      {
        //$('#titulo').html('Historic Data </br> '+'<b>Field:</b> '+campo +'  <b>Formation:</b> '+formacion);
        $('#titulo').html('<b>Historic Data</b> </br> '+'</br><b>Field:</b> '+campo+'</br>  <b>Subparameter:</b> '+parametro);
        //tx = '<b>Field:</b> '+campo +' - <b>Formation:</b> '+formacion;
        tx = '<b>Field:</b> '+campo+'  <b>Subparameter:</b> '+parametro;
      }
      else
      {
        //$('#titulo').html('Historic Data </br> '+'<b>Field:</b> '+campo +'  <b>Formation:</b> '+formacion+'- <b>Well:</b> '+pozo);
        $('#titulo').html('Historic Data </br> '+'<b>Field:</b> '+campo +'- <b>Well:</b> '+pozo+'- <b>Subparameter:</b> '+parametro);
        //tx = '<b>Field:</b> '+campo +' - <b>Formation:</b> '+formacion+'- <b>Well:</b> '+pozo;
        tx = '<b>Field:</b> '+campo +'- <b>Well:</b> '+pozo+'- <b>Subparameter:</b> '+parametro;
      }
      $('#container').highcharts(
      {
        title: 
        {
          text: tx,
          style: 
          {
            fontSize: '2.2vmin',
            color: '#1381bc',
            lineHeight: '18em' //This line did the trick
          }
        },
        yAxis:
        {
          title:
          {
            text: parametro,
            margin: 40
          }
        },
        xAxis: 
        {
          type: 'datetime',
          labels: {
            format: '{value:%Y-%m-%d}'
          },
          title: {
            text: 'Date'
          }
        },
        tooltip: {
          formatter: function() {
            return "<b>Date:</b> " + this.point.trueDate +
              "<br><b>Well name:</b> " + this.point.wellName +
              "<br><b>"+ parametro + ":</b> " + this.point.y;
          }
        },
        labels: 
        {
          items: 
          [{
            html: '',
            style: 
            {
              left: '50px',
              top: '18px',
              color: (Highcharts.theme && Highcharts.theme.textColor) || 'red'
            }
          }]
        },
        series: 
        [{
          type: 'spline',
          name: parametro,
          // keys: ['x', 'y', 'wellName'],
          data: dataMatrix,
          marker: 
          {
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'white'
          }
        }],

      });
    }
  });
</script>