<?php

namespace Rpc;

use Tuupola\Base58;
use Codec\Utils;
class ss58
{
    /**
     * ss58 encode
     *
     * @param string $accountId
     * @param int $addressType
     * @return string
     * @throws \SodiumException
     */
    public static function encode (string $accountId, int $addressType): string
    {
        $prefix = Utils::string2ByteArray("SS58PRE");
        if ($addressType < 0 || $addressType > 16383) {
            throw new \InvalidArgumentException("addressType  invalid");
        }
        $addressBytes = Utils::hexToBytes($accountId);

        switch (count($addressBytes)) {
            case 32:
            case 33:
                $checkSumLength = 2;
                break;
            case 1:
            case 2:
            case 4:
            case 8:
                $checkSumLength = 1;
                break;
            default:
                return "";
        }
        $addressFormatPrefix = $addressType >= 64 ? [(($addressType & 0b0000_0000_1111_1100) >> 2) | 0b0100_0000, (($addressType >> 8) | (($addressType & 0b0000_0000_0000_0011) << 6))] : [$addressType];
        $addressFormat = array_merge($addressFormatPrefix, $addressBytes);


        $checkSum = Utils::hexToBytes(sodium_bin2hex(sodium_crypto_generichash(hex2bin((Utils::bytesToHex(array_merge($prefix, $addressFormat)))), '', 64)));

        $bitcoin = new Base58(["characters" => Base58::BITCOIN]);

        return $bitcoin->encode(hex2bin(Utils::bytesToHex(array_merge($addressFormat, array_slice($checkSum, 0, $checkSumLength)))));
    }

}
