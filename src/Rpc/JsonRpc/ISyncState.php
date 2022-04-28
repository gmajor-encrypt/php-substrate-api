<?php

namespace Rpc\JsonRpc;
interface ISyncState
{
    /**
     * Returns the json-serialized chainspec running the node, with a sync state.

     *
     * @param bool $raw
     * @return array
     */
    function genSyncSpec (bool $raw): array;
}