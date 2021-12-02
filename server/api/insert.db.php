<?php
    include "conexion.php";
    

    $res = $mysqli->query("SELECT * FROM operarios");
    
    while($row = $res->fetch_assoc()){
        print_r($row);
    }

    $sql = "INSERT INTO `operarios` (`legajo`, `Nombre`, `Apellido`, `Sector`, `Trabaja`) VALUES ";

    

    $sql.= "(\'502515\', \'Marcos\', \'Pereson\', \'Oficina\', \'1\');";


?>