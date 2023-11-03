<?php

include_once("Router.php");

$router = Router::getInstance();


$ListarCodigos = function()
{
    return SFr("SELECT `Codigo`,`Descripcion` FROM `herramientas`");
};




function MostrarDetalles($_ARGS)
{

    $nroPedido = $_ARGS["nroPedido"];

    $sql = "SELECT sol.cod_herramienta AS codigo, her.Descripcion as descripcion, COUNT(sol.cod_herramienta) as cantidad ";
    $sql .= "FROM `solicitudes` sol ";
    $sql .= "INNER JOIN `herramientas` her ON (sol.cod_herramienta = her.Codigo) ";
    $sql .= "WHERE `id_solicitud_compra`='$nroPedido' GROUP BY sol.cod_herramienta ORDER BY sol.cod_herramienta";

    //echo $sql;
    //echo $bME;
    return SFr($sql);
}



function AgregarCodigo($_ARGS)
{
    global $mysqli;

    $codigo = $_ARGS["codigo"];
    $descripcion = $_ARGS["descripcion"];

    //include "editar.xlsx.php";

    //$fecha_solicitud = date("Y-m-d");
    //echo json_encode($fecha_consumo);
    //exit();
    $sql = "SELECT `Codigo` FROM herramientas WHERE `Codigo`='$codigo'";
    $res = $mysqli->query($sql);

    //echo $sql;
    //$sql = "CALL cargarSolicitud('$codigo','$legajo',$cantidad,'$fecha_solicitud',@SALIDA);";
    //Ahora hay un bug con la cantidad de elementos que por ahora no detecta y no tengo manera sencilla de solucionarlo,
    // deberia cambiar la tabla y poner que sea con cantidad y no por unidad
    //$res = $mysqli->query($sql);
    //print_r($res);
    //echo $res->num_rows;
    if ($res->num_rows == 0) {
        $sql = "INSERT INTO herramientas (`Codigo`, `Descripcion`) VALUES ('$codigo', '$descripcion');";
        if (IN($sql)) {
            return json_encode("ok");
        }
    } else {
        $sql = $sql = "UPDATE `herramientas` SET `Descripcion` = '$descripcion' WHERE `Codigo`='$codigo';";
        if (IN($sql)) {
            return json_encode("ac");
        }
    }

    return json_encode("error");
}


$router->addApiEntry("AgregarCodigo", "AgregarCodigo");
$router->addApiEntry("ListarCodigos", $ListarCodigos);
$router->addApiEntry("MostrarDetalles", "MostrarDetalles");

?>