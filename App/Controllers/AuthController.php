<?php

namespace App\Controllers;

//Recursos do MF.
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action
{
    
public function autenticar(){
        
    /*Instância de usuário (Classe responsável
     pela manipulação do usuário no banco
     de dados)*/
        
    $usuario = Container::getModel('usuario');
      
    $usuario->__set('email', $_POST['email']);
      
    $usuario->__set('senha', md5($_POST['senha']));
      
    $usuario->autenticar();
      
    //Verificação de autenticação.
    if($usuario->__get('id') != '' && $usuario->__get('nome') != ''){
          
        //Iniciando sessão.
        session_start();
          
        $_SESSION['id'] = $usuario->__get('id');
          
        $_SESSION['nome'] = $usuario->__get('nome');
          
        //Redirecionando para uma página protegida.
        header('Location: /timeline');
          
    }else{
        header('Location: /?login=erro');
    }
}
    
//Método de logout do sistema
public function sair(){
        
    session_start();
        
    session_destroy();
        
    header('Location: /');
}
}
