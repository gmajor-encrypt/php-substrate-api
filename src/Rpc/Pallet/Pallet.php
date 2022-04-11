<?php

namespace Rpc\Pallet;

use Rpc\IClient;
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
     *
     * @param Rpc $rpc
     * @param string $pallet
     */
    public function __construct (Rpc $rpc, string $pallet)
    {
        $this->rpc = $rpc;
        $this->pallet = $pallet;
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
        // todo
        // build extrinsic
        // sign
        $signature = "";
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
}
