<?php

namespace Config;

use Log\Logger as Log;

class Mysql
{
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
    const passwd = "123456";
    /**
     * @var array
     */
    const options = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ];
}

class Logger
{
    const level = Log::INFO;

    // 如果使用 tiny提供的Log4php logger，需要配置以下项
    //Log4php Logger类文件位置，要确定require_once能成功
    // 如果不使用log4php 可以不配置此项
    const log4php_class_file = 'E:\ChromeDownload\apache-log4php-2.3.0-src\apache-log4php-2.3.0\src\main\php/Logger.php';
}