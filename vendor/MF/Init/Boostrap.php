<?php

namespace MF\Init;


abstract class Boostrap {
    
    //Este atributo receberá a rota passada na url.
    private $routes;
    
    abstract protected function initRoutes();

        public function __construct() {
        $this->initRoutes();
        $this->run($this->getUrl());
    }
    
    function getRoutes() {
        return $this->routes;
    }

    function setRoutes(array $routes) {
        $this->routes = $routes;
    }
    
     //Método de captura de url.
    protected function run($url){
        
        foreach ($this->getRoutes() as $key => $rota) {
            
            //Verificando se a rota existe.
            if($url == $rota['route']){
               $class = "App\\Controllers\\" . $rota['controller'];
               
               $controller = new $class;
               
               $action = $rota['action'];
               
               $controller->$action();
            }
        }
    }

    //Nossa função de captura de rota na URL.
    protected function getUrl(){
        
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
