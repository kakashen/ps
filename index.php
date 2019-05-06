<?php

use Log\Log4php;
use Log\Logger;

require_once 'Common.php';
require_once 'Log\Logger.inc';
require_once 'Log\Log4php.inc';

class Index
{
    public static function main(): void
    {
        /*
         * 设置时区
         */
        self::setTimezone();
        /*
         * 设置错误日志
         */
        self::setErrorLog();


        if (!Logger::hasSetConcreteLogger()) {
            require_once('E:\ChromeDownload\apache-log4php-2.3.0-src\apache-log4php-2.3.0\src\main\php/Logger.php');
            Logger::setConcreteLogger(new Log4php());
        }

        $response = new Response();
        unset($response->data);
        try {
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

            Logger::getInstance()->info('start');

            // 加载api类文件
            self::loadApi($request, $response);

        } catch (\Exception $e) {
            $response->httpStatus = HttpStatus::FAILED;
            $response->httpStatusMsg = $e->getMessage();
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
     * @param Request $request
     * @param Response $response
     * 加载api类文件
     */
    private static function loadApi(Request $request, Response $response): void
    {
        $api = $request->api;

        require_once 'Api/' . $api . '.php';

        if (!class_exists('Api\\' . $api)) {
            $response->httpStatus = HttpStatus::NOT_FOUND;
            $response->httpStatusMsg = "API Not Found";
        } else {
            $api = 'Api\\' . $api;
            $api = new $api;
            $api->process($request, $response);
        }
    }
}

Index::main();



