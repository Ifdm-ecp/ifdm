<script type="text/javascript">
var data = [];
var max; 
var min;
var sd;
var avg;

var sp=" ";
var nps = []; //Datos ordenados
var tam; //Tamaño
var p10;
var p90;
var p50;
var camposf;
var formacionf;
var parametrof;

google.load('visualization', '1', {packages: ['corechart', 'bar']});

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
        console.log(formaciones);
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
  if("{{Request::get('subp')}}" && "{{Request::get('statistical')}}") {
    var Sub = {{ Input::get('subp') ? Input::get('subp') : 'false' }};
    var statId = {{ Input::get('statistical') ? Input::get('statistical') : 'false' }};
    var Cam = [{{ $statistical !== 'false' ? $statistical->escenario->campo->id : 'false' }}];
  }

  setTimeout(function() {
    start(Sub, [Cam], statId);
  }, 500);

  $("#Basin").change(function(e) {
    var cuenca = e.target.value;
    basinf = cuenca;
    $('#Well').prop('disabled', true);
    $.get("{{url('campos')}}",
      {cuenca : cuenca},
      function(data){
        $("#Field").empty();
        $.each(data, function(index, value){
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

  $("#Field").change(function(e) {
    $('#Parameter').prop('disabled', false);
    $('#Well').prop('disabled', true);

    var campos = $('#Field').val();
    camposf=campos;

    $('#Mecanismos').prop('disabled',false);
    $('#Mecanismos').selectpicker('refresh');
    $('#Mecanismos').selectpicker('val', '');

    $.get("{!! url('formaciones2') !!}",
      {campos: campos},
      function(data){
        $("#Formation").empty();
        $.each(data, function(index, value){
          $("#Formation").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        });
      $("#Formation").selectpicker('refresh');
    });

    $('#Parameter').empty();
    $('#Parameter').prop('disabled', true);
    $('#Parameter').selectpicker('refresh');
  });

  $("#Formation").change(function(e)
  {
    $('#Parameter').selectpicker('deselectAll');
    $('#alert').html('<div class="alert alert-danger " role="alert"> <strong>Remember! </strong> please choose a subparameter</div>');
    $('#Parameter').selectpicker('val', '');
    $('#Well').prop('disabled',true);
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

  $("#Parameter").change(function(e)
  {
    $('#Well').prop('disabled',false);
    $('#alert').html('<div class="alert alert-warning" role="alert">    If you want to see frequency distribution for a <strong> specific well </strong> please choose one</div>');

    var parametro = $('#Parameter').val();
    var formacion = $('#Formation').val();
    start(parametro, camposf);

    $.get("{!! url('pozosFiltros') !!}",
      {
        campo:camposf
      },
      function(data){
        $("#Well").empty();

        $.each(data, function(index, value){
          $("#Well").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        });
        $("#Well").selectpicker('refresh');
        $('#Well').selectpicker('val', '');
      });
  });
    
  $("#Well").change(function(e)
  {
    var parametro = $('#Parameter').val();
    var formacion = $('#Formation').val();
    var parametro = $('#Parameter').val();
    var pozo = $('#Well').val();
    $('#alert').empty();
    startP(pozo,parametro,formacion,camposf);

  });
});

function startP(pozox, Subx, Forx, Camx)
{
  var max; 
  var min; 
  var sd; 
  var avg;
  var nps = [];
  var d;
  var tam;
  var p10;
  var p50;
  var p90;
  Forx = "Formation";
  $.get("{{'pozosFreq'}}",
  {
    pozo:pozox,
    sp: Subx
  },
  function(data)
  {
    $("#chart_div").empty();
    $("#chart2_div").empty();
    google.setOnLoadCallback(chart(data.Chart,sp,Subx,Camx));
    google.setOnLoadCallback(chart2(data.Chart,sp,Subx,Camx));
    $.each(data.General, function(index,value) {
      max = value.Maximo;
      min = value.Minimo;
      sd = value.SD;
      avg = value.Media;
      number_of_data = value.pb;
    });

    $.each(data.General, function(index,value)
    {

      d=parseFloat(value.valorchart);
      d = Math.round(d * 100) / 100;
      nps.push(d);

    });

    max = parseFloat(max);
    min = parseFloat(min);
    sd = parseFloat(sd);
    avg = parseFloat(avg);

    tam = nps.length;
    p10 = nps[Math.floor(tam*0.1)];
    p50 = nps[Math.floor(tam*0.5)];
    p90 = nps[Math.floor(tam*0.9)];

    $('#p10').text(p10);
    $('#p50').text(p50);
    $('#p90').text(p90);
    $('#len').text(number_of_data);

    $('#MaV').text(max.toFixed(2));
    $('#MiV').text(min.toFixed(2));
    $('#AVG').text(avg.toFixed(2));
    $('#SD').text(sd.toFixed(2));

    var campoz = ' ';
    var formacionz;
    var pozoz;

    $.get("{{'nombresp'}}",
      {
        parametro: Subx,
        pozo: pozox,
        campo :Camx
      },
      function(data)
      {

        $.each(data.campo, function(index,value)
        {
          campoz = value.nombre + ' - '+campoz;
          
        });
        $.each(data.NPozo, function(index,value)
        {
          pozoz = value.nombre;
        });

        $('#titulo').empty();
        //$('#titulo').html('Frequency Distribution & General Information <br/> <b>Field:</b>- '+ campoz +'  <b>Formation:</b> ' + formacionz+' - <b>Well:</b> ' + pozoz);
        $('#titulo').html('<b>Frequency Distribution & General Information</b> <br/><br/> <b>Field:</b>- '+ campoz +' <b>Well:</b> ' + pozoz +'<br/> <b>Subparameter:</b> ' +parametro);
      });
  });
}

function start(Subx, Camx, multi)
{
  nps = [];
  var d;
  $.get("{{'pozos'}}",
    {
      parametro: Subx,
      campo: Camx,
      multi: multi
    },
    function(data) {
      $("#chart_div").empty();
      $("#chart2_div").empty();

      google.setOnLoadCallback(chart(data.Chart,sp,Subx,Camx));
      google.setOnLoadCallback(chart2(data.Chart2,sp,Subx,Camx));

      $.each(data.datos, function(index, value) {
        if(value.statistical == 'undefined' || value.statistical == null)
        {
          campos_sp = value.field_statistical.split(",");
          $('#Basin').val(value.basin_statistical);
          $('#Basin').selectpicker('refresh');
          fillBasicSelectors(value.basin_statistical, campos_sp, Subx);
        }
      });

      $.each(data.General, function(index,value) {
        max = value.Maximo;
        min = value.Minimo;
        sd = value.SD;
        avg = value.Media;
      });

      $.each(data.Chart2, function(index,value) {
        d=parseFloat(value.valorchart);
        d = Math.round(d * 100) / 100;
        nps.push(d);
      });

      tam = 0;
      tam = nps.length;
      p10 = nps[Math.floor(tam*0.1)];
      p50 = nps[Math.floor(tam*0.5)];
      p90 = nps[Math.floor(tam*0.9)];

      $('#p10').text(p10);
      $('#p50').text(p50);
      $('#p90').text(p90);
      $('#len').text(tam);

      max = parseFloat(max);
      min = parseFloat(min);
      sd = parseFloat(sd);
      avg = parseFloat(avg);
      
      $('#MaV').text(max.toFixed(2));
      $('#MiV').text(min.toFixed(2));
      $('#AVG').text(avg.toFixed(2));
      $('#SD').text(sd.toFixed(2));

      var campoz = ' ';
      var formacionz;

      $.get("{{'nombresp'}}",
        {
          parametro: Subx,
          campo :Camx
        },
        function(data)
        {
          $.each(data.nombre, function(index,value)
          {
            parametro = value.nombre;
          });
          $.each(data.NCampo, function(index,value)
          {
            campoz = value.nombre + ' - '+campoz;
          });

          $('#titulo').empty();
          $('#titulo').html('<b>Frequency Distribution & General Information</b> <br/><br/> <b>Field:</b> '+ campoz+'<br/> <b>Subparameter:</b> ' +parametro);
      });
    });
}

function chart(datos, parametro, subx, camx)
{
  data = new google.visualization.DataTable();
  data.addColumn('number', 'Value');
  data.addColumn('number', 'Cumulative %');
  var aux=[];
  var m =[];
  var i=0;
  var c = 0;
  var c2 = 0;
  $.each(datos, function(index, value)
  {
    c2 = c2+value.Freq;
  });

  $.each(datos, function(index, value){
    c = c+value.Freq;
    var d = (c*100)/c2;
    d = parseFloat(d);
    d = Math.round(d * 100) / 100;
    //alert(value.valorchart);
    //alert(value.Freq+"%");
    aux = [value.valorchart,d];
    data.addRow(aux);
    //d=parseFloat(value.valorchart);
    //d = Math.round(d * 100) / 100;
    //nps.push(d);
  });  

  var options = {
    title: 'Cumulative frequency ',
    vAxis: {
      format: '#\'%\'',title:"Cumulative Frequency (%)",
      viewWindow: {
        min: 0,
        max: 100
      }
    },
    hAxis: {
      title: parametro
    },
    chart: {
      title: 'Cumulative distribution...',
      subtitle: 'Subparameter: '+parametro,
      histogram: { bucketSize: 10 }
    },
    legend: {position: 'left'}
  };

  var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));

  chart.draw(data, options);
}

function chart2(datos, parametro, subx) {
  $.get("{{'nombresp'}}",
  {
    parametro: subx
  },
  function(data)
  {
    $.each(data.nombre, function(index,value)
    {
      parametro = value.nombre;
    });

    data = new google.visualization.DataTable();
      
    data.addColumn('number', parametro);
    var aux=[];
    var m =[];
    var i=0;
    $.each(datos, function(index, value) {
      aux = [value.valorchart];
      data.addRow(aux);
    });

    var options = {
      title: 'Frequency distribution ',
      vAxis: {title:"Frequency"},
      hAxis: {title:parametro},
      chart:
      {
        title: 'Frequency distribution...',
        subtitle: 'Subparameter: '+parametro,
      },
      legend: {position: 'left'}
    };

    var chart = new google.visualization.Histogram(document.getElementById('chart2_div'));
    chart.draw(data, options);
  });
}
</script>