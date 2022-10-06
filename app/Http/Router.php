<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;
use App\Http\Middleware\Quere as MiddlewareQuere;


class Router
{

    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice das rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    /**
     * Content type padrão do response
     * @var string
     */
    private $contentType = 'text/html';


    /**
     * Método responsável por inciar a classe
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por alterar o valor do content type
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

    }

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix()
    {

        // INFORMAÇÔES DA URL ATUAL
        $parseUrl = parse_url($this->url);

        // DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';

    }

    /**
     * Método responsável por adicionar uma rota  na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = [])
    {
        //VALIDAÇÂO DOS PARÂMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        // Se não existir um middleware e retonar uma lista vazia
        // MIDDLEWARE DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        // VARIÁVEIS DA ROTA
        $params['variables'] = [];

        // PADRÃO DE VALIDAÇÂO DA VARIÀVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {

            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // REMOVE BARRA NO FINAL DA ROTA
        $route = rtrim($route,'/');

        // PADRÃO DE VALIDAÇÃO DA URL
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        // ADICIONA A ROTA DENTRO DA CLASSE
        return $this->routes[$patternRoute][$method] = $params;

    }
    // =================================================================

    /**
     * Método responsável por definir uma rota de GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }

    // =================================================================

    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     * @return string
     */
    public function getUri()
    {
        // URI DA REQUEST
        $uri = $this->request->getUri();

        // FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
     
        // RETORNA A URI SEM PREFIXO- se caso tem a barra no final, é retirada
        return rtrim(end($xUri),'/');

    }
    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute()
    {
        // URI - RETORNA SOMENTE A '\'
        $uri = $this->getUri();

        //METHOD
        $httpMethod = $this->request->getHttpMethod();

        //VALIIDA AS ROTAS
        foreach ($this->routes as $patternRoute => $methods) {
            // VERIFICA SE A URI BATE COM O PADRÃO
            if (preg_match($patternRoute, $uri, $matches)) {
                // VERIFICA O MÉTODO
                if (isset($methods[$httpMethod])) {
                    // REMOVE A PRIMEIRA POSIÇÂO
                    unset($matches[0]);

                    // VARIÁVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;



                    // RETORNO DOS PARÂMETROS DA ROTA
                    return $methods[$httpMethod];
                }
                // MÉTODO NÃO PERMITIDO/DEFINIDO
                throw new Exception("Método não permitido", 405);
            }
        }
        // URL NÃO ENCONTRADA
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Método responsável por executar a rota atual
     * @return Response
     */
    public function run()
    {
        try {

            // OBTEM A ROTA ATUAL
            $route = $this->getRoute();

            // VERIFICA O CONTROLADOR 
            if (!isset($route['controller'])) {
                throw new Exception("URL não pode ser processada", 500);
            }

            // ARGUMENTOS DA FUNÇÃO
            $args = [];

            //REFLECTION
            $reflection = new ReflectionFunction($route['controller']);

            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARE
            return (new MiddlewareQuere($route['middlewares'], $route['controller'], $args))->next($this->request);

        }
        catch (Exception $e) {
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }

    /**
     * Método responsável por retornar a messagem de erro conforme o content type
     * @param string  $message
     * @return mixed
     */
    private function getErrorMessage($message)
    {
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $message
                ];
            default:
                return $message;
        }
    }

    /**
     * Método reponsável por retornar a URL atual
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->url . $this->getUri();
    }

    /**
     * Método responsável por redirecionar A URL
     * @param string $route
     */
    public function redirect($route)
    {
        // URL
        $url = $this->url . $route;

        // EXECURA O REDEIRECT
        header('location: ' . $url);
        exit;
    }
}