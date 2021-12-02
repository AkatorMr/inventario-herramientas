<?php 
    $mysqli = new mysqli("localhost","root","usbw","inventario");

    function SF($sql){
        
        global $mysqli;
        $res = $mysqli->query($sql);
        
        $rows = array();
        
        while($row = $res->fetch_assoc()){
            $rows[] = $row;
        }

        echo json_encode($rows);
    }
    function IN($sql){
        global $mysqli;
        $res = $mysqli->query($sql);
        
       return $res;
    }
?>