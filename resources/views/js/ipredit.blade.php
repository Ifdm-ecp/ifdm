<script type="text/javascript">
    
    function enviar(){
      var datos_unidades_hidraulicas = $hot.getData();
      $("#presiones_table").val(JSON.stringify(datos_unidades_hidraulicas));       
      var form = $(this).parents('form:first');
      form.submit();       
      return false;
    }
</script>
<!-- HandsOnTable JS -->
<script src="{{ asset('js/handsontable/dist/handsontable.full.js') }}"></script>
<!-- HandsOnTable CSS -->
<link rel="stylesheet" media="screen" href="{{ asset('js/handsontable/dist/handsontable.full.css') }}">