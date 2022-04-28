<?php

namespace Rpc\JsonRpc;

class State extends Base implements IState
{


    /**
     * Perform a call to a builtin on the chain
     *
     * @param string $methods
     * @param string $data
     * @param string $at
     * @return string
     */
    public function call (string $methods, string $data, string $at = ""): string
    {
        $res = $this->client->read("state_call", [$methods, $data, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the keys with prefix of a specific child storage
     *
     * @param string $childStorageKey
     * @param string $childDefinition
     * @param int $childType
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getChildKeys (string $childStorageKey, string $childDefinition, int $childType, string $key, string $at = ""): array
    {
        $res = $this->client->read("state_getChildKeys", [$childStorageKey, $childDefinition, $childType, $key, $at]);
        return $res["result"];
    }

    /**
     * Returns proof of storage for child key entries at a specific block state
     *
     * @param string $childStorageKey
     * @param array $keys
     * @param string $at
     * @return array
     */
    public function getChildReadProof (string $childStorageKey, array $keys, string $at = ""): array
    {
        $res = $this->client->read("state_getChildReadProof", [$childStorageKey, $keys, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the child storage for a key
     *
     * @param string $childStorageKey
     * @param string $childDefinition
     * @param int $childType
     * @param string $key
     * @param string $at
     * @return string
     */
    public function getChildStorage (string $childStorageKey, string $childDefinition, int $childType, string $key, string $at = ""): string
    {
        $res = $this->client->read("state_getChildStorage", [$childStorageKey, $childDefinition, $childType, $key, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the child storage hash
     *
     * @param string $childStorageKey
     * @param string $childDefinition
     * @param int $childType
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getChildStorageHash (string $childStorageKey, string $childDefinition, int $childType, string $key, string $at = ""): string
    {
        $res = $this->client->read("state_getChildStorageHash", [$childStorageKey, $childDefinition, $childType, $key, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the child storage size
     *
     * @param string $childStorageKey
     * @param string $childDefinition
     * @param int $childType
     * @param string $key
     * @param string $at
     * @return int
     */
    public function getChildStorageSize (string $childStorageKey, string $childDefinition, int $childType, string $key, string $at = ""): int
    {
        $res = $this->client->read("state_getChildStorageSize", [$childStorageKey, $childDefinition, $childType, $key, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the keys with a certain prefix
     *
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getKeys (string $key, string $at = ""): array
    {
        $res = $this->client->read("state_getKeys", [$key, $at]);
        return $res["result"];
    }

    /**
     * RReturns the keys with prefix with pagination support.
     *
     * @param string $key
     * @param int $count
     * @param string $startKey
     * @param string $at
     * @return array
     */
    public function getKeysPaged (string $key, int $count, string $startKey, string $at = ""): array
    {
        $res = $this->client->read("state_getKeysPaged", [$key, $count, $startKey, $at]);
        return $res["result"];
    }

    /**
     * Returns the runtime metadata
     *
     * @param string $at
     * @return string
     */
    public function getMetadata (string $at = ""): string
    {
        $res = $this->client->read("state_getMetadata", [$at]);
        return $res["result"];
    }

    /**
     * Returns the keys with prefix, leave empty to get all the keys (deprecated: Use getKeysPaged)
     *
     * @param string $prefix
     * @param string $at
     * @return array
     */
    public function getPairs (string $prefix, string $at = ""): array
    {
        $res = $this->client->read("state_getPairs", [$prefix, $at]);
        return $res["result"];
    }

    /**
     * Returns proof of storage entries at a specific block state
     *
     * @param array $keys
     * @param string $at
     * @return array
     */
    public function getReadProof (array $keys, string $at = ""): array
    {
        $res = $this->client->read("state_getReadProof", [$keys, $at]);
        return $res["result"];
    }

    /**
     * Get the runtime version
     *
     * @param string $at
     * @return array
     */
    public function getRuntimeVersion (string $at = ""): array
    {
        $res = $this->client->read("state_getRuntimeVersion", [$at]);
        return $res["result"];
    }

    /**
     * Retrieves the storage for a key
     *
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getStorage (string $key, string $at = ""): array
    {
        $res = $this->client->read("state_getStorage", [$key, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the storage for a key hash
     *
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getStorageHash (string $key, string $at = ""): array
    {
        $res = $this->client->read("state_getStorageHash", [$key, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the storage for a key size
     *
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getStorageSize (string $key, string $at = ""): array
    {
        $res = $this->client->read("state_getStorageSize", [$key, $at]);
        return $res["result"];
    }

    /**
     * Query historical storage entries (by key) starting from a start block
     *
     * @param array $keys
     * @param string $fromBlock
     * @param string $toBlock
     * @return array
     */
    public function queryStorage (array $keys, string $fromBlock, string $toBlock = ""): array
    {
        $res = $this->client->read("state_queryStorage", [$keys, $fromBlock, $toBlock]);
        return $res["result"];
    }

    /**
     * Query storage entries (by key) starting at block hash given as the second parameter
     *
     * @param array $keys
     * @param string $at
     * @return array
     */
    public function queryStorageAt (array $keys, string $at = ""): array
    {
        $res = $this->client->read("state_queryStorageAt", [$keys, $at]);
        return $res["result"];
    }

    /**
     * Retrieves the runtime version via subscription
     *
     * @return array
     */
    public function subscribeRuntimeVersion (): array
    {
        $res = $this->client->read("state_subscribeRuntimeVersion");
        return $res["result"];
    }

    /**
     * Subscribes to storage changes for the provided keys
     *
     * @param array $keys
     * @return array
     */
    public function subscribeStorage (array $keys): array
    {
        $res = $this->client->read("state_subscribeStorage", [$keys]);
        return $res["result"];
    }

    /**
     * Provides a way to trace the re-execution of a single block
     *
     * @param string $block
     * @param string $target
     * @param string $storageKeys
     * @param string $methods
     * @return array
     */
    public function traceBlock (string $block, string $target, string $storageKeys, string $methods): array
    {
        $res = $this->client->read("state_traceBlock", [$block, $target, $storageKeys, $methods]);
        return $res["result"];
    }

    /**
     * Check current migration state
     *
     * @param string $at
     * @return array
     */
    public function trieMigrationStatus (string $at = ""): array
    {
        $res = $this->client->read("state_trieMigrationStatus", [$at]);
        return $res["result"];
    }
}