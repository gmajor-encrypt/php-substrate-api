<?php

namespace Rpc;

use Codec\Types\ScaleInstance;
use Codec\Utils;

class Contract
{

    /**
     * runtime metadata, init after Rpc instance init
     *
     * @var array
     */
    public array $metadata;

    /**
     * scale code instance
     *
     * @var ScaleInstance
     */
    public ScaleInstance $codec;

    /**
     * Tx send transaction instance
     *
     * @param Rpc $rpc
     */

    public Tx $tx;


    /**
     * Contract
     * construct
     *
     * @param Tx $tx
     */
    public function __construct (Tx $tx)
    {
        $this->codec = $tx->codec;
        $this->metadata = $tx->metadata;
        $this->tx = $tx;
    }


    /**
     * deploy new contract
     *
     * @param string $code
     * @param string $data
     * @param array $option set gasLimit storageDepositLimit
     * @return string
     */

    // https://github.com/paritytech/substrate/blob/0ce39208841e519920b57d3ba5a3962188c4c66c/frame/contracts/src/lib.rs#L187
    public function new (string $code, string $data, array $option = []): string
    {
        $code = Utils::trimHex($code);
        $data = Utils::trimHex($data);
        $gasLimit = array_key_exists("gasLimit", $option) ? $option["gasLimit"] : "50000000000";
        $storageDepositLimit = array_key_exists("storageDepositLimit", $option) ? $option["storageDepositLimit"] : 0;
        $salt = Utils::hexToBytes("01");
        // Contracts.Instantiate_with_code(value,gas_limit,storage_deposit_limit,code,data,salt)
        return $this->tx->Contracts->instantiate_with_code(0, ["proof_size"=>0,"ref_time"=>$gasLimit], null, Utils::hexToBytes($code), Utils::hexToBytes($data), $salt);
    }

    /**
     * @param string $call
     * @param array $attributes
     *
     * @return void
     * @throws \InvalidArgumentException|\SodiumException
     */
    public function __call (string $call, array $attributes)
    {
        // state_call
        var_dump($call);
        var_dump($attributes);
    }
}

