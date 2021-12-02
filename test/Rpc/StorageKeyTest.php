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

    public function testEncode ()
    {
        $this->assertEquals("5f3e4907f716ac89b6347d15ececedca3ed14b45ed20d054f05e37e2542cfe70469dc8e44fce245a1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49",
            StorageKey::encode("Staking", "Bonded", $this->metadata, ["0x1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"])->encodeKey);
    }
}
