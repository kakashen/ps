<?php

namespace Core;

class BaseResponseData
{
    public $code;

    public function __construct()
    {
        $this->code = ErrorCode::SUC;
    }
}