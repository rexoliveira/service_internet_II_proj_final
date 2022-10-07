<?php

//API: Gerencia a fila de middleware

namespace App\Http\Middleware;
use App\Http\Request;
use App\Http\Response;

class Queue
{
    /**
     * Mapeamento de middleware
     * @var array
     */
    private static $map = [];

    /**
     * Mapeamento de middlewares serão carregados em todas as rotas
     * @var array
     */
    private static $default =[];

    /**
     * Filade middleware a serem executados
     * @var array
     */
    private $middlewares = [];

    /**
     * Função de execução do controlador
     * @var \Closure
     */
    private $controller;

    /**
     * Argumentos da função do controlador
     */
    private $controllerArgs = [];

    /**
     * Método responsável por construir a classe de filas de midddlewares
     * @param array $midllewares
     * @param \Closure $controller
     * @param array $controllerArgs
     */
    public function __construct($middlewares, $controller, $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default,$middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Método resposável por definir o mapeamento de middleware
     * @param array $map
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }

    /**
     * Método resposável por definir o mapeamento de middleware padrões
     * @param array $default
     */
    public static function setDefault($default)
    {
        self::$default = $default;
    }


    /**
     * Método resposável por executar o próximo nível da fila de middleware
     * @param  Request $request
     * @return Response
     */
    public function next($request)
    {
        // VERIFICA SE A FILA ESTÁ VAZI
        if (empty($this->middlewares))
            return \call_user_func_array($this->controller, $this->controllerArgs);

        //MIDDLEWARE
        $middleware = array_shift($this->middlewares);

        // VERIFICA O MAPEAMENTO
        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas ao processar o middleware da requisição", 500);            
        }
        // NEXT
        $quere =$this;
        $next = function($request) use($quere){
            return $quere->next($request);
        };
        
        // EXECUTA O MIDDLEWARE
        return (new self::$map[$middleware])->handle($request,$next);

    }

}
