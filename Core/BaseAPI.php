<?php

namespace Core;


class ErrorCode
{
    const SUC = 200;
    const NOT_MODIFIED = 304;
    const AUTH_ERROR = 401;
    const PERMISSION_DENIED = 403;
    const NOT_ACCEPTABLE = 406;
    const EXPIRE = 408;
    const ERROR = 500;
    const IS_ALREADY = 504;
    const NOT_FOUND = 404;
    const REDIRECT = 302;
}

class BaseResponseErrorData extends BaseResponseData
{
    public $errMsg;

    public function __construct(int $code = ErrorCode::ERROR, string $errMsg = "运行错误")
    {
        parent::__construct();
        $this->code = $code;
        $this->errMsg = $errMsg;
    }
}

abstract class BaseAPI extends JsonAPI
{
    protected function beforeRun(Request $request, Response $response): bool
    {
        if (!parent::beforeRun($request, $response)) return false;

        /**
         * @var BaseRequestData $rqData
         */
        $rqData = $request->data;

        if (empty($rqData->token) || !is_string($rqData->token)) {
            $response->data = json_encode(new BaseResponseErrorData(ErrorCode::AUTH_ERROR, "token error or token expire."));
            return false;
        }

        /*// 验证token
        $info = new MyTokenInfo();
        $token = new MyToken($rqData->token);
        if (!$token->getInfo($info)) {
            Logger::getInstance()->warn('token ' . $rqData->token . "token error or token expire.");
            $response->data = json_encode(new BaseResponseErrorData(ErrorCode::AUTH_ERROR, "token error or token expire."));
            return false;
        }*/


        /*// 验证权限
        if (!($this->authority() & $info->authority)) {
            Logger::getInstance()->warn('token ' . $rqData->token . " permission denied");
            $response->data = json_encode(new BaseResponseErrorData(ErrorCode::PERMISSION_DENIED, "permission denied"));
            return false;
        }*/

        /*$request->uid = $info->uid;
        $request->channel = $info->channel;
        if (isset($request->httpHeaders["HTTP_PUSHURL"])) {
            //TODO 存储Session
        }*/
        return true;
    }

    protected function authority(): int
    {
        return 1;
        //return MyTokenAuthority::GUEST;
    }
}
