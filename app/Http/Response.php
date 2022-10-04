<?php

namespace App\Http;


class Response
{


    /**
     * Código do Status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do Response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo que está sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo do Response
     * @var mixed
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     * @param  integer $httpCode
     * @param  mixed $content
     * @param  string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o content type do response
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHearer('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho de response
     * @param string $key
     * @param string $value
     */
    public function addHearer($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por eviar os headers para o naveagdor
     */
    private function sendHeaders()
    {
        //DEFINIR O STATUS
        http_response_code($this->httpCode);

        //ENVIAR HEADERS
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    /**
     * Método responsável por enivar a resposta para usuário
     * 
     */
    public function sendResponse()
    {

        // ENVIA OS HEADERS
        $this->sendHeaders();

        // IMPRIME O CONTEÚDO
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo \json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
    }


}