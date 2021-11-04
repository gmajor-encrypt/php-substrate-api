<?php

namespace Rpc;

use Rpc\Substrate\Json2;

class HttpClient extends Client
{

    /**
     * Http constructor.
     *
     */
    public function __construct()
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
    public function read(string $method, array $params = []): mixed
    {
        return Util::requestWithPayload(self::$HTTP_ENDPOINT, Json2::build($method, $params));
    }
}