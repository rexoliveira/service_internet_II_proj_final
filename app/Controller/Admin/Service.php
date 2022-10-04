<?php

namespace App\Controller\Admin;

use \App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\Item as EntityItem;
use WilliamCosta\DatabaseManager\Pagination;


class Service extends Page{

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
        $obPagination = new Pagination($quantidadetotal, $paginaAtual, 5);
        
        // RESULTADOS DA PÁGINA
        $results = EntityItem::getItems(null, 'id DESC',$obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obItems = $results->fetchObject(EntityItem::class)) {
            
            $items .= View::render('admin/modules/services/item', [
                'id' => $obItems->id,
                'nome_usuario' => $obItems->nome_usuario,
                'item_servico' => $obItems->item_servico,
                'descricao_servico' => $obItems->descricao_servico,
                'data_insercao' => date('d/m/Y H:i:s', strtotime($obItems->data_insercao)),
            ]);
        }
        // RETORNA OS ITENS DE SERVIÇO
        return $items;
    }

    /**
     * Método responsável por rederização da view de listagem de serviços
     * @param Request $request
     * @return string
     */
    public static function getServices($request)
    {
        // CARREGAR CONTEÚDO DA HOME
        $content =  View::render('admin/modules/services/index',[
            'itens'=> self::getItemService($request,$obPagination),
            'pagination'=> parent::getPagination($request,$obPagination),
            'status'=> self::getStatus($request),
           ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('SERVIÇOS > SERVIÇOS', $content,'services');
    }

    /**
     * Método responsável por retornar o formuário de casdastro de um novo serviço
     * @param Request $request
     * @return string
     */
    public static function getNewService($request)
    {
        // CARREGAR CONTEÚDO DO FORMULÁRIO
        $content =  View::render('admin/modules/services/form',[ 
            'title'=>'Cadastrar Serviços',
            'nomeUsuario'=>'',
            'itemServico'=>'',
            'descricaoServico'=>'',
            'status'=>'',
        ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('CADASTRAR-SERVIÇO > SERVIÇOS', $content,'services');
    }

    /**
     * Método responsável por casdastrar um novo serviço no banco
     * @param Request $request
     * @return string
     */
    public static function setNewService($request)
    {
        // POST VARS
        $postVars = $request->getPostVars();

        //NOVA INSTANCIA DE SERVICO
        $obServico = new EntityItem;
        $obServico->nome_usuario=$postVars['nomeUsuario']??'';
        $obServico->item_servico=$postVars['itemServico']??'';
        $obServico->descricao_servico=$postVars['descricaoServico']??'';
        $obServico->cadastrar();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/services/'.$obServico->id.'/edit?status=created');

        return '';
    }


    /**
     * Método responsável por gravar a atualização de um serviço
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditService($request, $id)
    {
        // OBTÉM O SERVIÇO DO BANCO DE DADOS
        $obServico = EntityItem::getServiceById($id);
        
        // VALIDA A INSTANCIA
        if (!$obServico instanceof EntityItem) {
            $request->getRouter()->redirect('/admin/services');
        }         
        
        // POST VARS
        $postVars = $request->getPostVars();

        // ATUALIZA A INSTANCIA
        $obServico->nome_usuario = $postVars['nomeUsuario'] ?? $obServico->nome_usuario;

        $obServico->item_servico = $postVars['itemServico'] ?? $obServico->item_servico;

        $obServico->descricao_servico = $postVars['descricaoServico'] ?? $obServico->descricao_servico;

        $obServico->atualizar();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/services/'.$obServico->id.'/edit?status=updated');
        return '';
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    private static function getStatus($request){
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        // VERIFICAR SE STATUS EXISTE
        if(!isset($queryParams['status']))return '';

        // MENSSAGENS DE STATUS DE ACORDO COM O TIPO
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Serviço criado com sucesso');
            case 'updated':
                return Alert::getSuccess('Serviço atualizado com sucesso');
            case 'deleted':
                return Alert::getSuccess('Serviço excluido com sucesso');
        }

        return '';
    }

    /**
     * Método responsável por editar um serviço no banco
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditService($request, $id)
    {
        // OBTÉM O SERVIÇO DO BANCO DE DADOS
        $obServico = EntityItem::getServiceById($id);
        
        // VALIDA A INSTANCIA
        if (!$obServico instanceof EntityItem) {
            $request->getRouter()->redirect('/admin/services');
        } 
        
        // CARREGAR CONTEÚDO DO FORMULÁRIO
        $content =  View::render('admin/modules/services/form',[ 
            'title'=>'Editar Serviço',
            'nomeUsuario'=> $obServico->nome_usuario,
            'itemServico'=>$obServico->item_servico,
            'descricaoServico'=>$obServico->descricao_servico,
            'status'=>self::getStatus($request),
        ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('EDITAR-SERVIÇO > SERVIÇOS', $content,'services');

    }

    /**
     * Método responsável por retornar o formuário de exclusão de um novo serviço
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getDeleteService($request,$id)
    {
        // OBTÉM O SERVIÇO DO BANCO DE DADOS
        $obServico = EntityItem::getServiceById($id);
        
        // VALIDA A INSTANCIA
        if (!$obServico instanceof EntityItem) {
            $request->getRouter()->redirect('/admin/services');
        } 
        
        // CARREGAR CONTEÚDO DO FORMULÁRIO
        $content =  View::render('admin/modules/services/delete',[ 
            'title'=> "Excluir serviço",
            'nomeUsuario'=> $obServico->nome_usuario,
            'itemServico'=>$obServico->item_servico,
            'descricaoServico'=>$obServico->descricao_servico,
        ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('EXCLUIR-SERVIÇO > SERVIÇOS', $content,'services');
    }

    
    /**
     * Método responsável por excluir um serviço
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteService($request, $id)
    {
        // OBTÉM O SERVIÇO DO BANCO DE DADOS
        $obServico = EntityItem::getServiceById($id);
        
        // VALIDA A INSTANCIA
        if (!$obServico instanceof EntityItem) {
            $request->getRouter()->redirect('/admin/services');
        }         
             
        // EXCLUI O SERVIÇO
        $obServico->excluir();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/services?status=deleted');
        return '';
    }


    

}