<?php

class Router{

    private static $instance = null;
    private $tree = [];

    private function __construct(){

    }

    public static function getInstance(){

        if(self::$instance==null){
            self::$instance = new Router();
        }

        return self::$instance;
    }

    public function addApiEntry($path,$func){

        array_push($this->tree,array($path,$func));

    }

    public function execute($request){
        for($i =0; $i<count($this->tree);$i++){
            $path = $this->tree[$i][0];
            $func = $this->tree[$i][1];

            if($request==$path){
                if(function_exists($func)){
                    return call_user_func($func);
                }

                return json_encode("Error: Función no encontrada");
            }
        }
    }


}