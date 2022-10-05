<?php
//API: Classe de controle de acesso as APIs
namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Model\Entity\User;


class UserBasicAuth
{

    /**
     * Método reposável por retornar uma instancia de usuário autenticado
     * @param User
     */
    public function getBasicAuthUser()
    {
        //  VERIFICA A EXISTÊNCIA DOS DADOS DE ACESSO
        if (!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        }

        //BUSCA USUÁRIO POR EMAIL
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        // VERIFICA A INSTANCIA
        if (!$obUser instanceof User) {
            return false;
        }
        // VALIDA A SENHA E RETORNA O USUÁRIO
        return \password_verify($_SERVER['PHP_AUTH_PW'], $obUser->password) ? $obUser : false;
    }

    /**
     * Método reposável por validar o acesso via basic auth
     * @param Request $request
     */
    public function basicAuth($request)
    {
        //  VERIFICA O USUÁRIO RECEBIDO
        if ($obUser = $this->getBasicAuthUser()) {
            // SETA UMA INSTANCIA DE USUÁRIO AUTENTICADO EM REQUEST, PARA RECUPERAR DEPOIS
            $request->setUser($obUser);
            return true;
        }
        // EMITE O ERRO DE SENHA INVÁLIDO
        throw new \Exception("Usuário ou senha inválidos", 403);
    }


    /**
     * Método reposável por executar o middleware
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        // REALIZA VALIDAÇÂO DO ACESSO VIA BASCI AUTH
        $this->basicAuth($request);

        // EXECUTA O PRÓXIMO NÍVEL DO MEDDLEWARE
        return $next($request);

    }

}