<?php

namespace Rpc\JsonRpc;

class Offchain extends base implements IOffchain
{

    /**
     * Get offchain local storage under given key and prefix
     *
     * @param string $kind
     * @param string $key
     * @return string|null
     */
    function localStorageGet (string $kind, string $key): string|null
    {
        $res = $this->client->read("offchain_localStorageGet", [$kind, $key]);
        return $res["result"];
    }

    /**
     * Set offchain local storage under given key and prefix
     *
     * @param string $kind
     * @param string $key
     * @param string $value
     * @return void
     */
    function localStorageSet (string $kind, string $key, string $value): void
    {
        $this->client->read("offchain_localStorageSet", [$kind, $key, $value]);
    }
}