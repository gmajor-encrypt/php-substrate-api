<?php

namespace Rpc\JsonRpc;

use Rpc\IClient;

class Base
{
    /**
     * @var IClient $client
     */
    public IClient $client;

    /**
     * @param IClient $client
     */
    public function __construct (IClient $client)
    {
        $this->client = $client;
    }

}