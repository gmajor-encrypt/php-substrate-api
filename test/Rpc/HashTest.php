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

    public function testXX64Hash ()
    {
        $this->assertEquals("398167db5dcadc4f",$this->hasher->checkSum(0,"test"));
    }

}
