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
     * Método responsável por buscar o usuário com base em seu e-mail
     * @param string $email
     * @return User
     */

    public static function getUserByEmail($email)
    {
        return (new Database('usuarios'))->select('email = "' . $email . '"')->fetchObject(self::class);
    }

}