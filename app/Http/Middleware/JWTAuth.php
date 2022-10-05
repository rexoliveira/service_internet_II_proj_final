<?php
//API: Classe de controle de acesso as APIs pelo JWT
namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Model\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JWTAuth
{

    /**
     * Método reposável por retornar uma instancia de usuário autenticado
     * @param Request $request
     * @param User
     */
    public function getJWTAuthUser($request)
    {
        // PEGA OS HEADERS
        $headers = $request->getHeaders();

        // TOKEN PURO EM JWT - OBS: 'Bearer ' -> colocar com espaço
        $jwt = isset($headers['Authorization']) ? \str_replace('Bearer ', '', $headers['Authorization']) : '';

        try {
            
            //CREDITO:https://github.com/firebase/php-jwt
            // DECODE - VERIFICA SE A HASH É VÁLIDA
            $decode = (array)JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));
        }
        catch (\Exception $e) {
            throw new \Exception("Token inválido", 403);
        }

        // VERIFICA SE EXISTE ID 
        $email = $decode['email'] ?? '';

        // BUSCA O USUÁRIO PELO E-MAIL
        $obUser = User::getUserByEmail($email);

        // RETORNA O USUÁRIO
        return $obUser instanceof User ? $obUser : false;
    }

    /**
     * Método reposável por validar o acesso via JWT
     * @param Request $request
     */
    public function auth($request)
    {
        // VERIFICA O USUÁRIO RECEBIDO
        if ($obUser = $this->getJWTAuthUser($request)) {
            // SETA UMA INSTANCIA DE USUÁRIO AUTENTICADO EM REQUEST, PARA RECUPERAR DEPOIS
            $request->setUser($obUser);
            return true;
        }
        //EMITE O ERRO DE SENHA INVÁLIDO
        throw new \Exception("Acesso negado", 403);
    }


    /**
     * Método reposável por executar o middleware
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        // REALIZA VALIDAÇÂO DO ACESSO VIA JWT
        $this->auth($request);

        // EXECUTA O PRÓXIMO NÍVEL DO MEDDLEWARE
        return $next($request);

    }

}