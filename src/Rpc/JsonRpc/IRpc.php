<?php

namespace Rpc\JsonRpc;

interface IRpc
{
    /**
     * Retrieves the list of RPC methods that are exposed by the node
     *
     * @return array
     */
    function methods (): array;

}