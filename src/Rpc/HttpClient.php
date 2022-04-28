<?php

namespace Rpc;

use InvalidArgumentException;
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
     *
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
     * read http request
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public function read (string $method, array $params = []): array
    {
        $res = Util::requestWithPayload(self::$HTTP_ENDPOINT, Json2::build($method, $params), $this->header);
        if (array_key_exists("error", $res)) {
            throw new InvalidArgumentException(sprintf("Read rpc get error %s", $res["error"]["message"]));
        }
        return $res;
    }
}