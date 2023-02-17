<?php

namespace Rpc\Contract\Abi;

use Codec\Generator;
use Codec\Types\ScaleInfo;

/**
 * Wrap by Contract Metadata V3
 */
class ContractMetadataV4 extends ContractMetadataV3
{

    /**
     * @var $registeredSiType array
     */
    protected array $registeredSiType;

    public static function to_obj (array $j): ContractMetadataV4
    {
        if ($j["version"] != 4) {
            throw new \InvalidArgumentException("Invalid contract v4 metadata");
        }
        $instance = new ContractMetadataV4;
        $instance->types = $j["types"];
        $instance->spec = $j["spec"];
        return $instance;
    }


    public function is_empty (): bool
    {
        return count($this->types) == 0 || count($this->spec) == 0;
    }


    /**
     * register_type
     * namespace is option params, prevent duplication of type name from causing type to be overwritten
     *
     * @param Generator $generator
     * @param string $namespace
     * @return void
     */
    public function register_type (Generator $generator, string $namespace = "")
    {
        // convert type label to upper
        foreach ($this->types as $index => $type) {
            $label = array_key_first($type["type"]["def"]);
            $this->types[$index]["type"]["def"][ucfirst($label)] = $type["type"]["def"][$label];
            unset($this->types[$index]["type"]["def"][$label]);
        }
        // fill path if namespace not empty
        if (!empty($namespace)) {
            foreach ($this->types as $index => $type) {
                if (array_key_exists("path", $type["type"])) {
                    if (!in_array(current($type["type"]["path"]), ["Option", "Result"])) {
                        $path = $type["type"]["path"];
                        array_unshift($path, $namespace);
                        $this->types[$index]["type"]["path"] = $path;
                    }
                } else {
                    $this->types[$index]["type"]["path"] = [];
                }
                if (array_key_exists("Variant", $type["type"]["def"])) {
                    if (!array_key_exists("fields", $type["type"]["def"]["Variant"]["variants"])) {
                        $variants = $type["type"]["def"]["Variant"]["variants"];
                        foreach ($variants as $k => $v) {
                            $variants[$k]["fields"] = [];
                        }
                        $this->types[$index]["type"]["def"]["Variant"]["variants"] = $variants;
                    }
                }
            }
        }

        $id2Portable = array();
        foreach ($this->types as $item) {
            $id2Portable[$item["id"]] = $item;
        }

        $scaleInfo = new ScaleInfo($generator);
        $scaleInfo->regPortableType($id2Portable);
        $this->registeredSiType = $scaleInfo->registeredSiType;
    }

    /**
     * getRegisteredSiType
     *
     * Get the contract metadata registry information
     *
     * @return array
     */
    public function getRegisteredSiType (): array
    {
        return $this->registeredSiType;
    }

    /**
     * getTypeNameBySiType
     *
     *
     * @param int $SiType
     * @return string
     */
    public function getTypeNameBySiType (int $SiType): string
    {
        return $this->registeredSiType[$SiType];
    }

    /**
     * get message by name
     *
     * @param string $name
     * @return array
     */
    public function message (string $name): array
    {
        if (!array_key_exists("messages", $this->spec)) {
            return [];
        }

        foreach ($this->spec["messages"] as $message) {
            if ($message["label"] == $name) {
                return $message;
            }
        }
        return [];
    }


}