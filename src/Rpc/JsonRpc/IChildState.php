<?php

namespace Rpc\JsonRpc;

interface IChildState
{

    /**
     * Returns the keys with prefix from a child storage, leave empty to get all the keys
     *
     * @param string $childKey
     * @param string $prefix
     * @param string $at
     * @return array
     */
    public function getKeys (string $childKey, string $prefix, string $at = ""): array;

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
    public function getKeysPaged (string $childKey, string $prefix, int $count, string $startKey, string $at = ""): array;

    /**
     * Returns a child storage entry at a specific block state
     *
     * @param string $childKey
     * @param string $key
     * @param string $at
     * @return array
     */
    public function getStorage (string $childKey, string $key, string $at = ""): array;

    /**
     * Returns child storage entries for multiple keys at a specific block state
     *
     * @param string $childKey
     * @param array $keys
     * @param string $at
     * @return array
     */
    public function getStorageEntries (string $childKey, array $keys, string $at = ""): array;

    /**
     * Returns the hash of a child storage entry at a block state
     *
     * @param string $childKey
     * @param string $key
     * @param string $at
     * @return string
     */
    public function getStorageHash (string $childKey, string $key, string $at = ""): string;


    /**
     * Returns the size of a child storage entry at a block state
     *
     * @param string $childKey
     * @param string $key
     * @param string $at
     * @return string
     */
    public function getStorageSize (string $childKey, string $key, string $at = ""): string;
}