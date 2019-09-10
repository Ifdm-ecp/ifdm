<script type="text/javascript">

//Mostrar mensaje modal para borrar un campo. Se debe mandar el id del campo unido al formulario para saber cual borrar
function Mostrar(a) { 
  var aux = 'form#form'+a;
  $("#confirmDelete").modal('show');
  $('#confirmDelete').on('show.bs.modal', function (e) {
    $message = $(e.relatedTarget).attr('data-message');
    $(this).find('.modal-body p').text($message);
    $title = $(e.relatedTarget).attr('data-title');
    $(this).find('.modal-title').text($title);

    var form = $(e.relatedTarget).closest('form');
    $(this).find('.modal-footer #confirm').data('form', form);
  });

  $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
    $(aux).submit();
  }); 
}

  $(document).ready(function(){
    //Funcionalidad filtros anidados
    $("#myModal").modal('show');

    $('#field').prop('disabled',true);
    $("#basin").change(function(e){
      $('#field').prop('disabled',true);
    var basin = $('#basin').val();
      $.get("{{url('fieldbybasin')}}",
      {basin : basin},
      function(data){
        $("#field").empty();
        $.each(data, function(index, value){
          $('#field').prop('disabled',false);
          $("#field").append('<option value="'+value.id+'">'+value.nombre+'</option>');
        });
          $("#field").selectpicker('refresh');
          $('#field').selectpicker('val', '');
      });
    });

    //Generar tabla de campos de acuerdo a los filtros escogidos
    $("#field").change(function(e){
    var field = $('#field').val();
      $.get("{{url('fieldbasin')}}",
      {field : field},
      function(data){
        var  k ="";
        $.each(data, function(index, value){
          var aux = value.id;
          k = k.concat("<tr> <td>"+ value.nombre +"</td><td>  <form method=\"POST\" action=\"{{ URL::route('listFieldC.destroy', "xxx") }}\"   id=\"form"+aux+"\"><input name=\"_method\" type=\"hidden\" value=\"DELETE\"><input name=\"_token\" type=\"hidden\" value=\"<?php echo csrf_token() ?>\"> <div class=\"form-inline\"><a href=\"{{ URL::route('listFieldC.edit', "yyy") }}\" class=\"btn btn-warning\">Manage</a><button class=\"btn btn-danger\" type=\"button\" data-toggle=\"modal\" OnClick=\"javascript: Mostrar("+aux+");\">Delete</button></div></form></td></tr>");
          k = k.replace("xxx",aux);
          k = k.replace("yyy",aux);
          k = k.replace("xyx",aux);

        });
          $('#table').html("<table class=\"table table-striped\"><tr><th>Name</th><th>Actions</th></tr>"+k+"</table>");
     });
    });

  });
</script>