<?php

namespace Rpc\Test;

use Codec\Base;
use Codec\Types\ScaleInstance;
use PHPUnit\Framework\TestCase;
use Rpc\Contract;
use Rpc\Contract\Abi\ContractMetadataV0;
use Rpc\Contract\Abi\ContractMetadataV1;
use Rpc\Contract\Abi\ContractMetadataV2;
use Rpc\Contract\Abi\ContractMetadataV3;
use Rpc\Contract\Abi\ContractMetadataV4;
use Rpc\Contract\Address;
use Rpc\Contract\ContractExecResult;
use Rpc\Hasher\Hasher;
use Rpc\KeyPair\KeyPair;
use Rpc\SubstrateRpc;
use Rpc\Util;
use WebSocket\ConnectionException;

require_once "const.php";

final class ContractTest extends TestCase
{
    public string $AliceSeed = "0xe5be9a5092b81bca64be81d212e7f2f9eba183bb7a90954f7b76361f6edb5c0a";


    public string $AliceId = "0xd43593c715fdd31c61141abd04a99fd6822c8558854ccde39a5684e7a56da27d";
    public string $BobId = "0x8eaf04151687736326c9fea17e25fc5287613693c912909cb226aa4794f26a48";


    public string $flipperContract = "0xfe6d6f70ff2940ae47bfb3fac7cbb5189a1e1e46ee1852acadb0490625d064ec";

    public string $erc20Contract = "0x6ceba22ac458402cf9c1f055d51f0b901be2d32ded222c5607b0d316a758aaaf";

    // https://shibuya.subscan.io/extrinsic/3319796-6


    public SubstrateRpc $wsClient;
    protected Hasher $hasher;

    /**
     * @before
     * @throws ConnectionException|\SodiumException
     */
    public function initHash ()
    {
        $endpoint = getenv("RPC_URL") == "" ? "wss://shibuya-rpc.dwellir.com" : getenv("RPC_URL");
        $this->wsClient = new SubstrateRpc($endpoint);
        $this->hasher = new Hasher();
        $this->wsClient->tx->withOpt(["subscribe" => true]);
        $this->wsClient->setSigner(KeyPair::initKeyPair("sr25519", $this->AliceSeed, $this->hasher), $this->hasher);
    }


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
        $this->assertFalse($latest->is_empty());

        // ContractMetadataV0 with no metadataVersion param will raise error
        $this->expectException(\InvalidArgumentException::class);
        ContractMetadataV0::to_obj(["types" => [], "spec" => []]);
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
        $this->assertFalse($latest->is_empty());

        // ContractMetadataV1 with no V1 key will raise error
        $this->expectException(\InvalidArgumentException::class);
        ContractMetadataV1::to_obj(["V2" => ["types" => [], "spec" => []]]);
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
        $this->assertFalse($latest->is_empty());

        // ContractMetadataV2 with no V2 key will raise error
        $this->expectException(\InvalidArgumentException::class);
        ContractMetadataV2::to_obj(["v3" => ["types" => [], "spec" => []]]);
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
        $this->assertFalse($v4->is_empty());

