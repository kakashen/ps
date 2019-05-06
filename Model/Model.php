<?php

use Model\Medoo;

require_once 'Medoo.php';

class Model extends Medoo
{
    private const database_type = 'mysql';
    private const database_name = 'local_huanxi';
    private const server = 'localhost';
    private const username = 'root';
    private const password = '123456';
    private const charset = 'utf8';
    private const collation = 'utf8_general_ci';
    private const port = 3306;

    private const option = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        // PDO::ATTR_STRINGIFY_FETCHES => false,
        // PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public function __construct()
    {
        parent::__construct([
            'database_type' => self::database_type,
            'database_name' => self::database_name,
            'server' => self::server,
            'username' => self::username,
            'password' => self::password,
            'charset' => self::charset,
            'collation' => self::collation,
            'port' => self::port,
            'option' => self::option
        ]);
    }


    function __destruct()
    {

    }



}