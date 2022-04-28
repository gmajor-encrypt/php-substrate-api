<?php

namespace Rpc\JsonRpc;

class SyncState extends Base implements ISyncState
{


    /**
     * Returns the json-serialized chainspec running the node, with a sync state.
     *
     * @param bool $raw
     * @return array
     */
    function genSyncSpec (bool $raw): array
    {
        $res = $this->client->read("sync_state_genSyncSpec", [$raw]);
        return $res["result"];
    }
}