<?php

namespace Rpc\Test;

use Rpc\Config;
use Rpc\Substrate\Method;
use Rpc\WSClient;
use PHPUnit\Framework\TestCase;

final class WebsocketTest extends TestCase
{
    public function testSubscribe ()
    {
        Config::setWSEndPoint("wss://kusama-rpc.polkadot.io/");
        $wsClient = new WSClient();
        $wsClient->subscribe(Method::SYSTEM_HEALTH);
    }
}