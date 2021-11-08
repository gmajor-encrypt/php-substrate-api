<?php

namespace Rpc\Test;

use Rpc\Config;
use Rpc\HttpClient;
use Rpc\WSClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testSubscribe()
    {
        Config::setEndPoint("wss://kusama-rpc.polkadot.io/");
        $wsClient = new WSClient();
        $this->assertNotEmpty($wsClient->read("system_health"));
    }

    public function testHTTPSubscribe()
    {
        Config::setEndPoint("https://kusama-rpc.polkadot.io/");
        $wsClient = new HttpClient();
        $this->assertNotEmpty($wsClient->read("system_health"));
    }

    public function testSubstrateRpc()
    {
        Config::setEndPoint("https://kusama-rpc.polkadot.io/");
        $wsClient = new SubstrateRpc();
        $this->expectException(\InvalidArgumentException::class);
        $wsClient->rpc->parent->hash();
    }
}
