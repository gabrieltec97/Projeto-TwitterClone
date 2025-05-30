<?php

namespace App\Models;
use MF\Model\Model;

class Usuario extends Model
{
    
    /*Atributos que receberão os dados para 
    armazenamento no banco.*/
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $codigo;
    
public function __get($atributo){
    return $this->$atributo;
}
    
public function __set($atributo, $valor) {
    $this->$atributo = $valor;
}
    
    //Função de salvamento de cadastro.
public function salvar(){
        
    $comando = "insert into tb_usuarios(nome, email, senha) values (:nome, 
     :email, :senha)";
       
    //Preparando o comando.
    $stmt = $this->db->prepare($comando);
       
    //Fazendo a ligação para evitar injection.
    $stmt->bindValue(':nome', $this->__get('nome'));
    
    $stmt->bindValue(':email', $this->__get('email'));
    
    $stmt->bindValue(':senha', $this->__get('senha'));
       
    //Execução do comando.
    $stmt->execute();
       
    return $this;
}
    
//Método de validação de cadastro.
public function validarCadastro(){
        
    $valido = true;
        
    /*Verificação se os atributos possuem pelo
    menos 3 caracteres. */
    if(strlen($this->__get('nome')) <3){
        $valido = false;
    }
        
    if(strlen($this->__get('email')) <3){
        $valido = false;
    }
        
    if(strlen($this->__get('senha')) <3){
        $valido = false;
    }
        
    return $valido;
}
    
//Método de verificação contra cadastros repetidos.
public function verificaEmail(){
        
    $comando = 'select * from tb_usuarios where email = :email';
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':email', $this->__get('email'));
    
    $stmt->execute();
       
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
    
//Método responsável por checar se o usuário existe.
public function autenticar(){
        
    $comando = "select id, nome, email from tb_usuarios
    where email = :email and senha = :senha";
        
    $stmt = $this->db->prepare($comando);
    $stmt->bindValue(':email', $this->__get('email'));
    
    $stmt->bindValue(':senha', $this->__get('senha'));
        
    $stmt->execute();
        
    $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        
    //Verificação de existência de dados
    if($usuario['id'] != '' && $usuario['nome'] != ''){
            
        $this->__set('id', $usuario['id']);
        $this->__set('nome', $usuario['nome']);
    }
        
    return $this;
}
    
//Método de recuperação de usuário de acordo com o termo de pesquisa.
public function getAll(){
        
    $comando = "select
                    u.id, u.nome, u.email, 
                    (select count(*) from tb_usuarios_seguidores as us where 
                    us.id_usuario_seguidor = :id_usuario and 
                    us.id_usuario_seguido = u.id) 
                    as seguindo_s_ou_n 
                from 
                    tb_usuarios as u
                where 
                    u.nome 
                like
                    :nome
                and
                    u.id != :id_usuario";
    $stmt = $this->db->prepare($comando);
        
    $stmt->bindValue(':nome', '%' . $this->__get('nome') . '%');
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
        
    $stmt->execute();
        
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
    
//Método de seguir usuário.
public function seguirUsuario($id_usuario_seguindo){
        
    $comando = "insert into tb_usuarios_seguidores (id_usuario_seguidor, 
        id_usuario_seguido) values (:id_usuario, :id_usuario_seguindo)";
        
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    
    $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        
    $stmt->execute();
        
    return true;
}
    
//Método de deixar de seguir usuário.
public function deixarSeguirUsuario($id_usuario_seguindo){
        
    $comando = "delete from tb_usuarios_seguidores where id_usuario_seguidor = :id_usuario 
        and id_usuario_seguido = :id_usuario_seguindo";
        
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    
    $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        
    $stmt->execute();
        
    return true;
}

//Método de recuperação de total de tweets. 
public function getTotalTweets(){

    $comando = "select count(*) as total_tweets from tb_tweets where id_usuario = :id_usuario";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

//Método de recuperação de total de seguidores.
public function totalSeguidores(){

    $comando = "SELECT count(*) as total_seguidores FROM tb_usuarios_seguidores WHERE id_usuario_seguido = :id_usuario";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

//Método de recuperação de total de seguindo.
public function totalSeguindo(){

    $comando = "select COUNT(*) as total_seguindo from tb_usuarios_seguidores where id_usuario_seguidor = :id_usuario";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

//Método para retornar os tweets do usuário.
public function retornaTweetsUsuario(){

    $comando = "select * from tb_tweets where id_usuario = :id_usuario order by data desc";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

//Método para retornar o nome do usuário.
public function retornaNomeUsuario(){

    $comando = "SELECT nome, id FROM tb_usuarios WHERE id = :id_usuario";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    //Método de verificação de segue você.
    public function segueVoce(){

        $comando = "select count(*) from tb_usuarios_seguidores 
        where id_usuario_seguidor = :id_usuario and id_usuario_seguido = :id";
        
        $stmt = $this->db->prepare($comando);
        
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        
        $stmt->bindValue(':id', $_SESSION['id']);
        
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
}
    
//Método de verificação se você segue ou não a pessoa.
public function verificaSeguir(){
        
    $comando = "SELECT count(*) FROM tb_usuarios_seguidores 
        WHERE id_usuario_seguidor = :id_1 and id_usuario_seguido = :id_2";
        
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id_1', $_SESSION['id']);
    
    $stmt->bindValue(':id_2', $this->__get('id'));
    
    $stmt->execute();
        
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

//Método que captura o id do usuário.
public function capturaId(){
    
    $comando = "SELECT id FROM tb_usuarios WHERE email like :email";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':email', '%' . $this->__get('email') . '%');
    
    $stmt->execute();
    
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

//Método que insere o código no banco.
public function insereCodigo(){
    
    $comando = "UPDATE tb_usuarios SET codigo = :codigo WHERE id = :id";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':codigo', $this->__get('codigo'));
    
    $stmt->bindValue(':id', $this->__get('id'));
    
    $stmt->execute();
    
    return true;
}

//Função de verificação de código.
public function verificaCodigo(){
    
    $comando = "SELECT count(*) FROM tb_usuarios WHERE codigo = :codigo";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':codigo', $this->__get('codigo'));
    
    $stmt->execute();
    
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

//Função de troca de senha no banco.
public function atualizaSenha(){
    
    $comando = "UPDATE tb_usuarios set senha = :senha where id = :id";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':senha', $this->__get('senha'));
    
    $stmt->bindValue(':id', $this->__get('id'));
    
    $stmt->execute();
    
    return true;
}

//Função que limpa o campo código do usuário.
public function removeCodigo(){
    
    $comando = "UPDATE tb_usuarios set codigo = null where id = :id";
    
    $stmt = $this->db->prepare($comando);
    
    $stmt->bindValue(':id', $this->__get('id'));
    
    $stmt->execute();
    
    return true;
}
}
