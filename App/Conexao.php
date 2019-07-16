<?php

namespace App;

class Conexao
{
    
    public static function getDB(){
        
        try{
            
            $conexao = new \PDO("mysql:host=localhost;dbname=twitter_clone;charset=utf8", "root", "");
            return $conexao;
            
        } catch (\PDOException $ex) {
            
        }
    }
}
