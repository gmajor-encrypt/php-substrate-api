<?php

namespace Rpc;

use Rpc\Substrate\Json2;

class HttpClient extends Client
{

    /**
     * http client header
     *
     * @var array
     */
    public array $header;

    /**
     * isConnected http client always sConnected
     * @var bool
     */
    public bool $isConnected;

    /**
     * Http constructor. allow http or https endpoint
     *
     * @param string $endpoint
     * @param array $header
     */
    public function __construct (string $endpoint, array $header = [])
    {
        $this->header = $header;
        $this->isConnected = true;
        parent::__construct($endpoint);
    }

    /**
     * subscribe http request
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public function read (string $method, array $params = []): array
    {
        return Util::requestWithPayload(self::$HTTP_ENDPOINT, Json2::build($method, $params), $this->header);
    }
}