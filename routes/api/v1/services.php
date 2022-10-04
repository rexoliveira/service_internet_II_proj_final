<?php

//API: Retorna as rota de listar serviços

use App\Http\Response;
use \App\Controller\Api;

//ROTA API DE LISTAGEM DE SERVIÇOS
$obRouter->get('/api/v1/services',[
    'middlewares'=>[
        'api'
    ],
    function($request){
        return new Response(200,Api\Service::getServices($request), 'application/json',);
    }
]);

//ROTA API CONSULTA POR ID
$obRouter->get('/api/v1/services/{id}',[
    'middlewares'=>[
        'api'
    ],
    function($request, $id){
        return new Response(200,Api\Service::getService($request, $id), 'application/json',);
    }
]);