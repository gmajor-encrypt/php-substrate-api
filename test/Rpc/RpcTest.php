<?php

namespace Rpc\Test;

use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;

final class RpcTest extends TestCase
{
    // test storage state
    public function testStorageState ()
    {
        $wsClient = new SubstrateRpc("wss://kusama-rpc.polkadot.io/");
        // chain_getFinalizedHead
        $this->assertNotEmpty($wsClient->rpc->chain->getFinalizedHead());
        // system_name
        $this->assertEquals("Parity Polkadot", $wsClient->rpc->system->name()["result"]);
        // rpc with params
        // https://kusama.subscan.io/block/10853190
        $header = $wsClient->rpc->chain->getBlock("0x3ef1a34520b3c00d3b32b86760f0bbcfc6c2fa89d65a27c48929287ae202462c")["result"]["block"]["header"];
        $this->assertEquals("0xa59b46", $header["number"]);
        $wsClient->close();
    }
}