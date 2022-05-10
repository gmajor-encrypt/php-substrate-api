<?php

namespace Rpc\Substrate;

use InvalidArgumentException;
use Rpc\IClient;

// https://polkadot.js.org/docs/substrate/rpc
class Method
{

    /**
     * rpc client inject
     *
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
     *
     * @var array
     */
    public array $support;

    /**
     *
     * @param IClient $client
     * @param string $pallet
     * @param array $support
     */
    public function __construct (IClient $client, string $pallet = "", array $support = array())
    {
        $this->client = $client;
        $this->pallet = $pallet;
        $this->support = $support;
    }

    public function methods ()
    {
        return $this->client->read("rpc_methods");
    }


    /**
     * as like state.getMetadata
     * state_getMetadata
     * Returns the runtime metadata
     *
     * @return string
     */
    public function getMetadata (): string
    {
        $res = $this->client->read("state_getMetadata");
        return $res["result"];
    }

    /**
     * magic call, if some pallet not defined, it will be auto build call like {$pallet}_{$call}
     *
     * @param string $call
     * @param array $attributes
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function __call (string $call, array $attributes)
    {
        $method = sprintf("%s_%s", $this->pallet, $call);
        return $this->client->read($method, $attributes);
    }
}
