<?php

namespace Config;

class Mysql {
    /**
     * @var string
     */
    const dsn = "mysql:host=127.0.0.1;dbname=local_huanxi";
    /**
     * @var string
     */
    const username = "root";
    /**
     * @var string
     */
    const passwd   = "123456";
    /**
     * @var array
     */
    const options  = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ];
}