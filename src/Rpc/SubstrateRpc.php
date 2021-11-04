<?php

namespace Rpc;

class SubstrateRpc
{

    /**
     * @var Rpc
     */
    public Rpc $rpc;


    public function __construct()
    {
        $this->rpc = new Rpc();
    }
}
