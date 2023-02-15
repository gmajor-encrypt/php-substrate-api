<?php

namespace Rpc\Contract\Abi;

class ContractMetadataV0
{

    public string $metadataVersion;

    public array $types;

    public array $spec;


    /**
     * abi json to v0 metadata instance
     *
     * @param array $j
     * @return ContractMetadataV0
     */
    public static function to_obj (array $j): ContractMetadataV0
    {
        if (!array_key_exists("metadataVersion", $j)) {
            throw new \InvalidArgumentException("Invalid v0 contract metadata");
        }
        if (!array_key_exists("types", $j) or !array_key_exists("spec", $j)) {
            throw new \InvalidArgumentException("Invalid contract metadata");
        }
        $instance = new ContractMetadataV0;
        $instance->metadataVersion = $j["metadataVersion"];
        $instance->types = $j["types"];
        $instance->spec = $j["spec"];
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
            'metadataVersion' => $this->metadataVersion,
            'spec' => $this->spec,
            'types' => $this->types,
        ];
    }

    private function convertDef (array $def): array
    {
        $key = array_key_first($def);
        switch (ucfirst($key)) {
            case 'Array':
            case "BitSequence":
            case 'Primitive':
            case 'Compact':
            case 'Sequence':
            case 'Tuple':
                return $def;
            case 'Composite':
                $fields = array_map(function (array $value): array {
                    $value["typeName"] = $value["type"];
                    return $value;
                }, $def[$key]["fields"]);
                $def[$key]["fields"] = $fields;
                return $def;
            case 'Phantom':
                $def[$key] = [];
                return $def;
            case 'Variant':
                foreach ($def[$key]["variants"] as $index => $variant) {
                    $variant["index"] = $index;
                    $def[$key]["variants"][$index] = $variant;
                }
                break;
            default:
                throw new \InvalidArgumentException("Invalid abi v0 def type");
        }
        return $def;
    }

    private function convertParams (array $defs): array
    {
        $new = [];
        foreach ($defs as $k => $def) {
            $new[] = ["name" => "param$k", "type" => $def];
        }
        return $new;
    }

    public function toV1 (): ContractMetadataV1
    {
        $instance = new ContractMetadataV1;

        $spec = $this->spec;
        foreach ($spec["constructors"] as $k => $constructor) {
            $spec["constructors"][$k]["name"] = is_array($constructor["name"]) ? $constructor["name"] : [$constructor["name"]];
        }
        foreach ($spec["messages"] as $k => $message) {
            $spec["messages"][$k]["name"] = is_array($message["name"]) ? $message["name"] : [$message["name"]];
        }
        $instance->spec = $spec;

        $types = $this->types;
        foreach ($types as $k => $type) {
            $types[$k] = [
                "id" => $k + 1,
                "type" => [
                    "def" => self::convertDef($type["def"]),
                    "params" => array_key_exists("params", $type) ? self::convertParams($type["params"]) : [],
                    "docs" => [],
                    "path" => array_key_exists("path", $type) ? $type["path"] : []
                ]
            ];

        }

        $instance->types = $types;

        return $instance;
    }

}