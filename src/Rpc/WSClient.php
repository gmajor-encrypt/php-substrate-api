<?php

namespace Rpc;

use WebSocket\BadOpcodeException;
use WebSocket\Client as WS;
use Rpc\Substrate\Json2;
use WebSocket\ConnectionException;

class WSClient extends Client
{

    /**
     * Websocket constructor.
     *
     */
    public function __construct()
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
    public function read(string $method, array $params = []): string
    {
        $client = new WS(self::$WS_ENDPOINT);
        $client->send(json_encode(Json2::build($method, $params)));
        return $client->receive();
    }
}
