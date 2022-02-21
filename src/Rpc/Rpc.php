<?php

namespace Rpc;

use Rpc\Substrate\Method;

class Rpc
{

    /**
     * @var IClient $client
     */
    public IClient $client;

    /**
     * @var array $methods
     */
    public array $methods;


    /**
     * Rpc client, allow websocket(wss/ws) or http(https/http)
     *
     * @param string $endpoint
     * @param array $header
     */
    public function __construct (string $endpoint, array $header = [])
    {
        $parse = parse_url($endpoint);

        if ($parse["scheme"] == "ws" || $parse["scheme"] == "wss") {
            $this->client = new WSClient($endpoint, $header);
        } elseif ($parse["scheme"] == "http" || $parse["scheme"] == "https") {
            $this->client = new HttpClient($endpoint, $header);
        }
        if (!isset($this->client)) {
            throw new \InvalidArgumentException("please provider http/ws endpoint");
        }
        $m = new Method($this->client, "rpc");
        $methods = $m->methods();
        $this->methods = $methods["result"]["methods"];
    }


    /**
     * @param string $pallet
     * @return Method
     */
    public function __get (string $pallet): Method
    {
        return new Method($this->client, $pallet);
    }
}
