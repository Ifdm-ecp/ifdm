$('#save').on('click', function(e){
	e.preventDefault();
	validarErrors();
});

function validarErrors()
{	var errors = [];
	var errors = sumarMinerales(errors);
	var errors = sumarZeolites(errors);
	
	if(errors.length > 0){
      $('#modal_error_js').modal();
      $('#alertErrorJS').html('');
      $('#alertErrorJS').append('<ul id="ulVerifyMineralogy"></ul>');
       	$.each(errors, function( index, value ) {
	      $('#ulVerifyMineralogy').append(value);
	    });
	}else{
		$('#formFts').submit();
	}
   
}

function sumarMinerales(errors){
  	var suma = obtenerData('#mineral  input[type=text]');
    if(suma != 100)
    {
      errors.push('<li>the sum of the Minerals is '+suma+'% .</li>');
    }

    return errors;
};

function sumarZeolites(errors)
{
	var suma = obtenerData('.zeolites');
    if(suma > 5)
    {
      errors.push('<li>the sum of the Zeolites is '+suma+' .</li>');
    }

    return errors;
}

function obtenerData(elemento)
{
	var data = [];
    var suma = 0;

    $(elemento).each(function() {
        data.push($(this).val());
    });

    $.each(data, function( index, value ) {
      if(value == ''){value = 0;}                
      suma = parseFloat(suma)+parseFloat(value);
    });

    return suma;

}

function cargarFines()
{
	$('#mineral  input[type=text]').val(0);
	
	var data = $('input[name=escenario_id]').val();
	var url = '/fines-fts/'+data;
	$.get(url, function(data){
		data = JSON.parse(data);
		$.each(data, function( index, value ){
			var name = value.finestraimentselection; 
			$('#mineral input[name='+name.toLowerCase()+']').val(value.percentage);
		});
	});

}
	 
$('.input-group').prepend('<span class="input-group-btn"><button type="button" class="btn btn-default button-advisor conTooltip"><span class="glyphicon glyphicon-info-sign"></span></button></span>');