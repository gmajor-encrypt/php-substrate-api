<?php

namespace Rpc\Test;

use Rpc\Config;
use Rpc\HttpClient;
use Rpc\Substrate\Method;
use Rpc\WSClient;
use Rpc\SubstrateRpc;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testSubscribe()
    {
        Config::setEndPoint("wss://kusama-rpc.polkadot.io/");
        $wsClient = new WSClient();
        $wsClient->read(Method::SYSTEM_HEALTH);
    }

    public function testHTTPSubscribe()
    {
        Config::setEndPoint("https://kusama-rpc.polkadot.io/");
        $wsClient = new HttpClient();
        $wsClient->read(Method::SYSTEM_HEALTH);
    }

    public function testSubstrateRpc()
    {
        Config::setEndPoint("https://kusama-rpc.polkadot.io/");
        $wsClient = new SubstrateRpc();
        $wsClient->rpc->System->Health();
    }
}
