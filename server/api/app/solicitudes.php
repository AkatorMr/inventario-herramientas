<?php

include_once("Router.php");

$router = Router::getInstance();

function NuevaSolicitud($_ARGS)
{

    global $mysqli;

    $legajo = $_ARGS["legajo"];
    $codigo = $_ARGS["codigo"];
    $cantidad = $_ARGS["cantidad"];
    $fecha = $_ARGS["fecha"];
    $nro_solicitud = $_ARGS["nro_solicitud"];

    //include "editar.xlsx.php";
    $fecha_solicitud = date("Y-m-d");
    //echo "p".$nro_solicitud."p";

    if (!empty($fecha))
        $fecha_solicitud = $fecha;
    //echo json_encode($fecha_consumo);
    //exit();
    $estado = "AGREGAR";

    $sql = "INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`) VALUES ('$legajo', '$codigo', '$fecha_solicitud', \'$estado\', \'0000-00-00\', \'$nro_solicitud\', \'0000-00-00\');";
    $sql = "CALL cargarSolicitud('$codigo','$legajo',$cantidad,'$fecha_solicitud',@SALIDA);";

    if ($nro_solicitud != "0") {
        $estado = "LISTA";
        $sql = "CALL CargarSolicitudPrevia('$codigo','$legajo',$cantidad,'$fecha_solicitud','$estado','$nro_solicitud',@SALIDA);";
    }

    //Ahora hay un bug con la cantidad de elementos que por ahora no detecta y no tengo manera sencilla de solucionarlo,
    // deberia cambiar la tabla y poner que sea con cantidad y no por unidad
    $res = $mysqli->query($sql);
    //print_r($res);
    //echo $res->num_rows;
    if ($res->num_rows > 0) {
        return json_encode("ok");

    }

    return json_encode("error");

}

function ActualizarSolicitud($_ARGS)
{

    $id_sol = $_ARGS["id_sol"];
    $estado = $_ARGS["estado"];
    $fecha = $_ARGS["fecha"];

    if ($estado == "CARGADO") {
        $sc = $_ARGS["nro_sc"];
        $editar = "";
        if (!empty($sc))
            $editar .= "`id_solicitud_compra` = '$sc', ";

        $sql = "UPDATE `solicitudes` SET " . $editar . "`estado` = '$estado' WHERE `id`='$id_sol';";
        //echo $sql;
        if (IN($sql)) {
            return json_encode("ac");

        }
        return json_encode("error");
    }


    $nro_solicitud = $_ARGS["nro_solicitud"];

    $editar = "";
    if (!empty($fecha)) {
        switch ($estado) {
            case 'AGREGAR':
            case 'GENERAR':
            case 'CARGADO':
                $editar = "`fecha_solicitud` = '$fecha', ";
                break;
            case 'LISTA':
            case 'PERDIDA':
                $editar = "`fecha_sc` = '$fecha', ";
                break;
            case 'DISPONIBLE':
            case 'LLEGO':
                $editar = "`fecha_llegada` = '$fecha', ";
                break;
            default:
                $editar = "";
        }
    }
    if (!empty($nro_solicitud))
        $editar .= "`id_solicitud_compra` = '$nro_solicitud', ";

    $sql = "UPDATE `solicitudes` SET " . $editar . "`estado` = '$estado' WHERE `id`='$id_sol';";
    //echo $sql;
    if (IN($sql)) {
        return json_encode("ac");
    }


    return json_encode("error");
}

function ListarSolicitudes($_ARGS)
{

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

function EliminarSolicitud($_ARGS)
{
    $id_sol = $_ARGS["id_sol"];
    $fecha = $_ARGS["fecha"];
    $editar = "`fecha_solicitud` = '$fecha', ";

    $sql = "UPDATE `solicitudes` SET " . $editar . "`estado` = 'ELIMINADA' WHERE `id`='$id_sol';";

    if (IN($sql)) {
        return json_encode("ac");
    }


    return json_encode("error");
}

$router->addApiEntry("NuevaSolicitud", "NuevaSolicitud");
$router->addApiEntry("ActualizarSolicitud", "ActualizarSolicitud");
$router->addApiEntry("EliminarSolicitud", "EliminarSolicitud");
$router->addApiEntry("ListarSolicitudes", "ListarSolicitudes");

?>