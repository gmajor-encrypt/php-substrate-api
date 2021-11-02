<?php

namespace Rpc;

use WebSocket\BadOpcodeException;
use WebSocket\Client as WS;
use Rpc\Substrate\json2;
use WebSocket\ConnectionException;

class WSClient extends Client
{

    /**
     * Websocket constructor.
     *
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * subscribe from websocket
     *
     * @param string $method
     * @param array $params
     * @return string
     * @throws BadOpcodeException
     * @throws ConnectionException
     */
    function subscribe (string $method, array $params = []): string
    {
        $client = new WS(self::$WS_ENDPOINT);
        $client->send(json_encode(json2::build($method, $params)));
        return $client->receive();
    }

}