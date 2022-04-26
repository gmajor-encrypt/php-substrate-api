<?php

namespace Rpc\Test;


use Rpc\KeyPair\KeyPair;
use Rpc\WSClient;
use Rpc\HttpClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;
use WebSocket\ConnectionException;

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

        // isConnected = false when client has been closed
        $this->assertEquals($wsClient->isConnected, false);

        // use closed client will be raise ConnectionException
        $this->expectException(ConnectionException::class);
        $wsClient->read("readCloseData");

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

    public function testRpcStorageState ()
    {
        $wsClient = new SubstrateRpc("wss://kusama-rpc.polkadot.io/");
        // chain_getFinalizedHead
        $this->assertNotEmpty($wsClient->rpc->chain->getFinalizedHead());
        // system_name
        $this->assertEquals("Parity Polkadot", $wsClient->rpc->system->name()["result"]);
        // rpc with params chain_getBlockHash
        // https://kusama.subscan.io/block/10853190
        $header = $wsClient->rpc->chain->getBlock("0x3ef1a34520b3c00d3b32b86760f0bbcfc6c2fa89d65a27c48929287ae202462c")["result"]["block"]["header"];
        $this->assertEquals("0xa59b46", $header["number"]);
        // chain_getBlockHash with param blockNumber
        $blockHash = $wsClient->rpc->chain->getBlockHash("0xf4240");
        $this->assertEquals("0xb267ffd706bbb93779eab04f47c7038031657b0a863794dbdd73170e3976c3e7", $blockHash);

        $this->assertEquals("0xb0a8d493285c2df73290dfb7e61f870f17b41801197a149ca93654499ea3dafe", $wsClient->rpc->chain->getBlockHash("0x0"));
        $this->assertEquals(0, $wsClient->rpc->system->accountNextIndex("HLgKKHcwDtvdxJWQUttnt5PrzwqUsEXBAshetVt97miXsen"));
        $this->assertEquals("kusama", $wsClient->rpc->state->getRuntimeVersion()["specName"]);

        // state_call with no params will raise error
        $this->expectException(\InvalidArgumentException::class);
        $wsClient->rpc->state->call();

        // close ws client connection
        $wsClient->close();
    }

    /**
     * @throws \SodiumException
     */
    public function testSendTransaction ()
    {
        $wsClient = new SubstrateRpc("wss://kusama-rpc.polkadot.io/");

        $wsClient->setSigner(KeyPair::initKeyPair("sr25519", "", $wsClient->hasher));

        $wsClient->close();

    }
}
