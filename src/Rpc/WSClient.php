<?php

namespace Rpc;

use WebSocket\BadOpcodeException;
use WebSocket\Client as WS;
use Rpc\Substrate\Json2;
use WebSocket\ConnectionException;

class WSClient extends Client
{

    public WS $client;

    /**
     * Websocket constructor.
     *
     */
    public function __construct (string $endpoint)
    {
        $this->client = new WS($endpoint);
        parent::__construct($endpoint);
    }

    /**
     * subscribe from websocket
     *
     * @param string $method
     * @param array $params
     * @return array
     * @throws BadOpcodeException|ConnectionException
     */
    public function read (string $method, array $params = []): array
    {
        $this->client->send(json_encode(Json2::build($method, $params)));
        return json_decode($this->client->receive(),true);
    }

    /**
     * close websocket connect
     */
    public function close ()
    {
        $this->client->close();
    }
}
