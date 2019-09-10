<script type="text/javascript">
    function show_modal(id_basin) {

        //Mostrar mensaje modal para borrar una cuenca. Se debe mandar el id de la cuenca unido al formulario para saber cual borrar
        var aux = 'form#form' + id_basin;

        $("#confirm_delete").modal('show');
        $('#confirm_delete').on('show.bs.modal', function(e) {
            $message = $(e.relatedTarget).attr('data-message');
            $(this).find('.modal-body p').text($message);
            $title = $(e.relatedTarget).attr('data-title');
            $(this).find('.modal-title').text($title);

            var form = $(e.relatedTarget).closest('form');
            $(this).find('.modal-footer #confirm').data('form', form);
        });

        $('#confirm_delete').find('.modal-footer #confirm').on('click', function() {


            $(aux).submit();

        });
    }
</script>