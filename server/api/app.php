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


$router->addApiEntry("ListarOperarios", "ListarOperarios");
$router->addApiEntry("ListarPedidos", "ListarPedidos");
$router->addApiEntry("ListarCodigos", "ListarCodigos");




?>