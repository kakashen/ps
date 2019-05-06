<?php

use Core\HttpStatus;
use Core\Request;
use Core\Response;
use Log\Log4php;
use Log\Logger;

//require_once 'Common.php';
//require_once 'Log\Logger.inc';
//require_once 'Log\Log4php.inc';

class Index
{
    public static function main(): void
    {
        // 加载api model类文件
        self::autoload();

        $response = new Response();
        unset($response->data);
        try {
            // 引入日志文件
            if (!Logger::hasSetConcreteLogger()) {
                require_once('E:\ChromeDownload\apache-log4php-2.3.0-src\apache-log4php-2.3.0\src\main\php/Logger.php');
                Logger::setConcreteLogger(new Log4php());
            }

            // ----- request -----
            $request = new Request();
            Logger::getInstance()->setRequest($request);

            $request->api = explode('?', $_SERVER["REQUEST_URI"]);
            $api = explode('/', $request->api[0]);
            if (count($api) > 0 && $api[0] === '') {
                $api = array_pop($api);
            }
            $request->api = $api;
            $request->data = file_get_contents('php://input');
            $request->httpHeaders = $_SERVER;

            // 日志 start
            Logger::getInstance()->info('start');

            $class = 'Api\\' . $api;
            $api = new $class;
            $api->process($request, $response);
        } catch (Exception $e) {
            $response->httpStatus = HttpStatus::FAILED;
            $response->httpStatusMsg = $e->getMessage();

            // 日志 end
            Logger::getInstance()->fatal("500 PHP Run Error", $e);

        }
        Logger::getInstance()->info("end");


        if ($response->httpStatus !== HttpStatus::SUC) {
            header("HTTP/1.1 " . $response->httpStatus . " " . $response->httpStatusMsg);
            return;
        }

        foreach ($response->httpHeaders as $header => $value) {
            header($header . ': ' . $value);
        }

        if (isset($response->data)) {
            file_put_contents('php://output', $response->data);
        }

    }

    /**
     * 设置时区
     */
    private static function setTimezone(): void
    {
        date_default_timezone_set('PRC'); // 设置时区
    }

    /**
     * 设置日志
     */
    private static function setErrorLog(): void
    {
        ini_set('display_errors', 'Off');
        error_reporting(E_ALL);
        ini_set('log_errors', 'On');
        $errorLog = ini_get('error_log');
        if ($errorLog === '' || $errorLog === false) {
            $log = 'Log\\ty-' . date('Y-m-d') . '.log';
            ini_set('error_log', $log);
        }
    }

    /**
     * 加载api类文件
     */
    private static function loadApi(string $name): void
    {
        $fileName = TY_API . $name . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }

    /**
     * 加载 model 类文件
     */
    private static function loadModel(string $name): void
    {
        $fileName = TY_MODEL . $name . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }

    /**
     * 设置目录常量
     */
    private static function setDIR(): void
    {
        define('TY_API', __DIR__ . '\\Api\\');
        define('TY_MODEL', __DIR__ . '\\');
        define('TY_CONF', __DIR__ . '\\Config\\');
        define('TY_LOG', __DIR__ . '\\');
        define('TY_CORE', __DIR__ . '\\');
    }

    /**
     * @param string $name
     * 自动加载日志
     */
    private static function loadLog(string $name): void
    {
        $fileName = TY_LOG . $name . '.inc';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }

    /**
     * @param string $name
     * 自动加载核心类文件
     */
    private static function loadCore(string $name): void
    {
        $fileName = TY_CORE . $name . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }

    /**
     * 自动加载 config
     */
    private static function loadConfig()
    {
        $fileName = TY_CONF . 'Config' . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
        echo $fileName;
    }

    /**
     * 自动加载 api model 类文件
     */
    private static function autoload(): void
    {
        // 设置时区
        self::setTimezone();
        // 设置错误日志
        self::setErrorLog();
        // 设置目录常量
        self::setDIR();
        // 自动加载日志
        spl_autoload_register('self::loadLog');
        // 自动加载核心类文件
        spl_autoload_register('self::loadCore');
        // 自动加载model
        spl_autoload_register('self::loadModel');
        // 自动加载api
        spl_autoload_register('self::loadApi');
        // 自动加载 config
        spl_autoload_register('self::loadConfig');

    }
}

Index::main();



