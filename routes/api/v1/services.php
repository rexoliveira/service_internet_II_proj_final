<?php

//API: Retorna as rotas de listar serviços

use App\Http\Response;
use \App\Controller\Api;

//ROTA API DE LISTAGEM DE SERVIÇOS
$obRouter->get('/api/v1/services',[
    'middlewares'=>[
        'api',
        'jwt-auth',
        'cache'
    ],
    function($request){
        return new Response(200,Api\Service::getServices($request), 'application/json',);
    }
]);

//ROTA API CONSULTA POR ID
$obRouter->get('/api/v1/services/{id}',[
    'middlewares'=>[
        'api',
        'jwt-auth',
        'cache'
    ],
    function($request, $id){
        return new Response(200,Api\Service::getService($request, $id), 'application/json',);
    }
]);

//ROTA API DE CADASTRO DE SERVIÇO
$obRouter->post('/api/v1/services',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201,Api\Service::setNewService($request), 'application/json',);
    }
]);

//ROTA API DE ATUALiZAÇÃO DE SERVIÇO
$obRouter->put('/api/v1/services/{id}',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200,Api\Service::setEditService($request,$id), 'application/json',);
    }
]);

//ROTA API DE EXCLUSÃO DE SERVIÇO
$obRouter->delete('/api/v1/services/{id}',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200,Api\Service::setDeleteService($request,$id), 'application/json',);
    }
]);