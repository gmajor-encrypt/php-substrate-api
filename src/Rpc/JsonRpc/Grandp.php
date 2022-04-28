<?php

namespace Rpc\JsonRpc;



use Rpc\Util;

class Grandp extends Base implements IGrandpa
{

    /**
     * Prove finality for the given block number, returning the Justification for the last block in the set.
     *
     * @param int $blockNum
     * @return string
     */
    function proveFinality (int $blockNum): string
    {
        $res = $this->client->read("grandpa_proveFinality", [Util::addHex(dechex($blockNum))]);
        return $res["result"];
    }

    /**
     * Returns the state of the current best round state as well as the ongoing background rounds
     *
     * @return array
     */
    function roundState (): array
    {
        $res = $this->client->read("grandpa_roundState");
        return $res["result"];
    }

    /**
     * Subscribes to grandpa justifications
     *
     * @return array
     */
    function subscribeJustifications (): array
    {
        $res = $this->client->read("grandpa_subscribeJustifications");
        return $res["result"];
    }
}