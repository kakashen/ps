<?php

namespace Api;

use API;
use Request;
use RequestData;
use Response;
use ResponseData;

require_once 'Common.php';

class TestApiRequest extends RequestData
{
    public $id;
}

class TestApiResponse extends ResponseData
{
    public $data;
}

class TestApi extends API
{
    protected function run(Request $request, Response $response)
    {
        $response->data = new TestApiResponse();

        /**
         * @var $rqData TestApiRequest
         */
        $rqData = $request->data;
        if (!isset($rqData->id) || !is_int($rqData->id)) {
            $response->data->code = 10001;
            $response->data->data = 'id not set or int';
            return;

        }

        $response->data->data = 123;
    }
}