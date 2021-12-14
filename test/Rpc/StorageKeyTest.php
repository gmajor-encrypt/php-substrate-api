<?php

namespace Rpc\Test;

use Codec\Base;
use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use Rpc\StorageKey;
use PHPUnit\Framework\TestCase;

require_once "const.php";

final class StorageKeyTest extends TestCase
{
    protected array $metadata;

    /**
     * @before
     */
    public function initHash ()
    {
        $codec = new ScaleInstance(Base::create());
        $metadata = $codec->process("metadata", new ScaleBytes(Constant::$metadataStaticV14));
        $this->metadata = $metadata["metadata"];
    }

    /**
     * @throws \SodiumException
     */
    public function testEncode ()
    {
        $this->assertEquals(
            new StorageKey("[U8; 32]","5f3e4907f716ac89b6347d15ececedca3ed14b45ed20d054f05e37e2542cfe70469dc8e44fce245a1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"),
            StorageKey::encode("Staking", "Bonded", $this->metadata, ["0x1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"]));

        $this->assertEquals(
            new StorageKey("AccountInfo","26aa394eea5630e07c48ae0c9558cef7b99d880ec681799c0cf30e8886371da9ae127a8f8f6f622fa2de1ab4de31f2751c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"),
            StorageKey::encode("System", "Account", $this->metadata, ["0x1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"]));
        $this->expectException(\InvalidArgumentException::class);
        // moonbeam module
        StorageKey::encode("authorMapping", "mappingWithDeposit", $this->metadata);
        // invalid args
        $this->expectException(\InvalidArgumentException::class);
        StorageKey::encode("Staking", "Ledger", $this->metadata);
    }
}
