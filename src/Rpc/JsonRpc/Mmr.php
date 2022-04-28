<?php

namespace Rpc\JsonRpc;

class Mmr extends base implements IMmr
{
    /**
     * Generate MMR proof for given leaf index.
     *
     * @param int $leafIndex
     * @param string $at
     * @return array
     */
    function generateProof (int $leafIndex, string $at = ""): array
    {
        $res = $this->client->read("mmr_generateProof", [$leafIndex, $at]);
        return $res["result"];
    }
}