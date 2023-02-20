<?php

namespace Rpc\Contract;

use Codec\Utils;

use InvalidArgumentException;
use Rpc\Hasher\Hasher;

/**
 * Address class
 *
 * For generate contract address
 * https://github.com/paritytech/substrate/blob/master/frame/contracts/src/address.rs#L32
 *
 *  Formula:
 * `hash("contract_addr_v1" ++ deploying_address ++ code_hash ++ input_data ++ salt)`
 */
class Address
{
    /**
     *
     * @param Hasher $hash
     * @param string $pk
     * @param string $code_hash
     * @param string $input_data
     * @param string $salt
     * @return string
     * @throws \SodiumException
     */
    public static function GenerateAddress (Hasher $hash,string $pk, string $code_hash, string $input_data, string $salt): string
    {
        $pk = Utils::trimHex($pk);
        if (strlen($pk) != 64) {
            throw new InvalidArgumentException("invalid deploy public key");
        }
        $code_hash = Utils::trimHex($code_hash);
        if (strlen($code_hash) != 64) {
            throw new InvalidArgumentException("invalid source code hash");
        }
        $data = bin2hex("contract_addr_v1") . $pk . $code_hash . Utils::trimHex($input_data) . Utils::trimHex($salt);
        return $hash->ByHasherName("Blake2_256", $data);
    }
}
