<script type="text/javascript">
//Mostrar mensaje modal para borrar un escenario. Se debe mandar el id del escenario unido al formulario para saber cual borrar
    function Mostrar(a) {

        var aux = 'form#form' + a;

        $("#confirmDelete").modal('show');
        $('#confirmDelete').on('show.bs.modal', function(e) {
            $message = $(e.relatedTarget).attr('data-message');
            $(this).find('.modal-body p').text($message);
            $title = $(e.relatedTarget).attr('data-title');
            $(this).find('.modal-title').text($title);

            var form = $(e.relatedTarget).closest('form');
            $(this).find('.modal-footer #confirm').data('form', form);
        });

        $('#confirmDelete').find('.modal-footer #confirm').on('click', function() {
            $(aux).submit();
        });
    }

    $(document).ready(function() {
        $("#myModal").modal('show');
    });
</script>