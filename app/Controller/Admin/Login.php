<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Alert;
use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page
{

    /**
     * Método resposável por retornar a rederização da página de login
     * @param Request $request
     * @param string $errorMessage
     * @return string
     */
    public static function getLogin($request, $erroMessage = null)
    {
        // STATUS VAUI RECEBER A REDENRIZAÇÃO DE UMA VIEW
        $status = !is_null($erroMessage) ? Alert::getError($erroMessage): '';


        // CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('admin/login', [
            'status' => $status,
        ]);


        // RETORNA A PÁGINA COMPLETA
        return parent::getPage('LOGIN > SERVIÇO', $content);
    }

    /**
     * Método responsável por definir o login do usuário
     * @param Request $request
     */
    public static function setLogin($request)
    {
        //POST VARS
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        // BUSCA O USUÁRIO PELO E-MAIL
        $obUser = User::getUserByEmail($email);

        if (!$obUser instanceof User) {
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        // VERIFICA A SENHA DO USUARIO
        if (!password_verify($senha, $obUser->password)) {
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }

        // CRIAR A SESSÃO DE LOIN
        SessionAdminLogin::login($obUser);

        // REDIRECIONA O USUÁRIO PARA HOME ADMIN
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Método responsável por deslogar o usuário
     * @param Request $request
     */
    public static function setLogout($request)
    {
        // DESTROI A SESSÃO DE LOIN
        SessionAdminLogin::logout();

        // REDIRECIONA O USUÁRIO PARA A TELA LOGIN
        $request->getRouter()->redirect('/admin/login');

    }

}