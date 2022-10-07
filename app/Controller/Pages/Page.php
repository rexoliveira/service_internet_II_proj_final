<?php

//API:Page- vai gerenciar as requisições que chegam na página inicial

namespace App\Controller\Pages;
use \App\Utils\View;
use \App\Http\Request;
use \App\Utils\DatabaseManager\Pagination;

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
     * Método responsável por renderizar o layout de paginação
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     * 
     */
    public static function getPagination($request, $obPagination)
    {
        // OBTER AS PÁGINAS
        $pages = $obPagination->getPages();

        // VERIFICA A QUANTIDADE DE PÁGINAS
        if (count($pages) <= 1)
            return '';

        // LINKS 
        $links = '';


        // URL ATUAL (SEM GETS)
        $url = $request->getRouter()->getCurrentUrl();

        // GET
        $queryParams = $request->getQueryParams();

        // RENDERIZA OS LINKS
        foreach ($pages as $page) {
            //ALTERA A PÁGINA
            $queryParams['page'] = $page['page'];

            // LINK
            $link = $url . '?' . http_build_query($queryParams);

            // VIEW
            $links .= View::render('pages/pagination/link', [
                "page" => $page['page'],
                "link" => $link,
                "active" => $page['current'] ? 'active' : '',
            ]);
        }

        // RENDERIZA BOX PAGINAÇÂO
        return View::render('pages/pagination/box', [
            "links" => $links,
        ]);
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