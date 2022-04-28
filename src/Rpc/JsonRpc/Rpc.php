<?php

namespace Rpc\JsonRpc;

class Rpc extends Base implements IRpc
{

    /**
     * Retrieves the list of RPC methods that are exposed by the node
     *
     * @return array
     */
    function methods (): array
    {
        $res = $this->client->read("rpc_methods");
        return $res["result"];
    }
}