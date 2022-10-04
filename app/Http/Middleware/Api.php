<?php
//API: Classe de manutenção da aplicação
namespace App\Http\Middleware;
use App\Http\Request;
use App\Http\Response;


class Api
{

    /**
     * Método reposável por exectar o middleware
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        // ALTERA O CONTENT TYPE PARA JSON
        $request->getRouter()->setContentType('application/json');

        // EXECUTA O PRÓXIMO NÍVEL DO MEDDLEWARE
        return $next($request);

    }

}
