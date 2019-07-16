<?php

namespace App;

use MF\Init\Boostrap;

class Route extends Boostrap
{

//Rotas que a nossa aplicação possui.
protected function initRoutes(){

    $routes['home'] = array(
        'route' => '/',
        'controller' => 'IndexController',
        'action' => 'index'
    );

    $routes['inscrever-se'] = array(
        'route' => '/inscrever-se',
        'controller' => 'IndexController',
        'action' => 'inscreverse'
    );

    $routes['registrar'] = array(
        'route' => '/registrar',
        'controller' => 'IndexController',
        'action' => 'registrar'
    );

    $routes['autenticar'] = array(
        'route' => '/autenticar',
        'controller' => 'AuthController',
        'action' => 'autenticar'
    );

    $routes['timeline'] = array(
        'route' => '/timeline',
        'controller' => 'AppController',
        'action' => 'timeline'
    );

    $routes['sair'] = array(
        'route' => '/sair',
        'controller' => 'AuthController',
        'action' => 'sair'
    );

    $routes['tweet'] = array(
        'route' => '/tweet',
        'controller' => 'AppController',
        'action' => 'tweet'
    );
        
    $routes['quem_seguir'] = array(
        'route' => '/quem_seguir',
        'controller' => 'AppController',
        'action' => 'quemSeguir'
    );
        
    $routes['acao'] = array(
        'route' => '/acao',
        'controller' => 'AppController',
        'action' => 'acao'
    );
        
    $routes['remover'] = array(
        'route' => '/remover',
        'controller' => 'AppController',
        'action' => 'remover'
    );

    $routes['perfil'] = array(
        'route' => '/perfil',
        'controller' => 'AppController',
        'action' => 'perfil'
    );

    $this->setRoutes($routes);
}
}
