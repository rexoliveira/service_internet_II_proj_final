<?php

namespace App\Controller\Admin;

use \App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use WilliamCosta\DatabaseManager\Pagination;


class User extends Page{

    /**
     * Método responsável por obter a rederização dos itens de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getItemUser($request,&$obPagination)
    {
        //ITENS DE USUÁRIO
        $items = '';

        // QUANTIDADE TOTAL DE REGISTROS
        $quantidadetotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        //  PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadetotal, $paginaAtual, 5);
        
        // RESULTADOS DA PÁGINA
        $results = EntityUser::getUsers(null, 'id DESC',$obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obUser = $results->fetchObject(EntityUser::class)) {
            
            $items .= View::render('admin/modules/users/item', [
                'id' => $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email,
                'telefone' => $obUser->tel,
                'criado_em' => date('d/m/Y H:i:s', strtotime($obUser->criado_em)),
            ]);
        }
        // RETORNA OS USUÁRIOS
        return $items;
    }

    /**
     * Método responsável por rederização da view de listagem de usuários
     * @param Request $request
     * @return string
     */
    public static function getUsers($request)
    {
        // CARREGAR CONTEÚDO DA HOME
        $content =  View::render('admin/modules/users/index',[
            'title'=>'Usuários',
            'itens'=> self::getItemUser($request,$obPagination),
            'pagination'=> parent::getPagination($request,$obPagination),
            'status'=> self::getStatus($request),
           ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('USUÁRIOS > SERVIÇOS', $content,'users');
    }

    /**
     * Método responsável por retornar o formuário de casdastro de um novo usuário
     * @param Request $request
     * @return string
     */
    public static function getNewUser($request)
    {
        // CARREGAR CONTEÚDO DO FORMULÁRIO
        $content =  View::render('admin/modules/users/form',[ 
            'title'=>'Cadastrar Usuário',
            'nome'=>'',
            'email'=>'',
            'telefone'=>'',
            'password'=>'',
            'status'=>self::getStatus($request),
            'required'=>'required',
        ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('CADASTRAR-USUÁRIO > SERVIÇOS', $content,'users');
    }

    /**
     * Método responsável por casdastrar um usuário no banco
     * @param Request $request
     * @return string
     */
    public static function setNewUser($request)
    {
        // POST VARS
        $postVars = $request->getPostVars();

        $nome = $postVars['nome']  ?? '';
        $email = $postVars['email']  ?? '';
        $telefone = $postVars['telefone']  ?? '';
        $password = $postVars['password']  ?? '';
        
        // VALIDA O EMAIL DO USUÀRIO
        $obUser = EntityUser::getUserByEmail($email);

        if($obUser instanceof EntityUser){
        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }
       
        //NOVA INSTANCIA DE USUÁRIO
        $obUser = new EntityUser;
        $obUser->nome=$nome;
        $obUser->email=$email;
        $obUser->tel=$telefone;
        $obUser->password= \password_hash($password,PASSWORD_DEFAULT);
        $obUser->cadastrar();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');

        return '';
    }


    /**
     * Método responsável por gravar a atualização de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditUser($request, $id)
    {
        // POST VARS
        $postVars = $request->getPostVars();
        $email = $postVars['email']  ?? '';        
        
        // VALIDA O EMAIL DO USUÀRIO
        $obUserEmail = EntityUser::getUserByEmail($email);

        //O EMAIL DIFERENTE DOS OUTROS MAIS IGUAL AO SEU
        if($obUserEmail instanceof EntityUser && $obUserEmail->id != $id){
        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }

        // OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);
        
        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }         
        
        // POST VARS
        $postVars = $request->getPostVars();

        // ATUALIZA A INSTANCIA
        $obUser->nome = $postVars['nome'] ?? $obUser->nome;

        $obUser->email = $postVars['email'] ?? $obUser->email;

        $obUser->tel = $postVars['telefone'] ?? $obUser->tel;

        $obUser->password = !empty($postVars['password']) ? password_hash($postVars['password'],PASSWORD_DEFAULT) : $obUser->password;

        $obUser->atualizar();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
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
                return Alert::getSuccess('Usuário criado com sucesso');
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso');
            case 'deleted':
                return Alert::getSuccess('Usuário excluido com sucesso');
            case 'duplicated':
                return Alert::getError('E-mail já está em uso');
        }

        return '';
    }

    /**
     * Método responsável por editar um usuário no banco
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditUser($request, $id)
    {
        // OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);
        
        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        } 
        
        // CARREGAR CONTEÚDO DO FORMULÁRIO
        $content =  View::render('admin/modules/users/form',[ 
            'title'=>'Editar Usuário',
            'nome'=> $obUser->nome,
            'email'=>$obUser->email,
            'telefone'=>$obUser->tel,
            'required'=>'',
            'status'=>self::getStatus($request),
        ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('EDITAR-USUÁRIO > SERVIÇOS', $content,'users');

    }

    /**
     * Método responsável por retornar o formuário de exclusão de um novo serviço
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getDeleteUser($request,$id)
    {
        // OBTÉM O SERVIÇO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);
        
        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        } 
        
        // CARREGAR CONTEÚDO DO FORMULÁRIO
        $content =  View::render('admin/modules/users/delete',[ 
            'title'=> "Excluir Usuário",
            'nome'=> $obUser->nome,
            'email'=>$obUser->email,
            'telefone'=>$obUser->tel,
            'criado_em'=>$obUser->criado_em,
        ]);

        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('EXCLUIR-USUÁRIO > SERVIÇOS', $content,'users');
    }

    
    /**
     * Método responsável por excluir um serviço
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteUser($request, $id)
    {
        // OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);
        
        // VALIDA A INSTANCIA
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }         
             
        // EXCLUI O USUÁRIO
        $obUser->excluir();

        // REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users?status=deleted');
        return '';
    }


    

}