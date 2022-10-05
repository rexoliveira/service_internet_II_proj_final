<?php

namespace App\Http;

use App\Http\Router;
use App\Model\Entity\User;


class Request
{


    /**
     * Instancia do Router
     * @var string
     */
    private $router;

    /**
     * Método HTTP da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página
     * @var string
     */
    private $uri;

    /**
     * Parâmetros da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis recebidas no POST da página ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    /**
     * Uma instancia de usuário
     * @var User
     */
    private $user;


    /**
     * Construtor da classe
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->user = new User();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? [];
        $this->setUri();
        $this->setPostVars();
    }


    /**
     * Método responsável por defir as variaveis do POST
     */
    private function setPostVars()
    {
        // VERIFICA O MÉTODO DA REQUISIÇÃO
        if ($this->httpMethod == 'GET')
            return false;

        // POST PADRÃO
        $this->postVars = $_POST ?? [];

        // POST JSON
        $inputRaw = \file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) and empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    /**
     * Método responsável por defiir a URI
     */
    private function setUri()
    {
        // URI COMPLETA (COM GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? [];

        // REMOVE GETS DA URI
        $xUri = explode('?', $this->uri);
        $this->uri = $xUri[0];

    }

    /**
     * Método responsável por retornar a instancia de Router
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;

    }

    /**
     * Método responsável por retornar o método HTTP da requisição
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;

    }
    /**
     * Método responsável por retornar o método URI da requisição
     * @return string
     */
    public function getUri()
    {
        return $this->uri;

    }
    /**
     * Método responsável por retornar os HEADERS da requisição
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;

    }
    /**
     * Método responsável por retornar os parâmetros da URL da requisição
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;

    }
    /**
     * Método responsável por retornar as variáveis POST da requisição
     * @return array
     */
    public function getPostVars()
    {
        return $this->postVars;

    }


    /**
     * Set uma instancia de usuário
     *
     * @param  User  $user  Uma instancia de usuário
     *
     * @return  self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get uma instancia de usuário
     *
     * @return  User
     */ 
    public function getUser()
    {
        return $this->user;
    }
}