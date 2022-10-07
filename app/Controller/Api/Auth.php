<?php

//API: Classe FILHA para gerar o token JWT

namespace App\Controller\Api;

use Firebase\JWT\JWT;
use \App\Utils\DatabaseManager\Pagination ;
use \App\Http\Request;
use \App\Model\Entity\User as EntityUser;

class Auth extends Api{

    /**
     * Método responsável por gerar um token jwt
     * @param Request $request
     * @return array
     */
    public static function generateToken($request){

        // POST VARS
        $postVars = $request->getPostVars();

        // VALIDA OS CAMPOS OBRIGATÓRIOS
        if(!isset($postVars['email']) or 
           !isset($postVars['password']))
        {
        throw new \Exception("Os nomes 'email' e 'password' são obrigatórios!", 400);        
        }

        // BUSCA O SERVIÇO NO BANCO
        $obUser = EntityUser::getUserByEmail($postVars['email']);
        
        // VALIDA INSTANCIA
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário ou password são inválidos", 404);    
        }

        // VALIDA O PASSWORD DO USUÁRIO
        if(!password_verify($postVars['password'],$obUser->password)){
            throw new \Exception("O usuário ou password são inválidos", 404);    
        }

        // PAYLOAD
        $peyload = [
            'email'=>$obUser->email
        ];
        
        //RETORNA O TOKEN GERADO
        return [
            'token'=>JWT::encode($peyload, getenv('JWT_KEY'),"HS256")
        ];
    }

}