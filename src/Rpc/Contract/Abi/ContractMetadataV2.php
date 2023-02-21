<?php

namespace Rpc\Contract\Abi;

class ContractMetadataV2
{

    public array $types;

    public array $spec;


    public static function to_obj (array $j): ContractMetadataV2
    {
        if (!array_key_exists("V2", $j)) {
            throw new \InvalidArgumentException("Invalid contract v2 metadata");
        }
        $instance = new ContractMetadataV2;
        $instance->types = $j["V2"]["types"];
        $instance->spec = $j["V2"]["spec"];
        return $instance;
    }


    /**
     * To json array
     *
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
     * @return ContractMetadataV3
     */
    public function toV3 (): ContractMetadataV3
    {
        $instance = new ContractMetadataV3;
        $spec = $this->spec;
        foreach ($spec["constructors"] as $k => $constructor) {
            $spec["constructors"][$k]["payable"] = true;
        }
        $instance->spec = $spec;

        $instance->types = $this->types;
        return $instance;
    }

}