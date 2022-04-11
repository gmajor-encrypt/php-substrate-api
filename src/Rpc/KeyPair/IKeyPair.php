<?php

namespace Rpc\KeyPair;

interface IKeyPair
{
    /**
     * sign a message
     *
     * @param string $msg
     * @return mixed
     */
    public function sign(string $msg): string;

}
