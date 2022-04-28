<?php

namespace Rpc\JsonRpc;
interface IEngine
{
    /**
     * Reexecute the specified block_hash and gather statistics while doing so
     *
     * @param bool $createEmpty
     * @param bool $finalize
     * @param string $parentHash
     * @return array
     */
    function createBlock (bool $createEmpty, bool $finalize, string $parentHash = ""): array;

    /**
     * @param string $hash
     * @param array $justification
     * @return bool
     */
    function finalizeBlock (string $hash, array $justification): bool;

}