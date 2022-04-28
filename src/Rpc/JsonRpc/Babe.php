<?php

namespace Rpc\JsonRpc;

class Babe extends base implements IBabe
{

    /**
     * Returns data about which slots (primary or secondary) can be claimed in the current epoch with the keys in the keystore
     *
     * @return array
     */
    public function epochAuthorship (): array
    {
        $res = $this->client->read("babe_epochAuthorship");
        return $res["result"];
    }
}