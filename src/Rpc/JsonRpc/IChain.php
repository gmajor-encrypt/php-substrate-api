<?php

namespace Rpc\JsonRpc;
interface IChain
{
    /**
     * Get the block hash for a specific block
     *
     * @param int $blockNum
     * @return string
     */
    public function getBlockHash (int $blockNum): string;

    /**
     * Get hash of the last finalized block in the canon chain
     *
     * @return string
     */
    public function getFinalizedHead (): string;


    /**
     * Get header and body of a relay chain block
     *
     * @param string $hash
     * @return array
     */
    public function getBlock (string $hash): array;


    /**
     * Retrieves the header for a specific block
     *
     * @param string $hash
     * @return array
     */
    public function getHeader (string $hash): array;


    /**
     * Retrieves the newest header via subscription
     *
     * @return array
     */
    public function subscribeAllHeads (): array;


    /**
     * Retrieves the best finalized header via subscription
     *
     * @return array
     */
    public function subscribeFinalizedHeads (): array;

    /**
     * Retrieves the best header via subscription
     *
     * @return array
     */
    public function subscribeNewHeads (): array;

}