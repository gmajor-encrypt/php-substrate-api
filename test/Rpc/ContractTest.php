<?php

namespace Rpc\Test;

use Codec\Base;
use Codec\Types\ScaleInstance;
use PHPUnit\Framework\TestCase;
use Rpc\Contract\Abi\ContractMetadataV0;
use Rpc\Contract\Abi\ContractMetadataV1;
use Rpc\Contract\Abi\ContractMetadataV2;
use Rpc\Contract\Abi\ContractMetadataV3;
use Rpc\Contract\Abi\ContractMetadataV4;

final class ContractTest extends TestCase
{
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

}