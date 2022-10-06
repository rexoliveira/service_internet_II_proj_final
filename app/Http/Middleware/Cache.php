<?php
//API: Classe de cache da aplicação
namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Utils\Cache\File as CacheFile;


class Cache
{

    /**
     * Método responsável por executar se a request atual é cacheável
     * @param Request $request
     * @return boolean
     */
    public function isCacheable($request)
    {
        // VALIDA O TEMPO DE CAHCE
        if (getenv('CACHE_TIME') <= 0) {
            return false;
        }

        // VALIDA O MÉTODO DA REQUISIÇÃo
        if ($request->getHttpMethod() != 'GET') {
            return false;
        }

        // VALIDA O HEADER DE CACHE - CONTROLE DE CACHE - [OPCIONAL]
        $headers = $request->getHeaders();
        if (isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache') {
            return false;
        }

        // CACHEÁVEL
        return true;
    }

    /**
     * Método responsável por retornar a hash do cache
     * @param Request $request
     * @return string
     */
    private function getHash($request)
    {
        // URI DA ROTA
        $uri = $request->getRouter()->getUri();

        // QUERY PARAMS
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : '';

        // REMOVE AS BARRAS E QUALQUER CARACTER ALFNUMERICO E RETORNA A HASH
        return rtrim('route-' . preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/')),'-');

    }

    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        // VERIFICA SE A REQUEST ATUAL É CACHEÁVEL
        if (!$this->isCacheable($request))
            return $next($request);

        // HASH DO CACHE
        $hash = $this->getHash($request);
    
        // RETORNA OS DADOS DO CACHE
        return CacheFile::getCache($hash, getenv('CACHE_TIME'), function () use ($request, $next) {
            return $next($request);
        });
    }

}