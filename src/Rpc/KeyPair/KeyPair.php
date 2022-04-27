<?php

namespace Rpc\KeyPair;


use Rpc\Hasher\Hasher;

class KeyPair
{
    private IKeyPair $pair;

    public string $type;

    public string $pk;

    public function __construct (IKeyPair $pair)
    {
        $this->pair = $pair;
        $this->type = $pair->type();
        $this->pk = $pair->pk();
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
     * init key pair
     *
     * @param string $type t is a type, it can be ed25519 or sr25519
     * @param string $sk
     * @param Hasher $hasher
     * @return KeyPair
     * @throws \SodiumException
     */
    public static function initKeyPair (string $type, string $sk, Hasher $hasher): KeyPair
    {
        switch ($type) {
            case "ed25519":
                return new KeyPair(new ed25519($sk));
            case "sr25519":
                return new KeyPair(new sr25519($sk, $hasher));
            default:
                throw new \InvalidArgumentException("keyPair only support ed25519 or sr25519");
        }
    }

}