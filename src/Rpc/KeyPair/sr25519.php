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
     * Hasher instance
     *
     * @var Hasher
     */
    public Hasher $hasher;

    /**
     *
     * @var keyPair
     */
    public keyPair $keyPair;

    public function __construct (string $sk, Hasher $hasher)
    {
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
     * sr25519 type name
     *
     * @return string
     */
    public function type (): string
    {
        return "Sr25519";
    }

    /**
     * public key
     *
     * @return string
     */
    public function pk (): string
    {
        return $this->pk;
    }

    /**
     * verify signed msg
     *
     * @param string $signature
     * @param string $msg
     * @return bool
     */
    public function verify (string $signature, string $msg): bool
    {
        return $this->hasher->sr->VerifySign($this->keyPair, $msg, $signature);
    }
}