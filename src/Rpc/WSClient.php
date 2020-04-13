<?php

namespace Rpc;

use WebSocket\BadOpcodeException;
use WebSocket\Client;
use Rpc\Substrate\Json2;
use WebSocket\ConnectionException;

class WSClient extends Utils
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
     * subscribe
     *
     * @param string $method
     * @param array $params
     * @throws BadOpcodeException
     * @throws ConnectionException
     */
    function subscribe (string $method, array $params = [])
    {
        $client = new Client(self::$WS_ENDPOINT);
        $client->send(json_encode(Json2::build($method, $params)));
        $data = [];
        echo $client->receive();
    }

}