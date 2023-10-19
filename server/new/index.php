<?php

$mysqli = new mysqli("localhost","root","usbw","inventario");

if(!empty($_GET["last"])){
    echo "26165";
    exit();
}

$data = file_get_contents('php://input');
$json = json_decode($data);

$f = fopen("log.txt","a+");

//fputs($f,$json);

$sql_multi = "";

foreach ($json as $key => $value) {
    //Cada $value es un array
    //fputs($f,print_r($value,true));

    $sql = "INSERT INTO datos ('Id_Request', 'Description', 'UserName', 'Departament', 'Id_Req_Status', 'Id_Status', 'Coments') ";
    $sql.= "VALUES ($value[0], '$value[1]', '$value[2]', '$value[3]', $value[4], $value[5], '$value[6]');";

    $sql_multi.=$sql;
    //$res = $mysqli->query($sql);

}
echo $sql_multi;
$mysqli->multi_query($sql_multi);

//fputs($f,print_r($json,true));cls
//fputs($f,print_r($_POST,true));

fclose($f);

?>