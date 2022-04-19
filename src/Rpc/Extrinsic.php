<?php

namespace Rpc;

class Extrinsic
{

    /**
     * pallet module name
     *
     * @var string
     */
    public string $module;

    /**
     * module method
     *
     * @var string
     */
    public string $method;

    /**
     * call param
     *
     * @var array
     */
    public array $param;

    /**
     * Extrinsic signer
     *
     * @var string
     */
    public string $signer;

    /**
     * Extrinsic nonce
     *
     * @var int
     */
    public int $nonce;


    /**
     * Extrinsic encode
     *
     * @return string
     */
    public function encode (): string
    {


    }


}

