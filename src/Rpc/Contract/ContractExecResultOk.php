<?php

namespace Rpc\Contract;

use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;

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
        $result->flags = $j["flags"];
        $result->data = array_key_exists("data", $j) ? $j["data"] : [];
        return $result;
    }


}
