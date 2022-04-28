<?php

namespace Rpc\JsonRpc;

class Author extends Base implements IAuthor
{

    /**
     * Returns true if the keystore has private keys for the given public key and key type.
     *
     * @param string $publicKey
     * @param string $keyType
     * @return bool
     */
    public function hasKey (string $publicKey, string $keyType): bool
    {
        $res = $this->client->read("author_hasKey", [$publicKey, $keyType]);
        return $res["result"];
    }

    /**
     * Returns true if the keystore has private keys for the given session public keys.
     *
     * @param string $sessionKeys
     * @return bool
     */
    public function hasSessionKeys (string $sessionKeys): bool
    {
        $res = $this->client->read("author_hasSessionKeys", [$sessionKeys]);
        return $res["result"];
    }

    /**
     * Insert a key into the keystore.
     *
     * @param string $keyType
     * @param string $suri
     * @param string $publicKey
     * @return string
     */
    public function insertKey (string $keyType, string $suri, string $publicKey): string
    {
        $res = $this->client->read("author_insertKey", [$keyType, $suri, $publicKey]);
        return $res["result"];
    }

    /**
     * Returns all pending extrinsics, potentially grouped by sender
     *
     * @return array
     */
    public function pendingExtrinsics (): array
    {
        $res = $this->client->read("author_pendingExtrinsics");
        return $res["result"];
    }

    /**
     * Remove given extrinsic from the pool and temporarily ban it to prevent reimporting
     *
     * @param array $bytesOrHash
     * @return array
     */
    public function removeExtrinsic (array $bytesOrHash): array
    {
        $res = $this->client->read("author_removeExtrinsic", [$bytesOrHash]);
        return $res["result"];
    }

    /**
     * Generate new session keys and returns the corresponding public keys
     *
     * @return string
     */
    public function rotateKeys (): string
    {
        $res = $this->client->read("author_rotateKeys");
        return $res["result"];
    }

    /**
     * Submit and subscribe to watch an extrinsic until unsubscribed
     *
     * @param string $extrinsic
     * @return array
     */
    public function submitAndWatchExtrinsic (string $extrinsic): array
    {
        $res = $this->client->read("author_submitAndWatchExtrinsic", [$extrinsic]);
        return $res["result"];
    }

    /**
     * Submit a fully formatted extrinsic for block inclusion
     *
     * @param string $extrinsic
     * @return string
     */
    public function submitExtrinsic (string $extrinsic): string
    {
        $res = $this->client->read("author_submitExtrinsic", [$extrinsic]);
        return $res["result"];
    }
}