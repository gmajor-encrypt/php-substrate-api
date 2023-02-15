<?php

namespace Rpc\Contract\Abi;

class Convert
{

    /**
     * metadata ABI convert to Latest
     *
     * @param array $metadata
     * @return ContractMetadataV4
     */
    public static function toLatest (array $metadata): ContractMetadataV4
    {
        if (array_key_exists("metadataVersion", $metadata)) {
            return ContractMetadataV0::to_obj($metadata)->toV1()->toV2()->toV3()->toV4();
        }

        if (array_key_exists("V1", $metadata)) {
            return ContractMetadataV1::to_obj($metadata)->toV2()->toV3()->toV4();
        }
        if (array_key_exists("V2", $metadata)) {
            return ContractMetadataV2::to_obj($metadata)->toV3()->toV4();
        }
        if (array_key_exists("V3", $metadata)) {
            return ContractMetadataV3::to_obj($metadata)->toV4();
        }
        if (array_key_exists("version", $metadata) && $metadata["version"] == 4) {
            return ContractMetadataV4::to_obj($metadata);
        }
        throw new \InvalidArgumentException("Invalid ABI metadata version");
    }

}