        // ContractMetadataV3 with no V3 key will raise error
        $this->expectException(\InvalidArgumentException::class);
        ContractMetadataV3::to_obj(["v4" => ["types" => [], "spec" => []]]);
    }

    public function testAbiMetadataV4Parse ()
    {
        foreach (["ink_v4", "ink_erc20"] as $fileName) {
            $content = json_decode(file_get_contents(__DIR__ . '/ink/' . $fileName . '.json'), true);
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
        // ContractMetadataV4 with no version will raise error
        $this->expectException(\InvalidArgumentException::class);
        ContractMetadataV4::to_obj(["types" => [], "spec" => []]);

    }

    /**
     * test Sample Deploy Contract flipper
     *
     */
    public function testSampleDeployContract ()
    {
        $contract = new Contract($this->wsClient->tx);
        $this->wsClient->tx->withOpt(["subscribe" => false]);
        $result = $contract->new(Constant::$flipperCode, "0x9bae9d5e01");
        $this->assertEquals(64, strlen(Util::trimHex($result->getContractAddress()))); // new contract accountID
        $this->assertEquals(64, strlen(Util::trimHex($result->getTransactionHash()))); //  transaction hash
        $this->wsClient->tx->withOpt(["subscribe" => true]);
        // input data not string will raise error
        $this->expectException(\InvalidArgumentException::class);
        $contract->new(Constant::$flipperCode, ["0x9bae9d5e01"]);
    }

    public function testErc20DeployContract ()
    {
        $v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_erc20.json'), true));
        $v4->register_type($this->wsClient->tx->codec->getGenerator(), "testErc20DeployContract");

        // send submitAndWatchExtrinsic util extrinsic is in block
        $contract = new Contract($this->wsClient->tx, "", $v4);
        // deploy with constructor args and waiting extrinsic exec success
        $result = $contract->new(Constant::$Erc20Code, [100000000]);
        $this->assertEquals(64, strlen(Util::trimHex($result->getInBlockHash()))); // deploy contract block hash
        // constructor args count Mismatch will raise error
        $this->expectException(\InvalidArgumentException::class);
        $contract->new(Constant::$Erc20Code, [0, 1, 2, 3]);
    }


    public function testErc20Contract ()
    {
        $v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_erc20.json'), true));
        $v4->register_type($this->wsClient->tx->codec->getGenerator(), "testErc20Contract");
        // read contract
        $contract = new Contract($this->wsClient->tx, $this->erc20Contract, $v4);
        // total_supply
        $execResult = $contract->state->total_supply();
        $this->assertEquals(["Ok" => gmp_init(100000000)], ContractExecResult::getDecodeResult($execResult, $this->wsClient->tx->codec));

        // balance_of
        $execResult = $contract->state->balance_of($this->BobId);
        $bobBalance = ContractExecResult::getDecodeResult($execResult, $this->wsClient->tx->codec)["Ok"];
        $this->assertTrue(gmp_cmp($bobBalance, 0) == 1);// $aliceBalance must positive

        // exec transfer
        $contract->call->transfer($this->BobId, 100, []);

        // after transfer
        $execResult = $contract->state->balance_of($this->BobId);
        $bobBalance2 = ContractExecResult::getDecodeResult($execResult, $this->wsClient->tx->codec)["Ok"];
        $this->assertEquals(gmp_add($bobBalance, 100), $bobBalance2);
    }


    /**
     * @throws \SodiumException
     * @throws ConnectionException
     */
    public function testContractQueryState ()
    {
        $v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_v4.json'), true));
        $v4->register_type($this->wsClient->tx->codec->getGenerator(), "testAbiMetadataV4Parse");

        // read contract
        $contract = new Contract($this->wsClient->tx, $this->flipperContract, $v4);
        $execResult = $contract->state->get();
        foreach (["gasConsumed", "gasRequired", "StorageDeposit", "debugMessage", "result"] as $value) {
            $this->assertArrayHasKey($value, $execResult->result);
        }
        $result = ContractExecResult::deserialization($execResult->result);
        $this->assertNotEmpty($result->result->Ok);
        // decode result
        // Result<bool,ink_primitives::LangError>
        $this->assertArrayHasKey("Ok", $result->decodeResult($this->wsClient->tx->codec, $execResult->type));
        // query state count Mismatch will raise error
        $this->expectException(\InvalidArgumentException::class);
        $contract->state->get("xxx", "2222");
    }


    /**
     * @throws \SodiumException
     * @throws ConnectionException
     */
    public function testContractSendTx ()
    {
        $v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_v4.json'), true));
        $v4->register_type($this->wsClient->tx->codec->getGenerator(), "testAbiMetadataV4Parse");

        // write contract
        $contract = new Contract($this->wsClient->tx, $this->flipperContract, $v4);

        // origin result
        $originResult = ContractExecResult::getDecodeResult($contract->state->get(), $this->wsClient->tx->codec);
        $this->assertArrayHasKey("Ok", $originResult);

        // do flip
        $exec = $contract->call->flip([]);
        $this->assertTrue(array_key_exists("inBlock", $exec["params"]["result"]));

        // after flip result
        $afterResult = ContractExecResult::getDecodeResult($contract->state->get(), $this->wsClient->tx->codec);
        $this->assertArrayHasKey("Ok", $originResult);

        $this->assertTrue((!$originResult["Ok"]) == $afterResult["Ok"]);

        // query state count Mismatch will raise error
        $this->expectException(\InvalidArgumentException::class);
        $contract->call->flip("xxx", "2222", []);
    }

    /**
     * @return void
     * @throws \SodiumException
     */
    public function testContractAddress ()
    {
        $hasher = $this->wsClient->hasher;
        $codec = new ScaleInstance(Base::create());
        $bytes = $codec->createTypeByTypeString("bytes");
        $this->assertEquals("b17e29478cb1029a52efe2a62268af0d550bad5f10e05c4621602696209c9171", Address::GenerateAddress($hasher, "0x90b5ab205c6974c9ea841be688864633dc9ca8a357843eeacf2314649965fe22", "0x0829899532bc68083fc9bf3cd13cf2048abc23f20583a8781989f68bce995c65", $bytes->encode(""), $bytes->encode("")));
        $this->assertEquals("e9253b25cc661ac5eccb978dcf6dfad55e3db66e9aed8c407997e9f49a881b18", Address::GenerateAddress($hasher, "0x90b5ab205c6974c9ea841be688864633dc9ca8a357843eeacf2314649965fe22", "0xc1c8f7908a009379743280998a998580c8c7415919cb873cb320756b972a7d3a", $bytes->encode("9bae9d5e01"), $bytes->encode("01")));

        $this->expectException(\InvalidArgumentException::class);
        // pk or code_hash not bytes 32 length will raise error
        Address::GenerateAddress($hasher, "", "", "", "");
    }
}