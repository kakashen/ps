<?php

namespace Core;

class Response
{
    public function __construct()
    {
        $this->httpStatus = HttpStatus::SUC;
    }

    public $data;
    public $httpStatus;
    public $httpStatusMsg;
    public $httpHeaders = [];
}