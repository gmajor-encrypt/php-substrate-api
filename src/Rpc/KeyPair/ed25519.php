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
     * ed25519 keypair
     *
     * @var string
     */
    public string $keyPair;

    /**
     * @throws \SodiumException
     */
    public function __construct (string $secretKey)
    {
        $this->sk = $secretKey;
        $this->keyPair = sodium_crypto_sign_seed_keypair(sodium_hex2bin(substr($secretKey, 0, 64)));
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
        return sodium_bin2hex(sodium_crypto_sign_detached($msg, sodium_hex2bin($this->sk)));
    }

    /**
     * return Ed25519 type
     *
     * @return string
     */
    public function type (): string
    {
        return "Ed25519";
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
     * @throws \SodiumException
     */
    public function verify (string $signature, string $msg): bool
    {
        return sodium_crypto_sign_verify_detached(sodium_hex2bin($signature), $msg, sodium_hex2bin($this->pk));
    }
}