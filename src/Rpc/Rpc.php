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


    public function __construct()
    {
        $conf = config::$config;
        if (!empty($conf["ws_endpoint"])) {
            $this->client = new WSClient();
        } elseif (!empty($conf["http_endpoint"])) {
            $this->client = new HttpClient();
        }
        if(!isset($this->client)){
            throw new \InvalidArgumentException("please provider http/ws endpoint");
        }
        $m = new Method($this->client,"rpc");
        $this->methods = $m->methods()["result"]["methods"];
    }


    /**
     * @param string $pallet
     * @return Method
     */
    public function __get(string $pallet): Method
    {
        return new Method($this->client, $pallet);
    }
}
