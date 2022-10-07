<?php

//API:HOME- vai gerenciar as requisições que chegam na página inicial

namespace App\Controller\Pages;

use \App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\Item as EntityItem;
use \App\Utils\DatabaseManager\Pagination;
class Item extends Page
{

    /**
     * Método responsável por obter a rederização dos itens de depoimentos para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getItemService($request,&$obPagination)
    {
        //ITENS DE SERVIÇO
        $items = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadetotal = EntityItem::getItems(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //  PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadetotal, $paginaAtual, 3);

        // RESULTADOS DA PÁGINA
        $results = EntityItem::getItems(null, 'id DESC',$obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obItems = $results->fetchObject(EntityItem::class)) {
            $items .= View::render('pages/item/item_service', [
                'nome_usuario' => $obItems->nome_usuario,
                'item_servico' => $obItems->item_servico,
                'data' => date('d/m/Y H:i:s', strtotime($obItems->data_insercao)),
                'descricao' => $obItems->descricao_servico,
            ]);
        }
        // RETORNA OS ITENS DE SERVIÇO
        return $items;
    }


    /**
     * Método responsavel por retornar o conteúdo (view) de itens de serviço
     * @param Request $request
     * @return string
     */
    public static function getItems($request)
    {

        //VIEW DE ITENS DE SERVIÇO
        $content = View::render('pages/items', [
            'items_service' => self::getItemService($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
        ]);

        //RETORNA A VIEW DA PÁGINA 
        return parent::getPage('ITENS > SERVIÇOS', $content);
    }

    /**
     * Método responsável por cadastrar um item de serviço
     * @param Request $request
     * @return string
     */
    public static function insertItem($request)
    {
        // DADOS DO POST
        $postVars = $request->getPostVars();

        //NOVA INSTANCIA DE ITEM
        $obItem = new EntityItem;
        $obItem->nome_usuario = $postVars['nomeUsuario'];
        $obItem->item_servico = $postVars['itemServico'];
        $obItem->descricao_servico = $postVars['descricaoServico'];
        $obItem->cadastrar();

        // RETORNA A PÁGINA DE LISTAGEM DE DEPOIMENTOS
        return self::getItems($request);
    }



}