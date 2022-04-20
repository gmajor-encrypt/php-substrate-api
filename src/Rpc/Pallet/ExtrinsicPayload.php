<?php

namespace Rpc\Pallet;

use Rpc\KeyPair\KeyPair;

class ExtrinsicPayload
{

    /**
     * call encode raw
     *
     * @var string
     */
    public string $call;

    /**
     * Extrinsic signer
     *
     * @var string
     */
    public string $signer;

    /**
     * extra CheckEra
     *
     * @var array
     */
    public array $era;

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

    /**
     * Signed Extrinsic
     * {
     *   "account_id": "MultiAddress"
     *   "signature": "ExtrinsicSignature"
     *   "era": "EraExtrinsic",
     *   "nonce": "Compact<U64>",
     *   "tip": "Compact<Balance>"
     *   "call":"Call",
     * }
     *
     *
     * @param ExtrinsicOption $opt
     * @param string $encodeCall
     */
    public function __construct (ExtrinsicOption $opt, string $encodeCall)
    {
        $this->call = $encodeCall;
        $this->era = $opt->era;
        $this->nonce = $opt->nonce;
        $this->tip = $opt->tip;
        $this->specVersion = $opt->specVersion;
        $this->genesisHash = $opt->genesisHash;
        $this->blockHash = $opt->blockHash;
        $this->transactionVersion = $opt->transactionVersion;
    }


    /**
     * ExtrinsicPayload sign
     *
     * @param keyPair $keyPair
     * @param string $encodePayload
     * @return string
     */
    public function sign (keyPair $keyPair, string $encodePayload): string
    {
        return $keyPair->sign($encodePayload);
    }

    /**
     * Extrinsic encode
     *
     * @param $codec
     * @return string
     */
    public function encode ($codec): string
    {
        return "";
    }


}

