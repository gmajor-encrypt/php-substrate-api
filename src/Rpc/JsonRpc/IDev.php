<?php

namespace Rpc\JsonRpc;
interface IDev
{
    /**
     * Reexecute the specified block_hash and gather statistics while doing so
     *
     * @param string $at
     * @return array
     */
    function getBlockStats (string $at): array;
}