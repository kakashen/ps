<?php

require 'Model\Medoo.php';

class RequestData
{
    public $token;
}

class ResponseData
{
    public $code;

    public function __construct()
    {
        $this->code = 200;
    }
}

abstract class API extends ParentApi
{
    public function process(Request $request, Response $response)
    {
        if ($this->beforeRun($request, $response)) {
            $this->run($request, $response);
            $this->afterRun($response);
        }
    }

    protected function beforeRun(Request $request, Response $response): bool
    {
        if (!parent::beforeRun($request, $response)) return false;

        $rqData = $request->data;

        if (empty($rqData->token) || !is_string($rqData->token)) {
            $response->httpStatus = 415;
            $response->httpStatusMsg = "token error or token expire.";
            return false;
        }
        // TODO 验证token
        return true;
    }

    abstract protected function run(Request $request, Response $response);

    protected function afterRun(Response $response)
    {
        if (!isset($response->data) || $response->data === null) {
            return;
        }

        $response->data = json_encode($response->data);

        if ($response->data === false) {
            unset($response->data);
            $response->httpStatus = HttpStatus::FAILED;
            $response->httpStatusMsg = 'PHP json_encode(response->data) error';
        }
    }
}

class ParentApi
{
    protected function beforeRun(Request $request, Response $response): bool
    {
        if (empty($request->data)) {
            $request->data = new \stdClass();
        } else {
            $request->data = json_decode($request->data);
        }

        if ($request->data === false) {
            $response->httpStatus = HttpStatus::ARGS_FORMAT_ERROR;
            $response->httpStatusMsg = 'PHP json_decode(request->data) error';
            return false;
        }
        return true;
    }
}

