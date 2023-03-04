<?php

namespace Rpc\Contract;

use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
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
    public array $gasConsumed;

    public array $gasRequired;

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
        $result->gasConsumed = $j["gasConsumed"];
        $result->gasRequired = $j["gasRequired"];
        $result->StorageDeposit = array_key_exists("StorageDeposit", $j) ? $j["StorageDeposit"] : [];
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


    public static function convertGasRequired (array $GasRequired): array
    {
        if (array_key_exists("proof_size", $GasRequired) && array_key_exists("ref_time", $GasRequired)) {
            return $GasRequired;
        }
        if (array_key_exists("refTime", $GasRequired) && array_key_exists("proofSize", $GasRequired)) {
            return ["proof_size" => $GasRequired["proofSize"], "ref_time" => $GasRequired["refTime"]];
        }
        return [];
    }
}

