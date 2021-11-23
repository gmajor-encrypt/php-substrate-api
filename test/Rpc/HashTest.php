<?php

namespace Rpc\Test;

use Rpc\Hasher\Hasher;
use PHPUnit\Framework\TestCase;

final class HashTest extends TestCase
{
    protected Hasher $hasher;

    /**
     * @before
     */
    public function initHash ()
    {
        $this->hasher = new Hasher();
    }

    /**
     * @throws \SodiumException
     */
    public function testHasher ()
    {
        $this->assertEquals("398167db5dcadc4f", $this->hasher->XXHash64(0, "test"));
        $this->assertEquals("c2261276cc9d1f8598ea4b6a74b15c2f", $this->hasher->TwoxHash("Balances", 128));
        $this->assertEquals("c2261276cc9d1f8598ea4b6a74b15c2f1982647952c5af2b7adeff7496e6388b", $this->hasher->TwoxHash("Balances", 256));
        $this->assertEquals("b99d880ec681799c4163636f756e74", $this->hasher->ByHasherName("Twox64Concat", "0x" . bin2hex("Account")));
        $this->assertEquals("5328fa027215451bcef79a1905b063d7", $this->hasher->ByHasherName("Blake2_128", "20be52a5a80cad065651ec35fcb1a212bc669aabb52d68d8780a41e29ec9c83e"));
        $this->assertEquals("bc024881cc9d7e3e4474fa73a769e921490f148c24f06228695051cfe793b6f0", $this->hasher->ByHasherName("Blake2_256", "20be52a5a80cad065651ec35fcb1a212bc669aabb52d68d8780a41e29ec9c83e"));
        $this->assertEquals("68656c6c6f", $this->hasher->ByHasherName("Identity", "68656c6c6f"));
    }
}
