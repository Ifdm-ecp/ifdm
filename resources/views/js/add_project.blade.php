<script type="text/javascript">
    $(document).ready(function() {
        //Fecha actual
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var tod = year + "-" + month + "-" + day;

        var today = 0000 + "-" + 00 + "-" + 00;

        //Validar que la fecha escogida no sea mayor a la actual
        $("#date").change(function(e) {
            var date1 = $('#date').val();

            if (new Date(tod).getTime() - new Date(date1).getTime() < 0) {
                document.getElementById('date').value = today;
                $('#datea').modal('show');
            }
        });

        $("#myModal").modal('show');
    });
</script>