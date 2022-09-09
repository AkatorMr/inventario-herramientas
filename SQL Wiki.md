# Wiki para consultas SQL
## Selecciona todas las  solicitudes que cuyo legajo no tenga nombre 


```
SELECT *
  FROM `solicitudes`
  WHERE NOT EXISTS (SELECT * FROM `operarios`
WHERE `solicitudes`.`legajo_operario` = `operarios`.`legajo`  );
```
