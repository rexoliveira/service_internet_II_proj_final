<?php

namespace App\Model\Entity;
use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DatabaseManager\PDOStatement;

class Item
{

    /**
     * ID do item de serviço
     * @var string
     */
    public $id;

    /**
     * Nome do usuário 
     * @var string
     */
    public $nome_usuario;

    /**
     * Item de serviço
     * @var string
     */
    public $item_servico;

    /**
     * Descrição do item de serviço
     * @var string
     */
    public $descricao_servico;

    /**
     * Data de inserção do item de serviço
     * @var string
     */
    public $data_insercao;

    /**
     * Método responsavel por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar()
    {
        // DEFINE A DATA
        // DEFINE O FUSO HORÁRIO
        date_default_timezone_set('America/Sao_Paulo');
        $this->data_insercao = date('Y-m-d H:i:s');

        // INSERE ITENS DE SERVICO NO BANCO DE DADOS
        $this->id = (new Database('servicos'))->insert([
            'nome_usuario' => $this->nome_usuario,
            'item_servico' => $this->item_servico,
            'descricao_servico' => $this->descricao_servico,
            'data_insercao' => $this->data_insercao,
        ]);

        // INSERÇÂO COM SUCESSO E RETORNO DE ID
        return true;
    }

    /**
     * Método resposável por retornar Itens de Serviço
     * @param string $where 
     * @param string $oder
     * @param string $limit
     * @param string $fields 
     * @return PDOStatement  
     */
    public static function getItems($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('servicos'))->select($where, $order, $limit, $fields);
    }


}
