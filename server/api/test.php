<?php
/* include "conexion.php";
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
$res = $mysqli->query($sql); */

class SimpleRequest
{
    private $sql = "";
    public function __construct()
    {

    }

    public function Select($array)
    {
        $this->sql = "SELECT " . $array . " ";
        return $this;
    }

    public function From($table)
    {
        $this->sql .= "FROM " . $table . " ";
        return $this;
    }

    public function Where($condition){
        $this->sql .= "WHERE " . $condition . " ";
        return $this;
    }

    public function OrderBy($array){
        $this->sql .= "ORDER BY " . $array . " ";
        return $this;
    }

    public function ASC(){
        $this->sql .= "ASC";
        return $this;
    }

    public function DESC(){
        $this->sql .= "DESC";
        return $this;
    }

    public function getSql()
    {
        return $this->sql;
    }
}

$sr = new SimpleRequest();

$sr->Select("nombre")->From("animales")->Where("nombre = `perro`")->OrderBy("id")->DESC();

echo $sr->getSql();

?>