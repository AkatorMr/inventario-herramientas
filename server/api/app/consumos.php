<?php

include_once("Router.php");

$router = Router::getInstance();



function ListarPedidos()
{
    $sql = "SELECT id_solicitud_compra AS pedido, COUNT( id_solicitud_compra ) AS cantidaditems ";
    $sql .= "FROM `solicitudes` ";
    $sql .= "WHERE `estado` <> 'AGREGAR' ";
    $sql .= "AND `estado` <> 'ELIMINADO' ";
    $sql .= "GROUP BY id_solicitud_compra ";
    $sql .= "ORDER BY id_solicitud_compra DESC;";

    return SFr($sql);
}


function ListarConsumosEstadistica($_ARGS)
{

    $sub_comando = $_ARGS["nivel"]; // Mediante GET
    $codigo = $_ARGS["codigo"];
    $descripcion = $_ARGS["descripcion"];


    $inicio = $sub_comando * 15;
    $sql = "SELECT herramientas.Codigo, herramientas.Descripcion AS Descripcion, SUM(con.cantidad) as Cantidad";
    $sql .= " FROM `consumos` con";
    $sql .= " INNER JOIN `herramientas` ON (con.cod_herramienta = herramientas.Codigo)";
    $sql .= " WHERE herramientas.Codigo LIKE '%$codigo%'";
    $sql .= " AND herramientas.Descripcion LIKE '%$descripcion%'";
    $sql .= " AND MONTH(con.fecha_consumido) = MONTH(CURRENT_TIMESTAMP) AND YEAR(con.fecha_consumido) = YEAR(CURRENT_TIMESTAMP)";
    $sql .= " GROUP BY herramientas.Codigo";
    $sql .= " ORDER BY Cantidad DESC LIMIT $inicio,15;";

    //echo $sql;        
    return SFr($sql);
}



function ListarConsumos($_ARGS)
{

    $sub_comando = $_ARGS["nivel"]; // Mediante GET
    $legajo = $_ARGS["legajo"];
    $codigo = $_ARGS["codigo"];
    $nombre = $_ARGS["nombre"];
    $descripcion = $_ARGS["descripcion"];


    $inicio = $sub_comando * 15;
    $sql = "SELECT operarios.Legajo, herramientas.Codigo, herramientas.Descripcion AS Descripcion, operarios.Nombre AS Nombre, operarios.Apellido AS Apellido, SUM(con.cantidad) as Cantidad, con.fecha_consumido AS Fecha";
    $sql .= " FROM `consumos` con";
    $sql .= " INNER JOIN `herramientas` ON (con.cod_herramienta = herramientas.Codigo)";
    $sql .= " INNER JOIN `operarios` ON con.legajo_operario = operarios.legajo";
    $sql .= " WHERE herramientas.Codigo LIKE '%$codigo%'";
    $sql .= " AND herramientas.Descripcion LIKE '%$descripcion%'";
    $sql .= " AND operarios.Legajo LIKE '%$legajo%'";
    $sql .= " AND (operarios.Nombre LIKE '%$nombre%'";
    $sql .= " OR operarios.Apellido LIKE '%$nombre%')";
    $sql .= " GROUP BY herramientas.Codigo, operarios.Legajo";
    $sql .= " ORDER BY operarios.Legajo, herramientas.Codigo LIMIT $inicio,15;";

    //MPLog($sql);

    $sql2 = "SELECT h.Descripcion AS Descripcion, o.Nombre AS Nombre, o.Apellido AS Apellido";
    $sql2 .= " FROM `consumos` con, `herramientas` h, `operarios` o";
    $sql2 .= " WHERE con.cod_herramienta = h.Codigo AND con.legajo_operario = o.legajo;";

    return SFr($sql);

}

