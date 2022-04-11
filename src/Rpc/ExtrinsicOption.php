<?php

namespace Rpc;

class ExtrinsicOption
{

    /**
     * extra CheckEra
     *
     * @var array
     */
    public array $Era;

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

}