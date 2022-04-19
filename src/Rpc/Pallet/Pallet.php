<?php

namespace Rpc\Pallet;

use Rpc\Extrinsic;
use Rpc\ExtrinsicOption;
use Rpc\KeyPair\KeyPair;
use Rpc\Rpc;

class Pallet
{

    /**
     * rpc client inject
     *
     * @var Rpc
     */
    public Rpc $rpc;

    /**
     * pallet name;
     *
     * @var string
     */
    public string $pallet;


    /**
     * tx signer KeyPair
     *
     * @var KeyPair
     */
    private KeyPair $keyPair;

    /**
     *
     * @param Rpc $rpc
     * @param string $pallet
     * @param KeyPair $keyPair
     */
    public function __construct (Rpc $rpc, string $pallet, keyPair $keyPair)
    {
        $this->rpc = $rpc;
        $this->pallet = $pallet;
        $this->keyPair = $keyPair;
    }

    /**
     * @param string $call
     * @param array $attributes
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __call (string $call, array $attributes)
    {
        // build extrinsic todo
        // sign
        $signature = $this->sign();
        return $this->submitAndWatchExtrinsic($signature);
    }


    /**
     * submitAndWatchExtrinsic
     * send signed Extrinsic
     *
     * @param string $signature
     * @return mixed
     */
    public function submitAndWatchExtrinsic (string $signature): mixed
    {
        return $this->rpc->author->submitAndWatchExtrinsic($signature);
    }


    /**
     * sign Extrinsic
     * support ed25519 or sr25519
     *
     * return signature
     *
     * @param Extrinsic $extrinsic
     * @param ExtrinsicOption $option
     * @return string
     */
    public function sign (Extrinsic $extrinsic, ExtrinsicOption $option): string
    {
        $encodeExtrinsic = $extrinsic->encode();
        $msg = "";
        return $this->keyPair->sign($msg);
    }
}
