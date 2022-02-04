<?php
    include "conexion.php";
    $comando = (array_keys($_GET));
    if(count($comando)==0)exit();
    $sub_comando = $_GET['nivel'];
    $comando = $comando[0];

    
    if(strpos($comando,"ListarOperarios")!==FALSE){
    
        SF("SELECT * FROM operarios");
        exit();
    }else if(strpos($comando,"InsertarOperario")!==FALSE){
    
        
        $legajo = $_POST["legajo"];
        $apellido = $_POST["apellido"];
        $nombre = $_POST["nombre"];
        $sector = $_POST["sector"];
        
        if(IN("INSERT INTO operarios (legajo,Nombre,Apellido,Sector)VALUES('$legajo','$nombre','$apellido','$sector')")){
            echo json_encode("ok");
            exit();
        }
        
        echo json_encode("error");
        die();
    }else if(strpos($comando,"ListarSectores")!==FALSE){
    
        SF("SELECT DISTINCT `Sector` FROM `operarios`");
        exit();
    }else if(strpos($comando,"ListarSolicitudes")!==FALSE){
        $inicio = $sub_comando*6;
        SF("SELECT o.Legajo, CONCAT(o.Nombre, ' ', o.Apellido) full_name, s.cod_herramienta, s.estado 
FROM `solicitudes` s
INNER JOIN `operarios` o ON (s.legajo_operario = o.Legajo)
WHERE s.estado != 'CONSUMIDA' ORDER BY o.Legajo LIMIT $inicio,6;
        ");
        exit();
    }else if(strpos($comando,"ListarConsumos")!==FALSE){
    
        $sql=<<<EOF
SELECT herramientas.Descripcion AS Descripcion, operarios.Nombre AS Nombre, operarios.Apellido AS Apellido
FROM `consumos` con
INNER JOIN `herramientas` ON (con.cod_herramienta = herramientas.Codigo)
INNER JOIN `operarios` ON con.legajo_operario = operarios.legajo;
EOF;

$sql2=<<<EOF
SELECT h.Descripcion AS Descripcion, o.Nombre AS Nombre, o.Apellido AS Apellido
FROM `consumos` con, `herramientas` h, `operarios` o
WHERE con.cod_herramienta = h.Codigo AND con.legajo_operario = o.legajo;
EOF;
        SF($sql);
        exit();
    }else if(strpos($comando,"ListarCodigos")!==FALSE){
    
        SF("SELECT `Codigo`,`Descripcion` FROM `herramientas`");
        
        exit();
    }else if(strpos($comando,"GenerarConsumo")!==FALSE){
    
        
        $legajo = $_POST["legajo"];
        $a_codigo = $_POST["codigo"];//Es un array
        $a_cantidad = $_POST["cantidad"];// Es un array
        $vale_oracle= $_POST["valeoracle"];
        $vale_mp= $_POST["mpvale"];
        $ot_mp= $_POST["mpot"];

        //include "editar.xlsx.php";

        $fecha_consumo = date("Y-m-d");
        //echo json_encode($fecha_consumo);
        //exit();

        $sql = "INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`) VALUES (\'502514\', \'CINTA_11_0011\', \'2021-11-25\', \'CONSUMIDA\', \'0000-00-00\', \'0\', \'0000-00-00\');";
        $s = "";
        $c=true;
        for($i =0;$i<count($a_codigo);$i++){
            $codigo = $a_codigo[$i];
            $cantidad = $a_cantidad[$i];

            //Esto vale oro
            $sql = "SELECT `solicitudes`.`id` AS ide , `solicitudes`.`cod_herramienta` , `operarios`.`Nombre` FROM `solicitudes`
            INNER JOIN `operarios` ON `solicitudes`.`legajo_operario` = `operarios`.`legajo` 
            WHERE `nombre`='Almacen' 
            AND `solicitudes`.`estado` = 'DISPONIBLE' 
            AND `solicitudes`.`cod_herramienta`='$codigo' 
            LIMIT 1;";
            error_log($sql);

            //Ahora hay un bug con la cantidad de elementos que por ahora no detecta y no tengo manera sencilla de solucionarlo,
            // deberia cambiar la tabla y poner que sea con cantidad y no por unidad
            $res = $mysqli->query($sql);
            //print_r($res);
            //echo $res->num_rows;
            if($res->num_rows>0){
                //Hacer un update 
                //echo "Realiza un update";
                $row = $res->fetch_assoc();
                $ide = $row['ide'];

                $sql = "UPDATE `solicitudes` SET `legajo_operario` = '$legajo',`estado`='CONSUMIDA' WHERE `id`='$ide';";
                
            }else{
                //Hacer un insert de la solicitud
                $sql = "INSERT INTO `solicitudes` (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`) VALUES ('$legajo', '$codigo', '$fecha_consumo', 'CONSUMIDA', '$fecha_consumo', '0', '$fecha_consumo');";
                

            }
            if(IN($sql))
                if(IN("INSERT INTO consumos (legajo_operario,cod_herramienta,cantidad,fecha_consumido,estado,vale_oracle,vale_mp,ot_mp)VALUES('$legajo','$codigo','$cantidad','$fecha_consumo','CONSUMIDA','$vale_oracle','$vale_mp','$ot_mp')")){
                    $c= true;
                    $s.="1";
                    continue;
                }
            $c=false;
            $s=$sql;
        }
        if($c){
            echo json_encode("ok");
            exit();
        }
        echo json_encode("error");
        die();
    }else if(strpos($comando,"NuevaSolicitud")!==FALSE){
    
        
        $legajo = $_POST["legajo"];
        $codigo = $_POST["codigo"];
        $cantidad = $_POST["cantidad"];
        
        //include "editar.xlsx.php";

        $fecha_solicitud = date("Y-m-d");
        //echo json_encode($fecha_consumo);
        //exit();

        $sql = "INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`) VALUES ('$legajo', '$codigo', '$fecha_solicitud', \'AGREGAR\', \'0000-00-00\', \'0\', \'0000-00-00\');";
        $sql = "CALL cargarSolicitud('$codigo','$legajo',$cantidad,'$fecha_solicitud',@SALIDA);";
        //Ahora hay un bug con la cantidad de elementos que por ahora no detecta y no tengo manera sencilla de solucionarlo,
        // deberia cambiar la tabla y poner que sea con cantidad y no por unidad
        $res = $mysqli->query($sql);
        //print_r($res);
        //echo $res->num_rows;
        if($res->num_rows>0){
            echo json_encode("ok");
            exit();   

        }
        
        echo json_encode("error");
        die();
    }else if(strpos($comando,"AgregarCodigo")!==FALSE){
    
        
        
        $codigo = $_POST["codigo"];
        $descripcion = $_POST["descripcion"];
        
        //include "editar.xlsx.php";

        //$fecha_solicitud = date("Y-m-d");
        //echo json_encode($fecha_consumo);
        //exit();
        
        $sql = "INSERT INTO herramientas (`Codigo`, `Descripcion`) VALUES ('$codigo', '$descripcion');";
        //echo $sql;
        //$sql = "CALL cargarSolicitud('$codigo','$legajo',$cantidad,'$fecha_solicitud',@SALIDA);";
        //Ahora hay un bug con la cantidad de elementos que por ahora no detecta y no tengo manera sencilla de solucionarlo,
        // deberia cambiar la tabla y poner que sea con cantidad y no por unidad
        //$res = $mysqli->query($sql);
        //print_r($res);
        //echo $res->num_rows;
        //if($res->num_rows>0){
        if(IN($sql)){
            echo json_encode("ok");
            exit();   

        }
        
        echo json_encode("error");
        die();
    }

    

?>