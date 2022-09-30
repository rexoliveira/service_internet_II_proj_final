<?php

//Vai cuidar do autoload das classes
require __DIR__ . '/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;

//CERREGA VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

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