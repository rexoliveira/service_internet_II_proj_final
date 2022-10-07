<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use App\Utils\DatabaseManager\Pagination;

class Page
{
    /**
     * Módulos disponíveis no painel
     * @var array
     */
    private static $modules = [
        'home' => [
            'label' => 'Início',
            'link' => URL . '/admin'
        ],
        'services' => [
            'label' => 'Serviços',
            'link' => URL . '/admin/services'
        ],
        'users' => [
            'label' => 'Usuários',
            'link' => URL . '/admin/users'
        ],
    ];

    /**
     * Método resposável por retornar o conteúdo (view) da estrutura genérica depágina do painel
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title, $content)
    {
        return View::render('admin/page', [
            'title' => $title,
            'content' => $content,
        ]);
    }

    /**
     * Método resposável por retornar o conteúdo (view) dom menu do painel
     * @param string $currentModule
     * @return string
     */
    private static function getMenu($currentModeule)
    {
        //LINKS DO MENU
        $links = '';

        //ITERA OS MÓDULOS
        foreach (self::$modules as $hash => $module) {
            $links .= View::render('admin/menu/link', [
                'label' => $module['label'],
                'link' => $module['link'],
                'current' => $hash == $currentModeule ? 'text-success' : '',
            ]);
        }

        // RETORNA A RENDERIZAÇÃO MENU
        return View::render('admin/menu/box', [
            'links' => $links,
        ]);
    }

    /**
     * Método resposável por retornar o conteúdo (view) do painel
     * @param string $title
     * @param string $content
     * @param string $currentModeule
     * @return string
     */
    public static function getPanel($title, $content, $currentModule)
    {
        // RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('admin/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content,
        ]);

        // RETORNA A PÁGINA RENDERIZADA
        return self::getPage($title, $contentPanel);
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
            $links .= View::render('admin/pagination/link', [
                "page" => $page['page'],
                "link" => $link,
                "active" => $page['current'] ? 'active' : '',
            ]);
        }

        // RENDERIZA BOX PAGINAÇÂO
        return View::render('admin/pagination/box', [
            "links" => $links,
        ]);
    }

}
;