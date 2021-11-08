<?php

namespace Rpc\Substrate;

use Rpc\IClient;

class Method
{

    /**
     * @var IClient
     */
    public IClient $client;

    /**
     * pallet name;
     *
     * @var string
     */
    public string $pallet;


    /**
     * support rpc methods
     * @var array
     */
    public array $support;

    /**
     *
     * @param IClient $client
     * @param string $pallet
     * @param array $support
     */
    public function __construct(IClient $client, string $pallet,array $support = array())
    {
        $this->client = $client;
        $this->pallet = $pallet;
        $this->support = $support;
    }

    public function methods(){
        return $this->client->read("rpc_methods");
    }

    /**
     * @param string $call
     * @param array $attributes
     *
     * @return void
     */
    public function __call(string $call, array $attributes)
    {
        $method = strtolower(sprintf("%s_%s", $this->pallet, $call));
        if(!in_array($method,$this->support)){
            throw new \InvalidArgumentException(sprintf("RPC %s not support",$method));
        }
        var_dump($this->client->read($method), $attributes);
    }
}
