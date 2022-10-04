<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DatabaseManager\PDOStatement;

class User
{

    /**
     * ID do usuário
     * @var string
     */
    public $id;

    /**
     * Nome do usuário 
     * @var string
     */
    public $nome;

    /**
     * E-mail do usuário
     * @var string
     */
    public $email;

    /**
     * Telefone do usuário
     * @var string
     */
    public $tel;

    /**
     * Senha do usuário
     * @var string
     */
    public $password;

    /**
     * Foto do usuário
     * @var string
     */
    public $foto;

    /**
     * Usuário criado em
     * @var \datetime
     */
    public $criado_em;

    /**
     * Método responsável por retornar um usuário com base no seu ID
     * @param integer $id
     * @return User
     */
    public static function getUserById($id)
    {
        return self::getUsers('id = ' . $id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário com base no seu e-mail
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email)
    {
        return self::getUsers('email = "' . $email . '"')->fetchObject(self::class);
    }

    /**
     * Método resposável por retornar  Usuários
     * @param string $where 
     * @param string $oder
     * @param string $limit
     * @param string $fields 
     * @return \PDOStatement  
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsavel por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar()
    {
        // DEFINE A DATA
        // DEFINE O FUSO HORÁRIO
        date_default_timezone_set('America/Sao_Paulo');
        $this->criado_em = date('Y-m-d H:i:s');

        // INSERE UM USUÁRIO NO BANCO DE DADOS
        $this->id = (new Database('usuarios'))->insert([
            'nome' => $this->nome,
            'email' => $this->email,
            'tel' => $this->tel,
            'password' => $this->password,
            'criado_em' => $this->criado_em,
        ]);

        // INSERÇÂO COM SUCESSO E RETORNO DE ID
        return true;
    }

    /**
     * Método responsavel por atualizar os dados do banco com a instancia atual
     * @return boolean
     */
    public function atualizar()
    {
        // ATUALIZA O SERVICO NO BANCO DE DADOS
        return (new Database('usuarios'))->update('id =' . $this->id, [
            'nome' => $this->nome,
            'email' => $this->email,
            'tel' => $this->tel,
            'password' => $this->password,
        ]);

    }





    /**
     * Método responsavel por excluir os dados do banco com a instancia atual
     * @return boolean
     */
    public function excluir()
    {
        // EXCLUI O SERVICO DO BANCO DE DADOS
        return (new Database('usuarios'))->delete('id =' . $this->id);

    }


}