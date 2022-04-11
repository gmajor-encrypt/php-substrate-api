<?php

namespace Rpc\KeyPair;


class KeyPair
{
    public string $pk;

    private string $sk;

    private IKeyPair $pair;

    public function __construct () {

    }

}