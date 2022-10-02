<?php

namespace App\Controller\Admin;
use App\Utils\View;


class Page
{

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
}
