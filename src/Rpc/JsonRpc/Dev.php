<?php

namespace Rpc\JsonRpc;

class Dev extends Base implements IDev
{

    /**
     * Reexecute the specified block_hash and gather statistics while doing so
     *
     * @param string $at
     * @return array
     */
    function getBlockStats (string $at): array
    {
        $res = $this->client->read("dev_getBlockStats",[$at]);
        return $res["result"];
    }
}