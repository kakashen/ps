<?php

namespace Api;


use Core\BaseAPI;
use Core\BaseRequestData;
use Core\BaseResponseData;
use Core\Request;
use Core\Response;
use Model\CommodityClass;


class TestApiRequest extends BaseRequestData
{
    public $id;
}

class TestApiResponse extends BaseResponseData
{
    public $data;
}

class TestApi extends BaseAPI
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

        $comm_class = new CommodityClass($rqData->id);
        $data = $comm_class->findOne();
        $response->data->data = $data;
    }
}