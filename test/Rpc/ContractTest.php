<?php

namespace Rpc\Test;

use Codec\Base;
use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use PHPUnit\Framework\TestCase;
use Rpc\Contract;
use Rpc\Contract\Abi\ContractMetadataV0;
use Rpc\Contract\Abi\ContractMetadataV1;
use Rpc\Contract\Abi\ContractMetadataV2;
use Rpc\Contract\Abi\ContractMetadataV3;
use Rpc\Contract\Abi\ContractMetadataV4;
use Rpc\Contract\ContractExecResult;
use Rpc\KeyPair\KeyPair;
use Rpc\SubstrateRpc;
use Rpc\Util;
use WebSocket\ConnectionException;

require_once "const.php";

final class ContractTest extends TestCase
{
    public string $AliceSeed = "0xe5be9a5092b81bca64be81d212e7f2f9eba183bb7a90954f7b76361f6edb5c0a";

    public string $flipperContract = "0x5087232e4cc344500ca789607e1083d5c075eda75d205fe007eff14629bd86ef";

    public function testAbiMetadataV0Parse ()
    {
        $content = json_decode(file_get_contents(__DIR__ . '/ink/ink_v0.json'), true);
        $v0 = ContractMetadataV0::to_obj($content);
        foreach (["constructors", "docs", "events", "messages"] as $value) {
            $this->assertArrayHasKey($value, $v0->spec);
        }
        $this->assertIsArray($v0->spec["constructors"][0]["name"]);
        $this->assertIsArray($v0->spec["messages"][0]["name"]);
        $v1 = $v0->toV1();
        $this->assertGreaterThan(0, count($v1->spec));
        $this->assertGreaterThan(0, count($v1->types));

        // to latest
        $latest = $v1->toV2()->toV3()->toV4();
        $this->assertGreaterThan(0, count($latest->spec));
        $this->assertGreaterThan(0, count($latest->types));
    }


    public function testAbiMetadataV1Parse ()
    {
        $content = json_decode(file_get_contents(__DIR__ . '/ink/ink_v1.json'), true);
        $v1 = ContractMetadataV1::to_obj($content);
        $this->assertIsArray($v1->spec["constructors"][0]["name"]);
        $this->assertIsArray($v1->spec["messages"][0]["name"]);
        $v2 = $v1->toV2();

        $this->assertEquals($v2->spec["constructors"][0]["label"], join("::", $v1->spec["constructors"][0]["name"]));
        $this->assertEquals($v2->spec["messages"][0]["label"], join("::", $v1->spec["messages"][0]["name"]));
        $this->assertGreaterThan(0, count($v2->spec));
        $this->assertGreaterThan(0, count($v2->types));

        // to latest
        $latest = $v2->toV3()->toV4();
        $this->assertGreaterThan(0, count($latest->spec));
        $this->assertGreaterThan(0, count($latest->types));
    }


    public function testAbiMetadataV2Parse ()
    {
        $content = json_decode(file_get_contents(__DIR__ . '/ink/ink_v2.json'), true);
        $v2 = ContractMetadataV2::to_obj($content);
        $this->assertArrayHasKey("label", $v2->spec["constructors"][0]);
        $this->assertArrayHasKey("label", $v2->spec["messages"][0]);
        $this->assertArrayHasKey("label", $v2->spec["events"][0]);

        $v3 = $v2->toV3();
        $this->assertEquals(true, $v3->spec["constructors"][0]["payable"]);
        $this->assertGreaterThan(0, count($v2->spec));
        $this->assertGreaterThan(0, count($v2->types));

        // to latest
        $latest = $v3->toV4();
        $this->assertGreaterThan(0, count($latest->spec));
        $this->assertGreaterThan(0, count($latest->types));
    }

    public function testAbiMetadataV3Parse ()
    {
        $content = json_decode(file_get_contents(__DIR__ . '/ink/ink_v3.json'), true);
        $v3 = ContractMetadataV3::to_obj($content);
        $this->assertArrayHasKey("payable", $v3->spec["constructors"][0]);
        $this->assertArrayHasKey("payable", $v3->spec["messages"][0]);

        $v4 = $v3->toV4();
        $this->assertGreaterThan(0, count($v4->spec));
        $this->assertGreaterThan(0, count($v4->types));
    }

