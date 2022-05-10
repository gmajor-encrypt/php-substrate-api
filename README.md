# php-substrate-api

---
PHP Substrate RPC Api

## Requirement

php >=8.0 (install ffi https://www.php.net/manual/en/intro.ffi.php)

## Installation

```sh
composer require gmajor/php-substrate-api
```

## Basic Usage

### Autoloading

Codec supports `PSR-4` autoloaders.

```php
<?php
# When installed via composer
require_once 'vendor/autoload.php';
```

### RPC

* Generate HTTP|Websocket Client

```php
<?php
use Rpc\SubstrateRpc;
// http client
$httpClient = new SubstrateRpc("https://kusama-rpc.polkadot.io/");
// websocket client 
$websocketClient = new SubstrateRpc("wss://kusama-rpc.polkadot.io/");
$websocketClient->close(); // close websocket connection
```

* Read RPC Data

```php
<?php
use Rpc\SubstrateRpc;
$client = new SubstrateRpc("wss://kusama-rpc.polkadot.io/");

// call any json rpc you can use $client->rpc->{$pallet_name}->{$method}, like
// call rpc system_health 
$res = $client->rpc->system->health(); 
var_dump($res); #{"peers": 31, "isSyncing": false, "shouldHavePeers": true}
// or call rpc chain_getFinalizedHead
$client->rpc->chain->getFinalizedHead(); 

$client->close(); // do not forget close websocket connection !
```

* Hasher

We currently support 6 hash methods, including Blake2_128，Blake2_256，Twox128，Twox256，Twox64Concat，Blake2_128Concat。

```php
<?php
use Rpc\Hasher\Hasher;

$hasher = new Hasher();
// Blake2_128
$hasher->ByHasherName("Blake2_128", "20be52a5a80cad065651ec35fcb1a212bc669aabb52d68d8780a41e29ec9c83e");
// Blake2_256
$hasher->ByHasherName("Blake2_256", "20be52a5a80cad065651ec35fcb1a212bc669aabb52d68d8780a41e29ec9c83e")
// Twox128
$hasher->TwoxHash("Key", 128)
// Twox128
$hasher->TwoxHash("Sudo", 256)
// XXHash64
$hasher->XXHash64(0, "test");
// Twox64Concat
$hasher->ByHasherName("Twox64Concat", "0xad2ecd66275a1ded")
//  Blake2_128Concat
$hasher->ByHasherName("Blake2_128Concat", "20be")
````

* Storage key

When you access storage using Substrate RPC(like
rpc [state_getStorage](https://polkadot.js.org/docs/substrate/rpc#getstoragechildkey-prefixedstoragekey-key-storagekey-at-hash-optionstoragedata)
, you need to provide the key associated with the item,

```php
<?php
use Rpc\StorageKey;

use Codec\Base;
use Codec\ScaleBytes;
use Codec\Types\ScaleInstance;
use Rpc\StorageKey;

$codec = new ScaleInstance(Base::create());
$metadataV14RawValue = "...." //  from json rpc state_getMetadata
$metadata = $codec->process("metadata", new ScaleBytes($metadataV14RawValue))["metadata"];
// Timestamp.now storage key
print_r(StorageKey::encode("Timestamp", "now", $metadata, [])));
// Staking.Bonded storage key with param accountId
print_r(StorageKey::encode("System", "Account", $metadata, ["0x1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"]))

```

* Json RPC

RPC methods that are Remote Calls available by default and allow you to interact with the actual node, query, and
submit.

```php
// On the api, these are exposed via <client>.rpc.<module>.<method>, like this 
$client->rpc->system->health(); // for rpc system_health
$client->rpc->author->rotateKeys(); // for rpc author_rotateKeys
$client->rpc->state->getMetadata("hash"); // for rpc state_getMetadata
```

All rpc interface has been declare at https://github.com/gmajor-encrypt/php-substrate-api/tree/master/src/Rpc/JsonRpc

More detailed RPC documentation can be found at https://polkadot.js.org/docs/substrate/rpc

* Send extrinsics

Below is a simple example of sending a token, you can use tx.<module>.<method> to send any transaction

```php
<?php
use Rpc\KeyPair\KeyPair;
use Rpc\SubstrateRpc;
$AliceSeed = "0xe5be9a5092b81bca64be81d212e7f2f9eba183bb7a90954f7b76361f6edb5c0a";
$BobId = ["Id" => "8eaf04151687736326c9fea17e25fc5287613693c912909cb226aa4794f26a48"];
$wsClient = new SubstrateRpc($endpoint);
$wsClient->setSigner(KeyPair::initKeyPair("sr25519", $AliceSeed, $wsClient->hasher));
$result = $wsClient->tx->Balances->transfer($BobId, 12345);
var_dump($result); // transaction hash
$wsClient->close()
````

* Keyring


The Keyring allows you to perform operations on these keys (such as sign/verify) and never exposes the secretKey

to the outside world. Support ed25519(Edwards https://ed25519.cr.yp.to/) or sr25519(schnorrkel https://github.com/w3f/schnorrkel)

```php
<?php
use Rpc\KeyPair\KeyPair;
$keyPair = KeyPair::initKeyPair("sr25519|ed25519", {$secretKey}, new Hasher());
// signed msg
$signature = $keyPair->sign("msg");
// verify this message
$keyPair->verify($signature, "123");
```

### Example

More examples can refer to the test file https://github.com/gmajor-encrypt/php-substrate-api/tree/master/test/Rpc

## Test

```
make test
```

## Troubleshooting

### FFI error FFI\Exception: Failed loading '../php-substrate-api/vendor/gmajor/sr25519-bindings/src/Crypto/sr25519.so'

The current default sr25519-bindings FFI is for mac. Unfortunately, php composer currently does not support automatic
compilation after install, so manual compilation is required. You can run this script

```bash
cd vendor/gmajor/sr25519-bindings/go && go build -buildmode=c-shared -o ../src/Crypto/sr25519.so .
```
### WebSocket\ConnectionException: Could not open socket to "127.0.0.1:9944"

In the test,The keyPair used in the test process is //Alice, **ws://127.0.0.1:9944** is used by default as the node for testing SendTransaction.
This node can start any private network settings by itself.
You can also set the node address through environment variables.
```base
export RPC_URL=ws://....
```



## Resources

- [sr25519](https://github.com/gmajor-encrypt/sr25519-bindings)
- [Polkadot.js](http://polkadot.js.org/)
- [substrate.dev](https://docs.substrate.io/v3/runtime/custom-rpcs/)
- [substrate-api-sidecar](https://github.com/paritytech/substrate-api-sidecar)

## License

The package is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT)