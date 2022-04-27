<?php

namespace Rpc\KeyPair;

class ed25519 implements IKeyPair
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

    /**
     *
     * @var string
     */
    public string $keyPair;

    /**
     * @throws \SodiumException
     */
    public function __construct (string $sk)
    {
        $this->sk = $sk;
        $this->keyPair = sodium_crypto_sign_seed_keypair(sodium_hex2bin($sk));
        $this->pk = sodium_bin2hex(sodium_crypto_sign_publickey($this->keyPair));
    }

    /**
     * ed25519 sign
     *
     * @param string $msg
     * @return string
     * @throws \SodiumException
     */
    public function sign (string $msg): string
    {
        return sodium_crypto_sign_detached($msg, $this->sk);
    }

    /**
     * sr25519
     * @return string
     */
    public function type (): string
    {
        return "Ed25519";
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