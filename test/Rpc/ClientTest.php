<?php

namespace Rpc\Test;


use Rpc\WSClient;
use Rpc\HttpClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testWsClientShouldReceiveData ()
    {
        // allow custom headers
        $customHeaderClient = new WSClient("wss://kusama-rpc.polkadot.io/", ["FROM_ORIGIN" => "gmajor"]);
        $this->assertEquals(["FROM_ORIGIN" => "gmajor"], $customHeaderClient->header);

        // read msg success
        $wsClient = new WSClient("wss://kusama-rpc.polkadot.io/");
        $this->assertNotEmpty($wsClient->read("system_health"));

        // call not exist method with message Method not found
        $this->assertEquals($wsClient->read("ff")['error']['message'], "Method not found");
        $wsClient->close();

        // require ws/wss prefixed endpoint
        $this->expectException(\InvalidArgumentException::class);
        new WSClient("https://");
    }

    public function testHttpClientShouldReceiveData ()
    {
        // require http/https prefixed endpoint
        $this->expectException(\InvalidArgumentException::class);
        new HttpClient("ws://test.io");
        // allow custom headers
        $customHeaderClient = new HttpClient("wss://kusama-rpc.polkadot.io/", ["FROM_ORIGIN" => "gmajor"]);
        // always isConnected = true
        $this->assertTrue($customHeaderClient->isConnected);
        $this->assertEquals(["FROM_ORIGIN" => "gmajor"], $customHeaderClient->header);
        $client = new SubstrateRpc("https://kusama-rpc.polkadot.io/");
        // method need some data
        $this->assertIsArray($client->rpc->methods);
        // parent_hash is not exist rpc
        $this->expectException(\InvalidArgumentException::class);
        $client->rpc->parent->hash();
    }
}
