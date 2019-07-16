<?php

namespace App\Controllers;

//Recursos do MF.
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

public function timeline(){

    $this->validaAutenticacao();

    //Recuperação dos tweets.
    $tweet = Container::getModel('tweet');

    //Parâmetro para capturar apenas os tweets daquele usuário.
    $tweet->__set('id_usuario', $_SESSION['id']);

    $tweets = $tweet->getAll();

    //Atribuindo o valor de tweets à este novo atributo.
    $this->view->tweets = $tweets;

    //Instanciando a model de usuário para trabalhar com os dados dele.
    $usuario = Container::getModel('usuario');
        
    $usuario->__set('id', $_SESSION['id']);

    $this->view->total_tweets = $usuario->getTotalTweets();
        
    $this->view->total_seguidores = $usuario->totalSeguidores();
        
    $this->view->total_seguindo = $usuario->totalSeguindo();

    $this->render('timeline');
}

//Método de tratamento de cada tweet postado.
public function tweet(){

    $this->validaAutenticacao();

    $tweet = Container::getModel('tweet');

    /* Vamos informar ao banco o conteúdo do 
    tweet e qual usuário o postou. */
    $tweet->__set('tweet', $_POST['tweet']);
    $tweet->__set('id_usuario', $_SESSION['id']);

    $tweet->salvar();

    header('Location: /timeline');
}

//Método que valida a autenticação do usuário.
public function validaAutenticacao(){

    session_start();

    if (!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){

        header('Location: /?login=erro');
    }
}

//Método de procura e seguimento de pessoas.
public function quemSeguir(){

    $this->validaAutenticacao();

    $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

    $usuarios = array();

    if($pesquisarPor != ''){

        $usuario = Container::getModel('usuario');

        $usuario->__set('nome', $pesquisarPor);
        
        $usuario->__set('id', $_SESSION['id']);
        
        $usuarios = $usuario->getAll();           
    }

    $this->view->usuarios = $usuarios;

    //Instanciando a model de usuário para trabalhar com os dados dele.
    $usuario = Container::getModel('usuario');
    
    $usuario->__set('id', $_SESSION['id']);

    $this->view->total_tweets = $usuario->getTotalTweets();
    
    $this->view->total_seguidores = $usuario->totalSeguidores();
    
    $this->view->total_seguindo = $usuario->totalSeguindo();
        
    $this->render('quemSeguir');
    
}

//Método de ação (Seguir ou deixar de seguir).
public function acao(){

    $this->validaAutenticacao();

    //Descobrir qual será a ação.
    $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
    
    $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
        
        
    //Instanciar a classe usuário.
    $usuario = Container::getModel('usuario');
        
    //Recuperando o id do usuário da seção (Usuário que está seguindo).
    $usuario->__set('id', $_SESSION['id']);
        
        
    if($acao == 'seguir'){
          
        $usuario->seguirUsuario($id_usuario_seguindo);
            
    }else if($acao == 'deixar_de_seguir'){
            
        $usuario->deixarSeguirUsuario($id_usuario_seguindo); 
    }
        
    header('location: /quem_seguir?pesquisarPor=' . $this->view->nome );
    
}
       
//Action de remoção de tweets.
public function remover(){
        
    $this->validaAutenticacao();
        
    $id_tweet = isset($_POST['tweetId']) ? $_POST['tweetId'] : '';
        
    //Instância de modelo do usuário.
    $usuario = Container::getModel('tweet');
    
    $usuario->removeTweet($id_tweet);
        
    header('Location: /timeline');
}

//Action de perfil de usuário.
public function perfil(){

    $this->validaAutenticacao();

    $id = $_GET['id_user'];

    //Instância da model de usuário.
    $usuario = Container::getModel('usuario');
    
    $usuario->__set('id', $id);


    //Métodos para recuperar os tweets e o nome do usuário.
    $tweets = $usuario->retornaTweetsUsuario();
    
    $nome = $usuario->retornaNomeUsuario();
    
    $valor = $usuario->segueVoce();

    $this->view->tweets_user = $tweets;
    
    $this->view->nome = $nome;
    
    $this->view->seguimento = $valor;

    
    //Métodos de recuperação de seguidores e tweets.
    $this->view->total_tweets = $usuario->getTotalTweets();
    
    $this->view->total_seguidores = $usuario->totalSeguidores();
    
    $this->view->total_seguindo = $usuario->totalSeguindo();
        
        
    //Método de verificação de você segue ou não a pessoa.
    $verificacao = $usuario->verificaSeguir();
        
    $this->view->acao = $verificacao;

    $this->render('perfil_usuario');  
}

}
