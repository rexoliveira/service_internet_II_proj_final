<?php

//API: Classe FILHA da API de listagem de serviços

namespace App\Controller\Api;

use \App\Http\Request;
use App\Controller\Api\Api;
use \App\Model\Entity\Item as EntityService;
use \WilliamCosta\DatabaseManager\Pagination;

class Service extends Api{
   

	/**
     * Método responsável por obter a rederização dos itens de serviços para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    private static function getItemService($request,&$obPagination)
    {
        //ITENS DE SERVIÇO COMO ARRAY
        $items = [];

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadetotal = EntityService::getItems(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //  PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadetotal, $paginaAtual, 5);
        
        // RESULTADOS DA PÁGINA
        $results = EntityService::getItems(null, 'id DESC',$obPagination->getLimit());

        
        // RENDERIZA O ITEM
        while ($obService = $results->fetchObject(EntityService::class)) {

            //CADA POSIÇÃO DO ARRAY VAI RECEBER OS ONJETOS DE SERVIÇO
            $items [] = [
                'id' => (int) $obService->id,
                'nome_usuario' => $obService->nome_usuario,
                'item_servico' => $obService->item_servico,
                'descricao_servico' => $obService->descricao_servico,
                'data_insercao' => $obService->data_insercao,
            ];
        }
        // RETORNA OS ITENS DE SERVIÇO
        return $items;
    }

    /**
     * Método responsável por retornar os serviços cadastrados
     * @param Request  $request
     * @return array
     */
    public static function getServices($request){
        return [
            'servicos'=>[self::getItemService($request, $obPagination)],
            'paginacao'=>[parent::getPagination($request, $obPagination)],
        ];
    }

    /**
     * Método responsável por retornar os detalhes de um serviço por ID
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getService($request, $id){

        // VALIDA O ID DO SERVIÇO SE É UM NÚMERO
        if(!is_numeric($id)){
            throw new \Exception("O id '".$id."' não é válido.", 400);
        }

        //BUSCA SERVIÇO
        $obService =EntityService::getServiceById($id);

        // VALIDA SE O SERVIÇO EXISTE
        if(!$obService instanceof EntityService){
            throw new \Exception("O serviço id ".$id." não encontrado", 404);            
        }

        ////RETORNA OS DETALHES DO SERVIÇO
        return [
            'id' => (int) $obService->id,
            'nome_usuario' => $obService->nome_usuario,
            'item_servico' => $obService->item_servico,
            'descricao_servico' => $obService->descricao_servico,
            'data_insercao' => $obService->data_insercao,
        ];
    }

    /**
     * Método responsável por cadastrar um novo serviço
     * @param  Request $request
     */
    public static function setNewService($request){
        // POST VARS
        $postVars=$request->getPostVars();
        // VALIDA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['nome-usuario']) or 
           !isset($postVars['item-servico']) or 
           !isset($postVars['descricao-servico']))
        {
        throw new \Exception("Os nomes 'nome-usuario', 'item-servico' e 'descricao-servico' são obrigatórios!", 400);        
        }
        
        // NOVO SERVIÇO
        $obService = new EntityService;
        $obService->nome_usuario = $postVars['nome-usuario'];
        $obService->item_servico = $postVars['item-servico'];
        $obService->descricao_servico = $postVars['descricao-servico'];
        $obService->cadastrar();
        
        // RETORNA OS DETALHES DO SERVIÇO CADASTRADO
        return [
            'id' => (int) $obService->id,
            'nome_usuario' => $obService->nome_usuario,
            'item_servico' => $obService->item_servico,
            'descricao_servico' => $obService->descricao_servico,
            'data_insercao' => $obService->data_insercao,
        ];
    }

    /**
     * Método responsável por atualizar um serviço
     * @param  Request $request
     * @param  integer $id
     */
    public static function setEditService($request, $id){
        // POST VARS
        $postVars=$request->getPostVars();
        // VALIDA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['nome-usuario']) or 
           !isset($postVars['item-servico']) or 
           !isset($postVars['descricao-servico']))
        {
        throw new \Exception("Os nomes 'nome-usuario', 'item-servico' e 'descricao-servico' são obrigatórios!", 400);        
        }

        // BUSCA O SERVIÇO NO BANCO
        $obService = EntityService::getServiceById($id);

        // VALIDA INSTANCIA
        if(!$obService instanceof EntityService){
            throw new \Exception("O serviço id ".$id." não encontrado", 404);    
        }
        
        // ATUALIZAR O SERVIÇO
        $obService->nome_usuario = $postVars['nome-usuario'];
        $obService->item_servico = $postVars['item-servico'];
        $obService->descricao_servico = $postVars['descricao-servico'];
        $obService->atualizar();
        
        // RETORNA OS DETALHES DO SERVIÇO ATUALIZADO
        return [
            'id' => (int) $obService->id,
            'nome_usuario' => $obService->nome_usuario,
            'item_servico' => $obService->item_servico,
            'descricao_servico' => $obService->descricao_servico,
            'data_insercao' => $obService->data_insercao,
        ];
    }

    /**
     * Método responsável por excluir um serviço
     * @param  Request $request
     * @param  integer $id
     */
    public static function setDeleteService($request, $id){
              
        // BUSCA O SERVIÇO NO BANCO
        $obService = EntityService::getServiceById($id);

        // VALIDA INSTANCIA
        if(!$obService instanceof EntityService){
            throw new \Exception("O serviço id ".$id." não encontrado", 404);    
        }
        
        // EXCLUI UM SERVIÇO        
        $obService->excluir();
        
        // RETORNA TRUE
        return [
            'sucess' => 'true',
        ];
    }
}