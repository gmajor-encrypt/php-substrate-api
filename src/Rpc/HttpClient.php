<?php

namespace Rpc;

use Rpc\Substrate\json2;

class HttpClient extends Client
{

    /**
     * Http constructor.
     *
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * subscribe
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    function subscribe (string $method, array $params = []): mixed
    {
        return Util::requestWithPayload(self::$HTTP_ENDPOINT, json2::build($method, $params));
    }
}