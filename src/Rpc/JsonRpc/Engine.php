<?php

namespace Rpc\JsonRpc;

class Engine extends Base implements IEngine
{

    /**
     * Reexecute the specified block_hash and gather statistics while doing so
     *
     * @param bool $createEmpty
     * @param bool $finalize
     * @param string $parentHash
     * @return array
     */
    function createBlock (bool $createEmpty, bool $finalize, string $parentHash = ""): array
    {
        $res = $this->client->read("engine_createBlock", [$createEmpty, $finalize, $parentHash]);
        return $res["result"];
    }

    /**
     * @param string $hash
     * @param array $justification
     * @return bool
     */
    function finalizeBlock (string $hash, array $justification): bool
    {
        $res = $this->client->read("engine_finalizeBlock", [$hash, $justification]);
        return $res["result"];
    }
}