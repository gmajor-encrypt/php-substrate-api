<?php

namespace Rpc\Test;


use Rpc\KeyPair\KeyPair;
use Rpc\Util;
use Rpc\WSClient;
use Rpc\HttpClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;
use WebSocket\ConnectionException;

final class ClientTest extends TestCase
{

    public string $AliceSeed = "0xe5be9a5092b81bca64be81d212e7f2f9eba183bb7a90954f7b76361f6edb5c0a";
    public array $BobId = ["Id" => "8eaf04151687736326c9fea17e25fc5287613693c912909cb226aa4794f26a48"];


    public function testWsClientShouldReceiveData ()
    {
        // allow custom headers
        $customHeaderClient = new WSClient("wss://kusama-rpc.polkadot.io/", ["FROM_ORIGIN" => "gmajor"]);
        $this->assertEquals(["FROM_ORIGIN" => "gmajor"], $customHeaderClient->header);

        // read msg success
        $wsClient = new WSClient("wss://kusama-rpc.polkadot.io/");
        $this->assertNotEmpty($wsClient->read("system_health"));

        // Method not found
        // require ws/wss prefixed endpoint
        $this->expectException(\InvalidArgumentException::class);
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
//        // system_name
        $this->assertEquals("Parity Polkadot", $wsClient->rpc->system->name());
        // rpc with params chain_getBlockHash
        // https://kusama.subscan.io/block/10853190
        $header = $wsClient->rpc->chain->getBlock("0x3ef1a34520b3c00d3b32b86760f0bbcfc6c2fa89d65a27c48929287ae202462c")["block"]["header"];
        $this->assertEquals("0xa59b46", $header["number"]);
        // chain_getBlockHash with param blockNumber
        $blockHash = $wsClient->rpc->chain->getBlockHash(1000000);
        $this->assertEquals("0xb267ffd706bbb93779eab04f47c7038031657b0a863794dbdd73170e3976c3e7", $blockHash);

        $this->assertEquals("0xb0a8d493285c2df73290dfb7e61f870f17b41801197a149ca93654499ea3dafe", $wsClient->rpc->chain->getBlockHash(0));
        $this->assertEquals(0, $wsClient->rpc->system->accountNextIndex("HLgKKHcwDtvdxJWQUttnt5PrzwqUsEXBAshetVt97miXsen"));
        $this->assertEquals("kusama", $wsClient->rpc->state->getRuntimeVersion()["specName"]);

        // state_call with no params will raise error
        $this->expectException(\InvalidArgumentException::class);
        $wsClient->rpc->state->call("","","");

        // close ws client connection
        $wsClient->close();
    }

    /**
     * @throws \SodiumException
     * @throws ConnectionException
     */
    public function testSendTransaction ()
    {
        // Alice send transfer 12345 token to Bob
        $endpoint = getenv("RPC_URL") == "" ? "ws://127.0.0.1:9944" : getenv("RPC_URL");
        $wsClient = new SubstrateRpc($endpoint);
        $wsClient->setSigner(KeyPair::initKeyPair("sr25519", $this->AliceSeed, $wsClient->hasher));
        $result = $wsClient->tx->Balances->transfer($this->BobId, 12345);
        $this->assertEquals(64, strlen(Util::trimHex($result))); // transaction hash
        $wsClient->close();
    }
}
