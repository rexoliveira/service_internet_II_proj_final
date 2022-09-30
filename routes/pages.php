<?php
use \App\Controller\Pages;
use \App\Http\Response;

//ROTA HOME
$obRouter->get('/',[
    function(){
        return new Response(200,Pages\Home::getHome());
    }
]);

//ROTA SOBRE
$obRouter->get('/about',[
    function(){
        return new Response(200,Pages\About::getAbout());
    }
]);

//ROTA DINÂMICA
$obRouter->get('/pagina/{idPagina}',[
    function($idPagina){
        return new Response(200,"Página  $idPagina");
    }
]);
