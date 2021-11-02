<?php

namespace Rpc\Test;

use Rpc\Config;
use Rpc\HttpClient;
use Rpc\Substrate\Method;
use Rpc\WSClient;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testSubscribe ()
    {
        Config::setWSEndPoint("wss://kusama-rpc.polkadot.io/");
        $wsClient = new WSClient();
        $wsClient->subscribe(Method::SYSTEM_HEALTH);
    }

    public function testHTTPSubscribe ()
    {
        Config::setHttpEndPoint("https://kusama-rpc.polkadot.io/");
        $wsClient = new HttpClient();
        print_r($wsClient->subscribe(Method::SYSTEM_HEALTH));
    }
}