<script type="text/javascript">
//Mostrar mensaje modal para borrar un usuario. Se debe mandar el id del usuario unido al formulario para saber cual borrar
    function Mostrar(a) {
        $('#formDelete').attr('action', window.location+'/'+a);
        $('#confirmDelete').modal();
    }

    $(document).ready(function() {
        $("#myModal").modal('show');
    });
</script>