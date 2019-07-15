<?php

namespace MF\Controller;

abstract class Action {
   
    protected $view;
    
    public function __construct() {
        $this->view = new \stdClass();
    }
    
    protected function render($view){
        
        //Novo atributo criado, uma vez que o outro ficou fora de escopo.
        $this->view->page = $view;
        require_once "../App/Views/layout.phtml";
    }
    
    protected function content(){
        
        //Pegando o caminho do controller atual.
        $classeAtual = get_class($this);
        
        
        /*Removendo o caminho até chegar no controlador,
        uma vez que é o mesmo caminho para todos.*/
        $classeAtual = str_replace('App\\Controllers\\', '', $classeAtual);
        
        
        //Removendo o nome "Controller".
        $classeAtual = strtolower(str_replace('Controller', '', $classeAtual));
        
        require_once "../App/Views/".$classeAtual."/".$this->view->page.".phtml";
    }
}
