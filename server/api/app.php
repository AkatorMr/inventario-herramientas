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



$router->addApiEntry("ListarOperarios", "ListarOperarios");
$router->addApiEntry("ListarPedidos", "ListarPedidos");
$router->addApiEntry("ListarCodigos", "ListarCodigos");
$router->addApiEntry("InsertarOperario", "InsertarOperario");

function Prueba($args){
    print_r($args);
}

$router->addApiEntry("Prueba", "Prueba");



?>