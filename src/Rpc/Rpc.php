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
     * @param string $endpoint
     */
    public function __construct (string $endpoint)
    {
        $parse = parse_url($endpoint);

        if ($parse["scheme"] == "ws" || $parse["scheme"] == "wss") {
            $this->client = new WSClient($endpoint);
        } elseif ($parse["scheme"] == "http" || $parse["scheme"] == "https") {
            $this->client = new HttpClient($endpoint);
        }
        if (!isset($this->client)) {
            throw new \InvalidArgumentException("please provider http/ws endpoint");
        }
        $m = new Method($this->client, "rpc");
        $this->methods = $m->methods()["result"]["methods"];
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
