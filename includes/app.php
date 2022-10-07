<?php

//API:Cofigurar o projeto e ser os "start" da aplicação

//Vai cuidar do autoload das classes
require __DIR__ . '/../vendor/autoload.php';

use \App\Utils\View;
use \App\Utils\Dotenv\Environment;
use App\Utils\DatabaseManager\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

//CERREGA VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__.'./../');

// DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT'),
);

// DEFINE A CONSTANTE DE URL DO PROJETO
define('URL', getenv('URL'));

// DEFINE O VALOR PADRÂO DAS VARIÁVEIS
View::init([
    'URL'=> URL
]);

// DEFINE O MAPEAMENTO DE MEDDLEWARE
MiddlewareQueue::setMap([
    'maintenance'=> \App\Http\Middleware\Maintenance::class,
    'required-admin-logout'=> \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login'=> \App\Http\Middleware\RequireAdminLogin::class,
    'api'=> \App\Http\Middleware\Api::class,
    'user-basic-auth'=> \App\Http\Middleware\UserBasicAuth::class,
    'jwt-auth'=> \App\Http\Middleware\JWTAuth::class,
    'cache'=> \App\Http\Middleware\Cache::class,    
]);


// DEFINE O MAPEAMENTO DE MEDDLEWARE PADRÕES (EXECUTADOS EM TODAS AS ROTAS)
MiddlewareQueue::setDefault([
    'maintenance'
]);