<?php

namespace Rpc\Contract;

class ContractExecResultResult
{
    public ContractExecResultOk $Ok;
    public array $Err;

    /**
     * deserialization json to ContractExecResultResult
     *
     * @param array $j
     * @return ContractExecResultResult
     */
    public static function deserialization (array $j): ContractExecResultResult
    {
        $result = new ContractExecResultResult();
        $result->Ok = array_key_exists("Ok", $j) ? ContractExecResultOk::deserialization($j["Ok"]) : null;
        $result->Err = array_key_exists("Err", $j) ? $j["Err"] : [];
        return $result;
    }


}