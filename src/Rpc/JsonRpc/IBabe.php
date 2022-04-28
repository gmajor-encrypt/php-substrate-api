<?php

namespace Rpc\JsonRpc;
interface IBabe
{
    /**
     * Returns data about which slots (primary or secondary) can be claimed in the current epoch with the keys in the keystore
     *
     * @return array
     */
    public function epochAuthorship (): array;

}