<?php

namespace App\Http\Middleware;
use App\Http\Request;
use App\Http\Response;
use App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout{

    /**
     * Método reposável por exectar o middleware
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        //VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if(SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin');
        }

        // CONTINUA A EXECUÇÂO
        return $next($request);
    }


}