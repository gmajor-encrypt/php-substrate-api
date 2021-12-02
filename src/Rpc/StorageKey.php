<?php

namespace Rpc;

use Rpc\Hasher\Hasher;

class StorageKey
{
    public string $scaleType;

    public string $encodeKey;

    /**
     * StorageKey encode
     *
     * @param string $moduleName
     * @param string $storageName
     * @param array $metadata
     * @param array $args
     * @return StorageKey
     *
     * @throws \SodiumException
     */
    public static function encode (string $moduleName, string $storageName, array $metadata, array $args = array()): StorageKey
    {
        $storageName = ucfirst($storageName);

        $storageItem = array();

        foreach ($metadata["pallets"] as $v) {
            if (strcasecmp($v["name"], $moduleName)) {
                if (!is_null($v["storage"])) {
                    foreach ($v["storage"]["items"] as $item) {
                        if (strcasecmp($item["name"], $storageName)) {
                            $storageItem = $item;
                        }
                    }
                }
            }
        }
        if (count($storageItem) == 0) {
            throw new \InvalidArgumentException(sprintf("invalid storage prefix %s", $storageName));
        }

        $valueType = "";
        $hashers = [];

        switch ($storageItem["type"]["origin"]) {
            case "MapType":
                $valueType = $storageItem["type"]["map_type"]["value"];
                array_push($hashers, $storageItem["type"]["map_type"]["hasher"]);
                break;
            case "Map":
                $valueType = $storageItem["type"]["MapType"]["values"];
                $hashers = array_merge($hashers, $storageItem["type"]["MapType"]["hashers"]);
                break;
            case "DoubleMapType":
                $valueType = $storageItem["type"]["DoubleMapType"]["value"];
                $hashers = array_merge($hashers, [$storageItem["type"]["MapType"]["hashers"], $storageItem["type"]["DoubleMapType"]["key2Hasher"]]);
                break;
            case "PlainType":
                $valueType = $storageItem["type"]["plain_type"];
                array_push($hashers, "Twox64Concat");
                break;
            case "NMap":
                $valueType = $storageItem["type"]["NMap"]["value"];
                $hashers = array_merge($hashers, $storageItem["type"]["NMapType"]["hashers"]);
                break;
        }
        if (count($args) != count($hashers)) {
            throw new \InvalidArgumentException(sprintf("invalid args, expect %d, actual %d ", count($hashers), count($args)));
        }

        $hash = new Hasher();
        $encodeKey = $hash->ByHasherName("Twox128", $moduleName) . $hash->ByHasherName("Twox128", $storageName);

        foreach ($args as $index => $arg) {
            $encodeKey = $encodeKey . $hash->ByHasherName($hashers[$index], $arg);
        }

        return new StorageKey($valueType, $encodeKey);
    }

    public function __construct (string $scaleType, string $encodeKey)
    {
        $this->encodeKey = $encodeKey;
        $this->scaleType = $scaleType;

    }

}