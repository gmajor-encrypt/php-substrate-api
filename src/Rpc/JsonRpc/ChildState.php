<?php

namespace Rpc\JsonRpc;

class ChildState extends base implements IChildState
{

    /**
     * Returns the keys with prefix from a child storage, leave empty to get all the keys
     *
     * @param string $childKey
     * @param string $prefix
     * @param string $at
     * @return array
     */
    public function getKeys (string $childKey, string $prefix, string $at = ""): array
    {
        $res = $this->client->read("babe_epochAuthorship", [$childKey, $prefix, $at]);
        return $res["result"];
    }

    /**
     * Returns the keys with prefix from a child storage with pagination support
     *
     * @param string $childKey
     * @param string $prefix
     * @param int $count
     * @param string $startKey
     * @param string $at
     * @return array
     */
    public function getKeysPaged (string $childKey, string $prefix, int $count, string $startKey, string $at = ""): array
    {
        $res = $this->client->read("childstate_getKeysPaged", [$childKey, $prefix, $count, $startKey, $at]);
        return $res["result"];
    }

    /**
     * Returns a child storage entry at a specific block state
     *
     * @param string $childKey
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getStorage (string $childKey, string $key, string $at = ""): array
    {
        $res = $this->client->read("childstate_getStorage", [$childKey, $key, $at]);
        return $res["result"];
    }

    /**
     * Returns child storage entries for multiple keys at a specific block state
     *
     * @param string $childKey
     * @param array $keys
     * @param string $at
     * @return array
     */
    public function getStorageEntries (string $childKey, array $keys, string $at = ""): array
    {
        $res = $this->client->read("childstate_getStorageEntries", [$childKey, $keys, $at]);
        return $res["result"];
    }

    /**
     * Returns the hash of a child storage entry at a block state
     *
     * @param string $childKey
     * @param string $key
     * @param string $at
     * @return string
     */
    public function getStorageHash (string $childKey, string $key, string $at = ""): string
    {
        $res = $this->client->read("childstate_getStorageHash", [$childKey, $key, $at]);
        return $res["result"];
    }

    /**
     * Returns the size of a child storage entry at a block state
     *
     * @param string $childKey
     * @param string $key
     * @param string $at
     * @return string
     */
    public function getStorageSize (string $childKey, string $key, string $at = ""): string
    {
        $res = $this->client->read("childstate_getStorageSize", [$childKey, $key, $at]);
        return $res["result"];
    }
}