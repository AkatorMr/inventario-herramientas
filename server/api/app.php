<?php

include_once("Router.php");

$router = Router::getInstance();

function ListarOperarios(){

    return SFr("SELECT * FROM operarios");
}


$router->addApiEntry("ListarOperarios","ListarOperarios");
$router->addApiEntry("Joder","Joraca");



?>