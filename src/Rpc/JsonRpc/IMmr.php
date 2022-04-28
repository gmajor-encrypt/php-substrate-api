<?php

namespace Rpc\JsonRpc;
interface IMmr
{
    /**
     * Generate MMR proof for given leaf index.
     *
     * @param int $leafIndex
     * @param string $at
     * @return array
     */
    function generateProof (int $leafIndex, string $at = ""): array;
}