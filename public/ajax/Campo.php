<?php 
	require 'conn.php';	

	  $cuenca = $_GET['cuenca'];

	  $query = $con->query("SELECT * FROM Campo where CfCuenca = $cuenca");
        while ($row = mysqli_fetch_array($query) ) {
      ?>
      <option value=" <?php echo $row['ID']; ?> " >
        <?php echo $row['Nombre']; ?>
      </option>


      <?php
    }






?>