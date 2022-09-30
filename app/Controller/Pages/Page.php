<?php

//API:Page- vai gerenciar as requisições que chegam na página inicial

namespace App\Controller\Pages;
use App\Utils\View;

class Page
{
    /**
     * Método reponsável por renderizar o TOPO da página
     * @return string
     */
    private static function getHeader()
    {
        return View::render('pages/header');
    }
    /**
     * Método reponsável por renderizar o RODAPÉ da página
     * @return string
     */
    private static function getFooter()
    {
        return View::render('pages/footer');
    }
    /**
     * Método responsavel por retornar o conteúdo (view) da nossa página genérica
     * @return string
     */
    public static function getPage($title, $content)
    {
        return View::render('pages/page', [
            "title" => $title,
            "header" => self::getHeader(),
            "content" => $content,
            "footer" => self::getFooter(),
        ]);
    }
}