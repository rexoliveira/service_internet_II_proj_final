<?php

namespace App\Session\Admin;

use App\Model\Entity\User;


class Login
{


    /**
     * Método responsável por iniciar a sessão
     */
    private static function init()
    {
        // VERIFICA SE SESSÃO NÃO ESTÁ ATIVA
        if (\session_status() != PHP_SESSION_ACTIVE) {
            \session_start();
        }
    }


    /**
     * Método responsável por criar o login do usuário
     * @param User $obUser
     * @return boolean
     */
    public static function login($obUser)
    {
        //INICIA A SESSÂO
        self::init();

        //DEFINE A SESSÃO DO USUÁRIO
        $_SESSION['admin']['usuario'] = [
            'id' => $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
        ];
        // SUCESSO
        return true;
    }

    /**
     * Méotodo responsável por verificar se o usuário está logado
     * @return boolean
     */
    public static function isLogged(){
        //INICIA A SESSAO
        self::init();

        //RETORNA A VERIFICAÇÂO
        return isset($_SESSION['admin']['usuario']['id']);
    }

    /**
     * Méotodo responsável por excutar o logout do usuário
     * @return boolean
     */
    public static function logout(){
        //INICIA A SESSAO
        self::init();

        //DESLOGA O USUÁRIO
        unset($_SESSION['admin']['usuario']);

        // SUCESSO
        return true;
    }
}
