<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE SERVIÇOS
$obRouter->get('/admin/services',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Service::getServices($request));
    }
]);

//ROTA DE CASDASTRO DE NOVO SERVIÇO
$obRouter->get('/admin/services/new',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Service::getNewService($request));
    }
]);

//ROTA DE CASDASTRO DE UM NOVO SERVIÇO (POST)
$obRouter->post('/admin/services/new',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Service::setNewService($request));
    }
]);

//ROTA DE EDIÇÃO DE UM SERVIÇO
$obRouter->get('/admin/services/{id}/edit',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200,Admin\Service::getEditService($request, $id));
    }
]);

//ROTA DE EDIÇÃO DE UM SERVIÇO (POST)
$obRouter->post('/admin/services/{id}/edit',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200,Admin\Service::setEditService($request, $id));
    }
]);


//ROTA PARA DELETAR UM SERVIÇO
$obRouter->get('/admin/services/{id}/delete',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200,Admin\Service::getDeleteService($request, $id));
    }
]);


//ROTA DE EXCLUSÃO DE UM SERVIÇO (POST)
$obRouter->post('/admin/services/{id}/delete',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request, $id){
        return new Response(200,Admin\Service::setDeleteService($request, $id));
    }
]);