<?php

namespace Api;
use API;
use Request;
use Response;

require_once 'Common.php';

class TestApi extends API
{
    protected function run(Request $request, Response $response)
    {
        // TODO: Implement run() method.
        $response->data = '123';
    }
}