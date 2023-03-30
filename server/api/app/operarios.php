<?php
include_once("Router.php");

$router = Router::getInstance();


function ListarOperarios()
{

    return SFr("SELECT * FROM operarios");
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


function ListarSectores()
{

    return SFr("SELECT DISTINCT `Sector` FROM `operarios`");

}

function EditarOperarioOperario($_ARGS)
{
    global $mysqli;

    $legajo_original = $_ARGS["legajo_original"];

    $legajo = $_ARGS["legajo"];
    $apellido = $_ARGS["apellido"];
    $nombre = $_ARGS["nombre"];
    $sector = $_ARGS["sector"];

    $bypass = false;
    if (isset($_ARGS["pass"]))
        $bypass = $_ARGS["pass"] == "by";

    if ($legajo != $legajo_original && !$bypass) {
        $sql = "SELECT * FROM operarios WHERE legajo='$legajo'";
        $res = $mysqli->query($sql);
        $c = 0;
        while ($row = $res->fetch_assoc()) {
            $c++;
        }

        if ($c != 0) {
            echo json_encode("error - Ya existe operario con ese legajo");
            exit();
        }
    }

    //Actualizamos tanto al operario como a los consumos relacionados.
    $sql = "UPDATE `operarios` SET `legajo` = '$legajo',";
    $sql .= " `Nombre` = '$nombre',";
    $sql .= " `Apellido` = '$apellido',";
    $sql .= " `Sector` = '$sector'";
    $sql .= " WHERE `legajo`='$legajo_original';";

    if ($legajo != $legajo_original) {
        $sql .= "UPDATE `consumos` SET `legajo_operario` = '$legajo'";
        $sql .= " WHERE `legajo_operario`='$legajo_original';";

        //UPDATE `inventario`.`solicitudes` SET `legajo_operario` = '112121' WHERE `solicitudes`.`id` =1;
        $sql .= "UPDATE `solicitudes` SET `legajo_operario` = '$legajo'";
        $sql .= " WHERE `legajo_operario`='$legajo_original';";
    }

    //echo $sql;
    $query = $sql;
    /* execute multi query */
    $mysqli->multi_query($query);
    $cc = 0;
    do {
        /* store the result set in PHP */
        if ($result = $mysqli->store_result()) {
            /* while ($row = $result->fetch_row()) {
            printf("%s\n", $row[0]);
            } */
            $cc++;
        }
        /* print divider */
        if (!$mysqli->more_results()) {
            //printf("-----------------\n");
            break;
        }
    } while ($mysqli->next_result());

    //echo $cc;

    if (empty($mysqli->error)) {
        return json_encode("ac.");

    }
    return json_encode("error no se actualizaron los valores.");
}

$router->addApiEntry("InsertarOperario", "InsertarOperario");
$router->addApiEntry("ListarOperarios", "ListarOperarios");
$router->addApiEntry("EditarOperarioOperario", "EditarOperarioOperario");
$router->addApiEntry("ListarSectores", "ListarSectores");


?>