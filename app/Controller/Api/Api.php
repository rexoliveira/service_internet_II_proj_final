<?php

//API: Classe MAE de todas as APIs

namespace App\Controller\Api;

use \WilliamCosta\DatabaseManager\Pagination ;
use \App\Http\Request;

class Api{

    /**
     * Método responsável por retornar os detalhes da API
     * @param Request  $request
     * @return array
     */
    public static function getDetails($request){
        return [
            'nome'=> 'API - SERVIÇO',
            'versao'=> 'v1.0.0',
            'autor'=>'Rodrigo Oliveira',
            'email'=>'rodrigooliveira.cm015@academico.ifsul.edu.br'
        ];
    }

    /**
     * Método responsável por retornar os detalhes da paginação
     * @param Request  $request
     * @param Pagination  $obPagination
     * @return array
     */
    protected static function getPagination($request, $obPagination){
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();
        
        // PÁGINA
        $pages = $obPagination->getPages();

        // RETORNO DOS DETALHES

        return [
            'pagina-atual'=> isset($queryParams['page'])?(int) $queryParams['page'] : 1,
            'quantidade-pagina'=> !empty($pages)? count($pages) : 1,
        ];

    }

}