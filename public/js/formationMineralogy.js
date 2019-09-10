function cargarDataSelect(estado, elemento, selected)
{   
  var dataSelect = ['Albite','Analcime','Anhydrite', 'Ankeritec','AnkeriteIM','Baryte',  'Bentonite','Biotite', 'Brucite','Calcite','Cast', 'Celestine',  'Chloritec','Chamosite','Chabazite','ChloriteIM','Chloritem', 'Dolomite', 'Emectite', 'Gibbsite','Glauconite', 'Halite', 'Hematite','Heulandite','Illite', 'Kaolinite', 'Magnetite', 'Melanterite','Microcline', 'Muscovite', 'Natrolite','Orthoclase', 'PlagioClase','Pyrite', 'Pyrrhotite', 'Sideritec', 'SideriteIM', 'Stilbite', 'Troilite','Quartz'];

  if(estado == true)
  {
    var selectRemove = []
    $('table > tbody > tr > td select').each(function() {
      selectRemove.push($(this).val());
    });
    dataSelect = $(dataSelect).not(selectRemove);
  }

  $(elemento).empty();
  $.each(dataSelect, function(index, value) {
      $(elemento).append('<option value="' + value + '">' + value + '</option>');
  });
  $(elemento).selectpicker('refresh');
  $(elemento).selectpicker('val', selected);
} 

$('#save').on('click', function(e)
{
  e.preventDefault();
  var verify = [];
  var errores = [];
  repeat = finesUnique(verify);
  var cifm = changeInputFormationMineralogy(true);
  var sm = sumarMinerales(true);
  if(cifm == 0 && sm == 0 && repeat.length == 0){
    $("#form").submit();
  }else{
    if(cifm > 0)
    {
      verify.push('<li>the sum of the Minerals in the section "Formation Mineralogys" is below 100% with a difference of '+cifm+' %.</li>');
    }else if(cifm < 0){
      verify.push('<li>The sum of the Minerals in the section "Formation Mineralogys" is above 100% with a difference of '+cifm+' %.</li>');
    }

    if(sm > 0)
    {
      verify.push('<li>the sum of the Minerals in the section "Fines Percentage" is below 100% with a difference of '+sm+' %.</li>');
    }else if(sm < 0){
      verify.push('<li>The sum of the Minerals in the section "Fines Percentage" is above 100% with a difference of '+sm+' %.</li>');
    }
    errores.push(repeat);
    errores.push(verify);

    $('#modal_error_js').modal();
    $.each(errores, function( index, value ) {
      $('#alertErrorJS').html('');
      $('#alertErrorJS').append('<ul id="ulVerifyMineralogy"></ul>');
      $('#ulVerifyMineralogy').append(value);
    });
  }
});

$('input[name=quarts], input[name=feldspar], input[name=clays]').change(function(){
    changeInputFormationMineralogy(false);
});

function changeInputFormationMineralogy(estado)
{
    q = $('input[name=quarts]').val();
    f = $('input[name=feldspar]').val();
    c = $('input[name=clays]').val();
    var data = [q,f,c];
    var suma = plusMineralogy(data, 'mineralogyPercentage', estado);
    return alertPercentage(suma, estado);
}

function sumarMinerales(estado){
  var data = [];
    $('table > tbody > tr input[type=text]').each(function() {
        data.push($(this).val());
    });
    var suma = plusMineralogy(data, 'finesMineralogyPercentage', estado);
    return alertPercentage(suma, estado);
};

function plusMineralogy(datos, elementoSelect)
{
    var suma = 0;
    $.each(datos, function( index, value ) {
      if(value == ''){value = 0;}                
      suma = parseFloat(suma)+parseFloat(value);
    });

    $('#'+elementoSelect).text(suma+'%.'); 
    return suma;
}

function alertPercentage(suma, estado){
  if(estado == true)
  {
    var total = 100-suma;
    if(total < 0 || total > 0)
    {
      return total;
    }else{
      return 0;
    }
  }
}

function finesUnique(verify)
{
  var selects = [];
  $('table > tbody > tr > td select').each(function() {
    elemento = $(this).val();
    $.each(selects, function(index, value){
      if(elemento == value)
      {
        $('table > tbody > tr > td select[value='+value+']').attr('data-style','btn-danger');
        verify.push('<li>"'+value+' " is repeated.</li>');
      }
    });
    selects.push(elemento);
  });

  return verify;
}

//FUNCION PARA BORRAR LOS ROWS AGREGADOS EN LA TABLA MINERALOGY
$(document).on('click', '.borrar', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
  sumarMinerales(true);
});