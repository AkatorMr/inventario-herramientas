<?php

include_once("Router.php");

$router = Router::getInstance();

include("app/operarios.php");
include("app/solicitudes.php");
include("app/consumos.php");
include("app/misc.php");



$Prueba = function($args)
{
    print_r($args);
};

$router->addApiEntry("Prueba", $Prueba);



?>