<?php

//API: Retorna as rotas padroes (NÃO SÃO RELACIONADAS A OUTROS RECURSOS DO PROJETO) na versão 1 das APIs

use App\Http\Response;
use \App\Controller\Api;

//ROTA RAIZ DA API
$obRouter->get('/api/v1',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200,Api\Api::getDetails($request), 'application/json',);
    }
]);