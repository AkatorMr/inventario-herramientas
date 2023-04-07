<?php

class DataBase{

    private static $esta = null;

    private $mysqli;

    private function __construct(){
        //Hard code credentials
        $this->mysqli = new mysqli("localhost","root","usbw","inventario");
    }

    public static function getInstance(){
        if(self::$esta==null){
            self::$esta = new DataBase();
            return self::$esta;
        }
    }
    
    public function SFr($sql){
        
        
        $res = $this->mysqli->query($sql);
        
        $rows = array();
        
        while($row = $res->fetch_assoc()){
            $rows[] = $row;
        }

        return json_encode($rows);
    }
    public function IN($sql){
        $res = $this->mysqli->query($sql);
        
       return $res;
    }
}
?>