<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;

class Home extends Page
{

    /**
     * Método resposável por rederização da view de home do painel
     * @param Request $request
     * @return string
     */
    public static function getHome($request)
    {
        // CARREGAR CONTEÚDO DA HOME
        $content =  View::render('admin/modules/home/index',[]);


        //RETORNA PÁGINA COMPLETA
        return parent::getPanel('HOME > SERVIÇOS', $content,'home');
    }
    
}