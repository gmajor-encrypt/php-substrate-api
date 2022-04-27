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
    public function testStorageKeyEncode ()
    {
        // storage key with no param
        $this->assertEquals(
            new StorageKey("U64", "f0c365c3cf59d671eb72da0e7a4113c49f1f0515f462cdcf84e0f1d6045dfcbb"),
            StorageKey::encode("Timestamp", "now", $this->metadata, []));

        // storage key with one param
        $this->assertEquals(
            new StorageKey("sp_core:crypto:AccountId32", "5f3e4907f716ac89b6347d15ececedca3ed14b45ed20d054f05e37e2542cfe70469dc8e44fce245a1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"),
            StorageKey::encode("Staking", "Bonded", $this->metadata, ["0x1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"]));

        $this->assertEquals(
            new StorageKey("frame_system:AccountInfo", "26aa394eea5630e07c48ae0c9558cef7b99d880ec681799c0cf30e8886371da9ae127a8f8f6f622fa2de1ab4de31f2751c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"),
            StorageKey::encode("System", "Account", $this->metadata, ["0x1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"]));

        // storage key with double map
        $this->assertEquals(
            new StorageKey("pallet_staking:Exposure", "5f3e4907f716ac89b6347d15ececedca8bde0a0ea8864605e3b68ed9cb2da01b5153cb1f00942ff40100000009e404b71daf5f559094c424429709a324e65c64f151630e6c3700192bba8abd3c8e2218b61c0a7a"),
            StorageKey::encode("Staking", "ErasStakers", $this->metadata, ["0x01000000", "0x9094c424429709a324e65c64f151630e6c3700192bba8abd3c8e2218b61c0a7a"]));

        // Invalid module or storage name, like moonbeam authorMapping module
        $this->expectException(\InvalidArgumentException::class);
        StorageKey::encode("authorMapping", "mappingWithDeposit", $this->metadata);

        // invalid args
        $this->expectException(\InvalidArgumentException::class);
        StorageKey::encode("Staking", "Ledger", $this->metadata);
    }
}