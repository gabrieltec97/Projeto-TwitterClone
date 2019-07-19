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
    
   /*Fizemos esse objeto global para que
    pudéssemos verificar se há erro ou na 
    troca de senha.*/
   $this->view->verifica = isset($_GET['verificar']) ? $_GET['verificar'] : '';
    
   $this->render('esqueci_senha');
}

//Action de captura e tratamento de dados.
public function novaSenha(){
    
   $usuario = Container::getModel('usuario');
    
   $user = $_POST['email'];
    
   $usuario->__set('email', $user);
    
   //Chamando método de captura de ID.
   $capturarId = $usuario->capturaId();
    
   $id = $capturarId['id'];
   
   $usuario->__set('id', $id);
   
   $this->view->verifica_senha = isset($_GET['verificar']) ? $_GET['verificar'] : '';
   
   //Atribuindo a variável ao escopo global.
   $this->view->id = $id;
    
   //Gerando código
   $codigo = rand(100000,500000);
    
   $usuario->__set('codigo', $codigo);
    
   //Chamando a função de inserção de código no banco.
   $usuario->insereCodigo();
   
   $this->render('trocar_senha');
}

//Action de captura de codigo e nova senha.
/*Esta action apenas renderiza a tela caso
alguma verificação falhe*/
public function trocarSenha(){
   
   //Atribuição de verificação se as senhas coincidem.
   $this->view->verifica_senha = isset($_GET['verificar']) ? $_GET['verificar'] : '';
    
   $this->render('trocar_senha');
}

//Action de alteração de senha.
public function alterarSenha(){
   
   $usuario = Container::getModel('usuario');
   
   //Capturando e encapsulando id, código e senha.
   $id = $_POST['id'];
   
   $codigo = $_POST['codigo'];
   
   /*Esta é a nova senha que o usuário digita
   primeiro, esta senha não será encapsulada, 
   pois ela só serve para fins de comparação.*/
   $novaSenha_1 = $_POST['novaSenha1'];
   
   $novaSenha = $_POST['novaSenha'];
   
   $usuario->__set('id', $id);
   
   $usuario->__set('codigo', $codigo);
   
   $usuario->__set('senha', md5($novaSenha));
   
   $verificacao = $usuario->verificaCodigo();
   
   //Primeiro tratamento de verificação de código.
   if($verificacao['count(*)'] == 1 && $novaSenha == $novaSenha_1){
       
       $usuario->atualizaSenha();
       
       $usuario->removeCodigo();
   
       header('Location: /');
       
   }elseif($novaSenha != $novaSenha_1){
    
        header('Location: /trocarSenha?verificar=erro'); 
   }else{
     
       $usuario->removeCodigo();
       header('Location: /esqueciMinhaSenha?verificar=erro');
   }  
}
}
