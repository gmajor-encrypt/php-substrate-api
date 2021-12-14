<?php

namespace Rpc\Test;

use Rpc\WSClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;

final class RpcTest extends TestCase
{
    // test storage state
    public function testStorageState ()
    {
        $wsClient = new SubstrateRpc("wss://kusama-rpc.polkadot.io/");
        $res = $wsClient->rpc->chain->getFinalizedHead();
        $this->assertNotEmpty($res);
        $wsClient->close();
    }
}