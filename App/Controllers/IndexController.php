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
    
//Action de esqueci senha.
public function esqueciMinhaSenha(){
    
   $this->render('esqueci_senha');
}

//Action de captura e tratamento de dados.
public function novaSenha(){
    
   $usuario = Container::getModel('usuario');
    
   $user = $_POST['email'];
    
   $usuario->__set('email', $user);
    
   //Chamando método de captura de ID.
   $teste = $usuario->capturaId();
    
   $id = $teste['id'];
   
   //Atribuindo a variável ao escopo global.
   $this->view->teste = $id;
    
   $usuario->__set('id', $id);
    
   //Gerando código
   $codigo = rand(100000,500000);
    
   $usuario->__set('codigo', $codigo);
    
   //Chamando a função de inserção de código no banco.
   $usuario->insereCodigo();
   
   $this->render('trocar_senha');
}

//Action de captura de codigo e nova senha.
public function trocarSenha(){

   $this->render('trocar_senha');
   
}

//Action de alteração de senha.
public function alterarSenha(){
   
    //Instância de usuário.
   $usuario = Container::getModel('usuario');
   
   //Capturando e encapsulando id, código e senha.
   $id = $_POST['id'];
   
   $codigo = $_POST['codigo'];
   
   $novaSenha = $_POST['novaSenha'];
   
   $usuario->__set('id', $id);
   
   $usuario->__set('codigo', $codigo);
   
   $usuario->__set('senha', md5($novaSenha));
   
   $verificacao = $usuario->verificaCodigo();
   
   //Primeiro tratamento de verificação de código.
   if($verificacao['count(*)'] == 1){
       
        $usuario->atualizaSenha();
        $usuario->removeCodigo();
   
        header('Location: /');
       
   }else{
       header('Location: /esqueciMinhaSenha');
   }
   
   
}
}
