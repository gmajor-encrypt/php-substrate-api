<?php

namespace Rpc\JsonRpc;

use phpDocumentor\Reflection\Utils;
use Rpc\Util;

class Chain extends Base implements IChain
{

    /**
     * Get hash of the last finalized block in the canon chain
     *
     * @return string
     */
    public function getFinalizedHead (): string
    {
        $res = $this->client->read("chain_getFinalizedHead");
        return $res["result"];
    }

    /**
     * Get header and body of a relay chain block
     *
     * @param string $hash
     * @return array
     */
    public function getBlock (string $hash = ""): array
    {
        $res = $this->client->read("chain_getBlock", [$hash]);
        return $res["result"];
    }

    /**
     * Retrieves the header for a specific block
     *
     * @param string $hash
     * @return array
     */
    public function getHeader (string $hash = ""): array
    {
        $res = $this->client->read("chain_getHeader", [$hash]);
        return $res["result"];
    }

    /**
     * Retrieves the newest header via subscription
     *
     * @return array
     */
    public function subscribeAllHeads (): array
    {
        $res = $this->client->read("chain_subscribeAllHeads");
        return $res["result"];
    }

    /**
     * Retrieves the best finalized header via subscription
     *
     * @return array
     */
    public function subscribeFinalizedHeads (): array
    {
        $res = $this->client->read("chain_subscribeFinalizedHeads");
        return $res["result"];
    }

    /**
     * Retrieves the best header via subscription
     *
     * @return array
     */
    public function subscribeNewHeads (): array
    {
        $res = $this->client->read("chain_subscribeNewHeads");
        return $res["result"];
    }

    /**
     * Get the block hash for a specific block
     *
     * @param int $blockNum
     * @return string
     */
    public function getBlockHash (int $blockNum): string
    {

        $res = $this->client->read("chain_getBlockHash", [Util::addHex(dechex($blockNum))]);
        return $res["result"];
    }
}