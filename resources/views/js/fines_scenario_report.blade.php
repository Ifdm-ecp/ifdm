<script type="text/javascript">


    /* Descripción: define sección que se incluye en el pdf de descarga del reporte*/
    function download()
    {
        //console.log("A");
        var divElements = document.getElementById('report').innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML = 
          "<html><head><title></title></head><body>" + 
          divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
        location.reload();
    }


</script>