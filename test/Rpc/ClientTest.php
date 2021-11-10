<?php

namespace Rpc\Test;

use Rpc\WSClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testSubscribe ()
    {
        $wsClient = new WSClient("wss://kusama-rpc.polkadot.io/");
        $this->assertNotEmpty($wsClient->read("system_health"));
        $wsClient->close();
    }

    public function testSubstrateRpc ()
    {
        $wsClient = new SubstrateRpc("https://kusama-rpc.polkadot.io/");
        $this->expectException(\InvalidArgumentException::class);
        $wsClient->rpc->parent->hash();
    }
}
