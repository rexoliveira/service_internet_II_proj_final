<?php

//API:HOME- vai gerenciar as requisições que chegam na página inicial

namespace App\Controller\Pages;
use App\Utils\View;
use App\Model\Entity\Organization;

class Home extends Page
{
    /**
     * Método responsavel por retornar o conteúdo (view) da nossa home
     * @return string
     */
    public static function getHome()
    {
        //ORGANIZAÇÂO
        $obOrganization = new Organization;

        //VIEW DA HOME
        $content = View::render('pages/home', [
            "name" => $obOrganization->name,
        ]);

        //RETORNA A VIEW DA PÁGINA 
        return parent::getPage('HOME > SERVIÇOS', $content);
    }



}