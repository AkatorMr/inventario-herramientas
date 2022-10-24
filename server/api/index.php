<?php
    include "conexion.php";
    function MPLog($data){
        $fp = fopen("log.txt","a+");
        fprintf($fp,$data."\n");
        fclose($fp);
    }
    $comando = (array_keys($_GET));

    
    if(count($comando)==0)
        exit();
        
    $sub_comando="";
    if(isset($_GET['nivel']))
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
        if(!is_numeric($sub_comando)){
            echo json_encode("error");
            exit();
        }

        $legajo = $_POST["legajo"];
        $codigo = $_POST["codigo"];
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        
        $bME = "true";
        if(!empty($_POST["bMostrarEliminados"]))
            $bME = $_POST["bMostrarEliminados"];

        $inicio = $sub_comando*6;

        $sql = "SELECT s.id, o.Legajo, CONCAT(o.Nombre, ' ', o.Apellido) full_name, s.cod_herramienta, h.Descripcion, s.estado, s.fecha_solicitud";
        $sql.= " FROM `solicitudes` s";
        $sql.= " INNER JOIN `operarios` o ON (s.legajo_operario = o.Legajo)";
        $sql.= " INNER JOIN `herramientas` h ON (s.cod_herramienta = h.Codigo)";
        $sql.= " WHERE s.estado != 'CONSUMIDA'";
        if($bME == "false")
            $sql.= " AND s.estado != 'ELIMINADA'";
        $sql.= " AND h.Codigo LIKE '%$codigo%'";
        $sql.= " AND h.Descripcion LIKE '%$descripcion%'";
        $sql.= " AND o.Legajo LIKE '%$legajo%'";
        $sql.= " AND (o.Nombre LIKE '%$nombre%'";
        $sql.= " OR o.Apellido LIKE '%$nombre%')";

        $sql.=" ORDER BY o.Legajo LIMIT $inicio,6;";
        //echo $sql;
        //echo $bME;
        SF($sql);
        exit();
    }else if(strpos($comando,"ListarConsumosEstadistica")!==FALSE){
    
        
        $codigo = $_POST["codigo"];
        $descripcion = $_POST["descripcion"];


        $inicio = $sub_comando*15;
        $sql="SELECT herramientas.Codigo, herramientas.Descripcion AS Descripcion, SUM(con.cantidad) as Cantidad";
        $sql.=" FROM `consumos` con";
        $sql.=" INNER JOIN `herramientas` ON (con.cod_herramienta = herramientas.Codigo)";
        $sql.=" WHERE herramientas.Codigo LIKE '%$codigo%'";
        $sql.=" AND herramientas.Descripcion LIKE '%$descripcion%'";
        $sql.=" GROUP BY herramientas.Codigo";
        $sql.=" ORDER BY Cantidad DESC LIMIT $inicio,15;";

        //echo $sql;        
        SF($sql);
        exit();
    }else if(strpos($comando,"ListarConsumos")!==FALSE){
    
        $legajo = $_POST["legajo"];
        $codigo = $_POST["codigo"];
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];


        $inicio = $sub_comando*15;
        $sql="SELECT operarios.Legajo, herramientas.Codigo, herramientas.Descripcion AS Descripcion, operarios.Nombre AS Nombre, operarios.Apellido AS Apellido, SUM(con.cantidad) as Cantidad, con.fecha_consumido AS Fecha";
        $sql.=" FROM `consumos` con";
        $sql.=" INNER JOIN `herramientas` ON (con.cod_herramienta = herramientas.Codigo)";
        $sql.=" INNER JOIN `operarios` ON con.legajo_operario = operarios.legajo";
        $sql.=" WHERE herramientas.Codigo LIKE '%$codigo%'";
        $sql.=" AND herramientas.Descripcion LIKE '%$descripcion%'";
        $sql.=" AND operarios.Legajo LIKE '%$legajo%'";
        $sql.=" AND (operarios.Nombre LIKE '%$nombre%'";
        $sql.=" OR operarios.Apellido LIKE '%$nombre%')";
        $sql.=" GROUP BY herramientas.Codigo, operarios.Legajo";
        $sql.=" ORDER BY operarios.Legajo, herramientas.Codigo LIMIT $inicio,15;";

        //echo $sql;

        $sql2="SELECT h.Descripcion AS Descripcion, o.Nombre AS Nombre, o.Apellido AS Apellido";
        $sql2.=" FROM `consumos` con, `herramientas` h, `operarios` o";
        $sql2.=" WHERE con.cod_herramienta = h.Codigo AND con.legajo_operario = o.legajo;";
        
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

        $vale_fecha = $_POST["valefecha"];

        //include "editar.xlsx.php";
        $fecha_consumo = date("Y-m-d");
        //MPLog($vale_fecha);
        //$tiempo  = DateTime::createFromFormat('d/m/Y', $vale_fecha);
        //MPLog($tiempo);
        //echo json_encode([$tiempo,$vale_fecha);
        //exit();
        //MPLog($fecha_consumo);
        //if($tiempo instanceof DateTime){
        if(!empty($vale_fecha)){
            //$fecha_consumo = date("Y-m-d",$tiempo);
            $fecha_consumo = $vale_fecha;
        }
        //MPLog($fecha_consumo);
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
            if(IN($sql)){
                $sql_2 = "INSERT INTO consumos (legajo_operario,cod_herramienta,cantidad,fecha_consumido,estado,vale_oracle,vale_mp,ot_mp)VALUES('$legajo','$codigo','$cantidad','$fecha_consumo','CONSUMIDA','$vale_oracle','$vale_mp','$ot_mp')";
                //MPLog($sql_2);
                if(IN($sql_2)){
                    $c= true;
                    $s.="1";
                    continue;
                }
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
        $fecha = $_POST["fecha"];
        $nro_solicitud = $_POST["nro_solicitud"];
        
        //include "editar.xlsx.php";
        $fecha_solicitud = date("Y-m-d");
        //echo "p".$nro_solicitud."p";

        if(!empty($fecha))
            $fecha_solicitud = $fecha;
        //echo json_encode($fecha_consumo);
        //exit();
        $estado = "AGREGAR";
        
        $sql = "INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`) VALUES ('$legajo', '$codigo', '$fecha_solicitud', \'$estado\', \'0000-00-00\', \'$nro_solicitud\', \'0000-00-00\');";
        $sql = "CALL cargarSolicitud('$codigo','$legajo',$cantidad,'$fecha_solicitud',@SALIDA);";

        if($nro_solicitud!="0"){
            $estado = "LISTA";
            $sql = "CALL CargarSolicitudPrevia('$codigo','$legajo',$cantidad,'$fecha_solicitud','$estado','$nro_solicitud',@SALIDA);";
        }
        
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
        $sql = "SELECT `Codigo` FROM herramientas WHERE `Codigo`='$codigo'";
        $res = $mysqli->query($sql);

        //echo $sql;
        //$sql = "CALL cargarSolicitud('$codigo','$legajo',$cantidad,'$fecha_solicitud',@SALIDA);";
        //Ahora hay un bug con la cantidad de elementos que por ahora no detecta y no tengo manera sencilla de solucionarlo,
        // deberia cambiar la tabla y poner que sea con cantidad y no por unidad
        //$res = $mysqli->query($sql);
        //print_r($res);
        //echo $res->num_rows;
        if($res->num_rows==0){
            $sql = "INSERT INTO herramientas (`Codigo`, `Descripcion`) VALUES ('$codigo', '$descripcion');";
            if(IN($sql)){
                echo json_encode("ok");
                exit();
            }
        }else{
            $sql = $sql = "UPDATE `herramientas` SET `Descripcion` = '$descripcion' WHERE `Codigo`='$codigo';";
            if(IN($sql)){
                echo json_encode("ac");
                exit();
            }
        }
        
        echo json_encode("error");
        die();
    }else if(strpos($comando,"ActualizarSolicitud")!==FALSE){
    
        $id_sol = $_POST["id_sol"];
        $estado = $_POST["estado"];
        $fecha = $_POST["fecha"];
        $nro_solicitud = $_POST["nro_solicitud"];
        
        $editar = "";
        if(!empty($fecha)){
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
                    $editar="";
            }
        }
        if(!empty($nro_solicitud))
            $editar.="`id_solicitud_compra` = '$nro_solicitud', ";

        $sql = "UPDATE `solicitudes` SET ".$editar."`estado` = '$estado' WHERE `id`='$id_sol';";
        echo $sql;
        if(IN($sql)){
            echo json_encode("ac");
            exit();
        }
        
        
        echo json_encode("error");
        die();
    }else if(strpos($comando,"EliminarSolicitud")!==FALSE){
        $id_sol = $_POST["id_sol"];
        
        $editar = "`fecha_solicitud` = '$fecha', ";
                   
        $sql = "UPDATE `solicitudes` SET ".$editar."`estado` = 'ELIMINADA' WHERE `id`='$id_sol';";
        echo $sql;
        if(IN($sql)){
            echo json_encode("ac");
            exit();
        }
        
        
        echo json_encode("error");
        die();
    }else if(strpos($comando,"EditarOperarioOperario")!==FALSE){

        $legajo_original = $_POST["legajo_original"];
        
        $legajo = $_POST["legajo"];
        $apellido = $_POST["apellido"];
        $nombre = $_POST["nombre"];
        $sector = $_POST["sector"];

        $bypass = false;
        if(isset($_POST["pass"]))
            $bypass = $_POST["pass"]=="by";

        if($legajo != $legajo_original && !$bypass){
            $sql = "SELECT * FROM operarios WHERE legajo='$legajo'";
            $res = $mysqli->query($sql);
            $c=0;
            while($row = $res->fetch_assoc()){
                $c++;
            }
            
            if($c!=0) {
                echo json_encode("error - Ya existe operario con ese legajo");
                exit();
            }
        }
        
        //Actualizamos tanto al operario como a los consumos relacionados.
        $sql = "UPDATE `operarios` SET `legajo` = '$legajo',";
        $sql.= " `Nombre` = '$nombre',";
        $sql.= " `Apellido` = '$apellido',";
        $sql.= " `Sector` = '$sector'";
        $sql.= " WHERE `legajo`='$legajo_original';";

        if($legajo != $legajo_original){
            $sql.= "UPDATE `consumos` SET `legajo_operario` = '$legajo'";
            $sql.= " WHERE `legajo_operario`='$legajo_original';";

            //UPDATE `inventario`.`solicitudes` SET `legajo_operario` = '112121' WHERE `solicitudes`.`id` =1;
            $sql.= "UPDATE `solicitudes` SET `legajo_operario` = '$legajo'";
            $sql.= " WHERE `legajo_operario`='$legajo_original';";
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

        if(empty($mysqli->error)){
            echo json_encode("ac.");        
            exit();
        }
        echo json_encode("error no se actualizaron los valores.");
        exit();
    }

    

?>