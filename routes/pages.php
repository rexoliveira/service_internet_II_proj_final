<?php
use \App\Controller\Pages;
use \App\Http\Response;

//ROTA HOME
$obRouter->get('/',[
    'middlewares'=>[
        'cache'
    ],
    function(){
        return new Response(200,Pages\Home::getHome());
    }
]);

//ROTA SOBRE
$obRouter->get('/about',[
    'middlewares'=>[
        'cache'
    ],
    function(){
        return new Response(200,Pages\About::getAbout());
    }
]);

//ROTA SERVIÇOS
$obRouter->get('/items',[
    'middlewares'=>[
        'cache'
    ],
    function($request){
        return new Response(200,Pages\Item::getItems($request));
    }
]);

//ROTA SERVIÇOS (INSERT)
$obRouter->post('/items',[
    function($request){
        
        return new Response(200,Pages\Item::insertItem($request));
    }
]);


