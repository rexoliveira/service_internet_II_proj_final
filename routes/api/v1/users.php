<?php

//API: Retorna as rotas de listar usuários

use App\Http\Response;
use \App\Controller\Api;

//ROTA API DE LISTAGEM DE USUÁRIOS
$obRouter->get('/api/v1/users',[
    'middlewares'=>[
        'api',
        'jwt-auth',
        'cache'
    ],
    function($request){
        return new Response(200,Api\User::getUsers($request), 'application/json',);
    }
]);

//ROTA API DE CONSULTA DO USUÁRIO ATUAL
//NÂO COLOCAR O MIDDLEWARE DE CACHE NESTE GET
$obRouter->get('/api/v1/users/me',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200,Api\User::getCurrentUser($request), 'application/json',);
    }
]);

//ROTA API CONSULTA POR ID
$obRouter->get('/api/v1/users/{id}',[
    'middlewares'=>[
        'api',
        'jwt-auth',
        'cache'
    ],
    function($request, $id){
        return new Response(200,Api\User::getUser($request, $id), 'application/json',);
    }
]);

//ROTA API DE CADASTRO DE USUÁRIO
$obRouter->post('/api/v1/users',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201,Api\User::setNewUser($request), 'application/json',);
    }
]);

//ROTA API DE ATUALiZAÇÃO DE SERVIÇO
$obRouter->put('/api/v1/users/{id}',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200,Api\User::setEditUser($request,$id), 'application/json',);
    }
]);

//ROTA API DE EXCLUSÃO DE SERVIÇO
$obRouter->delete('/api/v1/users/{id}',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200,Api\User::setDeleteUser($request,$id), 'application/json',);
    }
]);