<?php

namespace Rpc;

use Codec\Types\ScaleInstance;
use Rpc\KeyPair\KeyPair;
use Rpc\Pallet\Pallet;

/**
 * Tx package
 * this package used by send transaction
 */
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


    /**
     * For transaction option, it can be set tips or Era
     * default era is immortal, tip is 0
     *
     * @var array
     */
    protected array $options = [
        "tip" => 0,
        "era" => "00"
    ];

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
        return new Pallet($this->rpc, $pallet, $this->keyPair, $this->options);
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

    /**
     * set tx with opt, return tx instance
     * option support tip and era
     * Tips are an optional transaction fee that users can add
     *
     * @param array $opt
     * @return Tx
     */
    public function withOpt (array $opt): Tx
    {
        if (array_key_exists("tip", $opt)) {
            $this->options["tip"] = $opt["tip"];
        }
        if (array_key_exists("era", $opt)) {
            $this->options["era"] = $opt["era"];
        }
        return $this;
    }
}
