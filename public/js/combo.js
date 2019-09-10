	
    $(document).ready(function(){
    $('input[type="submit"]').attr('disabled','disabled');

             $("#Cuenca").load("ajax/server/Cuenca.php");
             $("#Cuenca").change(function () {
                 var id = $("#Cuenca").val();
                 $.get("ajax/server/Campo.php", {campo_id: id}).done(function (data) {
                     $("#Campo").html(data);
                 })
             })

             $("#Campo").load("ajax/server/Campo.php");
             $("#Campo").change(function () {
                 var Sid = $("#Campo").val();
                 $.get("ajax/server/Parametro.php", {sector_id: Sid})
                         .done(function (data) {
                             $("#Parametro").html(data);
                         })
             })

             $("#Parametro").change(function(){
                 if($(this).val() != '') {
              $('input[type="submit"]').removeAttr('disabled');
                  }
             })

                });