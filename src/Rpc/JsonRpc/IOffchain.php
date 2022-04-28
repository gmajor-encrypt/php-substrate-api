<?php

namespace Rpc\JsonRpc;
interface IOffchain
{
    /**
     * Get offchain local storage under given key and prefix
     *
     * @param string $kind
     * @param string $key
     * @return string|null
     */
    function localStorageGet (string $kind, string $key): string|null;

    /**
     * Set offchain local storage under given key and prefix
     *
     * @param string $kind
     * @param string $key
     * @param string $value
     * @return void
     */
    function localStorageSet (string $kind, string $key, string $value): void;
}