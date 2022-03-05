DROP PROCEDURE IF EXISTS CargarSolicitudPrevia;
DELIMITER $$

CREATE PROCEDURE CargarSolicitudPrevia(
    IN f_codigo VARCHAR(14), 
    IN f_legajo VARCHAR(6),
    IN f_cantidad int(11),
    IN f_fecha date,
    IN f_estado VARCHAR(10),
    IN f_nro_solicitud int(11),
    OUT resultado int(11)    
)
BEGIN
    DECLARE l_account_id INT DEFAULT 0;
    DECLARE l_restante INT DEFAULT 0;
    DECLARE l_id_to_update INT DEFAULT 0;
    DECLARE star VARCHAR(50);
    DECLARE resultado2 VARCHAR(50);

    SET resultado2 = "A";
    SELECT 10 INTO resultado;
    SELECT f_cantidad INTO l_restante;

    label_for_loop: LOOP
        IF l_restante = 0 THEN
            LEAVE label_for_loop;
        END IF;
        START TRANSACTION;
        
        -- No hay ningún elemento hay que hacer un insert
        INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `id_solicitud_compra`)
        VALUES (f_legajo, f_codigo, f_fecha, f_estado, f_nro_solicitud);
        -- SET resultado2 = CONCAT(resultado2 , "I");
    
        COMMIT;
        SET resultado2 = CONCAT(resultado2 , "B");
        SET resultado = resultado - 1;
        SET l_restante = l_restante - 1;
    END LOOP; 
    -- Hacemos un insert en consumos jeje
    -- INSERT INTO consumos (legajo_operario,cod_herramienta,cantidad,fecha_consumido,estado)
    -- VALUES(f_legajo,f_codigo,f_cantidad,f_fecha,'CONSUMIDA');
    SET resultado2 = CONCAT(resultado2 , "C");
    SELECT resultado,resultado2;
END$$

DELIMITER ;


DROP PROCEDURE IF EXISTS CargarSolicitud;
DELIMITER $$

CREATE PROCEDURE CargarSolicitud(
    IN f_codigo VARCHAR(14), 
    IN f_legajo VARCHAR(6),
    IN f_cantidad int(11),
    IN f_fecha date,
    OUT resultado int(11)    
)
BEGIN
    DECLARE l_account_id INT DEFAULT 0;
    DECLARE l_restante INT DEFAULT 0;
    DECLARE l_id_to_update INT DEFAULT 0;
    DECLARE star VARCHAR(50);
    DECLARE resultado2 VARCHAR(50);

    SET resultado2 = "A";
    SELECT 10 INTO resultado;
    SELECT f_cantidad INTO l_restante;

    label_for_loop: LOOP
        IF l_restante = 0 THEN
            LEAVE label_for_loop;
        END IF;
        START TRANSACTION;
        
        -- No hay ningún elemento hay que hacer un insert
        INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `id_solicitud_compra`)
        VALUES (f_legajo, f_codigo, f_fecha, 'AGREGAR', '0');
        -- SET resultado2 = CONCAT(resultado2 , "I");
    
        COMMIT;
        SET resultado2 = CONCAT(resultado2 , "B");
        SET resultado = resultado - 1;
        SET l_restante = l_restante - 1;
    END LOOP; 
    -- Hacemos un insert en consumos jeje
    -- INSERT INTO consumos (legajo_operario,cod_herramienta,cantidad,fecha_consumido,estado)
    -- VALUES(f_legajo,f_codigo,f_cantidad,f_fecha,'CONSUMIDA');
    SET resultado2 = CONCAT(resultado2 , "C");
    SELECT resultado,resultado2;
END$$

DELIMITER ;


DROP PROCEDURE IF EXISTS GenerarConsumo;
DELIMITER $$

