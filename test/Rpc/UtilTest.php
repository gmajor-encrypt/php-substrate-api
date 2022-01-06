<?php

namespace Rpc\Test;


use PHPUnit\Framework\TestCase;
use Rpc\Util;

final class UtilTest extends TestCase
{
    public function testTrimHex ()
    {
        $this->assertEquals("0",Util::trimHex("0x0"));
        $this->assertEquals("",Util::trimHex(""));
        $this->assertEquals("00000",Util::trimHex("00000"));
        $this->assertEquals("641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928",Util::trimHex("0x641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928"));
    }

    public function testAddHex ()
    {
        $this->assertEquals("0x0",Util::addHex("0x0"));
        $this->assertEquals("0x",Util::addHex(""));
        $this->assertEquals("0x00000",Util::addHex("00000"));
        $this->assertEquals("0x641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928",Util::addHex("0x641b4287610927a21d21a6d5b0464c7ecc07ec9a2481e419fbfae9f242bb5928"));
    }
}