      <option selected disabled>Basin</option>
      <?php 
        require 'conn.php'; 

        $query = $con->query("SELECT * FROM Cuenca") or die(mysqli_error($conn));
        while ($row = mysqli_fetch_array($query) ) {
      ?>
      <option value=" <?php echo $row['ID']; ?> " >
        <?php echo $row['Nombre']; ?>
      </option>


      <?php
    }

      ?>