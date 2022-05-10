<?php

namespace Rpc\KeyPair;
/**
 * KeyPair interface
 *
 */
interface IKeyPair
{
    /**
     * sign a message
     *
     * @param string $msg
     * @return mixed
     */
    public function sign (string $msg): string;

    /**
     * return Crypto type, sr25519 or ed25519
     *
     * @return string
     */
    public function type (): string;

    /**
     * return keypair public key
     *
     * @return string
     */
    public function pk (): string;

    /**
     * verify signed msg
     *
     * @param string $signature
     * @param string $msg
     * @return bool
     */
    public function verify (string $signature, string $msg): bool;
}
