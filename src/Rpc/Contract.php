<?php

namespace Rpc;

use Codec\Types\ScaleInstance;

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
     * @param Tx $tx
     */
    public function __construct (Tx $tx)
    {
        $this->codec = $tx->codec;
        $this->metadata = $tx->metadata;
        $this->tx = $tx;
    }


}
