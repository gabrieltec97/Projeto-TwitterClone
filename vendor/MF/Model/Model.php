<?php

namespace MF\Model;


abstract class Model {
   
    //Atributo que vai receber a conexão com o banco.
    protected $db;
    
    public function __construct(\PDO $db) {
        $this->db = $db;
    }
}
