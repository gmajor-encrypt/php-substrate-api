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
```

* Read RPC Data

```php
<?php
use Rpc\SubstrateRpc;
$client = new SubstrateRpc("wss://kusama-rpc.polkadot.io/");
$res = $client->rpc->system->health(); // call rpc system_health
var_dump($res); #{"peers": 31, "isSyncing": false, "shouldHavePeers": true}

```


### Example

More examples can refer to the test file https://github.com/gmajor-encrypt/php-substrate-api/tree/master/test/Rpc

## Test

```
make test
```

## Resources

- [sr25519](https://github.com/gmajor-encrypt/sr25519-bindings)
- [Polkadot.js](http://polkadot.js.org/)
- [substrate.dev](https://docs.substrate.io/v3/runtime/custom-rpcs/)
- [substrate-api-sidecar](https://github.com/paritytech/substrate-api-sidecar)


## License

The package is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT)