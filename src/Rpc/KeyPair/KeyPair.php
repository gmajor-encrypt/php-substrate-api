<?php

namespace Rpc\KeyPair;


use Rpc\Hasher\Hasher;

class KeyPair
{
    private IKeyPair $pair;

    /**
     * keyPair type
     *
     * @var string
     */
    public string $type;

    /**
     * public key
     *
     * @var string
     */
    public string $pk;

    protected Hasher $hasher;

    /**
     * KeyPair construct func
     *
     * @param IKeyPair $pair
     * @param Hasher $hasher
     */
    public function __construct (IKeyPair $pair, Hasher $hasher)
    {
        $this->pair = $pair;
        $this->type = $pair->type();
        $this->pk = $pair->pk();
        $this->hasher = $hasher;
    }


    /**
     * sign a msg
     *
     * @param string $msg
     * @return string
     */
    public function sign (string $msg): string
    {
        return $this->pair->sign($msg);
    }

    /**
     * get hasher instance
     *
     * @return Hasher
     */
    public function getHasher (): Hasher
    {
        return $this->hasher;
    }


    /**
     * verify a signed msg
     *
     * @param string $signature
     * @param string $msg
     * @return bool
     */
    public function verify (string $signature, string $msg): bool
    {
        return $this->pair->verify($signature, $msg);
    }

    /**
     * init key pair, will support ed25519 or sr25519
     *
     * @param string $type t is a type, it can be ed25519 or sr25519
     * @param string $sk
     * @param Hasher $hasher
     * @return KeyPair
     * @throws \SodiumException
     */
    public static function initKeyPair (string $type, string $sk, Hasher $hasher): KeyPair
    {
        return match ($type) {
            "ed25519" => new KeyPair(new ed25519($sk), $hasher),
            "sr25519" => new KeyPair(new sr25519($sk, $hasher), $hasher),
            default => throw new \InvalidArgumentException("keyPair only support ed25519 or sr25519"),
        };
    }

}