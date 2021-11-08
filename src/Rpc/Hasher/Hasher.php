<?php

namespace Rpc\Hasher;
class Hasher
{
    public function __construct()
    {
    }

    /**
     * hash a hex string by hash name
     * @param string $hasher
     * @param string $hex
     * @return string
     * @throws \SodiumException
     */
    public static function ByHasherName(string $hasher,string $hex):string{
        switch ($hasher){
            case "Blake2_128":
                return sprintf("0x%s", sodium_bin2hex(sodium_crypto_generichash(hex2bin($hex),'',16)));
            case "Blake2_256":
                return sprintf("0x%s", sodium_bin2hex(sodium_crypto_generichash(hex2bin($hex))));
            case "Twox128":
               // https://php.watch/versions/8.1/xxHash
            case "Twox256":

            case "Twox64Concat":

            case "Identity":

            case "Blake2_128Concat":

            default:

        }
    }
}