CREATE PROCEDURE GenerarConsumo(
    IN f_codigo VARCHAR(14), 
    IN f_legajo VARCHAR(6),
    IN f_cantidad int(11),
    IN f_fecha date,
    OUT resultado int(11),
    OUT resultado2 VARCHAR(100)
)
BEGIN
    DECLARE l_account_id INT DEFAULT 0;
    DECLARE l_restante INT DEFAULT 0;
    DECLARE l_id_to_update INT DEFAULT 0;
    DECLARE star VARCHAR(50);
  
    SET star= "*";
  
    
    SELECT 10 INTO resultado;
    SELECT f_cantidad INTO l_restante;
    SET resultado2 = "A";

    label_for_loop: LOOP
        IF l_restante = 0 THEN
        LEAVE label_for_loop;
        END IF;
        START TRANSACTION;
        SELECT solicitudes.id AS ide INTO l_id_to_update FROM solicitudes
            INNER JOIN operarios ON solicitudes.legajo_operario = operarios.legajo 
            WHERE nombre='Almacen' 
            AND solicitudes.estado = 'DISPONIBLE' 
            AND solicitudes.cod_herramienta=f_codigo
            LIMIT 1;
        SET resultado2 = CONCAT(resultado2 , l_id_to_update);
        IF l_id_to_update > 0 THEN
            -- Hay un elemento hay que hacerle un update
            UPDATE solicitudes SET legajo_operario = f_legajo,estado='CONSUMIDA' WHERE id = l_id_to_update ;
            SET resultado2 = CONCAT(resultado2 , "U");
            
        ELSE
            -- No hay ningún elemento hay que hacer un insert
            INSERT INTO solicitudes (`legajo_operario`, `cod_herramienta`, `fecha_solicitud`, `estado`, `fecha_sc`, `id_solicitud_compra`, `fecha_llegada`)
            VALUES (f_legajo, f_codigo, f_fecha, 'CONSUMIDA', f_fecha, '0', f_fecha);
            SET resultado2 = CONCAT(resultado2 , "I");
        END IF;
        COMMIT;
        SET resultado2 = CONCAT(resultado2 , "B");
        SET resultado = resultado - 1;
        SET l_restante = l_restante - 1;
    END LOOP; 
    -- Hacemos un insert en consumos jeje
    INSERT INTO consumos (legajo_operario,cod_herramienta,cantidad,fecha_consumido,estado)
    VALUES(f_legajo,f_codigo,f_cantidad,f_fecha,'CONSUMIDA');
    SET resultado2 = CONCAT(resultado2 , "C");
    SELECT resultado,resultado2;
END$$

DELIMITER ;






DROP PROCEDURE IF EXISTS for_loop_star;
DELIMITER $$
CREATE procedure for_loop_star()
BEGIN
  DECLARE l_restante INT DEFAULT 0;
  DECLARE f_output VARCHAR(50);
  SET l_restante = 1;
  forloop: LOOP
    IF l_restante > 5 THEN
    LEAVE forloop;
    END IF;
   SET l_restante = l_restante + 1;
  END LOOP;
END $$
DELIMITER ;





DROP PROCEDURE IF EXISTS GenerarConsumo;
DELIMITER $$

CREATE PROCEDURE GenerarConsumo(
    IN f_codigo VARCHAR(14), 
    IN f_legajo VARCHAR(6),
    IN f_cantidad int(11),
    OUT resultado int(11)
)
BEGIN
    DECLARE l_account_id INT DEFAULT 0;
    DECLARE l_restante INT DEFAULT 0;
    DECLARE l_id_to_update INT DEFAULT 0;

    START TRANSACTION;
    SELECT 0 INTO resultado;

    label_for_loop: LOOP
        IF l_restante > 0 THEN
        LEAVE label_for_loop;
        END IF

        SELECT solicitudes.id AS ide INTO l_id_to_update FROM solicitudes
            INNER JOIN operarios ON solicitudes.legajo_operario = operarios.legajo 
            WHERE nombre='Almacen' 
            AND solicitudes.estado = 'DISPONIBLE' 
            AND solicitudes.cod_herramienta=f_codigo
            LIMIT 1;

        SELECT l_id_to_update INTO resultado;
        
        
        SET l_restante = l_restante-1;
    END LOOP; 

    /* IF (SELECT COUNT(*) FROM usuarios WHERE username=f_username) > 0 THEN
        -- usuario ya registrado
        SELECT -1 INTO resultado; 
    ELSE
        -- Insert account data
        INSERT INTO usuarios (`username`, `pass_hash`, `token`) 
        VALUES(f_username, f_pass_hash, '');
        
        -- get account id
        SET l_account_id = LAST_INSERT_ID();
        
        -- se crea el perfil asociado a ese id del usuario
        IF l_account_id > 0 THEN
            INSERT INTO perfil (`id_user`, `nombre`, `apellido`, `DNI`, `fecha_de_nacimiento`, `provincia`, `ciudad`, `telefono`)
            VALUES(l_account_id, '', '', '', '', '', '', '');
            -- commit
            COMMIT;
            SELECT 1 INTO resultado;
        ELSE
        SELECT -2 INTO resultado;
        ROLLBACK;
        END IF;
    END IF; */
    
    SELECT resultado;
END$$

DELIMITER ;






