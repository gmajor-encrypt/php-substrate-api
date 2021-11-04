<?php

namespace Rpc;

use Rpc\Substrate\Method;

class Rpc
{

    /**
     * @var IClient
     */
    public IClient $client;

    public function __construct()
    {

        $conf = config::$config;
        if (!empty($conf["ws_endpoint"])) {
            $this->client = new WSClient();
            return;
        } elseif (!empty($conf["http_endpoint"])) {
            $this->client = new HttpClient();
            return;
        }
        throw new \InvalidArgumentException("please provider http/ws endpoint");
    }


    /**
     * @param string $method
     * @return Method
     */
    public function __get(string $method): Method
    {

        return new Method($this->client, $method);
    }
}
