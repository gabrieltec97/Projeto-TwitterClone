<?php

namespace App\Controllers;

//Recursos do MF.
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{

public function index(){
        
    //Captura de url que passa o erro.
    $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
        
    $this->render('index');
}

public function inscreverse(){

    $this->view->erroCadastro = FALSE;
        
    $this->render('inscreverse');
}

public function registrar(){

    /* Vamos precisar dos métodos que estão em usuário
      alem da conexão com o banco. Com isso podemos instanciar
      o model container que fará isso para nós. */
    $usuario = Container::getModel('usuario');

    //Atribuindo os valores do formulário aos atributos.
    $usuario->__set('nome', $_POST['nome']);
        
    $usuario->__set('email', $_POST['email']);
        
    $usuario->__set('senha', md5($_POST['senha']));

    //Verificação de caracteres digitados corretamente.
    if ($usuario->validarCadastro(true) && count($usuario->verificaEmail()) == 0){

        $usuario->salvar();

        $this->render('cadastro');
    } else {
            
        $this->view->erroCadastro = true;
            
        $this->render('inscreverse');
    }
    }
}
