<?php

namespace Rpc\Test;

use Rpc\WSClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testWsClientShouldReceiveData ()
    {
        $wsClient = new WSClient("wss://kusama-rpc.polkadot.io/");
        $this->assertNotEmpty($wsClient->read("system_health"));
        $wsClient->close();
    }

    public function testHttpClientShouldReceiveData ()
    {
        $client = new SubstrateRpc("https://kusama-rpc.polkadot.io/");
        // method need some data
        $this->assertIsArray($client->rpc->methods);
        // parent_hash is not exist rpc
        $this->expectException(\InvalidArgumentException::class);
        $client->rpc->parent->hash();
    }
}
