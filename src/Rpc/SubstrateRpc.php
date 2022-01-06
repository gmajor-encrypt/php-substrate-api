<?php

namespace Rpc;

class SubstrateRpc
{

    /**
     * @var Rpc instance
     */
    public Rpc $rpc;


    /**
     * construct
     *
     * @param string $endpoint
     */
    public function __construct (string $endpoint)
    {
        $this->rpc = new Rpc($endpoint);
    }

    /**
     *  client close connection
     *
     * @return void
     */
    public function close ()
    {
        $this->rpc->client->close();
    }
}
