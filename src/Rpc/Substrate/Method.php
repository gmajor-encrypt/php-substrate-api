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
     * getBlockHash
     *
     * @return string blockNum hex
     */
    public function getBlockHash (string $blockNum): string
    {
        $res = $this->client->read("chain_getBlockHash", [$blockNum]);
        return $res["result"];
    }

    /**
     * accountNextIndex
     *
     * @param string $accountId
     * @return int
     */
    public function accountNextIndex (string $accountId): int
    {
        $res = $this->client->read("system_accountNextIndex", [$accountId]);
        return $res["result"];
    }

    /**
     * getRuntimeVersion
     *
     * @return array
     */
    public function getRuntimeVersion (): array
    {
        $res = $this->client->read("state_getRuntimeVersion", []);
        return $res["result"];
    }


    /**
     * @param string $call
     * @param array $attributes
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function __call (string $call, array $attributes)
    {
        $method = sprintf("%s_%s", $this->pallet, $call);
        $res = $this->client->read($method, $attributes);
        if (array_key_exists("error", $res)) {
            throw new InvalidArgumentException(sprintf("Read rpc get error %s", $res["error"]["message"]));
        }
        return $res;
    }
}
