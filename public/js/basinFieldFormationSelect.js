$(document).ready(function(){
  cargarBasin();
});

$('#basin').change(function(){
  var basin = $(this).val();
  selectAjax('/basinField/'+basin, 'field', true);
});

$('#field').change(function(){
  var field = $(this).val();
  selectAjax('/fieldFormation/'+field, 'formation', false);
});

function cargarBasin()
{
  var basin = $('#basin').val();
  var cargar = selectAjax('/basinField/'+basin, 'field', true);
}

function cargarField()
{
  var field = $('#field').val();
  selectAjax('/fieldFormation/'+field, 'formation', false);
}

function selectAjax(ruta, elemento, cargar)
{
  //alert(ruta);
  $.getJSON(ruta, function(data){
      //console.log(data);
      $("#"+elemento).empty();
      $.each(data, function(index, value) {
          $("#"+elemento).append('<option value="' + value.id + '">' + value.nombre + '</option>');
      });
      //$('#'+elemento).append('response');
      $("#"+elemento).selectpicker('refresh');
      if(cargar == true)
      {
        cargarField();
      }
  });
}