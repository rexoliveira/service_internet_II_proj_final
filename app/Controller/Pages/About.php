<?php

//API:ABOUT- vai gerenciar as requisições que chegam na página inicial

namespace App\Controller\Pages;
use App\Utils\View;
use App\Model\Entity\Organization;

class About extends Page
{
    /**
     * Método responsavel por retornar o conteúdo (view) da nossa página de sobre
     * @return string
     */
    public static function getAbout()
    {        
        //ORGANIZAÇÂO
        $obOrganization = new Organization;

        //VIEW DA ABOUT
        $content = View::render('pages/about',[
            "name"=>$obOrganization->name,
            "description"=>$obOrganization->description,
            "site"=>$obOrganization->site,
        ]);

        //RETORNA A VIEW DA PÁGINA 
        return parent::getPage('SOBRE > SERVIÇOS', $content);
    }

    
}