function GenerarConsumo($_ARGS)
{

    global $mysqli;

    $legajo = $_ARGS["legajo"];
    $a_codigo = $_ARGS["codigo"]; //Es un array
    $a_cantidad = $_ARGS["cantidad"]; // Es un array
    if(!isset($_ARGS["estado"]) && !empty($_ARGS["estado"]))
        $a_estado = $_ARGS["estado"]; //Es un array

    $nota = "";
    
    if(isset($_ARGS["nota"]) && !empty($_ARGS["nota"]))
        $nota = $_ARGS["nota"]; //La nota debe ser por cada consumo
    
    $vale_oracle = $_ARGS["valeoracle"];

    $vale_fecha = $_ARGS["valefecha"];

    //include "editar.xlsx.php";
    $fecha_consumo = date("Y-m-d");
    //MPLog($vale_fecha);
    //$tiempo  = DateTime::createFromFormat('d/m/Y', $vale_fecha);
    //MPLog($tiempo);
    //echo json_encode([$tiempo,$vale_fecha);
    //exit();
    //MPLog($fecha_consumo);
    //if($tiempo instanceof DateTime){
    if (!empty($vale_fecha)) {
        //$fecha_consumo = date("Y-m-d",$tiempo);
        $fecha_consumo = $vale_fecha;
    }
    //MPLog($fecha_consumo);
    //echo json_encode($fecha_consumo);
    //exit();

    $sql = "INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`) VALUES (\'502514\', \'CINTA_11_0011\', \'2021-11-25\', \'CONSUMIDA\', \'0000-00-00\', \'0\', \'0000-00-00\');";
    $s = "";
    $c = true;
    $msg = "";
    for ($i = 0; $i < count($a_codigo); $i++) {
        $codigo = $a_codigo[$i];
        $cantidad = $a_cantidad[$i];

        $estado = "CONSUMIDA";
        if(isset($a_estado))
            $estado = $a_estado[$i];

        //Esto vale oro
        $sql = "SELECT `solicitudes`.`id` AS ide , `solicitudes`.`cod_herramienta` , `operarios`.`Nombre` AS `nombre` FROM `solicitudes`
            INNER JOIN `operarios` ON `solicitudes`.`legajo_operario` = `operarios`.`legajo` 
            WHERE ( `nombre`='Almacen' OR `operarios`.`legajo` = '000000' OR `operarios`.`legajo` = '$legajo' )
            AND `solicitudes`.`estado` = 'DISPONIBLE' 
            AND `solicitudes`.`cod_herramienta`='$codigo' 
            LIMIT 1;";
        //error_log($sql);

        //Ahora hay un bug con la cantidad de elementos que por ahora no detecta y no tengo manera sencilla de solucionarlo,
        // deberia cambiar la tabla y poner que sea con cantidad y no por unidad
        $res = $mysqli->query($sql);
        //print_r($res);
        //echo $res->num_rows;
        if ($res->num_rows > 0) {
            //Hacer un update 
            //echo "Realiza un update";
            $row = $res->fetch_assoc();
            $ide = $row['ide'];
            $msg = "Solicitud actualizada";
            $sql = "UPDATE `solicitudes` SET `legajo_operario` = '$legajo',`estado`='CONSUMIDA' WHERE `id`='$ide';";

        } else {
            //Hacer un insert de la solicitud
            $sql = "INSERT INTO `solicitudes` (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`) VALUES ('$legajo', '$codigo', '$fecha_consumo', 'CONSUMIDA', '$fecha_consumo', '0', '$fecha_consumo');";
            $msg = "Solicitud Creada";

        }
        if (IN($sql)) {
            $sql_2 = "INSERT INTO consumos (legajo_operario,cod_herramienta,cantidad,fecha_consumido,nota,estado,vale_oracle)VALUES('$legajo','$codigo','$cantidad','$fecha_consumo','$nota','$estado','$vale_oracle')";
            //MPLog($sql_2);
            if (IN($sql_2)) {
                $c = true;
                $s .= "1";
                $msg .= "\nConsumo generado";
                continue;
            }
        }
        $c = false;
        $s = $sql;
    }
    if ($c) {
        return json_encode(array($msg,"ok"));
    }
    return json_encode(array("error","error"));

}


$router->addApiEntry("ListarPedidos", "ListarPedidos");
$router->addApiEntry("GenerarConsumo", "GenerarConsumo");
$router->addApiEntry("ListarConsumos", "ListarConsumos");
$router->addApiEntry("ListarConsumosEstadistica", "ListarConsumosEstadistica");

?>