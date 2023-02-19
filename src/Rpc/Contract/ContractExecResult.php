<?php

namespace Rpc\Contract;

use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use GMP;

/**
 *
 *  Contract Exec Result type
 *   "ContractExecResult": {
 * "gasConsumed": "Weight",
 * "gasRequired": "Weight",
 * "StorageDeposit": "StorageDeposit",
 * "debugMessage": "Text",
 * "result": "ContractExecResultResult"
 * }
 */
class ContractExecResult
{
    public GMP $gasConsumed;

    public GMP $gasRequired;

    public array $StorageDeposit;

    public string $debugMessage;

    public ContractExecResultResult $result;

    /**
     * deserialization json to ContractExecResult
     *
     * @param array $j
     * @return ContractExecResult
     */
    public static function deserialization (array $j): ContractExecResult
    {
        $result = new ContractExecResult();
        $result->gasConsumed = is_string($j["gasConsumed"]) ? gmp_init($j["gasConsumed"]) : $j["gasConsumed"];
        $result->gasRequired = is_string($j["gasRequired"]) ? gmp_init($j["gasRequired"]) : $j["gasRequired"];
        $result->StorageDeposit = $j["StorageDeposit"];
        $result->debugMessage = $j["debugMessage"];
        $result->result = ContractExecResultResult::deserialization($j["result"]);

        return $result;
    }


    /**
     * decode scale raw to human readable
     *
     * @param ScaleInstance $codec
     * @param string $type
     * @return mixed
     */
    public function decodeResult (ScaleInstance $codec, string $type): mixed
    {
        return $codec->process($type, new ScaleBytes($this->result->Ok->data));
    }

}

