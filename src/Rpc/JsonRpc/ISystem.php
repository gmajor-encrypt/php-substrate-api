<?php

namespace Rpc\JsonRpc;
interface ISystem
{
    /**
     * Retrieves the next accountIndex as available on the node
     *
     * @param string $accountId
     * @return int
     */
    public function accountNextIndex (string $accountId): int;

    /**
     * Adds the supplied directives to the current log filter
     *
     * @param string $directives
     * @return void
     */
    public function addLogFilter (string $directives): void;


    /**
     * Adds a reserved peer
     *
     * @param string $peer
     * @return string
     */
    public function addReservedPeer (string $peer): string;

    /**
     * Retrieves the chain
     *
     * @return string
     */
    public function chain (): string;

    /**
     * Retrieves the chain type
     *
     * @return string
     */
    public function chainType (): string;

    /**
     * Dry run an extrinsic at a given block
     *
     * @param string $extrinsic
     * @param string $at
     * @return array
     */
    public function dryRun (string $extrinsic, string $at = ""): array;

    /**
     * Return health status of the node
     *
     * @return array
     */
    public function health (): array;

    /**
     * The addresses include a trailing /p2p/ with the local PeerId,
     * and are thus suitable to be passed to addReservedPeer or as a bootnode address for example
     *
     * @return array
     */
    public function localListenAddresses (): array;

    /**
     * Returns the base58-encoded PeerId of the node
     *
     * @return string
     */
    public function localPeerId (): string;

    /**
     * Retrieves the node name
     *
     * @return string
     */
    public function name (): string;

    /**
     * Returns current state of the network
     * @return array
     */
    public function networkState (): array;

    /**
     * Returns the roles the node is running as
     * @return array
     */
    public function nodeRoles (): array;

    /**
     * Returns the currently connected peers
     * @return array
     */
    public function peers (): array;

    /**
     * Get a custom set of properties as a JSON object, defined in the chain spec
     * @return array
     */
    public function properties (): array;

    /**
     * Remove a reserved peer
     * @return string
     */
    public function removeReservedPeer (): string;

    /**
     * Returns the list of reserved peers
     * @return string
     */
    public function reservedPeers (): string;

    /**
     * Resets the log filter to Substrate defaults
     * @return void
     */
    public function resetLogFilter (): void;

    /**
     * Returns the state of the syncing of the node
     * @return array
     */
    public function syncState (): array;

    /**
     *  Retrieves the version of the node
     * @return string
     */
    public function version (): string;

}