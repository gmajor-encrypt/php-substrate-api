<?php

namespace Rpc\Pallet;

/**
 * Extrinsic option
 *
 */
class ExtrinsicOption
{

    /**
     * extra CheckEra
     *
     * @var array|string
     */
    public array|string $era;

    /**
     * extra CheckNonce Compact<u32>
     *
     * @var int
     */
    public int $nonce;

    /**
     * ChargeTransactionPayment Compact<u128>
     *
     * @var string
     */
    public string $tip;

    /**
     * additional CheckSpecVersion
     *
     * @var int
     */
    public int $specVersion;

    /**
     * additional genesisHash
     *
     * @var string
     */
    public string $genesisHash;

    /**
     * additional blockHash
     *
     * @var string
     */
    public string $blockHash;

    /**
     * additional CheckTxVersion
     *
     * @var int
     */
    public int $transactionVersion;

    public function __construct (string $genesisHash)
    {
        $this->blockHash = $genesisHash;
        $this->genesisHash = $genesisHash;
    }
}