<?php

namespace Rpc\JsonRpc;

interface IGrandpa
{
    /**
     * Prove finality for the given block number, returning the Justification for the last block in the set.
     *
     * @param int $blockNum
     * @return string
     */
    function proveFinality (int $blockNum): string;


    /**
     * Returns the state of the current best round state as well as the ongoing background rounds
     *
     * @return array
     */
    function roundState (): array;

    /**
     * Subscribes to grandpa justifications
     *
     * @return array
     */
    function subscribeJustifications (): array;
}