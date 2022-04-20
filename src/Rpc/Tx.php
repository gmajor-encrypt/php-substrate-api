<?php

namespace Rpc;

use Codec\Types\ScaleInstance;
use Rpc\KeyPair\KeyPair;
use Rpc\Pallet\Pallet;

class Tx
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
     * tx signer KeyPair
     *
     * @var KeyPair
     */
    private KeyPair $keyPair;

    /**
     * Tx send transaction instance
     *
     * @param Rpc $rpc
     */

    public Rpc $rpc;


    public function __construct (Rpc $rpc)
    {
        $this->codec = $rpc->codec;
        $this->metadata = $rpc->metadata;
        $this->rpc = $rpc;
    }


    /**
     * magic function, will call Pallet method
     *
     * @param string $pallet
     * @return Pallet
     */
    public function __get (string $pallet)
    {
        if (!isset($this->keyPair)) {
            throw new \InvalidArgumentException("singer keypair not set");
        }
        return new Pallet($this->rpc, $pallet, $this->keyPair);
    }


    /**
     * set tx signer
     *
     * @return void
     */
    public function setKeyPair (keyPair $keyPair)
    {
        $this->keyPair = $keyPair;
    }
}
