<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {

    private $id, $id_usuario, $tweet, $data;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }
    
    //Método de salvamento de tweets.
    public function salvar(){
        
        $comando = "insert into tb_tweets (id_usuario, tweet) values (:id_usuario, :tweet)";
        $stmt = $this->db->prepare($comando);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':tweet', $this->__get('tweet'));
        $stmt->execute();
        
        return $this;
    }
    
    //Método de recuperação de registros.
    public function getAll(){
        
        $comando = "select 
                t.id, t.id_usuario,
                u.nome,
                t.tweet,
                DATE_FORMAT(t.data , '%d/%m/%Y %H:%i') as data
            from 
                tb_tweets as t
                left join tb_usuarios as u on (t.id_usuario = u.id)
            where
                id_usuario = :id_usuario
                or t.id_usuario in (select id_usuario_seguido from tb_usuarios_seguidores
                where id_usuario_seguidor = :id_usuario)
            order by
                t.data
            desc";
        
        $stmt= $this->db->prepare($comando);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    //Método de remoção de tweet.
    public function removeTweet($id_tweet){
        
        $comando = "delete from tb_tweets where id = :id";
        $stmt = $this->db->prepare($comando);
        $stmt->bindValue(':id', $_POST['tweetId']);
        $stmt->execute();
        
        return true;
    }
    
    
   
}
