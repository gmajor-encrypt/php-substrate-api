<?php

namespace Rpc\Contract\Abi;

/**
 * Wrap by Contract Metadata V3
 */
class ContractMetadataV4 extends ContractMetadataV3
{

    public static function to_obj (array $j): ContractMetadataV4
    {
        if (!array_key_exists("types", $j) or !array_key_exists("spec", $j)) {
            throw new \InvalidArgumentException("Invalid contract v4 metadata");
        }
        $instance = new ContractMetadataV4;
        $instance->types = $j["types"];
        $instance->spec = $j["spec"];
        return $instance;
    }

}