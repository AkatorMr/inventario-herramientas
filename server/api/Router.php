<?php

class Router
{

    private static $instance = null;
    private $tree = array();
    private $args = array();
    private function __construct()
    {

    }

    public static function getInstance()
    {

        if (self::$instance == null) {
            self::$instance = new Router();
        }

        return self::$instance;
    }

    public function addApiEntry($path, $func)
    {

        array_push($this->tree, array("/api/" . $path, $func));

    }

    private function PrepareArgs()
    {
        $post = array_keys($_POST);
        $get = array_keys($_GET);

        $this->args = array_merge($_POST, $_GET);
        //TODO verificar los valores de los arrays
        /* foreach($post as $key){
        $this->args[$key] = $_POST[$key];
        } */
    }

    public function contains($requested)
    {

        //Eliminamos el separador del get
        $t = explode("?", $requested);
        $request = $t[0];

        for ($i = 0; $i < count($this->tree); $i++) {
            $path = $this->tree[$i][0];


            if ($request == $path) {
                return true;
            }
        }
        return false;
    }

    public function execute($requested)
    {
        $this->PrepareArgs();
        $request = $requested;
        

        if (stripos($requested, "?") !== false) {
            $t = explode("?", $requested);
            $query = $t[1];// no hace falta tener la query porque se carga en args
            $request = $t[0];
        }

        for ($i = 0; $i < count($this->tree); $i++) {
            $path = $this->tree[$i][0];
            $func = $this->tree[$i][1];

            if ($request == $path) {
                if (function_exists($func)) {
                    return call_user_func($func, $this->args);
                }

                return json_encode("Error: Función no encontrada");
            }
        }
    }


}