<?php
 include "conexion.php";

$sql =<<<EOF
begin tran

if exists(SELECT * from Student where FirstName='Marcos' and LastName='Pereson')            
BEGIN            
 update Student set FirstName='Anu' where FirstName='Akhil'  
End                    
else            
begin  
insert into Student values('Marcos','Pereson')  
end 
end if

commit tran
EOF;

$res = $mysqli->query($sql);

?>