<?php

use Core\HttpStatus;
use Core\Request;
use Core\Response;
use Log\Log4php;
use Log\Logger;

define('APP', __DIR__ . '\\');

class Index
{
    public static function main(): void
    {
        // 自动加载类文件
        self::autoload();
        // 加载配置文件
        self::loadConfig();
        $response = new Response();
        unset($response->data);
        try {
            // 引入日志文件
            if (!Logger::hasSetConcreteLogger()) {
                require_once(Config\Logger::log4php_class_file);
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
            $response->httpStatusMsg = 'PHP Run Error';

            Logger::getInstance()->fatal("500 PHP Run Error", $e);
        }
        // 日志 end
        Logger::getInstance()->info("end");


        if ($response->httpStatus !== HttpStatus::SUC) {
            Logger::getInstance()->fatal($response->httpStatusMsg);
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
     * @param string $name
     * 自动加载
     */
    private static function load(string $name): void
    {
        // $fileName = TY_LOG . basename($name) . '.inc';
        $fileName = APP . $name . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }


    /**
     * 加载 config
     */
    private static function loadConfig(): void
    {
        $fileName = APP . 'Config\\Config' . '.php';
        if (file_exists($fileName)) {
            require_once $fileName;
        }
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
        // 自动加载
        spl_autoload_register('self::load');
    }
}

Index::main();



