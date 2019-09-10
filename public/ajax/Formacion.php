<?php 
	require 'conn.php';	

	  $campo = $_GET['campo'];

	  $query = $con->query("SELECT * FROM Formacion where CfCampo = $campo");
        while ($row = mysqli_fetch_array($query) ) {
      ?>
      <option value=" <?php echo $row['ID']; ?> " >
        <?php echo $row['Nombre']; ?>
      </option>


      <?php
    }






?>