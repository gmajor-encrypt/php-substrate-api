<?php

namespace Rpc\Test;


use PHPUnit\Framework\TestCase;
use Rpc\ss58;
use Rpc\Util;

final class UtilTest extends TestCase
{
    public function testTrimHex ()
    {
        $this->assertEquals("0", Util::trimHex("0x0"));
        $this->assertEquals("", Util::trimHex(""));
        $this->assertEquals("00000", Util::trimHex("00000"));
        $this->assertEquals("641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928", Util::trimHex("0x641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928"));
    }

    public function testAddHex ()
    {
        $this->assertEquals("0x0", Util::addHex("0x0"));
        $this->assertEquals("0x", Util::addHex(""));
        $this->assertEquals("0x00000", Util::addHex("00000"));
        $this->assertEquals("0x641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928", Util::addHex("0x641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928"));
    }

    public function testSS58Encode ()
    {
        $this->assertEquals("FfZRiEyrJwgxFZx1QsCnDjaJCHXoeUS4v4Hs1Yo8GpVveNQ", ss58::encode("88b3bfe1410ed8a12cd8a2c230e97cfd5a9fb1cc95ac859ec9c9a2ecfe7cf84f", 2));
        $this->assertEquals("5HZ3o1uoA6oKYjb86YnuSU2nbz8dw1LNj6joFzguGtn2wHu2", ss58::encode("f2cb2711b197eef9f2803aa2f087a1cedfeae2e10f55ef9242230efe18454491", 42));
        $this->assertEquals("8ANgaUSe4rALo2qjPYHYsDLLEGKf8ww9Y3wrpsUrSYgSE9K", ss58::encode("3ccbd50810c15f4cec3462ddb73b1ba5982cfb8643b9214e715a785e1e88e500", 1));
        $this->assertEquals("dfWZgJcjoC8ux7fcmaxvbbirHAvJBGTRof2EZuCQ4XsT8WgYS", ss58::encode("00818d1c3bb9ff5b214698f2f25c111501236311f53bd5c090d14b3276198259", 77));
    }
}