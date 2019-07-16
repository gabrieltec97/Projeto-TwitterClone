<?php

namespace MF\Model;

use App\Conexao;

class Container
{
    
//Este método irá identificar com qual model estamos trabalhando.
public static function getModel($model){
     
    //Capturando a model.
    $classe = "\\App\\Models\\".ucfirst($model);
        
    //Instância da conexão.
    $conn = Conexao::getDB();
        
    return new $classe($conn);
}
}