<?php

namespace Rpc\Contract\Abi;

class ContractMetadataV3
{

    public array $types;

    public array $spec;


    public static function to_obj (array $j): ContractMetadataV3
    {
        if (!array_key_exists("types", $j) or !array_key_exists("spec", $j)) {
            throw new \InvalidArgumentException("Invalid contract v3 metadata");
        }
        $instance = new ContractMetadataV3;
        $instance->types = $j["types"];
        $instance->spec = $j["spec"];
        return $instance;
    }


    /**
     * To json array
     * @return array
     */
    public function as_json (): array
    {
        return [
            'spec' => $this->spec,
            'types' => $this->types,
        ];
    }

    /**
     * to v3 metadata
     *
     * @return ContractMetadataV4
     */
    public function toV4 (): ContractMetadataV4
    {
        $instance = new ContractMetadataV4;
        $instance->types = $this->types;
        $instance->spec = $this->spec;
        return $instance;
    }

}