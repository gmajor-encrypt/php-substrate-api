<?php

namespace Rpc\JsonRpc;
interface IAuthor
{

    /**
     * Returns true if the keystore has private keys for the given public key and key type.
     *
     * @param string $publicKey
     * @param string $keyType
     * @return bool
     */
    public function hasKey (string $publicKey, string $keyType): bool;

    /**
     * Returns true if the keystore has private keys for the given session public keys.
     *
     * @param string $sessionKeys
     * @return bool
     */
    public function hasSessionKeys (string $sessionKeys): bool;

    /**
     * Insert a key into the keystore.
     *
     * @param string $keyType
     * @param string $suri
     * @param string $publicKey
     * @return string
     */
    public function insertKey (string $keyType, string $suri, string $publicKey): string;

    /**
     * Returns all pending extrinsics, potentially grouped by sender
     *
     * @return array
     */
    public function pendingExtrinsics (): array;

    /**
     * Remove given extrinsic from the pool and temporarily ban it to prevent reimporting
     *
     * @param array $bytesOrHash
     * @return array
     */
    public function removeExtrinsic (array $bytesOrHash): array;


    /**
     * Generate new session keys and returns the corresponding public keys
     *
     * @return string
     */
    public function rotateKeys (): string;


    /**
     * Submit and subscribe to watch an extrinsic until unsubscribed
     *
     * @param string $extrinsic
     * @return array
     */
    public function submitAndWatchExtrinsic (string $extrinsic): array;


    /**
     * Submit a fully formatted extrinsic for block inclusion
     *
     * @param string $extrinsic
     * @return string
     */
    public function submitExtrinsic (string $extrinsic): string;
}