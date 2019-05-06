<?php

namespace Core;


abstract class API
{
    public function process(Request $request, Response $response)
    {
        if ($this->beforeRun($request, $response)) {
            $this->run($request, $response);
            $this->afterRun($request, $response);
        }
    }

    protected function beforeRun(Request $request, Response $response): bool
    {
        return true;
    }

    abstract protected function run(Request $request, Response $response);

    protected function afterRun(Request $request, Response $response)
    {
    }
}
