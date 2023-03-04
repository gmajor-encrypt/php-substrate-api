<?php

namespace Rpc\Contract;

class ContractExecResultOk
{
    public array $flags;

    public string $data;

    /**
     * deserialization json to ContractExecResultOk
     *
     * @param array $j
     * @return ContractExecResultOk
     */
    public static function deserialization (array $j): ContractExecResultOk
    {
        $result = new ContractExecResultOk();
        $result->flags = array_key_exists("flags", $j) ? $j["flags"] : [];
        $result->data = array_key_exists("data", $j) ? $j["data"] : "";
        return $result;
    }


}
