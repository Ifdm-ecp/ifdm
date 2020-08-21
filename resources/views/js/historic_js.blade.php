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
  var campo; 
  var formacion; 
  var parametrod;
  var campod;
  var formaciond;

  $(document).ready(function()
  {
    //Universo de datos escogido en Multiparamétrico

    if("{{Request::get('subp')}}" && "{{Request::get('statistical')}}") {
      var Sub = {{ Input::get('subp') ? Input::get('subp') : 'false' }};
      var For = {{ $statistical !== 'false' ? $statistical->escenario->formacionxpozo->id : 'false' }};
      var Poz = {{ $statistical !== 'false' ? $statistical->escenario->pozo->id : 'false' }};
      var Ca = [{{ $statistical !== 'false' ? $statistical->escenario->campo->id : 'false' }}];
      var x;
      $.get("{{url('historico')}}",
        {
          parametro : Sub,
          formacion: For,
          pozo: Poz,
          campo: Ca
        },
        function(data)
        {    

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
            formacion: For,
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
            $.each(data.formacion, function(index,value)
            {
              formacion = value.nombre;
            });
            $.each(data.NCampo, function(index,value)
            {
              campo = value.nombre;
            });
            chart(datos, freq, wellnames, parametro, campo, formacion, '0');
          });
        });
    }

    $("#Basin").change(function(e)
    {
      var cuenca = e.target.value;
      var basinf = cuenca;
      $('#Well').prop('disabled',true);
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
        $.get("{!! url('parametros2') !!}",
          function(data)
          {
            $("#Parameter").empty();
            $.each(data, function(index, value)
            {
              $("#Parameter").append('<option value="'+value.id+'">'+value.nombre+'</option>');
            });
            $("#Parameter").selectpicker('refresh');
      });
    });

    $("#Field").change(function(e)
    {
      $('#Parameter').prop('disabled',false);
      $('#Well').prop('disabled',true);
      $('#Parameter').selectpicker('refresh');
      $('#Parameter').selectpicker('val', '');

      var campos = $('#Field').val();
      camposf=campos;
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

      $('#Parameter').selectpicker('deselectAll');
      $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Remember! </strong> Please choose a subparameter</div>'); 
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
      var formacionx = $('#Formation').val();
      var campox = $('#Field').val();
      var pozox = $('#Well').val();
      var campod = " ";

      var datosn=[];
      var freqn=[];
      var wellnames = [];

      $.get("{{url('historico')}}",
        {
          parametro : parametrox,
          formacion: formacionx,
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
              formacion: formacionx,
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
              $.each(data.formacion, function(index,value)
              {
                formaciond = value.nombre;
              });
              chart(datosn, freqn, wellnames, parametrod, campod, formaciond, '0');
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
      var formacionx = $('#Formation').val();
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
          chart(datosn, freqn, wellnames, parametrod, campod, formaciond,pozod);
        });
      });
    });

    function chart(data, freq, wellnames, parametro, campo, formacion, pozo)
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