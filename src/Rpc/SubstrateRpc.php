<?php

namespace Rpc;

class SubstrateRpc
{

    /**
     * @var Rpc
     */
    public Rpc $rpc;


    /**
     * construct
     *
     * @param string $endpoint
     */
    public function __construct(string $endpoint)
    {
        $this->rpc = new Rpc($endpoint);
    }
}
