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
$hasher = new Hasher();
print_r(StorageKey::encode($hasher,"Timestamp", "now", $metadata, []));
// Staking.Bonded storage key with param accountId
print_r(StorageKey::encode($hasher,"System", "Account", $metadata, ["0x1c79a5ada2ff0d55aaa65dfeaf0cba667babf312f9bf100444279b34cd769e49"]))

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
$hasher = new Hasher();
$wsClient->setSigner(KeyPair::initKeyPair("sr25519", $AliceSeed, $hasher),$hasher);
$result = $wsClient->tx->Balances->transfer($BobId, 12345);
var_dump($result); // transaction hash
$wsClient->close()
````

### Keyring

The Keyring allows you to perform operations on these keys (such as sign/verify) and never exposes the secretKey

to the outside world. Support ed25519(Edwards https://ed25519.cr.yp.to/) or sr25519(
schnorrkel https://github.com/w3f/schnorrkel)

```php
<?php
use Rpc\KeyPair\KeyPair;
$keyPair = KeyPair::initKeyPair("sr25519|ed25519", {$secretKey}, new Hasher());
// signed msg
$signature = $keyPair->sign("msg");
// verify this message
$keyPair->verify($signature, "123");
```

### Contract

#### Metadata support

The metadata is used to describe a contract in a language agnostic way. Metadata can declare the storage and executable
methods and types contained in the contract

We currently support ink metadata v0,v1,v2,v3,v4.

```php
<?php
use Rpc\Contract\Abi\Convert;
use Codec\Base;
use Codec\Types\ScaleInstance;
$content = json_decode(file_get_contents(__DIR__ . '/ink/ink_v0.json'), true);
$metadata = Convert::toLatest($content); // convert metadata to latest version
// reg metadata types
$scale = new ScaleInstance(Base::create());
$metadata->register_type($scale->getGenerator(), "some_prefix");
```

#### Deploy contract

After declaring a Contract class, you can call the new method to create a contract.

About how to build ink contract, you can refer to
this https://docs.substrate.io/tutorials/smart-contracts/prepare-your-first-contract/

Below is an example.

```php
<?php
use Rpc\KeyPair\KeyPair;
use Rpc\SubstrateRpc;
use Rpc\Contract;

$wsClient = new SubstrateRpc($endpoint);
// set deployer keyring
$hasher = new Hasher();
$wsClient->setSigner(KeyPair::initKeyPair("sr25519",$seed, $hasher),$hasher);
$contract = new Contract($wsClient->tx);
// $inputData = constructor_selector + encode(args...)
$result = $contract->new($contract_code, $inputData); // with default option

# If you need to additionally set the gas limit and storageDepositLimit, you can set it like this
$result = $contract->new($contract_code, $inputData,[], ["gasLimit"=>100000,"storageDepositLimit"=>50000]); // with default option
```

#### Read Contract state

Reading the storage on the contract does not consume any gas, so anyone can read the contract.

You can simply read the contract through ```$contract->state->{$method}($param1,$param2)```

```php
<?php
use Rpc\KeyPair\KeyPair;
use Rpc\SubstrateRpc;
use Rpc\Contract;

$wsClient = new SubstrateRpc($endpoint);
// set signer
$hasher = new Hasher();
$wsClient->setSigner(KeyPair::initKeyPair("sr25519", $seed, $hash),$hash);
// get abi
$v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_v4.json'), true));
// register contract type
$v4->register_type($wsClient->tx->codec->getGenerator(), "testAbiMetadataV4Parse");

// read contract
$contract = new Contract($wsClient->tx, $contractAddress, $v4);
// call get method
$execResult = $contract->state->get();
// parse exec Result
$result = ContractExecResult::deserialization($execResult->result);
print_r($result);
```

#### Send Contract transaction

Sending contract transactions is very similar to executing extrinsic. You can simply exec the contract through
```$contract->call->{$method}($param1,$param2,$option=[])```

```php
<?php
use Rpc\KeyPair\KeyPair;
use Rpc\SubstrateRpc;
use Rpc\Contract;
$wsClient = new SubstrateRpc($endpoint);
// set signer
$hasher = new Hasher();
$wsClient->setSigner(KeyPair::initKeyPair("sr25519", $this->AliceSeed, $hasher),$hasher);

// register contract type
$v4 = ContractMetadataV4::to_obj(json_decode(file_get_contents(__DIR__ . '/ink/ink_v4.json'), true));
$v4->register_type($wsClient->tx->codec->getGenerator(), "testAbiMetadataV4Parse");

// send contract transaction
$contract = new Contract($wsClient->tx, $contractAddress, $v4);
$result = $contract->call->flip([]); // with default option

// If you need to additionally set the gas limit and storageDepositLimit, you can set it like this
$result = $contract->call->flip(["storageDepositLimit"=>$limit,["gasLimit"=>["refTime"=>$refTime,"proofSize"=>$proofSize]] ]); 
print_r($result);// extrinsic_hash
```

#### Generate contract address

Since the address algorithm of the contract is fixed, it is easy to calculate the deployed contract address

```php
<?php
use Rpc\Hasher\Hasher;
use Rpc\Contract\Address;
use Codec\Base;
use Codec\Types\ScaleInstance;
$hasher = new Hasher();
$codec = new ScaleInstance(Base::create());
$bytes = $codec->createTypeByTypeString("bytes");
Address::GenerateAddress($hasher, $deployer, $codeHash, $bytes->encode($inputData), $bytes->encode($salt)));

```

### Example

More examples can refer to the test file https://github.com/gmajor-encrypt/php-substrate-api/tree/master/test/Rpc

## Test

```bash
make test
```

## Troubleshooting

### FFI error FFI\Exception: Failed loading '../php-substrate-api/vendor/gmajor/sr25519-bindings/src/Crypto/sr25519.so'

The current default sr25519-bindings FFI is for mac. Unfortunately, php composer currently does not support automatic
compilation after install, so manual compilation is required. You can run this script

```bash
## For darwin
cd vendor/gmajor/sr25519-bindings/go && go build -buildmode=c-shared -o ../src/Crypto/sr25519.dylib .
## For linux 
cd vendor/gmajor/sr25519-bindings/go && go build -buildmode=c-shared -o ../src/Crypto/sr25519.so . 
```

### WebSocket\ConnectionException: Could not open socket to "127.0.0.1:9944"

In the test,The keyPair used in the test process is //Alice, **ws://127.0.0.1:9944** is used by default as the node for
testing SendTransaction. This node can start any private network settings by itself. You can also set the node address
through environment variables.

```base
export RPC_URL=ws://....
```

## Resources

- [sr25519](https://github.com/gmajor-encrypt/sr25519-bindings)
- [polkadot.js](http://polkadot.js.org/)
- [substrate.dev](https://docs.substrate.io/v3/runtime/custom-rpcs/)
- [substrate-api-sidecar](https://github.com/paritytech/substrate-api-sidecar)

## License

The package is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT)