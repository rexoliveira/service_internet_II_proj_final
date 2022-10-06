<?php
//API: Classe de manutenção da aplicação
namespace App\Http\Middleware;
use App\Http\Request;
use App\Http\Response;


class Maintenance
{

    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        //VERIFICA O ESTADO DE MANUTENÇÃO DA PÁGINA
        if (getenv('MAINTENANCE') == 'true') {
            throw new \Exception("Página em manutenção. Tente novamente mais tarde.", 200);
        }

        // EXECUTA O PRÓXIMO NÍVEL DO MEDDLEWARE
        return $next($request);

    }

}
