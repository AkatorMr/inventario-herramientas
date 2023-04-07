<?php

class Router
{

    private static $instance = null;
    private $tree = array();
    private $tree_ref = array();
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

    /**
     * @param mixed $func Función a ejecutar cuando se encuentre el path, nombre de la función o función anónima
     */
    public function addApiEntry($path, $func)
    {

        if(is_string($func))
            array_push($this->tree, array("/api/" . $path, $func));
        else
            array_push($this->tree_ref, array("/api/" . $path, $func));

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

        for ($i = 0; $i < count($this->tree_ref); $i++) {
            $path = $this->tree_ref[$i][0];


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

        for ($i = 0; $i < count($this->tree_ref); $i++) {
            $path = $this->tree_ref[$i][0];
            $func = $this->tree_ref[$i][1];

            if ($request == $path) {
                return $func($this->args);
            }
        }
    }


}