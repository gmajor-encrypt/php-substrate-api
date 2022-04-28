<?php

namespace Rpc\JsonRpc;

class System extends base implements ISystem
{


    /**
     * Retrieves the next accountIndex as available on the node
     *
     * @param string $accountId
     * @return int
     */
    public function accountNextIndex (string $accountId): int
    {
        $res = $this->client->read("system_accountNextIndex", [$accountId]);
        return $res["result"];
    }

    /**
     * Adds the supplied directives to the current log filter
     *
     * @param string $directives
     * @return void
     */
    public function addLogFilter (string $directives): void
    {
        $this->client->read("system_addLogFilter", [$directives]);
    }

    /**
     * Adds a reserved peer
     *
     * @param string $peer
     * @return string
     */
    public function addReservedPeer (string $peer): string
    {
        $res = $this->client->read("system_addReservedPeer", [$peer]);
        return $res["result"];
    }

    /**
     * Retrieves the chain
     *
     * @return string
     */
    public function chain (): string
    {
        $res = $this->client->read("system_chain");
        return $res["result"];
    }

    /**
     * Retrieves the chain type
     *
     * @return string
     */
    public function chainType (): string
    {
        $res = $this->client->read("system_chainType");
        return $res["result"];
    }

    /**
     * Dry run an extrinsic at a given block
     *
     * @param string $extrinsic
     * @param string $at
     * @return array
     */
    public function dryRun (string $extrinsic, string $at = ""): array
    {
        $res = $this->client->read("system_dryRun", [$extrinsic, $at]);
        return $res["result"];
    }

    /**
     * Return health status of the node
     *
     * @return array
     */
    public function health (): array
    {
        $res = $this->client->read("system_health");
        return $res["result"];
    }

    /**
     * The addresses include a trailing /p2p/ with the local PeerId,
     * and are thus suitable to be passed to addReservedPeer or as a bootnode address for example
     *
     * @return array
     */
    public function localListenAddresses (): array
    {
        $res = $this->client->read("system_localListenAddresses");
        return $res["result"];
    }

    /**
     * Returns the base58-encoded PeerId of the node
     *
     * @return string
     */
    public function localPeerId (): string
    {
        $res = $this->client->read("system_localPeerId");
        return $res["result"];
    }

    /**
     * Retrieves the node name
     *
     * @return string
     */
    public function name (): string
    {
        $res = $this->client->read("system_name");
        return $res["result"];
    }

    /**
     * Returns current state of the network
     *
     * @return array
     */
    public function networkState (): array
    {
        $res = $this->client->read("system_networkState");
        return $res["result"];
    }

    /**
     * Returns the roles the node is running as
     *
     * @return array
     */
    public function nodeRoles (): array
    {
        $res = $this->client->read("system_nodeRoles");
        return $res["result"];
    }

    /**
     * Returns the currently connected peers
     *
     * @return array
     */
    public function peers (): array
    {
        $res = $this->client->read("system_peers");
        return $res["result"];
    }

    /**
     * Get a custom set of properties as a JSON object, defined in the chain spec
     *
     * @return array
     */
    public function properties (): array
    {
        $res = $this->client->read("system_properties");
        return $res["result"];
    }

    /**
     * Remove a reserved peer
     *
     * @return string
     */
    public function removeReservedPeer (): string
    {
        $res = $this->client->read("system_removeReservedPeer");
        return $res["result"];
    }

    /**
     * Returns the list of reserved peers
     *
     * @return string
     */
    public function reservedPeers (): string
    {
        $res = $this->client->read("system_reservedPeers");
        return $res["result"];
    }

    /**
     * Resets the log filter to Substrate defaults
     *
     * @return void
     */
    public function resetLogFilter (): void
    {
        $this->client->read("system_resetLogFilter");
    }

    /**
     * Returns the state of the syncing of the node
     *
     * @return array
     */
    public function syncState (): array
    {
        $res = $this->client->read("system_syncState");
        return $res["result"];
    }

    /**
     *  Retrieves the version of the node
     *
     * @return string
     */
    public function version (): string
    {
        $res = $this->client->read("system_version");
        return $res["result"];
    }
}