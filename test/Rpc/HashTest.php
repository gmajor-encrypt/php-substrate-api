<?php

namespace Rpc\Test;

use Rpc\Hasher\Hasher;
use PHPUnit\Framework\TestCase;
use Rpc\KeyPair\KeyPair;

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
        // XXHash64
        $this->assertEquals("398167db5dcadc4f", $this->hasher->XXHash64(0, "test"));
        $this->assertEquals("ad2ecd66275a1ded", $this->hasher->XXHash64(100, "test100"));
        $this->assertEquals("37da8eb7aca5d8f0", $this->hasher->XXHash64(99999999999999, "test100124124124214"));
        // XXHash64 with hex msg
        $this->assertEquals("5153cb1f00942ff4", $this->hasher->XXHash64(0, "0x01000000"));

        // TwoxHash 128
        $this->assertEquals("5c0d1176a568c1f92944340dbfed9e9c", $this->hasher->TwoxHash("Sudo", 128));
        $this->assertEquals("530ebca703c85910e7164cb7d1c9e47b", $this->hasher->TwoxHash("Key", 128));
        $this->assertEquals(32, strlen($this->hasher->TwoxHash("BalancesLength", 128)));
        // TwoxHash 256
        $this->assertEquals("c2261276cc9d1f8598ea4b6a74b15c2f1982647952c5af2b7adeff7496e6388b", $this->hasher->TwoxHash("Balances", 256));
        // TwoxHash 512
        $this->assertEquals("c2261276cc9d1f8598ea4b6a74b15c2f1982647952c5af2b7adeff7496e6388bd57f6573075368da523ba85a560e14054601e9f5eb82ac50ea5e348c60d59261", $this->hasher->TwoxHash("Balances", 512));
        // Twox64Concat
        $this->assertEquals("b99d880ec681799c4163636f756e74", $this->hasher->ByHasherName("Twox64Concat", "0x" . bin2hex("Account")));
        $this->assertEquals("85553bd51935f6dfad2ecd66275a1ded", $this->hasher->ByHasherName("Twox64Concat", "0xad2ecd66275a1ded"));
        $this->assertEquals("5153cb1f00942ff401000000", $this->hasher->ByHasherName("Twox64Concat", "0x01000000"));
        // Blake2_128
        $this->assertEquals("5328fa027215451bcef79a1905b063d7", $this->hasher->ByHasherName("Blake2_128", "20be52a5a80cad065651ec35fcb1a212bc669aabb52d68d8780a41e29ec9c83e"));
        // Blake2_256
        $this->assertEquals("bc024881cc9d7e3e4474fa73a769e921490f148c24f06228695051cfe793b6f0", $this->hasher->ByHasherName("Blake2_256", "20be52a5a80cad065651ec35fcb1a212bc669aabb52d68d8780a41e29ec9c83e"));
        $this->assertEquals("3e1aed8e76c3ca974e3e628e5a393b1720be", $this->hasher->ByHasherName("Blake2_128Concat", "20be"));
        // Identity
        $this->assertEquals("68656c6c6f", $this->hasher->ByHasherName("Identity", "0x68656c6c6f"));
        $this->assertEquals("686868686868", $this->hasher->ByHasherName("Identity", "hhhhhh"));

        // unknown hasher
        $this->expectException(\InvalidArgumentException::class);
        $this->hasher->ByHasherName("unknownHasher", "hasher");
    }

    /**
     * @throws \SodiumException
     */
    public function testEd25519 ()
    {
        $pair = sodium_crypto_sign_keypair();
        $publicKey = sodium_crypto_sign_publickey($pair);
        $secretKey = sodium_crypto_sign_secretkey($pair);
        $this->assertEquals(substr(sodium_bin2hex($secretKey), 64), sodium_bin2hex($publicKey));
        $pair2 = sodium_crypto_sign_seed_keypair(sodium_hex2bin(substr(sodium_bin2hex($secretKey), 0, 64)));
        $this->assertEquals(sodium_bin2hex($publicKey), sodium_bin2hex(sodium_crypto_sign_publickey($pair2)));
        // sign and verfiy
        $this->assertEquals(true, sodium_crypto_sign_verify_detached(sodium_crypto_sign_detached("1", $secretKey), "1", $publicKey));
        $this->assertEquals(true, sodium_crypto_sign_verify_detached(sodium_crypto_sign_detached("0xf12", $secretKey), "0xf12", $publicKey));
        $this->assertEquals(false, sodium_crypto_sign_verify_detached(sodium_crypto_sign_detached("fffffff", $secretKey), "fffff", $publicKey));
    }

    public function testKeyring ()
    {
        $hasher = new Hasher();
        // sr25519
        $sr = KeyPair::initKeyPair("sr25519", "0xe5be9a5092b81bca64be81d212e7f2f9eba183bb7a90954f7b76361f6edb5c0a", $hasher);
        $this->assertEquals($sr->type, "Sr25519");
        $this->assertEquals($sr->pk, "d43593c715fdd31c61141abd04a99fd6822c8558854ccde39a5684e7a56da27d");
        $this->assertEquals(true, $sr->verify($sr->sign("1234567"), "1234567"));
        $this->assertEquals(false, $sr->verify($sr->sign("0xffffffffff"), "1234567"));

        // ed25519
        $pair = sodium_crypto_sign_keypair();
        $secretKey = sodium_crypto_sign_secretkey($pair);
        $ed = KeyPair::initKeyPair("ed25519", sodium_bin2hex($secretKey), $hasher);
        $this->assertEquals($ed->type, "Ed25519");
        $this->assertEquals($ed->pk, substr(sodium_bin2hex($secretKey), 64));
        $this->assertEquals(true, sodium_crypto_sign_verify_detached(sodium_hex2bin($ed->sign("1")), "1", sodium_crypto_sign_publickey($pair)));
        $this->assertEquals(true, $ed->verify($ed->sign("123"), "123"));
        $this->assertEquals(false, $ed->verify($ed->sign("0xffffffffff"), "1234567"));

        // unknown type Ecdsa
        $this->expectException(\InvalidArgumentException::class);
        KeyPair::initKeyPair("Ecdsa", "0xe5be9a5092b81bca64be81d212e7f2f9eba183bb7a90954f7b76361f6edb5c0a", $hasher);
    }
}
