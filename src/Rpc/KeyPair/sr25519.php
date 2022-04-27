<?php

namespace Rpc\KeyPair;

use Rpc\Hasher\Hasher;
use Crypto\keyPair;

class sr25519 implements IKeyPair
{

    /**
     * public key
     *
     * @var string
     */
    public string $pk;

    /**
     * secret key
     *
     * @var string
     */
    private string $sk;

    public Hasher $hasher;

    /**
     *
     * @var keyPair
     */
    public keyPair $keyPair;

    public function __construct (string $sk, Hasher $hasher)
    {
        $this->sk = $sk;
        $this->hasher = $hasher;
        $this->keyPair = $hasher->sr->InitKeyPair($sk);
        $this->pk = $this->keyPair->publicKey;
    }

    /**
     * sr25519 sign
     *
     * @param string $msg
     * @return string
     */
    public function sign (string $msg): string
    {
        return $this->hasher->sr->Sign($this->keyPair, $msg);
    }


    /**
     * sr25519
     * @return string
     */
    public function type (): string
    {
        return "Sr25519";
    }

    /**
     * public key
     * @return string
     */
    public function pk (): string
    {
        return $this->pk;
    }
}