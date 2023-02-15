<?php

namespace Rpc\Contract\Abi;

class ContractMetadataV1
{
    public array $types;

    public array $spec;

    public static function to_obj (array $j): ContractMetadataV1
    {
        if (!array_key_exists("V1", $j)) {
            throw new \InvalidArgumentException("Invalid contract v1 metadata");
        }
        $instance = new ContractMetadataV1;
        $instance->types = $j["V1"]["types"];
        $instance->spec = $j["V1"]["spec"];
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


    public function toV2 (): ContractMetadataV2
    {
        $instance = new ContractMetadataV2;
        $spec = $this->spec;
        foreach ($spec["constructors"] as $k => $constructor) {
            $spec["constructors"][$k]["label"] = is_array($constructor["name"]) ? join("::", $constructor["name"]) : [$constructor["name"]];
        }
        foreach ($spec["events"] as $k => $event) {
            $spec["events"][$k]["label"] = is_array($event["name"]) ? join("::", $event["name"]) : [$event["name"]];
        }
        foreach ($spec["messages"] as $k => $message) {
            $spec["messages"][$k]["label"] = is_array($message["name"]) ? join("::", $message["name"]) : [$message["name"]];
        }
        $instance->spec = $spec;

        $instance->types = $this->types;
        return $instance;
    }


}