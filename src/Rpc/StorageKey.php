<?php

namespace Rpc;

use Rpc\Hasher\Hasher;

class StorageKey
{

    /**
     * $scaleType
     * storage value type, decode raw value this scaleType
     *
     * @var string
     */
    public string $scaleType;

    /**
     * storage key
     * encoded key details
     *
     * @var string
     */

    public string $encodeKey;

    public function __construct (string $scaleType, string $encodeKey)
    {
        $this->encodeKey = $encodeKey;
        $this->scaleType = $scaleType;
    }

    /**
     *  StorageKey encode
     *  When you use the Substrate RPC to access a storage item, you only need to provide the key associated with that item
     *  ui https://polkadot.js.org/apps/#/chainstate
     *
     *  https://docs.substrate.io/v3/advanced/storage/#storage-value-keys
     *
     *
     * https://docs.substrate.io/v3/advanced/storage/#querying-storage
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
        // checkout storage item
        foreach ($metadata["pallets"] as $v) {
            if (!strcasecmp($v["name"], $moduleName)) {
                if (!is_null($v["storage"])) {
                    foreach ($v["storage"]["items"] as $item) {
                        if (!strcasecmp($item["name"], $storageName)) {
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
            // Storage map keys v13
            case "MapType":
                $valueType = $storageItem["type"]["map_type"]["value"];
                $hashers[] = $storageItem["type"]["map_type"]["hasher"];
                break;
            // v14 map storage
            case "Map":
                $valueType = $storageItem["type"]["MapType"]["values"];
                $hashers = array_merge($hashers, $storageItem["type"]["MapType"]["hashers"]);
                break;
            // v14 map storage
            case "DoubleMapType":
                $valueType = $storageItem["type"]["DoubleMapType"]["value"];
                $hashers = array_merge($hashers, [$storageItem["type"]["MapType"]["hashers"], $storageItem["type"]["DoubleMapType"]["key2Hasher"]]);
                break;
            // PlainType
            case "PlainType":
                $valueType = $storageItem["type"]["plain_type"];
                break;
            // Storage map keys v13
            case "NMap":
                $valueType = $storageItem["type"]["NMap"]["value"];
                $hashers = array_merge($hashers, $storageItem["type"]["NMapType"]["hashers"]);
                break;
        }
        if (count($args) != count($hashers)) {
            throw new \InvalidArgumentException(sprintf("invalid args, expect %d, actual %d ", count($hashers), count($args)));
        }

        $hash = new Hasher();

        // To calculate the key for a simple Storage Value, take the TwoX 128 hash of the name of the pallet
        //  that contains the Storage Value and append to it the TwoX 128 hash of the name of the Storage Value itself
        $encodeKey = $hash->ByHasherName("Twox128", ucfirst($moduleName)) . $hash->ByHasherName("Twox128", $storageName);

        foreach ($args as $index => $arg) {
            $encodeKey = $encodeKey . $hash->ByHasherName($hashers[$index], $arg);
        }

        return new StorageKey($valueType, $encodeKey);
    }


}