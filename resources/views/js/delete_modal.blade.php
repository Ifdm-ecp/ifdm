<script type="text/javascript">
    function delete_modal(form_id, form_name) {
        //Mostrar mensaje modal para borrar. Se debe mandar el id unido al formulario para saber cual borrar
        $('#modal_message').empty();
        $('#modal_message').append("Do you want to delete this " + form_name + "?");
        var aux = 'form#form' + form_id;
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