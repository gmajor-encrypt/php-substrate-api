<?php

namespace Rpc\Substrate;

class Method
{
    /** account start **/
    // Retrieves the next accountIndex as available on the node
    const ACCOUNT_NEXT_INDEX = 'account_nextIndex';
    /** account end **/

    /** author start **/
    // Returns true if the keystore has private keys for the given public key and key type.
    const AUTHOR_HAS_KEY = 'author_hasKey';

    // Returns true if the keystore has private keys for the given session public keys
    const AUTHOR_HAS_SESSION_KEYS = 'author_hasSessionKeys';

    // Insert a key into the keystore.
    const AUTHOR_INSERT_KEY = 'author_insertKey';

    // Returns all pending extrinsics, potentially grouped by sender
    const AUTHOR_PENDING_EXTRINSICS = 'author_pendingExtrinsics';

    // Remove given extrinsic from the pool and temporarily ban it to prevent reimporting
    const AUTHOR_REMOVE_EXTRINSICS = 'author_removeExtrinsic';

    // Generate new session keys and returns the corresponding public keys
    const AUTHOR_ROTATE_KEYS = 'author_rotateKeys';

    // Submit and subscribe to watch an extrinsic until unsubscribed
    const AUTHOR_SUBMIT_AND_WATCH_EXTRINSIC = 'author_submitAndWatchExtrinsic';

    // Submit a fully formatted extrinsic for block inclusion
    const AUTHOR_SUBMIT_EXTRINSIC = 'author_submitExtrinsic';
    /** author end **/

    /** babe start **/
    // Returns data about which slots (primary or secondary) can be claimed in the current epoch with the keys in the keystore
    const BABE_EPOCH_AUTHORSHIP = 'babe_epochAuthorship';
    /** babe end **/

    /** chain start **/
    // Get header and body of a relay chain block
    const CHAIN_GET_BLOCK = 'chain_getBlock';

    // Get the block hash for a specific block
    const GET_BLOCK_HASH = 'getBlockHash';

    // Get hash of the last finalized block in the canon chain
    const CHAIN_GET_FINALIZED_HEAD = 'chain_getFinalizedHead';

    // Retrieves the header for a specific block
    const CHAIN_GET_HEADER = 'chain_getHeader';

    // Retrieves the newest header via subscription
    const CHAIN_SUBSCRIBE_ALL_HEADS = 'chain_subscribeAllHeads';

    // Retrieves the best finalized header via subscription
    const CHAIN_SUBSCRIBE_FINALIZED_HEADS = 'chain_subscribeFinalizedHeads';

    // Retrieves the best header via subscription
    const CHAIN_SUBSCRIBE_NEW_HEADS = 'chain_subscribeNewHeads';
    /** chain end **/

    /** TODO childstate,contracts,engine */

    /** offchain start */
    // Get offchain local storage under given key and prefix
    const OFFCHAIN_LOCALSTORAGE_GET = 'offchain_localStorageGet';

    // Set offchain local storage under given key and prefix
    const  OFFCHAIN_LOCALSTORAGE_SET = 'offchain_localStorageSet';
    /** offchain end */

    /** state start */
    // Perform a call to a builtin on the chain
    const STATE_CALL = 'state_call';

    // Retrieves the keys with prefix of a specific child storage
    const STATE_GET_CHILD_KEYS = 'state_getChildKeys';

    // Retrieves the child storage for a key
    const STATE_GET_CHILD_STORAGE = 'state_getChildStorage';

    // Retrieves the child storage hash
    const STATE_GET_CHILD_STORAGE_HASH = 'state_getChildStorageHash';

    // Retrieves the child storage size
    const STATE_GET_CHILD_STORAGE_SIZE = 'state_getChildStorageSize';

    // Retrieves the keys with a certain prefix
    const STATE_GET_KEYS = 'state_getKeys';

    // Returns the keys with prefix with pagination support.
    const STATE_GET_KEYS_PAGED = 'state_getKeysPaged';

    // Returns the runtime metadata
    const STATE_GET_METADATA = 'state_getMetadata';

    // Returns the runtime metadata
    const STATE_GET_PAIRS = 'state_getPairs';

    // Returns the keys with prefix, leave empty to get all the keys
    const STATE_GET_RUNTIME_VERSION = 'state_getRuntimeVersion';

    // Retrieves the storage for a key
    const STATE_GET_STORAGE = 'state_getStorage';

    // Retrieves the storage hash
    const STATE_GET_STORAGE_HASH = 'state_getStorageHash';

    // Retrieves the storage size
    const STATE_GET_STORAGE_SIZE = 'state_getStorageSize';

    // Query historical storage entries (by key) starting from a start block
    const STATE_QUERY_STORAGE = 'state_queryStorage';

    // Query storage entries (by key) starting at block hash given as the second parameter
    const STATE_QUERY_STORAGE_AT = 'state_queryStorageAt';

    // Retrieves the runtime version via subscription
    const STATE_SUBSCRIBE_RUNTIME_VERSION = 'state_subscribeRuntimeVersion';

    // Subscribes to storage changes for the provided keys
    const STATE_SUBSCRIBE_STORAGE = 'state_subscribeStorage';
    /** state end */

    /** system start */

    // Adds a reserved peer
    const SYSTEM_ADD_RESERVED_PEER = 'system_addReservedPeer';

    // Retrieves the chain
    const SYSTEM_CHAIN = 'system_chain';

    // Retrieves the chain type
    const SYSTEM_CHAIN_TYPE = 'system_chainType';

    // Return health status of the node
    const SYSTEM_HEALTH = 'system_health';

    // Retrieves the node name
    const SYSTEM_NAME = 'system_name';

    // Returns current state of the network
    const SYSTEM_NETWORK_STATE = 'system_networkState';

    // Returns the roles the node is running as
    const SYSTEM_NODE_ROLES = 'system_nodeRoles';

    // Returns the currently connected peers
    const SYSTEM_PEERS = 'system_peers';

    // Get a custom set of properties as a JSON object, defined in the chain spec
    const SYSTEM_PROPERTIES = 'system_properties';

    // Remove a reserved peer
    const SYSTEM_REMOVE_RESERVED_PEER = 'system_removeReservedPeer';

    // Retrieves the version of the node
    const SYSTEM_VERSION = 'system_version';

    /** system end */
}