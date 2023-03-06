<?php

include_once("Router.php");

$router = Router::getInstance();

function ListarOperarios(){
    
    return SF("SELECT * FROM operarios");
}


$router->addApiEntry("ListarOperarios","ListarOperarios")



?>