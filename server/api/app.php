<?php

include_once("Router.php");

$router = Router::getInstance();

function ListarOperarios()
{

    return SFr("SELECT * FROM operarios");
}

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

function ListarCodigos()
{
    return SFr("SELECT `Codigo`,`Descripcion` FROM `herramientas`");
}


function InsertarOperario($_ARGS)
{
    $legajo = $_ARGS["legajo"];
    $apellido = $_ARGS["apellido"];
    $nombre = $_ARGS["nombre"];
    $sector = $_ARGS["sector"];

    if (IN("INSERT INTO operarios (legajo,Nombre,Apellido,Sector)VALUES('$legajo','$nombre','$apellido','$sector')")) {
        return json_encode("ok");

    }

    return json_encode("error");
}

function ListarSolicitudes($_ARGS) {
    
    $sub_comando = $_ARGS["nivel"]; // Mediante GET

    if (!is_numeric($sub_comando)) 
        return json_encode("error");
        

    $legajo = $_ARGS["legajo"];
    $codigo = $_ARGS["codigo"];
    $nombre = $_ARGS["nombre"];
    $descripcion = $_ARGS["descripcion"];

    $filtroEstado = "";
    if (!empty($_ARGS["filtroEstado"]))
        $filtroEstado = $_ARGS["filtroEstado"];


    $bME = "true";
    if (!empty($_ARGS["bMostrarEliminados"]))
        $bME = $_ARGS["bMostrarEliminados"];

    $inicio = $sub_comando * 6;
    //SELECT COUNT(*)
    $sql = "SELECT s.id, s.id_solicitud_compra as id_solicitud_compra, o.Legajo, CONCAT(o.Nombre, ' ', o.Apellido) full_name, s.cod_herramienta, CONCAT(h.Descripcion, ' (', COUNT(s.cod_herramienta) , ')') Descripcion, s.estado, s.fecha_solicitud";
    $sql .= " FROM `solicitudes` s";
    $sql .= " INNER JOIN `operarios` o ON (s.legajo_operario = o.Legajo)";
    $sql .= " INNER JOIN `herramientas` h ON (s.cod_herramienta = h.Codigo)";
    $sql .= " WHERE s.estado != 'CONSUMIDA'";
    if ($bME == "false")
        $sql .= " AND s.estado != 'ELIMINADA'";
    $sql .= " AND h.Codigo LIKE '%$codigo%'";
    $sql .= " AND h.Descripcion LIKE '%$descripcion%'";
    $sql .= " AND o.Legajo LIKE '%$legajo%'";
    $sql .= " AND s.estado LIKE '%$filtroEstado%'";
    $sql .= " AND (o.Nombre LIKE '%$nombre%'";
    $sql .= " OR o.Apellido LIKE '%$nombre%')";
    $sql .= " GROUP BY h.Codigo, o.Legajo, s.estado";
    $sql .= " ORDER BY o.Legajo LIMIT $inicio,6;";
    //MPLog("Dentro de api");
    //MPLog($sql);
    //echo $bME;
    return SFr($sql);
}

$router->addApiEntry("ListarOperarios", "ListarOperarios");
$router->addApiEntry("ListarPedidos", "ListarPedidos");
$router->addApiEntry("ListarCodigos", "ListarCodigos");
$router->addApiEntry("InsertarOperario", "InsertarOperario");
$router->addApiEntry("ListarSolicitudes", "ListarSolicitudes");

function Prueba($args){
    print_r($args);
}

$router->addApiEntry("Prueba", "Prueba");



?>