    public function testAbiMetadataV4Parse ()
    {
        $content = json_decode(file_get_contents(__DIR__ . '/ink/ink_v4.json'), true);
        $v4 = ContractMetadataV4::to_obj($content);
        $this->assertGreaterThan(0, count($v4->spec));
        $this->assertGreaterThan(0, count($v4->types));
        foreach (["constructors", "docs", "events", "messages"] as $value) {
            $this->assertArrayHasKey($value, $v4->spec);
        }
        $scale = new ScaleInstance(Base::create());
        $v4->register_type($scale->getGenerator(), "testAbiMetadataV4Parse");
        $this->assertArrayHasKey("Primitive", $v4->types[0]["type"]["def"]);
        $this->assertArrayNotHasKey("primitive", $v4->types[0]["type"]["def"]);

        $this->assertEquals(["Result"], $v4->types[1]["type"]["path"]);
        self::assertNotNull($scale->getGenerator()->getRegistry("testAbiMetadataV4Parse:ink_primitives:LangError"));
        self::assertNull($scale->getGenerator()->getRegistry("Result<bool,testAbiMetadataV4Parse:ink_primitives:LangError>"));

        $this->assertEquals(count($v4->types), count($v4->getRegisteredSiType()));
    }


    /**
     * @throws \SodiumException
     * @throws ConnectionException
     */
    public function testDeployContract ()
    {
        $endpoint = getenv("RPC_URL") == "" ? "ws://127.0.0.1:9944" : getenv("RPC_URL");
        $wsClient = new SubstrateRpc($endpoint);
        $wsClient->setSigner(KeyPair::initKeyPair("sr25519", $this->AliceSeed, $wsClient->hasher));
        $contract = new Contract($wsClient->tx);
        $result = $contract->new(Constant::$flipperCode, "0x9bae9d5e01", []);
        $this->assertEquals(64, strlen(Util::trimHex($result))); // transaction hash
        $wsClient->close();
    }

    /**
     * @throws \SodiumException
     * @throws ConnectionException
     */
    public function testContractQueryState ()
    {
        $endpoint = getenv("RPC_URL") == "" ? "ws://127.0.0.1:9944" : getenv("RPC_URL");
        $wsClient = new SubstrateRpc($endpoint);
        $wsClient->setSigner(KeyPair::initKeyPair("sr25519", $this->AliceSeed, $wsClient->hasher));
        $v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_v4.json'), true));
        $v4->register_type($wsClient->tx->codec->getGenerator(), "testAbiMetadataV4Parse");

        // read contract
        $contract = new Contract($wsClient->tx, $this->flipperContract, $v4);
        $execResult = $contract->state->get();
        foreach (["gasConsumed", "gasRequired", "StorageDeposit", "debugMessage", "result"] as $value) {
            $this->assertArrayHasKey($value, $execResult->result);
        }
        $result = ContractExecResult::deserialization($execResult->result);
        $this->assertNotEmpty($result->result->Ok);
        // decode result
        // Result<bool,ink_primitives::LangError>
        $this->assertArrayHasKey("Ok", $result->decodeResult($wsClient->tx->codec, $execResult->type));
        $wsClient->close();
    }


    /**
     * @throws \SodiumException
     * @throws ConnectionException
     */
    public function testContractSendTx ()
    {
        $endpoint = getenv("RPC_URL") == "" ? "ws://127.0.0.1:9944" : getenv("RPC_URL");
        $wsClient = new SubstrateRpc($endpoint);
        $wsClient->setSigner(KeyPair::initKeyPair("sr25519", $this->AliceSeed, $wsClient->hasher));

        $v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_v4.json'), true));
        $v4->register_type($wsClient->tx->codec->getGenerator(), "testAbiMetadataV4Parse");

        // read contract
        $contract = new Contract($wsClient->tx, $this->flipperContract, $v4);
        $result = $contract->call->flip([]);
        $this->assertEquals(64, strlen(Util::trimHex($result))); // transaction hash
        $wsClient->close();
    }
}
