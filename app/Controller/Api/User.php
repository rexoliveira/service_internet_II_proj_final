<?php

//API: Classe FILHA da API de listagem de usuários

namespace App\Controller\Api;

use \App\Http\Request;
use App\Controller\Api\Api;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Api{
   

	/**
     * Método responsável por obter a rederização dos itens de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    private static function getItemUser($request,&$obPagination)
    {
        //ITENS DE USUÁRIO COMO ARRAY
        $items = [];

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

            //CADA POSIÇÃO DO ARRAY VAI RECEBER OS ONJETOS DE USUÁRIO
            $items [] = [
                'id' => (int) $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email,
                'telefone' => $obUser->tel,
                'criado_em' => $obUser->criado_em,
            ];
        }
        // RETORNA OS ITENS DE USUÁRIO
        return $items;
    }

    /**
     * Método responsável por retornar os usuários cadastrados
     * @param Request  $request
     * @return array
     */
    public static function getUsers($request){
        return [
            'usuarios'=>[self::getItemUser($request, $obPagination)],
            'paginacao'=>[parent::getPagination($request, $obPagination)],
        ];
    }

    /**
     * Método responsável por retornar os detalhes de um usuário por ID
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getUser($request, $id){

        // VALIDA O ID DO USUÁRIO SE É UM NÚMERO
        if(!is_numeric($id)){
            throw new \Exception("O id '".$id."' não é válido.", 400);
        }

        //BUSCA USUÁRIO
        $obUser =EntityUser::getUserById($id);

        // VALIDA SE O USUÁRIO EXISTE
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário id ".$id." não encontrado", 404);            
        }

        //RETORNA OS DETALHES DO USUÁRIO
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'telefone' => $obUser->tel,
            'criado_em' => $obUser->criado_em,
        ];
    }

    /**
     * Método responsável por retornar o usuário atualmente conectado
     * @param Request $request
     * @return array
     */
    public static function getCurrentUser($request){
        
        //BUSCA O USUÁRIO ATUAL
        $obUser=$request->getUser();

        //RETORNA OS DETALHES DO USUÁRIO
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'telefone' => $obUser->tel,
            'criado_em' => $obUser->criado_em,
        ];
    }

    /**
     * Método responsável por cadastrar um novo usuário
     * @param  Request $request
     */
    public static function setNewUser($request){
        // POST VARS
        $postVars=$request->getPostVars();
        // VALIDA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['nome']) or 
           !isset($postVars['email']) or 
           !isset($postVars['telefone']) or
           !isset($postVars['password']))
        {
        throw new \Exception("Os nomes 'nome', 'email', 'telefone' e password são obrigatórios!", 400);        
        }

        // VALIDA O EMAIL DO USUÁRIO
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);

        //O EMAIL DIFERENTE DOS OUTROS
        if($obUserEmail instanceof EntityUser){
        throw new \Exception("O e-mail '".$postVars['email']."' já está em uso", 400);        
        }
        
        // NOVO USUÁRIO
        $obUser = new EntityUser;
        $obUser->nome = !empty($postVars['nome']) ? $postVars['nome'] : $obUser->nome;
        $obUser->email = !empty($postVars['email']) ? $postVars['email']: $obUser->email;
        $obUser->tel = !empty($postVars['telefone']) ? $postVars['telefone']: $obUser->tel;
        $obUser->password = !empty($postVars['password']) ? password_hash($postVars['password'], PASSWORD_DEFAULT): $obUser->password;
        $obUser->cadastrar();
        
        // RETORNA OS DETALHES DO USUÁRIO CADASTRADO
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'telefone' => $obUser->tel,
            'criado_em' => $obUser->criado_em,
        ];
    }

    /**
     * Método responsável por atualizar um usuário
     * @param  Request $request
     * @param  integer $id
     */
    public static function setEditUser($request, $id){
        // POST VARS
        $postVars=$request->getPostVars();
        // VALIDA OS CAMPOS OBRIGATORIOS
        if(!isset($postVars['nome']) or 
           !isset($postVars['email']) or 
           !isset($postVars['telefone']) or
           !isset($postVars['password']))
        {
        throw new \Exception("Os nomes 'nome', 'email', 'telefone' e password são obrigatórios!", 400);        
        }

        // BUSCA O USUÁRIO NO BANCO
        $obUser = EntityUser::getUserById($id);

        // VALIDA INSTANCIA
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário id ".$id." não encontrado", 404);    
        }

        // VALIDA O EMAIL DO USUÁRIO
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);

        //O EMAIL DIFERENTE DOS OUTROS MAIS IGUAL AO SEU
        if($obUserEmail instanceof EntityUser && $obUserEmail->id != $id){
        throw new \Exception("O e-mail '".$postVars['email']."' já está em uso", 400);        
        }
        
        // ATUALIZAR O USUÁRIO
        $obUser->nome = !empty($postVars['nome']) ? $postVars['nome'] : $obUser->nome;
        $obUser->email = !empty($postVars['email'])?$postVars['email']: $obUser->email;
        $obUser->tel = !empty($postVars['telefone'])?$postVars['telefone']: $obUser->tel;
        $obUser->password = !empty($postVars['password'])?\password_hash($postVars['password'], PASSWORD_DEFAULT): $obUser->password;
        $obUser->atualizar();
        
        // RETORNA OS DETALHES DO USUÁRIO ATUALIZADO
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'telefone' => $obUser->tel,
            'criado_em' => $obUser->criado_em,
        ];
    }

    /**
     * Método responsável por excluir um usuário
     * @param  Request $request
     * @param  integer $id
     */
    public static function setDeleteUser($request, $id){
              
        // BUSCA O USUÁRIO NO BANCO
        $obUser = EntityUser::getUserById($id);

        // VALIDA INSTANCIA
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário id ".$id." não encontrado", 404);    
        }

        // NÃO PERMITE EXCLUIR O PROPRIO USUÁRIO AUTENTICADO
        if($obUser->id == $request->getUser()->id){
            throw new \Exception("Não é possível excluir o cadastro atualmente conectado, ID-> ".$id." !", 400);    
        }
        
        // EXCLUI UM USUÁRIO        
        $obUser->excluir();
        
        // RETORNA TRUE
        return [
            'sucess' => 'true',
        ];
    }
}